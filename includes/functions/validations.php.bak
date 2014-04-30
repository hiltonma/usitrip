<?php
/*
  $Id: validations.php,v 1.1.1.1 2004/03/04 23:40:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_validate_email
  //
  // Arguments   : email   email address to be checked
  //
  // Return      : true  - valid email address
  //               false - invalid email address
  //
  // Description : function for validating email address that conforms to RFC 822 specs
  //
  //               This function is converted from a JavaScript written by
  //               Sandeep V. Tamhankar (stamhankar@hotmail.com). The original JavaScript
  //               is available at http://javascript.internet.com
  //
  // Sample Valid Addresses:
  //
  //    first.last@host.com
  //    firstlast@host.to
  //    "first last"@host.com
  //    "first@last"@host.com
  //    first-last@host.com
  //    first.last@[123.123.123.123]
  //
  // Invalid Addresses:
  //
  //    first last@host.com
  //
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_validate_email($email) {
    $valid_address = true;

    $mail_pat = '^(.+)@(.+)$';
    $valid_chars = "[^] \(\)<>@,;:\.\\\"\[]";
    $atom = "$valid_chars+";
    $quoted_user='(\"[^\"]*\")';
    $word = "($atom|$quoted_user)";
    $user_pat = "^$word(\.$word)*$";
    $ip_domain_pat='^\[([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\]$';
    $domain_pat = "^$atom(\.$atom)*$";

    if (eregi($mail_pat, $email, $components)) {
      $user = $components[1];
      $domain = $components[2];
      // validate user
      if (eregi($user_pat, $user)) {
        // validate domain
        if (eregi($ip_domain_pat, $domain, $ip_components)) {
          // this is an IP address
      	  for ($i=1;$i<=4;$i++) {
      	    if ($ip_components[$i] > 255) {
      	      $valid_address = false;
      	      break;
      	    }
          }
        }
        else {
          // Domain is a name, not an IP
          if (eregi($domain_pat, $domain)) {
            /* domain name seems valid, but now make sure that it ends in a valid TLD or ccTLD
               and that there's a hostname preceding the domain or country. */
            $domain_components = explode(".", $domain);
            // Make sure there's a host name preceding the domain.
            if (sizeof($domain_components) < 2) {
              $valid_address = false;
            } else {
              $top_level_domain = strtolower($domain_components[sizeof($domain_components)-1]);
              // Allow all 2-letter TLDs (ccTLDs)
              if (eregi('^[a-z][a-z]$', $top_level_domain) != 1) {
                $tld_pattern = '';
                // Get authorized TLDs from text file
                $tlds = file(DIR_FS_INCLUDES . 'tld.txt');
                while (list(,$line) = each($tlds)) {
                  // Get rid of comments
                  $words = explode('#', $line);
                  $tld = trim($words[0]);
                  // TLDs should be 3 letters or more
                  if (eregi('^[a-z]{3,}$', $tld) == 1) {
                    $tld_pattern .= '^' . $tld . '$|';
                  }
                }
                // Remove last '|'
                $tld_pattern = substr($tld_pattern, 0, -1);
                if (eregi("$tld_pattern", $top_level_domain) == 0) {
                    $valid_address = false;
                }
              }
            }
          }
          else {
      	    $valid_address = false;
      	  }
      	}
      }
      else {
        $valid_address = false;
      }
    }
    else {
      $valid_address = false;
    }
    if ($valid_address && ENTRY_EMAIL_ADDRESS_CHECK == 'true') {
      if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
        $valid_address = false;
      }
    }
    return $valid_address;
  }

  /**
   * 检查用于注册的email地址是否可用,可用返回 '' 不可用返回错误信息
   * @param string $email 用户地址
   * @param boolean $process_create_account 是否是创建账号过程,创建账号过程会进行数据清理
   * @param int $without_customers_id 是否排除某个人的id，比如登录者本人的id,$customer_id
   * @author vincent
   * @modify by vincent at 2011-5-10 上午10:35:03
   */
  function tep_validate_email_for_register($email_address,$process_create_account = false, $without_customers_id=""){
  	$errorMessage= '';
  	if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
  		$errorMessage= ENTRY_EMAIL_ADDRESS_ERROR;
  	}elseif(tep_validate_email($email_address) == false) {
  		$errorMessage=ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
  	} else {
  		$where_e = "";
		if(tep_not_null($without_customers_id)){
			$where_e.=" and customers_id!='".(int)$without_customers_id."' ";
		}
		$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' ".$where_e);
  		$check_email = tep_db_fetch_array($check_email_query);
  		// BOF: daithik - PWA 检查未使用账号进行购买创建的临时账号
  		//      if ($check_email['total'] > 0) {
  		//        $error = true;
  		//        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
  		//      }
  		if ($check_email['total'] > 0)
  		{  
  			//PWA delete account
	    	$get_customer_info = tep_db_query("select customers_id, customers_email_address, purchased_without_account from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
	    	$customer_info = tep_db_fetch_array($get_customer_info);
	    	$customer_id = $customer_info['customers_id'];
	    	$customer_email_address = $customer_info['customers_email_address'];
	    	$customer_pwa = $customer_info['purchased_without_account'];
	    	if ($customer_pwa !='1')
	    	{
	    		$errorMessage= ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;//$messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
	    	} else {
	    		if($process_create_account){ //在进行账号创建的时候才进行账号删除
		    		tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
		    		tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
		    		tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "'");
		    		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $customer_id . "'");
		    		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $customer_id . "'");
		    		tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . $customer_id . "'");
	    		}
	    	}
  		}
  		// EOF: daithik - PWA
  	}
  	return $errorMessage;
  }
?>
