<?php
require('includes/application_top.php');
//权限判定
$order_dispaly_status_control_access =$login_groups_id == '1' ? true:false;
$VIN_NOTICE = '';
$VIN_ERROR = '';
//权限判定结束
if(!defined('VIN_TMP_PATH'))define('VIN_TMP_PATH' , realpath('./templates/ver1/').DIRECTORY_SEPARATOR); //模板路径
//设置模板变量

//列印
if($_GET['action'] == 'history'){
	$sql = "SELECT *	 FROM  statistics_pageview_history WHERE url='".html_to_db(tep_db_prepare_input($_GET['url']))."' ORDER BY history_id DESC";
	$current_page = $_GET['page'];
	$max_rows_per_page =50;
	$pager = new splitPageResults($current_page,$max_rows_per_page,$sql,$query_num_rows);
	$pageTotal = ceil($query_num_rows/$max_rows_per_page );
	$listQuery= tep_db_query($sql);
	$records = array();
	while ($row = tep_db_fetch_array($listQuery)) {	
		$records[] = $row;
	}
	if(empty($records)){	$VIN_ERROR = db_to_html('该URL还没有访问记录');}
	$get = $_GET;
	unset($get['page']);
	$split_left_content = $pager->display_count($query_num_rows, $max_rows_per_page, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
	$split_right_content = $pager->display_links($query_num_rows, $max_rows_per_page, MAX_DISPLAY_PAGE_LINKS,intval($_GET['page']) , http_build_query($get ));
	$content = 'stats_history.php';
}elseif($_GET['action'] == 'reset'){
	$sql = "UPDATE   statistics_pageview_counter SET total = 0 WHERE counter_id =  ".intval($_GET['counter_id']);
	tep_db_query($sql);
	$VIN_NOTICE = db_to_html('计数已经请零,请<a href="'.tep_href_link('stats_pageview.php').'">返回列表页</a>');
	$content = 'stats_pageview.php';
}else{
	if($_GET['url'] != '') $cond = " url like '".html_to_db($_GET['url'])."%'";else $cond = ' 1 ';
	if($_GET['sort']== 'total'&& in_array($_GET['order'] ,array('desc','asc'))) $cond .= ' ORDER by total '.$_GET['order'];
	$sql = "SELECT *	 FROM  statistics_pageview_counter WHERE ".$cond;
	$current_page = $_GET['page'];
	$max_rows_per_page =50;
	$pager = new splitPageResults($current_page,$max_rows_per_page,$sql,$query_num_rows);
	$pageTotal = ceil($query_num_rows/$max_rows_per_page );
	$listQuery= tep_db_query($sql);
	$records = array();
	while ($row = tep_db_fetch_array($listQuery)) {	
		$records[] = $row;
	}
	if(empty($records)){	$VIN_ERROR = db_to_html('没有查到计数记录,请修改搜索条件进行查询');}
	$get = $_GET;
	unset($get['page']);
	$split_left_content = $pager->display_count($query_num_rows, $max_rows_per_page, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
	$split_right_content = $pager->display_links($query_num_rows, $max_rows_per_page, MAX_DISPLAY_PAGE_LINKS,intval($_GET['page']) , http_build_query($get ));
	$content = 'stats_pageview.php';
}

include(VIN_TMP_PATH.$content);
require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>