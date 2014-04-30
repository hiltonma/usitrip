<?php
/*
  $Id: authorizenet.php,v 1.3 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 */

//  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Credit Card : U.S. Credit Card (Preferred)<br> <div style="font-weight:normal;">Use this option if you use a U.S. issued credit card.</div>');
  //define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', '信用卡 : U.S. 信用卡 (推荐)');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize信用卡');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE_ADDON', '<div style="font-weight:normal;">如果您使用的是 U.S. 信用卡 ，那么请选取这个选项.</div>');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER', '持卡人:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', '信用卡卡号:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', '过期日期:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_TYPE', '信用卡类型:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER', '* 持卡人姓名至少应该包含 ' . CC_OWNER_MIN_LENGTH . ' 字符.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_LINK', '这是什么?');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_NUMBER', 'CVV 号:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* 信用卡卡号至少应该含有 ' . CC_NUMBER_MIN_LENGTH . ' 字符.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', '在使用您的信用卡过程中发生错误，请重新尝试.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DECLINED_MESSAGE', '您的信用卡被拒绝，请重新试其他的卡或联系您的银行寻求帮助.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', '信用卡错误!');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_CVV', '* 您必须输入CVV号以继续.\n');
  define('TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH', '您输入的卡类型和卡号不相符合. 请检查后重新试一次.');
  define('TEXT_CCVAL_ERROR_CVV_LENGTH', 'CVV 号输入有误，请重新输入.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_AMEX', 'Amex');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DISCOVER', 'Discover');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_MASTERCARD', 'Mastercard');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_VISA', 'Visa');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_WARM_TIPS','信用卡的温馨提示');
?>
