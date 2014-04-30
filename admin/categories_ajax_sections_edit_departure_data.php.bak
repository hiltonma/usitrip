<?php
//上车地点和时间列表
$start_num = 0;
$max_row_num = 10;
if((int)$_GET['ajax_slip_page']&&(int)$_GET['products_id']&&(int)$_GET['page_id']){
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
	header("Pragma: no-cache");
	require_once('includes/application_top.php');
	header("Content-type: text/html; charset=".CHARSET."");

	$products_id = $_GET['products_id'];
	$start_num = $max_row_num*((int)$_GET['page_id']-1);
	$agency_id = tep_get_products_agency_id($products_id);
}else{
	$products_id = $pInfo->products_id;
	$agency_id = $pInfo->agency_id;
}



if($products_id != ""){

	$products_departure_query = tep_db_query("select tour_provider_regions_id,departure_tips from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = ".$products_id." and tour_provider_regions_id!='' ");
	$products_departures = array();
	$departure_tips_array = array();
	while($products_departure = tep_db_fetch_array($products_departure_query)){
		$products_departures[] = $products_departure['tour_provider_regions_id'];
		$departure_tips_array[$products_departure['tour_provider_regions_id']] = $products_departure['departure_tips'];
	}
	//departure_id 	products_id 	departure_region 	departure_address 	departure_time 	map_path 	departure_full_address 	departure_tips 	products_hotels_ids
	//tour_provider_regions_id 	agency_ids 	region 	address 	full_address 	departure_time 	map_path 	departure_tips 	products_hotels_ids 
	$agency_departure_query = tep_db_query("select tour_provider_regions_id, departure_time, map_path, departure_tips, products_hotels_ids, region as departure_region, address as departure_address, full_address as departure_full_address  from " . TABLE_TOUR_PROVIDER_REGIONS . " where FIND_IN_SET('".$agency_id."', agency_ids) order by departure_region, departure_time ");
	
	$rows_total = tep_db_num_rows($agency_departure_query);	//总记录数

	$pages_total = ceil($rows_total/$max_row_num);	//总页数
	$page_code="";
	for($i=1; $i<=$pages_total; $i++){
		if($i==(int)$_GET['page_id'] || (!(int)$_GET['page_id'] && $i==1)){
			$page_code .= '<span style="color:#FF6600">第'.$i.'页</span> ';
		}else{
			$page_code .= '<a href="javascript:void(0);" onclick="split_page_for_edit_departure_data('.$i.','.(int)$products_id.');">第'.$i.'页</a> ';
		}
	}

	$k = 1;
	$kk = 0;
	while ($products_departure_result = tep_db_fetch_array($agency_departure_query)){
		$edit_extra_lines = "";
		if($region_name_display != $products_departure_result['departure_region']){
			$selecthisid = $k;
			$region_name_display = $products_departure_result['departure_region'];
			$region_checked = '';
			$edit_extra_lines = '<tr><td colspan="6" class="main"><input type="checkbox" '.$region_checked.' name="chkalldasda'.$selecthisid.'" onClick="chkallregion(this.form,this.checked,\'selece_'.$selecthisid.'\')" ><b>'.$region_name_display.'</b></td></tr>';
		}

		$table_style = '';
		if($kk<$start_num || $kk>=($start_num+$max_row_num)){
			$table_style = ' style="display:none" ';
		}
		$kk++;
		
		$address_checked = '';
		$address_tips = '';
		if(in_array($products_departure_result['tour_provider_regions_id'], (array)$products_departures)){
			$address_checked = 'checked';
			$address_tips = $departure_tips_array[$products_departure_result['tour_provider_regions_id']];
		}
		$edit_departure_data .= '<table id="table_id_departure'.$k.'" cellpadding="2" cellspacing="2" width="100%" '.$table_style.'><tbody>'.$edit_extra_lines.'<tr class="'.(floor($k/2) == ($k/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent" valign="top" width="110" nowrap><input class="buttonstyle" '.$address_checked.'  id="selece_'.$selecthisid.'" name="regioninsertid_'.$k.'" type="checkbox" value="'.$k.'">'.$k.'.<input name="tour_provider_regions_id_'.$k.'" type="hidden" value="'.(int)$products_departure_result['tour_provider_regions_id'].'" /><input name="depart_region_'.$k.'" value="'.addslashes($products_departure_result['departure_region']).'" size="12" type="hidden" ></td><td class="dataTableContent" valign="top" width="100" nowrap>'.tep_draw_input_num_en_field('depart_time_'.$k,addslashes($products_departure_result['departure_time']),'size="12"').'</td><td class="dataTableContent" valign="top"  ><input size="20" name="departure_address_'.$k.'" value="'.addslashes($products_departure_result['departure_address']).'" type="text"></td><td class="dataTableContent" valign="top" ><input size="30" name="departure_full_address_'.$k.'" value="'.addslashes($products_departure_result['departure_full_address']).'" type="text"><input size="30" name="products_hotels_ids_'.$k.'" value="'.addslashes($products_departure_result['products_hotels_ids']).'" type="text"></td><td class="dataTableContent" valign="top" width="100"><input size="30" name="departure_map_path_'.$k.'" value="'.addslashes($products_departure_result['map_path']).'" type="text"></td><td class="dataTableContent" valign="top" width="100"><textarea name="departure_tips_'.$k.'" cols="20" rows="3" >'.addslashes(($address_tips == '' ? $products_departure_result['departure_tips'] : $address_tips)).'</textarea></td></tr></tbody></table>';


		$k++;
	}

	if($pages_total>1){	//超过1页时显示
		$page_code = '<div>'.$page_code.'</div>';
		$edit_departure_data .=$page_code;
		$edit_departure_data .='<div id="loading_img_categories_ajax_sections_edit_departure_data" style="display:none;"><img src="/admin/images/loading.gif"></div>';
	}
	echo $edit_departure_data;
	//echo date('H:i',strtotime('11:25pm'));
	//echo $rows_total;
}
?>
