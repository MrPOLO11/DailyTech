<?php

/**
 * Class Validator
 *
 * Provides necessary checks and server validation for each input field on the entire website
 * Sets errors if any values do not validate!
 *
 */
class Validator
{
    /**
     * @var array
     * an array of errors that need to be set if values do not pass validation
     */
    private $_errors;

    /**
     * Validator constructor.
     * Instantiates error array when a new Validator object is made
     */
    public function __construct()
    {
        $this->_errors = array();
    }

    /**
     * @return array
     *  any errors that need to be set when values don't pass validation
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return bool
     * True if no elements reside in the errors array, where email is valid
     * False if an element resides in the error array, where email provided is not valid
     */
    public function validLogin()
    {
        $this->validEmail($_POST['email']);
        return empty($this->_errors);
    }

    /**
     * @return bool
     * True if no elements reside in the errors array, where name is valid, email is valid and password is valid
     * False if an element resides in the error array, where name is not valid, or email provided is not valid or
     * password is not valid
     */
    public function validSignup()
    {
        $this->validName($_POST['name']);
        $this->validEmail($_POST['email']);
        $this->validPassword($_POST['email'], $_POST['password']);

        return empty($this->_errors);
    }

    /**
     * @return bool
     * True if no elements reside in the errors array, where name is valid and email is valid
     * False if an element resides in the error array, where name is not valid or email provided is not valid
     */
    public function validUpdateAccount()
    {
        $this->validName($_POST['name']);
        $this->validEmail($_POST['email']);

        return empty($this->_errors);
    }

    /**
     * @return bool
     * True if no elements reside in the errors array, where header is valid, article content is valid
     * and category selected is valid
     * False if an element resides in the error array, where header is not valid, or article content provided is not
     * valid or category selected is not valid
     */
    public function validCreatePost()
    {
        $this->validHeader($_POST['header']);
        $this->validArticleContent($_POST['post']);
        $this->validCategory($_POST['category']);

        return empty($this->_errors);
    }

    /**
     * @return bool
     * True if no elements reside in the errors array, where email is valid, all passwords are valid and the new and
     * confirmation passwords are matching
     * False if an element resides in the error array, where email is not valid, or all passwords provided are not
     * valid or new and confirmation passwords are not valid
     */
    public function validUpdatePassword()
    {
        $this->validEmail($_POST['email']);
        $this->validPassword($_POST['email'], $_POST['current']);
        $this->validPassword($_POST['email'], $_POST['new']);
        $this->validPassword($_POST['email'], $_POST['confirm']);
        $this->validPasswords($_POST['new'], $_POST['confirm']);

        return empty($this->_errors);
    }

    /**
     * The following function validates information user has provided is valid
     * before deleting account
     * @return bool
     * true if errors array is empty, where email provided is matched with account,
     * password matches and the phrase entered is the phrase required
     */
    public function validDelete()
    {
        $this->validEmails($_POST['email'], $GLOBALS['db']->getEmail());
        $this->validPasswords($_POST['current'], $_POST['confirmpass']);
        $this->checkPhrase($_POST['deleteconfirm']);

        return empty($this->_errors);
    }

    /**
     * The following function determines if the email provided matches with the
     * email linked to the account the user is logged in with for the website
     * @param $email
     *  provided email that should match with database email stored
     * @param $storedEmail
     *  email stored in database that linked to said account
     */
    private function validEmails($email, $storedEmail)
    {
        if(empty($email)) {
            $this->_errors['email'] = "*Please provide your email";
        } else if($email !== $storedEmail) {
            $this->_errors['email'] = "*Email does not match with account";
        }
    }

    /**
     * Function ensures the name field is provided with a value and the value is a string
     * @param $name
     *  provided name from input from the pages that require a name
     */
    private function validName($name)
    {
        if(empty($name)) {
            $this->_errors['name'] = "*Please provide a name";
        } else if(!ctype_alpha(preg_replace('/\s+/', "", $name))) {
            $this->_errors['name'] = "*Name must consist of letters";
        }
    }

    /**
     * Function ensures the email field is provided with a value and the email is valid
     * @param $email
     *  provided email from input from the pages that require a email
     */
    private function validEmail($email)
    {
        if(empty($email)) {
            $this->_errors['email'] = "*Please provide an email";
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_errors['email'] = "*Must provide valid email";
        }
    }

    /**
     * Function ensures that password is provided when email is provided and password is at least 8 characters long
     * @param $email
     * provided email from input from the pages that require a email
     * @param $password
     *  provided password from input from pages that require a password
     */
    private function validPassword($email, $password)
    {
        if(!empty($email) AND empty($password)) {
            $this->_errors['password'] = "*Please provide a password";
        } else if(strlen($password) < 8) {
            $this->_errors['password'] = "*Password must be at least 8 characters";
        }
    }

    /**
     * Function ensures that the header when creating a article is not empty and the header is between 10 to 25
     * characters
     * @param $header
     *  provided header from input from create the article page
     */
    private function validHeader($header)
    {
        if(empty($header)) {
            $this->_errors['header'] = "*Please provide a header";
        } else if (strlen($header) < 10 || strlen($header) > 25) {
            $this->_errors['header'] = "*Header must be at least 10 and at most 25 characters";
        }
    }

    /**
     * Function ensures that article content is not empty, there is an article to read, and the provided text
     * must be at least 300 characters long
     * @param $article
     *  provided article content/text from create the article page
     */
    private function validArticleContent($article)
    {
        if(empty($article)) {
            $this->_errors['article'] = "*Please provide content";
        } else if (strlen($article) < 300) {
            $this->_errors['article'] = "*Article must consist of at least 300 characters";
        }
    }

    /**
     * Function ensures category selected from user is a category provided from the website. Prevents injection
     * of unwanted values
     * @param $category
     *  category that is selected from drop down list at create the article page
     */
    private function validCategory($category)
    {
        $categories = array("general", "mobile", "programming", "science");
        if(!in_array($category, $categories)) {
            $this->_errors['category'] = "*Please select one of the provided category";
        }
    }

    /**
     * Function ensures the passwords provided are not empty and the confirmation password matches with the new
     * password provided
     * @param $new
     *  new password that needs to be updated from account
     * @param $confirm
     *  password that will confirm the new password, this password will match with the new password
     */
    private function validPasswords($new, $confirm)
    {
        if(empty($new)) {
            $this->_errors['new'] = "*Please provide a new password";
        }
        if(empty($confirm)) {
            $this->_errors['confirm'] = "*Please confirm new password";
        }
        else if($confirm !== $new) {
            $this->_errors['confirm'] = "*Confirmation password must match with new password";
        }
    }

    /**
     * The following function validates the phrase entered is matches with the one required
     * that being 'Delete My Profile'
     * @param $deleteConfirm
     *  phrase user provides the needs to match with required phrase
     */
    private function checkPhrase($deleteConfirm)
    {
        if(empty($deleteConfirm)) {
            $this->_errors['deleteConfirm'] = "*Please enter phrase to confirm";
        }
        if(strtolower($deleteConfirm) !== strtolower("Delete My Profile")) {
            $this->_errors['deleteConfirm'] = "*You must type the phrase to confirm deletion";
        }
    }
}