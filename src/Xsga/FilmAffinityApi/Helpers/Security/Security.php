<?php

/**
 * Security.
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
namespace Xsga\FilmAffinityApi\Helpers\Security;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;

/**
 * Class Security.
 */
final class Security
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
     * Constructor.
     *
     * @param LoggerInterface $logger LoggerInterface instance.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Basic security.
     *
     * @param string $authorization Authorization header.
     *
     * @return void
     *
     * @access public
     */
    public function basic(string $authorization): void
    {
        $this->logger->debug('BASIC security');
    }

    /**
     * token security.
     *
     * @param string $authorization Authorization header.
     *
     * @return void
     *
     * @access public
     */
    public function token(string $authorization): void
    {
        $this->logger->debug('TOKEN security');
    }
}
