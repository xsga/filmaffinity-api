<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\CountryDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;

class CountryToCountryDto
{
    public function convert(Country $country): CountryDto
    {
        $countryDto = new CountryDto();
        $countryDto->code = $country->code();
        $countryDto->name = $country->name();

        return $countryDto;
    }

    /**
     * @param Country[] $countries
     *
     * @return CountryDto[]
     */
    public function convertArray(array $countries): array
    {
        $out = [];

        foreach ($countries as $country) {
            $out[] = $this->convert($country);
        }

        return $out;
    }
}
