<?php
/*
  $Id: tchinese.php,v 1.1.1.1 2003/08/07 07:54:24 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// 設定本地時間
// RedHat 'zh_TW'
// FreeBSD 'zh_TW.Big5'
// Windows ''引號內空白即可
//
@setlocale(LC_TIME, '');
define('DATE_FORMAT_SHORT', '%m/%d%/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%Y年%m月%d日'); // this is used for strftime()
//define('DATE_FORMAT_LONG', '%Y年%m月%d日 %A'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('DATE_FORMAT_LONG_REVIEW', '%a %b %d, %Y at %I.%M %p ');
////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'CNY');//預設值

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="zh-tw"');

// charset for web pages and emails
define('CHARSET', 'big5');
//define('CHARSET', 'gb2312');

// page title
define('TITLE', STORE_NAME);
// domain name on email subject
define('STORE_OWNER_DOMAIN_NAME','usitrip.com');
define('ORDER_EMAIL_PRIFIX_NAME','');
// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', '註冊賬號');
define('HEADER_TITLE_MY_ACCOUNT', '我的賬號');
define('HEADER_TITLE_CART_CONTENTS', '購物車');
define('HEADER_TITLE_CHECKOUT', '結帳');
define('HEADER_TITLE_CONTACT_US', '聯絡我們');
define('HEADER_TITLE_TOP', '首&nbsp;頁');
define('HEADER_TITLE_CATALOG', '商品目錄');
define('HEADER_TITLE_LOGOFF', '退出');
//define('HEADER_TITLE_LOGOFF', '退出賬號');
define('HEADER_TITLE_LOGIN', '登錄');
define('HEADER_TITLE_ADMINISTRATION', '系統管理');

// box text in includes/boxes/administrators.php
define('BOX_HEADING_ADMINISTRATORS', '系統管理員');
define('BOX_ADMINISTRATORS_SETUP', '設定');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', '次瀏覽,自從');

// text for gender
define('MALE', '男');
define('FEMALE', '女');
define('MALE_ADDRESS', '先生');
define('FEMALE_ADDRESS', '小姐');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', '景點目錄');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', '製造廠商');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', '新上架商品');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', '快速尋找商品');
define('BOX_SEARCH_TEXT', '輸入關鍵字尋找商品');
define('BOX_SEARCH_ADVANCED_SEARCH', '進階尋找商品');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', '特價商品');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', '商品評論');
define('BOX_REVIEWS_WRITE_REVIEW', '請寫下您對這個商品的評論!');
define('BOX_REVIEWS_NO_REVIEWS', '目前沒有任何商品評論');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '等級 %s 星級');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', '購物車');
define('BOX_SHOPPING_CART_EMPTY', '購物車為空');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', '購物紀錄');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', '暢銷商品');
define('BOX_HEADING_BESTSELLERS_IN', '暢銷商品在<br />  ');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', '商品狀態通知');
define('BOX_NOTIFICATIONS_NOTIFY', '<b>%s</b><br />更新時通知我');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', '<b>%s</b><br />更新時不必通知我');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', '廠商的相關信息');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', ' %s 的主頁');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', '廠商的其他商品');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', '語言');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', '貨幣');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', '服務檯');


define('BOX_INFORMATION_SHIPPING', '退換貨事項');


// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', '推薦給親友');
define('BOX_TELL_A_FRIEND_TEXT', '推薦這個商品給親友');

// checkout procedure text
//define('CHECKOUT_BAR_CART_CONTENTS', '購物車內容');
//define('CHECKOUT_BAR_DELIVERY_ADDRESS', '出貨地址');
//define('CHECKOUT_BAR_PAYMENT_METHOD', '付款方式');
define('CHECKOUT_BAR_DELIVERY', '出貨信息');
define('CHECKOUT_BAR_PAYMENT', '支付信息');
define('CHECKOUT_BAR_CONFIRMATION', '確認訂單');
define('CHECKOUT_BAR_FINISHED', '完成');

// pull down default text
define('PULL_DOWN_DEFAULT', '請選擇');
define('TYPE_BELOW', '在下面輸入');

// javascript messages
define('JS_ERROR', '在提交表格過程中出現錯誤.\n\n請做下述改正:\n\n');

define('JS_REVIEW_TEXT', '* \'評論內容\' 必須至少包含 ' . REVIEW_TEXT_MIN_LENGTH . ' 個字符.\n');
define('JS_REVIEW_RATING', '* 您必須為您做了評論的團評等級.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* 請為您的訂單選擇一個支付方式.\n');

define('JS_ERROR_SUBMITTED', '這個表單已經送出，請按 Ok 後等待處理');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', '您必須選一個付款方式.');

define('CATEGORY_COMPANY', '公司資料');
define('CATEGORY_PERSONAL', '個人資料');
define('CATEGORY_ADDRESS', '地址');
define('CATEGORY_CONTACT', '您的聯係信息');
define('CATEGORY_OPTIONS', '選項');
define('CATEGORY_PASSWORD', '密碼');

define('ENTRY_COMPANY', '公司名稱:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', '性別:');
define('ENTRY_GENDER_ERROR', '請選擇性別');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', '中文姓名:');
define('ENTRY_FIRST_NAME_ERROR', '請確認姓名與您的有效證件上的姓名一致，且不少於 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 個字');
define('ENTRY_FIRST_NAME_ERROR_ONLYCHINA', ENTRY_FIRST_NAME.'只能輸入中文');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', '護照英文名:');
define('ENTRY_LAST_NAME_ERROR', ENTRY_LAST_NAME.'少於 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 個字');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', '生日:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '(例：05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', ' (例：05/21/1970)必填欄位');
define('ENTRY_EMAIL_ADDRESS', '電子郵箱:');
define('ENTRY_CONFIRM_EMAIL_ADDRESS', '郵箱確認:');
define('ENTRY_CONFIRM_EMAIL_ADDRESS_CHECK_ERROR', '郵箱確認必須和電子郵箱匹配');
define('ENTRY_EMAIL_ADDRESS_ERROR', ENTRY_EMAIL_ADDRESS.'少於 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 個字');
define('ENTRY_EMAIL_ADDRESS_NOTE_DEFAULT', '請輸入您常用的電子郵箱地址');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '電子郵箱地址格式錯誤');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '這個電子郵箱已經註冊過!請確認或換一個電子郵箱');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', '詳細地址:');
define('ENTRY_STREET_ADDRESS_ERROR', ENTRY_STREET_ADDRESS.'少於 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 個字');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', '街道:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', '郵政編碼:');
define('ENTRY_POST_CODE_ERROR', ' 郵政編碼 少於 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 個字');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', '城市:');
define('ENTRY_CITY_ERROR', ENTRY_CITY.' 少於 ' . ENTRY_CITY_MIN_LENGTH . ' 個字');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', '州/省:');
define('ENTRY_STATE_ERROR', '州/省最少必須 ' . ENTRY_STATE_MIN_LENGTH . '個字');
define('ENTRY_STATE_ERROR_SELECT', '請從下拉式選單中選取');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', '國家/地區:');
define('ENTRY_COUNTRY_ERROR', '請從下拉式選單中選取');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', '聯繫電話:');
define('ENTRY_TELEPHONE_NUMBER_ON_CREATE_ACCOUNT', '電話號碼:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '電話號碼不得少於 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 個字');
define('ENTRY_TELEPHONE_NUMBER_ERROR_1', ENTRY_TELEPHONE_NUMBER_ON_CREATE_ACCOUNT.'必須全部都是數字');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '');
define('ENTRY_FAX_NUMBER', '其他電話:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_MOBILE_PHONE','移動電話:');
define('ENTRY_MOBILE_PHONE_ERROR','');
define('ENTRY_MOBILE_PHONE_TEXT','*');

define('ENTRY_NEWSLETTER', '訂閱走四方資訊郵件:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', '-訂閱-');
define('ENTRY_NEWSLETTER_NO', '取消');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', '密碼:');
define('ENTRY_PASSWORD_ERROR', '密碼需要在' . ENTRY_PASSWORD_MIN_LENGTH . ' 位以上');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', '密碼不符');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', '確認密碼:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', '當前密碼:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', '密碼不得少於 ' . ENTRY_PASSWORD_MIN_LENGTH . ' 個字');
define('ENTRY_PASSWORD_NEW', '新密碼:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', '新密碼不得少於' . ENTRY_PASSWORD_MIN_LENGTH . ' 個字');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', '密碼不符');
define('PASSWORD_HIDDEN', '--隱藏--');
define('FORM_REQUIRED_INFORMATION', '* 表示該欄位必須填寫');

// constants for use in tep_prev_next_display function
//define('TEXT_RESULT_PAGE', '總頁數:');
define('TEXT_RESULT_PAGE', '');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '<span style="display:none">顯示 <b>%d</b> 到 <b>%d</b> </span>共%d個行程');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>筆訂單)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>條記錄)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>個新行程)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '顯示 <b>%d</b> 到 第 <b>%d</b> (共 <b>%d</b> 項特價)');


define('PREVNEXT_TITLE_FIRST_PAGE', '第一頁');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', '前一頁');
define('PREVNEXT_TITLE_NEXT_PAGE', '下一頁');
define('PREVNEXT_TITLE_LAST_PAGE', '最後一頁');
define('PREVNEXT_TITLE_PAGE_NO', '第%d頁');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', '前 %d 頁');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', '後 %d 頁');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;第一頁');
define('PREVNEXT_BUTTON_PREV', '&lt;&lt; 上一頁');
define('PREVNEXT_BUTTON_FIRST_SUB', '第一頁');
define('PREVNEXT_BUTTON_PREV_SUB', '上一頁');
define('PREVNEXT_BUTTON_NEXT', '下一頁 &gt;&gt;');
define('PREVNEXT_BUTTON_LAST', '末頁&gt;&gt;');
define('PREVNEXT_BUTTON_NEXT_SUB', '下一頁');
define('PREVNEXT_BUTTON_LAST_SUB', '末頁');

define('IMAGE_BUTTON_ADD_ADDRESS', '新增地址');
define('IMAGE_BUTTON_ADDRESS_BOOK', '通訊錄');
define('IMAGE_BUTTON_BACK', '回上頁');
define('IMAGE_BUTTON_BUY_NOW', '馬上買');
define('IMAGE_BUTTON_CHANGE_ADDRESS', '變更地址');
define('IMAGE_BUTTON_CHECKOUT', '結帳');
define('IMAGE_BUTTON_CONFIRM_ORDER', '確認訂單');
define('IMAGE_BUTTON_CONTINUE', '繼續');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', '繼續購物');
define('IMAGE_BUTTON_DELETE', '刪除');
define('IMAGE_BUTTON_EDIT_ACCOUNT', '編輯賬號');
define('IMAGE_BUTTON_HISTORY', '訂單記錄');
define('IMAGE_BUTTON_LOGIN', '登錄');
define('IMAGE_BUTTON_IN_CART', '放到購物車');
define('IMAGE_BUTTON_NOTIFICATIONS', '通知');
define('IMAGE_BUTTON_QUICK_FIND', '快速尋找');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', '移除商品通知');
define('IMAGE_BUTTON_REVIEWS', '評價');
define('IMAGE_BUTTON_SEARCH', '搜尋');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', '出貨選項');
define('IMAGE_BUTTON_TELL_A_FRIEND', '推薦給親友');
define('IMAGE_BUTTON_UPDATE', '更新');
define('IMAGE_BUTTON_UPDATE_CART', '更新購物車');
define('IMAGE_BUTTON_WRITE_REVIEW', '寫寫商品評論');

define('SMALL_IMAGE_BUTTON_DELETE', '刪除');
define('SMALL_IMAGE_BUTTON_EDIT', '編輯');
define('SMALL_IMAGE_BUTTON_VIEW', '檢視');

define('ICON_ARROW_RIGHT', '更多');
define('ICON_CART', '放到購物車');
define('ICON_ERROR', '錯誤');
define('ICON_SUCCESS', '完成');
define('ICON_WARNING', '注意');

define('TEXT_GREETING_PERSONAL', '<span class="greetUser">%s</span> 您好，歡迎光臨！ 想看看有什麼<a href="%s"><u>新進商品</u></a>？');
define('TEXT_GREETING_PERSONAL_RELOGON', '如果您不是 %s, 請用自己的賬號<a href="%s"><u>登錄</u></a>');
define('TEXT_GREETING_GUEST', '<span class="greetUser">訪客</span>，歡迎光臨，如果您已經是會員請直接<a href="%s"><u>登錄</u></a>？ 或是<a href="%s"><u>註冊為會員</u></a>？');

define('TEXT_SORT_PRODUCTS', '商品排序方式：');
define('TEXT_DESCENDINGLY', '遞減，');
define('TEXT_ASCENDINGLY', '遞增，');
define('TEXT_BY', '排序鍵：');

define('TEXT_REVIEW_BY', '%s 所評論寫道：');
define('TEXT_REVIEW_WORD_COUNT', '%s   字');
define('TEXT_REVIEW_RATING', '評等: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', '評論日期: %s');
define('TEXT_NO_REVIEWS', '無相關信息。');

//define('TEXT_NO_NEW_PRODUCTS', '目前沒有新進商品.');
define('TEXT_NO_NEW_PRODUCTS', '抱歉未能找到符合您要求的旅行團！<br /><br />您不妨減少您輸入的內容，或許會得到更多的結果。或者您可以直接撥打電話1-626-898-7800 888-887-2816，咨詢我們的旅遊專員。他們在每週一、三、五的上午9點到下午5點（太平洋時間）接受電話咨詢。 您還可以通過發郵件到inquiry@usitrip.com的方式與我們聯繫。我們會儘量在一到兩個工作日之內回復您的問題。');


define('TEXT_UNKNOWN_TAX_RATE', '不明的稅率');

define('TEXT_REQUIRED', '<span class="errorText">必填</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> 無法由指定的 SMTP 主機傳送郵件，請檢查 php.ini 設定</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', '警告： 安裝目錄仍然存在： ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. 基於安全的理由，請將這個目錄刪除');
define('WARNING_CONFIG_FILE_WRITEABLE', '警告： 設定檔允許被寫入： ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. 這將具有潛在的系統安全風險 - 請將檔案設定為正確的使用權限');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', '警告： sessions 資料夾不存在： ' . tep_session_save_path() . '. 在這個目錄未建立之前 Sessions 無法正常動作');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', '警告： 無法寫入sessions 資料夾： ' . tep_session_save_path() . '. 在使用者權限未正確設定之前 Sessions 將無法正常動作');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', '警告： 下載的商品目錄不存在： ' . DIR_FS_DOWNLOAD . '. 在這個目錄未建立之前，無法下載商品');
define('WARNING_SESSION_AUTO_START', '警告： session.auto_start 已啟動 - 請到 php.ini 內關閉這個功能，並重新啟動網頁主機');
define('TEXT_CCVAL_ERROR_INVALID_DATE', '輸入的信用卡到期日無效<br />請檢查日期後再試');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', '信用卡卡號無效<br />請檢查後再試');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', '您輸入的前四碼是: %s<br />如果正確，我們目前尚無法接受此類信用卡<br />如果錯誤請重試');
/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/




define('FREE_TEXT', '<img src="' . DIR_WS_IMAGES . 'table_background_payment.gif">免費!</img>');

define('CALL_TEXT', '<font color=red>價格請來電洽詢</font>');
define('CALL_LINK_ON','1');
define('CALL_LINK_TEXT','按這裏與我們聯絡');
define('CALL_LINK_OFF_TEXT','<font color=blue>洽詢電話請撥: xxxx-xxx-xxx</font>');
define('CALL_INCART_LINK', '<B><A HREF="' . DIR_WS_CATALOG . 'contact_us.php">' . CALL_LINK_TEXT . '</A></B>    ');

define('SOON_TEXT', '<font color=red>即將上市...</font>');
define('SOON_LINK_ON','0');
define('SOON_LINK_TEXT','按這裏與我們聯絡');
define('SOON_LINK_OFF_TEXT','<font color=blue>洽詢電話請撥: xxxx-xxx-xxx</font>');
define('SOON_INCART_LINK', '<B><A HREF="' . DIR_WS_CATALOG . 'contact_us.php">' . SOON_LINK_TEXT . '</A></B>    ');

require(DIR_FS_LANGUAGES . $language . '/' . 'banner_manager.php');


define('BOX_INFORMATION_ABOUT_US','關於我們'); 
  define('BOX_INFORMATION_CONDITIONS', '使用條款');
  define('BOX_INFORMATION_SITE_MAP', '網站地圖');
  define('BOX_INFORMATION_CONTACT', '聯係我們');
define('BOX_INFORMATION_DOWNLOAD_ACKNOWLEDGEMENT_CARD_BILLING','信用卡支付驗證書');
    //amit added new for language start
	define('FOOTER_TEXT_BODY', '<a href="javascript:void(0);" class="tip" onmouseover="document.getElementById(\'tip\').style.display=\'\'" onmouseout="document.getElementById(\'tip\').style.display=\'none\'">CST# 2096846-40</a> 版權 <span style="font-family:Arial" >&copy;</span>2006-'.date('Y').' usitrip.com, 擁有最終解釋權。<br />
	網站內價格和產品行程有可能會有更改變動，不做另行通知。<br />走四方網usitrip.com不對文字錯誤引起的不便負任何責任，文字錯誤都會及時更正。');

  define('BOTTOM_CST_NOTE_MSN','美國加利福尼亞州要求旅行團銷售方到州檢察院註冊，並在其所有廣告上展示註冊號。有效的註冊號表明此旅行團銷售方是依照法律註冊的。');
  define('BOX_INFORMATION_PRIVACY_AND_POLICY', '隱私條例');
  define('BOX_INFORMATION_PAYMENT_FAQ','付款常見問題');
  define('BOX_INFORMATION_COPY_RIGHT','版權');
  define('BOX_INFORMATION_CUSTOMER_AGREEMENT','客戶協議');
  define('BOX_INFORMATION_LINK_TO_US','友情鏈接');
  define('BOX_INFORMATION_CANCELLATION_REFUND_POLICY','取消和退款條例');  
  define('BOX_INFORMATION_VIEW_ALL_TOURS','查看所有旅遊');  
  
  
  /*advance search*/
define('DURATION', '持續時間：');
define('DEPARTURE_CITY', '選擇出發城市：');
define('TEXT_NONE', '-- 選擇出發城市 --');
define('OPTIONAL_KEYWORD', '輸入搜索關鍵字：');
define('START_DATE', '出發日期：');
define('IGNORE','忽略');

define('HEADING_SHIPPING_INFORMATION', '電子參團憑證（ETicket）寄送');

define('HEADING_ATTRACTION', '景點：');
/*Begin Checkout Without Account images*/
define('IMAGE_BUTTON_CREATE_ACCOUNT', '建立帳戶');
define('NAV_ORDER_INFO', '訂單信息');

/*End Checkout WIthout Account images*/
define('ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE', '國家代碼 ');
define('ENTRY_CELLPHONE_NUMBER',"手機號碼:");
define('ENTRY_CELLPHONE_NUMBER_TEXT', '');


define('BOX_INFORMATION_GV', '關於禮券的常見問題解答');
define('VOUCHER_BALANCE', '禮券餘額');
define('BOX_HEADING_GIFT_VOUCHER', '禮券帳戶'); 
define('GV_FAQ', '關於禮券的常見問題解答');
define('ERROR_REDEEMED_AMOUNT', '恭喜您，您的兌換成功了');
define('ERROR_NO_REDEEM_CODE', '您還沒有輸入兌換號碼.');  
define('ERROR_NO_INVALID_REDEEM_GV', '無效的禮券代碼'); 
define('TABLE_HEADING_CREDIT', '折扣券');
define('GV_HAS_VOUCHERA', '您的禮券帳戶上仍有餘額，如果您願意<br />
                         您可以將它們寄送出去通過<a class="pageResults" href="');       
define('GV_HAS_VOUCHERB', '"><b>以電子郵件寄給</b>給其他人'); 
define('ENTRY_AMOUNT_CHECK_ERROR', '您沒有足夠的禮券寄送這個數目.'); 
define('BOX_SEND_TO_FRIEND', '寄送禮券');
define('VOUCHER_REDEEMED', '禮券已經兌換');
define('CART_COUPON', '禮券 :');
define('CART_COUPON_INFO', '更多信息');
//amit added new for language end
//define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '顯示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>個商品)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', '顯示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)');  // featured tours
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', '顯示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // referrals
define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS', '顯示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // questions

//added for product listing page start
define('TEXT_WELCOME_TO','歡迎來到');
define('TEXT_CUSTOMER_AGREE_BOOK','請在網上預訂之前閱讀我們的客戶協議。');
define('TEXT_TOUR_PICKUP_NOTE','一個<FONT COLOR="#0000ff">旅遊套&#39184;</FONT> 通常包括機場的接送服務.');
define('TEXT_SORT_BY','排序方式：');
define('TEXT_TELL_YOUR_FRIEND','告訴您的朋友');
define('TEXT_ABOUT',' 關於 ');
define('TEXT_AND_MAKE','並且取得');
define('TEXT_COMMISSION','佣金');
define('TEXT_TOUR_ITINERARY','旅遊路線：');
define('TEXT_DEPART_FROM','出發城市：');//出發地點：
define('TEXT_OPERATE','出團時間：');    //出團日期
define('TEXT_PRICE','價格：');
define('TEXT_HIGHLIGHTS','行程特色：');//主要景點
define('TEXT_DURATION','持續時間：');
define('TEXT_DETAILS','查看詳情');
//added for product listing page end
//why book form us text

define('TEXT_TOP_HEADING_BOOK','我們的優勢：');

define('TAB_SPECIALLY_DESIGN_TOURS','精心設計的旅遊團');
define('TAB_LOW_PRICE_GUANRANTEED','低價保證');
define('TAB_EXPERIENCED_DRIVER','經驗豐富的司機');
define('TAB_PROFESSIONAL_TOUR_DUIDE','專業的導遊');
define('TAB_EXCELLETN_CUSTOMER_SERVICES','優質的客戶服務');

define('TEXT_PARA_SPECIALLY_DESIGN_TOURS','我們只為客戶提供精心打造的旅行。我們推出的旅行團，比如深受大家喜愛的黃石公園和總統山之旅，以及舉世聞名的大峽穀和尼亞加拉大瀑布之旅每年都會吸引成千上萬的遊客。很榮幸我們能幫您造就一段一生難忘的美好回憶。最重要的是，我們高質低價的旅行團只在這裏提供，別無尋處。');
define('TEXT_PARA_LOW_PRICE_GUANRANTEED','作為網上一流的旅遊供應商，也是網上最大的直屬代理商，我們提供最優質的服務和最佳的旅遊團。根據我們的規模和服務，我們向您保證，您將會以最優惠的價格享受最滿意的旅行。如果您選擇自費遊的話，所花的價錢可能會是我們的3-4倍，只有走四方網既能為您省下最多的錢，又能讓您充分感受旅行的樂趣。');
define('TEXT_PARA_EXPERIENCED_DRIVER','有著多年策劃假期旅遊和僅今年就組團上千的經驗，我們不僅能讓您體驗到旅遊的樂趣，還能讓您的旅途舒服自在。不象其他不善於組織策劃的旅遊公司，我們深知在一輛空間狹小的吉普車或汽車裏度過3-7天是多麼難受的事。因此，我們時間較長的團都會升級使用空間寬敞又舒適的豪華汽車。您不僅能輕鬆的在您的躺椅上隨意休息，還能享受到根據氣候調節的空調，獨享的閱讀燈，超大容量的儲物箱，VCD/DVD播放器和乾淨的休息室。乘坐我們的旅遊車旅行，您能感受到家一般的舒適。最重要的是，我們的司機都有著多年的經驗，他們對這些旅遊勝地的線路非常熟悉，而且他們本身還就是最棒的導遊。');
define('TEXT_PARA_PROFESSIONAL_TOUR_DUIDE','如果導遊對景點的認知比您還少，那將是一件最糟糕的事。所以我們只挑選最博學風趣的專業導遊。您不會再為導遊因為不熟悉環境而錯過一個重要的景點，或是無緣聽聞當地的傳說故事。我們專業的導遊會為您講述當地歷史，講一些平凡小事讓您開懷，還會用當地的趣聞樂事逗您笑個不停。在我們的旅遊團裏，您永遠不會感到無聊。');
define('TEXT_PARA_EXCELLETN_CUSTOMER_SERVICES','走四方網在提供歡樂旅程的同時，我們始終緊記，您-我們的顧客，才是每個旅遊團中最重要的元素。我們專業的客服代表會努力工作以確保您-我們尊貴的客人，在我們無微不至的服務下，由始至終都玩得盡興，玩得開心，不會留下一點遺憾。我們的宗旨就是讓遊客百分之百的滿意。');


define('TEXT_NO_QUESTION_FOUND','沒有找到相關信息。');
define('TEXT_SEARCH_FOR_YOUR_TOUR','搜索旅遊景點');

define('TEXT_TITLE_TOURS_DEALS','推薦旅遊');

//JAMES ADD FOR OTHERS TEXT
define('TEXT_NORMAL_TELL_FRIEND', '告訴您的朋友');
define('TEXT_NORMAL_ABOUT', '關於');
/* amit commneted old
define('TEXT_NORMAL_GAIN', '並且取得');
define('TEXT_NORMAL_COMISSION', '的佣金!');
*/
define('TEXT_NORMAL_GAIN', '就有機會獲得');
define('TEXT_NORMAL_COMISSION', '的傭金!');

//JAMES ADD FOR PRODUCT DURATION OPTIONS
define('TEXT_DURATION_OPTION_1','選擇持續天數');
define('TEXT_DURATION_OPTION_2','1天');
define('TEXT_DURATION_OPTION_3','2-3天');
define('TEXT_DURATION_OPTION_4','4-5天');
define('TEXT_DURATION_OPTION_5','6天以上');

define('TEXT_DURATION_OPTION_6','3 到 4 天');
define('TEXT_DURATION_OPTION_7','4 天');
define('TEXT_DURATION_OPTION_8','4 天或更多天數');
define('TEXT_DURATION_OPTION_9','5 天或更多天數');

define('TEXT_DURATION_HOURS','小時');

define('TEXT_ATTRACTION_OPTION_1','選擇景點');

define('TEXT_SORT_OPTION_1','--選擇排序方式--');
define('TEXT_SORT_OPTION_2','價格升序');
define('TEXT_SORT_OPTION_2_2','價格降序');
define('TEXT_SORT_OPTION_3','行程時間');
define('TEXT_SORT_OPTION_4','景點名稱');

define('TEXT_OPTION_FROM_TO','目的景點篩選');

define('TEXT_POPULAR_TOURS','暢銷景點');


//bof of navigation menu's translate

//WEST COAST TOURS
define('MENU_YELLOWSTONE_TOURS','美國黃石國家公園旅遊');
define('MENU_MTRUSHMORE_TOURS','拉斯莫爾山旅遊');
//define('MENU_LAS_VEGAS_TOURS','拉斯韋加斯旅遊');
//define('MENU_SAN_FRANCISCO_TOURS','舊金山旅遊');
define('MENU_LOS_ANGELES_TOURS','洛杉磯旅遊');
define('MENU_GRAND_CANYON_TOURS','大峽谷旅遊');
define('MENU_DISNELYLAND_TOURS','迪斯尼樂園旅遊');
define('MENU_SAN_DIEGO_TOURS','聖地亞哥旅遊');
define('MENU_UNIVERSAL_STUDIOS_TOURS','環球影城旅遊');
define('MENU_YOSEMITE_TOURS','約塞米蒂國家公園旅遊');
define('MENU_SEQUOIA_KINGS_CANYON_NP_TOURS','美洲杉國王峽谷國家公園旅遊');
define('MENU_MEXICO_TOURS','墨西哥城旅遊');
define('MENU_LAKE_TAHOE_TOURS','太浩湖旅遊');
define('MENU_SACRAMENTO_TOURS','薩克拉曼多旅遊');
define('MENU_NAPA_VALLEY_TOURS','納帕穀酒鄉之旅');
define('MENU_MORE_WEST_COAST_TOURS','<b>更多西海岸旅遊</b>');

//EAST COAST TOURS
//define('MENU_NEW_YORK_TOURS','紐約旅遊');
define('MENU_BOSTON_TOURS','波士頓旅遊');
define('MENU_CANADA_TOURS','加拿大旅遊');
define('MENU_NIAGARA_FALLS_TOURS','尼亞加拉河瀑布旅遊');
define('MENU_PHILADELPHIA_TOURS','費城旅遊');
define('MENU_WASHINGTON_DC_TOURS','華盛頓哥倫比亞特區旅遊');
define('MENU_BALTIMORE_TOURS','巴爾的摩旅遊');
define('MENU_RHODE_ISLAND_TOURS','美國羅得島州旅遊');
define('MENU_SHENANDOAH_TOURS','維珍尼亞州僊人洞旅遊');
define('MENU_GORNING_CLASS_CENTER_TOURS','康寧玻璃中心旅遊');
define('MENU_MORE_EAST_COAST','<b>更多東海岸旅遊</b>');

//Hawaii TourS
define('MENU_MORE_HAWAII_TOURS','<b>更多夏威夷,旅遊</b>');

//Florida Tour Packages
define('MENU_FLORIDA_TOURS_PACKAGES','佛羅里達旅遊套&#39184; Florida Tour Packages');


//Tours ByCity
define('MENU_LOSANGELES_TOURS','洛杉磯 Los Angeles');
define('MENU_LAS_VEGAS_TOURS','拉斯維加斯 Las Vegas');
define('MENU_SALT_LAKE_CITY_TOURS','鹽湖城 Salt Lake City');
define('MENU_SAN_FRANCISCO_TOURS','三藩市 San Francisco');
define('MENU_NEW_YORK_TOURS','紐約 New York');
define('MENU_HONOLUU_TOURS','檀香山 Honolulu');
define('MENU_ORLANDO_TOURS','奧蘭多 Orlando');
define('MENU_PHILADEPHIA_TOURS','費城Philadelphia');

//amit added for shopping cart start

define('TEXT_TOTAL_NO_OF_ROOMS','總房間數量');
define('TEXT_OF_ADULTS_IN_ROOM','房間內成人數量');
define('TEXT_OF_CHILDREN_IN_ROOM','房間內小孩數量');
define('TEXT_TOTAL_OF_ROOM','總房間數量');
define('TEXT_NO_ADULTS','# 成人');
define('TEXT_NO_CHILDREN','# 小孩');
define('TEXT_TOTLAL','共計');



//used new desing
define('TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS','總房間數量');
define('TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS','&nbsp;&nbsp;# 房間內成人數量');
define('TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS','&nbsp;&nbsp;# 房間內小孩數量');
define('TEXT_SHOPPIFG_CART_ADULTS_NO','&nbsp;&nbsp;# 成人');
define('TEXT_SHOPPIFG_CART_CHILDREDN_NO','&nbsp;&nbsp;# 小孩');
define('TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM','總房間數量');
define('TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL','共計');


define('TEXT_BY', '提問人:');
define('TEXT_REPLY_BY', '答復人:');
define('TEXT_AT', '於 : &nbsp;');

define('TEXT_WEEK','星期');
define('TEXT_WEEK_MON','星期一');
define('TEXT_WEEK_TUE','星期二');
define('TEXT_WEEK_WED','星期三');
define('TEXT_WEEK_THU','星期四');
define('TEXT_WEEK_FRI','星期五');
define('TEXT_WEEK_SAT','星期六');
define('TEXT_WEEK_SUN','星期天');
define('TEXT_DAILY','每日');
define('TEXT_TO','至');

//new design control
/*
		Homepage	Hotel Reservation	Flight Reservation	East Coast Tours	West Coast Tours	Las Vegas Tours	Hawaii Tours	Florida Tours	Destination Guide

*/		
define('TEXT_MENU_HOME_LINK','主頁');
define('TEXT_MENU_HOTEL_RESERVATION','賓館預訂');
define('TEXT_MENU_FLIGHT_RESERVATION','機票定購');
define('TEXT_MENU_EAST_COAST_TOURS','美國東海岸');
define('TEXT_MENU_WEST_COAST_TOURS','美國西海岸');
//define('TEXT_MENU_LAS_VEGAS_TOURS','拉斯維加斯旅遊');
define('TEXT_MENU_HAWAII_TOURS','夏威夷旅遊');
define('TEXT_MENU_FLORIDA_TOURS','佛羅里達旅遊');
define('TEXT_MENU_DESTINATION_GUIDE','目的地指南');


define('TEXT_TOP_LINK_REGISTER_REGISTER','註冊');
define('TEXT_TOP_LINK_REGISTER_LOGIN','登錄');
define('TEXT_TOP_LINK_CONTACT_US','聯係我們');
define('TEXT_TOP_LINK_SUPPORT_SERVICES','服務/支持');
define('HEADING_TEXT_FAMOUS_ATTRACTIONS','著名景點推薦');
//amit added for sub categories page start
define('HEADING_TEXT_BLUE_TOURS_TITLE','精品推薦');
define('TEXT_VIEW_MORE_TORUS_TITLE','查看更多');


define('TEXT_DURATION_DAY','天');
define('TEXT_PLEASE_INSERT_GUEST_NAME','請輸入游客姓名');
define('TEXT_PLEASE_INSERT_GUEST_EMAIL','請輸入遊伴郵箱');
define('TEXT_HEADING_MONTH_1','一月');
define('TEXT_HEADING_MONTH_2','二月');
define('TEXT_HEADING_MONTH_3','三月');
define('TEXT_HEADING_MONTH_4','四月');
define('TEXT_HEADING_MONTH_5','五月');
define('TEXT_HEADING_MONTH_6','六月');
define('TEXT_HEADING_MONTH_7','七月');
define('TEXT_HEADING_MONTH_8','八月');
define('TEXT_HEADING_MONTH_9','九月');
define('TEXT_HEADING_MONTH_10','十月');
define('TEXT_HEADING_MONTH_11','十一月');
define('TEXT_HEADING_MONTH_12','十二月');

define('TEXT_HEADING_MONTH_NUM_1','1月');
define('TEXT_HEADING_MONTH_NUM_2','2月');
define('TEXT_HEADING_MONTH_NUM_3','3月');
define('TEXT_HEADING_MONTH_NUM_4','4月');
define('TEXT_HEADING_MONTH_NUM_5','5月');
define('TEXT_HEADING_MONTH_NUM_6','6月');
define('TEXT_HEADING_MONTH_NUM_7','7月');
define('TEXT_HEADING_MONTH_NUM_8','8月');
define('TEXT_HEADING_MONTH_NUM_9','9月');
define('TEXT_HEADING_MONTH_NUM_10','10月');
define('TEXT_HEADING_MONTH_NUM_11','11月');
define('TEXT_HEADING_MONTH_NUM_12','12月');

define('TEXT_TOTAL_OF_ROOMS','總房間數：');

define('TEXT_OF_ADULTS_IN_ROOM1','房間一成人數：');//第一房間內成人數(Number of Adults in Room 1):
define('TEXT_OF_ADULTS_IN_ROOM2','房間二成人數：');
define('TEXT_OF_ADULTS_IN_ROOM3','房間三成人數：');
define('TEXT_OF_ADULTS_IN_ROOM4','房間四成人數：');
define('TEXT_OF_ADULTS_IN_ROOM5','房間五成人數：');
define('TEXT_OF_ADULTS_IN_ROOM6','房間六成人數：');
define('TEXT_OF_ADULTS_IN_ROOM7','房間七成人數：');
define('TEXT_OF_ADULTS_IN_ROOM8','房間八成人數：');
define('TEXT_OF_ADULTS_IN_ROOM9','房間九成人數：');
define('TEXT_OF_ADULTS_IN_ROOM10','房間十成人數：');
define('TEXT_OF_ADULTS_IN_ROOM11','房間十一成人數：');
define('TEXT_OF_ADULTS_IN_ROOM12','房間十二成人數：');
define('TEXT_OF_ADULTS_IN_ROOM13','房間十三成人數：');
define('TEXT_OF_ADULTS_IN_ROOM14','房間十四成人數：');
define('TEXT_OF_ADULTS_IN_ROOM15','房間十五成人數：');

define('TEXT_OF_CHILDREN_IN_ROOM1','房間一兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM2','房間二兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM3','房間三兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM4','房間四兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM5','房間五兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM6','房間六兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM7','房間七兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM8','房間八兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM9','房間九兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM10','房間十兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM11','房間十一兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM12','房間十二兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM13','房間十三兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM14','房間十四兒童數：');
define('TEXT_OF_CHILDREN_IN_ROOM15','房間十五兒童數：');

define('TEXT_TOTLE_OF_ROOM1','房間一小計：');
define('TEXT_TOTLE_OF_ROOM2','房間二小計：');
define('TEXT_TOTLE_OF_ROOM3','房間三小計：');
define('TEXT_TOTLE_OF_ROOM4','房間四小計：');
define('TEXT_TOTLE_OF_ROOM5','房間五小計：');
define('TEXT_TOTLE_OF_ROOM6','房間六小計：');
define('TEXT_TOTLE_OF_ROOM7','房間七小計：');
define('TEXT_TOTLE_OF_ROOM8','房間八小計：');
define('TEXT_TOTLE_OF_ROOM9','房間九小計：');
define('TEXT_TOTLE_OF_ROOM10','房間十小計：');
define('TEXT_TOTLE_OF_ROOM11','房間十一小計：');
define('TEXT_TOTLE_OF_ROOM12','房間十二小計：');
define('TEXT_TOTLE_OF_ROOM13','房間十三小計：');
define('TEXT_TOTLE_OF_ROOM14','房間十四小計：');
define('TEXT_TOTLE_OF_ROOM15','房間十五小計：');

define('TEXT_BED_OF_ROOM1','房間一床型：');
define('TEXT_BED_OF_ROOM2','房間二床型：');
define('TEXT_BED_OF_ROOM3','房間三床型：');
define('TEXT_BED_OF_ROOM4','房間四床型：');
define('TEXT_BED_OF_ROOM5','房間五床型：');
define('TEXT_BED_OF_ROOM6','房間六床型：');
define('TEXT_BED_OF_ROOM7','房間七床型：');
define('TEXT_BED_OF_ROOM8','房間八床型：');
define('TEXT_BED_OF_ROOM9','房間九床型：');
define('TEXT_BED_OF_ROOM10','房間十床型：');
define('TEXT_BED_OF_ROOM11','房間十一床型：');
define('TEXT_BED_OF_ROOM12','房間十二床型：');
define('TEXT_BED_OF_ROOM13','房間十三床型：');
define('TEXT_BED_OF_ROOM14','房間十四床型：');
define('TEXT_BED_OF_ROOM15','房間十五床型：');
define('TEXT_BED_STANDARD','不限制');
define('TEXT_BED_KING','兩張標準床');
define('TEXT_BED_QUEEN','一張King-sized大床');

define('QNA_FAQ_BACK_TO_TOP', '[Top]');
define('TEXT_DISPLAY_TOP','TOP');
define('TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE','假日價格');
define('TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE','升級價格');
define('TEXT_HEADING_PRODUCT_ATTRIBUTE_OPTIONS_TOUR','');
define('TEXT_HEADING_REGULAR_SPECIAL_PRICE','標準價格');

//error massaeg display
define('TEXT_ERROR_MSG_YOUR_NAME', '* 請輸入您的姓名.<br/>');
define('TEXT_ERROR_MSG_YOUR_EMAIL', '* 請輸入您的電子郵件地址.<br/>');
define('TEXT_ERROR_MSG_VALID_EMAIL', '* 請輸入有效的電子郵箱.<br/>');
define('TEXT_ERROR_MSG_YOUR_EMAIL_CONFIRMATION', '* 郵箱地址的確認要與您之前所填寫的郵箱地址相同.<br/>');
define('TEXT_ERROR_MSG_REVIEW_TITLE', '* 請輸入評論標題.<br/>');
define('TEXT_ERROR_MSG_REVIEW_TEXT', '* 請輸入評論內容.<br/>');
define('TEXT_ERROR_MSG_REVIEW_RATING', '* 您需要評定旅行的等級.<br/>');
define('TEXT_ERROR_MSG_YOUR_QUESTION', '* 請輸入您的問題.<br/>');
define('TEXT_ERROR_MSG_YOUR_ANSWERS', '* 請輸入您的答案.<br/>');
define('TEXT_DURATION_OPTION_ALL_DURATIONS','所有時間');
define('TEXT_DURATION_OPTION_DURATION','行程時間');
define('TEXT_DURATION_OPTION_LESS_ONE','1天以內');
define('TEXT_DEPARTURE_OPTION_CITY','出發城市篩選');
define('TEXT_DEPARTURE_OPTION_ALL_DEPARTURE_CITY','所有出發城市');
define('TEXT_OPTION_TOUR_TYPE','旅遊類型');
define('TEXT_OPTION_ALL_TOUR_TYPES','所有旅遊類型');
define('TEXT_OPTION_FILTER_BY','篩選:');
define('TEXT_OPTION_SORT_BY','排序按:');
define('TEXT_TAB_INTRODUCTION','景點介紹');
define('TEXT_TAB_TOURS','周邊熱點遊');
define('TEXT_TAB_VACATION_PACKAGES',"度假休閒遊 ");
define('TEXT_TAB_RECOMMENDED','限時搶購團');
define('TEXT_TAB_SPECIAL','限時搶購團');
define('TEXT_TAB_MAP','Map');
//define('TEXT_NOTES_CLICK_VIDEO','點擊 <img src="image/vido_light_bg.gif" alt="" /> 收看各旅遊勝地的錄像');
define('TEXT_NOTES_CLICK_VIDEO','');
define('TEXT_SEARCH_RESULT_BOX_HEADING','搜索結果');
define('TEXT_REQUERED_NOT_DISPLAYED','(必須填寫但是不會顯示在頁面)');
define('TEXT_REVIEW_ADDED_SUCCESS','您的評論添加成功.');
define('HEADING_REFEAR_A_FRIEND_RECOMMEND_CATGORY_OR_TOUR','推薦類別/旅行');
define('HEADING_REFEAR_A_FRIEND_YOUR_PERSONAME_DETAILS','個人詳細資訊');
define('HEADING_REFEAR_A_FRIEND_EMAIL_ADDRESS','您朋友的郵箱地址');
define('HEADING_REFEAR_A_FRIEND_A_MESSAGE_TO_FRIEND','給您朋友的留言');


define('TEXT_SHOPPING_CART_DEPARTURE_DATE','出發日期:');
define('TEXT_SHOPPING_CART_PICKP_LOCATION','出發地點:');
define('TEXT_SHOPPING_CART_REMOVE_RESERVATION_LIST_CONFIRM','您確定要將這次旅行預訂從列表中刪除嗎？');
define('TEXT_SHOPPING_CART_DEPARTURE_DATE_PRICE_FLUCTUATIONS','出發日期價格浮動:');



//header defines
define('TEXT_MEMBER_LOGIN','會員登錄');
define('TEXT_FREE_REG','免費註冊');

define('TEXT_HEADER_WELCOME_TO','歡迎來到走四方網！');
//define('TEXT_HEADER_ALREADY_A_MEMBER','已經是會員?');
//define('TEXT_HEADER_JOIN_TODAY','Join today');
define('TEXT_HEADER_ALREADY_A_MEMBER','');
define('TEXT_HEADER_JOIN_TODAY','');
define('TEXT_MENU_VACATION_PACKAGES','旅遊套&#39184;');
define('TEXT_MENU_BY_DEPARTURE_CITY','出發城市');

define('TEXT_NO_PRODUCTS', '<br /><br />抱歉未能找到符合您要求的旅行團！ <br /><br />
您不妨減少您輸入的內容，或許會得到更多的結果。或者您可以直接撥打電話1-626-898-7800 888-887-2816，咨詢我們的旅遊專員。
他們在每週一、三、五的上午9點到下午5點（太平洋時間）接受電話咨詢。 您還可以通過發郵件到<a class="sp3" href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'">'.STORE_OWNER_EMAIL_ADDRESS.'</a>的方式與我們聯繫。我們會儘量在一到兩個工作日之內回復您的問題。<br /><br />');

define('TEXT_HEADING_MORE_DEPARTURE_CITIES','<strong>更多出發城市</strong>');
define('TEXT_DROP_DOWN_SELECT_COUNTRY','選擇國家');
define('TEXT_HEDING_COUNTRY_SEARCH','國家/地區:');
//define('TEXT_DROP_DOWN_SELECT_REGION','選擇地區/類別');
define('TEXT_DROP_DOWN_SELECT_REGION','選擇類別/目的地');
define('TEXT_MU_DI_DI','選擇目的地');

if(HTTP_SERVER==TW_CHINESE_HTTP_SERVER){
	define('LANGUAGE_BUTTON','<li><a href="'.SCHINESE_HTTP_SERVER.$_SERVER['REQUEST_URI'].'" title="訪問簡體版的usitrip">&#31616;&#20307;</a></li><li class="us" style="display:none"><a href="http://www.usitrip.com/" title="to usitrip" >English</a></li>');
	define('LANGUAGE_BUTTON_1','<a href="'.SCHINESE_HTTP_SERVER.$_SERVER['REQUEST_URI'].'" title="訪問簡體版的usitrip">&#31616;&#20307;</a>');
}else{
	if(preg_match('/\?/',$_SERVER['REQUEST_URI'])){ $strlink = '&';}else{ $strlink = '?';}
	define('LANGUAGE_BUTTON','<li><a href="'.HTTP_SERVER.preg_replace('/(&*)language=(tw|sc)(&*)/','',$_SERVER['REQUEST_URI']).$strlink.'language=sc'.'" title="訪問簡體版的usitrip" class="bai_lan">&#31616;&#20307;</a></li><li class="us"><a href="http://www.usitrip.com/" title="to usitrip" style="display:none">English</a></li>');
	define('LANGUAGE_BUTTON_1','<a href="'.HTTP_SERVER.preg_replace('/(&*)language=(tw|sc)(&*)/','',$_SERVER['REQUEST_URI']).$strlink.'language=sc'.'" title="訪問簡體版的usitrip" class="bai_lan">&#31616;&#20307;</a>');
}

define('TEXT_ALTER_TAG','tours, travel');

define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', '顯示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 個產品)');
define('HEDING_TEXT_ENTER_PHOTO_TITLE','在這裡輸入標題');
define('HEDING_TEXT_ENTER_PHOTO_DESCRIPTION','在這裡輸入內容');

define('TEXT_DISPLAY_NUMBER_OF_PHOTOS', '結果-<b>%d</b>-<b>%d</b> 共 <b>%d</b> 條');

define('TEXT_PLEASE_INSERT_GUEST_LASTNAME','請輸入客戶姓氏');
define('TEXT_DROPDOWN_POULARITY','瀏覽量');



define('BOX_HEADING_LOYAL_CUSTOMER_GOOD_NEWS','給忠實客戶的好消息！');
define('BOX_HEADING_LOYAL_CUSTOMER_PARA','只要您在usitrip消費過一次，只要您再次向我們訂團，您的新定單將即時享受5%的折扣！我們藉以此種方式來表達謝意，感謝您再次選擇我們。');
define('TEXT_LOYAL_CUSMER_PERC_REWARD_FOR','5% 忠實客戶 <br/>折扣面向我們所有的');
define('TEXT_LOYAL_CUSMER_REPEAT_LINK','回頭顧客！');
define('TEXT_HEADING_RED_REPEAT_CUSTOMERS_NOTES','<font color="#ff0000"><b>5%的折扣給我們的忠實客戶。現在就<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=checkout', 'SSL') . '" style="color:#ff0000"><u>支付</u></a>吧！</b></font>');
define('TEXT_TITLE_DEPARTURE_CITY', '旅遊的出發城市');

define('FORGOT_PASSWORD','忘記密碼？');
define('REGISTER','註冊');
define('MY_TOURS','我的走四方');

define('SELECT_DESTINATIONS','選擇去向');
define('LOGIN_INPUT_TEXT','用戶名');
define('LOGIN_NAME','登錄名');
define('JS_NO_LOGIN_NAME','請輸入您的'.LOGIN_NAME.'(您在本站註冊時使用的電子郵箱)！');

define('YOUR_NEWS_LETTER_EMAIL','您的E-mail地址');
define('JS_NO_NEWS_LETTER_EMAIL','請輸入您的E-mail地址');
define('NEWS_LETTER_EMAIL_SUBMIT_OK','您的E-mail地址提交成功。');
define('PLX_WAIT','<img src="image/loading_16x16.gif" width="16" height="16" align="absmiddle" />&nbsp;請稍後...');

define('WELCOME_YOU','歡迎您');
define('HAVE_POINTS','擁有積分：');
define('WELCOME_TO_TOURS','%s,歡迎您來到走四方！');
define('ORDER_NEED_DO','您有<span class="hongse cu">%d</span>筆訂單需要處理。');
define('MY_SPACE','我的空間');
define('MY_SPACE_INFORMATION','個人信息');
define('MY_SPACE_LOGS','日誌');
define('MY_SPACE_LOOKS','隨便逛逛');
define('MY_SPACE_PHOTOS','相冊');
define('MY_SPACE_SHARE','分享');

define('SKIP_TO','跳轉至：');
define('ACCOUNT_MANAGEMENT','帳戶管理');
define('DEFAULT_STRING','默認');
define('SET_FOR_HOME','為首頁');

define('LOGIN_OVERTIME','登錄超時！');

// Points/Rewards Module V2.1rc2a BOF
define('MY_POINTS_TITLE', '我的積分');
define('MY_POINTS_VIEW', '積分獎勵概要');
define('MY_POINTS_VIEW_HELP', '積分FAQ');
define('MY_POINTS_CURRENT_BALANCE', '總積分： %s  價值： %s ');
define('REWARDS4FUN_ACTIONS_DESCRIPTION', '活動說明');
define('REWARDS4FUN_REFER_FRIENDS','推薦給朋友');
define('REWARDS4FUN_ACTIONS_HISTORY', '獎勵/兌換記錄');
define('REWARDS4FUN_FEEDBACK_APPROVAL', '評論鏈結回饋');
define('REWARDS4FUN_TERMS', 'Rewards4Fun條款'); 
define('REWARDS4FUN_TERMS_NAVI', '活動執行規則');
define('TEXT_DISCOUNT_UP_TO', 'Rewards4Fun折扣高達： ');
define('REDEEM_SYSTEM_ERROR_POINTS_NOT', '點值是不夠的，以支付您的購買。請選擇其他付款方式');
define('REDEEM_SYSTEM_ERROR_POINTS_OVER', 'REDEEM記分錯誤！點的價值不能超過總價值。請重新輸入點');
define('REFERRAL_ERROR_SELF', '很抱歉，您可以不提及自己。');
define('REFERRAL_ERROR_NOT_VALID', '推介電子郵件似乎沒有有效的-請進行任何必要的更正。');
define('REFERRAL_ERROR_NOT_FOUND', '推介的電子郵件地址您輸入沒有被發現。');
define('TEXT_POINTS_BALANCE', '點狀態');
define('TEXT_POINTS', '點：');
define('TEXT_VALUE', '價值：');
define('REVIEW_HELP_LINK', ' 點評您在旅途中對各方面的評價、分享您的旅遊心情和感受，即可獲得<b>%s</b>積分/條。 %s ');//worth of，
define('PHOTOS_HELP_LINK', ' 將您的旅途中的靚照與走四方網分享，與走四方網的朋友分享，走四方網對上傳的照片每張給予 <b>%s</b> 積分的獎勵，上傳多多，積分多多，更可為您下次的旅行節省開支！也可以結識更多的旅友！<br />查看 %s 更多優惠活動。	');
define('ANSWER_HELP_LINK', ' 回答問題獲取<b>%s</b>個走四方積分。請點擊 %s 瞭解詳情。');
define('REFER_FRIEND_HELP_LINK', ' 參與走四方積分回饋，贏取美元現金折扣！ <br><br>將走四方網或我們的旅遊產品通過郵件的方式推薦給您的朋友。如果您的朋友通過、郵件點擊並成為走四方網的註冊用戶，您就可以獲得每位 <b>%s</b> 積分的獎勵。在您訂購旅遊行程時，便可使用這些積分兌換為美元進行消費！<br />欲瞭解更多資訊，請單擊 %s 。');
define('BOX_INFORMATION_MY_POINTS_HELP', '積分FAQ');
define('TEXT_MENU_JOIN_REWARDS4FUN','積分獎勵');
define('TEXT_REG_GET_REWARDS4FUN','註冊即可獲得'.NEW_SIGNUP_POINT_AMOUNT.'積分獎勵！');
// Points/Rewards Module V2.1rc2a EOF

//howard added
define('NEXT_NEED_SIGN','請您登錄後進行下一步操作！');
define('SUNDAY','周日');
define('MONDAY','周一');
define('TUESDAY','周二');
define('WEDNESDAY','周三');
define('THURSDAY','周四');
define('FRIDAY','周五');
define('SATURDAY','周六');
define('TEXT_MONTH','月');
define('TEXT_DAY','日');
define('TEXT_YEAR','年');
define('TEXT_DAILY','每天');

define('HEADING_ORDER_COMMENTS','訂購留言');
define('HEADING_ORDER_COMMENTS_NOTES','<b>提示：</b>通過“結伴同游”下單的用戶，請在此方框內注明“結伴同遊”字樣及同行拼房用戶的訂單號。');

define('OFFICE_PHONE','聯繫電話');
define('HOME_PHONE','其他電話');
define('MOBILE_PHONE','移動電話');

define('PHONE_TYPE_ERROR','請選擇您填寫的電話號碼的所屬類型！');
define('RADIO_ERROR','必須選擇其中一項。');
define('SELECT_OPTION_ERROR','請作出一個選擇。');

define('MONTH_DAY_YEAR','月/日/年');
//howard added end

define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE','總價(包括3%服務費)');
define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_PERSENT','總價(包括%s%%服務費)');
define('TEXT_PRODUCTS_MIN_GUEST','參團人數不能少於:');

define('ORDER_TOTAL_TEXT','總計:');

define('TEXT_HEADING_DEPARTURE_TIME_LOCATIONS_LL','出發時間和地點');
define('TEXT_FOOTER_TRAVEL_INSURANCE','旅遊保險');

define('TEXT_RMB_CHECK_OUT_MSN','走四方所有產品以美元作為基準計價幣種，美元兌換人民幣匯率以銀行當日匯率中間價為準。您可以自由選擇此雙幣種多種支付方式的預訂服務。');

define('SEARCH_RECOMMEND','大峽谷 黃石公園');

define('ERROR_SEL_SHUTTLE','請選擇您的上車地點');
define('TEXT_MAX_ALLOW_ROOM','每房間允許的客人最大數:');

define('SHARE_ROOM_WITH_TRAVEL_COMPANION','結伴拼房');

//define('JS_MAY_NOT_ENTER_TEXT','可不填');
define('JS_MAY_NOT_ENTER_TEXT','');
define('JS_UNKNOWN_STRING','未知');

define('JIEBANG_CART_NOTE_MSN','註:結伴同遊只能下一次訂單，其他結伴者請登入用戶中心，查看“結伴同遊訂單”，確認個人信息並選擇支付即可。'); 

define('HEADING_BILLING_INFORMATION', '帳單資訊');
define('HEADING_BILLING_ADDRESS', '信用卡地址');

define('TEXT_BILLING_INFO_ADDRESS', ENTRY_STREET_ADDRESS);
define('TEXT_BILLING_INFO_CITY', ENTRY_CITY);
define('TEXT_BILLING_INFO_STATE', ENTRY_STATE);
define('TEXT_BILLING_INFO_POSTAL', ENTRY_POST_CODE);
define('TEXT_BILLING_INFO_COUNTRY', ENTRY_COUNTRY);
define('TEXT_BILLING_INFO_TELEPHONE', '辦公電話:');
define('TEXT_BILLING_INFO_FAX', ENTRY_FAX_NUMBER);
define('TEXT_BILLING_INFO_MOBILE', '移動電話:');

define('TEXT_EDIT', '編輯');

define('MY_TRAVEL_COMPANION','我的結伴同遊');
define('MY_TRAVEL_COMPANION_ORDERS','結伴同游訂單');
define('I_SENT_TRAVEL_COMPANION_BBS','我的發貼');
define('I_REPLY_TRAVEL_COMPANION_BBS','我的回帖');
define('LATEST_TRAVEL_COMPANION_BBS','最新結伴同遊帖');

define('TEXT_DURATION_LINK_1','1-2天');
define('TEXT_DURATION_LINK_2','3天行程');
define('TEXT_DURATION_LINK_3','4天行程');
define('TEXT_DURATION_LINK_4','5-6天');
define('TEXT_DURATION_LINK_5','7天以上');
define('TEXT_DURATION_LINK_ALL','全部');
define('TEXT_TRAVEL_OPTIONS', '<!--旅行選擇-->');

//amit added 2009-12-09
define('TEXT_REVIEW', '評論');
define('TEXT_QANDA', '諮詢');
define('TEXT_PHOTOS', '照片');
define('TEXT_TRAVEL_COMPANION_POSTS', '結伴同遊'); //結伴發貼

define('HEADING_DESTINATIONS', '景點列表');
define('HEADING_ATTRACTIONS', '目的地景點');
define('HEADING_DEPARTURE_CITIES', '按出發城市查看');

//howard added 2010-01-12
define('TITLE_GROUP_BUY','團體預定優惠:');
define('TITLE_BBS_CONTENT','帖子內容');
define('TITLE_NEW_GROUP_BUY','團購優惠:');
define('TITLE_NEW_GROUP_BUY_OLD_PRICE','原房間總價:');
define('TITLE_NEW_GROUP_BUY_OLD_PRICE_NOT_ROOM','原價:');

define('TEXT_HOW_SAVE','積分現金折扣比例');
define('TEXT_SAVINGS','積分目前只適用于行程抵扣。在積分足夠的情況下：<br />第一次訂購優惠最低<span style="color: rgb(241, 115, 13);">3%</span>,最高<span style="color: rgb(241, 115, 13);">6%</span>！<br>第二次訂購優惠最低<span style="color: rgb(241, 115, 13);">4%</span>,最高<span style="color: rgb(241, 115, 13);">7%</span>！<br>訂購超過兩次優惠最低<span style="color: rgb(241, 115, 13);">5%</span>,最高<span style="color: rgb(241, 115, 13);">8%</span>！');

define('TFF_POINTS_DESCRIPTION','走四方積分兌換說明');
define('TFF_POINTS_DESCRIPTION_CONTENT','兌換比例：100積分兌換1美元。<br>  兌換方法：在行程結帳頁面的“積分兌換”，您可以看到您目前的積分總數及訂購此行程可使用的最高積分數。隨後點擊“兌換積分”按鈕，系統自動計算您最後需要支付的余額，確認抵換後，系統自動扣除您的積分。');

//las vegas show
define('PERFORMANCE_TIME','演出時間:');
define('WATCH_PEOPLE_NUM','人數:');

//howard added 2010-5-18
define('BUY_SUCCESS_SMS',"您的訂單（%s）已生成，登陸網站至“我的走四方”或您的註冊郵箱查看進度。服務熱線：4006-333-926，感謝您的預訂！");
//tom added 2010-5-27
define('TEXT_ORDER_STATUS_PENDING','Pending');

define('YELLOWSTONE_TABLE_NOTES','實際剩餘座位數以下訂單後的郵件確認為準，郵件通常在1-2個工作日內回复。');

define('MY_COUPONS','優惠券');
define('MY_COUPONS_MENU','我的優惠券');
define('CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID','96'); // donot modify without permission
define('TEXT_VISA_PASS_YREQ','<strong>參觀美國及加拿大兩側瀑布：</strong> 除持有美國護照或綠卡外，進入加拿大境內還需要有效的戶照和簽證。請<a href="http://www.cic.gc.ca/english/visit/visas.asp" target="_blank">點擊這裏</a>獲取關於遊客簽證要求及豁免權資訊。');
define('TEXT_VISA_PASS_NOTREQ','<strong>僅參觀美國一側瀑布：</strong> 此類行程不要求提供加拿大簽證。');
define('TITLE_CREDIT_APPLIED','信用支付金額: ');
define('TEXT_SELECT_VALID_DEPARTURE_DATE','請選擇計劃出發時間');

define('RATING_STR_5','非常滿意');
define('RATING_STR_4','比較滿意');
define('RATING_STR_3','一般');
define('RATING_STR_2','不滿意');
define('RATING_STR_1','很不滿意');
define('ENTRY_HEIGHT','身高 (ft/cm):');
define('ENTRY_HEIGHT_ERROR','身高');

define('TXT_FEATURED_DEAL_DISCOUNT', '特色團購');
define('TXT_FEATURED_DEALS_SECTION', 'Featured Deals');
//yichi added 2011-04-02
define('BEFORE_EXTENSION_SMS',"您或許在參團前有加訂酒店住宿的需求，我們可為您提供潔淨、舒適、輕鬆參團的酒店增訂服務；服務熱線400-6333926或 Service@usitrip.com ");
//yichi added 2011-04-02
define('AFTER_EXTENSION_SMS',"您或許在參團後有加訂酒店住宿的需求，我們可為您提供潔淨、舒適、輕鬆參團的酒店增訂服務；服務熱線400-6333926或 Service@usitrip.com ");

define('NO_SEL_DATE_FOR_GROUP_BUY','未定日期');
define('TEXT_BEFORE','之前');
define('HOTEL_EXT_ATTRIBUTE_OPTION_ID','9999'); // donot modify without permission

define('SEARCH_BOX_TIPS',"請輸入出發城市或想去的景點");
define('SEARCH_BOX_TIPS1',"請輸入關鍵字");
define('TXT_ADD_FEATURES_TOUR_IDS', '');
define('PRIORITY_MAIL_PRODUCTS_OPTIONS_ID', '146'); // donot modify without permission
define('PRIORITY_MAIL_PRODUCTS_OPTIONS_VALUES_ID', '866'); // donot modify without permission
define('TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE', '門票郵遞日期 ');
define('TEXT_SELECT_PRIORITY_MAIL_DATE_NOTE', '請注意：我們通常在門票使用日期前7天內開始郵遞門票 ');
define('TXT_PRIORITY_MAIL_DELIVERY_ADDRESS', '郵遞地址');
define('TXT_PRIORITY_MAIL_DELIVERY_ADDRESS_NOTE', '提示：如果您使用美國酒店地址為郵遞門票的地址，請提供酒店的詳細資訊，包括酒店地址，聯繫電話以及房間號等，以便您的門票能夠及時投遞
所選日期為無效郵遞日期 ');
define('TXT_PRIORITY_MAIL_RECIPIENT_NAME', '收件人 ');
define('ERROR_CHECK_PRIORITY_MAIL_DATE', '所選日期為無效郵遞日期');

define('NEW_PAYMENT_METHOD_T4F_CREDIT', '虛擬帳戶');
define('TOUR_IDS_FOR_ATTR_THEME_PARK_NOTE', '');
define('TXT_PROVIDERS_DTE_BTL_IDS', '101,96');
define('HOTEL_PRICE_PER_DAYS_ATTR_NAME', '請選擇早餐類別'); //please donot modify

define('EMAIL_SEPARATOR', '-----------------------------------------------------------------------------------------------------------');

define('HEAD_TITLE_TAG_ALL', 'usitrip走四方旅遊網-美國華人旅行社_旅遊景點線路團價格報價_度假行程安排攻略_簽證移民留學遊學');
define('HEAD_DESC_TAG_ALL','USITRIP走四方旅遊網身為最知名華人旅行社,為全球華人量身定制去美國旅遊,加拿大旅遊,歐洲旅遊等出國旅遊景點行程攻略,旅行線路團購價格,旅遊簽證移民遊學,打折機票酒店預訂攻略,旅途美食住宿及購物攻略等服務');
define('HEAD_KEY_TAG_ALL','旅遊景點線路,旅遊行程攻略,簽證遊學留學,華人旅行社');

?>