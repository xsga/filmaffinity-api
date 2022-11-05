<?php

/**
 * Console.
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
use Symfony\Component\Console\Application;
use Xsga\FilmAffinityApi\Commands\AddUserCommand;

// Autoload.
require_once realpath(dirname(__FILE__, 2)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Load settings.
loadSettings();

// Get container.
$container = getContainer();

// Get logger.
$logger = $container->get(LoggerInterface::class);

// Get console.
$console = new Application();

// Add commands.
$console->add($container->get(AddUserCommand::class));

// Run console.
$console->run();
