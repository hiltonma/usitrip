<?php



  class cashdeposit {

    var $code, $title, $description, $enabled, $currency;

// class constructor

    function cashdeposit() {
      $this->code = 'cashdeposit';
      $this->title = MODULE_PAYMENT_CASEDEPOSIT_TEXT_TITLE;
	   $this->sort_order = MODULE_PAYMENT_CASEDEPOSIT_SORT_ORDER;
      $this->description = MODULE_PAYMENT_CASEDEPOSIT_TEXT_DESCRIPTION;
      $this->email_footer = MODULE_PAYMENT_CASEDEPOSIT_TEXT_EMAIL_FOOTER;
      $this->enabled = MODULE_PAYMENT_CASEDEPOSIT_STATUS;
      $this->currency = MODULE_PAYMENT_CASEDEPOSIT_CURRENCY;
    }

// class methods

    function javascript_validation() {

      return false;

    }



    function selection() {

      $warm_tips = '<div>'.MODULE_PAYMENT_CASEDEPOSIT_TEXT_DESCRIPTION.'</div>';	//温馨提示栏
	  return array('id' => $this->code,
                   'module' => $this->title,
				   'warm_tips' => $warm_tips,
				   'currency' => (tep_not_null($this->currency) ? $this->currency : 'USD'));

    } 

//    function selection() {

//      return false;

//    }



    function pre_confirmation_check() {

      return false;

    }





// I take no credit for this, I just hunted down variables, the actual code was stolen from the 2checkout

// module.  About 20 minutes of trouble shooting and poof, here it is. -- Thomas Keats

    function confirmation() {

      global $HTTP_POST_VARS;



      $confirmation = array('title' => $this->title . ': ' . $this->check,

                'fields' => array(array('title' => MODULE_PAYMENT_CASEDEPOSIT_TEXT_DESCRIPTION)));



      return $confirmation;

    }



// Below is the original pre-November snapshot code.  I have left it souly for the less technical minded might

// be able to compare what some of the more indepth changes consisted of.  Perhaps it will facilitate more preNov

// Snapshots to being modified to postNov snapshot compatibility -- Thomas Keats



//    function confirmation() {

//      $confirmation_string = '          <tr>' . "\n" .

//                             '            <td class="main">&nbsp;' . MODULE_PAYMENT_CASEDEPOSIT_TEXT_DESCRIPTION . $

//                             '          </tr>' . "\n";

//      return $confirmation_string;

//    }



    function process_button() {

      return false;

    }



    function before_process() {

      return false;

    }



    function after_process() {

      return false;

    }



    function output_error() {

      return false;

    }



    function check() {

      if (!isset($this->check)) {

        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_STATUS'");

        $this->check = tep_db_num_rows($check_query);

      }

      return $this->check;

    }



    function install() {

	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Cash Deposit Module', 'MODULE_PAYMENT_CASEDEPOSIT_STATUS', '1', 'Do you want to accept Cash Deposit Order payments?', '6', '1', 'tep_cfg_select_option_change_display(array(\'1\', \'0\'), ', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Name', 'MODULE_PAYMENT_CASEDEPOSIT_BANKNAM', 'Bank of America', 'Bank Name:', '6', '1', now());");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account Name', 'MODULE_PAYMENT_CASEDEPOSIT_ACCNAM', 'Unitedstars International Ltd.', 'Account Name:', '6', '1', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account No.', 'MODULE_PAYMENT_CASEDEPOSIT_ACCNUM', '229041631154', 'Account #:', '6', '1', now());");
   	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Routing No.', 'MODULE_PAYMENT_CASEDEPOSIT_ROUNUM', '063100277', 'Routing #:', '6', '1', now());");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Name 1', 'MODULE_PAYMENT_CASEDEPOSIT_BANKNAM_1', 'Chase', 'Bank Name:', '6', '1', now());");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account Name 1', 'MODULE_PAYMENT_CASEDEPOSIT_ACCNAM_1', 'Unitedstars International Ltd.', 'Account Name:', '6', '1', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account No. 1', 'MODULE_PAYMENT_CASEDEPOSIT_ACCNUM_1', '768285207 ', 'Account #:', '6', '1', now());");
   	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Routing No. 1', 'MODULE_PAYMENT_CASEDEPOSIT_ROUNUM_1', '065400137 ', 'Routing #:', '6', '1', now());");
	  
  	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_CASEDEPOSIT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '1', now())"); 
  	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('币种', 'MODULE_PAYMENT_CASEDEPOSIT_CURRENCY', 'USD', '币种', '6', '2', now())");
	  

    }



    function remove() {

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_ACCNUM'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_ACCNUM_1'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_ACCNAM'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_ACCNAM_1'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_BANKNAM'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_BANKNAM_1'");
	  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_ROUNUM'");
	  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_ROUNUM_1'");
	  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_SORT_ORDER'");
	  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CASEDEPOSIT_CURRENCY'");

    }



    function keys() {
      $keys = array('MODULE_PAYMENT_CASEDEPOSIT_STATUS', 'MODULE_PAYMENT_CASEDEPOSIT_BANKNAM', 'MODULE_PAYMENT_CASEDEPOSIT_ACCNAM',  'MODULE_PAYMENT_CASEDEPOSIT_ACCNUM', 'MODULE_PAYMENT_CASEDEPOSIT_ROUNUM' , 'MODULE_PAYMENT_CASEDEPOSIT_SORT_ORDER', 'MODULE_PAYMENT_CASEDEPOSIT_CURRENCY','MODULE_PAYMENT_CASEDEPOSIT_ACCNUM_1','MODULE_PAYMENT_CASEDEPOSIT_ACCNAM_1','MODULE_PAYMENT_CASEDEPOSIT_BANKNAM_1','MODULE_PAYMENT_CASEDEPOSIT_ROUNUM_1');
      return $keys;

    }

  }

?>