<?php
	function SafeFilter (&$arr)
	{
	   $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/'
	   ,'/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/'
	   ,'/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/',
	   '/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/'
	   ,'/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');
	     
	   if (is_array($arr))
	   {
	     foreach ($arr as $key => $value) 
	     {
	        if (!is_array($value))
	        {
	          if (!get_magic_quotes_gpc())  //不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
	          {
	             $value  = addslashes($value); //给单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）  加上反斜线转义
	          }
	          $value       = preg_replace($ra,'',$value);     //删除非打印字符，粗暴式过滤xss可疑字符串
	          $arr[$key]     = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为 HTML 实体
	        }
	        else
	        {
	          SafeFilter($arr[$key]);
	        }
	     }
	   }
	}
	function purge($string,$trim = true,$filter = true,$force = 0, $strip = FALSE) {//递归addslashes  对参数进行净化
		$encode = mb_detect_encoding($string,array("ASCII","UTF-8","GB2312","GBK","BIG5"));
		if($encode != 'UTF-8'){
			$string = iconv($encode,'UTF-8',$string);
		}
		if($trim){
			$string = trim($string);
		}
		if($filter){
			$farr = array(
				"/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
				"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
				"/select|insert|and|or|create|update|delete|alter|count|\'|\/\*|\*|\.\.\/|\.\/|\^|union|into|load_file|outfile|dump/is"
			);
			$string = preg_replace($farr,'',$string);
		}
		!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		if(!MAGIC_QUOTES_GPC || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = purge($val, $force, $strip);
				}
			} else {
				$string = addslashes($strip ? stripslashes($string) : $string);
			}
		}
		
		return $string;
	}
	function get_extension($file){//取后缀
		return substr(strrchr($file, '.'), 1);
	}
	function getDirContent($path,$type=null){//获取目录，默认获取文件及目录
	  if(!is_dir($path)){
	    return false;
	  }
	  //readdir方法
	  /* $dir = opendir($path);
	  $arr = array();
	  while($content = readdir($dir)){
	    if($content != '.' && $content != '..'){
	      $arr[] = $content;
	    }
	  }
	  closedir($dir); */
	 
	  //scandir方法
	  $arr = array();
	  $data = scandir($path);
	  foreach ($data as $value){
		  if($type==null){
			  if($value != '.' && $value != '..'){
			  	$arr[] = $value;
			  }
		  }else{
			  if($value != '.' && $value != '..' && $value!=null){
				  if(get_extension($value) == null){
					  $arr[] = $value;
				  }
			  }
		  }
	
	  }
	  return $arr;
	}
	function json($code,$msg) {//json输出
		$udata = array('code'=>$code,'msg'=>$msg);
		$jdata = json_encode($udata);
		echo $jdata;
		exit;
	}
	function make_password( $length = 8 ){ 
	     // 密码字符集，可任意添加你需要的字符
	     $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
	                 'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
	                't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
	                'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
	                'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',);
	   
	     // 在 $chars 中随机取 $length 个数组元素键名
	     $keys =array_rand ($chars, $length);
	     $password = "";
	     for($i = 0; $i < $length; $i++){
	         // 将 $length 个数组元素连接成字符串
	         $password .= $chars[$keys[$i]];
	     }
	            return $password;
	}
	
	function UnicodeEncode($str){
	    //split word
	    preg_match_all('/./u',$str,$matches);
	 
	    $unicodeStr = "";
	    foreach($matches[0] as $m){
	        //拼接
	        $unicodeStr .= "&#".base_convert(bin2hex(iconv('UTF-8',"UCS-4",$m)),16,10);
	    }
	    return $unicodeStr;
	}
	
	function unicodeDecode($unicode_str){
	    $json = '{"str":"'.$unicode_str.'"}';
	    $arr = json_decode($json,true);
	    if(empty($arr)) return '';
	    return $arr['str'];
	}
	function app_page($count, $one_page, $page, $url) {
		$pnums = ceil($count / $one_page);//应该多少页
		if($pnums==0)return '<li><a href="'.$url.'1'.'">无</a></li>';
		if($page>$pnums)return '<li><a href="'.$url.'1'.'">页码错误点击返回第一页</a></li>';
		$appp = '';
		$go = $page+1;
		$out = $page-1;
		if($page == 1){
			$appp = $appp.'<li class="disabled"><span>«</span></li>';
		}else{
			$appp = $appp.'<li><a href="'.$url.$out.'">«</a></li>';
		}
	
		for($i=1;$i<=$pnums;$i++){
			$urlHome = $url.$i;
			//$urlHome = $uel_linshi.$i;
			if($page == $i){
				$appp = $appp.'<li class="active"><a href="'.$urlHome.'">'.$i.'</a></li>';
			}else{
				$appp = $appp.'<li><a href="'.$urlHome.'">'.$i.'</a></li>';
			}
			
		}
	
		if($page==$pnums){
			$appp = $appp.'<li class="disabled"><span>»</span></li>';
		}else{
			$appp = $appp.'<li><a href="'.$url.$go.'">»</a></li>';
		}
		return $appp;
	}

?>
