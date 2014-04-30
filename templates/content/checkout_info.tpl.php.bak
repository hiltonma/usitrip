<?php 
for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
    $arrival_date = ""; $departure_date = "";
    foreach ($_SESSION as $key => $val) { if (strstr($key, 'arrival_date' . $i)) { $arrival_date = $val;  } }
    foreach ($_SESSION as $key => $val) {  if (strstr($key, 'departure_date' . $i)) {     $departure_date = $val;    }   }
}
?>
<style type="text/css">.main_blue{color:blue}</style>
<script type="text/javascript"><!--
function display_table(link_id,id){
	var link_id = document.getElementById(link_id);
	var id = document.getElementById(id);
	if(id.style.display=='none'){id.style.display ="block";link_id.innerHTML = "<?php echo db_to_html('隐藏');?>";	}
	else{id.style.display ="none";link_id.innerHTML = "<?php echo db_to_html('显示');?>";}
}
var DateForMat = "mm/dd/yyyy";	//日期选择框的日期格式
//--></script>
<script type="text/javascript" charset="gb2312" src="includes/javascript/calendar.js"><!--日期选择框//--></script>
<script type="text/javascript">
function check_flight(){
	/*var rtn1 = rtn2 = true;
	var msg = '';
	var len = jQuery("table[id^='flight_info_']").length;
	for(var i = 0; i < len; i++) {
		if (jQuery('#a' + i + ':checked').length == 0) {
			var ck1 = jQuery('#flight_info_' + i + ' input[mytype=\'Before\'][name=\'arrival_date' + i + '\']').val();
			var ck2 = jQuery('#flight_info_' + i + ' input[mytype=\'Before\'][name=\'airline_name[' + i + ']\']').val();
			var ck3 = jQuery('#flight_info_' + i + ' input[mytype=\'Before\'][name=\'flight_no[' + i + ']\']').val();
			var ck4 = jQuery('#flight_info_' + i + ' input[mytype=\'Before\'][name=\'airport_name[' + i + ']\']').val();
			var ck5 = jQuery('#flight_info_' + i + ' input[mytype=\'Before\'][name=\'arrival_time[' + i + ']\']').val();
			if(ck1 == '' && ck2 == '' && ck3 == '' && ck4 == '' && ck5 == '') {
				//alert('全是空，不管');
				//rtn1 = true;
			} else if (ck1 == '' || ck2 == '' || ck3 == '' || ck4 == '' || ck5 == '') {
				msg += '<?php #echo db_to_html('第')?>' + (i + 1) + '<?php #echo db_to_html('个接机航班信息还未填写完整！');?>\n';
				rtn1 = false;
			}
		} else {
			//alert('自行入住，不管了');
			//rtn1 = true;
		}
		
		// 判断参团后的航班信息  后根据商务部要求 不管填不填 都不管
		if (jQuery('#b' + i + ':checked').length == 0) {
			var ck1 = jQuery('#flight_info_' + i + ' input[mytype=\'After\'][name=\'departure_date' + i + '\']').val();
			var ck2 = jQuery('#flight_info_' + i + ' input[mytype=\'After\'][name=\'airline_name_departure[' + i + ']\']').val();
			var ck3 = jQuery('#flight_info_' + i + ' input[mytype=\'After\'][name=\'flight_no_departure[' + i + ']\']').val();
			var ck4 = jQuery('#flight_info_' + i + ' input[mytype=\'After\'][name=\'airport_name_departure[' + i + ']\']').val();
			var ck5 = jQuery('#flight_info_' + i + ' input[mytype=\'After\'][name=\'departure_time[' + i + ']\']').val();
			if(ck1 == '' && ck2 == '' && ck3 == '' && ck4 == '' && ck5 == '') {
				//alert('全是空，不管');
				rtn2 = true;
			} else if (ck1 == '' || ck2 == '' || ck3 == '' || ck4 == '' || ck5 == '') {
				msg += '<?php #echo db_to_html('第')?>' + (i + 1) + '<?php #echo db_to_html('个送机航班信息还未填写完整！');?>\n';
				rtn2 = false;
			}
		} else {
			//alert('自行离团，不管了');
			rtn2 = true;
		}
		
	}

	if (!(rtn1 && rtn2)) {
		alert(msg);	
	}
	//alert(rtn1 && rtn2);
	return (rtn1 && rtn2);*/
}
</script>

<?php echo tep_get_design_body_header(db_to_html('行程确认')); ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">														
							  <tr>
								<td class="main">							
<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', ' id="checkout_payment"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>"> 
<tr>
<td align="center"><?php ob_start();?>
	<div class="cart_progress">
		<div class="cart_left">
    		<em class="cart_icon"></em><h3>我的购物车</h3>
    	</div>
    	<div class="cart_right">
    		<ul>
        		<li class="first"><span></span>1.选择产品</li>
            	<li><span></span>2.查看购物车</li>
            	<li class="cur"><span></span>3.确认行程信息</li>
            	<li><span class="cur"></span>4.完成订购</li>
            	<li class="last"><span></span></li>
         	</ul>
    	</div>
	</div><?php echo db_to_html(ob_get_clean());?>
	<!--<table width="0%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td><?php echo tep_template_image_button('tours-info.gif','','')?></td>
		<td><img alt="" src="image/jiantou1.gif" style="margin-left:60px; margin-right:60px;" /></td>
		<td><?php echo tep_template_image_button('payment_info2.gif','','')?></td>
		<td><img alt="" src="image/jiantou1.gif" style="margin-left:60px; margin-right:60px;" /></td>
		<td><?php echo tep_template_image_button('check-info.gif','','')?></td>															
	  </tr>
	</table>-->
</td>
</tr>

<?php  if ($messageStack->size('checkout_payment') > 0) {?>
	  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
	  <tr><td><?php echo $messageStack->output('checkout_payment'); ?></td></tr>
	  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
<?php }?>
<?php  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {?>
  <tr>
	<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr><td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td></tr>
        </table>
	</td></tr>
  <tr>
	<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
	  <tr class="infoBoxNoticeContents">
		<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
			<td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		  </tr>
		</table></td>
	  </tr>
	</table></td>
  </tr>
  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td></tr>
<?php  } ?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){table_image_border_bottom();}
// EOF: Lango Added for template MOD
?>
<tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
</tr>

	<?php
	// if customer REDEMPTIONS >= $1 , display on.
	//if((int)(tep_get_shopping_points($customer_id) / REDEEM_POINT_VALUE)){
	?>  
	  <tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
			  <td class="infoBoxHeading blue">
			  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="cart_list_table">
              	<tr>
                	<td width="608"><div class="heading_bg"><?php echo db_to_html('线路名称');//TABLE_HEADING_REDEEM_SYSTEM; ?></div></td>
                    <td width="85" align="center"><?php echo db_to_html('出团日期')?></td>
                    <td width="85" align="center"><?php echo db_to_html('价格')?></td>
                    <td align="center"><?php echo db_to_html('操作') ?></td>
                   </tr>
                  </table>
			  <div class="head_note" style="display:none;"><span class="inputRequirement"><?php echo FORM_REQUIRED_INFORMATION; ?></span></div>
			  </td>
			</tr>
			</table>
		</td>
	  </tr>
	   <tr>
			<td>
			<div class="infoBox_outer">
			<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
			  <tr class="infoBoxContents_new">
				<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td></tr>
				  <tr>
							<td class="main" colspan="2">
								<table width="100%" align="center" cellpadding="0" cellspacing="1" border="0" bgcolor="#dbebfb">
								  <?php
									$jiebantongyou = false;
									$haveHotel = false;	//有酒店
									$haveTours = false;	//有普通行程
									$haveCruises = false; //有邮轮
									require_once(DIR_FS_FUNCTIONS . 'cruises_functions.php');
									
								  for ($i=0, $n=sizeof($order->products); $i<$n; $i++){
								  	
									//howard added travel companion
									$jiebantongyong="";
									if($order->products[$i]['roomattributes'][5]=='1'){
										$jiebantongyou = true;
										$jiebantongyong = '&nbsp;<span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
									}
									//echo '<pre>';print_r($order->products[$i]);echo '</pre>';
									//print_r($cart);
									//hotel-extension {
									$is_hotel_tour=tep_check_product_is_hotel((int)$order->products[$i]['id']);
									if($is_hotel_tour == 1){
										$haveHotel = true;
										$display_tour_heading = "酒店名称：";
									}elseif((int)getProductsCruisesId((int)$order->products[$i]['id']) ){
										$haveCruises = true;
										$display_tour_heading = "团名：";
									}else{
										$haveTours = true;
										$display_tour_heading = "团名：";
									}
									$hotel_ext_addon_text = "";
									//echo '<pre>'.print_r($order->products[$i]).'</pre>';
									if($order->products[$i]['attributes'][0]['option_id']==HOTEL_EXT_ATTRIBUTE_OPTION_ID && $order->products[$i]['attributes'][0]['value_id']=='1'){
										$hotel_ext_addon_text = '&nbsp;<span class="main_blue"><b>'.db_to_html('(参团前加订酒店)').'</b></span>';
									}else if($order->products[$i]['attributes'][0]['option_id']==HOTEL_EXT_ATTRIBUTE_OPTION_ID && $order->products[$i]['attributes'][0]['value_id']=='2'){
										$hotel_ext_addon_text = '&nbsp;<span class="main_blue"><b>'.db_to_html('(参团后加订酒店)').'</b></span>';
									}//}
								  ?>
									<tr>
										<!--<td bgcolor="#ffffff"  class="<?php if($i % 2 != 0){echo 'bg';}?>"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>-->
										<td width="633" bgcolor="#ffffff" style="padding-left:20px;" class="<?php if($i % 2 != 0){echo 'bg';}?>"><b class="dazi"><?php echo ($i+1).' '.db_to_html($display_tour_heading.$order->products[$i]['name']) ?></b><?php echo $jiebantongyong.$hotel_ext_addon_text;?><br/>
                                        <?php
                                        //print_r($order->products[$i]);
										if (is_array($order->products[$i]['attributes']) == true) { 
											foreach ($order->products[$i]['attributes'] as $key) {
												// 价为0 则不显示
												if ((int)$key['price'] > 0) {
													echo db_to_html($key['option'] . '：' .$key['value'] . '&nbsp;' . $key['prefix'] . '&nbsp;' . $currencies->display_price($key['price'],$order->products[$i]['tax']) . '<br/>');
												}
											}
										}
										//加乘车地址信息
										if(tep_not_null($order->products[$i]['dateattributes'][2])){
											echo db_to_html('发出地点：') .$order->products[$i]['dateattributes'][1].' '.$order->products[$i]['dateattributes'][2]. '<br/>';
										}
										echo db_to_html(format_out_roomattributes_1($order->products[$i]['roomattributes'][1],(int)$order->products[$i]['roomattributes'][3])) . '<br/>';
										echo db_to_html('房间总计：') . $order->products[$i]['roomattributes'][0];?></td>
                                        <td width="85" bgcolor="#ffffff"  align="center" class="<?php if($i % 2 != 0){echo 'bg';}?>"><?php echo $order->products[$i]['dateattributes'][0];#var_dump($order->products) ?></td>
										<td align="center" bgcolor="#ffffff"  class="sp1 <?php if($i % 2 != 0){echo 'bg';}?>" valign="middle"><b><?php echo $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']); ?></b></td>
                                        <td align="center" bgcolor="#ffffff"  class="sp1 <?php if($i % 2 != 0){echo 'bg';}?>"><a href="<?php echo tep_href_link('shopping_cart.php');?>" class="orderEdit_a"><?php echo db_to_html('编辑');?></a></td>
									</tr>
								  <?php	  }		  ?>
								</table>  
							</td>
						</tr>
				  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', min(15, (5*$n))); ?></td></tr>
				  <tr>
					<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
					<?php /* ?><td class="main">Maximum discount available for this order is $<?php echo $total_allowable_discount; ?>.  <?php printf(TEXT_REDEEM_SYSTEM_START, $currencies->format(tep_calc_shopping_pvalue($customer_shopping_points)), $currencies->format($order->info['total']). $note); ?></td>
					<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				 </tr>
				  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
					<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
					<td class="main"><?php printf(TEXT_REDEEM_SYSTEM_SPENDING, number_format($max_points,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue($max_points))); ?></td><?php */ ?>					
					<td class="main" align="right"><?php # 原来下面“您需要支付”显示的是 总计 常量 R4F_REDEMPTIONS_TOTAL?>
						<b class="sp1">	<?php echo '<span class="wenzi">' . db_to_html('您需要支付：') . '</span>&nbsp;<span class="daol">'.$currencies->format($order->info['total']) . '</span>'; ?></b>
					</td></tr>
				<?php
				//积分兑换按钮，已经被移到check_payment页面
				?>	
			<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td></tr>
			</table>
		</td>
	  </tr>
	</table>
			</div>
	</td>
	</tr>
	<?php
	//}
	// if customer REDEMPTIONS >= $1 , display on. end
	?>
<tr><td class="separator"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td></tr>
	<tr>
        <td>
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
				<tr>
					
					<?php
					$TEXT_GUEST_INFO_FLIGHT_INFO = TEXT_GUEST_INFO_FLIGHT_INFO;
					if($haveHotel==true || $haveCruises==true){
						$TEXT_GUEST_INFO_FLIGHT_INFO = db_to_html("游客信息");
					}
					
					if($haveTours==true){
						$TEXT_GUEST_INFO_FLIGHT_INFO = TEXT_GUEST_INFO_FLIGHT_INFO;
					}
					?>
					
					<td class="infoBoxHeading"><div class="heading_bg"><?php echo db_to_html('添加') . $TEXT_GUEST_INFO_FLIGHT_INFO; ?></div> </td>
				</tr>
			 </table>
		  </td>
      </tr>
	  <tr>
        <td>
		<div class="infoBox_outer">
		<table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox_new">
          <tr class="infoBoxContents_new">
            <td><table class="infoBoxCt_n_c" border="0" width="100%" cellspacing="0" cellpadding="2" style="padding-bottom:10px;">              
			  	<?php 
				$jiebantongyou = false;
				for ($i=0, $n=sizeof($order->products); $i<$n; $i++){
					//howard added auto load travel companion customers info
					$products_travel_companion_customers = array();
					if((int)$order->products[$i]['roomattributes'][5]){
						$hope_departure_date = $order->products[$i]['dateattributes'][0];
						$t_companion_id = 0;
						$products_travel_companion_customers = auto_load_travel_companion_customers_info($order->products[$i]['id'],$hope_departure_date,$t_companion_id);
						//print_r($products_travel_companion_customers);
					}
					
					//howard added travel companion
					$jiebantongyong="";
					if($order->products[$i]['roomattributes'][5]=='1'){
						$jiebantongyou = true;
						$jiebantongyong = '&nbsp;<span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
					}
					//hotel-extension {					
					$hotel_ext_addon_text = "";
					if($order->products[$i]['attributes'][0]['option_id']==HOTEL_EXT_ATTRIBUTE_OPTION_ID && $order->products[$i]['attributes'][0]['value_id']=='1'){
						$hotel_ext_addon_text = '&nbsp;<span class="main_blue"><b>'.db_to_html('(参团前加订酒店)').'</b></span>';
					}else if($order->products[$i]['attributes'][0]['option_id']==HOTEL_EXT_ATTRIBUTE_OPTION_ID && $order->products[$i]['attributes'][0]['value_id']=='2'){
						$hotel_ext_addon_text = '&nbsp;<span class="main_blue"><b>'.db_to_html('(参团后加订酒店)').'</b></span>';
					}//}				
					//amit added add extra customer information start
					$need_add_extra_checkout_fields = false;
					$need_add_extra_features_note = false;
					$need_add_features_provider_note = false;
					$products_guestgender_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
					$products_guestgender_array[] = array('id' => TEXT_CHECKOUT_GENDER_MALE, 'text' => TEXT_CHECKOUT_GENDER_MALE);
					$products_guestgender_array[] = array('id' => TEXT_CHECKOUT_GENDER_FEMALE, 'text' => TEXT_CHECKOUT_GENDER_FEMALE);
					$products_guestweight_array = array();					
					$products_guestweight_array[] = array('id' => TEXT_CHECKOUT_WEIGHT_KG, 'text' => TEXT_CHECKOUT_WEIGHT_KG);
					$products_guestweight_array[] = array('id' => TEXT_CHECKOUT_WEIGHT_POUND, 'text' => TEXT_CHECKOUT_WEIGHT_POUND);
					if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_CHECKOUT_IDS.",") || in_array($order->products[$i]['agency_id'],explode(',','12,48')) || $order->products[$i]['is_birth_info'] == '1') {
						$need_add_extra_checkout_fields = true;
					}			
					if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_FEATURES_TOUR_IDS.",")) {
						$need_add_extra_features_note = true;
					}					
					if($order->products[$i]['agency_id'] == 12 ){
						$need_add_features_provider_note = true;
					}			
					//amit added add extra customer information end
					//this is for height
					$need_add_extra_checkout_fields_height = false;
					if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_HEIGHT_CHECKOUT_IDS.",")) {
						$need_add_extra_checkout_fields_height = true;
					}
					
					//end for height
					// amit commented to remove child age ask																	
					$roomsinfo_string = trim($order->products[$i]['roomattributes'][3]);
					$ttl_rooms = get_total_room_from_str($roomsinfo_string);
					
					
					if($ttl_rooms>0){
					
						for($ir=0; $ir<$ttl_rooms; $ir++){
							//$totoal_child_room[$order->products[$i]['id']] = (int)$totoal_child_room[$order->products[$i]['id']] + tep_get_rooms_adults_childern($roomsinfo_string,$ir+1,'children');
						 $chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($order->products[$i]['roomattributes'][3],$ir+1);
						 $totoal_child_room[$order->products[$i]['id']] = (int)$totoal_child_room[$order->products[$i]['id']] + $chaild_adult_no_arr[1];
							 
						}
					}else{
						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($order->products[$i]['roomattributes'][3],1);
							$totoal_child_room[$order->products[$i]['id']] = $chaild_adult_no_arr[1];
					}
					// amit commented to remove child age ask		
				
					$class_pord_list = 'productListing-even';
					if($i%2!=0 ){
						#lwkaiRem $class_pord_list = 'productListing-odd';
					}
					
					$departuer_date_tags = tep_get_date_disp($order->products[$i]['dateattributes'][0]);
					if($order->products[$i]['no_sel_date_for_group_buy']=="1"){
						$departuer_date_tags = date('m/d/Y',strtotime($order->products[$i]['dateattributes'][0])+86400).TEXT_BEFORE;
					}
					//hotel-extension {
					if(tep_check_product_is_hotel($order->products[$i]['id'])==1){
						$txt_dept_date = db_to_html('入住日期:');
					}else{
						$txt_dept_date = TEXT_SHOPPING_CART_DEPARTURE_DATE;
					}
					//print_vars($order->products[$i]);
					$is_start_date_required=is_tour_start_date_required((int)$order->products[$i]['id']);
					 if($is_start_date_required==true){
						$DepartureDate = '<br /><span>'.$txt_dept_date.'<b > ' . tep_get_date_disp($order->products[$i]['dateattributes'][0]).'</b></span>';
						if($order->products[$i]['dateattributes'][4] != '' && $order->products[$i]['dateattributes'][4] != 0){
							 $DepartureDate =' '. $order->products[$i]['dateattributes'][3].' '. (($order->products[$i]['dateattributes'][4]!='') ? '$' : '') .$order->products[$i]['dateattributes'][4];
						}
						$hotel_guest_note = '';					
						if($order->products[$i]['is_hotel']==1){
							$hotel_extension_info = explode('|=|', $order->products[$i]['hotel_extension_info']);
							if($order->products[$i]['attributes'][0]['option_id']==HOTEL_EXT_ATTRIBUTE_OPTION_ID && $order->products[$i]['attributes'][0]['value_id']=='2'){
								$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
							}else if($order->products[$i]['attributes'][0]['option_id']==HOTEL_EXT_ATTRIBUTE_OPTION_ID){
								$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
							}
							//$hotel_guest_note = db_to_html('<span style="color:blue">注意：请填写办理酒店入住手续的人的姓名。</span>');;
							$DepartureDate .= '<span style="margin-left:2em">'.$str.db_to_html('离店日期:').'<b > ' . tep_get_date_disp($hotel_checkout_date) . '</b></span>';
							if(check_date($hotel_checkout_date) && check_date($order->products[$i]['dateattributes'][0])){
								$DepartureDate .=' <span>'.db_to_html('总共：'.date1SubDate2($hotel_checkout_date,$order->products[$i]['dateattributes'][0]).'晚').'</span>';
							}
						}
					}
					//}
					//$DepartureDate = '<br /><b style="color:#000000; font-size:18px;">'.START_DATE.$departuer_date_tags.'</b>';						;
					if($order->products[$i]['is_transfer'] != '1'){
						echo '<tr class="'.$class_pord_list.'" >
						<td class="main" style="border-bottom:1px solid #B7E0F6; ">
						'.'<table id="tour_list_'.$i.'" width="100%"><tr><td colspan="2">'.'<b class="dazi">'.($i+1).' '.db_to_html($order->products[$i]['name']).'</b>'.$hotel_ext_addon_text.$DepartureDate.''.$jiebantongyong.'<br/>'.$hotel_guest_note.'
						</td></tr>';
					}else{
						$info = tep_transfer_decode_info($order->products[$i]['transfer_info']);
						$rinfo = '<br /><span><b>'.db_to_html('接送服务信息:').'</b > </span>';
						$routeIndex = 1 ;
						foreach($info['routes'] as $route){
							if(is_numeric($route['pickup_id']) && is_numeric($route['dropoff_id'])){								
								$rinfo .= db_to_html('<br /><span><b>' . tep_get_date_disp($route['flight_arrival_time']) . '</b> '.tep_db_output($route['pickup_address']).' -> '.tep_db_output($route['dropoff_address']).'</span>');
								$routeIndex++;
							}
						}
						echo '<tr class="'.$class_pord_list.'" >
						<td class="main" style="border-bottom:1px solid #B7E0F6; padding:10px;">
						'.'<table id="tour_list_'.$i.'" width="100%"><tr><td colspan="2">'.'<b class="dazi">'.($i+1).' '.db_to_html($order->products[$i]['name']).'</b>'.$rinfo.$jiebantongyong.'<br/>'.$hotel_guest_note.'</td></tr>';
					}
					
					//团购不需要填写所有人资料按钮 start
					$is_long_trour = false;
					if((int)substr($order->products[$i]['roomattributes'][3],0,1)){
						$is_long_trour = true;
					}
					
					if(GROUP_BUY_ON==true && $order->products[$i]['roomattributes'][2] >= GROUP_MIN_GUEST_NUM && (GROUP_BUY_INCLUDE_SUB_TOUR==true || $is_long_trour==true) ){
						echo '<tr><td colspan="2"><label><input type="checkbox" name="can_un_fill_'.(int)$order->products[$i]['id'].'" id="can_un_fill_'.(int)$order->products[$i]['id'].'" onClick="can_un_fill_guest(\'tour_list_'.$i.'\', this);" value="1" /> '.db_to_html('顾客太多暂不填写顾客资料，下单后再联系填写顾客资料事宜！').'</label></td></tr>';
					}
					
					echo "<tr><td colspan='2'>" . db_to_html('游客信息：<span style="color:#154da4">游客姓名须与ID或护照相同</span>') . "</td></tr>"; 
					
					//团购不需要填写所有人资料按钮 end
					//自动填充按钮 start
					if($i>0) echo '<tr><td colspan="2"><input name="AutoSubmit" type="button" value="'.db_to_html('自动填充顾客资料').'" onClick="AutoPopGuest('.$i.')"></td></tr>';					
					//自动填充按钮 end
					//amit added to check helicopter tour 
					if(tep_get_product_type_of_product_id((int)$order->products[$i]['id']) == 2 ){					
							if($order->products[$i]['roomattributes'][2] != '')
							{
								$m=$order->products[$i]['roomattributes'][2];								
								// amit commented to remove child age ask
								$tot_nos_of_child_in_tour = $m - (int)$totoal_child_room[$order->products[$i]['id']];								
								/*echo 'm:'.$m;
								echo 'totoal_child_room:'.(int)$totoal_child_room[$order->products[$i]['id']];
								echo 'tot_nos_of_child_in_tour:'.$tot_nos_of_child_in_tour;*/
								// amit commented to remove child age ask	
								for($h=0; $h<$m; $h++)
								{
								// amit commented to remove child age ask
								$needed_show_child_title = false;
								$default_show_adult_title = TXT_DIS_ENTRY_GUEST_ADULT;
								if( ($h >= $tot_nos_of_child_in_tour) && $tot_nos_of_child_in_tour > 0){
									$needed_show_child_title =  true;
									$default_show_adult_title = TXT_DIS_ENTRY_GUEST_CHILD;
								}
								
								
								// amit commented to remove child age ask
									 if(($h%2)==0) echo '<tr>';
								?>
									<td>
									<table width="100%" cellpadding="2" cellspacing="0"><tr><td class="main" width="20%" style="padding-left:30px; ">
									
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <?php
								  $guestname_title = sprintf(TEXT_INFO_GUEST_NAME,($h+1));
								  $guestname_title_en = sprintf(db_to_html("顾客%s护照<b class=inputRequirement>英文名</b>"),($h+1));
								  
								  //结伴同游
								  if((int)$order->products[$i]['roomattributes'][5]){
								  	$mail_name = sprintf(db_to_html("拼房伴%s的注册邮箱"),$h);
									$guestname_title = sprintf(db_to_html('拼房伴%s中文名'),$h);
									$guestname_title_en = sprintf(db_to_html('拼房伴%s护照<b class=inputRequirement>英文名</b>'),$h);
									$readonly ='';
									if(count($products_travel_companion_customers)<2){	//在没有自动载入结伴客户资料时才能使用
										$guestemail_onblur=' onBlur="checkSameAccount(); check_and_get_guestname(&quot;GuestEmail'.$h.'_'.$i.'&quot;, &quot;guestname'.$h.'_'.$i.'&quot;)" ';
									}
;
									if($h==0 ){
										$mail_name = db_to_html('我的注册邮箱');
										$GuestEmail = 'GuestEmail'.$h.'['.$i.']';
										$$GuestEmail = $customer_email_address;
										$guestname_title = db_to_html('顾客1中文名');
										$guestname_title_en = db_to_html('顾客1的护照<b class=inputRequirement>英文名</b>');
										$readonly = ' readonly="true" ';
										$guestemail_onblur ='';
									}
									if($needed_show_child_title == true){
										$mail_name = sprintf(db_to_html("拼房伴%s的注册邮箱"),$h);
										$guestname_title = db_to_html('儿童中文名');
										$guestname_title_en = db_to_html('儿童护照<b class=inputRequirement>英文名</b>');
									}
									//if($needed_show_child_title != true){
								  ?>
										  <tr>
											<td align="right" valign="top" nowrap="nowrap">
											<?php echo db_to_html('付款人：');?>&nbsp;									
											</td>
											<td>
											<?php
											if($_SESSION['PayerMe'.$h][$i]=='1' || $h==0){
												$Payer_Me_Checked = true;
												$Payer_Me_Checked1 = false;
											}elseif($products_travel_companion_customers['who_payment']=="1"){
												$Payer_Me_Checked = true;
												$Payer_Me_Checked1 = false;
											}else{
												$Payer_Me_Checked = false;
												$Payer_Me_Checked1 = true;
											}
											echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '1',$Payer_Me_Checked,' id="PayerMe'.$h.'_'.$i.'_A" onClick="determine_input_field(this,&quot;'.$h.'_'.$i.'&quot;)" ').db_to_html(' <label for="PayerMe'.$h.'_'.$i.'_A">我付</label>');
											echo "&nbsp;";
											if($h>0){
												echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '0',$Payer_Me_Checked1,' id="PayerMe'.$h.'_'.$i.'_A" onClick="determine_input_field(this,&quot;'.$h.'_'.$i.'&quot;)" ').db_to_html(' <label for="PayerMe'.$h.'_'.$i.'_B">我不帮他付</label>');
											}
											?>
											
											</td>
										  </tr>
										  
										  <?php
										  $guestemailstyle = '';
										  if($needed_show_child_title == true){
										  	$guestemailstyle = 'display:none';
										  }										  
										  ?>
										  <tr style="<?= $guestemailstyle?>">
											<td align="right" valign="top" nowrap="nowrap">
											<?php echo $mail_name?>&nbsp;									
											</td>
											<td>
											<?php
											$mail_field_value = $_SESSION['GuestEmail'.$h][$i];
											if(!tep_not_null($mail_field_value) && $h>0){ $mail_field_value = $products_travel_companion_customers['app'][($h-1)]['customers_email'];}
											echo tep_draw_input_field('GuestEmail'.$h.'['.$i.']', $mail_field_value,' id="GuestEmail'.$h.'_'.$i.''.'" class="required" autocomplete="off"   title="'.TEXT_PLEASE_INSERT_GUEST_EMAIL.'"'.$readonly.$guestemail_onblur);?>
											<span class="inputRequirement">*</span><?php /*?>onkeyup="auto_list_customers_address(&quot;GuestEmail'.$h.'_'.$i.'&quot;,&quot;Layer'.$h.'_'.$i.'&quot;);"onFocus="auto_list_customers_address(&quot;GuestEmail'.$h.'_'.$i.'&quot;,&quot;Layer'.$h.'_'.$i.'&quot;);"<?php */?>
											<!--电子邮件列表层-->
											<div style="position: absolute; width: auto; height: auto; z-index: 1; visibility: visible; margin-top: 0px; margin-left: 0px; display:none" class="meun_layer" id="Layer<?php echo $h.'_'.$i?>"></div>
											<!--电子邮件列表层end-->
											</td>
										  </tr>
								  <?php
								  	//}
								  }elseif(tep_not_null($_SESSION['GuestEmail'.$h][$i])){
								  	$_SESSION['GuestEmail'.$h][$i] = "";
								  }
								  //结伴同游
								  ?>
								  
									<?php																					
									// amit commented to remove child age ask
									if($needed_show_child_title == true){
									
									$dis_ext_clas_faild_for_wrong_chlidage = '';
									if(urldecode($HTTP_GET_VARS['wrgdate']) == tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]) && isset($HTTP_GET_VARS['wrgdate'])){
										$dis_ext_clas_faild_for_wrong_chlidage = ' validation-failed';
									}
									?>																					
									<tr><td align="right" valign="top" nowrap="nowrap">
									<?php echo ENTRY_GUEST_CHILD_AGE;?>&nbsp;
									</td>
									<td>
									<?php echo tep_draw_input_field('guestchildage'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]), 'id="guestchildage'.$h.'_'.$i.'" size="10" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="required validate-date-us text_time '.$dis_ext_clas_faild_for_wrong_chlidage.'" title="'.TEXT_GUEST_ERROR_CHILD_BIRTH_DATE.'"'); ?>&nbsp;<span class="inputRequirement">*</span>
									</td></tr>
									<?php
									}
									// amit commented to remove child age ask
									
									?>

										  <tr>
											<td align="right" valign="top" nowrap="nowrap"><?php echo $guestname_title_en;?>&nbsp;</td>
											<td valign="top">											
											<?php echo db_to_html('姓')?>
											<?php echo tep_draw_input_field('GuestEngXing'.$h.'['.$i.']', $_SESSION['GuestEngXing'.$h][$i], ' id="GuestEngXing'.$h.'_'.$i.'" class="required" size="11" title="'.db_to_html('必填项').'" style="ime-mode: disabled;" '); ?>
											<?php echo db_to_html('名')?>
											<?php echo tep_draw_input_field('GuestEngName'.$h.'['.$i.']', $_SESSION['GuestEngName'.$h][$i], ' id="GuestEngName'.$h.'_'.$i.'" class="required" size="12" style="ime-mode: disabled;" title="'.db_to_html('必填项').'"'); ?>&nbsp;<span class="inputRequirement">* </span><br />
											<span style="color:#6F6F6F"><?php echo db_to_html('请确保输入的姓和名与护照一致。');?></span>
                                            </td>
										  </tr>
										  <tr>
											<td align="right" nowrap="nowrap" width="1%"><?php echo $guestname_title;?>&nbsp;</td>
											<td>
											<?php
											$guest_cn_name = $_SESSION['guestname'.$h][$i];
											if(!tep_not_null($guest_cn_name) && $h>0){
												$guest_cn_name = db_to_html($products_travel_companion_customers['app'][($h-1)]['customers_cn_name']);
											}
											//如果是单人配房则需要填写中文姓名
											$guest_class = "";
											$guest_must_ipnut_tag = "";
											if($order->products[$i]['roomattributes'][6]=='1' && preg_match('/###1!!0/',$order->products[$i]['roomattributes'][3])){
												$guest_class = ' class="required" ';
												$guest_must_ipnut_tag = '&nbsp;<span class="inputRequirement">*</span>';
											}
											echo tep_draw_input_field('guestname'.$h.'['.$i.']', $guest_cn_name, $guest_class.' id="guestname'.$h.'_'.$i.'" title="'.TEXT_PLEASE_INSERT_GUEST_NAME.'"').$guest_must_ipnut_tag; 
											?></td>
										  </tr>
								  
											<tr>
											<td align="right" valign="top" nowrap="nowrap">
											<?php echo TEXT_INFO_GUEST_BODY_WEIGHT.($h+1).'):'.'&nbsp;'?>
											</td>
											<td>
											<?php echo tep_draw_pull_down_menu('guestweighttype'.$h.'['.$i.']', $products_guestweight_array,  tep_db_prepare_input($_SESSION['guestweighttype'.$h][$i]), ' id="guestweighttype'.$h.'['.$i.']" style="width:65px;"') .' '. tep_draw_input_field('guestbodyweight'.$h.'['.$i.']', $_SESSION['guestbodyweight'.$h][$i], ' class="required" title="'.db_to_html('请输入体重').'" id="guestbodyweight'.$h.'_'.$i.'" size="3"'); ?><span class="inputRequirement">*</span>											
											</td>
											
											</tr>
                                            <?php if($need_add_extra_checkout_fields == true){ ?>
                                            <tr><td align="right" valign="top" nowrap="nowrap">
                                            <?php 	echo ENTRY_GUEST_GENDER;?>
                                            </td>
                                            <td>
                                            <?php 	echo tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  tep_db_prepare_input($_SESSION['guestgender'.$h][$i]), ' id="guestgender'.$h.'['.$i.']" class="required selglobus" style="margin-top:2px; margin-bottom:2px;" title="'.db_to_html('必填项').'"  ');?><span class="inputRequirement">*</span>
                                            </td></tr>
                                            <?php if($needed_show_child_title != true){ ?>
                                            <tr><td align="right" valign="top" nowrap="nowrap">
                                            <?php echo ENTRY_GUEST_DATE_OF_BIRTH; ?>
                                            </td>
                                            <td><?php echo tep_draw_input_field('guestdob'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestdob'.$h][$i]), '  id="guestdob'.$h.'['.$i.']" class="required validate-date-us'.$dis_ext_clas_faild_for_wrong_chlidage.'" style="ime-mode:disabled;" title="'.TEXT_GUEST_ERROR_CHILD_BIRTH_DATE.'"'); //onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ?>&nbsp;<span class="inputRequirement">*</span></td>
                                            </tr>
                                            <?php }	?>
                                            <?php } else if($order->products[$i]['is_gender_info'] == '1'){	?>
                                             <tr>
                                             <td align="right" valign="top" nowrap="nowrap">
                                            <?php 	echo ENTRY_GUEST_GENDER;?>
                                            </td>
                                            <td>
                                            <?php 	echo tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  tep_db_prepare_input($_SESSION['guestgender'.$h][$i]), ' id="guestgender'.$h.'['.$i.']" class="required selglobus" style="margin-top:2px; margin-bottom:2px;" title="'.db_to_html('必填项').'"  ');?><span class="inputRequirement">*</span>
                                            </td>
                                            </tr>
                                            <?php }	?>
									  </table>
									
								<?php //自动判断是否加不加"可不填"的字眼?>
								<script type="text/javascript">
									var sobj = document.getElementById('PayerMe<?php echo $h.'_'.$i?>_B');
									if(sobj!=null){
										determine_input_field(sobj,"<?php echo $h.'_'.$i?>");
									}
								</script>
								<?php //自动判断是否加不加"可不填"的字眼end?>
									
									</td></tr>
									
									</table>
									
									</td>
								<?php
									if(($h%2)!=0)
									echo '</tr>';
								}// end of for($h=0; $h<$m; $h++)
								if($need_add_features_provider_note == true){
									?><tr><td colspan="2"><div class="tipNote"><?php echo TEXT_FEATURES_PROVIDER_NOTE; ?></div></td></tr><?php
								}
							}
					}else{			
					
					if($order->products[$i]['roomattributes'][2] != '')
					{
						$m=$order->products[$i]['roomattributes'][2];
						
						// amit commented to remove child age ask
						$tot_nos_of_child_in_tour = $m - (int)$totoal_child_room[$order->products[$i]['id']];
						/*echo 'm:'.$m;
						 echo 'totoal_child_room:'.(int)$totoal_child_room[$order->products[$i]['id']];
						 echo 'tot_nos_of_child_in_tour:'.$tot_nos_of_child_in_tour;*/
						// amit commented to remove child age ask
						
						for($h=0; $h<$m; $h++)
						{
						// amit commented to remove child age ask
						$needed_show_child_title = false;
						$default_show_adult_title = TXT_DIS_ENTRY_GUEST_ADULT;
						if( ($h >= $tot_nos_of_child_in_tour) && $tot_nos_of_child_in_tour > 0){
							$needed_show_child_title =  true;
							$default_show_adult_title = TXT_DIS_ENTRY_GUEST_CHILD;
						}
						// amit commented to remove child age ask
						
						//if(($h%2)==0)		 echo '<tr>';
						?>
							<tr><td>
							<table width="100%" cellpadding="2" cellspacing="0">
								<tr><td class="main">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <?php
								  $guestname_title = sprintf(TEXT_INFO_GUEST_NAME,($h+1));
								  // old 代码 $guestname_title_en = sprintf(db_to_html("顾客%s护照<b class=inputRequirement>英文名</b>"),($h+1));								  
								  $guestname_title_en = sprintf(db_to_html("<strong>游客%s：</strong>姓(拼音或者英文):"),($h+1));	
								  
								  //结伴同游
								  if((int)$order->products[$i]['roomattributes'][5]){
								  	$mail_name = sprintf(db_to_html("拼房伴%s的注册邮箱"),$h);
									$guestname_title = sprintf(db_to_html('拼房伴%s中文名'),$h);
									// old code $guestname_title_en = sprintf(db_to_html('拼房伴%s护照<b class=inputRequirement>英文名</b>'),$h);
									$guestname_title_en = sprintf(db_to_html('拼房伴%s：姓(拼音或者英文):'),$h);
									$readonly ='';
									if(count($products_travel_companion_customers)<2){	//在没有自动载入结伴客户资料时才能使用
										$guestemail_onblur=' onBlur="checkSameAccount(); check_and_get_guestname(&quot;GuestEmail'.$h.'_'.$i.'&quot;, &quot;guestname'.$h.'_'.$i.'&quot;)" ';
									}									
									if($h==0){
										$mail_name = db_to_html('我的注册邮箱');
										$GuestEmail = 'GuestEmail'.$h.'['.$i.']';
										$$GuestEmail = $customer_email_address;
										$guestname_title = db_to_html('顾客1中文名');
										//old code $guestname_title_en = db_to_html('顾客1的护照<b class=inputRequirement>英文名</b>');
										$guestname_title_en = db_to_html('游客1：姓(拼音或者英文):');
										$readonly = ' readonly="true" ';
										$guestemail_onblur ='';
									}
									if($needed_show_child_title == true){
										$mail_name = sprintf(db_to_html("拼房伴%s的注册邮箱"),$h);
										$guestname_title = db_to_html('儿童中文名');
										$guestname_title_en = db_to_html('儿童护照<b class=inputRequirement>英文名</b>');
									}
									
									//if($needed_show_child_title != true){
								  ?>  <tr>
											<td valign="top" nowrap="nowrap" style="line-height:26px;padding:5px 0;position:relative;display:block;">
											<?php echo db_to_html('付款人：');?>&nbsp;									
											<?php
											if($_SESSION['PayerMe'.$h][$i]=='1' || $h==0){
												$Payer_Me_Checked = true;
												$Payer_Me_Checked1 = false;
											}elseif($products_travel_companion_customers['who_payment']=="1"){
												$Payer_Me_Checked = true;
												$Payer_Me_Checked1 = false;
											}else{
												$Payer_Me_Checked = false;
												$Payer_Me_Checked1 = true;
											}
											echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '1',$Payer_Me_Checked,' id="PayerMe'.$h.'_'.$i.'_A" onClick="determine_input_field(this,&quot;'.$h.'_'.$i.'&quot;)" ').db_to_html(' <label for="PayerMe'.$h.'_'.$i.'_A">我付</label>');
											echo "&nbsp;";
											if($h>0){
												echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '0',$Payer_Me_Checked1,' id="PayerMe'.$h.'_'.$i.'_B" onClick="determine_input_field(this,&quot;'.$h.'_'.$i.'&quot;)" ').db_to_html(' <label for="PayerMe'.$h.'_'.$i.'_B">我不帮他付</label>');
											}
											?>&nbsp;&nbsp;
                                          <!--付款人排版修改 start-->
                                            <?php echo $mail_name;?>&nbsp;									
											<?php 
											$mail_field_value = $_SESSION['GuestEmail'.$h][$i];
											if(!tep_not_null($mail_field_value) && $h>0){ $mail_field_value = $products_travel_companion_customers['app'][($h-1)]['customers_email'];}
											echo tep_draw_input_field('GuestEmail'.$h.'['.$i.']', $mail_field_value,' id="GuestEmail'.$h.'_'.$i.''.'" class="required" autocomplete="off"   title="'.TEXT_PLEASE_INSERT_GUEST_EMAIL.'"'.$readonly.$guestemail_onblur);
											?><?php /*?>onkeyup="auto_list_customers_address(&quot;GuestEmail'.$h.'_'.$i.'&quot;,&quot;Layer'.$h.'_'.$i.'&quot;);"onFocus="auto_list_customers_address(&quot;GuestEmail'.$h.'_'.$i.'&quot;,&quot;Layer'.$h.'_'.$i.'&quot;);"<?php */?>
											<span class="inputRequirement">*</span>
											<!--电子邮件列表层-->
											<div style="position: absolute; width: auto; height: auto; z-index: 1; visibility: visible; margin-top: 0px; margin-left: 0px; display:none;left:300px;" class="meun_layer" id="Layer<?php echo $h.'_'.$i?>"></div>
											<!--电子邮件列表层end-->
                                          <!--付款人排版修改 end-->
										  </tr>
										  
										  <?php
										  $guestemailstyle = '';
										  if($needed_show_child_title == true){
										  	$guestemailstyle = 'display:none';
										  }
										  ?>
								  <?php
								  	//}
								  }elseif(tep_not_null($_SESSION['GuestEmail'.$h][$i])){
								  	$_SESSION['GuestEmail'.$h][$i] = "";
								  }
								  //结伴同游
								  ?>
								
								<?php										
								// amit commented to remove child age ask
								if($needed_show_child_title == true){
								$dis_ext_clas_faild_for_wrong_chlidage = '';
								if(urldecode($HTTP_GET_VARS['wrgdate']) == tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]) && isset($HTTP_GET_VARS['wrgdate'])){
									$dis_ext_clas_faild_for_wrong_chlidage = ' validation-failed';
								}
								?>																					
								<tr><td align="right" valign="top" nowrap="nowrap">
								<?php echo ENTRY_GUEST_CHILD_AGE;?>&nbsp;
								</td>
								<td>
								<?php echo tep_draw_input_field('guestchildage'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]), 'id="guestchildage'.$h.'_'.$i.'" size="10" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="required validate-date-us text_time '.$dis_ext_clas_faild_for_wrong_chlidage.'" title="'.TEXT_GUEST_ERROR_CHILD_BIRTH_DATE.'"'); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<?php
								}
								// amit commented to remove child age ask
								
								?>
								  
								  <tr>
									<td align="left" valign="top" nowrap="nowrap"><?php echo $guestname_title_en;?>&nbsp;<!--</td>-->
									<!--<td valign="top">-->
                                    <script type="text/javascript">
									<!--
									function setChinaseName(h,i){
										jQuery('#guestname' + h + '_' + i).val(jQuery('#GuestEngXing' + h + '_' + i).val() + jQuery('#GuestEngName' + h + '_' + i).val());
									}
									//-->
									</script>
									<?php #echo db_to_html('姓');?>
									<?php echo tep_draw_input_field('GuestEngXing'.$h.'['.$i.']', $_SESSION['GuestEngXing'.$h][$i], ' id="GuestEngXing'.$h.'_'.$i.'" class="required" size="11" title="'.db_to_html('必填项').'" style="ime-mode: disabled;" onkeyup="setChinaseName(' . $h . ',' . $i . ')"'); ?>
									<?php echo db_to_html('<span class="inputRequirement">* </span>&nbsp;名(拼音或英文):') #echo db_to_html('名')?>
									<?php echo tep_draw_input_field('GuestEngName'.$h.'['.$i.']', $_SESSION['GuestEngName'.$h][$i], ' id="GuestEngName'.$h.'_'.$i.'" class="required" size="12" style="ime-mode: disabled;" title="'.db_to_html('必填项').'" onkeyup="setChinaseName(' . $h . ',' . $i . ')"'); ?>&nbsp;<span class="inputRequirement">* </span><!--<br />-->
									<?php /*?><span style="color:#6F6F6F"><?php echo db_to_html('请确保输入的姓和名与护照一致。');?></span><?php */?>
                                    
                                    <?php // 选择性别 开始 by lwkai 2012-4-21{ ?>
                                    <?php if($need_add_extra_checkout_fields == true){ ?>
									<!--<tr><td colspan="2" align="left" style="padding:5px 0;" valign="top" nowrap="nowrap">-->
									<?php 	echo ENTRY_GUEST_GENDER;?>
									<?php 	echo tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  tep_db_prepare_input($_SESSION['guestgender'.$h][$i]), ' id="guestgender'.$h.'['.$i.']" class="required selglobus" style="margin-top:2px; margin-bottom:2px;" title="'.db_to_html('必填项').'"  ');?><span class="inputRequirement">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php if($needed_show_child_title != true){ ?>
									<?php echo ENTRY_GUEST_DATE_OF_BIRTH;?>
									<?php echo tep_draw_input_field('guestdob'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestdob'.$h][$i]), '  id="guestdob'.$h.'['.$i.']" class="required validate-date-us'.$dis_ext_clas_faild_for_wrong_chlidage.'" style="ime-mode:disabled;" title="'.TEXT_GUEST_ERROR_CHILD_BIRTH_DATE.'"'); //onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ?>&nbsp;<span class="inputRequirement">*</span>
									<!--</tr>-->
									<?php }	?>
									<?php } else if($order->products[$i]['is_gender_info'] == '1'){	?>
                                    <!-- <tr><td colspan="2" align="left" valign="top" nowrap="nowrap">-->
									<?php 	echo ENTRY_GUEST_GENDER; ?>
									<?php 	echo tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  tep_db_prepare_input($_SESSION['guestgender'.$h][$i]), ' id="guestgender'.$h.'['.$i.']" class="required selglobus" style="margin-top:2px; margin-bottom:2px;" title="'.db_to_html('必填项').'"  ');?><span class="inputRequirement">*</span>
									<!--</td>
                                    </tr>-->
                                    <?php }	?>
                                    
                                    <?php // 选择性别 结束 } ?>
									</td>
								  </tr>
								  <tr style="display:none">
									<td align="right" nowrap="nowrap" width="1%"><?php echo $guestname_title;?>&nbsp;</td>
									<td>
									<?php 
									$guest_cn_name = $_SESSION['guestname'.$h][$i];
									if(!tep_not_null($guest_cn_name) && $h>0){
										$guest_cn_name = db_to_html($products_travel_companion_customers['app'][($h-1)]['customers_cn_name']);
									}
									//如果是单人配房则需要填写中文姓名
									$guest_class = "";
									$guest_must_ipnut_tag = "";
									if($order->products[$i]['roomattributes'][6]=='1' && preg_match('/###1!!0/',$order->products[$i]['roomattributes'][3])){
										$guest_class = ' class="required" ';
										$guest_must_ipnut_tag = '&nbsp;<span class="inputRequirement">*</span>';
									}
									echo tep_draw_input_field('guestname'.$h.'['.$i.']', $guest_cn_name, $guest_class.' id="guestname'.$h.'_'.$i.'" title="'.TEXT_PLEASE_INSERT_GUEST_NAME.'"').$guest_must_ipnut_tag; 
									?></td>
								  </tr>
								  <?php if($need_add_extra_checkout_fields_height == true){ ?>
									<tr><td colspan="2" align="left" valign="top" nowrap="nowrap">
									<?php 	echo ENTRY_GUEST_HEIGHT;?>
									<?php echo tep_draw_input_field('guestbodyheight'.$h.'['.$i.']', stripslashes($_SESSION['guestbodyheight'.$h][$i]), ' class="required" title="'.db_to_html('身高').'" id="guestbodyheight'.$h.'_'.$i.'"'); ?><span class="inputRequirement">*</span>
									</td></tr>
									<?php } ?>
								  <?php
								  /* 原来的 选性别的 代码 start by lwkai 2012-04-21 {
								   if($need_add_extra_checkout_fields == true){ ?>
									<tr><td colspan="2" align="left" style="padding:5px 0;" valign="top" nowrap="nowrap">
									<?php 	echo ENTRY_GUEST_GENDER;?>
									<?php 	echo tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  tep_db_prepare_input($_SESSION['guestgender'.$h][$i]), ' id="guestgender'.$h.'['.$i.']" class="required selglobus" style="margin-top:2px; margin-bottom:2px;" title="'.db_to_html('必填项').'"  ');?><span class="inputRequirement">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php if($needed_show_child_title != true){ ?>
									<?php echo ENTRY_GUEST_DATE_OF_BIRTH;?>
									<?php echo tep_draw_input_field('guestdob'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestdob'.$h][$i]), '  id="guestdob'.$h.'['.$i.']" class="required validate-date-us'.$dis_ext_clas_faild_for_wrong_chlidage.'" style="ime-mode:disabled;" title="'.TEXT_GUEST_ERROR_CHILD_BIRTH_DATE.'"'); //onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ?>&nbsp;<span class="inputRequirement">*</span>
									</tr>
									<?php }	?>
									<?php } else if($order->products[$i]['is_gender_info'] == '1'){	?>
                                     <tr><td colspan="2" align="left" valign="top" nowrap="nowrap">
									<?php 	echo ENTRY_GUEST_GENDER; ?>
									<?php 	echo tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  tep_db_prepare_input($_SESSION['guestgender'.$h][$i]), ' id="guestgender'.$h.'['.$i.']" class="required selglobus" style="margin-top:2px; margin-bottom:2px;" title="'.db_to_html('必填项').'"  ');?><span class="inputRequirement">*</span>
									</td>
                                    </tr>
                                    <?php }	?>
									
									// 原来的 选择性别的 代码 end } */?>
									
								</table>

								<?php //自动判断是否加不加"可不填"的字眼?>
								<script type="text/javascript">
									var sobj = document.getElementById('PayerMe<?php echo $h.'_'.$i?>_B');
									if(sobj!=null){
										determine_input_field(sobj,"<?php echo $h.'_'.$i?>");
									}
								</script>
								<?php //自动判断是否加不加"可不填"的字眼end?>

								</td></tr>
								
							</table>
							</td></tr>
							
					<?php
						//if(($h%2)!=0)				echo '</tr>';
						}// end of for($h=0; $h<$m; $h++)
						
						if($need_add_extra_features_note == true){
							?><tr><td colspan="2"><div class="tipNote"><?php echo TEXT_EXTRA_FEATURES_NOTE; ?></div></td></tr><?php
						}
						if($need_add_features_provider_note == true){
							?><tr><td colspan="2"><div class="tipNote"><?php echo TEXT_FEATURES_PROVIDER_NOTE; ?></div></td></tr><?php
						}
					
					}else if($order->products[$i]['is_transfer'] == '1'){
							$h = 0;	
							$guestname_title = db_to_html('顾客1中文名');							
						?>
						<td >
						<table width="100%" cellpadding="2" cellspacing="0">
						<tr>
							<td align="right" valign="top" nowrap="nowrap"><?php echo db_to_html('顾客1的护照<b class=inputRequirement>英文名</b>');?>&nbsp;</td>
							<td valign="top">
							<?php echo db_to_html('姓');?>
							<?php echo tep_draw_input_field('GuestEngXing'.$h.'['.$i.']', $_SESSION['GuestEngXing'.$h][$i], ' id="GuestEngXing'.$h.'_'.$i.'" class="required" size="11" title="'.db_to_html('必填项').'" style="ime-mode: disabled;" '); ?>
							<?php echo db_to_html('名')?>
							<?php echo tep_draw_input_field('GuestEngName'.$h.'['.$i.']', $_SESSION['GuestEngName'.$h][$i], ' id="GuestEngName'.$h.'_'.$i.'" class="required" size="12" style="ime-mode: disabled;" title="'.db_to_html('必填项').'"'); ?>&nbsp;<span class="inputRequirement">* </span><br />
							<span style="color:#6F6F6F"><?php echo db_to_html('请确保输入的姓和名与护照一致。');?></span>
							</td>
						 </tr>
						 <tr>
									<td align="right" nowrap="nowrap" width="1%"><?php echo $guestname_title;?>&nbsp;</td>
									<td>
									<?php 
									$guest_cn_name = $_SESSION['guestname'.$h][$i];
									if(!tep_not_null($guest_cn_name) && $h>0){
										$guest_cn_name = db_to_html($products_travel_companion_customers['app'][($h-1)]['customers_cn_name']);
									}
									//如果是单人配房则需要填写中文姓名
									$guest_class = "";
									$guest_must_ipnut_tag = "";
									if($order->products[$i]['roomattributes'][6]=='1' && preg_match('/###1!!0/',$order->products[$i]['roomattributes'][3])){
										$guest_class = ' class="required" ';
										$guest_must_ipnut_tag = '&nbsp;<span class="inputRequirement">*</span>';
									}
									echo tep_draw_input_field('guestname'.$h.'['.$i.']', $guest_cn_name, $guest_class.' id="guestname'.$h.'_'.$i.'" title="'.TEXT_PLEASE_INSERT_GUEST_NAME.'"').$guest_must_ipnut_tag; 
									?></td>
							 </tr>
					 </table>
					 </td>
					 <?php
					 if(($h%2)!=0)				echo '</tr>';
					}//end transfer 

					
					}
					
					if($need_add_extra_checkout_fields == true){
						echo '<tr><td colspan="2"><div class="tipNote">'.db_to_html('您填写的出生日期及性别信息涉及到机票或船票的购买，请认真核对是否与护照或ID一致，避免由于填写错误而导致无法登机或登船等经济损失。谢谢！').'</div></td></tr>';
					}
					
					// 单人部分配房 start {
					if($order->products[$i]['roomattributes'][6]=='1' && preg_match('/###1!!0/',$order->products[$i]['roomattributes'][3])){
						$SingleGenderChecked0 = true;
						$SingleGenderChecked1 = false;
						if($_SESSION['SingleGender'][$i]=='f'){
							$SingleGenderChecked0 = false;
							$SingleGenderChecked1 = true;
						}
						$dan_ren_pei_fang_str = '';#<font color="red">'.db_to_html('单人配房仅限男性').'</font>';
						$f_disabled = "";// disabled=disabled ";
						if(defined('SEXES_ROOM_PROD_IDS') && SEXES_ROOM_PROD_IDS!=""){
							$both_ids = explode(',',str_replace(' ','',SEXES_ROOM_PROD_IDS));
							if(in_array((int)$order->products[$i]['id'], $both_ids)){
								$dan_ren_pei_fang_str = "";
								$f_disabled = "";
							}
						}
						echo '<tr><td colspan="2">'.'<div style="margin-left:247px; margin-top:19px; position: absolute; width: auto; height: auto; z-index: 1; visibility: visible; display:none;" class="meun_layer" id="LayerSingleName'.$i.'"></div>'.db_to_html('请确定单人部分配房的客人姓名及性别：').db_to_html('姓名&nbsp;').tep_draw_input_field('SingleName['.$i.']', $_SESSION['SingleName'][$i], ' id="SingleName_'.$i.'" class="required" title="'.db_to_html('必填项').'" onMouseMove="SelSingleName(\''.$i.'\')" autocomplete="off" ').'&nbsp;&nbsp;'.db_to_html('性别&nbsp;').'<label>'.tep_draw_radio_field('SingleGender['.$i.']', 'm', $SingleGenderChecked0).db_to_html('男').'</label>'.'&nbsp;&nbsp;'.'<label>'.tep_draw_radio_field('SingleGender['.$i.']', 'f', $SingleGenderChecked1,$f_disabled).db_to_html('女').'</label>&nbsp;&nbsp;<label>'.$dan_ren_pei_fang_str.'</label>
						'.tep_draw_input_field('is_Single_AU['.$i.']',1,'','hidden').'
						</td></tr>';
					}
					// 单人部分配房 end }
					?>
				                    
				 <?php if($order->products[$i]['is_hotel_pickup_info'] == '1'){
				  echo '<tr><td colspan="2" class="main" style="border-top:1px dashed #6F6F6F; padding-top:8px;">';
				 ?>
                    <div class="flight_info"><br>
                    <p><strong><?php echo TEXT_DOSE_TOUR_HOTEL_PICKUP;?></strong>
                    <?php
                        if($_SESSION['is_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id']]!=''){
                            $is_hotel_pickup_info_selected_yes=true;
                            $is_hotel_pickup_info_selected_no=false;
                        }else{
                            $is_hotel_pickup_info_selected_yes=false;
                            $is_hotel_pickup_info_selected_no=true;
                        }							
                        echo tep_draw_radio_field('radio_is_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id'],'1',$is_hotel_pickup_info_selected_yes,' onClick="toggel_div_show(\'div_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id'].'\');" ').'&nbsp;'.TEXT_YES.'&nbsp;'.tep_draw_radio_field('radio_is_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id'],'0',$is_hotel_pickup_info_selected_no,'onClick="toggel_div_hide(\'div_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id'].'\');"').'&nbsp;'.TEXT_NO ; 
                    ?>                        
                    </p>
                    <p> 
                        <div id="<?php echo 'div_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id'];?>"  style="display:<?php echo $is_hotel_pickup_info_selected_yes == true ? 'block' : 'none'; ?>;" >
                            <p>
                                <strong><?php echo TEXT_NOTE_HOTEL_INFO_BELOW;?></strong><br>
                                <?php echo tep_draw_textarea_field('is_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id'], 'soft', '60', '5','',' style="border:1px solid #99CCFF;" '); ?><br>
                                <lable class="sp1"><?php echo TEXT_TOUR_HOTEL_PICKUP_NOTE; ?></lable>
                            </p>                                
                        </div>
                    </p>
                    
                    </div>
                    <?php 
					echo '</td></tr>';
					} ?>
                    
					<?php
					echo '<tr> <td colspan="2" class="main" style="border-top:1px dashed #6F6F6F; padding-top:8px;">';
					?>
					
					<?php
					$flight_info_display = '';
					$flight_info_link_text = '隐藏';
					if(tep_not_null($airline_name[$i])){
						$flight_info_display = 'none';
						$flight_info_link_text = '显示';
					}
					if($order->products[$i]['is_hotel'] == '1') {
						$hideFlightInput = 'style="display:none"';
					}elseif((int)getProductsCruisesId((int)$order->products[$i]['id'])){
						$hideFlightInput = 'style="display:none"';
					}else{ 
						$hideFlightInput = '';
					}
					?>
					
					<div <?php  echo $hideFlightInput;?>><b><?php echo TEXT_FLIGHT_INFO_IF_APPLICABLE;?></b> <a id="flight_info_link_<?php echo $i;?>" href="JavaScript:display_table('flight_info_link_<?php echo $i;?>', 'flight_info_<?php echo $i;?>');" style="text-decoration: underline;font-weight: bold;"><?php echo db_to_html($flight_info_link_text);?></a>
					<?php 
					////所有AA、SEA、PCC 从拉斯维加斯上车的旅行团，在客人下单中填写航班信息处添加信息 -BEGIN
					//@author vincent 2011-5-4		
					$need_notice_agency = array(5,2,70);	
					$products_id = intval($order->products[$i]['id']);		
					$sql = 'SELECT agency_id,departure_city_id FROM '.TABLE_PRODUCTS.' WHERE products_id = '.$products_id.' LIMIT 1';
					$_product = tep_db_fetch_array(tep_db_query($sql));
					$product_agency_id = intval($_product['agency_id']);
					$product_departure_city = explode(',',$_product['departure_city_id']);
					
					//explode(' ',$order->products[$i]['dateattributes'][2])
					$addr = preg_replace("/(\s|\n)+/",'', $order->products[$i]['dateattributes'][2]);
					if(in_array($product_agency_id,$need_notice_agency)){
						if(in_array(3,$product_departure_city)){
							//判断用户是否选择了 LASVGAS作为上车地点
							$sql = 'SELECT departure_address,departure_full_address FROM '.TABLE_PRODUCTS_DEPARTURE.' WHERE products_id = '.$products_id.' AND departure_region = \'Las Vegas\' OR departure_region = \'Las Vgeas\' OR departure_region=\'拉斯维加斯\'';
							
							$departure_address = tep_db_query($sql);
							while($row = tep_db_fetch_array($departure_address)){
								$row1 = preg_replace("/(\s|\n)+/",'', $row['departure_address'].$row['departure_full_address']);
								//echo $addr.'=='.$row1."?<br>";
								if($row1 == $addr){
									$flight_info_display = '';
									echo  db_to_html('<span  style="margin-left:15px;">请您提供到达拉斯维加斯的航班信息以便导游当天可以顺利与您安排汇合事宜</span><br>');
									break;
								}
							}
						}
					}
					////所有AA、SEA、PCC 从拉斯维加斯上车的旅行团，在客人下单中填写航班信息处添加信息	-END
				?>
					<div class="head_note" style="margin-top:0px; margin-left:10px; float:none">
					<?php
					if(tep_not_null($order->products[$i]['dateattributes'][2])){
						echo db_to_html('<b>车站接送的团，不提供免费的接送机服务。</b>');
					}
					if($i==0){	//航班提示信息
					?>
						
					<?php echo TEXT_FLIGHT_INFO_IF_AVAILABLE;?><div style="display:none;"><a class="tipslayer sp3" href="javascript:void(0)" style="margin-left:15px; text-align:left;"><?php echo FLIGHT_NOTES_POP_TITLE?><span><?php echo FLIGHT_NOTES_POP_CONTENT?></span></a></div>
					<?php	}?>
					</div>						
					
					</div>
					<script type="text/javascript">
					<!--
					function my_disable(obj,type,num){
						if(obj.checked == true) {
							jQuery('#flight_info_' + num + ' input[mytype=\'' + type + '\']').attr('readonly',true).css('background-color','#ece9d8');
							jQuery('#flight_info_' + num + ' input[id=\'flight_' + type + '_' + num + '\']').val(jQuery('#flight_' + type + '_' + num).attr('mytext'));
							if (type == 'Before') {
								jQuery('#flight_info_' + num + ' input[class=\'text_time\'][mytype=\'' + type + '\']').val('<?php echo date('m/d/Y',strtotime($order->products[$i]['dateattributes'][0]));?>');
							} else {
								jQuery('#flight_info_' + num + ' input[class=\'text_time\'][mytype=\'' + type + '\']').val('<?php 
								if ((int)$order->products[$i]['is_hotel'] == 0) {
									echo date('m/d/Y',strtotime(tep_get_products_end_date($order->products[$i]['id'],$order->products[$i]['dateattributes'][0])));
								} else {
									$hotel_date_temp = explode('|=|',$order->products[$i]['hotel_extension_info']);
									
									echo date('m/d/Y',strtotime($hotel_date_temp[1]));
								}
								?>');
							}
							jQuery('#flight_info_' + num + " input[name$='time[" + num + "]'][mytype=\'" + type + "\']").val('');
						} else {
							jQuery('#flight_info_' + num + ' input[mytype=\'' + type + '\']').attr('readonly',false).css('background-color','#fff');
							jQuery('#flight_info_' + num + ' input[id=\'flight_' + type + '_' + num + '\']').val('');
							//jQuery('input[class=\'text_time\'][mytype=\'' + type + '\']').val('');
							jQuery('#flight_info_' + num + ' input[class=\'text_time\'][mytype=\'' + type + '\']').val('');
						}
					}
					//-->
					</script>
                    
					<table border="0" width="100%" cellspacing="0" cellpadding="2" id="flight_info_<?php echo $i;?>" class="flight_list" style="display:<?= $flight_info_display ?>">
                    <tr>
                    	<td>&nbsp;</td>
                        <?php 
						// 如果已经填过航班信息 ，并且选择了自行入住 则勾选框默认选中
						$chk1 = $inputclass1 = $chk2 = $inputclass2 = '';
						unset($flight_no[$i]);
						unset($flight_no_departure[$i]);
						/*if ($flight_no[$i] == '自行入住酒店') {
							$chk1 = 'checked=checked';
							$inputclass1 = 'background-color:#ece9d8';
						}
						
						if ($flight_no_departure[$i] == '自行离团') {
							$chk2 = 'checked=checked';
							$inputclass2 = 'background-color:#ece9d8';
						}*/
						?>
                    	<td><?php echo tep_draw_checkbox_field('a'.$i,'',false,'id="a' . $i . '"' . $chk1 . ' style="width:auto;height:auto;" onclick="my_disable(this,\'Before\',\'' . $i . '\')"');?><?php echo db_to_html('参团日自行入住酒店')?></td>
                        <td>&nbsp;</td>
                        <td><?php echo tep_draw_checkbox_field('b'.$i,'',false,$chk2 . ' id="b' . $i . '" style="width:auto;height:auto;" onclick="my_disable(this,\'After\',\'' . $i . '\')"');?><?php echo db_to_html('行程结束自行离团')?></td>
                    </tr>
                    <tr style="color:#9B9B9B">
                    <td colspan="2" align="center"><?php echo db_to_html('若您选择自行入住酒店，请点勾选上面复选框。');?></td>
                    <td colspan="2" align="center"><?php echo db_to_html('若无离团航班，默认为自行离团。');?></td>
                    </tr>
					  <tr>
						<td align="right"><?php echo TEXT_ARRIVAL_DATE . db_to_html("：");?></td>
						<td><?php 
						//arrival_date
						echo tep_draw_input_field('arrival_date'.$i, '', 'mytype="Before" style="width:110px; ime-mode:disabled;' . $inputclass1 . '" class="text_time" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ','text',false); ?>
						<?php
						/*
                        <script type="text/javascript">dateAvailable<?php echo $i; ?>.writeControl(); dateAvailable<?php echo $i; ?>.dateFormat="MM/dd/yyyy";</script>
						*/
                        ?>
                        </td>
						<td align="right"><?php echo TEXT_DEPARTURE_DATE . db_to_html("："); ?></td>
						<td>
                        <?php echo tep_draw_input_field('departure_date'.$i, '', 'mytype="After" style="width:110px; ime-mode:disabled; ' . $inputclass2 . '" class="text_time" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ','text',false); ?>
						<?php
						/*
						<script type="text/javascript">dateAvailable1<?php echo $i; ?>.writeControl(); dateAvailable1<?php echo $i; ?>.dateFormat="MM/dd/yyyy";</script></td>
						*/?>
					  </tr>
                      <tr>
						<td  align="right"><?php echo TEXT_ARRIVAL_AIRLINE_NAME . db_to_html("：");?></td>
						<td><?php echo tep_draw_input_field('airline_name['.$i.']', $airline_name[$i], 'mytype="Before" style="' . $inputclass1 . '"'); ?></td>
						<td align="right"><?php echo TEXT_DEPARTURE_AIRLINE_NAME . db_to_html("："); ?></td>
						<td><?php echo tep_draw_input_field('airline_name_departure['.$i.']', $airline_name_departure[$i], 'mytype="After"  style="' . $inputclass2 . '"'); ?></td>
					  </tr>
					  <tr>
						<td height="25" align="right" width="20%"><?php echo TEXT_ARRIVAL_FLIGHT_NUMBER . db_to_html("："); ?></td>
						<td width="30%"><?php echo tep_draw_input_field('flight_no['.$i.']', $flight_no[$i], 'mytype="Before" mytext="' . db_to_html('自行入住酒店') . '" id="flight_Before_' . $i . '" style="' . $inputclass1 . '"'); ?></td>
						<td width="20%" align="right"><?php echo TEXT_DEPARTURE_FLIGHT_NUMBER . db_to_html("："); ?></td>
						<td width="30%"><?php echo tep_draw_input_field('flight_no_departure['.$i.']', $flight_no_departure[$i], 'mytype="After" mytext="' . db_to_html('自行离团') . '" id="flight_After_' . $i . '" style="' . $inputclass2 . '"'); ?></td>
					  </tr>
					  
					  <tr>
						<td align="right"><?php echo TEXT_ARRIVAL_AIRPORT_NAME . db_to_html("："); ?></td>
						<td><?php echo tep_draw_input_field('airport_name['.$i.']', $airport_name[$i], 'mytype="Before"  style="' . $inputclass1 . '"'); ?></td>
						<td align="right"><?php echo TEXT_DEPARTURE_AIRPORT_NAME . db_to_html("："); ?></td>
						<td><?php echo tep_draw_input_field('airport_name_departure['.$i.']', $airport_name_departure[$i], 'mytype="After"  style="' . $inputclass2 . '"'); ?></td>
					  </tr>
					  
					  <tr>
						<td align="right" valign="top"><?php echo TEXT_ARRIVAL_TIME . db_to_html("：");?></td>
						<td><?php
						//, 'onBlur="return IsValidTimeMilitry(this.value)";'
						 echo tep_draw_input_field('arrival_time['.$i.']', $arrival_time[$i],'mytype="Before"  style="' . $inputclass1 . '"'); ?><br />(HH:MM) e.g. 15:30 pm</td>
						<td align="right" valign="top"><?php echo TEXT_DEPARTURE_TIME . db_to_html("：");?></td>
						<td><?php echo tep_draw_input_field('departure_time['.$i.']', $departure_time[$i],'mytype="After"  style="' . $inputclass2 . '"'); ?><br />(HH:MM) e.g. 09:30 am</td>
					  </tr>
					</table>
					<?php
						//echo '</td></tr> <tr> <td  class="main"><hr style="color:#108BCE;" size="1" /> </td></tr></table> </td></tr>';
						echo '</td></tr></table> </td></tr>';
						}
					?>			    
            </table>
			 
			 </td></tr>
			 <tr><td style="padding-left:10px; padding-right:10px; padding-bottom:10px;">
			 <table border="0" cellspacing="0" cellpadding="2">
						<tr><td colspan="2" class="main"></td></tr>
						<tr><td class="main" width="110" ><b><?php echo str_replace(':','&nbsp;&nbsp;',TEXT_EMERGENCY_CONTACT_NUM);?></b> </td>
						  <td class="main">
						  <?php
						  echo
						  tep_draw_pull_down_menu('tel_code',db_to_html(tep_get_countries_tel_code_array()),'','id="tel_code" style="width:105px;" onChange="set_tel_code(this.value, jQuery(\'#customers_cellphone\'));" ').
						  tep_draw_input_field('customers_cellphone', tep_customers_cellphone($customer_id), 'id="customers_cellphone" class="required validate-phone" onkeydown="auto_set_tel_code_value();" onkeyup="auto_set_tel_code_value();" onBlur="set_tel_code(jQuery(\'#tel_code\').val(), this); auto_set_tel_code_value();" style="ime-mode: disabled;" title="'.db_to_html('请输入当日游客的手机号码！如：+86 138xxxxxxxx').'" '); ?>
						  <span class="inputRequirement">*</span>
						  <script type="text/javascript">
							//根据电码号码来设置tel_code的值
							function auto_set_tel_code_value(){
								var _val = get_tel_code(jQuery("#customers_cellphone").val());
								if(_val!=''){
									jQuery("#tel_code").val(_val);
								}
							}
							auto_set_tel_code_value();
							//根据tel_code的值自动给电码号码加上国家区号
							set_tel_code(jQuery("#tel_code").val(), jQuery("#customers_cellphone"));
						  </script>
						  </td>
						</tr>
						<tr><td class="main" align="left">&nbsp;</td><td align="left"><span style="color:#6F6F6F"><?php echo TEXT_EMERGENCY_CASE_AVALILABLE;?></span></td></tr>
			 </table>	
			</td>
          </tr>
        </table>
		</div>
		</td>
      </tr>	 
	  
	  <tr>
        <td class="separator"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
      </tr>
<tr><td class="infoBoxHeading"><div class="heading_bg"><?php echo TABLE_HEADING_COMMENTS; ?></div></td></tr>
<tr><td><?php 
$defaultMsg = '如您对行程有特殊要求，请务必在此留言，以便我们尽量安排。';

echo tep_draw_textarea_field('comments', 'virtual', '60', '5' ,db_to_html($defaultMsg),'style="width: 901px;border:1px solid #A2CBF4;" class="textarea_bg" onfocus="if(this.value == \'' . db_to_html($defaultMsg) . '\'){this.value=\'\';}"');?></td></tr>
      

    <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="30"><?php echo tep_draw_separator('pixel_trans.gif', '30', '1'); ?></td>
                <td class="main"><a class="btn" href="/shopping_cart.php"><span></span><?php echo db_to_html('返回上一页')?></a><?php #lwkairem echo '<b>'.TITLE_CONTINUE_CHECKOUT_PROCEDURE . db_to_html('下一步支付') . '</b><br />'. db_to_html('下一步您可以选择您喜欢的支付方式，修改完善您的通讯地址，给我们留言，及兑换积分或使用代金券获取折扣。'); ?></td>
                <td class="main subbtn" align="right"><input type="submit" value="<?php echo db_to_html('继续');?>" /><?php  #lwkaiRem echo tep_template_image_submit('button_continue_checkout.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="20"><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
      </tr>
     
	   <tr>
														<td align="center">
														<!--<table width="0%" border="0" cellspacing="0" cellpadding="0">
														  <tr>
															<td><?php echo tep_template_image_button('tours-info.gif','','')?></td>
															<td><img alt="" src="image/jiantou1.gif" style="margin-left:60px; margin-right:60px;" /></td>
															<td><?php echo tep_template_image_button('payment_info2.gif','','')?></td>
															<td><img alt="" src="image/jiantou1.gif" style="margin-left:60px; margin-right:60px;" /></td>
															<td><?php echo tep_template_image_button('check-info.gif','','')?></td>
															
														  </tr>
														</table>-->

		  </td>
	    </tr>
    </table>
	<input name="action_page_name" type="hidden" value="checkout_info">
	<?php echo '</form>';?>
		</td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  
							  
							  
							
							</table><!-- content main body end -->
							<script language="javascript" type="text/javascript"><!--
							
function formCallback(result, form) {
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
	/*if (result == true) {
		var ccc = check_flight();
		if(ccc == true){
			document.getElementById(form.id).submit();
		}
	}*/
	
	
}

var stop_use_var_submit_disabled = true;	//如果设置此变量则Validation内容的判断表单提交状态的变量submit_disabled将失效
var valid = new Validation('checkout_payment', {immediate : true,useTitles:true, onFormValidate : formCallback});

function CVVPopUpWindow(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=233,screenX=150,screenY=150,top=150,left=150')
}
//--></script>


<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">

var email_array = new Array();
var firstname_array = new Array();
<?php 

/*$cus_sql = tep_db_query('SELECT customers_email_address FROM `customers` WHERE customers_email_address!="" Order By customers_email_address ');
$i=0;
while($cus_rows = tep_db_fetch_array($cus_sql)){
	echo 'email_array['.$i.']="'.$cus_rows['customers_email_address'].'"; ';
	$i++;
}*/
?>

<?php
//以下js函数的原理是，如果f_id的长度大于2个字符就开始搜索用户，如果数组product_array小于1就从数据库中取数据
?>
function auto_list_customers_address(f_id,layer_id){
	var numtag = f_id.replace('GuestEmail','');
	var PayerMeRaiod = document.getElementById("PayerMe" + numtag + "_A");
	if(PayerMeRaiod!=null && PayerMeRaiod.checked==true ){
		return false;
	}
	var ajax_query = false;
	var f= document.getElementById(f_id);
	var l= document.getElementById(layer_id);
	if(email_array.length>0){
		if(f.value.length>=1){
			var p=new RegExp("^" + f.value, 'i'); 
			var htmlval="";
			var j=0;
			for(i=0; i<email_array.length; i++){
				if(email_array[i].search(p)!= -1 && j<20 && f.value.length > 2 ){	//输入字符多于2个时才显示
					end_val = email_array[i].replace(p,'<span style="color:#009933;font-weight: bold;">'+f.value+'</span>');
					end_val += '  '+ firstname_array[i];
					htmlval+='<div class ="meun_sel" ';
					htmlval+=' onMouseOut="this.className=&quot;meun_sel&quot; "';
					htmlval+=' onMouseMove="this.className=&quot; meun_sel1&quot; ;select_mail_input_a(&quot;'+f_id+'&quot;, &quot;'+layer_id+'&quot;,&quot;'+ email_array[i] +'&quot;);"';
					htmlval+=' onclick="select_mail_input(&quot;'+f_id+'&quot;, &quot;'+layer_id+'&quot;,&quot;'+ email_array[i] +'&quot;)" >'+end_val+'</div>';
					j++;
				}
			}
			if(htmlval.length >1){
				l.innerHTML=htmlval;
				l.style.display="";
				//alert(htmlval);
			}else{l.style.display="none";}
		}else{l.style.display="none";}
		
	}else{
		ajax_query = true;
	}
	
	if(ajax_query == true){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_customers_list_ajax.php','key=', 'SSL')) ?>")+ f.value;
		
		ajax.open("GET", url, true); 
		ajax.send(null);
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				eval(ajax.responseText);
			}
		}
		//alert('net get ajax ');
	}
}

function select_mail_input(f_id, layer_id, set_val){
	var f= document.getElementById(f_id);
	var l= document.getElementById(layer_id);
	f.value = set_val;
	l.style.display="none";
}
function select_mail_input_a(f_id, layer_id, set_val){
	var f= document.getElementById(f_id);
	var l= document.getElementById(layer_id);
	f.value = set_val;
	//l.style.display="none";
}

<?php //检查结伴同游客户中是否有重复的账号?>
function checkSameAccount(){
	var inputs = jQuery("#checkout_payment input[name^='GuestEmail']");
	var tmpArray = Array();
	jQuery(inputs).each(function (){
			if(tmpArray[jQuery(this).val()]==jQuery(this).val()){
				alert("<?= db_to_html("不能有重复参团人注册邮箱！");?>"+jQuery(this).val());
				jQuery(this).val("");
				return false;
			}else{
				if(jQuery(this).val()!=""){
					tmpArray[jQuery(this).val()] = jQuery(this).val();
				}
			}
		}
	);
}
</script>

<?php echo tep_get_design_body_footer();?>