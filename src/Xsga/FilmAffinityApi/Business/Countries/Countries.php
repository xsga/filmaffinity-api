<?php

/**
 * Countries.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Business\Countries;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\CountryDto;

/**
 * Class Countries.
 */
final class Countries
{
    /**
     * Countries.
     *
     * @var CountryDto[]
     */
    private array $countries;

    /**
     * Constructor.
     */
    public function __construct(private LoggerInterface $logger, string $language)
    {
        $this->countries = $this->load(strtoupper($language));
    }

    /**
     * Load countries.
     *
     * @return CountryDto[]
     */
    private function load(string $language): array
    {
        $out = array();

        $countriesLocation  = realpath(dirname(__FILE__, 3)) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;
        $countriesLocation .= 'Data' . DIRECTORY_SEPARATOR . 'countries-' . $language . '.json';

        if (!file_exists($countriesLocation)) {
            $this->logger->error("File \"$countriesLocation\" not found");
            return $out;
        }//end if

        // Load file.
        $countries = json_decode(file_get_contents($countriesLocation), true);

        if (empty($countries)) {
            $this->logger->warning("File \"$countriesLocation\" it's empty");
            return $out;
        }//end if

        foreach ($countries as $country) {
            $countryDto       = new CountryDto();
            $countryDto->code = $country['country_code'];
            $countryDto->name = $country['country_name'];

            $out[] = $countryDto;
        }//end foreach

        return $out;
    }

    /**
     * Get all countries.
     *
     * @return CountryDto[]
     */
    public function getAll(): array
    {
        return $this->countries;
    }

    /**
     * Get country.
     */
    public function get(string $code): CountryDto
    {
        foreach ($this->countries as $country) {
            if ($country->code === $code) {
                $this->logger->debug("Country with code \"$code\" found");
                return $country;
            }//end if
        }//end foreach

        $this->logger->warning("Country with code \"$code\" not found");

        return new CountryDto();
    }
}
