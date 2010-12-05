<?php
/**
 * sunFW(tm) :  PHP Web Development Framework (http://www.suncoding.com)
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

	define('DS', DIRECTORY_SEPARATOR);
	define('HOST', 'http://'.$_SERVER['HTTP_HOST']);
	define('ROOT', dirname(__FILE__).DS);
	define('CORE', ROOT.'sun'.DS);
	define('APP', ROOT.'app'.DS);
	define('WWW', APP.'www'.DS);
	
/**
 * Detects the correct URL excluding the base directory
 * Works without the consideration of  rewrite engine
 * @author	Pavan Kumar Sunkara
 */	
	if(array_key_exists('REDIRECT_STATUS',$_SERVER)) {
		if((int) $_SERVER['REDIRECT_STATUS'] == 200) {
			define('URL', $_SERVER['REDIRECT_QUERY_STRING']);
			$subdirUrl = explode("/",$_SERVER['SCRIPT_NAME']);
			array_pop($subdirUrl);
			define('WEBROOT', implode("/",$subdirUrl));
			$redirection = true;
		}
	} else {
		define('URL', $_SERVER['PATH_INFO']);
		define('WEBROOT',$_SERVER['SCRIPT_NAME']);
		$redirection = false;
	}

	define('BASE', HOST.WEBROOT);

/**
 * No need to go to dispatch if it is css or js or img
 */
	$url = explode("/",URL);
	array_shift($url);
	if($url[0] == "css" || $url[0] == "js" || $url[0] == "img")
		require_once WWW.implode("/",$url);
	else {
		/**
		 * Include boot.php which loads all the files
		 */
			require_once CORE.'boot.php';
	
		/**
		 * Include index.php which does the main dispatching and outputing
		 */	
			require_once WWW.'index.php';
	}

?>
