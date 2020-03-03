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

    function getPosts()
    {
        $sql = "SELECT * FROM MyPost
                ORDER BY post_ID";

        $statement = $this->_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}