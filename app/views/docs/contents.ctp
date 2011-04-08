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
?>
	<h1>Table of Contents</h1>
	<div>
		<?php
			for ($i=0; !empty($list[$i]); $i++) {
				$list[$i] = $html->link($list[$i], array('action'=>$link[$i]));
			}
			print $html->nestedList($list, $tag='ol');
		?>
	</div>
