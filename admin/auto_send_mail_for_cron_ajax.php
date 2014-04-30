<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
ini_set("max_execution_time", 3600); //1小时有效期
set_time_limit(0);

//每天自动发一次邮件给未完成订单的客户
//总开关
$off_on = true;
if($_SERVER['HTTP_HOST']=="208.109.123.18.php5-22.dfw1-2.websitetestlink.com"){
	$off_on=false;
}
if($off_on==false){
	echo '已经关闭发邮件功能！';
	exit;
}
require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'ajax_encoding_control.php');



//总开关
//$off_on = false;
if(IS_LIVE_SITES === true){	//只能在生产站使用此功能
	$off_on = true;
}
if($off_on==false){
	echo '已经关闭发短信功能！';
	exit;
}

$oops_you_left_something_in_your_basket = false;
$customers_point = false;
$customers_tours_back = false;
$orders_travel_companion_cancel = false;
$orders_travel_companion_note = false;
$coupon_expire_notes = false;

$today = date('Y-m-d');
$_sql = 'SELECT * FROM `cron` WHERE cron_start_date <= "'.$today.'" AND (cron_latest_send_date < "'.$today.'" || cron_latest_send_date="0000-00-00") AND cron_ation_state!="1" AND cron_state ="true"; ';
$cron_sql = tep_db_query($_sql);
//echo $_sql;exit;

//说明:cron_state不同于cron_ation_state，cron_ation_state是指任务目前的执行状态，cron_state是指是否打开任务计划功能。

while($cron_rows = tep_db_fetch_array($cron_sql)){

	$formatString = date('Y-m-d',strtotime('-'.$cron_rows['cron_interval_day_num'].' day'));
	if($formatString >= $cron_rows['cron_latest_send_date']){
		
		if($cron_rows['cron_code']=='OrdersTravelCompanionCancel'){
			$orders_travel_companion_cancel = true; //结伴同游订单因付款不及时导致取消，这个没有日期限制
			echo '1结伴同游订单取消: '.(string)$orders_travel_companion_cancel."\n\n";
		}
		if($cron_rows['cron_code']=='OrdersTravelCompanionNote'){
			$orders_travel_companion_note = true; //结伴同游订单付款提醒通知
			echo '2结伴同游订单付款提醒: '.(string)$orders_travel_companion_note."\n\n";
		}
		if($cron_rows['cron_code']=='ShoppingCartCall'){
			$oops_you_left_something_in_your_basket = true; //发客户购物车提醒邮件开关 true或false
			echo '3购物车提醒: '.(string)$oops_you_left_something_in_your_basket."\n\n";
		}
		
		if($cron_rows['cron_code']=='CustomersPoint'){
			$customers_point = true; //走四方积分余额提醒开关 true或false
			echo '4走四方积分余额提醒: '.(string)$customers_point."\n\n";
		}
		if($cron_rows['cron_code']=='TourAfterInvestigate'){
			$customers_tours_back = true; //旅游归来邮件调查开关 true或false
			echo '5旅游归来邮件调查: '.(string)$customers_tours_back."\n\n";
		}
		if($cron_rows['cron_code']=='CouponExpireNotes'){
			$coupon_expire_notes = true;
			echo '6优惠券到期提醒邮件: '.(string)$coupon_expire_notes."\n\n";
		}
	}
	
}

tep_db_query('update `cron` set cron_ation_state = "1" ');


//(1)结伴同游订单因付款不及时导致取消，并邮件通知，只发当天的
if($orders_travel_companion_cancel == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="OrdersTravelCompanionCancel" ');
	echo orders_travel_companion_cancel();
	echo '结伴同游订单取消邮件发送完毕。 OK！'."\n\n";
	
}
//(2)结伴同游订单提醒邮件，只提醒在规定时间内未付款的，邮件！
if($orders_travel_companion_note == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="OrdersTravelCompanionNote" ');
	echo orders_travel_companion_note();
	echo '结伴同游订单付款提醒邮件发送完毕。 OK！'."\n\n";
}

//(3)购物车提醒邮件：我们可以第二天发送一封邮件，然后隔一周后发送一封邮件，之后到1个月发送一份邮件，1个月以后每隔2个月再发送一次邮件。

tep_db_query('update `cron` set cron_latest_send_date = "'.$today.'" WHERE cron_code="ShoppingCartCall" ');

$todaynum = date('Ymd');

$interval_day = 2; //起始间隔天数
$interval_string = '7,30,60'; //第二次以后的间隔天数
$interval_array = explode(',', $interval_string);

$b4_2_days_time = time() - ( $interval_day *24*60*60);
$b4_2_days_date = date("Ymd",$b4_2_days_time);

if($oops_you_left_something_in_your_basket == true){

	for($i=0; $i<count($interval_array); $i++){
		
		$basket_sql = tep_db_query('SELECT * FROM `customers_basket` cb, customers c WHERE cb.customers_id = c.customers_id AND cb.customers_basket_date_added <= "'.$b4_2_days_date.'" AND cb.latest_mail_date <= "'.$b4_2_days_date.'" AND cb.next_send_date <= "'.$todaynum.'" AND customers_newsletter ="1" group by cb.customers_id order by cb.customers_basket_id desc ');
		while($basket_rows = tep_db_fetch_array($basket_sql)){
			if(!tep_not_null($basket_rows['latest_mail_date'])){
				$latest_mail_date = $todaynum;
			}else{
				$latest_mail_date = $basket_rows['latest_mail_date'];
			}
			
			if((strtotime($latest_mail_date) - strtotime($basket_rows['customers_basket_date_added']))/(24*60*60) ){
			
				$to_name = $basket_rows['customers_firstname'];
				$to_email_address = $basket_rows['customers_email_address'];
				$email_subject = SHOPPING_CART_CALL;
				$from_email_name = STORE_OWNER;
				$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
				$email_charset = $basket_rows['customers_char_set'];
				
				//howard added new eamil tpl
				$patterns = array();
				$patterns[0] = '{CustomerName}';
				$patterns[1] = '{images}';
				$patterns[2] = '{HTTP_SERVER}';
				$patterns[3] = '{HTTPS_SERVER}';
				$patterns[4] = '{ProductInfoPage}';
				$patterns[5] = '{EMAIL}';
				$patterns[6] = '{CONFORMATION_EMAIL_FOOTER}';
				
				$replacements = array();
				$replacements[0] = $to_name;
				$replacements[1] = HTTP_SERVER.'/email_tpl/images';
				$replacements[2] = HTTP_SERVER;
				$replacements[3] = HTTPS_SERVER;
				$replacements[4] = $product_link_page;
				$replacements[5] = $to_email_address;
				$replacements[6] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
				
				$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
				$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/oops_you_left_something_in_your_basket.tpl.html');
				$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
				
				$email_text = str_replace( $patterns ,$replacements, ($email_tpl));
				$email_text = preg_replace('/[[:space:]]+/',' ',$email_text);
		
				//howard added new eamil tpl end
				tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset );
				
				//设置下次发送日期
				$addday = 0;
				for($j=0; $j<count($interval_array); $j++){
					if((int)$basket_rows['sent_num'] == $j){
						$addday = $interval_array[$j];
						echo '+day: '.$addday.'<br>';
						echo 'send num: '.(int)$basket_rows['sent_num'].'<br>';
						break;
					}
				}
				if(!(int)$addday){
					$tmp_var = max((count($interval_array)-1), 0);
					$addday = $interval_array[$tmp_var];
						echo '+day: '.$addday.'<br>';
						echo 'send num: '.(int)$basket_rows['sent_num'].'<br>';
				}
				
				$next_send_date = date('Ymd', strtotime('+'.$addday.' day'));
				
				tep_db_query('update `customers_basket` set latest_mail_date = "'.$todaynum.'", sent_num = sent_num+1, next_send_date ="'.$next_send_date.'" WHERE customers_id="'.$basket_rows['customers_id'].'" ');
								
				echo $to_email_address."\n";
			}
			
		}	
		
	}
	
	echo '购物车提醒邮件 OK！'."\n\n";
}


//(4)走四方积分余额提醒
if($customers_point == true){
	require_once(DIR_FS_CATALOG.'includes/languages/schinese/my_points.php');
	require_once(DIR_WS_CLASSES . 'currencies.php');
	
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="CustomersPoint" ');
	send_point_to_customers();
	echo '走四方积分余额提醒 OK！'."\n\n";
}

//(5)旅游归来邮件调查
if($customers_tours_back == true){
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="TourAfterInvestigate" ');
	if(send_tours_back_to_customers()){
		echo '旅游归来邮件调查 OK！'."\n\n";
	}
}


//(6)取消已到期的结伴同游置顶贴
function close_top_bbs_for_travel_companion(){
	$the_date = date('Y-m-d H:i:s');
	$sql = tep_db_query('SELECT * FROM `travel_companion` WHERE bbs_type = 2 AND admin_id < 1 ');
	while($rows = tep_db_fetch_array($sql)){
		$end_date = date('Y-m-d H:i:s ', strtotime($rows['add_time']. ' + '.$rows['t_top_day'].' day'));
		if($the_date > $end_date){
			tep_db_query('update `travel_companion` SET remark = "结伴同游置顶到期，到期时间：'.$end_date.'。置顶天数：'.$rows['t_top_day'].' " , bbs_type = 100  WHERE t_companion_id="'.$rows['t_companion_id'].'" ');
		}
		//echo $rows['t_companion_id']."\t".$rows['t_top_day']."\t".$rows['add_time']."\t".date('Y-m-d H:i:s ', strtotime($rows['add_time']. ' + '.$rows['t_top_day'].' day'))."\n";
		
	}
}

//(7)优惠券将到期提醒
if($coupon_expire_notes==true){
	require_once(DIR_WS_CLASSES . 'currencies.php');
	tep_db_query('update `cron` set cron_latest_send_date = "'.date('Y-m-d').'" WHERE cron_code="CouponExpireNotes" ');
	send_coupon_expire_notes();
	echo '优惠券到期提醒邮件 OK！'."\n\n";
}

close_top_bbs_for_travel_companion();

tep_db_query('update `cron` set cron_ation_state = "0" ');
echo "Done";
?>