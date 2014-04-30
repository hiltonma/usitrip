<?php
require_once('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/bbs_travel_companion.php');

//如果有搜索行为是否包括目录的选项
$action_search_include = true;
//根据条件取得BBS列表信息 start
$where='';
if(tep_not_null($_GET['tc_keyword'])){
	$where.= ' AND t_companion_title Like "%'.tep_db_input(trim(html_to_db($_GET['tc_keyword']))).'%" ';
	$action_search_include = false;
}
if(tep_not_null($_GET['search_date'])){
	$where.= ' AND (hope_departure_date ="'.tep_db_input(trim(html_to_db($_GET['search_date']))).'" ';
	if((int)$_GET['date_step']>0){
		$search_date_num = strtotime($_GET['search_date']);
		$search_date_num_add = $search_date_num + ((int)$_GET['date_step']*24*60*60);
		$search_date_num_sub = $search_date_num - ((int)$_GET['date_step']*24*60*60);
		$add_final_date = date('Y-m-d', $search_date_num_add);
		$sub_final_date = date('Y-m-d', $search_date_num_sub);
		$where.= ' || (hope_departure_date < "'.$add_final_date.'" AND hope_departure_date > "'.$sub_final_date.'") ';
	}
	$where.= ' ) ';
	$action_search_include = false;
}

//取得当前目录的所有下级目录
if($Tccurrent_category_id>0 && $action_search_include == true){
	$cate_string = $Tccurrent_category_id;
	$child_categories_array=array();
	$child_categories_array = tep_get_categories('', $Tccurrent_category_id);
	for($i=0; $i<count($child_categories_array); $i++ ){
		$cate_string.= ','.$child_categories_array[$i]['id'];
	}
	
	//echo $cate_string;
	$where.= ' AND categories_id in('.$cate_string.') ';
}

//搜索某人的所有帖子
if((int)$_GET['customers_id']){
	$where = ' AND customers_id ="'.(int)$_GET['customers_id'].'" ';
}
//搜索某产品的所有帖子
if((int)$_GET['products_id']){
	$products_id = (int)$_GET['products_id'];
	$where = ' AND products_id ="'.(int)$_GET['products_id'].'" ';
}



$order_by = ' `last_time` ';
$a_d = ' DESC ';
$new_tag_class = 's';
$hit_tag_class = '';
switch($_GET['sort_name']){
	case 'new': $new_tag_class = 's'; $hit_tag_class = '';  $order_by = ' `last_time` '; 
	break;
	case 'hit': $new_tag_class = ''; $hit_tag_class = 's';  $order_by = ' `click_num` '; 
	break;
	case 'reply_num' : $new_tag_class = ''; $hit_tag_class = '';  $order_by = ' `reply_num` '; 
}

$sql_str = 'SELECT * FROM `travel_companion` WHERE `status`="1" '.$where.' ORDER BY `bbs_type` ,'.$order_by.' '.$a_d.' ';
$travel_split = new splitPageResults($sql_str, 20);
$rows_count = $travel_split->number_of_rows;	//记录总数

$rows_count_html_code = $travel_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);	//记录总数的信息页面
$rows_page_links_code = $travel_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); //翻页连接代码
$rows_count_pages = $travel_split->number_of_pages; //总页数


$travel_query = tep_db_query($travel_split->sql_query);
$dates=array();
while ($travel = tep_db_fetch_array($travel_query)) {
	$dates[] = array('id'=>$travel['t_companion_id'], 
					 'title'=>$travel['t_companion_title'],
					 'name'=>$travel['customers_name'],
					 'customers_id'=>$travel['customers_id'],
					 'gender'=>$travel['t_gender'],
					 'reply'=>$travel['reply_num'],
					 'click'=>$travel['click_num'],
					 'products_id'=>$travel['products_id'],
					 'categories_id'=>$travel['categories_id'],
					 'type'=>$travel['bbs_type'],
					 'time'=>$travel['last_time'],
					 'admin_id'=>$travel['admin_id']
					 );
}
//根据条件取得BBS列表信息 end

//Writing view history start
if((int)$rows_count_pages && count($TcPath_array) && tep_not_null($TcPath)){
	$tmp_var = false;
	$i = (int)count($_COOKIE['view_history_bbs']);
	for($j=0; $j<$i; $j++){
		if($_COOKIE['view_history_bbs'][$j]['TcPath'] == $TcPath){
			setcookie('view_history_bbs['.$j.'][TcPath]', $TcPath, time() +(3600*24*30*365));
			setcookie('view_history_bbs['.$j.'][date_time]', date('YmdHis'), time() +(3600*24*30*365));
			$tmp_var = true;
		}
	}
	
	if($tmp_var == false){
		setcookie('view_history_bbs['.$i.'][TcPath]', $TcPath, time() +(3600*24*30*365));
		setcookie('view_history_bbs['.$i.'][date_time]', date('YmdHis'), time() +(3600*24*30*365));
	}
}
//Writing view history end

	$output_title = db_to_html('走四方网 | ');
	$output_desc = db_to_html('走四方网,');
	$output_key_words = db_to_html('走四方网,');
	
	for ($jib=0, $jnb=sizeof($breadcrumb->_trail); $jib<$jnb; $jib++) {
			if(($jnb-1) != $jib){							
				$output_title .= trim($breadcrumb->_trail[$jib]['title']) . '  &gt;  ';	
				$output_desc .=	trim($breadcrumb->_trail[$jib]['title']). ',';	
				$output_key_words .= trim($breadcrumb->_trail[$jib]['title']). ',';	
			}else{
				$output_title .= trim($breadcrumb->_trail[$jib]['title']);
				$output_desc .=	trim($breadcrumb->_trail[$jib]['title']);	
				$output_key_words .= trim($breadcrumb->_trail[$jib]['title']);	
			}
	}

	$seo_sql = tep_db_query('SELECT * FROM `travel_companion_seo_tags` WHERE categories_id="'.$Tccurrent_category_id.'" LIMIT 1');
	$seo_row = tep_db_fetch_array($seo_sql);
	if(tep_not_null($seo_row['meta_title']) && tep_not_null($seo_row['meta_keywords']) && tep_not_null($seo_row['meta_description']) ){
		$output_title = db_to_html(tep_db_output($seo_row['meta_title']));
		$output_desc = db_to_html(tep_db_output($seo_row['meta_description']));
		$output_key_words = db_to_html(tep_db_output($seo_row['meta_keywords']));
		
	}
	//seo信息
	$the_title = $output_title;
	$the_desc = $output_desc;
	$the_key_words = $output_key_words;
	//seo信息 end


$javascript = 'bbs_travel_companion.js.php';
$content = 'bbs_travel_companion_rightindex';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/main_page_for_bbs.tpl.php');
?>

<?php
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>