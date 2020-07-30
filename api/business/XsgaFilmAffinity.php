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
use xsgaphp\filmaffinity\dto\FilmAffinityAdvSearchDto;
use xsgaphp\exceptions\XsgaValidationException;
use xsgaphp\exceptions\XsgaPageNotFoundException;
use xsgaphp\mvc\XsgaAbstractClass;

/**
 * Class XsgaFilmAffinity.
 */
class XsgaFilmAffinity extends XsgaAbstractClass
{
    
    /**
     * Base URL.
     * 
     * @var string
     * 
     * @access private
     */
    private $baseURL = 'https://www.filmaffinity.com/es/';
    
    /**
     * Search URL.
     * 
     * @var string
     * 
     * @access private
     */
    private $searchURL = 'search.php?stext={1}';
    
    /**
     * Advanced search URL.
     *
     * @var string
     *
     * @access private
     */
    private $advSearchURL = 'advsearch.php?stext={1}{2}&country={3}&genre={4}&fromyear={5}&toyear={6}';
    
    /**
     * Film URL.
     * 
     * @var string
     * 
     * @access private
     */
    private $filmURL = 'film{1}.html';
    
    /**
     * Search type.
     * 
     * @var string
     * 
     * @access private
     */
    private $searchType = 'title';
    
    /**
     * Language.
     * 
     * @var string
     * 
     * @access private
     */
    private $language = '';
    
    /**
     * Default language.
     * 
     * @var string
     * 
     * @access private
     */
    private $defaultLanguage = 'spa';
    
    /**
     * Errors.
     * 
     * @var array
     * 
     * @access private
     */
    private $errors;
    
    /**
     * Minimum length of the search text.
     * 
     * @var integer
     * 
     * @access private
     */
    private $minLength = 2;
    
    
    /**
     * Constructor.
     * 
     * @access public
     */
    public function __construct()
    {
        
        // Executes parent constructor.
        parent::__construct();
        
        // Set language.
        $this->setLanguage($this->defaultLanguage);
        
    }//end __construct()
    
    
    /**
     * Search.
     *
     * @param string $searchText Search text.
     *
     * @return array
     *
     * @access public
     */
    public function search($searchText)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates search text.
        $this->validateSearchText($searchText);
        
        // Prepare search string.
        $origSearchText = $searchText;
        $searchText     = trim($searchText);
        $searchText     = str_replace(' ', '+', $searchText);
        
        // Get url.
        $urlSearch = $this->searchURL;
        $urlSearch = str_replace('{1}', $searchText, $urlSearch);
        $urlSearch = $this->baseURL.$urlSearch;
        
        // Logger.
        $this->logger->debug('Search URL: '.$urlSearch);
        
        // Get search results.
        $out = $this->getSearchResults($urlSearch, $origSearchText);
        
        // Logger.
        $this->logger->debugEnd();
    
        return json_encode($out);
    
    }//end search()
    
    
    /**
     * Advanced search.
     *
     * @param FilmAffinityAdvSearchDto $advSearchDto Advanced search data.
     *
     * @return array
     *
     * @access public
     */
    public function advancedSearch(FilmAffinityAdvSearchDto $advSearchDto)
    {
    
        // Logger.
        $this->logger->debugInit();
    
        // Validates search text.
        $this->validateSearchText($advSearchDto->searchText);
    
        // Prepare search string.
        $origSearchText           = $advSearchDto->searchText;
        $advSearchDto->searchText = trim($advSearchDto->searchText);
        $advSearchDto->searchText = str_replace(' ', '+', $advSearchDto->searchText);
        
        // Prepare search type.
        $searchType = '';
        
        if ($advSearchDto->searchTypeTitle === true) {
            $searchType .= '&stype[]=tite';
        }//end if
        
        if ($advSearchDto->searchTypeDirector === true) {
            $searchType .= '&stype[]=director';
        }//end if
        
        if ($advSearchDto->searchTypeCast === true) {
            $searchType .= '&stype[]=cast';
        }//end if
        
        if ($advSearchDto->searchTypeMusic === true) {
            $searchType .= '&stype[]=music';
        }//end if
        
        if ($advSearchDto->searchTypeScript === true) {
            $searchType .= '&stype[]=script';
        }//end if
        
        if ($advSearchDto->searchTypePhoto === true) {
            $searchType .= '&stype[]=photo';
        }//end if
        
        if ($advSearchDto->searchTypeProducer === true) {
            $searchType .= '&stype[]=producer';
        }//end if
        
        // Get url.
        $urlSearch = $this->advSearchURL;
        $urlSearch = str_replace('{1}', $advSearchDto->searchText, $urlSearch);
        $urlSearch = str_replace('{2}', $searchType, $urlSearch);
        $urlSearch = str_replace('{3}', $advSearchDto->searchCountry, $urlSearch);
        $urlSearch = str_replace('{4}', $advSearchDto->searchGenre, $urlSearch);
        $urlSearch = str_replace('{5}', $advSearchDto->searchYearFrom, $urlSearch);
        $urlSearch = str_replace('{6}', $advSearchDto->searchYearTo, $urlSearch);
        $urlSearch = $this->baseURL.$urlSearch;
    
        // Logger.
        $this->logger->debug('Search URL: '.$urlSearch);
    
        // Get search results.
        $out = $this->getSearchResults($urlSearch, $origSearchText);
    
        // Logger.
        $this->logger->debugEnd();
    
        return json_encode($out);
    
    }//end advancedSearch()
    
    
    /**
     * Load film.
     *
     * @param string $filmId Film id.
     *
     * @return string
     *
     * @access public
     */
    public function loadFilm($filmId)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get url.
        $urlFilm = str_replace('{1}', $filmId, $this->filmURL);
        $urlFilm = $this->baseURL.$urlFilm;
        
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
        $out = array();
        
        // Get movie title and rating.
        $out['title']  = trim($document->getElementById('main-title')->nodeValue);
        $out['rating'] = trim($document->getElementById('movie-rat-avg')->nodeValue);
        
        // Get movie cover URL and cover filename.
        $result = $domXpath->query("//a[@class = 'lightbox']");
        
        if ($result->length === 0) {
            $out['cover_url']  = null;
            $out['cover_file'] = null;
        } else {
            $coverURL          = trim($result->item(0)->getAttribute('href'));
            $coverFile         = explode('/', $coverURL);
            $out['cover_url']  = $coverURL;
            $out['cover_file'] = end($coverFile);
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
        $out['filmaffinity_id'] = $filmId;
        $out['original_title']  = (isset($info['Título original']) === true) ? trim(str_replace('aka', '', $info['Título original'])) : '';
        $out['year']            = (isset($info['Año']) === true) ? $info['Año'] : '';
        $out['duration']        = (isset($info['Duración']) === true) ? trim(str_replace('min.', '', $info['Duración'])) : '';
        $out['country']         = (isset($info['País']) === true) ? trim(trim($info['País'], chr(0xC2).chr(0xA0))) : '';
        $out['director']        = $directorsTrim;
        $out['screenplay']      = (isset($info['Guion']) === true) ? $info['Guion'] : '';
        $out['soundtrack']      = (isset($info['Música']) === true) ? $info['Música'] : '';
        $out['photo']           = (isset($info['Fotografía']) === true) ? $info['Fotografía'] : '';
        $out['actors']          = $actorsTrim;
        $out['production']      = (isset($info['Productora']) === true) ? $info['Productora'] : '';
        $out['genres']          = $genresTrim;
        $out['official_web']    = (isset($info['Web oficial']) === true) ? $info['Web oficial'] : '';
        $out['synopsis']        = (isset($info['Sinopsis']) === true) ? trim(str_replace('(FILMAFFINITY)', '', $info['Sinopsis'])) : '';
        
        // Logger.
        $this->logger->debugEnd();
        
        return json_encode($out);
    
    }//end loadFilm()
    
    
    /**
     * Set minimum length of the search text.
     * 
     * @param integer $minLength Minimum length of the search text.
     * 
     * @return void
     * 
     * @access public
     */
    public function setMinLength($minLength)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (is_numeric($minLength) === true) {
            
            // Round minimum length.
            $minLength = round($minLength, 0);
            
            if ($minLength <= 0) {
                
                // Logger.
                $this->logger->warn('Length out of range');
                
                // Set minimum length to 2.
                $this->minLength = 2;
                
            } else {
                
                // Logger.
                $this->logger->debug('Set minimum length to: '.$minLength);
                
                // Set minimum length.
                $this->minLength = $minLength;
                
            }//end if
            
        } else {
            
            // Logger.
            $this->logger->warn('Not a valid number');
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end setMinLength()
    
    
    /**
     * Get minimum length of the search text.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getMinLength()
    {
        
        return $this->minLength;
        
    }//end getMinLength()
    
    
    /**
     * Set language.
     * 
     * @param string $language Language code.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLanguage($language)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get language.
        switch (strtolower(trim($language))) {
            case 'spa':
                
                // Logger.
                $this->logger->info('Set language to "spa" (spanish)');
                
                // Set language.
                $this->language = 'spa';
                
                break;
                
            case 'ca':
                
                // Logger.
                $this->logger->info('Set language to "ca" (catalan)');
                
                // Set language.
                $this->language = 'ca';
                
                break;
                
            case 'en':
                
                // Logger.
                $this->logger->info('Set language to "en" (english)');
                
                // Set language.
                $this->language = 'en';
                
                break;
                
            default:
                
                // Logger.
                $this->logger->warn('Language "'.$language.'" not valid. Set to default, "'.$this->defaultLanguage.'"');
                
                // Set language to default language.
                $this->language = $this->defaultLanguage;
                                
        }//end switch
        
        // Load language literals.
        $this->loadLanguageLiterals();
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end setLanguage()
    
    
    /**
     * Validate search text.
     * 
     * @param string $searchText Search text.
     * 
     * @return void
     * 
     * @throws XsgaValidationException Invalid search text: empty.
     * @throws XsgaValidationException Invalid search text: less than 3 chars.
     * 
     * @access private
     */
    private function validateSearchText($searchText)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (($searchText === '') || ($searchText === null)) {
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error('Invalid search text: empty');
        
            throw new XsgaValidationException($this->errors['no_search_text']);
        
        }//end if
        
        if (strlen($searchText) < $this->minLength) {
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error('Invalid search text: less than 3 chars');
        
            throw new XsgaValidationException($this->errors['short_search_text']);
        
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end validateSearchText()
    
    
    /**
     * Get search results.
     * 
     * @param string $urlSearch  Search URL.
     * @param string $searchText Search text.
     * 
     * @return array
     * 
     * @access private
     */
    private function getSearchResults($urlSearch, $searchText)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get search results page.
        $searchResult = $this->getPageContent($urlSearch);
        
        // Disable parse output errors.
        libxml_use_internal_errors(true);
        
        // New DOMDocument instance.
        $document = new \DOMDocument();
        
        // Load search results into the DOMDocument.
        $document->loadHtml(mb_convert_encoding($searchResult, 'HTML-ENTITIES', 'UTF-8'));
        
        // New DOMXPath instance.
        $domXpath = new \DOMXPath($document);
        
        // Initialize output.
        $out = array();
        
        $result = $domXpath->query("//meta[@property = 'og:title']");
        
        if (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
        
            // Logger.
            $this->logger->info('FilmAffinity search ("'.$searchText.'"): found 1 results');
        
            // Get film ID and title.
            $idAux    = $domXpath->query("//meta[@property = 'og:url']")->item(0)->getAttribute('content');
            $titleAux = $domXpath->query("//meta[@property = 'og:title']")->item(0)->getAttribute('content');
        
            $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $titleAux)));
            $id    = trim(str_replace('https://www.filmaffinity.com/es/film', '', str_replace('.html', '', $idAux)));
        
            // Put result data into output array.
            $out[] = array('id' => $id, 'title' => $title);
        
        } else {
        
            // Execute XPath query and store results into variable.
            $filter = $domXpath->query("//div[contains(@class, 'movie-card')]");
        
            // Set total search results.
            $totalResults = $filter->length;
        
            if ($totalResults === 0) {
        
                // Logger.
                $this->logger->warn('No search results for "'.$searchText.'"');
        
                throw new \Exception($this->errors['no_search_results']);
        
            }//end if
        
            // Logger.
            $this->logger->info('FilmAffinity search ("'.$searchText.'"): '.$totalResults.' results found');
        
            for ($i = 0; $i < $totalResults; $i++) {
        
                // Execute new XPath query.
                $filter2 = $domXpath->query("//div[@class = 'mc-title']");
                $filter3 = $domXpath->query("//div[@class = 'ye-w']");
        
                // Get result data.
                $id     = trim($filter->item($i)->getAttribute('data-movie-id'));
                $title  = trim(str_replace('  ', ' ', str_replace('   ', ' ', $filter2->item($i)->nodeValue)));
        
                if (empty($filter3->item($i)->nodeValue) === false) {
        
                    $title .= ' ('.$filter3->item($i)->nodeValue.')';
        
                }//end if
        
                // Put result data into output array.
                $out[] = array('id' => $id, 'title' => $title);
        
            }//end for
        
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
            
            throw new XsgaPageNotFoundException($this->errors['internal_error']);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $pageContent;
        
    }//end getPageContent()
    
    
    /**
     * Load language literals.
     * 
     * @return void
     * 
     * @access private
     */
    private function loadLanguageLiterals()
    {
        
        // Logger.
        $this->logger->debugInit();

        // Set language file.
        $file = 'errors'.ucfirst($this->language).'.php';

        // Logger.
        $this->logger->debug('Load property file: '.$file);
        
        // Load errors labels.
        require_once 'language/'.$file;
        
        // Set errors.
        $this->errors = $errors;
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end loadLanguageLiterals()
    
    
}//end XsgaFilmAffinity class
