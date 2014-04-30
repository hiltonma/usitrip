<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");   
/*
include_once "includes/configure.php";
include_once "includes/database_tables.php";
include_once "includes/functions/database.php";
include_once "includes/functions/html_output.php";
include_once "includes/functions/general.php";
*/
include_once "includes/application_top.php";

if($language == "tchinese") {
	
	header("Content-type: text/html; charset=big5");
	include_once "includes/languages/tchinese/categories.php";

}else if($language == "schinese"){
	header("Content-type: text/html; charset=gb2312");
	include_once "includes/languages/schinese/categories.php";
}else{

	include_once "includes/languages/schinese/categories.php";

}
tep_db_connect();

if(isset($HTTP_GET_VARS['action_attributes']) && $HTTP_GET_VARS['action_attributes']=='star_city_filter')
{
$where_extra_add_state_id =='';
if(isset($HTTP_GET_VARS['state_search_id']) && $HTTP_GET_VARS['state_search_id'] != '' && $HTTP_GET_VARS['state_search_id'] != '0'){
	$zones_get_country_query = tep_db_query("select  zone_country_id from " . TABLE_ZONES . " where zone_id = '" . (int)$state_search_id . "' order by zone_name");
	while ($zones_get_country_row = tep_db_fetch_array($zones_get_country_query)) {
		$HTTP_GET_VARS['country_id'] = $zones_get_country_row['zone_country_id'];
		$where_extra_add_state_id = " s.zone_id= '".$HTTP_GET_VARS['state_search_id']."' and ";
	}
}


	function tep_get_zone_array_of_country($country_id){		   
		$zones_array = array();
		$zones_array[] = array('id' => '', 'text' => TEXT_STATE);
		if($country_id != '0' || $country_id != ''){
			$zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
		}else{
			$zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " order by zone_name");
		}
		while ($zones_values = tep_db_fetch_array($zones_query)) {
		  $zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
		}	
		
		return $zones_array;
	}

	
	/*$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
	if(isset($HTTP_GET_VARS['country_id']) && $HTTP_GET_VARS['country_id'] != '' && $HTTP_GET_VARS['country_id'] != '0'){
	 	$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where ".$where_extra_add_state_id." c.state_id = s.zone_id and s.zone_country_id ='".(int)$HTTP_GET_VARS['country_id']."' and s.zone_country_id = co.countries_id and c.city !='' and (c.is_attractions='0' or c.is_attractions='2') order by c.city");
  	}else{
    	$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where ".$where_extra_add_state_id." c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and (c.is_attractions='0' or c.is_attractions='2') order by c.city");
    }
	while ($city_class = tep_db_fetch_array($city_class_query)) {
      $city_class_array[] = array('id' => $city_class['city_id'],
                                 'text' => $city_class['city'].', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
    }*/
	
$get_departure_city_id = tep_db_query("select departure_city_id,departure_end_city_id from ".TABLE_PRODUCTS." where products_id='".$HTTP_GET_VARS['pId']."'");
	$row_get_departure_city_id = tep_db_fetch_array($get_departure_city_id);
	$pInfo->departure_city_id = $row_get_departure_city_id['departure_city_id'];
	if($pInfo->departure_city_id == ''){
		$pInfo->departure_city_id = 0;
	}
	
	
	if(isset($HTTP_GET_VARS['country_id']) && $HTTP_GET_VARS['country_id'] != '' && $HTTP_GET_VARS['country_id'] != '0'){
		$city_start_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where ".$where_extra_add_state_id." c.state_id = s.zone_id and s.zone_country_id ='".(int)$HTTP_GET_VARS['country_id']."' and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_city_id.") and c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
	}else{
		$city_start_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where ".$where_extra_add_state_id." c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_city_id.") and c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
	}
	
	while ($city_start_class_left = tep_db_fetch_array($city_start_class_query_left)) {
	  $city_start_class_array_left[] = array('id' => $city_start_class_left['city_id'],
								 'text' => $city_start_class_left['city'].', '.$city_start_class_left['zone_code'].', '. $city_start_class_left['countries_iso_code_3']);
	}
	
	$city_start_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
	while ($city_start_class_right = tep_db_fetch_array($city_start_class_query_right)) {
	  $city_start_class_array_right[] = array('id' => $city_start_class_right ['city_id'],
								 'text' => $city_start_class_right ['city'].', '.$city_start_class_right['zone_code'].', '. $city_start_class_right['countries_iso_code_3']);
	}
	
?>


<?php
			$pInfo->departure_end_city_id = $row_get_departure_city_id['departure_end_city_id'];
			if($pInfo->departure_end_city_id == ''){
			$pInfo->departure_end_city_id = 0;
			}
				
				if(isset($HTTP_GET_VARS['country_id']) && $HTTP_GET_VARS['country_id'] != '' && $HTTP_GET_VARS['country_id'] != '0'){
					$city_end_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where ".$where_extra_add_state_id." c.state_id = s.zone_id and s.zone_country_id ='".(int)$HTTP_GET_VARS['country_id']."' and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_end_city_id.") AND c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				}else{
					$city_end_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where ".$where_extra_add_state_id." c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_end_city_id.") AND c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				}
				
				while ($city_class_left = tep_db_fetch_array($city_end_class_query_left)) {
				  $city_end_class_array_left[] = array('id' => $city_class_left['city_id'],
											 'text' => $city_class_left['city'].', '.$city_class_left['zone_code'].', '. $city_class_left['countries_iso_code_3']);
				}
				
				$city_end_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_end_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_class_right = tep_db_fetch_array($city_end_class_query_right)) {
				  $city_end_class_array_right[] = array('id' => $city_class_right ['city_id'],
											 'text' => $city_class_right ['city'].', '.$city_class_right['zone_code'].', '. $city_class_right['countries_iso_code_3']);
				}
			?>
			
			
			<?php
			
			$pInfo->products_id = $HTTP_GET_VARS['pId'];
			$pInfo->regions_id = $HTTP_GET_VARS['regions_id'];
			
			
			
			if($pInfo->products_id>0){
			$product_id_query = "pde.products_id = '".$pInfo->products_id."' and";
			}
			else{
			$product_id_query = "pde.products_id = 'XT456' and";
			}

			if($pInfo->regions_id>0){
			$regions_id_query = "and regi.regions_id = '".$pInfo->regions_id."'";
			}
			else{
			$regions_id_query = "";
			}			if($pInfo->products_id != "")
			{
				$sel2values = "";
				$countingcity = 0;
						$city_edit_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co, ".TABLE_PRODUCTS_DESTINATION." as pde  where ".$product_id_query." pde.city_id = c.city_id and s.zone_country_id = co.countries_id and c.state_id = s.zone_id and c.city !='' and (c.is_attractions='1' or c.is_attractions='2') order by c.city");
						while($city_edit_result = tep_db_fetch_array($city_edit_query)){
							$sel2values .= "<option value=".$city_edit_result['city_id'].">".$city_edit_result['city'].', '.$city_edit_result['zone_code'].', '.$city_edit_result['countries_iso_code_3']."</option>";
													
								if($countingcity == 0){
									$allready_product_city = $city_edit_result['city_id'];
								}else{
									$allready_product_city .= ",".$city_edit_result['city_id'];
								}
								$countingcity++;														
						}
																
				$tempsolution="";
				if($allready_product_city == '') $allready_product_city=0;
				
				if(isset($HTTP_GET_VARS['country_id']) && $HTTP_GET_VARS['country_id'] != '' && $HTTP_GET_VARS['country_id'] != '0'){
					
					//echo "select ttc.city_id, ttc.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where ".$where_extra_add_state_id." s.zone_country_id = co.countries_id and ttc.state_id = s.zone_id and s.zone_country_id ='".(int)$HTTP_GET_VARS['country_id']."' and  ttc.regions_id = regi.regions_id and regi.regions_id = ".$pInfo->regions_id." and ttc.city_id not in (".$allready_product_city.") and ttc.city!='' and (ttc.is_attractions='1' or ttc.is_attractions='2') order by ttc.city";
					$city_new_query = tep_db_query("select ttc.city_id, ttc.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where ".$where_extra_add_state_id." s.zone_country_id = co.countries_id and ttc.state_id = s.zone_id and s.zone_country_id ='".(int)$HTTP_GET_VARS['country_id']."' and  ttc.regions_id = regi.regions_id and regi.regions_id = ".$pInfo->regions_id." and ttc.city_id not in (".$allready_product_city.") and ttc.departure_city_status = '1' and ttc.city!='' and (ttc.is_attractions='1' or ttc.is_attractions='2') order by ttc.city");
				}else{					
					$city_new_query = tep_db_query("select ttc.city_id, ttc.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where ".$where_extra_add_state_id." s.zone_country_id = co.countries_id and ttc.state_id = s.zone_id and  ttc.regions_id = regi.regions_id ".$regions_id_query." and ttc.city_id not in (".$allready_product_city.") and ttc.departure_city_status = '1' and ttc.city!='' and (ttc.is_attractions='1' or ttc.is_attractions='2') order by ttc.city");
				}
				
				 while ($city_new_result = tep_db_fetch_array($city_new_query)) 
				{
				 $tempsolution .=  "<option value=".$city_new_result['city_id'].">".$city_new_result['city'].', '.$city_new_result['zone_code'].', '.$city_new_result['countries_iso_code_3']."</option>";
				} 

			}
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
            <td class="main" nowrap width="13%"><?php echo TEXT_HEADING_CITIES_BY_COUNTRY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '21', '15'); ?>
			<?php
			if(isset($HTTP_GET_VARS['edit']) && $HTTP_GET_VARS['edit']=='true'){
			echo tep_draw_pull_down_menu('countries_search_id', tep_get_countries('select country'), $HTTP_GET_VARS['country_id'], 'onChange="change_region_state_list_edit(this.value,'.$HTTP_GET_VARS['pId'].',document.new_product.regions_id.value);""');
			}else{
			echo tep_draw_pull_down_menu('countries_search_id', tep_get_countries('select country'), $HTTP_GET_VARS['country_id'], 'onChange="change_region_state_list(this.value,'.$HTTP_GET_VARS['pId'].',document.new_product.regions_id.value);""');
			}
			?>		
			</td>
		   </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          	</tr>
		   <tr>
            <td class="main" nowrap width="13%"><?php echo TEXT_HEADING_CITIES_BY_STATE; ?></td>
            <td class="main">
			<?php
			echo tep_draw_separator('pixel_trans.gif', '21', '15') . '&nbsp;';
			if(isset($HTTP_GET_VARS['edit']) && $HTTP_GET_VARS['edit']=='true'){
			echo tep_draw_pull_down_menu('state_search_id', tep_get_zone_array_of_country($HTTP_GET_VARS['country_id']), $HTTP_GET_VARS['state_search_id'], 'onChange="change_region_county_state_list_edit(this.value,'.$HTTP_GET_VARS['pId'].',document.new_product.regions_id.value);""');
			}else{
			echo tep_draw_pull_down_menu('state_search_id', tep_get_zone_array_of_country($HTTP_GET_VARS['country_id']), $HTTP_GET_VARS['state_search_id'], 'onChange="change_region_county_state_list(this.value,'.$HTTP_GET_VARS['pId'].',document.new_product.regions_id.value);""');
			}
			
			?>		
			</td>
		   </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          	</tr>
			<?php /*?><tr>
            <td class="main" ><?php echo TEXT_PRODUCTS_DURATION_SELECT_CITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '21', '15') . '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_class_array,$HTTP_GET_VARS['departure_city_id']); ?></td>
          	</tr><?php */?>
			
			<tr>
			<td class="main" nowrap ><?php echo TEXT_PRODUCTS_DURATION_SELECT_CITY; ?></td>
			<td class="main">
				
					<?php 
					if(isset($HTTP_GET_VARS['edit']) && $HTTP_GET_VARS['edit']=='true'){
						?>
						<table border="0">
				  <tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_city_id_temp', $city_start_class_array_left, '',' id="departure_city_id_temp" multiple="multiple" size="10"'); ?></td>
					<td><INPUT onClick="moveOptions(this.form.departure_city_id_temp, this.form.departure_city_id_temp1);" type=button value="-->">
					<BR><INPUT onClick="moveOptions(this.form.departure_city_id_temp1, this.form.departure_city_id_temp);" type=button value="<--"></td>
					<td>
					<?php echo  tep_draw_pull_down_menu('departure_city_id_temp1', $city_start_class_array_right, '',' id="departure_city_id_temp1" multiple="multiple" size="10"'); ?>
					<input type="hidden" name="departure_city_id" value="">
					</td>
				  </tr>
				</table>
				<?php
				}else{
					?>
					<table border="0">
					  <tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_city_id_temp', $city_start_class_array_left, '',' multiple="multiple" size="10"'); ?></td>
						<td><INPUT onClick="moveOptions(this.form.departure_city_id_temp, this.form.elements['departure_city_id[]']);" type=button value="-->"><BR><INPUT onClick="moveOptions(this.form.elements['departure_city_id[]'], this.form.departure_city_id_temp);" type=button value="<--"></td>
						<td><?php echo  tep_draw_pull_down_menu('departure_city_id[]', $city_start_class_array_right, '',' id="departure_city_id[]" multiple="multiple" size="10"'); ?></td>
					  </tr>
					</table>
				<?php
				}
				?>
					
				<?php //echo tep_draw_separator('pixel_trans.gif', '21', '15') . '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_class_array, $pInfo->departure_city_id); ?>
			</td>
			</tr>
			
			<tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
					  
		   <tr>
			<td class="main"><?php echo TEXT_PRODUCTS_DEPARTURE_END_CITY; ?></td>
			<td class="main">			
			
			
				<?php 
				if(isset($HTTP_GET_VARS['edit']) && $HTTP_GET_VARS['edit']=='true'){
					?>
					<table border="0">
					  <tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_end_city_id_temp', $city_end_class_array_left, '',' id="departure_end_city_id_temp" multiple="multiple" size="10"'); ?></td>
						<td><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp, this.form.departure_end_city_id_temp1);" type=button value="-->">
						<BR><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp1, this.form.departure_end_city_id_temp);" type=button value="<--"></td>
						<td>
					<?php
						echo  tep_draw_pull_down_menu('departure_end_city_id_temp1', $city_end_class_array_right, '',' id="departure_end_city_id_temp1" multiple="multiple" size="10"');?>
						<input type="hidden" name="departure_end_city_id" value="">
						</td>
					  </tr>
					</table>
						<?php
					}else{
						?>
						<table border="0">
						  <tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_end_city_id_temp', $city_end_class_array_left, '',' multiple="multiple" size="10"'); ?></td>
							<td><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp, this.form.elements['departure_end_city_id[]']);" type=button value="-->"><BR><INPUT onClick="moveOptions(this.form.elements['departure_end_city_id[]'], this.form.departure_end_city_id_temp);" type=button value="<--"></td>
							<td><?php echo  tep_draw_pull_down_menu('departure_end_city_id[]', $city_end_class_array_right, '',' id="departure_end_city_id[]" multiple="multiple" size="10"'); ?></td>
						  </tr>
						</table>
						<?php
					}
				
				 ?>
				
			</td>
		  </tr>
		  
		  <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		  <tr>
			<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESTINATION; ?></td>
			<td class="main">
			<table border="0">
				<tr>
					<td><?php 
					echo tep_draw_separator('pixel_trans.gif', '20', '12'); ?>
					
						<select name="sel1" id="sel1" size="10" multiple="multiple">
						<?php 
						echo $tempsolution;						
						?>
						</select>
					</td>
					<td align="center" valign="middle">
						<input type="button" value="--&gt;"
						 onclick="moveOptions(this.form.sel1, this.form.sel2);" /><br />
						<input type="button" value="&lt;--"
						 onclick="moveOptions(this.form.sel2, this.form.sel1);" />
					</td>
					<td>
						<select name="sel2" size="10" multiple="multiple">
						<?php 
						if($pInfo->products_id != "")
						{
							echo $sel2values;
						}
						?>
						</select>
						<input type="hidden" name="selectedcityid" value="">
					</td>
				</tr>
				
			</table>
			</td>
		  </tr>
		  
			<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          	</tr>
			</table>
<?php
}else if(isset($HTTP_GET_VARS['action_attributes']) && $HTTP_GET_VARS['action_attributes']=='true')
{
	$pInfo->agency_id = $HTTP_GET_VARS['agency_id'];
	$pInfo->products_id  = $HTTP_GET_VARS['product_id'];
?>

      <table border="3" cellspacing="5" cellpadding="2" align="center" bgcolor="000000">
		
<?php
    $rows = 0;
	
   // $options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
   if($pInfo->agency_id!='')
	{	
	  $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$pInfo->agency_id."' order by products_options_sort_order, products_options_name");
	  
	 /* $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$pInfo->agency_id."' order by products_options_sort_order, products_options_name");*/
	  
	 
	  
	  
	  //$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
	}
	else
	{
	$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
	}	
    while ($options = tep_db_fetch_array($options_query)) {
      
	  /*$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");*/
	  
	if($pInfo->agency_id!='')
	{
	
	
	  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$pInfo->agency_id."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
		 
	 
	 } 
	 else
	 {
	 	$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
	 } 
      $header = false;
      while ($values = tep_db_fetch_array($values_query)) {
        $rows ++;
        if (!$header) {
          $header = true;
?>
          <tr valign="top">
			<td><table border="2" cellpadding="2" cellspacing="2" bgcolor="FFFFFF">
              <tr class="dataTableHeadingRow">
              <td colspan="10" class="attributeBoxContent" align="center">Active Attributes</td>
             </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="250" align="left"><?php echo $options['products_options_name']; ?></td>
                <td class="dataTableHeadingContent" width="50" align="center"><?php echo 'Prefix'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Price'; ?></td>
				<td class="dataTableHeadingContent" width="70" align="center"><?php echo '<div  id="label_single[]" >Single</div>'; ?></td>
				<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Double'; ?></td>
				<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Triple'; ?></td>
				<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Quadruple'; ?></td>
				<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Kids'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Sort Order'; ?></td>
				<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Special'; ?></td>
              </tr>
<?php
        }
        $attributes = array();
        if (sizeof($HTTP_POST_VARS) > 0) {
          if ($HTTP_POST_VARS['option'][$rows]) {
            $attributes = array(
                                'products_attributes_id' => $HTTP_POST_VARS['option'][$rows],
                                'options_values_price' => $HTTP_POST_VARS['price'][$rows],
                                'price_prefix' => $HTTP_POST_VARS['prefix'][$rows],
                                'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order'][$rows],
                                    );
          }
        } else {
		  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,  products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $pInfo->products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
          if (tep_db_num_rows($attributes_query) > 0) {
            $attributes = tep_db_fetch_array($attributes_query);
          }
        }
		
		
		if($attributes['single_values_price']>0 || $attributes['double_values_price']>0 || $attributes['triple_values_price']>0 || $attributes['quadruple_values_price']>0 || $attributes['kids_values_price']>0)
		{
			$check_spe_price_attri=true;
		}
		else
		{
			$check_spe_price_attri=false;
		}
		
		
?>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo tep_draw_checkbox_field('option[' . $rows . ']', $attributes['products_attributes_id'], $attributes['products_attributes_id']) . '&nbsp;' . $values['products_options_values_name']; ?><?php echo( ($values['is_per_order_option'] == 1)? ' <small class="col_red">(Per Order)</small>' : '' ); ?>&nbsp;</td>
                <td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('prefix[' . $rows . ']', $attributes['price_prefix'], 'size="2"'); ?></td>
                <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], 'size="7" '.($check_spe_price_attri?'disabled="disabled"':'').''); ?></td>
				<!--<td class="dataTableContent" width="70" align="center">-->
				<?php //echo tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"'); ?><!--</td>-->
				
				<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"'):'<div id="show_sub_divattri_s_'.$rows.'" style="display:none" >'.tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_s_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
				
				
				
				<!--<td class="dataTableContent" width="70" align="center"><?php //echo tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"'); ?></td>-->
				
				<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"'):'<div id="show_sub_divattri_d_'.$rows.'" style="display:none" >'.tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_d_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
				
			<!--	<td class="dataTableContent" width="70" align="center"><?php //echo tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"'); ?></td>-->
				
				<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"'):'<div id="show_sub_divattri_t_'.$rows.'" style="display:none" >'.tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_t_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
				
				
				<!--<td class="dataTableContent" width="70" align="center"><?php //echo tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"'); ?></td>-->
				
				<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"'):'<div id="show_sub_divattri_q_'.$rows.'" style="display:none" >'.tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_q_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
				
				
				<!--<td class="dataTableContent" width="70" align="center"><?php //echo tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"'); ?></td>-->
				
				<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"'):'<div id="show_sub_divattri_k_'.$rows.'" style="display:none" >'.tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_k_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
				
                <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $rows . ']', $attributes['products_options_sort_order'], 'size="7"'); ?></td>
				<td class="dataTableContent" width="70" align="center">
				
				
				<?php
				
				echo  ($check_spe_price_attri?'<a href="javascript:void(0)" onclick="javascript:delete_spe_price_attri('.$rows.');">Delete spe. Price</a>':'<a href="javascript:void(0)" onclick="javascript:enter_spec_price_attri('.$rows.')">Sepecial Price</a>');
				
				?>
				
				</td>
                             </tr>
<?php
      }
      if ($header) {
?>
            </table></td>

<?php
      }
    }
?>
          </tr>
        </table>
<?php		
}
else
{

$count = $_GET['count'];
$products_id = $_GET['products_id'];
$itinerary = (int)$_GET['itinerary'];
$languages = tep_get_languages();
if($itinerary!=1){
?>

<table width = "100%"  >
						<tr>

									<td colspan=2 >
										
											</td>

											<td  >
												
														<table width="70%"   border="0" >
															<tr>
							<td width="10%"></td>
							
							<td class="main"  align="left"  width="30%">
							<b>	<?php echo TEXT_HEADING_ETICKET_ITINERARY; ?></b>
							</td>
							<td class="main" align="left" width="30%">
								<b><?php echo TEXT_HEADING_ETICKET_HOTEL; ?> </b>
							</td>
							<td class="main" align="left" width="30%" >
								<b><?php echo TEXT_HEADING_ETICKET_NOTES; ?> </b>
							</td>
						</tr>
						</table></td></tr>


		<?php
		 for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		 ?>
		 <tr>
			<td colspan=2 class="main" >
			
				<?php echo TEXT_HEADING_ETICKET. ' ' .  tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
			</td>

			<td>
				<table width="60%"  border="0" >
					<tr>
						<td>

		
		
			<table width="100%">
				
				
		<?php 
for($j=1; $j<=$count; $j++)
{
	$content_prod=tep_get_products_eticket_itinerary($products_id, $languages[$i]['id']);
	$splite_content =  explode("!##!", $content_prod);


	$content_prod_hotel=tep_get_products_eticket_hotel($products_id, $languages[$i]['id']);
	$splite_content_hotel =  explode("!##!", $content_prod_hotel);

			$content_prod_notes=tep_get_products_eticket_notes($products_id, $languages[$i]['id']);
			$splite_content_notes =  explode("!##!", $content_prod_notes);
	
?>				
		
				
						
		  <tr>
           
            
               <td class="main" align="center" width="10%" nowrap>Day <?php echo $j ?></td> 
                <td class="main" align="center" width="30%"><?php echo tep_draw_textarea_field('eticket_itinerary[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '3', (isset($eticket_itinerary[$languages[$i]['id']]) ? $eticket_itinerary[$languages[$i]['id']] : $splite_content[$j-1]),'id=eticket_itinerary[' . $languages[$i]['id'] . ']'); ?>
				</td>
<td class="main" width="30%" align="center"><?php echo tep_draw_textarea_field('eticket_hotel[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '3', (isset($eticket_hotel[$languages[$i]['id']]) ? $eticket_hotel[$languages[$i]['id']] : $splite_content_hotel[$j-1]),'id=eticket_hotel[' . $languages[$i]['id'] . ']'); ?></td>

             <td class="main" width="30%" align="center">
			 	<?php echo tep_draw_textarea_field('eticket_notes[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '3', (isset($eticket_notes[$languages[$i]['id']]) ? $eticket_notes[$languages[$i]['id']] : $splite_content_notes[$j-1]),'id=eticket_notes[' . $languages[$i]['id'] . ']'); ?>
				</td>

          </tr>
		  
		  
		<?php		
		}	
		?>
		</table>
			</td></tr></table></td></tr>

<?php	
		}?>				
				</table>
<?php }else{
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
	?>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
                                     <tr>
                                        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
                                        <td class="main"><b>Name</b></td>
                                        <td class="main"><b>Images</b></td>
                                        <td class="main"><b>Content</b></td>
                                        <td class="main"><b>Hotel</b><br />(格式:酒店名称<b style='color:red'>|</b>酒店星级)[一行一个]</td>
                                      </tr>

                                  <?php
                                  for($j=1; $j<=$count; $j++){
								?>
                                  <tr>
    <td class="main" width="6%">Day <strong><?php echo $j;?></strong></td>
    <td width="20%">
        <?php echo tep_draw_input_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][name]', '', 'size="25"'); ?>
    </td>
    <td width="16%">
       <input name="products_travel_img_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>" type="file" id="products_travel_img_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>" size="15">
</td>
    <td width="30%">
      <?php echo tep_draw_textarea_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][content]', 'soft', '35', '3', (isset($products_travel[$languages[$i]['id']][$j]['content']) ? $products_travel[$languages[$i]['id']][$j]['content'] : '')); ?>
</td>
    <td width="28%">
      <?php echo tep_draw_textarea_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][hotel]', 'soft', '30', '3', (isset($products_travel[$languages[$i]['id']][$j]['hotel']) ? $products_travel[$languages[$i]['id']][$j]['hotel'] : '')); ?>
</td>
  </tr>
                                <?php
								  }
								?></table>
<?php 
		}
	}
}
?>
