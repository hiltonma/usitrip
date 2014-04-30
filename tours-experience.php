<?php

  require('includes/application_top.php');
 
 //追加导航
$breadcrumb->add(db_to_html('美国旅游建议'), tep_href_link('tours-experience.php'));

if((int)$_GET['tours_experience_categories_id']){
	$tables = ' `tours_experience` as te, `tours_experience_to_categories` as tetc ';
	$where =' WHERE te.tours_experience_status=1 AND tetc.tours_experience_categories_id= '.
	(int)$_GET['tours_experience_categories_id'].' AND te.tours_experience_id=tetc.tours_experience_id ';
	$group_by = ' Group By te.tours_experience_id ';
}else{
	$tables = ' `tours_experience` as te ';
	$where =' WHERE te.tours_experience_status=1 ';
	$group_by = ' ';
}

$where_exc ='';
if((int)$_GET['tours_experience_id']){
	$where_exc .=' AND te.tours_experience_id='.(int)$_GET['tours_experience_id'].' ';
}
if(tep_not_null($_GET['experience_keyword'])){
	$where_exc .=' AND te.tours_experience_title Like binary "%'.html_to_db(tep_db_prepare_input($_GET['experience_keyword'])).'%" ';
}
$where.=$where_exc;

//列目录
//分页类
$pageNum_String='page';
$tours_experience_maxRows = 10;
$now_page=max(1,$_GET[$pageNum_String]);

$page_split = new set_pagination($tables, $where, $now_page, $pageNum_String, 'form_page','6','12px','1',$tours_experience_maxRows );
$page_split_display = $page_split -> pagination();
$tours_experience_totalRows = $page_split -> totalRows;
$tours_experience_startRows = max(0,($page_split -> startRow));
$tours_experience_totalPages = $page_split -> totalPages;
$tours_experience_now_page = $page_split -> now_page;
//起始记录数
$LIMIT ='';
if($tours_experience_totalRows){
	$LIMIT = ' limit '.$tours_experience_startRows.' , '.$tours_experience_maxRows;
}

$experience_sql = tep_db_query('SELECT * FROM '.$tables.$where.$where_exc.$group_by.' ORDER BY te.tours_experience_update_time DESC '.$LIMIT);
$experience_rows = tep_db_fetch_array($experience_sql);

if((int)$_GET['tours_experience_id']){
	$breadcrumb->add(db_to_html($experience_rows['tours_experience_title']),'');
	
	//seo信息
	$the_desc = (tep_not_null($experience_rows['meta_description'])) 
					? strip_tags($experience_rows['meta_description'])
					: strip_tags($experience_rows['tours_experience_content']);
	$the_key_words = (tep_not_null($experience_rows['meta_keywords'])) 
						? strip_tags($experience_rows['meta_keywords'])
						: strip_tags($experience_rows['tours_experience_title']);
	$the_title = (tep_not_null($experience_rows['meta_title'])) 
					? strip_tags($experience_rows['meta_title'])
					: strip_tags($experience_rows['tours_experience_title']);
	$the_desc = db_to_html($the_desc);
	$the_key_words = db_to_html($the_key_words);
	$the_title = db_to_html($the_title);
	//seo信息 end
}
  $content = 'tours-experience';


 require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

 require(DIR_FS_INCLUDES . 'application_bottom.php');
?>