<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', 'Order');
  define('HEADING_TITLE', 'Ordering online is easy.');
  define('TEXT_STEP_BY_STEP', 'We\'ll walk you through the process, step by step.');
} else {
  define('NAVBAR_TITLE', 'Login');
  define('HEADING_TITLE', 'Welcome, Please Sign In');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_RETURNING_ADMIN', '后台登录：');
define('HEADING_PASSWORD_FORGOTTEN', '取回密码：');
define('TEXT_RETURNING_ADMIN', 'Staff only!');
define('ENTRY_EMAIL_ADDRESS', 'Email Address:');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_FIRSTNAME', 'First Name:');
define('IMAGE_BUTTON_LOGIN', 'Submit');

define('TEXT_PASSWORD_FORGOTTEN', '如果您忘记密码请点击这里取回！');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Wrong username or password!');
define('TEXT_FORGOTTEN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> First name and password not match!');
define('TEXT_FORGOTTEN_FAIL', 'You have tried more than 3 times. For security reasons, please contact the Webmaster to get a new password.<br>&nbsp;<br>&nbsp;');
define('TEXT_FORGOTTEN_SUCCESS', '新密码已经发送到您的电子邮箱，请稍后去查收新密码！<br>&nbsp;<br>&nbsp;');

define('ADMIN_EMAIL_SUBJECT', 'New Password');
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'You can access the admin panel with the following password. Once you accessed the admin, please change your password immediately!' . "\n\n" . 'Website: %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as your answer would be unread!');
?>
