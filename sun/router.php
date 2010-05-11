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

class Router extends Object {

/**
 * Route mapping variable
 */
	protected static $map = array();

/**
 * Constructor.
 */
	function __construct() {
	
	}

/**
 * Connector which connects one URL to another params.
 * @param mixed $url relative URL, like "/products/edit/92" or "/presidents/elect/4"
 */
	function connect($url,$params) {
		$paramsnew = self::extract($url);
		array_push(self::$map,array($paramsnew,$params));
	}

/**
 * GetLink which examines the params it recieve and do proper inclusion if connected
 * @param mixed $params
 */
	function getLink($params) {
		for($i=0;!empty(self::$map[$i]);$i++) {
			if($params['controller'] == self::$map[$i][0]['controller']) {
				if($params['action'] == self::$map[$i][0]['action']) {
					$params['controller'] = self::$map[$i][1]['controller'];
					$params['action'] = self::$map[$i][1]['action'];

					return $params;
				}
			}
		}
	}

/**
 * Extracting controller, action and parameters from the URL
 * @param mixed $url relative URL, like "/products/edit/92" or "/presidents/elect/4"
 * @return array $params Array with keys 'controller', 'action', 'params'
 */
	function extract($url) {
		$params = array();
		$url = explode('/',$url);
		
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
