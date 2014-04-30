<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', '走四方積分回饋');
define('HEADING_TITLE', '走四方積分回饋');


define('TEXT_EARN_POINTS','走四方積分明細：');
define('TEXT_REG_POINTS','新用戶註冊--<strong>'.NEW_SIGNUP_POINT_AMOUNT.'</strong>積分');
define('TEXT_TOUR_POINTS','訂購產品—每消費<strong>1</strong>美元獲得<strong>'. POINTS_PER_AMOUNT_PURCHASE.'</strong>個積分。');
define('TEXT_REFER_POINTS','Email給朋友（朋友需通過郵件點擊註冊）—<strong>'. USE_REFERRAL_SYSTEM.'</strong>積分/位');
define('TEXT_REVIEW_POINTS','分享旅途感受，景點評價（須驗證通過）--<strong>'. USE_POINTS_FOR_REVIEWS .'</strong>積分/條');
define('TEXT_ANS_POINTS','回答問題（須驗證通過）--<strong>'. USE_POINTS_FOR_ANSWER .'</strong>積分');
define('TEXT_PHOTO_POINTS','上傳旅途個人及團體靚照（須驗證通過）--<strong> 100 </strong>積分/張');
define('TEXT_FEEDBACK_POINTS','在第三方網站宣傳走四方網（須驗證通過）--<strong>'. USE_POINTS_FOR_FEEDBACK_APPROVAL .'</strong>積分');
define('TEXT_MORE','走四方積分，高達8%的購物立省優惠！<br />趕快行動起來，贏取走四方積分吧！活動多多，積分多多！');
//define('TEXT_CASH_OUT','當您準備好現金，轉讓這些點到冷戰現金，您可以使用對您以後的usitrip購買。您的積分不會過期，因此您可以使用它們在任何時候。');
define('TEXT_SIGN_IN','我們的會員？&nbsp;&nbsp;<a href="'. tep_href_link(FILENAME_MY_POINTS) .'" class="sp3"><b>登錄</b></a>');
define('TEXT_HOW_WORKS','積分如何兌換：');

define('TEXT_HOW_WORKS_1','通過參與走四方所列之活動贏取積分。');
define('TEXT_HOW_WORKS_2','訂購走四方旅遊產品。');
define('TEXT_HOW_WORKS_3','結賬過程點擊“兌換”按鈕。');

define('TEXT_GET_STARTED','要開始使用，只需申請一個會員帳戶usitrip ，您就會有充分機會獲得獎勵計劃的Rewards4Fun');
define('TEXT_TERMS','計劃條款');
define('TEXT_FAQ','計劃常見問題解答');

/*howard added*/
define('TEXT_POINT_TO_USD','積分現金折扣：  <strong>'.(1/REDEEM_POINT_VALUE).'</strong>積分=<strong>1</strong>美元（USD）');
define('TEXT_REG_BANNER','還等什麼呢？趕快註冊成為走四方網會員賺取積分吧！');
define('TEXT_POINTS_FAQ','積分FAQ');
define('TEXT_POINTS_RULES','積分規則');
define('GO_TO_MY_ACCOUNT','返回我的帳戶');
define('UNREGISTERED_MSN','尚未註冊？');

/* english
define('NAVBAR_TITLE', 'Join Rewards4fun');
define('HEADING_TITLE', 'The Rewards4Fun Program');


define('TEXT_EARN_POINTS','Earn points by completing the following actions on our website:');
define('TEXT_REG_POINTS','Register for a new account - '.NEW_SIGNUP_POINT_AMOUNT.' points');
define('TEXT_TOUR_POINTS','Book a tour - '. POINTS_PER_AMOUNT_PURCHASE.' points for every dollar spent');
define('TEXT_REFER_POINTS','Refer a friend - '. USE_REFERRAL_SYSTEM.' points');
define('TEXT_REVIEW_POINTS','Leave a review (must be approved) - '. USE_POINTS_FOR_REVIEWS .' points');
define('TEXT_ANS_POINTS','Answer a tour related question (must be approved) - '. USE_POINTS_FOR_ANSWER .' points');
define('TEXT_PHOTO_POINTS','Upload a picture (must be approved) - '. USE_POINTS_FOR_PHOTOS .' points');
define('TEXT_FEEDBACK_POINTS','Leave feedback on other sites - '. USE_POINTS_FOR_FEEDBACK_APPROVAL .' points');
define('TEXT_MORE','The more you do, the more points you receive.');
define('TEXT_CASH_OUT','When you\'re ready to cash out, transfer those points into cold hard cash that you can use towards your subsequent usitrip purchases. Your points will never expire, so you can use them at any time.');
define('TEXT_SIGN_IN','Already a member?&nbsp;&nbsp;<a href="'. tep_href_link(FILENAME_MY_POINTS) .'" class="sp3"><b>Sign-In</b></a>');
define('TEXT_HOW_WORKS','Here\'s how it works:');

define('TEXT_HOW_WORKS_1','Earn points by completing any of the actions listed on this page.');
define('TEXT_HOW_WORKS_2','Add a Tour or Vacation Package to your cart.');
define('TEXT_HOW_WORKS_3','During checkout, click the &quot;Redeem Rewards&quot; <br />checkbox near the bottom of the page.');
define('TEXT_HOW_SAVE','Here\'s how much you can save:');
define('TEXT_SAVINGS','Up to 6% savings on Tours<br />Up to 8% savings on Vacation Packages<br />');

define('TEXT_GET_STARTED','To get started, just sign-up for a member account with usitrip, and you\'ll have full access to the Rewards4Fun incentive program');
define('TEXT_TERMS','Program Terms');
define('TEXT_FAQ','Program FAQ');

*/
?>
