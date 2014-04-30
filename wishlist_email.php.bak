<?php
/*
 $Id: wishlist_email.php,v 2.0  2004/08/11

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

 if (tep_session_is_registered('customer_id')) {
    $account = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $account_values = tep_db_fetch_array($account);
  } elseif (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false') {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $error = false;
$_POST['to_email_address'] = preg_replace( "/\n/", " ", $_POST['to_email_address'] );
$_POST['to_name'] = preg_replace( "/\n/", " ", $_POST['to_name'] );
$_POST['to_email_address'] = preg_replace( "/\r/", " ", $_POST['to_email_address'] );
$_POST['to_name'] = preg_replace( "/\r/", " ", $_POST['to_name'] );
$_POST['to_email_address'] = str_replace("Content-Type:","",$_POST['to_email_address']);
$_POST['to_name'] = str_replace("Content-Type:","",$_POST['to_name']);




    $to_email_address = tep_db_prepare_input($HTTP_POST_VARS['to_email_address']);
    $to_name = tep_db_prepare_input($HTTP_POST_VARS['to_name']);
    $from_email_address = tep_db_prepare_input($HTTP_POST_VARS['from_email_address']);
    $from_name = tep_db_prepare_input($HTTP_POST_VARS['from_name']);
    $message = tep_db_prepare_input($HTTP_POST_VARS['message']);

    if (empty($from_name)) {
      $error = true;

      $messageStack->add('friend', ERROR_FROM_NAME);
    }

    if (!tep_validate_email($from_email_address)) {
      $error = true;

      $messageStack->add('friend', ERROR_FROM_ADDRESS);
    }

    if (empty($to_name)) {
      $error = true;

      $messageStack->add('friend', ERROR_TO_NAME);
    }

    if (!tep_validate_email($to_email_address)) {
      $error = true;

      $messageStack->add('friend', ERROR_TO_ADDRESS);
    }

    if ($error == false) {
      $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
      $email_body = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $product_info['products_name'], STORE_NAME) . "\n\n";

      if (tep_not_null($message)) {
        $email_body .= $message . "\n\n";
      }

      $email_body .= sprintf(TEXT_EMAIL_LINK, tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'])) . "\n\n" .
                     sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");

      tep_mail($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);

      $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');

      tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));
    }
  } elseif (tep_session_is_registered('customer_id')) {
    $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
    $account = tep_db_fetch_array($account_query);

    $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
    $from_email_address = $account['customers_email_address'];
  }
 require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_WISHLIST_SEND);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WISHLIST_SEND, '', 'NONSSL'));

$content = CONTENT_WISHLIST_SEND;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
