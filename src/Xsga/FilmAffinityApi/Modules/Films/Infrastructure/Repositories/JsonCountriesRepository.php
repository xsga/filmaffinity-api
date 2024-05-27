<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers\JsonCountryToCountry;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonLoaderService;

final class JsonCountriesRepository implements CountriesRepository
{
    private string $countriesFilename;

    public function __construct(
        private LoggerInterface $logger,
        private GetSchemaService $schema,
        private JsonLoaderService $jsonLoader,
        private string $countriesPath,
        private string $language,
        private JsonCountryToCountry $jsonCountriesToModelMapper
    ) {
        $this->countriesFilename = 'countries-' . strtoupper($language) . '.json';
    }

    /**
     * @return Country[]
     */
    public function getAll(): array
    {
        $countries = $this->jsonLoader->toArray(
            $this->countriesPath,
            $this->countriesFilename,
            null
        );

        if (empty($countries)) {
            $this->logger->error('Error loading countries files');
            return [];
        }

        return $this->jsonCountriesToModelMapper->convertArray($countries);
    }

    public function get(string $code): ?Country
    {
        foreach ($this->getAll() as $country) {
            if ($country->code === $code) {
                return $country;
            }
        }

        $this->logger->warning("Country with code '$code' not found");

        return null;
    }
}
