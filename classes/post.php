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


}