<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Director;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SingleSearchResult;

final class SimpleSearchParser extends AbstractParser
{
    private const string QUERY_RESULTS_TYPE = "//meta[@property = 'og:title']";
    private const string QUERY_SINGLE_RESULT_GET_ID = "//meta[@property = 'og:url']";
    private const string QUERY_MULTIPLE_RESULTS_DATA = "//div[contains(@class, 'se-it')]";
    private const string QUERY_MULTIPLE_RESULTS_GET_TITLE = "//div[@class = 'mc-title']/a";
    private const string QUERY_MULTIPLE_RESULTS_GET_YEAR = "//div[contains(@class, 'ye-w')]";
    private const string QUERY_MULTIPLE_RESULTS_GET_ID = "//div[contains(@class, 'movie-card')]";
    private const string QUERY_MULTIPLE_RESULTS_GET_DIRECTORS = "//div[contains(@class, 'mc-director')]//a";

    private string $urlPattern = 'name-id=';

    public function isSingleResult(): bool
    {
        $queyResults = $this->getData(self::QUERY_RESULTS_TYPE, false);

        if (($queyResults->length > 0) && ($queyResults->item(0)?->attributes?->getNamedItem('content')?->nodeValue !== 'FilmAffinity')) {
            return true;
        }

        return false;
    }

    public function getSingleResultId(): int
    {
        $idSearch = $this->getData(self::QUERY_SINGLE_RESULT_GET_ID, false);
        $idArray  = explode('/', $idSearch->item(0)?->attributes?->getNamedItem('content')?->nodeValue);

        return (int)trim(str_replace('film', '', str_replace('.html', '', end($idArray))));
    }

    public function getMultiplesResultsTotal(): int
    {
        $searchResults = $this->getData(self::QUERY_MULTIPLE_RESULTS_DATA, false);

        return $searchResults->length;
    }

    /**
     * @return SingleSearchResult[]
     */
    public function getMultiplesResults(): array
    {
        $searchResults = $this->getData(self::QUERY_MULTIPLE_RESULTS_DATA, false);

        $out = [];

        for ($i = 0; $i < $searchResults->length; $i++) {
            $out[] = $this->getSearchResult($searchResults, $i);
        }

        return $out;
    }

    private function getSearchResult(DOMNodeList $searchResults, int $element): SingleSearchResult
    {
        $dom = new DOMDocument();
        $dom->appendChild($dom->importNode($searchResults->item($element), true));

        $domXpath = new DOMXPath($dom);

        return new SingleSearchResult(
            $this->getId($domXpath),
            $this->getTitle($domXpath),
            $this->getYear($domXpath),
            $this->getDirectors($domXpath)
        );
    }

    private function getId(DOMXPath $domXpath): int
    {
        $idResult  = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_ID);

        return (int)trim($idResult->item(0)?->attributes?->getNamedItem('data-movie-id')?->nodeValue);
    }

    private function getTitle(DOMXPath $domXpath): string
    {
        $titleResult = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_TITLE);

        $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $titleResult->item(0)->nodeValue)));

        return $title;
    }

    private function getYear(DOMXPath $domXpath): string
    {
        $yearResult = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_YEAR);

        $year = $yearResult->item(0)->nodeValue ?? '';

        return trim($year);
    }

    /**
     * @return Director[]
     */
    private function getDirectors(DOMXPath $domXpath): array
    {
        $directors = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_DIRECTORS);

        $out = [];

        foreach ($directors as $director) {
            $out[] = $this->getDirector($director);
        }

        return $out;
    }

    private function getDirector(DOMElement $item): Director
    {
        $url = trim($item->getAttribute('href'));

        $directorId   = (int)substr($url, strpos($url, $this->urlPattern) + strlen($this->urlPattern), -1);
        $directorName = trim($item->nodeValue);

        return new Director($directorId, $directorName);
    }
}
