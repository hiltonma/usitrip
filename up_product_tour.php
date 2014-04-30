<?php
/*
  $Id: account_edit.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!tep_session_is_registered('customer_id') || $customer_id!=58) {
	die('plx use id=58\'s use xmzhh2000@126.com to login it.');  
}

//从howard数据库的表products_description复制products_description到当前数据库下面的products_description表的products_description字段中去。
//1连接howard-dev数据库
$php_errormsg = 'user ro pass or db_server error!';
$link_howard_db = mysql_connect(DB_SERVER, 'root', 'GoodLuck2008') or die($php_errormsg);
mysql_select_db('usitrip_com_howard_dev',$link_howard_db) or die('not link db!');

$sql = mysql_query('SELECT products_id, language_id, products_description FROM `products_description` ');
while($rows = mysql_fetch_array($sql)){
	$up_array=array();
	//$up_array=array('products_description' => tep_db_prepare_input($rows['products_description']));
	$up_array=array('products_description' => $rows['products_description']);
	tep_db_perform('products_description', $up_array, 'update', "products_id = '" . (int)$rows['products_id'] . "' and language_id = '" . (int)$rows['language_id'] . "'");
}

//更新products_to_categories表
	$p_to_c_h_sql = mysql_query('SELECT products_id FROM `products_to_categories` ORDER BY `products_id` DESC limit 1');
	$p_to_c_h_row = mysql_fetch_array($p_to_c_h_sql);
	if((int)$p_to_c_h_row['products_id']){
		$p_to_c_prod_sql = tep_db_query('SELECT * FROM `products_to_categories` WHERE products_id > '.(int)$p_to_c_h_row['products_id'] );
		$prod_array=array();
		while($p_to_c_prod_rows = tep_db_fetch_array($p_to_c_prod_sql)){
			$prod_array[]=array('products_id'=> $p_to_c_prod_rows['products_id'], 'categories_id'=>$p_to_c_prod_rows['categories_id']);
		}
	}
	//清空
	tep_db_query('TRUNCATE TABLE `products_to_categories` ');
	
	//写新数据
	$w_pto_sql = mysql_query('SELECT * FROM `products_to_categories` ');
	while($w_pto_rows=mysql_fetch_array($w_pto_sql)){
		tep_db_query('INSERT INTO `products_to_categories` ( `products_id` , `categories_id` )VALUES ("'.$w_pto_rows['products_id'].'", "'.$w_pto_rows['categories_id'].'");');
	}
	for($i=0; $i<count($prod_array);$i++){
		$duplicate_check_query = tep_db_query("select count(*) as total from `products_to_categories` where products_id = '" . (int)$prod_array[$i]['products_id'] . "' and categories_id = '" . (int)$prod_array[$i]['categories_id'] . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1)tep_db_query('INSERT INTO `products_to_categories` ( `products_id` , `categories_id` )VALUES ("'.$prod_array[$i]['products_id'].'", "'.$prod_array[$i]['categories_id'].'");');
	}

echo "up OK.";
mysql_close($link_howard_db);
?>
