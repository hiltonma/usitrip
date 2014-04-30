<?php
/*
$Id: TRANSFER.php,v 1.10 2003/01/29 19:57:14 hpdl Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

class transfer{
	var $code, $title, $description, $enabled, $currency;

	// class constructor
	function transfer() {
		global $order;

		$this->code = 'transfer';
		$this->title = MODULE_PAYMENT_TRANSFER_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_TRANSFER_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_TRANSFER_STATUS == 'True') ? true : false);
		$this->currency = MODULE_PAYMENT_TRANSFER_CURRENCY;
		if ((int)MODULE_PAYMENT_TRANSFER_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_TRANSFER_ORDER_STATUS_ID;
		}

		if (is_object($order)) $this->update_status();

		$this->email_footer = MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER;
	}

	// class methods
	function update_status() {
		global $order;

		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_TRANSFER_ZONE > 0) ) {
			$check_flag = false;
			$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_TRANSFER_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
			while ($check = tep_db_fetch_array($check_query)) {
				if ($check['zone_id'] < 1) {
					$check_flag = true;
					break;
				} elseif ($check['zone_id'] == $order->billing['zone_id']) {
					$check_flag = true;
					break;
				}
			}

			if ($check_flag == false) {
				$this->enabled = false;
			}
		}
	}

	function javascript_validation() {
		return false;
	}

	function selection() {
		//温馨提示栏
		$warm_tips = '<div>'.MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION.'</div>';

		return array('id' => $this->code,
		'module' => $this->title,
		'warm_tips' => $warm_tips,
		'currency' => (tep_not_null($this->currency) ? $this->currency : 'USD'));
	}

	function pre_confirmation_check() {
		return false;
	}

	function confirmation() {
		return array('title' => MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION);
	}

	function process_button() {
		return false;
	}

	function before_process() {
		return false;
	}

	function after_process() {
		return false;
	}

	function get_error() {
		return false;
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_TRANSFER_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('银行转帐模组开关', 'MODULE_PAYMENT_TRANSFER_STATUS', 'True', '是否接受银行转帐或汇款？', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('收款人:', 'MODULE_PAYMENT_TRANSFER_PAYTO', '', '输入收款人姓名', '6', '1', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('帐号:', 'MODULE_PAYMENT_TRANSFER_ACCOUNT', '', '输入银行帐号', '6', '1', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('开户银行:', 'MODULE_PAYMENT_TRANSFER_BANK', '', '输入开户银行名称', '6', '1', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('币种:', 'MODULE_PAYMENT_TRANSFER_CURRENCY', 'CNY', '币种', '6', '100', now());");

		//BANK_ACCOUNT_NUM是zhh添加的，用於指定银行帐户的个数可在configuration表中找到它
		if(defined('BANK_ACCOUNT_NUM') && BANK_ACCOUNT_NUM >0){
			for($i=1;$i<BANK_ACCOUNT_NUM; $i++){
				tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('收款人[".$i."]:', 'MODULE_PAYMENT_TRANSFER_PAYTO".$i."', '', '输入收款人姓名".$i."', '6', '2', now());");
				tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('帐号[".$i."]:', 'MODULE_PAYMENT_TRANSFER_ACCOUNT".$i."', '', '输入银行帐号".$i."', '6', '2', now());");
				tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('开户银行[".$i."]:', 'MODULE_PAYMENT_TRANSFER_BANK".$i."', '', '输入开户银行名称".$i."', '6', '2', now());");
			}
		}

		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_TRANSFER_SORT_ORDER', '0', '排序顺序显示。最低的是首先显示。', '6', '0', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_TRANSFER_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_TRANSFER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		$array = array('MODULE_PAYMENT_TRANSFER_PAYTO', 'MODULE_PAYMENT_TRANSFER_ACCOUNT', 'MODULE_PAYMENT_TRANSFER_BANK');
		$array_2 = array('MODULE_PAYMENT_TRANSFER_STATUS', 'MODULE_PAYMENT_TRANSFER_ZONE', 'MODULE_PAYMENT_TRANSFER_ORDER_STATUS_ID', 'MODULE_PAYMENT_TRANSFER_SORT_ORDER','MODULE_PAYMENT_TRANSFER_CURRENCY');
		$array_1 = array();
		if(defined('BANK_ACCOUNT_NUM') && BANK_ACCOUNT_NUM >0){
			for($i=1;$i<BANK_ACCOUNT_NUM; $i++){
				$tmp_array = array('MODULE_PAYMENT_TRANSFER_PAYTO'.$i, 'MODULE_PAYMENT_TRANSFER_ACCOUNT'.$i, 'MODULE_PAYMENT_TRANSFER_BANK'.$i);
				$array_1 = array_merge((array)$array_1, (array)$tmp_array);
			}
		}

		return array_merge((array)$array, (array)$array_1, (array)$array_2);
	}
}
?>