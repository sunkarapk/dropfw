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

class View extends Object {

/**
 * Helpers variables array
 */
	public static $help = array();

/**
 * Constructor
 */
	function __construct() {}

/**
 * Main rendering function
 */
	public static function render($file,$data) {
		extract($data, EXTR_SKIP);
		extract(self::$help, EXTR_SKIP);
		ob_start();

		if (file_exists($file))
			include_once $file;
		else {
			Error::missingView($file);
		}
		
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

/**
 * Add helper
 */
	public static function addhelper($name,$var) {
		self::$help = array_merge(self::$help,array($name => $var));
	}

}
