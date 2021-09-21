<?php
/**
 * ApiUserDto.
 *
 * This file contains the ApiUserDto class.
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
namespace xsgaphp\api\dto;

/**
 * ApiUserDto class.
 */
class ApiUserDto
{
    /**
     * Id.
     * 
     * @var integer|null
     * 
     * @access private
     */
    private $id;

    /**
     * Email.
     * 
     * @var string|null
     * 
     * @access private
     */
    private $email;

    /**
     * Password.
     * 
     * @var string|null
     *
     * @access private
     */
    private $password;

    /**
     * Name.
     * 
     * @var string|null
     *
     * @access private
     */
    private $name;

    /**
     * Surname 1.
     * 
     * @var string|null
     *
     * @access private
     */
    private $surname1;

    /**
     * Surname 2.
     * 
     * @var string|null
     *
     * @access private
     */
    private $surname2;

    /**
     * Birthdate.
     * 
     * @var \DateTime|null
     *
     * @access private
     */
    private $birthdate;

    /**
     * Email 2.
     * 
     * @var string|null
     *
     * @access private
     */
    private $email2;

    /**
     * Phone 1.
     * 
     * @var string|null
     *
     * @access private
     */
    private $phone1;

    /**
     * Phone 2
     * 
     * @var string|null
     *
     * @access private
     */
    private $phone2;

    /**
     * Create date.
     * 
     * @var \DateTime|null
     *
     * @access private
     */
    private $createDate;

    /**
     * Last login.
     * 
     * @var \DateTime|null
     *
     * @access private
     */
    private $lastLogin;

    /**
     * Enabled.
     * 
     * @var integer|null
     *
     * @access private
     */
    private $enabled;

    /**
     * Token.
     * 
     * @var string|null
     *
     * @access private
     */
    private $token;


    /**
     * Set id.
     * 
     * @param integer|null $id Id.
     *
     * @return void
     * 
     * @access public
     */
    public function setId(int $id = null) : void
    {
        $this->id = $id;

    }//end getId()


    /**
     * Get id.
     *
     * @return integer|null
     * 
     * @access public
     */
    public function getId() : int|null
    {
        return $this->id;

    }//end getId()


    /**
     * Set email.
     *
     * @param string|null $email Email.
     *
     * @return void
     */
    public function setEmail(string $email = null) : void
    {
        $this->email = $email;

    }//end setEmail()


    /**
     * Get email.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getEmail() : string|null
    {
        return $this->email;

    }//end getEmail()


    /**
     * Set password.
     *
     * @param string|null $password Password.
     *
     * @return void
     * 
     * @access public
     */
    public function setPassword(string $password = null) : void
    {
        $this->password = $password;

    }//end setPassword()


    /**
     * Get password.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getPassword() : string|null
    {
        return $this->password;

    }//end getPassword()


    /**
     * Set name.
     *
     * @param string|null $name Name.
     *
     * @return void
     * 
     * @access public
     */
    public function setName(string $name = null) : void
    {
        $this->name = $name;

    }//end setName()


    /**
     * Get name.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getName() : string|null
    {
        return $this->name;

    }//end getName()


    /**
     * Set surname1.
     *
     * @param string|null $surname1 Surname 1.
     *
     * @return void
     * 
     * @access public
     */
    public function setSurname1(string $surname1 = null) : void
    {
        $this->surname1 = $surname1;

    }//end setSurname1()


    /**
     * Get surname1.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getSurname1() : string|null
    {
        return $this->surname1;

    }//end getSurname1()


    /**
     * Set surname2.
     *
     * @param string|null $surname2 Surname 2.
     *
     * @return void
     * 
     * @access public
     */
    public function setSurname2(string $surname2 = null) : void
    {
        $this->surname2 = $surname2;

    }//end setSurname2()


    /**
     * Get surname2.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getSurname2() : string|null
    {
        return $this->surname2;

    }//end getSurname2()


    /**
     * Set birthdate.
     *
     * @param \DateTime|null $birthdate Birthdate.
     *
     * @return void
     * 
     * @access public
     */
    public function setBirthdate(\Datetime $birthdate = null) : void
    {
        $this->birthdate = $birthdate;

    }//end setBirthdate()


    /**
     * Get birthdate.
     *
     * @return \DateTime|null
     * 
     * @access public
     */
    public function getBirthdate() : \Datetime|null
    {
        return $this->birthdate;

    }//end getBirthdate()


    /**
     * Set email2.
     *
     * @param string|null $email2 Email 2.
     *
     * @return void
     * 
     * @access public
     */
    public function setEmail2(string $email2 = null) : void
    {
        $this->email2 = $email2;

    }//end setEmail2()


    /**
     * Get email2.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getEmail2() : string|null
    {
        return $this->email2;

    }//end getEmail2()


    /**
     * Set phone1.
     *
     * @param string|null $phone1 Phone 1.
     *
     * @return void
     * 
     * @access public
     */
    public function setPhone1(string $phone1 = null) : void
    {
        $this->phone1 = $phone1;

    }//end setPhone1()


    /**
     * Get phone1.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getPhone1() : string|null
    {
        return $this->phone1;

    }//end getPhone1()


    /**
     * Set phone2.
     *
     * @param string|null $phone2 Phone 2.
     *
     * @return void
     * 
     * @access public
     */
    public function setPhone2(string $phone2 = null) : void
    {
        $this->phone2 = $phone2;

    }//end setPhone2()


    /**
     * Get phone2.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getPhone2() : string|null
    {
        return $this->phone2;

    }//end getPhone2()


    /**
     * Set createDate.
     *
     * @param \DateTime|null $createDate Create date.
     *
     * @return void
     * 
     * @access public
     */
    public function setCreateDate(\Datetime $createDate = null) : void
    {
        $this->createDate = $createDate;

    }//end setCreateDate()


    /**
     * Get createDate.
     *
     * @return \DateTime|null
     * 
     * @access public
     */
    public function getCreateDate() : \Datetime|null
    {
        return $this->createDate;

    }//end getCreateDate()


    /**
     * Set lastLogin.
     *
     * @param \DateTime|null $lastLogin Last login.
     *
     * @return void
     * 
     * @access public
     */
    public function setLastLogin(\Datetime $lastLogin = null) : void
    {
        $this->lastLogin = $lastLogin;

    }//end setLastLogin()


    /**
     * Get lastLogin.
     *
     * @return \DateTime|null
     * 
     * @access public
     */
    public function getLastLogin() : \Datetime|null
    {
        return $this->lastLogin;

    }//end getLastLogin()


    /**
     * Set enabled.
     *
     * @param integer|null $enabled Enabled.
     *
     * @return void
     * 
     * @access public
     */
    public function setEnabled(int $enabled = null) : void
    {
        $this->enabled = $enabled;

    }//end setEnabled()


    /**
     * Get enabled.
     *
     * @return integer|null
     * 
     * @access public
     */
    public function getEnabled() : int|null
    {
        return $this->enabled;

    }//end getEnabled()


    /**
     * Set token.
     *
     * @param string|null $token Token.
     *
     * @return void
     * 
     * @access public
     */
    public function setToken(string $token = null) : void
    {
        $this->token = $token;

    }//end setToken()


    /**
     * Get token.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getToken() : string|null
    {
        return $this->token;

    }//end getToken()


}//end ApiUserDto class
