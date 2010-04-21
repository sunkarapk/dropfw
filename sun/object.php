<?php
/**
 * sunFW(tm) :  PHP Web Development Framework (http://www.suncoding.com)
 * Copyright 2010, Sun Web Dev, Inc.
 *
 * Licensed under The GPLv3 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright      Copyright 2010, Sun Web Dev, Inc. (http://www.suncoding.com)
 * @version         1.0.0
 * @modifiedby    Pavan Kumar Sunkara
 * @lastmodified  Apr 10, 2010
 * @license         GPLv3
 */

class Object {

/**
 * Class constructor, overridden in descendant classes.
 */
	function __construct() {
	}

/**
 * Object-to-string conversion.
 * Each class can override this method as necessary.
 *
 * @return string The name of this class
 * @access public
 */	
	function toStr() {
		return get_class($this);
	}

/**
 * Allows setting of multiple properties of the object in a single line of code.
 *
 * @param array $properties An associative array containing properties and corresponding values.
 * @return void
 * @access protected
 */
	function set($properties = array()) {
		if (is_array($properties) && !empty($properties)) {
			$vars = get_object_vars($this);
			foreach ($properties as $key => $val) {
				if (array_key_exists($key, $vars))
					$this->{$key} = $val;
			}
		}
	}

/**
 * Stop execution of the current script
 *
 * @param $status see http://php.net/exit for values
 * @return void
 * @access public
 */
	function stop($status = 0) {
		exit($status);
	}

?>
