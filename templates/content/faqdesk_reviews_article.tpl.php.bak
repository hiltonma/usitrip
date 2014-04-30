<?php

?>

<!-- body_text //-->

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $product_info_values['faqdesk_question']); ?></td>
		<td class="pageHeading" align="right">
<?php
echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_info_values['faqdesk_question']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
?>
		</td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
		<td>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
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
$reviews = tep_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_FAQDESK_REVIEWS . " where faqdesk_id = '" . $HTTP_GET_VARS['faqdesk_id'] . "' order by reviews_id DESC");

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
		echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $reviews_values['customers_name'] . '</a></td>' . "\n";
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

	<tr>
		<td colspan="5"><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>
		<td class="main" colspan="5"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
		</td>
		<td align="right" class="main">
<?php 
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_submit_reviews.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
?>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
		</td>
	</tr>
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

	script name:	FaqDesk
	version:		1.2.5
	date:			2003-09-01
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>