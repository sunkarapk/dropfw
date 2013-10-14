<?php
/**
 * dropFW :  PHP Web Development Framework
 * Copyright 2010, Pavan Kumar Sunkara
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2010
 * @version	1.0.0
 * @author	Pavan Kumar Sunkara
 * @license	MIT
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
