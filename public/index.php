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
use xsgaphp\core\bootstrap\XsgaCoreBootstrap;
use Doctrine\ORM\Query\QueryException;
use xsgaphp\core\exceptions\XsgaBootstrapException;

// Load Composer autoloader.
$pathAutoload = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR;
require_once realpath(dirname(__FILE__)).$pathAutoload.'autoload.php';

try {

    // Bootstrap.
    XsgaCoreBootstrap::init();

    // Get Logger.
    $logger = Logger::getRootLogger();

    // Logger.
    $logger->debugInit();
    $logger->info("Request URI    : $_SERVER[REQUEST_URI]");
    $logger->info("Request method : $_SERVER[REQUEST_METHOD]");

    // Get API router.
    $apiRouter = new XsgaAPIRouter();

    // Dispatch API petition.
    $apiRouter->dispatchPetition($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

} catch (XsgaBootstrapException $e) {

    // Get Logger.
    $logger = Logger::getRootLogger();

    // Logger.
    $logger->debugInit();
    $logger->error($e->getMessage());

    // Get API router.
    $apiRouter = new XsgaAPIRouter();

    // Dispatch API bootstrap error.
    $apiRouter->dispatchBootstrapError($e->getMessage());

} catch (QueryException $e) {
    
    // Error code.
    $errorCode = 111;

    // Logger.
    $logger->error("Error code: $errorCode");
    $logger->error($e->__toString());
    
    // Dispatch error.
    $apiRouter->dispatchError($errorCode);
    
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
