<?php
/*
  $Id: manufacturer_info.php,v 1.1.1.1 2004/03/04 23:42:26 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- manufacturer_info //-->
<?php
  if (isset($HTTP_GET_VARS['products_id'])) {
    $manufacturer_query = tep_db_query("select p.products_id, p.manufacturers_id, m.manufacturers_id as manf_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from  " . TABLE_PRODUCTS . " p, " . TABLE_MANUFACTURERS . " m, " . TABLE_MANUFACTURERS_INFO . " mi where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and m.manufacturers_id = p.manufacturers_id and mi.manufacturers_id = m.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'");
//    $manufacturer_query = tep_db_query("select p.products_id, p.manufacturers_id from  " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");

   while ($manufacturer = tep_db_fetch_array($manufacturer_query)) {;
?>

          <tr>
            <td>
          
<?php
// echo 'prod-id' . (int)$HTTP_GET_VARS['products_id'] . 'manid' . $manufacturer['manufacturers_id'];
      $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_MANUFACTURER_INFO . '</font>');
      new infoBoxHeading($info_box_contents, false, false);

      $manufacturer_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
      if (tep_not_null($manufacturer['manufacturers_image'])) $manufacturer_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2">' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '</td></tr>';
      if (tep_not_null($manufacturer['manufacturers_url'])) $manufacturer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></td></tr>';
      $manufacturer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manf_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . ' ' . $manufacturer['manufacturers_name'] . '</a></td></tr>' .
                                   '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $manufacturer_info_string);

      new infoBox($info_box_contents);
?>
            </td>
          </tr>
          
          <?php
	     }
	    }
?>
<!-- manufacturer_info_eof //-->