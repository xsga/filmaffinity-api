<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\FilmDto;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class GetFilmByIdService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $filmUrl,
        private HttpClientService $httpClientService,
        private FilmParser $parser
    ) {
    }

    public function get(int $filmId): FilmDto
    {
        $pageContent = $this->httpClientService->getPageContent($this->getUrl($filmId));

        $this->parser->init($pageContent);

        $filmDto = $this->parser->getFilmDto($filmId);

        return $filmDto;
    }

    private function getUrl(int $filmId): string
    {
        return str_replace('{1}', (string)$filmId, $this->filmUrl);
    }
}