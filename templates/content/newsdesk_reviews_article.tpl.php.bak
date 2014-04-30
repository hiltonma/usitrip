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
                        <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']); ?></td>
		<td class="pageHeading" align="right">
<?php
echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
?>
</td>
          </tr>
	          </table></td>
      </tr>
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
		<td class="tableHeading"><?php echo TABLE_HEADING_NUMBER; ?></td>
		<td class="tableHeading"><?php echo TABLE_HEADING_AUTHOR; ?></td>
		<td align="center" class="tableHeading"><?php echo TABLE_HEADING_RATING; ?></td>
		<td align="center" class="tableHeading"><?php echo TABLE_HEADING_READ; ?></td>
		<td align="right" class="tableHeading"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
	</tr>
	<tr>
		<td colspan="5"><?php echo tep_draw_separator(); ?></td>
	</tr>

<?php
$reviews = tep_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_NEWSDESK_REVIEWS . " where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "' order by reviews_id DESC");

if (tep_db_num_rows($reviews)) {
	$row = 0;
	while ($reviews_values = tep_db_fetch_array($reviews)) {
		$row++;
		if (strlen($row) < 2) {
			$row = '0' . $row;
		}
		$date_added = tep_date_short($reviews_values['date_added']);
		if (($row / 2) == floor($row / 2)) {
			echo '<tr class="productReviews-even">' . "\n";
		} else {
			echo '<tr class="productReviews-odd">' . "\n";
		}
		echo '<td class="smallText">' . $row . '.</td>' . "\n";
		echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $reviews_values['customers_name'] . '</a></td>' . "\n";
		echo '<td align="center" class="smallText">' . tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])) . '</td>' . "\n";
		echo '<td align="center" class="smallText">' . $reviews_values['reviews_read'] . '</td>' . "\n";
		echo '<td align="right" class="smallText">' . $date_added . '</td>' . "\n";
		echo '</tr>' . "\n";
	}
} else {
?>

	<tr class="productReviews-odd">
		<td colspan="5" class="smallText"><?php echo TEXT_NO_REVIEWS; ?></td>
	</tr>

<?php
}
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td><td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_submit_reviews.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>
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

	script name:			NewsDesk
		version:        		1.48.2
		date:       			22-06-2004 (dd/mm/yyyy)
		original author:		Carsten aka moyashi
		web site:       		www..com
	    modified code by:		Wolfen aka 241

*/
?>
