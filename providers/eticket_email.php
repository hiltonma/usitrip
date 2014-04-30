<?php 
for($i=0; $i<count($order->products); $i++){
	if(isset($_GET['orders_products_id']) && $_GET['orders_products_id'] != ''){
		if($order->products[$i]['orders_products_id']==$_GET['orders_products_id']){
			break;
		}
	}else{
		if($order->products[$i]['id']==$_GET['products_id']){
			break;
		}
	}/*
	if($order->products[$i]['id']==$_GET['products_id']){
	break;
	}*/
}
$languages_id = 1;
//$i = $_GET['i'];
$order->products[$i]['orders_products_id'] = (int)$order->products[$i]['orders_products_id'];
$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_products_id = '".$order->products[$i]['orders_products_id']."' "); //orders_id = '" . (int)$oID . "'  and products_id=".$_GET['products_id']."
$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
$the_extra_query= tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
$the_extra= tep_db_fetch_array($the_extra_query);
$the_customers_id= $the_extra['customers_id'];

$agency_name=tep_get_tour_agency_name_from_product_id($orders_eticket_result['products_id']);
$display_eticket_itinerary_new_format=false;
if((int)$oID > 10650){
	$display_eticket_itinerary_new_format=true;
}

//从供应商数据取得资料
$agency_query = tep_db_query("select a.*,p.products_vacation_package from ".TABLE_PRODUCTS." as p, ".TABLE_TRAVEL_AGENCY." as a where p.products_id = '" . (int)$order->products[$i]['id'] . "' and p.agency_id = a.agency_id ");
$agency_result = tep_db_fetch_array($agency_query);

  /*//延住房间需求 strart{
  $extended_hotel_query = tep_db_query('SELECT * FROM orders_products_extended_hotel WHERE orders_products_id='.$order->products[$i]['orders_products_id'].' AND orders_id='.$oID);
  $extended_hotel_num = tep_db_num_rows($extended_hotel_query);
  $n = 0;
  while($extended_hotel_info = tep_db_fetch_array($extended_hotel_query)){
  	if(tep_not_null($extended_hotel_info['hotel_confirm_number']) && tep_not_null($extended_hotel_info['hotel_room_info'])){
  		$extended_hotel_arr = explode(',', $extended_hotel_info['hotel_room_info']);
  		$hotel_room_num = count($extended_hotel_arr);
  		$hotel_pep_num = array_sum($extended_hotel_arr);
  		$hotel_query = tep_db_query('SELECT hotel_address,hotel_phone FROM `hotel` WHERE hotel_id="'.(int)$extended_hotel_info['hotel_id'].'" ');
  		$hotel_info = tep_db_fetch_array($hotel_query);
  		$n++;
  ?>
  <tr>
	<td colspan="3" style="padding:10px 10px 5px; border-bottom:#777777 dashed 1px;" >
	   <table width="100%">
		   <tr><td colspan="2">
		   <b>延住房间需求 <?php if($extended_hotel_num>1)echo $n;?></b>
		   </td></tr>
		   <tr><td style="padding:0px 10px;" valign="top">
				<table width="100%" style="line-height:18px;">
				  <tr>
				  	<td width="10%">延住模式：</td>
				  	<td width="22%"><?php if($extended_hotel_info['eh_type']=='before')echo '提前入住';else echo '参团延住'; ?></td>
				  	<td width="10%">预定人名：</td>
				  	<td width="23%"><?php echo $extended_hotel_info['customer_name']; ?></td>
				  	<td width="10%">预定手机：</td>
					<td width="25%"><?php echo $extended_hotel_info['customer_mobile']; ?></td>
				  </tr>
				  <tr>
				  	<td>酒店名称：</td>
					<td colspan="5"><?php echo $extended_hotel_info['hotel_name']; ?></td>
				  </tr>
				  <tr>
				  	<td>酒店地址：</td>
					<td colspan="5"><?php echo $hotel_info['hotel_address']; ?></td>
				  </tr>
				  <tr>
				  	<td>入住日期：</td>
				  	<td><?php echo $extended_hotel_info['start_date']; ?></td>
				  	<td>离店日期：</td>
				  	<td><?php echo $extended_hotel_info['end_date']; ?></td>
				  	<td>入住人数：</td>
					<td><?php echo $hotel_pep_num; ?></td>
				  </tr>
				  <tr>
				  	<td>酒店号码：</td>
					<td><?php echo $extended_hotel_info['hotel_confirm_number']; ?></td>
				  	<td>酒店电话：</td>
					<td><?php echo $hotel_info['hotel_phone']; ?></td>
				  	<td>房间数：</td>
					<td><?php echo $hotel_room_num; ?></td>
				  </tr>
				</table>
		   </td>
		   <td></td>
		   </tr>
	   </table>
	</td>
  </tr>
  <?php
  	}
  }
  //延住房间需求 end}*/
  
  $special_note = stripslashes($orders_eticket_result['special_note']);

  if($special_result['products_vacation_package'] == '1'){
  	if($display_eticket_itinerary_new_format==true){
  		$special_note_itinerary = TXT_ETICKET_SELF_TRANSFER_NOTE;
  	}
  }

  $tour_arrangement = $orders_eticket_result['tour_arrangement'];
  if($_GET['neweticket']=='1'){
  	$orders_eticket_result['tour_arrangement']='';
  }

?>

<table cellpadding="0" cellspacing="0" border="0" width="715" align="center">
	<tbody>
    	<tr>
        	<td style="border-bottom:3px solid #000099;padding-bottom:7px;"><img src="<?php echo HTTP_SERVER?>/image/et_logo.png" width="180" height="70" /></td>
            <td style="border-bottom:3px solid #000099;padding-bottom:7px;text-align:right;">
            	<div style="margin:0;padding:0;text-align:right;">
                	<img src="<?php echo HTTP_SERVER?>/image/et_plus.png" width="40" height="40" style="float:right;margin-left:5px;margin-top:8px;" />
                	<p style="display:inline;margin:0;color:#000099;line-height:28px;font-family:'Microsoft Yahei';font-size:14px;font-weight:bold;">全球华人首选出国旅游网站<br />美国BBB认证最高商誉评级</p>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="color:#333333;">
    <tbody>
    	<tr>
        	<td>
            	<h1 style="margim:0;padding:0;font-family:Tahoma;font-size:26px;height:28px;line-height:28px;">UsiTrip · 走四方 【参团凭证】</h1>
                <p style="margin:0;padding:0;font-size:12px;">(请打印并于参团当日携带)</p>
            </td>
            <td style="text-align:right;">
            	<h2 style="margin:0;padding:0;font-family:Tahoma;font-size:22px;height:28px;line-height:28px;">订单号：<?php echo ORDER_EMAIL_PRIFIX_NAME . $oID;?></h2>
                <p style="margin:0;padding:0;font-size:18px;font-weight:bold;padding-top:10px;">确认码：<?php echo $order->products[$i]['customer_confirmation_no'];?></p>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="border:1px solid #548DD4;margin-top:10px;font-size:12px;color:#333333;">
	<thead>
    	<tr style="background:#4F81BD;line-height:24px;">
        	<th colspan="6" style="text-align:left;text-indent:5px;color:#ffffff;font-size:14px;">基本信息</th>
        </tr>
    </thead>
    <tbody>
    	<tr style="line-height:26px; font-size:12px;">
        	<td width="90" style="border-top:1px solid #548DD4;text-align:center;">旅游团号</td>
            <td width="430" colspan="3" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:10px;">
			<b><?php echo $order->products[$i]['model'];?></b>
			</td>
            <td width="90" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">出发日期</td>
            <td width="105" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">
			<b>
			<?php
			$_departure_strtotime = strtotime($order->products[$i]['products_departure_date']);
			echo date('Y-m-d',$_departure_strtotime);
			?>
			</b>
			</td>
        </tr>
        <tr style="line-height:26px; font-size:12px;">
        	<td width="90" style="border-top:1px solid #548DD4;text-align:center;">参团线路</td>
            <td width="430" colspan="3" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:10px;">
			<?php echo $order->products[$i]['name'];?>
			</td>
            <td width="90" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">当日游客手机</td>
            <td width="105" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;"><?php echo $order->info['guest_emergency_cell_phone'];?>&nbsp;</td>
        </tr>
        <?php
		//客人信息
		$show_guest = true;
		
		if($show_guest){
		?>
		<tr>
        	<td colspan="6" style="font-size:12px;">
            	<?php
				//房间总数信息 start{
				$total_room_info = tep_get_room_info_array_from_str($order->products[$i]['total_room_adult_child_info']);
					//只有某行有数据该都有6列
				?>
				<table cellpadding="0" cellspacing="0" border="0">
                	<tbody>
                    	<tr style="line-height:26px; font-size:12px;">
                            <?php
							$n=$total_room_info['room_total'];
							if($n%3!=0) $n = $n+(3-($n%3));
							
							for($j=0; $j<$n; $j++){
								//有房间
								$roomTite = '房间'.($j+1);
								$roomNumConten = "";
								if((int)$total_room_info[$j]['adultNum']){
									$roomNumConten = '成年人（'.$total_room_info[$j]['adultNum'].'）儿童（'.$total_room_info[$j]['childNum'].'）';
								}
								//无房间
								if(!(int)$total_room_info['room_total']){
									$roomTite = '参团人';
									$roomNumConten = '成年人（'.$total_room_info['adult_num'].'）儿童（'.$total_room_info['child_num'].'）';
								}
								if(!(int)$total_room_info[$j]['adultNum'] && !(int)$total_room_info['adult_num'] && (int)$j){
									$roomTite = $roomNumConten = "";
								}
								$_border_left = 'border-left:1px solid #548DD4;';
								if($j%3==0){
									$_border_left = '';
									echo '</tr><tr style="line-height:26px; font-size:12px;">';
								}
							?>
							<td width="60" style="border-top:1px solid #548DD4; <?php echo $_border_left;?> text-align:center;">
							<?php echo $roomTite;?>
							<span style="color:#FFFFFF; font-size:0px; height:0px;">.</span>						
							</td>
                            <td width="180" style="border-top:1px solid #548DD4; border-left:1px solid #548DD4; text-align:center;">
							<?php
							echo $roomNumConten;
							?>
							<span style="color:#FFFFFF; font-size:0px; height:0px;">.</span>
							</td>
                            <?php
							}
							
							?>
                        </tr>
                    </tbody>
                </table>
				<?php
				//房间总数信息 end}
				?>
				
            </td>
        </tr>
        <tr>
            <td colspan="6" style="font-size:12px;">
			<?php 
				$guestnames = explode('<::>',$orders_eticket_result['guest_name']);
				$bodyweights = explode('<::>',$orders_eticket_result['guest_body_weight']);
				$guestgenders = explode('<::>',$orders_eticket_result['guest_gender']);
				$guestheight = explode('<::>',$orders_eticket_result['guest_body_height']);
			?> 
            	<table cellpadding="0" cellspacing="0" border="0">
                	<tbody>
						<tr style="line-height:26px; font-size:12px;">
						<?php
						$n=count($guestnames);
						if($n%3!=0) $n = $n+(3-($n%3));
						
						for($j=0; $j<$n; $j++){
							//有房间
							$guestTite = '游客'.($j+1);
							$guestConten = "";
							if(tep_not_null($guestnames[$j])){
								//$guestConten = $guestnames[$j];
								
								$show_guest_gender = '';
								if(trim($guestgenders[$j]) != ''){
									$show_guest_gender = ' ('.trim($guestgenders[$j]).')';
								}
								$guest_name_incudes_child_age = explode('||',$guestnames[$j]);
								if(isset($guest_name_incudes_child_age[1])){
									if($order->products[$i]['products_departure_date'] != ''){
										$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($order->products[$i]['products_departure_date'],0,10)));
									}
									$guestConten = stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender) .'('.$di_childage_difference_in_year.')';
								}else{
									$guestConten = stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender);
								}
								
							}
							
							if(!tep_not_null($guestnames[$j]) && (int)$j){
								$guestTite = $guestConten = "";
							}
							$_border_left = 'border-left:1px solid #548DD4;';
							if($j%3==0){
								$_border_left = '';
								echo '</tr><tr style="line-height:26px; font-size:12px;">';
							}
						?>
						<td width="60" style="border-top:1px solid #548DD4; <?php echo $_border_left;?> text-align:center;">
						<?php echo $guestTite;?>
						<span style="color:#FFFFFF; font-size:0px; height:0px;">.</span>						
						</td>
						<td width="180" style="border-top:1px solid #548DD4; border-left:1px solid #548DD4; text-align:center;">
						<?php
						echo $guestConten;
						?>
						<span style="color:#FFFFFF; font-size:0px; height:0px;">.</span>
						</td>
						<?php
						}
						?>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
		<?php }?>
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="border:1px solid #548DD4;font-size:12px;margin-top:10px;color:#333333;">
	<thead>
    	<tr style="background:#4F81BD;line-height:24px;">
        	<th colspan="4" style="text-align:left;text-indent:5px;color:#ffffff;font-size:14px;">航班/乘车信息</th>
        </tr>
    </thead>
    <tbody>

<?php
//航班信息 start{
$orders_history_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . tep_db_input($oID) . "'  and  orders_products_id=".$order->products[$i]['orders_products_id']." and (airline_name != '' or airline_name_departure != '' or flight_no != '' or flight_no_departure != '' or airport_name != '' or airport_name_departure != '') and sent_confirm_email_to_provider='1' ");//orders_id = '" . tep_db_input($oID) . "'  and products_id=".$_GET['products_id']."
if (tep_db_num_rows($orders_history_query)) {
	while ($orders_history = tep_db_fetch_array($orders_history_query)){
?>	
    	<tr style="line-height:26px; font-size:12px;">
        	<td width="75" style="border-top:1px solid #548DD4;text-align:center;">接机日期</td>
            <td width="290" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo tep_get_date_disp($orders_history['arrival_date']); ?>&nbsp;
			</td>
            <td width="75" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">送机日期</td>
            <td style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo tep_get_date_disp($orders_history['departure_date']); ?>&nbsp;
			</td>
        </tr>
        <tr style="line-height:26px; font-size:12px;">
        	<td width="75" style="border-top:1px solid #548DD4;text-align:center;">航空公司</td>
            <td width="290" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['airline_name']; ?>&nbsp;
			</td>
            <td width="75" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">航空公司</td>
            <td style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['airline_name_departure']; ?>&nbsp;
			</td>
        </tr>
        <tr style="line-height:26px; font-size:12px;">
        	<td width="75" style="border-top:1px solid #548DD4;text-align:center;">接机航班</td>
            <td width="290" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['flight_no']; ?>&nbsp;
			</td>
            <td width="75" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">送机航班</td>
            <td style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['flight_no_departure']; ?>&nbsp;
			</td>
        </tr>
        <tr style="line-height:26px; font-size:12px;">
        	<td width="75" style="border-top:1px solid #548DD4;text-align:center;">接机机场</td>
            <td width="290" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['airport_name']; ?>&nbsp;
			</td>
            <td width="75" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">送机机场</td>
            <td style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['airport_name_departure']; ?>&nbsp;
			</td>
        </tr>
        <tr style="line-height:26px; font-size:12px;">
        	<td width="75" style="border-top:1px solid #548DD4;text-align:center;">到达时间</td>
            <td width="290" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['arrival_time']; ?>&nbsp;
			</td>
            <td width="75" style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-align:center;">出发时间</td>
            <td style="border-top:1px solid #548DD4;border-left:1px solid #548DD4;text-indent:5px;">
			<?php echo $orders_history['departure_time']; ?>&nbsp;
			</td>
        </tr>
<?php
	}// end of while loop
}//end of if
//航班信息 end}
//上车地址 {
if($orders_eticket_result['depature_full_address'] != '') {
$depature_full_address = $orders_eticket_result['depature_full_address'];
$get_total_departure_date_only = substr($depature_full_address,0,10);
	if($get_total_departure_date_only != '' && $get_total_departure_date_only != '0000-00-00'){
		$depature_full_address = tep_get_date_disp($get_total_departure_date_only).' '.str_replace($get_total_departure_date_only,'',$depature_full_address);
	}
}else{
	$orders_fulladdress_query = tep_db_query("select * from products_departure where departure_time = '" . $order->products[$i]['products_departure_time'] . "' and departure_address = '".$order->products[$i]['products_departure_location']."' and products_id = ".(int)$order->products[$i]['id']." ");
	$orders_fulladdress = tep_db_fetch_array($orders_fulladdress_query);
	$depature_full_address =  tep_get_date_disp($order->products[$i]['products_departure_date']).' &nbsp; '.$order->products[$i]['products_departure_time'].' &nbsp; '.$orders_fulladdress['departure_full_address'];

}
if(tep_not_null($depature_full_address)){
?>
<tr style="line-height:26px; font-size:12px;">
		<td colspan="4" style="padding:5px; border-top:1px solid #548DD4;">
<?php 
	echo '上车地址：'.$depature_full_address;
?>
		</td>
</tr>
<?php
}
?>
</tbody>
<?php
//}
?>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="border:1px solid #548DD4;font-size:12px;margin-top:10px;color:#333333;">
	<thead>
    	<tr style="background:#4F81BD;line-height:24px;font-size:14px;">
        	<th style="text-align:left;text-indent:5px;color:#ffffff;">行程和酒店信息</th>
        </tr>
    </thead>
    <tbody>
    	<tr style="line-height:22px;padding:5px 0;">
        	<td style="padding:5px;">
            	
		<?php
		if($orders_eticket_result['tour_arrangement'] == '' && (int)$order->products[$i]['is_hotel']==0 && $order->products[$i]['is_transfer'] == 0){
			if($display_eticket_itinerary_new_format==true && $forceelase == true){

				$suggested_tour_itinerary_date = $order->products[$i]['products_departure_date'];
				$suggested_tour_itinerary_pick_up_time = $order->products[$i]['products_departure_time'].' '.$order->products[$i]['products_departure_location'];
				$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($order->products[$i]['id'],$languages_id);
				$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($order->products[$i]['id'],$languages_id);
				$suggested_tour_eticket_notes = $special_note_itinerary.'<br /><br />'.tep_get_products_eticket_notes($order->products[$i]['id'],$languages_id);
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
											
											<TD><span style="color:#F7860F">'.$suggested_tour_eticket_notes_arr_explode[$lp_i].'&nbsp;</span></TD></TR>';

				}
			}else{

				$suggested_tour_itinerary_date = $order->products[$i]['products_departure_date'];
				$suggested_tour_itinerary_pick_up_time = $order->products[$i]['products_departure_time'].' '.$order->products[$i]['products_departure_location'];
				if(in_array($agency_result['agency_id'], (array)$arr_highlight_agency_details)){
					$suggested_tour_itinerary_pick_up_time = '<span '.$style_highlight_agency.' >'.$order->products[$i]['products_departure_time'].' '.$order->products[$i]['products_departure_location'].'</span>';
				}

				$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($order->products[$i]['id'],$languages_id);
				$suggested_tour_eticket_pickup_details = tep_get_products_eticket_pickup($order->products[$i]['id'],$languages_id);
				$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($order->products[$i]['id'],$languages_id);
				$suggested_tour_eticket_notes = tep_get_products_eticket_notes($order->products[$i]['id'],$languages_id);

				$suggested_tour_eticket_itinerary_arr_explode = explode('!##!',$suggested_tour_eticket_itinerary);
				$suggested_tour_eticket_pickup_details_arr_explode = explode('!##!',$suggested_tour_eticket_pickup_details);
				$suggested_tour_eticket_hotel_arr_explode = explode('!##!',$suggested_tour_eticket_hotel);
				$suggested_tour_eticket_notes_arr_explode = explode('!##!',$suggested_tour_eticket_notes);

				$exra_auto_row_load_eticke ='';
				for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {

					if($lp_i > 0){
						$suggested_tour_itinerary_pick_up_time = '';
					}else{
						if($agency_result['products_vacation_package']){
							$suggested_tour_eticket_notes_arr_explode[$lp_i] = ''.$suggested_tour_eticket_notes_arr_explode[$lp_i];
						}else{
							$suggested_tour_eticket_notes_arr_explode[$lp_i] = TXT_ETICKET_NOTE_CALL_TO_RECONFIRM1.$suggested_tour_eticket_notes_arr_explode[$lp_i];
						}
					}
					
					$exra_auto_row_load_eticke .= '
						<tr>
						<td style="padding-top:10px">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" >
						<tr><td colspan="2" bgcolor="#F7F7F7" height="25" style="padding:5px 10px"><b>' . sprintf(TXT_DAY_ETICKIT, ($lp_i + 1)) .' '. $suggested_tour_eticket_itinerary_arr_explode[$lp_i] .'</b></td></tr>
						';
					if(tep_not_null($suggested_tour_eticket_hotel_arr_explode[$lp_i])){	//酒店信息
						$exra_auto_row_load_eticke .= '<tr><td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="65" height="25">' . TXT_ETICKET_HOTEL . '</td>
														<td height="25">' . $suggested_tour_eticket_hotel_arr_explode[$lp_i] . '&nbsp;</td></tr></table></td></tr>';
					}
					if(tep_not_null($suggested_tour_eticket_pickup_details_arr_explode[$lp_i].$suggested_tour_eticket_pickup_details_arr_explode[$lp_i])){	//上车信息
						$exra_auto_row_load_eticke .= '<tr><td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="65" height="25">' . TXT_ETICKET_PICK_UP_DETAILS . '</td><td height="25" valign="top">' . ($suggested_tour_itinerary_pick_up_time != '' ? $suggested_tour_itinerary_pick_up_time . '<br />' . $suggested_tour_eticket_pickup_details_arr_explode[$lp_i] : $suggested_tour_eticket_pickup_details_arr_explode[$lp_i]) . '</td></tr></table></td></tr>';
					}
					
					$exra_auto_row_load_eticke .=
							'</table>
							</td>
							</tr>';

					/*
					$exra_auto_row_load_eticke .= '
				<tr>
					<td style="padding-top:10px">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" >
					  <tr>
					  	<td colspan="2" bgcolor="#F7F7F7" height="25" style="padding:5px 10px"><b>'.sprintf(TXT_DAY_ETICKIT, ($lp_i+1)).' '.$suggested_tour_eticket_itinerary_arr_explode[$lp_i].'</b></td></tr>
					  <tr><td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" ><tr><td width="20%" height="25">'.TXT_ETICKET_HOTEL.'</td>
					  <td width="80%" height="25">'.$suggested_tour_eticket_hotel_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
					  <tr><td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" ><tr><td width="20%" height="25">'.TXT_PICKUP_DETAILS_ETICKIT.'</td><td width="80%" height="25">'.($suggested_tour_itinerary_pick_up_time != '' ? $suggested_tour_itinerary_pick_up_time.'<br />'.$suggested_tour_eticket_pickup_details_arr_explode[$lp_i] : $suggested_tour_eticket_pickup_details_arr_explode[$lp_i]).'</td></tr></table></td></tr>
					</table>
					</td>
				</tr>';
					*/


				} // end for loop
			}
			$tour_arrangement = '<TABLE width="100%" border="0" border="0" cellspacing="0" cellpadding="0" >'.$exra_auto_row_load_eticke.'</TABLE>';
		}
		echo stripslashes($tour_arrangement);
		?>
			</td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="border:1px solid #548DD4;font-size:12px;margin-top:10px;color:#333333;">
	<thead>
    	<tr style="background:#4F81BD;line-height:24px;font-size:14px;">
        	<th style="text-align:left;text-indent:5px;color:#ffffff;">参团须知</th>
        </tr>
    </thead>
    <tbody>
    	<tr style="line-height:22px;padding:5px 0;">
        	<td style="padding:5px;">
          		<p style="margin:0;padding:0">
				<?php
				$eticket_comment = $orders_eticket_result['eticket_comment'];
				//echo nl2br($agency_result['providers_default_eticket_comment']);
				echo nl2br($eticket_comment);
				?>
				</p>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="border:1px solid #548DD4;font-size:12px;margin-top:10px;color:#333333;">
	<thead>
    	<tr style="background:#4F81BD;line-height:24px;font-size:14px;">
        	<th style="text-align:left;text-indent:5px;color:#ffffff;">温馨提示</th>
        </tr>
    </thead>
    <tbody>
    	<tr style="line-height:22px;padding:5px 0;">
            <td style="padding:5px;">
          		<p style="margin:0;padding:0">
				<?php echo nl2br($special_note);?>
				</p>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center" style="font-size:14px;color:#333333;">
    <tbody>
    	<tr style="line-height:24px;">
        	<td style="padding:10px;">
            	<p style="margin:0;padding:0;">
                	------------<br />
                    感谢您对走四方旅游网（usitrip.com）的信赖与支持！<br />
               		我们始终坚持为您提供最贴心最满意的旅游服务，在您的旅途中，有任何需求，请随时联系我们的服务人员。<br />
                    Usitrip走四方祝您旅途愉快！<br />
                    <br />
                    <span style="text-decoration:underline;font-weight:bold;">208.109.123.18|走四方旅游网</span><br />
                    Your trip , Our care!<br />
                    您的旅行,我的关注!
                </p>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="715" align="center">
	<tbody>
    	<tr style="line-height:24px;font-family:Tahoma;font-weight:bold;font-size:12px;color:#000099;">
        	<td style="border-top:3px solid #000099;text-align:left;">
            	<p style="margin:0;padding:0;">美加(热线):888-887-2816  FAX:(001) 225-757-1340<br />中国(热线):4006-333-926  FAX:(0086)0755-23036129</p>
            </td>
            <td style="border-top:3px solid #000099;text-align:right;">
            	<p style="margin:0;padding:0;">Unitedstars International Ltd<br />208.109.123.18|走四方旅游网</p>
            </td>
        </tr>
    </tbody>
</table>
