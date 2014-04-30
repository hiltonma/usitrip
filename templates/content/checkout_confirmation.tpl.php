<?php
echo tep_get_design_body_header(HEADING_TITLE); 
//取得所有属于邮轮团的产品属性ID
$cruisesOptionIds = getAllCruisesOptionIds();
?>
<?php
		$osCsid_string = '';
		if(tep_not_null($_GET['osCsid'])){
			$osCsid_string = '&osCsid='.(string)$_GET['osCsid'];
		}
?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">														
							  <tr>
								<td class="main"> 
												<?php
												if (isset($$payment->form_action_url)) {
													$form_action_url = $$payment->form_action_url;
												} else {
													$form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
												}
												echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','id="checkout_confirmation"'); //'onsubmit="return checkCheckBox(this)"'
												echo tep_draw_hidden_field('form_name','checkout_confirmation');											
												
												//amit added to check cc info on checkout confirmation page start
												  if(MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True' && $payment == 'authorizenet') {  												  	
													echo tep_draw_hidden_field('insert_id',$insert_id);
													echo tep_draw_hidden_field('authorizenet_cc_proccessed','true');
													echo tep_draw_hidden_field('avs_authorized_db_insert_note',tep_db_prepare_input($avs_authorized_db_insert_note));	
												  }
												//amit added to check cc info on checkout confirmation page start  
												?>
												<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
													  <tr>
														<td align="center">	<div class="cart_progress">
		<div class="cart_left">
    		<em class="cart_icon"></em><h3>我的购物车</h3>
    	</div>
    	<div class="cart_right">
    		<ul>
        		<li class="first"><span></span>1.选择产品</li>
            	<li><span></span>2.查看购物车</li>
            	<li><span></span>3.确认行程信息</li>
            	<li class="cur"><span></span>4.完成订购</li>
            	<li class="last"><span class="cur"></span></li>
         	</ul>
    	</div>
	</div><!--<table width="0%" border="0" cellspacing="0" cellpadding="0">
														  <tr>
															<td><a href="<?= tep_href_link('checkout_info.php', '', 'SSL');?>" class="broud-line"><?php echo tep_template_image_button('tours_info2.gif','','')?></a></td>
															<td><img src="image/jiantou2.gif" style="margin-left:60px; margin-right:60px;" /></td>
															<td><a href="<?= tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');?>" class="broud-line"><?php echo tep_template_image_button('payment-info2.gif')?></a></td>
															<td><img src="image/jiantou2.gif" style="margin-left:60px; margin-right:60px;" /></td>
															<td><?php echo tep_template_image_button('check-info3.gif','','')?></td>
														  </tr>
														</table>--></td>
													  </tr>
												 
												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_top(false, false, $header_text);
												}
												// EOF: Lango Added for template MOD
												?>
													 <tr><td class="infoBoxHeading  blue"><div class="heading_bg"><?php echo HEADING_TOURS_INFORMATION; ?></div></td></tr>
													  <tr>
														<td>
													<div class="infoBox_outer">
														<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
														  <tr class="infoBoxContents_new">
												<?php
												  /*if ($sendto != false) {
												?>
															<td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b>';  //<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>; ?></td>
															  </tr>
															  <tr>
																<td class="main"><?php //echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />');
																echo tep_get_customers_email($customer_id);
																 ?></td>
															  </tr>
												<?php
													if ($order->info['shipping_method']) {
												?>
															  <tr>
																<td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?></td>
															  </tr>
															  <tr>
																<td class="main"><?php echo $order->info['shipping_method']; ?></td>
															  </tr>
												<?php
													}
												?>
															</table></td>
												<?php
												  }*/
												?>
															<td width="<?php //echo (($sendto != false) ? '70%' : '100%'); ?>" valign="top">
															<table border="0" width="100%" cellspacing="0" cellpadding="0">
															  <tr>
																<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
												<?php
												  if (sizeof($order->info['tax_groups']) > 1) {
												?>
																  <tr>
																	<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '100%'); ?></td>
																	<td class="main"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?></td>
																	<td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
																	<td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
																  </tr>
												<?php } else {?>
																  <tr>
																	<td width="1%"><?php echo tep_draw_separator('pixel_trans.gif', '10', '100%'); ?></td>
																	<td class="main" colspan="3"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?></td>
																  </tr>
												<?php  }												
												// products loop
												  $i_pay = array();
												  $how_ipay = array();
												  $is_travel_companion = false;
												  $show_yellow_table_notes = false;
												  $haveHotel = false;	//有酒店
												  $haveTours = false;	//有普通行程
												  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
													
													//howard added yellow table notes
													$qry_remaining_seats = tep_db_query("SELECT products_id FROM products_remaining_seats as prs WHERE prs.products_id = '".(int)$order->products[$i]['id']."' limit 1");
													$res_remaining_seats = tep_db_fetch_array($qry_remaining_seats);
													if((int)$res_remaining_seats['products_id']){
														$show_yellow_table_notes = true;
													}
													//howard added travel companion
													$jiebantongyong="";
													if($order->products[$i]['roomattributes'][5]=='1'){
														$is_travel_companion = true;
														$jiebantongyong = '&nbsp;<span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
														
														//结伴同游，平均数和我该付多少？
														$how_ipay[$i] = "";
														$adult_averages = $currencies->display_price(str_replace(',','',$order->products[$i]['adult_average']),$order->products[$i]['tax'], $order->products[$i]['qty']);
														$child_averages = $currencies->display_price(str_replace(',','',$order->products[$i]['child_average']),$order->products[$i]['tax'], $order->products[$i]['qty']);
														if($order->products[$i]['adult_average']>0){
															//$how_ipay[$i] .= '<br>'.ADULT_AVERAGE_PAY.$adult_averages;
														}
														if($order->products[$i]['child_average']>0){
															//$how_ipay[$i] .= '<br>'.CHILD_AVERAGE_PAY.$child_averages;
														}

														//统计哪些人需要我付款
														//思路根据PayerMe值取得第几个人需要我付款，同时判断这几个人是大人还是小孩
														//考查checkout_process.php
														$h=0;
														$i_pay[$i]=0;
														
														foreach($_SESSION as $key=>$val){
															if(strstr($key,'PayerMe')){
																if($_SESSION['PayerMe'.$h][$i]=='1'){
																	//我付
																	if($_SESSION['guestchildage'.$h][$i] != ''){	//小孩
																		$i_pay[$i] += str_replace(',','',$order->products[$i]['child_average']);
																	}else{	//大人
																		$i_pay[$i] += str_replace(',','',$order->products[$i]['adult_average']);
																	}
																}																
																$h++;	
															}
															
														}
														//我付款
														$how_ipay[$i] .= db_to_html('<br>我付：').$currencies->display_price($i_pay[$i],$order->products[$i]['tax'], $order->products[$i]['qty']);
														
													}else{
														$i_pay[$i] += $order->products[$i]['final_price'];
													}
													//howard added travel companion end
													
													
													$tr_class = "productListing-even";
													if($i%2!=0){
														$tr_class = "productListing-odd";
													}
													echo '<tr class="'.$tr_class.'"><td colspan="4" style="border-bottom:1px solid #B7E0F6; padding:10px;">';
													echo '     <table id="tour_list_'.$i.'" width="100%"><tr>' . "\n" .
														 '            <td>'.tep_draw_separator('pixel_trans.gif', '10', '1').'</td><td class="main" align="left" valign="top" width="6">' . $order->products[$i]['qty'] . '</td>' . "\n" .
														 '            <td class="main" valign="top">x&nbsp; <b class="dazi">' . db_to_html($order->products[$i]['name']).'</b>'.$jiebantongyong;
													if (sizeof($order->info['tax_groups']) > 1) echo '<td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
												
													echo '        <td class="sp1" align="right" valign="top" style="padding-right:10px" nowrap="nowrap"><b>' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</b>'.$how_ipay[$i].'</td>' . "\n";
													echo '</tr><tr><td class="gray_dotted_line" colspan="4">&nbsp;</td><tr><td>'.tep_draw_separator('pixel_trans.gif', '10', '100%').'</td><td colspan="4"><table width="100%" align="center" cellpadding="0" cellspacing="0">';
												
													if (STOCK_CHECK == 'true') {
													  echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
													}
												
													$TEXT_SHOPPING_CART_PICKP_LOCATION = TEXT_SHOPPING_CART_PICKP_LOCATION;
													if((int)is_show($order->products[$i]['id'])){
														$TEXT_SHOPPING_CART_PICKP_LOCATION = PERFORMANCE_TIME;
													}
													
													$departuer_date_tags = tep_get_date_disp($order->products[$i]['dateattributes'][0]);
													if($order->products[$i]['no_sel_date_for_group_buy']=="1"){
														$departuer_date_tags = date('m/d/Y',strtotime($order->products[$i]['dateattributes'][0])+86400).TEXT_BEFORE;
													}
													$DepartureDate = '<strong style="color:#000000; font-size:18px;">'.$departuer_date_tags.'</strong>';
													//transfer-service {
													if($order->products[$i]['is_transfer']=='1'){
														$info = tep_transfer_decode_info($order->products[$i]['transfer_info']);
														$date_info = '<span>'.db_to_html('接送信息:').'</span></td><td class="main" width="80%">'.db_to_html(str_replace(array('<strong>','</strong>'),'',tep_transfer_display_route($info)));								
													}else{
													 //hotel-extension {
													$is_start_date_required=is_tour_start_date_required((int)$order->products[$i]['id']);
													if(tep_check_product_is_hotel($order->products[$i]['id'])==1){
														$haveHotel = true;
														$txt_dept_date = db_to_html('入住日期:');
													}else{
														$haveTours = true;
														$txt_dept_date = TEXT_SHOPPING_CART_DEPARTURE_DATE;
													}
													 if($is_start_date_required==true){
														$date_info = '<span>'.$txt_dept_date.'</span></td><td class="main" width="80%">' . tep_get_date_disp($order->products[$i]['dateattributes'][0]).'</span>';
														if($order->products[$i]['dateattributes'][4] != '' && $order->products[$i]['dateattributes'][4] != 0){
															$date_info .=' '. $order->products[$i][$i]['dateattributes'][3].' '. (($order->products[$i]['dateattributes'][4]!='') ? '$' : '') .$order->products[$i]['dateattributes'][4];
														}
														if($order->products[$i]['is_hotel']==1){															
															$hotel_extension_info = explode('|=|', $order->products[$i]['hotel_extension_info']);
															$hotel_attribute = getProductAttribute($order->products[$i]['attributes'],HOTEL_EXT_ATTRIBUTE_OPTION_ID);
															if($hotel_attribute=='2'){
																$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
															}else{
																$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
															}
															$date_info .= '<span style="margin-left:2em">'.db_to_html('离店日期:').' ' . tep_get_date_disp($hotel_checkout_date) . '</span>';
															if(check_date($hotel_checkout_date) && check_date($order->products[$i]['dateattributes'][0])){
																$date_info .=' <span>'.db_to_html('总共：'.date1SubDate2($hotel_checkout_date,$order->products[$i]['dateattributes'][0]).'晚').'</span>';
															}
														}
													}//}
													}//transfer-service
													

													 echo '<tr><td class="main" width="10%" nowrap="nowrap" valign="top">'.$date_info.'</td></tr>';
													 // echo  '<tr><td class="main" width="10%" nowrap="nowrap" valign="top">'.TEXT_SHOPPING_CART_DEPARTURE_DATE.' </td><td class="main" width="80%">'.$DepartureDate.'</td></tr>';
													 if($order->products[$i]['dateattributes'][1]!='')
													 echo  ' <tr><td class="main" valign="top">'.$TEXT_SHOPPING_CART_PICKP_LOCATION.' </td><td class="main">' . $order->products[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $order->products[$i]['dateattributes'][1], 'size="4"')  . ' ' . db_to_html($order->products[$i]['dateattributes'][2]).tep_draw_hidden_field('depart_locationone[]', $order->products[$i]['dateattributes'][2], 'size="4"') . '</td></tr>';
													  if($order->products[$i]['roomattributes'][1]!='')
													 
													    $str_br_find_array = array("<br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS, " ".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS, "<br/>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS, "<br />".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS);
														$str_br_replace_array = array(TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS, TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS, TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS, TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS);														
													 
													 if(isset($order->products[$i]['roomattributes'][1])){
													 	$room_info = preg_replace('@<[^>]*br[^>]*>@','',db_to_html(str_replace($str_br_find_array,$str_br_replace_array,$order->products[$i]['roomattributes'][1])),1);
														$room_info = preg_replace('/[^>]+0\.00/','',$room_info);	//清除为0.00的信息
														echo ' <tr><td class="main" valign="top">'.TXT_ROOM_INFORMATION.' </td><td class="main">' .$room_info.tep_draw_hidden_field('roominfo[]', $order->products[$i]['roomattributes'][1], 'size="4"')  . ' ' . tep_draw_hidden_field('roomprice[]', $order->products[$i]['roomattributes'][0], 'size="4"') . '</td></tr>';
													 }
												
													if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
													  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
													  	if($order->products[$i]['attributes'][$j]['price'] > 0) {
															echo '<tr><td class="main" valign="top"> ' . db_to_html($order->products[$i]['attributes'][$j]['option']) . ': </td><td class="main">' . db_to_html($order->products[$i]['attributes'][$j]['value']) . ': <span class="sp1">' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</span></td></tr>';
														}else{															
															if(trim($order->products[$i]['attributes'][$j]['option'])!='' && !in_array($order->products[$i]['attributes'][$j]['option_id'], (array)$cruisesOptionIds) ) echo '<tr><td class="main" valign="top"> ' . db_to_html($order->products[$i]['attributes'][$j]['option']) . ': </td><td class="main">' . db_to_html($order->products[$i]['attributes'][$j]['value']) . '</td></tr>';
														}
													  }
													}
													
													 if($order->products[$i]['dateattributes'][4]!='' && $order->products[$i]['dateattributes'][4]!=0 ){
													 	
														echo ' <tr><td class="main" valign="top">'.TEXT_SHOPPING_CART_DEPARTURE_DATE_PRICE_FLUCTUATIONS .' </td><td class="main">'.$order->products[$i]['dateattributes'][3]. $currencies->format($order->products[$i]['dateattributes'][4], true, $currency).'</td></tr>';
													 }
													 
													 if(tep_check_priority_mail_is_active($order->products[$i]['id']) == 1){
														$priority_mail_ticket_needed_date = tep_get_cart_get_extra_field_value('priority_mail_ticket_needed_date', $order->products[$i]['extra_values']);
														if(tep_not_null($priority_mail_ticket_needed_date)){
														echo '<tr><td class="main" valign="top">'.TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE.'</td><td class="main"> ' . tep_get_date_disp($priority_mail_ticket_needed_date) . '</td></tr>';
														}
														$priority_mail_delivery_address = tep_get_cart_get_extra_field_value('priority_mail_delivery_address', $order->products[$i]['extra_values']);
														if(tep_not_null($priority_mail_delivery_address)){
														echo '<tr><td class="main" valign="top">'.TXT_PRIORITY_MAIL_DELIVERY_ADDRESS.'</td><td class="main"> ' . db_to_html($priority_mail_delivery_address) . '</td></tr>';
														}
														$priority_mail_recipient_name = tep_get_cart_get_extra_field_value('priority_mail_recipient_name', $order->products[$i]['extra_values']);
														if(tep_not_null($priority_mail_recipient_name)){
														echo '<tr><td class="main" valign="top">'.TXT_PRIORITY_MAIL_RECIPIENT_NAME.'</td><td class="main"> ' . db_to_html($priority_mail_recipient_name) . '</td></tr>';
														}
													}
													 
													 //团购提示 start
													 if($order->products[$i]['group_buy_discount']>0){
														echo ' <tr><td class="main" valign="top">'.TITLE_GROUP_BUY.' </td><td class="main">'.' -'.$currencies->display_price($order->products[$i]['group_buy_discount'],0,$order->products[$i]['qty']).'</td></tr>';
													 //团购提示 end
													 }
												
												
													echo '</table></td>' . "\n";
												
													echo '</tr>';
													/*if($i!=($n-1)){
														echo '<tr><td colspan="4"><hr color="#CCEDFF" size="1" /></td></tr><tr><td colspan="4">'.tep_draw_separator('pixel_trans.gif', '100%', '20').'</td></tr>' . "\n";
													}*/													
													
													
													//<tr><td colspan="4"><hr color="#CCEDFF" size="1" /></td></tr>
											    	echo '</table>';
													echo '</td></tr>';
												}
												// products loop end
												?>
																</table></td>
															  </tr>
															</table></td>
														  </tr>
														  
														<?php
														//显示黄石剩余显示
														if($show_yellow_table_notes==true){
														?>
														  <tr>
														  <td style="padding: 10px 15px; color:#777777" colspan="3">* <?= YELLOWSTONE_TABLE_NOTES;?></td>
														  </tr>
														<?php
														}
														?>
														  

														</table>
													</div>
													  
													  </td>
													  </tr>
													  
													  <tr>
														<td class="separator"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>
													  
													  <tr>
														<td class="infoBoxHeading"><table border="0" cellspacing="0" cellpadding="2">
														   <tr>
															<?php
															$TEXT_GUEST_INFO_FLIGHT_INFO = TEXT_GUEST_INFO_FLIGHT_INFO;
															if($haveHotel){
																$TEXT_GUEST_INFO_FLIGHT_INFO = db_to_html("游客信息");
															}
															if($haveTours){
																$TEXT_GUEST_INFO_FLIGHT_INFO = TEXT_GUEST_INFO_FLIGHT_INFO;
															}
															if($haveHotel && $haveTours){
																$TEXT_GUEST_INFO_FLIGHT_INFO = TEXT_GUEST_INFO_FLIGHT_INFO;
															}
															?>
															<td><?php echo '<div class="heading_bg">' . $TEXT_GUEST_INFO_FLIGHT_INFO . '</div>'; ?></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td>
														<div class="infoBox_outer">
														
														<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
														  <tr class="infoBoxContents_new">
															<td>
															<table border="0"  width="100%" align='center' cellspacing="0" cellpadding="4">
															  
																<?php 
																// traveler loop
																for ($i=0, $n=sizeof($order->products); $i<$n; $i++){
																
																	//howard added travel companion
																	$jiebantongyong="";
																	if($order->products[$i]['roomattributes'][5]=='1'){
																		$jiebantongyong = '&nbsp;<span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
																	}
																	
																	$tr_class = "productListing-even";
																	if($i%2!=0){
																		$tr_class = "productListing-odd";
																	}

																	echo '<tr class="'.$tr_class.'"><td style="border-bottom:1px solid #B7E0F6; padding:15px;"><table width="100%">';
																	echo '<tr>
																	<td colspan="4" class="main"><b class="dazi">'.db_to_html($order->products[$i]['name']).'</b>'.$jiebantongyong.' <a onclick="javascript: sendFormData(\'checkout_confirmation\',\'checkout_edit_sections_ajax.php?ajax_section_name=traveler_detail&ajax=true&product_rank='.$i.$osCsid_string.'\',\'response_traveler_detail_div_'.$i.'\',\'true\');" style="cursor:pointer"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>
																	</td>
																	</tr>';
																	echo '<tr><td colspan="4">
																	<div id="response_traveler_detail_div_'.$i.'">
																	<table width="100%" cellpadding="4" cellspacing="0" border="0">';
																	//amit added to check helicopter tour 
																	if(tep_get_product_type_of_product_id((int)$order->products[$i]['id']) == 2){
																	
																		if($order->products[$i]['roomattributes'][2] != '')
																			{
																				$m=$order->products[$i]['roomattributes'][2];
																				for($h=0; $h<$m; $h++)
																				{
																					 if(($h%2)==0)
																					 echo '<tr>';
																				?>
																				
																					<td class="main" width="2%" valign="top" nowrap="nowrap"><?php echo sprintf(TEXT_GUEST_NAME,($h+1));?> <?php 
																					if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
																					echo "<br />".ENTRY_HEIGHT." ";
																					}											
																					if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
																					echo "<br />".ENTRY_GENDER." ";
																					}
																					if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
																					echo "<br />".ENTRY_DATE_OF_BIRTH." ";
																					}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){
																					echo "<br />".ENTRY_DATE_OF_BIRTH." ";
																					}
																					?>
																					<br />
																					<span style="white-space:nowrap"><?php echo TEXT_INFO_GUEST_BODY_WEIGHT;?> <?php echo ($h+1).'. ';?></span>
																					</td>
																					<td class="main" width="35%" valign="top"><b>
																					<?php 
																					
																					echo tep_db_prepare_input($_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i]);
																					echo tep_draw_hidden_field('guestname'.$h, $_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i], '');																					
																					
																					if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
																					echo "<br />".stripslashes($_SESSION['guestbodyheight'.$h][$i]);
																					}	
																					if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
																					echo '<br />'.$_SESSION['guestgender'.$h][$i];
																					}
																					if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){																																									
																					echo '<br />'.$_SESSION['guestchildage'.$h][$i];
																					}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){																																									
																					echo '<br />'.$_SESSION['guestdob'.$h][$i];
																					}
																					
																					?>
																					
																																									
																					<br />
																					<?php echo $_SESSION['guestbodyweight'.$h][$i]. ' ' . $_SESSION['guestweighttype'.$h][$i] .tep_draw_hidden_field('guestbodyweight'.$h, $_SESSION['guestbodyweight'.$h][$i], '') . tep_draw_hidden_field('guestweighttype'.$h, $_SESSION['guestweighttype'.$h][$i], ''); ?>
																					</b>
																					
																					</td>
																					
																				<?php
																					if(($h%2)!=0)
																					echo '</tr>';
																				}// end of for($h=0; $h<$m; $h++)
																			}
																	
																	}else{
																	
																			if($order->products[$i]['roomattributes'][2] != '')
																			{
																				$m=$order->products[$i]['roomattributes'][2];
																				for($h=0; $h<$m; $h++)
																				{
																					 if(($h%2)==0) echo '<tr>';
																				?>																				
																					<td class="main" width="2%" valign="top" nowrap="nowrap"><?php echo sprintf(TEXT_GUEST_NAME,($h+1));										
																					if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
																					echo "<br />".ENTRY_HEIGHT." ";
																					}											
																					if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
																					echo "<br />".ENTRY_GENDER." ";
																					}
																					if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
																					echo "<br />".ENTRY_DATE_OF_BIRTH." ";
																					}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){
																					echo "<br />".ENTRY_DATE_OF_BIRTH." ";
																					}
																					?></td>
																					<td class="main" width="35%" valign="top"><b>
																					<?php 
																					echo tep_db_prepare_input($_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i]);	
																					if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
																					echo "<br />".stripslashes($_SESSION['guestbodyheight'.$h][$i]);
																					}	
																					if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
																					echo '<br />'.$_SESSION['guestgender'.$h][$i];
																					}
																					if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){																																									
																					echo '<br />'.$_SESSION['guestchildage'.$h][$i];
																					}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){																																									
																					echo '<br />'.$_SESSION['guestdob'.$h][$i];
																					}
																					echo tep_draw_hidden_field('guestname'.$h, $_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i], ''); 
																					?>
																					</b></td>
																					
																				<?php
																					if(($h%2)!=0)		echo '</tr>';
																				}// end of for($h=0; $h<$m; $h++)
																			}else if($order->products[$i]['is_transfer'] == '1'){
																					$h = 0;																					
																					echo '<td class="main" width="2%" valign="top" nowrap="nowrap">';
																					echo sprintf(TEXT_GUEST_NAME,($h+1));										
																					if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
																						echo "<br />".ENTRY_HEIGHT." ";
																					}											
																					if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
																						echo "<br />".ENTRY_GENDER." ";
																					}
																					if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
																						echo "<br />".ENTRY_DATE_OF_BIRTH." ";
																					}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){
																						echo "<br />".ENTRY_DATE_OF_BIRTH." ";
																					}
																					echo '</td>';
																					echo '<td class="main" width="35%" valign="top"><b>';
																					echo tep_db_prepare_input($_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i]);	
																					if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
																						echo "<br />".stripslashes($_SESSION['guestbodyheight'.$h][$i]);
																					}	
																					if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
																						echo '<br />'.$_SESSION['guestgender'.$h][$i];
																					}
																					if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){																																									
																						echo '<br />'.$_SESSION['guestchildage'.$h][$i];
																					}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){																																									
																						echo '<br />'.$_SESSION['guestdob'.$h][$i];
																					}
																					echo tep_draw_hidden_field('guestname'.$h, $_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i], ''); 
																					echo '</b></td>';																		
																				}
																	}																	
																	//echo ' </tr><tr> <td colspan="4" class="main"><b>'.TEXT_FLIGHT_INFO_IF_APPLICABLE.'</b></td></tr>';
																	?>
																	<?php
																	//单人部分配房 start
																	if(isset($_SESSION['SingleName'][$i]) && $_SESSION['SingleName'][$i] != ''){
																		echo '<tr><td colspan="5">';
																		echo db_to_html('单人部分配房的客人姓名及性别：').tep_db_prepare_input($_SESSION['SingleName'][$i]);
																		if($_SESSION['SingleGender'][$i]=='m'){
																			echo db_to_html('（男）');
																		}
																		if($_SESSION['SingleGender'][$i]=='f'){
																			echo db_to_html('（女）');
																		}
																		echo tep_draw_hidden_field('SingleName['.$i.']', $_SESSION['SingleName'][$i]);
																		echo tep_draw_hidden_field('SingleGender['.$i.']', $_SESSION['SingleGender'][$i]);
																		echo '<br><br></td></tr>';
																	}
																	//单人部分配房 end
																	?>
																	<?php 
																	echo '<tr> <td colspan="4" class="main">';
																	if($order->products[$i]['is_hotel']!=1){
																	?>
																	<table border="0" width="100%" cellspacing="0" cellpadding="2">																	  
																	  <tr>
																		<td class="main" nowrap="nowrap"><?php echo TEXT_ARRIVAL_FLIGHT_NUMBER?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['flight_no'][$i])) . tep_draw_hidden_field('flight_no['.$i.']', $order->info['flight_no'][$i]);?></td>
																		<td class="main" nowrap="nowrap"><?php echo TEXT_DEPARTURE_FLIGHT_NUMBER?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['flight_no_departure'][$i])) . tep_draw_hidden_field('flight_no_departure['.$i.']', $order->info['flight_no_departure'][$i]);?></td>
																	  </tr>
																	  <tr>
																		<td class="main" width="10%" nowrap="nowrap"><?php echo TEXT_ARRIVAL_AIRLINE_NAME?>:</td>
																		<td class="main" width="30%"><?php echo nl2br(tep_output_string_protected($order->info['airline_name'][$i])) . tep_draw_hidden_field('airline_name['.$i.']', $order->info['airline_name'][$i]); ?></td>
																		<td class="main" width="10%" nowrap="nowrap"><?php echo TEXT_DEPARTURE_AIRLINE_NAME?>:</td>
																		<td class="main" width="30%"><?php echo nl2br(tep_output_string_protected($order->info['airline_name_departure'][$i])) . tep_draw_hidden_field('airline_name_departure['.$i.']', $order->info['airline_name_departure'][$i]); ?></td>
																	  </tr>
																	  <tr>
																		<td class="main" nowrap="nowrap"><?php echo TEXT_ARRIVAL_AIRPORT_NAME?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['airport_name'][$i])) . tep_draw_hidden_field('airport_name['.$i.']', $order->info['airport_name'][$i]);?></td>
																		<td class="main" nowrap="nowrap"><?php echo TEXT_DEPARTURE_AIRPORT_NAME?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['airport_name_departure'][$i])) . tep_draw_hidden_field('airport_name_departure['.$i.']', $order->info['airport_name_departure'][$i]);?></td>
																	  </tr>
																	  <tr>
																		<td class="main"><?php echo TEXT_ARRIVAL_DATE?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($_POST['arrival_date'.$i])) . tep_draw_hidden_field('arrival_date'.$i, $_POST['arrival_date'.$i]);?></td>
																		<td class="main"><?php echo TEXT_DEPARTURE_DATE?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($_POST['departure_date'.$i])) . tep_draw_hidden_field('departure_date'.$i, $_POST['departure_date'.$i]);?></td>
																	 </tr>
																	  <tr>
																		<td class="main"><?php echo TEXT_ARRIVAL_TIME?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['arrival_time'][$i])) . tep_draw_hidden_field('arrival_time['.$i.']', $order->info['arrival_time'][$i]);?></td>
																		<td class="main"><?php echo TEXT_DEPARTURE_TIME?>:</td>
																		<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['departure_time'][$i])) . tep_draw_hidden_field('departure_time['.$i.']', $order->info['departure_time'][$i]);?></td>
																	  </tr>
																	  
																	</table>
																	<?php
																	}
																	echo ' </td></tr>';
																	/*
																	if($i!=($n-1)){
																	echo '<tr> <td colspan="4" class="main" height="25"><hr style="color:#CCEDFF;" size="1" /></td></tr>';
																	}*/
																	echo '</table>';
																	echo '</div></td></tr>';
																	echo '</table></td></tr>';
																}
																// traveler loop end
																?>
															  
															</table>
															 <?php /* ?>
															 <table border="0"  width="50%" cellspacing="0" cellpadding="2">
																		<tr><td colspan="2" class="main"></td></tr>
																		<tr><td colspan="2" width="60%" class="main"><b><?php echo TEXT_EMERGENCY_CONTACT_NUM;?></b></td><td><?php echo tep_customers_cellphone($customer_id);?></td></tr>
																		<tr><td class="main" height="10"></td></tr>
															 </table>	
															<?php */ ?>
															</td>
														  </tr>
														</table>
														</div>
														</td>
													  </tr>
												
												
												
													 <tr>
														<td class="separator"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>
													  
													  <tr>
														<td class="infoBoxHeading"><div class="heading_bg"><?php echo db_to_html('通信地址'); ?></div></td>
													  </tr>
													 
													  
													  <tr>
														<td>
														<div class="infoBox_outer">
														
														<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
														  <tr class="infoBoxContents_new">
																<td class="main" colspan="3" style="padding-left:15px; padding-top:10px;"><?php echo '<b>' . db_to_html('通信地址') . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"> <span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?></td>
														  </tr>
														  <tr class="infoBoxContents_new">
														  <td style="padding: 15px;">
														  <div id="response_ship_confirmation_div">
																<table width="100%" cellpadding="0" cellspacing="0">
																
																
														  <tr>
															<td width="73%" valign="top">
															
															<table border="0" width="100%" cellspacing="0" cellpadding="4">
															  
															  <?php
															  $ship_address_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone, c.customers_mobile_phone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$shipto . "'");
															  $ship_address = tep_db_fetch_array($ship_address_query);
															  
															  ?>
															  <tr><td>
																<table width="100%" cellpadding="0" cellspacing="0">
															  <tr>
															  	<td class="main" width="10%"><?php echo TEXT_BILLING_INFO_ADDRESS ?> </td>
																<td class="main" width="80%"><?php echo db_to_html($ship_address['street_address']); ?></td>
															  </tr>
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_CITY; ?></td>
																<td class="main"><?php echo db_to_html($ship_address['city']); ?></td>
															  </tr>
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_STATE; ?></td>
																<td class="main">
																<?php
																if((int)$ship_address['zone_id']){
																	$get_zone_sql = tep_db_query('SELECT zone_id,zone_name FROM `zones` WHERE zone_id ="'.(int)$ship_address['zone_id'].'" limit 1');
																	$get_row = tep_db_fetch_array($get_zone_sql);
																	if((int)$get_row['zone_id']){
																		echo db_to_html($get_row['zone_name']);
																	}else{
																		echo db_to_html($ship_address['state']);
																	}
							
																}else{
																	echo db_to_html($ship_address['state']);
																}
																?>
																</td>
															  </tr>															  															
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_POSTAL; ?> </td>
																<td class="main"><?php echo $ship_address['postcode']; ?></td>
															  </tr>
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_COUNTRY; ?></td>
																<td class="main"><?php echo tep_get_country_name($ship_address['country_id']); ?></td>
															  </tr>
															 
															  <?php if(tep_not_null($ship_address['customers_cellphone'])){?>
															  <tr>
															  	<td class="main"><?php echo TEXT_EMERGENCY_CONTACT_NUM; ?> </td>

																<td class="main"><?php echo $ship_address['customers_cellphone']; ?></td>
															  </tr>
															  <?php }?>
															  
															  <?php if(tep_not_null($ship_address['customers_telephone'])){?>
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_TELEPHONE; ?></td>
																<td class="main"><?php echo $ship_address['customers_telephone']; ?></td>
															  </tr>
															  <?php }?>
															  
															  <?php if(tep_not_null($ship_address['customers_mobile_phone'])){?>
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_MOBILE; ?></td>
																<td class="main"><?php echo $ship_address['customers_mobile_phone']; ?></td>
															  </tr>
															  <?php }?>
															  
															  <?php if(tep_not_null($ship_address['customers_fax'])){?>
															  <tr>
															  	<td class="main"><?php echo TEXT_BILLING_INFO_FAX; ?></td>
																<td class="main"><?php echo $ship_address['customers_fax']; ?></td>
															  </tr>
															  <?php
															  }
															  ?>
															  
															  </table>
															  </td></tr>
															  
															</table>
															</td>
															<td width="1%"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td></tr>
														  
														  </table>
														  </div>
																</td></tr>
														  
														  </table>
														</div>
														</td>
													  </tr>
												
												<?php
												// BOF: Lango modified for print order mod
												  if (is_array($payment_modules->modules)) {
													 if (MODULE_ORDER_TOTAL_INSTALLED) {
													$order_total_modules->process();
													}
													if ($confirmation = $payment_modules->confirmation() || $payment == 'T4FCredit') {
													  $payment_info = $confirmation['title'];
													  $_confirmation = $payment_modules->confirmation();
													  //$input_box = $_confirmation['fields'];
													  
													  if (!tep_session_is_registered('payment_info')) tep_session_register('payment_info');
												// EOF: Lango modified for print order mod
												?>
													  <tr>
														<td class="separator"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>
													  <tr>
														<td class="infoBoxHeading"><div class="heading_bg"><?php echo HEADING_PAYMENT_INFORMATION; ?></div></td>
													  </tr>
													
													  <tr>
														<td>
														<div class="infoBox_outer">
														
														<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
														  <tr class="infoBoxContents_new">
															<td style="padding: 10px 15px;">
															
															<table border="0" cellspacing="0" cellpadding="2" width="100%">
															  <tr>
																<td class="main" colspan="3"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?>
																<?php
																for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) { 
																	echo $confirmation['fields'][$i]['field'];
																}
																?>
																</td>
															  </tr>
															  <tr>
																<td class="main" colspan="3"><b><?php echo $order->info['payment_method']; ?></b></td>
															  </tr>
															  <tr><td class="gray_dotted_line" colspan="3"></td></tr>
															  <tr>
																<td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
															  </tr>
												
												<?php
													  for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
														//以下两个变量在信用卡下面是20%和80%，其它不变
														$tmp_width1 = '';
														$tmp_width2 = '';
														if($payment == 'authorizenet'){
															$tmp_width1 = '20%';
															$tmp_width2 = '80%';
														}
												?>
															  <tr>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td class="main" width="<?=$tmp_width1?>"><?php echo $confirmation['fields'][$i]['title']; ?></td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td class="main" width="<?=$tmp_width2?>"><?php echo $confirmation['fields'][$i]['field']; ?></td>
															  </tr>
												<?php
													  }
												?>
															</table></td>
															<td width="1%" align="right" valign="middle"><img src="image/vertical_seperator.gif" /></td>
															<td width="24%" valign="bottom" align="right" style="padding: 10px 15px;">
												
												<table border="0" cellspacing="0" cellpadding="0">
												<?php
												if($is_travel_companion==true){
													 //含结伴同游的订单我需要付款的总额
													$i_pay_total=0;
													for($pp=0; $pp<count($i_pay); $pp++){
														$i_pay_total += $i_pay[$pp]; 
													}
												}
												  //产品总价模块
												  if (MODULE_ORDER_TOTAL_INSTALLED) {
													//$order_total_modules->process();
													echo $order_total_modules->output();
												  }
												?>
												</table>
															
												<?php
												 if($is_travel_companion==true){
													 echo '<div class="sp1"><b>'.db_to_html('我的付款总额：').$currencies->display_price($i_pay_total,0,1).'</b></div>';
												 }

															?>
															
															</td>
														  </tr>
													 <?php
													 //信用卡地址 start
													 if($payment == 'authorizenet'){
													 ?>
													  <tr>
														<td>
														  <table border="0" width="100%" cellspacing="0" cellpadding="2" >
														    <tr>
														      <td class="main" colspan="3" style="padding-left:15px; padding-top:10px;"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?></td>
														    </tr>
														    <tr>
														      <td style="padding: 15px;">
														        <div id="response_billing_confirmation_div">
														          <table width="100%" cellpadding="0" cellspacing="0">
														            
														            
														            <tr>
														              <td width="73%" valign="top">
														                
														                <table border="0" width="100%" cellspacing="0" cellpadding="4">
														                  
														                  <?php
															  $address_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$billto . "'");
															  $address = tep_db_fetch_array($address_query);
															  
															  ?>
														                  <tr><td>
														                    <table width="100%" cellpadding="0" cellspacing="0">
														                      <tr>
														                        <td class="main" width="15%"><?php echo TEXT_BILLING_INFO_ADDRESS ?> </td>
																  <td class="main" width="85%"><?php echo db_to_html($address['street_address']); ?></td>
															    </tr>
														                      <tr>
														                        <td class="main"><?php echo TEXT_BILLING_INFO_CITY; ?></td>
																  <td class="main"><?php echo db_to_html($address['city']); ?></td>
															    </tr>
														                      <tr>
														                        <td class="main"><?php echo TEXT_BILLING_INFO_STATE; ?></td>
																  <td class="main">
																    <?php
																if((int)$address['zone_id']){
																	$get_zone_sql = tep_db_query('SELECT zone_id,zone_name FROM `zones` WHERE zone_id ="'.(int)$address['zone_id'].'" limit 1');
																	$get_row = tep_db_fetch_array($get_zone_sql);
																	if((int)$get_row['zone_id']){
																		echo db_to_html($get_row['zone_name']);
																	}else{
																		echo db_to_html($address['state']);
																	}
							
																}else{
																	echo db_to_html($address['state']);
																}
																?>																    </td>
															    </tr>															  															
														                      <tr>
														                        <td class="main"><?php echo TEXT_BILLING_INFO_POSTAL; ?> </td>
																  <td class="main"><?php echo $address['postcode']; ?></td>
															    </tr>
														                      <tr>
														                        <td class="main"><?php echo TEXT_BILLING_INFO_COUNTRY; ?></td>
																  <td class="main"><?php echo tep_get_country_name($address['country_id']); ?></td>
															    </tr>
													                        </table>
															    </td></tr>
												                      </table></td>
															  
															<td width="2%"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td></tr>
													              </table>
														    </div>																  </td></tr>
													      </table></td></tr>
													  <?php 
													  }
													  //信用卡地址 end
													  
													  ?>
														</table>
														
														</div>
														</td>
													  </tr>
												<?php
													}else if($order->info['payment_method']=='PayPal'){
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
																<td class="main" colspan="3"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>'; ?></td>
															  </tr>
															  <tr>
																<td class="main" colspan="3"><b><?php echo $order->info['payment_method']; ?></b></td>
															  </tr>
															  </table></td>
														  </tr>
														</table>

														</div>
														</td>
													  </tr>
														<?php
													}
												  }
												?>
													  
													  
													  <tr>
														<td class="separator"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>
												<?php
												  if (tep_not_null($order->info['comments'])) {
												?>
													  <tr>
														<td class="infoBoxHeading"><?php echo '<div class="heading_bg">' . HEADING_ORDER_COMMENTS . '</div>'; /*<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit_a">(' . TEXT_EDIT . ')</span></a>*/ ?></td>
													  </tr>
													
													  <tr>
														<td>
														<div class="infoBox_outer">

														<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
														  <tr class="infoBoxContents_new">
															<td style="padding: 10px 15px;"><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table>

														</div>
														</td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>
												<?php
												  }
												?>
												
													  <tr>
														<td class="infoBoxHeading"><div class="heading_bg"><?php echo HEADING_SHIPPING_INFORMATION; ?></div></td>
													  </tr>
													 
													
													<tr>
														<td>
														<div class="infoBox_outer">

														<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox_new">
														  <tr class="infoBoxContents_new">
															<td style="padding: 10px 15px;">
															  <table border="0" width="100%" cellspacing="0" cellpadding="0">
																<tr>
																
																<td class="main" colspan="2">
																
																		<table border="0"  cellspacing="0" cellpadding="2" width="100%">
																		
																		
																		<tr>
																		  <td align="left">
																		  <table  border="0" cellspacing="1" cellpadding="2" >
																		  <tr>
																		  	<td width="30%" align="left" class="main" nowrap="nowrap"><?php echo TEXT_SHIPPING_METHOD;?></td>
																			<td width="70%" class="main"><b><?php echo TEXT_SIMPLE_DIS_EMAIL;?></b></td>
																		  </tr>
																		  <tr>
																			<td width="30%"  class="main" nowrap="nowrap"><?php echo db_to_html('用户：');?></td>
																			<td width="70%" align="left" class="main"><b><?php 
																			
																			if (isset($order->billing['firstname']) && tep_not_null($order->billing['firstname'])) {
																			  $firstname = tep_output_string_protected($order->billing['firstname']);
																			  $lastname = tep_output_string_protected($order->billing['lastname']);
																			} elseif (isset($order->billing['name']) && tep_not_null($order->billing['name'])) {
																			  $firstname = tep_output_string_protected($order->billing['name']);
																			  $lastname = '';
																			} else {
																			  $firstname = '';
																			  $lastname = '';
																			}
																			
																			echo db_to_html($firstname.' '.$lastname);
																			?></b></td>
																		  </tr>
																		  <tr>
																			<td  class="main" nowrap="nowrap"><?php echo db_to_html('邮箱地址：');?></td>
																			<td class="main"><b><?php echo db_to_html($order->customer['email_address']); ?></b></td>
																		  </tr>
																		
																		  </table>
																		  
																		  </td>
																		</tr>
																		</table>
																		
																	</td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																</tr>
																</table>
															</td>
														  </tr>
														</table>
														
														</div>
														</td>
													  </tr>
												
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>

													  <tr>
														<td>
														<?php //110627-5 Checkout confirmation Edit BEGIN ?>
														<style type="text/css">
.confirmations{ padding-top:20px;}
.confirmations .words{ width:838px; height:125px; padding:5px; overflow:hidden; overflow-y:auto; border:1px solid #bbb; color:#666; line-height:18px; }
.confirmations .words h5{ color:#111; font-size:14px; line-height:24px;}
.confirmations .choose{ line-height:30px;}
.confirmations .choose .chooseCon{ display:inline-block; *display:inline; *zoom:1; width:100px; margin-right:20px; font-size:14px; cursor:pointer;}
.confirmations .choose .chooseCon input{ vertical-align:middle; margin:-4px 3px 0 0;}
.confirmations .choose .active{ font-weight:bold;}
.confirmations .readTip{ line-height:40px;}
.confirmations .confirmationsTip{ visibility:hidden; line-height:35px;}
.confirmations .confirmationsTip b{ color:#F7860F;}
.checkout{ height:27px; padding-bottom:20px; line-height:27px; text-align:right;}
														</style>
														<?php 														ob_start();
														include "checkout_confirmation_agreement.tpl.php";
														?>
														<div class="readTip">我已阅读走四方网提供的 <a href="javascript:;" onclick="jQuery('#ConfirmationsWords').animate({scrollTop:0}, 300);">客户协议</a>、<a href="javascript:;" onclick="jQuery('#ConfirmationsWords').animate({scrollTop: jQuery('#ConfirmationsWords_2').offset().top-jQuery('#ConfirmationsWords').offset().top+jQuery('#ConfirmationsWords').scrollTop()}, 300);">取消和退款条例</a>、<a href="javascript:;" onclick="jQuery('#ConfirmationsWords').animate({scrollTop: jQuery('#ConfirmationsWords_3').offset().top-jQuery('#ConfirmationsWords').offset().top+jQuery('#ConfirmationsWords').scrollTop()}, 300);">信用卡支付验证书</a></div>
														<div class="choose" id="confirmationsChoose">
															<div class="chooseCon" id="AgreeAll"><input type="radio" name="agree" />我同意</div><div class="chooseCon"><input type="radio" name="agree" />我拒绝</div>
														</div>
													<script type="text/javascript">
													jQuery("#confirmationsChoose .chooseCon").click(function(){
														jQuery("#ConfirmationsTip").css("visibility","visible");
														jQuery(this).find("input[type=radio]").attr("checked",true);
														jQuery("#confirmationsChoose .chooseCon").removeClass("active");
														jQuery(this).addClass("active");
														
														if(jQuery(this).attr("id") =="AgreeAll"){
															//jQuery("#ConfirmBtn").attr('src','<?php echo "image/buttons/".$language."/button_confirm_order.gif"; ?>');
															jQuery("#ConfirmBtn").css('background-position','left -59px');
															jQuery("#ConfirmBtn").css("cursor",'pointer');
															jQuery("#ConfirmBtn").removeAttr('disabled');
 															jQuery("#NoAgreeTip").hide();
															jQuery("#AgreeTip").show();

														}else{
															//jQuery("#ConfirmBtn").attr('src','<?php echo "image/buttons/".$language."/button_confirm_order_disable.gif"; ?>');
															jQuery('#ConfirmBtn').css('background-position','left -209px');
															jQuery("#ConfirmBtn").css("cursor",'default');
															jQuery("#ConfirmBtn").attr('disabled','disabled');
															jQuery("#NoAgreeTip").show();
															jQuery("#AgreeTip").hide();
														}
													});
													</script>

														<div class="confirmationsTip" id="ConfirmationsTip"><b>温馨提示:</b><span id="AgreeTip" >行程在出发前七日内（含七日）将无法取消和申请退款，请您谅解 ！</span><span id="NoAgreeTip" >请您仔细阅读上述协议，并选择“我同意”完成预订。</span></div>

													</div>

													<div class="checkout">
													<?php
													if(isset($_POST['gv_redeem_code_royal_customer_reward']) && $_POST['gv_redeem_code_royal_customer_reward']!='')
													{
														echo tep_draw_hidden_field('gv_redeem_code_royal_customer_reward', $_POST['gv_redeem_code_royal_customer_reward']);
													}
													if (ACCOUNT_CONDITIONS_REQUIRED == 'true') {
														echo '<input type="checkbox" class="check-required"  style="display:none" value="0"   name="agree" id="agree2" />';
													}
													#echo tep_template_image_submit('button_confirm_order_disable.gif', html_to_db(IMAGE_BUTTON_CONFIRM_ORDER),' id="ConfirmBtn" disabled="true" style="cursor:default; float:right; margin-left:10px;" ') ;
													echo '<span class="subbtn"><input type="submit" id="ConfirmBtn" disabled="true" value="' . db_to_html('完成预订') . '"/></span>';
													echo '<span id="submit_msn" style="color:#FF6600; display:none; float:right; width:500px; ">'.html_to_db(TEXT_SUBMIT_MSN).'</span>';
													?>
													</div>
													<?php 
													echo db_to_html(ob_get_clean());
													//110627-5 Checkout confirmation Edit END?>
														</td>
													  </tr>
													  <?php
												  if (isset($$payment->form_action_url)) {
													$form_action_url = $$payment->form_action_url;
												  } else {
													$form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
												  }
												  if (is_array($payment_modules->modules)) {
													echo $payment_modules->process_button();
												  }
												?>
													   <?php
													   // 添加RMB提示语
													  if($currency == 'CNY'){											  
														echo '<tr><td>';
														echo '<div style="margin:5px 0 5px 0;text-align:left; background:#FFF3E3 none repeat scroll 0 0; color:#E67402; padding:5px; border: 1px solid #E67402;">'.TEXT_RMB_CHECK_OUT_MSN.'</div>'; 
														echo '</td></tr>';
														echo '<tr><td>'.tep_draw_separator('pixel_trans.gif', '100%', '10');
														echo '</td></tr>';
													} ?>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
													  </tr>
													  <tr>
														<td align="center">
														
														<!--<table width="0%" border="0" cellspacing="0" cellpadding="0">
														  <tr>
															<td><a href="<?= tep_href_link('checkout_info.php', '', 'SSL');?>" class="broud-line"><?php echo tep_template_image_button('tours_info2.gif','','')?></a></td>
															<td><img src="image/jiantou2.gif" style="margin-left:60px; margin-right:60px;" /></td>
															<td><a href="<?= tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');?>" class="broud-line"><?php echo tep_template_image_button('payment-info2.gif')?></a></td>
															<td><img src="image/jiantou2.gif" style="margin-left:60px; margin-right:60px;" /></td>
															<td><?php echo tep_template_image_button('check-info3.gif','','')?></td>
														  </tr>
														</table>-->
														</td>
													  </tr>
													</table>
													</form>

								</td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							</table><!-- content main body end -->
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
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">

function get_state(country_id,form_name,state_obj_name){
	var form = form_name;
	var state = form.elements[state_obj_name];
	var country_id = parseInt(country_id);
	if(country_id<1){
		alert('<?php echo ENTRY_COUNTRY.ENTRY_COUNTRY_ERROR ?>');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_for_checkout_confirmation_ajax.php', 'country_id='))?>") +country_id;
	ajax.open('GET', url, true);  
	ajax.send(null);
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById('state_td').innerHTML = ajax.responseText;
			document.getElementById('city_td').innerHTML ='<?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>';
		}
	}

	
}
function get_city(state_name,form_name,city_obj_name){
	var form = form_name;
	var city = form.elements[city_obj_name];
	var state_name = state_name;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_for_checkout_confirmation_ajax.php','', 'SSL')) ?>");
			var aparams=new Array(); 
			for(i=0; i<form.length; i++){
				var sparam=encodeURIComponent(form.elements[i].name);
				sparam+="=";
				sparam+=encodeURIComponent(form.elements[i].value);
				aparams.push(sparam);
			}
			var post_str=aparams.join("&");	
			ajax.open("POST", url, true); 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			ajax.send(post_str);
			ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById('city_td').innerHTML =ajax.responseText;
		}
	}
}

<?php if(!isset($HTTP_GET_VARS['edit'])){?>
/*取得默认省份列表*/
//get_state(<?php echo ($country) ? $country : '223'; ?>,document.getElementById('addressbook'),"state");
<?php }?>

</script>
<?php include(DIR_FS_JAVASCRIPT.'get_country_tel_code.js.php');?>
<?php echo tep_get_design_body_footer();?>

<?php
//自动完成预订 start{
?>
<script type="text/javascript">
	document.getElementById("checkout_confirmation").submit();
</script>
<?php
//自动完成预订 end}
?>
