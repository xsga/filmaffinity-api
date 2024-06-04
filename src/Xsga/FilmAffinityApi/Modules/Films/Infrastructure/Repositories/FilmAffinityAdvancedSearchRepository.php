<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\AdvancedSearch;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\AdvancedSearchParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\AdvancedSearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinityAdvancedSearchRepository implements AdvancedSearchRepository
{
    public function __construct(
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private AdvancedSearchParser $parser
    ) {
    }

    public function get(AdvancedSearch $advancedSearch): SearchResults
    {
        $advancedSearchUrl = $this->urlService->getAdvancedSearchUrl($advancedSearch);
        $pageContent       = $this->httpClientService->getPageContent($advancedSearchUrl);

        $this->parser->init($pageContent);

        return $this->parser->getAdvSearchResults();
    }
}
