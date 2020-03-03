<?php


class User
{
    private $_name;
    private $_email;
    private $_password;
    private $_organization;
    private $_position;

    /**
     * user constructor.
     * @param $_name
     * @param $_email
     * @param $_password
     */
    public function __construct($_name, $_email, $_password)
    {
        $this->_name = $_name;
        $this->_email = $_email;
        $this->_password = $_password;
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