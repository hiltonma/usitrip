<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Customer Rep Orders');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ENTRY_STARTDATE', 'Start Date:&nbsp;');
define('ENTRY_TODATE', 'End Date:&nbsp;');
define('ENTRY_SUBMIT', 'Submit');
define('ENTRY_ADMIN_MEMBER', 'Admin Member');
define('TEXT_ALL_ADMINS', 'All Admin Members');
define('HEADING_ORDER_ID', 'Order ID');
define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_DATE_OF_DEPARTURE', 'Date of Departure');
define('TABLE_HEADING_ADMIN_CUSTOMER', 'Admin Customers');
define('TABLE_HEADING_STATUS', 'Order Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
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
// END - Added for eCheck Payment Modual
?>
