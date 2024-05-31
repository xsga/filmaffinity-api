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
    public function getSimpleSearchResultsDto(): SearchResults
    {
        $result = $this->getData(XpathCons::SEARCH_TYPE, false);

        $out = match (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
            true => $this->simpleSearchSingleResult($result),
            false => $this->simpleSearchMultipleResults()
        };

        $this->logger->info('FilmAffinity search: ' . $out->total . ' results found');

        return $out;
    }

    private function simpleSearchSingleResult(DOMNodeList $data): SearchResults
    {
        $idSearch = $this->getData(XpathCons::SEARCH_ID_SINGLE, false);

        $idArray = explode('/', $idSearch->item(0)->getAttribute('content'));
        $title   = $data->item(0)->getAttribute('content');

        $id    = trim(str_replace('film', '', str_replace('.html', '', end($idArray))));
        $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));

        $searchResult        = new SingleSearchResult();
        $searchResult->id    = (int)$id;
        $searchResult->title = $title;

        $out = new SearchResults();

        $out->total     = 1;
        $out->results[] = $searchResult;

        return $out;
    }

    private function simpleSearchMultipleResults(): SearchResults
    {
        $searchResults = $this->getData(XpathCons::SEARCH_RESULTS, false);

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
        $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);

        $title = $titleResult->item(0)->nodeValue;
        $year  = $this->getYear($domXpath);

        return trim(str_replace('  ', ' ', str_replace('   ', ' ', $title))) . ' (' . trim($year) . ')';
    }

    private function getYear(DOMXPath $domXpath): string
    {
        $yearResult = $domXpath->query(XpathCons::SEARCH_YEAR);

        return $yearResult->item(0)->nodeValue;
    }

    private function getId(DOMXPath $domXpath): int
    {
        $idResult  = $domXpath->query(XpathCons::SEARCH_ID);

        return (int)trim($idResult->item(0)->getAttribute('data-movie-id'));
    }
}
