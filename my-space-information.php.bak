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
if($_POST['action']=='user_edit'){
	$user_nickname = tep_db_prepare_input($_POST['user_nickname']);
	$user_gender = tep_db_prepare_input($_POST['user_gender']);
	$user_description = tep_db_prepare_input($_POST['user_description']);
	$user_privacy_settings = tep_db_prepare_input($_POST['user_privacy_settings']);
	$user_description = tep_db_prepare_input($_POST['user_description']);
	$user_privacy_settings = tep_db_prepare_input($_POST['user_privacy_settings']);
	
	$tours_loving = $_POST['tours_loving'];
	
	if(!tep_not_null($user_nickname)){
		$error = true;
		$messageStack->add('user_edit', db_to_html('请填写您的昵称'));
	}
	if(!tep_not_null($user_gender)){
		$error = true;
		$messageStack->add('user_edit', db_to_html('请选择您的性别'));
	}
	if(!tep_not_null($tours_loving)){
		$error = true;
		$messageStack->add('user_edit', db_to_html('请选择您的旅游爱好'));
	}
	if(!tep_not_null($user_description)){
		$error = true;
		$messageStack->add('user_edit', db_to_html('请填写您的个人描述'));
	}
	//face
	$user_face = false;
	if(tep_not_null($_FILES['face_file']['name'])){
		$pic_name = $customer_id;
		$tmp = false;
		$tmp = up_file('gif,jpg,jpeg', 120*1024, 'images/face/' , $pic_name ,'face_file','Y','155,155');
		if($tmp!=false){
			$user_face = $tmp;
		}else{ 
			$error = true;
			$messageStack->add('user_edit', db_to_html('上传的图像长宽必须是不能大於155px * 155px的图片，图片的jpeg图片'));
		}
	}
	
	if($error == false){
		//update user table
		$sql_data_array = array('user_nickname' => $user_nickname,
		 						'user_gender' => $user_gender,
								'user_description' => $user_description,
								'user_privacy_settings' => $user_privacy_settings
								);
		if(tep_not_null($user_face)){
			$sql_data_array['user_face'] = $user_face;
		}
		$sql_data_array = html_to_db($sql_data_array);
		tep_db_perform('user', $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
		//update tours_loving_to_user
		tep_db_query('delete FROM `tours_loving_to_user` WHERE  customers_id="'.(int)$customer_id.'" ');
		foreach($tours_loving as $value){
			if((int)$value){
				tep_db_query('INSERT INTO `tours_loving_to_user` ( `tours_loving_id` , `customers_id` )VALUES ('.(int)$value.', '.(int)$customer_id.'); ');
			}
		}
		
		$messageStack->add('user_edit', db_to_html('数据更新成功！'),'success');
	}
}

$breadcrumb->add(MY_SPACE, tep_href_link('my-space.php'));
$breadcrumb->add(MY_SPACE_INFORMATION, tep_href_link('my-space-logs.php'));
//get user information
$user_sql = tep_db_query('SELECT * FROM `user` WHERE customers_id="'.(int)$customer_id.'" ');
$user_row = tep_db_fetch_array($user_sql);

require(DIR_FS_CLASSES . 'object_info.php');
$user_info = new objectInfo($user_row);
$fields = mysql_list_fields(DB_DATABASE, 'user');
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
	$field_name = mysql_field_name($fields, $i);
	$$field_name = db_to_html($user_info->$field_name);
}

$content = 'my-space-information';
//$javascript = $content . '.js';

$is_my_space = true;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');


?>