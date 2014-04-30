<?php
require('../includes/classes/visa_invitation.php');
require('../includes/classes/visa_invitation_print.php');
require('../includes/classes/mail_send_smtp.php');
require('includes/application_top.php');
$opid = (int)$_GET['opid'];
try {
	if (isset($_GET['action']) && $_GET['action'] != '') {
		$action = $_GET['action'];
		switch ($action) {
			case 'send': //预览邀请函
				$invitation = new visa_invitation_print($opid);
				$invitation->set_template_file(DIR_FS_CATALOG . 'email_tpl/visa_invitation.tpl.html');
				if (isset($_POST['dob']) && isset($_POST['passport_number']) && isset($_POST['nationality']) && isset($_POST['index'])) {
					$user_dob = array();
					if(isset($_POST['check'])){
						foreach($_POST['check'] as $i=>$value){
							if ($_POST['dob'][$i] == '' || $_POST['passport_number'][$i] == '' || $_POST['nationality'][$i] == '') {
								exit('表单未填写完整！<a href="javascript:history.go(-1)">Return</a>');
							}
							$user_dob[$_POST['index'][$i]] = array(
								'dob'             => tep_db_output($_POST['dob'][$i]),
								'passport_number' => tep_db_output($_POST['passport_number'][$i]),
								'nationality'     => tep_db_output($_POST['nationality'][$i]),
								'money'           => tep_db_output($_POST['money'][$i]),
								'guest_name'      => tep_db_output($_POST['guest_name'][$i]),
								'email'           => tep_db_output($_POST['email'][$i]),
								'sex'             => tep_db_output($_POST['sex'][$i])
							);
							if(empty($_POST['email'][$i])){
								die('客人邮箱信息不全，请填写完整后再来尝试');
							}
						}
					} else {
						die('未勾选发送人');
					}
					//print_r($user_dob);
					//die();
					$invitation->set_user_dob($user_dob);
					//$invitation->save_guest_to_db();
					//$invitation->addInvitationToDb();
					$name = $invitation->doit();
					$_SESSION['invitation'] = serialize($invitation);
					header('content-type:text/html;charset=' . CHARSET);
					echo($name);
					echo '<div style="margin:0 auto;text-align:center"><a href="?action=send_ok&opid=' . $opid . '">' . db_to_html('确认并且发送通知邮件') . '</a></div>';
					exit;
				} else {
					exit('数据填写不完整');
				}
				break;
			case 'send_ok': //发送邀请函邮件
				//print_r($_SESSION);
				//require('../includes/classes/visa_invitation_print.php');
				if (isset($_SESSION['invitation'])) {
					$invitation = unserialize($_SESSION['invitation']);
					unset($_SESSION['invitation']);
				} else {
					exit('<script type="text/javascript">alert("' . db_to_html('当前没有邮件要发送!') . '");</script>');
				}
				$invitation->addInvitationToDb();
				$invi = new visa_invitation($opid);
 				$to_arr=$invitation->getEmailArray();
 				list($email,$name) = each($to_arr);
 				array_splice($to_arr, 0,1);
				$subject = '走四方--邀请函';
				$str = "尊敬的";
				if (!empty($name)) {
					$str .= $name;
				}
 				if (!empty($to_arr)) {
 					$str .= '、'.implode('、',array_values($to_arr));
 				}
				$str .= "先生/女士，您好：\r\n";
				$str .= "&nbsp;&nbsp;&nbsp;&nbsp;您的签证邀请函已经制作完成，您可以使用此链接登录您的用户中心打印，（";
				$href = str_replace('/admin/','/',tep_href_link('visa_invitation.php','opid=' . $opid));
				$str .= '<a href="' . $href . '" target="_blank">' . $href . '</a>';
				$str .= "），签证时，请携带该邀请函，护照和相关文件，祝您签证顺利。\r\n";
				$str .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;谢谢\r\n注：如果您还未在本站注册，请使用当前收邮件的邮箱地址进行注册！再点以上连接打印邀请函！\r\n";
				$str .= "\r\n" . CONFORMATION_EMAIL_FOOTER . "\r\n";
				$str .= "<b>此邮件由系统自动发出，请勿直接回复！</b>";
				
				$job_number = tep_get_admin_customer_name($_SESSION['login_id']);
				$invi->isSendMail($job_number);
				$mail = new mail_send_smtp();
				$mail->set_from_address('automail@usitrip.com');
				$mail->set_from_name('走四方');
				$mail->set_subject('邀请函--走四方');
				$mail->set_charset('gb2312');
				$mail->set_to_name($name);
				$mail->set_to_address($email);
 				if (!empty($to_arr)) {
 					$mail->set_copy_to($to_arr);//抄送
 				}
 				$mail->set_bcc_to(array('2355652793@qq.com' => '财务','2355652780@qq.com' => '李'));//暗送
				$mail->set_mail_type('html');
				$mail->set_body($str);//设置邮件正文
				
				//读取网站第三方邮件发送信息
				$rs = tep_db_query("select * from smtp_mail where mail_id=4 and action_status = true");
				$rs = tep_db_fetch_array($rs);
				$host_name = $rs['host_name'];
				$port_code = $rs['port_code'];
				$user = $rs['mail_address'];
				$pass = $rs['mail_password'];
				require '../includes/classes/mail_send_agent_smtp.php';
				
				$rtn = $mail->send_mail(new mail_send_agent_smtp($host_name, $user, $pass, $port_code,false,2,DIR_FS_CATALOG . 'tmp'));//发送邮件

				
				header('Content-type:text/html;charset=' . CHARSET);
				exit('<script type="text/javascript">alert("' . db_to_html('邮件已发送!') . '");window.close();</script>');
				die();
				break;
		}
		
	} else {
		$invitation = new visa_invitation_print($opid);
		$guest_name = $invitation->getGuestName();
	}
	$invitation = new visa_invitation_print($opid);
} catch (Exception $e) {
	print_r($e->getMessage());
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>编辑/发送 邀请函</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<style type="text/css">
#connter{width:960px;margin:0 auto;}
</style>
</head>

<body>
<?php
require(DIR_WS_INCLUDES . 'header.php'); 
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>
<div id="connter">

<form action="visa_edit_invitation.php?action=send&opid=<?php echo $opid?>" method="post">
		<?php 
		echo tep_draw_hidden_field('opid',''.$opid.'');
		?>
		<fieldset><legend>参团人员列表</legend>
		<table id="TableList" width="100%">
			<tr style="text-align:center">
				<th>发送</th>
				<th>邮箱</th>
				<th>姓名</th>
				<th>出生日期</th>
				<th>护照号码</th>
				<th>国籍</th>
				<th>金额</th>
				<th>是否发送</th>
			</tr>
			<?php 
			if (is_array($guest_name)) {
				foreach($guest_name as $key => $val) {?>
			<tr>
				<td><input type="checkbox" name="check[<?=$val['guest_id']?>]" /></td>
				<td><?php echo $val['e_mail'] ?></td>
				<td>
					<input type="hidden" value="<?=$val['e_mail'] ?>" name="email[<?=$val['guest_id']?>]" />
					<input type="hidden" value="<?php echo $val['sex']?>" name="sex[<?php echo $val['guest_id']?>]" />
					<input type="hidden" value="<?=$val['guest_name'] ?>" name="guest_name[<?=$val['guest_id']?>]" />
					
					<?php 
						echo $val['guest_name'];
						echo tep_draw_hidden_field('index[' . $val['guest_id'] . ']',(string)$val['guest_id']);
					?>
				</td>
				<td><?php echo tep_draw_input_field('dob[' . $val['guest_id'] . ']', $val['dob'])?></td>
				<td><?php echo tep_draw_input_field('passport_number[' . $val['guest_id'] . ']',$val['passport_number'])?></td>
				<td><?php echo tep_draw_input_field('nationality[' . $val['guest_id'] . ']',$val['nationality'])?></td>
				<td><input type="text" name="money[<?=$val['guest_id']?>]" value="<?=$val['money']?>" /></td>
				<td><?php if($val['is_send']){?><font color="red">已发送</font><?php }else{?><font>未发送</font><?php }?>
				</td>
			</tr>
			<?php 
				}
			}?>
			<tr>
				<td><input type="submit" value="预览邀请函" /></td>
			</tr>
		</table>
		</fieldset>
</form>
</div>

</body>
</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

