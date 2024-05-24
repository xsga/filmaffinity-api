<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Extractor;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

final class Extractor
{
    private string $baseUrl = '';

    public function __construct(
        private LoggerInterface $logger,
        private Client $client,
        string $language,
        string $baseUrl
    ) {
        $this->baseUrl = match (strtolower($language)) {
            'spa' => $baseUrl . 'es/',
            'en' => $baseUrl . 'us/'
        };
    }

    public function getData(string $url): string
    {
        $response = $this->client->get($this->baseUrl . $url);

        if ($response->getStatusCode() !== 200) {
            $errorMsg = 'Error connecting to FilmAffinity website';
            $this->logger->error($errorMsg);
            throw new FilmAffinityWebsiteException($errorMsg, 1006);
        }

        return (string)$response->getBody();
    }
}
