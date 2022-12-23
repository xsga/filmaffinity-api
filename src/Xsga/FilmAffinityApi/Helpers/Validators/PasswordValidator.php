<?php

/**
 * Password validator.
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
 * PasswordValidator class.
 */
class PasswordValidator
{
    /**
     * Constructor.
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Validate password.
     */
    public function validate(string $password): bool
    {
        if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#", $password) !== 1) {
            $this->logger->error("Password not valid");
            return false;
        }//end if

        $this->logger->debug('Password validated successfully');

        return true;
    }
}
