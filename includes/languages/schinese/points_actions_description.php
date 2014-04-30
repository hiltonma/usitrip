<?php
/*
  $Id: my_points.php, V2.1rc2a 2008/OCT/01 17:41:03 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/
define('NAVBAR_TITLE', '我的账号');
define('NAVBAR_TITLE1', '活动说明');
define('NAVBAR_TITLE_2', '积分活动说明');
define('HEADING_TITLE', '活动说明');


define('TEXT_INTRO','通过以下方式可获得走四方积分：');
define('TITLE_TOUR_BOOK','订购旅游产品');
define('TEXT_TOUR_BOOK',' 在线订购走四方旅游产品，消费1$可获得2个走四方积分奖励。订购越多；积分越多；优惠越多。');

define('TITLE_REFER','推荐给朋友');
define('TEXT_REFER','<a href="'.tep_href_link(FILENAME_REFER_A_FRIEND, 'rewards4fun=true', 'SSL').'" class="sp3">点击此处</a>，推荐您的亲朋好友预订行程，每成功推荐一名，即可获得%d积分。');

define('TITLE_REVIEW_PHOTO','分享旅途感受');
define('TEXT_REVIEW_PHOTO','您可以通过在网站的旅游产品页面中进行评价回访，分享您旅途中的美好风景，美妙感受，经过我们的客服验证后，每一条成功的评论可获得来自走四方网%d积分奖励，每一张成功分享的图片，则是%d积分。');

define('TITLE_FEEDBACK','在第三方网站宣传走四方网');
define('TEXT_FEEDBACK','如果您在其他网站以博客、评论、留言等形式推广了走四方网品牌。请登录“我的走四方”，点击“评论链接回馈”，将您发表内容的链接粘贴到页面所留的方框中， 等待走四方网的工作人员予以验证，通过后即可获取积分30积分。推广多多，积分多多哦！');

define('TEXT_BOTTOM','如想了解更多详细信息，可查看<a href="'. tep_href_link(FILENAME_MY_POINTS_HELP,'', 'NONSSL').'" class="sp3">积分奖励FAQ</a>或是直接<a href="'. tep_href_link(FILENAME_CONTACT_US,'', 'NONSSL').'" class="sp3">联系我们</a>。 ');
?>
