<?php

/**
 * Extractor.
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
namespace Xsga\FilmAffinityApi\Business\Extractor;

/**
 * Import dependencies.
 */
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Exceptions\FilmAffinityWebsiteException;

/**
 * Class Extractor.
 */
final class Extractor
{
    /**
     * FilmAffinity base URL.
     */
    private string $baseUrl = '';

    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private Client $client,
        string $language,
        string $baseUrl
    ) {
        // Set base URL.
        switch (strtolower($language)) {
            case 'spa':
                $this->baseUrl = $baseUrl . 'es/';
                break;
            case 'en':
                $this->baseUrl = $baseUrl . 'us/';
                break;
        }//end switch
    }

    /**
     * Get page content.
     *
     * @throws FilmAffinityWebsiteException Error getting data from FilmAffinity website.
     */
    public function getData(string $url): string
    {
        $response = $this->client->get($this->baseUrl . $url);

        if ($response->getStatusCode() !== 200) {
            $errorMsg = 'Error connecting to FilmAffinity website';
            $this->logger->error($errorMsg);
            throw new FilmAffinityWebsiteException($errorMsg, 1006);
        }//end if

        return (string)$response->getBody();
    }
}
