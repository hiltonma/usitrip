<?php
/*
  $Id: create_account.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/
  //define('HEADING_TITLE', '完全免費註冊');
  define('HEADING_TITLE_CREATE_ACCOUNT', '完全免費註冊');
  define('TEXT_ORIGIN_LOGIN', '快樂旅行&nbsp;&nbsp;由此開始');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_TITLE', '銀行電匯');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_DESCRIPTION', 'Please see below details for information needed for sending wire transfer.<br>'.
		  '<br>Bank Name: '.MODULE_PAYMENT_BANKTRANSFER_BANKNAM.
		  '<br>Account Name: ' . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  '<br>Account #: ' . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  '<br>Routing #: ' . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  '<br>SWIFT #: ' . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . ' (For International Wire Transfer)'.
		  '<br><br>Company Address:<br>' . nl2br(db_to_html(strip_tags(STORE_NAME_ADDRESS))) .
		  '<br><br>Note: Please reference your reservation number on bank form when you send wire transfer. Your reservation will NOT be confirmed until we receive payment.<br>'.

		  '<br>請使用以下詳情到銀行電匯您的訂單金額：<br>'.
		  '<br>銀行名稱： ' . MODULE_PAYMENT_BANKTRANSFER_BANKNAM . 
		  '<br>帳戶名： ' . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  '<br>賬號： ' . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  '<br>ABA銀行號碼： ' . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  '<br>SWIFT 代碼： ' . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . ' (僅供國際電匯使用)' .
		  "<br><br>公司地址﹕<br>" . nl2br(db_to_html(strip_tags(STORE_NAME_ADDRESS))) .
		  '<br><br>注意：請在發送電匯的時候在銀行表格上注明您的訂單號。請您留意在我們未收到您的支付款之前我們將不會確認您的訂單。非常感謝您的配合。');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_EMAIL_FOOTER', "Please see below details for information needed for sending wire transfer.\n".
		  "\nBank Name: ".MODULE_PAYMENT_BANKTRANSFER_BANKNAM.
		  "\nAccount Name: " . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  "\nAccount #: " . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  "\nRouting #: " . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  "\nSWIFT #: " . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . " (For International Wire Transfer) \n ".
		  "\n\nCompany Address:\n" . nl2br(db_to_html(strip_tags(STORE_NAME_ADDRESS))) .
		  "\nNote: Please reference your reservation number on bank form when you send wire transfer. Your reservation will NOT be confirmed until we receive payment.\n".
		
		  "\n請使用以下詳情到銀行電匯您的訂單金額：".
		  "\n銀行名稱： " . MODULE_PAYMENT_BANKTRANSFER_BANKNAM . 
		  "\n帳戶名： " . MODULE_PAYMENT_BANKTRANSFER_ACCNAM . 
		  "\n賬號： " . MODULE_PAYMENT_BANKTRANSFER_ACCNUM . 
		  "\nABA銀行號碼： " . MODULE_PAYMENT_BANKTRANSFER_ROUNUM . 
		  "\nSWIFT 代碼： " . MODULE_PAYMENT_BANKTRANSFER_SORTCODE . ' (僅供國際電匯使用)'.
		  "\n\n公司地址﹕\n" . nl2br(db_to_html(strip_tags(STORE_NAME_ADDRESS))) .
		  "\n\n注意：請在發送電匯的時候在銀行表格上注明您的訂單號。請您留意在我們未收到您的支付款之前我們將不會確認您的訂單。非常感謝您的配合。");

define('EMAIL_SUBJECT', '歡迎註冊 usitrip');
define('EMAIL_GREET_MR', '親愛的 %s 先生,' . "<br><br>");
define('EMAIL_GREET_MS', '親愛的 %s 小姐,' . "<br><br>");
define('EMAIL_GREET_NONE', '親愛的 %s ' . "<br><br>");
define('EMAIL_WELCOME', '歡迎您來到 <b> usitrip.com</b>.<br><br> usitrip是一家新銳的網上旅行社，提供專業優秀的旅遊供應商的信息服務。致力於建立一個為喜歡旅遊的您提供最好的線上旅遊的優秀網站，usitrip正努力使自己成為最權威的線上旅遊信息供應網，給您提供最多的選擇和最優惠的價格。目前我們的主要產品包括夏威夷和佛羅裏達州的各式打包旅游，以及美國東海岸和西海岸的活力城市游。並將繼續竭盡所能的為您提供更多您心儀的旅遊線路。我們保證您會滿意我們一流的服務，並真誠歡迎您瀏覽我們的網頁，您一定能在我們提供的豐富資源中找到適合您的旅遊信息，並被他們優良的服務和難以置信的價格所折服。<br><br>');
define('EMAIL_TEXT', '現在，您只需輕點鼠標，就可以享受我們為您提供的各類服務了！其中包括：' . "<br><br>" . '<li><b>專屬購物車</b> - 任何您添加到線上購物車內的產品將一直為您保留，除非您清除或者購買了其中產品。' . "<br>" . '<li><b>航班情報更新系統 </b> 一旦您定購了機票，我們的航班情報更新系統向及時向您更新通報航班飛行相關信息。衹要您的預訂中包含機場運送，您就可以在您的帳戶中享受該服務。' . "<br>" . '<li><b>預訂狀況和歷史訂單 </b> - 您可以查看您的預訂狀態和您在我們網站上產生的所有歷史訂單。' . "<br>" . '<li><b>E-Ticket的獲取</b> - 衹要您擁有一台電腦和網絡設備，您就可以隨時隨地獲取我們發佈的E-Ticket。' . "<br><br>");
define('EMAIL_CONFIRMATION', 'Thank you for submitting your account information to our ' . STORE_NAME . "<br><br>" . 'To finish your account setup please verify your e-mail address by clicking the link below: ' . "<br><br>");
define('EMAIL_CONTACT', '想獲取以上服務，請登陸: '.HTTP_SERVER.'/account.php' ."<br><br>". '想獲取我們在線服務的相關幫助，請向我們的客服中心發送郵件：'.STORE_OWNER_EMAIL_ADDRESS.'.' . "<br><br>");
define('EMAIL_WARNING', '注意：此郵箱地址來源於我們的會員推薦，如果您沒有註冊成為我們的會員，請發送郵件至'.STORE_OWNER_EMAIL_ADDRESS.'進行退訂。' . "<br>");

define('EMAIL_ACCOUNT_FOOTER', '<br><br>歡迎您再次訪問我們的網站！' . "<br><br>".'此致,<br>敬禮'. "<br>".'新會員服務中心'."<br>".'<a href="http://www.usitrip.com" target="_blank">www.usitrip.com</a>'. "<br><br>");

// Points/Rewards system V2.1rc2a BOF
define('EMAIL_WELCOME_POINTS', '<li><strong>獎勵點計劃</strong> - 這是我們歡迎的新客戶，我們已記入您的 %s 總共有 %s 值得購買 %s .' . "\n" . '請參閱 %s 作為條件，可申請。');
define('EMAIL_POINTS_ACCOUNT', '購買點Accout');
define('EMAIL_POINTS_FAQ', '獎勵點計劃的常見問題');
// Points/Rewards system V2.1rc2a EOF
/* english

// Points/Rewards system V2.1rc2a BOF
define('EMAIL_WELCOME_POINTS', '<li><strong>Reward Point Program</strong> - As part of our Welcome to new customers, we have credited your %s with a total of %s Purchase Points worth %s .' . "\n" . 'Please refer to the %s as conditions may apply.');
define('EMAIL_POINTS_ACCOUNT', 'Purchase Points Accout');
define('EMAIL_POINTS_FAQ', 'Reward Point Program FAQ');
// Points/Rewards system V2.1rc2a EOF
*/

require_once('create_account_process.php'); 
?>
