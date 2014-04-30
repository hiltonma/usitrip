<?php
/*
  $Id: my_answer.php, V2.1rc2a 2008/OCT/01 16:04:22 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com
  Reformatted by phocea to use CSS display

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
************************************************************/

// Initialisation of some required parameters for the FAQ answers
 if (tep_not_null(POINTS_AUTO_EXPIRES)){
   $answer_expire = 'Reward answer will expire ' . POINTS_AUTO_EXPIRES . ' months from the date issuance.';
 } else {
   $answer_expire = 'Reward answer do not expire and can be accumulated until you decide to use them.';
 }

if (POINTS_PER_AMOUNT_PURCHASE > 1) {
  $point_or_answer = 'answer';
} else {
  $point_or_answer = 'point';
}

define('NAVBAR_TITLE', '我的账号');
define('NAVBAR_TITLE1', '积分奖励FAQ');
define('NAVBAR_TITLE_2', '积分奖励FAQ');
define('HEADING_TITLE', '积分奖励FAQ');

// Definitions of the FAQ questions
define('POINTS_FAQ_1', '<b>什么是走四方积分回馈？</b>');
define('POINTS_FAQ_2', '<b>走四方的积分回馈是如何运作的？</b>');
define('POINTS_FAQ_3', '<b>积分的现金折扣是多少？</b>');
define('POINTS_FAQ_4', '<b>如何兑换积分？</b>');
define('POINTS_FAQ_5', '<b>使用积分可获取的折扣是多少？</b>');
define('POINTS_FAQ_6', '<b>抵换现金最少积分要求。</b>');
define('POINTS_FAQ_7', '<b>特价团也有积分赠送吗？</b>');
define('POINTS_FAQ_8', '<b>我在使用积分购买产品时还可获取积分吗？</b>');
define('POINTS_FAQ_9', '<b>只要注册就可获取积分吗？</b>');
define('POINTS_FAQ_10', '<b>订购旅游产品赚取积分</b>');
define('POINTS_FAQ_11', '<b>推荐给您的朋友获取积分。</b>');
define('POINTS_FAQ_12', '<b>通过分享旅途感受，景点评价，上传旅途个人及团体靓照获取积分。</b>');
define('POINTS_FAQ_13', '<b>走四方积分活动的规则有哪些？</b>');
define('POINTS_FAQ_14', '<b>如果我有关于积分的疑问，应该联系谁？</b>');
//define('POINTS_FAQ_15', '<b>如何参加积分活动？</b>');
define('POINTS_FAQ_15', '<b>我是走四方网的会员，可以参加积分活动吗？</b>');
define('POINTS_FAQ_16', '<b>我能在线检查我的积分历史记录吗？</b>');
//define('POINTS_FAQ_17', '<b>积分能同其它优惠一起使用吗？</b>');
// Definition of the answer for each of the questions:

// FAQ1
define('TEXT_FAQ_1', '为答谢广大客户对走四方网的支持，我们特别推出了走四方积分活动，以积分获取美元现金折扣的方式回馈客户。<br><br>走四方积分回馈相当简单：
您只要参与我们的活动网站的活动即可获取积分。在您订购我们旅游产品的时候，就可以使用所获积分，赚取现金折扣。<br><br>走四方积分回馈于' . db_to_html(tep_get_last_date('USE_POINTS_SYSTEM')) . '正式启动。所有在此日期之后购买旅游产品的顾客均可获取积分。');


// FAQ2
define('TEXT_FAQ_2', '作为一名走四方积分回馈活动的参与者，您可以通过以下方式轻松赢取积分：<br />
注册账号，分享旅途感受，景点评价，上传旅途靓照，订购产品，推荐朋友，以及在第三方网站上宣传走四方网。<br />
每种方式都能为您赢取一定的积分，<a href="' . tep_href_link('points.php') . '" class="sp1">点击这里</a>，查看具体分值。部分活动是需要走四方网验证通过后才能将积分划入您的账号的。<br />
一旦您累积了一定积分，便可在下订单时将这些积分作为现金折扣，获取优惠。<br />
走四方网的每一个旅游产品都有一定的积分分值。您可以在我们的旅游产品页面查看。');


// FAQ3
define('TEXT_FAQ_3', (1/REDEEM_POINT_VALUE).'积分=1美元（'.$currencies->format(1).'）。');

// FAQ4
if(USE_POINTS_FOR_REDEEMED == 'false')  {
	define('TEXT_FAQ_4', '积分兑换功能目前暂时关闭，至于何时再开放此功能，我们强烈建议您经常查看此页面，以保证了解积分活动的最新动态。');
}else{
	define('TEXT_FAQ_4', '预订行程时，在结账过程中的付款信息页面下方，您会看到一个“兑换积分”的按钮。点击按钮，系统会自动为您兑换积分。<br />
在结账确认页面，您会看到此次订单您通过使用积分所获取的现金折扣。一旦订单确认，您所使用的积分会在走四方积分账号中在自动扣除。');
}

// FAQ5
define('TEXT_FAQ_5', '积分可获取的折扣是根据您目前所获的积分，您在走四方网订购的次数，和预定的行程三个因素来决定的。<br />积分现金折扣比例<br />'.TEXT_SAVINGS);


// FAQ6 - conditionnal depending on the point limit value set in admin
if (POINTS_LIMIT_VALUE  > 0)  {
	define('TEXT_FAQ_6', '
	目前，兑换积分的最小值为： <b>' . number_format(POINTS_LIMIT_VALUE) . '</b> 。 <b>(' . $currencies->format(tep_calc_shopping_pvalue(POINTS_LIMIT_VALUE)) . ')' . '</b> <br />
<br />我们强烈建议您经常查看此页面，以保证了解积分活动的最新动态。
	<p align="right"><small>最后更新日期： ' . db_to_html(tep_get_last_date('POINTS_LIMIT_VALUE')) . '</small></p>');
} else {
	define('TEXT_FAQ_6', '目前，我们没有对兑换积分的最小值做要求。<br />请注意如果在您的走四方积分账号中使用积分来购买产品，在结账时您仍然需要选择一种支付方式。');
	
	
}

// FAQ7 - conditionnal depending on value set in admin for giving point on specials
if(USE_POINTS_FOR_SPECIALS == 'false')  {
	define('TEXT_FAQ_7', '目前，特价团暂无积分赠送。
<br /> <br />我们强烈建议您经常查看此页面，以保证了解积分活动的最新动态。
	<p align="right"><small>最后更新日期： ' . db_to_html(tep_get_last_date('USE_POINTS_FOR_SPECIALS')) . '</small></p>');
} else {
	define('TEXT_FAQ_7', '是的。我们在做积分活动时，是将特价团也包括在内的。
<br />');
}

// FAQ8
define('TEXT_FAQ_8','可以。但是您使用积分购买产品后，按照您实际支付的余额来获赠积分，也就是每消费1$可获赠2个走四方积分。');

// FAQ9
define('TEXT_FAQ_9','是的，完成走四方网会员注册即可获取'.NEW_SIGNUP_POINT_AMOUNT.'积分，完善您的个人资料又可获赠100积分，包括完善个人数据部分获得30积分，完善地址部分获得30积分，完善联系方式部分获得'.(NEW_SIGNUP_POINT_AMOUNT-30-30).'积分，完成整个注册流程后可获赠200分。');

// FAQ10
define('TEXT_FAQ_10','订购行程可获得积分奖励，目前的计算方式为：每消费1美元得'.POINTS_PER_AMOUNT_PURCHASE.'个积分。
在您成功购买后，积分自动赠送。所有兑换积分记录在您的 <a href="'.tep_href_link('points_actions_history.php').'" class="sp1">奖励/兑换记录</a>  中可查询。积分没有使用时间限制，您可以在任意时间使用它们。');

// FAQ11
if (tep_not_null(USE_REFERRAL_SYSTEM)){
	define('TEXT_FAQ_11','把我们的网站推荐给您的亲戚或朋友，他们成功下单后，您将获得'.USE_REFERRAL_SYSTEM.'积分奖励。');
}else{
	define('TEXT_FAQ_11', '目前此功能已被禁用。
	<p align="right"><small>最后更新日期： ' . db_to_html(tep_get_last_date('USE_REFERRAL_SYSTEM')) . '</small></p>');
}

// FAQ12
if (tep_not_null(USE_POINTS_FOR_REVIEWS)){
	define('TEXT_FAQ_12', '在走四方网通过分享您的旅游感受景点评价，途中靓照，来帮助我们改进服务， 也可帮助我们的其它访客选择适合的旅游线路和产品。<br /> 
每个通过验证的评论，可获得 <b>'.USE_POINTS_FOR_REVIEWS.'</b> 个积分。<br />
每张通过验证的照片，可获得 <b>100</b> 个积分。<br /><br />
您的评论和照片需要符合以下要求：<br />
 ●必须是原创。<br />
 ●必须是与该旅游景点和线路相关的问题。<br />
 ●不可复制已经发过的内容。<br />
 ●发表的内容需要客观可信。<br />
 ●内容不可包括垃圾信息，商业广告或是相关的链接等。<br />
 ●不该滥用、骚扰、或威胁他人的人身安全。<br /><br />
走四方网保留对以上所述不当评论和照片进行禁止和删除的权利。<br />
走四方网保留其工作人员对内容错别字进行更改的权利。<br />
走四方网不以任何方式为顾客所传评论和照片承担责任。
');
}else{
	define('TEXT_FAQ_12', '目前此功能已被禁用。
	<p align="right"><small>最后更新日期： ' . db_to_html(tep_get_last_date('USE_POINTS_FOR_REVIEWS')) . '</small></p>');
}

// FAQ13
define('TEXT_FAQ_13', '您可以查看 <a href="' . tep_href_link(FILENAME_REWARDS4FUN_TERMS) . '" class="sp1">走四方积分规则</a> 页面，了解到我们积分活动的详细规则。 <br />请注意，我们保留权利改变这种政策而无须另行通知或责任。我们保留对活动规则及政策修改的权利。');

// FAQ14
define('TEXT_FAQ_14', '关于走四方积分活动的任何问题，您都可以 <a href="' . tep_href_link(FILENAME_CONTACT_US) . '" class="sp1">联系我们</a>。');

// FAQ15
//define('TEXT_FAQ_15', '在登录走四方网之后即可轻松加入。详细信息， <a href="' . tep_href_link('points.php') . '" class="sp1">请点击这里</a>。');

// FAQ16
define('TEXT_FAQ_15', '可以。只要您是走四方网的用户，系统自动默认您是参与积分活动的会员。');

// FAQ17
define('TEXT_FAQ_16', '可以。您可以在登录后，在您的账号页面查看，<a href="' . tep_href_link('points_actions_history.php') . '" class="sp1">点击这里</a>。');

// FAQ18
//define('TEXT_FAQ_17', '不可以。走四方积分活动不可以同网站的其它优惠活动一起使用。');


define('TEXT_CANT_FIND','无法找到您所需要的？');	
define('TEXT_CLICK_HERE','点击这里');
define('TEXT_CANT_FIND_NEXT','与我们联系。');


?>
