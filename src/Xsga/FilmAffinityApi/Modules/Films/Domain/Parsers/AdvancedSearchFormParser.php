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

            $out[] = new Genre($element->getAttribute('value'), trim($element->nodeValue));
        }

        $this->logger->info('FilmAffinity genres: ' . count($out) . ' results found');
        
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
            $out[] = new Country($element->getAttribute('value'), trim($element->nodeValue));
        }

        $this->logger->info('FilmAffinity countries: ' . count($out) . ' results found');
        
        return $out;
    }
}
