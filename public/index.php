<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;

require_once realpath(dirname(__FILE__, 2)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$start     = microtime(true);
$requestId = uniqid();

bootstrap();

$container = getDIContainer();
$logger    = $container->get(LoggerInterface::class);
$slimApp   = getSlimApp($container, filter_var($_ENV['ERROR_DETAIL'], FILTER_VALIDATE_BOOLEAN), $_ENV['URL_PATH']);

getRoutes($slimApp);

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'METHOD_NOT_FOUND';
$requestUri    = $_SERVER['REQUEST_URI'] ?? 'URI_NOT_FOUND';

$logger->info("API petition $requestId : $requestMethod - $requestUri");

$slimApp->run();

$execTimeInSeconds = number_format((microtime(true) - $startTime), 2);

$logger->info("API petition $requestId : executed in $execTimeInSeconds seconds");
