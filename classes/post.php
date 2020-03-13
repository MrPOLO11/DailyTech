<?php

class post {
	private $_type;
	private $_header;
	private $_body;

	/**
	 * post constructor.
	 * @param $_type
	 * @param $_header
	 * @param $_body
	 */
	public function __construct($_type,$_header,$_body) {
		$this->_type=$_type;
		$this->_header=$_header;
		$this->_body=$_body;
	}

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
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