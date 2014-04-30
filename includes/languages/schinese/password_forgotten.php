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

define('NAVBAR_TITLE_1', '登录');
define('NAVBAR_TITLE_2', '忘记密码');
define('HEADING_TITLE', '我忘记密码了！');
define('ENTRY_EMAIL_ADDRESS', '电子邮箱:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>提示:</b></font> 这个电子邮件地址没有登录，请检查是否输入错误？并重新输入');
//define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . '寄来的新密码。');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', '走四方网提醒您：请确认您的新密码。');
define('EMAIL_PASSWORD_REMINDER_BODY', '您从' . $REMOTE_ADDR . '要求补发新密码。' . "\n\n" . '您在\'' . STORE_NAME . '\' 的新密码是∶' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', '新的密码已经寄到您的电子邮件地址');
define('TEXT_MAIN', '');

define('SUCCESS_PASSWORD_SENT', '密码已经成功发送到您的电子邮箱，请稍侯查收！');

?>