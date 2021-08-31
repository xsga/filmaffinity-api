<?php
/**
 * Cli-Config.
 * 
 * Config file for Doctrine Console.
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
use xsgaphp\core\bootstrap\XsgaCoreBootstrap;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Required files.
$pathAutoload = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR;
require_once realpath(dirname(__FILE__)).$pathAutoload.'autoload.php';

// Bootstrap.
XsgaCoreBootstrap::init();

// setup Doctrine-ORM.
$setupDoctrine = XsgaCoreBootstrap::setupDoctrineORM();

// Get Doctrine Entity Manager.
$entityManager = EntityManager::create($setupDoctrine['connection'], $setupDoctrine['config']);

// Get console.
return ConsoleRunner::createHelperSet($entityManager);
