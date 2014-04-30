<?php
require('includes/application_top.php');
// 备注添加删除
$remark_gid = isset($_GET['list_by']) ? $_GET['list_by'] : '';
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_payable_report'.$remark_gid);
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

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>

<div id="spiffycalendar" class="text"></div>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'header.php'); ?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
  		<tr>
<?
if ( ! $_REQUEST['print'] ) {
?>
  			<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft"><? require(DIR_WS_INCLUDES . 'column_left.php'); ?></table></td>
<?
}
?>
  			<td valign="top" class="smallText">
  				<div class="pageHeading"><?php echo HEADING_TITLE; ?></div>
				<?php 
					if(isset($_GET['list_by']) && $_GET['list_by']=='departure'){
						echo '(By Departure Date)';
					}else{
						echo '(By Reservation Date)';
					}
				?><br /><br />
<?
$force_in = 'true';
//if ( (isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] )) || $force_in == 'true' ) {
//if ( isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] ) ) {

if ( $HTTP_GET_VARS['action'] == 'view_report' || $HTTP_GET_VARS['action'] == 'proccess_payment') {


if(!isset($_GET['paid_filter'])){
//$_GET['paid_filter'] = $HTTP_GET_VARS['paid_filter'] = '0';
}		 
?>


<table border="0" width='100%' cellspacing="0" cellpadding="2">
						<tr>
							
							<td colspan="9" valign="top">
								<?php echo tep_draw_form('search', FILENAME_STATS_PAYABLE_REPORT, '', 'get'); ?>
								<script type="text/javascript" src="includes/javascript/calendar.js"></script>
								<script type="text/javascript"><!--

//var dateAvailable_dept_start = new ctlSpiffyCalendarBox("dateAvailable_dept_start", "search", "dept_start_date","btnDate3","<?php echo tep_get_date_disp($_GET['dept_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable_dept_end = new ctlSpiffyCalendarBox("dateAvailable_dept_end", "search", "dept_end_date","btnDate4","<?php echo tep_get_date_disp($_GET['dept_end_date']); ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable_order_start = new ctlSpiffyCalendarBox("dateAvailable_order_start", "search", "order_start_date","btnDate3","<?php echo tep_get_date_disp($_GET['order_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable_order_end = new ctlSpiffyCalendarBox("dateAvailable_order_end", "search", "order_end_date","btnDate4","<?php echo tep_get_date_disp($_GET['order_end_date']); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
								<table width="99%"  cellpadding="2" cellspacing="0" border="0">
									<tr>
										<td class="smallText" width="20%" ><?php echo HEADING_TITLE_PROVIDER; ?></td>
										<?php
										 echo tep_draw_hidden_field('action', 'view_report');
										 echo tep_draw_hidden_field('list_by', $_GET['list_by']);
										// echo tep_draw_hidden_field('month', $_GET['month']);
										 
										 $provider_array = array(array('id' => '', 'text' => TEXT_NONE));
										 $provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name asc");
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
										<td colspan="2" class="smallText" >
											<?php echo HEADING_TITLE_NET_PRICE.'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_FROM.'&nbsp;'.tep_draw_input_field('net_amt_from', '',' size="11"').'&nbsp;'.HEADING_TITLE_INVOICED_AMOUNT_TO.'&nbsp;'.tep_draw_input_field('net_amt_to', '',' size="12"'); ?>
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
										<td class="smallText" colspan="2">Reservation Date From:
										<?php echo tep_draw_input_field('order_start_date', tep_get_date_disp($_GET['order_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
										<script type="text/javascript">//dateAvailable_order_start.writeControl(); dateAvailable_order_start.dateFormat="MM/dd/yyyy";</script> To:
										<?php echo tep_draw_input_field('order_end_date', tep_get_date_disp($_GET['order_end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
											<script type="text/javascript">//dateAvailable_order_end.writeControl(); dateAvailable_order_end.dateFormat="MM/dd/yyyy";</script>
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
										 //$paid_status_array[] = array('id' => '1', 'text' =>'Paid');
										 //$paid_status_array[] = array('id' => '2', 'text' =>'Final Payment Pending');
										 $paid_status_array[] = array('id' => '3', 'text' =>'Partially Paid');
										 //$paid_status_array[] = array('id' => '2', 'text' =>'Cancelled');
										
										?>
										<td class="smallText" ><?php echo tep_draw_pull_down_menu('paid_filter', $paid_status_array, $_GET['paid_filter'], 'style="width:200px;"'); ?></td>
									</tr>
									<tr>
									<td>&nbsp;</td>  
										<td>
											<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;';
											
											if($HTTP_GET_VARS['action'] == 'proccess_payment') {
											//echo '<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('selected_box','action')).'action=view_report', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
											}else{
											echo '<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT,'list_by='.$_GET['list_by']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
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
						
						<tr>
						<td><hr color="#990000" size="1"> </td>
						</tr>
			  </table>

						<table border="0" width='100%' cellspacing="0" cellpadding="2">
						<tr>
							<td class="dataTableRowSelectedPink main" colspan="15"><?php echo PINK_ROW_EXPLAINATION_TEXT; ?></td>
						</tr>
						<tr>
							<td class="dataTableRowSelectedYellow main" colspan="15"><?php echo YELLOW_ROW_EXPLAINATION_TEXT; ?></td>
						</tr>
						<tr>
							<td class="dataTableContent" colspan="9">&nbsp;</td>
						</tr>
						<tr class="dataTableHeadingRow">
							
							<td class="dataTableHeadingContent" width="5%">
								Order Date <?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=orderdate&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=orderdate&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>'; ?>  
							</td>
							<td class="dataTableHeadingContent">Tours</td>
							<td class="dataTableHeadingContent">Provider</td>
							<td class="dataTableHeadingContent">
							 <?php //echo 'Invoiced#<br><a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=invoice&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=invoice&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?>  
							<?php
							 $HEADING_INVOICE_SORT = 'Invoiced#<br />';
							  $HEADING_INVOICE_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=invoice&order=ascending">';
							  $HEADING_INVOICE_SORT .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
							  $HEADING_INVOICE_SORT .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=invoice&order=decending">';
							  $HEADING_INVOICE_SORT .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
							  echo $HEADING_INVOICE_SORT;
				  			?>	
							</td>
							<td class="dataTableHeadingContent">Invoiced Amount</td>
							<td class="dataTableHeadingContent">
							Depature Date <?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=deptdate&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=deptdate&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>'; ?> 
							</td>
							<td class="dataTableHeadingContent">
							Order# <?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=orderno&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=orderno&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>'; ?> 
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
				///////  sort coding /////////
				if($_GET["sort"] == 'orderdate') {
				  if($_GET["order"] == 'ascending') {
						$sortorder .= 'o.date_purchased  asc ';
				  } else {
						$sortorder .= 'o.date_purchased desc ';
				  }
				} elseif($_GET["sort"] == 'invoice') {
				  	if($_GET["order"]=='ascending') {
					  $sortorder = ' oph.invoice_number asc';
					} else {
					  $sortorder = ' oph.invoice_number desc';
					}
				} elseif($_GET["sort"] == 'deptdate') {
				   if($_GET["order"] == 'ascending') {
						$sortorder .= 'op.products_departure_date asc ';
				  } else {
						$sortorder .= 'op.products_departure_date desc ';
				  }
				} elseif($_GET["sort"] == 'orderno') {
				   if($_GET["order"] == 'ascending') {
						$sortorder .= 'o.orders_id asc ';
				  } else {
						$sortorder .= 'o.orders_id desc ';
				  }
				}
				else
				{
					$sortorder .= 'op.products_departure_date DESC, op.orders_products_id';
				}
				/////   start - search coding    ///////
						$where = ' 1=1 ';
						
						
						 if(isset($_GET['paid_filter']) && ($_GET['paid_filter'] != ''))
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
						  }/*else if ( isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] ) ) {
						  	$where .= " and year( op.products_departure_date ) = " . $_REQUEST['year'] . " AND monthname( op.products_departure_date ) = '" . $_REQUEST['month'] . "'";  
						  }*/
						  
						  
						  if ((isset($HTTP_GET_VARS['order_start_date']) && tep_not_null($HTTP_GET_VARS['order_start_date'])) && (isset($HTTP_GET_VARS['order_end_date']) && tep_not_null($HTTP_GET_VARS['order_end_date'])) ) 
						  {
						  //$make_start_date = $HTTP_GET_VARS['dept_start_date'] ;
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_start_date']);
						  //$make_end_date = $HTTP_GET_VARS['dept_end_date'] ;
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_end_date']);
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						   $search_extra_get .= "&order_start_date=".$_GET['order_start_date']."&order_end_date=".$_GET['order_end_date'];
						  }
						  else if ((isset($HTTP_GET_VARS['order_start_date']) && tep_not_null($HTTP_GET_VARS['order_start_date'])) && (!isset($HTTP_GET_VARS['order_end_date']) or !tep_not_null($HTTP_GET_VARS['order_end_date'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_start_date']) . " 00:00:00";
						  
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						  $search_extra_get .= "&order_start_date=".$_GET['order_start_date'];
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['order_start_date']) or !tep_not_null($HTTP_GET_VARS['order_start_date'])) && (isset($HTTP_GET_VARS['order_end_date']) && tep_not_null($HTTP_GET_VARS['order_end_date'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_end_date']) . " 00:00:00";
						  
							$where .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						  $search_extra_get .= "&order_end_date=".$_GET['order_end_date'];
						  }
						  
						  //for net/saleas amount
						  if ((isset($HTTP_GET_VARS['net_amt_from']) && tep_not_null($HTTP_GET_VARS['net_amt_from'])) && (isset($HTTP_GET_VARS['net_amt_to']) && tep_not_null($HTTP_GET_VARS['net_amt_to'])) ) 
						  {
						  //$make_start_date = $HTTP_GET_VARS['dept_start_date'] ;
						  $net_amt_from = $HTTP_GET_VARS['net_amt_from'];
						  //$make_end_date = $HTTP_GET_VARS['dept_end_date'] ;
						  $net_amt_to = $HTTP_GET_VARS['net_amt_to'];
						  $where .= " and ((op.final_price >= " . $net_amt_from . " and op.final_price <= " . $net_amt_to . ") or (op.final_price_cost >= " . $net_amt_from . " and op.final_price_cost <= " . $net_amt_to . ")) ";
						  }
						  else if ((isset($HTTP_GET_VARS['net_amt_from']) && tep_not_null($HTTP_GET_VARS['net_amt_from'])) && (!isset($HTTP_GET_VARS['net_amt_to']) or !tep_not_null($HTTP_GET_VARS['net_amt_to'])) ) {
							
						  $net_amt_from = $HTTP_GET_VARS['net_amt_from'];
						  //$where .= " and (op.final_price >= " . $net_amt_from . " or op.final_price_cost >= " . $net_amt_from . ")";
						  $where .= " and (op.final_price = " . $net_amt_from . " or op.final_price_cost = " . $net_amt_from . ")";
						  }
						  else if ((!isset($HTTP_GET_VARS['net_amt_from']) or !tep_not_null($HTTP_GET_VARS['net_amt_from'])) && (isset($HTTP_GET_VARS['net_amt_to']) && tep_not_null($HTTP_GET_VARS['net_amt_to'])) ) {
							
						  $net_amt_to = $HTTP_GET_VARS['net_amt_to'];
						  $where .= " and (op.final_price <= " . $net_amt_to . " or op.final_price_cost <= " . $net_amt_to . ") ";
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
							 
							//echo $products_id_query = "SELECT  opuh.orders_products_id, max(opuh.ord_prod_history_id)  FROM ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." as opuh  group by opuh.orders_products_id  having max(opuh.ord_prod_history_id) <= ( select opuh.ord_prod_history_id  from  ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." as opuh where 1=1 ".$invoice_where." )"; 
							// echo $products_id_query = "SELECT  orders_products_id, max(ord_prod_history_id)  FROM orders_product_update_history group by `orders_products_id`    having max(ord_prod_history_id) <= ( select ord_prod_history_id  from  orders_product_update_history where `invoice_number` = '1200' ) ";
							  $res_products_id = tep_db_query($products_id_query);
							  $in_products_id = '0,';
							 
							  while($row_products_id = tep_db_fetch_array($res_products_id))
							  {
							 // echo $row_products_id['ord_prod_history_id'].'-->'.$row_products_id['orders_products_id'].'</br>';
								  $select_final_check_latest_sql = "select opuh.orders_products_id, ord_prod_history_id from ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." opuh where orders_products_id='".$row_products_id['orders_products_id']."' order by ord_prod_history_id  desc limit 1 ";
								  $select_final_check_latest_query = tep_db_query($select_final_check_latest_sql);
								  $select_final_check_latest_row = tep_db_fetch_array($select_final_check_latest_query);			
									
								  if($row_products_id['ord_prod_history_id'] <= $select_final_check_latest_row['ord_prod_history_id']){
								  $in_products_id .= $row_products_id['orders_products_id'].",";
								  }	
								
							  }
							  
							  $in_products_id = substr($in_products_id,0,-1);
	
							  $where .= " and op.orders_products_id in(".$in_products_id.") ";
							  //$where .= " and op.orders_products_id in(3083,3086) ";
						  
						  }
						  
						 

						  if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
								 $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
								 $where .= " and (o.customers_name like '%" . $keywords . "%' or op.products_model like '%".$keywords."%' or o.orders_id='".$keywords."' ) ";
								
						   }
   
				/////   end - search coding    ///////

							$running_final_price = 0;
							$running_final_price_cost = 0;
							$running_gross_profit = 0 ;
							$running_margin = 0 ;
							$items_sold_cnt =0;
						
							if($_GET["sort"] == 'invoice'){
							 $prods_query_sql = "select p.agency_id, p.provider_tour_code, o.orders_id, o.orders_status, o.date_purchased, op.orders_products_id, op.payment_paid, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.final_price_cost,op.products_departure_date, op.orders_products_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment  from  orders as o, products as p, ".TABLE_ORDERS_PRODUCTS." as op,".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." oph LEFT JOIN ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." oph2 ON oph.orders_products_id = oph2.orders_products_id
AND oph.ord_prod_history_id < oph2.ord_prod_history_id and oph.last_updated_date < oph2.last_updated_date where ".$where." and oph2.last_updated_date IS NULL and oph.orders_products_id = op.orders_products_id and op.orders_id = o.orders_id and  op.products_id=p.products_id and ((o.orders_status = '6' or o.orders_status = '100005' and op.final_price_cost > 0) or o.orders_status = '100006') and op.payment_paid='0' order by ".$sortorder." ";
							}else{
							 $prods_query_sql = "select p.agency_id, p.provider_tour_code, o.orders_id, o.orders_status, o.date_purchased, op.orders_products_id, op.payment_paid, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.final_price_cost,op.products_departure_date, op.orders_products_id from orders_products as op, orders as o, products as p where  ".$where." and op.orders_id = o.orders_id and op.products_id=p.products_id and ((o.orders_status = '6' or o.orders_status = '100005' and op.final_price_cost > 0) or o.orders_status = '100006') and op.payment_paid='0' group by op.orders_products_id order by ".$sortorder."";
						    }
							
							 $prods_query = tep_db_query($prods_query_sql);
							 $total_invoice_amount = 0;
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
									  
									  $select_check_order_product_history_sql = "select oph.ord_prod_history_id, oph.cost, oph.retail, oph.last_updated_date, oph.invoice_number, oph.invoice_amount, oph.updated_by, oph.comment, op.products_id from " .TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY. " oph, ".TABLE_ORDERS_PRODUCTS." op where oph.orders_products_id=op.orders_products_id  and oph.orders_products_id='".$prods['orders_products_id']."'  order by oph.ord_prod_history_id desc limit 1";
									  $select_check_order_product_history_row = tep_db_query($select_check_order_product_history_sql);
									  $select_check_order_product_history = tep_db_fetch_array($select_check_order_product_history_row);
									  
									 // $select_check_order_product_payment_history_sql = "select opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_ORDERS_PRODUCTS." op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP ',".$prods['orders_products_id'].",')  order by opph.ord_prod_payment_id desc limit 1";
									 $select_check_order_product_payment_history_sql = "select ord_prod_payment_id, op.orders_products_id from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_ORDERS_PRODUCTS." op where opph.orders_products_id=op.orders_products_id and (CONCAT(',',opph.orders_products_id,',') REGEXP ',".$prods['orders_products_id'].",') order by opph.ord_prod_payment_id desc";
									  $select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
									  
									if($items_sold_cnt == 0 || $previous_dept_date == tep_date_short($prods['products_departure_date'])){
											$total_invoice_amount = $total_invoice_amount + $select_check_order_product_history['invoice_amount'];
									}else{
										?>
										<tr bgcolor="#D6E0ED" height="25">
											<td colspan="4" align="right" class="dataTableContent">Total Invoice Amount For <?php echo $previous_dept_date; ?>: </td>
											<td class="dataTableContent"><b><?php echo '$'.number_format($total_invoice_amount,2); ?></b></td>
											<td colspan="10"></td>
										</tr>
										<?php
										$total_invoice_amount = 0;
										$total_invoice_amount = $total_invoice_amount + $select_check_order_product_history['invoice_amount'];
									}
									$previous_dept_date = tep_date_short($prods['products_departure_date']);
									if(($select_check_order_product_history['invoice_amount']>0 && $prods['final_price_cost']>0 ) || ($select_check_order_product_history['invoice_amount'] == 0 && $prods['final_price_cost']>0)){
									?>
	  
									<tr <?php 
								
									if( ($select_check_order_product_history['invoice_amount'] != $prods['final_price_cost']) ){echo 'class="dataTableRowSelectedPink"';}else if($gross_profit<=0){ echo 'class="dataTableRowSelectedYellow"'; }else{ echo 'class="dataTableRow"'; } ?>>
									
									<td class="dataTableContent"><?= tep_date_short($prods['date_purchased']); ?></td>
									<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .$prods['products_id']) . '">' . $prods['products_name'].'</a>' .' [<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($prods['products_id']) . '&pID=' . $prods['products_id'].'&action=new_product') . '">' . $prods['products_model'] . '</a>'.']'.' [<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($prods['products_id']) . '&pID=' . $prods['products_id'].'&action=new_product') . '">' . $prods['provider_tour_code'] . '</a>'.']'; //&tabedit=eticket ?></td>
									<td class="dataTableContent"><?= tep_get_travel_agency_name($prods['agency_id']); ?></td>
									<td class="dataTableContent"><?= $select_check_order_product_history['invoice_number']; ?></td>
									<td class="dataTableContent">
									<?php
									/*
									if($select_check_order_product_history['invoice_amount'] != '0.00' && $select_check_order_product_history['invoice_amount'] != ''){ 
									echo '$'.$select_check_order_product_history['invoice_amount']; 
									}
									*/
									if($select_check_order_product_history['invoice_amount'] != ''){
									echo '$'.$select_check_order_product_history['invoice_amount'];
									}
									?>
									</td>
									<td class="dataTableContent"><?= tep_date_short($prods['products_departure_date']); ?></td>
									<td class="dataTableContent"><a href="edit_orders.php?action=edit&oID=<?= $prods['orders_id'] ?>" target="_blank"><strong><?= $prods['orders_id'] ?></strong></a></td>
									<td class="dataTableContent"><?= tep_get_orders_status_name($prods['orders_status']); ?></td>
									<td class="dataTableContent">$<?= number_format( $prods['final_price'], 2 ); ?></td>
									<td class="dataTableContent">$<?= number_format( $prods['final_price_cost'], 2 ); ?></td>
									<td class="dataTableContent" <?php if($gross_profit<=0){ echo ' style="background-color:#FFFFCC;"'; } ?>>$<?= $gross_profit;?></td>
									<td class="dataTableContent"><?php echo $margin . '%';?></td>
									<td class="dataTableContent"><?php if($prods['payment_paid'] == '1'){ echo 'Paid';} else if($prods['payment_paid'] == '2'){ echo 'Final Payment Pending';} else if($prods['payment_paid'] == '3'){ echo 'Partially Paid';}else {echo 'Unpaid';};?></td>
									<?php /*
									<td class="dataTableContent"><?php echo $select_check_order_product_payment_history['payment_method'] .' '.$select_check_order_product_payment_history['payment_comment']; ?></td>
									<td class="dataTableContent"><?php echo $select_check_order_product_payment_history['payment_amount'];?></td>
									<td class="dataTableContent"><?php echo tep_get_admin_customer_name($select_check_order_product_payment_history['updated_by']);?></td>
									<td class="dataTableContent"><?php echo tep_datetime_short($select_check_order_product_payment_history['last_update_date']);?></td>
									*/ ?>
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
									<?php } ?>						
	  
						  <?php
						  $str_order_id = $str_order_id.', '.$prods['orders_id'];
						  $items_sold_cnt++;
						  }
						  
						  if($items_sold_cnt > 0) {						  
						  ?>
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" colspan="8"></td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_final_price, 2 ) ?></td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_final_price_cost, 2 ) ?></td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_gross_profit, 2 ) ?></td>
							<td class="dataTableHeadingContent"><?= tep_round(($running_margin/$items_sold_cnt) , 0). '%'; ?></td>
							<td class="dataTableHeadingContent">&nbsp;</td>							
							<td class="dataTableHeadingContent">&nbsp;</td>
						</tr>
						<?php } ?>
						</table>
<?

} else {

?>
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_payable_report'.$remark_gid);
$list = $listrs->showRemark('list_by='.$remark_gid);
?>
<table border="0" width='60%' cellspacing="0" cellpadding="2">
<?php

	$count = 0;
	
	if(isset($_GET['list_by']) && $_GET['list_by']=='departure'){
		$years_query = tep_db_query( "SELECT DISTINCT( year( products_departure_date ) ) AS y FROM " . TABLE_ORDERS_PRODUCTS . " where products_departure_date != '0000-00-00 00:00:00' ORDER BY products_departure_date DESC limit 2" );
	}else{
		$years_query = tep_db_query( "SELECT DISTINCT( year( date_purchased ) ) AS y FROM " . TABLE_ORDERS . " ORDER BY date_purchased DESC limit 2" );
	}
	//$years_query = tep_db_query( "SELECT DISTINCT( year( products_departure_date ) ) AS y FROM " . TABLE_ORDERS_PRODUCTS . " where products_departure_date != '0000-00-00 00:00:00' ORDER BY products_departure_date DESC" );
	while ( $years = tep_db_fetch_array( $years_query ) ) {
		if ( $count > 0 ) {
?>
						<tr>
							<td class="dataTableContent">
						</tr>	
<?
		}
?>
						<tr>
							<td class="pageHeading" colspan="4"><?= $years['y'] ?></td>
						</tr>
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="20%" >
								<?php 
									if(isset($_GET['list_by']) && $_GET['list_by']=='departure'){
										echo TABLE_HEADING_DEPARTURE_MONTH;
									}else{
										echo TABLE_HEADING_RESERVATION_MONTH;
									}								
								?>
							</td>
							<td class="dataTableHeadingContent" align="right">Unpaid Invoiced Amount</td>
							<td class="dataTableHeadingContent" align="right">Cost Amount <?php //echo '<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=cost&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, tep_get_all_get_params(array('sort','order')).'sort=cost&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?>  </td>
							<?php /*?><td class="dataTableHeadingContent" align="right">Paid Amount</td>
							<td class="dataTableHeadingContent" align="right">Unpaid Amount</td><?php */?>					
						</tr>
<?

		$total_invoice_amount = 0;
		$total_cost_amount = 0;
		$total_paid_amount = 0;
		$total_unpaid_amount = 0;

		if(isset($_GET['list_by']) && $_GET['list_by']=='departure'){
			$months_query = tep_db_query( "SELECT DISTINCT( monthname( op.products_departure_date ) ) AS month, month( op.products_departure_date ) AS m FROM " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_ORDERS." o WHERE op.products_departure_date LIKE '" . $years['y'] . "-%' and op.products_departure_date != '0000-00-00 00:00:00' and op.payment_paid = '0' and op.orders_id = o.orders_id  and ((o.orders_status = '6' or o.orders_status = '100005' and op.final_price_cost > 0) or o.orders_status = '100006') ORDER BY op.products_departure_date DESC" );
		}else{
			$months_query = tep_db_query( "SELECT DISTINCT( monthname( o.date_purchased ) ) AS month, month( o.date_purchased ) AS m FROM " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_ORDERS." o WHERE o.date_purchased LIKE '" . $years['y'] . "-%' and o.date_purchased != '0000-00-00 00:00:00' and op.payment_paid = '0' and op.orders_id = o.orders_id  and ((o.orders_status = '6' or o.orders_status = '100005' and op.final_price_cost > 0) or o.orders_status = '100006') ORDER BY o.date_purchased DESC" );
		}
		
		while ( $months = tep_db_fetch_array( $months_query ) ) {
			
			if(isset($_GET['list_by']) && $_GET['list_by']=='departure'){
				$where = "  and  year(op.products_departure_date) = " . $years['y'] . " AND month(op.products_departure_date) = " . $months['m'] . " "; //and o.orders_status!='6'
			}else{
				$where = "  and  year( o.date_purchased ) = " . $years['y'] . " AND month( o.date_purchased ) = " . $months['m'] . " "; //and o.orders_status!='6'
			}
			 
			if(isset($HTTP_GET_VARS['provider']) && $HTTP_GET_VARS['provider'] != ''){
				$where .= " and p.agency_id ='".$HTTP_GET_VARS['provider']."' ";
			}
			//echo "<br/>";		
		    $prods_query_sql = "select sum(op.final_price * op.products_quantity) as psum, sum(op.final_price_cost * op.products_quantity) as total_cost_amount,  sum(oph.invoice_amount) as total_invoice_amount, sum(op.final_invoice_amount) as total_paid_amount  from   ".TABLE_ORDERS_PRODUCTS." as op, ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." oph LEFT JOIN ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." oph2 ON oph.orders_products_id = oph2.orders_products_id
AND oph.ord_prod_history_id < oph2.ord_prod_history_id and oph.last_updated_date < oph2.last_updated_date,  orders as o, products as p where oph2.last_updated_date IS NULL ".$where."  and oph.orders_products_id = op.orders_products_id  and op.orders_id = o.orders_id and  op.products_id=p.products_id  and (op.payment_paid='0') and ((o.orders_status = '6' or o.orders_status = '100005' and (op.final_price_cost > 0 and oph.invoice_amount > 0)) or o.orders_status = '100006')"; //o.last_modified desc
			//echo "<br/>";							
			$prods_query = tep_db_query($prods_query_sql);
							 
			$prods = tep_db_fetch_array($prods_query);
			
			/*
			$net_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id IN ( SELECT orders_id FROM orders WHERE year( date_purchased ) = " . $years['y'] . " AND month( date_purchased ) = " . $months['m'] . " ) AND class = 'ot_subtotal'" );
			$net_total = tep_db_fetch_array( $net_total_query );
			$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id IN ( SELECT orders_id FROM orders WHERE year( date_purchased ) = " . $years['y'] . " AND month( date_purchased ) = " . $months['m'] . " ) AND class = 'ot_shipping'" );
			$shipping_total = tep_db_fetch_array( $shipping_total_query );
			*/
			
			$givenMonth = $months['m'];
			$givenYear = $years['y'];
			 
			
			 $firstDayOfMonth = date("d", mktime(0, 0, 0, $givenMonth, 1, $givenYear));
			 $numberOfDays = date("d", mktime(0, 0, 0, $givenMonth + 1, 0, $givenYear));
			 $lastDayOfMonth = $numberOfDays;
			 
			
			$dept_start_date = $givenMonth.'/'.$firstDayOfMonth.'/'.$givenYear;
			$dept_end_date = $givenMonth.'/'.$lastDayOfMonth.'/'.$givenYear;
			
			$order_start_date = $givenMonth.'/'.$firstDayOfMonth.'/'.$givenYear;
			$order_end_date = $givenMonth.'/'.$lastDayOfMonth.'/'.$givenYear;
			
			$total_invoice_amount = $total_invoice_amount + $prods['total_invoice_amount'];
			$total_cost_amount = $total_cost_amount + $prods['total_cost_amount'];
			$total_paid_amount = $total_paid_amount + $prods['total_paid_amount'];
			$total_unpaid_amount = $total_unpaid_amount + ($prods['total_cost_amount'] - $prods['total_paid_amount']);

			
if($prods['total_invoice_amount'] >0 && $prods['total_cost_amount'] > 0){
?>
					
						<tr <?php if( ($prods['total_invoice_amount'] != $prods['total_cost_amount']) ){echo 'class="dataTableRowSelectedPink"';}else{ echo 'class="dataTableRow"';} ?>>
							<td class="dataTableContent">
							<?php  
								if(isset($_GET['list_by']) && $_GET['list_by']=='departure'){
									$link = 'stats_payable_report.php?dept_start_date='.$dept_start_date.'&dept_end_date='.$dept_end_date.'&action=view_report&list_by=departure';
								}else{
									$link = 'stats_payable_report.php?order_start_date='.$dept_start_date.'&order_end_date='.$dept_end_date.'&action=view_report';
								}
							?>
							<a href="<?php echo $link; ?>"><?php echo '<b>'.$months['month'].'</b>'; ?></a>
							</td>
							<td class="dataTableContent" align="right">$<?= number_format( $prods['total_invoice_amount'], 2 ) ?></td>
							<td class="dataTableContent" align="right">$<?= number_format( $prods['total_cost_amount'], 2 ); //payble total ?></td>							
							<?php /*?><td class="dataTableContent" align="right">$<?= number_format( $prods['total_paid_amount'], 2 ); //Paid Total ?></td>
							<td class="dataTableContent" align="right">$<?= number_format( ($prods['total_cost_amount']-$prods['total_paid_amount']), 2 ); //Paid Total ?></td><?php */?>
						</tr>
					
<?
}
			$count ++;
		}
		?>
		
						<tr class="dataTableHeadingRow">
							<td class="dataTableContent">&nbsp;</td>
							<td class="dataTableContent" align="right"><b>$<?= number_format($total_invoice_amount, 2 ) ?></b></td>
							<td class="dataTableContent" align="right"><b>$<?= number_format( $total_cost_amount, 2 ); ?></b></td>
							<?php /*?><td class="dataTableContent" align="right"><b>$<?= number_format( $total_paid_amount, 2 ); ?></b></td>							
							<td class="dataTableContent" align="right"><b>$<?= number_format( $total_unpaid_amount, 2 ); ?></b></td><?php */?>
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
  		