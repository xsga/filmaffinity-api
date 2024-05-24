<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Infrastructure\Services;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
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
        $response = $this->client->get($this->baseUrl . $url);

        if ($response->getStatusCode() !== 200) {
            $errorMsg = 'Error connecting to FilmAffinity website';
            $this->logger->error($errorMsg);
            //TODO: error code.
            throw new FilmAffinityWebsiteException($errorMsg, 1006);
        }

        return (string)$response->getBody();
    }
}
