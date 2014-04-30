<?php
/*
  $Id: countries.php,v 1.1.1.1 2004/03/04 23:38:18 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('zones_city');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  
  function sbs_get_countries($countries_id = '', $with_iso_codes = false) {
			$countries_array = array();
			if ($countries_id) {
			  if ($with_iso_codes) {
				$countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name");
				$countries_values = tep_db_fetch_array($countries);
				$countries_array = array('countries_name' => $countries_values['countries_name'],
										 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
										 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
			  } else {
				$countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'");
				$countries_values = tep_db_fetch_array($countries);
				$countries_array = array('countries_name' => $countries_values['countries_name']);
			  }
			} else {
			  $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
			  while ($countries_values = tep_db_fetch_array($countries)) {
				$countries_array[] = array('countries_id' => $countries_values['countries_id'],
										   'countries_name' => $countries_values['countries_name']);
			  }
			}
		
			return $countries_array;
		  }
		  
		  
		  
		   $countries_array = array(array('id' => '', 'text' => PLEASE_SELECT_COUNTRY));
		   $countries = sbs_get_countries();
		   $size = sizeof($countries);
		   for ($i=0; $i<$size; $i++) {
			 $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
		   }
		   
		   
		   function tep_get_regions_array_of_country($country_id) {
		   global $languages_id;
		   $region_tree_array = array(array('id' => '', 'text' => TEXT_REGION));
		   $regions_query = tep_db_query("select c.regions_id, cd.regions_name, c.parent_id from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd where c.regions_id = cd.regions_id and cd.language_id = '" . (int)$languages_id . "' and c.countries_id = '" . (int)$country_id . "' order by c.sort_order, cd.regions_name");
			while ($regions = tep_db_fetch_array($regions_query)) {
			   $region_tree_array[] = array('id' => $regions['regions_id'], 'text' => $regions['regions_name']);
			}	
			
			return $region_tree_array;
		   }
		   
		   function tep_get_zone_array_of_country($country_id){		   
		    $zones_array = array();
			$zones_array[] = array('id' => '', 'text' => TEXT_STATE);
			$zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
			while ($zones_values = tep_db_fetch_array($zones_query)) {
			  $zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
			}	
			
			return $zones_array;
		   }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $city_name = tep_db_prepare_input($HTTP_POST_VARS['city_name']);
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['state_id']);
        $city_tel_code = tep_db_prepare_input($HTTP_POST_VARS['city_tel_code']);
        $city_name_py = tep_db_prepare_input($HTTP_POST_VARS['city_name_py']);       
        tep_db_query("insert into " . TABLE_ZONES_CITY . " (city_name, zone_id, city_tel_code, city_name_py) values ('" . tep_db_input($city_name) . "', '" . tep_db_input($zone_id) . "', '" . tep_db_input($city_tel_code) . "','".tep_db_input($city_name_py)."')");
		tep_redirect(tep_href_link(FILENAME_ZONES_CITY,tep_get_all_get_params(array('action')) ));
        break;
      case 'save':
      	$city_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
         $city_name = tep_db_prepare_input($HTTP_POST_VARS['city_name']);
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['state_id']);
        $city_tel_code = tep_db_prepare_input($HTTP_POST_VARS['city_tel_code']);
        $city_name_py = tep_db_prepare_input($HTTP_POST_VARS['city_name_py']);       
        tep_db_query("update " . TABLE_ZONES_CITY . " set city_name = '" . tep_db_input($city_name) . "', zone_id = '" . tep_db_input($zone_id) . "', city_tel_code = '" . tep_db_input($city_tel_code) . "',  city_name_py='".tep_db_input($city_name_py)."' where city_id = '" . (int)$city_id . "'");
		tep_redirect(tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $city_id));
        break;
      case 'deleteconfirm':
        $city_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        tep_db_query("delete from " . TABLE_ZONES_CITY . " where city_id = '" . (int)$city_id . "'");
        tep_redirect(tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page')).'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" type="text/javascript"> 
function createRequestObject(){
	var request_;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_ = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_ = new XMLHttpRequest();
	}
	return request_;
}
//var http = createRequestObject();
var http1 = createRequestObject();
var sel_state_id = null;
function change_region_state_list(country_id,mstate_id)
{
	try{
			if(typeof(mstate_id)!='undefined') sel_state_id = mstate_id;
			else sel_state_id = null;
			
			http1.open('get', 'city.php?country_id='+country_id+'&action_attributes=true');
			http1.onreadystatechange = hendleInfo_change_attributes_list;
			http1.send(null);
	}catch(e){ 
		//alert(e);
	}
}
function hendleInfo_change_attributes_list()
	{	
		if(http1.readyState == 4){
		 var response1 = http1.responseText;
		 document.getElementById("country_region_state_id").innerHTML = response1;
		 if(sel_state_id != null){
			 var selobj = document.getElementById('ajax_draw_state_id_select');
			 for(var i=0 ; i<selobj.options.length;i++){
				 if(selobj.options[i].value == sel_state_id){
					 selobj.selectedIndex = i ;break;
					 //alert(selobj.options[i].value+" = "+ sel_state_id);
				 }
			 }
		 }
		}
	}

function change_region_state_list_search(country_id)
{
	try{
			http1.open('get', 'city.php?country_id='+country_id+'&action_attributes=true&srcfilter=yes');
			http1.onreadystatechange = hendleInfo_change_attributes_list_search;
			http1.send(null);
	}catch(e){ 
		//alert(e);
	}
}

function hendleInfo_change_attributes_list_search(){		
		if(http1.readyState == 4)
		{
		 var response1 = http1.responseText;
		 document.getElementById("country_state_start_city_search_id").innerHTML = response1;
		 
		}
}
</script>	
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>			
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!--
 onLoad="SetFocus();"
 header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('zones_city');
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table>
        </td>
      </tr>
	  <tr>
	  	<td class="dataTableContent"><?php tep_get_atoz_filter_links(FILENAME_ZONES_CITY); ?></td>
	  	<!-- filter -->
	  	<td >
	  	<?php 
	  	echo tep_draw_form('orders', FILENAME_ZONES_CITY, tep_get_all_get_params(array('search','option_order_by','option_page', 'action','x','y')), 'get'); 
	  	echo tep_draw_hidden_field('filter_search',$_GET['filter_search']);
	  	
	  	$state_array = array(array('id' => '', 'text' => TEXT_STATE));
    $state_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " order by zone_name");
    while ($states = tep_db_fetch_array($state_query)) 
	{
      $state_array[] = array('id' => $states['zone_id'],
                              'text' => $states['zone_name']);
    }
    
		?>
		<table  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="main">Search by city name:</td>
			<td nowrap class="main"><?php echo tep_draw_input_field('search', '', 'size="12"'); ?></td> 
		</tr>
		<tr>
			<td class="main">Search by Tel Code:</td>
			<td nowrap class="main"><?php echo tep_draw_input_field('tel_code', '', 'size="12"'); ?></td> 
		</tr>
		<tr>
			<td class="main">Filter by Country:</td>
			<td><?php
						 $inputs = '';
						 $inputs .= tep_draw_pull_down_menu('regions_id', tep_get_regions_array_of_country( $_GET['countries_search_id']), $_GET['regions_id'], '') .'<br>'. tep_draw_pull_down_menu('state_id', tep_get_zone_array_of_country( $_GET['countries_search_id']), $_GET['state_id'], '') .'<br>';    
						if(isset($HTTP_GET_VARS['state_search_id']) && $HTTP_GET_VARS['state_search_id']!= '') {
							$select_country_from_state_query = tep_db_query("select zone_id, zone_name, zone_country_id from " . TABLE_ZONES . " where zone_id = '".(int)$HTTP_GET_VARS['state_search_id']."' order by zone_name");
							while ($select_country_from_state = tep_db_fetch_array($select_country_from_state_query)) {
							  $selected_drop_down_country_id = $select_country_from_state['zone_country_id'];						  
							}	
						}else if(isset($HTTP_GET_VARS['countries_search_id']) && $HTTP_GET_VARS['countries_search_id']!= ''){
								$selected_drop_down_country_id = $HTTP_GET_VARS['countries_search_id'];		
						}else{
								$selected_drop_down_country_id = '';		
						}
						echo tep_draw_pull_down_menu('countries_search_id', $countries_array, $selected_drop_down_country_id, 'id="countries_id" onChange="change_region_state_list_search(this.value);" style="width:160px;"') ; //, 'onChange="this.form.submit();"' ?>
			</td>
		</tr>
		<tr>
			<td>Filter By State</td>
			<td>	<div id="country_state_start_city_search_id"><?php echo $inputs; ?></div></td>
		</tr>
		<tr>
			<td></td>
			<td><?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH); ?></td>
		</tr>
		</table>
		</form>
	</td>
	  	<!-- filter -->
	  </tr>
	  <tr><td height="5" colspan="2"></td></tr>
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="400"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center" width="150"><?php echo TABLE_HEADING_ZONE_NAME; ?></td>
				<td class="dataTableHeadingContent" align="center" width="150"><?php echo TABLE_HEADING_CITY_NAME; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('sort','order','page','cID')).'sort=city_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('sort','order','page','cID')).'sort=iso_code3&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" align="left" width="200"><?php echo TABLE_HEADING_CITY_TEL_CODE; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('sort','order','page','cID')).'sort=tel_code&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('sort','order','page','cID')).'sort=tel_code&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

	if($_GET["sort"] == 'city_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'city_name asc ';
	  } else {
			$sortorder .= 'city_name desc ';
	  }
	}else if($_GET["sort"] == 'tel_code') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'city_tel_code asc ';
	  } else {
			$sortorder .= 'city_tel_code desc ';
	  }
	}
	else
	{
		$sortorder .= 'countries_name ASC , zone_name_py ASC,city_name ASC';
	}
	
	$filter_search_query = '';
	if(isset($_GET['filter_search']) && $_GET['filter_search']!=''){
		$filter_search_query = " AND ( zc.city_name like '".$_GET['filter_search']."%' OR zc.city_name_py like '".$_GET['filter_search']."%')";
	}
	
	if(isset($_GET['search']) && $_GET['search']!=''){
		$filter_search_query  .= " AND ( zc.city_name like '".$_GET['search']."%' OR zc.city_name_py like '".$_GET['search']."%')";
	}
	
	if(is_numeric($_GET['countries_search_id'])){
			$filter_search_query .= " AND  c.countries_id  = ".intval($_GET['countries_search_id']);
	}
	if(is_numeric($_GET['state_id'])){
			$filter_search_query .=  " AND z.zone_id  = ".intval($_GET['state_id']);
	}
	if(isset($_GET['tel_code']) && $_GET['tel_code'] != ''){
		$tel_code = tep_db_input(tep_db_prepare_input($_GET['tel_code']));
		$filter_search_query = ' AND (zc.city_tel_code =\''.$tel_code.'\' OR  c.countries_tel_code = \''.$tel_code.'\')';
	}
	
  $countries_query_raw = "select * from " .TABLE_ZONES_CITY." zc  LEFT JOIN  ".TABLE_ZONES." z ON zc.zone_id = z.zone_id LEFT JOIN ". TABLE_COUNTRIES . " c ON z.zone_country_id = c.countries_id where zc.city_id>0  ".$filter_search_query." order by ".$sortorder."";
  $countries_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $countries_query_raw, $countries_query_numrows);
  $countries_query = tep_db_query($countries_query_raw);
  while ($row = tep_db_fetch_array($countries_query)) {
    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $row['city_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($row);
    }

    if (isset($cInfo) && is_object($cInfo) && ($row['city_id'] == $cInfo->city_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $row['city_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $row['countries_name']; ?>(<?php echo $row['countries_tel_code']; ?>)</td>
                <td class="dataTableContent" align="center" ><?php echo $row['zone_name']; ?></td>
                <td class="dataTableContent" align="center" ><?php echo $row['city_name']; ?>(<?php echo $row['city_name_py']; ?>)</td>
                <td class="dataTableContent" align="left" ><?php echo $row['city_tel_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($row['city_id'] == $cInfo->city_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ZONES_CITY,tep_get_all_get_params(array('action','page','cID')). 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $row['city_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','x','y'))); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ZONES_CITY,tep_get_all_get_params(array('action','page')). 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '"><input type="button" value="New City"/></a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CITY . '</b>');
      $contents = array('form' => tep_draw_form('countries', FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page')).'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' .tep_draw_pull_down_menu('countries_id', $countries_array, '', 'id="countries_id" onChange="change_region_state_list(this.value);" style="width:160px;"'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_STATE_NAME . '<br><div id="country_region_state_id">'.$inputs.'</div>');
      $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_NAME . '<br>' . tep_draw_input_field('city_name'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_NAME_PY . '<br>' . tep_draw_input_field('city_name_py'));      
      $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_TEL_CODE . '<br>' . tep_draw_input_field('city_tel_code'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES_CITY,tep_get_all_get_params(array('action','page')). 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':    
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CITY . '</b>');
      $contents = array('form' => tep_draw_form('countries', FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);      
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' .tep_draw_pull_down_menu('countries_id', $countries_array, $cInfo->countries_id, 'id="countries_id" onChange="change_region_state_list(this.value);" style="width:160px;"'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_STATE_NAME . '<br><div id="country_region_state_id">'.$inputs.'</div>');
      $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_NAME . '<br>' . tep_draw_input_field('city_name',$cInfo->city_name));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_NAME_PY . '<br>' . tep_draw_input_field('city_name_py',$cInfo->city_name_py));      
      $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_TEL_CODE . '<br>' . tep_draw_input_field('city_tel_code',$cInfo->city_tel_code));
      
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      $contents[] = array('text'=>'<script language="javascript">change_region_state_list("'.$cInfo->countries_id.'","'.$cInfo->zone_id.'")</script>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CITY . '</b>');
      $contents = array('form' => tep_draw_form('countries', FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->countries_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->countries_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ZONES_CITY, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->city_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_STATE_NAME . ' ' . $cInfo->zone_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_NAME . ' ' . $cInfo->city_name);
         $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_NAME_PY . ' ' . $cInfo->city_name_py);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CITY_TEL_CODE . ' ' . $cInfo->city_tel_code);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
