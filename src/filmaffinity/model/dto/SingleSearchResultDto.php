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
namespace api\filmaffinity\model\dto;

/**
 * SingleSearchResultDto.
 */
class SingleSearchResultDto
{
    
    /**
     * FilmAffinity film ID.
     *
     * @var integer
     *
     * @access public
     */
    public $id;
    
    /**
     * Film title and release year.
     *
     * @var string
     *
     * @access public
     */
    public $title;
    
    
}//end SingleSearchResultDto class
