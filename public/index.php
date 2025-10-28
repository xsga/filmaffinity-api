<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;

require_once realpath(dirname(__FILE__, 2)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$startTime     = microtime(true);
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'METHOD_NOT_FOUND';
$requestUri    = $_SERVER['REQUEST_URI'] ?? 'URI_NOT_FOUND';

bootstrap();

$container = getDIContainer();
$logger    = $container->get(LoggerInterface::class);

$logger->info("$requestMethod => $requestUri (start petition)");

$slimApp = getSlimApp($container);
$slimApp->run();

$execTimeInSeconds = number_format((microtime(true) - $startTime), 2);

$logger->info("$requestMethod => $requestUri (executed in $execTimeInSeconds seconds)");
