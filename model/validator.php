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

    public function validLogin()
    {
        $this->validEmail($_POST['email']);
        return empty($this->_errors);
    }

    public function validSignup()
    {
        $this->validName($_POST['name']);
        $this->validEmail($_POST['email']);
        $this->validPassword($_POST['email'], $_POST['password']);

        return empty($this->_errors);
    }

    public function validUpdateAccount()
    {
        $this->validName($_POST['name']);
        $this->validEmail($_POST['email']);

        return empty($this->_errors);
    }

    public function validCreatePost()
    {
        $this->validHeader($_POST['header']);
        $this->validArticleContent($_POST['post']);
        $this->validCategory($_POST['category']);

        return empty($this->_errors);
    }

    public function validUpdatePassword()
    {
        $this->validEmail($_POST['email']);
        $this->validPassword($_POST['email'], $_POST['current']);
        $this->validPasswords($_POST['new'], $_POST['confirm']);

        return empty($this->_errors);
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

    private function validHeader($header)
    {
        if(empty($header)) {
            $this->_errors['header'] = "*Please provide a header";
        } else if (strlen($header) < 10 || strlen($header) > 25) {
            $this->_errors['header'] = "*Header must be at least 10 and at most 25 characters";
        }
    }

    private function validArticleContent($article)
    {
        if(empty($article)) {
            $this->_errors['article'] = "*Please provide content";
        } else if (strlen($article) < 300) {
            $this->_errors['article'] = "*Article must consist of at least 300 characters";
        }
    }

    private function validCategory($category)
    {
        $categories = array("general", "mobile", "programming", "science");
        if(!in_array($category, $categories)) {
            $this->_errors['category'] = "*Please select one of the provided category";
        }
    }

    private function validPasswords($new, $confirm)
    {
        if($confirm !== $new) {
            $this->_errors['confirm'] = "*Confirmation password must match with new password";
        }
    }
}