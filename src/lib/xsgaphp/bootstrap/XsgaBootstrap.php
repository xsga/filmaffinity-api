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
 * Namespace.
 */
namespace xsgaphp\bootstrap;

/**
 * Import dependencies.
 */
use log4php\Logger;
use Dotenv\Dotenv;
use xsgaphp\utils\XsgaUtil;
use xsgaphp\cache\XsgaCache;
use xsgaphp\doctrine\XsgaSQLLogger;
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
    public static function loadEnv()
    {
        // Path to config folder.
        $pathConfig = realpath(dirname(__FILE__)).XsgaUtil::getPathTo(4, array('config'));

        // Load Logger configuration.
        Logger::configure($pathConfig.'log4php.xml');

        // Load common settings (.env).
        $dotenv = Dotenv::createImmutable($pathConfig);
        $dotenv->safeLoad();

        // Validates common settings.
        $dotenv->required('ENVIRONMENT')->allowedValues(['dev', 'pro']);
        $dotenv->required('LOGGER_SQL')->isBoolean();
        $dotenv->required('URL_PATH');
        $dotenv->required('VENDOR')->notEmpty();
        $dotenv->required('LANGUAGE')->allowedValues(['spa', 'en']);

        // Load environment settings (.environment.env).
        $dotenv = Dotenv::createImmutable($pathConfig, '.'.$_ENV['ENVIRONMENT'].'.env');
        $dotenv->safeLoad();

        // Validates environment settings.
        $dotenv->required(['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_SCHEMA'])->notEmpty();
        
        // Configure cache.
        $cacheConfig               = array();
        $cacheConfig['cache_path'] = realpath(dirname(__FILE__)).XsgaUtil::getPathTo(4, array('tmp', 'cache'));
        $cacheConfig['expires']    = 120;

        XsgaCache::configure($cacheConfig);

    }//end loadEnv()


    /**
     * Setup Doctrine-ORM.
     * 
     * @return array
     * 
     * @access public
     */
    public static function setupDoctrineORM()
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
            realpath(dirname(__FILE__)).XsgaUtil::getPathTo(3, array('entity'))
        );

        // Create config.
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
        $config->setSQLLogger(new XsgaSQLLogger());
        $config->setProxyDir(realpath(dirname(__FILE__)).XsgaUtil::getPathTo(4, array('tmp', 'doctrine-proxies')));

        if ($isDevMode) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }//end if

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

    }//en setupDoctrineORM()


}//end XsgaBootstrap class
