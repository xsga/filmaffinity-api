<?php

/**
 * Create user command validator.
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
 * CreateUserValidator class.
 */
class CreateUserValidator
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
     * Password validator.
     * 
     * @var PasswordValidator
     * 
     * @access private
     */
    private $password;

    /**
     * Constructor.
     * 
     * @param LoggerInterface $logger              LoggerInterface instance.
     * @param EmailValidator  $emailValidator      EmailValidator instance.
     * @param PasswordValidator $passwordValidator PasswordValidator instance.
     * 
     * @access public
     */
    public function __construct(
        LoggerInterface $logger, 
        EmailValidator $emailValidator, 
        PasswordValidator $passwordValidator)
    {
        $this->logger   = $logger;
        $this->email    = $emailValidator;
        $this->password = $passwordValidator;
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

    /**
     * Validate password.
     * 
     * @param string $password Password.
     * 
     * @return string
     * 
     * @throws RuntimeException Password not valid.
     * 
     * @access public
     */
    public function validatePassword(string $password): string
    {
        if ($this->password->validate($password)) {
            return $password;
        }//end if

        throw new RuntimeException('Password must have a minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character');
    }
}