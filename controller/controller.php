<?php

class Controller
{
    private $_f3;
    private $_val;

    /**
     * Controller constructor.
     * @param $_f3
     */
	public function __construct($_f3)
	{
		$this->_f3 = $_f3;
	}

	public  function home()
	{
		$posts = $GLOBALS['db']->getMainPosts();
		$GLOBALS['f3']->set('posts', $posts);
        $view = new Template();
        echo $view->render('views/home.html');
    }
    public  function login()
	{
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

    public function signup()
	{
	    if (isset($_SESSION['user'])) {
	        $GLOBALS['f3']->reroute('/');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_val = new Validator();
            if($this->_val->validSignup()) {
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
            } else {
                $this->_f3->set('errors', $this->_val->getErrors());
            }
        }
        $view = new Template();
        echo $view->render('views/signup.html');
    }

    public function logout()
	{
	    session_destroy();
	    $GLOBALS['f3']->reroute('/');;
    }

    public function viewPost($header)
	{
		$current = $GLOBALS['db']->getPostByHeader($header);
		$GLOBALS['f3']->set('header',$current['header']);
		$GLOBALS['f3']->set('content',$current['articleText']);

		$view = new Template();
		echo $view->render('views/view.html');
	}

	public function category($category)
	{
		$current = $GLOBALS['db']->getCategoricalPosts($category);

		$GLOBALS['f3']->set('categoryposts',$current);


		$view = new Template();
		echo $view->render('views/categorical.html');
	}

	public function settings()
	{
		if (is_a($_SESSION['user'],'AdminUser')) {
			$GLOBALS['f3']->reroute('/adminPage');
		} else if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}

		$view = new Template();
		echo $view->render('views/settings.html');
	}

	public function admin()
		{
			if (is_a($_SESSION['user'],'StandardUser')) {
				$GLOBALS['f3']->reroute('/settings');
			} else if(!isset($_SESSION['user'])) {
				$GLOBALS['f3']->reroute('/login');
			}


	}

	public function updateaccount()
	{
		if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}

		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$user = $_SESSION['user'];
			$serverUser = $GLOBALS['db']->getUser($user->getEmail());

			$id = $serverUser['user_ID'];

			if ($_POST['name']!=$_SESSION['user']->getName()) {
				$_SESSION['user']->setName($_POST['name']);
			}
			if ($_POST['email']!=$_SESSION['user']->getEmail()) {
				$_SESSION['user']->setEmail($_POST['email']);
			}
			if ($_POST['org']!=$_SESSION['user']->getOrganization()) {
				$_SESSION['user']->setOrganization($_POST['org']);
			}
			if ($_POST['position']!=$_SESSION['user']->getPosition()) {
				$_SESSION['user']->setPosition($_POST['position']);
			}
			$GLOBALS['db']->updateUser($_SESSION['user'],$id);
			$GLOBALS['f3']->set('updateSucesss', 'Update Completed Successfully');

		}

		$view = new Template();
		echo $view->render('views/account.html');
	}

	public function updatepassword()
	{
		if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}

		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$serverUser = $GLOBALS['db']->getUser($_SESSION['user']->getEmail());

			$id = $serverUser['user_ID'];
			$newPass = sha1(sha1($_POST['new']));

			if ($serverUser['email'] == $_POST['email']) {

				if ($serverUser['myPassword']==sha1(sha1($_POST['current'])) &&
					$_POST['new']== $_POST['confirm']) {
					$GLOBALS['db']->updatePassword($newPass,$id);
					$GLOBALS['f3']->set('updatePasswordSuccess',
						'Update Completed Successfully');
				}
				else {
					$GLOBALS['f3']->set('updatePasswordSuccess','Update Failed');
				}

			} else {
				$GLOBALS['f3']->set('updatePasswordSuccess','Update Failed');
			}
		}

		$view = new Template();
		echo $view->render('views/updatepassword.html');
	}

	public function deleteaccount() {
		if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}

		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$serverUser = $GLOBALS['db']->getUser($_SESSION['user']->getEmail());

			$id = $serverUser['user_ID'];
			$suppliedPassword = sha1(sha1($_POST['password']));

			//CALL VALIDATION FUNCTION HERE

			$GLOBALS['db']->deleteUser($id);
			session_destroy();
			$GLOBALS['f3']->reroute('/');
		}
		$view = new Template();
		echo $view->render('views/deleteaccount.html');
	}

	public function createarticle() {
		if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}

		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$serverUser=$GLOBALS['db']->getUser($_SESSION['user']->getEmail());
			//VALIDATE FIELDS FUNCTION

			$header = $_POST['header'];
			$article = $_POST['article'];
			$category = $_POST['category'];
			$id=$serverUser['user_ID'];



			$post = new Post($category, $header, $article,$id);
			var_dump($post);
			$GLOBALS['db']->insertPost($post);
//			$GLOBALS['f3']->reroute('/');
		}

		$view = new Template();
		echo $view->render('views/createarticle.html');
	}

}