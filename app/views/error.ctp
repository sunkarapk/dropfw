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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
	<title>Error</title>
	<link rel='shortcut icon' type='image/x-icon' href="<?php echo WEBIMG.'favicon.ico'; ?>">
	<link rel='stylesheet' type='text/css' href="<?php echo WEBCSS.'drop.css'; ?>">
</head>
<body>
	<div id="container">
		<div id="header">
			<table><tr>
				<td width=100px><img width='75px' height='75px' src="<?php echo WEBIMG.'drop.png'; ?>"></td>
				<td width=800px>
					<h1><a href='http://suncoding.com'>dropFW</a></h1>
					<span>PHP Web Development Framework v1.0.0</span>
				</td>
			</tr></table>
		</div>
		<div id="content">
			<p class='error'><?php echo $msg; ?></p>
			<p class='notice'><?php echo $sug; ?></p>
			<?php
				if($code!==null)
					echo '<pre>'.$code.'</pre>';
			?>
		</div>
		<div id="footer">
			<table><tr>
				<td style='text-align:left;width:450px;'>www.suncoding.com</td>
				<td style='text-align:right;width:450px;'>Copyright&#64;2010 Sun Web Dev, Inc.</td>
			</tr></table>
		</div>
	</div>
</body>
</html>
