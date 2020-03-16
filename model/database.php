<?php
require_once ("config-DailyTech.php");

/**
 * Class Database
 *
 * The following class provides the necessary queries and any altering needed on the database is done through
 * utilizing PDO from php. If verification for right user, inserting a new user, updating credentials of a user
 * or deleting an account is done from this class
 */
class Database
{
    //PDO Object
    private $_dbh;

    /**
     * Database constructor.
     *
     * Default constructor that instantiates a PDO Object that connects to a database that holds
     * the required tables for the data that needs to be stored
     */
    function __construct()
    {
        try {
            //Create new PDO connection
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * The following function retrieves all users stored from the database which includes all attributes of a user
     * such as user_ID, name, email, organization, position, password and isAdmin
     * @return array
     *  all the attributes of an user
     */
    function getUsers()
    {
        $sql = "SELECT * FROM MyUser
                ORDER BY name";

        $statement = $this->_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * The following function retrieves a specific user from the database based on email
     * @param $email
     *  specific email to search for user
     * @return mixed
     *  all attributes of the one user to search for
     */
    function getUser($email)
    {
        $sql = "SELECT * FROM MyUser
                    WHERE :email = email";

        $statement = $this->_dbh->prepare($sql);
		$statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);

    }

    /**
     * The following function will delete a user if the user has decided to delete
     * their account from the site
     * @param $id
     *  the required id aka user to delete from database
     */
    function deleteUser($id) {
    	$sql = "DELETE FROM `MyUser` WHERE `MyUser`.`user_ID` = :id";

    	$statement = $this->_dbh->prepare($sql);

    	$statement->bindParam(':id',$id);
    	$statement->execute();

	}

    /**
     * The following function will retrieve all posts from the database with all the attributes
     * such as id of each post and user, image extensions, article content and header
     * @return array
     *  all posts from the database
     */
    function getPosts()
    {
        $sql = "SELECT * FROM MyPost
                ORDER BY post_ID";

        $statement = $this->_dbh->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * The following function will get the most recent posts that were made from
     * the users of the website
     * @return array
     *  the 4 most recent posts made
     */
	function getMainPosts()
	{
		$sql = "SELECT * FROM MyPost
                ORDER BY post_ID DESC 
                LIMIT 4";

		$statement = $this->_dbh->prepare($sql);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

    /**
     * The following function retrieves a specific post from the given header
     * @param $header
     *  specific header to search for in database
     * @return mixed
     *  the attributes of the found post
     */
    function getPostByHeader($header) {
    	$sql = "SELECT * FROM MyPost
				WHERE :header = header";
		$statement = $this->_dbh->prepare($sql);
		$statement->bindParam(':header', $header);
		$statement->execute();
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

    /**
     * The following function retrieves the posts based on categories, the being general,
     * mobile, programming, and science
     * @param $category
     *  specific category to search for in each database
     * @return array
     *  the attributes of the searched post
     */
	function getCategoricalPosts($category) {
    	$sql = "SELECT * FROM MyPost
    			WHERE :category = category";
    	$statement = $this->_dbh->prepare($sql);
    	$statement->bindParam(':category',$category);
    	$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

    /**
     * The following function will insert a new user into the database
     * @param $user
     *  all the necessary fields to add to the database such as name, email, organization,
     *  position, password and if the user is an admin
     */
    function insertUser($user) {
        $sql = "INSERT INTO MyUser(name, email, organization, position, myPassword, isAdmin)
                    VALUES (:name, :email, :org, :position, :pswd, :isAdmin)";
        $statement = $this->_dbh->prepare($sql);
        $passhash = sha1($user->getPassword());
        if (is_a($user, "AdminUser")) {
            $isAdmin = 1;
        } else {
            $isAdmin = 0;
        }
        $statement ->bindParam(':name', $user->getName());
        $statement ->bindParam(':email', $user->getEmail());
        $statement ->bindParam(':org', $user->getOrganization());
        $statement ->bindParam(':position', $user->getPosition());
        $statement ->bindParam(':pswd', $passhash);
        $statement ->bindParam(':isAdmin', $isAdmin);

        $statement->execute();
    }

    /**
     * The following function will select a specific user to update the data to said selected user. The fields that
     * will be updated are name, email, organization and position for an account
     * @param $user
     *  specific user to look for to update credentials
     * @param $id
     *  specific id to search for
     */
	function updateUser($user, $id) {
		$sql = "UPDATE `MyUser` 
		SET `name` = :name, 
		`email` = :email, 
		`organization` = :org, 
		`position` = :pos 
		WHERE `MyUser`.`user_ID` = :id";

		$statement = $this->_dbh->prepare($sql);

		$statement ->bindParam(':name', $user->getName());
		$statement ->bindParam(':email', $user->getEmail());
		$statement ->bindParam(':org', $user->getOrganization());
		$statement ->bindParam(':pos', $user->getPosition());
		$statement ->bindParam(':id', $id);

		$statement->execute();
	}

    /**
     * The following function will update the password for the user by searching with id
     * @param $password
     *  the new password to set of the account
     * @param $id
     *  the specific account to update the password
     */
	function updatePassword($password, $id) {
    	$sql = "UPDATE `MyUser`
    	SET `myPassword` = :pswd
    	WHERE `MyUser`.`user_ID` = :id";

		$statement = $this->_dbh->prepare($sql);

		$statement ->bindParam(':id', $id);
		$statement ->bindParam(':pswd', $password);

		$statement->execute();
	}

    /**
     * The following function verifies the user is using the correct login credentials with the account they will
     * sign-in with to the website
     * @param $email
     *  specific email to look for to match account to login with
     * @param $password
     *  password for said account
     * @return bool
     *  true if the login credentials are met
     *  false if login credentials do not match
     */
    function verifyLogin($email, $password) {
        // PULL PASSWORD HASH
        $sql = "SELECT myPassword FROM MyUser
                    WHERE :email = email";
        $this->_dbh->beginTransaction();
        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $this->_dbh->commit();
        $hashArray = $statement->fetch(PDO::FETCH_ASSOC);

        $storedHash = $hashArray['myPassword'];
        return $storedHash == sha1(sha1($password));
    }

    /**
     * The following function will insert a new post that was created with an account.
     * @param $post
     *  post information to store in the database
     */
    function insertPost($post)
    {
        $sql = "INSERT INTO MyPost (`user_ID`, `articleText`, `header`, `category`)
                 VALUES (:userID, :body, :header, :category)";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':userID', $post->getUserID());
        $statement->bindParam(':body', $post->getBody());
        $statement->bindParam(':header', $post->getHeader());
        $statement->bindParam(':category', $post->getCategory());

        $statement ->execute();
    }

}