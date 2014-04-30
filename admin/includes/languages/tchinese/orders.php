<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Orders');
define('HEADING_TITLE_SEARCH', 'Search by Customers/Reservation Number/Tour Code:');
define('HEADING_TITLE_STATUS', 'Status:');
define('HEADING_ORDER_ID', 'Order ID');
define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_DATE_OF_DEPARTURE', 'Date of Departure');
define('TABLE_HEADING_PLACE_OF_DEPARTURE', 'Departure Place');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Tour Code');
define('TABLE_HEADING_PRODUCTS', 'Reservation List');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Retail<br> Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Retail<br> Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer<br> Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

//begin PayPal_Shopping_Cart_IPN
define('TABLE_HEADING_PAYMENT_STATUS', 'Payment Status');
//end PayPal_Shopping_Cart_IPN

define('ENTRY_CUSTOMER', 'Customer:');
define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_CREDIT_CARD_CVV', 'Credit Card CVV:'); 
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', 'Print Invoice');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_DELETE_DATA', 'Customers Name  ');
define('TEXT_INFO_DELETE_DATA_OID', 'Order Number  ');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');

define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Reservation Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Reservation Date:');
//define('EMAIL_TEXT_STATUS_UPDATE', 'Your reservation has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_STATUS_UPDATE', '' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your reservation are' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order does not exist.');
define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');
// START - Added for eCheck Payment Modual
  define('MODULE_PAYMENT_ECHECK_TEXT_PAY_TO', 'Pay to the<BR>Order of:');
  define('MODULE_PAYMENT_ECHECK_TEXT_MEMO', 'Memo:');
  define('MODULE_PAYMENT_ECHECK_TEXT_MEMO1', 'Memo:');
  define('MODULE_PAYMENT_ECHECK_TEXT_MEMO2', 'Online Order #');
  define('MODULE_PAYMENT_ECHECK_TEXT_NO_SIG1', 'No Signature Required');
  define('MODULE_PAYMENT_ECHECK_TEXT_NO_SIG2', 'Signature:');
  define('MODULE_PAYMENT_ECHECK_TEXT_NO_SIG3', 'Authorized by E-mail');
  define('TABLE_HEADING_TOUR_CODE', 'Tour Code');
  define('TABLE_HEADING_COST_PRICE', 'Cost');
  define('TEABLE_HEAIDNG_INVOICE_INFO','Invoice Info');
// END - Added for eCheck Payment Modual
define('TABLE_HEADING_UPDATED_BY','Updated by');
define('TABEL_HEADING_SALES_AMT','Sales Amount:');
define('TABEL_HEADING_INVOICE_AMT','Invoice Amount:');
define('TABEL_HEADING_INVOICE_NO','Invoice Number:');
define('TABLE_HEADING_PROVIDER_TOUR_CODE', 'Provider\'s Code');

######## Points/Rewards Module V2.1rc2a BOF ##################
define('ENTRY_NOTIFY_POINTS', 'Confirm Pending Points:');
define('ENTRY_QUE_POINTS', 'and Que');
define('ENTRY_QUE_DEL_POINTS', 'and Delete:');
define('ENTRY_CONFIRMED_POINTS', 'Points Confirmed.  ');
define('TEXT_ORDER_CANCELLED_COMMENT', 'Order Cancelled');
define('TEXT_ORDER_REFUNDED_COMMENT', 'Order Refunded');
######## Points/Rewards Module V2.1rc2a EOF ##################

define('TXT_PROVIDER_INFO', 'Provider Information:');
define('TXT_PRO_CONTACT_PERSON', 'Contact person');
define('TXT_PRO_COMPANY', 'Company');
define('TXT_PRO_TELEPHONE', 'Telephone#');
define('TEXT_PROVIDERS', 'Providers: ');
define('TXT_LAST_STATUS_CHANGED_BY', 'Status<br />Changed By');
define('TXT_AGO', 'ago');
define('SELECT_NONE', "Select ---");

define('TABLE_PROVIDERS_CONFIRMATION', 'Provider\'s Confirmation');
define('TEXT_SEAT_NO', 'Seat NO:');
define('TEXT_BUS_NO', 'Bus/Seat NO:');
define('TEXT_CONFIRMATION_NO', 'Confirmation NO:');
define('TEXT_PROVIDER_STATUS_HISTORY', 'Provider\'s status history');
define('TEXT_NO_PROVIDER_STATUS_HISTORY', 'No Provider\'s Status History Available');
define('SELECT_NONE', "Select ---");
define('TEXT_MESSAGE_TO_PROVIDER', '<b>Message to Provider:</b>');
define('PROVIDERS_CONFIRMATION_SENT', 'Message sent to provider');
define('TEXT_PROVIDERS_LAST_STATUS', '<b>Last status from provider:</b>');
define('TEXT_ADMIN_LAST_STATUS', '<b>Last status from admin:</b>');
define('TXT_SEND_MESSAGE', 'Send');
define('TXT_PROVIDER_STATUS_MAIL_FROM', '走四方網');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT', /*STORE_OWNER.*/TXT_PROVIDER_STATUS_MAIL_FROM.' has changed status of Reservation #%s (%s)');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY', "Hi,\n\n %s has updated Reservation #%s as below, \n\n Tour: %s \n Reservation: #%s \n Start Date: %s \n Reservation Status: %s %s \n\n For review or reply please checkout below link,\n\n <a href='%s'>%s</a>\n\n Thanks \n %s \n");
define('TEXT_INVOICE_NO', 'Invoice NO:');
define('TEXT_INVOICE_TOTAL', 'Invoice Total:');
define('TEXT_INVOICE_COMMENT', 'Invoice Comment:');
define('TEXT_PROVIDERS', 'Providers: ');
define('TXT_PROVIDER_INFO', 'Provider Information:');

define('TXT_FLIGHT_INFO', 'Flight Information<br />Available');
define('TITLE_FLIGHT_INFO', 'Flight Information:');
define('TITLE_AR', 'Arrival');
define('TITLE_DR', 'Departure');
define('TITLE_AR_AIRLINE_NAME', 'Airline Name:');
define('TITLE_DR_AIRLINE_NAME', 'Airline Name:');
define('TITLE_AR_FLIGHT_NUM', 'Flight Number:');
define('TITLE_DR_FLIGHT_NUM', 'Flight Number:');
define('TITLE_AR_AIRPORT_NAME', 'Airport Name:');
define('TITLE_DR_AIRPORT_NAME', 'Airport Name:');
define('TITLE_AR_DATE', 'Date:');
define('TITLE_DR_DATE', 'Date:');
define('TITLE_AR_TIME', 'Time:');
define('TITLE_DR_TIME', 'Time:');
define('TXT_TOUR_STARTS_ON', 'Tour starts on %s');
define('TXT_TOUR_ENDS_ON', 'Tour ends on %s');
define('PINK_ROW_EXPLAINATION_TEXT','* Order Total and Charge Captured Total are not same');
define('TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CC','我們已按要求將您為預訂支付的 $%s退到您的信用卡帳戶裏，在退款完成之前，此筆交易在您的信用卡帳單上會暫時顯示為Pending狀態，請給我們幾天處理時間。<br><br> 很高興能夠為您服務，歡迎再次訂購我們的產品。<br><br> We have refunded $%s to your credit card per your request for your reservation. The transaction may be displayed temporarily as a pending transaction before it is settled. Please allow a couple of days for it to appear on your credit card statement.<br><br>We appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future.');
define('TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_PAYPAL','我們已按要求將您為預訂支付的$%s退到您的PayPal帳戶中。<br><br> 很高興能夠為您服務，歡迎再次訂購我們的產品。<br><br> We have refunded $%s to your PayPal account per your request for your reservation.<br><br>We appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future.');
define('TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CHECK','我們已將$%s 以支票的方式退還給您，支票寄達需要幾天時間，請諒解。<br><br> 很高興能夠為您服務，歡迎再次訂購我們的產品。<br><br> We have refunded $%s to you. Please allow a couple of days for you to receive the refund check via mail.<br><br>We appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future.');
define('TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CASH_PAYMENT','我們已按要求將您為預訂支付的$%s 以現金的方式退還給您。<br><br> 很高興能夠為您服務，歡迎再次訂購我們的產品。<br><br> We have refunded $%s in cash to you per your request for your reservation.<br><br>We appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future.');
define('TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_WIRE_TRANFER','我們已經以電匯的方式將您為預訂支付的 $%s 退還給您。電匯退款需要幾天處理時間，請諒解。<br><br> 很高興能夠為您服務，歡迎再次訂購我們的產品。<br><br> We have refunded you $%s by wire transfer per your request for your reservation. Please allow a few days for you to receive the refund by wire transfer.<br><br>We appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future.');
define('TXT_REGARDS', '真誠地,');
define('TXT_SITE_ADDRESS_STORE', 'www.usitrip.com');
define('TXT_REPRESNT_SIGNATURE','走四方網 客戶服務部');
define('TXT_EMAIL_NOTIFY_CUSTOMER_REFUND_BY_CREDIT_ISSUED','我們已經按要求將您為預定支付的 $%s 以信用的方式退還到您的帳戶中。<br><br>請登陸我的走四方，在我的帳號>>我的信用中查看金額：<br><br>'.HTTP_SERVER.'/my_credits.php<br><br>很高興能夠為您服務，歡迎再次訂購我們的產品。');
?>