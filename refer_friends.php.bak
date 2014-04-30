<?php
/*
  $Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_REFER_A_FRIEND);
  
  // 网站联盟开关
  if (strtolower(AFFILIATE_SWITCH) === 'false') {
  	echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
  	exit();
  }
  
  if (!tep_session_is_registered('customer_id')) {
    $messageStack->add_session('login', NEXT_NEED_SIGN); 
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  checkAffiliateVerified();
  
  $referProducts = getAffiliateAllProducts(100);
  //print_r($referProducts);

 function RTESafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = trim($strText);
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), "<br />", $tmpString);
	return $tmpString;
}

 //发送邮件{
 if(isset($_POST['action']) && $_POST['action'] = 'process' ) {
 
	$add_points = 'true';
	$effective_points = 0;
	$max_mail_num = EVERY_DAY_POINTS_EMAIL_NUM;  //设置当天能发邮件的总个数，超过此数将不再计分(QA站2封，生产站50)
	
	$to_date = date('Y-m-d');
	
	for($i=1; $i<10; $i++){
		
		$check_max_sql = tep_db_query('SELECT count(*) as total FROM '.TABLE_REBATES_REFERRALS_INFO.' WHERE customers_id = "'.$customer_id.'" AND referrals_date 
		Like "'.$to_date.'%" ');
		$check_max_row = tep_db_fetch_array($check_max_sql);
		if($check_max_row['total'] > $max_mail_num){
			$add_points = 'false';
		}
		
		if(tep_not_null($_POST['refer_frd_email_'.$i]) ){
			$referrals_email = $_POST['refer_frd_email_'.$i];
			//insert in to referal table start
			
			 $sql_data_array_info = array( 'referrals_email' => $referrals_email,
								'customers_id' =>   $customer_id,
								'referrals_date' => 'now()',
								'referrals_status' => '0',
								);
		  
		  //check for resend start
		 
		  $check_customer_query = tep_db_query("select * from " . TABLE_REBATES_REFERRALS_INFO . " where customers_id = '".$customer_id."' and referrals_email = '".$referrals_email."'");
		
			if (!tep_db_num_rows($check_customer_query)) {
				$sql_data_array_info = html_to_db ($sql_data_array_info);
				tep_db_perform(TABLE_REBATES_REFERRALS_INFO, $sql_data_array_info);
				$referrals_id = tep_db_insert_id();
				if (tep_not_null(USE_REFERRAL_SYSTEM) && $add_points == 'true') {
				  $points_toadd = USE_REFERRAL_SYSTEM;
				  $points_comment = 'TEXT_DEFAULT_REFERRAL';
				  $points_type = 'RF';
				  $points_unique_id = (int) tep_add_pending_points($customer_id, 0, $points_toadd, $points_comment, $points_type);
				  $effective_points++;
			   }
			 } else {
					$customer_referrals = tep_db_fetch_array($check_customer_query);
					$referrals_id = $customer_referrals['referrals_id'];
			 }
		  //check for resend end
		  
		  //insert in to referal table end
		  //send mail to refer start
			$fname = $_POST['fname'];
			$fromemail = $_POST['email_address'];
			
			$subject = sprintf(TEXT_RECOMMEND_YOU_A_TOUR,$fname);
			
		  $message = '';
		
			if($_POST['msg_to_friends'] != ''){
			$msg_to_friends = $_POST['msg_to_friends'];
			$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
					"<tr>". 
					 "<td valign='top' colspan='2'>".RTESafe(stripslashes($msg_to_friends))."</td>".
					"</tr>".
					"</table>";
			}
			
			if(isset($_POST['products_id']) && $_POST['products_id'] != ''){
					$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
					"<tr>".
					"<td valign=top'><br />".db_to_html(tep_get_refer_friend_products_link($_POST['products_id'],$customer_id,$points_unique_id))."</td>".
	
					"</tr>".
					"</table>"; 
			}else if(isset($_POST['cPath']) && $_POST['cPath'] != ''){
					$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
					"<tr>".
					"<td valign=top'><br />".db_to_html(tep_get_refer_friend_categories_link($_POST['cPath'],$customer_id,$points_unique_id))."</td>".
					"</tr>".
					"</table>"; 
			}else{		
				
					if(isset($_POST['products1']) && $_POST['products1'] != '0' ){				
						$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
						"<tr>".
						"<td valign=top'><br />".db_to_html(tep_get_refer_friend_products_link($_POST['products1'],$customer_id,$points_unique_id))."</td>".
						"</tr>".
						"</table>"; 				
					}else if(isset($_POST['cPath1']) && $_POST['cPath1'] != '0'){
						$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
						"<tr>".
						"<td valign=top'><br />".db_to_html(tep_get_refer_friend_categories_link($_POST['cPath1'],$customer_id,$points_unique_id))."</td>".
	
						"</tr>".
						"</table>"; 
					}else{			
						$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
						"<tr>".
						"<td valign=top'><br /><a  href='".HTTP_SERVER . DIR_WS_HTTP_CATALOG. FILENAME_DEFAULT ."?ref=$customer_id&affiliate_banner_id=1'><b>" . TEXT_VISIT . " TorusForFun</b></a></b></td>".
						"</tr>".
						"</table>"; 
					}
					
			}		
			
			$message .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
					"<tr>".
					"<td valign='top' colspan='2'><br /><br /></td>".
					"</tr>".
					"<tr>".
					"<td valign='top' colspan='2'>".TEXT_FOOT_GREETINGS."&nbsp;&nbsp;&nbsp;&nbsp;$fname.</td>".
					"</tr>".
					"</table>";
		
			$message = stripcslashes($message);		
			//mail($referrals_email,$subject,$message,$headers); 
			
	// zhh fix and added
			
			$to_name = $_POST['refer_frd_name_'.$i];
			if(!tep_not_null($to_name)){
				$to_name = $fname.db_to_html('的朋友 ');
			}
			
			$to_email_address = $referrals_email;
			$email_subject = $subject.' ';
			$email_text = $message;
			
			$from_email_name = $fname.' ';
			$from_email_address = $fromemail;
			
			tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
	
			//echo $referrals_email
			//send mail to refer end 
			$sendmsg = "sendture";
			
			$sentIinfo['fnames'].= '<b>'.$to_name.'</b>&nbsp;';
			$sentIinfo['msg'] = $msg_to_friends;
		}
		
	}//end of for loop

	$AffiliateInfo = getAffiliateInfo($affiliate_id);
	$sentIinfo['percent'] = tep_get_affiliate_percent_display();
 }
 
 //发送邮件}

  //推荐目录{
	$rfCats = array();
	$rfCats[] = array('id'=>24,'name'=>'美西');
	$rfCats[] = array('id'=>25,'name'=>'美东');
	$rfCats[] = array('id'=>33,'name'=>'夏威夷');
	$rfCats[] = array('id'=>34,'name'=>'佛罗里达');
	$rfCats[] = array('id'=>54,'name'=>'加拿大');
	$rfCats[] = array('id'=>208,'name'=>'拉丁美洲');
	$rfCats[] = array('id'=>157,'name'=>'欧洲');
	$rfCats[] = array('id'=>195,'name'=>'日本');
	for($i=0, $n=sizeof($rfCats); $i < $n; $i++){
		//$rfCats[$i]['child'] = tep_get_category_tree($rfCats[$i]['id']);
		$cSql = tep_db_query('SELECT c.categories_id, cd.categories_name FROM `categories` c, `categories_description` cd 
							 WHERE c.categories_id=cd.categories_id and c.categories_status="1" and c.parent_id="'.$rfCats[$i]['id'].'" and cd.language_id="1" ORDER BY sort_order Limit 500');
		while($cRows = tep_db_fetch_array($cSql)){
			$rfCats[$i]['child'][] = array('id'=>$cRows['categories_id'], 'name'=>preg_replace('/ .+$/','',trim($cRows['categories_name'])));
		}
	}
	//print_r($rfCats);
  //推荐目录}
  

  $breadcrumb->add(NAVBAR_TITLE1, tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'));
  
  $validation_include_js = 'true';
  $content = CONTENT_REFER_FRIEND;
  $javascript = $content . '.js';
  $is_my_account = true;
  $validation_div_span = 'span';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
