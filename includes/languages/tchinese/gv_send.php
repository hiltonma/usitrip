<?php
/*
  $Id: gv_send.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

define('HEADING_TITLE', '贈送禮品卷');
define('NAVBAR_TITLE', '贈送禮品卷');
define('EMAIL_SUBJECT', 'Enquiry from ' . STORE_NAME);
define('HEADING_TEXT','<br>請填寫您要贈送禮品卷的詳細信息. 更多信息，請參看 <a href="' . tep_href_link(FILENAME_GV_FAQ,'','NONSSL').'">'.GV_FAQ.'.</a><br>');
define('ENTRY_NAME', '收件人姓名:');
define('ENTRY_EMAIL', '收件人郵箱:');
define('ENTRY_MESSAGE', '消息:');
define('ENTRY_AMOUNT', '禮品卷數量:');
define('ERROR_ENTRY_AMOUNT_CHECK', '&nbsp;&nbsp;<span class="errorText">無效數字</span>');
define('ERROR_ENTRY_EMAIL_ADDRESS_CHECK', '&nbsp;&nbsp;<span class="errorText">無效郵箱</span>');
define('MAIN_MESSAGE', '您已經發送出價值 %s 的禮品卷到 %s 郵箱地址 %s<br><br>在郵件中可以看到<br><br>親愛的 %s<br><br>
                        您收到了一份價值 %s 的禮品卷，禮品卷來自 %s');

define('PERSONAL_MESSAGE', '%s 留言');
define('TEXT_SUCCESS', '恭喜您，您的禮品卷已經成功送出');


define('EMAIL_SEPARATOR', '----------------------------------------------------------------------------------------');
define('EMAIL_GV_TEXT_HEADER', '祝賀您，您已經接受到了一張價值 %s 的禮品卷');
define('EMAIL_GV_TEXT_SUBJECT', '來自 %s 的禮物');
define('EMAIL_GV_FROM', '這個禮品卷由 %s 發送給您');
define('EMAIL_GV_MESSAGE', '附帶 ');
define('EMAIL_GV_SEND_TO', '您好□□, %s');
define('EMAIL_GV_REDEEM', '請點擊下面鏈接兌換禮品卷. 並且填寫兌換碼 %s. (防止出錯)');
define('EMAIL_GV_LINK', '點擊鏈接兌換 ');
define('EMAIL_GV_VISIT', ' 或者瀏覽 ');
define('EMAIL_GV_ENTER', ' 並且輸入兌換碼 ');
define('EMAIL_GV_FIXED_FOOTER', '如果您是用上面的鏈接不能正常兌換, ' . "\n" .
                                '您也可以在我們網站購買結算過程中填入以上禮品卷碼，一樣可以得到優惠.' . "\n\n");
define('EMAIL_GV_SHOP_FOOTER', '');
?>