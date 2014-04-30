<?php
/*
  $Id: shopping_cart.php,v 1.2 2004/03/09 17:56:06 ccwjr Exp $


  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/
?>
<!-- shopping_cart //-->
<script language="javascript"><!--
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

          <tr>
            <td>
<?php
  $info_box_contents = array();
$info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SHOPPING_CART . '</font>');
  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  if ($cart->count_contents() > 0) {
    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      $cart_contents_string .= $products[$i]['name'] . '</span></a></td></tr>';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
    $cart_contents_string .= '</table>';
  } else {
    $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  if ($cart->count_contents() > 0) {
    // WebMakers.com Added: Shoppe Enhancement Controller
    // check for free order
    $final_total=$cart->show_total();
    if ($final_total==0) {
      $final_total='Free';
    } else {
      $final_total=$currencies->format($cart->show_total());
    }
    $info_box_contents[] = array('text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'right',
                                 'text' => $final_total);
  }
// ICW ADDED FOR CREDIT CLASS GV
  if (tep_session_is_registered('customer_id')) {
    $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
    $gv_result = tep_db_fetch_array($gv_query);
    if ($gv_result['amount'] > 0 ) {
      $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_BALANCE . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($gv_result['amount']) . '</td></tr></table>');
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext"><a href="'. tep_href_link(FILENAME_GV_SEND) . '">' . BOX_SEND_TO_FRIEND . '</a></td></tr></table>');
    }
  }
  if (tep_session_is_registered('gv_id')) {
    $gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'");
    $coupon = tep_db_fetch_array($gv_query);
    $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_REDEEMED . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($coupon['coupon_amount']) . '</td></tr></table>');

  }
  if (tep_session_is_registered('cc_id') && $cc_id) {
    $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . CART_COUPON . '</td><td class="smalltext" align="right" valign="bottom">' . '<a href="javascript:couponpopupWindow(\'' . tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $cc_id) . '\')">' . CART_COUPON_INFO . '</a>' . '</td></tr></table>');

  }

// ADDED FOR CREDIT CLASS GV END ADDITTION
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->
