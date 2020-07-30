<?php
/**
 * Bootstrap.
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

// Load common bootstrap.
require_once 'XsgaBootstrapCommon.php';

$pathConfig  = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
$pathConfig .= 'config'.DIRECTORY_SEPARATOR;

// Load settings.
require_once realpath(dirname(__FILE__)).$pathConfig.'settingsAPI.php';

// Load Logger configuration.
Logger::configure(realpath(dirname(__FILE__)).$pathConfig.'log4php-api.xml');
