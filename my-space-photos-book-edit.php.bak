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

  $breadcrumb->add(MY_SPACE, tep_href_link('my-space.php'));
  $breadcrumb->add(MY_SPACE_PHOTOS, tep_href_link('my-space-photos.php'));

$error = false;

if(!(int)$photo_books_id){
	$messageStack->add_session('Photo', db_to_html('无此ID的相册！'),'');
	tep_redirect(tep_href_link('my-space-photos.php'));
}

//编辑相册
if($_POST['action']=='AddPhotoBooks' || $_POST['action']=='EditPhotoBooks'){
	$include = true;
	include('add_edit_del_photo_books_ajax.php');
}

//编辑相片
if($_POST['action']=='EditPhoto'){
	if($_POST['DelAction']=='1'){
		//删除相片
		$sql = tep_db_query('SELECT photo_name,photo_books_id FROM photo WHERE photo_id="'.(int)$_POST['photo_id'].'" AND customers_id="'.(int)$customer_id.'" ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['photo_name'])){
			@unlink(DIR_PHOTOS_FS_IMAGES.$row['photo_name']);
		}
		tep_db_query('DELETE FROM photo WHERE photo_id="'.(int)$_POST['photo_id'].'" AND customers_id="'.(int)$customer_id.'" ');
		tep_db_query('UPDATE photo_books SET photo_sum="'.get_photo_books_sum((int)$row['photo_books_id']).'" WHERE photo_books_id="'.(int)$row['photo_books_id'].'" ');
		$messageStack->add_session('Photo', db_to_html('相片删除成功！'),'success');
		tep_redirect(tep_href_link('my-space-photos-book-edit.php','photo_books_id='.(int)$photo_books_id));
		exit;
	}

	//编辑相片
	
	$sql_data_array = array('photo_tag' => tep_db_input($_POST['photo_tag']),
							'photo_name' => tep_db_input($_POST['photo_name']),
							'photo_update' => date("Y-m-d H:i:s"));
	
	if((int)$_POST['photo_books_id']){
		$sql_data_array['photo_books_id'] = (int)$_POST['photo_books_id'];
		$photo_books_id = (int)$_POST['photo_books_id'];
	}else{
		$photo_books_id = (int)$_GET['photo_books_id'];
	}
	
	tep_db_perform('photo', $sql_data_array,'update','photo_id="'.(int)$_POST['photo_id'].'" AND customers_id="'.(int)$customer_id.'" ');
	tep_db_query('UPDATE photo_books SET photo_sum="'.get_photo_books_sum((int)$_POST['photo_books_id']).'" WHERE photo_books_id="'.(int)$_POST['photo_books_id'].'" ');
	
	//设为相册默认封面
	if($_POST['set_cover']=='1'){
		tep_db_query('UPDATE photo_books SET photo_books_cover="'.tep_db_input($_POST['photo_name']).'" WHERE photo_books_id="'.(int)$_POST['photo_books_id'].'" ');
		
	}	

	$messageStack->add_session('Photo', db_to_html('相片更新成功！'),'success');
	tep_redirect(tep_href_link('my-space-photos-book-edit.php','photo_books_id='.(int)$photo_books_id) . '#tag=EditPhoto');
	exit;
}

//取得当前相册资料
	$photo_book = get_user_photo_books_list($customer_id,' photo_books_id = "'.(int)$_GET['photo_books_id'].'" ', '', '1');
	

  $content = 'my-space-photos-book-edit';
  //$javascript = $content . '.js';
  
  $is_my_space = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');


?>