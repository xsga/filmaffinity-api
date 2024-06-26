<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Infrastructure\Services;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Domain\Exceptions\ConnectionException;

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
            $response = $this->client->get($url);
            $statusCode = $response->getStatusCode();
        } catch (Throwable $exception) {
            $statusCode = $exception->getCode();
        }

        if ($statusCode !== 200) {
            $errorMsg = "Error connecting to website: $url";
            $this->logger->error($errorMsg);
            throw new ConnectionException($errorMsg, 2000);
        }

        return (string)$response->getBody();
    }
}
