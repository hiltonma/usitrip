<?php
/*
  $Id: new_products.php,v 1.1.1.1 2004/03/04 23:41:14 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- mainpages_modules.new_products.php//-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCTS_NEW));

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = tep_db_query("select products_id, products_image, products_tax_class_id, products_price from " . TABLE_PRODUCTS . " where DATE_SUB(CURDATE(),INTERVAL " .NEW_PRODUCT_INTERVAL ." DAY) <= products_date_added and products_status = '1' order by products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where DATE_SUB(CURDATE(),INTERVAL " .NEW_PRODUCT_INTERVAL ." DAY) <= p.products_date_added and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }


  $row = 0;
  $col = 0;
  $info_box_contents = array();
  while ($new_products = tep_db_fetch_array($new_products_query)) {
    $special_price = tep_get_products_special_price($new_products['products_id']);
    if ($special_price) {
      $products_price_s = '<s>' .  $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($special_price, tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>';
    } else {
      $products_price_s = $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id']));
    }

    $new_products['products_name'] = tep_get_products_name($new_products['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a><br>' . $products_price_s);


    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  new contentBox($info_box_contents);

?>
<!-- new_products_eof //-->
