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

class DocsController extends Controller 
{

	var $helpers = array('Html');
	
	var $uses = array('User');

	function beforeFilter() {
		$this->pageTitle = Configure::read('App.Name');
	}

	function home() { }

	function contents() { }

}

?>
