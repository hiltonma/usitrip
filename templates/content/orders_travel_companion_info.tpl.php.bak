<?php /*echo tep_get_design_body_header(HEADING_TITLE,1);*/ ?>
<script type="text/javascript">
//显示编辑框
function show_edit_guest_name(travel_companion_id){
	var ren_names = document.getElementById('ren_names_' +travel_companion_id);
	var ren_names_field = document.getElementById('ren_names_field_' +travel_companion_id);
	var close_id = document.getElementById('close_a_' +travel_companion_id);
	if(ren_names.style.display!="none"){
		ren_names.style.display="none";
		ren_names_field.style.display="";
		close_id.style.display="none";
	}
}

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
//提交修改名字
function guest_name_submit(travel_companion_id, form_id){
	var loading_img = document.getElementById('loading_img_'+travel_companion_id);
	loading_img.style.display = '';
	var ren_names = document.getElementById('ren_names_' +travel_companion_id);
	var ren_names_field = document.getElementById('ren_names_field_' +travel_companion_id);
	var close_id = document.getElementById('close_a_' +travel_companion_id);

	var form_orders = document.getElementById(form_id);
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_orders_travel_companion_info.php','ajax_action=1&travel_companion_id=','SSL')) ?>")+ travel_companion_id;
	var aparams=new Array();

	for(i=0; i<form_orders.elements.length; i++){

		if( ((form_orders.elements[i].type=='checkbox' || form_orders.elements[i].type=='radio') && form_orders.elements[i].checked==true) || (form_orders.elements[i].type!='checkbox' && form_orders.elements[i].type!='radio' )){
			var sparam=encodeURIComponent(form_orders.elements[i].name);
			sparam+="=";
			sparam+=encodeURIComponent(form_orders.elements[i].value);
			aparams.push(sparam);

		}
	}

	sparam+= '&ajax_send=true';
	aparams.push(sparam);

	var post_str=aparams.join("&");		//使用&将各个元素连接

	ajax.open("post", url, true);
	//定义传输的文件HTTP头信息
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send(post_str);

	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			loading_img.style.display = "none";
			ren_names.innerHTML = ajax.responseText;
			ren_names.style.display = "";
			close_id.style.display = "";
			ren_names_field.style.display = "none";
		}
	}

}
</script>

 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<?php
							if ($messageStack->size('travel_companion') > 0) {
							?>
							<tr>
							<td align="left">
							<?php echo $messageStack->output('travel_companion'); 	?>
							</td>
							</tr>
							<?php
							}
							?>
							  <tr>
								<td class="main">
    <table border="0" width="100%"  cellspacing="0" cellpadding="0">


<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
	table_image_border_top(false, false, $header_text);
}
//tom added
$result_echo_ss=tep_get_orders_status_name($HTTP_GET_VARS['order_id']);

// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" style="margin-top:10px">
          <tr>
          <td class="main" colspan="2" style="border:1px solid #FFC75F;padding:20px 15px;background-color:#FFFEE9;"><?php echo db_to_html('订单编号：')?><strong style="font-size:14px;color:#f60;"><?php echo $HTTP_GET_VARS['order_id']?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo db_to_html('状态：') . ' <strong style="color:#f60;font-size:14px;">' .db_to_html($result_echo_ss) . '</strong>'; ?><?php /*<a class="print_btn" href="javascript:popupWindow1('<?php echo(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_ORDERS_PRINTABLE)?>?<?php echo (tep_get_all_get_params(array('order_id')) . 'order_id=' . $HTTP_GET_VARS['order_id']) ?>')"><span></span><?php echo db_to_html("打印订单")?></a>*/ // 隐藏打印订单按钮 ?>
		  
	<?php
	if((int)$Admin->login_id){	//如果是管理登录则提示自动登录网址，以便给客人发过去！
	?>
	<iframe style="display:block; border:none;" width="720" height="85" src="<?php echo tep_href_link('auto_login_url.php','order_id='.(int)$_GET['order_id']);?>"></iframe>
	<?php
	}
	?>
		  </td>
          </tr>
          <tr><td height="15" colspan="2"></td></tr>
          <tr><td colspan="2" style="height:30px;line-height:30px;border:1px solid #D9D9D9;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/users.gif) repeat-x left -243px;font-size:14px;font-weight:bold;padding-left:20px;color:#1653a8;"><?php echo db_to_html('订购人信息')?></td></tr>
          <tr><td colspan="2" style="border:1px solid #D9D9D9;border-top:0;padding:25px 16px;"><?php
          //列出订购人信息 start {
          echo db_to_html("姓&nbsp;名:");
          echo db_to_html(tep_db_output($order->customer['name']));
          echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
          echo db_to_html("电&nbsp;话:");
          echo db_to_html(tep_db_output($order->customer['telephone']));
          echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
          echo db_to_html("电子邮箱:");
          echo db_to_html(tep_db_output($order->customer['email_address']));
          //列出订购人信息 end }
						?></td></tr>
          <tr><td height="8"></td></tr>
          <tr>
            <td colspan="2" style="height:30px;line-height:30px;border:1px solid #D9D9D9;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/users.gif) repeat-x left -243px;font-size:14px;font-weight:bold;padding-left:20px;color:#1653a8;"><?php echo db_to_html('订单详情')?>&nbsp;&nbsp;<?php #echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?><span style="float:right;color:#4d4d4d;font-size:12px;font-weight:normal;padding-right:10px;"><?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?></span></td>
          </tr>
        
<?php
//取消递送地址
$order->delivery = false;
if ($order->delivery != false) {
?>
            <tr><td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo HEADING_DELIVERY_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td class="main"><?php echo db_to_html(tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />')); ?></td>

              </tr>
<?php
if (tep_not_null($order->info['shipping_method'])) {
?>
              <tr>
                <td class="main"><b><?php echo HEADING_SHIPPING_METHOD; ?></b></td>
              </tr>
              <tr>
                <td class="main"><?php echo db_to_html($order->info['shipping_method']); ?></td>
              </tr>
<?php
}
?>
            </table></td></tr>
<?php
}
?>
            <tr><td style="border:1px solid #D9D9D9;border-top:0;padding:25px 16px 0;" width="<?php echo (($order->delivery != false) ? '70%' : '100%'); ?>" valign="top" cellpadding="2">
<?php
if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
                    <td class="main" colspan="2"><b><?php echo HEADING_PRODUCTS; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr></table>
<?php
} else {
?>
                  <!--<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
                    <td class="main" colspan="3"><b><?php echo HEADING_PRODUCTS; ?></b></td>
                  </tr></table>-->
<?php
}



//订单行程列表

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	if($order->products[$i]['is_hide']==0){
	$eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$HTTP_GET_VARS['order_id'] . "' and products_id=".$order->products[$i]['id']." ");
	$eticket_result = tep_db_fetch_array($eticket_query);
	if($eticket_result['confirmed']==1){
		$etickitlink = db_to_html('<a class="print_btn" href="' . tep_href_link('eticket.php', 'order_id=' . $HTTP_GET_VARS['order_id'].'&pid='.$order->products[$i]['id'].'&i='.$i, 'NONSSL') . '" target="_blank"><span></span>打印电子参团凭证</a>');#tep_template_image_button('eticket.gif', SMALL_IMAGE_BUTTON_EDIT)
	}else{
		//$etickitlink = '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $HTTP_GET_VARS['order_id'].'&pid='.$order->products[$i]['id'].'&i='.$i.'&edit=true', 'SSL') . '">' . tep_template_image_button('update.gif', IMAGE_BUTTON_UPDATE) . '</a>';
	}
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>' . "\n" .
	'            <td colspan="2" style="border-bottom:1px solid #e3dee3;font-size:14px;font-weight:bold;padding:13px 0;color:#f60;">' . ($i+1) .
	db_to_html('、' . $order->products[$i]['name']) . $etickitlink . '<td></tr>';//原来$+1的地方用的是这个$order->products[$i]['qty']
	echo '<tr><td colspan="2" class="orders_travel_companion_info"><p style="border-bottom:1px dotted #d3d3d3;line-height:26px;padding:15px 0;position: relative;min-height:120px;_height:120px;">';
	echo db_to_html('旅游团号：' . $order->products[$i]['model'] . '<br/>');
	if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
		for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			echo db_to_html('-' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '<br/>');
		}
	}

	echo db_to_html("出发时间:".$order->products[$i]['products_departure_date'].$order->products[$i]['products_departure_time']);
	if ($order->products[$i]['products_departure_location'] != '') {
		echo db_to_html("<br/>乘车地点：" . $order->products[$i]['products_departure_location']);
	}
	echo db_to_html("<br/>出发城市：" . tep_get_product_departure_city($order->products[$i]['id']) . '<br/>');
	
	
	if($order->products[$i]['products_room_info']!=''){
		$roomInfoString = format_out_roomattributes_1($order->products[$i]['products_room_info'], (int)$order->products[$i]['total_room_adult_child_info']);
		echo db_to_html($roomInfoString) . '<br/>';
	}

	echo db_to_html('房间总费用：' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']));
	echo '<em>' . db_to_html('行程费用') . '<br/><span>' .  $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</span><br/>' . db_to_html('支付状态') . '<br/><span>' . db_to_html(tep_get_orders_products_payment_status_name($order->products[$i]['orders_products_payment_status'])) . '</span></em>';
	echo '</p></td></tr>' . "\n";

	if (sizeof($order->info['tax_groups']) > 1) {
		echo '            <tr><td colspan="2" class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td></tr>' . "\n";
	}



	#echo '            <td class="main" nowrap="nowrap" align="right" valign="top">' .$etickitlink.'&nbsp;' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . '          </tr>' . "\n";

	$orders_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . tep_db_input($_GET['order_id']) . "' and products_id = ".$order->products[$i]['id']."");
	if (tep_db_num_rows($orders_history_query)){
		ob_start();
		while ($orders_history = tep_db_fetch_array($orders_history_query)){
			echo '<tr>
									<td class="main" colspan="2">
									<div class="information"><span class="float_r Flight "><em></em><span>隐藏</span></span>      <div class="float_l"><strong>航班信息</strong>&nbsp;&nbsp;建议您在确保此行程订购成功之后再购买机票&nbsp;&nbsp;</div>
			</div>
									<div class="flight flight_contraction">
									  <div class="box_warp">
										<ul>
										  <li class="textR">接机航班：</li>
										  <li class="textL">'. ($orders_history['flight_no']) .'</li>
										  <li class="textR">送机航班：</li>
										  <li class="textL">'. ($orders_history['flight_no_departure']) .'</li>
										  <li class="textR">航空公司：</li>
										  <li class="textL">' . ($orders_history['airline_name']) . '</li>
										  <li class="textR">航空公司：</li>
										  <li class="textL">'. ($orders_history['airline_name_departure']) .'</li>
										  <li class="textR">接机机场：</li>
										  <li class="textL">'. ($orders_history['airport_name']) .'</li>
										  <li class="textR">送机机场：</li>
										  <li class="textL">'. ($orders_history['airport_name_departure']) .'</li>
										  <li class="textR">接机日期：</li>
										  <li class="textL">'. (tep_get_date_disp($orders_history['arrival_date'])) .'</li>
										  <li class="textR">送机日期：</li>
										  <li class="textL">'. (tep_get_date_disp($orders_history['departure_date'])) .'</li>
										  <li class="textR">到达时间：</li>
										  <li class="textL">'. ($orders_history['arrival_time']) .'</li>
										  <li class="textR">起飞时间：</li>
										  <li class="textL">'. ($orders_history['departure_time']) .'</li>
										</ul>
									  </div>
									</div>';

			echo '
									</td>
								 </tr>';
		}// end of while loop
		echo db_to_html(ob_get_clean());
	}//end of if
	//结伴同游客户姓名
	echo '<tr><td colspan="3">';
?>

<?php

//取得结伴同游订单
$sql_str = 'SELECT * FROM `orders_travel_companion` WHERE orders_products_id ="'.(int)$order->products[$i]['orders_products_id'].'" order by orders_travel_companion_id desc ';

$orders_status_id = $order->info['orders_status_id'];

$sql = tep_db_query($sql_str);
$rows = tep_db_fetch_array($sql);
if((int)$rows['orders_travel_companion_id']){
?>
<form name="form_pay_<?php echo $order->products[$i]['orders_products_id']?>" id="form_pay_<?php echo $order->products[$i]['orders_products_id']?>" action="<?php echo tep_href_link('travel_companion_pay.php','','SSL')?>" method="post">
<input name="order_id" type="hidden" value="<?php echo (int)$_GET['order_id'];?>" />
<div class="information" style="margin-top:8px;"><strong><?php echo db_to_html('结伴同游子订单信息');?></strong></div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#dcdcdc" style="margin-bottom:8px">
  <tr>
	<td height="33" align="center" background="/image/nav/user_bg11.gif"><strong><?php echo db_to_html('选择')?></strong></td>
    <td align="left" background="/image/nav/user_bg11.gif"><strong><?php echo db_to_html('参团人姓名')?></strong></td>
    <td align="center" background="/image/nav/user_bg11.gif"><strong><?php echo db_to_html('应付款')?></strong></td>
    <td align="center" background="/image/nav/user_bg11.gif"><strong><?php echo db_to_html('已付')?></strong></td>
    <td align="center" background="/image/nav/user_bg11.gif"><strong><?php echo db_to_html('付款状态')?></strong></td>
  </tr>
<?php
$display_submit = false;
do{
	$tr_style="";

	//Disable checkbox if once pament done
	if((int)$rows['payment_customers_id']>0){
		$chk_disabled='';
		//$chk_disabled='disabled="disabled"';
	}else{
		$chk_disabled='';
	}

	//付款id的单选或多选处理
	$checkbox_radio='<input name="orders_travel_companion_ids[]" type="checkbox" value="'.(int)$rows['orders_travel_companion_id'].'" '.$chk_disabled.' />';
	if($rows['customers_id']==$customer_id){
		$checkbox_radio=' <input name="orders_travel_companion_ids[]" type="checkbox" value="'.(int)$rows['orders_travel_companion_id'].'" checked="checked" '.$chk_disabled.' /> ';
		$tr_style="background-color: #FEE9EB";
	}elseif($rows['payment_customers_id']==$customer_id){
		$tr_style="background-color:#fff";
		$checkbox_radio='<input name="orders_travel_companion_ids[]" type="checkbox" value="'.(int)$rows['orders_travel_companion_id'].'" checked="checked" '.$chk_disabled.' />';
	}

	//是否启用付款按钮
	if(in_array((int)$rows['orders_travel_companion_status'],array(0,3)) ){	//0为未付，3为已经部分付款
		$display_submit = true;
	}else{
		$checkbox_radio ='<input name="" type="checkbox" disabled="disabled" />';
	}
?>
  <tr style="<?php echo $tr_style ?>">
    <td align="center" height="33"><?php echo $checkbox_radio?></td>
	<td align="left" nowrap="nowrap" style="padding:5px;">
	<div id="ren_names_<?= (int)$rows['orders_travel_companion_id']?>" style="width:260px; float:left"><?php echo db_to_html(tep_filter_guest_chinese_name(tep_db_output($rows['guest_name'])))?></div>

	<?php
	//在付款人是当前用户的情况下可以改名，或付款人未知的情况，当前状态必须是未付款
	if(($rows['payment_customers_id']==$customer_id || $rows['customers_id']==$customer_id || !(int)$rows['payment_customers_id']) && $rows['orders_travel_companion_status'] <1 ){

		$tmp_names = explode( '[', db_to_html(tep_db_output($rows['guest_name'])));
		$js_ch_name = trim($tmp_names[0]);

		$en_name = explode( ' ', $tmp_names[1]);
		$js_en_name_xin = trim($en_name[0]);
		$js_en_name_min = substr(trim($en_name[1]),0,strlen($en_name[1])-1 );
	?>

	<span id="ren_names_field_<?= (int)$rows['orders_travel_companion_id']?>" style="display:<?= 'none'?>">
	<?php echo db_to_html("中文名 "). tep_draw_input_field('js_ch_name['.(int)$rows['orders_travel_companion_id'].']', $js_ch_name, 'id="js_ch_name_'.(int)$rows['orders_travel_companion_id'].'" size="10" ');?>
	<?php echo db_to_html("护照英文 姓"). tep_draw_input_field('js_en_name_xin['.(int)$rows['orders_travel_companion_id'].']', $js_en_name_xin, 'id="js_en_name_xin_'.(int)$rows['orders_travel_companion_id'].'" size="4" style="ime-mode: disabled;" ');?>
	<?php echo db_to_html("名 "). tep_draw_input_field('js_en_name_min['.(int)$rows['orders_travel_companion_id'].']', $js_en_name_min, 'id="js_en_name_min_'.(int)$rows['orders_travel_companion_id'].'" size="8" style="ime-mode: disabled;" ');?>

	<a class="sp1" href="javascript:void(0);" onclick="guest_name_submit(<?= (int)$rows['orders_travel_companion_id']?>,&quot;form_pay_<?php echo $order->products[$i]['orders_products_id']?>&quot; )"><?php echo db_to_html('确定')?></a>	</span>

	<img id="loading_img_<?= (int)$rows['orders_travel_companion_id']?>" style="display:<?php echo 'none'?>" src="image/loading.gif" align="absmiddle" />
	<a class="sp1" id="close_a_<?= (int)$rows['orders_travel_companion_id']?>" href="javascript:void(0);" onclick="show_edit_guest_name(<?= (int)$rows['orders_travel_companion_id']?>)"><?php echo db_to_html('改名')?></a>

	<?php
	//如果小孩就显示
	if($rows['is_child']=='true' || tep_not_null($rows['date_of_birth'])){
		echo db_to_html('&nbsp;&nbsp;(小孩)生出日期 ').strip_tags($rows['date_of_birth']);
	}
	?>

	<?php
	}
	?>	</td>
    <td align="center"><?php echo $currencies->format(db_to_html(tep_db_output($rows['payables'])))?></td>
    <td align="center"><?php echo $currencies->format(db_to_html(tep_db_output($rows['paid'])))?></td>
    <td align="center">
	<?php
	echo db_to_html(get_travel_companion_status((int)$rows['orders_travel_companion_status']));
	?>
	</td>
  </tr>

<?php
}while($rows = tep_db_fetch_array($sql));
?>
  <tr><td colspan="5" align="center" style="background-color:#fff;">
  	<?php if($display_submit==true && $orders_status_id!='6'){?>
		<input name="smbmit" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/checkoutbtn.jpg);border:0;height:38px;line-height:38px;margin:3px;width:162px;color:#fff;cursor:pointer;font-size:16px;font-weight:bold; letter-spacing:10px; font-family:<?php echo db_to_html('黑体')?>;text-shadow:0 1px 1px #111;" type="submit" id="smbmit" value="<?php echo db_to_html('在线付款')?>" /><span></span>
	<?php }elseif($orders_status_id=='6'){
		echo '<input name="smbmit" type="submit" style="background:url('.DIR_WS_TEMPLATE_IMAGES.'nav/checkoutbtn_disable.jpg);border:0;height:38px;line-height:38px;margin:3px;width:162px;color:#fff;cursor:default;font-size:16px;font-weight:bold; letter-spacing:10px; font-family:' . db_to_html('黑体') . ';text-shadow:0 1px 1px #111;" disabled="disabled" id="smbmit" value="'.db_to_html('在线付款').'" /> ['.db_to_html('订单已取消').']';
	}?>
  </td>
  </tr>
</table>
</form>
<?php
}

?>
<?php
echo '</td></tr>';
//结伴同游客户姓名 end

#echo '</table></td></tr> </table></td></tr>';

}//end of for for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
//订单行程列表end
}//end with hide
?>
                </table></td>
              </tr>
            </table></td>
          </tr>


        </table></td>
      </tr>


     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td class="main"><?php /*<b><?php echo HEADING_ORDER_HISTORY; ?></b>*/?>
        
		<?php //订单状态更新记录{

$statuses_query = tep_db_query("select os.orders_status_id, os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$HTTP_GET_VARS['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' and osh.customer_notified = '1' order by osh.date_added");

$rowsNum = tep_db_num_rows($statuses_query);
if((int)$rowsNum){
?>
<div class="userDebit">
  <div class="tit updateOrder" title="<?php echo db_to_html('点击展开/隐藏该项')?>"><span><?php echo db_to_html('订单状态更新记录').'[<span style="color:red">'.$rowsNum.'</span>]'?></span></div>
  <div class="con4">
    <div id="orders_status_update_history2" style="display:none;">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <?php
      while ($statuses = tep_db_fetch_array($statuses_query))
      {
      	echo '              <tr>' . "\n" .
      	'                <td class="main" valign="top" width="10%">' . tep_date_short($statuses['date_added']) . '</td>' . "\n" .
      	'                <td class="main" valign="top" width="15%">' . db_to_html(order_status_replace($statuses['orders_status_name'])) . '</td>' . "\n" .
      	'                <td class="main" valign="top" width="75%">' . ((empty($statuses['comments']) || $statuses['orders_status_id']=="100006") ? '&nbsp;' : nl2br(tep_db_prepare_input(db_to_html($statuses['comments'])))) . '</td>' . "\n" .
      	'              </tr>' . "\n";
      }

	  ?>
      </table>
    </div>
  </div>
</div>
<?php } //订单状态更新记录}?>
		
		</td></tr>
		</table>
        <?php #展开隐藏航班信息  start {?>
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
        });
</script>
<?php #展开隐藏航班信息} ?>
		</td>
		</tr>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
	table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
<?php
if (DOWNLOAD_ENABLED == 'true') include(DIR_FS_MODULES . 'downloads.php');
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" style="margin-top:8px">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
	   <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
        <td  class="main"><a class="btn" href="<?php echo tep_href_link('orders_travel_companion.php', '', 'SSL');?>"><span></span><?php echo db_to_html('回上一页');?><?php #echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
        <td align="right" class="main">&nbsp;

                </td>
<?php /* end PayPal_Shopping_Cart_IPN */ ?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
      </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>


 </table>

 <!-- content main body end -->

<?php //echo tep_get_design_body_footer();?>
