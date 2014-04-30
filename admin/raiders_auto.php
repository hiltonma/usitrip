<?php
/**
 * 攻略每日自动更新
 * @author wtj
 */
$_hi = (string)date('H:i');
if(($_hi >= '00:00' && $_hi < '00:30')||($_hi>='23:30'&&$_hi<'23:59')){//每天更新一次凌晨12点更新一次！
	require 'includes/configure.php';
	$str_sql='UPDATE raiders_article SET is_show=1,add_time=release_time WHERE is_show=2 AND release_time<"'.date('Y-m-d H:i:s',time()+86399).'" AND release_time<>"0000-00-00 00:00:00"';
	$db = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
	$db->query($str_sql);
	// echo 'SELECT DISTINCT o.orders_id FROM orders o,orders_products op,products p where o.orders_paid=0 AND o.last_modified<"'.(date('Y-m-d H:i:s',time()-432000)).'" AND o.orders_status NOT IN (100152,6,100130) AND o.orders_id=op.orders_id AND op.products_id=p.products_id AND p.products_status=1';
}