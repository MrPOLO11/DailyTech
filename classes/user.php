<?php

/**
 * Class User
 *
 * The following class defines the attributes of a default user
 * such as login credentials and basic information of work background
 */
class User
{
    //fields
    private $_name;
    private $_email;
    private $_password;
    private $_organization;
    private $_position;

    /**
     * User constructor.
     * @param $_name
     * @param $_email
     * @param $_password
     * @param $_organization
     * @param $_position
     */
    public function __construct($_name, $_email, $_password, $_organization, $_position)
    {
        $this->_name = $_name;
        $this->_email = $_email;
        $this->_password = $_password;
        $this->_organization = $_organization;
        $this->_position = $_position;
    }


    /**
     * The following function retrieves organization user works for
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->_organization;
    }

    /**
     * The following function sets organization of user
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->_organization = $organization;
    }

    /**
     * The following function retrieves position user holds
     * @return mixed
     */
    public function getPosition()
    {
        return $this->_position;
    }

    /**
     * The following function sets position of user
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->_position = $position;
    }

    /**
     * The following function retrieves password of user
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * The following function sets password of user
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * The following function retrieves name of user
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * The following function sets name of user
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * The following function retrieves email of user
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * The following function sets email of user
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }


}