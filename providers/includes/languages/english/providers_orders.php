<?php
/*
  $Id: providers_login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TEXT', 'My Orders');
define('ICON_EDIT', 'Edit');

define('MSG_SUCCESS', 'Order updated successfully');

define('TEXT_SEARCH', '<h2>搜索：</h2>');
define('TEXT_OK', 'OK');
define('SELECT_NONE', "Select ---");
define('TEXT_ORDER_ID', '订单号#:');
define('TEXT_ORDER_DATE', '订单日期:');
define('TEXT_ORDER_STATUS', '订单状态:');
define('TEXT_PAYMENT_METHOD', '付款方式:');
define('TEXT_PRODUCT_ID', '团号:');
define('TEXT_PRODUCT', '名称:');
define('TEXT_TOUR_CODE', STORE_NAME.' Code:');
define('TEXT_DEPARTURE_DATE', '出团日期:');
define('TEXT_ROOM_INFO', '房间人数:');
define('TEXT_PROVIDERS_ORDERS_STATUS', '地接订单状态:');
define('TEXT_PROVIDERS_COMMENTS', '地接留言:');
define('TEXT_FLIGHT_AVAILABLE', 'Available');
define('TEXT_FLIGHT_NA', 'N/A');
define('TEXT_CUSTOMER_NAME', '游客姓名：');
define('TEXT_CUSTOMER_CONTACT_NO', '客人的联系方式:');
define('TEXT_CUSTOMER_GENDER', '客人性别:');
define('TEXT_SEAT_NO', 'Seat NO:');
define('TEXT_BUS_NO', 'Bus/Seat NO:');
define('TEXT_CONFIRMATION_NO', 'Confirmation NO:');

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

define('TABLE_HEADING_ORDER_ID', '订单号');
define('TABLE_HEADING_PRODUCT_ID', '团号');
define('TABLE_HEADING_PRODUCT', 'Tour Name');
define('TABLE_HEADING_DATE_PURCHASED', '下单给地接的时间<span style="display:none">其实这字段的资料是“客户购买日期”</span>');
define('TABLE_HEADING_DATE_DEPARTURE', '出团日期');
define('TABLE_HEADING_TOUR_CODE', '团号');
define('TABLE_HEADING_PROVIDERS_CODE', 'Provider\'s Code');
define('TABLE_HEADING_ORDERS_STATUS', 'Orders Status');
define('TABLE_HEADING_PROVIDER_ORDERS_STATUS', 'Provider\'s Status');
define('TABLE_HEADING_ACTION', '操作');
define('TABLE_FLIGHT_INFO', '航班信息');

define('TEXT_NO_PROVIDER_STATUS_HISTORY', 'Status History Not Available');
define('TEXT_PROVIDER_STATUS_HISTORY', 'Status History:');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer<br> Notified');
define('TABLE_HEADING_DATE_ADDED', '添加日期');
define('TABLE_HEADING_STATUS', '状态');
define('TABLE_HEADING_COMMENTS', '内容');
define('TABLE_HEADING_UPDATED_BY','提交人');
define('TEXT_TOUR_ITINERARY', '行程及酒店:');
define('TEXT_TOUR_ITINERARY_NOTES', '');

define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT', '#%s provider\'s order status has been changed. (%s)');
define('EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY', "Hi,\n\n %s has changed status of their tour# %s of order#%s. \n Please follow this link to check changed status.\n <a href='%s'>%s</a>\n\n Thanks");

define('TEXT_SEARCH_INVOICE', '发票号');
define('TEXT_SEARCH_ORDER', '订单号');
define('TEXT_SEARCH_TOUR_CODE', '团号');
define('TEXT_SEARCH_ORDER_STATUS', 'Order Status');
define('TEXT_INVOICE_NO', '发票号:');
define('TEXT_INVOICE_TOTAL', '发票总金额:');
define('TEXT_INVOICE_COMMENT', '发票内容:');
define('LINK_TOUR_PACKAGES', 'Tour Packages');
define('LINK_TOURS', 'Tours');
define('LINK_PREVIEW', '查看电子参团凭证');
define('LINK_PRINT', 'Print');
define('LINK_CLOSE', 'Close');
define('TXT_TOUR_START_DATE', '出团日期');
define('TXT_TOUR_END_DATE', 'Tour End Date');
define('TABLE_HEADING_INVOICE_NO', '发票号码：');
define('TEXT_COMMENT_UPDATED_BY', 'Updated By');
define('TEXT_SPECIAL_NOTE', '注意事项:');
define('TEXT_T4F_ORDERS_STATUS', '需要处理的- '.STORE_NAME.' 订单');
define('TEXT_PROVIDERS_STATUS', '地接已处理的订单分类');
define('LNK_UPDATE_TEMPLATE', '更新行程模板');
define('MSG_SUCCESS_ETICKET_TEMPLATE', 'E-ticket template updated successfully');
define('TEXT_DEPARTURE_LOCATION', '上车时间和地点：');
define('TEXT_SEARCH_TRAVRLER_NAME', '线路名称');
define('CUSTOMER_SERVICE_PHONE', 'Tel: 1-626-898-7800&nbsp;&nbsp;1-225-754-4325&nbsp;&nbsp;1-225-304-4893');
define('CUSTOMER_SERVICE_HOURS', 'Fax: 1-626-569-0580&nbsp;&nbsp;<br />Accounting: 1-225-754-4326<br />Email：usitrip@gmail.com  order@usitrip.com');
define('TXT_CUSTOMER_SERVICE', '走四方网订单部（7*24小时）:');
define('TEXT_PRODUCT_AKA', 'Provider AKA:');
define('ENTRY_NOTIFY_TFF_SERVICES_TEAM', 'Notify usitrip');
define('TABLE_HEADING_NOTIFIED_ADMIN', 'Notified '.STORE_NAME);
define('TXT_TOUR_STARTS_ON', 'Tour starts on %s');
define('TXT_TOUR_ENDS_ON', 'Tour ends on %s');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('TXT_E_TICKET_FROM', '走四方网参团凭证 E-Ticket from '.STORE_OWNER_DOMAIN_NAME);
define('TXT_ETICKET_NUMBER_OF_ROOM', '房间数');
define('TXT_ETICKET_ADULT', 'Adult');
define('TXT_ETICKET_CHILD', 'Child');
define('TXT_TOUR_START_TIMES', 'Departure Time:');
define('TXT_FLIGHT_NAME_PRINT_A','Flight Name');
define('TXT_FLIGHT_NUMBER_PRINT_A','Flight Number');
define('TXT_AIRPORT_NAME_PRINT_A','Airport Name');
define('TXT_DATE_PRINT_A','Date');
define('TXT_TIME_PRINT_A','Time');
define('LINK_PREVIEW_PRINT_FIRST','打印走四方订单');
define('LINK_PREVIEW_PRINT_ALL','Print All Order');
define('TEXT_CUST_BUS_NO','Bus/Seat NO:');
define('TEXT_CUST_SEAT_NO','Seat NO:');
define('TXT_CONFIRMATION_NO',"Confirmation# ");
define('TXT_ETICKET_NOTE_CALL_TO_RECONFIRM', '<strong>Please call tour/product service company one day prior to tour start date to re-confirm your tour or pickup details.</strong><br />');
define('TXT_ETICKET_GUEST_CELL_PHONE_EMERGENCY_ONLY_CELL', '当日游客手机:');
define('TXT_SHOW_HISTORY_DEFINE','Show History');
define('TXT_SHOW_HISTORY_SR_NO','Sr.No.');
define('TXT_SHOW_HISTORY_GUEST_NAME','Guest Name');
define('TXT_SHOW_HISTORY_LODGING','Lodging');
define('TXT_SHOW_HISTORY_DEPARTURE_DATE','出团日期');
define('TXT_SHOW_HISTORY_UPDATED_BY','Updated by');
define('TXT_SHOW_HISTORY_MODIFYDATE','Modify Date');
define('TXT_SHOW_HISTORY_ROOMS','房间数');
define('TXT_SHOW_HISTORY_ADULT','成人');
define('TXT_SHOW_HISTORY_CHILD','小孩');
//define('TEXT_DEPARTURE_UPDATED_HISTORY','Departure and Lodging Updated History:');
define('TEXT_DEPARTURE_UPDATED_HISTORY','客户信息更新历史：');

define('BED_OPTION_INFO','床型信息');
define('TEXT_HOTEL_CHECKIN_DATE','入住日期:');
define('TEXT_HOTEL_CHECKOUT_DATE','退房日期:');
?>