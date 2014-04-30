<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");

require_once('includes/application_top.php');
get_Convertutf8($_SESSION['language']);

if(tep_not_null($_GET['total_adult'])&&tep_not_null($_GET['products_id'])&&tep_not_null($_GET['departure_date'])){
	$check_sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.tep_db_prepare_input($_GET['products_id']).'" AND departure_date ="'.tep_db_prepare_input($_GET['departure_date']).'"');
	//$check_row = tep_db_fetch_array($check_sql);
	if((int)tep_db_num_rows($check_sql)>'0'){

			$sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.tep_db_prepare_input($_GET['products_id']).'" AND departure_date="'.tep_db_prepare_input($_GET['departure_date']).'"');
			$row = tep_db_fetch_array($sql);
			if($row['remaining_seats_num']<$_GET['total_adult']){
				 //echo '<td colspan="3" align="center">';
				//结伴同游选项
	           if(TRAVEL_COMPANION_OFF_ON=='true'){
		         echo '<input name="travel_comp" id="travel_comp" type="hidden" />';
	           }
	           echo tep_draw_hidden_field('products_id', $_GET['products_id']) . tep_template_image_submit('button_ok.gif', 'Update','onclick="alert(\''.general_to_ajax_string(db_to_html('该团剩余座位小于订购人数,请选其它日期的团！')).'\')" onmouseover="check_remaining_seats_edit()" id="check_edit_tour" style=""');
               echo '&nbsp;<a onClick="parent.HideContent(\'edit_cart_product_data_'.(int)$_GET['products_id'].'\');parent.ShowContent(\'cart_product_data_'.(int)$_GET['products_id'].'\');  return true;"  href="javascript:parent.HideContent(\'edit_cart_product_data_'.(int)$_GET['products_id'].'\');parent.ShowContent(\'cart_product_data_'.$_GET['products_id'].'\');">'. tep_template_image_button('button_cancel.gif', 'Cancel','','') . '</a>';
	          // echo '</td></tr>';
			}else{
				//echo '<td colspan="3" align="center">';
				//结伴同游选项
	           if(TRAVEL_COMPANION_OFF_ON=='true'){
		         echo '<input name="travel_comp" id="travel_comp" type="hidden" />';
	           }
	           echo tep_draw_hidden_field('products_id', $_GET['products_id']) . tep_template_image_submit('button_ok.gif', 'Update','onmouseover="check_remaining_seats_edit()" onmouseout="clearStart(this)" id="check_edit_tour" style=""');#onclick="return validate()" 
               echo '&nbsp;<a onClick="parent.HideContent(\'edit_cart_product_data_'.(int)$_GET['products_id'].'\');parent.ShowContent(\'cart_product_data_'.(int)$_GET['products_id'].'\');  return true;"  href="javascript:parent.HideContent(\'edit_cart_product_data_'.(int)$_GET['products_id'].'\');parent.ShowContent(\'cart_product_data_'.(int)$_GET['products_id'].'\');">'. tep_template_image_button('button_cancel.gif', 'Cancel','','') . '</a>';
	          // echo '</td></tr>';
			}

    }else{
    	//echo '<td colspan="3" align="center">';
    	if(TRAVEL_COMPANION_OFF_ON=='true'){
		         echo '<input name="travel_comp" id="travel_comp" type="hidden" />';
	           }
	           echo tep_draw_hidden_field('products_id', $_GET['products_id']) . tep_template_image_submit('button_ok.gif', 'Update','onclick="return validate()" onmouseover="check_remaining_seats_edit()" id="check_edit_tour" style=""');
               echo '&nbsp;<a onClick="parent.HideContent(\'edit_cart_product_data_'.(int)$_GET['products_id'].'\');parent.ShowContent(\'cart_product_data_'.(int)$_GET['products_id'].'\');  return true;"  href="javascript:parent.HideContent(\'edit_cart_product_data_'.(int)$_GET['products_id'].'\');parent.ShowContent(\'cart_product_data_'.(int)$_GET['products_id'].'\');">'. tep_template_image_button('button_cancel.gif', 'Cancel','','') . '</a>';
	           //echo '</td></tr>';

    }
}
?>
