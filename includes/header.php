<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	    <meta charset='UTF-8' />
		<link rel='stylesheet' href='css/style.css' />
        <title>izCMS <?php echo(isset($title)) ? $title : "Home page" ;?></title>
</head>
<body>
	<div id="container">
	<div id="header">
		<h1><a href="index.php">izCMS</a></h1>
        <p class="slogan">The iz Content Management System</p>
	</div>
	<div id="navigation">
		<ul>
	        <li><a href='index.php'>Home</a></li>
			<li><a href='#'>About</a></li>
			<li><a href='#'>Services</a></li>
			<li><a href='contact.php'>Contact us</a></li>
		</ul>
	</div><!-- end navigation-->