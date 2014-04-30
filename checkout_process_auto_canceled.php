<?php
if(0){	//走四方暂时不需要自动取消重复订单的功能
	$inside_self_safe_canceled = false;
	$is_deleted_non_cc_method = false;
	$any_double_book_ids = false;
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {	
		$check_product_id_providers = tep_get_prid($order->products[$i]['id']);
		if($any_double_book_ids == false){
			$is_double_book_bool_array_check = is_double_booked((int)$insert_id, $check_product_id_providers);
			if($is_double_book_bool_array_check[0] == true){
				$any_double_book_ids = true;
			}
		}	
	}

	if($any_double_book_ids == true){
		$get_double_book_list_array = get_double_booked_orders_ids_payment_method((int)$insert_id);
		if(sizeof($get_double_book_list_array) > 0){
			//print_r($get_double_book_list_array);
			for ($loi=0; $loi<sizeof($get_double_book_list_array); $loi++){
				$check_orders_id = $get_double_book_list_array[$loi]['orders_id'];
				
				// canceled non cc payment method start
				$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_email_address, o.customers_id, o.orders_status, o.payment_method FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o WHERE o.orders_id=op.orders_id AND o.orders_status != 6 AND op.orders_id > 0  AND op.orders_id ='".(int)$check_orders_id."' AND op.orders_id !='".(int)$insert_id."' AND o.cc_expires = '' group by op.orders_id";  //cc_expires blank means no credit card method
				$check_double_book_query = tep_db_query($check_double_book_sql);
				while($check_double_book_row = tep_db_fetch_array($check_double_book_query)){
					    $is_deleted_non_cc_method = true;
					   $final_conf_reg_recpt_id = ORDER_EMAIL_PRIFIX_NAME.(int)$check_double_book_row['orders_id'];
					   $current_conf_reg_rect_id = ORDER_EMAIL_PRIFIX_NAME.(int)$insert_id;
					   $auto_canceled_default_subject = sprintf(AUTO_CANCELED_EMAIL_TEXT_SUBJECT, $final_conf_reg_recpt_id);
					   $auto_canceled_default_template=sprintf(AUTO_CANCELED_EMAIL_TEXT_BODY, $final_conf_reg_recpt_id, $current_conf_reg_rect_id, $final_conf_reg_recpt_id, $final_conf_reg_recpt_id, $current_conf_reg_rect_id, $final_conf_reg_recpt_id);
						
						$var_num = count($_SESSION['need_send_email']);
						$_SESSION['need_send_email'][$var_num]['to_name'] = $check_double_book_row['customers_name'];
						$_SESSION['need_send_email'][$var_num]['to_email_address'] =$check_double_book_row['customers_email_address'];
						$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
						$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
						$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
						$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
						$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;						
						//tep_mail($check_double_book_row['customers_name'], $check_double_book_row['customers_email_address'], $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
						tep_auto_cancelled_zero_price((int)$check_double_book_row['orders_id']);
						// send emails to other people
						if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
							$var_num = count($_SESSION['need_send_email']);
							$_SESSION['need_send_email'][$var_num]['to_name'] = '';
							$_SESSION['need_send_email'][$var_num]['to_email_address'] =SEND_EXTRA_ORDER_EMAILS_TO;
							$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
							$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
							$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
							$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
							$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
							//tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
						}
					   
					    tep_db_query("update " . TABLE_ORDERS . " set orders_status = '6', last_modified = now() , cancelled_time = now(), next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id = '" . (int)$check_double_book_row['orders_id']. "'");
					    $sql_data_array = array('orders_id' => (int)$check_double_book_row['orders_id'],
									  'orders_status_id' => '6',
									  'date_added' => 'now()',
									  'customer_notified' => '1',
									  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
									  'comments' => html_to_db($auto_canceled_default_template));
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
				}
				// canceled non cc payment method end
				
				//safe canceled payment method start
				$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_email_address, o.customers_id, o.orders_status, o.payment_method FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o WHERE o.orders_id=op.orders_id AND o.orders_status != 6 AND op.orders_id > 0  AND op.orders_id ='".(int)$check_orders_id."' AND op.orders_id !='".(int)$insert_id."' AND o.cc_expires != '' group by op.orders_id";
				$check_double_book_query = tep_db_query($check_double_book_sql);
				while($check_double_book_row = tep_db_fetch_array($check_double_book_query)){
					   
					   
					$select_check_product_oder_his_sql = "select orders_status_id from " .TABLE_ORDERS_STATUS_HISTORY. " where orders_id='".(int)$check_double_book_row['orders_id']."' and (orders_status_id='100060' OR orders_status_id='100062') ";
					$select_check_product_oder_his_query = tep_db_query($select_check_product_oder_his_sql);
						
					if(tep_db_num_rows($select_check_product_oder_his_query) > 0){ //found order with cc method
						
					   $select_check_product_oder_his_row = tep_db_fetch_array($select_check_product_oder_his_query);						
					   if($select_check_product_oder_his_row['orders_status_id']=='100060'){ //cc failed
							   
							   $final_conf_reg_recpt_id = ORDER_EMAIL_PRIFIX_NAME.(int)$check_double_book_row['orders_id'];
							   $current_conf_reg_rect_id = ORDER_EMAIL_PRIFIX_NAME.(int)$insert_id;
							   $auto_canceled_default_subject = sprintf(AUTO_CANCELED_EMAIL_TEXT_SUBJECT, $final_conf_reg_recpt_id);
							   $auto_canceled_default_template=sprintf(AUTO_CANCELED_EMAIL_TEXT_BODY, $final_conf_reg_recpt_id, $current_conf_reg_rect_id, $final_conf_reg_recpt_id, $final_conf_reg_recpt_id, $current_conf_reg_rect_id, $final_conf_reg_recpt_id);
								
								$var_num = count($_SESSION['need_send_email']);
								$_SESSION['need_send_email'][$var_num]['to_name'] = $check_double_book_row['customers_name'];
								$_SESSION['need_send_email'][$var_num]['to_email_address'] =$check_double_book_row['customers_email_address'];
								$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
								$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
								$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
								$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
								$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
								//tep_mail($check_double_book_row['customers_name'], $check_double_book_row['customers_email_address'], $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
								tep_auto_cancelled_zero_price((int)$check_double_book_row['orders_id']);
								// send emails to other people
								if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
									$var_num = count($_SESSION['need_send_email']);
									$_SESSION['need_send_email'][$var_num]['to_name'] = '';
									$_SESSION['need_send_email'][$var_num]['to_email_address'] =SEND_EXTRA_ORDER_EMAILS_TO;
									$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
									$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
									$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
									$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
									$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
									//tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
								}
							   
							   tep_db_query("update " . TABLE_ORDERS . " set orders_status = '6', last_modified = now() , cancelled_time = now(), next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id = '" . (int)$check_double_book_row['orders_id']. "'");
							   $sql_data_array = array('orders_id' => (int)$check_double_book_row['orders_id'],
											  'orders_status_id' => '6',
											  'date_added' => 'now()',
											  'customer_notified' => '1',
											  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
											  'comments' => html_to_db($auto_canceled_default_template));
								tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);						
						}else if($select_check_product_oder_his_row['orders_status_id']=='100062'){ //CC Authorized	-- setd void request for current order id - canceled order
							//(int)$insert_id
							$inside_self_safe_canceled = true;
							$inside_self_safe_orders_id = (int)$check_double_book_row['orders_id'];
							$inside_self_safe_customers_name = $check_double_book_row['customers_name'];							
							$inside_self_safe_customers_email_address = $check_double_book_row['customers_email_address'];
							
							$response_auth_trans_id = $last_active_charged_transation;  //amit recover last transation 	
							if($response_auth_trans_id != ''){						
								$auto_charged_request_response = auto_charged_authorized_net_order('VOID'); //void current transations
								if($auto_charged_request_response[0] == '1'){ // only if success response
									//add CC Voided status comment
									$cc_voided_orders_status_comment = "Last 4 digits of CC: xxxx".substr($order->info['cc_number'],-4)."
													Expiration Date: ".$order->info['cc_expires']."
													Settlement Amount: USD ".$auto_charged_request_response[9]."
													Transaction ID: ".$auto_charged_request_response[6]."";
									$sql_data_array = array('orders_id' => $insert_id,
												  'orders_status_id' => '100077',
												  'date_added' => 'now()',
												  'customer_notified' => '0',
												  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
												  'comments' => html_to_db($cc_voided_orders_status_comment)							  
												  );
									tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
									//add CC Voided status comment
									
									//cancle current because already have one order with charged captured
									   $final_conf_reg_recpt_id = ORDER_EMAIL_PRIFIX_NAME.$inside_self_safe_orders_id;
									   $current_conf_reg_rect_id = ORDER_EMAIL_PRIFIX_NAME.(int)$insert_id;
									   $auto_canceled_default_subject = sprintf(AUTO_CANCELED_EMAIL_TEXT_SUBJECT, $current_conf_reg_rect_id);
									   $auto_canceled_default_template=sprintf(AUTO_CANCELED_EMAIL_TEXT_BODY, $current_conf_reg_rect_id, $final_conf_reg_recpt_id, $current_conf_reg_rect_id, $current_conf_reg_rect_id, $final_conf_reg_recpt_id, $current_conf_reg_rect_id);
										
										$var_num = count($_SESSION['need_send_email']);
										$_SESSION['need_send_email'][$var_num]['to_name'] = $inside_self_safe_customers_name;
										$_SESSION['need_send_email'][$var_num]['to_email_address'] =$inside_self_safe_customers_email_address;
										$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
										$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
										$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
										$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
										$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
										//tep_mail($inside_self_safe_customers_name, $inside_self_safe_customers_email_address, $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
										tep_auto_cancelled_zero_price((int)$insert_id);
										// send emails to other people
										if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
											$var_num = count($_SESSION['need_send_email']);
											$_SESSION['need_send_email'][$var_num]['to_name'] = '';
											$_SESSION['need_send_email'][$var_num]['to_email_address'] =SEND_EXTRA_ORDER_EMAILS_TO;
											$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
											$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
											$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
											$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
											$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
											//tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
										}
									   
										tep_db_query("update " . TABLE_ORDERS . " set orders_status = '6', last_modified = now() , cancelled_time = now(), next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id = '" . (int)$insert_id. "'");							   
										$sql_data_array = array('orders_id' => (int)$insert_id,
													  'orders_status_id' => '6',
													  'date_added' => 'now()',
													  'customer_notified' => '1',
													  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
													  'comments' => html_to_db($auto_canceled_default_template));
										tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);	
								   //cancle current because already have one order with charged captured
																	
									$response_auth_trans_id = ''; //added to avoide multiple changed same order	
									$last_active_charged_transation = ''; //added to avoide multiple changed same order	
								}							
							} //if valid authorized.net transation id then only sent void request
							
							
						} ////CC Authorized end
						
					}	
						
						
				}				
				//safe canceled cc payment method
			
			}
		
		}
	}
	
	if($inside_self_safe_canceled == true && $order->info['cc_expires'] == ''){ //non cc payment method
		$is_deleted_non_cc_method = true;
		//cancle current because already have one order with charged captured
		   $final_conf_reg_recpt_id = ORDER_EMAIL_PRIFIX_NAME.$inside_self_safe_orders_id;
		   $current_conf_reg_rect_id = ORDER_EMAIL_PRIFIX_NAME.(int)$insert_id;
		   $auto_canceled_default_subject = sprintf(AUTO_CANCELED_EMAIL_TEXT_SUBJECT, $current_conf_reg_rect_id);
		   $auto_canceled_default_template=sprintf(AUTO_CANCELED_EMAIL_TEXT_BODY, $current_conf_reg_rect_id, $final_conf_reg_recpt_id, $current_conf_reg_rect_id, $current_conf_reg_rect_id, $final_conf_reg_recpt_id,$current_conf_reg_rect_id);
			
			$var_num = count($_SESSION['need_send_email']);
			$_SESSION['need_send_email'][$var_num]['to_name'] = $inside_self_safe_customers_name;
			$_SESSION['need_send_email'][$var_num]['to_email_address'] =$inside_self_safe_customers_email_address;
			$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
			$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
			$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
			$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
			$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
			//tep_mail($inside_self_safe_customers_name, $inside_self_safe_customers_email_address, $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			tep_auto_cancelled_zero_price((int)$insert_id);
			// send emails to other people
			if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
				$var_num = count($_SESSION['need_send_email']);
				$_SESSION['need_send_email'][$var_num]['to_name'] = '';
				$_SESSION['need_send_email'][$var_num]['to_email_address'] =SEND_EXTRA_ORDER_EMAILS_TO;
				$_SESSION['need_send_email'][$var_num]['email_subject'] =$auto_canceled_default_subject;
				$_SESSION['need_send_email'][$var_num]['email_text'] = $auto_canceled_default_template;
				$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
				$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
				$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
				//tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $auto_canceled_default_subject, $auto_canceled_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			}
		   
			tep_db_query("update " . TABLE_ORDERS . " set orders_status = '6', last_modified = now(), cancelled_time = now(), next_admin_id=0, need_next_admin=0, need_next_urgency=''  where orders_id = '" . (int)$insert_id. "'");							   
			$sql_data_array = array('orders_id' => (int)$insert_id,
						  'orders_status_id' => '6',
						  'date_added' => 'now()',
						  'customer_notified' => '1',
						  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
						  'comments' => html_to_db($auto_canceled_default_template));
			tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);	
	   //cancle current because already have one order with charged captured		
	}

//}

//}

//根据用户选择的支付方式自动修改订单状态 start {
	if($is_deleted_non_cc_method == false && !tep_not_null($order->info['comments'])){
			$auto_changed_orders_status_id = '';
			$get_mail_orders_status_id = '';
			//以下状态已经自动更新到100094，邮件内容还是原来的内容 -- Howard
			if($payment== 'banktransfer'){
				$auto_changed_orders_status_id = '100094'; //'2';
				$get_mail_orders_status_id = '2';
			}else if($payment =='cashdeposit'){
				$auto_changed_orders_status_id = '100094'; //'100030';
				$get_mail_orders_status_id = '100030';
			}else if($payment =='moneyorder'){
				$auto_changed_orders_status_id = '100094'; //'100029';
				$get_mail_orders_status_id = '100029';
			}
	
			if($auto_changed_orders_status_id != ''){
				 $auto_new_orders_status_query = tep_db_query("select orders_status_id, orders_status_name, orders_status_default_subject, orders_status_default_comment from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' and orders_status_id 	 = '".$get_mail_orders_status_id."'");
				 if($auto_new_orders_status = tep_db_fetch_array($auto_new_orders_status_query)) {
				
					$inside_self_safe_orders_id = ORDER_EMAIL_PRIFIX_NAME.(int)$insert_id;
					$inside_self_safe_customers_name = ucfirst($customer_first_name) . ' ' . ucfirst($customer_last_name);
					$inside_self_safe_customers_email_address = tep_get_customers_email($customer_id);
					$auto_changed_default_subject = str_replace('OID',$inside_self_safe_orders_id,$auto_new_orders_status['orders_status_default_subject']);
					$auto_changed_default_template= str_replace('OID',$inside_self_safe_orders_id,$auto_new_orders_status['orders_status_default_comment']);
					
					$var_num = count($_SESSION['need_send_email']);
					$_SESSION['need_send_email'][$var_num]['to_name'] = db_to_html($inside_self_safe_customers_name);
					$_SESSION['need_send_email'][$var_num]['to_email_address'] =$inside_self_safe_customers_email_address;
					$_SESSION['need_send_email'][$var_num]['email_subject'] =db_to_html($auto_changed_default_subject);
					$_SESSION['need_send_email'][$var_num]['email_text'] =db_to_html($auto_changed_default_template);
					$_SESSION['need_send_email'][$var_num]['from_email_name'] =  db_to_html(STORE_OWNER);
					$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
					$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
				
					//tep_mail($inside_self_safe_customers_name, $inside_self_safe_customers_email_address, $auto_changed_default_subject, $auto_changed_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				
					// send emails to other people
					if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
						//tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $auto_changed_default_subject, $auto_changed_default_template, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
					}
				   
					tep_db_query("update " . TABLE_ORDERS . " set orders_status = '".$auto_changed_orders_status_id."', last_modified = now(), next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id = '" . (int)$insert_id. "'");							   
					$sql_data_array = array('orders_id' => (int)$insert_id,
								  'orders_status_id' => $auto_changed_orders_status_id,
								  'date_added' => 'now()',
								  'customer_notified' => '1',
								  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
								  'comments' => $auto_changed_default_template);
					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);				
				
				  }
			}
	}	
//根据用户选择的支付方式自动修改订单状态 end }

}
?>