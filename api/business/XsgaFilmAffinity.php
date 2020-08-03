<?php
/**
 * XsgaFilmAffinity.
 * 
 * This file contains he XsgaFilmAffinity class. This class provides two public methods:
 * 
 *  - Search   : executes a search in FilmAffinity web page and return the results in JSON format.
 *  - LoadFilm : executes a search in FilmAffinty web page by filmId and return the film information in JSON format.
 * 
 * PHP Version 7
 * 
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace api\business;

/**
 * Import namespaces.
 */
use xsgaphp\rest\XsgaRestWrapper;
use xsgaphp\exceptions\XsgaPageNotFoundException;
use xsgaphp\mvc\XsgaAbstractClass;
use api\model\dto\AdvSearchDto;
use api\model\dto\SearchResultsDto;
use api\model\dto\SingleSearchResultDto;
use api\model\dto\SearchDto;
use api\model\dto\FilmDto;

/**
 * Class XsgaFilmAffinity.
 */
class XsgaFilmAffinity extends XsgaAbstractClass
{
    
    
    /**
     * Search.
     *
     * @param SearchDto $searchDto Search DTO.
     *
     * @return SearchResultsDto
     *
     * @access public
     */
    public function search($searchDto)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Prepare search string.
        $origSearchText = $searchDto->searchText;
        $searchText     = trim($searchDto->searchText);
        $searchText     = str_replace(' ', '+', $searchText);
        
        // Get url.
        $urlSearch = FA_SEARCH_URL;
        $urlSearch = str_replace('{1}', $searchText, $urlSearch);
        $urlSearch = FA_BASE_URL.$urlSearch;
        
        // Logger.
        $this->logger->debug('Search URL: '.$urlSearch);
        
        // Get search results.
        $out = $this->getSearchResults($urlSearch, $origSearchText);
        
        // Logger.
        $this->logger->debugEnd();
    
        return $out;
    
    }//end search()
    
    
    /**
     * Advanced search.
     *
     * @param AdvSearchDto $advSearchDto Advanced search data.
     *
     * @return SearchResultsDto
     *
     * @access public
     */
    public function advancedSearch(AdvSearchDto $advSearchDto)
    {
    
        // Logger.
        $this->logger->debugInit();
    
        // Prepare search string.
        $origSearchText           = $advSearchDto->searchText;
        $advSearchDto->searchText = trim($advSearchDto->searchText);
        $advSearchDto->searchText = str_replace(' ', '+', $advSearchDto->searchText);
        
        // Prepare search type.
        $searchType = '';
        
        if ($advSearchDto->searchTypeTitle === true) {
            $searchType .= '&stype[]=title';
        }//end if
        
        if ($advSearchDto->searchTypeDirector === true) {
            $searchType .= '&stype[]=director';
        }//end if
        
        if ($advSearchDto->searchTypeCast === true) {
            $searchType .= '&stype[]=cast';
        }//end if
        
        if ($advSearchDto->searchTypeSoundtrack === true) {
            $searchType .= '&stype[]=music';
        }//end if
        
        if ($advSearchDto->searchTypeScreenplay === true) {
            $searchType .= '&stype[]=script';
        }//end if
        
        if ($advSearchDto->searchTypePhotography === true) {
            $searchType .= '&stype[]=photo';
        }//end if
        
        if ($advSearchDto->searchTypeProducer === true) {
            $searchType .= '&stype[]=producer';
        }//end if
        
        // By default, search in title.
        if ($searchType === '') {
            
            $this->logger->warn('No search type found. Set the default search type: title');
            
            $searchType = '&stype[]=title';
        }//end if
        
        // Get url.
        $urlSearch = FA_ADV_SEARCH_URL;
        $urlSearch = str_replace('{1}', $advSearchDto->searchText, $urlSearch);
        $urlSearch = str_replace('{2}', $searchType, $urlSearch);
        $urlSearch = str_replace('{3}', $advSearchDto->searchCountry, $urlSearch);
        $urlSearch = str_replace('{4}', $advSearchDto->searchGenre, $urlSearch);
        $urlSearch = str_replace('{5}', $advSearchDto->searchYearFrom, $urlSearch);
        $urlSearch = str_replace('{6}', $advSearchDto->searchYearTo, $urlSearch);
        $urlSearch = FA_BASE_URL.$urlSearch;
    
        // Logger.
        $this->logger->debug('Search URL: '.$urlSearch);
    
        // Get search results.
        $out = $this->getSearchResults($urlSearch, $origSearchText);
    
        // Logger.
        $this->logger->debugEnd();
    
        return $out;
    
    }//end advancedSearch()
    
    
    /**
     * Load film.
     *
     * @param integer $filmId Film id.
     *
     * @return FilmDto
     *
     * @access public
     */
    public function loadFilm($filmId)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get url.
        $urlFilm = str_replace('{1}', $filmId, FA_FILM_URL);
        $urlFilm = FA_BASE_URL.$urlFilm;
        
        // Logger.
        $this->logger->debug('Film URL: '.$urlFilm);
        
        // Get page content.
        $filmContent = $this->getPageContent($urlFilm);
        
        // Disable parse output errors.
        libxml_use_internal_errors(true);
        
        // New DOMDocument instance.
        $document = new \DOMDocument();
        
        // Load film data page into DOMDocument.
        $document->loadHtml(mb_convert_encoding($filmContent, 'HTML-ENTITIES', 'UTF-8'));
        
        // New OMXPath instance.
        $domXpath = new \DOMXPath($document);
        
        // Initialize output array.
        $out = new FilmDto();
        
        // Get movie title and rating.
        $out->title  = trim($document->getElementById('main-title')->nodeValue);
        $out->rating = trim($document->getElementById('movie-rat-avg')->nodeValue);
        
        // Get movie cover URL and cover filename.
        $result = $domXpath->query("//a[@class = 'lightbox']");
        
        if ($result->length === 0) {
            $out->coverUrl  = null;
            $out->coverFile = null;
        } else {
            $coverURL      = trim($result->item(0)->getAttribute('href'));
            $coverFile     = explode('/', $coverURL);
            $out->coverUrl = $coverURL;
            $out->coverFile= end($coverFile);
        }//end if
        
        // Execute XPaths queries.
        $filter  = $domXpath->query("//dl[@class = 'movie-info']");
        $filter2 = $domXpath->query("./dt[not(@class) or @class != 'akas'] | ./dd[not(@class) or @class != 'akas']", $filter->item(0));
        
        // Initilize auxiliar film information array.
        $infoAux = array();
        
        // Get auxiliar film information array.
        for ($i = 0; $i < $filter2->length; $i++) {
            $infoAux[] = trim($filter2->item($i)->nodeValue);
        }//end for
        
        // Initialize film information array.
        $info = array();
        
        // Get film information array.
        for ($i = 0; $i < count($infoAux); $i = $i + 2) {
            $info[$infoAux[$i]] = $infoAux[$i + 1];
        }//end for
                
        // Enable parse output errors.
        libxml_use_internal_errors(false);
        
        // Prepare film actors.
        $actors     = (isset($info['Reparto']) === true) ? explode(',', $info['Reparto']) : array();
        $actorsTrim = array_map('trim', $actors);
        
        // Prepare film genres.
        $genres     = (isset($info['Género']) === true) ? explode('.', str_replace('|', '.', $info['Género'])) : array();
        $genresTrim = array_map('trim', $genres);
        
        // Prepare film directors.
        $directors     = (isset($info['Dirección']) === true) ? explode(',', $info['Dirección']) : array();
        $directorsTrim = array_map('trim', $directors);
        
        // Set film information into output array.
        $out->filmAfinityId = (int)$filmId;
        $out->originalTitle = (isset($info['Título original']) === true) ? trim(str_replace('aka', '', $info['Título original'])) : '';
        $out->year          = (isset($info['Año']) === true) ? $info['Año'] : '';
        $out->duration      = (isset($info['Duración']) === true) ? trim(str_replace('min.', '', $info['Duración'])) : '';
        $out->country       = (isset($info['País']) === true) ? trim(trim($info['País'], chr(0xC2).chr(0xA0))) : '';
        $out->directors     = $directorsTrim;
        $out->screenplay    = (isset($info['Guion']) === true) ? $info['Guion'] : '';
        $out->producer      = (isset($info['Productora']) === true) ? $info['Productora'] : '';
        $out->soundtrack    = (isset($info['Música']) === true) ? $info['Música'] : '';
        $out->photography   = (isset($info['Fotografía']) === true) ? $info['Fotografía'] : '';
        $out->cast          = $actorsTrim;
        $out->genres        = $genresTrim;
        $out->officialweb   = (isset($info['Web oficial']) === true) ? $info['Web oficial'] : '';
        $out->synopsis      = (isset($info['Sinopsis']) === true) ? trim(str_replace('(FILMAFFINITY)', '', $info['Sinopsis'])) : '';
        
        // Logger.
        $this->logger->debugEnd();
        
        return $out;
    
    }//end loadFilm()
    
    
    /**
     * Get search results.
     * 
     * @param string $urlSearch  Search URL.
     * @param string $searchText Search text.
     * 
     * @return SearchResultsDto
     * 
     * @access private
     */
    private function getSearchResults($urlSearch, $searchText)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get search results page.
        $searchResultPage = $this->getPageContent($urlSearch);
        
        // Disable parse output errors.
        libxml_use_internal_errors(true);
        
        // New DOMDocument instance.
        $document = new \DOMDocument();
        
        // Load search results into the DOMDocument.
        $document->loadHtml(mb_convert_encoding($searchResultPage, 'HTML-ENTITIES', 'UTF-8'));
        
        // New DOMXPath instance.
        $domXpath = new \DOMXPath($document);
        
        // Initialize output.
        $out = new SearchResultsDto();
        
        $result = $domXpath->query("//meta[@property = 'og:title']");
        
        if (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
        
            // Logger.
            $this->logger->info('FilmAffinity search ("'.$searchText.'"): found 1 results');
        
            // Get film ID and title.
            $idAux    = $domXpath->query("//meta[@property = 'og:url']")->item(0)->getAttribute('content');
            $titleAux = $domXpath->query("//meta[@property = 'og:title']")->item(0)->getAttribute('content');
        
            $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $titleAux)));
            $id    = trim(str_replace('https://www.filmaffinity.com/es/film', '', str_replace('.html', '', $idAux)));
        
            // Put result into single result DTO.
            $searchResult        = new SingleSearchResultDto();
            $searchResult->id    = (int)$id;
            $searchResult->title = $title;
            
            // Put single result into output DTO.
            $out->total     = 1;
            $out->results[] = $searchResult;
        
        } else {
        
            // Execute XPath query and store results into variable.
            $filter = $domXpath->query("//div[contains(@class, 'movie-card')]");
        
            // Set total search results.
            $totalResults = $filter->length;
            
            // Set total results.
            $out->total = $totalResults;
            
            // Logger.
            $this->logger->info('FilmAffinity search ("'.$searchText.'"): '.$totalResults.' results found');
        
            if ($totalResults > 0) {
        
                for ($i = 0; $i < $totalResults; $i++) {
                    
                    // Execute new XPath query.
                    $filter2 = $domXpath->query("//div[@class = 'mc-title']");
                    $filter3 = $domXpath->query("//div[@class = 'ye-w']");
                    
                    // Get result data.
                    $searchResult        = new SingleSearchResultDto();
                    $searchResult->id    = (int)trim($filter->item($i)->getAttribute('data-movie-id'));
                    $searchResult->title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $filter2->item($i)->nodeValue)));
                    
                    if (empty($filter3->item($i)->nodeValue) === false) {
                        $searchResult->title .= ' ('.$filter3->item($i)->nodeValue.')';
                    }//end if
                    
                    // Put single result data into output DTO.
                    $out->results[] = $searchResult;
                    
                }//end for
        
            }//end if
            
        }//end if
        
        // Enable parse output errors.
        libxml_use_internal_errors(false);
        
        // Logger.
        $this->logger->debugEnd();
        
        return $out;
        
    }//end getSearchResults()
    
    
    /**
     * Get page content.
     * 
     * @param string $url Page URL.
     * 
     * @return string
     * 
     * @throws XsgaPageNotFoundException
     * 
     * @access private
     */
    private function getPageContent($url)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get REST wrapper.
        $restWrapper = new XsgaRestWrapper();
        
        // Get response.
        $pageContent = $restWrapper->getPageContent($url);
        
        if (empty($pageContent) === true) {
            
            // Error message.
            $errorMsg = 'FilmAffinity page can not be loaded';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaPageNotFoundException($errorMsg);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $pageContent;
        
    }//end getPageContent()
    
    
}//end XsgaFilmAffinity class
