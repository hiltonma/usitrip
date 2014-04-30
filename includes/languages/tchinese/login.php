<?php
/*
  $Id: login.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

if ($HTTP_GET_VARS['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', '訂單');
  define('HEADING_TITLE', '方便快捷網上下訂單.');
  define('TEXT_STEP_BY_STEP', '我們將按照步驟幫助您逐步完成此過程.');
} else {
  define('NAVBAR_TITLE', '登錄');
  define('HEADING_TITLE', '歡迎光臨，會員請由右邊填入電子郵件及密碼後登錄，新客戶請按繼續鈕註冊');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_NEW_CUSTOMER', '註冊賬號');
define('TEXT_NEW_CUSTOMER', '我是新客戶.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', '走四方網會員可以得到稱心如意的旅遊行程服務，定期收到旅遊資訊和特價資訊。專為會員提供的積分獎勵計畫讓您可以通過訂購產品，發表評論，上傳照片，回答問題等方式贏取現金折扣！');

define('HEADING_RETURNING_CUSTOMER', '舊客戶');
define('TEXT_RETURNING_CUSTOMER', '已有賬號');
define('ENTRY_EMAIL_ADDRESS', '電子郵箱:');
define('ENTRY_PASSWORD', '密碼:');

define('TEXT_PASSWORD_FORGOTTEN', '忘記密碼？');
define('SUCCESS_PASSWORD_SENT', '密碼已經成功發送到您的電子郵箱，請稍侯查收！');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>錯誤:</b></font>\'電子郵件地址\' 或 \'密碼\'不符，請重新輸入');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>提示:</b></font> 若您要結帳請先以會員賬號登錄，您於&quot;訪客購物車&quot;裏的商品，會在登錄後自動併入&quot;會員購物車&quot;內 <a href="javascript:session_win();">[更多說明]</a>');

// +Login Page a la Amazon
define('TEXT_EMAIL_QUERY', '什麼是您的電子郵件?');
define('TEXT_EMAIL_IS', '我的電子郵件是:');
define('TEXT_HAVE_PASSWORD', '您有一個 '. STORE_NAME . ' 密碼嗎?');
define('TEXT_HAVE_PASSWORD_NO', '不, 我是一名新顧客。');
define('TEXT_HAVE_PASSWORD_YES', '是, 我的密碼是:');
define('HEADING_CHECKOUT', '直接結賬');
define('TEXT_CHECKOUT_INTRODUCTION', '您可以選擇直接結賬.我們將不會儲存任何關於您的個人資料.選擇直接結賬的話,您過後將不能再察看您的訂單狀態或任何訂單記錄.');
// -Login Page a la Amazon

// Points/Rewards Module V2.1rc2a
define('TEXT_CONTINUE_WITHOUT_LOGIN_MESSAGE','您將不會獲得任何積分。您可以進行下列表格。');
define('TEXT_ALREADY_LOGIN_MESSAGE','您已登錄');
define('TEXT_SUCCESS_LOGIN_MESSAGE','登錄成功。 ');

/* english
// Points/Rewards Module V2.1rc2a
define('TEXT_NOT_LOGIN_MESSAGE','You will not earn any points. You can proceed with the below Form.');
define('TEXT_ALREADY_LOGIN_MESSAGE','You have already logged in');
define('TEXT_SUCCESS_LOGIN_MESSAGE','You have logged in successfully. You will now earn points for ');
*/

?>