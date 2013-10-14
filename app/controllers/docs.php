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

class DocsController extends Controller 
{

	var $helpers = array('Html');

	function beforeFilter() {
		$this->pageTitle = Configure::read('App.Name');
	}

	function home() { }

	function contents() { }

}

?>
