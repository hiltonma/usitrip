<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Featured Products Listing Module
*/
  if (sizeof($featured_products_array) == '0') {
?>
  <tr>
    <td class="main" ><?php echo TEXT_NO_FEATURED_PRODUCTS; ?></td>
  </tr>
<?php
  } else {
    for($i=0; $i<sizeof($featured_products_array); $i++) {
      if ($featured_products_array[$i]['specials_price']) {
        $products_price = '<s>' .  $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($featured_products_array[$i]['specials_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id']));
      }
?>
  <tr>
    <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
    <td valign="top" class="main" width="60%">
	<?php 
	//echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><b><u>' . $featured_products_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $featured_products_array[$i]['date_added'] . '<br>' . TEXT_MANUFACTURER . ' ' . $featured_products_array[$i]['manufacturer'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; 
	echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><b><u>' . $featured_products_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $featured_products_array[$i]['date_added'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; 
	?>
	
	</td>
    <td align="right" valign="middle" class="main">
	<?php
	// echo '<a href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>'; 
	 echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_template_image_button('button_view_detail.gif', 'View Detail') . '</a>'; 
	 
	 ?>
	
	</td>
  </tr>
<?php
      if (($i+1) != sizeof($featured_products_array)) {
?>
  <tr>
    <td colspan="3" class="main">&nbsp;</td>
  </tr>
<?php
      }
    }
  }
?>
