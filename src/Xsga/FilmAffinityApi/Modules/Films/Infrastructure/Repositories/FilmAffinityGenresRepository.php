<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetGenresService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinityGenresRepository implements GenresRepository
{
    public function __construct(
        private LoggerInterface $logger,
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private GetGenresService $getGenresService
    ) {
    }

    /**
     * @return Genre[]
     */
    public function getAll(): array
    {
        $advSearchFormUrl = $this->urlService->getAdvancedSearchFormUrl();
        $pageContent      = $this->httpClientService->getPageContent($advSearchFormUrl);

        $genres = $this->getGenresService->get($pageContent);

        if (empty($genres)) {
            $this->logger->error('Error getting genres from FilmAffinity');
            return [];
        }

        return $genres;
    }

    public function get(string $code): ?Genre
    {
        foreach ($this->getAll() as $genre) {
            if (strtoupper($genre->code()) === strtoupper($code)) {
                return $genre;
            }
        }

        $this->logger->warning("Genre with code '$code' not found");

        return null;
    }
}
