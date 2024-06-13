<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMElement;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Director;

final class FilmDirectorsParser extends AbstractParser
{
    private const string QUERY_FILM_GET_DIRECTORS = "//span[@itemprop = 'director']/a";

    private string $urlPattern = 'name-id=';
    
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

        $directorId   = (int)substr($url, strpos($url, $this->urlPattern) + strlen($this->urlPattern), -1);
        $directorName = trim($item->nodeValue);

        return new Director($directorId, $directorName);
    }
}
