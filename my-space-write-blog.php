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
if($_GET['action']=='delete_confirmation'){	/*delete blog*/
	tep_db_query('DELETE FROM blog WHERE blog_id="'.(int)$_GET['blog_id'].'" AND customers_id="'.(int)$customer_id.'" ');
	$value = mysql_affected_rows();
	$messageStack->add_session('write_blog', db_to_html('日志删除成功！'),'success');
	tep_redirect(tep_href_link('my-space-logs.php','value='.$value));
	exit;
	
}elseif($_GET['action']=='blog_edit'){
	$action = 'blog_edit_confirmation';
}elseif($_POST['action']!='blog_edit_confirmation'){
	$action = 'blog_add_confirmation';
}

if($_POST['action']=='blog_add_confirmation' || $_POST['action']=='blog_edit_confirmation'){
	$blog_id = (int)$_POST['blog_id'];
	$blog_title = tep_db_prepare_input($_POST['blog_title']);
	$blog_description = tep_db_prepare_input($_POST['blog_description']);
	$blog_privacy_settings = (int)$_POST['blog_privacy_settings'];
	$blog_draft = (int)$_POST['blog_draft'];
	$datetime = date('Y-m-d H:i:s');
	$blog_image = $_POST['blog_image'];

	if(tep_not_null($_FILES['blog_image_file']['name'])){
		//echo "have";exit;
		if(!tep_not_null($blog_image)){
			$blog_image = date('YmdHis');
		}
		$blog_basename = preg_replace("/^(.*\.)/","", $blog_image);

		$temp_name = false;
		$temp_name = up_file('gif,jpg,jpeg', 1200*1024, DIR_BLOG_FS_IMAGES , $blog_basename ,'blog_image_file');
		if($temp_name!=false){
			$blog_image = $temp_name;
		}else{ 
			$messageStack->add('write_blog', db_to_html($temp_name));
			$error = true;
		}
	}
	
	if(strlen($blog_title)<1){
		$error = true;
		$messageStack->add('write_blog', db_to_html('标题 不能少於1个字'));
	}
	if($error == false){
		$sql_data_array = array('blog_title' => $blog_title,
								'blog_description' => $blog_description,
								'blog_privacy_settings' => $blog_privacy_settings,
								'blog_draft' => $blog_draft,
								'customers_id' => $customer_id,
								'blog_image' => $blog_image
								);
		if($_POST['action']=='blog_add_confirmation' && !(int)$blog_id){
			$sql_data_array['blog_add_date'] = $datetime;
			$sql_data_array['blog_up_date'] = $datetime;
			$sql_data_array = html_to_db($sql_data_array);
			tep_db_perform('blog', $sql_data_array);
			$blog_id = tep_db_insert_id();
			if($blog_draft=='1'){
				$messageStack->add('write_blog', db_to_html('数据成功保存到<a href="'.tep_href_link('my-space-logs.php','tag=MyDraftBox').'">草稿箱</a>！'),'success');
			}else{
				$messageStack->add_session('write_blog', db_to_html('数据添加成功！'),'success');
				tep_redirect(tep_href_link('my-space-logs.php'));
			}
			
		}
		if($_POST['action']=='blog_edit_confirmation' || (int)$_POST['blog_id']){
			$sql_data_array['blog_up_date'] = $datetime;
			$sql_data_array = html_to_db($sql_data_array);
			tep_db_perform('blog', $sql_data_array,'update','blog_id="'.(int)$blog_id.'" AND customers_id = "' . (int)$customer_id . '" ');
			if($blog_draft=='1'){
				$messageStack->add('write_blog', db_to_html('数据成功保存到<a href="'.tep_href_link('my-space-logs.php','tag=MyDraftBox').'">草稿箱</a>！'),'success');
			}else{
				$messageStack->add_session('write_blog', db_to_html('数据更新成功！'),'success');
				tep_redirect(tep_href_link('my-space-logs.php'));
			}

		}
		
	}
}

$breadcrumb->add(MY_SPACE, tep_href_link('my-space.php'));
$breadcrumb->add(MY_SPACE_LOGS, tep_href_link('my-space-logs.php'));

// Get Blog
if((int)$blog_id){
	$blog_sql = tep_db_query('SELECT * FROM `blog` WHERE blog_id="'.(int)$blog_id.'" AND customers_id="'.(int)$customer_id.'" ');
	$blog_row = tep_db_fetch_array($blog_sql);
	
	require(DIR_FS_CLASSES . 'object_info.php');
	$blog_info = new objectInfo($blog_row);
	$fields = mysql_list_fields(DB_DATABASE, 'blog');
	$columns = mysql_num_fields($fields);
	for ($i = 0; $i < $columns; $i++) {
		$field_name = mysql_field_name($fields, $i);
		$$field_name = db_to_html($blog_info->$field_name);
	}
}

$content = 'my-space-write-blog';
//$javascript = $content . '.js';

$is_my_space = true;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');


?>