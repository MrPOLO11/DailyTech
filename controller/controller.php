<?php
/**
 * Class Controller
 *
 * The following class acts as a controller for validation and routing for the website.
 * The following class ensures proper routing is made for each page, when a user will login
 * and setting errors that arise when validating
 */
class Controller
{
    private $_f3;
	private $_val;

	/**
     * Controller constructor.
	 * Instantiate #f3 access and validation
     * @param $_f3
     */
	public function __construct($_f3)
	{
		$this->_f3 = $_f3;
		$this->_val = new Validator();
	}

	public  function home()
	{
		$posts = $GLOBALS['db']->getMainPosts();
		$GLOBALS['f3']->set('posts', $posts);
        $view = new Template();
        echo $view->render('views/home.html');
    }

	/**
	 * The following function verifies and routes user when logging in with an account
	 */
    public  function login()
	{
		//Runs on submit
	    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    	if ($this->_val->validLogin()) {
				$suppliedPass=$_POST['password'];
				$suppliedEmail=$_POST['email'];
				$validPass=$GLOBALS['db']->verifyLogin($suppliedEmail,$suppliedPass);
				if ($validPass) {
					$userArray=$GLOBALS['db']->getUser($suppliedEmail);
					$_SESSION['user']=new User($userArray['name'],
						$suppliedEmail,
						$suppliedPass,
						$userArray['organization'],
						$userArray['position']
					);
					$GLOBALS['f3']->reroute('/');
				}
			}
        }
        $this->_f3->set('errors', $this->_val->getErrors());
        $view = new Template();
        echo $view->render('views/login.html');
    }

	/**
	 * The following function verifies and routes user for creating an account/signing up to the website
	 */
    public function  signup()
	{
	    if (isset($_SESSION['user'])) {
	        $GLOBALS['f3']->reroute('/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($this->_val->validSignup()) {
				$passHash=sha1($_POST['password']);
				$name=$_POST['name'];
				$email=$_POST['email'];
				$organization=$_POST['org'];
				$position=$_POST['position'];
				$user=new StandardUser(
					$name,
					$email,
					$passHash,
					$organization,
					$position);

				$_SESSION['user']=$user;
				$GLOBALS['db']->insertUser($user);
				$GLOBALS['f3']->reroute('/');
			}
		}
        $this->_f3->set('errors',$this->_val->getErrors());
        $view = new Template();
        echo $view->render('views/signup.html');
    }

	/**
	 * The following function will successfully log out an user by destroying the session and route back to home page
	 */
    public function logout()
	{
	    session_destroy();
	    $GLOBALS['f3']->reroute('/');;
    }

	/**
	 * The following function allows for a post to be viewed
	 * @param $header
	 *  specific post to search for with given header
	 */
    public function viewPost($header)
	{
		$current = $GLOBALS['db']->getPostByHeader($header);
		$GLOBALS['f3']->set('header',$current['header']);
		$GLOBALS['f3']->set('content',$current['articleText']);
		$view = new Template();
		echo $view->render('views/view.html');
	}

	/**
	 * The following function will select posts based on category
	 * @param $category
	 *  the posts to search for based on given category
	 */
	public function category($category)
	{
		$current = $GLOBALS['db']->getCategoricalPosts($category);

		$GLOBALS['f3']->set('categoryposts',$current);


		$view = new Template();
		echo $view->render('views/categorical.html');
	}

	/**
	 * The following function displays proper privileges based on account
	 */
	public function settings()
	{
		$user = $GLOBALS['db']->getUser($_SESSION['user']->getEmail());
		if ($user['isAdmin'] == 1) {
			$GLOBALS['f3']->reroute('/adminPage');
		} else if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}

		$view = new Template();
		echo $view->render('views/settings.html');
	}


	public function admin()
		{
			if(!isset($_SESSION['user'])) {
				$GLOBALS['f3']->reroute('/login');
			}
			$user = $GLOBALS['db']->getUser($_SESSION['user']->getEmail());
			if ($user['isAdmin']==0) {
				$GLOBALS['f3']->reroute('/settings');
			}

			$users = $GLOBALS['db']->getUsers();
			$GLOBALS['f3']->set('users', $users);

			$view = new Template();
			echo $view->render('views/admin.html');
	}

	/**
	 * The following function will verify and update account details of logged in user
	 */
	public function updateaccount()
	{
		if(!isset($_SESSION['user'])) {
		$GLOBALS['f3']->reroute('/login');
	}
		//When posted, when user has submitted form
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			//Verify information user has provided in form
			if($this->_val->validUpdateAccount()) {
				//If passed, est new information of user account
				$user = $_SESSION['user'];
				$serverUser = $GLOBALS['db']->getUser($user->getEmail());

				$id = $serverUser['user_ID'];
				if ($_POST['name'] != $_SESSION['user']->getName()) {
					$_SESSION['user']->setName($_POST['name']);
				}
				if ($_POST['email'] != $_SESSION['user']->getEmail()) {
					$_SESSION['user']->setEmail($_POST['email']);
				}
				if ($_POST['org'] != $_SESSION['user']->getOrganization()) {
					$_SESSION['user']->setOrganization($_POST['org']);
				}
				if ($_POST['position'] != $_SESSION['user']->getPosition()) {
					$_SESSION['user']->setPosition($_POST['position']);
				}
				$GLOBALS['db']->updateUser($_SESSION['user'], $id);
				$GLOBALS['f3']->set('updateSucesss', 'Update Completed Successfully');
			}
		}
		//Otherwise, display any errors and stay on account page
		$this->_f3->set('errors', $this->_val->getErrors());
		$view = new Template();
		echo $view->render('views/account.html');
	}

	public function updatepassword()
	{//If user is not logged in, route back to login
		if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}
		//When posted, user has submitted form
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			//Verify passwords and email user has provided
			if($this->_val->validUpdatePassword()) {
				//If passed, update password of user logged in
				$serverUser = $GLOBALS['db']->getUser($_SESSION['user']->getEmail());

				$id = $serverUser['user_ID'];
				$newPass = sha1(sha1($_POST['new']));
				if ($serverUser['email'] == $_POST['email']) {
					if ($serverUser['myPassword'] == sha1(sha1($_POST['current'])) &&
						$_POST['new'] == $_POST['confirm']) {
						$GLOBALS['db']->updatePassword($newPass, $id);
						$GLOBALS['f3']->set('updatePasswordSuccess',
							'Update Completed Successfully');
					} else {
						$GLOBALS['f3']->set('updatePasswordSuccess', 'Update Failed');
					}
				} else {
					$GLOBALS['f3']->set('updatePasswordSuccess', 'Update Failed');
				}
			}
		}
		//Otherwise, display any errors and stay on update password page
		$this->_f3->set('errors', $this->_val->getErrors());
		$view = new Template();
		echo $view->render('views/updatepassword.html');
	}

	public function deleteaccount() {
		//If user is not logged in, route back to login
		if(!isset($_SESSION['user'])) {
			$GLOBALS['f3']->reroute('/login');
		}
		//When posted, user has submitted form
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			//Verify passwords and email user has provided
			if ($this->_val->validDelete()) {
				//If passed, update password of user logged in
				$serverUser=$GLOBALS['db']->getUser($_SESSION['user']->getEmail());
				$id=$serverUser['user_ID'];

				$GLOBALS['db']->deleteUser($id);
				session_destroy();
				$GLOBALS['f3']->reroute('/');
			}
		}
		//Otherwise, display any errors and stay on update password page
		$this->_f3->set('errors', $this->_val->getErrors());
		$view = new Template();
		echo $view->render('views/deleteaccount.html');
	}

	public function createarticle() {
		if(!isset($_SESSION['user'])) {
		$GLOBALS['f3']->reroute('/login');
	}
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			if($this->_val->validCreatePost()) {
				$serverUser = $GLOBALS['db']->getUser($_SESSION['user']->getEmail());


				$header = $_POST['header'];
				$article = $_POST['article'];
				$category = $_POST['category'];
				$id = $serverUser['user_ID'];


				$post = new Post($category, $header, $article, $id);

				$GLOBALS['db']->insertPost($post);
				$GLOBALS['f3']->reroute('/');
			}
		}
		//Otherwise, display any errors and stay on create article page
		$this->_f3->set('errors', $this->_val->getErrors());
		$view = new Template();
		echo $view->render('views/createarticle.html');
	}

}