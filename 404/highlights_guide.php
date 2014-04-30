<?php 
if ($error404 === false) return;
//目的地指南根页面

if(preg_match('/highlights_guide.html/', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'destination_guide.php';
	$error404 = false;
}else{
	//目的地指南详细页
	if(preg_match('/highlights_guide/', $req)){
		$req_array = explode('/',$req);
		//echo $req_array[1];
		$attractions_key = preg_replace('/\..+$/','',$req_array[1]);
		//echo $attractions_key;
		if($attractions_key!=""){
			$sql = mysql_query('SELECT SQL_CACHE dg_categories_id, dg_categories_name_en FROM `destination_guide_categories` WHERE dg_categories_name_en="'.$attractions_key.'" limit 1');
			$row = mysql_fetch_array($sql);
			if((int)$row['dg_categories_id']){
				$HTTP_GET_VARS['field'] = $_GET['field'] = 'overview';	//目的地概况
			}else{	//除了目的地概况的其它部分
				$tmp_key_array = explode('_',$attractions_key);
				$tmp_key_array_count = count($tmp_key_array);
				$att_key='';
				$field_str='';
				for($i=0; $i<$tmp_key_array_count; $i++){
					if($i<($tmp_key_array_count-1)){
						$att_key .= $tmp_key_array[$i].'_';
					}else{
						$field_str = $tmp_key_array[$i];
					}
				}
				$att_key = substr($att_key,0,(strlen($att_key)-1));
				if($att_key!="" && $field_str!=""){
					$sql = mysql_query('SELECT SQL_CACHE dg_categories_id, dg_categories_name_en FROM `destination_guide_categories` WHERE dg_categories_name_en="'.$att_key.'" limit 1');
					$row = mysql_fetch_array($sql);
					if((int)$row['dg_categories_id']){
						$p = array('/^accommodation$/');
						$r = array('lodging');
						$field_str = preg_replace($p,$r,$field_str);
						$HTTP_GET_VARS['field'] = $_GET['field'] = $field_str;
					}
				}
			}
			
		}
		if((int)$row['dg_categories_id']){
			$HTTP_GET_VARS['dg_categories_id'] = $_GET['dg_categories_id'] = (int)$row['dg_categories_id'];
			$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'destination_guide_details.php';
			$error404 = false;
		}

	}
}
?>