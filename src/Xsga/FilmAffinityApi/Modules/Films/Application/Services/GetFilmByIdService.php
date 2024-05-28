<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\FilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\FilmToFilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class GetFilmByIdService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $filmUrl,
        private HttpClientService $httpClientService,
        private FilmParser $parser,
        private FilmToFilmDto $mapper
    ) {
    }

    public function get(int $filmId): FilmDto
    {
        $pageContent = $this->httpClientService->getPageContent($this->getUrl($filmId));

        $this->parser->init($pageContent);

        $filmDto = $this->parser->getFilm($filmId);

        return $this->mapper->convert($filmDto);
    }

    private function getUrl(int $filmId): string
    {
        return str_replace('{1}', (string)$filmId, $this->filmUrl);
    }
}
