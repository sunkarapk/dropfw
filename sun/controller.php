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

class Controller extends Object {
/**
 * The name of this controller. Controller names are plural, named after the model they manipulate.
 *
 * @var string
 * @access public
 * @link http://book.cakephp.org/view/52/name
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
 * An array containing the class names of the models this controller uses.
 *
 * @var array Array of model objects.
 * @access public
 */
	var $modelNames = array();

/**
 * Set to true to automatically render the view
 * after action logic.
 *
 * @var boolean
 * @access public
 */
	var $autoRender = true;

/**
 * Array containing the names of components this controller uses. Component names
 * should not contain the "Component" portion of the classname.
 *
 * Example: var $components = array('Session', 'RequestHandler', 'Acl');
 *
 * @var array
 * @access public
 */
	var $components = array();

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
			$this->viewPath = Inflector::underscore($this->name);
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

		if(file_exists(MODELS.$modelClass.'.php'))
			require_once MODELS.$modelClass.'.php';

		if (!class_exists($modelClass)) {
			print "Missing $modelClass";
		}
		else
			eval("\$this->$modelClass = new $modelClass(); ");

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
			$this->header('Location: ');
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
 * @link http://book.cakephp.org/view/427/set
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
			if ($name === 'title') {
				$this->pageTitle = $value;
			} else {
				if ($two === null && is_array($one)) {
					$this->viewVars[Inflector::variable($name)] = $value;
				} else {
					$this->viewVars[$name] = $value;
				}
			}
		}
	}
/**
 * Internally redirects one action to another. Examples:
 *
 * setAction('another_action');
 * setAction('action_with_parameters', $parameter1);
 *
 * @param string $action The new action to be redirected to
 * @param mixed  Any other parameters passed to this method will be passed as
 *               parameters to the new action.
 * @return mixed Returns the return value of the called action
 * @access public
 */
	function setAction($action) {
		$this->action = $action;
		$args = func_get_args();
		unset($args[0]);
		return call_user_func_array(array(&$this, $action), $args);
	}
/**
 * Controller callback to tie into Auth component. Only called when AuthComponent::authorize is set to 'controller'.
 *
 * @return bool true if authorized, false otherwise
 * @access public
 * @link http://book.cakephp.org/view/396/authorize
 */
	function isAuthorized() {
		trigger_error(sprintf(__('%s::isAuthorized() is not defined.', true), $this->name), E_USER_WARNING);
		return false;
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
 * @link http://book.cakephp.org/view/428/render
 */
	function render($action = null, $layout = null, $file = null) {
		$this->beforeRender();

		$viewClass = $this->view;
		if ($this->view != 'View') {
			if (strpos($viewClass, '.') !== false) {
				list($plugin, $viewClass) = explode('.', $viewClass);
			}
			$viewClass = $viewClass . 'View';
			App::import('View', $this->view);
		}

		$this->Component->beforeRender($this);

		$this->params['models'] = $this->modelNames;

		if (Configure::read() > 2) {
			$this->set('cakeDebug', $this);
		}

		$View =& new $viewClass($this);

		if (!empty($this->modelNames)) {
			$models = array();
			foreach ($this->modelNames as $currentModel) {
				if (isset($this->$currentModel) && is_a($this->$currentModel, 'Model')) {
					$models[] = Inflector::underscore($currentModel);
				}
				if (isset($this->$currentModel) && is_a($this->$currentModel, 'Model') && !empty($this->$currentModel->validationErrors)) {
					$View->validationErrors[Inflector::camelize($currentModel)] =& $this->$currentModel->validationErrors;
				}
			}
			$models = array_diff(ClassRegistry::keys(), $models);
			foreach ($models as $currentModel) {
				if (ClassRegistry::isKeySet($currentModel)) {
					$currentObject =& ClassRegistry::getObject($currentModel);
					if (is_a($currentObject, 'Model') && !empty($currentObject->validationErrors)) {
						$View->validationErrors[Inflector::camelize($currentModel)] =& $currentObject->validationErrors;
					}
				}
			}
		}

		$this->autoRender = false;
		$this->output .= $View->render($action, $layout, $file);

		return $this->output;
	}
/**
 * Returns the referring URL for this request.
 *
 * @param string $default Default URL to use if HTTP_REFERER cannot be read from headers
 * @param boolean $local If true, restrict referring URLs to local server
 * @return string Referring URL
 * @access public
 * @link http://book.cakephp.org/view/430/referer
 */
	function referer($default = null, $local = false) {
		$ref = env('HTTP_REFERER');
		if (!empty($ref) && defined('FULL_BASE_URL')) {
			$base = FULL_BASE_URL . $this->webroot;
			if (strpos($ref, $base) === 0) {
				$return =  substr($ref, strlen($base));
				if ($return[0] != '/') {
					$return = '/'.$return;
				}
				return $return;
			} elseif (!$local) {
				return $ref;
			}
		}

		if ($default != null) {
			return $default;
		}
		return '/';
	}
/**
 * Forces the user's browser not to cache the results of the current request.
 *
 * @return void
 * @access public
 * @link http://book.cakephp.org/view/431/disableCache
 */
	function disableCache() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
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
 * @link http://book.cakephp.org/view/426/flash
 */
	function flash($message, $url, $pause = 1) {
		$this->autoRender = false;
		$this->set('url', Router::url($url));
		$this->set('message', $message);
		$this->set('pause', $pause);
		$this->set('page_title', $message);
		$this->render(false, 'flash');
	}
/**
 * Converts POST'ed form data to a model conditions array, suitable for use in a Model::find() call.
 *
 * @param array $data POST'ed data organized by model and field
 * @param mixed $op A string containing an SQL comparison operator, or an array matching operators to fields
 * @param string $bool SQL boolean operator: AND, OR, XOR, etc.
 * @param boolean $exclusive If true, and $op is an array, fields not included in $op will not be included in the returned conditions
 * @return array An array of model conditions
 * @access public
 * @link http://book.cakephp.org/view/432/postConditions
 */
	function postConditions($data = array(), $op = null, $bool = 'AND', $exclusive = false) {
		if (!is_array($data) || empty($data)) {
			if (!empty($this->data)) {
				$data = $this->data;
			} else {
				return null;
			}
		}
		$cond = array();

		if ($op === null) {
			$op = '';
		}

		foreach ($data as $model => $fields) {
			foreach ($fields as $field => $value) {
				$key = $model.'.'.$field;
				$fieldOp = $op;
				if (is_array($op) && array_key_exists($key, $op)) {
					$fieldOp = $op[$key];
				} elseif (is_array($op) && array_key_exists($field, $op)) {
					$fieldOp = $op[$field];
				} elseif (is_array($op)) {
					$fieldOp = false;
				}
				if ($exclusive && $fieldOp === false) {
					continue;
				}
				$fieldOp = strtoupper(trim($fieldOp));
				if ($fieldOp === 'LIKE') {
					$key = $key.' LIKE';
					$value = '%'.$value.'%';
				} elseif ($fieldOp && $fieldOp != '=') {
					$key = $key.' '.$fieldOp;
				}
				$cond[$key] = $value;
			}
		}
		if ($bool != null && strtoupper($bool) != 'AND') {
			$cond = array($bool => $cond);
		}
		return $cond;
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
