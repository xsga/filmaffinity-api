<?php
/**
 * Bootstrap.
 * 
 * PHP Version 8
 * 
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace xsgaphp\bootstrap;

/**
 * Import dependencies.
 */
use log4php\Logger;
use Dotenv\Dotenv;
use xsgaphp\core\utils\XsgaPath;
use xsgaphp\core\cache\XsgaCache;
use xsgaphp\core\doctrine\XsgaSQLLogger;
use Doctrine\ORM\Tools\Setup;

/**
 * Bootstrap class.
 */
class XsgaBootstrap
{

    /**
     * Load framework environment.
     * 
     * @return void
     * 
     * @access public
     */
    public static function loadEnv() : void
    {
        // Set internal variables.
        $_ENV['APP_ROOT'] = realpath(dirname(__FILE__, 5)).DIRECTORY_SEPARATOR;
        $_ENV['APP_TYPE'] = 'api';

        // Path to config folder.
        $pathConfig = XsgaPath::getPathTo('config');

        // Load Logger configuration.
        Logger::configure($pathConfig.'log4php.xml');

        // Get logger.
        $logger = Logger::getRootLogger();

        // Logger.
        $logger->debug('Loading common environment settings');

        // Load common settings (.env).
        $dotenv = Dotenv::createImmutable($pathConfig);
        $dotenv->safeLoad();

        // Logger.
        $logger->debug('Validating common environment settings');

        // Validates common settings.
        $dotenv->required('ENVIRONMENT')->allowedValues(['dev', 'pro']);
        $dotenv->required('LOGGER_SQL')->isBoolean();
        $dotenv->required('SECURITY_TYPE')->allowedValues(['0', '1']);
        $dotenv->required('URL_PATH');
        $dotenv->required('VENDOR')->notEmpty();
        $dotenv->required('LANGUAGE')->allowedValues(['spa', 'en']);

        // Logger.
        $logger->debug("Loading \"$_ENV[ENVIRONMENT]\" environment settings");

        // Load environment settings (.environment.env).
        $dotenv = Dotenv::createImmutable($pathConfig, ".$_ENV[ENVIRONMENT].env");
        $dotenv->safeLoad();

        // Logger.
        $logger->debug("Validating \"$_ENV[ENVIRONMENT]\" environment settings");

        // Validates environment settings.
        $dotenv->required(['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_SCHEMA'])->notEmpty();
        
        // Logger.
        $logger->debug('Configuring cache component');

        // Configure cache.
        $cacheConfig               = array();
        $cacheConfig['cache_path'] = XsgaPath::getPathTo(array('tmp', 'cache'));
        $cacheConfig['expires']    = 120;

        XsgaCache::configure($cacheConfig);

        // Logger.
        $logger->debug('Environment loaded successfully');

    }//end loadEnv()


    /**
     * Setup Doctrine-ORM.
     * 
     * @return array
     * 
     * @access public
     */
    public static function setupDoctrineORM() : array
    {
        // Mode.
        switch ($_ENV['ENVIRONMENT']) {
            case 'dev':
                $isDevMode = true;
                break;
            case 'pro':
                $isDevMode = false;
                break;
            default:
                $isDevMode = true;
        }//end switch
        
        // Path to entities.
        $paths = array(
            XsgaPath::getPathTo(array('src', 'entity'))
        );

        // Create config.
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
        $config->setSQLLogger(new XsgaSQLLogger());
        $config->setProxyDir(XsgaPath::getPathTo(array('tmp', 'doctrine-proxies')));
        $config->setAutoGenerateProxyClasses($isDevMode);

        // MySQL data.
        $conn = array(
            'driver'   => 'pdo_mysql',
            'dbname'   => $_ENV['DB_SCHEMA'],
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host'     => $_ENV['DB_HOST'],
            'charset'  => 'UTF8'
        );

        $setup               = array();
        $setup['config']     = $config;
        $setup['connection'] = $conn;

        return $setup;

    }//end setupDoctrineORM()


}//end XsgaBootstrap class
