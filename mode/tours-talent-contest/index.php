<?php
!defined('_MODE_KEY') && exit('Access error!');
unset($_SESSION['reg_method']); 
 /* 最新发布的作品列表 */
 $sql = "SELECT works_id, customers_id,works_title, works_frontcover, works_frontcover_thumb, works_addtime, works_author,works_content FROM  `daren_works`  WHERE works_is_view =1  ORDER BY works_addtime DESC LIMIT 6";
 $work_list_res = tep_db_query($sql);
 $data = array();
 $i = 0;
 while($works = tep_db_fetch_array($work_list_res)){
    
    $data['neworks'][$i]['works_id'] = $works['works_id'];
    $data['neworks'][$i]['works_title'] = strip_tags($works['works_title']);
    //$data['neworks'][$i]['works_title'] = cutword(strip_tags($works['works_title']),20);
    
    $works_frontcover = substr($works['works_frontcover'],1);
    
    if (file_exists($works_frontcover)){
        $data['neworks'][$i]['works_frontcover'] = $works['works_frontcover'];
    }else{
        $data['neworks'][$i]['works_frontcover'] = '';
    }
    
    $works_frontcover_thumb = substr($works['works_frontcover_thumb'],1); 
    if (file_exists($works_frontcover_thumb)){
        $data['neworks'][$i]['works_frontcover_thumb'] = $works['works_frontcover_thumb'];
    }else{
        $data['neworks'][$i]['works_frontcover_thumb'] = '';        
    }
    
    $data['neworks'][$i]['works_addtime'] = $works['works_addtime'];
    //$data['neworks'][$i]['works_author'] = iconv('utf-8','gb2312',$works['works_author']);
    $data['neworks'][$i]['works_author'] = $works['works_author'];
    $works_content = cutword(preg_replace('/[[:space:]]+/','',strip_tags(nl2br($works['works_content']))),52);
    $works_content = str_replace('　','',$works_content);
    $works_content = str_replace('&nbsp;','',$works_content);
    //$works_content = strip_tags(html_entity_decode(iconv('utf-8','gb2312',$works['works_content'])));
    $data['neworks'][$i]['works_content'] = $works_content;
    $data['neworks'][$i]['customers_id'] = $works['customers_id'];
    //$data['neworks'][$i]['works_content'] = cutword(iconv('utf-8','gb2312',strip_tags($works['works_content'])),35);
    //$data['neworks'][$i]['works_author'] = $works['works_author'];
    //$data['neworks'][$i]['works_content'] = cutword(strip_tags($works['works_content']),35);        
    $i++;
 }
 
 

 
 
 /* 人气排行列表 */
 $sql = "SELECT works_id, customers_id,works_title, works_frontcover, works_frontcover_thumb, works_addtime, works_votes, works_author,works_content FROM  `daren_works`  WHERE works_is_view =1  ORDER BY works_votes DESC LIMIT 6";
 $work_list_res = tep_db_query($sql);

 
 $i = 0;
 while($votes = tep_db_fetch_array($work_list_res)){
    $data['votes'][$i]['works_id'] = $votes['works_id'];
    $data['votes'][$i]['works_title'] = strip_tags($votes['works_title']);
    //$data['votes'][$i]['works_title'] = cutword(strip_tags($votes['works_title']),20);
    
    
    $works_frontcover = substr($votes['works_frontcover'],1);
    if (file_exists($works_frontcover)){
        $data['votes'][$i]['works_frontcover'] = $votes['works_frontcover'];
    }else{
        $data['votes'][$i]['works_frontcover'] = '';
    }
    
    $works_frontcover_thumb = substr($votes['works_frontcover_thumb'],1);
    if (file_exists($works_frontcover_thumb)){
        $data['votes'][$i]['works_frontcover_thumb'] = $votes['works_frontcover_thumb'];
    }else{
        $data['votes'][$i]['works_frontcover_thumb'] = '';        
    }
    
    $data['votes'][$i]['works_votes'] = $votes['works_votes'];
    $data['votes'][$i]['works_author'] = $votes['works_author'];
    $data['votes'][$i]['customers_id'] = $votes['customers_id'];
    $votes_content = cutword(preg_replace('/[[:space:]]+/','',strip_tags(nl2br($votes['works_content']))),52); 
    $votes_content = str_replace('　','',$votes_content); 
    $votes_content = str_replace('&nbsp;','',$votes_content); 
    $data['votes'][$i]['works_content']  = $votes_content;
    //$data['votes'][$i]['works_author'] = $votes['works_author'];
    //$data['votes'][$i]['works_content'] = cutword(strip_tags($votes['works_content']),35);     
    $i++;
 }
 if (tep_session_is_registered('customer_id')){
    $is_login =  true;
    //$navigation->reset();
    /*取得走四方达人相关信息*/
    $sql = "SELECT * FROM  `daren_user_info` WHERE customers_id=".(int)$customer_id;
    $account_query = tep_db_query($sql);
    $account_talent = tep_db_fetch_array($account_query);   
    if(tep_not_null($account_talent['blog_url'])){
        $tmp_info = true;        
    }
    
 }

 /*退出登录*/
 if ($_GET['action'] == 'logoff'){
      tep_session_unregister('customer_id');
      tep_session_unregister('customer_default_address_id');
      tep_session_unregister('customer_default_ship_address_id');
      tep_session_unregister('customer_first_name');
      tep_session_unregister('customer_country_id');
      tep_session_unregister('customer_zone_id');
      tep_session_unregister('customer_validation');
      //会员积分卡 begin
      tep_session_unregister('pointcards_id');
      tep_session_unregister('pointcards_id_string');
      //会员积分卡 end
      tep_session_unregister('comments');
      tep_session_unregister('affiliate_id');
      tep_session_unregister('affiliate_ref');
      tep_session_unregister('authorizenet_failed_cntr');
    //ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
      tep_session_unregister('gv_id');
      tep_session_unregister('cc_id');
    //ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
      
      #### Points/Rewards Module V2.1rc2a balance customer points EOF ####*/
      if (tep_session_is_registered('customer_shopping_points')) tep_session_unregister('customer_shopping_points');
      if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
      if (tep_session_is_registered('customer_referral')) tep_session_unregister('customer_referral');
      if (tep_session_is_registered('customer_review_process_without_login')) tep_session_unregister('customer_review_process_without_login');
      if (tep_session_is_registered('total_pur_suc_nos_of_cnt')) tep_session_unregister('total_pur_suc_nos_of_cnt');
      #### Points/Rewards Module V2.1rc2a balance customer points EOF ####*/
      if (tep_session_is_registered('last_place_order_authorized_id')) tep_session_unregister('last_place_order_authorized_id');
      $cart->reset();
      $navigation->reset();
      
    //howard added 

    tep_session_unregister('billto');
    tep_session_unregister('customer_email_address');
    tep_session_unregister('travel_companion_ids');
    tep_session_unregister('pay_order_id');
    tep_session_unregister('sendto');

    setcookie('LoginDate', '');
    
    tep_redirect($referer_url);
     
 }
 
 if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
	
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $email_address_bk = $email_address; //$email_address_bk 可以是手机号或者邮箱，没有改变原来id
    if (checkregemail($email_address_bk) == true) {
        $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, customers_default_ship_address_id, customers_validation, customers_state,customers_group from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address_bk) . "'");
    } else if (check_mobilephone_for_profile_update($email_address_bk)) {
        //$check_customer_query = tep_db_query("select customers_id, customers_firstname,customers_password, customers_email_address, customers_default_address_id, customers_default_ship_address_id, customers_validation, customers_state from " . TABLE_CUSTOMERS . " where customers_telephone LIKE '%" . tep_db_input($email_address_bk) . "'");
        $check_customer_query = tep_db_query("select customers_id, customers_firstname,customers_password, customers_email_address, customers_default_address_id, customers_default_ship_address_id, customers_validation, customers_state,customers_group from " . TABLE_CUSTOMERS . " where confirmphone = '" . tep_db_input($email_address_bk) . "'");

        //$row=tep_db_fetch_array($check_customer_query);
        //$email_address = $row['customers_email_address'];
//###会员积分卡登录支持  -----------BEGIN
//2011.3.30 vincent
    }elseif(is_pointcard($email_address_bk)){    	
    	$cardId = intval(str_ireplace('tff','',$email_address_bk));    	    
    	$member_point_card_query = tep_db_query('SELECT pointcards_id,password,UNIX_TIMESTAMP(expire_date) as exdate,UNIX_TIMESTAMP(active_date) as actdate FROM '. TABLE_POINTCARDS.' WHERE pointcards_id = '.$cardId);     	
    	$pointcards_info = tep_db_fetch_array($member_point_card_query);    	
    	tep_db_free_result($member_point_card_query);
    	$member_point_card_rel_query = tep_db_query('SELECT * FROM '. TABLE_POINTCARDS_TO_CUSTOMERS.' WHERE pointcards_id = '.$cardId); 
		$pointcards_rel_info = tep_db_fetch_array($member_point_card_rel_query);
		tep_db_free_result($member_point_card_rel_query);
		if(!tep_not_null($pointcards_info)){
			$pointcarderror = true;
			$error_msn = db_to_html('您的积分卡卡号或密码错误!');
			$error_target = 'Account';
		}else if(!tep_not_null($pointcards_rel_info)){//未使用
			if(intval($pointcards_info['exdate']) < time() || intval($pointcards_info['actdate'])>time()){
				$pointcarderror = true;
				$error_msn = db_to_html('您的积分已经过期或没有激活！');
				$error_target = 'Account';
			}elseif($pointcards_info['password'] != $password){
				$pointcarderror = true;
				$error_msn = db_to_html('您的积分卡卡号或密码错误!');
				$error_target = 'Account';
			}else{
				//积分卡第一次使用 引导用户进入积分卡注册用户界面
				$pointcards_code = vin_crypt($pointcards_info['pointcards_id'].":".$pointcards_info['password']) ;
				tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT,'pointcards_code='.urlencode($pointcards_code)));
				exit;
			}
		}else{//已经注册过了
			$check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, customers_default_ship_address_id, customers_validation, customers_state,customers_group from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($pointcards_rel_info['customers_id']) . "'");
		}
    	
//###会员积分卡登录支持 -----------END
    } else {
        $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, customers_default_ship_address_id, customers_validation, customers_state,customers_group from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address_bk) . "'");
    }
    

// Check if email exists
    //$check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, customers_default_ship_address_id, customers_validation, customers_state from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
//显示积分卡部分的错误 BEGIN
// 2011-3-31 vincent
    if ($pointcarderror ) {    	
     	 $error = true;
    	if ($ajax == 'true') {
	           $ajax_error = '[ERROR]' . $error_msn . '[/ERROR]';
	            echo $ajax_error;
	            exit;
	     }
    }
//显示积分卡部分的错误 END
    elseif (!tep_db_num_rows($check_customer_query)) {
        
        $error = true;
        $error_msn = db_to_html('不存在的用户名！您需要先注册');
        $error_target = 'Account';
        //if ($ajax == 'true') { 
            $ajax_error = '<script src="jquery-1.3.2/jquery-1.4.2.min.js"  type="text/javascript"></script>'; 
            $ajax_error .= '<script>
                jQuery(document).ready(function (){
                    jQuery(window.parent.document).find("#PasswordErrorMessage").html("<span class=\'errorTip\'>'.$error_msn.'</span>");
                    jQuery(window.parent.document).find("#PasswordErrorMessage").toggle();
                    jQuery(window.parent.document).find("#email").val("'.$email_address.'");
                });
            </script>';
            echo $ajax_error;
            exit;
        //}
    } else {
        $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
        if (!tep_validate_password($password, $check_customer['customers_password'])) {
            $error = true;
            $error_msn = db_to_html('密码错误！');
            $error_target = 'Password';
            //if ($ajax == 'true') {
              
                $ajax_error = '<script src="jquery-1.3.2/jquery-1.4.2.min.js"  type="text/javascript"></script>'; 
                $ajax_error .= '<script>
                jQuery(document).ready(function (){
                    jQuery(window.parent.document).find("#PasswordErrorMessage").html("<span class=\'errorTip\'>'.$error_msn.'</span>");
                    jQuery(window.parent.document).find("#PasswordErrorMessage").toggle();
                    jQuery(window.parent.document).find("#email").val("'.$email_address.'");
                });
                </script>';               
                echo $ajax_error;
                exit;
            //}
        } elseif ((int) $check_customer['customers_state'] < 1) {
            $error = true;
            $error_msn = db_to_html('此帐户已被冻结，要解冻请联系我们：0086-4006-333-926');
            $error_target = 'Account';
            //if ($ajax == 'true') {
                $ajax_error = '<script src="jquery-1.3.2/jquery-1.4.2.min.js"  type="text/javascript"></script>'; 
                $ajax_error .= '<script>
                jQuery(document).ready(function (){
                    jQuery(window.parent.document).find("#PasswordErrorMessage").html("<span class=\'errorTip\'>'.$error_msn.'</span>");
                    jQuery(window.parent.document).find("#PasswordErrorMessage").toggle();
                    jQuery(window.parent.document).find("#email").val("'.$email_address.'");
                });
                </script>';   
                echo $ajax_error;
                exit;
            //}
        } else {
            if (SESSION_RECREATE == 'True') {
                tep_session_recreate();
            }

            $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $check_customer['customers_id'] . "' and address_book_id = '" . (int) $check_customer['customers_default_address_id'] . "'");
            $check_country = tep_db_fetch_array($check_country_query);

            $customer_id = $check_customer['customers_id'];
            $customer_default_address_id = $check_customer['customers_default_address_id'];
            $customer_default_ship_address_id = $check_customer['customers_default_ship_address_id'];
            $customer_first_name = $check_customer['customers_firstname'];
            $customer_country_id = $check_country['entry_country_id'];
            $customer_zone_id = $check_country['entry_zone_id'];
            $customer_email_address = $check_customer['customers_email_address'];
            $customer_validation = $check_customer['customers_validation'];
            $affiliate_id = $check_customer['customers_id'];
			$customers_group=$check_customer['customers_group'];
            tep_session_register('customer_id');
            tep_session_register('customer_default_address_id');
            tep_session_register('customer_default_ship_address_id');
            tep_session_register('customer_first_name');
            tep_session_register('customer_country_id');
            tep_session_register('customer_zone_id');
            tep_session_register('customer_email_address');
            tep_session_register('customer_validation');
            tep_session_register('affiliate_id');
			tep_session_register('customers_group');
			
			//读取用户的最后一次登录时间
			$lastloginRow = tep_db_fetch_array(tep_db_query('SELECT customers_info_date_of_last_logon  FROM '.TABLE_CUSTOMERS_INFO." WHERE  customers_info_id = ".(int)$customer_id));
			$customer_lastlogin	= trim($lastloginRow['customers_info_date_of_last_logon']); 
			tep_session_register('customer_lastlogin');
			
			  //记住用户账号
			setcookie('user_remembered_accountname',$check_customer['customers_email_address'],time() + 3600*24*30*12 );
			/*if(tep_not_null($_POST['remember_me'])){
				 echo "<script>alert('Save cookie ".$check_customer['email_address']."');</script>";
				 setcookie('user_remembered_accountname',$check_customer['customers_email_address'],time() + 3600*24*30*12 );
			}else{
				 setcookie('user_remembered_accountname','',time() - 3600);
			}*/
			//
			
			/* 自动登录博客 */
			//$_SESSION['WordPressLogin'] = $WordPress->auto_login_user($email_address, $password);
			$WordPressLogin = $WordPress->auto_login_user($email_address, $password);
			tep_session_register('WordPressLogin');

            // write favorites;
            auto_add_session_to_favorites();

            //howard added need create user for customers if table "user" no this customers.
            if (!get_user_id($customer_id)) {
                $sql_data_array = array();
                $sql_data_array = array('customers_id' => (int) $customer_id,
                    'user_nickname' => $customer_first_name,
                    'user_score_total' => FIRST_LOGIN_SCORE);
                tep_db_perform('user', $sql_data_array);

                //update user_score_history
                update_user_score($customer_id, FIRST_LOGIN_SCORE, FIRST_LOGIN, 'false');
            }
            //会员积分卡 begin
            $is_point_card_user = false ;
            if((int)$customer_id){
            	$member_point_card_rel_query = tep_db_query('SELECT * FROM '. TABLE_POINTCARDS_TO_CUSTOMERS.' WHERE customers_id = '.$customer_id);
				$pointcards_rel_info = tep_db_fetch_array($member_point_card_rel_query);
				if(tep_not_null($pointcards_rel_info)){
					$pointcards_id = $pointcards_rel_info['pointcards_id'];
			      	$pointcards_id_string = format_pointcard($pointcards_id);
			      	tep_session_register('pointcards_id');
			      	tep_session_register('pointcards_id_string');
			      	$is_point_card_user = true ;
			      	//加分
			      	$lastlogin = tep_db_fetch_array(tep_db_query('SELECT DATE_FORMAT(customers_info_date_of_last_logon,\'%Y%m%d\') AS lastlogin FROM '.TABLE_CUSTOMERS_INFO." WHERE  customers_info_id = ".$customer_id));
			      	//print_r($lastlogin);
			      	if(tep_not_null($lastlogin))
			      		$isfirstlogin = (intval($lastlogin['lastlogin']) < intval(date('Ymd',time())) );
			      	else
			      		$isfirstlogin = true;
			      	
			      	if($isfirstlogin){
			      		tep_add_pending_points_pointcard_login($customer_id);
			      	}
				}
				
            }
            //会员积分卡 end
            
            if (!tep_not_null($_COOKIE['every_day_login'])) {
                /* every day login +5 */
                update_user_score($customer_id, EVERY_DAY_LOGIN_SCORE, EVERY_DAY_LOGIN);                
            }
            //howard added need create user for customers if table "user" no this customers. end
            //howard added write login date cookie to local
            //setcookie('LoginDate', date('Ymd'), time() +(3600*24 ));
            setcookie('LoginDate', date('Ymd'));
            //howard added write login date cookie to local end


            $royal_customers_query_raw = tep_db_query("select orders_id from " . TABLE_ORDERS . " o, orders_status s where o.orders_status = s.orders_status_id and s.language_id = '" . (int) $languages_id . "' and o.orders_status='100006' and o.customers_id='" . $customer_id . "' ");

            if (tep_db_num_rows($royal_customers_query_raw) > 0) {
                //echo tep_draw_hidden_field('gv_redeem_code_royal_customer_reward', md5($osCsid.$customer_id));
                /* $repeat_royal_customer_discount = 'apply_discount';
                  tep_session_register('repeat_royal_customer_discount'); */

                $total_pur_suc_nos_of_cnt = tep_db_num_rows($royal_customers_query_raw) + 1;
                tep_session_register('total_pur_suc_nos_of_cnt');
            } else {
                $total_pur_suc_nos_of_cnt = 1;
                tep_session_register('total_pur_suc_nos_of_cnt');
            }

            //amit added to check if user exits in affiliate table start
            $check_affilate = "select affiliate_id from " . TABLE_AFFILIATE . " where affiliate_id=" . $affiliate_id;
            $check_affilate_query = tep_db_query($check_affilate);
            if (!tep_db_num_rows($check_affilate_query)) {
                $sql_data_array = array('affiliate_id' => $affiliate_id);
                $sql_data_array['affiliate_lft'] = '1';
                $sql_data_array['affiliate_rgt'] = '2';
                tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
                tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $affiliate_id . "' ");

                /*
                  if(isset($HTTP_SESSION_VARS['affiliate_ref'])){
                  $testaffiliate_id = affiliate_insert ($sql_data_array, $HTTP_SESSION_VARS['affiliate_ref']);
                  }else{
                  $sql_data_array['affiliate_lft'] = '1';
                  $sql_data_array['affiliate_rgt'] = '2';
                  tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
                  tep_db_query ("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $affiliate_id . "' ");
                  }
                 */
            }
            //amit added to check if user exits in affilite table end

            tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int) $customer_id . "'");
            tep_db_query("update " . TABLE_CUSTOMERS . " set  customers_char_set = '" . CHARSET . "' where customers_id = '" . (int) $customer_id . "' ");
			
			
            // restore cart contents
            $cart->restore_contents();
            $ajax_error = '<script src="jquery-1.3.2/jquery-1.4.2.min.js"  type="text/javascript"></script>'; 
            $ajax_error .= '<script>
                jQuery(document).ready(function (){
                   parent.location.href="trip_player.php";
                });
                </script>';               
            echo $ajax_error;
        }
    }
}
 
 if ($_GET['focus'] == 'true'){
    $smarty->assign('focus',$_GET['focus']);
 }
 
 $smarty->assign('cru_host',$_SERVER['SERVER_NAME']);
 $smarty->assign('is_login',$is_login);
 $smarty->assign('tmp_info',$tmp_info);
 $smarty->assign('votes',$data['votes']);
 $smarty->assign('neworks',$data['neworks']);
 
?>