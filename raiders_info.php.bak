<?php
require('includes/application_top.php');
require('includes/classes/index.php');
require('admin/includes/classes/T.class.php');
require('admin/includes/classes/Raiders.class.php');
require('admin/includes/classes/RaidersTags.class.php');
require('admin/includes/classes/RaidersCatalog.class.php');
$rc=new RaidersCatalog;
$r=new Raiders();

$parent_id=isset($_GET['parent_id'])?(int)$_GET['parent_id']:0;
$type_id=isset($_GET['type_id'])?(int)$_GET['type_id']:0;
if(!$parent_id&&$type_id){
	$parent_id=$rc->getParentIdByType($type_id);
}
$article_id=(int)$_GET['article_id'];
if(!$article_id)
	$error404 = true;
$type_info=$rc->getIndexInfo($parent_id);
$type_name=$rc->getTypeName($type_id,$parent_id);
$parent_name=$rc->getTypeName($parent_id,0);

$parent_id_str=$rc->createAllTypeByParentId($parent_id);
$info=$r->getOneInfo($article_id, $type_id, $parent_id_str,$_GET['is_test']);//信息
$recommend=$r->recommend($article_id,RaidersTags::downTagsArray($info['tags']),$type_id);//推荐
$best_sell=Index::best_sellers();//热销
$content = 'raiders_info';
$breadcrumb->add(db_to_html($parent_name), tep_href_link('raiders_list.php', 'parent_id='.$parent_id));
if($type_id)
$breadcrumb->add(db_to_html($type_name), tep_href_link('raiders_list.php', 'parent_id='.$parent_id.'&type_id='.$type_id));
$breadcrumb->add(db_to_html($info['info']['article_title']), '');
$the_title = db_to_html($info['info']['article_title'].'_走四方旅游网');
$the_desc = db_to_html($info['info']['article_desc']);
$the_key_words = db_to_html($info['info']['article_key_words']);
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php'); 

?>