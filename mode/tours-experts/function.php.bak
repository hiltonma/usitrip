<?php
$urlParme=$_GET;
function makesearchUrl($mod='',$val='',$dir=false){
	global $urlParme,$baseUrl;
	$url = makeDirUrl($urlParme,$mod,$val,$dir);
	return tep_href_link($baseUrl,$url);
}

function getPhoto($photo){
	if(trim($photo)==''){
		$photo = 'image/expert_user_nopic.jpg';
	}else{
		$photo = DIR_WS_IMAGES .$photo;
		if(!is_file($photo)){
			$photo = 'image/expert_user_nopic.jpg';
		}
	}
	return $photo;
}
?>