<?php header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");   
include_once "includes/configure.php";
include_once "includes/database_tables.php";
include_once "includes/functions/database.php";
include_once "includes/functions/html_output.php";
include_once "includes/functions/general.php";
tep_db_connect();


if(isset($_GET['products_model']) && $_GET['products_model']!='')
{
	if($_GET['products_id'] != "")
	{
	$check_same_model =  tep_db_query("select * from ".TABLE_PRODUCTS."  where products_id not in (".$_GET['products_id'].") and products_model = '".$_GET['products_model']."' ");
	}
	else
	{
	$check_same_model =  tep_db_query("select * from ".TABLE_PRODUCTS."  where products_model = '".$_GET['products_model']."' ");
	}
	$check_same_result = tep_db_fetch_array($check_same_model);
	if($check_same_result['products_model'] != "")
	{
		echo 'Please enter another products-model as this was already in use';
	}
	else
	{
		echo '0';
	}
}

if(isset($_GET['agency_id']) && $_GET['agency_id']!='' )
{
	$k=1;
	$edit_departure_data = '';
	$options_region = '';
	$regionquery = 'select * from '.TABLE_TOUR_PROVIDER_REGIONS.' where agency_id = "'.$_GET['agency_id'].'" order by region';	 
	$regionrow = mysql_query($regionquery);	
	while($products_departure_result = mysql_fetch_array($regionrow))
	{
		$edit_extra_lines = "";
		if($region_name_display != $products_departure_result['region'])
		{
		$region_name_display = $products_departure_result['region'];
		$options_region .= '<option value="'.$region_name_display.'">'.$region_name_display.'</option>';
		$edit_extra_lines = '<tr><td colspan="6" class="main"><b>'.$region_name_display.'</b></td></tr>';
		}
	$edit_departure_data .= '<table id="table_id_departure'.$k.'" cellpadding="2" cellspacing="2" width="100%"><tbody>'.$edit_extra_lines.'<tr class="'.(floor($k/2) == ($k/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent" valign="top" width="110" nowrap>'.$k.'.<input name="depart_region_'.$k.'" value="'.addslashes($products_departure_result['region']).'" size="12" type="hidden" readonly></td><td class="dataTableContent" valign="top" width="100" nowrap><input name="depart_time_'.$k.'" value="'.addslashes($products_departure_result['departure_time']).'" size="12" type="text"></td><td class="dataTableContent" align="center"  ><input size="20" name="departure_address_'.$k.'" value="'.addslashes($products_departure_result['hotel']).'" type="text"></td><td class="dataTableContent" align="center" ><input size="30" name="departure_full_address_'.$k.'" value="'.addslashes($products_departure_result['address']).', '.addslashes($products_departure_result['city']).'" type="text"></td><td class="dataTableContent" align="center" width="100"><input size="30" name="departure_map_path_'.$k.'" value="'.addslashes($products_departure_result['map_path']).'" type="text"></td><td align="center" width="70"><input src="includes/languages/english/images/buttons/button_delete.gif" name="delete_depart_'.$k.'" onclick="return cleardeparturerow('.$k.')" type="image"></td></tr></tbody></table>';

			
			$k++;
	}
	
	$select_region = '<select name="region">'.$options_region.'</select>';
	
	if($_GET['edit']=='true')
	echo $select_region;
	else
	echo $edit_departure_data."<::::::::::>".$select_region."<::::::::::>".$k;
}
?>

