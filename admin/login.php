<?php
	// include_once 'captcha.php';
	define('IN_CRONLITE', true);
	include_once '../includes/class.php';
	include_once '../includes/db_class.php';
	include_once '../includes/class_db.php';
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
	if ($islogin) {
		header('Location:./');//过期cookie或未登录尝试进入后台
		exit;
	}
	$err = isset($_GET['err']) ? intval($_GET['err']) : 0;
	$errmsg = array(null,'账号密码不能为空','账号密码有误','您还没有登陆，请先登录！','你还没有输入验证码或验证码错误，请输入验证码');
	$error_msg = $errmsg[$err];

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title><?=$config_sz['title']?></title>
<link rel="icon" href="../static/img/ico/favicon.ico" type="image/ico">
<meta name="keywords" content=<?=$config_sz['title1']?>>
<meta name="description" content=<?=$config_sz['title2']?>>
<meta name="author" content="yinqi">
<link href="../static/css/bootstrap.min.css" rel="stylesheet">
<link href="../static/css/materialdesignicons.min.css" rel="stylesheet">
<link href="../static/css/style.min.css" rel="stylesheet">
<style>
body {
    display: -webkit-box;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    height: 100%;
}
.login-box {
    display: table;
    table-layout: fixed;
    overflow: hidden;
    max-width: 700px;
}
.login-left {
    display: table-cell;
    position: relative;
    margin-bottom: 0;
    border-width: 0;
    padding: 45px;
}
.login-left .form-group:last-child {
    margin-bottom: 0px;
}
.login-right {
    display: table-cell;
    position: relative;
    margin-bottom: 0;
    border-width: 0;
    padding: 45px;
    width: 50%;
    max-width: 50%;
    background: #67b26f!important;
    background: -moz-linear-gradient(45deg,#67b26f 0,#4ca2cd 100%)!important;
    background: -webkit-linear-gradient(45deg,#67b26f 0,#4ca2cd 100%)!important;
    background: linear-gradient(45deg,#67b26f 0,#4ca2cd 100%)!important;
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#67b26f', endColorstr='#4ca2cd', GradientType=1 )!important;
}
.login-box .has-feedback.feedback-left .form-control {
    padding-left: 38px;
    padding-right: 12px;
}
.login-box .has-feedback.feedback-left .form-control-feedback {
    left: 0;
    right: auto;
    width: 38px;
    height: 38px;
    line-height: 38px;
    z-index: 4;
    color: #dcdcdc;
}
.login-box .has-feedback.feedback-left.row .form-control-feedback {
    left: 15px;
}
@media (max-width: 576px) {
  .login-right {
      display: none;
  }   
}
</style>
</head>
  
<body style="background-image: url(../static/images/login-bg-2.jpg); background-size: cover;">
<div class="bg-translucent p-10">
  <div class="login-box bg-white clearfix">
    <div class="login-left">
      <form action="./ajax.php?action=login" method="post">
        <div class="form-group has-feedback feedback-left">
          <input type="text" placeholder="请输入您的用户名" class="form-control" name="user" id="user" />
          <span class="mdi mdi-account form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="form-group has-feedback feedback-left">
          <input type="password" placeholder="请输入密码" class="form-control" id="pass" name="pass" />
          <span class="mdi mdi-lock form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="form-group has-feedback feedback-left row">
          <div class="col-xs-7">
            <input type="text" name="authcode" class="form-control" placeholder="验证码">
            <span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
          </div>
          <div class="col-xs-5">
            <img src="./captcha.php?r=echo rand();" class="pull-right" id="captcha_img" style="cursor: pointer;" onclick="document.getElementById('captcha_img').src='./captcha.php?r='+Math.random()" title="点击刷新" alt="captcha">
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-block btn-primary" onclick="" id="login">立即登录</button>
        </div>
      </form>
	  <?php if($error_msg):?>
	  <div class="card-body" role="alert">
	  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	  		<span aria-hidden="true">&times;</span>
	  	</button>
	  	<strong>提示：</strong> <?php echo $error_msg; ?>
	  </div>
	  <?php endif; ?>
    </div>
    <div class="login-right">
      <p><img src="../static/images/logo.png" class="m-b-md m-t-xs" alt="logo"></p>
      <p class="text-white m-tb-15">湮灭短链是以光年后台为前端管理模板，自写首页跳转自定义模板的系统</p>
      <p class="text-white">Copyright © 2050 <a href="http://www.ymypay.cn">湮灭网络</a>. All right reserved</p>
    </div>
  </div>
</div>
<script type="text/javascript" src="../static/js/jquery.min.js"></script>
<script type="text/javascript" src="../static/js/bootstrap.min.js"></script>
<script type="text/javascript">
$("login").click(function(){
	console.log("1");
	// $.ajax({
	//     type: 'get',
	//     url: './ajax.php',
	//     data: {
	// 		user: user,
	//         pass: pass,
	//     },
	//     dataType: 'text',
	//     success: function(data) {
	//         console.log(data)
	//         data = JSON.parse(data);
	//         if (data.code == 200) {
	// 			alert("登录成功");
	//             // mdui.alert('<div class="mdui-typo">您的短链接为：<a href="'+data.result+'" target="_blank">'+data.result+'</a></div>', '生成成功');
	//             $("#url").val("");
	//         } else {
	// 			alert(data.msg);
	//             // mdui.snackbar({
	//             //     message: data.msg,
	//             //     position: 'right-top'
	//             // });
	//         }
	//     },
	//     error: function(data) {
	//         var errors = data.responseJSON;
	//         $.each(errors.errors, function(key, value) {
	// 			alert("出现了未知错误");
	//         });
	//     },
	// });
})
</script>
</body>
</html>