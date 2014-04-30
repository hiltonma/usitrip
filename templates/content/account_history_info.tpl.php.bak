<?php /*echo tep_get_design_body_header(HEADING_TITLE,1);*/ ?>
<?php
//取得所有属于邮轮团的产品属性ID
$cruisesOptionIds = getAllCruisesOptionIds();


// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
	table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
//tom added
$result_echo_ss=tep_get_orders_status_name($_GET['order_id']);

//是否是结伴同游的订单
$is_companion = false;
$check_sql = tep_db_query('SELECT orders_id FROM `orders_travel_companion` WHERE orders_id="'.(int)$HTTP_GET_VARS['order_id'].'" limit 1 ');
$check_rows = tep_db_fetch_array($check_sql);
if ((int)$check_rows['orders_id'] > 0) {
	$is_companion = true;
}

?>

<!-- content main body start -->

<div class="userConts">
  <div class="OrderFormDetail_1">
    <div class="float_l"><?php echo db_to_html('订单编号：')?><strong class="color_orange font_size14"><?php echo $_GET['order_id']?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo db_to_html('状态：')?><strong class="color_orange font_size14"><?php echo db_to_html($result_echo_ss)?></strong>
	<?php
	if((int)$Admin->login_id){	//如果是管理登录则提示自动登录网址，以便给客人发过去！
	?>
	<iframe style="display:block; border:none;" width="720" height="85" src="<?php echo tep_href_link('auto_login_url.php','order_id='.(int)$_GET['order_id']);?>"></iframe>
	<?php
	}
	?>
	</div>
    <?php /*?><div class="float_r"><a class="print_btn" href="javascript:popupWindow1('<?php echo(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_ORDERS_PRINTABLE)?>?<?php echo (tep_get_all_get_params(array('order_id')) . 'order_id=' . $_GET['order_id']) ?>')"><span></span><?php echo db_to_html("打印订单")?></a></div><?php */?>
    <div class="clear"></div>
  </div>
</div>
<div class="userDebit">
  <div class="tit"><?php echo db_to_html('订购人信息')?></div>
  <div class="con">
  <?php #print_r($order->info); ?>
    <ul>
      <li>
	  <?php
	  //列出订购人信息 start {
	  if(!tep_not_null($order->customer['name'])){
		  $order->customer['name'] = tep_get_customer_name($order->customer['id']);
	  }
	  if(!tep_not_null($order->customer['telephone'])){
		  $order->customer['telephone'] = tep_customers_cellphone($order->customer['id']);
	  }
	  if(!tep_not_null($order->customer['email_address'])){
		  $order->customer['email_address'] = tep_get_customers_email($order->customer['id']);
	  }
	  echo db_to_html("姓&nbsp;名:");
	  echo db_to_html(tep_db_output($order->customer['name']));
	  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	  echo db_to_html("电&nbsp;话:");
	  echo db_to_html(tep_db_output($order->customer['telephone']));
	  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	  echo db_to_html("电子邮箱:");
	  echo db_to_html(tep_db_output($order->customer['email_address']));
	  //列出订购人信息 end }
	  ?>
	  </li>
    </ul>
  </div>
</div>
<div class="userDebit">
  <div class="tit"><span class="float_r"><?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?></span><?php echo db_to_html('订单详情')?></div>
  <div class="con3">
    <?php
	if(isset($_POST['flight_no']))
{
	$sql_data_array = array('airline_name' => tep_db_prepare_input($_POST['airline_name']),
							'flight_no' => tep_db_prepare_input($_POST['flight_no']),
							'airline_name_departure' => tep_db_prepare_input($_POST['airline_name_departure']),
							'flight_no_departure' => tep_db_prepare_input($_POST['flight_no_departure']),
							'airport_name' => tep_db_prepare_input($_POST['airport_name']),
							'airport_name_departure' => tep_db_prepare_input($_POST['airport_name_departure']),
							'arrival_date' => tep_get_date_db($_POST['arrival_date']),
							'arrival_time' => tep_db_prepare_input($_POST['arrival_time']),
							'departure_date' => tep_get_date_db($_POST['departure_date']),
							'departure_time' => tep_db_prepare_input($_POST['departure_time']));
	//航班信息更新通知客服人员
	if(tep_not_null($_POST['airline_name'])){
		$check_sql_str = ('SELECT orders_flight_id FROM '.TABLE_ORDERS_PRODUCTS_FLIGHT. ' WHERE orders_id="'.(int)$_GET['order_id'].'" AND products_id="'.(int)$_GET['pid'].'" AND airline_name ="'.html_to_db(tep_db_input($sql_data_array['airline_name'])).'" AND flight_no="'.html_to_db(tep_db_input($sql_data_array['flight_no'])).'" AND airline_name_departure="'.html_to_db(tep_db_input($sql_data_array['airline_name_departure'])).'" AND flight_no_departure="'.html_to_db(tep_db_input($sql_data_array['flight_no_departure'])).'" AND airport_name="'.html_to_db(tep_db_input($sql_data_array['airport_name'])).'" AND airport_name_departure="'.html_to_db(tep_db_input($sql_data_array['airport_name_departure'])).'" AND arrival_date="'.html_to_db(tep_db_input($sql_data_array['arrival_date'])).'" AND arrival_time="'.html_to_db(tep_db_input($sql_data_array['arrival_time'])).'" AND departure_date="'.html_to_db(tep_db_input($sql_data_array['departure_date'])).'" AND departure_time="'.html_to_db(tep_db_input($sql_data_array['departure_time'])).'" Limit 1; ');
		//echo $check_sql_str;
		$check_sql = tep_db_query($check_sql_str);
		$check_row = tep_db_fetch_array($check_sql);
		if(!(int)$check_row['orders_flight_id']){
			$sql_data_array['show_warning_on_admin'] = "1";
			//自动更新订单状态为"100100"，step4 航班信息已更新,需更新给地接
			//2012-05-22根据Sofia要求暂时取消这个动作,需求文档05-8_Sofia_Bug.fixed.xls
			/////tep_update_orders_status($_GET['order_id'], '100100', '96', '此状态由系统在前台客人订单页自动设置。');

			//send mail to service :
			if(IS_DEV_SITES == true){
				$to_name=$to_email_address='xmzhh2000@126.com';
			}else{
				$to_name=$to_email_address='service@usitrip.com';
			}
			$from_email_name='usitrip';
			$from_email_address='service@usitrip.com';

			$email_subject='订单 #'.$_GET['order_id'].' 航班信息已更新！';
			//$mail_orderhref = tep_href_link_noseo('admin/edit_orders.php','language=sc&oID='.$_GET['order_id'].'&action=edit');
			$mail_orderhref = HTTP_SERVER.'/admin/edit_orders.php?language=sc&oID='.$_GET['order_id'].'&action=edit';
			$email_text='订单号码 #'.$_GET['order_id'].' 的航班信息已经更新, 请跟进处理。<br><br>定单管理地址：<br><a href="'.$mail_orderhref.'" target="_blank">'.$mail_orderhref.'</a><br><br>';
			tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address , 'true', 'gb2312');
			
			
			/*
			$var_num = count($_SESSION['need_send_email']);
			$_SESSION['need_send_email'][$var_num]['to_name'] = $to_name;
			$_SESSION['need_send_email'][$var_num]['to_email_address'] = $to_email_address;
			$_SESSION['need_send_email'][$var_num]['email_subject'] = $email_subject;
			$_SESSION['need_send_email'][$var_num]['email_text'] = $email_text;
			$_SESSION['need_send_email'][$var_num]['from_email_name'] =$from_email_name;
			//$_SESSION['need_send_email'][0]['from_email_address'] = STORE_OWNER_EMAIL_ADDRESS;
			$_SESSION['need_send_email'][$var_num]['from_email_address'] =$from_email_address;
			$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;*/
		}
	}

	//查看是否需要短信提醒延住
	if(tep_not_null($_POST['airline_name'])){
		$qry_flight_info = "SELECT o.orders_id, o.orders_status, f.arrival_date, f.arrival_time, f.departure_date, f.departure_time, DATE(op.products_departure_date) AS products_departure_date, p.products_durations FROM orders o, orders_products op, products p, orders_product_flight f WHERE o.orders_id = op.orders_id AND p.products_id = op.products_id AND f.orders_id = op.orders_id AND f.products_id = op.products_id AND op.orders_id='".(int)$_GET['order_id']."' AND op.products_id='".(int)$_GET['pid']."' Limit 1;";
		$res_flight_info = tep_db_query($qry_flight_info);
		$row_flight_info = tep_db_fetch_array($res_flight_info);
		//当订单状态不为“Cancelled”（即不为6）时才进一步验证
		if($row_flight_info['orders_status'] != '6'){
			//旅游开始和结束时间
			$tour_start_time = strtotime($row_flight_info['products_departure_date']);
			$tour_end_time = $tour_start_time+(($row_flight_info['products_durations']-1)*3600*24);
			/************* start *** 原来的飞机到达时间未在出团时间前1-3天内，但自己修改航班信息后在1-3天内，则发送短信提醒。其它情况不发送 ************/
			$is_sended = 0; //是否已发送过短信
			$tmp_arrival_date = query_date($row_flight_info['arrival_date']);
			if(tep_not_null($tmp_arrival_date) && $tmp_arrival_date != '0000-00-00'){
				$flight_arrival_time = strtotime($tmp_arrival_date);
				$differ_days = ($tour_start_time - $flight_arrival_time)/24/3600;
				//提前1天或者2天到的旅客已发送过酒店延住短信提醒
				if($differ_days == '1' || $differ_days == '2' || $differ_days == '3'){
					$is_sended = 1;
				}
			}
			//没有发送过短信提醒
			if(!$is_sended){
				$tmp_arrival_date = query_date($_POST['arrival_date']);
				if(tep_not_null($tmp_arrival_date) && $tmp_arrival_date != '0000-00-00'){
					$flight_arrival_time = strtotime($tmp_arrival_date);
					$differ_days = ($tour_start_time - $flight_arrival_time)/24/3600;
					//提前1天或者2天到的旅客才发送酒店延住信息
					if($differ_days == '1' || $differ_days == '2'){
						send_before_extension_sms($row_flight_info['orders_id']);
					}
				}
			}
			/************* end ***** 原来的飞机到达时间未在出团时间前1-3天内，但自己修改航班信息后在1-3天内，则发送短信提醒。其它情况不发送 ************/

			/************* start *** 原来的飞机离开时间未在旅游结束后32-104小时内，但自己修改航班信息后在32-104小时内，则发送短信提醒。其它情况不发送 ************/
			$is_sended = 0; //是否已发送过短信
			//发短信提醒延住的飞机离开时间要求
			$interval_start_time = $tour_end_time+32*3600;
			$interval_end_time = $tour_end_time+104*3600;
			//飞机离开日期和时间
			$tmp_departure_date = query_date($row_flight_info['departure_date']);
			$tmp_departure_time = query_time($row_flight_info['departure_time']);
			if(tep_not_null($tmp_departure_date) && $tmp_departure_date != '0000-00-00' && tep_not_null($tmp_departure_time) && $tmp_departure_time != '00:00'){
				//飞机离开时间
				$flight_departure_time = $tmp_departure_date.' '.$tmp_departure_time;
				$flight_departure_time = strtotime($flight_departure_time);
				//飞机离开时间在旅行结束后第二天早上8:00以后，第五天早上8:00之前的，已发送短信提醒旅客延住
				if($interval_start_time < $flight_departure_time && $flight_departure_time < $interval_end_time){
					$is_sended = 1;
				}
			}
			//没有发送过短信提醒
			if(!$is_sended){
				$tmp_departure_date = query_date($_POST['departure_date']);
				$tmp_departure_time = query_time($_POST['departure_time']);
				if(tep_not_null($tmp_departure_date) && $tmp_departure_date != '0000-00-00' && tep_not_null($tmp_departure_time) && $tmp_departure_time != '00:00'){
					//飞机离开时间
					$flight_departure_time = $tmp_departure_date.' '.$tmp_departure_time;
					$flight_departure_time = strtotime($flight_departure_time);
					//飞机离开时间在旅行结束后第二天早上8:00以后，第五天早上8:00之前的，已发送短信提醒旅客延住
					if($interval_start_time < $flight_departure_time && $flight_departure_time < $interval_end_time){
						send_after_extension_sms($row_flight_info['orders_id']);
					}
				}
			}
			/************* end ***** 原来的飞机离开时间未在旅游结束后32-104小时内，但自己修改航班信息后在32-104小时内，则发送短信提醒。其它情况不发送 ************/
		}
	}

	$sql_data_array['orders_products_id'] = (int)$_POST['orders_products_id'];
	tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, html_to_db($sql_data_array), 'update', 'orders_products_id='.(int)$_POST['orders_products_id']);
	
	//如果已经出了电子参团凭证，并且现在在更新航班信息，则把该航班信息的出了电子参团凭证后的更新记录字段设置为1，不再允许客户更新航班信息
	$eticket_query = tep_db_query("select confirmed,products_id from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$_GET['order_id'] . "' and orders_eticket_id=".(int)$_POST['orders_products_id']." ");
	$eticket_result = tep_db_fetch_array($eticket_query);
	if($eticket_result['confirmed']=='1'){
		//已出电子参团凭证
		tep_db_query("update orders_product_flight set modify_count = 1 where orders_id='" . (int)$_GET['order_id'] . "' and products_id='" . $eticket_result['products_id'] . "'");
	}
	//记录航班更新历史
	tep_add_orders_product_flight_history(html_to_db($sql_data_array),(int)$_POST['orders_products_id'],0,$customer_id,0);
}


#if($_GET['edit']!='true')
#{
	
	/* 计算是否已经取消单 ，如果取消了 则不显示更新航班信息 */
	$edit_switch = false; //航班信息显示开关变量 false 不可编辑
	for ($fli_i=0, $n=sizeof($order->totals); $fli_i<$n; $fli_i++) {	
		if($order->totals[$fli_i]['class']=="ot_total"){
			$otTotal = $order->totals[$fli_i]['value'];
		}
	}
	
	$need_pay = $otTotal - $order->info['orders_paid'];
	
	//if($need_pay > 0 && $order->info['orders_status_id'] != 6){
	// 上面是算订单是否已经付款完毕
	// 现在只是取消和发了电子参团凭证的不能填写航班信息
	if ($order->info['orders_status_id'] != 6){
		//echo $updateFlightInfoLink;
		$edit_switch = true; // 可以编辑航班信息
	} else {
		// 把编辑状态改为非编辑状态 因为取消的单 不允许编辑航班信息
		$_GET['edit'] = 'false';
	}
//	unset($need_pay);
//	unset($otTotal);
	// 航班修改按钮 结束
											
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		if($order->products[$i]['is_hide']==0){//判断是否隐藏
		$updateFlightInfoLink = ''; //先清空修改按钮连接
		$etickitlink = '';// 清空前一个循环的打印电子参团凭证连接
		$visa_invitation_link = '';//清空前一个循环的邀请函连接
		//echo '<pre>'.print_r($order->products[$i],true)."</pre>";
		$eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$_GET['order_id'] . "' and products_id=".$order->products[$i]['id']." ");
		$eticket_result = tep_db_fetch_array($eticket_query);
		if($eticket_result['confirmed']==1){	//电子参团凭证发送后只能看票，不能再更新航班信息
			$etickitlink = '<a href="' . tep_href_link('eticket.php', 'order_id=' . $_GET['order_id'].'&pid='.$order->products[$i]['id'].'&i='.$i, 'NONSSL') . '" class="print_btn dzp" target="_blank"><span></span>' . db_to_html('打印电子参团凭证') . '</a>';
			// 判断出了电子参团凭证之后，是否已经修改过一次航班信息
			$check_modify_sql = "select modify_count from orders_product_flight where orders_id='" . $_GET['order_id'] . "' and products_id='" . $order->products[$i]['id'] . "'";
			$check_modify_result = tep_db_query($check_modify_sql);
			$check_modify_result = tep_db_fetch_array($check_modify_result);
			if ($check_modify_result['modify_count'] == '0') {
				$updateFlightInfoLink = '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'] . '&pid='.$order->products[$i]['id'].'&i='.$i.'&edit=true', 'SSL') . '" class="update_btn"><span></span>修 改</a>';
			}
			
			#$etickitlink = '<a href="' . tep_href_link('eticket.php', 'order_id=' . $_GET['order_id'].'&pid='.$order->products[$i]['id'].'&i='.$i, 'NONSSL') . '" target="_blank">' . tep_template_image_button('eticket.gif', SMALL_IMAGE_BUTTON_EDIT) . '</a>';
		}else{
			if(!tep_check_product_is_hotel($order->products[$i]['id'])  &&  !tep_check_product_is_transfer($order->products[$i]['id']) ){
					$updateFlightInfoLink = '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'] . '&pid='.$order->products[$i]['id'].'&i='.$i.'&edit=true', 'SSL') . '" class="update_btn"><span></span>修 改</a>';
			}
		}
		//判断是否发了邀请函
		if (tep_not_null($eticket_result['visa_invitation_send_date'])) {
		if(checkHaveInvitation($eticket_result['orders_products_id'])){
			$visa_invitation_link = '<a href="' . tep_href_link('visa_invitation.php','opid=' . $eticket_result['orders_products_id'], 'NONSSL') . '" class="print_btn" id="ui-invitation-btn" target="_blank"><span></span>' . db_to_html('打印邀请函') . '</a>';
			}
		}
		echo '<div class="OrderFormD_tit color_orange">' . $visa_invitation_link . $etickitlink . ($i+1) . db_to_html('、' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$order->products[$i]['id']) . '" target="_blank">' . $order->products[$i]['name'] . '</a>') . '</div>'; # ($i+1) 原来此处标题前显示的号是这个变量 $order->products[$i]['qty']
		echo '<div class="basic">';
		
		#print_r($order->products[$i]);
		echo '<p>';
		echo db_to_html('旅游团号:') . $order->products[$i]['model'] . '<br/>';
		if($order->products[$i]['is_hotel'] == '1'){
			echo db_to_html("<i>入住日期:").tep_date_long($order->products[$i]['products_departure_date']).db_to_html("  退房日期：").tep_date_long($order->products[$i]['hotel_checkout_date']). '</i>' ;
		}else if($order->products[$i]['is_transfer'] == '1'){
			echo db_to_html(str_replace(array('<strong>','</strong>'),'',tep_transfer_display_route($order->products[$i]['transfer_info_arr'])));
		}else{
			echo db_to_html('<i>出发时间:' . $order->products[$i]['products_departure_date'] . $order->products[$i]['products_departure_time']. '</i>');
			echo db_to_html('<br/><i>');
			if ($order->products[$i]['products_type'] == 7) {
				echo db_to_html('演出时间/地点：');
			} else {
				echo db_to_html('乘车地点：');
			}
			echo db_to_html($order->products[$i]['products_departure_location'] . '</i>');
			echo db_to_html("<br/><i>出发城市：" . tep_get_product_departure_city($order->products[$i]['id']) . '</i>');
		}
		$customers_tel = $order->info['guest_emergency_cell_phone'] ? $order->info['guest_emergency_cell_phone'] : tep_customers_cellphone((int)$order->customer['id']);
		if (tep_not_null($customers_tel)) {
			echo db_to_html("<br/><i>游客手机：" . $customers_tel . '</i>');
		}
		
		
							if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
								for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {

									if($order->products[$i]['attributes'][$j]['price']>0){
										echo '<br/>'.db_to_html('<small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small>');
									}else{
										if(trim($order->products[$i]['attributes'][$j]['option'])!='' && !in_array($order->products[$i]['attributes'][$j]['option_id'], (array)$cruisesOptionIds)){
											echo '<br/>'.db_to_html('<small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small>');
										}
									}

								}
							}

							if($order->products[$i]['products_room_info']!=''){
								$roomInfoString = format_out_roomattributes_1($order->products[$i]['products_room_info'], (int)$order->products[$i]['total_room_adult_child_info']);
								echo '<br/>'.'<nobr><small><i>' . db_to_html($roomInfoString) . '</i></small></nobr>';
							}
							echo '<br/>' . db_to_html('房间总费用:') . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
							echo '<em>' . db_to_html('行程费用') . '<br/><span>' .  $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</span><br/>' . db_to_html('支付状态') . '<br/><span>' . db_to_html(tep_get_orders_products_payment_status_name($order->products[$i]['orders_products_payment_status'])) . '</span></em>';
							//var_dump($order->products[$i]);
							echo '</p>' . "\n";
							
							$guestInfo = tep_get_orders_product_guest($order->products[$i]['orders_products_id']);
							
							$guestSex = tep_get_orders_product_guest_gender($order->products[$i]['orders_products_id']);
							
							?>
							<ul class="ren" style="margin:5px 0;overflow:hidden;*zoom:1;">
								<?php
								$guest_html = '';
								for($ii=0, $N=sizeof($guestInfo); $ii<$N; $ii++){
									$guest_html .= '<li><span id="guest_' . $i . '_' . $ii . '">';
									
									$userName_temp = $userName_date = '';
									
									$guest_name_incudes_child_age = explode('||',$guestInfo[$ii]);
									if(isset($guest_name_incudes_child_age[1])){
										if($order->products[$i]['products_departure_date'] != ''){
											$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($order->products[$i]['products_departure_date'],0,10)));
										}
										$userName_temp =stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]));
										$userName_date = $guest_name_incudes_child_age[1];
										$guestConten = $userName_temp .'('.$di_childage_difference_in_year.')';
									}else{
										$userName_temp =stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]));
										$guestConten = $userName_temp;
									}
								
									$guest_html .= db_to_html('<i style="font-style:normal" id="text_' . $i . '_' . $ii .'">游客'.($ii+1).'：' . $guestConten) . '<br/>';
									if (is_array($guestSex) && tep_not_null($guestSex[$ii])) {
										$guest_html .= db_to_html(tep_db_output('&nbsp;性别：' . $guestSex[$ii]));
									}
									//$guest_html .= '</span>';
									// 如果不是结伴同游订单 并且没有付过一分钱.则可以修改用户名

									if ((float)$order->info['orders_paid'] <= 0){
										if ($is_companion == false){ //结伴同游订单,在点击本身的结伴同游 付款之后,是可以修改参与人的.所以这里只对非结伴订单进行修改
											$guest_html .= db_to_html('</i><input type="button" class="ui-guestBtn" value="修改" onclick="jQuery(\'#guest_' . $i . '_' . $ii . '\').hide();jQuery(\'#Modify_' . $i . '_' . $ii . '\').show();" /></span>');

											$guestConten_arr = explode(",",$userName_temp);
											$guest_html .= db_to_html('<span id="Modify_' . $i . '_' . $ii . '" style="display:none">' . 
												tep_draw_hidden_field("temp_temp1_" . $i . '_' . $ii, (int)$_GET['order_id'], ' id="order_' . $i . '_' . $ii . '" ') . 
												tep_draw_hidden_field("temp_temp2_" . $i . '_' . $ii, $order->products[$i]['id'],' id="product_' . $i . '_' . $ii . '"') . '姓:' .
												tep_draw_input_num_en_field("temp_temp3_" . $i . "_" . $ii, $guestConten_arr[0],' class="ui-guestIpt" id="firstname_' . $i . '_' . $ii . '" style="width:50px;"') . '名:' .
												tep_draw_input_num_en_field("temp_temp3_".$i."_".$ii,$guestConten_arr[1],'  class="ui-guestIpt" id="lastname_' . $i . '_' . $ii . '" style="width:50px;"') . '<br/>');
												if (is_array($guestSex) && tep_not_null($guestSex[$ii])) {
													$guest_html .= db_to_html('性别:' .
														tep_draw_pull_down_menu("temp_select5_" . $i . '_' . $ii, array(array('text'=>'男','id'=>'男'),array('text'=>'女','id'=>'女')), $guestSex[$ii],' id="guestSex_' . $i . '_' . $ii . '"'));
												}
											if (tep_not_null($userName_date) == true) {
												$guest_html .= db_to_html('出生日期:' . 
												tep_draw_input_field("temp_date_" . $i . "_" . $ii,$userName_date,' title="格式：月月/日日/年年年年" class="required validate-date-us text_time " onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" size="10" id="guestchildage_' . $i . '_' . $ii . '"'));
											}
											$guest_html .= db_to_html('<input type="button" value="保存" class="ui-guestBtn" onclick="ajaxModifyName(' . $i . ',' . $ii . ')"/></span>');
										} else {
											$guest_html .= '</i></span>';
										}
									} else {
										$guest_html .= '</i></span>';
									}
									$guest_html .= '</li>';
								}
								echo $guest_html;?>
							</ul>
							<?php

							if (sizeof($order->info['tax_groups']) > 1) {
								echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
							}

							
							// 总价格
							#echo '            <td class="main" nowrap="nowrap" align="right" valign="top">' .$etickitlink.'&nbsp;' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
							//'          </tr>' . "\n";
ob_start();

							if(!tep_check_product_is_hotel($order->products[$i]['id'])  &&  !tep_check_product_is_transfer($order->products[$i]['id']) ){
								
											


								if ($_GET['edit'] != 'true' || $_GET['i'] != $i) {
									
									$orders_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)tep_db_input($_GET['order_id']) . "' and products_id = '".$order->products[$i]['id']."'");
									if (tep_db_num_rows($orders_history_query))
									{
										while ($orders_history = tep_db_fetch_array($orders_history_query))
										{
											?>
											<div class="information"><span class="float_r Flight "><em></em><span><?php echo ('隐藏')?></span></span><?php
											/* 计算是否已经取消单 ，如果取消了 则不显示更新航班信息 */
											if ($edit_switch == true) {
												echo $updateFlightInfoLink;
											}
											// 航班修改按钮 结束
											?>
						  <div class="float_l"><strong><?php echo ('航班信息')?></strong>&nbsp;&nbsp;<?php echo ('建议您在确保此行程订购成功之后再购买机票')?>&nbsp;&nbsp;</div>
											</div>
											<div class="flight flight_contraction">
											  <div class="box_warp">
												<ul>
                                                  <li class="textR">接机日期：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['arrival_date']));?></li>
												  <li class="textR">送机日期：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['departure_date']));?></li>
                                                  <li class="textR">航空公司：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['airline_name']));?></li>
												  <li class="textR">航空公司：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['airline_name_departure']));?></li>
												  <li class="textR">接机航班：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['flight_no']));?></li>
												  <li class="textR">送机航班：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['flight_no_departure']));?></li>
												  
												  <li class="textR">接机机场：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['airport_name']));?></li>
												  <li class="textR">送机机场：</li>
												  <li class="textL"><?php echo  (tep_db_output($orders_history['airport_name_departure']));?></li>
												  
												  <li class="textR">到达时间：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['arrival_time']));?></li>
												  <li class="textR">起飞时间：</li>
												  <li class="textL"><?php echo (tep_db_output($orders_history['departure_time']));?></li>
												</ul>
											  </div>
											</div>
										<?php 
										}// end of while loop
									}//end of if
								} elseif ($_GET['edit'] == 'true' && $_GET['i'] == $i) {
									?>
                                    <script type="text/javascript">
									function validateFlightInfo(form){
										var flag1 = flag2 = true ;
										var msg = '';
										if (jQuery('#a1:checked').length == 0) {
											var ck1 = jQuery('input[mytype=\'Before\'][name=\'arrival_date\']').val();
											var ck2 = jQuery('input[mytype=\'Before\'][name=\'airline_name\']').val();
											var ck3 = jQuery('input[mytype=\'Before\'][name=\'flight_no\']').val();
											var ck4 = jQuery('input[mytype=\'Before\'][name=\'airport_name\']').val();
											var ck5 = jQuery('input[mytype=\'Before\'][name=\'arrival_time\']').val();
											if (ck1 == '' || ck2 == '' || ck3 == '' || ck4 == '' || ck5 == '') {
												msg = '您的接机航班信息未填写完整！\n';
												flag1 = false;
											}
										} else {
											//alert('自行入住，不管了');
										}
										
										// 判断参团后的航班信息 按商务部要求 不对后面的做检测 不填 默认是 自行离团
										
										if (jQuery('#a2:checked').length == 0) {
											var ck1 = jQuery('input[mytype=\'After\'][name=\'departure_date\']').val();
											var ck2 = jQuery('input[mytype=\'After\'][name=\'airline_name_departure\']').val();
											var ck3 = jQuery('input[mytype=\'After\'][name=\'flight_no_departure\']').val();
											var ck4 = jQuery('input[mytype=\'After\'][name=\'airport_name_departure\']').val();
											var ck5 = jQuery('input[mytype=\'After\'][name=\'departure_time\']').val();
											if (ck1 == '' || ck2 == '' || ck3 == '' || ck4 == '' || ck5 == '') {
												msg += '您的送机航班信息未填写完整！\n';
												flag2 = false;
											}
										} else {
											//alert('自行离团，不管了');
											
										}
										
										
										
										if(flag1 && flag2){
											form.submit();
											return true;
										} else {
											alert(msg);
											return false;	
										}
									}
									</script>
                                    <?php
									echo tep_draw_form('frmflightinfo', tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'].'&pid='.$_GET['pid'], 'SSL'), 'post', ' id="Frmflightinfo"  onsubmit="validateFlightInfo(this);return false;"');
					                $orders_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$_GET['order_id'] . "' and products_id='".$_GET['pid']."'");
					                if (tep_db_num_rows($orders_history_query))
					                {
					                	while ($orders_history = tep_db_fetch_array($orders_history_query))
					                	{
						  ?>
                  
    <div class="information"><span class="float_r Flight "><em></em><span>隐藏</span></span>
          <div class="float_l"><strong>航班信息</strong>&nbsp;&nbsp;建议您在确保此行程订购成功之后再购买机票&nbsp;&nbsp;</div>
    </div>
                    <?php
					// 如果已经填过航班信息 ，并且选择了自行入住 则勾选框默认选中
						$chk1 = $inputclass1 = $chk2 = $inputclass2 = '';
						if ($orders_history['flight_no'] == '自行入住酒店') {
							$chk1 = 'checked=checked';
							$inputclass1 = 'background-color:#ece9d8';
						}
						
						if ($orders_history['flight_no_departure'] == '自行离团') {
							$chk2 = 'checked=checked';
							$inputclass2 = 'background-color:#ece9d8';
						}
					?>
                    <script type="text/javascript">
					<!--
					<?php
					if ($chk1 != "") {
						?>
						jQuery(document).ready(function(e) {
                            jQuery('#Frmflightinfo').attr('chk','1');
                        });
						<?php	
					}
					?>
					function my_disable(obj,type,num){
						if(obj.checked == true) {
							jQuery('input[mytype=\'' + type + '\']').attr('readonly',true).css('background-color','#ece9d8');
							jQuery('#flight_' + type + '_' + num).val(jQuery('#flight_' + type + '_' + num).attr('mytext'));
							if (type == 'Before') {
								jQuery('input[class=\'text_time\'][mytype=\'' + type + '\']').val('<?php echo date('m/d/Y',strtotime($order->products[$i]['products_departure_date']));?>');
							} else {
								jQuery('input[class=\'text_time\'][mytype=\'' + type + '\']').val('<?php 
								if ((int)$order->products[$i]['is_hotel'] == 0) {
									echo date('m/d/Y',strtotime(tep_get_products_end_date($order->products[$i]['id'],$order->products[$i]['products_departure_date'])));
								} else {
									//$hotel_date_temp = explode('|=|',$order->products[$i]['hotel_extension_info']);
									
									echo date('m/d/Y',strtotime($order->products[$i]['hotel_checkout_date']));
								}
								?>');
							}
							jQuery('input[class=\'text_hour\'][mytype=\'' + type + '\']').val('');
							if(jQuery('#a1').attr('checked') == true) {
								jQuery('#Frmflightinfo').attr('chk','1');
							}
						} else {
							jQuery('input[mytype=\'' + type + '\']').attr('readonly',false).css('background-color','#fff');
							jQuery('#flight_' + type + '_' + num).val('');
							jQuery('input[class=\'text_time\'][mytype=\'' + type + '\']').val('');
							if(jQuery('#a1').attr('checked') == false) {
								jQuery('#Frmflightinfo').attr('chk','0');
							}
						}
					}

					//-->
					</script>
    <div class="flight flight_contraction">
      <div class="box_warp">
        <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
          <td class="textR"></td>
          <td class="textL"><?php echo tep_draw_checkbox_field('a1','',false,$chk1 . ' id="a1" style="width:auto;height:auto;" onclick="my_disable(this,\'Before\',\'' . $i . '\')"');?>参团日自行入住酒店</td>
          <td class="textR"></td>
          <td class="textL"><?php echo tep_draw_checkbox_field('a2','',false,$chk2 . ' id="a2" style="width:auto;height:auto;" onclick="my_disable(this,\'After\',\'' . $i . '\')"');?>行程结束自行离团</td>
          </tr>
          <tr>
          <td colspan="2" align="center" style="color:#9B9B9B;">若您选择自行入住酒店，请点勾选上面复选框。</td>
          <td colspan="2" align="center" style="color:#9B9B9B;">若无离团航班，默认为自行离团。</td>
          </tr>
          <tr>
          <td class="textR">接机日期：</td>
          <td class="textL"><?php echo tep_draw_input_num_en_field('arrival_date', tep_get_date_disp($orders_history['arrival_date']), 'mytype="Before" style="width:110px; ime-mode:disabled; ' . $inputclass1 . '" class="text_time" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ','text',false); ?></td>
          <td class="textR">送机日期：</td>
          <td class="textL"><?php echo tep_draw_input_num_en_field('departure_date', tep_get_date_disp($orders_history['departure_date']), 'mytype="After" style="width:110px; ime-mode:disabled; ' . $inputclass2 . '" class="text_time" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ','text',false); ?></td>
          </tr>
          <tr>
          <td class="textR">航空公司：</td>
          <td class="textL"><?php echo tep_draw_input_field('airline_name', ($orders_history['airline_name']), 'mytype="Before" style="' . $inputclass1 . '" ','text',false); ?></td>
          <td class="textR">航空公司：</td>
          <td class="textL"><?php echo tep_draw_input_field('airline_name_departure', ($orders_history['airline_name_departure']), 'mytype="After"  style="' . $inputclass2 . '"','text',false); ?></td>
          </tr>
          <tr>
          <td class="textR">接机航班：</td>
          <td class="textL"><?php echo tep_draw_input_field('flight_no', ($orders_history['flight_no']), 'mytype="Before" mytext="自行入住酒店"  id="flight_Before_' . $i . '" style="' . $inputclass1 . '"','text',false); ?></td>
          <td class="textR">送机航班：</td>
          <td class="textL"><?php echo tep_draw_input_field('flight_no_departure', ($orders_history['flight_no_departure']), 'mytype="After" mytext="自行离团"  id="flight_After_' . $i . '" style="' . $inputclass2 . '"','text',false); ?></td>
          </tr>
          <tr>
          <td class="textR">接机机场：</td>
          <td class="textL"><?php echo tep_draw_input_field('airport_name', ($orders_history['airport_name']), 'mytype="Before"  style="' . $inputclass1 . '"','text',false); ?></td>
          <td class="textR">送机机场：</td>
          <td class="textL"><?php echo tep_draw_input_field('airport_name_departure', ($orders_history['airport_name_departure']), 'mytype="After"  style="' . $inputclass2 . '"','text',false); ?></td>
          </tr>
          <tr>
          <td class="textR">到达时间：</td>
          <td class="textL h40"><?php unset($GLOBALS['arrival_time']); echo tep_draw_input_num_en_field('arrival_time', $orders_history['arrival_time'], 'mytype="Before" class="text_hour"  style="' . $inputclass1 . '"','text',false); ?><br/> (HH:MM) e.g. 15:30 pm</td>
          <td class="textR">出发时间：</td>
          <td class="textL h40"><?php unset($GLOBALS['departure_time']); echo tep_draw_input_num_en_field('departure_time', $orders_history['departure_time'], 'mytype="After" class="text_hour"  style="' . $inputclass2 . '"','text',false); ?><br/> (HH:MM) e.g. 09:30 am</td>
          </tr>
		</table>
        <div style="text-align:center">
		<input name="orders_products_id" type="hidden" value="<?php echo $orders_history['orders_products_id'];?>" />
		<input type="submit" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/updatebtn.gif);border:0;width:83px;height:23px;text-indent:20px;cursor:pointer;" value="更 新" />
		</div>
		<div><?php # echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE);?></div>
      </div>
    </div>
	</form>
				  
				  <?php
			}// end of while loop
		}//end of if
								}
							}
							echo '</div>';
							echo db_to_html(ob_get_clean());

}//end of hiding 
}

ob_start();
?>
<script type="text/javascript">
//修改参团人员姓名与性别的JS by lwkai 2012-08-13 add
function ajaxModifyName(index,num){
	if (isNaN(index) || isNaN(num)){
		return;
	}
	var lastName = jQuery('#lastname_' + index + '_' + num).val();
	var firstname = jQuery('#firstname_' + index + '_' + num).val();
	var gender = jQuery('#guestSex_' + index + '_' + num).val();
	var orders_id = jQuery('#order_' + index + '_' + num).val();
	var products_id = jQuery('#product_' + index + '_' + num).val();
	var guestchildage = jQuery('#guestchildage_' + index + '_' + num).val();
	if (lastName == '' || firstname == '') {
		alert('姓和名未填写完整!');
		return;
	}
	if (jQuery('guestchildage_' + index + '_' + num).length > 0 && guestchildage=='') {
		alert('请选择出生日期!');
		return;
	}
	var data = {'orders_id':orders_id,'products_id':products_id,'lastName':lastName,'firstname':firstname,'gender':gender,'index':index,'num':num,'guestchildage':guestchildage};
	jQuery.post('<?php echo tep_href_link('ajax_modify_orders_username.php', '', 'SSL');?>',data,function(r){
		
		if(r.error == 'false'){
			jQuery('#Modify_' + index + '_' + num).hide();
			jQuery('#text_' + index + '_' + num).html(r.html);
			jQuery('#guest_' + index + '_' + num).show();
		} else {
			alert(r.error);
		}
	},'json');
}

</script>
  </div>
</div>
<?php
echo db_to_html(ob_get_clean());
//产品清单start {
ob_start();
?>
<div class="userDebit">
  <div class="tit2">产品清单</div>
  <div class="con2">
    <table width="100%" bgcolor="#dcdcdc" cellpadding="0" cellspacing="1" border="0">
      <tr>
        <td width="55%" height="33" align="center" bgcolor="#FFFFFF"><strong class="color_blue">产品名称</strong></td>
        <td width="15%" align="center" bgcolor="#FFFFFF"><strong class="color_blue">出团日期</strong></td>
        <td width="15%" align="center" bgcolor="#FFFFFF"><strong class="color_blue">支付状态</strong></td>
        <td width="15%" align="center" bgcolor="#FFFFFF"><strong class="color_blue">小计</strong></td>
      </tr>
      <?php for($i=0, $n=sizeof($order->products); $i<$n; $i++){ 
      if($order->products[$i]['is_hide'] == 0){
      ?>
	  <tr>
      	<td align="left" bgcolor="#FFFFFF" class="padding8"><?php echo '<div>'.($i+1);?>.<?php echo $order->products[$i]['name'] . '</div>';
      	if (is_array($order->products[$i]['attributes']) == true ) { 
											foreach ($order->products[$i]['attributes'] as $key) {
												// 价为0 则不显示
												if ((int)$key['price'] > 0) {
													echo $key['option'] . '：' .$key['value'] . '&nbsp;' . $key['prefix'] . '&nbsp;' . $currencies->display_price($key['price'],$order->products[$i]['tax']) . '<br/>';
												}
											}
										}
										#print_r($order->products[$i]);
										#format_out_roomattributes_1($order->products[$i]['products_room_info'],(int)$order->products[$i]['total_room_adult_child_info'])
										echo format_out_roomattributes_1($order->products[$i]['products_room_info'],(int)$order->products[$i]['total_room_adult_child_info']) . '<br/>';
										
      	?></td>
      	<td align="center" bgcolor="#FFFFFF" class="padding8"><?php echo $order->products[$i]['products_departure_date'];?></td>
      	<td align="center" bgcolor="#FFFFFF" class="padding8"><?php echo tep_get_orders_products_payment_status_name($order->products[$i]['orders_products_payment_status']);?></td>
      	<td align="center" bgcolor="#FFFFFF" class="padding8"><?php echo $currencies->format($order->products[$i]['final_price'],true);?></td>
      	</tr>
		<?php }}?>
      </table>
  </div>
</div>
<?php
echo db_to_html(ob_get_clean());
//产品清单end }

// 用户留言 start {
	$order_message_query = tep_db_query("select * from orders_message where orders_id = '" . (int)$_GET['order_id'] . "' order by id asc");


?>
<div class="userDebit">
	<div class="tit" title="<?php echo db_to_html('点击展开/隐藏该项')?>"><span><?php echo db_to_html('我的留言')?></span></div>
    <div class="con4">
    <div id="orders_status_update_history3" style="display:;padding:16px;">
      <table border="0" width="100%" cellspacing="0" cellpadding="2" class="ui-refresh-uhlog" style="table-layout:fixed;" id="msgTable">
      <?php
	  
      while ($statuses = tep_db_fetch_array($order_message_query))
      {
      	echo '              <tr>' . "\n" .
      	'                <td class="main ui-border-bottom" valign="top" width="80%" style="word-wrap:break-word;word-break:break-all;">' . db_to_html(tep_db_output($statuses['message'])) . '</td>' . "\n" .
      	'                <td class="main ui-border-bottom" valign="top" width="20%">' . db_to_html($statuses['addtime']) .'</td>' . "\n" .
      	'              </tr>' . "\n";
      }

	  ?>
      </table>
    </div>
  </div>
</div>
<?php

//用户留言 end }

//订单状态更新记录{

$statuses_query = tep_db_query("select os.orders_status_id, os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' and osh.customer_notified = '1' order by osh.date_added");

$rowsNum = tep_db_num_rows($statuses_query);
if((int)$rowsNum){
?>
<div class="userDebit">
  <div class="tit updateOrder" title="<?php echo db_to_html('点击展开/隐藏该项')?>"><span><?php echo db_to_html('订单状态更新记录').'[<span style="color:red">'.$rowsNum.'</span>]'?></span></div>
  <div class="con4">
    <div id="orders_status_update_history2" style="display:none;">
      <table border="0" width="100%" cellspacing="0" cellpadding="2" class="ui-refresh-uhlog">
      <?php
      while ($statuses = tep_db_fetch_array($statuses_query))
      {
      	echo '              <tr>' . "\n" .
      	'                <td class="main" valign="top" width="10%">' . tep_date_short($statuses['date_added']) . '</td>' . "\n" .
      	'                <td class="main" valign="top" width="15%">' . db_to_html(tep_get_orders_status_name_from_status_id(intval($statuses['orders_status_id']))) .'</td>' . "\n" .
      	'                <td class="main" valign="top" width="75%">' . ((empty($statuses['comments']) || $statuses['orders_status_id']=="100006") ? '&nbsp;' : nl2br(tep_db_prepare_input(db_to_html($statuses['comments'])))) . '</td>' . "\n" .
      	'              </tr>' . "\n";
      }

	  ?>
      </table>
    </div>
  </div>
</div>
<?php } //订单状态更新记录}?>
<?php
if(!(int)$order->info['orders_paid'] && strpos(json_encode($order->totals), '"class":"ot_coupon"') === false && strpos(json_encode($order->totals), '"class":"ot_redemptions"') === false && $is_companion != true){
//Coupon Code折扣券start {
//by lwkai add 2012-10-14 19:13 当钱已经付完之后，则不允许再用优惠码与积分 添加条件判断
if($need_pay>0 && $enable_coupon_points === true && $order->info['disabled_coupon_point']!="1"){
	ob_start();
?>
<div class="userDebit">
 <div class="tit">
 <span>使用积分或优惠券</span>
</div>

<div class="con4 ui-fcc">

<form id="formCouponCode" action="" target="_self" method="post" enctype="multipart/form-data" onsubmit="ajaxSubmitCouponCode(); return false;" >
 <p>Coupon Code（优惠码）：
 <?= tep_draw_input_num_en_field('acc_gv_redeem_code', '', 'id="gv_redeem_code" title="如果您有优惠码可在这里填写！" autocomplete="off" ');?>
 <input type="submit" value="确定" class="fcc-sbtn" />
 <input name="order_id" type="hidden" value="<?= (int)$_GET['order_id']?>" />
 <input name="action" type="hidden" value="submitCouponCode" />
 </p>
</form>
<p style="color:#1653A8;">
或使用积分抵扣
<?php 
echo db_to_html(ob_get_clean());
//积分兑换按钮
points_selection();

ob_start();
?>
</p>
<form name="formPoint" id="formPoint" action="" target="_self" method="post" enctype="multipart/form-data" onsubmit="ajaxSubmitPoint(); return false;" >
<p>
 <?= tep_draw_input_num_en_field('_customer_shopping_points_spending', '', 'id="customer_shopping_points_spending" autocomplete="off" ');?>
 <input name="action" type="hidden" value="submitPoint" />
 <input type="submit" value="确定" class="fcc-sbtn" />
 <input name="order_id" type="hidden" value="<?= (int)$_GET['order_id']?>" />
 <p>
</form>
 </div>

</div>
<script type="text/javascript">
function ajaxSubmitCouponCode(){
	if(jQuery('#gv_redeem_code').val().length<1){
		alert('请输入优惠码！');
		return 0;
	}
	var _data = get_form_data('formCouponCode', '');
	_url = '<?php echo tep_href_link_noseo('account_history_info.php', 'order_id='.(int)$_GET['order_id'], 'SSL');?>';
	jQuery.ajax({type:'POST',url:_url,dataType:"script", cache:false, data:_data,success:function(html){
		if(html=='true'){
			alert('恭喜！优惠券兑换成功！');
			window.location.reload();
		}else{
			alert(html);
		}
	}});
}
function ajaxSubmitPoint(){
	if(jQuery('#customer_shopping_points_spending').val()<1){
		alert('请输入要兑换的积分数！');
		return 0;
	}
	var _data = get_form_data('formPoint', '');
	_url = '<?php echo tep_href_link_noseo('account_history_info.php', 'order_id='.(int)$_GET['order_id'], 'SSL');?>';
	jQuery.ajax({type:'POST',url:_url,dataType:"script", cache:false, data:_data,success:function(html){
		if(html=='true'){
			alert('恭喜！积分兑换成功！');
			window.location.reload();
		}else{
			alert(html);
		}
	}});
}
function submitFunctionPoint(reedmpoinvalue) {
   point_submitter = 1;
   document.formPoint._customer_shopping_points_spending.value = reedmpoinvalue;
}

</script>
<?php
echo db_to_html(ob_get_clean());

}
//Coupon Code折扣券end }
}
?>
<div class="userDebit">
<?php // 留言开始 by lwkai  start { ?>
<script type="text/javascript">
function ajaxSubmitMessage(){
	var message = jQuery('#message_txt').val();
	var orders_id = parseInt(jQuery("#orders_id").val(),10);
	if (orders_id == 0) {
		alert("<?php echo db_to_html('留言系统出错！请刷新页面重试！');?>");
		return false;
	}
	if (message == '') {
		alert('<?php echo db_to_html("您还未填写留言内容呢！")?>');
		return false;	
	}
	var date = new Date();
	var date_string = date.getYear() + '-' + (date.getMonth()+1) + '-' + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" +  date.getSeconds();

	var data = {'orders_id':orders_id,'message':message};
	jQuery('#submit_btn').attr('disabled',true);
	jQuery.post('<?php echo tep_href_link('ajax_save_orders_message.php', '', 'SSL');?>',data,function(r){
		if (r == '<?php echo db_to_html('提交成功！')?>') {
			jQuery('#message_txt').val('');
			var text = '<tr><td class="main ui-border-bottom" valign="top" width="80%" style="word-wrap:break-word;word-break:break-all;">' + (message.replace('<','&lt;').replace('>','&gt;')) + '</td><td class="main ui-border-bottom" valign="top" width="20%">' + date_string + '</td></tr>';
			jQuery('#msgTable').append(text);
		}
		jQuery('#submit_btn').attr('disabled',false);
		alert(r);
	},'html');
	return false;
}
</script>
<div class="fl">
<span class="ui-msg-title"><?php echo db_to_html('留言：');?></span>
<?php echo tep_draw_form('mymessage', '#','post', ' onSubmit="return ajaxSubmitMessage()"');?>
<?php echo tep_draw_textarea_field('message', '', 80, 3,'','id="message_txt"') ;
echo tep_draw_hidden_field('orders_id', $_GET['order_id'],'id="orders_id"');

?><br/>
<input type="submit" id="submit_btn" class="ui-msg-btn" value="<?php echo db_to_html('提交留言');?>" />
<?php echo tep_draw_form_close(); ?>
</div>
<?php // } 留言结束 by lwkai end ?>
<div class="counts">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
	echo db_to_html('              <tr>' . "\n" .
	'                <td class="main" align="right" width="100%">' . ($order->totals[$i]['title']) . '</td>' . "\n" .
	'                <td class="main" align="right">' . ($order->totals[$i]['text']) . '</td>' . "\n" .
	'              </tr>' . "\n");

}


//已付款金额，未付款金额{
			
?>
                    <tr>
                      <td class="main" align="right" width="100%"><b><?php echo db_to_html("已付款:");?></b></td>
                      <td class="main" align="right"><b style="color:#060"><?php echo $currencies->format($order->info['orders_paid'],true);?></b></td>
                    </tr>
                    <?php 
			//$need_pay = $otTotal - $order->info['orders_paid'];
			if($need_pay>0){
?>
                    <tr>
                      <td class="main" align="right" width="100%"><?php echo db_to_html("还需付款:");?></td>
                      <td class="main" align="right"><b style="color:#F00"><?php echo $currencies->format($need_pay,true);?></b></td>
                    </tr>
                    <?php
			}
//已付款金额，未付款金额}
			?>
                  </table><br/>
<?php
//如果是结伴同游则不显示
// $check_sql = tep_db_query('SELECT orders_id FROM `orders_travel_companion` WHERE orders_id="'.(int)$HTTP_GET_VARS['order_id'].'" limit 1 ');
// $check_rows = tep_db_fetch_array($check_sql);
if($need_pay > 0 && $order->info['orders_status_id'] != 6){
	if(!$is_companion){
		?>
		<a href="<?php echo tep_href_link('account_history_payment_method.php','order_id='.$_GET['order_id'],'SSL')?>" class="btn qzf"><span></span><?php echo db_to_html("去支付");?></a>
	<?php }else{?>
		<a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.$_GET['order_id'],'SSL')?>" class="btn qzf"><span></span><?php echo db_to_html("结伴同游付款");?></a>
	<?php }
}?>

</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		jQuery('.Flight').click(function(e) {
			if(jQuery(this).parent().parent().find('div.flight_contraction').css('display') == 'none') {
				jQuery(this).parent().parent().find('div.flight_contraction').slideDown('slow');
				jQuery(this).children().eq(1).text('<?php echo db_to_html('隐藏')?>');
				jQuery(this).children().eq(0).removeClass('yc');
			} else {
				jQuery(this).parent().parent().find('div.flight_contraction').slideUp("slow");
				jQuery(this).children().eq(1).text('<?php echo db_to_html('展开')?>');
				jQuery(this).children().eq(0).addClass('yc');
			}
			//jQuery(this).parent().next().toggle();
			return false;
		});
		jQuery('.updateOrder').click(function(){
			if(jQuery("#orders_status_update_history2").css('display') == 'none') {
				jQuery("#orders_status_update_history2").slideDown('slow');	
			} else {
				jQuery('#orders_status_update_history2').slideUp('slow');	
			}
		});
		//var $uhlogTr = jQuery("table.ui-refresh-uhlog tr td").addClass("ui-border-bottom");
		//jQuery("table.ui-refresh-uhlog tr:last td").removeClass("ui-border-bottom");
		
	});
	</script>

<?php //echo tep_get_design_body_footer();?>