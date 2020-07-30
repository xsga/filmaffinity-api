<?php
/**
 * File comment.
 *
 * PHP Version 5
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */

$settings = array();

// URL path.
$settings['url_path'] = '/filmaffinity-api/';

// Security config.
$settings['security'] = 'true';

// Logger config.
$settings['logger_level'] = 'debug';
$settings['logger_sql']   = 'true';

// User config constants definition.
define('URL_PATH', $settings['url_path']);
define('SECURITY', strtolower($settings['security']));
define('LOGGER_LEVEL', strtolower($settings['logger_level']));
define('LOGGER_SQL', strtolower($settings['logger_sql']));
