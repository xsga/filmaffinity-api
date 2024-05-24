<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Parser;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Xsga\FilmAffinityApi\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Dto\SingleSearchResultDto;

final class SimpleSearchParser extends AbstractParser
{
    public function getSimpleSearchResultsDto(): SearchResultsDto
    {
        $result = $this->getData(XpathCons::SEARCH_TYPE, false);

        if (($result->length > 0) && ($result->item(0)->getAttribute('content') !== 'FilmAffinity')) {
            $out = $this->simpleSearchSingleResult($result);
        } else {
            $out = $this->simpleSearchMultipleResults();
        }

        $this->logger->info('FilmAffinity search: ' . $out->total . ' results found');

        return $out;
    }

    private function simpleSearchSingleResult(DOMNodeList $data): SearchResultsDto
    {
        $idSearch = $this->getData(XpathCons::SEARCH_ID_SINGLE, false);

        $idAux   = $idSearch->item(0)->getAttribute('content');
        $idArray = explode('/', $idAux);
        $title   = $data->item(0)->getAttribute('content');

        $id    = trim(str_replace('film', '', str_replace('.html', '', end($idArray))));
        $title = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));

        $searchResult        = new SingleSearchResultDto();
        $searchResult->id    = (int)$id;
        $searchResult->title = $title;

        $out = new SearchResultsDto();

        $out->total     = 1;
        $out->results[] = $searchResult;

        return $out;
    }

    private function simpleSearchMultipleResults(): SearchResultsDto
    {
        $searchResults = $this->getData(XpathCons::SEARCH_RESULTS, false);

        $out = new SearchResultsDto();

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

            $searchResult         = new SingleSearchResultDto();
            $searchResult->id     = (int)trim($id);
            $searchResult->title  = trim(str_replace('  ', ' ', str_replace('   ', ' ', $title)));
            $searchResult->title .= ' (' . trim($year) . ')';

            $out->results[] = $searchResult;
        }

        return $out;
    }
}
