<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Countries;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\CountryDto;


final class Countries
{
    /**
     * @var CountryDto[]
     */
    private array $countries;

    public function __construct(private LoggerInterface $logger, string $language)
    {
        $this->countries = $this->load(strtoupper($language));
    }

    /**
     * @return CountryDto[]
     */
    private function load(string $language): array
    {
        $out = [];

        $countriesLocation  = realpath(dirname(__FILE__, 3)) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;
        $countriesLocation .= 'Data' . DIRECTORY_SEPARATOR . 'countries-' . $language . '.json';

        if (!file_exists($countriesLocation)) {
            $this->logger->error("File '$countriesLocation' not found");
            return $out;
        }

        $countries = json_decode(file_get_contents($countriesLocation), true);

        if (empty($countries)) {
            $this->logger->warning("File '$countriesLocation' it's empty");
            return $out;
        }

        foreach ($countries as $country) {
            $countryDto       = new CountryDto();
            $countryDto->code = $country['country_code'];
            $countryDto->name = $country['country_name'];

            $out[] = $countryDto;
        }

        return $out;
    }

    /**
     * @return CountryDto[]
     */
    public function getAll(): array
    {
        return $this->countries;
    }

    public function get(string $code): CountryDto
    {
        foreach ($this->countries as $country) {
            if ($country->code === $code) {
                $this->logger->debug("Country with code '$code' found");
                return $country;
            }
        }

        $this->logger->warning("Country with code '$code' not found");

        return new CountryDto();
    }
}
