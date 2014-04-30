<?php
require('includes/application_top.php');
require('includes/classes/index.php');
require('admin/includes/classes/T.class.php');
require('admin/includes/classes/Raiders.class.php');
require('admin/includes/classes/RaidersTags.class.php');
require('admin/includes/classes/RaidersCatalog.class.php');
$rc=new RaidersCatalog;
$r=new Raiders();
$parent_id_tmp=$rc->getInfoFromParentId(0);
$parent_id=isset($_GET['parent_id'])?(int)$_GET['parent_id']:$parent_id_tmp[0]['type_id'];
$parent_id=$parent_id?$parent_id:0;
$type_id=isset($_GET['type_id'])?(int)$_GET['type_id']:0;
$type_info=$rc->getIndexInfo($parent_id);
$type_str=$rc->createAllTypeByParentId($parent_id);
$type_name=$rc->getTypeName($type_id,$parent_id);
$parent_name=$rc->getTypeName($parent_id,0);
$list_info=$r->getIndexList($type_str,$type_id);
$best_sell=Index::best_sellers();//热销
$content = 'raiders_list';
$breadcrumb->add(db_to_html($parent_name), tep_href_link('raiders_list.php', 'parent_id='.$parent_id));
if($type_id)
$breadcrumb->add(db_to_html($type_name), tep_href_link('raiders_list.php', 'parent_id='.$parent_id.'&type_id='.$type_id));
$the_title = db_to_html('美国旅游攻略_美国旅游网_美国自助游攻略_Usitrip走四方旅游网');
$the_desc = db_to_html('走四方美国华人旅行社为您提供美国旅游攻略资讯,让你全方位的了解美国旅游购物、美食、交通、住宿等信息');
$the_key_words = db_to_html('美国旅游景点,美国自助游,美国旅游购物,美国旅游注意事项');
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php'); 
?>