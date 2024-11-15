<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Infrastructure\Services;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Domain\Exceptions\ConnectionException;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Domain\Exceptions\PageNotFoundException;

final class GuzzleHttpClientService implements HttpClientService
{
    public function __construct(
        private LoggerInterface $logger,
        private Client $client
    ) {
    }

    public function getPageContent(string $url): string
    {
        $statusCode = 0;

        try {
            $this->logger->debug("GET $url");

            $response = $this->client->get($url);
            $statusCode = $response->getStatusCode();

            $this->logger->debug("HTTP status code: $statusCode");
        } catch (Throwable $exception) {
            $statusCode = $exception->getCode();
            $this->logger->error("HTTP status code: $statusCode");
        }

        if ($statusCode === 404) {
            $errorMsg = "Page not found: $url";
            $this->logger->error($errorMsg);
            throw new PageNotFoundException($errorMsg, 2004);
        }

        if ($statusCode !== 200) {
            $errorMsg = "Error connecting to website: $url";
            $this->logger->error($errorMsg);
            throw new ConnectionException($errorMsg, 2000);
        }

        return (string)$response->getBody();
    }
}
