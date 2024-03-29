<?php

/**
 * Genres.
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
namespace Xsga\FilmAffinityApi\Business\Genres;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\GenreDto;

/**
 * Class Genres.
 */
final class Genres
{
    /**
     * Genres.
     *
     * @var GenreDto[]
     */
    private array $genres;

    /**
     * Constructor.
     */
    public function __construct(private LoggerInterface $logger, string $language)
    {
        $this->genres = $this->load(strtoupper($language));
    }

    /**
     * Load genres.
     *
     * @return GenreDto[]
     */
    private function load($language): array
    {
        $out = [];

        $genresLocation  = realpath(dirname(__FILE__, 3)) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;
        $genresLocation .= 'Data' . DIRECTORY_SEPARATOR . 'genres-' . $language . '.json';

        if (!file_exists($genresLocation)) {
            $this->logger->error("File \"$genresLocation\" not found");
            return $out;
        }//end if

        // Load file.
        $genres = json_decode(file_get_contents($genresLocation), true);

        if (empty($genres)) {
            $this->logger->warning("File \"$genresLocation\" it's empty");
            return $out;
        }//end if

        foreach ($genres as $genre) {
            $genreDto              = new GenreDto();
            $genreDto->code        = $genre['genre_code'];
            $genreDto->description = $genre['genre_desc'];

            $out[] = $genreDto;
        }//end foreach

        return $out;
    }

    /**
     * Get all genres.
     *
     * @return GenreDto[]
     */
    public function getAll(): array
    {
        return $this->genres;
    }

    /**
     * Get genres.
     */
    public function get(string $code): GenreDto
    {
        foreach ($this->genres as $genre) {
            if ($genre->code === $code) {
                $this->logger->debug("Genre with code \"$code\" found");
                return $genre;
            }//end if
        }//end foreach

        $this->logger->warning("Genre with code \"$code\" not found");

        return new GenreDto();
    }
}
