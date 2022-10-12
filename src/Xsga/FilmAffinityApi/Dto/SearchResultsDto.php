<?php

/**
 * SearchResultsDto.
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
 * SearchResultsDto.
 */
class SearchResultsDto
{
    /**
     * Total results.
     *
     * @var integer
     *
     * @access public
     */
    public $total = 0;

    /**
     * Search results.
     *
     * @var SingleSearchResultDto[]
     *
     * @access public
     */
    public $results = array();
}
