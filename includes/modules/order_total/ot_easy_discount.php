<?php
  class ot_easy_discount {
    var $title, $output;

    function ot_easy_discount() {
      $this->code = 'ot_easy_discount';
      $this->title = MODULE_EASY_DISCOUNT_TITLE;
      $this->description = MODULE_EASY_DISCOUNT_DESCRIPTION;
      $this->enabled = ((MODULE_EASY_DISCOUNT_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_EASY_DISCOUNT_SORT_ORDER;
      $this->output = array();
    }
    
    function process() {
      global $order, $currencies, $ot_subtotal, $cart, $easy_discount, $i_pay_total, $is_travel_companion, $payment, $customer_id, $customer_apply_credit_bal, $customer_apply_credit_amt;
	  if($is_travel_companion==true){
			$order_total=$i_pay_total;
		}else{
			$order_total=$order->info['total'];
		}
	  //amit added to apply 2% for specific payment method	
	  if($payment =='transfer'  || $payment =='moneyorder' || $payment =='cashdeposit' ){
	  	$easy_discount->clear('PAYMENT_BY_METHODS');		
		
		//amit added to get total of all hotel products start
		$ttl_all_hotel_prods = 0;$ttl_all_transfer_prods = 0;
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {			
			if($order->products[$i]['is_hotel']==1){
			$ttl_all_hotel_prods = $ttl_all_hotel_prods + $order->products[$i]['final_price'];
			}else if($order->products[$i]['is_transfer']==1){
				$ttl_all_transfer_prods = $ttl_all_transfer_prods + $order->products[$i]['final_price'];
			}
		}		
		//amit added to get total of all hotel products end
		$available_total_discount_priced =  $order_total - $ttl_all_hotel_prods - $ttl_all_transfer_prods;
		if($available_total_discount_priced > 0){
		// calculate discoutn amount by order total 2% fixed
		 $discount_per_set_for_pay_method = 0;//用线下支付方式优惠百分多少
		 $dis_pay_by_method = ($available_total_discount_priced * $discount_per_set_for_pay_method) /100;
		// calculate discount amout by order total
		
		$easy_discount->set('PAYMENT_BY_METHODS',$order->info['payment_method'].'Discount:',$dis_pay_by_method);
		}
	  }else{
	   $easy_discount->clear('PAYMENT_BY_METHODS');
	  }	 
	  //amit added to apply 2% for specific payment method	  
	   // added for credit balance apply on checkout - start
	  if($customer_apply_credit_bal == 1){
		  $customer_credit_bal = $customer_apply_credit_amt;
		  $max_customer_credit_bal = tep_get_customer_credits_balance($customer_id);
		  if($customer_apply_credit_amt > $max_customer_credit_bal){
			  $customer_credit_bal = $max_customer_credit_bal;
		  }
		  if($customer_credit_bal >0){
			  $easy_discount->clear('PAYMENT_BY_CREDITS');		
			  $dis_pay_by_credits = $customer_credit_bal;
			  $order_total = $order_total - $dis_pay_by_method;
			  if($dis_pay_by_credits >= $order_total){
				$dis_pay_by_credits = $order_total;
				$order->info['payment_method'] = NEW_PAYMENT_METHOD_T4F_CREDIT;
			  }else{
			 	$order->info['payment_method'] = NEW_PAYMENT_METHOD_T4F_CREDIT . " + " . $order->info['payment_method'];
			  }
			  $easy_discount->set('PAYMENT_BY_CREDITS',TITLE_CREDIT_APPLIED,$dis_pay_by_credits);
		  }else{
			$easy_discount->clear('PAYMENT_BY_CREDITS');
		  }
	  }
	  // added for credit balance apply on checkout - end
      $od_amount = 0;
      if ($easy_discount->count() > 0) {
        $easy_discounts = $easy_discount->get_all();
        $n = sizeof($easy_discounts);
        for ($i=0;$i < $n; $i++) {
           $this->output[] = array('title' => $easy_discounts[$i]['description'],
                                   'text' => '<font color="red">-' . $currencies->format($easy_discounts[$i]['amount']).'</font>',
                                   'value' => $easy_discounts[$i]['amount']);
           $od_amount = $od_amount + $easy_discounts[$i]['amount'];
        }
        $this->deduction = $od_amount;
		
		$order->info['total'] = $order->info['total'] - $od_amount;
		$i_pay_total=$i_pay_total - $od_amount;

        if ($this->sort_order < $ot_subtotal->sort_order) $order->info['subtotal'] = $order->info['subtotal'] - $od_amount;
      }
    }
  
    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_EASY_DISCOUNT_STATUS'");
        $this->check = mysql_num_rows($check_query);
      }
      return $this->check;
    }

    function keys() {
      return array('MODULE_EASY_DISCOUNT_STATUS', 'MODULE_EASY_DISCOUNT_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values 
    ('Activate Easy Discount', 'MODULE_EASY_DISCOUNT_STATUS', 'true', 'Do you want to enable the Easy discount module?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values 
    ('Sort Order', 'MODULE_EASY_DISCOUNT_SORT_ORDER', '793', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>