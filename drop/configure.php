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

class Configure extends Object {

/**
 * The main variable where all the config infor is stored
 */
	protected static $info = array();

/**
 * Used to store a dynamic variable in configure instance
 * @param array $config Name of var to write
 * @param mixed $value Value to set for var
 * @return void
 * @access public
 */
	public static function write($config, $value = null) {
		if (!is_array($config)) {
			$config = array($config => $value);
		}

		foreach ($config as $names => $value) {
			$name = self::configVarNames($names);

			switch (count($name)) {
				case 3:
					self::$info[$name[0]][$name[1]][$name[2]] = $value;
				break;
				case 2:
					self::$info[$name[0]][$name[1]] = $value;
				break;
				case 1:
					self::$info[$name[0]] = $value;
				break;
			}
		}
	}

/**
 * Used to read information stored in the Configure instance.
 * @param string $var Variable to obtain
 * @return string value of Configure::$var
 * @access public
 */
	public static function read($var) {
		$name = self::configVarNames($var);

		switch (count($name)) {
			case 3:
				if (isset(self::$info[$name[0]][$name[1]][$name[2]])) {
					return self::$info[$name[0]][$name[1]][$name[2]];
				}
			break;
			case 2:
				if (isset(self::$info[$name[0]][$name[1]])) {
					return self::$info[$name[0]][$name[1]];
				}
			break;
			case 1:
				if (isset(self::$info[$name[0]])) {
					return self::$info[$name[0]];
				}
			break;
		}
		return null;
	}

/**
 * Used to delete a variable from the Configure instance.
 * @param string $var the var to be deleted
 * @return void
 * @access public
 */
	public static function delete($var) {
		$name = self::configVarNames($var);

		if (count($name) > 2) {
			unset(self::$info[$name[0]][$name[1]][$name[2]]);
		} else if (count($name) == 2) {
			unset(self::$info[$name[0]][$name[1]]);
		} else {
			unset(self::$info[$name[0]]);
		}
	}

/**
 * Used to check a variable from the Configure instance.
 * @param string $var the var to be checked for
 * @return boolean
 * @access public
 */
	public static function check($var) {
		$name = self::configVarNames($var);
		
		if(array_key_exists($name[0],self::$info)) {
			if (count($name) >= 2) {
				if(array_key_exists($name[1],self::$info[$name[0]])) {
					if (count($name) > 2) {
						if(array_key_exists($name[2],self::$info[$name[0]][$name[1]])) {
							return true;
						} else {
							return false;
						}
					} else {
						return true;
					}
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

/**
 * Checks $name for dot notation to create dynamic Configure::$var as an array when needed.
 * @param mixed $name Name to split
 * @return array Name separated in items through dot notation
 * @access private
 */
	protected static function configVarNames($name) {
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
