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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else{
$header_text =  HEADING_TITLE;
}
?>

<?php
  $products_new_array = array();
 // $products_new_query_raw = "select p.products_id, pd.products_name, products_date_available as date_expected from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT ;
//  $products_new_query_raw = "select products_id, products_image, products_tax_class_id, products_price from " . TABLE_PRODUCTS . " where products_status = '1' order by products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS;

  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where  DATE_SUB(CURDATE(),INTERVAL " .NEW_PRODUCT_INTERVAL ." DAY) <= p.products_date_added and p.products_status = '1'  and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
  $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($products_new_split->number_of_rows > 0) {
    $products_new_query = tep_db_query($products_new_split->sql_query);
    while ($products_new = tep_db_fetch_array($products_new_query)) {
      if ($new_price = tep_get_products_special_price($products_new['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
      }
     $manufname = '';
       $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS ." where manufacturers_id ='" . $products_new['manufacturers_id'] . "' ");
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              $manufname = $manufacturers['manufacturers_name']; 
            } 
     
?>
          <tr>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $products_new['products_image'], $products_new['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
            <td valign="top" class="main">
			<?php
			//amit commented  echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '"><b><u>' . $products_new['products_name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . tep_date_long($products_new['products_date_added']) . '<br>' . TEXT_MANUFACTURER . ' ' . $manufname . '<br><br>' . TEXT_PRICE . ' ' . $products_price; 
			 echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '"><b><u>' . $products_new['products_name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . tep_date_long($products_new['products_date_added']) . '<br><br>' . TEXT_PRICE . ' ' . $products_price; 
			?>
			</td>
            <td align="right" valign="middle" class="main">
			<?php 
			
			//amit commented  echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id']) . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>'; 
			echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '">' . tep_template_image_button('button_view_detail.gif', 'View Detail') . '</a>'; 
			?>
			
			</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
   
    }
  } else {
?>
          <tr>
            <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
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
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </table></td>
      </tr>
<?php
  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table>