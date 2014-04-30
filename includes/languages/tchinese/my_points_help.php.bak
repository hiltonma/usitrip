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

define('NAVBAR_TITLE', '我的賬號');
define('NAVBAR_TITLE1', '積分獎勵FAQ');
define('NAVBAR_TITLE_2', '積分獎勵FAQ');
define('HEADING_TITLE', '積分獎勵FAQ');

// Definitions of the FAQ questions
define('POINTS_FAQ_1', '<b>什麼是走四方積分回饋？</b>');
define('POINTS_FAQ_2', '<b>走四方的積分回饋是如何運作的？</b>');
define('POINTS_FAQ_3', '<b>積分的現金折扣是多少？</b>');
define('POINTS_FAQ_4', '<b>如何兌換積分？</b>');
define('POINTS_FAQ_5', '<b>使用積分可獲取的折扣是多少？</b>');
define('POINTS_FAQ_6', '<b>抵換先金最少積分要求。</b>');
define('POINTS_FAQ_7', '<b>特價團也有積分贈送嗎？</b>');
define('POINTS_FAQ_8', '<b>我在使用積分購買產品時還可獲取積分嗎？</b>');
define('POINTS_FAQ_9', '<b>只要註冊就可獲取積分嗎？</b>');
define('POINTS_FAQ_10', '<b>訂購旅遊產品賺取積分</b>');
define('POINTS_FAQ_11', '<b>推薦給您的朋友獲取積分。</b>');
define('POINTS_FAQ_12', '<b>通過分享旅途感受，景點評價，上傳旅途個人及團體靚照獲取積分。</b>');
define('POINTS_FAQ_13', '<b>走四方積分活動的規則有哪些？</b>');
define('POINTS_FAQ_14', '<b>如果我有關於積分的疑問，應該聯繫誰？</b>');
//define('POINTS_FAQ_15', '<b>如何參加積分活動？</b>');
define('POINTS_FAQ_15', '<b>我是走四方網的會員，可以參加積分活動嗎？</b>');
define('POINTS_FAQ_16', '<b>我能線上檢查我的積分歷史記錄嗎？</b>');
//define('POINTS_FAQ_18', '<b>積分能同其他優惠一起使用嗎？</b>');
// Definition of the answer for each of the questions:

// FAQ1
define('TEXT_FAQ_1', '為答謝廣大客戶對走四方網的支持，我們特別推出了走四方積分活動，以積分獲取美元現金折扣的方式回饋客戶。<br><br>走四方積分回饋相當簡單：
您只要參與我們的活動網站的活動即可獲取積分。在您訂購我們旅遊產品的時候，就可以使用所獲積分，賺取現金折扣。<br><br>走四方積分回饋於' . db_to_html(tep_get_last_date('USE_POINTS_SYSTEM')) . '正式啟動。所有在此日期之後購買旅遊產品的顧客均可獲取積分。');


// FAQ2
define('TEXT_FAQ_2', '作為一名走四方積分回饋活動的參與者，您可以通過以下方式輕鬆贏取積分：<br />
註冊帳號，分享旅途感受，景點評價，上傳旅途靚照，訂購產品，推薦朋友，以及在第三方網站上宣傳走四方網。<br />
每種方式都能為您贏取一定的積分，<a href="' . tep_href_link('points.php') . '" class="sp1">點擊這裏</a>，查看具體分值。部分活動是需要走四方網驗證通過後才能將積分劃入您的帳號的。<br />
一旦您累積了一定積分，便可在下訂單時將這些積分作為現金折扣，獲取優惠。<br />
走四方網的每一個旅遊產品都有一定的積分分值。您可以在我們的旅遊產品頁面查看。');


// FAQ3
define('TEXT_FAQ_3', '就目前而言，'.(1/REDEEM_POINT_VALUE).'積分=1美元（'.$currencies->format(1).'）。');

// FAQ4
if(USE_POINTS_FOR_REDEEMED == 'false')  {
	define('TEXT_FAQ_4', '積分兌換功能目前暫時關閉，至於何時再開放此功能，我們強烈建議您經常查看此頁面，以保證瞭解積分活動的最新動態。');
}else{
	define('TEXT_FAQ_4', '預訂行程時，在結賬過程中的付款信息頁面下方，您會看到一個“兌換積分”的按鈕。點擊按鈕，系統會自動為您兌換積分。<br /> 在結賬確認頁面，您會看到此次訂單您通過使用積分所獲取的現金折扣。一旦訂單確認，您所使用的積分會在走四方積分賬號中在自動扣除。');
}

// FAQ5
define('TEXT_FAQ_5', '積分可獲取的折扣是根據您目前所獲的積分，您在走四方網訂購的次數，和預定的行程三個因素來決定的。<br />積分現金折扣比例<br />'.TEXT_SAVINGS);

// FAQ6 - conditionnal depending on the point limit value set in admin
if (POINTS_LIMIT_VALUE  > 0)  {
	define('TEXT_FAQ_6', '
	目前，兌換積分的最小值為： <b>' . number_format(POINTS_LIMIT_VALUE) . '</b> 。 <b>(' . $currencies->format(tep_calc_shopping_pvalue(POINTS_LIMIT_VALUE)) . ')' . '</b> <br />
<br />我們強烈建議您經常查看此頁面，以保證瞭解積分活動的最新動態。
	<p align="right"><small>最後更新日期： ' . db_to_html(tep_get_last_date('POINTS_LIMIT_VALUE')) . '</small></p>');
} else {
	define('TEXT_FAQ_6', '目前，我們沒有對兌換積分的最小值做要求。<br /><br />請注意如果在您的走四方積分賬號中使用積分來購買產品，在結賬時您仍然需要選擇一種支付方式。');
	
	
}

// FAQ7 - conditionnal depending on value set in admin for giving point on specials
if(USE_POINTS_FOR_SPECIALS == 'false')  {
	define('TEXT_FAQ_7', '目前，特價團暫無積分贈送。
<br /> <br />我們強烈建議您經常查看此頁面，以保證瞭解積分活動的最新動態。
	<p align="right"><small>最後更新日期： ' . db_to_html(tep_get_last_date('USE_POINTS_FOR_SPECIALS')) . '</small></p>');
} else {
	define('TEXT_FAQ_7', '是的。我們在做積分活動時，是將特價團也包括在內的。');
}

// FAQ8
define('TEXT_FAQ_8','可以。但是您使用積分購買產品後，按照您實際支付的余額來獲贈積分，也就是每消費1$可獲贈2個走四方積分。');

// FAQ9
define('TEXT_FAQ_9','是的，完成走四方網會員註冊即可獲取'.NEW_SIGNUP_POINT_AMOUNT.'積分，完善您的個人資料又可獲贈100積分，包括完善個人數據部分獲得30積分，完善地址部分獲得30積分，完善聯系方式部分獲得'.(NEW_SIGNUP_POINT_AMOUNT-30-30).'積分，完成整個注冊流程後可獲贈200分。');

// FAQ10
define('TEXT_FAQ_10','訂購行程可獲得積分獎勵，目前的計算方式為：每消費1美元得'.POINTS_PER_AMOUNT_PURCHASE.'個積分。 在您成功購買後，積分自動贈送。所有兌換積分記錄在您的 <a href="'.tep_href_link('points_actions_history.php').'" class="sp1">獎勵/兌換記錄</a>  中可查詢。積分沒有使用時間限制，您可以在任意時間使用它們。');

// FAQ11
if (tep_not_null(USE_REFERRAL_SYSTEM)){
	define('TEXT_FAQ_11','把我們的網站推薦給您的親戚或朋友，他們成功下單後，您將獲得'.USE_REFERRAL_SYSTEM.'積分獎勵。');
}else{
	define('TEXT_FAQ_11', '目前此功能已被禁用。
	<p align="right"><small>最後更新日期： ' . db_to_html(tep_get_last_date('USE_REFERRAL_SYSTEM')) . '</small></p>');
}

// FAQ12
if (tep_not_null(USE_POINTS_FOR_REVIEWS)){
	define('TEXT_FAQ_12', '在走四方網通過分享您的旅遊感受景點評價，途中靚照，來幫助我們改進服務， 也可幫助我們的其他訪客選擇適合的旅遊線路和產品。<br /> <br />
每個通過驗證的評論，可獲得 <b>'.USE_POINTS_FOR_REVIEWS.'</b> 個積分。<br />
每張通過驗證的照片，可獲得 <b>100</b> 個積分。<br /><br />
您的評論和照片需要符合以下要求：<br />
 ●必須是原創。<br />
 ●必須是與該旅遊景點和線路相關的問題。<br />
 ●不可複製已經發過的內容。<br />
 ●發表的內容需要客觀可信。<br />
 ●內容不可包括垃圾資訊，商業廣告或是相關的鏈結等。<br />
 ●不該濫用、騷擾、或威脅他人的人身安全。<br /><br />
走四方網保留對以上所述不當評論和照片進行禁止和刪除的權利。<br />
走四方網保留其工作人員對內容錯別字進行更改的權利。<br />
走四方網不以任何方式為顧客所傳評論和照片承擔責任。
');
}else{
	define('TEXT_FAQ_12', '目前此功能已被禁用。
	<p align="right"><small>最後更新日期： ' . db_to_html(tep_get_last_date('USE_POINTS_FOR_REVIEWS')) . '</small></p>');
}

// FAQ13
define('TEXT_FAQ_13', '您可以查看 <a href="' . tep_href_link(FILENAME_REWARDS4FUN_TERMS) . '" class="sp1">走四方積分規則</a> 頁面，瞭解到我們積分活動的詳細規則。 <br />請注意，我們保留權利改變這種政策而無須另行通知或責任。我們保留對活動規則及政策修改的權利。');

// FAQ14
define('TEXT_FAQ_14', '關於走四方積分活動的任何問題，您都可以 <a href="' . tep_href_link(FILENAME_CONTACT_US) . '" class="sp1">聯繫我們</a>');

// FAQ15
//define('TEXT_FAQ_15', '在登錄走四方網之後即可輕鬆加入。詳細資訊， <a href="' . tep_href_link('points.php') . '" class="sp1">請點擊這裏</a>。');

// FAQ16
define('TEXT_FAQ_15', '可以。只要您是走四方網的用戶，系統自動默認您是參與積分活動的會員。');

// FAQ17
define('TEXT_FAQ_16', '可以。您可以在登錄後，在您的賬號頁面查看，<a href="' . tep_href_link('points_actions_history.php') . '" class="sp1">點擊這裏</a>。');

// FAQ18
//define('TEXT_FAQ_18', '不可以。走四方積分活動不可以同網站的其他優惠活動一起使用。');


define('TEXT_CANT_FIND','無法找到您所需要的？');	
define('TEXT_CLICK_HERE','點擊這裡');
define('TEXT_CANT_FIND_NEXT','與我們聯繫。');


?>