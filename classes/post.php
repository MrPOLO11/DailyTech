<?php

class Post {
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
	 * @return mixed
	 */
	public function getUserID() {
		return $this->_userID;
	}

	/**
	 * @param mixed $userID
	 */
	public function setUserID($userID) {
		$this->_userID=$userID;
	}

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->_body;
    }




}