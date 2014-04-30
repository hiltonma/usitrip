<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- categories //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CATEGORIES1 . '</font>'
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form action="' . tep_href_link(FILENAME_DEFAULT) . '" method="get">' . tep_hide_session_id(),
                               'align' => 'left',
                               'text'  => tep_draw_pull_down_menu('cPath', tep_get_categories(array(array('id' => '', 'text' => PULL_DOWN_DEFAULT))), $cPath, 'onchange="this.form.submit();"')
                              );
  new infoBox($info_box_contents);
   if (TEMPLATE_INCLUDE_FOOTER =='true'){
       $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                      'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
   new infoboxFooter($info_box_contents);
 }
?>
            </td>
          </tr>
<!-- categories_eof //-->
