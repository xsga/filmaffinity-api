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
     * Constructor.
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Validate e-mail.
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
