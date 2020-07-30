<?php
/**
 * API main page.
 * 
 * The index web page it's the xsga-PHP API front controller. It manages all REST petitions.
 * 
 * PHP Version 7
 * 
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */

/**
 * Import namespaces.
 */
use log4php\Logger;
use xsgaphp\api\XsgaAPIRouter;
use xsgaphp\exceptions\XsgaException;

// Start or continue session.
session_start();

// Bootstrap.
$path  = DIRECTORY_SEPARATOR.'..';
$path .= DIRECTORY_SEPARATOR.'library';
$path .= DIRECTORY_SEPARATOR.'xsgaphp';
$path .= DIRECTORY_SEPARATOR.'bootstrap';
$path .= DIRECTORY_SEPARATOR;

require_once realpath(dirname(__FILE__)).$path.'XsgaBootstrapAPI.php';

// Get Logger.
$logger = Logger::getRootLogger();

// Logger.
$logger->debugInit('main');
$logger->info('Request        : '.$_SERVER['REQUEST_URI']);
$logger->info('Request method : '.$_SERVER['REQUEST_METHOD']);

// Get API router.
$apiRouter = new XsgaAPIRouter();

try {
    
    // Dispatch API petition.
    $apiRouter->dispatchPetition($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

} catch (Exception $e) {
    
    if ($e->getCode() === 0) {
        $errorCode = 500;
    } else {
        $errorCode = $e->getCode();
    }//end if
    
    // Logger.
    $logger->error('Error code: '.$errorCode);
    $logger->error($e->__toString());
    
    // Dispatch error.
    $apiRouter->dispatchError($errorCode, $e->getMessage(), $e->getFile(), $e->getLine(), $e->__toString());
        
    throw new XsgaException();
        
}//end try

// Logger.
$logger->debugEnd('main');
