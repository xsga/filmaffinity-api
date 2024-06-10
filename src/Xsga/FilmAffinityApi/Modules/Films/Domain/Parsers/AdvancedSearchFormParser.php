<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;

final class AdvancedSearchFormParser extends AbstractParser
{
    private const string QUERY_ADV_SEARCH_FORM_GET_GENRES = "//option[not(@data-content)]";
    private const string QUERY_ADV_SEARCH_FORM_GET_COUNTRIES = "//option[@data-class = 'flag-wrapper']";

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        $xpathResults = $this->getData(self::QUERY_ADV_SEARCH_FORM_GET_GENRES, false);

        $out = [];

        foreach ($xpathResults as $element) {
            if ($element->getAttribute('value') === '') {
                continue;
            }

            $genre = new Genre(
                $element->getAttribute('value'),
                trim($element->nodeValue)
            );

            $out[] = $genre;
        }
        
        return $out;
    }

    /**
     * @return Country[]
     */
    public function getCountries(): array
    {
        $xpathResults = $this->getData(self::QUERY_ADV_SEARCH_FORM_GET_COUNTRIES, false);

        $out = [];

        foreach ($xpathResults as $element) {
            $country = new Country(
                $element->getAttribute('value'),
                trim($element->nodeValue)
            );
            
            $out[] = $country;
        }
        
        return $out;
    }
}
