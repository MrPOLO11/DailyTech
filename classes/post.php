<?php

/**
 * Class Post
 *
 * The following class defines the attributes of a post such as
 * category, header, body and user associated with post
 */
class Post {
    //fields
	private $_category;
	private $_header;
	private $_body;
	private $_userID;


	/**
	 * post constructor.
	 * @param $_category
	 * @param $_header
	 * @param $_body
	 * @param $_userID
	 */
	public function __construct($_category,$_header,$_body,$_userID) {
		$this->_category=$_category;
		$this->_header=$_header;
		$this->_body=$_body;
		$this->_userID=$_userID;

	}


	/**
     * The following function retrieves id of user
	 * @return mixed
	 */
	public function getUserID() {
		return $this->_userID;
	}

	/**
     * The following function sets id of user
	 * @param mixed $userID
	 */
	public function setUserID($userID) {
		$this->_userID=$userID;
	}

    /**
     * The following function retrieves category of post
     * @return mixed
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * The following function retrieves header of post
     * @return mixed
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * The following function retrieves body of post
     * @return mixed
     */
    public function getBody()
    {
        return $this->_body;
    }

}