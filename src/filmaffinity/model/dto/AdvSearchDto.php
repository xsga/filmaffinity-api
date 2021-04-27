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
namespace api\filmaffinity\model\dto;

/**
 * AdvSearchDto.
 */
class AdvSearchDto
{
    
    /**
     * Search text.
     *
     * @var string
     *
     * @access public
     */
    public $searchText;
    
    /**
     * Search types title.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypeTitle = false;
    
    /**
     * Search types director.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypeDirector = false;
    
    /**
     * Search types cast.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypeCast = false;
    
    /**
     * Search types screenplay.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypeScreenplay = false;
    
    /**
     * Search types photography.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypePhotography = false;
    
    /**
     * Search types soundtrack.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypeSoundtrack = false;
    
    /**
     * Search types producer.
     *
     * @var boolean
     *
     * @access public
     */
    public $searchTypeProducer = false;
    
    /**
     * Search country.
     *
     * @var string
     *
     * @access public
     */
    public $searchCountry;
    
    /**
     * Search genre.
     *
     * @var string
     *
     * @access public
     */
    public $searchGenre;
    
    /**
     * Search year from.
     *
     * @var string
     *
     * @access public
     */
    public $searchYearFrom;
    
    /**
     * Search year to.
     *
     * @var string
     *
     * @access public
     */
    public $searchYearTo;
    
    
}//end AdvSearchDto class
