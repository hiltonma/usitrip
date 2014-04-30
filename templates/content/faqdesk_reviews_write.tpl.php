<form name="faqdesk_reviews_write" method="post" action="<?php echo tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, 'action=process&faqdesk_id=' . $HTTP_GET_VARS['faqdesk_id'], 'NONSSL'); ?>" onSubmit="return checkForm();">

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
<?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
		<td class="main" width="50%">
		<b>
<?php echo SUB_TITLE_PRODUCT; ?>
		</b>
<?php echo $product_info_values['faqdesk_question']; ?>
		</td>
                                    <td class="main" rowspan="3" align="center">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td>
                                        <p align="center">
                                        <?php echo $insert_image; ?>
                                        </td>
                                      </tr>
                                    </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="main"><b><?php echo SUB_TITLE_FROM; ?></b><?php echo $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="main" colspan="2"><?php echo SUB_TITLE_RATING; ?>
                                    <?php echo TEXT_BAD; ?>
				<input type="radio" name="rating" value="1">
				<input type="radio" name="rating" value="2">
				<input type="radio" name="rating" value="3">
				<input type="radio" name="rating" value="4">
				<input type="radio" name="rating" value="5">
				<?php echo TEXT_GOOD; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="main" colspan="2"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><?php echo tep_draw_textarea_field('review', 'soft', 60, 15);?></td>
                                  </tr>
                                  <tr>
                                    <td class="smallText" colspan="2"><?php echo TEXT_NO_HTML; ?></td>
                                  </tr>
                                  </td>
                                  </tr>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
</table>
      <tr>
        <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="5"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td><td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	<input type="hidden" name="get_params" value="<?php echo $get_params; ?>">
	</form>
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
