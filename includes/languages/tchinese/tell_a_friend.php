<?php
/*
  $Id: tell_a_friend.php,v 1.7 2003/06/10 18:20:39 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', '推薦給朋友');

define('HEADING_TITLE', '推薦給朋友關於 \'%s\'');

define('FORM_TITLE_CUSTOMER_DETAILS', '您的詳細信息');
define('FORM_TITLE_FRIEND_DETAILS', '您朋友的詳細信息');
define('FORM_TITLE_FRIEND_MESSAGE', '消息');

define('FORM_FIELD_CUSTOMER_NAME', '您的名字:');
define('FORM_FIELD_CUSTOMER_EMAIL', '您的E-mail地址:');
define('FORM_FIELD_FRIEND_NAME', '您朋友的名字:');
define('FORM_FIELD_FRIEND_EMAIL', '您朋友的郵箱地址:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', '您關於 <b>%s</b> 的郵件已經成功的送到了 <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', '您的朋友 %s 推薦給您一個好景點，來自 %s');
define('TEXT_EMAIL_INTRO', '您好 %s!' . "\n\n" . '您的朋友, %s, 您會感興趣於 %s 來自 %s.');
define('TEXT_EMAIL_LINK', '點擊下面鏈接或者直接拷貝下面鏈接地址到瀏覽器地址欄查看內容:' . "\n\n" . '%s');
define('TEXT_EMAIL_LINK_ARTICLE', '點擊下面鏈接或者直接拷貝下面鏈接地址到瀏覽器地址欄查看內容:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', '祝福,' . "\n\n" . '%s');

define('ERROR_TO_NAME', '錯誤: 您朋友的名字不能為空.');
define('ERROR_TO_ADDRESS', '錯誤: 您朋友的郵箱地址必須為有效地址.');
define('ERROR_FROM_NAME', '錯誤: 您的名字必須填寫.');
define('ERROR_FROM_ADDRESS', '錯誤: 您的E-mail地址必須為有效郵箱地址.');
?>
