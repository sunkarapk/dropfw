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
 * AJAX urls
 */
	var $ajax = false;

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
		$this->dispatch();
	}
	
/**
 * Main Dispatcher
 * @return array $params Array with keys 'controller', 'action', 'params'
 * @return boolean Success
 */
	function dispatch() {
		$this->params = Router::getLink($this->params);

		$filename = $this->params['controller'].'.php';
		$classname = Inflector::camelize($this->params['controller'].'_controller');

		if(file_exists(CONTROLLERS.$filename))
			require_once CONTROLLERS.$filename;

		if(!class_exists($classname)) {
			Error::missingController($this->params['controller']);
		}
		else {
			eval("\$controllerVar = new $classname();");

			if (!in_array(strtolower($this->params['action']),$controllerVar->methods)) {
				Error::missingAction($this->params['action'],$this->params['controller']);
			} else {
				$controllerVar->action = $this->params['action'];
				
				$controllerVar->beforeFilter();

				$actstr = "\$controllerVar->output = \$controllerVar->doAction(";
				if(!empty($this->params['params'][0])) {
					$actstr .= $this->params['params'][0];
					for($i=1; !empty($this->params['params'][$i]); $i++) {
						$actstr .= ",".$this->params['params'][$i];
					}
				}

				eval($actstr.");");

				$controllerVar->afterFilter();
				
				if ($controllerVar->autoRender) {
					if($this->ajax)
						$controllerVar->render($this->params['action'],"ajax");
					else
						$controllerVar->render($this->params['action']);
				}
				echo $controllerVar->output;
			}
		}
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
		} elseif ($url[0]=='ajax') {
			$this->ajax = true;
			array_shift($url);
			$params['controller'] = empty($url[0])?'pages':$url[0];
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
