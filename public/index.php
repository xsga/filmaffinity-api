<?php
/**
 * API main page.
 * 
 * The index page is the FilmAffinity-API front controller. It manages all petitions.
 * 
 * PHP Version 7
 * 
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Import namespaces.
 */
use log4php\Logger;
use xsgaphp\api\XsgaAPIRouter;

// Start or continue session.
session_start();

// Path to bootstrap files.
$pathToBootstrap  = DIRECTORY_SEPARATOR.'..';
$pathToBootstrap.= DIRECTORY_SEPARATOR.'library';
$pathToBootstrap.= DIRECTORY_SEPARATOR.'xsgaphp';
$pathToBootstrap.= DIRECTORY_SEPARATOR.'bootstrap';
$pathToBootstrap.= DIRECTORY_SEPARATOR;

// Loads API bootstrap.
require_once realpath(dirname(__FILE__)).$pathToBootstrap.'XsgaBootstrapAPI.php';

// Get Logger.
$logger = Logger::getRootLogger();

// Logger.
$logger->debugInit('main');
$logger->info('Request URI    : '.$_SERVER['REQUEST_URI']);
$logger->info('Request method : '.$_SERVER['REQUEST_METHOD']);

// Get API router.
$apiRouter = new XsgaAPIRouter();

try {
    
    // Dispatch API petition.
    $apiRouter->dispatchPetition($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

} catch (Exception $e) {
    
    // Get error code.
    if ($e->getCode() === 0) {
        $errorCode = 101;
    } else {
        $errorCode = $e->getCode();
    }//end if
    
    // Logger.
    $logger->error('Error code: '.$errorCode);
    $logger->error($e->__toString());
    
    // Dispatch error.
    $apiRouter->dispatchError($errorCode, $e->getFile(), $e->getLine(), $e->__toString());
    
}//end try

// Logger.
$logger->debugEnd('main');
