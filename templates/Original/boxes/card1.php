<?php
/*
  Card Infobox, v 1.0 2002/12/04 by Kevin Park

  osCommerce
  http://www.oscommerce.com/

  Copyright (c) 2000,2001 osCommerce

  Released under the GNU General Public License

http://www.movion.com
go to any packages and see custom shop demo

  -------------------------------------------------------------------------------------------------------
  If you find my work usefull you can send me some money ...
  https://www.paypal.com/xclick/business=elari%40free.fr&item_name=elari&no_note=1&tax=0&currency_code=EUR
  If you need a custom install for Oscommerce, contact me....
  -------------------------------------------------------------------------------------------------------

*/
?>
<!-- Card Info Box //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CARD1 . '</font>'
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
//                               'text'  => tep_image(DIR_WS_IMAGES . '/cards/cards.gif') .
//elari chanegd to provide a link to your payment acount
                               'text'  => tep_image(DIR_WS_IMAGES . 'cards/logo-xclick_paypal.gif' , BOX_INFORMATION_CARD . MODULE_PAYMENT_PAYPAL_ID).
                               '<br>' . tep_image(DIR_WS_IMAGES . 'cards/cards2.gif', BOX_INFORMATION_CARD . MODULE_PAYMENT_PAYPAL_ID) . '</a><br>'
                               );

new infoBox($info_box_contents);
?>
</td></tr>
<!-- card_eof //-->
