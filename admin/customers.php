<?php
/*
  $Id: customers.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('customers');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setstate':
	 	if((int)$_GET['cID']){
			tep_db_query('update `customers` set customers_state="'.(int)$_GET['status'].'" where customers_id="'.(int)$HTTP_GET_VARS['cID'].'"');
		}
		break;
	  case 'view_credit_history':
	  	if(isset($HTTP_GET_VARS['adj_credits']) && $HTTP_GET_VARS['adj_credits'] == 'true'){
			
			if($HTTP_POST_VARS['credit_plus'] > 0 || $HTTP_POST_VARS['credit_minus'] > 0){
			if(tep_db_input($HTTP_POST_VARS['csr_comment']) == ''){
				$messageStack->add_session('Comments required!!', 'error');				
			}else{
				$customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
				$adjust_credits_amt = number_format($HTTP_POST_VARS['credit_plus'], 2, '.', '') - number_format($HTTP_POST_VARS['credit_minus'], 2, '.', '');
				
				$get_credit_amt = tep_db_fetch_array(tep_db_query("select customers_credit_issued_amt from ".TABLE_CUSTOMERS." where customers_id = ".$customers_id['customers_id']." "));				
					
				tep_db_query("update  ".TABLE_CUSTOMERS."  set customers_credit_issued_amt = ".($get_credit_amt['customers_credit_issued_amt'] + $adjust_credits_amt)." where customers_id = ".(int)$customers_id. " ");
				
				$sql_data_credits_array = array('customers_id' => (int)$customers_id,
												'orders_id' => tep_db_input($HTTP_POST_VARS['ch_orders_id']),
												'credit_bal' => $adjust_credits_amt,
												'credit_comment' => 'Adjusted by Admin',
												'date_added' => 'now()',
												'admin_id' => $login_id,
												'csr_comment' => tep_db_input($HTTP_POST_VARS['csr_comment'])
												);
				tep_db_perform(TABLE_CUSTOMERS_CREDITS, $sql_data_credits_array);
			}
			}
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('adj_credits'))));
		}		
	  break;
	  case 'update':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $customers_firstname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
        $customers_lastname = tep_db_prepare_input($HTTP_POST_VARS['customers_lastname']);
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);
        $customers_telephone = tep_db_prepare_input($HTTP_POST_VARS['customers_telephone']);
        $customers_cellphone = tep_db_prepare_input($HTTP_POST_VARS['customers_cellphone']);
        $customers_mobile_phone = tep_db_prepare_input($HTTP_POST_VARS['customers_mobile_phone']);
		
		$customers_fax = tep_db_prepare_input($HTTP_POST_VARS['customers_fax']);
        $customers_newsletter = tep_db_prepare_input($HTTP_POST_VARS['customers_newsletter']);

        $customers_gender = tep_db_prepare_input($HTTP_POST_VARS['customers_gender']);
        $customers_dob = tep_db_prepare_input($HTTP_POST_VARS['customers_dob']);
		$customers_group = tep_db_prepare_input($HTTP_POST_VARS['customers_group']);

        $default_address_id = tep_db_prepare_input($HTTP_POST_VARS['default_address_id']);
        $entry_street_address = tep_db_prepare_input($HTTP_POST_VARS['entry_street_address']);
        $entry_suburb = tep_db_prepare_input($HTTP_POST_VARS['entry_suburb']);
        $entry_postcode = tep_db_prepare_input($HTTP_POST_VARS['entry_postcode']);
        $entry_city = tep_db_prepare_input($HTTP_POST_VARS['entry_city']);
        $entry_country_id = tep_db_prepare_input($HTTP_POST_VARS['entry_country_id']);

        $entry_company = tep_db_prepare_input($HTTP_POST_VARS['entry_company']);
        $entry_state = tep_db_prepare_input($HTTP_POST_VARS['entry_state']);
        if (isset($HTTP_POST_VARS['entry_zone_id'])) $entry_zone_id = tep_db_prepare_input($HTTP_POST_VARS['entry_zone_id']);

        if (strlen($customers_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_firstname_error = true;
        } else {
          $entry_firstname_error = false;
        }

        if (strlen($customers_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_lastname_error = true;
        } else {
          $entry_lastname_error = false;
        }

        if (ACCOUNT_DOB == 'true') {
          if (checkdate(substr(tep_date_raw($customers_dob), 4, 2), substr(tep_date_raw($customers_dob), 6, 2), substr(tep_date_raw($customers_dob), 0, 4))) {
            $entry_date_of_birth_error = false;
          } else {
            $error = true;
            $entry_date_of_birth_error = true;
          }
        }

        if (strlen($customers_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_email_address_error = true;
        } else {
          $entry_email_address_error = false;
        }

        if (!tep_validate_email($customers_email_address)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }

        if (strlen($entry_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_street_address_error = true;
        } else {
          $entry_street_address_error = false;
        }

        if (strlen($entry_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
          $error = true;
          $entry_post_code_error = true;
        } else {
          $entry_post_code_error = false;
        }

        if (strlen($entry_city) < ENTRY_CITY_MIN_LENGTH) {
          $error = true;
          $entry_city_error = true;
        } else {
          $entry_city_error = false;
        }

        if ($entry_country_id == false) {
          $error = true;
          $entry_country_error = true;
        } else {
          $entry_country_error = false;
        }

        if (ACCOUNT_STATE == 'true') {
          if ($entry_country_error == true) {
            $entry_state_error = true;
          } else {
            $zone_id = 0;
            $entry_state_error = false;
            $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "'");
            $check_value = tep_db_fetch_array($check_query);
            $entry_state_has_zones = ($check_value['total'] > 0);
            if ($entry_state_has_zones == true) {
              $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "' and zone_name = '" . tep_db_input($entry_state) . "'");
              if (tep_db_num_rows($zone_query) == 1) {
                $zone_values = tep_db_fetch_array($zone_query);
                $entry_zone_id = $zone_values['zone_id'];
              } else {
                $error = true;
                $entry_state_error = true;
              }
            } else {
              if ($entry_state == false) {
                $error = true;
                $entry_state_error = true;
              }
            }
         }
      }

      if (strlen($customers_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
        $error = true;
        $entry_telephone_error = true;
      } else {
        $entry_telephone_error = false;
      }

      $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "' and customers_id != '" . (int)$customers_id . "'");
      if (tep_db_num_rows($check_email)) {
        $error = true;
        $entry_email_address_exists = true;
      } else {
        $entry_email_address_exists = false;
      }

      if ($error == false) {

        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_telephone' => $customers_telephone,
								'customers_cellphone' => $customers_cellphone,
								'customers_mobile_phone' => $customers_mobile_phone,
                                'customers_fax' => $customers_fax,
								'customers_group' => $customers_group,
                                'customers_newsletter' => $customers_newsletter);

        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");

        if ($entry_zone_id > 0) $entry_state = '';

        $sql_data_array = array('entry_firstname' => $customers_firstname,
                                'entry_lastname' => $customers_lastname,
                                'entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);

        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;

        if (ACCOUNT_STATE == 'true') {
          if ($entry_zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $entry_zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $entry_state;
          }
        }

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$default_address_id . "'");

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));

        } else if ($error == true) {
          $cInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }

        break;
      case 'deleteconfirm':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        if (isset($HTTP_POST_VARS['delete_reviews']) && ($HTTP_POST_VARS['delete_reviews'] == 'on')) {
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews['reviews_id'] . "'");
          }

          tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
        } else {
          tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . (int)$customers_id . "'");
        }

		$customers_email = tep_get_customers_email((int)$customers_id);
		tep_db_query("delete from `old_customer_testimonials` where new_customer_email_address = '".$customers_email."' || old_customer_email_address = '".$customers_email."' ");
		
        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");
        

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))));
        break;
      default:
$customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_cellphone, c.customers_mobile_phone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id, c.customers_group from
" . TABLE_CUSTOMERS . " c, 
" . TABLE_ADDRESS_BOOK . " a 
where
a.customers_id = c.customers_id and
a.address_book_id = c.customers_default_address_id and
c.customers_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
  if ($action == 'edit' || $action == 'update') {
?>
<script language="javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var customers_firstname = document.customers.customers_firstname.value;
  var customers_lastname = document.customers.customers_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
<?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
  var customers_email_address = document.customers.customers_email_address.value;
  var entry_street_address = document.customers.entry_street_address.value;
  var entry_postcode = document.customers.entry_postcode.value;
  var entry_city = document.customers.entry_city.value;
  var customers_telephone = document.customers.customers_telephone.value;

<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?php if (ACCOUNT_DOB == 'true') { ?>
  if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_DOB; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (entry_street_address == "" || entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (entry_postcode == "" || entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (entry_city == "" || entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php
  if (ACCOUNT_STATE == 'true') {
?>
  if (document.customers.elements['entry_state'].type != "hidden") {
    if (document.customers.entry_state.value == '' || document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  }
<?php
  }
?>

  if (document.customers.elements['entry_country_id'].type != "hidden") {
    if (document.customers.entry_country_id.value == 0) {
      error_message = error_message + "<?php echo JS_COUNTRY; ?>";
      error = 1;
    }
  }

  if (customers_telephone == "" || customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('customers');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($action == 'edit' || $action == 'update') {
    $newsletter_array = array(array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO),
                              array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES));
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_gender_error == true) {
        echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($cInfo->customers_gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('customers_gender');
      }
    } else {
      echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE;
    }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $cInfo->customers_firstname . tep_draw_hidden_field('customers_firstname');
    }
  } else {
    echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_lastname_error == true) {
      echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $cInfo->customers_lastname . tep_draw_hidden_field('customers_lastname');
    }
  } else {
    echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main">

<?php
    if ($error == true) {
      if ($entry_date_of_birth_error == true) {
        echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
      } else {
        echo $cInfo->customers_dob . tep_draw_hidden_field('customers_dob');
      }
    } else {
      echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"', true);
    }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_email_address_error == true) {
      echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error == true) {
      echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists == true) {
      echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $customers_email_address . tep_draw_hidden_field('customers_email_address');
    }
  } else {
    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
<?php
    if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_company_error == true) {
        echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
      } else {
        echo $cInfo->entry_company . tep_draw_hidden_field('entry_company');
      }
    } else {
      echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"');
    }
?></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_street_address_error == true) {
      echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $cInfo->entry_street_address . tep_draw_hidden_field('entry_street_address');
    }
  } else {
    echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_suburb_error == true) {
        echo tep_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
      } else {
        echo $cInfo->entry_suburb . tep_draw_hidden_field('entry_suburb');
      }
    } else {
      echo tep_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
    }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_post_code_error == true) {
      echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $cInfo->entry_postcode . tep_draw_hidden_field('entry_postcode');
    }
  } else {
    echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_city_error == true) {
      echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $cInfo->entry_city . tep_draw_hidden_field('entry_city');
    }
  } else {
    echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main">
<?php
    $entry_state = tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
    if ($error == true) {
      if ($entry_state_error == true) {
        if ($entry_state_has_zones == true) {
          $zones_array = array();
          $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
            $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          }
          echo tep_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
        } else {
          echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state)) . '&nbsp;' . ENTRY_STATE_ERROR;
        }
      } else {
        echo $entry_state . tep_draw_hidden_field('entry_zone_id') . tep_draw_hidden_field('entry_state');
      }
    } else {
      echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state));
    }

?></td>
         </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_country_error == true) {
      echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo tep_get_country_name($cInfo->entry_country_id) . tep_draw_hidden_field('entry_country_id');
    }
  } else {
    echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle">Contact Telephone</td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main">Office Phone: </td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_telephone_error == true) {
      echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $cInfo->customers_telephone . tep_draw_hidden_field('customers_telephone');
    }
  } else {
    echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true);
  }
?></td>
          </tr>

			 <!-- amit added for cell phone start -->
		   <tr>
            <td class="main">紧急联系电话:</td>
            <td class="main">
			<?php
			  if ($processed == true) {
				echo $cInfo->customers_cellphone . tep_draw_hidden_field('customers_cellphone');
			  } else {
				echo tep_draw_input_field('customers_cellphone', $cInfo->customers_cellphone, 'maxlength="32"');
			  }
			?></td>
          </tr>
		  <!-- amit added for cell phone end -->

          <tr>
            <td class="main">Home Phone: </td>
            <td class="main">
<?php
  if ($processed == true) {
    echo $cInfo->customers_fax . tep_draw_hidden_field('customers_fax');
  } else {
    echo tep_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"');
  }
?></td>

<!--howard added for 移动电话-->
          </tr>
          <tr>
            <td class="main">移动电话:</td>
            <td class="main">
<?php
  if ($processed == true) {
    echo $cInfo->customers_mobile_phone . tep_draw_hidden_field('customers_mobile_phone');
  } else {
    echo tep_draw_input_field('customers_mobile_phone', $cInfo->customers_mobile_phone, 'maxlength="32"');
  }
?></td>
          </tr>
<!--howard added for 移动电话 end-->
 
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
            <td class="main">
<?php
  if ($processed == true) {
    if ($cInfo->customers_newsletter == '1') {
      echo ENTRY_NEWSLETTER_YES;
    } else {
      echo ENTRY_NEWSLETTER_NO;
    }
    echo tep_draw_hidden_field('customers_newsletter');
  } else {
    echo tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, (($cInfo->customers_newsletter == '1') ? '1' : '0'));
  }
?></td>
          </tr>
          <tr>
            <td class="main">Customers Groups:</td>
            <td class="main">
<?php
$group_array=array();
$group_array[]=array('id'=>'0','text'=>'Ordinary Users');
$group_array[]=array('id'=>'1','text'=>'Tours Experts');
echo tep_draw_pull_down_menu('customers_group', $group_array, $cInfo->customers_group);
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main">
		<?php
		if($can_edit_customers_info === true ){
		?>		
		<input type="submit" value="更新" />
		<?php
		}else{
		?>
		<input type="button" value="更新" disabled />
		<?php
		}
		?>
		<input type="button" onClick="window.location=&quot;<?= tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')));?>&quot;" value="取消" />
		
		</td>
      </tr></form>
<?php
  } else if($action == 'view_credit_history'){
?>
	  <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			<?php
			$pending_points_query_raw = "select unique_id, orders_id, products_id, credit_bal, date_added, credit_comment,  admin_id, csr_comment from " . TABLE_CUSTOMERS_CREDITS . " where customers_id = '" . (int)$HTTP_GET_VARS['cID'] . "' order by unique_id desc";
			
			$pending_points_split = new splitPageResults($HTTP_GET_VARS['page1'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $pending_points_query_raw, $pending_points_query_numrows);
   			$pending_points_query = tep_db_query($pending_points_query_raw);
			//$pending_points_query = tep_db_query($pending_points_query);
		
			if (tep_db_num_rows($pending_points_query)) {
			?>
			<table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr class="dataTableHeadingRow">
			  	<td class="dataTableHeadingContent"><?php echo HEADING_ORDER_DATE; ?></td>
				<td class="dataTableHeadingContent"><?php echo HEADING_ORDERS_NUMBER; ?></td>
				<td class="dataTableHeadingContent"><?php echo HEADING_POINTS_STATUS; ?></td>
				<td class="dataTableHeadingContent"><?php echo HEADING_POINTS_COMMENT; ?></td>
				<td class="dataTableHeadingContent"><?php echo HEADING_POINTS_TOTAL; ?></td>	
                <td class="dataTableHeadingContent">Updated By</td>	
                <td class="dataTableHeadingContent">Comments</td>	
			  </tr>
			  <?php
			  $total_credit_bal = 0;
			  while ($pending_points = tep_db_fetch_array($pending_points_query)) {
			  if ($pending_points['orders_id'] > 0) {
			  	/*if($pending_points['credit_type'] == 'HR'){
					$row_link = tep_href_link(FILENAME_HR_HONEYMOON_REGISTRIES,'customer_id=' . (int)$HTTP_GET_VARS['cID'] . '&action=show_gift_received');
				}else{*/
					$row_link = tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $pending_points['orders_id']);
				//}
			  ?>
			  <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo $row_link; ?>'" title="<?php echo TEXT_ORDER_HISTORY .'&nbsp;' . $pending_points['orders_id']; ?>">
			  <?php
			  }else{
			  ?>
			  <tr class="dataTableRow">
			  <?php
			  }
			  ?>
                <td class="dataTableContent"><?php echo tep_date_short($pending_points['date_added']); ?></td>
				<td class="dataTableContent"><?php echo '#' . $pending_points['orders_id']; ?></td>
				<td class="dataTableContent"><?php echo  tep_get_products_model($pending_points['products_id']); ?></td>
				<td class="dataTableContent"><?php echo  nl2br($pending_points['credit_comment']); ?></td>
				<td class="dataTableContent"><?php echo number_format($pending_points['credit_bal'],2); ?></td>
                <td class="dataTableContent"><?php echo tep_get_admin_customer_name($pending_points['admin_id']); ?></td>
                <td class="dataTableContent"><?php echo  nl2br($pending_points['csr_comment']); ?></td>
			  </tr>
			  <?php
			  $total_credit_bal = $total_credit_bal + $pending_points['credit_bal'];
			  }//end while
			  ?>
			  <tr>
			  	<td class="dataTableContent" colspan="4" align="right"><b>Total: </b></td>
			  	<td class="dataTableContent"><b><?php echo number_format($total_credit_bal,2); ?></b></td>
			  </tr>
              
              <tr>
              	<td colspan="5" align="left">
                <a href="javascript:toggel_div('show_adjust_credit_div');">Adjust Credits</a><br /><br />
                <div id="show_adjust_credit_div">

                	<?php echo tep_draw_form('adj_credits', FILENAME_CUSTOMERS, tep_get_all_get_params() . 'adj_credits=true', 'post'); ?>
                    <table cellpadding="5" cellspacing="0" style="border:1px solid black;">
                    	<tr><td colspan="2" height="10"></td></tr>
                        <tr>
                        	<td class="main">Add Credit: </td>
                            <td><input type="text" name="credit_plus" size="10" /></td>
                        </tr>
                        <tr>
                        	<td class="main">Remove Credit: </td>
                            <td><input type="text" name="credit_minus" size="10" /></td>
                        </tr>
                        <tr>
                        	<td class="main">Order ID: </td>
                            <td><input type="text" name="ch_orders_id" size="10" /></td>
                        </tr>
                        <tr>
                        	<td class="main">Comments: </td>
                            <td><input type="text" name="csr_comment" size="20" /></td>
                        </tr>
                        <tr>
                        	<td class="main"></td>
                            <td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
                        </tr>
                        <tr><td colspan="2" height="10"></td></tr>
                    </table>
                    <?php echo '</form>'; ?>
                </div>
                <br />
                </td>
              </tr>
			</table>
			<?php
			}else{
				echo 'No History found!!';
			}
			?>
			</td>
		  </tr>
		  <tr>
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr>
				<td class="smallText" valign="top"><?php echo $pending_points_split->display_count($pending_points_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page1'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
				<td class="smallText" align="right"><?php echo $pending_points_split->display_links($pending_points_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page1'], tep_get_all_get_params(array('info', 'x', 'y', 'page1')), 'page1'); ?></td>
			  </tr>
			</table></td><?php  ?>
		  </tr>
		  <tr>
		  	<td align="center"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action','page1'))) . '">' . tep_image_button('button_back.gif', IMAGE_CANCEL) . '</a>'; ?></td>
		  </tr>
		</table>
		</td>
	  </tr>	 
<?php  
  }else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', FILENAME_CUSTOMERS, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
<?php
  $HEADING_LASTNAME = TABLE_HEADING_LASTNAME."<br>";
  $HEADING_LASTNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=lastname&order=ascending">';
  $HEADING_LASTNAME .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
  $HEADING_LASTNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=lastname&order=decending">';
  $HEADING_LASTNAME .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
  $HEADING_FIRSTNAME = TABLE_HEADING_FIRSTNAME."<br>";
  $HEADING_FIRSTNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=firstname&order=ascending">';
  $HEADING_FIRSTNAME .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
  $HEADING_FIRSTNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=firstname&order=decending">';
  $HEADING_FIRSTNAME .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
  $HEADING_ACCOUNT_CREATED = TABLE_HEADING_ACCOUNT_CREATED."<br>";
  $HEADING_ACCOUNT_CREATED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=account_created&order=ascending">';
  $HEADING_ACCOUNT_CREATED .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
  $HEADING_ACCOUNT_CREATED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=account_created&order=decending">';
  $HEADING_ACCOUNT_CREATED .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
  $HEADING_CREDIT_ISSUED = TABLE_HEADING_CREDIT_ISSUED."<br>";
  $HEADING_CREDIT_ISSUED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=credit_issued&order=ascending">';
  $HEADING_CREDIT_ISSUED .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
  $HEADING_CREDIT_ISSUED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=credit_issued&order=decending">';
  $HEADING_CREDIT_ISSUED .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
?>
                <td class="dataTableHeadingContent"><?php echo $HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo $HEADING_FIRSTNAME; ?></td>
				<!--<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AD; ?></td>-->
                 <td class="dataTableHeadingContent"><?php echo  TABLE_HEADING_REFURL; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo $HEADING_ACCOUNT_CREATED; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo $HEADING_CREDIT_ISSUED; ?></td>
                <td class="dataTableHeadingContent" align="right">状态</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      if (strpos($keywords, " ") === false){
	  	//echo 'false';
		$search_extra = "";
	  }else{
	  	//echo 'true';
		$keyword_split = explode(" ",$keywords);
      	$search_extra = " or c.customers_firstname like '%" . $keyword_split[0] . "%' and c.customers_lastname like '%" . $keyword_split[1] . "%' or c.customers_firstname like '%" . $keyword_split[1] . "%' and c.customers_lastname like '%" . $keyword_split[0] . "%'";
	  }
	  $search = " and (c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%'".$search_extra.")";
    }
    // BOM Mod:provide an order by option
    $sortorder = 'order by c.customers_lastname, c.customers_firstname';
    switch ($_GET["sort"]) {
      case 'lastname':
        if($_GET["order"]==ascending) {
          $sortorder = ' order by c.customers_lastname  asc';
        } else {
          $sortorder = ' order by c.customers_lastname  desc';
        }
        break;
      case 'firstname':
        if($_GET["order"]==ascending) {
          $sortorder = ' order by c.customers_firstname  asc';
        } else {
          $sortorder = ' order by c.customers_firstname  desc';
        }
        break;
		case 'credit_issued':
        if($_GET["order"]==ascending) {
          $sortorder = 'order by c.customers_credit_issued_amt  asc';
        } else {
          $sortorder = 'order by c.customers_credit_issued_amt  desc';
        }
        break;
      default:
        if($_GET["order"]==ascending) {
          $sortorder = ' order by c.customers_id  asc';
        } else {
          $sortorder = ' order by c.customers_id  desc';
        }
        break;
    }

//left(c.customers_referer_url, 100) as customers_referer_url

//    $customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by c.customers_lastname, c.customers_firstname";
 $customers_query_raw = "select c.customers_id, c.customers_referer_url, c.customers_advertiser, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id,  c.customers_credit_issued_amt, c.customers_state from
" . TABLE_CUSTOMERS . " c, 
" . TABLE_ADDRESS_BOOK . " a
where
a.customers_id = c.customers_id and
a.address_book_id = c.customers_default_address_id 
" . $search . $sortorder;
// EOM mod
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons,customers_info_register_ip as register_ip from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");
        $country = tep_db_fetch_array($country_query);

        $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

        $customer_info = array_merge((array)$country, (array)$info, (array)$reviews);

        $cInfo_array = array_merge((array)$customers, (array)$customer_info);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $customers['customers_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $customers['customers_firstname']; ?></td>
				<!--<td class="dataTableContent"><?php echo $customers['customers_advertiser']; ?></td> -->
				 <td class="dataTableContent"><a target="_blank" href="<?php echo $customers['customers_referer_url']; ?>">
				 <?php 
				 if($customers['customers_referer_url'] != '') {
				 echo substr($customers['customers_referer_url'], 0, 60); 
				 echo ' ...';
				 }
				 ?> 
				 </a></td>
                <td class="dataTableContent" align="right"><?php echo tep_datetime_short($info['date_account_created']); ?></td>
				<td class="dataTableContent" align="right"><?php echo '$'.number_format($customers['customers_credit_issued_amt'], 2); ?></td>
                <td class="dataTableContent" align="right"><?php
					  if ($customers['customers_state'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('customers.php', 'action=setstate&status=0&cID=' . $customers['customers_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link('customers.php', 'action=setstate&status=1&cID=' . $customers['customers_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');

        if($can_edit_customers_info === true){
			$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=view_credit_history') . '">' . tep_image_button('button_credit_history.gif', IMAGE_VIEW_CREDIT_HISTORY) . '</a>');
		}
		
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($cInfo->date_account_created));
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->date_account_last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($cInfo->date_last_logon));
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $cInfo->number_of_logons);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' ' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $cInfo->number_of_reviews);
		 $contents[] = array('text' => '<br>Registered IP :  <a target="_blank" href="http://www.ip138.com/ips.asp?ip='.$cInfo->register_ip.'&action=2">' . $cInfo->register_ip.'</a>');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>