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
  	$remark = new Remark('city');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  
  if($language == "schinese") {
	
	header("Content-type: text/html; charset=gb2312");	

}
		  //AMIT added for assign region to country
if( (!isset($HTTP_GET_VARS['type'])  || $HTTP_GET_VARS['type'] == '') && !isset($HTTP_GET_VARS['search'])  && !isset($HTTP_GET_VARS['state_search_id']) && !isset($HTTP_GET_VARS['countries_search_id'])   ){
//&&(!isset($HTTP_GET_VARS['search']) )

$type =$_GET['type'] =$HTTP_GET_VARS['type'] = '0';
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
		
		//AMIT added for assign region to country

  
  if(isset($HTTP_GET_VARS['action_attributes']) && $HTTP_GET_VARS['action_attributes']=='true')
  { //for ajax purpose
  
    echo tep_draw_pull_down_menu('regions_id', tep_get_regions_array_of_country((int)$HTTP_GET_VARS['country_id']), '', 'style="width:135px;"');
	if($HTTP_GET_VARS['srcfilter']=="yes") { echo "<br/>";}
	else { echo '&nbsp;';}
	echo tep_draw_pull_down_menu('state_id', tep_get_zone_array_of_country((int)$HTTP_GET_VARS['country_id']), '', ' id="ajax_draw_state_id_select" style="width:135px;"') . '<br>';

	
  }else{	 //regular files
  
  
  if ($HTTP_GET_VARS['action']) {
    $page_info = 'option_page=' . $HTTP_GET_VARS['option_page'] . '&value_page=' . $HTTP_GET_VARS['value_page'] . '&attribute_page=' . $HTTP_GET_VARS['attribute_page'];
    switch($HTTP_GET_VARS['action']) {
	
	case 'setflagf':
	if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) 
	{
		if ( isset($HTTP_GET_VARS['pID']) ) 
		{
		       //check if city code exist or not
			   		$get_city_code = tep_db_fetch_array(tep_db_query("select city_code from ".TABLE_TOUR_CITY." where city_id='" . $HTTP_GET_VARS['pID'] . "'"));
					if($HTTP_GET_VARS['flag'] == '1' && $get_city_code['city_code']==''){
						$messageStack->add_session('City code is null so can not be enabled as departure city. Enter City Code or Try with another city.', 'error');	
					}
					else
					{
			   //check if city code exist or not
			   
				   //echo "update " . TABLE_TOUR_CITY . " set departure_city_status = '" . $HTTP_GET_VARS['flag'] . "' where city_id = '" . $HTTP_GET_VARS['pID'] . "'";
				   tep_db_query("update " . TABLE_TOUR_CITY . " set departure_city_status = '" . $HTTP_GET_VARS['flag'] . "' where city_id = '" . $HTTP_GET_VARS['pID'] . "'");
				   }
		}
	}
	tep_redirect(tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','oID','products_id','x','y'))));
	 break;		
	 
      case 'add_product_options':
		$option_name = $HTTP_POST_VARS['option_name'];
		$regions_id = $HTTP_POST_VARS['regions_id'];
	    $state_id = $HTTP_POST_VARS['state_id'];
		$type = $HTTP_POST_VARS['type'];
		$option_code = $HTTP_POST_VARS['option_code'];
		$country_id = $HTTP_POST_VARS['countries_id'];
		
		//chack if already exist city name
		$check_exist_query = tep_db_query("select city_id from ".TABLE_TOUR_CITY." where city = '".$option_name."'");
		if(tep_db_num_rows($check_exist_query)>0){
			$messageStack->add_session('City '.$option_name.' already exist. Try with another city.', 'error');
			$error = 'true';	
		}
		
		
		$check_citycode_exist_query = tep_db_query("select c.city_code,r.countries_id from ".TABLE_TOUR_CITY." c, ".TABLE_REGIONS." r where c.regions_id=r.regions_id and c.city_code = '".$option_code."' and r.countries_id='".$country_id."'");
		if(tep_db_num_rows($check_citycode_exist_query)>0){
			$messageStack->add_session('City Code '.$option_code.' already exist for this country. Try with another city code.', 'error');
			$error = 'true';	
		}
		if($error != 'true'){
		//chack if already exist city name
		
	  
		
		//$countries_id = $HTTP_POST_VARS['countries_id'];
        tep_db_query("insert into " . TABLE_TOUR_CITY . " (city_id, regions_id, state_id, city, is_attractions, city_code) values ('', '".$regions_id."', '".$state_id."', '" . $option_name . "', '".$type."','".$option_code."')");
        $city_insert_id = tep_db_insert_id();
	  	//echo $HTTP_POST_VARS['city_img']."hello".DIR_FS_CATALOG_IMAGES."<br>";
		  if ($city_img = new upload('city_img', DIR_FS_CATALOG_IMAGES)) 
		  {
		  	//echo "inside".$city_img->filename;
			tep_db_query("update " . TABLE_TOUR_CITY . " set city_img = '" . tep_db_input($city_img->filename) . "' where city_id = '" . $city_insert_id. "'");
          } 
     
       }
	   tep_redirect(tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','option_id','x','y'))));
        //tep_redirect(tep_href_link(FILENAME_CITY, $page_info));
        break;

      case 'update_option_name':
    
		$city_img = new upload('city_img');
        $city_img->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($city_img->parse() && $city_img->save()) 
		{
          $city_img_name = $city_img->filename;
        }
		 else 
		{
        $city_img_name = $HTTP_POST_VARS['city_previous_image'];
      	}
	  // tep_db_query("update " . TABLE_TOUR_CITY . " set city_img = '" . tep_db_input($city_img_name) . "' where city_id = '" . $HTTP_POST_VARS['option_id']. "'");
      
         	$option_name = $HTTP_POST_VARS['option_name'];
			$country_id = $HTTP_POST_VARS['countries_id'];
			$option_code = $HTTP_POST_VARS['option_code'];
		  	//chack if already exist city name
			$check_exist_query = tep_db_query("select city_id from ".TABLE_TOUR_CITY." where city = '".$option_name."' and city_id != '".$HTTP_POST_VARS['option_id']."'");
			if(tep_db_num_rows($check_exist_query)>0){
				$messageStack->add_session('City '.$option_name.' already exist. Try with another city.', 'error');	
				$error = 'true';
			}
			
			
			$check_citycode_exist_query = tep_db_query("select c.city_code,r.countries_id from ".TABLE_TOUR_CITY." c, ".TABLE_REGIONS." r where c.regions_id=r.regions_id and c.city_code = '".$option_code."' and r.countries_id='".$country_id."' and c.city_id!='".$HTTP_POST_VARS['option_id']."'");
			if(tep_db_num_rows($check_citycode_exist_query)>0){
				$messageStack->add_session('City Code '.$option_code.' already exist for this country. Try with another city code.', 'error');
				$error = 'true';	
			}
			
			if($error != 'true'){
			//chack if already exist city name
			  $option_code = $HTTP_POST_VARS['option_code'];
			  $type = $HTTP_POST_VARS['type'];
			  tep_db_query("update " . TABLE_TOUR_CITY . " set city_img = '" . tep_db_input($city_img_name) . "', city = '" . $option_name . "', regions_id = '".$regions_id."', state_id = '".$state_id."', last_modified=now(), is_attractions='".$type."', city_code = '".$option_code."'  where city_id = '" . $HTTP_POST_VARS['option_id'] . "' ");
      		}
        //tep_redirect(tep_href_link(FILENAME_CITY, $page_info));
		 tep_redirect(tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','option_id','x','y'))));
        break;
     
      case 'delete_option':
	      $manufacturer_query = tep_db_query("select city_img from " . TABLE_TOUR_CITY . " where city_id = '" . $HTTP_GET_VARS['option_id'] . "'");
          $manufacturer = tep_db_fetch_array($manufacturer_query);

          $image_location = DIR_FS_CATALOG_IMAGES . $manufacturer['city_img'];
		  if (file_exists($image_location)) @unlink($image_location);
		  tep_db_query("delete from " . TABLE_TOUR_CITY . " where city_id = '" . $HTTP_GET_VARS['option_id'] . "'");
          tep_redirect(tep_href_link(FILENAME_CITY, $page_info));
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
<script type="text/javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_CITY, 'option_page=' . ($HTTP_GET_VARS['option_page'] ? $HTTP_GET_VARS['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
<script language="javascript" src="includes/menu.js"></script>
<script>
function validation()
{	
	if(document.option.option_name.value == '')
	{
		alert("Please enter City Name");
		document.option.option_name.focus();
		return false;
	}
	
	if(document.option.regions_id.value == '0')
	{
		alert("Please enter Region");
		document.option.regions_id.focus();
		return false;
	}
	if(document.option.state_id.value == '')
	{
		alert("Please enter State Name");
		document.option.state_id.focus();
		return false;
	}
	return true; 
}
function validationsnew()
{	
	if(document.options_new.option_name.value == '')
	{
		alert("Please enter City Name");
		document.options_new.option_name.focus();
		return false;
	}
	
	if(document.options_new.regions_id.value == '0')
	{
		alert("Please enter Region");
		document.options_new.regions_id.focus();
		return false;
	}
	if(document.options_new.state_id.value == '')
	{
		alert("Please enter State Name");
		document.options_new.state_id.focus();
		return false;
	}
	return true; 
}
</script>
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
		
				function change_region_state_list(country_id)
				{
				
					try{
							http1.open('get', 'city.php?country_id='+country_id+'&action_attributes=true');
							http1.onreadystatechange = hendleInfo_change_attributes_list;
							http1.send(null);
					}catch(e){ 
						//alert(e);
					}
				}
				
				function hendleInfo_change_attributes_list()
					{
						
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 //alert(response);
						 document.getElementById("country_region_state_id").innerHTML = response1;
						 
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
				
				function hendleInfo_change_attributes_list_search()
					{
						
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 //alert(response);
						 document.getElementById("country_state_start_city_search_id").innerHTML = response1;
						 
						}
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
$listrs = new Remark('city');
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

$type_array = array();
$type_array[] = array('id' => '0', 'text' => TEXT_CITY_ONLY);
$type_array[] = array('id' => '1', 'text' => TEXT_ATTRACTIONS_ONLY);
$type_array[] = array('id' => '2', 'text' => TEXT_CITY_AND_ATTRACTIONS);
						
						
						
  if ($HTTP_GET_VARS['action'] == 'delete_product_option') { // delete product option
    $options = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id = '" . $HTTP_GET_VARS['option_id'] . "'");
    $options_values = tep_db_fetch_array($options);
?>
              <tr>
                <td class="pageHeading">&nbsp;<?php echo $options_values['city']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>

                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','oID','products_id','x','y')).'action=delete_option', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','oID','products_id','x','y')), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
  //  }
?>
                </table></td>
              </tr>
<?php
  } else {
    /*if ($HTTP_GET_VARS['option_order_by']) {
      $option_order_by = $HTTP_GET_VARS['option_order_by'];
    } else {
      $option_order_by = 'city_id';
    }*/
	
	$state_array = array(array('id' => '', 'text' => TEXT_STATE));
    $state_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " order by zone_name");
    while ($states = tep_db_fetch_array($state_query)) 
	{
      $state_array[] = array('id' => $states['zone_id'],
                              'text' => $states['zone_name']);
    }
?>
              <tr>
                <td colspan="5" class="dataTableContent">&nbsp;<span class="pageHeading"><?php echo HEADING_TITLE_OPT; ?></span>
					<br /><br />
					<?php tep_get_atoz_filter_links(FILENAME_CITY); ?>
				</td>
                
				<td colspan="4">
				<?php echo tep_draw_form('orders', FILENAME_CITY, tep_get_all_get_params(array('search','option_order_by','option_page', 'action','x','y')), 'get'); 
				echo tep_draw_hidden_field('filter_search',$_GET['filter_search']);
				?>
				<table  border="0" cellspacing="2" cellpadding="2">
				  <tr>
					<td class="main">Search by city name/city code:</td>
					<td nowrap class="main">
							<?php
							$alltype_array = array(array('id' => 'all', 'text' => 'All Types'));
							$type_search_array = array_merge($alltype_array, $type_array);
							 ?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="main"><?php echo tep_draw_input_field('search', '', 'size="12"'); ?></td>
								<td><?php //echo tep_image_submit('button_search.gif', IMAGE_SEARCH); ?></td>
							  </tr>
							</table>
							
					</td>
				  </tr>
				  <?php /*?><tr>
					<td class="main">Sort by:</td>
					<td class="main"><form name="option_order_by" action="<?php echo FILENAME_CITY; ?>" >
					<select name="selected" onChange="go_option()">
					<option value="city_id"<?php if ($option_order_by == 'city_id') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_ID; ?></option>
					<option value="city"<?php if ($option_order_by == 'city') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_NAME; ?></option>
					</select>
					</form>
					</td>					
				  </tr><?php */?>
				  <tr>
					<td class="main">Filter by:</td>
					<td>
						<?php 
						//echo tep_draw_form('fildter_frm_by_type', FILENAME_CITY, '', 'get'); 
						echo tep_draw_pull_down_menu('type',$type_search_array,$_GET['type'], ' style="width:160px;"'); //onChange="this.form.submit();"
						//echo '</form>';
						?>
					</td>
				  </tr>
				  
				  <?php
				  $inputs = '';
				  /*$inputs .=  tep_draw_pull_down_menu('regions_id', array(array('id' => '', 'text' => TEXT_REGION)), '', 'style="width:160px;"') . '<br>'.tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '20'). tep_draw_pull_down_menu('state_id',array(array('id' => '', 'text' => TEXT_STATE)), '', 'style="width:160px;"') . '<br>'; */
				  
				  $inputs .= tep_draw_pull_down_menu('regions_id', tep_get_regions_array_of_country( $_GET['countries_search_id']), $_GET['regions_id'], '') .'<br>'. tep_draw_pull_down_menu('state_id', tep_get_zone_array_of_country( $_GET['countries_search_id']), $_GET['state_id'], '') .'<br>';    
				  ?>
				  <tr>
					<td class="main">Filter by:</td>
					<td>
					
					<?php //echo tep_draw_form('fildter_frm_by_country', FILENAME_CITY, '', 'get'); ?>
					
					
					<?php
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
					<td></td>
					<td>					
					<div id="country_state_start_city_search_id"><?php echo $inputs; ?></div>					
					</td>
				  </tr>
				  <tr>
				  	<td></td>
				  	<td><?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH); ?></td>
				  </tr>			 
					
				</table>
				</form>

				</td>
            
			  </tr>
              <tr>
                <td colspan="9" class="smallText">
<?php
   // $per_page = MAX_ROW_LISTS_OPTIONS;
   $per_page = 50;
    //$options = "select * from " . TABLE_TOUR_CITY . " order by " . $option_order_by;
	
	
	if($_GET["sort"] == 'city_id') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'ttc.city_id asc ';
	  } else {
			$sortorder .= 'ttc.city_id desc ';
	  }
	}
	else if($_GET["sort"] == 'city_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'ttc.city asc ';
	  } else {
			$sortorder .= 'ttc.city desc ';
	  }
	}
	else if($_GET["sort"] == 'country_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'c.countries_name asc ';
	  } else {
			$sortorder .= 'c.countries_name desc ';
	  }
	}
	else if($_GET["sort"] == 'region_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'rd.regions_name asc ';
	  } else {
			$sortorder .= 'rd.regions_name desc ';
	  }
	}
	else if($_GET["sort"] == 'state_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'ts.zone_name asc ';
	  } else {
			$sortorder .= 'ts.zone_name desc ';
	  }
	}
	else
	{
		$sortorder .= 'ttc.city_id ';
	}
	
	$extrawhere_search = "";
	
	if(isset($_GET['filter_search']) && $_GET['filter_search']!=''){
		$filter_search_query = " and ttc.city like '".$_GET['filter_search']."%'";
	}
	if($HTTP_GET_VARS['type'] != '' && $HTTP_GET_VARS['type'] != 'all'){
		 $extrawhere_search .= " and (ttc.is_attractions = '".$HTTP_GET_VARS['type']."' or ttc.is_attractions = '2') ";
	}
	if(isset($HTTP_GET_VARS['search']) && $HTTP_GET_VARS['search'] != ''){
		$extrawhere_search .= " and ( ttc.city_id = '".$HTTP_GET_VARS['search']."' ||  ttc.city like '".$HTTP_GET_VARS['search']."%' || ttc.city_code like '".$HTTP_GET_VARS['search']."%' ) ";
	}
	if(isset($HTTP_GET_VARS['countries_search_id']) && $HTTP_GET_VARS['countries_search_id'] != ''){
		$extrawhere_search .= " and r.countries_id = '".$HTTP_GET_VARS['countries_search_id']."' ";
	}
	if(isset($HTTP_GET_VARS['state_id']) && $HTTP_GET_VARS['state_id'] != ''){
		$extrawhere_search .= " and ttc.state_id = '".$HTTP_GET_VARS['state_id']."' ";
	}
	if(isset($HTTP_GET_VARS['regions_id']) && $HTTP_GET_VARS['regions_id'] != ''){
		$extrawhere_search .= " and ttc.regions_id = '".$HTTP_GET_VARS['regions_id']."' ";
	}	
   
   $options = "select ttc.departure_city_status, ttc.city_id, ttc.city_img, ttc.city, ttc.city_code, ttc.is_attractions, ttc.regions_id, ttc.state_id, r.countries_id, rd.regions_name, c.countries_name, ts.zone_name from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS_DESCRIPTION." as rd, ".TABLE_REGIONS." r, ".TABLE_ZONES." as ts, ".TABLE_COUNTRIES." c where ttc.regions_id = rd.regions_id and r.regions_id = rd.regions_id and r.countries_id = c.countries_id and rd.language_id='".$languages_id."'  and ttc.state_id = ts.zone_id ".$extrawhere_search." ".$filter_search_query." order by ". $sortorder;
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
      echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','option_page','x','y')) .'option_page=' . $prev_option_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $option_page) {
        echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','option_page','x','y')) .'option_page=' . $i) . '">' . $i . '</a> | ';
      } else {
        echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($option_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','option_page','x','y')) .'option_page=' . $next_option_page) . '"> &gt;&gt; </a>';
    }
// WebMakers.com Added: Product Options City Code
?>
                </td>
              </tr>
              <tr>
                <td colspan="10"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="2%">
					&nbsp;<?php echo TABLE_HEADING_ID; ?><br>
					<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=city_id&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=city_id&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" width="10%">
					&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?><br>
					<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page','option_page')).'sort=city_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order')).'sort=city_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" width="5%">&nbsp;<?php echo TABLE_HEADING_CITY_CODE; ?>&nbsp;</td>
				<td class="dataTableHeadingContent" width="10%">
				&nbsp;<?php echo TABLE_HEADING_COUNTRY_NAME; ?><br>
				<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=country_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=country_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" width="8%">
				&nbsp;<?php echo TABLE_HEADING_REGION_NAME; ?><br>
				<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=region_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=region_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" width="6%">
				&nbsp;<?php echo TABLE_HEADING_STATE_NAME; ?><br>	
				<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=state_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('sort','order','option_page')).'sort=state_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" width="11%">&nbsp;<?php echo TABLE_HEADING_TYPE; ?>&nbsp;</td>
				<td class="dataTableHeadingContent" width="10%">&nbsp;</td>				
				<td class="dataTableHeadingContent" width="10%" align="right">&nbsp;<?php echo TEXT_HEADING_DEPARTURE_CITY; ?>&nbsp;</td>
				<td class="dataTableHeadingContent" width="15%" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="10"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
	
	
    $next_id = 1;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($HTTP_GET_VARS['action'] == 'update_option') && ($HTTP_GET_VARS['option_id'] == $options_values['city_id'])) {
	 	echo '<td colspan="10">';
        echo '<form name="option" action="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','option_id','x','y')).'action=update_option_name', 'NONSSL') . '" enctype="multipart/form-data" method="post" onsubmit="return validation()"><table width="100%" cellpadding="0" cellspacing="0">';
        $inputs = '';
		//echo "select ttc.city_id, ttc.city, ttc.regions_id, ttc.state_id, r.countries_id, rd.regions_name, ts.zone_name from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS_DESCRIPTION." as rd, ".TABLE_REGIONS." r,  ".TABLE_ZONES." as ts where city_id = '" . $options_values['city_id'] . "' and r.regions_id = rd.regions_id and rd.language_id='".$languages_id."' and rd.regions_id = ttc.regions_id and ts.zone_id = ttc.state_id order by city_id";
		  $option_name_query = tep_db_query("select ttc.city_id, ttc.city, ttc.city_code, ttc.is_attractions, ttc.regions_id, ttc.state_id, r.countries_id, rd.regions_name, ts.zone_name from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS_DESCRIPTION." as rd, ".TABLE_REGIONS." r,  ".TABLE_ZONES." as ts where city_id = '" . $options_values['city_id'] . "' and r.regions_id = rd.regions_id and rd.language_id='".$languages_id."' and rd.regions_id = ttc.regions_id and ts.zone_id = ttc.state_id order by city_id");
          $option_name_data = tep_db_fetch_array($option_name_query);
         // $inputs .= '&nbsp;'.tep_draw_pull_down_menu('regions_id', tep_get_region_tree(), $option_name_data['regions_id'], 'style="width:160px;"') .'&nbsp;'. tep_draw_pull_down_menu('state_id', $state_array, $option_name_data['state_id'], 'style="width:160px;"') .'<br>';
        $inputs .= '&nbsp;'.tep_draw_pull_down_menu('regions_id', tep_get_regions_array_of_country($option_name['countries_id']), $option_name['regions_id'],'style="width:135px;"') .'&nbsp;'. tep_draw_pull_down_menu('state_id', tep_get_zone_array_of_country($option_name['countries_id']), $option_name['state_id'],'style="width:135px;"') .'<br>';
?>
                <td align="left" class="smallText">&nbsp;<?php echo $options_values['city_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['city_id']; ?>">&nbsp;</td>
                 <td class="smallText" width="180" nowrap><?php echo tep_draw_input_field('option_name',$option_name_data['city'],'size="20"');?></td>
				 <td class="smallText" nowrap><?php echo '<input type="text" name="option_code" size="5" value="' . $option_name_data['city_code'] . '">'; ?></td>
			    <td class="smallText"><?php echo tep_draw_pull_down_menu('countries_id', $countries_array, $option_name_data['countries_id'], 'id="countries_id" onChange="change_region_state_list(this.value);" style="width:160px;"') ;?></td>
			    <td class="smallText" colspan="2" nowrap><div id="country_region_state_id"><?php echo $inputs; ?></div></td>
				<td class="smallText" nowrap="nowrap"><?php echo tep_draw_pull_down_menu('type',$type_array,$option_name_data['is_attractions'], ''); ?></td>
				<td class="smalltext" colspan="2"><?php echo '&nbsp;' . tep_draw_file_field('city_img') ; ?><?php echo '<br>' . $options_values['city_img'] . tep_draw_hidden_field('city_previous_image', $options_values['city_img']);?></td>
				<td align="left" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','oID','products_id','x','y')), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</table></form></td>' . "\n";
      } else {
// WebMakers.com Added: Product Options City Code
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values["city_id"]; ?>&nbsp;</td>
				<td class="smallText">&nbsp;<?php echo $options_values["city"]; ?>&nbsp;</td>
				<td class="smallText">&nbsp;<?php echo $options_values["city_code"]; ?>&nbsp;</td>
				<td class="smallText">&nbsp;<?php echo tep_get_country_name($options_values["countries_id"]); ?>&nbsp;</td>
				<td class="smallText">&nbsp;<?php echo $options_values["regions_name"]; ?>&nbsp;</td>
				<td class="smallText">&nbsp;<?php echo $options_values["zone_name"]; ?>&nbsp;</td>
				<td class="smallText">&nbsp;
					<?php 
						switch($options_values["is_attractions"])
						{
							case '0':
								echo TEXT_CITY_ONLY; 	
							break;
							case '1':
								echo TEXT_ATTRACTIONS_ONLY; 	
							break;
							case '2':
								echo TEXT_CITY_AND_ATTRACTIONS; 	
							break;					
						}
						
					?>&nbsp;
				</td>
				<td class="smalltext"><?php echo '&nbsp;' . $options_values['city_img'];?></td>
				<td align="right"><?php

					  if ($options_values['departure_city_status'] == '1') {
				
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','x','y')).'action=setflagf&flag=0&pID=' . $options_values["city_id"]) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
				
					  } else {
				
						echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','x','y')).'action=setflagf&flag=1&pID=' .$options_values["city_id"]) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
				
					  }
				
				?></td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CITY,  tep_get_all_get_params(array('action','pID','flag','option_id','x','y','option_order_by','option_page')).'action=update_option&option_id=' . $options_values['city_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','option_id','option_order_by','option_page','x','y')).'action=delete_product_option&option_id=' . $options_values['city_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(city_id) + 1 as next_id from " . TABLE_TOUR_CITY);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
              <tr>
                <td colspan="10"><?php echo tep_black_line(); ?></td>
              </tr>
			
			<?php
    if ($HTTP_GET_VARS['action'] != 'update_option') {
?>
	
				   <tr>
                <td colspan="10">
				<?php
				      echo '<form name="options_new" action="' . tep_href_link(FILENAME_CITY, tep_get_all_get_params(array('action','pID','flag','option_id','x','y','option_order_by','option_page')).'action=add_product_options&option_page=' . $option_page, 'NONSSL') . '" enctype="multipart/form-data" method="post"  onsubmit="return validationsnew()"><input type="hidden" name="city_id" value="' . $next_id . '">';

				?>
				<table width="100%" cellpadding="0" cellspacing="0">
 
              <tr>
			  	<td align="center" class="smallText"></td>
                <td class="smallText" nowrap>City:</td>
			    <td class="smallText" nowrap="nowrap">City Code:</td>
				<td class="smallText"></td>
			    <td class="smallText" colspan="2" nowrap></td>
				<td class="smalltext" nowrap="nowrap">Type:</td>
				<td class="smalltext" colspan="2">Image:</td>
			  </tr>
			  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
// WebMakers.com Added: Product Options City Code

  
 	 
      $inputs = '';
	 // $inputs .=  tep_draw_pull_down_menu('regions_id', tep_get_region_tree(), '', 'style="width:160px;"') . '&nbsp;' . tep_draw_pull_down_menu('state_id', $state_array, '', 'style="width:160px;"') . '<br>';
        $inputs .=  tep_draw_pull_down_menu('regions_id', array(array('id' => '', 'text' => TEXT_REGION)), '', 'style="width:160px;"') . '&nbsp;' . tep_draw_pull_down_menu('state_id',array(array('id' => '', 'text' => TEXT_STATE)), '', 'style="width:160px;"') . '<br>';
     
?>
                <td align="center" class="smallText"><?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText" nowrap><?= tep_draw_input_field('option_name','','size="15"');?>&nbsp;</td>
			    <td class="smallText" nowrap="nowrap"><input type="text" name="option_code" size="8">&nbsp;</td>
				<td class="smallText"><?php echo tep_draw_pull_down_menu('countries_id', $countries_array, '', 'id="countries_id" onChange="change_region_state_list(this.value);" style="width:160px;"') ;?></td>
			    <td class="smallText" colspan="2" nowrap>
				<div id="country_region_state_id"><?php echo $inputs; ?></div></td>
				<td>
					<?php
						echo tep_draw_pull_down_menu('type',$type_array,0, 'style="width:160px;"');
					?>
				</td>
				<td class="smalltext" colspan="2">
					<table >
					 <tr>
					 	<td><?php echo tep_draw_file_field('city_img') ; ?></td>
						<td><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?></td>
					 </tr>
					</table>
				</td>
              </tr>
			  </table>
<?php
      echo '</form>';
?>
			  </td>
              </tr>
			  
			  <?php
			  if(isset($_GET['import']) && ($_GET['import']==1)){
			  ?>
			  <tr>
			  	<td colspan="7">
					<FORM ENCTYPE="multipart/form-data" ACTION="exportcsv.php?split=0" METHOD=POST>
					  <p><p>
					  <div align = "left">
						<p><b>Import City:</b></p>
						
						  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
							<INPUT TYPE="hidden" name="importdata"	value="uploadtourcity">
						  <input name="usrfl" type="file" size="35"><input type="submit" name="buttoninsert" value="Import"><br>
						</p> 
					  </div>
		
					</form>
				</td>
			  </tr>
			  <?php
			  }
			  ?>
              <tr>
                <td colspan="10"><?php echo tep_black_line(); ?></td>
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
<?php
 //   $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_TOUR_CITY_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pa.products_id = p.products_id and pa.options_id='" . $HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
 //select 8 from prodct,region,region_city,city where region_id = region_id and region.region_id = region_city.region_id and region_city.city_id= city.city_id
	//$products = tep_db_query("select * from ". TABLE_PRODUCTS . " p, " . TABLE_TOUR_CITY_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pa.products_id = p.products_id and pa.options_id='" . $HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
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
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_CITY, '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr> -->
<?php
   // } else {
?>
<?php } ?>