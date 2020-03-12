<?php

class Controller {
    private $_f3;

    /**
     * Controller constructor.
     * @param $_f3
     */
	public function __construct($_f3) {
		$this->_f3 = $_f3;
	}

	public  function home() {
        $view = new Template();
        echo $view->render('views/home.html');
    }
    public  function login() {
	    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $suppliedPass = $_POST['password'];
            $suppliedEmail = $_POST['email'];
	        $validPass = $GLOBALS['db']->verifyLogin($suppliedEmail, $suppliedPass);
	        if ($validPass) {
                $userArray = $GLOBALS['db']-> getUser($suppliedEmail);
	            $_SESSION['user'] = new User($userArray['name'],
                    $suppliedEmail,
                    $suppliedPass,
                    $userArray['organization'],
                    $userArray['position']
                );
	            $GLOBALS['f3']->reroute('/');
            }
        }
        $view = new Template();
        echo $view->render('views/login.html');
    }

    public function  signup() {
	    if (isset($_SESSION['user'])) {
	        $GLOBALS['f3']->reroute('/');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $passHash = sha1($_POST['password']);
            $name = $_POST['name'];
            $email = $_POST['email'];
            $organization = $_POST['org'];
            $position = $_POST['position'];
            $user = new StandardUser(
                $name,
                $email,
                $passHash,
                $organization,
                $position);

                $_SESSION['user'] = $user;
                $GLOBALS['db']->insertUser($user);
                $GLOBALS['f3']->reroute('/');
        }
        $view = new Template();
        echo $view->render('views/signup.html');
    }

    public function logout() {
	    session_destroy();
	    $GLOBALS['f3']->reroute('/');;
    }

}