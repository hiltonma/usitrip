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
define('NAVBAR_TITLE','我的賬號');
define('NAVBAR_TITLE_2','我的積分');
define('NAVBAR_TITLE1','獎勵/兌換記錄');

define('HEADING_TITLE','Rewards4Fun行動歷史');

define('HEADING_ORDER_DATE','日期');
define('HEADING_ORDERS_NUMBER','&nbsp;');
define('HEADING_ORDERS_NUMBER','訂單號&狀態');
define('HEADING_ORDERS_STATUS','訂單狀態');
define('HEADING_POINTS_COMMENT','積分記錄');
define('HEADING_POINTS_STATUS','積分狀態');
define('HEADING_POINTS_TOTAL','分值');

define('TEXT_DEFAULT_COMMENT','購買產生的積分');
define('TEXT_DEFAULT_REDEEMED','兌換積分');
define('TEXT_WELCOME_POINTS_COMMENT','歡迎積分');
define('TEXT_VALIDATION_ACCOUNT_POINT_COMMENT','電子郵箱通過驗證獎勵積分');
define('TEXT_ORDER_CANCELLED_COMMENT','訂單撤銷退還積分給我司');
define('TEXT_ORDER_CANCELLED_BUY_NEW_COMMENT','撤銷原單退還積分給我司');
define('TEXT_ORDER_REFUNDED_COMMENT','取消訂單，退款給客人，積分歸還我司！');

define('TEXT_DEFAULT_REFERRAL', '朋友推薦獲得積分');
define('TEXT_DEFAULT_REVIEWS', '評論積分');

define('TEXT_NEW_GROUP_BUY_REFERRAL', '郵件推薦朋友參加團購獲得積分');

define('TEXT_DEFAULT_FEEDBACK_APPROVAL' , '信息反饋點' ) ;
define('TEXT_DEFAULT_REVIEWS_PHOTOS' , '照片上傳獲得積分' ) ;
define('TEXT_DEFAULT_ANSWER' , '回答問題獲得積分' ) ;

define('TEXT_OLD_SITE_WELCOME_POINTS_COMMENT' , '老站客戶轉新站贈予積分' ) ;

define('TEXT_ORDER_HISTORY' ,'查看詳細資料' ) ;
define('TEXT_REVIEW_HISTORY' ,'顯示這一評論。' ) ;

define('TEXT_ORDER_ADMINISTATION' ,'---');
define('TEXT_STATUS_ADMINISTATION' ,'-----------');

define('TEXT_POINTS_PENDING' ,'待定' ) ;
define('TEXT_POINTS_PROCESSING' ,'處理' ) ;
define('TEXT_POINTS_CONFIRMED' ,'確認' ) ;
define('TEXT_POINTS_CANCELLED' ,'取消' ) ;
define('TEXT_POINTS_REDEEMED' ,'兌換' ) ;

define('MY_POINTS_EXPIRE' ,'有效期限為：' ) ;

define('MY_POINTS_CURRENT_BALANCE','<b>積分：</b> %s 點。 <b>價值：</b> %s .');

define('MY_POINTS_HELP_LINK',' 請檢查 <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP) .'" title="獎勵點計劃的常見問題"><u>獎勵</u></a> 點計劃的常見問題以了解更多信息');

define('TEXT_NO_PURCHASES','您還沒有作出任何購買，您沒有點尚未');
define('TEXT_NO_POINTS','您目前暫無積分，因此不能顯示。');

define('TEXT_DISPLAY_NUMBER_OF_RECORDS','顯示 <b>%d</b> 至 <b>%d</b> （共有 <b>%d</b> 條積分記錄）');

define('TEXT_INTRO_STRING_TOP','以下是您所有積分歷史記錄。');
define('TEXT_INTRO_STRING_BOTTOM','兌換方式： 在預定行程支付（checkout）過程中點擊“兌換積分”按鈕。 <br><br>');

define('TEXT_VOTE_POINTS_COMMENT','參加走四方調查活動獲得積分');
define('USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT','置頂結伴同遊貼消費積分！');

define('TEXT_POINTCARD_REGISTER','使用走四方網會員積分卡登錄網站獎勵積分');
define('TEXT_POINTCARD_PROFILE','使用走四方網會員積分卡註冊並完善個人資料獎勵積分');
define('TEXT_POINTCARD_LOGIN','走四方網會員積分卡用戶每日登錄獎勵積分');


/* English
define('NAVBAR_TITLE','Points Information');

define('HEADING_TITLE','Rewards4Fun Action History');

define('HEADING_ORDER_DATE','Date');
define('HEADING_ORDERS_NUMBER','Order No. & Status');
define('HEADING_ORDERS_STATUS','Order Status');
define('HEADING_POINTS_COMMENT','Comments');
define('HEADING_POINTS_STATUS','Points Status');
define('HEADING_POINTS_TOTAL','Points');

define('TEXT_DEFAULT_COMMENT','Purchase Points');
define('TEXT_DEFAULT_REDEEMED','Redeemed Points');
define('TEXT_WELCOME_POINTS_COMMENT','Welcome Points');

define('TEXT_DEFAULT_REFERRAL','Friend Referral Points');
define('TEXT_DEFAULT_REVIEWS','Review Points');
define('TEXT_DEFAULT_FEEDBACK_APPROVAL','Feedback Points');
define('TEXT_DEFAULT_REVIEWS_PHOTOS','Photo Upload Points');
define('TEXT_DEFAULT_ANSWER','Question-Answer');

define('TEXT_ORDER_HISTORY','View details for order no.');
define('TEXT_REVIEW_HISTORY','Show this Review.');

define('TEXT_ORDER_ADMINISTATION','---');
define('TEXT_STATUS_ADMINISTATION','-----------');

define('TEXT_POINTS_PENDING','Pending');
define('TEXT_POINTS_PROCESSING','Processing');
define('TEXT_POINTS_CONFIRMED','Confirmed');
define('TEXT_POINTS_CANCELLED','Cancelled');
define('TEXT_POINTS_REDEEMED','Redeemed');

define('MY_POINTS_EXPIRE','Expire at:');
define('MY_POINTS_CURRENT_BALANCE','<b>Point Balance :</b> %s points. <b>Valued at :</b> %s .');

define('MY_POINTS_HELP_LINK',' Please check the <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP) .'" title="Reward Point Program FAQ"><u>Reward</u></a> Point Program FAQ for more information.');

define('TEXT_NO_PURCHASES','You have not yet made any purchases, and you don\'t have points yet');
define('TEXT_NO_POINTS','You don\'t have Qualified Points yet.');

define('TEXT_DISPLAY_NUMBER_OF_RECORDS','Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> points records)');

*/
?>