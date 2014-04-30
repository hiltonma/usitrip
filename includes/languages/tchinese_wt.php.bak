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
define('DATE_FORMAT_LONG', '%Y年%m月%d日 %A'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

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
//define('CHARSET', 'big5');
define('CHARSET', 'gbr2312');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', '註冊賬號');
define('HEADER_TITLE_MY_ACCOUNT', '我的賬號');
define('HEADER_TITLE_CART_CONTENTS', '購物車');
define('HEADER_TITLE_CHECKOUT', '結帳');
define('HEADER_TITLE_CONTACT_US', '聯絡我們');
define('HEADER_TITLE_TOP', '首頁');
define('HEADER_TITLE_CATALOG', '商品目錄');
define('HEADER_TITLE_LOGOFF', '退出');
define('HEADER_TITLE_LOGIN', '登陸');
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
define('BOX_HEADING_BESTSELLERS_IN', '暢銷商品在<br>  ');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', '商品狀態通知');
define('BOX_NOTIFICATIONS_NOTIFY', '<b>%s</b><br>更新時通知我');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', '<b>%s</b><br>更新時不必通知我');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', '廠商的相關資訊');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', ' %s 的主頁');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', '廠商的其他商品');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', '語言');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', '貨幣');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', '服務台');


define('BOX_INFORMATION_SHIPPING', '退換貨事項');


// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', '推薦給親友');
define('BOX_TELL_A_FRIEND_TEXT', '推薦這個商品給親友');

// checkout procedure text
//define('CHECKOUT_BAR_CART_CONTENTS', '購物車內容');
//define('CHECKOUT_BAR_DELIVERY_ADDRESS', '出貨地址');
//define('CHECKOUT_BAR_PAYMENT_METHOD', '付款方式');
define('CHECKOUT_BAR_DELIVERY', '出貨信息');
define('CHECKOUT_BAR_PAYMENT', '支付資訊');
define('CHECKOUT_BAR_CONFIRMATION', '確認訂單');
define('CHECKOUT_BAR_FINISHED', '完成');

// pull down default text
define('PULL_DOWN_DEFAULT', '請選擇');
define('TYPE_BELOW', '在下面輸入');

// javascript messages
define('JS_ERROR', '在提交表格過程中出現錯誤.\n\n請做下述改正:\n\n');

define('JS_REVIEW_TEXT', '* \'評論內容\' 必須至少包含 ' . REVIEW_TEXT_MIN_LENGTH . ' 個字元.\n');
define('JS_REVIEW_RATING', '* 您必須為您做了評論的團評等級.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* 請為您的訂單選擇一個支付方式.\n');

define('JS_ERROR_SUBMITTED', '這個表單已經送出，請按 Ok 後等待處理');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', '您必須選一個付款方式.');

define('CATEGORY_COMPANY', '公司資料');
define('CATEGORY_PERSONAL', '個人資料');
define('CATEGORY_ADDRESS', '地址');
define('CATEGORY_CONTACT', '您的聯繫資訊');
define('CATEGORY_OPTIONS', '選項');
define('CATEGORY_PASSWORD', '密碼');

define('ENTRY_COMPANY', '公司名稱:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', '性別:');
define('ENTRY_GENDER_ERROR', '請選擇性別');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', '中文姓名:');
define('ENTRY_FIRST_NAME_ERROR', ' <small><font color="#FF0000">'.ENTRY_FIRST_NAME.'少於 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', '護照英文名:');
define('ENTRY_LAST_NAME_ERROR', ' <small><font color="#FF0000">'.ENTRY_LAST_NAME.'少於 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', '生日:');
define('ENTRY_DATE_OF_BIRTH_ERROR', ' <small><font color="#FF0000">(例：05/21/1970)</font></small>');
define('ENTRY_DATE_OF_BIRTH_TEXT', ' <small>(例：05/21/1970) <font color="#AABBDD">必填欄位</font></small>');
define('ENTRY_EMAIL_ADDRESS', '電子郵箱:');
define('ENTRY_EMAIL_ADDRESS_ERROR', ' <small><font color="#FF0000">'.ENTRY_EMAIL_ADDRESS.'少於 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', ' <small><font color="#FF0000">電子郵件位址格式錯誤!</font></small>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', ' <small><font color="#FF0000">這個電子郵件已經註冊過!請確認或換一個電子郵件</font></small>');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', '詳細地址:');
define('ENTRY_STREET_ADDRESS_ERROR', ' <small><font color="#FF0000">'.ENTRY_STREET_ADDRESS.'少於 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', '街道:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', '郵政編碼:');
define('ENTRY_POST_CODE_ERROR', ' <small><font color="#FF0000">'.ENTRY_POST_CODE.'少於 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', '城市:');
define('ENTRY_CITY_ERROR', ' <small><font color="#FF0000">'.ENTRY_CITY.'少於 ' . ENTRY_CITY_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', '州/省:');
define('ENTRY_STATE_ERROR', '州/省最少必須 ' . ENTRY_STATE_MIN_LENGTH . '個字');
define('ENTRY_STATE_ERROR_SELECT', '請從下拉式選單中選取');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', '國家/地區:');
define('ENTRY_COUNTRY_ERROR', '請從下拉式選單中選取');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', '電話號碼:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '電話號碼不得少於 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 個字</font></small>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', '移動電話:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', '訂閱走四方資訊郵件:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', '-訂閱-');
define('ENTRY_NEWSLETTER_NO', '取消');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', '密碼:');
define('ENTRY_PASSWORD_ERROR', '密碼不得少於' . ENTRY_PASSWORD_MIN_LENGTH . ' 個字');
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
define('TEXT_RESULT_PAGE', '總頁數:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '顯示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>個商品)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>筆訂單)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>條記錄)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>個新商品)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '顯示 <b>%d</b> 到 第 <b>%d</b> (共 <b>%d</b> 項特價)');


define('PREVNEXT_TITLE_FIRST_PAGE', '第一頁');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', '前一頁');
define('PREVNEXT_TITLE_NEXT_PAGE', '下一頁');
define('PREVNEXT_TITLE_LAST_PAGE', '最後一頁');
define('PREVNEXT_TITLE_PAGE_NO', '第%d頁');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', '前 %d 頁');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', '後 %d 頁');
define('PREVNEXT_BUTTON_FIRST', '<<最前面');
define('PREVNEXT_BUTTON_PREV', '[<< 往前]');
define('PREVNEXT_BUTTON_NEXT', '[往後 >>]');
define('PREVNEXT_BUTTON_LAST', '最後面>>');

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
define('TEXT_NO_REVIEWS', '目前沒有任何商品評論.');

define('TEXT_NO_NEW_PRODUCTS', '目前沒有新進商品.');

define('TEXT_UNKNOWN_TAX_RATE', '不明的稅率');

define('TEXT_REQUIRED', '<span class="errorText">必填</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> 無法由指定的 SMTP 主機傳送郵件，請檢查 php.ini 設定</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', '警告： 安裝目錄仍然存在： ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. 基於安全的理由，請將這個目錄刪除');
define('WARNING_CONFIG_FILE_WRITEABLE', '警告： 設定檔允許被寫入： ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. 這將具有潛在的系統安全風險 - 請將檔案設定為正確的使用權限');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', '警告： sessions 資料夾不存在： ' . tep_session_save_path() . '. 在這個目錄未建立之前 Sessions 無法正常動作');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', '警告： 無法寫入sessions 資料夾： ' . tep_session_save_path() . '. 在使用者許可權未正確設定之前 Sessions 將無法正常動作');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', '警告： 下載的商品目錄不存在： ' . DIR_FS_DOWNLOAD . '. 在這個目錄未建立之前，無法下載商品');
define('WARNING_SESSION_AUTO_START', '警告： session.auto_start 已啟動 - 請到 php.ini 內關閉這個功能，並重新啟動網頁主機');
define('TEXT_CCVAL_ERROR_INVALID_DATE', '輸入的信用卡到期日無效<br>請檢查日期後再試');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', '信用卡卡號無效<br>請檢查後再試');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', '您輸入的前四碼是: %s<br>如果正確，我們目前尚無法接受此類信用卡<br>如果錯誤請重試');
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
  define('BOX_INFORMATION_CONTACT', '聯繫我們');
    //amit added new for language start
	define('FOOTER_TEXT_BODY', '版權 &copy;2005-'.date('Y').' usitrip.com, 擁有最終解釋權. <br>網站內價格和產品行程有可能會有更改變動，不做另行通知. <br>usitrip.com不對印刷錯誤引起的不便負任何責任. 所有的印刷錯誤我們都會作改動.');

  define('BOX_INFORMATION_PRIVACY_AND_POLICY', '隱私條例');
  define('BOX_INFORMATION_PAYMENT_FAQ','付款常見問題');
  define('BOX_INFORMATION_COPY_RIGHT','版權');
  define('BOX_INFORMATION_CUSTOMER_AGREEMENT','客戶協定');
  define('BOX_INFORMATION_LINK_TO_US','友情鏈結');
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
define('NAV_ORDER_INFO', '訂單資訊');

/*End Checkout WIthout Account images*/
define('ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE', '國家代碼 ');
define('ENTRY_CELLPHONE_NUMBER',"（請提供必要時的以備緊急聯繫之用的號碼）：");
define('ENTRY_CELLPHONE_NUMBER_TEXT', '');


define('BOX_INFORMATION_GV', '關於禮券的常見問題解答');
define('VOUCHER_BALANCE', '禮券餘額');
define('BOX_HEADING_GIFT_VOUCHER', '禮券帳戶'); 
define('GV_FAQ', '關於禮券的常見問題解答');
define('ERROR_REDEEMED_AMOUNT', '恭喜您，您的兌換成功了');
define('ERROR_NO_REDEEM_CODE', '您還沒有輸入兌換號碼.');  
define('ERROR_NO_INVALID_REDEEM_GV', '無效的禮券代碼'); 
define('TABLE_HEADING_CREDIT', '有效卡');
define('GV_HAS_VOUCHERA', '您的禮券帳戶上仍有餘額，如果您願意<br>
                         您可以將它們寄送出去通過<a class="pageResults" href="');       
define('GV_HAS_VOUCHERB', '"><b>以電子郵件寄給</b>給其他人'); 
define('ENTRY_AMOUNT_CHECK_ERROR', '您沒有足夠的禮券寄送這個數目.'); 
define('BOX_SEND_TO_FRIEND', '寄送禮券');
define('VOUCHER_REDEEMED', '禮券已經兌換');
define('CART_COUPON', '禮券 :');
define('CART_COUPON_INFO', '更多資訊');
//amit added new for language end
//define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '顯示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>個商品)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', '顯示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)');  // featured tours
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', '顯示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // referrals
define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS', '顯示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // questions

//added for product listing page start
define('TEXT_WELCOME_TO','歡迎來到');
define('TEXT_CUSTOMER_AGREE_BOOK','請在網上預訂之前閱讀我們的客戶協議。');
define('TEXT_TOUR_PICKUP_NOTE','一個<FONT COLOR="#0000ff">打包旅遊</FONT> 通常包括機場的接送服務.');
define('TEXT_SORT_BY','排序方式：');
define('TEXT_TELL_YOUR_FRIEND','告訴您的朋友');
define('TEXT_ABOUT',' 關於 ');
define('TEXT_AND_MAKE','並且取得');
define('TEXT_COMMISSION','傭金');
define('TEXT_TOUR_ITINERARY','旅遊路線：');
define('TEXT_DEPART_FROM','出發地點：');
define('TEXT_OPERATE','出團日期：');
define('TEXT_PRICE','價格：');
define('TEXT_HIGHLIGHTS','主要景點：');
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


define('TEXT_NO_QUESTION_FOUND','沒有找到相關資訊。');
define('TEXT_SEARCH_FOR_YOUR_TOUR','搜索旅遊景點');

define('TEXT_TITLE_TOURS_DEALS','推薦旅遊');

//JAMES ADD FOR OTHERS TEXT
define('TEXT_NORMAL_TELL_FRIEND', '告訴您的朋友');
define('TEXT_NORMAL_ABOUT', '關於');
define('TEXT_NORMAL_GAIN', '並且取得');
define('TEXT_NORMAL_COMISSION', '的傭金!');

//JAMES ADD FOR PRODUCT DURATION OPTIONS
define('TEXT_DURATION_OPTION_1','選擇持續天數');
define('TEXT_DURATION_OPTION_2','1 天');
define('TEXT_DURATION_OPTION_3','2 天');
define('TEXT_DURATION_OPTION_4','2 到 3 天');
define('TEXT_DURATION_OPTION_5','3 天');
define('TEXT_DURATION_OPTION_6','3 到 4 天');
define('TEXT_DURATION_OPTION_7','4 天');
define('TEXT_DURATION_OPTION_8','4 天或更多天數');
define('TEXT_DURATION_OPTION_9','5 天或更多天數');

define('TEXT_ATTRACTION_OPTION_1','選擇景點');

define('TEXT_SORT_OPTION_1','--選擇排序方式--');
define('TEXT_SORT_OPTION_2','旅遊價格');
define('TEXT_SORT_OPTION_3','持續天數');
define('TEXT_SORT_OPTION_4','景點名稱');

define('TEXT_POPULAR_TOURS','暢銷景點');
?>
