<?php
/*
  $Id: donate.php,v 1.1.1.1 2004/03/04 23:42:14 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- whats_new //-->
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_DONATE  . '</font>');
    new infoBoxHeading($info_box_contents, false, false);
$info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
              <input type="hidden" name="cmd" value="_xclick">
              <input type="hidden" name="business" value="donations@creloaded.com">
              <input type="hidden" name="no_note" value="1">
              <input type="hidden" name="currency_code" value="USD">
              <input type="hidden" name="tax" value="0">
              <input type="image" src="images/x-click_butcc_donate.gif" border="0" name="submit"  alt="Make payments with PayPal - it\'s fast, free and secure" width="79" height="33">
            </form>');
    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- whats_new_eof //-->
