<?php

/**
 * E-mail validator.
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
namespace Xsga\FilmAffinityApi\Helpers\Validators;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;

/**
 * EmailValidator class.
 */
class EmailValidator
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
     * Validate e-mail.
     *
     * @param string $email E-mail to validate.
     *
     * @return boolean
     *
     * @access public
     */
    public function validate(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->logger->error("E-mail \"$email\" not valid");
            return false;
        }//end if

        $this->logger->debug('E-mail validated successfully');

        return true;
    }
}
