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
     */
    public int $userId = 0;

    /**
     * User e-mail.
     */
    public string $email = '';

    /**
     * User password.
     */
    public string $password = '';

    /**
     * User role.
     */
    public string $role = '';

    /**
     * User enabled flag.
     */
    public int $enabled = 0;

    /**
     * Create date.
     */
    public DateTime $createDate;
}
