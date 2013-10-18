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

		if ($configure->read('rewrite')) {
			$this->base = HOST;
		} else {
			$this->base = HOST. $configure->read('app.base') .DS;
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
		define('CONTROLLER', $this->params['controller']);

		$this->params = Router::getLink($this->params);

		$filename = $this->params['controller'].'.php';
		$classname = Inflector::camelize($this->params['controller'].'_controller');

		if(file_exists(CONTROLLERS.$filename))
			require_once CONTROLLERS.$filename;

		if(!class_exists($classname)) {
			Error::missingController($this->params['controller']);
		} else {
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
