<?php
$product_info = tep_db_query("
select p.faqdesk_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, 
p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, pd.faqdesk_extra_url, pd.faqdesk_extra_viewed, p.faqdesk_date_added, 
p.faqdesk_date_available 
from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = '" . $HTTP_GET_VARS['faqdesk_id'] . "' 
and pd.faqdesk_id = '" . $HTTP_GET_VARS['faqdesk_id'] . "' and pd.language_id = '" . $languages_id . "'");

if (!tep_db_num_rows($product_info)) { // product not found in database
?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
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
	tep_db_query("update " . TABLE_FAQDESK_DESCRIPTION . " set faqdesk_extra_viewed = faqdesk_extra_viewed+1 where faqdesk_id = '" . $HTTP_GET_VARS['faqdesk_id'] . "' and language_id = '" . $languages_id . "'");
	$product_info_values = tep_db_fetch_array($product_info);

if (($product_info['faqdesk_image'] != 'Array') && ($product_info['faqdesk_image'] != '')) {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image'], $product_info_values['faqdesk_question'], '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}


if (($product_info_values['faqdesk_image_two'] != '') && ($product_info['faqdesk_image_two'] != '')) {
$insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image_two'], $product_info_values['faqdesk_question'], '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}

if (($product_info_values['faqdesk_image_three'] != '') && ($product_info['faqdesk_image_three'] != '')) {
$insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image_three'], $product_info_values['faqdesk_question'], '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}

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
                        <td class="pageHeading"><?php echo TEXT_FAQDESK_HEADING; ?></td>
		<td class="pageHeading" align="right">
<?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
</td>
          </tr>
	          </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = TEXT_FAQDESK_HEADING;
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
	<tr class="headerNavigation">
		<td class="tableHeading"><?php echo $product_info_values['faqdesk_question']; ?></td>
		<td class="subBar" align="right">&nbsp;
			<?php echo sprintf(TEXT_FAQDESK_DATE, tep_date_long($product_info_values['faqdesk_date_added']));; ?>
		</td>
	</tr>
</table>


<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td width="100%" class="main" valign="top">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_ANSWER_SHORT; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['faqdesk_answer_short']); ?>

<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_ANSWER_LONG; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['faqdesk_answer_long']); ?>

<?php if ($product_info_values['faqdesk_extra_url']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_LINK_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo sprintf(TEXT_FAQDESK_LINK, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['faqdesk_extra_url']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php
$reviews = tep_db_query("
select count(*) as count from " . TABLE_FAQDESK_REVIEWS . " where approved='1' and faqdesk_id = '" 
. $HTTP_GET_VARS['faqdesk_id'] . "'
");
$reviews_values = tep_db_fetch_array($reviews);
?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_VIEWED . $product_info_values['faqdesk_extra_viewed'] ?></td>
	</tr>
<?php
if ( DISPLAY_FAQDESK_REVIEWS ) {
?>
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS . ' ' . $reviews_values['count']; ?></td>
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
</table>

<?php
if ( DISPLAY_FAQDESK_REVIEWS ) {
	if ($reviews_values['count'] > 0) {
		require FILENAME_FAQDESK_ARTICLE_REQUIRE;
	}
}
?>
<?php
/*
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td align="right" class="main">
<?php 
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_submit_reviews.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
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
		<a href="<?php echo tep_href_link(FILENAME_FAQDESK_REVIEWS_ARTICLE, substr(tep_get_all_get_params(), 0, -1)); ?>">
<?php echo tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS); ?></a>
		</td>
		<td align="right" class="main">
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>
		</td>
	</tr>
</table>
*/
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

<?php } ?>
      <tr>
        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="left">
<?php 
if ( DISPLAY_FAQDESK_REVIEWS ) {
	echo '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_submit_reviews.gif',
	IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
}
?></td><td align="right">
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
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
