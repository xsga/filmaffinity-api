<?php

/**
 * AdvancedSearchController.
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
use Xsga\FilmAffinityApi\Business\Search\AdvancedSearch;
use Xsga\FilmAffinityApi\Dto\AdvSearchDto;

/**
 * Class AdvancedSearchController.
 */
final class AdvancedSearchController extends AbstractController
{
    /**
     * Advanced search service.
     *
     * @Inject
     * @var AdvancedSearch
     *
     * @access private
     */
    private $advancedSearch;

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
        $this->validateJsonInput($request->getBody(), 'advanced.search.schema');

        $body = json_decode($request->getBody(), true);

        $searchDto = new AdvSearchDto();

        $searchDto->searchText            = $body['text'];
        $searchDto->searchTypeTitle       = $body['title'] ?? false;
        $searchDto->searchTypeDirector    = $body['director'] ?? false;
        $searchDto->searchTypeCast        = $body['cast'] ?? false;
        $searchDto->searchTypeScreenplay  = $body['screenplay'] ?? false;
        $searchDto->searchTypePhotography = $body['photography'] ?? false;
        $searchDto->searchTypeSoundtrack  = $body['soundtrack'] ?? false;
        $searchDto->searchTypeProducer    = $body['producer'] ?? false;
        $searchDto->searchGenre           = $body['genre'] ?? '';
        $searchDto->searchCountry         = $body['country'] ?? '';
        $searchDto->searchYearFrom        = $body['year_from'] ?? 0;
        $searchDto->searchYearTo          = $body['year_to'] ?? 0;

        return $this->writeResponse($response, $this->advancedSearch->search($searchDto));
    }
}
