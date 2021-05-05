<?php
/**
 * XsgaFilmAffinity.
 * 
 * This file contains the XsgaFilmAffinity class. This class provides three public methods:
 * 
 *  - Search         : executes a search in FilmAffinity web page using a single text.
 *  - AdvancedSearch : executes a search in FilmAffinity web page using some criterias.
 *  - LoadFilm       : executes a search in FilmAffinity web page by filmId.
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
namespace api\filmaffinity\business;

/**
 * Import dependencies.
 */
use xsgaphp\rest\XsgaRestWrapper;
use xsgaphp\exceptions\XsgaPageNotFoundException;
use xsgaphp\core\XsgaAbstractClass;
use api\filmaffinity\model\dto\AdvSearchDto;
use api\filmaffinity\model\dto\SearchResultsDto;
use api\filmaffinity\model\dto\SingleSearchResultDto;
use api\filmaffinity\model\dto\SearchDto;
use api\filmaffinity\model\dto\FilmDto;
use api\filmaffinity\business\helpers\XpathCons;
use xsgaphp\utils\XsgaLangFiles;

/**
 * Class XsgaFilmAffinity.
 */
class XsgaFilmAffinity extends XsgaAbstractClass
{
    
    /**
     * FilmAffinity language literals.
     * 
     * @var array
     * 
     * @access private
     */
    private $lang;

    /**
     * FilmAffinity base URL.
     * 
     * @var string
     * 
     * @access private
     */
    private $baseUrl = '';
    
    
    /**
     * Constructor.
     * 
     * @access public
     */
    public function __construct()
    {
        // Executes parent constructor.
        parent::__construct();
        
        // Set language literals.
        $this->lang = XsgaLangFiles::load();

        // Set base URL.
        switch ($_ENV['LANGUAGE']) {
            case 'spa':
                $this->baseUrl = $_ENV['BASE_URL'].'es/';
                break;
            case 'en':
                $this->baseUrl = $_ENV['BASE_URL'].'us/';
                break;
        }//end switch
        
    }//end __construct()
        
    
    /**
     * Search.
     *
     * @param SearchDto $searchDto Search DTO.
     *
     * @return SearchResultsDto
     *
     * @access public
     */
    public function search(SearchDto $searchDto) : SearchResultsDto
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get search results.
        $out = $this->getSearchResults($this->getSearchUrl($searchDto), $searchDto->searchText);
        
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
    public function advancedSearch(AdvSearchDto $advSearchDto) : SearchResultsDto
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get search results.
        $out = $this->getSearchResults($this->getAdvSearchUrl($advSearchDto), $advSearchDto->searchText);
    
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
    public function loadFilm(int $filmId) : FilmDto
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get page content.
        $pageContent = $this->getPageContent($this->getFilmUrl($filmId));
        
        // Get film information from page content.
        $filmInfoArray = $this->getFilmInfo($pageContent, $filmId);
        
        // Get film DTO.
        $filmDto = $this->getFilmDto($filmInfoArray);
        
        // Logger.
        $this->logger->debugEnd();
        
        return $filmDto;
    
    }//end loadFilm()
    
    
    /**
     * Get search URL.
     *
     * @param SearchDto $searchDto Search DTO.
     *
     * @return string
     *
     * @access private
     */
    private function getSearchUrl(SearchDto $searchDto) : string
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get url.
        $urlSearch = $this->baseUrl.str_replace('{1}', $this->prepareSearchText($searchDto->searchText), $_ENV['SEARCH_URL']);
        
        // Logger.
        $this->logger->debug('Search URL: '.$urlSearch);
        $this->logger->debugEnd();
        
        return $urlSearch;
        
    }//end getSearchUrl()
    
    
    /**
     * Get advanced search URL.
     * 
     * @param AdvSearchDto $advSearchDto Advanced search DTO.
     * 
     * @return string
     * 
     * @access private
     */
    private function getAdvSearchUrl(AdvSearchDto $advSearchDto) : string
    {
        // Logger.
        $this->logger->debugInit();
        
        // Prepare search type.
        $searchType = '';
        
        if ($advSearchDto->searchTypeTitle) {
            $searchType .= '&stype[]=title';
        }//end if
        
        if ($advSearchDto->searchTypeDirector) {
            $searchType .= '&stype[]=director';
        }//end if
        
        if ($advSearchDto->searchTypeCast) {
            $searchType .= '&stype[]=cast';
        }//end if
        
        if ($advSearchDto->searchTypeSoundtrack) {
            $searchType .= '&stype[]=music';
        }//end if
        
        if ($advSearchDto->searchTypeScreenplay) {
            $searchType .= '&stype[]=script';
        }//end if
        
        if ($advSearchDto->searchTypePhotography) {
            $searchType .= '&stype[]=photo';
        }//end if
        
        if ($advSearchDto->searchTypeProducer) {
            $searchType .= '&stype[]=producer';
        }//end if
        
        // By default, search in title.
        if ($searchType === '') {
            
            // Logger.
            $this->logger->warn('No search type found. Set the default search type: title');
            
            $searchType = '&stype[]=title';
            
        }//end if
        
        // Get url.
        $urlAdvSearch= $_ENV['ADV_SEARCH_URL'];
        $urlAdvSearch= str_replace('{1}', $this->prepareSearchText($advSearchDto->searchText), $urlAdvSearch);
        $urlAdvSearch= str_replace('{2}', $searchType, $urlAdvSearch);
        $urlAdvSearch= str_replace('{3}', $advSearchDto->searchCountry, $urlAdvSearch);
        $urlAdvSearch= str_replace('{4}', $advSearchDto->searchGenre, $urlAdvSearch);
        $urlAdvSearch= str_replace('{5}', $advSearchDto->searchYearFrom, $urlAdvSearch);
        $urlAdvSearch= str_replace('{6}', $advSearchDto->searchYearTo, $urlAdvSearch);
        $urlAdvSearch= $this->baseUrl.$urlAdvSearch;
        
        // Logger.
        $this->logger->debug('Advanced Search URL: '.$urlAdvSearch);
        $this->logger->debugEnd();
        
        return $urlAdvSearch;
        
    }//end getAdvSearchUrl()
    
    
    /**
     * Get film URL.
     * 
     * @param string $filmId FilmAffinity film ID.
     * 
     * @return string
     * 
     * @access private
     */
    private function getFilmUrl(string $filmId) : string
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get url.
        $urlFilm = $this->baseUrl.str_replace('{1}', $filmId, $_ENV['FILM_URL']);
        
        // Logger.
        $this->logger->debug('Film URL: '.$urlFilm);
        $this->logger->debugEnd();
        
        return $urlFilm;
        
    }//end getFilmUrl()
    
    
    /**
     * Prepare search text.
     * 
     * @param string $searchText Text to search.
     * 
     * @return string
     * 
     * @access private
     */
    private function prepareSearchText(string $searchText) : string
    {
        // Logger.
        $this->logger->debugInit();
        
        // Prepare search string.
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);
        
        // Logger.
        $this->logger->debugEnd();
        
        return $searchText;
        
    }//end prepareSearchText()
    
    
    /**
     * Get search results.
     * 
     * @param string $urlSearch  Search URL.
     * @param string $searchText Original search text.
     * 
     * @return SearchResultsDto
     * 
     * @access private
     */
    private function getSearchResults(string $urlSearch, string $searchText) : SearchResultsDto
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
        
        $result = $domXpath->query(XpathCons::OG_TITLE);
        
        if (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
        
            // Get film ID and title.
            $id    = $domXpath->query(XpathCons::OG_URL)->item(0)->getAttribute('content');
            $title = $domXpath->query(XpathCons::OG_TITLE)->item(0)->getAttribute('content');
            
            // Prepare film ID and title.
            $id    = trim(str_replace($this->baseUrl.'film', '', str_replace('.html', '', $id)));
            $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));
            
            // Put result into single result DTO.
            $searchResult        = new SingleSearchResultDto();
            $searchResult->id    = (int)$id;
            $searchResult->title = $title;
            
            // Put single result into output DTO.
            $out->total     = 1;
            $out->results[] = $searchResult;
        
        } else {
        
            // Execute XPath query and store results into variable.
            $filterMovieCard = $domXpath->query(XpathCons::MOVIE_CARD);
        
            // Set total search results.
            $totalResults = $filterMovieCard->length;
            
            // Set total results.
            $out->total = $totalResults;
            
            if ($totalResults > 0) {
        
                for ($i = 0; $i < $totalResults; $i++) {
                    
                    // Execute new XPath query.
                    $filterTitle = $domXpath->query(XpathCons::MC_TITLE);
                    $filterYear  = $domXpath->query(XpathCons::YEA_W);
                    
                    // Get result data.
                    $searchResult        = new SingleSearchResultDto();
                    $searchResult->id    = (int)trim($filterMovieCard->item($i)->getAttribute('data-movie-id'));
                    $searchResult->title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $filterTitle->item($i)->nodeValue)));
                    
                    if (!empty($filterYear->item($i)->nodeValue)) {
                        $searchResult->title .= ' ('.trim($filterYear->item($i)->nodeValue).')';
                    }//end if
                    
                    // Put single result data into output DTO.
                    $out->results[] = $searchResult;
                    
                }//end for
        
            }//end if
            
        }//end if
        
        // Logger.
        $this->logger->info('FilmAffinity search ("'.$searchText.'"): '.$totalResults.' results found');
        
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
    private function getPageContent(string $url) : string
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get REST wrapper.
        $restWrapper = new XsgaRestWrapper();
        
        // Get response.
        $pageContent = $restWrapper->getPageContent($url);
        
        if (empty($pageContent)) {
            
            // Error message.
            $errorMsg = 'FilmAffinity page can not be loaded';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaPageNotFoundException($errorMsg, 200);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $pageContent;
        
    }//end getPageContent()
    
    
    /**
     * Get film information from page content.
     * 
     * @param string $pageContent Film page content.
     * @param string $filmId      FilmAffinity film ID.
     * 
     * @return array
     * 
     * @access private
     */
    private function getFilmInfo(string $pageContent, string $filmId) : array
    {
       // Logger.
       $this->logger->debugInit();
       
       // Disable parse output errors.
       libxml_use_internal_errors(true);
       
       // New DOMDocument instance.
       $document = new \DOMDocument();
       
       // Load film data page into DOMDocument.
       $document->loadHtml(mb_convert_encoding($pageContent, 'HTML-ENTITIES', 'UTF-8'));
       
       // New OMXPath instance.
       $domXpath = new \DOMXPath($document);
       
       // Initialize film information array.
       $filmInfoArray = array();
       
       // Get movie ID, title and rating.
       $filmInfoArray[$this->lang['id']]     = $filmId;
       $filmInfoArray[$this->lang['title']]  = trim($document->getElementById('main-title')->nodeValue);
       $filmInfoArray[$this->lang['rating']] = trim($document->getElementById('movie-rat-avg')->nodeValue);
              
       // Get movie cover URL and cover filename.
       $filterLightbox = $domXpath->query(XpathCons::LIGHTBOX);
       
       if ($filterLightbox->length === 0) {
           
           $filmInfoArray[$this->lang['cover_url']]  = null;
           $filmInfoArray[$this->lang['cover_file']] = null;
           
       } else {

           $coverURL  = trim($filterLightbox->item(0)->getAttribute('href'));
           $coverFile = explode('/', $coverURL);
           
           $filmInfoArray[$this->lang['cover_url']]  = $coverURL;
           $filmInfoArray[$this->lang['cover_file']] = end($coverFile);
           
       }//end if
       
       // Execute XPaths queries.
       $filterMovieInfo    = $domXpath->query(XpathCons::MOVIE_INFO);
       $filterMovieInfoAka = $domXpath->query(XpathCons::AKAS, $filterMovieInfo->item(0));
       
       // Initilize auxiliar film information array.
       $infoAux = array();
       
       // Get auxiliar film information array.
       for ($i = 0; $i < $filterMovieInfoAka->length; $i++) {
           $infoAux[] = trim($filterMovieInfoAka->item($i)->nodeValue);
       }//end for
       
       // Get film information array.
       for ($i = 0; $i < count($infoAux); $i = $i + 2) {
           $filmInfoArray[$infoAux[$i]] = $infoAux[$i + 1];
       }//end for
       
       // Enable parse output errors.
       libxml_use_internal_errors(false);
       
       // Logger.
       $this->logger->debugEnd();
       
       return $filmInfoArray;
        
    }//end getFilmInfo()
    
    
    /**
     * Get film DTO from film information array.
     * 
     * @param array $info Film information array.
     * 
     * @return FilmDto
     * 
     * @access private
     */
    private function getFilmDto(array $info) : FilmDto
    {
        // Logger.
        $this->logger->debugInit();
        
        // Prepare film actors.
        $actors     = (isset($info[$this->lang['cast']])) ? explode(',', $info[$this->lang['cast']]) : array();
        $actorsTrim = array_map('trim', $actors);
        
        // Prepare film genres.
        $genres     = (isset($info[$this->lang['genre']])) ? explode('.', str_replace('|', '.', $info[$this->lang['genre']])) : array();
        $genresTrim = array_map('trim', $genres);
        
        // Prepare film directors.
        $directors     = (isset($info[$this->lang['director']])) ? explode(',', $info[$this->lang['director']]) : array();
        $directorsTrim = array_map('trim', $directors);
        
        // Initialize output array.
        $out = new FilmDto();
        
        // Set film information into output array.
        $out->filmAfinityId = (int)$info[$this->lang['id']];
        $out->title         = $info[$this->lang['title']];
        $out->rating        = $info[$this->lang['rating']];
        $out->coverUrl      = $info[$this->lang['cover_url']];
        $out->coverFile     = $info[$this->lang['cover_file']];
        $out->originalTitle = (isset($info[$this->lang['original_title']])) ? trim(str_replace('aka', '', $info[$this->lang['original_title']])) : '';
        $out->year          = (isset($info[$this->lang['year']])) ? $info[$this->lang['year']] : '';
        $out->duration      = (isset($info[$this->lang['duration']])) ? trim(str_replace('min.', '', $info[$this->lang['duration']])) : '';
        $out->country       = (isset($info[$this->lang['country']])) ? trim(trim($info[$this->lang['country']], chr(0xC2).chr(0xA0))) : '';
        $out->directors     = $directorsTrim;
        $out->screenplay    = (isset($info[$this->lang['screenplay']])) ? $info[$this->lang['screenplay']] : '';
        $out->producer      = (isset($info[$this->lang['producer']])) ? $info[$this->lang['producer']] : '';
        $out->soundtrack    = (isset($info[$this->lang['soundtrack']])) ? $info[$this->lang['soundtrack']] : '';
        $out->photography   = (isset($info[$this->lang['photography']])) ? $info[$this->lang['photography']] : '';
        $out->cast          = $actorsTrim;
        $out->genres        = $genresTrim;
        $out->officialweb   = (isset($info[$this->lang['official_web']])) ? $info[$this->lang['official_web']] : '';
        $out->synopsis      = (isset($info[$this->lang['synopsis']])) ? trim(str_replace('(FILMAFFINITY)', '', $info[$this->lang['synopsis']])) : '';
        
        // Logger.
        $this->logger->debugEnd();
        
        return $out;
        
    }//end getFilmDto()
    
    
}//end XsgaFilmAffinity class
