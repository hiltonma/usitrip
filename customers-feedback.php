<?php
require('includes/application_top.php');

$sql = tep_db_query('SELECT customers_id,customers_firstname, customers_email_address FROM `customers` WHERE customers_id > 0 AND customers_email_address="'.trim($customers_email_address).'" Order By customers_id limit 1');
$row = tep_db_fetch_array($sql);

$error = false;

if(tep_not_null($_POST['customers_email_address'])){

		$_POST['tours_customer_name'] = db_to_html($row['customers_firstname']);
	
	if(date('Ymd')>20090208){
		$error = true;
		$messageStack->add('feedback', db_to_html('此调查持续时间为<strong>2008年12月8日</strong>至<strong>2009年2月8日</strong>。调查已经结束！'));
	}
	if(!tep_not_null($_POST['tours_age'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择您的年龄'));
	}
	if(!tep_not_null($_POST['tours_gender'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择您的性别'));
	}
	if(!tep_not_null($_POST['tours_from'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 您是通过何种方式知道本网站的'));
	}
	if(!tep_not_null($_POST['ServicesEval'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 您对本站服务的评价'));
	}
	if(!tep_not_null($_POST['tours_add_server'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 您觉得我们需要增加哪些方面的业务/服务'));
	}
	if(!tep_not_null($_POST['tours_i_rec'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 您推荐本站产品给其他朋友没'));
	}
	if(!tep_not_null($_POST['tours_consumer'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 您一般花费多少在一次旅游行程上呢'));
	}
	
	if(!tep_not_null($_POST['tours_focus'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 您在旅游过程中，更在乎下面中的哪一样'));
	}
	
	if(!(int)count($_POST['tours_add_prod'])){
		$error = true;
		$messageStack->add('feedback', db_to_html('请选择 你希望本站除了专业的旅游产品外，还可以添加哪些方面内容呢(可多选)'));
	}
	if(strlen($_POST['tours_phone'])<5){
		$error = true;
		$messageStack->add('feedback', db_to_html('请输入您的电话号码'));
	}else{
		if($_POST['tours_phone']!=$_POST['tours_phone_re']){
			$error = true;
			$messageStack->add('feedback', db_to_html('您两次输入的电话号码不符'));
		}
	}
	
	$check_sql = tep_db_query('select feedback_id from feedback where customers_email_address ="'.tep_db_prepare_input($_POST['customers_email_address']).'" limit 1 ');
	$check_row = tep_db_fetch_array($check_sql);
	if($check_row['feedback_id']){
		$error = true;
		$messageStack->add('feedback', db_to_html('谢谢您的参与，您的邮箱'.$_POST['customers_email_address'].'已经参加过调查！'));
	}
	
	if($error==false){
		$sql_data=array('customers_email_address'=>tep_db_prepare_input($_POST['customers_email_address']),
						'tours_age'=>tep_db_prepare_input($_POST['tours_age']),
						'tours_gender'=>tep_db_prepare_input($_POST['tours_gender']),
						'tours_job'=>tep_db_prepare_input($_POST['tours_job']),
						'tours_like'=>tep_db_prepare_input($_POST['tours_like']),
						'tours_from'=>tep_db_prepare_input($_POST['tours_from']),
						'ServicesEval'=>tep_db_prepare_input($_POST['ServicesEval']),
						'tours_number'=>tep_db_prepare_input($_POST['tours_number']),
						'tours_add_server'=>tep_db_prepare_input($_POST['tours_add_server']),
						'tours_i_rec'=>tep_db_prepare_input($_POST['tours_i_rec']),
						'tours_consumer'=>tep_db_prepare_input($_POST['tours_consumer']),
						'tours_focus'=>tep_db_prepare_input($_POST['tours_focus']),
						'tours_add_prod'=>tep_db_prepare_input(implode(';',$_POST['tours_add_prod'])),
						'tours_site_improve'=>tep_db_prepare_input($_POST['tours_site_improve']),
						'tours_customer_name'=>tep_db_prepare_input($_POST['tours_customer_name']),
						'tours_phone'=>tep_db_prepare_input($_POST['tours_phone']),
						'add_date'=>date('Y-m-d H:i:s')
						);
		$sql_data = html_to_db($sql_data);
		tep_db_perform('feedback', $sql_data);
		
		foreach((array)$_POST['tours_add_prod'] as $key){
			if((int)$key){
				tep_db_query("INSERT INTO `feedback_add_prod` (`tours_add_prod` )VALUES ('".(int)$key."');");
			}
		}
		
		$email_text = "尊敬的 走四方网客户：\n\n" . "&nbsp;&nbsp;&nbsp;&nbsp;您的代金券编号为：USD20 \n\n <b>请注意：此代金券支持的最低订单金额为$300。</b>如果您的单张订单金额小於$300，该代金券将不可使用！";
		$email_text .= "\n\n\n" . CONFORMATION_EMAIL_FOOTER;
		

		$to_email_address[0] = $_POST['customers_email_address'];
		$to_name = db_to_html('走四方网客户');
		$email_text = db_to_html(preg_replace('[\n\r\t]',' ',$email_text));
		$email_subject =db_to_html('usitrip 走四方网客户代金券！');
		$from_email_name ='usitrip';
		$from_email_address = 'service@usitrip.com';
		for($i=0; $i<count($to_email_address); $i++){
			@tep_mail($to_name, $to_email_address[$i], $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
			//echo 'Send Ok!'.$to_email_address[$i].'<br />';
		}

		$hidden_feedback = true;
		//$messageStack->add('feedback', db_to_html('谢谢您的参与，稍后请查看您的邮箱 '.$_POST['customers_email_address'].'。我们已经把代金券发送到您的邮箱！'),'success');
		$messageStackStr = db_to_html('谢谢您的参与，稍后请查看您的邮箱 '.$_POST['customers_email_address'].'。我们已经把代金券发送到您的邮箱！如果您在1小时后依然没有收到代金券邮件，请直接与我们的客服人员联系。 400电话：0086-4006-333-926');
	}
	
}


$content = 'customers-feedback';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>