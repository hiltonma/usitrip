<?php
require_once('includes/application_top.php');
if(isset($_GET['ajax']) || isset($_POST['ajax'])){
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-type: text/html; charset=" . CHARSET);

	$ajax = true;
}


//取得某个问题的回复列表
$reviews_id = (int)$reviews['reviews_id'] ? (int)$reviews['reviews_id'] : (int)$_GET['reviews_id'];
if((int)$reviews_id){	
	$reviews_sql = tep_db_query('SELECT * FROM '.TABLE_REVIEWS.' r, '.TABLE_REVIEWS_DESCRIPTION.' rd  WHERE r.parent_reviews_id="'.$reviews_id.'" and rd.languages_id ="1" and rd.reviews_id = r.reviews_id Order By r.date_added DESC ');
	$html_string = "";
	while($reviews_rows = tep_db_fetch_array($reviews_sql)){
		$bottons_all = '';
		$bottons_all .= '&nbsp;&nbsp;<button onclick="window.open(&quot;'.tep_href_link(FILENAME_REVIEWS, 'rID=' . (int)$reviews_rows['reviews_id']. '&action=edit').'&quot;)" type="button"> Edit </button>';
		$bottons_all .= '&nbsp;&nbsp;<button onclick="fast_del_reviews('.(int)$reviews_id.', '.(int)$reviews_rows['reviews_id'].')" type="button"> Delete </button>';
		$bottons_all .= '&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="show_this_child('.(int)$reviews_id.', 1)"> Refresh </button>';
		
		$html_string .= '<div style="padding-bottom:10px;">';
		
		$status_img = '<a onclick="fast_set_status('.$reviews_rows['reviews_id'].','.(int)$reviews_id.',1)" href="javascript:;">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
		if($reviews_rows['reviews_status']==1){
			$status_img = tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a onclick="fast_set_status('.$reviews_rows['reviews_id'].','.(int)$reviews_id.',0)" href="javascript:;">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
		}
		
		$html_string .= '	<div> Status:'.$status_img.' Created time:'.db_to_html($reviews_rows['date_added']).'&nbsp;&nbsp;Created By:'.db_to_html($reviews_rows['customers_name']).'&nbsp;&nbsp;Last Modified:'.db_to_html($reviews_rows['last_modified']).'&nbsp;&nbsp;Modified By:'.db_to_html(tep_get_admin_customer_name($reviews_rows['admin_id'])).'</div>
				<div>'.nl2br(db_to_html($reviews_rows['reviews_text'])).'</div>
				<div>'.$bottons_all.'</div>
			</div><hr>';
	}
	if($ajax!=true){
		echo $html_string;
	}else{
		$js_str = ' document.getElementById("child_div_'.$reviews_id.'").innerHTML = "'.addslashes($html_string).'";';
		$js_str .= ' document.getElementById("child_tr_'.$reviews_id.'").style.display = ""; ';
		$js_string = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
		echo $js_string;
	}
}
?>