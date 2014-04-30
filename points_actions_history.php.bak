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

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_POINTS_ACTIONS_HISTORY);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php', '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE1, tep_href_link(FILENAME_POINTS_ACTIONS_HISTORY, '', 'SSL'));

?>

<?php
$content = CONTENT_POINTS_ACTIONS_HISTORY;
  $javascript = CONTENT_REFER_FRIEND . '.js';
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>