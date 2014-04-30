<?php
  require('includes/application_top.php');
  
  require(DIR_FS_LANGUAGES . $language . '/i_reply_bbs.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
$breadcrumb->add(db_to_html('我的走四方'),tep_href_link(FILENAME_ACCOUNT,'','SSL'));
  $breadcrumb->add(MY_TRAVEL_COMPANION, tep_href_link('i_sent_bbs.php', '', 'SSL'));
  $breadcrumb->add(I_REPLY_TRAVEL_COMPANION_BBS, tep_href_link('i_reply_bbs.php', '', 'SSL'));

//根据条件取得BBS回帖列表信息 start
$where='';
if(tep_not_null($_GET['tc_keyword'])){
	$where.= ' AND t_c_reply_content Like "%'.tep_db_input(trim(html_to_db($_GET['tc_keyword']))).'%" ';
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

$sql_str = 'SELECT * FROM `travel_companion_reply` WHERE customers_id ="'.(int)$customer_id.'" '.$where.' ORDER BY '.$order_by.' '.$a_d.' ';
$travel_split = new splitPageResults($sql_str, 20);
$rows_count = $travel_split->number_of_rows;	//记录总数

$rows_count_html_code = $travel_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);	//记录总数的信息页面
$rows_page_links_code = $travel_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); //翻页连接代码
$rows_count_pages = $travel_split->number_of_pages; //总页数


$travel_query = tep_db_query($travel_split->sql_query);
$dates=array();
while ($travel = tep_db_fetch_array($travel_query)) {
	$dates[] = array('id'=>$travel['t_c_reply_id'],
					 't_companion_id'=>$travel['t_companion_id'],
					 'parent_id'=>$travel['parent_id'],
					 'parent_type'=>$travel['parent_type'],
					 'name'=>$travel['customers_name'],
					 'customers_id'=>$travel['customers_id'],
					 'gender'=>$travel['gender'],
					 'customers_phone'=>$travel['customers_phone'],
					 'content'=>$travel['t_c_reply_content'],
					 'time'=>$travel['last_time']
					 );
}
//根据条件取得BBS回帖列表信息 end

  $validation_include_js = 'true';
  
  $content = 'i_reply_bbs';
  $other_css_base_name = 'bbs_travel_companion';
  $javascript = 'i_reply_bbs.js.php';
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>