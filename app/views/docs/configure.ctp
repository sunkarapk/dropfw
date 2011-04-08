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
	<h1>Configuration Class</h1>
	<div class='doc'>
			<p>
				&nbsp;&nbsp;&nbsp;<?php print $html->link('↑Contents',array('action'=>'contents')); ?>
			</p>
			
			<h3>Introduction</h3>
			<p>
				<ul>
					<li><b>File:</b> <i>drop/configure.php</i></li>
					<li><b>Type:</b> <i>Non-Configurable Inclusion</i></li>
					<li><b>Usage:</b> <i>Configure:: (Static)</i></li>
				</ul>
			</p>
		
			<h3>Contents</h3>
			<p>
				<ul>
					<li><?php print $html->link('Write', '#write'); ?></li>
					<li><?php print $html->link('Read', '#read'); ?></li>
					<li><?php print $html->link('Check', '#check'); ?></li>
					<li><?php print $html->link('Delete', '#delete'); ?></li>
				</ul>
			</p>
		
			<h3>Construction</h3>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;Done by parent class <?php print $html->link('Object', array('action'=>'object')); ?> (Default)</p>
		
			<h3>Variables</h3>
			<p>
				<ul>
					<li><b>$info</b> <i>Main variable where configuration information is stored</i></li>
				</ul>
			</p>
			
			<h3>Functions</h3>
			<p>
				<ol>
					<li>
						<h4>
							<?php print $html->bookmark('write'); ?>
							<i>void</i> write ( <i>string</i>, <i>mixed</i> )
						</h4>
						<?php echo "<pre>\$options = array('Home', 'Profile', 'Settings', 'exit'=>'Logout');\nConfigure::write('App.Dashboard.Options', \$options);</pre>"; ?>
						The whole array will be stored in <b>$info</b>['App']['Dashboard']['Options']
						<pre>$info['App']['Dashboard']['Options']['exit'] == 'Logout' is true</pre>
					</li>
					<li>
						<h4>
							<?php print $html->bookmark('read'); ?>
							<i>mixed</i> read ( <i>string</i> )
						</h4>
						<?php echo "<pre>\$name = Configure::read('App.Name');\nprint \$name;</pre>"; ?>
						<b>$info</b>['App']['Name'] will be printed
					</li>
					<li>
						<h4>
							<?php print $html->bookmark('check'); ?>
							<i>bool</i> check ( <i>string</i> )
						</h4>
						<pre>$flag = Configure::check('App.Name');</pre>
						Returns true if <b>$info</b>['App']['Name'] exists
					</li>
					<li>
						<h4>
							<?php print $html->bookmark('delete'); ?>
							<i>void</i> delete ( <i>string</i> )
						</h4>
						<pre>Configure::delete('App');</pre>
						<b>$info</b>['App'] will be deleted
					</li>
				</ol>
			</p>
	</div>
	<br>
