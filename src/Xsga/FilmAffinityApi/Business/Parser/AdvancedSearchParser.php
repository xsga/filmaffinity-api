<?php

/**
 * AdvancedSearchParser.
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
use DOMXPath;
use Xsga\FilmAffinityApi\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Dto\SingleSearchResultDto;

/**
 * Class AdvancedSearchParser.
 */
final class AdvancedSearchParser extends AbstractParser
{
    /**
     * Get advanced search results.
     *
     * @return SearchResultsDto
     *
     * @access public
     */
    public function getAdvSearchResultsDto(): SearchResultsDto
    {
        // Initialize output.
        $out = new SearchResultsDto();

        $result = $this->getData(XpathCons::SEARCH_ADV, false);

        // Set total search results.
        $totalResults = $result->length;

        // Set total results.
        $out->total = $totalResults;

        for ($i = 0; $i < $totalResults; $i++) {
            // DOMDocument instance.
            $dom = new DOMDocument();

            // Add search result as root child.
            $dom->appendChild($dom->importNode($result->item($i), true));

            // Gets film release date.
            $data     = strtolower(preg_replace('~\s+~u', '', $dom->textContent));
            $dataAux  = preg_replace("#\(\d{4}\)#", '#', $data);
            $position = strpos($dataAux, '#');
            $year     = substr($data, $position, 6);

            // New DOMXPath instance.
            $domXpath = new DOMXPath($dom);

            // Get data.
            $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);
            $idResult    = $domXpath->query(XpathCons::SEARCH_ID);

            // Prepare data.
            $title = $titleResult->item(0)->nodeValue;
            $id    = $idResult->item(0)->getAttribute('data-movie-id');

            // Set result data.
            $searchResult         = new SingleSearchResultDto();
            $searchResult->id     = (int)trim($id);
            $searchResult->title  = trim(str_replace('  ', ' ', trim(str_replace('   ', ' ', $title))) . ' ' . $year);

            // Put single result data into output DTO.
            $out->results[] = $searchResult;
        }//end for

        $this->logger->info("FilmAffinity search: $totalResults results found");

        return $out;
    }
}
