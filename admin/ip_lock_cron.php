<?php
/**
 * 自动封锁和解封IP，由服务器端每隔1分钟执行1次。
 */
if(isset($_SERVER['HTTP_HOST'])){
	die('此程序只能在服务器端执行！'."\n\n");
}
require_once 'includes/configure.php';
require_once 'includes/classes/ipTables.php';
$o = new ipTables(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
echo $o->drop_ip();
echo $o->undrop_ip();
echo "\n\n";
?>