<?php 	

if(basename($PHP_SELF) == 'edit_orders.php'){
	//$ajax_formname = 'edit_order';
	$ajax_formname = 'settele_order';
}else{
	$ajax_formname = 'settele_order';
}

		/*
		//amit added if no settement information for old order start
		if(($order->info['orders_status'] == '100006' || $order->info['orders_status'] == '6' || $order->info['orders_status'] == '100005') && ((int)$oID != 0)){
			$settle_check_numb_row_sql = "select * from ".TABLE_ORDERS_SETTLEMENT_INFORMATION." where orders_id='".$oID."'";
			$settle_check_numb_row_query = tep_db_query($settle_check_numb_row_sql);
			if(tep_db_num_rows($settle_check_numb_row_query) == 0){
				
				$add_order_total_value = '';
				for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
					if( ( $order->totals[$i]['class'] != 'ot_total')  ||  ($order->totals[$i]['value'] > 0 ) ){
						 $add_order_total_value = $order->totals[$i]['value'];
						 $add_order_total_value = number_format($add_order_total_value, 2, '.', '');
					}			
				}
				
				
				$add_initial_payment_method = 'Credit Card';
				$add_initial_reference_comments = 'Credit Card (8658): ';					
				// insert new row with default comment and payment method start
				$last_sellemtn_array = tep_get_latest_settelemt_date($oID, $order->info['orders_status']);
				$sql_data_settlement_array = array(			  
										'orders_id' => $oID,
										'order_value' => $add_order_total_value,
										'orders_payment_method' => tep_db_prepare_input($add_initial_payment_method),
										'reference_comments' => tep_db_prepare_input($add_initial_reference_comments).' ' . $add_order_total_value,			
										'settlement_date' => $last_sellemtn_array['date_added'],		
										'updated_by' => $last_sellemtn_array['updated_by'],								
										'date_added' => 'now()',
									);
				tep_db_perform(TABLE_ORDERS_SETTLEMENT_INFORMATION, $sql_data_settlement_array);				
				// insert new row with default comment and payment method end
			}
			
		}
		//amit added if no settement information for old order end
		*/
		
		//if($access_full_edit == 'true' && ($order->info['orders_status'] == '100006' || $order->info['orders_status'] == '6' || $order->info['orders_status'] == '100005')) { 
		//amit added for settlemetn info start
		if($access_full_edit == 'true' || $login_groups_id == '5' || $login_id == '28' || $login_id == '64' || $login_id == '82'  || $login_id == '112' || $login_id == '165' || $login_id == '155'){
			$select_settelment_info_array = tep_get_settlement_informations($oID);			
		}	
		//amit added for settlemetn info end	
		if($access_full_edit == 'true'  || $login_groups_id == '5' || $login_id == '28' || $login_id == '64' || $login_id == '82'  || $login_id == '112' || $login_id == '165' || $login_id == '155'){
					
				?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr> 
			  
			  <tr>
				<td class="col_b1" nowrap><b><?php echo 'Charge Capture Information'; ?></b> 
				<?php //if(basename($PHP_SELF) == 'orders.php'){ ?>
				<a style="color:#0060FF" href="javascript:void(0);" <?php echo 'onclick="sendFormData(\''.$ajax_formname.'\',\''. tep_href_link('orders_settlement_ajax.php', 'action=edit&section=order_settlement&ajax_formname='.$ajax_formname.'&oID=' . $HTTP_GET_VARS['oID']).'\',\'ajax_settle_response\',\'true\');"'; ?>>[Edit]</a>&nbsp;&nbsp;
				<?php //} ?>
				<a style="color:#0060FF" href="javascript:void(0);" <?php echo 'onclick="sendFormData(\''.$ajax_formname.'\',\''. tep_href_link('orders_settlement_ajax.php', 'section=order_settlement_history&ajax_formname='.$ajax_formname.'&oID=' . $HTTP_GET_VARS['oID']).'\',\'ajax_settle_history_response\',\'true\');"'; ?>>[Detail History]</a>
				
				</td>
			  </tr> 
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr> 
			  <tr>
				<td class="order_f_tab1">
					<div id="ajax_settle_response">
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
					<?php                  
                    if( $select_settelment_info_array['orders_settlement_id'] > 0){ ?>
					  <tr>
						<td class="tab_t tab_line1"><strong>Charge Captured Date</strong></td>
						<td class="tab_line1 p_l1"><?php /*echo tep_date_short($select_settelment_info_array['settlement_date']); */ echo  tep_datetime_short($select_settelment_info_array['settlement_date']);?></td>
					  </tr>
					  <tr>
						<td class="tab_t tab_line1"><strong>Payment Method</strong></td>
						<td class="tab_line1 p_l1">
						<?php 
						echo tep_db_prepare_input($select_settelment_info_array['payment_method']);
						?>
						</td>
					  </tr>
					   <tr>
						<td class="tab_t tab_line1"><strong>Amount</strong></td>
						<td class="tab_line1 p_l1">
						<?php 
						
							if($select_settelment_info_array['order_value'] < 0) {
								echo '<span class="errorText">'.number_format($select_settelment_info_array['order_value'], 2, '.', '').'</span>';	
							}else{
								echo number_format($select_settelment_info_array['order_value'], 2, '.', '');
							}		
						
						?></td>
					  </tr>
					  <tr>
						<td class="tab_t tab_line1"><strong>Received by</strong></td>
						<td class="tab_line1 p_l1"><?php echo tep_get_admin_customer_name($select_settelment_info_array['updated_by']); ?></td>
					  </tr>
					  <tr>
						<td class="tab_t tab_line1"><strong>Reference Comment</strong></td>
						<td class="tab_line1 p_l1">
						<?php 
						echo tep_db_prepare_input(nl2br($select_settelment_info_array['reference_comments']));
						?>
						<input type="hidden" name="auajax_submit" id= "auajax_submit" value="true">
						</td>
					  </tr>	
					  <?php }else{
					  
					  		if(basename($PHP_SELF) == 'edit_orders.php' && $fource_else == true){
							?>
							<tr><td colspan="2" class="main">No Settlement History available. <?php echo '<a href="' . tep_href_link(FILENAME_ORDERS,'action=edit&'. tep_get_all_get_params(array('action','step')).'#settleanchor') . '"><b>Click here</b></a> link to Add Settlement Information'; ?></td></tr>
							<?php
							}else{
							echo '<tr><td colspan="2" class="order_note">No Settlement History available. Click on [Edit] link to Add Settlement Information</td></tr>';
							}
							echo '<tr><td><input type="hidden" name="sendhidden1" id="sendhidden1" value="true"><input type="hidden" name="auajax_submit" id= "auajax_submit" value="true"></td></tr>';
						
						} 
						?>				 			 
					</table>
					
					<?php //if(basename($PHP_SELF) == 'orders.php'){ ?>
					</form>
					<?php //} ?>
					</div>
				</td>		
				</tr>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr>	  	  
			  <tr>
				<td><div id="ajax_settle_history_response"></div>
				</td>
			  </tr>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr>
	  <?php } ?>	