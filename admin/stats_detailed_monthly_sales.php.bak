<?php
require ('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_detailed_monthly_sales');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require (DIR_WS_CLASSES . 'currencies.php');

//付款确认start {
if ($HTTP_GET_VARS['action'] == 'proccess_payment_cofirm') {
	
	if (isset($_POST['orderitemid'])) {
		
		$inserte_items_ids = '';
		foreach ($_POST['orderitemid'] as $orderitemid) {
			//first udpate order status paid field
			$sql_data_array_locations = array (
					'payment_paid' => $HTTP_POST_VARS['payment_paid'],
					'final_invoice_amount' => $HTTP_POST_VARS['final_invoice_amount_' . $orderitemid . ''] 
			);
			
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_locations, 'update', " orders_products_id = '" . $orderitemid . "' ");
			//amit added to unset session when paid start
			$get_ord_model_info_array = tep_get_products_name_model_from_orders_products($orderitemid);
			$disply_prod_model_name = $get_ord_model_info_array['products_model'];
			$ordID = $get_ord_model_info_array['orders_id'];
			unset($_SESSION['paid_status' . $disply_prod_model_name . $ordID]);
			//amit added to unset session when paid end					
			

			//make insert on payment history table
			

			if ($inserte_items_ids == '') {
				$inserte_items_ids .= "" . $orderitemid . "";
			} else {
				$inserte_items_ids .= "," . $orderitemid . "";
			}
		}
		
		$payment_comment = tep_db_prepare_input($HTTP_POST_VARS['payment_comment']) . '<br>- ' . tep_get_admin_customer_name($login_id) . '&nbsp;(' . tep_datetime_short(date("Y-m-d h:i:s")) . ')';
		$sql_data_array = array (
				'orders_products_id' => $inserte_items_ids,
				
				'payment_amount' => tep_db_prepare_input($HTTP_POST_VARS['payment_amount']),
				
				'payment_method' => tep_db_prepare_input($HTTP_POST_VARS['payment_method']),
				
				'payment_comment' => $payment_comment,
				
				'updated_by' => $login_id,
				
				'last_update_date' => 'now()',
				
				'payment_date' => 'now()',
				
				'payment_agency_id' => tep_db_input($HTTP_POST_VARS['payment_agency_id']) 
		);
		
		tep_db_perform(TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY, $sql_data_array);
		
		$payment_history_track_id = tep_db_insert_id();
		
		foreach ($_POST['orderitemid'] as $orderitemid) {
			$get_previouse_history_infor_sql = "select final_invoice_amount_history from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '" . $orderitemid . "' ";
			$get_previouse_history_infor_row = tep_db_query($get_previouse_history_infor_sql);
			$get_previouse_history_infor = tep_db_fetch_array($get_previouse_history_infor_row);
			
			if ($get_previouse_history_infor['final_invoice_amount_history'] == '') {
				$final_invoice_amount_history = $payment_history_track_id . '||' . $HTTP_POST_VARS['final_invoice_amount_' . $orderitemid . ''];
			} else {
				$final_invoice_amount_history = $get_previouse_history_infor['final_invoice_amount_history'] . '!!##!!' . $payment_history_track_id . '||' . $HTTP_POST_VARS['final_invoice_amount_' . $orderitemid . ''];
			}
			$sql_data_array_locations_history = array (
					'final_invoice_amount_history' => $final_invoice_amount_history 
			);
			
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_locations_history, 'update', " orders_products_id = '" . $orderitemid . "' ");
		}
		
		$messageStack->add_session('Payment has been updated successfully.', 'success');
		// 111121-1_credit card付款邮件发送功能 start 
		//当选择Payment Comment为Credit Card时自动发邮件到service@usitrip.com                
		if (tep_db_prepare_input($HTTP_POST_VARS['payment_method']) == 'Credit Card') {
			$email_subject = 'Order#' . $inserte_items_ids . ' credit card paid';
			$email_text = $payment_comment;
			tep_mail('', 'service@usitrip.com', $email_subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		}
		// 111121-1_credit card付款邮件发送功能 end
		tep_redirect(tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array (
				'action',
				'oID',
				'products_id' 
		)) . 'action=view_report'));
		//make redirect call and session msg  
	}
}
//付款确认end }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="Javascript1.2">

function chkallfun()
{
    //alert(""+window.document.forms[2].elements.length);
    var i = 0 ;
    var j = 0;
    for ( i= 0 ; i < window.document.orderitemcheck.elements.length ; i ++)
    {
        if(window.document.orderitemcheck.elements[i].type == "checkbox" )
        {
            if (window.document.orderitemcheck.chkall.checked == true)
                window.document.orderitemcheck.elements[i].checked= true ;
            else	
                window.document.orderitemcheck.elements[i].checked= false;
        }
    }    

}
</script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
        <? if ( ! $_REQUEST['print'] ) {
        	require(DIR_WS_INCLUDES . 'header.php');
        	
        	//echo $login_id;
        	include DIR_FS_CLASSES . 'Remark.class.php';
        	$listrs = new Remark('stats_detailed_monthly_sales');
        	$list = $listrs->showRemark();
        	
        }?>
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
<?
if (!$_REQUEST['print']) {
	?>
            <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table
					border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1"
					cellpadding="1" class="columnLeft"><? require(DIR_WS_INCLUDES . 'column_left.php'); ?></table></td>
<?
}
?>
            <td valign="top">
				<div class="pageHeading"><?= HEADING_TITLE ?></div> <br />
                
                
                
                
<?
$force_in = 'true';
//if ( (isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] )) || $force_in == 'true' ) {
//if ( isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] ) ) {


if ($HTTP_GET_VARS['action'] == 'view_report' || $HTTP_GET_VARS['action'] == 'proccess_payment') {
	//浏览报表或打开付款页面
	

	if (!isset($_GET['paid_filter'])) {
		$_GET['paid_filter'] = $HTTP_GET_VARS['paid_filter'] = '0';
	}
	?>


<table border="0" width='100%' cellspacing="0" cellpadding="2">
					<tr>

						<td colspan="9" valign="top">
                                <?php echo tep_draw_form('search', FILENAME_STATS_DETAILED_MONTHLY_SALES, '', 'get'); ?>
							<table cellpadding="2" cellspacing="0" border="0">
								<tr>
									<td class="smallText"><?php echo HEADING_TITLE_PROVIDER; ?></td>
                                        <?php
	echo tep_draw_hidden_field('action', 'view_report');
	// echo tep_draw_hidden_field('month', $_GET['month']);
	

	$provider_array = array (
			array (
					'id' => '',
					'text' => TEXT_NONE 
			) 
	);
	$provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name asc");
	while ($provider_result = tep_db_fetch_array($provider_query)) {
		$provider_array[] = array (
				'id' => $provider_result['agency_id'],
				'text' => $provider_result['agency_name'] 
		);
	}
	?>
                                        <td class="smallText"><?php echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px;" '); //onChange="this.form.submit();" ?></td>
								</tr>
								<tr>
									<td class="smallText">
                                            <?php echo HEADING_TITLE_INVOICED_NUMBER.'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_FROM;?>
									</td>
									<td>		
											<?php echo tep_draw_input_field('invoiced_amt_from', '',' size="11"').'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_TO.'&nbsp;'.tep_draw_input_field('invoiced_amt_to', '',' size="12"'); ?>
                                        </td>
								</tr>
								<tr>
									<td class="smallText">
                                            <?php echo HEADING_TITLE_NET_PRICE.'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_FROM;?>
											</td>
											<td>
											<?php echo tep_draw_input_field('net_amt_from', '',' size="11"').'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_TO.'&nbsp;'.tep_draw_input_field('net_amt_to', '',' size="12"'); ?>
                                        </td>
								</tr>
								<tr>
									<td class="smallText">Departure Date From: 
									</td>
									<td><input
										name="dept_start_date"
										onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"
										value="<?php echo tep_get_date_disp($_GET['dept_start_date']); ?>" />
										To: <input name="dept_end_date"
										onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"
										value="<?php echo tep_get_date_disp($_GET['dept_end_date']); ?>" />
									</td>
								</tr>
								<?php
								/* 查客户姓名没意思 
								<tr>
									<td class="smallText">客户姓名：</td>
									<td class="smallText"><?php echo tep_draw_input_field('search', $HTTP_GET_VARS['search'], '80'); ?></td>
								</tr>
								*/?>

								<tr>
									<td class="smallText"><?php echo HEADING_TITLE_FILTER_BY; ?></td>
                                        <?php
	
	$paid_status_array = array (
			array (
					'id' => '',
					'text' => 'All' 
			) 
	);
	$paid_status_array[] = array (
			'id' => '0',
			'text' => 'Unpaid' 
	);
	$paid_status_array[] = array (
			'id' => '1',
			'text' => 'Paid' 
	);
	$paid_status_array[] = array (
			'id' => '2',
			'text' => 'Final Payment Pending' 
	);
	$paid_status_array[] = array (
			'id' => '3',
			'text' => 'Partially Paid' 
	);
	//$paid_status_array[] = array('id' => '2', 'text' =>'Cancelled');
	

	?>
                                        <td class="smallText"><?php echo tep_draw_pull_down_menu('paid_filter', $paid_status_array, $_GET['paid_filter'], 'style="width:200px;"'); ?></td>
								</tr>
								
								<tr>
								<td>团号：</td>
								<td>
								<?php echo tep_draw_input_num_en_field('products_model');?>
								</td>
								</tr>
								<tr>
								<td>供应商团号：</td>
								<td>
								<?php echo tep_draw_input_num_en_field('provider_tour_code');?>
								</td>
								</tr>
								<tr>
								<td>订单号：</td>
								<td>
								<?php echo tep_draw_input_num_en_field('orders_id');?>
								</td>
								</tr>
								<tr>
								<td>购买时间：</td>
								<td>
								<?php echo tep_draw_input_num_en_field('date_purchased_start','','onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?> To: <?php echo tep_draw_input_num_en_field('date_purchased_end','','onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
								</td>
								</tr>
								
								<tr>
									<td>&nbsp;</td>
									<td>
                                            <?php
	
	echo tep_image_submit('button_search.gif', IMAGE_SEARCH) . '&nbsp;';
	
	if ($HTTP_GET_VARS['action'] == 'proccess_payment') {
		//echo '<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('selected_box','action')).'action=view_report', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
	} else {
		echo '<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		echo '<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES) . '">清除搜索</a>';
	}
	
	?>
                                            
                                            
                                        </td>
								</tr>
							</table>
                                <?php
	echo '</form>';
	?>
                                
                                
                            </td>
					</tr>
                        <?php
	if ($HTTP_GET_VARS['action'] == 'proccess_payment') {
		?>
                        <tr>
						<td>
                        <?php echo tep_draw_form('search', FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('selected_box','action')).'action=view_report', 'post'); ?>
                        <?php
		foreach ($_POST['orderitemid'] as $orderitemid_back) {
			echo '<input name="post_back_checked_' . $orderitemid_back . '" type="hidden" value="' . $orderitemid_back . '" >';
		}
		echo tep_image_submit('button_back.gif', IMAGE_BACK);
		echo '</form>';
		?>
                        </td>
					</tr>
                        <?php } ?>
                        <tr>
						<td><hr color="#990000" size="1"></td>
					</tr>
				</table>


                <?php if($HTTP_GET_VARS['action'] == 'proccess_payment') { ?>
                
                <?php echo tep_draw_form('payment_con', FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('selected_box','action')).'action=proccess_payment_cofirm', 'post','onSubmit="return confirm(\'Are you sure you want to make payment for above orders items ?\');"'); ?>
                
                <table border="0" width='100%' cellspacing="0"
					cellpadding="2">
                        
                    <?php  if(isset($_POST['orderitemid'])) { ?>
                        <?php
			
			$sum_tot_invoice_amt = 0;
			$sum_tot_final_price_cost = 0;
			$show_error_msg_str = '';
			$jsvascript_daynamic_fields = '';
			foreach ($_POST['orderitemid'] as $orderitemid) {
				
				$prods_query = mysql_query("select p.agency_id, o.orders_id, o.date_purchased, op.orders_products_id, op.payment_paid, op.products_id, op.products_model, op.products_name,  op.products_id, op.final_price, op.products_quantity, op.final_price_cost,op.products_departure_date, op.orders_products_id from orders_products as op, orders as o, products as p where  op.orders_products_id = '" . $orderitemid . "' and op.orders_id = o.orders_id and op.products_id=p.products_id");
				if ($prods = mysql_fetch_array($prods_query)) {
					$payment_agency_id = $prods['agency_id'];
					$sum_tot_final_price_cost += $prods['final_price_cost'];
					$select_check_order_product_history_sql = "select oph.ord_prod_history_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment, op.products_id from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph, " . TABLE_ORDERS_PRODUCTS . " op where oph.orders_products_id=op.orders_products_id and oph.orders_products_id='" . $prods['orders_products_id'] . "'  order by oph.ord_prod_history_id desc limit 1";
					$select_check_order_product_history_row = tep_db_query($select_check_order_product_history_sql);
					$select_check_order_product_history = tep_db_fetch_array($select_check_order_product_history_row);
					
					$sum_tot_invoice_amt += $select_check_order_product_history['invoice_amount'];
					
					if ($prods['final_price_cost'] != $select_check_order_product_history['invoice_amount']) {
						
						echo '<tr><td colspan="2" class="errorText">Net and Invoice Amount does not match for order #' . $prods['orders_id'] . '</td></tr>';
					}
					
					?>
                                    <tr>
						<td colspan="2" class="main"><strong>Order #</strong><?php echo '<a href="edit_orders.php?action=edit&oID='.$prods['orders_id'].'" target="_blank"><strong>'.$prods['orders_id'].'</strong></a> '; ?></td>
					</tr>
					<tr>
						<td colspan="2" class="main"><strong>Tour:</strong>
                                    <?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $prods['products_id']) . '">'.$prods['products_name'].'</a>&nbsp;[<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($prods['products_id']) . '&pID=' . $prods['products_id'].'&action=new_product') . '">'.$prods['products_model'].'</a>]';?>
                                    </td>
					</tr>
                                    <?php
					if ($prods['payment_paid'] == '1') {
						$select_check_order_product_payment_history_sql = "select ord_prod_payment_id, op.orders_products_id from " . TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY . " opph, " . TABLE_ORDERS_PRODUCTS . " op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP '," . $prods['orders_products_id'] . ",')  order by opph.ord_prod_payment_id desc";
						$select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
						echo '<tr><td colspan="2" class="errorText">Payment for this tour already Paid. ';
						/*
						 *
						 * if(tep_db_num_rows($select_check_order_product_payment_history_row)>0){
						 * $select_check_order_product_payment_history =
						 * tep_db_fetch_array($select_check_order_product_payment_history_row);
						 * echo '<a target="_blank" href="' .
						 * tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY,'payment_id='.$select_check_order_product_payment_history['ord_prod_payment_id'].'&item_id='.$select_check_order_product_payment_history['orders_products_id'],
						 * 'NONSSL') . '"><strong>Click here to see
						 * detail</strong></a>'; }
						 */
						?>
                                        <?php
						$view_count = tep_db_num_rows($select_check_order_product_payment_history_row);
						if ($view_count > 0) {
							$select_check_order_product_payment_history = tep_db_fetch_array($select_check_order_product_payment_history_row);
							
							echo '<a  target="_blank" href="' . tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, 'payment_id=' . $select_check_order_product_payment_history['ord_prod_payment_id'] . '&item_id=' . $prods['orders_products_id'], 'NONSSL') . '"><strong>View';
							if ($view_count > 1) {
								echo $view_count;
							}
							echo '</strong></a>';
						} else {
							echo '&nbsp;';
						}
						?>										
                                        <?php
						echo '</td></tr>';
					}
					?>
                                    
                                    <tr>
						<td colspan="2" class="main"><strong>Tour Invoice Amount: </strong><input
							type="text" name="final_invoice_amount_<?=$orderitemid;?>"
							value="<?php echo $select_check_order_product_history['invoice_amount'];?>"
							onFocus="startCalc();" onBlur="stopCalc();"></td>
					</tr>
					<tr>
						<td colspan="2" class="main">&nbsp;</td>
					</tr>
                                    
                                    
                                    <?php
					
					if ($jsvascript_daynamic_fields == '') {
						$jsvascript_daynamic_fields = "final_invoice_amount_" . $orderitemid . "";
					} else {
						$jsvascript_daynamic_fields .= "###final_invoice_amount_" . $orderitemid . "";
					}
					
					echo '<input name="orderitemid[]" type="hidden" value="' . $orderitemid . '" >';
				}
			}
			
			?>
                                    
                                    
                                    <tr>
						<td colspan="2" class="main"><strong>Total Invoice Amount: <?php echo number_format($sum_tot_invoice_amt, 2); ?></strong></td>
					</tr>

					<tr>
						<td colspan="2" class="main"><strong>Totel Net Amount: <?php echo number_format($sum_tot_final_price_cost, 2); ?></strong></td>
					</tr>

					<tr>
						<td class="main" width="15%">Payment Status</td>
						<td><select name="payment_paid">
								<option value="2">Final Payment Pending</option>
								<option value="1">Paid</option>
								<option value="0">Unpaid</option>
								<option value="3">Partially Paid</option>
						</select></td>
					</tr>
					<tr>
						<td class="main" width="20%"><?php echo HEADING_TITLE_PROVIDER; ?></td>
						<td><?php echo tep_draw_pull_down_menu('payment_agency_id', $provider_array, $payment_agency_id, 'style="width:200px;" '); //onChange="this.form.submit();" ?></td>
					</tr>
					<tr>
						<td class="main">Payment Method</td>
						<td><select name="payment_method">
								<option value="Check" selected>Check</option>
								<option value="ACH Payment">ACH Payment</option>
								<option value="Cash">Cash</option>
								<option value="Credit Card">Credit Card</option>
								<option value="Customer paid directly to provider">Customer paid
									directly to provider</option>
								<option value="Wire Transfer">Wire Transfer</option>
								<option value="Paypal">Paypal</option>
								<option value="Money Order">Money Order</option>
								<option value="Other">Other</option>
						</select></td>
					</tr>

					<tr>
						<td class="main">Payment Comment</td>
						<td><textarea name="payment_comment" cols="50" rows="5"></textarea></td>
					</tr>

					<tr>
						<td class="main">Payment Amount</td>
						<td><input type="text" name="payment_amount"
							value="<?php echo $sum_tot_invoice_amt; ?>"></td>
					</tr>
					<tr>
						<td colspan="2"><hr>&nbsp;</td>
					</tr>


					<tr>
						<td></td>
						<td><?php echo  tep_image_submit('button_pay_all.gif', 'Pay All').'&nbsp;<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('selected_box','action')).'action=view_report', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<script language="javascript">
                        function startCalc(){
                          interval = setInterval("calc()",1);
                        }
                        function calc(){
                        
                        <?php
			$daynamic_name_fields_array = explode('###', $jsvascript_daynamic_fields);
			$totalassing_sum = '';
			for ($fi = 0; $fi < sizeof($daynamic_name_fields_array); $fi ++) {
				
				echo "fileds_" . $fi . " = document.payment_con." . $daynamic_name_fields_array[$fi] . ".value; \n";
				
				if ($totalassing_sum == '') {
					$totalassing_sum = " (fileds_" . $fi . " * 1) ";
				} else {
					$totalassing_sum .= "+ (fileds_" . $fi . " * 1) ";
				}
			}
			
			?>
                        
                                                  
                          document.payment_con.payment_amount.value = <?php echo $totalassing_sum;?>;
                        }
                        function stopCalc(){
                          clearInterval(interval);
                        }
                        </script>						
                        <?php }	else{ ?>
                        <tr>
						<td class="main">Opps!! you didn't select any order for payment.</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo '<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('selected_box','action')).'action=view_report', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
                        <?php }?>
              </table>

				</form>
                
                <?php
	} else {
		
		?>
                
                        
                
                        <table border="0" width='100%' cellspacing="0" cellpadding="2">
                        <?php echo tep_draw_form('orderitemcheck', FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('selected_box','action')).'action=proccess_payment', 'post'); ?>
                        <tr>
						<td class="dataTableRowSelectedPink main" colspan="17"><?php echo PINK_ROW_EXPLAINATION_TEXT; ?></td>
					</tr>
					<tr>
						<td class="dataTableRowSelectedYellow main" colspan="17"><?php echo YELLOW_ROW_EXPLAINATION_TEXT; ?></td>
					</tr>
					<tr>
						<td class="dataTableContent" colspan="9">&nbsp;</td>
					</tr>
					<tr class="dataTableHeadingRow">

						<td class="dataTableHeadingContent" width="5%">
                                Order Date <?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=orderdate&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=orderdate&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?>  
                            </td>
						<td class="dataTableHeadingContent">Tours</td>
						<td class="dataTableHeadingContent">团号</td><td class="dataTableHeadingContent">供应商团号</td><td class="dataTableHeadingContent">Provider</td>
						<td class="dataTableHeadingContent">
                             <?php //echo 'Invoiced#<br><a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=invoice&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=invoice&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?>  
                            <?php
		$HEADING_INVOICE_SORT = 'Invoiced#';
		/* 取消按发票的排序方式，没啥用还浪费服务器资源 by howard 2013-06-04
		$HEADING_INVOICE_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array (
				'page',
				'sort',
				'x',
				'y',
				'order' 
		)) . 'sort=invoice&order=ascending">';
		$HEADING_INVOICE_SORT .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
		$HEADING_INVOICE_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array (
				'page',
				'sort',
				'x',
				'y',
				'order' 
		)) . 'sort=invoice&order=decending">';
		$HEADING_INVOICE_SORT .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
		*/
		echo $HEADING_INVOICE_SORT;
		?>	
                            </td>
						<td class="dataTableHeadingContent">Invoiced Amount</td>
						<td class="dataTableHeadingContent"><input type="checkbox"
							class="buttonstyle" name="chkall" onClick="chkallfun()"></td>
						<td class="dataTableHeadingContent">
                            Depature Date <?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=deptdate&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=deptdate&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?> 
                            </td>
						<td class="dataTableHeadingContent">
                            Order# <?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=orderno&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, tep_get_all_get_params(array('sort','order')).'sort=orderno&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?> 
                            </td>
						<td class="dataTableHeadingContent">Orders<br>Status
						</td>
						<td class="dataTableHeadingContent">Sales</td>
						<td class="dataTableHeadingContent">Net</td>
						<td class="dataTableHeadingContent">Gross Profit</td>
						<td class="dataTableHeadingContent">Gross Profit<br>(%)
						</td>
						<td class="dataTableHeadingContent">Paid</td>
						<!--
                            <td class="dataTableHeadingContent">Payment Method</td>
                            <td class="dataTableHeadingContent">Payment Amount</td>
                            <td class="dataTableHeadingContent">Updated by</td>
                            <td class="dataTableHeadingContent">Last Modify</td>
                            -->
						<td class="dataTableHeadingContent">Payment History</td>
					</tr>

                <?
		///////  sort coding /////////
		if ($_GET["sort"] == 'orderdate') {
			if ($_GET["order"] == 'ascending') {
				$sortorder .= 'o.date_purchased  asc ';
			} else {
				$sortorder .= 'o.date_purchased desc ';
			}
		} elseif ($_GET["sort"] == 'invoice') {
			if ($_GET["order"] == 'ascending') {
				$sortorder = ' oph.invoice_number asc';
			} else {
				$sortorder = ' oph.invoice_number desc';
			}
		} elseif ($_GET["sort"] == 'deptdate') {
			if ($_GET["order"] == 'ascending') {
				$sortorder .= 'op.products_departure_date asc ';
			} else {
				$sortorder .= 'op.products_departure_date desc ';
			}
		} elseif ($_GET["sort"] == 'orderno') {
			if ($_GET["order"] == 'ascending') {
				$sortorder .= 'o.orders_id asc ';
			} else {
				$sortorder .= 'o.orders_id desc ';
			}
		} else {
			$sortorder .= 'op.products_departure_date DESC, op.orders_products_id';
		}
		/////   start - search coding    ///////
		$where = ' 1=1 ';
		$where.= ' and op.admin_confirm="Y" ';	//必须是与供应商确认过的才列出（即财务在应付账款报表中点了“确认”按钮的）
		if(tep_not_null($_GET['orders_id'])){
			$where .= " and o.orders_id='" . (int)$_GET['orders_id'] . "' ";
		}
		if (isset($_GET['paid_filter']) && ($_GET['paid_filter'] == '0' || $_GET['paid_filter'] == '1')) {
			$where .= " and op.payment_paid='" . $_GET['paid_filter'] . "'";
		}
		
		/*
		 * if($_GET['paid_filter'] == '2'){ $where .= " and o.orders_status='6'
		 * "; }
		 */
		if ($_GET['paid_filter'] == '2' || $_GET['paid_filter'] == '3') {
			$where .= " and op.payment_paid='" . $_GET['paid_filter'] . "' ";
		}
		if (isset($_GET['provider']) && $_GET['provider'] != '') {
			$where .= " and p.agency_id='" . $_GET['provider'] . "'";
		}
		if ((isset($HTTP_GET_VARS['dept_start_date']) && tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (isset($HTTP_GET_VARS['dept_end_date']) && tep_not_null($HTTP_GET_VARS['dept_end_date']))) {
			//$make_start_date = $HTTP_GET_VARS['dept_start_date'] ;
			$make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']);
			//$make_end_date = $HTTP_GET_VARS['dept_end_date'] ;
			$make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']);
			$where .= " and  op.products_departure_date >= '" . $make_start_date . "' and op.products_departure_date <= '" . $make_end_date . "' ";
			
			$search_extra_get .= "&dept_start_date=" . $_GET['dept_start_date'] . "&dept_end_date=" . $_GET['dept_end_date'];
		} elseif ((isset($HTTP_GET_VARS['dept_start_date']) && tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (!isset($HTTP_GET_VARS['dept_end_date']) or !tep_not_null($HTTP_GET_VARS['dept_end_date']))) {
			
			$make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']) . " 00:00:00";
			
			$where .= " and  op.products_departure_date >= '" . $make_start_date . "' ";
			
			$search_extra_get .= "&dept_start_date=" . $_GET['dept_start_date'];
		} elseif ((!isset($HTTP_GET_VARS['dept_start_date']) or !tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (isset($HTTP_GET_VARS['dept_end_date']) && tep_not_null($HTTP_GET_VARS['dept_end_date']))) {
			
			$make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']) . " 00:00:00";
			
			$where .= " and  op.products_departure_date <= '" . $make_end_date . "' ";
			$search_extra_get .= "&dept_end_date=" . $_GET['dept_end_date'];
		} /*
		   * elseif ( isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] ) )
		   * { $where .= " and year( op.products_departure_date ) = " .
		   * $_REQUEST['year'] . " AND monthname( op.products_departure_date ) =
		   * '" . $_REQUEST['month'] . "'"; }
		   */
		
		//for net/saleas amount
		if ((isset($HTTP_GET_VARS['net_amt_from']) && tep_not_null($HTTP_GET_VARS['net_amt_from'])) && (isset($HTTP_GET_VARS['net_amt_to']) && tep_not_null($HTTP_GET_VARS['net_amt_to']))) {
			//$make_start_date = $HTTP_GET_VARS['dept_start_date'] ;
			$net_amt_from = $HTTP_GET_VARS['net_amt_from'];
			//$make_end_date = $HTTP_GET_VARS['dept_end_date'] ;
			$net_amt_to = $HTTP_GET_VARS['net_amt_to'];
			$where .= " and ((op.final_price >= " . $net_amt_from . " and op.final_price <= " . $net_amt_to . ") or (op.final_price_cost >= " . $net_amt_from . " and op.final_price_cost <= " . $net_amt_to . ")) ";
		} elseif ((isset($HTTP_GET_VARS['net_amt_from']) && tep_not_null($HTTP_GET_VARS['net_amt_from'])) && (!isset($HTTP_GET_VARS['net_amt_to']) or !tep_not_null($HTTP_GET_VARS['net_amt_to']))) {
			
			$net_amt_from = $HTTP_GET_VARS['net_amt_from'];
			//$where .= " and (op.final_price >= " . $net_amt_from . " or op.final_price_cost >= " . $net_amt_from . ")";
			$where .= " and (op.final_price = " . $net_amt_from . " or op.final_price_cost = " . $net_amt_from . ")";
		} elseif ((!isset($HTTP_GET_VARS['net_amt_from']) or !tep_not_null($HTTP_GET_VARS['net_amt_from'])) && (isset($HTTP_GET_VARS['net_amt_to']) && tep_not_null($HTTP_GET_VARS['net_amt_to']))) {
			
			$net_amt_to = $HTTP_GET_VARS['net_amt_to'];
			$where .= " and (op.final_price <= " . $net_amt_to . " or op.final_price_cost <= " . $net_amt_to . ") ";
		}
		
		//for invoice amount
		if ((isset($HTTP_GET_VARS['invoiced_amt_from']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) or (isset($HTTP_GET_VARS['invoiced_amt_to']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_to']))) {
			if ((isset($HTTP_GET_VARS['invoiced_amt_from']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) && (isset($HTTP_GET_VARS['invoiced_amt_to']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_to']))) {
				$invoiced_amt_from = $HTTP_GET_VARS['invoiced_amt_from'];
				$invoiced_amt_to = $HTTP_GET_VARS['invoiced_amt_to'];
				$invoice_where = " and opuh.invoice_number >= " . $invoiced_amt_from . " and opuh.invoice_number <= " . $invoiced_amt_to . "";
				//$invoice_where = " and opuh.invoice_number  between '".$invoiced_amt_from."' and '".$invoiced_amt_to."'";
			} elseif ((isset($HTTP_GET_VARS['invoiced_amt_from']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) && (!isset($HTTP_GET_VARS['invoiced_amt_to']) or !tep_not_null($HTTP_GET_VARS['invoiced_amt_to']))) {
				$invoiced_amt_from = $HTTP_GET_VARS['invoiced_amt_from'];
				$invoice_where = " and opuh.invoice_number  = '" . $invoiced_amt_from . "'";
			} elseif ((!isset($HTTP_GET_VARS['invoiced_amt_from']) or !tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) && (isset($HTTP_GET_VARS['invoiced_amt_to']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_to']))) {
				
				$invoiced_amt_to = $HTTP_GET_VARS['invoiced_amt_to'];
				$invoice_where = " and opuh.invoice_number  = '" . $invoiced_amt_to . "'";
			}
			$products_id_query = "select opuh.orders_products_id, ord_prod_history_id from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " opuh where 1=1 " . $invoice_where . " group by orders_products_id order by ord_prod_history_id  desc";
			
			//echo $products_id_query = "SELECT  opuh.orders_products_id, max(opuh.ord_prod_history_id)  FROM ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." as opuh  group by opuh.orders_products_id  having max(opuh.ord_prod_history_id) <= ( select opuh.ord_prod_history_id  from  ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." as opuh where 1=1 ".$invoice_where." )"; 
		  // echo $products_id_query = "SELECT  orders_products_id, max(ord_prod_history_id)  FROM orders_product_update_history group by `orders_products_id`    having max(ord_prod_history_id) <= ( select ord_prod_history_id  from  orders_product_update_history where `invoice_number` = '1200' ) ";
			$res_products_id = tep_db_query($products_id_query);
			$in_products_id = '0,';
			
			while ($row_products_id = tep_db_fetch_array($res_products_id)) {
				// echo $row_products_id['ord_prod_history_id'].'-->'.$row_products_id['orders_products_id'].'</br>';
				$select_final_check_latest_sql = "select opuh.orders_products_id, ord_prod_history_id from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " opuh where orders_products_id='" . $row_products_id['orders_products_id'] . "' order by ord_prod_history_id  desc limit 1 ";
				$select_final_check_latest_query = tep_db_query($select_final_check_latest_sql);
				$select_final_check_latest_row = tep_db_fetch_array($select_final_check_latest_query);
				
				if ($row_products_id['ord_prod_history_id'] <= $select_final_check_latest_row['ord_prod_history_id']) {
					$in_products_id .= $row_products_id['orders_products_id'] . ",";
				}
			}
			
			$in_products_id = substr($in_products_id, 0, -1);
			
			$where .= " and op.orders_products_id in(" . $in_products_id . ") ";
			//$where .= " and op.orders_products_id in(3083,3086) ";
		}
		
		if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
			$keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
			$where .= " and (o.customers_name like '%" . $keywords . "%' ) ";
		}
		//搜索供应商团号
		if(tep_not_null($_GET['provider_tour_code'])){
			$provider_tour_code = tep_db_input(tep_db_prepare_input($_GET['provider_tour_code']));
			$where .= " and p.provider_tour_code Like '".$provider_tour_code."%'";
		}
		//搜索产品团号
		if(tep_not_null($_GET['products_model'])){
			$products_model = tep_db_input(tep_db_prepare_input($_GET['products_model']));
			$where .= " and p.products_model Like '".$products_model."%'";
		}
		//购买时间
		if(tep_not_null($_GET['date_purchased_start'])){
			$date_purchased_start = date('Y-m-d', strtotime($_GET['date_purchased_start']));
			$where .= " and o.date_purchased >='".$date_purchased_start." 00:00:00'";
		}
		if(tep_not_null($_GET['date_purchased_end'])){
			$date_purchased_end = date('Y-m-d', strtotime($_GET['date_purchased_end']));
			$where .= " and o.date_purchased <='".$date_purchased_end." 23:59:59'";
		}
		
		/////   end - search coding    ///////
		

		$running_final_price = 0;
		$running_final_price_cost = 0;
		$running_gross_profit = 0;
		$running_margin = 0;
		$items_sold_cnt = 0;
		
		$prods_query_sql = "select p.agency_id, p.provider_tour_code, o.orders_id, o.orders_status, o.date_purchased, op.orders_products_id, op.payment_paid, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.final_price_cost,op.products_departure_date, op.orders_products_id from orders_products as op, orders as o, products as p where  " . $where . " and op.orders_id = o.orders_id and op.products_id=p.products_id group by op.orders_products_id order by " . $sortorder . "";
		/*if ($_GET["sort"] == 'invoice') {	//按发票排序时用这段sql
			$prods_query_sql = "select p.agency_id, p.provider_tour_code, o.orders_id, o.orders_status, o.date_purchased, op.orders_products_id, op.payment_paid, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.final_price_cost,op.products_departure_date, op.orders_products_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment  from  orders as o, products as p, " . TABLE_ORDERS_PRODUCTS . " as op," . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph LEFT JOIN " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph2 ON oph.orders_products_id = oph2.orders_products_id
AND oph.ord_prod_history_id < oph2.ord_prod_history_id and oph.last_updated_date < oph2.last_updated_date where " . $where . " and oph2.last_updated_date IS NULL and oph.orders_products_id = op.orders_products_id and op.orders_id = o.orders_id and  op.products_id=p.products_id order by " . $sortorder . " ";
		}*/
		
		//echo $prods_query_sql; exit;
		
		$prods_query = tep_db_query($prods_query_sql);
		$total_invoice_amount = 0;
		while ($prods = tep_db_fetch_array($prods_query)) {
			$running_final_price += $prods['final_price'];
			$running_final_price_cost += $prods['final_price_cost'];
			$gross_profit = number_format($prods['final_price'] - $prods['final_price_cost'], 2);
			$running_gross_profit += $gross_profit;
			if ($prods['final_price'] != 0) {
				$margin = tep_round((((($prods['final_price']) - ($prods['final_price_cost'])) / ($prods['final_price'])) * 100), 0);
			} else {
				$margin = 0;
			}
			$running_margin += $margin;
			
			$select_check_order_product_history_sql = "select oph.ord_prod_history_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment, op.products_id from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph, " . TABLE_ORDERS_PRODUCTS . " op where oph.orders_products_id=op.orders_products_id  and oph.orders_products_id='" . $prods['orders_products_id'] . "'  order by oph.ord_prod_history_id desc limit 1";
			$select_check_order_product_history_row = tep_db_query($select_check_order_product_history_sql);
			$select_check_order_product_history = tep_db_fetch_array($select_check_order_product_history_row);
			
			// $select_check_order_product_payment_history_sql = "select opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_ORDERS_PRODUCTS." op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP ',".$prods['orders_products_id'].",')  order by opph.ord_prod_payment_id desc limit 1";
			$select_check_order_product_payment_history_sql = "select ord_prod_payment_id, op.orders_products_id from " . TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY . " opph, " . TABLE_ORDERS_PRODUCTS . " op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP '," . $prods['orders_products_id'] . ",') order by opph.ord_prod_payment_id desc";
			$select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
			
			if ($items_sold_cnt == 0 || $previous_dept_date == tep_date_short($prods['products_departure_date'])) {
				$total_invoice_amount = $total_invoice_amount + $select_check_order_product_history['invoice_amount'];
			} else {
				?>
                   <tr bgcolor="#D6E0ED" height="25">
						<td colspan="6" align="right" class="dataTableContent">Total Invoice Amount For <?php echo $previous_dept_date; ?>: </td>
						<td class="dataTableContent"><b><?php echo '$'.number_format($total_invoice_amount,2); ?></b></td>
						<td colspan="10"></td>
					</tr>
                                        <?php
				$total_invoice_amount = 0;
				$total_invoice_amount = $total_invoice_amount + $select_check_order_product_history['invoice_amount'];
			}
			$previous_dept_date = tep_date_short($prods['products_departure_date']);
			?>
      
                                    <tr
						<?php
			
			if (($select_check_order_product_history['invoice_amount'] != $prods['final_price_cost'])) {
				echo 'class="dataTableRowSelectedPink"';
			} elseif ($gross_profit <= 0) {
				echo 'class="dataTableRowSelectedYellow"';
			} else {
				echo 'class="dataTableRow"';
			}
			?>>

						<td class="dataTableContent"><?= tep_date_short($prods['date_purchased']); ?></td>
						<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .$prods['products_id']) . '">' . $prods['products_name'].'</a>'; //&tabedit=eticket ?></td>
						<td class="dataTableContent">
						<?php
						$_products_href = tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($prods['products_id']) . '&pID=' . $prods['products_id'].'&action=new_product');
						echo '<a target="_blank" href="' . $_products_href . '">' . $prods['products_model'] . '</a>';?></td>
						<td class="dataTableContent"><?php echo '<a target="_blank" href="' . $_products_href . '">' . $prods['provider_tour_code'] . '</a>';?></td>
						<td class="dataTableContent"><?= tep_get_travel_agency_name($prods['agency_id']); ?></td>
						<td class="dataTableContent"><?= $select_check_order_product_history['invoice_number']; ?></td>
						<td class="dataTableContent">
                                    <?php
			/*
			 * if($select_check_order_product_history['invoice_amount'] !=
			 * '0.00' && $select_check_order_product_history['invoice_amount']
			 * != ''){ echo
			 * '$'.$select_check_order_product_history['invoice_amount']; }
			 */
			if ($select_check_order_product_history['invoice_amount'] != '') {
				echo '$' . $select_check_order_product_history['invoice_amount'];
			}
			?>
                                    </td>
						<td class="dataTableContent"><input class="buttonstyle"
							name="orderitemid[]" type="checkbox"
							value="<?php echo $prods['orders_products_id'];?>"
							<?php if($_POST['post_back_checked_'.$prods['orders_products_id']] == $prods['orders_products_id']){ echo  'checked="checked"'; } ?>></td>
						<td class="dataTableContent"><?= tep_date_short($prods['products_departure_date']); ?></td>
						<td class="dataTableContent"><a
							href="edit_orders.php?action=edit&oID=<?= $prods['orders_id'] ?>"
							target="_blank"><strong><?= $prods['orders_id'] ?></strong></a></td>
						<td class="dataTableContent"><?= tep_get_orders_status_name($prods['orders_status']); ?></td>
						<td class="dataTableContent">$<?= number_format( $prods['final_price'], 2 ); ?></td>
						<td class="dataTableContent">$<?= number_format( $prods['final_price_cost'], 2 ); ?></td>
						<td class="dataTableContent"
							<?php if($gross_profit<=0){ echo ' style="background-color:#FFFFCC;"'; } ?>>$<?= $gross_profit;?></td>
						<td class="dataTableContent"><?php echo $margin . '%';?></td>
						<td class="dataTableContent"><?php if($prods['payment_paid'] == '1'){ echo 'Paid';} elseif($prods['payment_paid'] == '2'){ echo 'Final Payment Pending';} elseif($prods['payment_paid'] == '3'){ echo 'Partially Paid';}else {echo 'Unpaid';};?></td>
                                    <?php
			/*
                                    <td class="dataTableContent"><?php echo $select_check_order_product_payment_history['payment_method'] .' '.$select_check_order_product_payment_history['payment_comment']; ?></td>
                                    <td class="dataTableContent"><?php echo $select_check_order_product_payment_history['payment_amount'];?></td>
                                    <td class="dataTableContent"><?php echo tep_get_admin_customer_name($select_check_order_product_payment_history['updated_by']);?></td>
                                    <td class="dataTableContent"><?php/*
			       * <td class="dataTableContent"><?php echo
			       * $select_check_order_product_payment_history['payment_method']
			       * .'
			       * '.$select_check_order_product_payment_history['payment_comment'];
			       * ?></td> <td class="dataTableContent"><?php echo
			       * $select_check_order_product_payment_history['payment_amount'];?></td>
			       * <td class="dataTableContent"><?php echo
			       * tep_get_admin_customer_name($select_check_order_product_payment_history['updated_by']);?></td>
			       * <td class="dataTableContent"><?php/*
			 * <td class="dataTableContent"><?php echo
			 * $select_check_order_product_payment_history['payment_method'] .'
			 * '.$select_check_order_product_payment_history['payment_comment'];
			 * ?></td> <td class="dataTableContent"><?php echo
			 * $select_check_order_product_payment_history['payment_amount'];?></td>
			 * <td class="dataTableContent"><?php echo
			 * tep_get_admin_customer_name($select_check_order_product_payment_history['updated_by']);?></td>
			 * <td class="dataTableContent"><?php/* <td
			 * class="dataTableContent"><?php echo
			 * $select_check_order_product_payment_history['payment_method'] .'
			 * '.$select_check_order_product_payment_history['payment_comment'];
			 * ?></td> <td class="dataTableContent"><?php echo
			 * $select_check_order_product_payment_history['payment_amount'];?></td>
			 * <td class="dataTableContent"><?php echo
			 * tep_get_admin_customer_name($select_check_order_product_payment_history['updated_by']);?></td>
			 * <td class="dataTableContent"><?php echo
			 * tep_datetime_short($select_check_order_product_payment_history['last_update_date']);?></td>
			 */








			
			?>
                                    <td class="dataTableContent"
							align="center">
                                    <?php
			$view_count = tep_db_num_rows($select_check_order_product_payment_history_row);
			if ($view_count > 0) {
				$select_check_order_product_payment_history = tep_db_fetch_array($select_check_order_product_payment_history_row);
				
				echo '<a  target="_blank" href="' . tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, 'payment_id=' . $select_check_order_product_payment_history['ord_prod_payment_id'] . '&item_id=' . $prods['orders_products_id'], 'NONSSL') . '"><strong>View';
				if ($view_count > 1) {
					echo $view_count;
				}
				echo '</strong></a>';
			} else {
				echo '&nbsp;';
			}
			?></td>
					</tr>						
      
                          <?php
			$str_order_id = $str_order_id . ', ' . $prods['orders_id'];
			$items_sold_cnt ++;
		}
		
		if ($items_sold_cnt > 0) {
			?>
                        <tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" colspan="6"></td>
						<td class="dataTableHeadingContent" colspan="5"><input
							type="submit" name="submit" value="Continue with Payment"></td>
						<td class="dataTableHeadingContent">$<?= number_format( $running_final_price, 2 ) ?></td>
						<td class="dataTableHeadingContent">$<?= number_format( $running_final_price_cost, 2 ) ?></td>
						<td class="dataTableHeadingContent">$<?= number_format( $running_gross_profit, 2 ) ?></td>
						<td class="dataTableHeadingContent"><?= tep_round(($running_margin/$items_sold_cnt) , 0). '%'; ?></td>
						<td class="dataTableHeadingContent">&nbsp;</td>
						<td class="dataTableHeadingContent">&nbsp;</td>
					</tr>
                        <?php } ?>
                        </table>
				</form>
    <?php
	} //end of check paymetn proccess if
	?>
                        
<?
} else {
	
	?>
<table border="0" width='100%' cellspacing="0" cellpadding="2">
					<tr>
						<td>
        <?php  echo tep_draw_form('search', FILENAME_STATS_DETAILED_MONTHLY_SALES, '', 'get'); ?>
        <table width="99%" cellpadding="2" cellspacing="0" border="0">
								<tr>
									<td class="smallText" width="20%"><?php echo HEADING_TITLE_PROVIDER; ?></td>
            <?php
	$provider_array = array (
			array (
					'id' => '',
					'text' => TEXT_NONE 
			) 
	);
	$provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name asc");
	while ($provider_result = tep_db_fetch_array($provider_query)) {
		$provider_array[] = array (
				'id' => $provider_result['agency_id'],
				'text' => $provider_result['agency_name'] 
		);
	}
	?>
            <td class="smallText"><?php echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px;" '); //onChange="this.form.submit();" ?></td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?></td>
								</tr>
							</table>
							</form>
						</td>
					</tr>
					<tr>
						<td class="smallText" colspan="10"><font color="#FF0000"> Note:
								All the data on this report are according to departure date not
								order purchase date. </font></td>
					</tr>
<?php
	
	$count = 0;
	$years_query = tep_db_query("SELECT DISTINCT( year( date_purchased ) ) AS y FROM " . TABLE_ORDERS . " ORDER BY date_purchased DESC limit 2");
	//$years_query = tep_db_query( "SELECT DISTINCT( year( products_departure_date ) ) AS y FROM " . TABLE_ORDERS_PRODUCTS . " where products_departure_date != '0000-00-00 00:00:00' ORDER BY products_departure_date DESC" );
	while ($years = tep_db_fetch_array($years_query)) {
		if ($count > 0) {
			?>
                        <tr>
						<td class="dataTableContent">
					
					</tr>	
<?
		}
		?>
                        <tr>
						<td class="pageHeading" colspan="6"><?= $years['y'] ?></td>
					</tr>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" width="20%"><?= TABLE_HEADING_MONTH ?></td>
						<td class="dataTableHeadingContent">Sales Total</td>
						<td class="dataTableHeadingContent">Cost Total</td>
						<td class="dataTableHeadingContent">Payable Total</td>
						<td class="dataTableHeadingContent">Paid Total</td>
						<td class="dataTableHeadingContent">Unpaid Total</td>
						<td class="dataTableHeadingContent">Paid %</td>
					</tr>
<?
		
		$total_sum_psum = 0;
		$total_sum_psum_cost = 0;
		$total_sum_psum_invoice = 0;
		$total_sum_psum_final_invoice_amount = 0;
		
		$months_query = tep_db_query("SELECT DISTINCT( monthname( date_purchased ) ) AS month, month( date_purchased ) AS m FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE '" . $years['y'] . "-%' ORDER BY date_purchased DESC");
		//$months_query = tep_db_query( "SELECT DISTINCT( monthname( products_departure_date ) ) AS month, month( products_departure_date ) AS m FROM " . TABLE_ORDERS_PRODUCTS . " WHERE products_departure_date LIKE '" . $years['y'] . "-%' and products_departure_date != '0000-00-00 00:00:00' ORDER BY products_departure_date DESC" );
		while ($months = tep_db_fetch_array($months_query)) {
			
			//$where = "  and  year( o.date_purchased ) = " . $years['y'] . " AND month( o.date_purchased ) = " . $months['m'] . " "; //and o.orders_status!='6'
			//必须是财务在应付账款报表中点了确认按钮的才能计算
			//$where = " and op.admin_confirm='Y' and  year(op.products_departure_date) = " . $years['y'] . " AND month(op.products_departure_date) = " . $months['m'] . " "; //and o.orders_status!='6'
			$where = " and op.admin_confirm='Y' and op.products_departure_date Like '" . $years['y'] . "-%' AND month(op.products_departure_date) = " . $months['m'] . " "; //and o.orders_status!='6'

			if (isset($HTTP_GET_VARS['provider']) && $HTTP_GET_VARS['provider'] != '') {
				$where .= " and p.agency_id ='" . $HTTP_GET_VARS['provider'] . "' ";
			}
			//echo "<br/>";		
			$prods_query_sql = "select sum(op.final_price * op.products_quantity) as psum, sum(op.final_price_cost * op.products_quantity) as psum_cost,  sum(oph.invoice_amount) as psum_invoice, sum(op.final_invoice_amount) as psum_final_invoice_amount  from   " . TABLE_ORDERS_PRODUCTS . " as op, " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph LEFT JOIN " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " oph2 ON oph.orders_products_id = oph2.orders_products_id
AND oph.ord_prod_history_id < oph2.ord_prod_history_id and oph.last_updated_date < oph2.last_updated_date,  orders as o, products as p where oph2.last_updated_date IS NULL " . $where . "  and oph.orders_products_id = op.orders_products_id  and op.orders_id = o.orders_id and  op.products_id=p.products_id "; //o.last_modified desc
			//echo "<br/>";							
			//echo $prods_query_sql.'<hr>';
			$prods_query = tep_db_query($prods_query_sql);
			
			$prods = tep_db_fetch_array($prods_query);
			
			$givenMonth = $months['m'];
			$givenYear = $years['y'];
			
			$firstDayOfMonth = date("d", mktime(0, 0, 0, $givenMonth, 1, $givenYear));
			$numberOfDays = date("d", mktime(0, 0, 0, $givenMonth + 1, 0, $givenYear));
			$lastDayOfMonth = $numberOfDays;
			
			$dept_start_date = $givenMonth . '/' . $firstDayOfMonth . '/' . $givenYear;
			$dept_end_date = $givenMonth . '/' . $lastDayOfMonth . '/' . $givenYear;
			
			$total_sum_psum = $total_sum_psum + $prods['psum'];
			$total_sum_psum_cost = $total_sum_psum_cost + $prods['psum_cost'];
			$total_sum_psum_invoice = $total_sum_psum_invoice + $prods['psum_invoice'];
			$total_sum_psum_final_invoice_amount = $total_sum_psum_final_invoice_amount + $prods['psum_final_invoice_amount'];
			
			?>
                    
                        <tr class="dataTableRow">
						<td class="dataTableContent"><a
							href="?dept_start_date=<?php echo $dept_start_date; ?>&dept_end_date=<?php echo $dept_end_date; ?>&action=view_report"><?= $months['month'] ?></a></td>
						<td class="dataTableContent">$<?= number_format( $prods['psum'], 2 ) ?></td>
						<td class="dataTableContent">$<?= number_format( $prods['psum_cost'], 2 ); //change to cost ?></td>
						<td class="dataTableContent">$<?= number_format( $prods['psum_invoice'], 2 ); //payble total ?></td>
						<td class="dataTableContent">$<?= number_format( $prods['psum_final_invoice_amount'], 2 ); //Paid Total ?></td>
						<td class="dataTableContent">$<?= number_format( ($prods['psum_invoice']-$prods['psum_final_invoice_amount']), 2 ); //Paid Total ?></td>
						<td class="dataTableContent"><?php
			if ($prods['psum_invoice'] != 0) {
				echo (int) (($prods['psum_final_invoice_amount'] / $prods['psum_invoice']) * 100) . '%';
			} else {
				echo '-';
			}
			?> </td>
					</tr>
                    
<?
			$count ++;
		}
		?>
        
                        <tr class="dataTableHeadingRow">
						<td class="dataTableContent">&nbsp;</td>
						<td class="dataTableContent"><b>$<?= number_format($total_sum_psum, 2 ) ?></b></td>
						<td class="dataTableContent"><b>$<?= number_format( $total_sum_psum_cost, 2 ); //change to cost ?></b></td>
						<td class="dataTableContent"><b>$<?= number_format( $total_sum_psum_invoice, 2 ); //payble total ?></b></td>
						<td class="dataTableContent"><b>$<?= number_format( $total_sum_psum_final_invoice_amount, 2 ); //Paid Total ?></b></td>
						<td class="dataTableContent"><b>$<?= number_format( ($total_sum_psum_invoice-$total_sum_psum_final_invoice_amount), 2 ); //Paid Total ?></b></td>
						<td class="dataTableContent"><b><?php
		if ($total_sum_psum_invoice != 0) {
			echo (int) (($total_sum_psum_final_invoice_amount / $total_sum_psum_invoice) * 100) . '%';
		} else {
			echo '-';
		}
		?> </b></td>
					</tr>
        
        <?php
	}
	
	?>
    </table>
<?php
}
?>
            </td>
		</tr>
	</table>
        <? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'footer.php'); ?>
    </body>
</html>
