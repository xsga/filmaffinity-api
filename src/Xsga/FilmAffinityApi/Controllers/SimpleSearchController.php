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
     * @var SimpleSearch
     *
     * @access private
     */
    private $simpleSearch;

    /**
     * Search.
     *
     * @param Request  $request  Request.
     * @param Response $response Response.
     *
     * @return Response
     *
     * @access public
     */
    public function search(Request $request, Response $response): Response
    {
        $this->validateJsonInput($request->getBody(), 'simple.search.schema');

        $body = json_decode($request->getBody(), true);

        $searchDto = new SearchDto();
        $searchDto->searchText = $body['text'];

        return $this->writeResponse($response, $this->simpleSearch->search($searchDto));
    }
}
