<?php
	if (file_exists("../install/install.lock") == false){
		$string = file_get_contents("error/index.html");
		echo $string;
		exit();
	}
	define('IN_CRONLITE', true);
	include '../includes/class.php';
	include '../includes/db_class.php';
	include '../includes/class_db.php';
	$db_first = DB_PRE;
	$_GET     && SafeFilter($_GET);
	$_POST    && SafeFilter($_POST);
	$_COOKIE  && SafeFilter($_COOKIE);
	
	$action = isset($_GET['action']) ? purge($_GET['action']) : '';
	//未登录不允许触发事件
	if($action!='login' && $action!='quit'){
		$ADMIN_COOKIE = isset($_COOKIE['ADMIN_COOKIE_DL']) ? purge($_COOKIE['ADMIN_COOKIE_DL']) : '';
		// $DB = new DB(DB_HOST,DB_USER,DB_PASSWD,DB_NAME,DB_PORT);
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
		}
	}
	
	
	
	
	//登录验证
	if ($action == 'login') {
		session_start();
		$user = isset($_POST['user']) ? purge($_POST['user']) : null;
		$pass = isset($_POST['pass']) ? purge($_POST['pass']) : null;
		if($user == '' || $pass == ''){
			header('Location:./login.php?err=1');//未输入
			exit;
		}
		if($_REQUEST['authcode'] == ''){
			//未输入验证码
			header('Location:./login.php?err=4');//输入错误或未输入验证码
		}
		if (strtolower(purge($_REQUEST['authcode']))==$_SESSION['authcode']) {//strtolower转化为小写的函数		
			if($user == HT_USER && $pass == HT_PASS){
				$cookie = make_password(16);
				setcookie('ADMIN_COOKIE_DL', $cookie, time() + 36000, '/');//输入正确
				$config_db = $DB->query("update `{$db_first}config` set `code`='{$cookie}' where `code_key`='cookie'");
				$config = $DB->fetch($config_db);
				header('Location:./');
				exit;
			}else{
				header('Location:./login.php?err=2');//输入错误
				exit;
			}
		}
		else{
		    echo"输入错误！";
			header('Location:./login.php?err=4');//输入错误或未输入验证码
		}
		
	}
	if($action == "quit"){
		setcookie('ADMIN_COOKIE_DL', ' ', time() - 36000, '/');
		$config_db = $DB->query("update `{$db_first}config` set `code`='' where `code_key`='cookie'");
		$config = $DB->fetch($config_db);
		header('Location:./login.php');
		exit;
	}
	if($action == "del"){//删除短链
		$id = isset($_POST['id']) ? purge($_POST['id']) : null;//涉及批量
		if($id == null)json(201,"链接为空");//直接删除的短链，无需检查链接是否正确
		
		$ids = explode("|",$id);//数组
		$idss = '';
		foreach ($ids as $value) {
			$idss .= intval($value).",";
		}
		$idss = rtrim($idss, ",");
		$lj_db = $DB->query("delete from `{$db_first}data` where `id` in ({$idss})");
		if($lj_db){
			json(200,"删除成功");
		}else{
			json(201,"删除失败");
		}
		
	}
	if($action == "add"){//新增短链
		$lj = isset($_POST['lj']) ? purge($_POST['lj']) : null;
		$title = isset($_POST['title']) ? purge($_POST['title']) : null;
		$title1 = isset($_POST['title1']) ? purge($_POST['title1']) : null;
		$title2 = isset($_POST['title2']) ? purge($_POST['title2']) : null;
		if($lj == null)json(201,"链接为空");//直接删除的短链，无需检查链接是否正确
		//验证是否是链接
		$zz = '/^(((ht|f)tps?):\/\/)?[\w-]+(\.[\w-]+)+(:\d{1,5})?\/?(\S)*([\W-]*[\w-]+\=[\w-]+)*$/';
		if(preg_match($zz, $lj) == false)json(201,"链接错误");
		if(strpos($lj, "http://") === false && strpos($lj, "https://") === false)$lj = "http://".$lj;
		$cl_1 = $DB->query("select * from `{$db_first}data` where `cl`='{$lj}'");
		$cl_1_nums = $cl_1->num_rows;//count方法失效
		
		if($cl_1_nums!=0)json(201,"已存在此链接，无法生成，请点击查询按钮查询短链");
		
		$hots = $_SERVER['SERVER_NAME'];
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
		$sc_time = time();
		//生成成功
		//写入数据库
		//可能是虚拟主机，不仅仅是域名，还有路径执行
		$dqlj = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"/admin"));
		$dl_hots= "http://".$hots."$dqlj"."/?c=".$dlcode;
		$dl = $DB->query("insert into `{$db_first}data` (`code`,`cl`,`title`,`title1`,`title2`,`data`) values ('{$dlcode}','{$lj}','{$title}','{$title1}','{$title2}','{$sc_time}')");
		if($dl){//开始写入
			json(200,$dl_hots);
		}else{
			json(201,"写入失败");
		}
	}
	if($action == "edit"){//修改短链
		$id = isset($_POST['id']) ? purge($_POST['id']) : json(201,"id为空");
		$cl = isset($_POST['cl']) ? purge($_POST['cl']) : null;
		$title = isset($_POST['title']) ? purge($_POST['title']) : '湮灭短链';
		$title1 = isset($_POST['title1']) ? purge($_POST['title1']) : '湮灭短链';
		$title2 = isset($_POST['title2']) ? purge($_POST['title2']) : '湮灭短链';
		
		//查询是否存在，这里就不写了，反正无此id就修改失败，即写入失败
		$hots = $_SERVER['SERVER_NAME'];
		$array_1=explode('/', $cl);
		$hots_cl_1 = $array_1['2'];
		if($hots_cl_1 == $hots)json(201,"不能使用本站进行生成");
		
		//验证是否是链接
		$zz = '/^(((ht|f)tps?):\/\/)?[\w-]+(\.[\w-]+)+(:\d{1,5})?\/?(\S)*([\W-]*[\w-]+\=[\w-]+)*$/';
		if(preg_match($zz, $cl) == false)json(201,"链接错误");
		if(strpos($cl, "http://") === false && strpos($cl, "https://") === false)$cl = "http://".$cl;
		
		//检查是否与原来相同，如果相同不检查是否存在
		$jc_db = $DB->query("select * from `{$db_first}data` where `id`='{$id}'");
		$jc_q = $DB->fetch($jc_db);
		if($cl != $jc_q['cl']){
			$cl_1 = $DB->query("select * from `{$db_first}data` where `cl`='{$cl}'");
			$cl_1_nums = $cl_1->num_rows;//count方法失效
			
			if($cl_1_nums!=0)json(201,"已存在此链接");
		}
		$dl = $DB->query("update `{$db_first}data` set `cl`='{$cl}',`title`='{$title}',`title1`='{$title1}',`title2`='{$title2}'");
		if($dl){
			json(200,"修改成功");
		}else{
			json(201,"修改失败");
		}
		
	}
	if($action == "edit_sz"){//修改设置
		$config_user = isset($_POST['user']) ? purge($_POST['user']) : null;
		$config_pass = isset($_POST['pass']) ? purge($_POST['pass']) : null;
		$config_lj = true; //管理员账号密码修改成功与否
		$file = "../includes/config_db.php";

		if($config_user!=null){//修改管理员密码
			$info = file_get_contents($file);
			// foreach ($arr as $k => $v) {
			//     $info = preg_replace("/define\(\"{$k}\",\".*?\"\)/", "define(\"{$k}\",\"{$v}\")", $info);
			//     }
			//  file_put_contents($file, $info);
			//楼上批量，涉及单个null，所以单个
			$info = preg_replace("/\'HT_USER','(.*?)'/", "'HT_USER','{$config_user}'", $info);
			$config_lj = file_put_contents($file, $info);
			
			setcookie('ADMIN_COOKIE_DL', ' ', time() - 36000, '/');
			$config_db = $DB->query("update `{$db_first}config` set `code`='' where `code_key`='cookie'");
			$config = $DB->fetch($config_db);
		}
		if(!$config_lj){
			header('Location:./set.php?err=3');
			exit();
		}
		
		if($config_user!=null){//修改管理员密码
			$info = file_get_contents($file);
			// foreach ($arr as $k => $v) {
			//     $info = preg_replace("/define\(\"{$k}\",\".*?\"\)/", "define(\"{$k}\",\"{$v}\")", $info);
			//     }
			//  file_put_contents($file, $info);
			//楼上批量，涉及单个null，所以单个
			// $info = preg_replace("/define\(\"HT_PASS\",\".*?\"\)/", "define(\"HT_PASS\",\"{$config_pass}\")", $info);
			$info = preg_replace("/\'HT_PASS','(.*?)'/", "'HT_PASS','{$config_pass}'", $info);
			$config_lj = file_put_contents($file, $info);
			
			setcookie('ADMIN_COOKIE_DL', ' ', time() - 36000, '/');
			$config_db = $DB->query("update `{$db_first}config` set `code`='' where `code_key`='cookie'");
			$config = $DB->fetch($config_db);
		}
		if(!$config_lj){
			header('Location:./set.php?err=4');
			exit();
		}
		
		
		$config_title = isset($_POST['title']) ? purge($_POST['title']) : null;
		$config_title1 = isset($_POST['title1']) ? purge($_POST['title1']) : null;
		$config_title2 = isset($_POST['title2']) ? purge($_POST['title2']) : null;
		$config_time = isset($_POST['time']) ? purge($_POST['time']) : null;
		$config_template = isset($_POST['template']) ? purge($_POST['template']) : null;
		$config_db = $DB->query("update `{$db_first}config` set `code`='{$config_title}' where `code_key`='title'");
		$config_db1 = $DB->query("update `{$db_first}config` set `code`='{$config_title1}' where `code_key`='title1'");
		$config_db2 = $DB->query("update `{$db_first}config` set `code`='{$config_title2}' where `code_key`='title2'");
		$config_db3 = $DB->query("update `{$db_first}config` set `code`='{$config_time}' where `code_key`='time'");
		$config_db4 = $DB->query("update `{$db_first}config` set `code`='{$config_template}' where `code_key`='template'");
		
		if($config_db == true && $config_db1 == true && $config_db2 == true && $config_db3 == true && $config_db4 == true){
			header('Location:./set.php?err=1');
			exit();
		}else{
			header('Location:./set.php?err=2');
			exit();
		}	
		
	}
	if($action == "check"){//虽然已做重复检测，但为了方便，https与http并未区分，有需要 可以触发此事件只保留http
		
	}
	

?>
