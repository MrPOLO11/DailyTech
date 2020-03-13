<?php

class Controller
{
    private $_f3;

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

    public function  signup()
	{
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
			reroute('/adminPage');
		} else if(!isset($_SESSION['user'])) {
			reroute('/login');
		}

		$view = new Template();
		echo $view->render('views/settings.html');
	}

	public function admin()
		{
			if (is_a($_SESSION['user'],'StandardUser')) {
				reroute('/settings');
			} else if(!isset($_SESSION['user'])) {
				reroute('/login');
			}


	}

	public function updateaccount() {
		if(!isset($_SESSION['user'])) {
			reroute('/login');
		}

		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$user = $_SESSION['user'];
			var_dump($user);
			$serverUser = $GLOBALS['db']->getUser($user->getEmail());

			var_dump($serverUser);
			$id = $serverUser['user_ID'];
			echo "id: ".$id;
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
			$user = $_SESSION['user'];
			echo "<br>";
			echo "updated: ";
			var_dump($user);
			$GLOBALS['db']->updateUser($user,$id);

		}

		$view = new Template();
		echo $view->render('views/account.html');
	}

}