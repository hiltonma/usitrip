<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_uncharged_report');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require(DIR_WS_CLASSES.'split_page_results_outer.php');
require(DIR_WS_CLASSES . 'currencies.php');

if($HTTP_GET_VARS['action'] == 'edit_invo_proccess'){
	$display_msg_txt = '';
	if($HTTP_POST_VARS['settlement_date'] != '' && ($HTTP_POST_VARS['batch_no'] != $HTTP_POST_VARS['prev_batch_no'] || $HTTP_POST_VARS['batch_amt'] != $HTTP_POST_VARS['prev_batch_amt'] )  ){
	
	 /* update code start */
	 $settlement_date = date("Y-m-d", $HTTP_POST_VARS['settlement_date']);
	 if($HTTP_POST_VARS['action'] == 'insert'){
		$sql_data_array = array(			  
			'orders_settlement_date' => $settlement_date,
			'orders_settlement_date_short' => $settlement_date,
			'orders_settlement_batch_no' => $HTTP_POST_VARS['batch_no'],	
			'orders_settlement_batch_total' => $HTTP_POST_VARS['batch_amt'],
			);
		tep_db_perform(TABLE_ORDERS_SETTLEMENT_BATCH_INFO, $sql_data_array);
	 }else{
	 	$sql_data_array = array(			  
			'orders_settlement_batch_no' => $HTTP_POST_VARS['batch_no'],	
			'orders_settlement_batch_total' => $HTTP_POST_VARS['batch_amt'],
			);
		tep_db_perform(TABLE_ORDERS_SETTLEMENT_BATCH_INFO, $sql_data_array, 'update', " orders_settlement_date = '".$settlement_date."' ");
	 }
	 /* update code end */
	 
	 $display_msg_txt .= "Batch No. and Total updated successfully. please close this window and refresh parent window to see updated effect";
	}else {
	 $display_msg_txt .= "Nothing to Changed. or Error in Input";
	}
	
	?>
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr><td class="main" height="20">&nbsp; </td></tr>
	<tr><td class="main"><?php echo $display_msg_txt;?></td></tr>
	<tr><td class="main" height="20">&nbsp; </td></tr>
	</table>
	<?php	
	exit;
}

if($HTTP_GET_VARS['action'] == 'show_popup_form' && $HTTP_GET_VARS['settlement_date'] != ''){

	$settlement_date = date("Y-m-d", $HTTP_GET_VARS['settlement_date']);
	$get_batch_info = tep_db_query("select orders_settlement_batch_no, orders_settlement_batch_total from ".TABLE_ORDERS_SETTLEMENT_BATCH_INFO." where orders_settlement_date = '".$settlement_date."'");
	
	?>
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr><td>
		<?php echo tep_draw_form('batch_info_edit', FILENAME_STATS_UNCHARGED_REPORT, 'action=edit_invo_proccess', 'post'); ?>
		<table border="0" cellspacing="3" cellpadding="3">
		  <?php
		    if(tep_db_num_rows($get_batch_info)>0){
				echo tep_draw_hidden_field('action', 'edit');
				$row_batch_info = tep_db_fetch_array($get_batch_info);
			}else{
				echo tep_draw_hidden_field('action', 'insert');
			}
		  ?>
		  <tr>
			<td class="main">Batch No.</td>
			<td><input type="text" name="batch_no" value="<?php echo $row_batch_info['orders_settlement_batch_no']; ?>"><input type="hidden" name="prev_batch_no" value="<?php echo $row_batch_info['orders_settlement_batch_no']; ?>"></td>
		  </tr>
		  <tr>
			<td class="main">Batch Total</td>
			<td>
			<input type="text" name="batch_amt" value="<?php echo $row_batch_info['orders_settlement_batch_total']; ?>">
			<input type="hidden" name="prev_batch_amt" value="<?php echo $row_batch_info['orders_settlement_batch_total']; ?>">
			<input type="hidden" name="settlement_date" value="<?php echo (int)$HTTP_GET_VARS['settlement_date'];?>">
			</td>
		  </tr>
		  <tr><td height="10"></td></tr>
		  <tr>
		  	<td colspan="2" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
		  </tr>
		</table>
		</form>
	</td></tr>
	</table>
	
	<?php
exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>

<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=250,screenX=150,screenY=50,top=175,left=300')
}
function settle_ment_total_div(divid){
	if(document.getElementById(divid).style.display=="none"){
		document.getElementById(divid).style.display="";
	}else{
		document.getElementById(divid).style.display="none";
	}
}
//--></script>
<div id="spiffycalendar" class="text"></div>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_uncharged_report');
$list = $listrs->showRemark();
?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
  		<tr>

  			<td valign="top">
  				<div class="pageHeading"><?= HEADING_TITLE ?></div><br />
				
				
				
				
<?

 $orders_statuses = array();

  $orders_status_array = array();

  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");

  while ($orders_status = tep_db_fetch_array($orders_status_query)) {

    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],

                               'text' => $orders_status['orders_status_name']);

    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];

  }


	/*if(!isset($HTTP_GET_VARS['action']) && !isset($HTTP_GET_VARS['settle_start_date']) && !isset($HTTP_GET_VARS['order_date_purchased_to'])){		
	  $_GET['settle_start_date'] = $HTTP_GET_VARS['settle_start_date'] = date('m/01/Y');
	 // $_GET['settle_end_date'] = $HTTP_GET_VARS['settle_end_date'] = date("m/d/Y", mktime(0, 0, 0, date("m") + 1, 0, date("Y")));
	}*/ 
	if(!isset($HTTP_GET_VARS['dept_start_date']) && !isset($HTTP_GET_VARS['dept_end_date'])){
		$HTTP_GET_VARS['dept_start_date'] = $_GET['dept_start_date'] = '01/01/2010';
	}
	/*if(!isset($HTTP_GET_VARS['pay_method_filter']) && !isset($HTTP_GET_VARS['pay_method_filter'])){
		$HTTP_GET_VARS['pay_method_filter'] = $_GET['pay_method_filter'] = 'Credit Card';
	}*/
?>


<table border="0" width='100%' cellspacing="0" cellpadding="2">
						<tr>
							
							<td colspan="9" valign="top">
								<?php echo tep_draw_form('search', FILENAME_STATS_UNCHARGED_REPORT, '', 'get'); ?>
								
								<script type="text/javascript">

/*var dateAvailable_settle_start = new ctlSpiffyCalendarBox("dateAvailable_settle_start", "search", "settle_start_date","btnDate3","<?php echo tep_get_date_disp($_GET['settle_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable_settle_end = new ctlSpiffyCalendarBox("dateAvailable_settle_end", "search", "settle_end_date","btnDate4","<?php echo tep_get_date_disp($_GET['settle_end_date']); ?>",scBTNMODE_CUSTOMBLUE);

var dateAvailable_dept_start = new ctlSpiffyCalendarBox("dateAvailable_dept_start", "search", "dept_start_date","btnDate3","<?php echo tep_get_date_disp($_GET['dept_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable_dept_end = new ctlSpiffyCalendarBox("dateAvailable_dept_end", "search", "dept_end_date","btnDate4","<?php echo tep_get_date_disp($_GET['dept_end_date']); ?>",scBTNMODE_CUSTOMBLUE);
*/
</script>
								<table  cellpadding="2" cellspacing="0" border="0">
									<tr>
										<td class="smallText" colspan="2">Reservation Date From:
										<?php echo tep_draw_input_field('settle_start_date', tep_get_date_disp($_GET['settle_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
										<script type="text/javascript">//dateAvailable_settle_start.writeControl(); dateAvailable_settle_start.dateFormat="MM/dd/yyyy";</script> To:
										<?php echo tep_draw_input_field('settle_end_date', tep_get_date_disp($_GET['settle_end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
											<script type="text/javascript">//dateAvailable_settle_end.writeControl(); dateAvailable_settle_end.dateFormat="MM/dd/yyyy";</script>
										</td>
									</tr>
									<tr>
										<td class="smallText" colspan="2">Departure Date From:
										<?php echo tep_draw_input_field('dept_start_date', tep_get_date_disp($_GET['dept_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
										<script type="text/javascript">//dateAvailable_dept_start.writeControl(); dateAvailable_dept_start.dateFormat="MM/dd/yyyy";</script> To:
										<?php echo tep_draw_input_field('dept_end_date', tep_get_date_disp($_GET['dept_end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
											<script type="text/javascript">//dateAvailable_dept_end.writeControl(); dateAvailable_dept_end.dateFormat="MM/dd/yyyy";</script>
										</td>
									</tr>
									
									<tr>
										<td class="smallText" ><?php echo HEADING_TITLE_SEARCH_BY; ?></td>
										<td class="smallText"><?php echo tep_draw_input_field('search', $HTTP_GET_VARS['search'], '80'); ?></td>
									</tr>	
									<tr>
										<td class="smallText" ><?php echo HEADING_TITLE_FILTER_BY; ?></td>
										<?php
																			 
										 $paid_status_array = array(array('id' => '', 'text' => 'All'));	
										 /*	
										 $fildert_by_payment_method_sql ="select pay_method from orders_pay_methods order by pay_method";															 										 $fildert_by_payment_method_query = tep_db_query($fildert_by_payment_method_sql);
										 while ($fildert_by_payment_method = tep_db_fetch_array($fildert_by_payment_method_query)) {
											$paid_status_array[] = array('id'   => $fildert_by_payment_method['pay_method'],
																		  'text' => $fildert_by_payment_method['pay_method']);
								  
								  		}	
										*/	
										/*	
										$paid_status_array[] = array('id'   => "Credit Card",	'text' => "Credit Card");
										$paid_status_array[] = array('id'   => "US Wire",	'text' => "Wire to US Bank");
										$paid_status_array[] = array('id'   => "Cash Deposit to Bank",	'text' => "Cash Deposit to US Bank");										
										$paid_status_array[] = array('id'   => "PayPal",	'text' => "PayPal");
										$paid_status_array[] = array('id'   => "Check/Cashier's Check/Money Order",	'text' => "Check/Cashier's Check/Money Order");
										$paid_status_array[] = array('id'   => "Cash to US Office",	'text' => "Cash to US Office");	
										$paid_status_array[] = array('id'   => "Cash to China Office",	'text' => "Cash to China Office");										
										$paid_status_array[] = array('id'   => "Customer's Credit Card Paid to Provider	",	'text' => "Customer Paid Directly to Provider");										
										$paid_status_array[] = array('id'   => "China Wire",	'text' => "Wire to China");
										*/
										$paid_status_array[] = array('id'   => "Credit Card",	'text' => "Credit Card");
										$paid_status_array[] = array('id'   => "US Wire",	'text' => "US Wire");	
										$paid_status_array[] = array('id'   => "Cash Deposit to China Bank(CMB: 8015)",	'text' => "Cash Deposit to China Bank(CMB: 8015)");																
										$paid_status_array[] = array('id'   => "Cash Deposit to China Bank(CCB: 2113)",	'text' => "Cash Deposit to China Bank(CCB: 2113)");		
										$paid_status_array[] = array('id'   => "Cash Deposit to China Bank(ICBC: 924)",	'text' => "Cash Deposit to China Bank(ICBC: 924)");		
										$paid_status_array[] = array('id'   => "Cash Deposit to China Bank(BOC: 192)",	'text' => "Cash Deposit to China Bank(BOC: 192)");		
										$paid_status_array[] = array('id'   => "Cash Deposit to US Bank",	'text' => "Cash Deposit to US Bank");	
										$paid_status_array[] = array('id'   => "Cash to US Office",	'text' => "Cash to US Office");
										$paid_status_array[] = array('id'   => "Cash to China Office",	'text' => "Cash to China Office");
										$paid_status_array[] = array('id'   => "Cash Deposit",	'text' => "Cash Deposit");										
										$paid_status_array[] = array('id'   => "Money Order/Traveler's Check/Cashier's Check",	'text' => "Money Order/Traveler's Check/Cashier's Check");
										$paid_status_array[] = array('id'   => "China Wire",	'text' => "China Wire");	
										$paid_status_array[] = array('id'   => "Bank Wire Transfer",	'text' => "Bank Wire Transfer");
										$paid_status_array[] = array('id'   => "Customer's Credit Card Paid to Provider	",	'text' => "Customer's Credit Card Paid to Provider");										
										$paid_status_array[] = array('id'   => "PayPal",	'text' => "PayPal");
										
										?>
										<td class="smallText" ><?php echo tep_draw_pull_down_menu('pay_method_filter', $paid_status_array, urldecode(stripslashes($_GET['pay_method_filter'])), 'style="width:200px;"'); ?></td>
									</tr>
									<tr>
									<td>&nbsp;</td>  
										<td>
											<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;';
											
											?>
											
											
										</td>
									</tr>
								</table>
								<?php
								echo '</form>';
								?>
							</td>
						</tr>
						<tr>
						<td><hr color="#990000" size="1"> </td>
						</tr>
						</table>
<?php
/////   start - search coding    ///////
				
				
				
						$where = ' ';
						$where_expternal_search = '';
						
						 if(isset($_GET['pay_method_filter']) && $_GET['pay_method_filter'] != '')
						  {
							$where .= " and (o.payment_method like'%".$_GET['pay_method_filter']."%' or osi.reference_comments like '%" . $_GET['pay_method_filter'] . "%' or osi.orders_payment_method like '%" . $_GET['pay_method_filter'] . "%' )";
						  }
						  
						
						  if(isset($_GET['provider']) && $_GET['provider'] != '')
						  {
							$where .= " and p.agency_id='".$_GET['provider']."'";
						  }
						  
						  if ((isset($HTTP_GET_VARS['settle_start_date']) && tep_not_null($HTTP_GET_VARS['settle_start_date'])) && (isset($HTTP_GET_VARS['settle_end_date']) && tep_not_null($HTTP_GET_VARS['settle_end_date'])) ) 
						  {
						 
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['settle_start_date']) . " 00:00:00";
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['settle_end_date']) . " 00:00:00";
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						  // $search_extra_get .= "&settle_start_date=".$_GET['settle_start_date']."&settle_end_date=".$_GET['settle_end_date'];
						  }
						  else if ((isset($HTTP_GET_VARS['settle_start_date']) && tep_not_null($HTTP_GET_VARS['settle_start_date'])) && (!isset($HTTP_GET_VARS['settle_end_date']) or !tep_not_null($HTTP_GET_VARS['settle_end_date'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['settle_start_date']) . " 00:00:00";
						  
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						 // $search_extra_get .= "&settle_start_date=".$_GET['settle_start_date'];
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['settle_start_date']) or !tep_not_null($HTTP_GET_VARS['settle_start_date'])) && (isset($HTTP_GET_VARS['settle_end_date']) && tep_not_null($HTTP_GET_VARS['settle_end_date'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['settle_end_date']) . " 00:00:00";
						  
							$where .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						  //$search_extra_get .= "&settle_end_date=".$_GET['settle_end_date'];
						  }
						  
						  
						  if ((isset($HTTP_GET_VARS['dept_start_date']) && tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (isset($HTTP_GET_VARS['dept_end_date']) && tep_not_null($HTTP_GET_VARS['dept_end_date'])) ) 
						  {
						 
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']) . " 00:00:00";
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']) . " 00:00:00";
						  $where .= " and  op.products_departure_date >= '" . $make_start_date . "' and op.products_departure_date <= '" . $make_end_date . "' ";
						  
						  // $search_extra_get .= "&settle_start_date=".$_GET['settle_start_date']."&settle_end_date=".$_GET['settle_end_date'];
						  }
						  else if ((isset($HTTP_GET_VARS['dept_start_date']) && tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (!isset($HTTP_GET_VARS['dept_end_date']) or !tep_not_null($HTTP_GET_VARS['dept_end_date'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']) . " 00:00:00";
						  
						  $where .= " and  op.products_departure_date >= '" . $make_start_date . "' ";
						  
						 // $search_extra_get .= "&settle_start_date=".$_GET['settle_start_date'];
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['dept_start_date']) or !tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (isset($HTTP_GET_VARS['dept_end_date']) && tep_not_null($HTTP_GET_VARS['dept_end_date'])) ) {
							
							$make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']) . " 00:00:00";
						  
							$where .= " and  op.products_departure_date <= '" . $make_end_date . "' ";
						  //$search_extra_get .= "&settle_end_date=".$_GET['settle_end_date'];
						  }
						  
						  $where_expternal_search = $where;
						
						  //for invoice amount
						 			  

  						  if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
								 $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
								 $where_expternal_search .= " and (osi.order_value='".str_replace('$','',$keywords)."' or osi.reference_comments like '%" . $keywords . "%' or osi.orders_payment_method like '%" . $keywords . "%'  ) ";
										$insear = 0;
											 $extername_search_osi_history_sql = "select o.orders_id from ".TABLE_ORDERS." o, ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi, " . TABLE_ORDERS_PRODUCTS . " as op where o.orders_id = osi.orders_id and op.orders_id = o.orders_id ".$where_expternal_search." and osi.orders_settlement_id > 0 group by osi.orders_id ";							 	
											 $extername_search_osi_orders_ids = "'0', ";
											 $extername_search_osi_history_query = tep_db_query($extername_search_osi_history_sql);							 
											 while ($extername_search_osi_history = tep_db_fetch_array($extername_search_osi_history_query)) {
												$insear = 1;
												$extername_search_osi_orders_ids .= "'" . (int)$extername_search_osi_history['orders_id'] . "', ";
											 }
											 $extername_search_osi_orders_ids = substr($extername_search_osi_orders_ids, 0, -2);
										
										 if($insear == 1){
											$added_external_search_id = " or o.orders_id in (" . $extername_search_osi_orders_ids . ") ";
										 }
							 	
						   		 $where .= " and (o.customers_name like '%" . $keywords . "%' or o.orders_id='".$keywords."' or ot.value='".str_replace('$','',$keywords)."' or osi.order_value='".str_replace('$','',$keywords)."' or osi.reference_comments like '%" . $keywords . "%' or osi.orders_payment_method like '%" . $keywords . "%' ".$added_external_search_id."  ) ";
						 		
						   }
						   
						
				/////   end - search coding    ///////
?>
				
						<table border="0" width='100%' cellspacing="1" cellpadding="2">
				
						
						<?php echo tep_draw_form('orderitemcheck', FILENAME_STATS_UNCHARGED_REPORT, tep_get_all_get_params(array('selected_box','action')).'action=proccess_payment', 'post', ' id="orderitemcheck"');
						
						$ajax_formname = 'orderitemcheck';
						?>						
						
						<tr>
							<td class="dataTableRowSelectedPink main" colspan="15"><?php echo PINK_ROW_EXPLAINATION_TEXT; ?></td>
						</tr>
						
						<tr>
							<td class="main" colspan="15">
							<?php
							/*
							$total_sum_settlement_prods_query_sql = "select osi.order_value, ot.value,  osbi.orders_settlement_batch_no, (select sum(order_value) from orders_settlement_information where orders_id = o.orders_id) as total_settlement_amount from  
".TABLE_ORDERS." as o 
left join ".TABLE_ORDERS_SETTLEMENT_INFORMATION." as osi on osi.orders_id = o.orders_id
left join ".TABLE_ORDERS_SETTLEMENT_BATCH_INFO." as osbi on date_format(osi.settlement_date, '%Y-%m-%d') = date_format(osbi.orders_settlement_date, '%Y-%m-%d')
LEFT JOIN ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi2 ON osi.orders_id = o.orders_id and osi.orders_id = osi2.orders_id
AND osi.orders_settlement_id < osi2.orders_settlement_id
AND osi.date_added < osi2.date_added,
".TABLE_ORDERS_STATUS_HISTORY." osh LEFT JOIN ".TABLE_ORDERS_STATUS_HISTORY." osh2 ON osh.orders_id = osh2.orders_id
AND osh.orders_status_history_id < osh2.orders_status_history_id
AND osh.date_added < osh2.date_added, ".TABLE_ORDERS_TOTAL." ot where osh2.date_added IS NULL and osi2.date_added IS NULL and o.orders_status = osh.orders_status_id and o.orders_id = osh.orders_id and (o.orders_status ='100006' or o.orders_status ='6' or o.orders_status ='100005' or osi.orders_settlement_id > 0 ) and o.orders_id=ot.orders_id and ot.class='ot_total' and ot.value > 0 ".$where." group by osh.orders_id having osbi.orders_settlement_batch_no is NULL or ot.value != total_settlement_amount";	
							
							 $total_sum_settlement_prods_row = tep_db_query($total_sum_settlement_prods_query_sql);
							 $total_sum_settlement_prods_count_str = '';
							 while($total_sum_settlement_prods = tep_db_fetch_array($total_sum_settlement_prods_row)) {		
							 $total_sum_settlement_prods_count_str = $total_sum_settlement_prods_count_str + $total_sum_settlement_prods['order_value'];
							 }
							 
							 if($total_sum_settlement_prods_count_str != ''){
							 echo '<b>Total Settlement Amount :</b> '.$total_sum_settlement_prods_count_str;
							 }
							 */
							?>
							
							</td>
						</tr>
						<tr>
							<td class="dataTableContent" colspan="9">&nbsp;</td>
						</tr>
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="5%">Order Date</td>
							<td class="dataTableHeadingContent">Departure Date</td>
							<td class="dataTableHeadingContent">Settlement Date</td>
							<td class="dataTableHeadingContent">
							<?php
							 $HEADING_ORDER_SORT = 'Order#<br />';
							  $HEADING_ORDER_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=orderid&order=ascending">';
							  $HEADING_ORDER_SORT .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
							  $HEADING_ORDER_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=orderid&order=decending">';

							  $HEADING_ORDER_SORT .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
							  echo $HEADING_ORDER_SORT;
				  			?>
							</td>
							<td class="dataTableHeadingContent">Orders Status</td>							
							<td class="dataTableHeadingContent">Order Total</td>
							<td class="dataTableHeadingContent">Settlement Total</td>
							<td class="dataTableHeadingContent">Last Settlement Amount</td>
							<td class="dataTableHeadingContent">Received by</td>
							<td class="dataTableHeadingContent">Payment Method</td>			
							<td class="dataTableHeadingContent">Reference Comment</td>					
							<td class="dataTableHeadingContent">Batch No.</td>					
							<td class="dataTableHeadingContent">Batch Total</td>					
						</tr>

				<?php
				
				
				 			   switch ($_GET["sort"]) {
								  case 'invoice':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by oph.invoice_number asc, osh.date_added desc';
									} else {
									  $sortorder = 'order by oph.invoice_number desc, osh.date_added desc';
									}
									break;
								  case 'provider':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by ta.agency_name asc, osh.date_added desc';
									} else {
									  $sortorder = 'order by ta.agency_name desc, osh.date_added desc';
									}
									break;
									case 'orderid':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by o.orders_id asc, osi.settlement_date desc';
									} else {
									  $sortorder = 'order by o.orders_id desc, osi.settlement_date desc';
									}
									break;
								  default:									
									$sortorder = 'order by (FORMAT(ot.value,2) != FORMAT((select sum(order_value) from orders_settlement_information where orders_id = o.orders_id), 2) ) desc, opdatemin asc';
									break;
								}
							
							
							 
							$running_gross_profit = 0 ;
							$running_margin = 0 ;
							$items_sold_cnt =0;
							$sum_settlement_amount = 0;
							
						$prods_query_sql = "select min(op.products_departure_date) AS opdatemin, osi.reference_comments, osi.orders_payment_method, osi.settlement_date, o.payment_method, o.orders_id, o.orders_status, o.date_purchased, osh.date_added, osi.updated_by, ot.text, ot.value, osi.order_value, osbi.orders_settlement_batch_no, osbi.orders_settlement_batch_total, (select sum(order_value) from orders_settlement_information where orders_id = o.orders_id) as total_settlement_amount from  
".TABLE_ORDERS." as o 
left join ".TABLE_ORDERS_SETTLEMENT_INFORMATION." as osi on osi.orders_id = o.orders_id   
left join ".TABLE_ORDERS_SETTLEMENT_BATCH_INFO." as osbi on osi.settlement_date_short = osbi.orders_settlement_date_short
LEFT JOIN ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi2 ON osi.orders_id = o.orders_id and osi.orders_id = osi2.orders_id
AND osi.orders_settlement_id < osi2.orders_settlement_id
AND osi.date_added < osi2.date_added,
".TABLE_ORDERS_STATUS_HISTORY." osh LEFT JOIN ".TABLE_ORDERS_STATUS_HISTORY." osh2 ON osh.orders_id = osh2.orders_id
AND osh.orders_status_history_id < osh2.orders_status_history_id
AND osh.date_added < osh2.date_added, ".TABLE_ORDERS_TOTAL." ot, " . TABLE_ORDERS_PRODUCTS . " as op where osh2.date_added IS NULL and osi2.date_added IS NULL and o.orders_status = osh.orders_status_id and o.orders_id = osh.orders_id and o.orders_id=ot.orders_id and ot.class='ot_total' and ot.value > 0 and o.orders_id = op.orders_id ".$where." group by o.orders_id having (osbi.orders_settlement_batch_no is NULL and o.orders_status ='100006' and osi.orders_payment_method like '%Credit Card%') or (ot.value != total_settlement_amount ) ".$sortorder." ";	
//or (datediff(date_format(now(),'%Y-%m-%d'), date_format(opdatemin,'%Y-%m-%d')) <= 1 and o.orders_status ='100006' and osi.settlement_date is NULL)
//exit;
// and (o.payment_method like'%Credit Card%' or osi.reference_comments like '%Credit Card%' or osi.orders_payment_method like '%Credit Card%' )
//(osbi.orders_settlement_batch_no is NULL or (ot.value != (select sum(osi.order_value) from ".TABLE_ORDERS_SETTLEMENT_INFORMATION." where orders_id = o.orders_id)))
						
						  $product_split = new splitPageResults1($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $prods_query_sql, $prods_query_numrows);

							 $prods_query = tep_db_query($prods_query_sql);
							 
							 while ($prods = tep_db_fetch_array($prods_query)) {						
								
								if($prods['orders_payment_method'] != ''){
									$prods['payment_method'] = $prods['orders_payment_method'];
								}
								
								if($items_sold_cnt  % 2 == 0){
									$select_row_color = 'bgcolor="#D2E9FF"';
									$table_select_row_color = 'bgcolor="#E8F3FF"';
									
								}else{
									$select_row_color = 'bgcolor="#F3EEE0"';
									$table_select_row_color = 'bgcolor="#F9F7F0"';
								}
								?>
	  
									<tr <?php echo $select_row_color;?> class="" id="settlement_row_changed_color_id_<?= $prods['orders_id'] ?>">
									<td class="dataTableContent" height="20"><?= tep_date_short($prods['date_purchased']); ?></td>
									<td class="dataTableContent" height="20"><?php echo '<a href="javascript:settle_ment_total_div(\'div_settlement_total_box_'.$prods['orders_id'].'\');"><b>'. tep_date_short($prods['opdatemin']).'</b></a>'; ?></td>
									<td class="dataTableContent">									
									<?php /*?><a style="color:#0033CC;" href="javascript:void(0);" title="Click Here to Show Settlement History" <?php echo 'onclick="sendFormData(\''.$ajax_formname.'\',\''. tep_href_link('orders_settlement_ajax.php', 'section=order_settlement_history&setthideheading=true&ajax_formname='.$ajax_formname.'&oID=' .$prods['orders_id']).'\',\'ajax_settle_history_response_'.$prods['orders_id'].'\',\'true\');"'; ?>><strong><?php echo tep_date_short($prods['date_added']); ?></strong></a><?php */?>
									<?php echo tep_date_short($prods['settlement_date']); ?>
									</td>
									<td class="dataTableContent"><a style="color:#0033CC;" href="orders.php?action=edit&oID=<?= $prods['orders_id'] ?>" target="_blank" title="View Order Detail"><b><?php echo highlightWords($prods['orders_id'],$keywords); ?></b></a></td>
									<td class="dataTableContent"><?= tep_get_orders_status_name($prods['orders_status']); ?></td>
									<td class="dataTableContent" align="right"><?php echo highlightWords(number_format($prods['value'], 2, '.', ''),$keywords); ?>&nbsp;
									</td>	
									<td class="dataTableContent" align="right">
									<span style="white-space:nowrap;" id="div_settlement_total_<?php echo $prods['orders_id'];?>"></span>
									</td>	
									<td class="dataTableContent" align="right">
									<?php 
										if($prods['order_value'] != 0){
											if($prods['order_value'] < 0) {
												echo '<span class="errorText">'.highlightWords(number_format($prods['order_value'], 2, '.', ''),$keywords).'</span>';	
											}else{
												echo highlightWords(number_format($prods['order_value'], 2, '.', ''),$keywords);
											}
										}
										
										$sum_settlement_amount = $sum_settlement_amount + $prods['order_value'];
									?>&nbsp;
									</td>	
									<td class="dataTableContent"><?php echo tep_get_admin_customer_name($prods['updated_by']); ?></td>		
									<td class="dataTableContent"><?php echo highlightWords(stripslashes($prods['payment_method']),$keywords); ?></td>		
									<td class="dataTableContent"><?php echo highlightWords(tep_db_prepare_input(nl2br($prods['reference_comments'])),$keywords); ?></td>							
									<td class="dataTableContent"><?php echo $prods['orders_settlement_batch_no']; ?>&nbsp;
									<?php /* ?>
									<a href="javascript:popupImageWindow('<?php echo tep_href_link(FILENAME_STATS_UNCHARGED_REPORT, 'action=show_popup_form&settlement_date='.strtotime($prods['settlement_date'])); ?>');">[<u><font color="#0000CC">Edit</font></u>]<?php //echo tep_image(DIR_WS_ICONS . 'edit.gif', 'Add/Edit Invoice No. or Amount');?></a>
									<?php */ ?>

									</td>				
									<td class="dataTableContent"><?php echo $prods['orders_settlement_batch_total']; ?></td>				
									</tr>	
									
									<?php /*?><div style="margin:0; padding:0" id="ajax_settle_history_response_<?php echo $prods['orders_id'];?>"></div><?php */?>
											  <?php 
												$ord_settle_prods_detail_history_sql = "select * from ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi where orders_id ='".(int)$prods['orders_id']."' order by orders_settlement_id asc ";
												$ord_settle_prods_detail_history_query = tep_db_query($ord_settle_prods_detail_history_sql);
											 	 $sum_individule_settlemnet_row = 0;
												if(tep_db_num_rows($ord_settle_prods_detail_history_query) > 0){
											  ?>
											  <tr id="div_settlement_total_box_<?php echo $prods['orders_id'];?>" height="0"><td colspan="12">
											  <table cellspacing="1" <?php echo $table_select_row_color;?>  cellpadding="2" style="border:1px solid #006666">
											  <tr style="background-color:#006666; color:#FFFFFF;">											  
											    <td class="smallText"><strong>Charge Capture Date</strong></td>
												<td class="smallText"><strong>Payment Method</strong></td>
												<td class="smallText"><strong>Amount</strong></td>
												<td class="smallText"><strong>Reference Comment</strong></td>
												<td class="smallText"><strong>Update By</strong></td>
												<td class="smallText"><strong>Modify Date</strong></td>
											  </tr>
											 								  
											  <?php 
											   
												while($ord_settle_prods_detail_history_row = tep_db_fetch_array($ord_settle_prods_detail_history_query)){
											 	$sum_individule_settlemnet_row = $sum_individule_settlemnet_row + $ord_settle_prods_detail_history_row['order_value'];
											  ?>
											  <tr bgcolor="#DDEEEB">
											    <td class="smallText"><?php echo $ord_settle_prods_detail_history_row['settlement_date'];?></td>
												<td class="smallText"><?php echo highlightWords($ord_settle_prods_detail_history_row['orders_payment_method'],$keywords);?></td>
												<td class="smallText" align="right">
												<?php 										
													if($ord_settle_prods_detail_history_row['order_value'] < 0) {
														echo '<span class="errorText">'.highlightWords(number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', ''),$keywords).'</span>';	
													}else{
														echo highlightWords(number_format($ord_settle_prods_detail_history_row['order_value'], 2, '.', ''),$keywords);
													}	
												?>&nbsp;</td>
												<td class="smallText"><?php echo highlightWords(tep_db_prepare_input(nl2br($ord_settle_prods_detail_history_row['reference_comments'])),$keywords); ?></td>
												<td class="smallText"><?php echo tep_get_admin_customer_name($ord_settle_prods_detail_history_row['updated_by']);?></td>
												<td class="smallText"><?php echo $ord_settle_prods_detail_history_row['date_added'];?></td>
											  </tr>	
											  
											  <?php 
											  
											  
											  } //end of while
											   ?>
											   <script language="javascript">
											    document.getElementById("div_settlement_total_<?php echo $prods['orders_id'];?>").innerHTML="<?php echo number_format($sum_individule_settlemnet_row,2,'.','');?>";
												
												<?php 
												if(number_format($sum_individule_settlemnet_row,2,'.','') != number_format($prods['value'], 2, '.', '')  && $prods['value'] != 0){ ?>
												document.getElementById("settlement_row_changed_color_id_<?php echo $prods['orders_id'];?>").className = "dataTableRowSelectedPink";
												<?php } else { ?>
													settle_ment_total_div("div_settlement_total_box_<?php echo $prods['orders_id'];?>");
												<?php } ?>									
												
											</script>
											  </table>	
											  </td></tr>	
											   <?php
											   }else{
											   ?>
											    <script language="javascript">
											    document.getElementById("div_settlement_total_<?php echo $prods['orders_id'];?>").innerHTML="<?php echo number_format($prods['value'],2,'.','');?>";												
												</script>
											   <?php											   
											   } // end of if
											   ?>	
											   
	  
						  <?php
						  $items_sold_cnt++;
						  }
						  
						  ?>
						  <tr class="dataTableRow">
									<td class="dataTableContent" height="20"><strong>TOTAL:</strong></td>
									<td class="dataTableContent"></td>
									<td class="dataTableContent"></td>
									<td class="dataTableContent"></td>
									<td class="dataTableContent"></td>
									<td class="dataTableContent" align="right"></td>	
									<td class="dataTableContent" align="right"></td>
									<td class="dataTableContent" align="right">
									<strong><?php 
										echo number_format($sum_settlement_amount, 2, '.', '')
									?></strong>&nbsp;
									</td>	
									<td class="dataTableContent"></td>		
									<td class="dataTableContent"></td>		
									<td class="dataTableContent"></td>	
									<td class="dataTableContent"></td>		
									<td class="dataTableContent"></td>											
									</tr>	
						  
						</table>
						</form>						
						<table  border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td>&nbsp;</td>
						</tr>		
						 <tr>
							<td colspan="11"><table border="0" width="100%" cellspacing="0" cellpadding="2">
							  <tr>
								<td class="smallText" valign="top"><?php echo $product_split->display_count1($prods_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
								<td class="smallText" align="right"><?php echo $product_split->display_links1($prods_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
							  </tr>
							</table></td>
						  </tr>
						</table>
  			</td>
  		</tr>
		</table>
		<? require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>  		