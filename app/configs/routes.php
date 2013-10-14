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

/**
 * Here, we are connecting '/' (base path) to controller called 'docs' and action 'home'
 * (in this case, /app/views/docs/home.ctp)...
 */
	Router::connect('/', array('controller' => 'docs', 'action' => 'home'));

?>
