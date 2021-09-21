<?php
/**
 * ErrorsBusiness.
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
namespace xsgaphp\api\errors;

/**
 * Import dependencies.
 */
use xsgaphp\api\abstract\XsgaAbstractApiBusiness;
use xsgaphp\api\dto\ApiErrorsListDto;
use xsgaphp\core\errors\XsgaErrors;

/**
 * ErrorsBusiness.
 */
class ErrorsBusiness extends XsgaAbstractApiBusiness
{

    
    /**
     * Get API errors.
     * 
     * @return ApiErrorsListDto[]
     * 
     * @access public
     */
    public function getErrors() : array
    {
        // Logger.
        $this->logger->debugInit();

        // Initializes output.
        $out = array();

        foreach (XsgaErrors::getAllErrors() as $error) {

            $dto = new ApiErrorsListDto();

            $dto->code        = $error->code;
            $dto->description = $error->message;
            $dto->statusCode  = $error->httpCode;

            $out[] = $dto;

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getErrors()


}//end ErrorsBusiness class
