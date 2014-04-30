<?php
/**
 * AJAX服务端 
 * 1.action=draw_full_address_input提供联动地址输入控件的服务端支持 
 * @author vincent 
 */
require('includes/application_top.php');
$ajax = true;
//--------------------------------------------------------------------------------------------联动地址栏
if($_GET['action'] == 'draw_full_address_input'){
	$refobj = ajax_to_general_string($_GET['refobj']);
	$country = ajax_to_general_string($_GET['country']);
	$state = ajax_to_general_string($_GET['state']);
	echo tep_draw_full_address_input($refobj, $country, $state);
	exit;
}
?>
