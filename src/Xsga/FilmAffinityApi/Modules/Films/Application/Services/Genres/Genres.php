<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Genres;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\GenreDto;

final class Genres
{
    /**
     * @var GenreDto[]
     */
    private array $genres;

    public function __construct(private LoggerInterface $logger, string $language)
    {
        $this->genres = $this->load(strtoupper($language));
    }

    /**
     * @return GenreDto[]
     */
    private function load($language): array
    {
        $out = [];

        $genresLocation  = realpath(dirname(__FILE__, 3)) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;
        $genresLocation .= 'Data' . DIRECTORY_SEPARATOR . 'genres-' . $language . '.json';

        if (!file_exists($genresLocation)) {
            $this->logger->error("File 'genresLocation' not found");
            return $out;
        }

        $genres = json_decode(file_get_contents($genresLocation), true);

        if (empty($genres)) {
            $this->logger->warning("File '$genresLocation' it's empty");
            return $out;
        }

        foreach ($genres as $genre) {
            $genreDto              = new GenreDto();
            $genreDto->code        = $genre['genre_code'];
            $genreDto->description = $genre['genre_desc'];

            $out[] = $genreDto;
        }

        return $out;
    }

    /**
     * @return GenreDto[]
     */
    public function getAll(): array
    {
        return $this->genres;
    }

    public function get(string $code): GenreDto
    {
        foreach ($this->genres as $genre) {
            if ($genre->code === $code) {
                $this->logger->debug("Genre with code '$code' found");
                return $genre;
            }
        }

        $this->logger->warning("Genre with code '$code' not found");

        return new GenreDto();
    }
}
