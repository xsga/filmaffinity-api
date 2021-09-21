<?php
/**
 * FilmParser.
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
use api\filmaffinity\model\FilmDto;

/**
 * Class FilmParser.
 */
class FilmParser extends AbstractParser
{

    
    /**
     * @param string $filmId Film ID.
     * 
     * @return FilmDto
     * 
     * @access public
     */
    public function getFilmDto(string $filmId) : FilmDto
    {
        // Logger.
        $this->logger->debugInit();

        // Gest FilmDto instance.
        $dto = new FilmDto();

        // Set data into film DTO.
        $dto->filmAfinityId = $filmId;
        $dto->title         = $this->getTitle();
        $dto->originalTitle = $this->getOriginalTitle();
        $dto->year          = $this->getYear();
        $dto->duration      = $this->getDuration();
        $dto->country       = $this->getCountry();
        $dto->directors     = $this->getDirectors();
        $dto->screenplay    = $this->getScreenplay();
        $dto->soundtrack    = $this->getSoundtrack();
        $dto->photography   = $this->getPhotography();
        $dto->cast          = $this->getActors();
        $dto->producer      = $this->getProducers();
        $dto->genres        = $this->getGenres();
        $dto->rating        = $this->getRating();
        $dto->synopsis      = $this->getSynopsis();
        $dto->coverUrl      = $this->getCoverUrl();
        $dto->coverFile     = $this->getCoverFile();
        
        // Logger.
        $this->logger->debugEnd();

        return $dto;
        
    }//end getFilmDto()


    /**
     * Get title.
     * 
     * @return string
     * 
     * @access private
     */
    private function getTitle() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_TITLE);

        // Sets default title.
        $title = '';

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film title not found');
                break;

            case 1:
                // Set title.
                $title = trim($data[0]);
                break;

            default:
                // Logger.
                $this->logger->error('More than 1 film title found');
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $title;

    }//end getTitle()


    /**
     * Get release date.
     * 
     * @return string
     * 
     * @access private
     */
    private function getYear() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_RELEASE_DATE);

        // Sets default release date.
        $year = '';

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film release date not found');
                break;

            case 1:
                // Set release date.
                $year = trim($data[0]);
                break;

            default:
                // Logger.
                $this->logger->error('More than 1 film release date found');
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $year;

    }//end getYear()


    /**
     * Get duration.
     * 
     * @return string
     * 
     * @access private
     */
    private function getDuration() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_DURATION);

        // Sets default duration.
        $duration = '';

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film duration not found');
                break;

            case 1:
                // Set duration.
                $duration = trim(str_replace('min.', '', $data[0]));
                break;

            default:
                // Logger.
                $this->logger->error('More than 1 film duration found');
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $duration;

    }//end getDuration()


    /**
     * Get directors
     * 
     * @return array
     * 
     * @access private
     */
    private function getDirectors() : array
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_DIRECTORS);

        // Sets default directors.
        $directors = array();

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film directors not found');
                break;

            default:
                // Set directors.
                $directors = array_map('trim', $data);
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $directors;

    }//end getDirectors()


    /**
     * Get actors.
     * 
     * @return array
     * 
     * @access private
     */
    private function getActors() : array
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_ACTORS);

        // Sets default actors.
        $actors = array();

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film actors not found');
                break;

            default:
                // Set actors.
                $actors = array_map('trim', $data);
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $actors;

    }//end getActors()


    /**
     * Get producers.
     * 
     * @return string
     * 
     * @access private
     */
    private function getProducers() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_PRODUCERS);

        // Sets default producers.
        $producers = '';

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film producers not found');
                break;

            default:
                // Set producers.
                $producers = implode(' ', $data);
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $producers;

    }//end getProducers()


    /**
     * Get genres.
     * 
     * @return array
     * 
     * @access private
     */
    private function getGenres() : array
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_GENRES);

        // Sets default genres.
        $genres = array();

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film genres not found');
                break;

            default:
                // Set genres.
                $genres = array_map('trim', $data);
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $genres;

    }//end getGenres()


    /**
     * Get original title.
     * 
     * @return string
     * 
     * @access private
     */
    private function getOriginalTitle() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets original title.
        $originalTitle = isset($data[0]) ? trim(str_replace('aka', '', $data[0])) : '';

        // Logger.
        $this->logger->debugEnd();

        return $originalTitle;

    }//end getOriginalTitle()


    /**
     * Get country.
     * 
     * @return string
     * 
     * @access private
     */
    private function getCountry() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets country.
        $country = isset($data[1]) ? trim(trim($data[1], chr(0xC2).chr(0xA0))) : '';
        
        // Logger.
        $this->logger->debugEnd();

        return $country;

    }//end getCountry()


    /**
     * Get screenplay.
     * 
     * @return string
     * 
     * @access private
     */
    private function getScreenplay() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets screenplay.
        $screenplay = isset($data[2]) ? trim($data[2]) : '';
        
        // Logger.
        $this->logger->debugEnd();

        return $screenplay;

    }//end getScreenplay()


    /**
     * Get soundtrack.
     * 
     * @return string
     * 
     * @access private
     */
    private function getSoundtrack() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets soundtrack.
        $soundtrack = isset($data[3]) ? trim($data[3]) : '';
        
        // Logger.
        $this->logger->debugEnd();

        return $soundtrack;

    }//end getSoundtrack()


    /**
     * Get photography.
     * 
     * @return string
     * 
     * @access private
     */
    private function getPhotography() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets photography.
        $photography = isset($data[4]) ? trim($data[4]) : '';
        
        // Logger.
        $this->logger->debugEnd();

        return $photography;

    }//end getPhotography()


    /**
     * Get rating.
     * 
     * @return string
     * 
     * @access private
     */
    private function getRating() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_RATING);

        // Sets default rating.
        $rating = '';

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film rating not found');
                break;

            case 1:
                // Set rating.
                $rating = trim($data[0]);
                break;

            default:
                // Logger.
                $this->logger->error('More than 1 film rating found');
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $rating;

    }//end getRating()


    /**
     * Get synopsis.
     * 
     * @return string
     * 
     * @access private
     */
    private function getSynopsis() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_SYNOPSIS);

        // Sets default synopsis.
        $synopsis = '';

        switch (count($data)) {

            case 0:
                // Logger.
                $this->logger->warn('Film synopsis not found');
                break;

            case 1:
                // Set synopsis.
                $synopsis = trim(str_replace('(FILMAFFINITY)', '', $data[0]));
                break;

            default:
                // Logger.
                $this->logger->error('More than 1 film synopsis found');
                
        }//end switch

        // Logger.
        $this->logger->debugEnd();

        return $synopsis;

    }//end getSynopsis()


    /**
     * Get cover URL.
     * 
     * @return string
     * 
     * @access private
     */
    private function getCoverUrl() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_COVER, false);

        // Sets default cover URL.
        $coverUrl = '';

        if ($data->length === 0) {
            
            // Logger.
            $this->logger->warn('Film cover URL not found');
            
        } else {

            $coverUrl = trim($data->item(0)->getAttribute('href'));
                        
        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $coverUrl;

    }//end getCoverUrl()


    /**
     * Get cover file.
     * 
     * @return string
     * 
     * @access private
     */
    private function getCoverFile() : string
    {
        // Logger.
        $this->logger->debugInit();

        // Gets data.
        $data = $this->getData(XpathCons::FILM_COVER, false);

        // Sets default cover file.
        $coverFile = '';

        if ($data->length === 0) {
            
            // Logger.
            $this->logger->warn('Film cover file not found');
            
        } else {

            $coverUrl      = trim($data->item(0)->getAttribute('href'));
            $coverUrlArray = explode('/', $coverUrl);
            $coverFile     = end($coverUrlArray);
                        
        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $coverFile;

    }//end getCoverFile()


}//end FilmParser class
