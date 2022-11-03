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
use Xsga\FilmAffinityApi\Business\Users\GetUser;

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
     * Get user service.
     *
     * @var GetUser
     *
     * @access private
     */
    private $getUser;

    /**
     * Authorizated role.
     *
     * @var string
     *
     * @access private
     */
    private $authRole;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger  LoggerInterface instance.
     * @param GetUser         $getUser GetUser instance.
     * @param string          $role    Authorizated role.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, GetUser $getUser, string $role)
    {
        $this->logger   = $logger;
        $this->getUser  = $getUser;
        $this->authRole = strtolower($role);
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
        $this->logger->debug('Authorizaed role: ' . $this->authRole);

        $user = $request->getAttribute('user');

        if ($user === null) {
            return $handler->handle($request);
        }//end if

        $userDto = $this->getUser->byEmail($user);

        $this->logger->debug('User "' . $user . '" with role "' . $userDto->role . '"');

        return $handler->handle($request);
    }
}
