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
namespace api\filmaffinity\model\dto;

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
    public $total;
    
    /**
     * Search results.
     * 
     * @var SingleSearchResultDto[]
     * 
     * @access public
     */
    public $results = array();
    
    
}//end SearchResultsDto class
