<?php

/**
 * Settings.
 *
 * Load API settings.
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
use Dotenv\Dotenv;

/**
 * Load API setting.
 *
 * @return void
 *
 * @access public
 */
function loadSettings(): void
{
    // Load settings (.env).
    $dotEnv = Dotenv::createImmutable(realpath(dirname(__FILE__, 5)) . DIRECTORY_SEPARATOR . 'config');
    $dotEnv->safeLoad();

    // Settings validations.
    $dotEnv->required('URL_PATH');
    $dotEnv->required('ERROR_DETAIL')->isBoolean();
    $dotEnv->required('LANGUAGE')->allowedValues(['spa', 'en']);
    $dotEnv->required('BASE_URL');
    $dotEnv->required('SEARCH_URL');
    $dotEnv->required('ADV_SEARCH_URL');
    $dotEnv->required('FILM_URL');
}
