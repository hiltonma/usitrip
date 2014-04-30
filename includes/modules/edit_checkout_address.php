<?php
/*
  $Id: checkout_new_address.php,v 1.1.1.1 2004/03/04 23:41:09 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if (!isset($process)) $process = false;

$billto = ((int)$billto) ? $billto : $customer_default_address_id;

$check_address_blank_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone, c.customers_mobile_phone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$billto . "'");
$row_check_address_blank = tep_db_fetch_array($check_address_blank_query);

?>
<table border="0" width="100%" cellspacing="0" cellpadding="2" style="padding:10px;">


<tr><td colspan="2" valign="top"><hr color="#CCEDFF" size="1" /></td></tr>
<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td> </tr>
<tr><td class="main" colspan="2" valign="top"><b><?php echo general_to_ajax_string(db_to_html('信用卡帐单地址（Billing Address)')); ?></b></td></tr>
<?php
echo tep_draw_hidden_field('billto',$billto);
?>
<?php
  if ($messageStack->size('checkout_address') > 0) {
?>
  <tr>
	<td colspan="3"><?php echo general_to_ajax_string($messageStack->output('checkout_address')); ?></td>
  </tr>
  <tr>
	<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
<?php
  }
?>

			  <tr>
                <td class="main" align="right" width="80" nowrap="nowrap"><?php echo general_to_ajax_string(ENTRY_COUNTRY); ?></td>
                <td class="main">
				<?php
				$country = $row_check_address_blank['country_id'];
				if($_GET['confirmation']=='true'){
					$checkout_action_form = 'checkout_confirmation';
				}else{
					$checkout_action_form = 'checkout_payment';
				}
				if((int)$country<1){  $country=223; }
				
				echo tep_get_country_list('country',$country,' id="country" class="validate-selection-blank"  onchange="get_state(this.value,'.$checkout_action_form.',\'state\');" ') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_COUNTRY_TEXT) . '</span>': ''); //title="'.ENTRY_COUNTRY_ERROR.'"?>
				</td>
              </tr>

             
<?php
  if (ACCOUNT_STATE == 'true') {
?>
              <tr>
                <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_STATE); ?></td>
                <td class="main" id="state_td">
<?php
    //if ($process == true) {
      $entry_state_has_zones = true;
	  if ($entry_state_has_zones == true) {
        $zones_array = array();
        //$zones_array[] = array('id' => '', 'text' => 's');
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_code");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => general_to_ajax_string(db_to_html($zones_values['zone_name'])), 'text' => general_to_ajax_string(db_to_html($zones_values['zone_name'])));
        }
		
        if((int)count($zones_array) && (int)$row_check_address_blank['zone_id']){
			$zone_sql = tep_db_query('SELECT zone_name FROM `zones` WHERE zone_id="'.(int)$row_check_address_blank['zone_id'].'" limit 1');
			$zone_row = tep_db_fetch_array($zone_sql);
			$state = general_to_ajax_string(db_to_html($zone_row['zone_name']));
			echo tep_draw_pull_down_menu('state', $zones_array, $state,'id="state" onchange="return get_city(this.value,'.$checkout_action_form.',\'city\');" ');
		}else{
			$state = general_to_ajax_string(db_to_html($row_check_address_blank['state']));
			echo tep_draw_input_field('state', $state,'id="state" class="required validate-length-state" title="'.general_to_ajax_string(ENTRY_STATE_ERROR).'"');
		}
      } else {
        	$state = general_to_ajax_string(db_to_html($row_check_address_blank['state']));
			echo tep_draw_input_field('state', $state,'id="state" class="required validate-length-state" title="'.general_to_ajax_string(ENTRY_STATE_ERROR).'"');
      }
   // } else {
      //echo tep_draw_input_field('state',general_to_ajax_string($row_check_address_blank['state']),'id="state" class="required validate-length-state" title="'.ENTRY_STATE_ERROR.'"');
   // }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT. '</span>';
?>
                </td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_CITY); ?></td>
                <td class="main" id="city_td">
				<?php
				$city = general_to_ajax_string(db_to_html($row_check_address_blank['city']));
				echo tep_draw_input_field('city', $city, 'id="city" class="required validate-length-city" title="'.general_to_ajax_string(ENTRY_CITY_ERROR).'"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_CITY_TEXT) . '</span>': '');
				?>
				</td>
              </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
              <tr>
                <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_SUBURB); ?></td>
                <td class="main"><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>

			  <tr>
                <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_STREET_ADDRESS); ?></td>
                <td class="main"><?php echo tep_draw_input_field('street_address',general_to_ajax_string(db_to_html($row_check_address_blank['street_address'])),'id="street_address" class="required validate-length-street" title="'.general_to_ajax_string(ENTRY_STREET_ADDRESS_ERROR).'"') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_STREET_ADDRESS_TEXT) . '</span>': ''); ?></td>
              </tr>

               <tr>
                <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_POST_CODE); ?></td>
                <td class="main"><?php echo tep_draw_input_field('postcode',$row_check_address_blank['postcode'],'id="postcode" class="required validate-length-postcode" title="'.general_to_ajax_string(ENTRY_POST_CODE_ERROR).'"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_POST_CODE_TEXT ). '</span>': ''); ?></td>
              </tr>


<?php
if (ACCOUNT_COMPANY == 'true') {
?>
  <tr>
    <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_COMPANY); ?></td>
    <td class="main"><?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_COMPANY_TEXT) . '</span>': ''); ?></td>
  </tr>
<?php
}

?>

<?php
if (($content=='travel_companion_pay' || $content=='checkout_payment') && $ajax!=true) {
?>
  <tr>
	<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="main" align="right">&nbsp;</td>
    <td class="main">
	<label><input name="auto_write_ship" type="checkbox" id="auto_write_ship" value="1" onClick="FillShip(this)"> <?php echo general_to_ajax_string(db_to_html('这也是我的通信地址'));?></label>
	<script type="text/javascript">
	function FillShip(obj){
		var CheckoutPayment = document.getElementById('checkout_payment');
		var show_address_div = document.getElementById('show_address_div');
		var show_edit_address_div = document.getElementById('show_edit_address_div');
		if(obj.checked==true){
			if(show_edit_address_div!=null){ show_edit_address_div.style.display='';}
			if(show_address_div!=null){ show_address_div.style.display='none';}
		}
		//auto set country
		var country = document.getElementById('country');
		var ship_country = document.getElementById('ship_country');
		if(country!=null && ship_country!=null){
			ship_country.value = country.value;
			get_ShipCountryTelCode('checkout_payment',ship_country.value);
		}
		//auto set state
		var state = document.getElementById('state');
		var ship_state = document.getElementById('ship_state');
		if(state!=null && ship_state!=null){
			if(ship_state.type=='select-one'){
				for(var i=ship_state.length-1;i>=0;i--){
					//ship_state.options[i].remove();
					ship_state.length=0;
				}
				
				if(state.type=='select-one'){
					for(var i=state.length-1;i>=0;i--){
						ship_state.options[i]= new Option(state.options[i].text,state.options[i].value);
					}
				}else{
					ship_state.options[0]= new Option(state.value,state.value);
				}
			}
			ship_state.value = state.value;
			
		}
		//auto set city
		var city = document.getElementById('city');
		var ship_city = document.getElementById('ship_city');
		if(city!=null && ship_city!=null){
			if(ship_city.type=='select-one'){
				for(var i=ship_city.length-1;i>=0;i--){
					//ship_city.options[i].remove();
					ship_city.length=0;
				}
				if(city.type=='select-one'){
					for(var i=city.length-1;i>=0;i--){
						ship_city.options[i]= new Option(city.options[i].text,city.options[i].value);
					}
				}else{
					ship_city.options[0]= new Option(city.value,city.value);
				}
			}
			ship_city.value = city.value;
		}
		//auto set street_address
		var street_address = document.getElementById('street_address');
		var ship_street_address = document.getElementById('ship_street_address');
		if(street_address!=null && ship_street_address!=null){
			ship_street_address.value = street_address.value;
		}
		//auto set postcode
		var postcode = document.getElementById('postcode');
		var ship_postcode = document.getElementById('ship_postcode');
		if(postcode!=null && ship_postcode!=null){
			ship_postcode.value = postcode.value;
		}
		
	}
	</script>
	
	</td>
  </tr>
<?php
}

?>
  
  

 <?php if(isset($HTTP_GET_VARS['ajax_section_name'])){ ?>
  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td> </tr>
  <tr>
  	<td>&nbsp;</td>
	<td>
		<?php 
	
		$osCsid_string = '';
		if(tep_not_null($_GET['osCsid'])){
			$osCsid_string = '&osCsid='.(string)$_GET['osCsid'];
		}

		if($HTTP_GET_VARS['confirmation']!='true'){
			echo tep_template_image_button('button_update.gif', general_to_ajax_string(IMAGE_BUTTON_UPDATE),' onclick="sendFormData(\'checkout_payment\',\'checkout_edit_sections_ajax.php?action_ajax_edit=process&ajax_section_name=billing_information&ajax=true'.$osCsid_string.'\',\'response_billing_div\',\'true\');" style="cursor:pointer;"'); // onclick="toggel_div(\'address_edit_div\');" 
			echo tep_draw_separator('pixel_trans.gif', '25', '1');
			echo tep_template_image_button('button_cancel.gif', general_to_ajax_string(IMAGE_BUTTON_CANCEL),' onclick="sendFormData(\'checkout_payment\',\'checkout_edit_sections_ajax.php?action_ajax_edit=cancel&ajax_section_name=billing_information&ajax=true'.$osCsid_string.'\',\'response_billing_div\',\'true\');" style="cursor:pointer;"');
		}else{
			echo tep_template_image_button('button_update.gif', general_to_ajax_string(IMAGE_BUTTON_UPDATE),' onclick="sendFormData(\'checkout_confirmation\',\'checkout_edit_sections_ajax.php?action_ajax_edit=process&ajax_section_name=billing_information&confirmation=true&ajax=true'.$osCsid_string.'\',\'response_billing_confirmation_div\',\'true\');" style="cursor:pointer;"'); // onclick="toggel_div(\'address_edit_div\');" 
			echo tep_draw_separator('pixel_trans.gif', '25', '1');
			echo tep_template_image_button('button_cancel.gif', general_to_ajax_string(IMAGE_BUTTON_CANCEL),' onclick="sendFormData(\'checkout_confirmation\',\'checkout_edit_sections_ajax.php?action_ajax_edit=cancel&ajax_section_name=billing_information&confirmation=true&ajax=true'.$osCsid_string.'\',\'response_billing_confirmation_div\',\'true\');" style="cursor:pointer;"'); // onclick="toggel_div(\'address_edit_div\');" 
		}
	
		?>
	</td>
  </tr>  
 <?php } ?>
  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td> </tr>

</table>