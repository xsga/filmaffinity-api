<?php
/**
 * XsgaAbstractBusiness.
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
namespace xsgaphp\core;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractClass;
use xsgaphp\api\dto\ApiFiltersDto;

/**
 * XsgaAbstractBusiness class.
 */
abstract class XsgaAbstractBusiness extends XsgaAbstractClass
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
                    if (is_numeric($value) && $value > 0) {
                        $filtersDto->setPageNumber($value);
                    } else {
                        $this->logger->error("Query filter PAGE not valid ($value)");
                    }//end if
                    break;

                case 'page_size':
                    if (is_numeric($value) && $value > 0) {
                        $filtersDto->setPageSize($value);
                    } else {
                        $this->logger->error("Query filter PAGE_SIZE not valid ($value)");
                    }//end if
                    break;

                case 'order':
                    $filtersDto->setOrderField($value);
                    break;

                case 'order_type':
                    if (upper($value) === 'ASC' || upper($value) === 'DESC') {
                        $filtersDto->setOrderType($value);
                    } else {
                        $this->logger->error("Query filter ORDER_TYPE not valid ($value)");
                    }//end if
                    break;

            }//end switch

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

        return $filtersDto;

    }//end getQueryFilters()
    
    
}//end XsgaAbstractBusiness class
