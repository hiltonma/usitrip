    <?php echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo $products_name; ?></td>
            <td class="pageHeading" align="right" valign="top"><?php echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else{
$header_text =  $products_name . '<br>' . $products_price;
}
?>

<?php
  if ($messageStack->size('review') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('review'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			
			 <tr>
                <td class="main"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="smallText">
							<?php
							  if (tep_not_null($product_info['products_image'])) {
							?>
							<script language="javascript"><!--
							document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
							//--></script>
							<noscript>
							<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
							</noscript>
							<?php
							  }
							
							 // echo '<p><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a></p>';
							?>
											</td>
										  </tr>
										</table>
			
			</td>
              </tr>
           
              <tr>
                <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>

        		
		
		  <td class="pageHeading"></br><?php echo TEXT_SUBMIT_REVIEW;?></td>
		  

      </tr>
	  </table></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                  <tr class="infoBoxContents">
                    <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
									<tr> 
                                        <td width="23%" class="main"><b><?PHP ECHO SUB_TITLE_FROM;?></b>&nbsp;</td>
                                        <td colspan="2" class="main" id="customers_name"><?php echo tep_draw_input_field('customers_name',tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname'])); ?>&nbsp;<font color="#FF0000"><?PHP ECHO TEXT_REQUIRED_FIELDS;?></font></td>
                                      </tr>
<!--
                                      <tr> 
									  
                                        <td class="main"><b>Your E-mail:</b></td>

                                        <td colspan="2" class="main" id="customers_email"><?php echo tep_draw_input_field('customers_email'); ?></td>

                                      </tr>-->
									    <tr> 

                                        <td class="main"><strong><?PHP ECHO TEXT_TITLE_REVIEW;?></strong></td>

                                        <td colspan="2" class="main" id="review_title"><?php echo tep_draw_input_field('review_title','','size="50"'); ?> 

                                          <font color="#FF0000"><?PHP ECHO TEXT_REQUIRED_FIELDS;?></font></td>

                                      </tr>

                      <tr>
					  	<td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
                        <td class="main"><?php echo tep_draw_textarea_field('review', 'soft', 45, 10); ?></td>
                      </tr>
                      <tr>
					  <td class="main"></td>
                        <td class="smallText" align="right"><?php echo TEXT_NO_HTML; ?></td>
                      </tr>
                      <tr>
					  <td class="main"><b><?php echo SUB_TITLE_RATING; ?></b></td>
                        <td class="main"><?php echo  TEXT_BAD . ' ' . tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5') . ' ' . TEXT_GOOD; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="0"<?php //echo SMALL_IMAGE_WIDTH + 10; ?> align="right" valign="top">
          </td>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                  <tr class="infoBoxContents">
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('reviews_id', 'action'))) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                        <td class="main" align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>

    </table></form>

