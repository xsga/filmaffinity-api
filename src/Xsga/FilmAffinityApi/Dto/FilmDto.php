<?php

/**
 * FilmDto class.
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
namespace Xsga\FilmAffinityApi\Dto;

/**
 * FilmDto class.
 */
class FilmDto
{
    /**
     * FilmAffinity film ID.
     */
    public int $filmAfinityId = 0;

    /**
     * Film title.
     */
    public string $title = '';

    /**
     * Film original title.
     */
    public string $originalTitle = '';

    /**
     * Release year.
     */
    public string $year = '';

    /**
     * Film duration.
     */
    public string $duration = '';

    /**
     * Cover URL.
     */
    public string $coverUrl = '';

    /**
     * Cover filename.
     */
    public string $coverFile = '';

    /**
     * Rating.
     */
    public string $rating = '';

    /**
     * Country.
     */
    public string $country = '';

    /**
     * Directors.
     */
    public array $directors = [];

    /**
     * Screenplay.
     */
    public string $screenplay = '';

    /**
     * Sountrack.
     */
    public string $soundtrack = '';

    /**
     * Photography.
     */
    public string $photography = '';

    /**
     * Cast.
     */
    public array $cast = [];

    /**
     * Producer.
     */
    public string $producer = '';

    /**
     * Genres.
     */
    public array $genres = [];

    /**
     * Synopsis.
     */
    public string $synopsis = '';
}
