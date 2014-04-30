<?php
/*
  $Id: my_points.php, V2.1rc2a 2008/OCT/01 16:04:22 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_FEEDBACK_APPROVAL);
  
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') ) {
  	#### Points/Rewards Module V2.1rc2a BOF ####*/
	 if(isset($customer_id) && $customer_id!=''){
		if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_FEEDBACK_APPROVAL))) {
			$points_toadd = USE_POINTS_FOR_FEEDBACK_APPROVAL;
			$comment = 'TEXT_DEFAULT_FEEDBACK_APPROVAL';
			$points_type = 'FA';
			$feedback_url = tep_db_prepare_input($HTTP_POST_VARS['feedback_link_url']);
			tep_add_pending_points($customer_id, '', $points_toadd, $comment, $points_type, $feedback_url);
		}
	 }
	#### Points/Rewards Module V2.1rc2a EOF ####*/
tep_redirect(tep_href_link(FILENAME_FEEDBACK_APPROVAL, 'msg=success', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php', '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE1, tep_href_link(FILENAME_FEEDBACK_APPROVAL, '', 'SSL'));
$validation_include_js = 'true';
$content = CONTENT_FEEDBACK_APPROVAL;
 $is_my_account = true;
  //$javascript = CONTENT_REFER_FRIEND . '.js';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>