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
		<td class="pageHeading"><?php echo TEXT_NEWSDESK_HEADING; ?></td>
		<td class="pageHeading" align="right">
<?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
		</td>
	</tr>
</table></td>
      </tr>

	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = TEXT_NEWSDESK_HEADING;
}
// EOF: Lango Added for template MOD
?>
<?php
$product_info = tep_db_query("
select p.newsdesk_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext,
p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, pd.newsdesk_article_url, pd.newsdesk_article_url_name, pd.newsdesk_article_viewed, p.newsdesk_date_added,
p.newsdesk_date_available
from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "'
and pd.newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "' and pd.language_id = '" . $languages_id . "'");

if (!tep_db_num_rows($product_info)) { // product not found in database
?>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><br><?php echo TEXT_NEWS_NOT_FOUND; ?></td>
	</tr>
	<tr>
		<td align="right">
			<br>
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>
		</td>
	</tr>
</table>

<?php
} else {
	tep_db_query("update " . TABLE_NEWSDESK_DESCRIPTION . " set newsdesk_article_viewed = newsdesk_article_viewed+1 where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "' and language_id = '" . $languages_id . "'");
	$product_info_values = tep_db_fetch_array($product_info);

if ($product_info_values['newsdesk_image'] != 'Array') {
if (($product_info['newsdesk_image'] != 'Array') or ($product_info['newsdesk_image'] != '')) {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], $product_info_values['newsdesk_article_name'], '', '',
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}
}

if ($product_info_values['newsdesk_image_two'] != '') {
if (($product_info['newsdesk_image_two'] != 'Array') or ($product_info['newsdesk_image_two'] != '')) {
$insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image_two'], $product_info_values['newsdesk_article_name'], '', '',
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}
}

if ($product_info_values['newsdesk_image_three'] != '') {
if (($product_info['newsdesk_image_three'] != 'Array') or ($product_info['newsdesk_image_three'] != '')) {
$insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image_three'], $product_info_values['newsdesk_article_name'], '', '',
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}
}
?>

<tr>
        <td>



<table border="0" width="100%" cellspacing="3" cellpadding="3">
      <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
	<tr>
		<td width="100%" class="main" valign="top" colspan="2">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr class="headerNavigation">
		<td class="tableHeading"><?php echo $product_info_values['newsdesk_article_name']; ?></td>
		<td class="subBar" align="right">&nbsp;
			<?php echo sprintf(TEXT_NEWSDESK_DATE, tep_date_long($product_info_values['newsdesk_date_added']));; ?>
		</td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td width="100%" class="main" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_SUMMARY; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['newsdesk_article_shorttext']); ?>

<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_CONTENT; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['newsdesk_article_description']); ?>

<?php if ($product_info_values['newsdesk_article_url']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_LINK_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php $newslink = ($product_info_values['newsdesk_article_url_name']); ?>
<?php echo sprintf(TEXT_NEWSDESK_LINK . '<a href="%s" target="_blank"><u>' . $newslink . '</u></a>.', tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['newsdesk_article_url']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php if ($product_info_values['newsdesk_article_url_name']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_LINK_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo sprintf(TEXT_NEWSDESK_LINK, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['newsdesk_article_url_name']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php
$reviews = tep_db_query("
select count(*) as count from " . TABLE_NEWSDESK_REVIEWS . " where approved='1' and newsdesk_id = '"
. $HTTP_GET_VARS['newsdesk_id'] . "'
");
$reviews_values = tep_db_fetch_array($reviews);
?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_REVIEWS_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_VIEWED . $product_info_values['newsdesk_article_viewed'] ?></td>
	</tr>
<?php
if ( DISPLAY_NEWSDESK_REVIEWS ) {
?>
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_REVIEWS . ' ' . $reviews_values['count']; ?></td>
	</tr>
<?php
}
?>
</table>



		</td>
		<td width="" class="main" valign="top" align="center">
<?php
echo $insert_image;
echo $insert_image_two;
echo $insert_image_three;
?>
		</td>

	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
</table>

<?php
if ( DISPLAY_NEWSDESK_REVIEWS ) {
	if ($reviews_values['count'] > 0) {
		require FILENAME_NEWSDESK_ARTICLE_REQUIRE;
	}
}
?>
<?php
/*
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
		</td>
		<td align="right" class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_submit_reviews.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
?>
		</td>
	</tr>
</table>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
	<tr>
		<td class="main">
		<a href="<?php echo tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, substr(tep_get_all_get_params(), 0, -1)); ?>">
<?php echo tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS); ?></a>
		</td>
		<td align="right" class="main">
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>
		</td>
	</tr>
</table>
*/
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php
if ( DISPLAY_NEWSDESK_REVIEWS ) {
	echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_submit_reviews.gif',
	IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
}
?>
		</td>
		<td align="right" class="main">
<?php
// BOF Wolfen added code for button case
if ($get_backpath_back = $get_backpath) {
echo '<td align="right" class="main"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, $get_backpath) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>' . tep_draw_separator('pixel_trans.gif', '10', '1') . '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></td>';
} else {
echo '<td align="right" class="main"><a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></td>';
}
// EOF Wolfen added code for button case
?>
		</td>
	</tr>
</table>

		</td>
	</tr>
</table>

<?php } ?>

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
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
