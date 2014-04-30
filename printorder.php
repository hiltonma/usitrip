<?php
/*
  $Id: printorder.php,v 1.1 2003/01 xaglo

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

//取得所有属于邮轮团的产品属性ID
$cruisesOptionIds = getAllCruisesOptionIds();


if (tep_session_is_registered('noaccount')){

 }else if (!tep_session_is_registered('customer_id')){
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $customer_number = tep_db_fetch_array($customer_number_query);

  if ($customer_number['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
	exit();
  }

  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ORDERS_PRINTABLE);

  require(DIR_FS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['order_id']);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php //echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo STORE_NAME . ' - ' . 'Print Order' . ' #' . $HTTP_GET_VARS['order_id']; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">


<!-- body_text //-->
<table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main"><script type="text/javascript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?= DIR_WS_IMAGES;?>close_window.jpg' border="0" alt="" /></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
  </tr>
  <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
          
          <tr>
            <td colspan="2" align="left" class="titleHeading"><img src="image/logo-dindan.gif" alt="<?php echo STORE_NAME ?>" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="titleHeading"><b><?php echo TITLE_PRINT_ORDER . ' #' . $HTTP_GET_VARS['order_id']; ?></b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
if ($customer_number['customers_id'] == $customer_id) {
?>
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><?php echo '<b>' . ENTRY_PAYMENT_METHOD . '</b> ' . db_to_html($order->info['payment_method']); ?></td>
      </tr>
      <tr>
        <td class="main"><?php //echo db_to_html($payment_info); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="main"><?php echo '<b>' . ENTRY_DATE_PURCHASED . '</b> ' . tep_get_date_disp(substr($order->info['date_purchased'],0,10)).substr($order->info['date_purchased'],10);  ?></td>
  </tr>
  
  <?php
  //取消显示寄送地址
  $address_off = false;
  if($address_off==true){
  ?>
  <tr>
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo db_to_html(tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>')); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo db_to_html(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>')); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  <?php
  }
  //取消显示寄送地址 end
  ?>
  
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#000000">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
        <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . db_to_html($order->products[$i]['name']) . '<br>';

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
		if($order->products[$i]['attributes'][$j]['price']>0){
			echo db_to_html('<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i><br></small></nobr>');
		}else{
			if(trim($order->products[$i]['attributes'][$j]['option'])!="" && !in_array($order->products[$i]['attributes'][$j]['option_id'], (array)$cruisesOptionIds)){
				echo db_to_html('<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i><br></small></nobr>');
			}
		}
		
      }
    }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
      echo '        <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '      </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" colspan="7"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="smallText">' . db_to_html($order->totals[$i]['title']) . '</td>' . "\n" .
         '            <td align="right" class="smallText">' . db_to_html($order->totals[$i]['text']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" class="main"><?php echo nl2br(db_to_html(STORE_NAME_ADDRESS)); ?></td>
  </tr>
<?php
} else {
?>
  <tr>
    <td align="left" class="main"><?php echo ENTRY_ACCESS_ERROR; ?></td>
  </tr>
<?php
}
?>

<tr>
<td align="left" class="main">
<?php echo db_to_html('<br><br>
<b>注意事项：</b><br>
1.为避免信用卡支付的客户避免遭遇信用卡欺诈, 如果您的订单符合以下一种情况，将需要您向我们提供相关文档和证明。<br><br>

&#8226; 信用卡持卡人不参加旅游<br>
&#8226; 您的信用卡地址不能通过系统核实<br>
&#8226; 订单消费金额超过$1800<br>

请进入链接<a class="sp3" href="'.tep_href_link('download_acknowledgement_card_billing.php').'" target="_blank">'.tep_href_link('download_acknowledgement_card_billing.php').'</a>下载相关文档和证明模板。<br>

2.如果您预订的行程提供机场接送服务，请您尽早向我们提供航班信息，否则将无法安排机场接送服务。请您在填写航班信息完毕后，发送电子邮件通知我们，以便我们及时处理。
填写方式：登录您的帐户'.tep_href_link('account.php').'后点击<br>
“我的订单”--&gt;“新订单”填写航班信息。<br>
3.有关行程变更，行程取消等服务标准请仔细阅读《取消和退款条例》。<br>
链接如下：<a class="sp3" href="'.tep_href_link('cancellation-and-refund-policy.php').'" target="_blank">'.tep_href_link('cancellation-and-refund-policy.php').'</a><br>
');
?></td>
</tr>

<tr>
  <td align="left" class="main">&nbsp;</td>
</tr>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_FS_INCLUDES . 'application_bottom.php'); ?>
