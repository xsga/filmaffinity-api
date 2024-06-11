<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMNode;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\GenreTopic;

final class FilmGenresParser extends AbstractParser
{
    private const string QUERY_FILM_GET_GENRES = "//span[@itemprop = 'genre']/a";
    private const string QUERY_FILM_GET_GENRE_TOPICS = "//dd[@class = 'card-genres']/a";
    
    /**
     * @return Genre[]
     */
    public function getGenres(): array
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
    public function getGenreTopics(): array
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
}
