<?php
/*
  $Id: affiliate_payment.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', BOX_AFFILIATE_PAYMENT);
define('HEADING_TITLE', '商家經銷概況: 商家收款記錄');

define('TEXT_AFFILIATE_HEADER', '收款總筆數:');

define('TABLE_HEADING_DATE', '付款日');
define('TABLE_HEADING_PAYMENT', '金額');
define('TABLE_HEADING_STATUS', '付款狀態');
define('TABLE_HEADING_PAYMENT_ID','付款編號');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', '顯示第 <b>%d</b> 到 <b>%d</b> (of <b>%d</b> payments)');
define('TEXT_INFORMATION_PAYMENT_TOTAL', '您已收到的佣金款項總計:');
define('TEXT_NO_PAYMENTS', '您目前尚無任何傭金收入記錄。');

define('TEXT_PAYMENT_HELP', ' <font color="#FFFFFF">[?]</font>');
define('TEXT_PAYMENT', '按下 [?] 即可觀看該項目所代表的意義及詳細說明.');
define('HEADING_PAYMENT_HELP', '項目名稱詳細說明');
define('TEXT_DATE_HELP', '<i>Date</i> represents the date of the payment made to the affiliate.');
define('TEXT_PAYMENT_ID_HELP', '<i>Payment-ID</i> represents the payment number associated to the payment.');
define('TEXT_PAYMENT_HELP', '<i>Affiliate Earnings</i> represents the value of payment made to the affiliate.');
define('TEXT_STATUS_HELP', '<i>Payment Status</i> represents the status of the payment made to the affiliate');
define('TEXT_CLOSE_WINDOW', '關閉視窗 [x]');
define('TEXT_PAGE_HEADING_SUB'," 下面是您網站聯盟帳戶傭金資訊。<br><br>請注意，從團出發日期到傭金待支付會有為期30天的過渡期，用以防止中途因訂單取消而帶來的統計問題。我們在確認後的5-7天內會支付給您應得金額。");
define('TABLE_HEADING_TOURNAME','行程名稱');
?>