<?php
/*
邮轮团的专用函数
WebMakers.com Added: Additional Functions
Written by Linda McGrath osCOMMERCE@WebMakers.com
http://www.thewebmakerscorner.com

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/

/**
 * 检查是否有重复的邮轮名称
 *
 * @param unknown_type $cruises_name
 * @param unknown_type $where
 * @return unknown
 */
function CheckCruisesName($cruises_name, $where = ""){
	$sql = tep_db_query('SELECT cruises_id  FROM `cruises` WHERE cruises_name ="'.$cruises_name.'" '.$where );
	$row = tep_db_fetch_array($sql);
	return (int)$row['cruises_id'];
}

/**
 * 取得某艘邮轮的名称
 *
 * @param unknown_type $cruises_id
 * @return unknown
 */
function getCruisesName($cruises_id){
	$sql = tep_db_query('SELECT cruises_name  FROM `cruises` WHERE cruises_id ="'.(int)$cruises_id.'" ');
	$row = tep_db_fetch_array($sql);
	return $row['cruises_name'];
}

/**
 * 取得邮轮的图片（根据情况取客舱、甲板图片等）
 *
 * @param unknown_type $id
 * @param unknown_type $type
 */
function getCruisesImages($id,$type='cruises',$cruises_id){
	$data = false;
	if(!(int)$id) return false;
	$sql = tep_db_query('SELECT * FROM `cruises_images` WHERE cruises_id="'.(int)$cruises_id.'" and images_link_id="'.(int)$id.'" AND images_type="'.tep_db_input(tep_db_prepare_input($type)).'" ORDER BY sort_id ASC, images_id DESC');
	while ($rows = tep_db_fetch_array($sql)){
		$data[] = $rows;
	}
	return $data;
}

/**
 * 取得邮轮的客舱数据用于列表菜单，返回的ID是products_options_id，不是cruises_cabin_id
 * 输出数组
 */
function getCruisesCabinOptions($cruises_id){
	$data = false;
	$sql = tep_db_query('SELECT * FROM `cruises_cabin` WHERE cruises_id="'.(int)$cruises_id.'" ORDER BY sort_id, products_options_id DESC ');
	while($rows = tep_db_fetch_array($sql)){
		$data[] = array('id'=> $rows['products_options_id'], 'text'=> tep_db_output($rows['cruises_cabin_name']));
	}
	return $data;
}

/**
 * 取得供应商邮轮的客舱选项
 * 此功能是取得供应商产品属性中的邮轮的客舱选项
 * 返回数组
 * @param unknown_type $agency_id
 */
function getAgencyProductsOptions($agency_id, $filter_products_options_id=''){
	$data = false;
	$not_in = 0;
	if($filter_products_options_id!=""){
		$not_in = $filter_products_options_id;
	}
	$sql = tep_db_query('SELECT * FROM `products_options` p, `products_attributes_tour_provider` patp WHERE p.products_options_id=patp.products_options_id AND patp.agency_id="'.(int)$agency_id.'" AND p.products_options_id not in('.$not_in.') ORDER BY p.`products_options_sort_order` ');
	while ($rows=tep_db_fetch_array($sql)) {
		$data[] = array('id'=>$rows['products_options_id'],'text'=>$rows['products_options_name']);
	}
	return $data;
}

/**
 * 取得产品对应的邮轮ID号
 * @return 0 或 邮轮的ID号
 */
function getProductsCruisesId($products_id){
	$sql = tep_db_query('SELECT cruises_id FROM `products_to_cruises` WHERE products_id ="'.(int)$products_id.'" ');
	$row = tep_db_fetch_array($sql);
	return (int)$row['cruises_id'];
}

/**
 * 取得产品邮轮的信息，包括价格信息，此功能要调用产品选项价格信息，如果没有相关的产品选项将不会出现
 * 注意：此函数只用于产品详细页面
 * @param unknown_type $cruises_id
 */
function getProductsCruisesInfos($cruises_id, $products_id){
	global $tour_agency_opr_currency;
	$data = false;
	$sql = tep_db_query('SELECT * FROM `cruises` WHERE 1 AND cruises_id="'.(int)$cruises_id.'" ');
	$data = tep_db_fetch_array($sql);
	//判断产品是否是显示房间的产品
	$pSql = tep_db_query('SELECT display_room_option FROM `products` WHERE 1 AND products_id="'.(int)$products_id.'" ');
	$pRow = tep_db_fetch_array($pSql);

	//取得客舱和甲板资料
	$csql=tep_db_query('SELECT * FROM `cruises_cabin` WHERE cruises_id="'.(int)$cruises_id.'" ORDER BY sort_id ASC, products_options_id DESC');
	$data['cabins'] = array();
	$loop=0;
	while($crows = tep_db_fetch_array($csql)){
		$data['cabins'][$loop] = $crows;
		//图片
		$data['cabins'][$loop]['images'] = getCruisesImages($crows['products_options_id'], 'cabin', (int)$cruises_id);
		//甲板
		$values_price_where = ' AND pa.options_values_price >0 ';
		if($pRow['display_room_option']=="1"){
			$values_price_where = ' AND (pa.single_values_price >0 || pa.double_values_price >0 || pa.triple_values_price >0 || pa.quadruple_values_price >0) ';
		}
		$dsql = tep_db_query('SELECT ccd.*,pa.options_values_price,pa.single_values_price,pa.double_values_price,pa.triple_values_price,pa.quadruple_values_price,pa.kids_values_price FROM `cruises_cabin_deck` ccd, products_attributes pa WHERE ccd.products_options_id="'.$crows['products_options_id'].'" AND pa.options_values_id=ccd.products_options_values_id '.$values_price_where.' AND pa.products_id="'.(int)$products_id.'" AND ccd.cruises_id="'.(int)$cruises_id.'" ORDER BY ccd.sort_id ASC, ccd.products_options_values_id DESC ');

		$lp1 = 0;
		while($drows=tep_db_fetch_array($dsql)){
			$data['cabins'][$loop]['decks'][$lp1] = $drows;
			$data['cabins'][$loop]['decks'][$lp1]['images'] = getCruisesImages($drows['products_options_values_id'], 'deck', (int)$cruises_id);
			if($pRow['display_room_option']=="1"){	// room
				$optionsValuesMinPrice = $drows['single_values_price'];
				if((int)$drows['double_values_price'] && $drows['double_values_price'] < $optionsValuesMinPrice){
					$optionsValuesMinPrice = $drows['double_values_price'];
				}
				if((int)$drows['triple_values_price'] && $drows['triple_values_price'] < $optionsValuesMinPrice){
					$optionsValuesMinPrice = $drows['triple_values_price'];
				}
				if((int)$drows['quadruple_values_price'] && $drows['quadruple_values_price'] < $optionsValuesMinPrice){
					$optionsValuesMinPrice = $drows['quadruple_values_price'];
				}
				/*
				if((int)$drows['kids_values_price'] && $drows['kids_values_price'] < $optionsValuesMinPrice){
					$optionsValuesMinPrice = $drows['kids_values_price'];
				}*/

			}else{	//no room
				$optionsValuesMinPrice = $drows['options_values_price'];
			}

			if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
				$data['cabins'][$loop]['decks'][$lp1]['single_values_price']=tep_get_tour_price_in_usd($data['cabins'][$loop]['decks'][$lp1]['single_values_price'],$tour_agency_opr_currency);
				$data['cabins'][$loop]['decks'][$lp1]['double_values_price']=tep_get_tour_price_in_usd($data['cabins'][$loop]['decks'][$lp1]['double_values_price'],$tour_agency_opr_currency);
				$data['cabins'][$loop]['decks'][$lp1]['triple_values_price']=tep_get_tour_price_in_usd($data['cabins'][$loop]['decks'][$lp1]['triple_values_price'],$tour_agency_opr_currency);
				$data['cabins'][$loop]['decks'][$lp1]['quadruple_values_price']=tep_get_tour_price_in_usd($data['cabins'][$loop]['decks'][$lp1]['quadruple_values_price'],$tour_agency_opr_currency);
				$data['cabins'][$loop]['decks'][$lp1]['optionsValuesMinPrice']=tep_get_tour_price_in_usd($optionsValuesMinPrice,$tour_agency_opr_currency);
			}else{
				$data['cabins'][$loop]['decks'][$lp1]['optionsValuesMinPrice']=$optionsValuesMinPrice;
			}
			$lp1++;
			//print_r($drows);
		}
		//如果该舱下没有包括价格的甲板信息则不取出该客舱信息
		if($lp1==0){
			unset($data['cabins'][$loop]);
		}else{
			$loop++;
		}
	}

	return $data;

}

/**
 * 取得邮轮团每人要交的税金
 *
 * @param unknown_type $products_options_id
 * @return unknown
 */
function getCruisesPerPersonTaxT($products_options_id){
	$sql = tep_db_query('SELECT options_values_price FROM `products_options_values_to_products_options` povtp, `products_attributes` pa WHERE `products_options_id`="'.(int)$products_options_id.'" AND pa.options_id=povtp.products_options_id AND options_values_price>0 Limit 1');
	$row = tep_db_fetch_array($sql);
	return $row['options_values_price'];
}
?>