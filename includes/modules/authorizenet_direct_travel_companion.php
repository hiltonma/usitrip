<?php

	unset($form_data);
	$xx = '';
	$x_Amount = 0;
	$authorizenet_cc_owner = ($authorizenet_cc_owner) ? $authorizenet_cc_owner : $cc_owner;
	//echo $x_Exp_Date;exit;
	
	// 结伴同游下单人需要付的款项
	if($i_need_pay>0){
		$x_Amount = $i_need_pay;
	}
	// 结伴同游下单人需要付的款项 end
	
	$xx = '订单号：'.$order_id.' 之结伴同游信用卡付款。参团人名称：'.html_to_db($guest_names);
	
	
	//amit fixed currency problem
	$my_autho_currency = 'USD';
	//Austin519 - added transaction key, ccmode
	$form_data = array(
		x_Login => MODULE_PAYMENT_AUTHORIZENET_LOGIN,
		x_Tran_Key => MODULE_PAYMENT_AUTHORIZENET_TRANSKEY,
		x_Relay_Response => 'FALSE',
		x_Delim_Data => 'TRUE',
		x_Version => '3.1',
		x_Type => MODULE_PAYMENT_AUTHORIZENET_CCMODE == 'Authorize Only' ? 'AUTH_ONLY' : 'AUTH_CAPTURE',
		x_Method => MODULE_PAYMENT_AUTHORIZENET_METHOD == 'Credit Card' ? 'CC' : 'ECHECK',
		x_Amount => $x_Amount,
		x_Currency_Code => "$my_autho_currency",
		x_Email_Customer => MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER == 'True' ? 'TRUE': 'FALSE',
		x_Email_Merchant => MODULE_PAYMENT_AUTHORIZENET_EMAIL_MERCHANT == 'True' ? 'TRUE': 'FALSE',
		x_Cust_ID => "$customer_id",
		x_Invoice_Num => "$order_id",
		x_First_Name => "$authorizenet_cc_owner",
		x_Company => "$billing_company",
		x_Address => "$billing_street_address",
		x_City => "$billing_city",
		x_State => "$billing_state",
		x_Zip => "$billing_postcode",
		x_Country => "$billing_country",
		x_Phone => "$billing_telephone",
		x_Email => "$customer_email_address",
		x_Ship_To_First_Name => "$authorizenet_cc_owner",	
		//x_Ship_To_First_Name => "{$order->delivery['firstname']}",
		//x_Ship_To_Last_Name => "{$order->delivery['lastname']}",
		x_Ship_To_Company => "",
		x_Ship_To_Address => "",
		x_Ship_To_City => "",
		x_Ship_To_State => "",
		x_Ship_To_Zip => "",
		x_Ship_To_Country => "",
		x_Customer_IP => "{$HTTP_SERVER_VARS['REMOTE_ADDR']}",
		x_Description => "$xx",
		tep_session_name() => tep_session_id()
	);

	if(MODULE_PAYMENT_AUTHORIZENET_METHOD == 'Credit Card') {
		$form_data['x_Card_Num'] = "$x_Card_Num";
		$form_data['x_Exp_Date'] = "$x_Exp_Date";

	if(MODULE_PAYMENT_AUTHORIZENET_CCV	== 'True' ) {
		$form_data['x_Card_Code'] = "$x_Card_Code";
		}
	} else {
		//E-check Information (Currently NOT IMPLEMENTED!)
		$form_data['x_Recurring_Billing'] = '';
		$form_data['x_Bank_Aba_Code'] = '';
		$form_data['x_Bank_Acct_Num'] = '';
		$form_data['x_Bank_Acct_Type'] = '';
		$form_data['x_Bank_Name'] = '';
		$form_data['x_Bank_Acct_Name'] = '';
		$form_data['x_echeck_type'] = '';
		$form_data['x_drivers_license_num'] = '';
		$form_data['x_drivers_license_state'] = '';
		$form_data['x_drivers_license_DOB'] = '';
	}

     if (($x_Card_Num == '4111111111111111') || ($x_Card_Num == '370000000000002') || ($x_Card_Num == '6011000000000012') || ($x_Card_Num == '5424000000000015') || ($x_Card_Num == '4007000000027')) {
      $cardtestmode=1;
    } else {
      $cardtestmode=0;
    }

	if((MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') || (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug') ) {
		//$form_data['x_Test_Request'] = 'TRUE';
	}

	// concatenate order information variables to $data
	while(list($key, $value) = each($form_data)) {
		$data .= $key . '=' . urlencode(ereg_replace(',', '', $value)) . '&';
	}

	// take the last & out for the string
	$data = substr($data, 0, -1);

	/* Debug code - will dump cURL data to a file when
	 * the admin module Transaction Mode is set to Test and Debug.
	 * The file is written into the root directory of the catalog
	 */
	if (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug') {
		$filename = './authnet_debug.txt';
		$fp = fopen($filename, "a");
		$write = fputs($fp, $data);
		fclose($fp);
	}
	unset($response);

/******************************************************************************************
 * Post order info data to Authorize.net, make sure you have curl installed
 *    Those with cURL not compiled into PHP (Windows users, some Linux users):
 *          Please type in your path in the admin module.  The code below will TRY to find
 *          your cURL path for you (may not work under Windows) and if it finds it, will
 *          default to that, but you should enter your cURL path in the admin module to be
 *          sure.  You no longer need to edit the code manually in this file (that code thanks
 *          to dreamscape).
 *    Those with cURL compiled into PHP (some Linux users):
 *          This should work without any editing if cURL is compiled into your PHP and you
 *          have PHP configured to realize it (per the PHP guides).
 ******************************************************************************************
 */
	if (MODULE_PAYMENT_AUTHORIZENET_CURL == 'Not Compiled') {
		if (function_exists('exec')) {
		  exec('which curl', $curl_output);
			if ($curl_output) {
				$curl_path = $curl_output[0];
			}else {
				$curl_path = MODULE_PAYMENT_AUTHORIZENET_CURL_PATH;
			}
    }
    if (($x_Card_Num == '4111111111111111') || ($x_Card_Num == '370000000000002') || ($x_Card_Num == '6011000000000012') || ($x_Card_Num == '5424000000000015') || ($x_Card_Num == '4007000000027')) {
      $cardtestmode=1;
    } else {
      $cardtestmode=0;
    }
    if((MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') || (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug') ) {
      exec("$curl_path -d \"$data\" https://certification.authorize.net/gateway/transact.dll", $response);
    } else {
      exec("$curl_path -d \"$data\" https://secure.authorize.net/gateway/transact.dll", $response);
    }

  } else {
    if((MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') || (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug') ) {
      $url = "https://certification.authorize.net/gateway/transact.dll";
    } else {
      $url = "https://secure.authorize.net/gateway/transact.dll";
    }
    $agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  //Windows 2003 Compatibility
		$authorize = curl_exec($ch);
		curl_close($ch);
		$response = explode(',', $authorize);
	}


	if (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug') {
	        $filename2 = 'authnet_debug.txt';
				$authorize2=' :data String : ' . $data . '\n';
				$response2 = ' :Response String : ' . $authorize . '\n';
	        $fp2 = fopen($filename2,"ab");
	        $write = fputs($fp2, $authorize2, strlen($authorize2));
		$write = fputs($fp2, $response2, strlen($response2));
	        fclose($fp2);
	}
?>
