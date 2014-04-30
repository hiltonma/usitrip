<?php
/*
  $Id: create_account_process.php,v 1.3 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 1.0
  Customer Entry through Admin
*/

  require('includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_PROCESS);

if (!@$HTTP_POST_VARS['action']) {
   tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL'));
 }

  $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
  $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
  $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
  $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
  $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
  $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
  $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
  $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
  $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
  $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
  $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
  $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
  $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
  $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
  $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
  $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
  $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);


  /////////////////      RAMDOMIZING SCRIPT BY PATRIC VEVERKA       \\\\\\\\\\\\\\\\\\

$t1 = date("mdy");
srand ((float) microtime() * 10000000);
$input = array ("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z");
$rand_keys = array_rand ($input, 3);
$l1 = $input[$rand_keys[0]];
$r1 = rand(0,9);
$l2 = $input[$rand_keys[1]];
$l3 = $input[$rand_keys[2]];
$r2 = rand(0,9);

$password = $l1.$r1.$l2.$l3.$r2;

/////////////////    End of Randomizing Script   \\\\\\\\\\\\\\\\\\\



  $error = false; // reset error flag

  if (ACCOUNT_GENDER == 'true') {
    if (($gender == 'm') || ($gender == 'f')) {
      $entry_gender_error = false;
    } else {
      $error = true;
      $entry_gender_error = true;
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_firstname_error = true;
  } else {
    $entry_firstname_error = false;
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_lastname_error = true;
  } else {
    $entry_lastname_error = false;
  }

  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
      $entry_date_of_birth_error = false;
    } else {
      $error = true;
      $entry_date_of_birth_error = true;
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_email_address_error = true;
  } else {
    $entry_email_address_error = false;
  }

 if (!tep_validate_email($email_address)) {
    $error = true;
    $entry_email_address_check_error = true;
  } else {
    $entry_email_address_check_error = false;
  }

  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_street_address_error = true;
  } else {
    $entry_street_address_error = false;
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $entry_post_code_error = true;
  } else {
    $entry_post_code_error = false;
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $entry_city_error = true;
  } else {
    $entry_city_error = false;
  }

  if (!$country) {
    $error = true;
    $entry_country_error = true;
  } else {
    $entry_country_error = false;
  }

  if (ACCOUNT_STATE == 'true') {
    if ($entry_country_error) {
      $entry_state_error = true;
    } else {
      $zone_id = 0;
      $entry_state_error = false;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "'");
      $check_value = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check_value['total'] > 0);
      if ($entry_state_has_zones) {
        $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_name = '" . tep_db_input($state) . "'");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone_values = tep_db_fetch_array($zone_query);
          $zone_id = $zone_values['zone_id'];
        } else {
          $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_code = '" . tep_db_input($state) . "'");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone_values = tep_db_fetch_array($zone_query);
            $zone_id = $zone_values['zone_id'];
          } else {
            $error = true;
            $entry_state_error = true;
          }
        }
      } else {
        if (!$state) {
          $error = true;
          $entry_state_error = true;
        }
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $entry_telephone_error = true;
  } else {
    $entry_telephone_error = false;
  }

/*  $passlen = strlen($password);
  if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;
    $entry_password_error = true;
  } else {
    $entry_password_error = false;
  }

  if ($password != $confirmation) {
    $error = true;
    $entry_password_error = true;
  }*/

  $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id <> '" . tep_db_input($customer_id) . "'");
  if (tep_db_num_rows($check_email)) {
    $error = true;
    $entry_email_address_exists = true;
  } else {
    $entry_email_address_exists = false;
  }

  if ($error == true) {
    $processed = true;

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (sizeof($navigation->snapshot) > 0) {
?>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  //$email_address = tep_db_prepare_input($HTTP_GET_VARS['email_address']);
  $account['entry_country_id'] = STORE_COUNTRY;

  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONTINUE); ?></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  } else  {
    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_fax' => $fax,
                            'customers_newsletter' => $newsletter,
            				'customers_password' => tep_encrypt_password($password),
                            'customers_default_address_id' => 1);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $customer_id = tep_db_insert_id();

    $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
      if ($zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $state;
      }
    }

    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

    $address_id = tep_db_insert_id();

    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

    $customer_first_name = $firstname;
	$customer_last_name = $lastname;
    $customer_default_address_id = 1;
    $customer_country_id = $country;
    $customer_zone_id = $zone_id;
    tep_session_register('customer_id');
    tep_session_register('customer_first_name');
	tep_session_register('customer_last_name');
    tep_session_register('customer_default_address_id');
    tep_session_register('customer_country_id');
    tep_session_register('customer_zone_id');

    // build the message content
    $name = $firstname . " " . $lastname;

    if (ACCOUNT_GENDER == 'true') {
       if ($HTTP_POST_VARS['gender'] == 'm') {
         $email_text = EMAIL_GREET_MR;
       } else {
         $email_text = EMAIL_GREET_MS;
       }
    } else {
      $email_text = EMAIL_GREET_NONE;
    }

    $email_text .= EMAIL_WELCOME . EMAIL_PASS_1 . $password . EMAIL_PASS_2 . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    @tep_mail($name, $email_address, EMAIL_SUBJECT, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

     //tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
	
	tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, 'forcesumit=true&Customer='.$customer_id, 'SSL'));
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
