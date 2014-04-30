<?php
/*
  $Id: password_forgotten.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

define('NAVBAR_TITLE_1', '登錄');
define('NAVBAR_TITLE_2', '忘記密碼');
define('HEADING_TITLE', '我忘記密碼了！');
define('ENTRY_EMAIL_ADDRESS', '電子郵箱:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>提示:</b></font> 這個電子郵件地址沒有登錄，請檢查是否輸入錯誤？並重新輸入');
//define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . '寄來的新密碼。');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', '走四方網提醒您：請確認您的新密碼。');
define('EMAIL_PASSWORD_REMINDER_BODY', '您從' . $REMOTE_ADDR . '要求補發新密碼。' . "\n\n" . '您在\'' . STORE_NAME . '\' 的新密碼是：' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', '新的密碼已經寄到您的電子郵件地址');
define('TEXT_MAIN', '');

define('SUCCESS_PASSWORD_SENT', '密碼已經成功發送到您的電子郵箱，請稍侯查收！');

?>