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
// 设定本地时间
// RedHat 'zh_TW'
// FreeBSD 'zh_TW.Big5'
// Windows ''引号内空白即可
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
define('LANGUAGE_CURRENCY', 'CNY');//预设值

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="zh"');

// charset for web pages and emails
define('CHARSET', 'gb2312');
//define('CHARSET', 'gb2312');

// page title
define('TITLE', STORE_NAME);

// domain name on email subject
define('STORE_OWNER_DOMAIN_NAME','usitrip.com');
define('ORDER_EMAIL_PRIFIX_NAME','');
// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', '注册账号');
define('HEADER_TITLE_MY_ACCOUNT', '我的账号');
define('HEADER_TITLE_CART_CONTENTS', '购物车');
define('HEADER_TITLE_CHECKOUT', '结帐');
define('HEADER_TITLE_CONTACT_US', '联络我们');
define('HEADER_TITLE_TOP', '首&nbsp;页');
define('HEADER_TITLE_CATALOG', '商品目录');
define('HEADER_TITLE_LOGOFF', '退出');
//define('HEADER_TITLE_LOGOFF', '退出账号');
define('HEADER_TITLE_LOGIN', '登录');
define('HEADER_TITLE_ADMINISTRATION', '系统管理');

// box text in includes/boxes/administrators.php
define('BOX_HEADING_ADMINISTRATORS', '系统管理员');
define('BOX_ADMINISTRATORS_SETUP', '设定');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', '次浏览,自从');

// text for gender
define('MALE', '男');
define('FEMALE', '女');
define('MALE_ADDRESS', '先生');
define('FEMALE_ADDRESS', '小姐');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', '景点目录');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', '制造厂商');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', '新上架商品');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', '快速寻找商品');
define('BOX_SEARCH_TEXT', '输入关键字寻找商品');
define('BOX_SEARCH_ADVANCED_SEARCH', '进阶寻找商品');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', '特价商品');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', '商品评论');
define('BOX_REVIEWS_WRITE_REVIEW', '请写下您对这个商品的评论!');
define('BOX_REVIEWS_NO_REVIEWS', '目前没有任何商品评论');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '等级 %s 星级');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', '购物车');
define('BOX_SHOPPING_CART_EMPTY', '购物车为空');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', '购物纪录');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', '畅销商品');
define('BOX_HEADING_BESTSELLERS_IN', '畅销商品在<br>  ');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', '商品状态通知');
define('BOX_NOTIFICATIONS_NOTIFY', '<b>%s</b><br>更新时通知我');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', '<b>%s</b><br>更新时不必通知我');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', '厂商的相关信息');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', ' %s 的主页');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', '厂商的其他商品');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', '语言');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', '货币');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', '服务台');


define('BOX_INFORMATION_SHIPPING', '退换货事项');


// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', '推荐给亲友');
define('BOX_TELL_A_FRIEND_TEXT', '推荐这个商品给亲友');

// checkout procedure text
//define('CHECKOUT_BAR_CART_CONTENTS', '购物车内容');
//define('CHECKOUT_BAR_DELIVERY_ADDRESS', '出货地址');
//define('CHECKOUT_BAR_PAYMENT_METHOD', '付款方式');
define('CHECKOUT_BAR_DELIVERY', '出货信息');
define('CHECKOUT_BAR_PAYMENT', '支付信息');
define('CHECKOUT_BAR_CONFIRMATION', '确认订单');
define('CHECKOUT_BAR_FINISHED', '完成');

// pull down default text
define('PULL_DOWN_DEFAULT', '请选择');
define('TYPE_BELOW', '在下面输入');

// javascript messages
define('JS_ERROR', '在提交表格过程中出现错误.\n\n请做下述改正:\n\n');

define('JS_REVIEW_TEXT', '* \'评论内容\' 必须至少包含 ' . REVIEW_TEXT_MIN_LENGTH . ' 个字符.\n');
define('JS_REVIEW_RATING', '* 您必须为您做了评论的团评等级.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* 请为您的订单选择一个支付方式.\n');

define('JS_ERROR_SUBMITTED', '这个表单已经送出，请按 Ok 后等待处理');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', '您必须选一个付款方式.');

define('CATEGORY_COMPANY', '公司资料');
define('CATEGORY_PERSONAL', '个人资料');
define('CATEGORY_ADDRESS', '地址');
define('CATEGORY_CONTACT', '您的联系信息');
define('CATEGORY_OPTIONS', '选项');
define('CATEGORY_PASSWORD', '密码');

define('ENTRY_COMPANY', '公司名称:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', '性别:');
define('ENTRY_GENDER_ERROR', '请选择性别');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', '中文姓名:');
define('ENTRY_FIRST_NAME_ERROR', '请确认姓名与您的有效证件上的姓名一致，且不少于' . ENTRY_FIRST_NAME_MIN_LENGTH . '个字');
define('ENTRY_FIRST_NAME_ERROR_ONLYCHINA', ENTRY_FIRST_NAME.'只能输入中文');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', '护照英文名:');
define('ENTRY_LAST_NAME_ERROR', ENTRY_LAST_NAME.'少于 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 个字');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', '生日:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '(例∶05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', ' (例∶05/21/1970)必填栏位');
define('ENTRY_EMAIL_ADDRESS', '电子邮箱:');
define('ENTRY_CONFIRM_EMAIL_ADDRESS', '邮箱确认:');
define('ENTRY_CONFIRM_EMAIL_ADDRESS_CHECK_ERROR', '邮箱确认必须和电子邮箱匹配');
define('ENTRY_EMAIL_ADDRESS_ERROR', ENTRY_EMAIL_ADDRESS.'少于 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 个字');
define('ENTRY_EMAIL_ADDRESS_NOTE_DEFAULT', '请输入您常用的电子邮箱地址');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '电子邮箱地址格式错误');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '这个电子邮箱已经注册过!请确认或换一个电子邮箱');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', '详细地址:');
define('ENTRY_STREET_ADDRESS_ERROR', ENTRY_STREET_ADDRESS.'少于 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 个字');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', '街道:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', '邮政编码:');
define('ENTRY_POST_CODE_ERROR', ' 邮政编码 少于 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 个字');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', '城市:');
define('ENTRY_CITY_ERROR', ENTRY_CITY.' 少于 ' . ENTRY_CITY_MIN_LENGTH . ' 个字');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', '州/省:');
define('ENTRY_STATE_ERROR', '州/省最少必须 ' . ENTRY_STATE_MIN_LENGTH . '个字');
define('ENTRY_STATE_ERROR_SELECT', '请从下拉式选单中选取');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', '国家/地区:');
define('ENTRY_COUNTRY_ERROR', '请从下拉式选单中选取');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', '联系电话:');
define('ENTRY_TELEPHONE_NUMBER_ON_CREATE_ACCOUNT', '电话号码:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '电话号码不得少于 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 个字');
define('ENTRY_TELEPHONE_NUMBER_ERROR_1', ENTRY_TELEPHONE_NUMBER_ON_CREATE_ACCOUNT.'必须全部都是数字');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '');
define('ENTRY_FAX_NUMBER', '其他电话:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_MOBILE_PHONE','移动电话:');
define('ENTRY_MOBILE_PHONE_ERROR','');
define('ENTRY_MOBILE_PHONE_TEXT','*');

define('ENTRY_NEWSLETTER', '订阅走四方资讯邮件:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', '-订阅-');
define('ENTRY_NEWSLETTER_NO', '取消');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', '密码:');
define('ENTRY_PASSWORD_ERROR', '密码需要在' . ENTRY_PASSWORD_MIN_LENGTH . ' 位以上');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', '密码不符');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', '确认密码:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', '当前密码:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', '密码不得少于 ' . ENTRY_PASSWORD_MIN_LENGTH . ' 个字');
define('ENTRY_PASSWORD_NEW', '新密码:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', '新密码不得少于' . ENTRY_PASSWORD_MIN_LENGTH . ' 个字');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', '密码不符');
define('PASSWORD_HIDDEN', '--隐藏--');
define('FORM_REQUIRED_INFORMATION', '* 表示该栏位必须填写');

// constants for use in tep_prev_next_display function
//define('TEXT_RESULT_PAGE', '总页数:');
define('TEXT_RESULT_PAGE', '');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '<span style="display:none">显示 <b>%d</b> 到 <b>%d</b> </span>共%d个行程');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '显示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>笔订单)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '显示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>条记录)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', '显示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>个新行程)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '显示 <b>%d</b> 到 第 <b>%d</b> (共 <b>%d</b> 项特价)');


define('PREVNEXT_TITLE_FIRST_PAGE', '第一页');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', '前一页');
define('PREVNEXT_TITLE_NEXT_PAGE', '下一页');
define('PREVNEXT_TITLE_LAST_PAGE', '最后一页');
define('PREVNEXT_TITLE_PAGE_NO', '第%d页');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', '前 %d 页');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', '后 %d 页');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;第一页');
define('PREVNEXT_BUTTON_PREV', '&lt;&lt; 上一页');
define('PREVNEXT_BUTTON_FIRST_SUB', '第一页');
define('PREVNEXT_BUTTON_PREV_SUB', '上一页');
define('PREVNEXT_BUTTON_NEXT', '下一页 &gt;&gt;');
define('PREVNEXT_BUTTON_LAST', '末页&gt;&gt;');
define('PREVNEXT_BUTTON_NEXT_SUB', '下一页');
define('PREVNEXT_BUTTON_LAST_SUB', '末页');

define('IMAGE_BUTTON_ADD_ADDRESS', '新增地址');
define('IMAGE_BUTTON_ADDRESS_BOOK', '通讯录');
define('IMAGE_BUTTON_BACK', '回上页');
define('IMAGE_BUTTON_BUY_NOW', '马上买');
define('IMAGE_BUTTON_CHANGE_ADDRESS', '变更地址');
define('IMAGE_BUTTON_CHECKOUT', '结帐');
define('IMAGE_BUTTON_CONFIRM_ORDER', '确认订单');
define('IMAGE_BUTTON_CONTINUE', '继续');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', '继续购物');
define('IMAGE_BUTTON_DELETE', '删除');
define('IMAGE_BUTTON_EDIT_ACCOUNT', '编辑账号');
define('IMAGE_BUTTON_HISTORY', '订单记录');
define('IMAGE_BUTTON_LOGIN', '登录');
define('IMAGE_BUTTON_IN_CART', '放到购物车');
define('IMAGE_BUTTON_NOTIFICATIONS', '通知');
define('IMAGE_BUTTON_QUICK_FIND', '快速寻找');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', '移除商品通知');
define('IMAGE_BUTTON_REVIEWS', '评价');
define('IMAGE_BUTTON_SEARCH', '搜寻');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', '出货选项');
define('IMAGE_BUTTON_TELL_A_FRIEND', '推荐给亲友');
define('IMAGE_BUTTON_UPDATE', '更新');
define('IMAGE_BUTTON_UPDATE_CART', '更新购物车');
define('IMAGE_BUTTON_WRITE_REVIEW', '写写商品评论');

define('SMALL_IMAGE_BUTTON_DELETE', '删除');
define('SMALL_IMAGE_BUTTON_EDIT', '编辑');
define('SMALL_IMAGE_BUTTON_VIEW', '检视');

define('ICON_ARROW_RIGHT', '更多');
define('ICON_CART', '放到购物车');
define('ICON_ERROR', '错误');
define('ICON_SUCCESS', '完成');
define('ICON_WARNING', '注意');

define('TEXT_GREETING_PERSONAL', '<span class="greetUser">%s</span> 您好，欢迎光临！ 想看看有什麽<a href="%s"><u>新进商品</u></a>？');
define('TEXT_GREETING_PERSONAL_RELOGON', '如果您不是 %s, 请用自己的账号<a href="%s"><u>登录</u></a>');
define('TEXT_GREETING_GUEST', '<span class="greetUser">访客</span>，欢迎光临，如果您已经是会员请直接<a href="%s"><u>登录</u></a>？ 或是<a href="%s"><u>注册为会员</u></a>？');

define('TEXT_SORT_PRODUCTS', '商品排序方式∶');
define('TEXT_DESCENDINGLY', '递减，');
define('TEXT_ASCENDINGLY', '递增，');
define('TEXT_BY', '排序键∶');

define('TEXT_REVIEW_BY', '%s 所评论写道∶');
define('TEXT_REVIEW_WORD_COUNT', '%s   字');
define('TEXT_REVIEW_RATING', '评等: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', '评论日期: %s');
define('TEXT_NO_REVIEWS', '无相关信息。');

define('TEXT_NO_NEW_PRODUCTS', '目前没有新进商品.');

define('TEXT_UNKNOWN_TAX_RATE', '不明的税率');

define('TEXT_REQUIRED', '<span class="errorText">必填</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> 无法由指定的 SMTP 主机传送邮件，请检查 php.ini 设定</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', '警告∶ 安装目录仍然存在∶ ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. 基于安全的理由，请将这个目录删除');
define('WARNING_CONFIG_FILE_WRITEABLE', '警告∶ 设定档允许被写入∶ ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. 这将具有潜在的系统安全风险 - 请将档案设定为正确的使用权限');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', '警告∶ sessions 资料夹不存在∶ ' . tep_session_save_path() . '. 在这个目录未建立之前 Sessions 无法正常动作');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', '警告∶ 无法写入sessions 资料夹∶ ' . tep_session_save_path() . '. 在使用者权限未正确设定之前 Sessions 将无法正常动作');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', '警告∶ 下载的商品目录不存在∶ ' . DIR_FS_DOWNLOAD . '. 在这个目录未建立之前，无法下载商品');
define('WARNING_SESSION_AUTO_START', '警告∶ session.auto_start 已启动 - 请到 php.ini 内关闭这个功能，并重新启动网页主机');
define('TEXT_CCVAL_ERROR_INVALID_DATE', '输入的信用卡到期日无效<br>请检查日期后再试');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', '信用卡卡号无效<br>请检查后再试');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', '您输入的前四码是: %s<br>如果正确，我们目前尚无法接受此类信用卡<br>如果错误请重试');
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




define('FREE_TEXT', '<img src="' . DIR_WS_IMAGES . 'table_background_payment.gif">免费!</img>');

define('CALL_TEXT', '<font color=red>价格请来电洽询</font>');
define('CALL_LINK_ON','1');
define('CALL_LINK_TEXT','按这里与我们联络');
define('CALL_LINK_OFF_TEXT','<font color=blue>洽询电话请拨: xxxx-xxx-xxx</font>');
define('CALL_INCART_LINK', '<B><A HREF="' . DIR_WS_CATALOG . 'contact_us.php">' . CALL_LINK_TEXT . '</A></B>    ');

define('SOON_TEXT', '<font color=red>即将上市...</font>');
define('SOON_LINK_ON','0');
define('SOON_LINK_TEXT','按这里与我们联络');
define('SOON_LINK_OFF_TEXT','<font color=blue>洽询电话请拨: xxxx-xxx-xxx</font>');
define('SOON_INCART_LINK', '<B><A HREF="' . DIR_WS_CATALOG . 'contact_us.php">' . SOON_LINK_TEXT . '</A></B>    ');

require(DIR_FS_LANGUAGES . $language . '/' . 'banner_manager.php');


define('BOX_INFORMATION_ABOUT_US','关于我们');
  define('BOX_INFORMATION_CONDITIONS', '使用条款');
  define('BOX_INFORMATION_SITE_MAP', '网站地图');
  define('BOX_INFORMATION_CONTACT', '联系我们');
define('BOX_INFORMATION_DOWNLOAD_ACKNOWLEDGEMENT_CARD_BILLING','信用卡支付验证书');
    //amit added new for language start
	define('FOOTER_TEXT_BODY', '<a onmouseout="document.getElementById(\'tip\').style.display=\'none\'" onmouseover="document.getElementById(\'tip\').style.display=\'\'" class="tip" href="javascript:void(0);">CST# 2096846-40</a> 版权 <span style="font-family:Arial" >&copy;</span>2006-'.date('Y').' usitrip.com, 拥有最终解释权。<br />
	网站内价格和产品行程有可能会有更改变动，不做另行通知。<br />走四方网usitrip.com不对文字错误引起的不便负任何责任，文字错误都会及时更正。蜀ICP备10200285号');

  define('BOTTOM_CST_NOTE_MSN','美国加利福尼亚州要求旅行团销售方到州检察院注册，并在其所有广告上展示注册号。有效的注册号表明此旅行团销售方是依照法律注册的。');

  define('BOX_INFORMATION_PRIVACY_AND_POLICY', '隐私条例');
  define('BOX_INFORMATION_PAYMENT_FAQ','付款常见问题');
  define('BOX_INFORMATION_COPY_RIGHT','版权');
  define('BOX_INFORMATION_CUSTOMER_AGREEMENT','客户协议');
  define('BOX_INFORMATION_LINK_TO_US','友情链接');
  define('BOX_INFORMATION_CANCELLATION_REFUND_POLICY','取消和退款条例');
  define('BOX_INFORMATION_VIEW_ALL_TOURS','查看所有旅游');


  /*advance search*/
define('DURATION', '持续时间∶');
define('DEPARTURE_CITY', '选择出发城市∶');
define('TEXT_NONE', '-- 选择出发城市 --');
define('OPTIONAL_KEYWORD', '输入搜索关键字∶');
define('START_DATE', '出发日期∶');
define('IGNORE','忽略');

define('HEADING_SHIPPING_INFORMATION', '电子参团凭证（ETicket）寄送');

define('HEADING_ATTRACTION', '景点∶');
/*Begin Checkout Without Account images*/
define('IMAGE_BUTTON_CREATE_ACCOUNT', '建立帐户');
define('NAV_ORDER_INFO', '订单信息');

/*End Checkout WIthout Account images*/
define('ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE', '国家代码 ');
define('ENTRY_CELLPHONE_NUMBER',"手机号码:");
define('ENTRY_CELLPHONE_NUMBER_TEXT', '');


define('BOX_INFORMATION_GV', '关于礼券的常见问题解答');
define('VOUCHER_BALANCE', '礼券馀额');
define('BOX_HEADING_GIFT_VOUCHER', '礼券帐户');
define('GV_FAQ', '关于礼券的常见问题解答');
define('ERROR_REDEEMED_AMOUNT', '恭喜您，您的兑换成功了');
define('ERROR_NO_REDEEM_CODE', '您还没有输入兑换号码.');
define('ERROR_NO_INVALID_REDEEM_GV', '无效的礼券代码');
define('TABLE_HEADING_CREDIT', '折扣券');
define('GV_HAS_VOUCHERA', '您的礼券帐户上仍有馀额，如果您愿意<br>
                         您可以将它们寄送出去通过<a class="pageResults" href="');
define('GV_HAS_VOUCHERB', '"><b>以电子邮件寄给</b>给其他人');
define('ENTRY_AMOUNT_CHECK_ERROR', '您没有足够的礼券寄送这个数目.');
define('BOX_SEND_TO_FRIEND', '寄送礼券');
define('VOUCHER_REDEEMED', '礼券已经兑换');
define('CART_COUPON', '礼券 :');
define('CART_COUPON_INFO', '更多信息');
//amit added new for language end
//define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '显示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>个商品)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', '显示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)');  // featured tours
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', '显示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // referrals
define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS', '显示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // questions

//added for product listing page start
define('TEXT_WELCOME_TO','欢迎来到');
define('TEXT_CUSTOMER_AGREE_BOOK','请在网上预订之前阅读我们的客户协议。');
define('TEXT_TOUR_PICKUP_NOTE','一个<FONT COLOR="#0000ff">旅游套&#39184;</FONT> 通常包括机场的接送服务.');
define('TEXT_SORT_BY','排序方式∶');
define('TEXT_TELL_YOUR_FRIEND','告诉您的朋友');
define('TEXT_ABOUT',' 关于 ');
define('TEXT_AND_MAKE','并且取得');
define('TEXT_COMMISSION','佣金');
define('TEXT_TOUR_ITINERARY','旅游路线∶');
define('TEXT_DEPART_FROM','出发城市∶');//出发地点
define('TEXT_OPERATE','出团时间∶');    //出团日期
define('TEXT_PRICE','价格∶');
define('TEXT_HIGHLIGHTS','行程特色∶');//主要景点
define('TEXT_DURATION','持续时间∶');
define('TEXT_DETAILS','查看详情');
//added for product listing page end
//why book form us text

define('TEXT_TOP_HEADING_BOOK','我们的优势∶');

define('TAB_SPECIALLY_DESIGN_TOURS','精心设计的旅游团');
define('TAB_LOW_PRICE_GUANRANTEED','低价保证');
define('TAB_EXPERIENCED_DRIVER','经验丰富的司机');
define('TAB_PROFESSIONAL_TOUR_DUIDE','专业的导游');
define('TAB_EXCELLETN_CUSTOMER_SERVICES','优质的客户服务');

define('TEXT_PARA_SPECIALLY_DESIGN_TOURS','我们只为客户提供精心打造的旅行。我们推出的旅行团，比如深受大家喜爱的黄石公园和总统山之旅，以及举世闻名的大峡谷和尼亚加拉大瀑布之旅每年都会吸引成千上万的游客。很荣幸我们能帮您造就一段一生难忘的美好回忆。最重要的是，我们高质低价的旅行团只在这里提供，别无寻处。');
define('TEXT_PARA_LOW_PRICE_GUANRANTEED','作为网上一流的旅游供货商，也是网上最大的直属代理商，我们提供最优质的服务和最佳的旅游团。根据我们的规模和服务，我们向您保证，您将会以最优惠的价格享受最满意的旅行。如果您选择自费游的话，所花的价钱可能会是我们的3-4倍，只有走四方网既能为您省下最多的钱，又能让您充分感受旅行的乐趣。');
define('TEXT_PARA_EXPERIENCED_DRIVER','有着多年策划假期旅游和仅今年就组团上千的经验，我们不仅能让您体验到旅游的乐趣，还能让您的旅途舒服自在。不象其它不善于组织策划的旅游公司，我们深知在一辆空间狭小的吉普车或汽车里度过3-7天是多么难受的事。因此，我们时间较长的团都会升级使用空间宽敞又舒适的豪华汽车。您不仅能轻松的在您的躺椅上随意休息，还能享受到根据气候调节的空调，独享的阅读灯，超大容量的储物箱，VCD/DVD播放器和干净的休息室。乘坐我们的旅游车旅行，您能感受到家一般的舒适。最重要的是，我们的司机都有着多年的经验，他们对这些旅游胜地的线路非常熟悉，而且他们本身还就是最棒的导游。');
define('TEXT_PARA_PROFESSIONAL_TOUR_DUIDE','如果导游对景点的认知比您还少，那将是一件最糟糕的事。所以我们只挑选最博学风趣的专业导游。您不会再为导游因为不熟悉环境而错过一个重要的景点，或是无缘听闻当地的传说故事。我们专业的导游会为您讲诉当地历史，讲一些平凡小事让您开怀，还会用当地的趣闻乐事逗您笑个不停。在我们的旅游团里，您永远不会感到无聊。');
define('TEXT_PARA_EXCELLETN_CUSTOMER_SERVICES','走四方网在提供欢乐旅程的同时，我们始终紧记，您-我们的顾客，才是每个旅游团中最重要的元素。我们专业的客服代表会努力工作以确保您-我们尊贵的客人，在我们无微不至的服务下，由始至终都玩得尽兴，玩得开心，不会留下一点遗憾。我们的宗旨就是让游客百分之百的满意。');


define('TEXT_NO_QUESTION_FOUND','没有找到相关信息。');
define('TEXT_SEARCH_FOR_YOUR_TOUR','搜索旅游景点');

define('TEXT_TITLE_TOURS_DEALS','推荐旅游');

//JAMES ADD FOR OTHERS TEXT
define('TEXT_NORMAL_TELL_FRIEND', '告诉您的朋友');
define('TEXT_NORMAL_ABOUT', '关于');
/* amit commneted old
define('TEXT_NORMAL_GAIN', '并且取得');
define('TEXT_NORMAL_COMISSION', '的佣金!');
*/
define('TEXT_NORMAL_GAIN', '就有机会获得');
define('TEXT_NORMAL_COMISSION', '的佣金!');

//JAMES ADD FOR PRODUCT DURATION OPTIONS
define('TEXT_DURATION_OPTION_1','选择持续天数');
define('TEXT_DURATION_OPTION_2','1天');
define('TEXT_DURATION_OPTION_3','2-3天');
define('TEXT_DURATION_OPTION_4','4-5天');
define('TEXT_DURATION_OPTION_5','6天以上');

define('TEXT_DURATION_OPTION_6','3 到 4 天');
define('TEXT_DURATION_OPTION_7','4 天');
define('TEXT_DURATION_OPTION_8','4 天或更多天数');
define('TEXT_DURATION_OPTION_9','5 天或更多天数');

define('TEXT_DURATION_HOURS','小时');

define('TEXT_ATTRACTION_OPTION_1','选择景点');

define('TEXT_SORT_OPTION_1','--选择排序方式--');
define('TEXT_SORT_OPTION_2','价格升序');
define('TEXT_SORT_OPTION_2_2','价格降序');
define('TEXT_SORT_OPTION_3','行程时间');
define('TEXT_SORT_OPTION_4','景点名称');

define('TEXT_OPTION_FROM_TO','目的景点筛选');

define('TEXT_POPULAR_TOURS','畅销景点');


//bof of navigation menu's translate

//WEST COAST TOURS
define('MENU_YELLOWSTONE_TOURS','美国黄石国家公园旅游');
define('MENU_MTRUSHMORE_TOURS','拉斯莫尔山旅游');
//define('MENU_LAS_VEGAS_TOURS','拉斯韦加斯旅游');
//define('MENU_SAN_FRANCISCO_TOURS','旧金山旅游');
define('MENU_LOS_ANGELES_TOURS','洛杉矶旅游');
define('MENU_GRAND_CANYON_TOURS','大峡谷旅游');
define('MENU_DISNELYLAND_TOURS','迪斯尼乐园旅游');
define('MENU_SAN_DIEGO_TOURS','圣地亚哥旅游');
define('MENU_UNIVERSAL_STUDIOS_TOURS','环球影城旅游');
define('MENU_YOSEMITE_TOURS','约塞米蒂国家公园旅游');
define('MENU_SEQUOIA_KINGS_CANYON_NP_TOURS','美洲杉国王峡谷国家公园旅游');
define('MENU_MEXICO_TOURS','墨西哥城旅游');
define('MENU_LAKE_TAHOE_TOURS','太浩湖旅游');
define('MENU_SACRAMENTO_TOURS','萨克拉曼多旅游');
define('MENU_NAPA_VALLEY_TOURS','纳帕谷酒乡之旅');
define('MENU_MORE_WEST_COAST_TOURS','<b>更多西海岸旅游</b>');

//EAST COAST TOURS
//define('MENU_NEW_YORK_TOURS','纽约旅游');
define('MENU_BOSTON_TOURS','波士顿旅游');
define('MENU_CANADA_TOURS','加拿大旅游');
define('MENU_NIAGARA_FALLS_TOURS','尼亚加拉河瀑布旅游');
define('MENU_PHILADELPHIA_TOURS','费城旅游');
define('MENU_WASHINGTON_DC_TOURS','华盛顿哥伦比亚特区旅游');
define('MENU_BALTIMORE_TOURS','巴尔的摩旅游');
define('MENU_RHODE_ISLAND_TOURS','美国罗得岛州旅游');
define('MENU_SHENANDOAH_TOURS','维珍尼亚州仙人洞旅游');
define('MENU_GORNING_CLASS_CENTER_TOURS','康宁玻璃中心旅游');
define('MENU_MORE_EAST_COAST','<b>更多东海岸旅游</b>');

//Hawaii TourS
define('MENU_MORE_HAWAII_TOURS','<b>更多夏威夷,旅游</b>');

//Florida Tour Packages
define('MENU_FLORIDA_TOURS_PACKAGES','佛罗里达旅游套&#39184; Florida Tour Packages');


//Tours ByCity
define('MENU_LOSANGELES_TOURS','洛杉矶 Los Angeles');
define('MENU_LAS_VEGAS_TOURS','拉斯维加斯 Las Vegas');
define('MENU_SALT_LAKE_CITY_TOURS','盐湖城 Salt Lake City');
define('MENU_SAN_FRANCISCO_TOURS','三藩市 San Francisco');
define('MENU_NEW_YORK_TOURS','纽约 New York');
define('MENU_HONOLUU_TOURS','檀香山 Honolulu');
define('MENU_ORLANDO_TOURS','奥兰多 Orlando');
define('MENU_PHILADEPHIA_TOURS','费城Philadelphia');

//amit added for shopping cart start

define('TEXT_TOTAL_NO_OF_ROOMS','总房间数量');
define('TEXT_OF_ADULTS_IN_ROOM','房间内成人数量');
define('TEXT_OF_CHILDREN_IN_ROOM','房间内小孩数量');
define('TEXT_TOTAL_OF_ROOM','总房间数量');
define('TEXT_NO_ADULTS','# 成人');
define('TEXT_NO_CHILDREN','# 小孩');
define('TEXT_TOTLAL','共计');



//used new desing
define('TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS','总房间数量');
define('TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS','&nbsp;&nbsp;# 房间内成人数量');
define('TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS','&nbsp;&nbsp;# 房间内小孩数量');
define('TEXT_SHOPPIFG_CART_ADULTS_NO','&nbsp;&nbsp;# 成人');
define('TEXT_SHOPPIFG_CART_CHILDREDN_NO','&nbsp;&nbsp;# 小孩');
define('TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM','总房间数量');
define('TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL','共计');


define('TEXT_BY', '提问人:');
define('TEXT_REPLY_BY', '答复人:');
define('TEXT_AT', '于 : &nbsp;');

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
define('TEXT_MENU_HOME_LINK','主页');
define('TEXT_MENU_HOTEL_RESERVATION','宾馆预订');
define('TEXT_MENU_FLIGHT_RESERVATION','机票定购');
define('TEXT_MENU_EAST_COAST_TOURS','美国东海岸');
define('TEXT_MENU_WEST_COAST_TOURS','美国西海岸');
//define('TEXT_MENU_LAS_VEGAS_TOURS','拉斯维加斯旅游');
define('TEXT_MENU_HAWAII_TOURS','夏威夷旅游');
define('TEXT_MENU_FLORIDA_TOURS','佛罗里达旅游');
define('TEXT_MENU_DESTINATION_GUIDE','目的地指南');


define('TEXT_TOP_LINK_REGISTER_REGISTER','注册');
define('TEXT_TOP_LINK_REGISTER_LOGIN','登录');
define('TEXT_TOP_LINK_CONTACT_US','联系我们');
define('TEXT_TOP_LINK_SUPPORT_SERVICES','服务/支持');
define('HEADING_TEXT_FAMOUS_ATTRACTIONS','著名景点推荐');
//amit added for sub categories page start
define('HEADING_TEXT_BLUE_TOURS_TITLE','精品推荐');
define('TEXT_VIEW_MORE_TORUS_TITLE','查看更多');


define('TEXT_DURATION_DAY','天');
define('TEXT_PLEASE_INSERT_GUEST_NAME','请输入游客姓名');
define('TEXT_PLEASE_INSERT_GUEST_EMAIL','请输入游伴邮箱');
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

define('TEXT_TOTAL_OF_ROOMS','总房间数：');//总房间数(Total Number of Rooms):

define('TEXT_OF_ADULTS_IN_ROOM1','房间一成人数：');//第一房间内成人数(Number of Adults in Room 1)
define('TEXT_OF_ADULTS_IN_ROOM2','房间二成人数：');
define('TEXT_OF_ADULTS_IN_ROOM3','房间三成人数：');
define('TEXT_OF_ADULTS_IN_ROOM4','房间四成人数：');
define('TEXT_OF_ADULTS_IN_ROOM5','房间五成人数：');
define('TEXT_OF_ADULTS_IN_ROOM6','房间六成人数：');
define('TEXT_OF_ADULTS_IN_ROOM7','房间七成人数：');
define('TEXT_OF_ADULTS_IN_ROOM8','房间八成人数：');
define('TEXT_OF_ADULTS_IN_ROOM9','房间九成人数：');
define('TEXT_OF_ADULTS_IN_ROOM10','房间十成人数：');
define('TEXT_OF_ADULTS_IN_ROOM11','房间十一成人数：');
define('TEXT_OF_ADULTS_IN_ROOM12','房间十二成人数：');
define('TEXT_OF_ADULTS_IN_ROOM13','房间十三成人数：');
define('TEXT_OF_ADULTS_IN_ROOM14','房间十四成人数：');
define('TEXT_OF_ADULTS_IN_ROOM15','房间十五成人数：');

define('TEXT_OF_CHILDREN_IN_ROOM1','房间一儿童数：');//第一房间内儿童数(Number of Children in Room 1):
define('TEXT_OF_CHILDREN_IN_ROOM2','房间二儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM3','房间三儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM4','房间四儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM5','房间五儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM6','房间六儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM7','房间七儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM8','房间八儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM9','房间九儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM10','房间十儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM11','房间十一儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM12','房间十二儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM13','房间十三儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM14','房间十四儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM15','房间十五儿童数：');

define('TEXT_TOTLE_OF_ROOM1','房间一小计：');//第一房间总费用(Subtotal 1)：
define('TEXT_TOTLE_OF_ROOM2','房间二小计：');
define('TEXT_TOTLE_OF_ROOM3','房间三小计：');
define('TEXT_TOTLE_OF_ROOM4','房间四小计：');
define('TEXT_TOTLE_OF_ROOM5','房间五小计：');
define('TEXT_TOTLE_OF_ROOM6','房间六小计：');
define('TEXT_TOTLE_OF_ROOM7','房间七小计：');
define('TEXT_TOTLE_OF_ROOM8','房间八小计：');
define('TEXT_TOTLE_OF_ROOM9','房间九小计：');
define('TEXT_TOTLE_OF_ROOM10','房间十小计：');
define('TEXT_TOTLE_OF_ROOM11','房间十一小计：');
define('TEXT_TOTLE_OF_ROOM12','房间十二小计：');
define('TEXT_TOTLE_OF_ROOM13','房间十三小计：');
define('TEXT_TOTLE_OF_ROOM14','房间十四小计：');
define('TEXT_TOTLE_OF_ROOM15','房间十五小计：');

define('TEXT_BED_OF_ROOM1','房间一床型：');
define('TEXT_BED_OF_ROOM2','房间二床型：');
define('TEXT_BED_OF_ROOM3','房间三床型：');
define('TEXT_BED_OF_ROOM4','房间四床型：');
define('TEXT_BED_OF_ROOM5','房间五床型：');
define('TEXT_BED_OF_ROOM6','房间六床型：');
define('TEXT_BED_OF_ROOM7','房间七床型：');
define('TEXT_BED_OF_ROOM8','房间八床型：');
define('TEXT_BED_OF_ROOM9','房间九床型：');
define('TEXT_BED_OF_ROOM10','房间十床型：');
define('TEXT_BED_OF_ROOM11','房间十一床型：');
define('TEXT_BED_OF_ROOM12','房间十二床型：');
define('TEXT_BED_OF_ROOM13','房间十三床型：');
define('TEXT_BED_OF_ROOM14','房间十四床型：');
define('TEXT_BED_OF_ROOM15','房间十五床型：');

define('TEXT_BED_STANDARD','不限制');
define('TEXT_BED_KING','一张King-sized大床');
define('TEXT_BED_QUEEN','两张标准床');



define('QNA_FAQ_BACK_TO_TOP', '[Top]');
define('TEXT_DISPLAY_TOP','TOP');
define('TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE','假日价格');
define('TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE','升级价格');
define('TEXT_HEADING_PRODUCT_ATTRIBUTE_OPTIONS_TOUR','');
define('TEXT_HEADING_REGULAR_SPECIAL_PRICE','标准价格');

//error massaeg display
define('TEXT_ERROR_MSG_YOUR_NAME', '* 请输入您的姓名.<br/>');
define('TEXT_ERROR_MSG_YOUR_EMAIL', '* 请输入您的电子邮件地址.<br/>');
define('TEXT_ERROR_MSG_VALID_EMAIL', '* 请输入有效的电子邮箱.<br/>');
define('TEXT_ERROR_MSG_YOUR_EMAIL_CONFIRMATION', '* 邮箱地址的确认要与您之前所填写的邮箱地址相同.<br/>');
define('TEXT_ERROR_MSG_REVIEW_TITLE', '* 请输入评论标题.<br/>');
define('TEXT_ERROR_MSG_REVIEW_TEXT', '* 请输入评论内容.<br/>');
define('TEXT_ERROR_MSG_REVIEW_RATING', '* 您需要评定旅行的等级.<br/>');
define('TEXT_ERROR_MSG_YOUR_QUESTION', '* 请输入您的问题.<br/>');
define('TEXT_ERROR_MSG_YOUR_ANSWERS', '* 请输入您的答案.<br/>');
define('TEXT_DURATION_OPTION_ALL_DURATIONS','所有时间');
define('TEXT_DURATION_OPTION_DURATION','行程时间');
define('TEXT_DURATION_OPTION_LESS_ONE','1天以内');
define('TEXT_DEPARTURE_OPTION_CITY','出发城市筛选');
define('TEXT_DEPARTURE_OPTION_ALL_DEPARTURE_CITY','所有出发城市');
define('TEXT_OPTION_TOUR_TYPE','旅游类型');
define('TEXT_OPTION_ALL_TOUR_TYPES','所有旅游类型');
define('TEXT_OPTION_FILTER_BY','筛选:');
define('TEXT_OPTION_SORT_BY','排序按:');
define('TEXT_TAB_INTRODUCTION','景点介绍');
define('TEXT_TAB_TOURS','周边热点游');
define('TEXT_TAB_VACATION_PACKAGES','度假休闲游 ');
define('TEXT_TAB_SPECIAL','限时抢购团');
define('TEXT_TAB_RECOMMENDED','畅销行程');
define('TEXT_TAB_MAP','Map');
//define('TEXT_NOTES_CLICK_VIDEO','点击 <img src="image/vido_light_bg.gif" /> 收看各旅游胜地的录像');
define('TEXT_NOTES_CLICK_VIDEO','');
define('TEXT_SEARCH_RESULT_BOX_HEADING','搜索结果');
define('TEXT_REQUERED_NOT_DISPLAYED','(必须填写但是不会显示在页面)');
define('TEXT_REVIEW_ADDED_SUCCESS','您的评论添加成功.');
define('HEADING_REFEAR_A_FRIEND_RECOMMEND_CATGORY_OR_TOUR','推荐类别/旅行');
define('HEADING_REFEAR_A_FRIEND_YOUR_PERSONAME_DETAILS','个人详细资讯');
define('HEADING_REFEAR_A_FRIEND_EMAIL_ADDRESS','您朋友的邮箱地址');
define('HEADING_REFEAR_A_FRIEND_A_MESSAGE_TO_FRIEND','给您朋友的留言');


define('TEXT_SHOPPING_CART_DEPARTURE_DATE','出发日期：');
define('TEXT_SHOPPING_CART_PICKP_LOCATION','出发地点：');
define('TEXT_SHOPPING_CART_REMOVE_RESERVATION_LIST_CONFIRM','您确定要将这次旅行预订从列表中删除吗？');
define('TEXT_SHOPPING_CART_DEPARTURE_DATE_PRICE_FLUCTUATIONS','出发日期价格浮动:');


//header defines
define('TEXT_MEMBER_LOGIN','会员登录');
define('TEXT_FREE_REG','免费注册');
define('TEXT_HEADER_WELCOME_TO','欢迎来到走四方网！');
//define('TEXT_HEADER_ALREADY_A_MEMBER','已经是会员?');
//define('TEXT_HEADER_JOIN_TODAY','Join today');
define('TEXT_HEADER_ALREADY_A_MEMBER','');
define('TEXT_HEADER_JOIN_TODAY','');
define('TEXT_MENU_VACATION_PACKAGES','旅游套餐');
define('TEXT_MENU_BY_DEPARTURE_CITY','出发城市');
define('TEXT_HEADING_MORE_DEPARTURE_CITIES','More Departure Cities');
define('TEXT_NO_PRODUCTS', '<br><br>抱歉未能找到符合您要求的旅行团！ <br><br>
您不妨减少您输入的内容，或许会得到更多的结果。或者您可以直接拨打电话1-626-898-7800 888-887-2816，咨询我们的旅游专员。
他们在每周一、三、五的上午9点到下午5点（太平洋时间）接受电话咨询。您还可以通过发邮件到<a class="sp3" href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'">'.STORE_OWNER_EMAIL_ADDRESS.'</a>的方式与我们联系。我们会尽量在一到两个工作日之内回复您的问题。 <br><br>');

define('TEXT_HEADING_MORE_DEPARTURE_CITIES','<strong>更多出发城市</strong>');
define('TEXT_DROP_DOWN_SELECT_COUNTRY','选择国家');
define('TEXT_HEDING_COUNTRY_SEARCH','国家/地区:');
define('TEXT_DROP_DOWN_SELECT_REGION','选择类别/目的地');
define('TEXT_MU_DI_DI','选择目的地');

if(HTTP_SERVER==SCHINESE_HTTP_SERVER){
	define('LANGUAGE_BUTTON','<li><a href="'.TW_CHINESE_HTTP_SERVER.$_SERVER['REQUEST_URI'].'" title="访问繁体版的usitrip" >繁体</a></li><li class="us" style="display:none"><a href="http://www.usitrip.com/" title="to usitrip">English</a></li>');
	define('LANGUAGE_BUTTON_1','<a href="'.TW_CHINESE_HTTP_SERVER.$_SERVER['REQUEST_URI'].'" title="访问繁体版的usitrip" >繁体</a>');
}else{

	if(preg_match('/\?/',$_SERVER['REQUEST_URI'])){ $strlink = '&';}else{ $strlink = '?';}
	define('LANGUAGE_BUTTON','<li><a href="'.HTTP_SERVER.preg_replace('/(&*)language=(tw|sc)(&*)/','',$_SERVER['REQUEST_URI']).$strlink.'language=tw'.'" title="访问繁体版的usitrip">繁体</a></li><li class="us" style="display:none"><a href="http://www.usitrip.com/" title="to usitrip" >English</a></li>');
	define('LANGUAGE_BUTTON_1','<a href="'.HTTP_SERVER.preg_replace('/(&*)language=(tw|sc)(&*)/','',$_SERVER['REQUEST_URI']).$strlink.'language=tw'.'" title="访问繁体版的usitrip">繁体</a>');
}

define('TEXT_ALTER_TAG','tours, travel');

define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个产品)');
define('HEDING_TEXT_ENTER_PHOTO_TITLE','在这里输入标题');
define('HEDING_TEXT_ENTER_PHOTO_DESCRIPTION','在这里输入内容');

define('TEXT_DISPLAY_NUMBER_OF_PHOTOS', '结果-<b>%d</b>-<b>%d</b> 共 <b>%d</b> 条');

define('TEXT_PLEASE_INSERT_GUEST_LASTNAME','请输入客户姓氏');
define('TEXT_DROPDOWN_POULARITY','浏览量');


define('BOX_HEADING_LOYAL_CUSTOMER_GOOD_NEWS','给忠实客户的好消息！');
define('BOX_HEADING_LOYAL_CUSTOMER_PARA','只要您在usitrip消费过一次，只要您再次向我们订团，您的新订单将即时享受5%的折扣！我们借以此种方式来表达谢意，感谢您再次选择我们。');
define('TEXT_LOYAL_CUSMER_PERC_REWARD_FOR','5%忠实客户<br/>折扣面向我们所有的');
define('TEXT_LOYAL_CUSMER_REPEAT_LINK','回头顾客！');
define('TEXT_HEADING_RED_REPEAT_CUSTOMERS_NOTES','<font color="#ff0000"><b>5%的折扣给我们的忠实客户。现在就<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=checkout', 'SSL') . '" style="color:#ff0000"><u>支付</u></a>吧！</b></font> ');
define('TEXT_TITLE_DEPARTURE_CITY', '旅游的出发城市');

define('FORGOT_PASSWORD','忘记密码？');
define('REGISTER','注册');
define('MY_TOURS','我的走四方');

define('SELECT_DESTINATIONS','选择去向');
define('LOGIN_INPUT_TEXT','用户名');
define('LOGIN_NAME','登录名');
define('JS_NO_LOGIN_NAME','请输入您的'.LOGIN_NAME.'(您在本站注册时使用的电子邮箱)！');

define('YOUR_NEWS_LETTER_EMAIL','您的E-mail地址');
define('JS_NO_NEWS_LETTER_EMAIL','请输入您的E-mail地址');
define('NEWS_LETTER_EMAIL_SUBMIT_OK','您的E-mail地址提交成功。');
define('PLX_WAIT','<img src="image/loading_16x16.gif" width="16" height="16" align="absmiddle" />&nbsp;请稍后...');

define('WELCOME_YOU','欢迎您');
define('HAVE_POINTS','拥有积分：');
define('WELCOME_TO_TOURS','%s,欢迎您来到走四方！');
define('ORDER_NEED_DO','您有<span class="hongse cu">%d</span>笔订单需要处理。');
define('MY_SPACE','我的空间');
define('MY_SPACE_INFORMATION','个人信息');
define('MY_SPACE_LOGS','日志');
define('MY_SPACE_LOOKS','随便逛逛');
define('MY_SPACE_PHOTOS','相册');
define('MY_SPACE_SHARE','分享');

define('SKIP_TO','跳转至：');
define('ACCOUNT_MANAGEMENT','帐户管理');
define('DEFAULT_STRING','默认');
define('SET_FOR_HOME','为首页');

define('LOGIN_OVERTIME','登录超时！');

// Points/Rewards Module V2.1rc2a BOF
define('MY_POINTS_TITLE', '我的积分');
define('MY_POINTS_VIEW', '积分奖励概要');
define('MY_POINTS_VIEW_HELP', '积分FAQ');
define('MY_POINTS_CURRENT_BALANCE', '总积分： %s  价值： %s ');
define('REWARDS4FUN_ACTIONS_DESCRIPTION', '活动说明');
define('REWARDS4FUN_REFER_FRIENDS','推荐给朋友');
define('REWARDS4FUN_ACTIONS_HISTORY', '奖励/兑换记录');
define('REWARDS4FUN_FEEDBACK_APPROVAL', '评论链接回馈');
define('REWARDS4FUN_TERMS', 'Rewards4Fun条款');
define('REWARDS4FUN_TERMS_NAVI', '活动执行规则');
define('TEXT_DISCOUNT_UP_TO', 'Rewards4Fun折扣高达： ');
define('REDEEM_SYSTEM_ERROR_POINTS_NOT', '您的积分不够。请用其它方式购买！');
define('REDEEM_SYSTEM_ERROR_POINTS_OVER', 'REDEEM记分错误！点的价值不能超过总价值。请重新输入点');
define('REFERRAL_ERROR_SELF', '很抱歉，您可以不提及自己。');
define('REFERRAL_ERROR_NOT_VALID', '推介电子邮件似乎没有有效的-请进行任何必要的更正。');
define('REFERRAL_ERROR_NOT_FOUND', '推介的电子邮件地址您输入没有被发现。');
define('TEXT_POINTS_BALANCE', '点状态');
define('TEXT_POINTS', '点：');
define('TEXT_VALUE', '价值：');
define('REVIEW_HELP_LINK', ' 点评您在旅途中对各方面的评价、分享您的旅游心情和感受，即可获得<b>%s</b>积分/条。 %s ');//worth of
define('PHOTOS_HELP_LINK', ' 将您的旅途中的靓照与走四方网分享，与走四方网的朋友分享，走四方网对上传的照片每张给予 <b>%s</b> 积分的奖励，上传多多，积分多多，更可为您下次的旅行节省开支！也可以结识更多的旅友！<br />查看 %s 更多优惠活动。	');
define('ANSWER_HELP_LINK', ' 回答问题获取<b>%s</b>个走四方积分。请点击 %s 了解详情。');
define('REFER_FRIEND_HELP_LINK', ' 参与走四方积分回馈，赢取美元现金折扣！ <br><br>将走四方网或我们的旅游产品通过邮件的方式推荐给您的朋友。如果您的朋友通过、邮件点击并成为走四方网的注册用户，您就可以获得每位 <b>%s</b> 积分的奖励。在您订购旅游行程时，便可使用这些积分兑换为美元进行消费！<br />欲了解更多信息，请单击 %s 。');
define('BOX_INFORMATION_MY_POINTS_HELP', '积分FAQ');
define('TEXT_MENU_JOIN_REWARDS4FUN','积分奖励');
define('TEXT_REG_GET_REWARDS4FUN','注册即可获得'.NEW_SIGNUP_POINT_AMOUNT.'积分奖励！');
// Points/Rewards Module V2.1rc2a EOF

//howard added
define('NEXT_NEED_SIGN','请您登录后进行下一步操作！');
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

define('HEADING_ORDER_COMMENTS','订购留言');
define('HEADING_ORDER_COMMENTS_NOTES','<b>提示：</b>通过“结伴同游”下单的用户，请在此方框内注明“结伴同游”字样及同行拼房用户的订单号。');

define('OFFICE_PHONE','联系电话');
define('HOME_PHONE','其他电话');
define('MOBILE_PHONE','移动电话');

define('PHONE_TYPE_ERROR','请选择您填写的电话号码的所属类型！');
define('RADIO_ERROR','必须选择其中一项。');
define('SELECT_OPTION_ERROR','请作出一个选择。');

define('MONTH_DAY_YEAR','月/日/年');
//howard added end

define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE','总价(包括3%服务费)');
define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_PERSENT','总价(包括%s%%服务费)');
define('TEXT_PRODUCTS_MIN_GUEST','参团人数不能少于:');

define('ORDER_TOTAL_TEXT','总计:');

define('TEXT_HEADING_DEPARTURE_TIME_LOCATIONS_LL','出发时间和地点');
define('TEXT_FOOTER_TRAVEL_INSURANCE','旅游保险');

define('TEXT_RMB_CHECK_OUT_MSN','走四方所有产品以美元作为基准计价币种，美元兑换人民币汇率以银行当日汇率中间价为准。您可以自由选择此双币种多种支付方式的预订服务。');

define('SEARCH_RECOMMEND','大峡谷 黄石公园');

define('ERROR_SEL_SHUTTLE','请选择您的上车地点');
define('TEXT_MAX_ALLOW_ROOM','每房间允许的客人最大数:');

define('SHARE_ROOM_WITH_TRAVEL_COMPANION','结伴拼房');

//define('JS_MAY_NOT_ENTER_TEXT','可不填');
define('JS_MAY_NOT_ENTER_TEXT','');
define('JS_UNKNOWN_STRING','未知');

define('JIEBANG_CART_NOTE_MSN','注:结伴同游只能下一次订单，其他结伴者请登入用户中心，查看“结伴同游订单”，确认个人信息并选择支付即可。');

define('HEADING_BILLING_INFORMATION', '帐单资讯');
define('HEADING_BILLING_ADDRESS', '信用卡地址');

define('TEXT_BILLING_INFO_ADDRESS', ENTRY_STREET_ADDRESS);
define('TEXT_BILLING_INFO_CITY', ENTRY_CITY);
define('TEXT_BILLING_INFO_STATE', ENTRY_STATE);
define('TEXT_BILLING_INFO_POSTAL', ENTRY_POST_CODE);
define('TEXT_BILLING_INFO_COUNTRY', ENTRY_COUNTRY);
define('TEXT_BILLING_INFO_TELEPHONE', '办公电话:');
define('TEXT_BILLING_INFO_FAX', ENTRY_FAX_NUMBER);
define('TEXT_BILLING_INFO_MOBILE', '移动电话:');

define('TEXT_EDIT', '编辑');

define('MY_TRAVEL_COMPANION','我的结伴同游');
define('MY_TRAVEL_COMPANION_ORDERS','结伴同游订单');
define('I_SENT_TRAVEL_COMPANION_BBS','我的发帖');
define('I_REPLY_TRAVEL_COMPANION_BBS','我的回帖');
define('LATEST_TRAVEL_COMPANION_BBS','最新结伴同游帖');

define('TEXT_DURATION_LINK_1','1-2天');
define('TEXT_DURATION_LINK_2','3天行程');
define('TEXT_DURATION_LINK_3','4天行程');
define('TEXT_DURATION_LINK_4','5-6天');
define('TEXT_DURATION_LINK_5','7天以上');
define('TEXT_DURATION_LINK_ALL','全部');
define('TEXT_TRAVEL_OPTIONS', '<!--旅行选择-->');

//amit added 2009-12-09
define('TEXT_REVIEW', '评论');
define('TEXT_QANDA', '咨询');
define('TEXT_PHOTOS', '照片');
define('TEXT_TRAVEL_COMPANION_POSTS', '结伴同游'); //结伴发贴

define('HEADING_DESTINATIONS', '景点列表');
define('HEADING_ATTRACTIONS', '目的地景点');
define('HEADING_DEPARTURE_CITIES', '按出发城市查看');

//howard added 2010-01-12
define('TITLE_GROUP_BUY','团体预定优惠:');
define('TITLE_BBS_CONTENT','帖子内容');
define('TITLE_NEW_GROUP_BUY','团购优惠:');
define('TITLE_NEW_GROUP_BUY_OLD_PRICE','原房间总价:');
define('TITLE_NEW_GROUP_BUY_OLD_PRICE_NOT_ROOM','原价:');


define('TEXT_HOW_SAVE','积分现金折扣比例');
define('TEXT_SAVINGS','积分目前只适用于行程抵扣。在积分足够的情况下：<br />第一次订购优惠最低<span style="color: rgb(241, 115, 13);">3%</span>,最高<span style="color: rgb(241, 115, 13);">6%</span>！<br>第二次订购优惠最低<span style="color: rgb(241, 115, 13);">4%</span>,最高<span style="color: rgb(241, 115, 13);">7%</span>！<br>订购超过两次优惠最低<span style="color: rgb(241, 115, 13);">5%</span>,最高<span style="color: rgb(241, 115, 13);">8%</span>！');

define('TFF_POINTS_DESCRIPTION','走四方积分兑换说明');
define('TFF_POINTS_DESCRIPTION_CONTENT','兑换比例：100积分兑换1美元。<br> 
兑换方法：在行程结帐页面的“积分兑换”，您可以看到您目前的积分总数及订购此行程可使用的最高积分数。随后点击“兑换积分”按钮，系统自动计算您最后需要支付的余额，确认抵换后，系统自动扣除您的积分。');

//las vegas show
define('PERFORMANCE_TIME','演出时间:');
define('WATCH_PEOPLE_NUM','人数:');

//howard added 2010-5-18
define('BUY_SUCCESS_SMS',"您的订单（%s）已生成，登陆网站至“我的走四方”或您的注册邮箱查看进度。服务热线：4006-333-926，感谢您的预订！");
//tom added 2010-5-27
define('TEXT_ORDER_STATUS_PENDING','Pending');

define('YELLOWSTONE_TABLE_NOTES','实际剩余座位数以下订单后的邮件确认为准，邮件通常在1-2个工作日内回复。');

define('MY_COUPONS','优惠券');
define('MY_COUPONS_MENU','我的优惠券');
define('CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID','96'); // donot modify without permission 系统自动更新的虚拟管理账号的id，此值不可修改！
define('TEXT_VISA_PASS_YREQ','<strong>参观美国及加拿大两侧瀑布：</strong> 除持有美国护照或绿卡外，进入加拿大境内还需要有效的户照和签证。请<a href="http://www.cic.gc.ca/english/visit/visas.asp" target="_blank">点击这里</a>获取关于游客签证要求及豁免权信息。');
define('TEXT_VISA_PASS_NOTREQ','<strong>仅参观美国一侧瀑布：</strong> 此类行程不要求提供加拿大签证。');
define('TITLE_CREDIT_APPLIED','信用支付金额： ');
define('TEXT_SELECT_VALID_DEPARTURE_DATE','请选择计划出发时间');

define('RATING_STR_5','非常满意');
define('RATING_STR_4','比较满意');
define('RATING_STR_3','一般');
define('RATING_STR_2','不满意');
define('RATING_STR_1','很不满意');
define('ENTRY_HEIGHT','身高 (ft/cm):');
define('ENTRY_HEIGHT_ERROR','身高');

define('TXT_FEATURED_DEAL_DISCOUNT', '特色团购');
define('TXT_FEATURED_DEALS_SECTION', 'Featured Deals');
//yichi added 2011-04-02
define('BEFORE_EXTENSION_SMS',"您或许在参团前有加订酒店住宿的需求，我们可为您提供洁净、舒适、轻松参团的酒店增订服务；服务热线400-6333926或 Service@usitrip.com ");
//yichi added 2011-04-02
define('AFTER_EXTENSION_SMS',"您或许在参团后有加订酒店住宿的需求，我们可为您提供洁净、舒适、轻松参团的酒店增订服务；服务热线400-6333926或 Service@usitrip.com ");

define('NO_SEL_DATE_FOR_GROUP_BUY','未定日期');
define('TEXT_BEFORE','之前');
define('HOTEL_EXT_ATTRIBUTE_OPTION_ID','9999'); // donot modify without permission

define('SEARCH_BOX_TIPS',"请输入出发城市或想去的景点");
define('SEARCH_BOX_TIPS1',"请输入关键字");
define('TXT_ADD_FEATURES_TOUR_IDS', '');
define('PRIORITY_MAIL_PRODUCTS_OPTIONS_ID', '146'); // donot modify without permission
define('PRIORITY_MAIL_PRODUCTS_OPTIONS_VALUES_ID', '866'); // donot modify without permission
define('TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE', '门票邮递日期 ');
define('TEXT_SELECT_PRIORITY_MAIL_DATE_NOTE', '请注意：我们通常在门票使用日期前7天内开始邮递门票');
define('TXT_PRIORITY_MAIL_DELIVERY_ADDRESS', '邮递地址 ');
define('TXT_PRIORITY_MAIL_DELIVERY_ADDRESS_NOTE', '提示：如果您使用美国酒店地址为邮递门票的地址，请提供酒店的详细信息，包括酒店地址，联系电话以及房间号等，以便您的门票能够及时投递。');
define('TXT_PRIORITY_MAIL_RECIPIENT_NAME', '收件人 ');
define('ERROR_CHECK_PRIORITY_MAIL_DATE', '所选日期为无效邮递日期');
define('NEW_PAYMENT_METHOD_T4F_CREDIT', '虚拟账户');
define('TOUR_IDS_FOR_ATTR_THEME_PARK_NOTE', '');
define('TXT_PROVIDERS_DTE_BTL_IDS', '101,96');
define('HOTEL_PRICE_PER_DAYS_ATTR_NAME', '请选择早餐类别'); //please donot modify

define('EMAIL_SEPARATOR', '-----------------------------------------------------------------------------------------------------------');

//默认的SEO信息
define('HEAD_TITLE_TAG_ALL', 'usitrip走四方旅游网-美国华人旅行社_旅游景点线路团价格报价_度假行程安排攻略_签证移民留学游学');
define('HEAD_DESC_TAG_ALL','USITRIP走四方旅游网身为最知名华人旅行社,为全球华人量身定制去美国旅游,加拿大旅游,欧洲旅游等出国旅游景点行程攻略,旅行线路团购价格,旅游签证移民游学,打折机票酒店预订攻略,旅途美食住宿及购物攻略等服务');
define('HEAD_KEY_TAG_ALL','旅游景点线路,旅游行程攻略,签证游学留学,华人旅行社');

?>