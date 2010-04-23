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

class Dispatcher extends Object {

/**
 * URL base
 */
	var $base = null;

/**
 * Parameters for the URL
 */
	var $params = null;

/**
 * The main URL
 */
	var $url = null;

/**
 * Constructor.
 */
	function __construct($url = null) {
		$this->url = $url;
		if (Configure::read('rewrite')) {
			$this->base = HOST;
		} else {
			$this->base = HOST. Configure::read('App.base') .DS;
		}
		$this->params = $this->extract();
		return $this->dispatch();
	}
	
/**
 * Main Dispatcher
 * @return array $params Array with keys 'controller', 'action', 'params'
 * @return boolean Success
 */
	function dispatch() {

	}

/**
 * Extracting controller, action and parameters from the URL
 * @param mixed $url relative URL, like "/products/edit/92" or "/presidents/elect/4"
 * @return array $params Array with keys 'controller', 'action', 'params'
 */
	function extract() {
		$params = array();
		$url = explode('/',$this->url);
		
		if (empty($url[0])) {
			array_shift($url);
		}
		
		if (empty($url[0])) {
			$params['controller'] = 'pages';
		} else {
			$params['controller'] = $url[0];
		}
		
		array_shift($url);
		
		if (empty($url[0])) {
			$params['action'] = 'index';
		} else {
			$params['action'] = $url[0];
		}
		
		array_shift($url);
		
		foreach ($url as $key=>$val) {
			if (!empty($val)) {
				$params['params'][$key] = $val;
			} else {
				break;
			}
		}
		
		return $params;
	}

}

?>
