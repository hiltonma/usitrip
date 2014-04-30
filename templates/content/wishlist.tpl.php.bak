
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
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_wishlist.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
//############ start editing
?>
        <td width="100%" valign="top" align="center"><table border="0" width="96%" cellspacing="0" cellpadding="0">
              <!-- <tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
		</tr> -->
              <?php
	$wishlist_query_raw = "select * from " . TABLE_WISHLIST . " where customers_id = '" . (int)$customer_id . "' order by products_name";
	$wishlist_split = new splitPageResults($wishlist_query_raw, MAX_DISPLAY_WISHLIST_PRODUCTS);
	$wishlist_query = tep_db_query($wishlist_split->sql_query);
?>
              <!-- customer_wishlist //-->
              <?php
	$info_box_contents = array();

	if (tep_db_num_rows($wishlist_query))
	{
		$product_ids = '';
		while ($wishlist = tep_db_fetch_array($wishlist_query))
		{
			$product_ids .= $wishlist['products_id'] . ',';
		}
		$product_ids = substr($product_ids, 0, -1);
?>
              <?php

//    $products_query = tep_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $product_ids . ") and language_id = '" . $languages_id . "' order by products_name");
		$products_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id in (" . $product_ids . ") and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by products_name");

// BOF PAGING_MOVED
		if ($wishlist_split > 0 && (PREV_NEXT_BAR_LOCATION == '1' || PREV_NEXT_BAR_LOCATION == '3'))
		{
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText"><?php echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></td>
                      <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
                    </tr>
                  </table></td>
              </tr>
              <?php
		}

?>              
               <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
               </tr>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params(array('action')) . 'action=add_products_wishlist')); ?> </td>
                    </tr>
                    <tr>
                      <?php // EOF PAGING_MOVED


		$row = 0;
		while ($products = tep_db_fetch_array($products_query))
		{
			if ($new_price = tep_get_products_special_price($products['products_id']))
			{
				$products_price = '<s>' . $currencies->display_price($products['products_price'], tep_get_tax_rate($products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($products['products_tax_class_id'])) . '</span>';
			}
			else
			{
				$products_price = $currencies->display_price($products['products_price'], tep_get_tax_rate($products['products_tax_class_id']));
			}
			$row++;
?>
                      <td width="50%" valign="top" align="center" class="smallText"><div align="center"><b><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&products_id=' . $products['products_id'], 'NONSSL'); ?>"><?php echo $products['products_name']; ?></a></b></div>
                        <br>
                        <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&products_id=' . $products['products_id'], 'NONSSL'); ?>"><?php echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></a><br>
                        <?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5'); ?><br>
                        <?php echo BOX_TEXT_PRICE;?>&nbsp;<?php echo $products_price; ?><br>
                        <?php echo BOX_TEXT_SELECT_PRODUCT . tep_draw_checkbox_field('add_wishprod[]',$products['products_id']); ?><br>
                        <?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5'); ?> </td>
                      <?php
			if ((($row / 2) == floor($row / 2)))
			{
?>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
                    </tr>
                    <tr>
                      <?php
			}
?>
                      <?php
		}
?>
                    </tr>
                  </table></td>
              </tr>
              <!-- Added by MTH -->
              <tr>
                <td class="main" align="left"><?php echo '<b>' . BOX_TEXT_SELECTED_PRODUCTS . ':</b><br>' . BOX_TEXT_MOVE_TO_CART . ': ' . tep_draw_radio_field('borrar', '0', 'checked') . ' ' . BOX_TEXT_DELETE . ': ' . tep_draw_radio_field('borrar', '1'); ?></td>
                <td class="main" align="left"><br>
                  <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
                  </form></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?>
                  <!-- tell_a_friend //-->
                  <table border="0" align="center" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><b><?php echo BOX_HEADING_SEND_WISHLIST; ?></b></td>
                    </tr>
                    <tr class="infoBoxContents">
                      <td align="center"><?php echo tep_draw_form('tell_a_friend', tep_href_link(FILENAME_WISHLIST_SEND, '', 'NONSSL', false)); ?> 
					  <?php echo tep_draw_input_field('friendemail', '', 'size="20"') . '&nbsp;' . tep_image_submit('button_tell_a_friend.gif', BOX_TEXT_SEND) . tep_draw_hidden_field('products_ids', $product_ids) . tep_hide_session_id() . '<br>' . BOX_TEXT_SEND; ?></td>
                      
								  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                  </table>
                  <!-- tell_a_friend_eof //-->
                  <?php
		if ($wishlist_split > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3'))
		{
?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText"><?php echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></td>
                      <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
                    </tr>
                  </table>
                  <?php
		}
?>
                </td>
              </tr>
              <?php
	}
	else
	{ // Nothing in the customers wishlist
?>
              <tr>
                <td class="main"><?php echo BOX_TEXT_NO_ITEMS;?></td>
              </tr>
              <?php
	}
?>
              <!-- customer_wishlist_eof //-->
            </table></td>
          <?php
//#######stop editing

// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
</table>
