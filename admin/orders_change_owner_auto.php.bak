<?php
/**
 * 订单归属定时清空脚本，在服务器端执行。每隔30分钟执行一次！
 * @author wtj
 */
require 'includes/configure.php';
$str_sql='SELECT 
DISTINCT 
o.orders_id FROM 
orders o,
(
SELECT 
orders_id,
products_id,
products_departure_date,
orders_products_payment_status 
FROM orders_products 
GROUP BY orders_id
ORDER BY 
products_departure_date)AS op where o.orders_paid=0 AND o.last_modified<"'.(date('Y-m-d H:i:s',time()-432000)).'" AND op.products_departure_date>="'.date('Y-m-d H:i:s').'" AND o.orders_status NOT IN (100152,6,100130) AND o.orders_id=op.orders_id AND op.orders_products_payment_status=0 AND o.owner_is_change=0';
$db = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
// echo $str_sql;
//432000
// var_dump($db);
$result = $db->query($str_sql);
$id_str='';
while($obj=$result->fetch_object()){
// 	echo $obj->orders_id;
	$id_str.=','.$obj->orders_id;
// 	break;
}
if($id_str){
	$str_sql='UPDATE orders set is_top=1, owner_id_change_str=CONCAT("'.date('m月d日').'，原归属'.'",orders_owners),orders_owners="", owner_is_change=1 WHERE orders_id in ('.substr($id_str, 1).')';
// 	echo $str_sql;
// 	exit;
	$db->query($str_sql);
}
echo substr($id_str, 1)."\r\n";
// echo 'SELECT DISTINCT o.orders_id FROM orders o,orders_products op,products p where o.orders_paid=0 AND o.last_modified<"'.(date('Y-m-d H:i:s',time()-432000)).'" AND o.orders_status NOT IN (100152,6,100130) AND o.orders_id=op.orders_id AND op.products_id=p.products_id AND p.products_status=1';