<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use DOMElement;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Actor;

final class FilmCastParser extends AbstractParser
{
    private const string QUERY_FILM_GET_ACTORS = "//li[@itemprop = 'actor']/a";

    private string $urlPattern = 'name-id=';

    /**
     * @return Actor[]
     */
    public function getCast(): array
    {
        $data = $this->getData(self::QUERY_FILM_GET_ACTORS);

        $out = [];

        foreach ($data as $item) {
            $out[] = $this->getActor($item);
        }

        $this->logger->debug(count($out) . ' actors found');

        return $out;
    }

    private function getActor(DOMElement $item): Actor
    {
        $url = trim($item->getAttribute('href'));

        $actorId   = (int)substr($url, strpos($url, $this->urlPattern) + strlen($this->urlPattern), -1);
        $actorName = trim($item->nodeValue ?? '');

        return new Actor($actorId, $actorName);
    }
}
