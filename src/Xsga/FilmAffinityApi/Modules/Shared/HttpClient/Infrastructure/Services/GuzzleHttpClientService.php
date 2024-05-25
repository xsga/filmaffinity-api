<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Infrastructure\Services;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Domain\Exceptions\FilmAffinityWebsiteException;

final class GuzzleHttpClientService implements HttpClientService
{
    private string $baseUrl = '';

    public function __construct(
        private LoggerInterface $logger,
        private Client $client,
        string $language,
        string $baseUrl
    ) {
        // TODO: in container?.
        $this->baseUrl = match (strtolower($language)) {
            'spa' => $baseUrl . 'es/',
            'en' => $baseUrl . 'us/'
        };
    }

    public function getPageContent(string $url): string
    {
        try {
            $response = $this->client->get($this->baseUrl . $url);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
            $errorMsg = 'Error connecting to FilmAffinity website';
            throw new FilmAffinityWebsiteException($errorMsg);
        }

        if ($response->getStatusCode() !== 200) {
            $errorMsg = 'Error connecting to FilmAffinity website';
            $this->logger->error($errorMsg);
            //TODO: error code.
            throw new FilmAffinityWebsiteException($errorMsg);
        }

        return (string)$response->getBody();
    }
}
