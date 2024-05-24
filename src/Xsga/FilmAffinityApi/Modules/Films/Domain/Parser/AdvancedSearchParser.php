<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Parser;

use DOMDocument;
use DOMXPath;
use Xsga\FilmAffinityApi\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Dto\SingleSearchResultDto;

final class AdvancedSearchParser extends AbstractParser
{
    public function getAdvSearchResultsDto(): SearchResultsDto
    {
        $out = new SearchResultsDto();

        $result = $this->getData(XpathCons::SEARCH_ADV, false);

        $totalResults = $result->length;

        $out->total = $totalResults;

        for ($i = 0; $i < $totalResults; $i++) {
            $dom = new DOMDocument();

            $dom->appendChild($dom->importNode($result->item($i), true));

            $data     = strtolower(preg_replace('~\s+~u', '', $dom->textContent));
            $dataAux  = preg_replace("#\(\d{4}\)#", '#', $data);
            $position = strpos($dataAux, '#');
            $year     = substr($data, $position, 6);

            $domXpath = new DOMXPath($dom);

            $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);
            $idResult    = $domXpath->query(XpathCons::SEARCH_ID);

            $title = $titleResult->item(0)->nodeValue;
            $id    = $idResult->item(0)->getAttribute('data-movie-id');

            $searchResult         = new SingleSearchResultDto();
            $searchResult->id     = (int)trim($id);
            $searchResult->title  = trim(str_replace('  ', ' ', trim(str_replace('   ', ' ', $title))) . ' ' . $year);

            $out->results[] = $searchResult;
        }

        $this->logger->info("FilmAffinity search: $totalResults results found");

        return $out;
    }
}
