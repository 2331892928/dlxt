<?php
	// include_once '../includes/class.php';
	if (file_exists("../install/install.lock") == false){
		$string = file_get_contents("error/index.html");
		echo $string;
		exit();
	}
	// include_once '../includes/global.php';
	define('IN_CRONLITE', true);
	include '../includes/class.php';
	include '../includes/db_class.php';
	include '../includes/class_db.php';
	$db_first = DB_PRE;
	$_GET     && SafeFilter($_GET);
	$_POST    && SafeFilter($_POST);
	$_COOKIE  && SafeFilter($_COOKIE);
	
	$act = isset($_GET['act']) ? purge($_GET['act']) : json(201,"类型为空");
	$cl = isset($_GET['cl']) ? $_GET['cl'] : null;
	if ($cl == null)json(201,"链接为空");
	if($cl!=purge($_GET['cl']))json(201,"链接有异常字符");
	$cl = purge($_GET['cl']);
	$hots = $_SERVER['SERVER_NAME'];
	$zz = '/^(((ht|f)tps?):\/\/)?[\w-]+(\.[\w-]+)+(:\d{1,5})?\/?(\S)*([\W-]*[\w-]+\=[\w-]+)*$/';
	if(preg_match($zz, $cl) == false)json(201,"链接错误");

	if(strpos($cl, "http://") === false && strpos($cl, "https://") === false)$cl = "http://".$cl;
	// $cl = UnicodeEncode($cl);
	if($act == "scdl"){
		$array_1=explode('/', $cl);
		$hots_cl_1 = $array_1['2'];
		if($hots_cl_1 == $hots)json(201,"不能使用本站进行生成");
		$dlcode = make_password(6);
		while (true) {
			$dl = $DB->query("select * from `{$db_first}data` where `code`='{$dlcode}'");
			$dl_nums = $dl->num_rows;//count方法失效
			if ($dl_nums==0)break;
			$dlcode = make_password(6);
		}
		//查看是否存在在数据库
		$cl_1 = $DB->query("select * from `{$db_first}data` where `cl`='{$cl}'");
		$cl_1_nums = $cl_1->num_rows;//count方法失效

		if($cl_1_nums!=0)json(201,"已存在此链接，无法生成，请点击查询按钮查询短链");
		// 生成成功
		//写入数据库
		//可能是虚拟主机，不仅仅是域名，还有路径执行
		$lj = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"/ajax"));
		$dl_hots= "http://".$hots."$lj"."/?c=".$dlcode;
		$dl = $DB->query("insert into `{$db_first}data` (`code`,`cl`) values ('{$dlcode}','{$cl}')");
		if($dl){//开始写入,$cl为原
			json(200,$dl_hots);
		}else{
			json(201,"写入失败");
		}
	}
	if($act == "cxdl_d"){//短查长
		$query = parse_url($cl, PHP_URL_QUERY);
		parse_str($query, $params);
		$c = $params['c'];
		if($c == null)json(201, "链接不正确");
		$array=explode('/', $cl);
		if(count($array) <= 4)json(201, "链接不正确");
		$hots_cl = $array['2'];
		if($hots_cl != $hots)json(201, "非本站短链");
		$cl_2 = $DB->query("select * from `{$db_first}data` where `code`='{$c}'");
		$cl_2_n = $DB->fetch($cl_2);
		if($cl_2_n){
			json(200,$cl_2_n['cl']);
		}else{
			json(201,"不存在此链接");
		}
	}
	if($act == "cxdl_c"){//长链查短
		//可能是虚拟主机，不仅仅是域名，还有路径执行
		$lj = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"/ajax"));
		$cl_3 = $DB->query("select * from `{$db_first}data` where `cl`='{$cl}'");
		$cl_3_n = $DB->fetch($cl_3);
		if($cl_3_n){
			json(200,"http://".$hots."$lj"."/?c=".$cl_3_n['code']);
		}else{
			json(201,"不存在此链接");
		}
	}

?>
