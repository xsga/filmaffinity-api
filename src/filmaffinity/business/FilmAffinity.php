<?php
/**
 * FilmAffinity.
 * 
 * This file contains the FilmAffinity class. This class provides three public methods:
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
use xsgaphp\core\rest\XsgaRestWrapper;
use xsgaphp\core\exceptions\XsgaPageNotFoundException;
use xsgaphp\core\abstract\XsgaAbstractClass;
use api\filmaffinity\model\AdvSearchDto;
use api\filmaffinity\model\SearchResultsDto;
use api\filmaffinity\model\SearchDto;
use api\filmaffinity\model\FilmDto;
use api\filmaffinity\business\parser\FilmParser;
use api\filmaffinity\business\parser\SearchParser;

/**
 * Class FilmAffinity.
 */
class FilmAffinity extends XsgaAbstractClass
{
    
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
        
        // Gets film parser instance.
        $parser = new FilmParser();

        // Inits parser.
        $parser->init($pageContent);

        // Get film DTO.
        $filmDto = $parser->getFilmDto($filmId);

        // Logger.
        $this->logger->debugEnd();
        
        return $filmDto;
    
    }//end loadFilm()


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

        // Get page content.
        $pageContent = $this->getPageContent($this->getSearchUrl($searchDto));

        // Gets film parser instance.
        $parser = new SearchParser();

        // Inits parser.
        $parser->init($pageContent);

        // Get search results DTO.
        $out = $parser->getSimpleSearchResultsDto();
                
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
        
        // Get page content.
        $pageContent = $this->getPageContent($this->getAdvSearchUrl($advSearchDto));

        // Gets film parser instance.
        $parser = new SearchParser();

        // Inits parser.
        $parser->init($pageContent);

        // Get search results DTO.
        $out = $parser->getAdvSearchResultsDto();
    
        // Logger.
        $this->logger->debugEnd();
    
        return $out;
    
    }//end advancedSearch()
    
    
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
        $urlAdvSearch = $_ENV['ADV_SEARCH_URL'];
        $urlAdvSearch = str_replace('{1}', $this->prepareSearchText($advSearchDto->searchText), $urlAdvSearch);
        $urlAdvSearch = str_replace('{2}', $searchType, $urlAdvSearch);
        $urlAdvSearch = str_replace('{3}', $advSearchDto->searchCountry, $urlAdvSearch);
        $urlAdvSearch = str_replace('{4}', $advSearchDto->searchGenre, $urlAdvSearch);
        $urlAdvSearch = str_replace('{5}', $advSearchDto->searchYearFrom, $urlAdvSearch);
        $urlAdvSearch = str_replace('{6}', $advSearchDto->searchYearTo, $urlAdvSearch);
        $urlAdvSearch = $this->baseUrl.$urlAdvSearch;
        
        // Logger.
        $this->logger->debug('Advanced Search URL: '.$urlAdvSearch);
        $this->logger->debugEnd();
        
        return $urlAdvSearch;
        
    }//end getAdvSearchUrl()


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
            
            throw new XsgaPageNotFoundException($errorMsg, 4001);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $pageContent;
        
    }//end getPageContent()
    
    
}//end XsgaFilmAffinity class
