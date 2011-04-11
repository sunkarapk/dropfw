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
 * Holds the models with which the current model has
 * One-One relationship
 *
 * @var array Tables
 * @access public
 */
	var $oneToOne = array();

/**
 * Holds the models with which the current model has
 * One-Many relationship
 *
 * @var array Tables
 * @access public
 */	
	var $oneToMany = array();

/**
 * Holds the models with which the current model has
 * Many-One relationship
 *
 * @var array Tables
 * @access public
 */	
	var $manyToOne = array();

/**
 * Holds the models with which the current model has
 * Many-Many relationship
 *
 * @var array Tables
 * @access public
 */	
	var $manyToMany = array();

/**
 * Id of the data record that is being held currently
 *
 * @var integer ID
 * @access public
 */
	var $id = null;

/**
 * Schema of the Model's table
 *
 * @var array table fields
 * @access public
 */
	var $schema = null;

/**
 * Data retreived or created
 *
 * @var array record
 * @access public
 */
	var $data = array();

/**
 * To know whethere this model is the parent or not
 *
 * @var boolean true or false
 * @access public
 */
	var $isParent = null;

/**
 * Constructor.
 */
	function __construct($parent=true) {
		$this->isParent = $parent;
		$this->table = Inflector::tableize(get_class($this));
		$this->schema = Database::describeTable($this->table);
		$this->buildModelClasses();
	}

/**
 * Method to include required model file classes
 */
	function loadModelClass($modelClass) {
		$umodel = Inflector::underscore($modelClass);
		if(file_exists(MODELS.$umodel.'.php'))
			require_once MODELS.$umodel.'.php';
		if (!class_exists($modelClass))
			Error::missingModel($modelClass);
	}

/**
 * Method to build model classes
 */
	function buildModelClasses() {
		for($i=0; isset($this->oneToOne[$i]); $i++) {
			$this->loadModelClass($this->oneToOne[$i]);
			eval('$this->'.$this->oneToOne[$i].' = new '.$this->oneToOne[$i].'(false);');
		}
		for($i=0; isset($this->oneToMany[$i]); $i++) {
			$this->loadModelClass($this->oneToMany[$i]);
			eval('$this->'.$this->oneToMany[$i].' = new '.$this->oneToMany[$i].'(false);');
		}
		for($i=0; isset($this->manyToOne[$i]); $i++) {
			$this->loadModelClass($this->manyToOne[$i]);
			eval('$this->'.$this->manyToOne[$i].' = new '.$this->manyToOne[$i].'(false);');
		}
		for($i=0; isset($this->manyToMany[$i]); $i++) {
			$this->loadModelClass($this->manyToMany[$i]);
			eval('$this->'.$this->manyToMany[$i].' = new '.$this->manyToMany[$i].'(false);');
		}
	}

/**
 * Method to invoke dynamic methods
 */
	function __call($method, $params) {
		if(substr($method,0,6)=='findBy') {
			$this->column = Inflector::underscore(substr_replace($method, '', 0, 6));
			return $this->retreive($params);
		} else if(substr($method,0,9)=='findAllBy') {
			$this->column = Inflector::underscore(substr_replace($method, '', 0, 9));
			return $this->retreive($params);
		}
	}

/**
 * Method to implement every retreiving query
 */
	function retreive($params) {
		array_unshift($params, $this->table);
		$this->data = Database::row(Database::query('SELECT * FROM @ WHERE '.$this->column.'=#', $params, true));
		if($this->isParent) {
			$this->data = $this->data[0];
			$this->id = $this->data['id'];
			for($i=0; isset($this->oneToOne[$i]); $i++) {
				if(Set::secondDimSearch($this->schema, Inflector::underscore($this->oneToOne[$i]).'_id')!=-1)
					$this->{$this->oneToOne[$i]}->findAllById($this->data[Inflector::underscore($this->oneToOne[$i]).'_id']);
				else
					$this->{$this->oneToOne[$i]}->{'findAllBy'.get_class($this).'Id'}($this->data['id']);
				$this->data[$this->oneToOne[$i]] = $this->{$this->oneToOne[$i]}->data[0];
			}
			for($i=0; isset($this->oneToMany[$i]); $i++) {
				$this->{$this->oneToMany[$i]}->{'findAllBy'.get_class($this).'Id'}($this->data['id']);
				$this->data[$this->oneToMany[$i]] = $this->{$this->oneToMany[$i]}->data;
			}
			for($i=0; isset($this->manyToOne[$i]); $i++) {
				$this->{$this->manyToOne[$i]}->findAllById($this->data[Inflector::underscore($this->manyToOne[$i]).'_id']);
				$this->data[$this->manyToOne[$i]] = $this->{$this->manyToOne[$i]}->data[0];
			}
			for($i=0; isset($this->manyToMany[$i]); $i++) {
				$mtmTable = Inflector::underscore(get_class($this).'And'.$this->manyToMany[$i]);
				$mtmData = Database::row(Database::query('SELECT '.Inflector::underscore($this->manyToMany[$i]).'_id FROM @ WHERE '.Inflector::underscore(get_class($this)).'_id=#', array($mtmTable, $this->id), true));
				$mtmArr = array();
				for($j=0; isset($mtmData[$j]); $j++) {
					$this->{$this->manyToMany[$i]}->findAllById($mtmData[$j][0]);
					array_push($mtmArr, $this->{$this->manyToMany[$i]}->data[0]);
				}
				$this->data[$this->manyToMany[$i]] = $mtmArr;
			}
		}
		return $this->data;
	}

}

?>
