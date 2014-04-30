<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

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

//提交结伴同游申请
if($_GET['action']=='process' && $error == false){
	$error_sms = "";	
	
	$t_companion_id = tep_db_prepare_input($_POST['t_companion_id']);
	if(!(int)$t_companion_id){ $t_companion_id = (int)$_GET['t_companion_id'];}
	if(!(int)$t_companion_id){
		$error_sms .= db_to_html('无结伴同游数据，请刷新页面！\n\n');
		$error = true;
	}
	
	if((int)tep_check_travel_companion_app($customer_id, $t_companion_id)){
		$error_sms = db_to_html('您已经申请该结伴，请勿重复申请！\n\n');
		$error = true;
		echo "[JS] style_alert('".$error_sms."'); closeDiv('travel_companion_tips_2064')[/JS]";
		exit;
	}
	
	$apped_num = tep_count_travel_companion_app_num($t_companion_id);
	$sql = tep_db_query('SELECT customers_id,hope_people_man,hope_people_woman,hope_people_child,open_ended, has_expired  FROM `travel_companion` WHERE t_companion_id='.(int)$t_companion_id.' ');
	$rows = tep_db_fetch_array($sql);
	//检查是否已经满人或是否已过期
	if($rows['has_expired']=="1"){
		$error_sms = db_to_html('已经过期！\n\n');
		$error = true;
		echo "[JS] style_alert('".$error_sms."'); closeDiv('travel_companion_tips_2064')[/JS]";
		exit;
	}
	
	$hope_pep_num = (int)$rows['hope_people_man']+(int)$rows['hope_people_woman']+(int)$rows['hope_people_child'];
	if($apped_num > $hope_pep_num && !(int)$rows['open_ended']){
		$error_sms = db_to_html('名额已满！不能申请了，看看别的吧。\n\n');
		$error = true;
		echo "[JS] style_alert('".$error_sms."'); closeDiv('travel_companion_tips_2064')[/JS]";
		exit;
	}
	
	//检查是否是楼主申请
	if($rows['customers_id']==$customer_id){
		$error_sms = db_to_html('你是楼主，不用申请。\n\n');
		$error = true;
		echo "[JS] style_alert('".$error_sms."'); closeDiv('travel_companion_tips_2064')[/JS]";
		exit;
	}
	
	$tca_cn_name = tep_db_prepare_input($_POST['tca_cn_name']);
    if (strlen($tca_cn_name) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    	$error = true;
    	$error_sms .= db_to_html('姓名 不能少于'.ENTRY_FIRST_NAME_MIN_LENGTH.'个字\n\n');
	}
	$tca_en_name = tep_db_prepare_input($_POST['tca_en_name']);
    if (strlen($tca_en_name) < ENTRY_LAST_NAME_MIN_LENGTH) {
    	$error = true;
    	$error_sms .= db_to_html('英文名 不能少于'.ENTRY_LAST_NAME_MIN_LENGTH.'个字\n\n');
	}
	$tca_gender = tep_db_prepare_input($_POST['tca_gender']);
    if (!(int)$tca_gender) {
    	$error = true;
    	$error_sms .= db_to_html('性别 必需填写或选择\n\n');
	}
	$tca_email_address = tep_db_prepare_input($_POST['email_address']);
	if(tep_validate_email($tca_email_address) == false){
    	$error = true;
    	$error_sms .= db_to_html('邮箱 请输入有效的电子邮箱\n\n');
	}
	$tca_people_num = (int)$_POST['tca_people_num'];
	if($tca_people_num == 0){
    	$error = true;
    	$error_sms .= db_to_html('人数 人数不能小于1\n\n');
	}
	
	$tca_phone = tep_db_prepare_input($_POST['tca_phone']);
	$tca_content = tep_db_prepare_input($_POST['tca_content']);
		
	if($error == true){
		//echo '[ERROR]'.$error_sms.'[/ERROR]';
		echo '[JS] alert("'.$error_sms.'"); [/JS]';
		exit;
	}
	
	$tca_verify_status = '0';
	$customers_id = $customer_id;
	
	$date_time = date('Y-m-d H:i:s');
	$status = '1';
	$sql_data_array = array('t_companion_id' => (int)$t_companion_id ,
							'tca_cn_name' => iconv('utf-8',CHARSET.'//IGNORE',$tca_cn_name),
						  	'tca_en_name' => iconv('utf-8',CHARSET.'//IGNORE',$tca_en_name),
						  	'tca_gender' => $tca_gender,
						  	'tca_email_address' => $tca_email_address,
						  	'tca_phone' => iconv('utf-8',CHARSET.'//IGNORE',$tca_phone),
						  	'tca_people_num' => $tca_people_num,
							'tca_content' => iconv('utf-8',CHARSET.'//IGNORE',$tca_content),
							'tca_verify_status' => (int)$tca_verify_status,
							'customers_id' => $customers_id,
							'date_time' => $date_time);

	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform('`travel_companion_application`', $sql_data_array);
	$t_c_reply_id = tep_db_insert_id();

	//更新用户的真实姓名，如果$will_update_acc为true才更新
	$will_update_acc = false;
	if(tep_not_null($tca_cn_name) && $will_update_acc==true){
		$sql_data_array1 = array('customers_firstname' => iconv('utf-8',CHARSET.'//IGNORE',$tca_cn_name),
								'customers_lastname' => iconv('utf-8',CHARSET.'//IGNORE',$tca_en_name) );
		if((int)$tca_gender){
			$sql_data_array1['customers_gender'] = ($tca_gender==1) ? 'm' : 'f';
		}
		$sql_data_array1 = html_to_db($sql_data_array1);
		$customer_first_name = $sql_data_array1['customers_firstname'];
		tep_session_register('customer_first_name');
		tep_db_perform('customers', $sql_data_array1,'update', ' customers_id="'.(int)$customer_id.'" ');
	}
	
	//添加了申请后发邮件给楼主
	
	/* 发有人回复邮件  by lwkai add 2012-06-26*/
	if (class_exists('send_mail_ready') == false) {
		require_once DIR_FS_CLASSES . 'send_mail_ready.php';
	}
	if (class_exists('companion_mail') == false) {
		require_once DIR_FS_CLASSES . 'companion_mail.php';
	}
	if (class_exists('application_companion_mail') == false) {
		require_once DIR_FS_CLASSES . 'application_companion_mail.php';
	}
	new application_companion_mail($t_companion_id, $customer_id, $t_c_reply_id);
	
	/* 回复邮件结束 */
	
	/*$mail_sql = tep_db_query('SELECT * FROM `travel_companion` WHERE t_companion_id="'.(int)$t_companion_id.'" Limit 1 ');
	$mail_rows = tep_db_fetch_array($mail_sql);
	
	$travel_companion_app_email_switch = TRAVEL_COMPANION_RE_EMAIL_SWITCH;
	
	if($travel_companion_app_email_switch == 'true' && $customer_id!=$mail_rows['customers_id'] ){	//自己回的则不发邮件
		$to_name = strip_tags($mail_rows['customers_name']) ." ";
		$to_email_address = strip_tags($mail_rows['email_address']);
		$from_email_name = STORE_OWNER;
		$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
		
		$email_subject = '走四方结伴同游—有人申请 ';
		$email_text = '尊敬的 '.strip_tags($mail_rows['customers_name'])."\n";
		$tTcPath = tep_get_category_patch($mail_rows['categories_id']);
		/* 取得结伴同游所在线路的名称和URL地址 * /
		$sql = tep_db_query("select p.products_urlname,pd.products_name from products as p,products_description as pd where p.products_id=pd.products_id and p.products_id='" . $mail_rows['products_id'] . "'");
		$product_row = tep_db_fetch_array($sql);
		$email_text .= '您发起的名称为 『'.strip_tags($mail_rows['t_companion_title']).'』的结伴同游,有新的人员申请。';
		$email_text .= '线路是：<a href="' . tep_href_link($product_row['products_urlname'] . '.html') . '" target="_blank">' . $product_row['products_name'] . '</a>' . "\n";
		$email_text .= '点此连接查看<a href="'.tep_href_link('my_travel_companion.php','action=my_sent') . '" target="_blank">'.tep_href_link('my_travel_companion.php','action=my_sent') . '</a> 注：如果点击打不开，请复制该连接到浏览器地址栏打开。'."\n\n";
		$email_text .= EMAIL_SEPARATOR."\n";
		$email_text .= '申请人的资料如下：'."\n";
		$email_text .= '姓名：'.$sql_data_array['tca_cn_name'].' ['.$sql_data_array['tca_en_name'].']'."\n";
		$email_text .= '性别：'.($sql_data_array['tca_gender']=="2" ? '女士' : '先生')."\n";
		$email_text .= '电子邮箱：'.$sql_data_array['tca_email_address']."\n";
		$email_text .= '电话：'.$sql_data_array['tca_phone']."\n";
		$email_text .= '人数：'.$sql_data_array['tca_people_num']."\n";
		$email_text .= '留言内容：'.tep_db_output($sql_data_array['tca_content'])."\n";
		$email_text .= EMAIL_SEPARATOR."\n\n";
		$email_text .= CONFORMATION_EMAIL_FOOTER;
		
		$array_i = count($_SESSION['need_send_email']);
		$_SESSION['need_send_email'][$array_i]['to_name'] = db_to_html($to_name);
		$_SESSION['need_send_email'][$array_i]['to_email_address'] = $to_email_address;
		$_SESSION['need_send_email'][$array_i]['email_subject'] = db_to_html($email_subject);
		$_SESSION['need_send_email'][$array_i]['email_text'] = db_to_html($email_text);
		$_SESSION['need_send_email'][$array_i]['from_email_name'] = db_to_html($from_email_name);
		$_SESSION['need_send_email'][$array_i]['from_email_address'] = $from_email_address;
		$_SESSION['need_send_email'][$array_i]['action_type'] = 'true';
		
	}
	*/
	//echo '[SUCCESS]'.(int)$t_companion_id.'[/SUCCESS]';
	$notes_content = '申请结伴信息已经成功提交！';
	$out_time = 3; //延迟3秒关闭
	$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'out_time_notes.tpl.html');
	$tpl_content = str_replace('{notes_content}',$notes_content,$tpl_content);
	$tpl_content = str_replace('{out_time}',$out_time,$tpl_content);
	$goto_url = "";			
	
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