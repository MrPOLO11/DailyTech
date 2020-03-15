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
        //$this->validEmail();
        //$this->validPassword();
    }

    private function validName($name)
    {
        if(empty($name)) {
            $this->_errors['name'] = "*Please provide a name";
        } else if(!ctype_alpha($name)) {
            $this->_errors['name'] = "*Name must consist of letters";
        }
    }
}