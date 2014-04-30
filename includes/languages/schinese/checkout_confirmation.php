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

define('NAVBAR_TITLE_1', '结帐');
define('NAVBAR_TITLE_2', '确认');

define('HEADING_TITLE', '核对订单！');

define('HEADING_DELIVERY_ADDRESS', '出货地址');
define('HEADING_SHIPPING_METHOD', '出货方式');
define('HEADING_PRODUCTS', '订购产品');
define('HEADING_PAYMENT_METHOD', '付款方式');
define('HEADING_PAYMENT_INFORMATION', '付款资讯');
define('HEADING_ORDER_COMMENTS', HEADING_ORDER_COMMENTS);

//amit added
define('TEXT_EDIT', '编辑');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', '继续支付流程');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', '确认这张订单。');
//please change the info_id=1 to the ID number for your condition in the information system
define('CONDITION_AGREEMENT', '已阅读并同意 <a href="order_agreement.php" target="_blank" style="text-decoration: underline;">《订购协议》</a>');
//define('CONDITION_AGREEMENT', '我已经阅读了 <a href="customer-agreement.php" target="_blank" style="text-decoration: underline;">客户协议</a> 并且同意协议的所有条件与条款，同意继续支付流程以完成线上预订。');

//define('CONDITION_AGREEMENT', 'I have read the <a href="customer-agreement.php" target="_blank">Customer Agreement</a> and I have agreed to all its terms and conditions before I make online reservation. Continue check-out procedure to make online reservation.');
define('CONDITION_AGREEMENT_WARNING', '请仔细阅读我们的客户协议，请您确认相关条款并勾选后，点击确认订单按钮。');

define('TEXT_GUEST_INFO_FLIGHT_INFO','游客信息和航班信息');
define('TEXT_FLIGHT_INFO_IF_AVAILABLE','（如果您已经预订了机票，请提供您的航班信息，您也可以随后登陆在进行更新）');
//define('TEXT_INFO_GUEST_NAME','顾客中文名');
define('TEXT_INFO_GUEST_NAME','顾客%s');
define('TEXT_FLIGHT_INFO_IF_APPLICABLE','如果您有预订航班，请填写航班信息');
define('TEXT_ARRIVAL_AIRLINE_NAME','航空公司');
define('TEXT_DEPARTURE_AIRLINE_NAME','航空公司');
define('TEXT_ARRIVAL_FLIGHT_NUMBER','接机航班');
define('TEXT_DEPARTURE_FLIGHT_NUMBER','送机航班');
define('TEXT_ARRIVAL_AIRPORT_NAME','到达机场名字');
define('TEXT_DEPARTURE_AIRPORT_NAME','出发机场名字');
define('TEXT_ARRIVAL_DATE','接机日期');
define('TEXT_DEPARTURE_DATE','送机日期');
define('TEXT_ARRIVAL_TIME','到达时间');
define('TEXT_DEPARTURE_TIME','起飞时间');
define('TEXT_EMERGENCY_CONTACT_NUM','联系电话:');
define('TEXT_EMERGENCY_CASE_AVALILABLE','（如果有请提供以备紧急时候使用）');
define('TEXT_CELL_NUMBER','移动电话数字:');

define('TEXT_SHIPPING_METHOD','寄送方式∶');
define('TEXT_SIMPLE_DIS_EMAIL','电子邮件');
define('TEXT_DIS_EMAIL','电子邮件∶');
define('TEXT_DIS_CUSTIMER','顾客∶');
define('TEXT_DIGITAL_PRODUCTS','');
define('TEXT_INFO_GUEST_BODY_WEIGHT','顾客的体重');
define('TEXT_CHILD_BIRTH_ERROR_MSG','您所填写的小孩出生日期 %s 为无效的日期，小孩年龄应在 %s 岁以下。请重新填写参团者的信息。'); //Invalid child age for child birth date 05/07/1990. Child age should be under 12 years at time of travel. Please edit tour member information.
define('TEXT_CHILD_BIRTH_ERROR_MIN_MSG','您所填写的儿童出生日期 %s 为无效的日期，儿童年龄应在 %s 岁以下。请重新填写参团者的信息。'); 
//howard added
define('HEADING_TOURS_INFORMATION','订单信息');
define('TEXT_SUBMIT_MSN','正在提交订单，这个过程需要花费您的一些时间，请耐心等候...');


define('TEXT_GUEST_NAME','顾客%s姓名:');

define('TXT_DIS_ENTRY_GUEST_ADULT','');
define('TXT_ROOM_INFORMATION','房间信息:');
define('TXT_DIS_ENTRY_GUEST_CHILD','(儿童)');
define('ADULT_AVERAGE_PAY','每个成人需付');
define('CHILD_AVERAGE_PAY','每个小孩需付');

//howard added end
?>
