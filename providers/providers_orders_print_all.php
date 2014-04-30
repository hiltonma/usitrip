<?php 
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id'))
{
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
$provider_id=$_SESSION['providers_id'];
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_ORDERS);
require_once(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' .'eticket.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="reset.css" />
<title><?php echo 'usitrip Print Order' ?></title>
<style type="text/css">
.wrap{width:650px;margin:20px auto;}
.date-table,.date-msg,.date-context{width:100%;/*border:1px solid #000;*/color:#333;}
.date-table td,.date-msg td{border:1px solid #000;line-height:26px;padding-left:15px;}
.date-msg th{border:1px solid #000;line-height:30px;border-top:0 none;border-bottom:0 none;border-right:0;background:#eee;font-size:14px;font-weight:bold;}
.date-msg td{border-color:#f60;}
.date-msg td.msg-info{background-color:#ffc;}
.date-context{border-top:0 none;}
.date-context td{line-height:24px;padding:5px 15px;font-size:13px;}
</style>
</head>

<body>
<table class="wrap" align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main"><script type="text/javascript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo LINK_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo LINK_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?php echo DIR_WS_TEMPLATE_IMAGES;?>close_window.jpg' border=0></a></p></td>
      </tr>
    </table>
<div class="wrap">
<?php 
		$sql_orders_detail_print_all = "SELECT o.orders_id, o.customers_id, o.customers_name, o.customers_telephone, o.guest_emergency_cell_phone, o.date_purchased, o.orders_status, o.payment_method, op.orders_products_id, op.orders_id, op.products_id, p.provider_tour_code, op.products_model, op.products_name, op.products_price, op.final_price, op.products_tax, op.products_quantity, op.products_departure_date, op.products_departure_time, op.hotel_checkout_date, op.products_departure_location, op.products_room_price, op.products_room_info, op.final_price_cost, op.products_room_info, op.customer_seat_no, op.customer_bus_no, op.customer_confirmation_no, op.customer_invoice_no, op.total_room_adult_child_info,op.customer_invoice_total, op.customer_invoice_comment, op.customer_bus_no,op.customer_seat_no,p.provider_tour_code, a.agency_code, os.orders_status_name, f.airline_name, f.flight_no, f.airline_name_departure, f.flight_no_departure, f.airport_name, f.airport_name_departure, f.arrival_date, f.arrival_time, f.departure_date , f.departure_time, f.show_warning_on_admin, f.sent_confirm_email_to_provider, pd.products_name_provider, p.products_durations, p.products_durations_type,p.products_vacation_package,p.departure_city_id, op.products_departure_location_sent_to_provider_confirm, op.is_hotel, p.note_to_agency FROM ".TABLE_ORDERS." o 
	INNER JOIN ".TABLE_ORDERS_PRODUCTS." op ON o.orders_id=op.orders_id 
	INNER JOIN ".TABLE_PRODUCTS." p ON op.products_id=p.products_id AND p.agency_id IN (".$_SESSION['providers_agency_id'].")
	INNER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON p.products_id=pd.products_id AND pd.language_id = 1
	INNER JOIN ".TABLE_TRAVEL_AGENCY." a ON p.agency_id=a.agency_id
	LEFT JOIN ".TABLE_ORDERS_STATUS." os ON os.orders_status_id=o.orders_status
	LEFT JOIN ".TABLE_ORDERS_PRODUCTS_FLIGHT." f ON op.orders_id = f.orders_id AND op.products_id=f.products_id 
	WHERE op.orders_products_id='".$_GET['opID']."'";
		#echo $sql_orders_detail_print_all;
	$res_orders_detail_print_all=tep_db_query($sql_orders_detail_print_all);
	$row_orders_detail_print_all=tep_db_fetch_array($res_orders_detail_print_all);

	$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_products_id = '" . (int)$row_orders_detail_print_all['orders_products_id'] . "' ");
	$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
?>
<table class="date-table">
		<tbody>
        	<tr>
            	<td><strong>UsiTrip订单号#：</strong><?php echo ORDER_EMAIL_PRIFIX_NAME . tep_get_products_orders_id_str($row_orders_detail_print_all['orders_id'], $row_orders_detail_print_all['is_hotel']);?></td>
                <td><strong>团号：</strong><?php echo $row_orders_detail_print_all['provider_tour_code'];//地接商只能看懂他们自己的团号?></td>
            </tr>
            <?php $tour_name_by_provider = tep_get_products_provider_name($row_orders_detail_print_all['products_id'], '1');
							if($tour_name_by_provider != ''){
							?>
            <tr>
            	<td colspan="2"><strong>名称：</strong><?php echo  $tour_name_by_provider;?></td>
            </tr>
            <?php } ?>
			
			<tr>
            	<td colspan="2"><strong>注意事项：<?php echo nl2br(tep_db_output($row_orders_detail_print_all['note_to_agency']));?></strong></td>
            </tr>
            
            <?php if($row_orders_detail_print_all['is_hotel'] == 1){?>
			<tr>
            	<td><strong>入住日期：</strong>
				<?php
					echo date('m/d/Y',strtotime($row_orders_detail_print_all['products_departure_date']));
				?>
				</td>
                <td><strong>退房日期：</strong>
				<?php
					echo date('m/d/Y',strtotime($row_orders_detail_print_all['hotel_checkout_date']));
					echo ' ('.date1SubDate2($row_orders_detail_print_all['hotel_checkout_date'], $row_orders_detail_print_all['products_departure_date']).'晚)';
				?>
				</td>
            </tr>
			<?php }else{?>
			<tr>
            	<td><strong>出团日期：</strong>
				<?php
				if($row_orders_detail_print_all['products_departure_location_sent_to_provider_confirm'] == "1"){
					echo date('Y-m-d',strtotime($row_orders_detail_print_all['products_departure_date']));
					echo $row_orders_detail_print_all['products_departure_time'];
				}
				?>
				</td>
                <td><strong>出发城市：</strong><?php echo implode(' / ', tep_get_city_names($row_orders_detail_print_all['departure_city_id']));?></td>
            </tr>
			<?php }?>
			
            <?php
			//游客姓名和房间信息等
			$can_show_guest_info = tep_can_show_guest_info($row_orders_detail_print_all['orders_products_id']);
			//显示最新客人信息和历史记录						
			if($can_show_guest_info == true){
			?>
			<tr>
            	<td><table style="border:0;"><tr><td style="border:0;"><strong>游客姓名：</strong></td><td style="border:0;"><?php $guestnames = explode('<::>',$orders_eticket_result['guest_name']);
						$bodyweights = explode('<::>',$orders_eticket_result['guest_body_weight']);
						$guestgenders = explode('<::>',$orders_eticket_result['guest_gender']);
						if($orders_eticket_result['guest_number']==0 || (is_array($guestnames) &&  !empty($guestnames))){
							foreach($guestnames as $key=>$val){
								$loop = $key;
							}
						}
						else{
							$loop = $orders_eticket_result['guest_number'];		
						}
						if(tep_get_product_type_of_product_id($orders_eticket_result['products_id']) == 2){
					
							for($noofguest=1;$noofguest<=$loop;$noofguest++)
							{
								$show_guest_gender = '';
								if(trim($guestgenders[($noofguest-1)]) != ''){
									$show_guest_gender = ' ('.trim($guestgenders[($noofguest-1)]).')';
								}
								?>
								<?php echo $noofguest.'. '; 
								$guest_name_incudes_child_age = explode('||',$guestnames[($noofguest-1)]);
								
								if(isset($guest_name_incudes_child_age[1])){
									if($row_orders_detail_print_all['products_departure_date'] != ''){
										$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($row_orders_detail_print_all['products_departure_date'],0,10)));
									}
									$guestConten = stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender) .'('.$di_childage_difference_in_year.')<br />';
								}else{
									$guestConten = stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender) .'<br />';
								}
								echo $guestConten; 
								?>
								
							<?php 
								
							} 
						}else{
							for($noofguest=1;$noofguest<=$loop;$noofguest++){
								$show_guest_gender = '';
								if(trim($guestgenders[($noofguest-1)]) != ''){
									$show_guest_gender = ' ('.trim($guestgenders[($noofguest-1)]).')';
								}
								echo $noofguest.'. ';
								$guest_name_incudes_child_age = explode('||',$guestnames[($noofguest-1)]);
													
								if(isset($guest_name_incudes_child_age[1])){
									if($row_orders_detail_print_all['products_departure_date'] != ''){
										$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($row_orders_detail_print_all['products_departure_date'],0,10)));
									}
									$guestConten = stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender) .'('.$di_childage_difference_in_year.')<br />';
								}else{
									$guestConten = stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender) .'<br />';
								}
								echo $guestConten;								
							} 
					}?></td></tr></table></td>
                <td style="padding:5px;"><strong>房间人数：</strong><?php //Howard add 显示床型信息{
								$bedOptions=array();
								if(strpos($row_orders_detail_print_all['products_room_info'],'Bed type')!==false || strpos($row_orders_detail_print_all['products_room_info'],'床型')!==false){
									
									$bed_option_infos = explode('<br>',$row_orders_detail_print_all['products_room_info']);
									for($bedI=0, $N=sizeof($bed_option_infos); $bedI<$N; $bedI++){
										if(strpos($bed_option_infos[$bedI],'Bed type')!==false || strpos($bed_option_infos[$bedI],'床型')!==false){
											$bedOptions[sizeof($bedOptions)+1]=preg_replace('/.+:/','',$bed_option_infos[$bedI]);
										}
									}
								}
								//Howard add 显示床型信息}
								?>
								 
								 <?php
								 if(tep_not_null($row_orders_detail_print_all['total_room_adult_child_info'])){
										$total_rooms = get_total_room_from_str($row_orders_detail_print_all['total_room_adult_child_info']);
										 if($total_rooms > 0){
											?>
											<table width="100%" class="login">
												 <tr><td width="34%" align="center" nowrap><?php echo TXT_ETICKET_NUMBER_OF_ROOM;?></td>
												 <td align="center"><?php echo TXT_ETICKET_ADULT;?></td>
												 <td align="center"><?php echo TXT_ETICKET_CHILD;?></td>
												 <td align="center"><?php if(sizeof($bedOptions)>0) echo BED_OPTION_INFO;?></td>
												 </tr>
											<?php
											for($t=1;$t<=$total_rooms;$t++){
												$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_orders_detail_print_all['total_room_adult_child_info'],$t);
												echo '<tr><td align="center">'.$t.'</td><td align="center">'.$chaild_adult_no_arr[0].'</td><td align="center">'.$chaild_adult_no_arr[1].'<td align="center">'.$bedOptions[$t].'</td></td></tr>';
											}
											?></table><?php
										 }else{
												$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_orders_detail_print_all['total_room_adult_child_info'], 1);
												$total_adults = $chaild_adult_no_arr[0];
												$total_children = $chaild_adult_no_arr[1];
												echo '<table width="100%" class="login"><tr><td width="50%" align="center">'.TXT_ETICKET_ADULT.'</td><td width="50%" align="center">'.TXT_ETICKET_CHILD.'</td></tr><tr><td align="center">'.$total_adults.'</td><td align="center">'.$total_children.'</td></tr></table>';
										 }
								 }else{
								 $finalrommstring = str_replace('No','Number',$row_orders_detail_print_all['products_room_info']); 
								$finalrommstring = str_replace('no','Number',$finalrommstring);
								$finalrommstring = str_replace('#','Number',$finalrommstring);
								$finalrommstring = str_replace('room','Room',$finalrommstring);
								$finalrommstring = str_replace('childs','children',$finalrommstring);
								//$finalrommstring = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE,'',$finalrommstring);
								$finalrommstring = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,'',$finalrommstring);
								$finalrommstring = preg_replace('/Total Fares \([0-9.]+% transaction fee included\)/','',$finalrommstring);
			
							//	echo $finalrommstring; 					
								if(eregi('- Total :',stripslashes2($finalrommstring))){
									$req_roomarray = explode('- Total :',stripslashes2($finalrommstring));
								}else if(eregi('Total :',stripslashes2($finalrommstring))){
									$req_roomarray = explode('Total :',stripslashes2($finalrommstring));
								}else if(eregi('Total :-',stripslashes2($finalrommstring))){
									$req_roomarray = explode('Total :-',stripslashes2($finalrommstring));
								}else if(eregi('- Total of Room',stripslashes2($finalrommstring))){						
									$req_roomarray[0] = preg_replace('/-[[:space:]]Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
								}else if(eregi('Total of Room',stripslashes2($finalrommstring))){						
									$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
								}else{
									$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
								
								}						
								echo db_to_html($req_roomarray[0]);
								 }//End of check if of total_room_adult_child_info
								 ?></td>
            </tr>
			<?php }?>
			
            <tr>
                <td><strong>客人联系方式：</strong><?php echo $row_orders_detail_print_all['customers_telephone'];?></td>
                <td><strong>当日游客手机：</strong><?php echo $row_orders_detail_print_all['guest_emergency_cell_phone'];?></td>
            </tr>
            <?php if (tep_not_null($row_orders_detail_print_all['products_departure_time'])) { ?>
            <tr>
            	<td colspan="2"><strong>上车地点：</strong><?php echo $row_orders_detail_print_all['products_departure_time'];?>##<?php echo $row_orders_detail_print_all['products_departure_location'] ?></td>
            </tr>
            <?php } ?>
            <tr>
            	<td colspan="2" style="padding:5px;"><strong>航班信息：</strong><?php 
            	if(tep_not_null($row_orders_detail_print_all["arrival_date"]) && tep_not_null($row_orders_detail_print_all["departure_date"]) && $row_orders_detail_print_all["sent_confirm_email_to_provider"]=="1") {
            	//if($row_orders_detail_print_all['airline_name']!='') {?>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>&nbsp;</td>
							<td><b><?php echo TXT_FLIGHT_NAME_PRINT_A?></b></td>
							<td><b><?php echo TXT_FLIGHT_NUMBER_PRINT_A?></b></td>
							<td><b><?php echo TXT_AIRPORT_NAME_PRINT_A?></b></td>
							<td><b><?php echo TXT_DATE_PRINT_A?></b></td>
							<td><b><?php echo TXT_TIME_PRINT_A?></b></td>
						</tr>
						<tr>
							<td><b><?php echo TITLE_AR;?></b></td>
							<td><?php echo $row_orders_detail_print_all['airline_name'];?></td>
							<td><?php echo $row_orders_detail_print_all['flight_no'];?></td>
							<td><?php echo $row_orders_detail_print_all['airport_name'];  ?></td>
							<td><?php echo tep_date_short($row_orders_detail_print_all['arrival_date']);?></td>
							<td><?php echo $row_orders_detail_print_all['arrival_time']; ?></td>
						</tr>
						<tr>
							<td><b><?php echo TITLE_DR;?></b></td>
							<td><?php echo $row_orders_detail_print_all['airline_name_departure'];?></td>
							<td><?php echo $row_orders_detail_print_all['flight_no_departure'];?></td>
							<td><?php echo $row_orders_detail_print_all['airport_name_departure'];?></td>
							<td><?php echo tep_date_short($row_orders_detail_print_all['departure_date']); ?></td>
							<td><?php echo $row_orders_detail_print_all['departure_time']; ?></td>
						</tr>
					</table>
				<?php  }
				else
						echo TEXT_FLIGHT_NA;?></td>
            </tr>
        </tbody>
        <tbody>
        	<tr>
            	<td><strong>酒店确认码：</strong><?php echo $row_orders_detail_print_all['customer_confirmation_no'];?></td>
                <td><strong>发票号：</strong><?php echo $row_orders_detail_print_all['customer_invoice_no'];?></td>
            </tr>
            <tr>
            	<td><strong>发票总金额：</strong><?php echo $row_orders_detail_print_all['customer_invoice_total'] ?></td>
                <td><strong>发票内容：</strong><?php echo $row_orders_detail_print_all['customer_invoice_comment'] ?></td>
            </tr>
        </tbody>
    </table>
<?php
if($_GET['action']=='eticket_printall'){
	//列出我们与供应商的交流数据{
	$historys = tep_get_provider_order_products_status_history($_GET['opID']);
	if(is_array($historys)){
		?>
<table class="date-msg">
    	<thead>
        	<tr><th colspan="2" style="border:1px solid #FF6600;border-top:0;">联络记录(如遇订单信息与联络记录有冲突的话请以联络记录为准！)<th></tr>
        </thead>
        <tbody>
        	<tr>
            	<td width="50%" style="text-align:center;border:1px solid #FF6600;"><strong><?php echo $historys['heardTitie'][0]?></strong></td>
                <td class="msg-info" style="text-align:center;border:1px solid #FF6600;"><strong><?php echo $historys['heardTitie'][1]?></strong></td>
            </tr>
	<?php
	for($i=0, $n=sizeof($historys)-1; $i<$n; $i++){
		//显示内容以我们发布的内容为主导，如果是供应商回复的内容应该跟到我们右边的单元格，如果是我们回复供应商的则要跳下一行左边单元格显示。
		if(tep_not_null($historys[$i][0]) || tep_not_null($historys[$i+1][1])){
	?>
	<tr>
		<td valign="bottom"style="border:1px solid #FF6600;"><?php if(tep_not_null($historys[$i][0])){ echo $historys[$i][0]['provider_order_status_name'].' - '.nl2br(tep_db_output($historys[$i][0]['provider_comment'])).'<br><b>'.date('n/j/Y H:i:s',strtotime($historys[$i][0]['provider_status_update_date']))." By ".tep_get_admin_customer_name($historys[$i][0]['popc_updated_by'], (int)$historys[$i][0]['popc_user_type']).'</b>'; }?>&nbsp;</td>
		
		<td align="left" valign="bottom" class="msg-info"style="border:1px solid #FF6600;">
		<?php
		if(tep_not_null($historys[$i+1][1])){
			echo $historys[$i+1][1]['provider_order_status_name'].' - '.nl2br(tep_db_output($historys[$i+1][1]['provider_comment'])).'<br><b>'.date('n/j/Y H:i:s',strtotime($historys[$i+1][1]['provider_status_update_date']))." By ".tep_db_output($historys[$i+1][1]['popc_updated_by']).'</b>'; 
		}
		?>
		
		&nbsp;</td>
	</tr>
	<?php
		}
	}
	
	?>
        </tbody>
    </table>
<?php
	}
	// 按 SOFIA 要求 不显示 注意事项 by lwkai 2012-05-30
	/*if (tep_not_null($orders_eticket_result['special_note'])) {
	?>
	<table class="date-context">
    	<tbody>
    	<tr>
            	<td style="border:1px solid #000;border-top:0;"><strong>注意事项：</strong></td>
            </tr>
        	<tr>
            	<td style="border:1px solid #000;">
                	<?php echo nl2br($orders_eticket_result['special_note']); ?>
                </td>
            </tr>
        </tbody>
    </table>
	<?php
	}*/
}
	?>
</div>

<?php  /*  //old code ?>
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main"><script type="text/javascript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo LINK_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo LINK_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?php echo DIR_WS_TEMPLATE_IMAGES;?>close_window.jpg' border=0></a></p></td>
      </tr>
    </table>
<?php
?>
<table width="610" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	<?php 
		$sql_orders_detail_print_all = "SELECT o.orders_id, o.customers_id, o.customers_name, o.customers_telephone, o.date_purchased, o.orders_status, o.payment_method, op.orders_products_id, op.orders_id, op.products_id, p.provider_tour_code, op.products_model, op.products_name, op.products_price, op.final_price, op.products_tax, op.products_quantity, op.products_departure_date, op.products_departure_time, op.products_departure_location, op.products_room_price, op.products_room_info, op.final_price_cost, op.products_room_info, op.customer_seat_no, op.customer_bus_no, op.customer_confirmation_no, op.customer_invoice_no, op.total_room_adult_child_info,op.customer_invoice_total, op.customer_invoice_comment, op.customer_bus_no,op.customer_seat_no,p.provider_tour_code, a.agency_code, os.orders_status_name, f.airline_name, f.flight_no, f.airline_name_departure, f.flight_no_departure, f.airport_name, f.airport_name_departure, f.arrival_date, f.arrival_time, f.departure_date , f.departure_time, f.show_warning_on_admin, pd.products_name_provider, p.products_durations, p.products_durations_type,p.products_vacation_package,p.departure_city_id FROM ".TABLE_ORDERS." o 
	INNER JOIN ".TABLE_ORDERS_PRODUCTS." op ON o.orders_id=op.orders_id 
	INNER JOIN ".TABLE_PRODUCTS." p ON op.products_id=p.products_id AND p.agency_id IN (".$_SESSION['providers_agency_id'].")
	INNER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON p.products_id=pd.products_id AND pd.language_id = 1
	INNER JOIN ".TABLE_TRAVEL_AGENCY." a ON p.agency_id=a.agency_id
	LEFT JOIN ".TABLE_ORDERS_STATUS." os ON os.orders_status_id=o.orders_status
	LEFT JOIN ".TABLE_ORDERS_PRODUCTS_FLIGHT." f ON op.orders_id = f.orders_id AND op.products_id=f.products_id 
	WHERE op.orders_products_id='".$_GET['opID']."'";
	$res_orders_detail_print_all=tep_db_query($sql_orders_detail_print_all);
	$row_orders_detail_print_all=tep_db_fetch_array($res_orders_detail_print_all);

	$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$row_orders_detail_print_all['orders_id'] . "'  and products_id='".$row_orders_detail_print_all['products_id']."' ");
	$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
	print_r($row_orders_detail_print_all);					
	?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td bgcolor="#000000">
					<table width="100%" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000000; ">
					  <tr>
						<td class="borderb paddinglr" valign="top" width="33%"><?php echo TABLE_HEADING_INVOICE_NO.' '.$row_orders_detail_print_all['customer_invoice_no']?><br><?php echo TEXT_INVOICE_TOTAL.' '.$row_orders_detail_print_all['customer_invoice_total']?><br><?php echo TEXT_INVOICE_COMMENT.' '.$row_orders_detail_print_all['customer_invoice_comment'];?><br><?php echo TXT_CONFIRMATION_NO.' '.$row_orders_detail_print_all['customer_confirmation_no'];?></td>
						<td class="borderb borderlr paddinglr" valign="top" width="33%"><?php echo TEXT_PRODUCT_ID;?>&nbsp;
						<?php echo $row_orders_detail_print_all['provider_tour_code'];
						$tour_name_by_provider = tep_get_products_provider_name($row_orders_detail_print_all['products_id'], '1');
							if($tour_name_by_provider != ''){
							echo '<br /> '.$tour_name_by_provider;
							}
						?></td>
						<td class="borderb paddinglr" valign="top" width="33%"><?php echo TEXT_DEPARTURE_DATE;?>&nbsp;<?php echo tep_date_short($row_orders_detail_print_all['products_departure_date']);?><br><?php echo $row_orders_detail_print_all['products_departure_time'];?> <?php echo $row_orders_detail_print_all['products_departure_location'] ?>
						<br><?php echo TEXT_CUST_BUS_NO;?>&nbsp;<?php echo $row_orders_detail_print_all['customer_bus_no'];?>
						<?php if(tep_not_null($row_orders_detail_print_all['customer_seat_no'])){?>
						<?php echo TEXT_CUST_SEAT_NO;?>&nbsp;<?php echo $row_orders_detail_print_all['customer_seat_no'];?>						
						<?php }?>
						</td>
					  </tr>
					  <tr>
						<td class="borderb paddinglr" valign="top"><?php echo STORE_NAME.' '.TEXT_ORDER_ID;?>&nbsp;<?php echo ORDER_EMAIL_PRIFIX_NAME.$row_orders_detail_print_all['orders_id'];?><br><?php echo TEXT_ORDER_DATE;?></strong><?php echo tep_date_short($row_orders_detail_print_all['date_purchased']);?>
						</td>
						<td class="borderb borderlr paddinglr" valign="top"><?php echo TEXT_PRODUCT;?>&nbsp;<?php echo preg_replace('/\*\*.+/','',tep_db_output($row_orders_detail_print_all['products_name'])); //不显示副标题 ?></td>
						<td class="borderb paddinglr" valign="top">
							<?php $qry_product_attributes = "SELECT products_options, products_options_values FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." WHERE orders_products_id = '".$row_orders_detail_print_all['orders_products_id']."'";
								$res_product_attributes = tep_db_query($qry_product_attributes);
								if(tep_db_num_rows($res_product_attributes) > 0){
									while($row_product_attributes = tep_db_fetch_array($res_product_attributes)){
										echo '<nobr>- '.$row_product_attributes['products_options']." : ".$row_product_attributes['products_options_values'].'<nobr><br>';
									}
								} else { echo "&nbsp;";}?>
						</td>
					  </tr>
					  <tr>
						<td class="paddinglr" valign="top">
							<table cellpadding="0" cellspacing="0" width="100%" border="0">
								 <tr><td valign="top" align="left">
								 <?php
								 if(tep_not_null($row_orders_detail_print_all['total_room_adult_child_info'])){
										$total_rooms = get_total_room_from_str($row_orders_detail_print_all['total_room_adult_child_info']);
										 if($total_rooms > 0){
											?>
											<table width="100%" class="login">
												 <tr><td width="34%" align="center" nowrap><?php echo TXT_ETICKET_NUMBER_OF_ROOM;?></td>
												 <td width="33%" align="center"><?php echo TXT_ETICKET_ADULT;?></td>
												 <td width="33%" align="center"><?php echo TXT_ETICKET_CHILD;?></td>
												 </tr>
											<?php
											for($t=1;$t<=$total_rooms;$t++){
												$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_orders_detail_print_all['total_room_adult_child_info'],$t);
												echo '<tr><td align="center">'.$t.'</td><td align="center">'.$chaild_adult_no_arr[0].'</td><td align="center">'.$chaild_adult_no_arr[1].'</td></tr>';
											}
											?></table><?php
										 }else{
												$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_orders_detail_print_all['total_room_adult_child_info'], 1);
												$total_adults = $chaild_adult_no_arr[0];
												$total_children = $chaild_adult_no_arr[1];
												echo '<table width="100%" class="login"><tr><td width="50%" align="center">'.TXT_ETICKET_ADULT.'</td><td width="50%" align="center">'.TXT_ETICKET_CHILD.'</td></tr><tr><td align="center">'.$total_adults.'</td><td align="center">'.$total_children.'</td></tr></table>';
										 }
								 }else{
										 $room_desc = $row_orders_detail_print_all['products_room_info'];
										 $total_rooms = tep_get_total_nos_of_rooms($row_orders_detail_print_all['products_room_info']);
										 if($total_rooms > 0){
											?>
											<table width="100%" ce cellpadding="0" cellspacing="0">
												 <tr><td width="34%" align="center" nowrap><?php echo TXT_ETICKET_NUMBER_OF_ROOM;?></td>
												 <td width="33%" align="center"><?php echo TXT_ETICKET_ADULT;?></td>
												 <td width="33%" align="center"><?php echo TXT_ETICKET_CHILD;?></td>
												 </tr>
											<?php
											for($t=1;$t<=$total_rooms;$t++){
												echo '<tr><td align="center">'.$t.'</td><td align="center">'.tep_get_rooms_adults_childern($row_orders_detail_print_all['products_room_info'],$t,'adult').'</td><td align="center">'.tep_get_rooms_adults_childern($row_orders_detail_print_all['products_room_info'],$t,'children').'</td></tr>';
											}
											?></table><?php
										 }else{
											$total_adults = tep_get_no_adults_childern($row_orders_detail_print_all['products_room_info'],'adult');
											$total_children = tep_get_no_adults_childern($row_orders_detail_print_all['products_room_info'],'children');
											echo '<table width="100%" class="login"><tr><td width="50%" align="center">'.TXT_ETICKET_ADULT.'</td><td width="50%" align="center">'.TXT_ETICKET_CHILD.'</td></tr><tr><td align="center">'.$total_adults.'</td><td align="center">'.$total_children.'</td></tr></table>';
										 } 
								 }//End of check if of total_room_adult_child_info
								 ?>
								 </td>
								 <td></td>
								 </tr>
							 </table>
						</td>
						<td class="borderlr paddinglr" valign="top"><?php echo TEXT_CUSTOMER_NAME;?><br>
						<?php
						$guestnames = explode('<::>',$orders_eticket_result['guest_name']);
						$bodyweights = explode('<::>',$orders_eticket_result['guest_body_weight']);
						$guestgenders = explode('<::>',$orders_eticket_result['guest_gender']);
						if($orders_eticket_result['guest_number']==0 || (is_array($guestnames) &&  !empty($guestnames))){
							foreach($guestnames as $key=>$val){
								$loop = $key;
							}
						}
						else{
							$loop = $orders_eticket_result['guest_number'];		
						}
						
						if(tep_get_product_type_of_product_id($orders_eticket_result['products_id']) == 2){
					
						for($noofguest=1;$noofguest<=$loop;$noofguest++)
							{
								$show_guest_gender = '';
								if(trim($guestgenders[($noofguest-1)]) != ''){
									$show_guest_gender = ' ('.trim($guestgenders[($noofguest-1)]).')';
								}
								?>
								<?php echo $noofguest.'. '; 
								$guest_name_incudes_child_age = explode('||',$guestnames[($noofguest-1)]);
								if(isset($guest_name_incudes_child_age[1])){
								echo '<br>Birth Date'.$noofguest.'.';
								}
								?>
								<br><?php echo 'Guest Weight'.$noofguest.'.'; ?>
								<?php 
								//echo $guestnames[($noofguest-1)]; 
								
								if(isset($guest_name_incudes_child_age[1])){
								echo $guest_name_incudes_child_age[0].$show_guest_gender.'<br>'.$guest_name_incudes_child_age[1].'<br>'; 
								}else{
								echo str_replace("||","",$guestnames[($noofguest-1)]).$show_guest_gender.'<br>'; 
								}
								 echo $bodyweights[($noofguest-1)].'<br>'; ?>
								
							<?php 
								
							} 
						
					
					}else{
							for($noofguest=1;$noofguest<=$loop;$noofguest++)
							{
								$show_guest_gender = '';
								if(trim($guestgenders[($noofguest-1)]) != ''){
									$show_guest_gender = ' ('.trim($guestgenders[($noofguest-1)]).')';
								}
								echo $noofguest.'. ';
								$guest_name_incudes_child_age = explode('||',$guestnames[($noofguest-1)]);
								if(isset($guest_name_incudes_child_age[1])){
								echo '<br>Birth Date'.$noofguest;
								}
													
								if(isset($guest_name_incudes_child_age[1])){
								echo $guest_name_incudes_child_age[0].$show_guest_gender.'<br>'.$guest_name_incudes_child_age[1].'<br>'; 
								}else{
								echo str_replace("||","",$guestnames[($noofguest-1)]).$show_guest_gender.'<br>'; 
								}								
							} 
					}
				

						?></td>
						<td class="paddinglr" valign="top">
						<?php echo TEXT_CUSTOMER_CONTACT_NO.' '.$row_orders_detail_print_all['customers_telephone'].'<br>';
						echo TXT_ETICKET_GUEST_CELL_PHONE_EMERGENCY_ONLY_CELL.' '.tep_customers_cellphone((int)$row_orders_detail_print_all['customers_id']).'<br>';
						?></td>
					  </tr>
					</table>
				</td>
			  </tr>
			  <?php if($row_orders_detail_print_all['airline_name']!='') {?>
			  <tr>
				<td class="borderb borderlr paddinglr">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>&nbsp;</td>
							<td><b><?php echo TXT_FLIGHT_NAME_PRINT_A?></b></td>
							<td><b><?php echo TXT_FLIGHT_NUMBER_PRINT_A?></b></td>
							<td><b><?php echo TXT_AIRPORT_NAME_PRINT_A?></b></td>
							<td><b><?php echo TXT_DATE_PRINT_A?></b></td>
							<td><b><?php echo TXT_TIME_PRINT_A?></b></td>
						</tr>
						<tr>
							<td><b><?php echo TITLE_AR;?></b></td>
							<td><?php echo $row_orders_detail_print_all['airline_name'];?></td>
							<td><?php echo $row_orders_detail_print_all['flight_no'];?></td>
							<td><?php echo $row_orders_detail_print_all['airport_name'];  ?></td>
							<td><?php echo tep_date_short($row_orders_detail_print_all['arrival_date']);?></td>
							<td><?php echo $row_orders_detail_print_all['arrival_time']; ?></td>
						</tr>
						<tr>
							<td><b><?php echo TITLE_DR;?></b></td>
							<td><?php echo $row_orders_detail_print_all['airline_name_departure'];?></td>
							<td><?php echo $row_orders_detail_print_all['flight_no_departure'];?></td>
							<td><?php echo $row_orders_detail_print_all['airport_name_departure'];?></td>
							<td><?php echo tep_date_short($row_orders_detail_print_all['departure_date']); ?></td>
							<td><?php echo $row_orders_detail_print_all['departure_time']; ?></td>
						</tr>
					</table>
				</td>
			  </tr><?php  }
//Ptint only if action=eticket_printall
if($_GET['action']=='eticket_printall'){
	//列出我们与供应商的交流数据{
	$historys = tep_get_provider_order_products_status_history($_GET['opID']);
	if(is_array($historys)){
	?>	
	
	<tr>
	<td class="borderb borderlr">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
	<tbody>
		<tr class="dataTableHeadingRow">
       		<td class="dataTableHeadingContent" align="center" colspan="2" height="30"><b>联络记录</b></td>
    	</tr>
		<tr class="bor_tab table_td_p">
		<td height="30" align="center" class="tab_t tab_line1" style="background-color:#FFF; padding:5px; border-bottom: 1px solid #F4CF91; border-right: 1px solid #F4CF91;">&nbsp;
		<b><?php echo $historys['heardTitie'][0]?></b>		</td>
		<td align="center" class="tab_t tab_line1" style=" background-color:#FEFBED; padding:5px; border-bottom: 1px solid #F4CF91;"><b><?php echo $historys['heardTitie'][1]?></b></td>
	</tr>
	<?php
	for($i=0, $n=sizeof($historys)-1; $i<$n; $i++){
		//显示内容以我们发布的内容为主导，如果是供应商回复的内容应该跟到我们右边的单元格，如果是我们回复供应商的则要跳下一行左边单元格显示。
		if(tep_not_null($historys[$i][0]) || tep_not_null($historys[$i+1][1])){
	?>
	<tr>
		<td valign="bottom" style="width:50%; background-color:#FFF; padding:5px; border-bottom: 1px solid #F4CF91; border-right: 1px solid #F4CF91;"><?php if(tep_not_null($historys[$i][0])){ echo $historys[$i][0]['provider_order_status_name'].' - '.nl2br(tep_db_output($historys[$i][0]['provider_comment'])).'<br><b>'.date('n/j/Y H:i:s',strtotime($historys[$i][0]['provider_status_update_date']))." ".tep_get_admin_customer_name($historys[$i][0]['popc_updated_by'], (int)$historys[$i][0]['popc_user_type']).'</b>'; }?>&nbsp;</td>
		
		<td align="left" valign="bottom" style="width:50%; background-color:#FEFBED; padding:5px; border-bottom: 1px solid #F4CF91;">
		<?php
		if(tep_not_null($historys[$i+1][1])){
			echo $historys[$i+1][1]['provider_order_status_name'].' - '.nl2br(tep_db_output($historys[$i+1][1]['provider_comment'])).'<br><b>'.date('n/j/Y H:i:s',strtotime($historys[$i+1][1]['provider_status_update_date']))." ".tep_db_output($historys[$i+1][1]['popc_updated_by']).'</b>'; 
		}
		?>
		
		&nbsp;</td>
	</tr>
	<?php
		}
	}
	
	?>
		</tbody>
		</table>
		</td>
	</tr>
	<?php
	}
	//列出我们与供应商的交流数据}
				
				//打印页面不需要行程信息
				/*
				$arr_highlight_agency_details=array(3,81,82,33,46,47);
				$languages_id=1;
				$tour_arrangement = $orders_eticket_result['tour_arrangement'];
				if($orders_eticket_result['tour_arrangement'] == ''){
					if($display_eticket_itinerary_new_format==true && $forceelase == true){
						$suggested_tour_itinerary_date = $row_orders_detail_print_all['products_departure_date'];				
						$suggested_tour_itinerary_pick_up_time = $row_orders_detail_print_all['products_departure_time'].' '.$row_orders_detail_print_all['products_departure_location'];				
						$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($row_orders_detail_print_all['products_id'],$languages_id);				
						$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($row_orders_detail_print_all['products_id'],$languages_id);		
						$suggested_tour_eticket_notes = $special_note_itinerary.'<br /><br />'.tep_get_products_eticket_notes($row_orders_detail_print_all['products_id'],$languages_id);
						$suggested_tour_eticket_local_contact="";
						if($orders_eticket_result['tour_provider'] != ''){
							$suggested_tour_eticket_local_contact.=stripslashes($orders_eticket_result['tour_provider']);
						}else{
							$suggested_tour_eticket_local_contact.=$agency_result['agency_name'].' '.TXT_ETICKET_TEL.' '.$agency_result['phone'];
						}
						$suggested_tour_eticket_local_contact.="<br><br>".TXT_ETICKET_EMERGENCY_CONTACT_PERSON."<br>";
						if($orders_eticket_result['emergency_contact_person'] != ''){
							$suggested_tour_eticket_local_contact.=stripslashes($orders_eticket_result['emergency_contact_person']);
						}else{
							$suggested_tour_eticket_local_contact.=stripslashes($agency_result['emerency_contactperson']);
						}
						$suggested_tour_eticket_local_contact.="<br><br>".TXT_ETICKET_EMERGENCY_CONTACT_NUMBER."<br>";
						if($orders_eticket_result['emergency_contact_no'] != ''){
							$suggested_tour_eticket_local_contact.=stripslashes($orders_eticket_result['emergency_contact_no']);
						}else{
							$suggested_tour_eticket_local_contact.=stripslashes($agency_result['emerency_number']);
						}
						
						$suggested_tour_eticket_local_contact_arr_explode = explode('!##!',$suggested_tour_eticket_local_contact);
						$suggested_tour_eticket_itinerary_arr_explode = explode('!##!',$suggested_tour_eticket_itinerary);
						$suggested_tour_eticket_hotel_arr_explode = explode('!##!',$suggested_tour_eticket_hotel);
						$suggested_tour_eticket_notes_arr_explode = explode('!##!',$suggested_tour_eticket_notes);
						
						$exra_auto_row_load_eticke ='';
						for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {
							if($lp_i > 0){
							$suggested_tour_itinerary_pick_up_time = '';
							}
								$exra_auto_row_load_eticke .= '<TR valign="top">
			
													<TD>'.date_add_day($lp_i,'d',$suggested_tour_itinerary_date).'<br /><br />'.$suggested_tour_itinerary_pick_up_time.'&nbsp;</TD>
													<TD>'.$suggested_tour_eticket_local_contact_arr_explode[$lp_i].'&nbsp;</TD>
													
													<TD>'.$suggested_tour_eticket_itinerary_arr_explode[$lp_i].'&nbsp;</TD>
													
													<TD>'.$suggested_tour_eticket_hotel_arr_explode[$lp_i].'&nbsp;</TD>
													
													<TD>'.$suggested_tour_eticket_notes_arr_explode[$lp_i].'&nbsp;</TD></TR>';
											
						}
					}else{
		
					$suggested_tour_itinerary_date = date('m/d/Y', strtotime($row_orders_detail_print_all['products_departure_date']));
					$suggested_tour_itinerary_pick_up_time = $row_orders_detail_print_all['products_departure_time'].' '.$row_orders_detail_print_all['products_departure_location'];
					if(in_array($agency_id, $arr_highlight_agency_details)){				
						$suggested_tour_itinerary_pick_up_time = '<span '.$style_highlight_agency.' >'.$row_orders_detail_print_all['products_departure_time'].' '.$row_orders_detail_print_all['products_departure_location'].'</span>';	
					}		
					
					$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($row_orders_detail_print_all['products_id'],$languages_id);				
					$suggested_tour_eticket_pickup_details = tep_get_products_eticket_pickup($row_orders_detail_print_all['products_id'],$languages_id);
					$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($row_orders_detail_print_all['products_id'],$languages_id);		
					$suggested_tour_eticket_notes = tep_get_products_eticket_notes($row_orders_detail_print_all['products_id'],$languages_id);			
				
					$suggested_tour_eticket_itinerary_arr_explode = explode('!##!',$suggested_tour_eticket_itinerary);
					$suggested_tour_eticket_pickup_details_arr_explode = explode('!##!',$suggested_tour_eticket_pickup_details);
					$suggested_tour_eticket_hotel_arr_explode = explode('!##!',$suggested_tour_eticket_hotel);
					$suggested_tour_eticket_notes_arr_explode = explode('!##!',$suggested_tour_eticket_notes);
				
					$exra_auto_row_load_eticke ='';
					for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {
						
						if($lp_i > 0){
							$suggested_tour_itinerary_pick_up_time = '';
						}else{
							if($row_orders_detail_print_all['products_vacation_package']){
								$suggested_tour_eticket_notes_arr_explode[$lp_i] = ''.$suggested_tour_eticket_notes_arr_explode[$lp_i];
							}else{
								$suggested_tour_eticket_notes_arr_explode[$lp_i] = TXT_ETICKET_NOTE_CALL_TO_RECONFIRM1.$suggested_tour_eticket_notes_arr_explode[$lp_i];
							}
						}
						$exra_auto_row_load_eticke .= '
						<tr>
							<td style="padding-top:10px">
							<table width="100%" border="1" cellpadding="2" cellspacing="0" >
								<tr><td colspan="2" bgcolor="#CCCCCC" height="25" style="padding:5px 10px"><b>'.TXT_DAY_ETICKIT .($lp_i+1).'</b></td></tr>
								<tr><td colspan="2"><table width="100%"><tr><td width="20%" height="25">'.TXT_DATE_ETICKIT.'</td>
								<td width="80%" height="25">'.date_add_day($lp_i,'d',$suggested_tour_itinerary_date).'&nbsp;</td></tr></table></td>
								</tr>
								<tr><td colspan="2"><table width="100%"><tr><td width="20%" height="25">'.TXT_PICKUP_DETAILS_ETICKIT.'</td><td width="80%" height="25" valign="top">'.($suggested_tour_itinerary_pick_up_time != '' ? $suggested_tour_itinerary_pick_up_time.'<br />'.$suggested_tour_eticket_pickup_details_arr_explode[$lp_i] : $suggested_tour_eticket_pickup_details_arr_explode[$lp_i]).'</td></tr></table></td></tr>
								
								<tr><td colspan="2"><table width="100%"><tr><td width="20%" height="25">'.TXT_ETICKET_ITINERARY.'</td><td width="80%" height="25">'.$suggested_tour_eticket_itinerary_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
								<tr><td colspan="2"><table width="100%"><tr><td valign="top" width="20%" height="25">'.TXT_ETICKET_HOTEL.'</td>
								<td width="80%" height="25">'.$suggested_tour_eticket_hotel_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
								<tr><td colspan="2"><table width="100%"><tr><td valign="top" width="20%" height="25">'.TXT_ETICKET_NOTE.'</td>
								<td width="80%" height="25">'.$suggested_tour_eticket_notes_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
							</table>
							</td>
						</tr>';
												
										
					} // end for loop
				}
			
				$tour_arrangement = '<TABLE width="100%" border="0">'.$exra_auto_row_load_eticke.'</TABLE>';
				}
				
				
			  if($tour_arrangement != '') {
			  ?>
			   <tr>
				<td class="borderb borderlr paddinglr">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><?php 
							echo str_replace($agency_name, '<b><font size="3">'.$agency_name.'</font></b>', stripslashes($tour_arrangement));
							?>
						</td>
					</tr></table>
				</td>
			  </tr> 
			  <?php  } * /?>
			  <?php
			  if($orders_eticket_result['special_note'] != '') {
			  ?>
			  <tr>
				<td class="borderb borderlr paddinglr">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
								<?php echo nl2br($orders_eticket_result['special_note']); ?>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
			  <?php  } 
}//action=eticket_printall?>
		</table>
	</td>
  </tr>
</table> <?php  */ ?>
</body>
</html>