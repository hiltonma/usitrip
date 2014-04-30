<?php
/*
  $Id: affiliate_account_details.php,v 2.0 2002/09/29 SDK

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

//
echo __FILE__."<br>此模块已经Stop use!";
exit;
//


  if (!isset($is_read_only)) $is_read_only = false;
  if (!isset($processed)) $processed = false;
?>
<table border="0" width="90%" class="automarginclass"  align='center' cellspacing="0" cellpadding="2">
  <tr>
    <td class="formAreaTitle"><br /><?php echo CATEGORY_PAYMENT_DETAILS; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if (AFFILIATE_USE_CHECK == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_CHECK; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_check']);
    } elseif ($error == true) {
      if ($entry_payment_check_error == true) {
        echo tep_draw_input_field('a_payment_check') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_CHECK_ERROR;
      } else {
        echo $a_payment_check . tep_draw_hidden_field('a_payment_check');
      }
    } else {
      echo tep_draw_input_field('a_payment_check', db_to_html($affiliate['affiliate_payment_check'])) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_CHECK_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_PAYPAL == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_PAYPAL; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_paypal']);
    } elseif ($error == true) {
      if ($entry_payment_paypal_error == true) {
        echo tep_draw_input_field('a_payment_paypal') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_PAYPAL_ERROR;
      } else {
        echo $a_payment_paypal . tep_draw_hidden_field('a_payment_paypal');
      }
    } else {
      echo tep_draw_input_field('a_payment_paypal', db_to_html($affiliate['affiliate_payment_paypal'])) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_PAYPAL_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_BANK == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;</td>
		  </tr>
          <tr>
            <td class="main" colspan="2">&nbsp;<b><?php echo CHINA_CUST_BANKS_MSN; ?></b></td>
		  </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_NAME; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_bank_name']);
    } elseif ($error == true) {
      if ($entry_payment_bank_name_error == true) {
        echo tep_draw_input_field('a_payment_bank_name') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_NAME_ERROR;
      } else {
        echo $a_payment_bank_name . tep_draw_hidden_field('a_payment_bank_name');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_name', db_to_html($affiliate['affiliate_payment_bank_name'])) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_NAME_TEXT;
    }
?>
            </td>
          </tr>

<!--取消 银行代码          
		  <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
/*取消 银行代码
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_bank_branch_number']);
    } elseif ($error == true) {
      if ($entry_payment_bank_branch_number_error == true) {
        echo db_to_html(tep_draw_input_field('a_payment_bank_branch_number')) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER_ERROR;
      } else {
        echo db_to_html($a_payment_bank_branch_number . tep_draw_hidden_field('a_payment_bank_branch_number'));
      }
    } else {
      echo db_to_html(tep_draw_input_field('a_payment_bank_branch_number', $affiliate['affiliate_payment_bank_branch_number'])) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER_TEXT;
    }
*/
?>
            </td>
          </tr>
-->		  

<!--取消 分行代码
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE; ?></td>
            <td class="main">&nbsp;
<?php
/*取消 分行代码
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_bank_swift_code']);
    } elseif ($error == true) {
      if ($entry_payment_bank_swift_code_error == true) {
        echo db_to_html(tep_draw_input_field('a_payment_bank_swift_code')) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE_ERROR;
      } else {
        echo db_to_html($a_payment_bank_swift_code . tep_draw_hidden_field('a_payment_bank_swift_code'));
      }
    } else {
      echo db_to_html(tep_draw_input_field('a_payment_bank_swift_code', $affiliate['affiliate_payment_bank_swift_code'])) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE_TEXT;
    }
*/
?>
            </td>
          </tr>
-->		  
		  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_bank_account_name']);
    } elseif ($error == true) {
      if ($entry_payment_bank_account_name_error == true) {
        echo tep_draw_input_field('a_payment_bank_account_name') . '&nbsp;' ;//. ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME_ERROR;
      } else {
        echo $a_payment_bank_account_name . tep_draw_hidden_field('a_payment_bank_account_name');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_account_name', db_to_html($affiliate['affiliate_payment_bank_account_name'])) . '&nbsp;' ;//. ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo db_to_html($affiliate['affiliate_payment_bank_account_number']);
    } elseif ($error == true) {
      if ($entry_payment_bank_account_number_error == true) {
        echo tep_draw_input_field('a_payment_bank_account_number') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER_ERROR;
      } else {
        echo db_to_html($a_payment_bank_account_number) . tep_draw_hidden_field('a_payment_bank_account_number');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_account_number', db_to_html($affiliate['affiliate_payment_bank_account_number'])) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?> 
	      </table></td>
      </tr>
    </table></td>
  </tr>
  
  <tr>
    <td class="formAreaTitle"><br /><?php echo CATEGORY_CONTACT; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
        
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_HOMEPAGE; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_homepage'];
  } elseif ($error == true) {
    if ($entry_homepage_error == true) {
      echo tep_draw_input_field('a_homepage') . '&nbsp;' . ENTRY_AFFILIATE_HOMEPAGE_ERROR;
    } else {
      echo $a_homepage . tep_draw_hidden_field('a_homepage');
    }
  } else {
    echo tep_draw_input_field('a_homepage', $affiliate['affiliate_homepage']) . '&nbsp;' . ENTRY_AFFILIATE_HOMEPAGE_TEXT;
  }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  if ($is_read_only == false) {
?>
 
  <tr>
    <td class="formAreaTitle"><br /></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;
<?php 
	echo tep_draw_checkbox_field('a_agb', $value = '1', $checked = $affiliate['affiliate_agb']) .' '. ENTRY_AFFILIATE_ACCEPT_AGB;
    if ($entry_agb_error == true) {
      echo "<br />".ENTRY_AFFILIATE_AGB_ERROR;
    }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  }
?>
</table>
