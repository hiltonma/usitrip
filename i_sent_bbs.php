<?php
  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $breadcrumb->add(MY_TRAVEL_COMPANION, tep_href_link('i_sent_bbs.php', '', 'SSL'));
  $breadcrumb->add(I_SENT_TRAVEL_COMPANION_BBS, tep_href_link('i_sent_bbs.php', '', 'SSL'));

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

$sql_str = 'SELECT * FROM `travel_companion` WHERE customers_id ="'.(int)$customer_id.'" '.$where.' ORDER BY `bbs_type` ,'.$order_by.' '.$a_d.' ';
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
					 'time'=>$travel['last_time']
					 );
}
//根据条件取得BBS列表信息 end
  
  $validation_include_js = 'true';
  
  $content = 'i_sent_bbs';
  $other_css_base_name = 'bbs_travel_companion';
  $javascript = 'i_sent_bbs.js.php';
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>