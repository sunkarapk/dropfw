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
 * Constructor.
 */
	function __construct() {}

/**
 * Render error page
 */
	function render($msg,$sug,$code) {
		if($code===null)
			echo View::render(VIEW.'error.ctp',array('msg'=>$msg,'sug'=>$sug,'code'=>null));
		else
			echo View::render(VIEWS.'error.ctp',array('msg'=>$msg,'sug'=>$sug,'code'=>Sanitize::hsc($code)));
		die();
	}

}

?>
