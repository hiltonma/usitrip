<?php
/*
  $Id: products_attributes.php,v 1.3 2004/03/16 22:36:34 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('feature_tour_section_a');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  if ($HTTP_GET_VARS['action']) {
    $page_info = 'option_page=' . $HTTP_GET_VARS['option_page'] . '&value_page=' . $HTTP_GET_VARS['value_page'] . '&attribute_page=' . $HTTP_GET_VARS['attribute_page'];
    switch($HTTP_GET_VARS['action']) {
      case 'add_product_options':
          $departure_city_id = $HTTP_POST_VARS['departure_city_id'];
		  $categories_id = $HTTP_POST_VARS['categories_id'];
          tep_db_query("insert into " . TABLE_FEATURE_TOUR_SECTION . " (feature_tour_section_id, departure_city_id, categories_id, tour_section, date_added) values ('" . $HTTP_POST_VARS['feature_tour_section_id'] . "', '".$departure_city_id."', '" . $categories_id . "', 'A', 'now()')");
      
        tep_redirect(tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, $page_info));
        break;

      case 'update_option_name':
          $departure_city_id = $HTTP_POST_VARS['departure_city_id'];
		  $categories_id = $HTTP_POST_VARS['categories_id'];
          $option_name = $HTTP_POST_VARS['option_name'];
          tep_db_query("update " . TABLE_FEATURE_TOUR_SECTION . " set departure_city_id = '".$departure_city_id."', categories_id=".$categories_id."  where feature_tour_section_id = '" . $HTTP_POST_VARS['option_id'] . "' ");
      
        tep_redirect(tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, $page_info));
        break;
     
      case 'delete_option':
        tep_db_query("delete from " . TABLE_FEATURE_TOUR_SECTION . " where feature_tour_section_id = '" . $HTTP_GET_VARS['option_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, $page_info));
        break;
      
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'option_page=' . ($HTTP_GET_VARS['option_page'] ? $HTTP_GET_VARS['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script>
function validation()
{	

		
	if(document.option.departure_city_id.value == '')
	{
		alert("Please select City Name");
		document.option.departure_city_id.focus();
		return false;
	}
	return true; 
}
function validationsnew()
{	

	
	if(document.options_new.departure_city_id.value == '')
	{
		alert("Please select City Name");
		document.options_new.departure_city_id.focus();
		return false;
	}
	return true; 
}
</script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('feature_tour_section_a');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if ($HTTP_GET_VARS['action'] == 'delete_product_option') { // delete product option
    $options = tep_db_query("select ftse.*,ci.* from ".TABLE_FEATURE_TOUR_SECTION." as ftse, ".TABLE_TOUR_CITY." as ci where ftse.departure_city_id = ci.city_id and ftse.feature_tour_section_id = " . $HTTP_GET_VARS['option_id'] . " and ftse.tour_section = 'A' order by ftse.feature_tour_section_id");
    $options_values = tep_db_fetch_array($options);
?>
              <tr>
                <td colspan="2" class="pageHeading">&nbsp;<?php echo '&nbsp;&nbsp;&nbsp;'.$options_values['city']; ?>&nbsp;&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td width="19%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
 //   $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_FEATURE_TOUR_SECTION_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pa.products_id = p.products_id and pa.options_id='" . $HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
 //select 8 from prodct,region,region_city,city where region_id = region_id and region.region_id = region_city.region_id and region_city.feature_tour_section_id= city.feature_tour_section_id
	//$products = tep_db_query("select * from ". TABLE_PRODUCTS . " p, " . TABLE_FEATURE_TOUR_SECTION_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pa.products_id = p.products_id and pa.options_id='" . $HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
   // if (tep_db_num_rows($products)) {
?>
         <!--         <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
   //   while ($products_values = tep_db_fetch_array($products)) {
   //     $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
    //  }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr> -->
<?php
   // } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'action=delete_option&option_id=' . $HTTP_GET_VARS['option_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
  //  }
?>
                </table></td>
              </tr>
<?php
  } else {
    if ($HTTP_GET_VARS['option_order_by']) {
      $option_order_by = $HTTP_GET_VARS['option_order_by'];
    } else {
      $option_order_by = 'feature_tour_section_id';
    }
	
	if ($HTTP_GET_VARS['durationday']) {
	
      $duration_days_from = $HTTP_POST_VARS['duration_days_from'];
	  $duration_days_to = $HTTP_POST_VARS['duration_days_to'];
		 tep_db_query("update " . TABLE_FEATURE_TOUR_SECTION_DURATION . " set duration_from = '".$duration_days_from."',duration_to = '".$duration_days_to."'  where tour_section = 'A' ");
    }
?>
              <tr>
                <td colspan="2" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
                <td width="20%" class="main"></td>
              </tr>
			  
			  <?php $duration_query = tep_db_query("select * from " . TABLE_FEATURE_TOUR_SECTION_DURATION . " where tour_section = 'A' ");
			  		$duration_result = tep_db_fetch_array($duration_query);
					
			  ?>
              <tr>
                <td colspan="4" valign="top" class="main"><form name="duration_days" action="<?php echo FILENAME_FEATURE_TOUR_SECTION_A."?durationday=true"; ?>" method="post"><br>Duration Days: From 
				<input type="text" name="duration_days_from" value="<?php echo $duration_result['duration_from'];?>">
                  - To
                <input type="text" name="duration_days_to" value="<?php echo $duration_result['duration_to'];?>">  <?php echo tep_image_submit('button_edit.gif', IMAGE_UPDATE); ?></form></td>
              </tr>
              <tr>
                <td colspan="2" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    //$options = "select ftse.*,ci.* from ".TABLE_FEATURE_TOUR_SECTION." as ftse, ".TABLE_TOUR_CITY." as ci where ftse.departure_city_id = ci.city_id and ftse.tour_section = 'A' order by " . $option_order_by;
	$options = "select ftse.*,rd.regions_name, r.regions_id from ".TABLE_FEATURE_TOUR_SECTION." as ftse, " . TABLE_REGIONS . " r, " . TABLE_REGIONS_DESCRIPTION . " rd where ftse.departure_city_id = r.regions_id and ftse.tour_section = 'A' and r.regions_id = rd.regions_id and rd.language_id ='".(int)$languages_id."' order by " . $option_order_by;
	 if (!$option_page) {
      $option_page = 1;
    }
    $prev_option_page = $option_page - 1;
    $next_option_page = $option_page + 1;

    $option_query = tep_db_query($options);

    $option_page_start = ($per_page * $option_page) - $per_page;
    $num_rows = tep_db_num_rows($option_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $options = $options . " LIMIT $option_page_start, $per_page";

    // Previous
    if ($prev_option_page)  {
      echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'option_page=' . $prev_option_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $option_page) {
        echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'option_page=' . $i) . '">' . $i . '</a> | ';
      } else {
        echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($option_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'option_page=' . $next_option_page) . '"> &gt;&gt; </a>';
    }
// WebMakers.com Added: Product Options City Code
?>
                </td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
				<td width="34%" class="dataTableHeadingContent">&nbsp;<?php echo 'Region Name';//TABLE_HEADING_REGION_NAME; ?>&nbsp;</td>
				<td width="34%" class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_CATEGORIES_ID; ?>&nbsp;</td>
				<td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
	
	//"select rd.regions_name, r.regions_id from " . TABLE_REGIONS . " r, " . TABLE_REGIONS_DESCRIPTION . " rd where r.regions_id = rd.regions_id and r.regions_id  = ".$feature_result['departure_city_id']." "

	$city_array = array(array('id' => '', 'text' => '--Select Region--'));
    $city_query = tep_db_query("select rd.regions_name, r.regions_id from " . TABLE_REGIONS . " r, " . TABLE_REGIONS_DESCRIPTION . " rd where r.regions_id = rd.regions_id and rd.language_id ='".(int)$languages_id."' order by regions_id");
    while ($cities = tep_db_fetch_array($city_query)) 
	{
      $city_array[] = array('id' => $cities['regions_id'],
                              'text' => $cities['regions_name']);
    }
	
	/* $city_array = array(array('id' => '', 'text' => TEXT_CITY));
    $city_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " order by city");
    while ($cities = tep_db_fetch_array($city_query)) 
	{
      $city_array[] = array('id' => $cities['city_id'],
                              'text' => $cities['city']);
    } */
	
      $max_options_id_query = tep_db_query("select max(feature_tour_section_id) + 1 as next_id from " . TABLE_FEATURE_TOUR_SECTION);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
	  if($next_id == "")
	  {
	  $next_id = 1;
	  }
	  
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($HTTP_GET_VARS['action'] == 'update_option') && ($HTTP_GET_VARS['option_id'] == $options_values['feature_tour_section_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'action=update_option_name', 'NONSSL') . '" method="post" onsubmit="return validation()">';
        $inputs = '';
			
		  $option_name = tep_db_query("select ftse.*,rd.regions_name, r.regions_id from ".TABLE_FEATURE_TOUR_SECTION." as ftse, " . TABLE_REGIONS . " r, " . TABLE_REGIONS_DESCRIPTION . " rd where ftse.departure_city_id = r.regions_id and ftse.tour_section = 'A' and r.regions_id = rd.regions_id and ftse.feature_tour_section_id = " . $options_values['feature_tour_section_id'] . " order by ftse.feature_tour_section_id");
		 //$option_name = tep_db_query("select ftse.*,ci.* from ".TABLE_FEATURE_TOUR_SECTION." as ftse, ".TABLE_TOUR_CITY." as ci where ftse.departure_city_id = ci.city_id and ftse.feature_tour_section_id = " . $options_values['feature_tour_section_id'] . " and ftse.tour_section = 'A' order by ftse.feature_tour_section_id");
          $option_name = tep_db_fetch_array($option_name);
          $inputs .= '&nbsp;'. tep_draw_pull_down_menu('departure_city_id', $city_array, $option_name['departure_city_id'], '') .'&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;     <br>';
       
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['feature_tour_section_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['feature_tour_section_id']; ?>">&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
				<td class="smallText"><?php echo '&nbsp;' . tep_draw_input_field('categories_id', $options_values['categories_id'], '', '') . '&nbsp;&nbsp;&nbsp;<br>'; ?></td>
                <td width="20%" align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, '', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>' . "\n";
      } else {
// WebMakers.com Added: Product Options City Code
?>
                <td width="6%" align="center" class="smallText">&nbsp;<?php echo $options_values["feature_tour_section_id"]; ?>&nbsp;</td>
				<td width="6%" class="smallText">&nbsp;<?php echo $options_values["regions_name"]; ?>&nbsp;</td>
				<td class="smallText">&nbsp;<?php echo $options_values['categories_id']; ?></td>
                <td width="15%" align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'action=update_option&option_id=' . $options_values['feature_tour_section_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'action=delete_product_option&option_id=' . $options_values['feature_tour_section_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php

    }
?>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if ($HTTP_GET_VARS['action'] != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
// WebMakers.com Added: Product Options City Code

 	 
      echo '<form name="options_new" action="' . tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, 'action=add_product_options&option_page=' . $option_page, 'NONSSL') . '" method="post"  onsubmit="return validationsnew()"><input type="hidden" name="feature_tour_section_id" value="' . $next_id . '">';
      $inputs = '';$inputs .= '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_array, '', '') . '&nbsp;&nbsp;&nbsp;<br>';
      
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
				<td class="smallText"><?php echo '&nbsp;' . tep_draw_input_field('categories_id', $options_values['categories_id'], '', '') . '&nbsp;&nbsp;&nbsp;<br>'; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
<!-- option value eof //-->
      </tr>
<!-- products_attributes //-->
      
      <tr>
 </tr>
    </table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
