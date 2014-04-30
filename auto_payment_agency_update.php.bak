<?php
require('includes/application_top.php');

$get_original_listing = tep_db_query("select ta.agency_name, p.agency_id, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, p.provider_tour_code from orders_products_payment_history opph, tour_travel_agency as ta, products as p, orders_products as op where cast(opph.orders_products_id as signed) = op.orders_products_id and p.agency_id=ta.agency_id and op.products_id=p.products_id and opph.payment_agency_id <= 0 order by opph.ord_prod_payment_id desc");

while($row_original_listing = tep_db_fetch_array($get_original_listing)){
	echo $row_original_listing['agency_name'];
	echo '<br>';
	echo "update orders_products_payment_history set payment_agency_id = '".$row_original_listing['agency_id']."' where ord_prod_payment_id = '".$row_original_listing['ord_prod_payment_id']."'";
	echo '<br><br>';
	tep_db_query("update orders_products_payment_history set payment_agency_id = '".$row_original_listing['agency_id']."' where ord_prod_payment_id = '".$row_original_listing['ord_prod_payment_id']."'");
}
?>