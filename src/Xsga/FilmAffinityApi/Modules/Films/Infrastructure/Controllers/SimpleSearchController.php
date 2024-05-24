<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Search\SimpleSearch;
use Xsga\FilmAffinityApi\Dto\SearchDto;

final class SimpleSearchController extends AbstractController
{
    /**
     * @Inject
     */
    private SimpleSearch $simpleSearch;

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJsonInput($request->getBody(), 'simple.search.schema');

        $body = $request->getParsedBody();

        $searchDto = new SearchDto();
        $searchDto->searchText = $body['text'];

        return $this->writeResponse($response, $this->simpleSearch->search($searchDto));
    }
}
