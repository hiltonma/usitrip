<?php
/*
  search_header.php



*/
?>
<!-- search_header //-->
        <table>
          <tr>
            <td align="center">
<?php
  $search_string = '';
    $search_string .=   tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');
    $search_string .=   tep_draw_input_field('keywords', '', 'size="10" maxlength="30" style="width: ' . (100) . 'px"') . '&nbsp;'

. tep_hide_session_id() . tep_template_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH);
    $search_string .= '</form>';
echo $search_string;
?>


<?php
/*
  if (!is_object($lng)) {
    include(DIR_FS_CLASSES . 'language.php');
    $lng = new language;
  }

  if (getenv('HTTPS') == 'on') $connection = 'SSL';
  else $connection = 'NONSSL';

  $languages_string = '';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
    $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $connection) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
  }

echo $languages_string;
*/
?>
            </td>
          </tr>
        </table>
<!-- languages_header_eof //-->
