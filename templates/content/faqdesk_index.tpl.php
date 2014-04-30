<!-- faq_desk Index //-->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>" valign="top">
	<?php
	// BOF: Lango Added for template MOD
	if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
	$header_text = '&nbsp;';
?>
       <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
                        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
		<td class="pageHeading" align="right">
		<?php
}else{
$header_text = HEADING_TITLE;
}
?>
<!-- faqdesk header-->
<?php
// EOF: Lango Added for template MOD


if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}


// BOF: Lango Added for template MOD
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// let's make a drop down with all the categories and subcategories
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$info_box_contents = array();
if (MAX_MANUFACTURERS_LIST < 2) {
	$cat_choose = array(array('id' => '', 'text' => FAQ_BOX_CATEGORIES_CHOOSE));
} else {
	$cat_choose = '';
}
// Below lines changed by Marcel
$categories_array = faqdesk_get_categories($cat_choose);
for ($i=0; $i<sizeof($categories_array); $i++) {
	$path = "";
	$parent_categories = array();
//	faqdesk_get_parent_categories($parent_categories, $categories_array[$i]['id']);
//	for ($j = sizeof($parent_categories) - 1; $j>=0; $j--) {
//		$path = ($path == "") ? $parent_categories[$j] : ($path . "_" . $parent_categories[$j]);
//	}
	$categories_array[$i]['id'] = ($path == "") ? $categories_array[$i]['id'] : ($path . "_" . $categories_array[$i]['id']);
}
$info_box_contents[] = array(
		'form' => '<form action="' . tep_href_link(FILENAME_FAQDESK_INDEX) . '" method="get">',
		'align' => 'center',
		'text'  => FAQ_BOX_CATEGORIES_CHOOSE . '     ' . faqdesk_show_draw_pull_down_menu('faqPath', $categories_array,'','onChange="this.form.submit();" size="' . ((sizeof($categories_array) < MAX_MANUFACTURERS_LIST) ? sizeof($categories_array) : MAX_MANUFACTURERS_LIST) . '" style="width:' . BOX_WIDTH . '"')
	);
  new infoBox($info_box_contents, true);

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
		</td>

		<td class="pageHeading" width="50%">
<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// show search box
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$hide = tep_hide_session_id();
$info_box_contents = array();
$info_box_contents[] = array(
	'form'  => '<form name="quick_find_faq" method="get" action="' . tep_href_link(FILENAME_FAQDESK_SEARCH_RESULT, '', 'NONSSL', false) . '">',
	'align' => 'center',
	'text'  => TEXT_FAQ_SEARCH . '      ' .
$hide . '<input type="text" name="keywords" size="20" maxlength="30" value="'
. tep_htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["keywords"]))
. '" style="width: ' . (BOX_WIDTH-10) . 'px">&nbsp;' . tep_template_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH)
);
  new infoBox($info_box_contents);
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
		</td>
	</tr>
</table>

<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// let's pick up information for the top area of the category listings
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$category_query = tep_db_query(
"select cd.categories_name, c.categories_image, cd.categories_heading_title, cd.categories_description from "
. TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.categories_id = '"
. $current_category_id . "' and cd.categories_id =  '" . $HTTP_GET_VARS['faqPath'] . "' and cd.language_id = '" . $languages_id . "'"
);
$category = tep_db_fetch_array($category_query);
?>

<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="infoBoxHeading" align="left" colspan="3"><?php echo TABLE_HEADING_CATEGORY; ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>
		<td class="pageHeading">
<?php
if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
	echo $category['categories_heading_title'];
} else {
//	echo HEADING_TITLE;
}
?>
		</td>
		<td class="pageHeading" align="center">
<?php
if (($category['categories_image'] = 'NULL') or ($category['categories_image'] = '')) {
echo tep_draw_separator('pixel_trans.gif', '1', '1');
} else {
echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
?>
		</td>
	</tr>
<?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
	<tr>
		<td class="cat_description" align="left" colspan="2">
		<?php// echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
		<?php echo $category['categories_description']; ?>
		</td>
	</tr>
<?php } ?>
</table>

<?php //echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>

<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="cat_description" colspan="3"><?php echo TABLE_HEADING_SUBCATEGORY; ?></td>
	</tr>
	<tr>
		<td colspan="3"><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>
<?php
if ($faqPath && ereg('_', $faqPath)) {
// check to see if there are deeper categories within the current category
	$category_links = array_reverse($faqPath_array);
	for($i=0; $i<sizeof($category_links); $i++) {

$categories_query = tep_db_query(
"select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, "
. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $category_links[$i] . "'
and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name"
);

		if (tep_db_num_rows($categories_query) < 1) {
			// do nothing, go through the loop
		} else {
			break; // we've found the deepest category the customer is in
		}
	}
} else {

$categories_query = tep_db_query(
"select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, "
. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id =  '" . $HTTP_GET_VARS['faqPath'] . "'
and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name"
);

}
if (($categories['categories_image'] = 'NULL') or ($categories['categories_image'] = '')) {
echo tep_draw_separator('pixel_trans.gif', '1', '1');
} else {
echo tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
	$faqPath_new = faqdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
	echo '
		<td align="left" class="smallText" style="width: ' . $width . '" valign="top"><a href="'
		. tep_href_link(FILENAME_FAQDESK_INDEX, $faqPath_new, 'NONSSL') . '">';
if (($categories['categories_image'] = 'NULL') or ($categories['categories_image'] = '')) {
echo tep_draw_separator('pixel_trans.gif', '1', '1');
} else {
echo tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
	echo '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '</tr>' . "\n";
		echo '<tr>' . "\n";
	}
}
?>


<?php //echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>

<?php
if ($category_depth == 'nested') {
} elseif ($category_depth == 'products') {

?>
    <tr>
		<td><?php include(FILENAME_FAQDESK_SHOW); ?></td>
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
            </table>

         </table>

<?php
}
?>
</table>
<!-- body_text_eof //-->

<!-- faqdesk_index_eof //-->

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
