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
		$posts = $GLOBALS['db']->getMainPosts();
		$GLOBALS['f3']->set('posts', $posts);
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
            } else if(!$validPass){
                global $f3;
                $f3->set("errors['email']", "*Incorrect Email");
                $f3->set("errors['password']", "*Incorrect Password");
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
            if($this->checkSignUp($_POST['email'], $_POST['password'])) {
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
        }
        $view = new Template();
        echo $view->render('views/signup.html');
    }

    public function checkSignUp($email, $password) {
        global $f3;
        if(isset($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $f3->set("errors['emailSignUp']", "*Must Provide a valid Email");
                return false;
            }
            else if(isset($password)) {
                if (strlen($password) < 8) {
                    $f3->set("errors['passwordSignUp']", "*Password must be 8 characters or more");
                    return false;
                }
            }
        }
        return true;
    }

    public function logout() {
	    session_destroy();
	    $GLOBALS['f3']->reroute('/');;
    }

    public function viewPost($header) {
		$current = $GLOBALS['db']->getPostByHeader($header);
		$GLOBALS['f3']->set('header',$current['header']);
		$GLOBALS['f3']->set('content',$current['articleText']);

		$view = new Template();
		echo $view->render('views/view.html');
	}

	public function category($category) {
		$current = $GLOBALS['db']->getCategoricalPosts($category);

		$GLOBALS['f3']->set('categoryposts',$current);


		$view = new Template();
		echo $view->render('views/categorical.html');
	}

}