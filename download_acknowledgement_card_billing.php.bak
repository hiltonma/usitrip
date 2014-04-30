<?php
/*
  $Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

/*  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
 	 tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));  
  }
*/
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DOWNLOAD_ACKNOWLEDGEMENT_CARD_BILLING);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_DOWNLOAD_ACKNOWLEDGEMENT_CARD_BILLING, '', 'SSL'));

	//seo信息
	$the_title = db_to_html('信用卡支付验证书-走四方旅游网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end
  $add_div_footpage_obj = true;
  $content = CONTENT_DOWNLOAD_ACKNOWLEDGEMENT_CARD_BILLING;
  //$javascript = $content . '.js';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>