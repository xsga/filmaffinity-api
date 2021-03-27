<?php
/**
 * FilmAffinity-API settings.
 *
 * PHP Version 7
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

// Initialize settings array.
$settings = array();

/**
 * Environment settings.
 * 
 * dev --> development
 * pro --> production
 */
$settings['environment'] = 'pro';

/**
 * Domain settings.
 * 
 * URL path. Path in URL to access the API without domain or subdomain.
 *  
 * Examples:
 * 
 *  - http://www.domain.com            --> url_path = ''
 *  - http://subdomain.domain.com      --> url_path = ''
 *  - http://www.domain.com/api        --> url_path = '/api/'
 *  - http://www.domain.com/api/folder --> url_path = '/api/folder/'
 */
$settings['url_path'] = '/filmaffinity-api/';

/**
 * Language settings.
 *
 * Language:
 *  - spa --> https://www.filmaffinity.com/es/
 *  - en  --> https://www.filmaffinity.com/us/
 */
$settings['fa_language'] = 'spa';

/**
 * Logger settings.
 * 
 * Logger level --> wrapper for log4PHP logger level.
 *  - debug
 *  - info
 */
$settings['logger_level'] = 'debug';

// User config constants definition.
define('API_ENV', $settings['environment']);
define('URL_PATH', $settings['url_path']);
define('FA_LANGUAGE', strtolower($settings['fa_language']));
define('LOGGER_LEVEL', strtolower($settings['logger_level']));
