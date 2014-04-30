<?php
//本程序自动取得用户的消息并更新到top_msn_box上
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

if((int)$customer_id){
	$sql = tep_db_query('SELECT count(*) as total FROM `site_inner_sms` WHERE owner_id ="'.$customer_id.'" and to_customers_id="'.$customer_id.'" and has_read!="1" ');
	$row = tep_db_fetch_array($sql);
	$html_code = "";
	if((int)$row['total']){
		$href_links = tep_href_link('my_travel_companion.php').'#my_information';
		$html_code = '<a href="'.$href_links.'" title="您有'.(int)$row['total'].'条短消息！">('.(int)$row['total'].'条未读)</a>';
	}
	if($html_code!=""){	
		$js_string ="";
		$js_string.= '
		var msn_box = document.getElementById("top_msn_box");
		if(msn_box!=null){
			msn_box.innerHTML = "'.addslashes($html_code).'";
		}
		
		';
		$js_string = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_string).'[/JS]';
		echo db_to_html($js_string);
	}
	exit;
}
?>