<?php
/**
 * XsgaPassword.
 *
 * This file contains the XsgaPassword class.
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
namespace xsgaphp\core\utils;

/**
 * XsgaPassword class.
 */
class XsgaPassword
{

    /**
     * Hash method.
     * 
     * @var int
     * 
     * @access public
     */
    const HASH = PASSWORD_DEFAULT;

    /**
     * Password cost.
     * 
     * @var int
     * 
     * @access public
     */
    const COST = 10;

    
    /**
     * Get password hash.
     * 
     * @param string $password Password to encrypt.
     * 
     * @return string
     * 
     * @access public
     */
    public static function getHash(string $password) : string
    {
        return \password_hash($password, self::HASH, ['cost' => self::COST]);
        
    }//end getHash()


    /**
     * Verify password.
     * 
     * @param string $password Password.
     * @param string $hash     Password hash.
     * 
     * @return boolean
     * 
     * @access public
     */
    public static function verify(string $password, string $hash) : bool
    {
        return \password_verify($password, $hash);

    }//end verify()


    /**
     * Validates if password needs rehash.
     * 
     * @param string $hash Hash to validates.
     * 
     * @return boolean
     * 
     * @access public
     */
    public static function needsRehash(string $hash) : bool
    {
        return \password_needs_rehash($hash, self::HASH, ['cost' => self::COST]);

    }//end needsRehash()
    
    
}//end XsgaPassword class
