<?php
/*
  $Id: checkout_success.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

define('NAVBAR_TITLE_1', '结帐');
define('NAVBAR_TITLE_2', '完成');
define('HEADING_TITLE', '订单程序完成!');
define('TEXT_SUCCESS', '您的订单已经预订成功！我们将会在1-2个工作日内处理您的预订并且邮件通知您！请注意查收您的邮件！');
define('TEXT_NOTIFY_PRODUCTS', '下列所勾选的商品若更新时，请通知我:');
define('TEXT_SEE_ORDERS', '您可以随时回到<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'我的账号\'</a>的网页内，检视您的<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'订单记录\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', '有任何疑问，欢迎直接联络我们<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">店长</a>.');
define('TEXT_THANKS_FOR_SHOPPING', '感谢您在走四方网预订旅游产品!<span style="font-size:12px; font-weight:normal;">快乐旅行&nbsp;&nbsp;由此开始！</span>');

define('TABLE_HEADING_COMMENTS', '我们非常欢迎您留下宝贵的意见或建议，将是我们进步的动力，谢谢');

define('TABLE_HEADING_DOWNLOAD_DATE', '到期日: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' 下载剩余.');
define('HEADING_DOWNLOAD', '从这里下载您的商品:');
define('FOOTER_DOWNLOAD', '您也可以稍后再来\'%s\'下载');

define('TEXT_TOP','----- 选择 ------');
define('TEXT_REFER_FRIEND1',"为什么不推荐给您的朋友然后获取");
define('TEXT_REFER_COMMISSION',"的佣金呢？");
	define('TEXT_RECOMMEND_CAT',"推荐一个目录：");
define('TEXT_OR',"或");
define('TEXT_RECOMMEND_PROD',"推荐一个景点：");
define('TEXT_YOUR_EMAIL',"您的电子邮件地址：");
define('TEXT_FULL_NAME',"姓名：");
define('TEXT_FRIEND_EMAIL',"您的朋友的电子邮件：");
define('TEXT_MESSAGE_TO',"给朋友的留言：");
define('TEXT_MESSAGE_SENT_SUCCESS',"您的消息成功地发给您的朋友。");


//james add for translate for "thanks for shopping!"
define('TEXT_THANKS_ONE','<p style="padding-left:5px;">走四方网（usitrip.com）保证您在本网站的行程预订是绝对安全的。为了持续为您提供优质的服务，避免您遭遇信用卡欺诈，</p><span style="color:#F7860F"><IMG src="image/p7.gif" align="absmiddle" style="margin-left:10px;margin-right:5px;">如果您的消费符合以下任一种情况，我们需要您向我们提供相关证明和数据。</span>');
define('TEXT_THANKS_TWO','
<p style=" padding-left:27px; padding-bottom:5px;"><span style="color:#F7860F">&#8226; 参与旅游的人不是信用卡持有人本人<br />
&#8226; 您的信用卡地址没有通过我们的银行系统核实<br />
&#8226; 单笔消费金额超过$2000.00<br />
</span></p>');

define('TEXT_THANKS_THREE','需要的相关证明和数据：');
define('TEXT_THANKS_FOUR','1.信用卡持有人有效身份证件的影印本(<b>有效身份证件包括您的护照或由美国签发的带有本人签名的驾驶执照或由美国签发的带有本人签名的身份证</b>) ');
/////////
define('TEXT_THANKS_FIVE',"2.填写完整，并签署了信用卡持有人签名和日期的 <u><b><a href='". tep_href_link(FILENAME_ACKNOWLEDGEMENT_CARD_BILLING, 'order_id='.(int)$order_id, 'SSL') ."' target='_blank'>信用卡支付验证书</a></b></u> 。");
define('TEXT_THANKS_SIX',"3.如果信用卡持有人不是参与旅行团的成员，请附上游客的护照影印本。");
define('TEXT_THANKS_SEVEN',"三种提供相关证实档和证明的方式：");
define('TEXT_THANKS_EIGHT',"<div style='padding:15px 0px 0px 25px;'><ul><li>电子邮箱(首选)：将相关证实文件和证明的影印本，扫描本或者数码照片发送至 ".STORE_OWNER_EMAIL_ADDRESS."。</li>
<li>如有问题请拨打客服400电话，谢谢！</li>
</ul>

<ul><li>邮寄地址：<br>".nl2br(db_to_html(strip_tags(STORE_NAME_ADDRESS)))."</li></ul></div>");
define('TEXT_THANKS_NINE','我们非常感谢您的协助和支持，<b>请了解我们将在确认您的相关证明和数据后才会寄送您的旅游行程电子参团凭证（E-Ticket）。</b>');

define('TEXT_MESSAGE_SENT_SUCCESS',"您的消息已经成功地发给您的朋友。");
define('TEXT_PAGE_HEADING1', "为什么不向您的朋友推荐我们的网站并且获得". tep_get_affiliate_percent_display() . "的佣金呢？");
define('TEXT_TOP','----- 选择 ------');
//define('TEXT_REFER_FRIEND1',"为什么不做您参考您的朋友和使变得");
//define('TEXT_REFER_COMMISSION',"委员会？");
define('TEXT_RECOMMEND_CAT',"推荐目录：");
define('TEXT_OR',"或");
define('TEXT_RECOMMEND_PROD',"推荐景点：");
define('TEXT_YOUR_EMAIL',"您的电子邮件地址：");
define('TEXT_FULL_NAME',"您的全名：");
define('TEXT_FRIEND_EMAIL',"您朋友的电子邮件地址：");
define('TEXT_MESSAGE_TO',"要给朋友的消息：");

// zhh added
define('TEXT_AND_THIS',"我对");
define('TEXT_INTEREST_TO_ME',"旅游比较感兴趣.");
define('PAYMENT_WARNING','<font color="#F7860F"><b>温馨提示：如果在5个工作日内没有收您的汇款或支票，系统将会自动取消您的订单，谢谢您对我们工作的理解与支持！</b></font>');
define('PAYMENT_WARNING_FOR_TC','<font color="#F7860F"><b>温馨提示：如果在'.TRAVEL_COMPANION_MAX_PAY_DAY.'个工作日内没有收您的汇款或支票，系统将会自动取消您的订单，谢谢您对我们工作的理解与支持！</b></font>');


define('ENTRY_FIRSTNAME_ERROR','Please enter the Full Name.');
define('ENTRY_ATLEAST_1_EMAIL_ERROR','Please enter at least 1 friend\'s email address on the first line.');

define('PAYMENT_WARNING_AUTHORIZENET','');
?>