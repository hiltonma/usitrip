<?php
/*
  $Id: categories3.php,v 1.1 2004/04/05

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004osCommerce

  Released under the GNU General Public License
*/

// Categories_tree written by Gideon Romm from Symbio Technologies, LLC

function tep_show_category3($cid, $cpath, $COLLAPSABLE) {
  global $categories_string3, $languages_id, $HTTP_GET_VARS;
  global $level;
  $selectedPath = array();

// Get all of the categories on this level

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = " . $cid . " and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");

  while ($categories = tep_db_fetch_array($categories_query))  {
    if ($level{$categories['parent_id']} == "") { $level{$categories['parent_id']} = 0; }
    $level{$categories['categories_id']} = $level{$categories['parent_id']} + 1;

// Add category link to $categories_string3
    for ($a=1; $a<$level{$categories['categories_id']}; $a++) {
      $categories_string3 .= "&nbsp;&nbsp;";
    }

    $categories_string3 .= '<a href="';

    $cPath_new = $cpath;
    if ($level{$categories['parent_id']} > 0) {
      $cPath_new .= "_";
    }
    $cPath_new .= $categories['categories_id'];

    $cPath_new_text = "cPath=" . $cPath_new;

    $categories_string3 .= tep_href_link(FILENAME_DEFAULT, $cPath_new_text);
    $categories_string3 .= '">';

    if ($HTTP_GET_VARS['cPath']) {
      $selectedPath = split("_", $HTTP_GET_VARS['cPath']);
    }

    if (in_array($categories['categories_id'], $selectedPath)) { $categories_string3 .= '<b>'; }

    if ($level{$categories['categories_id']} == 1) { $categories_string3 .= '<u>'; }

    $categories_string3 .= $categories['categories_name'];
    if ($COLLAPSABLE && tep_has_category_subcategories($categories['categories_id'])) { $categories_string3 .= ' ->'; }


    if ($level{$categories['categories_id']} == 1) { $categories_string3 .= '</u>'; }

    if (in_array($categories['categories_id'], $selectedPath)) { $categories_string3 .= '</b>'; }

    $categories_string3 .= '</a>';

    if (SHOW_COUNTS) {
      $products_in_category = tep_count_products_in_category($categories['categories_id']);
      if ($products_in_category > 0) {
        $categories_string3 .= '&nbsp;(' . $products_in_category . ')';
      }
    }

    $categories_string3 .= '<br>';


// If I have subcategories, get them and show them
    if (tep_has_category_subcategories($categories['categories_id'])) {

      if ($COLLAPSABLE) {
        if (in_array($categories['categories_id'], $selectedPath)) {
          tep_show_category3($categories['categories_id'], $cPath_new, $COLLAPSABLE);
        }
      }
      else { tep_show_category3($categories['categories_id'], $cPath_new, $COLLAPSABLE); }

    }

  }

}
?>


<!-- categories //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CATEGORIES3 . '</font>'
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $categories_string3 = '';

// tep_show_category3(<top category_id>, <top cpath>, <1=Collapsable tree, 0=static--show all>)
  tep_show_category3(0,'',0);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $categories_string3
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
