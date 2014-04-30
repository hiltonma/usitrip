<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	FaqDesk
	version:		1.2.5
	date:			2003-09-01
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>


<?php

// set application wide parameters
// this query set is for FAQDesk
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

if ( DISPLAY_FAQS_CATAGORY_BOX ) {

$do_we_have_categories_faq_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, "
. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $value . "'
and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
}

$faqdesk_check = tep_db_num_rows($do_we_have_categories_faq_query);
if ($faqdesk_check > 0) {

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Return true if the category has subcategories
// TABLES: categories

function FAQDesk_box_has_category_subcategories($category_id) {
	$child_faqdesk_category_query = tep_db_query("select count(*) as count from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
	$child_category = tep_db_fetch_array($child_faqdesk_category_query);

	if ($child_category['count'] > 0) {
		return true;
	} else {
		return false;
	}
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
function FAQDesk_box_count_products_in_category($category_id, $include_inactive = false) {
	$products_faqdesk_count = 0;
	if ($include_inactive) {
		$products_faqdesk_faqdesk_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_TO_CATEGORIES . "
		p2c where p.faqdesk_id = p2c.faqdesk_id and p2c.categories_id = '" . $category_id . "'");
	} else {
		$products_faqdesk_faqdesk_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_TO_CATEGORIES .
		" p2c where p.faqdesk_id = p2c.faqdesk_id and p.faqdesk_status = '1' and p2c.categories_id = '" . $category_id . "'");
	}
	$products_faqdesk = tep_db_fetch_array($products_faqdesk_faqdesk_query);
	$products_faqdesk_count += $products_faqdesk['total'];

	if (USE_RECURSIVE_COUNT == 'true') {
		$child_categories_query = tep_db_query("select categories_id from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
		if (tep_db_num_rows($child_categories_query)) {
			while ($child_categories = tep_db_fetch_array($child_categories_query)) {
				$products_faqdesk_count += FAQDesk_box_count_products_in_category($child_categories['categories_id'], $include_inactive);
			}
		}
	}

return $products_faqdesk_count;
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function FAQDesk_show_category($counter) {
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
global $foo_faqdesk, $categories_faqdesk_string, $id_faq;

for ($a=0; $a<$foo_faqdesk[$counter]['level']; $a++) {
	$categories_faqdesk_string .= "&nbsp;&nbsp;";
}

$categories_faqdesk_string .= '<a href="';

if ($foo_faqdesk[$counter]['parent'] == 0) {
	$faqPath_new = 'faqPath=' . $counter;
} else {
	$faqPath_new = 'faqPath=' . $foo_faqdesk[$counter]['path'];
}

$categories_faqdesk_string .= tep_href_link(FILENAME_FAQDESK_INDEX, $faqPath_new);
$categories_faqdesk_string .= '">';

if ( ($id_faq) && (in_array($counter, $id_faq)) ) {
	$categories_faqdesk_string .= '<b>';
}

// display category name
$categories_faqdesk_string .= $foo_faqdesk[$counter]['name'];

if ( ($id_faq) && (in_array($counter, $id_faq)) ) {
	$categories_faqdesk_string .= '</b>';
}

if (FAQDesk_box_has_category_subcategories($counter)) {
	$categories_faqdesk_string .= '-&gt;';
}

$categories_faqdesk_string .= '</a>';

if (SHOW_COUNTS == 'true') {
	$products_faqdesk_in_category = FAQDesk_box_count_products_in_category($counter);
	if ($products_faqdesk_in_category > 0) {
		$categories_faqdesk_string .= '&nbsp;(' . $products_faqdesk_in_category . ')';
	}
}

$categories_faqdesk_string .= '<br>';

if ($foo_faqdesk[$counter]['next_id']) {
	FAQDesk_show_category($foo_faqdesk[$counter]['next_id']);
}

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


?>

<!-- FAQ desk categories //-->
	<tr>
		<td>

<?php
$info_box_contents = array();
$info_box_contents[] = array(
		'align' => 'left',
		'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FAQDESK_CATEGORIES . '</font>'
	);

new infoBoxHeading($info_box_contents, false, false);

$categories_faqdesk_string = '';

$categories_faqdesk_query = tep_db_query(
"select c.categories_id, cd.categories_name, c.parent_id from "
. TABLE_FAQDESK_CATEGORIES . " c, "
. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd
where c.catagory_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='"
. $languages_id ."' order by sort_order, cd.categories_name"
);

while ($categories_faqdesk = tep_db_fetch_array($categories_faqdesk_query))  {
	$foo_faqdesk[$categories_faqdesk['categories_id']] = array(
		'name' => $categories_faqdesk['categories_name'],
		'parent' => $categories_faqdesk['parent_id'],
		'level' => 0,
		'path' => $categories_faqdesk['categories_id'],
		'next_id' => false
	);

	if (isset($prev_id)) {
		$foo_faqdesk[$prev_id]['next_id'] = $categories_faqdesk['categories_id'];
	}

	$prev_id = $categories_faqdesk['categories_id'];

	if (!isset($counter)) {
		$counter = $categories_faqdesk['categories_id'];
	}
}

//------------------------
if ($faqPath) {
	$new_path = '';
	$id_faq = split('_', $faqPath);
	reset($id_faq);
	while (list($key, $value) = each($id_faq)) {
		unset($prev_id);
		unset($first_id);

		$categories_faqdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, "
		. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $value . "'
		and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");

		$category_faqdesk_check = tep_db_num_rows($categories_faqdesk_query);
		if ($category_faqdesk_check > 0) {
			$new_path .= $value;
			while ($row = tep_db_fetch_array($categories_faqdesk_query)) {
				$foo_faqdesk[$row['categories_id']] = array(
					'name' => $row['categories_name'],
					'parent' => $row['parent_id'],
					'level' => $key+1,
					'path' => $new_path . '_' . $row['categories_id'],
					'next_id' => false
				);

				if (isset($prev_id)) {
					$foo_faqdesk[$prev_id]['next_id'] = $row['categories_id'];
				}

				$prev_id = $row['categories_id'];

				if (!isset($first_id)) {
					$first_id = $row['categories_id'];
				}

				$last_id = $row['categories_id'];
			}
			$foo_faqdesk[$last_id]['next_id'] = $foo_faqdesk[$value]['next_id'];
			$foo_faqdesk[$value]['next_id'] = $first_id;
			$new_path .= '_';
		} else {
			break;
		}
	}
}

FAQDesk_show_category($counter);

$info_box_contents = array();
$info_box_contents[] = array(
		'align' => 'left',
		'text'  => $categories_faqdesk_string
	);

new infoBox($info_box_contents);
  if (TEMPLATE_INCLUDE_FOOTER =='true'){
      $info_box_contents = array();
       $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                   );
  new infoboxFooter($info_box_contents);
 }

?>

		</td>
	</tr>
<!-- categories_eof //-->

<?php
}
?>