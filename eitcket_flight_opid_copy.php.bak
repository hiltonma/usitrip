<?php
require_once('includes/application_top.php');

$get_orders_products_id = tep_db_query("select op.orders_products_id, opt.orders_eticket_id  FROM `orders_product_eticket` opt, orders_products op WHERE opt.orders_id = op.orders_id AND opt.products_id = op.products_id and opt.orders_products_id=0 order by op.orders_id desc limit 3000 ");
while($row_op_id = tep_db_fetch_array($get_orders_products_id)){
	tep_db_query("update orders_product_eticket set orders_products_id = '".$row_op_id['orders_products_id']."' where orders_eticket_id = '".$row_op_id['orders_eticket_id']."'");
	echo "ineticket<br>";
}


$get_orders_products_id = tep_db_query("select op.orders_products_id, opt.orders_flight_id  FROM `orders_product_flight` opt, orders_products op WHERE opt.orders_id = op.orders_id AND opt.products_id = op.products_id and opt.orders_products_id=0 order by op.orders_id desc limit 3000 ");
while($row_op_id = tep_db_fetch_array($get_orders_products_id)){
	tep_db_query("update orders_product_flight set orders_products_id = '".$row_op_id['orders_products_id']."' where orders_flight_id = '".$row_op_id['orders_flight_id']."'");
	echo "inflight<br>";
}

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>