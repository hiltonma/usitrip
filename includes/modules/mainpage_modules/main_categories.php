<?php
/*
  $Id: main_categories.php,v 1.0a 2002/08/01 10:37:00 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com/

  Copyright (c) 2002 Barreto
  Gustavo Barreto <gustavo@barreto.net>
  http://www.barreto.net/

  Based on: all_categories.php Ver. 1.6 by Christian Lescuyer

  History: 1.0 Creation
	   1.0a Correction: Extra Carriage Returns
	   1.1  added parameters to change display options -- mdt

  Released under the GNU General Public License

*/

//------------------------------------------------------------------------------------------------------
// PARAMETERS
//------------------------------------------------------------------------------------------------------

$item_column_number = 3;		// range of 1 to 9
$item_title_on_newline = true;	// true or false

// for item and subcategory options, suugest that you just put in CSS code
// you can also just define a class and then change it in a template addon like BTS
$item_div_options = 'style="text-align:center;font-weight:bold;font-size:larger;margin-top:5px;"';
$item_subcategories_options = '';

//------------------------------------------------------------------------------------------------------
// CODE - do not change below here
//------------------------------------------------------------------------------------------------------

// error checking on parameters
if($item_column_number < 1)
{
	$item_column_number = 1;
}
if($item_column_number > 9)
{
	$item_column_number = 9;
}
if($item_title_on_newline)
{
	$item_separator = '<br>';
} else {
	$item_separator = '&nbsp;';
}

// Preorder tree traversal
  function preorder($cid, $level, $foo1, $cpath)
  {
    global $categories_string, $HTTP_GET_VARS;

// Display link
    if ($cid != 0) {
      for ($i=0; $i<$level; $i++)
        $categories_string .=  '&nbsp;&nbsp;';
      $categories_string .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath
=' . $cpath . $cid) . '">';
// 1.6 Are we on the "path" to selected category?
      $bold = strstr($HTTP_GET_VARS['cPath'], $cpath . $cid . '_') || $HTTP_GET_VARS['cPath'] == $cpath . $cid;
// 1.6 If yes, use <b>
      if ($bold)
        $categories_string .=  '<b>';
      $categories_string .=  $foo1[$cid]['name'];
      if ($bold)
        $categories_string .=  '</b>';
      $categories_string .=  '</a>';
// 1.4 SHOW_COUNTS is 'true' or 'false', not true or false
      if (SHOW_COUNTS == 'true') {
        $products_in_category = tep_count_products_in_category($cid);
        if ($products_in_category > 0) {
          $categories_string .= '&nbsp;(' . $products_in_category . ')';
        }
      }
      $categories_string .= '<br>';
    }

// Traverse category tree
    if (is_array($foo1)) {
      foreach ($foo1 as $key => $value) {
        if ($foo1[$key]['parent'] == $cid) {
          preorder($key, $level+1, $foo1, ($level != 0 ? $cpath . $cid . '_' : ''));
        }
      }
    }
  }

?>
<!-- main_categories //-->
          <tr>
            <td>
<?php
//////////
// Display box heading
//////////
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text'  => BOX_HEADING_CATEGORIES_MAIN_PAGE);
  new contentBoxHeading($info_box_contents, '');


//////////
// Get categories list
//////////
// 1.2 Test for presence of status field for compatibility with older versions
  $status = tep_db_num_rows(tep_db_query('describe categories status'));

  $query = "select c.categories_id, cd.categories_name, c.parent_id, c.categories_image
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION .
" cd
            where c.categories_id = cd.categories_id";
// 1.3 Can't have 'where' in an if statement!
  if ($status >0)
    $query.= " and c.status = '1'";
  $query.= " and cd.language_id='" . $languages_id ."'
            order by sort_order, cd.categories_name";

  $categories_query = tep_db_query($query);


// Initiate tree traverse
$categories_string = '';
preorder(0, 0, $foo1, '');

//////////
// Display box contents
//////////

$info_box_contents = array();

$row = 0;
$col = 0;
while ($categories = tep_db_fetch_array($categories_query))
{
	if ($categories['parent_id'] == 0)
   	{
   		$cPath_new = tep_get_path($categories['categories_id']);
    	$text_subcategories = '';
    	$subcategories_query = tep_db_query($query);
    	while ($subcategories = tep_db_fetch_array($subcategories_query))
    	{
     		if ($subcategories['parent_id'] == $categories['categories_id'])
 			{
                $cPath_new_sub = "cPath="  . $categories['categories_id'] . "_" . $subcategories['categories_id'];
                $text_subcategories .= ' <a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new_sub, 'NONSSL') . '">';
                $text_subcategories .= $subcategories['categories_name'] . '</a>' . " ";

        	} // if ($subcategories['parent_id'] == $categories['categories_id'])

    	} // while ($subcategories = tep_db_fetch_array($subcategories_query))

		$info_box_contents[$row][$col] = array('align' => 'left',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<div '. $item_div_options . '><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></DIV></DIV>');
//                                           'text' => '<div '. $item_div_options . '><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' .  tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '</a>' . $item_separator . '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . $categories['categories_name'] . '</a><DIV ' . $item_subcategories_options . '>' . $text_subcategories . '</DIV></DIV>');
//      echo '                <td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";

    	// determine the column position to see if we need to go to a new row
    	$col ++;
    	if ($col > ($item_column_number - 1))
    	{
      		$col = 0;
      		$row ++;

    	} //if ($col > ($number_of_columns - 1))

    } //if ($categories['parent_id'] == 0)

} // while ($categories = tep_db_fetch_array($categories_query))

//output the contents
new contentBox($info_box_contents);
if (TEMPLATE_INCLUDE_FOOTER =='true'){
     $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                    'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
 new contentBoxFooter($info_box_contents);
 } 

?>
            </td>
          </tr>
<!-- main_categories_eof //-->
