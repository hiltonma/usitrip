<?php
/*
_____________________________________________________________________

dbmtce.php Version 1.0.2 23/03/2002

osCommerce Database Maintenance Add-on
Copyright (c) 2002 James C. Logan

osCommerce, Open Source E-Commerce Solutions
Copyright (c) 2002 osCommerce
http://www.oscommerce.com

IMPORTANT NOTE:

This script is not part of the official osCommerce distribution
but an add-on contributed to the osCommerce community. Please
read the README and  INSTALL documents that are provided 
with this file for further information and installation notes.

LICENSE:

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
_____________________________________________________________________

*/
?>
<!-- Database Maintenance //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_DBMTCE,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=dbmtce'));

  if ($selected_box == 'dbmtce' || $menu_dhtml == true) {
    $contents[] = array('text'  => //'<a href="' . tep_href_link(FILENAME_DBMTCE_STATUS) . '" class="menuBoxContentLink">' . BOX_DBMTCE_STATUS . '</a><br>' .
                                //   '<a href="' . tep_href_link(FILENAME_DBMTCE_CHECK) . '" class="menuBoxContentLink">' . BOX_DBMTCE_CHECK . '</a><br>' .
                                //   '<a href="' . tep_href_link(FILENAME_DBMTCE_REPAIR) . '" class="menuBoxContentLink">' . BOX_DBMTCE_REPAIR . '</a><br>' .
                                //   '<a href="' . tep_href_link(FILENAME_DBMTCE_ANALYZE) . '" class="menuBoxContentLink">' . BOX_DBMTCE_ANALYZE . '</a><br>' .
                                //   '<a href="' . tep_href_link(FILENAME_DBMTCE_OPTIMIZE) . '" class="menuBoxContentLink">' . BOX_DBMTCE_OPTIMIZE . '</a><br>' .

								   tep_admin_files_boxes(FILENAME_DBMTCE_STATUS, BOX_DBMTCE_STATUS) .
                                   tep_admin_files_boxes(FILENAME_DBMTCE_CHECK, BOX_DBMTCE_CHECK) .
                                   tep_admin_files_boxes(FILENAME_DBMTCE_REPAIR, BOX_DBMTCE_REPAIR) .
                                   tep_admin_files_boxes(FILENAME_DBMTCE_ANALYZE, BOX_DBMTCE_ANALYZE) .
                                   tep_admin_files_boxes(FILENAME_DBMTCE_OPTIMIZE, BOX_DBMTCE_OPTIMIZE)	
									);
  }

    $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- Database Maintenance_eof //-->

