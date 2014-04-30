<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<?php echo tep_draw_form('etickets', 'c_orders.php', tep_get_all_get_params(array('action')) . 'action=add_product', 'post', 'onsubmit="return validation_guest();"');
?>
<table border="0" width="98%" cellspacing="2" cellpadding="2">
	<tr>
		<td class="main">
			
			<table width="100%" cellpadding="3" cellspacing="0">
				<tr>
					<td  class="dataTableContent" colspan="4"><b>Guest Name</b>
					<input type="hidden" name="g_number" value="<?php echo $total_no_guest_tour;?>"/></td>
				</tr>
				<?php 
				for($noofguest=1;$noofguest<=$total_no_guest_tour;$noofguest++)
				{
					if($noofguest != '')
					{
						
						if(($noofguest%2)!=0)
						echo '<tr>';						
						?>
						<td width="3%" class="dataTableContent"><?php echo $noofguest; ?></td>
						<td width="40%" class="dataTableContent">&nbsp;<input type="text" name="<?php echo 'guest'.$noofguest; ?>" value="<?php if($noofguest == 1) echo ucfirst($customer_first_name).' '. ucfirst($customer_last_name); ?>" /></td>
						<?php 
						if(($noofguest%2)==0)
						echo '</tr>';
						
					}
					if($noofguest == '')
					{
						if(($noofguest%2)==0)
						echo '</tr>';
						break;
					}
				} ?>
			</table>
		</td>
	</tr>
	<tr>
        <td class="dataTableContent">
			<table width="100%" cellpadding="3" cellspacing="0">

					<tr>
					<td width="30%" class="dataTableContent" valign="top" colspan="2"><b>Flight Information: <?php //echo $order->products[$i]['name']; ?></b></td>
					</tr>
					<?php 
					
						  
					echo '<tr>
						<td class="dataTableContent" colspan="2">';
						?>
						<script type="text/javascript"><!--

var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "etickets", "arrival_date","btnDate","<?php echo $orders_history['arrival_date']; ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "etickets", "departure_date","btnDate1","<?php echo $orders_history['departure_date']; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
						  <tr>
							<td class="dataTableContent">Arrival Airline Name</td>
							<td><?php echo tep_draw_input_field('airline_name', $orders_history['airline_name'], ''); ?></td>
							<td class="dataTableContent">Departure Airline Name</td>
							<td><?php echo tep_draw_input_field('airline_name_departure', $orders_history['airline_name_departure'], ''); ?></td>
						  </tr>
						  <tr>
						  	<td class="dataTableContent">Arrival Flight Number</td>
							<td><?php echo tep_draw_input_field('flight_no', $orders_history['flight_no'], ''); ?></td>
							<td class="dataTableContent">Departure Flight Number</td>
							<td><?php echo tep_draw_input_field('flight_no_departure', $orders_history['flight_no_departure'], ''); ?></td>				 
						  </tr>
						  <tr>
							<td class="dataTableContent">Arrival Airport Name</td>
							<td><?php echo tep_draw_input_field('airport_name', $orders_history['airport_name'], ''); ?></td>
							<td class="dataTableContent">Departure Airport Name</td>
							<td><?php echo tep_draw_input_field('airport_name_departure', $orders_history['airport_name_departure'], ''); ?></td>
						  </tr>
						  <tr>
								<td class="dataTableContent">Arrival Date</td>
								<td><?php //echo tep_draw_input_field('arrival_date', $arrival_date, ''); ?>
								<?php echo tep_draw_input_field('arrival_date', $orders_history['arrival_date'], ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
								<!--script type="text/javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script-->
								</td>
								<td class="dataTableContent">Departure Date</td>
								<td><?php //echo tep_draw_input_field('departure_date', $departure_date, ''); ?>
								<?php echo tep_draw_input_field('departure_date', $orders_history['departure_date'], ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
								<!--script type="text/javascript">dateAvailable1.writeControl(); dateAvailable1.dateFormat="yyyy-MM-dd";</scrip-t--></td>
							  </tr>
						  <tr>
						    <td class="dataTableContent">Arrival Time</td>
							<td><?php echo tep_draw_input_field('arrival_time', $orders_history['arrival_time'], ''); ?></td>
						  	<td class="dataTableContent">Departure Time</td>
							<td><?php echo tep_draw_input_field('departure_time', $orders_history['departure_time'], ''); ?></td>
						  </tr>
						</table>
						<?php 
						echo '
						</td>
					 </tr>';
			    ?>
			</table>
		</td>
      </tr>
	  <tr>
	  <td align="right">
	  <?php 
	        echo  field_forwarder('add_product_categories_id');
			echo  field_forwarder('add_product_products_id');
			echo  field_forwarder('add_product_options');
			//echo  field_forwarder('add_product_quantity');
			
			echo  field_forwarder('order_product_method');
			echo  field_forwarder('cc_credit_card_type');
			echo  field_forwarder('cc_owner');
			echo  field_forwarder('cc_number');
			echo  field_forwarder('cc_expires_month');
			echo  field_forwarder('cc_expires_year');		
			echo  field_forwarder('cc_cvv');
		
	  ?>
	  <input type="hidden" name="add_product_quantity" value="<?php echo $_POST['add_product_quantity'];?>"/>
	  <input type="hidden" name="availabletourdate" value="<?php echo $_POST['availabletourdate'];?>"/>
	  <input type="hidden" name="departurelocation" value="<?php echo $_POST['departurelocation'];?>"/>
	  <input type="hidden" name="numberOfRooms" value="<?php echo $_POST['numberOfRooms'];?>"/>
	  <input type="hidden" name="room-0-adult-total" value="<?php echo $_POST['room-0-adult-total'];?>"/>
	  <input type="hidden" name="room-0-child-total" value="<?php echo $_POST['room-0-child-total'];?>"/>
	  <input type="hidden" name="final_product_price_cost" value="<?php echo $final_product_price_cost;?>"/>
	   <input type="hidden" name="total_room_adult_child_info" value="<?php echo $total_room_adult_child_info;?>"/>
	  <input type="hidden" name="total_info_room" value="<?php echo $total_info_room;?>"/>
	  <input type="hidden" name="total_room_adult_child_info" value="<?php echo $total_room_adult_child_info;?>"/>
	  <input type="hidden" name="total_room_price" value="<?php echo $total_room_price;?>"/>
	  <input type="hidden" name="final_product_price" value="<?php echo $final_product_price;?>"/>
	  <input type="hidden" name="finaldate" value="<?php echo $finaldate;?>"/>
	  <input type="hidden" name="depart_time" value="<?php echo $depart_time;?>"/>
	  <input type="hidden" name="depart_location" value="<?php echo $depart_location;?>"/> 
	  <input type='hidden' name='step' value='6'>
	  <input type="submit" name="update" value="Add Now"/><?php //echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
	  </tr>
</table>
</form>
<script>
function LTrim(str){if(str==null){return null;}for(var i=0;str.charAt(i)==" ";i++);return str.substring(i,str.length);}
function RTrim(str){if(str==null){return null;}for(var i=str.length-1;str.charAt(i)==" ";i--);return str.substring(0,i+1);}
function Trim(str){return LTrim(RTrim(str));}
function validation_guest()
{
			var i = 0 ;
			for ( i= 0 ; i < window.document.etickets.elements.length ; i ++)
			{
				if(window.document.etickets.elements[i].name.substr(0,5) == "guest")
				{
					var ch = Trim(window.document.etickets.elements[i].value);
					if(ch == "")
					{
						alert("Please Enter guest name")
						window.document.etickets.elements[i].focus();
						return false;
					}
				}
			}
			return true;
			
			
}
</script>
<!-- body //-->

