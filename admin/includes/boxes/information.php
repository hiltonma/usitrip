<?php
  /*
  Module: Information Pages Unlimited
  		  File date: 2003/03/02
		  Based on the FAQ script of adgrafics
  		  Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  */
?>
<!-- information //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_INFORMATION,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=information'));

  if ($selected_box == 'information' || $menu_dhtml == true) {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_INFORMATION_MANAGER, BOX_INFORMATION_MANAGER) .
                                   tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- information_eof //-->
