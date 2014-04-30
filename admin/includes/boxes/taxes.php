<?php
/*
  $Id: taxes.php,v 1.1.1.1 2004/03/04 23:39:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- taxes //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                     'link'  => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'));

  if ($selected_box == 'taxes' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin
//                                   '<a href="' . tep_href_link(FILENAME_COUNTRIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_COUNTRIES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_ZONES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_ZONES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_GEO_ZONES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_GEO_ZONES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_TAX_CLASSES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_TAX_RATES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_TAX_RATES . '</a>');
                                   tep_admin_files_boxes(FILENAME_COUNTRIES, BOX_TAXES_COUNTRIES) .
                                   tep_admin_files_boxes(FILENAME_ZONES, BOX_TAXES_ZONES) .
								   tep_admin_files_boxes(FILENAME_ZONES_CITY, BOX_TAXES_ZONES_CITY) .
								   tep_admin_files_boxes(FILENAME_REGIONS, BOX_CATALOG_REGION) .
								   tep_admin_files_boxes(FILENAME_CITY, BOX_CATALOG_CITIES) .
                                   tep_admin_files_boxes(FILENAME_GEO_ZONES, BOX_TAXES_GEO_ZONES) .
                                   tep_admin_files_boxes(FILENAME_TAX_CLASSES, BOX_TAXES_TAX_CLASSES) .
                                   tep_admin_files_boxes(FILENAME_TAX_RATES, BOX_TAXES_TAX_RATES));
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- taxes_eof //-->
