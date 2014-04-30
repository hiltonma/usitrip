<?php
/*
  $Id: checkout_new_address.php,v 1.1.1.1 2004/03/04 23:41:09 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if (!isset($process)) $process = false;

$shipto = ((int)$shipto) ? $shipto : $customer_default_ship_address_id;
//载入通信地址
$sql_check_ship_address = tep_db_query('SELECT * FROM `address_book` WHERE address_book_id="'.(int)$shipto.'" AND customers_id="'.(int)$customer_id.'" ');
$row_check_ship_address = tep_db_fetch_array($sql_check_ship_address);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2" style="padding:10px;">

<tr><td class="main" valign="top"><b><?php echo general_to_ajax_string(db_to_html('通信地址')); ?></b></td></tr>

<tr>
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php 
echo tep_draw_hidden_field('shipto',$shipto);
?>


			  <tr>
                <td class="main" align="right" style="width:80px;" nowrap="nowrap"><?php echo general_to_ajax_string(ENTRY_COUNTRY); ?>&nbsp;</td>
                <td class="main" align="left">
				<?php
				$ship_country = $row_check_ship_address['entry_country_id'];
				if($_GET['confirmation']=='true'){
					$checkout_action_form = 'checkout_confirmation';
				}else{
					$checkout_action_form = 'checkout_payment';
				}
				
				if((int)$ship_country<1){  $ship_country=$country; }
				if((int)$ship_country<1){  $ship_country=223; }
				
				echo tep_get_country_list('ship_country',$ship_country,' id="ship_country" class="validate-selection-blank"  onchange=" get_ShipCountryTelCode(\''.$checkout_action_form.'\',this.value); get_ship_state(this.value,'.$checkout_action_form.',\'ship_state\');" ') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_COUNTRY_TEXT) . '</span>': ''); //title="'.ENTRY_COUNTRY_ERROR.'"?>
				</td>
              </tr>

             
<?php
  if (ACCOUNT_STATE == 'true') {
?>
              <tr>
                <td class="main" align="right" style="width:80px;" nowrap="nowrap"><?php echo general_to_ajax_string(ENTRY_STATE); ?>&nbsp;</td>
                <td class="main" id="ship_state_td">
<?php
    //if ($process == true) {
      $entry_state_has_zones = true;
	  if ($entry_state_has_zones == true) {
        $zones_array = array();
        //$zones_array[] = array('id' => '', 'text' => 's');
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$ship_country . "' order by zone_code");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => general_to_ajax_string(db_to_html($zones_values['zone_name'])), 'text' => general_to_ajax_string(db_to_html($zones_values['zone_name'])));
        }
		
        if((int)count($zones_array) && (int)$row_check_ship_address['entry_zone_id']){
			$zone_sql = tep_db_query('SELECT zone_name FROM `zones` WHERE zone_id="'.(int)$row_check_ship_address['entry_zone_id'].'" limit 1');
			$zone_row = tep_db_fetch_array($zone_sql);
			
			$ship_state = general_to_ajax_string(db_to_html($zone_row['zone_name']));
			echo tep_draw_pull_down_menu('ship_state', $zones_array, $ship_state, ' id="ship_state" onchange="return get_ship_city(this.value,'.$checkout_action_form.',\'ship_city\');" ');
		}else{
			$ship_state = general_to_ajax_string(db_to_html($row_check_ship_address['entry_state']));
			echo tep_draw_input_field('ship_state',$ship_state,'id="ship_state" class="required validate-length-ship_state" title="'.general_to_ajax_string(ENTRY_STATE_ERROR).'"');
		}
      } else {
        	$ship_state = general_to_ajax_string(db_to_html($row_check_ship_address['entry_state']));
			echo tep_draw_input_field('ship_state',$ship_state,'id="ship_state" class="required validate-length-ship_state" title="'.general_to_ajax_string(ENTRY_STATE_ERROR).'"');
      }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT. '</span>';
?>
                </td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main" align="right" style="width:80px;" nowrap="nowrap"><?php echo general_to_ajax_string(ENTRY_CITY); ?>&nbsp;</td>
                <td class="main" id="ship_city_td">
				<?php
				$ship_city = general_to_ajax_string(db_to_html($row_check_ship_address['entry_city']));
				echo tep_draw_input_field('ship_city',$ship_city,'id="ship_city" class="required validate-length-ship_city" title="'.general_to_ajax_string(ENTRY_CITY_ERROR).'"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_CITY_TEXT) . '</span>': ''); 
				?>
				</td>
              </tr>

			  <tr>
                <td class="main" align="right" style="width:80px;" nowrap="nowrap"><?php echo general_to_ajax_string(ENTRY_STREET_ADDRESS); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_input_field('ship_street_address',general_to_ajax_string(db_to_html($row_check_ship_address['entry_street_address'])),'id="ship_street_address" class="required validate-length-street" title="'.general_to_ajax_string(ENTRY_STREET_ADDRESS_ERROR).'"') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_STREET_ADDRESS_TEXT) . '</span>': ''); ?></td>
              </tr>

               <tr>
                <td class="main" align="right" style="width:80px;" nowrap="nowrap"><?php echo general_to_ajax_string(ENTRY_POST_CODE); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_input_field('ship_postcode',$row_check_ship_address['entry_postcode'],'id="ship_postcode" class="required validate-length-postcode" title="'.general_to_ajax_string(ENTRY_POST_CODE_ERROR).'"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_POST_CODE_TEXT ). '</span>': ''); ?></td>
              </tr>
  
</table>

</td>
</tr>

  <tr>
  	<td>
  		<table align="left" cellpadding="0" cellspacing="0">
		<tr>
			<td width="80">&nbsp;</td>
			<td class="main" style="color:#A7A7A7"><?php echo general_to_ajax_string(db_to_html('国家代码'));?></td>
			<td>&nbsp;</td>
			<td class="main" style="color:#A7A7A7"><?php echo general_to_ajax_string(db_to_html('城市区号和电话号码，区号与号码之间勿用连接符号！'));?></td>
		</tr>
		<tr>
			<?php	
			
			if($error == 'true'){
			$telephone_disp[0] = $HTTP_POST_VARS['telephone_cc'];
			$telephone_disp[1] = $HTTP_POST_VARS['telephone'];
			$fax_disp[0] = $HTTP_POST_VARS['fax_cc'];
			$fax_disp[1] = $HTTP_POST_VARS['fax'];
			$mobile_phone_disp[0] = $HTTP_POST_VARS['mobile_phone_cc'];
			$mobile_phone_disp[1] = $HTTP_POST_VARS['mobile_phone'];
			
			}else{			
			$select_phone_fax = tep_db_query("select customers_telephone, customers_fax, customers_mobile_phone from  ".TABLE_CUSTOMERS." where customers_id = '" . (int)$customer_id . "'");
			$row_phone_fax = tep_db_fetch_array($select_phone_fax);
			
				   /*$telephone_disp = explode('-',tep_db_prepare_input($row_phone_fax['customers_telephone']));
				   $fax_disp = explode('-',tep_db_prepare_input($row_phone_fax['customers_fax']));*/
				   
				   $db_call_telephone_disp = tep_db_prepare_input($row_phone_fax['customers_telephone']);																			
					if(eregi('-',$db_call_telephone_disp)){																									
						$telephone_disp = split_desk_numbers_display_in_two_parts($db_call_telephone_disp);
					}else{
						$telephone_disp[1] = $db_call_telephone_disp;
					}	
					
				   $db_call_mobile_phone_disp = tep_db_prepare_input($row_phone_fax['customers_mobile_phone']);																			
					if(eregi('-',$db_call_mobile_phone_disp)){																			
						$mobile_phone_disp = split_desk_numbers_display_in_two_parts($db_call_mobile_phone_disp);
					}else{
						$mobile_phone_disp[1] = $db_call_mobile_phone_disp;
					}
						
				   $db_call_fax_disp = tep_db_prepare_input($row_phone_fax['customers_fax']);																			
					if(eregi('-',$db_call_fax_disp)){																			
						$fax_disp = split_desk_numbers_display_in_two_parts($db_call_fax_disp);
					}else{
						$fax_disp[1] = $db_call_fax_disp;
					}	
				   
			}		
					
					
				   
			?>
			<td class="main" align="right" nowrap="nowrap"><?php echo general_to_ajax_string(TEXT_BILLING_INFO_TELEPHONE); ?></td>
			<td class="main"><?php echo tep_draw_input_field('telephone_cc',$telephone_disp[0],'size=10  maxlength="4" style="ime-mode: disabled;" '); ?></td>
			<td class="main"> - </td>
			<td class="main">
			<?php		
			    echo tep_draw_input_field('telephone',$telephone_disp[1],'id="telephone" class="skyborder" style="ime-mode:disabled" ') . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_TELEPHONE_NUMBER_TEXT) . '</span>': ''); //validate-telephone-us?>
			<a id="a_add_mobile" href="JavaScript:void(0)" onClick="add_yi_dong()" style="text-decoration: underline;"><?php echo db_to_html('添加联系电话')?></a></td>
		</tr>
		<tr id="yidong_phone">
		  <td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_MOBILE_PHONE); ?></td>
		  <td class="main"><?php echo tep_draw_input_field('mobile_phone_cc',$mobile_phone_disp[0],'size=10 maxlength="4" style="ime-mode:disabled"'); ?></td>
		  <td class="main"> - </td>
		  <td class="main">
		  <?php echo tep_draw_input_field('mobile_phone',$mobile_phone_disp[1],' class="skyborder" style="ime-mode:disabled"') ; ?>
		  <a id="a_jiayong" href="JavaScript:void(0)" onClick="add_jia_yong()" style="text-decoration: underline;"><?php echo db_to_html('添加联系电话')?></a></td>
		  </tr>
		<tr id="jiayong_phone">
			<td class="main" align="right"><?php echo general_to_ajax_string(ENTRY_FAX_NUMBER); ?></td>
			<td class="main"><?php echo tep_draw_input_field('fax_cc',$fax_disp[0],'size=10 maxlength="4" style="ime-mode:disabled"'); ?></td>
			<td class="main"> - </td>
			<td class="main"><?php echo tep_draw_input_field('fax',$fax_disp[1],' class="skyborder" style="ime-mode:disabled"') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . general_to_ajax_string(ENTRY_FAX_NUMBER_TEXT) . '</span>': ''); ?>
			
			</td>
		</tr>
	  </table>
  	</td>
  </tr>

  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td> </tr>

</table>

<script type="text/javascript">
function add_jia_yong(){
	if(document.getElementById('jiayong_phone').style.display=="none"){
		document.getElementById('jiayong_phone').style.display="";
	}else{
		document.getElementById('jiayong_phone').style.display="none";
	}
}
function add_yi_dong(){
	if(document.getElementById('yidong_phone').style.display=="none"){
		document.getElementById('yidong_phone').style.display="";
	}else{
		document.getElementById('yidong_phone').style.display="none";
	}
}

var now_from = document.getElementById('checkout_payment');
var fax = now_from.elements["fax"];
var mobile_phone = now_from.elements["mobile_phone"];

//自动隐藏空的电话输入框
if(fax.value==''){
	document.getElementById('jiayong_phone').style.display="none";
}
if(mobile_phone.value==''){
	document.getElementById('yidong_phone').style.display="none";
}

//自动隐藏添加其他电话和添加移动电话连接
if(mobile_phone.value!=''){
	document.getElementById('a_add_mobile').style.display="none";
}
if(fax.value!=''){
	document.getElementById('a_jiayong').style.display="none";
}
</script>