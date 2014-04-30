<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_paid_payment_order_history_new');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require(DIR_WS_CLASSES.'split_page_results_outer.php');
if(isset($HTTP_GET_VARS['action_addcomment']) && $HTTP_GET_VARS['action_addcomment']=='true')
  { 
	if($HTTP_GET_VARS['comment']!=''){
		$HTTP_GET_VARS['comment'] = str_replace('|plushplush|','+',$HTTP_GET_VARS['comment']);
		$HTTP_GET_VARS['comment'] = str_replace('|ampamp|','&',$HTTP_GET_VARS['comment']);
		$HTTP_GET_VARS['comment'] = str_replace('|hashhash|','#',$HTTP_GET_VARS['comment']);
		$HTTP_GET_VARS['comment'] = str_replace('|newline|','<br>',$HTTP_GET_VARS['comment']);
		$select_comment_query = tep_db_query("select payment_comment from ".TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY." where ord_prod_payment_id = '".$HTTP_GET_VARS['ord_prod_payment_id']."'");
		$row_comment = tep_db_fetch_array($select_comment_query);
		if($row_comment['payment_comment']!=''){
			$br = '<br><br>';
		}else{
			$br='';
		}
		$new_comment = addslashes($row_comment['payment_comment']).$br.$HTTP_GET_VARS['comment'].'<br>- '.tep_get_admin_customer_name($login_id) . '&nbsp;('.tep_datetime_short(date("Y-m-d h:i:s")).')';
		$update_comment_ajax = tep_db_query("update ".TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY." set payment_comment='".$new_comment."',updated_by = '".$login_id."',last_update_date='".date("Y-m-d h:i:s")."' where ord_prod_payment_id = '".$HTTP_GET_VARS['ord_prod_payment_id']."'");
			echo $br.stripslashes($HTTP_GET_VARS['comment']).'<br>- '.tep_get_admin_customer_name($login_id) . '&nbsp;('.tep_datetime_short(date("Y-m-d h:i:s")).')' .'|!!!!!|'.$HTTP_GET_VARS['ord_prod_payment_id'];
	}
	exit;
}

require(DIR_WS_CLASSES . 'currencies.php');

if($HTTP_GET_VARS['action'] == 'cancel_payment'){
	if(isset($HTTP_GET_VARS['ord_prod_payment_id']) && $HTTP_GET_VARS['ord_prod_payment_id']!=''){
		tep_db_query("update ".TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY." set payment_amount = 0, is_payment_cancelled = '1' where ord_prod_payment_id = '".(int)$HTTP_GET_VARS['ord_prod_payment_id']."'");
	}
	tep_redirect(tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, tep_get_all_get_params(array('action','ord_prod_payment_id'))));
}

if($HTTP_GET_VARS['action'] == 'edit_payment_info'){
	$display_msg_txt = '';
	//if($HTTP_POST_VARS['settlement_date'] != '' && ($HTTP_POST_VARS['batch_no'] != $HTTP_POST_VARS['prev_batch_no'] || $HTTP_POST_VARS['batch_amt'] != $HTTP_POST_VARS['prev_batch_amt'] )  ){
	
	 /* update code start */
	 $agency_id = $HTTP_POST_VARS['agency_id'];
	 $payment_month_year = $HTTP_POST_VARS['payment_month_year'];
	 if($HTTP_POST_VARS['action'] == 'insert'){
		$new_payment_comment = 'Total Invoice Amount: $'.$HTTP_POST_VARS['total_invoice_amount'].'<br>Payment Status: '.$HTTP_POST_VARS['payment_status'].'<br>Payment Method: '.$HTTP_POST_VARS['payment_method'].'<br>'.$HTTP_POST_VARS['payment_comment'].'<br>- '.tep_get_admin_customer_name($login_id) . '&nbsp;('.tep_datetime_short(date("Y-m-d h:i:s")).')<br>';
		$new_payment_comment = stripslashes($new_payment_comment);
		$sql_data_array = array(			  
			'agency_id' => $agency_id,
			'payment_month_year' => $payment_month_year,
			'total_invoice_amount' => $HTTP_POST_VARS['total_invoice_amount'],	
			'payment_status' => $HTTP_POST_VARS['payment_status'],
			'payment_comment' => $new_payment_comment,
			'payment_method' => $HTTP_POST_VARS['payment_method'],
			'updated_by' => $login_id,
			'last_update_date' => date("Y-m-d h:i:s")
			);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS, $sql_data_array);
	 }else{
	 	$get_previous_comment = tep_db_query("select payment_comment from ".TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS." where agency_id = '".$agency_id."' and payment_month_year = '".$payment_month_year."'");
		$row_prev_comment = tep_db_fetch_array($get_previous_comment);
		if($row_prev_comment['payment_comment']!=''){
			$br = '<br><br>';
		}else{
			$br='';
		}
		$new_payment_comment = 'Total Invoice Amount: $'.$HTTP_POST_VARS['total_invoice_amount'].'<br>Payment Status: '.$HTTP_POST_VARS['payment_status'].'<br>Payment Method: '.$HTTP_POST_VARS['payment_method'].'<br>Comment: '.$HTTP_POST_VARS['payment_comment'].'<br>- '.tep_get_admin_customer_name($login_id) . '&nbsp;('.tep_datetime_short(date("Y-m-d h:i:s")).')<br>---------------------------------------<br><br>'.$row_prev_comment['payment_comment'];
		$new_payment_comment = stripslashes($new_payment_comment);
		$sql_data_array = array(			  
			'total_invoice_amount' => $HTTP_POST_VARS['total_invoice_amount'],	
			'payment_status' => $HTTP_POST_VARS['payment_status'],
			'payment_comment' => $new_payment_comment,
			'payment_method' => $HTTP_POST_VARS['payment_method'],
			'updated_by' => $login_id,
			'last_update_date' => date("Y-m-d h:i:s")
			);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS, $sql_data_array, 'update', " agency_id = '".$agency_id."' and payment_month_year = '".$payment_month_year."' ");
	 }
	 if($HTTP_POST_VARS['payment_status']=='Paid'){
	 	$payment_month = substr($payment_month_year,0,2);
		$payment_year = substr($payment_month_year,2);
		$get_paid_order_products = tep_db_query("select op.orders_products_id from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_TRAVEL_AGENCY . " as ta, " . TABLE_ORDERS_PRODUCTS . " as op  where  cast(opph.orders_products_id as signed) = op.orders_products_id and opph.payment_agency_id=ta.agency_id and opph.payment_agency_id = '".$agency_id."' and date_format(opph.payment_date, '%m') = '".$payment_month."' and date_format(opph.payment_date, '%y') = '".$payment_year."'");
		if(tep_db_num_rows($get_paid_order_products)>0){
			while($row_get_paid_order_products = tep_db_fetch_array($get_paid_order_products)){
				tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set payment_paid='1' where orders_products_id = '".$row_get_paid_order_products['orders_products_id']."'");
			}
		}
	 }
	 tep_add_accountant_history(3);
	 /* update code end */
	 
	 $display_msg_txt .= "Payment Status/Comments updated successfully. please close this window and refresh parent window to see updated effect";
	/*}else {
	 $display_msg_txt .= "Nothing to Changed. or Error in Input";
	}*/
	
	?>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	<table width="98%" align="center" cellpadding="0" cellspacing="0">
	<tr><td class="main" height="35">&nbsp; </td></tr>
	<tr><td class="main"><?php echo $display_msg_txt;?></td></tr>
	<tr><td class="main" height="20">&nbsp; </td></tr>
	<tr><td class="main" height="20"><a href="javascript:void();" onClick="javascript:refreshParent();"><b>Click here to Close and Refresh Parent Window</b></a></td></tr>
	</table>
	<script language="JavaScript">	
<!--
function refreshParent() {
  window.opener.location.href = window.opener.location.href;

  if (window.opener.progressWindow)
		
 {
    window.opener.progressWindow.close()
  }
  window.close();
}
//-->
</script>
	<?php	
	exit;
}

if($HTTP_GET_VARS['action'] == 'show_popup_edit_comment'){

	$agency_id = $HTTP_GET_VARS['agency_id'];
	$payment_month_year = $HTTP_GET_VARS['payment_month_year'];
	$get_payment_comment_info = tep_db_query("select total_invoice_amount, payment_status, payment_comment, payment_method from ".TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS." where payment_month_year = '".$payment_month_year."' and agency_id = '".$agency_id."'");
	
	?>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr><td height="35">&nbsp;</td></tr>
	<tr><td>
		<?php echo tep_draw_form('payment_comment_info_edit', FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, 'action=edit_payment_info', 'post'); ?>
		<table border="0" cellspacing="3" cellpadding="3">
		  <?php
		    if(tep_db_num_rows($get_payment_comment_info)>0){
				echo tep_draw_hidden_field('action', 'edit');
				$row_payment_comment_info = tep_db_fetch_array($get_payment_comment_info);
			}else{
				echo tep_draw_hidden_field('action', 'insert');
			}
		  ?>
		  
		  <tr>
			<td class="main">Total Invoice Amount:</td>
			<td>
				<input type="text" name="total_invoice_amount" value="<?php echo $row_payment_comment_info['total_invoice_amount']; ?>">			
			</td>
		  </tr>
		  <tr>
			<td class="main">Payment Status:</td>
			<td>
				<select name="payment_status">
					<option value="Final Payment Pending" <?php if($row_payment_comment_info['payment_status'] == 'Final Payment Pending'){ echo 'selected="selected"'; } ?> >Final Payment Pending</option>
					<option value="Partially Paid" <?php if($row_payment_comment_info['payment_status'] == 'Partially Paid'){ echo 'selected="selected"'; } ?> >Partially Paid</option>
					<option value="Paid" <?php if($row_payment_comment_info['payment_status'] == 'Paid'){ echo 'selected="selected"'; } ?> >Paid</option>
				</select>
			</td>
		  </tr>
		  <tr>
			<td class="main">Payment Method:</td>
			<td>
			<?php
				if($row_payment_comment_info['payment_method']!=''){
					$payment_method_selected = $row_payment_comment_info['payment_method'];
				}else{
					$payment_method_selected = urldecode($HTTP_GET_VARS['payment_method']);
				}
				
				$payment_method_array = array();
				$payment_method_array[] = array('id' => 'Check', 'text' => 'Check');
				$payment_method_array[] = array('id' => 'ACH Payment', 'text' => 'ACH Payment');
				$payment_method_array[] = array('id' => 'Cash', 'text' => 'Cash');
				$payment_method_array[] = array('id' => 'Credit Card', 'text' => 'Credit Card');
				$payment_method_array[] = array('id' => 'Customer paid directly to provider', 'text' => 'Customer paid directly to provider');
				$payment_method_array[] = array('id' => 'Wire Transfer', 'text' => 'Wire Transfer');
				$payment_method_array[] = array('id' => 'Paypal', 'text' => 'Paypal');
				$payment_method_array[] = array('id' => 'Money Order', 'text' => 'Money Order');
				$payment_method_array[] = array('id' => 'Other', 'text' => 'Other');
				
				echo tep_draw_pull_down_menu('payment_method', $payment_method_array, $payment_method_selected, 'style="width:200px;" ');
			?>
			</td>
		  </tr>
		  <tr>
			<td class="main">Payment Comment:</td>
			<td>
				<textarea name="payment_comment" rows="3" cols="25"><?php //echo stripslashes($row_payment_comment_info['payment_comment']); ?></textarea>
				<input type="hidden" name="agency_id" value="<?php echo $agency_id;?>">			
				<input type="hidden" name="payment_month_year" value="<?php echo $payment_month_year;?>">			
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
		<div id="spiffycalendar" class="text"></div>
<script language="javascript">
 function toggel_div(divid,commentdivid)
    {
        if(eval("document.getElementById('" +  divid + "').style.display") == ''){
            eval("document.getElementById('" +  divid + "').style.display = 'none'");
			if(divid!=commentdivid){
				eval("document.getElementById('" +  commentdivid + "').style.display = 'none'");
			}
        }else{
            eval("document.getElementById('" +  divid + "').style.display = ''");
			if(divid!=commentdivid){
				eval("document.getElementById('" +  commentdivid + "').style.display = 'none'");
			}
		}
    }

 function toggel_div_show(divid)
    {
        if(eval("document.getElementById('" +  divid + "').style.display") == 'none'){
            eval("document.getElementById('" +  divid + "').style.display = ''");
        }
    }
</script>
<script type="text/javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=300,screenX=150,screenY=50,top=175,left=300')
}
//--></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<? require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_paid_payment_order_history_new');
$list = $listrs->showRemark();
?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
  		<tr>

  			<td valign="top">
  				<div class="pageHeading"><?= HEADING_TITLE ?></div><br />			
				<?php 
								if(!isset($HTTP_GET_VARS['payment_id'])){ ?>				
				<?php
				$where = ' and 1=1 ';				
				if(!isset($_GET['modify_start_date'])){
					$HTTP_GET_VARS['modify_start_date'] = $_GET['modify_start_date'] = date('m/01/Y');
				}

				  if ((isset($HTTP_GET_VARS['modify_start_date']) && tep_not_null($HTTP_GET_VARS['modify_start_date'])) && (isset($HTTP_GET_VARS['modify_end_date']) && tep_not_null($HTTP_GET_VARS['modify_end_date'])) ) 
						  {
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['modify_start_date']);
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['modify_end_date']);						  
						  $where .= " and  opph.last_update_date >= '" . $make_start_date . "' and opph.last_update_date <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['modify_start_date']) && tep_not_null($HTTP_GET_VARS['modify_start_date'])) && (!isset($HTTP_GET_VARS['modify_end_date']) or !tep_not_null($HTTP_GET_VARS['modify_end_date'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['modify_start_date']) . " 00:00:00";
						  
						  $where .= " and  opph.last_update_date >= '" . $make_start_date . "' ";
						  
						  /*$make_start_date_end_limit = tep_get_date_db($HTTP_GET_VARS['modify_start_date']) . " 24:00:00";
						 
						  $where .= " and  opph.last_update_date <= '" . $make_start_date_end_limit . "' ";*/
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['modify_start_date']) or !tep_not_null($HTTP_GET_VARS['modify_start_date'])) && (isset($HTTP_GET_VARS['modify_end_date']) && tep_not_null($HTTP_GET_VARS['modify_end_date'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['modify_end_date']) . " 00:00:00";
						  
							$where .= " and  opph.last_update_date <= '" . $make_end_date . "' ";
						  }
				
				 if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
				 $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
				 
				 $pos = strpos($keywords, 'TFF');
				 if ($pos === false) {
					$from_query = " left join ".TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS." oppc on date_format(opph.payment_date, '%m%y') = oppc.payment_month_year and oppc.agency_id = opph.payment_agency_id";
					$where_main = " and (opph.payment_method like '%" . $keywords . "%' or opph.payment_comment like '%" . $keywords . "%' or opph.payment_amount like '".$keywords."%' or oppc.payment_comment like '%". $keywords ."%' ) ";					
				 }else{
					$kw_array = explode('TFF', $keywords);
					$kw_payment_agency_id = $kw_array[0];
					$kw_payment_date = $kw_array[1];
					$where .= " and (opph.payment_agency_id = '" . $kw_payment_agency_id . "' and date_format(opph.payment_date, '%b%Y') = '" . $kw_payment_date . "') ";
				 }
				 //opph.payment_agency_id.'TFF'.date_format(opph.payment_date, '%MY') = opph.payment_date				
				}
				
				 if (isset($HTTP_GET_VARS['provider']) && tep_not_null($HTTP_GET_VARS['provider'])) {
				 
				  $where .= " and opph.payment_agency_id='".$HTTP_GET_VARS['provider']."'";
				 
				 }
				 if (isset($HTTP_GET_VARS['is_payment_cancelled']) && $HTTP_GET_VARS['is_payment_cancelled'] == 1) {				 
				  $where .= "";				 
				 }else{
				  $where .= " and opph.is_payment_cancelled='0'";
				 }
				 
				 /*if (isset($HTTP_GET_VARS['only_cancelled']) && $HTTP_GET_VARS['only_cancelled'] == 1) {				 
				  $where .= " and opph.is_payment_cancelled='1'";				 
				 }else{
				  $where .= "";
				 }*/
				if (isset($HTTP_GET_VARS['orders_id']) && tep_not_null($HTTP_GET_VARS['orders_id'])) {
				 //$where .= " and (opph.payment_method like '%" . $keywords . "%' or opph.payment_comment like '%" . $keywords . "%' or opph.payment_amount like '".$keywords."%') ";
				 $ord_prod_id_query = tep_db_query("select orders_products_id from ".TABLE_ORDERS_PRODUCTS." where orders_id=".$HTTP_GET_VARS['orders_id']);
				 $orders_products_ids='';
				 $where .= " and (opph.orders_products_id=0";

				while($row_ord_prod_id = tep_db_fetch_array($ord_prod_id_query))
				{		
					$where .= " or CONCAT(',',opph.orders_products_id,',') REGEXP ',".$row_ord_prod_id['orders_products_id'].",'";						
				}
					
				  $where .=")";


				 /*$row_ord_prod_id = tep_db_fetch_array($ord_prod_id_query);
				 $where .= " and opph.orders_products_id =".$row_ord_prod_id['orders_products_id'];*/
				}
				
				//opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date
				
				
   
				?>
				
				
				<?php echo tep_draw_form('search', FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, '', 'get'); 
				
				?>
							<script type="text/javascript" src="includes/javascript/calendar.js"></script>	
								<script type="text/javascript"><!--

//var dateAvailable_modify_start = new ctlSpiffyCalendarBox("dateAvailable_modify_start", "search", "modify_start_date","btnDate3","<?php echo tep_get_date_disp($_GET['modify_start_date']); ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable_modify_end = new ctlSpiffyCalendarBox("dateAvailable_modify_end", "search", "modify_end_date","btnDate4","<?php echo tep_get_date_disp($_GET['modify_end_date']); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
								<table width="99%"  cellpadding="2" cellspacing="0" border="0">
								<tr>
										<td class="smallText" width="20%" ><?php echo HEADING_TITLE_PROVIDER; ?></td>
										<?php
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
										<td class="smallText" width="20%"><?php echo HEADING_TITLE_SEARCH_BY; ?></td>
										<td class="smallText"><?php echo tep_draw_input_field('search', $HTTP_GET_VARS['search'], '80'); ?></td>
									</tr>	
									<tr>
										<td class="smallText" width="20%"><?php echo HEADING_TITLE_SEARCH_BY_ORDER_NO; ?></td>
										<td class="smallText"><?php echo tep_draw_input_field('orders_id', $HTTP_GET_VARS['orders_id'], '80'); ?></td>
									</tr>										
																
									<tr>
										<td class="smallText" colspan="2"><?php echo HEADING_TITLE_MODIFY_BY; ?> From:
										<?php echo tep_draw_input_field('modify_start_date', tep_get_date_disp($_GET['modify_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
										<script type="text/javascript">//dateAvailable_modify_start.writeControl(); dateAvailable_modify_start.dateFormat="MM/dd/yyyy";</script> To:
										<?php echo tep_draw_input_field('modify_start_date', tep_get_date_disp($_GET['modify_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
											<script type="text/javascript">//dateAvailable_modify_end.writeControl(); dateAvailable_modify_end.dateFormat="MM/dd/yyyy";</script>
										</td>
									</tr>
									<tr>
										<td class="smallText" ><?php echo HEADING_TITLE_FILTER_BY; ?></td>
										<?php
																				 
										 $paid_status_array = array(array('id' => '', 'text' => 'All'));										
										 $paid_status_array[] = array('id' => 'Paid', 'text' =>'Paid');
										 $paid_status_array[] = array('id' => 'FPP', 'text' =>'Final Payment Pending');
										 $paid_status_array[] = array('id' => 'PP', 'text' =>'Partially Paid');
										 //$paid_status_array[] = array('id' => '2', 'text' =>'Cancelled');
										
										?>
										<td class="smallText" ><?php echo tep_draw_pull_down_menu('paid_filter', $paid_status_array, $_GET['paid_filter'], 'style="width:200px;"'); ?></td>
									</tr>
									<?php
									if(isset($_GET['is_payment_cancelled']) && $_GET['is_payment_cancelled']==1){
										$checked = true;
									}else{
										$checked = false;
									}
									
									/*if(isset($_GET['only_cancelled']) && $_GET['only_cancelled']==1){
										$checked1 = true;
									}else{
										$checked1 = false;
									}*/
									?>
									<tr>
										<td class="smallText">Include Cancelled Payment?</td>
										<td class="smallText"><?php echo tep_draw_checkbox_field('is_payment_cancelled','1',$checked); ?></td>
									</tr>									
									<?php
									/*
									?>
									<tr>
										<td class="smallText">Show only Cancelled Payment</td>
										<td class="smallText"><?php echo tep_draw_checkbox_field('only_cancelled','1',$checked1); ?></td>
									</tr>
									<?php
									*/
									?>
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
						<?php } ?>
						
						
				
						<table border="0" width='99%' align="center" cellspacing="0" cellpadding="2">
						<?php if(isset($HTTP_GET_VARS['item_id']) || isset($HTTP_GET_VARS['orders_id']) ){ ?>
						<tr bgcolor="#FAC6BC" >
							<td class="main" colspan="16"><?php echo ORANGE_ROW_EXPLAINATION_TEXT; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td  bgcolor="#E3B3D8" class="main" colspan="15"><?php echo ' *  &nbsp;&nbsp;Order Item paid more than once'; ?></td>
						</tr>
						<tr>
							<td class="dataTableContent" colspan="9">&nbsp;</td>
						</tr>
						<tr class="dataTableHeadingRow">			
							<td class="dataTableHeadingContent" width="10%">							
								Payment ID
							</td>	
							<td class="dataTableHeadingContent" width="15%">
								Provider
							</td>
							<td class="dataTableHeadingContent" width="15%">Payment Method</td>
							<td class="dataTableHeadingContent" width="15%">Payment Amount</td>
							<td class="dataTableHeadingContent" width="25%">Payment Status/Comment</td>
							<td class="dataTableHeadingContent" width="10%">Updated by</td>
							<td class="dataTableHeadingContent" width="10%">Last Modify</td>
						</tr>

				<?
								
								if(isset($HTTP_GET_VARS['paid_filter']) && $HTTP_GET_VARS['paid_filter'] != ''){
									$from_query = " left join ".TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS." oppc on date_format(opph.payment_date, '%m%y') = oppc.payment_month_year and oppc.agency_id = opph.payment_agency_id";
									$where_query = ""; // and date_format(opph.payment_date, '%m%y') = oppc.payment_month_year and oppc.agency_id = opph.payment_agency_id
									if($HTTP_GET_VARS['paid_filter'] == 'FPP'){
										$HTTP_GET_VARS['paid_filter'] = 'Final Payment Pending';
									}			
									if($HTTP_GET_VARS['paid_filter'] == 'PP'){
										$HTTP_GET_VARS['paid_filter'] = 'Partially Paid';
									}								
									$where_query .= " and oppc.payment_status = '".$HTTP_GET_VARS['paid_filter']."'";
								}
								if(isset($HTTP_GET_VARS['payment_id']) && $HTTP_GET_VARS['payment_id'] != ''){
								
								if(isset($HTTP_GET_VARS['item_id']) && $HTTP_GET_VARS['item_id'] != ''){
								$extra_where_items_id =  " or CONCAT(',',opph.orders_products_id,',') REGEXP ',".$HTTP_GET_VARS['item_id'].",' ";
								}
								
								$select_check_order_product_payment_history_sql = "select ta.agency_name, opph.payment_agency_id, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, opph.payment_date, p.provider_tour_code, date_format(opph.payment_date, '%m') as month, date_format(opph.payment_date, '%Y') as year from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph".$from_query.", ".TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." as p, " . TABLE_ORDERS_PRODUCTS . " as op where  cast(opph.orders_products_id as signed) = op.orders_products_id and p.agency_id=ta.agency_id and op.products_id=p.products_id and (opph.ord_prod_payment_id = '".$HTTP_GET_VARS['payment_id']."' ".$extra_where_items_id." ) ".$where_query." group by ta.agency_id, date_format(opph.payment_date, '%Y'), date_format(opph.payment_date, '%m') order by ta.agency_id, opph.ord_prod_payment_id desc";
								}else{
									//$select_check_order_product_payment_history_sql = "select opph.ord_prod_payment_id, opph.orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph ".$where."  order by opph.ord_prod_payment_id desc";
									$select_check_order_product_payment_history_sql = "select ta.agency_name, opph.payment_agency_id, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, opph.payment_date, p.provider_tour_code, date_format(opph.payment_date, '%m') as month, date_format(opph.payment_date, '%Y') as year, date_format(opph.payment_date, '%m%y') as month_year from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph".$from_query.", ".TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." as p, " . TABLE_ORDERS_PRODUCTS . " as op where  cast(opph.orders_products_id as signed) = op.orders_products_id and opph.payment_agency_id=ta.agency_id and op.products_id=p.products_id ".$where.$where_main.$where_query." group by ta.agency_id, date_format(opph.payment_date, '%Y'), date_format(opph.payment_date, '%m') order by ta.agency_id, opph.ord_prod_payment_id desc";
								}
								
								
								
								$select_check_order_product_payment_history_split = new splitPageResults1($HTTP_GET_VARS['page'], 15, $select_check_order_product_payment_history_sql, $select_check_order_product_payment_history_numrows); //MAX_DISPLAY_SEARCH_RESULTS_ADMIN

								$select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
								$order_row_cnts = 0;
								$running_gross_total_payment_price = 0;
								while($select_check_order_product_payment_history = tep_db_fetch_array($select_check_order_product_payment_history_row)){
								
								if($order_row_cnts  % 2 == 0){
									$select_row_color = 'bgcolor="#D2E9FF"';
								}else{
									$select_row_color = 'bgcolor="#eadfc6"';
								}
								$order_row_cnts++;
								
								
								//$select_check_order_product_payment_history['month']
								?>
								<tr <?php echo $select_row_color;?> >	
									<td class="dataTableContent" valign="top">
									<a style="CURSOR: pointer" onclick="javascript:toggel_div('sub_div_order_paid_payment_<?php echo $order_row_cnts;?>','sub_div_order_paid_payment_<?php echo $order_row_cnts;?>')"><b><?php echo $select_check_order_product_payment_history['payment_agency_id'].'TFF'.date('MY', strtotime($select_check_order_product_payment_history['payment_date']));?></b></a>
									</td>
									<td class="dataTableContent" valign="top">
									<?php echo $select_check_order_product_payment_history['agency_name'];?>
									</td>
									<?php
									$check_comment_exist = tep_db_query("select total_invoice_amount, payment_status, payment_comment, payment_method, updated_by, last_update_date from ".TABLE_ORDERS_PRODUCTS_PAYMENT_COMMNETS." where payment_month_year = '".date('my', strtotime($select_check_order_product_payment_history['payment_date']))."' and agency_id = '".$select_check_order_product_payment_history["payment_agency_id"]."'");
									?>
									<td class="dataTableContent" valign="top">
									<?php 
										if(tep_db_num_rows($check_comment_exist)>0){
											$row_payment_comment = tep_db_fetch_array($check_comment_exist);
											if($row_payment_comment['payment_method']!=''){
												echo highlightWords($row_payment_comment['payment_method'], $keywords);
											}else{
												echo highlightWords($select_check_order_product_payment_history['payment_method'], $keywords);
											}
										}else{
											echo highlightWords($select_check_order_product_payment_history['payment_method'], $keywords);
										}
									?>
									</td>
									<td class="dataTableContent" valign="top"><div id="gross_payment_total_<?php echo $order_row_cnts; ?>"></div></td>
									<td class="dataTableContent" valign="top">
									<?php
									
									if(tep_db_num_rows($check_comment_exist)>0){
										//$row_payment_comment = tep_db_fetch_array($check_comment_exist);
										echo '<span class="col_h"><b>Current Status</b></span>';
										echo '<br />';
										echo '<b>Last Invoice Amount: $'.$row_payment_comment['total_invoice_amount'].'</b>';
										echo '<br />';
										echo '<b>Last Updated Payment Status: '.$row_payment_comment['payment_status'].'</b>';											
										echo '<br /><br /><br />';									
										echo '<span class="col_h"><b>Update History</b></span>';
										echo '<br />';
										echo highlightWords(stripslashes($row_payment_comment['payment_comment']), $keywords);
										echo '<br />';
									}
									?>
									<a href="javascript:popupImageWindow('<?php echo tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, 'action=show_popup_edit_comment&agency_id='.$select_check_order_product_payment_history["payment_agency_id"].'&payment_method='.urlencode($select_check_order_product_payment_history['payment_method']).'&payment_month_year='.date("my", strtotime($select_check_order_product_payment_history["payment_date"])) ); ?>');"><?php echo tep_image(DIR_WS_ICONS.'edit.gif','Add/Edit Comment'); ?></a></td>
									<td class="dataTableContent" valign="top"><div id="main_updated_by_name_<?php echo $order_row_cnts; ?>">
									<?php
									if(tep_db_num_rows($check_comment_exist)>0 && $row_payment_comment['updated_by']>0){
										echo tep_get_admin_customer_name($row_payment_comment['updated_by']);
									}
									?>
									</div></td>
									<td class="dataTableContent" valign="top"><div id="main_last_updated_date_<?php echo $order_row_cnts; ?>">
									<?php
									if(tep_db_num_rows($check_comment_exist)>0 && $row_payment_comment['last_update_date']>0){
										echo tep_datetime_short($row_payment_comment['last_update_date']);
									}
									?>
									</div></td>
								</tr>
								<tr>
									<td colspan="7">
										<div id="sub_div_order_paid_payment_<?php echo $order_row_cnts;?>" style="display:none ">
										<table width="100%" align="center" cellspacing="0" cellpadding="1" style="border:2px solid #808080;">	
											<?php
											if(isset($HTTP_GET_VARS['payment_id']) && $HTTP_GET_VARS['payment_id'] != ''){
											
											if(isset($HTTP_GET_VARS['item_id']) && $HTTP_GET_VARS['item_id'] != ''){
											$extra_where_items_id =  " or CONCAT(',',opph.orders_products_id,',') REGEXP ',".$HTTP_GET_VARS['item_id'].",' ";
											}
												$order_product_payment_history_sql = "select ta.agency_name, opph.payment_agency_id, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, opph.payment_date, p.provider_tour_code, op.payment_paid, opph.is_payment_cancelled from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." as p, " . TABLE_ORDERS_PRODUCTS . " as op  where  cast(opph.orders_products_id as signed) = op.orders_products_id and opph.payment_agency_id=ta.agency_id and op.products_id=p.products_id and (opph.ord_prod_payment_id = '".$HTTP_GET_VARS['payment_id']."' ".$extra_where_items_id." ) and ta.agency_id = '".$select_check_order_product_payment_history['payment_agency_id']."' and date_format(opph.payment_date, '%m') = '".$select_check_order_product_payment_history['month']."' and date_format(opph.payment_date, '%Y') = '".$select_check_order_product_payment_history['year']."' order by opph.last_update_date desc";
											}else{
												$order_product_payment_history_sql = "select ta.agency_name, opph.payment_agency_id, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, opph.payment_date, p.provider_tour_code, op.payment_paid, opph.is_payment_cancelled from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." as p, " . TABLE_ORDERS_PRODUCTS . " as op  where  cast(opph.orders_products_id as signed) = op.orders_products_id and opph.payment_agency_id=ta.agency_id and op.products_id=p.products_id ".$where." and ta.agency_id = '".$select_check_order_product_payment_history['payment_agency_id']."' and date_format(opph.payment_date, '%m') = '".$select_check_order_product_payment_history['month']."' and date_format(opph.payment_date, '%Y') = '".$select_check_order_product_payment_history['year']."' order by opph.last_update_date desc";
											}
			
											$order_product_payment_history_row = tep_db_query($order_product_payment_history_sql);
											//$order_row_cnts = 0;
											$sub_count = 0;
											$running_total_payment_price = 0;
											$search_found_to_sub_ids = 0;
											while($order_product_payment_history = tep_db_fetch_array($order_product_payment_history_row)){
											$running_total_payment_price += $order_product_payment_history['payment_amount'];
											if($sub_count == 0){
											?>
											<script type="text/javascript">												
												<?php
												if(tep_db_num_rows($check_comment_exist)==0 || $row_payment_comment['updated_by']<=0){
												?>
												document.getElementById("main_updated_by_name_<?php echo $order_row_cnts;?>").innerHTML="<?php echo tep_get_admin_customer_name($order_product_payment_history['updated_by']);?>";
												<?php
												}
												?>
												<?php
												if(tep_db_num_rows($check_comment_exist)==0 || $row_payment_comment['last_update_date']<=0){
												?>
												document.getElementById("main_last_updated_date_<?php echo $order_row_cnts;?>").innerHTML="<?php echo tep_datetime_short($order_product_payment_history['last_update_date']);?>";
												<?php
												}
												?>
											</script>
											<?php
											}
											$sub_count++;
											
											if($order_row_cnts  % 2 == 0){
												$select_subrow_color = 'bgcolor="#EEEEEE"';
											}else{
												$select_subrow_color = 'bgcolor="#E3F0FD"';
											}
											if(tep_not_null($keywords)){
												if (preg_match("/(".$keywords.")/i", $order_product_payment_history['payment_method']) || preg_match("/(".$keywords.")/i", $order_product_payment_history['payment_amount']) || preg_match("/(".$keywords.")/i", $order_product_payment_history['payment_comment'])){
													$search_found_to_sub_ids = 1;
												} 
											}
											?>
											<tr <?php echo $select_subrow_color; ?>>	
												<td class="dataTableContent" valign="top" width="10%">
												<a style="CURSOR: pointer" onclick="javascript:toggel_div('div_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>','div_comment_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>')"><b><?php echo $order_product_payment_history['payment_agency_id'].'TFF'.date('mdy', strtotime($order_product_payment_history['payment_date']));?></b></a>
												</td>
												<td class="dataTableContent" valign="top" width="15%">
												<?php echo $order_product_payment_history['agency_name'];?>
												</td>
												<td class="dataTableContent" valign="top" width="15%"><?php echo highlightWords($order_product_payment_history['payment_method'], $keywords);?></td>
												<td class="dataTableContent" valign="top" width="15%">$<?php echo highlightWords($order_product_payment_history['payment_amount'], $keywords);?></td>
												<td class="dataTableContent" valign="top" width="25%">
												<?php
												echo '<b>';
												if($order_product_payment_history['is_payment_cancelled']==1){
													echo '<font color="#FF0000">Payment Cancelled</font><br />';
												}
													if($order_product_payment_history['payment_paid']==0){
														echo 'Unpaid';
													}else if($order_product_payment_history['payment_paid']==1){
														echo 'Paid';
													}else if($order_product_payment_history['payment_paid']==2){
														echo 'Final Payment Pending';
													}else if($order_product_payment_history['payment_paid']==3){
														echo 'Partially Paid';
													}													
												echo '</b><br>'.highlightWords($order_product_payment_history['payment_comment'], $keywords);
												?>
												<div id="responsediv<?php echo $order_product_payment_history['ord_prod_payment_id'];?>">	</div>
												<a style="CURSOR: pointer" onclick="javascript:toggel_div('div_comment_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>','div_comment_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>')"> <?php echo tep_image(DIR_WS_IMAGES.'icon_add_comment.gif','Add Comment'); ?></a>
												
												<div id="div_comment_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>" style="display:none">
													<?php echo tep_draw_form('frmaddcomment'.$order_product_payment_history['ord_prod_payment_id'], FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW,'', '','id="frmaddcomment'.$order_product_payment_history['ord_prod_payment_id'].'"'); ?>
														<table align="left" cellpadding="2" style="border:1px solid #006666" cellspacing="0">
														<tr style="background-color:#006666; color:#FFFFFF;">
															<td align="left"><b>Add comment here: </b><br>
															 <textarea name="add_payment_comment" id="add_payment_comment" rows="2" cols="70"></textarea>
															 <br><input type="button" name="btn_add_comment" value="Add Comment" onClick="ajax_add_comment('<?php echo $order_product_payment_history['ord_prod_payment_id'];?>',document.frmaddcomment<?php echo $order_product_payment_history['ord_prod_payment_id'] ?>.add_payment_comment.value);" /></td>
														</tr>
														</table>
													<?php echo '</form>'; ?>
												</div>									
												</td>
												<td class="dataTableContent" valign="top" width="10%"><?php echo tep_get_admin_customer_name($order_product_payment_history['updated_by']);?></td>
												<td class="dataTableContent" valign="top" width="10%">									
												<a style="CURSOR: pointer" onclick="javascript:toggel_div('div_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>','div_comment_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>')"><b><?php echo tep_datetime_short($order_product_payment_history['last_update_date']);?></b></a><br />
												<?php
												if($order_product_payment_history['is_payment_cancelled']==0){
												/*
												?>
												<a href="javascript: void(0);" onClick="javascript: return confirm_cancel(); window.location.replace('<?php echo tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, tep_get_all_get_params(array('action','ord_prod_payment_id')).'&action=cancel_payment&ord_prod_payment_id='.$order_product_payment_history['ord_prod_payment_id']); ?>');">Cancel Payment</a>
												<?php
												*/
												?>
												<input type="button" value="Cancel Payment" onClick="return confirm_cancel('<?php echo tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, tep_get_all_get_params(array('action','ord_prod_payment_id')).'&action=cancel_payment&ord_prod_payment_id='.$order_product_payment_history['ord_prod_payment_id']); ?>');" />
												<?php
												}
												?>
												</td>
												</tr>
												
												<tr bgcolor="#DDEEEB" id="div_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>" style="DISPLAY: none">	
												<td class="dataTableContent" colspan="7" >
														<table width="100%" cellpadding="2" style="border:1px solid #006666" cellspacing="2">
														<tr style="background-color:#006666; color:#FFFFFF;">
														<td><strong>Order#</strong>
														</td>
														<td width="50%"><strong>Tours</strong>
														</td>
														<td><strong>Departure Date</strong></td>											
														<td  nowrap="nowrap"><strong>Paid Amount [<span style="white-space:nowrap;" id="div_order_paid_payment_total_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>"></span>]</strong>
														</td>
														<td><strong>GP</strong></td>
														<td><strong>Invoice Number</strong></td>
														</tr>
														<?php 
														 $tours_paid_items_array = explode(',',$order_product_payment_history['orders_products_id']);
														$total_paid_amt_history = 0;
														$found_search_row = 0;
														for($pi=0; $pi<sizeof($tours_paid_items_array); $pi++){
																															
														$ord_prods_model_name_array = tep_get_paid_payment_history_invoice_amt_and_tour_detail($tours_paid_items_array[$pi], $order_product_payment_history['ord_prod_payment_id']);
														$ord_prods_prov_tourcode_array = tep_get_products_prov_tourcode_from_orders_products($tours_paid_items_array[$pi]);
														
														if($tours_paid_items_array[$pi] != ''){			
														
														
														if($tours_paid_items_array[$pi] == $HTTP_GET_VARS['item_id'] || $ord_prods_model_name_array['orders_id'] == $HTTP_GET_VARS['orders_id'] ){
															$class_search_bgcolor = ' bgcolor="#FAC6BC" ';	
															$found_search_row = 1;										
														}else{
														
															$class_search_bgcolor = '';
														}			
														
														$select_check_color_ord_prod_pay_history_sql = "select ord_prod_payment_id, op.orders_products_id from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_ORDERS_PRODUCTS." op where opph.orders_products_id=op.orders_products_id and ( opph.orders_products_id REGEXP '[,]".$tours_paid_items_array[$pi]."' OR opph.orders_products_id REGEXP '".$tours_paid_items_array[$pi]."[,]' OR opph.orders_products_id ='".$tours_paid_items_array[$pi]."')  order by opph.ord_prod_payment_id desc";
														$select_check_color_ord_prod_pay_history_row = tep_db_query($select_check_color_ord_prod_pay_history_sql);									
												
														if(tep_db_num_rows($select_check_color_ord_prod_pay_history_row) > 1){
															$paid_twice_calss = ' bgcolor="#FAC6BC" ';
															$class_search_bgcolor = ' bgcolor="#E3B3D8" ';	
														}else{
															$paid_twice_calss = '';
														}	
																									
														?>
															<tr   <?php echo $class_search_bgcolor;?>>
															<td <?php echo $paid_twice_calss;?>><?php echo '<a href="edit_orders.php?action=edit&oID='.$ord_prods_model_name_array['orders_id'].'" target="_blank"><strong>'.$ord_prods_model_name_array['orders_id'].'</strong></a> '?>
															</td>
															<td><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $ord_prods_model_name_array['products_id']) . '">'.$ord_prods_model_name_array['products_name'].'</a>'; ?> <?php echo '[<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($ord_prods_model_name_array['products_id']) . '&pID=' . $ord_prods_model_name_array['products_id'].'&action=new_product') . '">'.$ord_prods_model_name_array['products_model'].'</a>]'.'[<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($ord_prods_model_name_array['products_id']) . '&pID=' . $ord_prods_model_name_array['products_id'].'&action=new_product') . '">'.$ord_prods_prov_tourcode_array['provider_tour_code'].'</a>]';?>
															</td>		
															<td><?php echo tep_date_short($ord_prods_model_name_array['products_departure_date']); ?></td>													
															<td>$<?php echo $ord_prods_model_name_array['final_invoice_amount'];
															$total_paid_amt_history = $total_paid_amt_history + $ord_prods_model_name_array['final_invoice_amount'];
															?>
															</td>
															<td><?php 												
															$gp_cal ='';
															
															//$gp_cal = 1 - ($ord_prods_model_name_array['final_invoice_amount']/$ord_prods_model_name_array['final_price']);
															if($ord_prods_model_name_array['final_price'] != 0){
															
																if($ord_prods_model_name_array['total_final_invoice_amount'] > 0){
																$gp_cal = 1 - ($ord_prods_model_name_array['total_final_invoice_amount']/$ord_prods_model_name_array['final_price']);
																}else{
																$gp_cal = 1 - ($ord_prods_model_name_array['final_invoice_amount']/$ord_prods_model_name_array['final_price']);
																}
															}
															
															echo number_format($gp_cal,2,'.','');  		
															
															?></td>
															<td><?php echo $ord_prods_model_name_array['invoice_number'];?></td>
															</tr>
														
															
															<?php
															
															
															
															
															
															}//end of check if loop
															
															} //end of for each
															?>
															
															<script language="javascript">
															document.getElementById("div_order_paid_payment_total_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>").innerHTML="$<?php echo number_format($total_paid_amt_history,2,'.','');?>";
															
															
															<?php 
															if($found_search_row == 1){ ?>
															document.getElementById("div_order_paid_payment_<?php echo $order_product_payment_history['ord_prod_payment_id'];?>").style.display = "";
															document.getElementById('sub_div_order_paid_payment_<?php echo $order_row_cnts;?>').style.display = '';
															<?php
															}												
															?>
															
															</script>
															</table>
												</td>									
												</tr>
												<tr><td height="2"></td></tr>
											<?php
											}
											if($search_found_to_sub_ids == 1){
											?>
												<script type="text/javascript">
													document.getElementById('sub_div_order_paid_payment_<?php echo $order_row_cnts;?>').style.display = '';
												</script>
											<?php
											}
											$running_gross_total_payment_price += $running_total_payment_price;
											?>
						<script type="text/javascript">
							document.getElementById("gross_payment_total_<?php echo $order_row_cnts;?>").innerHTML="$<?php echo highlightWords(number_format($running_total_payment_price,2,'.',''), $keywords);?>";
						</script>
										</table>
										</div>
									</td>
								</tr>
						<?php } ?>
						  <?php if(!isset($HTTP_GET_VARS['payment_id'])){ ?>
						 <tr class="dataTableHeadingRow">						 
						  <td colspan="3"></td>			
						  <td class="dataTableHeadingContent" nowrap="nowrap">$<?php echo number_format($running_gross_total_payment_price,2);?></td>		
						  <td colspan="4"></td>				
						  </tr>				
						    <tr>
								<td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
								  <tr>
									<td class="smallText" valign="top"><?php echo $select_check_order_product_payment_history_split->display_count1($select_check_order_product_payment_history_numrows, 15, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
									<td class="smallText" align="right"><?php echo $select_check_order_product_payment_history_split->display_links1($select_check_order_product_payment_history_numrows, 15, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
								  </tr>
								</table></td>
							</tr>
							<?php }else{
							?>
							 <tr>
								<td colspan="6">
								<a href="<?php echo tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW);?>"><b>See All Paid Tours</b></a>
								</td>
							</tr>
								
							<?php
							} ?>
						</table>
					

  			</td>
  		</tr>
		</table>
	<script language="JavaScript" type="text/javascript"> 
				function createRequestObject(){
				var request_;
				var browser = navigator.appName;
				if(browser == "Microsoft Internet Explorer"){
				 request_ = new ActiveXObject("Microsoft.XMLHTTP");
				}else{
				 request_ = new XMLHttpRequest();
				}
			return request_;
			}
			//var http = createRequestObject();
			var http1 = createRequestObject();
		
				function ajax_add_comment(ord_prod_payment_id,add_comment)
				{
					try{
						add_comment = add_comment.replace(/\+/g,"|plushplush|");
						add_comment = add_comment.replace(/\&/g,"|ampamp|");
						add_comment = add_comment.replace(/\#/g,"|hashhash|");
						add_comment = add_comment.replace(/\n/g,"|newline|");
						
							http1.open('get', 'stats_paid_payment_order_history.php?ord_prod_payment_id='+ord_prod_payment_id+'&comment='+add_comment+'&action_addcomment=true');
							http1.onreadystatechange = hendleInfo_add_comment;
							http1.send(null);
					}catch(e){ 
						//alert(e);
					}
				}
				
				function hendleInfo_add_comment()
					{
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 var responsecomment = response1.split("|!!!!!|");
						 document.getElementById("responsediv"+responsecomment[1]).innerHTML = document.getElementById("responsediv"+responsecomment[1]).innerHTML + responsecomment[0];
						 
						}
					}
				function confirm_cancel(url){
					if(confirm('Are you sure you want to cancel this payement?')){					
					window.location = url;
					return true;
					}else{
					return false;
					}
				}	
					
						
				
</script>		
	</body>
</html>
  		