<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox
*/
?>
<!-- featured_products_mainpage //-->
<?php
 if(FEATURED_PRODUCTS_DISPLAY == true)
 {
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);

    $featured_products_query = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, p.products_image from  " . TABLE_FEATURED . " f, " . TABLE_PRODUCTS . " p where p.products_id = f.products_id  and f.status = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
 
  $row = 0;
  $col = 0;
  $num = 0;
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
     
    $num ++; if ($num == 1) { new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS));}
   
   $featured_products['products_name'] = tep_get_products_name($featured_products['products_id']);

   $special_price = tep_get_products_special_price($featured_products['products_id']);
    if ($special_price) {
      $products_price = '<s>' .  $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($special_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';

  } else {
      $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));

 }

    $featured_products['products_name'] = tep_get_products_name($featured_products['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                          'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br>' . $products_price);


    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  if($num) {
      new contentBox($info_box_contents);
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents, true, true);

  }
 }
?>
<!-- featured_products_eof //-->
