<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Cover;

final class FilmCoverParser extends AbstractParser
{
    private const string QUERY_FILM_GET_COVER = "//a[@class = 'lightbox']";

    public function getCover(): Cover
    {
        return new Cover($this->getCoverUrl(), $this->getCoverFileName());
    }

    private function getCoverUrl(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_COVER, false);

        if ($data->length === 0) {
            $this->logger->warning('Film cover URL not found');
            return '';
        }

        return trim($data->item(0)?->attributes?->getNamedItem('href')?->nodeValue);
    }

    private function getCoverFileName(): string
    {
        $data = $this->getData(self::QUERY_FILM_GET_COVER, false);

        if ($data->length === 0) {
            $this->logger->warning('Film cover file not found');
            return '';
        }

        $coverUrl      = trim($data->item(0)?->attributes?->getNamedItem('href')?->nodeValue);
        $coverUrlArray = explode('/', $coverUrl);
        $coverFile     = end($coverUrlArray);

        return $coverFile;
    }
}
