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

    private string $urlGenre = 'genre=';
    private string $urlTopic = 'topic=';

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_GENRES);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getGenre($item);
        }

        return $out;
    }

    private function getGenre(DOMNode $item): Genre
    {
        $url = trim($item->attributes?->getNamedItem('href')?->nodeValue);

        $genreCode = substr(
            $url,
            strpos($url, $this->urlGenre) + strlen($this->urlGenre),
            strpos($url, '&') - strpos($url, $this->urlGenre) - strlen($this->urlGenre)
        );
        $genreName = trim($item->nodeValue);

        return new Genre($genreCode, $genreName);
    }

    /**
     * @return GenreTopic[]
     */
    public function getGenreTopics(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_GENRE_TOPICS);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getGenreTopic($item);
        }

        return $out;
    }

    private function getGenreTopic(DOMNode $item): GenreTopic
    {
        $url = trim($item->attributes?->getNamedItem('href')?->nodeValue);

        $genreTopicId = (int)substr(
            $url,
            strpos($url, $this->urlTopic) + strlen($this->urlTopic),
            strpos($url, '&') - strpos($url, $this->urlTopic) - strlen($this->urlTopic)
        );
        $genreTopicName = trim($item->nodeValue);

        return new GenreTopic($genreTopicId, $genreTopicName);
    }
}
