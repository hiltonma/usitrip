<?php
/*
  $Id: account_validation.php,v 2.1a 2004/08/10 20:19:27 chaicka Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  define('CATEGORY_ANTIROBOTREG', '檢驗代碼');

  define('ENTRY_ANTIROBOTREG', '依照被顯示輸入信件在箱子之上。<BR><i>這幫助防止自動化的註冊。</i>.');
  define('ENTRY_ANTIROBOTREG_TEXT', '&nbsp;<small><font color="red">*</font></small>');

  define('ERROR_VALIDATION', '錯誤:');
  define('ERROR_VALIDATION_1', '不能獲得註冊資訊');
  define('ERROR_VALIDATION_2', '無效代碼');
  define('ERROR_VALIDATION_3', 'Could not delete validation key');
  define('ERROR_VALIDATION_4', 'Could not optimize database');
  define('ERROR_VALIDATION_5', 'Could not check registration information');
?>