<?php
/*
  $Id: default_specials.php,v 2.0 2003/06/13

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- default_specials //-->

<?php
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_DEFAULT_SPECIALS, strftime('%B')));

  new infoBoxHeading($info_box_contents, true, true, tep_href_link(FILENAME_SPECIALS));

 $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and s.status = '1' order by s.specials_date_added DESC limit " . MAX_DISPLAY_SPECIAL_PRODUCTS);
    
 $info_box_contents = array();
  $row = 0;
  $col = 0;
  while ($default_specials = tep_db_fetch_array($new)) {
    $default_specials['products_name'] = tep_get_products_name($default_specials['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $default_specials['products_image'], $default_specials['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials['products_id']) . '">' . $default_specials['products_name'] . '</a><br><s>' . $currencies->display_price($default_specials['products_price'], tep_get_tax_rate($default_specials['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price($default_specials['specials_new_products_price'], tep_get_tax_rate($default_specials['products_tax_class_id'])) . '</span>');
    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  new contentBox($info_box_contents);
if (MAIN_TABLE_BORDER == 'yes'){
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents, false, false);
}

?>

<!-- default_specials_eof //-->
