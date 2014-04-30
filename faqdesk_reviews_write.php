<?php

require('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if (@$HTTP_GET_VARS['action'] == 'process') {
	$customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
	$customer_values = tep_db_fetch_array($customer);
	$date_now = date('Ymd');

	tep_db_query("insert into " . TABLE_FAQDESK_REVIEWS . " (faqdesk_id, customers_id, customers_name, reviews_rating, date_added) values ('" . $HTTP_GET_VARS['faqdesk_id'] . "', '" . $customer_id . "', '" . addslashes($customer_values['customers_firstname']) . ' ' . addslashes($customer_values['customers_lastname']) . "', '" . $HTTP_POST_VARS['rating'] . "', now())");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into " . TABLE_FAQDESK_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . $insert_id . "', '" . $languages_id . "', '" . $HTTP_POST_VARS['review'] . "')");

	tep_redirect(tep_href_link(FILENAME_FAQDESK_REVIEWS_ARTICLE, $HTTP_POST_VARS['get_params'], 'NONSSL'));
}

// lets retrieve all $HTTP_GET_VARS keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
	$get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
	$get_params_back = $get_params;
}

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_REVIEWS_WRITE);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQDESK_REVIEWS_ARTICLE, $get_params, 'NONSSL'));

$product = tep_db_query("select pd.faqdesk_question, p.faqdesk_image from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = '" . $HTTP_GET_VARS['faqdesk_id'] . "' and pd.faqdesk_id = p.faqdesk_id and pd.language_id = '" . $languages_id . "'");

$product_info_values = tep_db_fetch_array($product);

$customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
$customer_values = tep_db_fetch_array($customer);

if ($product_info_values['faqdesk_image'] != '') {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $product_info_values['faqdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$product_info_values['faqdesk_image'], '', '') . '</a>
		</td>
	</tr>
</table>
';
 }

//' . tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image'], $product_info_values['faqdesk_question'] '', '') . '


$javascript = "faqdesk_reviews_write.js";


$content = CONTENT_FAQDESK_REVIEWS_WRITE;
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>


<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			FAQDesk
	version:        		1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>