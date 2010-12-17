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

	Configure::write('App.Name', 'My dropFW App');

/**
 * Configuration about debugging
 * 
 * 0 - No debug at all
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
 * Configuration for various libraries
 */	
	Configure::write('lib.acl', true);
	
	Configure::write('lib.cookie', true);

	Configure::write('lib.captcha', true);

	Configure::write('lib.email', true);
	
	Configure::write('lib.file', true);

	Configure::write('lib.json', true);

	Configure::write('lib.html', true);

	Configure::write('lib.logger', true);

	Configure::write('lib.pay', true);
	
	Configure::write('lib.rss', true);
	
	Configure::write('lib.session', true);

	Configure::write('lib.xml', true);

/**
 * A random string used in security hashing methods.
 */
	Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVoUuiR2G0FgaC9mi');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
	Configure::write('Security.cipherSeed', '7685930965745382496749683645');

?>
