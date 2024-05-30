<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\FilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\FilmToFilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class GetFilmByIdService
{
    public function __construct(
        private HttpClientService $httpClientService,
        private FilmParser $parser,
        private FilmToFilmDto $mapper,
        private UrlService $urlService
    ) {
    }

    public function get(int $filmId): FilmDto
    {
        $filmUrl     = $this->urlService->getFilmUrl($filmId);
        $pageContent = $this->httpClientService->getPageContent($filmUrl);

        $this->parser->init($pageContent);

        $filmDto = $this->parser->getFilm($filmId);

        return $this->mapper->convert($filmDto);
    }
}
