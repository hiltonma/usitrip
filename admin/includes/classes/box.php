<?php
/*
  $Id: box.php,v 1.1.1.1 2004/03/04 23:39:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

  class box extends tableBlock {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function infoBox($heading, $contents) {
      $this->table_row_parameters = 'class="infoBoxHeading"';
      $this->table_data_parameters = 'class="infoBoxHeading"';
      $this->heading = $this->tableBlock($heading);

      $this->table_row_parameters = '';
      $this->table_data_parameters = 'class="infoBoxContent"';
      $this->contents = $this->tableBlock($contents);

      return $this->heading . $this->contents;
    }

    function menuBox($heading, $contents) {

    global $menu_dhtml;              // add for dhtml_menu
    if ($menu_dhtml == false ) {     // add for dhtml_menu

      $this->table_data_parameters = 'class="menuBoxHeading"';
      if ($heading[0]['link']) {
        $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
        $heading[0]['text'] = '&nbsp;<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>&nbsp;';
      } else {
        $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
      }
      $this->heading = $this->tableBlock($heading);
      $this->table_data_parameters = 'class="menuBoxContent"';
      $this->contents = $this->tableBlock($contents);
      return $this->heading . $this->contents . $dhtml_contents;
// ## add for dhtml_menu
    } else {
      //$selected = substr(strrchr ($heading[0]['link'], '='), 1);
      /* 为什么不用上面这句，而改用下面这么长一段，因为并不一定最后一个参数就是这个菜单的控制参数，
       * 所以改用下面的这段，从所有参数中优先查找selected_box，如果没有，则再找action，这样找出来的才是最正确的，
       * 之前导航二级菜单不显示的问题就在这里。 by lwkai add 2012-07-24
       */
      $selected_par = substr(strrchr($heading[0]['link'], '?'), 1);
      $temp_arr = explode("&", $selected_par);
      $url_get = array();
      foreach ($temp_arr as $val) {
      	$temp = explode("=", $val);
      	$url_get[trim(trim(strtolower($temp[0])))] = $temp[1];
      }
      if (isset($url_get['selected_box']) && $url_get['selected_box'] != '') {
      	$selected = trim($url_get['selected_box']);//,array('action','selected_box'))) {
      } elseif (isset($url_get['action']) && $url_get['action'] != ''){
      	$selected = trim($url_get['action']);
      }
      // 二级菜单控制代码获取结束。 by lwkai add 2012-07-24
      
      
      //echo '$selected=' . $selected . '<br/><br/><br/><br/><br/>';
      $dhtml_contents = $contents[0]['text'];
      $change_style = array ('<br>'=>' ','<BR>'=>' ', 'a href='=> 'a class="menuItem" href=','class="menuBoxContentLink"'=>' ');
      $dhtml_contents = strtr($dhtml_contents,$change_style);
      $dhtml_contents = '<div id="'.$selected.'Menu" class="menu" onmouseover="menuMouseover(event)">'. $dhtml_contents . '</div>';
      return $dhtml_contents;
      }
// ## eof add for dhtml_menu
    }
  }
?>
