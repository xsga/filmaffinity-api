<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use GuzzleHttp\Client;
use Log4Php\Logger;
use Log4Php\LoggerWrapper;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    // --------------------------------------------------------------------------------------------
    // CONFIGURATION.
    // --------------------------------------------------------------------------------------------

    // FOLDERS.
    'root.folder' => getPathTo(),
    'entity.folders' => [getPathTo('src#Xsga#FilmAffinityApi#Entities')],
    'schema.folder' => getPathTo('src#Xsga#FilmAffinityApi#Resources#Schemas'),
    'entities.proxy.folder' => getPathTo('tmp#doctrine-proxies'),
    'logger.config.folder' => getPathTo('config#logger'),
    'errors.folder' => getPathTo('config#errors'),
    'cache.folder' => getPathTo('tmp#cache'),

    // ENVIRONMENT.
    'getLanguage' => $_ENV['LANGUAGE'],
    'getEnvironment' => $_ENV['ENVIRONMENT'],
    'getErrorDetail' => filter_var($_ENV['ERROR_DETAIL'], FILTER_VALIDATE_BOOLEAN),
    'getUrlPath' => $_ENV['URL_PATH'],
    'getJwtSecretKey' => $_ENV['JWT_SECRET_KEY'],
    'getJwtLifetime' => (int)$_ENV['JWT_LIFETIME'],
    //'getSecurityType' => function () {
    //    return match ($_ENV['SECURITY_TYPE']) {
    //        'basic' => SecurityTypes::BASIC,
    //        'token' => SecurityTypes::TOKEN
    //    };
    //},
    //'getDateMask' => $_ENV['DATE_MASK'],
    //'getDateTimeMask' => $_ENV['DATETIME_MASK'],
    'database.info' => [
        'driver'   => 'pdo_mysql',
        'dbname'   => $_ENV['DB_SCHEMA'],
        'user'     => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'host'     => $_ENV['DB_HOST'],
        'port'     => $_ENV['DB_PORT'],
        'charset'  => 'UTF8'
    ],

    // --------------------------------------------------------------------------------------------
    // ENTITY MANAGER.
    // --------------------------------------------------------------------------------------------
    EntityManagerInterface::class => function (ContainerInterface $container) {
        $isDevMode = match ($container->get('getEnvironment')) {
            'dev' => true,
            'pro' => false,
            default => true
        };

        $entityPaths = $container->get('entity.folders');
        $proxyPath   = $container->get('entities.proxy.folder');
        $connection  = DriverManager::getConnection($container->get('database.info'));

        $config = ORMSetup::createAttributeMetadataConfiguration($entityPaths, $isDevMode, $proxyPath, null);
        $config->setAutoGenerateProxyClasses($isDevMode);

        return new EntityManager($connection, $config);
    },

    // --------------------------------------------------------------------------------------------
    // LOGGER.
    // --------------------------------------------------------------------------------------------
    Logger::class => function (ContainerInterface $container) {
        if (!Logger::isInitialized()) {
            Logger::configure($container->get('logger.config.folder') . 'log4php.xml');
        }
        return Logger::getRootLogger();
    },
    LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
        $logger = $container->get(Logger::class);
        return new LoggerWrapper($logger);
    },
];
