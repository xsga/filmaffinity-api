<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SingleSearchResult;

final class SimpleSearchParser extends AbstractParser
{
    private const QUERY_RESULTS_TYPE = "//meta[@property = 'og:title']";
    private const QUERY_SINGLE_RESULT_GET_ID = "//meta[@property = 'og:url']";
    private const QUERY_MULTIPLE_RESULTS_DATA = "//div[contains(@class, 'se-it')]";
    private const QUERY_MULTIPLE_RESULTS_GET_TITLE = "//div[@class = 'mc-title']/a";
    private const QUERY_MULTIPLE_RESULTS_GET_YEAR = "//div[contains(@class, 'ye-w')]";
    private const QUERY_MULTIPLE_RESULTS_GET_ID = "//div[contains(@class, 'movie-card')]";

    public function getSimpleSearchResults(): SearchResults
    {
        $queyResults = $this->getData(self::QUERY_RESULTS_TYPE, false);

        $searchResults = match (
            ($queyResults->length > 0) && 
            ($queyResults->item(0)?->attributes?->getNamedItem('content')?->nodeValue !== 'FilmAffinity')
        ) {
            true => $this->simpleSearchSingleResult($queyResults),
            false => $this->simpleSearchMultipleResults()
        };

        $this->logger->info("FilmAffinity search: $searchResults->total results found");

        return $searchResults;
    }

    private function simpleSearchSingleResult(DOMNodeList $data): SearchResults
    {
        $idSearch = $this->getData(self::QUERY_SINGLE_RESULT_GET_ID, false);

        $idArray = explode('/', $idSearch->item(0)?->attributes?->getNamedItem('content')?->nodeValue);
        $title   = $data->item(0)?->attributes?->getNamedItem('content')?->nodeValue;

        $searchResult        = new SingleSearchResult();
        $searchResult->id    = (int)trim(str_replace('film', '', str_replace('.html', '', end($idArray))));
        $searchResult->title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));

        $out = new SearchResults();
        $out->total     = 1;
        $out->results[] = $searchResult;

        return $out;
    }

    private function simpleSearchMultipleResults(): SearchResults
    {
        $searchResults = $this->getData(self::QUERY_MULTIPLE_RESULTS_DATA, false);

        $out        = new SearchResults();
        $out->total = $searchResults->length;

        for ($i = 0; $i < $out->total; $i++) {
            $out->results[] = $this->getSearchResult($searchResults, $i);
        }

        return $out;
    }

    private function getSearchResult(DOMNodeList $searchResults, int $element): SingleSearchResult
    {
        $dom = new DOMDocument();
        $dom->appendChild($dom->importNode($searchResults->item($element), true));

        $domXpath = new DOMXPath($dom);

        $searchResult        = new SingleSearchResult();
        $searchResult->id    = $this->getId($domXpath);
        $searchResult->title = $this->getTitle($domXpath);

        return $searchResult;
    }

    private function getTitle(DOMXPath $domXpath): string
    {
        $titleResult = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_TITLE);

        $title = $titleResult->item(0)->nodeValue;
        $year  = $this->getYear($domXpath);

        return trim(str_replace('  ', ' ', str_replace('   ', ' ', $title))) . ' (' . trim($year) . ')';
    }

    private function getYear(DOMXPath $domXpath): string
    {
        $yearResult = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_YEAR);

        return $yearResult->item(0)->nodeValue ?? '';
    }

    private function getId(DOMXPath $domXpath): int
    {
        $idResult  = $domXpath->query(self::QUERY_MULTIPLE_RESULTS_GET_ID);

        return (int)trim($idResult->item(0)?->attributes?->getNamedItem('data-movie-id')?->nodeValue);
    }
}
