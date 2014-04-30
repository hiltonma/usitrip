<script type="text/javascript">
var winPop;
function OpenWindow()
{ 
var height=550;
var width =610;
var left=50;
var top =50;
var status="NO";
var scroll = "YES";
var features="left= " + left + ",  top= " + top + ", Height= " + height + ", Width= " + width + ", status="+status + ", scrollbars=" + scroll;

winPop = window.open("pickuplocation_helicopter.html","winPop",features); 

}
</script>
<script>
function validate(){

/*if(document.cart_quantity.availabletourdate.value==""){
alert("<?php echo TEXT_SELECT_VALID_DEPARTURE_DATE;?>")
return false
}*/
var checkvalue = true;

var set2 = document.cart_quantity._1_H_address; 
if(typeof(set2)=="undefined" || set2.value != ''){
checkvalue = false;
}

/*if (document.cart_quantity._1_H_hot3.value=="" && checkvalue == true) {
	if(document.cart_quantity._1_H_address.value!="" && document.cart_quantity._1_H_hot2.value!="" ){
		alert("<?php echo TEXT_SELECT_VALID_PICKUP_TIME;?>")
	}else{
		alert("<?php echo TEXT_SELECT_PICKUP_LOCATION;?>")
	}
return false
}*/

return true;
}
</script> 

		
<?php
if($dis_buy_steps_2!=true && $on_shopping_cart != true){
	$nbsp='&nbsp;';
	if($content=='product_info_vegas_show'){
		$nbsp='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}
	$html_str = '<td class="buy_steps_2" >'.$nbsp.'</td>';
	$add_td = '<td>&nbsp;</td>';
	$dis_buy_steps_2=true;
}else{
	$html_str = '<td>&nbsp;</td>';
	$add_td = '';
}
?> 


<tr>
<?php echo $add_td;?>

<?php echo $html_str;?>

<td nowrap><a href="#"  onclick="OpenWindow();return false;"><font color="#005983"><?php echo TEXT_CLICK_SELECT_PICKUP_HOTEL;?></font></a></td>
</tr>

<tr>
<?php echo $add_td;?>
<td>&nbsp;</td><td class="buy_options_title" ><?php echo TEXT_HEADING_HOTEL;?></td>
</tr>
<tr>
<?php echo $add_td;?>
<td>&nbsp;</td><td><div class="main" id="divpickuphotel"></div><input type="hidden" name="_1_H_address"></td>
</tr>

<tr>
<?php echo $add_td;?>
<td>&nbsp;</td><td class="buy_options_title" >
<?php echo TEXT_HEADING_PICKUP_LOCATION;?></td>
</tr>
<tr>
<?php echo $add_td;?>
<td>&nbsp;</td><td><div class="main" id="divpickuplocation"></div><input type="hidden"  name="_1_H_hot2"></td>
</tr>

<tr>
<?php echo $add_td;?>
<td>&nbsp;</td><td class="buy_options_title" >
<?php echo TEXT_HEADING_PICKUP_TIME;?></td>
</tr><tr>
<?php echo $add_td;?>
<td>&nbsp;</td><td> <div id="pickuptimediv"><select class="validate-select-custom-pickup sel2" name="_1_H_hot3" id="_1_H_hot3"  title="<?php echo TEXT_SELECT_PICKUP_LOCATION; ?>"><option value=""><?php echo TEXT_SELECT_NOTHING;?></option></select></div></td>
</tr>