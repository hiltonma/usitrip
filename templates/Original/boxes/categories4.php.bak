<?php
/*
  $Id: categories4.php,v 1.0

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License


*/

  function tep_show_category4($counter) {
    global $foo, $categories_string4, $id, $aa;

    for ($a=0; $a<$foo[$counter]['level']; $a++) {
      if ($a == $foo[$counter]['level']-1)
	  	{
		$categories_string4 .= "<font color='#ff0000'>|__</font>";
     	} else
	  		{
	  		$categories_string4 .= "<b><font color='#ff0000'>&nbsp;&nbsp;&nbsp;&nbsp;</font>";
     		}

		}
    if ($foo[$counter]['level'] == 0)
	{
		if ($aa == 1)
		{
		$categories_string4 .= "<hr>";
	    }
		else
		{$aa=1;}

	}

if ($foo[$counter]['parent'] == 0) {
      $categories_string4 .= '<b>';
    }

    $categories_string4 .= '<nobr><a nowrap href="';

    if ($foo[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
      $cPath_new = 'cPath=' . $foo[$counter]['path'];
    }

    $categories_string4 .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
    $categories_string4 .= '">';

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string4 .= "<b><font color='#ff0000'>";
    }

// display category name
    $categories_string4 .= $foo[$counter]['name'];

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string4 .= '</font></b>';
    }

      if ($foo[$counter]['parent'] == 0) {
       $categories_string4 .= ' </b>';
    }
    if (tep_has_category_subcategories($counter)) {
      $categories_string4 .= '-&gt; </b>';
    }

    $categories_string4 .= '</nobr></a>';

    if (SHOW_COUNTS == 'true') {
      $products_in_category = tep_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $categories_string4 .= '&nbsp;(' . $products_in_category . ')';
      }    }

    $categories_string4 .= '<br>';

    if ($foo[$counter]['next_id']) {
      tep_show_category4($foo[$counter]['next_id']);
    }
  }
?>
<!-- categories //-->
          <tr>
            <td>
<?php
  $aa = 0;
  $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CATEGORIES4 . '</font>');
  new infoBoxHeading($info_box_contents, false, false);

  $categories_string4 = '';

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
    $new_path = '';
    $id = split('_', $cPath);
    reset($id);
    while (list($key, $value) = each($id)) {
      unset($prev_id);
      unset($first_id);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
      $category_check = tep_db_num_rows($categories_query);
      if ($category_check > 0) {
        $new_path .= $value;
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
        $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
        $foo[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
   tep_show_category4($first_element);

 //coment out the below line if you do not want to havw an all products list
    $categories_string4 .= "<hr>\n";
 $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODS) . '"><b>' . BOX_INFORMATION_ALLPRODS . "</b></a>\n";
    $categories_string4 .= "-&gt;<br><hr>\n";
 $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODCATS) . '"><b>' . ALL_PRODUCTS_LINK . "</b></a>\n";
    $categories_string4 .= "-&gt;<br><hr>\n";
 $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODMANF) . '"><b>' . ALL_PRODUCTS_MANF . "</b>\n";
    $categories_string4 .= "-&gt;<br>\n";


   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                                'text'  => $categories_string4
                               );
   new infoBox($info_box_contents);
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                  'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
  new infoboxFooter($info_box_contents, true, true);
?>

		    </td>
          </tr>
<!-- categories_eof //-->
