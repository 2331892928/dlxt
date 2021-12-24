<?php
	include 'header.php';
	$index_dl_db = $DB->query("select * from `{$db_first}data`");
	$index_dl_db_nums = $index_dl_db->num_rows;//count方法失效
	
	//今日
	$day_q = strtotime(date("Y-m-d")." "."00:00:00");
	$day_h = strtotime(date("Y-m-d")." "."23:59:59");
	$index_dl_db1 = $DB->query("select * from `{$db_first}data` where `data`>='{$day_q}' and `data`<='{$day_h}'");
	$index_dl_db1_nums = $index_dl_db1->num_rows;//count方法失效
	
	//http
	$index_dl_db2 = $DB->query("select * from `{$db_first}data` where `cl` like 'http://%'");
	$index_dl_db2_nums = $index_dl_db2->num_rows;//count方法失效
	
	//https
	$index_dl_db3 = $DB->query("select * from `{$db_first}data` where `cl` like 'https://%'");
	$index_dl_db3_nums = $index_dl_db3->num_rows;//count方法失效
	
	$ws_arr = [];
	$weekarray=array(7,1,2,3,4,5,6); //先定义一个数组
	$ws_d = $weekarray[date("w")]; //今日周几，比如今日周3，即获取最近上次周一到今天
	$ws_r = date("d"); //今日号数
	$ws_q = date("Y-m"); // 今月
	for($i = 1; $i < $ws_d; $i++){
		$ws_q = date("Y-m"); // 今月
		//有可能上个月
		if($ws_d-$i<=0){
			// $ws_q = date("Y")."-".(string)(date("m")-"1");
			// $ws_r = date("m")-"1"+$ws_r-$i;
			// $ls = $ws_q."-".$ws_r;//昨日/前日日期，再获取前时间戳以及尾时间戳
			
			// //有可能去年
			// if(date("m")-"1"<=0){
			// 	$ws_q = (string)(date("Y"))-"1"."-"."12";
			// 	$ws_r = "11"+$ws_r-$i;
			// 	$ls = $ws_q."-".$ws_r;//昨日/前日日期，再获取前时间戳以及尾时间戳
			// }
		}else{
			$ls = $ws_q."-".(string)($ws_r-$i);//昨日/前日日期，再获取前时间戳以及尾时间戳
		}
		
		
		$ls_q = strtotime($ls." "."00:00:00");
		$ls_h = strtotime($ls." "."23:59:59");
		$index_dl_db4 = $DB->query("select * from `{$db_first}data` where `data`>='{$ls_q}' and `data`<='{$ls_h}'");
		$index_dl_db4_nums = $index_dl_db4->num_rows;//count方法失效
		Array_push($ws_arr,$index_dl_db4_nums);
	}
	$ws_arr = array_reverse($ws_arr);
	//再加上今日
	Array_push($ws_arr,$index_dl_db1_nums);
	//检查是否为7个不足添0
	while (true) {
		# code...
		if(count($ws_arr)==7){
			break;
		}
		Array_push($ws_arr,0);
	}
	
	//数组格式化
	foreach ($ws_arr as $value) {
	  $json .= json_encode($value) . ',';
	} 
	$json = '[' . substr($json,0,strlen($json) - 1) . ']';
?>
<?php require 'head.php';?>
  
<body>
<div class="lyear-layout-web">
  <div class="lyear-layout-container">
    <!--左侧导航-->
    <aside class="lyear-layout-sidebar">
      <?php include 'menu.php';?>
      
    </aside>
    <!--End 左侧导航-->
    
    <!--头部信息-->
    <header class="lyear-layout-header">
		<?php require 'headr.php';?>
      
    </header>
    <!--End 头部信息-->
    
    <!--页面主要内容-->
    <main class="lyear-layout-content">
      
      <div class="container-fluid">
        
        <div class="row">
          <div class="col-sm-6 col-lg-3">
            <div class="card bg-primary">
              <div class="card-body clearfix">
                <div class="pull-right">
                  <p class="h6 text-white m-t-0">今日短链</p>
                  <p class="h3 text-white m-b-0 fa-1-5x"><?= $index_dl_db1_nums ?></p>
                </div>
                <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-currency-cny fa-1-5x"></i></span> </div>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-lg-3">
            <div class="card bg-danger">
              <div class="card-body clearfix">
                <div class="pull-right">
                  <p class="h6 text-white m-t-0">短链总数</p>
                  <p class="h3 text-white m-b-0 fa-1-5x"><?= $index_dl_db_nums ?></p>
                </div>
                <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-account fa-1-5x"></i></span> </div>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-lg-3">
            <div class="card bg-success">
              <div class="card-body clearfix">
                <div class="pull-right">
                  <p class="h6 text-white m-t-0">http短链总数</p>
                  <p class="h3 text-white m-b-0 fa-1-5x"><?= $index_dl_db2_nums ?></p>
                </div>
                <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-lg-3">
            <div class="card bg-purple">
              <div class="card-body clearfix">
                <div class="pull-right">
                  <p class="h6 text-white m-t-0">https短链总数</p>
                  <p class="h3 text-white m-b-0 fa-1-5x"><?= $index_dl_db3_nums ?></p>
                </div>
                <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-lg-6"> 
            <div class="card">
              <div class="card-header">
                <h4>每周数量</h4>
              </div>
              <div class="card-body">
                <canvas class="js-chartjs-bars"></canvas>
              </div>
            </div>
          </div>
          
          <div class="col-lg-6"> 
            <div class="card">
              <div class="card-header">
                <h4>空白图表(待更新)</h4>
              </div>
              <div class="card-body">
                <canvas class="js-chartjs-lines"></canvas>
              </div>
            </div>
          </div>
           
        </div>
        
        <div class="row">
          
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4>项目信息(该项目论坛：http://bbs.ymypay.cn/)</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>项目名称</th>
                        <th>开始日期</th>
                        <th>截止日期</th>
                        <th>状态</th>
                        <th>进度</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>制作项目</td>
                        <td>12/20/2021</td>
                        <td>--/--/----</td>
                        <td><span class="label label-warning">进行中</span></td>
                        <td>
                          <div class="progress progress-striped progress-sm">
                            <div class="progress-bar progress-bar-warning" style="width: 45%;"></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>新增用户管理</td>
                        <td>--/--/----</td>
                       <td>--/--/----</td>
                        <td><span class="label label-success">待定</span></td>
                        <td>
                          <div class="progress progress-striped progress-sm">
                            <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>未登录用户短链有有效期(登录用户可更改title)</td>
                         <td>--/--/----</td>
                         <td>--/--/----</td>
                        <td><span class="label label-success">待定</span></td>
                        <td>
                          <div class="progress progress-striped progress-sm">
                            <div class="progress-bar progress-bar-warning" style="width: 0%;"></div>
                          </div>
                        </td>
                      </tr>
					  <tr>
					    <td>4</td>
					    <td>当前版本：1.0</td>
					     <td>--/--/----</td>
					     <td>--/--/----</td>
					    <td><span class="label label-warning">进行中</span></td>
					    <td>
					      <div class="progress progress-striped progress-sm">
					        <div class="progress-bar progress-bar-warning" style="width: 0%;"></div>
					      </div>
					    </td>
					  </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        
      </div>
    </main>
    <!--End 页面主要内容-->
  </div>
</div>

<?php require 'foot.php';?>
<script type="text/javascript">
$(document).ready(function(e) {
    var $dashChartBarsCnt  = jQuery( '.js-chartjs-bars' )[0].getContext( '2d' ),
        $dashChartLinesCnt = jQuery( '.js-chartjs-lines' )[0].getContext( '2d' );
    
    var $dashChartBarsData = {
		labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
		datasets: [
			{
				label: '新增数量',
                borderWidth: 1,
                borderColor: 'rgba(0,0,0,0)',
				backgroundColor: 'rgba(51,202,185,0.5)',
                hoverBackgroundColor: "rgba(51,202,185,0.7)",
                hoverBorderColor: "rgba(0,0,0,0)",
				data: <?= $json ?>
			}
		]
	};
    var $dashChartLinesData = {
		labels: ['2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014'],
		datasets: [
			{
				label: '空白图表',
				data: [20, 25, 40, 30, 45, 40, 55, 40, 48, 40, 42, 50],
				borderColor: '#358ed7',
				backgroundColor: 'rgba(53, 142, 215, 0.175)',
                borderWidth: 1,
                fill: false,
                lineTension: 0.5
			}
		]
	};
    
    new Chart($dashChartBarsCnt, {
        type: 'bar',
        data: $dashChartBarsData
    });
    
    var myLineChart = new Chart($dashChartLinesCnt, {
        type: 'line',
        data: $dashChartLinesData,
    });
});
</script>
</body>
</html>