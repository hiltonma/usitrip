<?php
/*
  $Id: address_book_process.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

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
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'deleteconfirm') && isset($HTTP_GET_VARS['delete']) && is_numeric($HTTP_GET_VARS['delete'])) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$HTTP_GET_VARS['delete'] . "' and customers_id = '" . (int)$customer_id . "'");

    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
  }

// error checking when updating or adding an entry
  $process = false;
  if (isset($HTTP_POST_VARS['action']) && (($HTTP_POST_VARS['action'] == 'process') || ($HTTP_POST_VARS['action'] == 'update'))) {
    $process = true;
    $error = false;

    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    if (ACCOUNT_STATE == 'true') {
      if (isset($HTTP_POST_VARS['zone_id'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
      } else {
        $zone_id = false;
      }
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
    }

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_CITY_ERROR);
    }

    if (!is_numeric($country)) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
//State abbreviation bug fix applied DMG 10/1/2004
//Allows abbreviation of states and does not complain about capitalization
$zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . html_to_db(tep_db_input($state)) . "' OR zone_code = '" . html_to_db(tep_db_input($state)) . "')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR_SELECT);

        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR);
        }
      }
    }

    if ($error == false) {
      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => (int)$country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = (int)$zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      if ($HTTP_POST_VARS['action'] == 'update') {
        $sql_data_array = html_to_db ($sql_data_array);
		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$HTTP_GET_VARS['edit'] . "' and customers_id ='" . (int)$customer_id . "'");

// reregister session variables
        if ( (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) || ($HTTP_GET_VARS['edit'] == $customer_default_address_id) ) {
          $customer_first_name = $firstname;
          $customer_country_id = $country_id;
          $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
          $customer_default_address_id = (int)$HTTP_GET_VARS['edit'];

          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname,
                                  'customers_default_address_id' => (int)$HTTP_GET_VARS['edit']);

          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;

          $sql_data_array = html_to_db ($sql_data_array);
		  tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
        }
      } else {
        $sql_data_array['customers_id'] = (int)$customer_id;
        $sql_data_array = html_to_db ($sql_data_array);
		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

        $new_address_book_id = tep_db_insert_id();

// reregister session variables
        if (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) {
          $customer_first_name = $firstname;
          $customer_country_id = $country_id;
          $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
          if (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) $customer_default_address_id = $new_address_book_id;

          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname);

          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
          if (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) $sql_data_array['customers_default_address_id'] = $new_address_book_id;

          $sql_data_array = html_to_db ($sql_data_array);
		  tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
        }
      }

      $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
    
	$entry_query = tep_db_query("select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$HTTP_GET_VARS['edit'] . "'");

    if (!tep_db_num_rows($entry_query)) {
      $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = tep_db_fetch_array($entry_query);

  } elseif (isset($HTTP_GET_VARS['delete']) && is_numeric($HTTP_GET_VARS['delete'])) {
    if ($HTTP_GET_VARS['delete'] == $customer_default_address_id) {
      $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$HTTP_GET_VARS['delete'] . "' and customers_id = '" . (int)$customer_id . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] < 1) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
      }
    }
  } else {
    $entry = array();
  }

  if (!isset($HTTP_GET_VARS['delete']) && !isset($HTTP_GET_VARS['edit'])) {
    if (tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

  if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $HTTP_GET_VARS['edit'], 'SSL'));
  } elseif (isset($HTTP_GET_VARS['delete']) && is_numeric($HTTP_GET_VARS['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $HTTP_GET_VARS['delete'], 'SSL'));
  } else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
  }

$validation_include_js = 'true';
  $content = CONTENT_ADDRESS_BOOK_PROCESS;
  $javascript = $content . '.php';
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
