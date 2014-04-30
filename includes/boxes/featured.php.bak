<?php
/*
  $Id: featured.php,v 1.1.1.1 2004/03/04 23:42:14 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- specials //-->
<?php
  if ($random_product = tep_random_select("select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by f.featured_date_added DESC limit " . MAX_RANDOM_SELECT_SPECIALS)) {


?>
          <tr>
            <td>
<?php
if ($random_product['specials_new_products_price']) {
      $whats_new_price =  '<s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br>';
      $whats_new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
    } else {
      $whats_new_price =  $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
    }


    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FEATURED . '</font>'
                                );
    new infoBoxHeading($info_box_contents,  false, false, tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'));

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
						'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id'], 'NONSSL') . '">' . $random_product['products_name'] . '</a><br>' . $whats_new_price
                                );



   new infoBox($info_box_contents);
    if (TEMPLATE_INCLUDE_FOOTER =='true'){
        $info_box_contents = array();
         $info_box_contents[] = array('align' => 'left',
                                       'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                     );
    new infoboxFooter($info_box_contents);
 }
?>
            </td>
          </tr>
<?php
  }
?>
<!-- specials_eof //-->
