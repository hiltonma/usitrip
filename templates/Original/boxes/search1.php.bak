<?php
/*
  $Id: search1.php,v 1.1.1.1 2004/03/04 23:42:16 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- search //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SEARCH1 . '</font>');
  new infoBoxHeading($info_box_contents, false, false);

  $hide = tep_hide_session_id();
  $info_box_contents = array();
  $info_box_contents[] = array('form'  => '<form name="quick_find" method="get" action="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false) . '">',
                               'align' => 'center',
                               'text'  => $hide . '<input type="text" name="keywords" size="10" maxlength="30" value="' . tep_htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["keywords"])) . '" style="width: ' . (BOX_WIDTH-30) . 'px">&nbsp;' . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH) . '<br>' . BOX_SEARCH_TEXT . '<br><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><b>' . BOX_SEARCH_ADVANCED_SEARCH . '</b></a>'
                              );

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- search_eof //-->
