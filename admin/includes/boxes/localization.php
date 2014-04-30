<?php
/*
  $Id: localization.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- localization //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCALIZATION,
                     'link'  => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization'));

  if ($selected_box == 'localization' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin
//                                   '<a href="' . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_CURRENCIES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_LANGUAGES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_LANGUAGES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_ORDERS_STATUS . '</a>');
                                   tep_admin_files_boxes(FILENAME_CURRENCIES, BOX_LOCALIZATION_CURRENCIES) .
                                   tep_admin_files_boxes(FILENAME_LANGUAGES, BOX_LOCALIZATION_LANGUAGES) .
                                   tep_admin_files_boxes(FILENAME_ORDERS_STATUS, BOX_LOCALIZATION_ORDERS_STATUS).
																	 tep_admin_files_boxes(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, BOX_LOCALIZATION_PROVIDERS_ORDERS_PROD_STATUS));
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- localization_eof //-->
