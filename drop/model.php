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
 * Method to call basic query
 */
	function query($sql) {
		Database::query($sql, array());
		return Database::row();
	}

/**
 * Main method to retreive data
 */
	function find($type = 'first', $params = array()) {
		if(empty($params['fields'])) {
			$params['fields']=array();
		}
		if(empty($params['limit'])) {
			$params['limit']=array();
		}
		if(empty($params['order'])) {
			$params['order']=array();
		}
		if(empty($params['group'])) {
			$params['group']=array();
		}
		if(empty($params['conditions'])) {
			$params['conditions']=array();
		}
		switch ($type) {
			case 'count':
				$params['fields'] = 'count(*)';
				break;
			case 'first':
				$params['limit'] = array(1);
			case 'all':
			default:
				break;
		}
		return $this->retreive($params);
	}

/**
 * Method to insert a new entry
 */
	function create() {
		Database::query('INSERT INTO @ VALUES ()', array($this->table));
		$this->id = Database::getId();
	}

/**
 * Method to save data
 */
	function save() {
		$params = array();
		$str = array();
		for($i=0; $i<count($this->schema); $i++) {
			if($this->schema[$i]['Field']!='id') {
				array_push($params, $this->data[$this->schema[$i]['Field']]);
				array_push($str, $this->schema[$i]['Field'].'=#');
			}
		}
		array_unshift($params, $this->table);
		Database::query('UPDATE @ SET '.implode(',', $str).' WHERE id=#', $params);
	}

/**
 * Method to delete entry
 */
	function deleteById($id) {
		$this->delete(array('id='=>$id));
	}

/**
 * Method to delte entry with given conditions
 */
	function delete($params) {
		$sqlParams = array();
		if(empty($params)) {
			$condCond = '';
		} else {
			$condCond = 'WHERE ';
			$condCondArr = array();
			foreach($params as $key=>$value) {
				array_push($condCondArr,$key.'#');
				array_push($sqlParams,$value);
			}
			$condCond.=implode(' AND ', $condCondArr);
		}
		array_unshift($sqlParams, $this->table);
		Database::query('DELETE FROM @ '.$condCond, $sqlParams);
	}

/**
 * Method to invoke dynamic methods
 */
	function __call($method, $params) {
		if(substr($method,0,6)=='findBy') {
			$column = Inflector::underscore(substr_replace($method, '', 0, 6)).'=';
			return $this->find('first', array('conditions'=>array($column => $params[0])));
		} else if(substr($method,0,9)=='findAllBy') {
			$column = Inflector::underscore(substr_replace($method, '', 0, 9)).'=';
			return $this->find('all', array('conditions'=>array($column => $params[0])));
		}
	}

/**
 * Method to implement every retreiving query
 */
	function retreive($params) {
		if(empty($params['limit'])) {
			$limitCond = '';
		} elseif(count($params['limit'])==1) {
			$limitCond = ' LIMIT '.$params['limit'][0];
		} else {
			$limitCond = ' LIMIT '.$params['limit'][0].','.$params['limit'][1];
		}
		if(empty($params['group'])) {
			$groupCond = '';
		} else {
			$groupCond = ' GROUP BY '.$params['group'];
		}
		if(empty($params['order'])) {
			$orderCond = '';
		} else {
			$orderCond = ' ORDER BY '.$params['order'];
		}
		if(empty($params['fields'])) {
			$fieldsCond = '*';
		} else {
			$fieldsCond = implode(',', $params['fields']);
		}
		$sqlParams = array();
		if(empty($params['conditions'])) {
			$condCond = '';
		} else {
			$condCond = 'WHERE ';
			$condCondArr = array();
			foreach($params['conditions'] as $key=>$value) {
				array_push($condCondArr,$key.'#');
				array_push($sqlParams,$value);
			}
			$condCond.=implode(' AND ', $condCondArr);
		}
		array_unshift($sqlParams, $this->table);
		$sqlStatement = 'SELECT '.$fieldsCond.' FROM @ '.$condCond.$orderCond.$groupCond.$limitCond;
		$this->data = Database::row(Database::query($sqlStatement, $sqlParams, true));
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
					$mtmDataJ = $mtmData[$j][Inflector::underscore($this->manyToMany[$i]).'_id'];
					$this->{$this->manyToMany[$i]}->findAllById($mtmDataJ);
					$mtmBuf = Database::row(Database::query('SELECT '.Inflector::underscore(get_class($this)).'_id FROM @ WHERE '.Inflector::underscore($this->manyToMany[$i]).'_id=# and  '.get_class($this).'_id!=#', array($mtmTable, $mtmDataJ, $this->id), true));
					$mtmArrJ = array($this->{$this->manyToMany[$i]}->data[0], get_class($this)=>$mtmBuf);
					array_push($mtmArr, $mtmArrJ);
				}
				$this->data[$this->manyToMany[$i]] = $mtmArr;
			}
		}
		return $this->data;
	}

}

?>
