<?php
/**
 * @author yichi.sun@usitrip.com
 * @time 2011-08-18
 */
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
set_time_limit(0);

$smsajax = true;
require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'ajax_encoding_control.php');
require('sms_send.php');

//每天自动发一次短信给满足需求的客户
$today = date('Y-m-d');
$cron_sql = tep_db_query('SELECT * FROM `cron` WHERE cron_start_date <= "'.$today.'" AND (cron_latest_send_date < "'.$today.'" || cron_latest_send_date="0000-00-00") AND cron_ation_state!="1" AND cron_state ="true"');
//说明:cron_state不同于cron_ation_state，cron_ation_state是指任务目前的执行状态，cron_state是指是否打开任务计划功能。

$end_greeting_message_remind = false;
while($cron_rows = tep_db_fetch_array($cron_sql)){
	//参团结束后的问候短信
	if($cron_rows['cron_code']=='EndGreetingMessageRemind'){
		$end_greeting_message_remind = true;
		echo '参团结束后的问候短信: '.(string)$end_greeting_message_remind."\n\n";
	}
}

//测试人手机号码
$test_phone_arr = array(
	//'andy'=>'13980965011', 
	//'richard'=>'18981831192', 
	//'tracy'=>'18780129392', 
	//'joanna'=>'13547864296', 
	//'gavin'=>'13699464385', 
	'yichi'=>'13880695761', 
);

//参团结束当天下午8：00给旅客发送问候短信，若有延住则不发送
if(0 && $end_greeting_message_remind == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="EndGreetingMessageRemind" ');
	
	$now_time = time();
	$send_sms_time = strtotime($today)+20*3600;
	$sleep_time = $send_sms_time - $now_time;
	$qry_departure_info = "SELECT o.orders_id, op.orders_products_id, op.products_id, c.customers_telephone, c.customers_mobile_phone, c.customers_cellphone, c.confirmphone FROM orders o, orders_products op, products p, customers c WHERE o.orders_status>99999 AND o.orders_status!=100004 AND o.orders_status!=100005 AND o.orders_status!=100036 AND ADDDATE(DATE(op.products_departure_date), (p.products_durations-1))='".$today."' AND op.orders_id = o.orders_id AND p.products_id = op.products_id AND c.customers_id = o.customers_id";
	$res_departure_info = tep_db_query($qry_departure_info);
	$num = tep_db_num_rows($res_departure_info);
	if($num>0 && $sleep_time>0 && $sleep_time<=72000){
		echo '等待时间：'.$sleep_time.'秒'."\n\n";
		sleep($sleep_time);
		while($row_departure_info = tep_db_fetch_array($res_departure_info)){
			//判断是否有延后离开的延住信息，无延住则发短信问候
			/*
			$tour_eticket_hotel = tep_get_products_eticket_hotel($row_departure_info['products_id'],$languages_id);
			$tour_eticket_hotel_arr = explode('!##!',$tour_eticket_hotel);
			$max = count($tour_eticket_hotel_arr)-1;
			$last_day_hotel = strtolower(trim($tour_eticket_hotel_arr[$max]));
			if($max==0 || $last_day_hotel=='' || $last_day_hotel=='不提供' || $last_day_hotel=='n/a'){
			*/
			$extended_hotel_sql = 'SELECT count(1) as cnt FROM `orders_products_extended_hotel` WHERE orders_id="'.(int)$row_departure_info['orders_id'].'" AND orders_products_id="'.(int)$row_departure_info['orders_products_id'].'" AND eh_type="after" AND (hotel_confirm_number!=NULL OR hotel_confirm_number!="")';
			$extended_hotel_query = tep_db_query($extended_hotel_sql);
			$extended_hotel_result = tep_db_fetch_array($extended_hotel_query);
			if($extended_hotel_result['cnt']<1){
				$phone = '';
				$result = check_phone($row_departure_info['confirmphone']);
				if(!empty($result))$phone = $result[0];
				else{
					$result = check_phone($row_departure_info['customers_cellphone']);
					if(!empty($result))$phone = $result[0];
					else{
						$result = check_phone($row_departure_info['customers_mobile_phone']);
						if(!empty($result))$phone = $result[0];
						else{
							$result = check_phone($row_departure_info['customers_telephone']);
							if(!empty($result))$phone = $result[0];
						}
					}
				}
				
				if(!empty($phone)){
					//给参团结束的旅客发送问候短信
					foreach($test_phone_arr as $test_phone){
						$content = $phone."分享您的旅途经历、旅途照片等到走四方网，或通过电话，邮件，评论等方式提供您宝贵建议，不仅能为您的朋友提供旅行参考，还能享有走四方积分的优惠奖励。真诚期待再次为您服务。";
						if(sms_send($test_phone, $content, 'GB2312')=='1'){
							echo $phone."旅客参团结束的问候短信发送成功"."\n\n";
						}
						else{
							echo $phone."旅客参团结束的问候短信发送失败"."\n\n";
						}
					}
				}
			}
		}
	}
	echo '参团结束后的问候短信 OK！'."\n\n";
}

echo "Done";
?>