<?php
	if (file_exists("../install/install.lock") == false){
		$string = file_get_contents("../error/index.html");
		echo $string;
		exit();
	} 
	define('IN_CRONLITE', true);
	include '../includes/class.php';
	include '../includes/db_class.php';
	include '../includes/class_db.php';
	$db_first = DB_PRE;
	$ADMIN_COOKIE = isset($_COOKIE['ADMIN_COOKIE_DL']) ? purge($_COOKIE['ADMIN_COOKIE_DL']) : '';
	$DB = new DB(DB_HOST,DB_USER,DB_PASSWD,DB_NAME,DB_PORT);
	$config_db = $DB->query("select * from `{$db_first}config` where `code_key`='cookie'");
	$config = $DB->fetch($config_db);
	if($ADMIN_COOKIE == $config['code']){
		$islogin = true;
	}else{
		$islogin = false;
	}
	if($config['code']==null || $ADMIN_COOKIE==null)$islogin = false;
	if (!$islogin) {
		header('Location:./login.php?err=3');//过期cookie或未登录尝试进入后台
		exit;
	}else{//已经登录，得到config
		$config_db_1 = $DB->query("select * from `{$db_first}config` where `code_key`='title'");
		$config_1 = $DB->fetch($config_db_1);
		$config_sz['title'] = $config_1['code'];
		
		$config_db_1 = $DB->query("select * from `{$db_first}config` where `code_key`='title1'");
		$config_1 = $DB->fetch($config_db_1);
		$config_sz['title1'] = $config_1['code'];
		
		$config_db_1 = $DB->query("select * from `{$db_first}config` where `code_key`='title2'");
		$config_1 = $DB->fetch($config_db_1);
		$config_sz['title2'] = $config_1['code'];
		
		$config_db_1 = $DB->query("select * from `{$db_first}config` where `code_key`='time'");
		$config_1 = $DB->fetch($config_db_1);
		$config_sz['time'] = $config_1['code'];
		
		$config_db_1 = $DB->query("select * from `{$db_first}config` where `code_key`='template'");
		$config_1 = $DB->fetch($config_db_1);
		$config_sz['template'] = $config_1['code'];
	}
	
	
	// function php_self(){
	//     $php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
	//     return $php_self;
	// }

?>
