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

class Sanitize extends Object {

/**
 * Constructor.
 */
	function __construct() {
	
	}
	
/**
 * Convenience method for htmlspecialchars.
 *
 * @param string $text Text to wrap through htmlspecialchars
 * @param string $charset Character set to use when escaping.  Defaults to config value in 'App.encoding' or 'UTF-8'
 * @return string Wrapped text
 */
	function hsc($text, $charset = null) {
		if (is_array($text)) {
			return array_map('h', $text);
		}

		static $defaultCharset = false;
		if ($defaultCharset === false) {
			$defaultCharset = Configure::read('App.encoding');
			if ($defaultCharset === null) {
				$defaultCharset = 'UTF-8';
			}
		}
		if ($charset) {
			return htmlspecialchars($text, ENT_QUOTES, $charset);
		} else {
			return htmlspecialchars($text, ENT_QUOTES, $defaultCharset);
		}
	}

}

?>
