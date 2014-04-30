<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");   

require_once('includes/application_top.php');
require_once(DIR_FS_LANGUAGES . $language . '/hotel.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

$error = false;

if($_POST['action']=='send_mail_to_friends'){
	//echo $_POST['hotel_id'];
	$email_address = trim($_POST['email_address']);
    $email_address_strong = trim($_POST['email_address_strong']);
	$hotel_id = (int)$_POST['hotel_id'];
	if(!(int)$hotel_id){
		$error = true;
		$messageStack->add('hotel', db_to_html('无酒店信息。'));
	}
	
	//拆分朋友邮件地址
	$f_email_array = explode(',',$email_address_strong);
	if($email_address_strong==iconv(CHARSET,'utf-8'.'//IGNORE',TEXT_NOTE_MSN)){
		$error = true;
		$messageStack->add('hotel', TEXT_ERROR_MSN);
	}
	if($email_address==iconv(CHARSET,'utf-8'.'//IGNORE',TEXT_NOTE_MSN_1)){
		$error = true;
		$messageStack->add('hotel', TEXT_ERROR_MSN_1);
	}

	$email_chk_a = $f_email_array;
	array_push($email_chk_a, $email_address);
	if($error == false){
		foreach((array)$email_chk_a as $key => $val){
			$val = trim($val);
			if (strlen($val) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
				$error = true;
				$messageStack->add('hotel', iconv('utf-8',CHARSET.'//IGNORE',$val).': '.ENTRY_EMAIL_ADDRESS_ERROR);
			}elseif(tep_validate_email($val) == false){
				$error=true;
				$messageStack->add('hotel', iconv('utf-8',CHARSET.'//IGNORE',$val).': '.ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
			}
		}
	}
	
	if($error == false){
		$hotel_sql = tep_db_query('SELECT * FROM `hotel` WHERE hotel_id="'.(int)$hotel_id.'" LIMIT 1');
		$hotel_row = tep_db_fetch_array($hotel_sql);

		$email_text = 'Hi，我在走四方网（208.109.123.18）上发现了一个很不错的酒店 '.$hotel_row['hotel_name'].'，你不妨去看看吧！'."\n";
		$email_text .= '这个酒店的网址是：'.tep_href_link('hotel.php','hotel_id='.(int)$hotel_row['hotel_id'])."\n";
		$email_text = db_to_html($email_text);
		
		foreach((array)$f_email_array as $key => $val){
			$val = trim($val);
			$to_name = $val." ";
			$to_email_address = $val;
			$email_subject = db_to_html('走四方网酒店推荐')." ";
			$from_email_name = $email_address." ";
			$from_email_address = $email_address;
			$action_type = 'true';
			tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $action_type, CHARSET);
			$messageStack->add('hotel', db_to_html('成功发送到'). $to_email_address, 'success');
		} 
	}
}
?>

<?php
if ($messageStack->size('hotel') > 0) {
?>
	<table border="0" width="100%"   cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
	<tr>
	<td align="left">
	<?php echo $messageStack->output('hotel'); 	?>
	</td>
	</tr>
	</table>
<?php
}
?>

