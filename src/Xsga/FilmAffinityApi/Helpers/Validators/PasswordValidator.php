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
     * Validate password.
     *
     * @param string $password Password to validate.
     *
     * @return boolean
     *
     * @access public
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
