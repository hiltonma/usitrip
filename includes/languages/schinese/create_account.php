<?php
/*
  $Id: banktransfer.php,v 1.3 2002/05/31 19:02:02 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  //define('HEADING_TITLE', '完全免费注册');
  define('HEADING_TITLE_CREATE_ACCOUNT', '完全免费注册');
  define('TEXT_ORIGIN_LOGIN', '快乐旅行&nbsp;&nbsp;由此开始');
   
   
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_TITLE', '银行电汇');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_DESCRIPTION', 'Please see below details for information needed for sending wire transfer.<br>'.
		  '<br>Bank Name: '.MODULE_PAYMENT_BANKTRANSFER_BANKNAM.
		  '<br>Account Name: ' . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  '<br>Account #: ' . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  '<br>Routing #: ' . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  '<br>SWIFT #: ' . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . ' (For International Wire Transfer)'.
		  '<br><br>Company Address:<br>' . nl2br(db_to_html (STORE_NAME_ADDRESS)) .
		  '<br><br>Note: Please reference your reservation number on bank form when you send wire transfer. Your reservation will NOT be confirmed until we receive payment.<br>'.

		  '<br>请使用以下详情到银行电汇您的订单金额∶<br>'.
		  '<br>银行名称∶ ' . MODULE_PAYMENT_BANKTRANSFER_BANKNAM . 
		  '<br>帐户名∶ ' . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  '<br>账号∶ ' . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  '<br>ABA银行号码∶ ' . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  '<br>SWIFT 代码∶ ' . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . ' (仅供国际电汇使用)' .
		  "<br><br>公司地址：<br>" . nl2br(db_to_html (STORE_NAME_ADDRESS)) .
		  '<br><br>注意∶请在发送电汇的时候在银行表格上注明您的订单号。请您留意在我们未收到您的支付款之前我们将不会确认您的订单。非常感谢您的配合。');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_EMAIL_FOOTER', "Please see below details for information needed for sending wire transfer.\n".
		  "\nBank Name: ".MODULE_PAYMENT_BANKTRANSFER_BANKNAM.
		  "\nAccount Name: " . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  "\nAccount #: " . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  "\nRouting #: " . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  "\nSWIFT #: " . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . " (For International Wire Transfer) \n ".
		  "\n\nCompany Address:\n" . nl2br(db_to_html (STORE_NAME_ADDRESS)) .
		  "\nNote: Please reference your reservation number on bank form when you send wire transfer. Your reservation will NOT be confirmed until we receive payment.\n".
		
		  "\n请使用以下详情到银行电汇您的订单金额∶".
		  "\n银行名称∶ " . MODULE_PAYMENT_BANKTRANSFER_BANKNAM . 
		  "\n帐户名∶ " . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  "\n账号∶ " . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  "\nABA银行号码∶ " . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  "\nSWIFT 代码∶ " . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . ' (仅供国际电汇使用)'.
		  "\n\n公司地址：\n" . nl2br(db_to_html (STORE_NAME_ADDRESS)) .
		  "\n\n注意∶请在发送电汇的时候在银行表格上注明您的订单号。请您留意在我们未收到您的支付款之前我们将不会确认您的订单。非常感谢您的配合。");


// Points/Rewards system V2.1rc2a BOF
define('EMAIL_WELCOME_POINTS', '<li><strong>奖励点计划</strong> - 作为我们欢迎新的客户，我们已记入您的 %s 总共有 %s 值得购买点 %s .' . "\n" . '请参阅 %s 如条件可能适用。');
define('EMAIL_POINTS_ACCOUNT', '购买点Accout');
define('EMAIL_POINTS_FAQ', '奖励计划的常见问题点');
// Points/Rewards system V2.1rc2a EOF
/*
// English Points/Rewards system V2.1rc2a BOF
define('EMAIL_WELCOME_POINTS', '<li><strong>Reward Point Program</strong> - As part of our Welcome to new customers, we have credited your %s with a total of %s Purchase Points worth %s .' . "\n" . 'Please refer to the %s as conditions may apply.');
define('EMAIL_POINTS_ACCOUNT', 'Purchase Points Accout');
define('EMAIL_POINTS_FAQ', 'Reward Point Program FAQ');
// Points/Rewards system V2.1rc2a EOF
*/
require_once('create_account_process.php'); 
?>
