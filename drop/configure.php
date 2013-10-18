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

# TODO: Fix the bad "switch" code

class Configure extends Object {

/**
 * The main variable where all the config infor is stored
 */
	protected $info = array();

/**
 * Constructor.
 */
	function __construct() {}

/**
 * Used to store a dynamic variable in configure instance
 * @param array $config Name of var to write
 * @param mixed $value Value to set for var
 * @return void
 * @access public
 */
	public function write($config, $value = null) {
		if (!is_array($config)) {
			$config = array($config => $value);
		}

		foreach ($config as $names => $values) {
			$name = $this->configVarNames($names);
			$tmp  = $this->info;

			foreach ($name as $key => $nameval) {
				$tmp = $tmp[$nameval];
			}

			$tmp = $value;
		}
	}

/**
 * Used to read information stored in the Configure instance.
 * @param string $var Variable to obtain
 * @return string value of Configure::$var
 * @access public
 */
	public function read($var) {
		$name = $this->configVarNames($var);
		$tmp  = $this->info;

		foreach ($name as $key => $value) {
			if (array_key_exists($tmp, $value)) {
				$tmp = $tmp[$value];
			} else {
				return null;
			}
		}

		return $tmp;
	}

/**
 * Used to delete a variable from the Configure instance.
 * @param string $var the var to be deleted
 * @return void
 * @access public
 */
	public function delete($var) {
		$name = $this->configVarNames($var);
		$tmp  = $this->info;

		foreach ($name as $key => $value) {
			if (array_key_exists($tmp, $value)) {
				$tmp = $tmp[$value];
			}
		}

		return unset($tmp);
	}

/**
 * Used to check a variable from the Configure instance.
 * @param string $var the var to be checked for
 * @return boolean
 * @access public
 */
	public function check($var) {
		$name = $this->configVarNames($var);
		$tmp  = $this->info;

		foreach ($name as $key => $value) {
			if (array_key_exists($value, $tmp)) {
				$tmp = $tmp[$value];
			} else {
				return false;
			}
		}

		return true;
	}

/**
 * Checks $name for dot notation to create dynamic Configure::$var as an array when needed.
 * @param mixed $name Name to split
 * @return array Name separated in items through dot notation
 * @access private
 */
	protected function configVarNames($name) {
		if (is_string($name)) {
			if (strpos($name, ".")) {
				return explode(".", $name);
			}
			return array($name);
		}
		return $name;
	}

/**
 * TODO: Storing the configuration info into a file
 * TODO: Loading the configuration info from a file
 */
 
}

?>
