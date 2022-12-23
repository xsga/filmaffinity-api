<?php

/**
 * AdvSearchDto.
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
 * AdvSearchDto.
 */
class AdvSearchDto
{
    /**
     * Search text.
     */
    public string $searchText = '';

    /**
     * Search types title.
     */
    public bool $searchTypeTitle = false;

    /**
     * Search types director.
     */
    public bool $searchTypeDirector = false;

    /**
     * Search types cast.
     */
    public bool $searchTypeCast = false;

    /**
     * Search types screenplay.
     */
    public bool $searchTypeScreenplay = false;

    /**
     * Search types photography.
     */
    public bool $searchTypePhotography = false;

    /**
     * Search types soundtrack.
     */
    public bool $searchTypeSoundtrack = false;

    /**
     * Search types producer.
     */
    public bool $searchTypeProducer = false;

    /**
     * Search country.
     */
    public string $searchCountry = '';

    /**
     * Search genre.
     */
    public string $searchGenre = '';

    /**
     * Search year from.
     */
    public int $searchYearFrom = 0;

    /**
     * Search year to.
     */
    public int $searchYearTo = 0;
}
