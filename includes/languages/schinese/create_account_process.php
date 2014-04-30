<?php
/*
  $Id: create_account_process.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

define('NAVBAR_TITLE_1', '新增一个账号');
define('NAVBAR_TITLE_2', '处理');
define('HEADING_TITLE', '我的账号');

define('EMAIL_SUBJECT', '欢迎光临 ' . STORE_NAME);
/*define('EMAIL_GREET_MR', '亲爱的 ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "先生\n\n");
define('EMAIL_GREET_MS', '亲爱的 ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "小姐\n\n");*/
define('EMAIL_GREET_MR', '亲爱的 ' . stripslashes($HTTP_POST_VARS['lastname']) . '先生您好,' . "\n\n");
define('EMAIL_GREET_MS', '亲爱的 ' . stripslashes($HTTP_POST_VARS['lastname']) . '小姐您好,' . "\n\n");
define('EMAIL_GREET_NONE', '亲爱的 ' . stripslashes($HTTP_POST_VARS['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', '我们非常诚挚的欢迎您光临 <b>' . STORE_NAME . '</b>' . "\n");
/*define('EMAIL_TEXT', '现在您可以来看看我们所为您提供的<b>线上服务</b>. 这些服务包含:' . "\n\n" . '<li><b>购物车</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");*/
define('EMAIL_TEXT', '下列为我们提供<b>线上服务</b>∶' . "\n\n" . '<li><b>1.智慧型购物车</b>∶'."\n".'          放到购物车的商品，除非您将它们移除或结帐，否则商品将会一直留在购物车内' . "\n\n" . '<li><b>2.个人通讯录</b>∶'."\n".'          我们提供您将购买的商品，直接寄送给通讯录里的亲友! 例如∶当您有亲友生日时，我们可以替您将购买的生日礼物直接送到寿星手上' . "\n\n" . '<li><b>3.订单购物记录</b>∶'."\n".'          您可以随时登录，查询已购买商品的最新状态及纪录' . "\n\n" . '<li><b>4.商品评论</b>∶'."\n".'          分享您的购物经验或评论您有兴趣的商品' . "\n\n".'<li><b>5.商品通知</b>∶'."\n".'          商品通知可以让您随时掌握我们的商品动态,让您不错失特价商品及其他优惠的良机' . "\n\n");
define('EMAIL_CONTACT', '任何有关线上服务的建言或疑问，请写信告诉我们mailto:' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>附注:</b>这个电子邮件地址(E-mail address)是由我们的客户提供，如果您并不愿意加入会员，请来信∶ mailto:' . STORE_OWNER_EMAIL_ADDRESS . '，我们会立刻将您的资料删除。' . "\n");
define('EMAIL_ACCOUNT_FOOTER', '<br><br>我们真诚的期望您与usitrip一起旅行愉快!' . "<br><br>".'真诚地,'. "<br><br>". '新会员客服'."<br>".'<a href="'.HTTP_SERVER.'" target="_blank">www.usitrip.com</a>'. "<br> <br>");

	
define('CONFI_NEWSLLETTER_SUBJECT','欢迎订阅走四方网（usitrip）旅游资讯。');
?>
