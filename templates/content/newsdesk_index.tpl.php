<?php
if ($category_depth == 'nested') {
	$category_query = tep_db_query(
	"select cd.categories_name, c.categories_image from " . TABLE_NEWSDESK_CATEGORIES . " c, " .  newsdesk_categories_description .
	" cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '"
	. $languages_id . "'"
	);

	$category = tep_db_fetch_array($category_query);

?>

    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">

<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
                        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
		<td class="pageHeading" align="right">
<?php
if (($category['categories_image'] = 'NULL') or ($category['categories_image'] = '')) {
echo tep_draw_separator('pixel_trans.gif', '1', '1');
} else {
echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
?>
</td>
          </tr>
	          </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>

	<tr>

<?php
if ($newsPath && ereg('_', $newsPath)) {
// check to see if there are deeper categories within the current category
	$category_links = array_reverse($newsPath_array);
	$size = sizeof($category_links);
	for($i=0; $i<$size; $i++) {
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from "
		. TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $category_links[$i]
		. "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
		if (tep_db_num_rows($categories_query) < 1) {
// do nothing, go through the loop
		} else {
			break; // we've found the deepest category the customer is in
		}
	}
} else {
$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from "
. TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '"
. $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id
. "' order by sort_order, cd.categories_name");
}

if (($categories['categories_image'] = 'NULL') or categories) {
} else {
$print_echo = tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'],
SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT);
}

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
	$newsPath_new = newsdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

	echo '
<td align="left" class="smallText" style="width: ' . $width . '" valign="top">
<a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, $newsPath_new, 'NONSSL') . '">' .  $print_echo
. '<br>' . $categories['categories_name'] . '</a></td>' . "\n";

	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '</tr>' . "\n";
		echo '<tr>' . "\n";
	}
}
?>

	</tr>
</table>
</td></tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

<?php
} elseif ($category_depth == 'products') {

// create column list
$define_list = array(
	'NEWSDESK_ARTICLE_URL' => NEWSDESK_ARTICLE_URL,
	'NEWSDESK_ARTICLE_URL_NAME' => NEWSDESK_ARTICLE_URL_NAME,
	'NEWSDESK_ARTICLE_DESCRIPTION' => NEWSDESK_ARTICLE_DESCRIPTION,
	'NEWSDESK_ARTICLE_SHORTTEXT' => NEWSDESK_ARTICLE_SHORTTEXT,
	'NEWSDESK_ARTICLE_NAME' => NEWSDESK_ARTICLE_NAME,
	'NEWSDESK_DATE_AVAILABLE' => NEWSDESK_DATE_AVAILABLE,
	'NEWSDESK_STATUS' => NEWSDESK_STATUS,
);

	asort($define_list);

	$column_list = array();
	reset($define_list);
	while (list($column, $value) = each($define_list)) {
		if ($value) $column_list[] = $column;
	}

	$select_column_list = '';

	$size = sizeof($column_list);
	for ($col=0; $col<$size; $col++) {
		if ( ($column_list[$col] == 'NEWSDESK_ARTICLE_NAME') || ($column_list[$col] == 'NEWSDESK_ARTICLE_SHORTTEXT') ) {
			continue;
		}

		if ($select_column_list != '') {
			$select_column_list .= ', ';
		}

		switch ($column_list[$col]) {
		case 'NEWSDESK_ARTICLE_URL': $select_column_list .= 'pd.newsdesk_article_url';
			break;
		case 'NEWSDESK_ARTICLE_URL_NAME': $select_column_list .= 'pd.newsdesk_article_url_name';
			break;
		case 'NEWSDESK_ARTICLE_DESCRIPTION': $select_column_list .= 'pd.newsdesk_article_description';
			break;
		case 'NEWSDESK_ARTICLE_SHORTTEXT': $select_column_list .= 'pd.newsdesk_article_shorttext';
			break;
		case 'NEWSDESK_ARTICLE_NAME': $select_column_list .= 'pd.newsdesk_article_name';
			break;
		case 'NEWSDESK_DATE_AVAILABLE': $select_column_list .= 'p.newsdesk_date_added';
			break;
		case 'NEWSDESK_STATUS': $select_column_list .= 'p.newsdesk_status';
			break;
		}
	}

	if ($select_column_list != '') {
		$select_column_list .= ', ';
	}

// show the products of a specified manufacturer
	if ($HTTP_GET_VARS['manufacturers_id']) {
		if ($HTTP_GET_VARS['filter_id']) {
// We are asked to show only a specific category
/*
$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id,
IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price,
p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS .
" m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where
p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id']
. "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "'
and p2c.categories_id = '" . $HTTP_GET_VARS['filter_id'] . "'";
*/
		} else {
// We show them all
/*
$listing_sql = "select " . $select_column_list . "  p.newsdesk_id, p.newsdesk_status, p.newsdesk_date_added, pd.newsdesk_article_name, pd.newsdesk_article_shorttext,
IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price,
p.products_price) as final_price from "
. TABLE_PRODUCTS . " p, "
. TABLE_PRODUCTS_DESCRIPTION . " pd, "
. TABLE_MANUFACTURERS .
" m left join "
. TABLE_SPECIALS . " s
on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id
and pd.language_id = '" . $languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '"
. $HTTP_GET_VARS['manufacturers_id'] . "'";
p.newsdesk_image, p.newsdesk_date_available, p.newsdesk_status
pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url,
*/

$listing_sql = "select " . $select_column_list . " p.newsdesk_id, p.newsdesk_date_added, p.newsdesk_status,p.newsdesk_image,
p.newsdesk_image_two, p.newsdesk_image_three, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext,
pd.newsdesk_article_url, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
IF(s.status, s.specials_new_products_price, NULL) as final_price from "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_DESCRIPTION . " pd, "
. TABLE_MANUFACTURERS .
" m left join "
. TABLE_SPECIALS . " s
on p.newsdesk_id = s.products_id where p.newsdesk_status = '1' and pd.newsdesk_id = p.newsdesk_id
and pd.language_id = '" . $languages_id . "' and m.manufacturers_id = '"
. $HTTP_GET_VARS['manufacturers_id'] . "'";

		}

// We build the categories-dropdown
$filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_TO_CATEGORIES . " p2c, "
. TABLE_NEWSDESK_CATEGORIES . " c, "
. TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd
where p.newsdesk_status = '1' and p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id
and cd.language_id = '" . $languages_id . "' order by cd.categories_name";

		} else {

// show the products in a given categorie

		if ($HTTP_GET_VARS['filter_id']) {

// We are asked to show only specific catgeory
$listing_sql = "select " . $select_column_list . " p.newsdesk_id, p.newsdesk_status, p.newsdesk_date_added, pd.newsdesk_article_name, pd.newsdesk_article_shorttext,
pd.newsdesk_article_description, pd.newsdesk_article_url, pd.newsdesk_article_url_name, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three,
IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
IF(s.status, s.specials_new_products_price, NULL) as final_price from "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_DESCRIPTION . " pd, "
. TABLE_MANUFACTURERS . " m, "
. TABLE_NEWSDESK_TO_CATEGORIES .
" p2c left join "
. TABLE_SPECIALS . " s
on p.newsdesk_id = s.products_id where
p.newsdesk_status = '1' and m.manufacturers_id = '" . $HTTP_GET_VARS['filter_id'] .
"' and p.newsdesk_id = p2c.newsdesk_id and pd.newsdesk_id = p2c.newsdesk_id and pd.language_id = '" . $languages_id . "'
and p2c.categories_id = '" . $current_category_id . "'";

		} else {

// We show them all
// /*
$listing_sql = "select " . $select_column_list . " p.newsdesk_id, p.newsdesk_date_added, p.newsdesk_status, p.newsdesk_image,
p.newsdesk_image_two, p.newsdesk_image_three, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext,
pd.newsdesk_article_url, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
IF(s.status, s.specials_new_products_price, NULL) as final_price from "
. TABLE_NEWSDESK_DESCRIPTION . " pd, "
. TABLE_NEWSDESK .
" p left join "
. TABLE_MANUFACTURERS .
" m on p.newsdesk_id = m.manufacturers_id, "
. TABLE_NEWSDESK_TO_CATEGORIES . " p2c
left join "
. TABLE_SPECIALS . " s on
p.newsdesk_id = s.products_id where p.newsdesk_status = '1' and p.newsdesk_id = p2c.newsdesk_id and pd.newsdesk_id = p2c.newsdesk_id
and pd.language_id = '" . $languages_id . "' and p2c.categories_id =  '" . $HTTP_GET_VARS['newsPath'] . "'
";
//*/
/*
$listing_sql = "select * from "
. TABLE_NEWSDESK_DESCRIPTION . " pd, "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_TO_CATEGORIES . " p2c
where p.newsdesk_status = '1'
and p2c.categories_id = '" . $current_category_id . "'
";
*/

		}

// We build the manufacturers Dropdown
$filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_TO_CATEGORIES . " p2c, "
. TABLE_MANUFACTURERS . " m
where p.newsdesk_status = '1' and p.newsdesk_id = m.manufacturers_id and p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = '"
. $current_category_id . "' order by m.manufacturers_name";

	}

	$cl_size = sizeof($column_list);
	if ( (!$HTTP_GET_VARS['sort']) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'],0,1) > $cl_size) ) {
		for ($col=0; $col<$cl_size; $col++) {
			if ($column_list[$col] == 'NEWSDESK_DATE_AVAILABLE') {
				$HTTP_GET_VARS['sort'] = $col+1 . 'd';
				$listing_sql .= " order by p.newsdesk_date_added desc";
				break;
			}
		}
	} else {
		$sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
		$sort_order = substr($HTTP_GET_VARS['sort'], 1);
		$listing_sql .= ' order by ';
		switch ($column_list[$sort_col-1]) {
	case 'NEWSDESK_ARTICLE_URL': $listing_sql .= "pd.newsdesk_article_url " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_URL_NAME': $listing_sql .= "pd.newsdesk_article_url_name " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION': $listing_sql .= "pd.newsdesk_article_description " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_SHORTTEXT': $listing_sql .= "pd.newsdesk_article_shorttext " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_NAME': $listing_sql .= "pd.newsdesk_article_name " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_DATE_AVAILABLE': $listing_sql .= "p.newsdesk_date_added " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_STATUS': $listing_sql .= "p.newsdesk_status " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
		}
	}
?>


<!-- Wolfen added code BOF -->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="pageHeading" width="50%">
<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// let's make a drop down with all the categories and subcategories
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$info_box_contents = array();
if (MAX_MANUFACTURERS_LIST < 2) {
	$cat_choose = array(array('id' => '', 'text' => NEWS_BOX_CATEGORIES_CHOOSE));
} else {
	$cat_choose = '';
}
// Below lines changed by Wolfen
$categories_array = newsdesk_get_categories($cat_choose);
for ($i=0; $i<sizeof($categories_array); $i++) {
	$path = "";
	$parent_categories = array();
//	newsdesk_get_parent_categories($parent_categories, $categories_array[$i]['id']);
//	for ($j = sizeof($parent_categories) - 1; $j>=0; $j--) {
//		$path = ($path == "") ? $parent_categories[$j] : ($path . "_" . $parent_categories[$j]);
//	}
	$categories_array[$i]['id'] = ($path == "") ? $categories_array[$i]['id'] : ($path . "_" . $categories_array[$i]['id']);
}
$info_box_contents[] = array(
        'form' => '<form action="' . tep_href_link(FILENAME_NEWSDESK_INDEX) . '" method="get">',
		'align' => 'center',
		'text'  => NEWS_BOX_CATEGORIES_CHOOSE . '     ' . newsdesk_show_draw_pull_down_menu('newsPath', $categories_array,'','onChange="this.form.submit();" size="' . ((sizeof($categories_array) < MAX_MANUFACTURERS_LIST) ? sizeof($categories_array) : MAX_MANUFACTURERS_LIST) . '" style="width:' . BOX_WIDTH . '"')
	);
new infoBox($info_box_contents);
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
		</td></form>
		<td class="pageHeading" width="50%">
<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// show search box
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$hide = tep_hide_session_id();
$info_box_contents = array();
$info_box_contents[] = array(
	'form'  => '<form name="quick_find_news" method="get" action="' . tep_href_link(FILENAME_NEWSDESK_SEARCH_RESULT, '', 'NONSSL', false) . '">',
	'align' => 'center',
	'text'  =>TEXT_NEWS_SEARCH . '      ' .
$hide . '<input type="text" name="keywords" size="20" maxlength="30" value="'
. tep_htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["keywords"]))
. '" style="width: ' . (BOX_WIDTH-10) . 'px">&nbsp;' . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH)
);
  new infoBox($info_box_contents);
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
		</td>
	</tr>
</table>
</form>
<!-- Wolfen added code EOF -->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
	<tr>
		<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<form>
	<tr>
		<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

<?php
// optional Product List Filter
/*
if (PRODUCT_LIST_FILTER) {
	$filterlist = tep_db_query($filterlist_sql);
	if (tep_db_num_rows($filterlist) > 1) {
		echo '<td align="center" class="main">' . TEXT_SHOW . '<select size="1" onChange="if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)">';
		if ($HTTP_GET_VARS['manufacturers_id']) {
			$arguments = 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'];
		} else {
			$arguments = 'newsPath=' . $newsPath;
		}
		$arguments .= '&sort=' . $HTTP_GET_VARS['sort'];

		$option_url = tep_href_link(FILENAME_NEWSDESK_INDEX, $arguments, 'NONSSL');

		if (!$HTTP_GET_VARS['filter_id']) {
			echo '<option value="' . $option_url . '" SELECTED>' . TEXT_ALL . '</option>';
		} else {
			echo '<option value="' . $option_url . '">' . TEXT_ALL . '</option>';
		}

		echo '<option value="">---------------</option>';
		while ($filterlist_values = tep_db_fetch_array($filterlist)) {
			$option_url = tep_href_link(FILENAME_NEWSDESK_INDEX, $arguments . '&filter_id=' . $filterlist_values['id'], 'NONSSL');
			if ( ($HTTP_GET_VARS['filter_id']) && ($HTTP_GET_VARS['filter_id'] == $filterlist_values['id']) ) {
				echo '<option value="' . $option_url . '" SELECTED>' . $filterlist_values['name'] . '</option>';
			} else {
				echo '<option value="' . $option_url . '">' . $filterlist_values['name'] . '</option>';
			}
		}
		echo '</select></td>' . "\n";
	}
}
*/

// Get the right image for the top-right
$image = DIR_WS_IMAGES . 'table_background_list.gif';
if ($HTTP_GET_VARS['newsdesk_id']) {
	$image = tep_db_query("select newsdesk_image from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "'");
	$image = tep_db_fetch_array($image);
	$image = $image['newsdesk_image'];

	$image_two = tep_db_query("select newsdesk_image_two from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "'");
	$image_two = tep_db_fetch_array($image_two);
	$image_two = $image['newsdesk_image_two'];

	$image_three = tep_db_query("select newsdesk_image_three from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "'");
	$image_three = tep_db_fetch_array($image_three);
	$image_three = $image['newsdesk_image_three'];

} elseif ($current_category_id) {
	$image = tep_db_query("select categories_image from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . $current_category_id . "'");
	$image = tep_db_fetch_array($image);
	$image = $image['categories_image'];
}
?>

		<td align="right">
<?php
if ($category['categories_image'] = 'null') {
echo '';
} else {
echo tep_image(DIR_WS_IMAGES . $image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
echo tep_image(DIR_WS_IMAGES . $image_two, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
echo tep_image(DIR_WS_IMAGES . $image_three, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
?>
		</td>
	</tr>
</form>
</table>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']);
}
// EOF: Lango Added for template MOD
?>

	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
	<tr>
		<td><?php include(DIR_FS_MODULES . FILENAME_NEWSDESK_LISTING); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="5"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
}
?>
</table>
<!-- body_text_eof //-->

<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	NewsDesk
	version:		1.4.5
	date:			2003-08-31
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>
