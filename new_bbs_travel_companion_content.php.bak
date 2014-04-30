<?php
require_once('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/bbs_travel_companion.php');
function getPlace($str){
	if($str){
		$arr=explode(',', $str);
		$str_sql='select city from tour_city where city_id in('.$str.')';
		$sql_query=tep_db_query($str_sql);
		while($row=tep_db_fetch_array($sql_query)){
			$return.=$row['city'].'   ';
		}
		return $return;
	}else return '';
}
$t_companion_id = isset($_POST['t_companion_id']) ? $_POST['t_companion_id'] : $_GET['t_companion_id']; 

//根据条件取得BBS信息 start
$sql = tep_db_query('SELECT * FROM `travel_companion` WHERE t_companion_id='.(int)$t_companion_id.' AND `status`="1" ');
$rows = tep_db_fetch_array($sql);
if(!(int)$rows['t_companion_id']){
	tep_redirect(tep_href_link('new_travel_companion_index.php'));
}
$for_seo_key_content = $rows['t_companion_title'].$rows['customers_name'].$rows['t_companion_content'];
//根据条件取得BBS信息 end

//判断是否为旧版的帖子
$is_old_version = false;
if($rows['bbs_type']!="1" && $rows['hope_people_man']=="0" && $rows['hope_people_woman']=="0"){
	$is_old_version = true;
}

//判断名额是否已满
$has_filled = false;
$apped_num = tep_count_travel_companion_app_num($rows['t_companion_id']);
$hope_pep_num = (int)$rows['hope_people_man']+(int)$rows['hope_people_woman']+(int)$rows['hope_people_child'];
if($apped_num >= $hope_pep_num && !(int)$rows['open_ended']){
	$has_filled = true;
}

//更新点击量
$click_num = tep_rand(1,3);
tep_db_query('update travel_companion set click_num=(click_num+'.$click_num.') WHERE t_companion_id="'.(int)$rows['t_companion_id'].'" ' );

$old_content = '';
if((int)$rows['products_id']){	//所属产品名称
	$p_name = tep_db_output(strip_tags($rows['t_companion_content']));
	if($is_old_version == true){
		$p_name = tep_get_products_name((int)$rows['products_id'],'',true);
		$old_content = tep_db_output(strip_tags($rows['t_companion_content']));
	}
	$p_model = tep_get_products_model((int)$rows['products_id']);
}else{
	$p_name = tep_db_output(strip_tags($rows['t_companion_content']));
}

//取得bbs 跟贴列表 start
$reply_where_exc = ' AND `status`="1" ';
if((int)$customer_id){
	$reply_where_exc = ' AND (`status`="1" || customers_id="'.(int)$customer_id.'")';
}
$reply_sql_string = 'SELECT * FROM `travel_companion_reply` WHERE t_companion_id="'.(int)$rows['t_companion_id'].'" '.$reply_where_exc.' ORDER BY  t_c_reply_id ';
$row_max = TRAVEL_LIST_MAX_ROW;
$row_max = 10;	//每页显示几行
$reply_split = new splitPageResults($reply_sql_string, $row_max);
$rows_count = $reply_split->number_of_rows;	//记录总数
$rows_count_html_code = $reply_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);	//记录总数的信息页面
$rows_count_pages = $reply_split->number_of_pages; //总页数
$current_page = $reply_split->current_page_number; //当前页号
$rows_page_links_code = $reply_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); //翻页连接代码


//确定当前路径
$categories_id = (int)$rows['categories_id'];
$TcPath = tep_get_category_patch($categories_id);

$reply_query = tep_db_query($reply_split->sql_query);
$dates=array();
while ($reply = tep_db_fetch_array($reply_query)) {
	$dates[] = array('id'=>$reply['t_c_reply_id'], 
					 'name'=>$reply['customers_name'],
					 'customers_id'=>$reply['customers_id'],
					 'gender'=>$reply['gender'],
					 'email'=>$reply['email_address'],
					 'content'=>$reply['t_c_reply_content'],
					 'products_id'=>$reply['products_id'],
					 'time'=>$reply['add_time'],
					 'parent_id'=>$reply['parent_id'],
					 'parent_type' =>$reply['parent_type'],
					 'only_top_can_see' => $reply['only_top_can_see'],
					 'status' => $reply['status']
					 );
	$for_seo_key_content .= $reply['customers_name'].$reply['t_c_reply_content'];
}

//取得bbs 跟贴列表 end


	$tc_title = db_to_html(tep_db_output($rows['t_companion_title']));
	$output_title = $tc_title.','.$output_title;
	$output_desc = $tc_title.','.$output_desc;
	$output_key_words = $tc_title.','.$output_key_words;

	$use_long_title = false;	//是否使用长目录作为Seo内容
	if($use_long_title == true){
		for ($jib=0, $jnb=sizeof($breadcrumb->_trail); $jib<$jnb; $jib++) {
			if(($jnb-1) != $jib){							
				$output_title .= trim($breadcrumb->_trail[$jib]['title']) . ',';	
				$output_desc .=	trim($breadcrumb->_trail[$jib]['title']). ',';	
				$output_key_words .= trim($breadcrumb->_trail[$jib]['title']). ',';	
			}else{
				$output_title .= trim($breadcrumb->_trail[$jib]['title']);
				$output_desc .=	trim($breadcrumb->_trail[$jib]['title']);	
				$output_key_words .= trim($breadcrumb->_trail[$jib]['title']);	
			}
		}
	}else{
		$jib = $jnb=sizeof($breadcrumb->_trail)-1;
		$output_title .= trim($breadcrumb->_trail[$jib]['title']);
		$output_desc .=	trim($breadcrumb->_trail[$jib]['title']);	
		$output_key_words .= trim($breadcrumb->_trail[$jib]['title']);	
	}
	
	//补充关键词信息
	$pat_content = strip_tags($for_seo_key_content);
	$add_key = tep_add_meta_keywords_from_thesaurus($pat_content, 2);
	if(is_array($add_key) && count($add_key)>0){
		$output_key_words .= ','.db_to_html(implode(',',$add_key));
	}
	
	$output_title .= db_to_html('-结伴同游-走四方网');
	$output_desc .= ','.cutword(db_to_html(tep_db_output($rows['t_companion_content'])),97,'...');
	//$output_key_words .= '-'.db_to_html('走四方网');
	
	//seo信息
	$the_title = $output_title;
	$the_desc = $output_desc;
	$the_key_words = $output_key_words;
	//seo信息 end

$is_travel_companion_bbs = true;
$other_css_base_name = "new_travel_companion_index";
$javascript = 'new_travel_companion.js.php';
$content = 'new_bbs_travel_companion_content';

$js_get_parameters[] = 't_companion_id='.(int)$t_companion_id;
$js_get_parameters[] = 'content='.$content;



$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));
$breadcrumb->add(db_to_html(tep_db_output($rows['t_companion_title'])), tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.$t_companion_id));


require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
?>

<?php
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>