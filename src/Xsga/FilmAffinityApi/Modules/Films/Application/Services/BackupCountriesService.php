<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityCountriesRepository;

final class BackupCountriesService
{
    public function __construct(
        private LoggerInterface $logger,
        private FilmAffinityCountriesRepository $repository,
        private string $language,
        private string $destinationPath
    ) {
    }

    public function get(): bool
    {
        try {
            $genres = $this->repository->getAll();

            $genresJson = json_encode($genres);

            $fileName = "countries_$this->language.json";

            file_put_contents($this->destinationPath . $fileName, $genresJson);
        } catch (Throwable $th) {
            return false;
        }

        return true;
    }
}
