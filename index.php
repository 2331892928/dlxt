<?php
	include 'includes/global.php';
	$_GET     && SafeFilter($_GET);
	$_POST    && SafeFilter($_POST);
	$_COOKIE  && SafeFilter($_COOKIE);
	$template = isset($_GET['c']) ? purge($_GET['c']) : null;
	template($template);
?>
