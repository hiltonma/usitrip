<?php
/*
  $Id: customer_gv.php,v 1.1.1.1 2003/09/18 19:05:49 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- Tell Customer he has a gift voucher-->
<?php
  if ($customer_id) {
    $gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='".$customer_id."'");
    if ($gv_result=tep_db_fetch_array($gv_query)) $customer_gv_amount=$gv_result['amount'];
  }
if ($customer_gv_amount>0) {
?>
          <tr>
            <td>
<?php
 $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_GIFT_VOUCHER . '</font>'
                               );

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<div align="center">You still have</br>'.$currencies->format($customer_gv_amount).'</br>left to spend in your Gift Voucher Account<br><br><a href="'.tep_href_link(FILENAME_GV_SEND).'">Send to a Friend</a>'
                              );

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?php
}
?>
<!-- Tell Customer he has a gift voucher_eof //-->
