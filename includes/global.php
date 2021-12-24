<?php
	if (file_exists("install/install.lock") == false){
		$string = file_get_contents("error/index.html");
		echo $string;
		exit();
	} 
    define('IN_CRONLITE', true);
    include 'class.php';
    include 'db_class.php';
    include 'class_db.php';
	
    function template($mode = null)
    {	
		$db_first = DB_PRE;
		require 'config_db.php';
		$DB = new DB(DB_HOST,DB_USER,DB_PASSWD,DB_NAME,DB_PORT);
		//查config表
		$default = "default";
		//数据库查找当前设置的目录，覆盖$default
		
		$config_db = $DB->query("select * from `{$db_first}config` where `code_key`='template'");
		$config = $DB->fetch($config_db);
		$default = isset($config['code']) ? $config['code'] : "default";
		
		$string = file_get_contents("error/600.html");
		$template_1 = getDirContent("template/",1);
		if (count($template_1) == 0){//无模板
			echo $string;
			exit();
		}

		$mod = in_array($default,$template_1,false);
		if($mod == false){
			echo $string;
			exit();
		}

		if($mode == null){
			//首页
			$config_db = $DB->query("select * from `{$db_first}config` where `code_key`='title'");
			$config = $DB->fetch($config_db);
			$index_title = isset($config['code']) ? $config['code'] : "湮灭短链";
			
			$config_db = $DB->query("select * from `{$db_first}config` where `code_key`='title1'");
			$config = $DB->fetch($config_db);
			$index_title1 = isset($config['code']) ? $config['code'] : "湮灭短链";
			
			$config_db = $DB->query("select * from `{$db_first}config` where `code_key`='title2'");
			$config = $DB->fetch($config_db);
			$index_title2 = isset($config['code']) ? $config['code'] : "湮灭短链";
			
			require 'template/'.$default.'/index.php';
			exit();
		}
		//跳转
		$cl_2 = $DB->query("select * from `{$db_first}data` where `code`='{$mode}'");
		$cl_2_n = $DB->fetch($cl_2);
		$url = $cl_2_n['cl'];
		$title = isset($cl_2_n['title']) ?  $cl_2_n['title'] : "湮灭短链";
		$title1 = isset($cl_2_n['title1']) ? $cl_2_n['title1'] : "湮灭短链";
		$title2 = isset($cl_2_n['title2']) ? $cl_2_n['title2'] : "湮灭短链";
		
		$config_db = $DB->query("select * from `{$db_first}config` where `code_key`='time'");
		$config = $DB->fetch($config_db);
		$time = isset($config['code']) ? (int)$config['code'] : 0;

		if($cl_2_n){
			require 'template/'.$default.'/go.php';
			exit();
		}else{
			$string = file_get_contents("error/600.html");
			echo $string;
			exit();
		}
    }
?>