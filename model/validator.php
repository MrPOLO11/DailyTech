<?php
class Validator
{
    private $_errors;

    public function __construct()
    {
        $this->_errors = array();
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function validSignup()
    {
        $this->validName($_POST['name']);
        $this->validEmail($_POST['email']);
        $this->validPassword($_POST['email'], $_POST['password']);
    }

    private function validName($name)
    {
        if(empty($name)) {
            $this->_errors['name'] = "*Please provide a name";
        } else if(!ctype_alpha(preg_replace('/\s+/', "", $name))) {
            $this->_errors['name'] = "*Name must consist of letters";
        }
    }

    private function validEmail($email)
    {
        if(empty($email)) {
            $this->_errors['email'] = "*Please provide an email";
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_errors['email'] = "*Must provide valid email";
        }
    }

    private function validPassword($email, $password)
    {
        if(!empty($email) AND empty($password)) {
            $this->_errors['password'] = "*Please provide a password";
        } else if(strlen($password) < 8) {
            $this->_errors['password'] = "*Password must be at least 8 characters";
        }
    }
}