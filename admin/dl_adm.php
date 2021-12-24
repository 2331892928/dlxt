<?php
 include 'header.php';
 $index_dl_db = $DB->query("select * from `{$db_first}data`");
 $index_dl_db_nums = $index_dl_db->num_rows;//count方法失效
 

 $page=isset($_GET['page']) ? intval($_GET['page']) : 1;
 $url="dl_adm.php?page=";
 $bnums=($page-1)*10;
 $enums=$bnums+10;
 $index_dl_db_page = $DB->query("select * from `{$db_first}data` limit {$bnums},{$enums}");
?>
<?php require 'head.php';?>
<body>
<div class="lyear-layout-web">
  <div class="lyear-layout-container">
    <!--左侧导航-->
    <aside class="lyear-layout-sidebar">
		<?php include_once 'menu.php';?>
      
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
          <div class="col-lg-12">
            <div class="card">
              <div class="card-toolbar clearfix">
<!--                <form class="pull-right search-bar" method="get" action="#!" role="form">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <input type="hidden" name="search_field" id="search-field" value="name">
                      <button class="btn btn-default dropdown-toggle" id="search-btn" data-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="false">
                      应用名称 <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li> <a tabindex="-1" href="javascript:void(0)" data-field="name">应用名称</a> </li>
                        <li> <a tabindex="-1" href="javascript:void(0)" data-field="ID">ID</a> </li>
                      </ul>
                    </div>
                    <input type="text" class="form-control" value="" name="keyword" placeholder="请输入名称">
                  </div>
                </form> -->
                <div class="toolbar-btn-action">
                  <a class="btn btn-primary m-r-5" data-toggle="modal" data-target="#myModal" onclick="edit('new');"><i class="mdi mdi-plus"></i> 新增</a>
                  <button type="submit" class="btn btn-danger" id="del" name="del"><i class="mdi mdi-window-close"></i> 删除</button>
				  <!-- 为了方便，a标签改为button，方便触发事件，函数juqery出问题 -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">新增短链</h4>
						      </div>
						      <div class="modal-body">
																	<form>
																	  <lable id="id" name="id"></label>
																	  <div class="form-group">
																		<label for="recipient-name" class="control-label">填写长链</label>
																		<input type="text" class="form-control" id="add_cl" name="add_cl">
																	  </div>
																	  <div class="form-group">
																		<label for="message-text" class="control-label">链接标题(可空)</label>
																		<input type="text" class="form-control" id="add_title" name="add_title">
																	  </div>
																	  <div class="form-group">
																	  	<label for="message-text" class="control-label">链接标题1(可空)</label>
																	  	<textarea class="form-control" id="add_title1" name="add_title1" rows="5" value="0" placeholder="链接的title1,可空"></textarea>
																	  </div>
																	  <div class="form-group">
																	  	<label for="message-text" class="control-label">链接标题2(可空)</label>
																		<textarea class="form-control" id="add_title2" name="add_title2" rows="5" value="0" placeholder="链接的title2,可空"></textarea>
																	  </div>
																	</form>
						      </div>
						      <div class="modal-footer">
								<button type="button" class="btn btn-danger" id="zhuyi" name="zhuyi">注意</button>
						        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						        <button type="button" class="btn btn-primary" id="add_shop" name="add_shop">点击生成</button>
						      </div>
						    </div>
						  </div>
						</div>
                </div>
              </div>
              <div class="card-body">
                
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>
                          <label class="lyear-checkbox checkbox-primary">
                            <input type="checkbox" id="check-all"><span></span>
                          </label>
                        </th>
						<th>ID</th>
						<th>标题</th>
                        <th>短链code</th>
                        <th>长链</th>
                        <th>添加时间</th>
                        <th>操作</th>
                      </tr>
                    </thead>
					
                    <tbody>
					<?php 	
							while($row1= mysqli_fetch_assoc($index_dl_db_page))
							{
								
					?>
                      <tr>
                        <td width="10">
                          <label class="lyear-checkbox checkbox-primary">
                            <input type="checkbox" name="ids[]" value="<?php echo $row1['id']; ?>" id="<?php echo $row1['id']; ?>"><span></span>
                          </label>
                        </td>
						<!-- ID -->
                        <td><?php echo $row1['id']; ?></td>
						<!-- 标题 -->
                        <td><?php echo $row1['title']; ?></td>
						<!-- code -->
                        <td><?php echo $row1['code']; ?></td>
						<!--  长链-->
                        <td><?php echo $row1['cl'];?></td>
						<!-- 时间 -->
                        <td><?php echo date("Y-m-d",$row1['data']); ?></td>
                        <td>
							<!-- 操作 -->
                          <div class="btn-group">
                            <a class="btn btn-xs btn-default" title="编辑" data-toggle="modal" data-target="#myModal" onclick="edit('edit','<?= $row1['id'] ?>','<?= $row1['cl'] ?>','<?= $row1['title'] ?>','<?= $row1['title1'] ?>','<?= $row1['title2'] ?>');"><i class="mdi mdi-pencil"></i></a>
                            <a class="btn btn-xs btn-default" title="复制短链" data-toggle="tooltip" id="copy" onclick="copy('<?= $row1['code']?>');" ><i class="mdi mdi-content-copy"></i></a>
							<a class="btn btn-xs btn-default" title="删除" data-toggle="tooltip" id="<?= $row1['id'].$row1['name'] ?>" onclick="JavaScript:queren(<?= $row1['id']?>);" ><i class="mdi mdi-window-close"></i></a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
					<?php } ?>
                  </table>
                </div>
                <ul class="pagination">
				<?php
				$url_page = 'dl_adm.php?page=';
				echo app_page($index_dl_db_nums,10,$page,$url_page);
				?>
                </ul>
       
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
$(function(){
    $('.search-bar .dropdown-menu a').click(function() {
        var field = $(this).data('field') || '';
        $('#search-field').val(field);
        $('#search-btn').html($(this).text() + ' <span class="caret"></span>');
    });
});
	var host = GetUrlPath();
	function GetUrlPath(){
		var url = document.location.toString();				
		if(url.indexOf("/") != -1){
			url = url.substring(0,  url.lastIndexOf("/")) ;
		}
		return url;
	}
	function ajaxPostFun(selfObj, ajax_url, form_data, loader) {
		    jQuery.post(ajax_url, form_data).done(function(res) {
		        selfObj.find('.lyear-loading').remove(); // 清除按钮上的loading
		        loader.destroy();
		        var res = eval('(' + res + ')');
		        var msg = res.msg;
				var url = host+'/dl_adm.php';
		        if (200 == res.code) {
		            if (url && !selfObj.hasClass('no-refresh')) {
		                msg += '页面即将自动跳转';
		            }
		            showNotify("操作成功", msg, 'info');
		            setTimeout(function () {
						selfObj.attr("autocomplete", "on").prop("disabled", false);
						return selfObj.hasClass("no-refresh") ? false : (url ? location.href = url : location.reload());
					}, 1500);
		        } else {
		            showNotify("错误", msg, 'danger');
		            selfObj.attr("autocomplete", "on").prop("disabled", false);
		        }
		    }).fail(function () {
		        loader.destroy();
		        selfObj.find('.lyear-loading').remove(); // 清除按钮上的loading
		        showNotify("错误", '服务器发生错误，请稍后再试', 'danger');
		        selfObj.attr("autocomplete", "on").prop("disabled", false);
		    });
		}
		    /*
		     * 提取通用的通知消息方法
		     * 这里只采用简单的用法，如果想要使用回调或者更多的用法，请查看lyear_js_notify.html页面
		     * @param $msg 提示信息
		     * @param $type 提示类型:'info', 'success', 'warning', 'danger'
		     * @param $delay 毫秒数，例如：1000
		     * @param $icon 图标，例如：'fa fa-user' 或 'glyphicon glyphicon-warning-sign'
		     * @param $from 'top' 或 'bottom' 消息出现的位置
		     * @param $align 'left', 'right', 'center' 消息出现的位置
		     */
		    function showNotify($title, $msg, $type, $delay, $icon, $from, $align) {
		        $type  = $type || 'info';
		        $delay = $delay || 1000;
		        $from  = $from || 'top';
		        $align = $align || 'right';
		        $enter = $type == 'danger' ? 'animated shake' : 'animated fadeInUp';
		
		        jQuery.notify({
		            icon: $icon,
		            message: $title+"<br>"+$msg
		        },
		        {
		            element: 'body',
		            type: $type,
		            allow_dismiss: true,
		            newest_on_top: true,
		            showProgressbar: false,
		            placement: {
		                from: $from,
		                align: $align
		            },
		            offset: 20,
		            spacing: 10,
		            z_index: 10800,
		            delay: $delay,
		            animate: {
		                enter: $enter,
		                exit: 'animated fadeOutDown'
		            }
		        });
		    }
			function queren(ID){
				$.alert({
				    title: '嗨',
				    content: '你确定要删除吗？这将不可恢复',
					buttons: {
						confirm: {
							text: '确认',
							btnClass: 'btn-primary',
							action: function(){
								var loader = $('body').lyearloading({
								    opacity: 0.2,
								    spinnerSize: 'lg'
								});	
								ajaxPostFun($(this), host+"/ajax.php?action=del", 'id='+ID, loader);
				                return false;
							}
						},
						cancel: {
							text: '取消',
							action: function () {
				                
							}
						}
					}
				});

			}
			function edit(type=null, id=null, cl=null, title=null, title1=null, title2=null){
				if(type == 'edit'){//修改
					$("#myModalLabel").html("编辑短链");
					$("#add_cl").val(cl);
					$("#add_title").val(title);
					$("#add_title1").val(title1);
					$("#add_title2").val(title2);
					$("#id").val(id);
					$("#add_shop").html("点击修改");
				}else{//新增
					$("#myModalLabel").html("新增短链");
					$("#add_cl").val("");
					$("#add_title").val("");
					$("#add_title1").val("");
					$("#add_title2").val("");
					$("#id").val("");
					$("#add_shop").html("点击生成");
				}
			}
			function copy(connect){
				// 执行浏览器复制命令
				var oInput = document.createElement('input');
				oInput.value = "http://"+document.domain+"/?c="+connect;
				document.body.appendChild(oInput);
				oInput.select(); // 选择对象
				document.execCommand("Copy"); // 执行浏览器复制命令
				oInput.className = 'oInput';
				oInput.style.display='none';
				console.log('复制成功');
				showNotify("复制成功", "http://"+document.domain+"/?c="+connect , 'info');
			}
	$(document).ready(function(){
		$("#del").click(function(){
			$.alert({
			    title: '嗨',
			    content: '你确定要删除吗？这将不可恢复',
				buttons: {
					confirm: {
						text: '确认',
						btnClass: 'btn-primary',
						action: function(){
							var id_array=new Array();
							var str = '';
							$("input[name='ids[]']:checked").each(function(){  
								id_array.push($(this).val());//向数组中添加元素  
							});  //获取界面复选框的所有值
							for (var i=0;i<id_array.length;i++){
								if(str==''){
									str=id_array[i];
								}else{
									str=str+"|"+id_array[i];
								}
								
							}
							// var l = $(this).lyearloading({
							// 	opacity: 0.2,
							// 	spinnerSize: 'nm'
							// });//对话框中无按钮加载
							var loader = $('body').lyearloading({
								opacity: 0.2,
								spinnerSize: 'lg'
							});	
							ajaxPostFun($(this), host+"/ajax.php?action=del", 'id='+str, loader);
			                return false;
						}
					},
					cancel: {
						text: '取消',
						action: function () {
			                
						}
					}
				}
			});
		});
		$("#add_shop").click(function(){
			var l = $(this).lyearloading({
			    opacity: 0.2,
			    spinnerSize: 'nm'
			});
			var loader = $('body').lyearloading({
			    opacity: 0.2,
			    spinnerSize: 'lg'
			});	
			if($("#id").val()!=""){//修改
				console.log("修改");
				cl = $("#add_cl").val();
				title = $("#add_title").val();
				title1 = $("#add_title1").val();
				title2 = $("#add_title2").val();
				id = $("#id").val();
				ajaxPostFun($(this), host+"/ajax.php?action=edit", 'id='+id+'&cl='+cl+'&title='+title+'&title1='+title1+'&title2='+title2, loader);
				
				
				
			}else{//新增
				console.log("新增");
				cl = $("#add_cl").val();
				title = $("#add_title").val();
				title1 = $("#add_title1").val();
				title2 = $("#add_title2").val();
				ajaxPostFun($(this), host+"/ajax.php?action=add", 'lj='+cl+'&title='+title+'&title1='+title1+'&title2='+title2, loader);
			}
			
		});
		$("#zhuyi").click(function(){
			confirm("短链就是达到防止屏蔽链接的目的，请勿将title,title1,title2填写容易违规或屏蔽的词，否则容易造成主网站屏蔽");
		});
	})


</script>
</body>
</html>