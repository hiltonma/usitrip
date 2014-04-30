<?php
/*
创建网站地图：
（1）访问此页面即自动创建网站地图文件sitemap.xml和big5sitemap.xml
*/
require('includes/application_top.php');
header("Content-type: application/xml; charset=utf-8");
$array = array();
//--------首页网址
$index_page = preg_replace('/\/$/','',HTTP_SERVER).'/';
$array[] = array('loc'=>$index_page, 'priority'=>"1");

$array_filer = array();

//--------所有产品和分类
$products_sql = tep_db_query('SELECT p.products_id, ptc.categories_id FROM `products` p, `products_to_categories` ptc WHERE p.products_status="1" AND p.products_id=ptc.products_id Order By ptc.categories_id DESC, p.products_id DESC ');
while($products = tep_db_fetch_array($products_sql)){
	$loc_href = tep_href_link(FILENAME_DEFAULT, 'cPath='.$products['categories_id'],'NONSSL',false);
	if(!in_array($loc_href,$array_filer) && tep_not_null($products['categories_id'])){	//--------过滤数组
		$array_filer[] = $loc_href;
		$array[] = array('loc'=>$loc_href, 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_DEFAULT, 'cPath='.$products['categories_id'].'&mnu=tours','NONSSL',false), 'priority'=>"0.7");
	}
	
	$loc_href = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'],'NONSSL',false);
	if(!in_array($loc_href,$array_filer) && (int)$products['products_id']){	//--------过滤数组
		$array_filer[] = $loc_href;
		$array[] = array('loc'=>$loc_href, 'priority'=>"0.6");
		//$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=qanda','NONSSL',false), 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=prices','NONSSL',false), 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=departure','NONSSL',false), 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=notes','NONSSL',false), 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=frequentlyqa','NONSSL',false), 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=reviews','NONSSL',false), 'priority'=>"0.6");
		$array[] = array('loc'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'].'&mnu=photos','NONSSL',false), 'priority'=>"0.6");
	}
}

//--------SEO首页、分类、文章
/*$array[] = array('loc'=>tep_href_link('seo_news.php'), 'priority'=>"0.6");
$seo_sql = tep_db_query('SELECT n.news_id, ntc.class_id FROM `seo_news` n , `seo_news_to_class` ntc WHERE n.news_id=ntc.news_id AND news_state="1" Order By ntc.class_id DESC, n.news_id DESC');
$array_filer = array();
while($seo_rows = tep_db_fetch_array($seo_sql)){
	$loc_href = tep_href_link('article_news_list.php','class_id='.(int)$seo_rows['class_id'],'NONSSL',false);
	if(!in_array($loc_href,$array_filer)){	//--------过滤数组
		$array_filer[] = $loc_href;
		$array[] = array('loc'=>$loc_href, 'priority'=>"0.6");
	}
	$loc_href = tep_href_link('article_news_content.php', 'news_id='.$seo_rows['news_id'],'NONSSL',false);
	if(!in_array($loc_href,$array_filer)){	//--------过滤数组
		$array_filer[] = $loc_href;
		$array[] = array('loc'=>$loc_href, 'priority'=>"0.6");
	}
}
*/
//--------结伴同游
$array[] = array('loc'=>tep_href_link('new_travel_companion_index.php'), 'priority'=>"0.6");
$travel_companion_sql = tep_db_query('SELECT t.t_companion_id FROM travel_companion t WHERE t.status="1" ORDER BY t.t_companion_id DESC ');
while($travel_companion = tep_db_fetch_array($travel_companion_sql)){
	$array[] = array('loc'=>tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.$travel_companion['t_companion_id'],'NONSSL',false), 'priority'=>"0.6");
}


$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
$sizeof = sizeof($array);
for($i=0; $i<$sizeof; $i++){
	if($index_page!=$array[$i]['loc'] || $i==0 ){
		$xml .= '<url>';
		$xml .= '<loc>'.$array[$i]['loc'].'</loc>';
		$xml .= '<priority>'.$array[$i]['priority']./*':'.$sizeof.*/'</priority>';
		$xml .= '</url>';
	}
}
//攻略文章
require('admin/includes/classes/T.class.php');
require('admin/includes/classes/Raiders.class.php');
$r=new Raiders();
$xml.=$r->getMap();
//攻略文章
$xml .= '</urlset>';
echo $xml;

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>