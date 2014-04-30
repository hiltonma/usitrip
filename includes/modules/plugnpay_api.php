<?php
/*
  osCommerce 2.2 Open Source E-Commerce Solutions
  PlugnPay API Connection

  Last Update: 01/17/05 
*/


        # perform hack to discover the 2 letter state abrev, if necessary 
        if (MODULE_PAYMENT_PLUGNPAY_SEND_STATE_CODE == 'yes') {
          # lookup billing zone_code (AKA - 2 letter state abrev)
          $zone_query1 = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$order->billing['country']['id'] . "' and (zone_name like '" . tep_db_input($order->billing['state']) . "%' or zone_code like '%" . tep_db_input($order->billing['state']) . "%')");
          if (tep_db_num_rows($zone_query1) == 1) {
            $zone1 = tep_db_fetch_array($zone_query1);
            $bill_state = $zone1['zone_code'];
          }
          else {
            $bill_state = $order->billing['state'];
          }

          # lookup shipping zone_code (AKA - 2 letter state abrev)
          $zone_query2 = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$order->delivery['country']['id'] . "' and (zone_name like '" . tep_db_input($order->delivery['state']) . "%' or zone_code like '%" . tep_db_input($order->delivery['state']) . "%')");
          if (tep_db_num_rows($zone_query2) == 1) {
            $zone2 = tep_db_fetch_array($zone_query2);
            $ship_state = $zone2['zone_code'];
          }
          else {
            $ship_state = $order->delivery['state'];
          }
        }
        else {
          $bill_state = $order->billing['state'];
          $ship_state = $order->delivery['state'];
        }

        # reset PnP tax total
        $pnp_tax = 0;

	unset($form_data);

        # start building submit array
	$form_data = array(
          publisher_name => MODULE_PAYMENT_PLUGNPAY_LOGIN,
          publisher_email => MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL,
          mode => 'auth',
          convert => 'underscores',
          easycart => '1',
          shipinfo => '1',
          client => 'osCommerce_API',
          authtype => MODULE_PAYMENT_PLUGNPAY_CCMODE == 'Authorization Type' ? 'authonly' : 'authpostauth',
          card_amount => number_format($order->info['total'], 2),
          currency => "{$order->info['currency']}",
          dontsndmail => MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL == 'yes' ? 'yes': 'no',
          order_id => "$customer_id",
          orderID => "$insert_id",
          card_name => "{$order->billing['firstname']} {$order->billing['lastname']}",
          card_company => "{$order->billing['company']}",
          card_address1 => "{$order->billing['street_address']}",
          card_city => "{$order->billing['city']}",
          card_state => "$bill_state",
          card_zip => "{$order->billing['postcode']}",
          card_country => "{$order->billing['country']['title']}",
          phone => "{$order->customer['telephone']}",
          email => "{$order->customer['email_address']}",
          shipname => "{$order->delivery['firstname']} {$order->delivery['lastname']}",
          company => "{$order->delivery['company']}",	
          address1 => "{$order->delivery['street_address']}",
          city => "{$order->delivery['city']}",
          state => "$ship_state",
          zip => "{$order->delivery['postcode']}",
          country => "{$order->delivery['country']['title']}",
          ipaddress => "{$HTTP_SERVER_VARS['REMOTE_ADDR']}",
          tep_session_name() => tep_session_id()
        );

        for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
          $j = $i + 1;
          $form_data["item$j"] = $order->products[$i]['model'];
          $form_data["cost$j"] = $order->products[$i]['final_price'];
          $form_data["quantity$j"] = $order->products[$i]['qty'];
          $form_data["description$j"] = $order->products[$i]['name'];

          $pnp_tax += ($order->products[$i]['final_price'] * $order->products[$i]['qty']) * ($order->products[$i]['tax'] / 100);
        }

        $form_data["shipping"] = $order->info['shipping_cost'];
        $form_data["tax"] = $pnp_tax;


        if ((MODULE_PAYMENT_PLUGNPAY_PAYMETHOD == "onlinecheck") && ($HTTP_POST_VARS['plugnpay_paytype'] == 'echeck')) {
          // use electronic check info
          $form_data['paymethod'] = 'onlinecheck';
          $form_data['accttype'] = $this->echeck_accttype;
          $form_data['accountnum'] = $this->echeck_accountnum;
          $form_data['routingnum'] = $this->echeck_routingnum;
          $form_data['checknum'] = $this->echeck_checknum;
        }
        else {
          // or assume credit card info
          $form_data['paymethod'] = 'credit';
          $form_data['card_number'] = "$card_number";
          $form_data['card_exp'] = "$card_exp";
          if(MODULE_PAYMENT_PLUGNPAY_CVV == 'yes') {
            $form_data['card_cvv'] = "$card_cvv";
          }
        }

	if((MODULE_PAYMENT_PLUGNPAY_TESTMODE == 'Test') && (MODULE_PAYMENT_PLUGNPAY_TESTMODE == 'Test And Debug')) {
          $form_data['mode'] = 'debug';
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
        if (MODULE_PAYMENT_PLUGNPAY_TESTMODE == 'Test And Debug') {
          $filename = './plugnpay_debug.txt';
          $fp = fopen($filename, "a");
          $write = fputs($fp, "PREAUTH: $data\n");
          fclose($fp);
        }
        unset($response);

/******************************************************************************************
 * Post order info data to PlugnPay, make sure you have curl installed
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
  if (MODULE_PAYMENT_PLUGNPAY_CURL == 'Not Compiled') {
    if (function_exists('exec')) {
      exec('which curl', $curl_output);
      if ($curl_output) {
        $curl_path = $curl_output[0];
      }
      else {
        $curl_path = MODULE_PAYMENT_PLUGNPAY_CURL_PATH;
      }
    }
    exec("$curl_path -d \"$data\" https://pay1.plugnpay.com/payment/pnpremote.cgi", $response);
  }
  else {
    $url = "https://pay1.plugnpay.com/payment/pnpremote.cgi";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  //Windows 2003 Compatibility 
    $authorize = curl_exec($ch);
    curl_close($ch);
    $response = split(",", $authorize);
  }

?>
