<?php 
$email .= "<br>";
$email .= "------------------------------------------------------\n\n";
$email .= "Reservation List \n 订单清单 \n";

$order_product_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS." where  orders_id = ".$oID." ");
while($order_product_result = tep_db_fetch_array($order_product_query))
{

	$displayattributesinmail = str_replace("<br>","\n",$order_product_result['products_room_info']);
	$displayattributesinmail = str_replace('</br>',"\n",$displayattributesinmail);
	
	$displayattributesinmail = str_replace('Total of room','Subtotal',$displayattributesinmail);
	/*$displayattributesinmail = str_replace('#','Number',$displayattributesinmail);*/
	$displayattributesinmail = str_replace('adults','Adults',$displayattributesinmail);
	$displayattributesinmail = str_replace('childs','Children',$displayattributesinmail);
	$displayattributesinmail = str_replace('room','Room',$displayattributesinmail);
	
	$email .=  "\n ".$order_product_result['products_quantity'].' X '.($order_product_result['products_name']).
			" " .$displayattributesinmail;
	$order_guest_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$oID . "' and products_id=".$order_product_result['products_id']."");
	while($order_guest_result = tep_db_fetch_array($order_guest_query))
	{	$i = 1;
		if($order_guest_result['depature_full_address'] == '')
		{
		$depature_email0 = "\n Departure Time & Location: ".str_replace('&nbsp;',' ',$order_product_result['products_departure_date']).' '.str_replace('&nbsp;','',$order_product_result['products_departure_time']).' '.str_replace('&nbsp;','',$order_product_result['products_departure_location']);
		$depature_email = "\n 出发时间 & 地点: ".str_replace('&nbsp;',' ',$order_product_result['products_departure_date']).' '.str_replace('&nbsp;','',$order_product_result['products_departure_time']).' '.str_replace('&nbsp;','',$order_product_result['products_departure_location']);
		$email .= str_replace('&nbsp;','',$depature_email0);
		$email .= str_replace('&nbsp;','',$depature_email);
		
		}
		else
		{
		$email .= "\n Departure Time & Location: ".str_replace('&nbsp;','',$order_guest_result['depature_full_address']);
		$email .= "\n 出发时间 & 地点: ".str_replace('&nbsp;','',$order_guest_result['depature_full_address']);
		}
		$email .= "\n customer name";
		$email .= "\n 客户姓名: ";
		$guest_name = split('<::>',($order_guest_result['guest_name']));
		foreach ($guest_name as $key=>$val)
		{
			if(trim($val) != '')
			$email .= $i++.".) ".trim($val)."  ";
		}
		$email .= "\n";
		
	}
}
$email .= "\n------------------------------------------------------";
$order_total_query = tep_db_query("select * from ".TABLE_ORDERS_TOTAL." where  orders_id = ".$oID." order by sort_order");
while($order_total_result = tep_db_fetch_array($order_total_query))
{

	$email .= "\n" . ($order_total_result['title']).' '.$order_total_result['text'];
}
$email .= "\n------------------------------------------------------";
$email .= "\n E-Ticket Delivery Address";
$email .= "\n 参团凭证邮寄地址:" . $check_status['customers_email_address']. "\n";
$email .= "\n------------------------------------------------------";

$email .= "\n Billing Address";
$email .= "\n 信用卡地址 \n\n".($check_status['billing_name'])." \n ".($check_status['billing_company'])." \n ".
			($check_status['billing_street_address']).' '.($check_status['billing_suburb'])." \n ".
			($check_status['billing_city']).' '.$check_status['billing_postcode']." \n ".
			($check_status['billing_state'])." \n ".($check_status['billing_country'])." \n ";
$email .= "------------------------------------------------------";
$email .= "\n Payment Method "; 
$email .= "\n 支付方式: ". ($check_status['payment_method']); 
?>