<?php
/**
 * dropFW(tm) :  PHP Web Development Framework (http://www.suncoding.com)
 * Copyright 2010, Sun Web Dev, Inc.
 *
 * Licensed under The GPLv3 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2010, Sun Web Dev, Inc. (http://www.suncoding.com)
 * @version	1.0.0
 * @author	Pavan Kumar Sunkara
 * @license	GPLv3
 */

class Session extends Object {

/**
 * Constructor.
 */
	function __construct() {
		session_start();
	}

/**
 * Method to set session variable
 */
	function set($key, $val) {
		$_SESSION[$key] = $val;
	}

/**
 * Method to get session variable
 */
	function get($key) {
		return $_SESSION[$key];
	}

/**
 * Method to delete session variable
 */
	function delete($key) {
		unset($_SESSION[$key]);
	}

/**
 * Method to check session variable
 */
	function check($key) {
		if(array_key_exists($key,$_SESSION))
			return true;
		else
			return false;
	}

/**
 * Destructor
 */
	function __destruct() {
		session_unset();
	}

}

?>
