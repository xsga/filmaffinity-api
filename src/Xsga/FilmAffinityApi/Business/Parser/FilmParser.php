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
namespace Xsga\FilmAffinityApi\Business\Parser;

/**
 * Import dependencies.
 */
use Xsga\FilmAffinityApi\Dto\FilmDto;

/**
 * Class FilmParser.
 */
final class FilmParser extends AbstractParser
{
    /**
     * Get film DTO.
     *
     * @param string $filmId FilmAffinity film ID.
     *
     * @return FilmDto
     *
     * @access public
     */
    public function getFilmDto(string $filmId): FilmDto
    {
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

        return $dto;
    }

    /**
     * Get title.
     *
     * @return string
     *
     * @access private
     */
    private function getTitle(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_TITLE);

        // Sets default title.
        $title = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film title not found');
                break;
            case 1:
                $title = trim($data[0]);
                break;
            default:
                $this->logger->error('More than 1 film title found');
        }//end switch

        return $title;
    }

    /**
     * Get release date.
     *
     * @return string
     *
     * @access private
     */
    private function getYear(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_RELEASE_DATE);

        // Sets default release date.
        $year = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film release date not found');
                break;
            case 1:
                $year = trim($data[0]);
                break;
            default:
                $this->logger->error('More than 1 film release date found');
        }//end switch

        return $year;
    }

    /**
     * Get duration.
     *
     * @return string
     *
     * @access private
     */
    private function getDuration(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_DURATION);

        // Sets default duration.
        $duration = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film duration not found');
                break;
            case 1:
                $duration = trim(str_replace('min.', '', $data[0]));
                break;
            default:
                $this->logger->error('More than 1 film duration found');
        }//end switch

        return $duration;
    }

    /**
     * Get directors
     *
     * @return array
     *
     * @access private
     */
    private function getDirectors(): array
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_DIRECTORS);

        // Sets default directors.
        $directors = array();

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film directors not found');
                break;
            default:
                // Set directors.
                $directors = array_map('trim', $data);
        }//end switch

        return $directors;
    }

    /**
     * Get actors.
     *
     * @return array
     *
     * @access private
     */
    private function getActors(): array
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_ACTORS);

        // Sets default actors.
        $actors = array();

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film actors not found');
                break;
            default:
                // Set actors.
                $actors = array_map('trim', $data);
        }//end switch

        return $actors;
    }

    /**
     * Get producers.
     *
     * @return string
     *
     * @access private
     */
    private function getProducers(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_PRODUCERS);

        // Sets default producers.
        $producers = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film producers not found');
                break;
            default:
                // Set producers.
                $producers = implode(' ', $data);
        }//end switch

        return $producers;
    }

    /**
     * Get genres.
     *
     * @return array
     *
     * @access private
     */
    private function getGenres(): array
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_GENRES);

        // Sets default genres.
        $genres = array();

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film genres not found');
                break;
            default:
                $genres = array_map('trim', $data);
        }//end switch

        return $genres;
    }

    /**
     * Get original title.
     *
     * @return string
     *
     * @access private
     */
    private function getOriginalTitle(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets original title.
        $originalTitle = isset($data[0]) ? trim(str_replace('aka', '', $data[0])) : '';

        return $originalTitle;
    }

    /**
     * Get country.
     *
     * @return string
     *
     * @access private
     */
    private function getCountry(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets country.
        $country = isset($data[1]) ? trim(trim($data[1], chr(0xC2) . chr(0xA0))) : '';

        return $country;
    }

    /**
     * Get screenplay.
     *
     * @return string
     *
     * @access private
     */
    private function getScreenplay(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets screenplay.
        $screenplay = isset($data[2]) ? trim($data[2]) : '';

        return $screenplay;
    }

    /**
     * Get soundtrack.
     *
     * @return string
     *
     * @access private
     */
    private function getSoundtrack(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets soundtrack.
        $soundtrack = isset($data[3]) ? trim($data[3]) : '';

        return $soundtrack;
    }

    /**
     * Get photography.
     *
     * @return string
     *
     * @access private
     */
    private function getPhotography(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        // Sets photography.
        $photography = isset($data[4]) ? trim($data[4]) : '';

        return $photography;
    }

    /**
     * Get rating.
     *
     * @return string
     *
     * @access private
     */
    private function getRating(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_RATING);

        // Sets default rating.
        $rating = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film rating not found');
                break;
            case 1:
                $rating = trim($data[0]);
                break;
            default:
                $this->logger->error('More than 1 film rating found');
        }//end switch

        return $rating;
    }

    /**
     * Get synopsis.
     *
     * @return string
     *
     * @access private
     */
    private function getSynopsis(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_SYNOPSIS);

        // Sets default synopsis.
        $synopsis = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film synopsis not found');
                break;
            case 1:
                $synopsis = trim(str_replace('(FILMAFFINITY)', '', $data[0]));
                break;
            default:
                $this->logger->error('More than 1 film synopsis found');
        }//end switch

        return $synopsis;
    }

    /**
     * Get cover URL.
     *
     * @return string
     *
     * @access private
     */
    private function getCoverUrl(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_COVER, false);

        // Sets default cover URL.
        $coverUrl = '';

        if ($data->length === 0) {
            $this->logger->warning('Film cover URL not found');
        } else {
            $coverUrl = trim($data->item(0)->getAttribute('href'));
        }//end if

        return $coverUrl;
    }

    /**
     * Get cover file.
     *
     * @return string
     *
     * @access private
     */
    private function getCoverFile(): string
    {
        // Gets data.
        $data = $this->getData(XpathCons::FILM_COVER, false);

        // Sets default cover file.
        $coverFile = '';

        if ($data->length === 0) {
            $this->logger->warning('Film cover file not found');
        } else {
            $coverUrl      = trim($data->item(0)->getAttribute('href'));
            $coverUrlArray = explode('/', $coverUrl);
            $coverFile     = end($coverUrlArray);
        }//end if

        return $coverFile;
    }
}
