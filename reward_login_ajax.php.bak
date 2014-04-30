<?php
/*
  $Id: login.php,v 1.1.1.1 2004/03/04 23:38:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
/* if ($session_started == false) {
  tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  } */
if (isset($_POST['aryFormData'])) {
    $aryFormData = $_POST['aryFormData'];

    foreach ($aryFormData as $key => $value) {
        foreach ($value as $key2 => $value2) {
            $HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;', '&', $value2));
        }
    }

    if (isset($aryFormData['rating'])) {
        foreach ($aryFormData['rating'] as $rat_key => $rat_val) {

            if ($aryFormData['rating'][$rat_key] == "true") {
                //$HTTP_POST_VARS['rating'] = $key+1;
                $HTTP_POST_VARS['rating'] = (int) $rat_key + 1;
            }
        }
    }
    require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

    if ($HTTP_POST_VARS['ajxsub_send_login'] == '1') {
        $ajxsub_send = $HTTP_POST_VARS['ajxsub_send_login'];
    }

    $error = false;

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process_without_login')) {
        $customer_review_process_without_login = 'customer_review_process_without_login';
        tep_session_register('customer_review_process_without_login');
        echo '<br>' . TEXT_CONTINUE_WITHOUT_LOGIN_MESSAGE;
        exit;
    }

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
        $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
        $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists
        $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_lastname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
        if (!tep_db_num_rows($check_customer_query)) {
            $error = true;
        } else {
            $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
            if (!tep_validate_password($password, $check_customer['customers_password'])) {
                $error = true;
            } else {
                if (SESSION_RECREATE == 'True') {
                    tep_session_recreate();
                }

                $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $check_customer['customers_id'] . "' and address_book_id = '" . (int) $check_customer['customers_default_address_id'] . "'");
                $check_country = tep_db_fetch_array($check_country_query);

                $customer_id = $check_customer['customers_id'];
                $customer_default_address_id = $check_customer['customers_default_address_id'];
                $customer_first_name = $check_customer['customers_firstname'];
                $customer_last_name = $check_customer['customers_lastname'];
                $customer_country_id = $check_country['entry_country_id'];
                $customer_zone_id = $check_country['entry_zone_id'];
                $affiliate_id = $check_customer['customers_id'];


                //repeat_royal_customer_discount



                tep_session_register('customer_id');
                tep_session_register('customer_default_address_id');
                tep_session_register('customer_first_name');
                tep_session_register('customer_last_name');
                tep_session_register('customer_country_id');
                tep_session_register('customer_zone_id');

                tep_session_register('affiliate_id');


                $royal_customers_query_raw = tep_db_query("select orders_id from " . TABLE_ORDERS . " o, orders_status s where o.orders_status = s.orders_status_id and s.language_id = '" . (int) $languages_id . "' and o.orders_status='100006' and o.customers_id='" . $customer_id . "' ");

                if (tep_db_num_rows($royal_customers_query_raw) > 0) {
                    //echo tep_draw_hidden_field('gv_redeem_code_royal_customer_reward', md5($osCsid.$customer_id));
                    //$repeat_royal_customer_discount = 'apply_discount';
                    //tep_session_register('repeat_royal_customer_discount');

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

// restore cart contents
                //$cart->restore_contents();

                if (sizeof($navigation->snapshot) > 0) {
                    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
                    $navigation->clear_snapshot();
                    /* if(!isset($ajxsub_send)){
                      tep_redirect($origin_href);
                      } */
                } else {
                    //tep_redirect(tep_href_link(FILENAME_DEFAULT, '', NONSSL));
                    /* if(!isset($ajxsub_send)){
                      tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', NONSSL));
                      } */
                }
            }
        }
    }

    if ($error == true) {
        $messageStack->add('login', TEXT_LOGIN_ERROR);
    }

    // $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));

    if (($messageStack->size('login') > 0) || (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'show_login_form'))) {
        if ($messageStack->size('login') > 0) {
            $error_message = preg_replace('/&nbsp;|\n|\t/', '', strip_tags($messageStack->output('login')));
            echo '<table width="100%"  border="0" cellspacing="5" cellpadding="5">
	  <tr>
		<td bgcolor="#FFE1E1" class="messageStackError">' . $error_message . '</td>
	  </tr>
	</table>';
        }

        if (!isset($customer_id)) {
            echo tep_draw_form('login', '', '', 'id="login"');
?>
            <div class="tip"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
            <div class="con"><h3><?php echo ENTRY_EMAIL_ADDRESS; ?></h3><?php echo tep_draw_input_field('email_address', '',' class="text" '); ?>&nbsp;*</div>
            <div class="con"><h3><?php echo ENTRY_PASSWORD; ?></h3><?php echo tep_draw_password_field('password', '', ' class="text" '); ?>&nbsp;*</div>
<?php
            echo tep_draw_hidden_field('ajxsub_send_login', '1');
            if (isset($HTTP_POST_VARS['response_div_name'])) {
                echo tep_draw_hidden_field('tab_name', tep_db_prepare_input($HTTP_POST_VARS['tab_name']));
                echo tep_draw_hidden_field('response_div_name', tep_db_prepare_input($HTTP_POST_VARS['response_div_name']));
            }
            if (isset($HTTP_GET_VARS['successtoggeldiv']) && $HTTP_GET_VARS['successtoggeldiv'] != '') {
                $extra_quert_string = '&successtoggeldiv=' . $HTTP_GET_VARS['successtoggeldiv'];
            }
?> 
            <div class="submit"><a href="javascript:;" onClick="sendFormData('login','<?php echo tep_href_link('reward_login_ajax.php', 'action=process' . $extra_quert_string); ?>','<?php echo tep_db_prepare_input($HTTP_POST_VARS['response_div_name']); ?>','true');" class="btn btnOrange"><button type="button"><?php echo db_to_html("µÇÂ¼") ?></button></a></div>
            
            
<?php
            echo '</form>';
        } else {
            echo '<br>' . TEXT_ALREADY_LOGIN_MESSAGE;
        }//if customer id	
    } else {
        if (isset($HTTP_GET_VARS['successtoggeldiv']) && $HTTP_GET_VARS['successtoggeldiv'] != '') {
            echo 'successtoggeldiv|###|' . trim($HTTP_GET_VARS['successtoggeldiv']) . '|###|';
        }
        echo '<br><table border="0" width="98%" align="center" id="success_review_fad_out_id"  class="automarginclass" cellspacing="2" cellpadding="2">
<tr class="messageStackSuccess">
<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp; ' . TEXT_SUCCESS_LOGIN_MESSAGE . /* tep_db_input($HTTP_POST_VARS['tab_name']). */'.</td>
</tr>
</table>'; //<a style="CURSOR: pointer" onclick="javascript:toggel_div(\'sign_in_form_id\');toggel_div(\'write_review_form_id\');" class="sp3" title="Click here to Submit Your Review">click here</a> to 
//<a style="CURSOR: pointer" onclick="javascript:toggel_div(\'write_review_form_id\');" class="sp3" title="Click here to Submit Your Review"><img style="cursor: pointer; cursor: hand; " src="image/buttons/english/button_submit_reviews.gif" border="0" alt="Submit Your Review" /></a>
        exit;
    }
    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'show_login_form')) {
        exit;
    }
} //end of ajax if
?>