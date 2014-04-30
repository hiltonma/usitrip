<?php
/*
  $Id: featured.php,v 1.1.1.1 2004/03/04 23:42:14 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- featured //-->
<?php
  if ($random_product = tep_random_select("select featured_id from  " . TABLE_FEATURED . " where status = '1' order by featured_date_added DESC limit " . MAX_RANDOM_SELECT_SPECIALS)) {


//          $random_product_query21 = tep_db_query("select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_FEATURED . " f where f.featured_id ='" .  $random_product['featured_id'] . "' and f.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and m.manufacturers_id = p.manufacturers_id and p.products_id = s.products_id and p.products_status = '1'");
//   $random_product21 = tep_db_query("select f.products_id, p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_FEATURED . " f where f.featured_id ='" .  $random_product['featured_id'] . "' and f.products_id = p.products_id and f.status = '1' and p.products_status = '1' ");
          $random_product_query21 = tep_db_query("select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_FEATURED . " f where f.featured_id ='" .  $random_product['featured_id'] . "' and f.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1'");

while ($random_product21 = tep_db_fetch_array($random_product_query21)){
$specials_new_products_price1 = tep_get_products_special_price($random_product21['products_id']);
?>
          <tr>
            <td>
<?php

if ($specials_new_products_price1) {
      $whats_new_price =  '<s>' . $currencies->display_price($random_product21['products_price'], tep_get_tax_rate($random_product21['products_tax_class_id'])) . '</s><br>';
      $whats_new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($specials_new_products_price1, tep_get_tax_rate($random_product21['products_tax_class_id'])) . '</span>';
    } else {
      $whats_new_price =  $currencies->display_price($random_product21['products_price'], tep_get_tax_rate($random_product21['products_tax_class_id']));
    }


    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FEATURED . '</font>'
                                );
    new infoBoxHeading($info_box_contents,  false, false, tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'));

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
						'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product21['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product21['products_image'], $random_product21['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product21['products_id'], 'NONSSL') . '">' . $random_product21['products_name'] . '</a><br>' . $whats_new_price
                                );



   new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?php
 }
 }
?>
<!-- featured_eof //-->
