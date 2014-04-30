<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  if($HTTP_GET_VARS['update_fax']=='true' || $HTTP_POST_VARS['update_fax']=='true'){
  	$show_extra_update_fields = 'true';
  }
  
if($_POST['output'])
{
	include("html_to_doc.inc.php");	
	$htmltodoc= new HTML_TO_DOC();	
	$htmltodoc->createDoc(tep_db_prepare_input($_POST['output']),$_POST['oID'],true);
}
$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
$customer_number = tep_db_fetch_array($customer_number_query);
/*
  if ($customer_number['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
*/
  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];

//  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INVOICE);

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
	$i = $_GET['i'];

$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_products_id = '".$order->products[$i]['orders_products_id']."'"); //orders_id = '" . (int)$oID . "' and products_id=".$_GET['products_id']." 

$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
//tom added 日本团的自带磁带提示信息
//JPTK82-1407，JPTK82-1413， JPTK82-1414， JPTK82-1414， JPTK82-1414， JPTK82-1414，JPTK82-1413，JPTK82-1413
$japan_array = '';
$japan_array[0]='JPTK82-1407';
$japan_array[1]='JPTK82-1413';
$japan_array[2]='JPTK82-1414';
$japan_array[3]='JPTK82-1414';
$japan_array[4]='JPTK82-1414';
$japan_array[5]='JPTK82-1414';
$japan_array[6]='JPTK82-1413';
$japan_array[7]='JPTK82-1413';
$japan_array[8]='JPTK82-1402';
$japan_array[9]='JPTK82-1404';
?>
<?php 

function RTESafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = trim($strText);
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	//$tmpString = str_replace("'", "&#39;", $tmpString);
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), "", $tmpString);
	$tmpString = str_replace(chr(13), "\\n", $tmpString);
	return $tmpString;
}
?>
<?php if($HTTP_GET_VARS['action'] == 'fax_preview') {?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo STORE_NAME; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
	<tr>
		<td align="center" class="main">	
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td valign="top" align="left" class="main">
						<script language="JavaScript">
						  if (window.print) {
							document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_CATALOG_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_CATALOG_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_CATALOG_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
						  }
						  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
				        </script>		
					</td>
	        		<td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?php echo DIR_WS_CATALOG_IMAGES;?>close_window.jpg' border=0></a></p></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<?php	ob_start();?>
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr><td align="center"> <strong><font size="+2">走四方网/<?php echo STORE_NAME; ?></font></strong> </td></tr>
				<tr><td>
				133B Garvey Avenue, Monterey Park, CA 
				<br />
				Email: <?php echo STORE_OWNER_EMAIL_ADDRESS;?>
				</td></tr>
				<tr><td><hr size="3" color="#000000"></td></tr>
				<tr><td><p align="center"><strong>Facsimile Transmittal Cover Page </strong></p></td></tr>
				<tr><td><hr size="3" color="#000000"></td></tr>
			  	<tr><td>
			  			<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr><td colspan="2" height="1"></td></tr>						  
							<tr><td colspan="2"><strong>Date:</strong> <?php echo $_POST['order_date'];?> </td></tr>
							<tr><td colspan="2"><strong> Re:</strong> <strong><?php echo $_POST['reservation_id'];?></strong></td></tr>
							<tr><td colspan="2"><strong> Total Number of Pages:</strong> <?php echo $_POST['total_num_page'];?></td></tr>
							<tr><td colspan="2"><hr size="2"></td></tr>
							<tr>
								<td width="50%"> <strong>To:</strong> <?php echo $_POST['to_name'];?> </td>
								<td width="50%"><strong>From:</strong> <?php echo $_POST['from_name'];?> </td>
							</tr>
							<tr><td colspan="2"><hr size="2"></td></tr>
							<tr>
								<td><strong>Company: </strong><?php echo $_POST['to_company'];?>  </td>
								<td><strong>Company: </strong><?php echo $_POST['from_company'];?>  </td>
							</tr>
							<tr><td colspan="2"><hr size="2"></td></tr>
							<tr>
								<td> <strong>Telephone#: </strong><?php echo $_POST['to_telephone'];?> </td>
								<td> <strong>Telephone#: </strong> <?php echo $_POST['from_telephone'];?> </td>
							</tr>
							<tr><td colspan="2"><hr size="2"></td></tr>					
							<tr>
								<td> <strong>Fax#: </strong><?php echo $_POST['to_fax'];?>  </td>
								<td> <strong>Fax#: </strong><?php echo $_POST['from_fax'];?>   </td>
							 </tr>							
							<tr><td colspan="2"><hr size="2"></td></tr>								 
							<tr><td colspan="2">
									<?php 
									if($show_extra_update_fields == 'true'){ 									   
									   if(isset($HTTP_POST_VARS['contect_for_chk'])){
											foreach($HTTP_POST_VARS['contect_for_chk'] as $key => $val){
												if($val == 'replybyfax'){$show_check_replybyfax = ' checked ';}													
												if( $val == 'urgent'){$show_check_urgent = ' checked ';}
												if($val == 'foryourinfo'){$show_check_foryourinfo = ' checked ';}
											}
										}
									?>				
									<table width="100%"  border="0" cellspacing="0" cellpadding="0">
									  <tr>
									  	<td><input type="checkbox" name="contect_for_chk[]" <?php echo $show_check_replybyfax;?> value="replybyfax"> <strong>Please Reply by Fax</strong></td>
										<td><input type="checkbox" name="contect_for_chk[]" <?php echo $show_check_urgent;?> value="urgent"> <strong>Urgent	</strong>	</td>
										<td><input type="checkbox" name="contect_for_chk[]" <?php echo $show_check_foryourinfo;?> value="foryourinfo"> <strong>For Your Information</strong></td>
									  </tr>
									</table>
									<?php 
									}
									else
									{ 				
									   if(isset($HTTP_POST_VARS['contect_for_chk'])){
												foreach($HTTP_POST_VARS['contect_for_chk'] as $key => $val){
													if($val == 'confirmbyfax'){$show_check_confirmbyfax = ' checked ';}													
													if( $val == 'confirmbyemail'){$show_check_confirmbyemail = ' checked ';}
													if($val == 'urgentreply'){$show_check_urgentreply = ' checked ';}
												}												
										}
									?>				
									<table width="100%"  border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td><input type="checkbox" name="contect_for_chk[]" <?php echo $show_check_confirmbyfax;?> value="confirmbyfax"> <strong>Confirm by Fax</strong></td>
										 <td><input type="checkbox" name="contect_for_chk[]" <?php echo $show_check_confirmbyemail;?> value="confirmbyemail"> <strong>Confirm by Email</strong></td>
										 <td><input type="checkbox" name="contect_for_chk[]" <?php echo $show_check_urgentreply;?> value="urgentreply"> <strong>Urgent Reply</strong> 	</td>
									  </tr>
									</table>
									<?php } ?>
									</td>
						 </tr>
						</table>
						</td>
			  	</tr>
			  	<?php if($show_extra_update_fields == 'true'){ ?>
				<tr><td><hr size="3" color="#000000"></td></tr>
				<?php } ?>
				<tr>
					<td>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						<?php if($show_extra_update_fields == 'true'){ ?>
							<tr><td colspan="2"><strong>This is an existing booking. Do NOT double book!</strong></td></tr>
							<tr><td colspan="2"><strong>Reference#: </strong><?php echo stripslashes2($_POST['reference']);?></td></tr>
						<?php } ?>
						</table>	
					</td>
				</tr>
				 <?php if($show_extra_update_fields == 'true'){ ?>
				<tr>
					<td colspan="2">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr><td colspan="2"><strong>Update for: </strong></td></tr>
							<tr><td colspan="2">
									<?php //echo $HTTP_POST_VARS['update_for_chk'];
									if(isset($HTTP_POST_VARS['update_for_chk'])){
										foreach($HTTP_POST_VARS['update_for_chk'] as $key => $val){														
											if($val == 'Flight'){$show_check_flight = ' checked ';}													
											if( $val == 'Pickup'){$show_check_pickup = ' checked ';}
											if($val == 'Add Hotel Extension'){$show_check_add_hotel_extension = ' checked ';}
											if($val == 'Cancellation'){$show_check_cancellation = ' checked ';}
											if($val == 'Other'){$show_check_other = ' checked ';}
										}
									}
									?>
									<input type="checkbox" name="update_for_chk[]" <?php echo $show_check_flight;?> value="Flight">Flight&nbsp;
									<input type="checkbox" name="update_for_chk[]" <?php echo $show_check_pickup;?> value="Pickup">Pickup&nbsp;
									<input type="checkbox" name="update_for_chk[]" <?php echo $show_check_add_hotel_extension;?> value="Add Hotel Extension">Add Hotel Extension&nbsp;
									<input type="checkbox" name="update_for_chk[]" <?php echo $show_check_cancellation;?> value="Cancellation">Cancellation&nbsp;
									<input type="checkbox" name="update_for_chk[]" <?php echo $show_check_other;?> value="Other">Other&nbsp;
									</td>
							</tr>
							<?php if($_POST['update_for_notes'] != '') { ?>
							<tr><td colspan="2"><strong>Note: </strong><?php echo $_POST['update_for_notes'];?></td></tr>						
							<?php } ?>	
						</table>
					</td>
				</tr>
				<?php }?>
				<tr><td><hr size="3" color="#000000"></td></tr>							
				<tr>
					<td>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<tr><td colspan="2"><strong>Tour: </strong><?php echo stripslashes2($_POST['order_tour_name']);?></td></tr>							
							<tr><td colspan="2"><strong>Tour Code: </strong><?php echo stripslashes2($_POST['order_tour_model']);?></td></tr>
							<?php if(tep_not_null($_POST['transfer_info_text'])){?>
							<tr><td colspan="2"><strong>Service Information: </strong></td></tr>
							<tr><td colspan="2"><?php echo $_POST['transfer_info_text'];?></td></tr>
							<?php } else {?>
							<tr><td colspan="2"><strong>Departure Time and Location: </strong> <?php echo $_POST['depature_full_address'];?></td></tr>
							<?php //if($show_extra_update_fields != 'true'){ ?>
							<tr>
								<td width="10%" nowrap valign="top" ><strong>Room Requested: </strong></td>
								<td><?php echo stripslashes2(nl2br($_POST['guest_order_rooms_info']));?></td>
							</tr>  
							<?php //} ?>
							<?php }?>
							<tr>
								<td valign="top"><strong>Special Note:</strong></td>
								<td><?php echo stripslashes2(nl2br($_POST['guest_order_comment'])); ?></td>
							</tr>
							<tr><td colspan="2"><p><strong>Guest Name: </strong></p></td></tr>
							<tr>
								<td colspan="2">
										<table width="100%"  border="1" cellspacing="0" cellpadding="4">
										<?php 
											$guestnames = explode('<::>',$orders_eticket_result['guest_name']);
											$bodyweights = explode('<::>',$orders_eticket_result['guest_body_weight']);
											$guestheight = explode('<::>',$orders_eticket_result['guest_body_height']);
											if($orders_eticket_result['guest_number']==0){
												foreach($guestnames as $key=>$val)$loop = $key;
											}else{
												$loop = $orders_eticket_result['guest_number'];
											}
											for($noofguest=1;$noofguest<=$loop;$noofguest++){
												if(($noofguest%2)!=0)echo '<tr>';
										?>
											<td  width="50%" class="main" nowrap="nowrap"><strong><?php echo $noofguest; ?>.</strong>&nbsp;<?php echo $_POST['guest'.$noofguest];?> 
											<?php
											if(isset($_POST['etckchildage'.$noofguest]) && $_POST['etckchildage'.$noofguest] != ''){
												echo '&nbsp;&nbsp;&nbsp;<b>Age:</b> '.$_POST['etckchildage'.$noofguest];
											}
											if(isset($_POST['guestbirth'.$noofguest]) && $_POST['guestbirth'.$noofguest] != ''){
												echo '<br><b>Birth Date</b> (mm/dd/yyyy):'.$_POST['guestbirth'.$noofguest];
											}
											if(isset($_POST['etckheight'.$noofguest]) && $_POST['etckheight'.$noofguest] != ''){
												echo '<br><b>Height(ft/cm):</b> '.stripslashes($_POST['etckheight'.$noofguest]);
											}
											if(isset($_POST['etckweight'.$noofguest]) && $_POST['etckweight'.$noofguest] != ''){
												echo '<br><b>Weight:</b> '.stripslashes($_POST['etckweight'.$noofguest]);
											}
											?>
											</td>
											<?php 
											if(($noofguest%2)==0)echo '</tr>';
											} 
											?> 
										</table>
								</td>
							</tr>
							<tr><td colspan="2"><strong>Customer Contact#:</strong> <?php echo $_POST['customers_telephone'];?> </td></tr>
							<tr><td colspan="2"><strong>Customer Cell#:</strong> <?php echo $_POST['customers_cellphone'];?> </td></tr>
							<tr>
								<td class="main" colspan="2">
									<table width="100%" cellpadding="2" cellspacing="0">
										<tr>
											<td width="30%" class="main" valign="top" colspan="2"><b>Flight Information:</b> <?php if($show_extra_update_fields == 'true'){ echo $_POST['flight_infor_notes']; } ?></td></tr>
										<?php $orders_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_products_id = '".$order->products[$i]['orders_products_id']."' "); //orders_id = '" . tep_db_input($oID) . "'  and products_id=".$_GET['products_id']."
											if (tep_db_num_rows($orders_history_query)) 
											{
												while ($orders_history = tep_db_fetch_array($orders_history_query)) 
												{
													echo '<tr>
																		<td class="main" colspan="2">
																		<table width="100%">
																		<tr>
																			<td class="main">&nbsp;</td>
																			<td class="main"><strong>Flight Name</strong></td>
																			<td class="main"><strong>Flight Number</strong></td>
																			<td class="main"><strong>Airport Name</strong></td>
																			<td class="main"><strong>Date</strong></td>
																			<td class="main"><strong>Time</strong></td>
																		</tr>
																		<tr>
																			<td class="main"><strong>Arrival</strong></td>
																			<td class="main">'.$_POST['airline_name'] .'</td>
																			<td class="main">'.$_POST['flight_no'] .'</td>
																			<td class="main">'.$_POST['airport_name'] .'</td>
																			<td class="main">'.$_POST['arrival_date'] .'</td>
																			<td class="main">'.$_POST['arrival_time'] .'</td>
																		</tr>
																		<tr>
																			<td class="main"><strong>Departure</strong></td>
																			<td class="main">'.$_POST['airline_name_departure'] .'</td>
																			<td class="main">'.$_POST['flight_no_departure'] .'</td>
																			<td class="main">'.$_POST['airport_name_departure'] .'</td>
																			<td class="main">'.$_POST['departure_date'] .'</td>
																			<td class="main">'.$_POST['departure_time'] .'</td>
																		</tr>
																	</table>
																		</td>
																	 </tr>';
												}// end of while loop
											}//end of if
										//}//end of for
									?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			 <?php
			 $output = ob_get_contents(); 
			 $output = tep_db_prepare_input($output);
			// ob_clean();
			?>
		</td>
	</tr>
	<tr align="left"><td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td></tr>
	<tr>
		<td>
			<?php if(isset($_POST['save_doc_file']) && $_POST['save_doc_file']=='2'){?>
			<form name="sub_again" action="" method="post" >
				<input type="hidden" name="oID" value="<?php echo $_GET['oID']; ?>">
				<input type="hidden" name="output" value="<?php echo tep_db_output($output); ?>">
		 		<!--<input type="submit" name="submit_again" value="Save">-->
			</form>
			<?php echo '<script language="javascript">document.sub_again.submit();</script>';
			}?>
		</td>
	</tr>
</table>
<?php 
//fax preview end
}else{
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo STORE_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<div id="spiffycalendar" class="text"></div>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php  require(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
		<!-- left_navigation //-->
		<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
		<!-- left_navigation_eof //-->
		</table>
	</td>
	<!-- body_text //-->
	<td width="100%" valign="top">	
	<?php 
	if($_GET['auto_submit']=='1'){
		$extra_form_params='';
	}else{
		$extra_form_params='target="_blank"';
	}
	echo tep_draw_form('fax_form', FILENAME_ORDERS_FAX, tep_get_all_get_params(array('action')) . 'action=fax_preview', 'post', $extra_form_params);?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td>
			<!-- show content here start -->
			<table width="600" border="0" cellspacing="0" cellpadding="0">
      			<tr><td align="center"><strong><font size="+2">走四方网/<?php echo STORE_NAME; ?></font></strong></td></tr>
				<tr>
					<td class="main">
						<?php 
						$contact_phones = tep_get_us_contact_phone();
						foreach((array)$contact_phones as $key => $val){
							echo $val['name'].$val['phone']."<br>";
						}
						?>						
						Email：<?php echo STORE_OWNER_EMAIL_ADDRESS;?>
					</td>
				</tr>
				<tr><td><hr size="3" color="#000000"></td></tr>
				<tr><td><p align="center"><strong>Facsimile Transmittal Cover Page </strong></p></td></tr>
				<tr><td><hr size="3" color="#000000"></td></tr>
				<tr>
					<td>
						<?php 
						$agency_query = tep_db_query("select a.* from ".TABLE_PRODUCTS." as p, ".TABLE_TRAVEL_AGENCY." as a where p.products_id = '" . (int)$order->products[$i]['id'] . "' and p.agency_id = a.agency_id ");
						//组合团自动改变供应商信息
						//howard added sub-agency start
						$sub_agency_id = (int)$_POST['sub_agency_id'] ? (int)$_POST['sub_agency_id'] : (int)$_GET['sub_agency_id'];
						if((int)$sub_agency_id){
							$agency_query = tep_db_query("select a.* from ".TABLE_TRAVEL_AGENCY." as a where a.agency_id = '" . (int)$sub_agency_id . "' ");
						}
						//howard added sub-agency end
						$agency_result = tep_db_fetch_array($agency_query);
						?>
            <table width="600" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2">
                	<p><strong>Date: </strong><strong>
                	<input name="order_date" type="text" value="<?php echo tep_date_short($order->info['date_purchased']); ?>"></strong><strong></strong>
                	</p>
					<p>
                		<strong>Re:
                		<?php if($show_extra_update_fields == 'true'){ ?>
                		<input name="reservation_id" type="text" size="60" value="update for <?php echo STORE_NAME;?> existing Reservation # <?php echo ORDER_EMAIL_PRIFIX_NAME . tep_db_input($oID); ?>">
						<?php }else{ ?>					 
						<input name="reservation_id" type="text" size="60" value="<?php echo STORE_NAME;?> New Reservation # <?php echo ORDER_EMAIL_PRIFIX_NAME . tep_db_input($oID); ?>">
						<?php }?>
						</strong>
					</p>
					<p><strong>Total Number of Pages:<input name="total_num_page" type="text" size="40" value="1 (including cover page)"></strong></p>
				</td>
			</tr>
			<tr><td colspan="2"><hr size="2"></td></tr>
			<tr><td colspan="2" height="5"></td></tr>
			<tr>
				<td width="300"><strong>To:</strong><input name="to_name" type="text" value="<?php echo $agency_result['contactperson'];?>"></td>
                <td width="300"><p><strong>From:</strong><input name="from_name" type="text" value="<?php echo tep_get_admin_customer_name($_SESSION['login_id'])."/".STORE_NAME;?>" size="25">	</p></td>
			</tr>
			<tr><td colspan="2" height="5"></td></tr>
			<tr><td colspan="2"><hr size="2"></td></tr>
			<tr><td colspan="2" height="5"></td></tr>
			<tr>
				<td><strong>Company:</strong><input name="to_company" type="text" value="<?php echo $agency_result['agency_name'];?>"></td>
                <td><strong>Company:</strong><input name="from_company" type="text" value="<?php echo STORE_NAME; ?>">                </td>
              </tr>
              <tr><td colspan="2" height="5"></td></tr>
              <tr><td colspan="2"><hr size="2"></td></tr>
              <tr><td colspan="2" height="5"></td></tr>
              <tr>
				<td><strong>Telephone#:</strong><input name="to_telephone" type="text" value="<?php echo $agency_result['phone'];?>"></td>
				<td><strong>Telephone#:</strong><input name="from_telephone" type="text" value="626-898-7800"> </td>
              </tr>
              <tr><td colspan="2" height="5"></td></tr>
              <tr><td colspan="2"><hr size="2"></td></tr>
              <tr>
              	<td colspan="2" height="5"></td></tr>
				<td><strong>Fax#: </strong><input name="to_fax" type="text" value="<?php echo $agency_result['fax'];?>" size="40">              </td>
				<td><strong>Fax#: </strong><input name="from_fax" type="text" value="">            </td></tr>
              <tr><td colspan="2" height="5"></td></tr>
              <tr><td colspan="2"><hr size="2"></td></tr>
              <tr>
              	<td colspan="2">
					<?php if($show_extra_update_fields == 'true'){ ?>				
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	                  <tr>
	                    <td><input type="checkbox" name="contect_for_chk[]" checked value="replybyfax"> <strong>Please Reply by Fax</strong></td>
						<td><input type="checkbox" name="contect_for_chk[]" value="urgent"> <strong>Urgent</strong></td>
						<td><input type="checkbox" name="contect_for_chk[]" value="foryourinfo"> <strong>For Your Information</strong></td>
	                  </tr>
	                </table>
					<?php }else{ ?>				
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	                  <tr>
	                    <td><input type="checkbox" name="contect_for_chk[]" checked value="confirmbyfax"> <strong>Confirm by Fax</strong></td>
						<td><input type="checkbox" name="contect_for_chk[]" value="confirmbyemail"> <strong>Confirm by Email</strong> </td>
						<td>	<input type="checkbox" name="contect_for_chk[]" value="urgentreply"> <strong>Urgent Reply</strong></td>
	                  </tr>
	                </table>	
					<?php } ?>
				</td>
              </tr>
          </table>
         </td>
      </tr>
      <tr><td><hr size="3" color="#000000"></td></tr>
	<?php if($show_extra_update_fields == 'true'){ ?>
	<tr>
		<td colspan="2">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td colspan="2"><strong>This is an existing booking. Do NOT double book!</strong></td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td><strong>Reference#:</strong></td>
				   <td ><input type="text" name="reference" value="" size="50"/></td>
				</tr>
				<tr><td colspan="2"><strong>Update for: </strong></td></tr>
				<tr>
					<td colspan="2">
						<input type="checkbox" name="update_for_chk[]" value="Flight"> Flight
						<input type="checkbox" name="update_for_chk[]" value="Pickup"> Pickup
						<input type="checkbox" name="update_for_chk[]" value="Add Hotel Extension"> Add Hotel Extension 
						<input type="checkbox" name="update_for_chk[]" value="Cancellation"> Cancellation
						<input type="checkbox" name="update_for_chk[]" value="Other"> Other
					</td>
				</tr>
				<tr>
					<td><strong>Note:</strong></td>
					<td><textarea name="update_for_notes" cols="50" rows="2"></textarea></td>
				</tr>
			</table>
		</td>
	</tr>			
	<tr><td colspan="2"><hr size="3" color="#000000"></td></tr>   
		<?php }?>
		<tr>
			<td>
				<table   border="0" cellspacing="0" cellpadding="0">
	            <tr>
	            	<td><strong>Tour:</strong></td>
					<td>
					<?php
					//组合团自动改变产品名
					//howard added sub-agency start
					$order_tour_name = stripslashes2($order->products[$i]['name']);
					if((int)$_GET['sub_products_id']){
						$order_tour_name = tep_get_products_name((int)$_GET['sub_products_id']);
					}
					//howard added sub-agency end
					?>
					<input type="text" name="order_tour_name" value="<?php echo $order_tour_name;?>" size="50"/>
				  </td>
					<?php
					/* 
					$tour_name_by_provider = tep_get_products_provider_name($order->products[$i]['id'], $languages_id);
					if($tour_name_by_provider != ''){
						$tour_name_fax_display = $tour_name_by_provider;
					}else{
						$tour_name_fax_display = $order->products[$i]['name'];
						
						if($language != 'english'){
							$get_provider_eng_name = tep_get_products_english_for_providers($order->products[$i]['id']);
							if($get_provider_eng_name != ''){
								$tour_name_fax_display .= '<br>['.$get_provider_eng_name.']';
							}
						}
					}
					*/
					 /* <input type="text" name="order_tour_name" value="<?php echo stripslashes2($tour_name_fax_display);?>" size="50"/> */ 
					?>
	            </tr>
	            <tr><td colspan="2">&nbsp;</td></tr>
	            <tr>
					<td><strong>Tour Code:</strong></td>
					<td>
					  <?php
					//组合团自动改变对应的供应商产品信息
					//howard added sub-agency start
					  $order_tour_model = tep_get_provider_tourcode(stripslashes2($order->products[$i]['id']));
					  if((int)$_GET['sub_products_id']){
					  	$order_tour_model = tep_get_provider_tourcode((int)$_GET['sub_products_id']);
					  }
					//howard added sub-agency end
					  ?>
					  <input type="text" name="order_tour_model" value="<?php echo $order_tour_model;?>" size="50"/>              </td>
	            </tr>
	            <?php if($order->products[$i]['is_transfer'] != '1') {?>
	            <tr><td colspan="2">&nbsp;</td></tr>
	            <tr>
	              <td><strong>Departure Time and Location:</strong></td>
	              <td>
		              <?php 
						  if($orders_eticket_result['depature_full_address'] != '') {
						  $depature_full_address = $orders_eticket_result['depature_full_address']; 			
						  }else {
							$orders_fulladdress_query = tep_db_query("select * from products_departure where departure_time = '" . tep_db_prepare_input(tep_db_input($order->products[$i]['products_departure_time'])) . "' and departure_address = '".tep_db_prepare_input(tep_db_input($order->products[$i]['products_departure_location']))."' and products_id = ".(int)$order->products[$i]['id']." ");
							$orders_fulladdress = tep_db_fetch_array($orders_fulladdress_query);
							$depature_full_address =  $order->products[$i]['products_departure_date'].' &nbsp; '.$order->products[$i]['products_departure_time'].' &nbsp; '.$orders_fulladdress['departure_full_address'];
						}
						//组合团自动改变第二个团以后的团的出发日期上车时间等{ Howard added
						if((int)$_GET['sub_products_id']){
							$_depature_full_address = tep_get_sub_orders_products_departure_date($_GET['oID'], $order->products[$i]['id'], (int)$_GET['sub_products_id']);							
							if($_depature_full_address!=false){
								$depature_full_address = $_depature_full_address;
							}
						}
						//}
						?>
						<input type="text" name="depature_full_address" value="<?php echo stripslashes2($depature_full_address);?>" size="50"/>
					</td>
	            </tr>
	            <?php }else {?>
	              <tr>
	              <td valign="top"><strong>Service Information:</strong></td>
	              <td>
	              <?php 
	              	$transfer_info_html =  tep_transfer_display_route($order->products[$i]['transfer_info_arr']);
	              	$trans = array('Pick Up'=>'起点','<br/>Drop Off'=>'终点','Flight Number'=>'航班号',
	              	'<br/>Flight Departure'=>'出发地点' ,'Flight Arrival Time'=>'抵达时间'
	              	,'<br/>Guest Total'=>'人数','<br/>Baggage Total'=>'行李','Guest Note'=>'留言',' '=>'件','  '=>'人',
	              	':'=>'：','   '=>'<strong>','    '=>'</strong>'
	              	);	              	
	              	$transfer_info_html = db_to_html(str_replace(array_values($trans),array_keys(	$trans),$transfer_info_html));
	              	echo $transfer_info_html;
	              	?>
	              <span style="color:red"><?php echo db_to_html('(Service Info的内容请在订单管理中 相应产品的接驳路线编辑中修改)')?></span>
	              <textarea  name="transfer_info_text"  style="visibility:hidden"><?php echo stripslashes2($transfer_info_html);?></textarea>
	              </td>
	              </tr>
	            <?php } //end for is_transfer check?>
				<?php //if($show_extra_update_fields != 'true'){ ?>
				 <?php if($order->products[$i]['is_transfer'] != '1') {?>
	            <tr><td colspan="2">&nbsp;</td></tr>
	            <tr>
					<td ><strong>Room Requested:</strong></td>
					<td>
					<?php 
						  //$order->products[$i]['products_room_info'] = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE,'',$order->products[$i]['products_room_info']);
						$order->products[$i]['products_room_info'] = preg_replace('/总价\(包括[0-9.]+%服务费\)/','',$order->products[$i]['products_room_info']);
						$finalrommstring = str_replace('No','Number',$order->products[$i]['products_room_info']); 
						$finalrommstring = str_replace('no','Number',$finalrommstring);
						/*$finalrommstring = str_replace('#','Number',$finalrommstring);*/
						$finalrommstring = str_replace('room','Room',$finalrommstring);
						$finalrommstring = str_replace('childs','children',$finalrommstring);
						//	echo $finalrommstring; 		
						if(eregi('- Total :',stripslashes($finalrommstring))){
							$req_roomarray = explode('- Total :',stripslashes($finalrommstring));
						}
						if(!isset($req_roomarray[0])){
							$req_roomarray[0] = str_replace("<br>".TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL." :",'',stripslashes($finalrommstring));
						}
						
						$req_roomarray[0] = preg_replace('/[[:space:]](\$|\&#65509;)[0-9\,]*.[0-9]+/', '',$req_roomarray[0]);
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM1,'',$req_roomarray[0]);
						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM2,'',$req_roomarray[0]);
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM3,'',$req_roomarray[0]);
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM4,'',$req_roomarray[0]);
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM5,'',$req_roomarray[0]);
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM6,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM7,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM8,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM9,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM10,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM11,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM12,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM13,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM14,'',$req_roomarray[0]);						
						$req_roomarray[0] = str_replace('<br>'.TEXT_TOTLE_OF_ROOM15,'',$req_roomarray[0]);
												

						$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]room[[:space:]][0-9]+:[[:space:]](\$|\&#65509;)[0-9\,]*.[0-9]+/', '', $req_roomarray[0]);
						$req_roomarray[0] = preg_replace('/(\$|\&#65509;)[0-9\,]*.[0-9]*/','',$req_roomarray[0]);
							
						//echo $req_roomarray[0];
						//echo $finalrommstring;		
						?>
	                  <?php 
	                  	if($showroom1str == 'true'){
	                  		$extrashow = '';
						//$extrashow = 'Total # of Rooms : 1 ';
						}?>
		                <?php // echo $req_roomarray[0];?>
						<?php 
						if(get_products_departure_date_num($order->products[$i]['id']) <= 1){
							//$req_roomarray[0] = '';
						}
						?>
					  <textarea name="guest_order_rooms_info" cols="50" rows="5"><?php echo stripslashes2($extrashow . $req_roomarray[0]); ?></textarea> </td>
	            </tr>
	            <?php } //end transfer room info not need?>
				<?php //} ?>
	            <tr><td colspan="2">&nbsp;</td></tr>
	            <tr>
	              <td valign="top"><strong>Special Note:</strong></td>
	              <td>
		              <?php
						$orders_history_query1 = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' and orders_status_id='1' order by date_added limit 1");
						if (tep_db_num_rows($orders_history_query1)) {
							while ($orders_history1 = tep_db_fetch_array($orders_history_query1)) {
								$orders_history_comment = $orders_history1['comments'];
							}
						}
						?>
	                  <textarea name="guest_order_comment" cols="70" rows="7"><?php echo nl2br(stripslashes2($orders_history_comment));
					  $attribute_total_str = '';
						if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
							$attribute_total_str .=  chr(13).chr(10);
							for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
								
								$attribute_total_str .= chr(13).chr(10) . $order->products[$i]['attributes'][$j]['option'] . ': ' ;
								if( preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TOUR_IDS_FOR_ATTR_THEME_PARK_NOTE.",")){							
									if(preg_match("/预订时支付/i", $order->products[$i]['attributes'][$j]['value']) || preg_match("/预定时支付/i", $order->products[$i]['attributes'][$j]['value']) ){
										$attribute_total_str .=  $order->products[$i]['attributes'][$j]['value'] .' &nbsp; '. ATTR_THEME_PARK_INCLUDE_NOTE;
									}else if(preg_match("/在团上以现金方式支付/i", $order->products[$i]['attributes'][$j]['value']) ){						  
										$attribute_total_str .= ATTR_THEME_PARK_EXCLUDE_NOTE;	  	
									}else{
										$attribute_total_str .= $order->products[$i]['attributes'][$j]['value'];
								  }						  
									  
							  }else{
									$attribute_total_str .= $order->products[$i]['attributes'][$j]['value'];
								}
							}
							echo stripslashes2($attribute_total_str);
						}	
						if(tep_not_null($order->products[$i]['hotel_pickup_info'])){
							echo chr(13).chr(10).chr(10).'Guest Hotel Info: '.nl2br(stripslashes2($order->products[$i]['hotel_pickup_info']));
						}					 
						//添加单人配房的客户通知
						if(!preg_match('/Please pair customer up to double room\. Customer Name:/',$orders_history_comment)){
							$single_pu_tags = get_single_pu_tags($oID,$order->products[$i]['id']);
							if(tep_not_null($single_pu_tags)){
								$p_string = str_replace('(Female)',chr(13).chr(10) .'Gender:(Female)',$single_pu_tags);
								$p_string = str_replace('(Male)',chr(13).chr(10) .'Gender:(Male)',$p_string);
								echo chr(13).chr(10) .'Please pair customer up to double room. '.chr(13).chr(10) .'Customer Name:'.$p_string;
							}
					 	}
						//tom added 日本团添加自带磁带start
						if(in_array($order->products[$i]['model'],$japan_array)){
							echo  chr(13).chr(10) .db_to_html('带中文磁带');
						}
					 ?></textarea></td>
	            </tr>
	            <tr><td colspan="2">&nbsp;</td></tr>
			</table>
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr><td><p><strong>Guest Name: </strong></p></td></tr>
              <tr>
                <td>
                	<table width="100%"  border="1" cellspacing="0" cellpadding="4">
                    	<?php 
                    	$guestnames = explode('<::>',$orders_eticket_result['guest_name']);
						$bodyweights = explode('<::>',$orders_eticket_result['guest_body_weight']);
						$guestgenders = explode('<::>',$orders_eticket_result['guest_gender']);
						$guestheight = explode('<::>',$orders_eticket_result['guest_body_height']);	
						if($orders_eticket_result['guest_number']==0){
							foreach($guestnames as $key=>$val)$loop = $key;
						}else{
							$loop = $orders_eticket_result['guest_number'];
						}
						
						for($noofguest=1;$noofguest<=$loop;$noofguest++){
							if(($noofguest%2)!=0)echo '<tr>';
						?>
						<td  width="50%" class="main" nowrap="nowrap"><strong><?php echo $noofguest; ?>.</strong>&nbsp;
				    	<?php		
							$show_guest_gender = '';
							$show_age_field = true;
							if(trim($guestgenders[($noofguest-1)]) != ''){$show_guest_gender = ' ('.trim($guestgenders[($noofguest-1)]).')';}
							$show_guest_height = '';
							if(trim($guestheight[($noofguest-1)]) != ''){$show_guest_height = trim($guestheight[($noofguest-1)]);}								
							$show_guest_weight = '';
							if(trim($bodyweights[($noofguest-1)]) != ''){$show_guest_weight = trim($bodyweights[($noofguest-1)]);}
							$guest_name_incudes_child_age = explode('||',$guestnames[($noofguest-1)]);
							if(isset($guest_name_incudes_child_age[1])){
								$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($depature_full_address,0,10)));
						?>
								<input type="text" name="<?php echo 'guest'.$noofguest; ?>" size="15" value="<?php echo stripslashes2(trim($guest_name_incudes_child_age[0]).$show_guest_gender); ?>" />
								<?php 								
								if($show_guest_gender != '') { 
								$max_allow_age = tep_get_max_allow_child_age_for_tour($order->products[$i]['id']);
								if($max_allow_age <= (float)$di_childage_difference_in_year){
									$show_age_field = false;
								}
								?>
								<strong>Birth Date:</strong> <input type="text" size="10" name="<?php echo 'guestbirth'.$noofguest; ?>" value="<?php echo $guest_name_incudes_child_age[1]; ?>" />
								<br>
								<?php } ?>
								<?php if($show_age_field == true) { ?>
								<strong>Age:</strong> <input type="text" size="11" name="<?php echo 'etckchildage'.$noofguest; ?>" value="<?php echo $di_childage_difference_in_year; ?>" />
								<?php } ?>								
								<?php }else{ ?>
					  			<input type="text" name="<?php echo 'guest'.$noofguest; ?>" size="20" value="<?php echo stripslashes2(preg_replace("/(\|\||\(f\)|\(m\))/","",$guestnames[($noofguest-1)]).$show_guest_gender); ?>" />
					  			<?php } ?>
					  			<?php if($show_guest_height != ''){ ?>
								<strong>Height(ft/cm):</strong> <input type="text" size="6" name="<?php echo 'etckheight'.$noofguest; ?>" value="<?php echo stripslashes2($show_guest_height); ?>" />
								<?php } ?>
                                <?php if($show_guest_weight != ''){ ?>
								<strong>Weight:</strong> <input type="text" size="6" name="<?php echo 'etckweight'.$noofguest; ?>" value="<?php echo stripslashes2($show_guest_weight); ?>" />
								<?php } ?>
					  </td>
						<?php if(($noofguest%2)==0)echo '</tr>';
							} //endfor
						?>
                        </table>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td  class="main" valign="top" colspan="2"><b>Flight Information:
								<?php //echo $order->products[$i]['name']; ?>
								<?php if($show_extra_update_fields == 'true'){ ?>
								<input type="text" name="flight_infor_notes" value="" size="50"/>
								<?php } ?></b>
								</td>
							</tr>
                    <?php 
                    $orders_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . tep_db_input($oID) . "' and products_id=".$_GET['products_id']." ");
					if (tep_db_num_rows($orders_history_query)) {
						while ($orders_history = tep_db_fetch_array($orders_history_query)) {
					?>
							<tr>
								<td class="main" colspan="2">
				                    <script language="javascript"><!--
									var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "fax_form", "arrival_date","btnDate","<?php echo tep_get_date_disp($orders_history['arrival_date']); ?>",scBTNMODE_CUSTOMBLUE);
									var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "fax_form", "departure_date","btnDate1","<?php echo tep_get_date_disp($orders_history['departure_date']); ?>",scBTNMODE_CUSTOMBLUE);
									//--></script>
									<table border="0" width="100%" cellspacing="0" cellpadding="2">
					                    <tr>
					                        <td class="main">Arrival Airline Name</td>
					                        <td><?php echo tep_draw_input_field('airline_name', $orders_history['airline_name'], ''); ?></td>
					                        <td class="main">Departure Airline Name</td>
					                        <td><?php echo tep_draw_input_field('airline_name_departure', $orders_history['airline_name_departure'], ''); ?></td>
					                      </tr>
					                      <tr>
					                        <td class="main">Arrival Flight Number</td>
					                        <td><?php echo tep_draw_input_field('flight_no', $orders_history['flight_no'], ''); ?></td>
					                        <td class="main">Departure Flight Number</td>
					                        <td><?php echo tep_draw_input_field('flight_no_departure', $orders_history['flight_no_departure'], ''); ?></td>
					                      </tr>
					                      <tr>
					                        <td class="main">Arrival Airport Name</td>
					                        <td><?php echo tep_draw_input_field('airport_name', $orders_history['airport_name'], ''); ?></td>
					                        <td class="main">Departure Airport Name</td>
					                        <td><?php echo tep_draw_input_field('airport_name_departure', $orders_history['airport_name_departure'], ''); ?></td>
					                      </tr>
					                      <tr>
					                        <td class="main">Arrival Date</td>
					                        <td><?php //echo tep_draw_input_field('arrival_date', $arrival_date, ''); ?>
					                         <script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="MM/dd/yyyy";</script>                        </td>
					                        <td class="main">Departure Date</td>
					                        <td><?php //echo tep_draw_input_field('departure_date', $departure_date, ''); ?>
					                            <script language="javascript">dateAvailable1.writeControl(); dateAvailable1.dateFormat="MM/dd/yyyy";</script></td>
					                      </tr>
					                      <tr>
					                        <td class="main">Arrival Time</td>
					                        <td><?php echo tep_draw_input_field('arrival_time', $orders_history['arrival_time'], ''); ?></td>
					                        <td class="main">Departure Time</td>
					                        <td><?php echo tep_draw_input_field('departure_time', $orders_history['departure_time'], ''); ?></td>
					                      </tr>
				                    </table>
				              </td></tr>
				<?php 
						}// end of while loop
					}//end of if
				//}//end of for
			    ?>
                </table></td>
              </tr>
              <tr><td>&nbsp;</td></tr>
			  <tr><td><strong>Customer Contact#:</strong><input name="customers_telephone" type="text" value="<?php echo $order->customer['telephone'];?>"></td></tr>
			  <tr><td>&nbsp;</td></tr>
              <tr><td><strong>Customer Cell#:</strong><input name="customers_cellphone" type="text" value="<?php echo tep_customers_cellphone($order->customer['id']);?>"></td></tr>
              <tr><td>&nbsp;</td></tr>
			</table>
			</td>
		</tr>
	</table>
	<!-- show content here end --></td>
	</tr>
	<input type="hidden" name="save_doc_file" value='1'>
	<?php if($show_extra_update_fields == 'true'){ ?>
	<input type="hidden" name="update_fax" value='true'>
	<?php } ?>
	<tr><td align="center"  >
	<?php
	// echo '<a href="' . tep_href_link(FILENAME_ORDERS_FAX, tep_get_all_get_params(array('action')). 'action=fax_preview' ) . '">'.  tep_image_submit('button_save.gif', 'Save') .'</a>';
	 echo tep_image_button('button_save.gif', 'Save As', 'onclick="save_doc()" class=cursor_type');
	echo tep_image_button('button_preview.gif', IMAGE_PREVIEW, 'onclick="not_save_doc()" class=cursor_type') .'<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action','update_fax')). '' ) . '">'. tep_image_button('button_back.gif', IMAGE_BACK) .'</a>';
	?>	</td>
	</tr>
	</table>
	</form>
</td>	
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<script>
		function save_doc()
		{
			document.fax_form.save_doc_file.value=2;
			document.fax_form.submit();
		}
		function not_save_doc()
		{
			document.fax_form.save_doc_file.value=1;
			document.fax_form.submit();
		}
	<?php if($_GET['auto_submit']=='1'){?>not_save_doc();<?php }?>
</script>

<style type="text/css">
.cursor_type{	cursor:pointer;}
</style>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php } ?>  
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
