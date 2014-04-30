<?php
/*
  $Id: theme_select.php,v 1.1.1.1 2004/03/04 23:42:27 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
    if (tep_session_is_registered('customer_id')) {

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {

?>
<!-- theme //-->
         <tr>
            <td>
<?php

  $info_box_contents = array();
$info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_TEMPLATE_SELECT . '</font>');
  new infoBoxHeading($info_box_contents, false, false);

  $template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where active = '1' order by template_name");

 
// Display a drop-down
    $select_box = '<select name="template" onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%">';
    if (MAX_THEME_LIST < 2) {
      $select_box .= '<option value="">' . PULL_DOWN_DEFAULT . '</option>';
    }
    while ($template_values = tep_db_fetch_array($template_query)) {
      $select_box .= '<option value="' . $template_values['template_name'] . '"';
      if ($HTTP_GET_VARS['template_id'] == $template_values['template_id']) $select_box .= ' SELECTED';
      $select_box .= '>' . substr($template_values['template_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
    }
    $select_box .= "</select>";
    $select_box .= tep_hide_session_id();

    $info_box_contents = array();
    $info_box_contents[] = array('form'  => '<form name="template" method="post" action="' . tep_href_link(FILENAME_DEFAULT, '&action=update_template', 'NONSSL') . '">',
                                 'align' => 'left',
                                 'text'  => $select_box);

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
<?php
}}
?>
<!-- template_eof //-->
