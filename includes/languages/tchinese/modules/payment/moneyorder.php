<?php
/*
  $Id: moneyorder.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', '支票支付(美國)');
  
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', '
  <ul>
  <li>
  我司接收個人支票（Personal Check），公司支票（Business Check），現金支票（Money Order）, 旅行支票（Travel Check）和銀行支票（Bank Check）。
  <br>
  <span class="color_blue">請將支票付給：<strong>'.MODULE_PAYMENT_MONEYORDER_PAYTO.'</strong></span>
  </li>
  <li><a class="download" href="'.tep_href_link('CheckDraftAuthorizationForm.pdf').'">Download支票支付授權書 </a>(A).  如果是個人支票或公司支票您無須郵寄支票，只需填寫<a class="color_orange" href="'.tep_href_link('CheckDraftAuthorizationForm.pdf').'">支票支付授權書</a>,<br>
傳真或掃描後EMAIL給我們的郵箱<a class="color_orange" href="mailto:service@usitrip.com">service@usitrip.com</a> 即可。  </li>
  <li class="last">(B).  如果是現金支票（Money Order）, 旅行支票（Travel Check）或銀行支票（Bank Check），傳真或掃描後EMAIL給我們的郵箱<a class="color_orange" href="mailto:service@usitrip.com">service@usitrip.com </a>，並請將支票原件通過快遞郵寄給我們公司：Unitedstars International Ltd.， Address: 133B W Garvey Ave, Monterey Park, CA, USA 91754<br>
<br>
<img alt="支票樣板" src="'.HTTP_SERVER.'/image/pic11.jpg"><br><br></li>
  </ul>
  ');
 
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', 
  '
  我司接收個人支票（Personal Check），公司支票（Business Check），現金支票（Money Order）, 旅行支票（Travel Check）和銀行支票（Bank Check）。'."\n".
  '請將支票付給：<strong>'.MODULE_PAYMENT_MONEYORDER_PAYTO.'</strong>'."\n".
  '(A). 如果是個人支票或公司支票您無須郵寄支票，只需填寫<a class="color_orange" href="'.tep_href_link('CheckDraftAuthorizationForm.pdf').'">支票支付授權書</a>,傳真或掃描後EMAIL給我們的郵箱<a class="color_orange" href="mailto:service@usitrip.com">service@usitrip.com</a> 即可。'."\n".
  '(B). 如果是現金支票（Money Order）, 旅行支票（Travel Check）或銀行支票（Bank Check），傳真或掃描後EMAIL給我們的郵箱<a class="color_orange" href="mailto:service@usitrip.com">service@usitrip.com </a>，並請將支票原件通過快遞郵寄給我們公司：Unitedstars International Ltd.， Address: 133B W Garvey Ave, Monterey Park, CA, USA 91754'."\n".
  '<img alt="支票樣板" src="'.HTTP_SERVER.'/image/pic11.jpg">'
  );
?>