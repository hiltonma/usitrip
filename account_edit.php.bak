<?php
/*
  $Id: account_edit.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);
  $tabId = 'process';
  if($_GET['action'] == 'change_password')   $tabId = 'change_password';
  elseif($_GET['action'] == 'upload_avatar')   $tabId = 'upload_avatar';
  elseif($_GET['action'] == 'edit_profile')   $tabId = 'edit_profile';

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) { 
    //验证
    $sql_data_array = array();
  	$error = false;
  	//gender
    if(isset($HTTP_POST_VARS['gender']) && in_array($HTTP_POST_VARS['gender'],array('f','m'))){
    	$sql_data_array['customers_gender'] = $HTTP_POST_VARS['gender'];
    }
    //dob
  	if(isset($HTTP_POST_VARS['customers_dob'])){
    	$dob = tep_db_prepare_input($HTTP_POST_VARS['customers_dob']);
    	/*if(checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))){
    		$sql_data_array['customers_dob'] = tep_date_raw($dob);
    	} */
    	$sql_data_array['customers_dob'] = $dob ;
    }
    //dob_secret
    if(isset($HTTP_POST_VARS['dob_secret']) && is_numeric($HTTP_POST_VARS['dob_secret'])){
    	$dob_secret = intval(tep_db_prepare_input($HTTP_POST_VARS['dob_secret']));
    	$sql_data_array['dob_secret'] = $dob_secret;
    }   
	$email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
	 $mobile_phone = tep_db_prepare_input($HTTP_POST_VARS['mobile_phone']);

	if(isset($HTTP_POST_VARS['email_address']) && $HTTP_POST_VARS['email_address'] == ''){
		$error = true;
		$edit_to_email_address = $account['customers_email_address'];
		$messageStack->add('account_edit',db_to_html('注册邮箱不能为空，请填写您的注册邮箱，用于登陆走四方网。'),'error');
	}else{	
		//email
		//$account = tep_get_customers_info( (int)$customer_id );	
		$account = tep_get_customers_info_fix((int)$customer_id);
		$edit_to_email_address = $email_address;
		if($email_address != '' && $account['customers_email_address'] != html_to_db($email_address))
		{
			if($email_address != ''){
				$msg = tep_validate_email_for_register($email_address);
				if($msg== ''){
					$sql_data_array['customers_email_address'] = $email_address;
					$edit_to_email_address = $email_address;
				}else{
					$error = true;
					$messageStack->add('account_edit', $msg);
				}
			}
		}
	}

	$firstname = $_POST['firstname'];
	$reg_arr = reg_filter();
	foreach($reg_arr as $val) {
		if (strpos($firstname,$val) !== false) {
			$error = true;
			$messageStack->add('account_edit', db_to_html("有人与您使用相同姓名，您必须换一个名称。"));
			break;
		}
	}
	
	$temp_email = html_to_db($email_address);
	reset($reg_arr);
	foreach($reg_arr as $val) {
		if (strpos($temp_email,$val) !== false) {
			$error = true;
			$messageStack->add('account_edit', db_to_html("这个电子邮箱已经注册过!请确认或换一个电子邮箱。"));
			break;
		}
	}
	if( $mobile_phone!='' && !check_mobilephone_for_profile_update($mobile_phone)){
		$error = true;	
		$messageStack->add('account_edit',db_to_html('您输入的手机号码无效。'),'error');
	}
	
    if ($error == false) {
      //处理用户详细地址更新
      $sql_address_data_array = array(
      						'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
      						'entry_country_id' => tep_db_prepare_input($HTTP_POST_VARS['country']),
      						'entry_state'=>tep_db_prepare_input($HTTP_POST_VARS['state']),
      						'entry_zone_id'=>tep_db_prepare_input($HTTP_POST_VARS['zone_id']),
      						'entry_city'=>tep_db_prepare_input($HTTP_POST_VARS['city']),
      						'entry_postcode'=>tep_db_prepare_input($HTTP_POST_VARS['postcode']),
      						'entry_street_address'=>tep_db_prepare_input($HTTP_POST_VARS['street_address'])
      );
      $sql_address_data_array = html_to_db ($sql_address_data_array);     
      if(is_numeric($account['customers_default_address_id']) && !empty($account['_default_address']) ){ ///检查是否包含默认地址没有则创建一个 
      	 tep_db_perform(TABLE_ADDRESS_BOOK, $sql_address_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$account['customers_default_address_id'] . "'");
      }else{
      	 $sql_address_data_array['customers_id'] = intval($customer_id);
      	 tep_db_perform(TABLE_ADDRESS_BOOK, $sql_address_data_array, 'insert');
      	 $customers_default_address_id = tep_db_insert_id();
      	 $sql_data_array['customers_default_address_id'] = $customers_default_address_id;
      	  $sql_data_array['customers_default_ship_address_id'] = $customers_default_address_id;
      }
      //用户地址更新结束
      $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
      $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
     
      $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
	
      $sql_data_array['customers_firstname'] = $firstname;
      $sql_data_array['customers_lastname'] = $lastname;
      $sql_data_array['customers_mobile_phone'] = $mobile_phone;
      $sql_data_array['customers_fax'] = $fax;     
      //电子报订阅
	  /*
	    if(isset($HTTP_POST_VARS['newsletter']) && $HTTP_POST_VARS['newsletter'] == '1'){    
	    	$sql_data_array['customers_newsletter'] = '1' ;
	    }else 
	    	$sql_data_array['customers_newsletter'] = '0' ;*/
      
      
      //检查用户验证情况 by vincent
      if($account['customers_validation'] == 1 || $account['customers_validation_code'] != ""){ 
      	if(trim($account['customers_email_address']) != $edit_to_email_address){
      		//已经验证通过的用户和正在验证中的用户如果修改了 email地址 则设置其验证状态为未验证
      		 $sql_data_array['customers_validation'] = 0;
      		 $sql_data_array['customers_validation_code'] = '';
      	}
      }
      
      $sql_data_array['customers_notice'] = $customers_notice; //旧版使用该字段
     /*
        if(tep_not_null($confirmphone)&&check_mobilephone_for_profile_update($confirmphone)){
              $sql_data_array = array('customers_firstname' => $firstname,
                                      'customers_lastname' => $lastname,
                                      'customers_email_address' => $email_address,
                                      'customers_telephone' => $telephone,
                                      'customers_mobile_phone'=>$mobile_phone,
                                      'confirmphone' => $confirmphone,
                                      'customers_notice' => $customers_notice,
                                      'customers_fax' => $fax);
        }else{
            $sql_data_array = array('customers_firstname' => $firstname,
                                      'customers_lastname' => $lastname,
                                      'customers_email_address' => $email_address,
                                      'customers_telephone' => $telephone,
                                      'customers_mobile_phone'=>$mobile_phone,
                                      'customers_notice' => $customers_notice,
                                      'customers_fax' => $fax);

        }*/

      //print_r($sql_data_array);
      $sql_data_array = html_to_db ($sql_data_array);
	  tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");
      
	  //更新affiliate邮箱地址
	  update_affiliate_customers_email_address($email_address);
	  
	 // reset the session variables
      $customer_first_name = html_to_db($firstname);
      $customer_email_address = html_to_db($email_address);//change session
      
      //$messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');
	 $addTips = tep_not_null($sql_data_array['customers_email_address']) ?  '请牢记您修改后的邮箱，下次登录将用此邮箱登录，密码保持不变。':'';
      $messageStack->add('account_edit',db_to_html('恭喜您,成功保存信息。'.$addTips),'success');
      //tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));     
    }
  }else if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'change_password')) {
  	$tabId = 'change_password';
  	 require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_PASSWORD);
    $password_current = tep_db_prepare_input($HTTP_POST_VARS['password_current']);
    $password_new = tep_db_prepare_input($HTTP_POST_VARS['password_new']);
    $password_confirmation = tep_db_prepare_input($HTTP_POST_VARS['password_confirmation']);
    $error = false;
    if (strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_PASSWORD_CURRENT_ERROR,'error','verify_password_current');
    } elseif (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_PASSWORD_NEW_ERROR,'error','verify_password_new');
    } elseif ($password_new != $password_confirmation) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING,'error','verify_password_confirmation');
    }
    if ($error == false) {
      $check_customer_query = tep_db_query("select customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);
      if (!tep_validate_password($password_current, $check_customer['customers_password'])) {
        $error = true;
        $messageStack->add('account_edit', ERROR_CURRENT_PASSWORD_NOT_MATCHING,'error','verify_password_current');
      }else{
      	tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_encrypt_password($password_new) . "' where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");
        $messageStack->add('account_edit', db_to_html('恭喜您,密码修改成功。'), 'success');
        //tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
      }
    }
  }
 // echo tep_encrypt_password("11111");
//读取用户个人资料数据
  //$account_query = tep_db_query("select customers_gender,customers_mobile_phone,customers_validation, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone,confirmphone,customers_fax,customers_notice from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  //$account = tep_db_fetch_array($account_query);
  $account = tep_get_customers_info_fix( (int)$customer_id );

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(db_to_html('完善个人资料'), tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));

  $validation_include_js = 'true';
  
  $content = CONTENT_ACCOUNT_EDIT;
  $javascript = 'account_edit.js.php';
  $is_my_account = true;
  $BreadOff=true;
 $HideDateSelectLayer = true;
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
  //tep_statistics_addpage(tep_current_url('',true).'?version='.USE_OLD_VERSION,$customer_id);//记录页面访问情况
?>
