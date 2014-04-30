<?php
require('includes/application_top.php');

//追加导航
//$breadcrumb->add(db_to_html('美国旅游须知'), 'index.php');

//由否有usa_tours_info_id来决定是显示目录还是详细内容。
if((int)$_GET['usa_tours_info_id']){
	$info_sql = tep_db_query('SELECT * FROM `usa_tours_info` uti, `usa_tours_info_to_type` utt, `usa_tours_info_type` utit WHERE uti.usa_tours_info_id='.(int)$_GET['usa_tours_info_id'].' AND uti.usa_tours_info_id=utt.usa_tours_info_id AND utt.usa_tours_info_type_id=utt.usa_tours_info_type_id AND utit.usa_tours_info_type_id=utt.usa_tours_info_type_id Group By utt.usa_tours_info_id limit 1 ');
	$info_rows = tep_db_fetch_array($info_sql);
	if(!(int)$info_rows['usa_tours_info_id']){
		tep_redirect(tep_href_link('index.php'));
	}
	
	//seo信息
	$the_desc = (tep_not_null($info_rows['meta_description'])) 
					? strip_tags($info_rows['meta_description'])
					: strip_tags($info_rows['usa_tours_info_description']);
	$the_key_words = (tep_not_null($info_rows['meta_keywords'])) 
						? strip_tags($info_rows['meta_keywords'])
						: strip_tags($info_rows['usa_tours_info_title']);
	$the_title = (tep_not_null($info_rows['meta_title'])) 
					? strip_tags($info_rows['meta_title'])
					: strip_tags($info_rows['usa_tours_info_title']);
	$the_desc = db_to_html($the_desc);
	$the_key_words = db_to_html($the_key_words);
	$the_title = db_to_html($the_title);
	//seo信息 end

	$breadcrumb->add((db_to_html(tep_db_output($info_rows['usa_tours_info_type_name']))), tep_href_link('usa-tours-info.php','usa_tours_info_type_id='.(int)$info_rows['usa_tours_info_type_id']));
	$breadcrumb->add((db_to_html(tep_db_output($info_rows['usa_tours_info_title']))), tep_href_link('usa-tours-info.php','usa_tours_info_id='.(int)$info_rows['usa_tours_info_id']));


}elseif((int)$_GET['usa_tours_info_type_id']){
	
	$tables = ' `usa_tours_info` uti, `usa_tours_info_to_type` utt, `usa_tours_info_type` utit ';
	$where =' WHERE utit.usa_tours_info_type_id='.(int)$_GET['usa_tours_info_type_id'].' AND uti.usa_tours_info_id=utt.usa_tours_info_id AND utt.usa_tours_info_type_id=utt.usa_tours_info_type_id AND utit.usa_tours_info_type_id=utt.usa_tours_info_type_id  ';
	$group_by = ' Group By utt.usa_tours_info_id ';
	
	$where_exc ='';
	
	//列目录
	//分页类
	$pageNum_String='page';
	$tours_info_maxRows = 20;
	$now_page=max(1,$_GET[$pageNum_String]);
	
	$page_split = new set_pagination($tables, $where, $now_page, $pageNum_String, 'form_page','6','12px','1',$tours_info_maxRows );
	$page_split_display = $page_split -> pagination();
	$tours_info_totalRows = $page_split -> totalRows;
	$tours_info_startRows = max(0,($page_split -> startRow));
	$tours_info_totalPages = $page_split -> totalPages;
	$tours_info_now_page = $page_split -> now_page;
	//起始记录数
	$LIMIT ='';
	if($tours_info_totalRows){
		$LIMIT = ' limit '.$tours_info_startRows.' , '.$tours_info_maxRows;
	}
	
	$info_sql = tep_db_query('SELECT * FROM '.$tables.$where.$where_exc.$group_by.' ORDER BY uti.usa_tours_info_id ASC '.$LIMIT);
	$info_rows = tep_db_fetch_array($info_sql);
	if(!(int)$info_rows['usa_tours_info_type_id']){
		tep_redirect(tep_href_link('index.php'));
	}
	
	$breadcrumb->add((db_to_html(tep_db_output($info_rows['usa_tours_info_type_name']))), tep_href_link('usa-tours-info.php','usa_tours_info_type_id='.(int)$info_rows['usa_tours_info_type_id']));
	
}else{
	tep_redirect(tep_href_link('index.php'));
}

$content = 'usa-tours-info';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>