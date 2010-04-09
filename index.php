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

	define('DS', DIRECTORY_SEPARATOR);
	define('HOST', 'http://'.$_SERVER['HTTP_HOST'].DS);
	define('ROOT', dirname(__FILE__).DS);
	define('CORE', ROOT.'sun'.DS);
	define('APP', ROOT.'app'.DS);
	define('WWW', APP.'www'.DS);
	define('URL', $_GET['url']);

	require_once CORE.'boot.php';
	
?>
