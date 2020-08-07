<?php
/**
 * FilmAffinity-API settings.
 *
 * PHP Version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @license MIT
 * @version 1.0.0
 */

// Initialize settings.
$settings = array();

/**
 * Environment settings.
 * 
 * dev --> development
 * pro --> production
 */
$settings['environment'] = 'dev';

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
 * Filmaffinity settings.
 * 
 * Base URL --> page country MUST MATCH with language!!
 * Search URL.
 * Advanced search URL.
 * Film URL.
 */
$settings['fa_base_url']       = 'https://www.filmaffinity.com/es/';
$settings['fa_search_url']     = 'search.php?stext={1}';
$settings['fa_adv_search_url'] = 'advsearch.php?stext={1}{2}&country={3}&genre={4}&fromyear={5}&toyear={6}';
$settings['fa_film_url']       = 'film{1}.html';

/**
 * Logger settings.
 * 
 * Logger level.
 */
$settings['logger_level'] = 'debug';


// User config constants definition.
define('API_ENV', $settings['environment']);
define('URL_PATH', $settings['url_path']);
define('FA_BASE_URL', strtolower($settings['fa_base_url']));
define('FA_SEARCH_URL', strtolower($settings['fa_search_url']));
define('FA_ADV_SEARCH_URL', strtolower($settings['fa_adv_search_url']));
define('FA_FILM_URL', strtolower($settings['fa_film_url']));
define('FA_LANGUAGE', strtolower($settings['fa_language']));
define('LOGGER_LEVEL', strtolower($settings['logger_level']));
