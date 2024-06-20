<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\FilmsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetFilmService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinityFilmsRepository implements FilmsRepository
{
    public function __construct(
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private GetFilmService $getFilm
    ) {
    }

    public function get(int $filmId): Film
    {
        $filmUrl     = $this->urlService->getFilmUrl($filmId);
        $pageContent = $this->httpClientService->getPageContent($filmUrl);

        return $this->getFilm->get($filmId, $pageContent);
    }
}
