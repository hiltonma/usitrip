<?php
require('includes/application_top.php');



$sql = tep_db_query('SELECT  products_id, products_single, products_double, products_triple, products_quadr FROM `products` ');

while($rows = tep_db_fetch_array($sql)){
	//不更新目录为143(特价)的产品价格
	$cat_prod_sql = tep_db_query('SELECT * FROM `products_to_categories` WHERE categories_id ="143" AND products_id="'.(int)$rows['products_id'].'" limit 1');
	$cat_prod_rows = tep_db_fetch_array($cat_prod_sql);
	
	if(!(int)$cat_prod_rows['categories_id']){

		$lowest_price = 0;
		
		$price_array = array();
		if((int)$rows['products_single']){
			$price_array[] = $rows['products_single'];
		}
		if((int)$rows['products_double']){
			$price_array[] = $rows['products_double'];
		}
		if((int)$rows['products_triple']){
			$price_array[] = $rows['products_triple'];
		}
		if((int)$rows['products_quadr']){
			$price_array[] = $rows['products_quadr'];
		}
		
		sort($price_array,SORT_NUMERIC);
		$lowest_price = $price_array[0];	
		
		tep_db_query('update `products` SET products_price="'.$lowest_price.'" WHERE products_id="'.(int)$rows['products_id'].'" ');
	}
}

echo '[UPDATE]1[/UPDATE]';

?>