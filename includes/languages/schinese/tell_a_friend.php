<?php
/*
  $Id: tell_a_friend.php,v 1.7 2003/06/10 18:20:39 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', '推荐给朋友');

define('HEADING_TITLE', '推荐给朋友关于 \'%s\'');

define('FORM_TITLE_CUSTOMER_DETAILS', '您的详细信息');
define('FORM_TITLE_FRIEND_DETAILS', '您朋友的详细信息');
define('FORM_TITLE_FRIEND_MESSAGE', '消息');

define('FORM_FIELD_CUSTOMER_NAME', '您的名字:');
define('FORM_FIELD_CUSTOMER_EMAIL', '您的E-mail地址:');
define('FORM_FIELD_FRIEND_NAME', '您朋友的名字:');
define('FORM_FIELD_FRIEND_EMAIL', '您朋友的邮箱地址:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', '您关于 <b>%s</b> 的邮件已经成功的送到了 <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', '您的朋友 %s 推荐给您一个好景点，来自 %s');
define('TEXT_EMAIL_INTRO', '您好 %s!' . "\n\n" . '您的朋友, %s, 您会感兴趣于 %s 来自 %s.');
define('TEXT_EMAIL_LINK', '点击下面链接或者直接拷贝下面链接地址到浏览器地址栏查看内容:' . "\n\n" . '%s');
define('TEXT_EMAIL_LINK_ARTICLE', '点击下面链接或者直接拷贝下面链接地址到浏览器地址栏查看内容:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', '祝福,' . "\n\n" . '%s');

define('ERROR_TO_NAME', '错误: 您朋友的名字不能为空.');
define('ERROR_TO_ADDRESS', '错误: 您朋友的邮箱地址必须为有效地址.');
define('ERROR_FROM_NAME', '错误: 您的名字必须填写.');
define('ERROR_FROM_ADDRESS', '错误: 您的E-mail地址必须为有效邮箱地址.');
?>
