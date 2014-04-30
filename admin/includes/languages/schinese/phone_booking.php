<?php
/*
  $Id: create_order.php,v 1.3 2004/09/08 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2004 osCommerce

  Released under the GNU General Public License

*/
define('HEADING_STEP1', 'STEP 1 - Choose a customer & check their details');

define('HEADING_CREATE', 'Check Customer Details');

define('TEXT_SELECT_CUST', 'Select a Customer:');
define('BUTTON_TEXT_SELECT_CUST', 'Select This Customer');
define('TEXT_OR_BY', 'or Customer ID:');
define('BUTTON_TEXT_CHOOSE_CUST', 'Select This Customer ID');



define('HEADING_TITLE', 'Editing Order');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Adding a Product to Order');

define('ENTRY_UPDATE_TO_CC', '(Update to <b>Credit Card</b> to view CC fields.)');
define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_UNIT_PRICE', 'Unit Price');
define('TABLE_HEADING_TOTAL_PRICE', 'Total Price');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('ENTRY_CUSTOMER', 'Customer:');
define('ENTRY_SOLD_TO', 'Sold To:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'Ship To:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
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
define('ENTRY_CUSTOMER_DISCOUNT', 'Use a negitive number -1.00 ');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');

define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Order Date:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order does not exist.');
define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');
define('TEXT_SELECT_TOUR_CODE','Add Tour Code');
define('TEXT_SELECT_PAYMENT_METHOD','-- Select payment method --');
define('TEXT_GIFT_CERTIFICATE_CODE', 'Enter Gift Certificate Code:');
define('TEXT_ENTER_COUPON_CODE', 'Enter Coupon Code:');
define('ERROR_NO_INVALID_REDEEM_GC', 'Invalid Gift Certificate Code');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear ' . stripslashes($HTTP_POST_VARS['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'We welcome you to <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email us: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup for a free member account, please send an Email to us:' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_PASS_1', 'Your password for this account is ');
define('EMAIL_PASS_2', ', keep it in a safe place. (Please note: Your password is case sensitive.)');


define('TXT_DEAR', 'Dear');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_ORDER_NUMBER', 'Reservation Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Reservation Date:');
define('EMAIL_TEXT_PRODUCTS', 'Reservation List');
define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
define('TXT_GRAND_TOTAL', 'Grand Total:');
define('TXT_PAYMENT', 'Payment:');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');
define('EMAIL_TEXT_SUBJECT_ORDER_CREATED', 'usitrip Reservation Request');
define('TEXT_HOTEL_CHECK_IN_DATE', 'Check In Date and Time:');
define('TXT_RETURN_DEPARTURE_TIME_LOCATION', 'Return Departure Time & Location');
define('TXT_DEPARTURE_TIME_LOCATION', 'Departure Time & Location');
define('TXT_PRICE', 'Price');
define('TXT_ADULTS', 'Adults');
define('TXT_CHILDREN', 'Children');
define('TXT_ROOMS', 'Rooms');
define('GUESTNAME_BLANK_DEFAULT_TEXT', 'To be assigned');
?>