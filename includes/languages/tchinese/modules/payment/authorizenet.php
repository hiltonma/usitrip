<?php
/*
  $Id: authorizenet.php,v 1.3 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 */

//  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Credit Card : U.S. Credit Card (Preferred)<br> <div style="font-weight:normal;">Use this option if you use a U.S. issued credit card.</div>');
  //define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', '信用卡 : U.S. 信用卡 (推薦)');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize信用卡');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE_ADDON', '<div style="font-weight:normal;">如果您使用的是 U.S. 信用卡 ，那麼請選取這個選項.</div>');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER', '持卡人:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', '信用卡卡號:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', '過期日期:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_TYPE', '信用卡類型:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER', '* 持卡人姓名至少應該包含 ' . CC_OWNER_MIN_LENGTH . ' 字符.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_LINK', '這是什麼?');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_NUMBER', 'CVV 號:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* 信用卡卡號至少應該含有 ' . CC_NUMBER_MIN_LENGTH . ' 字符.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', '在使用您的信用卡過程中發生錯誤，請重新嘗試.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DECLINED_MESSAGE', '您的信用卡被拒絕，請重新試其他的卡或聯係您的銀行尋求幫助.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', '信用卡錯誤!');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_CVV', '* 您必須輸入CVV號以繼續.\n');
  define('TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH', '您輸入的卡類型和卡號不相符合. 請檢查後重新試一次.');
  define('TEXT_CCVAL_ERROR_CVV_LENGTH', 'CVV 號輸入有誤，請重新輸入.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_AMEX', 'Amex');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DISCOVER', 'Discover');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_MASTERCARD', 'Mastercard');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_VISA', 'Visa');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_WARM_TIPS','信用卡的溫馨提示');
?>
