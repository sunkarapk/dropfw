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
	function setObject($properties = array()) {
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
	function stopExec($status = 0) {
		exit($status);
	}

}

?>
