<?php
	//示例模板
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		 <meta name="keywords" content=<?= $title1 ?>>
		 <meta name="description" content=<?= $title2 ?>>
		 
		 
		 
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<title><?= $title?></title>
		<style>
			*{
			    padding: 0px;
				margin: 0px;
			}
			body{
				width: 100%;
				height: 100vh;
			}
			.main{
				position: relative;
			    width: 100%;
				height: 100%;
				background: #000;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			.circle{
				display: flex;
				justify-content: center;
				align-items: center;
				width: 200px;
				height: 200px;
				background-image: linear-gradient(0deg,
				rgb(47,102,255),
				rgb(153,64,255) 30%,
				rgb(238,55,255) 60%,
				rgb(255,0,76) 100%);
				border-radius: 50%;
				animation: rotate 1s linear infinite;
			}
			/* 模糊 */
			.circle::before{
				content: "";
				position:absolute;
				width: 200px;
				height: 200px;
				background-image: linear-gradient(0deg,
				rgb(47,102,255),
				rgb(153,64,255) 30%,
				rgb(238,55,255) 60%,
				rgb(255,0,76) 100%);
				border-radius: 50%;
				filter: blur(35px);
			}
			/* 黑圆 */
			.circle::after{
				content: "";
				position:absolute;
				width: 150px;
				height: 150px;
				background:#000;
				border-radius: 50%;
			}
			h1{
				position: absolute;
				color: #fff;
				font-weight: bold;
			}
			.title{
				color: #FFFFFF;
				position:absolute;
				top:10%;
				text-shadow:0px 0 30px #fff;
				text-align: center;
				font-size: 2rem;
				font-weight: bold;
			}
			.text{
				color: #FFFFFF;
				position:absolute;
				bottom:10%;
				text-shadow:0px 0 30px #fff;
				font-weight: bold;text-align: center;
			}
			/* 添加动画 */
			@keyframes rotate{
				0%{
					transform: rotate(0deg);
				}
				100%{
					transform: rotate(360deg);
				}
			}
		</style>
	</head>
	<body onload="time()">
	    <div class="main">
			<div class="circle"></div>
			<h1 id="second"><?= $time ?>s</h1>
			<p class="title">安全跳转页</p>
			<p class="text">网络交易需谨慎，正在跳转到目标网址</p>
		</div>
	</body>
	<script>
		function time(){
			var sec = document.getElementById("second");
			 var i = <?= $time ?>;//设置定时时间
			 var timer = setInterval(function(){
			 
			 sec.innerHTML = i+"s";
				 if(i==1){
					 sec.innerHTML = "正在跳转中，请稍后";
				  window.location.href = <?php echo '"'.$url.'"';?>;
				 }
				 i--;
			 },1000);
			  
			 function goBack(){ 
			 window.history.go(-1);
			 } 
		}
	</script>
</html>