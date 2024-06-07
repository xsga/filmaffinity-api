<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Slim\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Log\LoggerInterface;
use Slim\Routing\RouteContext;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Exceptions\ApiResourceDisabledException;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Exceptions\AuthHeaderNotFoundException;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Exceptions\RouteContextException;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Exceptions\SecurityException;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\BasicSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\TokenSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;

final class SecurityMiddleware implements MiddlewareInterface
{
    private string $getTokenRoute = 'get_token';

    public function __construct(
        private LoggerInterface $logger,
        private BasicSecurityService $basicSecurity,
        private TokenSecurityService $tokenSecurity,
        private SecurityTypes $securityType
    ) {
    }

    public function process(Request $request, Handler $handler): Response
    {
        $this->logger->debug('Init middleware SECURITY');

        $routeName = $this->getRouteName($request);
        $request   = $request->withAttribute('routeName', $routeName);

        if ($this->isNonSecuredRequest($routeName)) {
            if ($routeName === $this->getTokenRoute) {
                $this->validateSecurityType();
            }

            $this->logger->debug('Non secured request, not applying security');
            $this->logger->debug('End middleware SECURITY');

            return $handler->handle($request);
        }

        $authHeader = $this->getAuthHeader($request);

        $userDataToken = match ($this->securityType) {
            SecurityTypes::BASIC => $this->basicSecurity->apply($authHeader),
            SecurityTypes::TOKEN => $this->tokenSecurity->apply($authHeader)
        };

        $this->validateUserDataToken($userDataToken);

        $request = $request->withAttribute('userDataToken', $userDataToken);

        $this->logger->debug('End middleware SECURITY');

        return $handler->handle($request);
    }

    private function getRouteName(Request $request): string
    {
        $route = RouteContext::fromRequest($request)->getRoute();

        if (is_null($route)) {
            $errorMsg = "Error getting Slim route context";
            $this->logger->error($errorMsg);
            throw new RouteContextException($errorMsg, 1021);
        }

        return $route->getName() ?? '';
    }

    private function isNonSecuredRequest(string $routeName): bool
    {
        return match ($routeName) {
            $this->getTokenRoute => true,
            default => false
        };
    }

    private function validateSecurityType(): void
    {
        if ($this->securityType !== SecurityTypes::TOKEN) {
            $errorMsg = "The 'getToken' resource is not available. API security type must be 'token'";
            $this->logger->error($errorMsg);
            throw new ApiResourceDisabledException($errorMsg, 1009);
        }
    }

    private function getAuthHeader(Request $request): string
    {
        if (empty($request->getHeader('Authorization'))) {
            $errorMsg = 'Error validating authorization header, not found';
            $this->logger->error($errorMsg);
            throw new AuthHeaderNotFoundException($errorMsg, 1002);
        }

        return $request->getHeader('Authorization')[0];
    }

    private function validateUserDataToken(?UserDataTokenDto $userDataToken): void
    {
        if ($userDataToken === null) {
            $errorMsg = "Error applying application security";
            $this->logger->error($errorMsg);
            throw new SecurityException($errorMsg, 1008);
        }
    }
}
