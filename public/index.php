<?php

/**
 * API front controller.
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
use Psr\Log\LoggerInterface;

// Autoload.
require_once realpath(dirname(__FILE__, 2)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$start = microtime(true);
$id    = uniqid();

// Load settings.
loadSettings();

// Get container.
$container = getContainer();

// Get logger.
$logger = $container->get(LoggerInterface::class);

// Get Slim application.
$app = getSlimApp($container);

// Get and add routes to Slim app.
getRoutes($app);

$logger->info('API petition ' . $id . ' : ' . $_SERVER['REQUEST_METHOD'] . ' - ' . $_SERVER['REQUEST_URI']);

// Run Slim app.
$app->run();

$end = number_format((microtime(true) - $start), 2);

$logger->info("API petition $id : executed in $end seconds");
