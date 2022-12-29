<?php

/**
 * SingleSearchResultDto.
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
 * SingleSearchResultDto.
 */
class SingleSearchResultDto
{
    /**
     * FilmAffinity film ID.
     */
    public int $id = 0;

    /**
     * Film title and release year.
     */
    public string $title = '';
}
