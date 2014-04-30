<?php
//相片或游记列表
require('includes/application_top.php');

$photo_books_id = (int)$_GET['photo_books_id'];


//更新相册点击量
tep_db_query("UPDATE `photo_books` SET `books_hot` =(`books_hot`+1) WHERE `photo_books_id` = '".$photo_books_id."' LIMIT 1 ;");
//取得相册
$books_sql = tep_db_query('SELECT photo_books_id, photo_books_name, customers_id  FROM `photo_books` WHERE photo_books_id="'.$photo_books_id.'" ');
$books = tep_db_fetch_array($books_sql);
if(!(int)$books['photo_books_id']){
	die(db_to_html("相册不存在"));
}

//相册名称
$books_name = db_to_html(tep_db_output($books['photo_books_name']));

//取得相册的5张相片
$where_exc = "";
if((int)$_GET['photo_id']){
	$photo_id5 = array();
	$photo_id5[] = (int)$_GET['photo_id'];
	$tmp_sql = tep_db_query('SELECT photo_id FROM `photo` WHERE photo_books_id="'.$photo_books_id.'" and photo_id < "'.(int)$_GET['photo_id'].'" Order By photo_id DESC Limit 2');
	while($tmp_rows = tep_db_fetch_array($tmp_sql)){
		$photo_id5[] = $tmp_rows['photo_id'];
	}
	$tmp1_sql = tep_db_query('SELECT photo_id FROM `photo` WHERE photo_books_id="'.$photo_books_id.'" and photo_id > "'.(int)$_GET['photo_id'].'" Order By photo_id ASC Limit 5');
	while($tmp1_rows = tep_db_fetch_array($tmp1_sql)){
		if(count($photo_id5)<5){
			$photo_id5[] = $tmp1_rows['photo_id'];
		}
	}
	
	$where_exc = " and photo_id in(".implode(',',$photo_id5).") ";
	
	$photo_id = (int)$_GET['photo_id'];
}

$photo_sql = tep_db_query('SELECT * FROM `photo` WHERE photo_books_id="'.$photo_books_id.'" '.$where_exc.' Order By photo_id Limit 5');

$photos = array();
$i_now = 0;
while($photo_list = tep_db_fetch_array($photo_sql)){
	$photos[] = array('id'=>$photo_list['photo_id'],'title'=>db_to_html($photo_list['photo_title']),'name'=>$photo_list['photo_name'], 'content'=> db_to_html($photo_list['photo_content']), 'customers_id'=> $photo_list['customers_id'], 'date'=> $photo_list['photo_update']);
	if(!(int)$photo_id){ $photo_id = $photo_list['photo_id']; }
	if($photo_id == $photo_list['photo_id']){
		$i_now = max(0,(count($photos)-1));
	}
}

//找出前一个、后一个相片ID
$bakc_i = ($i_now-1);
$next_i = ($i_now+1);
$back_photo_id = $photos[$bakc_i]['id'];
$next_photo_id = $photos[$next_i]['id'];

//网友评论
$comments_sql = tep_db_query('SELECT * FROM `photo_comments` WHERE photo_id="'.$photo_id.'" and has_remove!="1" Order By photo_comments_id ');
$comments = array();
while($comments_rows = tep_db_fetch_array($comments_sql)){
	$comments[] = array('id'=> $comments_rows['photo_comments_id'], 
						'content'=> nl2br(db_to_html(tep_db_output($comments_rows['photo_comments_content']))),
						'date'=>$comments_rows['added_time'],
						'customers_id'=>$comments_rows['customers_id']);
}
$comments_num = count($comments);

$customers_name = tep_customers_name($books['customers_id']);
if($books['customers_id']==$customer_id){ $breadcrumb_title = "我"; }else{ $breadcrumb_title = $customers_name; }

$h3_2 = db_to_html($breadcrumb_title.'的相册');
$breadcrumb_title.="的个人中心";
$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));
$breadcrumb->add(db_to_html($breadcrumb_title), tep_href_link('individual_space.php','customers_id='.$books['customers_id']));
$breadcrumb->add($h3_2, tep_href_link('individual_space.php','customers_id='.$books['customers_id']));
$breadcrumb->add($books_name, tep_href_link('photo_list.php','photo_books_id='.$photo_books_id));

$other_css_base_name = "new_travel_companion_index";

$javascript = 'new_travel_companion.js.php';
$is_travel_companion_bbs = true;
$content = 'photo_list';	//相片模板

$js_get_parameters[] = 'photo_id='.(int)$photo_id;
$js_get_parameters[] = 'content='.$content;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>