<?php
/*
  $Id: links_submit.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', '鏈結');
define('NAVBAR_TITLE_2', '提交鏈結');

define('HEADING_TITLE', '聯繫信息');

define('TEXT_MAIN', '請填寫以下表格來提交您的網站。');

define('EMAIL_SUBJECT', '歡迎來到 ' . STORE_NAME . ' 鏈接交換。');
define('EMAIL_GREET_NONE', '親愛的 %s' . "\n\n");
define('EMAIL_WELCOME', '我們歡迎您們到 <b>' . STORE_NAME . '</b> 鏈接交換計劃。' . "\n\n");
define('EMAIL_TEXT', '您的鏈接已成功提交到 ' . STORE_NAME . '，通過審核後您的鏈接將馬上添加到我們的列表上。您將收到一封通知提交狀態的郵件。如果在接下來的48小時內您未能收到這封郵件，請在重新提交鏈接前與我們聯繫。' . "\n\n");
define('EMAIL_CONTACT', '為幫助與我們聯繫交流計劃，請您發送電子郵件到： ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> 這個電子郵件地址是給我們在一個環節遞交。如果有任何問題，請發送電子郵件至 ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_OWNER_SUBJECT', '連接遞交在' . STORE_NAME);
define('EMAIL_OWNER_TEXT', '一個新的鏈接提交給' . STORE_NAME . '。目前尚未獲得批准。請確認這個連接，並啟動。' . "\n\n");

define('TEXT_LINKS_HELP_LINK', '&nbsp;Help&nbsp;[?]');

define('HEADING_LINKS_HELP', '連結幫助');
define('TEXT_LINKS_HELP', '<b>網站名稱：</b> 為您的網站填寫一段描述。<br><br><b>網站地址URL:</b> 輸入您的網站地址，其中包括 \'http://\'.<br><br><b>類別：</b> 給您的網站設置一個適當的類別。<br><br><b>描述：</b> 簡要描述您的網站。<br><br><b>Image URL:</b> 填寫您的網站LOGO圖標地址，其中包括 \'http://\'. 這個圖標將隨著您的網站鏈接顯示。<br>例如： http://your-domain.com/path/to/your/image.gif <br><br><b>全名：</b> 您的全名。<br><br><b>Email:</b> 您的電子郵件地址。請輸入一個有效的電子郵件，您將會收到電子郵件。<br><br><b>互惠頁：</b> 您的連結網頁絕對的URL地址，凡鏈接到我們的網站，將被列為/展示。<br>例如： http://your-domain.com/path/to/your/links_page.php');
define('TEXT_CLOSE_WINDOW', '<u>關閉窗口</u> [x]');

// VJ todo - move to common language file
define('CATEGORY_WEBSITE', '網站詳情');
define('CATEGORY_RECIPROCAL', '互惠頁詳情');

define('ENTRY_LINKS_TITLE', '網站名稱：');
define('ENTRY_LINKS_TITLE_ERROR', '鏈接標題必須包含至少 ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' 個字符。');
define('ENTRY_LINKS_TITLE_TEXT', '*');
define('ENTRY_LINKS_URL', 'URL:');
define('ENTRY_LINKS_URL_ERROR', 'URL 必須包含至少 ' . ENTRY_LINKS_URL_MIN_LENGTH . '  個字符。');
define('ENTRY_LINKS_URL_TEXT', '*');
define('ENTRY_LINKS_CATEGORY', '類別：');
define('ENTRY_LINKS_CATEGORY_TEXT', '*');
define('ENTRY_LINKS_DESCRIPTION', '描述：');
define('ENTRY_LINKS_DESCRIPTION_ERROR', '描述必須包含至少 ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' 個字符。');
define('ENTRY_LINKS_DESCRIPTION_TEXT', '*');
define('ENTRY_LINKS_IMAGE', 'Image URL:');
define('ENTRY_LINKS_IMAGE_TEXT', '');
define('ENTRY_LINKS_CONTACT_NAME', '全名：');
define('ENTRY_LINKS_CONTACT_NAME_ERROR', '您的全名必須包含至少 ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' 個字符。');
define('ENTRY_LINKS_CONTACT_NAME_TEXT', '*');
define('ENTRY_LINKS_RECIPROCAL_URL', '互惠頁：');
define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', '互惠頁必須包含至少 ' . ENTRY_LINKS_URL_MIN_LENGTH . ' 個字符。');
define('ENTRY_LINKS_RECIPROCAL_URL_TEXT', '*');
?>
