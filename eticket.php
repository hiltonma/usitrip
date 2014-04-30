<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  
  require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ETICKET);  
  define(IMAGE_BUTTON_PRINT,db_to_html('打印'));
   if (!isset($HTTP_GET_VARS['order_id']) || (isset($HTTP_GET_VARS['order_id']) && !is_numeric($HTTP_GET_VARS['order_id']))) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
  $_GET['products_id'] = (int)$_GET['products_id'] ? (int)$_GET['products_id'] : (int)$_GET['pid']; 
  
  //check Permissions start
  $e_permissions = true;
  $check_sql = tep_db_query("select confirmed from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$HTTP_GET_VARS['order_id'] . "'  and products_id='".(int)$_GET['products_id']."'");
  $check_row = tep_db_fetch_array($check_sql);
  if($check_row['confirmed']!="1"){
	  $e_permissions = false;
  }
  if($e_permissions == true){
  	  // 更新 电子参团凭证为 已查看状态 by lwkai add 2012-05-11
  	  tep_db_query("update " . TABLE_ORDERS_PRODUCTS_ETICKET . " SET is_read=1 where orders_id = '" . (int)$HTTP_GET_VARS['order_id'] . "' AND products_id='" . (int)$_GET['products_id'] . "'");
  	  // end by lwkai 2012-05-11
	  $customer_info_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". (int)$HTTP_GET_VARS['order_id'] . "'");
	  $customer_info = tep_db_fetch_array($customer_info_query);
	  if ($customer_info['customers_id'] != $customer_id) {
		//如果不是用户下的订单就检查结伴同游
		$check_t_c_sql = tep_db_query('SELECT orders_travel_companion_id FROM `orders_travel_companion` WHERE orders_id="'.(int)$HTTP_GET_VARS['order_id'].'" AND products_id="'.(int)$_GET['products_id'].'" AND customers_id="'.$customer_id.'" limit 1');
		$check_t_c_row = tep_db_fetch_array($check_t_c_sql);
		if(!(int)$check_t_c_row['orders_travel_companion_id']){
			$e_permissions = false;
		}
	  }
  }
  if($e_permissions == false){
	  $messageStack->add_session('global', db_to_html('不存在的电子参团凭证！'));
	  tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
	  exit;
  }
  //check Permissions end
  

  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];


  $oID = tep_db_prepare_input($HTTP_GET_VARS['order_id']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

  include(DIR_FS_CLASSES . 'order.php');
  $order = new order($oID);
  $email_list = get_order_travel_companion_email_list((int)$oID,(int)$_GET['products_id']);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo 'usitrip - Eticket - ' . $oID; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
<script type="text/javascript">
function printPage(obj) {
	if (obj == 'true') {
		var body = window.document.body.innerHTML;
		var printArea = window.document.getElementById("printPage").innerHTML;
		window.document.body.innerHTML = printArea;
		window.print("", 5000);
		window.document.body.innerHTML = body;
	}
}
   
function doPrint(){
	var body = window.document.body.innerHTML;
	bdhtml=body;
	sprnstr="<!--startprint-->";
	eprnstr="<!--endprint-->";
	prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);
	prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
	window.document.body.innerHTML=prnhtml;
	window.print("", 5000);
	window.close();
}
</script>

</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<div id="menmtop">
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="100" align="left" valign="bottom" class="main"><a href="<?php echo tep_href_link('eticket.php','order_id='.(int)$oID.'&pid='.(int)$_GET['products_id'].'&i='.$_GET['i'].'&sendmail=true')?>"><?php echo db_to_html('发送到我的邮箱')?></a></td>
		<td valign="top" align="left" class="main"><script language="JavaScript">
  if (window.print) {
    document.write('<a href="javascript:void(0);" onClick="javascript:doPrint();" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?= DIR_WS_IMAGES;?>close_window.jpg' border=0></a></p></td>
      </tr>
    </table>
</div>
<div id="printPage">
<!--startprint-->
<?php


 ob_start();
 include("eticket_email.php"); 
 $email_order = db_to_html(ob_get_clean());
 echo $email_order;
 //$email_order = ob_get_contents();
?>
<!--endprint-->
</div>
<?php
if(isset($_GET['sendmail']) && $_GET['sendmail']=='true')
{
  
  
  //$emp_email = $order->customer['email_address'];
  $emp_email = tep_get_customers_email($customer_id);
  $email_subject = STORE_OWNER.db_to_html(" 参团凭证 订单号：".(int)$oID." ");

// zhh fix and added
					
	$to_name = db_to_html($order->customer['name']);

  /* 只有后台才需要下面的代码
  if(tep_not_null($email_list)){
  	$emp_email = $email_list;
	$to_name = $email_list;
  }*/
	
	$to_email_address = $emp_email;
	$email_subject = $email_subject;
	$email_text = preg_replace('/[[:space:]]+/',' ',$email_order);
	$email_text .= email_track_code('eTicket',$to_email_address,(int)$oID );
	
	$from_email_name = STORE_OWNER;
	$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
	tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');


	//tep_db_query("update ".TABLE_ORDERS_PRODUCTS_ETICKET." set confirmed=1  where orders_id = '" . (int)$oID . "'  and products_id=".$_GET['products_id']."");
  echo '<div align="center"><b style="color:#009933;">'.db_to_html('电子参团凭证已经发送到').$to_email_address.'</b></div>';
}

?>


</body>
</html>
<?php require(DIR_FS_INCLUDES . 'application_bottom.php'); ?>