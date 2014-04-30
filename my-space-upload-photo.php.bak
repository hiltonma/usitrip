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

if($_POST['action']=='add_confirmation'){
	$uplode_sum = 0;
	for($i=0; $i<count($_FILES['file']['name']); $i++){
		if(!(int)$_FILES['file']['error'][$i] && $_FILES['file']['name'][$i]!=""){
			$extension_name = strtolower(end(explode('.',$_FILES['file']['name'][$i])));
			$new_file_name = $customer_id .'_'.date('YmdHis').'_'.$i.'.'.$extension_name;
			
			#判断上传的文件类型
			if($extension_name!="gif" && $extension_name!="jpg" && $extension_name!="jpeg"){
				$messageStack->add('Photo', db_to_html($_FILES['file']['name'][$i].'格式错误：请确保您上传的相片jpg,jpeg或gif格式的相片！'));
				unlink($_FILES['file']['tmp_name'][$i]);
				
			}elseif(copy($_FILES['file']['tmp_name'][$i], DIR_PHOTOS_FS_IMAGES.$new_file_name)){
				//echo 'upOK!<br>';
				$sql_data_array = array('photo_name' => $new_file_name,
										'photo_tag' => $new_file_name,
										'photo_update' => date("Y-m-d H:i:s"),
										'photo_books_id' => (int)$_POST['photo_books_id'],
										'customers_id' => (int)$customer_id
										);
				$sql_data_array = html_to_db($sql_data_array);
				//added photo
				tep_db_perform('photo', $sql_data_array);
				$messageStack->add('Photo', db_to_html('上传相片 '.$new_file_name.' 成功！'),'success');
				$uplode_sum++;
			}
		}
	}
	if(!(int)$uplode_sum){
		$error = true;
		$messageStack->add('Photo', db_to_html('您没有选择上传相片！请选择相片上传，每张相片大小建议不要超过120K！'));
	}else{
		//update photo_sum for photo_books
		$photo_sum = get_photo_books_sum((int)$_POST['photo_books_id']);
		tep_db_query('update photo_books SET `photo_sum` = "'.(int)$photo_sum.'" WHERE photo_books_id="'.(int)$_POST['photo_books_id'].'"');
	}	
}

  $breadcrumb->add(MY_SPACE, tep_href_link('my-space.php'));
  $breadcrumb->add(db_to_html('上传相片'), tep_href_link('my-space-upload-photo.php'));

  $content = 'my-space-upload-photo';
  //$javascript = $content . '.js';
  
  $is_my_space = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');


?>