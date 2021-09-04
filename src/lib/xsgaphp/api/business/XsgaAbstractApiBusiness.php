<?php
/**
 * XsgaAbstractApiBusiness.
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
namespace xsgaphp\api\business;

/**
 * Import dependencies.
 */
use xsgaphp\core\abstract\XsgaAbstractClass;
use xsgaphp\api\dto\ApiFiltersDto;

/**
 * XsgaAbstractApiBusiness class.
 */
abstract class XsgaAbstractApiBusiness extends XsgaAbstractClass
{
    
        
    /**
     * Get query filters.
     * 
     * @param array $filters Filters array.
     * 
     * @return ApiFiltersDto
     * 
     * @access protected
     */
    protected function getQueryFilters(array $filters = array()) : ApiFiltersDto
    {
        // Logger.
        $this->logger->debugInit();

        // Get new ApiFiltersDto instance.
        $filtersDto = new ApiFiltersDto();

        foreach ($filters as $key => $value) {
            
            switch (strtolower($key)) {
                
                case 'page':
                    $filtersDto->setPageNumber($this->getPageFilter($value));
                    break;

                case 'page_size':
                    $filtersDto->setPageSize($this->getPageSizeFilter($value));
                    break;

                case 'sort':
                    $filtersDto->setSortFields($this->getSortFilter($value));
                    break;

            }//end switch

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

        return $filtersDto;

    }//end getQueryFilters()


    /**
     * Get page filter.
     * 
     * @param string $value Page number.
     * 
     * @return integer
     * 
     * @access private
     */
    private function getPageFilter(string $value) : int
    {
        // Logger.
        $this->logger->debugInit();

        if (is_numeric($value) && $value > 0) {
            $out = (int)$value;
        } else {
            $out = 0;
            $this->logger->error("Query filter PAGE not valid ($value)");
        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getPageFilter()


    /**
     * Get page size filter.
     * 
     * @param string $value Page size number.
     * 
     * @return integer
     * 
     * @access private
     */
    private function getPageSizeFilter(string $value) : int
    {
        // Logger.
        $this->logger->debugInit();

        if (is_numeric($value) && $value > 0) {
            $out = (int)$value;
        } else {
            $out = 0;
            $this->logger->error("Query filter PAGE_SIZE not valid ($value)");
        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getPageSizeFilter()


    /**
     * Get sort filter.
     * 
     * @param string $value Sort fields array.
     * 
     * @return array
     * 
     * @access private
     */
    private function getSortFilter(string $value) : array
    {
        // Logger.
        $this->logger->debugInit();

        // Get sort fields array.
        $sortFieldsArray = explode(',', $value);
        $sortFieldsArray = array_filter($sortFieldsArray, 'strlen');

        // Initialize output array.
        $out = array();

        foreach ($sortFieldsArray as $sortField) {

            // Get sort type flags.
            $sortAsc  = !strpos($sortField, '+') ? false : true;
            $sortDesc = !strpos($sortField, '-') ? false : true;

            // Get sort field.
            $field = str_replace('+', '', $sortField);
            $field = str_replace('-', '', $sortField);
            $field = trim($field);

            if (strlen($field) === 0) {
                // Logger.
                $this->logger->error('SORT field not valid');
                continue;
            }//end if

            // Set sort type.
            if ($sortAsc && !$sortDesc) {
                $sort = 'ASC';
            } else if (!$sortAsc && $sortDesc) {
                $sort = 'DESC';
            } else {
                $sort = 'ASC';

                // Logger.
                $this->logger->warn('Setting default sort type (ASC)');
            }//end if

            // Set sort field and type to output array.
            $data          = array();
            $data['field'] = $field;
            $data['sort']  = $sort;

            $out[] = $data;

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getSortFilter()
    
    
}//end XsgaAbstractApiBusiness class
