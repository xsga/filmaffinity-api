<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Search;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\SearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetSimpleSearchResultsService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinitySearchRepository implements SearchRepository
{
    public function __construct(
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private GetSimpleSearchResultsService $getResultsService
    ) {
    }

    public function get(Search $search): SearchResults
    {
        $searchUrl   = $this->urlService->getSearchUrl($search);
        $pageContent = $this->httpClientService->getPageContent($searchUrl);

        return $this->getResultsService->get($pageContent);
    }
}
