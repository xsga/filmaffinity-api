<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMElement;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Actor;

final class FilmCastParser extends AbstractParser
{
    private const string QUERY_FILM_GET_ACTORS = "//li[@itemprop = 'actor']/a";
    
    /**
     * @return Actor[]
     */
    public function getCast(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_ACTORS, false);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getActor($item);
        }

        return $out;
    }

    private function getActor(DOMElement $item): Actor
    {
        $url = trim($item->getAttribute('href'));

        $actor = new Actor(
            (int)substr($url, strpos($url, 'name-id=') + 8, -1),
            trim($item->nodeValue)
        );

        return $actor;
    }
}
