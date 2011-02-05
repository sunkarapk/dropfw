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

class Model extends Object {

/**
 * Constructor.
 */
	function __construct() {
		$this->table = Inflector::tableize(get_class($this));
	}

/**
 * Method to invoke dynamic methods
 */
	function __call($method, $params) {
		if(substr($method,0,6)=='findBy') {
			$this->column = Inflector::underscore(substr_replace($method, '', 0, 6));
			$this->limit = "LIMIT 1";
		} else if(substr($method,0,9)=='findAllBy') {
			$this->column = Inflector::underscore(substr_replace($method, '', 0, 9));
			$this->limit = "";
		}
		array_unshift($params, $this->table);
		return Database::obj(Database::query("SELECT * FROM @ WHERE ".$this->column."=# ".$this->limit,$params,true));
	}

}

?>
