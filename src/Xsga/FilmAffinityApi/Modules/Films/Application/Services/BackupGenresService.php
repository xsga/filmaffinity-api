<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityGenresRepository;

final class BackupGenresService
{
    public function __construct(
        private LoggerInterface $logger,
        private FilmAffinityGenresRepository $repository,
        private string $language,
        private string $destinationPath
    ) {
    }

    public function get(): bool
    {
        try {
            $genres     = $this->repository->getAll();
            $genresJson = json_encode($genres);
            $fileName   = "genres_$this->language.json";

            file_put_contents($this->destinationPath . $fileName, $genresJson);
        } catch (Throwable $exception) {
            $this->logger->error('Error storing genres backup');
            $this->logger->error($exception->__toString());
            
            return false;
        }

        return true;
    }
}
