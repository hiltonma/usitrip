<?php
/*
  $Id: checkout_confirmation.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!
  Released under the GNU General Public License

*/

define('NAVBAR_TITLE_1', '結帳');
define('NAVBAR_TITLE_2', '確認');

define('HEADING_TITLE', '核對訂單！');

define('HEADING_DELIVERY_ADDRESS', '出貨地址');
define('HEADING_SHIPPING_METHOD', '出貨方式');
define('HEADING_PRODUCTS', '訂購產品');
define('HEADING_PAYMENT_METHOD', '付款方式');
define('HEADING_PAYMENT_INFORMATION', '付款資訊');
define('HEADING_ORDER_COMMENTS', HEADING_ORDER_COMMENTS);

//amit added
define('TEXT_EDIT', '編輯');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', '繼續支付流程');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', '確認這張訂單。');
//please change the info_id=1 to the ID number for your condition in the information system
define('CONDITION_AGREEMENT', '已閱讀並同意 <a href="order_agreement.php" target="_blank" style="text-decoration: underline;">《訂購協議》</a>');
//define('CONDITION_AGREEMENT', '我已經閱讀了 <a href="customer-agreement.php" target="_blank" style="text-decoration: underline;">客戶協議</a> 並且同意協議的所有條件與條款，同意繼續支付流程以完成線上預訂。');

//define('CONDITION_AGREEMENT', 'I have read the <a href="customer-agreement.php" target="_blank">Customer Agreement</a> and I have agreed to all its terms and conditions before I make online reservation. Continue check-out procedure to make online reservation.');
define('CONDITION_AGREEMENT_WARNING', '請仔細閱讀我們的客戶協議，請您確認相關條款並勾選後，點擊確認訂單按鈕。');

define('TEXT_GUEST_INFO_FLIGHT_INFO','游客信息和航班信息');
define('TEXT_FLIGHT_INFO_IF_AVAILABLE','（如果您已經預訂了機票，請提供您的航班信息，您也可以隨後登陸在進行更新）');
//define('TEXT_INFO_GUEST_NAME','顧客中文名');
define('TEXT_INFO_GUEST_NAME','顧客%s');
define('TEXT_FLIGHT_INFO_IF_APPLICABLE','如果您有預訂航班，請填寫航班信息');
define('TEXT_ARRIVAL_AIRLINE_NAME','航空公司');
define('TEXT_DEPARTURE_AIRLINE_NAME','航空公司');
define('TEXT_ARRIVAL_FLIGHT_NUMBER','接機航班');
define('TEXT_DEPARTURE_FLIGHT_NUMBER','送機航班');
define('TEXT_ARRIVAL_AIRPORT_NAME','到達機場名字');
define('TEXT_DEPARTURE_AIRPORT_NAME','出發機場名字');
define('TEXT_ARRIVAL_DATE','接機日期');
define('TEXT_DEPARTURE_DATE','送機日期');
define('TEXT_ARRIVAL_TIME','到達時間');
define('TEXT_DEPARTURE_TIME','起飛時間');
define('TEXT_EMERGENCY_CONTACT_NUM','聯繫電話:');
define('TEXT_EMERGENCY_CASE_AVALILABLE','（如果有請提供以備緊急時候使用）');
define('TEXT_CELL_NUMBER','移動電話數字:');

define('TEXT_SHIPPING_METHOD','寄送方式：');
define('TEXT_SIMPLE_DIS_EMAIL','電子郵件');
define('TEXT_DIS_EMAIL','電子郵件：');
define('TEXT_DIS_CUSTIMER','顧客：');
define('TEXT_DIGITAL_PRODUCTS','');
define('TEXT_INFO_GUEST_BODY_WEIGHT','顧客的體重');
define('TEXT_CHILD_BIRTH_ERROR_MSG','您所填寫的小孩出生日期 %s 為無效的日期，小孩年齡應在 %s 歲以下。請重新填寫參團者的信息。'); //Invalid child age for child birth date 05/07/1990. Child age should be under 12 years at time of travel. Please edit tour member information.
define('TEXT_CHILD_BIRTH_ERROR_MIN_MSG','您所填寫的兒童出生日期 %s 為無效的日期，兒童年齡應在 %s 歲以下。請重新填寫參團者的信息。'); 


//howard added
define('HEADING_TOURS_INFORMATION','訂單資訊');
define('TEXT_SUBMIT_MSN','正在提交訂單，這個過程需要花費您的一些時間，請耐心等候...');

define('TEXT_GUEST_NAME','顧客%s姓名:');

define('TXT_DIS_ENTRY_GUEST_ADULT','');
define('TXT_ROOM_INFORMATION','房間信息:');
define('TXT_DIS_ENTRY_GUEST_CHILD','(兒童)');
define('ADULT_AVERAGE_PAY','每個成人需付');
define('CHILD_AVERAGE_PAY','每個小孩需付');

//howard added end
?>
