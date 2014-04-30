<?php
require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'ajax_encoding_control.php');
$customers_id = (int)$_GET['customers_id'];
$customers_referer_type = (int)$_GET['customers_referer_type'];
if((int)$customers_id){
	tep_db_query('UPDATE `customers` SET `customers_referer_type` = "'.(int)$customers_referer_type.'" WHERE `customers_id` = "'.(int)$customers_id.'" LIMIT 1; ');
}
?>