<?php

/**
 * SecurityInterface.
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
 * Interface SecurityInterface..
 */
interface SecurityInterface.
{
    /**
     * Basic security.
     *
     * @param string $authorization Authorization header.
     *
     * @return string
     *
     * @access public
     */
    public function basic(string $authorization): string;

    /**
     * Token security.
     *
     * @param string $authorization Authorization header.
     *
     * @return string
     *
     * @access public
     */
    public function token(string $authorization): string;
}
