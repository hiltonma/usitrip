<?php 
/*******************************
*作者：周豪华
*日期：2007-12-6
常用的几个smtp邮箱
host：ssl://smtp.gmail.com
port：465 或 587
Username：service@usitrip.com
pass：Jul2009S*

host：smtp.126.com
port：25
Username：xmzhh2000@126.com
pass：Jul2009S*

host：smtp.bizmail.yahoo.com
port：25
Username：service@hobby-estore.com
pass：2008newhobby

*******************************/
require_once(dirname(__FILE__)."/class.phpmailer.php");

//参数说明(发送到,收件人姓名,邮件主题,邮件内容,使用的字符集,发件人姓名，设定附件文件名(数组)，上传的附件文件临时路径(数组),是否群发 )
function smtp_mail ($sendto_email, $sendto_name, $subject, $body, $CharSet = "gb2312", $FromName='走四方网', $SetFileNameArray="",$TmpFileArray="",$use_mail_address="automail@usitrip.com") {
	global $mail_isNoReplay;
	$mail = new PHPMailer();
	$mail->SMTPDebug = false; //是否打开测错模式测试时建议打开
	$mail->IsSMTP(); // send via SMTP
	$mail->Host = urlencode("ssl://smtp.gmail.com"); // SMTP servers for SSL
	$mail->Port = '465';//连接端口用于SSL连接
	$mail->SMTPAuth = true; // turn on SMTP authentication 是否需要验证

	$use_this = true;
	if(tep_not_null($use_mail_address)){
		$sql = tep_db_query('SELECT mail_id, mail_password FROM `smtp_mail` where mail_address="'.$use_mail_address.'" AND action_status="true" ');
		$row = tep_db_fetch_array($sql);
		if((int)$row['mail_id']){
			$mail->Username = $use_mail_address;
			$mail->Password = $row['mail_password'];

			$use_this = false;
		}else{
			$use_this = true;
		}

	}

	if($use_this==true){
		$sql = tep_db_query('SELECT * FROM `smtp_mail` where mail_address="automail@usitrip.com" AND action_status="true" ');
		$row = tep_db_fetch_array($sql);
		$mail->Username = $row['mail_address']; // SMTP username注意：普通邮件认证不需要加@域名
		$mail->Password = $row['mail_password']; // SMTP password
	}

	//检测邮箱是否还可以发邮件
	$T_day = date('Y-m-d');
	$check_sql = tep_db_query('SELECT * FROM `smtp_mail` where mail_address="'.$mail->Username.'" limit 1');
	$check = tep_db_fetch_array($check_sql);
	if($check['mail_max_sent_num']>=$check['mail_max_send_num'] && $check['mail_date']==$T_day){
		$like_f = "auto%";
		if(substr($mail->Username,0,10)=="newsletter"){
			$like_f = "newsletter%";
		}

		tep_db_query('update smtp_mail set mail_max_sent_num = "0", mail_date ="'.$T_day.'" WHERE mail_date < "'.$T_day.'" ');

		$sql = tep_db_query('SELECT * FROM `smtp_mail` where mail_max_send_num > mail_max_sent_num AND action_status="true" AND mail_address Like "'.$like_f.'" order by rand() limit 1 ');
		$row = tep_db_fetch_array($sql);
		$mail->Username = $row['mail_address'];
		$mail->Password = $row['mail_password'];
	}elseif($check['mail_date']!=$T_day){
		tep_db_query('update smtp_mail set mail_max_sent_num = "0", mail_date ="'.$T_day.'" where mail_address="'.$mail->Username.'" ');
	}

	$mail->From = $mail->Username; //发件人邮箱注意：发件人邮箱必须是Host下面的一个电子邮件地址
	$mail->FromName =$FromName; //发件人

	$mail->CharSet = $CharSet; //这里指定字符集！
	$mail->Encoding = "base64";

	$to_email_array=explode(",",$sendto_email);
	$to_name_array=explode(",",$sendto_name);
	//if(count($to_email_array)==count($to_name_array)){
	for($i=0; $i<count($to_email_array); $i++){
		$mail->AddAddress($to_email_array[$i],$to_name_array[$i]); //收件人邮箱和姓名
	}
	//}else{ die('ERR: count(\$to_email_array) != count(\$to_name_array)');}

	//$mail->AddReplyTo($mail->Username , $FromName); //回复的邮件地址及名称
	//$mail->AddReplyTo('service@usitrip.com' , $FromName); //回复的邮件地址及名称
	$AddReplyTo = strlen($use_mail_address) ? $use_mail_address : 'service@usitrip.com';
	$mail->AddReplyTo($AddReplyTo , $FromName);
	//$mail->ConfirmReadingTo = 'xmzhh2000@126.com'; //回执？

	$mail->WordWrap = 100; // set word wrap 自动换行的字数

	//以下与附件有关
	if(is_array($SetFileNameArray) && count($SetFileNameArray)>0 && count($TmpFileArray)>0){//附件路径和文件名,是数组集合
		for($i=0; $i<count($SetFileNameArray); $i++){
			if($TmpFileArray[$i]!="" && $SetFileNameArray[$i]!=""){
				$mail->AddAttachment($TmpFileArray[$i], $SetFileNameArray[$i]);
			}
		}
	}

	$mail->IsHTML(true); // send as HTML
	//邮件主题
	$mail->Subject = $subject;
	//邮件内容

	if(preg_match('/^auto/', $mail->Username)||preg_match('/^order/', $mail->Username)||preg_match('/^jifen/', $mail->Username)||preg_match('/^newsletter/', $mail->Username) ){
		$attach_text = iconv('gb2312',$mail->CharSet.'//IGNORE','<div style="width:100%; display:block; clear:both; line-height:25px;"><b>此邮件由系统自动发出，请勿直接回复。</b></div>');
		$mail->Body = $body.$attach_text;
	}else{
		$mail->Body = $body;
	}


	$mail->AltBody ="text/html";
	if(!$mail->Send()){
		//写错误日志到log DIR_FS_CATALOG DIR_FS_DOCUMENT_ROOT
		$error_log_file="";
		$error_log_file = dirname(__FILE__)."/SendErrorLog.txt";
		$error_notes = date("Y-m-d H:i:s")." | ";
		for($i=0; $i<count($to_email_array); $i++){
			$error_notes .= "Send to: ".$to_email_array[$i]."; ";
		}
		$error_notes .= " | Error: " . $mail->ErrorInfo;
		$error_notes .= " | From:".$mail->From." | Url:".AbsoluteUrl()."\n";
		if($error_log_file==""){
			echo $error_notes;
		}else{
			$write_type = "ab";
			$file_max_size = 1024*1024*2; //2M
			if(@filesize($error_log_file)>$file_max_size){
				$write_type = "wb";
			}
			if($handle = fopen($error_log_file, $write_type)){
				fwrite($handle, $error_notes);
				fclose($handle);
			}
		}
		//exit;
	}else {
		//echo " $sendto_email邮件发送成功!<br />";
		$log_file = dirname(__FILE__)."/SendGoodLog.txt";
		/*echo $log_file;
		print_r($to_email_array);
		exit();*/
		$ok_notes = '';
		for($i=0; $i<count($to_email_array); $i++){
			$ok_notes .= date('Y-m-d H:i:s')." Send to: ".$to_email_array[$i].";\n";
		}
		$write_type = "ab";
		$file_max_size = 1024*1024*2; //2M
		if(@filesize($log_file)>$file_max_size){
			$write_type = "wb";
		}
		if($handle = fopen($log_file, $write_type)){
			fwrite($handle, $ok_notes);
			fclose($handle);
		}


		if($_SESSION['Whetherismass'] != 1){//判断是否群发
			$ok_num = 0;
			for($i=0; $i<count($to_email_array); $i++){
				$ok_num++;
				//echo $to_name_array[$i]."&lt;".$to_email_array[$i]."&gt;邮件发送成功!<br />";
			}
			//写该邮件已经发送的信息到数据库
			$the_day = date("Y-m-d");
			tep_db_query('update smtp_mail set mail_max_sent_num = mail_max_sent_num+'.$ok_num.', mail_date ="'.$the_day.'" where mail_address="'.$mail->From.'" ');

			/*/删除附件文件
			if(count($TmpFileArray)>0){
			for($i=0; $i<count($TmpFileArray); $i++){
			if($TmpFileArray[$i]!=""){
			@unlink($TmpFileArray[$i]);
			}
			}
			}*/
		}
		return true;
	}
}

?>