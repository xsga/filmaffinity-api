<?php

/**
 * SecurityMiddleware.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Helpers\Slim;

/**
 * Import dependencies.
 */
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Xsga\FilmAffinityApi\Exceptions\AuthHeaderNotFoundException;
use Xsga\FilmAffinityApi\Helpers\Security\Security;

/**
 * Class SecurityMiddleware.
 */
final class SecurityMiddleware
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Security services.
     *
     * @var Security
     *
     * @access private
     */
    private $security;

    /**
     * Security type.
     *
     * @var string
     *
     * @access private
     */
    private $securityType;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger       LoggerInterface instance.
     * @param string          $securityType API security type.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, Security $security, string $securityType)
    {
        $this->logger       = $logger;
        $this->security     = $security;
        $this->securityType = $securityType;
    }

    /**
     * Invoke method.
     *
     * @param Request $request API request.
     * @param Handler $handler Request handler.
     *
     * @return Response
     *
     * @throws AuthHeaderNotFoundException Authorization header not found.
     *
     * @access public
     */
    public function __invoke(Request $request, Handler $handler): Response
    {
        if ($this->securityType === 'none') {
            $this->logger->debug('Not applying security');
            return $handler->handle($request);
        }//end if

        $authHeader = $request->getHeader('Authorization');

        if (empty($authHeader)) {
            $errorMsg = 'Authorization header not found';
            $this->logger->error($errorMsg);
            throw new AuthHeaderNotFoundException($errorMsg, 1012);
        }//end if

        $authHeader = $authHeader[0];

        switch ($this->securityType) {
            case 'basic':
                $this->logger->debug('Applying BASIC security');
                $this->security->basic($authHeader);
                break;
            case 'token':
                $this->logger->debug('Applying TOKEN security');
                $this->security->token($authHeader);
                break;
        }//end switch

        return $handler->handle($request);
    }
}
