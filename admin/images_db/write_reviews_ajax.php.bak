<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php'); 
$error = false;
$date_time = date('Y-m-d H:i:s');
if(strlen(trim($_POST['reviews_content'])) <1 && strlen(trim($_POST['reviews_people_name'])) <1 ){
	$error = true;
	echo("reviews_content or reviews_people_name is null!");
}
if(!tep_not_null($_POST['reviews_type_id'])){
	$error = true;
	echo("reviews_type_id is null!");
}
if(!tep_not_null($_POST['reviews_type'])){
	$error = true;
	echo("reviews_type is null!");
}

if($error == false){
	$reviews_people_name = iconv('utf-8','gb2312',tep_db_prepare_input($_POST['reviews_people_name']));
	$reviews_content = iconv('utf-8','gb2312',tep_db_prepare_input($_POST['reviews_content']));
	$reviews_type_id = iconv('utf-8','gb2312',tep_db_prepare_input($_POST['reviews_type_id']));
	$reviews_type = iconv('utf-8','gb2312',tep_db_prepare_input($_POST['reviews_type']));
	
	$sql_data_array = array('reviews_people_name' => $reviews_people_name,
							'reviews_content' => $reviews_content,
							'reviews_type_id' => $reviews_type_id,
							'reviews_time' => $date_time,
							'reviews_type' => $reviews_type
							);
	tep_db_perform('reviews', $sql_data_array);
	//统计评论总数
	$sql_total = tep_db_query("select count(*) as c_num from `reviews` where  reviews_type='".$reviews_type."' AND reviews_type_id='".$reviews_type_id."' ");
	$row_total =  tep_db_fetch_array($sql_total);
	$totalRows = (int)$row_total['c_num'];
	
	if($reviews_type=="images"){
		$id_str = "images_id";
	}elseif($reviews_type=="products"){
		$id_str = "products_id";
	}elseif($reviews_type=="creative"){
		$id_str = "creative_id";
	}
	//tep_db_query("UPDATE ".$reviews_type." SET `reviews_total` = '".$totalRows."',".$reviews_type."_date='".$date_time."' WHERE ".$id_str." = '".$reviews_type_id."' ; ");
	tep_db_query("UPDATE ".$reviews_type." SET `reviews_total` = '".$totalRows."' WHERE ".$id_str." = '".$reviews_type_id."' ; ");

	echo "[OK]1[/OK]";
	echo "[TA]".$totalRows."[/TA]";
}
?>
