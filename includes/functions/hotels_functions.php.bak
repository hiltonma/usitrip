<?php
/*
WebMakers.com Added: Additional Functions
Written by Linda McGrath osCOMMERCE@WebMakers.com
http://www.thewebmakerscorner.com

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/

/**
 * 酒店特有的函数，前后台共用 Howard added
 */

/**
 * 取得酒店餐食选项
 * @param $mealsId餐食id，如果没有ID则输出所有
 */
function getHotelMealsOptions($mealsId=0){
	$array = array();
	$array[] = array('id'=>1,'text'=>"含早餐");
	$array[] = array('id'=>2,'text'=>"含午餐");
	$array[] = array('id'=>3,'text'=>"含晚餐");
	$array[] = array('id'=>4,'text'=>"含早餐和午餐");
	$array[] = array('id'=>5,'text'=>"含早餐和晚餐");
	$array[] = array('id'=>6,'text'=>"含午餐和晚餐");
	$array[] = array('id'=>7,'text'=>"含早餐、午餐和晚餐");
	$array[] = array('id'=>9,'text'=>"不含餐");
	if((int)$mealsId){
		foreach($array as $key => $val){
			if($mealsId==$array[$key]['id']){
				return $array[$key]['text'];
			}
		}
		return '';
	}
	return $array;
}

/**
 * 取得酒店大概位置选项
 * @param $locationId位置id，如果没有ID则输出所有
 */
function getHotelApproximateLocation($locationId=0){
	$array = array();
	$array[] = array('id'=>1,'text'=>"机场附近");
	$array[] = array('id'=>2,'text'=>"市中心");
	$array[] = array('id'=>3,'text'=>"市区");
	$array[] = array('id'=>4,'text'=>"郊区");
	$array[] = array('id'=>5,'text'=>"旅游区");
	$array[] = array('id'=>6,'text'=>"拉斯维加斯大道");
	
	if((int)$locationId){
		foreach($array as $key => $val){
			if($locationId==$array[$key]['id']){
				return $array[$key]['text'];
			}
		}
		return '';
	}
	return $array;
}

/**
 * 酒店上网属性
 * @param $optionsId位置id，如果没有ID则输出所有
 */
function getHotelInternetOptions($optionsId=0){
	$array = array();
	$array[] = array('id'=>1,'text'=>"免费宽带");
	$array[] = array('id'=>2,'text'=>"免费有线上网");
	$array[] = array('id'=>3,'text'=>"免费无线上网");
	$array[] = array('id'=>4,'text'=>"付费上网");
	$array[] = array('id'=>5,'text'=>"无免费上网服务");
	$array[] = array('id'=>6,'text'=>"免费上网服务");
	$array[] = array('id'=>9,'text'=>"无上网服务");
	if((int)$optionsId){
		foreach($array as $key => $val){
			if($optionsId==$array[$key]['id']){
				return $array[$key]['text'];
			}
		}
		return '';
	}
	return $array;
}

/**
 * 取得某酒店的所有图片信息
 * @param $hotel_id
 * @return $infos[]
 */
function getHotelImagesInfos($hotel_id){
	$infos = array();
	$hotel_pic_sql = tep_db_query('SELECT * FROM `hotel_pic` WHERE hotel_id ="'.(int)$hotel_id.'" ORDER BY `hotel_pic_sort` ASC ');
	$hotel_pic_rows = tep_db_fetch_array($hotel_pic_sql);
	if((int)$hotel_pic_rows['hotel_pic_id']){
		$first_img_src = $hotel_pic_rows['hotel_pic_url'];
		if(!preg_match('/^http:\/\//',$first_img_src)){
			$first_img_src = DIR_WS_IMAGES.'hotel/'.$first_img_src;
		}
		$first_img_alt = tep_db_output($hotel_pic_rows['hotel_pic_alt']);
		$infos[]=array('src'=> $first_img_src, 'alt'=>ALT_VIEW_BIG_PIC, 'desc'=> $hotel_pic_rows['hotel_pic_alt']);
		do{
			$img_src =$hotel_pic_rows['hotel_pic_url'];
			if(!preg_match('/^http:\/\//',$img_src)){
				$img_src = DIR_WS_IMAGES.'hotel/'.$img_src;
			}
			$img_alt = tep_db_output($hotel_pic_rows['hotel_pic_alt']);

			$infos[]=array('src'=> $img_src, 'alt'=>$img_alt, 'desc'=> $img_alt);
		}while($hotel_pic_rows = tep_db_fetch_array($hotel_pic_sql));
	}
	if(!(int)sizeof($infos) || ((int)sizeof($infos)==1 && !tep_not_null($infos[0]['src'])) ){ return false; }
	return $infos;
}
?>