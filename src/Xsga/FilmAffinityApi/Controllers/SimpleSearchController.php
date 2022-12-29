<?php

/**
 * SimpleSearchController.
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
namespace Xsga\FilmAffinityApi\Controllers;

/**
 * Import dependencies.
 */
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Search\SimpleSearch;
use Xsga\FilmAffinityApi\Dto\SearchDto;

/**
 * Class SimpleSearchController.
 */
final class SimpleSearchController extends AbstractController
{
    /**
     * Simple search service.
     *
     * @Inject
     */
    private SimpleSearch $simpleSearch;

    /**
     * Search.
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJsonInput($request->getBody(), 'simple.search.schema');

        $body = $request->getParsedBody();

        $searchDto = new SearchDto();
        $searchDto->searchText = $body['text'];

        return $this->writeResponse($response, $this->simpleSearch->search($searchDto));
    }
}
