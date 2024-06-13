<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\AdvancedSearch;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Search;

final class UrlService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $filmUrl,
        private string $searchUrl,
        private string $advancedSearchUrl
    ) {
    }

    public function getFilmUrl(int $filmId): string
    {
        $url = str_replace('{1}', (string)$filmId, $this->filmUrl);

        $this->logger->debug("Film URL: $url");

        return $url;
    }

    public function getSearchUrl(Search $search): string
    {
        $url = str_replace('{1}', $this->prepareSearchText($search->text()), $this->searchUrl);

        $this->logger->debug("Search URL: $url");

        return $url;
    }

    public function getAdvancedSearchFormUrl(): string
    {
        $urlArray = explode('?', $this->advancedSearchUrl);
        $url      = $urlArray[0];

        $this->logger->debug("Advanced search form URL: $url");

        return $url;
    }

    public function getAdvancedSearchUrl(AdvancedSearch $advancedSearch): string
    {
        $url = $this->advancedSearchUrl;
        $url = str_replace('{1}', $this->prepareSearchText($advancedSearch->text()), $url);
        $url = str_replace('{2}', $this->getAdvancedSearchType($advancedSearch), $url);
        $url = str_replace('{3}', $advancedSearch->countryCode(), $url);
        $url = str_replace('{4}', $advancedSearch->genreCode(), $url);
        $url = str_replace('{5}', $advancedSearch->yearFrom() === 0 ? '' : (string)$advancedSearch->yearFrom(), $url);
        $url = str_replace('{6}', $advancedSearch->yearTo() === 0 ? '' : (string)$advancedSearch->yearTo(), $url);

        $this->logger->debug("Advanced Search URL: $url");

        return $url;
    }

    private function getAdvancedSearchType(AdvancedSearch $advancedSearch): string
    {
        $searchType  = '';
        $searchType .= $advancedSearch->typeTitle() ? '&stype[]=title' : '';
        $searchType .= $advancedSearch->typeDirector() ? '&stype[]=director' : '';
        $searchType .= $advancedSearch->typeCast() ? '&stype[]=cast' : '';
        $searchType .= $advancedSearch->typeSoundtrack() ? '&stype[]=music' : '';
        $searchType .= $advancedSearch->typeScreenplay() ? '&stype[]=script' : '';
        $searchType .= $advancedSearch->typePhotography() ? '&stype[]=photo' : '';
        $searchType .= $advancedSearch->typeProducer() ? '&stype[]=producer' : '';

        if (empty($searchType)) {
            $this->logger->warning('No search type found. Set the default search type: title');
            $searchType = '&stype[]=title';
        }

        return $searchType;
    }

    private function prepareSearchText(string $searchText): string
    {
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}
