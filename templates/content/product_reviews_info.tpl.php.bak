    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo db_to_html($products_name); ?></td>
            <td class="pageHeading" align="right" valign="top"><?php echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else{
$header_text = db_to_html($products_name) . '&nbsp;&nbsp;&nbsp;&nbsp;' . $products_price;
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
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo '<b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected(db_to_html($reviews_info['customers_name']))) . '</b>'; ?></td>
                    <td class="smallText" align="right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews_info['date_added'])); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                  <tr class="infoBoxContents">
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td valign="top" class="main"><?php //echo db_to_html(tep_break_string(nl2br(tep_output_string_protected(($reviews_info['reviews_text']))), 60, '-<br>')) . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_info['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_info['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews_info['reviews_rating'])) . '</i>'; ?>
						<?php echo db_to_html(nl2br(tep_db_output($reviews_info['reviews_text']))) . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_info['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_info['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews_info['reviews_rating'])) . '</i>'; ?>
						
						</td>
                        <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                  <tr class="infoBoxContents">
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('reviews_id'))) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                        <td class="main" align="right"><?php //echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))) . '">' . tep_template_image_button('button_submit_reviews.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="right" valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="smallText">
<?php
  if (tep_not_null($reviews_info['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $reviews_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $reviews_info['products_image'], addslashes(db_to_html($reviews_info['products_name'])), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $reviews_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $reviews_info['products_image'], db_to_html($reviews_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript>
<?php
  }


  $wishlist_id_query = tep_db_query('select products_id as wPID from ' . TABLE_WISHLIST . ' where products_id= ' . $reviews_info['products_id'] . ' and customers_id = ' . (int)$customer_id . ' order by products_name');
$wishlist_Pid = tep_db_fetch_array($wishlist_id_query);

 // echo '<p><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>';
echo '<form name="wishlist_quantity" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_wishlist', 'NONSSL') . '">';
echo '                    <input type="hidden" name="products_id" value="' . $reviews_info['products_id'] . '">
                          <input type="hidden" name="products_model" value="' . $reviews_info['products_model'] . '">
                          <input type="hidden" name="products_name" value="' . tep_htmlspecialchars(stripslashes(db_to_html($reviews_info['products_name']))) . '">
                          <input type="hidden" name="products_price" value="' . $products_price . '">
                          <input type="hidden" name="final_price" value="' . $reviews_info['final_price'] . '">
                          <input type="hidden" name="products_tax" value="' . $reviews_info['products_tax'] . '">'; 
    
if ( (!tep_not_null($wishlist_Pid[wPID])) && (tep_session_is_registered('customer_id')) ) // echo tep_template_image_submit('button_add_wishlist.gif', IMAGE_BUTTON_ADD_WISHLIST);
echo  '
                        </form>
</p>';
?>

                </td>
              </tr>
            </table>
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
    </table>

