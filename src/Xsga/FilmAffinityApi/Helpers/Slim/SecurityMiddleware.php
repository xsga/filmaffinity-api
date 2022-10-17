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
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;

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
    public function __construct(LoggerInterface $logger, string $securityType)
    {
        $this->logger       = $logger;
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
     * @access public
     */
    public function __invoke(Request $request, Handler $handler): Response
    {
        $this->logger->debug('Security middleware');

        if ($this->securityType === 'none') {
            $this->logger->debug('Not applying security');
            return $handler->handle($request);
        }//end if

        $authHeader = $request->getHeader('Authorization');

        if (empty($authHeader)) {
            throw new Exception();
        }//end if

        $auth = $authHeader[0];

        $this->logger->debug('Authorization header: ' . $auth);

        return $handler->handle($request);
    }
}
