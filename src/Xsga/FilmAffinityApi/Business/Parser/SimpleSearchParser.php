<?php

/**
 * SimpleSearchParser.
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
namespace Xsga\FilmAffinityApi\Business\Parser;

/**
 * Import dependencies.
 */
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Xsga\FilmAffinityApi\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Dto\SingleSearchResultDto;

/**
 * Class SimpleSearchParser.
 */
final class SimpleSearchParser extends AbstractParser
{
    /**
     * Get simple search results.
     *
     * @return SearchResultsDto
     *
     * @access public
     */
    public function getSimpleSearchResultsDto(): SearchResultsDto
    {
        $result = $this->getData(XpathCons::SEARCH_TYPE, false);

        if (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
            // Single result.
            $out = $this->simpleSearchSingleResult($result);
        } else {
            // Multiple results.
            $out = $this->simpleSearchMultipleResults();
        }//end if

        $this->logger->info('FilmAffinity search: ' . $out->total . ' results found');

        return $out;
    }

    /**
     * Gets simple search single result.
     *
     * @param DOMNodeList $data Single result object.
     *
     * @return SearchResultsDto
     *
     * @access private
     */
    private function simpleSearchSingleResult(DOMNodeList $data): SearchResultsDto
    {
        // Search film ID.
        $idSearch = $this->getData(XpathCons::SEARCH_ID_SINGLE, false);

        // Get film ID and title.
        $idAux   = $idSearch->item(0)->getAttribute('content');
        $idArray = explode('/', $idAux);
        $title   = $data->item(0)->getAttribute('content');

        // Prepare film ID and title.
        $id    = trim(str_replace('film', '', str_replace('.html', '', end($idArray))));
        $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));

        // Put result into single result DTO.
        $searchResult        = new SingleSearchResultDto();
        $searchResult->id    = (int)$id;
        $searchResult->title = $title;

        // Gets DTO instance.
        $out = new SearchResultsDto();

        // Put single result into output DTO.
        $out->total     = 1;
        $out->results[] = $searchResult;

        return $out;
    }

    /**
     * Gets simple search multiple results.
     *
     * @return SearchResultsDto
     *
     * @access private
     */
    private function simpleSearchMultipleResults(): SearchResultsDto
    {
        // Get search results.
        $searchResults = $this->getData(XpathCons::SEARCH_RESULTS, false);

        // Gets DTO instance.
        $out = new SearchResultsDto();

        // Set total results.
        $out->total = $searchResults->length;

        for ($i = 0; $i < $out->total; $i++) {
            // DOMDocument instance.
            $dom = new DOMDocument();

            // Add search result as root child.
            $dom->appendChild($dom->importNode($searchResults->item($i), true));

            // New DOMXPath instance.
            $domXpath = new DOMXPath($dom);

            // Get data.
            $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);
            $yearResult  = $domXpath->query(XpathCons::SEARCH_YEAR);
            $idResult    = $domXpath->query(XpathCons::SEARCH_ID);

            // Prepare data.
            $title = $titleResult->item(0)->nodeValue;
            $year  = $yearResult->item(0)->nodeValue;
            $id    = $idResult->item(0)->getAttribute('data-movie-id');

            // Set result data.
            $searchResult         = new SingleSearchResultDto();
            $searchResult->id     = (int)trim($id);
            $searchResult->title  = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));
            $searchResult->title .= ' (' . trim($year) . ')';

            // Put single result data into output DTO.
            $out->results[] = $searchResult;
        }//end for

        return $out;
    }
}
