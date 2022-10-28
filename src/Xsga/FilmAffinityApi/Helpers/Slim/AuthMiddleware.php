<?php

/**
 * AuthMiddleware.
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

/**
 * Class AuthMiddleware.
 */
final class AuthMiddleware
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
     * Constructor.
     *
     * @param LoggerInterface $logger LoggerInterface instance.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
        $this->logger->debug('AUTH');
        $this->logger->debug('User: ' . $request->getAttribute('user'));

        return $handler->handle($request);
    }
}
