<?php
$order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : (int) $_GET['order_id'];
$orders_travel_companion_ids = isset($_POST['orders_travel_companion_ids']) ? (array) $_POST['orders_travel_companion_ids'] : (array) $_GET['orders_travel_companion_ids'];

$orders_travel_companion_id_str = implode(',', $orders_travel_companion_ids);

// 是否包含物价团或者团购团
$is_special = false;
//统计所有产品ID，用来判断是否某个产品不能使用某种支付方式 by lwkai add 2013-5-13
$products_id_array = array();
if (tep_not_null($orders_travel_companion_id_str)) {
	// 取得付款总额和参团人信息
	$payables_total_sql = tep_db_query('SELECT products_id, payables, guest_name, paid FROM `orders_travel_companion` WHERE orders_travel_companion_id IN(' . $orders_travel_companion_id_str . ') GROUP BY orders_travel_companion_id ');
	$payables_total = 0;
	$guest_name = '';
	while($payables_total_row = tep_db_fetch_array($payables_total_sql)) {
		
		$payables_total += $payables_total_row['payables'];
		// 减去已经付款
		$payables_total -= $payables_total_row['paid'];
		
		$guest_name .= tep_db_output($payables_total_row['guest_name']) . ' , ';
		$products_id = $payables_total_row['products_id'];
		$products_id_array[intval($products_id)] = count($products_id_array);
		// 判断有没有特价产品 或者团购产品
		if ((int) special_detect($products_id)) {
			$is_special = true;
		}
	}
	$guest_name = preg_replace('/ , $/', '', $guest_name);
	?>
<form
	action="<?php echo tep_href_link('checkout_confirmation_travel_companion.php','','SSL')?>"
	method="post" name="checkout_payment" id="checkout_payment"
	onSubmit="return check_form();">
	<div>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"
			bgcolor="#FFFFFF">
			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			</tr>


	<?php //行程信息?>
	<tr>
				<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td class="infoBoxHeading">
								<div class="heading_bg"><?php echo db_to_html('行程信息'); ?></div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div class="infoBox_outer">
						<table border="0" width="100%" cellspacing="1" cellpadding="2"
							class="infoBox_new">
							<tr class="infoBoxContents_new">
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0"
										style="padding: 10px;">
										<tr>
											<td align="right" nowrap="nowrap"><?php echo db_to_html('订单号：');?></td>
											<td align="left"><b><?php echo $order_id?></b><input
												name="order_id" type="hidden" id="order_id"
												value="<?php echo $order_id?>" size="50"> <input
												name="orders_travel_companion_ids" type="hidden"
												id="orders_travel_companion_ids"
												value="<?php echo $orders_travel_companion_id_str?>"></td>
										</tr>
										<tr>
											<td align="right"><?php echo db_to_html('团　名：');?></td>
											<td><b><?php echo db_to_html(tep_get_products_name($products_id))?></b></td>
										</tr>
										<tr>
											<td align="right"><?php echo db_to_html('付款人：');?></td>
											<td><b><?php echo db_to_html($guest_name)?></b><input
												name="guest_names" type="hidden" id="guest_names"
												value="<?php echo db_to_html($guest_name)?>" size="80"></td>
										</tr>
										<tr>
											<td align="right"><?php echo db_to_html('金　额：');?></td>
											<td><b><?php echo $currencies->display_price($payables_total,0, 1);?></b><input
												name="payables_total" type="hidden" id="payables_total"
												value="<?php echo $payables_total?>" size="50"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
	<?php //行程信息end?>

	<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
			</tr>

	<?php
	// 错误提示
	if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
		?>
	<tr>
				<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" cellspacing="1" cellpadding="2"
						class="infoBoxNotice">
						<tr class="infoBoxNoticeContents">
							<td>
								<table border="0" width="100%" cellspacing="0" cellpadding="2">
									<tr>
										<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
										<td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
										<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
			</tr>
	<?php
	}
	?>

	<?php //支付方式?>
	<tr>
				<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td class="infoBoxHeading">
								<div class="heading_bg"><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div class="infoBox_outer">
						<table border="0" width="100%" cellspacing="1" cellpadding="2"
							class="infoBox_new">
							<tr class="infoBoxContents_new">
								<td><?php
	$selection = $payment_modules->selection(array_flip($products_id_array));
	// amit added to default selection start
	if (! isset($payment)) {
		$payment = "paypal_nvp_samples";
	}
	// amit added to default selection end
	
	?>

				<div class="cont pay">
										<div id="payment_list_table" class="payLeft">
											<ul>

				<?php
	// 付款方式列表 start
	$radio_buttons = 0;
	$show_all_pay_module = true;
	$_checkdID = '';
	for($i = 0, $n = sizeof($selection); $i < $n; $i ++) {
		
		// 如果当前订单中有一个特价产品，或者团购产品，则支付方式只显示 支付宝 和 中国银行转帐 by lwkai 2012-09-03 add ,howard added 添加了银联在线,lwkai added 如果总金额大于3000则显示美国银行转帐
		if (($selection[$i]['id'] != 'alipay_direct_pay' && $selection[$i]['id'] != 'transfer' && $selection[$i]['id']!='netpay' && $selection[$i]['id'] != 'cashdeposit') && $is_special == true) {
			//continue; 不再做限制 by lwkai rem 2013-03-25 13.24
		} 
		// 如果是美国银行转帐,并且总金额大于等于3000 则显示,否则不显示
		if ($selection[$i]['id'] == 'cashdeposit'  && $payables_total < 3000 ) {
			continue;
		}
		if ($_checkdID == '') $_checkdID = "div_pay_list_" . $selection[$i]['id']; //假设默认要展示的支付方式就是第一个
		// lwkai add end
		if ($selection[$i]['id'] == "authorizenet" || $selection[$i]['id'] == "paypal" || $show_all_pay_module == true) {
			$margin_top = $i * 40;
			
			?>
					<li id="div_pay_list_<?= $selection[$i]['id']?>"><label> <?php
			$_checkd = false;
			if ($payment == $selection[$i]['id']) {
				$_checkd = true;
				$_checkdID = "div_pay_list_" . $selection[$i]['id']; //发现用户有选支付方式,则更改为用户选择的支付方式为默认选中支付方式
			}
			echo tep_draw_radio_field('payment', $selection[$i]['id'], $_checkd, ' id="payment_' . $selection[$i]['id'] . '" ');
			?> <span class="font_size14"><?php echo $selection[$i]['module']; ?></span>
												</label></li>
					<?php
			$radio_buttons ++;
		}
	}
	// 付款方式列表 end
	?>

				</ul>
										</div>
										<div id="payment_list_content" class="payRight"><?php
	// 付款方式右边信息框 start
	$show_all_expansion = true; // 显示所有右边的内容
	for($i = 0, $n = sizeof($selection); $i < $n; $i ++) {
		// 如果当前订单中有一个特价产品，或者团购产品，则支付方式只显示 支付宝 和 中国银行转帐 by lwkai 2012-09-03 add ,howard added 添加了银联在线,lwkai added 如果总金额大于3000则显示美国银行转帐
		if (($selection[$i]['id'] != 'alipay_direct_pay' && $selection[$i]['id'] != 'transfer' && $selection[$i]['id']!='netpay' && $selection[$i]['id'] != 'cashdeposit' ) && $is_special == true) {
			continue;
		} 
		// 如果是美国银行转帐,并且总金额大于等于3000 则显示,否则不显示
		if ($selection[$i]['id'] == 'cashdeposit'  && $payables_total < 3000 ) {
			continue;
		}
		// lwkai add end
		if (isset($selection[$i]['error'])) {
			?>
				<div><?php echo $selection[$i]['error']; ?></div>
				<?php
		} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields']) || $show_all_expansion == true) {
			?>
				<div id="Expansion_<?php echo $selection[$i]['id']?>">
												<div class="tit">
													<h2 class="font_bold font_size14"><?php echo $selection[$i]['module'];?></h2>
													<span class="font_bold color_orange"><?php echo db_to_html('您已选择了').$selection[$i]['module'].db_to_html('方式，请继续您的支付过程')?></span>
												</div>
												<div class="cont">
													<table border="0" cellspacing="0" cellpadding="2">
				<?php
			if ($selection[$i]['id'] == 'authorizenet' || $selection[$i]['id'] == 'cc_cvc') {
				?>
					<tr>
															<td colspan="4" class="font_black"><span class="sp1"><b><?php echo TEXT_NOTES_HEADING_DIS;?></b>
															</span><?php echo TEXT_NOTES_HEADING_HOLDER_CC_NOTE;?></td>
														</tr>
														<tr>
															<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
														</tr>
					<?php
			}
			?>
				<?php
			for($j = 0, $n2 = sizeof($selection[$i]['fields']); $j < $n2; $j ++) {
				?>
					<tr>
															<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															<td class="main" height="25px;" align="right"
																nowrap="nowrap"><?php echo $selection[$i]['fields'][$j]['title']; ?>&nbsp;</td>
															<td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
															<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
														</tr>
					<?php
			}
			?>
				</table>
												</div>
												<div class="pay2_warp"><?php
			// 温馨提示栏start
			if (isset($selection[$i]['warm_tips'])) {
				echo $selection[$i]['warm_tips'];
			}
			// 温馨提示栏end			?></div>
											</div>
				<?php
		}
	}
	// 付款方式右边信息框 end
	?></div>
										<div class="clear"></div>
									</div></td>
							</tr>
						</table>
					</div> <script type="text/javascript"><!--

jQuery(document).ready(function() {




    jQuery('#payment_list_table > ul').children().click(function(){
			
			jQuery(this).addClass('cur').siblings().removeClass('cur'); //    设置点击的LI的class为cur 去掉其他LI的cur样式
			jQuery(this).find('input[type=radio]').attr('checked','true');//   防用户未点到input radio 的响应区上 造成切换到当前项而单选按钮不选中的问题
			var index = jQuery('#payment_list_table > ul').children().index(this);//    取得当前LI的索引 
			jQuery('#payment_list_content').children().eq(index).show().siblings().hide(); //    用li的索引来显示对应的右边内容
	});
	/*    如果PHP中进行默认展示设置 则注释掉下面这句 PHP中 左边LI 上面 设置class = cur 即为当前展开的项，右边则对应去掉display:none 即可      */
	jQuery('#<?php echo $_checkdID?>').click().find('input').attr('checked','true');

});


<?php
	for($i = 0, $n = sizeof($selection); $i < $n; $i ++) {
		if (isset($selection[$i]['fields']) && is_array($selection[$i]['fields']) && ! isset($selection[$i]['error']) || $show_all_expansion == true) {
			if (($selection[$i]['id'] != $payment)) {
				echo 'if(document.getElementById("Expansion_' . $selection[$i]['id'] . '")!= null){ document.getElementById("Expansion_' . $selection[$i]['id'] . '").style.display="none";}' . "\n";
			}
		}
	}
	?>

function display_cxpansion_tr(id){
	<?php
	for($i = 0, $n = sizeof($selection); $i < $n; $i ++) {
		echo 'if(document.getElementById("Expansion_' . $selection[$i]['id'] . '")!= null){ document.getElementById("Expansion_' . $selection[$i]['id'] . '").style.display="none";}' . "\n";
	}
	?>
	
	var id = document.getElementById(id);
	if(id!=null){
		id.style.display = "";
	}
}

//--></script>
				</td>
			</tr>
	<?php //支付方式end?>

	<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
			</tr>

	<?php
	if ($messageStack->size('travel_companion') > 0) {
		?>
	<tr>
				<td align="left"><?php echo $messageStack->output('travel_companion');?>
		</td>
			</tr>
	<?php
	}
	?>

	<?php //通信地址?>
	<tr style="display: none">
				<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td class="infoBoxHeading">
								<div class="heading_bg"><?php echo TABLE_HEADING_CONTACT_ADDRESS; ?></div>
								<div class="head_note" style="color: #f68711;"><?php //echo TABLE_HEADING_BILLING_ADDRESS_EXP; ?></div>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr style="display: none">
				<td>
					<div id="response_ship_div"><?php
	// amit added move billing address exits query above don't delete
	$shipto = ((int) $shipto) ? $shipto : $customer_default_ship_address_id;
	
	$check_address_blank_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone, c.customers_mobile_phone from " . TABLE_ADDRESS_BOOK . " a, " . TABLE_CUSTOMERS . " c where a.customers_id=c.customers_id and a.customers_id = '" . (int) $customer_id . "' and a.address_book_id = '" . (int) $shipto . "'");
	$row_check_address_blank = tep_db_fetch_array($check_address_blank_query);
	// amit added to move billing address exits query above don't delete
	
	if ($row_check_address_blank['street_address'] == '' && $row_check_address_blank['city'] == '') {
		$style_show_address_div = ' style="display:none;"';
		$style_show_edit_address_div = '';
	} else {
		$style_show_address_div = '';
		$style_show_edit_address_div = ' style="display:none;"';
	}
	$osCsid_string = '';
	if (tep_not_null($_GET['osCsid'])) {
		$osCsid_string = '&osCsid=' . (string) $_GET['osCsid'];
	}
	?>

		<div id="show_address_div" <?php echo $style_show_address_div; ?>>
							<div class="infoBox_outer">
								<table border="0" width="100%" cellspacing="0" cellpadding="2"
									class="infoBox_new">
									<tr class="infoBoxContents_new">
										<td>
											<table border="0" width="100%" cellspacing="0"
												cellpadding="2">
												<tr>
													<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
												</tr>
												<tr>
													<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
													<td class="main" colspan="3"><span class="sp1"><b><?php echo TEXT_NOTES_HEADING_DIS;?>
						</b></span><?php echo TEXT_NOTES_HEADING_BILLING_EDIT_INFORMATION;?></td>
												</tr>
												<tr>
													<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
													<td align="left" width="50%" valign="top">
														<table border="0" cellspacing="0" cellpadding="2">
															<tr>

																<td class="main" align="center" valign="top"><b><?php //echo TITLE_BILLING_ADDRESS; ?></b><?php //echo tep_image('image/'. 'arrow_south_east.gif'); ?></td>

																<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td valign="top" style="padding-left: 20px;"
																	nowrap="nowrap"><b><?php
	
	echo db_to_html(tep_address_label($customer_id, $shipto, true, ' ', '<br />'));
	// amit added shot telpphone number start
	if ($row_check_address_blank['customers_telephone'] != '') {
		echo '<br />' . TEXT_BILLING_INFO_TELEPHONE . ' ' . db_to_html($row_check_address_blank['customers_telephone']);
	}
	if ($row_check_address_blank['customers_mobile_phone'] != '') {
		echo '<br />' . TEXT_BILLING_INFO_MOBILE . ' ' . db_to_html($row_check_address_blank['customers_mobile_phone']);
	}
	if ($row_check_address_blank['customers_fax'] != '') {
		echo '<br />' . ENTRY_FAX_NUMBER . ' ' . db_to_html($row_check_address_blank['customers_fax']);
	}
	// amit added show telephone number end
	?></b></td>

																<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															</tr>
														</table>
													</td>
													<td align="right" class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?><br />
														<br /> <a href="javascript:void(0);"
														onclick="show_edit_adderss()" class="btn"><span></span><?php echo db_to_html('修改信息') ?></a><?php # echo tep_template_image_button('button_edit_information.gif', IMAGE_BUTTON_CHANGE_ADDRESS,' onclick="show_edit_adderss()" style="cursor:pointer;"'); // onclick="toggel_div(\'address_edit_div\');" ?>
						</td>
													<td align="right" class="main" width="1%" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
												</tr>
												<tr>
													<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div id="show_edit_address_div"
							<?php echo $style_show_edit_address_div; ?>>
							<div class="infoBox_outer">
								<table border="0" width="100%" cellspacing="0" cellpadding="2"
									class="infoBox_new">
									<tr class="infoBoxContents_new">
										<td><?php require(DIR_FS_MODULES . 'edit_ship_address.php');?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</td>
			</tr>
	<?php //通信地址end?>

	<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
			</tr>
			<tr>
				<td>


					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><a class="btn"
								href="<?php echo tep_href_link('orders_travel_companion_info.php', 'order_id='.(int)$order_id, 'SSL');?>"><span></span><?php echo db_to_html('回上一页')?><?php # echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a>
							</td>
							<td align="right" class="subbtn"><input type="submit"
								value="<?php echo db_to_html('继续');?>"><?php # echo tep_template_image_submit('button_continue_checkout.gif', db_to_html('继续'));?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
	</div>
</form>
<?php
} else {
	?>
<table width="100%" border="0" cellspacing="0" cellpadding="0"
	style="margin-top: 10px;">
	<tr>
		<td><?php echo db_to_html('您没有选择要付款的人员，请返回重新选择！');?></td>
		<td align="right"><a class="btn" href="javascript:history.go(-1)"><span></span><?php echo db_to_html('返回上一页'); //echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
	</tr>
</table>
<?php
}
?>


<?php
$p = array(
		'/&amp;/',
		'/&quot;/' 
);
$r = array(
		'&',
		'"' 
);
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
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_for_checkout_payment_ajax.php', 'country_id='))?>") +country_id;
	ajax.open('GET', url, true);  
	ajax.send(null);
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById('state_td').innerHTML = ajax.responseText;
			document.getElementById('city_td').innerHTML ='<?php echo tep_draw_input_field('city','','id="city" class="required validate-length-city" title="'.ENTRY_CITY_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>';
		}
	}

	
}
function get_ship_state(country_id,form_name,state_obj_name){
	var ajax_ = false;
	if(window.XMLHttpRequest) {
		 ajax_ = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		try {
				ajax_ = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
		try {
				ajax_ = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!ajax_) {
		window.alert("can not use ajax");
	}

	var form = form_name;
	var state = form.elements[state_obj_name];
	var country_id = parseInt(country_id);
	if(country_id<1){
		alert('<?php echo ENTRY_COUNTRY.ENTRY_COUNTRY_ERROR ?>');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_ship_state_list_for_checkout_payment_ajax.php', 'country_id='))?>") +country_id;

	ajax_.open('GET', url, true);  
	ajax_.send(null);
	ajax_.onreadystatechange = function() { 
		if (ajax_.readyState == 4 && ajax_.status == 200 ) { 
			document.getElementById('ship_state_td').innerHTML = ajax_.responseText;
			document.getElementById('ship_city_td').innerHTML ='<?php echo tep_draw_input_field('ship_city','','id="ship_city" class="required validate-length-ship_city" title="'.ENTRY_CITY_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>';
		}
	}

	
}
function get_city(state_name,form_name,city_obj_name){
	var form = form_name;
	var city = form.elements[city_obj_name];
	var state_name = state_name;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_for_checkout_payment_ajax.php','', 'SSL')) ?>");
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
function get_ship_city(state_name,form_name,city_obj_name){
	var form = form_name;
	var city = form.elements[city_obj_name];
	var state_name = state_name;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_ship_state_list_for_checkout_payment_ajax.php','', 'SSL')) ?>");
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
			document.getElementById('ship_city_td').innerHTML =ajax.responseText;
		}
	}
}

//自动根据国家值选省份
<?php if(!tep_not_null($state) && !tep_not_null($city)){?>
if(document.getElementById('country')!=null && document.getElementById('country').value>0){
	get_state(document.getElementById('country').value, document.getElementById('checkout_payment'), "state");
}
<?php }?>

<?php if(!tep_not_null($ship_state) && !tep_not_null($ship_city)){?>
if(document.getElementById('ship_country')!=null && document.getElementById('ship_country').value>0){
	get_ship_state(document.getElementById('ship_country').value,document.getElementById('checkout_payment'),'ship_state');
}
<?php }?>

//-->

function show_edit_adderss(){
	var show_edit_address_div = document.getElementById('show_edit_address_div');
	var show_address_div = document.getElementById('show_address_div');
	
	if(show_edit_address_div.style.display!="none"){
		show_edit_address_div.style.display="none";
	}else{
		show_edit_address_div.style.display="";
	}
	show_address_div.style.display="none";
}

</script>
