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
	            $GLOBALS['f3']->reroute('views/home');
            }

        }
        $view = new Template();
        echo $view->render('views/login.html');
    }


}