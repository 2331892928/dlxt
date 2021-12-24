<?php
    include 'header.php';
	
	
	
	
	$err = isset($_GET['err']) ? intval($_GET['err']) : 0;
	$errmsg = array(null,'保存成功','保存失败','管理员账号修改失败','管理员密码修改失败');
	$error_msg = $errmsg[$err];
	
	$template_1 = getDirContent("../template/",1);//所有模板
?>
<?php require 'head.php';?>
<body>
	<!-- 检查是否保持正确 -->
	<?php if($error_msg):?>
	<script type="text/javascript">
		alert('<?= $error_msg ?>');
	</script>
	<?php endif; ?>
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
              <div class="card-body">
                
<!--               <div class="edit-avatar">
                  <img src="images/users/avatar.jpg" alt="..." class="img-avatar">
                  <div class="avatar-divider"></div>
                  <div class="edit-avatar-content">
                    <button class="btn btn-default">修改头像</button>
                    <p class="m-0">选择一张你喜欢的图片，裁剪后会自动生成264x264大小，上传图片大小不能超过2M。</p>
                  </div>
                </div> -->
                <hr>
                <form method="post" action="./ajax.php?action=edit_sz" class="site-form" onsubmit="return sumbit_sure();">
                  <div class="form-group">
                    <label for="user">管理员账号(修改后需重新登录)</label>
                    <input type="text" class="form-control" name="user" id="user" placeholder="不更改请留空" value=""/>
                  </div>
                  <div class="form-group">
                    <label for="pass">管理员密码(修改后需重新登录)</label>
                    <input type="text" class="form-control" name="pass" id="pass" placeholder="不更改请留空" value="">
                  </div>
				  <div class="form-group">
				    <label for="title">网站标题</label>
				    <input type="text" class="form-control" name="title" id="title" placeholder="" value="<?= $config_sz['title'] ?>">
				  </div>
				  <div class="form-group">
				    <label for="title1">网站title1</label>
				    <textarea class="form-control" rows="5" name="title1" id="title1" placeholder="" value="<?= $config_sz['title1'] ?>"><?= $config_sz['title1'] ?></textarea>
				  </div>
				  <div class="form-group">
				    <label for="title2">网站title2</label>
				    <textarea class="form-control" rows="5" name="title2" id="title2" placeholder="" value="<?= $config_sz['title2'] ?>"><?= $config_sz['title2'] ?></textarea>
				  </div>
				  <div class="form-group">
				    <label for="time">短链跳转时间(秒)</label>
				    <input type="number" class="form-control" name="time" id="time" placeholder="" value="<?= $config_sz['time'] ?>">
				  </div>
				  <div class="form-group">
				     <label for="template">首页及跳转模板(模板可在论坛发布或下载，见后台首页)</label>
				     <div>
					 <!-- value用html一样的，可中文 -->
						<select class="form-control" id="template" name="template" size="1">
							<?php foreach ($template_1 as $key) {?>
							<option value="<?= $key ?>" <?php if($config_sz['template']==$key) echo 'selected = "selected"'; ?>><?= $key ?></option>
							<?php }?>
						</select>
				     </div>
				   </div>
                  <button type="submit" class="btn btn-primary">保存</button>
                </form>
       
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
<script language="javascript">
function sumbit_sure(){
var gnl=confirm("确定要提交?");
if (gnl==true){
return true;
}else{
return false;
}
}
</script>
</body>
</html>
