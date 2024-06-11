<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMElement;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Director;

final class FilmDirectorsParser extends AbstractParser
{
    private const string QUERY_FILM_GET_DIRECTORS = "//span[@itemprop = 'director']/a";
    
    /**
     * @return Director[]
     */
    public function getDirectors(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_DIRECTORS, false);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getDirector($item);
        }

        return $out;
    }

    private function getDirector(DOMElement $item): Director
    {
        $url = trim($item->getAttribute('href'));

        $director = new Director(
            (int)substr($url, strpos($url, 'name-id=') + 8, -1),
            trim($item->nodeValue)
        );

        return $director;
    }
}
