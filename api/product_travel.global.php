<?php
function formatTravelData($travelstr){
	$travelData=array();
	$travelstr = formathtml($travelstr);

	preg_match_all('~(<H5[^>]*?class=sp4[^>]*?><SPAN[^>]*?class=sp2[^>]*?></SPAN></H5>)?<H5[^>]*?class=["|\']?sp4["|\']?[^>]*?><SPAN[^>]*?class=["|\']?sp2["|\']?[^>]*?>(.*?)</SPAN>(.*?)</H5>.*?<DIV[^>]*?((class=["|\']?p_p_1["|\']?)|(class=["|\']?p_p["|\']?))\s*?>(.*?)</DIV>~is',$travelstr,$data);
	//print_r($data);exit;
	$st_key=0;
	foreach($data[2] as $key=>$item){
		$travelData[$st_key++]["travel_index"]=$st_key;
	}
	foreach($data[3] as $key=>$item){
		if(trim(strip_tags($item)))$travelData[$key]["travel_name"]=trim(strip_tags($item));
	}
	foreach($data[7] as $key=>$item){
		$item = preg_replace('~<br[^>]*?(/)?[^>]*?>~is',"\r\n",$item);
		$travelData[$key]["travel_content"]=strip_tags($item);
	}
	foreach($data[0] as $key=>$item){
		preg_match_all('~<DIV[^>]*?class=["|\']?p_p_img["|\']?[^>]*?>.*?<img[^>]*?src=["|\'](.*?)["|\'][^>]*?>.*?</DIV>~is',$item,$dataimg);
		$travelData[$key]["travel_img"]=$dataimg[1][0];
	}

	$travelstr=str_replace("<SPAN class=hotel_title>酒店</SPAN>:","<SPAN class=hotel_title>酒店:</SPAN>",$travelstr);
	$travelstr=str_replace("：",":",$travelstr);
	$travelstr=str_replace("STRONG>","B>",$travelstr);
	preg_match_all('~<TD[^>]*?><B[^>]*?><SPAN[^>]*?class=hotel_title[^>]*?>酒店:(.*?)</B></TD>~is',$travelstr,$data);
	foreach($data[1] as $key=>$item){
		$item = strip_tags($item);
		$item = str_replace("或者","或",$item);
		$item = str_replace("或同等级酒店","",$item);
		$item = str_replace("或同等级的酒店","",$item);
		$item = str_replace(", ","或",$item);
		$item = str_replace(" / ","或",$item);
		$item = str_replace(" or ","或",$item);
		$item = str_replace("或","\r\n",$item);
		$travelData[$key]["travel_hotel"]=trim($item.'');
	}
	//echo $travelstr;
	//print_r($travelData);exit;
	return $travelData;
}
?>