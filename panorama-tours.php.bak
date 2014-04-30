<?php
require_once('includes/application_top.php');

if(!tep_not_null($cat_and_subcate_ids)){
	$cat_and_subcate_ids = tep_get_category_subcategories_ids($current_category_id);
}


//$current_category_id=='134';
$cat_sql = tep_db_query('SELECT * FROM `categories` c,`categories_description` cd WHERE c.categories_id=cd.categories_id AND c.categories_id="'.$current_category_id.'" LIMIT 1');
$cat_row = tep_db_fetch_array($cat_sql);

//产品列表
$listing_sql = 'SELECT * FROM `products` p, `products_description` pd, `products_to_categories` ptc WHERE p.products_id=pd.products_id AND ptc.products_id=p.products_id AND (ptc.categories_id in ('.$cat_and_subcate_ids.') ) AND p.products_status=1';


$content = 'panorama-tours';
require_once(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>