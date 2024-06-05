<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\CountryDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\CountryToCountryDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;

final class GetAllCountriesService
{
    public function __construct(
        private LoggerInterface $logger,
        private CountriesRepository $countriesRepository,
        private CountryToCountryDto $mapper
    ) {
    }

    /**
     * @return CountryDto[]
     */
    public function get(): array
    {
        return $this->mapper->convertArray($this->countriesRepository->getAll());
    }
}
