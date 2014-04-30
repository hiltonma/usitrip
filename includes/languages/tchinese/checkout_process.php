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

//define('EMAIL_TEXT_SUBJECT', STORE_NAME . '的訂單通知');
define('EMAIL_TEXT_SUBJECT', "走四方(Usitrip.com)訂單-%s支付(訂單號：%s) ");
define('EMAIL_TEXT_ORDER_NUMBER', "Reservation Number/訂單號:");
define('EMAIL_TEXT_INVOICE_URL', "Detailed Invoice/詳單:");
define('EMAIL_TEXT_DATE_ORDERED', "Reservation Date/預訂日期:");
define('EMAIL_TEXT_PRODUCTS', "Reservation List/訂單列表");
define('EMAIL_TEXT_SUBTOTAL', "Subtotal \n 小計:");
define('EMAIL_TEXT_TAX', '稅:        ');
define('EMAIL_TEXT_SHIPPING', '送貨: ');
define('EMAIL_TEXT_TOTAL', "Grand Total \n 總計:    ");
define('EMAIL_TEXT_DELIVERY_ADDRESS', "參團憑證郵寄地址:");
define('EMAIL_TEXT_BILLING_ADDRESS', "信用卡地址:");
define('EMAIL_TEXT_PAYMENT_METHOD', "支付方式:");
define('EMAIL_TEXT_INVOICE_LINK', '帳單明細表');
define('EMAIL_TEXT_DEPARTURE_TIME_AND_LOCATION', '出發地點：');

define('ERROR_SESSION_GUSET_LOSS','由於您填寫的資料信息不全，您需要重新填寫完整的遊客信息才能繼續！');

define('EMAIL_SEPARATOR', '-----------------------------------------------------------------------------------------------------------');
define('TEXT_EMAIL_VIA', '經由');
// email signature
define('EMAIL_TEXT_SIGNATURE','謝謝您的次序!' . "\n\n" . '請與我們聯係如果您有任何問題關於您的次序:' . "\n\n" . 'PHONE: +886-(0)2 2767 1689 - 0963-387766' . "\n\n" . 'Crystal Light Centrum' . "\n" . '信義區永吉路 30巷 102弄 14號' . "\n" . '110 台北市');
define('TXT_PROVIDER_STATUS_MAIL_FROM', '走四方網');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT', TXT_PROVIDER_STATUS_MAIL_FROM.' has changed status of Reservation #%s (%s)');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY', "Hi,\n\n %s has updated Reservation #%s as below, \n\n Tour: %s \n Reservation: #%s \n Start Date: %s \n Reservation Status: %s %s \n Message: %s. \n\n For review or reply please checkout below link,\n\n <a href='%s'>%s</a>\n\n Thanks \n %s \n");
if($_SERVER['HTTP_HOST'] == '208.109.123.18' || $_SERVER['HTTP_HOST'] == 'tw.usitrip.com'){ //only allow for demo site  
define('AUTO_CANCELED_EMAIL_TEXT_SUBJECT', '預訂# %s被自動取消');
}else{
define('AUTO_CANCELED_EMAIL_TEXT_SUBJECT', '預訂# %s被自動取消 - QA ORDER');
}
define('AUTO_CANCELED_EMAIL_TEXT_BODY', "由於重複預訂，您的預定單#%s被系統自動取消。我們正在處理您的另一個相同訂單#%s。如果是錯誤取消，我們為此而道歉。請通過以下方式，儘快與我們的客服人員聯繫以糾正對訂單#%s的錯誤處理： \n\n 郵件：service@usitrip.com \n");
define('TEXT_HOTEL_CHECK_IN_DATE', 'Check In Date and Time:');
define('TXT_RETURN_DEPARTURE_TIME_LOCATION', 'Return Departure Time & Location');
?>