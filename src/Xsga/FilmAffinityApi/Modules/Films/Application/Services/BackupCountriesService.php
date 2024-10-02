<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Log4Php\Logger;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityCountriesRepository;

final class BackupCountriesService
{
    public function __construct(
        private Logger $logger,
        private FilmAffinityCountriesRepository $repository,
        private string $language,
        private string $destinationPath
    ) {
    }

    public function get(): bool
    {
        try {
            $countries     = $this->repository->getAll();
            $countriesJson = json_encode($countries);
            $fileName      = "countries_$this->language.json";

            file_put_contents($this->destinationPath . $fileName, $countriesJson);
        } catch (Throwable $exception) {
            $this->logger->error('Error storing countries backup');
            $this->logger->error($exception->__toString());
            
            return false;
        }

        return true;
    }
}
