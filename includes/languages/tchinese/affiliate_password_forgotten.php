<?php
/*
  $Id: affiliate_password_forgotten.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', '登陸');
define('NAVBAR_TITLE_2', '密碼找回');
define('HEADING_TITLE', '密碼找回');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>消息:</b></font> 該郵件地址不存在，請重新輸入.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - 新的聯盟密碼');
define('EMAIL_PASSWORD_REMINDER_BODY', '新密碼來自 ' . $REMOTE_ADDR . '.' . "\n\n" . '您的 \'' . STORE_NAME . '\' 新聯盟是:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', '您的新密碼已經發送到您填寫的郵箱裏');
?>