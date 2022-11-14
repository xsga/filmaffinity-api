<?php

/**
 * Enable user command validator.
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
namespace Xsga\FilmAffinityApi\Commands\Validators;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use RuntimeException;
use Xsga\FilmAffinityApi\Helpers\Validators\EmailValidator;
use Xsga\FilmAffinityApi\Helpers\Validators\PasswordValidator;

/**
 * EnableUserValidator class.
 */
class EnableUserValidator
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
     * Email validator.
     * 
     * @var EmailValidator
     * 
     * @access private
     */
    private $email;

    /**
     * Constructor.
     * 
     * @param LoggerInterface $logger         LoggerInterface instance.
     * @param EmailValidator  $emailValidator EmailValidator instance.
     * 
     * @access public
     */
    public function __construct(
        LoggerInterface $logger, 
        EmailValidator $emailValidator)
    {
        $this->logger = $logger;
        $this->email  = $emailValidator;
    }

    /**
     * Validate e-mail.
     * 
     * @param string $email E-mail.
     * 
     * @return string
     * 
     * @throws RuntimeException E-mail not valid.
     * 
     * @access public
     */
    public function validateEmail(string $email): string
    {
        if ($this->email->validate($email)) {
            return $email;
        }//end if

        throw new RuntimeException('E-mail not valid');
    }
}