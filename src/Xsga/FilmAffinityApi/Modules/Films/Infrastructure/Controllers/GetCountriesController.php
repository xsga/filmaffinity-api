<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Countries\Countries;

final class GetCountriesController extends AbstractController
{
    /**
     * @Inject
     */
    private Countries $countries;

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->countries->getAll());
    }
}
