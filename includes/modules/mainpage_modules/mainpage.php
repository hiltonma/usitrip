<?php
/*
  $Id: mainpage.php,v 2.0 2003/06/13

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- default_specials //-->


<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, HEADING_TITLE);
}
// EOF: Lango Added for template MOD


include(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DEFINE_MAINPAGE);



// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>


<!-- default_specials_eof //-->
