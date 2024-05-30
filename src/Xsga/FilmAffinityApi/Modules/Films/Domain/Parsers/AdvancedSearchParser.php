<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SingleSearchResult;

final class AdvancedSearchParser extends AbstractParser
{
    public function getAdvSearchResultsDto(): SearchResults
    {
        $out = new SearchResults();

        $xpathResults = $this->getData(XpathCons::SEARCH_ADV, false);
        $totalResults = $xpathResults->length;

        $out->total = $totalResults;

        for ($i = 0; $i < $totalResults; $i++) {
            $out->results[] = $this->getResultData($xpathResults, $i);
        }

        $this->logger->info("FilmAffinity search: $totalResults results found");

        return $out;
    }

    private function getResultData(DOMNodeList $node, int $itemNumber): SingleSearchResult
    {
        $dom = new DOMDocument();

        $dom->appendChild($dom->importNode($node->item($itemNumber), true));

        $domXpath = new DOMXPath($dom);

        $titleResult = $domXpath->query(XpathCons::SEARCH_TITLE);
        $idResult    = $domXpath->query(XpathCons::SEARCH_ID);
        $yearResult  = $domXpath->query(XpathCons::SEARCH_YEAR_ADV);

        $title = $titleResult->item(0)->nodeValue;
        $id    = $idResult->item(0)->getAttribute('data-movie-id');
        $year  = $yearResult->item(1)->nodeValue;

        $searchResult         = new SingleSearchResult();
        $searchResult->id     = (int)trim($id);
        $searchResult->title  = trim(str_replace('  ', ' ', trim(str_replace('   ', ' ', $title))) . ' (' . $year . ')');

        return $searchResult;
    }
}
