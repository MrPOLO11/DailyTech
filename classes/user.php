<?php


class User
{
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
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->_organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->_organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->_position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->_position = $position;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }


}