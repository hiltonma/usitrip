<?php
		$osCsid_string = '';
		if(tep_not_null($_GET['osCsid'])){
			$osCsid_string = '&osCsid='.(string)$_GET['osCsid'];
		}
?>
<?php
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : (int)$_GET['order_id'];
$orders_travel_companion_ids = isset($_POST['orders_travel_companion_ids']) ? (array)$_POST['orders_travel_companion_ids'] : (array)$_GET['orders_travel_companion_ids'];

$orders_travel_companion_id_str = implode(',',$orders_travel_companion_ids);

if(tep_not_null($orders_travel_companion_id_str)){

//取得付款总额和参团人信息
$payables_total_sql = tep_db_query('SELECT products_id, payables, guest_name, paid FROM `orders_travel_companion` WHERE orders_travel_companion_id IN('.$orders_travel_companion_id_str.') GROUP BY orders_travel_companion_id ');
$payables_total = 0;
$guest_name = '';
while($payables_total_row = tep_db_fetch_array($payables_total_sql)){
	$payables_total += $payables_total_row['payables'];
	//减去已经付款
	$payables_total -= $payables_total_row['paid'];
	
	$guest_name .= tep_db_output($payables_total_row['guest_name']).' , ';
	$products_id = $payables_total_row['products_id'];
}
$guest_name = preg_replace('/ , $/','',$guest_name);

//返回页设置
$go_back_page = tep_href_link('travel_companion_pay.php', 'order_id='.(int)$order_id.'&orders_travel_companion_ids='.$orders_travel_companion_id_str.'&products_id='.(int)$products_id, 'SSL');
?>
					 <!-- content main body start -->
                     <div style="">

		                                                    
                                           <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		                                                      
                                             <tr>
                                               <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                 <tr>
                                                   <td class="infoBoxHeading"><div class="heading_bg"><?php echo db_to_html('行程信息'); ?></div></td>
							    </tr>
                                               </table></td>
						    </tr>
                                             <tr>
                                               <td>
                                                 <div class="infoBox_outer">
                                                   <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox_new">
                                                     <tr class="infoBoxContents_new">
                                                       <td>
                                                         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:10px;">
                                                             <tr>
                                                               <td align="right" nowrap="nowrap"><?php echo db_to_html('订单号：');?></td>
											  <td align="left"><b><?php echo $order_id?></b></td>
										    </tr>
                                                             <tr>
                                                               <td align="right"><?php echo db_to_html('团　名：');?></td>
											  <td><b><?php echo db_to_html(tep_get_products_name($products_id))?></b></td>
										    </tr>
                                                             <tr>
                                                               <td align="right"><?php echo db_to_html('付款人：');?></td>
											  <td><b><?php echo db_to_html($guest_name)?></b></td>
										    </tr>
                                                             <tr>
                                                               <td align="right"><?php echo db_to_html('金　额：');?></td>
											  <td><b><?php echo $currencies->display_price($payables_total,0, 1);?></b></td>
										    </tr>
                                                           </table>									  </td>
								    </tr>
                                                   </table>
							    </div>							    </td>
							    </tr>								
		                                                      
                                               <?php
								//付款方式
								// BOF: Lango modified for print order mod
								  if (is_array($payment_modules->modules)) {
									if ($confirmation = $payment_modules->confirmation()) {
									  $payment_info = $confirmation['title'];
									  if (!tep_session_is_registered('payment_info')) tep_session_register('payment_info');
								// EOF: Lango modified for print order mod
								?>
                                                     <tr>
                                                       <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
									    </tr>
                                                     <tr>
                                                       <td class="infoBoxHeading"><div class="heading_bg"><?php echo HEADING_PAYMENT_METHOD; ?></div></td>
									    </tr>
		                                                      
                                                     <tr>
                                                       <td>
                                                         <div class="infoBox_outer">
		                                                            
                                                           <table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
                                                             <tr class="infoBoxContents_new">
                                                               <td style="padding: 10px 15px;"><table border="0" cellspacing="0" cellpadding="2">
                                                                 <tr>
                                                                   <td class="main" colspan="3"><span style="font-size:16px; color:#FF6600; font-weight: bold;"><?php echo $payment_name; ?></span></td>
											    </tr>
                                                                 <tr><td class="gray_dotted_line" colspan="3"></td></tr>
                                                                 <tr>
                                                                   <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
											    </tr>
		                                                                  
                                                                 <?php
									  for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
								?>
                                                                 <tr>
                                                                   <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
												  <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
												  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
												  <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
											    </tr>
                                                                 <?php
									  }
								?>
                                                               </table></td>
										    </tr>
                                                           </table>
										</div>										  </td>
									    </tr>
                                               <?php
									}else if($payment== 'paypal'){
										?>
                                                       <tr>
                                                         <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
									    </tr>
                                                     <tr>
                                                       <td class="infoBoxHeading"><div class="heading_bg"><?php echo HEADING_PAYMENT_INFORMATION; ?></div></td>
									    </tr>
		                                                      
                                                     <tr>
                                                       <td>
                                                         <div class="infoBox_outer">
		                                                            
                                                           <table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
                                                             <tr class="infoBoxContents_new">
                                                               <td style="padding: 10px 15px;"><table border="0" cellspacing="0" cellpadding="2" width="100%">
                                                                 <tr>
                                                                   <td class="main" colspan="3"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b>'; ?></td>
											    </tr>
                                                                 <tr>
                                                                   <td class="main" colspan="3"><span style="font-size:16px; color:#FF6600; font-weight: bold;"><?php echo $payment_name; ?></span></td>
											    </tr>
                                                                 </table></td>
										    </tr>
                                                           </table>
										</div>										  </td>
									    </tr>
                                                       <?php
									}
								  }
								  //付款方式end 
								?>
		                                                      
                                <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
							    </tr>
								
								<?php if($payment == 'authorizenet'){//帐单地址?> 
									<tr>
									<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
									  <tr>
										<td class="infoBoxHeading"><div class="heading_bg"><?php echo db_to_html('信用卡地址'); ?></div> <div class="head_note" style="color:#f68711;"><?php //echo TABLE_HEADING_BILLING_ADDRESS_EXP; ?></div></td>
									  </tr>
									</table>
									</td>
								  </tr>
								
								  <tr>
									<td>
										<div id="response_billing_div">
										<?php
								//amit added move billing address exits query above don't delete
								$billto = ((int)$billto) ? $billto : $customer_default_address_id;
								
								$check_address_blank_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone, c.customers_mobile_phone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$billto . "'");
								$row_check_address_blank = tep_db_fetch_array($check_address_blank_query);
								//amit added to move billing address exits query above don't delete 
								
										if($row_check_address_blank['street_address']=='' && $row_check_address_blank['city']==''){
											$style_show_address_div = ' style="display:none;"';
											$style_show_edit_address_div = '';
										}else{
											$style_show_address_div = '';
											$style_show_edit_address_div = ' style="display:none;"';
										}
										$osCsid_string = '';
										if(tep_not_null($_GET['osCsid'])){
											$osCsid_string = '&osCsid='.(string)$_GET['osCsid'];
										}
										?>
								
										<div id="show_address_div" <?php echo $style_show_address_div; ?>>
											<div class="infoBox_outer">
											<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
											  <tr class="infoBoxContents_new">
												<td>
												<table border="0" width="100%" cellspacing="0" cellpadding="2">
													 
												  <tr>
													<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
													<td align="left" width="100%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
													  <tr>
														
														<td class="main" align="center" valign="top"><b><?php //echo TITLE_BILLING_ADDRESS; ?></b><?php //echo tep_image('image/'. 'arrow_south_east.gif'); ?></td>
														
														<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
														<td class="main" valign="top"><b><?php echo db_to_html(tep_address_label($customer_id, $billto, true, ' ', '<br />'));
														
														 ?></b></td>
									
														<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
													  </tr>
													</table></td>
													
													<td align="right" class="main" width="1%" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
												  </tr>
													<tr>
														<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
														</tr>
												</table></td>
											  </tr>
											</table>
											</div>
										</div>
										
										</div>	
									</td>
								  </tr>
                                <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
							    </tr>
								 
								 <?php }//帐单地址end?> 								
								
								<?php //通信地址 start 
														 ?> 
									<tr style="display:none">
									<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
									  <tr>
										<td class="infoBoxHeading"><div class="heading_bg"><?php echo db_to_html('通信地址'); ?></div> <div class="head_note" style="color:#f68711;"></div></td>
									  </tr>
									</table>
									</td>
								  </tr>
								
								  <tr>
									<td>
										<div id="response_ship_div">
										<?php

								$shipto = ((int)$shipto) ? $shipto : $customer_default_ship_address_id;
								
								$check_ship_address_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone, c.customers_mobile_phone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$shipto . "'");
								$row_check_ship_address = tep_db_fetch_array($check_ship_address_query);

								
										if($row_check_ship_address['street_address']=='' && $row_check_ship_address['city']==''){
											$style_show_address_div = ' style="display:none;"';
											$style_show_edit_address_div = '';
										}else{
											$style_show_address_div = '';
											$style_show_edit_address_div = ' style="display:none;"';
										}
										$osCsid_string = '';
										if(tep_not_null($_GET['osCsid'])){
											$osCsid_string = '&osCsid='.(string)$_GET['osCsid'];
										}
										?>
								
										<div id="show_ship_address_div" <?php echo $style_show_address_div; ?>>
											<div class="infoBox_outer">
											<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
											  <tr class="infoBoxContents_new">
												<td>
												<table border="0" width="100%" cellspacing="0" cellpadding="2">
													 
												  <tr>
													<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
													<td align="left" width="100%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
													  <tr>
														
														<td class="main" align="center" valign="top"><b><?php //echo TITLE_BILLING_ADDRESS; ?></b><?php //echo tep_image('image/'. 'arrow_south_east.gif'); ?></td>
														
														<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
														<td class="main" valign="top"><b><?php echo db_to_html(tep_address_label($customer_id, $shipto, true, ' ', '<br />'));
														//amit added shot telpphone number start
														if($row_check_ship_address['customers_telephone'] != ''){
															echo '<br />'.ENTRY_TELEPHONE_NUMBER.' '.db_to_html($row_check_ship_address['customers_telephone']);
														}
														if($row_check_ship_address['customers_fax'] != ''){
															echo '<br />'.ENTRY_FAX_NUMBER.' '.db_to_html($row_check_ship_address['customers_fax']);
														}
														if($row_check_ship_address['customers_mobile_phone'] != ''){
															echo '<br />'.TEXT_BILLING_INFO_MOBILE.' '.db_to_html($row_check_ship_address['customers_mobile_phone']);
														}
														//amit added show telephone number end
														 ?></b></td>
									
														<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
													  </tr>
													</table></td>
													
													<td align="right" class="main" width="1%" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
												  </tr>
													<tr>
														<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
														</tr>
												</table></td>
											  </tr>
											</table>
											</div>
										</div>
										
										</div>	
									</td>
								  </tr>
                                <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
							    </tr>
								 
								 <?php //通信地址 end?> 								
								
                                             <tr style="display:none">
                                               <td class="infoBoxHeading"><div class="heading_bg"><?php echo db_to_html('本次付款');?></div></td>
							    </tr>
		                                                      
                                             <tr style="display:none">
                                               <td class="main">
                                                 <div class="infoBox_outer">
                                                   <table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
                                                     <tr class="infoBoxContents_new">
                                                       <td style="padding: 10px 15px;"><table border="0" cellspacing="0" cellpadding="2" width="100%">
                                                         <tr>
                                                           <td class="main" colspan="3"><span style="font-size:16px; color:#FF6600; font-weight: bold;"><?php echo $currencies->display_price($i_need_pay,0, 1);?></span></td>
									    </tr>
                                                         </table></td>
								    </tr>
                                                   </table>
								  </div>								</td>
						      </tr>
                                             <tr>
                                               <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
							    </tr>
		                                                      
                                             <tr>
                                               <td class="main"> 
		                                                          
	                                                           <?php
												 
												if(is_array($orders_travel_companion_ids)){
													$orders_travel_companion_ids = implode(',',$orders_travel_companion_ids);
												}
												 //返回页面
												 $return_href_link = tep_href_link('orders_travel_companion_payment_results.php', 'order_id='.$orders_id.'&payment='.$payment.'&travel_companion_ids='.$orders_travel_companion_ids.'&pay_order_id='.$orders_id, 'SSL');
												 $cancel_href_link = tep_href_link('orders_travel_companion_info.php', 'order_id='.$orders_id, 'SSL');
												
												$form_action_url = tep_href_link('checkout_process_travel_companion.php', '', 'SSL');
												
												if($payment== 'paypal'){
													echo tep_draw_form('checkout_confirmation', $paypal->form_paypal_url, 'post','id="checkout_confirmation"');
												}else{
													echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','id="checkout_confirmation"  '); //'target="_blank" onsubmit="return checkCheckBox(this)"'
												}
												echo tep_draw_hidden_field('form_name','checkout_confirmation');
												echo tep_draw_hidden_field('payment');
												echo tep_draw_hidden_field('i_need_pay');
												echo tep_draw_hidden_field('order_id');
												echo tep_draw_hidden_field('orders_travel_companion_ids');
												echo tep_draw_hidden_field('guest_names');
												echo tep_draw_hidden_field('payables_total');
												
												
												//amit added to check cc info on checkout confirmation page start
												 
												  if(MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True' && $payment == 'authorizenet') {  
												  	//信用卡付款
													echo tep_draw_hidden_field('insert_id',$insert_id);
													echo tep_draw_hidden_field('authorizenet_cc_proccessed','true');
													echo tep_draw_hidden_field('avs_authorized_db_insert_note',tep_db_prepare_input($avs_authorized_db_insert_note));													
												  
													if (is_array($payment_modules->modules)) {
														echo $payment_modules->process_button();
													}
												  }elseif($payment == 'paypal'){
													//贝宝付款
													$orders_session_info_array['payment_amount'] = number_format($i_need_pay,2, '.', '');
													include_once(DIR_FS_MODULES . 'payment/paypal.php');
													$paypal = new PayPal();
													//$p_orders_ids = $orders_id.'-'.$_POST['orders_travel_companion_ids'];
													$p_orders_ids = $orders_id;
													echo $paypal->formFields($txn_signature, number_format($i_need_pay,2, '.', ''), 'USD', 'USD', $p_orders_ids, $return_href_link, $cancel_href_link);
													//echo tep_draw_hidden_field('item_name_0', '8888888999999999999');
													//echo tep_draw_hidden_field('item_number_0','444444444');
													
												  }
												?>
	                                                           
												  
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
												  <tr>
												    <td><a class="btn" href="<?php echo $go_back_page;?>"><span></span><?php echo db_to_html('返回上页')?><?php # echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
												    <td align="right" class="subbtn"><input type="submit" value="<?php echo db_to_html('确认提交')?>" /><?php # echo tep_template_image_submit('vote_submit.gif', IMAGE_BUTTON_CONFIRM_ORDER) . "\n";?></td>
											      </tr>
												  <tr>
                                               			<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
							    					</tr>
											     </table>
												 <table border="0"  width="100%" cellspacing="0" cellpadding="2">
	                                                             <tr>
		                                                              
		                                                              <td class="main" colspan="2" width="100%" align="right" >
		                                                                <?php
												  if (ACCOUNT_CONDITIONS_REQUIRED == 'true') {
												?>
		                                                                <label for="agree"><?php echo CONDITION_AGREEMENT; ?></label> <input type="checkbox" class="check-required" value="0" name="agree" id="agree" />
		                                                                <?php
												}
												?>
		                                                                <span id="submit_msn" style="color:#FF6600; display:<?php echo 'none';?>"><br /><?php echo TEXT_SUBMIT_MSN;?></span>																</td>
																  <td class="main" align="right" valign="middle" rowspan="2" width="25%">&nbsp;</td>
																  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															    </tr>
		                                                            <tr><td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td></tr>
		                                                            </table>
  
														
												  </form>														</td>
														    </tr>
		                                                      </table>	
                       
                       
                     </div>
         <script type="text/javascript">
			function formCallback(result, form) {
				window.status = "valiation callback for form '" + form.id + "': result = " + result;
			}
			
			var valid = new Validation('checkout_confirmation', {immediate : true,useTitles:true, onFormValidate : formCallback});
			
			Validation.add('check-required', '<?php echo CONDITION_AGREEMENT_WARNING; ?>', function(v,elm) { 
				return (elm.checked == true);
			});
			
		</script>



<?php
}
?>
