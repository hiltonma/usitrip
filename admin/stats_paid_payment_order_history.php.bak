<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_paid_payment_order_history');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
if($language == "schinese") {
	
	header("Content-type: text/html; charset=gb2312");	

}
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
			echo $br.stripslashes($HTTP_GET_VARS['comment']).'<br>- '.tep_get_admin_customer_name($login_id) . '&nbsp;('.tep_datetime_short(date("Y-m-d h:i:s")).')'.'|!!!!!|'.$HTTP_GET_VARS['ord_prod_payment_id'];
	}
	exit;
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
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<? require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_paid_payment_order_history');
$list = $listrs->showRemark();
?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
  		<tr>

  			<td valign="top">
  				<div class="pageHeading"><?= HEADING_TITLE ?></div><br />			
				<?php if(!isset($HTTP_GET_VARS['payment_id'])){ ?>				
				<?php
				$where = ' and 1=1 ';				
				
				  if ((isset($HTTP_GET_VARS['modify_start_date']) && tep_not_null($HTTP_GET_VARS['modify_start_date'])) && (isset($HTTP_GET_VARS['modify_end_date']) && tep_not_null($HTTP_GET_VARS['modify_end_date'])) ) 
						  {
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['modify_start_date']);
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['modify_end_date']);						  
						  $where .= " and  opph.last_update_date >= '" . $make_start_date . "' and opph.last_update_date <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['modify_start_date']) && tep_not_null($HTTP_GET_VARS['modify_start_date'])) && (!isset($HTTP_GET_VARS['modify_end_date']) or !tep_not_null($HTTP_GET_VARS['modify_end_date'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['modify_start_date']) . " 00:00:00";
						  
						  $where .= " and  opph.last_update_date >= '" . $make_start_date . "' ";
						  
						  $make_start_date_end_limit = tep_get_date_db($HTTP_GET_VARS['modify_start_date']) . " 24:00:00";
						 
						  $where .= " and  opph.last_update_date <= '" . $make_start_date_end_limit . "' ";
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['modify_start_date']) or !tep_not_null($HTTP_GET_VARS['modify_start_date'])) && (isset($HTTP_GET_VARS['modify_end_date']) && tep_not_null($HTTP_GET_VARS['modify_end_date'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['modify_end_date']) . " 00:00:00";
						  
							$where .= " and  opph.last_update_date <= '" . $make_end_date . "' ";
						  }
				
				 if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
				 $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
				 $where .= " and (opph.payment_method like '%" . $keywords . "%' or opph.payment_comment like '%" . $keywords . "%' or opph.payment_amount like '".$keywords."%' or opph.ord_prod_payment_id = '".str_replace('TFF','',$keywords)."') ";
				
				}
				
				 if (isset($HTTP_GET_VARS['provider']) && tep_not_null($HTTP_GET_VARS['provider'])) {
				 
				  $where .= " and p.agency_id='".$HTTP_GET_VARS['provider']."'";
				 
				 }
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
				
				
				<?php echo tep_draw_form('search', FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY, '', 'get'); ?>
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
										<?php echo tep_draw_input_field('modify_end_date', tep_get_date_disp($_GET['modify_end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
											<script type="text/javascript">//dateAvailable_modify_end.writeControl(); dateAvailable_modify_end.dateFormat="MM/dd/yyyy";</script>
										</td>
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
							<td class="dataTableHeadingContent" >							
								Payment ID
							</td>	
							<td class="dataTableHeadingContent" >							
								Provider
							</td>
							<td class="dataTableHeadingContent">Payment Method</td>
							<td class="dataTableHeadingContent">Payment Amount</td>
							<td class="dataTableHeadingContent">Comment</td>
							<td class="dataTableHeadingContent">Updated by</td>
							<td class="dataTableHeadingContent">Last Modify</td>
						</tr>

				<?
				 			$restrict_old_data_sql = '';
							$restrict_old_data_sql = " and opph.payment_date <= '2010-02-01' ";
							
								if(isset($HTTP_GET_VARS['payment_id']) && $HTTP_GET_VARS['payment_id'] != ''){
								
								if(isset($HTTP_GET_VARS['item_id']) && $HTTP_GET_VARS['item_id'] != ''){
								
								$extra_where_items_id =  " or CONCAT(',',opph.orders_products_id,',') REGEXP ',".$HTTP_GET_VARS['item_id'].",' ";
								
								}
								$select_check_order_product_payment_history_sql = "select ta.agency_name, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, p.provider_tour_code  from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." as p, " . TABLE_ORDERS_PRODUCTS . " as op  where  cast(opph.orders_products_id as signed) = op.orders_products_id and p.agency_id=ta.agency_id and op.products_id=p.products_id and (opph.ord_prod_payment_id = '".$HTTP_GET_VARS['payment_id']."' ".$extra_where_items_id." ) ".$restrict_old_data_sql." order by opph.ord_prod_payment_id desc";
								}else{
									//$select_check_order_product_payment_history_sql = "select opph.ord_prod_payment_id, opph.orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph ".$where."  order by opph.ord_prod_payment_id desc";
									$select_check_order_product_payment_history_sql = "select ta.agency_name, opph.ord_prod_payment_id, opph.orders_products_id, cast(opph.orders_products_id as signed) as int_orders_products_id, opph.payment_amount, opph.payment_method, opph.payment_comment, opph.updated_by, opph.last_update_date, p.provider_tour_code from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." as p, " . TABLE_ORDERS_PRODUCTS . " as op  where  cast(opph.orders_products_id as signed) = op.orders_products_id and p.agency_id=ta.agency_id and op.products_id=p.products_id ".$where." ".$restrict_old_data_sql." order by opph.ord_prod_payment_id desc";
								}
								
								
								
								$select_check_order_product_payment_history_split = new splitPageResults($HTTP_GET_VARS['page'], 15, $select_check_order_product_payment_history_sql, $select_check_order_product_payment_history_numrows); //MAX_DISPLAY_SEARCH_RESULTS_ADMIN

								$select_check_order_product_payment_history_row = tep_db_query($select_check_order_product_payment_history_sql);
								$order_row_cnts = 0;
								$running_total_payment_price = 0;
								while($select_check_order_product_payment_history = tep_db_fetch_array($select_check_order_product_payment_history_row)){
								
								if($order_row_cnts  % 2 == 0){
									$select_row_color = 'bgcolor="#D2E9FF"';
								}else{
									$select_row_color = 'bgcolor="#eadfc6"';
								}
								$order_row_cnts++;
								$running_total_payment_price += $select_check_order_product_payment_history['payment_amount'];
								
											
								?>
	  
	  								<tr <?php echo $select_row_color;?> >	
									<td class="dataTableContent" valign="top">
									<a style="CURSOR: pointer" onclick="javascript:toggel_div('div_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>','div_comment_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>')"><b>TFF<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?></b></a>
									</td>
									<td class="dataTableContent" valign="top">
									<?php echo $select_check_order_product_payment_history['agency_name'];?>
									</td>
								    <td class="dataTableContent" valign="top"><?php echo $select_check_order_product_payment_history['payment_method'];?></td>
									<td class="dataTableContent" valign="top">$<?php echo $select_check_order_product_payment_history['payment_amount'];?></td>
									<td class="dataTableContent" valign="top"><?php echo $select_check_order_product_payment_history['payment_comment'];?>
									<div id="responsediv<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>">	</div>
									<a style="CURSOR: pointer" onclick="javascript:toggel_div('div_comment_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>','div_comment_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>')"> <?php echo tep_image(DIR_WS_IMAGES.'icon_add_comment.gif',ALTER_TEXT_ADD_COMMENT); ?></a>
									
									<div id="div_comment_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>" style="display:none">
										<?php echo tep_draw_form('frmaddcomment'.$select_check_order_product_payment_history['ord_prod_payment_id'], FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY,'', '','id="frmaddcomment'.$select_check_order_product_payment_history['ord_prod_payment_id'].'"'); ?>
											<table align="left" cellpadding="2" style="border:1px solid #006666" cellspacing="0">
											<tr style="background-color:#006666; color:#FFFFFF;">
												<td align="left"><b><?php echo TEXT_ADD_COMMENT; ?></b><br>
												 <textarea name="add_payment_comment" id="add_payment_comment" rows="2" cols="70"></textarea>
												 <br><input type="button" name="btn_add_comment" value="Add Comment" onClick="ajax_add_comment('<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>',document.frmaddcomment<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'] ?>.add_payment_comment.value);" /></td>
											</tr>
											</table>
										<?php echo '</form>'; ?>
									</div>
									</td>
									<td class="dataTableContent" valign="top"><?php echo tep_get_admin_customer_name($select_check_order_product_payment_history['updated_by']);?></td>
									<td class="dataTableContent" valign="top">									
									<a style="CURSOR: pointer" onclick="javascript:toggel_div('div_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>','div_comment_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>')"><b><?php echo tep_datetime_short($select_check_order_product_payment_history['last_update_date']);?></b></a>
									</td>
									</tr>
									<tr bgcolor="#DDEEEB" id="div_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>" style="DISPLAY: none">	
									<td class="dataTableContent" colspan="7" >
											<table width="100%" cellpadding="2" style="border:1px solid #006666" cellspacing="2">
											<tr style="background-color:#006666; color:#FFFFFF;">
											<td><strong>Order#</strong>
											</td>
											<td width="50%"><strong>Tours</strong>
											</td>			
											<td><strong>Departure Date</strong></td>								
											<td  nowrap="nowrap"><strong>Paid Amount [<span style="white-space:nowrap;" id="div_order_paid_payment_total_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>"></span>]</strong>
											</td>
											<td><strong>GP</strong></td>
											<td><strong>Invoice Number</strong></td>
											</tr>
											<?php 
											$found_search_row = 0;
											 $tours_paid_items_array = explode(',',$select_check_order_product_payment_history['orders_products_id']);
											$total_paid_amt_history = 0;
											for($pi=0; $pi<sizeof($tours_paid_items_array); $pi++){
																												
											$ord_prods_model_name_array = tep_get_paid_payment_history_invoice_amt_and_tour_detail($tours_paid_items_array[$pi], $select_check_order_product_payment_history['ord_prod_payment_id']);
											$ord_prods_prov_tourcode_array = tep_get_products_prov_tourcode_from_orders_products($tours_paid_items_array[$pi]);
											
											if($tours_paid_items_array[$pi] != ''){			
											
											
											if($tours_paid_items_array[$pi] == $HTTP_GET_VARS['item_id'] || $ord_prods_model_name_array['orders_id'] == $HTTP_GET_VARS['orders_id'] ){
												$class_search_bgcolor = ' bgcolor="#FAC6BC" ';		
												$found_search_row = 1;											
											}else{
												$class_search_bgcolor = ' ';
											}	
											
											$select_check_color_ord_prod_pay_history_sql = "select ord_prod_payment_id, op.orders_products_id from " .TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY. " opph, ".TABLE_ORDERS_PRODUCTS." op where opph.orders_products_id=op.orders_products_id and ( CONCAT(',',opph.orders_products_id,',') REGEXP ',".$tours_paid_items_array[$pi].",')  order by opph.ord_prod_payment_id desc";
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
												<td width="50%"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $ord_prods_model_name_array['products_id']) . '">'.$ord_prods_model_name_array['products_name'].'</a>'; ?> <?php echo '[<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($ord_prods_model_name_array['products_id']) . '&pID=' . $ord_prods_model_name_array['products_id'].'&action=new_product') . '">'.$ord_prods_model_name_array['products_model'].'</a>]'.'[<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($ord_prods_model_name_array['products_id']) . '&pID=' . $ord_prods_model_name_array['products_id'].'&action=new_product') . '">'.$ord_prods_prov_tourcode_array['provider_tour_code'].'</a>]';?>
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
												//$gp_cal = 1 - ($ord_prods_model_name_array['final_invoice_amount']/$ord_prods_model_name_array['final_price']);
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
												document.getElementById("div_order_paid_payment_total_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>").innerHTML="$<?php echo number_format($total_paid_amt_history,2,'.','');?>";
												
												<?php 
												if($found_search_row == 1){ ?>
												document.getElementById("div_order_paid_payment_<?php echo $select_check_order_product_payment_history['ord_prod_payment_id'];?>").style.display = "";
												<?php
												}												
												?>
												</script>
												</table>
									</td>									
									</tr>						
	  
						
						
						<?php } ?>
						  <?php if(!isset($HTTP_GET_VARS['payment_id'])){ ?>
						 <tr class="dataTableHeadingRow">						 
						  <td colspan="3"></td>			
						  <td class="dataTableHeadingContent" nowrap="nowrap">$<?php echo number_format($running_total_payment_price,2);?></td>		
						  <td colspan="4"></td>				
						  </tr>				
						    <tr>
								<td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
								  <tr>
									<td class="smallText" valign="top"><?php echo $select_check_order_product_payment_history_split->display_count($select_check_order_product_payment_history_numrows, 15, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
									<td class="smallText" align="right"><?php echo $select_check_order_product_payment_history_split->display_links($select_check_order_product_payment_history_numrows, 15, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
								  </tr>
								</table></td>
							</tr>
							<?php }else{
							?>
							 <tr>
								<td colspan="6">
								<a href="<?php echo tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY);?>"><b>See All Paid Tours</b></a>
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
					
</script>
	</body>
</html>
  		