<?php
//游记详细信息
require('includes/application_top.php');

if(!$travel_notes_id = (int)$_GET['travel_notes_id']){
	echo 'travel_notes_id is '.$travel_notes_id; exit;
}

$notes_sql = tep_db_query('SELECT * FROM `travel_notes` WHERE travel_notes_id ="'.$travel_notes_id.'" LIMIT 1 ');
$notes = tep_db_fetch_array($notes_sql);
if(!(int)$notes['travel_notes_id']){
	echo db_to_html('<a href="'.tep_href_link('index.php').'">不存在的游记或可能已经被删除！</a>'); exit;
}

$p_href = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $notes['products_id']);
$notes_title = db_to_html(tep_db_output($notes['travel_notes_title']));
$notes_date = chardate($notes['added_time'],"I",1);
$photo_ids = $notes['photo_ids'];
$comment_num = (int)$notes['comment_num'];
$notes_author_id = (int)$notes['customers_id'];
$notes_author_name = tep_customers_name($notes_author_id);
$notes_author_genders = tep_get_gender_string(tep_customer_gender($notes_author_id), 1);

$other_css_base_name = "new_travel_companion_index";
$javascript = 'new_travel_companion.js.php';
$is_travel_companion_bbs = true;
$content = 'travel_notes_detail';

$js_get_parameters[] = 'content='.$content;
$js_get_parameters[] = 'travel_notes_id='.$travel_notes_id;


if($notes_author_id==$customer_id){ $breadcrumb_title = "我"; }else{ $breadcrumb_title = $notes_author_name; }
$h3_2 = db_to_html($breadcrumb_title.'的游记');

$breadcrumb_title .= '的个人中心';
$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));
$breadcrumb->add(db_to_html($breadcrumb_title), tep_href_link('individual_space.php','customers_id='.$notes_author_id));
$breadcrumb->add($h3_2, tep_href_link('travel_notes_list.php','products_id='.$products_id.'&customers_id='.$notes_author_id));
$breadcrumb->add($notes_title, tep_href_link('travel_notes_detail.php','travel_notes_id='.$travel_notes_id));

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>