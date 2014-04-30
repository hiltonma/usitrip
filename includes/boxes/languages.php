<?php
/*
  $Id: languages.php,v 1.1.1.1 2004/03/04 23:42:25 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- languages //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_LANGUAGES . '</font>');
  new infoBoxHeading($info_box_contents, false, false);

  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    include(DIR_FS_CLASSES . 'language.php');
    $lng = new language;
  }

  $languages_string = '';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
    $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text' => $languages_string);

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
<!-- languages_eof //-->
