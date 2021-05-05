<?php
/**
 * Xsga-PHP-API main page.
 * 
 * The index page is the API front controller. It manages all petitions to the API.
 * 
 * PHP Version 8
 * 
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Import dependencies.
 */
use log4php\Logger;
use xsgaphp\api\router\XsgaAPIRouter;
use xsgaphp\bootstrap\XsgaBootstrap;

// Load Composer autoloader.
$pathAutoload = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR;
require_once realpath(dirname(__FILE__)).$pathAutoload.'autoload.php';

// Bootstrap.
XsgaBootstrap::loadEnv();

// Get Logger.
$logger = Logger::getRootLogger();

// Logger.
$logger->debugInit();
$logger->info("Request URI    : $_SERVER[REQUEST_URI]");
$logger->info("Request method : $_SERVER[REQUEST_METHOD]");

try {
    
    // Get API router.
    $apiRouter = new XsgaAPIRouter();

    // Dispatch API petition.
    $apiRouter->dispatchPetition($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

} catch (Throwable $e) {
    
    // Get error code.
    if ($e->getCode() === 0) {
        $errorCode = 101;
    } else {
        $errorCode = $e->getCode();
    }//end if
    
    // Logger.
    $logger->error("Error code: $errorCode");
    $logger->error($e->__toString());
    
    // Dispatch error.
    $apiRouter->dispatchError($errorCode, $e->getFile(), $e->getLine(), $e->__toString());
    
}//end try

// Logger.
$logger->debugEnd();
