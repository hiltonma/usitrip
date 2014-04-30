<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");  

require_once('includes/application_top.php');
require_once(DIR_WS_FUNCTIONS . 'timezone.php');
require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS);
include_once(DIR_WS_CLASSES . 'order.php');
$languages = tep_get_languages();
header("Content-type: text/html; charset=".CHARSET."");

if(isset($_POST['aryFormData']))
  {
 		$aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  

			  if (eregi('--leftbrack', $key)) {				
				$key = str_replace('--leftbrack','[',$key);
				$key = str_replace('rightbrack--',']',$key);				
			  }
			  
			  $value2 = str_replace('@@amp;','&',$value2);
			  $value2 = str_replace('@@plush;','+',$value2);
			  $value2 = mb_convert_encoding($value2, CHARSET, 'UTF-8');
			  $_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes($value2);  

			  //echo "$key=>$value2<br>";  	   
		  }
		}
		
}	


if(isset($HTTP_GET_VARS['oID'])){
$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
}

$order = new order($oID);	//取得订单对象

//取得update_products数组
$update_products = array();
$update_products[$_POST['credit_item_orders_products_id']]['final_price'] = number_format($order->products[0]['final_price'], 2, '.', '');


if(isset($HTTP_GET_VARS['ajax_formname'])){
$ajax_formname = tep_db_prepare_input($HTTP_GET_VARS['ajax_formname']);
}else{
$ajax_formname = 'settele_order';
}

$ajax_formname = $HTTP_GET_VARS['ajax_formname'];

$error='false';

if($HTTP_GET_VARS['section'] == 'order_settlement' &&  $HTTP_GET_VARS['action'] == 'process'){
}


if(isset($HTTP_GET_VARS['action']) &&  $HTTP_GET_VARS['action'] == 'process' && $error=='false'){
		
		switch($HTTP_GET_VARS['section']) {
			
			case 'order_settlement':
				//start of tour categorization section 	{							
				// Howard added Credit Issued 客户信用扣款操作判断{
				if(trim($_POST['orders_payment_method'])==trim($_POST['append_pmethod']) && trim($_POST['orders_payment_method'])=="Credit Issued" && $_POST['changed_order_status_select']!="100005"){
					$updateCREDITS = true;
					$customersSql = tep_db_query('SELECT customers_id FROM `orders` WHERE orders_id="'.(int)$oID.'" ');
					$customersRow = tep_db_fetch_array($customersSql);
					$_customers_id = $customersRow['customers_id'];
					$customer_current_credit_bal = tep_get_customer_credits_balance($_customers_id);
					$customer_new_credit_bal = $customer_current_credit_bal - tep_db_prepare_input(abs($_POST['order_value']));
					if($customer_new_credit_bal<0){
						echo '<b class="col_red_b">客户信用余额不足，本次操作失败！</b>';
						exit;
					}
				}
				// Howard added Credit Issued 客户信用扣款操作判断}
				
				 //amit added for auto append order status history start				 
				 if($_POST['change_status'] == '1' && $_POST['changed_order_status_select'] != ''){
				 
				 	$det_changed_orders_status = $_POST['changed_order_status_select'];
					//amit added to auto approved changed catured order start
					if($det_changed_orders_status == '100006'){
						if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
							//所有与100006状态有关的积分更新操作都请用以下函数
							auto_changed_points_info_for_orders_status_100006((int)$oID);
						}
					}else if($det_changed_orders_status=='6' || $det_changed_orders_status=='100005'){
						if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
							//所有与100005或6状态有关的积分更新操作都请用以下函数
							auto_changed_points_info_for_orders_status_6_100005_100130_100134($oID, $det_changed_orders_status);
						}
					}
					//amit added to auto approved changed catured order end
				 }else{
				 	$det_changed_orders_status = '100006';  //Charge Captured (I)				 	
				 }
				 
				 //fixed for refund should be -ve
				 if($_POST['order_value'] > 1 && $det_changed_orders_status == '100005' && $_POST['order_value'] != 0){				 
				 	$_POST['order_value'] = ($_POST['order_value'] * (-1));					
				 }
				 //fixed for refund should be -ve
				  
				 $order_status_reference_comments = $_POST['orders_payment_method'].chr(13).chr(10).$_POST['order_value'].chr(13).chr(10).$_POST['reference_comments'];
				$customer_notified = "0";
				if($det_changed_orders_status=='100006'){	//如果是已收款状态则要让客户前台也显示此状态
					//$customer_notified = '1'; 
				}
				 $sql_data_array = array('orders_id' => (int)$oID,
								  'orders_status_id' => $det_changed_orders_status,
								  'date_added' => 'now()',
								  'customer_notified' => $customer_notified,
								  'updated_by' => $login_id,
								  'comments' => tep_db_prepare_input($order_status_reference_comments)
								  );
				tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
				
				 if($_POST['change_status'] == '1' && $_POST['changed_order_status_select'] != ''){				 	
					tep_db_query("update " . TABLE_ORDERS . " set orders_status = '".$det_changed_orders_status."', last_modified = now() where orders_id = '" . (int)$oID . "'");
					
					
					  if($det_changed_orders_status == '100005'){
							$changed_order_status_select_array = explode('-',$_POST['refund_type_select']);				
							if($_POST['order_value'] < 1){
								$display_charge_order_value = ($_POST['order_value'] * (-1));
							}else{
								$display_charge_order_value = $_POST['order_value'];
							}							
							if($display_charge_order_value > 0){ // notify refund comment to customer
									$addon_refund_comment='';
									$mail_body='';
									if($changed_order_status_select_array[1] == '1'){ // refunded by cc
										$mail_body=sprintf(TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CC, $display_charge_order_value, $display_charge_order_value);
										$addon_refund_comment='<b>By credit card:</b><br>';
									}else if($changed_order_status_select_array[1] == '2'){ // refunded by paypal
										$mail_body=sprintf(TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_PAYPAL, $display_charge_order_value, $display_charge_order_value);
										$addon_refund_comment='<b>By PayPal:</b><br>';
									}else if($changed_order_status_select_array[1] == '3'){ // refunded by check										
										$mail_body=sprintf(TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CHECK, $display_charge_order_value, $display_charge_order_value);
										$addon_refund_comment='<b>By check:</b><br>';
									}else if($changed_order_status_select_array[1] == '4'){ // refunded by cash payment			
										$mail_body=sprintf(TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CASH_PAYMENT, $display_charge_order_value, $display_charge_order_value);
										$addon_refund_comment='<b>By cash payment:</b><br>';
									}else if($changed_order_status_select_array[1] == '5'){ // refunded by wire transfer			
										$mail_body=sprintf(TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_WIRE_TRANFER, $display_charge_order_value, $display_charge_order_value);
										$addon_refund_comment='<b>By wire transfer:</b><br>';
									}else if($changed_order_status_select_array[1] == '6'){ // refunded by wire transfer
									
										$mail_body=sprintf(TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CREDIT_ISSUED, $display_charge_order_value, $display_charge_order_value);
										$addon_refund_comment='<b>Credit issued:</b><br>';
										// Howard added from $status == '100080' rmove to here start
										if (tep_not_null($_POST['credit_reason']) || tep_not_null($_POST['credit_with_provider']) || tep_not_null($_POST['credit_date']) || tep_not_null($_POST['credit_fee']) || tep_not_null($_POST['provider_credit_fee']) || tep_not_null($_POST['credit_reason_detail'])) {
											
											$get_order_total = tep_db_fetch_array(tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'"));
											if(tep_not_null($_POST['credit_date'])){
												$_POST['credit_date'] = date('m/d/Y', strtotime($_POST['credit_date'])); // format to m/d/Y
											}
						
											$credit_history = tep_db_input($_POST['credit_item']) . '$$' . tep_db_input($_POST['credit_item_orders_products_id']) . '||' . tep_db_input($_POST['credit_reason']) . '||' . tep_db_input($_POST['credit_with_provider']) . '||' . tep_db_input($_POST['credit_date']) . '||' . tep_db_input($_POST['credit_fee']) . '($' . number_format($get_order_total['value'], 2) . 'x' . tep_db_input($_POST['credit_fee_percent']) . '%)||' . tep_db_input($_POST['provider_credit_fee']) . '||' . tep_db_prepare_input($credit_reason_detail) . '||' . tep_get_admin_customer_name($login_id) . '||' . date("m/d/Y (h:i a)") . '||==||';
											
											// Update Orders start
											$UpdateOrders = "update " . TABLE_ORDERS . " set ";
											$UpdateOrders .= "creditissued_history = concat(creditissued_history, '" . tep_db_input($credit_history) . "'), last_modified = now() ";
            								$UpdateOrders .= " where orders_id = '" . tep_db_input($oID) . "';";
											tep_db_query($UpdateOrders);
											// Update Orders end
						
											$customers_id = tep_db_fetch_array(tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = " . tep_db_input($oID)));
						
											$get_credit_amt = tep_db_fetch_array(tep_db_query("select customers_credit_issued_amt from " . TABLE_CUSTOMERS . " where customers_id = " . $customers_id['customers_id'] . " "));
						
											tep_db_query("update  " . TABLE_CUSTOMERS . "  set customers_credit_issued_amt = " . ($get_credit_amt['customers_credit_issued_amt'] + $_POST['credit_fee']) . " where customers_id = " . $customers_id['customers_id'] . " ");
						
											$sql_data_credits_array = array('customers_id' => (int) $customers_id['customers_id'],
												'orders_id' => tep_db_input($oID),
												'products_id' => tep_db_input($_POST['credit_item']),
												'credit_bal' => $_POST['credit_fee'],
												'credit_comment' => '$' . number_format(($update_products[$_POST['credit_item_orders_products_id']]['final_price']), 2) . 'x' . tep_db_input($_POST['credit_fee_percent']) . '%',
												'date_added' => 'now()',
												'admin_id' => $login_id
											);
											tep_db_perform(TABLE_CUSTOMERS_CREDITS, $sql_data_credits_array);
											
											
										}
										// Howard added from $status == '100080' rmove to here end
            
									}
									
									$mail_subject = 'Refunded - Reservation # '.ORDER_EMAIL_PRIFIX_NAME.$oID;
									//$login_publicity_name = ucfirst(get_login_publicity_name());
									$login_publicity_name = tep_get_admin_customer_name($login_id);
									$phones = tep_get_us_contact_phone();
									$mail_body .= sprintf(EMAIL_FOOTER_SIGNATURE, $login_publicity_name, $phones[0]['name'].$phones[0]['phone'].'  '.$phones[2]['name'].$phones[2]['phone']);
									
									$check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
									$check_status = tep_db_fetch_array($check_status_query);							
									$to_name = $check_status['customers_name'];
									$to_email_address = $check_status['customers_email_address'];
									tep_mail($to_name, $to_email_address, $mail_subject, $mail_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
							 
									$sql_data_array = array('orders_id' => (int)$oID,
												  'orders_status_id' => $det_changed_orders_status,
												  'date_added' => 'now()',
												  'customer_notified' => '1',
												  'updated_by' => $login_id,
												  'comments' => tep_db_input($addon_refund_comment.str_replace('\n','<br>',$mail_body))
												  );
									tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
							}
					
					}
					
				 }else{
				 	// no udpate new status recover previouse once
				 	$get_current_latest_order_status = get_latest_orders_status_from_order_id((int)$oID);
					
					if($get_current_latest_order_status != ''){
						$customer_notified = "0";
						if($get_current_latest_order_status=='100006'){	//如果是已收款状态则要让客户前台也显示此状态
							$customer_notified = '1';
						}
						$sql_data_array = array('orders_id' => (int)$oID,
								  'orders_status_id' => $get_current_latest_order_status,
								  'date_added' => 'now()',
								  'customer_notified' => $customer_notified,
								  'updated_by' => $login_id,
								  'comments' => 'Change status back.'
								  );
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					}
					
				 }				 
				 //amit added for auto append order status history end	
				if(!ereg('^((((1[6-9]|[2-9][0-9])[0-9]{2})-(0?[13578]|1[02])-(0?[1-9]|[12][0-9]|3[01]))|(((1[6-9]|[2-9][0-9])[0-9]{2})-(0?[13456789]|1[012])-(0?[1-9]|[12][0-9]|30))|(((1[6-9]|[2-9][0-9])[0-9]{2})-0?2-(0?[1-9]|1[0-9]|2[0-8]))|(((1[6-9]|[2-9][0-9])(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-)) (20|21|22|23|[0-1]?[0-9]):[0-5]?[0-9]:[0-5]?[0-9]$',$_POST['settlement_date'])){
					$_POST['settlement_date'] = gmdate('Y-m-d H:i:s', time()-28800);
				}
				 $sql_data_settlement_array = array(		
				 						'orders_id' =>  (int)$oID,
										'order_value' => tep_db_prepare_input($_POST['order_value']),			
										'orders_payment_method' => tep_db_prepare_input($_POST['orders_payment_method']),
										'reference_comments' => tep_db_prepare_input($_POST['reference_comments']),	
										'settlement_date' => $_POST['settlement_date'],	
										'settlement_date_short' => substr($_POST['settlement_date'],0,10),
										'date_added' => GetTime(5, false, true),
										'updated_by' => $login_id									
										);									
				//tep_db_perform(TABLE_ORDERS_SETTLEMENT_INFORMATION, $sql_data_settlement_array, 'update', "orders_id = '" . (int)$oID . "'");
				tep_db_perform(TABLE_ORDERS_SETTLEMENT_INFORMATION, $sql_data_settlement_array);		
				
				// Howard added Credit Issued 客户信用扣款操作{
				if($updateCREDITS == true){
					tep_db_query('update customers set customers_credit_issued_amt='.$customer_new_credit_bal.' where customers_id ="'.$_customers_id.'" ');
					$sql_data_credits_array = array('customers_id' => (int)$_customers_id,
										'orders_id' => (int)$oID,
										'credit_bal' => (tep_db_prepare_input($_POST['order_value'])*(-1)),
										'credit_comment' => 'Credit Applied',
										'date_added' => 'now()',
										'admin_id' => $login_id
										);
					tep_db_perform(TABLE_CUSTOMERS_CREDITS, $sql_data_credits_array);
				}
				// Howard added Credit Issued 客户信用扣款操作}
				
				$show_heading=tep_get_settlement_heading((int)$oID);
				if($show_heading==true){
					echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td class="errorText">'.PINK_ROW_EXPLAINATION_TEXT.'</td></tr></table>';
				}
				$messageStack->add('Updated Settlement Information', 'success');
				//end of tour categorization section	}
			break;		
		
		 } //end of swich 
			
} //end of check proccess





if ($messageStack->size > 0) {
        echo $messageStack->output();				
} 

switch ($HTTP_GET_VARS['section']) {
	case 'order_settlement_history':
		?>
		<table border="0" cellpadding="0" cellspacing="0">
		<?php if($HTTP_GET_VARS['setthideheading'] != 'true') {?>
		<tr>
        <td class="col_b1"><strong>Settlement Update History</strong></td>
        </tr>
		<?php } ?> 
	    <tr>
        <td>
				<table cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
					  <tr>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Charge Captured Date (PST Time)</strong></td>
						<td class="tab_t tab_line1_wo_bot_border"><strong>Payment Method</strong></td>
						<td class="tab_t tab_line1_wo_bot_border"><strong>Amount</strong></td>
						<td class="tab_t tab_line1_wo_bot_border"><strong>Reference Comment</strong></td>
						<td class="tab_t tab_line1_wo_bot_border"><strong>Update By</strong></td>						
						<td class="tab_t"><strong>Modify Date</strong></td>
					  </tr>
					  
					  <?php 
					  	$ord_settle_prods_detail_history_sql = "select * from ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi where orders_id ='".(int)$oID."' order by orders_settlement_id asc ";
						$ord_settle_prods_detail_history_query = tep_db_query($ord_settle_prods_detail_history_sql);
						while($ord_settle_prods_detail_history_row = tep_db_fetch_array($ord_settle_prods_detail_history_query)){
					  ?>
					  <tr>
					    <td class="tab_line1_wo_bot_border p_l1"><?php echo $ord_settle_prods_detail_history_row['settlement_date'];?></td>
						<td class="tab_line1_wo_bot_border p_l1"><?php echo $ord_settle_prods_detail_history_row['orders_payment_method'];?></td>
						<td class="tab_line1_wo_bot_border p_l1" align="right">
						<?php 										
							if($ord_settle_prods_detail_history_row['order_value'] < 0) {
								echo '<span class="errorText">'.number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', '').'</span>';	
							}else{
								echo number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', '');
							}	
						?>&nbsp;</td>
						<td class="tab_line1_wo_bot_border p_l1"><?php echo tep_db_prepare_input(nl2br($ord_settle_prods_detail_history_row['reference_comments'])); ?></td>
						<td class="tab_line1_wo_bot_border p_l1"><?php echo tep_get_admin_customer_name($ord_settle_prods_detail_history_row['updated_by']);?></td>
						<td class="order_default p_l1"><?php echo $ord_settle_prods_detail_history_row['date_added'];?></td>
					  </tr>		
					  <?php } ?>			  
					</table>

		</td>
      </tr>
		</table>
		<?php		
	break;
	case 'order_settlement':		
		//amit added for settlemetn info start
		$select_settelment_info_array = tep_get_settlement_informations($oID);		
		//amit added for settlemetn info end
		if(isset($HTTP_GET_VARS['action']) &&  $HTTP_GET_VARS['action'] == 'edit'){
		?>
		<?php 
		  $show_heading=tep_get_settlement_heading($HTTP_GET_VARS['oID']);
		  if($show_heading==true) { ?>
		  <table cellspacing="0" cellpadding="0" border="0">
		  <tr>
			<td class="errorText"><?php echo PINK_ROW_EXPLAINATION_TEXT; ?></td>
		  </tr> 
		  </table>
		<?php }?>
		 <form name="<?php echo $ajax_formname;?>"  id="<?php echo $ajax_formname;?>">	
		<table cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
			  <tr>
				<td class="tab_t tab_line1"><strong>Charge Captured Date (PST Time)</strong></td>
				<td class="tab_line1 p_l1">
				<?php //echo tep_date_short($select_settelment_info_array['date_added']);?>
				<?php //echo tep_draw_input_field('settlement_date', tep_date_short($select_settelment_info_array['settlement_date']),'size="25"');
				/*
				$settlement_final_date = gmdate('Y-m-d h:i:s', time()-28800);
				//$settlement_final_date = '2009-11-12 00:00:01';
				$settlement_time = gmdate('his', time()-28800);
				if((int)$settlement_time >= 0 && (int)$settlement_time <= 170000){
					$settlement_final_date = $settlement_final_date; 
					//gmdate('Y-m-d', time()-28800) . ' ' . gmdate('h:i:s', time()-28800);
				}else{
					$settlement_date_only = gmdate('Y-m-d');
					$settlement_final_date = gmdate($settlement_date_only, strtotime("+1 day")). ' ' . gmdate('h:i:s', time()-28800);		
				}
				*/				
				// commneted version 2			
				/*
				$settlement_final_date = gmdate('Y-m-d H:i:s', time()-28800);
				
				$settlement_time = gmdate('His', time()-28800);
				if((int)$settlement_time >= 0 && (int)$settlement_time <= 170000){					
					$settlement_final_date = $settlement_final_date;
				}else{
					$settlement_final_date = gmdate('Y-m-d H:i:s', strtotime("+1 day")-28800);
				}	
				*/
				$settlement_final_date = GetTime(5, false, true);  //timezone , settlemetnt_time formate, daytimesaving  -- PST TIME
				$settlement_time = GetTime(5, true, true);				
				if((int)$settlement_time >= 0 && (int)$settlement_time <= 170000){					
					$settlement_final_date = $settlement_final_date;
				}else{
					$settlement_final_date = date('Y-m-d H:i:s', strtotime($settlement_final_date . ' + 1 day'));
				}	
				?>
				<?php echo tep_draw_input_field('settlement_date', $settlement_final_date,'size="25"'); ?>
				</td>
			    <td rowspan="6" class="tab_line1 p_l1" valign="top">
						<table border="0" cellpadding="2" cellspacing="0" >
						    <tr><td colspan="2" class="smallText" nowrap="nowrap"><b>Select to Add Payment Method</b></td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Last 4 Digits of CC: \r\nTransaction ID', this.value)" value="Credit Card"></td><td class="smallText">Credit Card</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('US Wire (5456)', this.value)" value="US Wire"></td><td class="smallText">US Wire</td></tr>
							<?php /*?><tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('China Wire (CR)', this.value)" value="China Wire"></td><td class="smallText">China Wire</td></tr><?php */?>							
							
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash Deposit to China Bank(CMB: 8015)', this.value)" value="Cash Deposit to China Bank(CMB)"></td><td class="smallText">Cash Deposit to China Bank(CMB: 8015)</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash Deposit to China Bank(CCB: 2113)', this.value)" value="Cash Deposit to China Bank(CCB)"></td><td class="smallText">Cash Deposit to China Bank(CCB: 2113)</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash Deposit to China Bank(ICBC: 924)', this.value)" value="Cash Deposit to China Bank(ICBC)"></td><td class="smallText">Cash Deposit to China Bank(ICBC: 924)</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash Deposit to China Bank(BOC: 192)', this.value)" value="Cash Deposit to China Bank(BOC)"></td><td class="smallText">Cash Deposit to China Bank(BOC: 192)</td></tr>
							
							
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Check/Cashier\'s check/Money Order (5456)', this.value)" value="Check/Cashier\'s check/Money Order"></td><td class="smallText">Check/Cashier's check/Money Order</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash Deposit to US Bank', this.value)" value="Cash Deposit to US Bank"></td><td class="smallText">Cash Deposit to US Bank</td></tr>		
																			
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash to US Office (received by  )', this.value)" value="Cash to US Office"></td><td class="smallText">Cash to US Office</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash to China Office (received by  )', this.value)" value="Cash to China Office"></td><td class="smallText">Cash to China Office</td></tr>							
							<?php /*?><tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Cash to China Bank', this.value)" value="Cash to China Bank"></td><td class="smallText">Cash to China Bank</td></tr><?php */?>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Customer\'s Credit Card Paid to Provider', this.value)" value="Customer\'s Credit Card Paid to Provider"></td><td class="smallText">Customer's Credit Card Paid to Provider</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Paypal Unique Transaction ID', this.value)" value="Paypal"></td><td class="smallText">Paypal</td></tr>
							<tr><td><input type="checkbox" name="append_pmethod" onClick="append_settle_payment_method('Credit Issued', this.value)" value="Credit Issued"></td><td class="smallText">Credit Issued</td></tr>
						</table>
				
				</td>
			  </tr>
			  <tr>
				<td class="tab_t tab_line1"><strong>Payment Method</strong></td>
				<td class="tab_line1 p_l1">				
				<?php 
				if( $select_settelment_info_array['orders_settlement_id'] > 0){
				echo tep_draw_input_field('orders_payment_method', '','size="52"'); //$select_settelment_info_array['payment_method']
				}else{
				//$paymentmethodname = tep_get_order_payment_method_name($oID);
				$paymentmethodname = 'Credit Card';
				echo tep_draw_input_field('orders_payment_method', $paymentmethodname,'size="52"');
				}
				 ?>				</td>
		      </tr>
			   <tr>
				<td class="tab_t tab_line1"><strong>Amount</strong></td>
				<td class="tab_line1 p_l1">
				<?php //echo $select_settelment_info_array['text'];
				//$select_settelment_info_array['order_value']
				?>
				<?php 
				if( $select_settelment_info_array['orders_settlement_id'] > 0 && $select_settelment_info_array['payment_method'] != 'Credit Applied'){
				echo tep_draw_input_field('order_value', '0.00','size="52"');
				}else{				
				echo tep_draw_input_field('order_value', number_format($select_settelment_info_array['value'], 2, '.', ''),'size="52"');
				}
				 ?>			</td>
		      </tr>
			  <tr>
				<td class="tab_t tab_line1"><strong>Received by</strong></td>
				<td class="tab_line1 p_l1"><?php echo tep_get_admin_customer_name($select_settelment_info_array['updated_by']); ?>&nbsp;</td>
		      </tr>
			   <tr>
				<td class="tab_t tab_line1"><strong>Need to Change Order Status?</strong></td>
				<td class="tab_line1 p_l1"><input type="radio" name="change_status" checked="checked" id="check_yes" onClick="javascript:toggel_div_show('change_order_status_yes');" value="1" /><label for="yes" style="cursor:pointer;">Yes</label> <input type="radio"  name="change_status" id="check_no" onClick="javascript:toggel_div_hide('change_order_status_yes');" value="0" /><label for="no" style="cursor:pointer;">No</label><br/>
					<div id="change_order_status_yes" style="display:block;">
					    <br/>						
						<label>Select Order Status:</label>
						<select id="changed_order_status_select" name="changed_order_status_select" onchange="if(document.settele_order.changed_order_status_select.value == '100005'){ toggel_div_show('refund_type_select_div_id'); document.settele_order.reference_comments.value = 'Last 4 Digits of CC: \r\nTransaction ID: '; document.settele_order.orders_payment_method.value='Credit Card'; }else{ toggel_div_hide('refund_type_select_div_id'); document.settele_order.reference_comments.value = ''; document.settele_order.orders_payment_method.value='';}">
							<option value="100006" selected="selected">Charge Captured (I)</option>
							<option value="100095">To Charge Capture<!--  Weiyi to Capture (I--></option>
							<option value="100005">Refunded</option>							
						</select>
						<br/>
						<br/>
					</div>
					<div id="refund_type_select_div_id" style="display:none;">
						<label><?php echo tep_draw_separator('pixel_trans.gif', '112', '1'); ?></label>
						<select id="refund_type_select" name="refund_type_select" onchange="refunc_changed_payment_method_refrence_comment(this.value);">
							<option value="100005-1" selected="selected">By Credit Card</option>
							<option value="100005-2">By PayPal</option>
							<option value="100005-3">By Check</option>							
							<option value="100005-4">By Cash Payment</option>
							<option value="100005-5">By Wire Transfer</option>
							<option value="100005-6">By Credit Issued</option>
						</select>
						<br/>
						<br/>
					</div>
					
					<?php // Howard added CreditIssuedTable start?>
					<div id="CreditIssuedTable" style="display:none; background-color:#F5F5F5">
					<table width="100%">                        
    <tr>                        
        <td width="40%" class="order_default" valign="top" rowspan="2">                        
            <table width="100%">                        
                <?php
                $cancel_item_orders_products_id = $order->products[0]['orders_products_id'];
                if (sizeof($order->products) > 1) {
                    for ($i = 0; $i < sizeof($order->products); $i++) {
                        if ($i == 0) {
                            $checked = ' checked="checked"';
                        } else {
                            $checked = '';
                        }
                ?>
                        <tr>                            
                            <td class="order_default"><input type="radio" name="credit_item" value="<?php echo $order->products[$i]['id']; ?>" <?php echo $checked; ?> onClick="set_credit_item(<?php echo $order->products[$i]['orders_products_id']; ?>);" />&nbsp;<?php echo '<b>' . $order->products[$i]['model'] . '</b>&nbsp;' . $order->products[$i]['name'] . '&nbsp;' . ($order->products[$i]['is_hotel'] == 1 ? '(' . tep_date_short($order->products[$i]['products_departure_date']) . ')' : ''); ?></td>
                        </tr>                            
                <?php
                    }
                } else {
                ?>
                    <input type="hidden" name="credit_item" value="<?php echo $order->products[0]['id']; ?>" />
                <?php
                }
                ?>
                <input type="hidden" id="credit_item_orders_products_id" name="credit_item_orders_products_id" value="<?php echo $cancel_item_orders_products_id; ?>" />
                <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
                <tr>
                    <td class="order_default"><b>Reason for Credit Issued:</b></td>
                </tr>
                <tr>
                    <td class="order_default"><input type="checkbox" name="credit_reason[]" id="credit_reason_resnotavail" value="reservation not available"  <?php //if(in_array('reservation not available', $get_cancel_reasons)){ echo 'checked="checked"';}   ?> />&nbsp;Reservation not available</td>
                </tr>
                <tr>
                    <td class="order_default">
                        <input type="checkbox" name="credit_reason[]" id="credit_reason_reaquire" value="Re-aquire cancelled Order" onChange="show_credit_amt_div(this.value);" />&nbsp;Re-aquire cancelled Order
                    </td>
                </tr>								
                
                <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
                <tr>
                    <td class="order_default"><b>Did you cancel with provider?</b></td>
                </tr>
                <tr>
                    <td class="order_default"><input type="checkbox" name="credit_with_provider[]" value="Provider already confirmed cancellation" /> Provider already confirmed cancellation.<br /><input type="checkbox" name="credit_with_provider[]" value="I have sent cancellation request to provider" /> I have sent cancellation request to provider.</td>
                </tr>
                <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
                <tr>
                    <td class="order_default">Estimated Provider Credit Fee: <input type="text" value="<?php //echo $order->info['cancellation_fee'];    ?>" name="provider_credit_fee" class="order_textbox" style="text-align:right; " size="5" onKeyPress="return disableEnterKey(event);" /></td>
                </tr>
            </table>
        </td>
        <td width="45%" valign="top" rowspan="2">
            <table width="100%">
                <tr>
                    <td class="order_default" colspan="2"><b>Customer's cancellation was received on: </b></td>
                </tr>
                <tr>
                    <td class="order_default" colspan="2">
                        <?php /* 
						<script type="text/javascript">
                            var dateCredit = new ctlSpiffyCalendarBox("dateCredit", "edit_order", "credit_date","btndateCredit","",scBTNMODE_CUSTOMBLUE);

                            dateCredit.writeControl(); dateCredit.dateFormat="MM/dd/yyyy";
                        </script>
						*/?>
						<input type="text" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="" name="credit_date" class="textTime">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="order_default"><b>Credit Issue Adjustments:</b></td>
                </tr>
                <?php
                $get_order_total = tep_db_fetch_array(tep_db_query("select value,text from " . TABLE_ORDERS_TOTAL . " where class = 'ot_total' and orders_id = '" . $oID . "'"));
                ?>
                <tr>
                    <td class="col_h" nowrap>Customer Payment: </td>
                    <td align="right" class="order_default"><div id="customer_payment_credit">
					<?php /*<script type="text/javascript">document.write('$'+document.edit_order.elements['update_products['+<?php echo $order->products[0]['orders_products_id']; ?>+'][final_price]'].value);</script>*/?><?php //echo $get_order_total['text']."<br>";?><?php echo $update_products[$order->products[0]['orders_products_id']]['final_price']?></div></td>
                </tr>
                <tr id="show_cancel_on_credit" style="display:none ">
                    <td class="col_h" nowrap>Cancellation Fee: </td>
                    <td align="right" class="order_default" nowrap>-$<input type="text" value="<?php //echo $order->info['cancellation_fee'];    ?>" id="credit_cancellation_fee" name="credit_cancellation_fee" class="order_textbox" style="text-align:right; " size="5" onChange="get_return_amount1(this.value, '', 'cancel');" onKeyPress="return disableEnterKey(event);"/></td><td class="order_default" nowrap="nowrap"> &nbsp; Or &nbsp; <input type="text" value="" id="credit_cancellation_fee_percent" name="credit_cancellation_fee_percent" class="order_textbox" style="text-align:right; " size="5" onChange="get_return_amount1(this.value, 'fee_percent', 'cancel');" onKeyPress="return disableEnterKey(event);" /> %</td>
                </tr>																
                <tr>
                    <td class="col_h" nowrap>Amount Credited: </td>
                    <td align="right" class="order_default" nowrap>-$<input type="text" value="<?php //echo $order->info['cancellation_fee'];   ?>" id="credit_fee" name="credit_fee" class="order_textbox" style="text-align:right; " size="5" onChange="get_return_amount1(this.value);" onKeyPress="return disableEnterKey(event);" /></td><td class="order_default" nowrap="nowrap"> &nbsp; Or &nbsp; <input type="text" value="" id="credit_fee_percent" name="credit_fee_percent" class="order_textbox" style="text-align:right; " size="5" onChange="get_return_amount1(this.value, 'fee_percent');" onKeyPress="return disableEnterKey(event);" /> %</td>
                </tr>																
                <tr>
                    <td class="col_h" nowrap>Adjusted Retail Price: </td>
                    <td align="right" class="order_default"><div id="return_amount_credit"><?php /*<script type="text/javascript">document.write('$'+document.edit_order.elements['update_products['+<?php echo $order->products[0]['orders_products_id']; ?>+'][final_price]'].value);</script> */?>
                        <?php //echo number_format($get_order_total['value'], 2);?><?php echo $update_products[$order->products[0]['orders_products_id']]['final_price']?></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="order_default"><span class="col_1">How did you calculate the cancellation fee? </span><br /><textarea name="credit_reason_detail" rows="2" cols="30" class="order_textarea"></textarea></td>
                </tr>
            </table>
        </td>
        <td width="15%" valign="top">
        </td>
    </tr>
</table>
					</div>
					<?php // Howard added CreditIssuedTable end ?>
				</td>
		      </tr>
			  <tr>
				<td class="tab_t tab_line1"><strong>Reference Comment</strong></td>
				<td class="tab_line1 p_l1">
				<?php 
				$default_refernce_comment_value = '';				
				if( $select_settelment_info_array['orders_settlement_id'] > 0){
					//$default_refernce_comment_value = $select_settelment_info_array['reference_comments'];					
				}else{
					if($paymentmethodname == 'Credit Card'){
						$default_refernce_comment_value = 'Last 4 Digits of CC: '.chr(13).chr(10).'Transaction ID: ';
					}					
				}				
				echo tep_draw_textarea_field('reference_comments', 'soft', '50', '10', $default_refernce_comment_value ,'id=reference_comments');
				 ?>				</td>
		      </tr>		
			  <tr>
				<td class="tab_t tab_line1">&nbsp;<input type="hidden" name="auajax_submit" id= "auajax_submit" value="true"></td>
				<td class="tab_line1 p_l1"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick=" if(check_ref_comment_added(document.settele_order.reference_comments.value, document.settele_order.orders_payment_method.value) == true){ sendFormData(\''.$ajax_formname.'\',\''. tep_href_link('orders_settlement_ajax.php', 'action=process&section=order_settlement&ajax_formname='.$ajax_formname.'&oID=' . $HTTP_GET_VARS['oID']).'\',\'ajax_settle_response\',\'true\',\'refreshtrue\',\''.tep_href_link(FILENAME_EDIT_ORDERS, 'oID='.(int)$HTTP_GET_VARS['oID']).'\'); } else { return false;}" ');?>
				<?php echo tep_image_submit('button_cancel.gif', IMAGE_CANCEL, ' onclick="sendFormData(\''.$ajax_formname.'\',\''. tep_href_link('orders_settlement_ajax.php', 'section=order_settlement&ajax_formname='.$ajax_formname.'&oID=' . $HTTP_GET_VARS['oID']).'\',\'ajax_settle_response\',\'true\');" ');?>
				</td>
		      </tr>			 
			</table>
</form>
		<?php
		}else{
		?>			
		<form name="<?php echo $ajax_formname;?>"  id="<?php echo $ajax_formname;?>">		
			<table cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
			  <tr>
				<td class="tab_t tab_line1"><strong>Charge Captured Date (PST Time)</strong></td>
				<td class="tab_line1 p_l1"><?php echo $select_settelment_info_array['settlement_date'];?></td>
			  </tr>
			  <tr>
				<td class="tab_t tab_line1"><strong>Payment Method</strong></td>
				<td class="tab_line1 p_l1"><?php echo $select_settelment_info_array['payment_method'];?></td>
			  </tr>
			   <tr>
				<td class="tab_t tab_line1"><strong>Amount</strong></td>
				<td class="tab_line1 p_l1"><?php 
				/*
				if($select_settelment_info_array['order_value'] != 0){
				*/	
					if($select_settelment_info_array['order_value'] < 0) {
						echo '<span class="errorText">'.number_format($select_settelment_info_array['order_value'], 2, '.', '').'</span>';	
					}else{
						echo number_format($select_settelment_info_array['order_value'], 2, '.', '');
					}			
				/*	
				}else{
					echo $select_settelment_info_array['text'];
				}
				*/
				?></td>
			  </tr>
			  <tr>
				<td class="tab_t tab_line1"><strong>Received by</strong></td>
				<td class="tab_line1 p_l1"><?php echo tep_get_admin_customer_name($select_settelment_info_array['updated_by']); ?></td>
			  </tr>
			  <tr>
				<td class="tab_t tab_line1"><strong>Reference Comment</strong></td>
				<td class="tab_line1 p_l1"><?php echo tep_db_prepare_input(nl2br($select_settelment_info_array['reference_comments']));?></td>
			  </tr>			 
			</table>
			</form>
		<?php } ?>	
		<?php
		//end of tour categorization section
    break;
 }
?>