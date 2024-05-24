<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Application\Services\Films;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\App\Application\Dto\FilmDto;

final class GetFilmByIdService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $filmUrl,
        private Extractor $extractor,
        private FilmParser $parser
    ) {
    }

    public function loadFilm(int $filmId): FilmDto
    {
        $pageContent = $this->extractor->getData($this->getUrl($filmId));

        $this->parser->init($pageContent);

        $filmDto = $this->parser->getFilmDto($filmId);

        return $filmDto;
    }

    private function getUrl(int $filmId): string
    {
        return str_replace('{1}', (string)$filmId, $this->filmUrl);
    }
}
