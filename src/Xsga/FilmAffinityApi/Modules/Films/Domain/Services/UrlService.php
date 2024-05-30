<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Psr\Log\LoggerInterface;
// TODO: domain object
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;

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
        return str_replace('{1}', (string)$filmId, $this->filmUrl);
    }

    public function getSearchUrl(SearchDto $searchDto): string
    {
        $urlSearch = str_replace(
            '{1}',
            $this->prepareSearchText($searchDto->searchText),
            $this->searchUrl
        );

        $this->logger->debug('Search URL: ' . $urlSearch);

        return $urlSearch;
    }

    public function getAdvancedSearchUrl(AdvancedSearchDto $advancedSearchDto): string
    {
        $searchType  = '';
        $searchType .= $advancedSearchDto->searchTypeTitle ? '&stype[]=title' : '';
        $searchType .= $advancedSearchDto->searchTypeDirector ? '&stype[]=director' : '';
        $searchType .= $advancedSearchDto->searchTypeCast ? '&stype[]=cast' : '';
        $searchType .= $advancedSearchDto->searchTypeSoundtrack ? '&stype[]=music' : '';
        $searchType .= $advancedSearchDto->searchTypeScreenplay ? '&stype[]=script' : '';
        $searchType .= $advancedSearchDto->searchTypePhotography ? '&stype[]=photo' : '';
        $searchType .= $advancedSearchDto->searchTypeProducer ? '&stype[]=producer' : '';

        if ($searchType === '') {
            $this->logger->warning('No search type found. Set the default search type: title');
            $searchType = '&stype[]=title';
        }

        $url = $this->advancedSearchUrl;
        $url = str_replace('{1}', $this->prepareSearchText($advancedSearchDto->searchText), $url);
        $url = str_replace('{2}', $searchType, $url);
        $url = str_replace('{3}', $advancedSearchDto->searchCountry, $url);
        $url = str_replace('{4}', $advancedSearchDto->searchGenre, $url);
        $url = str_replace('{5}', $advancedSearchDto->searchYearFrom === 0 ? '' : $advancedSearchDto->searchYearFrom, $url);
        $url = str_replace('{6}', $advancedSearchDto->searchYearTo === 0 ? '' : $advancedSearchDto->searchYearTo, $url);

        $this->logger->debug('Advanced Search URL: ' . $url);

        return $url;
    }

    private function prepareSearchText(string $searchText): string
    {
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}
