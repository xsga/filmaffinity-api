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
use xsgaphp\api\business\XsgaAbstractApiBusiness;
use xsgaphp\api\dto\ApiErrorsListDto;
use xsgaphp\core\utils\XsgaLoadFile;
use xsgaphp\core\utils\XsgaPath;
use xsgaphp\core\utils\XsgaCheckFile;
use xsgaphp\core\exceptions\XsgaValidationException;

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

        // Get language file.
        $language = XsgaLoadFile::loadJson(XsgaPath::getPathTo(array('src', 'language')), strtolower($_ENV['LANGUAGE']).'.json');

        // Initializes output.
        $out = array();

        // Get errors file.
        if (XsgaCheckFile::apiErrors($errors)) {

            foreach ($errors as $error) {

                $dto = new ApiErrorsListDto();
    
                $dto->code        = $error['api_code'];
                $dto->description = $language['errors']['error_'.$error['api_code']];
                $dto->statusCode  = $error['http_code'];
    
                $out[] = $dto;
    
            }//end foreach

        } else {

            if (empty($errors)) {
                throw new XsgaValidationException('Error file not found', 114);
            } else {
                throw new XsgaValidationException('Error file not valid', 115);
            }//end if

        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getErrors()


}//end ErrorsBusiness class
