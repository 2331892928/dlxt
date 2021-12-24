<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
	<meta name="keywords" content=<?= $index_title1?>>
	<meta name="description" content=<?= $index_title2?>>
    <title><?= $index_title?></title>
    <link rel="stylesheet" href="//static.llilii.cn/css/other/background.css" />
	<!-- 必要 -->
	<link rel="stylesheet" href="static/css/base.css" />
	<!--  -->
    <link rel="stylesheet" href="static/css/mdui.min.css" />
	
    <script src="https://static.llilii.cn/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        a{
            text-decoration:none
        }
        .hide {
            position: inherit;
            width: 10PX;
            height: calc(20vh);
        }
    </style>
</head>

<body class="mdui-appbar-with-toolbar  mdui-theme-primary-pink mdui-theme-accent-pink">

    <div class="mdui-container " style="max-width: 400px; ">
        <div class="hide">
        </div>
        <div class="mdui-card" style="border-radius: 16px;">
            <div class="mdui-card-media">
                <div class="mdui-card-menu">
                </div>
            </div>
            <div class="mdui-card-primary">
                <div class="mdui-card-primary-title">短链生成器</div>
                <div class="mdui-card-primary-subtitle">在此处您可以一键生成您的短链接</div>
            </div>
            <div class="mdui-card-content">
                <div class="mdui-textfield">
                    <label class="mdui-textfield-label">需要生成短链的网址</label>
                    <input class="mdui-textfield-input" id="url" placeholder="" type="text" />
                </div>
                <br>
            </div>
            <div class="mdui-card-actions">
				<button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" id="submitbtn1" style="border-radius: 10px;">查询短链</button>
				<button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" id="submitbtn2" style="border-radius: 10px;">查询长链</button>
                <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" id="submitbtn" style="border-radius: 10px;">生成短链</button>
            </div>
        </div>
        <footer>
            <!-- 本程序使用GPL2.0协议开源，请遵守此协议，请勿删除本处版权，否则原作者保留一切法律权利 -->
            <!-- 如果看不懂GPL2.0协议请自行查看根目录人话版解释。如果想删除本处版权的请直接不要使用本程序。 -->
            <center><p style="color:white;">&copy; 2020 Copyright <a style="color:white;" target="_blank" href="https://www.wunote.cn/">湮灭网络</a></p></center>
        </footer>

    </div>

    <script src="static/js/mdui.min.js"></script>
	<script type="text/javascript" src="static/js/message.js"></script>
	<script src="static/js/ajax.js"></script>
	
    <script>
        $("#submitbtn").click(function(){
            // $("#submitbtn").attr("disabled", true);
			var cl = $("#url").val();
			request(cl);
			
        })
		$("#submitbtn1").click(function(){
		    // $("#submitbtn").attr("disabled", true);
			var cl = $("#url").val();
			request(cl,"cx_c");
			
		})
		$("#submitbtn2").click(function(){
		    // $("#submitbtn").attr("disabled", true);
			var cl = $("#url").val();
			request(cl,"cx_d");
			
		})
    </script>
    <div id="background">
        <div class="bg-image" style="background: url('//static.llilii.cn/images/kagamine/32639516_p2.jpg') no-repeat center center; display: block;"></div>
    </div>
</body>

</html>
