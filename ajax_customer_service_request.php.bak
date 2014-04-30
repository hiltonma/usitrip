<?php
//ajax_customer_service_request.php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require_once("includes/application_top.php");
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

$customer_id =intval($customer_id);
if($customer_id <=0 ){
	echo '[JS]closePopup(\'PopupTransferServiceRequest\');jQuery("#csrbtn2").hide();jQuery("#csrbtn1").show();popAlert("'.general_to_ajax_string(db_to_html("请重新登录后再进行该操作")).'");[/JS]';
	exit;
}

if($_POST['action'] == 'add_service_request' && $_POST['ajax'] == 'true'){
	$sql_data_array = array(
		'customers_id'=>$customer_id,
		'name'=>ajax_to_general_string($_POST['name']),
		'mobile_phone'=> ajax_to_general_string($_POST['mobile_phone']),
		'email'=> ajax_to_general_string($_POST['email']),
		'comment'=> ajax_to_general_string($_POST['comment']),
		'from_url'=>ajax_to_general_string($_POST['from_url']),
		'created_time'=>date('Y-m-d H:i:s' ,time())
		);
	tep_db_perform(TABLE_CUSTOMERS_SERVICE_REQUEST, html_to_db($sql_data_array));
	echo '[JS]jQuery("#CSR_FORM").hide();jQuery("#CSR_SUCCESS").show();jQuery("#csrbtn1").show();jQuery("#csrbtn2").show();[/JS]';
	$mail_to = 'service@usitrip.com';
	$to_name = 'Service Team';
	$mail_body = db_to_html('有用户提交了自定义服务申请，请前往<a href="'.tep_href_link('admin/index.php').'">'.tep_href_link('admin/index.php').'</a>处理: <br/>');
	foreach($sql_data_array as $k=>$v){
		$mail_body.= ucwords(str_replace('_',' ',$k)).":".$v."<br/>" ;
	}
	ob_flush();flush();	
	tep_mail($to_name, $mail_to, db_to_html('自定义服务申请#').$customer_id, $mail_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}else {
	echo 'error';
}


?>