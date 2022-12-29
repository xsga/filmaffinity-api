<?php

/**
 * TokenMiddleware.
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
use Xsga\FilmAffinityApi\Exceptions\ApiResourceDisabledException;

/**
 * Class TokenMiddleware.
 */
final class TokenMiddleware
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private string $securityType
    ) {
    }

    /**
     * Invoke method.
     *
     * @throws ApiResourceDisabledException GetToken API's resource disabled.
     */
    public function __invoke(Request $request, Handler $handler): Response
    {
        if ($this->securityType !== 'token') {
            $errorMsg = 'Get token resource disabled. Security type must be "token"';
            $this->logger->error($errorMsg);
            throw new ApiResourceDisabledException($errorMsg, 1011);
        }//end if

        return $handler->handle($request);
    }
}
