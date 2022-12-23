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
use Xsga\FilmAffinityApi\Helpers\Security\SecurityInterface;

/**
 * Class SecurityMiddleware.
 */
final class SecurityMiddleware
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private SecurityInterface $security,
        private string $securityType
    ) {
    }

    /**
     * Invoke method.
     *
     * @throws AuthHeaderNotFoundException Authorization header not found.
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
                $user = $this->security->basic($authHeader);
                break;
            case 'token':
                $this->logger->debug('Applying TOKEN security');
                $user = $this->security->token($authHeader);
                break;
        }//end switch

        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
}
