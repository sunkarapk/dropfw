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

class Error extends Object {

/**
 * Variable for code to be written in Error <pre> tag.
 */
	var $code = null;
/**
 * Variable for message
 */
	var $msg = null;
/**
 * Variable for suggestion
 */
	var $sug = null;
/**
 * Constructor.
 */
	function __construct() {}

/**
 * Render error page
 */
	function render() {
		if($this->code===null)
			echo View::render(VIEW.'error.ctp',array('msg'=>$this->msg,'sug'=>$this->sug,'code'=>null));
		else
			echo View::render(VIEWS.'error.ctp',array('msg'=>$this->msg,'sug'=>$this->sug,'code'=>Sanitize::hsc($this->code)));
		die();
	}

/**
 * Missing model
 */
	function missingModel($modelClass) {
		$model = Inflector::underscore($modelClass);
		$this->msg = "<b>Missing Model</b>: $modelClass";
		$this->sug = "Create the class <b>$modelClass</b> in <b>".MODELS.$model.".php</b>";
		$this->code = "<?php\nclass $modelClass extends Model {\n\n\tvar \$uses = array();\n}\n?>";
		self::render();
	}

/**
 * Missing helper
 */
	function missingHelper($helperClass) {
		$helper = Inflector::underscore($helperClass);
		$this->msg = "<b>Missing Library</b>: $helperClass";
		$this->code = "<?php\nclass $helperClass extends Object {\n\n\tfunction __construct() {\n\t}\n}\n?>";
		$this->sug = "Create the class <b>$helperClass</b> in <b>".CORE.$helper.".php</b>";
		self::render();
	}

/**
 * Missing controller
 */
	function missingController($controller) {
		$classname = Inflector::camelize($controller.'_controller');
		$this->code = "<?php\nclass $classname extends Controller {\n\n\tvar \$helpers = array('Html');\n}\n?>";
		$this->msg = "<b>Missing Controller</b>: $classname";
		$this->sug = "Create the class <b>$classname</b> in <b>".CONTROLLERS.$controller.".php</b>";
		self::render();
	}

/**
 * Missing Action
 */
	function missingAction($action,$controller) {
		$classname = Inflector::camelize($controller.'_controller');
		$this->code = "<?php\nclass $classname extends Controller {\n\n\tvar \$helpers = array('Html');\n\n\tfunction $action() {\n\t}\n}\n?>";
		$this->msg = "<b>Missing Action</b>: $action";
		$this->sug = "Create <b>$classname::$action()</b> in <b>".CONTROLLERS.$controller.".php</b>";
		self::render();
	}

/**
 * Missing view
 */
	function missingView($file) {
		$this->code = "<div>\n\t<!-- $file -->\n</div>";
		$this->msg = "<b>Missing View</b>: $file";
		$this->sug = "Create view in <b>$file</b>";
		self::render();
	}

/**
 * JSON error
 */
	function json($data) {
		$this->code = print_r($data,true);
		$this->msg = "<b>JSON error</b>: ".gettype($data);
		$this->sug = "<b>".gettype($data)."</b> cannot be encoded to JSON string";
		self::render();
	}

/**
 * Empty cipher key
 */
	function emptyCipherKey() {
			$this->code = "<?php\n\tSecurity::cipher(\$text,'9nHPrYcxmvTliA');\n?>";
			$this->msg = "<b>Encyption error</b>: Empty key";
			$this->sug = "You cannot use an empty key for <b>Security::cipher()</b>";
			self::render();
	}

/**
 * Validation Comparison: No operator
 */
	function validationComparison($opertor) {
		$this->code = "<?php\n\tValidation::comparison(\$value1, 'lessorequal', \$value2);\n?>";
		$this->msg = "<b>Validation error</b>: Unknow <b>$operator</b> in comparison()";
		$this->sug = "You must define the operator parameter for <b>Validation::comparison()</b>";
		self::render();
	}

/**
 * Validation Custom: No regex expression
 */
	function validationCustom($regex) {
		$this->code = "<?php\n\tValidation::custom(\$value, '/^[a-zA-Z0-9_]*(1|2|3)$/i');\n?>";
		$this->msg = "<b>Validation error</b>: Unknow <b>$regex</b> in custom()";
		$this->sug = "You must define a regular expression for <b>Validation::custom()<b>";
		self::render();
	}

/**
 * No Unhandled Validation class
 */
	function validationClass($className) {
		$this->code = "<?php\nclass $className {\n\n\tfunction __construct(){\n\t}\n}\n?>";
		$this->msg = "<b>Missing validation class</b>: $className";
		$this->sug = "Create the class <b>$className</b> in <b>".CORE."validation.php</b>";
		self::render();
	}

/**
 * No Unhandled Validation method
 */
	function validationClass($method,$classname) {
		$this->code = "<?php\nclass $classname {\n\n\tfunction $method() {\n\t}\n}\n?>";
		$this->msg = "<b>Missing validation method</b>: $method";
		$this->sug = "Create <b>$classname::$method()</b> in <b>".CORE."validation.php</b>";
		self::render();
	}

}

?>
