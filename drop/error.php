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
	public static $code = null;
/**
 * Variable for message
 */
	public static $msg = null;
/**
 * Variable for suggestion
 */
	public static $sug = null;
/**
 * Constructor.
 */
	function __construct() {}

/**
 * Render error page
 */
	function render() {
		if(self::$code===null)
			echo View::render(VIEW.'error.ctp',array('msg'=>self::$msg,'sug'=>self::$sug,'code'=>null));
		else
			echo View::render(VIEWS.'error.ctp',array('msg'=>self::$msg,'sug'=>self::$sug,'code'=>Sanitize::hsc(self::$code)));
		die();
	}

/**
 * Missing model
 */
	function missingModel($modelClass) {
		$model = Inflector::underscore($modelClass);
		self::$msg = "<b>Missing Model</b>: $modelClass";
		self::$sug = "Create the class <b>$modelClass</b> in <b>".MODELS.$model.".php</b>";
		self::$code = "<?php\nclass $modelClass extends Model {\n\n\tvar \$uses = array();\n}\n?>";
		self::render();
	}

/**
 * Missing helper
 */
	function missingHelper($helperClass) {
		$helper = Inflector::underscore($helperClass);
		self::$msg = "<b>Missing Library</b>: $helperClass";
		self::$code = "<?php\nclass $helperClass extends Object {\n\n\tfunction __construct() {\n\t}\n}\n?>";
		self::$sug = "Create the class <b>$helperClass</b> in <b>".CORE.$helper.".php</b>";
		self::render();
	}

/**
 * Missing controller
 */
	function missingController($controller) {
		$classname = Inflector::camelize($controller.'_controller');
		self::$code = "<?php\nclass $classname extends Controller {\n\n\tvar \$helpers = array('Html');\n}\n?>";
		self::$msg = "<b>Missing Controller</b>: $classname";
		self::$sug = "Create the class <b>$classname</b> in <b>".CONTROLLERS.$controller.".php</b>";
		self::render();
	}

/**
 * Missing Action
 */
	function missingAction($action,$controller) {
		$classname = Inflector::camelize($controller.'_controller');
		self::$code = "<?php\nclass $classname extends Controller {\n\n\tvar \$helpers = array('Html');\n\n\tfunction $action() {\n\t}\n}\n?>";
		self::$msg = "<b>Missing Action</b>: $action";
		self::$sug = "Create <b>$classname::$action()</b> in <b>".CONTROLLERS.$controller.".php</b>";
		self::render();
	}

/**
 * Missing view
 */
	function missingView($file) {
		self::$code = "<div>\n\t<!-- $file -->\n</div>";
		self::$msg = "<b>Missing View</b>: $file";
		self::$sug = "Create view in <b>$file</b>";
		self::render();
	}

/**
 * JSON error
 */
	function json($data) {
		self::$code = print_r($data,true);
		self::$msg = "<b>JSON error</b>: ".gettype($data);
		self::$sug = "<b>".gettype($data)."</b> cannot be encoded to JSON string";
		self::render();
	}

/**
 * Empty cipher key
 */
	function emptyCipherKey() {
			self::$code = "<?php\n\tSecurity::cipher(\$text,'9nHPrYcxmvTliA');\n?>";
			self::$msg = "<b>Encyption error</b>: Empty key";
			self::$sug = "You cannot use an empty key for <b>Security::cipher()</b>";
			self::render();
	}

/**
 * Validation Comparison: No operator
 */
	function validationComparison($opertor) {
		self::$code = "<?php\n\tValidation::comparison(\$value1, 'lessorequal', \$value2);\n?>";
		self::$msg = "<b>Validation error</b>: Unknow <b>$operator</b> in comparison()";
		self::$sug = "You must define the operator parameter for <b>Validation::comparison()</b>";
		self::render();
	}

/**
 * Validation Custom: No regex expression
 */
	function validationCustom($regex) {
		self::$code = "<?php\n\tValidation::custom(\$value, '/^[a-zA-Z0-9_]*(1|2|3)$/i');\n?>";
		self::$msg = "<b>Validation error</b>: Unknow <b>$regex</b> in custom()";
		self::$sug = "You must define a regular expression for <b>Validation::custom()<b>";
		self::render();
	}

/**
 * No Unhandled Validation class
 */
	function validationClass($className) {
		self::$code = "<?php\nclass $className {\n\n\tfunction __construct(){\n\t}\n}\n?>";
		self::$msg = "<b>Missing validation class</b>: $className";
		self::$sug = "Create the class <b>$className</b> in <b>".CORE."validation.php</b>";
		self::render();
	}

/**
 * No Unhandled Validation method
 */
	function validationMethod($method,$classname) {
		self::$code = "<?php\nclass $classname {\n\n\tfunction $method() {\n\t}\n}\n?>";
		self::$msg = "<b>Missing validation method</b>: $method";
		self::$sug = "Create <b>$classname::$method()</b> in <b>".CORE."validation.php</b>";
		self::render();
	}

/**
 * No DB connection
 */
	function databaseConnection($server,$user,$pass) {
		self::$code = "<?php\n\tdefine('DB_HOST', \$server)\n\tdefine('DB_USER', \$user)\n\tdefine('DB_PASS', \$pass)\n?>";
		self::$msg = "<b>No MySQL connection</b>: $user@$server -p $pass";
		self::$sug = "Configure database variables in <b>".CONFIGS."database.php</b>";
		self::render();
	}

/**
 * No database with $name
 */
	function databaseDb($name) {
		self::$code = "<?php\n\tdefine('DB_NAME', \$dbName)\n?>";
		self::$msg = "<b>No database</b>: $name";
		self::$sug = "Configure database name in <b>".CONFIGS."database.php</b>";
		self::render();
	}

/**
 * Count mismatch of params for query
 */
	function databaseAPI($query,$count) {
		self::$code = "<?php\n\tDatabase::query(\$sqlStatement,\$params);\n?>";
		self::$msg = "<b>Params count mismatch</b>: $count";
		self::$sug = "Change <b>$query</b>";
		self::render();
	}

/**
 * Database SQL error
 */
	function databaseSQL($err,$sql) {
		self::$code = "<?php\n\tDatabase::query(\$sqlStatement,\$params);\n?>";
		self::$msg = "<b>MySQL error</b>: $err";
		self::$sug = "Check <b>$sql</b>";
		self::render();
	}

}

?>
