<?php
/**
 * SearchResultsDto.
 *
 * PHP version 7
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace api\model\dto;

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
