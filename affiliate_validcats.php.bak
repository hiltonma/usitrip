<?php
/*
  $Id: affiliate_validcats.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD_CAT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
if ( file_exists(DIR_FS_INCLUDES . 'header_tags.php') ) {
	  require(DIR_FS_INCLUDES . 'header_tags.php');
	} else {
		?> 
		  <title><?php echo TITLE; ?></title>
		  <?php
		}
?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES.TEMPLATE_NAME;?>/css/main.css" type="text/css"/>
<head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">

	<table width="755"  align="center" class="infoBoxContents">
	<!--amit added to show categories structure start -->
<tr>
<td>
<?php

  function tep_show_category($counter) {
    global $tree, $categories_string, $cPath_array;

    for ($i=0; $i<$tree[$counter]['level']; $i++) {
      $categories_string .= "&nbsp;&nbsp;";
    }

    $categories_string .= $counter. '&nbsp;&nbsp;<a href="';

    if ($tree[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
     $cPath_new = 'cPath=' . $tree[$counter]['path'];
	 // $cPath_new = 'cPath=' . $counter;
    }

    $categories_string .= tep_href_link(FILENAME_AFFILIATE_VALIDCATS, $cPath_new) . '">';

    if (isset($cPath_array) && in_array($counter, $cPath_array)) {
      $categories_string .= '<b>';
    }

// display category name
    $categories_string .= db_to_html($tree[$counter]['name']);

    if (isset($cPath_array) && in_array($counter, $cPath_array)) {
      $categories_string .= '</b>';
    }

    if (tep_has_category_subcategories($counter)) {
      $categories_string .= '-&gt;';
    }

    $categories_string .= '</a>';

    if (SHOW_COUNTS == 'true') {
      $products_in_category = tep_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $categories_string .= '&nbsp;(' . $products_in_category . ')';
      }
    }

	//simple products hack starts here -------
if(isset($_GET['cPath']) &&  $_GET['cPath'] != '' && $_GET['view'] != 'allproducts'){
	$maincatidarray = explode('_',$_GET['cPath']);
	$maincatidarray = array_reverse($maincatidarray);	
	$maincatid = $maincatidarray[0];
	$products_in_category_query = tep_products_in_category($counter,false,$maincatid);
	if($products_in_category_query != ''){
		while ($products_in_category = tep_db_fetch_array($products_in_category_query)) {
		  $product_id_h = 'products_id='.$products_in_category ['product_id'];
		  $product_name_h = $products_in_category ['product'];
		  $categories_string .= '<br>&nbsp;&nbsp;&nbsp;<span style="color:#ff0000;font-size:12px;" class="boxText">&nbsp;&nbsp;'.$products_in_category ['product_id'].'&nbsp;&nbsp;&nbsp;&nbsp;<a  class="productlist" target="_blank" href="'. tep_href_link(FILENAME_PRODUCT_INFO, $product_id_h). '">' .db_to_html($product_name_h) . '</a></span>';
	
		}
	}// end of innner if loop

}else if($_GET['view'] == 'allproducts'){
		$products_in_category_query = tep_products_in_category_all($counter,false);	
		while ($products_in_category = tep_db_fetch_array($products_in_category_query)) {
		  $product_id_h = 'products_id='.$products_in_category ['product_id'];
		  $product_name_h = $products_in_category ['product'];
		  $categories_string .= '<br>&nbsp;&nbsp;&nbsp;<span class="boxText">&nbsp;&nbsp;'.$products_in_category ['product_id'].'&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="'. tep_href_link(FILENAME_PRODUCT_INFO, $product_id_h). '">' .$product_name_h . '</a></span>';
		}
}
//end of simple hack --------
    $categories_string .= '<br>';

    if ($tree[$counter]['next_id'] != false) {
      tep_show_category($tree[$counter]['next_id']);
    }
  }
?>
<!-- categories //-->
			<tr>
            <td class="main"><?php echo TEXT_AFFILIATE_CLCIK_CATEGORIES;?>
			</td>
			</tr>
			<tr>
            <td class="smallText">
			<?php echo TEXT_CATEGORIES_CATEGORIESNAME; ?></br>
			<span style="color:#ff0000;"><?php echo TEXT_PRODUCTS_PRODUCTSNAME;?></span>
			</td>
			</tr>
			<tr>
            <td class="main" align="right"><b><a class="sp3" href="affiliate_validproducts.php"><?php echo TEXT_AFFILIATE_VIEW_ALL_PRODUCTS; ?></a></b>&nbsp;|&nbsp;<b><a class="sp3" href="affiliate_validcats1.php"><?php echo TEXT_AFFILIATE_VIEW_ALL_CATEGORIES; ?></a></b>&nbsp;&nbsp;
			</td>
			</tr>
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TEXT_AVAILABLE_CAT_PRODS);

  new infoBoxHeading($info_box_contents, true, true);

  $categories_string = '';
  $tree = array();

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query))  {
    $tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
                                                'parent' => $categories['parent_id'],
                                                'level' => 0,
                                                'path' => $categories['categories_id'],
                                                'next_id' => false);

    if (isset($parent_id)) {
      $tree[$parent_id]['next_id'] = $categories['categories_id'];
    }

    $parent_id = $categories['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }

  //------------------------
  if (tep_not_null($cPath)) {
    $new_path = '';
    reset($cPath_array);
    while (list($key, $value) = each($cPath_array)) {
      unset($parent_id);
      unset($first_id);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$value . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
      if (tep_db_num_rows($categories_query)) {
        $new_path .= $value;
        while ($row = tep_db_fetch_array($categories_query)) {
          $tree[$row['categories_id']] = array('name' => $row['categories_name'],
                                               'parent' => $row['parent_id'],
                                               'level' => $key+1,
                                               'path' => $new_path . '_' . $row['categories_id'],
                                               'next_id' => false);

          if (isset($parent_id)) {
            $tree[$parent_id]['next_id'] = $row['categories_id'];
          }

          $parent_id = $row['categories_id'];

          if (!isset($first_id)) {
            $first_id = $row['categories_id'];
          }

          $last_id = $row['categories_id'];
        }
        $tree[$last_id]['next_id'] = $tree[$value]['next_id'];
        $tree[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  tep_show_category($first_element); 

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $categories_string);

  new infoBox($info_box_contents); 
?>
</td>
</tr>
<!-- amit added to show categories structure end -->
<!--
<tr>
<td colspan="2" class="infoBoxHeading" align="center"><?php echo TEXT_VALID_CATEGORIES_LIST; ?></td>
</tr>

<?php     echo "<tr><td><b>". TEXT_VALID_CATEGORIES_ID . "</b></td><td><b>" . TEXT_VALID_CATEGORIES_NAME . "</b></td></tr><tr>";
    $result = mysql_query("SELECT * FROM categories, categories_description WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = '" . $languages_id . "' ORDER BY categories_description.categories_name");
    if ($row = mysql_fetch_array($result)) {
        do {
            echo "<td class='infoBoxContents'>&nbsp".$row["categories_id"]."</td>\n";
            echo "<td class='infoBoxContents'>".$row["categories_name"]."</td>\n";
            echo "</tr>\n";
        }
        while($row = mysql_fetch_array($result));
    }
    echo "</table>\n";
?>
-->
<tr>

<td colspan="2"  align="center">
<p class="smallText" align="right"><?php echo '<a class="sp3" href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?>&nbsp;&nbsp;&nbsp;</p>
</td>
</tr>
</table>

</body>
</html>
<?php require('includes/application_bottom.php'); ?>