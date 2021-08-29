<?php
/**
 * ApiFiltersDto.
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
namespace xsgaphp\api\dto;

/**
 * Class ApiFiltersDto.
 */
class ApiFiltersDto
{
    
    /**
     * Page number.
     * 
     * @var integer
     * 
     * @access private
     */
    private $pageNumber = 0;
    
    /**
     * Page size.
     * 
     * @var integer
     * 
     * @access private
     */
    private $pageSize = 0;

    /**
     * Sort fields.
     * 
     * @var array
     * 
     * @access private
     */
    private $sortFields = array();

    
    /**
     * Set page number.
     * 
     * @param integer $pageNumber Page number.
     * 
     * @return void
     * 
     * @access public
     */
    public function setPageNumber(int $pageNumber) : void
    {
        $this->pageNumber = $pageNumber;

    }//end setPageNumber()


    /**
     * Set page size.
     * 
     * @param integer $pageSize Page size.
     * 
     * @return void
     * 
     * @access public
     */
    public function setPageSize(int $pageSize) : void
    {
        $this->pageSize = $pageSize;

    }//end setPageSize()


    /**
     * Set sort fields.
     * 
     * @param array $sortFields Array with sort field and type.
     * 
     * @return void
     * 
     * @access public
     */
    public function setSortFields(array $sortFields) : void
    {
        $this->sortFields = $sortFields;

    }//end setSortFields()


    /**
     * Get page number.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getPageNumber() : int
    {
        return $this->pageNumber;

    }//end getPageNumber()


    /**
     * Get page size.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getPageSize() : int
    {
        return $this->pageSize;

    }//end getPageSize()


    /**
     * Get sort fields.
     * 
     * @return array
     * 
     * @access public
     */
    public function getSortFields() : array
    {
        return $this->sortFields;

    }//end getSortFields()
    
    
}//end ApiFiltersDto class
