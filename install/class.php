<?php

/**
 * Class install
 */
class install
{
    /**
     * @param $string
     * @param int $force
     * @param bool $strip
     * @return array|string
     */
    public function daddslashes($string, $force = 0, $strip = FALSE)
    {

        !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
        if (!MAGIC_QUOTES_GPC || $force) {
            if (is_array($string)) {
                foreach ($string as $key => $val) {
                    $string[$key] = install::daddslashes($val, $force, $strip);
                }
            } else {
                $string = htmlspecialchars($strip ? stripslashes($string) : $string);
            }
        }
        return $string;
    }

    /**
     * @param $dbconfig
     * @return array
     * 文件修改操作
     */
    public function ModifyFileContents($dbconfig)
    {
        $FILE = '../includes/config_db.php';
		$data = "<?php
/*数据库配置*/
define('DB_HOST','{$dbconfig['host']}');//数据库连接地址，默认：localhost或127.0.0.1
define('DB_PORT','{$dbconfig['port']}');//数据库端口
define('DB_USER','{$dbconfig['user']}');//数据库账号
define('DB_PASSWD','{$dbconfig['pwd']}');//数据库密码
define('DB_NAME','{$dbconfig['dbname']}');//数据库名称
define('DB_PRE','{$dbconfig['dbfirst']}');//数据库前缀
define('HT_USER','{$dbconfig['htuser']}');//后台账号
define('HT_PASS','{$dbconfig['htpwd']}');//后台密码
?>
";
        $numbytes = file_put_contents($FILE, $data);
        if ($numbytes) {
            return ['code' => 1, 'msg' => '数据更新成功！'];
        } else {
            return ['code' => -1, 'msg' => '写入失败或者文件(config_db.php)没有写入权限，注意检查！'];
        }
    }
}