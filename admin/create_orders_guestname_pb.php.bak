<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>

<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<?php echo tep_draw_form('etickets', basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('action')) . 'action=add_product', 'post');
//print_r($_POST);
print "<input type='hidden' name='total_products' value='".sizeof($products_ids_array)."'>";
for ($iii=0, $n=sizeof($products_ids_array); $iii<$n; $iii++) {
	$products_id_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model = '" . $products_ids_array[$iii] . "'");
	$get_products_id = tep_db_fetch_array($products_id_query);					
	$products_id = $get_products_id['products_id'];
	//echo $total_no_guest_tour_arr[$products_id];
	?>
    <input type="hidden" name="g_number[<?php echo $products_id ?>]" value="<?php echo $total_no_guest_tour_arr[$products_id];?>"/>
    <input type="hidden" name="guest1[<?php echo $products_id ?>]" value="<?php echo ucfirst($_POST['firstname']).' '. ucfirst($_POST['lastname']); ?>" />
    
    <input type="hidden" name="add_product_quantity[<?php echo $products_id ?>]" value="<?php echo $_POST['add_product_quantity'];?>"/>
	  <input type="hidden" name="final_product_price_cost[<?php echo $products_id ?>]" value="<?php echo $final_product_price_cost_arr[$products_id];?>"/>
	  <input type="hidden" name="total_room_adult_child_info[<?php echo $products_id ?>]" value="<?php echo $total_room_adult_child_info_arr[$products_id];?>"/>
	  <input type="hidden" name="total_info_room[<?php echo $products_id ?>]" value="<?php echo $total_info_room_arr[$products_id];?>"/>
	  <input type="hidden" name="total_room_price[<?php echo $products_id ?>]" value="<?php echo $total_room_price_arr[$products_id];?>"/>
	  <input type="hidden" name="final_product_price[<?php echo $products_id ?>]" value="<?php echo $final_product_price_arr[$products_id];?>"/>
	  <input type="hidden" name="finaldate[<?php echo $products_id ?>]" value="<?php echo $finaldate_arr[$products_id];?>"/>
	  <input type="hidden" name="depart_time[<?php echo $products_id ?>]" value="<?php echo $depart_time_arr[$products_id];?>"/>
	  <input type="hidden" name="depart_location[<?php echo $products_id ?>]" value="<?php echo $depart_location_arr[$products_id];?>"/> 
	  <input type="hidden" name="early_hotel_checkout_date[<?php echo $products_id ?>]" value="<?php echo $_POST['early_hotel_checkout_date'][$products_id];?>"/>
    <?php
}
echo  field_forwarder('add_product_categories_id');
echo  field_forwarder('add_product_products_id');
print "<input type='hidden' name='gc_code' value='".$HTTP_POST_VARS['gc_code']."'>";
print "<input type='hidden' name='gc_total' value='".$HTTP_POST_VARS['gc_total']."'>";
print "<input type='hidden' name='gv_redeem_code' value='".$HTTP_POST_VARS['gv_redeem_code']."'>";
print "<input type='hidden' name='dc_total' value='".$HTTP_POST_VARS['dc_total']."'>";

echo  field_forwarder('order_product_method');
echo  field_forwarder('cc_credit_card_type');
echo  field_forwarder('cc_owner');
echo  field_forwarder('cc_number');
echo  field_forwarder('cc_expires_month');
echo  field_forwarder('cc_expires_year');			
echo  field_forwarder('cc_cvv');
/* customer details forward - start */
echo  field_forwarder('customer_id');
echo  field_forwarder('firstname');
echo  field_forwarder('lastname');
echo  field_forwarder('email_address');
echo  field_forwarder('street_address');
echo  field_forwarder('city');
echo  field_forwarder('state');
echo  field_forwarder('suburb');
echo  field_forwarder('postcode');
echo  field_forwarder('country');
echo  field_forwarder('telephone_cc');
echo  field_forwarder('telephone');
echo  field_forwarder('fax_cc');
echo  field_forwarder('fax');
echo  field_forwarder('newsletter');
/* customer details forward - start */

foreach($_POST as $post_key=>$post_val){
	if(is_array($post_val)){
		foreach($post_val as $subpostkey=>$subpostval){
			if(is_array($subpostval)){
				foreach($subpostval as $subpostkey1=>$subpostval1){
				print "<input type='hidden' name='".$post_key."[".$subpostkey."][".$subpostkey1."]' value='".$subpostval1."'>";
				}
			}else{
				print "<input type='hidden' name='".$post_key."[".$subpostkey."]' value='".$subpostval."'>";
			}
		}
	}
}
?>
<table border="0" width="98%" cellspacing="2" cellpadding="2">
	  <tr>
	  <td align="right">
	  <input type='hidden' name='step' value='6'>
	  <input type="submit" name="update" value="Create Order"/><?php //echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
	  </tr>
</table>
</form>

<!-- body //-->

