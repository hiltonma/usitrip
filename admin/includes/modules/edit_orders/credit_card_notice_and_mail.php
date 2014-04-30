<?php
/**
 * 信用卡冻结信息自动识别
 *
 * <p>1、如果信用卡主人非参团人、订单金额超过$1800，或AVS not match at all，订单页面均显示红色提示，以提醒客服需要客人提供ACB。</p>
 * <p>2、当订单页面同时出现以上三种红色提示时，系统自动发送一封邮件给客人索要ACB</p>
 * <p>（1）在Customer and Guest Information中的	Billing Address-> Name变红。
 *（2）>$1800时在总计中标红
 *（3）当Address Verification Status:这行字中包含“Street或 Zip 同时包括Not”时则该行文字标红；另外，Card Code Status: Match如果不是“Match”的该行标红。</p>
 */

$billing_name_is_guest = false;	//信用卡主人是否是参团人
$guest_info_sql = tep_db_query('SELECT guest_name FROM `orders_product_eticket` WHERE orders_id="'.(int)$oID.'" ');
while($guest_info = tep_db_fetch_array($guest_info_sql)){
	if(tep_not_null($order->billing['name']) && strpos(strtolower($guest_info['guest_name']), strtolower($order->billing['name']))!==false){
		$billing_name_is_guest = true;
		break;
	}
}

$credit_max_amount = 1800; //$1800
$totals_exceeds = false; //总额是否超过上限
foreach($order->totals as $key => $val){
	if($val['value'] > $credit_max_amount && $val['class']=="ot_total"){
		$totals_exceeds = true;
		break;
	}
}

$street_and_zip_match = true;	//街道地址或编辑是否匹配
$replace_str = '';
$orders_history_sql = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' and orders_status_id in(100060,100062) ");
$tmp_array = array();
while($orders_history_rows = tep_db_fetch_array($orders_history_sql)){
	if(tep_not_null($orders_history_rows['comments'])){
		$tmp_array[] = explode("\n",$orders_history_rows['comments']);
	}
}
if(sizeof($tmp_array)){
	foreach($tmp_array as $key => $val){
		if(strpos($val[0], 'Address Verification Status:') !==false){
			if((strpos(strtolower($val[0]), 'street') !==false || strpos(strtolower($val[0]), 'zip') !== false) && strpos(strtolower($val[0]), ' not ') !== false){
				$replace_str = substr($val[0],0,-1);
				$street_and_zip_match = false;
				break;
			}
		}
	}
}

if($billing_name_is_guest == false && $totals_exceeds == true && $street_and_zip_match == false && !(int)$order->info['sent_acb_mail']){	//系统自动发送一封邮件给客人索要ACB start
	$acb_mail_text = '谢谢您的购买和支付，我们正在处理您的订单。为了保证顺利出团，请您尽快发送所需支持档以确保交易顺利完成。'."\n";
	$acb_mail_text.= '走四方网（usitrip.com）将竭尽全力保证您网上购物的安全性。 为了继续为您提供最低廉的价格和避免您遭遇信用卡欺诈, 如果您的消费符合以下一种情况，请您向我们提供相关证实档和证明。'."\n\n";
	$acb_mail_text.= '&bull; 信用卡持卡人不参加旅游<br />&bull; 您的信用卡地址不能通过系统核实<br />&bull; 订单消费金额超过$1800'."\n\n";
	$acb_mail_text.= '我们需要哪些证实文件和证明: '."\n";
	$acb_mail_text.= '1. 信用卡持有人有效身份证件的影印本(<b>有效身份证件包括您的护照或由美国签发的带有本人签名的驾驶执照或由美国签发的带有本人签名的身份证</b>)。'."\n";
	$acb_mail_text.= '2. 填写完整，并签署了信用卡持有人签名和日期的信用卡支付验证书(<a href="'.tep_catalog_href_link('credit_card_holder_verification_form_simplified.doc').'" target="_blank">点击链接下载授权书</a>)。'."\n";
	$acb_mail_text.= '3. 如果信用卡持有人不是参与旅行团的成员，请附上游客的护照影印本。'."\n\n";
	$acb_mail_text.= '三种寄发相关证实档和证明的方式:'."\n";
	$acb_mail_text.= '&bull; 电子邮箱：将相关证实文件和证明的影印本，扫描本或者数码照片发送至'.STORE_OWNER_EMAIL_ADDRESS.' <br>
&bull; 地址：<br>'.nl2br(db_to_html(strip_tags(STORE_NAME_ADDRESS)))."\n";
	$acb_mail_text.= ''."\n";
	
	$acb_mail_to = $order->customer['email_address'];
	$acb_mail_to_name = strip_tags($order->customer['name']);
	if(IS_LIVE_SITES!=true){
		$acb_mail_to = "xmzhh2000@hotmail.com";
	}
	if(tep_not_null($acb_mail_to)){
		$acb_mail_subject = '走四方网请您提供ACB授权书 订单号：'.$oID;
		tep_mail($acb_mail_to_name, $acb_mail_to, $acb_mail_subject, $acb_mail_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
		tep_db_query('update orders set sent_acb_mail="1" where orders_id="'.(int)$oID.'" ');
	}
//系统自动发送一封邮件给客人索要ACB end
}
?>
<script type="text/javascript">
jQuery().ready(function() {
<?php 
//JS start
if($billing_name_is_guest == false){
?>
	jQuery("#customer_billing_info input[name='update_billing_name']").addClass('col_red');
	jQuery("#CustomerAndGuestInformation").show();

<?php
}
if($totals_exceeds == true){
?>
	jQuery("#TotalModule td").addClass('col_red');
	
<?php
}
if($street_and_zip_match == false && tep_not_null($replace_str)){
?>
	var tmp_hmtl_codes = document.getElementById("OrderStatusHistoryList").innerHTML.replace("<?= $replace_str?>", "<?= '<b class=col_red>'.$replace_str.'</b>';?>");
	document.getElementById("OrderStatusHistoryList").innerHTML = tmp_hmtl_codes;
<?php
}
//JS end
?>
});
</script>
