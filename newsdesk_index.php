<?php
require('includes/application_top.php');
require('includes/functions/newsdesk_general.php');

// set application wide parameters
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}
// calculate category path
if ($HTTP_GET_VARS['newsPath']) {
	$newsPath = $HTTP_GET_VARS['newsPath'];
} elseif ($HTTP_GET_VARS['newsdesk_id'] && !$HTTP_GET_VARS['manufacturers_id']) {
	$newsPath = newsdesk_get_product_path($HTTP_GET_VARS['newsdesk_id']);
} else {
	$newsPath = '';
}

if (strlen($newsPath) > 0) {
	$newsPath_array = newsdesk_parse_category_path($newsPath);
	$newsPath = implode('_', $newsPath_array);
	$current_category_id = $newsPath_array[(sizeof($newsPath_array)-1)];
} else {
	$current_category_id = 0;
}

if (isset($newsPath_array)) {
	$n = sizeof($newsPath_array);
	for ($i = 0; $i < $n; $i++) {
		$categories_query = tep_db_query(
		"select categories_name from " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $newsPath_array[$i]
		. "' and language_id='" . $languages_id . "'"
		);
		if (tep_db_num_rows($categories_query) > 0) {
			$categories = tep_db_fetch_array($categories_query);
			$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath='
			. implode('_', array_slice($newsPath_array, 0, ($i+1)))));
		} else {
			break;
		}
	}
/*
  } elseif ($HTTP_GET_VARS['manufacturers_id']) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
    $manufacturers = tep_db_fetch_array($manufacturers_query);
    $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_NEWSDESK_INDEX, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));
  }
if ($HTTP_GET_VARS['newsdesk_id']) {
	$model_query = tep_db_query("select products_model from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "'");
//    $model = tep_db_fetch_array($model_query);
	$breadcrumb->add($model['products_model'], tep_href_link(FILENAME_NEWSDESK_INFO, 'newsPath=' . $newsPath . '&newsdesk_id='
	. $HTTP_GET_VARS['newsdesk_id']));
}
*/
}


// the following newsPath references come from application_top.php
$category_depth = 'top';
if ($newsPath) {
	$category_parent_query = tep_db_query(
	"select count(*) as total from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . $current_category_id . "'"
	);

	$category_parent = tep_db_fetch_array($category_parent_query);
	if ($category_parent['total'] > 0) {
		$category_depth = 'nested'; // navigate through the categories
	} else {
		$category_depth = 'products'; // category has no products, but display the 'no products' message
	}
}

//}  // I lost track to what loop this is closing ... ugh I hate when this happens
// ------------------------------------------------------------------------------------------------------------------------------------------
// Output a form pull down menu
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_show_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false) {

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

	if ($required) $field .= NEWS_TEXT_FIELD_REQUIRED;

return $field;
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_INDEX);


//$javascript = "support.js";


$content = CONTENT_NEWSDESK_INDEX;
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

	script name:	NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	author:				Carsten aka moyashi
	web site:			www..com
	modified code by:		Wolfen aka 241
*/
?>
