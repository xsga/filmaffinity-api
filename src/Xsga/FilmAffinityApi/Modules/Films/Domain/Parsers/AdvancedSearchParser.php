<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Director;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SingleSearchResult;

final class AdvancedSearchParser extends AbstractParser
{
    private const string QUERY_ADV_SEARCH_DATA = "//div[contains(@class, 'adv-search-item')]";
    private const string QUERY_ADV_SEARCH_GET_TITLE = "//div[@class = 'mc-title']/a";
    private const string QUERY_ADV_SEARCH_GET_ID = "//div[contains(@class, 'movie-card')]";
    private const string QUERY_ADV_SEARCH_GET_YEAR = "//span[contains(@class, 'mc-year')]";
    private const string QUERY_ADV_SEARCH_GET_DIRECTORS = "//div[contains(@class, 'mc-director')]//a";

    private string $urlPattern = 'name-id=';

    public function getAdvSearchResults(): SearchResults
    {
        $xpathResults = $this->getData(self::QUERY_ADV_SEARCH_DATA);
        $totalResults = $xpathResults->length;

        $this->logger->debug("FilmAffinity search: $totalResults results found");

        return new SearchResults($totalResults, $this->getResults($xpathResults));
    }

    private function getResults(DOMNodeList $results): array
    {
        $out = [];

        for ($i = 0; $i < $results->length; $i++) {
            $out[] = $this->getResultData($results, $i);
        }

        return $out;
    }

    private function getResultData(DOMNodeList $node, int $itemNumber): SingleSearchResult
    {
        $dom = new DOMDocument();
        $dom->appendChild($dom->importNode($node->item($itemNumber), true));
        $domXpath = new DOMXPath($dom);

        return new SingleSearchResult(
            $this->getFilmId($domXpath),
            $this->getFilmTitle($domXpath),
            $this->getFilmYear($domXpath),
            $this->getFilmDirectors($domXpath)
        );
    }

    private function getFilmId(DOMXPath $domXpath): int
    {
        $idResult = $domXpath->query(self::QUERY_ADV_SEARCH_GET_ID);
        $id       = $idResult->item(0)->attributes->getNamedItem('data-movie-id')->nodeValue ?? '';

        return (int)trim($id);
    }

    private function getFilmTitle(DOMXPath $domXpath): string
    {
        $titleResult = $domXpath->query(self::QUERY_ADV_SEARCH_GET_TITLE);
        $title       = str_replace('  ', ' ', trim(str_replace('   ', ' ', $titleResult->item(0)->nodeValue)));

        return trim($title ?? '');
    }

    private function getFilmYear(DOMXPath $domXpath): int
    {
        $yearResult  = $domXpath->query(self::QUERY_ADV_SEARCH_GET_YEAR);
        $year        = $yearResult->item(1)->nodeValue ?? '';

        return (int)trim($year);
    }

    /**
     * @return Director[]
     */
    private function getFilmDirectors(DOMXPath $domXpath): array
    {
        $directorsResult = $domXpath->query(self::QUERY_ADV_SEARCH_GET_DIRECTORS);

        $out = [];

        foreach ($directorsResult as $director) {
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
