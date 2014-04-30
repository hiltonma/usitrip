<?php
//允许客户通过电子邮箱自动登录到走四方网站

//如果从电子邮件登录我们网站将设置为已经登录
function from_email_auto_login(){
	global $customer_id, $cart ;
	$cfm_to = tep_not_null($_POST['cfm_to']) ? $_POST['cfm_to'] : $_GET['cfm_to'];
	$cfm_p = tep_not_null($_POST['cfm_p']) ? $_POST['cfm_p'] : $_GET['cfm_p'];
	if(!tep_session_is_registered('customer_id') && tep_not_null($cfm_to) && tep_not_null($cfm_p)){
		//$cfm_to is customers_id
		//$cfm_p is MD5 pass
		$check_customer_e_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($cfm_to) . "'");
		$check_customer_e = tep_db_fetch_array($check_customer_e_query);
		
		//echo 'customers_id:'.$check_customer_e['customers_id'].'<br>';
		//echo 'cfm_to:'.$cfm_to.'<br>';
		//echo 'cfm_p:'.$cfm_p.'<br>';
		//echo 'customers_password:'.$check_customer_e['customers_password'].'<br>';
		
		if ((int)$check_customer_e['customers_id'] && $cfm_p == $check_customer_e['customers_password']) {
			
	
			if (SESSION_RECREATE == 'True') {
			  tep_session_recreate();
			}
	
			$check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer_e['customers_id'] . "' and address_book_id = '" . (int)$check_customer_e['customers_default_address_id'] . "'");
			$check_country = tep_db_fetch_array($check_country_query);
	
			$customer_id = $check_customer_e['customers_id'];
			$customer_default_address_id = $check_customer_e['customers_default_address_id'];
			$customer_first_name = $check_customer_e['customers_firstname'];
			$customer_country_id = $check_country['entry_country_id'];
			$customer_zone_id = $check_country['entry_zone_id'];
			$affiliate_id = $check_customer_e['customers_id'];
			tep_session_register('customer_id');
			tep_session_register('customer_default_address_id');
			tep_session_register('customer_first_name');
			tep_session_register('customer_country_id');
			tep_session_register('customer_zone_id');
			tep_session_register('affiliate_id'); 
			
			//howard added need create user for customers if table "user" no this customers.
			if(!get_user_id($customer_id)){
				$sql_data_array = array();
				$sql_data_array = array('customers_id' => (int)$customer_id,
										'user_nickname' => $customer_first_name,
										'user_score_total' => FIRST_LOGIN_SCORE);
				tep_db_perform('user', $sql_data_array);
				
				//update user_score_history
				update_user_score($customer_id, FIRST_LOGIN_SCORE, FIRST_LOGIN,'false');
			}
	
			if(!tep_not_null($_COOKIE['every_day_login'])){
				/*every day login +5*/
				update_user_score($customer_id, EVERY_DAY_LOGIN_SCORE, EVERY_DAY_LOGIN);
				
			}
			//howard added need create user for customers if table "user" no this customers. end
			
			//howard added write login date cookie to local
			//setcookie('LoginDate', date('Ymd'), time() +(3600*24 ));
			setcookie('LoginDate', date('Ymd'));
			//howard added write login date cookie to local end
			
			
			$royal_customers_query_raw = tep_db_query("select orders_id from " . TABLE_ORDERS . " o, orders_status s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and o.orders_status='100006' and o.customers_id='".$customer_id."' ");
									
									if(tep_db_num_rows($royal_customers_query_raw)>0)
									{								
										$total_pur_suc_nos_of_cnt = tep_db_num_rows($royal_customers_query_raw)+1;
										tep_session_register('total_pur_suc_nos_of_cnt');
									}else{
										$total_pur_suc_nos_of_cnt = 1;
										tep_session_register('total_pur_suc_nos_of_cnt');
									}
			
			//amit added to check if user exits in affiliate table start
			$check_affilate = "select affiliate_id from ".TABLE_AFFILIATE." where affiliate_id=".$affiliate_id;
			 $check_affilate_query = tep_db_query($check_affilate);
			 if (!tep_db_num_rows($check_affilate_query)) {
				$sql_data_array = array('affiliate_id' => $affiliate_id);
				$sql_data_array['affiliate_lft'] = '1';
				$sql_data_array['affiliate_rgt'] = '2';
				tep_db_perform(TABLE_AFFILIATE, $sql_data_array);			 
				tep_db_query ("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $affiliate_id . "' ");		   
				
			 }
			//amit added to check if user exits in affilite table end
	
			tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
			tep_db_query("update " . TABLE_CUSTOMERS. " set  customers_char_set = '".CHARSET."' where customers_id = '" . (int)$customer_id . "' ");
	
	// restore cart contents
			$cart->restore_contents();
		}
	}
}

//自动在邮件中添加自动登录网站的标记
function add_from_email_auto_login_tag($customers_id,$method="post"){
	$input = '';
	if(!(int)$customers_id){
		return false;
	}
	$sql = tep_db_query("select customers_id, customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "' limit 1");
	$row = tep_db_fetch_array($sql);
	if((int)$row['customers_id']){
		if($method=="post"){
			$input .= '<input name="cfm_to" type="hidden" id="cfm_to" value="'.(int)$row['customers_id'].'" />';
			$input .= '<input name="cfm_p" type="hidden" id="cfm_p" value="'.$row['customers_password'].'" />';
		}else{
			$input .= 'cfm_to='.(int)$row['customers_id'].'&cfm_p='.$row['customers_password'];
		}
	}
	return $input;
}
?>