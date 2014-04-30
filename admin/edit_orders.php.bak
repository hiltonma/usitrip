<?php
set_time_limit(0);
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ETICKET);
header("Content-type: text/html; charset=" . CHARSET . "");
require('includes/classes/Assessment_Score.class.php');	//添加商务中心考核类
$Assessment_Score = new Assessment_Score();
require('includes/classes/OrdersOwnerChange.class.php');//订单更新状态的时候改变订单归属的类
require('includes/classes/PickUpSms.class.php');	//上传接机导游信息类
require(DIR_WS_CLASSES . 'Price_Change_Alert.class.php');	//未付款订单产品最新价格变动类
$PCA = new Price_Change_Alert;
$OOC = new OrdersOwnerChange;

$reload_url = tep_href_link('edit_orders.php', 'random_code='.microtime(true).'&'.tep_get_all_get_params(array('action','random_code')));
//订单编辑页面权限分配（在application_top.php和reports_permissions.php已经分配好，此处不再设置）
//$access_full_edit = 'false';
//$access_order_cost = 'false';

require('includes/classes/payment_history_accounts_remark.php');
require('../includes/classes/visa_invitation.php');
$account_remark = new payment_history_accounts_remark();
// print_r($_POST);
// exit;

//添加销售订单备注开始
if(isset($_POST['action'])&&$_POST['action']=='add_seller_mark'){
	$content=iconv('UTF-8','GBK//IGNORE',$_POST['content']);
	$str_sql='insert into orders_remark_seller set login_id='.(int)$_POST['loogin_id'].',orders_id='.(int)$_POST['ooID'].',remark="'.$content.'",add_time=now()';
	tep_db_query($str_sql);
	exit();
}
//添加销售订单备注结束
//添加主管订单备注开始
if(isset($_POST['action'])&&$_POST['action']=='add_head_mark'){
	$content=iconv('UTF-8','GBK//IGNORE',$_POST['content']);
	$str_sql='insert into orders_remarks_head set login_id='.(int)$_POST['loogin_id'].',orders_id='.(int)$_POST['ooID'].',remark="'.$content.'",add_time=now()';
	tep_db_query($str_sql);
	exit();
}
//添加主管订单备注结束

if (isset($_POST['action']) && isset($_POST['ajax']) && $_POST['action'] == 'update_owners' && $_POST['ajax'] == 'true') {
	$orders_id = (int)$_POST['orders_id'];
	$owners = iconv('UTF-8','GB2312//IGNORE',$_POST['owners']);
	$owners = str_replace('，',',',$owners);
	$sql = "update orders set orders_owners='" . $owners . "' where orders_id='" . $orders_id . "'";
	tep_db_query($sql);
	exit('ok');
}
//各种action动作 start{
switch($_GET['action']){
	//OP修改订单产品名称
	case 'update_orders_products_name':
		if($_POST['orders_products_id'] && $_POST['orders_product_name']){
			$products_name = tep_db_prepare_input(iconv('utf-8','gb2312//IGNORE',$_POST['orders_product_name']));
			tep_db_query('update orders_products set products_name="'.tep_db_input($products_name).'" where orders_products_id="'.(int)$_POST['orders_products_id'].'" ');
			if(tep_db_affected_rows()){
				echo 'ok';
			}
		}
		exit;
	break;
	//财务点击是否可用积分或优惠券动作
	case 'setDisabledCouponPoint':
		if((int)$_POST['orders_id']){
			tep_db_query('update orders set disabled_coupon_point="'.(int)$_POST['disabled_coupon_point'].'" WHERE orders_id = "'.(int)$_POST['orders_id'].'" ');
			if(tep_db_affected_rows()){
				echo 'ok';
			}
		}
		exit;
	break;
	//财务点击“我已知道这是step3产品”的动作
	case 'step3SetKnow':
		tep_db_query('update orders_products set is_step3_accounting_already_know="1" WHERE orders_products_id = "'.(int)$_POST['orders_products_id'].'" ');
		if(tep_db_affected_rows()){
			echo 'ok';
		}
		exit;
	break;
	//更新订单产品最后的产品价格修改时间(从产品表中更新过来)
	case 'update_orders_products_price_last_modified':
		if($PCA->update_orders_products_price_last_modified($_POST['orders_id'])){
			
			echo 'ok';
		}
		exit;
	break;
	//记录需要发接机短信的客户手机信息
	case 'record_pick_up_sms':
		$success = 0;
		$_POST['admin_id'] = $login_id;
		PickUpSms::record_pick_up_sms($_POST);
		echo 'ok';
		exit;
	break;
	//发手机短信
	case 'sendajaxsms':
		include('sendajaxsms.php');
		$sAjaxSms = new sendajaxsms;
		echo $sAjaxSms->send_sms($_POST['phone'], $_POST['content'], $_POST['orders_id'], $login_id, $Assessment_Score);
		exit;
	break;
	//给某个订单状态历史的销售加分
	case 'add_effective_updated_score':
		if((int)$_POST['orders_status_history_id']){
			$admin_id = tep_db_get_field_value('updated_by','orders_status_history', 'orders_status_history_id="'.(int)$_POST['orders_status_history_id'].'" ');
			if((int)$admin_id && $admin_id!=CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID && !$Assessment_Score->get_orders_status_history_score((int)$_POST['orders_status_history_id'])&&$Assessment_Score->checkLoginOwner($_POST['orders_id'])){
				$Assessment_Score->add_pending_score($admin_id,1,tep_get_admin_customer_name($login_id).'点击有效更新按钮给'.tep_get_admin_customer_name($admin_id).'加+1分！','orders_status_history_id',(int)$_POST['orders_status_history_id'],'1',$login_id, 1, (int)$_POST['orders_id']);
				echo 'success';
// 				$str_sql='update orders_status_history set score=score+1 where orders_status_history_id='.(int)$_POST['orders_status_history_id'];
// 				tep_db_query($str_sql);
			}
		}
		exit;
		break;
		//下一处理人确认完成处理
	case 'set_processing_done':
		if($_POST['ajax']=='true' && (int)$_POST['orders_status_history_id'] && (int)$_POST['next_admin_id']){
			tep_db_query('update orders_status_history set is_processing_done="1" WHERE orders_status_history_id = "'.(int)$_POST['orders_status_history_id'].'" and next_admin_id="'.(int)$_POST['next_admin_id'].'" ');
			if(tep_db_affected_rows()){
				echo 'ok';
			}
		}
		exit;
		break;
		//删除可疑优惠码佣金
	case 'removeBrokerage':
		if($_POST['ajax']=='true' && (int)$_POST['orders_id']){
			tep_db_query('DELETE FROM `affiliate_sales` WHERE affiliate_orders_id = "'.(int)$_POST['orders_id'].'" ');
			echo tep_db_affected_rows();
		}
		exit;
		break;
		//隐藏产品
	case 'hide_product':
		$str_sql='update '.TABLE_ORDERS_PRODUCTS.' set is_hide=1 where orders_products_id='.(int)$_GET['orders_products_id'];
		tep_db_query($str_sql);
		exit;
		//显示产品
	case 'show_product':
		$str_sql='update '.TABLE_ORDERS_PRODUCTS.' set is_hide=0 where orders_products_id='.(int)$_GET['orders_products_id'];
		tep_db_query($str_sql);
		exit;
		break;
		
	case 'confirm_cellphone_number':	//审核当日游客手机历史
		if($can_confirm_cellphone_updated_history !== true){
			echo 'not_permissions';
		}elseif((int)$_POST['orders_customers_cellphone_history_id']){
			tep_db_query('update orders_customers_cellphone_history set checked_id="'.(int)$login_id.'" where orders_customers_cellphone_history_id="'.(int)$_POST['orders_customers_cellphone_history_id'].'" ');
			echo (int)$_POST['orders_customers_cellphone_history_id'];
		}
		exit;
		break;
		//审核确认客人上车地址更新 start {
	case 'confirm_departure_location':
		if($_POST['ajax']=='true' && (int)$_POST['history_id']){
			if($can_confirm_departure_location !== true){
				echo 'not_permissions';
			}else{
				tep_db_query('update orders_products_departure_location_history set has_confirmed="1", confirmed_admin_id="'.(int)$login_id.'" where history_id="'.(int)$_POST['history_id'].'" ');
				//向供应商开放新的上车时间和地址
				$orders_products_id = tep_db_get_field_value('orders_products_id', 'orders_products_departure_location_history', 'history_id="'.(int)$_POST['history_id'].'"');
				tep_db_query('UPDATE '.TABLE_ORDERS_PRODUCTS.' SET products_departure_location_sent_to_provider_confirm="1" WHERE orders_products_id="'.(int)$orders_products_id.'" ');
				echo $orders_products_id;
			}
		}
		exit;
		//审核确认客人上车地址更新 end }
		break;
		//审核确认客人新参团人信息 start {
	case 'confirm_histories':
		if($_POST['ajax']=='true' && (int)$_POST['op_histoty_id']){
			if($can_confirm_departure_location !== true){
				echo 'not_permissions';
			}else{
				tep_db_query('update order_products_departure_guest_histoty set has_confirmed="1", confirmed_admin_id="'.(int)$login_id.'" where op_histoty_id="'.(int)$_POST['op_histoty_id'].'" ');
				$orders_products_id = tep_db_get_field_value('op_order_products_ids', 'order_products_departure_guest_histoty', 'op_histoty_id="'.(int)$_POST['op_histoty_id'].'"');
				echo $orders_products_id;
			}
		}
		exit;
		break;
		//审核确认客人新参团人信息 end }
	case "showhistory":
		//显示财务备注的历史记录start{
		//aBen added
		$data = $account_remark->show_history($_GET['orders_payment_history_id'],false);
		$rt = '';
		if(is_array($data))
		{
			$rt = '<table border="1" cellspacing="0" cellpadding="0" style="border-colllapse:collapse;"><tr><th width="160">时间</th><th width="120">工号</th><th width="300">备注内容</th></tr>';
			foreach($data AS $key=>$value)
			{
				$rt .= '<tr><td>'.$value['add_date'].'</td><td>'.$value['admin_job_number'].'</td><td>'.tep_db_output($value['remark']).'</td></tr>';
			}
			$rt .= '</table>';
		}
		echo $rt;
		exit();
		//显示财务备注的历史记录end}
		break;
	case "tep_split_orders_products":
		//自动分单 start{
		if((int)$_GET['orders_products_id'] && (int)$_GET['oID']){
			if(tep_split_orders_products((int)$_GET['orders_products_id'])){
				$messageStack->add_session('自动分单成功！', 'success');
				tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'action=edit&oID='.(int)$_GET['oID']));
			}
			exit;
		}
		//自动分单 end}
		break;
	case "checked_order_up_no_change_status":
		//主管OP完成检查"未产生费用，有更新的订单" start {
		if((int)$_GET['oID']){
			tep_set_order_up_no_change_price((int)$_GET['oID'],'0','主管OP完成检查"未产生费用，有更新的订单"');
			$messageStack->add_session('订单更新成功！', 'success');
			tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'action=edit&oID='.(int)$_GET['oID']));
			exit;
		}
		//主管OP完成检查"未产生费用，有更新的订单" end }
		break;
	case "close_set_again_paid_orders":	//取消客人再次付款订单状态
		if(tep_set_or_close_again_paid_orders($_GET['oID'], '0')){
			$messageStack->add_session('订单更新成功！', 'success');
			tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'action=edit&oID='.(int)$_GET['oID']));
			exit;
		}
		break;
		//更新允许最小支付金额数 {
	case "update_allow_pay_min_money":
		if((int)$_GET['oID']){
			$allow_pay_min_money = round($_POST['allow_pay_min_money'], 2);
			$allow_pay_min_money_deadline = date('Y-m-d H:i:s', strtotime($_POST['allow_pay_min_money_deadline']));
			//如果不提交时没有写日期就默认为2小时
			if($_POST['allow_pay_min_money_deadline']=='' || $_POST['allow_pay_min_money_deadline']=='0000-00-00 00:00:00'){
				$allow_pay_min_money_deadline = date('Y-m-d H:i:s', strtotime('+2 hours'));
			}
			if($allow_pay_min_money < 0.01){
				$allow_pay_min_money_deadline = '0000-00-00 00:00:00';
			}
			tep_db_query('update ' . TABLE_ORDERS . ' set allow_pay_min_money = "'.$allow_pay_min_money.'", allow_pay_min_money_deadline = "'.$allow_pay_min_money_deadline.'" WHERE orders_id = "'.(int)$_GET['oID'].'" ');
			//记录更新历史
			tep_db_query('insert into orders_allow_pay_min_money_history set orders_id = "'.(int)$_GET['oID'].'", admin_id="'.(int)$login_id.'", allow_pay_min_money = "'.$allow_pay_min_money.'", allow_pay_min_money_deadline = "'.$allow_pay_min_money_deadline.'", added_time ="'.date('Y-m-d H:i:s').'" ');
			echo 'OK';
			exit;
		}
		break;
		//更新允许最小支付金额数 }
	case "points_pending_confirmed":
		//更新客户订单积分 start {
		if((int)$_GET['oID']){
			$unique_id = tep_db_get_field_value('unique_id', '`customers_points_pending`', 'orders_id = "'.(int)$_GET['oID'].'" AND points_type="SP" AND points_comment="TEXT_DEFAULT_COMMENT" ');
			//只能更新未确定的积分值，已经确认的积分任何人都不能在这里更新
			if($unique_id > 0){
				tep_db_query('update `customers_points_pending` set points_pending="'.tep_db_prepare_input(tep_db_input($_GET['points_pending'])).'", admin_id="'.(int)$login_id.'" WHERE unique_id="'.$unique_id.'" AND orders_id = "'.(int)$_GET['oID'].'" ');
				tep_db_query('update `orders` set `point_lock`=1 where orders_id="' . (int)$_GET['oID'] . '"');
				echo 'OK';
			}
			exit;
		}
		//更新客户订单积分 end }
		break;
	case "admin_and_provider_confirmed":
		//howard added 确认与供应商确认完毕 start{
		if ((int)$_GET['orders_products_id']) {
			tep_db_query('UPDATE `orders_products` SET `admin_and_provider_confirmed` ="1" WHERE `orders_products_id` = "' . (int)$_GET['orders_products_id'] . '" LIMIT 1 ;');
			exit;
		}
		//howard added 确认与供应商确认完毕 end}
		break;
	case "submit_payment_history":
		//添加客户付款记录{
		if($_POST['ajax']=="true" && $access_order_cost == 'true' ){	//只有财务能添加
			$usa_value = $_POST['_orders_value'];
			$payment_method = $_POST['_payment_method'];
			$comment = $_POST['_comment'];
			$orders_id_include_time = date("YmdHis");
			if(tep_payment_success_update($oID, $usa_value, ajax_to_general_string($payment_method), ajax_to_general_string($comment), $login_id, $orders_id_include_time)){
				$messageStack->add_session('Updated Successfully.', 'success');
				//添加时自动将财务状态修改为已确认
				//tep_db_query('update orders_payment_history set has_checked="1", checked_time="'.date("Y-m-d H:i:s").'", checked_admin_id="'.(int)$login_id.'" where orders_id="'.(int)$oID.'" and has_checked!="1" and orders_id_include_time="'.$orders_id_include_time.'" ');

			}
			$js_str = 'document.location = "'.$reload_url.'";';
			echo '[JS]'.$js_str.'[/JS]';
			exit;
		}
		//添加客户付款记录}
		break;
	case "update_transfer_calculation_price":
		//更新接驳车服务行程价格{
		$js_str = "";
		if(is_array($_POST['transferInfo'])){
			foreach($_POST['transferInfo'] as $orders_products_id => $transferInfos){
				//echo $orders_products_id ;
				$params = array();
				foreach($transferInfos as $n => $val){
					foreach($val as $k => $v){
						$params[$k.($n+1)] = $v;
					}
				}
				$priceAndCost = tep_transfer_calculation_price((int)$_GET["products_id"] , $params, true);
				$price = number_format($priceAndCost['price'], 2, '.', '');
				$cost = number_format($priceAndCost['cost'], 2, '.', '');
				//echo $price."|".$cost;

				$js_str .= 'var fpwa = document.getElementById("edit_order").elements["update_products['.$orders_products_id.'][final_price_without_attr]"];';
				$js_str .= 'var fpcwa = document.getElementById("edit_order").elements["update_products['.$orders_products_id.'][final_price_cost_without_attr]"];';
				$js_str .= 'fpwa.value="'.$price.'"; ';
				$js_str .= 'fpcwa.value="'.$cost.'"; ';
				//$js_str .= 'fpcwa.focus(); ';
				//$js_str .= 'fpwa.focus(); ';
			}
		}
		echo '[JS]'.$js_str.'[/JS]';
		exit;
		//更新接驳车服务行程价格}
		break;
	case "update_invoice_popup":
		//更新发票信息 start {
		if (isset($_POST['aryFormData'])) {
			$aryFormData = $_POST['aryFormData'];
			foreach ($aryFormData as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if (eregi('--leftbrack', $key)) {
						$key = str_replace('--leftbrack', '[', $key);
						$key = str_replace('rightbrack--', ']', $key);
					}
					$value2 = str_replace('@@amp;', '&', $value2);
					$value2 = str_replace('@@plush;', '+', $value2);
					$value2 = mb_convert_encoding($value2, CHARSET, 'UTF-8');
					$_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes2($value2);
					//echo "$key=>$value2<br>";
				}
			}
			$i = $HTTP_GET_VARS['i'];
			$oID = addslashes($HTTP_GET_VARS['oID']);
			$_GET['orders_products_id'] = $orders_products_id = $HTTP_POST_VARS['orders_products_id[' . $i . ']'];
			if ($HTTP_POST_VARS['update_products[' . $orders_products_id . '][previous_invoice_number]'] != $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_number]'] || $HTTP_POST_VARS['update_products[' . $orders_products_id . '][previous_invoice_amount]'] != $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_amount]'] || $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_comments]'] != '' || $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_comments_retail]'] != '' || $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_comments_invoice]'] != '') {
				$total_invoices_cost_retail_comments = '';
				$select_check_product_oder_his = "select ord_prod_history_id from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " where orders_products_id='" . $orders_products_id . "'";
				$select_check_product_oder_his_query = tep_db_query($select_check_product_oder_his);
				if (tep_db_num_rows($select_check_product_oder_his_query) == 0) {
					$sql_data_array_original_insert = array(
					'orders_products_id' => $orders_products_id,
					'products_model' => $HTTP_POST_VARS['update_products[' . $orders_products_id . '][model]'],
					'products_name' => str_replace("'", "&#39;", $HTTP_POST_VARS['update_products[' . $orders_products_id . '][name]']),
					'retail' => 0,
					'cost' => 0,
					'last_updated_date' => 'now()'
					);
					tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
				}

				$total_invoices_cost_retail_comments = stripslashes2($HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_comments]']) . '!!###!!' . stripslashes2($HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_comments_retail]']) . '!!###!!' . stripslashes2($HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_comments_invoice]']);
				$sql_data_array = array(
				'orders_products_id' => $orders_products_id,
				'products_model' => $HTTP_POST_VARS['update_products[' . $orders_products_id . '][model]'],
				'products_name' => str_replace("'", "&#39;", $HTTP_POST_VARS['update_products[' . $orders_products_id . '][name]']),
				'retail' => 0,
				'cost' => 0,
				'invoice_number' => $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_number]'],
				'invoice_amount' => $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_amount]'],
				'comment' => tep_db_input($total_invoices_cost_retail_comments),
				'updated_by' => $login_id,
				'last_updated_date' => 'now()'
				);
				tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array);

				//Update invoice amount in order products for providers invoice amount
				$sql_data_array = array('customer_invoice_no' => $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_number]'],
				'customer_invoice_total' => $HTTP_POST_VARS['update_products[' . $orders_products_id . '][invoice_amount]']);
				tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array, 'update', "orders_products_id='" . $orders_products_id . "'");
			}
			$messageStack->add('Updated Successfully.', 'success');
			if ($messageStack->size > 0) {
				echo $messageStack->output();
			}
		}
		exit;
		//更新发票信息 end }
		break;
	case "update_eticket_popup":
		//更新电子参团凭证信息 start {
		if (isset($_POST['aryFormData'])) {
			$aryFormData = $_POST['aryFormData'];
			foreach ($aryFormData as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if (eregi('--leftbrack', $key)) {
						$key = str_replace('--leftbrack', '[', $key);
						$key = str_replace('rightbrack--', ']', $key);
					}
					$value2 = str_replace('@@amp;', '&', $value2);
					$value2 = str_replace('@@plush;', '+', $value2);
					$value2 = mb_convert_encoding($value2, CHARSET, 'UTF-8');
					$_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes2($value2);
				}
			}
			$i = $HTTP_GET_VARS['i'];
			$oID = addslashes($HTTP_GET_VARS['oID']);
			$_GET['products_id'] = $products_id = $HTTP_POST_VARS['products_id[' . $i . ']'];
			$_GET['orders_products_id'] = $orders_products_id = $HTTP_POST_VARS['orders_products_id['.$i.']']; //hotel-extension
			$special_note = addslashes($HTTP_POST_VARS['special_note[' . $i . ']']);
			$eticket_comment = addslashes($HTTP_POST_VARS['eticket_comment[' . $i . ']']);
			$sub_tour_agency_info = addslashes($HTTP_POST_VARS['sub_tour_agency_info[' . $i . ']']);
			$tour_provider = addslashes($HTTP_POST_VARS['tourprovider']);
			$local_operator_phone = addslashes($HTTP_POST_VARS['local_operator_phone']);
			$emergency_contact_person = addslashes($HTTP_POST_VARS['emergency_contact_person']);
			$emergency_contact_no = addslashes($HTTP_POST_VARS['emergency_contact_no']);
			$tour_arrangement = addslashes($HTTP_POST_VARS['tour_arrangement[' . $i . ']']);
			$depature_full_address = addslashes($HTTP_POST_VARS['depature_full_address[' . $i . ']']);
			$old_depature_full_address = addslashes($HTTP_POST_VARS['old_depature_full_address[' . $i . ']']);

			$customer_confirmation_no = addslashes($HTTP_POST_VARS['customer_confirmation_no['.$i.']']);//hotel-extension
			$hotel_checkout_date = addslashes($HTTP_POST_VARS['hotel_checkout_date['.$i.']']);//hotel-extension

			//howard added send mail to provider if depature_full_address has changed.
			if (preg_replace('/[[:space:]]+/', '', $depature_full_address) != preg_replace('/[[:space:]]+/', '', $old_depature_full_address)) {
				$_SESSION['EmailSendProvider']['products_id'] = $products_id;
				$_SESSION['EmailSendProvider']['orders_id'] = $oID;
				$_SESSION['EmailSendProvider']['orders_products_id'] = $orders_products_id;
				//改变新的上车时间和地址后暂不让供应看到
				tep_db_query('update '.TABLE_ORDERS_PRODUCTS.' set products_departure_location_sent_to_provider_confirm = "0" where orders_products_id="'.(int)$orders_products_id.'" ');
				//写上车地址历史记录
				tep_add_departure_location_history((int)$orders_products_id, $depature_full_address, $login_id);
				$OOC->addRecord((int)$login_id,4,(int)$_GET['oID']);
			}

			$depature_full_address = ltrim($depature_full_address);
			$depature_full_address = str_replace('  ', ' ', $depature_full_address);
			$depature_full_address = str_replace('&nbsp;', ' ', $depature_full_address);
			//howard fixed
			$depature_full_address = str_replace('&nbsp;', ' ', $depature_full_address);
			$depature_full_address = str_replace('&#160;', ' ', $depature_full_address);
			$depature_full_address = preg_replace('/ +/', ' ', $depature_full_address);
			$depature_full_address = trim(ltrim($depature_full_address));

			$fulladdress_array = explode(' ', $depature_full_address);
			//$products_departure_date = $fulladdress_array[0];
			$products_departure_date = date('Y-m-d H:i:s', strtotime($fulladdress_array[0]));
			//hotel-extension
			$return_full_address = "";
			if(tep_check_product_is_hotel($products_id) == 3){
				$depature_full_address_return = addslashes($HTTP_POST_VARS['depature_full_address_return['.$i.']']);
				$date = substr($depature_full_address_return,0,10);
				$location = substr($depature_full_address_return,11);
				$return_full_address = '|=|'.$location.'=|='.$date;
			}

			$departure_add_location = '';
			if (!empty($fulladdress_array)) {
				foreach ($fulladdress_array as $key => $val) {
					if ($key > 0)
					$departure_add_location .= $val . ' ';
				}
			}

			if (eregi('am ', $departure_add_location)) {
				$fulladdress_array_time = explode('am ', $departure_add_location);
				$products_departure_time = $fulladdress_array_time[0] . 'am ';
				$departure_add_location = $fulladdress_array_time[1];
			} else if (eregi('pm ', $departure_add_location)) {
				$fulladdress_array_time = explode('pm ', $departure_add_location);
				$products_departure_time = $fulladdress_array_time[0] . 'pm ';
				$departure_add_location = $fulladdress_array_time[1];
			}

			//update date change start here Nirav  0000-00-00
			if(0){	//更新出发日期的时候不再更新客户信息那里了，sofia指示无必要
				$get_date_update = get_older_values_for_update($HTTP_GET_VARS['opID']);
				$dateold = substr($get_date_update['depature_date_old'], 0, 10);
				$new_date_updated = substr($depature_full_address, 0, 10);
				if ($dateold != $new_date_updated) {
					$sql_check_new_entry1 = "SELECT op_order_products_ids from " . ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY . " where op_order_products_ids=" . $HTTP_GET_VARS['opID'] . " limit 1";
					$run_check_new_entry1 = tep_db_query($sql_check_new_entry1);
					if (tep_db_num_rows($run_check_new_entry1) == 0) {
						$dateold = substr($get_date_update['depature_date_old'], 0, 10);
						create_update_history_orders($orders_products_id, $dateold, $get_date_update['total_room_adult_child_info_old'], $get_date_update['guest_name_old'], $get_date_update['date_purchased']);
					}
					create_update_history_orders($HTTP_GET_VARS['opID'], $products_departure_date, $get_date_update['total_room_adult_child_info_old'], $get_date_update['guest_name_old']);
					$OOC->addRecord($login_id,8,$_GET['oID']);
				}
			}
			//update date change end here Nirav  0000-00-00

			$products_departure_location = $departure_add_location;
			$sql_data_array_locations = array('products_departure_date' => tep_db_prepare_input($products_departure_date),
			'products_departure_time' => tep_db_prepare_input($products_departure_time),
			'products_departure_location' => tep_db_prepare_input($products_departure_location),
			'hotel_checkout_date' => tep_db_prepare_input($hotel_checkout_date), //hotel-extension
			'customer_confirmation_no' => tep_db_prepare_input($customer_confirmation_no)//hotel-extension
			);
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_locations, 'update',"orders_products_id = '" . (int)$orders_products_id  . "'");

			$orders_id = (int) $oID;
			if ($orders_id == '') {
				tep_db_query("INSERT INTO `" . TABLE_ORDERS_PRODUCTS_ETICKET . "` ( `orders_id` , `products_id`, `tour_provider` , `tour_arrangement`, `confirmation_date`,`special_note`,`eticket_comment`,`emergency_contact_person`,`emergency_contact_no`,`depature_full_address`,`sub_tour_agency_info`, `orders_products_id`, `local_operator_phone`) VALUES ('$oID', '$products_id', '$tour_provider', '$tour_arrangement', 'now()', '$special_note','$eticket_comment', '$emergency_contact_person', '$emergency_contact_no','$depature_full_address','$sub_tour_agency_info', '$orders_products_id', '$local_operator_phone'));");
			} else {
				tep_db_query("update " . TABLE_ORDERS_PRODUCTS_ETICKET . " set tour_provider='" . $tour_provider . "', tour_arrangement='" . $tour_arrangement . "', special_note='" . $special_note . "', eticket_comment='".$eticket_comment."', emergency_contact_person='" . $emergency_contact_person . "', emergency_contact_no='" . $emergency_contact_no . "', depature_full_address='" . $depature_full_address . "', sub_tour_agency_info='" . $sub_tour_agency_info ."', local_operator_phone='".$local_operator_phone."'  where orders_products_id = ".$orders_products_id."");
			}
			$airline_name = addslashes($HTTP_POST_VARS['airline_name[' . $i . ']']);
			$flight_no = addslashes($HTTP_POST_VARS['flight_no[' . $i . ']']);
			$airline_name_departure = addslashes($HTTP_POST_VARS['airline_name_departure[' . $i . ']']);
			$flight_no_departure = addslashes($HTTP_POST_VARS['flight_no_departure[' . $i . ']']);
			$airport_name = addslashes($HTTP_POST_VARS['airport_name[' . $i . ']']);
			$airport_name_departure = addslashes($HTTP_POST_VARS['airport_name_departure[' . $i . ']']);
			$arrival_date = tep_get_date_db($HTTP_POST_VARS['arrival_date[' . $i . ']']);
			$arrival_time = addslashes($HTTP_POST_VARS['arrival_time[' . $i . ']']);
			$departure_date = tep_get_date_db($HTTP_POST_VARS['departure_date[' . $i . ']']);
			$departure_time = addslashes($HTTP_POST_VARS['departure_time[' . $i . ']']);
			$the_flight_query = tep_db_query("select * from  " . TABLE_ORDERS_PRODUCTS_FLIGHT ." where orders_products_id = '" . (int)$orders_products_id . "'");
			$the_flight = tep_db_fetch_array($the_flight_query);
			$orders_id = $the_flight['orders_id'];

			$flight_data_array = array('orders_id'=> $oID,
			'products_id'=> $products_id,
			'airline_name'=> $airline_name,
			'flight_no'=>$flight_no,
			'airline_name_departure'=>$airline_name_departure,
			'flight_no_departure'=>$flight_no_departure,
			'airport_name'=>$airport_name,
			'airport_name_departure'=>$airport_name_departure,
			'arrival_date'=>$arrival_date,
			'arrival_time'=>$arrival_time,
			'departure_date'=>$departure_date,
			'departure_time'=>$departure_time
			);
			if ($orders_id == '') {
				$flight_data_array['orders_products_id'] = $orders_products_id;
				tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, $flight_data_array);
			} else {
				tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, $flight_data_array, 'update', 'orders_products_id ="'.(int)$orders_products_id.'"');
			}
			//记录航班信息历史
			tep_add_orders_product_flight_history($flight_data_array,$orders_products_id,$login_id);
			//记录更改信息到分配表
			$OOC->addRecord($login_id,5,$_GET['oID']);
			$customers_cellphone = addslashes($HTTP_POST_VARS['customers_cellphone['.$i.']']);
			$customers_cellphone_old = addslashes($HTTP_POST_VARS['customers_cellphone_old['.$i.']']);
			$customers_id = addslashes($HTTP_POST_VARS['customers_id']);
			//tep_db_query("update  " . TABLE_CUSTOMERS . " set customers_cellphone='$customers_cellphone' where customers_id = " . $customers_id . "  ");
			//更新电子参团凭证的紧急联系电话等信息，妈的
			tep_db_query("update  " . TABLE_ORDERS . " set guest_emergency_cell_phone='$customers_cellphone' where orders_id = " . $oID. "  ");
			//记录紧急联系电话更新历史
			tep_update_customers_cellphone_history($oID, $customers_cellphone, $customers_cellphone_old, $login_id);
			//E-ticket Log Start
			if( (addslashes(trim($HTTP_POST_VARS['depature_full_address['.$i.']'])) != addslashes(trim($HTTP_POST_VARS['old_depature_full_address['.$i.']']))) || (addslashes($HTTP_POST_VARS['hotel_checkout_date['.$i.']']) != addslashes($HTTP_POST_VARS['previous_hotel_checkout_date['.$i.']'])) || (addslashes($HTTP_POST_VARS['tour_arrangement['.$i.']']) != addslashes($HTTP_POST_VARS['previous_tour_arrangement['.$i.']'])) ){
				tep_get_eticket_log_content($oID, $orders_products_id, tep_get_admin_customer_name($login_id),0,0);
			}
			//E-ticket Log End
			//tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action','oID','products_id','fudate')).'oID='.$orders_id));
			$messageStack->add('Updated Successfully.', 'success');
			if ($messageStack->size > 0) {
				echo $messageStack->output();
			}
		}
		exit;
		//更新电子参团凭证信息 end }
		break;
	case "act_providers_conf":
		//发信息给供应商 start{
		if (isset($_POST['aryFormData'])) {
			$aryFormData = $_POST['aryFormData'];
			foreach ($aryFormData as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if (eregi('--leftbrack', $key)) {
						$key = str_replace('--leftbrack', '[', $key);
						$key = str_replace('rightbrack--', ']', $key);
					}
					$value2 = str_replace('@@amp;', '&', $value2);
					$value2 = str_replace('@@plush;', '+', $value2);
					$value2 = mb_convert_encoding($value2, CHARSET, 'UTF-8');
					$_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes2($value2);
					//echo "$key=>$value2<br>";
				}
			}
			$orders_id = tep_db_input($_POST['orders_id']);
			$provider_order_status_id = tep_db_input($_POST['provider_order_status_id_' . $_GET["opID"]]);
			$provider_comment = tep_db_input($_POST['provider_comment_' . $_GET["opID"]]);
			$orders_products_id = tep_db_input($_GET["opID"]);
			$products_id = tep_db_input($_POST['products_id_' . $_GET["opID"]]);
			if (tep_not_null($provider_order_status_id) && $provider_order_status_id > 0) {
				//howard added set admin_and_provider_confirmed=0 , lwkai added `supplier_deal_with_state`="1",`provider_order_status_id`="' . $provider_order_status_id . '" supplier_deal_with_state发言状态，provider_order_status_id地接状态
				tep_db_query('UPDATE `orders_products` SET `admin_and_provider_confirmed` ="0",`supplier_deal_with_state`="1",`provider_order_status_id`="' . $provider_order_status_id . '" WHERE `orders_products_id` = "' . $orders_products_id . '" LIMIT 1 ;');
				$sql_data_array = array('orders_products_id' => $orders_products_id,
				'provider_order_status_id' => $provider_order_status_id,
				'provider_comment' => $provider_comment,
				'provider_status_update_date' => 'now()',
				'popc_updated_by' => $login_id,
				'notify_usi4trip' => 1);
				tep_db_perform(TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY, $sql_data_array);

				$qry_providers = "SELECT pl.*, concat_ws(' ', pl.providers_firstname, pl.providers_lastname) as providers_name, p.provider_tour_code, pd.products_name, ta.agency_name, op.products_departure_date, op.products_departure_time, op.customer_invoice_no, ta.agency_code FROM " . TABLE_PROVIDERS_LOGIN . " pl, " . TABLE_TRAVEL_AGENCY . " ta, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_ORDERS_PRODUCTS . " op WHERE pl.providers_agency_id=ta.agency_id AND p.agency_id=ta.agency_id AND p.products_id=pd.products_id AND pl.providers_agency_id=p.agency_id AND p.products_id=op.products_id AND op.orders_id='" . $orders_id . "' AND op.orders_products_id='" . $orders_products_id . "' AND pl.providers_status=1";
				$mail_to = "";
				$qry_providers_detail = $qry_providers . " AND pl.providers_email_notification=1";
				$res_providers_detail = tep_db_query($qry_providers_detail);
				$orders_link = FILENAME_PROVIDERS_ORDERS . '?oID=' . $orders_products_id . '&action=edit_order';	//oID没错，供应商后台那边用oID表示orders_products_id
				$orders_prod_status = tep_get_provider_order_status_name($provider_order_status_id);
				$first_products_departure_date = "";

				if (tep_db_num_rows($res_providers_detail) > 0) {//Send to sub users
					$sents_mail_to = array();
					while ($row_providers_detail = tep_db_fetch_array($res_providers_detail)) {
						if(tep_not_null($row_providers_detail['products_departure_date']) && ($row_providers_detail['products_departure_date'] < $first_products_departure_date || $first_products_departure_date == "")){
							$first_products_departure_date = $row_providers_detail['products_departure_date'];
						}
						/* if(tep_not_null($mail_to)){
						$mail_to.=", ".$row_providers_detail['providers_firstname']." <".$row_providers_detail['providers_email_address'].">";
						}else{
						$mail_to=$row_providers_detail['providers_firstname']." <".$row_providers_detail['providers_email_address'].">";
						} */
						$agency_code = $row_providers_detail['agency_code'];
						$mail_subject = sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT, $orders_prod_status, $orders_id, $agency_code);
						if($provider_order_status_id=="1"){	//新下单要调用不同的邮件主题
							$mail_subject = sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT_NEW_ORDER, $orders_prod_status, $orders_id, $agency_code);
						}
						$mail_to = $row_providers_detail['providers_email_address'];
						if (IS_LIVE_SITES!=true) {
							$mail_to = "xmzhh2000@hotmail.com";
						}
						if (trim($row_providers_detail['providers_firstname']) == "") {
							$to_name = $row_providers_detail['agency_name'];
						} else {
							$to_name = $row_providers_detail['providers_firstname'];
						}

						$mail_body = '<b style=color:#000000;>'.$mail_subject.'</b>'."\n\n".sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY, tep_db_prepare_input($row_providers_detail['provider_tour_code']) . " (" . tep_db_prepare_input($row_providers_detail['products_name']) . ")", tep_date_short($row_providers_detail['products_departure_date']) . " " . $row_providers_detail['products_departure_time'], $orders_prod_status, ($row_providers_detail['customer_invoice_no'] != "") ? "\n Invoice#: " . $row_providers_detail['customer_invoice_no'] : "", $orders_link, $orders_link, STORE_OWNER, tep_get_admin_customer_name($login_id));
						$mail_to = strtolower($mail_to);
						if (in_array($mail_to, $sents_mail_to) != true) {
							$sents_mail_to[] = $mail_to;
							if (IS_LIVE_SITES!=true) {
								echo '<pre>'.$mail_body."\n发送给：".$mail_to.'</pre>';
							}
							tep_mail($to_name, $mail_to, $mail_subject, $mail_body, STORE_OWNER, 'order@usitrip.com');
							tep_mail($to_name, 'usitrip@gmail.com', $mail_subject, $mail_body, STORE_OWNER, 'order@usitrip.com');
						}
					}
				} else {//Send to root user
					$qry_providers_detail = $qry_providers . " AND pl.parent_providers_id=0";
					$res_providers_detail = tep_db_query($qry_providers_detail);
					$row_providers_detail = tep_db_fetch_array($res_providers_detail);
					$agency_code = $row_providers_detail['agency_code'];
					$mail_subject = sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT, $orders_prod_status, $orders_id, $agency_code);
					if($provider_order_status_id=="1"){	//新下单要调用不同的邮件主题
						$mail_subject = sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT_NEW_ORDER, $orders_prod_status, $orders_id, $agency_code);
					}
					//$mail_to=$row_providers_detail['providers_firstname']." <".$row_providers_detail['providers_email_address'].">";
					$mail_to = $row_providers_detail['providers_email_address'];
					if (IS_LIVE_SITES!=true) {
						$mail_to = "xmzhh2000@hotmail.com";
					}
					if (trim($row_providers_detail['providers_firstname']) == "") {
						$to_name = $row_providers_detail['agency_name'];
					} else {
						$to_name = $row_providers_detail['providers_firstname'];
					}

					$mail_body = '<b style=color:#000000;>'.$mail_subject.'</b>'."\n\n".sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY, tep_db_prepare_input($row_providers_detail['provider_tour_code']) . " (" . tep_db_prepare_input($row_providers_detail['products_name']) . ")", tep_date_short($row_providers_detail['products_departure_date']) . " " . $row_providers_detail['products_departure_time'], $orders_prod_status, ($row_providers_detail['customer_invoice_no'] != "") ? "\n Invoice#: " . $row_providers_detail['customer_invoice_no'] : "", $orders_link, $orders_link, STORE_OWNER, tep_get_admin_customer_name($login_id));
					if (IS_LIVE_SITES!=true) {
						echo '<pre>'.$mail_body."\n发送给：".$mail_to.'</pre>';
					}
					tep_mail($to_name, $mail_to, $mail_subject, $mail_body, STORE_OWNER, 'order@usitrip.com');
					tep_mail($to_name, 'usitrip@gmail.com', $mail_subject, $mail_body, STORE_OWNER, 'order@usitrip.com');	//备份到gmail邮箱！
				}

				//Howard 添加已下单给地接记录表 start {
				tep_add_or_sub_sent_provider_not_re_rows ($orders_products_id, $login_id, 'add');
				//Howard 添加已下单给地接记录表 end }

				//amit added blinking management start
				//回复给地接后，此订单在订单列表中停止闪烁
				tep_get_order_start_stop($orders_id,0);
				//amit added blinking management end

				//Howard added 当向供应商发送信息时自动对应更新订单状态 start{
				if(in_array($provider_order_status_id, array("1",/*"8",*/"9","10","11","12","13","15","19","20","25","29","33"))){	//数字代表发地接的状态，可在provider order status中查到
					$_tmp_orders_status = '100009';
					$_tmp_time = (int)((strtotime($first_products_departure_date) - time())/86400);
					if(($provider_order_status_id=="1" && $_tmp_time >= 0 && $_tmp_time <= 7 ) || in_array($provider_order_status_id, array("13","20")) ){	//下单给地接的日期距离行程出发日期7天以内 或 最紧急的有问题的订单；请最后确认一下这个订单；
						$_tmp_orders_status = '100127';
					}elseif(in_array($provider_order_status_id, array("12","15","19","25","29"))){	//请确认/更新航班信息；请更新出发时间或乘车地点；需要更新的订单；请再次确认；请提供发票
						$_tmp_orders_status = '100122';
					}/*elseif($provider_order_status_id=="8"){	//向地接发送"请取消"的请求
						$_tmp_orders_status = '100134';	//财务取消及换团办理中的：已为客人取消订单，退款办理中
					}*/elseif($provider_order_status_id=="33"){	//选择 【询问报价；请回复】 点击发送给供应商后 订单状态自动更新为 1-06【已询价，等回复】
						$_tmp_orders_status = '100149';
					}

					$_last_modified = date('Y-m-d H:i:s');
					tep_db_query("update " . TABLE_ORDERS . " set orders_status = '".$_tmp_orders_status."', last_modified='".$_last_modified."', next_admin_id=0, need_next_admin=0, need_next_urgency='' WHERE orders_id = '".tep_db_input($orders_id)."' ");
					tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "(orders_id, orders_status_id, date_added, customer_notified, updated_by, comments) values ('" . tep_db_input($orders_id) . "', '".$_tmp_orders_status."', '".$_last_modified."', '0','".CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID."','下单或更新给地接，产品ID:".$products_id."')");

					//向供应商开放最新的航班信息
					if(in_array($provider_order_status_id, array("1","9","11","15"))){
						tep_db_query('UPDATE '.TABLE_ORDERS_PRODUCTS_FLIGHT.' SET sent_confirm_email_to_provider="1" WHERE orders_products_id="'.(int)$orders_products_id.'" ');
					}
					//向供应商开放新的上车时间和地址
					if(in_array($provider_order_status_id, array("1","29"))){
						tep_db_query('UPDATE '.TABLE_ORDERS_PRODUCTS.' SET products_departure_location_sent_to_provider_confirm="1" WHERE orders_products_id="'.(int)$orders_products_id.'" ');
					}
				}
				//Howard added 当向供应商发送信息时自动对应更新订单状态 end}
				echo PROVIDERS_CONFIRMATION_SENT;
			}
		}
		//取消未产生费用，有更新的订单的提醒
		tep_set_order_up_no_change_price($orders_id,'0','发信息给供应商后。取消未产生费用，有更新的订单的提醒');
		exit;
		//发信息给供应商 end}
		break;
	case "change_red_border_fields":
		//update red boxes - start {
		$orders_products_id = $_GET['orders_products_id'];
		$show_red_border[0] = '';
		$show_red_border[1] = '';
		$show_red_border[2] = '';
		$show_red_border[3] = '';

		$fetch_original_value = tep_db_fetch_array(tep_db_query("select is_adjustments_needed from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id='" . $orders_products_id . "'"));
		$show_red_border = explode("||", $fetch_original_value['is_adjustments_needed']);
		$update_string_element = (int) tep_db_input($_GET['update_string']);
		$show_red_border[$update_string_element] = '';
		$update_string = $show_red_border[0] . '||' . $show_red_border[1] . '||' . $show_red_border[2] . '||' . $show_red_border[3];
		tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set is_adjustments_needed = '" . tep_db_input($update_string) . "' where orders_products_id='" . $orders_products_id . "'");
		echo '<span class="messageStackSuccess order_default" height>Updated.</span>#' . $_GET['response_id'];
		exit;
		//update red boxes - end }

		break;
}
//各种action动作 end}
//TMD，后面还有action动作自己去查

// add provider comment start
if(isset($_GET['action_addcomment']) && $_GET['action_addcomment']=='true') {
	if($_GET['comment']!=''){
		$_GET['comment'] = str_replace('|plushplush|','+',$_GET['comment']);
		$_GET['comment'] = str_replace('|ampamp|','&',$_GET['comment']);
		$_GET['comment'] = str_replace('|hashhash|','#',$_GET['comment']);
		$_GET['comment'] = str_replace('|newline|','<br>',$_GET['comment']);

		echo $product_id = $_GET['product_id'].'|!!!!!|';
		$qry_provider_info = "select a.agency_id from ".TABLE_PRODUCTS." as p, ".TABLE_TRAVEL_AGENCY." as a where p.agency_id = a.agency_id AND p.products_id = '".(int)$product_id."'";
		$res_provider_info = tep_db_query($qry_provider_info);
		$row_provider_info = tep_db_fetch_array($res_provider_info);
		tep_db_query("update ".TABLE_TRAVEL_AGENCY." set res_notes = '".addslashes($_GET['comment'])."' where agency_id = '".$row_provider_info['agency_id']."'");
		echo stripslashes($_GET['comment']);
	}
	exit;
}

//tom added 日本团的自带磁带提示信息
$japan_array = array('JPTK82-1407','JPTK82-1413','JPTK82-1414','JPTK82-1414','JPTK82-1414','JPTK82-1414','JPTK82-1413','JPTK82-1413','JPTK82-1402','JPTK82-1404');
//followup exclude array
$followup_exclude_arr = array('100002','100097','100095','6','100005','100006','100077'); //todo:Task-13_订无论这些订单之前处在何标签下，当状态更新为以上7种中任何一种时，都不显示在folloe up标签下。

//检查订单客户资料
$check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int) $oID . "'");
$check_status = tep_db_fetch_array($check_status_query);

//电子邮件的签名信息
//$login_publicity_name = ucfirst(get_login_publicity_name());
$login_publicity_name = tep_get_admin_customer_name($login_id);
$phones = tep_get_us_contact_phone();
$EmailSignature = sprintf(EMAIL_FOOTER_SIGNATURE, $login_publicity_name, '美加热线：'.$phones[1]['phone'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$phones[0]['phone']."\n 中国热线：".$phones[2]['phone'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$phones[4]['name'].$phones[4]['phone']);


$is_travel_comp_order = false;
if ((int) is_travel_comp((int) $_GET['oID'])) {
	$is_travel_comp_order = true;
}


//自动更新客服对无主订单的所有权，提成为0.5
if(tep_not_null($_GET['oID'])){
	//tep_update_order_orders_owner($login_id, $_GET['oID'], '0.5');
}

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
include(DIR_WS_CLASSES . 'order.php');

// New "Status History" table has different format.
$OldNewStatusValues = (tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "old_value") && tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "new_value"));
$CommentsWithStatus = tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "comments");
$SeparateBillingFields = tep_field_exists(TABLE_ORDERS, "billing_name");

// Optional Tax Rate/Percent
$AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"
$orders_statuses = array();
$orders_status_array = array();
$not_in_ids = '11,47,100011,100024,100034,100035,100039,100028,100041,100047,100049,100050,100058,100059';
$orders_status_query = tep_db_query("select orders_status_id, orders_status_name, orders_status_name_1, os_groups_id, sort_id from " . TABLE_ORDERS_STATUS . " where orders_status_id not in(" . $not_in_ids . ") and orders_status_display='1' AND language_id = '" . (int) $languages_id . "' ORDER BY `orders_status_name` ASC ");
while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	$orders_statuses[] = array('id' => $orders_status['orders_status_id'],
	'text' => $orders_status['orders_status_name'],
	'group' => $orders_status['os_groups_id'],
	'sort' => $orders_status['sort_id']);
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name_1'];
}

$orders_ship_method = array();
$orders_ship_method_array = array();
$orders_ship_method_query = tep_db_query("select ship_method from orders_ship_methods");
while ($orders_ship_methods = tep_db_fetch_array($orders_ship_method_query)) {
	$orders_ship_method[] = array('id' => stripslashes2($orders_ship_methods['ship_method']),
	'text' => stripslashes2($orders_ship_methods['ship_method']));
	$orders_ship_method_array[$orders_ship_methods['ship_method']] = $orders_ship_methods['ship_method'];
}

//amit added to fixed code if no payment method found start
$paymentmethodname = tep_get_order_payment_method_name($oID);
if ($paymentmethodname != '') {
	$checkoder_pay_method = "select * from orders_pay_methods where pay_method=\"" . tep_db_input($paymentmethodname) . "\"";
	$checkoder_pay_method_query = tep_db_query($checkoder_pay_method);
	if (tep_db_num_rows($checkoder_pay_method_query) == 0) {
		//insert new payment method start
		$inser_new_pay_m = "insert into orders_pay_methods
(pay_method, date_added)
values (\"" . tep_db_input(stripslashes2($paymentmethodname)) . "\", now())";
		tep_db_query($inser_new_pay_m);
		//insert new payment method end
	}
}
//amit added to fixe code if no payment method found end

$orders_pay_method = array();
$orders_pay_method_array = array();
$orders_pay_method_query = tep_db_query("select pay_method from orders_pay_methods Order By date_added DESC ");
while ($orders_pay_methods = tep_db_fetch_array($orders_pay_method_query)) {
	$orders_pay_method[] = array('id' => $orders_pay_methods['pay_method'],
	'text' => $orders_pay_methods['pay_method']);
	$orders_pay_method_array[$orders_pay_methods['pay_method']] = $orders_pay_methods['pay_method'];
}
$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'edit');

//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
$order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int) $oID . "'");
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################

if (tep_not_null($action)) {
	switch ($action) {
		// Activate gift certificate
		case 'activate':
			$update_query = tep_db_query("update `" . TABLE_GIFT_CERTIFICATES . "` set gc_status=1 where gc_code='" . $gc_code . "'");
			$order = new order($oID);
			tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_GIFT_CERTIFICATE_ACTIVATED_SUBJECT, sprintf(EMAIL_GIFT_CERTIFICATE_ACTIVATED_BODY, $order->customer['name'], $gc_code, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			$messageStack->add_session(MSG_GC_MAIL_SENT, 'gc_sent');
			tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'step', 'gc_code', 'fudate'))));
			break;

			// Update Order
		case 'update_order':
			$update_products = $_POST['update_products'];	//array
			$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
			//Howard added check last modified start {
			$check_last_sql = tep_db_query('select last_modified from orders where orders_id ="' . $oID . '" ');
			$check_last = tep_db_fetch_array($check_last_sql);
			if ($check_last['last_modified'] != $HTTP_POST_VARS['old_last_modified']) {
				echo '<script>
					if(confirm("' . db_to_html("在您打开本页面后有人更新了此订单所以您当前的订单内容不是最新的!\\r\\n\\r\\n为了确保别人的修改不被覆盖，请选择是否自动刷新此页面：\\r\\n“确定”：自动刷新此页面\\r\\n“取消”：不刷新，返回前一页") . '")){
						location="' . tep_href_link('edit_orders.php', 'oID=' . $oID . '&action=edit') . '";
					}else{
						history.back();
					}
					</script>';
				exit;
			}
			//Howard added check last modified end }

			$order = new order($oID);
			$order_old_data = $order;	//提交之前的订单旧数据
			$status = tep_db_prepare_input($HTTP_POST_VARS['status']);
			$previous_status = tep_db_prepare_input($HTTP_POST_VARS['previous_status']);
			$comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);

			$error = false;
			$cancel_reason = '';
			//当修改时更改那些没有归属人的订单{
			if($_POST&&!isset($_POST['ajax'])&&in_array($_SESSION['login_groups_id'], array(4,5,7,48))&&$comments){
				$str_sql='select orders_id from orders where orders_owners="" AND orders_id='.(int)$_GET['oID'];
				$arr_status_tmp=tep_db_fetch_array(tep_db_query($str_sql));
				if($arr_status_tmp){
					$my_user_id=tep_get_job_number_from_admin_id($_SESSION['login_id']);
					$str_sql='update orders set orders_owners='.$my_user_id.',owner_is_change=0,is_top=0,owner_id_change_str=CONCAT(owner_id_change_str,";'.$my_user_id.'于'.date('m月d日').'再更新，订单归属人更改为'.$my_user_id.'") where orders_id='.(int)$_GET['oID'];
					tep_db_query($str_sql);
				}
			}
			//当修改时更改那些没有归属人的订单}
			//订单更新时必须填写comments字段的内容
			if(strlen(preg_replace('/[[:space:]]+/','',strip_tags($comments))) < 2){
				$error = true;
				$messageStack->add_session('警告：本次更新失败。原因：Comments的内容必须填写！', 'error');
			}
			//修改订单，修改订单归属开始{
			$OOC->runing($_GET['oID'],$status,$login_id);
			//修改订单，修改订单归属开始{
			if($order_old_data->info['orders_status']!="6" && $status == "6" ){
				if($access_full_edit != 'true' && $access_order_cost != 'true'){	//财务和会计才能手工取消订单
					$error = true;
					$messageStack->add_session('财务和会计才能手工取消订单。', 'error');
				}
			}

			if (is_array($_POST['cancel_reason'])) {
				foreach ($_POST['cancel_reason'] as $key => $val) {
					if ($val == 'other') {
						//$cancel_reason .= 'other-'.$_POST['cancel_reason_other'].',';
						$cancel_reason .= $_POST['cancel_reason_other'] . ',';
					} else {
						$cancel_reason .= $val . ',';
					}
				}
			} else {
				$cancel_reason = '';
			}
			$cancel_reason = substr($cancel_reason, 0, -1);

			$cancel_with_provider = '';
			if (is_array($_POST['cancel_with_provider'])) {
				foreach ($_POST['cancel_with_provider'] as $key => $val) {
					$cancel_with_provider .= $val . ',';
				}
			} else {
				$cancel_with_provider = '';
			}
			$cancel_with_provider = substr($cancel_with_provider, 0, -1);

			if ($status != $previous_status && $status == '100021') {
				if ($cancel_reason == '') {
					$error = true;
					$messageStack->add_session('Reason for cancellation should be entered.', 'error');
				}
			}
			if (tep_not_null($cancel_reason) || tep_not_null($cancel_with_provider) || tep_not_null($cancel_date) || tep_not_null($cancellation_fee) || tep_not_null($provider_cancellation_fee) || tep_not_null($cancel_reason_detail)) {
				if ($cancel_reason == '') {
					$error = true;
					$messageStack->add_session('Reason for cancellation should be entered.', 'error');
				}
			}

			// start add for credit issued by iipl-326
			$credit_reason = '';
			if (is_array($_POST['credit_reason'])) {
				foreach ($_POST['credit_reason'] as $key => $val) {
					$credit_reason .= $val . ',';
				}
			} else {
				$credit_reason = '';
			}
			$credit_reason = substr($credit_reason, 0, -1);

			$credit_with_provider = '';
			if (is_array($_POST['credit_with_provider'])) {
				foreach ($_POST['credit_with_provider'] as $key => $val) {
					$credit_with_provider .= $val . ',';
				}
			} else {
				$credit_with_provider = '';
			}
			$credit_with_provider = substr($credit_with_provider, 0, -1);

			if ($status != $previous_status && $status == '100080') {
				if ($credit_reason == '') {
					$error = true;
					$messageStack->add_session('Reason for credit issued should be entered.', 'error');
				}
				$addon_comments = sprintf(TXT_CREDIT_ISSUED_COMMENTS, $credit_fee_percent, $credit_fee, $update_customer_email_address);
				$comments = $addon_comments . $comments;
			}
			if (tep_not_null($credit_reason) || tep_not_null($credit_with_provider) || tep_not_null($credit_date) || tep_not_null($credit_fee) || tep_not_null($provider_credit_feee) || tep_not_null($credit_reason_detail)) {
				if ($credit_reason == '') {
					$error = true;
					$messageStack->add_session('Reason for credit issued should be entered.', 'error');
				}
			}
			// end credit issued added by iipl 326

			if ($error == true) {
				tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action', 'fudate')) . 'action=edit'));
				exit;
			}

			//Update customers_cellphone 更新客户账户的紧急联系电话
			tep_db_query("update  " . TABLE_CUSTOMERS . " set customers_cellphone='$update_customers_cellphone' where customers_id = " . (int) $order->customer['id'] . "  ");
			//记录紧急联系电话更新历史
			tep_update_customers_cellphone_history($oID, $update_customers_cellphone, $update_customers_cellphone_old, $login_id);
			//如果没有指定下一个处理人的话就把跟进紧急程度设置为空，同时把下一个处理人id也设为空
			$_need_next_urgency = $_POST['need_next_urgency'];
			$_next_admin_id = $_POST['next_admin_id'];
			if(!(int)$_POST['need_next_admin']){ $_need_next_urgency = ''; $_next_admin_id = '0'; }
			// Update Order Info
			$UpdateOrders = "update " . TABLE_ORDERS . " set admin_id_orders = '".$login_id."',
							guest_emergency_cell_phone = '".$update_customers_cellphone."',
							customers_name = '" . tep_db_input(stripslashes2($update_customer_name)) . "',
							customers_company = '" . tep_db_input(stripslashes2($update_customer_company)) . "',
							customers_street_address = '" . tep_db_input(stripslashes2($update_customer_street_address)) . "',
							customers_suburb = '" . tep_db_input(stripslashes2($update_customer_suburb)) . "',
							customers_city = '" . tep_db_input(stripslashes2($update_customer_city)) . "',
							customers_state = '" . tep_db_input(stripslashes2($update_customer_state)) . "',
							customers_postcode = '" . tep_db_input($update_customer_postcode) . "',
							customers_country = '" . tep_db_input(stripslashes2($update_customer_country)) . "',
							customers_telephone = '" . tep_db_input($update_customer_telephone) . "',
							customers_email_address = '" . tep_db_input($update_customer_email_address) . "',
							need_next_admin = '" . (int)$_POST['need_next_admin'] . "',
							next_admin_id = '".tep_db_input($_next_admin_id)."',
							need_next_urgency = '". tep_db_input($_need_next_urgency)."',
							followup_team_type = '" . tep_db_input($_POST['followup_team_type']) . "',";
			$UpdateOrders .= "is_top = '0',";	//只要更新过此单就不再置顶了
			//var_dump($OOC->checkIsChange(TABLE_ORDERS, 'customers_telephone', tep_db_input(stripslashes2($update_customer_telephone)),'orders_id='.tep_db_input($oID)));
			
			if($OOC->checkIsChange(TABLE_ORDERS, 'customers_name', tep_db_input(stripslashes2($update_customer_name)),'orders_id='.tep_db_input($oID))||$OOC->checkIsChange(TABLE_ORDERS, 'guest_emergency_cell_phone', tep_db_input(stripslashes2($update_customers_cellphone)),'orders_id='.tep_db_input($oID))){//当客户名称，客户电话更改的时候添加一个历史记录
				$OOC->addRecord($login_id,1,tep_db_input($oID));	
			}
		
			if(isset($_POST['set_accounting_todo_done']) && $_POST['set_accounting_todo_done']=="1" && $can_set_orders_accounting_todo_done === true){
				$UpdateOrders .= "need_accounting_todo = '0',";
			}else{	//通知财务处理该订单
				$tmp_status_id = array();
				$_sql = tep_db_query('SELECT orders_status_id FROM `orders_status` WHERE os_groups_id in (8,12);');
				while($_rows = tep_db_fetch_array($_sql)){
					$tmp_status_id[] = $_rows['orders_status_id'];
				}
				if(in_array($_POST['status'], (array)$tmp_status_id)){
					$UpdateOrders .= "need_accounting_todo = '1',";
				}
			}

			if ($SeparateBillingFields) {
				$UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes2($update_billing_name)) . "',
								billing_company = '" . tep_db_input(stripslashes2($update_billing_company)) . "',
								billing_street_address = '" . tep_db_input(stripslashes2($update_billing_street_address)) . "',
								billing_suburb = '" . tep_db_input(stripslashes2($update_billing_suburb)) . "',
								billing_city = '" . tep_db_input(stripslashes2($update_billing_city)) . "',
								billing_state = '" . tep_db_input(stripslashes2($update_billing_state)) . "',
								billing_postcode = '" . tep_db_input($update_billing_postcode) . "',
								billing_country = '" . tep_db_input(stripslashes2($update_billing_country)) . "',";
			}
			$UpdateOrders .= "delivery_name = '" . tep_db_input(stripslashes2($update_delivery_name)) . "',
							delivery_company = '" . tep_db_input(stripslashes2($update_delivery_company)) . "',
							delivery_street_address = '" . tep_db_input(stripslashes2($update_delivery_street_address)) . "',
							delivery_suburb = '" . tep_db_input(stripslashes2($update_delivery_suburb)) . "',
							delivery_city = '" . tep_db_input(stripslashes2($update_delivery_city)) . "',
							delivery_state = '" . tep_db_input(stripslashes2($update_delivery_state)) . "',
							delivery_postcode = '" . tep_db_input($update_delivery_postcode) . "',
							delivery_country = '" . tep_db_input(stripslashes2($update_delivery_country)) . "',
							payment_method = \"" . tep_db_input(stripslashes2($update_info_payment_method)) . "\",
							account_name = '" . tep_db_input($account_name) . "',
							account_number = '" . tep_db_input($account_number) . "',
							po_number = '" . tep_db_input($po_number) . "',
							last_modified = now(),";
			if ($status != $previous_status && $status == '6') {//Order Cancelled time
				$UpdateOrders .= "cancelled_time = now(),";
			}
			if ($login_groups_id == '1' || $access_cc_info == 'true') {//amti added for top see top admin and account group  only
				if (substr($update_info_cc_number, 0, 8) != "(Last 4)") {
					$UpdateOrders .= "cc_number = '" . scs_cc_encrypt($update_info_cc_number) . "',";
				}
				$UpdateOrders .= "cc_type = '" . tep_db_input($update_info_cc_type) . "',	cc_owner = '" . tep_db_input($update_info_cc_owner) . "',cc_cvv = '" . tep_db_input($update_info_cc_cvv) . "',cc_expires = '" . tep_db_input($update_info_cc_expires) . "',";
			}//amti added for top see top admin and account group  only
			//amit added blinking management start
			if(($status != $previous_status || tep_not_null($comments) != '') && $can_set_orders_is_blinking === true){	//只有操作员组才能取消地接更新提示的信息[和途风不一样]
				$UpdateOrders .= "is_blinking = '0',";
			}
			//amit added blinking management end
			/* update cancel reason and cacellatio fee - start */
			$UpdateOrders .= "provider_cancellation_fee = '" . tep_db_input($provider_cancellation_fee) . "', cancellation_fee = '" . tep_db_input($cancellation_fee) . "', ";
			if(tep_not_null($provider_cancellation_fee)){
				$UpdateOrders .= "provider_cancellation_fee_1 = '" . tep_db_input($provider_cancellation_fee) . "', ";
			}

			if($status == '100045'){
				$provider_cancellation_penalty = trim($_POST['provider_cancellation_penalty']);
				if(tep_not_null($provider_cancellation_penalty)){
					$UpdateOrders .= "provider_cancellation_penalty = '" . tep_db_input($provider_cancellation_penalty) . "', ";
					$provider_cancellation_penalty_history = $provider_cancellation_penalty.'||'.tep_get_admin_customer_name($login_id).'||'.$login_id.'||'. date("m/d/Y (h:i a)") . '||==||';
					$UpdateOrders .= "provider_cancellation_penalty_history = concat(provider_cancellation_penalty_history, '" . tep_db_input($provider_cancellation_penalty_history) . "'), ";
				}
			}

			if ($status == '100021') {//一旦订单状态变更为Cancellation Form Received，输入id="div_cancel_reason"里面的设定的内容
				if (tep_not_null($cancel_reason) || tep_not_null($cancel_with_provider) || tep_not_null($cancel_date) || tep_not_null($cancellation_fee) || tep_not_null($provider_cancellation_fee) || tep_not_null($cancel_reason_detail)) {
					$get_order_total = tep_db_fetch_array(tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'"));
					$cancellation_history = tep_db_input($cancel_item).'$$'.tep_db_input($cancel_item_orders_products_id).'||'.tep_db_input($cancel_reason).'||'.tep_db_input($cancel_with_provider).'||'.tep_db_input($cancel_date).'||'.tep_db_input($cancellation_fee).'($'.number_format($get_order_total['value'], 2).'x'.tep_db_input($cancellation_fee_percent).'%)||'.tep_db_input($provider_cancellation_fee).'||'.tep_db_prepare_input($cancel_reason_detail).'||'.tep_get_admin_customer_name($login_id).'||'.date("m/d/Y (h:i a)").'||==||';

					//$cancellation_history = tep_db_input($cancel_item) . '||' . tep_db_input($cancel_reason) . '||' . tep_db_input($cancel_with_provider) . '||' . tep_db_input($cancel_date) . '||' . tep_db_input($cancellation_fee) . '($' . number_format($get_order_total['value'], 2) . 'x' . tep_db_input($cancellation_fee_percent) . '%)||' . tep_db_input($provider_cancellation_fee) . '||' . tep_db_prepare_input($cancel_reason_detail) . '||' . tep_get_admin_customer_name($login_id) . '||' . date("m/d/Y (h:i a)") . '||==||';
					$UpdateOrders .= "cancellation_history = concat(cancellation_history, '" . tep_db_input($cancellation_history) . "'), ";
				}
			}

			if ($status == '100094') {
				$payment_methods_str = "";
				if(tep_not_null($_POST['payment_methods'])){
					$payment_methods_str = implode('<::>', $_POST['payment_methods']);
				}
				$UpdateOrders .= "payment_methods = '".tep_db_input($payment_methods_str)."', ";
			}

			if ($status == '100097') {
				$UpdateOrders .= "refund_total = '".tep_db_input($_POST['refund_total'])."', ";
			}

			if ($status == '6') {
				//howard added write vale to "orders_cancelled_total"
				$check_old_status_sql = tep_db_query('SELECT count(*) as total FROM `orders` WHERE orders_id="' . (int) $oID . '" AND orders_status=6 ');
				$check_old_status_row = tep_db_fetch_array($check_old_status_sql);
				if (!(int) $check_old_status_row['total']) {
					$sql = tep_db_query('SELECT value FROM `orders_total` WHERE orders_id="' . (int) $oID . '" AND class="ot_total" ');
					$row = tep_db_fetch_array($sql);
					if ((int) $row['value']) {
						tep_db_query('UPDATE orders SET orders_cancelled_total="' . $row['value'] . '" WHERE orders_id="' . (int) $oID . '" ');
					}
				}
				//howard added write vale to "orders_cancelled_total" end
			}

			// Start for credit issue
			if ($status == '100080') {/*
				if (tep_not_null($credit_reason) || tep_not_null($credit_with_provider) || tep_not_null($credit_date) || tep_not_null($credit_fee) || tep_not_null($provider_credit_fee) || tep_not_null($credit_reason_detail)) {
				$get_order_total = tep_db_fetch_array(tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'"));
				$credit_history = tep_db_input($credit_item) . '$$' . tep_db_input($credit_item_orders_products_id) . '||' . tep_db_input($credit_reason) . '||' . tep_db_input($credit_with_provider) . '||' . tep_db_input($credit_date) . '||' . tep_db_input($credit_fee) . '($' . number_format($get_order_total['value'], 2) . 'x' . tep_db_input($credit_fee_percent) . '%)||' . tep_db_input($provider_credit_fee) . '||' . tep_db_prepare_input($credit_reason_detail) . '||' . tep_get_admin_customer_name($login_id) . '||' . date("m/d/Y (h:i a)") . '||==||';
				$UpdateOrders .= "creditissued_history = concat(creditissued_history, '" . tep_db_input($credit_history) . "'), ";
				$customers_id = tep_db_fetch_array(tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = " . tep_db_input($oID)));
				$get_credit_amt = tep_db_fetch_array(tep_db_query("select customers_credit_issued_amt from " . TABLE_CUSTOMERS . " where customers_id = " . $customers_id['customers_id'] . " "));
				tep_db_query("update  " . TABLE_CUSTOMERS . "  set customers_credit_issued_amt = " . ($get_credit_amt['customers_credit_issued_amt'] + $credit_fee) . " where customers_id = " . $customers_id['customers_id'] . " ");

				$sql_data_credits_array = array('customers_id' => (int) $customers_id['customers_id'],
				'orders_id' => tep_db_input($oID),
				'products_id' => tep_db_input($credit_item),
				'credit_bal' => $credit_fee,
				'credit_comment' => '$' . number_format(($update_products[$credit_item_orders_products_id]['final_price']), 2) . 'x' . tep_db_input($credit_fee_percent) . '%',
				'date_added' => 'now()',
				'admin_id' => $login_id
				);
				tep_db_perform(TABLE_CUSTOMERS_CREDITS, $sql_data_credits_array);
				}
				*/
			}
				/* update cancel reason and cacellatio fee - start */
				$UpdateOrders .= "orders_status = '" . tep_db_input($status) . "'";
				if (!$CommentsWithStatus) {
					$UpdateOrders .= ", comments = '" . tep_db_input($comments) . "'";
				}
				$UpdateOrders .= " where orders_id = '" . tep_db_input($oID) . "';";
				if ((int) $status != 0) {
					tep_db_query($UpdateOrders);
					$Query1 = "update orders set last_modified = now() where orders_id = '" . tep_db_input($oID) . "';";
					tep_db_query($Query1);
					// lwkai added 记录100154状态的特殊处理 start
					// 如果订单状态为100154[主管审核有问题，需销售继续跟进]，并且与原始状态不相等，则记录下改状态的时间，
					//因为要计算主管设置后多久销售没处理则放出来，让所有人能看到，能及时处理
					// 如果状态为100154 并且之前不是同一状态
					if (tep_db_input($status) != $order_old_data->info['orders_status'] && tep_db_input($status) == '100154') { 
						$Query2 = "replace into orders_status_update set status='" . tep_db_input($status) . "',urgency_name='" . tep_db_input($_need_next_urgency) . "',change_date='" . date("Y-m-d H:i:s") . "',orders_id='" . tep_db_input($oID) . "'" ;
						// 记录此操用的时间点，供后面判断此操作过去的时长
						tep_db_query($Query2);
						// 如果在此之前已经有过超时处理，订单表中判断是否需要所有人可见的状态已经变为可见，改回只有当前处理人可见状态
						tep_db_query("update orders set is_timeout=0 where orders_id='" . intval($oID) . "'");
					} else {// 如果在同一时间内，主管又更改了紧急度，则需要对紧急度进行变更
						$Query2 = "select urgency_name from orders_status_update where orders_id='" . tep_db_input($oID) . "'";
						$result = tep_db_query($Query2);
						$rs = tep_db_fetch_array($result);
						if (tep_not_null($rs) && $rs['urgency_name'] != tep_db_input($_need_next_urgency)) {
							// 改变紧急度状态
							tep_db_query("update orders_status_update set urgency_name='" . tep_db_input($_need_next_urgency) . "' where orders_id='" . tep_db_input($oID) . "'");
						} 
					}
					// lwkai added 记录100154状态的特殊处理 end
					$order_updated = true;
				}
				//Update Status History & Email Customer if Necessary
				//if ($order->info['orders_status'] != $status && (int)$status != 0){
				//always update date and time on order_status
				if (( ($check_status['orders_status'] != $status) || $comments != '' || ($status == DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE)) && (int) $status != 0) {
					//==start=========weiyi to capture 请Weiyi 收费/Need Check Bank Account====================
					//只发送邮件到
					//==startus 100066  jason  Weiyi: Tour Provider Already Confirmed Cash Collected for us
					//更新to chage capture100095及Need Check Bank Account100071这两个状态时，无论勾选notify customer还是不勾选，均发送邮件到payment@usitrip.com
					$noMailHeader = false;
					if ($status == '100042' || $status == '100066') {//|| $status == '100071' || $status =='100095' 和notify accounting有冲突 Tracy要求取消 2011-7-29 vincent
						$HTTP_POST_VARS['notify'] = 'on';
						$_POST['send_travel_companion_user'] = '-1';
						if (IS_QA_SITES==true) {
							$check_status['customers_email_address'] = $check_status['customers_name'] = 'xmzhh2000@126.com';
						} else {
							$check_status['customers_email_address'] = $check_status['customers_name'] = 'payment@usitrip.com';
						}
						$noMailHeader = true;
					}
					//==end=========weiyi to capture 请Weiyi 收费/Need Check Bank Account======================

					//============= start ========== 获取当前订单的客户手机号码 ===============================================
					$customer_sql = "select confirmphone, customers_cellphone, customers_mobile_phone, customers_telephone from customers where customers_id = '" . $order->customer['id'] . "'";
					$customer_query_result = tep_db_query($customer_sql);
					$row_customer_info = tep_db_fetch_array($customer_query_result);
					$phone = '';
					$result_phone = check_phone($row_customer_info['confirmphone']);
					if (!empty($result_phone))$phone = $result_phone[0];
					else {
						$result_phone = check_phone($row_customer_info['customers_cellphone']);
						if (!empty($result_phone))$phone = $result_phone[0];
						else {
							$result_phone = check_phone($row_customer_info['customers_mobile_phone']);
							if (!empty($result_phone))$phone = $result_phone[0];
							else {
								$result_phone = check_phone($row_customer_info['customers_telephone']);
								if (!empty($result_phone))$phone = $result_phone[0];
							}
						}
					}
					//============== end =========== 获取当前订单的客户手机号码 ===============================================

					//==========Send payment received  jason 2011/3/21 start===============================================
					if($status == '100027' && !empty($phone) && isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
						include('sms_send.php');
						if(preg_match('/'.preg_quote('[收款确认通知]').'/',CPUNC_USE_RANGE)){
							preg_match("/(确定收到您的团款)(.*?)(，正在进一步处理)/i", $HTTP_POST_VARS['comments'], $matches);
							$content = "您的订单（".$oID."）款项（".$matches[2]."）已确认，我们将于2个工作日内向您通报订单最新情况。详情请登陆“用户中心”或您的邮箱查看。祝您愉快！";
							if (sms_send($phone, $content) == 1) {
								$messageStack->add_session($phone . '旅客的确认收款短信发送成功！', 'success');
							} else {
								$messageStack->add_session($phone . '旅客的确认收款短信发送失败！', 'error');
							}
						}
						if(preg_match('/'.preg_quote('[旅游保险提醒]').'/',CPUNC_USE_RANGE)){
							$content = "温馨提示：建议您在预订旅行的同时，也购买旅行保险！当因不可抗力或突发事件造成航班延误、旅行取消时，旅行保险能维护您的权益。";
							if (sms_send($phone, $content) == 1) {
								$messageStack->add_session($phone . '提醒旅客购买旅行保险的短信发送成功！', 'success');
							} else {
								$messageStack->add_session($phone . '提醒旅客购买旅行保险的短信发送失败！', 'error');
							}
						}
					}
					//==========Send payment received	 jason 2011/3/21 end===============================================

					//==========Send flight information needed  jason 2011/3/21 start===============================================
					if(preg_match('/'.preg_quote('[航班信息填写提醒]').'/',CPUNC_USE_RANGE) && ($status == '100012' || ($order_updated && $status == 100083 && is_array($HTTP_POST_VARS['checkbox_100083']) && in_array("Flight", $HTTP_POST_VARS['checkbox_100083']))) && !empty($phone) && isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
						include('sms_send.php');
						$content = "您的订单（".$oID."）还未填写航班信息，您可以：1.登录“用户中心”填写；2.邮件至 service@usitrip.com ；所需内容（订单号、航班号、航空公司、到达日期及时间）请务必在参团前72小时完成，否则无法安排接机。（温馨提示：如您提前到达或延后离开，欢迎使用酒店增订服务，详询客服）";
						if (sms_send($phone, $content) == 1) {
							$messageStack->add_session($phone . '提醒旅客机场接应服务的航班信息未填写短信发送成功！', 'success');
						} else {
							$messageStack->add_session($phone . '提醒旅客机场接应服务的航班信息未填写短信发送失败!', 'error');
						}
					}
					//==========Send flight information needed	 jason 2011/3/21 end===============================================

					//==========Send Flight Information Needed (Travel Companion）  yichi 2011/10/21 start===============================================
					if(preg_match('/'.preg_quote('[航班信息填写提醒]').'/',CPUNC_USE_RANGE) && $status == '100078' && !empty($phone) && isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
						include('sms_send.php');
						$content = "您的结伴同游订单（#".$oID."）还未填写航班信息，航班信息提交详情已发送至您的邮箱。请您在与您的同伴确定接机方式后，将您的航班信息（订单号、航班号、航空公司、到达日期及时间）回复邮件至 service@usitrip.com ；请务必在参团前72小时完成，否则无法安排接机。（温馨提示：如您提前到达或延后离开，欢迎使用酒店增订服务，详询客服）";
						if (sms_send($phone, $content) == 1) {
							$messageStack->add_session($phone . '提醒结伴同游旅客机场接应服务的航班信息未填写短信发送成功！', 'success');
						} else {
							$messageStack->add_session($phone . '提醒结伴同游旅客机场接应服务的航班信息未填写短信发送失败!', 'error');
						}
					}
					//==========Send Flight Information Needed (Travel Companion）  yichi 2011/10/21 end===============================================

					//==========Send Cancelled  jason 2011/3/21 start===============================================
					/* if(preg_match('/'.preg_quote('[取消订单通知]').'/',CPUNC_USE_RANGE) && $status=='6' && !empty($phone) && isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')){
					include('sms_send.php');
					$content = "尊敬的用户，您好！感谢你订购走四方网产品，很遗憾，这次没能为您成功预订此团，我们衷心希望您下一次还选择我们的服务。期待您的再次访问！";
					if(sms_send($phone,$content)==1){
					$messageStack->add_session($phone.'旅客产品未订购成功的短信发送成功！', 'success');
					}else{
					$messageStack->add_session($phone.'旅客产品未订购成功的短信发送失败！', 'error');
					}
					} */
					//==========Send Cancelled	 jason 2011/3/21 end===============================================

					//==========Send ticket issued  jason 2011/3/21 start==============================================={
					//$striposproduct_name 产品名称
					//$checkairportpick 机场接机
					if(preg_match('/'.preg_quote('[度假行程确认完毕]').'/',CPUNC_USE_RANGE) && $status == '100002' && !empty($phone) && isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
						if (send_ticket_issued_sms($order->products[$i]['id'], $order->products[0]['name'], $order->products[0]['attributes'], $oID, $phone)) {
							$messageStack->add_session($phone . '提醒旅客的行程或机场接应已确认并签发电子客票的短信发送成功！', 'success');
						} else {
							$messageStack->add_session($phone . '提醒旅客的行程或机场接应已确认并签发电子客票的短信发送失败！', 'error');
						}
					}
					//==========Send ticket issued jason 2011/3/21 end===============================================}

					// 邮件通知客人 Notify Customer {
					$customer_notified = '0';
					if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
						$notify_comments = '';
						if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
							$notify_comments = $noMailHeader ? db_to_html($comments) . "\n\n" : sprintf(EMAIL_TEXT_COMMENTS_UPDATE, db_to_html($comments)) . "\n\n";
						}
						$email = '';
						$order_url = tep_catalog_href_link('orders_travel_companion_info.php', 'order_id=' . $oID, 'SSL');
						if (!isset($_POST['send_travel_companion_user']) || !$_POST['send_travel_companion_user']) {
							$email.= EMAIL_TEXT_DEAR . db_to_html($check_status['customers_name']) . "\n\n";
							$order_url = tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL');
						}
						if (!$noMailHeader) {
							$email.=
							//							EMAIL_TEXT_DEAR_A . "\n" .
							//							EMAIL_SEPARATOR . "\n" .
							//							STORE_NAME . "\n" .
							//							EMAIL_SEPARATOR . "\n" .
							EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" .
							EMAIL_TEXT_DATE_ORDERED . ' ' . chardate($check_status['date_purchased'], "D", 1).'（'. tep_date_long($check_status['date_purchased']) . "）\n".
							EMAIL_TEXT_INVOICE_URL . ' ' . $order_url . "\n\n" .
							sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]). "\n";
						}
						$email .= $notify_comments;
						if (!$noMailHeader) {
							$email.= EMAIL_TEXT_REPLY_MSN."\n";
							$email.= email_track_code('OrderStatus', $check_status['customers_email_address'], $oID);
						}

						//amit added to change email subject start
						if ($HTTP_POST_VARS['email_subject'] != '') {
							$email_sent_subject = $HTTP_POST_VARS['email_subject'];
						} else {
							$email_sent_subject = EMAIL_TEXT_SUBJECT . ' #' . $oID . ' Sent:' . date('Y-m-d H:i:s');
						}
						//code extra information on comformation tour by bhavik
						if ($status == '100000') include("orders_confirm_email_info.php");
						//$email .= "\n\n" .str_replace('<a></a>', get_login_publicity_name() ,CONFORMATION_EMAIL_FOOTER);
						// howard fixed atuo get lang code to send mail

						$user_lang_code = customers_language_code($check_status['customers_email_address']);
						$to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html($check_status['customers_name']));
						$to_email_address = $check_status['customers_email_address'];
						$email_subject = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_sent_subject);
						$email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email);
						$from_email_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
						//$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
						$from_email_address = 'automail@usitrip.com';
						if (isset($_POST['send_travel_companion_user']) && $_POST['send_travel_companion_user'] == '1') {
							$travel_companion_email = get_order_travel_companion_email_list($oID);
							$travel_companion_email = explode(',', $travel_companion_email);
							foreach ($travel_companion_email as $tce_key => $tce_val) {
								if ($to_email_address == $tce_val) {
									unset($travel_companion_email[$tce_key]);
								}
							}
							$travel_companion_email = join(',', array_unique($travel_companion_email));
							$travel_companion_email = $travel_companion_email != '' ? ',' . $travel_companion_email : '';
							$to_email_address .= $travel_companion_email;
							$to_name .= $travel_companion_email;
						}
						//$mail_isNoReplay = $status == '6'?true:false;
						$mail_isNoReplay = true;
						//exit($to_email_address);

						//如果$status==100083 Confirmation 订单确认的话，要单独从数据库重新取得邮件内容发给客人
						/* 2011-9-23又被客服部废除
						if($status=='100083'){
						$mail_sql = tep_db_query('SELECT orders_status_default_comment FROM `orders_status` WHERE orders_status_id="'.$status.'" ');
						$mail_row = tep_db_fetch_array($mail_sql);
						$email_text = db_to_html($mail_row['orders_status_default_comment']);
						$email_text .= $EmailSignature;
						$email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_text);
						$comments = $mail_row['orders_status_default_comment'];
						}
						*/

						if($status!='100006'){	//100006不发邮件只是让客人在前台可看
							//tep_mail($to_name, $to_email_address, $email_subject, db_to_html($email_text), $from_email_name, $from_email_address,'true', CHARSET);
							tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address,'true', $user_lang_code);
							//发完邮件要记录订单邮件发送历史
							function tep_add_orders_email_history($orders_id, $email_address, $email_subject, $email_content, $charset, $admin_id){
								$email_subject = iconv($charset, 'gb2312//IGNORE', $email_subject);
								$email_content = iconv($charset, 'gb2312//IGNORE', $email_content);
								$data_array = array('orders_id'=>$orders_id, 'admin_id'=>$admin_id, 'sent_date'=>date("Y-m-d H:i:s"), 'email_address'=>$email_address, 'subject'=>$email_subject, 'content'=>$email_content);
								$data_array = tep_db_prepare_input($data_array);
								tep_db_perform('orders_email_history',$data_array);
								return tep_db_insert_id();
							}
							$orders_email_history_id = tep_add_orders_email_history($oID, $to_email_address, $email_subject, $email_text, $user_lang_code, $login_id);
							//Howard added by 2013-03-05 发完邮件后要给当前管理员添加未定积分+1
							if(!in_array($status,array(100115,100100,100128))&&$Assessment_Score->checkLoginOwner($oID)){//排除：0-01销售已经审核，等待主管下单给地接，0-03客人已更新航班信息,需更新给地接，0-04等待主管更新给地接。此三状态的更新在另外的地方由主管确认后再加积分
								$score_mail_mark=true;
								$Assessment_Score->add_pending_score($login_id, 1, tep_get_admin_customer_name($login_id).'发送通知邮件给客户！系统自动加+1分。', 'orders_email_history_id', $orders_email_history_id, '1', CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID, 1, $oID);
							}
						}
						$customer_notified = '1';
					} //Notify Customer end }
					//Notify Accounting{
					//@author vincent
					// 在To Charge Capture(100095),Need to check bank account(100072)状态下,
					//勾选"Notify China accounting to charge capture immediately?"，update后发送邮件到payment@usitrip.com
					//勾选"Notify US accounting to charge capture immediately?"，update后发送邮件到  andy.tsai@usitrip.com.
					if($previous_status != $status &&  ( $status == '100095'|| $status == '100071')){
						$email_subject_accounting =  $HTTP_POST_VARS['email_subject'] != '' ? $HTTP_POST_VARS['email_subject'] : EMAIL_TEXT_SUBJECT . ' #' . $oID . ' Sent:' . date('Y-m-d H:i:s');
						$email_subject_accounting ='[Accounting Notification]'.$email_subject_accounting;
						$email_text_accounting = $email_text == '' ?  iconv(CHARSET, $user_lang_code . '//IGNORE', $comments):$email_text;
						$email_text_accounting = "Order #".$oID."<br/><br/>".$email_text_accounting.'<br/>';
						$from_email_name_accounting = $from_email_name;
						$cnEmail = 'payment@usitrip.com';
						$usEmail = 'payment@usitrip.com';
						if($_POST['notifyAccountingCN'] == 1){
							tep_mail('China Accounting', $cnEmail, $email_subject_accounting, $email_text_accounting, $from_email_name, $from_email_name_accounting, 'true', $user_lang_code);
						}
						if($_POST['notifyAccountingUS'] == 1){
							tep_mail('US Accounting', $usEmail, $email_subject_accounting, $email_text_accounting, $from_email_name, $from_email_name_accounting, 'true', $user_lang_code);
						}
					}
					//}
					
					if($status=='100141'){	//9-00的状态的添加退款订单表 Howard added by 2013-07-01
						$_log = tep_db_get_field_value('log', 'orders_refund', 'orders_id="'.$oID.'"' );
						tep_db_query('REPLACE INTO orders_refund SET orders_id="'.$oID.'", last_time=now(), last_admin_id="'.$login_id.'", log=CONCAT("'.$_log.'",now(),"|'.$login_id.'","\n") ');
					}
					
					// "Status History" table has gone through a few
					// different changes, so here are different versions of
					// the status update.
					// NOTE: Theoretically, there shouldn't be a
					//	   orders_status field in the ORDERS table. It
					//	   should really just use the latest value from
					//	   this status history table.
					// Howard fixed 写订单更新状态历史 by 2013-03-05
					$_data_array = array('orders_id'=>$oID, 'orders_status_id'=>$status,'date_added'=>date('Y-m-d H:i:s'),'customer_notified'=>$customer_notified,'comments'=>$comments,'updated_by'=>$login_id,'next_admin_id'=>$_next_admin_id);
					if(isset($score_mail_mark)&&$score_mail_mark){
						$_data_array['score']=1;
					}
					$_data_array = tep_db_prepare_input($_data_array);
					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $_data_array);
				}

				// check to see if there are products to update {
				if (count($update_products) > 0) {
					// Update Products
					$RunningSubTotal = 0;
					$RunningTax = 0;
					$RunningSubTotal_cost = 0;
					$RunningTax_cost = 0;
					$other_server_final_price = 0;

					// CWS EDIT (start) -- Check for existence of subtotals...
					// Do pre-check for subtotal field existence
					$ot_subtotal_found = false;
					foreach ($update_totals as $total_details) {
						extract($total_details, EXTR_PREFIX_ALL, "ot");
						if ($ot_class == "ot_subtotal") {
							$ot_subtotal_found = true;
							break;
						}
					}
					// CWS EDIT (end) -- Check for existence of subtotals...

					foreach ($update_products as $orders_products_id => $products_details) {
						// Update orders_products Table
						//amit added to fixed update same order from two window start
						$select_old_price_sql = "select final_price, final_price_cost from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '" . (int) $orders_products_id . "'";
						$select_old_price_query = tep_db_query($select_old_price_sql);
						if ($select_old_price_row = tep_db_fetch_array($select_old_price_query)) {
							$products_details["previous_final_price"] = number_format($select_old_price_row['final_price'], 2, '.', '');
							$products_details["previous_final_price_cost"] = number_format($select_old_price_row['final_price_cost'], 2, '.', '');
						}
						//amit added to fixed update same order from two window end

						//amit added to avoide invoice update from regular submit start
						$get_latest_invoice_detail_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " where orders_products_id = '" . (int) $orders_products_id . "' order by ord_prod_history_id desc limit 1");
						$get_latest_invoice_detail_row = tep_db_fetch_array($get_latest_invoice_detail_query);
						$products_details["previous_invoice_number"] = $get_latest_invoice_detail_row["invoice_number"];
						$products_details["invoice_number"] = $get_latest_invoice_detail_row["invoice_number"];
						$products_details["previous_invoice_amount"] = $get_latest_invoice_detail_row["invoice_amount"];
						$products_details["invoice_amount"] = $get_latest_invoice_detail_row["invoice_amount"];
						//amit added to avoide invoice update from regular submit end

						//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
						$_order = tep_db_fetch_array($order_query);
						if ($products_details["qty"] != $_order['products_quantity']) {
							$differenza_quantita = ($products_details["qty"] - $_order['products_quantity']);
							tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int) $_order['products_id'] . "'");
							MCache::update_product($_order['products_id']); //MCache update
						}
						//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################

						if ($products_details["qty"] > 0) {
							//amit added to get model name and tour code from product id
							$select_product_model_code_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='" . $products_details["id"] . "'  and p.products_id=pd.products_id and  pd.language_id = '" . (int) $languages_id . "' order by p.products_model ");
							while ($select_product_model_code_row = tep_db_fetch_array($select_product_model_code_sql)) {
								$products_details["name"] = $select_product_model_code_row['products_name'];
								$products_details["model"] = $select_product_model_code_row['products_model'];
							}
							$select_previouse_product_model_code_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='" . $products_details["previouse_id"] . "'  and p.products_id=pd.products_id and  pd.language_id = '" . (int) $languages_id . "' order by p.products_model ");
							while ($select_previouse_product_model_code_row = tep_db_fetch_array($select_previouse_product_model_code_sql)) {
								$products_details["previouse_name"] = $select_previouse_product_model_code_row['products_name'];
								$products_details["previouse_model"] = $select_previouse_product_model_code_row['products_model'];
							}
							//amit added to get model name and tour code from product id

							if ($products_details["id"] != $products_details["previouse_id"]) {
								//$orders_product_id = tep_get_ordersid_productsid_from_orderproducts($orders_products_id);
								$this_orders_id = $orders_product_id['orders_id'];
								$update_eticket_query = "update " . TABLE_ORDERS_PRODUCTS_ETICKET . " set products_id= '" . $products_details["id"] . "' where orders_products_id = '".$orders_products_id."'"; //orders_id = '".$this_orders_id."' and products_id= '".$products_details["previouse_id"]."'
								$update_flight_query = "update " . TABLE_ORDERS_PRODUCTS_FLIGHT . " set products_id= '" . $products_details["id"] . "' where orders_products_id = '".$orders_products_id."'"; //orders_id = '".$this_orders_id."' and products_id= '".$products_details["previouse_id"]."'
								tep_db_query($update_eticket_query);
								tep_db_query($update_flight_query);
							}

							$attribute_price_total = 0;
							$attribute_price_cost_total = 0;
							if (IsSet($products_details[attributes])) {
								foreach ($products_details["attributes"] as $orders_products_attributes_id => $attributes_details) {
									$attribute_price_total = $attribute_price_total + $attributes_details["price"];
									$attribute_price_cost_total = $attribute_price_cost_total + $attributes_details["price_cost"];
								}
							}
							$products_details["final_price_cost"] = $products_details["final_price_cost_without_attr"] + $attribute_price_cost_total;
							$products_details["final_price"] = $products_details["final_price_without_attr"] + $attribute_price_total;
							if ($access_full_edit == 'true' || $access_order_cost == 'true') {
								//可更新成本cost的人员更新
								$Query = "update " . TABLE_ORDERS_PRODUCTS . " set
									final_price_cost = '" . $products_details["final_price_cost"] . "',
									orders_products_payment_status = '". tep_db_input($products_details["orders_products_payment_status"]). "',
									orders_products_payment_status_remarks = '". tep_db_input($products_details["orders_products_payment_status_remarks"]). "',
									products_id= '" . $products_details["id"] . "',
									products_model = '" . $products_details["model"] . "',
									products_name = '" . tep_db_input($products_details["name"]) . "',
									final_price = '" . $products_details["final_price"] . "',
									products_tax = '" . $products_details["tax"] . "',
									products_quantity = '" . $products_details["qty"] . "',
									hotel_pickup_info = '" . tep_db_input($products_details["hotel_pickup_info"]) . "' 
									where orders_products_id = '$orders_products_id';";
							} else {
								//不可更新成本cost的人员更新
								$Query = "update " . TABLE_ORDERS_PRODUCTS . " set
									products_id= '" . $products_details["id"] . "',
									products_model = '" . $products_details["model"] . "',
									products_name = '" . tep_db_input($products_details["name"]) . "',
									final_price = '" . $products_details["final_price"] . "',
									products_tax = '" . $products_details["tax"] . "',
									products_quantity = '" . $products_details["qty"] . "',
									hotel_pickup_info = '" . tep_db_input($products_details["hotel_pickup_info"]) . "' 
									where orders_products_id = '$orders_products_id';";
							}

							tep_db_query($Query);

							/* Guest names update - start */
							if ($products_details['previouse_id'] != $products_details['id'] && (int) $products_details['id'] > 0) {
								$cur_guest_prod_id = $products_details["previouse_id"];
								tep_db_query("update " . TABLE_ORDERS_PRODUCTS_ETICKET . " SET products_id='" . (int) $products_details['id'] . "' WHERE orders_products_id = '".$orders_products_id."'"); //orders_id = '" . (int)$oID . "' AND products_id='".(int)$products_details['previouse_id']."'

								tep_db_query("update " . TABLE_ORDERS_PRODUCTS_FLIGHT . " SET products_id='" . (int) $products_details['id'] . "' WHERE orders_products_id = '".$orders_products_id."'"); //orders_id = '" . (int)$oID . "' AND products_id='".(int)$products_details['previouse_id']."'
							} else {
								$cur_guest_prod_id = $products_details["id"];
							}

							$h = 1;
							$guest_name = '';
							$guest_email = '';
							$guest_gender = '';
							$guest_height = '';
							//hotel-extension begin
							$total_guest = (int)$_POST['total_guest_'.$orders_products_id];
							for($h=1; $h<=$total_guest;$h++){
								if(isset($_POST['guest'.$h.'_'.$orders_products_id])){
									$_guest_name = $_POST['guest'.$h.'_'.$orders_products_id];
									if(strpos($_guest_name,'[') === false){
										$_guest_name = $_guest_name.' ['.$_guest_name.']';
									}

									$guest_name .= isset($_POST['etckchildage'.$h.'_'.$orders_products_id])?trim($_guest_name).'||'.$_POST['etckchildage'.$h.'_'.$orders_products_id]."<::>":trim($_guest_name)."<::>";
								}
								if(isset($_POST['bodyweight'.$h.'_'.$orders_products_id])){$body_weight .= $_POST['bodyweight'.$h.'_'.$orders_products_id]."<::>";}
								if(isset($_POST['guestemail'.$h.'_'.$orders_products_id])){$guest_email .= $_POST['guestemail'.$h.'_'.$orders_products_id]."<::>";}
								if(isset($_POST['guestgenders' . $h . '_' . $orders_products_id]) && $_POST['guestgenders' . $h . '_' . $orders_products_id] != 'Delete') {
									$guest_gender .= $_POST['guestgenders' . $h . '_' . $orders_products_id] . "<::>";}
									if(isset($_POST['height' . $h . '_' . $orders_products_id]) && $_POST['height' . $h . '_' . $orders_products_id] != '') {
										$guest_height .= $_POST['height' . $h . '_' . $orders_products_id] . "<::>";	}
							}// end for loop
							////hotel-extension end

							//amit added for update gender start
							if (tep_not_null($HTTP_POST_VARS['new_gender_' . $orders_products_id]) && tep_not_null($HTTP_POST_VARS['new_cus_name_' . $orders_products_id])) {
								$guest_gender .= $HTTP_POST_VARS['new_gender_' . $orders_products_id] . "<::>";
							}
							if (tep_not_null($HTTP_POST_VARS['new_height_' . $orders_products_id]) && tep_not_null($HTTP_POST_VARS['new_cus_name_' . $orders_products_id])) {
								$guest_height .= $HTTP_POST_VARS['new_height_' . $orders_products_id] . "<::>";
							}
							//amit added for update gender end

							//guest name add and edit option
							if (tep_not_null($HTTP_POST_VARS['garbage_boxs_' . $orders_products_id])) {
								$will_remove_guests = explode("<::>", $HTTP_POST_VARS['garbage_boxs_' . $orders_products_id]);
								$old_guest_names = explode("<::>", $guest_name);
								$new_guests = array_diff($old_guest_names, $will_remove_guests);
								$guest_name = implode("<::>", $new_guests) . "<::>";
							}

							//new customer names
							if (tep_not_null($HTTP_POST_VARS['new_cus_name_' . $orders_products_id])) {
								$cus_name_and_bdate = $HTTP_POST_VARS['new_cus_name_' . $orders_products_id];

								//自动处理不规范的名字输入方式 En Name => En Name [En Name]
								if(strpos($cus_name_and_bdate,'[') === false){
									$cus_name_and_bdate = $cus_name_and_bdate.' ['.$cus_name_and_bdate.']';
								}

								if (tep_not_null($HTTP_POST_VARS['birth_date_' . $orders_products_id])) {
									$cus_name_and_bdate .= "||" . $HTTP_POST_VARS['birth_date_' . $orders_products_id];
								}
								if (preg_match('/(.+ +\[.+\](\|\|\d\d\/\d\d\/\d\d\d\d)*)/', $cus_name_and_bdate, $m)) { //CN name [En Name]||01/12/2008
									$m[0] = preg_replace('/ +/', ' ', $m[0]);
									$guest_name .= str_replace(' [', '  [', $m[0]) . "<::>";
								}
							}

							// check no of guest changed or not
							$now_total_guests_array = explode("<::>", $guest_name);
							$new_total_guest = (int) sizeof($now_total_guests_array) - 1;
							$old_total_guest = $total_guest;
							if ($new_total_guest != $old_total_guest) {
								tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set is_adjustments_needed = '1||1||1||1' where orders_products_id='" . $orders_products_id . "'");
							}

							//allow to update room info detail
							if (tep_not_null($HTTP_POST_VARS['products_room_info_textarea_' . $orders_products_id])) {
								$nr = array("\r\n", "\n", "\r");
								$products_room_info_textarea = str_replace($nr, "<br>", tep_db_prepare_input($HTTP_POST_VARS['products_room_info_textarea_' . $orders_products_id]));
								tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_room_info='" . $products_room_info_textarea . "' where orders_id = " . (int) $oID . " and products_id = " . (int) $cur_guest_prod_id . " ");
							}

							// update name change start here Nirav name
							$get_data = get_older_values_for_update($orders_products_id);
							$sql_check_new_entry = "SELECT op_order_products_ids from " . ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY . " where op_order_products_ids=" . $orders_products_id . " ";
							$run_check_new_entry = tep_db_query($sql_check_new_entry);
							$row_fetch_check_exist = tep_db_fetch_array($run_check_new_entry);
							if ($row_fetch_check_exist['op_order_products_ids'] == '' && $get_data['guest_name_old'] != $guest_name) {
								create_update_history_orders($orders_products_id, $get_data['depature_date_old'], $get_data['total_room_adult_child_info_old'], $get_data['guest_name_old'], $get_data['date_purchased']);
								$OOC->addRecord($login_id,8,$_GET['oID']);
							}
							if ($get_data['guest_name_old'] != $guest_name) {
								$cust_body_kg = $body_weight;
								create_update_history_orders($orders_products_id, $get_data['depature_date_old'], $get_data['total_room_adult_child_info_old'], $guest_name);
								$OOC->addRecord($login_id,8,$_GET['oID']);
							}
							// update name change end here

							$products_id = (int) $products_details["id"];
							$orders_id = (int) $oID;
							/*
							$the_extra_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where  orders_products_id = ".intval($orders_products_id);// orders_id='".$orders_id."' AND products_id='".$products_id."'");
							$the_extra = tep_db_fetch_array($the_extra_query);
							*/
							if (!tep_not_null(str_replace('<::>', '', $guest_name))) {
								$guest_name = "无客人姓名edit_orders.php [" . (int) $login_id . "]<::>";
							}
							if ($orders_id == '') {
								tep_db_query("INSERT INTO `" . TABLE_ORDERS_PRODUCTS_ETICKET . "` ( `orders_id` , `products_id` , `guest_name`, `guest_body_weight`,guest_body_height, `guest_email` , `orders_products_id`) VALUES ('$oID', '$products_id', '" . tep_db_input(tep_db_prepare_input($guest_name)) . "', '$body_weight','$guest_height', '$guest_email', '$orders_products_id');");
								//
							} else {
								tep_db_query("update " . TABLE_ORDERS_PRODUCTS_ETICKET . " set guest_name='" . tep_db_input(tep_db_prepare_input($guest_name)) . "', guest_body_weight='" . $body_weight . "', guest_email = '" . $guest_email . "', guest_gender = '" . $guest_gender . "', guest_body_height = '" . $guest_height . "'  where orders_products_id='".$orders_products_id."'");//orders_id = " . $orders_id . " and products_id = '" . $products_id . "' ");
								if($OOC->checkIsChange(TABLE_ORDERS_PRODUCTS_ETICKET, 'guest_gender', $guest_gender,'orders_id='.tep_db_input($oID))){
									$OOC->addRecord($login_id,2,$orders_id);
								}
							}
							/* Guest names update - end */

							//amit added to track order item changed start {
							if ($products_details["name"] != $products_details["previouse_name"] || $products_details["model"] != $products_details["previouse_model"] || trim($products_details["final_price_cost"]) != trim($products_details["previous_final_price_cost"]) || trim($products_details["final_price"]) != trim($products_details["previous_final_price"]) || $products_details["previous_invoice_number"] != $products_details["invoice_number"] || $products_details["previous_invoice_amount"] != $products_details["invoice_amount"] || $products_details["invoice_comments"] != '' || $products_details["invoice_comments_retail"] != '') {
								$total_invoices_cost_retail_comments = '';
								$select_check_product_oder_his = "select ord_prod_history_id from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " where orders_products_id='" . $orders_products_id . "'";
								$select_check_product_oder_his_query = tep_db_query($select_check_product_oder_his);
								if (tep_db_num_rows($select_check_product_oder_his_query) == 0) {
									$sql_data_array_original_insert = array(
									'orders_products_id' => $orders_products_id,
									'products_model' => $products_details["model"],
									'products_name' => str_replace("'", "&#39;", $products_details["name"]),
									'retail' => $products_details["previous_final_price"],
									'cost' => $products_details["previous_final_price_cost"],
									'last_updated_date' => 'now()'
									);
									tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
								}
								$adjusted_retail_price = $products_details["final_price"] - $products_details["previous_final_price"];
								$adjusted_cost_price = $products_details["final_price_cost"] - $products_details["previous_final_price_cost"];
								$total_invoices_cost_retail_comments = stripslashes2($products_details["invoice_comments"]) . '!!###!!' . stripslashes2($products_details["invoice_comments_retail"]);
								if ($access_full_edit == 'true' || $access_order_cost == 'true') {
									$sql_data_array = array(
									'orders_products_id' => $orders_products_id,
									'products_model' => $products_details["model"],
									'products_name' => str_replace("'", "&#39;", $products_details["name"]),
									'retail' => $adjusted_retail_price,
									'cost' => $adjusted_cost_price,
									'invoice_number' => $products_details["invoice_number"],
									'invoice_amount' => $products_details["invoice_amount"],
									'comment' => tep_db_input($total_invoices_cost_retail_comments),
									'updated_by' => $login_id,
									'last_updated_date' => 'now()'
									);
								} else {
									$sql_data_array = array(
									'orders_products_id' => $orders_products_id,
									'products_model' => $products_details["model"],
									'products_name' => str_replace("'", "&#39;", $products_details["name"]),
									'retail' => $adjusted_retail_price,
									'cost' => 0,
									'invoice_number' => $products_details["invoice_number"],
									'invoice_amount' => $products_details["invoice_amount"],
									'comment' => tep_db_input($total_invoices_cost_retail_comments),
									'updated_by' => $login_id,
									'last_updated_date' => 'now()'
									);
								}
								tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array);

								//Update invoice amount in order products for providers invoice amount
								$sql_data_array = array('customer_invoice_no' => $products_details["invoice_number"],
								'customer_invoice_total' => $products_details["invoice_amount"]);
								tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array, 'update', "orders_products_id='" . $orders_products_id . "'");

								//amit added show change on payment adjusted report if changed price
								if ($adjusted_retail_price != 0) {
									$sql_data_array_payment_adjusted_blocked = array(
									'payment_adjusted_blocked' => '0'
									);
									tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_payment_adjusted_blocked, 'update', " orders_products_id = '" . $orders_products_id . "' ");
								}
								//Howard added 提醒财务“价格修改后要检查优惠券、积分兑换额和积分赠送值”
								$messageStack->add_session("价格虽然已经修改，但请务必通知财务检查优惠券、积分兑换额和积分赠送值！", 'error');
							}
							//amit added to track order item changed end }

							// Update Any Attributes
							if (IsSet($products_details[attributes])) {
								foreach ($products_details["attributes"] as $orders_products_attributes_id => $attributes_details) {
									$attributes_details["prefix"] = '+';
									if (substr($attributes_details["price"], 0, 1) == '-') {
										$attributes_details["price"] = $attributes_details["price"] * (-1);
										$attributes_details["prefix"] = '-';
									}
									if ($access_full_edit == 'true' || $access_order_cost == 'true') {
										if (substr($attributes_details["price_cost"], 0, 1) == '-') {
											$attributes_details["price_cost"] = $attributes_details["price_cost"] * (-1);
											$attributes_details["prefix"] = '-';
										}
										$Query = "update " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
											products_options = '" . $attributes_details["option"] . "',
											products_options_values = '" . $attributes_details["value"] . "',
											price_prefix = '" . $attributes_details["prefix"] . "',
											options_values_price = '" . $attributes_details["price"] . "',
											options_values_price_cost = '" . $attributes_details["price_cost"] . "'
											where orders_products_attributes_id = '$orders_products_attributes_id';";
										tep_db_query($Query);
									} else {
										$Query = "update " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
											products_options = '" . $attributes_details["option"] . "',
											products_options_values = '" . $attributes_details["value"] . "',
											price_prefix = '" . $attributes_details["prefix"] . "',
											options_values_price = '" . $attributes_details["price"] . "'
											where orders_products_attributes_id = '$orders_products_attributes_id';";
										tep_db_query($Query);
									}
									/* if($attributes_details["price"]!=$attributes_details["previous_price"]){
									$products_details["final_price"] = $products_details["final_price"] - ($attributes_details["prefix"].$attributes_details["previous_price"]) + ($attributes_details["prefix"].$attributes_details["price"]);
									}
									if($attributes_details["price_cost"]!=$attributes_details["previous_price_cost"]){
									$products_details["final_price_cost"] = $products_details["final_price_cost"] - ($attributes_details["prefix"].$attributes_details["previous_price_cost"]) + ($attributes_details["prefix"].$attributes_details["price_cost"]);
									} */
								}
							}

							// Update Tax and Subtotals
							$RunningSubTotal += $products_details["qty"] * $products_details["final_price"];
							$RunningTax += ( ($products_details["tax"] / 100) * ($products_details["qty"] * $products_details["final_price"]));
							$RunningSubTotal_cost += $products_details["qty"] * $products_details["final_price_cost"];
							$RunningTax_cost += ( ($products_details["tax"] / 100) * ($products_details["qty"] * $products_details["final_price_cost"]));
						} else {
							//amit added to delete recored form flight and eticket table start
							//$orders_product_id = tep_get_ordersid_productsid_from_orderproducts($orders_products_id); hotel-extension
							$this_product_id = $orders_product_id['products_id'];
							$this_orders_id = $orders_product_id['orders_id'];

							$del_eticket_query = "delete from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where  orders_products_id = '".$orders_products_id."' and orders_products_id>0"; //orders_id = '" . $this_orders_id . "' and products_id= '" . $this_product_id . "'";
							$del_flight_query = "delete from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where  orders_products_id = '".$orders_products_id."' and orders_products_id>0"; //orders_id = '" . $this_orders_id . "' and products_id= '" . $this_product_id . "'";
							tep_db_query($del_eticket_query);
							tep_db_query($del_flight_query);
							//exit;
							//amit added to delete recored form flight and eticket table end

							// 0 Quantity = Delete
							$Query = "delete from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '$orders_products_id' and orders_products_id>0";
							tep_db_query($Query);

							$Queryhistory = "delete from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " where orders_products_id = '$orders_products_id' and orders_products_id>0";
							tep_db_query($Queryhistory);

							//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
							$_order = tep_db_fetch_array($order_query);
							if ($products_details["qty"] != $_order['products_quantity']) {
								$differenza_quantita = ($products_details["qty"] - $_order['products_quantity']);
								tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int) $_order['products_id'] . "'");
								MCache::update_product($_order['products_id']); //MCache update
							}
							//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
							$Query = "delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = '$orders_products_id' AND orders_products_id>0";
							tep_db_query($Query);
						}

						//---------酒店延住等 start--------
						/* if(isset($products_details["extended_hotel_final_price"])){
						$other_server_final_price += number_format($products_details["extended_hotel_final_price"], 2, '.', '');
						}
						if(isset($products_details["airport_transfer_final_price"])){
						$other_server_final_price += number_format($products_details["airport_transfer_final_price"], 2, '.', '');
						}
						if(isset($products_details["address_transfer_final_price"])){
						$other_server_final_price += number_format($products_details["address_transfer_final_price"], 2, '.', '');
						}
						if(isset($products_details["orders_change_final_price"])){
						$other_server_final_price += number_format($products_details["orders_change_final_price"], 2, '.', '');
						} */
						$paid_status_ids = array(2, 3, 6); //可确认为已经付款的状态
						$extended_hotel_sql = tep_db_query('select orders_products_id, status_id, hotel_final_price from orders_products_extended_hotel where orders_products_id="' . $orders_products_id . '" ');
						while ($extended_hotel = tep_db_fetch_array($extended_hotel_sql)) {
							if ((int) $extended_hotel['orders_products_id']) {
								if (in_array($extended_hotel['status_id'], $paid_status_ids)) {
									$other_server_final_price += $extended_hotel['hotel_final_price'];
								}
							}
						}

						$airport_transfer_sql = tep_db_query('select orders_products_id, status_id, final_price from orders_products_airport_transfer where orders_products_id="' . $orders_products_id . '" Limit 1');
						$airport_transfer = tep_db_fetch_array($airport_transfer_sql);
						if ((int) $airport_transfer['orders_products_id']) {
							if (in_array($airport_transfer['status_id'], $paid_status_ids)) {
								$other_server_final_price += $airport_transfer['final_price'];
							}
						}

						$address_transfer_sql = tep_db_query('select orders_products_id, status_id, final_price from orders_products_address_transfer where orders_products_id="' . $orders_products_id . '" Limit 1');
						$address_transfer = tep_db_fetch_array($address_transfer_sql);
						if ((int) $address_transfer['orders_products_id']) {
							if (in_array($address_transfer['status_id'], $paid_status_ids)) {
								$other_server_final_price += $address_transfer['final_price'];
							}
						}

						$orders_change_sql = tep_db_query('select orders_products_id, status_id, final_price from orders_products_orders_change where orders_products_id="' . $orders_products_id . '" Limit 1');
						$orders_change = tep_db_fetch_array($orders_change_sql);
						if ((int) $orders_change['orders_products_id']) {
							if (in_array($orders_change['status_id'], $paid_status_ids)) {
								$other_server_final_price += $orders_change['final_price'];
							}
						}
						//---------酒店延住等 end--------

						// Howard added 更新接驳车服务信息{
						tep_db_query('DELETE FROM '.TABLE_ORDERS_PRODUCTS_TRANSFER.' WHERE `orders_products_id` = "'.$orders_products_id.'" ');
						if(is_array($_POST['transferInfo'][$orders_products_id])){
							foreach($_POST['transferInfo'][$orders_products_id] as $key => $val){
								if((int)$val['guest_total']){	//以接送人数为准，没有接送人数代表没有接驳车服务
									$val['flight_arrival_time'] = date('Y-m-d H:i:s',strtotime($val['flight_arrival_time']));
									$val['pickup_zipcode'] = getZipCodeFromTransfer($val['pickup_id']);
									$val['dropoff_zipcode'] = getZipCodeFromTransfer($val['dropoff_id']);
									$dataArray = array('orders_products_id'=>$orders_products_id,
									'orders_id'=>$oID,
									'flight_number'=>tep_db_prepare_input($val['flight_number']),
									'flight_departure'=>tep_db_prepare_input($val['flight_departure']),
									'flight_arrival_time'=>tep_db_prepare_input($val['flight_arrival_time']),
									'flight_pick_time'=>tep_db_prepare_input($val['flight_pick_time']),
									'pickup_id'=>tep_db_prepare_input($val['pickup_id']),
									'pickup_address'=>tep_db_prepare_input($val['pickup_address']),
									'pickup_zipcode'=>tep_db_prepare_input($val['pickup_zipcode']),
									'dropoff_id'=>tep_db_prepare_input($val['dropoff_id']),
									'dropoff_address'=>tep_db_prepare_input($val['dropoff_address']),
									'dropoff_zipcode'=>tep_db_prepare_input($val['dropoff_zipcode']),
									'guest_total'=>tep_db_prepare_input($val['guest_total']),
									'baggage_total'=>tep_db_prepare_input($val['baggage_total']),
									'comment'=>tep_db_prepare_input($val['comment']),
									'created_date'=>tep_db_prepare_input($val['created_date'])
									);
									tep_db_perform(TABLE_ORDERS_PRODUCTS_TRANSFER, $dataArray);
								}
							}
						}
						// Howard added 更新接驳车服务信息}

					}

					$RunningSubTotal += $other_server_final_price;
					// Shipping Tax
					foreach ($update_totals as $total_index => $total_details) {
						extract($total_details, EXTR_PREFIX_ALL, "ot");
						if ($ot_class == "ot_shipping") {
							$RunningTax += ( ($AddShippingTax / 100) * $ot_value);
						}
					}

					// Update Totals
					$RunningTotal = 0;
					$sort_order = 0;

					// Do pre-check for Tax field existence
					$ot_tax_found = 0;
					foreach ($update_totals as $total_details) {
						extract($total_details, EXTR_PREFIX_ALL, "ot");
						if ($ot_class == "ot_tax") {
							$ot_tax_found = 1;
							break;
						}
					}

					// 取得原订单总价用于后面判断用户是否已经给完款 start{
					$old_ot_total = 0;
					$_tmp_array = $order_old_data->totals;
					foreach((array)$_tmp_array as $_key => $_values){
						if($_values['class']=='ot_total'){
							$old_ot_total = $_values['value'];
							break;
						}
					}
					// 取得原订单总价用于后面判断用户是否已经给完款 end}
					$need_update_points = false;
					foreach ($update_totals as $total_index => $total_details) {
						extract($total_details, EXTR_PREFIX_ALL, "ot");
						if (trim(strtolower($ot_title)) == "tax" || trim(strtolower($ot_title)) == "tax:") {
							if ($ot_class != "ot_tax" && $ot_tax_found == 0) {
								// Inserting Tax
								$ot_class = "ot_tax";
								$ot_value = "x"; // This gets updated in the next step
								$ot_tax_found = 1;
							}
						}
						if (trim($ot_title) && trim($ot_value)) {
							$sort_order++;
							// Update ot_subtotal, ot_tax, and ot_total classes
							if ($ot_class == "ot_subtotal")$ot_value = $RunningSubTotal;
							if ($ot_class == "ot_tax") {
								$ot_value = $RunningTax;
								// print "ot_value = $ot_value<br>\n";
							}
							//disocunt

							// CWS EDIT (start) -- Check for existence of subtotals...
							if ($ot_class == "ot_total") {
								$ot_value = $RunningTotal;
								if (!$ot_subtotal_found) {
									// There was no subtotal on this order, lets add the running subtotal in.
									$ot_value = $ot_value + $RunningSubTotal;
								}
							}
							// CWS EDIT (end) -- Check for existence of subtotals...

							// Set $ot_text (display-formatted value)
							// $ot_text = "\$" . number_format($ot_value, 2, '.', ',');
							$order = new order($oID);
							$ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);
							if ($ot_class == "ot_total") {
								$ot_text = "<b>" . $ot_text . "</b>";
							}
							if ($ot_total_id > 0) {
								// In Database Already - Update
								$Query = "update " . TABLE_ORDERS_TOTAL . " set
									title = '$ot_title',
									text = '$ot_text',
									value = '$ot_value'
									
									where orders_total_id = '$ot_total_id'";
								tep_db_query($Query);
							} else {
								// New Insert
								$Query = "insert into " . TABLE_ORDERS_TOTAL . " set
									orders_id = '$oID',
									title = '$ot_title',
									text = '$ot_text',
									value = '$ot_value',
									class = '$ot_class',
									sort_order = '$sort_order'";
								tep_db_query($Query);
							}
							$RunningTotal += $ot_value;

							if($ot_class=='ot_total' && tep_round($ot_value,2) > tep_round($old_ot_total,2) ){
								//如果新总价大于旧总价同时原订单产品状态是已付款(1)的话要转成已部分付款(2)状态
								tep_db_query('update '.TABLE_ORDERS_PRODUCTS.' set orders_products_payment_status ="2" where orders_id="'.$oID.'" and orders_products_payment_status="1" ');
							}
							//如果新总价与旧总价不一致应该重新更新该订单的赠与积分数据给客人，同时如果客人兑换的积分数如果超出了范围则要重新处理兑换的积分数(未完)
							if($ot_class=='ot_total' && $ot_value != $old_ot_total ){
								$need_update_points = true;
							}
						} elseif ($ot_total_id > 0) {
							// Delete Total Piece
							$Query = "delete from " . TABLE_ORDERS_TOTAL . " where orders_total_id = '$ot_total_id'";
							tep_db_query($Query);
						}
					}

					######## Points/Rewards Module V2.1rc2a BOF ##################
					//积分处理start{
					//取得订单表中是否财务手动修改过积分，如果手动修改过，则不再在修改卖价的时候重新算本订单赠送的积分 by lwkai added 2013-05-28
					// 取得订单表中的财务修改状态
					$finance_status = tep_db_query("select point_lock from orders where orders_id='" . (int)$oID . "'");
					$finance_status = tep_db_fetch_array($finance_status); 
					if($need_update_points == true && !$finance_status['point_lock']){ // point_lock 0为财务没有手动修改过积分，1为已经修改过
						$new_order = new order($oID);
						$points_comment = 'TEXT_DEFAULT_COMMENT';
						$points_type = 'SP';
						//删除旧积分
						tep_db_query('DELETE FROM customers_points_pending WHERE orders_id="'.(int)$oID.'" and points_type="'.$points_type.'" AND points_status !="4" ');
						//添加新的待定积分
						$points_toadd = get_points_toadd($new_order);
						tep_add_pending_points((int)$new_order->customer['id'], (int)$oID, $points_toadd, $points_comment, $points_type);
					}

					if ($status == 100006) {	//订单的最后一步状态：已出团，非常重要
						if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
							//所有与100006状态有关的积分更新操作都请用以下函数
							auto_changed_points_info_for_orders_status_100006((int)$oID);
						}
						//将与此订单相关的客服考核积分状态更新成确认状态(订单归属人除外)
						$outwith_job_ids = explode(',',preg_replace('/[[:space:]]+/','',$order->info['orders_owners']));
						$outwith_admin_ids = array();
						foreach((array)$outwith_job_ids as $job_num){
							$outwith_admin_ids[] = tep_get_admin_id_from_job_number($job_num);
						}
						$Assessment_Score->set_orders_score_status((int)$oID, '1', $outwith_admin_ids);
						
					} else if (in_array($status ,array(6,100005,100130,100134) ) ) {
						if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
							//所有与100134、100130、100005或6状态有关的积分更新操作都请用以下函数
							auto_changed_points_info_for_orders_status_6_100005_100130_100134($oID, $status);
						}
					} else {
						if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
							$customer_query = tep_db_query("select unique_id, customer_id, points_pending, points_status from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_type = 'SP' and points_status != 1 and points_status != 4 and orders_id = '" . (int) $oID . "' limit 1");
							$customer_points = tep_db_fetch_array($customer_query);
							if (tep_db_num_rows($customer_query)) {
								$set_comment = ", points_comment = 'TEXT_DEFAULT_COMMENT'";
								tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 1 " . $set_comment . " where orders_id = '" . (int) $oID . "' and unique_id = '" . $customer_points['unique_id'] . "'");
								tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
							}

						}
					}
					//积分处理end}
					######## Points/Rewards Module V2.1rc2a EOF ##################
				}
				// check to see if there are products to update }


				if ($order_updated) {
					//Howard 添加操作员认为有问题的订单功能{
					if(in_array($status, array('100083','100012','100078','100135'))){
						tep_add_or_sub_op_think_problems_orders((int)$oID, $login_id, 'add');
					}
					//Howard 添加操作员认为有问题的订单功能}
					if ($status == 100083) {
						//1.[选择CCV后更新订单时，更新完毕要把订单状态再自动更新为100090
						//2.勾选Flight，系统单独发送一封题为（Flight Information Needed）的邮件到客人的邮箱中。
						//3.如果前面选择后的自动更新状态不是Wait for supporting doc（100090）, 则发送Flight Information Needed后状态为Flight Information Needed（100012）
						/*if (is_array($HTTP_POST_VARS['checkbox_100083']) && in_array("CCV", $HTTP_POST_VARS['checkbox_100083'])) {
						$new_status = "100090";
						tep_db_query('update `orders` set orders_status ="' . $new_status . '", last_modified = now() where orders_id="' . (int) $oID . '" ');
						tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)values ('" . tep_db_input($oID) . "', '" . tep_db_input($new_status) . "', now(), 0, 'System auto updated to 100090.', 0)");
						}*/

						/*最新修改要求
						勾选：单选Flight
						->Confirmation100083->Flight Information Needed 100012
						勾选：单选CCV
						->Confirmation 100083-> Wait for Supporting Doc 100090
						勾选：CCV+ Flight
						->Confirmation 100083->Flight Information Needed 100012 ->Wait for Supporting Doc 100090
						*/
						$new_status = NULL;
						if (is_array($HTTP_POST_VARS['checkbox_100083'])) {
							if(in_array("Flight", $HTTP_POST_VARS['checkbox_100083'])){
								$new_status = "100012";
								tep_db_query('update `orders` set orders_status ="' . $new_status . '", last_modified = now() where orders_id="' . (int) $oID . '" ');
								tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)values ('" . tep_db_input($oID) . "', '" . tep_db_input($new_status) . "', now(), 0, 'System auto updated to ".$new_status.".', 0)");
							}
							if(in_array("CCV", $HTTP_POST_VARS['checkbox_100083'])){
								$new_status = "100090";
								tep_db_query('update `orders` set orders_status ="' . $new_status . '", last_modified = now() where orders_id="' . (int) $oID . '" ');
								tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)values ('" . tep_db_input($oID) . "', '" . tep_db_input($new_status) . "', now(), 0, 'System auto updated to ".$new_status.".', 0)");
							}

							//if ($new_status != "100090" && (int)is_package_tour_on_order($oID)) {	//套餐团才能自动更新状态为100012
							/*if ($new_status != "100090") {	//自动更新状态为100012
							tep_db_query('update `orders` set orders_status ="100012", last_modified = now() where orders_id="' . (int) $oID . '" ');
							tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)values ('" . tep_db_input($oID) . "', '100012', now(), 0, 'System auto updated to 100012.',0)");
							}*/
						}

						if (is_array($HTTP_POST_VARS['checkbox_100083'])) {
							if ($HTTP_POST_VARS['notify'] == 'on') {
								$user_lang_code = customers_language_code($check_status['customers_email_address']);
								//$sql = tep_db_query('SELECT orders_status_default_subject, orders_status_default_comment FROM `orders_status` WHERE orders_status_id=100012 AND language_id ="1" ');
								//$row = tep_db_fetch_array($sql);
								//$e_subject = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, $row['orders_status_default_subject']);
								//$e_subject = iconv('gb2312', $user_lang_code . '//IGNORE', $e_subject);
								//$e_text = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $HTTP_GET_VARS['oID'], $row['orders_status_default_comment']);
								//$e_text = str_replace('{ORDERURL}', '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $oID) . '" target="_blank">' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $oID) . '</a>', $e_text);
								// $e_text = iconv('gb2312', $user_lang_code . '//IGNORE', $e_text);
								$to_name = iconv('gb2312', $user_lang_code . '//IGNORE', db_to_html($check_status['customers_name']));
								$to_email_address = $check_status['customers_email_address'];
								$from_email_name = iconv('gb2312', $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
								$from_email_address = 'automail@usitrip.com';
								//tep_mail($to_name, $to_email_address, $e_subject, $e_text, $from_email_name, $from_email_address, 'true', $user_lang_code);

								include(DIR_WS_MODULES . 'edit_orders/orders_status_100083_mail_text.php');
								//如果选择了选项则只发送这些选项的邮件(下面最多发3封邮件)
								if(in_array("English ONLY", $HTTP_POST_VARS['checkbox_100083'])){
									$e_subject = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, MAIL_SUBJECT_ENGLISH_ONLY);
									$e_text = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, db_to_html(MAIL_TEXT_ENGLISH_ONLY));
									$e_subject = iconv('gb2312', $user_lang_code . '//IGNORE', $e_subject);
									$e_text .= $EmailSignature;
									$e_text = iconv('gb2312', $user_lang_code . '//IGNORE', $e_text);
									tep_mail($to_name, $to_email_address, $e_subject, $e_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
								}
								if(in_array("CCV", $HTTP_POST_VARS['checkbox_100083'])){
									$e_subject = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, MAIL_SUBJECT_CCV);
									$e_text = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, MAIL_TEXT_CCV);
									$e_subject = iconv('gb2312', $user_lang_code . '//IGNORE', $e_subject);
									$e_text .= $EmailSignature;
									$e_text = iconv('gb2312', $user_lang_code . '//IGNORE', $e_text);
									tep_mail($to_name, $to_email_address, $e_subject, $e_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
								}
								if(in_array("Flight", $HTTP_POST_VARS['checkbox_100083'])){
									$e_subject = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, MAIL_SUBJECT_FLIGHT);
									$e_text = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $oID, MAIL_TEXT_FLIGHT);
									$e_subject = iconv('gb2312', $user_lang_code . '//IGNORE', $e_subject);
									$e_text .= $EmailSignature;
									$e_text = iconv('gb2312', $user_lang_code . '//IGNORE', $e_text);
									tep_mail($to_name, $to_email_address, $e_subject, $e_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
								}
							}
						}
					}

					// 如果状态为100014，在Comment中加一条提示
					if ($status == 100014 ) {
						$_comments = 'Please go ahead to ticket issue for local tour or change status to Flight Information Needed for package tour if guest does not have other request.';
						tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)values ('" . tep_db_input($oID) . "', '".$status."', now(), 0, '".$_comments."',0)");
					}


					if (isset($to_email_address, $noMailHeader)) {
						$messageStack->add_session(SUCCESS_ORDER_UPDATED . ' And mail to:<b style="color:red;">' . $to_email_address . '</b>', 'success');
					} else {
						$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
					}

					//Howard added 更新订单状态时把转付支付管理的对应订单状态也做更新start
					tep_db_query('update domestic_orders set orders_status="' . tep_db_input($status) . '" where orders_id="' . (int) $oID . '" ');
					//Howard added 更新订单状态时把转付支付管理的对应订单状态也做更新end

					//Howard added 如果订单支付方式有变，则检查订单总额是否需要更新。start
					//1.支付方式有变，取消ot_easy_discount优惠
					$enabled_update_total = true; //支付方式时自动更新总额的开关
					//结伴同游订单不在重算的范围
					if ($is_travel_comp_order == true) {
						$enabled_update_total = false;
					}
					if ($enabled_update_total == true && $update_info_payment_method != $old_update_info_payment_method && tep_not_null($old_update_info_payment_method)) {
						//die("支付方式有变！");
						$check_sql = tep_db_query('SELECT orders_total_id, value, text, class FROM `orders_total` WHERE orders_id="' . tep_db_input($oID) . '" and (class="ot_easy_discount" || class="ot_total" ) ');
						$total_num_nows = tep_db_num_rows($check_sql);
						$check_rows = tep_db_fetch_array($check_sql);
						$new_total_value = 0;
						$is_rmb = false;
						if (preg_match('/&#65509;|￥/', $check_rows['text'])) {
							$is_rmb = true;
						}
						if ($total_num_nows == 2) {
							$old_total_value = 0;
							$old_discount_value = 0;
							do {
								if ($check_rows['class'] == "ot_total") {
									$old_total_value = $check_rows['value'];
								}
								if ($check_rows['class'] == "ot_easy_discount") {
									$old_discount_value = $check_rows['value'];
								}
							} while ($check_rows = tep_db_fetch_array($check_sql));
							if ((int) $old_total_value) {
								$new_total_value = $old_total_value + abs($old_discount_value);
							}
						} elseif ($total_num_nows == 1 && $check_rows['class'] == "ot_total") {
							$new_total_value = $check_rows['value'];
						}
						if ($new_total_value == 0) {
							die('new_total_value is 0');
						}
						$new_total_text = $currencies->format($new_total_value, true, 'USD', '1');
						if ($is_rmb == true) {
							$new_total_text = $currencies->format($new_total_value, true, 'CNY');
						}
						tep_db_query('update orders_total set sort_order="99999", value="' . $new_total_value . '", text="<b>' . $new_total_text . '</b>" WHERE orders_id="' . tep_db_input($oID) . '" and class="ot_total" limit 1 ');
						tep_db_query('delete FROM `orders_total` WHERE orders_id="' . tep_db_input($oID) . '" and class="ot_easy_discount" limit 1 ');
						/*if (preg_match('/省2/', $update_info_payment_method)) {
						$discount_value = -($new_total_value * 0.02);
						$up_new_total_value = $new_total_value - abs($discount_value);
						$discount_text = $currencies->format($discount_value, true, 'USD', '1');
						$up_new_total_text = $currencies->format($up_new_total_value, true, 'USD', '1');
						if ($is_rmb == true) {
						$discount_text = $currencies->format($discount_value, true, 'CNY');
						$up_new_total_text = $currencies->format($up_new_total_value, true, 'CNY');
						}
						tep_db_query('update orders_total set value="' . $up_new_total_value . '", text="<b>' . $up_new_total_text . '</b>" WHERE orders_id="' . tep_db_input($oID) . '" and class="ot_total" limit 1 ');
						$data_e_array = array(
						'orders_id' => $oID,
						'title' => stripslashes2($update_info_payment_method) . 'Discount:',
						'text' => stripslashes2($discount_text),
						'value' => $discount_value,
						'class' => 'ot_easy_discount',
						'sort_order' => "2"
						);
						tep_db_perform(TABLE_ORDERS_TOTAL, $data_e_array);
						}*/
					}
					//Howard added 如果订单支付方式有变，则检查订单总额是否需要更新。end
				}

				//amit added for update cost start
				if ($access_full_edit == 'true' || $access_order_cost == 'true') {
					//$order_cost_calculation = $RunningSubTotal_cost + $RunningTax_cost;
					$order_cost_calculation = $RunningSubTotal_cost;
					$sql_order_cost_data_array = array('order_cost' => $order_cost_calculation, 'admin_id_orders' => $login_id);
					tep_db_perform(TABLE_ORDERS, $sql_order_cost_data_array, 'update', "orders_id = '" . (int) $oID . "'");
				}
				//amit added for update cost end

				//amit added for auto update order depends on proivder cancellation fee entered
				if ($status == 100021 && tep_not_null(trim($_POST['provider_cancellation_fee']))) {
					if (trim($_POST['provider_cancellation_fee']) >= 0) {
						tep_auto_cancelled_zero_cost_price((int) $oID, $_POST['provider_cancellation_fee'], $_POST['cancel_item_orders_products_id']);
					}
				}
				//amit added for auto updated order depends on proivder cancellation fee entered

				// Howard added updated  domestic_orders 同步更新转账支付订单的状态
				tep_db_query('update domestic_orders set orders_status = "' . tep_db_input($status) . '" where orders_id="' . (int) $oID . '" ');

				// Howard added updated orders status if ot total has changed 如果新旧总额不同则按需更新订单状态
				$last_total_value = get_orders_ot_total_value(tep_db_input($oID));
				if ((int) $last_total_value != (int) $_POST['old_ot_total'] && (int) $last_total_value && (int) $_POST['old_ot_total']) {
					auto_update_orders_status_set_partial_payment_received(tep_db_input($oID));
				}

				// Howard added 支付方式从其它方式变更到银行转账时，自动捕获该订单增加到转账支付系统
				if (preg_match('/银行转账/', $update_info_payment_method) && $old_update_info_payment_method != $update_info_payment_method) {
					auto_add_to_domestic_orders((int) $oID);
				}
				if (isset($_POST['edit_ret']) && $_POST['edit_ret'] == '1' && isset($_POST['is_agret']) && $_POST['is_agret'] == '1') {
					$_POST['visit_start_date'] = tep_get_date_db($_POST['visit_start_date']);
					$_POST['visit_end_date'] = tep_get_date_db($_POST['visit_end_date']);
					tep_db_query("delete FROM `orders_return_visit` WHERE `orders_id`='{$_GET['oID']}'");
					tep_db_query("insert into `orders_return_visit` set `orders_id`='{$_GET['oID']}',`visit_start_date`='{$_POST['visit_start_date']}', `visit_end_date`='{$_POST['visit_end_date']}', `visit_time`='{$_POST['ret_time']}', `mark`='{$_POST['mark']}',`prodcuts_ids`='{$_POST['prodcuts_ids']}'");
					tep_db_query("update `orders` set `is_agret`='{$_POST['is_agret']}' WHERE `orders_id`='{$_GET['oID']}'");
				}
				tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action', 'fudate')) . 'action=edit'));
				exit;
				break;
				// Add a Product
		case 'add_product':	//在现有订单中添加新产品
			if ($step == 1){	//到前台添加订单产品
				setcookie('ParentOrdersId', (int)$_GET['oID'], time()+(3600*2), '/', HTTP_COOKIE_DOMAIN);	//必须添加路径为/否则前台的程序找不到此变量
				tep_redirect(tep_catalog_href_link("logoff.php"));	//去前台的退出页面，以防销售前台登录了非本订单的账号
			}
			die();
			//以下代码已经不再使用，在原有订单中添加产品时已经转到前台操作了，后台再无添加订单产品功能！Howard added by 2013-06-10
			if ($step == 6) {
				// Get Order Info
				$final_product_price = $_POST['final_product_price'];
				$final_product_price_cost = $_POST['final_product_price_cost'];
				$finaldate = $_POST['finaldate'];
				$depart_time = $_POST['depart_time'];
				$depart_location = $_POST['depart_location'];
				$total_room_price = $_POST['total_room_price'];
				$total_info_room = $_POST['total_info_room'];
				$total_room_adult_child_info = $_POST['total_room_adult_child_info'];
				$g_number = $_POST['g_number'];
				//hotel-extension begin
				$is_hotel = tep_check_product_is_hotel((int)$add_product_products_id);

				$early_hotel_checkout_date = date('Y-m-d H:i:s',strtotime($_POST['early_hotel_checkout_date']));

				$hotel_checkout_date = $early_hotel_checkout_date;
				//hotel-extension end
				$add_product_quantity = $_POST['add_product_quantity'];
				$add_product_options = $_POST['add_product_options'];
				$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
				$order = new order($oID);
				$AddedOptionsPrice = 0;
				$AddedOptionsPrice_cost = 0;

				// Howard added Check orders_products 2011-07-17 {
				//接送服务产品可以添加多个
				for($i=0, $n=count($order->products); $i<$n; $i++){
					if((int)$add_product_products_id==(int)$order->products[$i]['id'] && $order->products[$i]['is_transfer'] != '1'){
						die("不能添加此产品，请先删除旧的相同产品后再添加！{$order->products[$i]['model']}");
					}
				}
				// Howard added Check orders_products 2011-07-17 }

				// Get Product Attribute Info
				if ($add_product_options != '') {
					foreach ($add_product_options as $option_id => $option_value_id) {
						if (DOWNLOAD_ENABLED == 'true') {
							$execute_query = "SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad on pad.products_attributes_id=pa.products_attributes_id WHERE products_id='$add_product_products_id' and options_id='$option_id' and options_values_id='$option_value_id'";
						} else {
							$execute_query = "select pa.products_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.options_values_price_cost, pa.price_prefix, popt.products_options_name, poval.products_options_values_name from
										" . TABLE_PRODUCTS_OPTIONS . " popt,
										" . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
										" . TABLE_PRODUCTS_ATTRIBUTES . " pa
										where
										pa.products_id = '" . (int) $add_product_products_id . "'
										and pa.options_id = '" . (int) $option_id . "'
										and pa.options_id = popt.products_options_id
										and pa.options_values_id = '" . (int) $option_value_id . "'
										and pa.options_values_id = poval.products_options_values_id
										and popt.language_id = '" . (int) $languages_id . "'
										and poval.language_id = '" . (int) $languages_id . "'
										order by pa.products_options_sort_order
										";
						}

						$attributes_query = tep_db_query($execute_query);
						while ($attributes = tep_db_fetch_array($attributes_query)) {
							if($attributes['price_prefix'] == ''){
								$attributes['price_prefix'] = '+';
							}

							$option = $attributes['options_id'];
							$opt_products_options_name = $attributes['products_options_values_name'];
							$option_id = "'" . (int) $option_id . "'";
							$value_id = "'" . (int) $option_value_id . "'";
							$att_prefix[$option_value_id] = $attributes['price_prefix'];
							//$att_price[$option_value_id] = $attributes['options_values_price'];
							$att_price[$option_value_id] = attributes_price_display($add_product_products_id, $attributes['options_id'], $attributes['options_values_id']);
							$att_price_cost[$option_value_id] = attributes_price_display_cost($add_product_products_id, $attributes['options_id'], $attributes['options_values_id']);
							$option_names[$option_value_id] = $attributes['products_options_values_name'];
							$option_values_names[$option_value_id] = $attributes['products_options_name'];
							if ($att_prefix[$option_value_id] != '-') {
								$AddedOptionsPrice += $att_price[$option_value_id];
								$AddedOptionsPrice_cost += $att_price_cost[$option_value_id];
							} else {
								$AddedOptionsPrice -= $att_price[$option_value_id];
								$AddedOptionsPrice_cost -= $att_price_cost[$option_value_id];
							}
							$option_value_details[$option_id][$value_id] = array("options_values_price" => $att_price[$option_value_id]);
							$att_options_values_price = $att_price[$option_value_id];
						}
					}
				}

				// Get Product Info
				$InfoQuery = "select p.products_model,p.products_price,pd.products_name,p.products_tax_class_id,is_transfer from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='$add_product_products_id' and p.products_id=pd.products_id";
				$result = tep_db_query($InfoQuery);
				$row = tep_db_fetch_array($result);
				extract($row, EXTR_PREFIX_ALL, "p");

				// Following functions are defined at the bottom of this file
				$CountryID = tep_get_country_id($order->delivery["country"]);
				$ZoneID = tep_get_zone_id($CountryID, $order->delivery["state"]);
				$ProductsTax = tep_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);
				/*
				$Query = "insert into " . TABLE_ORDERS_PRODUCTS . " set
				orders_id = $oID,
				products_id = $add_product_products_id,
				products_model = '$p_products_model',
				products_name = '" . str_replace("'", "&#39;", $p_products_name) . "',
				products_price = '$final_product_price',
				final_price = '" . ($final_product_price + $AddedOptionsPrice) . "',
				final_price_cost = '" . ($final_product_price_cost + $AddedOptionsPrice_cost) . "',
				products_tax = '$ProductsTax',
				products_quantity = $add_product_quantity,
				products_departure_date = '$finaldate',
				products_departure_time = '$depart_time',
				products_departure_location = '$depart_location',
				products_room_price = '$total_room_price',
				products_room_info = '$total_info_room',
				total_room_adult_child_info = '$total_room_adult_child_info'";
				tep_db_query($Query);
				*/

				$final_product_price = $AddedOptionsPrice = 0; //新加的产品价格默认强制设置为0，让销售自己去添加

				$sql_data_array_op_insert = array(
					'orders_id' => $oID,
					'products_id' => $add_product_products_id,
					'products_model' => $p_products_model,
					'products_name' => $p_products_name,
					'products_price' => $final_product_price,
					'final_price' => ($final_product_price + $AddedOptionsPrice),
					'final_price_cost' => ($final_product_price_cost + $AddedOptionsPrice_cost),
					'products_tax' => $ProductsTax,
					'products_quantity' => $add_product_quantity,
					'products_departure_date' => $finaldate,
					'products_departure_time' => $depart_time,
					'products_departure_location' => $depart_location,
					'products_room_price' => $total_room_price,
					'products_room_info' => $total_info_room,
					'total_room_adult_child_info' => $total_room_adult_child_info,
					'order_item_purchase_date' => 'now()',
					'is_hotel' =>$is_hotel,
					'hotel_checkout_date' => $hotel_checkout_date,
					'is_transfer'=>$p_is_transfer,
					'products_price_last_modified'=> tep_db_get_field_value('products_price_last_modified','products',' products_id="'.(int)$add_product_products_id.'" '),
					'products_departure_location_sent_to_provider_confirm'=>'1'
				);
				tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_op_insert);
				$new_product_id = tep_db_insert_id();

				//amit added for cost cal history
				$sql_data_array_original_insert = array(
					'orders_products_id' => $new_product_id,
					'products_model' => $p_products_model,
					'products_name' => str_replace("'", "&#39;", $p_products_name),
					'retail' => ($final_product_price + $AddedOptionsPrice),
					'cost' => ($final_product_price_cost + $AddedOptionsPrice_cost),
					'last_updated_date' => 'now()'
				);
				tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
				//amit added for cost cal history

				//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
				tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $add_product_quantity . ", products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");
				MCache::update_product($add_product_products_id); //MCache update
				//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################

				if ($add_product_options != '') {
					foreach ($add_product_options as $option_id => $option_value_id) {
						$att_price[$option_value_id] = 0; //新加的产品属性价格默认强制设置为0，让销售自己去添加
						$Query = "insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
							orders_id = $oID,
							orders_products_id = $new_product_id,
							products_options = '" . $option_values_names[$option_value_id] . "',
							products_options_values = '" . $option_names[$option_value_id] . "',
							options_values_price = '" . (int) $att_price[$option_value_id] . "',
							options_values_price_cost = '" . (int) $att_price_cost[$option_value_id] . "',
							price_prefix = '" . $att_prefix[$option_value_id] . "' ";
						tep_db_query($Query);
						if ((DOWNLOAD_ENABLED == 'true') && isset($opt_products_attributes_filename) && tep_not_null($opt_products_attributes_filename)) {
							$sql_data_array = array('orders_id' => $oID,
							'orders_products_id' => $new_product_id,
							'orders_products_filename' => $opt_products_attributes_filename,
							'download_maxdays' => $opt_products_attributes_maxdays,
							'download_count' => $opt_products_attributes_maxcount);
							tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
						}
					}
				}

				// Stock Update
				$stock_chk_query = tep_db_query("select products_quantity from products where products_id = '" . $add_product_products_id . "'");
				$stock_chk_values = tep_db_fetch_array($stock_chk_query);
				$stock_chk_left = $stock_chk_values['products_quantity'] - $add_product_quantity;
				tep_db_query("update products set products_quantity = '" . $stock_chk_left . "' where products_id = '" . $add_product_products_id . "'");

				// Update products_ordered (for bestsellers list)
				tep_db_query("update products set products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");

				// Calculate Tax and Sub-Totals
				$order = new order($oID);
				$RunningSubTotal = 0;
				$RunningTax = 0;
				$RunningSubTotal_cost = 0;
				$RunningTax_cost = 0;
				for ($i = 0; $i < sizeof($order->products); $i++) {
					$RunningSubTotal += ( $order->products[$i]['qty'] * $order->products[$i]['final_price']);
					$RunningTax += ( ($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
					$RunningSubTotal_cost += ( $order->products[$i]['qty'] * $order->products[$i]['final_price_cost']);
					$RunningTax_cost += ( ($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price_cost']));
				}
				$Query = "update " . TABLE_ORDERS . " set order_cost = '" . $RunningSubTotal_cost . "', admin_id_orders = '".$login_id."' where orders_id=$oID";
				tep_db_query($Query);

				// Tax
				$Query = "update " . TABLE_ORDERS_TOTAL . " set
					text = '\$" . number_format($RunningTax, 2, '.', ',') . "',
					value = '" . $RunningTax . "'
					where class='ot_tax' and orders_id=$oID";
				tep_db_query($Query);

				// Sub-Total
				$Query = "update " . TABLE_ORDERS_TOTAL . " set
					text = '\$" . number_format($RunningSubTotal, 2, '.', ',') . "',
					value = '" . $RunningSubTotal . "'
					where class='ot_subtotal' and orders_id=$oID";
				tep_db_query($Query);

				// Total
				$Query = "select sum(value) as total_value from " . TABLE_ORDERS_TOTAL . " where class != 'ot_total' and orders_id=$oID";
				$result = tep_db_query($Query);
				$row = tep_db_fetch_array($result);
				$Total = $row["total_value"];
				$Query = "update " . TABLE_ORDERS_TOTAL . " set
					text = '<b>\$" . number_format($Total, 2, '.', ',') . "</b>',
					value = '" . $Total . "'
					where class='ot_total' and orders_id=$oID";
				tep_db_query($Query);

				//bhavik add you logic to insert recored for guest name and filght
				//filght information
				$airline_name = addslashes($HTTP_POST_VARS['airline_name']);
				$flight_no = addslashes($HTTP_POST_VARS['flight_no']);
				$airline_name_departure = addslashes($HTTP_POST_VARS['airline_name_departure']);
				$flight_no_departure = addslashes($HTTP_POST_VARS['flight_no_departure']);
				$airport_name = addslashes($HTTP_POST_VARS['airport_name']);
				$airport_name_departure = addslashes($HTTP_POST_VARS['airport_name_departure']);
				$arrival_date = addslashes($HTTP_POST_VARS['arrival_date']);
				$arrival_time = addslashes($HTTP_POST_VARS['arrival_time']);
				$departure_date = addslashes($HTTP_POST_VARS['departure_date']);
				$departure_time = addslashes($HTTP_POST_VARS['departure_time']);
				$sql_data_array = array('orders_id' => $_GET['oID'],
				'products_id' => $HTTP_POST_VARS['add_product_products_id'],
				'airline_name' => $airline_name,
				'flight_no' => $flight_no,
				'airline_name_departure' => $airline_name_departure,
				'flight_no_departure' => $flight_no_departure,
				'airport_name' => $airport_name,
				'airport_name_departure' => $airport_name_departure,
				'arrival_date' => $arrival_date,
				'arrival_time' => $arrival_time,
				'departure_date' => $departure_date,
				'departure_time' => $departure_time,
				'orders_products_id' => $new_product_id);
				tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, $sql_data_array);

				//记录航班信息历史
				tep_add_orders_product_flight_history($sql_data_array,$new_product_id,$login_id);


				//total_no_guest_tour
				foreach ($_POST as $key => $val) {
					if (strstr($key, 'guest')) {
						$guest_name .= $val . " <::>";
					}
				}
				$depature_full_address = $finaldate . ' &nbsp; ' . $depart_time . ' &nbsp; ' . $depart_location;
				$sql_data_array = array('orders_id' => $_GET['oID'],
				'products_id' => $HTTP_POST_VARS['add_product_products_id'],
				'guest_name' => tep_db_prepare_input($guest_name),
				'guest_body_weight' => tep_db_prepare_input($guest_body_weight),
				'depature_full_address' => tep_db_prepare_input($depature_full_address),
				'guest_number' => "0",
				'orders_products_id' => $new_product_id);
				//'guest_number' => $HTTP_POST_VARS['g_number']);
				tep_db_perform(TABLE_ORDERS_PRODUCTS_ETICKET, $sql_data_array);

				// Howard added updated orders status if ot total has changed 如果新总额比旧总额大则按需更新订单状态
				$last_total_value = get_orders_ot_total_value(tep_db_input($oID));
				if ((int) $last_total_value) {
					auto_update_orders_status_set_partial_payment_received(tep_db_input($oID));
				}
				$messageStack->add_session('请您注意：产品已经添加，但新增加产品的价格默认为0，请务必去填写正确的价格。', 'error');
				tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action', 'fudate')) . 'action=edit'));
				exit();
				//tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action','fudate')) . 'action=edit'));
			}
			break;
	}
}
//<--------------------------以上均为数据提交的程序
//---------------------------以下为数据查询的程序

if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {
	$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
	$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int) $oID . "'");
	$order_exists = true;
	if (!tep_db_num_rows($orders_query)) {
		$order_exists = false;
		$messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
	}
	//供应商订单更新历史记录的sql查询语句 start {
	$qry_order_prod_history = "select h.popc_id, h.provider_comment, h.provider_status_update_date, h.popc_user_type, s.provider_order_status_name, op.products_id,
							op.products_model, if(h.popc_user_type=0, h.popc_updated_by, p.agency_id) as updated_by, popc_updated_by, p.agency_id
							FROM " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY . " h, " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " s, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p
							WHERE
							h.provider_order_status_id=s.provider_order_status_id AND
							h.orders_products_id=op.orders_products_id AND
							op.products_id=p.products_id AND
							h.notify_usi4trip=1 AND
							op.orders_id = '" . tep_db_input($oID) . "' ORDER BY p.products_id ASC, h.provider_status_update_date ASC";
	$res_order_prod_history = tep_db_query($qry_order_prod_history);
	$agency_prod_historys = array();
	while($rows = tep_db_fetch_array($res_order_prod_history)){
		//[产品ID][回复人0为我们1为对方]
		$agency_prod_historys[$rows['products_id']][$rows['popc_user_type']][] = $rows;
	}
	//供应商订单更新历史记录的sql查询语句 end }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" language="javascript">
//以下几个函数的真正定义在页脚
function confirm_cellphone_number(orders_customers_cellphone_history_id){ alert('数据未加载完毕，请稍候……'); }
function confirm_departure_location(history_id, thisBut, i ){ alert('数据未加载完毕，请稍候……'); }
function confirm_histories_action(op_histoty_id, thisBut, i ){ alert('数据未加载完毕，请稍候……'); }
function is_read(orders_flight_id){ alert('数据未加载完毕，请稍候……'); }
function update_orders_products_name(orders_products_id){ alert('数据未加载完毕，请稍候……'); }

/* 添加OP备注按钮 */
function fn_addremark(orders_id)
{
	var s=prompt("请给订单 [ "+orders_id+" ] 输入备注的内容:");
	if (s.length==0){ alert("请务必输入备注内容"); return false;}
	if (s.length>100){ alert("内容长度超过限制"); return false;}

	//ajax
	var url="op_special_list.php?ajax=true&action=addremark&sid="+Math.random();

	jQuery.post(url, {"remark": s,"orders_id":orders_id }, function (data, textStatus){
		if('success' == data ){
			alert("ok");
			window.location = "<?= $reload_url?>";
		}
		else
		{
			alert(data);
		}
	});

}
//主管审核为【有效更新】时给该销售加分
function add_effective_updated_score(history_id, hide_obj){
	jQuery(hide_obj).attr('disabled',true);
	var url="edit_orders.php?action=add_effective_updated_score&"+Math.random();
	jQuery.post(url,{'orders_status_history_id':history_id, 'orders_id':<?= $oID;?>},function(text){
		if('success' == text){
			jQuery(hide_obj).html('分数添加成功！').hide(1000,function(){ jQuery(hide_obj).remove(); });
		}else{
			alert('操作失败！');
			jQuery(hide_obj).attr('disabled',false);
		}
	},'text');
}
</script>
<script type="text/javascript">
<!--
function run_click_button(button_id){
	if(document.getElementById(button_id)!=null){
		document.getElementById(button_id).click();
	}else{
		alert('The button does not exist! '+button_id );
	}
}

function popupWindow1(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=1000,height=500,screenX=150,screenY=100,top=100,left=150')
}

function HideContent(d) {
	if(d.length < 1) { return; }
	document.getElementById(d).style.display = "none";
}

function ShowContent(d) {
	if(d.length < 1) { return; }
	document.getElementById(d).style.display = "block";
}

function ReverseContentDisplay(d) {
	if(d.length < 1) { return; }
	if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
	else { document.getElementById(d).style.display = "none"; }
}

function calcHeight(the_iframe)
{
	var the_height=document.getElementById(the_iframe).contentWindow.document.body.scrollHeight;//find the height of the internal page
	var the_width=document.getElementById(the_iframe).contentWindow.document.body.scrollWidth;//find the width of the internal page
	if(the_height != '0'){
		the_height = parseFloat(the_height) + 10;
		//alert(the_height);
		document.getElementById(the_iframe).style.height=the_height+'px';//change the height of the iframe
	}
}

function calcHeight_increase(the_iframe,increment_size)
{
	var browser1 = navigator.appName;
	if(browser1 == "Microsoft Internet Explorer"){
		var the_height=document.getElementById(the_iframe).contentWindow.document.body.scrollHeight;//find the height of the internal page
	}else{
		var the_height= '80';
	}

	var the_width=document.getElementById(the_iframe).contentWindow.document.body.scrollWidth;//find the width of the internal page
	if(the_height != '0'){
		the_height = parseFloat(the_height) + parseFloat(increment_size) +20;
		//alert(the_height);
		document.getElementById(the_iframe).style.height=the_height+'px';//change the height of the iframe
	}
}

function resize_ifrm_by_id(frmid) {
	var ifrm = document.getElementById(frmid);
	if(window.frames[frmid] && window.frames[frmid].document) {
		window.frames[frmid].window.scroll(0,0);
		var body = window.frames[frmid].document.body;
		if(body) {
			ifrm.style.height = (body.scrollHeight || body.offsetHeight) + 'px';
		}
	}
}

function createRequestObject(){
	var request_;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_ = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_ = new XMLHttpRequest();
	}
	return request_;
}

//var http = createRequestObject();
var http1 = createRequestObject();

function update_redbox(ajax_url, response_div)
{
	try{
		http1.open('get', ajax_url);
		http1.onreadystatechange = hendleInfo_update_redbox;
		http1.send(null);
	}catch(e){
		//alert(e);
	}
}

function hendleInfo_update_redbox()
{
	if(http1.readyState == 4)
	{
		var response = http1.responseText;
		var response1 = response.split("#");
		document.getElementById(""+response1[1]+"").className = '';
		document.getElementById(""+response1[1]+"").innerHTML = response1[0];
	}
}
//-->
</script>

<script type="text/javascript" src="includes/javascript/categories.js?20131008"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>

<?PHP
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
include('includes/javascript/editor.php');
echo tep_load_html_editor();

if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) {
?>
<script type="text/javascript">
<!--
// load htmlarea
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.8 <head>
_editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  //URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')>= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE')>= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')>= 0) { win_ie_ver = 0; }
<?php
if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic') {
	?>
	if (win_ie_ver >= 5.5) {
		document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
		document.write(' language="Javascript1.2"></scr' + 'ipt>');
	}
	else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
	<?php
}
else {
	?>
	if (win_ie_ver >= 5.5) {
		document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
		document.write(' language="Javascript1.2"></scr' + 'ipt>');
	}
	else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
	<?php
}
?>
// -->
</script>
<?php
}

function RTESafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = trim($strText);
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	//$tmpString = str_replace("'", "&#39;", $tmpString);
	//convert all types of double quotes
	$tmpString = str_replace('"', '\"', $tmpString);
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), "", $tmpString);
	$tmpString = str_replace(chr(13), "\\n", $tmpString);
	return $tmpString;
}
?>

<script type="text/javascript">
var orderstatussubjectarray = new Array();
var orderstatuscommentarray = new Array();
var ordersItineraryInformation = '</br></br><b>订单详情：<a target="_blank" href="<?= tep_catalog_href_link('account_history_info.php','order_id='.$oID);?>"><?= $oID?></a>（您可以使用预订时注册的Email登录您的账户来查询订单详情）</b>';
<?php
if(!is_object($order)){
	$order = new order($oID);
}
for($i=0, $n = sizeof($order->products); $i<$n; $i++){
	if ($order->products[$i]['final_price'] == '0.00') {
		continue;
	}
	?>
	ordersItineraryInformation += "<br>";
	ordersItineraryInformation += '线路名称：<?= $order->products[$i]['name']?>'+"<br>";
	ordersItineraryInformation += '旅游团号：<?= $order->products[$i]['model']?>'+"<br>";
	ordersItineraryInformation += '出发日期：<?= substr($order->products[$i]['products_departure_date'],0,10)?>'+"<br>";
	<?php
	if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
		for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
			if($order->products[$i]['attributes'][$j]['option']!="" && $order->products[$i]['attributes'][$j]['value']!=""){
				?>
				ordersItineraryInformation += '<?= $order->products[$i]['attributes'][$j]['option'].'：'. $order->products[$i]['attributes'][$j]['value'];?>'+"<br>";
				<?php
			}
		}
	}
	?>
	<?php }?>

	var EmailSignature = '<?php echo preg_replace('/[[:space:]]+/',' ',nl2br($EmailSignature)); ?>';

/*	jQuery().ready(function(){
		jQuery('#notify').click(function(){
			tinyMCE.triggerSave();
			if(jQuery(this).attr('checked')==true && document.edit_order.comments.value.indexOf('Best Regards')==-1){
				try{
					document.edit_order.comments.value += ordersItineraryInformation;	//发信给客人时才加订单信息
					document.edit_order.comments.value += EmailSignature.replace(/\n/gi, "<br />");
					tinyMCE.updateContent('comments');
					tinyMCEformsaveIt();
				}catch(e){
				}
			}
		});
	});*/
	//写点击#notify时的动作（这种方式比上面的方法好，能实现自动化完成点击动作）
	function notify_click_1(){
		tinyMCE.triggerSave();
		var obj = jQuery('#notify');
		if(jQuery(obj).attr('checked')==true && document.edit_order.comments.value.indexOf('Best Regards')==-1){
				try{
					document.edit_order.comments.value += ordersItineraryInformation;	//发信给客人时才加订单信息
					document.edit_order.comments.value += EmailSignature.replace(/\n/gi, "<br />");
					tinyMCE.updateContent('comments');
					tinyMCEformsaveIt();
				}catch(e){
				}
		}
	}
	//自动写内容到comments框
	function auto_write_comments(text){
		document.edit_order.comments.value = text;
		tinyMCE.updateContent('comments');
		tinyMCEformsaveIt();
	}
	<?php
	$orders_status_default_email_query = tep_db_query("select orders_status_id, orders_status_name, orders_status_default_subject, orders_status_default_comment  from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int) $languages_id . "' and orders_status_display='1' ");
	while ($orders_status_default_email_row = mysql_fetch_array($orders_status_default_email_query)) {
		if ($orders_status_default_email_row['orders_status_default_comment'] != '' && $orders_status_default_email_row['orders_status_id'] != '100009' && $orders_status_default_email_row['orders_status_id'] != '100019' && $orders_status_default_email_row['orders_status_id'] != '100018' && $orders_status_default_email_row['orders_status_id'] != '100008' && $orders_status_default_email_row['orders_status_id'] != '5' && $orders_status_default_email_row['orders_status_id'] != '99999' && $orders_status_default_email_row['orders_status_id'] != '100003' && $orders_status_default_email_row['orders_status_id'] != '1' && $orders_status_default_email_row['orders_status_id'] != '2' && $orders_status_default_email_row['orders_status_id'] != '100042' && $orders_status_default_email_row['orders_status_id'] != '100071') {}
		$orders_status_default_email_row['orders_status_default_subject'] = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $HTTP_GET_VARS['oID'], $orders_status_default_email_row['orders_status_default_subject']);
		$orders_status_default_email_row['orders_status_default_comment'] = str_replace('OID', ORDER_EMAIL_PRIFIX_NAME . $HTTP_GET_VARS['oID'], $orders_status_default_email_row['orders_status_default_comment']);
		$orders_status_default_email_row['orders_status_default_comment'] = str_replace('{ORDERURL}', '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $HTTP_GET_VARS['oID']) . '" target="_blank">' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $HTTP_GET_VARS['oID']) . '</a>', $orders_status_default_email_row['orders_status_default_comment']);
		if($_GET['RefundAmount']){	//自动发退款邮件时的退款金额元素替换
			$orders_status_default_email_row['orders_status_default_comment'] = str_replace('{RefundAmount}',rawurldecode($_GET['RefundAmount']),$orders_status_default_email_row['orders_status_default_comment']);
		}
		echo 'orderstatussubjectarray[' . $orders_status_default_email_row['orders_status_id'] . '] = "' . RTESafe($orders_status_default_email_row['orders_status_default_subject']) . '";';
		$orders_status_default_comment = RTESafe($orders_status_default_email_row['orders_status_default_comment']);
		if ($orders_status_default_email_row['orders_status_id'] == "100002") {
			/* 暂时取消自动判别，系统不需要自动加“您的所有行程和在当地的机场接应事宜已经全部得到确认”
			$flight_sql = tep_db_query('SELECT * FROM `orders_product_flight` WHERE orders_id = "' . $oID . '" ');
			while ($flight_rows = tep_db_fetch_array($flight_sql)) {
			if (tep_not_null($flight_rows['flight_no_departure']) && tep_not_null($flight_rows['flight_no']) && tep_not_null($flight_rows['arrival_date']) && tep_not_null($flight_rows['departure_date'])) {
			$orders_status_default_comment = str_replace('以下信息仅用于包含机场接送服务的预订。请仔细阅读电子参团凭证上的航班信息，如有误，请尽快通知我们。', '您的所有行程和在当地的机场接应事宜已经全部得到确认。<br /><br />以下信息仅用于包含机场接送服务的预订。请仔细阅读电子参团凭证上的航班信息，如有误，请尽快通知我们。', $orders_status_default_comment);
			break;
			}
			}
			*/
		}
		//设置自动替换的标签
		if(!is_object($order)){
			$order = new order($HTTP_GET_VARS['oID']);
		}
		$_eticket_arrangement = '';
		for ($i = 0; $i < sizeof($order->products); $i++) {
			if(!(int)$order->products[$i]['parent_orders_products_id']
			&& (in_array(tep_get_orders_products_provider_status_id($order->products[$i]['orders_products_id']),array('2','6','14','30','31'))
			|| in_array($order->info['orders_status'],array('100102','100103','100116','100111')))
			){	//不可以调组合团中的子团行程，同时必须是地接已经确认了的行程
				$_eticket_arrangement .= '<p><h3>'.strip_tags($order->products[$i]['name']).'</h3></p>';
				$_eticket_arrangement .= preg_replace('/[[:space:]]+/',' ',$order->products[$i]['eticket']['tour_arrangement']);
			}
		}
		$strtr_array = array('{程序自动调出地接给我们回复的行程及酒店}'=>RTESafe($_eticket_arrangement));

		$orders_status_default_comment = strtr($orders_status_default_comment, $strtr_array);

		echo 'orderstatuscommentarray[' . $orders_status_default_email_row['orders_status_id'] . '] = "' . $orders_status_default_comment . '";';
	}
	?>

	function checkStatusNotify(){
		var statusStr = ',100009,100072,100019,100084,100085,100088,100089,100046,100045,100093,100062,100077,100006,100071,100095,';
		var Notify = document.getElementById("notify");
		var StatusSelect = document.getElementById("statusSelect");
		if(statusStr.indexOf(','+StatusSelect.value+',')!=-1 && Notify.checked==true){
			Notify.checked=false;
			alert("此状态只能内部使用，不可发送给客户！");
			return false;
		}

		<?php if($access_full_edit != 'true' && $access_order_cost != 'true'){ 	//取消订单状态只有财务会计能用?>
		if(StatusSelect.value =='6'){
			alert("取消订单状态只有财务会计可以用！");
			return false;
		}
		<?php }?>
		//Notify动作2
		notify_click_1();

		return false;
	}

	/* 指定下一位管理的开关 */
	function needNextAdmin(obj){
		if(obj.checked==true){
			jQuery("#next_admin_urgency").show();
		}else{
			jQuery("#next_admin_urgency").hide();
		}
	}

	/* 改变Email Subject和Comments的值 */
	function changesubjectemail_new(theform){
		if(document.edit_order.status.value != ""){
			try{
				document.edit_order.email_subject.value = orderstatussubjectarray[document.edit_order.status.value];
				//tinyMCEformsaveIt();
				document.edit_order.comments.value = (orderstatuscommentarray[document.edit_order.status.value]).replace(/\n/gi, "<br />");
				tinyMCE.updateContent('comments');
				tinyMCEformsaveIt();
				//alert($("#comments").val());
			}catch(e){}
		}
		return true;
	}

	function changesubjectemail(theform){
		if( document.edit_order.status.value == "100001" ){
			document.edit_order.email_subject.value = "Receipt of Reservation - Reservation # <?php echo $HTTP_GET_VARS['oID']; ?>";
		} else if(document.edit_order.status.value == "100002"){
			document.edit_order.email_subject.value = "Your E-Ticket has been issued. (Reservation # <?php echo $HTTP_GET_VARS['oID']; ?>)";
			document. edit_order.comments.value = 'Your E-Ticket has been delivered to your email address. Please carefully review all the information on your E-Ticket and let us know should there be an error.We shall not be responsible for any consequences due to being notified of an error within 72 business hours before departure date by customer. \n\nYou may also access your E-Ticket by logging into your account: <?= HTTP_SERVER?>/login.php  "view" your previous orders and click "e-ticket" button. \n\nThank you very much for your purchase.';
		}else if(document.edit_order.status.value ==  "100000"){
			document. edit_order.email_subject.value = "Reservation Confirmation";
			document. edit_order.comments.value = "Congratulations! Your reservation on usitrip has been confirmed. Please save this confirmation email and your reservation number for future reference. We will send you an E-Ticket two or three days before your departure date or even sooner. \n\nIf your reservation includes airport pick-up and/or drop-off and you have not yet provided your flight information to us, please go to <?= HTTP_SERVER?>/account.php to update your flight information as soon as you book flights. We will not be able to issue an E-Ticket to you without flight information.";
	}else if(document.edit_order.status.value ==  "100003"){
		document. edit_order.email_subject.value = "Reservation Update - Reservation # <?php echo $HTTP_GET_VARS['oID']; ?>";
	}else if(document.edit_order.status.value ==  "100004"){
		document. edit_order.comments.value = "We regret that we will not be available to confirm booking of your interested tour this time. We apologize for any inconvenience this may have caused you.";
	}else if(document.edit_order.status.value ==  "100005"){
		document. edit_order.comments.value = "We have refunded $ to your credit card per your request for your reservation.\n\nThe transaction may be displayed temporarily as a pending transcation before it is settled. Please allow a couple of days for it to appear on your credit card statement. \n\nWe appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future.";
	}else if(document.edit_order.status.value ==  "100007"){
		document. edit_order.comments.value = "Adjusted Sales:	Adjusted Net:\n\nOriginal Sales:	Original Net:";
	}else if(document.edit_order.status.value ==  "6"){
		document. edit_order.comments.value = "We regret that we were not able to provide a tour for you this time but we definitely appreciate your interest.\n\nPlease come back see us soon.";
	}else if(document.edit_order.status.value ==  "100011"){
		document. edit_order.comments.value = "Our attempts to authorize $ on your provided credit card were declined for some reason.\n\nPlease check with your credit card issuer to make sure our next attempt upon receipt of your required documents will go through.\n\nOr if you would like to use another credit card, please feel free to make changes on the Acknowledgement of Card Billing form and send supporting documents to us.\n<?= HTTP_SERVER?>/acknowledgement_of_card_billing.php.\n\nThank you for your attention. We would appreciate if you could respond promptly. ";
	}else if(document.edit_order.status.value ==  "100012"){
		document. edit_order.comments.value = "Please provide flight information to us by logging into <?= HTTP_SERVER?>/account.php to update your flight information in your account as soon as you book flight. We will not be able to issue an E-Ticket to you without flight information. We would appreciate if you could send an email to <?php echo STORE_OWNER_EMAIL_ADDRESS; ?> to inform us of your completion of flight update in your account. Thank you in advance for your time.";
	}else if(document.edit_order.status.value ==  "100013"){
		document. edit_order.comments.value = "Congratulations! Your reservation on usitrip has been confirmed. Please save this confirmation email and your reservation number for future reference.  \n\nWe will issue an E-Ticket (voucher needed to join tour activity) to you as soon as we receive supporting documentation from you. \n\nTo review details of supporting documentation requirement and how to send documents to us, please go to <?= HTTP_SERVER?>/acknowledgement_of_card_billing.php\n\nThank you in advance for your time.";
	}else if(document.edit_order.status.value ==  "100014"){
		document. edit_order.comments.value = "Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received your documents and everything looks great. We will issue an E-Ticket to you shortly. ";
	}else if(document.edit_order.status.value ==  "100015"){
		document. edit_order.comments.value = "Thank you for following up with our documentation requirement in a prompt manner.  We confirm that we have received your documents; however, your copy of valid photo ID was missing.  Please send a copy to us at your earliest convenience.";
	}else if(document.edit_order.status.value ==  "100016"){
		document. edit_order.comments.value = "Please notify us as soon as you send funds to us. We would like to further process your booking. \n\nThank you for your attention.";
	}else if(document.edit_order.status.value == "100020"){
		document. edit_order.comments.value = "We have received your email regarding cancellation of your booking; however, according to our Cancellation and Refund Policy, we will not acknowledge voice mail or email cancellation.Cancellation must be made in writing by fax or by mail or by sending scanned/digital Cancellation Request Form with signature.\n\nTo download Cancellation Request Form, please go to <?= HTTP_SERVER?>/cancellation-and-refund-policy.php";
	}else if(document.edit_order.status.value == "100021"){
		document. edit_order.comments.value = "Thank you for your follow-up.  We confirm receipt of your flight information.  We will issue an E-Ticket to you shortly.";
	}else if(document.edit_order.status.value == "100022"){
		document. edit_order.comments.value = "We confirm receipt of your Cancellation Request Form.  According to our Cancellation and Refund Policy, <?= HTTP_SERVER?>/cancellation-and-refund-policy.php\n\nWe will charge  of the tour fees ($  ) as cancellation cost. Please allow 1-5 business days for us to process your request.\n\nWe regret that you cannot join a tour this time and hope you will come back visit us in the future.  We appreciate your business.";
	}else if(document.edit_order.status.value == "100023"){
		document. edit_order.comments.value = "We confirm receipt of your wire transfer. We have proceeded with further processing your booking. Thank you.";
	}else if(document.edit_order.status.value == "100024"){
		document. edit_order.comments.value = "You paypal payment did not go through. Please check with Paypal and re-send the payment to paypal@usitrip.com. Besides Paypal, we also accept wire transfer, credit card and money order as payment option. If you wish to use credit card (Visa or Mastercard), you may follow the below link, download and fill out the Acknowledgement of Card Billing form and send it to us with a copy of credit card holder\'s US Driver\'s License or US Photo ID or national passport with signature page. Thank you for your attention and time in advance. <?= HTTP_SERVER?>/acknowledgement_of_card_billing.php  ";
	}
	return true;
}
</script>

<script>
function hasClass(ele,cls) {
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
function addClass(ele,cls) {
	if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
function removeClass(ele,cls) {
	if (hasClass(ele,cls)) {
		var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	}
}
function toggleClass(ele,cls) {
	if(hasClass(ele,cls)){
		removeClass(ele,cls);
	}
	else
	addClass(ele,cls);
}
function getStyle(elem,styleName){
	if(elem.style[styleName]){
		return elem.style[styleName];
	}
	else if(elem.currentStyle){//IE
		return elem.currentStyle[styleName];
	}
	else if(document.defaultView && document.defaultView.getComputedStyle){
		styleName = styleName.replace(/([A-Z])/g,'-$1').toLowerCase();
		var s = document.defaultView.getComputedStyle(elem,'');
		return s&&s.getPropertyValue(styleName);
	}
	else{
		return null;
	}
}
function showHideLyer(tit,con,cls){
	toggleClass(tit,cls);
	var t = document.getElementById(con);
	if(t){t.style.display = getStyle(t,'display') == 'none' ? '' : 'none';}
}

function trimstr(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function ajax_add_comment(product_id,add_comment)
				{ 
					try{
						add_comment = add_comment.replace(/\+/g,"|plushplush|");
						add_comment = add_comment.replace(/\&/g,"|ampamp|");
						add_comment = add_comment.replace(/\#/g,"|hashhash|");
						add_comment = add_comment.replace(/\n/g,"|newline|");
						
							http1.open('get', 'edit_orders.php?product_id='+product_id+'&comment='+add_comment+'&action_addcomment=true');
							http1.onreadystatechange = hendleInfo_add_comment;
							http1.send(null);
					}catch(e){ 
						//alert(e);
					}
				}
				
 function hendleInfo_add_comment()
					{
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 var responsecomment = response1.split("|!!!!!|");
						   var div_id = trimstr(responsecomment[0]);
						 document.getElementById("responsediv"+div_id).innerHTML = responsecomment[1]+'<div style="clear:both;"></div><div style="float:right"><a style="CURSOR: pointer" class="col_a1_popup" onclick="javascript:toggel_div_comment(\'div_comment_order_paid_payment_'+div_id+'\',\'responsediv'+div_id+'\');">Edit</a></div>';
						 document.getElementById("div_comment_order_paid_payment_"+div_id).style.display = 'none';
						 document.getElementById("responsediv"+div_id).style.display = '';
						}
					}
						
function toggel_div_comment(divid,commentdivid)
		{
			if(eval("document.getElementById('" +  divid + "').style.display") == ''){
				eval("document.getElementById('" +  divid + "').style.display = 'none'");
				if(divid!=commentdivid){
					eval("document.getElementById('" +  commentdivid + "').style.display = 'none'");
				}
			}else{
				eval("document.getElementById('" +  divid + "').style.display = ''");
				if(divid!=commentdivid){
					eval("document.getElementById('" +  commentdivid + "').style.display = 'none'");
				}
			}
		}
</script>

<link rel="stylesheet" href="includes/javascript/jquery-plugin-boxy/css/common.css" type="text/css" />
<link rel="stylesheet" href="includes/javascript/jquery-plugin-boxy/css/boxy.css" type="text/css" />
<script type="text/javascript" src="includes/javascript/jquery-plugin-boxy/jquery.boxy.js"></script>
<script language="javascript" type="text/javascript">
/*这一段都是付款记录之财务备注用的*/
var allDialogs = [];
function add_account_remark(obj,id)
{
	if("none" == jQuery("#input_remark_"+id).css("display"))
	{
		jQuery("#input_remark_"+id).css("display","block");
		obj.innerHTML="保存";
	}
	else
	{
		//ajax		
		var url="accounts_receivable.php?ajax=true&action=addremark";
		var remark=jQuery("#input_remark_"+id).val();
		if (remark.length<1){ alert("未填写内容"); return false; }
		if (remark.length>100){ alert("内容长度不能超过100个字"); return false;}
		jQuery.post(url, {"orders_payment_history_id": id,"remark":remark}, function (data, textStatus){
			if('success' == data ){
				alert("ok");
				jQuery("#input_remark_"+id).css("display","none");
				var text=jQuery("#input_remark_"+id).val();
				jQuery("#div_remark_"+id).text(text);
				obj.innerHTML="添加";
			}
			else
			{
				alert(data);
			}
		});	
	}
}
function show_account_remark(obj,id)
{
	var url="edit_orders.php";
	jQuery.get(url, {"ajax":"true","action":"showhistory","orders_payment_history_id": id}, function (data, textStatus){
		if( data.length>0 ){
				var options = {modal:true};
				options = jQuery.extend({title: "财务备注之历史记录"}, options || {});
				var dialog = new Boxy(data, options);
				allDialogs.push(dialog);
		}
		else
		{
			
		}

	});	
}

/* 订单状态更新历史记录中设置下一处理人已经完成处理 */
function set_processing_done(login_id, orders_status_history_id, button){
	if(login_id > 0 && orders_status_history_id > 0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=set_processing_done')) ?>");
		jQuery.post(url,{ajax:'true', next_admin_id:login_id, orders_status_history_id:orders_status_history_id },function(text){
			if(text=='ok'){
				jQuery(button).html('设置成功！');
				jQuery(button).attr('disabled','disabled');
				window.setTimeout(function(){
					jQuery(button).remove();
				}, 2000);
			}
		},'text');
	}
}
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("<div class=\"toolkit\"></div>").appendTo("body");
	var toggleCnt = jQuery("div#orderHead").html();
	jQuery("div.toolkit").html(toggleCnt);
	jQuery(window).bind("scroll",function(){
		var top = Math.max(document.documentElement.scrollTop,document.body.scrollTop);
		var oh = jQuery("div#orderHead").offset().top;
		if (top >= oh){
			jQuery("div.toolkit").show();
			jQuery("div.toolkit").html(toggleCnt);
			jQuery("div#orderHead > div.orderHead").remove();
		}else{
			jQuery("div.toolkit").hide();
			jQuery("div#orderHead").html(toggleCnt);
			jQuery("div.toolkit > div.orderHead").remove();
		}
	});
});
</script>
<style type="text/css">
.toolkit{width:98%;margin:0 auto;display:none;}
.toolkit{z-index:999;position:fixed;_position:absolute; top:0;left:10px;_top:expression(eval(document.documentElement.scrollTop));width:100%;_width:expression(eval(document.documentElement.clientWidth));}
</style>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<!-- <div id="spiffycalendar" class="text"></div> -->
<?php
require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
	  <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
		<!-- left_navigation //-->
		<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
		<!-- left_navigation_eof //-->
	  </table>
	</td>
	<!-- body_text //-->
	<td width="100%" valign="top">
	  <table border="0" width="100%" cellspacing="0" cellpadding="2">
		<?php
		if (($action == 'edit') && ($order_exists == true)) {
			$order = new order($oID);// 不能加后面参数 加了出错,'op.products_departure_date asc');
		?>
		<tr>
		  <td width="100%">
		  <div id="orderHead" style="padding:0; margin:0;">
          <div class="orderHead">
			<table border="0" cellspacing="0" cellpadding="0" class="order_head">
			  <tr>
				<td width="80%">
				<?php
				if (isset($HTTP_GET_VARS['search']) && $HTTP_GET_VARS['search'] != '') {
					$search_val = tep_db_prepare_input($HTTP_GET_VARS['search']);
				}/* else{
				$search_val = $oID;
				} */
				
				?>
				  <table cellpadding="0" cellspacing="0">
					<tr><td>
					<?php
					echo tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'y', 'x', 'action', 'fudate')), 'get');
					?><table cellpadding="0" cellspacing="0" border="0">
					<tr>
					  <td style="line-height:24px; "><h3>订单号：<?= $oID;?></h3></td>
					  <td align="right">
					  <?php
					  if (isset($search_val) && tep_not_null($search_val)) {
					  	$search_val = $search_val;
					  } else {
					  	$search_val = HEADING_TITLE_SEARCH;
					  }
					  echo ' <input type="text" name="oID" value="' . $search_val . '"  size="12" class="order_text1 searchbox" onFocus="javascript: this.value=\'\';" > ' . tep_draw_hidden_field('action', 'edit');
					  ?>
					  </td>
					  <td align="left">
						<input type="submit" name="btnsubmit" value="<?php echo IMAGE_SEARCH; ?>" class="button" />
						<?php //echo tep_image_submit('button_search.gif', IMAGE_SEARCH);?>
					  </td>
					  </tr>
					  </table>
					  </form>
					  </td>
					  <td align="left">
						&nbsp;&nbsp;订单归属工号：<span id="js_orders_owners" style="padding:5px;"><?= $order->info['orders_owners'];?>&nbsp;</span>
						<?php 
						if ($login_id == '246' || $login_id == '222' || $login_id == '19') {
						?>
													<span id="js_orders_owners_edit" style="display:none"><input type="text" id="js_orders_owners_edit_input" style="width:80px;" value="<?php echo $order->info['orders_owners']?>"/>&nbsp;<input type="button" id="ajax_btn_update_owners" value="保存修改" onclick="ajax_owners_save()"/></span>
													<script type="text/javascript">
													jQuery(document).ready(function(e) {
														jQuery('#js_orders_owners').css({'cursor':'pointer','border':'1px solid #fff'}).attr('title','点击修改订单归属');
														jQuery('#js_orders_owners').click(function(e) {
															jQuery(this).hide();
															jQuery('#js_orders_owners_edit').show();
														});
														
														
													});
													function ajax_owners_save() {
														var url = '<?php echo tep_href_link_noseo('edit_orders.php')?>';
														jQuery.post(url,{'action':'update_owners','ajax':'true','orders_id':'<?php echo $oID?>','owners':jQuery('#js_orders_owners_edit_input').val()},function(text){
															if (text == 'ok') {
																jQuery('#js_orders_owners').html(jQuery('#js_orders_owners_edit_input').val()).show();
																jQuery('#js_orders_owners_edit').hide();
															}
														});
													}
													</script>
						<?php 
						}
						?>
						&nbsp;&nbsp;销售链接工号：<?= tep_get_order_owner_admin((int)$_GET['oID']);?>
						&nbsp;&nbsp<?=$order->info['owner_id_change_str'];?>
					  </td>
					</tr>
				  </table>
				
				</td>
				<?php
				//begin PayPal_Shopping_Cart_IPN V2.8 DMG;
				if ((strtolower($order->info['payment_method']) == 'paypal') && (isset($HTTP_GET_VARS['referer'])) && ($HTTP_GET_VARS['referer'] == 'ipn')) {
				?>
				<td align="center" width="20%"><?php echo '<a href="' . tep_href_link(FILENAME_PAYPAL, tep_get_all_get_params(array('action', 'oID', 'referer', 'fudate'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
				<?php } else { ?>
				<td align="center" width="20%"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action', 'referer', 'fudate'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
				<?php
				}
				//end PayPal_Shopping_Cart_IPN
				?>
			  </tr>
			</table>
          
		  <table cellpadding="2" cellspacing="0" class="order_head"  style="padding:10px;">
			  <tr>
				<td align="right">order#:&nbsp;</td>
				<td><span class="col_b"><?php echo tep_db_input($oID); ?></span></td>
				<td align="right">购买日期: </td>
				<td><span class="col_b"><?php echo tep_datetime_short($order->info['date_purchased']); ?></span></td>
				<td align="right">Status: </td>
				<td><span class="col_b"><?php echo tep_get_orders_status_name($order->info['orders_status']); ?></span></td>
				<td align="right">Departure date:</td>
				<td><span class="col_b" id="RecentDateOfDeparture"></span></td>
				<td nowrap="nowrap">
				<?php
				if($can_show_warning_order_up_no_change_status === true){
					if($order->info['order_up_no_change_status']=="1"){
				?>
				<h2 class="col_red_b">此单为【未产生费用，有更新的订单】
				<button type="button" onclick="location='<?php echo tep_href_link(FILENAME_EDIT_ORDERS,'oID='.$_GET['oID'].'&action=checked_order_up_no_change_status');?>'">取消关注</button>
				</h2>
				<?php
					}
					if(tep_db_get_field_value('orders_id', 'orders_op_think_problems', 'orders_id="'.(int)$_GET['oID'].'"')){
				?>
				<h2 id="orders_op_think_problems_A" class="col_red_b">此单为【操作员认为有问题的订单】
				<button type="button" onclick="fnFinish(<?= (int)$_GET['oID'];?>); ">确认处理OK</button>
				</h2>
				<?php
					}
				}
				?>
				<?php if($order->info['is_again_paid']=="1" && $can_close_again_paid_orders === true){?>
				<h2 class="col_red_b">
				此单为【再次付款需更新订单】
				<button type="button" onclick="location='<?php echo tep_href_link(FILENAME_EDIT_ORDERS,'oID='.$_GET['oID'].'&action=close_set_again_paid_orders');?>'">取消关注</button>
				</h2>
				<?php }?>
				</td>
			  </tr>
			</table>
		  </div>
		  </div>
		  </td>
		</tr>
		
		<tr>
		  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
		</tr>
	  </table>
	<?php
	//加载投诉处理功能块(功能完成后再开放) 2013-07-22 fixed Howard, 模块开发人wtj
	if(1){
		require 'edit_order_complaints.php';
	}
	?>
		<!-- Begin Addresses Block -->
<script type="text/javascript">
		var from_have_error = 0;
		function checkOutOnSubmit(){
			var formObj = document.getElementById("edit_order");
			if(formObj.elements["status"].value<1){
				alert("Status can not null!");
				return false;
			}
			if(from_have_error > 0){
				alert("表单有错，不能提交！");
				return false;
			}
			<?php	//订单状态更改为 【0-05请求取消,递交主管审核】时必须要有下一处理人?>
			if(formObj.elements["status"].value==100152 && (formObj.elements["need_next_admin"].checked!=true || formObj.elements["next_admin_id"].value=='0')){
				alert("订单状态更改为 【0-05请求取消,递交主管审核】时必须要有下一处理人：316、106、885、900、1001中的其中一个！");
				return false;
			}
			/*if(formObj.elements["status"].value==100045 && formObj.elements["provider_cancellation_penalty"].value<1){
			alert("Provider Cancellation Penalty can not null!");
			formObj.elements["provider_cancellation_penalty"].focus();
			return false;
			}*/
			formObj.submit();
		}
		</script>

		<?php echo tep_draw_form('edit_order', "edit_orders.php", tep_get_all_get_params(array('action', 'paycc', 'fudate')) . 'action=update_order', 'post', ' id="edit_order" onSubmit="checkOutOnSubmit(); return false;"'); ?>
		<?php
		echo tep_draw_hidden_field('old_last_modified', $order->info['last_modified']);
		echo tep_draw_hidden_field('old_ot_total', get_orders_ot_total_value($oID));
		?>
		<table tip="ordersFormTable" width="100%" cellspacing="0" cellpadding="2" border="0">
		<tr>
		  <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
				<td>
				<?php #bylwkai 2012-08-20 add 
				$temp_sql = "SELECT `visa_orderid` FROM `visa_orders_byadmin` WHERE orders_id = '" . $oID . "' AND is_deleted='0'";
				$result = tep_db_query($temp_sql);
				if (tep_db_num_rows($result) > 0){
					$row = tep_db_fetch_array($result);
					$visa_order_text = '签证订单号:<a style="font-size:18px;" href="visa.php?action=search&visa_orderid=' . $row['visa_orderid'] .'">' . $row['visa_orderid'] . '</a>';
					$is_order = true;
				}// add end 
				// 只有财务才有权下签证订单 by lwkai add 2012-11-12 15:11
				if($can_send_visa_invitations === true){
				?>
				<a <?php if ($is_order != true){?>href="visa.php?orders_id=<?php echo $_GET['oID'];?>&action=list"<?php }?>>----------点击这里下签证订单----------</a>
				<?php 
				}
				echo '<span style="font-size:18px;color:#f00;">' . $visa_order_text . '</span>';
				
				//订单关联人员查看相关订单顾客信息权限 start {
				//已付款(包括部分付款)订单所有销售可查看【顾客信息】中【客人姓名】和【联系电话】，非订单关联销售不可查看【客人邮箱】
				$show_customer_info = true;
				$is_authorize_admin_customer_email = false;
				if($show_customer_info_permission === true && $show_customer_info_email_permission === true){
					$is_authorize_admin_customer_email = true;
				}
				if($show_customer_info_permission !== true){
					$tmp_sql = tep_db_query('select orders_id from orders where orders_id='.(int)$_GET['oID'].' and (orders_owner_admin_id="'.(int)$login_id.'" || next_admin_id="'.(int)$login_id.'" || FIND_IN_SET("'.tep_get_job_number_from_admin_id((int)$login_id).'",orders_owners)'.')');
					$tmp_row = tep_db_fetch_array($tmp_sql);
					if(!(int)$tmp_row['orders_id']){
						$show_customer_info = false;
					}else{
						$is_authorize_admin_customer_email = true;
					}
				}
				//订单关联人员查看相关订单顾客信息权限 end }
				?>
				  <div class="ItemsTj">
					<?php if($show_customer_info_permission || $show_customer_info){?><h1 class="ItemsH1 ItemsH1Select" onClick="showHideLyer(this,'CustomerAndGuestInformation','ItemsH1Select')">顾客信息</h1><?php } ?>
					<table id="CustomerAndGuestInformation" class="ItemsTjContent" style="display:<?= "none"; ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
					  <tr>
						<td valign="top">
						<?php
						$customer_original_info = tep_get_customers_info($order->customer['id']);
						?>
						  <table border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td class="col_b1" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
							  <td class="col_b1" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?>&nbsp;&nbsp;<a href="javascript: get_customer_billing_info('<?php echo tep_html_quotes($customer_original_info["customers_firstname"] . " " . $customer_original_info["customers_lastname"]); ?>', '<?php echo tep_html_quotes($customer_original_info["entry_company"]); ?>', '<?php echo tep_html_quotes($customer_original_info["entry_street_address"]); ?>', '<?php echo tep_html_quotes($customer_original_info["entry_suburb"]); ?>', '<?php echo tep_html_quotes($customer_original_info["entry_city"]); ?>', '<?php echo tep_html_quotes($customer_original_info["entry_state"]); ?>', '<?php echo $customer_original_info["entry_postcode"]; ?>', '<?php echo tep_html_quotes(tep_get_country_name($customer_original_info["entry_country_id"])); ?>');" class="col_a1" id="txt_customer_info">Obtain Customer Information</a></b></td>
							  <td class="col_b1" valign="top"><b><?php echo 'Guest Information'; ?></b></td>
							</tr>
							<tr>
							  <td class="col_h p_l" valign="top"><?php // rowspan="2"  ?>
								<table border="0" cellspacing="0" cellpadding="2">
								  <tr>
									<td class="col_h"><?php echo ENTRY_NAME ?></td>
									<td class="p_b">
									<?php
									if(!tep_not_null($order->customer['name'])) $order->customer['name'] = $customer_original_info['customers_firstname'];
									?>
									<input name='update_customer_name' size='37' value='<?php echo tep_html_quotes($order->customer['name']); ?>' class="order_text2">
									</td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_COMPANY ?></td>
									<td class="p_b"><input name='update_customer_company' size='37' value='<?php echo tep_html_quotes($order->customer['company']); ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo CATEGORY_ADDRESS ?></td>
									<td class="p_b"><input name='update_customer_street_address' size='37' value='<?php echo tep_html_quotes($order->customer['street_address']); ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_SUBURB ?></td>
									<td class="p_b"><input name='update_customer_surburb' size='37' value='<?php echo tep_html_quotes($order->customer['suburb']); ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_CITY ?></td>
									<td class="p_b"><input name='update_customer_city' size='15' value='<?php echo tep_html_quotes($order->customer['city']); ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_STATE ?></td>
									<td class="p_b"><input name='update_customer_state' size='15' value='<?php echo tep_html_quotes($order->customer['state']); ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_POST_CODE ?></td>
									<td class="p_b"><input name='update_customer_postcode' size='5' value='<?php echo $order->customer['postcode']; ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_COUNTRY ?></td>
									<td class="p_b"><input name='update_customer_country' size='37' value='<?php echo tep_html_quotes($order->customer['country']); ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
									<td class="p_b"><input sensitive="true" name='update_customer_telephone' size='15' value='<?php echo $order->customer['telephone']; ?>' class="order_text2"></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
									<?php if(!tep_not_null($order->customer['email_address'])) $order->customer['email_address'] = tep_get_customers_email($order->customer['id']);?>
									<td class="p_b">
									<input type="<?php if($is_authorize_admin_customer_email!==true){ echo "hidden"; }else{ echo "text";}?>" name='update_customer_email_address' size='35' <?php if($can_edit_customers_info !== true && !$show_customer_info){?> readonly="readonly" <?php }?> value='<?php echo $order->customer['email_address']; ?>' class="order_text2">
									<?php if($is_authorize_admin_customer_email!==true){ echo "[不可见]"; }?>
									</td>
								  </tr>
								  <tr>
									<td class="col_h">Guest Cell Phone (Emergency Only): </td>
									<td class="p_b">
									<?php 
									//当日游客手机
									$gUpdateCustomersCellphone =  $order->info['guest_emergency_cell_phone'] ? $order->info['guest_emergency_cell_phone'] : tep_customers_cellphone($order->customer['id']);
									$gCellphoneUpdatedHistory = tep_get_customers_cellphone_history($oID);
									?>
									<input sensitive="true" name="update_customers_cellphone" size="35" value="<?php echo $gUpdateCustomersCellphone; ?>" class="order_text2">
									<input type="hidden" name="update_customers_cellphone_old" value="<?php echo $gUpdateCustomersCellphone; ?>" />
									<span sensitive="true">手机号必须带国家代码，否则无法接收短信！例如：+86 13509636116, +1-626-456-7890</span>
									
									</td>
								  </tr>
								</table></td>
							  <?php if ($SeparateBillingFields) { ?>
							  <td class="col_h p_l" valign="top"><div id="customer_billing_info">
								  <table border="0" cellspacing="0" cellpadding="2">
									<tr>
									  <td class="col_h"><?php echo ENTRY_NAME ?></td>
									  <td class="p_b"><input name='update_billing_name' size='37' value='<?php echo tep_html_quotes($order->billing['name']); ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo ENTRY_COMPANY ?></td>
									  <td class="p_b"><input name='update_billing_company' size='37' value='<?php echo tep_html_quotes($order->billing['company']); ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo CATEGORY_ADDRESS ?></td>
									  <td class="p_b"><input name='update_billing_street_address' size='37' value='<?php echo tep_html_quotes($order->billing['street_address']); ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo ENTRY_SUBURB ?></td>
									  <td class="p_b"><input name='update_billing_surburb' size='37' value='<?php echo tep_html_quotes($order->billing['suburb']); ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo ENTRY_CITY ?></td>
									  <td class="p_b"><input name='update_billing_city' size='15' value='<?php echo tep_html_quotes($order->billing['city']); ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo ENTRY_STATE ?></td>
									  <td class="p_b"><input name='update_billing_state' size='15' value='<?php echo tep_html_quotes($order->billing['state']); ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo ENTRY_POST_CODE ?></td>
									  <td class="p_b"><input name='update_billing_postcode' size='5' value='<?php echo $order->billing['postcode']; ?>' class="order_text2"></td>
									</tr>
									<tr>
									  <td class="col_h"><?php echo ENTRY_COUNTRY ?></td>
									  <td class="p_b"><input name='update_billing_country' size='37' value='<?php echo tep_html_quotes($order->billing['country']); ?>' class="order_text2"></td>
									</tr>
								  </table>
								</div></td>
							  <?php } ?>
							  <td class="col_h p_l" valign="top">
								<input name='update_delivery_name' size='37' value='<?php echo tep_html_quotes($order->delivery['name']); ?>' class="order_text2" type="hidden">
								<input name='update_delivery_company' size='37' value='<?php echo tep_html_quotes($order->delivery['company']); ?>' class="order_text2" type="hidden">
								<input name='update_delivery_street_address' size='37' value='<?php echo tep_html_quotes($order->delivery['street_address']); ?>' class="order_text2" type="hidden">
								<input name='update_delivery_surburb' size='37' value='<?php echo tep_html_quotes($order->delivery['suburb']); ?>' class="order_text2" type="hidden">
								<input name='update_delivery_city' size='15' value='<?php echo tep_html_quotes($order->delivery['city']); ?>' class="order_text2" type="hidden">
								<input name='update_delivery_state' size='15' value='<?php echo tep_html_quotes($order->delivery['state']); ?>' class="order_text2" type="hidden">
								<input name='update_delivery_postcode' size='5' value='<?php echo $order->delivery['postcode']; ?>' class="order_text2" type="hidden">
								<input name='update_delivery_country' size='37' value='<?php echo tep_html_quotes($order->delivery['country']); ?>' class="order_text2" type="hidden">
								<?php /* ?>
						<table border="0" cellspacing="0" cellpadding="2">
						<tr>
						<td class="col_h"><?php echo ENTRY_NAME ?></td>
						<td class="p_b"><input name='update_delivery_name' size='37' value='<?php echo tep_html_quotes($order->delivery['name']); ?>' class="order_text2"></td>
						</tr>
						<tr>
						<td class="col_h"><?php echo ENTRY_COMPANY ?></td>
						<td class="p_b"><input name='update_delivery_company' size='37' value='<?php echo tep_html_quotes($order->delivery['company']); ?>' class="order_text2"></td>
						</tr>
						<tr>
						<td class="col_h"><?php echo CATEGORY_ADDRESS ?></td>
						<td class="p_b"><input name='update_delivery_street_address' size='37' value='<?php echo tep_html_quotes($order->delivery['street_address']); ?>' class="order_text2"></td>
						</tr>
						<tr>
						<td class="col_h"><?php echo ENTRY_SUBURB ?></td>
						<td class="p_b"><input name='update_delivery_surburb' size='37' value='<?php echo tep_html_quotes($order->delivery['suburb']); ?>' class="order_text2"></td>
						</tr>
						<tr>
						<td class="col_h"><?php echo ENTRY_CITY ?></td>
						<td class="p_b"><input name='update_delivery_city' size='15' value='<?php echo tep_html_quotes($order->delivery['city']); ?>' class="order_text2"> </td>
						</tr>
						<tr>
						<td class="col_h"><?php echo ENTRY_STATE ?></td>
						<td class="p_b"><input name='update_delivery_state' size='15' value='<?php echo tep_html_quotes($order->delivery['state']); ?>' class="order_text2"> </td>
						</tr>
						<tr>
						<td class="col_h"><?php echo ENTRY_POST_CODE ?></td>
						<td class="p_b"><input name='update_delivery_postcode' size='5' value='<?php echo $order->delivery['postcode']; ?>' class="order_text2"></td>
						</tr>
						<tr>
						<td class="col_h"><?php echo ENTRY_COUNTRY ?></td>
						<td class="p_b"><input name='update_delivery_country' size='37' value='<?php echo tep_html_quotes($order->delivery['country']); ?>' class="order_text2"></td>
						</tr>
						</table>
								<?php */ ?>
								<table border="0" cellspacing="0" cellpadding="2">
								  <?php
								  $concat_string_ordprodid_tour_code = '';
								  $op_sort_order = 0;
								  for ($i = 0; $i < sizeof($order->products); $i++) {
								  	// set sort order to display in order the all tours and hotels of hotel extension
								  	if($order->products[$i]['is_hotel']==0){
								  		if(tep_not_null($order->products[$i]['hotel_extension_info'])){
								  			$hotel_extension_info = explode('|=|', $order->products[$i]['hotel_extension_info']);
								  			tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set sort_order = '".$op_sort_order."' where products_id = '".$hotel_extension_info[2]."' and is_early = '1'");
								  			$op_sort_order++;
								  			tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set sort_order = '".$op_sort_order."' where orders_products_id = '".$order->products[$i]['orders_products_id']."'");
								  			$op_sort_order++;
								  			tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set sort_order = '".$op_sort_order."' where products_id = '".$hotel_extension_info[8]."' and is_early = '2'");
								  			$op_sort_order++;
								  		}
								  	}
								  	if($order->products[$i]['is_hotel']==1 && ($order->products[$i]['is_early']==3 || $order->products[$i]['is_early']==0)){
								  		tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set sort_order = '".sizeof($order->products)."' where orders_products_id = '".$order->products[$i]['orders_products_id']."'");
								  	}
								  	// set sort order to display in order the all tours and hotels of hotel extension
								  	$concat_string_ordprodid_tour_code .= $order->products[$i]['orders_products_id'] . '||' . $order->products[$i]['model'] . '||=||';
								  ?>
								  <tr>
									<td class="col_h" height="25" nowrap="nowrap" valign="top"><?php echo 'Tour ' . ($i + 1) . ': '; ?></td>
									<td class="p_b"><span class="col_b3"><?php echo $order->products[$i]['name']; ?></span></td>
								  </tr>
								  <?php
								  //hotel-extension -vincent - temp script begin
								  //检查该订单关联的E-Ticket和Flight是否已经填写了orders_products_id没有则填充它
								  $temp_eticket_query = tep_db_query('SELECT orders_eticket_id,orders_products_id FROM '.TABLE_ORDERS_PRODUCTS_ETICKET.' WHERE orders_id='.$oID.' AND products_id='.intval($order->products[$i]['id']));
								  while($row = tep_db_fetch_array($temp_eticket_query)){
								  	if(intval($row['orders_products_id']) == 0){
								  		tep_db_query("UPDATE ".TABLE_ORDERS_PRODUCTS_ETICKET.' SET orders_products_id='.intval($order->products[$i]['orders_products_id']).' WHERE orders_eticket_id='.$row['orders_eticket_id']);
								  	}
								  }
								  $temp_flight_query = tep_db_query('SELECT orders_flight_id,orders_products_id FROM '.TABLE_ORDERS_PRODUCTS_FLIGHT.' WHERE orders_id='.$oID.' AND products_id='.intval($order->products[$i]['id']));
								  while($row = tep_db_fetch_array($temp_flight_query)){
								  	if(intval($row['orders_products_id'])==0){
								  		tep_db_query("UPDATE ".TABLE_ORDERS_PRODUCTS_FLIGHT.' SET orders_products_id='.intval($order->products[$i]['orders_products_id']).' WHERE orders_flight_id='.$row['orders_flight_id']);
								  	}
								  }
								  //hotel-extension -vincent - temp script end
								  $guest_names_info = explode("<br />", tep_get_products_guest_names_lists($order->products[$i]['orders_products_id'],$order->products[$i]['products_departure_date']) );
								  $guest_emails_info = explode("<br />", tep_get_products_guest_emails_lists($order->products[$i]['orders_products_id']) );
								  //$guest_names_info = explode("<br />", tep_get_products_guest_names_lists($oID, $order->products[$i]['id'], $order->products[$i]['products_departure_date']));
								  //$guest_emails_info = explode("<br />", tep_get_products_guest_emails_lists($oID, $order->products[$i]['id']));
								  $total_guests = sizeof($guest_names_info);
								  for ($g = 1; $g < $total_guests; $g++) {
								  ?>
								  <tr>
									<td class="col_h" height="25"><?php echo 'Name: '; ?></td>
									<td class="p_b"><span class="col_b3"><?php echo $guest_names_info[$g - 1]; ?></span></td>
								  </tr>
								  <tr>
									<td class="col_h"><?php echo 'Email: '; ?></td>
									<td class="p_b"><input onblur='auto_write_comments("发邀请函！");' name='guestemail<?php echo $g . '_' . (int)$order->products[$i]['orders_products_id']; ?>' value="<?php echo $guest_emails_info[$g - 1]; ?>" class="order_text2"></td>
								  </tr>
								  <?php
								  }
								  ?>
								  <?php
								  }
								  $concat_string_ordprodid_tour_code = substr($concat_string_ordprodid_tour_code, 0, -5);
								  ?>
								</table>
							  </td>
							</tr>
							<?php /* ?>
								  <tr>
								  <td class="col_h p_l" align="left" valign="bottom" colspan="2">
								  <div class="order_note">
								  <span class="col_1"><?php echo HEADING_INSTRUCT1 ?></span>
								  <p><?php echo HEADING_INSTRUCT2 ?></p>
								  </div>
								  </td>
								  </tr>
							<?php */ ?>
						  </table>
						</td>
					  </tr>
					  <tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					  </tr>
					  <tr>
						<td colspan="3" class="text_c p_t"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE, 'onclick="return check_for_price_change(\'' . $concat_string_ordprodid_tour_code . '\');"'); ?></td>
					  </tr>
					</table>
				  </div>
				</td>
			  </tr>
			  <!-- End Addresses Block -->
			  <tr>
				<td class="next_line"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td>
				  <div class="ItemsTj">
					<h1 class="ItemsH1 ItemsH1Select" onClick="showHideLyer(this,'Agree_accept_return_visit','ItemsH1Select')">顾客同意回访信息</h1>
					<?php
					$retOrder = tep_db_fetch_array(tep_db_query("SELECT `is_agret`, `is_ret` FROM `orders` WHERE `orders_id`='{$_GET['oID']}'"));
					$is_agret = $retOrder['is_agret'];
					$is_ret = $retOrder['is_ret'];
					$is_agret_chk = $is_agret ? 'checked' : '';
					$retData = array();
					if ($is_agret) {
						$retData = tep_db_fetch_array(tep_db_query("SELECT * FROM `orders_return_visit` WHERE `orders_id`='{$_GET['oID']}'"));
					}
					?>
					
					<script type="text/javascript">
					<!--
					var visit_start_date_contrl = new ctlSpiffyCalendarBox("visit_start_date_contrl", "edit_order", "visit_start_date","btnDate3","<?php echo tep_get_date_disp($retData['visit_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
					var visit_end_date_contrl = new ctlSpiffyCalendarBox("visit_end_date_contrl", "edit_order", "visit_end_date","btnDate4","<?php echo tep_get_date_disp($retData['visit_end_date']); ?>",scBTNMODE_CUSTOMBLUE);
					//-->
					</script>
					
					<table id="Agree_accept_return_visit" class="ItemsTjContent" style="display:none;" width="60%" border="0" cellspacing="2" cellpadding="5">
					  <tr>
						<td colspan="4"><label>
							<input name="is_agret" type="checkbox" value="1" <?php echo $is_agret_chk; ?>/>
							<strong>Customer Agrees to Be Called Back</strong></label></td>
					  </tr>
					  <tr>
						<td width="17%">Date to be called:</td>
						<td width="38%">
						<?php echo tep_draw_input_field('visit_start_date', tep_get_date_disp($retData['visit_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.OutPutType=\'m/d/y\';GeCalendar.SetDate(this);"');?>
						  <script type="text/javascript">
						  //visit_start_date_contrl.writeControl(); visit_start_date_contrl.dateFormat="MM/dd/yyyy";
						  </script>
						  -
						  <?php echo tep_draw_input_field('visit_end_date', tep_get_date_disp($retData['visit_end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.OutPutType=\'m/d/y\';GeCalendar.SetDate(this);"');?>
						  <script type="text/javascript">
						  //visit_end_date_contrl.writeControl(); visit_end_date_contrl.dateFormat="MM/dd/yyyy";
						  </script>
						</td>
						<td width="17%">Time to be called at:</td>
						<td width="28%">
						<?php
						$ret_time_option = array();
						$ret_time_option[] = array('id' => 'Morning', 'text' => 'Morning');
						$ret_time_option[] = array('id' => 'Midday', 'text' => 'Midday');
						$ret_time_option[] = array('id' => 'Afternoon', 'text' => 'Afternoon');
						$ret_time_option[] = array('id' => 'Night', 'text' => 'Night');
						$ret_time = $retData['visit_time'];
						echo tep_draw_pull_down_menu('ret_time', $ret_time_option, '', ' class="order_option2"');
						?>
						</td>
					  </tr>
					  <tr>
						<td> Remarks:</td>
						<td colspan="3">
						<?php
						$mark = $retData['mark'];
						echo tep_draw_textarea_field("mark", '', 70, 5);
						?>
						</td>
					  </tr>
					  <tr>
						<td colspan="4" align="center">
						<?php
						if ($is_agret) {
							if ($is_ret) {
								echo '<h3 style="color:red">Has been added to reviews!</h3>';
							} else {
								$button_href = tep_href_link("reviews.php", "action=add&oID={$_GET['oID']}");
						?>
						  <a href="<?php echo $button_href; ?>">
						  <button class="but_3">Add to reviews</button>
						  </a>
						<?php
							}
						} else {
							echo tep_draw_hidden_field('edit_ret', 1);
							$prodcuts_ids = array();
							foreach ($order->products as $_products) {
								$prodcuts_ids[] = $_products['id'];
							}
							echo tep_draw_hidden_field('prodcuts_ids', join(',', $prodcuts_ids));
						?>
						  <input type="submit" name="retupdate" value="Save" class="but_3" onClick="return check_for_price_change('<?php echo $concat_string_ordprodid_tour_code; ?>');" />
						<?php } ?>
						</td>
					  </tr>
					</table>
				  </div>
				</td>
			  </tr>
			  <tr>
				<td class="next_line"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			
			  <!-- Begin Payment Block -->
			  <tr>
				<td><table id="update_info_payment_method_table" <?php if($can_view_update_info_payment_method_table!==true){ echo 'style="display:none"';}?> border="0" cellspacing="0" cellpadding="2">
					<?php
					// EOF: WebMakers.com Added: Show Order Info
					//begin PayPal_Shopping_Cart_IPN V2.8 DMG
					if (strtolower($order->info['payment_method']) == 'paypal') {
						include(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/TransactionSummaryLogs.inc.php');
					}
					?>
					<tr valine="middle">
					  <td class="col_b1" colspan="2"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
					</tr>
					<tr>
					  <td class="main">
					  <?php
					  //Howard added 优化上面的菜单只显示一部分 start
					  $pay_type_string = preg_quote('银行转账(中国)', '/') . '|' . preg_quote('银行转账/现金存款(美国)', '/') . '|' . preg_quote('银行电汇(美国)', '/') . '|' . preg_quote('支票支付(美国)', '/');
					  $Optimized_orders_pay_method = array();
					  for ($i = 0; $i < count($orders_pay_method); $i++) {
					  	//if ($order->info['payment_method'] == $orders_pay_method[$i]['id'] || preg_match('/' . $pay_type_string . '/', $orders_pay_method[$i]['id']) || $orders_pay_method[$i]['id']=="PayPal" || $orders_pay_method[$i]['id']== "信用卡（美元）") {
					  		$Optimized_orders_pay_method[] = array('id' => $orders_pay_method[$i]['id'], 'text' => $orders_pay_method[$i]['text']);
					  	//}
					  }
					  //Howard added 优化上面的菜单只显示一部分 end

					  echo tep_draw_pull_down_menu('update_info_payment_method', $Optimized_orders_pay_method, $order->info['payment_method'], 'style="width:250px" class="order_option" onchange="alert(&quot;您更新了订单支付方式，更新后订单总额可能会有所变化（结伴同游订单除外）！&quot;);" ');
					  echo tep_draw_hidden_field('old_update_info_payment_method', $order->info['payment_method']);
					  ?>
					  </td>
					  <?php
					  //if ($login_groups_id != '11') {
					  	echo '<td>' . tep_image_submit('button_update.gif', IMAGE_UPDATE, 'onClick="return check_for_price_change(\'' . $concat_string_ordprodid_tour_code . '\');"') . '</td>';
					 // }
					  if(preg_match("/".NEW_PAYMENT_METHOD_T4F_CREDIT."/i", $order->info['payment_method'])) {
					  	//if(substr($order->info['payment_method'], 0, 16) == NEW_PAYMENT_METHOD_T4F_CREDIT){
					  	echo '<td> &nbsp; <a href="'.tep_href_link(FILENAME_CUSTOMERS, 'action=view_credit_history&cID='.$order->customer['id']).'" target="_blank">Customer Credit History</a></td>';
					  }
					  ?>
					</tr>
					<tr>
					  <td class="col_h" colspan="2">
					  <?php
					  if ($order->info['payment_method'] != "Credit Card")
					  echo TEXT_VIEW_CC;
					  ?>
					  <?php
					  if ($order->info['payment_method'] != "Purchase Order")
					  echo TEXT_VIEW_PO;
					  ?>
					  </td>
					</tr>
					<?php
					if ($order->info['cc_type'] || $order->info['cc_owner'] || $order->info['payment_method'] == "Credit Card : U.S. Credit Card (Preferred)" || $order->info['payment_method'] == "Credit Card : International Credit Card" || $order->info['payment_method'] == "Credit Card" || $order->info['cc_number'] || $order->info['payment_method'] == "信用卡（美元）" || $order->info['cc_expires']) {
						$close_credit_card_info = true;	//暂关闭显示信用卡信息，以后要用随时打开即可
						if ($close_credit_card_info != true && ($login_groups_id == '1' || $access_cc_info == 'true')) { //amti added for top see top admin and account group  only
							include(DIR_WS_MODULES . 'edit_orders/credit_card_notice_and_mail.php'); //信用卡冻结信息自动识别
					?>
					<!-- Begin Credit Card Info Block -->
					<tr>
					  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
					  <td class="col_h"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
					  <td class="p_b"><input name='update_info_cc_type' size='10' value='<?php echo $order->info['cc_type']; ?>' class="order_text2"></td>
					</tr>
					<tr>
					  <td class="col_h"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
					  <td class="p_b"><input name='update_info_cc_owner'  value="<?php echo tep_db_prepare_input($order->info['cc_owner']); ?>" class="order_text2"></td>
					</tr>
					<tr>
					  <td class="col_h"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
					  <td class="p_b"><input name='update_info_cc_number' size='20' value='<?php echo "(Last 4) " . substr($order->info['cc_number'], -4); ?>' class="order_text2"></td>
					</tr>
					<tr>
					  <td class="col_h"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
					  <td class="p_b"><input name='update_info_cc_expires' size='4' value='<?php echo $order->info['cc_expires']; ?>' class="order_text2"></td>
					</tr>
					<tr>
					  <td class="col_h"><?php echo ENTRY_CREDIT_CARD_CVV; ?></td>
					  <td class="p_b"><input name='update_info_cc_cvv' size='4' value='<?php echo $order->info['cc_cvv']; ?>' class="order_text2"></td>
					</tr>
					<!-- End Credit Card Info Block -->

					<?php
						} //amti added for top see top admin and account group  only
						// purchaseorder start
					} else if ((($order->info['account_name']) || ($order->info['account_number']) || $order->info['payment_method'] == "Purchase Order" || ($order->info['po_number']))) {
					?>
					<tr>
					  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
					  <td class="main" valign="top" align="left"><b><?php echo TEXT_INFO_PO ?></b></td>
					  <td><table border="0" cellspacing="0" cellpadding="2">
						  <tr>
							<td width="10"><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
							<td class="col_h"><?php echo TEXT_INFO_NAME ?></td>
							<td><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
							<td class="p_b"><input type="text" name="account_name" value='<?php echo $order->info['account_name']; ?>' class="order_text2"></td>
							<td width="10"><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
						  </tr>
						  <tr>
							<td width="10"><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
							<td class="col_h"><?php echo TEXT_INFO_AC_NR ?></td>
							<td><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
							<td class="p_b"><input type="text" name="account_number" value='<?php echo $order->info['account_number']; ?>' class="order_text2"></td>
							<td width="10"><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
						  </tr>
						  <tr>
							<td width="10"><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
							<td class="col_h"><?php echo TEXT_INFO_PO_NR ?></td>
							<td><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
							<td class="p_b"><input type="text" name="po_number" value='<?php echo $order->info['po_number']; ?>' class="order_text2"></td>
							<td width="10"><img src="<?php echo DIR_WS_IMAGES; ?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
						  </tr>
						</table>
					  </td>
					</tr>
					<?php } ?>
				  </table>
				</td>
			  </tr>
			  <!-- End Payment Block -->
			<?php
			//客户付款记录{
			$payment_history_rows = tep_get_orders_payment_history($oID); 			
			?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td><span class="col_b1">客户付款记录</span></td>
			  </tr>
			  <tr>
				<td>
				<table id="J_CustomerPaymentHistory" class="bor_tab table_td_p" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td class="tab_t tab_line1">日期</td>
				<td class="tab_t tab_line1">Updated by</td>
				<td class="tab_t tab_line1">付款方式</td>
				<td class="tab_t tab_line1">备注</td>
				<td class="tab_t tab_line1" align="center">金额</td>
				<td class="tab_t tab_line1" align="center">财务状态</td>
				<td class="tab_t tab_line1" align="center" title="订单审核是指财务根据到账情况审核订单价格信息">订单审核</td>
				<td class="tab_t tab_line1">财务备注</td>
				<td class="tab_t tab_line1">其他备注</td>
				</tr>
				<?php
				$AccountingAudited = false;	//财务审核状态（订单必须通过财务全部审核过后才能发参团凭证）
				if($payment_history_rows){
					$AccountingAudited = true;
					$_pied = 0;
					foreach((array)$payment_history_rows as $rows => $value){
						$_pied += $value['orders_value'];
						$_class = '';
						if($value['orders_value']<0) $_class = 'col_red_b';
						if($value['audited']!="1") $AccountingAudited = false;
				?>
				<tr>
				<td class="tab_line1 p_l1"><?= $value['add_date'];?></td>
				<td class="tab_line1 p_l1"><?= tep_get_admin_customer_name($value['admin_id']);?></td>
				<td class="tab_line1 p_l1"><?= $value['payment_method'];?></td>
				<td class="tab_line1 p_l1"><?= nl2br($value['comment']);?></td>
				<td class="tab_line1 p_l1" align="right"><span class="<?php echo $_class?>">$<?= $value['orders_value'];?></span></td>
				<td class="tab_line1 p_l1" align="center" >
				<?php echo (($value['has_checked']=="1") ? '<b style="color:#009900;">'.tep_get_job_number_from_admin_id($value['checked_admin_id']).'已确认</b>':'<span style="color:#FF0000;">未确认</span>');?>
				</td>
				<td class="tab_line1 p_l1" align="center" >
				<?php echo (($value['audited']=="1") ? '<b style="color:#009900;">'.tep_get_job_number_from_admin_id($value['audited_admin_id']).'已审核</b>':'<span style="color:#FF0000;">未审核</span>');?>
				</td>
				<td class="tab_line1 p_l1">
				<?php
					$last_remark = $account_remark->show_history($value['orders_payment_history_id'],true);
				?>
				<div id="div_remark_<?php echo $value['orders_payment_history_id'];?>">
				<?php
				if(is_array($last_remark)) {
					echo tep_db_output($last_remark[0]['remark']);
				}
				?>				
				</div>
				<?php 
				if($access_order_cost == 'true'){ 
					echo tep_draw_textarea_field('', 'virtual', '20', '2', '', 'id="input_remark_'.$value['orders_payment_history_id'].'"  style="display:none;"');
				?>					
					<a href="javascript:void(0)" onClick="add_account_remark(this,<?php echo $value['orders_payment_history_id'];?>)"/>添加</a>
				<?php };
				if(is_array($last_remark)) {
				?>
				[<a href="javascript:void(0)" onClick="show_account_remark(this,<?php echo $value['orders_payment_history_id'];?>)"/>more..</a>]
				<?php
				}
				?>				
				</td>
				<td class="tab_line1 p_l1">
				<?php
				echo $value['comment_flights'] ? '<div>机票：'.tep_db_output($value['comment_flights']).'</div>' : '';
				echo $value['comment_individuation'] ? '<div>订制团：'.tep_db_output($value['comment_individuation']).'</div>' : '';
				echo $value['comment_other'] ? '<div>团款：'.tep_db_output($value['comment_other']).'</div>' : '';
				?>
				</td>
				</tr>
				<?php
					}
				?>
				<tr>
				<td class="tab_line1 p_l1">&nbsp;</td>
				<td class="tab_line1 p_l1">&nbsp;</td>
				<td class="tab_line1 p_l1">&nbsp;</td>
				<td class="tab_line1 p_l1" align="right"><b>合计：</b></td>
				<td class="tab_line1 p_l1" align="right"><b>$<?php echo $_pied;?></b></td>
				<td class="tab_line1 p_l1" >&nbsp;</td>
				<td class="tab_line1 p_l1" >&nbsp;</td>				
				<td class="tab_line1 p_l1" >&nbsp;</td>				
				<td class="tab_line1 p_l1" >&nbsp;</td>				
				</tr>
				<?php
				}
				?>
				</table>
				<?php if($access_full_edit == 'true' || $access_order_cost == 'true'){?>
				<button type="button" onclick="jQuery('#add_orders_payment_history').toggle()">增加付款记录</button>
				<script type="text/javascript">
				//财务点击是否可用积分或优惠券时的动作
				jQuery(document).ready(function(e) {
					jQuery('#edit_order input[name="_disabled_coupon_point"]').click(function(){
						if(jQuery(this).attr('checked')==true){
							var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=setDisabledCouponPoint&oID='.$oID)) ?>");
							jQuery.post(url,{'disabled_coupon_point':jQuery(this).val(), 'orders_id':<?= $oID;?> },function(text){
									if(text=='ok'){
										alert("是否可用积分或优惠券设置成功！");
									}
								},'text');
						}
					});
				});
				</script>
				<?php }?>
				是否可用积分或优惠券：<label><input id="disabled_coupon_point0" name="_disabled_coupon_point" type="radio" value="0" <?= $order->info['disabled_coupon_point']=="1" ? '':'checked="checked"';?> />可用</label> <label><input id="disabled_coupon_point1" name="_disabled_coupon_point" type="radio" value="1"  <?= $order->info['disabled_coupon_point']=="1" ? 'checked="checked"' : '';?> />不可再用</label>
				<table id="add_orders_payment_history" class="bor_tab table_td_p" border="0" cellspacing="0" cellpadding="0" style="display:none;" >
				<tr>
				<td class="tab_t tab_line1">付款方式</td>
				<td class="tab_t tab_line1">备注</td>
				<td class="tab_t tab_line1">金额</td>
				</tr>
				<tr>
				<td class="tab_line1 p_l1" valign="top"><?php echo tep_draw_pull_down_menu('_payment_method', $Optimized_orders_pay_method, $order->info['payment_method'], 'style="width:250px" class="order_option" ');?></td>
				<td class="tab_line1 p_l1" valign="top"><?php echo tep_draw_textarea_field('_comment','wrap',50,5);?></td>
				<td class="tab_line1 p_l1" valign="top">$<?php echo tep_draw_input_num_en_field('_orders_value');?><br /><button type="button" onclick="submit_payment_history(this);">确定增加</button></td>
				</tr>
				</table>
				
				</td>
			  </tr>
			<script type="text/javascript">
			//提交新的付款记录
			function submit_payment_history(disabled_obj){
				var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=submit_payment_history')) ?>")+"&oID=<?php echo $oID;?>";
				var form_id = "edit_order";
				disabled_obj.disabled = true;
				ajax_post_submit(url,form_id);
				return false;
			}
			</script>
			<?php
			//客户付款记录}
			//允许每次最小支付金额 start {
			$_disabled = ' disabled ';
			if($can_edit_allow_pay_min_money === true){
				$_disabled = '';
			}
			?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td align="left">
				  <span class="col_b1">允许每次最小支付金额：$<?php echo tep_draw_input_num_en_field('allow_pay_min_money', $order->info['allow_pay_min_money'], 'title="如果不设最小支付金额就保持为0.00即可" class="order_textbox" '.$_disabled);?> </span>
				  <span class="col_b1">允许支付最小金额的有效期：<?php echo tep_draw_input_num_en_field('allow_pay_min_money_deadline', $order->info['allow_pay_min_money_deadline'], ' class="order_textbox" title="日期格式必须是YYYY-MM-DD HH:II:SS，洛杉矶时间。如：2012-12-31 08:55:39" '.$_disabled);?> </span><button type="button" style="padding:6px" onclick="update_allow_pay_min_money(<?php echo (int)$oID;?>)" <?php echo $_disabled;?>> 确定 </button>
				</td>
			  </tr>
			 <script type="text/javascript">
			 function update_allow_pay_min_money(oID){
				var _from = document.getElementById('edit_order');
				var _allow_pay_min_money = _from.elements['allow_pay_min_money'].value;
				var _allow_pay_min_money_deadline = _from.elements['allow_pay_min_money_deadline'].value;
				var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=update_allow_pay_min_money')) ?>"+"&oID=" + oID);
				jQuery.post(url,{'action':"update_allow_pay_min_money", 'ajax':"true", 'oID':oID, 'allow_pay_min_money' : _allow_pay_min_money, 'allow_pay_min_money_deadline' : _allow_pay_min_money_deadline },function(text){
					if(text=="OK"){
						alert("恭喜：最小金额更新成功！");
						window.location = "<?= $reload_url?>";
					}else{
						alert("警告：最小金额更新失败！");
					}
				},'text');
			 }
			 </script>
			 <?php
			 //允许每次最小支付金额 end }
			 ?> 
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  
			<?php
			//积分赠送 start {
			if($can_edit_orders_points == true){
				$orders_points_sql = tep_db_query('SELECT points_pending FROM `customers_points_pending` WHERE orders_id = "'.(int)$oID.'" AND points_type="SP" and points_comment="TEXT_DEFAULT_COMMENT"');
				$orders_points = tep_db_fetch_array($orders_points_sql);
				$points_pending = round(floatval($orders_points['points_pending']), 0);
			?>
			  <tr>
				<td align="left">
				  <a id="show_points"></a><span class="col_b1">积分赠送</span>
				</td>
			  </tr>
			  <tr>
				<td>
				本订单赠送积分：<?php echo tep_draw_input_num_en_field('points_pending', $points_pending, ' id="points_pending" class="order_textbox" ');?> <button type="button" style="padding:6px" onclick="update_points_pending(<?php echo (int)$oID;?>)"> 确定 </button>
				</td>
			  </tr>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			 <script type="text/javascript">
			 function update_points_pending(oID){
			 	if(confirm('请注意！\n\n本次积分修改过后，后续积分都需要手动进行修改！')){
			 		var points_pending = document.getElementById('points_pending').value;
					var url = "<?= tep_href_link_noseo(FILENAME_EDIT_ORDERS, 'action=points_pending_confirmed') ?>"+"&points_pending=" + points_pending +"&oID=" + oID;
					$.post(url, "", function(html) {
						if(html=="OK"){
							alert("<?= db_to_html("更新成功！") ?>"+points_pending);
						}else{
							alert("<?= db_to_html("此订单无法更新积分，更新失败！") ?>");
						}
					});
				}else{return false;}
			 }
			 </script>
			  <?php
			  }
			  //积分赠送 end }
			  ?>
			  
			  <tr>
				<td align="left">
				  <a id="show_eticket"></a><span class="col_b1">Reservation List</span>
				  <?php
				  //检查用户是否收到电子参团凭证
				  $eticket_sql = tep_db_query('SELECT email_track_id,email_track_date,email_address FROM `email_track`  WHERE key_field="orders_id" AND key_id="' . (int) $oID . '" AND email_type="eTicket"');
				  while ($eticket_row = tep_db_fetch_array($eticket_sql)) {
				  	if ((int) $eticket_row['email_track_id']) {
				  		echo '<b style="color: #00CC00; margin-left:100px;">已收到eTicket邮件！</b> <span style="color: #CCCCCC;">' . $eticket_row['email_track_date'] . " " . ($can_see_customers_email_full_address === true ? $eticket_row['email_address'] : '') . '</span>';
				  	}
				  }
				  ?>
				</td>
			  </tr>
			
			  <!-- Begin Products Listing Block -->
			  <tr>
				<td><table border="0" width="100%" cellspacing="0" cellpadding="2" >
					<tr class="tab_t tab_line1" >
					  <?php /* ?><td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td><?php */ ?>
					  <td class="tab_t tab_line1"><?php echo 'Itinerary Information'; ?></td>
					  <td class="tab_t tab_line1" nowrap="nowrap" ><?php echo 'Guest Name'; ?></td>
					  <td class="tab_t tab_line1" nowrap="nowrap" width="150" ><?php echo 'Lodging'; ?></td>
					  <td class="tab_t tab_line1" style="padding-right:5px; ">
					  <?php
					  if (sizeof($order->products) == "1") {
					  	$display_providers_column = get_if_display_provider_status_history($order->products[0]['id'], $oID);
					  } else {
					  	$display_providers_column = 1;
					  }
					  if ($display_providers_column == "0") {
					  	echo '&nbsp;';
					  } else {
					  	echo '与地接交流记录';
					  }
					  ?>
					  </td>
					 <!-- <?php /* ?><td class="tab_t tab_line1" align="center"><?php //echo TABLE_HEADING_TAX; ?></td><?php */ ?>-->
					  <!--<?php /* ?>
					  <td class="tab_t tab_line1"><?php echo TABLE_HEADING_TOUR_ATTRIBUTES; ?></td>
					  <td class="tab_t tab_line1" align="right"><?php echo TABLE_HEADING_TOUR_PRICE; ?></td>
					  <td class="tab_t tab_line1" align="right"><?php echo TABLE_HEADING_TOTAL_PRICE; ?></td>
					  <?php */ ?>-->
					  <td class="tab_t tab_line1" ><?php echo TABLE_HEADING_PRICE; ?></td>
					  <td class="tab_t" align="left">Action</td>
					</tr>					
					<!-- 订单包含的产品 列表开始 -->
					<?php
					$print_heading_featured_once = 0;
					$product_model_select_array = array();
					$product_model_select_array[] = array('id' => '', 'text' => '-- Select Tour Code --');
					$product_model_sel_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and  pd.language_id = '" . (int) $languages_id . "' and p.agency_id !='" . GLOBUS_AGENCY_ID . "' and p.products_model!='' order by p.products_model ");
					while ($product_model_sel_row = tep_db_fetch_array($product_model_sel_sql)) {
						$product_model_select_array[] = array('id' => $product_model_sel_row['products_id'], 'text' => $product_model_sel_row['products_model'] . ' [' . $product_model_sel_row['products_name'] . ']');
					}

					$diff_color_count = 0;
					$cancellation_history_bottom = array();
					$recent_date = "2999-12-31 23:59:59";
					$new_ot_subtotal = 0;
					for ($i = 0; $i < sizeof($order->products); $i++) {
						//howard added get js code for Recent date of departure start
						$only_departure_date = substr($order->products[$i]['products_departure_date'], 0, 19);
						if ($only_departure_date < $recent_date) {
							$recent_date = $only_departure_date;
						}
						//howard added get js code for Recent date of departure end

						$display_providers_comment = get_if_display_provider_status_history($order->products[$i]['id'], $oID);
						$products_guestgender_array = array();
						$products_guestgender_array[] = array('id' => MALE, 'text' => MALE);
						$products_guestgender_array[] = array('id' => FEMALE, 'text' => FEMALE);
						$is_gift_certificate_tour = tep_is_gift_certificate_product((int) $order->products[$i]['id']);
						if (!isset($_SESSION['row_color_' . $order->products[$i]['model'] . $oID])) {
							$_SESSION['row_color_' . $order->products[$i]['model'] . $oID] = tep_get_rand_color($diff_color_count);
							$diff_color_count++;
						}
						$orders_products_id = $order->products[$i]['orders_products_id'];
						//$bgcolor = $_SESSION['row_color_'.$order->products[$i]['model'].$oID];
						if ($i % 2 == 0) {
							$bgcolor = '#FAFAFA';
						} else {
							$bgcolor = '#FFFFFF';
						}
						//$RowStyle = "tab_line1 p_t";
						//$RowStyle_price = "tab_line1";
						$RowStyle = "p_t";
						$RowStyle_price = "";
						$diy_section_sort_order = $order->products[$i]['is_diy_tours_book'];
						if ($diy_section_sort_order == 2 && $print_heading_featured_once < 1) {
							echo $heading_diy_section = '<tr><td class="tab_t tab_line1">Featured Deals</td></tr>';
							$print_heading_featured_once++;
						}
						//echo $order->products[$i]['model'];
						if($order->products[$i]['is_hotel']) $borderColor="blue";
						else if($order->products[$i]['is_transfer']) $borderColor="green";
						else  $borderColor="gray";
						echo '	  <tr><td colspan="6" style="border-top:3px solid '.$borderColor.'"></td></tr><tr bgcolor="' . $bgcolor . '" title="'.($order->products[$i]['is_step3'] ? '订单生成后才添加的产品。添加工号：'.tep_get_admin_customer_name($order->products[$i]['step3_admin_id']).'。添加日期：'.$order->products[$i]['add_date']:'').'" >' . "\n";
						// '		<td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' readonly size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" .
						if ($is_gift_certificate_tour == true) {
							$product_name = tep_draw_hidden_field("update_products[$orders_products_id][id]", $order->products[$i]['id']);
							$product_name.='<nobr>' . $order->products[$i]['name'] . "</nobr><br /><br /> <b>" . TEXT_GC_CODE . "</b> " . $order->products[$i]['gc_code'];
							if ($order->products[$i]['gc_status'] == "0") {
								$product_name.='<br /><a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'action=activate&gc_code=' . $order->products[$i]['gc_code'] . '&' . tep_get_all_get_params(array('action', 'step', 'fudate'))) . '">' . tep_image_button('button_activate.gif', IMAGE_ACTIVATE) . '</a> ' . TEXT_ACTION_WILL_SEND_MAIL;
							} else {
								$product_name.='<br />Active';
							}
						} else {
							$product_name = '<a href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $order->products[$i]['id']) . '" class="col_a1" target="_blank">' . preg_replace("/\*\*.+\*\*$/","",$order->products[$i]['name']) . '</a>';
							$product_name .= tep_draw_hidden_field("update_products[$orders_products_id][id]", $order->products[$i]['id']);
						}
						//如果是OP的话得给一个按钮能让OP修改产品名称(此修改是通过ajax提交，不影响其它表单数据！) Howard added by 2013-07-15
						if($can_edit_orders_products_name === true){
							$product_name .= tep_draw_input_field("orders_products_name[$orders_products_id]", $order->products[$i]['name'],'title="OP可以修改产品名称" size="60" class="order_textbox" ').'<button onclick="update_orders_products_name('.$orders_products_id.')" style="padding:6px" type="button"> 确定 </button>';
						}
						//以下的数量只能为1，其它数字都会做成总计不对的情况
						echo '		<td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][qty]' readonly size='2' type='hidden' value='1'><input type='hidden' name='update_products[$orders_products_id][previouse_id]' size='25' value='" . $order->products[$i]['id'] . "'>";

						//Howard added 取得父团号信息 start{
						$parent_model_html = "";
						if((int)$order->products[$i]['parent_orders_products_id']){
							$parent_model_html .= '<h2 class="col_red_b" >此团是 <a href="javascript:void(0)" onclick="jQuery(\'html,body\').animate({ scrollTop: (jQuery(\'#tours_model_'.$order->products[$i]['parent_orders_products_id'].'\').offset().top - 20) });">'.tep_get_orders_products_model($order->products[$i]['parent_orders_products_id']).'</a> 的子团！</h2>';
						}
						//Howard added 取得父团号信息 end}
						//howard added for sub-products and sub-PROVIDER_TOUR_CODE start {
						//取得子团号及子供应商信息
						$sub_products_sql = tep_db_query('SELECT products_model_sub, products_model_sub_notes, provider_tour_code_sub FROM `products` WHERE products_id ="' . $order->products[$i]['id'] . '" ');
						$sub_products_row = tep_db_fetch_array($sub_products_sql);
						$sub_provider_html = '';
						if (tep_not_null($sub_products_row['provider_tour_code_sub'])) {
							$sub_provider_html = '<div style="color:#999999">' . str_replace(';', '<br/>', $sub_products_row['provider_tour_code_sub']) . '</div>';
						}
						$sub_model_html = '';
						$sub_model = array();
						$hidden_big_button = false;
						if (tep_not_null($sub_products_row['products_model_sub'])) {
							$sub_model = explode(';', $sub_products_row['products_model_sub']);
							foreach ($sub_model as $key => $val) {
								$sub_products_id = tep_get_products_id_from_model($val);
								$sub_agency_id = tep_get_agency_id_from_model($val);
								$sub_model_html .= '<div style="color:#999999">' . $val . '<a target="_blank" href="' . tep_href_link(FILENAME_ORDERS_FAX, tep_get_all_get_params(array('oID', 'action', 'i', 'products_id', 'update_fax')) . 'oID=' . $oID . '&sub_products_id=' . $sub_products_id . '&sub_agency_id=' . $sub_agency_id . '&products_id=' . $order->products[$i]['id'] . '&action=fax_show&i=' . $i) . '" title="fax">' . tep_image_button('fax_min.gif', '') . '</a>&nbsp;<a target="_blank" href="' . tep_href_link(FILENAME_ORDERS_FAX, tep_get_all_get_params(array('oID', 'action', 'i', 'products_id', 'update_fax')) . 'oID=' . $oID . '&sub_products_id=' . $sub_products_id . '&sub_agency_id=' . $sub_agency_id . '&products_id=' . $order->products[$i]['id'] . '&action=fax_show&update_fax=true&i=' . $i) . '" title="UpDate Fax">' . tep_image_button('up_fax_min.gif', '') . '</a></div>';
								$hidden_big_button = true;
							}
							//自动分单按钮
							if(!tep_get_orders_products_sub($order->products[$i]['orders_products_id'])){
								$sub_model_html .= '<div style="padding:2px; margin-top:8px;"><button type="button" onclick="location=\''.tep_href_link("edit_orders.php","action=tep_split_orders_products&orders_products_id=".$order->products[$i]['orders_products_id'].'&oID='.$oID).'\'" style="width:120px; height:30px; font-size:14px;"><b>自动分单</b></button></div>';
							}else{
								$sub_model_html .= '<div style="padding:2px; margin-top:8px;"><h2 class="col_red_b">此行程是组合的大团，具体的子团请看后面的行程列表！</h2></div>';
							}
						}
						if (tep_not_null($sub_products_row['products_model_sub_notes'])) {
							$sub_model_html .= '<div style="background-color:#fddffb;padding:2px; margin-top:8px;">' . db_to_html($sub_products_row['products_model_sub_notes']) . '</div>';
						}
						//howard added for sub-products and sub-PROVIDER_TOUR_CODE end }
						// lwkai moved start by 2013-06-03
						$products_final_price = $order->products[$i]['final_price'] * $order->products[$i]['qty'];
						$products_final_price += $os_final_price;
						$new_ot_subtotal += $products_final_price;
						
						//echo $currencies->format($products_final_price, true, $order->info['currency'], $order->info['currency_value']);*/
						// lwkai moved end by 2013-06-03
						echo '<table width="100%" >
							<tr>
								<td width="50%" nowrap class="p_l1">
									<span class="col_h"><b class="col_red_b">产品'.($i+1).'</b>';
						
						echo '<br>Tour Code:</span>
								</td>
								<td width="50%" class="order_default">';
						if ($products_final_price <= 0&&!(int)$order->products[$i]['parent_orders_products_id']) {
							echo '<span style="color:red">已取消，请勿发送！</span><br/>';
						}
						
						$CellphoneUpdatedHistory_html = '';
						if($gCellphoneUpdatedHistory){	//游客手机有更新历史
							$CellphoneUpdatedHistory_html = '<a href="javascript:void(0);" class="thumbnail"><strong><nobr><u>[历史]<h1 style="color:#FF0000" id="cellphoneUpdatedHistory_'.$i.'"></h1></u></nobr></strong><span>';
							$CellphoneUpdatedHistory_html .= '
							<table id="TcellphoneUpdatedHistory_'.$order->products[$i]['orders_products_id'].'" width="100%" border="0" class="dataTableContent">
								<tbody>
								<tr>
								<th align="left" scope="col" nowrap>号码</th>
								<th align="left" scope="col" nowrap class="col_h">旧号码</th>
								<th align="left" scope="col" nowrap class="col_h">添加人</th>
								<th align="left" scope="col" nowrap class="col_h">添加日期</th>
								</tr>
								';
							foreach($gCellphoneUpdatedHistory as $num => $_val){
								$_confirm_msn = ($_val['checked_id'] ? '[已由'.tep_get_admin_customer_name($_val['checked_id']).'审核]':(($can_confirm_cellphone_updated_history === true && $num==0) ? '<button type="button" onclick="confirm_cellphone_number('.$_val['orders_customers_cellphone_history_id'].')">审核</button><script>jQuery("#cellphoneUpdatedHistory_'.$i.'").text("主管OP注意"); jQuery(document).ready(function(){ jQuery("input[name=\'btnProvidersConfirmation_'.$order->products[$i]['orders_products_id'].'\']").attr("disabled",true).attr("title","主管OP必须审核新的游客手机后才能发地接！");}); </script>' :'' ));
								$CellphoneUpdatedHistory_html .= '<tr>
									<td align="left" nowrap id="dl_'.(int)$_val['orders_customers_cellphone_history_id'].'">'.tep_db_output($_val['new_number']).'</td>
									<td align="left" nowrap class="col_h">'.tep_db_output($_val['old_number']).'</td>
									<td align="left" nowrap class="col_h">'.tep_get_admin_customer_name($_val['admin_id']).'</td>
									<td align="left" nowrap class="col_h">'.date('Y-m-d H:i:s',strtotime($_val['add_date'])). ' ' .$_confirm_msn.'</td>
								</tr>';
							}	
							$CellphoneUpdatedHistory_html .= '</table>';
							$CellphoneUpdatedHistory_html .= '</span>';
						}
						
						echo '<a target="_blank" href="'.tep_href_link(FILENAME_CATEGORIES, 'cPath='.tep_get_products_catagory_id($order->products[$i]['id']).'&pID='.$order->products[$i]['id'].'&action=new_product&tabedit=eticket').'" class="order_default"><b id="tours_model_'.$order->products[$i]['orders_products_id'].'">'.$order->products[$i]['model'].'</b></a>
									<br/>' . $parent_model_html. $sub_model_html . '
								</td>
							</tr>
							<tr>
							<td nowrap class="p_l1"><span class="col_h">当日游客手机:</span>'.$CellphoneUpdatedHistory_html.'</td>
							<td class="order_default">'.$gUpdateCustomersCellphone.'</td>
							</tr>
							<tr>
								<td valign="top" class="p_l1" nowrap><span class="col_h">Tour Name:</span></td>
								<td class="order_default">' . $product_name . '</td>
							</tr>';
						$tour_name_by_provider = tep_get_products_provider_name($order->products[$i]['id'], $languages_id);
						if ($tour_name_by_provider != '') {
							$tour_name_by_provider =  preg_replace("/\*\*.+\*\*$/","",$tour_name_by_provider);
							echo '<tr><td valign="top" class="p_l1"><span class="col_h">' . TABLE_HEADING_PROVIDER_TOUR_NAME . '</span></td><td class="order_default">(AKA: ' . $tour_name_by_provider . ')</td></tr>';
						}
						if ($is_gift_certificate_tour == false) {
							echo '<tr><td class="order_default p_l1"><span class="col_h">' . TABLE_HEADING_PROVIDER_TOUR_CODE . '</span></td><td class="order_default"><a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($order->products[$i]['id']) . '&pID=' . $order->products[$i]['id'] . '&action=new_product&tabedit=eticket') . '"><b>' . tep_get_provider_tourcode($order->products[$i]['id']) . '</b></a><br/>' . $sub_provider_html . '</td></tr>';
						}

						$forBeforeString = "";
						if((int)$order->products[$i]['no_sel_date_for_group_buy']){ $forBeforeString = ' <b style="color: #F00">之前</b>'; }

						//echo '<tr><td class="p_l1" valign="top"><span class="col_h">Date of Departure:</span></td><td class="order_default">' ;

						echo '<tr><td class="p_l1" valign="top"><span class="col_h">'.($order->products[$i]['is_hotel'] == 1 ? TXT_ETICKET_HOTEL_CHECK_IN_DATE : '出团日期:').'</span></td><td class="order_default">' .($is_gift_certificate_tour==false? tep_date_short($order->products[$i]['products_departure_date']).'&nbsp;'.$order->products[$i]['products_departure_time'] : '&nbsp;') . '</td></tr>';
						
						if($order->products[$i]['is_hotel'] == 1){
							$totalNight='';
							if(check_date($order->products[$i]['products_departure_date']) && check_date($order->products[$i]['hotel_checkout_date'])){
								$totalNight ='<br><span>'.db_to_html('总共：'.date1SubDate2($order->products[$i]['hotel_checkout_date'],$order->products[$i]['products_departure_date']).'晚').'</span>';
							}
							echo '<tr><td class="p_l1" valign="top"><span class="col_h">'.TXT_ETICKET_HOTEL_CHECK_OUT_DATE.'</span></td><td class="order_default">'.tep_date_short($order->products[$i]['hotel_checkout_date']).$totalNight.'</td></tr>';
						
						}else{
							
							echo '<tr><td class="p_l1" valign="top"><span class="col_h">结束日期:</span></td><td class="order_default">[' .tep_date_short(tep_get_products_end_date((int)$order->products[$i]['id'],$order->products[$i]['products_departure_date'])) . ']</td></tr>';
						}

						//取得上车时间地址历史数据 {
						$departure_location_history_html = '';
						$departure_location_history = tep_get_departure_location_history($order->products[$i]['orders_products_id']);
						if($departure_location_history != false){
							$departure_location_history_html .= '<a href="javascript:void(0);" class="thumbnail"><strong><nobr><u>[历史]<h1 style="color:#FF0000" id="departureLocationH_'.$i.'"></h1></u></nobr></strong><span>';
							$departure_location_history_html .= '
							<table id="departureLocationHistory_'.$order->products[$i]['orders_products_id'].'" width="100%" border="0" class="dataTableContent">
								<tbody>
								<tr>
								<th align="left" scope="col" nowrap>时间地址</th>
								<th align="left" scope="col" nowrap class="col_h">添加人</th>
								<th align="left" scope="col" nowrap class="col_h">添加日期</th>
								</tr>
								';
							$_loop = 0;
							foreach($departure_location_history as $_key => $_val){
								$_loop++;
								$_confirm_msn = '';
								if($_loop==1 && $can_confirm_departure_location === true){	//只显示最新的那条地址的审核按钮
									$_confirm_msn = '<button type="button" style="padding:6px" onclick="confirm_departure_location('.(int)$_val['history_id'].', this, '.$i.')">审核</button>';
									$_confirm_msn.= '<script>jQuery("#departureLocationH_'.$i.'").text("主管OP注意"); jQuery(document).ready(function(){ jQuery("input[name=\'btnProvidersConfirmation_'.$order->products[$i]['orders_products_id'].'\']").attr("disabled",true).attr("title","主管OP必须审核新时间地址后才能发地接！");}); </script>';
								}
								if($_val['has_confirmed']=='1'){ $_confirm_msn = '[已由'.tep_get_admin_customer_name($_val['confirmed_admin_id']).'审核]'; }
								$departure_location_history_html .= '
								<tr>
									<td align="left" nowrap id="dl_'.(int)$_val['history_id'].'">'.tep_db_output($_val['departure_location']).'</td>
									<td align="left" nowrap class="col_h">'.tep_get_admin_customer_name($_val['updated_by']).'</td>
									<td align="left" nowrap class="col_h">'.date('Y-m-d H:i:s',strtotime($_val['added_time'])). ' ' .$_confirm_msn.'</td>
								</tr>
								';
							}
							$departure_location_history_html .= '</tbody></table>';
							$departure_location_history_html .= '</span></a>';
						}
						//取得上车时间地址历史数据 }
						if($order->products[$i]['is_hotel'] == 3){
							list($pick_uplocation, $return_location) = explode('|=|',$order->products[$i]['products_departure_location']);
							list($return_final_location,$return_date_two) = explode('=|=',$return_location);
							echo '<tr><td class="p_l1" valign="top"><span class="col_h">Departure Location:</span>'.$departure_location_history_html.'</td><td class="order_default">'. ($is_gift_certificate_tour==false?$pick_uplocation : '&nbsp;').'</td></tr><tr><td class="p_l1" valign="top"><span class="col_h">Return Date:</span></td><td class="order_default">'.date('m/d/Y',strtotime(substr($return_date_two,0,10))).'</td></tr><tr><td class="p_l1" valign="top"><span class="col_h">Return Location:</span></td><td class="order_default">'. ($is_gift_certificate_tour==false?str_replace('||',' ',$return_final_location) : '&nbsp;').$totalNight.'</td></tr>';
						}else{
							echo '<tr><td class="p_l1" valign="top"><span class="col_h">上车地址：</span>'.$departure_location_history_html.'</td><td class="order_default" id="departure_location_'.$order->products[$i]['orders_products_id'].'">'. ($is_gift_certificate_tour==false?$order->products[$i]['products_departure_location'] : '&nbsp;').'</td></tr>';
						}
						
						//出发城市 start{
						$_city_title = "出发城市：";
						if($order->products[$i]['is_hotel'] == 1){
							$_city_title = "所在城市：";
						}
						echo '<tr><td class="p_l1" valign="top"><span class="col_h">'.$_city_title.'</span></td><td class="order_default">'.tep_get_product_departure_city($order->products[$i]['id']) .'</td></tr>';
						//出发城市 end}
						
						//howard added 单人配房提示 start
						$single_pu_tags = get_single_pu_tags($oID, $order->products[$i]['id']);
						if (tep_not_null($single_pu_tags)) {
							echo '<tr><td class="p_l1" valign="top"><span class="col_h" style="color:green">单人配房：</span></td><td class="order_default">' . $single_pu_tags . '</td></tr>';
						}
						//howard added 单人配房提示 end

						//tom added 日本团添加自带磁带start
						if (in_array($order->products[$i]['model'], $japan_array)) {
							echo '<tr><td class="p_l1" valign="top"><span class="col_h" style="color:green">带中文磁带：</span></td><td class="order_default"><b style="color:green">YES</b></td></tr>';
						}
						//tom added 日本团添加自带磁带end

						$striposproduct_name = $product_name;
						tep_session_register('striposproduct_name');
						$_SESSION['striposproduct_name'] = $product_name; //SESSION ['striposproduct_name'] jason 判断有没有机场接机
						if ($is_gift_certificate_tour == false) {
							//amit added for attribute display start
							if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
								for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
									if(trim($order->products[$i]['attributes'][$j]['option']) == '') continue;
									if ($j == 0) {
										echo '<tr><td colspan="2">&nbsp;</td></tr>';
									}
									echo '<tr><td class="p_l1" valign="top"><span class="col_h">' . $order->products[$i]['attributes'][$j]['option'] . ': </span></td><td class="order_default" valign="top">' . $order->products[$i]['attributes'][$j]['value'];
									if ($order->products[$i]['attributes'][$j]['price'] != '0')
									echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
									if (($access_full_edit == 'true' || $access_order_cost == 'true') && $order->products[$i]['attributes'][$j]['price_cost'] != '0') {
										echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price_cost'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
									}
									echo '</td></tr>';
								}
							}
							//amit added for attribute display end

							$finalrommstring = str_replace('No', 'Number', $order->products[$i]['products_room_info']);
							$finalrommstring = str_replace('no', 'Number', $finalrommstring);
							$finalrommstring = str_replace('#', 'Number', $finalrommstring);
							$finalrommstring = str_replace('room', 'Room', $finalrommstring);
							$finalrommstring = str_replace('childs', 'children', $finalrommstring);
							if (eregi('- Total :', stripslashes2($finalrommstring))) {
								$req_roomarray = explode('- Total :', stripslashes2($finalrommstring));
							} else if (eregi('Total :', stripslashes2($finalrommstring))) {
								$req_roomarray = explode('Total :', stripslashes2($finalrommstring));
							} else if (eregi('Total :-', stripslashes2($finalrommstring))) {
								$req_roomarray = explode('Total :-', stripslashes2($finalrommstring));
							} else if (eregi('- Total of Room', stripslashes2($finalrommstring))) {
								$req_roomarray[0] = preg_replace('/-[[:space:]]Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
							} else if (eregi('Total of Room', stripslashes2($finalrommstring))) {
								$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
							} else {
								$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
							}

							//howard added start
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM1, '', stripslashes($finalrommstring));
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM2, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM3, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM4, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM5, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM6, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM7, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM8, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM9, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM10, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM11, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM12, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM13, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM14, '', $req_roomarray[0]);
							$req_roomarray[0] = str_replace('<br>' . TEXT_TOTLE_OF_ROOM15, '', $req_roomarray[0]);
							$req_roomarray[0] = preg_replace('/[[:space:]](\$|\&#65509;)[0-9\,]*.[0-9]+/', '', $req_roomarray[0]);
							//howard added end

						}
						$disp_flight_info = get_flignt_info_popup($order->products[$i]['orders_products_id']);
						if ($disp_flight_info != '') {
							echo '<tr><td class="order_default" colspan="2">' . $disp_flight_info . '</td></tr>';
						}
						if (tep_not_null($order->info['cancellation_history'])) {
							$disp_cancellation_history = get_cancellation_history_popup($oID, $order->products[$i]['id'], $order->products[$i]['orders_products_id']);
							if ($disp_cancellation_history != '') {
								echo '<tr><td colspan="2"><input name="update_products[' . $order->products[$i]['orders_products_id'] . '][is_cancel_history_exists]" type="hidden" value="yes">' . $disp_cancellation_history . '</td></tr>';
							} else {
								echo '<tr><td colspan="2"><input name="update_products[' . $order->products[$i]['orders_products_id'] . '][is_cancel_history_exists]" type="hidden" value="no"></td></tr>';
							}
						} else {
							echo '<tr><td colspan="2"><input name="update_products[' . $order->products[$i]['orders_products_id'] . '][is_cancel_history_exists]" type="hidden" value="no"></td></tr>';
						}
						$sql_show_history_link = "SELECT op_order_products_ids from " . ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY . " where op_order_products_ids=" . (int) $orders_products_id . "";
						$runsql_check_history = tep_db_query($sql_show_history_link);
						if (tep_db_num_rows($runsql_check_history) > 0) {
							$show_history_changed = show_histories_action($orders_products_id, $i, $can_confirm_order_products_departure_histoty);
							echo '<tr><td colspan="2">' . $show_history_changed . '</td></tr>';
						}
						if(tep_check_priority_mail_is_active($order->products[$i]['id']) == 1){
							$priority_mail_ticket_needed_date = tep_get_cart_get_extra_field_value('priority_mail_ticket_needed_date', $order->products[$i]['extra_values']);
							if(tep_not_null($priority_mail_ticket_needed_date)){
								echo '<tr><td class="p_l1" valign="top"><span class="col_h">'.TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE.'</span></td><td class="order_default">'.tep_get_date_disp($priority_mail_ticket_needed_date).'</td></tr>';
							}
							$priority_mail_delivery_address = tep_get_cart_get_extra_field_value('priority_mail_delivery_address', $order->products[$i]['extra_values']);
							if(tep_not_null($priority_mail_delivery_address)){
								echo '<tr><td class="p_l1" valign="top"><span class="col_h">'.TXT_PRIORITY_MAIL_DELIVERY_ADDRESS.'</span></td><td class="order_default">'.$priority_mail_delivery_address.'</td></tr>';
							}
							$priority_mail_recipient_name = tep_get_cart_get_extra_field_value('priority_mail_recipient_name', $order->products[$i]['extra_values']);
							if(tep_not_null($priority_mail_recipient_name)){
								echo '<tr><td class="p_l1" valign="top"><span class="col_h">'.TXT_PRIORITY_MAIL_RECIPIENT_NAME.'</span></td><td class="order_default">'.$priority_mail_recipient_name.'</td></tr>';
							}
						}
						
						//供应商要注意的信息
						$note_to_agency = tep_db_get_field_value('note_to_agency', TABLE_PRODUCTS,'products_id="'.$order->products[$i]['id'].'" ');
						if(tep_not_null($note_to_agency)){
							echo '<tr><td class="p_l1" valign="top"><span class="col_h" title="供应商要注意的信息">订单备注：</span></td><td class="order_default">'.nl2br($note_to_agency).'</td></tr>';
						}
												
						echo '</table>';
						echo '		<input name="update_products['.$orders_products_id.'][tax]" type="hidden" size="3" value="' . ($is_gift_certificate_tour == false ? tep_display_tax_value($order->products[$i]['tax']) : '&nbsp;') . '"></td>' . "\n";
						$show_red_border[0] = '';
						$show_red_border[1] = '';
						$show_red_border[2] = '';
						$show_red_border[3] = '';
						$show_red_border = explode("||", $order->products[$i]['is_adjustments_needed']);
						$red_border_class = 'red_border';
						echo '<td class="p_t ' . (tep_not_null($show_red_border[0]) ? $red_border_class : '') . '" valign="top" nowrap id="red_alerts_guestname_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[0]) ? 'title="' . TITLE_GUESTNAME_RED_BOX . '"' : '') . '>';
						$orders_eticket_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_products_id=".(int)$order->products[$i]['orders_products_id']." ");
						$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
						$guestnames = explode('<::>', $orders_eticket_result['guest_name']);
						$bodyweights = explode('<::>', $orders_eticket_result['guest_body_weight']);
						$guestgenders = explode('<::>', $orders_eticket_result['guest_gender']);
						$guestheight = explode('<::>', $orders_eticket_result['guest_body_height']);
						$gender_guest_cnt = 0;
						$get_agency_info_total_array = tep_get_tour_agency_information($order->products[$i]['id']);
						if ($gender_guest_cnt == 0) {
							if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_CHECKOUT_IDS.",") || in_array($get_tour_agency_id['agency_id'],explode(',','12,48'))) {
								$gender_guest_cnt = 1;
							} else {
								//check if provider enable gender
								if ($get_agency_info_total_array['is_gender_info'] == '1') {
									$gender_guest_cnt = 1;
								}
							}
						}
						$height_guest_cnt = 0;
						if ($height_guest_cnt == 0) {
							if (preg_match("/," . (int) $order->products[$i]['id'] . ",/i", "," . TXT_ADD_EXTRA_FIELDS_HEIGHT_CHECKOUT_IDS . ",")) {
								$height_guest_cnt = 1;
							}
						}
						if ($orders_eticket_result['guest_number'] == 0 || (is_array($guestnames) && !empty($guestnames))) {
							foreach ($guestnames as $key => $val) {
								$loop = $key;
							}
						} else {
							$loop = $orders_eticket_result['guest_number'];
						}
						echo tep_draw_hidden_field('total_guest_' . (int) $order->products[$i]['orders_products_id'], $loop);
						$guest_total_adults = 0;
						$guest_total_children = 0;
						
						$_input_readonly = ' readonly="readonly" ';	//客户资料默认只读
						if($can_edit_guest_room == true){
							$_input_readonly = '';
						}

						
						if (tep_get_product_type_of_product_id($orders_eticket_result['products_id']) == 2) {
							for ($noofguest = 1; $noofguest <= $loop; $noofguest++) {
								echo '<br><b>' . $noofguest . '</b>';
								$gender_guest_cnt = 0; //amit fixed to hide gender
								/*
								if(stripslashes($guestgenders[($noofguest-1)]) != '' || $gender_guest_cnt > 0){
								$gender_guest_cnt++;
								echo tep_draw_pull_down_menu('guestgenders'.$noofguest.'_'.(int)$order->products[$i]['id'], $products_guestgender_array,stripslashes($guestgenders[($noofguest-1)]),' id=guestgenders'.$noofguest.'_'.(int)$order->products[$i]['id']);
								}
								*/
								$guest_name_incudes_child_age = explode('||', $guestnames[($noofguest - 1)]);
								if (isset($guest_name_incudes_child_age[1])) {
									$guest_total_children++;
					?>
					&nbsp;
					
					<input <?php echo $_input_readonly;?> type="text" name="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" id="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[0])); ?>" class="order_textbox" size="12" />
					Birth Date:
					<input <?php echo $_input_readonly;?> type="text" size="10" name="<?php echo 'etckchildage' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[1])); ?>" id="<?php echo 'etckchildage' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" class="order_textbox" />
					<?php if($can_edit_guest_room == true){?>
					[<a href="javascript:void(0)" style="color: #F00" title="remove guest" onClick="remove_to_garbage_boxs('<?= 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>','<?php echo 'etckchildage' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>', '<?php echo (int) $order->products[$i]['orders_products_id']; ?>', '<?php echo $noofguest; ?>'); show_div('new_guest_alert_msg_<?php echo (int) $order->products[$i]['orders_products_id']; ?>');">x</a>]
					<?php }?>
					<?php
					//show age
					$this_time = time();
					$bth_time = strtotime(stripslashes2(trim($guest_name_incudes_child_age[1])));
					$age_num = (int) (($this_time - $bth_time) / (3600 * 24 * 365));
					if ($age_num < 18) {
						echo 'Age:' . $age_num;
					}
					//}
					?>
					<?php
								} else {
									$guest_total_adults++;
					?>
					&nbsp;
					
					<input <?php echo $_input_readonly;?> type="text" name="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" id="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guestnames[($noofguest - 1)])); ?>" class="order_textbox" size="12" />
					<?php if($can_edit_guest_room == true){?>
					[<a href="javascript:void(0)" style="color: #F00" title="remove guest" onClick="remove_to_garbage_boxs('<?= 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>','', '<?php echo (int) $order->products[$i]['orders_products_id']; ?>', '<?php echo $noofguest; ?>'); show_div('new_guest_alert_msg_<?php echo (int) $order->products[$i]['orders_products_id']; ?>');">x</a>]
					<?php
						}
					}
					?>
					&nbsp;
					Weight:
					<input <?php echo $_input_readonly;?> type="text" name="<?php echo 'bodyweight' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2($bodyweights[($noofguest - 1)]); ?>" class="order_textbox" size="12" />
					<?php
							}//end for loop
						} else {
							for ($noofguest = 1; $noofguest <= $loop; $noofguest++) {
								echo '<br><b>' . $noofguest . '.</b>';
								if (stripslashes2($guestgenders[($noofguest - 1)]) != '' || $gender_guest_cnt > 0) {
									//echo '<br>'.'Gender:';
									$gender_guest_cnt++;
									/*if($can_edit_guest_room != true){
										//如果该用户不能编辑客人资料则要把权限禁止
										$_products_guestgender_array = $products_guestgender_array;
										unset($products_guestgender_array);
										foreach($_products_guestgender_array as $_option => $_val){
											$products_guestgender_array[] = array('id' => $_val['id'], 'text'=> $_val['text'], 'optgroup' => true );
										}
									}*/
									//print_r($products_guestgender_array);
									
									echo tep_draw_pull_down_menu('guestgenders' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id'], $products_guestgender_array, stripslashes2($guestgenders[($noofguest - 1)]), ' id=guestgenders' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']);
									//echo '<br>';
								}
								if (stripslashes($guestheight[($noofguest - 1)]) != '' || $height_guest_cnt > 0) {
									$height_guest_cnt++;
					?>
					Height:
					<input <?php echo $_input_readonly;?> type="text" size="10" name="<?php echo 'height' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" id="<?php echo 'height' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes($guestheight[($noofguest - 1)]); ?>" class="order_textbox" />
					<?php
								}
								$guest_name_incudes_child_age = explode('||', $guestnames[($noofguest - 1)]);
								if (isset($guest_name_incudes_child_age[1])) {
									$guest_total_children++;
									/* if($noofguest==1){
									echo '&nbsp;'.stripslashes2(trim($guest_name_incudes_child_age[0])).' Birth Date: '.stripslashes2(trim($guest_name_incudes_child_age[1]));
									?>
									<input type="hidden" name="<?php echo 'guest'.$noofguest.'_'.(int)$order->products[$i]['orders_products_id']; ?>" id="<?php echo 'guest'.$noofguest.'_'.(int)$order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[0])); ?>" class="order_textbox" size="12" /> Birth Date: <input type="hidden" size="10" name="<?php echo 'etckchildage'.$noofguest.'_'.(int)$order->products[$i]['orders_products_id']; ?>" id="<?php echo 'etckchildage'.$noofguest.'_'.(int)$order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[1])); ?>" class="order_textbox" />
									<?php
									}else{ */
					?>
					<input <?php echo $_input_readonly;?> type="text" name="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" id="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[0])); ?>" class="order_textbox" size="12" />
					Birth Date:
					<input <?php echo $_input_readonly;?> type="text" size="10" name="<?php echo 'etckchildage' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" id="<?php echo 'etckchildage' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[1])); ?>" class="order_textbox" />
					<?php if($can_edit_guest_room == true){?>
					[<a href="javascript:void(0)" style="color: #F00" title="remove guest" onClick="remove_to_garbage_boxs('<?= 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>','<?php echo 'etckchildage' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>', '<?php echo (int) $order->products[$i]['orders_products_id']; ?>', '<?php echo $noofguest; ?>'); show_div('new_guest_alert_msg_<?php echo (int) $order->products[$i]['orders_products_id']; ?>');">x</a>]
					<?php
					}
					//show age
					$this_time = time();
					$bth_time = strtotime(stripslashes2(trim($guest_name_incudes_child_age[1])));
					$age_num = (int) (($this_time - $bth_time) / (3600 * 24 * 365));
					if ($age_num < 18) {
						echo 'Age:' . $age_num;
					}
					//}
								} else {
									$guest_total_adults++;
									/* if($noofguest==1){
									echo '&nbsp;'.stripslashes2(trim($guestnames[($noofguest-1)]));
									?>
									<input type="hidden" name="<?php echo 'guest'.$noofguest.'_'.(int)$order->products[$i]['orders_products_id']; ?>" id="<?php echo 'guest'.$noofguest.'_'.(int)$order->products[$i]['id']; ?>" value="<?php echo stripslashes2(trim($guestnames[($noofguest-1)])); ?>" class="order_textbox" size="12" />
									<?php
									}else{ */
					?>
					<input <?php echo $_input_readonly;?> type="text" name="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" id="<?php echo 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>" value="<?php echo stripslashes2(trim($guestnames[($noofguest - 1)])); ?>" class="order_textbox" size="28" />
					<?php if($can_edit_guest_room == true){?>
					[<a href="javascript:void(0)" style="color: #F00" title="remove guest" onClick="remove_to_garbage_boxs('<?= 'guest' . $noofguest . '_' . (int) $order->products[$i]['orders_products_id']; ?>','', '<?php echo (int) $order->products[$i]['orders_products_id']; ?>', '<?php echo $noofguest; ?>'); show_div('new_guest_alert_msg_<?php echo (int) $order->products[$i]['orders_products_id']; ?>');">x</a>]
					
					<?php
					}
					//}
								}
							}
						}

						$add_guest_a = '';	//添加客人的按钮
						$edit_room_a = '';	//编辑房间的按钮
						if($can_edit_guest_room == true){
							$add_guest_a = '<br /><br /><a href="javascript:show_div(\'new_guest_' . (int) $order->products[$i]['orders_products_id'] . '\'); show_div(\'new_guest_alert_msg_' . (int) $order->products[$i]['orders_products_id'] . '\');" class="col_a1">Add Guest</a>';
							$edit_room_a = '</br> <a href="javascript:toggel_div(\'edit_cart_product_data_' . (int) $order->products[$i]['orders_products_id'] . '\');" class="col_a1">Edit</a> ';
						}
						
						echo $add_guest_a.'<br /><input name="garbage_boxs_' . (int) $order->products[$i]['orders_products_id'] . '" id="garbage_boxs_' . (int) $order->products[$i]['orders_products_id'] . '" type="hidden" value=""><div id="new_guest_' . (int) $order->products[$i]['orders_products_id'] . '" style="display:none;"><table border="0" cellspacing="0" cellpadding="0"><tr><td height="15"></td></tr><tr>';
						if ($gender_guest_cnt > 0) {
							echo '<td height="30" align="left" class="infoBoxContent">Gender:</b>';
							echo tep_draw_pull_down_menu('new_gender_' . (int) $order->products[$i]['orders_products_id'], $products_guestgender_array, '');
							echo '</td>';
						}
						if ($height_guest_cnt > 0) {
							echo '<td height="30" align="left" class="infoBoxContent">Height:';
							echo '<input type="text" name="new_height_' . (int) $order->products[$i]['orders_products_id'] . '" size="8"></td>';
						}
						echo '<td height="30" align="left" class="infoBoxContent">Add Guest Name:<input type="text" name="new_cus_name_' . (int) $order->products[$i]['orders_products_id'] . '" size="16"></td><td class="infoBoxContent">Child Birth Date:';
					?>
					<?php echo tep_draw_input_field('birth_date_'.(int) $order->products[$i]['orders_products_id'], '', ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'d/m/y\'; GeCalendar.SetDate(this);"');?>
					<script type="text/javascript">
					
					//var dateBirth_<?php echo (int) $order->products[$i]['orders_products_id']; ?> = new ctlSpiffyCalendarBox("dateBirth_<?php echo (int) $order->products[$i]['orders_products_id']; ?>", "edit_order", "birth_date_<?php echo (int) $order->products[$i]['orders_products_id']; ?>","btnDateBirth_<?php echo (int) $order->products[$i]['orders_products_id']; ?>","",scBTNMODE_CUSTOMBLUE);
					//dateBirth_<?php echo (int) $order->products[$i]['orders_products_id']; ?>.writeControl(); dateBirth_<?php echo (int) $order->products[$i]['orders_products_id']; ?>.dateFormat="MM/dd/yyyy";
					</script>
					<?php
					echo '</td></tr><tr><td colspan="2" class="infoBoxContent">注意:添加人员请使用 &quot;<b>姓,名</b>&quot; 的格式,如：Hu,JingTong</td></tr><tr><td align="center" colspan="3" class="infoBoxContent"><input name="SubmitFast" type="submit" value=" Update "></td></tr></table></div><br /><br /><div id="new_guest_alert_msg_' . (int) $order->products[$i]['orders_products_id'] . '" style="display:none;"><font color="FF0000">' . ALERT_MSG_FOR_GUEST_CHANGE . '</font></div>';
					echo '&nbsp;<div align="right" style="vertical-align:bottom;"><div class="but_3 order_default" align="center" id="done_guestname_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[0]) ? '' : 'style="display:none;"') . '><a href="javascript: update_redbox(\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'orders_products_id', 'update_string', 'fudate')) . 'action=change_red_border_fields&orders_products_id=' . $orders_products_id . '&update_string=0&response_id=done_guestname_' . (int) $order->products[$i]['orders_products_id']) . '\');">done</a></div></div><br /><div id="guestname_not_matching_msg_' . (int) $order->products[$i]['orders_products_id'] . '" style="display:none;">' . ERROR_GUESTNAMES_NOT_MATCHING . '</div></td>';
					echo '<td class="' . (tep_not_null($show_red_border[1]) ? $red_border_class : '') . '" valign="top" id="red_alerts_lodging_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[1]) ? 'title="' . TITLE_LODGING_RED_BOX . '"' : '') . '>';
					//'.$show_red_border[0].'||||'.$show_red_border[2].'||'.$show_red_border[3]
					//echo $order->products[$i]['products_room_info'];
					echo '<div id="cart_product_data_' . $order->products[$i]['orders_products_id'] . '">';
					if (tep_not_null($order->products[$i]['total_room_adult_child_info'])) {
						$total_rooms = get_total_room_from_str($order->products[$i]['total_room_adult_child_info']);
					} else {
						$total_rooms = tep_get_total_nos_of_rooms($order->products[$i]['products_room_info']);
					}
					$room_total_adults = 0;
					$room_total_children = 0;
					if ($total_rooms > 0) {
						if (tep_not_null($order->products[$i]['total_room_adult_child_info'])) {
							echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">';
							echo '<tr><td class="p_l1 tab_t_bg ">No. of  Room</td><td class="tab_t_bg ">Adult</td><td class="tab_t_bg ">Child</td></tr>';
							for ($t = 1; $t <= $total_rooms; $t++) {
								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($order->products[$i]['total_room_adult_child_info'], $t);
								$room_total_adults = $room_total_adults + $chaild_adult_no_arr[0];
								$room_total_children = $room_total_children + $chaild_adult_no_arr[1];
								echo '<tr><td class="p_l1 order_default"><span>' . $t . '</span></td><td class="order_default">' . $chaild_adult_no_arr[0] . '</td><td class="order_default">' . $chaild_adult_no_arr[1] . '</td></tr>';
							}
							echo '</table>';
						} else {
							echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">';
							echo '<tr><td class="p_l1 tab_t_bg ">No. of  Room</td><td class="tab_t_bg ">Adult</td><td class="tab_t_bg ">Child</td></tr>';
							for ($t = 1; $t <= $total_rooms; $t++) {
								$room_total_adults = $room_total_adults + tep_get_rooms_adults_childern($order->products[$i]['products_room_info'], $t, 'adult');
								$room_total_children = $room_total_children + tep_get_rooms_adults_childern($order->products[$i]['products_room_info'], $t, 'children');
								echo '<tr><td class="p_l1 order_default"><span>' . $t . '</span></td><td class="order_default">' . tep_get_rooms_adults_childern($order->products[$i]['products_room_info'], $t, 'adult') . '</td><td class="order_default">' . tep_get_rooms_adults_childern($order->products[$i]['products_room_info'], $t, 'children') . '</td></tr>';
							}
							echo '</table>';
						}
						echo '<div>' . db_to_html("总房间数：{$total_rooms} 间") . '</div>';
					} else {
						if (tep_not_null($order->products[$i]['total_room_adult_child_info'])) {
							$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($order->products[$i]['total_room_adult_child_info'], 1);
							$total_adults = $chaild_adult_no_arr[0];
							$total_children = $chaild_adult_no_arr[1];
						} else {
							$total_adults = tep_get_no_adults_childern($order->products[$i]['products_room_info'], 'adult');
							$total_children = tep_get_no_adults_childern($order->products[$i]['products_room_info'], 'children');
						}
						$room_total_adults = $room_total_adults + $total_adults;
						$room_total_children = $room_total_children + $total_children;
						echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr><td class="tab_t_bg ">Adult</td><td class="tab_t_bg ">Child</td></tr>
								<tr><td class="order_default">' . $total_adults . '</td><td class="order_default">' . $total_children . '</td></tr>
							  </table>';
					}
					echo '</div>';
					$order->products[$i]['products_room_info'] = str_replace("<br>" . TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT, '', $order->products[$i]['products_room_info']);
					$order->products[$i]['products_room_info'] = preg_replace('/Total Fares \([0-9.]+% transaction fee included\)/', '', $order->products[$i]['products_room_info']);
					if (eregi('- Total :', stripslashes2($order->products[$i]['products_room_info']))) {
						$room_info = explode('- Total :', stripslashes2($order->products[$i]['products_room_info']));
					} else if (eregi('Total :', stripslashes2($order->products[$i]['products_room_info']))) {
						$room_info = explode('Total :', stripslashes2($order->products[$i]['products_room_info']));
					} else if (eregi('Total :-', stripslashes2($order->products[$i]['products_room_info']))) {
						$room_info = explode('Total :-', stripslashes2($order->products[$i]['products_room_info']));
					} else if (eregi('- Total of room', stripslashes2($order->products[$i]['products_room_info']))) {
						$room_info[0] = preg_replace('/-[[:space:]]Total[[:space:]]of[[:space:]]room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($order->products[$i]['products_room_info']));
					} else if (eregi('Total of Room', stripslashes2($order->products[$i]['products_room_info']))) {
						$room_info[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($order->products[$i]['products_room_info']));
					} else {
						$room_info[0] = preg_replace('/Total[[:space:]]of[[:space:]]room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($order->products[$i]['products_room_info']));
					}
					$products_room_info_textarea = str_replace(array("<br>", "</br>", "<br />"), "\r\n", $room_info[0], $m);
					if (tep_not_null($order->products[$i]['total_room_adult_child_info'])) {
						echo $edit_room_a.'</br><div id="edit_cart_product_data_' . (int) $order->products[$i]['orders_products_id'] . '" style="display:none;"><iframe onresize="calcHeight(\'iframe_prod_' . (int) $order->products[$i]['orders_products_id'] . '\');" id="iframe_prod_' . (int) $order->products[$i]['orders_products_id'] . '" src="ajax_edit_room_info.php?products_id=' . (int) $order->products[$i]['id'] . '&product_number=' . $i . '&oID=' . $oID . '&update_frame=no&room_total_adults_old=' . $room_total_adults . '&room_total_children_old=' . $room_total_children . '&opID=' . (int) $order->products[$i]['orders_products_id'] . '" frameborder="0"   scrolling="no" style="overflow:visible;" width="200px"  height="160px" allowTransparency="true"   > </iframe>';
						echo '<br /><font color="#FF000">' . strip_tags(ALERT_MSG_FOR_GUEST_CHANGE) . '</font></div>';
					}

					//amit added for display hotel information textbox start
					if (tep_not_null($order->products[$i]['hotel_pickup_info']) || $get_agency_info_total_array['is_hotel_pickup_info'] == '1') {
						echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr><td>&nbsp;</td></tr>
								<tr><td class="tab_t_bg ">Hotel Info</td></tr>
								<tr><td class="order_default">' . tep_draw_textarea_field('update_products[' . $orders_products_id . '][hotel_pickup_info]', 'soft', 25, 5, stripslashes2($order->products[$i]['hotel_pickup_info']), 'class="order_textarea"') . '</td></tr>
							  </table>';
					}
					//amit added for display hotel information textbox end

					//echo tep_draw_textarea_field('products_room_info_textarea_'.(int)$order->products[$i]['id'], 'soft', 25, 3, $products_room_info_textarea);
					/*
					if($total_rooms > 0){
					echo '<select style="width:60px;" name="numberOfRooms_'.(int)$order->products[$i]['id'].'" onchange="setNumRooms(this.value);">';
					for($r=1;$r<8;$r++){
					if($r==7){
					if(is_travel_comp((int)$oID, (int)$order->products[$i]['id']) > 0 ){
					echo '<option value="'.$r.'" selected >'.SHARE_ROOM_WITH_TRAVEL_COMPANION;
					}
					}else{
					echo '<option value="'.$r.'"'.($total_rooms == $r ? ' selected' : '').'>'.$r.'</option>';
					}
					}
					echo '</select>';
					}
					*/

					echo "<input name='update_products[$orders_products_id][previous_invoice_number]' type='hidden' value='" . $order->products[$i]['invoice_number'] . "'><input name='update_products[$orders_products_id][previous_invoice_amount]' type='hidden' value='" . $order->products[$i]['invoice_amount'] . "'><input name='update_products[$orders_products_id][invoice_number]' type='hidden' value='" . $order->products[$i]['invoice_number'] . "'><input name='update_products[$orders_products_id][invoice_amount]' type='hidden' value='" . $order->products[$i]['invoice_amount'] . "'>";
					echo '<div align="right" style="vertical-align:bottom;"><div class="but_3 order_default" align="center" id="done_lodging_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[1]) ? '' : 'style="display:none;"') . '><a href="javascript: update_redbox(\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=change_red_border_fields&orders_products_id=' . $orders_products_id . '&update_string=1&response_id=done_lodging_' . (int) $order->products[$i]['orders_products_id']) . '\');">done</a></div></div><br /><div id="lodging_not_matching_msg_' . (int) $order->products[$i]['orders_products_id'] . '" style="display:none;">' . ERROR_GUESTNAMES_NOT_MATCHING . '</div>';
					echo '</td>';

					if ($display_providers_comment == '1') {

						echo '		<td class="' . $RowStyle . ' ' . (tep_not_null($show_red_border[2]) ? $red_border_class : '') . '" align="left" valign="top" id="red_alerts_provider_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[2]) ? 'title="' . TITLE_PROVIDER_RED_BOX . '"' : '') . '>';


						echo '<div class="noabcdefg">';
						echo 	TEXT_SEAT_NO.' '.$order->products[$i]['customer_seat_no'].'<br />'.
								TEXT_BUS_NO.' '.$order->products[$i]['customer_bus_no'].'<br />'.
								TEXT_CONFIRMATION_NO.' '.$order->products[$i]['customer_confirmation_no'].'<br />';					
						
						//财务可看发票信息start {
						if ($can_view_invoice === true) {
							echo TEXT_INVOICE_NO.' '.$order->products[$i]['customer_invoice_no'].'<br />';
							//发票总额只有少数人能看到
							if($can_view_invoice_total === true){
								echo TEXT_INVOICE_TOTAL.' '.$order->products[$i]['customer_invoice_total'].'<br />';
							}
							echo TEXT_INVOICE_COMMENT.' '.$order->products[$i]['customer_invoice_comment'];
						}
						//财务可看发票信息end }
						echo "</div>\n";

						$pro_status_array = array();
						$qry_pro_status = "select * from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " os WHERE os.provider_order_status_for=0 ORDER BY os.sort_order ASC, os.provider_order_status_name ASC";
						$res_pro_status = tep_db_query($qry_pro_status);
						$pro_status_array[] = array('id' => 0,
						'text' => SELECT_NONE);
						while ($row_pro_status = tep_db_fetch_array($res_pro_status)) {
							$pro_status_array[] = array('id' => $row_pro_status['provider_order_status_id'],
							'text' => $row_pro_status['provider_order_status_name']);
						}
						
						//$txt_provider_status_name = tep_db_prepare_input($order->products[$i]['provider_order_status_name']);
						//$txt_admin_last_status_name = tep_db_prepare_input($order->products[$i]['admin_last_status_name']);
						//$txt_provider_comment = tep_db_prepare_input($order->products[$i]['provider_comment']);
						//$txt_admin_last_comment = tep_db_prepare_input($order->products[$i]['admin_last_comment']);
						
						if ($is_gift_certificate_tour == true ) {
							echo '此团$is_gift_certificate_tour为真，不提供交流记录输入框&nbsp;';
						} else {
							echo '<div style="width: 500px; margin: 5px 0px; overflow: auto;">';
							//(与地接商交流记录)
							include(DIR_WS_MODULES . 'edit_orders/provider_status_history.php');
							echo '</div>';

							$ajax_response_div_id = 'ajax_pro_conf_response_' . $order->products[$i]['orders_products_id'];
							echo '<div id="' . $ajax_response_div_id . '">';
							echo tep_draw_hidden_field('orders_id', $_GET['oID']);
							echo TEXT_MESSAGE_TO_PROVIDER . '<br />' .tep_draw_hidden_field('products_id_' . $order->products[$i]['orders_products_id'], $order->products[$i]['id']) ;
							$comment_value_for_hotel = '';
							if($order->products[$i]['is_hotel'] == 1){
								$comment_value_for_hotel = 'Check-in: '.tep_date_short($order->products[$i]['products_departure_date']).chr(13).chr(10).'Check-out: '.tep_date_short($order->products[$i]['hotel_checkout_date']);
							}
							
							if($allow_set_cancel_to_provider !== true){	//不可以给地接发取消下单的信息
								$_pro_status_array = $pro_status_array;
								unset($pro_status_array);
								foreach($_pro_status_array as $_option => $_val){
									$_optgroup = false;
									if($_val['id'] == '8'){
										$_optgroup = true;
									}
									$pro_status_array[] = array('id'=>$_val['id'], 'text'=>$_val['text'], 'optgroup' => $_optgroup );
								}
							}
							echo tep_draw_pull_down_menu('provider_order_status_id_' . $order->products[$i]['orders_products_id'], $pro_status_array, 0, 'style="width:290px;" id="select_flight_' . $i . '"') . '<br />';
							echo tep_draw_textarea_field('provider_comment_' . $order->products[$i]['orders_products_id'], $wrap, 91, 6,  $comment_value_for_hotel, 'class="order_textarea" id="textarea_flight_' . $i . '"') . '<br />';
							$ajax_pro_conf_params = ' onclick="sendFormData(\'edit_order\',\'' . tep_href_link(FILENAME_EDIT_ORDERS, 'action=act_providers_conf&ajax_formname=orders&opID=' . $order->products[$i]['orders_products_id']) . '\', \'' . $ajax_response_div_id . '\',\'true\'); enable_button(\'admin_confirmation_button_' . $order->products[$i]['orders_products_id'] . '\'); this.disabled=true;" ';
							
							if($order->products[$i]['orders_products_payment_status']!="1"){	//必须是已经付完款才能给供应商下单
								$ajax_pro_conf_params .= ' globaldisabled="1" disabled title="必须是已经付完款才能给供应商下单" ';
							}elseif($allow_message_to_provider != true){
								$ajax_pro_conf_params .= ' globaldisabled="1" disabled title="您暂无权限与供应商交流" ';
							}
							
							echo tep_draw_input_field('btnProvidersConfirmation_' . $order->products[$i]['orders_products_id'], '发送信息给供应商', $ajax_pro_conf_params, false, 'button');
							echo "&nbsp;&nbsp;&nbsp;&nbsp;";
							echo tep_draw_input_field('toProviderStatusHistory', '查看交流记录', 'onclick="if(jQuery(\'#providerStatusHistoryTable\').size() > 0){ jQuery(\'html,body\').animate({ scrollTop: jQuery(\'#providerStatusHistoryTable\').offset().top }); }else{ alert(\'无记录\'); }"', false, 'button' );

							//howard added 等待provider确认按钮 start
							$confirmation_disabled = '';
							$confirm_sql = tep_db_query('SELECT admin_and_provider_confirmed FROM `orders_products` WHERE `orders_products_id` ="' . $order->products[$i]['orders_products_id'] . '" LIMIT 1 ');
							$confirm_row = tep_db_fetch_array($confirm_sql);
							if ($confirm_row['admin_and_provider_confirmed'] == "1") {
								$confirmation_disabled = 'disabled';
							}
							echo '<br /><input id="admin_confirmation_button_' . $order->products[$i]['orders_products_id'] . '" type="button" value="' . db_to_html("已和Provider确认") . '" style="display:none" onclick="admin_confirmation(this,' . $order->products[$i]['orders_products_id'] . ')" ' . $confirmation_disabled . ' />';
							//此按钮暂时不显示
							//howard added 等待provider确认按钮 end
							echo '</div>';
						}
						echo '<div align="right" style="vertical-align:bottom;"><div class="but_3 order_default" align="center" id="done_provider_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[2]) ? '' : 'style="display:none;"') . '><a href="javascript: update_redbox(\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=change_red_border_fields&orders_products_id=' . $orders_products_id . '&update_string=2&response_id=done_provider_' . (int) $order->products[$i]['orders_products_id']) . '\');">done</a></div></div>';
						echo '</td>';
					} else {
						echo '		<td class="' . $RowStyle . '" align="left" valign="middle">此团的$display_providers_comment为0，该供应商没有使用我们的供应商管理系统！&nbsp;</td>';
					}
					$total_attribute_price = 0;
					$retail_pr_tt = $order->products[$i]['final_price'];
					//'.$show_red_border[0].'||'.$show_red_border[1].'||'.$show_red_border[2].'||'
					echo '		<td class="' . $RowStyle_price . ' ' . (tep_not_null($show_red_border[3]) ? $red_border_class : '') . '" valign="top" id="red_alerts_price_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[3]) ? 'title="' . TITLE_PRICE_RED_BOX . '"' : '') . '>
								  <table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td class="p_l1 tab_t_bg">&nbsp;</td>';
					if ($access_full_edit == 'true' || $access_order_cost == 'true') {
						echo '<td class="tab_t_bg" align="right">Cost</td>';
					}
					echo '<td class="tab_t_bg p_r " align="right">Retail</td>
						</tr>
						<tr>
						  <td class="p_l1" valign="top">';
					echo '<table width="100%" align="right" border="0">';
					if (sizeof($order->products[$i]['attributes']) > 0) {
						//echo '<table width="100%">';
						for ($j = 0; $j < sizeof($order->products[$i]['attributes']); $j++){
							$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
							echo '<tr><td>' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='21' value='" . $order->products[$i]['attributes'][$j]['option'] . "' class='order_textbox'>" . '</td></tr><tr><td>' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='21' value='" . $order->products[$i]['attributes'][$j]['value'] . "' class='order_textbox' >";
							echo '</td></tr>';
						}
						//echo '<br>';
					}
					echo '<tr><td><span class="col_h" style="white-space:nowrap; line-height:28px; ">Attr.Total:</span></td></tr>';
					echo '</table></td>';
					if ($access_full_edit == 'true' || $access_order_cost == 'true') {
						$total_attribute_price_cost = 0;
						echo '<td align="right" class="order_default" valign="top">';
						if (sizeof($order->products[$i]['attributes']) > 0) {
							echo '<table width="100%" align="right" border="0">'; // style="border:1px solid #999999"
							for ($j = 0; $j < sizeof($order->products[$i]['attributes']); $j++) {
								$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
								if ($order->products[$i]['attributes'][$j]['prefix'] == '-') {
									$total_attribute_price_cost = $total_attribute_price_cost - ($order->products[$i]['attributes'][$j]['price_cost'] * $order->products[$i]['qty']);
									$prefix = '-';
								} else {
									$total_attribute_price_cost = $total_attribute_price_cost + ($order->products[$i]['attributes'][$j]['price_cost'] * $order->products[$i]['qty']);
									$prefix = '';
								}
								echo '<tr><td height="27">&nbsp;</td></tr>';
								echo '<tr><td align="right"><nobr><small>';
								echo "&nbsp;" . tep_draw_separator('pixel_trans.gif', '10', '1') . "&nbsp;<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price_cost]' size='8' value='" . $prefix . number_format($order->products[$i]['attributes'][$j]['price_cost'], 2, '.', '') . "' class='order_textbox' onchange='compare_val(this.value, " . $prefix . number_format($order->products[$i]['attributes'][$j]['price_cost'], 2, '.', '') . ", \"is_cost_changed_" . $orders_products_id . "\");'><br><input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][previous_price_cost]' type='hidden' value='" . $prefix . number_format($order->products[$i]['attributes'][$j]['price_cost'], 2, '.', '') . "' class='order_textbox'>";
								echo '</small></nobr></td></tr>';
							}
							echo '<tr><td class="order_default" valign="bottom" nowrap="nowrap" align="right">' . $currencies->format($total_attribute_price_cost, true, $order->info['currency'], $order->info['currency_value']) . '</td>';
							echo '</tr></table>';
						} else {
							echo '&nbsp;';
						}
						echo '</td>';
					}
					if (sizeof($order->products[$i]['attributes']) > 0) {
						echo '<td class="p_r order_default" valign="top" align="right">';
						echo '<table width="100%">';
						for ($j = 0; $j < sizeof($order->products[$i]['attributes']); $j++) {
							$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
							if ($order->products[$i]['attributes'][$j]['prefix'] == '-') {
								$total_attribute_price = $total_attribute_price - ($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty']);
								$prefix = '-';
							} else {
								$total_attribute_price = $total_attribute_price + ($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty']);
								$prefix = '';
							}
							echo '<tr><td height="27">&nbsp;</td></tr>';
							echo "<tr><td align='right'><nobr><small>" . tep_draw_separator('pixel_trans.gif', '10', '1') . "&nbsp; <input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='8' value='" . $prefix . number_format($order->products[$i]['attributes'][$j]['price'], 2, '.', '') . "' class='order_textbox' onchange='compare_val(this.value, " . $prefix . number_format($order->products[$i]['attributes'][$j]['price'], 2, '.', '') . ", \"is_retail_changed_" . $orders_products_id . "\");'><input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][previous_price]' type='hidden' value='" . $prefix . number_format($order->products[$i]['attributes'][$j]['price'], 2, '.', '') . "'>";
							echo "</td></tr>";
						}
						echo '<tr><td class="order_default" valign="bottom" nowrap="nowrap" align="right" style="width:70px;">' . $currencies->format($total_attribute_price, true, $order->info['currency'], $order->info['currency_value']) . '</td>';
						echo '</tr></table>';
						echo '</td>';
					} else {
						echo '<td class="order_default tab_line1_wo_border" valign="bottom" align="center" >';
						echo 'N/A';
						echo '</td>';
					}
					echo '  </tr>
							<tr>
							  <td class="p_l1 next_line2"><span class="col_h" style="white-space:nowrap ">Tour Price:</span></td>';
					if ($access_full_edit == 'true' || $access_order_cost == 'true') {
						echo '<td class="next_line2 order_default" align="right" valign="middle">';
						$retail_pr_cost_tt = $order->products[$i]['final_price_cost'];
						echo "<input name='update_products[$orders_products_id][previous_final_price_cost]' type='hidden' value='" . number_format($order->products[$i]['final_price_cost'], 2, '.', '') . "'>
							  <input name='update_products[$orders_products_id][final_price_cost]' type='hidden' value='" . number_format($order->products[$i]['final_price_cost'], 2, '.', '') . "'>
							  <input name='update_products[$orders_products_id][final_price_cost_without_attr]' size='8' value='" . number_format($retail_pr_cost_tt - $total_attribute_price_cost, 2, '.', '') . "' class='order_textbox' onchange='compare_val(this.value, " . number_format($retail_pr_cost_tt - $total_attribute_price_cost, 2, '.', '') . ", \"is_cost_changed_" . $orders_products_id . "\");'>";
						echo '</td>';
					}
					echo '<td class="next_line2 p_r order_default" align="right" valign="middle">' . "<input name='update_products[$orders_products_id][previous_final_price]' type='hidden' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>
							<input name='update_products[$orders_products_id][final_price]' size='6' type='hidden' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>
							<input name='update_products[$orders_products_id][final_price_without_attr]' size='8'  value='" . number_format($retail_pr_tt - $total_attribute_price, 2, '.', '') . "' class='order_textbox' onchange='compare_val(this.value, " . number_format($retail_pr_tt - $total_attribute_price, 2, '.', '') . ", \"is_retail_changed_" . $orders_products_id . "\");'>
							<input type='hidden' name='update_products[$orders_products_id][original_final_price_without_attr]' value='" . number_format($retail_pr_tt - $total_attribute_price, 2, '.', '') . "'>
							" . '
						  </td>
						</tr>';

					//------------酒店延住等输入框 start{
					$default_status_str = "<b style='color:#FF0000'>未付</b>";
					$paid_status_str = "<b style='color:#4BA700'>已付</b>";
					$paid_status_ids = array(2, 3, 6); //可确认为已经付款的状态
					$os_final_price = 0; //os= OtherServer;

					$extended_hotel_sql = tep_db_query('select orders_products_id, status_id, hotel_final_price from orders_products_extended_hotel where orders_products_id="' . $orders_products_id . '" ');
					//echo ('select orders_products_id, status_id, hotel_final_price from orders_products_extended_hotel where orders_products_id="'.$orders_products_id.'" ');
					$extended_hotel_final_price = 0;
					$extended_hotel_status_str = $default_status_str;
					$extended_hotel_show = false;
					while ($extended_hotel = tep_db_fetch_array($extended_hotel_sql)) {
						if ((int) $extended_hotel['orders_products_id']) {
							if (in_array($extended_hotel['status_id'], $paid_status_ids)) {
								$extended_hotel_final_price += $extended_hotel['hotel_final_price'];
								$os_final_price += $extended_hotel['hotel_final_price'];
								$extended_hotel_status_str = $paid_status_str;
								$extended_hotel_show = true;
							}
						}
					}
					$airport_transfer_sql = tep_db_query('select orders_products_id, status_id, final_price from orders_products_airport_transfer where orders_products_id="' . $orders_products_id . '" Limit 1');
					$airport_transfer = tep_db_fetch_array($airport_transfer_sql);
					$airport_transfer_final_price = "";
					$airport_transfer_status_str = $default_status_str;
					$airport_transfer_show = false;
					if ((int) $airport_transfer['orders_products_id']) {
						$airport_transfer_final_price = $airport_transfer['final_price'];
						if (in_array($airport_transfer['status_id'], $paid_status_ids)) {
							$os_final_price += $airport_transfer_final_price;
							$airport_transfer_status_str = $paid_status_str;
							$airport_transfer_show = true;
						}
					}

					$address_transfer_sql = tep_db_query('select orders_products_id, status_id, final_price from orders_products_address_transfer where orders_products_id="' . $orders_products_id . '" Limit 1');
					$address_transfer = tep_db_fetch_array($address_transfer_sql);
					$address_transfer_final_price = "";
					$address_transfer_status_str = $default_status_str;
					$address_transfer_show = false;
					if ((int) $address_transfer['orders_products_id']) {
						$address_transfer_final_price = $address_transfer['final_price'];
						if (in_array($address_transfer['status_id'], $paid_status_ids)) {
							$os_final_price += $address_transfer_final_price;
							$address_transfer_status_str = $paid_status_str;
							$address_transfer_show = true;
						}
					}

					$orders_change_sql = tep_db_query('select orders_products_id, status_id, final_price from orders_products_orders_change where orders_products_id="' . $orders_products_id . '" Limit 1');
					$orders_change = tep_db_fetch_array($orders_change_sql);
					$orders_change_final_price = "";
					$orders_change_status_str = $default_status_str;
					$orders_change_show = false;
					if ((int) $orders_change['orders_products_id']) {
						$orders_change_final_price = $orders_change['final_price'];
						if (in_array($orders_change['status_id'], $paid_status_ids)) {
							$os_final_price += $orders_change_final_price;
							$orders_change_status_str = $paid_status_str;
							$orders_change_show = true;
						}
					}

					$os_td_class = "";
					$os_td_class_end = "next_line2";
					$extended_hotel_class = $airport_transfer_class = $address_transfer_class = $orders_change_class = $os_td_class_end;
					if ($orders_change_show == true) {
						$extended_hotel_class = $airport_transfer_class = $address_transfer_class = $os_td_class;
					}
					if ($address_transfer_show == true) {
						$extended_hotel_class = $airport_transfer_class = $os_td_class;
					}
					if ($airport_transfer_show == true) {
						$extended_hotel_class = $os_td_class;
					}

					if ($extended_hotel_show == true) {
						echo "
							<tr id='tr1_$orders_products_id'>
							<td class='p_l1 $extended_hotel_class' height='35'><span style='white-space: nowrap;' class='col_h'>酒店延住($extended_hotel_status_str):</span></td>
							<td valign='middle' align='right' class='order_default extendedTop  $extended_hotel_class'>".tep_draw_input_num_en_field("update_products[$orders_products_id][extended_hotel_final_price]", number_format($extended_hotel_final_price, 2, '.', ''), "readonly='true' size='12' class='order_textbox order_textboxBgGrey'")."</td>
							<td valign='middle' align='right' class='p_r order_default $extended_hotel_class'></td>
							</tr>";
					}
					if ($airport_transfer_show == true) {
						echo "
							<tr id='tr2_$orders_products_id'>
							<td class='p_l1 $airport_transfer_class' height='35'><span style='white-space: nowrap;' class='col_h'>机场接/送($airport_transfer_status_str):</span></td>
							<td valign='middle' align='right' class='order_default extendedTop $airport_transfer_class'>".tep_draw_input_num_en_field("update_products[$orders_products_id][airport_transfer_final_price]", $airport_transfer_final_price, "readonly='true' size='12' class='order_textbox order_textboxBgGrey'")."</td>
							<td valign='middle' align='right' class='p_r order_default $airport_transfer_class'></td>
							</tr>";
					}
					if ($address_transfer_show == true) {
						echo "
							<tr id='tr3_$orders_products_id'>
							<td class='p_l1 $address_transfer_class' height='35'><span style='white-space: nowrap;' class='col_h'>指定地点接/送($address_transfer_status_str):</span></td>
							<td valign='middle' align='right' class='order_default extendedTop $address_transfer_class'>" . tep_draw_input_num_en_field("update_products[$orders_products_id][address_transfer_final_price]", $address_transfer_final_price, "readonly='true' size='12' class='order_textbox order_textboxBgGrey'") . "</td>
							<td valign='middle' align='right' class='p_r order_default $address_transfer_class'></td>
							</tr>";
					}
					if ($orders_change_show == true) {
						echo "
							<tr id='tr4_$orders_products_id'>
							<td class='p_l1 $orders_change_class' height='35'><span style='white-space: nowrap;' class='col_h'>订单更改($orders_change_status_str):</span></td>
							<td valign='middle' align='right' class='order_default extendedTop $orders_change_class'>" . tep_draw_input_num_en_field("update_products[$orders_products_id][orders_change_final_price]", $orders_change_final_price, "readonly='true' size='12' class='order_textbox order_textboxBgGrey'") . "</td>
							<td valign='middle' align='right' class='p_r order_default $orders_change_class'></td>
							</tr>";
					}
					//------------酒店延住等输入框 end}

					//------------行程总价显示 start{
					echo '<tr><td height="50" valign="middle" align="right"><span class="col_b1">Total（小计）：&nbsp;</span></td>';
					if ($access_full_edit == 'true' || $access_order_cost == 'true') {
						echo '<td align="right" valign="middle">&nbsp;<span class="col_red_b">';
						echo $currencies->format($order->products[$i]['final_price_cost'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
						echo '</span></td>';
					} else {
						$total_attribute_price_cost = 0;
						if (sizeof($order->products[$i]['attributes']) > 0) {
							for ($j = 0; $j < sizeof($order->products[$i]['attributes']); $j++) {
								$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
								if ($order->products[$i]['attributes'][$j]['prefix'] == '-') {
									$total_attribute_price_cost = $total_attribute_price_cost - ($order->products[$i]['attributes'][$j]['price_cost'] * $order->products[$i]['qty']);
									$prefix = '-';
								} else {
									$total_attribute_price_cost = $total_attribute_price_cost + ($order->products[$i]['attributes'][$j]['price_cost'] * $order->products[$i]['qty']);
									$prefix = '';
								}
								echo "
									<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price_cost]' type='hidden' value='" . $prefix . number_format($order->products[$i]['attributes'][$j]['price_cost'], 2, '.', '') . "'>
									<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][previous_price_cost]' type='hidden' value='" . $prefix . number_format($order->products[$i]['attributes'][$j]['price_cost'], 2, '.', '') . "'>";
								//echo '</i></small></nobr>';
							}
						}
						echo "<input name='update_products[$orders_products_id][previous_final_price_cost]' type='hidden' value='" . number_format($order->products[$i]['final_price_cost'], 2, '.', '') . "'>
							  <input name='update_products[$orders_products_id][final_price_cost]' type='hidden' value='" . number_format($order->products[$i]['final_price_cost'], 2, '.', '') . "'>
							  <input name='update_products[$orders_products_id][final_price_cost_without_attr]' type='hidden'  value='" . number_format($order->products[$i]['final_price_cost'] - $total_attribute_price_cost, 2, '.', '') . "'>";
					}
					echo '<td align="right" valign="middle">&nbsp;<span class="col_red_b p_r ">';
					// this block is moved by lwkai 2013-06-03 to up
					/*$products_final_price = $order->products[$i]['final_price'] * $order->products[$i]['qty'];
					$products_final_price += $os_final_price;
					$new_ot_subtotal += $products_final_price;*/
					// moved end
					echo $currencies->format($products_final_price, true, $order->info['currency'], $order->info['currency_value']);
					echo '</span><input type="hidden" name="is_retail_changed_' . $orders_products_id . '" id="is_retail_changed_' . $orders_products_id . '" value="0"><input type="hidden" name="is_cost_changed_' . $orders_products_id . '" id="is_cost_changed_' . $orders_products_id . '" value="0"></td>
</tr>';
					//------------行程总价显示 end}
					
					//------------价格和成本更新备注 start{
					echo '<tr><td></td>';
					if ($access_full_edit == 'true' || $can_update_orders_price === true) {
						if ($access_full_edit == 'true' || $access_order_cost == 'true') {
							echo '<td valign="top">
									<span class="smallText">成本调整备注</span><br/>' . tep_draw_textarea_field("update_products[$orders_products_id][invoice_comments]", "soft", "20", "3", "", 'class="order_textarea" onblur="if(this.value!=\'\'){auto_write_comments(&quot;改底价！&quot;);}"') . '
								  </td>';
						}
						echo '<td valign="top">
								<span class="smallText">价格调整备注</span><button type="button" onclick="jQuery(\'html,body\').animate({ scrollTop: jQuery(\'#priceUpdateHistory\').offset().top });">查看明细</button><br/>' . tep_draw_textarea_field("update_products[$orders_products_id][invoice_comments_retail]", "soft", "20", "3", "", 'class="order_textarea" onblur="if(this.value!=\'\'){auto_write_comments(&quot;改价格！&quot;);}"') . '
							  </td>';
					} else {
						
						echo "<input name='update_products[$orders_products_id][previous_invoice_number]' type='hidden' value='" . $order->products[$i]['invoice_number'] . "'><input name='update_products[$orders_products_id][previous_invoice_amount]' type='hidden' value='" . $order->products[$i]['invoice_amount'] . "'><input name='update_products[$orders_products_id][invoice_number]' type='hidden' value='" . $order->products[$i]['invoice_number'] . "'><input name='update_products[$orders_products_id][invoice_amount]' type='hidden' value='" . $order->products[$i]['invoice_amount'] . "'>";
					}
					echo '</td>
						</tr>';
					//------------价格和成本更新备注 end}
					//------------付款状态选择框 start{
					if ($access_full_edit == 'true' || $access_order_cost == 'true') {
						echo '<tr><td valign="middle" height="50" align="right"><span class="col_b1">付款状态：&nbsp;</span></td><td>'.tep_draw_pull_down_menu("update_products[$orders_products_id][orders_products_payment_status]", tep_get_orders_products_payment_status_array(), $order->products[$i]['orders_products_payment_status'], ' style="font-size:14px;" onchange="auto_write_comments(\'更改付款状态！\');"').'</td><td>付款状态备注：'.tep_draw_textarea_field("update_products[$orders_products_id][orders_products_payment_status_remarks]", "soft", "20", "3", $order->products[$i]['orders_products_payment_status_remarks'], "class='order_textarea'").'</td></tr>';
					}
					//------------付款状态选择框 end}
					
					echo '
					  </table>';
					 
					echo '
					  
					  <div align="right" class="order_default"><br />' . tep_image_submit('button_update.gif', IMAGE_UPDATE, 'id="Cost_Retail_Nearest_Botton_' . $i . '" onclick="return check_for_price_change(\'' . $concat_string_ordprodid_tour_code . '\');"') . '</div><br />
					  <div align="right" style="vertical-align:bottom;"><div class="but_3 order_default" align="center" id="done_price_' . (int) $order->products[$i]['orders_products_id'] . '" ' . (tep_not_null($show_red_border[3]) ? '' : ' style="display:none;"') . '><a href="javascript: update_redbox(\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=change_red_border_fields&orders_products_id=' . $orders_products_id . '&update_string=3&response_id=done_price_' . (int) $order->products[$i]['orders_products_id']) . '\');">done</a></div></div>
					</td>';

					//echo $currencies->format($retail_pr_tt - $total_attribute_price, true, $order->info['currency'], $order->info['currency_value']);
					//amit added add extra customer information start
					if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_CHECKOUT_IDS.",") || in_array($get_tour_agency_id['agency_id'],explode(',','12,48'))) {
						$room_total_children = $room_total_adults + $room_total_children;
						$room_total_adults = 0;
					}
					//amit added add extra customer information end
					if((int)$order->products[$i]['is_hotel']==0 && $order->products[$i]['is_transfer'] == '0' ){
						if ($guest_total_adults != $room_total_adults || $guest_total_children != $room_total_children) {
					?>
					<script type="text/javascript">
					document.getElementById('red_alerts_guestname_<?php echo (int) $order->products[$i][orders_products_id]; ?>').className = 'p_t red_border';
					document.getElementById('red_alerts_guestname_<?php echo (int) $order->products[$i][orders_products_id]; ?>').title = 'Guest names are not matching to \'Lodging\' room numbers and passengers';
					document.getElementById('red_alerts_lodging_<?php echo (int) $order->products[$i][orders_products_id]; ?>').className = 'p_t red_border';
					document.getElementById('red_alerts_lodging_<?php echo (int) $order->products[$i][orders_products_id]; ?>').title = 'Please arrange no. or rooms/passengers according to guest names, both are not matching';
					document.getElementById('guestname_not_matching_msg_<?php echo (int) $order->products[$i][orders_products_id]; ?>').style.display = '';
					document.getElementById('lodging_not_matching_msg_<?php echo (int) $order->products[$i][orders_products_id]; ?>').style.display = '';
					if(document.getElementById('red_alerts_provider_<?php echo (int) $order->products[$i][orders_products_id]; ?>')){
						document.getElementById('red_alerts_provider_<?php echo (int) $order->products[$i][orders_products_id]; ?>').className = 'p_t red_border';
						document.getElementById('red_alerts_provider_<?php echo (int) $order->products[$i][orders_products_id]; ?>').title = 'Please send notification to provider for rooms/passengers change';
					}
					document.getElementById('red_alerts_price_<?php echo (int) $order->products[$i][orders_products_id]; ?>').className = 'p_t red_border';
					document.getElementById('red_alerts_price_<?php echo (int) $order->products[$i][orders_products_id]; ?>').title = 'Do not forget to adjust price according to guest info changes';
					</script>
					<?php
						}
					}
					echo '		<td class="' . $RowStyle . '" align="center" valign="top">';
					if ($is_gift_certificate_tour == true) {
						echo '&nbsp;';
					} else {
						echo '';
						if ($access_full_edit == 'true' || $can_view_invoice == true) {
							//invoice popup array - 发票层 start
							$travel_companion_tips_inv_body_con_pat = "[IIIIIIIIIIIIIIIIIIIIIIIIIIIIII]";
							$layer_obj[sizeof($layer_obj)] = array('pop' => 'travel_companion_tips_inv_' . $i,
							'drag' => "travel_companion_tips_inv_drag_" . $i,
							'con' => "travel_companion_tips_inv_popupCon_" . $i,
							'con_width' => "895px",
							'body_id' => "travel_companion_tips_inv_LayerBody_" . $i,
							'title' => 'Edit Invoice details And Comments: ' . $order->products[$i]['model'],
							'body_con' => $travel_companion_tips_inv_body_con_pat
							);

							$now_obj = $layer_obj[sizeof($layer_obj) - 1];
							$invoice_layers[$i] = pop_layer_tpl($now_obj);
							//echo $invoice_layers[$i];
							echo '<br /><span class="col_a" target="_blank" style="z-index:5" onclick="showPopup(\'' . $now_obj['pop'] . '\',\'' . $now_obj['con'] . '\');">查看发票</span>';
							//invoice popup array - 发票层 end
						}else{
							echo '<br /><input type="button" value="查看发票" disabled="disabled" />';
						}

						//howard added start 在没有小传真按钮时才显示大按钮
						if (0 && $hidden_big_button == false) { //暂时隐藏传真相关按钮
							echo '<br /><br /><a href="' . tep_href_link(FILENAME_ORDERS_FAX, tep_get_all_get_params(array('oID', 'action', 'i', 'products_id', 'update_fax', 'auto_submit', 'fudate')) . 'oID=' . $oID . '&products_id=' . $order->products[$i]['id'] . '&action=fax_show&i=' . $i . '&auto_submit=1') . '", target="_blank">' . '<div class="but_3 order_default">Preview Fax</div>' . '</a> <a  href="' . tep_href_link(FILENAME_ORDERS_FAX, tep_get_all_get_params(array('oID', 'action', 'i', 'products_id', 'update_fax', 'auto_submit', 'fudate')) . 'oID=' . $oID . '&products_id=' . $order->products[$i]['id'] . '&action=fax_show&i=' . $i) . '">' . '<div class="but_3 order_default">Fax</div>' . '</a> <a  href="' . tep_href_link(FILENAME_ORDERS_FAX, tep_get_all_get_params(array('oID', 'action', 'i', 'products_id', 'update_fax', 'auto_submit', 'fudate')) . 'oID=' . $oID . '&products_id=' . $order->products[$i]['id'] . '&action=fax_show&update_fax=true&i=' . $i) . '">' . '<div class="but_3 order_default">Update Fax</div></a>';
						}
						//howard added end 在没有小传真按钮时才显示大按钮

						//电子参团凭证按钮 start
						$travel_companion_tips_body_con_pat = "[XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]";
						$layer_obj[sizeof($layer_obj)] = array('pop' => 'travel_companion_tips_' . $i,
						'drag' => "travel_companion_tips_drag_" . $i,
						'con' => "travel_companion_tips_popupCon_" . $i,
						'con_width' => "895px",
						'body_id' => "travel_companion_tips_LayerBody_" . $i,
						'title' => 'Edit E-Ticket: ' . $order->products[$i]['model'],
						'body_con' => $travel_companion_tips_body_con_pat
						);

						$now_obj = $layer_obj[sizeof($layer_obj) - 1];
						if ((isset($HTTP_GET_VARS['show_eticket']) && $HTTP_GET_VARS['show_eticket'] == 'true') && isset($HTTP_GET_VARS['oID']) && $HTTP_GET_VARS['i'] == $i) {
							$eticket_layers[$i] = pop_layer_tpl($now_obj, true);
						} else {
							$eticket_layers[$i] = pop_layer_tpl($now_obj);
						}

						echo '<div class="but_3 order_default" target="_blank" style="z-index:5" onclick="showPopup(\'' . $now_obj['pop'] . '\',\'' . $now_obj['con'] . '\',100);">编辑电子参团凭证</div>';
						if($order->products[$i]['orders_products_payment_status']=="1" && $AccountingAudited === true /*&& in_array(tep_get_orders_products_provider_status_id($order->products[$i]['orders_products_id']),array('2','23'))*/ ){	//已付款状态、地接确认Confirmed and closed. 才能显示电子参团凭证发送按钮(地接确认的暂时取消。20120330改)
							echo ' <a href="' . (HTTP_SERVER . DIR_WS_ADMIN . 'eticket.php') . '?' . (tep_get_all_get_params(array('oID', 'action', 'i', 'products_id', 'fudate')) . 'oID=' . $oID . '&products_id=' . $order->products[$i]['id'] . '&action=eticket&i=' . $i) . '">' . '<div class="but_3_long order_default">电子参团凭证预览/发送</div>' . '</a>';
						}
						//发送签证邀请函按钮 by lwkai start {
						if($order->products[$i]['is_hotel'] == 0) {
							$invitation_send_date = visa_invitation::getSendMailDateJobNumber($orders_products_id);
							$invitation_title = array();
							if (!empty($invitation_send_date)) {
								foreach($invitation_send_date as $i_key=>$i_val) {
									$invitation_title[] = '发送人:' . $i_val['job_number'] . ' 发送时间:' . $i_val['date'];
								}
							}
							$invitation_title['tt'] = '只有财务和顶级管理员才能使用此功能，您不是财务所以不能点它！';
							$_html_parameter = '<button type="button" disabled="disabled" title="' . implode("\r\n",$invitation_title) . '" class="but_3_long order_default">编辑/预览/发送签证邀请函</button>';// 如果已经付款
							if($can_send_visa_invitations === true && $order->products[$i]['orders_products_payment_status']=="1"){
								unset($invitation_title['tt']);
								//if ($login_id == '246' || $login_id == '251' || $login_id == '212' || $login_id == '218' || $login_id == '210' || $login_id == '19') {
										$_html_parameter = '<a href="visa_edit_invitation.php?opid=' . $orders_products_id . '" target="_blank"  title="' . implode("\r\n",$invitation_title) . '"><div class="but_3_long order_default">编辑/预览/发送签证邀请函</div></a>';
									
								/*} else {
									$_html_parameter = '<button type="button" title="' . implode("\r\n",$invitation_title) . '" onclick="javascript:void(0)" id="' . $orders_products_id . '"  class="but_3_long order_default">编辑/预览/发送签证邀请函</button>';
								}*/
							}
							echo $_html_parameter;
							unset($_html_parameter);
						}
						//电子参团凭证按钮 end
						
						//根据电子参团凭证中的人数判断买二送一标准
						$eticket_sql = tep_db_query('SELECT guest_name  FROM `orders_product_eticket` WHERE orders_products_id='.$orders_products_id.' LIMIT 1');//orders_id ="' . (int) $oID . '" AND products_id="' . (int) $order->products[$i]['id'] . '" limit 1');
						$eticket_row = tep_db_fetch_array($eticket_sql);
						$tmp_array = explode('<::>', $eticket_row['guest_name']);
						/* if(count($tmp_array) <=3){
						$is_buy_two_get_one=false;
						} */
						$pep_num = (count($tmp_array) - 1);
						//如果该是买二送一团以及订购人数超过或等於3人就是买二送一或二
						$is_buy_two_get_one = check_buy_two_get_one($order->products[$i]['id'], $pep_num, endate_to_dbdate($order->products[$i]['products_departure_date']));
						if ($pep_num == '3') {
							if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
								echo '<br><b style="color:#FF0000">Buy two get one group</b>';
							}
						} else if ($pep_num == '4') {
							if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '3') {
								echo '<br><b style="color:#FF0000">Buy 2 get 2 groups</b>';
							}
							if ($is_buy_two_get_one == '2') {
								echo '<br><b style="color:#FF0000">Buy two get one group</b>';
							}
						}
						if (is_travel_comp((int) $oID, (int) $order->products[$i]['id']) > 0) {
							echo '<br>' . tep_draw_separator('pixel_trans.gif', '100%', '7') . '<a href="javascript:void(0);" onclick="showPopup(\'jbtyList\',\'jbtyList_popupCon\');"><span class="main" style="color:#F2720D"><b>结伴同游</b></span></a>';
						}
						if (strstr(strip_tags($order->products[$i]['products_room_info']), TXT_FEATURED_DEAL_DISCOUNT)) {
							echo '<br /><b style="color:#FF0000">' . TXT_GROUP_DEAL_DISCOUNT . '</b>';
						}
						//Group ordering
						if ($order->products[$i]['group_buy_discount'] > 0) {
							echo '<br><b style="color:#006699">' . ($order->products[$i]['is_diy_tours_book'] == 2 ? 'Featured Deal Discount' : 'Group booking discount') . ': -' . $currencies->format($order->products[$i]['group_buy_discount'], true, $order->info['currency'], $order->info['currency_value']) . '</b>';
						}
						//Group ordering end

						//New Group Buy {
						if($order->products[$i]['is_new_group_buy']=="1"){
							echo '<br><b style="color:#006699">'.TXT_IS_NEW_GROUP_BUY.'</b>';
							if($order->products[$i]['new_group_buy_type']=="1"){
								echo ' <b style="color:#006699">'.TXT_NEW_GROUP_BUY_TYPE1.'</b>';
							}
							if($order->products[$i]['new_group_buy_type']=="2"){
								echo ' <b style="color:#006699">'.TXT_NEW_GROUP_BUY_TYPE2.'</b>';
							}
						}
						//New Group Buy }
					}
					echo '<br>';
					
					if($can_delete_orders_products === true ){	//只有顶级管理员有删除订单行程的权力 by Howard added
						echo '<input type="button" value="删除此订单行程" onClick="check_confirm(' . $orders_products_id . ');" />';
					}else{
						echo '<input type="button" value="删除此订单行程" disabled="disabled" />';
					}
					
					echo '<br>';
					if($can_hide_orders_products === true ){	//只有顶级管理员和财务有隐藏和显示订单行程的权力 by Howard fixed from WTJ
						if($order->products[$i]['is_hide'] == "1"){
							$tmp_value = '显示';
							$show_or_hide = 'show_product';
						}else{
							$tmp_value = '隐藏';
							$show_or_hide = 'hide_product';
						}
						echo '<input id="show_or_hide_button_'.$orders_products_id.'" type="button" value="'.$tmp_value.'此订单行程" onClick="check_confirm_show_or_hide('.$orders_products_id.', \''.$show_or_hide.'\');" />';
					}else{
						$temp = ($order->products[$i]['is_hide']== "1") ? '显示' : '隐藏';
						echo '<input type="button" value="'.$temp.'此订单行程" disabled="disabled" />';
					}
					
					//付款状态显示 start{
					echo '<div style="margin-top:20px;">'.tep_get_orders_products_payment_status_name($order->products[$i]['orders_products_payment_status']).'</div>';
					//付款状态显示 end}
					//print_r($order->products[$i]);

					$get_double_book_list_array = get_double_booked_canceled_reference_orders_ids((int) $oID, $order->products[$i]['id']);
					$fianl_txt_str_active_bookings = '';
					$fianl_txt_str_canclled_bookings = '';
					$txt_str_active_bookings = '';
					$txt_str_canceled_bookings = '';
					if (sizeof($get_double_book_list_array) > 0) {
						for ($loi = 0; $loi < sizeof($get_double_book_list_array); $loi++) {
							$check_orders_id = $get_double_book_list_array[$loi]['orders_id'];
							if ($get_double_book_list_array[$loi]['orders_status'] == '6') {
								$txt_str_canceled_bookings .= $check_orders_id . ',';
							} else {
								$txt_str_active_bookings .= $check_orders_id . ',';
							}
						}
					}
					$txt_str_active_bookings = substr($txt_str_active_bookings, 0, -1);
					$txt_str_canceled_bookings = substr($txt_str_canceled_bookings, 0, -1);

					$display_full_active_string = '';
					$txt_str_active_bookings_explod_array = explode(',', $txt_str_active_bookings);
					if ($txt_str_active_bookings_explod_array[0] > 0) {
						if (sizeof($txt_str_active_bookings_explod_array) > 1) {
							$display_full_active_string .='<br><br><span style="white-space:nowrap;text-align:left;"><b>Active Booking:</b></span><br>';
						} else if (sizeof($txt_str_active_bookings_explod_array) == 1 && (int) $oID != (int) $txt_str_active_bookings_explod_array[0]) {
							$display_full_active_string .='<br><br><span style="white-space:nowrap;text-align:left;"><b>Active Booking:</b></span><br>';
						}
						for ($loi = 0; $loi < sizeof($txt_str_active_bookings_explod_array); $loi++) {
							if ((int) $oID != (int) $txt_str_active_bookings_explod_array[$loi] && (int) $txt_str_active_bookings_explod_array[$loi] != 0) {
								$display_full_active_string .='<a class="col_a1" target="_blank" href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . (int) $txt_str_active_bookings_explod_array[$loi]) . '">' . (int) $txt_str_active_bookings_explod_array[$loi] . '</a><br>';
							}
						}
					}
					echo $display_full_active_string;

					$display_full_cenceled_reference_string = '';
					$txt_str_canceled_bookings_explod_array = explode(',', $txt_str_canceled_bookings);
					if ($txt_str_canceled_bookings_explod_array[0] > 0) {
						if (sizeof($txt_str_canceled_bookings_explod_array) > 1) {
							$display_full_cenceled_reference_string .= '<br><br><span style="white-space:nowrap;text-align:left;"><b>Other Canceled<br>Double Bookings:</b></span><br>';
						} else if (sizeof($txt_str_canceled_bookings_explod_array) == 1 && (int) $oID != (int) $txt_str_canceled_bookings_explod_array[0]) {
							$display_full_cenceled_reference_string .= '<br><br><span style="white-space:nowrap;text-align:left;"><b>Other Canceled<br>Double Bookings:</b></span><br>';
						}
						for ($loi = 0; $loi < sizeof($txt_str_canceled_bookings_explod_array); $loi++) {
							if ((int) $oID != (int) $txt_str_canceled_bookings_explod_array[$loi] && $txt_str_canceled_bookings_explod_array[$loi] != 0) {
								$display_full_cenceled_reference_string .= '<a class="col_a1" target="_blank" href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . (int) $txt_str_canceled_bookings_explod_array[$loi]) . '">' . (int) $txt_str_canceled_bookings_explod_array[$loi] . '</a><br>';
							}
						}
					}
					echo $display_full_cenceled_reference_string;

					echo '</td>' . "\n" . '	  </tr>' . "\n";
					if ($order->products[$i]['operate_currency_exchange_code'] != 'USD' && $order->products[$i]['operate_currency_exchange_code'] != '' && $order->products[$i]['operate_currency_exchange_value'] != 0) {
						echo '	  <tr bgcolor="' . $bgcolor . '">';
						if ($access_full_edit == 'true' || $access_order_cost == 'true') {
							echo ' 	  <td class="' . $RowStyle . '" align="right" colspan="8" style="color:#007900 ">';
						} else {
							echo ' 	  <td class="' . $RowStyle . '" align="right" colspan="7" style="color:#007900 ">';
						}
						$converted_usd = 1 / $order->products[$i]['operate_currency_exchange_value'];
						echo '(1 ' . $order->products[$i]['operate_currency_exchange_code'] . ' = ' . number_format($converted_usd, 3) . ' USD)';
						echo '</td><td class="' . $RowStyle . '"  ><td>';
						echo '	  </tr>';
					}
					//echo '<tr><td>';
					//echo '</td></tr>';
					// 接驳车服务{
					if($order->products[$i]['is_transfer']=="1"){
						$OPTransferSql = tep_db_query('SELECT * FROM '.TABLE_ORDERS_PRODUCTS_TRANSFER.' WHERE orders_products_id="'.(int)$order->products[$i]['orders_products_id'].'" AND orders_id="'.(int)$oID.'" ');

						$orders_products_transfer_tpl = DIR_WS_MODULES . 'edit_orders/orders_products_transfer.tpl.html';
						$_tpl_con = file_get_contents($orders_products_transfer_tpl);
						$_tpl_con = preg_replace('/[[:space:]]+/', ' ',$_tpl_con);
						$_loop_code = preg_replace('/.*\{循环\}(.*)\{\/循环\}.*/','$1', $_tpl_con);
						$_ouput_code = "";
						$ln = 0;
						while($OPTransferRows = tep_db_fetch_array($OPTransferSql)){
							$TransferInfo[$ln] = $OPTransferRows;
							foreach($TransferInfo as $key => $val){
								$TransferInfo[$ln][$key] = tep_db_prepare_input($val);
							}
							$ln++;
						}

						for($I=0; $I<max($ln,2); $I++){
							$_con_pr = array();
							$_con_pr['p'][] = "{接送人数}";
							$_con_pr['r'][] = tep_draw_input_num_en_field('transferInfo['.$orders_products_id.']['.$I.'][guest_total]', $TransferInfo[$I]['guest_total'],' class="order_textbox" size="4" onBlur="transfer_calculation_price('.(int)$order->products[$i]['id'].');" ');
							$_con_pr['p'][] = "{航班号}";
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][flight_number]',$TransferInfo[$I]['flight_number'],' class="order_textbox" size="10"');
							$_con_pr['p'][] = "{送达/接应准确地点}";
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][flight_departure]', $TransferInfo[$I]['flight_departure'],' class="order_textbox" size="10"');
							$_con_pr['p'][] = "{期待接应时间}";
							$flight_pick_time = strpos($TransferInfo[$I]['flight_pick_time'],'1970-01-01') === false?$TransferInfo[$I]['flight_pick_time']:'';
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][flight_pick_time]', $flight_pick_time,' class="order_textbox" size="22" ');
							$_con_pr['p'][] = "{航班抵达时间}";
							
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][flight_arrival_time]', $TransferInfo[$I]['flight_arrival_time'],' class="order_textbox" size="22" onBlur="transfer_calculation_price('.(int)$order->products[$i]['id'].');" ');
							

							$tLocations = tep_transfer_get_locations((int)$order->products[$i]['id']);
							$addressOption = array();
							foreach((array)$tLocations as $key => $val){
								$zipcode = ((int)$val['zipcode']) ? '('.$val['zipcode'].')' : '';
								$addressOption[] = array('id'=>$val['products_transfer_location_id'],'text'=>$val['short_address'].$zipcode );
							}
							$_con_pr['p'][] = "{起点}";
							$_con_pr['r'][] = tep_draw_pull_down_menu('transferInfo['.$orders_products_id.']['.$I.'][pickup_id]', $addressOption, $TransferInfo[$I]['pickup_id'],' class="order_option" style="width:160px;" onChange="transfer_calculation_price('.(int)$order->products[$i]['id'].'); transfer_address_update(this, &quot;transferInfo['.$orders_products_id.']['.$I.'][pickup_address]&quot;); " ');

							$_con_pr['p'][] = "{起点地址}";
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][pickup_address]', $TransferInfo[$I]['pickup_address'],' class="order_textbox"');
							$_con_pr['p'][] = "{终点}";
							$_con_pr['r'][] = tep_draw_pull_down_menu('transferInfo['.$orders_products_id.']['.$I.'][dropoff_id]', $addressOption, $TransferInfo[$I]['dropoff_id'],' class="order_option" style="width:160px;" onChange="transfer_calculation_price('.(int)$order->products[$i]['id'].'); transfer_address_update(this, &quot;transferInfo['.$orders_products_id.']['.$I.'][dropoff_address]&quot;); " ');
							$_con_pr['p'][] = "{终点地址}";
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][dropoff_address]', $TransferInfo[$I]['dropoff_address'],' class="order_textbox"');
							$_con_pr['p'][] = "{行李数}";
							$_con_pr['r'][] = tep_draw_input_num_en_field('transferInfo['.$orders_products_id.']['.$I.'][baggage_total]', $TransferInfo[$I]['baggage_total'],' class="order_textbox" size="3"');
							$_con_pr['p'][] = "{备注}";
							$_con_pr['r'][] = tep_draw_input_field('transferInfo['.$orders_products_id.']['.$I.'][comment]', $TransferInfo[$I]['comment'],' class="order_textbox"  size="30"');
							$_con_pr['p'][] = "{隐藏域}";
							$_con_pr['r'][] =
							tep_draw_hidden_field('transferInfo['.$orders_products_id.']['.$I.'][orders_products_transfer_id]', $TransferInfo[$I]['orders_products_transfer_id'],' class="order_textbox"  size="30"').
							tep_draw_hidden_field('transferInfo['.$orders_products_id.']['.$I.'][created_date]', $TransferInfo[$I]['created_date'],' class="order_textbox"  size="30"')

							;

							$_ouput_code .= str_replace($_con_pr['p'], $_con_pr['r'], $_loop_code);
						}


						$_tpl_con = preg_replace('/{循环\}(.*)\{\/循环\}/',$_ouput_code, $_tpl_con);


						echo '<tr bgcolor="' . $bgcolor . '" ><td colspan="6" class="tab_line1">';
						echo $_tpl_con;
						echo '</td></tr>';
					}
					// 接驳车服务}
					// howard added Other Services start
					// 其它服务项目，如酒店延住、机场接/送服务、指定地点接/送服务、订单更改服务……
					if($login_groups_id == '1'){	//只有顶级管理员可看，因为这里功能未完成
						//echo '<tr bgcolor="' . $bgcolor . '"><td colspan="6" class="tab_line1 ">';
						//include("orders_other_services.php");
						//echo '</td></tr>';
					}
					$passagener_guest_note_row = tep_db_fetch_array(tep_db_query("select agency_id from " . TABLE_PRODUCTS . " where products_id = '".(int)$order->products[$i]['id']."'"));

					if($passagener_guest_note_row['agency_id'] == 2 || $passagener_guest_note_row['agency_id'] == 35 || $passagener_guest_note_row['agency_id'] == 87  || $passagener_guest_note_row['agency_id'] == 52   || $passagener_guest_note_row['agency_id'] == 108 ){

						$display_attr_no_of_passengers_note = 0;
						if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
							for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {

								if( preg_match("/酒店前往DCA\/IAD\/BWI机场/i", $order->products[$i]['attributes'][$j]['option'])
								|| preg_match("/DCA\/IAD\/BWI机场前往酒店/i", $order->products[$i]['attributes'][$j]['option'])
								|| preg_match("/请选择参团人数/i", $order->products[$i]['attributes'][$j]['option'])
								|| preg_match("/?T票?x??/i", $order->products[$i]['attributes'][$j]['option'])
								|| preg_match("/门票选择/i", $order->products[$i]['attributes'][$j]['option'])
								|| preg_match("/机场接机/i", $order->products[$i]['attributes'][$j]['option'])
								|| preg_match("/机场接送/i", $order->products[$i]['attributes'][$j]['option']) ){
									$display_attr_no_of_passengers_note = 1;
									break;
								}
							}
						}
						if($display_attr_no_of_passengers_note == 1){
							echo '	  <tr bgcolor="'.$bgcolor.'">';
							echo ' 	  <td class="tab_line1 smallText" align="left" colspan="8">';
							echo '<font color="#ff0000">请注意：当您选择附加服务的人数与参团人数不匹配时，价格计算可能会不准确。请再次确认您所选择的人数已匹配</font>';
							echo '</td>';
							echo '	  </tr>';
						}
					}
					// howard added Other Services end
					}
					?>
					<!-- End Products Listings Block -->
<script type="text/javascript">

/* 在前台显示或隐藏某订单产品 */
function check_confirm_show_or_hide(id, show_or_hide){
	var _do = true;
	if(show_or_hide == 'hide_product'){
		_do = false;
		if(confirm('您真的要隐藏此订单行程吗？隐藏后客人无法在前台看到此订单行程！')){
			_do = true;
		}
	}
	if(_do === true){
		$.ajax({
		  type: 'GET',
		  url: 'edit_orders.php',
		  data: {'action': show_or_hide ,'orders_products_id':id},
		  success: function(data){
			document.getElementById("show_or_hide_button_"+id).disabled = true;
			if(show_or_hide == "hide_product"){
				alert('请注意修改金额，谢谢。。。');
			}
		  }
		});
	}
}

</script>
					<!-- Begin Order Total Block -->
					<tr>
					  <td align="right" colspan="5"><?php // if($access_full_edit == 'true'){echo '5';}else {echo '4';}  ?>
						<table border="0" cellspacing="0" cellpadding="2" width="100%">
						  <tr>
							<td align='left' valign='top'><br>
							  <a target="_blank" href="<?php print $PHP_SELF . "?oID=$oID&action=add_product&step=1&page=$page"; ?>"><u><b><font size='3'><?php echo HEADING_STEP3 ?></font></b></u></a>
							  <?php
							  //结伴同游快速连接
							  if ($is_travel_comp_order == true) {
							  	$layer_obj[sizeof($layer_obj)] = array('pop' => 'jbtyList',
							  	'drag' => "jbtyList_drag");
							  ?>
							  <br>
							  <br>
							  <a href="javascript:void(0);" onclick="showPopup('jbtyList','jbtyList_popupCon');"><u><b><font size="3" color="#FF0000">Payment Details of Travel Companion Order 结伴同游</font></b></u></a>
							  <?php
							  }
							  ?></td>
							<td align='right'><table border="0" cellspacing="0" cellpadding="2" id="TotalModule">
								<?php
								$TotalsArray = array();
								for ($i = 0; $i < sizeof($order->totals); $i++) {
									$TotalsArray[] = array("Name" => $order->totals[$i]['title'], "Text" => $order->totals[$i]['text'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']);
									//$TotalsArray[] = array("Name" => "		  ", "Text" =>"", "Price" => "", "Class" => "ot_custom", "TotalID" => "0");
								}
								//array_pop($TotalsArray);
								foreach ($TotalsArray as $TotalIndex => $TotalDetails) {
									$TotalStyle = "smallText";
									if (($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total")) {
										// Howard added notify admin to update the ordres
										$notify_update_ordres_str = "";
										if ($TotalDetails["Class"] == "ot_subtotal" && (int) $TotalDetails["Price"] != (int) $new_ot_subtotal) {
											$notify_update_ordres_str = '&nbsp;<b class="col_red_b">注意：订单总价有变，请点update按钮进行更新！</b>';
											$notify_update_ordres_str .= '<script type="text/javascript">jQuery().ready(function(){ check_for_price_change("' . $concat_string_ordprodid_tour_code . '"); $("#edit_order").submit(); });</script>';
										}

										// Howard aded show RMB for total and subtotal start
										$rmb_total_string = '';
										if (preg_match('/￥|&#65509;/', $TotalDetails["Text"])) {
											$rmb_total_string = ' <span class="col_red_b">(' . $TotalDetails["Text"] . ')</span>';
										} else {
											$cny_rate = tep_db_fetch_array(tep_db_query('SELECT us_to_cny_rate FROM `orders` WHERE orders_id="' . (int) $oID . '" Limit 1'));
											if ($cny_rate['us_to_cny_rate'] > 0) {
												$rmb_total_string = ' <span title="下单日汇率为' . $cny_rate['us_to_cny_rate'] . '" class="col_red_b">(&asymp;￥' . number_format(round($TotalDetails["Price"] * $cny_rate['us_to_cny_rate'], 2), 2, '.', ',') . ')</span>';
											}
										}
										// Howard aded show RMB for total and subtotal end

										echo '		  <tr>' . "\n" .
										'		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
										'		<td class="main"><b>' . $TotalDetails["Price"] . $rmb_total_string . '</b>' . $notify_update_ordres_str .
										"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
										"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
										"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
										"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</td>' .
										'		  </tr>' . "\n";
									} elseif ($TotalDetails["Class"] == "ot_customer_discount") {
										//amit added to make -ve auto start
										if ($TotalDetails["Price"] > 0) {
											$TotalDetails["Price"] = ($TotalDetails["Price"] * -1);
										}
										//amit added to make -ve auto end

										echo '		  <tr>' . "\n" .
										'		<td class="main" align="right">' . ENTRY_CUSTOMER_DISCOUNT . '<b>' . $TotalDetails["Name"] . '</b></td>' .
										'		<td align="left" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value=' " . $TotalDetails["Price"] . "' class='order_textbox'>" .
										"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
										"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
										"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
										'		  </tr>' . "\n";
									} elseif ($TotalDetails["Class"] == "ot_redemptions" || $TotalDetails["Class"] == "ot_easy_discount" || $TotalDetails["Class"] == "ot_coupon") {
										//amit added to make -ve auto start
										if ($TotalDetails["Price"] > 0) {
											$TotalDetails["Price"] = ($TotalDetails["Price"] * -1);
										}
										//amit added to make -ve auto end
										//注意：积分优惠的输入框不能开放给客服修改，否则就和数据库对应不了。
										//折旧券时要隐藏折扣券编码,Sofia要求 Howard added by 2012-11-12
										$removeBrokerageButton = '';
										if(tep_db_get_field_value('affiliate_id', 'affiliate_sales', ' affiliate_orders_id="'.(int)$oID.'" ') > 0){
											$removeBrokerageButton = '<button onclick="removeBrokerage('.$oID.');" type="button">删除佣金</button>';
										}
										// 凡是用了优惠券的订单都不可以赠送积分！
										if($TotalDetails["Class"] == "ot_coupon"){
											tep_db_query('DELETE FROM `customers_points_pending` WHERE orders_id="'.(int)$oID.'" AND points_type="SP" AND points_pending>0 AND points_status="1" AND points_comment="TEXT_DEFAULT_COMMENT" ');
										}
										
										echo '		  <tr>' . "\n" .
										'		<td class="main" align="right"><b>' . (($can_see_orders_coupon_code === true && $TotalDetails["Class"] == "ot_coupon") ? $removeBrokerageButton.' <a href="'.tep_href_link('affiliate_affiliates.php','affiliate_coupon_code='.preg_replace('/.+:([^:]+):/', '$1', $TotalDetails["Name"])).'" target="_blank">'.$TotalDetails["Name"].'</a>' : ($TotalDetails["Class"] == "ot_coupon" ? '折扣券:' : $TotalDetails["Name"])) . '</b></td>' .
										'		<td align="left" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' ".(($can_see_orders_coupon_code === true) ? "" : " readonly='readonly' ") ." value=' " . $TotalDetails["Price"] . "' class='order_textbox'>" .
										"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
										"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
										"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
										'		  </tr>' . "\n";
									}elseif (($TotalDetails["Class"] == "ot_tax") && ($TotalDetails["Price"] > 0)) {
										echo '		  <tr>' . "\n" .
										'		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
										'		<td class="main"><b>' . $TotalDetails["Price"] .
										"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
										"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
										"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
										"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
										'		  </tr>' . "\n";
									}
									// Shipping
									elseif (($TotalDetails["Class"] == "ot_shipping") && ($TotalDetails["Price"] > 0)) {
										echo '	<tr>' . "\n" .
										'	   <td align="right" class="' . $TotalStyle . '"><b>' . HEADING_SHIPPING . '</b>' . $TotalDetails["Name"] . tep_draw_pull_down_menu('update_totals[' . $TotalIndex . '][title]', $orders_ship_method, $TotalDetails["Name"]) . '</td>' . "\n";
										echo '	<td align="left" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "' class='order_textbox'>" . "<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" . "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</td>' . '		  </tr>' . "\n";
									}
									// End Shipping
									else {
										if ($TotalDetails["Price"] != 0) {
											//howard edited start
											$TotalDetails["Price"] = $TotalDetails["Price"];
											if (preg_match('/-/', strip_tags($TotalDetails["Text"]))) {
												$TotalDetails["Price"] = '-' . abs($TotalDetails["Price"]);
												//$TotalStyle = '';
											}
											//howard edited end
											echo '		  <tr>' . "\n" .
											'		<td class="main" align="right" class="' . $TotalStyle . '"><b>' . $TotalDetails["Name"] . '</b></td>' .
											'		<td align="left" class="' . $TotalStyle . '">' . "<input type='text' name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" .
											"<input type='hidden' name='update_totals[$TotalIndex][title]' value='" . trim($TotalDetails["Name"]) . "' >" .
											"<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" .
											"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" .
											'</td>' . "\n" .
											'		  </tr>' . "\n";
										}
									}
								}

								//已付款金额，未付款金额{
								for ($i = 0; $i < sizeof($order->totals); $i++) {
									if($order->totals[$i]['class']=="ot_total"){
										$otTotal = $order->totals[$i]['value'];
										break;
									}
								}
								$need_pay = $otTotal - $order->info['orders_paid'];								
								?>
								<tr>
								<td class="main" align="right"><b>已付款:</b></td>
								<td align="left" class="main"><font color="#007900"><?= number_format($order->info['orders_paid'], 2, '.', '');?></font></td>
								</tr>
								<?php if($need_pay>0){?>
								<tr>
								<td class="main" align="right"><b>还需付款:</b></td>
								<td align="left" class="main"><font class="col_red_b p_r "><?= number_format($need_pay, 2, '.', '');?></font></td>
								</tr>
								<?php
								}
								//已付款金额，未付款金额}
								
								//amit added to show cost total for admin
								if ($access_full_edit == 'true' || $access_order_cost == 'true') {
									echo '		  <tr>' . "\n" .
									'		<td class="main" align="right"><b>Cost:</b></td>' .
									'		<td align="left" class="main"><b>' . number_format($order->info['order_cost'], 2, '.', '') .
									'</b></td>' . "\n" .
									'		  </tr>' . "\n";
								}
								//amit added to show cost total for admin
								
								?>
							  </table>
				<?php
				//若商品部有更新产品价格就通知客服人员 start {
				if($PCA->get_product_price_update_orders('0,2',$oID)){?>
				<b id="priceChangeTips"><span class="col_red_b">注意：商品部又改了产品价格，请注意重新核对价格！</span><button id="priceChangeKnowBtn" type="button">知道了</button></b>
				<script>
				jQuery('#priceChangeKnowBtn').click(function(){
					jQuery(this).attr('disabled', true);
					var url = "<?= tep_href_link(FILENAME_EDIT_ORDERS, 'action=update_orders_products_price_last_modified') ?>";
					jQuery.post(url,{orders_id:<?= $oID;?>},function(text){
						if(text=='ok'){
							jQuery('#priceChangeTips').remove();
						}else{
							jQuery(this).attr('disabled', false);
						}
					},'text');
				});
				</script>
				<?php }
				//若商品部有更新产品价格就通知客服人员 end }
				$result = tep_db_query("select * from price_notification_validation where orders_id='" . intval($oID) . "' order by click_time desc");
				while ($rs = tep_db_fetch_array($result)) {
					echo '<div>点击工号:' . $rs['job_number'] . ' ' . $rs['click_time'] . '</div>';
				}
				?>
							</td>
						  </tr>
						</table></td>
					  <td align="right">
					  <?php
					  /* $concat_string_ordprodid_tour_code = '';
					  for ($i=0; $i<sizeof($order->products); $i++) {
					  $concat_string_ordprodid_tour_code .= $order->products[$i]['orders_products_id'].'||'.$order->products[$i]['model'].'||=||';
					  }
					  $concat_string_ordprodid_tour_code = substr($concat_string_ordprodid_tour_code, 0, -5);
					  */
					  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, 'onclick="return check_for_price_change(\'' . $concat_string_ordprodid_tour_code . '\');"');
					  ?>
					  </td>
					</tr>
					<!-- End Order Total Block -->
				  </table>
				</td>
			  </tr>
			<?php 
			  // 订单留言开始 {
			    $order_message_query = tep_db_query("select * from orders_message where orders_id = '" . (int)$_GET['oID'] . "' order by id asc");
				$msgRowsNum = tep_db_num_rows($order_message_query);
				if ($msgRowsNum > 0 ) { ?>
              <tr>
              <td><br/><b style="font-size:14px;coloe:#000;">客人补充的订单留言：</b><br/>
              <table cellpadding="0" cellspacing="1" border="0" bgcolor="#CCCCCC" style="margin:8px 0;">
              <tr>
              <td height="25"  class="tab_t tab_line1">Comments</td>
              <td height="25" class="tab_t tab_line1">Date Added</td>
			  <td height="25" class="tab_t tab_line1">Read ID</td>
			  <td height="25" class="tab_t tab_line1">Date Read</td>
              </tr>
<?php              
while ($statuses = tep_db_fetch_array($order_message_query))
      {
			  $_read_a = '';
			  $_font_class = '';
			  if($statuses['has_read']!="1"){
			  	$_font_class = 'col_red_b';
				//$_read_a = '[<a target="_blank" href="'.tep_href_link('orders_warning.php','action=hidden_message&message_id='.$statuses['id'].'&orders_id='.(int)$_GET['oID']).'">设为已读</a>]';
				$_read_a= '<a style="cursor:pointer" onclick="jQuery.get(\''.tep_href_link('orders_warning.php','action=hidden_message&message_id='.$statuses['id'].'&orders_id='.(int)$_GET['oID']).'\',function(data,status){alert(\'设置为已读成功\');});jQuery(this).hide();">[设为已读]</a>';
			  }
		  ?>
              <tr>
              <td bgcolor="#FFFFFF" style="padding:8px" class=" <?php echo $_font_class;?>">
			  <?php
			  echo nl2br(db_to_html(tep_db_output($statuses['message'])));
			  echo $_read_a;
			  ?>			  
			  </td>
              <td bgcolor="#FFFFFF" style="padding:8px"><?php echo db_to_html($statuses['addtime']) ?></td>
			  <td bgcolor="#FFFFFF" style="padding:8px"><?php echo db_to_html(tep_get_job_number_from_admin_id($statuses['read_admin_id'])) ?></td>
			  <td bgcolor="#FFFFFF" style="padding:8px"><?php echo db_to_html($statuses['read_time']) ?></td>
              </tr>
              <?php 
	  }?>
              </table></td>
              </tr>
			  <?php
				} // 留言结束 }
			 ?>
			  
			  
			  <tr>
				<td class="col_b1"><strong>Order Status</strong> [<a href="javascript:void(0)" onclick="jQuery('#OrderStatusHistoryList').slideToggle(50)">隐藏/显示</a>]</td>
			  </tr>
			  <tr>
				<td><table id="OrderStatusHistoryList" border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p">
					<tr>
					  <td class="tab_t tab_line1" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
					  <td class="tab_t tab_line1" align="center" nowrap><b><?php echo TABLE_HEADING_UPDATED_BY; ?></b></td>
					  <td class="tab_t tab_line1" align="center" nowrap><b>下一个处理人</b></td>
					  <td class="tab_t tab_line1" align="center"><b>获得积分数</b></td>
					  <td class="tab_t tab_line1" align="center" nowrap><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
					  
					  <?php //if($CommentsWithStatus) {  ?>
					  <td class="tab_t" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
					  <?php //}  ?>
					</tr>
					<?php
					// Howard fixed by 2012-01-05 订单状态更新历史 {
					$orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by orders_status_history_id asc");
					if ($_total = tep_db_num_rows($orders_history_query)) {
						$_loop_num = 0;
						while ($orders_history = tep_db_fetch_array($orders_history_query)) {
							$_loop_num++;
							$_next_admin = '';
							if((int)$orders_history['next_admin_id']){
								$_next_admin = tep_get_admin_customer_name($orders_history['next_admin_id']);
								//加个按钮
								if($orders_history['is_processing_done']!="1" && $login_id == $orders_history['next_admin_id']){
									$_next_admin.= '&nbsp;<button type="button" onClick="set_processing_done('.(int)$login_id.', '.(int)$orders_history['orders_status_history_id'].', this)">处理OK</button>';
								}
							}
							$_updated_by = tep_get_admin_customer_name($orders_history['updated_by']);
							if($_total == $_loop_num && $_next_admin == ''){
								$_next_admin = (int)$order->info['need_next_admin'] ? tep_get_admin_customer_name($order->info['next_admin_id']) : '';
								//if(!tep_not_null($_next_admin) && $orders_history['updated_by']!=CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID ) $_next_admin = $_updated_by; //给下一个处理人为空时设默认值
							}
							
							$_comments = explode('Best Regards', $orders_history['comments']);	//不要真诚地(Best Regards) 后面的内容
							
							echo '		  <tr>' . "\n" .
							'			<td class="tab_line1 p_l1" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
							'			<td class="tab_line1 p_l1" align="center">' . $_updated_by . '&nbsp;</td>' . "\n" .
							'			<td class="tab_line1 p_l1" align="center">' . $_next_admin . '&nbsp;</td>' . "\n" .
							'			<td class="tab_line1 p_l1" align="center">' . $orders_history['score'] . '&nbsp;</td>' . "\n" .
							'			<td class="tab_line1 p_l1" align="center">';
							if ($orders_history['customer_notified'] == '1') {
								echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
							} else {
								echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
							}
							$effective_updated_btn = '';	//有效更新按钮，专供主管给销售加分用的
							if($can_add_score_to_customer_service === true && in_array($orders_history['orders_status_id'],array(100115,100100,100128)) && !$Assessment_Score->get_orders_status_history_score($orders_history['orders_status_history_id'])){	//0-01销售已经审核，等待主管下单给地接，0-03客人已更新航班信息,需更新给地接，0-04等待主管更新给地接。
								$effective_updated_btn = '<button type="button" onClick="add_effective_updated_score('.$orders_history['orders_status_history_id'].',this);">有效更新</button>';
							}
							//订单归属人更新的订单状态不出现“有效更新”按钮
							$orders_owners = explode(',',preg_replace('/[[:space:]]+/','',$order->info['orders_owners']));
							if(in_array(tep_get_admin_customer_name($orders_history['updated_by']), $orders_owners)){
								$effective_updated_btn = '';
							}
							echo '			<td class="tab_line1 p_l1">' . tep_get_orders_status_name($orders_history['orders_status_id']) . ' <span class="col_h">('.$orders_status_array[$orders_history['orders_status_id']].')</span>'.$effective_updated_btn.'</td>' . "\n";
							// if($CommentsWithStatus) {
							echo '			<td class="tab_line1 p_l1">' . rtrim(nl2br(tep_db_prepare_input($_comments[0])),'<br />') . '&nbsp;</td>' . "\n";
							// }
							echo '		  </tr>' . "\n";
						}
					} else {
						echo '		  <tr>' . "\n" .
						'			<td class="tab_line1 p_l1" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
						'		  </tr>' . "\n";
					}
					// Howard fixed by 2012-01-05 订单状态更新历史 }
					?>
				  </table></td>
			  </tr>
			  <?php 
			  require 'includes/classes/notebook.php';
			  $notebook=new notebook();
			  $nb_list=$notebook->getListByOrdersId($_GET['oID']);
			  if($nb_list){
			  ?>
			  <tr>
			  	<td>
				<strong>相关留言 [<a target="_blank" href="notebook.php?orders_id=<?php echo $_GET['oID']?>">查看全部留言</a>]</strong>
				<table border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p">
					<tr>
					<?php
					foreach($nb_list as $nb_note){
					?>
					<th width="120" class="tab_t tab_line1"><a target="_blank" href="notebook.php?notebook_id=<?php echo $nb_note['notebook_id']?>"><?php echo $nb_note['notebook_id'];?></a></th>
					<?php
					}
					?>
					</tr>
				</table>
				</td>
			  </tr>
			  <?php }?>
			  <tr>
			  	<td>
				<?php
				$op_button = '';
				if($can_edit_op_special_list === true){
					$op_button = '<a href="javascript:void(0);" onClick="fn_addremark('.$oID.');">[添加]</a>';
				}
				?>
				<strong>OP备注:(orders_remark)<?php echo $op_button;?></strong>
				<table border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p">
					<tr>
						<th width="40" class="tab_t tab_line1">角色</th>
						<th width="80" class="tab_t tab_line1">工号</th>
						<th width="300" class="tab_t tab_line1">备注内容</th>
						<th width="120" class="tab_t tab_line1">添加时间</th>
					</tr>
					<?php
					$op_remarks = get_orders_op_remark($oID);
					foreach($op_remarks as $op_remark){
					?>
					<tr>
						<td><?php echo $op_remark['role'];?></td>
						<td><?php echo $op_remark['admin_job_number'];?></td>
						<td><?php echo nl2br(tep_db_output($op_remark['remark']));?></td>
						<td><?php echo $op_remark['add_date'];?></td>
					</tr>
					<?php
					}
					?>
				</table>
				</td>
			  </tr>
			  <tr>
			  	<td>
			  	<table>
			  		<tr>
			  			<td>
			  				<strong>销售备注:(seller_remark)</strong>
							<table border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p">
								<tbody id="seller_remark_table">
									<tr>
										<th width="80" class="tab_t tab_line1">工号</th>
										<th width="300" class="tab_t tab_line1">备注内容</th>
										<th width="120" class="tab_t tab_line1">添加时间</th>
										<th class="tab_t tab_line1"><input type="button" value="添加" onclick="hideShow('add_seller_mark')" /></th>
									</tr>
									<?php
									$str_sql='select t1.admin_job_number,t2.* from admin t1,orders_remark_seller t2 where t1.admin_id=t2.login_id and t2.orders_id='.(int)$_GET['oID'];
									$sql_query=tep_db_query($str_sql);
									$seller_remarks=array();
									while ($rows = tep_db_fetch_array($sql_query)){
										$seller_remarks[] = $rows;
									}
									foreach($seller_remarks as $seller_remark){
									?>
									<tr>
										<td><?php echo $seller_remark['admin_job_number'];?></td>
										<td><?php echo nl2br(tep_db_output($seller_remark['remark']));?></td>
										<td><?php echo $seller_remark['add_time'];?></td>
										<td></td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</td>
						<td>
							<strong>主管备注:(head_remarks)</strong>
								<table border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p">
								<tbody id="head_remark_table">
									<tr>
										<th width="80" class="tab_t tab_line1">工号</th>
										<th width="300" class="tab_t tab_line1">备注内容</th>
										<th width="120" class="tab_t tab_line1">添加时间</th>
										<th class="tab_t tab_line1"><input type="button" value="添加" onclick="hideShow('add_head_mark')" /></th>
									</tr>
									<?php
									$str_sql='select t1.admin_job_number,t2.* from admin t1,orders_remarks_head t2 where t1.admin_id=t2.login_id and t2.orders_id='.(int)$_GET['oID'];
									$sql_query=tep_db_query($str_sql);
									$head_remarks=array();
									while ($rows = tep_db_fetch_array($sql_query)){
										$head_remarks[] = $rows;
									}
									foreach($head_remarks as $head_remark){
									?>
									<tr>
										<td><?php echo $head_remark['admin_job_number'];?></td>
										<td><?php echo nl2br(tep_db_output($head_remark['remark']));?></td>
										<td><?php echo $head_remark['add_time'];?></td>
										<td></td>
									</tr>
									<?php
									}
									?>
								</tbody>
								</table>
					  </td>
					</tr>
						<tr>
			  <td><div id="add_seller_mark" style="display:none;">
			 <textarea id="seller_mark_content"></textarea>
			 <input type="button" value="提交" onclick="addSellerMark()"/>
			 </div></td>
			  <td><div id="add_head_mark" style="display:none">
			 <textarea id="head_mark_content"></textarea>
			 <input type="button" value="提交" onclick="addSellerMark('add_head_mark','head_remark_table','head_mark_content')"/>
			 </div></td>
			  </tr>
				  </table>
				
				</td>
			  </tr>

			  <?php
 
			  
			  
			  //<!-- Order product status history -Start -->{
			  $res_order_prod_history = tep_db_query($qry_order_prod_history);
			  if (tep_db_num_rows($res_order_prod_history)) {
			  ?>
			  <tr>
				<td class="col_b1" id="providerStatusHistoryTable"><b><a title="点击可显示或隐藏与供应商交流的历史记录" href="javascript:void(0)" onclick="jQuery('#providerStatusHistoryTableList').toggle()"><?php echo TEXT_PROVIDER_STATUS_HISTORY; ?></a></b>
				<button onclick="if(jQuery('#show_eticket').size() > 0){ jQuery('html,body').animate({ scrollTop: jQuery('#show_eticket').offset().top }); }else{ alert('无法跳转'); }" type="button">去发信息给供应商</button>
				</td>
			  </tr>
			  <tr>
				<td><table id="providerStatusHistoryTableList" style="display:none" border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p"><!--与供应商交流记录表默认隐藏，Sofia觉得有点多余-->
					<tr>
					  <td class="tab_t tab_line1_wo_bot_border"><b><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></b></td>
					  <td class="tab_t tab_line1_wo_bot_border" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
					  <td class="tab_t tab_line1_wo_bot_border" align="center"><b><?php echo TABLE_HEADING_UPDATED_BY; ?></b></td>
					  <td class="tab_t tab_line1_wo_bot_border" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
					  <td class="tab_t tab_line1_wo_bot_border" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
					</tr>
					<?php
					$previous_prod_id = "";
					while ($row_order_prod_history = tep_db_fetch_array($res_order_prod_history)) {
						if ($row_order_prod_history['popc_user_type'] == '0') {
							$status_updated_by = STORE_NAME . " - ";
							$bgcolor = "#FFC0CB";
						} else {
							$status_updated_by = tep_get_providers_agency($row_order_prod_history['agency_id'], 1) . " - ";
							$bgcolor = "#33FF99";
						}
						if ((int) $row_order_prod_history['popc_updated_by'] > 0) {
							$status_updated_by.=tep_get_admin_customer_name($row_order_prod_history['popc_updated_by'], (int) $row_order_prod_history['popc_user_type']);
						} else {
							$status_updated_by.=$row_order_prod_history['popc_updated_by'];
						}

						if ($row_order_prod_history['products_id'] != $previous_prod_id) {
							$previous_prod_id = $row_order_prod_history['products_id'];
							echo '<tr><td class="tab_line1_wo_bot_border p_l1" align="left" colspan="5"><strong>' . $row_order_prod_history['products_model'] . '&nbsp;&nbsp;' . tep_get_products_name($row_order_prod_history['products_id']) . '</strong></td></tr>';
						}
						echo '		  <tr bgcolor="' . $bgcolor . '">' . "\n" .
						'			<td class="tab_line1_wo_bot_border p_l1" align="left" bgcolor="' . $bgcolor . '">' . $row_order_prod_history['products_model'] . '</td>' . "\n" .
						'			<td class="tab_line1_wo_bot_border p_l1" align="center">' . tep_datetime_short($row_order_prod_history['provider_status_update_date']) . '</td>' . "\n" .
						'			<td class="tab_line1_wo_bot_border p_l1" align="center">' . tep_db_output($status_updated_by) . '&nbsp;</td>' . "\n";
						echo '			<td class="tab_line1_wo_bot_border p_l1">' . $row_order_prod_history['provider_order_status_name'] . '</td>' . "\n" .
						'			<td class="tab_line1_wo_bot_border p_l1">' . nl2br(tep_db_output($row_order_prod_history['provider_comment'])) . '&nbsp;</td>' . "\n" .
						'		  </tr>' . "\n";
					}
					/* } else {
					echo '		  <tr>' . "\n" .
					'			<td class="smallText" colspan="5">' . TEXT_NO_PROVIDER_STATUS_HISTORY . '</td>' . "\n" .
					'		  </tr>' . "\n";
					} */
					?>
				  </table></td>
			  </tr>
			  <?php //<!-- Order product status history -End -->}
			  } ?>
			  
			  
			  
			  <?php if (tep_not_null($order->info['cancellation_history'])) { ?>
			  <tr>
				<td class="mar_t order_default"><b>Cancellation History</b></td>
			  </tr>
			  <tr>
				<td class="order_default" colspan="2"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="bor_tab table_td_p mar_t1">
					<tr>
					  <td class="tab_t tab_line1" valign="top"><b>Tour Code</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>Reason for Cancellation</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>Did you cancel with provider?</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>Customer's cancellation was received on</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>Customer Cancellation Fee</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>Provider Cancellation Fee</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>How did you calculate the cancellation fee?</b></td>
					  <td class="tab_t tab_line1" valign="top"><b>Updated by</b></td>
					</tr>
					<?php
					//print_r($cancellation_history_bottom);
					for ($i = 0; $i < sizeof($order->products); $i++) {
						if (sizeof($cancellation_history_bottom[$order->products[$i]['orders_products_id']]) > 0) {
							echo '<tr><td colspan="8" class="tab_line1 p_l1" valign="top"><b>' . $order->products[$i]['model'] . '</b>&nbsp;' . ($order->products[$i]['is_hotel']==1 ? '('.tep_date_short($order->products[$i]['products_departure_date']).')' : '').'</td></tr>';
							for ($j = 0; $j < sizeof($cancellation_history_bottom[$order->products[$i]['id']]); $j++) {
								echo '<tr>
										<td class="tab_line1 p_l1" valign="top">' . $order->products[$i]['model'] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][0] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][1] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][2] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][3] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][4] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][5] . '&nbsp;</td>
										<td class="tab_line1 p_l1" valign="top">' . $cancellation_history_bottom[$order->products[$i]['orders_products_id']][$j][6] . '&nbsp;</td>
									  </tr>';
							}
						}
					}
					?>
				  </table></td>
			  </tr>
			  <?php } ?>
			  <?php
			  $select_check_order_product_history_sql = "select op.orders_products_id,op.products_departure_date,op.is_hotel,oph.ord_prod_history_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment, oph.update_user_type, op.products_id, op.products_model, op.products_name , op.payment_paid, oph.products_model as oph_model, oph.products_name as oph_name, op.is_step3, op.is_step3_accounting_already_know from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph, " . TABLE_ORDERS_PRODUCTS . " op where oph.orders_products_id=op.orders_products_id and op.orders_id='" . $oID . "' ORDER BY op.orders_products_id ASC, oph.ord_prod_history_id ASC";
			  $select_check_order_product_history_row = tep_db_query($select_check_order_product_history_sql);
			  if (tep_db_num_rows($select_check_order_product_history_row) > 0) {
			  ?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr>
			  <tr>
				<td class="mar_t order_default" id="priceUpdateHistory">
				<b>价格调整历史记录</b> (<?php echo TEXT_GREEN_INDECATES_CHANGE_PROVIDER; ?>)
				</td>
			  </tr>
			  <tr>
				<td class=""><table border="0" cellspacing="0" cellpadding="0" class="bor_tab table_td_p">
					<tr>
					  <td class="tab_t tab_line1"><b><?php echo 'Tour Code'; ?></b></td>
					  <td class="tab_t tab_line1"><b><?php echo 'Updated Tour'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Retail Price'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Retail Price Adjusted'; ?></b></td>
					  <td class="tab_t tab_line1" ><b><?php echo 'Retail Comment'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Invoice No.'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Invoice Amt.'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Invoice Comment'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Cost'; ?></b></td>
					  <td class="tab_t tab_line1" align="center"><b><?php echo 'Cost Adjusted'; ?></b></td>
					  <td class="tab_t tab_line1" ><b><?php echo 'Cost Comment'; ?></b></td>
					  <td class="tab_t tab_line1"><b><?php echo 'Updated by'; ?></b></td>
					  <td class="tab_t tab_line1" ><b><?php echo 'GP'; ?></b></td>
					  <td class="tab_t tab_line1"><b><?php echo 'Paid'; ?></b></td>
					  <td class="tab_t"><b><?php echo 'Modify Date'; ?></b></td>
					</tr>
					<?php
					// $row_cnt = 0;
					//$diff_color_count = 0;
					$isLastRetailPriceTotal = 0;
					$hist_previous_prod_id = "";
					while ($select_check_order_product_history = tep_db_fetch_array($select_check_order_product_history_row)) {
						$tota_ratail_calculation[$select_check_order_product_history['orders_products_id']] = $tota_ratail_calculation[$select_check_order_product_history['orders_products_id']] + $select_check_order_product_history['retail'];
						$tota_cost_calculation[$select_check_order_product_history['orders_products_id']] = $tota_cost_calculation[$select_check_order_product_history['orders_products_id']] + $select_check_order_product_history['cost'];
						$array_split_cost_retail_comment = explode('!!###!!', $select_check_order_product_history['comment']);
						if ($select_check_order_product_history['oph_model'] != '') {
							$disply_prod_modl = $select_check_order_product_history['oph_model'];
						} else {
							$disply_prod_modl = $select_check_order_product_history['products_model'];
						}

						if (!isset($_SESSION['row_color_' . $disply_prod_modl . $oID])) {
							$_SESSION['row_color_' . $disply_prod_modl . $oID] = tep_get_rand_color($diff_color_count);
							$diff_color_count++;
						}
						if (!isset($_SESSION['paid_status' . $disply_prod_modl . $oID])) {
							if ($select_check_order_product_history['payment_paid'] > 0) {
								//$_SESSION['paid_status'.$disply_prod_modl.$oID] = '<font color="#FF0000">Paid</font>';
								////////// amit added for paid link
								$select_check_order_product_payment_history_sql = "select ord_prod_payment_id, op.orders_products_id, op.is_hotel from " . TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY . " opph, " . TABLE_ORDERS_PRODUCTS . " op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP '," . $select_check_order_product_history['orders_products_id'] . ",')  order by opph.ord_prod_payment_id desc";
								$select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
								$view_count = tep_db_num_rows($select_check_order_product_payment_history_row);
								if ($view_count > 0) {
									$select_check_order_product_payment_history = tep_db_fetch_array($select_check_order_product_payment_history_row);
									$view_count_link = '<a  target="_blank" href="' . tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, 'payment_id=' . $select_check_order_product_payment_history['ord_prod_payment_id'] . '&item_id=' . $select_check_order_product_history['orders_products_id'], 'NONSSL') . '"><font color="#FF0000">Paid</font></a>';
									if ($view_count > 1) {
										$view_count_link .= $view_count;
									}
								}
								////////// amit added for paid link
								$_SESSION['paid_status' . $disply_prod_modl . $oID] = $view_count_link;
							} else {
								$_SESSION['paid_status' . $disply_prod_modl . $oID] = '<font color="#007900">Unpaid</font>';
							}
						}

						$_class = '';
						$_button = '';
						if($select_check_order_product_history['is_step3']=='1' && $select_check_order_product_history['is_step3_accounting_already_know']!='1'){
							$_class = 'col_red_b';
							if($can_set_step3_set_know === true){
								$_button = '<button type="button" onclick="step3_set_know('.$select_check_order_product_history['orders_products_id'].')">我已知道这是step3产品</button>';
							}
						}
						if ($select_check_order_product_history['orders_products_id'] != $hist_previous_prod_id) {
							$hist_previous_prod_id = $select_check_order_product_history['orders_products_id'];
							echo '<tr><td class="tab_line1_wo_bot_border p_l1" align="left" colspan="15"><strong class="'.$_class.'">' . $disply_prod_modl .$_button;
							if($select_check_order_product_history['is_hotel'] == 1){
								echo ' ('.tep_date_short($select_check_order_product_history['products_departure_date']).')';
							}
							echo'</strong></td></tr>';
						}
						$invoice_updated_by = $select_check_order_product_history['update_user_type'];
						$tr_bgcolor = $_SESSION['row_color_' . $disply_prod_modl . $oID];
						$td_bgcolor = "";
						if ($invoice_updated_by == '1') {
							$tr_bgcolor = "#33FF99";
							$td_bgcolor = $_SESSION['row_color_' . $disply_prod_modl . $oID];
						}
					?>
					<tr bgcolor="<?php //echo $tr_bgcolor; ?>">
					  <td bgcolor="<?php //echo $td_bgcolor; ?>" class="tab_line1 p_l1"><?php
					  echo $disply_prod_modl;
					  ?></td>
					  <td class="tab_line1 p_l1"><?php
					  if ($select_check_order_product_history['oph_name'] != '') {
					  	echo $select_check_order_product_history['oph_name'];
					  } else {
					  	echo $select_check_order_product_history['products_name'];
					  }
					  ?></td>
					  <td class="tab_line1 p_l1" align="right"><?php
					  if ($invoice_updated_by == '1') {
					  	echo '&nbsp;';
					  } else {
						//注意：只把每个产品最后添加的那条记录的价格相加
						if($select_check_order_product_history['ord_prod_history_id'] == tep_db_get_field_value('ord_prod_history_id', TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, ' orders_products_id="'.$select_check_order_product_history['orders_products_id'].'" order by ord_prod_history_id DESC ' )){
							$isLastRetailPriceTotal += $tota_ratail_calculation[$select_check_order_product_history['orders_products_id']];
						}
						echo $tota_ratail_calculation[$select_check_order_product_history['orders_products_id']];
					  }
					  ?></td>
					  <td class="tab_line1 p_l1" align="right"><?php
					  if ($invoice_updated_by == '1') {
					  	echo '&nbsp;';
					  } else {
					  	if ($row_cnt[$select_check_order_product_history['orders_products_id']] > 0) {
					  		if ($select_check_order_product_history['retail'] < 0) {
					  			echo '<span class="errorText">' . $select_check_order_product_history['retail'] . '</span>';
					  		} else {
					  			if ($select_check_order_product_history['retail'] > 0) {
					  				$add_plush_sign = '+';
					  			} else {
					  				$add_plush_sign = '';
					  			}
					  			echo '<span>' . $add_plush_sign . $select_check_order_product_history['retail'] . '</span>';
					  		}
					  	} else {
					  		echo '0.00';
					  	}
					  } ?></td>
					  <?php 
					  ?>
					  <td class="tab_line1 p_l1">
					<?php
					if ($can_update_orders_price === true || $access_full_edit == 'true' || $access_order_cost == 'true') {
						echo tep_db_prepare_input(nl2br($array_split_cost_retail_comment[1]));
					}else{ echo '<span style="color:#CCCCCC;">--[hide]--</span>';}
					?>
					&nbsp; </td>
					  <td class="tab_line1 p_l1" align="center"><?php echo $select_check_order_product_history['invoice_number']; ?>&nbsp;</td>
					  <td class="tab_line1 p_l1" align="center">
					  <?php
					  if ($access_full_edit == 'true' || $access_order_cost == 'true') {
					  	echo $select_check_order_product_history['invoice_amount'];
					  }else{ echo '<span style="color:#CCCCCC;">--[hide]--</span>';}
					  ?>
					  &nbsp;
					  </td>
					  <td class="tab_line1 p_l1"><?php
					  echo tep_db_prepare_input($array_split_cost_retail_comment[2]);
					  ?>
						&nbsp; </td>
					  <td class="tab_line1 p_l1" align="right"><?php
					  if ($invoice_updated_by == '1') {
					  	echo '&nbsp;';
					  } else {
					  	if ($access_full_edit == 'true' || $access_order_cost == 'true') {
							echo number_format($tota_cost_calculation[$select_check_order_product_history['orders_products_id']],2, '.', '');
						}else{ echo '<span style="color:#CCCCCC;">--[hide]--</span>';}
					  }
					  ?></td>
					  <td class="tab_line1 p_l1" align="right"><?php
					  if ($invoice_updated_by == '1') {
					  	echo '&nbsp;';
					  } else {
					  	if ($row_cnt[$select_check_order_product_history['orders_products_id']] > 0) {
					  		if ($select_check_order_product_history['cost'] < 0) {
					  			echo '<span class="errorText">-' . $select_check_order_product_history['cost'] . '</span>';
					  		} else {
					  			if ($select_check_order_product_history['cost'] > 0) {
					  				$add_plush_sign = '+';
					  			} else {
					  				$add_plush_sign = '';
					  			}					  			
								echo '<span>' . $add_plush_sign . $select_check_order_product_history['cost'] . '</span>';								
					  		}
					  	} else {
					  		echo '0.00';
					  	}
					  }
					  ?></td>
					  <td class="tab_line1 p_l1">
					  <?php
					  if ($access_full_edit == 'true' || $access_order_cost == 'true') {
						  echo tep_db_prepare_input($array_split_cost_retail_comment[0]);
					  }else{ echo '<span style="color:#CCCCCC;">--[hide]--</span>';}
					  ?>
						&nbsp; </td>
					  <td class="tab_line1 p_l1" nowrap><?php
					  if ($invoice_updated_by == '1') {
					  	echo tep_db_prepare_input($array_split_cost_retail_comment[2]);
					  } else {
					  	$admin_updated_by = tep_get_admin_customer_name($select_check_order_product_history['updated_by']);
					  	if ($admin_updated_by != "") {
					  		echo STORE_NAME . ' - ' . $admin_updated_by;
					  	}
					  }
					  ?>
						&nbsp; </td>
					  <td class="tab_line1 p_l1" align="center"><?php
					  if ($invoice_updated_by == '1') {
					  	echo '&nbsp;';
					  } else {
					  	if ($tota_ratail_calculation[$select_check_order_product_history['orders_products_id']] != 0.00 && $tota_ratail_calculation[$select_check_order_product_history['orders_products_id']] != 0 && $tota_ratail_calculation[$select_check_order_product_history['orders_products_id']] != '') {
					  		$gp_cal = 1 - ($tota_cost_calculation[$select_check_order_product_history['orders_products_id']] / $tota_ratail_calculation[$select_check_order_product_history['orders_products_id']]);
					  	} else {
					  		$gp_cal = '';
					  	}
					  	if ($access_full_edit == 'true' || $access_order_cost == 'true') {
							echo number_format($gp_cal, 2, '.', '');
						}else{ echo '<span style="color:#CCCCCC;">--[hide]--</span>';}
					  }
					  ?></td>
					  <td class="tab_line1 p_l1" nowrap><?php
					  if ($invoice_updated_by == '1') {
					  	echo '&nbsp;';
					  } else {
					  	echo $_SESSION['paid_status' . $disply_prod_modl . $oID];
					  }
					  ?></td>
					  <td class="tab_line1 p_l1" nowrap><?php echo tep_datetime_short($select_check_order_product_history['last_updated_date']); ?></td>
					</tr>
					<?php
						$row_cnt[$select_check_order_product_history['orders_products_id']] = $row_cnt[$select_check_order_product_history['orders_products_id']] + 1;
					} ?>
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="right" style="padding:5px">最终金额总和: <b><?= $isLastRetailPriceTotal;?></b></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
				  </table></td>
			  </tr>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr>
			  <?php
			  //在价格历史调整记录后面添加客户付款历史过来 Howard added by 2013-06-07
			  if($can_js_show_customer_payment_history === true){
			  ?>
			  <tr>
			  <td id="J_CustomerPaymentHistory2">
			  </td>
			  </tr>
			  <script type="text/javascript">
			  jQuery('#J_CustomerPaymentHistory2').html('<div class="col_b1">客户付款记录2</div><table class="bor_tab table_td_p" cellspacing="0" cellpadding="0" border="0">'+jQuery('#J_CustomerPaymentHistory').html()+'</table>');
			  jQuery('#J_CustomerPaymentHistory2 a, #J_CustomerPaymentHistory2 button, #J_CustomerPaymentHistory2 input').remove();
			  </script>
			  <?php
			  	}
			  }
			  ?>
			  
			  <?php
			  if (1) {
			  ?>
			  <tr>
				<td class="f_order1"><strong>Charge Captured Information</strong></td>
			  </tr>
			  <tr>
				<td><table cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
					<tr>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Charge Captured Date (PST Time)</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Payment Method</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Amount</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Reference Comment</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Update By</strong></td>
					  <td class="tab_t"><strong>Modify Date</strong></td>
					</tr>
					<?php
					$ord_settle_prods_detail_history_sql = "select * from " . TABLE_ORDERS_SETTLEMENT_INFORMATION . " osi where orders_id ='" . (int) $oID . "' order by orders_settlement_id asc ";
					$ord_settle_prods_detail_history_query = tep_db_query($ord_settle_prods_detail_history_sql);
					while ($ord_settle_prods_detail_history_row = tep_db_fetch_array($ord_settle_prods_detail_history_query)) {
					?>
					<tr>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo $ord_settle_prods_detail_history_row['settlement_date']; ?></td>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo $ord_settle_prods_detail_history_row['orders_payment_method']; ?></td>
					  <td class="tab_line1_wo_bot_border p_l1" align="right"><?php
					  if ($ord_settle_prods_detail_history_row['order_value'] < 0) {
					  	echo '<span class="errorText">' . number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', '') . '</span>';
					  } else {
					  	echo number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', '');
					  }
					  ?>
						&nbsp;</td>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo tep_db_prepare_input(nl2br($ord_settle_prods_detail_history_row['reference_comments'])); ?></td>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo tep_get_admin_customer_name($ord_settle_prods_detail_history_row['updated_by']); ?></td>
					  <td class="order_default p_l1"><?php echo $ord_settle_prods_detail_history_row['date_added']; ?></td>
					</tr>
					<?php } ?>
				  </table></td>
			  </tr>
			  
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td>
                
                <table border="0" cellspacing="0" cellpadding="2" class="mar_t" style="float:left" id="emailEditBox">
					<tr>
					  <td class="order_default"><b><?php echo '更新订单状态选项';#ENTRY_STATUS; ?></b></td>
					  <td><div>
						  <?php
						  /* Howard added 添加层级的状态供客服使用 start */
						  //对应的项目文档"\TFF中文网站文档\项目文档\20110407_订单流程优化文档_Howard"
						  $status_groups_sql = tep_db_query('SELECT * FROM `orders_status_groups` ORDER BY sort_id ASC, os_groups_id ASC ');
						  $status_groups_options = array();
						  $status_groups_options_default = 0;
						  $status_groups_all = array();
						  foreach ($orders_statuses as $key => $val) {
						  	if (!isset($status_groups_all[$val['group']])) {
						  		$status_groups_all[$val['group']] = array();
						  	}
						  	array_push($status_groups_all[$val['group']], $val);
						  	if ($val['id'] == $order->info['orders_status']) {
						  		$status_groups_options_default = $val['group'];
						  	}
						  }
						  ksort($status_groups_all);
						  while ($status_groups_rows = tep_db_fetch_array($status_groups_sql)) {
						  	$optgroup = false;
							if(!in_array($status_groups_rows['os_groups_id'], $can_use_orders_status_groups) || ($status_groups_rows['os_groups_id']=="3" && $AccountingAudited !== true)){	//无论是谁，如果财务被审核过订单都不可选“发行程确认信”的状态
								$optgroup = true;
							}
							$status_groups_options[] = array('id' => $status_groups_rows['os_groups_id'], 'text' => tep_db_output($status_groups_rows['os_groups_name']), 'optgroup'=> $optgroup);
							
						  }
						  echo 'Group:' . tep_draw_pull_down_menu('status_groups', $status_groups_options, $status_groups_options_default, ' class="order_option" onchange="GetChildOrdersStatus(this.value)" ');
						  $status_groups_status = $status_groups_all[$status_groups_options_default];
						  $tmp_array = array();
						  foreach ($status_groups_status as $key => $val) {
						  	$tmp_array[] = $val["sort"];
						  }
						  array_multisort($tmp_array, SORT_ASC, SORT_NUMERIC, $status_groups_status); //按sort排序 $status_groups_status
						  //Cancellation Details填写完毕之后，方可进入下一步Cancellation Request sent to Provider(100046)
						  if ($order->info['orders_status'] != 100046 && !tep_not_null($order->info['cancellation_history'])) {
						  	$status_groups_status_tmp = array();
						  	foreach ($status_groups_status as $key => $val) {
						  		if ($status_groups_status[$key]['id'] != 100046) {
						  			$status_groups_status_tmp[] = $val;
						  		}
						  	}
						  	$status_groups_status = $status_groups_status_tmp; //vincent fixed2011-7-25
						  }
						  //print_r($status_groups_status_tmp); exit;
						  $status_groups_status = tep_filter_orders_statuses_from_can_use_orders_status($status_groups_status, $can_use_orders_status, $order->info['orders_status']);	//权限过滤
						  echo 'Status:' . tep_draw_pull_down_menu('status', $status_groups_status, $order->info['orders_status'], 'id="statusSelect" class="order_option"  onchange="checkStatusNotify(); check_status(this.value); changesubjectemail_new(this); changeStatusBoxes(this.value);setFollowUpOption();"');
						  //print_r($orders_statuses);
						  //exit;
						  
						  ?>
						</div>

						<script type="text/javascript">
							jQuery(document).ready(function($){
		$('#emailEditBox #statusSelect').change(function(){
			if($(this).val() == '100152'){
				$('#need_next_admin').parents('label').click();
				var defaultVal = ["选择下一步要操作此订单的人员","316","106","900","1001","20","19","17","2","885"];
				$('select[name="next_admin_id"] option').attr('selected',false);
				$('select[name="next_admin_id"] option').each(function(){
					var _val = $(this).text();
					var chk = false;
					for(var i=0; i<defaultVal.length; i++){
						if(_val == defaultVal[i]){
							//$(this).attr('disabled',true);
							chk = true;
							break;
						}
					}
					if (chk == false) {
						$(this).attr('disabled',true);
					}
				});
				$('select[name="next_admin_id"] option[value="0"]').attr('selected',true);
			}else{
				$('select[name="next_admin_id"] option').removeAttr('disabled');
			}
		});
	});
						var OrdersStatuses = new Array();
						<?php
						$tmp_num = 0;
						$can_use_orders_status;
						$orders_statuses = tep_filter_orders_statuses_from_can_use_orders_status($orders_statuses, $can_use_orders_status, $order->info['orders_status']);	//权限过滤
						$js_orders_statuses = $orders_statuses;
						$tmp_array = array();
						foreach ($orders_statuses as $ma => $val) {
							$tmp_array[] = $orders_statuses[$ma]["sort"];
						}
						array_multisort($tmp_array, SORT_ASC, SORT_NUMERIC, $orders_statuses); //子订单状态排序
						//Cancellation Details填写完毕之后，方可进入下一步Cancellation Request sent to Provider(100046)
						if ($order->info['orders_status'] != 100046 && !tep_not_null($order->info['cancellation_history'])) {
							foreach ($orders_statuses as $key => $val) {
								if ($orders_statuses[$key]['id'] == 100046) {
									unset($orders_statuses[$key]);
								}
							}
						}
						foreach ($orders_statuses as $key => $val) {
							echo 'OrdersStatuses[' . $tmp_num . '] = new Array("' . $orders_statuses[$key]['id'] . '","' . $orders_statuses[$key]['group'] . '","' . tep_db_output($orders_statuses[$key]['text']) . '","' . $orders_statuses[$key]['sort'] . '");' . "\n";
							$tmp_num++;
						}
						?>

						function GetChildOrdersStatus(ParentValue){
							if(ParentValue<1){ return false; }
							SelectObj = document.getElementById("statusSelect");
							SelectObj.length = 0;
							//SelectObj.options[0] = new Option("<?= "--请选择订单状态--" ?>", 0);
							//SelectObj.options[0].selected = true;
							tmp_num = 0;
							for(var i=0; i<OrdersStatuses.length; i++){
								if(OrdersStatuses[i][1]==ParentValue){
									SelectObj.options[tmp_num] = new Option(OrdersStatuses[i][2], OrdersStatuses[i][0]);
									if(tmp_num==0){
										SelectObj.options[tmp_num].selected = true;
									}else{
										SelectObj.options[tmp_num].selected = false;
									}
									tmp_num++;
								}
							}
							if(SelectObj.length>0){
								//触发Status菜单的change功能
								jQuery("#"+SelectObj.id).trigger("change");
							}
						}

						//选择了订单状态后的下一步动作，主要是改变StatusBoxes中的内容；
						function changeStatusBoxes(ordersStatusValue){
							if(ordersStatusValue<1){ return false;}
							jQuery("div[id^='status_display_boxes_']").hide();
							jQuery("#status_display_boxes_"+ordersStatusValue).show();
						}

						function checkboxActionForStatus_display_boxes_100083(){
							//Comments = "";
							Subject = "";
							if(document.getElementById("checkbox_100083_0").checked==true){
								document.getElementById('notify').checked = true;
								//Comments += "\n已选择发送English ONLY信件 \n";
							}
							if(document.getElementById("checkbox_100083_1").checked==true){
								document.getElementById('notify').checked = true;
								//Comments += "\n已选择发送CCV信件 \n";
							}
							if(document.getElementById("checkbox_100083_2").checked==true){
								document.getElementById('notify').checked = true;
								//Comments += "\n已选择发送Flight信件 \n";
							}
							try{
								document.edit_order.email_subject.value = Subject ? Subject : orderstatussubjectarray[document.edit_order.status.value];
								document.edit_order.comments.value = Comments.replace(/\n/gi, "<br />");
								tinyMCE.updateContent('comments');
								tinyMCEformsaveIt();
							}catch(e){}
						}

						function checkboxActionForStatus_display_boxes_100090(){
							Subject = "Please provide supporting documentation. 请您提供订单<?= $oID ?>的支持文档";

							Comments = '为了继续处理您的订单，请您尽快发送给我们：<br /><b>信用卡支付验证书</b><br /><b>信用卡主人的有效身份证件</b><br /><b>信用卡正面的影印件</b><br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a><br />谢谢您的配合！<br /><br />In order for us to further process your reservation, please send below information to us at your earliest convenience：<br />Credit Card Holder Verification Form<br />Credit Card Holder ID<br />Credit Card Imprint<br />To download the form or read more details, please refer to: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a>';
							if(document.getElementById("checkbox_100090_3").checked==true){
								document.getElementById("checkbox_100090_0").checked = false;
								document.getElementById("checkbox_100090_1").checked = false;
								document.getElementById("checkbox_100090_2").checked = false;
							}
							if(document.getElementById("checkbox_100090_0").checked==true){
								Comments = '感谢您在在最短的时间内发送给我们所需的部分文件。我们已经确定收到您的信用卡支付验证书。<br />请您尽快发送给我们：<b>信用卡主人的有效身份证件及信用卡正面的影印件</b>， 以便我们继续处理您的订单。<br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a><br />谢谢您的配合！<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received Credit Card Holder Verification Form.<br />However, your copies of Credit Card Holder ID and Credit Card Imprint were missing.<br />In order for us to further process your reservation, please send copies to us at your earliest convenience.<br />For more details, please refer to: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a>';
							}
							if(document.getElementById("checkbox_100090_1").checked==true){
								Comments = '感谢您在在最短的时间内发送给我们所需的部分文件。我们已经确定收到您的信用卡主人的有效身份证件。<br />请您尽快发送给我们： <b>信用卡支付验证书及信用卡正面的影印件</b>，以便我们继续处理您的订单。<br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a> 谢谢您的配合！<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received Credit Card Holder ID.<br />However, your copies of Credit Card Holder Verification Form and Credit Card Imprint were missing.<br />In order for us to further process your reservation, please send copies to us at your earliest convenience.<br />To download the form or read more details, please refer to: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a>.';
							}
							if(document.getElementById("checkbox_100090_2").checked==true){
								Comments = '感谢您在在最短的时间内发送给我们所需的部分文件，我们已经确定收到您的信用卡正面的影印件。<br />请您尽快发送给我们：<b>信用卡支付验证书及信用卡主人的有效身份证件</b>，以便我们继续处理您的订单。<br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a> 谢谢您的配合！<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received Credit Card Imprint.<br />However, copies of Card Holder Verification Form and Credit Card Holder ID were missing.<br />In order for us to further process your reservation, please send copies to us at your earliest convenience.<br />To download the form or read more details, please refer to below link: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a>';
							}
							if(document.getElementById("checkbox_100090_0").checked==true && document.getElementById("checkbox_100090_1").checked==true){
								Comments = '感谢您在在最短的时间内发送给我们所需的部分文件。我们已经确定收到您的信用卡支付验证书及信用卡主人的有效身份证件。<br />请您尽快发送给我们：<b>信用卡正面的影印件</b>，以便我们继续处理您的订单。<br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a> 谢谢您的配合！<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received your Credit Card Holder Verification Form and Credit Card Holder ID.<br />However, your copy of Credit Card Imprint was missing. In order for us to further process your reservation, please send a copy to us at your earliest convenience.<br />For more details, please refer to: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php.</a>';
							}
							if(document.getElementById("checkbox_100090_1").checked==true && document.getElementById("checkbox_100090_2").checked==true){
								Comments = '感谢您在在最短的时间内发送给我们所需的部分文件。我们已经确定收到您的信用卡主人的有效身份证件及信用卡正面的影印件。<br />请您尽快发送给我们：<b>信用卡支付验证书</b>，以便我们继续处理您的订单。<br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a> 谢谢您的配合！<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received Credit Card Holder ID and Credit Card Imprint.<br />However, your copy of Credit Card Holder Verification Form was missing. In order for us to further process your reservation, please send a copy to us at your earliest convenience.<br />To download the form or read more details, please refer to: <a href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php" target="_blank"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a>.';
							}
							if(document.getElementById("checkbox_100090_0").checked==true && document.getElementById("checkbox_100090_2").checked==true){
								Comments = '感谢您在在最短的时间内发送给我们所需的部分文件。我们已经确定收到您的信用卡支付验证书及信用卡正面的影印件。<br />请您在方便时尽快发送给我们：<b>信用卡主人的有效身份证件</b>，以便我们继续处理您的订单。<br />关于所需支持文件详情、如何下载文件以及发送文件，请参考: <a target="_blank" href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a> 谢谢您的配合！<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received Card Holder Verification Form and Credit Card Imprint.<br />However, your copy of Credit Card Holder ID was missing. In order for us to further process your reservation, please send a copy to us at your earliest convenience,<br />For more details, please refer to: <a target="_blank" href="<?= HTTP_SERVER?>/download_acknowledgement_card_billing.php"><?= HTTP_SERVER?>/download_acknowledgement_card_billing.php</a>';
							}
							if(document.getElementById("checkbox_100090_0").checked==true && document.getElementById("checkbox_100090_1").checked==true && document.getElementById("checkbox_100090_2").checked==true){	//全部选中
								Comments = '感谢您在最短的时间内发送给我们所需的支持文件。我们已经确定收到文件，并在处理中。我们将尽快发给您参团凭证。<br /><br />Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received your documents and everything looks great. We will issue an E-Ticket to you shortly.';
								Subject = "Full Doc Received我们已经收到您的所有支持文档";

								//全选中时自动变换为100014状态
								jQuery("#statusSelect").val(100014);
								jQuery("#statusSelect").change();
}
Subject += " - 订单#C-<?= $oID ?>";
try{
	document.edit_order.email_subject.value = Subject;
	document.edit_order.comments.value = Comments.replace(/\n/gi, "<br />");
	tinyMCE.updateContent('comments');
	tinyMCEformsaveIt();
}catch(e){}
}
//设置由美国客服和中国客服跟踪的订单状态码 begin
//根据订单状态设置订单跟踪选项 - vincent
var followup_array_us =['100009','100072','100004','100019','100085','100088','100089','100092','100021','100046','100045'];
var followup_array_cn=['100094','100083','100036','100012','100026','100084','100086','100087','100090','100091','100057','100075','100020'];
function setFollowUpOption(){
	var statusCode = jQuery("#statusSelect").val();
}
//设置由美国客服和中国客服跟踪的订单状态码 end
						</script>

						<!--选择Confirmation（100083）后，弹出下列勾选项-->
						<style type="text/css">
						<!--
						.StatusBoxesHide {
						padding:10px 0;
						display:none;
						}
						-->
						</style>
						<div id="status_display_boxes_100083" class="StatusBoxesHide">
						  <label>
							<input type="checkbox" name="checkbox_100083[]" id="checkbox_100083_0" onclick="checkboxActionForStatus_display_boxes_100083()" value="English ONLY">
							English ONLY</label>
						  &nbsp;&nbsp;
						  <label>
							<input type="checkbox" name="checkbox_100083[]" id="checkbox_100083_1" onclick="checkboxActionForStatus_display_boxes_100083()" value="CCV">
							CCV</label>
						  &nbsp;&nbsp;
						  <label>
							<input type="checkbox" name="checkbox_100083[]" id="checkbox_100083_2" onclick="checkboxActionForStatus_display_boxes_100083()" value="Flight">
							Flight</label>
						  <label class="col_red_b">每勾选一项，系统将会发送客户一封通知邮件。此处Flight选项仅适用于非结伴同游订单；若结伴同游需航班信息，请在step 4里面选择"状态"请结伴同游者提供航班信息"单独发送</label>
						</div>
						<div id="status_display_boxes_100090" class="StatusBoxesHide">
						  <label>We have received: </label>
						  <label>
							<input type="checkbox" name="checkbox_100090[]" id="checkbox_100090_0" onclick="checkboxActionForStatus_display_boxes_100090()" value="CCV Form">
							CCV Form</label>
						  &nbsp;&nbsp;
						  <label>
							<input type="checkbox" name="checkbox_100090[]" id="checkbox_100090_1" onclick="checkboxActionForStatus_display_boxes_100090()" value="Card Holder ID">
							Card Holder ID</label>
						  &nbsp;&nbsp;
						  <label>
							<input type="checkbox" name="checkbox_100090[]" id="checkbox_100090_2" onclick="checkboxActionForStatus_display_boxes_100090()" value="CC imprint">
							CC imprint</label>
						  <label>
							<input type="checkbox" name="checkbox_100090[]" id="checkbox_100090_3" onclick="checkboxActionForStatus_display_boxes_100090()" value="None">
							None</label>
						</div>
						<div id="status_display_boxes_100045" class="StatusBoxesHide">
						  <label>Provider Cancellation Penalty:
							<?= tep_draw_input_field('provider_cancellation_penalty', '', 'id="provider_cancellation_penalty" onBlur="provider_cancellation_penalty_onBlur()" class="order_textbox" ');?>
						  </label>
						  <label>Estimated Provider Cancellation Fee:
							<?= tep_draw_input_field('provider_cancellation_fee_1',$order->info['provider_cancellation_fee_1'],'id="provider_cancellation_fee_1" disabled class="order_textbox"  ');?>
						  </label>
						</div>
						<div id="status_display_boxes_100094" class="StatusBoxesHide">
						  <table width="200" border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td><label>
								  <?php
								  $tmp_payments_options = $Optimized_orders_pay_method;
								  echo tep_draw_pull_down_menu('tmp_payments',$tmp_payments_options,'', 'id="tmp_payments" size="7" multiple="multiple" style="color:#CCCCCC"');
								  ?>
								</label></td>
							  <td><label>
								  <input name="button" type="button" value="--&gt;&gt; " onclick="add_to_class('tmp_payments','payment_methods'); show_mail_text_from_payment_methods();" />
								</label>
								<br />
								<label>
								  <input name="button" type="button" value=" &lt;&lt;--" onclick="move_form_categories('payment_methods'); show_mail_text_from_payment_methods();" />
								</label>
								<span class="col_red">可拖动鼠标多选或按Ctrl+鼠标选择</span></td>
							  <td><label>
								  <?php
								  $payments_options = array();
								  if(tep_not_null($order->info['payment_methods'])){
								  	$_payment_methods = explode('<::>', $order->info['payment_methods']);
								  	foreach($_payment_methods as $key => $val){
								  		if(tep_not_null($_payment_methods[$key])){
								  			$payments_options[] = array('id'=> $_payment_methods[$key], 'text'=> $_payment_methods[$key]);
								  		}
								  	}
								  }
								  echo tep_draw_pull_down_menu('payment_methods[]',$payments_options,'', 'id="payment_methods" size="7" multiple="multiple" onclick="all_select_options(this)"');
								  ?>
								</label></td>
							</tr>
						  </table>
						</div>
						<div id="status_display_boxes_100115" class="StatusBoxesHide" title="系统会提前一天给客人发接机短信！">
							<ul>
							<li><label><input id="send_flight_sms_box" <?php echo (($order->info['need_pick_up_sms']==1) ? 'checked="checked"' : '');?> value="1" type="checkbox"/> 发送接机信息</label></li>
							<?php
							if($orders_need_pick_up_sms = PickUpSms::get_orders_need_pick_up_sms($oID)){
								foreach($orders_need_pick_up_sms as $_data){
									echo '<li>
									<label>接机日期：'.tep_draw_input_num_en_field('sms_date',date('Y-m-d H:i:s',strtotime($_data['send_date'])),'class="textTime" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;"').'</label>
									<label>接机供应商：'.tep_draw_input_num_en_field('sms_agency',$_data['agency_id'],'class="text"').'</label>
									<label>接收号码：'.tep_draw_input_num_en_field('sms_mobile',$_data['mobile_phone'],'class="text"').'</label></li>';
								}
							}else{
							?>
							<li>
							<label>接机日期：<input name="sms_date" class="textTime" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;" type="text" /></label>
							<label>接机供应商：<input name="sms_agency" class="text" type="text" /></label><label>接收号码：<input name="sms_mobile" class="text" type="text" /></label>
							</li>
							<?php }?>
							<li><button type="button">确定</button><a href="javascript:void(0);">增加++</a></li>
							</ul>
							<script type="text/javascript">
							// 添加发送接机信息功能处理
							(function(){
								var divID = '#status_display_boxes_100115 ';
								var ul = divID+' ul';
								var li0 = divID+' li:gt(0)';
								var eq1 = divID+' li:eq(1)';
								var btn = divID+' button';
								var a = divID+' a';
								var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=record_pick_up_sms')) ?>");
								//发送接机信息选择的动作
								jQuery('#send_flight_sms_box').click(function(){ if(this.checked == true){ jQuery(li0).show(); }else{ jQuery(li0).hide(); }; });
								//增加++的链接按钮动作
								jQuery(a).click(function(){ jQuery(eq1).clone().insertBefore(jQuery(ul+' li').last()); jQuery(ul+' li').last().prev('li').append('<img alt="close" src="images/icons/icon_x.gif" onclick="jQuery(this).parents(\'li\').remove();">').find('input').val(''); });
								//接送机短信信息提交
								jQuery(btn).click(function(){
									if(jQuery('#send_flight_sms_box').attr('checked') == true){
										var _date = divID + ' input[name="sms_date"]';
										var _agency = divID + ' input[name="sms_agency"]';
										var _mobile = divID + ' input[name="sms_mobile"]';
										var _i = 0;
										var d ={}, a={}, m = {};
										var willdo = true;
										jQuery(_date).each(function(i){
											if(this.value){
												_agency_value = jQuery(_agency +':eq('+ i +')').val();
												_mobile_value = jQuery(_mobile +':eq('+ i +')').val();
												if(_mobile_value.search(/^(\+1 [0-9]{10})|(\+86 [0-9]{11})$/)== -1){
													alert('手机号格式有误！');
													willdo = false;
												}
												if(_agency_value.search(/^<?php echo '('.implode(')|(',PickUpSms::$allows_agency).')'; ?>$/)== -1){
													alert('供应商只能是<?php echo implode(', ',PickUpSms::$allows_agency);?>之一！');
													willdo = false;
												}
												d[_i] = this.value;
												a[_i] = _agency_value;
												m[_i] = _mobile_value;
												_i++;
											};
										});
										
										if(willdo == true){	//无论有没有日期数据都记录接机信息
											jQuery(btn).attr('disabled',true);
											jQuery.post(url,{ send_dates:d, agency_ids:a, mobile_phones:m, need_send:'1', orders_id:<?= $oID;?> },function(text){ if(text=='ok'){ alert('接机信息添加成功'); } jQuery(btn).attr('disabled',false); },'text');
										}
									}
								});
							})(jQuery);
							</script>
						</div>
						<div id="status_display_boxes_100097" class="StatusBoxesHide">
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="CreditCard" style="margin-right:3px;" />
							Credit Card</label>
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="Paypal" style="margin-right:3px;" />
							Paypal</label>
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="Check" style="margin-right:3px;" />
							Check</label>
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="TFFCredit" style="margin-right:3px;" />
							TFF Credit</label>
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="ChinaBank" style="margin-right:3px;" />
							China Bank</label>
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="CashPayment" style="margin-right:3px;" />
							Cash Payment</label>
						  <label>
							<input onclick="show_mail_text_from_to_refund(this.value);" type="radio" name="RefundRadio" value="WireTransfer" style="margin-right:3px;" />
							Wire Transfer</label>
						  <br />
						  <label>退款金额(Refund Total)：<?php echo tep_draw_input_field('refund_total',$order->info['refund_total']);?></label>
						  <div id="TipsFor100097" style="color:#F00; padding:5px;"></div>
						</div>

						<script type="text/javascript">
						/* 自动显示退款金额到邮件内容 start */
						jQuery().ready(function(){
							var _eval = 'jQuery("input[name=\'RefundRadio\']").each(function(){ if(this.checked==true){ return show_mail_text_from_to_refund(this.value);} })';

							jQuery("input[name='refund_total']").blur(function(){
								eval(_eval);
							});
							jQuery("input[name='refund_total']").keydown(function(){
								eval(_eval);
							});
							jQuery("input[name='refund_total']").keyup(function(){
								eval(_eval);;
							});
						});
						/* 自动显示退款金额到邮件内容 end */

						var StatusDisplayBoxes = document.getElementById("status_display_boxes_<?= $order->info['orders_status']?>");
						if(StatusDisplayBoxes!=null){
							StatusDisplayBoxes.style.display = "block";
						}
						<?php
						//当Estimated Provider Cancellation Fee和Provider Cancellation Penalty两者金额不一致），则系统自动弹出提示框：请与Provider联系询问罚金原因，更改Status为Urgent：Information Needed from Provider [100085]
						$error_fee = 'Provider Cancellation Penalty和Estimated Provider Cancellation Fee两者金额不一致，请与Provider联系询问罚金原因！';
						if($order->info['orders_status'] == 100045 && (int)$order->info['provider_cancellation_penalty'] != (int)$order->info['provider_cancellation_fee_1'] ){
							/*echo 'alert("'.$error_fee.'");';
							echo '
							var ProviderCancellationPenalty = document.getElementById("provider_cancellation_penalty");
							if(ProviderCancellationPenalty!=null){
							ProviderCancellationPenalty.focus();
							ProviderCancellationPenalty.className = " col_red_b ";
							}';
							*/
							tep_db_query('update `orders` set orders_status ="100085", provider_cancellation_penalty = "0.00", last_modified = now() where orders_id="' . (int) $oID . '" ');
							tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)values ('" . tep_db_input($oID) . "', '100085', now(), 0, '".tep_db_input($error_fee)." System auto updated to 100085. Previously recorded cancellation fee was $".$order->info['provider_cancellation_fee_1'].", but now we are changed $".$order->info['provider_cancellation_penalty']." by provider. ',0)");
							//tep_redirect(tep_href_link('edit_orders.php','timetag='.time().'&action=edit&oID='.(int) $oID));
							echo '
								jQuery().ready(function(){
									location="'.tep_href_link('edit_orders.php','action=edit&oID='.(int) $oID).'"
								});
								';
						}
						?>

						function provider_cancellation_penalty_onBlur(){
							var ProviderCancellationFee1 =  document.getElementById("provider_cancellation_fee_1");
							var ProviderCancellationPenalty =  document.getElementById("provider_cancellation_penalty");
							if(parseInt(ProviderCancellationPenalty.value, 10)>0 && parseInt(ProviderCancellationFee1.value, 10) != parseInt(ProviderCancellationPenalty.value, 10) ){
								alert("<?= $error_fee;?>");
								var Comments = "<?= $error_fee;?>";
								try{
									document.edit_order.comments.value = Comments.replace(/\n/gi, "<br />");
									tinyMCE.updateContent('comments');
									tinyMCEformsaveIt();
								}catch(e){}
							}
						}

						//多选菜单用到的方法 start
						function add_to_class(from_id, to_id){
							var All_Class_Box = document.getElementById(from_id);
							for(j=0; j<All_Class_Box.length; j++){
								var Class_Box = document.getElementById(to_id);
								var s = Class_Box.length;
								if(All_Class_Box.options[j].selected == true){
									var ready_add_value = All_Class_Box.options[j].value;
									var ready_add_text = All_Class_Box.options[j].text;
									var add_action = true;
									for(i=0; i<Class_Box.length; i++){
										Class_Box.options[i].selected = true;
										if(ready_add_value == Class_Box.options[i].value){
											add_action = false;
										}
									}
									if(add_action==true && ready_add_value.length>0){
										Class_Box.options[s] = new Option(ready_add_text, ready_add_value);
										Class_Box.options[s].selected = true;
									}
								}
							}
						}

						function all_select_options(selectObj){
							for(var i=0; i<selectObj.length; i++){
								selectObj.options[i].selected = true;
							}
						}

						if(document.getElementById("payment_methods")!=null){
							all_select_options(document.getElementById("payment_methods"));
						}

						function move_form_categories(box_id){
							var Class_Box = document.getElementById(box_id);
							for(i=0; i<Class_Box.length; i++){
								if( Class_Box.options[i].selected ){
									Class_Box.remove(i);
									i--;
									//break;
								}
							}

						}

						function show_mail_text_from_payment_methods(){
							<?php
							//付款方式邮件内容
							include(DIR_WS_MODULES . 'edit_orders/payment_notice_mail_text.php');
							?>
							var Comments = "";
							var Select = document.getElementById("payment_methods");
							for(var i=0; i<Select.length; i++){
								title_text = '<b>'+Select.options[i].value+'</b><br />';
								if(Select.options[i].value== "信用卡（美元）"){
									Comments += title_text +'<?= preg_replace('/[[:space:]]+/',' ',nl2br(tep_db_input(MAIL_TEXT_CREDIT_CARD)));?>';
								}
								if(Select.options[i].value=="PayPal"){
									Comments += title_text +'<?= preg_replace('/[[:space:]]+/',' ',nl2br(tep_db_input(MAIL_TEXT_PAYPAL)));?>';
								}
								if(Select.options[i].value.indexOf("银行转账/现金存款")>-1){
									Comments += title_text +'<?= preg_replace('/[[:space:]]+/',' ',nl2br(tep_db_input(MAIL_TEXT_CASH_DEPOSIT)));?>';
								}
								if(Select.options[i].value.indexOf("银行电汇")>-1){
									Comments += title_text +'<?= preg_replace('/[[:space:]]+/',' ',nl2br(tep_db_input(MAIL_TEXT_WIRE_TRANSFER)));?>';
								}
								if(Select.options[i].value.indexOf("支票支付")>-1){
									Comments += title_text +'<?= preg_replace('/[[:space:]]+/',' ',nl2br(tep_db_input(MAIL_TEXT_CASHIER_CHECK)));?>';
								}
								if(Select.options[i].value.indexOf("银行转账")>-1){
									Comments += title_text +'<?= preg_replace('/[[:space:]]+/',' ',nl2br(tep_db_input(MAIL_TEXT_BANK_TRANSFER_CHINA)));?>';
								}
							}
							try{
								//document.edit_order.email_subject.value = Subject ? Subject : orderstatussubjectarray[document.edit_order.status.value];
								document.edit_order.comments.value = Comments.replace(/\n/gi, "<br />");
								tinyMCE.updateContent('comments');
								tinyMCEformsaveIt();
							}catch(e){}
						}
						//多选菜单用到的方法 end

						function show_mail_text_from_to_refund(radioValue){
							var TipsComments = "";
							switch(radioValue){
								case "CreditCard":
								TipsComments = "此项适用于初次支付使用信用卡的客人。\n 请注意卡片Charge Capture的时间，如果超过120天，则不能退换到原来的信用卡上面。请建议客人采用Paypal，或者美国支票，或者中国国内银行账号来完成退款。确认之后再使用此状态通知Accounting。";
								break;
								case "Paypal":
								TipsComments = "此项适用于初次支付使用Paypal的客人或者信用卡无法完成退款，请与客人核对Paypal账户地址（通常是邮件地址，初次支付使用Paypal的客人提交初次订单时候使用的Paypal账户为接受Paypal退款的账户。）";
								break;
								case "Check":
								TipsComments = "此项适用于除信用卡和Paypal支付方式的退款。请与客人核对邮寄地址和收款人，确认之后再使用此状态通知Accounting。";
								break;
								case "TFFCredit":
								TipsComments = "此项适用于客人愿意保留团费在走四方网账户。可以直接填入保留金额后由Accounting操作。";
								break;
								case "CashPayment":
								TipsComments = "此项适用于在团上委托Provider的Tour guide直接退现金给客人。";
								break;
								case "ChinaBank":
								TipsComments = "此项适用于退款到中国国内银行账户。请和客人确认以下信息后，再通知Accounting. \n 开户银行和城市（请注意如果是中国国内银行账户，务必准确填写分行名字）：\n 银行账号：\n 户名：";
								break;
								case "WireTransfer":
								TipsComments = "此项适用于国际电汇。一般不采用此退款方式，因为公司的费用高且费时。请先咨询上级再决定是否为最佳方式。请和客人确认以下信息后，再通知Accounting. \n Country: \n Bank name: \n BSS: \n Account number: \n Address: \n Phone number:";
								break;
								default: TipsComments = '';
							}
							jQuery('#TipsFor100097').html(TipsComments.replace(/\n/gi, "<br />"));
							var Comments = radioValue + ' 退款金额(Refund Total)：' +document.edit_order.refund_total.value;
							try{
								document.edit_order.comments.value = Comments.replace(/\n/gi, "<br />");
								tinyMCE.updateContent('comments');
								tinyMCEformsaveIt();
							}catch(e){}
						}
						</script>
						<?php
						/* Howard added 添加层级的状态供客服使用 end */
						?>
						<?php
						/* 旧的订单状态列表
						echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status'], ' class="order_option" onchange="check_status(this.value); return changesubjectemail_new(this); "');
						*/
						?>
						<?php echo tep_draw_hidden_field('previous_status', $order->info['orders_status']); ?> <?php echo tep_draw_hidden_field('last_visited_status', $order->info['orders_status']); ?></td>
					</tr>
					<tr>
					  <td colspan="2"><?php
					  if ($order->info['orders_status'] == '100021') {
					  	$dis_sty = '';
					  } else {
					  	$dis_sty = 'style="display:none"';
					  }
					  ?>
						<div class="order_canel" id="div_cancel_reason" <?php echo $dis_sty; ?>>
						  <table width="100%">
							<tr>
							  <td width="40%" class="order_default" valign="top" rowspan="2"><table width="100%">
								  <?php
								  //$get_cancel_reasons = explode(',', $order->info['cancel_reason'].',');
								  $cancel_item_orders_products_id = $order->products[0]['orders_products_id'];
								  if (sizeof($order->products) > 1) {
								  	for ($i = 0; $i < sizeof($order->products); $i++) {
								  		if ($i == 0) {
								  			$checked = ' checked="checked"';
								  			//$cancel_item_orders_products_id = $order->products[$i]['orders_products_id'];
								  		} else {
								  			$checked = '';
								  		}
								  ?>
								  <tr>
									<td class="order_default"><input type="radio" name="cancel_item" value="<?php echo $order->products[$i]['id']; ?>" <?php echo $checked; ?> onClick="set_cancel_item(<?php echo $order->products[$i]['orders_products_id']; ?>);" />&nbsp;<?php echo '<b>' . $order->products[$i]['model'] . '</b>&nbsp;' . $order->products[$i]['name'].'&nbsp;' . ($order->products[$i]['is_hotel']==1 ? '('.tep_date_short($order->products[$i]['products_departure_date']).')' : ''); ?></td>
								  </tr>
								  <?php
								  	}
								  } else {
								  ?>
								  <input type="hidden" name="cancel_item" value="<?php echo $order->products[0]['id']; ?>" />
								  <?php
								  }
								  ?>
								  <input type="hidden" name="cancel_item_orders_products_id" value="<?php echo $cancel_item_orders_products_id; ?>" />
								  <tr>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								  </tr>
								  <tr>
									<td class="order_default"><b>Reason for Cancellation:</b></td>
								  </tr>
								  <tr>
									<td class="order_default"><input type="checkbox" name="cancel_reason[]" value="Request by customer" <?php //if(in_array('Request by customer', $get_cancel_reasons)){ echo 'checked="checked"';}   ?> />
									  &nbsp;Per customer's request</td>
								  </tr>
								  <tr>
									<td class="order_default"><input type="checkbox" name="cancel_reason[]" value="reservation not available" <?php //if(in_array('reservation not available', $get_cancel_reasons)){ echo 'checked="checked"';}   ?> />
									  &nbsp;Reservation not available</td>
								  </tr>
								  <tr>
									<td class="order_default"><input type="checkbox" name="cancel_reason[]" value="Payment failed" <?php //if(in_array('Payment failed', $get_cancel_reasons)){ echo 'checked="checked"';}   ?> />
									  &nbsp;Payment failed</td>
								  </tr>
								  <tr>
									<td class="order_default"><input type="checkbox" name="cancel_reason[]" value="Place another order" <?php //if(in_array('Place another order', $get_cancel_reasons)){ echo 'checked="checked"';}   ?> />
									  &nbsp;Replacement reservation has been made.</td>
								  </tr>
								  <?php
								  /* $set_other_reason_checked = false;
								  $other_div_style=' style="display:none;"';
								  foreach($get_cancel_reasons as $key=>$val){
								  if(substr($val,0,6) == 'other-'){
								  $cancel_reason_other = substr($val,6);
								  $set_other_reason_checked = true;
								  $other_div_style='';
								  }
								  } */
								  ?>
								  <tr>
									<td class="order_default"><input type="checkbox" name="cancel_reason[]" value="other" <?php //if($set_other_reason_checked == true){ echo 'checked="checked"'; }   ?> onClick="toggel_div('cancel_reason_other_div');" />
									  &nbsp;Other reason&nbsp;<br />
									  <div id="cancel_reason_other_div" style="display:none; ">
										<input type="text" class="order_textbox" name="cancel_reason_other" value="<?php //echo $cancel_reason_other;   ?>" />
									  </div></td>
								  </tr>
								  <tr>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								  </tr>
								  <tr>
									<td class="order_default">Estimated Provider Cancellation Fee:
									  <input type="text" value="<?php //echo $order->info['cancellation_fee'];   ?>" name="provider_cancellation_fee" class="order_textbox" style="text-align:right; " size="5"/>
									  <small style="font-size:11px;color:#FF0000"><strong><br>
									  Note:</strong> If provider does not charge us, enter "0".  System will auto zero out cost to "0".  If >0 is entered, the amount entered will be recorded as cost.</small></td>
								  </tr>
								</table></td>
							  <td width="45%" valign="top" rowspan="2"><table width="75%">
								  <tr>
									<td class="order_default" colspan="2"><b>Customer's cancellation was received on: </b></td>
								  </tr>
								  <tr>
									<td class="order_default" colspan="2">
									<?php echo tep_draw_input_field('cancel_date', '', ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
									<script type="text/javascript">
									//var dateCancel = new ctlSpiffyCalendarBox("dateCancel", "edit_order", "cancel_date","btndateCancel","",scBTNMODE_CUSTOMBLUE);
									//dateCancel.writeControl(); dateCancel.dateFormat="MM/dd/yyyy";
									</script>
									</td>
								  </tr>
								  <tr>
									<td colspan="2" class="order_default"><b>Customer Cancellation Fee:</b></td>
								  </tr>
								  <?php
								  $get_order_total = tep_db_fetch_array(tep_db_query("select value,text from " . TABLE_ORDERS_TOTAL . " where class = 'ot_total' and orders_id = '" . $oID . "'"));
								  ?>
								  <tr>
									<td class="col_h" nowrap>Customer Payment: </td>
									<td align="right" class="order_default"><div id="customer_payment_cancel"><script type="text/javascript">document.write('$'+document.edit_order.elements['update_products['+<?php echo $order->products[0]['orders_products_id']; ?>+'][final_price]'].value);</script>
										<?php //echo $get_order_total['text'];   ?>
									  </div></td>
								  </tr>
								  <tr>
									<td class="col_h" nowrap>Cancellation fee: </td>
									<td align="right" class="order_default" nowrap>-$
									  <input type="text" value="<?php //echo $order->info['cancellation_fee'];   ?>" name="cancellation_fee" class="order_textbox" style="text-align:right; " size="5" onChange="get_return_amount(<?php echo $get_order_total['value']; ?>, this.value);"/></td>
									<td class="order_default">&nbsp; Or &nbsp;
									  <input type="text" value="" name="cancellation_fee_percent" class="order_textbox" style="text-align:right; " size="5" onChange="get_return_amount(<?php echo $get_order_total['value']; ?>, this.value, 'fee_percent');" />
									  %</td>
								  </tr>
								  <tr>
									<td class="col_h" nowrap>Refund Amount: </td>
									<td align="right" class="order_default"><div id="return_amount">$
										<?php
										//$return_amount = $get_order_total['value'] - $order->info['cancellation_fee']; echo number_format($return_amount,2);
										echo number_format($get_order_total['value'], 2);
										?>
									  </div></td>
								  </tr>
								  <tr>
									<td colspan="3" class="order_default"><span class="col_1">How did you calculate the cancellation fee? </span><br />
									  <textarea name="cancel_reason_detail" rows="2" cols="30" class="order_textarea"></textarea></td>
								  </tr>
								</table></td>
							  <td width="15%" valign="top"></td>
							</tr>
							<?php /* ?><tr><td align="right" valign="middle"><input type="submit" name="btnupdate" value="<?php echo IMAGE_UPDATE; ?>" class="but_4" /></td></tr><?php */ ?>
						  </table>
						</div>
						<?php
						if ($order->info['orders_status'] == '100080') {
							$dis_cre_sty = '';
						} else {
							$dis_cre_sty = 'style="display:none"';
						}
						?>
						<div class="order_canel" id="div_credit_reason"  <?php echo $dis_cre_sty; ?>>Credit issue table 已经移动到Charge Capture Information表格的内部，这里已经不用了。</div></td>
					</tr>
					<?php /* if(tep_not_null($order->info['cancellation_history'])){ ?>
						<tr>
						<td class="order_default"><a href="javascript:toggel_div('cancellation_history');" class="col_a1">View Cancellation History</a></td>
						</tr>
						<tr>
						<td class="order_default" colspan="2">
						<div id="cancellation_history" style="display:none; ">
						<?php if(tep_not_null($order->info['cancellation_history'])){ ?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="bor_tab table_td_p mar_t1">
						<tr>
						<td class="tab_t tab_line1" valign="top"><b>Reason for Cancellation</b></td>
						<td class="tab_t tab_line1" valign="top"><b>Did you cancel with provider?</b></td>
						<td class="tab_t tab_line1" valign="top"><b>Cancellation was received on</b></td>
						<td class="tab_t tab_line1" valign="top"><b>Cancellation Fee</b></td>
						<td class="tab_t tab_line1" valign="top"><b>Provider Cancellation Fee</b></td>
						<td class="tab_t tab_line1" valign="top"><b>How did you calculate the cancellation fee?</b></td>
						<td class="tab_t tab_line1" valign="top"><b>Updated by</b></td>
						</tr>
						<?php
						$cancellation_history_array = explode("||==||", $order->info['cancellation_history']);
						foreach($cancellation_history_array as $key=>$val){
						if(tep_not_null($val)){
						?>
						<tr>
						<?php
						$cancellation_history_values = explode("||", $val);
						?>
						<td class="tab_line1 p_l1" valign="top"><?php echo $cancellation_history_values[0]; ?>&nbsp;</td>
						<td class="tab_line1 p_l1" valign="top"><?php echo $cancellation_history_values[1]; ?>&nbsp;</td>
						<td class="tab_line1 p_l1" valign="top"><?php echo $cancellation_history_values[2]; ?>&nbsp;</td>
						<td class="tab_line1 p_l1" valign="top"><?php echo (tep_not_null($cancellation_history_values[3])? '$'.$cancellation_history_values[3]:''); ?>&nbsp;</td>
						<td class="tab_line1 p_l1" valign="top"><?php echo (tep_not_null($cancellation_history_values[4])?'$'.$cancellation_history_values[4]:''); ?>&nbsp;</td>
						<td class="tab_line1 p_l1" valign="top"><?php echo nl2br(stripslashes2($cancellation_history_values[5])); ?>&nbsp;</td>
						<td class="tab_line1 p_l1" valign="top"><?php echo $cancellation_history_values[6].' On '.$cancellation_history_values[7]; ?>&nbsp;</td>
						</tr>
						<?php
						}
						}
						?>
						</table>
						<?php } ?>
						</div>
						</td>
						</tr>
					<?php } */ ?>
					<tr>
					  <td class="order_default" valign="top"><b><?php echo TABLE_HEADING_COMMENTS; ?>:</b><br/>
						<span style="color:red"><b style="font-size:18px">注意：粘贴内容时先粘到“记事本”然后从记事本复制此处<br/>请不要直接从网页复制！！！</b><br/><br/>不要再在email中打尊称，<br/>因为系统默认加上尊称了</span></td>
					  <td><?php
					  echo tep_insert_html_editor('comments', 'customcomment', '300', '150');
					  if ($CommentsWithStatus) {
					  	echo tep_draw_textarea_field('comments', 'soft', '100', '10', '', ' class="textarea1" id="comments"');
					  } else {
					  	echo tep_draw_textarea_field('comments', 'soft', '100', '10', $order->info['comments'], ' class="textarea1" id="comments"');
					  }
					  ?></td>
					</tr>
					<tr>
					  <td></td>
					  <td class="order_default" height="35px">
					  <label>
					  <?php echo tep_draw_checkbox_field('notify', '', false, '','id="notify" onclick="checkStatusNotify()" '); ?> 邮件通知客人&nbsp; 
					  </label>
					  <?php //隐藏notify_comments元素?>
					  <div style="display:none">
					  <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?> &nbsp; 
					  <?php echo ENTRY_NOTIFY_COMMENTS; ?>
					  </div>
					  
						<?php
						//travel companion orders
						if ($is_travel_comp_order == true) {
						?>
						&nbsp; <label><?php echo tep_draw_checkbox_field('send_travel_companion_user', '1', false); ?> 通知此订单的结伴同游成员</label>
						<?php
						}
						//travel companion orders
						?>
						
						<?php 
						//指定下一位处理人复选框 start {
						echo '<label>'.tep_draw_checkbox_field('need_next_admin', '1', false, '', 'id="need_next_admin" onclick="needNextAdmin(this);" ').' 需要下一位客服继续处理该订单 </label>';
						echo '<script>jQuery(document).ready(function(e) { needNextAdmin(jQuery("#need_next_admin")); });</script>';
						$next_admin_id_options = array();
						$next_admin_id_options[] = array('id'=>'0','text'=>'选择下一步要操作此订单的人员');
						$next_admin_id_options = array_merge($next_admin_id_options, tep_get_admin_list("1,5,7,11,42,48,47,53,56"));	//普通客服、主管、副主管、操作员、财务、签证专员、财务助理
						echo '<span id="next_admin_urgency">';
						echo tep_draw_pull_down_menu('next_admin_id', $next_admin_id_options,$login_id);
						echo ' 紧急度 ';
						$_array_urgent = tep_get_need_next_urgency_name('all');
						$_m_array = array();
						foreach((array)$_array_urgent as $_key => $_values){
							$_m_array[] = array('id'=>$_key, 'text'=>strip_tags($_values));
						}
						echo tep_draw_pull_down_menu('need_next_urgency', $_m_array,$order->info['need_next_urgency']);
						echo '</span>';
						//指定下一位处理人复选框 end }
						
						//财务已经完成处理复选框 start {
						$_html_parameter = ' disabled="disabled" title="只有财务才能使用此复选框，您不是财务所以不能点它！" ';
						if($can_set_orders_accounting_todo_done === true){
							$_html_parameter = ' ';
						}
						echo "&nbsp;&nbsp;";
						echo '<label>'.tep_draw_checkbox_field('set_accounting_todo_done', '1', false, '', $_html_parameter).' 财务已完成本阶段的处理 </label>';
						//财务已经完成处理复选框 end }
						?>
						
					  </td>
					</tr>
					<tr >
					  <td></td>
					  <td class="order_default">
					  <div id="NotifyAccountingCheckboxDiv" style="display:none">
					  <!-- 
					  在To Charge Capture(100095) ,Need to check bank account(100072),状态下，增加"Notify China accounting to charge capture immediately?" 和"Notify US accounting to charge capture immediately?"两个勾选项，当勾选"Notify China accounting to charge capture immediately?"，update后发送邮件到payment@usitrip.com；当勾选"Notify US accounting to charge capture immediately?"，update后发送邮件到  payment@usitrip.com. 
					  -->
					  <?php echo tep_draw_checkbox_field('notifyAccountingCN', '1', false, '','id="notifyAccountingCN" '); ?> Notify China accounting to charge capture immediately? &nbsp; 
					  <?php echo tep_draw_checkbox_field('notifyAccountingUS', '1', false, '','id="notifyAccountingUS" '); ?> Notify US accounting to charge capture immediately?&nbsp; 
						</div>
					</td>
					</tr>
					<tr>
					  <td class="order_default"><b>Email Subject:</b></td>
					  <td class="order_default"><?php //echo tep_draw_input_field('email_subject', '', '80');  ?>
						<input type="text" name="email_subject" value="" size="60" class="order_text3"></td>
					</tr>
					<?php
					//vincent 订单避免重复更新管理优化 BEGIN
					$currentFollowTeam = tep_get_order_followup($oID);
					if($currentFollowTeam!= 0){
					?>
					<tr>
					  <td class="order_default"><b>Follow Up Team :</b></td>
					  <td class="order_default">
					  <script type="text/javascript">
					  function selectFollowTeam(teamId){
					  	var oldFollowUpTeam = <?php echo intval($currentFollowTeam);?>;
					  	for(i=1;i<=3;i++){
					  		var obj = document.getElementById("followup_team_type_"+i);
					  		var checked = (obj.checked == true );
					  		if(teamId == i){
					  			if(checked){
					  				document.getElementById("followup_team_type").value = teamId;
					  			}else{
					  				document.getElementById("followup_team_type").value = oldFollowUpTeam;
					  			}
					  		}else{
					  			obj.checked = false ;
					  		}
					  	}
					  }
					  </script>
					  <?php
					  $radios = array(
					  '1'=>' <input type="checkbox" id="followup_team_type_1"  value="1" onclick="selectFollowTeam(1)"> <img src="images/icons/us.png" align="absmiddle" alt="US follow up" title="US follow up" />US follow up',
					  '2'=>' <input type="checkbox" id="followup_team_type_2" value="2" onclick="selectFollowTeam(2)"> <img src="images/icons/cnds.png" align="absmiddle" alt="China Day Shift follow up" title="China Day Shift follow up" />China Day Shift follow up',
					  '3'=>' <input type="checkbox" id="followup_team_type_3" value="3" onclick="selectFollowTeam(3)"> <img src="images/icons/cnns.png" align="absmiddle" alt="China Night Shift follow up" title="China Night Shift follow up" />China Night Shift follow up'
					  );
					  $radios[$currentFollowTeam] = '<span style="display:none">'.$radios[$currentFollowTeam].'</span>';
					  //if($currentFollowTeam == 1) $radios[3] ='<span style="display:none">'.$radios[3].'</span>';
					  echo implode("&nbsp;" , $radios).'<input type="hidden" name="followup_team_type" id="followup_team_type"  value="'.$currentFollowTeam.'" />';
					  ?>
					  </td>
					</tr>
					<?php }
					//vincent 订单避免重复更新管理优化 END
					?>
					<tr>
					  <td class="order_default"></td>
					  <td><?php echo tep_draw_separator('pixel_trans.gif', '380', '1'); ?>
                      
						<input id="masterUpdateOrdersButton" type="submit" name="btnupdate" value="<?php echo '更新订单状态';#IMAGE_UPDATE; ?>" class="but_4_4" style="padding:6px"  onClick="return check_for_price_change('<?php echo $concat_string_ordprodid_tour_code; ?>');" /></td>
					</tr>
				  </table>
                 
                 <?php // 发送手机短信 开始 start { ?>
                 
                 <!---edit sms jason start--->
			  <?php
			  $guestname = explode(']', $orders_eticket_result['guest_name']);
			  $guestname = !empty($guestname) ? $guestname[0] : 'null'; ?>
			  <script language="javascript">
			  var osid = <?php echo $oID; ?>;
			  function zdysmss(obj){
			  	if($(obj).val() == 1){
			  		//结伴同游
			  		$("#zdysmscontent").val("");
			  		/*<?=HTTP_SERVER?>/companions_process.php*/
			  		$("#zdysmscontent").val("由于单人成房产生费用高于拼房，如您想节约出行费用，或邀人同行共享快乐旅程，您可以登陆“用户中心”发布结伴同游帖寻找同伴。");
			  	}else if ($(obj).val() == 2){
			  		//天气原因未能出行
			  		$("#zdysmscontent").val("");
			  		$("#zdysmscontent").val("很遗憾，由于近期天气不稳定，此次没能成功为您预订旅行。欢迎提供宝贵建议以改进我们的服务，衷心期待您的再次访问。祝您愉快！");
			  	}else if ($(obj).val() == 3){
			  		//扣款不成功
			  		$("#zdysmscontent").val("");
			  		$("#zdysmscontent").val("很遗憾，由于您的订单扣款不成功，此次没能成功为您预订旅行，请联系客服排除故障后重试。衷心期待您的再次访问。祝您愉快！");
			  	}else if ($(obj).val() == 4){
			  		//结伴同游订单预订错误
			  		$("#zdysmscontent").val("");
			  		$("#zdysmscontent").val("很遗憾，由于结伴同游订单预订出现错误，没能成功预订旅行，请联系客服排除故障后重试。衷心期待您的再次访问。祝您愉快！");
			  	}else if ($(obj).val() == 5){
			  		//用户自身原因
			  		$("#zdysmscontent").val("");
			  		$("#zdysmscontent").val("很遗憾，此次我们没能为您成功预订旅行，欢迎提供宝贵建议以改进我们的服务，并衷心期待您的再次访问。祝您愉快！");
			  	}else if ($(obj).val() == 0){
			  		//预留自定义
			  		$("#zdysmscontent").val("");
			  	}
			  }
			  //发送手机短信
			  function sendajaxsms(btn){
			  	if(!confirm("您确定要发短信吗？")){ return false; }
				var error = false;
				var error_msn = '';
				var content = $("#zdysmscontent").val();
				var phone = $("#zdysmsphone").val();
				if(content.length <= 5){
					error = true;
					error_msn = '内容不能小于5个字符';
				}else if(phone.length >0 && phone.search(/^(\+1 [0-9]{10})|(\+86 [0-9]{11})$/)== -1){
					error = true;
					error_msn = '电话号码格式有误！';
				}
				
				if(error === false){
			  		$(btn).attr('disabled', true);
					$.ajax({type:"post", url:"edit_orders.php?action=sendajaxsms", dataType:'json', data:'orders_id='+osid+'&phone='+ encodeURIComponent(phone) +'&content='+encodeURIComponent(content), success:function(json){ if(json['CN']=="1" || json['US']=="1"){alert('短信发送成功');}else{alert('短信发送失败，请检查网络是否正常，余额是否充足');} $(btn).attr('disabled', false); }});
			  	}else{
					alert(error_msn);
				}
			  }
			  </script>
              <table style="float:left;margin-left:20px;">
			  <tr>
				<td class="mar_t col_b2"><strong>Event Message:</strong></td>
			  </tr>
			  <tr>
				<td class="col_b1">手机号码：<input id="zdysmsphone" value="" title="格式：+国家代码 电话号码，如：+1 9145256865,+86 13509636116，若不输入电话号码则直接调订单里面的电话。系统将会记录您发送信息及您的工号！" /></td>
			  </tr>
			  <tr>
				<td class="col_b1"><select name="zdysms" onchange="zdysmss(this);">
					<option value="0">请选择主题 </option>
					<option value="1">结伴同游 </option>
					<option value="2">Cancelled--天气原因未能出行</option>
					<option value="3">Cancelled--扣款不成功</option>
					<option value="4">Cancelled--结伴同游订单预订错误</option>
					<option value="5">Cancelled--用户自身原因</option>
				  </select></td>
			  </tr>
			  <tr>
				<td class="col_b1">
				<textarea rows="10" id="zdysmscontent" cols="49" onblur="if(this.value.length > 120){ alert('字数不可超过120个字符，超长部分已经被截取！'); this.value=this.value.substr(0,120); }" ></textarea >
				</td>
			  </tr>
			  <tr>
				<td class="col_b1"><input type="button" value='发送手机短信' onclick="sendajaxsms(this);" style="float:right;padding:6px"/>&nbsp;&nbsp;&nbsp;<a href="order_msm_show.php?orders_id=<?=$_GET['oID']?>" target="_blank"><input type="button" value='查看订单短信记录' style="float:right;padding:6px"/></a></td>
			  </tr>
			  <!---edit sms jason end--->
              </table>
                 <?php // 发送手机短信 结束 end } ?>  
                </td>
			  </tr>
			  <?php
				}
				?>
                <?php
			  //Provider Information(供应商信息) start {
			  $n = sizeof($order->products);
			  if ($can_see_eticket_providers_info === true && $n > 0) {
			  ?>
			  <tr>
				<td class="mar_t col_b2"><strong><?php echo TXT_PROVIDER_INFO; ?></strong></td>
			  </tr>
			  <tr>
				<td><table cellpadding="0" cellspacing="0" border="0" class="bor_tab">
					<tr>
					  <td  valign="top" class="bg_c_all3"><table cellpadding="5" cellspacing="0" border="0" class="smallText">
						  <tr>
							<td class="tab_t tab_line1_wo_bot_border" height="27">&nbsp;</td>
						  </tr>
						  <tr>
							<td class="tab_line1_wo_bot_border bg_c_all3 p_l1" height="27"><strong><?php echo TXT_PRO_CONTACT_PERSON; ?></strong></td>
						  </tr>
						  <tr>
							<td class="tab_line1_wo_bot_border bg_c_all3 p_l1" height="27"><strong><?php echo TXT_PRO_COMPANY; ?></strong></td>
						  </tr>
						  <tr>
							<td class="tab_line1_wo_bot_border bg_c_all3 p_l1" height="27"><strong><?php echo TXT_PRO_TELEPHONE; ?></strong></td>
						  </tr>
                          <tr>
							<td class="tab_line1_wo_bot_border bg_c_all3 p_l1" height="27"><strong><?php echo TXT_PRO_CANCELLATION; ?></strong></td>
						  </tr>
                          <tr><td class="tab_line1_wo_bot_border bg_c_all3 p_l1" height="27" ><strong><?php echo TXT_PRO_NOTES;?></strong></td></tr>
                          
						</table></td>
					  <?php
					  for ($i = 0; $i < $n; $i++) {
					  	$qry_provider_info = "select a.agency_id, a.provider_cxln_policy,a.contactperson, a.agency_name, a.phone, p.products_id, p.products_model ,a.res_notes from " . TABLE_PRODUCTS . " as p, " . TABLE_TRAVEL_AGENCY . " as a where p.agency_id = a.agency_id AND p.products_id = '" . (int) $order->products[$i]['id'] . "'";
					  	$res_provider_info = tep_db_query($qry_provider_info);
					  	$row_provider_info = tep_db_fetch_array($res_provider_info);
					  ?>
					  <td  valign="top"><table cellpadding="5" cellspacing="0" border="0" class="smallText">
						  <tr>
							<td class="tab_t tab_line1_wo_bot_border" height="27"><strong><?php echo tep_db_prepare_input($row_provider_info['products_model']); ?></strong></td>
						  </tr>
						  <tr>
							<td class="bg_c_item p_l1 order_default" height="27"><?php echo tep_db_prepare_input($row_provider_info['contactperson']); ?></td>
						  </tr>
						  <tr>
							<td class="bg_c_item p_l1 order_default" height="27"><?php echo tep_db_prepare_input($row_provider_info['agency_name']); ?></td>
						  </tr>
						  <tr>
							<td class="bg_c_item p_l1 order_default" height="27"><?php echo tep_db_prepare_input($row_provider_info['phone']); ?></td>
						  </tr>
                          <tr>
							<td class="bg_c_item p_l1 order_default" height="27"><?php echo nl2br($row_provider_info['provider_cxln_policy']);?></td>
						  </tr>
                            <tr>
                                    <td class="bg_c_item p_l1 order_default bg_c_all3" height="27">
										<div id="responsediv<?php echo $order->products[$i]['id'];?>">	<?php echo nl2br(stripslashes($row_provider_info['res_notes']));?><div style="clear:both;"></div><div style="float:right"><a style="CURSOR: pointer" class="col_a1_popup" onclick="javascript:toggel_div_comment('div_comment_order_paid_payment_<?php echo $order->products[$i]['id'];?>','responsediv<?php echo $order->products[$i]['id'];?>');">Edit</a></div></div>
										
										<div id="div_comment_order_paid_payment_<?php echo $order->products[$i]['id'];?>" style="display:none">
												<table align="left" cellpadding="2" width="100%" cellspacing="0">
												<tr style="color:#FFFFFF;">
													<td align="left">
													 <?php 
													 $reserv_notes = str_replace(array("<br>","</br>","<br />"),"\r\n",$row_provider_info['res_notes'], $m);
													 echo tep_draw_textarea_field('add_payment_comment'.(int)$order->products[$i]['id'], 'soft', 60, 3, tep_db_prepare_input($reserv_notes), 'id="add_payment_comment'.(int)$order->products[$i]['id'].'"'); ?>
													 <br><input type="button" name="btn_add_comment" value="Update" onClick="ajax_add_comment(<?php echo $order->products[$i]['id'];?>,document.getElementById('add_payment_comment<?php echo $order->products[$i]['id']; ?>').value);" /><input type="button" name="btn_cancel" value="Cancel" onclick="toggel_div_comment('responsediv<?php echo $order->products[$i]['id'];?>','div_comment_order_paid_payment_<?php echo $order->products[$i]['id'];?>');" /></td>
												</tr>
												</table>
										</div>
                              </td>
                          </tr>
                          
						</table></td>
					  <?php } ?>
					</tr>
				  </table></td>
			  </tr>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <?php } 
			  //Provider Information(供应商信息) end }
			  ?>
                
                <?php
				if($login_groups_id == '11' || $login_groups_id=='1'){	//会计 Accountant和顶级管理员
			  ?>
			  

			  <tr>
				<td class="f_order1"><strong>Charge Captured Information</strong></td>
			  </tr>
			  <tr>
				<td><table cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
					<tr>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Charge Captured Date (PST Time)</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Payment Method</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Amount</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Reference Comment</strong></td>
					  <td class="tab_t tab_line1_wo_bot_border"><strong>Update By</strong></td>
					  <td class="tab_t"><strong>Modify Date</strong></td>
					</tr>
					<?php
					$ord_settle_prods_detail_history_sql = "select * from " . TABLE_ORDERS_SETTLEMENT_INFORMATION . " osi where orders_id ='" . (int) $oID . "' order by orders_settlement_id asc ";
					$ord_settle_prods_detail_history_query = tep_db_query($ord_settle_prods_detail_history_sql);
					while ($ord_settle_prods_detail_history_row = tep_db_fetch_array($ord_settle_prods_detail_history_query)) {
					?>
					<tr>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo $ord_settle_prods_detail_history_row['settlement_date']; ?></td>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo $ord_settle_prods_detail_history_row['orders_payment_method']; ?></td>
					  <td class="tab_line1_wo_bot_border p_l1" align="right"><?php
					  if ($ord_settle_prods_detail_history_row['order_value'] < 0) {
					  	echo '<span class="errorText">' . number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', '') . '</span>';
					  } else {
					  	echo number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', '');
					  }
					  ?>
						&nbsp;</td>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo tep_db_prepare_input(nl2br($ord_settle_prods_detail_history_row['reference_comments'])); ?></td>
					  <td class="tab_line1_wo_bot_border p_l1"><?php echo tep_get_admin_customer_name($ord_settle_prods_detail_history_row['updated_by']); ?></td>
					  <td class="order_default p_l1"><?php echo $ord_settle_prods_detail_history_row['date_added']; ?></td>
					</tr>
					<?php } ?>
				  </table></td>
			  </tr>
			  <tr>
				<td class="order_default"><b><?php echo ENTRY_STATUS; ?></b>&nbsp;<?php echo tep_get_orders_status_name($order->info['orders_status']); ?></td>
			  </tr>
			  <tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			  </tr>
			  <?php
}
			  ?>
		    </table>
			  </form>
			
		  <tr>
				<td><a name="orders_travel_comp"></a>
				  <?php
				  // howard added
				  include('orders_travel_comp.php');
				  ?></td>
		  </tr>
			  <?php
			  //结伴同游快速连接1
			  if ($is_travel_comp_order == true) {
			  ?>
			  <tr>
				<td><br>
				  <br>
				  <a href="javascript:void(0);" onclick="showPopup('jbtyList','jbtyList_popupCon');"><u><b><font size='3'>T/C Payment Details</font></b></u></a></td>
			  </tr>
			  <?php
			  }
			  ?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td><div class="next_line"></div></td>
			  </tr>
			  <?php
			  //amit added for settlement history
			  include('orders_settlement_history.php');
			  //amit added for settlement history
			  ?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td colspan="2" align="center" class="order_foot"><?php
				//检查管理员是否发送及用户是否收到Invoice邮件
				$invoice_sql = tep_db_query('SELECT o.`invoice_issendmail`,o.`invoice_sendtime`,
								e.email_track_id,a.`admin_firstname`,a.`admin_lastname` 
								FROM ' . TABLE_ORDERS . ' o 
								left join `admin` a on o.`invoice_sendadmin`=a.`admin_id`
								left join `email_track` e on o.orders_id=e.key_id AND e.key_field="orders_id" AND  e.email_type="Invoice"
								WHERE o.orders_id="' . (int) $oID . '"');
				while ($invrow = tep_db_fetch_array($invoice_sql)) {
					if ($invrow['invoice_issendmail'] == '1') {
						echo '<b style="color: #f00; margin-left:100px;">[' . $invrow['admin_lastname'] . ' ' . $invrow['admin_firstname'] . ']已发送Invoice邮件！</b> <span style="color: #ccc;">' . $invrow['invoice_sendtime'] . '</span>';
						if ((int) $invrow['email_track_id']) {
							echo '<span style="color: #00CC00;">(用户已收到！)</span>';
						}
						echo "<br><br>";
					}
				}
				//Begin Paypal IPN V2.8 DMG (I improvised here.)
				if($can_edit_invoice === true){
					if (strtolower($order->info['payment_method']) == 'paypal' && isset($HTTP_GET_VARS['referer']) && $HTTP_GET_VARS['referer'] == 'ipn') {
						echo '<a href="javascript:popupWindow1(\'' . (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_INVOICE) . '?' . (tep_get_all_get_params(array('oID', 'fudate')) . 'oID=' . $HTTP_GET_VARS['oID']) . '\')">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="' . tep_href_link(FILENAME_PAYPAL, tep_get_all_get_params(array('action', 'fudate'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
					} else { //not paypal
						echo '<a href="javascript:popupWindow1(\'' . (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_INVOICE) . '?' . (tep_get_all_get_params(array('oID', 'fudate')) . 'oID=' . $HTTP_GET_VARS['oID']) . '\')">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action', 'fudate'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
					}  //end PapPal IPN V2.8
				}
				?>
				</td>
			  </tr>
			  <?php
			  echo '<tr><td>';
			  for ($i = 0; $i < sizeof($order->products); $i++) {
			  	//howard added 判断是否是组合团 start
			  	$have_sub_tours = false;
			  	if (is_array(get_provider_tour_code_sub((int) $order->products[$i]['id']))) {
			  		$have_sub_tours = true;
			  	}
			  	//howard added 判断是否是组合团 end

			  	$orders_products_id = $order->products[$i]['orders_products_id'];
			  	//invoice popup 显示发票层表单 start
			  	if ($access_full_edit == 'true' || $can_view_invoice == true) {
					$save_onclick = 'alert(\'对不起，您无权更新发票信息！\');';
					$_readonly = ' readonly="readonly" ';
					if($can_edit_invoice === true){
						$_readonly = '';
						$save_onclick = 'sendFormData(\'edit_order_invoice_' . $i . '\',\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=update_invoice_popup&i=' . $i) . '\', \'travel_companion_tips_responce_inv_' . $i . '\',true,\'Not_refreshtrue\',\'' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . (int) $oID) . '&fudate=i' . date('mdyhis') . $i . '#show_eticket\',\'run_click_button(&quot;Cost_Retail_Nearest_Botton_' . $i . '&quot;)\');';
					}
					
					$travel_companion_tips_inv_body_con = "";
			  		$travel_companion_tips_inv_body_con.= tep_draw_form('edit_order_invoice_' . $i, FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=update_invoice_popup&i=' . $i, 'post', 'id="edit_order_invoice_' . $i . '"') . tep_draw_hidden_field('orders_products_id[' . $i . ']', (int) $orders_products_id) . '<div class="order_tc1_content"><p><span class="col_b1"></span></p><table width="60%"><tr>';
			  		$travel_companion_tips_inv_body_con.= '<td class="tab_t tab_line1" >' . TEABLE_HEAIDNG_INVOICE . '</td><td class="tab_t tab_line1" >' . TABLE_HEADING_COMMENTS . '</td><td class="tab_t tab_line1" >供应商上传的发票文件[<a href="'.tep_href_link('upload_invoices.php','orders_id='.$oID.'&products_id='.(int)$order->products[$i]['id'].'&orders_products_id='.$orders_products_id).'" target="_blank">上传发票</a>]</td>';
			  		$travel_companion_tips_inv_body_con.= '</tr><tr>';
			  		
			  			$_customer_invoice_files = explode(';',$order->products[$i]['customer_invoice_files']);
						$_links_invoice_files = '';
						foreach((array)$_customer_invoice_files as $ival){
							if(tep_not_null($ival)){
								$_links_invoice_files.= '<a href="/images/invoices/'.$ival.'" target="_blank">'.$ival.'</a><br>';
							}
						}
						$travel_companion_tips_inv_body_con.= '	  <td class="' . $RowStyle . '"  valign="top">
						<table>
						<tr>
						<td class="dataTableContent">No.</td>
						<td>' . "<input name='update_products[$orders_products_id][previous_invoice_number]' type='hidden' value='" . $order->products[$i]['invoice_number'] . "'><input name='update_products[$orders_products_id][invoice_number]' size='8' value='" . $order->products[$i]['invoice_number'] . "' ".$_readonly." class='order_textbox'>" . '</td>
						</tr>
						<tr>
						<td class="dataTableContent">Amt</td>
						<td>
						' . "<input name='update_products[$orders_products_id][previous_invoice_amount]' type='hidden' value='" . $order->products[$i]['invoice_amount'] . "'><input name='update_products[$orders_products_id][invoice_amount]' size='8' value='" . $order->products[$i]['invoice_amount'] . "' ".$_readonly." class='order_textbox'>" . '
						</td>
						</tr>
						</table></td>
						<td class="' . $RowStyle . '"  valign="top">
						<span class="smallText">Invoice Comment</span><br/>
						' . tep_draw_textarea_field("update_products[$orders_products_id][invoice_comments_invoice]", "soft", "25", "3", "", "class='order_textarea'".$_readonly) . '
						</td>
						<td class="' . $RowStyle . '"  valign="top">
						<span class="smallText">'.$_links_invoice_files.'&nbsp;</span>
						</td>
						';
			  		

			  		$travel_companion_tips_inv_body_con.= '</tr>';
					
					
			  		$travel_companion_tips_inv_body_con.= '<tr><td align="right"><input type="button" name="btnsave" class="but_4" value="Save" onclick="'.$save_onclick.'" /></td><td align="left"><div class="but_4 mar_l" onclick="javascript: show_travel_companion_tips(0,\'inv_' . $i . '\');" align="center">Cancel</div></td></tr>';
			  		$travel_companion_tips_inv_body_con.= '</table><div id="travel_companion_tips_responce_inv_' . $i . '"></div></form>';
			  		echo str_replace('[IIIIIIIIIIIIIIIIIIIIIIIIIIIIII]', $travel_companion_tips_inv_body_con, $invoice_layers[$i]);
			  	}
			  	//invoice popup 显示发票层表单 end

			  	//eticket info popup -start
			  	$eticket_contents = "";
			  	$eticket_contents.= tep_draw_form('edit_order_flight_' . $i, FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=update_eticket_popup&i=' . $i, 'post', 'id="edit_order_flight_' . $i . '"') . tep_draw_hidden_field('products_id[' . $i . ']', (int) $order->products[$i]['id']) .tep_draw_hidden_field('orders_products_id['.$i.']', (int)$order->products[$i]['orders_products_id']). '<div class="order_tc1_content"><span class="order_default"><b><a href="javascript: show_div(\'flight_info_div_' . $i . '\');" class="order_default">Flight Information</a></b></span>';
			  	$orders_history_check_blank_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where orders_products_id=".(int)$order->products[$i]['orders_products_id']." and (airline_name != '' or airline_name_departure != '' or flight_no != '' or flight_no_departure != '' or airport_name != '' or airport_name_departure != '') ");
			  	if (tep_db_num_rows($orders_history_check_blank_query) > 0) {
			  		$flight_info_div_style = '';
			  	} else {
			  		$flight_info_div_style = ' style="display:none;"';
			  		$eticket_contents.= ' &nbsp; <a href="javascript: toggel_div(\'flight_info_div_' . $i . '\');">(Show)</a>';
			  	}
			  	$eticket_contents.= '<div id="flight_info_div_' . $i . '" ' . $flight_info_div_style . '><table width="100%"><tr><td height="5"></td></tr><tr><td><span class="col_h">(Flight Information is applicable only if your tour itinerary includes airport transfer on Day 1 of the tour.)</span></td></tr>';
			  	$orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where orders_products_id = '".(int)$order->products[$i]['orders_products_id']."' ");
			  	// orders_id = '" . tep_db_input($oID) . "' and products_id=".(int)$order->products[$i]['id']."
			  	if (tep_db_num_rows($orders_history_query)) {
			  		while ($orders_history = tep_db_fetch_array($orders_history_query)) {
			  			$eticket_contents.= '<tr>
						<td class="main" colspan="2">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
						<td width="120" class="col_h">Arrival Airline Name</td>
						<td>' . tep_draw_input_field('airline_name[' . $i . ']', $orders_history['airline_name'], ' class="order_text2"') . '</td>
						<td width="140" class="col_h">Departure Airline Name</td>
						<td>' . tep_draw_input_field('airline_name_departure[' . $i . ']', $orders_history['airline_name_departure'], ' class="order_text2"') . '</td>
						</tr>
						<tr>
						<td class="col_h">Arrival Flight Number</td>
						<td>' . tep_draw_input_field('flight_no[' . $i . ']', $orders_history['flight_no'], ' class="order_text2"') . '</td>
						<td class="col_h">Departure Flight Number</td>
						<td>' . tep_draw_input_field('flight_no_departure[' . $i . ']', $orders_history['flight_no_departure'], ' class="order_text2"') . '</td>
						</tr>
						<tr>
						<td class="col_h">Arrival Airport Name</td>
						<td>' . tep_draw_input_field('airport_name[' . $i . ']', $orders_history['airport_name'], ' class="order_text2"') . '</td>
						<td class="col_h">Departure Airport Name</td>
						<td>' . tep_draw_input_field('airport_name_departure[' . $i . ']', $orders_history['airport_name_departure'], ' class="order_text2"') . '</td>
						</tr>
						<tr>
						<td class="col_h">Arrival Date</td>
						<td class="col_h">' . tep_draw_input_field('arrival_date[' . $i . ']', tep_get_date_disp($orders_history['arrival_date']), ' class="order_text2"') . ' e.g. 09/27/2010</td>
						<td class="col_h">Departure Date</td>
						<td class="col_h">' . tep_draw_input_field('departure_date[' . $i . ']', tep_get_date_disp($orders_history['departure_date']), ' class="order_text2"') . ' e.g. 09/30/2010</td>
						</tr>
						<tr>
						<td class="col_h">Arrival Time</td>
						<td class="col_h">' . tep_draw_input_field('arrival_time[' . $i . ']', $orders_history['arrival_time'], ' class="order_text2"') . ' e.g. 09:30 am</td>
						<td class="col_h">Departure Time</td>
						<td class="col_h">' . tep_draw_input_field('departure_time[' . $i . ']', $orders_history['departure_time'], ' class="order_text2"') . ' e.g. 08:30 pm</td>
						</tr>
						</table>
						</td>
						</tr>';
			  		}// end of while loop
			  	}//end of if
			  	$eticket_contents.= '</table></div>';
			  	$orders_eticket_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ETICKET ." where orders_products_id = '".(int)$order->products[$i]['orders_products_id']."'"); //orders_id = '" . (int)$oID . "' and products_id=".(int)$order->products[$i]['id']."

			  	$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
			  	if ($orders_eticket_result['depature_full_address'] != '') {
			  		$depature_full_address = str_replace("&nbsp;", " ", $orders_eticket_result['depature_full_address']);
			  	} else {
			  		$orders_fulladdress_query = tep_db_query("select * from products_departure where departure_time = '" . $order->products[$i]['products_departure_time'] . "' and departure_address = '" . tep_db_prepare_input(tep_db_input($order->products[$i]['products_departure_location'])) . "' and products_id = " . (int) $order->products[$i]['id'] . " ");
			  		$orders_fulladdress = tep_db_fetch_array($orders_fulladdress_query);
			  		$depature_full_address = $order->products[$i]['products_departure_date'] . '  ' . $order->products[$i]['products_departure_time'] . '  ' . $orders_fulladdress['departure_full_address'];
			  	}

			  	if ($orders_eticket_result['special_note'] == '') {
			  		$special_query = tep_db_query("select products_special_note, products_vacation_package from " . TABLE_PRODUCTS . "  where products_id = '" . (int) $order->products[$i]['id'] . "' ");
			  		$special_result = tep_db_fetch_array($special_query);
			  		$special_note = $special_result['products_special_note'];
			  	} else {
			  		$special_note = $orders_eticket_result['special_note'];
			  	}
			  	//当前行程的供应商信息 start {
				$the_customers_id = $order->customer['id']; //$the_extra['customers_id'];
			  	$agency_query = tep_db_query("select a.*,p.products_vacation_package from " . TABLE_PRODUCTS . " as p, " . TABLE_TRAVEL_AGENCY . " as a where p.products_id = '" . (int) $order->products[$i]['id'] . "' and p.agency_id = a.agency_id ");
			  	$agency_result = tep_db_fetch_array($agency_query);
			  	//当前行程的供应商信息 end }
				
				if ($orders_eticket_result['eticket_comment'] == '') {
			  		$eticket_comment = $agency_result['providers_default_eticket_comment'];
			  	} else {
			  		$eticket_comment = $orders_eticket_result['eticket_comment'];
			  	}
				

			  	//Howard added sub_tour_agency_info start
			  	if ($orders_eticket_result['sub_tour_agency_info'] == '') {
			  		$provider_tour_code_sub = get_provider_tour_code_sub((int) $order->products[$i]['id']);
			  		if (is_array($provider_tour_code_sub)) {
			  			$p_sql = tep_db_query("select agency_id, products_model_sub from " . TABLE_PRODUCTS . " where products_id = '" . (int) $order->products[$i]['id'] . "'  ");
			  			$p_row = tep_db_fetch_array($p_sql);
			  			$provider_ids = array();
			  			$provider_ids[0] = $p_row['agency_id'];
			  			$products_model_sub_array = explode(';', $p_row['products_model_sub']);
			  			foreach ((array) $products_model_sub_array as $val) {
			  				$tmp_sql = tep_db_query("select agency_id from " . TABLE_PRODUCTS . " where products_model = '" . $val . "' ");
			  				while ($row = tep_db_fetch_array($tmp_sql)) {
			  					if ((int) $row['agency_id'] && !in_array($row['agency_id'], $provider_ids)) {
			  						$provider_ids[] = $row['agency_id'];
			  					}
			  				}
			  			}

			  			$provider_ids_string = implode(",", $provider_ids);
			  			$sub_tour_agency_info = NULL;
			  			$sub_agency_query = tep_db_query("select * from " . TABLE_TRAVEL_AGENCY . " where agency_id IN(" . $provider_ids_string . ") ");
			  			while ($sub_agency_result = tep_db_fetch_array($sub_agency_query)) {
			  				$sub_tour_agency_info .= TXT_DATE_ETICKIT . '[XXXX-XX-XX]' . '	' . TXT_ETICKET_AGENCY_CMPANY_NAME . "<b>" . $sub_agency_result['agency_name'] . '</b>	' . TXT_ETICKET_TEL . $sub_agency_result['phone'] . '	' . TXT_ETICKET_EMERGENCY_CONTACT_PERSON . $sub_agency_result['emerency_contactperson'] . '	' . TXT_ETICKET_EMERGENCY_CONTACT_NUMBER . $sub_agency_result['emerency_number'] . "\n\n";
			  			}
			  		}
			  	} else {
			  		$sub_tour_agency_info = $orders_eticket_result['sub_tour_agency_info'];
			  	}
			  	//Howard added sub_tour_agency_info end

			  	if ($special_result['products_vacation_package'] == '1') {
			  		if ($display_eticket_itinerary_new_format == true) {
			  			$special_note_itinerary = 'If you will self transfer to hotel on day one, please connect with customer service dept or local tour contact to re-confirm hotel information three days prior to departure date.';
			  		}
			  	}

			  	/* $the_extra_query= tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
			  	$the_extra= tep_db_fetch_array($the_extra_query);
			  	*/
			  	$arr_highlight_agency_details = array(33, 67, 68, 3, 35, 36);
			  	$style_highlight_agency = '';
			  	$SentAdminInfo = $orders_eticket_result['sent_administrator_info'];
			  	$SentAdminInfo = explode("<::>", $SentAdminInfo);
			  	$SentAdminInfo_str = '';
			  	foreach ($SentAdminInfo as $key => $val) {
			  		$tmps = array();
			  		if (tep_not_null($val)) {
			  			$tmps = explode('|', $val);
			  			$SentAdminInfo_str .= tep_get_admin_customer_name($tmps[0]) . "&nbsp;" . $tmps[1] . "&nbsp;&nbsp;";
			  		}
			  	}
			  	//hotel-extension begin
			  	//电子参团凭证加上酒店信息
			  	if((int)$order->products[$i]['is_hotel']==1){
			  		$eticket_content_part= '<p class="order_default"><b>Check-in date:</b> <input type="text" value="'.stripslashes($depature_full_address).'" class="order_text7" name="depature_full_address['.$i.']"  />'.tep_draw_hidden_field('previous_depature_full_address['.$i.']', stripslashes($depature_full_address)).' &nbsp; &nbsp; <b>Check-out date:</b> <input type="text" value="'.$order->products[$i]['hotel_checkout_date'].'" class="order_text7" name="hotel_checkout_date['.$i.']"  />'.tep_draw_hidden_field('previous_hotel_checkout_date['.$i.']', $order->products[$i]['hotel_checkout_date']).'</p>';
			  	}else  if((int)$order->products[$i]['is_transfer']==1){
			  		//接驳车服务 ，仅显示只读信息
			  		$eticket_content_part= '<p class="order_default"><b>Transfer Service Information:</b> <br/>'.db_to_html(tep_transfer_display_route($order->products[$i]['transfer_info_arr'])).db_to_html('<span style="color:red">(要修改接驳服务信息请在订单编辑的【接驳车服务】处修改后Update即可生效)</span>').'</p>';
			  	}else{
			  		$eticket_content_part='<p class="order_default"><b>Tour Start Date and Location:</b> <input type="text" value="' . stripslashes2($depature_full_address) . '" class="order_text6" name="depature_full_address[' . $i . ']" id="depature_full_address_' . $i . '" onBlur="ComparisonDateAddress(this.id,&quot;old_depature_full_address_' . $i . '&quot;);linkageForDate(this.id);" /><input type="hidden" value="' . stripslashes2($depature_full_address) . '" id="old_depature_full_address_' . $i . '" name="old_depature_full_address[' . $i . ']"  /></p>';
			  	}
			  	//hotel-extension end

			  	if (tep_not_null($SentAdminInfo_str)) {
			  		$eticket_contents.= '<p class="order_default" style="color:#F00; padding-top:5px; padding-bottom:5px;"><b>Sent history:</b> ' . $SentAdminInfo_str . ' </p>';
			  	}
			  	//$eticket_contents.= '<p class="order_default"><b>Tour Start Date and Location:</b> <input type="text" value="' . stripslashes2($depature_full_address) . '" class="order_text6" name="depature_full_address[' . $i . ']" id="depature_full_address_' . $i . '" onBlur="ComparisonDateAddress(this.id,&quot;old_depature_full_address_' . $i . '&quot;)" /><input type="hidden" value="' . stripslashes2($depature_full_address) . '" id="old_depature_full_address_' . $i . '" name="old_depature_full_address[' . $i . ']"  /></p><p class="order_default"><b>Guest Cell Phone (Emergency Only): </b>' . tep_draw_input_field('customers_cellphone', tep_customers_cellphone($the_customers_id), '', 'class="order_textbox"') . tep_draw_hidden_field('customers_id', $the_customers_id, '') . '</p>';
			  	//$eticket_contents.= '<p class="order_default"><b>Tour Start Date and Location:</b> <input type="text" value="' . stripslashes2($depature_full_address) . '" class="order_text6" name="depature_full_address[' . $i . ']" id="depature_full_address_' . $i . '" onBlur="ComparisonDateAddress(this.id,&quot;old_depature_full_address_' . $i . '&quot;);linkageForDate(this.id);" /><input type="hidden" value="' . stripslashes2($depature_full_address) . '" id="old_depature_full_address_' . $i . '" name="old_depature_full_address[' . $i . ']"  /></p>';
			  	$eticket_contents.=$eticket_content_part;
				//var_dump($can_see_user_it_cell_phone);
				$temp='';
				if(!$can_see_user_it_cell_phone && $order->info['orders_paid']<1){ $temp =' style="display:none"'; }
				if(!$can_see_user_it_cell_phone){ $temp .=' readonly="readonly" '; }
				
				$_customers_cellphone = $order->info['guest_emergency_cell_phone'] ? $order->info['guest_emergency_cell_phone'] : tep_customers_cellphone($the_customers_id);
			  	$eticket_contents.='<p class="order_default"><b>Guest Cell Phone (Emergency Only): </b>' . tep_draw_input_field('customers_cellphone['.$i.']', $_customers_cellphone, $temp. 'class="order_textbox" size="50"') . tep_draw_hidden_field('customers_cellphone_old['.$i.']', $_customers_cellphone) . tep_draw_hidden_field('customers_id', $the_customers_id, '') . '<span '.$temp.' sensitive="true">手机号须带国家代码！如：+86 13509636116, +1-626-456-7890</span></p>';

			  	if($orders_eticket_result[tour_provider] != ''){
			  		$full_tour_provider = explode('Tel:', $orders_eticket_result[tour_provider]);
			  		if(trim($full_tour_provider[1]) == ''){
			  			$full_tour_provider = explode(TXT_ETICKET_TEL, $orders_eticket_result[tour_provider]);
			  		}
			  		$orders_eticket_result[tour_provider] = trim($full_tour_provider[0]);
			  		if(trim($full_tour_provider[1]) != ''){
			  			$orders_eticket_result[local_operator_phone] = trim($full_tour_provider[1]);
			  		}
			  	}
			  	
				//以下内容只有主管以上人员可看start {
				$_tmp_style = 'display:none;';
				if($can_see_eticket_providers_info === true){
					$_tmp_style = '';
				}
				$eticket_contents.= '<div style="'.$_tmp_style.'">';
				if ($have_sub_tours == false) {
			  		$eticket_contents.= '<p class="order_default"><b>Local Tour Contact: </b><input type="text" name="tourprovider" value="' . ($orders_eticket_result[tour_provider] != '' ? stripslashes2($orders_eticket_result[tour_provider]) : $agency_result[agency_name]) . '" size="50"/></p><p class="order_default"><b>Local Operator Phone No.: </b><input type="text" name="local_operator_phone" value="'. ($orders_eticket_result[local_operator_phone] != ''? stripslashes($orders_eticket_result[local_operator_phone]):$agency_result[local_operator_phone]).'" size="50"/></p>';
			  		//.' Tel: '.$agency_result[phone]';
			  	}

			  	if ($display_eticket_itinerary_new_format == true && $forceelase == true) {
			  		$eticket_contents.= '<input type="hidden" name="emergency_contact_person" value="';
			  		if ($orders_eticket_result['emergency_contact_person'] != '')
			  		$eticket_contents.= stripslashes2($orders_eticket_result['emergency_contact_person']); else
			  		$eticket_contents.= stripslashes2($agency_result['emerency_contactperson']);
			  		$eticket_contents.= '" /><input type="hidden" name="emergency_contact_no" value="';
			  		if ($orders_eticket_result['emergency_contact_no'] != '')
			  		$eticket_contents.= stripslashes2($orders_eticket_result['emergency_contact_no']); else
			  		$eticket_contents.= stripslashes2($agency_result['emerency_number']);
			  		$eticket_contents.= '" />';
			  	}else {
			  		$eticket_contents.= '<p class="order_default"><b>Emergency Contact Person : </b><input type="text" name="emergency_contact_person" value="';
			  		if ($orders_eticket_result['emergency_contact_person'] != '')
			  		$eticket_contents.= stripslashes2($orders_eticket_result['emergency_contact_person']); else
			  		$eticket_contents.= stripslashes2($agency_result['emerency_contactperson']);
			  		$eticket_contents.= '" size="20"/> &nbsp; &nbsp; &nbsp; <b>Emergency Contact Number:</b><input type="text" name="emergency_contact_no" value="';
			  		if ($orders_eticket_result['emergency_contact_no'] != '')
			  		$eticket_contents.= stripslashes2($orders_eticket_result['emergency_contact_no']); else
			  		$eticket_contents.= stripslashes2($agency_result['emerency_number']);
			  		$eticket_contents.= '" size="20"/></p>';
			  	}
				$eticket_contents.= '</div>';
				//以下内容只有主管以上人员可看end }
			  	//hotel-extension

			  	$_etCommentsTitle = "Suggested Tour Itinerary";
			  	if((int)$order->products[$i]['is_hotel']==1){
			  		//hotel-extension
			  		//酒店产品Notice
			  		$_etCommentsTitle = "Comments";
			  	}

				
				$_customer_confirmation_no_readonly = ' readonly="readonly" title="此框您能看不能写" ';
				if($can_edit_eticket_itinerary === true){
					$_customer_confirmation_no_readonly = '';
				}
				$eticket_contents.= '<p class="order_default"><b>Confirmation #: </b><input type="text" value="'.$order->products[$i]['customer_confirmation_no'].'" '.$_customer_confirmation_no_readonly.' class="order_text7" name="customer_confirmation_no['.$i.']"  /></p>';
				
				$eticket_contents.= '<p></p><p class="order_default mar_t"><b>'.$_etCommentsTitle.'</b> </p>';

			  	$tour_arrangement = $orders_eticket_result['tour_arrangement'];
				
				if(!tep_not_null($orders_eticket_result['tour_arrangement_providers']) && tep_not_null(preg_replace('/[[:space:]]+/','',strip_tags($tour_arrangement)))){ //自动填充供应商的tour_arrangement_providers值
					tep_db_query('update '.TABLE_ORDERS_PRODUCTS_ETICKET.' set tour_arrangement_providers = tour_arrangement where orders_eticket_id="'.$orders_eticket_result['orders_eticket_id'].'" ');
					//写tour_arrangement_providers历史
					tep_db_query(
					'INSERT INTO orders_product_eticket_tour_arrangement_providers_log 
					    ( `orders_eticket_id`, `tour_arrangement_providers`, `last_modified`, `providers_agency_id`) 
					 SELECT 
					    ope.orders_eticket_id as orders_eticket_id, ope.tour_arrangement_providers as tour_arrangement_providers, "'.date('Y-m-d H:i:s').'" as last_modified, "0" as providers_agency_id  
					 FROM '.TABLE_ORDERS_PRODUCTS_ETICKET.' as ope WHERE ope.orders_eticket_id ="'.(int)$orders_eticket_result['orders_eticket_id'].'";
					');
				}
				
				$tour_arrangement_providers = $orders_eticket_result['tour_arrangement_providers'];
				
			  	//howard added for notes start
			  	//不含$uninludes中的产品
			  	if ($agency_result['products_vacation_package']) {
			  		$notes_expand = '';
			  	} else {
			  		$notes_expand = "<strong>请您在出发前一天致电该团地接社作最后确认。</strong><br />";
			  	}
			  	//howard added for notes end

			  	//关于tour_arrangement。如果是酒店产品它默认是空的，用于给客服人员填写Comments
			  	if ($orders_eticket_result['tour_arrangement'] == '' && (int)$order->products[$i]['is_hotel']!=1) {
					
					if ($display_eticket_itinerary_new_format == true && $forceelase == true) {
					$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($order->products[$i]['id'], $languages_id);
					if(!tep_not_null($suggested_tour_eticket_itinerary)){
						//如果产品模板没有电子参团凭证模板就用旧站模板
						$exra_auto_row_load_eticke = tep_get_eticket_old_tpl_for_usitrip((int)$order->products[$i]['id']);
					}else{
						$suggested_tour_itinerary_date = $order->products[$i]['products_departure_date'];
						$suggested_tour_itinerary_pick_up_time = $order->products[$i]['products_departure_time'] . ' ' . $order->products[$i]['products_departure_location'];
						$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($order->products[$i]['id'], $languages_id);
						$suggested_tour_eticket_notes = $special_note_itinerary . '<br /><br />' . tep_get_products_eticket_notes($order->products[$i]['id'], $languages_id);
						$suggested_tour_eticket_local_contact = "";
						if ($orders_eticket_result['tour_provider'] != '') {
							$suggested_tour_eticket_local_contact.=stripslashes2($orders_eticket_result['tour_provider']);
						} else {
							$suggested_tour_eticket_local_contact.=$agency_result['agency_name'] . ' Tel: ' . $agency_result['phone'];
						}
						$suggested_tour_eticket_local_contact.="<br><br>Emergency Contact  Person:<br>";
						if ($orders_eticket_result['emergency_contact_person'] != '') {
							$suggested_tour_eticket_local_contact.=stripslashes2($orders_eticket_result['emergency_contact_person']);
						} else {
							$suggested_tour_eticket_local_contact.=stripslashes2($agency_result['emerency_contactperson']);
						}
						$suggested_tour_eticket_local_contact.="<br><br>Emergency Contact Number:<br>";
						if ($orders_eticket_result['emergency_contact_no'] != '') {
							$suggested_tour_eticket_local_contact.=stripslashes2($orders_eticket_result['emergency_contact_no']);
						} else {
							$suggested_tour_eticket_local_contact.=stripslashes2($agency_result['emerency_number']);
						}
						$suggested_tour_eticket_local_contact_arr_explode = explode('!##!', $suggested_tour_eticket_local_contact);
						$suggested_tour_eticket_itinerary_arr_explode = explode('!##!', $suggested_tour_eticket_itinerary);
						$suggested_tour_eticket_hotel_arr_explode = explode('!##!', $suggested_tour_eticket_hotel);
						$suggested_tour_eticket_notes_arr_explode = explode('!##!', $suggested_tour_eticket_notes);
						$exra_auto_row_load_eticke = '';
						for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {
							if ($lp_i > 0) {
								$suggested_tour_itinerary_pick_up_time = '';
								$eticket_notes_string = $suggested_tour_eticket_notes_arr_explode[$lp_i];
							} else {
								$eticket_notes_string = $notes_expand . str_replace($notes_expand, '', $suggested_tour_eticket_notes_arr_explode[$lp_i]);
							}
							$exra_auto_row_load_eticke .= '<TR valign="top">
								<TD>' . date_add_day($lp_i, 'd', $suggested_tour_itinerary_date) . '<br /><br />' . $suggested_tour_itinerary_pick_up_time . '</TD>
								<TD>' . $suggested_tour_eticket_local_contact_arr_explode[$lp_i] . '</TD>
								<TD>' . $suggested_tour_eticket_itinerary_arr_explode[$lp_i] . '</TD>
								<TD>' . $suggested_tour_eticket_hotel_arr_explode[$lp_i] . '</TD>
								<TD><span style="color:#F7860F; font-weight: bold;">' . $eticket_notes_string . '</span></TD></TR>';
						}
					}
					
				} else {
					
					$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($order->products[$i]['id'], $languages_id);
					if(!tep_not_null($suggested_tour_eticket_itinerary)){
						//如果产品模板没有电子参团凭证模板就用旧站模板
						$exra_auto_row_load_eticke = tep_get_eticket_old_tpl_for_usitrip((int)$order->products[$i]['id']);
					}else{
						$suggested_tour_itinerary_date = tep_get_date_disp($order->products[$i]['products_departure_date']);
						$suggested_tour_itinerary_pick_up_time = $order->products[$i]['products_departure_time'] . ' ' . $order->products[$i]['products_departure_location'];
						if (in_array($agency_result['agency_id'], $arr_highlight_agency_details)) {
							$style_highlight_agency = 'style="font-weight:bold; font-size:15px; color:#000000;"';
							$suggested_tour_itinerary_pick_up_time = '<span ' . $style_highlight_agency . ' >' . $order->products[$i]['products_departure_time'] . ' ' . $order->products[$i]['products_departure_location'] . '</span>';
						}
						$suggested_tour_eticket_pickup_details = tep_get_products_eticket_pickup($order->products[$i]['id'], $languages_id);
						$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($order->products[$i]['id'], $languages_id);
						
						$suggested_tour_eticket_notes = tep_get_products_eticket_notes($order->products[$i]['id'], $languages_id);
						$suggested_tour_eticket_itinerary_arr_explode = explode('!##!', $suggested_tour_eticket_itinerary);
						$suggested_tour_eticket_pickup_details_arr_explode = explode('!##!', $suggested_tour_eticket_pickup_details);
						$suggested_tour_eticket_hotel_arr_explode = explode('!##!', $suggested_tour_eticket_hotel);
						$suggested_tour_eticket_notes_arr_explode = explode('!##!', $suggested_tour_eticket_notes);
						$exra_auto_row_load_eticke = '';
						for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {
							if ($lp_i > 0) {
								$suggested_tour_itinerary_pick_up_time = '';
								$eticket_notes_string = $suggested_tour_eticket_notes_arr_explode[$lp_i];
							} else {
								$eticket_notes_string = $notes_expand . str_replace($notes_expand, '', $suggested_tour_eticket_notes_arr_explode[$lp_i]);
							}
							$exra_auto_row_load_eticke .= '
								<tr>
								<td style="padding-top:10px">
								<table width="100%" border="0" cellpadding="0" cellspacing="0" >
								<tr><td colspan="2" bgcolor="#F7F7F7" height="25" style="padding:5px 10px"><b>' . sprintf(TXT_DAY_ETICKIT, ($lp_i + 1)) .' '. $suggested_tour_eticket_itinerary_arr_explode[$lp_i] .'</b></td></tr>
								';
							/* 具体行程日期，已被取消
							$exra_auto_row_load_eticke .= '<tr><td colspan="2"><table width="100%"><tr><td width="65" height="25">' . TXT_DATE_ETICKIT . ':</td>
								<td width="80%" height="25">' . date_add_day($lp_i, 'd', $suggested_tour_itinerary_date) . '&nbsp;</td></tr></table></td>
								</tr>'; */
							if(tep_not_null($suggested_tour_eticket_hotel_arr_explode[$lp_i])){	//酒店信息
								$exra_auto_row_load_eticke .= '<tr><td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="65" height="25">' . TXT_ETICKET_HOTEL . '</td>
							<td height="25">' . $suggested_tour_eticket_hotel_arr_explode[$lp_i] . '&nbsp;</td></tr></table></td></tr>';
							}
							if(tep_not_null($suggested_tour_eticket_pickup_details_arr_explode[$lp_i].$suggested_tour_eticket_pickup_details_arr_explode[$lp_i])){	//上车信息
								$exra_auto_row_load_eticke .= '<tr><td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="65" height="25">' . TXT_ETICKET_PICK_UP_DETAILS . '</td><td height="25" valign="top">' . ($suggested_tour_itinerary_pick_up_time != '' ? $suggested_tour_itinerary_pick_up_time . '<br />' . $suggested_tour_eticket_pickup_details_arr_explode[$lp_i] : $suggested_tour_eticket_pickup_details_arr_explode[$lp_i]) . '</td></tr></table></td></tr>';
							}
							/* 备注的内容已被取消
							if(tep_not_null($eticket_notes_string)){
								$exra_auto_row_load_eticke .= '
							<tr><td colspan="2"><table width="100%"><tr><td width="65" height="25">' . TXT_ETICKET_NOTE . '</td>
							<td width="80%" height="25"><span style="color:#F7860F; font-weight: bold;">'.$eticket_notes_string.'&nbsp;</span></td></tr></table></td></tr>
							';
							}*/
							
							$exra_auto_row_load_eticke .=
							'</table>
							</td>
							</tr>';
						} // end for loop
					}
				}

			  		if(tep_not_null($exra_auto_row_load_eticke)){ $tour_arrangement = '<TABLE width="100%" border="0">' . $exra_auto_row_load_eticke . '</TABLE>'; }
			  		
			  	}
			  	//echo $tour_arrangement;
			  	/*  Panda added 2011-07-05 13:29在 $tour_arrangement 加上 标示符用来联动识别 START*/
			  	if (preg_match('/<div id="tour_arrangement">/',$tour_arrangement)){
			  		$tour_arrangement = $tour_arrangement;
			  	}else{
			  		$tour_arrangement = '<div id="tour_arrangement">'.$tour_arrangement.'</div>';
			  	}
			  	/*  Panda added 2011-07-05 13:29在 $tour_arrangement 加上 标示符用来联动识别 END*/


			  	//销售7不能修改电子凭证行程内容以下的内容
				$_textarea_readonly = ' readonly="readonly" ';
				if($can_edit_eticket_itinerary == true){
					$eticket_contents.= tep_insert_html_editor('tour_arrangement[' . $i . ']', 'advanced', '500');
					$_textarea_readonly = '';
				}
				
				$eticket_contents.= tep_draw_textarea_field('tour_arrangement[' . $i . ']', '', '130', '30', stripslashes2($tour_arrangement), $_textarea_readonly);

			  	$eticket_contents.= '<div style="display:none;">'.
			  	'<p></p><p class="order_default mar_t"><b>'.$_etCommentsTitle.'[旧值，用于在提交时判断要不要添加电子票历史记录]</b> </p>'.
				tep_insert_html_editor('previous_tour_arrangement['.$i.']','customcomment','200').
			  	tep_draw_textarea_field('previous_tour_arrangement['.$i.']', '', '130', '50', stripslashes2($tour_arrangement)).'
				 </div>';
			  	
				//供应商给我们返回的行程信息
				if($tour_arrangement_providers!=""){
					$tour_arrangement_providers_history = '';
					$hsql = tep_db_query('SELECT * FROM `orders_product_eticket_tour_arrangement_providers_log` WHERE orders_eticket_id="'.(int)$orders_eticket_result['orders_eticket_id'].'" order By orders_product_eticket_tour_arrangement_providers_log_id DESC ');
					$hrows = tep_db_fetch_array($hsql);
					if((int)$hrows['orders_product_eticket_tour_arrangement_providers_log_id']){
						$tour_arrangement_providers_history.= '
						<table class="dataTableContent" style="width:100%;" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="3"><hr /></td>
						</tr>
						<tr>
							<th scope="col">序号</th>
							<th scope="col">历史行程内容</th>
							
						</tr>
						';
						//<th scope="col">修改日期</th><td valign="top"></td>
						$sort_num = 0;
						$tour_arrangement_providers_history_tr = '';
						do{
							$sort_num++;
							//$_tour_arrangement = getHtmlTagsContent($hrows['tour_arrangement_providers'],'gb2312','div','id','tour_arrangement');
							$_tour_arrangement = $hrows['tour_arrangement_providers'];
							
							$tour_arrangement_providers_history_tr.='
								<tr '.((int)$hrows['providers_agency_id']<1 ? 'title="系统自动添加的，供应商没有添加这条记录！'.$hrows['orders_product_eticket_tour_arrangement_providers_log_id'].'"':'' ).'>
									<td valign="top" nowrap="nowrap">'.($sort_num==1 ? '最后一次更新' : $sort_num ).'<br />'.$hrows['last_modified'].'<br /> by : '.$hrows['providers_email_address'].'</td>
									<td valign="top">'.stripslashes($_tour_arrangement).'</td>
									
								</tr>
								<tr>
									<td colspan="3"><hr /></td>
								</tr>
								';
						}while($hrows = tep_db_fetch_array($hsql));
						$tour_arrangement_providers_history .= $tour_arrangement_providers_history_tr.'</table>';
					}
				
					$eticket_contents.= '<div>'.
					'<p></p><p class="order_default mar_t"><b style="color:red;">供应商给我们填写的行程信息<a href="javascript:void(0)" onclick="$(\'#tour_arrangement_providers_'.$i.'\').toggle();">[显示/隐藏]</a></b></p>'.
					'<div id="tour_arrangement_providers_'.$i.'" style="display:none">'.stripslashes2($tour_arrangement_providers).'</div>';
					if($tour_arrangement_providers_history!=''){
						$eticket_contents.= '<div><a href="javascript:void(0);"  onclick="$(\'#tour_arrangement_providers_history_'.$i.'\').toggle();">[查看供应商行程更新历史]</a></div>';
						$eticket_contents.= '<div id="tour_arrangement_providers_history_'.$i.'" class="tourArrangementProvidersHistory" style="display:none">'.$tour_arrangement_providers_history.'</div>';
					}
					$eticket_contents.= '</div>';
				}

			  	//howard added start
			  	//自动在温馨提示添加一些文字
			  	$main_special_note = 
				'1、"参团凭证"有您出团的详细资讯、紧急联络电话等，请务必打印并携带"参团凭证"及有效身份证件，并在参团时出示给导游。
2、请仔细核实"参团凭证"上所有信息，如有问题，请尽快联系我们。 "参团凭证"发出后在48小时之内，仍未收到您的异议，将被认为您已经认可其上所有信息正确。在此之后，因信息错误导致的后果，我公司不承担任何责任。
3、您有责任阅读所有旅行团相关信息，包括∶价格、行程、 团费包含/不含、注意事项以及取消和退款政策、订购协议中的各项条款等。收到此"参团凭证"，我们将认为您已经阅读和同意上述所及条款。
4、旅行团会尽量保证行程安排与计划。地接社保留对由于天气、交通、旅行游览车临时发生故障和其它一些不可抗力原因对行程更改、推迟和取消的权利。
5、请注意行程的先后顺序可能会根据您的参团日期不同有所调整，行程内容不变。为了保证您的行程顺利进行，若您需要提前在行程中的某个地点离团，请务必在预订时留言告知我们，以便确认是否可以安排。
6、您的订单费用中不包含个人旅游保险，为了避免因一些特殊情况给您造成的损失（航班取消、行李丢失等），建议您自行购买旅游保险，详细可参考：' . tep_catalog_href_link('insurance.php');
				
				if (tep_not_null($main_special_note) && strpos($special_note, '1、"参团凭证"有您出团的详细资讯、紧急联络电话等，请务必打印并携带"参团凭证"及有效身份证件，并在参团时出示给导游。') === false) {
			  		$special_note = $main_special_note."\n".str_replace($main_special_note, '', $special_note);
			  		tep_db_query("update " . TABLE_ORDERS_PRODUCTS_ETICKET . " set special_note='" . tep_db_input($special_note) . "', eticket_comment='".tep_db_input($eticket_comment)."', tour_arrangement='" . tep_db_input($tour_arrangement) . "' where orders_id = " . (int) $oID . " and products_id = " . $order->products[$i]['id'] . " ");
			  	}

			  	//howard added end
			  	/* Panda added  for 联动修改日期 Start*/
			  	$eticket_contents .= '<input type="hidden" name="panda_old_date" id="panda_old_date" value=""/>';
			  	/* Panda added  for 联动修改日期 End*/
			  	$eticket_contents.= '<div class="col_1 mar_t1">参团须知:</div>' . tep_draw_textarea_field('eticket_comment[' . $i . ']', 'soft', '80', '5', stripslashes2($eticket_comment), 'class="textarea3 mar_t1"'.$_textarea_readonly);
			  	$eticket_contents.= '<div class="col_1 mar_t1">温馨提示:</div>' . tep_draw_textarea_field('special_note[' . $i . ']', 'soft', '80', '3', stripslashes2($special_note), 'class="textarea3 mar_t1"'.$_textarea_readonly);
			  	if ($have_sub_tours == true) {
			  		//$eticket_contents.= '<div class="col_1 mar_t1">' . TITLE_SUB_CONTACT_INFO . '</div>' . tep_draw_textarea_field('sub_tour_agency_info[' . $i . ']', 'soft', '80', '3', stripslashes2($sub_tour_agency_info), 'class="textarea3 mar_t1" ');
			  	}

			  	/*echo '<table width="100%"><tr><td width="50%" align="right"><input type="button" name="btnsave" class="but_4" value="Save" onclick="tinyMCEformsaveIt(); sendFormData(\'edit_order_flight_'.$i.'\',\''. tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action','fudate')) . 'action=update_eticket_popup&i='.$i.'&oID='.(int)$oID).'\', \'travel_companion_tips_responce_'.$i.'\',true,\'refreshtrue\',\''.tep_href_link(FILENAME_EDIT_ORDERS, 'oID='.(int)$oID).'&fudate=f'.date('mdyhis').$i.'#show_eticket\');" /></td><td align="left" width="30%"><a href="javascript: show_travel_companion_tips(0,'.$i.');"><div class="but_4" align="center">Cancel</div></a></td><td align="right" width="20%"><a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($order->products[$i]['id']) . '&pID=' . $order->products[$i]['id'].'&action=new_product&tabedit=eticket') . '"><div class="but_3_toolong order_default" align="center">Update E-Ticket Template</div></a></td></tr></table>'; //T4F*/

			  	$eticket_contents.= '<table width="100%"><tr><td width="50%" align="right"><input type="button" name="btnsave" class="but_4" value="Save" onclick="tinyMCEformsaveIt(); sendFormData(\'edit_order_flight_' . $i . '\',\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'fudate')) . 'action=update_eticket_popup&i=' . $i . '&oID=' . (int) $oID . '&opID=' . (int) $orders_products_id) . '\', \'travel_companion_tips_responce_' . $i . '\',true,\'refreshtrue\',\'' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . (int) $oID) . '&fudate=f' . date('mdyhis') . $i . '#show_eticket\');" /></td><td align="left" width="30%"><a href="javascript: show_travel_companion_tips(0,' . $i . ');"><div class="but_4" align="center">Cancel</div></a></td><td align="right" width="20%">&nbsp;</td></tr></table><div id="travel_companion_tips_responce_' . $i . '"></div></form>';
			  	echo str_replace('[XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]', $eticket_contents, $eticket_layers[$i]);
			  	//eticket info popup - end
			  }
			  echo '</td></tr>';
}

if ($action == "add_product") {
			  ?>
			  <tr>
				<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
					  <td class="pageHeading"><?php echo ADDING_TITLE; ?> #<?php echo $oID; ?></td>
					  <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					  <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, 'action=edit&' . tep_get_all_get_params(array('action', 'step', 'fudate'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
					</tr>
				  </table></td>
			  </tr>
			  <?php
			  // ############################################################################
			  //   Get List of All Products
			  // ############################################################################
			  $result = tep_db_query("SELECT products_name, p.products_id, cd.categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc, " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.categories_id=ptc.categories_id and ptc.products_id=p.products_id and p.products_id=pd.products_id and pd.language_id = '" . (int) $languages_id . "' ORDER BY cd.categories_name");
			  while ($row = tep_db_fetch_array($result)) {
			  	extract($row, EXTR_PREFIX_ALL, "db");
			  	$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
			  	$CategoryList[$db_categories_id] = $db_categories_name;
			  	$LastCategory = $db_categories_name;
			  }
			  // ksort($ProductList);
			  $LastOptionTag = "";
			  $ProductSelectOptions = "<option value='0'>Don't Add New Product" . $LastOptionTag . "\n";
			  $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			  foreach ($ProductList as $Category => $Products) {
			  	$ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
			  	$ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
			  	asort($Products);
			  	foreach ($Products as $Product_ID => $Product_Name) {
			  		$ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
			  	}
			  	if ($Category != $LastCategory) {
			  		$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			  		$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			  	}
			  }
			  // ############################################################################
			  //   Add Products Steps
			  // ############################################################################
			  print "<tr><td><table border='0'>\n";
			  // Set Defaults
			  if (!IsSet($add_product_categories_id))
			  $add_product_categories_id = 0;

			  if (!IsSet($add_product_products_id))
			  $add_product_products_id = 0;
			  /*
			  // Step 1: Choose Category
			  print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  print "<td class='dataTableContent' align='right'><b><?php echo TEXT_ADD_PROD_STEP1 ?></b></td><td class='dataTableContent' valign='top'>";
			  $tree = tep_get_category_tree();
			  $dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
			  echo $dropdown;
			  // print "<select name='add_product_categories_id'>\n";
			  // $CategoryOptions = "<option value='0'> TEXT_ADD_CAT_CHOOSE ";
			  // foreach($CategoryList as $CategoryID => $CategoryName)
			  // {
			  // $CategoryOptions .= "<option value='$CategoryID'> $CategoryName\n";
			  // }
			  $CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
			  print $CategoryOptions;
			  print "</td>\n";
			  print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_CAT . "'>";
			  print "<input type='hidden' name='step' value='2'>";
			  print "</td>\n";
			  print "</form></tr>\n";
			  print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
			  // Step 2: Choose Product
			  if(($step > 1) && ($add_product_categories_id > 0))
			  {
			  print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  print "<td class='dataTableContent' align='right'><b><?php echo TEXT_ADD_STEP2 ?></b></td><td class='dataTableContent' valign='top'><select name='add_product_products_id'>";
			  $ProductOptions = "<option value='0'> " . TEXT_ADD_PROD_CHOOSE;
			  asort($ProductList[$add_product_categories_id]);
			  foreach($ProductList[$add_product_categories_id] as $ProductID => $ProductName)
			  {
			  $ProductOptions .= "<option value='$ProductID'> $ProductName\n";
			  }
			  $ProductOptions = str_replace("value='$add_product_products_id'","value='$add_product_products_id' selected", $ProductOptions);
			  print $ProductOptions;
			  print "</select></td>\n";
			  print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_PROD . "'>";
			  print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			  print "<input type='hidden' name='step' value='3'>";
			  print "</td>\n";
			  print "</form></tr>\n";

			  print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
			  }
			  */
			  // Step 1: Choose Category
			  /*
			  print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  print "<td class='dataTableContent' align='right'><b><?php echo TEXT_ADD_PROD_STEP1 ?></b></td><td class='dataTableContent' valign='top'>";
			  $tree = tep_get_category_tree();
			  $dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
			  echo $dropdown;
			  $CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
			  print $CategoryOptions;
			  print "</td>";
			  print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_CAT . "'>";
			  print " <input type='hidden' name='step' value='2'>";
			  print "</td>";
			  print "</form></tr>";
			  */
			  print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_PROD_STEP1 . "</b></td>
<td class='dataTableContent' valign='top'>";
			  //$tree = tep_get_category_tree();
			  //$dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
			  //echo $dropdown;

			  $product_model_select_array = array();
			  $product_model_select_array[] = array('id' => '', 'text' => '-- Select Tour Code --');
			  $product_model_sel_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and   p.is_hotel=0 and p.is_transfer = 0  and pd.language_id = '" . (int) $languages_id . "' and p.products_model!='' and p.agency_id !='" . GLOBUS_AGENCY_ID . "' order by p.products_id ");
			  while ($product_model_sel_row = tep_db_fetch_array($product_model_sel_sql)) {
			  	$tour_code_last_digits = explode("-", $product_model_sel_row['products_model']);
			  	$product_model_select_array[] = array('id' => $product_model_sel_row['products_model'], 'text' => $tour_code_last_digits[1] . ' [' . $product_model_sel_row['products_name'] . ']');
			  }
			  // howard add fixed bugs {
			  echo 'Enter products model '.tep_draw_input_field('add_product_categories_id',$add_product_categories_id,' id="add_product_categories_id" ').' Or ';
			  if($add_product_categories_id=="0"){
			  	$add_product_categories_id ="";
			  }
			  // howard add fixed bugs }

			  echo tep_draw_pull_down_menu('_add_product_categories_id', $product_model_select_array, $add_product_categories_id,'onchange="jQuery(\'#add_product_categories_id\').val(this.value)"');
			  //hotel-extension begin
			  $hotel_model_select_array = array();
			  $hotel_model_select_array [] = array('id' => '', 'text' => '-- Select Hotel Code --');
			  $product_model_sel_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and p.is_hotel=1 and pd.language_id = '" . (int)$languages_id . "' and p.agency_id !='".GLOBUS_AGENCY_ID."' order by p.products_id ");
			  while($product_model_sel_row = tep_db_fetch_array($product_model_sel_sql))
			  {
			  	$tour_code_last_digits = explode("-", $product_model_sel_row['products_model']);
			  	$hotel_model_select_array [] = array('id' => $product_model_sel_row['products_model'], 'text' => $tour_code_last_digits[1].' ['.$product_model_sel_row['products_name'].']');
			  }
			  echo '<br />Or<br />';
			  echo tep_draw_pull_down_menu('_add_product_categories_id', $hotel_model_select_array  , $add_product_categories_id,'onchange="jQuery(\'#add_product_categories_id\').val(this.value)"');
			  //hotel-extension end
			  //transfer-service {
			  $transfer_model_select_array = array();
			  $transfer_model_select_array [] = array('id' => '', 'text' => '-- Select Transfer Code --');
			  $product_model_sel_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and p.is_transfer=1 and pd.language_id = '" . (int)$languages_id . "' and p.agency_id !='".GLOBUS_AGENCY_ID."' order by p.products_id ");
			  while($product_model_sel_row = tep_db_fetch_array($product_model_sel_sql))
			  {
			  	$tour_code_last_digits = explode("-", $product_model_sel_row['products_model']);
			  	$transfer_model_select_array [] = array('id' => $product_model_sel_row['products_model'], 'text' => $tour_code_last_digits[1].' ['.$product_model_sel_row['products_name'].']');
			  }
			  echo '<br />Or<br />';
			  echo tep_draw_pull_down_menu('_add_product_categories_id', $transfer_model_select_array  , $add_product_categories_id,'onchange="jQuery(\'#add_product_categories_id\').val(this.value)"');
			  //} //end transfer selector

			  //echo tep_draw_input_field('add_product_categories_id',$add_product_categories_id);
			  //$CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
			  //print $CategoryOptions;
			  print "</td>";
			  print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_TOUR_CODE . "'>";
			  print "<input type='hidden' name='step' value='2'>";
			  print "</td>";
			  print "</form></tr>";
			  print "<tr><td colspan='3'>&nbsp;</td></tr>";
			  if (isset($add_product_categories_id) && $add_product_categories_id != '') {
			  	$check_prod_model_sql = tep_db_query("SELECT products_name, p.products_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and products_model ='" . $add_product_categories_id . "' and pd.language_id = '" . (int) $languages_id . "'");
			  	while ($check_prod_model_row = tep_db_fetch_array($check_prod_model_sql)) {
			  		$valid_product_model = 'true';
			  		$ProductName = $check_prod_model_row['products_name'];
			  		$add_product_products_id = $check_prod_model_row['products_id'];
			  	}
			  }
			  // Step 2: Choose Product
			  if (($step > 1) && $valid_product_model == 'true') {
			  	print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>";
			  	print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP2 . "</b></td>
<td class='dataTableContent' valign='top'>";
			  	echo tep_draw_input_field('add_product_name', $ProductName, ' size="60" readonly  class="order_textbox"');
			  	print "</td>";
			  	print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_PROD . "'>";
			  	print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			  	print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			  	print "<input type='hidden' name='step' value='3'>";
			  	print "</td>";
			  	print "</form></tr>";
			  	print "<tr><td colspan='3'>&nbsp;</td></tr>";
			  }
			  // Step 3: Choose Options
			  echo TEXT_ADD_PROD . $add_product_products_id;
			  if (($step > 2) && ($add_product_products_id > 0)) {
			  	// Get Options for Products
			  	$result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$add_product_products_id'");
			  	$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($add_product_products_id);
			  	// Skip to Step 4 if no Options
			  	if (tep_db_num_rows($result) == 0) {
			  		print "<tr class=\"dataTableRow\">\n";
			  		print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP3 . "</b></td><td class='dataTableContent' valign='top' colspan='2'><i>" . TEXT_SELECT_OPT_SKIP . "</i></td>";
			  		print "</tr>\n";
			  		if ($_POST['step'] <= '3') {
			  			$step = 4;
			  		}
			  	} else {
			  		/*
			  		while($row = tep_db_fetch_array($result))
			  		{
			  		extract($row,EXTR_PREFIX_ALL,"db");
			  		$Options[$db_products_options_id] = $db_products_options_name;
			  		$ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
			  		}
			  		*/
			  		while ($row = tep_db_fetch_array($result)) {
			  			extract($row, EXTR_PREFIX_ALL, "db");
			  			$Options[$db_products_options_id] = $db_products_options_name;
			  			if ($db_options_values_price != '0') {
			  				//amit modified to make sure price on usd
			  				if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
			  					$db_options_values_price = tep_get_tour_price_in_usd($db_options_values_price, $tour_agency_opr_currency);
			  				}
			  				//amit modified to make sure price on usd
			  				$db_products_options_values_name .= ' (' . $db_price_prefix . number_format($db_options_values_price, 2, '.', '') . ') ';
			  			}
			  			//amit added to show Holiday Surcharge -- for special price start
			  			if ($db_single_values_price > 0 || $db_kids_values_price > 0) {
			  				$db_products_options_values_name .= ' (' . TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE . ') ';
			  			}
			  			//amit added to show Holiday Surcharge -- for special price end
			  			$ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
			  		}
			  		print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  		print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP3 . "</b></td><td class='dataTableContent' valign='top'>";
			  		foreach ($ProductOptionValues as $OptionID => $OptionValues) {
			  			$OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[$OptionID]'>";
			  			foreach ($OptionValues as $OptionValueID => $OptionValueName) {
			  				$OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
			  			}
			  			$OptionOption .= "</select><br>\n";
			  			if (IsSet($add_product_options))
			  			$OptionOption = str_replace("value='".$add_product_options[$OptionID]."'", "value='".$add_product_options[$OptionID]."' selected", $OptionOption);
			  			print $OptionOption;
			  		}
			  		print "</td>";
			  		print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'>";
			  		print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			  		print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			  		print "<input type='hidden' name='step' value='4'>";
			  		print "</td>\n";
			  		print "</form></tr>\n";
			  	}
			  	print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
			  }

			  // Step 4: Confirm
			  if ($step > 3) {
			  	if ($_POST['add_product_quantity'] == '')
			  	$add_product_quantity = '1'; else
			  	$add_product_quantity = $_POST['add_product_quantity'];
			  	print "<tr><td colspan='3'><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'><table width='100%' cellspacing='1'><tr class=\"dataTableRow\">\n";
			  	print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP4 . "</b></td>";
			  	print "<td class='dataTableContent' valign='top'>" . TEXT_ADD_QUANTITY . ":  <input readonly='true' name='add_product_quantity' size='2' value='" . $add_product_quantity . "' class='order_textbox'>";
			  	/*
			  	if(IsSet($add_product_options))
			  	{
			  	foreach($add_product_options as $option_id => $option_value_id)
			  	{
			  	print "<input type='hidden' name='add_product_options[$option_id]' value='$option_value_id'>";
			  	}
			  	}
			  	print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			  	print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			  	*/
			  	//amit added to add departure info start
			  	//bhavik add your code to diplay lodgin info here
			  	if ($_POST['room-0-adult-total']) {
			  		include("edit_new_product_save.php");
			  	}
			  	include("edit_new_product.php");
			  	//amit added to add departure info end
			  	print "</td>";
			  	print "<input type='hidden' name='step' value='5'>";
			  	echo field_forwarder('add_product_categories_id');
			  	echo field_forwarder('add_product_products_id');
			  	echo field_forwarder('add_product_options');
			  	print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'>";
			  	print "</td>\n";
			  	print "</tr></table></form></td></tr>\n";
			  }

			  //amit added to next step 5 start
			  if ($step > 4) {
			  	define('TEXT_ADD_STEP5', 'STEP 5:');
			  	define('TEXT_ADD_STEP6', 'STEP 6:');
			  	//print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  	print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
			  	print "<tr class=\"dataTableRow\">\n";
			  	print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP5 . "</b>";
			  	//guest name air departure login here
			  	/* echo  field_forwarder('add_product_categories_id');
			  	echo  field_forwarder('add_product_products_id');
			  	echo  field_forwarder('add_product_options');
			  	echo  field_forwarder('add_product_quantity'); */
			  	//echo  field_forwarder('availabletourdate');
			  	//echo  field_forwarder('departurelocation');
			  	//echo  field_forwarder('numberOfRooms');
			  	//echo  field_forwarder('room-0-adult-total');
			  	//echo  field_forwarder('room-0-child-total');
			  	/* availabletourdate
			  	departurelocation
			  	numberOfRooms
			  	room-0-adult-total
			  	room-0-child-total
			  	*/
			  	//bhavik add your departure select name here like above
			  	print "</td>";
			  	print "<td class='dataTableContent' align='center' colspan='2'>";
			  	include "edit_orders_guestname.php";
			  	print "</td></tr>\n";
			  	//print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'></td>";
			  	//print "<input type='hidden' name='step' value='6'>";
			  	//print "</form></tr>\n";
			  }
			  //amit added to next sete 5 end

			  /*
			  //amit added to next step 6 start
			  if($step > 5)
			  {
			  print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			  print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP5 . "</b></td>";
			  //guest name air departure login here
			  echo  field_forwarder('add_product_categories_id');
			  echo  field_forwarder('add_product_products_id');
			  echo  field_forwarder('add_product_options');
			  //bhavik add your departure select name here like above
			  print "<input type='hidden' name='step' value='6'>";
			  print "</form></tr>\n";
			  }
			  //amit added to next sete 6 end
			  */
			  print "</table></td></tr>";
		}
			  ?>
	  </table></td>
		  <!-- body_text_eof //-->
		</tr>
	  </table></td>
</table>
<!-- body_eof //-->

<script type="text/javascript">
function check_confirm(orders_products_id)
{
	if(confirm('您真的要删除此订单产品？三思而后行对您并无坏处，您说是吧！'))
	{
		deleteproducts(orders_products_id);
		return true;
	}
	else
	{
		return false;
	}
}
function deleteproducts(index){
	var From = document.getElementById("edit_order");
	if(typeof(From.elements["update_products["+index+"][qty]"])!='undefined'){
		From.elements["update_products["+index+"][qty]"].value = 0;
		From.submit();
	}
	/* 以下代码不知道是哪个瓜儿弄的，非常不成熟！一旦edit_order表单前面的兄弟表单数目有变就有问题了。
	for ( i= 0 ; i < window.document.forms[1].elements.length ; i ++)
	{
		if(window.document.forms[1].elements[i].name == "update_products["+index+"][qty]" )
		{
			window.document.forms[1].elements[i].value = 0;
			document.edit_order.submit();
		}
	}
	*/
}
</script>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>

<?php
////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_get_country_id
//
// Arguments   : country_name		country name string
//
// Return	  : country_id
//
// Description : Function to retrieve the country_id based on the country's name
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_get_country_id($country_name) {
	$country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");
	if (!tep_db_num_rows($country_id_query)) {
		return 0;
	} else {
		$country_id_row = tep_db_fetch_array($country_id_query);
		return $country_id_row['countries_id'];
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_get_zone_id
//
// Arguments   : country_id		country id string
//			   zone_name		state/province name
//
// Return	  : zone_id
//
// Description : Function to retrieve the zone_id based on the zone's name
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_get_zone_id($country_id, $zone_name) {
	$zone_id_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . $zone_name . "'");
	if (!tep_db_num_rows($zone_id_query)) {
		return 0;
	} else {
		$zone_id_row = tep_db_fetch_array($zone_id_query);
		return $zone_id_row['zone_id'];
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_field_exists
//
// Arguments   : table	table name
//			   field	field name
//
// Return	  : true/false
//
// Description : Function to check the existence of a database field
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_field_exists($table, $field) {
	$describe_query = tep_db_query("describe $table");
	while ($d_row = tep_db_fetch_array($describe_query)) {
		if ($d_row["Field"] == "$field")
		return true;
	}
	return false;
}

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_html_quotes
//
// Arguments   : string	any string
//
// Return	  : string with single quotes converted to html equivalent
//
// Description : Function to change quotes to HTML equivalents for form inputs.
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_html_quotes($string) {
	return str_replace("'", "&#39;", $string);
}

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_html_unquote
//
// Arguments   : string	any string
//
// Return	  : string with html equivalent converted back to single quotes
//
// Description : Function to change HTML equivalents back to quotes
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_html_unquote($string) {
	return str_replace("&#39;", "'", $string);
}
?>

<script type="text/javascript">
function window_pos(popUpDivVar) {
	var window_width;
	var window_height;
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	try{
		var popUpDiv = document.getElementById(popUpDivVar);
		//window_width=window_width/2-400;//150 is half popup's width
		//window_width = parseFloat(window_width) - 1050;
		window_width = parseFloat(window_width) - 1000;
		popUpDiv.style.left = parseFloat(window_width) + 'px';
		var window_height = self.pageYOffset ? self.pageYOffset : document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body ? document.body.scrollTop : null;
		popUpDiv.style.top = parseFloat(window_height) + 'px';
	}catch(re){}
}

function show_travel_companion_tips(int_num, tips_id_mun){
	var jiesong_info_tips = document.getElementById('travel_companion_tips_'+tips_id_mun);
	if(jiesong_info_tips!=null){
		if(int_num>0){
			jiesong_info_tips.style.display="";
			if(document.all){
				//隐藏页面的下拉菜单
				var select_ = document.getElementsByTagName("select");
				for(i=0; i<select_.length; i++){
					select_[i].className += " hidden_select";
				}
				//恢复当前对象的下拉菜单
				var obj_select = jiesong_info_tips.getElementsByTagName("select");
				for(i=0; i<obj_select.length; i++){
					obj_select[i].className = obj_select[i].className.replace(/hidden_select/,'');
				}
			}
		}else{
			jiesong_info_tips.style.display="none";
			if(document.all){
				//还原页面的下拉菜单
				var select_ = document.getElementsByTagName("select");
				for(i=0; i<select_.length; i++){
					select_[i].className = select_[i].className.replace(/hidden_select/,'');
				}
			}
		}
	}
	window_pos('travel_companion_tips_'+tips_id_mun);
}

function get_customer_billing_info(name, company, address, suburb, city, state, postcode, country){
	document.edit_order.update_billing_name.value = name;
	document.edit_order.update_billing_company.value = company;
	document.edit_order.update_billing_street_address.value = address;
	document.edit_order.update_billing_surburb.value = suburb;
	document.edit_order.update_billing_city.value = city;
	document.edit_order.update_billing_state.value = state;
	document.edit_order.update_billing_postcode.value = postcode;
	document.edit_order.update_billing_country.value = country;
}

function check_status(status_id){
	var closeNotifyAccountingCheckboxDiv = true;
	if(status_id == '100021'){
		document.getElementById('div_cancel_reason').style.display = '';
		document.getElementById('div_credit_reason').style.display = 'none';
	}else if(status_id == '100080'){
		document.getElementById('div_credit_reason').style.display = '';
		document.getElementById('div_cancel_reason').style.display = 'none';
	}else if(status_id == '100006'){
		//document.edit_order.notify.checked = true;
	}else if(status_id == '100095' || status_id == '100071' ){
		jQuery('#NotifyAccountingCheckboxDiv').fadeIn('fast');
		closeNotifyAccountingCheckboxDiv = false;
	}
	else{
		document.getElementById('div_cancel_reason').style.display = 'none';
		document.getElementById('div_credit_reason').style.display = 'none';
		if(document.edit_order.last_visited_status.value == '6'){
			var previous_cancel_item = document.edit_order.cancel_item_orders_products_id.value;
			document.edit_order.elements['update_products['+previous_cancel_item+'][invoice_comments_retail]'].value = '';
			document.edit_order.elements['update_products['+previous_cancel_item+'][final_price_without_attr]'].value = document.edit_order.elements['update_products['+previous_cancel_item+'][original_final_price_without_attr]'].value;
			alert('Retail price and Retail comment fields value reset, please make sure all your changes before update, or just refresh the window.');
		}
		if(document.edit_order.last_visited_status.value == '100080'){
			var previous_credit_item = document.getElementById('credit_item_orders_products_id').value;
			document.edit_order.elements['update_products['+previous_credit_item+'][invoice_comments_retail]'].value = '';
			document.edit_order.elements['update_products['+previous_credit_item+'][final_price_without_attr]'].value = document.edit_order.elements['update_products['+previous_credit_item+'][original_final_price_without_attr]'].value;
			alert('Retail price and Retail comment fields reset, please make sure all your changes before update, or just refresh the window.');
		}
	}
	if(closeNotifyAccountingCheckboxDiv == true) {
		jQuery('#NotifyAccountingCheckboxDiv').fadeOut('fast');
		document.getElementById("notifyAccountingCN").checked =false;
		document.getElementById("notifyAccountingUS").checked =false;

	}
	document.edit_order.last_visited_status.value = status_id;
}

function get_return_amount(order_total, cancellation_fee, fee_percent){ //, other_cancellation_fee
	/*var other_charge = 0;
	var cancel_fee = 0;
	var return_amt = 0;
	if(other_cancellation_fee != ''){
	other_charge = other_cancellation_fee;
	}
	cancel_fee = cancellation_fee;
	var return_amt = parseFloat(order_total) - (parseFloat(cancel_fee) + parseFloat(other_charge));*/
	if(fee_percent == 'fee_percent'){
		var cancellation_fee_percent = cancellation_fee;
		var cancellation_fee = (order_total*cancellation_fee)/100;
		document.edit_order.cancellation_fee.value = cancellation_fee.toFixed(2);
	}else{
		var cancellation_fee_percent = (cancellation_fee * 100)/order_total;
		cancellation_fee_percent = cancellation_fee_percent.toFixed(2);
		document.edit_order.cancellation_fee_percent.value = cancellation_fee_percent;
	}
	var return_amt = parseFloat(order_total) - parseFloat(cancellation_fee)
	var return_amt = return_amt.toFixed(2);
	document.getElementById('return_amount').innerHTML = '$'+return_amt;
	var cancel_item = document.edit_order.cancel_item_orders_products_id.value;
	document.edit_order.elements['update_products['+cancel_item+'][final_price_without_attr]'].value = parseFloat(cancellation_fee);
	document.edit_order.elements['update_products['+cancel_item+'][invoice_comments_retail]'].value = 'Cancellation Fee: $'+parseFloat(order_total)+'x'+cancellation_fee_percent+'%';
}

function toggel_div(divid)
{
	if(eval("document.getElementById('" +  divid + "').style.display") == ''){
		eval("document.getElementById('" +  divid + "').style.display = 'none'");
	}else{
		eval("document.getElementById('" +  divid + "').style.display = ''");
	}
}

function show_div(divid)
{
	eval("document.getElementById('" +  divid + "').style.display = ''");
}

function append_settle_payment_method(addvalue,addpayment){
	var totalorders_payment_method=document.settele_order.orders_payment_method.value;
	var totalorders_reference_comments=document.settele_order.reference_comments.value;
	/*
	for(var i=0; i < document.settele_order.append_pmethod.length; i++)
	{
	if(document.settele_order.append_pmethod[i].checked){
	totalorders_payment_method += " + "+document.settele_order.append_pmethod[i].value;
	totalorders_reference_comments += "\n"+document.settele_order.append_pmethod[i].value + ": ";
	}
	}
	*/
	if(totalorders_payment_method==""){
		totalorders_payment_method = addpayment;
	}else{
		totalorders_payment_method += " + "+addpayment;
	}
	if(totalorders_reference_comments==""){
		totalorders_reference_comments = addvalue+ ": ";
	}else{
		totalorders_reference_comments += "\n"+addvalue+ ": ";
	}
	document.settele_order.orders_payment_method.value = totalorders_payment_method;
	document.settele_order.reference_comments.value = totalorders_reference_comments;
	document.settele_order.reference_comments.focus();
}

function check_product_change(val, previous_val, divid){
	if(val!=previous_val){
		document.getElementById(divid).style.display = '';
	}else{
		document.getElementById(divid).style.display = 'none';
	}
}

function compare_val(val, previous_val, divid){
	if(val!=previous_val){
		<?php if($access_full_edit != 'true' && $access_order_cost != 'true' && $can_sub_price != true){ //只有财务能减负值，其他人只能增加值，确定后将重载本页面！?>
		var cannot_do = false;
		val = parseFloat(val);
		if(isNaN(val)){
			cannot_do = true;
		}else if(val < parseFloat(previous_val)){
			cannot_do = true;
		}
		
		if(cannot_do == true){
			from_have_error = 1;
			alert("只有财务能减负值，其他人只能增加值，确定后将重载本页面！");
			window.location = "<?= $reload_url?>";
			return false;
		}
		<?php }?>
		document.getElementById(divid).value = 1;
	}
}

function remove_to_garbage_boxs(id,dateid,ordprodid,noguestindex){
	var re_obj = document.getElementById(id);
	var boxs = document.getElementById('garbage_boxs_'+ordprodid);
	boxs.value += re_obj.value;
	re_obj.style.color = "#CCCCCC";
	re_obj.style.textDecoration = "line-through";
	re_obj.readOnly = true;
	if(dateid!=""){
		var re_date_obj = document.getElementById(dateid);
		if(re_date_obj!=null){
			boxs.value += "||"+re_date_obj.value;
			re_date_obj.style.color = "#CCCCCC";
			re_date_obj.style.textDecoration = "line-through";
			re_date_obj.readOnly = true;
		}
	}
	boxs.value += "<::>";
	try{
		if(document.getElementById('guestgenders'+noguestindex+'_'+ordprodid) != null){
			var box_re_obj = document.getElementById('guestgenders'+noguestindex+'_'+ordprodid);
			addOption(box_re_obj,"Delete", "Delete");
			box_re_obj.value = 'Delete';
			box_re_obj.style.color = "#CCCCCC";
			box_re_obj.style.textDecoration = "line-through";
			box_re_obj.readOnly = true;
		}
	}catch(e){}
	try{
		if(document.getElementById('height'+noguestindex+'_'+ordprodid) != null){
			var box_height_obj = document.getElementById('height'+noguestindex+'_'+ordprodid);
			box_height_obj.value = '';
			box_height_obj.style.color = "#CCCCCC";
			box_height_obj.style.textDecoration = "line-through";
			box_height_obj.readOnly = true;
		}
	}catch(e){}

}

function addOptionDropdown(selectbox,text,value )
{
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	selectbox.options.add(optn);
}

function check_for_price_change(ord_prod_id_tourcode_string){
	var total_items_array = ord_prod_id_tourcode_string.split("||=||");
	var alert_msg_for_price_change = '';
	var error_for_price_change = 0;
	var total_reason_failed_couont = 0;
	/* Amit Added for reason alert start */
	if(document.edit_order.status.value == 100021){
		if(getSelectedCheckboxValue(document.edit_order.elements['cancel_reason[]']) == ""){
			if(document.edit_order.previous_status.value != 6 ){
				alert_msg_for_price_change = alert_msg_for_price_change+"Reason for cancellation should be entered.\n";
				error_for_price_change = 1;
				total_reason_failed_couont = 1;
			}
			if( (trim(document.edit_order.cancellation_fee.value) != ""  || trim(document.edit_order.provider_cancellation_fee.value) != "" || trim(document.edit_order.cancel_reason_detail.value) != "") && total_reason_failed_couont == 0){
				alert_msg_for_price_change = alert_msg_for_price_change+"Reason for cancellation should be entered.\n";
				error_for_price_change = 1;
			}
		}
	}
	/* Amit Added for reason alert end */

	for(i=0; i<total_items_array.length; i++){
		split_items_array = total_items_array[i].split("||");
		var ord_prod_id = split_items_array[0];
		var tour_code = split_items_array[1];
		if(document.edit_order.elements['is_retail_changed_'+ord_prod_id].value==1){
			if(trim(document.edit_order.elements['update_products['+ord_prod_id+'][invoice_comments_retail]'].value)==''){
				alert_msg_for_price_change = alert_msg_for_price_change+"请输入 "+tour_code+" 的价格调整备注.\n";
				error_for_price_change = 1;
				document.edit_order.elements['update_products['+ord_prod_id+'][invoice_comments_retail]'].focus();
			}
		}
		if(document.edit_order.elements['is_cost_changed_'+ord_prod_id].value==1){
			if(trim(document.edit_order.elements['update_products['+ord_prod_id+'][invoice_comments]'].value)==''){
				alert_msg_for_price_change = alert_msg_for_price_change+"请输入 "+tour_code+" 的成本调整备注.\n";
				error_for_price_change = 1;
				document.edit_order.elements['update_products['+ord_prod_id+'][invoice_comments]'].focus();
			}
		}
	}

	/* Amit Added for make cancle fee required start */
	if(document.edit_order.status.value == 100021){
		try{
			if(document.edit_order.elements['update_products['+document.edit_order.cancel_item_orders_products_id.value+'][is_cancel_history_exists]'].value == "no"){
				if(document.edit_order.provider_cancellation_fee.value == ""){
					alert_msg_for_price_change = alert_msg_for_price_change+"Provider Cancellation Fee field required.\n";
					error_for_price_change = 1;
					document.edit_order.provider_cancellation_fee.focus();
				}
				if(document.edit_order.cancellation_fee.value == "" ){
					alert_msg_for_price_change = alert_msg_for_price_change+"Customer Cancellation Fee field required.\n";
					error_for_price_change = 1;
					document.edit_order.cancellation_fee.focus();
				}
			}
		}catch(ee){}
	}
	/* Amit Added for make cancle fee required end */

	if(error_for_price_change==1){
		alert(alert_msg_for_price_change);
		return false;
	}
	if(check_comments()!=true){ /* Howard added */
		return false;
	}
	return true;
}

function set_cancel_item(val){
	var previous_cancel_item = document.edit_order.cancel_item_orders_products_id.value;
	document.edit_order.elements['update_products['+previous_cancel_item+'][invoice_comments_retail]'].value = '';
	document.edit_order.elements['update_products['+previous_cancel_item+'][final_price_without_attr]'].value = document.edit_order.elements['update_products['+previous_cancel_item+'][original_final_price_without_attr]'].value;
	document.edit_order.cancel_item_orders_products_id.value = val;
	document.getElementById('customer_payment_cancel').innerHTML = '$'+document.edit_order.elements['update_products['+val+'][final_price]'].value;
	document.getElementById('return_amount').innerHTML = '$'+document.edit_order.elements['update_products['+val+'][final_price]'].value;
}

//howard added
function check_comments(){
	tinyMCE.triggerSave();
	var tmp_val = $("#comments").val();
	if(tmp_val.indexOf('<xml>')!=-1){
		alert('请您不要从Word或WPS中直接复制内容到网站编辑上，谢谢！Comments的内容已经被清除！');
		document.edit_order.comments.value = "";
		tinyMCE.updateContent('comments');
		tinyMCEformsaveIt();
		return false;
	}
	return true;
}

function set_recent_date_of_departure_html(){
	var R = document.getElementById("RecentDateOfDeparture");
	if(R!=null){
		<?php
		$tmp_time_value = strtotime($recent_date) - time();
		$inner_html = tep_date_short($recent_date);
		if ($tmp_time_value >= (-86400) && $tmp_time_value <= (7 * 86400)) {
			$inner_html.=' <span style="color:red;">(紧急)</span>';
		}
		?>
		R.innerHTML = '<?= $inner_html; ?>';
	}else{
		//alert("on obj 'RecentDateOfDeparture'");
	}
}
set_recent_date_of_departure_html();

<?php
//howard added 根据EmailSendProvider自动驱动发邮件给供应商 start {
if (tep_not_null($_SESSION['EmailSendProvider'])) {
	$order_id = $_SESSION['EmailSendProvider']['orders_id'];
	$product_id = $_SESSION['EmailSendProvider']['products_id'];
	$order_product_id = $_SESSION['EmailSendProvider']['orders_products_id'];
	if ((int) $order_product_id) {
		$check = tep_db_fetch_array(tep_db_query('SELECT orders_products_id  FROM `provider_order_products_status_history` WHERE orders_products_id ="' . $order_product_id . '" and provider_order_status_id ="1" limit 1'));
		if ((int) $check['orders_products_id']) { //确认供应商有权限看到该订单时才发邮件
?>

function send_mail_for_changed_Tour_Start_Date_and_Location(){
	var frm = document.getElementById("edit_order");
	if(frm==null){ return false; }
	frm.elements["provider_comment_<?= $order_product_id ?>"].focus();
	frm.elements["provider_comment_<?= $order_product_id ?>"].value = "Tour Start Date and Location Changed,please confirm";
	frm.elements["provider_order_status_id_<?= $order_product_id ?>"].value = "29";

	var auto_send = false;
	if(auto_send==true || confirm("<?= db_to_html('由于您刚才修改了出发日期和上车地址，现在需要给供应发送确认邮件，点击确定按钮将开始发送，点击取消则不发送！'); ?>")){
		frm.elements["provider_comment_<?= $order_product_id ?>"].focus();
		frm.elements["btnProvidersConfirmation_<?= $order_product_id ?>"].click();
	}
}
<?php
			$switch_send_mail_for_changed_Tour_Start_Date_and_Location = false;	//暂时关闭这个智能功能
			if($switch_send_mail_for_changed_Tour_Start_Date_and_Location == true){
?>
send_mail_for_changed_Tour_Start_Date_and_Location();
<?php 
			}
		}
	}
	tep_session_unregister('EmailSendProvider');
}
//howard added 根据EmailSendProvider自动驱动发邮件给供应商 end }
?>

function ComparisonDateAddress(a_id, b_id){
	if(document.getElementById(a_id).value.replace(/ +/,'')!=document.getElementById(b_id).value.replace(/ +/,'')){
		alert("<?= db_to_html('您已改变 Tour Start Date and Location ，请注意：\t\n（1）Suggested Tour Itinerary 中的信息也要作相应的修改；\t\n（2）请检查更新后的出团时间是否适用于假日假格。') ?>");
	}
}

//确认行程已经和供应商对过
function admin_confirmation(button,order_product_id){
	if(confirm("<?= db_to_html('您确定与供应商Provider核对无误了？'); ?>")){
		button.disabled = true;
		var url = "<?= tep_href_link(FILENAME_EDIT_ORDERS, 'action=admin_and_provider_confirmed') ?>"+"&orders_products_id="+order_product_id;
		$.post(url, "", function() {
			alert("<?= db_to_html("确认完成！") ?>");
		});
	}
}

//启用某个按钮
function enable_button(button_id){
	var button = document.getElementById(button_id);
	if(button!=null){
		button.disabled = false;
	}
}

function toggel_div_show(divid){
	if(eval("document.getElementById('" +  divid + "').style.display") == 'none'){
		eval("document.getElementById('" +  divid + "').style.display = ''");
	}
}

function toggel_div_hide(divid){
	if(eval("document.getElementById('" +  divid + "').style.display") != 'none'){
		eval("document.getElementById('" +  divid + "').style.display = 'none'");
	}
}

function check_ref_comment_added(val,payment_method){
	if(val == "" && payment_method != "Credit Issued"){
		alert("Please Enter Reference Comment");
		return false;
	}else if(payment_method == ""){
		alert("Please Enter Payment Method");
		return false;
	}else{
		return true;
	}
}

function refunc_changed_payment_method_refrence_comment(dropdown_select_value){
	document.getElementById('CreditIssuedTable').style.display = 'none';
	if(dropdown_select_value == '100005-1'){
		document.settele_order.orders_payment_method.value = 'Credit Card';
		document.settele_order.reference_comments.value = 'Last 4 Digits of CC: \r\nTransaction ID: ';
	}else if(dropdown_select_value == '100005-2'){
		document.settele_order.orders_payment_method.value = 'Paypal';
		document.settele_order.reference_comments.value = 'Paypal Unique Transaction ID: ';
	}else if(dropdown_select_value == '100005-3'){
		document.settele_order.orders_payment_method.value = 'Check';
		document.settele_order.reference_comments.value = 'Check Number: ';
	}else if(dropdown_select_value == '100005-4'){
		document.settele_order.orders_payment_method.value = 'Cash Payment';
		document.settele_order.reference_comments.value = 'Who paid directly to customer: ';
	}else if(dropdown_select_value == '100005-5'){
		document.settele_order.orders_payment_method.value = 'Wire Transfer';
		document.settele_order.reference_comments.value = 'Customer\'s bank account details: \r\nWho made the wire transfer: ';
	}else if(dropdown_select_value == '100005-6'){
		document.settele_order.orders_payment_method.value = 'Credit Issued';
		document.settele_order.reference_comments.value = '';
		document.getElementById('CreditIssuedTable').style.display = '';
	}
	document.settele_order.reference_comments.focus();
}

function get_return_amount1(credit_fee, fee_percent, include_cancel){
	//, other_cancellation_fee
	var credit_item = document.getElementById('credit_item_orders_products_id').value;
	var order_total = document.edit_order.elements['update_products['+credit_item+'][final_price]'].value;
	if(fee_percent == 'fee_percent'){
		var credit_fee_percent = credit_fee;
		var credit_fee = (order_total*credit_fee)/100;
		if(include_cancel == 'cancel'){
			document.getElementById('credit_cancellation_fee').value = credit_fee.toFixed(2);
		}else{
			document.getElementById('credit_fee').value = credit_fee.toFixed(2);
		}
	}else{
		var credit_fee_percent = (credit_fee * 100)/order_total;
		credit_fee_percent = credit_fee_percent.toFixed(2);
		if(include_cancel == 'cancel'){
			document.getElementById('credit_cancellation_fee_percent').value = credit_fee_percent;
		}else{
			document.getElementById('credit_fee_percent').value = credit_fee_percent;
		}
	}
	var credit_limit = 102;
	/*
	if(document.getElementById('credit_reason_resnotavail').checked == true){
	credit_limit = 102;
	}
	*/
	if(parseFloat(credit_fee_percent)>credit_limit){
		alert("Credit fee can not be allowed more than "+credit_limit+"%");
		document.getElementById('credit_fee').value = '';
		document.getElementById('credit_fee_percent').value = '';
		return false;
	}
	var order_total_without_attr = document.edit_order.elements['update_products['+credit_item+'][original_final_price_without_attr]'].value;
	if(document.getElementById('credit_cancellation_fee').value > 0){
		var return_amt = parseFloat(order_total) - parseFloat(document.getElementById('credit_cancellation_fee').value) - parseFloat(credit_fee);
		var return_amt_without_attr = parseFloat(document.getElementById('credit_cancellation_fee').value);
	}else{
		var return_amt = parseFloat(order_total) - parseFloat(credit_fee);
		var return_amt_without_attr = parseFloat(order_total_without_attr) - parseFloat(credit_fee);
	}
	var return_amt = return_amt.toFixed(2);
	var return_amt_without_attr = return_amt_without_attr.toFixed(2);
	document.getElementById('return_amount_credit').innerHTML = '$'+return_amt;
	document.edit_order.elements['update_products['+credit_item+'][final_price_without_attr]'].value = return_amt_without_attr;
	if(document.getElementById('credit_cancellation_fee').value > 0){
		document.edit_order.elements['update_products['+credit_item+'][invoice_comments_retail]'].value = 'Cancellation Fee: $'+ parseFloat(order_total).toFixed(2)+'x'+document.getElementById('credit_cancellation_fee_percent').value+'%'+'\n'+'Credit issued : $'+ parseFloat(order_total).toFixed(2)+'x'+document.getElementById('credit_fee_percent').value+'%';
	}else{
		document.edit_order.elements['update_products['+credit_item+'][invoice_comments_retail]'].value = 'Credit issued : $'+ parseFloat(order_total).toFixed(2)+'x'+credit_fee_percent+'%';
	}
}

function show_credit_amt_div(){
	if(document.getElementById('credit_reason_reaquire').checked == true){
		document.getElementById('show_cancel_on_credit').style.display = '';
	}else{
		document.getElementById('show_cancel_on_credit').style.display = 'none';
	}
}

function disableEnterKey(e)
{
	var key;
	if(window.event){
		key = window.event.keyCode;	 //IE
	}else {
		key = e.which;	 //firefox
	}
	if(key == 13){
		return false;
	}else {
		return true;
	}
}

function set_credit_item(val){
	var previous_credit_item = document.getElementById('credit_item_orders_products_id').value;
	document.edit_order.elements['update_products['+previous_credit_item+'][invoice_comments_retail]'].value = '';
	document.edit_order.elements['update_products['+previous_credit_item+'][final_price_without_attr]'].value = document.edit_order.elements['update_products['+previous_credit_item+'][original_final_price_without_attr]'].value;
	document.getElementById('credit_item_orders_products_id').value = val;
	document.getElementById('customer_payment_credit').innerHTML = '$'+document.edit_order.elements['update_products['+val+'][final_price]'].value;
	document.getElementById('return_amount_credit').innerHTML = '$'+document.edit_order.elements['update_products['+val+'][final_price]'].value;
}


jQuery(document).ready(function(e) {
    jQuery('select[id^=\'select_flight_\']').change(function(r){
		var index = jQuery(this).attr('id').replace('select_flight_','');
		var val = jQuery(this).val();
		switch (val){
			case '15':
			case '1':
				var Then = '接机：';
				var Send = '送机：';
				//航班到达日期
				Then += jQuery('#flight_info_div_' + index + ' input[name=\'arrival_date[' + index + ']\']').val();
				Send += jQuery('#flight_info_div_' + index + ' input[name=\'departure_date[' + index + ']\']').val();
				//航空公司
				Then += "\t" + jQuery('#flight_info_div_' + index + " input[name='airline_name[" + index + "]']").val();
				Send += "\t" + jQuery('#flight_info_div_' + index + " input[name='airline_name_departure[" + index + "]']").val();
				
				//航班号
				Then += "\t" + jQuery('#flight_info_div_' + index + " input[name='flight_no[" + index + "]']").val();
				Send += "\t" + jQuery('#flight_info_div_' + index + " input[name='flight_no_departure[" + index + "]']").val();
				
				//接机机场
				Then += "\t" + jQuery('#flight_info_div_' + index + " input[name='airport_name[" + index + "]']").val();
				Send += "\t" + jQuery('#flight_info_div_' + index + " input[name='airport_name_departure[" + index + "]']").val();
				//时间
				Then += "\t" + jQuery('#flight_info_div_' + index + " input[name='arrival_time[" + index + "]']").val();
				Send += "\t" + jQuery('#flight_info_div_' + index + " input[name='departure_time[" + index + "]']").val();
				if (val == '1') {
					Then = Then.replace('接机：','').replace('/\\t/g','');
					Send = Send.replace('送机：','').replace('/\\t/g','');
					if (Then != '' || Send != '') {
						jQuery('#textarea_flight_' + index).val(Then + "\n" + Send);
					}
				} else {
					jQuery('#textarea_flight_' + index).val(Then + "\n" + Send);
				}
				break;
			case "1":
				break;
			default:
				jQuery('#textarea_flight_' + index).val("");
				break;
		}
	});
});

/*销售副主管级别以上的管理审核当日游客手机号更新*/
function confirm_cellphone_number(orders_customers_cellphone_history_id){
	if(orders_customers_cellphone_history_id>0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=confirm_cellphone_number')) ?>");
		jQuery.post(url,{'action':"confirm_cellphone_number", 'ajax':"true", 'orders_customers_cellphone_history_id':orders_customers_cellphone_history_id },function(text){
				if(text=='not_permissions'){ alert('您无权审核哟……');};
				text = parseInt(text);
				if(text>0){
					alert('当日游客手机审核成功，请不要忘记给地接发确认信！');
					/*刷新页面*/
					window.location = "<?= $reload_url?>";
				}
			},'text');
		
	}
}
/*销售主管级别以上的管理审核上车地址*/
function confirm_departure_location(history_id, thisBut, i ){
	if(history_id>0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=confirm_departure_location')) ?>");
		jQuery.post(url,{'action':"confirm_departure_location", 'ajax':"true", 'history_id':history_id, 'oID':"<?= $oID?>" },function(text){
				if(text=='not_permissions'){ alert('您无权审核哟……');};
				text = parseInt(text);
				if(text>0){
					alert('上车地址审核成功，请不要忘记给地接发确认信！');
					/*刷新页面*/
					window.location = "<?= $reload_url?>";
				}
			},'text');
		
	}
}

/*销售主管级别以上的管理审核客户信息*/
function confirm_histories_action(op_histoty_id, thisBut, i ){
	if(op_histoty_id>0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=confirm_histories')) ?>");
		jQuery.post(url,{'action':"confirm_histories", 'ajax':"true", 'op_histoty_id':op_histoty_id, 'oID':"<?= $oID?>" },function(text){
				if(text=='not_permissions'){ alert('您无权审核哟……');};
				text = parseInt(text);
				if(text>0){
					alert('客户信息审核成功，请不要忘记给地接发确认信！');
					/*刷新页面*/
					window.location = "<?= $reload_url?>";
				}
			},'text');
		
	}
}

/*航班信息已阅动作*/
function is_read(orders_flight_id,opid,oid,pid){
	var str = {
			"action"             : "hidden_flight",
			"orders_flight_id"   : orders_flight_id ,
			"ajax"               : "true",
			"orders_products_id" : opid,
			"products_id" : pid,
			"orders_id" : oid
		};
	jQuery.get('<?php echo tep_href_link_noseo('orders_warning.php','')?>',str,function(r){
		if (r == "success") {
			alert("设置成功!");
			jQuery("#is_read_btn_"+ orders_flight_id ).hide();
		}
	},"html");
}

/* OP修改订单产品名称 */
function update_orders_products_name(orders_products_id){
	var value = jQuery('input[name="orders_products_name['+ orders_products_id +']"]').val();
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=update_orders_products_name')) ?>");
	jQuery.post(url,{'orders_products_id' : orders_products_id, 'orders_product_name':value },function(text){
		if(text=='ok'){
			alert("名称修改成功！");
		}
	});
}
</script>

<?php
/* 可移动的多重层 start {*/
?>
<style type="text/css">
* {
	margin:0;
	padding:0;
	list-style: none;
}
img {
	border:0;
}
body {
	font-size:12px;
	font-family:宋体, Tahoma, Arial, Verdana, Helvetica, sans-serif;
	color:#353535;
}
/* 弹出层 */
.popup {
	display:none;
	position:absolute;
	text-align:center;
}
.actLayers .popupConTop {
	background:#eee;
}
.iframeBg {
	position:absolute;
	top:0;
	left:0;
	width:100%;
	height:100%;
}
.popupTable {
	position:relative;
	z-index:1;
}
.popupBg {
	display:none;
	position:absolute;
	z-index:99;
	top:0;
	left:0;
	background:#ccc;
	filter:alpha(opacity=30);
	-moz-opacity:0.3;
	opacity:0.3;
}
.popupBgIframe {
	filter:alpha(opacity=0);
	-moz-opacity:0;
	opacity:0;
}
.popupTable td.side {
	background:url(images/icons/popup_png.png);
}
.popupTable td.topLeft {
	width:6px;
	height:6px;
	background:url(images/icons/popup_t1.png);
}
.popupTable td.topRight {
	width:6px;
	height:6px;
	background:url(images/icons/popup_t3.png);
}
.popupTable td.botLeft {
	height:6px;
	background:url(images/icons/popup_b1.png);
}
.popupTable td.botRight {
	background:url(images/icons/popup_b3.png);
}
.popupTable td.con {
	background:none;
	_filter:none;
	background:#fff;
}
.popup .popupClose {
	float:right;
	margin:3px 0 0;
	width:16px;
	height:16px;
	cursor:pointer;
}
.popup .popupChange {
	float:right;
	margin:3px 10px 0 0;
	display:inline;
	width:14px;
	height:14px;
	border:1px solid #1d476d;
	color:#1d476d;
	text-align:center;
	font-size:15px;
	line-height:14px;
	cursor:pointer;
}
/* 弹出框按钮 */
.btnPopup {
	margin:15px 0 0 180px;
	display:inline;
}
.popupCon {
	padding:12px;
	text-align:left;
	overflow:hidden;
}
.popupCon .popupConTop {
	width:100%;
	height:25px;
	border-bottom:1px #DBDBDB dashed;
	position:relative;
	cursor:move;
}
.popupCon .popupConTop h4 {
	float:left;
	height:25px;
	line-height:25px;
	color:#FF6D03;
}
.popupCon .popupConTop h4 b {
	font-size:14px;
	color:#000;
}
.popupCon .popupConTop span {
	position:absolute;
	right:0;
	top:3px;
	width:16px;
	height:16px;
	cursor:pointer;
}
</style>

<script type="text/javascript">
function changeCon(n,popupId,conId){
	ObjId(conId).style.display= ObjId(conId).style.display==''?'none':'';
	ObjId(popupId).style.height = ObjId(popupId).offsetHeight > 65 ? "65px" : ObjId(popupId).offsetHeight;
	var ifr = null;
	var iframes = ObjId(popupId).getElementsByTagName('iframe'); //iframeBg
	for(var i=0; i<iframes.length; i++){
		if(iframes[i].className=="iframeBg"){
			iframes[i].style.height = ObjId(popupId).offsetHeight;
			break;
		}
	}
	n.innerHTML = n.innerHTML =="+"?"-":"+";
}
</script>

<script type="text/javascript">
// 弹出层
var ObjId=function(id){return document.getElementById(id)};
function bodySize(){
	var a=new Array();
	a.st = document.body.scrollTop?document.body.scrollTop:document.documentElement.scrollTop;
	a.sl = document.body.scrollLeft?document.body.scrollLeft:document.documentElement.scrollLeft;
	a.sw = document.documentElement.clientWidth;
	a.sh = document.documentElement.clientHeight;
	return a;
}

function centerElement(obj,top){
	var s = bodySize();
	var w = ObjId(obj).offsetWidth;
	var h = ObjId(obj).offsetHeight;
	ObjId(obj).style.left = parseInt((s.sw - w)/2, 10) + s.sl + "px";
	if(top!="" && top!=null){
		ObjId(obj).style.top = top+s.st+ "px";
	}else{
		ObjId(obj).style.top = parseInt((s.sh - h)/2, 10) + s.st + "px";
	}
}

function hideAllSelect(){
	var selects = document.getElementsByTagName("SELECT");
	for(var i = 0 ; i<selects.length;i++){
		selects[i].style.display = "none";
	}
}

function showPopup(popupId,popupCon,top){
	ObjId(popupId).style.display="block";  //显示弹出窗
	ObjId(popupId).style.width = (ObjId(popupCon).offsetWidth + 12) + "px";   //设置弹出层的宽度
	ObjId(popupId).style.height = (ObjId(popupCon).offsetHeight + 12) + "px";  //设置弹出层的高度
	centerElement(popupId,top);
	//hideAllSelect();
	window.onresize = function() {centerElement(popupId,top);}//屏幕改变的时候重新设定悬浮框
	//设置弹出层层级显示
	findLayer(popupId);
}

/*  以下是修改电子参团凭证日期的联动效果 BY Panda START*/
/* 失去焦点时执行联动 */
function linkageForDate(id){        	
    var str = jQuery("#"+id).val();    
    var form_id = jQuery("#"+id) .closest('form').attr('id');
    if (str != ''){
        var strArr = str.split(' ');
        var nowDate = trim(strArr[0]);                
        var oldDate = jQuery("#panda_old_date").val(); 
        
        var weekArr = nowDate.split('-');                
        var neweek = getDayOfWeek(nowDate);  
        var str5;
        if (strArr[1]){
                 var reg3=new RegExp(strArr[1],"g"); //创建正则RegExp对象             
                 str5 = str.replace(reg3, neweek);
            }else{
                str5 = nowDate + ' ' + neweek;           
        }
        
        var red = /undefined*$/;
        if (red.test(str5)){
            alert('日期格式错误,请在日期后面加上空格!');
            return false;
        }else{
           
        
        
        jQuery("#"+id).val(str5);
        var formIdArr = form_id.split('_');
        var editorId = parseInt(formIdArr[3], 10)+1;
        
        /* 如果原来时间与现在时间不同则修改时间 */    
        
        //if (nowDate != oldDate){       
                
            var stringObj = jQuery("#mce_editor_"+editorId).contents().find("#tour_arrangement").html();                  
            var reg=new RegExp("&nbsp;","g"); //创建正则RegExp对象        
            var Html = stringObj.replace(reg,""); 
               
            var reg2=new RegExp('&',"g"); //创建正则RegExp对象
            var Html2 = Html.replace(reg2,""); 
            
            /* 动态添加日期 */                
                var oldweek;
                if (strArr[1]){
                    oldweek = strArr[1];
                }else{                  
                    /* 原来的星期 */
                    regweek1 = /Monday/;
                    regweek2 = /Tuesday/;
                    regweek3 = /Wednesday/;
                    regweek4 = /Thursday/;
                    regweek5 = /Friday/;
                    regweek6 = /Saturday/;
                    regweek7 = /Sunday/;
                    if (regweek1.test(Html2)){
                        oldweek = 'Monday'
                    }                    
                    if(regweek2.test(Html2)){
                        oldweek = 'Tuesday'
                    }
                    if(regweek3.test(Html2)){
                        oldweek = 'Wednesday'
                    }
                    if(regweek4.test(Html2)){
                        oldweek = 'Thursday'
                    }
                    if(regweek5.test(Html2)){
                        oldweek = 'Friday'
                    }
                    if(regweek6.test(Html2)){
                        oldweek = 'Saturday'
                    }
                    if(regweek7.test(Html2)){
                        oldweek = 'Sunday'
                    }
                    
                }
            
            jQuery.ajax({
                url:url_ssl('orders_edit_date.php'),
                data:"html=" + escape(Html2) + "&olddate=" + oldDate + "&newdate=" + nowDate + "&action=top" + "&oldweek=" + strArr[1] + "&newweek=" + neweek,
                type:"POST",
                cache: false,
                dataType:"html",
                success: function(html){
                    //alert(html);
                    jQuery("#mce_editor_"+editorId).contents().find("#tour_arrangement").html(unescape(html));
                },
                error: function (msg){
                    alert(msg);
                },

            });
           
           
            
        //} 
        }
        
    }
}
/* 	获得指定日期的星期数，1-6为星期一到星期六，0为星期天 */
function getDayOfWeek(dayValue){
      var day = new Date(Date.parse(dayValue.replace(/-/g, '/'))); //将日期值格式化
      var today = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
      return today[day.getDay()] //day.getDay();根据Date返一个星期中的某一天，其中0为星期日
}

/* 点击时候执行一些必须操作 */

function pandaGetOldDate(id){
    var str = jQuery("#"+id).val();    
    if (str != ''){
        strArr = str.split(' ');
        nowDate = trim(strArr[0]);        
        jQuery("#panda_old_date").val(nowDate);        
    }
}

/* 修改电子参团凭证日期的联动效果 BY Panda END */

function closePopup(popupId){
	ObjId(popupId).style.display='none';
	ObjId("popupBg").style.display='none';
}

//以下是与拖动层有关的代码 php只改pop_id_string和drag_id_string即可
<?php
if (is_array($layer_obj) && sizeof($layer_obj)) {
	$pop_id_string = $drag_id_string = "";
	for ($i = 0; $i < sizeof($layer_obj); $i++) {
		$pop_id_string.=$layer_obj[$i]['pop'] . ',';
		$drag_id_string.=$layer_obj[$i]['drag'] . ',';
	}
	$pop_id_string = substr($pop_id_string, 0, -1);
	$drag_id_string = substr($drag_id_string, 0, -1);
}
?>

var pop_id_string = "<?= $pop_id_string; ?>";
var drag_id_string = "<?= $drag_id_string; ?>";
var pop_ids = pop_id_string.split(',');
var drag_ids = drag_id_string.split(',');
if(pop_ids.length!=drag_ids.length){ alert("Pop与Drag总数不一样。"); }
var layers = new Array();
function findLayer(eID){
	for(var i=0; i<pop_ids.length; i++){
		layers[i] = ObjId(pop_ids[i]);
	}
	for(j = 0 ; j < layers.length;j++){
		if(layers[j].id == eID){
			jh(j);
			px(j);
			break;
		}else{
			continue;
		}
	}
}

function jh(y){
	for(a=0;a<layers.length;a++){
		if(layers[y].id == layers[a].id){
			layers[a].className = 'popup actLayers';
		}else{
			layers[a].className = 'popup';
		}
	}
}

function px(x){//排序函数
	var maxNum;
	if(layers[x].style.zIndex == ''){layers[x].style.zIndex = 100;}
	maxNum = layers[x].style.zIndex;
	for(i=0;i<layers.length;i++){
		if(layers[i].style.zIndex == ''){layers[i].style.zIndex = 100;}
		if(maxNum <= layers[i].style.zIndex){
			maxNum = parseInt(layers[i].style.zIndex, 10)+1;
		}else{
			continue;
		}
	}
	layers[x].style.zIndex = maxNum;
}

//弹出层顶部拖曳
Array.prototype.extend = function(C) {
	for (var B = 0, A = C.length; B < A; B++) {
		this.push(C[B]);
	}
	return this;
}

function divDrag() {
	var A, B, popupcn;
	this.dragStart = function(e) {
		e = e || window.event;
		if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
		var pos = this.popuppos;
		popupcn = this.parent || this;
		if (document.defaultView) {
			_top = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("top");
			_left = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("left");
		}
		else {
			if (popupcn.currentStyle) {
				_top = popupcn.currentStyle["top"];
				_left = popupcn.currentStyle["left"];
			}
		}
		pos.ox = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(_left,10);
		pos.oy = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(_top,10);
		if ( !! A) {
			if (document.removeEventListener) {
				document.removeEventListener("mousemove", A, false);
				document.removeEventListener("mouseup", B, false);
			}
			else {
				document.detachEvent("onmousemove", A);
				document.detachEvent("onmouseup", B);
			}
		}
		A = this.dragMove.create(this);
		B = this.dragEnd.create(this);
		if (document.addEventListener) {
			document.addEventListener("mousemove", A, false);
			document.addEventListener("mouseup", B, false);
		}
		else {
			document.attachEvent("onmousemove", A);
			document.attachEvent("onmouseup", B);
		}

		this.stop(e);
	}
	this.dragMove = function(e) {
		e = e || window.event;
		var pos = this.popuppos;
		popupcn = this.parent || this;
		popupcn.style.top = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(pos.oy,10) + 'px';
		popupcn.style.left = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(pos.ox,10) + 'px';
		this.stop(e);
	}
	this.dragEnd = function(e) {
		var pos = this.popuppos;
		e = e || window.event;
		if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
		popupcn = this.parent || this;
		if ( !! (this.parent)) {
			this.style.backgroundColor = pos.color
		}
		if (document.removeEventListener) {
			document.removeEventListener("mousemove", A, false);
			document.removeEventListener("mouseup", B, false);
		}
		else {
			document.detachEvent("onmousemove", A);
			document.detachEvent("onmouseup", B);
		}
		A = null;
		B = null;
		//popupcn.style.zIndex=(++zIndex);
		this.stop(e);
	}
	this.shiftColor = function() {
		this.style.backgroundColor = "#dddddd";
	}
	this.position = function(e) {
		var t = e.offsetTop;
		var l = e.offsetLeft;
		while (e = e.offsetParent) {
			t += e.offsetTop;
			l += e.offsetLeft;
		}
		return {
			x: l,
			y: t,
			ox: 0,
			oy: 0,
			color: null
		}
	}
	this.stop = function(e) {
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
		if (e.preventDefault) {
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
	}
	this.stop1 = function(e) {
		e = e || window.event;
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
	}
	this.create = function(bind) {
		var B = this;
		var A = bind;
		return function(e) {
			return B.apply(A, [e]);
		}
	}
	this.dragStart.create = this.create;
	this.dragMove.create = this.create;
	this.dragEnd.create = this.create;
	this.shiftColor.create = this.create;
	this.initialize = function() {
		for (var A = 0, B = arguments.length; A < B; A++) {
			C = arguments[A];
			if (! (C.push)) {
				C = [C];
			}
			popupC = (typeof(C[0]) == 'object') ? C[0] : (typeof(C[0]) == 'string' ? ObjId(C[0]) : null);
			if (!popupC) continue;
			popupC.popuppos = this.position(popupC);
			popupC.dragMove = this.dragMove;
			popupC.dragEnd = this.dragEnd;
			popupC.stop = this.stop;
			if ( !! C[1]) {
				popupC.parent = C[1];
				popupC.popuppos.color = popupC.style.backgroundColor;
			}
			if (popupC.addEventListener) {
				popupC.addEventListener("mousedown", this.dragStart.create(popupC), false);
				if ( !! C[1]) {
					popupC.addEventListener("mousedown", this.shiftColor.create(popupC), false);
				}
			}
			else {
				popupC.attachEvent("onmousedown", this.dragStart.create(popupC));
				if ( !! C[1]) {
					popupC.attachEvent("onmousedown", this.shiftColor.create(popupC));
				}
			}
		}
	}
	this.initialize.apply(this, arguments);
}

function auto_new_obj(){
	for(var i=0; i<pop_ids.length; i++){
		new divDrag([ObjId(drag_ids[i]),ObjId(pop_ids[i])]);
	}
}
auto_new_obj();

</script>
<?php
/* 可移动的多重层 end }*/
?>

<script type="text/javascript">
<?php
//自动引导财务退完款后到发邮件窗口，此功能的来源于退款报表页面accounts_refund.php点“待退款”按钮
if(preg_replace('/\?.+/','',basename($_SERVER['HTTP_REFERER']))=='accounts_refund.php'){
	if($_GET['sendmail_for_refund']=='1'){
?>
	jQuery('#notify').attr('checked','checked');
	jQuery('#edit_order select[name="status_groups"]').val('8').change();
<?php
		if($_GET['send_mail_type']=='full'){	//全额退款，指引到 9-05 Refunded 已退款(全额退款)
			$_sid = '100005';
		}else{	//部分退款，指引到 9-09已部分退款
			$_sid = '100151';
		}
		
?>
	jQuery('#statusSelect').val('<?= $_sid;?>').change();
	//自动提交邮件订单:保守起见，暂不自动提交
	if(jQuery('#notify').attr('checked')!=true){
		jQuery('#notify').click();
	}
	//jQuery('#masterUpdateOrdersButton').click();
<?php
	}
}
?>


//财务设置已知道这个产品是setp3的产品
function step3_set_know(orders_products_id){
	if(orders_products_id > 0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=step3SetKnow')) ?>");
		jQuery.post(url,{'action':"step3SetKnow", 'ajax':"true", 'orders_products_id':orders_products_id },function(text){
				if(text=='ok'){
					alert("设置成功，颜色已清除！");
					window.location = "<?= $reload_url?>";
				}
			},'text');
	}
}

/**
* 删除可疑的优惠码佣金
**/
function removeBrokerage(orders_id){
	if(orders_id>0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','action=removeBrokerage')) ?>");
		jQuery.post(url,{'action':"removeBrokerage", 'ajax':"true", 'orders_id':orders_id },function(text){
				text = parseInt(text,10);
				if(text>0){
					alert("佣金删除成功！");
					window.location = "<?= $reload_url?>";
				}
			},'text');
	}
}
function addSellerMark(action,tableID,textareaID){
	action = action || 'add_seller_mark';
	textareaID = textareaID || 'seller_mark_content';
	tableID = tableID || "seller_remark_table";
var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php')) ?>");
	$.ajax({ 
  url: url, 
  type: "POST", 
  data: {action : action,content:document.getElementById(textareaID).value,ooID:<?php echo $_GET['oID'];?>,loogin_id:<?php echo $login_id; ?>}, 
  success: function(data) {
　　var tr=document.createElement("tr");
　　var td1=document.createElement("td"); 
　　td1.appendChild(document.createTextNode("<?php echo tep_get_admin_customer_name($login_id);?>"));
　　tr.appendChild(td1);
	var td2=document.createElement("td"); 
　　td2.appendChild(document.createTextNode(document.getElementById(textareaID).value));
　　tr.appendChild(td2);
	var td3=document.createElement("td"); 
　　td3.appendChild(document.createTextNode("<?php echo date('Y-m-d H:i:s',time());?>"));
　　tr.appendChild(td3);
	var td4=document.createElement("td"); 
　　td4.appendChild(document.createTextNode(""));
　　tr.appendChild(td4);
　　document.getElementById(tableID).appendChild (tr);
　　var str = '';
　　switch (action) {
		case 'add_seller_mark':
			str = '销售';
			break;
		case 'add_head_mark':
			str = '主管';
			break;
		default:
			str = '未知';
	}
	alert('添加订单' + str + '备注成功');
	hideShow(action);
  }
}); 

}

function hideShow(idName){
	var doc=document.getElementById(idName);
	if(doc.style.display=='none'){
		doc.style.display='';
	}else{
		doc.style.display='none';
	}
}

function fnFinish(orders_id)
{
	if(confirm("您确认订单[ "+orders_id+" ] 的问题已经处理ok了?"))
	{
		var url="op_special_list.php";
		jQuery.get(url, {"item":"op_think_it_problem","action":"finish","orders_id": orders_id}, function (data, textStatus){
			if(data == 'success'){	jQuery('#orders_op_think_problems_A').remove(); alert("确认OK");}
		});	
	}
}
</script>
<?php

/* 禁止选中某些内容 {*/
if($can_copy_sensitive_information !== true){
?>
<script type="text/javascript"> 
//元素中的属性sensitive="true"的元素为敏感元素
if (window.sidebar){	//其它浏览器
	var disabledObj = $('[sensitive="true"]');
	$(disabledObj).mousedown(function(){ return false; });
	$(disabledObj).click(function(){ return false; });
}else{	//IE浏览器
	var disabledObj = document.getElementsByTagName('*');
	for(var i=0; i<disabledObj.length; i++){
		if(disabledObj[i].sensitive == "true"){
			disabledObj[i].onselectstart = new Function ("return false");
		}
	}
}


	
</script>
<?php
}
/* 禁止选中某些内容 }*/
//订单更新的历史记录前两条的不同信息用颜色区分 start {
?>
<script type="text/javascript" src="includes/javascript/orders_history_data_vs.js"></script>
<?php
//订单更新的历史记录前两条的不同信息用颜色区分 end }

echo 'access_full_edit:'.$access_full_edit.'<br />';
echo 'access_order_cost:'.$access_order_cost;

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>