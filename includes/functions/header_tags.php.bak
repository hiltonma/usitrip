<?php
// /catalog/includes/functions/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.0

////
// Get products_head_title_tag
// TABLES: products_description
function tep_get_header_tag_products_title($product_id) {
  global $languages_id, $HTTP_GET_VARS;

  $product_header_tags = tep_db_query("select products_head_title_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
  $product_header_tags_values = tep_db_fetch_array($product_header_tags);

  return clean_html_comments($product_header_tags_values['products_head_title_tag']);
  }


////
// Get products_head_keywords_tag
// TABLES: products_description
function tep_get_header_tag_products_keywords($product_id) {
  global $languages_id, $HTTP_GET_VARS;

  $product_header_tags = tep_db_query("select products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
  $product_header_tags_values = tep_db_fetch_array($product_header_tags);

  return $product_header_tags_values['products_head_keywords_tag'];
  }


////
// Get products_head_desc_tag
// TABLES: products_description
function tep_get_header_tag_products_desc($product_id) {
  global $languages_id, $HTTP_GET_VARS;

  $product_header_tags = tep_db_query("select products_head_desc_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
  $product_header_tags_values = tep_db_fetch_array($product_header_tags);

  return $product_header_tags_values['products_head_desc_tag'];
  }

///howard added for Categories Pages, return false or an Array
function tep_get_categories_header_tags_array($categories_id, $tab_tag){
	//echo $categories_id.'||'. $tab_tag;	
	global $languages_id;
	if(!tep_not_null($tab_tag)){ return false; }
	
	if($tab_tag=="tours" ){
		$sql = tep_db_query("select c.categories_id, cd.categories_head_desc_tag AS meta_description, cd.categories_head_title_tag AS meta_title, cd.categories_head_keywords_tag AS meta_keywords FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $categories_id . "' and cd.categories_id = c.categories_id and cd.language_id = '" . $languages_id . "' limit 1 ");
		//$row = MCache::fetch_categories($categories_id);
		$row = tep_db_fetch_array($sql);
	}else{
		$sql = tep_db_query('SELECT categories_id, meta_title, meta_keywords, meta_description FROM `categories_meta_tags` WHERE categories_id="'.(int)$categories_id.'" and tab_tag ="'.tep_db_prepare_input($tab_tag).'" limit 1 ');
		$row = tep_db_fetch_array($sql);
	}	
	if((int)$row['categories_id'] && (tep_not_null($row['meta_title']) && tep_not_null($row['meta_keywords']) && tep_not_null($row['meta_description']) )){
		$Array = array();
		$Array['meta_title'] = $row['meta_title'];
		$Array['meta_keywords'] = $row['meta_keywords'];
		$Array['meta_description'] = $row['meta_description'];
		return $Array;
	}else{	/* 如果后台没有设置Tab Meta信息的时候,自动使用模板（分类名+固定文字），如果后台设置，则使用设置的信息*/
		switch($tab_tag){
			case "introduction":
				$title_str = '%s景点,%s旅游景点介绍 - 走四方网';
				$description_str = '%s景点介绍,走四方网%s旅游景点介绍,查看%s夷有哪些旅游景点';
				$keywords_str = '%s景点,%s旅游景点';
			break;
			case "vcpackages":
				$title_str = '%s旅游,%s度假,%s旅游度假行程线路旅游团预订 - 走四方网';
				$description_str = '%s旅游,%s度假行程线路旅游团,价格,费用信息,走四方网为你提供%s旅游度假行程旅游团在线预订的专业服务';
				$keywords_str = '%s旅游,%s度假';
			break;
			case "recommended":
				$title_str = '%s旅游行程线路推荐,%s旅游团推荐 - 走四方网';
				$description_str = '%s旅游行程线路推荐,%s旅游团推荐,走四方网推荐的去%s旅游线路,旅游团价格,费用信息并提供所推荐的%s旅游在线预订的专业服务';
				$keywords_str = '%s旅游推荐';
			
			break;
			case "special":
				$title_str = '特价%s旅游行程线路, 特价%s旅游团在线预订 - 走四方网';
				$description_str = '特价%s旅游行程线路,%s特价旅游团,特价去%s旅游线路,旅游团价格,费用信息并在线预订的专业服务';
				$keywords_str = '%s旅游特价';
			
			break;
			case "diy": 
				$title_str = '%s自助游,%s自助旅游在线预订 - 走四方网';
				$description_str = '%s自助游,%s自助旅游,特价去%s自助游线路,旅游团价格,费用信息并在线预订的专业服务';
				$keywords_str = '%s自助游';
			break;
			default:
				$title_str = '%s旅游,去%s旅游价格_%s旅游行程线路旅游团在线预订 - 走四方网';
				$description_str = ' %s旅游,去%s旅游价格,费用,旅游团行程线路信息,走四方网为你提供%s旅游行程旅游团在线预订的专业服务';
				$keywords_str = '%s旅游,去%s旅游';
			break;
		}
		$categories_name = tep_get_categories_name($categories_id);
		$categories_name = preg_replace('/ .+/','',$categories_name);
		$categories_name = str_replace('旅游','',$categories_name);
		$Array = array();
		$Array['meta_title'] = str_replace('%s',$categories_name, $title_str);
		$Array['meta_keywords'] = str_replace('%s',$categories_name, $keywords_str);
		$Array['meta_description'] = str_replace('%s',$categories_name, $description_str);
		return $Array;
	}
	return false;
}
?>
