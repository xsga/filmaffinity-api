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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Film URL.
     *
     * @var string
     *
     * @access private
     */
    private $filmUrl;

    /**
     * Extractor.
     *
     * @var Extractor
     *
     * @access private
     */
    private $extractor;

    /**
     * Parser.
     *
     * @var FilmParser
     *
     * @access private
     */
    private $parser;

    /**
     * Constructor.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, string $filmUrl, Extractor $extractor, FilmParser $parser)
    {
        $this->logger    = $logger;
        $this->filmUrl   = $filmUrl;
        $this->extractor = $extractor;
        $this->parser    = $parser;
    }

    /**
     * Load film.
     *
     * @param string $filmId Film id.
     *
     * @return FilmDto
     *
     * @access public
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
     * Get film URL.
     *
     * @param string $filmId FilmAffinity film ID.
     *
     * @return string
     *
     * @access private
     */
    private function getUrl(string $filmId): string
    {
        return str_replace('{1}', $filmId, $this->filmUrl);
    }
}
