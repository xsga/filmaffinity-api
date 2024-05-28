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

        $idAux   = $idSearch->item(0)->getAttribute('content');
        $idArray = explode('/', $idAux);
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

        $out = new SearchResults();

        $out->total = $searchResults->length;

        for ($i = 0; $i < $out->total; $i++) {
            $dom = new DOMDocument();

            $dom->appendChild($dom->importNode($searchResults->item($i), true));

            $domXpath = new DOMXPath($dom);

            $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);
            $yearResult  = $domXpath->query(XpathCons::SEARCH_YEAR);
            $idResult    = $domXpath->query(XpathCons::SEARCH_ID);

            $title = $titleResult->item(0)->nodeValue;
            $year  = $yearResult->item(0)->nodeValue;
            $id    = $idResult->item(0)->getAttribute('data-movie-id');

            $searchResult         = new SingleSearchResult();
            $searchResult->id     = (int)trim($id);
            $searchResult->title  = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));
            $searchResult->title .= ' (' . trim($year) . ')';

            $out->results[] = $searchResult;
        }

        return $out;
    }
}
