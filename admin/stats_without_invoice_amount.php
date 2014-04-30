<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_without_invoice_amount');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require(DIR_WS_CLASSES . 'currencies.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>



<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_without_invoice_amount');
$list = $listrs->showRemark();
?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
  		<tr>

  			<td valign="top">
  				<div class="pageHeading"><?= HEADING_TITLE ?></div><br />
				
				
				
				
<?
/*
if(!isset($_GET['paid_filter'])){
$_GET['paid_filter'] = $HTTP_GET_VARS['paid_filter'] = '0';
}		 
*/

	if(!isset($HTTP_GET_VARS['action']) && !isset($HTTP_GET_VARS['dept_start_date']) && !isset($HTTP_GET_VARS['order_date_purchased_to'])){		
	  $_GET['dept_start_date'] = $HTTP_GET_VARS['dept_start_date'] = date('m/01/Y');
	  $_GET['dept_end_date'] = $HTTP_GET_VARS['dept_end_date'] = date("m/d/Y", mktime(0, 0, 0, date("m") + 1, 0, date("Y")));
	}
	
?>


<table border="0" width='100%' cellspacing="0" cellpadding="2">
						<tr>
							
							<td colspan="9" valign="top">
								<?php echo tep_draw_form('search', FILENAME_STATS_WITHOUT_INVOICE_AMOUNT, '', 'get'); ?>
								
								<script type="text/javascript"><!--

/*var dateAvailable_dept_start = new ctlSpiffyCalendarBox("dateAvailable_dept_start", "search", "dept_start_date","btnDate3","<?php echo tep_get_date_disp($_GET['dept_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable_dept_end = new ctlSpiffyCalendarBox("dateAvailable_dept_end", "search", "dept_end_date","btnDate4","<?php echo tep_get_date_disp($_GET['dept_end_date']); ?>",scBTNMODE_CUSTOMBLUE);


var order_date_purchased_start = new ctlSpiffyCalendarBox("order_date_purchased_start", "search", "order_date_purchased_from","btnDate3","<?php echo tep_get_date_disp($_GET['order_date_purchased_from']); ?>",scBTNMODE_CUSTOMBLUE);
var order_date_purchased_end = new ctlSpiffyCalendarBox("order_date_purchased_end", "search", "order_date_purchased_to","btnDate4","<?php echo tep_get_date_disp($_GET['order_date_purchased_to']); ?>",scBTNMODE_CUSTOMBLUE);
*/

//--></script>
								<table width="99%"  cellpadding="2" cellspacing="0" border="0">
									<tr>
										<td class="smallText" width="20%" ><?php echo HEADING_TITLE_PROVIDER; ?></td>
										<?php
										 echo tep_draw_hidden_field('action', 'view_report');
										// echo tep_draw_hidden_field('month', $_GET['month']);
										 
										 $provider_array = array(array('id' => '', 'text' => TEXT_NONE));
										 $provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
										  while($provider_result = tep_db_fetch_array($provider_query))
										  {
										  $provider_array[] = array('id' => $provider_result['agency_id'],
																	 'text' => $provider_result['agency_name']);
										  }
										?>
										<td class="smallText"  ><?php echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px;" '); //onChange="this.form.submit();" ?></td>
									</tr>
									<tr>
										<td colspan="2" class="smallText" >
											<?php echo HEADING_TITLE_INVOICED_NUMBER.'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_FROM.'&nbsp;'.tep_draw_input_field('invoiced_amt_from', '',' size="11"').'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_TO.'&nbsp;'.tep_draw_input_field('invoiced_amt_to', '',' size="12"'); ?>
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
										<td class="smallText" colspan="2">Order Date From:
										<?php echo tep_draw_input_field('order_date_purchased_from', tep_get_date_disp($_GET['order_date_purchased_from']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
										<script type="text/javascript">//order_date_purchased_start.writeControl(); order_date_purchased_start.dateFormat="MM/dd/yyyy";</script> To:
										<?php echo tep_draw_input_field('order_date_purchased_to', tep_get_date_disp($_GET['order_date_purchased_to']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
											<script type="text/javascript">//order_date_purchased_end.writeControl(); order_date_purchased_end.dateFormat="MM/dd/yyyy";</script>
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
										 $paid_status_array[] = array('id' => '0', 'text' =>'Unpaid');
										 $paid_status_array[] = array('id' => '1', 'text' =>'Paid');
										
										?>
										<td class="smallText" ><?php echo tep_draw_pull_down_menu('paid_filter', $paid_status_array, $_GET['paid_filter'], 'style="width:200px;"'); ?></td>
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

				
						<table border="0" width='100%' cellspacing="0" cellpadding="2">
				
						
						<?php echo tep_draw_form('orderitemcheck', FILENAME_STATS_WITHOUT_INVOICE_AMOUNT, tep_get_all_get_params(array('selected_box','action')).'action=proccess_payment', 'post'); ?>
						<tr>
							<td class="dataTableRowSelectedPink main" colspan="15"><?php echo PINK_ROW_EXPLAINATION_TEXT; ?></td>
						</tr>
						<tr>
							<td class="dataTableContent" colspan="9">&nbsp;</td>
						</tr>
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="5%">Order Date</td>
							<td class="dataTableHeadingContent">Tours</td>
							<td class="dataTableHeadingContent">
							<?php
							  $HEADING_PROVIDER_SORT = 'Provider#<br />';
							  $HEADING_PROVIDER_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=provider&order=ascending">';
							  $HEADING_PROVIDER_SORT .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
							  $HEADING_PROVIDER_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=provider&order=decending">';
							  $HEADING_PROVIDER_SORT .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
							  echo $HEADING_PROVIDER_SORT;
				  			?>
							</td>
							<td class="dataTableHeadingContent">
							<?php
							 $HEADING_INVOICE_SORT = 'Invoiced#<br />';
							  $HEADING_INVOICE_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=invoice&order=ascending">';
							  $HEADING_INVOICE_SORT .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
							  $HEADING_INVOICE_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=invoice&order=decending">';
							  $HEADING_INVOICE_SORT .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
							  echo $HEADING_INVOICE_SORT;
				  			?>							
							</td>
							<td class="dataTableHeadingContent">Invoiced Amount</td>
							<td class="dataTableHeadingContent">Depature Date</td>
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
							<td class="dataTableHeadingContent">Orders<br>Status</td>							
							<td class="dataTableHeadingContent">Sales</td>
							<td class="dataTableHeadingContent">Net</td>
							<td class="dataTableHeadingContent">Gross Profit</td>
							<td class="dataTableHeadingContent">Gross Profit<br>(%)</td>
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
				/////   start - search coding    ///////
						$where = ' ';
						
						
						 if(isset($_GET['paid_filter']) && ($_GET['paid_filter'] == '0' || $_GET['paid_filter'] == '1'))
						  {
							$where .= " and op.payment_paid='".$_GET['paid_filter']."'";
						  }
						  
						
						  if(isset($_GET['provider']) && $_GET['provider'] != '')
						  {
							$where .= " and p.agency_id='".$_GET['provider']."'";
						  }
						  
						  if ((isset($HTTP_GET_VARS['dept_start_date']) && tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (isset($HTTP_GET_VARS['dept_end_date']) && tep_not_null($HTTP_GET_VARS['dept_end_date'])) ) 
						  {
						  //$make_start_date = $HTTP_GET_VARS['dept_start_date'] ;
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']);
						  //$make_end_date = $HTTP_GET_VARS['dept_end_date'] ;
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']);
						  $where .= " and  op.products_departure_date >= '" . $make_start_date . "' and op.products_departure_date <= '" . $make_end_date . "' ";
						  
						   $search_extra_get .= "&dept_start_date=".$_GET['dept_start_date']."&dept_end_date=".$_GET['dept_end_date'];
						  }
						  else if ((isset($HTTP_GET_VARS['dept_start_date']) && tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (!isset($HTTP_GET_VARS['dept_end_date']) or !tep_not_null($HTTP_GET_VARS['dept_end_date'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']) . " 00:00:00";
						  
						  $where .= " and  op.products_departure_date >= '" . $make_start_date . "' ";
						  
						  $search_extra_get .= "&dept_start_date=".$_GET['dept_start_date'];
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['dept_start_date']) or !tep_not_null($HTTP_GET_VARS['dept_start_date'])) && (isset($HTTP_GET_VARS['dept_end_date']) && tep_not_null($HTTP_GET_VARS['dept_end_date'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']) . " 00:00:00";
						  
							$where .= " and  op.products_departure_date <= '" . $make_end_date . "' ";
						  $search_extra_get .= "&dept_end_date=".$_GET['dept_end_date'];
						  }
						  
						  
						
						  //for invoice amount
						  if((isset($HTTP_GET_VARS['invoiced_amt_from']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) or (isset($HTTP_GET_VARS['invoiced_amt_to']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_to'])))
						  {
							  if ((isset($HTTP_GET_VARS['invoiced_amt_from']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) && (isset($HTTP_GET_VARS['invoiced_amt_to']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_to'])) ) 
							  {
							  $invoiced_amt_from = $HTTP_GET_VARS['invoiced_amt_from'];
							  $invoiced_amt_to = $HTTP_GET_VARS['invoiced_amt_to'];
							  $invoice_where = " and opuh.invoice_number >= ".$invoiced_amt_from." and opuh.invoice_number <= ".$invoiced_amt_to."";
							  //$invoice_where = " and opuh.invoice_number  between '".$invoiced_amt_from."' and '".$invoiced_amt_to."'";
							  }
							  else if ((isset($HTTP_GET_VARS['invoiced_amt_from']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) && (!isset($HTTP_GET_VARS['invoiced_amt_to']) or !tep_not_null($HTTP_GET_VARS['invoiced_amt_to'])) ) 
							  {
							  $invoiced_amt_from = $HTTP_GET_VARS['invoiced_amt_from'];
							  $invoice_where = " and opuh.invoice_number  = '".$invoiced_amt_from."'";
							  }
							  else if ((!isset($HTTP_GET_VARS['invoiced_amt_from']) or !tep_not_null($HTTP_GET_VARS['invoiced_amt_from'])) && (isset($HTTP_GET_VARS['invoiced_amt_to']) && tep_not_null($HTTP_GET_VARS['invoiced_amt_to'])) ) 
							  {
								
							  	$invoiced_amt_to = $HTTP_GET_VARS['invoiced_amt_to'];								
							  	$invoice_where = " and opuh.invoice_number  = '".$invoiced_amt_to."'";
							  }
							 $products_id_query = "select opuh.orders_products_id, ord_prod_history_id from ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." opuh where 1=1 ".$invoice_where." group by orders_products_id order by ord_prod_history_id  desc";
							 
							  $res_products_id = tep_db_query($products_id_query);
							  $in_products_id = '0,';
							 
							  while($row_products_id = tep_db_fetch_array($res_products_id))
							  {
								  $select_final_check_latest_sql = "select opuh.orders_products_id, ord_prod_history_id from ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." opuh where orders_products_id='".$row_products_id['orders_products_id']."' order by ord_prod_history_id  desc limit 1 ";
								  $select_final_check_latest_query = tep_db_query($select_final_check_latest_sql);
								  $select_final_check_latest_row = tep_db_fetch_array($select_final_check_latest_query);			
									
								  if($row_products_id['ord_prod_history_id'] <= $select_final_check_latest_row['ord_prod_history_id']){
								  $in_products_id .= $row_products_id['orders_products_id'].",";
								  }	
								
							  }
							  
							  $in_products_id = substr($in_products_id,0,-1);
	
							  $where .= " and op.orders_products_id in(".$in_products_id.") ";
							  
						  
						  }
						  

  						
							 
						 if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) 
						  {
						  
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']);						 
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']);
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (!isset($HTTP_GET_VARS['order_date_purchased_to']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']) . " 00:00:00";
						  
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['order_date_purchased_from']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']) . " 00:00:00";
						  
						 $where .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						 
						 }
							  
							  
							  
							  
						 

						  if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
								 $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
								 $where .= " and (o.customers_name like '%" . $keywords . "%' or op.products_model like '%".$keywords."%' or o.orders_id='".$keywords."' ) ";
								
						   }
						   
						 
				/////   end - search coding    ///////
				
				 			   switch ($_GET["sort"]) {
								  case 'invoice':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by oph.invoice_number asc';
									} else {
									  $sortorder = 'order by oph.invoice_number desc';
									}
									break;
								  case 'provider':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by ta.agency_name  asc';
									} else {
									  $sortorder = 'order by ta.agency_name desc';
									}
									break;
									case 'orderid':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by o.orders_id  asc';
									} else {
									  $sortorder = 'order by o.orders_id desc';
									}
									break;
								  default:									
									$sortorder = 'order by op.products_departure_date';
									break;
								}

						
							$running_final_price = 0;
							$running_final_price_cost = 0;
							$running_gross_profit = 0 ;
							$running_margin = 0 ;
							$items_sold_cnt =0;
						
							 $prods_query_sql = "select p.agency_id, p.provider_tour_code, o.orders_id, o.orders_status, o.date_purchased, op.orders_products_id, op.payment_paid, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.final_price_cost,op.products_departure_date, op.orders_products_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment  from   ".TABLE_ORDERS_PRODUCTS." as op, ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." oph LEFT JOIN ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." oph2 ON oph.orders_products_id = oph2.orders_products_id
AND oph.ord_prod_history_id < oph2.ord_prod_history_id and oph.last_updated_date < oph2.last_updated_date, orders as o, products as p, tour_travel_agency ta where	oph2.last_updated_date IS NULL  ".$where." and o.orders_status<>6 and (oph.invoice_amount = '0.00' or oph.invoice_amount = '') and oph.orders_products_id = op.orders_products_id and op.orders_id = o.orders_id and p.agency_id=ta.agency_id and  op.products_id=p.products_id ".$sortorder." ";

							  $product_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $prods_query_sql, $prods_query_numrows);
							
							 $prods_query = tep_db_query($prods_query_sql);
							 
							 while ($prods = tep_db_fetch_array($prods_query)) {							
									  $running_final_price += $prods['final_price'];
									  $running_final_price_cost += $prods['final_price_cost'];
									  $gross_profit = number_format( $prods['final_price'] - $prods['final_price_cost'], 2 );
									  $running_gross_profit += $gross_profit;
									  if($prods['final_price'] != 0){
									  $margin = tep_round((((($prods['final_price'])-($prods['final_price_cost']))/($prods['final_price']))*100), 0);
									  }else{
									  $margin = 0;
									  }
									  $running_margin += $margin;
									  
									 
									 $select_check_order_product_payment_history_sql = "select ord_prod_payment_id, op.orders_products_id from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_ORDERS_PRODUCTS." op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP ',".$prods['orders_products_id'].",')  order by opph.ord_prod_payment_id desc";
									 $select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
									?>
	  
									<tr <?php 
								
									if( ($prods['invoice_amount'] != $prods['final_price_cost']) ){echo 'class="dataTableRowSelectedPink"';}else{ echo 'class="dataTableRow"'; } ?>>
									<td class="dataTableContent"><?= tep_date_short($prods['date_purchased']); ?></td>
									<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .$prods['products_id']) . '">' . $prods['products_name'].'</a>' .' [<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($prods['products_id']) . '&pID=' . $prods['products_id'].'&action=new_product') . '">' . $prods['products_model'] . '</a>'.']'.' [<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($prods['products_id']) . '&pID=' . $prods['products_id'].'&action=new_product') . '">' . $prods['provider_tour_code'] . '</a>'.']'; //&tabedit=eticket ?></td>
									<td class="dataTableContent"><?= tep_get_travel_agency_name($prods['agency_id']); ?></td>
									<td class="dataTableContent"><?= $prods['invoice_number']; ?></td>
									<td class="dataTableContent">
									<?php									
									if($prods['invoice_amount'] != ''){
									echo '$'.$prods['invoice_amount'];
									}
									?>
									</td>
									<td class="dataTableContent"><?= tep_date_short($prods['products_departure_date']); ?></td>
									<td class="dataTableContent"><a href="edit_orders.php?action=edit&oID=<?= $prods['orders_id'] ?>" target="_blank"><?= $prods['orders_id'] ?></a></td>
									<td class="dataTableContent"><?= tep_get_orders_status_name($prods['orders_status']); ?></td>
									<td class="dataTableContent">$<?= number_format( $prods['final_price'], 2 ); ?></td>
									<td class="dataTableContent">$<?= number_format( $prods['final_price_cost'], 2 ); ?></td>
									<td class="dataTableContent">$<?= $gross_profit;?></td>
									<td class="dataTableContent"><?php echo $margin . '%';?></td>
									<td class="dataTableContent"><?php if($prods['payment_paid'] == '1'){ echo 'Paid';}else{echo 'Unpaid';};?></td>									
									<td class="dataTableContent" align="center">
									<?php 
									$view_count = tep_db_num_rows($select_check_order_product_payment_history_row);
									if($view_count>0){ 
									  $select_check_order_product_payment_history = tep_db_fetch_array($select_check_order_product_payment_history_row);
								
									echo '<a  target="_blank" href="' . tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW,'payment_id='.$select_check_order_product_payment_history['ord_prod_payment_id'].'&item_id='.$prods['orders_products_id'], 'NONSSL') . '"><strong>View';
									if($view_count>1){
										echo $view_count;
									}
									echo '</strong></a>'; }else { echo '&nbsp;';} ?></td>
									</tr>						
	  
						  <?php
						  $items_sold_cnt++;
						  }
						  
						  ?>
						  
						</table>
						</form>						
						<table  border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td>&nbsp;</td>
						</tr>		
						 <tr>
							<td colspan="11"><table border="0" width="100%" cellspacing="0" cellpadding="2">
							  <tr>
								<td class="smallText" valign="top"><?php echo $product_split->display_count($prods_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
								<td class="smallText" align="right"><?php echo $product_split->display_links($prods_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
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