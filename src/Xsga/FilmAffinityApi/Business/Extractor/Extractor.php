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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * FilmAffinity base URL.
     *
     * @var string
     *
     * @access private
     */
    private $baseUrl = '';

    /**
     * HTTP client.
     *
     * @var Client
     *
     * @access private
     */
    private $client;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger   LoggerInterface instance.
     * @param string          $language API language.
     * @param string          $baseUrl  filmAffinity base URL.
     * @param Client          $client   HTTP client.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, string $language, string $baseUrl, Client $client)
    {
        $this->logger = $logger;
        $this->client = $client;

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
     * @param string $url Page URL.
     *
     * @return string
     *
     * @throws FilmAffinityWebsiteException Error getting data from FilmAffinity website.
     *
     * @access public
     */
    public function getData(string $url): string
    {
        $response = $this->client->get($this->baseUrl . $url);

        if ($response->getStatusCode() !== 200) {
            $errorMsg = 'Error connecting to FilmAffinity website';
            $this->logger->error($errorMsg);
            throw new FilmAffinityWebsiteException($errorMsg, 1006);
        }//end if

        return $response->getBody();
    }
}
