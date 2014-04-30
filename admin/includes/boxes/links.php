<?php
/*
  $Id: links.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- links //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LINKS,
                     'link'  => tep_href_link(FILENAME_LINKS, 'selected_box=links'));

 if ($selected_box == 'links' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//				   '<a href="' . tep_href_link(FILENAME_LINKS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LINKS_LINKS . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LINKS_LINK_CATEGORIES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_LINKS_CONTACT, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LINKS_LINKS_CONTACT . '</a>');
                                   tep_admin_files_boxes(FILENAME_LINKS, BOX_LINKS_LINKS) .
                                   tep_admin_files_boxes(FILENAME_LINK_CATEGORIES, BOX_LINKS_LINK_CATEGORIES) .                 tep_admin_files_boxes(FILENAME_LINKS_CONTACT, BOX_LINKS_LINKS_CONTACT) . 
                                   tep_admin_files_boxes('links_manage.php', '友情链接分配管理'));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- links_eof //-->
