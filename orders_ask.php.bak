<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/orders_ask.php');
	//seo信息
	$the_title = db_to_html('订单咨询-走四方网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end
  
  $breadcrumb->add(db_to_html('我的账号'), tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(db_to_html('订单咨询'), '');
  
  $is_my_account = true;
  $add_div_footpage_obj = true;
  $validation_include_js = 'true';
  
  $content = 'orders_ask';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
