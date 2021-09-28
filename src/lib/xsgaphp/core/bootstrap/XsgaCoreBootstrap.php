<?php
/**
 * XsgaCoreBootstrap.
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
namespace xsgaphp\core\bootstrap;

/**
 * Import dependencies.
 */
use log4php\Logger;
use Dotenv\Dotenv;
use xsgaphp\core\utils\XsgaPath;
use xsgaphp\core\cache\XsgaCache;
use xsgaphp\core\doctrine\XsgaSQLLogger;
use Doctrine\ORM\Tools\Setup;
use xsgaphp\core\exceptions\XsgaBootstrapException;
use Dotenv\Exception\ValidationException;

/**
 * XsgaCoreBootstrap class.
 */
class XsgaCoreBootstrap
{


    /**
     * Load framework environment.
     * 
     * @return void
     * 
     * @access public
     */
    public static function init() : void
    {
        // Set internal variables.
        $_ENV['APP_ROOT'] = realpath(dirname(__FILE__, 6)).DIRECTORY_SEPARATOR;

        // Path to config folder.
        $pathConfig = XsgaPath::getPathTo('config');

        // Load Logger configuration.
        Logger::configure($pathConfig.'log4php.xml');

        // Load common settings (.env).
        $dotenv = Dotenv::createMutable($pathConfig);
        $dotenv->safeLoad();

        // Validates common settings.
        try {
            $dotenv->required('ENVIRONMENT')->allowedValues(['dev', 'pro']);
            $dotenv->required('LOGGER_SQL')->isBoolean();
            $dotenv->required('LANGUAGE')->allowedValues(['spa', 'en']);
        } catch (ValidationException $e) {
            throw new XsgaBootstrapException($e->getMessage());
        }//end try

        // Load environment settings (.environment.env).
        $dotenv = Dotenv::createMutable($pathConfig, ".$_ENV[ENVIRONMENT].env");
        $dotenv->safeLoad();

        // Validates environment settings.
        try {
            $dotenv->required(['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_SCHEMA'])->notEmpty();
        } catch (ValidationException $e) {
            throw new XsgaBootstrapException($e->getMessage());
        }//end try

        // Validates custom settings.
        try {

            // Set application bootstrap class.
            $apiBootstrap   = 'xsgaphp\\api\\bootstrap\\XsgaApiBootstrap';
            $batchBootstrap = 'xsgaphp\\batch\\bootstrap\\XsgaApiBootstrap';

            // Validates custom application properties.
            if (class_exists($apiBootstrap)) {
                $apiBootstrap::valProps($dotenv);
            } else if (class_exists($batchBootstrap)) {
                $batchBootstrap::valProps($dotenv);
            }//end if

        } catch (ValidationException $e) {
            throw new XsgaBootstrapException($e->getMessage());
        }//end try

        // Configure cache.
        $cacheConfig               = array();
        $cacheConfig['cache_path'] = XsgaPath::getPathTo(array('tmp', 'cache'));
        $cacheConfig['expires']    = 120;

        XsgaCache::configure($cacheConfig);

    }//end init()


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
        $paths = array(XsgaPath::getPathTo(array('src', 'common', 'persistence', 'entity')));

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


}//end XsgaCoreBootstrap class
