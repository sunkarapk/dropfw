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

	$configure->write('app.Name', 'dropFW Docs');

/**
 * Configuration about debugging
 * 
 * 0 - No debug at all
 * 1 - Only PHP errors and warnings
 * 2 - Print SQL queries along with PHP errors and warnings
 */
	$configure->write('debug', 2);

/**
 * Configurations about including library files and Rewrite support
 * Don't remove them.
 * You can only edit their boolean values
 */

/**
 * Configuration for rewrite support
 */
	$configure->write('rewrite', $redirection);

/**
 * Base script for all URL's
 * generally index.php
 * If changed here, It needs to be changed in .htaccess too
 */
	$configure->write('app.base', 'index.php');

/**
 * Configuration for various libraries
 */	
	$configure->write('lib.acl', true);
	
	$configure->write('lib.cookie', true);

	$configure->write('lib.captcha', true);

	$configure->write('lib.email', true);
	
	$configure->write('lib.file', true);

	$configure->write('lib.json', true);

	$configure->write('lib.html', true);

	$configure->write('lib.logger', true);

	$configure->write('lib.pay', true);
	
	$configure->write('lib.rss', true);
	
	$configure->write('lib.session', true);

	$configure->write('lib.xml', true);

/**
 * A random string used in security hashing methods.
 */
	$configure->write('security.salt', 'DYhG93b0qyJfIxfs2guVoUuiR2G0FgaC9mi');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
	$configure->write('security.cipherSeed', '7685930965745382496749683645');

/**
 * Configuration for internationalizationa and localization
 */
	$configure->write('config.language','eng');

?>
