<?php
require_once ("config-DailyTech.php");

class Database
{
    //PDO Object
    private $_dbh;

    function __construct()
    {
        try {
            //Create new PDO connection
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function getUsers()
    {
        $sql = "SELECT * FROM MyUser
                ORDER BY name";

        $statement = $this->_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function getUser($email)
    {
        $sql = "SELECT * FROM MyUser
                    WHERE :email = email";

        $statement = $this->_dbh->prepare($sql);
		$statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);

    }

    function deleteUser($id) {
    	$sql = "DELETE FROM `MyUser` WHERE `MyUser`.`user_ID` = :id";

    	$statement = $this->_dbh->prepare($sql);

    	$statement->bindParam(':id',$id);
    	$statement->execute();

	}

    function getPosts()
    {
        $sql = "SELECT * FROM MyPost
                ORDER BY post_ID";

        $statement = $this->_dbh->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

	function getMainPosts()
	{
		$sql = "SELECT * FROM MyPost
                ORDER BY post_ID DESC 
                LIMIT 4";

		$statement = $this->_dbh->prepare($sql);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

    function getPostByHeader($header) {
    	$sql = "SELECT * FROM MyPost
				WHERE :header = header";
		$statement = $this->_dbh->prepare($sql);
		$statement->bindParam(':header', $header);
		$statement->execute();
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	function getCategoricalPosts($category) {
    	$sql = "SELECT * FROM MyPost
    			WHERE :category = category";
    	$statement = $this->_dbh->prepare($sql);
    	$statement->bindParam(':category',$category);
    	$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

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

	function updatePassword($password, $id) {
    	$sql = "UPDATE `MyUser`
    	SET `myPassword` = :pswd
    	WHERE `MyUser`.`user_ID` = :id";

		$statement = $this->_dbh->prepare($sql);

		$statement ->bindParam(':id', $id);
		$statement ->bindParam(':pswd', $password);

		$statement->execute();
	}

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

    function insertPost($post)
    {
        $serverUser = $this->getUser($_SESSION['user']->getEmail());

        $sql = "INSERT INTO MyPost (`user_ID`, `articleText`, `header`, `category`)
                 WHERE :userID = user_ID
                 VALUES (:userID, :body, :header, :category)";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':userID', $serverUser['user_ID']);
        $statement->bindParam(':body', $post->getBody());
        $statement->bindParam(':header', $post->getHeader());
        $statement->bindParam(':category', $post->getCategory());

        $statement ->execute();
    }

}