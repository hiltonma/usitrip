<?php
require('includes/application_top.php');
if(tep_not_null($_GET['mail_type']) ){
	$email_address = tep_db_prepare_input($_GET['mail_address']);
	$mail_type = tep_db_prepare_input($_GET['mail_type']);
	$orders_id = (int)$_GET['orders_id'];
	$newsletters_id = (int)$_GET['newsletters_id'];
	$email_track_date = date('Y-m-d H:i:s');
	
	$key_field = $_GET['key_field'];
	$key_id = (int)$_GET['key_id'];
	
	$ex_where ='';
	if((int)$orders_id){
		//$ex_where .= ' AND orders_id="'.(int)$orders_id.'" ';
		$ex_where .= ' AND key_field="orders_id" AND key_id="'.(int)$orders_id.'" ';
		
		$key_field = "orders_id";
		$key_id = (int)$orders_id;
		
	}
	//E-ticket Log Start
	if((int)$orders_eticket_log_id){
		$ex_where .= ' AND orders_eticket_log_id="'.(int)$orders_eticket_log_id.'" ';
	}
	//E-ticket Log End
	if((int)$newsletters_id){
		//$ex_where .= ' AND newsletters_id="'.(int)$newsletters_id.'" ';
		$ex_where .= ' AND key_field="newsletters_id" AND key_id="'.(int)$newsletters_id.'" ';
		
		$key_field = "newsletters_id";
		$key_id = (int)$newsletters_id;
	}
	
	$check_sql = tep_db_query('SELECT email_track_id FROM `email_track` WHERE email_address="'.$email_address.'" AND email_type="'.$mail_type.'" '.$ex_where .' Limit 1 ');
	$check_row = tep_db_fetch_array($check_sql);
	if(!(int)$check_row['email_track_id'] || ($email_address && $mail_type=='newsletter')){	//newsletter时邮箱可以为空(因为如果用外部方式发邮件是不能自定义收件人的邮箱地址)
	
		
		$sql_data_array = array('email_address' => $email_address,
								'email_type' => $mail_type,
								'email_track_date' => $email_track_date,
								'key_field' => $key_field,
								'key_id' => $key_id,
								//E-ticket Log Start
								'orders_eticket_log_id' => $orders_eticket_log_id
								//E-ticket Log End
								//'orders_id' => $orders_id,
								//'newsletters_id'=> $newsletters_id
								);
		tep_db_perform('email_track', $sql_data_array);
	}else{
		tep_db_query('UPDATE `email_track` SET `email_track_date` = "'.$email_track_date.'" WHERE `email_track_id` = "'.(int)$check_row['email_track_id'].'" ');
	}
	tep_db_query('OPTIMIZE TABLE `email_track` ');
}
?>