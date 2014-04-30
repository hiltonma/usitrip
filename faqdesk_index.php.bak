<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');
require('includes/classes/split_page_results_old.php');

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_INDEX);

// set application wide parameters --- this query set is for FAQDesk
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}
// calculate category path
if ($HTTP_GET_VARS['faqPath']) {
	$faqPath = $HTTP_GET_VARS['faqPath'];
} elseif ($HTTP_GET_VARS['faqdesk_id']) {
	$faqPath = faqdesk_get_product_path($HTTP_GET_VARS['faqdesk_id']);
} else {
	$faqPath = '';
}
// caluculate something ??? like what?  current category??
if (strlen($faqPath) > 0) {
	$faqPath_array = faqdesk_parse_category_path($faqPath);
	$faqPath = implode('_', $faqPath_array);
	$current_category_id = $faqPath_array[(sizeof($faqPath_array)-1)];
} else {
	$current_category_id = 0;
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQDESK_INDEX, '', 'NONSSL'));
//$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQDESK_INDEX, '', 'NONSSL'));
//$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=', 'NONSSL'));

if (isset($faqPath_array)) {
	$n = sizeof($faqPath_array);
	for ($i = 0; $i < $n; $i++) {
		$categories_query = tep_db_query(
		"select categories_name from " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $faqPath_array[$i] 
		. "' and language_id='" . $languages_id . "'"
		);
		if (tep_db_num_rows($categories_query) > 0) {
			$categories = tep_db_fetch_array($categories_query);
			$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=' 
			. implode('_', array_slice($faqPath_array, 0, ($i+1)))));
		} else {
			break;
		}
	}
/*
  if ($HTTP_GET_VARS['faqPath']) {
    $categories_query = tep_db_query("select categories_name from " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $HTTP_GET_VARS['categories_id'] . "'");
    $categories = tep_db_fetch_array($categories_query);
    $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=' . $faqPath . '&categories_id=' . $HTTP_GET_VARS['categories_id']));
//			$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=' 
//			. implode('_', array_slice($faqPath_array, 0, ($i+1)))));
  }
*/
}

// the following faqPath references come from application_top.php
$category_depth = 'top';
if ($faqPath) {
///*
// IF this area is included problems occur when trying to view unpopulated catagories
// OR!!! is this not a but???
// Well which the @!#p$@ is it?  Regular products shows the catagory while the below won't @!#@&@

	$categories_products_query = tep_db_query(
	"select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where categories_id = '" . $current_category_id . "'"
	);

	$cateqories_products = tep_db_fetch_array($categories_products_query);
	if ($cateqories_products['total'] > 0) {
		$category_depth = 'products'; // display products
	} else {
	$category_parent_query = tep_db_query(
	"select count(*) as total from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $current_category_id . "'"
	);

	$category_parent = tep_db_fetch_array($category_parent_query);
		if ($category_parent['total'] > 0) {
			$category_depth = 'nested'; // navigate through the categories
		} else {
			$category_depth = 'products'; // category has no products, but display the 'no products' message
		}
	}
}
// ------------------------------------------------------------------------------------------------------------------------------------------
// Output a form pull down menu
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function faqdesk_show_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false) {

$field = '<select name="' . $name . '"';
if ($params) $field .= ' ' . $params;
	$field .= '>';
	for ($i=0; $i<sizeof($values); $i++) {
		$field .= '<option value="' . $values[$i]['id'] . '"';
		if ( ($GLOBALS[$name] == $values[$i]['id']) || ($default == $values[$i]['id']) ) {
			$field .= ' SELECTED';
		}
		$field .= '>' . $values[$i]['text'] . '</option>';
	}
	$field .= '</select>';
	$field .= tep_hide_session_id();

	if ($required) $field .= FAQ_TEXT_FIELD_REQUIRED;

return $field;
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------

//$javascript = "support.js";


$content = CONTENT_FAQDESK_INDEX;
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
	author:				Carsten aka moyashi
	web site:			www..com
	modified code by:		Wolfen aka 241
*/
?>
