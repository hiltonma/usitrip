<script type="text/javascript">
function remove_item_confirmation(index){	
	var fRet;
	fRet = confirm('<?php echo TEXT_SHOPPING_CART_REMOVE_RESERVATION_LIST_CONFIRM;?>');										
	if (fRet == false) { return false;}
	for ( i= 0 ; i < window.document.cart_quantity.elements.length ; i ++){
		if(window.document.cart_quantity.elements[i].id == "rem_"+index)
		{
			window.document.cart_quantity.elements[i].checked = true;
			//window.document.cart_quantity.edit_order.submit(); 
			return true;	
		}
	}
}
function reload_ifr(id){
	jQuery("#"+id+" iframe").each(function(){var src = jQuery(this).attr("src");jQuery(this).removeAttr('src') ;jQuery(this).attr('src',src);});
}
</script>
<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>
<?php echo tep_get_design_body_header(HEADING_TITLE); ?>
<?php
//取得所有属于邮轮团的产品属性ID
$cruisesOptionIds = getAllCruisesOptionIds();
?>
<!-- content main body start -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>	<td height="5"></td></tr>

	<tr><td>
			<div id="error_msn" style=" display:<?php echo 'none';?>;border: 1px solid #79AC21; padding: 5px; background: #F7FFE1; text-align: left; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(230, 116, 2);">
				<img src="image/p5.gif" border="0" align="absmiddle" style="padding-right: 5px" /><?php echo db_to_html('请完善您的出发日期等信息，确认无误之后去结算！')?>
			</div>
	</td></tr>
	
	<tr><td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr><td height="25" colspan="2">
                <?php ob_start() ?>
                <div class="cart_progress">
		<div class="cart_left">
    		<em class="cart_icon"></em><h3>我的购物车</h3>
    	</div>
    	<div class="cart_right">
    		<ul>
        		<li class="first"><span></span>1.选择产品</li>
            	<li class="cur"><span></span>2.查看购物车</li>
            	<li><span class="cur"></span>3.确认行程信息</li>
            	<li><span></span>4.完成订购</li>
            	<li class="last"><span></span></li>
         	</ul>
    	</div>
	</div><?php echo db_to_html(ob_get_clean());?>
    <!--<img src="image/sp_ioc.gif" alt="" title="">--></td><!--<td>&nbsp;<strong style="font-size: 14px"><?php echo db_to_html('购物车列表');//echo TEXT_HEADING_RED_REPEAT_CUSTOMERS_NOTES; ?></strong></td>--></tr>
			</table>
	</td></tr>

	<tr><td class="main">
		<table border="0" width="100%" cellspacing="0" 	cellpadding="<?php echo CELLPADDING_SUB;?>">
		<?php if (MAIN_TABLE_BORDER == 'yes'){table_image_border_top(false, false, $header_text);}?>
		<?php  if ($cart->count_contents() > 0) {?>
				<tr>
					<td>
						<div class="infoBox_outer">
							<?php
							    $info_box_contents = array();
							    $info_box_contents[0][] = array('params' => 'class="productListing-heading"','text' => '&nbsp;'.TABLE_HEADING_PRODUCTS);
							    $info_box_contents[0][] = array('align' => 'center','params' => 'class="productListing-heading"','text' => TABLE_HEADING_EDIT);
								//TABLE_HEADING_QUANTITY -- amit commented due to hide qty
								$info_box_contents[0][] = array('align' => 'center','params' => 'class="productListing-heading"','text' => '&nbsp;'.TABLE_HEADING_REMOVE);
								/*$info_box_contents[0][] = array('align' => 'right', 'params' => 'class="productListing-heading" nowrap="nowrap"','text' => TABLE_HEADING_TOTAL.'&nbsp;');*/
								$any_out_of_stock = 0;
								$cart->restore_contents();
								$products = $cart->get_products();	
								//print_r($products);						
								for ($i=0, $n=sizeof($products); $i<$n; $i++) 
								{
									// Push all attributes information in an array
							    	if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])){
							      		while (list($option, $value) = each($products[$i]['attributes'])){
											$products[$i][$option]['options_values_price'] = $cart->attributes_price_display($products[$i]['id'],$option,$value);
										  	//print_r($products);
										  
							      			if($products[$i][$option]['options_values_price']!=0 || ($products[$i][$option]['options_values_price']==0 && !in_array($option, (array)$cruisesOptionIds))){	
											//不显示产品属性价格为0的邮轮团选项
												echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
							      				$attributes = tep_db_query("SELECT popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
							                    							FROM " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
							                                      			WHERE pa.products_id = '" . $products[$i]['id'] . "'
							                                       			and pa.options_id = '" . $option . "'   and pa.options_id = popt.products_options_id
							                                       			and pa.options_values_id = '" . $value . "'	 and pa.options_values_id = poval.products_options_values_id
							                                       			and popt.language_id = '" . $languages_id . "'	 and poval.language_id = '" . $languages_id . "'");
								          		$attributes_values = tep_db_fetch_array($attributes);
								
								          		$products[$i][$option]['products_options_name'] = db_to_html($attributes_values['products_options_name']);
								          		$products[$i][$option]['options_values_id'] = $value;
								          		$products[$i][$option]['products_options_values_name'] = db_to_html($attributes_values['products_options_values_name']);
								          		//amit change it take price form cart
								          		// $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price']; 
								          		//echo $cart->attributes_price_display($products[$i]['id'],$option,$value).'<br />';  
								          
								          		//$products[$i][$option]['options_values_price'] = $cart->attributes_price($products[$i]['id']);
								          		$products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
											}
        								}//endwhile
      								}//endif
    							}//endfor
	
								$chk_out_pro = '';
					
    							$cart_have_jiebantongyong = false; //判断购物车是否有结伴同游团
					
								for ($i=0, $n=sizeof($products); $i<$n; $i++){						
									//$iframe_cart_edit_update = $i;
									$iframe_cart_edit_update = (int)$products[$i]['id'] . "_" . $i;
									$display_depa_dt_error = '';
									//amit added to check validation start{
									$tmp_dp_date = $products[$i]['dateattributes'][0];
									//hotel-extension {
									$is_start_date_required=is_tour_start_date_required((int)$products[$i]['id']);
									if($is_start_date_required==true){
										$tmp_dp_date = $products[$i]['dateattributes'][0];	//$currect_sys_date = date('Y-m-d');		
										if((int)$products[$i]['is_hotel']==1){
											$currect_sys_date = date('Y-m-d');		
											if(preg_match("/,".$products[$i]['agency_id'].",/i", ",".TXT_PROVIDERS_DTE_BTL_IDS.",")) {
												$currect_sys_date = GetWorkingDays(date('Y-m-d'),4);
												$currect_sys_date = date("Y-m-d", strtotime($currect_sys_date) + (24*60*60) );
											}
										}else{
											$currect_sys_date = date('Y-m-d', strtotime('+2 days'));		
										}												
										if(@tep_get_compareDates($tmp_dp_date,$currect_sys_date) == "invalid" && $is_gift_certificate_tour==false){
											$display_depa_dt_error = 'true';	
										}
									}
									//}
									//$currect_sys_date = date('Y-m-d', strtotime('+2 days'));
									if($products[$i]['is_transfer']!='1'){		
										if(@tep_get_compareDates($tmp_dp_date,$currect_sys_date) == "invalid" ){
											//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'].'&error_departure_date=true'));
											//exit();
											$display_depa_dt_error = 'true';
											$products[$i]['dateattributes'][0] ="";	 //howard added Empty < today's date 
										}
									}else{
										//接送服务检查 抵达时间是否有效
										$param = tep_transfer_decode_info($products[$i]['transfer_info']);							
										if($param['FlightArrivalTime1']!=""){
											if(strtotime($param['FlightArrivalTime1']) < time()){
												$display_depa_dt_error = 'true';
											}
										}
										if($param['FlightArrivalTime2']!=""){
											if(strtotime($param['FlightArrivalTime2']) < time()){
												$display_depa_dt_error = 'true';
											}
										}
									}
									//amit added to check validation end}
					
									//amit added to check needed update price {
									$update_needed_default = 'no';	
									if (tep_session_is_registered('customer_id') && $_SESSION['updated_prod_id_once'.(int)$products[$i]['id']] != 'updated'){
										$get_added_date_cust_basket_sql = "select customers_basket_date_added from ".TABLE_CUSTOMERS_BASKET." where customers_id ='".$customer_id."' and products_id='".$products[$i]['id']."'";
										$get_added_date_cust_basket_query = tep_db_query($get_added_date_cust_basket_sql);
									
										if(tep_db_num_rows($get_added_date_cust_basket_query) > 0)
										{		
											$get_added_date_cust_basket_row = tep_db_fetch_array($get_added_date_cust_basket_query);
											$final_cart_comapredate = $get_added_date_cust_basket_row['customers_basket_date_added'];
											//$final_cart_comapredate = substr($cust_bkt_date_added, 0, 4).'-'.substr($cust_bkt_date_added, 4, 2).'-'.substr($cust_bkt_date_added, 6, 2);
									
											//get product last update date start		
											$get_products_last_modified_sql = "select products_last_modified from ".TABLE_PRODUCTS." where  products_id='".(int)$products[$i]['id']."'";
											$get_products_last_modified_query = tep_db_query($get_products_last_modified_sql);
											$get_products_last_modified_row = tep_db_fetch_array($get_products_last_modified_query);
											$final_product_modify_comapredate = str_replace('-','',substr($get_products_last_modified_row['products_last_modified'],0,10));
										
											if($final_cart_comapredate <= $final_product_modify_comapredate){			
												$update_needed_default = 'yes';	
												$_SESSION['updated_prod_id_once'.(int)$products[$i]['id']]= 'updated';
											}	
										}
									}//amit added to check needed update price}
									//amit added to force submit when changed currency start{
									if(isset($HTTP_GET_VARS['currency']) && $HTTP_GET_VARS['currency'] !='' ){
										$update_needed_default = 'yes';	
									}	
									//amit added to force submit when changed currency end}
					
									if (($i/2) == floor($i/2)) {
				        				$info_box_contents[] = array('params' => 'class="productListing-even"');
				    				} else {
				        				$info_box_contents[] = array('params' => 'class="productListing-odd"');
				    				}
				    
				    				$cur_row = sizeof($info_box_contents) - 1;
				
				      				//howard added travel companion{
					  				$jiebantongyong="";
					  				if($products[$i]['roomattributes'][5]=='1'){
					  					$jiebantongyong = '<br><span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
										$cart_have_jiebantongyong = true;
					  				}//}
									//hotel-extension for hotel extension early/late
									$hotel_ext_addon_text = "";
									if($products[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='1'){
										$hotel_ext_addon_text = '<br><span><b>'.db_to_html("(参团前加订酒店)").'</b></span>';
									}else if($products[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
										$hotel_ext_addon_text = '<br><span><b>'.db_to_html("(参团后加订酒店)").'</b></span>';
									}
					  
					  				$products_obj_html = '<table  border="0" cellspacing="2" cellpadding="2">' ;
					  				if(tep_not_null($products[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID])){
										$extra_query_string = '?hotel_attribute='.$products[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID];
									}else{
										$extra_query_string = '';
									}
					  				$products[$i]['image'] = (stripos($products[$i]['image'],'http://') === false ? 'images/':'') . $products[$i]['image'];
									$products_obj_html.='<tr><td class="productListing-data_new cimg" align="center" valign="top" style="padding:5px;"><a href="' . 
										
										tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .(int)$products[$i]['id'] ).$extra_query_string.'">' . tep_image($products[$i]['image'],db_to_html($products[$i]['name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . 
										'</a></td><td class="productListing-data_new" valign="top"><div id="cart_product_data_'.$iframe_cart_edit_update.
										'"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products[$i]['id']).$extra_query_string.
										'" class="product_name_orange blue"><span style="color:#000">' . $hotel_ext_addon_text . '</span><b>' . 
										db_to_html($products[$i]['name']) . '</b></a>'.$jiebantongyong . '<br/>' . db_to_html('旅游团号：') . db_to_html($products[$i]['model']);
				                    //'  <tr><td class="productListing-data" align="center" valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .(int)$products[$i]['id']) . '">' . tep_image($products[$i]['image'], db_to_html($products[$i]['name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT,'style="margin-right: 12px;"') . '</a></td><td class="productListing-data" valign="top"><div id="cart_product_data_'.(int)$products[$i]['id'].'"><a class="cu dazi" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products[$i]['id']) . '"><b>' .db_to_html($products[$i]['name']) . '</b></a>'.$jiebantongyong;

					  				if($display_depa_dt_error == "true"){	 	
									 	$error = true;
										$error_date = true;
									 }
									if (STOCK_CHECK == 'true') {
								    	$stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
				        				if (tep_not_null($stock_check)) {
				          					$any_out_of_stock = 1;				
				          					$products_obj_html .= $stock_check;
				        				}
				      				}
					  
					  				$departuer_date_tags = tep_get_date_disp($products[$i]['dateattributes'][0]);
					  				if($products[$i]['no_sel_date_for_group_buy']=="1"){
										$departuer_date_tags = date('m/d/Y',strtotime($products[$i]['dateattributes'][0])+86400).TEXT_BEFORE;
					  				}
					  
					  				//hotel-extension {
					 				if(tep_check_product_is_hotel($products[$i]['id'])==1){
										$txt_dept_date = db_to_html('入住日期:');
									}else{
										$txt_dept_date = TEXT_SHOPPING_CART_DEPARTURE_DATE;
									}
					 				if($is_start_date_required==true){
										$products_obj_html .= '<br /><span>'.$txt_dept_date.'<b style="color:#f60; font-size:18px;"> ' . tep_get_date_disp($products[$i]['dateattributes'][0]).tep_draw_hidden_field('finaldateone[]', $products[$i]['dateattributes'][0], 'size="4"').'</b></span>';
										if($products[$i]['dateattributes'][4] != '' && $products[$i]['dateattributes'][4] != 0){
											$products_obj_html .=' '. $products[$i]['dateattributes'][3].' '.tep_draw_hidden_field('prifixone[]', $products[$i]['dateattributes'][3], 'size="4"') .' '. (($products[$i]['dateattributes'][4]!='') ? '$' : '') .$products[$i]['dateattributes'][4].tep_draw_hidden_field('date_priceone[]', $products[$i]['dateattributes'][4], 'size="4"');
										}
										if($products[$i]['is_hotel']==1){
											$hotel_extension_info = explode('|=|', $products[$i]['hotel_extension_info']);
											if($products[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
												$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
											}else{
												$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
											}
											$products_obj_html .= '<span style="margin-left:2em">'.db_to_html('离店日期:').'<b style="color:#ff6600; font-size:18px;"> ' . tep_get_date_disp($hotel_checkout_date) . '</b></span>';
											if(check_date($hotel_checkout_date) && check_date($products[$i]['dateattributes'][0])){
												$products_obj_html .='<br><span>'.db_to_html('总共：'.date1SubDate2($hotel_checkout_date,$products[$i]['dateattributes'][0]).'晚').'</span>';
											}
										}else{	//行程显示结束日期
											$prod_dura_day = tep_db_get_field_value('products_durations', TABLE_PRODUCTS,'products_id="'.$products[$i]['id'].'" AND products_durations_type="0" ');
											$prod_dura_day = max(0, ((int)$prod_dura_day - 1 ));
											$date_str = date_add_day_product($prod_dura_day, 'd', $products[$i]['dateattributes'][0]);
											$weeks_str = en_to_china_weeks(substr($date_str, 10));
											$date_str = substr($date_str, 0, 10) . $weeks_str;
											$products_obj_html .='<br><span>'.db_to_html('结束日期：').$date_str.'</span>';
										}
									}
									// echo ' <pre>';  print_r($products[$i]);  echo ' </pre>';
					
					  
					 				//$products_obj_html .= '<br /><span>'.$txt_dept_date.' <b style="color:#000000; font-size:18px;">' .$departuer_date_tags. tep_draw_hidden_field('finaldateone[]', $products[$i]['dateattributes'][0], 'size="4"') . '</b></span>';
				     
									if($error_date == true){
										//$products_obj_html .= ' <span><a class="a2" onclick="ShowContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');HideContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');  return true;"  href="javascript:void(0);">'.TEXT_SELECT_VALID_DEPARTURE_DATE.'</a></span>';
										$products_obj_html .= ' <span><a class="a2" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products[$i]['id']).$extra_query_string . '#edit_box">' . TEXT_SELECT_VALID_DEPARTURE_DATE . '</a></span>';
									}	
									 
									$error_date = false;
									 
									if($products[$i]['is_transfer'] == '1'){								
										$msg = tep_transfer_validate(intval($products[$i]['id']),tep_transfer_decode_info($products[$i]['transfer_info']));
										if($msg!=''){
											$products_obj_html .= '<span><a class="a2" onclick="ShowContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');HideContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');  return true;"  href="javascript:void(0);">'.db_to_html($msg).'</a></span>';
										}
									}	
									/*
									if($products[$i]['dateattributes'][4] != '' && $products[$i]['dateattributes'][4] != 0)
									 {
									  $products_name .= '<span class="main_new">'. $products[$i]['dateattributes'][3].'</span><span>'.tep_draw_hidden_field('prifixone[]', $products[$i]['dateattributes'][3], 'size="4"') .' '. (($products[$i]['dateattributes'][4]!='') ? '$' : '') .$products[$i]['dateattributes'][4].tep_draw_hidden_field('date_priceone[]', $products[$i]['dateattributes'][4], 'size="4"') . '</span>';
									}*/
				
				
									$text_shopping_cart_pickp_location = TEXT_SHOPPING_CART_PICKP_LOCATION;
									//if((int)is_show((int)$products[$i]['id'])){ // 原来的演出时间
									if ($products[$i]['products_type'] == 7){
										$text_shopping_cart_pickp_location = db_to_html('演出时间/地点：');//PERFORMANCE_TIME;
									}
									//howard added check departurelocation 接送地址检查{
									$display_departure_region_combo = "";
									$query_region = "select * from products_departure where departure_region<>'' and products_id = ".(int)$products[$i]['id']." group by departure_region";
									$row_region = tep_db_query($query_region);
									$totlaregioncount = tep_db_num_rows($row_region);
									
									$product_info_query = tep_db_query("select agency_id, display_pickup_hotels from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products[$i]['id'] . "' limit 1");
									$product_info = tep_db_fetch_array($product_info_query);
								
									if((int)$totlaregioncount > 1 || ($product_info['agency_id'] == 12 && $product_info['display_pickup_hotels'] == '1')  ){					
										$display_departure_region_combo = "true";					
									}else if((int)$totlaregioncount == 1){
										$display_departure_region_combo = "true";
									}
									
									if(!tep_not_null($products[$i]['dateattributes'][2]) && $display_departure_region_combo=='true'){
										$error = true;
										$products_obj_html .=   '<br /><span>'.$text_shopping_cart_pickp_location.'</span>';
										$products_obj_html .= ' <span><a class="a2" onclick="ShowContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');HideContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');  return true;"  href="javascript:void(0);">'.ERROR_SEL_SHUTTLE.'</a></span>';	
									}
									//howard added check departurelocation end }
									 
									if($products[$i]['dateattributes'][1] != '')	  
										if (!tep_session_is_registered('customer_id')) {
											$products_obj_html .=  ' <br /><span>'.$text_shopping_cart_pickp_location.' ' . $products[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $products[$i]['dateattributes'][1], 'size="4"')  . ' ' . $products[$i]['dateattributes'][2].''.tep_draw_hidden_field('depart_locationone[]', $products[$i]['dateattributes'][2], 'size="4"') . '</span>';
										}else{
											$products_obj_html .=  ' <br /><span>'.$text_shopping_cart_pickp_location.' ' . $products[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $products[$i]['dateattributes'][1], 'size="4"')  . ' ' . db_to_html($products[$i]['dateattributes'][2]).''.tep_draw_hidden_field('depart_locationone[]', db_to_html($products[$i]['dateattributes'][2]), 'size="4"') . '</span>';
										}
									//howard added check minnumber 最少订团人数检查{
									if($products[$i]['is_transfer'] != '1'){
										$total_no_guest_tour = $products[$i]['roomattributes'][2];
										$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.(int)$products[$i]['id'].'" limit 1');
										$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
										if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
											$error = true;
											$products_obj_html .= '<br />'.db_to_html('出团人数:');
											$products_obj_html .= ' <span><a class="a2" onclick="ShowContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');HideContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');  return true;"  href="javascript:void(0);">'.TEXT_PRODUCTS_MIN_GUEST.max(1,(int)$check_min_guest_row['min_num_guest']).'</a></span>';
									
										}
									}
									//howard added check minnumber end}
									if($products[$i]['roomattributes'][1] != '')  {
										/*
										if( tep_not_null($products[$i]['roomattributes'][4]) ){
											//howard added clear zero price line(清除价格为0那那一整行文字)
											$roomInfoString = $cart->re_get_room_info_to_gb2312($products[$i]['roomattributes'][3]);
											//$roomInfoString = preg_replace('@(<[^<]*br[^<]*>[^<]+0.00)@','',$products[$i]['roomattributes'][1]); //此值在shopping_cart.php中已经被转成了gb2312格式
											//$roomInfoString = format_out_roomattributes_1($roomInfoString, (int)$products[$i]['roomattributes'][3]);
											
											
											//$products_obj_html .=  iconv($products[$i]['roomattributes'][4],CHARSET.'//IGNORE',' <br /><span>' . $roomInfoString. tep_draw_hidden_field('roominfo[]', $products[$i]['roomattributes'][1], 'size="4"').tep_draw_hidden_field('travel_comp[]', $products[$i]['roomattributes'][5]) . tep_draw_hidden_field('roomprice[]', $products[$i]['roomattributes'][0]) . tep_draw_hidden_field('guestcount[]', $products[$i]['roomattributes'][2]) . '</span>');
											$products_obj_html .=  db_to_html(' <br /><span>' . $roomInfoString. tep_draw_hidden_field('roominfo[]', $products[$i]['roomattributes'][1], 'size="4"').tep_draw_hidden_field('travel_comp[]', $products[$i]['roomattributes'][5]) . tep_draw_hidden_field('roomprice[]', $products[$i]['roomattributes'][0]) . tep_draw_hidden_field('guestcount[]', $products[$i]['roomattributes'][2]) . '</span>');
										}else{
											//howard added clear zero price line(清除价格为0那那一整行文字)
											$roomInfoString = $cart->re_get_room_info_to_gb2312($products[$i]['roomattributes'][3]);
											//$roomInfoString = preg_replace('@(<[^<]*br[^<]*>[^<]+0.00)@','',$products[$i]['roomattributes'][1]); //此值在shopping_cart.php中已经被转成了gb2312格式
											//$roomInfoString = format_out_roomattributes_1($roomInfoString, (int)$products[$i]['roomattributes'][3]);
											
											$products_obj_html .=  db_to_html(' <br /><span>' . $roomInfoString. tep_draw_hidden_field('roominfo[]', $products[$i]['roomattributes'][1], 'size="4"').tep_draw_hidden_field('travel_comp[]', $products[$i]['roomattributes'][5]) . tep_draw_hidden_field('roomprice[]', $products[$i]['roomattributes'][0]) . tep_draw_hidden_field('guestcount[]', $products[$i]['roomattributes'][2]) . '</span>');
										}*/
										
										$roomInfoString = $cart->re_get_room_info_to_gb2312($products[$i]['roomattributes'][3]);
										$products_obj_html .=  db_to_html(' <br /><span>' . $roomInfoString. tep_draw_hidden_field('roominfo[]', $products[$i]['roomattributes'][1], 'size="4"').tep_draw_hidden_field('travel_comp[]', $products[$i]['roomattributes'][5]) . tep_draw_hidden_field('roomprice[]', $products[$i]['roomattributes'][0]) . tep_draw_hidden_field('guestcount[]', $products[$i]['roomattributes'][2]) . '</span>');
										
									}
									//New Group Buy 新团购优惠 {
									if($products[$i]['is_new_group_buy']>0 ){
										$products_price = tep_db_query("select p.products_single, p.products_single_pu, p.products_double, p.products_triple, p.products_quadr, p.products_kids from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products[$i]['id'] . "' ");
										$products_result = tep_db_fetch_array($products_price);
										$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products[$i]['id']);
										if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
											$products_result['products_single'] = tep_get_tour_price_in_usd($products_result['products_single'], $tour_agency_opr_currency);
											$products_result['products_single_pu'] = tep_get_tour_price_in_usd($products_result['products_single_pu'], $tour_agency_opr_currency);
											$products_result['products_double'] = tep_get_tour_price_in_usd($products_result['products_double'], $tour_agency_opr_currency);
											$products_result['products_triple'] = tep_get_tour_price_in_usd($products_result['products_triple'], $tour_agency_opr_currency);
											$products_result['products_quadr'] = tep_get_tour_price_in_usd($products_result['products_quadr'], $tour_agency_opr_currency);
											$products_result['products_kids'] = tep_get_tour_price_in_usd($products_result['products_kids'], $tour_agency_opr_currency);
										}
										
										$a_price[1] = $products_result['products_single']; //single or single pair up
										if((int)$products[$i]['roomattributes'][6] && (int)$products_result['products_single_pu']){
											$a_price[1] = $products_result['products_single_pu'];
										}
										$a_price[2] = $products_result['products_double']; //double
										$a_price[3] = $products_result['products_triple']; //triple
										$a_price[4] = $products_result['products_quadr']; //quadr
										$e = $products_result['products_kids']; //child Kid
										
										$oldPrice = 0;
										$g_array = explode('###',$products[$i]['roomattributes'][3]);
										if($g_array[0]>0){ // room
											for($jj=1, $nn = sizeof($g_array); $jj<$nn; $jj++){
												if(strlen($g_array[$jj])>2){
													$n_array = explode('!!', $g_array[$jj]); //$n_array[0]大人 $n_array[1]小孩
													if($n_array[1]==0){
														$oldPrice += $a_price[$n_array[0]] * $n_array[0];
													}else{
														if($n_array[0] == '1' && $n_array[1] == '1') {
															$t_price[0] = 2*$a_price[2];
															$t_price[1] = $a_price[1]+$e;
															$oldPrice += min($t_price[0],$t_price[1]);
														}else if($n_array[0] == '1' && $n_array[1] == '2'){										
															$t_price[0] = (2*$a_price[2]) + $e;
															$t_price[1] = 3*$a_price[3];
															$oldPrice += min($t_price[0],$t_price[1]);
														}else if($n_array[0] == '1' && $n_array[1] == '3'){
															$t_price[0] = (2*$a_price[2]) + 2*$e;
															$t_price[1] = 3*$a_price[3]+$e;
															$t_price[2] = 4*$a_price[4];					
															$oldPrice += min($t_price[0],$t_price[1],$t_price[2]);
														}else if($n_array[0] == '2' && $n_array[1] == '1'){
															$t_price[0] = (2*$a_price[2]) + $e;
															$t_price[1] = 3*$a_price[3];
															$oldPrice += min($t_price[0],$t_price[1]);										
														}else if($n_array[0] == '2' && $n_array[1] == '2'){
															$t_price[0] = (2*$a_price[2]) + 2*$e;
															$t_price[1] = 3*$a_price[3]+$e;
															$t_price[2] = 4*$a_price[4];
															$oldPrice += min($t_price[0],$t_price[1],$t_price[2]);										
														}else if($n_array[0] == '3' && $n_array[1] == '1'){
															$t_price[0] = 3*$a_price[3]+$e;
															$t_price[1] = 4*$a_price[4];
															$oldPrice += min($t_price[0],$t_price[1]);		
														}
													}
												}
											}
											$TITLE_NEW_GROUP_BUY_OLD_PRICE = TITLE_NEW_GROUP_BUY_OLD_PRICE;
										}else{ // no room
											$n_array = explode('!!', $g_array[1]); //$n_array[0]大人 $n_array[1]小孩
											$oldPrice += $n_array[0]*$a_price[1] + $n_array[1]*$e;
											$TITLE_NEW_GROUP_BUY_OLD_PRICE = TITLE_NEW_GROUP_BUY_OLD_PRICE_NOT_ROOM;
										}
										$SaveNum = $oldPrice - $products[$i]['roomattributes'][0];
										if($SaveNum>0){
											$products_obj_html .= '<br /><span style="color:#F7860F">'.$TITLE_NEW_GROUP_BUY_OLD_PRICE.$currencies->display_price($oldPrice,0,$products[$i]['quantity']).' '.TITLE_NEW_GROUP_BUY.' -'.$currencies->display_price($SaveNum,0,$products[$i]['quantity']).'</span>';
										}
					  				}
					  				//New Group Buy 新团购优惠 }
					  				//transfer-service {
									if($products[$i]['is_transfer'] == '1'){
					  					$transfer_param = tep_transfer_decode_info($products[$i]['transfer_info']);					  
					 					$products_obj_html .=  "<div style=\"padding:0 5px 0 5px;\">".db_to_html(tep_transfer_display_route($transfer_param))."<br/></div>";
									}//}
					
				
									if($products[$i]['dateattributes'][4] != '' && $products[$i]['dateattributes'][4] != 0){
										$products_obj_html .= '<br><span>'.TEXT_SHOPPING_CART_DEPARTURE_DATE_PRICE_FLUCTUATIONS . $products[$i]['dateattributes'][3].tep_draw_hidden_field('prifixone[]', $products[$i]['dateattributes'][3], 'size="4"') .' '. (($products[$i]['dateattributes'][4]!='') ? '' : '') .$currencies->format($products[$i]['dateattributes'][4],true,$currency).tep_draw_hidden_field('date_priceone[]', $products[$i]['dateattributes'][4], 'size="4"') . '</span>';
									}
				
				 					if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {		
										//hotel-extension {
										// if(tep_check_product_is_hotel($products[$i]['id'])==0){
										reset($products[$i]['attributes']);
										//}
										//}reset($products[$i]['attributes']);
										while (list($option, $value) = each($products[$i]['attributes'])) {
											if(trim($products[$i][$option]['products_options_name']) != ''){
												if($products[$i][$option]['options_values_price'] != 0 ){
													$products_obj_html .=  '<br /><span>' . $products[$i][$option]['products_options_name'] . ': <b>' . $products[$i][$option]['products_options_values_name'] . '</b>:</span><span> ' . $products[$i][$option]['price_prefix'] . ' ' . $currencies->display_price($products[$i][$option]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1) . '</span>';
												}else{
													if(trim($products[$i][$option]['products_options_name'])!=''){
														$products_obj_html .= '<br /><span>' . $products[$i][$option]['products_options_name'] . ': ' . $products[$i][$option]['products_options_values_name'] . '</span>';
													}
												}
											}
										}
									}
									if(tep_check_priority_mail_is_active($products[$i]['id']) == 1){
										$priority_mail_ticket_needed_date = tep_get_cart_get_extra_field_value('priority_mail_ticket_needed_date', $products[$i]['extra_values']);
										if(tep_not_null($priority_mail_ticket_needed_date)){
											$products_obj_html .=  '<br /><span>'.TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE.':  ' . tep_get_date_disp($priority_mail_ticket_needed_date) . '</span>';
										}
										$priority_mail_delivery_address = tep_get_cart_get_extra_field_value('priority_mail_delivery_address', $products[$i]['extra_values']);
										if(tep_not_null($priority_mail_delivery_address)){
											if (tep_session_is_registered('customer_id')){
												$priority_mail_delivery_address = db_to_html($priority_mail_delivery_address);
											}
											$products_obj_html .=  '<br /><span>'.TXT_PRIORITY_MAIL_DELIVERY_ADDRESS.': ' . $priority_mail_delivery_address . '</span>';
										}
										$priority_mail_recipient_name = tep_get_cart_get_extra_field_value('priority_mail_recipient_name', $products[$i]['extra_values']);
										if(tep_not_null($priority_mail_recipient_name)){
											if (tep_session_is_registered('customer_id')){
												$priority_mail_recipient_name = db_to_html($priority_mail_recipient_name);
											}
											$products_obj_html .=  '<br /><span>'.TXT_PRIORITY_MAIL_RECIPIENT_NAME.': ' . $priority_mail_recipient_name . '</span>';
										}
									}
									$div_force_show = 'display:none;';
									if($update_needed_default == 'yes'){
										$div_force_show = '';
									}
				
			  						//团购优惠
			  						if($products[$i]['group_buy_discount']>0){
			  							$products_obj_html .= '<br /><span>'.TITLE_GROUP_BUY.' -'.$currencies->display_price($products[$i]['group_buy_discount'],0,$products[$i]['quantity']).'</span>';
			  						}

									$products_obj_html .= '</div>';
									if(tep_check_product_is_hotel($products[$i]['id'])!=1){
										$products_obj_html .= '<div style="'.$div_force_show.'width:100%;display:none" id="edit_cart_product_data_'.$iframe_cart_edit_update.'">
											<iframe onresize="calcHeight(\'iframe_prod_'.$iframe_cart_edit_update.'\');" id="iframe_prod_'.$iframe_cart_edit_update.'" src="ajax_edit_tour.php?products_id='.$products[$i]['id'].'&full_products_id='.$products[$i]['id'].'&update_frame='.$update_needed_default.'&product_number='.$i.'&departure_date='.$products[$i]['dateattributes'][0].'" frameborder="0"   scrolling="auto" width="380px"  height="300px" allowtransparency="true"   > </iframe>
											</div>';
									}
									$products_obj_html .= '<div><b>'.TABLE_HEADING_TOTAL.':</b> <b id="cart_product_data_price_'.$iframe_cart_edit_update.'" class="product_name_orange01">' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b></div>
										</td></tr></table>';
								
			
									$info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"','text' => $products_obj_html);
		
									$info_box_contents[$cur_row][] = array('align' => 'center','params' => 'class="productListing-data" valign="middle" style="white-space:nowrap"','text' => '<a   href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products[$i]['id']).$extra_query_string . '#edit_box">'. tep_template_image_button('small_edit.gif', db_to_html('编辑'),'','') . '</a>' .tep_draw_hidden_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']));
									/*  这是编辑按钮原来的JS代码 取消于2012-05-24 by lwkai   onclick="ShowContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');HideContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');  return true;"    */
									$info_box_contents[$cur_row][] = array('align' => 'center','params' => 'class="productListing-data" valign="middle" style="white-space:nowrap"','text' => tep_draw_checkbox_field('cart_delete[]', $products[$i]['id'],'','id="rem_'.$products[$i]['id'].'" style="visibility:hidden"') . tep_template_image_submit('button_remove.gif', "Remove",'onclick="return remove_item_confirmation(\''.$products[$i]['id'].'\');"'));										 
								}//endfor
								new productListingBox($info_box_contents);
	
?>
						</div>
					</td>
				</tr>
				
				<tr><td style="display:none;"><?php #lwkairem echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>	</tr>
				<?php
				if(isset($repeat_royal_customer_discount)){
						$sub_total = $cart->show_total();
						$od_amount_royal = ($sub_total*5)/100;
						$sub_total = $sub_total - $od_amount_royal;
						//$this->output[] = array('title' =>   ' Royal Customer Reward :','text' => '<b>-' . $currencies->format($od_amount_royal) . '</b>', 'value' => $od_amount_royal);								
				?>
				<tr>
					<td align="right" class="LRB" nowrap="nowrap">
					<?php
					// td old class=main lwkaiAdd
					//amit added to show currency icon start{
					echo '<b>'.TEXT_SHOW_CURRENCY_CHANGE.'</b>';
					if($currency == 'USD'){	?> 
					<span>
						<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART);?>"><img src="image/icons/usd_currency_on.jpg" align="absmiddle" /> </a>&nbsp;
						<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'currency=CNY');?>"><img src="image/icons/cny_ currency_off.jpg" align="absmiddle" /> </a>&nbsp;&nbsp;
					</span>
					<?php }else if($currency == 'CNY'){ ?> 
					<span>
						<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'currency=USD');?>"><img src="image/icons/usd_currency_off.jpg" align="absmiddle" /> </a>&nbsp;
						<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART);?>"><img	src="image/icons/cny_ currency_on.jpg" align="absmiddle" /> </a>&nbsp;&nbsp;</span> 
					<?php	}			
					//amit added to show currency icon end
					?>
					<b><?php echo SUB_TITLE_LOYAL_CUSTOMER_DISCOUNT; ?> <?php echo '<span id="loyal_sub_product_total_div" class="sp3_no_decoration">-' . $currencies->format($od_amount_royal) . '</span>'; ?></b>
					<br /> <b style="font-size: 16px; color: #E67402"><?php echo SUB_TITLE_TOTAL; ?><?php echo '<span id="sub_product_total_div">'.$currencies->format($sub_total).'</span>'; ?></b>
					</td>
				</tr>
				<?php } 	else{	?>
				<tr>
					<td align="right" class="LRB" nowrap="nowrap" valign="middle">
					<?php
					// 如果有结伴线路，则不显示切换币种的按钮 by lwkai 2012-06-08 add
					if (tep_not_null($jiebantongyong) == false) {
						//amit added to show currency icon start
					echo '<b>'.TEXT_SHOW_CURRENCY_CHANGE.'</b>';
					if($currency == 'USD'){	
					?>
					<span class="ui-paytype">
                    	
						<!--<a style="" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART);?>">--><input href="<?php echo tep_href_link(FILENAME_SHOPPING_CART);?>" type="radio" name="choose-money" checked="checked" id="choose-USD"  /><!--</a>--><label for="choose-USD" class="ui-pay-label"><?php echo db_to_html("$"); ?></label>&nbsp;
						<!--<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'currency=CNY');?>">--><input href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'currency=CNY');?>" type="radio" name="choose-money" id="choose-CNY"  /><!--</a>--><label for="choose-CNY" class="ui-pay-label"><?php echo db_to_html("￥"); ?></label>&nbsp;&nbsp;
                        
					</span>
					<?php }else if($currency == 'CNY'){ ?>
					<span class="ui-paytype">
                    	
						<!--<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'currency=USD');?>">--><input href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'currency=USD');?>" type="radio" name="choose-money" id="choose-USD"  /><label for="choose-USD" class="ui-pay-label"><?php echo db_to_html("$"); ?></label><!--</a>-->&nbsp;
						<!--<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART);?>">--><input href="<?php echo tep_href_link(FILENAME_SHOPPING_CART);?>" type="radio" name="choose-money"  checked="checked" id="choose-CNY"  /><label for="choose-CNY" class="ui-pay-label"><?php echo db_to_html("￥"); ?></label><!--</a>-->&nbsp;&nbsp;
                        
					</span>
					<?php }
					}			
					//amit added to show currency icon end
					?> 
					<b style="padding-left:15px;font-size: 16px; color: #E67402; padding-right:10px;"><?php echo SUB_TITLE_SUB_TOTAL; ?>
							<?php echo '<span id="sub_product_total_div">'.$currencies->format($cart->show_total()).'</span>'; ?>
					</b>
                    <script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("input[name='choose-money']").click(function(){
							location.href = jQuery(this).attr("href");
							//alert(1111);
						});
					});
					</script>
					</td>
				</tr>
				<?php
		}
				if ($any_out_of_stock == 1) {
					if (STOCK_ALLOW_CHECKOUT == 'true') { 	?>
						<tr><td class="stockWarning" align="center"><br /> <?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td></tr>
				<?php } else {?>
						<tr><td class="stockWarning" align="center"><br /> <?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td></tr>
				<?php  }
		   		 }?>
		    	<?php 
		    	if (MAIN_TABLE_BORDER == 'yes'){
		    		table_image_border_bottom();
		    	}
		    	?>
				<tr>	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>

				<?php
				if($currency == 'CNY'){ // 添加RMB提示语
					echo '<tr><td>';
					echo '<div style="text-align:left; background:#F7FFE1; color:#E67402; padding:5px; border: 1px solid #79AC21;margin-top:8px;">'.TEXT_RMB_CHECK_OUT_MSN.'</div>'; 
					echo '</td></tr>';
					echo '<tr><td>'.tep_draw_separator('pixel_trans.gif', '100%', '10');
					echo '</td></tr>';
				}
				
				if($cart_have_jiebantongyong == true){ // 添加结伴同游提示语
					echo '<tr><td>';
					echo '<div style="text-align:left; background:#F7FFE1; color:#E67402; padding:5px; border: 1px solid #79AC21;margin-top:8px;">'.JIEBANG_CART_NOTE_MSN.'</div>'; 
					echo '</td></tr>';
					echo '<tr><td>'.tep_draw_separator('pixel_trans.gif', '100%', '10');
					echo '</td></tr>';
				}
				?>

				<tr>
					<td><table border="0" width="100%" cellspacing="1" cellpadding="2"	class="infoBox">
							<tr class="infoBoxContents">
								<td>
									<table border="0" width="100%" cellspacing="0"	cellpadding="2">
										<tr>
											<td width="10"><?php #lwkairem echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
											<td width="32%">
											<?php 
												if(preg_match('/shopping_cart\.php/',$HTTP_SERVER_VARS['HTTP_REFERER'])){
													//echo '<a href="'.$HTTP_REFERER_TWO.'">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; 
												}else{
													#lwkairem echo '<a href="javascript:history.go(-1)">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
													echo '<a class="btn" href="javascript:history.go(-1)"><span></span>' . db_to_html('返回上一页') . '</a>';
												}
											?>
											</td>
											<td class="main" width="1%"><?php //echo tep_template_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); ?></td>
											<?php
											 //  $back = sizeof($navigation->path)-2;
											  //   if (isset($navigation->path[$back])) {
											  ?>
											<td class="main" width="32%" align="center">
												<?php #lwkairem echo '<a href="' .tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'; 
														echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '"  class="btn"><span></span>' . db_to_html("继续购物") . '</a>'; 
												?>
											</td>
											<?php  //tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) 
											//    }
											?>
											<td width="32%" align="right" class="main" 	style="padding: 2px;">
												<?php #lwkaiRem echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=checkout', 'SSL') . '">' . tep_template_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>';
													echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=checkout', 'SSL') . '" class="btn checkout"><span></span>' . db_to_html("确定无误，去结算") . '</a>';
												?>
											</td>
											<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				// WebMakers.com Added: Shipping Estimator
				if (SHOW_SHIPPING_ESTIMATOR=='true') {
					// always show shipping table
				?>
				<tr><td><?php require(DIR_FS_MODULES . 'shipping_estimator.php'); ?></td>	</tr>
				<?php  }?>
				
<?php  } else {?>
				<tr>	<td align="center" class="main"><?php echo TEXT_CART_EMPTY; ?></td>	</tr>
				<?php if (MAIN_TABLE_BORDER == 'yes'){table_image_border_bottom();}?>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellspacing="1" cellpadding="2"		class="infoBox">
							<tr class="infoBoxContents">
								<td>
									<table border="0" width="100%" cellspacing="0"		cellpadding="2">
											<tr>
												<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
												<td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
												<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
											</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			<?php  }?>
			</table>
		</td>
	</tr>
	<tr>	<td height="15" style="word-wrap:break-word:" ><?php if($arr_orders=getOldOrders()){?><font color="#FF0000"><?php $str_need=''; foreach ($arr_orders as $value){ $str_need.=", $value[orders_id]";} echo db_to_html('温馨提示：您的帐户中存在未付款的订单：'.substr($str_need,1).'<a href="'.tep_href_link_noseo('account_history.php').'" target="_blank">（查看）</a>');?></font><?php }?></td></tr>
</table>
<!-- content main body end -->
<?php echo tep_get_design_body_footer();?></form>
<?php

//print_r($products);$error = true;	
if($error == true){ ?>
<script type="text/javascript">
var error_msn = document.getElementById('error_msn');
if(error_msn !=null){
	error_msn.style.display ="";
}
</script>
<?php } ?>