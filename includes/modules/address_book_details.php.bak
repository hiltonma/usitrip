<?php
/*
  $Id: address_book_details.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b><?php echo NEW_ADDRESS_TITLE; ?></b></td>
        <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($entry['entry_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('firstname', db_to_html($entry['entry_firstname']),' id="firstname" class="required validate-length-firstname" title="'.ENTRY_FIRST_NAME_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('lastname', db_to_html($entry['entry_lastname']),'id="lastname" class="required validate-length-lastname" title="'.ENTRY_LAST_NAME_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('company', db_to_html($entry['entry_company'])) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  }
?>

          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">
			<?php echo (tep_get_country_list('country', $entry['entry_country_id'],'id="country" class="validate-selection-blank" title="'.ENTRY_COUNTRY_ERROR.'" onchange="return get_state(this.value,addressbook,\'state\');" ')) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
			</td>
          </tr>
 
<?php
  if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main" id="state_td">
<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_array[] = array('id' => '', 'text' => '');
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo db_to_html(tep_draw_pull_down_menu('state', $zones_array,'','id="state" class="validate-selection" title="'.iconv(CHARSET,'gb2312'.'//IGNORE',ENTRY_STATE_ERROR_SELECT).'" onchange="return get_city(this.value,addressbook,\'city\');" '));
      } else {
        echo db_to_html(tep_draw_input_field('state','','id="state" class="required validate-length-state" title="'.iconv(CHARSET,'gb2312'.'//IGNORE',ENTRY_STATE_ERROR).'"'));
      }
    } else {
      echo db_to_html(tep_draw_input_field('state', tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']),'id="state" class="required validate-length-state" title="'. iconv(CHARSET,'gb2312'.'//IGNORE',ENTRY_STATE_ERROR).'"'));
    }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT. '</span>';
?></td>
          </tr>
<?php
  }
?>

          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main" id="city_td"><?php echo tep_draw_input_field('city', db_to_html($entry['entry_city']),'id="city" class="required validate-length-city" title="'.ENTRY_CITY_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('street_address', db_to_html($entry['entry_street_address']),'id="street_address" class="required validate-length-street" title="'.ENTRY_STREET_ADDRESS_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php echo tep_draw_input_field('suburb', db_to_html($entry['entry_suburb'])) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('postcode', $entry['entry_postcode'],'id="postcode" class="required validate-length-postcode" title="'.ENTRY_POST_CODE_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
          </tr>
		  
<?php
  if ((isset($HTTP_GET_VARS['edit']) && ($customer_default_address_id != $HTTP_GET_VARS['edit'])) || (isset($HTTP_GET_VARS['edit']) == false) ) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' ' . SET_AS_PRIMARY; ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>


<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">

function get_state(country_id,form_name,state_obj_name){
	var form = form_name;
	var state = form.elements[state_obj_name];
	var country_id = parseInt(country_id);
	if(country_id<1){
		alert('<?php echo ENTRY_COUNTRY.ENTRY_COUNTRY_ERROR ?>');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_for_address_book_ajax.php', 'country_id='))?>") +country_id;
	ajax.open('GET', url, true);  
	ajax.send(null);
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById('state_td').innerHTML = ajax.responseText;
			document.getElementById('city_td').innerHTML ='<?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>';
		}
	}

	
}
function get_city(state_name,form_name,city_obj_name){
	var form = form_name;
	var city = form.elements[city_obj_name];
	var state_name = state_name;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_for_address_book_ajax.php','', 'SSL')) ?>");
			var aparams=new Array(); 
			for(i=0; i<form.length; i++){
				var sparam=encodeURIComponent(form.elements[i].name);
				sparam+="=";
				sparam+=encodeURIComponent(form.elements[i].value);
				aparams.push(sparam);
			}
			var post_str=aparams.join("&");	
			ajax.open("POST", url, true); 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			ajax.send(post_str);
			ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById('city_td').innerHTML =ajax.responseText;
		}
	}
}

<?php if(!isset($HTTP_GET_VARS['edit'])){?>
/*取得默认省份列表*/
get_state(<?php echo ($country) ? $country : '223'; ?>,document.getElementById('addressbook'),"state");
<?php }?>

</script>			  
