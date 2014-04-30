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

//POST form
$error = false;

if($_POST['action']=='AddPhotoBooks' || $_POST['action']=='EditPhotoBooks'){
	$include = true;
	include('add_edit_del_photo_books_ajax.php');
}

  $breadcrumb->add(MY_SPACE, tep_href_link('my-space.php'));
  $breadcrumb->add(db_to_html('创建新相册'), tep_href_link('my-space-create-photo-books.php'));

  $content = 'my-space-create-photo-books';
  //$javascript = $content . '.js';
  
  $is_my_space = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');


?>