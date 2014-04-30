<?php
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id')){
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
$provider_id=$_SESSION['providers_id'];
$agency_id=$_SESSION['providers_agency_id'];
$is_provider_can_send_eticket=get_provider_can_send_eticket($agency_id);

require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_ORDERS);
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' .'eticket.php');

if(isset($_GET['action']) && $_GET['action']=='mailsent'){
	echo '<div align="center"><font color="#FF0000">Mail sent successfully</font></div>';
}

  $customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $customer_number = tep_db_fetch_array($customer_number_query);

  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];

  require_once(DIR_FS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

  require_once(DIR_FS_CLASSES . 'order.php');
  $order = new order($oID);
  $email_list = get_order_travel_companion_email_list((int)$oID,(int)$_GET['products_id']);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo 'usitrip - Eticket - ' . $oID; ?></title>
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main"><script type="text/javascript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo LINK_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo LINK_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?php echo DIR_WS_TEMPLATE_IMAGES;?>close_window.jpg' border=0></a></p></td>
      </tr>
    </table>
<?php
//service@usitrip.com

 ob_start();
 require_once("eticket_email.php"); 
 $email_order = ob_get_contents();

//Strt - send mail to customer
if(isset($_GET['action']) && $_GET['action']=='sendmail' && $is_provider_can_send_eticket=='1'){
	$to_name = $order->customer['name'];
	$emp_email = $order->customer['email_address'];
	if(tep_not_null($email_list)){
		$emp_email = $email_list;	
		$to_name = $email_list;  
	}
	
	//E-ticket Log Start
	$eticket_log_data_array = array('orders_products_id' => $order->products[$i]['orders_products_id'],
									'orders_eticket_last_modified' => 'now()',
									'orders_eticket_content' => $email_order,
									'orders_eticket_is_customer_notified' => 1,
									'orders_eticket_updated_by' => tep_db_input($_POST['popc_updated_by']),
									'orders_eticket_updator_type' => 1
								   );
	tep_db_perform(TABLE_ORDERS_ETICKET_LOG, $eticket_log_data_array);	
	$orders_eticket_log_id = tep_db_insert_id();
	//E-ticket Log End
	
	//Send mail to customer
	$headers = "From: ".STORE_OWNER_DOMAIN_NAME." <".STORE_OWNER_EMAIL_ADDRESS.">\n"; 
  $headers .= "MIME-Version: 1.0\r\n"; 
  $headers .= "Content-type: text/html; charset=".CHARSET."\r\n";
  //amit added for tacking eticket status start
	$email_order .= email_track_code('eTicket',$emp_email,(int)$oID, 'orders_id', (int)$orders_eticket_log_id);
	//amit added for tacking eticket status end
	
	// 在测试版本取消这个发邮件功能
	//$returnmail = mail($emp_email, TXT_E_TICKET_FROM,$email_order,$headers);
	//
//	tep_mail($to_name, $emp_email, TXT_E_TICKET_FROM, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	tep_db_query("update ".TABLE_ORDERS_PRODUCTS_ETICKET." set confirmed=1  where orders_products_id = '" . (int)$order->products[$i]['orders_products_id'] . "' ");
	$redirect_to = tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS_PREVIEW, tep_get_all_get_params(array('action')).'&action=mailsent', "SSL");
	
	//Change providers orders products status
	$orders_id = (int)$_GET['oID'];
	$products_id = (int)$order->products[$i]['id'];
	$products_name = (int)$order->products[$i]['name'];
	$provider_order_status_id='28';
	$orders_products_id=$order->products[$i]['orders_products_id'];
	$sql_data_array = array('orders_products_id' => $orders_products_id,
								'provider_order_status_id' => $provider_order_status_id,
								'provider_comment' => $_POST['provider_comment'],
								'provider_status_update_date' => 'now()',
								'popc_user_type' => '1',
								'popc_updated_by' => tep_db_input($_POST['popc_updated_by']),
								'notify_usi4trip' => '1');
	tep_db_perform(TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY, $sql_data_array);
	
	//Notify store owner for updated status
	$qry_providers_detail="SELECT agency_name FROM ".TABLE_TRAVEL_AGENCY." WHERE agency_id='".$_SESSION['providers_agency_id']."'";
	$res_providers_detail=tep_db_query($qry_providers_detail);
	$row_providers_detail=tep_db_fetch_array($res_providers_detail);
	$orders_link=tep_href_link(FILENAME_ADMIN_ORDERS, 'action=edit&oID=' . $orders_id);
	tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT, $orders_id, tep_get_provider_order_status_name($provider_order_status_id)), sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY, $row_providers_detail['agency_name'], $products_id." - ".$products_name, $orders_id, $orders_link, $orders_link), $row_providers_detail['agency_name'], $_SESSION['providers_email_address']);
	
	//Change order status
	//100073 = Ticket issued by provider
	//记录电子参团凭证地接直接发给客人的订单历史状态
	/*
	$orders_status='100073';
	tep_db_query("update " . TABLE_ORDERS . " set orders_status = '".$orders_status."', last_modified = now() where orders_id = '" . (int)$orders_id . "'");
	$sql_data_array = array('orders_id' => $orders_id,
				'orders_status_id' => $orders_status,
				'date_added' => 'now()',
				'customer_notified' => '0',
				'comments' => 'Ticket issued by provider');
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	*/
	//当地接更新信息时直接也更新订单状态
	if($orders_status_id = tep_get_orders_status_id_form_provider_order_status($provider_order_status_id)){
		tep_update_orders_status($orders_id, $orders_status_id, CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,'供应商给客人发电子参团凭证，系统自动更新订单状态！');
	}
?>
	<script type="text/javascript" language="javascript">
		window.location = "<?php echo $redirect_to;?>";
	</script><?php exit;
}
//End - send mail to customer


			$is_provider_can_send_eticket = '0';	//暂时关闭供应商发电子参团凭证功能
			if($is_provider_can_send_eticket == '1'){
				$qry_default_eticket_comment = "SELECT providers_default_eticket_comment FROM ".TABLE_TRAVEL_AGENCY." WHERE agency_id='".$agency_id."'";
				$res_default_eticket_comment = tep_db_query($qry_default_eticket_comment);
				$row_default_eticket_comment = tep_db_fetch_array($res_default_eticket_comment);
				$providers_default_eticket_comment = tep_db_prepare_input($row_default_eticket_comment['providers_default_eticket_comment']);
		
			echo tep_draw_form('provider_email_eticket', tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS_PREVIEW, tep_get_all_get_params(array('action')).'&action=sendmail', "SSL"), 'post');
			?>
			<table border="0" width="100%" cellpadding="2" cellspacing="2" style="clear:left;">
				<tr class="login" valign="top">
					<td><strong><?php echo TEXT_PROVIDERS_COMMENTS;?></strong></td>
					<td><?php echo tep_draw_textarea_field("provider_comment", $wrap, 80, 5, $providers_default_eticket_comment);?></td>
				</tr>
				<tr class="login">
						<td><strong><?php echo TEXT_COMMENT_UPDATED_BY;?></strong></td>
						<td><?php echo tep_draw_input_field("popc_updated_by", tep_db_output($_POST['popc_updated_by']));?></td>
					</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left">
						<?php //echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS_PREVIEW, tep_get_all_get_params(array('action')).'&action=sendmail', "SSL").'">'.tep_image_button('button_send_mail.gif', IMAGE_SEND_EMAIL).'</a>';
						echo '<a href="javascript:void(0);" onclick="submitform();">'.tep_image(DIR_WS_TEMPLATE_IMAGES.'buttons/' .$language . '/button_send_mail.gif', IMAGE_SEND_EMAIL, '65', '22').'</a>';
						?>
					</td>
				</tr>
			</table>
			</form>
<?php }
?>
<script type="text/javascript">
function submitform(){
	with(document.provider_email_eticket){
		if(popc_updated_by.value==""){
			alert("Please enter Updated By");
			popc_updated_by.focus();
			return false;
		}
		submit();
	}
}
</script>
</body>
</html>