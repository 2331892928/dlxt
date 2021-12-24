<?php
//MySQL、MySQLi、SQLite 三合一数据库操作类
if(!defined('IN_CRONLITE'))exit();

$nomysqli=false;

if(defined('SQLITE')==true){
	class DB {
		var $link = null;

		function __construct($db_file){
			global $siteurl;
		$this->link = new PDO('sqlite:'.ROOT.'includes/sqlite/'.$db_file.'.db');
		if (!$this->link) die('Connection Sqlite failed.\n');
		return true;
        }

		function fetch($q){
			return $q->fetch();
		}
		function get_row($q){
			$sth = $this->link->query($q);
			return $sth->fetch();
		}
		function count($q){
			$sth = $this->link->query($q);
			return $sth->fetchColumn();
		}
		function query($q){
			return $this->result=$this->link->query($q);
		}
		function affected(){
			return $this->result->rowCount();
		}
		function error(){
			$error = $this->link->errorInfo();
			return '['.$error[1].'] '.$error[2];
		}
	}
}
elseif(extension_loaded('mysqli') && $nomysqli==false) {
    class DB {
        var $link = null;

        function __construct($db_host,$db_user,$db_pass,$db_name,$db_port){
            
            $this->link = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
            
            if (!$this->link) die('Connect Error (' . mysqli_connect_errno() . ') '.mysqli_connect_error());
            
            //mysqli_select_db($this->link, $db_name) or die(mysqli_error($this->link));
            
 
mysqli_query($this->link,"set sql_mode = ''");
 //字符转换，读库
mysqli_query($this->link,"set character set 'utf8'");
//写库
mysqli_query($this->link,"set names 'utf8'"); 
	return true;
	}
		function fetch($q){
			return mysqli_fetch_assoc($q);	
		}
		function get_row($q){
			$result = mysqli_query($this->link,$q);
			return mysqli_fetch_assoc($result);
		}
		function set($data) {
			$data_new = '';
			if($data!=NULL && is_array($data)){
				foreach ($data as $v=>$k){
					$data_new = $data_new."`".$v."`='".$k."',";
				}
			}
			$data_new = rtrim($data_new, ',');
			return $data_new;
		}
		function whereor($where_or) {
			$where_or_new = '';
			if($where_or!=NULL && is_array($where_or)){
				foreach ($where_or as $v=>$k){
					$where_or_new = $where_or_new."`".$v."`='".$k."' or ";
				}
				$where_or_new = rtrim($where_or_new, ' or ');
			}
			return $where_or_new;
		}
		function whereand($where_and) {
			$where_and_new = '';
			if($where_and!=NULL && is_array($where_and)){
				foreach ($where_and as $v=>$k){
					$where_and_new = $where_and_new."`".$v."`='".$k."' and ";
				}
			}
			$where_and_new = rtrim($where_and_new, ' and ');
			return $where_and_new;
		}
		function insert_and($insert) {
			$insert_new = '(';
			if($insert!=NULL && is_array($insert)){
				foreach ($insert as $v=>$k){
					$insert_new = $insert_new.'\''.$k.'\',';
				}
			}
			$insert_new = rtrim($insert_new, ',');
			return $insert_new.')';
		}
		// function get_row_all($q){
		// 	$result = mysqli_query($this->link,$q);
		// 	return mysqli_fetch_assoc($result);
		// }
		function count($q){
			$result = mysqli_query($this->link,$q);
			$count = mysqli_fetch_array($result);
			return $count[0];
		}
		function query($q){
			return mysqli_query($this->link,$q);
		}
		function escape($str){
			return mysqli_real_escape_string($this->link,$str);
		}
		function insert($q){
			if(mysqli_query($this->link,$q))
				return mysqli_insert_id($this->link); 
			return false;
		}
		function affected(){
			return mysqli_affected_rows($this->link);
		}
		function insert_array($table,$array){
			$q = "INSERT INTO `$table`";
			$q .=" (`".implode("`,`",array_keys($array))."`) ";
			$q .=" VALUES ('".implode("','",array_values($array))."') ";
			
			if(mysqli_query($this->link,$q))
				return mysqli_insert_id($this->link);
			return false;
		}
		function error(){
			$error = mysqli_error($this->link);
			$errno = mysqli_errno($this->link);
			return '['.$errno.'] '.$error;
		}
		function close(){
			$q = mysqli_close($this->link);
			return $q;
		}
	}
} else { // we use the old mysql
	class DB {
		var $link = null;

		function __construct($db_host,$db_user,$db_pass,$db_name,$db_port){

		$this->link = @mysql_connect($db_host.':'.$db_port, $db_user, $db_pass);
            
		if (!$this->link) die('Connect Error (' . mysql_errno() . ') '.mysql_error());
            
			mysql_select_db($db_name, $this->link) or die(mysql_error($this->link));

mysql_query("set sql_mode = ''");
//字符转换，读库
mysql_query("set character set 'utf8'");
//写库
mysql_query("set names 'utf8'"); 

	return true;
		}
		function fetch($q){
			return mysql_fetch_assoc($q);
		}
		function get_row($q){
			$result = mysql_query($q, $this->link);
			return mysql_fetch_assoc($result);
		}
		function count($q){
			$result = mysql_query($q, $this->link);
			$count = mysql_fetch_array($result);
			return $count[0];
		}
        function query($q){
			return mysql_query($q, $this->link);
		}
		function escape($str){
			return mysql_real_escape_string($str, $this->link);
		}
		function affected(){
			return mysql_affected_rows($this->link);
		}
		function insert($q){
			if(mysql_query($q, $this->link))
				return mysql_insert_id($this->link);
			return false;
		}
		function insert_array($table,$array){
			$q = "INSERT INTO `$table`";
			$q .=" (`".implode("`,`",array_keys($array))."`) ";
			$q .=" VALUES ('".implode("','",array_values($array))."') ";

			if(mysql_query($q, $this->link))
				return mysql_insert_id($this->link);
			return false;
		}
		function error(){
			$error = mysql_error($this->link);
			$errno = mysql_errno($this->link);
			return '['.$errno.'] '.$error;
		}
		function close(){
			$q = mysql_close($this->link);
			return $q;
		}
	}

}
?>