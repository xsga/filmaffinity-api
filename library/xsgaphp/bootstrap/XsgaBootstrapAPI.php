<?php
/**
 * Bootstrap.
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

// Load common bootstrap.
require_once 'XsgaBootstrapCommon.php';

$pathConfig  = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
$pathConfig .= 'config'.DIRECTORY_SEPARATOR;

// Load settings.
require_once realpath(dirname(__FILE__)).$pathConfig.'settingsAPI.php';

// Additional API settings.
switch (FA_LANGUAGE) {
    case 'spa':
        define('FA_BASE_URL', 'https://www.filmaffinity.com/es/');
        break;
    case 'en':
        define('FA_BASE_URL', 'https://www.filmaffinity.com/us/');
        break;
    default:
        define('FA_BASE_URL', 'https://www.filmaffinity.com/es/');
}//end switch

define('FA_SEARCH_URL', 'search.php?stext={1}');
define('FA_ADV_SEARCH_URL', 'advsearch.php?stext={1}{2}&country={3}&genre={4}&fromyear={5}&toyear={6}');
define('FA_FILM_URL', 'film{1}.html');

// Load Logger configuration.
Logger::configure(realpath(dirname(__FILE__)).$pathConfig.'log4php-api.xml');
