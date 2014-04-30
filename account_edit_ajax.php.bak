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
	$refobj = html_to_db(ajax_to_general_string($_GET['refobj']));
	$country = html_to_db(ajax_to_general_string($_GET['country']));
	$state = html_to_db(ajax_to_general_string($_GET['state']));
	//header('Content-Type:text/html;charset=UTF-8');
	echo general_to_ajax_string(db_to_html(tep_draw_full_address_input($refobj , $country,$state)));
	//$js_str = general_to_ajax_string(db_to_html(tep_draw_full_address_input($refobj , $country,$state)));
	//echo '[JS]alert(1);[/JS]';
	//echo '[JS]document.getElementById("'.$refobj.'").innerHTML="'.format_for_js($js_str).'";[/JS]';

	exit;
}

if($_GET['action'] == 'get_states_json'){
	$country = intval($_GET['countryId']);
	//echo "select zone_name,zone_id,zone_name_py from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' order by zone_name_py,zone_name ASC";
	$stateQuery = tep_db_query("select zone_name,zone_id,zone_name_py from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' order by zone_name_py,zone_name ASC");
	$str = array();
	while($row = tep_db_fetch_array($stateQuery)){
		$str[] = '{id:"'.$row['zone_id'].'",name:"'.format_for_js($row['zone_name']).'",pinyin:"'.format_for_js($row['zone_name_py']).'",telcode:" "}';
	}
	echo general_to_ajax_string("[".db_to_html(implode(",",$str))."]");
}
if($_GET['action'] == 'get_cities_json'){
	$zone_id = intval($_GET['zone_id']);
	//echo "select zone_name,zone_id,zone_name_py from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' order by zone_name_py,zone_name ASC";
	$stateQuery = tep_db_query("select city_name,city_id,city_tel_code,city_name_py from  zones_city  where zone_id = '" . $zone_id . "' order by city_name_py,city_name ASC");
	$str = array();
	while($row = tep_db_fetch_array($stateQuery)){
		$str[] = '{id:"'.$row['city_id'].'",name:"'.format_for_js($row['city_name']).'",pinyin:"'.format_for_js($row['city_name_py']).'",telcode:"'.format_for_js($row['city_tel_code']).'"}';
	}
	echo general_to_ajax_string("[".db_to_html(implode(",",$str))."]");
}
//--------------------------------------------------------------------------------------------用户必须登录才能使用该功能
$customer_id =intval($customer_id);
if($customer_id <=0 ){
	echo general_to_ajax_string(db_to_html("1,请重新登录后再进行该操作。"));
	exit;
}
//--------------------------------------------------------------------------------------------解除用户邮件绑定
if($_GET['action'] == 'unbind_email'){
	tep_db_query('UPDATE `customers` SET `customers_validation`= 0  , `customers_validation_code`=\'\'  WHERE `customers_id` = "'.intval($customer_id).'" LIMIT 1 ;');	//	customers_validation = 0  customers_validation_code为空
	$msg = '0,成功'.$customer_id;
	echo general_to_ajax_string(db_to_html($msg));	
//--------------------------------------------------------------------------------------------验证用户邮件是否有效
//1 fEmail =SEmail = validate
}elseif($_GET['action'] == 'unbind_phone'){
	tep_db_query('UPDATE `customers` SET `confirmphone`= \'\'    WHERE `customers_id` = "'.intval($customer_id).'" LIMIT 1 ;');	//	customers_validation = 0  customers_validation_code为空
	$msg = '0,成功'.$customer_id;
	echo general_to_ajax_string(db_to_html($msg));	
//--------------------------------------------------------------------------------------------验证用户邮件是否有效
//1 fEmail =SEmail = validate
}elseif($_GET['action'] == 'validate_email'){
	$email = trim(html_to_db(ajax_to_general_string($_GET['email'])));
	$info  = tep_get_customers_info($customer_id);	
	if($info['customers_email_address'] == $email){//.$customer_email_address 用户没有修改自己的邮箱 来进行绑定
		$msg = '999,成功。'; 
	}else{
		$error = html_to_db(tep_validate_email_for_register(html_to_db($email)));
		if($error != '')
			$msg = '1,'.$error;
		else 
		   $msg = '0,成功';
	}
	echo general_to_ajax_string(db_to_html($msg));		
//--------------------------------------------------------------------------------------------发送验证邮件,更新用户注册邮箱
}else if($_GET['action'] == 'send_validate_email'){
	//sleep(5);	echo general_to_ajax_string('0,错误');exit;
	$msg = '';
	$email= trim(html_to_db(ajax_to_general_string($_GET['email'])));
	$info  = tep_get_customers_info($customer_id);		
	$accountChangedNotice = '';
	if(intval($info['customers_validation']) == 1 && $email == $info['customers_email_address'] ){
			$msg = '1,该邮件地址已经通过验证。';
			echo general_to_ajax_string(db_to_html($msg));
	}else{
		if($email != $info['customers_email_address']){
			$accountChangedNotice = '请牢记您修改后的邮箱，下次登录将用此邮箱登录，密码保持不变。';
			$msg2 = tep_validate_email_for_register($email);
			if($msg2 != '' ){
				$msg = '1,'.html_to_db($msg2);
			}
		}
		
		if($msg==''){//update user email address
			tep_db_query('UPDATE `customers` SET `customers_email_address`= \''.$email.'\'  WHERE `customers_id` = "'.intval($customer_id).'" LIMIT 1 ;');
			$customer_email_address = html_to_db($email);//change session
			//更新affiliate邮箱地址
			update_affiliate_customers_email_address($email);
			
			$msg = '0,验证邮件已发送，请注意查收！'.$accountChangedNotice;
			echo general_to_ajax_string(db_to_html($msg));
			ob_start();
	  		send_validation_mail($email,true);
	  		ob_clean();
	    }else{
	    	 echo general_to_ajax_string(db_to_html($msg));
	    }
	}
//--------------------------------------------------------------------------------------------发送验证短信
}elseif($_GET['action'] == 'validate_phone'){
	$phone = html_to_db(ajax_to_general_string($_GET['phone']));
	if(check_mobilephone_for_profile_update($phone)){
		$msg = '999,成功。'; 
	}else{
			$msg = '1,您输入的手机号码无效!';
	}
	echo general_to_ajax_string(db_to_html($msg));		
}else if($_GET['action'] == 'send_validate_sms'){	
	$_GET['phone'] = trim($_GET['phone']);
	if(check_mobilephone_for_profile_update($_GET['phone'])){	
		if(check_mobilephone($_GET['phone'])){
			 $check_sql = tep_db_query('SELECT * FROM `customers` WHERE confirmphone ="'.tep_db_input(tep_db_prepare_input($_GET['phone'])).'"');
			 if(tep_db_num_rows($check_sql) > 0){
				$msg = '4,该手机号码已被绑定,请输入其他手机号码!';
			 }else {
				$phonenum = trim($_GET['phone']);
				$rndpwd=(integer)(rand(1000,9999));
				$content='尊敬的用户，你在走四方网的临时手机验证码是:'.$rndpwd.'。';
				if(preg_match('/'.preg_quote('[手机绑定]').'/',CPUNC_USE_RANGE)){
					if($cpunc->SendSMS($_GET['phone'],db_to_html($content),CHARSET)==true){
					  $msg = '0,发送成功！';
					}else{
					  $msg = '2,发送失败！';
					}
				}else{
					$msg = '1,未开启短信发送功能!';
				}
			}
		}else{
			$msg = '1,抱歉！目前仅支持中国大陆地区的手机号码绑定。';
		}
	}else{
		$msg = '1,您输入的手机号码无效!';
	}
	echo general_to_ajax_string(db_to_html($msg));
//--------------------------------------------------------------------------------------------检查验证短信是否有效,更新验证手机信息
}else if($_GET['action'] == 'check_sms_validate_code'){
	$phone= tep_db_prepare_input($_GET['phone']);
	$code = tep_db_prepare_input($_GET['code']);
	$sql = tep_db_query('SELECT to_content FROM `cpunc_sms_history` WHERE to_phone ="'.$phone.'" ORDER BY `add_date` desc limit 1');
	$row = tep_db_fetch_array($sql);
	$str = '';
    $to_content = $row['to_content'];
    $to_content = preg_replace("/\D+/", $str, $to_content);    
	if($to_content!=$code){
		$msg = '1,验证码错误!';
	}else{
		//更新用户的confirmphone
		tep_db_query('UPDATE `customers` SET `confirmphone`=\''.$phone.'\' , `customers_mobile_phone`=\''.$phone.'\' WHERE `customers_id` = "'.intval($customer_id).'" LIMIT 1 ;');	
		$msg = '0,成功'.$customer_id;
	}	
	echo general_to_ajax_string(db_to_html($msg));
}
?>
