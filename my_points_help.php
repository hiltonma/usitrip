<?php
/*
$Id: my_points_help.php, V2.1rc2a 2008/OCT/01 16:04:22 dsa_ Exp $
created by Ben Zukrel, Deep Silver Accessories
http://www.deep-silver.com

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2005 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// ฬ๘ื฿มห
tep_redirect(tep_href_link('faq_points.php'));

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_MY_POINTS_HELP);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php', '', 'NONSSL'));
$breadcrumb->add(NAVBAR_TITLE1, tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL'));

$content = CONTENT_MY_POINTS_HELP;
$javascript = $content . '.js';
if(isset($customer_id) && $customer_id !=''){
	$is_my_account = true;
}
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php'); ?>