<?php
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

if($_GET['action']=='process' && $error == false){
	$customers_name = tep_db_prepare_input($_POST['customers_name']);
	$customers_phone = tep_db_prepare_input($_POST['customers_phone']);
	$email_address = tep_db_prepare_input($_POST['email_address']);
	$t_companion_title = tep_db_prepare_input($_POST['t_companion_title']);
	if(!tep_not_null($t_companion_title)){
		echo db_to_html('[ERROR]标题不能为空[/ERROR]');
		exit;
	}
	$t_companion_content='';
	$t_companion_content = tep_db_prepare_input($_POST['t_companion_content']);
	if(!tep_not_null($t_companion_content)&&!tep_not_null($_POST['end_place'])){
		echo db_to_html('[ERROR]旅游线路不能为空[/ERROR]');
		exit;
	}
	$products_id = (int)($_POST['products_id']);
	$categories_id = (int)($_POST['categories_id']);
	$t_show_email = 1; 
	if((int)$_POST['not_show_email']){
		$t_show_email = 0; 
	}
	$date_time = date('Y-m-d H:i:s');
	$status = '1';
	$t_gender = (int)($_POST['t_gender']); 
	$hope_departure_date = substr(tep_db_prepare_input($_POST['hope_departure_date']),0,10);
	$hope_departure_date_end = substr(tep_db_prepare_input($_POST['hope_departure_date_end']),0,10);
	$t_top_day = (int)$_POST['t_top_day'];
	$now_people_type=(int)$_POST['type_jieban'];
	$now_people_man = (int)$_POST['now_people_man'];
	$now_people_woman = (int)$_POST['now_people_woman'];
	$now_people_child = (int)$_POST['now_people_child'];
	$hope_people_man = (int)$_POST['hope_people_man'];
	$hope_people_woman = (int)$_POST['hope_people_woman'];
	$hope_people_child = (int)$_POST['hope_people_child'];
	$end_place=isset($_POST['end_place'])?$_POST['end_place']:'';
	$plan=tep_db_prepare_input($_POST['personal_plan']);
	if(($hope_people_man+$hope_people_woman) <1){
		echo db_to_html('[ERROR]期望伴友不能少于1个成人[/ERROR]');
		exit;
	} 
	$open_ended = (int)$_POST['open_ended'];
	$t_show_phone = 1;
	if((int)$_POST['not_show_phone']){
		$t_show_phone = 0;
	}
	$who_payment = (int)$_POST['who_payment'];
	
	//同一出发日期不能发重复的帖子
	if((int)$products_id){
		if(!(int)check_date($hope_departure_date)){
			echo db_to_html('[ERROR]选择一个期望出发日期[/ERROR]');
			exit;
		}
		$check_product = tep_db_query('SELECT t_companion_id FROM `travel_companion` WHERE customers_id="'.(int)$customer_id.'" and products_id="'.(int)$products_id.'" and hope_departure_date="'.$hope_departure_date.'" limit 1');
		$check_product = tep_db_fetch_array($check_product);
		if((int)$check_product['t_companion_id']){
			echo db_to_html('[ERROR]您已经在此行程中发过帖子了，同一出发日期不能多次发帖！[/ERROR]');
			exit;
		}
		
	}
	
	$personal_introduction = tep_db_prepare_input($_POST['personal_introduction']);
	
	$customers_name = html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$customers_name));
	$t_companion_title = html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$t_companion_title));
	$t_companion_content = html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$t_companion_content));
	$customers_phone = html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$customers_phone));
	$personal_introduction = html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$personal_introduction));
	$email_address = html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$email_address));
	$plan= html_to_db(iconv('utf-8',CHARSET.'//IGNORE',$plan));
	
	// 检查是否有coupon_code 代码在发布的内容中。。
	$coupon_code_result = tep_db_query("select coupon_code from coupons");
	while($coupon_code = tep_db_fetch_array($coupon_code_result)) {
		if (strpos($t_companion_title, $coupon_code['coupon_code']) !== false || strpos($t_companion_content,$coupon_code['coupon_code']) !== false || strpos($customers_phone, $coupon_code['coupon_code']) !== false || strpos($personal_introduction,$coupon_code['coupon_code']) !== false) {
			$t_companion_title = str_replace($coupon_code['coupon_code'],'******',$t_companion_title);
			$t_companion_content = str_replace($coupon_code['coupon_code'], '******', $t_companion_content);
			$customers_phone = str_replace($coupon_code['coupon_code'],'******', $customers_phone);
			$personal_introduction = str_replace($coupon_code['coupon_code'], '******', $personal_introduction);
		}
	}
	
	//敏感词过滤
	include_once('includes/classes/word_filter.php');
	$filter = new stringFilter();
	try{
		$customers_name = $filter->checkString($customers_name, 'gb2312');
		$arr_replace=array('群255745376','群8983881','群92823258');
		$t_companion_title=str_replace($arr_replace, '282972788', $t_companion_title);
		$t_companion_title = $filter->checkString($t_companion_title, 'gb2312');
		$t_companion_content=str_replace($arr_replace, '282972788', $t_companion_content);
		$t_companion_content = $filter->checkString($t_companion_content, 'gb2312');
		$customers_phone = $filter->checkString($customers_phone, 'gb2312');
		$personal_introduction=str_replace($arr_replace, '282972788', $personal_introduction);
		$personal_introduction = $filter->checkString($personal_introduction, 'gb2312');
		$email_address = $filter->checkString($email_address, 'gb2312');
		//$filter->write(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'a.php');
	} catch (Exception $e){
		echo($e->getMessage());
	}
	
	$sql_data_array = array('customers_id' => (int)$customer_id ,
							'customers_name' => ($customers_name),
						  	'customers_phone' => ($customers_phone),
						  	'email_address' => $email_address,
						  	't_companion_title' => ($t_companion_title),
						  	't_companion_content' => ($t_companion_content),
						  	'products_id' => $products_id,
						  	'categories_id' => $categories_id,
						  	'status' => $status,
							'add_time' => $date_time,
							'last_time' => $date_time,
							't_show_email'=> $t_show_email,
							't_gender' => $t_gender,
							'hope_departure_date' => $hope_departure_date,
							'hope_departure_date_end' => $hope_departure_date_end,
							't_top_day' => $t_top_day,
							'now_people_man' => $now_people_man,
							'now_people_woman' => $now_people_woman,
							'now_people_child' => $now_people_child,
							'hope_people_man' => $hope_people_man,
							'hope_people_woman' => $hope_people_woman,
							'hope_people_child' => $hope_people_child,
							'open_ended' => $open_ended,
							't_show_phone' => $t_show_phone,
							'personal_introduction' => ($personal_introduction),
							'who_payment' => $who_payment,
							'_type'=>$now_people_type,
							'end_place'=>$end_place,
							'travel_plan'=>$plan
							);
	//发送邮件提醒
	$str_sql='select p.products_id,pd.products_name,p.products_urlname from products_description as pd,products as p where p.products_id = pd.products_id and p.products_id='.(int)$products_id;
	$product_info=tep_db_fetch_array(tep_db_query($str_sql));
	$message='';
	$message.='客户姓名:'.$customers_name."\n";
	$message.='帖子主题:'.$t_companion_title."\n";
	$message.='线    路:'.'<a target="_blank" href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id).'">'.$product_info['products_name']."</a>\n";
	$message.='客人邮箱:'.$email_address."\n";
	$message.="走四方结伴同游官方QQ群:282972788 | <a href='http://e.weibo.com/usitrip2013' target='_blank'>新浪微博</a> | 微信账号:usitrip1 | <a hredf='https://www.facebook.com/iusitrip' target='_blank'>Fcebook</a> \n";
	if (IS_LIVE_SITES === true) {
		$_mail = STORE_OWNER_EMAIL_ADDRESS;
	} else {
		// 如果是测试站就把邮件发给测试本人的邮箱
		$_mail = '158761028@qq.com';
	}
	$to_email_address = $_mail; // STORE_OWNER_EMAIL_ADDRESS;//'service@usitrip.com';
	// $to_email_address .= ',
	// howard.zhou@usitrip.com';
	
	$message .= "\n\n" . '---------------------------------------------------------' . "\n";
	$message .= "\n" . ('发送源位置：') . tep_href_link('jiebantongyou') . "\n";
	$EMAIL_SUBJECT = db_to_html('结伴同游') . " ";
	if(tep_mail(db_to_html(STORE_OWNER . ' '), $to_email_address, $EMAIL_SUBJECT . ' ', db_to_html($message), db_to_html('结伴同游' . ' '), 'automail@usitrip.com')){
		//$messageStack->add_session('global', db_to_html('邮件发送成功,请保持电话畅通。'), 'success');
	}else{
		//$messageStack->add('global', db_to_html('邮件发送不成功，请重新发送。'), 'error');
	}
	//$sql_data_array = html_to_db($sql_data_array);	前面已转不要再转码了
	tep_db_perform('travel_companion', $sql_data_array);
	//优化表
	tep_db_query('OPTIMIZE TABLE `travel_companion` ');
	
	//更新用户的真实姓名，如果$will_update_acc为true才更新
	$will_update_acc = false;
	if(tep_not_null($customers_name) && $will_update_acc==true){
		$sql_data_array1 = array('customers_firstname' => ($customers_name));
		if((int)$t_gender){
			$sql_data_array1['customers_gender'] = ($t_gender==1) ? 'm' : 'f';
		}
		//$sql_data_array1 = html_to_db($sql_data_array1);	前面已转不要再转码了
		$customer_first_name = $sql_data_array1['customers_firstname'];
		tep_session_register('customer_first_name');
		tep_db_perform('customers', $sql_data_array1,'update', ' customers_id="'.(int)$customer_id.'" ');
	}

	$t_companion_id  = tep_db_insert_id();
	if((int)$t_top_day && defined('USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION')){	//置顶扣50积分每天
		if((int)tep_sub_points_for_travel_companion_bbs_to_top($customer_id, $t_top_day, $t_companion_id)){
			tep_db_query('update travel_companion SET bbs_type="2" WHERE t_companion_id ="'.(int)$t_companion_id.'" AND customers_id ="'.(int)$customer_id.'" ');
		}else{
			tep_db_query('update travel_companion SET t_top_day="0" WHERE t_companion_id ="'.(int)$t_companion_id.'" AND customers_id ="'.(int)$customer_id.'" ');
			echo '[JS]alert("'.db_to_html('你的积分不足以用于置顶'.$t_top_day.'天，本次置顶被取消！').'")[/JS]';
		}
	}
	
	//echo '[SUCCESS]'.(int)$t_companion_id.'[/SUCCESS]';
	$notes_content = '结伴同游帖子发送成功！';
	$out_time = 3; //延迟3秒关闭
	$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'out_time_notes.tpl.html');
	$tpl_content = str_replace('{notes_content}',$notes_content,$tpl_content);
	$tpl_content = str_replace('{out_time}',$out_time,$tpl_content);
	$goto_url = preg_replace($p,$r,tep_href_link('new_travel_companion_index.php'));			
	/* jQuery(\'#OutTimeNotesContent\').css({left:(jQuery(\'document\').width() - jQuery(\'#OutTimeNotesContent\').width()) / 2,top:(jQuery(\'document\').height()-jQuery(\'#OutTimeNotesContent\').height()) / 2});
		document.getElementById(\'OutTimeNotesContent\').style.top = \'800px\';
	document.getElementById(\'OutTimeNotesContent\').style.left = \'669px\';
	
	
	*/
	$js_str = '
	
	closePopup("CreateNewCompanion");
	var gotourl = "'.$goto_url.'";
	var notes_contes = "'.addslashes($tpl_content).'";

	jQuery(\'#OutTimeNotes\').css({left:(document.documentElement.clientWidth - jQuery(\'#OutTimeNotes\').width()) / 2,top:((document.documentElement.clientHeight-jQuery(\'#OutTimeNotes\').height()) / 2) + Math.max(document.documentElement.scrollTop, document.body.scrollTop)});
	write_success_notes('.$out_time.', notes_contes, gotourl);
	
	';
	$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
	echo db_to_html($js_str);
	exit;
	
}
?>