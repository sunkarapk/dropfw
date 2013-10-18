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
?>
<?php echo $html->doctype(); ?>
<html>
<head>
	<?php
		echo $html->charset();
		echo $html->title($title);
		echo $html->meta('icon');
		echo $html->css('drop');
		echo $html->script('jquery');
		#echo $html->scriptBlock('$(document).ready(function(){alert("Welcome")});');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<table><tr>
				<td width=100px><?php echo $html->image('drop.png',array('width'=>'75px','height'=>'75px')); ?></td>
				<td width=800px>
					<h1><?php echo $html->link('dropFW', 'http://suncoding.com'); ?></h1>
					<span>PHP Web Development Framework v1.0.0</span>
				</td>
			</tr></table>
		</div>
		<div id="content">

			<?php #$session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
			<table><tr>
				<td style='text-align:left;width:450px;'>www.suncoding.com</td>
				<td style='text-align:right;width:450px;'>Copyright&#64;2010 Pavan Kumar Sunkra</td>
			</tr></table>
		</div>
	</div>
</body>
</html>
