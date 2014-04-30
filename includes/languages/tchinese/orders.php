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
define(EMAIL_COMMENTS_TICKET_ISSUED,"您的參團憑證已發送到您的電子郵箱。請仔細閱讀參團憑證上的所有信息，如果有問題，請及時通知我們。我們將不會對在出團的72小時內才通知參團憑證出錯而造成的任何後果承擔責任。\n\n您還可以通過訪問http://208.109.123.18/login.php ，「查看」您的前一個預訂，並點擊「參團憑證」按鈕查看您的參團憑證。");

//status id: 100000
define(EMAIL_TITLE_CONFIRMED,'Reservation Confirmation');
define(EMAIL_COMMENTS_CONFIRMED,"祝賀您！您在usitrip的預訂已經被確定了。請保留此確定郵件和訂單號以備以後使用。我們會在您出發日期的前兩天或三天，也可能更短時間內，發給您參團憑證。\n\n如果您預訂的團包含機場接/送機，並且您還沒有提供我們您的航班信息，請您儘快到http://208.109.123.18/account.php 更新您的航班信息。如果沒有航班信息，我們將無法出票。");

//status id: 100003
define(EMAIL_TITLE_UPDATE,'Reservation Update');

//status id: 100004
define(EMAIL_COMMENTS_NOT_AVAILABLE,"很遺憾，我們暫時不能確定您的預訂。對您所造成的不便，我們深感抱歉。");

//status id: 100005
define(EMAIL_COMMENTS_REFUNDED,"根據您對您預訂的申請，我們已經退款$到您的信用卡上。\n在此處理被完全確定前，可能暫時會顯示為待定的處理。幾天後，它便會在您的卡上顯示。\n希望能再次為您服務，期待您的下次光臨");

//status id: 100007  Payment Adjusted
define(EMAIL_COMMENTS_PAYMENT_ADJUSTED,"調整後的價格:    調整後的底價:\n\n原始價格：    原始底價：");

//status id: 6  CANCELLED
define(EMAIL_COMMENTS_CANCELLED,"很遺憾，現在我們暫時不能為您預訂此團，但仍然感謝您對我們的關注.\n\n期待您的再次訪問。");

//status id: 100011  Charge Failed
define(EMAIL_COMMENTS_CHARGE_FAILED,"我們試圖在您提供的信用卡上收取$  ，但系統操作失敗了。\n\n請您與您的信用卡發行商聯係，以確保下次我們在收到您的文件後再次操作時可以成功。\n\n或者如果您想使用另一張信用卡，請在信用卡帳戶委託表上填寫更改後的信息，並連同附加文件發送給我們。\nhttp://208.109.123.18/acknowledgement_of_card_billing.php.\n非常感謝您的購買，期待您儘快的回答。 ");

//status id: 100012  Flight Information Needed
define(EMAIL_COMMENTS_FLIGHT_INFO_NEEDED,"請在您預訂航班後，儘快登陸http://208.109.123.18/account.php在您的帳戶信息裏更新您的航班信息。沒有航班信息，我們將無法發送參團憑證給您。希望您能在完成次操作後發郵件到service@usitrip.com，以通知我們您已經進行了更新。非常感謝您的參與。");

//status id: 100013  Confirmed, Doc. Pending
define(EMAIL_COMMENTS_CONFIRM_DOC_PENDING,"祝賀您！您在usitrip的預訂已經被確定了。請保留此確定郵件和訂單號以備以後使用。\n\n在收到您發送的所需支持文件後我們會儘快發給您參團憑證（參加旅行團的憑證）。\n閱讀所需支持文件詳情以及如何發送文件，請參考http://208.109.123.18/acknowledgement_of_card_billing.php\n感謝您的參與。");

//status id: 100014  Confirmed, Full Doc. Received
define(EMAIL_COMMENTS_CONFIRM_FULL_DOC_RECEIVED,"感謝您在在最短的時間內發送給我們所需的文件。我們已經確定收到您的文件，一切正常，並處於進程中。我們將儘快發給您參團憑證。");

//status id: 100015  Confirmed, Partial Doc. Received
define(EMAIL_COMMENTS_CONFIRM_PARTIAL_DOC_RECEIVED,"感謝您在在最短的時間內發送給我們所需的文件。我們已經確定收到您的文件，但還缺少您的有效身份驗證照片。如果方便，請儘快發送給我們。");

//status id: 100016  Payment Pending
define(EMAIL_COMMENTS_PAYMENT_PENDING,"請在付款後儘快通知我們，以便我們進一步處理您的預訂。\n\n非常感謝您的參與。");

//status id: 100020  Cancellation Form Required
define(EMAIL_COMMENTS_CANCELLATION_FORM_REQUIRED,"我們已經收到您申請取消預訂的郵件；但根據「取消和退款政策」，我們不接受郵件或電子郵件取消。您必須通過手寫帶簽名的傳真、郵件或掃描的申請取消表進行取消.");
?>