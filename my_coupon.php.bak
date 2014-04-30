<?php
/*
  $Id: my_points.php, V2.1rc2a 2008/OCT/01 16:04:22 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

	require('includes/application_top.php');
	
	if (!tep_session_is_registered('customer_id')) {
		$navigation->set_snapshot();
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}
	
	require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_MY_COUPONS);
	
	//激活优惠券 start
	if(tep_not_null($_POST["coupon_code"])){
		if(isset($_POST['ajax'])){
			header("Content-type: text/html; charset=".CHARSET);
		}
		$check_sql = tep_db_query('SELECT * FROM `coupons` WHERE coupon_code="'.tep_db_prepare_input($_POST["coupon_code"]).'" and need_customers_active="1" and coupon_active="Y" ');
		$check_row = tep_db_fetch_array($check_sql);
		$error = false;
		$error_msn = NULL;
		$success_msn = NULL;
		
		if(!(int)$check_row['coupon_id']){
			$error = true;
			$error_msn = "不存在的优惠券或此券无需激活！";
		}
		$tmp_array = @explode(',',$check_row['restrict_to_customers']);
		if($error == false && tep_not_null($check_row['restrict_to_customers']) && !in_array($customer_id, $tmp_array) && strtolower($_POST["coupon_code"])!="fmtf2"){
			$error = true;
			$error_msn = "此优惠券已经被其他客人激活，您不能使用此优惠券！";
		}
		if($error == false && in_array($customer_id, $tmp_array)){
			$error = true;
			$error_msn = "您已经激活此优惠券，请勿重复激活！";
		}
		if($error == false && $check_row['coupon_expire_date'] < date("Y-m-d H:i:s")){
			$error = true;
			$error_msn = "很抱歉！您的优惠券已失效，请您及时关注走四方网最新优惠活动。";
		}
		
		if($error == false){
			$restrict_to_customers = $customer_id.",".$check_row['restrict_to_customers'];
			$restrict_to_customers = preg_replace('/,$/','',$restrict_to_customers);
			tep_db_query("update `coupons` set restrict_to_customers='{$restrict_to_customers}' WHERE coupon_id={$check_row['coupon_id']} ");
			$success_msn = "恭喜您的优惠券已经激活，请您在有效期内使用！";
			if(substr($check_row['coupon_amount'],-1)=="%"){
				$Yuan = $check_row['coupon_amount'];
			}else{
				$Yuan = str_replace('$',preg_quote('$'),$currencies->format($check_row['coupon_amount']));
			}
			$ExpireDate = substr($check_row['coupon_expire_date'],0,10);
			
			//写通知邮件
			//howard added new eamil tpl
			$patterns = array();
			$patterns[0] = '{CustomerName}';
			$patterns[1] = '{images}';
			$patterns[2] = '{HTTP_SERVER}';
			$patterns[3] = '{ToDate}';
			$patterns[4] = '{Yuan}';
			$patterns[5] = '{EMAIL}';
			$patterns[6] = '{ExpireDate}';
			$patterns[7] = '{CONFORMATION_EMAIL_FOOTER}';
			
			$to_name = db_to_html(tep_customers_name($customer_id))." ";
			$to_email_address = tep_get_customers_email($customer_id);
			$from_email_address = "automail@usitrip.com";
			$from_email_name = db_to_html("走四方网 ");
			$email_subject = db_to_html($success_msn).$from_email_name;

			$replacements = array();
			$replacements[0] = $to_name;
			$replacements[1] = HTTP_SERVER.'/email_tpl/images';
			$replacements[2] = HTTP_SERVER;
			$replacements[3] = date("Y-m-d");
			$replacements[4] = $Yuan;
			$replacements[5] = $to_email_address;
			$replacements[6] = $ExpireDate;
			$replacements[7] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
			
			$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
			$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/coupon_active_success.tpl.html');
			$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
			
			$email_tpl = preg_replace('/'.preg_quote('<!--','/').'(.+)'.preg_quote('-->','/').'/','',$email_tpl);
			$email_tpl = preg_replace('/[[:space:]]+/',' ',$email_tpl);
			$email_text = str_replace( $patterns ,$replacements, db_to_html($email_tpl));
			//howard added new eamil tpl end
			
			//tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
			
			//howard add use session+ajax send email
			$var_num = count($_SESSION['need_send_email']);
			$_SESSION['need_send_email'][$var_num]['to_name'] = $to_name;
			$_SESSION['need_send_email'][$var_num]['to_email_address'] = $to_email_address;
			$_SESSION['need_send_email'][$var_num]['email_subject'] = $email_subject;
			$_SESSION['need_send_email'][$var_num]['email_text'] = $email_text;
			$_SESSION['need_send_email'][$var_num]['from_email_name'] = $from_email_name;
			$_SESSION['need_send_email'][$var_num]['from_email_address'] = $from_email_address;
			$_SESSION['need_send_email'][$var_num]['action_type'] = 'true';
			$_SESSION['need_send_email'][$var_num]['email_charset'] = CHARSET;
			
			//howard add use session+ajax send email end
		}
		
		if(isset($_POST['ajax'])){	//JS提示
			if($error == true){
				$JS = '
				[JS]
				$("#active_coupon_msn").html("<span style=color:#F00;>&nbsp;'.db_to_html($error_msn).'</span>");
				$("#active_coupon_msn").fadeIn(200);
				$("#active_coupon_msn").fadeOut(2000);
				[/JS]
				';
			}else{
				$new_sql = tep_db_query('SELECT * FROM `coupons` c, `coupons_description` cd WHERE c.coupon_id="'.$check_row['coupon_id'].'" and cd.coupon_id = c.coupon_id AND language_id="1" ');
				$new_row = tep_db_fetch_array($new_sql);
				$c_code = $new_row['coupon_code'];
				$c_amount = $currencies->format($new_row['coupon_amount']);
				$start_date = substr($new_row['coupon_start_date'],0,10);
				$expire_date = substr($new_row['coupon_expire_date'],0,10);
				$use_range = db_to_html($new_row['use_range']);
				$this_status = db_to_html("可用");

				$JS = '
				[JS]
				$("#active_coupon_msn").html("<span style=color:#090;>&nbsp;'.db_to_html($success_msn).'</span>");
				$("#active_coupon_msn").fadeIn(200);
				$("#active_coupon_msn").fadeOut(2000);
				
				var ta = document.getElementById("coupon_list_table");
				var tr = ta.insertRow(1);
				if(!document.all){
					tr.innerHTML = "<td>'.$c_code.'</td><td>'.$c_amount.'</td><td>'.$start_date.'</td><td>'.$use_range.'</td><td>'.$expire_date.'</td><td>'.$this_status.'</td>";
				}else{
					var c = tr.insertCell();
					c.innerHTML = "'.$c_code.'";
					c = tr.insertCell();
					c.innerHTML = "'.$c_amount.'";
					c = tr.insertCell();
					c.innerHTML = "'.$start_date.'";
					c = tr.insertCell();
					c.innerHTML = "'.$use_range.'";
					c = tr.insertCell();
					c.innerHTML = "'.$expire_date.'";
					c = tr.insertCell();
					c.innerHTML = "'.$this_status.'";
				}
				;
				[/JS]
				';
			}
			echo preg_replace('/[[:space:]]+/',' ',$JS);
			exit;
		}else{	//普通提示
			if($error == true){
				$messageStack->add('global', db_to_html($error_msn));
			}else{
				$messageStack->add_session('global', db_to_html($success_msn), 'success');
				tep_redirect(tep_href_link('my_coupon.php', '', 'SSL'));
			}
		}
	}
	//激活优惠券 end
	
	$breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php', '', 'SSL'));
	$breadcrumb->add(NAVBAR_TITLE1, tep_href_link(FILENAME_MY_COUPONS, '', 'SSL'));
	
	//取得我已激活的优惠券 start
	$my_coupons_sql = tep_db_query('SELECT * FROM `coupons` c, `coupons_description` cd WHERE cd.coupon_id = c.coupon_id AND language_id="1" AND coupon_active="Y" AND need_customers_active="1" AND FIND_IN_SET("'.$customer_id.'",restrict_to_customers) AND coupon_type="F" Order By coupon_expire_date DESC ');
	//$my_coupons_sql = tep_db_query('SELECT * FROM `coupons` c, `coupons_description` cd WHERE cd.coupon_id = c.coupon_id AND language_id="1" AND coupon_active="Y" AND coupon_type="F" Order By coupon_expire_date DESC Limit 1000 ');
	$HtmlMyCoupons = NULL;	//用于html输出的数组
	while($my_coupons = tep_db_fetch_array($my_coupons_sql)){
		$this_status = "可用";	//判断不可用的状态有已过期，未开始，使用次数已达到上限。
		if($my_coupons['coupon_start_date']>=date("Y-m-d H:i:s")){
			$this_status = '<span style="color:#CCCCCC" title="未到生效日期">不可用</span>';
		}
		if($my_coupons['coupon_expire_date']<=date("Y-m-d H:i:s")){
			$this_status = '<span style="color:#CCCCCC" title="已经过期">不可用</span>';
		}
		//check used count
 		$coupon_count = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $my_coupons['coupon_id']."'");
 		$coupon_count_customer = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $my_coupons['coupon_id']."' and customer_id = '" . $customer_id . "'");

        if (tep_db_num_rows($coupon_count)>=$my_coupons['uses_per_coupon'] && $my_coupons['uses_per_coupon'] > 0) {
			$this_status = '<span style="color:#CCCCCC" title="已到使用上限">不可用</span>';
        }

        if (tep_db_num_rows($coupon_count_customer)>=$my_coupons['uses_per_user'] && $my_coupons['uses_per_user'] > 0) {
			$this_status = '<span style="color:#CCCCCC" title="您已经使用此券">不可用</span>';
        }
		
		$HtmlMyCoupons[]=array( 'id'=>$my_coupons['coupon_id'],
								'code'=>$my_coupons['coupon_code'],
								'amount'=>$currencies->format($my_coupons['coupon_amount']),
								'start_date'=>substr($my_coupons['coupon_start_date'],0,10),
								'expire_date'=>substr($my_coupons['coupon_expire_date'],0,10),
								'use_range'=>db_to_html($my_coupons['use_range']),
								'status'=>db_to_html($this_status)
								);
	}
	
	//取得我已激活的优惠券 end
	$content = CONTENT_MY_COUPONS;
	$javascript = CONTENT_MY_COUPONS.".js.php";
	$is_my_account = true;
	
	require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	
	require(DIR_FS_INCLUDES . 'application_bottom.php');
?>