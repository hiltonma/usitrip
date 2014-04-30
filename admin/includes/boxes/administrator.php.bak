<?php
/*
  $Id: administrator.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_ADMINISTRATOR,
                     'link'  => tep_href_link(FILENAME_ADMIN_MEMBERS, tep_get_all_get_params(array('selected_box')) . 'selected_box=administrator'));

  if ($selected_box == 'administrator' || $menu_dhtml == true) {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_ADMIN_MEMBERS, BOX_ADMINISTRATOR_MEMBERS) .
                                   tep_admin_files_boxes(FILENAME_ADMIN_ACCOUNT, BOX_ADMINISTRATOR_ACCOUNT_UPDATE) .
                                   tep_admin_files_boxes(FILENAME_ADMIN_FILES, BOX_ADMINISTRATOR_BOXES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
