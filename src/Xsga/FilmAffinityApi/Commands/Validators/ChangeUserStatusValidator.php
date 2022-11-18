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
use Xsga\FilmAffinityApi\Business\Users\GetUser;
use Xsga\FilmAffinityApi\Helpers\Validators\EmailValidator;

/**
 * ChangeUserStatusValidator class.
 */
class ChangeUserStatusValidator
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
     * Get user service.
     *
     * @var GetUser
     *
     * @access private
     */
    private $getUser;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger         LoggerInterface instance.
     * @param EmailValidator  $emailValidator EmailValidator instance.
     * @param GetUser         $getUser        GetUser instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        EmailValidator $emailValidator,
        GetUser $getUser
    ) {
        $this->logger  = $logger;
        $this->email   = $emailValidator;
        $this->getUser = $getUser;
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
     * Validates if user exists.
     *
     * @param string $email User e-mail.
     *
     * @return boolean
     *
     * @access public
     */
    public function validateUserExists(string $email): bool
    {
        $userDto = $this->getUser->byEmail($email);

        if ($userDto->userId === 0) {
            return false;
        }//end if

        return true;
    }
}
