<?php

/**
 * LoadFilm.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Business\Films;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Business\Extractor\Extractor;
use Xsga\FilmAffinityApi\Business\Parser\FilmParser;
use Xsga\FilmAffinityApi\Dto\FilmDto;

/**
 * Class LoadFilm.
 */
final class LoadFilm
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private string $filmUrl,
        private Extractor $extractor,
        private FilmParser $parser
    ) {
    }

    /**
     * Load film.
     */
    public function loadFilm(string $filmId): FilmDto
    {
        // Get page content.
        $pageContent = $this->extractor->getData($this->getUrl($filmId));

        // Inits parser.
        $this->parser->init($pageContent);

        // Get film DTO.
        $filmDto = $this->parser->getFilmDto($filmId);

        return $filmDto;
    }

    /**
     * Get film URL
     */
    private function getUrl(string $filmId): string
    {
        return str_replace('{1}', $filmId, $this->filmUrl);
    }
}
