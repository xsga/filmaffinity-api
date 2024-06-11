<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMElement;
use DOMNode;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Actor;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Director;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\GenreTopic;

final class FilmParser extends AbstractParser
{
    private const string QUERY_FILM_GET_VARIOUS = "//dd[not(@class) and not(@itemprop)]/div";
    private const string QUERY_FILM_GET_TITLE = "//h1[@id = 'main-title']/span[@itemprop = 'name']";
    private const string QUERY_FILM_GET_ORIGINAL_TITLE = "//dd[not(@class) and not(@itemprop)]";
    private const string QUERY_FILM_GET_RELEASE_DATE = "//dd[@itemprop = 'datePublished']";
    private const string QUERY_FILM_GET_DURATION = "//dd[@itemprop = 'duration']";
    private const string QUERY_FILM_GET_COUNTRY = "//img[@class = 'nflag']";
    private const string QUERY_FILM_GET_DIRECTORS = "//span[@itemprop = 'director']/a";
    private const string QUERY_FILM_GET_ACTORS = "//li[@itemprop = 'actor']/a";
    private const string QUERY_FILM_GET_PRODUCERS = "//dd[@class = 'card-producer']//span";
    private const string QUERY_FILM_GET_GENRES = "//span[@itemprop = 'genre']/a";
    private const string QUERY_FILM_GET_GENRE_TOPICS = "//dd[@class = 'card-genres']/a";
    private const string QUERY_FILM_GET_RATING = "//div[@id = 'movie-rat-avg']";
    private const string QUERY_FILM_GET_SYNOPSIS = "//dd[@class = '' and @itemprop = 'description']";
    private const string QUERY_FILM_GET_COVER = "//a[@class = 'lightbox']";

    public function getFilm(int $filmId): Film
    {
        $film = new Film();

        $film->filmAfinityId = $filmId;
        $film->title         = $this->getTitle();
        $film->originalTitle = $this->getOriginalTitle();
        $film->year          = $this->getYear();
        $film->duration      = $this->getDuration();
        $film->country       = $this->getCountry();
        $film->directors     = $this->getDirectors();
        $film->screenplay    = $this->getScreenplay();
        $film->soundtrack    = $this->getSoundtrack();
        $film->photography   = $this->getPhotography();
        $film->cast          = $this->getActors();
        $film->producer      = $this->getProducers();
        $film->genres        = $this->getGenres();
        $film->genreTopics   = $this->getGenreTopics();
        $film->rating        = $this->getRating();
        $film->synopsis      = $this->getSynopsis();
        $film->coverUrl      = $this->getCoverUrl();
        $film->coverFile     = $this->getCoverFile();

        return $film;
    }

    private function validateOneResult(array $results, string $element): bool
    {
        $resultsCount = count($results);

        if ($resultsCount === 0) {
            $this->logger->warning(ucfirst($element) . ' not found');
            return false;
        }

        if ($resultsCount > 1) {
            $this->logger->error('More than 1 ' . strtolower($element) . ' found');
            return false;
        }

        return true;
    }

    private function validateMultipleResult(array $results, string $element): bool
    {
        $resultsCount = count($results);

        if ($resultsCount === 0) {
            $this->logger->warning(ucfirst($element) . ' not found');
            return false;
        }

        return true;
    }

    private function getTitle(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_TITLE);

        if (!$this->validateOneResult($data, 'film title')) {
            return '';
        }

        return trim($data[0]);
    }

    private function getYear(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_RELEASE_DATE);

        if (!$this->validateOneResult($data, 'film release')) {
            return '';
        }

        return trim($data[0]);
    }

    private function getDuration(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_DURATION);

        if (!$this->validateOneResult($data, 'film duration')) {
            return '';
        }

        return trim(str_replace('min.', '', $data[0]));
    }

    /**
     * @return Director[]
     */
    private function getDirectors(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_DIRECTORS, false);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getDirector($item);
        }

        return $out;
    }

    private function getDirector(DOMElement $item): Director
    {
        $url = trim($item->getAttribute('href'));

        $director = new Director(
            (int)substr($url, strpos($url, 'name-id=') + 8, -1),
            trim($item->nodeValue)
        );

        return $director;
    }

    /**
     * @return Actor[]
     */
    private function getActors(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_ACTORS, false);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getActor($item);
        }

        return $out;
    }

    private function getActor(DOMElement $item): Actor
    {
        $url = trim($item->getAttribute('href'));

        $actor = new Actor(
            (int)substr($url, strpos($url, 'name-id=') + 8, -1),
            trim($item->nodeValue)
        );

        return $actor;
    }

    private function getProducers(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_PRODUCERS);

        if (!$this->validateMultipleResult($data, 'film producers')) {
            return '';
        }

        return implode(' ', $data);
    }

    /**
     * @return Genre[]
     */
    private function getGenres(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_GENRES, false);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getGenre($item);
        }

        return $out;
    }

    private function getGenre(DOMNode $item): Genre
    {
        $url = trim($item->attributes?->getNamedItem('href')?->nodeValue);
            
        $genre = new Genre(
            substr($url, strpos($url, 'genre=') + 6, strpos($url, '&') - strpos($url, 'genre=') - 6),
            trim($item->nodeValue)
        );

        return $genre;
    }

    /**
     * @return GenreTopic[]
     */
    private function getGenreTopics(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_GENRE_TOPICS, false);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getGenreTopic($item);
        }

        return $out;
    }

    private function getGenreTopic(DOMNode $item): GenreTopic
    {
        $url = trim($item->attributes?->getNamedItem('href')?->nodeValue);
            
        $genreTopic = new GenreTopic(
            (int)substr($url, strpos($url, 'topic=') + 6, strpos($url, '&') - strpos($url, 'topic=') - 6),
            trim($item->nodeValue)
        );
        
        return $genreTopic;
    }

    private function getOriginalTitle(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_ORIGINAL_TITLE);

        return match (isset($data[0])) {
            true => trim(str_replace('aka', '', $data[0])),
            false => ''
        };
    }

    private function getCountry(): Country
    {
        $data = $this->getData(self::QUERY_FILM_GET_COUNTRY, false);

        $url          = $data->item(0)->attributes->getNamedItem('src')->nodeValue;
        $urlArray     = explode('/', $url);
        $flagImg      = end($urlArray);
        $flagImgArray = explode('.', $flagImg);
        $countryCode  = $flagImgArray[0];

        return new Country($countryCode, $data->item(0)->attributes->getNamedItem('alt')->nodeValue);
    }

    private function getScreenplay(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_VARIOUS);

        return match (isset($data[0])) {
            true => trim($data[0]),
            false => ''
        };
    }

    private function getSoundtrack(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_VARIOUS);

        return match (isset($data[1])) {
            true => trim($data[1]),
            false => ''
        };
    }

    private function getPhotography(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_VARIOUS);

        return match (isset($data[2])) {
            true => trim($data[2]),
            false => ''
        };
    }

    private function getRating(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_RATING);

        if (!$this->validateOneResult($data, 'film rating')) {
            return '';
        }

        return trim($data[0]);
    }

    private function getSynopsis(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_SYNOPSIS);

        if (!$this->validateOneResult($data, 'film synopsis')) {
            return '';
        }

        return trim(str_replace('(FILMAFFINITY)', '', $data[0]));
    }

    private function getCoverUrl(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_COVER, false);

        if ($data->length === 0) {
            $this->logger->warning('Film cover URL not found');
            return '';
        }
        
        return trim($data->item(0)?->attributes?->getNamedItem('href')?->nodeValue);
    }

    private function getCoverFile(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_COVER, false);

        if ($data->length === 0) {
            $this->logger->warning('Film cover file not found');
            return '';
        }

        $coverUrl      = trim($data->item(0)?->attributes?->getNamedItem('href')?->nodeValue);
        $coverUrlArray = explode('/', $coverUrl);
        $coverFile     = end($coverUrlArray);

        return $coverFile;
    }
}
