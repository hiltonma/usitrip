<?php
//check new account
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");   

require_once('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

if(tep_not_null($_GET['email_address'])){
	$sql = tep_db_query('SELECT customers_id, customers_firstname FROM `customers` WHERE customers_email_address="'.tep_db_prepare_input($_GET['email_address']).'" limit 1 ');
	$row = tep_db_fetch_array($sql);
	if((int)$row['customers_id']){
		//echo '2';	//已经存在该电子邮件帐户
		echo '[SUCCESS]';
		$customers_firstname = db_to_html(tep_db_prepare_input($row['customers_firstname']));
		if(!preg_match('/\d+/', $customers_firstname)){
			echo '[NAME]'.$customers_firstname.'[/NAME]'; //同伴1中文名
		}
		
	}else{
		echo '0';
	}
}
?>