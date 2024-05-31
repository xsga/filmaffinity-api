<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

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
        $data = $this->getData(XpathCons::FILM_TITLE);

        if (!$this->validateOneResult($data, 'film title')) {
            return '';
        }

        return trim($data[0]);
    }

    private function getYear(): string
    {
        $data = $this->getData(XpathCons::FILM_RELEASE_DATE);

        if (!$this->validateOneResult($data, 'film release')) {
            return '';
        }

        return trim($data[0]);
    }

    private function getDuration(): string
    {
        $data = $this->getData(XpathCons::FILM_DURATION);

        if (!$this->validateOneResult($data, 'film duration')) {
            return '';
        }

        return trim(str_replace('min.', '', $data[0]));
    }

    private function getDirectors(): array
    {
        $data = $this->getData(XpathCons::FILM_DIRECTORS);

        if (!$this->validateMultipleResult($data, 'film directors')) {
            return [];
        }

        return array_map('trim', $data);
    }

    private function getActors(): array
    {
        $data = $this->getData(XpathCons::FILM_ACTORS);

        if (!$this->validateMultipleResult($data, 'film actors')) {
            return [];
        }

        return array_map('trim', $data);
    }

    private function getProducers(): string
    {
        $data = $this->getData(XpathCons::FILM_PRODUCERS);

        if (!$this->validateMultipleResult($data, 'film producers')) {
            return [];
        }

        return implode(' ', $data);
    }

    private function getGenres(): array
    {
        $data = $this->getData(XpathCons::FILM_GENRES);

        if (!$this->validateMultipleResult($data, 'film genres')) {
            return [];
        }

        return array_map('trim', $data);
    }

    private function getOriginalTitle(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        return match (isset($data[0])) {
            true => trim(str_replace('aka', '', $data[0])),
            false => ''
        };
    }

    private function getCountry(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        return match (isset($data[1])) {
            true => trim(trim($data[1], chr(0xC2) . chr(0xA0))),
            false => ''
        };
    }

    private function getScreenplay(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        return match (isset($data[2])) {
            true => trim($data[2]),
            false => ''
        };
    }

    private function getSoundtrack(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        return match (isset($data[3])) {
            true => trim($data[3]),
            false => ''
        };
    }

    private function getPhotography(): string
    {
        $data = $this->getData(XpathCons::FILM_VARIOUS);

        return match (isset($data[4])) {
            true => trim($data[4]),
            false => ''
        };
    }

    private function getRating(): string
    {
        $data = $this->getData(XpathCons::FILM_RATING);

        if (!$this->validateOneResult($data, 'film rating')) {
            return '';
        }

        return trim($data[0]);
    }

    private function getSynopsis(): string
    {
        $data = $this->getData(XpathCons::FILM_SYNOPSIS);

        if (!$this->validateOneResult($data, 'film synopsis')) {
            return '';
        }

        return trim(str_replace('(FILMAFFINITY)', '', $data[0]));
    }

    private function getCoverUrl(): string
    {
        $data = $this->getData(XpathCons::FILM_COVER, false);

        if ($data->length === 0) {
            $this->logger->warning('Film cover URL not found');
            return '';
        }

        return trim($data->item(0)->getAttribute('href'));
    }

    private function getCoverFile(): string
    {
        $data = $this->getData(XpathCons::FILM_COVER, false);

        if ($data->length === 0) {
            $this->logger->warning('Film cover file not found');
            return '';
        }

        $coverUrl      = trim($data->item(0)->getAttribute('href'));
        $coverUrlArray = explode('/', $coverUrl);
        $coverFile     = end($coverUrlArray);

        return $coverFile;
    }
}
