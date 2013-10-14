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

class Router extends Object {

/**
 * Route mapping variable
 */
	protected static $map = array();

/**
 * Constructor.
 */
	function __construct() { }

/**
 * Connector which connects one URL to another params.
 * @param mixed $url relative URL, like "/products/edit/92" or "/presidents/elect/4"
 */
	public static function connect($url,$params) {
		$paramsnew = self::extract($url);
		array_push(self::$map,array($paramsnew,$params));
	}

/**
 * GetLink which examines the params it recieve and do proper inclusion if connected
 * @param mixed $params
 */
	public static function getLink($params) {
		for($i=0;!empty(self::$map[$i]);$i++) {
			if($params['controller'] == self::$map[$i][0]['controller'] && $params['action'] == self::$map[$i][0]['action']) {
					$params['controller'] = self::$map[$i][1]['controller'];
					$params['action'] = self::$map[$i][1]['action'];
					if(isset($params['params'])) {
						$params['params'] = self::$map[$i][1]['params'];
					}
			}
		}
		return $params;
	}

/**
 * Extracting controller, action and parameters from the URL
 * @param mixed $url relative URL, like "/products/edit/92" or "/presidents/elect/4"
 * @return array $params Array with keys 'controller', 'action', 'params'
 */
	public static function extract($url) {
		$params = array();
		$url = explode('/',$url);
		
		if (empty($url[0])) {
			array_shift($url);
		}
		
		if (empty($url[0])) {
			$params['controller'] = 'docs';
		} elseif ($url[0]=='ajax') {
			$this->ajax = true;
			array_shift($url);
			$params['controller'] = empty($url[0])?'docs':$url[0];
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
