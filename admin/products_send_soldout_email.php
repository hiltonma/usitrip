<?php
ignore_user_abort(true);
require('includes/application_top.php');
ini_set("max_execution_time", 0);
set_time_limit(0);

$product_id = intval($_GET['product_id']);
if($product_id>0){
	
	$sql = 'select pd.products_name from '.TABLE_PRODUCTS . ' p , '. TABLE_PRODUCTS_DESCRIPTION . ' pd where p.products_id = pd.products_id  and p.products_status = "1" and p.products_id="'.$product_id.'"';
	$product = tep_db_query($sql);
	$product = tep_db_fetch_array($product);
	
	$products_name = $product['products_name'];
	
	if($products_name!=''){
		$product_url = HTTP_CATALOG_SERVER."/".seo_get_products_path($product_id, true,'');
		//邮件标题及内容 Start =========================
		//简体
		$mail_title['gb2312'] = '产品恢复预订通知-usitrip';
		$mail_content['gb2312'] = '<p>Dear {email}<br />尊敬的走四方网用户</p><p>行程:<a href="{products_url}" target="_blank">{products_name}</a><br />地址：<a href="{products_url}" target="_blank">{products_url}</a><br />（如无法点击连接，请将地址复制到浏览器进行访问！）</p><p>已经恢复预定，您现在可以马上进行预定该行程。<br />走四方网欢迎您的光临！</p><p> 走四方网 - usitrip<br />www.usitrip.com</p>';
		
		$mail_content['gb2312'] = str_replace('{products_url}',$product_url,$mail_content['gb2312']);
		$mail_content['gb2312'] = str_replace('{products_name}',$products_name,$mail_content['gb2312']);
		//繁体
		$mail_title['big5'] = iconv('gb2312','big5//IGNORE',$mail_title['gb2312']);
		$mail_content['big5'] = iconv('gb2312','big5//IGNORE',$mail_content['gb2312']);
		//邮件标题及内容  End  =========================
		$soldout_email = tep_db_query("SELECT * FROM `products_soldout_email` WHERE `products_id`='{$product_id}' and `email`!='#soldout_sending#'");// group by `email`
		$mail = tep_db_fetch_array($soldout_email);
		if($mail['products_id']>0){
			//更新正在发送的状态
			tep_db_query("INSERT INTO `products_soldout_email` SET `products_id`='{$product_id}',`email`='#soldout_sending#';");
			//循环发送邮件
			$mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
			do{
				$FromEmailName = 'usitrip';
				$FromEmail = get_mail_address();
				
				$mailTitle = $mail_title['big5'];
				$mailContent = $mail_content['big5'];
				$charset = strtolower(trim($mail['charset']));
				if($charset=='gb2312'){
					$mailTitle = $mail_title['gb2312'];
					$mailContent = $mail_content['gb2312'];	
				}else{
					$charset='big5';
					$FromEmailName = iconv('gb2312','big5//IGNORE',$FromEmailName);
				}
				$mailTo = $mailToName = $mail['email'];
				$mailContent = str_replace('{email}',$mailTo,$mailContent);
				
				$mimemessage->add_html($mailContent);
				$mimemessage->build_message();
				
				if (EMAIL_TRANSPORT != 'smtp') {
					$mimemessage->send($mailToName, $mailTo, $FromEmail, $FromEmailName, $mailTitle, '',$charset);
				}else{
					if(!tep_not_null($FromEmail)){
						$sleeptime = strtotime("tomorrow")-time();
						sleep($sleeptime);//一直休眠到第二天再继续发送邮件
						clear_zero_sent_mail();
						sleep(5);//多5秒钟
						$FromEmail = get_mail_address();
					}
					tep_mail($mailToName, $mailTo, $mailTitle, $mailContent, $FromEmailName, $FromEmail, EMAIL_USE_HTML , $charset);
				}
				tep_db_query("DELETE From `products_soldout_email` where `products_id`='{$product_id}' and `email`='{$mailTo}' and `charset`='{$mail['charset']}';");
				sleep(1);
			}while($mail = tep_db_fetch_array($soldout_email));
			//删除正在发送的状态
			tep_db_query("DELETE From `products_soldout_email` where `products_id`='{$product_id}' and `email`='#soldout_sending#';");
			echo 'show_sendmail_msg(2);';
			exit;
		}
		
	}
}
echo 'show_sendmail_msg(3);';

function get_mail_address(){
	$smtp_mail_sql = tep_db_query('SELECT mail_id, mail_address,mail_date FROM `smtp_mail` WHERE mail_address Like "auto%@usitrip.com" and mail_max_send_num > mail_max_sent_num and action_status ="true" limit 1 ');
	$smtp_mail = tep_db_fetch_array($smtp_mail_sql);
	if(tep_not_null($smtp_mail['mail_address'])){
		tep_db_query('UPDATE `smtp_mail` SET `mail_max_sent_num` =  `mail_max_sent_num`+1 WHERE `mail_id` = "'.$smtp_mail['mail_id'].'" ');
		return trim($smtp_mail['mail_address']);
	}
	return false;
}
function clear_zero_sent_mail(){
	tep_db_query('UPDATE `smtp_mail` SET `mail_max_sent_num` = 0 WHERE mail_address Like "auto%@usitrip.com" ');
}
?>