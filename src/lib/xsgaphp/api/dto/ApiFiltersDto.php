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
     * Order field.
     * 
     * @var string
     * 
     * @access private
     */
    private $orderField = '';

    /**
     * Order type.
     * 
     * @var string
     * 
     * @access private
     */
    private $orderType = '';


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
     * Set order field.
     * 
     * @param string $orderField Order field.
     * 
     * @return void
     * 
     * @access public
     */
    public function setOrderField(string $orderField) : void
    {
        $this->orderField = $orderField;

    }//end setOrderField()


    /**
     * Set order type.
     * 
     * @param string $orderType Order type.
     * 
     * @return void
     * 
     * @access public
     */
    public function setOrderType(string $orderType) : void
    {
        $this->orderType = $orderType;

    }//end setOrderType()


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
     * Get order field.
     * 
     * @return string
     * 
     * @access public
     */
    public function getOrderField() : string
    {
        return $this->orderField;

    }//end getOrderField()


    /**
     * Get order type.
     * 
     * @return string
     * 
     * @access public
     */
    public function getOrderType() : string
    {
        return $this->orderType;

    }//end getOrderType()
    
    
}//end ApiFiltersDto class
