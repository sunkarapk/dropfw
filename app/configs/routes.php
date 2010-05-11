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

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages' and action 'home'
 * (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'ps', 'action' => 'home'));

?>
