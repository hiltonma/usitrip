<?php
//if($_SERVER['HTTP_HOST'] != 'www.usitrip.com' && $_SERVER['HTTP_HOST'] != 'cn.usitrip.com'){ //only allow for demo site  
	
 if($orders_products_id > 0){
	/*
	echo "here".$orders_products_id;
	echo "<br>".$orders_id;
	echo "<br>".$products_id;
	*/
	$get_ordres_status_query= tep_db_query("select o.orders_status, op.products_model, op.products_name from " .TABLE_ORDERS. " as o, ".TABLE_ORDERS_PRODUCTS." op where o.orders_id=op.orders_id and op.orders_products_id = '" . (int)$orders_products_id . "'");
	$get_ordres_status_row= tep_db_fetch_array($get_ordres_status_query);
			
	$select_check_product_oder_his_sql = "select orders_status_id from " .TABLE_ORDERS_STATUS_HISTORY. " where orders_id='".(int)$orders_id."' and orders_status_id='100072' ";
	$select_check_product_oder_his_query = tep_db_query($select_check_product_oder_his_sql);
	
	$order_total_value =  tep_get_order_final_price_of_oid($orders_id);
	if(tep_db_num_rows($select_check_product_oder_his_query) == 1 && $get_ordres_status_row['orders_status'] == '100072'){ //found order with booked issued by system and order total should < 1800
			//get common information start
			$get_provider_charged_request_array = tep_get_tour_agency_information($products_id);
			
			$orders_status_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS . " where language_id = '1' and ( orders_status_id='100083' or orders_status_id='100002' or orders_status_id='100090' or orders_status_id='100012' ) order by orders_status_name");
			while ($orders_status_row = tep_db_fetch_array($orders_status_query)) {
			
				$orders_status_row['orders_status_default_subject'] = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME.$orders_id ,$orders_status_row['orders_status_default_subject']);
				$orders_status_row['orders_status_default_comment'] = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME.$orders_id ,$orders_status_row['orders_status_default_comment']);
				$replace_list_tours_comment = '';
				
				$orders_status_row['orders_status_default_comment'] = str_replace('ADD_TOUR_LIST_TO_COMMENT',$replace_list_tours_comment,$orders_status_row['orders_status_default_comment']);
				$comments[$orders_status_row['orders_status_id']] = $orders_status_row['orders_status_default_comment'];
				$email_subject_array[$orders_status_row['orders_status_id']] = $orders_status_row['orders_status_default_subject'];
			}
			
			$check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$orders_id . "'");
       		$check_status = tep_db_fetch_array($check_status_query);
			//get common information end
			if( $get_provider_charged_request_array['products_vacation_package'] == 0 && $provider_order_status_id != 6){ // local tour
					if(tep_get_acb_required_or_not($orders_products_id,$orders_id,$products_id,$order_total_value) == false){ //System judge No ACB Needed
						//first Confirmation ->  with send eticket	 ->Ticket Issued	
						
						//first Confirmation - 100083
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100083', last_modified = now(), next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id = '" . (int)$orders_id . "'");
						$sql_data_array = array('orders_id' => $orders_id,
										  'orders_status_id' => '100083',
										  'date_added' => 'now()',
										  'customer_notified' => '1',
										  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
										  'comments' => $comments['100083']);
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$to_name = $check_status['customers_name'];
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = $email_subject_array['100083'];
						$email_text = $comments['100083'] ;
						$from_email_name = STORE_OWNER;						
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						sleep(2);
						
						//prepare eticket sent start						
						require_once(DIR_FS_CLASSES . 'order.php');
						$oID = $_GET['oID'] = $orders_id;
						$_GET['products_id'] = $products_id;
						$_GET['orders_products_id'] = $orders_products_id;
						$order = new order($oID);
						$email_list = get_order_travel_companion_email_list((int)$oID,(int)$_GET['products_id']);
						
						ob_start();
						require_once("eticket_email.php"); 
						$email_order = ob_get_contents();
						ob_end_clean();
						
						$to_name = $order->customer['name'];
						$emp_email = $order->customer['email_address'];
						if(tep_not_null($email_list)){
							$emp_email = $email_list;	
							$to_name = $email_list;  
						}
						
						//E-ticket Log Start
						$eticket_log_data_array = array('orders_products_id' => $orders_products_id,
														'orders_eticket_last_modified' => 'now()',
														'orders_eticket_content' => $email_order,
														'orders_eticket_is_customer_notified' => 1,
														'orders_eticket_updated_by' => tep_get_admin_customer_name(CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID),
														'orders_eticket_updator_type' => 0
													   );
						tep_db_perform(TABLE_ORDERS_ETICKET_LOG, $eticket_log_data_array);	
						$orders_eticket_log_id = tep_db_insert_id();
						//E-ticket Log End
						
						$email_subject = STORE_OWNER." ²ÎÍÅÆ¾Ö¤ ¶©µ¥ºÅ£º".(int)$oID." ";
						$from_email_name = STORE_OWNER;
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						
						$email_text = preg_replace('/[[:space:]]+/',' ',$email_order);
						$email_text .= email_track_code('eTicket',$emp_email,(int)$oID, 'orders_id', (int)$orders_eticket_log_id);
						
					   //×ª»»×Ö·û±àÂë
					   $customers_char_set = 'gb2312';
						$to_name = iconv('gb2312',$customers_char_set.'//IGNORE',$to_name );
						$email_subject = iconv('gb2312',$customers_char_set.'//IGNORE', $email_subject);
						$email_text = iconv('gb2312',$customers_char_set.'//IGNORE', $email_text);
						$from_email_name = iconv('gb2312',$customers_char_set.'//IGNORE', $from_email_name);
											
						//Send mail to customer
						$headers = "From: ".STORE_OWNER_DOMAIN_NAME." <".STORE_OWNER_EMAIL_ADDRESS.">\n"; 
					  	$headers .= "MIME-Version: 1.0\r\n"; 
					  	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
						//amit added for tacking eticket status start
						
						//amit added for tacking eticket status end
						tep_mail($to_name,$emp_email, $email_subject,$email_text,$from_email_name,$from_email_address,'true', $customers_char_set);												     
						tep_db_query("update ".TABLE_ORDERS_PRODUCTS_ETICKET." set confirmed=1  where orders_products_id = '" . (int)$orders_products_id . "' ");
						sleep(2);
						//prepare eticket send end
						
						//Ticket Issued  - 100002
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100002', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
						$sql_data_array = array('orders_id' => $orders_id,
										  'orders_status_id' => '100002',
										  'date_added' => 'now()',
										  'customer_notified' => '1',
										  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
										  'comments' => $comments['100002']);
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$to_name = $check_status['customers_name'];
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = $email_subject_array['100002'];
						$email_text = $comments['100002'] ;
						$from_email_name = STORE_OWNER;						
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
												
					}else{ //ACB  Required
						
						//first Confirmation - 100083
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100083', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
						$sql_data_array = array('orders_id' => $orders_id,
										  'orders_status_id' => '100083',
										  'date_added' => 'now()',
										  'customer_notified' => '1',
										  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
										  'comments' => $comments['100083']);
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$to_name = $check_status['customers_name'];
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = $email_subject_array['100083'];
						$email_text = $comments['100083'] ;
						$from_email_name = STORE_OWNER;						
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						sleep(2);
						
						//Wait for Supporting Doc -- 100090 	
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100090', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
						$sql_data_array = array('orders_id' => $orders_id,
										  'orders_status_id' => '100090',
										  'date_added' => 'now()',
										  'customer_notified' => '1',
										  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
										  'comments' => $comments['100090']);
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$to_name = $check_status['customers_name'];
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = $email_subject_array['100090'];
						$email_text = $comments['100090'] ;
						$from_email_name = STORE_OWNER;						
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						
					}				
			}else if( $get_provider_charged_request_array['products_vacation_package'] == 1){ // tour package
			
					if(tep_get_acb_required_or_not($orders_products_id,$orders_id,$products_id,$order_total_value) == false){ //System judge No ACB Needed
					
						// if guest flight information no available Confirmed B - flight information needed
						// if guest flight information availble Confirmation is last
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100083', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
						$sql_data_array = array('orders_id' => $orders_id,
										  'orders_status_id' => '100083',
										  'date_added' => 'now()',
										  'customer_notified' => '1',
										  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
										  'comments' => $comments['100083']);
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$to_name = $check_status['customers_name'];
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = $email_subject_array['100083'];
						$email_text = $comments['100083'] ;
						$from_email_name = STORE_OWNER;						
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						sleep(2);
						//check for Flight Information Needed -- 100012	
						$flight_information_ava = false;
						$orders_flight_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$orders_id . "' and products_id = '" . (int)$products_id . "' and (airline_name != '' or airline_name_departure !='' or flight_no !='' or flight_no_departure !='' or airport_name !='' or airport_name_departure !='' or arrival_date !='' or arrival_time !='' or departure_date !='' or departure_time) ");
						if($orders_flight_history_row = tep_db_fetch_array($orders_flight_history_query)){
							$flight_information_ava = true;						
						}
						
						if($flight_information_ava == false){
								tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100012', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
								$sql_data_array = array('orders_id' => $orders_id,
												  'orders_status_id' => '100012',
												  'date_added' => 'now()',
												  'customer_notified' => '1',
												  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
												  'comments' => $comments['100012']);
								tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
								
								$to_name = $check_status['customers_name'];
								$to_email_address = $check_status['customers_email_address'];
								$email_subject = $email_subject_array['100012'];
								$email_text = $comments['100012'] ;
								$from_email_name = STORE_OWNER;						
								$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
								tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						}
						
					}else{ //ACB Required
						//Confirmation  -- 100083 	
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100083', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
						$sql_data_array = array('orders_id' => $orders_id,
										  'orders_status_id' => '100083',
										  'date_added' => 'now()',
										  'customer_notified' => '1',
										  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
										  'comments' => $comments['100083']);
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$to_name = $check_status['customers_name'];
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = $email_subject_array['100083'];
						$email_text = $comments['100083'] ;
						$from_email_name = STORE_OWNER;						
						$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						sleep(2);
						
						//check for Flight Information Needed  -- 100012	
						$flight_information_ava = false;
						$orders_flight_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$orders_id . "' and products_id = '" . (int)$products_id . "' and (airline_name != '' or airline_name_departure !='' or flight_no !='' or flight_no_departure !='' or airport_name !='' or airport_name_departure !='' or arrival_date !='' or arrival_time !='' or departure_date !='' or departure_time) ");
						if($orders_flight_history_row = tep_db_fetch_array($orders_flight_history_query)){
							$flight_information_ava = true;						
						}
						
						if($flight_information_ava == false){
								tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100012', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
								$sql_data_array = array('orders_id' => $orders_id,
												  'orders_status_id' => '100012',
												  'date_added' => 'now()',
												  'customer_notified' => '1',
												  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
												  'comments' => $comments['100012']);
								tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
								
								$to_name = $check_status['customers_name'];
								$to_email_address = $check_status['customers_email_address'];
								$email_subject = $email_subject_array['100012'];
								$email_text = $comments['100012'] ;
								$from_email_name = STORE_OWNER;						
								$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
								tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
								sleep(2);
						}
						
						
						// wait document -- 100090	
						tep_db_query("update " . TABLE_ORDERS . " set orders_status = '100090', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
								$sql_data_array = array('orders_id' => $orders_id,
												  'orders_status_id' => '100090',
												  'date_added' => 'now()',
												  'customer_notified' => '1',
												  'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
												  'comments' => $comments['100090']);
								tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
								
								$to_name = $check_status['customers_name'];
								$to_email_address = $check_status['customers_email_address'];
								$email_subject = $email_subject_array['100090'];
								$email_text = $comments['100090'] ;
								$from_email_name = STORE_OWNER;						
								$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
								tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
						
					}
			
			} // end of chekcing package or tour if
			
	} // end of checking book issue by system
	
 } // end of checking set order product id
	
//}
?>