<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;

final class JsonCountryToCountry
{
    public function convert(array $jsonCountry): Country
    {
        $country       = new Country();
        $country->code = $jsonCountry['country_code'];
        $country->name = $jsonCountry['country_name'];

        return $country;
    }

    /**
     * @return Country[]
     */
    public function convertArray(array $jsonCountries): array
    {
        $out = [];

        foreach ($jsonCountries as $jsonCountry) {
            $out[] = $this->convert($jsonCountry);
        }

        return $out;
    }
}
