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
use xsgaphp\core\XsgaAbstractBusiness;
use xsgaphp\api\dto\ApiErrorsListDto;
use xsgaphp\utils\XsgaLoadFile;

/**
 * ErrorsBusiness.
 */
class ErrorsBusiness extends XsgaAbstractBusiness
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

        // Get language file.
        $language = XsgaLoadFile::language();

        // Get errors file.
        $errors = XsgaLoadFile::errors();

        // Initializes output.
        $out = array();

        foreach ($errors as $error) {

            $dto = new ApiErrorsListDto();

            $dto->code        = $error['api_code'];
            $dto->description = $language['errors']['error_'.$error['api_code']];
            $dto->statusCode  = $error['http_code'];

            $out[] = $dto;

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getErrors()


}//end ErrorsBusiness class
