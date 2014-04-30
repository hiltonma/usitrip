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
define('LANGUAGE_CURRENCY', 'CNY');//预设值

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="zh-tw"');

// charset for web pages and emails
//define('CHARSET', 'gb2312');
define('CHARSET', 'gbr2312');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', '注册账号');
define('HEADER_TITLE_MY_ACCOUNT', '我的账号');
define('HEADER_TITLE_CART_CONTENTS', '购物车');
define('HEADER_TITLE_CHECKOUT', '结帐');
define('HEADER_TITLE_CONTACT_US', '联络我们');
define('HEADER_TITLE_TOP', '首页');
define('HEADER_TITLE_CATALOG', '商品目录');
define('HEADER_TITLE_LOGOFF', '退出');
define('HEADER_TITLE_LOGIN', '登陆');
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
define('BOX_HEADING_MANUFACTURER_INFO', '厂商的相关资讯');
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
define('CHECKOUT_BAR_PAYMENT', '支付资讯');
define('CHECKOUT_BAR_CONFIRMATION', '确认订单');
define('CHECKOUT_BAR_FINISHED', '完成');

// pull down default text
define('PULL_DOWN_DEFAULT', '请选择');
define('TYPE_BELOW', '在下面输入');

// javascript messages
define('JS_ERROR', '在提交表格过程中出现错误.\n\n请做下述改正:\n\n');

define('JS_REVIEW_TEXT', '* \'评论内容\' 必须至少包含 ' . REVIEW_TEXT_MIN_LENGTH . ' 个字元.\n');
define('JS_REVIEW_RATING', '* 您必须为您做了评论的团评等级.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* 请为您的订单选择一个支付方式.\n');

define('JS_ERROR_SUBMITTED', '这个表单已经送出，请按 Ok 后等待处理');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', '您必须选一个付款方式.');

define('CATEGORY_COMPANY', '公司资料');
define('CATEGORY_PERSONAL', '个人资料');
define('CATEGORY_ADDRESS', '地址');
define('CATEGORY_CONTACT', '您的联系资讯');
define('CATEGORY_OPTIONS', '选项');
define('CATEGORY_PASSWORD', '密码');

define('ENTRY_COMPANY', '公司名称:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', '性别:');
define('ENTRY_GENDER_ERROR', '请选择性别');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', '中文姓名:');
define('ENTRY_FIRST_NAME_ERROR', ' <small><font color="#FF0000">'.ENTRY_FIRST_NAME.'少于 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', '护照英文名:');
define('ENTRY_LAST_NAME_ERROR', ' <small><font color="#FF0000">'.ENTRY_LAST_NAME.'少于 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', '生日:');
define('ENTRY_DATE_OF_BIRTH_ERROR', ' <small><font color="#FF0000">(例∶05/21/1970)</font></small>');
define('ENTRY_DATE_OF_BIRTH_TEXT', ' <small>(例∶05/21/1970) <font color="#AABBDD">必填栏位</font></small>');
define('ENTRY_EMAIL_ADDRESS', '电子邮箱:');
define('ENTRY_EMAIL_ADDRESS_ERROR', ' <small><font color="#FF0000">'.ENTRY_EMAIL_ADDRESS.'少于 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', ' <small><font color="#FF0000">电子邮件位址格式错误!</font></small>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', ' <small><font color="#FF0000">这个电子邮件已经注册过!请确认或换一个电子邮件</font></small>');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', '详细地址:');
define('ENTRY_STREET_ADDRESS_ERROR', ' <small><font color="#FF0000">'.ENTRY_STREET_ADDRESS.'少于 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', '街道:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', '邮政编码:');
define('ENTRY_POST_CODE_ERROR', ' <small><font color="#FF0000">'.ENTRY_POST_CODE.'少于 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', '城市:');
define('ENTRY_CITY_ERROR', ' <small><font color="#FF0000">'.ENTRY_CITY.'少于 ' . ENTRY_CITY_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', '州/省:');
define('ENTRY_STATE_ERROR', '州/省最少必须 ' . ENTRY_STATE_MIN_LENGTH . '个字');
define('ENTRY_STATE_ERROR_SELECT', '请从下拉式选单中选取');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', '国家/地区:');
define('ENTRY_COUNTRY_ERROR', '请从下拉式选单中选取');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', '电话号码:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '电话号码不得少于 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 个字</font></small>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', '移动电话:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', '订阅走四方资讯邮件:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', '-订阅-');
define('ENTRY_NEWSLETTER_NO', '取消');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', '密码:');
define('ENTRY_PASSWORD_ERROR', '密码不得少于' . ENTRY_PASSWORD_MIN_LENGTH . ' 个字');
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
define('TEXT_RESULT_PAGE', '总页数:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '显示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>个商品)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '显示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>笔订单)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '显示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>条记录)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', '显示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>个新商品)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '显示 <b>%d</b> 到 第 <b>%d</b> (共 <b>%d</b> 项特价)');


define('PREVNEXT_TITLE_FIRST_PAGE', '第一页');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', '前一页');
define('PREVNEXT_TITLE_NEXT_PAGE', '下一页');
define('PREVNEXT_TITLE_LAST_PAGE', '最后一页');
define('PREVNEXT_TITLE_PAGE_NO', '第%d页');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', '前 %d 页');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', '后 %d 页');
define('PREVNEXT_BUTTON_FIRST', '<<最前面');
define('PREVNEXT_BUTTON_PREV', '[<< 往前]');
define('PREVNEXT_BUTTON_NEXT', '[往后 >>]');
define('PREVNEXT_BUTTON_LAST', '最后面>>');

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
define('TEXT_NO_REVIEWS', '目前没有任何商品评论.');

define('TEXT_NO_NEW_PRODUCTS', '目前没有新进商品.');

define('TEXT_UNKNOWN_TAX_RATE', '不明的税率');

define('TEXT_REQUIRED', '<span class="errorText">必填</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> 无法由指定的 SMTP 主机传送邮件，请检查 php.ini 设定</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', '警告∶ 安装目录仍然存在∶ ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. 基于安全的理由，请将这个目录删除');
define('WARNING_CONFIG_FILE_WRITEABLE', '警告∶ 设定档允许被写入∶ ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. 这将具有潜在的系统安全风险 - 请将档案设定为正确的使用权限');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', '警告∶ sessions 资料夹不存在∶ ' . tep_session_save_path() . '. 在这个目录未建立之前 Sessions 无法正常动作');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', '警告∶ 无法写入sessions 资料夹∶ ' . tep_session_save_path() . '. 在使用者许可权未正确设定之前 Sessions 将无法正常动作');
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
    //amit added new for language start
	define('FOOTER_TEXT_BODY', '版权 &copy;2005-'.date('Y').' usitrip.com, 拥有最终解释权. <br>网站内价格和产品行程有可能会有更改变动，不做另行通知. <br>usitrip.com不对印刷错误引起的不便负任何责任. 所有的印刷错误我们都会作改动.');

  define('BOX_INFORMATION_PRIVACY_AND_POLICY', '隐私条例');
  define('BOX_INFORMATION_PAYMENT_FAQ','付款常见问题');
  define('BOX_INFORMATION_COPY_RIGHT','版权');
  define('BOX_INFORMATION_CUSTOMER_AGREEMENT','客户协定');
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
define('NAV_ORDER_INFO', '订单资讯');

/*End Checkout WIthout Account images*/
define('ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE', '国家代码 ');
define('ENTRY_CELLPHONE_NUMBER',"（请提供必要时的以备紧急联系之用的号码）∶");
define('ENTRY_CELLPHONE_NUMBER_TEXT', '');


define('BOX_INFORMATION_GV', '关于礼券的常见问题解答');
define('VOUCHER_BALANCE', '礼券馀额');
define('BOX_HEADING_GIFT_VOUCHER', '礼券帐户'); 
define('GV_FAQ', '关于礼券的常见问题解答');
define('ERROR_REDEEMED_AMOUNT', '恭喜您，您的兑换成功了');
define('ERROR_NO_REDEEM_CODE', '您还没有输入兑换号码.');  
define('ERROR_NO_INVALID_REDEEM_GV', '无效的礼券代码'); 
define('TABLE_HEADING_CREDIT', '有效卡');
define('GV_HAS_VOUCHERA', '您的礼券帐户上仍有馀额，如果您愿意<br>
                         您可以将它们寄送出去通过<a class="pageResults" href="');       
define('GV_HAS_VOUCHERB', '"><b>以电子邮件寄给</b>给其他人'); 
define('ENTRY_AMOUNT_CHECK_ERROR', '您没有足够的礼券寄送这个数目.'); 
define('BOX_SEND_TO_FRIEND', '寄送礼券');
define('VOUCHER_REDEEMED', '礼券已经兑换');
define('CART_COUPON', '礼券 :');
define('CART_COUPON_INFO', '更多资讯');
//amit added new for language end
//define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '显示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>个商品)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', '显示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)');  // featured tours
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', '显示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // referrals
define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS', '显示 <b>%d</b> 到  <b>%d</b> (共 <b>%d</b>)'); // questions

//added for product listing page start
define('TEXT_WELCOME_TO','欢迎来到');
define('TEXT_CUSTOMER_AGREE_BOOK','请在网上预订之前阅读我们的客户协议。');
define('TEXT_TOUR_PICKUP_NOTE','一个<FONT COLOR="#0000ff">打包旅游</FONT> 通常包括机场的接送服务.');
define('TEXT_SORT_BY','排序方式∶');
define('TEXT_TELL_YOUR_FRIEND','告诉您的朋友');
define('TEXT_ABOUT',' 关于 ');
define('TEXT_AND_MAKE','并且取得');
define('TEXT_COMMISSION','佣金');
define('TEXT_TOUR_ITINERARY','旅游路线∶');
define('TEXT_DEPART_FROM','出发地点∶');
define('TEXT_OPERATE','出团日期∶');
define('TEXT_PRICE','价格∶');
define('TEXT_HIGHLIGHTS','主要景点∶');
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


define('TEXT_NO_QUESTION_FOUND','没有找到相关资讯。');
define('TEXT_SEARCH_FOR_YOUR_TOUR','搜索旅游景点');

define('TEXT_TITLE_TOURS_DEALS','推荐旅游');

//JAMES ADD FOR OTHERS TEXT
define('TEXT_NORMAL_TELL_FRIEND', '告诉您的朋友');
define('TEXT_NORMAL_ABOUT', '关于');
define('TEXT_NORMAL_GAIN', '并且取得');
define('TEXT_NORMAL_COMISSION', '的佣金!');

//JAMES ADD FOR PRODUCT DURATION OPTIONS
define('TEXT_DURATION_OPTION_1','选择持续天数');
define('TEXT_DURATION_OPTION_2','1 天');
define('TEXT_DURATION_OPTION_3','2 天');
define('TEXT_DURATION_OPTION_4','2 到 3 天');
define('TEXT_DURATION_OPTION_5','3 天');
define('TEXT_DURATION_OPTION_6','3 到 4 天');
define('TEXT_DURATION_OPTION_7','4 天');
define('TEXT_DURATION_OPTION_8','4 天或更多天数');
define('TEXT_DURATION_OPTION_9','5 天或更多天数');

define('TEXT_ATTRACTION_OPTION_1','选择景点');

define('TEXT_SORT_OPTION_1','--选择排序方式--');
define('TEXT_SORT_OPTION_2','旅游价格');
define('TEXT_SORT_OPTION_3','持续天数');
define('TEXT_SORT_OPTION_4','景点名称');

define('TEXT_POPULAR_TOURS','畅销景点');
?>
