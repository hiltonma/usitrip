<?php
require_once('includes/application_top.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

$osCsid_string = '';
if(tep_not_null($_GET['osCsid'])){
	$osCsid_string = '&osCsid='.(string)$_GET['osCsid'];
}


if(isset($_POST['aryFormData'])){
	  $aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value)
		{
		  if(is_array($value)){
		  foreach ($value as $key2 => $value2 )
		  {	  

			  if (eregi('--leftbrack', $key)) {				
				$key = str_replace('--leftbrack','[',$key);
				$key = str_replace('rightbrack--',']',$key);				
			  }
			  
			  $value2 = str_replace('@@amp;','&',$value2);
			  $value2 = str_replace('@@plush;','+',$value2);
			  $_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes($value2);  

			  //echo "$key=>$value2<br>";  	   
		  }
		  }
		}
		
		
		 if (!tep_session_is_registered('customer_id')) {
		 echo general_to_ajax_string('You need to login again!!您需要重新登录。');
		 }
		if ($cart->count_contents() < 1) {
		echo general_to_ajax_string('Empaty Cart!!空购物车。');
	  	}
		
		$error='false';
		$edited_section_succesed = 'false';
		
		//validation check start
		if($HTTP_GET_VARS['ajax_section_name'] == 'billing_information' &&  $HTTP_GET_VARS['action_ajax_edit'] == 'process'){
			  $firstname = $customer_first_name;
			  $lastname = $customer_last_name;
			  $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']); //.' , '.tep_db_prepare_input($HTTP_POST_VARS['street_address_line2']);
			  if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
			  $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
			  $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
			  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
			 
			  
			  if (ACCOUNT_STATE == 'true') {
				$state = tep_db_prepare_input($HTTP_POST_VARS['state']);
			  }
				  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
					$error = 'true';
					$messageStack->add('checkout_address', ENTRY_STREET_ADDRESS_ERROR);
				  }						
				  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
					$error = 'true';				
					$messageStack->add('checkout_address', ENTRY_POST_CODE_ERROR);
				  }						
				  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
					$error = 'true';						
					$messageStack->add('checkout_address', ENTRY_CITY_ERROR);
				  }
				  if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
					$error = 'true';					
					$messageStack->add('checkout_address', ENTRY_STATE_ERROR);
				  }
				  if ( (is_numeric($country) == false) || ($country < 1) ) {
					$error = 'true';
					$messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);
				  }
				  
				  /*if(teg_get_validate_telephone_number(tep_db_prepare_input($HTTP_POST_VARS['telephone']),true) == false){
				  	$error = 'true';
					$messageStack->add('checkout_address', db_to_html('请至少输入一个电话号码！'));
				  }*/
				  
					if ( ((strlen($HTTP_POST_VARS['telephone'])-3) < ENTRY_TELEPHONE_MIN_LENGTH && (strlen($telephone)-3) < ENTRY_TELEPHONE_MIN_LENGTH && (strlen($HTTP_POST_VARS['mobile_phone'])-3) < ENTRY_TELEPHONE_MIN_LENGTH && (strlen($HTTP_POST_VARS['fax'])-3) < ENTRY_TELEPHONE_MIN_LENGTH ) 
					|| 
					(teg_get_validate_telephone_number(tep_db_prepare_input($HTTP_POST_VARS['telephone']),true) == false && teg_get_validate_telephone_number(tep_db_prepare_input($HTTP_POST_VARS['mobile_phone']),true) == false && teg_get_validate_telephone_number(tep_db_prepare_input($HTTP_POST_VARS['fax']),true) == false )){
				  	$error = 'true';
					$messageStack->add('checkout_address', db_to_html('请至少输入一个电话号码！'));
				  }
				  
				  $def_tel_code = tep_get_countries_tel_code((int)$HTTP_POST_VARS['country']);
				  //check telephone
				  if(tep_not_null($HTTP_POST_VARS['telephone']) && !preg_match('/^\d{'.ENTRY_TELEPHONE_MIN_LENGTH.',20}$/',$HTTP_POST_VARS['telephone'])){
				  	$error = 'true';
					$messageStack->add('checkout_address', ENTRY_TELEPHONE_NUMBER. ENTRY_TELEPHONE_NUMBER_ERROR );
				  }elseif(tep_not_null($HTTP_POST_VARS['telephone']) && $HTTP_POST_VARS['country']=='44' && !preg_match('/^\d{10,20}$/',$HTTP_POST_VARS['telephone'])){
				  	$error = 'true';
					$messageStack->add('checkout_address', ENTRY_TELEPHONE_NUMBER. db_to_html('中国内地客户请输入完整的城市区号以方便更好的联络，如01088888888') );
				  }elseif(tep_not_null($HTTP_POST_VARS['telephone']) && !tep_not_null($HTTP_POST_VARS['telephone_cc'])){ //补充国家区号
				  	$HTTP_POST_VARS['telephone_cc'] = $def_tel_code;
				  }
				  
				  //check fax
				  if(tep_not_null($HTTP_POST_VARS['fax']) && !preg_match('/^\d{'.ENTRY_TELEPHONE_MIN_LENGTH.',20}$/',$HTTP_POST_VARS['fax'])){
				  	$error = 'true';
					$messageStack->add('checkout_address', ENTRY_FAX_NUMBER. ENTRY_TELEPHONE_NUMBER_ERROR );
				  }elseif(tep_not_null($HTTP_POST_VARS['fax']) && $HTTP_POST_VARS['country']=='44' && !preg_match('/^\d{10,20}$/',$HTTP_POST_VARS['fax'])){
				  	$error = 'true';
					$messageStack->add('checkout_address', ENTRY_FAX_NUMBER. db_to_html('中国内地客户请输入完整的城市区号以方便更好的联络，如01088888888') );
				  }elseif(tep_not_null($HTTP_POST_VARS['fax']) && !tep_not_null($HTTP_POST_VARS['fax_cc'])){ //补充国家区号
				  	$HTTP_POST_VARS['fax_cc'] = $def_tel_code;
				  }
				  
				  //check mobile_phone
				  if(tep_not_null($HTTP_POST_VARS['mobile_phone']) && !tep_not_null($HTTP_POST_VARS['mobile_phone_cc'])){
				  	$HTTP_POST_VARS['mobile_phone_cc'] = $def_tel_code;
				  }
				  
		}
		//validation check end
		
		
		
		if(isset($HTTP_GET_VARS['action_ajax_edit']) &&  $HTTP_GET_VARS['action_ajax_edit'] == 'process' && $error=='false'){ //start of proccess if
		
			switch($HTTP_GET_VARS['ajax_section_name']) { //start of proccess swich 	
				
				case 'billing_information':
				
					/* billing info update - start */
					  
					  if(isset($HTTP_POST_VARS['billto'])){
					
						  $firstname = $customer_first_name;
						  $lastname = $customer_last_name;
						  $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']); //.' , '.tep_db_prepare_input($HTTP_POST_VARS['street_address_line2']);
						  if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
						  $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
						  $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
						  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);

						  if (ACCOUNT_STATE == 'true') {
							$state = tep_db_prepare_input($HTTP_POST_VARS['state']);
						  }
						  if ($error == 'false') {
							$sql_data_array = array('customers_id' => $customer_id,
													'entry_street_address' => ajax_to_general_string($street_address),
													'entry_postcode' => ajax_to_general_string($postcode),
													'entry_city' => ajax_to_general_string($city),
													'entry_country_id' => ajax_to_general_string($country));
					
							//if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
							if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = ajax_to_general_string($company);
							if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = ajax_to_general_string($suburb);
							if (ACCOUNT_STATE == 'true') {
								/*howard added*/
								$get_zone_sql = tep_db_query('SELECT zone_id FROM `zones` WHERE zone_name="'.html_to_db(ajax_to_general_string($state)).'" limit 1');
								$get_row = tep_db_fetch_array($get_zone_sql);
								if((int)$get_row['zone_id']){
									$sql_data_array['entry_state'] = '';
									$sql_data_array['entry_zone_id'] = (int)$get_row['zone_id'];
								}else{
									$sql_data_array['entry_state'] = ajax_to_general_string($state);
									$sql_data_array['entry_zone_id'] = '0';
								}
								/*howard added end*/
							}
							
							$sql_data_array = html_to_db($sql_data_array);
							
							tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$HTTP_POST_VARS['billto'] . "'");
							
						}
					  }
					  /* billing info update - end */
				
				$edited_section_succesed = 'true';
			
				break;
				case 'traveler_detail':
					
				  $i = $HTTP_GET_VARS['product_rank'];
					
				  if (!tep_session_is_registered('arrival_date'.$i)) tep_session_register('arrival_date'.$i);
				  if (isset($HTTP_POST_VARS['arrival_date'.$i])) {
					$_SESSION['arrival_date'.$i] = tep_db_prepare_input($HTTP_POST_VARS['arrival_date'.$i]);
				  }
				  if (!tep_session_is_registered('departure_date'.$i)) tep_session_register('departure_date'.$i);
				  if (isset($HTTP_POST_VARS['departure_date'.$i])) {
					$_SESSION['departure_date'.$i] = tep_db_prepare_input($HTTP_POST_VARS['departure_date'.$i]);
				  }
				  
				  if (!tep_session_is_registered('airline_name')) tep_session_register('airline_name');
				  if (isset($HTTP_POST_VARS['airline_name'])) {
					$airline_name = tep_db_prepare_input($HTTP_POST_VARS['airline_name']);
				  }
				  if (!tep_session_is_registered('flight_no')) tep_session_register('flight_no');
				  if (isset($HTTP_POST_VARS['flight_no'])) {
					$flight_no = tep_db_prepare_input($HTTP_POST_VARS['flight_no']);
				  }
				  if (!tep_session_is_registered('airline_name_departure')) tep_session_register('airline_name_departure');
				  if (isset($HTTP_POST_VARS['airline_name_departure'])) {
					$airline_name_departure = tep_db_prepare_input($HTTP_POST_VARS['airline_name_departure']);
				  }
				  if (!tep_session_is_registered('flight_no_departure')) tep_session_register('flight_no_departure');
				  if (isset($HTTP_POST_VARS['flight_no_departure'])) {
					$flight_no_departure = tep_db_prepare_input($HTTP_POST_VARS['flight_no_departure']);
				  }
				  
				  if (!tep_session_is_registered('airport_name')) tep_session_register('airport_name');
				  if (isset($HTTP_POST_VARS['airport_name'])) {
					$airport_name = tep_db_prepare_input($HTTP_POST_VARS['airport_name']);
				  }		 
				  if (!tep_session_is_registered('airport_name_departure')) tep_session_register('airport_name_departure');
				  if (isset($HTTP_POST_VARS['airport_name_departure'])) {
					$airport_name_departure = tep_db_prepare_input($HTTP_POST_VARS['airport_name_departure']);
				  }
				  
				  
				  if (!tep_session_is_registered('arrival_time')) tep_session_register('arrival_time');
				  if (isset($HTTP_POST_VARS['arrival_time'])) {
					$arrival_time = tep_db_prepare_input($HTTP_POST_VARS['arrival_time']);
				  }
				 
				  if (!tep_session_is_registered('departure_time')) tep_session_register('departure_time');
				  if (isset($HTTP_POST_VARS['departure_time'])) {
					$departure_time = tep_db_prepare_input($HTTP_POST_VARS['departure_time']);
				  }
				  function checkmiltrytime($checkstring){
						$validationcheck = true;
						if(preg_match('/^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/', $checkstring, $matchArray)) { 	
								$hour = $matchArray[1];			
								$minute = $matchArray[2];						
								if ($hour < 0  || $hour > 23) {
									$validationcheck = false;
								}
								if ($minute < 0 || $minute > 59) {		
									$validationcheck = false;
								}
								
						}else{
						$validationcheck = false;
						}			
					return $validationcheck;
					} 
				  if(isset($HTTP_POST_VARS['arrival_time['.$i.']'])){	
						if(strlen(trim($HTTP_POST_VARS['arrival_time['.$i.']']))>1 && checkmiltrytime($HTTP_POST_VARS['arrival_time['.$i.']']) == false ){						
							$error = 'true';					
							$messageStack->add('checkout_cfrm_trv_detail', 'Please enter valid arrival time');
					
						}
					}
					if(isset($HTTP_POST_VARS['departure_time['.$i.']'])){	
						if(strlen(trim($HTTP_POST_VARS['departure_time['.$i.']']))>1 && checkmiltrytime($HTTP_POST_VARS['departure_time['.$i.']']) == false ){						
							$error = 'true';					
							$messageStack->add('checkout_cfrm_trv_detail', 'Please enter valid departure time');
					
						}
					}
					if(isset($HTTP_POST_VARS['arrival_date'.$i]) && $HTTP_POST_VARS['arrival_date'.$i]!=''){	
						if(!tep_checkdate($HTTP_POST_VARS['arrival_date'.$i], 'mm/dd/yyyy', $child_age_date_available_array)) {
							$error = 'true';					
							
							if($arrival_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', 'Arrival Date should in mm/dd/yyyy format');
							}
							$arrival_error='true';
						}
					}
					if(isset($HTTP_POST_VARS['departure_date'.$i]) && $HTTP_POST_VARS['departure_date'.$i]!=''){
						if(!tep_checkdate($HTTP_POST_VARS['departure_date'.$i], 'mm/dd/yyyy', $child_age_date_available_array)) {
							$error = 'true';					
							
							if($departure_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', 'Departure Date should in mm/dd/yyyy format');
							}
							$departure_error='true';
						}
					}
  
					$m=$HTTP_POST_VARS['total_guest_number_m'];
					for($h=0; $h<$m; $h++)
					{
						//howard fixed
						if(strlen(trim($HTTP_POST_VARS['guestname'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestname'.$h.'['.$i.']'] == ''){
							$HTTP_POST_VARS['guestname'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestname'.$h.'['.$i.'']);
						}
						if(strlen(trim($HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']'] == ''){
							$HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['GuestEngXing'.$h.'['.$i.'']);
						}
						if(strlen(trim($HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']'] == ''){
							$HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['GuestEngName'.$h.'['.$i.'']);
						}
						if(strlen(trim($HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']'] == ''){
							if(isset($HTTP_POST_VARS['aryFormData']['guestbodyweight'.$h.'['.$i.''])){
								$HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestbodyweight'.$h.'['.$i.'']);
							}
						}
						if(strlen(trim($HTTP_POST_VARS['guestweighttype'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestweighttype'.$h.'['.$i.']'] == ''){
							if(isset($HTTP_POST_VARS['aryFormData']['guestweighttype'.$h.'['.$i.''])){
								$HTTP_POST_VARS['guestweighttype'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestweighttype'.$h.'['.$i.'']);
							}
						}
						
						if(strlen(trim($HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']'] == ''){
							if(isset($HTTP_POST_VARS['aryFormData']['guestbodyheight'.$h.'['.$i.''])){
								$HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestbodyheight'.$h.'['.$i.'']);
							}
						}
						
						if(strlen(trim($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'] == ''){
							if(isset($HTTP_POST_VARS['aryFormData']['guestchildage'.$h.'['.$i.''])){
								$HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestchildage'.$h.'['.$i.'']);
							}
						}
						
						if(strlen(trim($HTTP_POST_VARS['guestgender'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestgender'.$h.'['.$i.']'] == ''){
							$HTTP_POST_VARS['guestgender'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestgender'.$h.'['.$i.'']);
						}
						
						if(strlen(trim($HTTP_POST_VARS['guestdob'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestdob'.$h.'['.$i.']'] == ''){
							if(isset($HTTP_POST_VARS['aryFormData']['guestdob'.$h.'['.$i.''])){
								$HTTP_POST_VARS['guestdob'.$h.'['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['guestdob'.$h.'['.$i.'']);
							}
						}
						//howard fixed end

						if(strlen(trim($HTTP_POST_VARS['guestname'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestname'.$h.'['.$i.']'] == ''){
						
							$error = 'true';
							if($guestname_error!='true'){
							$messageStack->add('checkout_cfrm_trv_detail', 'Customer name can\'t be blank!');
							}
							$guestname_error='true';
						}

						if(strlen(trim($HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']'] == ''){
							$error = 'true';
							if($guestname_error!='true'){
							$messageStack->add('checkout_cfrm_trv_detail', 'Customer name can\'t be blank!!');
							}
							$guestname_error='true';
						}
						if(strlen(trim($HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']'] == ''){
							$error = 'true';
							if($guestname_error!='true'){
							$messageStack->add('checkout_cfrm_trv_detail', 'Customer name can\'t be blank!!!!');
							}
							$guestname_error='true';
						}
						
						if(isset($HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']'])){
							if(strlen(trim($HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']'] == ''){
								$error = 'true';					
								
								if($guestbodyweight_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', 'Customer body weight can\'t be blank');
								}
								$guestbodyweight_error='true';
								}
						}
						
						if(isset($HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']'])){
							if(strlen(trim($HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']'] == ''){
								$error = 'true';					
								
								if($guestbodyheight_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', 'Customer body height can\'t be blank');
								}
								$guestbodyheight_error='true';
								}
						}
						
						if(isset($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'])){	
							if(strlen(trim($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'] == ''){
							$error = 'true';					
							$messageStack->add('checkout_cfrm_trv_detail', 'Child birth date can\'t be blank');
							$guestchildage_error='true';
							}
							
							if(!tep_checkdate($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'], 'mm/dd/yyyy', $child_age_date_available_array)) {
							$error = 'true';					
							
							if($guestchildage_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', 'Child birth date should in mm/dd/yyyy format');
							}
							$guestchildage_error='true';
							}
						}
						
						
						if (tep_not_null($HTTP_POST_VARS['GuestEmail'.$h.'['.$i.']'])) {
							$_SESSION['GuestEmail'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['GuestEmail'.$h.'['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['PayerMe'.$h.'['.$i.']'])) {
							$_SESSION['PayerMe'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['PayerMe'.$h.'['.$i.']']);
						}
						
						if (tep_not_null($HTTP_POST_VARS['guestname'.$h.'['.$i.']'])) {
							$_SESSION['guestname'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestname'.$h.'['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']'])) {
							$_SESSION['GuestEngXing'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['GuestEngXing'.$h.'['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']'])) {
							$_SESSION['GuestEngName'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['GuestEngName'.$h.'['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']'])) {
							$_SESSION['guestbodyweight'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestbodyweight'.$h.'['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['guestweighttype'.$h.'['.$i.']'])) {
							$_SESSION['guestweighttype'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestweighttype'.$h.'['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']'])) {
							$_SESSION['guestbodyheight'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestbodyheight'.$h.'['.$i.']']);
						}
						
						if (tep_not_null($HTTP_POST_VARS['SingleName['.$i.']'])) {
							$_SESSION['SingleName'][$i] = tep_db_prepare_input($HTTP_POST_VARS['SingleName['.$i.']']);
						}
						if (tep_not_null($HTTP_POST_VARS['SingleGender['.$i.']'])) {
							$_SESSION['SingleGender'][$i] = tep_db_prepare_input($HTTP_POST_VARS['SingleGender['.$i.']']);
						}
						
						//amit added for add extra field for selected tours start
						if(isset($HTTP_POST_VARS['guestdob'.$h.'['.$i.']'])){	
							if(strlen(trim($HTTP_POST_VARS['guestdob'.$h.'['.$i.']']))<1 || $HTTP_POST_VARS['guestdob'.$h.'['.$i.']'] == ''){
							$error = 'true';					
							$messageStack->add('checkout_cfrm_trv_detail', ajax_to_general_string(ENTRY_DATE_OF_BIRTH_ERROR));
							$guestdob_error='true';
							}
							
							if(!tep_checkdate($HTTP_POST_VARS['guestdob'.$h.'['.$i.']'], 'mm/dd/yyyy', $child_age_date_available_array)) {
							$error = 'true';					
							
							if($guestdob_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', ajax_to_general_string(ENTRY_DATE_OF_BIRTH_ERROR));
							}
							$guestdob_error='true';
							}
						}
						
						if (tep_not_null($HTTP_POST_VARS['guestdob'.$h.'['.$i.']']) && $guestdob_error != 'true') {
							$_SESSION['guestdob'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestdob'.$h.'['.$i.']']);
						}	
						
						if (tep_not_null($HTTP_POST_VARS['guestgender'.$h.'['.$i.']'])) {
							$_SESSION['guestgender'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestgender'.$h.'['.$i.']']);
						}
						//amit added for add extra field for selected tours end
						

					}
				//print_r($_SESSION);
					
				break;
				
			}//end of proccess swich 			
				
		}	//end of proccess if
		
		
		
		// load required files start
		  require_once(DIR_FS_CLASSES . 'payment.php');
		  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
		  $payment_modules = new payment($payment);
		  require_once(DIR_FS_CLASSES . 'order_total.php');
		
		  require_once(DIR_FS_CLASSES . 'order.php');
		  $order = new order;
		
		  $payment_modules->update_status();
		  $order_total_modules = new order_total;	  
			
		  $order_total_modules->collect_posts();
		  $order_total_modules->pre_confirmation_check();
		  // load required files end
		  
		 if($HTTP_GET_VARS['ajax_section_name'] == 'traveler_detail' &&  $HTTP_GET_VARS['action_ajax_edit'] == 'process'){
				$i = $HTTP_GET_VARS['product_rank'];
				for($h=0; $h<$m; $h++)
					{
						
						if($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'] != ''){					
							$age_diff_at_time_travel = (int)((strtotime(tep_get_date_disp(substr($order->products[$i]['dateattributes'][0],0,10))) - strtotime(trim($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']'])))/(86400*365)) ;
							if($age_diff_at_time_travel > $order->products[$i]['max_allow_child_age'] && $order->products[$i]['max_allow_child_age'] != ''){
								$error = 'true';					
								
								if($guestchildage_max_error!='true'  && $guestchildage_error!='true'){
								$messageStack->add('checkout_cfrm_trv_detail', 'Invalid child age for child birth date '.$_POST['guestchildage'.$h.'['.$i.']'].' Child age should be under '.$order->products[$i]['max_allow_child_age'].' year at time of travel. Please edit tour memeber information.');
								}
								$guestchildage_max_error='true';
								//tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'guest_childage_error=true&wrgdate='.urlencode($_POST['guestchildage'.$hh][$i]).'&error=Invalid+child+age+for+child+birth+date+'.$_POST['guestchildage'.$hh][$i].'.+Child+age+should+be+under+'.$order->products[$i]['max_allow_child_age'].'+years+at+time+of+travel.+Please+edit+tour+member+information.', 'SSL'));					
							}
						}
						
						if (tep_not_null($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']']) && $guestchildage_error != 'true' && $guestchildage_max_error!='true') {
							$_SESSION['guestchildage'.$h][$i] = tep_db_prepare_input($HTTP_POST_VARS['guestchildage'.$h.'['.$i.']']);
						}	
					}
				
				//howard fixed
				if(strlen(trim($HTTP_POST_VARS['airline_name['.$i.']']))<1 || $HTTP_POST_VARS['airline_name['.$i.']'] == ''){
					$HTTP_POST_VARS['airline_name['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['airline_name['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['airline_name_departure['.$i.']']))<1 || $HTTP_POST_VARS['airline_name_departure['.$i.']'] == ''){
					$HTTP_POST_VARS['airline_name_departure['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['airline_name_departure['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['flight_no['.$i.']']))<1 || $HTTP_POST_VARS['flight_no['.$i.']'] == ''){
					$HTTP_POST_VARS['flight_no['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['flight_no['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['flight_no_departure['.$i.']']))<1 || $HTTP_POST_VARS['flight_no_departure['.$i.']'] == ''){
					$HTTP_POST_VARS['flight_no_departure['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['flight_no_departure['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['airport_name['.$i.']']))<1 || $HTTP_POST_VARS['airport_name['.$i.']'] == ''){
					$HTTP_POST_VARS['airport_name['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['airport_name['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['airport_name_departure['.$i.']']))<1 || $HTTP_POST_VARS['airport_name_departure['.$i.']'] == ''){
					$HTTP_POST_VARS['airport_name_departure['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['airport_name_departure['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['arrival_time['.$i.']']))<1 || $HTTP_POST_VARS['arrival_time['.$i.']'] == ''){
					$HTTP_POST_VARS['arrival_time['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['arrival_time['.$i.'']);
				}
				if(strlen(trim($HTTP_POST_VARS['departure_time['.$i.']']))<1 || $HTTP_POST_VARS['departure_time['.$i.']'] == ''){
					$HTTP_POST_VARS['departure_time['.$i.']'] = ajax_to_general_string($HTTP_POST_VARS['aryFormData']['departure_time['.$i.'']);
				}
				//howard fixed end
				$airline_name[$i] = tep_db_prepare_input($HTTP_POST_VARS['airline_name['.$i.']']);
				$airline_name_departure[$i] = tep_db_prepare_input($HTTP_POST_VARS['airline_name_departure['.$i.']']);
				$flight_no[$i] = tep_db_prepare_input($HTTP_POST_VARS['flight_no['.$i.']']);
				$flight_no_departure[$i] = tep_db_prepare_input($HTTP_POST_VARS['flight_no_departure['.$i.']']);
				$airport_name[$i] = tep_db_prepare_input($HTTP_POST_VARS['airport_name['.$i.']']);
				$airport_name_departure[$i] = tep_db_prepare_input($HTTP_POST_VARS['airport_name_departure['.$i.']']);
				$arrival_time[$i] = tep_db_prepare_input($HTTP_POST_VARS['arrival_time['.$i.']']);
				$departure_time[$i] = tep_db_prepare_input($HTTP_POST_VARS['departure_time['.$i.']']);
				
		 	if ($error == 'false') {
				$order->info['airline_name'][$i] = tep_db_prepare_input($HTTP_POST_VARS['airline_name['.$i.']']);
				$order->info['airline_name_departure'][$i] = tep_db_prepare_input($HTTP_POST_VARS['airline_name_departure['.$i.']']);
				$order->info['flight_no'][$i] = tep_db_prepare_input($HTTP_POST_VARS['flight_no['.$i.']']);
				$order->info['flight_no_departure'][$i] = tep_db_prepare_input($HTTP_POST_VARS['flight_no_departure['.$i.']']);
				$order->info['airport_name'][$i] = tep_db_prepare_input($HTTP_POST_VARS['airport_name['.$i.']']);
				$order->info['airport_name_departure'][$i] = tep_db_prepare_input($HTTP_POST_VARS['airport_name_departure['.$i.']']);
				$order->info['arrival_time'][$i] = tep_db_prepare_input($HTTP_POST_VARS['arrival_time['.$i.']']);
				$order->info['departure_time'][$i] = tep_db_prepare_input($HTTP_POST_VARS['departure_time['.$i.']']);
						
				$edited_section_succesed = 'true';  
			}		 
		 }
				
		
		switch ($HTTP_GET_VARS['ajax_section_name']) { //start switch of display html code	
			case 'billing_information':
			
																			
						
						if($HTTP_GET_VARS['confirmation']=='true'){
							
							?>
							<table width="100%" cellpadding="0" cellspacing="0">
							  <tr class="infoBoxContents_new">
								<td width="73%" valign="top">
								
								<table border="0" width="100%" cellspacing="0" cellpadding="4">
								  
								  <?php
								  $address_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$billto . "'");
								  $address = tep_db_fetch_array($address_query);
								  								  
								  ?>
								  <tr><td>
									<table width="100%" cellpadding="0" cellspacing="0">
								  <tr>
									<td class="main_new_sky" width="30%"><?php echo general_to_ajax_string(TEXT_BILLING_INFO_ADDRESS) ?> </td>
									<td class="main" width="70%"><?php echo general_to_ajax_string(db_to_html($address['street_address'])); ?></td>
								  </tr>
								  <tr>
									<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_BILLING_INFO_CITY); ?></td>
									<td class="main"><?php echo general_to_ajax_string(db_to_html($address['city'])); ?></td>
								  </tr>
								  <tr>
									<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_BILLING_INFO_STATE); ?></td>
									<td class="main">
									<?php
									if((int)$address['zone_id']){
										$get_zone_sql = tep_db_query('SELECT zone_id,zone_name FROM `zones` WHERE zone_id ="'.(int)$address['zone_id'].'" limit 1');
										$get_row = tep_db_fetch_array($get_zone_sql);
										if((int)$get_row['zone_id']){
											echo general_to_ajax_string(db_to_html($get_row['zone_name']));
										}else{
											echo general_to_ajax_string(db_to_html($address['state']));
										}

									}else{
										echo general_to_ajax_string(db_to_html($address['state']));
									}
									?>
									</td>
								  </tr>															  															
								  <tr>
									<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_BILLING_INFO_POSTAL); ?> </td>
									<td class="main"><?php echo general_to_ajax_string(db_to_html($address['postcode'])); ?></td>
								  </tr>
								  <tr>
									<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_BILLING_INFO_COUNTRY); ?></td>
									<td class="main"><?php echo general_to_ajax_string(db_to_html(tep_get_country_name($address['country_id']))); ?></td>
								  </tr>
								  
								  </table>
								  </td></tr>
								  
								</table></td>
								
								<td width="2%"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td></tr>
							  
							  <tr>
							  	<td colspan="3">
									<?php
									if($edited_section_succesed == 'false' && $HTTP_GET_VARS['action_ajax_edit']!='cancel'){
										$check_address_blank_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
										$row_check_address_blank = tep_db_fetch_array($check_address_blank_query);		
										require(DIR_FS_MODULES . 'edit_checkout_address.php');
									}
									?>
								</td>
							  </tr>
</table>
							  
						<?php	
						}else{
						$check_address_blank_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone, c.customers_mobile_phone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$billto . "'");
						$row_check_address_blank = tep_db_fetch_array($check_address_blank_query);		
						?>
						
						 <div id="show_address_div">
							<div class="infoBox_outer"> 
							 <table class="infoBox_new" width="100%" cellspacing="0" cellpadding="2" border="0">
							   
							  <tr class="infoBoxContents_new">
								<td>	
								
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
								  <tr>
								    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
							      </tr>
								   <tr>
									 <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
									 <td class="main" colspan="3"><span class="sp1"><b><?php echo general_to_ajax_string(TEXT_NOTES_HEADING_DIS);?> </b></span><?php echo general_to_ajax_string(TEXT_NOTES_HEADING_BILLING_EDIT_INFORMATION);?></td>
									</tr>
								  <tr>
								<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 																<td align="left" width="50%" valign="top">
								
								<table border="0" cellspacing="0" cellpadding="0">
								  <tr>
									
									<td class="main" align="center" valign="top" nowrap><b><?php //echo general_to_ajax_string(TITLE_BILLING_ADDRESS); ?></b><?php //echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
								
									<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
									<td class="main" valign="top"><b>
									<?php 
										echo general_to_ajax_string(db_to_html(tep_address_label($customer_id, $billto, true, ' ', '<br />')));
										//amit added shot telpphone number start
										if($row_check_address_blank['customers_telephone'] != ''){
											echo general_to_ajax_string('<br />'.ENTRY_TELEPHONE_NUMBER.' '.db_to_html($row_check_address_blank['customers_telephone']));
										}
										if($row_check_address_blank['customers_fax'] != ''){
											echo general_to_ajax_string('<br />'.ENTRY_FAX_NUMBER.' '.db_to_html($row_check_address_blank['customers_fax']));
										}
										if($row_check_address_blank['customers_mobile_phone'] != ''){
											echo general_to_ajax_string('<br />'.TEXT_BILLING_INFO_MOBILE.' '.db_to_html($row_check_address_blank['customers_mobile_phone']));
										}
										//amit added show telephone number end
									?></b></td>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
								  </tr>
								</table>
								</td>
								<td class="main" width="50%" valign="top" align="right"><?php echo general_to_ajax_string(TEXT_SELECTED_BILLING_DESTINATION); ?><br /><br /><?php echo tep_template_image_button('button_edit_information.gif', general_to_ajax_string(IMAGE_BUTTON_CHANGE_ADDRESS),' onclick="sendFormData(\'checkout_payment\',\'checkout_edit_sections_ajax.php?ajax_section_name=billing_information&ajax=true'.$osCsid_string.'\',\'response_billing_div\',\'true\');" style="cursor:pointer;"'); // onclick="toggel_div(\'address_edit_div\');" ?>							</td>
								<td class="main" width="1%" valign="top" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
								  </tr>
								  <tr>
								    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
							      </tr>
								  </table>
								
								</td>
							  </tr>
							  <?php
							  if($edited_section_succesed == 'false' && $HTTP_GET_VARS['action_ajax_edit']!='cancel'){
							  ?>
							  <tr>
								<td colspan="3">
									<?php require(DIR_FS_MODULES . 'edit_checkout_address.php'); ?>
								</td>
							  </tr>
							  <?php
							  }
							  ?>
							</table>
							</div>
						</div>
					 <?php
					 }
			break;	
			case 'traveler_detail':
				$i = $HTTP_GET_VARS['product_rank'];
				
				//amit added add extra customer information start
				$need_add_extra_checkout_fields = false;
				$need_add_extra_features_note = false;
				$need_add_features_provider_note = false;
				$products_guestgender_array = array(array('id' => '', 'text' => general_to_ajax_string(PULL_DOWN_DEFAULT)));
				$products_guestgender_array[] = array('id' => general_to_ajax_string(TEXT_CHECKOUT_GENDER_MALE), 'text' => general_to_ajax_string(TEXT_CHECKOUT_GENDER_MALE));
				$products_guestgender_array[] = array('id' => general_to_ajax_string(TEXT_CHECKOUT_GENDER_FEMALE), 'text' => general_to_ajax_string(TEXT_CHECKOUT_GENDER_FEMALE));
				$products_guestweight_array = array();				
				$products_guestweight_array[] = array('id' => general_to_ajax_string(TEXT_CHECKOUT_WEIGHT_KG), 'text' => general_to_ajax_string(TEXT_CHECKOUT_WEIGHT_KG));
				$products_guestweight_array[] = array('id' => general_to_ajax_string(TEXT_CHECKOUT_WEIGHT_POUND), 'text' => general_to_ajax_string(TEXT_CHECKOUT_WEIGHT_POUND));
				if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_CHECKOUT_IDS.",") || in_array($order->products[$i]['agency_id'],explode(',','12,48'))) {
					$need_add_extra_checkout_fields = true;
				}
				if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_FEATURES_TOUR_IDS.",")) {
					$need_add_extra_features_note = true;
				}
				if($order->products[$i]['agency_id'] == 12){
					$need_add_features_provider_note = true;
				}		
				//amit added add extra customer information end		
				//this is for height
				$need_add_extra_checkout_fields_height = false;
				if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_HEIGHT_CHECKOUT_IDS.",")) {
						$need_add_extra_checkout_fields_height = true;
				}
				//end for height
			if($edited_section_succesed == 'false' && $HTTP_GET_VARS['action_ajax_edit']!='cancel'){
				// amit commented to remove child age ask																	
				$roomsinfo_string = trim($order->products[$i]['roomattributes'][3]);
				$ttl_rooms = get_total_room_from_str($roomsinfo_string);
				
				if($ttl_rooms>0){
				
					for($ir=0; $ir<$ttl_rooms; $ir++){
						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($order->products[$i]['roomattributes'][3],$ir+1);
						$totoal_child_room[$order->products[$i]['id']] = (int)$totoal_child_room[$order->products[$i]['id']] + $chaild_adult_no_arr[1];
					}
				}else{
						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($order->products[$i]['roomattributes'][3],1);
							$totoal_child_room[$order->products[$i]['id']] = $chaild_adult_no_arr[1];										
				}
				// amit commented to remove child age ask
					echo '<table width="100%" cellpadding="4" cellspacing="0" border="0">';
				
				  if ($messageStack->size('checkout_cfrm_trv_detail') > 0) {
				?>
				  <tr>
					<td colspan="3"><?php echo $messageStack->output('checkout_cfrm_trv_detail'); ?></td>
				  </tr>
				  <tr>
					<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				  </tr>
				<?php
				  }
				//amit added to check helicopter tour 
				if(tep_get_product_type_of_product_id((int)$order->products[$i]['id']) == 2){
				
						if($order->products[$i]['roomattributes'][2] != '')
						{
							$m=$order->products[$i]['roomattributes'][2];
							
							
							// amit commented to remove child age ask
							$tot_nos_of_child_in_tour = $m - (int)$totoal_child_room[$order->products[$i]['id']];
							/*echo 'm:'.$m;
							echo 'totoal_child_room'.(int)$totoal_child_room[$order->products[$i]['id']];
							echo 'tot_nos_of_child_in_tour:'.$tot_nos_of_child_in_tour;*/
							// amit commented to remove child age ask
							
							
							for($h=0; $h<$m; $h++)
							{
							
								// amit commented to remove child age ask
								$needed_show_child_title = false;
								$default_show_adult_title = TXT_DIS_ENTRY_GUEST_ADULT;
								if( ($h >= $tot_nos_of_child_in_tour) && $tot_nos_of_child_in_tour > 0){
									$needed_show_child_title =  true;
									$default_show_adult_title = TXT_DIS_ENTRY_GUEST_CHILD;
								}
								// amit commented to remove child age ask
							
								 if(($h%2)==0)
								 echo '<tr>';
							?>
							
								<td valign="top" width="50%"><table width="100%" cellpadding="2" cellspacing="0"><tr><td class="main" width="20%"><?php echo sprintf( general_to_ajax_string(TEXT_INFO_GUEST_NAME), ($h+1) );?> <?php echo general_to_ajax_string($default_show_adult_title).' ' ?>
								<br />
								<?php // echo TEXT_INFO_GUEST_NAME;?> <?php //echo ($h+1).'). '?>
								</td></tr>
								  
								  <?php
								  $guestname_title = str_replace(':','',ENTRY_GUEST_FIRST_NAME);
								  $guestname_title_en = str_replace(':','',ENTRY_GUEST_LAST_NAME.db_to_html(' 姓'));
								  
								  //结伴同游
								  if((int)$order->products[$i]['roomattributes'][5]){
								  	$mail_name = sprintf(db_to_html("拼房伴%s的帐号"),$h);
									$guestname_title = sprintf(db_to_html('拼房伴%s中文名'),$h);
									$guestname_title_en = sprintf(db_to_html('拼房伴%s护照英文名 姓'),$h);
									$readonly ='';
									$guestemail_onblur=' onBlur="check_and_get_guestname(&quot;GuestEmail'.$h.'['.$i.']&quot;, &quot;guestname'.$h.'['.$i.']&quot;)" ';
									if($h==0){
										$mail_name = db_to_html('我的注册邮箱');
										$GuestEmail = 'GuestEmail'.$h.'['.$i.']';
										$$GuestEmail = $customer_email_address;
										$guestname_title = db_to_html('顾客1中文名');
										$guestname_title_en = db_to_html('顾客1护照英文名 姓');
										$readonly = ' readonly="true" ';
										$guestemail_onblur ='';
									}
									//if($needed_show_child_title != true){
								  ?>
										  <tr>
											<td align="left" valign="top" nowrap="nowrap" style="padding-left: 20px;">
											<?php echo general_to_ajax_string(db_to_html('付款人：'));?>&nbsp;									
											
											
											<?php
											if($_SESSION['PayerMe'.$h][$i]=='1' || $h==0){
												$Payer_Me_Checked = true;
												$Payer_Me_Checked1 = false;
											}else{
												$Payer_Me_Checked = false;
												$Payer_Me_Checked1 = true;
											}
											echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '1',$Payer_Me_Checked).general_to_ajax_string(db_to_html(' 我付'));
											echo "&nbsp;";
											if($h>0){
												echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '0',$Payer_Me_Checked1).general_to_ajax_string(db_to_html(' 我不帮他付'));
											}
											?>
											
											</td>
										  </tr>
										  <tr>
											<td class="font_black" style="padding-left:20px;">
											<?php echo general_to_ajax_string($mail_name)?>&nbsp;									
											
											<?php echo tep_draw_input_field('GuestEmail'.$h.'['.$i.']', $_SESSION['GuestEmail'.$h][$i],' id="GuestEmail'.$h.'['.$i.']'.'" class="required" title="'.general_to_ajax_string(TEXT_PLEASE_INSERT_GUEST_EMAIL).'"'.$readonly.$guestemail_onblur);?>
											</td>
										  </tr>
								  <?php
								  	//}
								  }elseif(tep_not_null($_SESSION['GuestEmail'.$h][$i])){
								  	$_SESSION['GuestEmail'.$h][$i] = "";
								  }
								  //结伴同游
								  ?>
								
								<tr>
								<td class="font_black" style="padding-left:20px;"><?php echo general_to_ajax_string($guestname_title).'&nbsp;&nbsp;&nbsp;'.tep_draw_input_field('guestname'.$h.'['.$i.']', general_to_ajax_string(tep_db_prepare_input($_SESSION['guestname'.$h][$i])), ' id="guestname'.$h.'['.$i.']" class="required"'); ?>&nbsp;<span class="inputRequirement">*</span>								</td>
								</tr>
								<tr><td width="30%" class="font_black" style="padding-left:20px; ">
								<?php echo general_to_ajax_string($guestname_title_en).'&nbsp;'.tep_draw_input_field('GuestEngXing'.$h.'['.$i.']', general_to_ajax_string(tep_db_prepare_input($_SESSION['GuestEngXing'.$h][$i])), ' id="GuestEngXing'.$h.'['.$i.']" class="required" size="4" style="ime-mode: disabled;" '); ?>&nbsp;<span class="inputRequirement">*</span>
								<?php echo general_to_ajax_string(db_to_html(' 名')).'&nbsp;'.tep_draw_input_field('GuestEngName'.$h.'['.$i.']', general_to_ajax_string(tep_db_prepare_input($_SESSION['GuestEngName'.$h][$i])), ' id="GuestEngName'.$h.'['.$i.']" class="required" size="8" style="ime-mode: disabled;" '); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<tr><td width="30%" class="font_black" style="padding-left:20px; ">
								<?php echo general_to_ajax_string(TEXT_INFO_GUEST_BODY_WEIGHT).'&nbsp;'.tep_draw_pull_down_menu('guestweighttype'.$h.'['.$i.']', $products_guestweight_array,  general_to_ajax_string(tep_db_prepare_input($_SESSION['guestweighttype'.$h][$i])), ' id="guestweighttype'.$h.'['.$i.']" style="width:65px;"') .' '.tep_draw_input_field('guestbodyweight'.$h.'['.$i.']', $_SESSION['guestbodyweight'.$h][$i], ' class="skyborder"'); ?><span class="inputRequirement">*</span>
								</td></tr>
                                <?php if($need_add_extra_checkout_fields == true){ ?>
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php 
								echo general_to_ajax_string(ENTRY_GUEST_GENDER).'&nbsp;'. tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  general_to_ajax_string(tep_db_prepare_input($_SESSION['guestgender'.$h][$i])), ' id="guestgender'.$h.'['.$i.']" class="required selglobus"');?><span class="inputRequirement">*</span>
								</td></tr>
								<?php if($needed_show_child_title != true){ ?>
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php echo general_to_ajax_string(ENTRY_GUEST_DATE_OF_BIRTH).'&nbsp;'.tep_draw_input_field('guestdob'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestdob'.$h][$i]), ' size="10"  id="guestdob'.$h.'['.$i.']" class="required validate-date-us"'); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<?php }	?>
								<?php }	else if($order->products[$i]['is_gender_info'] == '1'){	?>
                                <tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php 
								echo general_to_ajax_string(ENTRY_GUEST_GENDER).'&nbsp;'. tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  general_to_ajax_string(tep_db_prepare_input($_SESSION['guestgender'.$h][$i])), ' id="guestgender'.$h.'['.$i.']" class="required selglobus"');?><span class="inputRequirement">*</span>
								</td></tr>
                                 <?php } ?>
								<?php
								
								// amit commented to remove child age ask
								if($needed_show_child_title == true){
								
								$dis_ext_clas_faild_for_wrong_chlidage = '';
								if(urldecode($HTTP_GET_VARS['wrgdate']) == tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]) && isset($HTTP_GET_VARS['wrgdate'])){
									$dis_ext_clas_faild_for_wrong_chlidage = ' validation-failed';
								}
								?>																					
								<tr><td width="30%" class="font_black" style="padding-left:20px;">
								<?php echo general_to_ajax_string(ENTRY_GUEST_CHILD_AGE).'&nbsp;'.tep_draw_input_field('guestchildage'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]), 'id="guestchildage'.$h.'['.$i.']" size="10" class="required validate-date-us'.$dis_ext_clas_faild_for_wrong_chlidage.'"'); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<?php
								}
								// amit commented to remove child age ask
								
								?>
								</table></td>
								
							<?php
								if(($h%2)!=0)
								echo '</tr>';
							}// end of for($h=0; $h<$m; $h++)
							if($need_add_features_provider_note == true){
								?><tr><td colspan="2"><div class="tipNote"><?php echo general_to_ajax_string(TEXT_FEATURES_PROVIDER_NOTE); ?></div></td></tr><?php
							}
						}
				
				}else{	
				
						if($order->products[$i]['roomattributes'][2] != '')
						{
							$m=$order->products[$i]['roomattributes'][2];
							
							
							// amit commented to remove child age ask
							$tot_nos_of_child_in_tour = $m - (int)$totoal_child_room[$order->products[$i]['id']];
							/*echo 'm:'.$m;
							echo 'totoal_child_room'.(int)$totoal_child_room[$order->products[$i]['id']];
							echo 'tot_nos_of_child_in_tour:'.$tot_nos_of_child_in_tour;*/
							// amit commented to remove child age ask
							
								
							for($h=0; $h<$m; $h++)
							{
							
								// amit commented to remove child age ask
								$needed_show_child_title = false;
								$default_show_adult_title = TXT_DIS_ENTRY_GUEST_ADULT;
								if( ($h >= $tot_nos_of_child_in_tour) && $tot_nos_of_child_in_tour > 0){
									$needed_show_child_title =  true;
									$default_show_adult_title = TXT_DIS_ENTRY_GUEST_CHILD;
								}
								// amit commented to remove child age ask
							
								 if(($h%2)==0)
								 echo '<tr>';
							?>
							
								<td width="50%" valign="top"><table width="100%" cellpadding="2" cellspacing="0"><tr><td class="main" width="20%"><?php echo sprintf( general_to_ajax_string(TEXT_INFO_GUEST_NAME),($h+1));?> <?php echo general_to_ajax_string($default_show_adult_title).' '?>
								</td></tr>
								  
								  <?php
								  $guestname_title = str_replace(':','',ENTRY_GUEST_FIRST_NAME);
								  $guestname_title_en = str_replace(':','',ENTRY_GUEST_LAST_NAME.db_to_html(' 姓'));
								  
								  //结伴同游
								  if((int)$order->products[$i]['roomattributes'][5]){
								  	$mail_name = sprintf(db_to_html("拼房伴%s的帐号"),$h);
									$guestname_title = sprintf(db_to_html('拼房伴%s中文名'),$h);
									$guestname_title_en = sprintf(db_to_html('拼房伴%s护照英文名 姓'),$h);
									$readonly ='';
									$guestemail_onblur=' onBlur="check_and_get_guestname(&quot;GuestEmail'.$h.'['.$i.']&quot;, &quot;guestname'.$h.'['.$i.']&quot;)" ';
									if($h==0){
										$mail_name = db_to_html('我的注册邮箱');
										$GuestEmail = 'GuestEmail'.$h.'['.$i.']';
										$$GuestEmail = $customer_email_address;
										$guestname_title = db_to_html('顾客1中文名');
										$guestname_title_en = db_to_html('顾客1护照英文名 姓');
										$readonly = ' readonly="true" ';
										$guestemail_onblur ='';
									}
									//if($needed_show_child_title != true){
								  ?>
										  <tr>
											<td align="left" valign="top" nowrap="nowrap" style="padding-left: 20px;">
											<?php echo general_to_ajax_string(db_to_html('付款人：'));?>&nbsp;									
											
											<?php
											if($_SESSION['PayerMe'.$h][$i]=='1' || $h==0){
												$Payer_Me_Checked = true;
												$Payer_Me_Checked1 = false;
											}else{
												$Payer_Me_Checked = false;
												$Payer_Me_Checked1 = true;
											}
											echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '1',$Payer_Me_Checked).general_to_ajax_string(db_to_html(' 我付'));
											echo "&nbsp;";
											if($h>0){
												echo tep_draw_radio_field('PayerMe'.$h.'['.$i.']', '0',$Payer_Me_Checked1).general_to_ajax_string(db_to_html(' 我不帮他付'));
											}
											?>
											
											</td>
										  </tr>
										  <tr>
											<td style="padding-left:20px;" class="font_black">
											<?php echo general_to_ajax_string($mail_name)?>&nbsp;									
											
											<?php echo tep_draw_input_field('GuestEmail'.$h.'['.$i.']', $_SESSION['GuestEmail'.$h][$i],' id="GuestEmail'.$h.'['.$i.']'.'" class="required" title="'.general_to_ajax_string(TEXT_PLEASE_INSERT_GUEST_EMAIL).'"'.$readonly.$guestemail_onblur);?>
											</td>
										  </tr>
								  <?php
								  	//}
								  }elseif(tep_not_null($_SESSION['GuestEmail'.$h][$i])){
								  	$_SESSION['GuestEmail'.$h][$i] = "";
								  }
								  //结伴同游
								  ?>
								
								<tr>
								<td style="padding-left:20px;" class="font_black">
								<?php echo general_to_ajax_string($guestname_title).'&nbsp;&nbsp;&nbsp;'.tep_draw_input_field('guestname'.$h.'['.$i.']', general_to_ajax_string(tep_db_prepare_input($_SESSION['guestname'.$h][$i])), 'id="guestname'.$h.'['.$i.']" class="required"'); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php echo general_to_ajax_string($guestname_title_en).'&nbsp;'.tep_draw_input_field('GuestEngXing'.$h.'['.$i.']', general_to_ajax_string(tep_db_prepare_input($_SESSION['GuestEngXing'.$h][$i])), 'id="GuestEngXing'.$h.'['.$i.']" class="required" size="4" style="ime-mode: disabled;" '); ?>&nbsp;<span class="inputRequirement">*</span>
								<?php echo general_to_ajax_string(db_to_html(' 名')).'&nbsp;'.tep_draw_input_field('GuestEngName'.$h.'['.$i.']', general_to_ajax_string(tep_db_prepare_input($_SESSION['GuestEngName'.$h][$i])), 'id="GuestEngName'.$h.'['.$i.']" class="required" size="8" style="ime-mode: disabled;" '); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<?php if($need_add_extra_checkout_fields_height == true){?>
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
									<?php 	echo general_to_ajax_string(ENTRY_GUEST_HEIGHT).'&nbsp;'. tep_draw_input_field('guestbodyheight'.$h.'['.$i.']', stripslashes($_SESSION['guestbodyheight'.$h][$i]), ' class="required" title="'.db_to_html('ō蔼').'" id="guestbodyheight'.$h.'_'.$i.'"'); ?><span class="inputRequirement">*</span>
									</td></tr>
								<?php } ?>
								<?php if($need_add_extra_checkout_fields == true){ ?>
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php 
								echo general_to_ajax_string(ENTRY_GUEST_GENDER).'&nbsp;'. tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  general_to_ajax_string(tep_db_prepare_input($_SESSION['guestgender'.$h][$i])), ' id="guestgender'.$h.'['.$i.']" class="required selglobus"');?><span class="inputRequirement">*</span>
								</td></tr>
								<?php if($needed_show_child_title != true){ ?>
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php echo general_to_ajax_string(ENTRY_GUEST_DATE_OF_BIRTH).'&nbsp;'.tep_draw_input_field('guestdob'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestdob'.$h][$i]), ' size="10"  id="guestdob'.$h.'['.$i.']" class="required validate-date-us"'); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<?php }	?>
								<?php }	else if($order->products[$i]['is_gender_info'] == '1'){	?>
                                <tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php 
								echo general_to_ajax_string(ENTRY_GUEST_GENDER).'&nbsp;'. tep_draw_pull_down_menu('guestgender'.$h.'['.$i.']', $products_guestgender_array,  general_to_ajax_string(tep_db_prepare_input($_SESSION['guestgender'.$h][$i])), ' id="guestgender'.$h.'['.$i.']" class="required selglobus"');?><span class="inputRequirement">*</span>
								</td></tr>
                                 <?php } ?>
								<?php
								
								// amit commented to remove child age ask
								if($needed_show_child_title == true){
								$dis_ext_clas_faild_for_wrong_chlidage = '';
								if(urldecode($HTTP_GET_VARS['wrgdate']) == tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]) && isset($HTTP_GET_VARS['wrgdate'])){
									$dis_ext_clas_faild_for_wrong_chlidage = ' validation-failed';
								}
								?>																					
								<tr><td width="30%" style="padding-left:20px;" class="font_black">
								<?php echo general_to_ajax_string(ENTRY_GUEST_CHILD_AGE).'&nbsp;'.tep_draw_input_field('guestchildage'.$h.'['.$i.']', tep_db_prepare_input($_SESSION['guestchildage'.$h][$i]), 'id="guestchildage'.$h.'['.$i.']" size="10" class="required validate-date-us'.$dis_ext_clas_faild_for_wrong_chlidage.'"'); ?>&nbsp;<span class="inputRequirement">*</span>
								</td></tr>
								<?php
								}
								// amit commented to remove child age ask
								
								?>
								
								</table></td>
								
							<?php
								if(($h%2)!=0)
								echo '</tr>';
							}// end of for($h=0; $h<$m; $h++)
							if($need_add_extra_features_note == true){
								?><tr><td colspan="2"><div class="tipNote"><?php echo general_to_ajax_string(TEXT_EXTRA_FEATURES_NOTE); ?></div></td></tr><?php
							}
							if($need_add_features_provider_note == true){
								?><tr><td colspan="2"><div class="tipNote"><?php echo general_to_ajax_string(TEXT_FEATURES_PROVIDER_NOTE); ?></div></td></tr><?php
							}
						}
				
				
				}
				//amit change to check helicopter tour
				
				
				echo '</tr><tr> <td colspan="4" class="main" valign="bottom">&nbsp;</td></tr><tr> <td colspan="4" height="10" class="gray_dotted_line" valign="bottom"></td></tr>
				<tr><td colspan="4" class="main"><b>' .general_to_ajax_string(TEXT_FLIGHT_INFO_IF_APPLICABLE). ' &nbsp; </b> </td></tr>
				 <tr>
					<td class="font_black" colspan="4">'. general_to_ajax_string(TEXT_FLIGHT_INFO_IF_AVAILABLE).'</td>
				  </tr>
				<tr> <td colspan="4" class="main"><div id="flight_info_'.$i.'">';
				?>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<?php /*<tr><td colspan="4" class="main"><b><?php echo TEXT_FLIGHT_INFO_IF_APPLICABLE;?></b></td></tr> */ ?>												
				 
				  <tr>
					<td class="main" width="20%"><?php echo general_to_ajax_string(TEXT_ARRIVAL_AIRLINE_NAME);?></td>
					<td width="30%"><?php echo tep_draw_input_field('airline_name['.$i.']', general_to_ajax_string($airline_name[$i]), ' class="skyborder"'); ?></td>
					<td class="main" width="20%"><?php echo general_to_ajax_string(TEXT_DEPARTURE_AIRLINE_NAME); ?></td>
					<td width="30%"><?php echo tep_draw_input_field('airline_name_departure['.$i.']', general_to_ajax_string($airline_name_departure[$i]), ' class="skyborder"'); ?></td>
				  </tr>
				  <tr>
					<td class="main"><?php echo general_to_ajax_string(TEXT_ARRIVAL_FLIGHT_NUMBER); ?></td>
					<td><?php echo tep_draw_input_field('flight_no['.$i.']', general_to_ajax_string($flight_no[$i]), ' class="skyborder"'); ?></td>
					<td class="main" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_DEPARTURE_FLIGHT_NUMBER); ?></td>
					<td><?php echo tep_draw_input_field('flight_no_departure['.$i.']', general_to_ajax_string($flight_no_departure[$i]), ' class="skyborder"'); ?></td>
				  </tr>
				  <tr>
					<td class="main"><?php echo general_to_ajax_string(TEXT_ARRIVAL_AIRPORT_NAME); ?></td>
					<td><?php echo tep_draw_input_field('airport_name['.$i.']', general_to_ajax_string($airport_name[$i]), ' class="skyborder"'); ?></td>
					<td class="main" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_DEPARTURE_AIRPORT_NAME); ?></td>
					<td><?php echo tep_draw_input_field('airport_name_departure['.$i.']', general_to_ajax_string($airport_name_departure[$i]), ' class="skyborder"'); ?></td>
				  </tr>
				  <tr>
					<td class="main"><?php echo general_to_ajax_string(TEXT_ARRIVAL_DATE);?><br />(mm/dd/yyyy)</td>
					<td><?php echo tep_draw_input_field('arrival_date'.$i, $arrival_date, ' class="skyborder"'); ?></td>
					<td class="main"><?php echo general_to_ajax_string(TEXT_DEPARTURE_DATE); ?><br />(mm/dd/yyyy)</td>
					<td><?php echo tep_draw_input_field('departure_date'.$i, $departure_date, ' class="skyborder"'); ?></td>
				  </tr>
				  <tr>
					<td class="main"><?php echo general_to_ajax_string(TEXT_ARRIVAL_TIME);?><br/>(HH:MM) e.g. 15:30 pm</td>
					<td valign="top"><?php
					//, 'onBlur="return IsValidTimeMilitry(this.value)";'
					 echo tep_draw_input_field('arrival_time['.$i.']', $arrival_time[$i],' class="skyborder"'); ?></td>
					<td class="main"><?php echo general_to_ajax_string(TEXT_DEPARTURE_TIME);?><br/>(HH:MM) e.g. 09:30 am</td>
					<td valign="top"><?php echo tep_draw_input_field('departure_time['.$i.']', $departure_time[$i],' class="skyborder"'); ?></td>
				  </tr>
				</table>
				<?php
				
				echo ' </div></td></tr>
				<tr><td align="center" colspan="2">';
				echo tep_draw_hidden_field('total_guest_number_m',$m);
				
				 echo tep_template_image_button('button_update.gif', general_to_ajax_string(IMAGE_BUTTON_UPDATE),' onclick="javascript: sendFormData(\'checkout_confirmation\',\'checkout_edit_sections_ajax.php?ajax=true&action_ajax_edit=process&ajax_section_name=traveler_detail&product_rank='.$i.$osCsid_string.'\',\'response_traveler_detail_div_'.$i.'\',\'true\');" style="cursor:pointer"');
				 echo tep_draw_separator('pixel_trans.gif', '25', '1');
				 echo tep_template_image_button('button_cancel.gif', general_to_ajax_string(IMAGE_BUTTON_CANCEL),' onclick="javascript: sendFormData(\'checkout_confirmation\',\'checkout_edit_sections_ajax.php?ajax=true&action_ajax_edit=cancel&ajax_section_name=traveler_detail&product_rank='.$i.$osCsid_string.'\',\'response_traveler_detail_div_'.$i.'\',\'true\');" style="cursor:pointer"');
				echo '</td></tr>
				</table>';
			
			
			}else{
					
					echo '<table width="100%" cellpadding="4" cellspacing="0" border="0">';
					//amit added to check helicopter tour 
					if(tep_get_product_type_of_product_id((int)$order->products[$i]['id']) == 2){
					
						if($order->products[$i]['roomattributes'][2] != '')
							{
								$m=$order->products[$i]['roomattributes'][2];
								for($h=0; $h<$m; $h++)
								{
									 if(($h%2)==0)
									 echo '<tr>';
								?>
								
									<td class="main_new_sky" width="2%" valign="top" nowrap="nowrap"><?php echo sprintf(general_to_ajax_string(TEXT_GUEST_NAME),($h+1)); echo '&nbsp;';
									if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
										echo '<br/>'.general_to_ajax_string(ENTRY_GUEST_HEIGHT);
									}
									if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
									echo "<br />".general_to_ajax_string(ENTRY_DATE_OF_BIRTH)." ";
									}
									if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
									echo "<br />".general_to_ajax_string(ENTRY_GENDER)." ";
									}
									else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){
									echo "<br />".general_to_ajax_string(ENTRY_DATE_OF_BIRTH)." ";
									}
									?>
									<br />
									<span style="white-space:nowrap"><?php echo general_to_ajax_string(TEXT_INFO_GUEST_BODY_WEIGHT);?> <?php echo ($h+1).'. ';?></span>
									</td>
									<td class="main" width="35%" valign="top"><b>
									<?php 
									echo general_to_ajax_string(tep_db_prepare_input($_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i]).tep_draw_hidden_field('guestname'.$h, $_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i], ''));
									if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
									echo '<br />'.general_to_ajax_string($_SESSION['guestgender'.$h][$i]);
									}
									if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){																																									
									echo '<br />'.general_to_ajax_string($_SESSION['guestchildage'.$h][$i]);
									}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){																																									
									echo '<br />'.general_to_ajax_string($_SESSION['guestdob'.$h][$i]);
									}
									
									if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
									echo '<br/>'.general_to_ajax_string($_SESSION['guestbodyheight'.$h][$i]);
									}
									
									?>
									
																													
									<br />
									<?php echo general_to_ajax_string($_SESSION['guestbodyweight'.$h][$i].tep_draw_hidden_field('guestbodyweight'.$h, $_SESSION['guestbodyweight'.$h][$i]), ''); ?>
                                    <?php echo general_to_ajax_string($_SESSION['guestweighttype'.$h][$i].tep_draw_hidden_field('guestweighttype'.$h, $_SESSION['guestweighttype'.$h][$i]), ''); ?>
									</b></td>
									
								<?php
									if(($h%2)!=0)
									echo '</tr>';
								}// end of for($h=0; $h<$m; $h++)
							}
					
					}else{
					
							if($order->products[$i]['roomattributes'][2] != '')
							{
								$m=$order->products[$i]['roomattributes'][2];
								for($h=0; $h<$m; $h++)
								{
									 if(($h%2)==0)
									 echo '<tr>';
								?>
								
									<td class="main_new_sky" width="2%" valign="top" nowrap="nowrap"><?php echo sprintf(general_to_ajax_string(TEXT_GUEST_NAME),($h+1)); echo '&nbsp;';
									if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
										echo "<br />".general_to_ajax_string(ENTRY_HEIGHT)." ";
									}
									if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
										echo "<br />".general_to_ajax_string(ENTRY_GENDER)." ";
										}
										if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
										echo "<br />".general_to_ajax_string(ENTRY_DATE_OF_BIRTH)." ";
										}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){
										echo "<br />".general_to_ajax_string(ENTRY_DATE_OF_BIRTH)." ";
										}
									?></td>
									<td class="main" width="35%" valign="top"><b>
									<?php 
									echo general_to_ajax_string(tep_db_prepare_input($_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i]));
									if(isset($_SESSION['guestbodyheight'.$h][$i]) && $_SESSION['guestbodyheight'.$h][$i] != ''){
										echo "<br />".general_to_ajax_string($_SESSION['guestbodyheight'.$h][$i]);
									}
									if(isset($_SESSION['guestgender'.$h][$i]) && $_SESSION['guestgender'.$h][$i] != ''){
									echo '<br />'.general_to_ajax_string($_SESSION['guestgender'.$h][$i]);
									}
									if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){																																									
									echo '<br />'.general_to_ajax_string($_SESSION['guestchildage'.$h][$i]);
									}else if(isset($_SESSION['guestdob'.$h][$i]) && $_SESSION['guestdob'.$h][$i] != ''){																																									
									echo '<br />'.general_to_ajax_string($_SESSION['guestdob'.$h][$i]);
									}
									echo tep_draw_hidden_field('guestname'.$h, general_to_ajax_string($_SESSION['guestname'.$h][$i].' '.$_SESSION['GuestEngXing'.$h][$i].$_SESSION['GuestEngName'.$h][$i]), ''); 
									?>
									</b></td>
									
								<?php
									if(($h%2)!=0)
									echo '</tr>';
								}// end of for($h=0; $h<$m; $h++)
							}
					
					}
					
					
					
					//echo ' </tr><tr> <td colspan="4" class="main_sky"><b>'.general_to_ajax_string(TEXT_FLIGHT_INFO_IF_APPLICABLE).'</b></td></tr>';
					?>
					<?php
					//单人部分配房 start
					if(isset($_SESSION['SingleName'][$i]) && $_SESSION['SingleName'][$i] != ''){
						echo '<tr><td colspan="5">';
						echo general_to_ajax_string(db_to_html('单人部分配房的客人姓名及性别：')).general_to_ajax_string(tep_db_prepare_input($_SESSION['SingleName'][$i]));
						if($_SESSION['SingleGender'][$i]=='m'){
							echo general_to_ajax_string(db_to_html('（男）'));
						}
						if($_SESSION['SingleGender'][$i]=='f'){
							echo general_to_ajax_string(db_to_html('（女）'));
						}
						echo tep_draw_hidden_field('SingleName['.$i.']', general_to_ajax_string($_SESSION['SingleName'][$i]));
						echo tep_draw_hidden_field('SingleGender['.$i.']', general_to_ajax_string($_SESSION['SingleGender'][$i]));
						echo '<br><br></td></tr>';
					}
					//单人部分配房 end
					?>
					<?php 
					echo '<tr> <td colspan="4" class="main">';
					?>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
					  <tr>
						<td class="main_new_sky" width="20%" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_ARRIVAL_AIRLINE_NAME)?>:</td>
						<td class="main" width="30%"><?php echo nl2br(tep_output_string_protected(general_to_ajax_string($order->info['airline_name'][$i]))) . tep_draw_hidden_field('airline_name['.$i.']', general_to_ajax_string($order->info['airline_name'][$i])); ?></td>
						<td class="main_new_sky" width="20%" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_DEPARTURE_AIRLINE_NAME)?>:</td>
						<td class="main" width="30%"><?php echo nl2br(tep_output_string_protected(general_to_ajax_string($order->info['airline_name_departure'][$i]))) . tep_draw_hidden_field('airline_name_departure['.$i.']', general_to_ajax_string($order->info['airline_name_departure'][$i])); ?></td>
					  </tr>
					  <tr>
						<td class="main_new_sky" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_ARRIVAL_FLIGHT_NUMBER)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected(general_to_ajax_string($order->info['flight_no'][$i]))) . tep_draw_hidden_field('flight_no['.$i.']', general_to_ajax_string($order->info['flight_no'][$i]));?></td>
						<td class="main_new_sky" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_DEPARTURE_FLIGHT_NUMBER)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected(general_to_ajax_string($order->info['flight_no_departure'][$i]))) . tep_draw_hidden_field('flight_no_departure['.$i.']', general_to_ajax_string($order->info['flight_no_departure'][$i]));?></td>
					  </tr>
					  <tr>
						<td class="main_new_sky" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_ARRIVAL_AIRPORT_NAME)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected(general_to_ajax_string($order->info['airport_name'][$i]))) . tep_draw_hidden_field('airport_name['.$i.']', general_to_ajax_string($order->info['airport_name'][$i]));?></td>
						<td class="main_new_sky" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_DEPARTURE_AIRPORT_NAME)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected(general_to_ajax_string($order->info['airport_name_departure'][$i]))) . tep_draw_hidden_field('airport_name_departure['.$i.']', general_to_ajax_string($order->info['airport_name_departure'][$i]));?></td>
					  </tr>
					  <tr>
						<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_ARRIVAL_DATE)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($_POST['arrival_date'.$i])) . tep_draw_hidden_field('arrival_date'.$i, $_POST['arrival_date'.$i]);?></td>
						<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_DEPARTURE_DATE)?></td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($_POST['departure_date'.$i])) . tep_draw_hidden_field('departure_date'.$i, $_POST['departure_date'.$i]);?></td>
					 </tr>
					  <tr>
						<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_ARRIVAL_TIME)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['arrival_time'][$i])) . tep_draw_hidden_field('arrival_time['.$i.']', $order->info['arrival_time'][$i]);?></td>
						<td class="main_new_sky"><?php echo general_to_ajax_string(TEXT_DEPARTURE_TIME)?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['departure_time'][$i])) . tep_draw_hidden_field('departure_time['.$i.']', $order->info['departure_time'][$i]);?></td>
					  </tr>
					  
					</table>
					<?php
					echo ' </td></tr>';
					
					echo '</table>';
					
				}
			break;
		}	//start switch of display html code
				
}
?>