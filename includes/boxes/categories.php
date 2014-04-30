<?php
/*
  $Id: categories.php,v 1.1.1.1 2004/03/04 23:42:13 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
$count=0;

 function tep_show_category($counter,$count) {
    global $foo, $categories_string, $id;

$count++;
    if ($count != 1) {
$image=2;
$HEIGHT = 20;
     }else{
$image=1;
$HEIGHT = 21;
}

    if ($foo[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
      $cPath_new = 'cPath=' . $foo[$counter]['path'];
    }


    $categories_string .= '<tr><td width="100%"';

    if ( ($id) && (in_array($counter, $id)) ) {
    $categories_string .= ' background="' . DIR_WS_TEMPLATE_IMAGES.'infobox/r' .$image.'.gif" name="' . $foo[$counter]['name'] . '" width="100%"  height="' .$HEIGHT . '" border="0" style="padding-left:39px;padding-right:5px;">';
    $categories_string .= '<a class="navBlue" href="';

}else{
    $categories_string .= ' background="' . DIR_WS_TEMPLATE_IMAGES.'infobox/q' .$image.'.gif" name="' . $foo[$counter]['name'] . '" width="100%"  height="' .$HEIGHT . '" border="0" style="padding-left:39px;padding-right:5px;">';
if ($foo[$counter]['parent'] != 0) {
      $class="subnavBlue";
   } else {
	$class="navGrey";
}
    $categories_string .= '<a class="' . $class . '" href="';

}
    $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
    $categories_string .= '">';
// display category name
      $categories_string .= $foo[$counter]['name'];
    $categories_string .= '</a>';
    $categories_string .= '</td></tr>';



    if ($foo[$counter]['next_id']) {
      tep_show_category($foo[$counter]['next_id'],$count);
    }

  }
?>
<!-- categories //-->
                  <tr>
                    <td>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="100%">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => '<font color="' . $font_color . '">' .BOX_HEADING_CATEGORIES . '</font>');
  new infoBoxHeading($info_box_contents, false, false);

$categories_string = '';

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query))  {
    $foo[$categories['categories_id']] = array(
                                        'name' => $categories['categories_name'],
                                        'parent' => $categories['parent_id'],
                                        'level' => 0,
                                        'path' => $categories['categories_id'],
                                        'next_id' => false
                                       );

    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories['categories_id'];
    }

    $prev_id = $categories['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }

  //------------------------
  if ($cPath) {
    $id = split('_', $cPath);
    reset($id);
    while (list($key, $value) = each($id)) {
      $new_path .= $value;
      unset($prev_id);
      unset($first_id);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
      $category_check = tep_db_num_rows($categories_query);
      while ($row = tep_db_fetch_array($categories_query)) {
        $foo[$row['categories_id']] = array(
                                            'name' => $row['categories_name'],
                                            'parent' => $row['parent_id'],
                                            'level' => $key+1,
                                            'path' => $new_path . '_' . $row['categories_id'],
                                            'next_id' => false
                                           );

        if (isset($prev_id)) {
          $foo[$prev_id]['next_id'] = $row['categories_id'];
        }

        $prev_id = $row['categories_id'];

        if (!isset($first_id)) {
          $first_id = $row['categories_id'];
        }

        $last_id = $row['categories_id'];
      }
      if ($category_check != 0) {
        $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
        $foo[$value]['next_id'] = $first_id;
      }

   	  $new_path .= '_';
    }
  }

  tep_show_category($first_element,$count);


  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => $categories_string
                              );
new $infobox_template($info_box_contents);
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
                    </table>
                    </td>
                  </tr>
<!-- categories_eof //-->
