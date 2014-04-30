<?php
/*
  WebMakers.com Added: Additional Functions
  Written by Linda McGrath osCOMMERCE@WebMakers.com
  http://www.thewebmakerscorner.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

////
// Verify Free Shipping or Regular Shipping modules to show
  function tep_get_free_shipper($chk_shipper) {
  global $cart;
  $show_shipper =false;
    switch (true) {
      case ( ($chk_shipper =='freeshipper' and $cart->show_weight() == 0) ):
        $show_shipper=true;
        break;
      case ( ($chk_shipper !='freeshipper' and $cart->show_weight() == 0) ):
        $show_shipper=false;
        break;

      case ( ($chk_shipper =='freeshipper' and $cart->show_weight() != 0) ):
        $show_shipper=false;
        break;
      case ( ($chk_shipper !='freeshipper' and $cart->show_weight() != 0) ):
        $show_shipper=true;
        break;
      default:
        $show_shipper=false;
        break;
    }

  return $show_shipper;
  }


////
// Verify Free Charge or Regular Payment methods to show
  function tep_get_free_charger($chk_module) {
  global $cart;

  $show_it =false;
    switch (true) {
      case ( ($chk_module =='freecharger' and ($cart->show_total()==0 and $cart->show_weight() == 0)) ):
        $show_it=true;
        break;
      case ( ($chk_module !='freecharger' and ($cart->show_total()==0 and $cart->show_weight() == 0)) ):
        $show_it=false;
        break;

      case ( ($chk_module =='freecharger' and ($cart->show_total()!=0 or $cart->show_weight() != 0)) ):
        $show_it=false;
        break;
      case ( ($chk_module !='freecharger' and ($cart->show_total()!=0 or $cart->show_weight() != 0)) ):
        $show_it=true;
        break;
    }

  return $show_it;
  }

////

////
// Display Price Retail
// Specials and Tax Included
  function tep_get_products_display_price($products_id, $prefix_tag=false, $value_price_only=false, $include_units=true) {
    global $currencies;
    $product_check_query = tep_db_query("select products_tax_class_id, products_price, products_priced_by_attribute, product_is_free, product_is_call, product_is_showroom_only from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'" . " limit 1");
    $product_check = tep_db_fetch_array($product_check_query);

    $display_price='';
    $value_price=0;
    // Price is either normal or priced by attributes
      if ($product_check['products_priced_by_attribute']) {
        $attributes_priced=tep_get_products_base_price($products_id, $include_units);
        $display_price=$currencies->display_price( ($product_check['products_price'] + $attributes_priced + ($attributes_priced * ($product_check['products_price_markup']/100))),'',1);
        $value_price=($product_check['products_price'] + $attributes_priced + ($attributes_priced * ($product_check['products_price_markup']/100)));
      } else {
        if ($product_check['products_price'] !=0) {
          $display_price=$currencies->display_price($product_check['products_price'],tep_get_tax_rate($product_check['products_tax_class_id']),1);
        }
      }

      // If a Special, Show it
      if ($add_special=tep_get_products_special_price($products_id)) {
        //       $products_price = '<s>' . $currencies->display_price($product_info_values['products_price'], tep_get_tax_rate($product_info_values['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info_values['products_tax_class_id'])) . '</span>';
        $display_price = '<s>' . $display_price . '</s> <span class="productSpecialPrice"> ' . $currencies->display_price($add_special,tep_get_tax_rate($product_check['products_tax_class_id']),'1') .  '</span> ';
      }

      // If Free, Show it
      if ($product_check['product_is_free']) {
        if (PRODUCTS_PRICE_IS_FREE_IMAGE_ON=='0') {
          $free_tag= ' ' . PRODUCTS_PRICE_IS_FREE_TEXT;
        } else {
          $free_tag= ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_FREE_IMAGE,PRODUCTS_PRICE_IS_FREE_TEXT);
        }

        if ($product_check['products_price'] !=0) {
          $display_price='<s>' . $display_price . '</s>' . '<br><span class="ProductIsFree">' . $free_tag . '</span>';
        } else {
          $display_price='<span class="ProductIsFree">' . $free_tag . '</span>';
        }
      } // FREE

      // If Call for Price, Show it
      if ($product_check['product_is_call']) {
        if (PRODUCTS_PRICE_IS_FREE_IMAGE_ON=='0') {
          $call_tag=' ' . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT;
        } else {
          $call_tag= ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_IMAGE,PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT);
        }

        if ($product_check['products_price'] !=0) {
          $display_price='<s>' . $display_price . '</s> ' .  $call_tag;
        } else {
          $display_price=$call_tag;
        }
      } // CALL

      // If Showroom, Show it
      if ($product_check['product_is_showroom_only']) {
        if (PRODUCTS_PRICE_IS_SHOWROOM_IMAGE_ON=='0') {
          $showroom_only_tag= ' ' . PRODUCTS_PRICE_IS_SHOWROOM_ONLY_TEXT;
        } else {
          $showroom_only_tag= ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_SHOWROOM_ONLY_IMAGE,PRODUCTS_PRICE_IS_SHOWROOM_ONLY_TEXT);
        }

        if ($product_check['products_price'] !=0) {
//          $display_price='<s>' . $display_price . '</s>' . '<br><span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
          $display_price=$display_price . '<br><span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
        } else {
          $display_price='<span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
        }
      } // FREE



    if ($value_price_only) {
      return $value_price;
    } else {
      if ($display_price) {
        return ($prefix_tag ? $prefix_tag . ' ' : '') . $display_price;
      } else {
        return false;
      }
    }
  }
?>
