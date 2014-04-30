<?php
/**
 * @author yichi.sun@usitrip.com
 * @time 2011-08-18
 */
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
$smsajax = true;
require('includes/application_top.php');
require('includes/ajax_encoding_control.php');
require('sms_send.php');

//每天自动发一次短信给满足需求的客户
$today = date('Y-m-d');
$cron_sql = tep_db_query('SELECT * FROM `cron` WHERE cron_start_date <= "'.$today.'" AND (cron_latest_send_date < "'.$today.'" || cron_latest_send_date="0000-00-00") AND cron_ation_state!="1" AND cron_state ="true"');
//说明:cron_state不同于cron_ation_state，cron_ation_state是指任务目前的执行状态，cron_state是指是否打开任务计划功能。

$weather_forecast_remind = false;
$preparatory_work_remind = false;
$shopping_sms = false;
$shopping_not_closing = false;
$not_logged_invited_six = false;
$end_greeting_message_remind = false;

while($cron_rows = tep_db_fetch_array($cron_sql)){
	//参团前天气预报提醒
	if($cron_rows['cron_code']=='WeatherForecastRemind'){
		$weather_forecast_remind = true;
		echo '参团前天气预报提醒: '.(string)$weather_forecast_remind."\n\n";
	}
	//参团前准备工作提醒
	if($cron_rows['cron_code']=='PreparatoryWorkRemind'){
		$preparatory_work_remind = true;
		echo '参团前准备工作提醒: '.(string)$preparatory_work_remind."\n\n";
	}    
    //购物车内产品7天未下订单
	if($cron_rows['cron_code']=='ShoppingSMS'){
		$shopping_sms = true;
        echo '购物车内产品7天未下订单: '.(string)$shopping_sms."\n\n";
	}
	//产品订单3/7天未结账
    if($cron_rows['cron_code']=='ShoppingNotClosing'){
		$shopping_not_closing = true;
        echo '产品订单3/7天未结账: '.(string)$shopping_not_closing."\n\n";
	}
	//用户行程结束后半年没有登录，发短信问候并邀请再次登录
    if($cron_rows['cron_code']=='NotLoggedInvitedSix'){
		$not_logged_invited_six = true;
		echo '用户行程结束后半年没有登录: '.(string)$not_logged_invited_six."\n\n";
	}
	//参团结束后的问候短信
	if($cron_rows['cron_code']=='EndGreetingMessageRemind'){
		$end_greeting_message_remind = true;
		echo '参团结束后的问候短信: '.(string)$end_greeting_message_remind."\n\n";
	}
}


tep_db_query('update `cron` set cron_ation_state = "1" ');

//测试人手机号码
$test_phone_arr = array(
	//'andy'=>'13980965011', 
	//'richard'=>'18981831192', 
	//'tracy'=>'18780129392', 
	//'joanna'=>'13547864296', 
	//'gavin'=>'13699464385', 
	'yichi'=>'13880695761', 
	//'lollipop'=>'15828022554', 
);

//参团前天气预报提醒
if((preg_match('/'.preg_quote('[天气预报提醒]').'/',CPUNC_USE_RANGE) || preg_match('/'.preg_quote('[预祝出行愉快]').'/',CPUNC_USE_RANGE)) && $weather_forecast_remind == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="WeatherForecastRemind" ');
	$qry_departure_info = "SELECT o.orders_id, op.products_name, DATE(op.products_departure_date) as products_departure_date, c.customers_telephone, c.customers_mobile_phone, c.customers_cellphone, c.confirmphone FROM orders o, orders_products op, customers c WHERE o.orders_status>99999 AND o.orders_status!=100004 AND o.orders_status!=100005 AND o.orders_status!=100036 AND DATE_SUB(DATE(op.products_departure_date), INTERVAL 1 DAY)='".$today."' AND op.orders_id = o.orders_id AND c.customers_id = o.customers_id";
	$res_departure_info = tep_db_query($qry_departure_info);
	while($row_departure_info = tep_db_fetch_array($res_departure_info)){
		//在参团前一天给旅客发送天气预报信息
		$phone = '';
		$result = check_phone($row_departure_info['confirmphone']);
		if(!empty($result))$phone = $result[0];
		else{
			$result = check_phone($row_departure_info['customers_cellphone']);
			if(!empty($result))$phone = $result[0];
			else{
				$result = check_phone($row_flight_info['customers_mobile_phone']);
				if(!empty($result))$phone = $result[0];
				else{
					$result = check_phone($row_flight_info['customers_telephone']);
					if(!empty($result))$phone = $result[0];
				}
			}
		}
		
		if(!empty($phone)){
			//对参团前旅客发送天气预报短信提醒
            if(preg_match('/'.preg_quote('[天气预报提醒]').'/',CPUNC_USE_RANGE)){
				$content = "旅客您好！根据您的参团信息特发未来几天的天气情况，如下：";
				if(sms_send($phone, $content, 'GB2312')=='1'){
					echo $phone."旅客参团前天气预报短信发送成功"."\n\n";
				}
				else{
					echo $phone."旅客参团前天气预报短信发送失败"."\n\n";
				}
            }
			//预祝旅客出行愉快短信
			if(preg_match('/'.preg_quote('[预祝出行愉快]').'/',CPUNC_USE_RANGE)){
				$content = "亲爱的旅客，您所预定的走四方网".$row_departure_info['products_name']."于".$row_departure_info['products_departure_date']."即将开始完美旅程，祝您旅途愉快！";
				if(sms_send($phone, $content, 'GB2312')=='1'){
					echo $phone."预祝旅客出行愉快短信发送成功"."\n\n";
				}
				else{
					echo $phone."预祝旅客出行愉快短信发送失败"."\n\n";
				}
			}
		}
	}
	echo '参团前天气预报提醒 OK！'."\n\n";
}

//参团前一周准备工作提醒
if(preg_match('/'.preg_quote('[准备工作提醒]').'/',CPUNC_USE_RANGE) && $preparatory_work_remind == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="PreparatoryWorkRemind" ');
	$qry_departure_info = "SELECT o.orders_id, op.products_name, c.customers_telephone, c.customers_mobile_phone, c.customers_cellphone, c.confirmphone FROM orders o, orders_products op, customers c WHERE o.orders_status>99999 AND o.orders_status!=100004 AND o.orders_status!=100005 AND o.orders_status!=100036 AND DATE_SUB(DATE(op.products_departure_date), INTERVAL 7 DAY)='".$today."' AND op.orders_id = o.orders_id AND c.customers_id = o.customers_id";
	$res_departure_info = tep_db_query($qry_departure_info);
	while($row_departure_info = tep_db_fetch_array($res_departure_info)){
		//在参团前一周给旅客发送准备工作信息
		$phone = '';
		$result = check_phone($row_departure_info['confirmphone']);
		if(!empty($result))$phone = $result[0];
		else{
			$result = check_phone($row_departure_info['customers_cellphone']);
			if(!empty($result))$phone = $result[0];
			else{
				$result = check_phone($row_flight_info['customers_mobile_phone']);
				if(!empty($result))$phone = $result[0];
				else{
					$result = check_phone($row_flight_info['customers_telephone']);
					if(!empty($result))$phone = $result[0];
				}
			}
		}
		
		if(!empty($phone)){
			//对参团前旅客发送准备工作短信提醒
			$content = "您预订的".$row_departure_info['products_name']."将于七天后开始，请您尽早做好出行准备。走四方网倾力打造的《出行指南》能为您提供相关出行资讯，请登陆走四方网下载了解！预祝您拥有完美假期！";
			if(sms_send($phone, $content, 'GB2312')=='1'){
				echo $phone."旅客参团前准备工作短信发送成功"."\n\n";
			}
			else{
				echo $phone."旅客参团前准备工作短信发送失败"."\n\n";
			}
		}
	}
	echo '参团前准备工作提醒 OK！'."\n\n";
}

//购物车内产品7天未下订单提醒
if(preg_match('/'.preg_quote('[未下订单提醒]').'/',CPUNC_USE_RANGE) && $shopping_sms == true){
    $oday = date("Ymd", mktime(0,0,0,date("m"),date("d")-7,date("Y")));
    tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="ShoppingSMS" ');  
    $sql = "SELECT cb.customers_id, cu.customers_telephone, cu.customers_mobile_phone, cu.customers_cellphone, cu.confirmphone FROM customers_basket as cb, customers as cu WHERE cb.customers_id = cu.customers_id and customers_basket_date_added = (".$oday.")  group by `customers_id`";
	$query = tep_db_query($sql);
    while($row = tep_db_fetch_array($query)){
    	//对购物车内的产品7天未下订单的客户发送短信提醒
		$phone = '';
		$result = check_phone($row['confirmphone']);
		if(!empty($result))$phone = $result[0];
		else{
			$result = check_phone($row['customers_cellphone']);
			if(!empty($result))$phone = $result[0];
			else{
				$result = check_phone($row['customers_mobile_phone']);
				if(!empty($result))$phone = $result[0];
				else{
					$result = check_phone($row['customers_telephone']);
					if(!empty($result))$phone = $result[0];
				}
			}
		}
		
		if(!empty($phone)){
			//对购物车内的产品7天未下订单的客户发送短信提醒
			$content = "走四方网现有火爆团购让利促销，畅销行程买二送二，会员尊享多倍积分等优惠活动。即刻预订即刻享受优惠。详询：400-637-8888或1-888-887-2816。";
			if(sms_send($phone, $content, 'GB2312')=='1'){
				echo $phone."购物车内产品7天未下订单的提醒短信发送成功"."\n\n";
			}
			else{
				echo $phone."购物车内产品7天未下订单的提醒短信发送失败"."\n\n";
			}
		}
    }
	echo '购物车内产品7天未下订单提醒 OK！'."\n\n";
}

//产品订单3/7天未结账提醒
if(preg_match('/'.preg_quote('[未结账提醒]').'/',CPUNC_USE_RANGE) && $shopping_not_closing == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="ShoppingNotClosing" ');  
	$sql="SELECT orders_id FROM orders_status_history WHERE (orders_status_id = '1' or orders_status_id = '100054' or orders_status_id = '100060' or orders_status_id = '100094') GROUP BY orders_id";
	$orid = tep_db_query($sql);//得到状态已付款的ID
	$notin = '';
	$k = 1;
	while($row = tep_db_fetch_array($orid)){
		if($k==1){
			$notin .= "'".$row['orders_id']."'";
		}else{
			$notin .= ",'".$row['orders_id']."'";
		}
		$k++;
	}
	
	$day7 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-7,date("Y")));
	$day3 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-3,date("Y")));
	//有价格的SQL
	/*$sql =
	"select ord.date_purchased, ord.orders_id, cu.customers_telephone, cu.customers_mobile_phone, cu.customers_cellphone, cu.confirmphone, orders_total.value from
	orders as ord, customers as cu, orders_total where 
	ord.orders_id in 
	(".$notin.") and 
	(ord.date_purchased like '".$day7."%' or ord.date_purchased like '".$day3."%') and 
	ord.customers_id=cu.customers_id and ord.orders_id=orders_total.orders_id and orders_total.class='ot_total' ";//group by ord.customers_id 如果不需要商品名称*/
	
	//没有价格的SQL
	$sql =
	"select ord.date_purchased, ord.orders_id, cu.customers_telephone, cu.customers_mobile_phone, cu.customers_cellphone, cu.confirmphone from
	orders as ord, customers as cu where 
	ord.orders_id in 
	(".$notin.") and 
	(ord.date_purchased like '".$day7."%' or ord.date_purchased like '".$day3."%') and 
	ord.customers_id=cu.customers_id";//group by ord.customers_id 如果不需要商品名称
	
	$query = tep_db_query($sql);
	while($row = tep_db_fetch_array($query)){
		//对已下订单3/7天未结账的客户发送短信提醒
		$phone = '';
		$result = check_phone($row['confirmphone']);
		if(!empty($result))$phone = $result[0];
		else{
			$result = check_phone($row['customers_cellphone']);
			if(!empty($result))$phone = $result[0];
			else{
				$result = check_phone($row['customers_mobile_phone']);
				if(!empty($result))$phone = $result[0];
				else{
					$result = check_phone($row['customers_telephone']);
					if(!empty($result))$phone = $result[0];
				}
			}
		}
		
		if(!empty($phone)){
			//对已下订单3/7天未结账的客户发送短信提醒
			//尊敬的顾客，您的订单（#".$row['orders_id']."）已经生成。请您尽快安排支付订单款项($".$row['value'].")，以便我们尽早为您处理订单，安排行程。如果您需要我们的协助完成，请您拨打我们的客服热线888-887-2816（美国）4006-333-926（中国）。我们将竭诚为您服务。
			$content = "尊敬的用户，为尽早替您预留座位，请您尽快支付订单款项。如需帮助，请拨打客服热线1-888-887-2816（美）4006-333-926（中）。我们将竭诚为您服务。";
			if(sms_send($phone, $content, 'GB2312')=='1'){
				echo $phone."客户已下订单3/7天未结账的提醒短信发送成功"."\n\n";
			}
			else{
				echo $phone."客户已下订单3/7天未结账的提醒短信发送失败"."\n\n";
			}
		}
	}
	echo '产品订单3/7天未结账提醒 OK！'."\n\n";
}

//用户行程结束后半年没有登录，发短信问候并邀请再次登录
if(preg_match('/'.preg_quote('[未登录提醒]').'/',CPUNC_USE_RANGE) && $not_logged_invited_six == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="NotLoggedInvitedSix" ');
	$sql="SELECT orders_id FROM orders_status_history WHERE orders_status_id = '100006' GROUP BY orders_id";
	$query = tep_db_query($sql);//行程结束的订单
	$i = 1;
	$in = '';
	while($row = tep_db_fetch_array($query)){
		if($i == 1){
			$in .= "'".$row['orders_id']."'";
		}else{
			$in .= ",'".$row['orders_id']."'";
		}
		$i++;
	}
	
	$sql = "select customers_id from orders as ord where ord.orders_id in(".$in.") GROUP BY ord.customers_id";//得到行程完成的用户ID，为了安全（GROUP BY ID）一个帐号可以同时存在几种订单
	$logid = tep_db_query($sql);
	$ik = 1;
	$lin = '';
	while($rows = tep_db_fetch_array($logid)){
		if($ik == 1){
			$lin .= "'".$rows['customers_id']."'";
		}else{
			$lin .= ",'".$rows['customers_id']."'";
		}
		$ik++;
	}
	
	$sql = "select customers_id,user_score_history_date,user_score_history_id from user_score_history where customers_id in (".$lin.") order by customers_id desc,user_score_history_date desc";
	$logged = tep_db_query($sql);
	$ari = 0;
	$temp = '';
	$cuin = '';
	while($ro = tep_db_fetch_array($logged)){
		if($ro['customers_id'] != $temp){
			if($ari == 0){
				$cuin .= "'".$ro['user_score_history_id']."'";
			}else{
				$cuin .= ",'".$ro['user_score_history_id']."'";
			}
			$temp = $ro['customers_id'];
			$ari++;
		}
	}
	
	$day186 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-186,date("Y")));
	$sql = "select cu.customers_telephone, cu.customers_mobile_phone, cu.customers_cellphone, cu.confirmphone from 
	user_score_history as user, customers as cu where 
	user.user_score_history_id in(".$cuin.") and user.user_score_history_date like '".$day186."%' and 
	user.customers_id = cu.customers_id";
	$resu = tep_db_query($sql);
	while($rownum = tep_db_fetch_array($resu)){
		//用户行程结束后半年没有登录，发短信问候并邀请再次登录
		$phone = '';
		$result = check_phone($rownum['confirmphone']);
		if(!empty($result))$phone = $result[0];
		else{
			$result = check_phone($rownum['customers_cellphone']);
			if(!empty($result))$phone = $result[0];
			else{
				$result = check_phone($rownum['customers_mobile_phone']);
				if(!empty($result))$phone = $result[0];
				else{
					$result = check_phone($rownum['customers_telephone']);
					if(!empty($result))$phone = $result[0];
				}
			}
		}
		
		if(!empty($phone)){
			//用户行程结束后半年没有登录，发送短信提醒
			$content = "走四方网精选畅销行程火热促销中，低价团购还买二送二，购买立省更有多倍积分送不停。欢迎登陆了解优惠详情。";
			if(sms_send($phone, $content, 'GB2312')=='1'){
				echo $phone."用户行程结束后半年没有登录提醒短信发送成功"."\n\n";
			}
			else{
				echo $phone."用户行程结束后半年没有登录提醒短信发送失败"."\n\n";
			}
		}
	}
	echo '用户行程结束后半年没有登录提醒 OK！'."\n\n";
}

//参团结束当天下午8：00给旅客发送问候短信，若有延住则不发送
if(preg_match('/'.preg_quote('[行程结束问候]').'/',CPUNC_USE_RANGE) && $end_greeting_message_remind == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="EndGreetingMessageRemind" ');
	$qry_departure_info = "SELECT o.orders_id, op.orders_products_id, op.products_id, c.customers_telephone, c.customers_mobile_phone, c.customers_cellphone, c.confirmphone FROM orders o, orders_products op, products p, customers c WHERE o.orders_status>99999 AND o.orders_status!=100004 AND o.orders_status!=100005 AND o.orders_status!=100036 AND ADDDATE(DATE(op.products_departure_date), (p.products_durations-1))='".$today."' AND op.orders_id = o.orders_id AND p.products_id = op.products_id AND c.customers_id = o.customers_id";
	$res_departure_info = tep_db_query($qry_departure_info);
	$num = tep_db_num_rows($res_departure_info);
	if($num>0){
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
					//参团结束当天下午8：00给旅客发送问候短信
					$content = "分享您的旅途经历、旅途照片等到走四方网，或通过电话，邮件，评论等方式提供您宝贵建议，不仅能为您的朋友提供旅行参考，还能享有走四方积分的优惠奖励。真诚期待再次为您服务。";
					$sendTime = date('Ymd').'200000';
					//$sendTime = '20110819143400';
					if(sms_send($phone, $content, 'GB2312', $sendTime)=='1'){
						echo $phone."旅客参团结束的问候短信发送成功"."\n\n";
					}
					else{
						echo $phone."旅客参团结束的问候短信发送失败"."\n\n";
					}
				}
			}
		}
	}
	echo '参团结束后的问候短信 OK！'."\n\n";
}

tep_db_query('update `cron` set cron_ation_state = "0" ');


echo "Done";
?>