<?php
tep_db_query("update " . TABLE_NEWSDESK_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "'");

$reviews = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.newsdesk_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read from " . TABLE_NEWSDESK_REVIEWS . " r, " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id");

$reviews_values = tep_db_fetch_array($reviews);

$reviews_text = tep_htmlspecialchars($reviews_values['reviews_text']);
$reviews_text = tep_break_string($reviews_text, 60, '-<br>');

$product = tep_db_query("select p.newsdesk_id, pd.newsdesk_article_name, p.newsdesk_image from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . $reviews_values['newsdesk_id'] . "' and pd.newsdesk_id = p.newsdesk_id and pd.language_id = '". $languages_id . "'");

$product_info_values = tep_db_fetch_array($product);
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
                        <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $product_info_values['products_name']); ?></td>
		<td class="pageHeading" align="right">
<?php
echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']), '', '');
?>
</td>
          </tr>
	          </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = sprintf(HEADING_TITLE, $product_info_values['products_name']);
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_PRODUCT; ?></b> <?php echo $product_info_values['newsdesk_article_name']; ?></td>
		<td class="smallText" rowspan="3" align="center">
<?php echo tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], $product_info_values['newsdesk_article_name'], '', '', 'align="center" hspace="5" vspace="5"'); ?>
		</td>
	</tr>
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_FROM; ?></b> <?php echo $reviews_values['customers_name']; ?></td>
	</tr>
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_DATE; ?></b> <?php echo tep_date_long($reviews_values['date_added']); ?></td>
	</tr>

	<tr>
		<td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
	</tr>
	<tr>
		<td class="main"><br><?php echo nl2br($reviews_text); ?></td>
	</tr>
	<tr>
		<td class="main">
<br><b>
<?php
echo SUB_TITLE_RATING; ?></b> <?php echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])); ?> <small>[<?php echo sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']); ?>]</small>
		</td>
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
                <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
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

	script name:	NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
