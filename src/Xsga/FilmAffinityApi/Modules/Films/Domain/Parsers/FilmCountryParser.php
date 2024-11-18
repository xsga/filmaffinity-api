<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;

final class FilmCountryParser extends AbstractParser
{
    private const string QUERY_FILM_GET_COUNTRY = "//img[@class = 'nflag']";

    public function getCountry(): Country
    {
        $data = $this->getData(self::QUERY_FILM_GET_COUNTRY);

        $countryCode = $this->getCountryCode($data->item(0)->attributes->getNamedItem('src')->nodeValue);
        $countryName = $data->item(0)->attributes->getNamedItem('alt')->nodeValue;

        return new Country($countryCode, $countryName ?? '');
    }

    private function getCountryCode(string $url): string
    {
        $urlArray     = explode('/', $url);
        $flagImg      = end($urlArray);
        $flagImgArray = explode('.', $flagImg);

        return $flagImgArray[0];
    }
}
