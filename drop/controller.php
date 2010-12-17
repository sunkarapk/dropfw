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

class Controller extends Object {
/**
 * The name of this controller. Controller names are plural, named after the model they manipulate.
 *
 * @var string
 * @access public
 */
	var $name = null;

/**
 * The name of the currently requested controller action.
 *
 * @var string
 * @access public
 */
	var $action = null;

/**
 * An array containing the class names of models this controller uses.
 *
 * Example: var $uses = array('Product', 'Post', 'Comment');
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @access protected
 */
	var $uses = false;

/**
 * An array containing the names of helpers this controller uses. The array elements should
 * not contain the "Helper" part of the classname.
 *
 * Example: var $helpers = array('Html', 'Javascript', 'Time', 'Ajax');
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @access protected
 */
	var $helpers = array();

/**
 * Parameters received in the current request: GET and POST data, information
 * about the request, etc.
 *
 * @var array
 * @access public
 */
	var $params = array();

/**
 * Data POSTed to the controller using the HtmlHelper. Data here is accessible
 * using the $this->data['ModelName']['fieldName'] pattern.
 *
 * @var array
 * @access public
 */
	var $data = array();

/**
 * The name of the views subfolder containing views for this controller.
 *
 * @var string
 * @access public
 */
	var $viewPath = null;

/**
 * Contains variables to be handed to the view.
 *
 * @var array
 * @access public
 */
	var $viewVars = array();

/**
 * Text to be used for the $title_for_layout layout variable (usually
 * placed inside <title> tags.)
 *
 * @var boolean
 * @access public
 */
	var $pageTitle = false;

/**
 * Set to true to automatically render the view
 * after action logic.
 *
 * @var boolean
 * @access public
 */
	var $autoRender = true;

/**
 * The output of the requested action.  Contains either a variable
 * returned from the action, or the data of the rendered view;
 * You can use this var in child controllers' afterFilter() callbacks to alter output.
 *
 * @var string
 * @access public
 */
	var $output = null;

/**
 * Holds all params passed and named.
 *
 * @var mixed
 * @access public
 */
	var $passedArgs = array();

/**
 * Holds current methods of the controller
 *
 * @var array
 * @access public
 * @link
 */
	var $methods = array();

/**
 * This controller's primary model class name, the Inflector::classify()'ed version of
 * the controller's $name property.
 *
 * Example: For a controller named 'Comments', the modelClass would be 'Comment'
 *
 * @var string
 * @access public
 */
	var $modelClass = null;

/**
 * This controller's model key name, an underscored version of the controller's $modelClass property.
 *
 * Example: For a controller named 'ArticleComments', the modelKey would be 'article_comment'
 *
 * @var string
 * @access public
 */
	var $modelKey = null;

/**
 * Holds any validation errors produced by the last call of the validateErrors() method/
 *
 * @var array Validation errors, or false if none
 * @access public
 */
	var $validationErrors = null;

/**
 * Constructor.
 */
	function __construct() {
		if ($this->name === null) {
			$r = null;
			if (!preg_match('/(.*)Controller/i', get_class($this), $r)) {
				die (__("Controller::__construct() : Can not get or parse my own class name, exiting."));
			}
			$this->name = $r[1];
		}

		if ($this->viewPath == null) {
			$this->viewPath = VIEWS.Inflector::underscore($this->name).DS;
		}
		$this->modelClass = Inflector::classify($this->name);
		$this->modelKey = Inflector::underscore($this->modelClass);

		$childMethods = get_class_methods($this);
		$parentMethods = get_class_methods('Controller');

		foreach ($childMethods as $key => $value) {
			$childMethods[$key] = strtolower($value);
		}

		foreach ($parentMethods as $key => $value) {
			$parentMethods[$key] = strtolower($value);
		}

		$this->methods = array_diff($childMethods, $parentMethods);

		foreach ($this->uses as $model)
			$this->loadModel($model);

		foreach ($this->helpers as $helper)
			$this->loadHelper($helper);
		
		parent::__construct();
	}

/**
 * Loads and instantiates models required by this controller.
 * @param string $modelClass Name of model class to load
 * @return mixed true when single model found and instance created error returned if models not found.
 * @access public
 */
	function loadModel($modelClass = null) {
		if ($modelClass === null) {
			$modelClass = $this->modelClass;
		}

		$umodel = Inflector::underscore($modelClass);

		if(file_exists(MODELS.$umodel.'.php'))
			require_once MODELS.$umodel.'.php';

		if (!class_exists($modelClass)) {
			Error::missingModel($modelClass);
		}
		else
			eval("\$this->$modelClass = new $modelClass(); ");

	}

/**
 * Loads and instantiates helpers required by this controller.
 * @param string $helperClass Name of helper class to load
 * @return mixed true when single model found and instance created error returned if models not found.
 * @access public
 */
	function loadHelper($helperClass = null) {
		$uhelper = Inflector::underscore($helperClass);
		if(file_exists(CORE.$uhelper.'.php'))
			require_once CORE.$uhelper.'.php';

		if (!class_exists($helperClass)) {
			Error::missingHelper($helperClass);
		}
		else
			eval("\$this->$uhelper = new $helperClass(); ");

	}

/**
 * Redirects to given $url, after turning off $this->autoRender.
 * Script execution is halted after the redirect.
 *
 * @param mixed $url A string or array-based URL pointing to another location within the app, or an absolute URL
 * @param integer $status Optional HTTP status code (eg: 404)
 * @param boolean $exit If true, exit() will be called after the redirect
 * @return mixed void if $exit = false. Terminates script if $exit = true
 * @access public
 */
	function redirect($url, $status = null, $exit = true) {
		$this->autoRender = false;

		if (function_exists('session_write_close')) {
			session_write_close();
		}

		if (!empty($status)) {
			$codes = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Time-out',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Large',
				415 => 'Unsupported Media Type',
				416 => 'Requested range not satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Time-out'
			);
			if (is_string($status)) {
				$codes = array_combine(array_values($codes), array_keys($codes));
			}

			if (isset($codes[$status])) {
				$code = $msg = $codes[$status];
				if (is_numeric($status)) {
					$code = $status;
				}
				if (is_string($status)) {
					$msg = $status;
				}
				$status = "HTTP/1.1 {$code} {$msg}";
			} else {
				$status = null;
			}
		}

		if (!empty($status)) {
			$this->header($status);
		}
		if ($url !== null) {
			$this->header('Location: '.BASE.$url);
		}

		if (!empty($status) && ($status >= 300 && $status < 400)) {
			$this->header($status);
		}

		if ($exit) {
			$this->stop();
		}
	}

/**
 * Convenience method for header()
 *
 * @param string $status
 * @return void
 * @access public
 */
	function header($status) {
		header($status);
	}

/**
 * Saves a variable for use inside a view template.
 *
 * @param mixed $one A string or an array of data.
 * @param mixed $two Value in case $one is a string (which then works as the key).
 *   Unused if $one is an associative array, otherwise serves as the values to $one's keys.
 * @return void
 * @access public
 */
	function set($one, $two = null) {
		$data = array();

		if (is_array($one)) {
			if (is_array($two)) {
				$data = array_combine($one, $two);
			} else {
				$data = $one;
			}
		} else {
			$data = array($one => $two);
		}

		foreach ($data as $name => $value) {
			if ($two === null && is_array($one)) {
				$this->viewVars[Inflector::variable($name)] = $value;
			} else {
				$this->viewVars[$name] = $value;
			}
		}
	}

/**
 * Do the required action
 * @param string $action The action to be done
 */
	function doAction() {
		$args = func_get_args();
		return call_user_func_array(array(&$this, $this->action), $args);
	}

/**
 * Returns number of errors in a submitted FORM.
 *
 * @return integer Number of errors
 * @access public
 */
	function validate() {
		$args = func_get_args();
		$errors = call_user_func_array(array(&$this, 'validateErrors'), $args);

		if ($errors === false) {
			return 0;
		}
		return count($errors);
	}

/**
 * Validates models passed by parameters. Example:
 *
 * $errors = $this->validateErrors($this->Article, $this->User);
 *
 * @param mixed A list of models as a variable argument
 * @return array Validation errors, or false if none
 * @access public
 */
	function validateErrors() {
		$objects = func_get_args();

		if (!count($objects)) {
			return false;
		}

		$errors = array();
		foreach ($objects as $object) {
			$this->{$object->alias}->set($object->data);
			$errors = array_merge($errors, $this->{$object->alias}->invalidFields());
		}

		return $this->validationErrors = (count($errors) ? $errors : false);
	}

/**
 * Instantiates the correct view class, hands it its data, and uses it to render the view output.
 *
 * @param string $action Action name to render
 * @param string $layout Layout to use
 * @param string $file File to use for rendering
 * @return string Full output string of view contents
 * @access public
 */
	function render($action = null, $layout = "default") {
		$this->beforeRender();

		foreach($this->helpers as $helper) {
			$uhelper = Inflector::underscore($helper);
			View::addhelper($uhelper,$this->{$uhelper});
		}
				
		$out = View::render($this->viewPath.$action.".ctp",$this->viewVars);
		$this->output = View::render(VIEWS.$layout.".ctp",array("content_for_layout" => $out,"title" => $this->pageTitle));
	}

/**
 * Forces the user's browser not to cache the results of the current request.
 *
 * @return void
 * @access public
 */
	function disableCache() {
		header("Expires: Mon, 28 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
/**
 * Shows a message to the user for $pause seconds, then redirects to $url.
 * Uses flash.ctp as the default layout for the message.
 * Does not work if the current debug level is higher than 0.
 *
 * @param string $message Message to display to the user
 * @param mixed $url Relative string or array-based URL to redirect to after the time expires
 * @param integer $pause Time to show the message
 * @return void Renders flash layout
 * @access public
 */
	function flash($message, $url, $pause = 1) {
		$this->autoRender = false;
		$this->set('url', BASE.$url);
		$this->set('message', $message);
		$this->set('pause', $pause);
		$this->pageTitle = $message;
		$this->render(false, 'flash');
	}

/**
 * Called before the controller action.
 * @access public
 */
	function beforeFilter() {
	}

/**
 * Called after the controller action is run, but before the view is rendered.
 * @access public
 */
	function beforeRender() {
	}

/**
 * Called after the controller action is run and rendered.
 * @access public
 */
	function afterFilter() {
	}

}
?>
