<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\GetTokenService;

final class GetTokenController extends AbstractController
{
    public function __construct(private GetTokenService $getToken)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJson((string)$request->getBody(), 'get.token.schema');

        $body = $request->getParsedBody();

        // TODO: custom response.
        $token          = [];
        $token['token'] = $this->getToken->get($body['user'], $body['password']);

        return $this->writeResponse($response, $token);
    }
}
