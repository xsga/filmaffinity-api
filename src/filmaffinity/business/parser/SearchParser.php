<?php
/**
 * SearchParser.
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
namespace api\filmaffinity\business\parser;

/**
 * Import dependencies.
 */
use api\filmaffinity\business\parser\AbstractParser;
use api\filmaffinity\business\parser\XpathCons;
use api\filmaffinity\model\SearchResultsDto;
use api\filmaffinity\model\SingleSearchResultDto;

/**
 * Class SearchParser.
 */
class SearchParser extends AbstractParser
{

    
    /**
     * Get simple search results.
     * 
     * @return SearchResultsDto
     * 
     * @access public
     */
    public function getSimpleSearchResultsDto() : SearchResultsDto
    {
        // Logger.
        $this->logger->debugInit();

        $result = $this->getData(XpathCons::SEARCH_TYPE, false);
        
        if (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
        
            // Single result.
            $out = $this->simpleSearchSingleResult($result);
        
        } else {
        
            // Multiple results.
            $out = $this->simpleSearchMultipleResults();
            
        }//end if
        
        // Logger.
        $this->logger->info('FilmAffinity search: '.$out->total.' results found');
        $this->logger->debugEnd();

        return $out;

    }//end getSimpleSearchResultsDto()


    /**
     * Get advanced search results.
     * 
     * @return SearchResultsDto
     * 
     * @access public
     */
    public function getAdvSearchResultsDto() : SearchResultsDto
    {
        // Logger.
        $this->logger->debugInit();

        // Initialize output.
        $out = new SearchResultsDto();
        
        $result = $this->getData(XpathCons::SEARCH_ADV, false);
        
        // Set total search results.
        $totalResults = $result->length;

        // Set total results.
        $out->total = $totalResults;

        for ($i = 0; $i < $totalResults; $i++) {
                    
            // DOMDocument instance.
            $dom = new \DOMDocument();

            // Add search result as root child. 
            $dom->appendChild($dom->importNode($result->item($i), true));

            // Gets film release date.
            $data     = strtolower(preg_replace('~\s+~u', '', $dom->textContent));
            $dataAux  = \preg_replace("#\(\d{4}\)#", '#', $data);
            $position = strpos($dataAux, '#');
            $year     = \substr($data, $position, 6);
            
            // New DOMXPath instance.
            $domXpath = new \DOMXPath($dom);

            // Get data.
            $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);
            $idResult    = $domXpath->query(XpathCons::SEARCH_ID);

            // Prepare data.
            $title = $titleResult->item(0)->nodeValue;
            $id    = $idResult->item(0)->getAttribute('data-movie-id');

            // Set result data.
            $searchResult         = new SingleSearchResultDto();
            $searchResult->id     = (int)trim($id);
            $searchResult->title  = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)).' '.$year);
            
            // Put single result data into output DTO.
            $out->results[] = $searchResult;
            
        }//end for
        
        // Logger.
        $this->logger->info('FilmAffinity search: '.$totalResults.' results found');
        $this->logger->debugEnd();

        return $out;

    }//end getAdvSearchResultsDto()


    /**
     * Gets simple search single result.
     * 
     * @param \DOMNodeList $data Single result object.
     * 
     * @return SearchResultsDto
     * 
     * @access private
     */
    private function simpleSearchSingleResult(\DOMNodeList $data) : SearchResultsDto
    {
        // Logger.
        $this->logger->debugInit();

        // Search film ID.
        $idSearch = $this->getData(XpathCons::SEARCH_ID_SINGLE, false);
        
        // Get film ID and title.
        $id    = $idSearch->item(0)->getAttribute('content');
        $title = $data->item(0)->getAttribute('content');
        
        // Prepare film ID and title.
        $id    = trim(str_replace($this->baseUrl.'film', '', str_replace('.html', '', $id)));
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

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end simpleSearchSingleResult()


    /**
     * Gets simple search multiple results.
     * 
     * @return SearchResultsDto
     * 
     * @access private
     */
    private function simpleSearchMultipleResults() : SearchResultsDto
    {
        // Logger.
        $this->logger->debugInit();

        // Get search results.
        $searchResults = $this->getData(XpathCons::SEARCH_RESULTS, false);
        
        // Gets DTO instance.
        $out = new SearchResultsDto();
        
        // Set total results.
        $out->total = $searchResults->length;
        
        for ($i = 0; $i < $out->total; $i++) {
                
            // DOMDocument instance.
            $dom = new \DOMDocument();

            // Add search result as root child.
            $dom->appendChild($dom->importNode($searchResults->item($i), true));
            
            // New DOMXPath instance.
            $domXpath = new \DOMXPath($dom);

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
            $searchResult->title .= ' ('.trim($year).')';
            
            // Put single result data into output DTO.
            $out->results[] = $searchResult;
            
        }//end for

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end simpleSearchMultipleResults()
    

}//end SearchParser class
