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

	Configure::write('App.Name', 'My sunFW App');

/**
 * Configuration about debugging
 * 
 * 0 -  No debug at all
 * 1 - Only PHP errors and warnings
 * 2 - Print SQL queries along with PHP errors and warnings
 */
	Configure::write('debug', 2);

/**
 * Configurations about including library files and Rewrite support
 * Don't remove them.
 * You can only edit their boolean values
 */

/**
 * Configuration for rewrite support
 */
	Configure::write('rewrite',$redirection);

/**
 * Base script for all URL's
 * generally index.php
 * If changed here, It needs to be changed in .htaccess too
 */
	Configure::write('App.base', 'index.php');

/**
 * Configuration for Access control lists
 * Usage: "$ACL->"
 */	
	Configure::write('lib.acl', true);
	
/**
 * Configuration for using cookie variables
 * Usage: "Cookie::"
 */
	Configure::write('lib.cookie', true);

/**
 * Configuration for using captcha
 * Usage: "$Captcha->"
 */
	Configure::write('lib.captcha', true);

/**
 * Configuration for sending emails in PHP
 * Usage: "Email::"
 */
	Configure::write('lib.email', true);
	
/**
 * Configuration for file handling
 * Usage: "$File->"
 */
	Configure::write('lib.file', true);

/**
 * Configuration for manipulating json
 * Usage: "$JSON->"
 */	
	Configure::write('lib.json', true);

/**
 * Configuration for logging objects
 * Usage: "$Logger->"
 */	
	Configure::write('lib.logger', true);

/**
 * Configuration for using payment modules
 * Usage: "$Pay->"
 */	
	Configure::write('lib.pay', true);
	
/**
 * Configuration for using RSS feed
 * Usage: "RSS::"
 */
	Configure::write('lib.rss', true);
	
/**
 * Configuration for using session variables
 * Usage: "Session::"
 */
	Configure::write('lib.session', true);

/**
 * Configuration for using XML
 * Usage: "$XML->"
 */
	Configure::write('lib.xml', true);

?>
