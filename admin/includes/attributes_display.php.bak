<?php
/*
  /includes/attributes_display.php

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/
/////////////////////////////////////////////////////////////////////////////
// BOF: ATTRIBUTES //////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
?>
<?php
if (false) { // for testing
echo '<br>' . ' int ' . $products_id;
echo '<br>' . ' $products_id ' . $products_id;
echo '<br>' . ' look_it_up ' . $look_it_up;
echo '<br>' . ' PRODUCTS_OPTIONS_SORT_ORDER ' . PRODUCTS_OPTIONS_SORT_ORDER;
echo '<br>' . ' $languages_id ' . $languages_id;
}

//    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
      echo '<b>' . TEXT_PRODUCT_OPTIONS . '</b><br>' .
           '<table border="0" cellpadding="0" cellspacing="0">';
// WebMakers.com Added: sort order
      if (PRODUCTS_OPTIONS_SORT_ORDER=='0') {
        $options_order_by= ' order by LPAD(popt.products_options_sort_order,11,"0")';
      } else {
        $options_order_by= ' order by popt.products_options_name';
      }

      $one_time_attributes_note='false';
      $attributes_qty_prices_onetime_counter=0;

      // dogu 2003-02-28 update query to pull option_type
      // clr 2003-03-15 add order by statement to query
      // "' order by popt.products_options_id"

      $new_fields_attributes_text= ", popt.products_options_type, popt.products_options_length, popt.products_options_comment, popt.products_options_size";
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name" . $new_fields_attributes_text . " from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'" . $options_order_by);
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
//dogu 2003-02-28 BEGIN Add if statement to check product option type.  If add more option types, then change this to a case statement.

// BOF: prepare info on attributes with Text
        if ($products_options_name['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT) {
          $new_fields=', patrib.products_options_sort_order, patrib.attributes_price_onetime, patrib.attributes_display_only, patrib.product_attribute_is_free, patrib.products_attributes_weight, patrib.products_attributes_weight_prefix, patrib.attributes_default, patrib.attributes_qty_prices_onetime, patrib.attributes_discounted, patrib.attributes_price_factor, patrib.attributes_price_factor_offset';
          $new_fields_weight=', patrib.products_attributes_weight_prefix, patrib.products_attributes_weight ';
          $products_options_query = tep_db_query("select distinct patrib.options_values_price, patrib.price_prefix" . $new_fields . $new_fields_weight . " from " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id . "' and patrib.options_id = '" . $products_options_name['products_options_id'] . "'");
          $products_options = tep_db_fetch_array($products_options_query);

          $show_details='';
          $show_weight='';
          $show_free='';

// old          $show_attributes_price = $products_options['options_values_price'] + (tep_get_products_price_quantity_discount_price($product_info['products_id'],1,false,true) * (($products_options['attributes_price_factor'] - $products_options['attributes_price_factor_offset'])) );
          $the_products_price = tep_get_products_price(tep_get_prid($products_id),true);
          $show_attributes_price= ($products_options['options_values_price'] + tep_get_attributes_price_factor($the_products_price, tep_get_products_special_price($product_info['products_id']), $products_options['attributes_price_factor'], $products_options['attributes_price_factor_offset']));

          if (!empty($products_options['attributes_qty_prices_onetime'])) {
            $attributes_qty_prices_onetime_counter++;
          }

          if ($products_options['attributes_price_onetime'] !=0 ) {
            $one_time_attributes_note='true';
          }

// mark free attributes
          if ($products_options['product_attribute_is_free']=='1') {
            $show_free=' - FREE';
          }

// show weight
          if ($products_options['products_attributes_weight']!=0) {
            // show attribute weight on dropdown
            if (SHOW_PRODUCT_INFO_ATTRIBUTES_WEIGHT=='1') {
              if ($products_options['products_attributes_weight']) {
                $show_weight=' (' . $products_options['products_attributes_weight_prefix'] . round($products_options['products_attributes_weight'],2) . PRODUCTS_WEIGHT_UNITS_TEXT . ')';
              }
            }
          }

// prices on everything except display only
//          if ($products_options['attributes_price_onetime'] != '0') {
          if ($products_options['options_values_price'] != '0' or $products_options['attributes_price_onetime'] != 0) {
            $show_details = '(';
            $show_details .= ($show_attributes_price !=0 ? $products_options['price_prefix'] . $currencies->display_price($show_attributes_price, tep_get_tax_rate($product_info['products_tax_class_id'])) : '');
            $show_details .= ( ($products_options['attributes_price_onetime']) !=0 ? ' *' . $currencies->display_price( ($products_options['attributes_price_onetime']), tep_get_tax_rate($product_info['products_tax_class_id']) ) : '');
            $show_details .= ')&nbsp';
          }

          $show_details .= $show_weight . $show_free;
        } // EOF: TEXT attribute preparation
// EOF: prepare info on attribute with Text

// show in infobox or text
        switch (true) {
// show in infoboxes
        case ($products_options_name['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT and SHOW_ATTRIBUTES_OPTION_TEXT_INFOBOX=='1'):
          // dogu 2003-02-28 add query to pull attribute price and price_prefix
?>
        <table border="1" width="100%"><tr><td>
          <tr>
            <td colspan="2" valign="top">
<?php
          // dogu 2003-02-28 add query to pull attribute price and price_prefix
          echo '<tr><td class="main" valign="top">' . $products_options_name['products_options_name'] . '&nbsp;</td>';
          switch (true) {
          // show price and weight details
            case ($show_details):
              echo '<td class="main" valign="top">' . '&nbsp;' . $show_details . '</td></tr>';
              echo '<tr><td>&nbsp;</td><td class="main" valign="top">';
              echo ($products_options_name['products_options_comment'] ? $products_options_name['products_options_comment'] . '<br>' : '');
              echo '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_size'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="">';
              break;
          // no price and weight details
            default:
              echo '<td class="main" valign="top">';
              echo ($products_options_name['products_options_comment'] ? $products_options_name['products_options_comment'] . '<br>' : '');
              echo '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_size'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="">';
              echo '</td>';
              break;
          }
          echo '</td></tr>';
?>
            </td>
          </tr>
        </td></tr></table>

<?php
          break;

// show as text no infobox
        case ($products_options_name['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT):
          // dogu 2003-02-28 add query to pull attribute price and price_prefix
            echo '<tr><td colspan="2" valign="top">' . tep_draw_separator('pixel_trans.gif', '100%', '2'). '</td></tr>';
          echo '<tr><td class="main" valign="top">' . $products_options_name['products_options_name'] . '&nbsp;</td>';
          switch (true) {
          // show price and weight details
            case ($show_details):
              echo '<td class="main" valign="top">' . '&nbsp;' . $show_details . '</td></tr>';
              echo '<tr><td>&nbsp;</td><td class="main" valign="top">';
              echo ($products_options_name['products_options_comment'] ? $products_options_name['products_options_comment'] . '<br>' : '');
              echo '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_size'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="">';
              break;
          // no price and weight details
            default:
              echo '<td class="main" valign="top">';
              echo ($products_options_name['products_options_comment'] ? $products_options_name['products_options_comment'] . '<br>' : '');
              echo '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_size'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="">';
              break;
          }
          echo '</td></tr>';
          echo '<tr><td colspan="2" valign="top">' . tep_draw_separator('pixel_trans.gif', '100%', '2'). '</td></tr>';

          break;
//dogu 2003-02-28 END CASE statement to check product option type TEXT.
////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////
// radio buttons
          case ($products_options_name['products_options_type'] == PRODUCTS_OPTIONS_TYPE_RADIO):
            //CLR 2003-03-18 Add logic for radio buttons
            echo '<tr><td class="main" valign="top">' . $products_options_name['products_options_name'] . ':&nbsp;</td><td class="main" valign="top">';
// WebMakers.com Added: Attributes Sorter and Quantity Discounts
            $new_fields=', pa.products_options_sort_order, pa.attributes_price_onetime, pa.attributes_display_only, pa.product_attribute_is_free, pa.products_attributes_weight, pa.products_attributes_weight_prefix, pa.attributes_default, pa.attributes_qty_prices_onetime, pa.attributes_discounted, pa.attributes_price_factor, pa.attributes_price_factor_offset';
            $new_fields_weight=', pa.products_attributes_weight_prefix, pa.products_attributes_weight ';
            if ( PRODUCTS_OPTIONS_SORT_BY_PRICE =='1' ) {
              $order_by= ' order by LPAD(pa.products_options_sort_order,11,"0"), pov.products_options_values_name';
            } else {
              $order_by= ' order by LPAD(pa.products_options_sort_order,11,"0"), pa.options_values_price';
            }
            $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix" . $new_fields . $new_fields_weight . " from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products_id . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'" . $order_by);
            $count_it = tep_db_num_rows($products_options_query);
            while ($products_options = tep_db_fetch_array($products_options_query)) {
              $show_details='';
              $show_weight='';
              $show_free='';

          $the_products_price = tep_get_products_price(tep_get_prid($products_id),true);
          $show_attributes_price= ($products_options['options_values_price'] + tep_get_attributes_price_factor($the_products_price, tep_get_products_special_price($product_info['products_id']), $products_options['attributes_price_factor'], $products_options['attributes_price_factor_offset']));

// fix here
              if (!empty($products_options['attributes_qty_prices_onetime'])) {
                $attributes_qty_prices_onetime_counter++;
              }

              if ($products_options['attributes_price_onetime'] !=0 ) {
                $one_time_attributes_note='true';
              }

// mark free attributes
              if ($products_options['product_attribute_is_free']=='1') {
                $show_free=' - FREE';
              }
// show weight
              if ($products_options['products_attributes_weight']!=0) {
// BOF: WebMakers.com Added: Shoppe Enhancement Controller
            // show attribute weight on dropdown
                if (SHOW_PRODUCT_INFO_ATTRIBUTES_WEIGHT=='1') {
                  if ($products_options['products_attributes_weight']) {
                    $show_weight=' (' . $products_options['products_attributes_weight_prefix'] . round($products_options['products_attributes_weight'],2) . PRODUCTS_WEIGHT_UNITS_TEXT . ')';
                  }
                }
              }

              if ($products_options['options_values_price'] != '0' or $products_options['attributes_price_onetime'] != 0) {
                $show_details = '(';
                $show_details .= ($show_attributes_price !=0 ? $products_options['price_prefix'] . $currencies->display_price($show_attributes_price, tep_get_tax_rate($product_info['products_tax_class_id'])) : '');
                $show_details .= ( ($products_options['attributes_price_onetime']) !=0 ? ' *' . $currencies->display_price( ($products_options['attributes_price_onetime']), tep_get_tax_rate($product_info['products_tax_class_id']) ) : '');
                $show_details .= ')&nbsp';
              }
              $show_details .= $show_weight . $show_free;

                if ($count_it==1) {
                  echo tep_draw_radio_field('id[' . $products_options_name['products_options_id'] . ']', $products_options['products_options_values_id'], true );
                } else {
                  echo tep_draw_radio_field('id[' . $products_options_name['products_options_id'] . ']', $products_options['products_options_values_id'], ($products_options['attributes_default']=='1' ? true : false) );
                }

              echo $products_options['products_options_values_name'] . '&nbsp;' . $show_details;
              echo '<br>';
            }
            echo '</td></tr>';
            echo '<tr><td colspan="2" valign="top">' . tep_draw_separator('pixel_trans.gif', '100%', '2'). '</td></tr>';
            break;

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Normal Attributes without Text
        default:
          $selected = 0;
          $products_options_array = array();
// WebMakers.com Added: Attributes Sorter and Quantity Discounts
          $new_fields=', pa.products_options_sort_order, pa.attributes_price_onetime, pa.attributes_display_only, pa.product_attribute_is_free, pa.products_attributes_weight, pa.products_attributes_weight_prefix, pa.attributes_default, pa.attributes_qty_prices_onetime, pa.attributes_discounted, pa.attributes_price_factor, pa.attributes_price_factor_offset';
          $new_fields_weight=', pa.products_attributes_weight_prefix, pa.products_attributes_weight ';
          if ( PRODUCTS_OPTIONS_SORT_BY_PRICE =='1' ) {
            $order_by= ' order by LPAD(pa.products_options_sort_order,11,"0"), pov.products_options_values_name';
          } else {
            $order_by= ' order by LPAD(pa.products_options_sort_order,11,"0"), pa.options_values_price';
          }
          $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix" . $new_fields . $new_fields_weight . " from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products_id . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'" . $order_by);
          $count_it = tep_db_num_rows($products_options_query);
          if ($count_it==1) {
            echo '<tr><td class="main" valign="top">' . $products_options_name['products_options_name'] . ':&nbsp;</td><td class="main" valign="top">';
            while ($products_options = tep_db_fetch_array($products_options_query)) {

              $show_details='';
              $show_weight='';
              $show_free='';

          $the_products_price = tep_get_products_price(tep_get_prid($products_id),true);
          $show_attributes_price= ($products_options['options_values_price'] + tep_get_attributes_price_factor($the_products_price, tep_get_products_special_price($product_info['products_id']), $products_options['attributes_price_factor'], $products_options['attributes_price_factor_offset']));

              if (!empty($products_options['attributes_qty_prices_onetime'])) {
                $attributes_qty_prices_onetime_counter++;
              }

              if ($products_options['attributes_price_onetime'] !=0 ) {
                $one_time_attributes_note='true';
              }

// mark free attributes
              if ($products_options['product_attribute_is_free']=='1') {
                $show_free=' - FREE';
              }

// show weight
              if ($products_options['products_attributes_weight']!=0) {
                if (SHOW_PRODUCT_INFO_ATTRIBUTES_WEIGHT=='1') {
                  if ($products_options['products_attributes_weight']) {
                    $show_weight=' (' . $products_options['products_attributes_weight_prefix'] . round($products_options['products_attributes_weight'],2) . PRODUCTS_WEIGHT_UNITS_TEXT . ')';
                  }
                }
              }

              if ($products_options['options_values_price'] != '0' or $products_options['attributes_price_onetime'] != 0) {
                $show_details = '(';
                $show_details .= ($show_attributes_price !=0 ? $products_options['price_prefix'] . $currencies->display_price($show_attributes_price, tep_get_tax_rate($product_info['products_tax_class_id'])) : '');
                $show_details .= ( ($products_options['attributes_price_onetime']) !=0 ? ' *' . $currencies->display_price( ($products_options['attributes_price_onetime']), tep_get_tax_rate($product_info['products_tax_class_id']) ) : '');
                $show_details .= ')&nbsp';
              }
              $show_details .= $show_weight . $show_free;

              echo tep_draw_radio_field('id[' . $products_options_name['products_options_id'] . ']', $products_options['products_options_values_id'], true );

              echo $products_options['products_options_values_name'] . '&nbsp;' . $show_details;
              echo '<br>';
            } // EOF: while options
/////////////////////////////////////////////////////////////////////////////////
          } else { // count > 1
            echo '<tr><td class="main" valign="top">' . $products_options_name['products_options_name'] . ':&nbsp;</td><td valign="top">' . "\n";
            while ($products_options = tep_db_fetch_array($products_options_query)) {
// WebMakers.com Added: Attributes Sorter and Quantity Discounts - one time attributes
              if (!empty($products_options['attributes_qty_prices_onetime'])) {
                $attributes_qty_prices_onetime_counter++;
              }

              if ($products_options['attributes_price_onetime'] !=0 ) {
                $one_time_attributes_note='true';
              }

          $the_products_price = tep_get_products_price(tep_get_prid($products_id),true);
          $show_attributes_price= ($products_options['options_values_price'] + tep_get_attributes_price_factor($the_products_price, tep_get_products_special_price($product_info['products_id']), $products_options['attributes_price_factor'], $products_options['attributes_price_factor_offset']));

              $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);

              if ($show_attributes_price != 0 or $products_options['attributes_price_onetime'] != 0) {
                  $products_options_array[sizeof($products_options_array)-1]['text'] .=
                  ' (' .
                  ($show_attributes_price !=0 ? $products_options['price_prefix'] . $currencies->display_price($show_attributes_price, tep_get_tax_rate($product_info['products_tax_class_id'])) : '') .
                  ( ($products_options['attributes_price_onetime']) !=0 ? ' *' . $currencies->display_price( ($products_options['attributes_price_onetime']), tep_get_tax_rate($product_info['products_tax_class_id'])) : '') .
                  ')';
              }
// mark free attributes
              if ($products_options['product_attribute_is_free']=='1') {
                $products_options_array[sizeof($products_options_array)-1]['text'].=' - FREE';
              }

// show weight
              if ($products_options['products_attributes_weight']!=0) {
                if (SHOW_PRODUCT_INFO_ATTRIBUTES_WEIGHT=='1') {
                  if (!$products_options['products_attributes_weight']) {
                    $show_weight='';
                  } else {
                    $show_weight=' (' . $products_options['products_attributes_weight_prefix'] . round($products_options['products_attributes_weight'],2) . PRODUCTS_WEIGHT_UNITS_TEXT . ')';
                  }
                  $products_options_array[sizeof($products_options_array)-1]['text'].= $show_weight;
                }
              }

// find default attribute if set to default
              if ($products_options['attributes_default']=='1') {
                $selected_dropdown=$products_options['products_options_values_id'];
              }
            }

// default attribute based on attributes_default
              echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_dropdown );
          } // if count
          echo '</td></tr>';
          break;
////////////////////////////////////////////////////////////////////
        } // end case


// BOF: WebMakers.com Added: Include separate spacing for between attributes
        if ($products_options_name['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT and SHOW_SPACE_TEXT_ATTRIBUTES > 0) {
          echo '<tr><td colspan="2" valign="top">' . tep_draw_separator('pixel_trans.gif', '100%', SHOW_SPACE_TEXT_ATTRIBUTES) . '</td></tr>';
        }
        if ($products_options_name['products_options_type'] != PRODUCTS_OPTIONS_TYPE_TEXT and SHOW_SPACE_NORMAL_ATTRIBUTES > 0) {
          echo '<tr><td colspan="2" valign="top">' . tep_draw_separator('pixel_trans.gif', '100%', SHOW_SPACE_NORMAL_ATTRIBUTES) . '</td></tr>';
        }
// EOF: WebMakers.com Added: Include separate spacing for between attributes

// dogu 2003-02-28 insert closing bracket due to if statement
      } // if attribute count

      echo '</table>';
?>

<?php
// BOF: WebMakers.com Added: If Attributes prices, note price discounts based on attributes_price_onetime
if ($one_time_attributes_note=='true') {
?>
      <tr>
        <td class="main" valign="top"><?php echo ONE_TIME_CHARGES_APPLY; ?></td>
      </tr>
<?php
}
?>

<?php
////////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Attributes Qty:Prices Ontime Charges Table
// if ($products_options['attributes_qty_prices_onetime'] !=0 or $products_options_array['attributes_qty_prices_onetime'] !=0) {
if ($attributes_qty_prices_onetime_counter != 0) {
  if (SHOW_ATTRIBUTES_QTY_PRICES_ONETIME_TABLE=='0') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="2"><table border="2" cellpadding="2" cellspacing="2">
          <tr>
            <td align="center" class="main"><a href="<?php echo 'attributes_qty_prices_table_popup.php?products_id=' . $products_id . '&tax=' . tep_get_tax_rate($product_info['products_tax_class_id']); ?>" onclick="NewWindow(this.href,'name','600','500','yes');return false;"><?php echo tep_image(DIR_WS_IMAGES . SHOW_ATTRIBUTES_QTY_PRICES_ONETIME_IMAGE,ATTRIBUTES_QTY_PRICE_ONETIME_TITLE); ?><br><?php echo '&nbsp;' . ATTRIBUTES_QTY_PRICE_ONETIME_TITLE . '&nbsp;'; ?></a></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
    require(DIR_WS_INCLUDES . 'attributes_qty_prices_table.php');
  }
}
// EOF: WebMakers.com Added: Attributes Qty:Prices Ontime Charges Table
////////////////////////////////////////////////////////////////////////
?>

<?php
// BOF: WebMakers.com Added: If Attributes prices, note price discounts
?>
<?php
if ($product_info['products_priced_by_attribute']=='1' and tep_get_products_price_quantity_discount_on($product_info['products_id'])) {
?>
      <tr>
        <td class="main">**Discounts may vary based on selected options</td>
      </tr>
<?php
}
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

<?php
// EOF: WebMakers.com Added: If Attributes prices, note price discounts
    } // attributes > 0
?>

<?php
/////////////////////////////////////////////////////////////////////////////
// BOF: ATTRIBUTES //////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
?>
