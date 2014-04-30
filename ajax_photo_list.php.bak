<?php
/**
 * 提交相片评论
 */
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

//检测用户
if(!tep_session_is_registered('customer_id') || !(int)$customer_id){
	if(!tep_not_null($_POST['password'])){
		echo db_to_html('[ERROR]请输入您的登录密码！[/ERROR]');
		exit;
	}
	if(!tep_not_null($HTTP_GET_VARS['action'])){ $HTTP_GET_VARS['action'] = 'process';}else{ $old_action = $HTTP_GET_VARS['action']; $HTTP_GET_VARS['action'] = 'process'; }
	$ajax = $_POST['ajax'];
	include('login.php');
	if(tep_not_null($old_action)){
		$HTTP_GET_VARS['action'] = $old_action;
	}
}

if($_GET['action']=='process' && $error == false){	//提交相片评论
	$error = false;
	$error_sms = '';
	$comments = tep_db_prepare_input($_POST['tcomments']);
	if(!tep_not_null($comments)){
		$error = true;
		$error_sms .= db_to_html('请输入评论内容。\n\n');
	}
	$photo_id = (int)$_POST['photo_id'];
	$photo_books_id = (int)$_POST['photo_books_id'];
	if(!(int)$photo_id){
		$error = true;
		$error_sms .= db_to_html('Not photo_id. \n\n');
	}
	if(!(int)$customer_id){
		$error = true;
		$error_sms .= db_to_html('Not customer_id. \n\n');
	}
	if($error == true){
		echo '[ERROR]'.	preg_replace('/[[:space:]]+/',' ',$error_sms).'[/ERROR]';
		exit;
	}
	$sql_data_array = array('photo_comments_content' => ajax_to_general_string($comments),
							'photo_id' => $photo_id,
							'customers_id' => $customer_id,
							'added_time' => date('Y-m-d H:i:s'));
	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform('photo_comments', $sql_data_array);

	$close_parameters = array('action','ajax','page','x','y');
	
	$notes_content = '评论添加成功！';
	$out_time = 3; //延迟3秒关闭
	$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'out_time_notes.tpl.html');
	$tpl_content = str_replace('{notes_content}',$notes_content,$tpl_content);
	$tpl_content = str_replace('{out_time}',$out_time,$tpl_content);
	$goto_url = preg_replace($p,$r,tep_href_link('photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$photo_id));			
	
	$js_str = '
	var gotourl = "'.$goto_url.'";
	var notes_contes = "'.addslashes($tpl_content).'";
	write_success_notes('.$out_time.', notes_contes, gotourl);
	';
	$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
	echo db_to_html($js_str);
	exit;
}
?>