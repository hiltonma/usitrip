<?php
//首页用到的一些函数，只用在首页
/**
 * 取得首页的特价，返回数组
 *
 */
function tep_get_index_homepage_specials(){
	global $languages_id;
	$data = false;
	$p_id_str = '970,719,411';
	if(defined('TOURS_HOMEPAGE_SPECIAL_OFFERS')){
		$p_id_str = TOURS_HOMEPAGE_SPECIAL_OFFERS;
	}
	$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_image, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id from ".TABLE_SPECIALS." as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd  where s.products_id in(".$p_id_str.") and s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and s.status=1 Order BY s.specials_date_added DESC limit 12 ");
	while ($rows = tep_db_fetch_array($specials_sql)) {
		$data[] = $rows;
	}
	return $data;
}

/**
 * 取得热销排行榜数据，返回数组
 *
 */
function tep_get_best_sellers(){
	$data = false;
	$prod_id_str ='';
	if(defined('TOURS_HOMEPAGE_BEST_SELLERS')){
		$prod_id_str = TOURS_HOMEPAGE_BEST_SELLERS;
	}
	if(tep_not_null($prod_id_str)){
		$prod_id_array = explode(',',$prod_id_str);	
		foreach($prod_id_array as $key => $val){
			$sql = tep_db_query("select p.products_id,products_name,products_tax_class_id,products_price FROM products as p, products_description as pd WHERE p.products_id=pd.products_id AND p.products_id ='".(int)$val."' ");			
			$row = tep_db_fetch_array($sql);
			if((int)$row['products_id']){
				$data[] = $row;
			}
		}
	}
	return $data;
}
?>