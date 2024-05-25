<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parser;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;

final class FilmParser extends AbstractParser
{
    public function getFilm(int $filmId): Film
    {
        $dto = new Film();

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

    private function getTitle(): string
    {
        $data = $this->getData(XpathCons::FILM_TITLE);

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
        }

        return $title;
    }

    private function getYear(): string
    {
        $data = $this->getData(XpathCons::FILM_RELEASE_DATE);

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
        }

        return $year;
    }

    private function getDuration(): string
    {
        $data = $this->getData(XpathCons::FILM_DURATION);

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
        }

        return $duration;
    }

    private function getDirectors(): array
    {
        $data = $this->getData(XpathCons::FILM_DIRECTORS);

        $directors = [];

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film directors not found');
                break;
            default:
                $directors = array_map('trim', $data);
        }

        return $directors;
    }

    private function getActors(): array
    {
        $data = $this->getData(XpathCons::FILM_ACTORS);

        $actors = [];

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film actors not found');
                break;
            default:
                $actors = array_map('trim', $data);
        }

        return $actors;
    }

    private function getProducers(): string
    {
        $data = $this->getData(XpathCons::FILM_PRODUCERS);

        $producers = '';

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film producers not found');
                break;
            default:
                $producers = implode(' ', $data);
        }

        return $producers;
    }

    private function getGenres(): array
    {
        $data = $this->getData(XpathCons::FILM_GENRES);

        $genres = [];

        switch (count($data)) {
            case 0:
                $this->logger->warning('Film genres not found');
                break;
            default:
                $genres = array_map('trim', $data);
        }

        return $genres;
    }

    private function getOriginalTitle(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        $originalTitle = isset($data[0]) ? trim(str_replace('aka', '', $data[0])) : '';

        return $originalTitle;
    }

    private function getCountry(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        $country = isset($data[1]) ? trim(trim($data[1], chr(0xC2) . chr(0xA0))) : '';

        return $country;
    }

    private function getScreenplay(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        $screenplay = isset($data[2]) ? trim($data[2]) : '';

        return $screenplay;
    }

    private function getSoundtrack(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        $soundtrack = isset($data[3]) ? trim($data[3]) : '';

        return $soundtrack;
    }

    private function getPhotography(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        $photography = isset($data[4]) ? trim($data[4]) : '';

        return $photography;
    }

    private function getRating(): string
    {
        $data = $this->getData(XpathCons::FILM_RATING);

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
        }

        return $rating;
    }

    private function getSynopsis(): string
    {
        $data = $this->getData(XpathCons::FILM_SYNOPSIS);

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
        }

        return $synopsis;
    }

    private function getCoverUrl(): string
    {
        $data = $this->getData(XpathCons::FILM_COVER, false);

        $coverUrl = '';

        if ($data->length === 0) {
            $this->logger->warning('Film cover URL not found');
        } else {
            $coverUrl = trim($data->item(0)->getAttribute('href'));
        }

        return $coverUrl;
    }

    private function getCoverFile(): string
    {
        $data = $this->getData(XpathCons::FILM_COVER, false);

        $coverFile = '';

        if ($data->length === 0) {
            $this->logger->warning('Film cover file not found');
        } else {
            $coverUrl      = trim($data->item(0)->getAttribute('href'));
            $coverUrlArray = explode('/', $coverUrl);
            $coverFile     = end($coverUrlArray);
        }

        return $coverFile;
    }
}
