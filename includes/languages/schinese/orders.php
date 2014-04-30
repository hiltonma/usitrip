<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Orders');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('HEADING_ORDER_ID', 'Order ID');
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
define('EMAIL_TEXT_STATUS_UPDATE', 'Your reservation has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
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


//James add for the email which was used to reply customers for update one's order status

//status id: 100001
define(EMAIL_TITLE_RECEIPT_SENT,'Receipt of Reservation');

//status id: 100002
define(EMAIL_TITLE_TICKET_ISSUED,'Your E-Ticket has been issued.');
define(EMAIL_COMMENTS_TICKET_ISSUED,"您的参团凭证已发送到您的电子邮箱。请仔细阅读参团凭证上的所有信息，如果有问题，请及时通知我们。我们将不会对在出团的72小时内才通知参团凭证出错而造成的任何后果承担责任。\n\n您还可以通过访问http://www.usitrip.com/login.php ，「查看」您的前一个预订，并点击「参团凭证」按钮查看您的参团凭证。");

//status id: 100000
define(EMAIL_TITLE_CONFIRMED,'Reservation Confirmation');
define(EMAIL_COMMENTS_CONFIRMED,"祝贺您！您在usitrip的预订已经被确定了。请保留此确定邮件和订单号以备以后使用。我们会在您出发日期的前两天或三天，也可能更短时间内，发给您参团凭证。\n\n如果您预订的团包含机场接/送机，并且您还没有提供我们您的航班信息，请您尽快到http://www.usitrip.com/account.php 更新您的航班信息。如果没有航班信息，我们将无法出票。");

//status id: 100003
define(EMAIL_TITLE_UPDATE,'Reservation Update');

//status id: 100004
define(EMAIL_COMMENTS_NOT_AVAILABLE,"很遗憾，我们暂时不能确定您的预订。对您所造成的不便，我们深感抱歉。");

//status id: 100005
define(EMAIL_COMMENTS_REFUNDED,"根据您对您预订的申请，我们已经退款$到您的信用卡上。\n在此处理被完全确定前，可能暂时会显示为待定的处理。几天后，它便会在您的卡上显示。\n希望能再次为您服务，期待您的下次光临");

//status id: 100007  Payment Adjusted
define(EMAIL_COMMENTS_PAYMENT_ADJUSTED,"调整后的价格:    调整后的底价:\n\n原始价格∶    原始底价∶");

//status id: 6  CANCELLED
define(EMAIL_COMMENTS_CANCELLED,"很遗憾，现在我们暂时不能为您预订此团，但仍然感谢您对我们的关注.\n\n期待您的再次访问。");

//status id: 100011  Charge Failed
define(EMAIL_COMMENTS_CHARGE_FAILED,"我们试图在您提供的信用卡上收取$  ，但系统操作失败了。\n\n请您与您的信用卡发行商联系，以确保下次我们在收到您的文件后再次操作时可以成功。\n\n或者如果您想使用另一张信用卡，请在信用卡帐户委托表上填写更改后的信息，并连同附加文件发送给我们。\nhttp://www.usitrip.com/acknowledgement_of_card_billing.php.\n非常感谢您的购买，期待您尽快的回答。 ");

//status id: 100012  Flight Information Needed
define(EMAIL_COMMENTS_FLIGHT_INFO_NEEDED,"请在您预订航班后，尽快登陆http://www.usitrip.com/account.php在您的帐户信息里更新您的航班信息。没有航班信息，我们将无法发送参团凭证给您。希望您能在完成次操作后发邮件到service@usitrip.com，以通知我们您已经进行了更新。非常感谢您的参与。");

//status id: 100013  Confirmed, Doc. Pending
define(EMAIL_COMMENTS_CONFIRM_DOC_PENDING,"祝贺您！您在usitrip的预订已经被确定了。请保留此确定邮件和订单号以备以后使用。\n\n在收到您发送的所需支持文件后我们会尽快发给您参团凭证（参加旅行团的凭证）。\n阅读所需支持文件详情以及如何发送文件，请参考http://www.usitrip.com/acknowledgement_of_card_billing.php\n感谢您的参与。");

//status id: 100014  Confirmed, Full Doc. Received
define(EMAIL_COMMENTS_CONFIRM_FULL_DOC_RECEIVED,"感谢您在在最短的时间内发送给我们所需的文件。我们已经确定收到您的文件，一切正常，并处于进程中。我们将尽快发给您参团凭证。");

//status id: 100015  Confirmed, Partial Doc. Received
define(EMAIL_COMMENTS_CONFIRM_PARTIAL_DOC_RECEIVED,"感谢您在在最短的时间内发送给我们所需的文件。我们已经确定收到您的文件，但还缺少您的有效身份验证照片。如果方便，请尽快发送给我们。");

//status id: 100016  Payment Pending
define(EMAIL_COMMENTS_PAYMENT_PENDING,"请在付款后尽快通知我们，以便我们进一步处理您的预订。\n\n非常感谢您的参与。");

//status id: 100020  Cancellation Form Required
define(EMAIL_COMMENTS_CANCELLATION_FORM_REQUIRED,"我们已经收到您申请取消预订的邮件；但根据「取消和退款政策」，我们不接受邮件或电子邮件取消。您必须通过手写带签名的传真、邮件或扫描的申请取消表进行取消.");
?>