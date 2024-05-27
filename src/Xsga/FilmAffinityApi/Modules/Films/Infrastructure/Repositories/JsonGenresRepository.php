<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers\JsonGenreToGenre;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonLoaderService;

final class JsonGenresRepository implements GenresRepository
{
    private string $genresFilename;

    public function __construct(
        private LoggerInterface $logger,
        private GetSchemaService $schema,
        private JsonLoaderService $jsonLoader,
        private string $genresPath,
        private string $language,
        private JsonGenreToGenre $jsonGenreToModelMapper
    ) {
        $this->genresFilename = 'genres-' . strtoupper($language) . '.json';
    }

    /**
     * @return Genre[]
     */
    public function getAll(): array
    {
        $genres = $this->jsonLoader->toArray(
            $this->genresPath,
            $this->genresFilename,
            null
        );

        if (empty($genres)) {
            $this->logger->error('Error loading genres files');
            return [];
        }

        return $this->jsonGenreToModelMapper->convertArray($genres);
    }

    public function get(string $code): ?Genre
    {
        foreach ($this->getAll() as $genre) {
            if ($genre->code === $code) {
                return $genre;
            }
        }

        $this->logger->warning("Genre with code '$code' not found");

        return null;
    }
}
