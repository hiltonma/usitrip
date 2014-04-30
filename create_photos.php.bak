<?php
/*
  $Id: account_edit.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

//取得我的相册
$books_sql = tep_db_query('SELECT photo_books_id, photo_books_name FROM `photo_books` WHERE customers_id ="'.$customer_id.'" Order By photo_books_id DESC ');
$books_option = array();
$books = tep_db_fetch_array($books_sql);
if((int)$books['photo_books_id']){
	$books_option[] = array('id'=>'0', 'text'=>db_to_html('选择相册'));
	do{
		$books_option[] = array('id'=>$books['photo_books_id'], 'text'=>db_to_html(tep_db_output($books['photo_books_name'])));
	}while($books = tep_db_fetch_array($books_sql));
}else{
	$books_option[] = array('id'=>'0', 'text'=>db_to_html('请点右边“创建新相册”创建相册'));
}

$is_travel_companion_bbs = true;
$content = 'create_photos';
$other_css_base_name = "new_travel_companion_index";
$javascript = 'new_travel_companion.js.php';

$breadcrumb_title = "我";

$h3_2 = db_to_html($breadcrumb_title.'的相册');
$breadcrumb_title.="的个人中心";

$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));
$breadcrumb->add(db_to_html($breadcrumb_title), tep_href_link('individual_space.php','customers_id='.$customer_id));
$breadcrumb->add($h3_2, tep_href_link('individual_space.php','customers_id='.$customer_id));
$breadcrumb->add(db_to_html('上传照片'), tep_href_link('create_photos.php'));


require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
