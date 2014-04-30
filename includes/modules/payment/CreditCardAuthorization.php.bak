<?php

  class CreditCardAuthorization {
    var $code, $title, $description, $enabled, $currency;

// class constructor
    function CreditCardAuthorization() {
      $this->code = 'CreditCardAuthorization';
      $this->title = db_to_html('信用卡支付授权');
	  $this->sort_order = MODULE_PAYMENT_CREDITCARDAUTHORIZATION_SORT_ORDER;
      $this->description = db_to_html('
      		<ul>
      			<li>
      			<p class="color_orange" style="font-weight:bold">我们接受Visa、MasterCard、American Express,不接受Discover。<br/>同时我们接受美国银行Debit Card（美国银行卡）。</p>
      			<p><b>我们需要哪些正式文件和证明:</b><br>1.信用卡持有人签名和日期的信用卡支付授权书(点击链接下载验证书)。<br>2.签名的机票行程单。<br>3. 如果信用卡持有人不是乘客，我们需要用来支付的信用卡的正、反面影印本；信用卡持有人有效身份证件的影印本(有效身份证件包括您的护照或由美国签发的带有本人签名的驾驶执照)。</p>
      			<p><b>寄发以上文件的方式:</b></p>
      			<p style="padding-left:20px;background:url(/image/nav/mail_03.jpg) no-repeat">电子邮箱：将相关证实文件和证明的影印本，扫描本或者数码照片发送至flight#usitrip.com (首选方式)(发邮件时，请把#换成@)</p>
      			<p style="padding-left:20px;background:url(/image/nav/fax_06.jpg) no-repeat">传真： ' . MODULE_PAYMENT_CREDITCARDAUTHORIZATION_FAX . '</p>
      			<p style="text-align:right"><a href="' . MODULE_PAYMENT_CREDITCARDAUTHORIZATION_DOWNLOAD . '" target="_blank" class="download" style="background:url(/image/nav/download_10.jpg);color: #FFFFFF;
    display: inline-block;
    font-weight: bold;
    height: 30px;
    line-height: 30px;
    text-indent: 10px;
    text-align:left;float:none;
    width: 220px;">Download信用卡支付授权书</a></li></ul>');
      $this->email_footer = '';
      $this->enabled = MODULE_PAYMENT_CREDITCARDAUTHORIZATION_STATUS;
      $this->currency = MODULE_PAYMENT_CREDITCARDAUTHORIZATION_CURRENCY;

    }
// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      $warm_tips = '<div>'.$this->description.'</div>';	//温馨提示栏
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
                'fields' => array(array('title' => $this->description)));

      return $confirmation;
    }

// Below is the original pre-November snapshot code.  I have left it souly for the less technical minded might
// be able to compare what some of the more indepth changes consisted of.  Perhaps it will facilitate more preNov
// Snapshots to being modified to postNov snapshot compatibility -- Thomas Keats

//    function confirmation() {
//      $confirmation_string = '          <tr>' . "\n" .
//                             '            <td class="main">&nbsp;' . MODULE_PAYMENT_CREDITCARDAUTHORIZATION_TEXT_DESCRIPTION . $
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function install() {
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('信用卡支付授权', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_STATUS', '1', '是否开启信用卡支付授权', '6', '1', 'tep_cfg_select_option_change_display(array(\'1\', \'0\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
	  		values ('传真号码：', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_FAX', '626-768-3706', '', '6', '1', now());");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
	  		values ('下载地址：', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_DOWNLOAD', '', '授权书下载地址.', '6', '1', now());");
      //tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account No.', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_ACCNUM', '229041631154', 'Account #:', '6', '1', now());");
   	  //tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Routing No.', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_ROUNUM', '026009593', 'Routing #:', '6', '1', now());");
   	  //tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SWIFT Code', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_SORTCODE', 'BOFAUS3N', 'SWIFT #:', '6', '1', now());");
   	  //tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('描述说明(支持html)', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_DESCRIPTION', '', '', '6', '1', now());");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '1', now())"); 
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('币种', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_CURRENCY', 'USD', '币种', '6', '2', now())"); 
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_FAX'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_DOWNLOAD'");
      //tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_ACCNAM'");
      //tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_BANKNAM'");
	  //tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_ROUNUM'");
	  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_SORT_ORDER'");
	  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_CURRENCY'");
	  //tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_DESCRIPTION'");
	  
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_CREDITCARDAUTHORIZATION_STATUS', 'MODULE_PAYMENT_CREDITCARDAUTHORIZATION_SORT_ORDER','MODULE_PAYMENT_CREDITCARDAUTHORIZATION_CURRENCY','MODULE_PAYMENT_CREDITCARDAUTHORIZATION_FAX','MODULE_PAYMENT_CREDITCARDAUTHORIZATION_DOWNLOAD');

      return $keys;
    }
  }
?>
