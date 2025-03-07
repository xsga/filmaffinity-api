<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

final class FilmParser extends AbstractParser
{
    private const string QUERY_FILM_GET_VARIOUS = "//dd[not(@class) and not(@itemprop)]/div";
    private const string QUERY_FILM_GET_TITLE = "//h1[@id = 'main-title']/span[@itemprop = 'name']";
    private const string QUERY_FILM_GET_ORIGINAL_TITLE = "//dd[not(@class) and not(@itemprop)]";
    private const string QUERY_FILM_GET_RELEASE_DATE = "//dd[@itemprop = 'datePublished']";
    private const string QUERY_FILM_GET_DURATION = "//dd[@itemprop = 'duration']";
    private const string QUERY_FILM_GET_PRODUCERS = "//dd[@class = 'card-producer']//span";
    private const string QUERY_FILM_GET_RATING = "//div[@id = 'movie-rat-avg']";
    private const string QUERY_FILM_GET_SYNOPSIS = "//dd[@class = '' and @itemprop = 'description']";

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

    public function getTitle(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_TITLE);

        if (!$this->validateOneResult($data, 'film title')) {
            return '';
        }

        return trim($data[0] ?? '');
    }

    public function getYear(): int
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_RELEASE_DATE);

        if (!$this->validateOneResult($data, 'film release')) {
            return 0;
        }

        return (int)trim($data[0] ?? '');
    }

    public function getDuration(): int
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_DURATION);

        if (!$this->validateOneResult($data, 'film duration')) {
            return 0;
        }

        return (int)trim(str_replace('min.', '', $data[0] ?? ''));
    }

    public function getProducers(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_PRODUCERS);

        if (!$this->validateMultipleResult($data, 'film producers')) {
            return '';
        }

        return implode(' ', $data);
    }

    public function getOriginalTitle(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_ORIGINAL_TITLE);

        return trim(str_replace('aka', '', $data[0] ?? ''));
    }

    public function getScreenplay(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_VARIOUS);

        return trim($data[0] ?? '');
    }

    public function getSoundtrack(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_VARIOUS);

        return trim($data[1] ?? '');
    }

    public function getPhotography(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_VARIOUS);

        return trim($data[2] ?? '');
    }

    public function getRating(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_RATING);

        if (!$this->validateOneResult($data, 'film rating')) {
            return '';
        }

        return trim($data[0] ?? '');
    }

    public function getSynopsis(): string
    {
        $data = $this->getDataArray(self::QUERY_FILM_GET_SYNOPSIS);

        if (!$this->validateOneResult($data, 'film synopsis')) {
            return '';
        }

        return trim(str_replace('(FILMAFFINITY)', '', $data[0] ?? ''));
    }
}
