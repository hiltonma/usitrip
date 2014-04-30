<?php
/*
  $Id: checkout_process.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

//define('EMAIL_TEXT_SUBJECT', STORE_NAME . '的订单通知');
//define('EMAIL_TEXT_SUBJECT', db_to_html(STORE_NAME) . " 预定单 # %s (单号：%s)");
define('EMAIL_TEXT_SUBJECT', "走四方(Usitrip.com)订单-%s支付(订单号：%s) ");
define('EMAIL_TEXT_ORDER_NUMBER', "Reservation Number/订单号:");
define('EMAIL_TEXT_INVOICE_URL', "Detailed Invoice/详单:");
define('EMAIL_TEXT_DATE_ORDERED', "Reservation Date/预订日期:");
define('EMAIL_TEXT_PRODUCTS', "Reservation List/订单列表");
define('EMAIL_TEXT_SUBTOTAL', "Subtotal \n 小计:");
define('EMAIL_TEXT_TAX', '税:        ');
define('EMAIL_TEXT_SHIPPING', '送货: ');
define('EMAIL_TEXT_TOTAL', "Grand Total \n 总计:    ");
define('EMAIL_TEXT_DELIVERY_ADDRESS', "参团凭证邮寄地址:");
define('EMAIL_TEXT_BILLING_ADDRESS', "信用卡地址:");
define('EMAIL_TEXT_PAYMENT_METHOD', "支付方式:");
define('EMAIL_TEXT_INVOICE_LINK', '账单明细表');
define('EMAIL_TEXT_DEPARTURE_TIME_AND_LOCATION', '出发地点：');

define('ERROR_SESSION_GUSET_LOSS','由于您填写的数据信息不全，您需要重新填写完整的游客信息才能继续！');

define('TEXT_EMAIL_VIA', '经由');
// email signature
define('EMAIL_TEXT_SIGNATURE','谢谢您的次序!' . "\n\n" . '请与我们联系如果您有任何问题关于您的次序:' . "\n\n" . 'PHONE: +886-(0)2 2767 1689 - 0963-387766' . "\n\n" . 'Crystal Light Centrum' . "\n" . '信义区永吉路 30巷 102弄 14号' . "\n" . '110 台北市');
define('TXT_PROVIDER_STATUS_MAIL_FROM', '走四方网');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT', TXT_PROVIDER_STATUS_MAIL_FROM.' has changed status of Reservation #%s (%s)');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY', "Hi,\n\n %s has updated Reservation #%s as below, \n\n Tour: %s \n Reservation: #%s \n Start Date: %s \n Reservation Status: %s %s \n Message: %s. \n\n For review or reply please checkout below link,\n\n <a href='%s'>%s</a>\n\n Thanks \n %s \n");
if($_SERVER['HTTP_HOST'] == '208.109.123.18' || $_SERVER['HTTP_HOST'] == 'cn.usitrip.com'){ //only allow for demo site  
define('AUTO_CANCELED_EMAIL_TEXT_SUBJECT', '预订#%s被自动取消 ');
}else{
define('AUTO_CANCELED_EMAIL_TEXT_SUBJECT', '预订#%s被自动取消 - QA ORDER');
}
define('AUTO_CANCELED_EMAIL_TEXT_BODY', "由于重复预订，您的预定单#%s被系统自动取消。我们正在处理您的另一个相同订单#%s。如果是错误取消，我们为此而道歉。请通过以下方式，尽快与我们的客服人员联系以纠正对 订单#%s的错误处理：\n\n邮件：service@usitrip.com\n");
define('TEXT_HOTEL_CHECK_IN_DATE', 'Check In Date and Time:');
define('TXT_RETURN_DEPARTURE_TIME_LOCATION', 'Return Departure Time & Location');
?>