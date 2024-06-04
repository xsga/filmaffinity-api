<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\FilmsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinityFilmsRepository implements FilmsRepository
{
    public function __construct(
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private FilmParser $parser
    ) {
    }

    public function get(int $filmId): Film
    {
        $filmUrl     = $this->urlService->getFilmUrl($filmId);
        $pageContent = $this->httpClientService->getPageContent($filmUrl);

        $this->parser->init($pageContent);

        return $this->parser->getFilm($filmId);
    }
}
