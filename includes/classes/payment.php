<?php
/*
$Id: payment.php,v 1.1.1.1 2004/03/04 23:40:46 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

class payment {
	var $modules, $selected_module;

	// class constructor
	function payment($module = '') {
		// BOF: WebMakers.com Added: Downloads Controller
		global $payment, $language, $PHP_SELF, $cart, $travel_pay;
		// EOF: WebMakers.com Added: Downloads Controller

		if (defined('MODULE_PAYMENT_INSTALLED') && tep_not_null(MODULE_PAYMENT_INSTALLED)) {
			$this->modules = explode(';', MODULE_PAYMENT_INSTALLED);

			$include_modules = array();

			if ( (tep_not_null($module)) && (in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
				$this->selected_module = $module;

				$include_modules[] = array('class' => $module, 'file' => $module . '.php');

			} else {
				reset($this->modules);
				// BOF: WebMakers.com Added: Downloads Controller - Free Shipping and Payments
				// Show either normal payment modules or free payment module when Free Shipping Module is On
				// Free Payment Only

				if (tep_get_configuration_key_value('MODULE_PAYMENT_FREECHARGER_STATUS') and ($cart->show_total()==0 and $cart->show_weight==0) and $travel_pay!=true) {
					$this->selected_module = $module;
					$include_modules[] = array('class'=> 'freecharger', 'file' => 'freecharger.php');
				} else {
					// All Other Payment Modules
					while (list(, $value) = each($this->modules)) {
						$class = substr($value, 0, strrpos($value, '.'));
						// Don't show Free Payment Module
						if ($class !='freecharger') {
							$include_modules[] = array('class' => $class, 'file' => $value);
						}
					}
					// EOF: WebMakers.com Added: Downloads Controller
				}
			}

			for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
				include(DIR_FS_LANGUAGES . $language . '/modules/payment/' . $include_modules[$i]['file']);
				include(DIR_FS_MODULES . 'payment/' . $include_modules[$i]['file']);

				$GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
			}

			// if there is only one payment method, select it as default because in
			// checkout_confirmation.php the $payment variable is being assigned the
			// $HTTP_POST_VARS['payment'] value which will be empty (no radio button selection possible)
			if ( (tep_count_payment_modules() == 1) && (!isset($GLOBALS[$payment]) || (isset($GLOBALS[$payment]) && !is_object($GLOBALS[$payment]))) ) {
				$payment = $include_modules[0]['class'];
			}

			if ( (tep_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url)) ) {
				$this->form_action_url = $GLOBALS[$module]->form_action_url;
			}
		}
	}

	// class methods
	/* The following method is needed in the checkout_confirmation.php page
	due to a chicken and egg problem with the payment class and order class.
	The payment modules needs the order destination data for the dynamic status
	feature, and the order class needs the payment module title.
	The following method is a work-around to implementing the method in all
	payment modules available which would break the modules in the contributions
	section. This should be looked into again post 2.2.
	*/
	function update_status() {
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module])) {
				if (function_exists('method_exists')) {
					if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
						$GLOBALS[$this->selected_module]->update_status();
					}
				} else { // PHP3 compatibility
					//@call_user_method('update_status', $GLOBALS[$this->selected_module]);
					@call_user_func('update_status', $GLOBALS[$this->selected_module]);
				}
			}
		}
	}

	function javascript_validation() {
		$js = '';
		if (is_array($this->modules)) {
			$js = '<script type="text/javascript"><!-- ' . "\n" .
			'function check_form() {' . "\n" .
			'  var error = 0;' . "\n" .
			'  var error_message = "' . JS_ERROR . '";' . "\n" .
			'  var payment_value = null;' . "\n" .
			'var i = 0 ;' . "\n" .
			/*'for ( i= 0 ; i < window.document.forms[0].elements.length ; i ++)' . "\n" .
			'{' . "\n" .
			'if(window.document.forms[0].elements[i].name.substr(0,9) == "guestname")' . "\n" .
			'{' . "\n" .
			'if(window.document.forms[0].elements[i].value == "")' . "\n" .
			'{' . "\n" .
			'alert("'.TEXT_PLEASE_INSERT_GUEST_NAME.'");' . "\n" .
			'window.document.forms[0].elements[i].focus();' . "\n" .
			'return false;' . "\n" .
			'}' . "\n" .
			'}' . "\n" .

			'if(window.document.forms[0].elements[i].name.substr(0,12) == "guestsurname")' . "\n" .
			'{' . "\n" .
			'if(window.document.forms[0].elements[i].value == "")' . "\n" .
			'{' . "\n" .
			'alert("'.TEXT_PLEASE_INSERT_GUEST_LASTNAME.'");' . "\n" .
			'window.document.forms[0].elements[i].focus();' . "\n" .
			'return false;' . "\n" .
			'}' . "\n" .
			'}' . "\n" .

			'}' . "\n" .*/
			'  if (document.checkout_payment.payment.length) {' . "\n" .
			'    for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
			'      if (document.checkout_payment.payment[i].checked) {' . "\n" .
			'        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
			'		   break;' . "\n" .
			'      }' . "\n" .
			'    }' . "\n" .
			'  } else if (document.checkout_payment.payment.checked) {' . "\n" .
			'    payment_value = document.checkout_payment.payment.value;' . "\n" .
			'  } else if (document.checkout_payment.payment.value) {' . "\n" .
			'    payment_value = document.checkout_payment.payment.value;' . "\n" .
			'  }' . "\n\n";

			reset($this->modules);
			while (list(, $value) = each($this->modules)) {
				$class = substr($value, 0, strrpos($value, '.'));
				if ($GLOBALS[$class]->enabled) {
					$js .= $GLOBALS[$class]->javascript_validation();
				}
			}

			$js .= "\n" . '  if (payment_value == null && submitter != 1) {' . "\n" . // ICW CREDIT CLASS Gift Voucher System
			'    error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
			'    error = 1;' . "\n" .
			'  }' . "\n\n" .

			//  ICW CREDIT CLASS Gift Voucher System Line below amended

			//Points/Rewards Module V2.1rc2a BOF
			'  if (error == 1 && submitter != 1 && point_submitter != 1) {' . "\n" .
			//Points/Rewards Module V2.1rc2a EOF
			'    alert(error_message);' . "\n" .
			'    return false;' . "\n" .
			'  } else {' . "\n" .
			'    return true;' . "\n" .
			'  }' . "\n" .
			'}' . "\n" .
			'//--></script>' . "\n";
		}

		return $js;
	}

	/**
	 * 取得当前支付方式列表
	 * @return multitype:unknown 
	 * @author lwkai 2013-5-13 下午4:23:33
	 */
	public function selection($ids = array()) {
		$selection_array = array();

		if (is_array($this->modules)) {
			reset($this->modules);
			while (list(, $value) = each($this->modules)) {
				$class = substr($value, 0, strrpos($value, '.'));
				$is_forbid = true;
				
				if ($GLOBALS[$class]->enabled) {
					// 如果某个产品ID被禁止使用某种支付方式，则当前订单中，所有产品都不能用该支付方式，即该支付方式不出现在当前支付列表中
					foreach((array)$ids as $key=>$val) {
						if (in_array($val, (array)$GLOBALS[$class]->forbid_ids)) {
							$is_forbid = false;
							break;
						}
					}
					if ($is_forbid == true) {
						$selection = $GLOBALS[$class]->selection();
						if (is_array($selection)) $selection_array[] = $selection;
					}
				}
			}
		}

		return $selection_array;
	}
	//ICW CREDIT CLASS Gift Voucher System
	// check credit covers was setup to test whether credit covers is set in other parts of the code
	function check_credit_covers() {
		global $credit_covers;

		return $credit_covers;
	}
	function pre_confirmation_check() {
		global $credit_covers, $payment_modules; //ICW CREDIT CLASS Gift Voucher System
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {

				if ($credit_covers) { //  ICW CREDIT CLASS Gift Voucher System
					$GLOBALS[$this->selected_module]->enabled = false; //ICW CREDIT CLASS Gift Voucher System
					$GLOBALS[$this->selected_module] = NULL; //ICW CREDIT CLASS Gift Voucher System
					$payment_modules = ''; //ICW CREDIT CLASS Gift Voucher System
				} else { //ICW CREDIT CLASS Gift Voucher System
					$GLOBALS[$this->selected_module]->pre_confirmation_check();
				}
			}
		}
	} //ICW CREDIT CLASS Gift Voucher System

	function confirmation() {
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
				return $GLOBALS[$this->selected_module]->confirmation();
			}
		}
	}

	function process_button() {
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
				return $GLOBALS[$this->selected_module]->process_button();
			}
		}
	}

	function before_process() {
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
				return $GLOBALS[$this->selected_module]->before_process();
			}
		}
	}

	function after_process() {
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
				return $GLOBALS[$this->selected_module]->after_process();
			}
		}
	}

	function get_error() {
		if (is_array($this->modules)) {
			if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
				return $GLOBALS[$this->selected_module]->get_error();
			}
		}
	}
}
?>
