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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Countries.
     *
     * @var CountryDto[]
     *
     * @access private
     */
    private $countries;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger   LoggerInterface instance.
     * @param string          $language API language.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, string $language)
    {
        $this->logger    = $logger;
        $this->countries = $this->load(strtoupper($language));
    }

    /**
     * Load countries.
     *
     * @param string $language Application language.
     *
     * @return CountryDto[]
     *
     * @access private
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
     *
     * @access public
     */
    public function getAll(): array
    {
        return $this->countries;
    }

    /**
     * Get country.
     *
     * @param string $code Country code.
     *
     * @return CountryDto
     *
     * @access public
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
