<?php

/**
 * UserDto.
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
namespace Xsga\FilmAffinityApi\Dto;

/**
 * Import dependencies.
 */
use DateTime;

/**
 * UserDto.
 */
class UserDto
{
    /**
     * User ID.
     *
     * @var integer
     *
     * @access public
     */
    public $userId = 0;

    /**
     * User e-mail.
     *
     * @var string
     *
     * @access public
     */
    public $email = '';

    /**
     * User password.
     *
     * @var string
     *
     * @access public
     */
    public $password = '';

    /**
     * User role.
     *
     * @var string
     *
     * @access public
     */
    public $role = '';

    /**
     * User enabled flag.
     *
     * @var integer
     *
     * @access public
     */
    public $enabled = 0;

    /**
     * Create date.
     *
     * @var DateTime
     *
     * @access public
     */
    public $createDate;
}
