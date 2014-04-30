<?php
/*
  $Id: english.php,v 1.3 2004/03/15 12:13:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'en_US.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
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
define('LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="zh-cn"');

// charset for web pages and emails
define('CHARSET', 'gb2312');

// page title
define('TITLE', 'usitrip');

// domain name on email subject
define('STORE_OWNER_DOMAIN_NAME','usitrip.com');
define('ORDER_EMAIL_PRIFIX_NAME','');

define('TEXT_ALTER_TAG','');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');
define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('PROVIDER_MENU_ACCOUNT', '更改账户密码');
define('PROVIDER_MENU_USERS', '添加或删除订单处理者');
define('PROVIDER_MENU_ORDERS', '所有订单');
define('PROVIDER_MENU_LOGOFF', '登出');
define('TEXT_RESULT_PAGE_PROVIDERS', 'Page %s of %d');
define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');

define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_PASSWORD', 'Password:');
define('IMAGE_BUTTON_LOGIN', 'Sign In');

define('TEXT_BACK', 'Back');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');

define('ENTRY_NAME', 'Name:');
define('PROVIDER_MENU_AGENCY', '本公司信息');
define('MAX_PROVIDERS_USERS_LIMIT', 5);
define('MSG_RECORD_NOT_FOUND', '无记录');
define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE','Total Fares (3% transaction fee included)');
define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT','Total Fares (transaction fee included)');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');
define('PROVIDER_MENU_SOLDOUT_DATES', '我们的行程');

define('TEXT_DEFULAT_TOUR_ARRANMGENT','<TR>
<TD><STRONG>Date<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">日期</SPAN></STRONG></TD>
<TD><STRONG>Pick-up Time<BR><SPAN lang=ZH-CN style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: SimSun; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">上车</SPAN><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">时间</SPAN></STRONG></TD>
<TD><STRONG>Itinerary<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">线路</SPAN></STRONG></TD>
<TD><STRONG>Hotel<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">酒店</SPAN></STRONG></TD>
<TD><STRONG>Note<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\"><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">备注</SPAN></SPAN></STRONG></TD></TR>
');

define('TEXT_DEFULAT_TOUR_ARRANMGENT_NEW','<TR>
<TD><STRONG>Date and Pickup Details<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">出发时间和地点信息</SPAN></STRONG></TD>
<TD><STRONG>Local Tour Contact<BR><SPAN lang=ZH-CN style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: SimSun; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">当地地接巴士公司</SPAN></STRONG></TD>
<TD><STRONG>Itinerary<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">线路</SPAN></STRONG></TD>
<TD><STRONG>Hotel<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">酒店</SPAN></STRONG></TD>
<TD><STRONG>Note<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\"><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">备注</SPAN></SPAN></STRONG></TD></TR>
');
define('STORE_OWNER_DOMAIN_NAME','usitrip.com');
define('DATE_FORMAT_WITH_DAY', 'm/d/Y l'); // this is used for date()
define('TXT_GUEST_HOTEL_INFORMATION', 'Guest Hotel Info:');
define('CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID','96'); // donot modify without permission
?>