<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Users\GetToken;

final class GetTokenController extends AbstractController
{
    /**
     * @Inject
     */
    private GetToken $getToken;

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJsonInput($request->getBody(), 'get.token.schema');

        $body = $request->getParsedBody();

        $token          = [];
        $token['token'] = $this->getToken->get($body['user'], $body['password']);

        return $this->writeResponse($response, $token);
    }
}
