<?php 
define('TEXT_HEDING_PLEASE_SELECT_PICKUP_LOCATION',db_to_html('请选择您的上车地点'));
$array_script = '<script type="text/javascript">
				var array1 = new Array();
				var array2 = new Array();
				';
							 
$i=1;

$query = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." group by departure_region order by departure_region";
$row = mysql_query($query);
while($result = mysql_fetch_array($row))
{
	$region_combo .= "<option selected='selected' value='".$i."'>".db_to_html($result['departure_region'])."</option>";
	
$array_script .= 'array1['.$i.'] = new Array(
"Please make a selection...","'.TEXT_HEDING_PLEASE_SELECT_PICKUP_LOCATION.'","-","-",
';
	
	
	$comma = 1;
	$k=1;
	$j= (int)$i.'00'.$k;
	$query_address = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_region = '".tep_db_input($result['departure_region'])."'
	  group by  departure_address order by  departure_id";
	$row_address = mysql_query($query_address);
	while($result_address = mysql_fetch_array($row_address))
	{
			$map_arrar_add = "";
			if($result_address['map_path']!='')	
			{		
				$map_arrar_add = '<a href='.$result_address['map_path'].' target=_blank><img height=16 src=images/maps.jpg width=16 align=absMiddle border=0></a>';
			}
						
						
			if($comma == 1)
			{
$array_script .= $j.',"'.db_to_html($result_address['departure_address']).'","'.db_to_html($result_address['departure_full_address']).'","'.$map_arrar_add.'"';
				$comma = 0;
			}
			else
			{
$array_script .= ',
'.$j.',"'.db_to_html($result_address['departure_address']).'","'.db_to_html($result_address['departure_full_address']).'","'.$map_arrar_add.'"'; 
			}
			
$array_script_time .= 'array2['.$j.'] = new Array(
';
			
			$j++;
			$comma_time = 1;
			
			$query_time = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_region = '".tep_db_input($result['departure_region'])."' and departure_address = '".tep_db_input($result_address['departure_address'])."' order by  departure_id";
			$row_time = mysql_query($query_time);
			$rowcount = 1;
			$totaldepa_rowcount = mysql_num_rows($row_time);
			while($result_time = mysql_fetch_array($row_time))
			{
					///////////////////
					$len=strlen($result_time['departure_time']);
					if($len == 6)
					$depart_final = '0'.$result_time['departure_time'];
					else
					$depart_final = $result_time['departure_time'];
					
					
					if(strstr($depart_final,'pm'))
					{
						$pma[$result_time['departure_id']] = $depart_final ;
					}
					if(strstr($depart_final,'am'))
					{
						$ama[$result_time['departure_id']] = $depart_final ;
					}
					////////////////////////
			
					
					if($totaldepa_rowcount == 1){
					//stort array start
						if($ama != '')
						array_multisort($ama,SORT_ASC);
						if($pma != '')
						array_multisort($pma,SORT_ASC);
					
					//shor array end
					
					if($ama != '')
						{
							foreach($ama as $key => $val)
							{
									if(substr($val,0,1) == 0){
									$val = substr($val,1,7);
									}
									if($comma_time == 1)
									{
										$array_script_time .= $k.',"'.$val.'"';
										$comma_time = 0;
									}
									else
									{
										$array_script_time .= ',
										'.$k.',"'.$val.'"';
									}
										
									$k++;
							}
						}
					if($pma != '')
					{
						foreach($pma as $key => $val)
						{
								if(substr($val,0,1) == 0){
									$val = substr($val,1,7);
									}
							
								if($comma_time == 1)
									{
										$array_script_time .= $k.',"'.$val.'"';
										$comma_time = 0;
									}
									else 
									{
										$array_script_time .= ',
										'.$k.',"'.$val.'"';
									}
										
									$k++;
						}	
					}		
					 ////make array blank start
					 $ama = array();
					 $pma = array();  
					 ////make array blank end
					}
					
					// bof of james add
						if(strstr(strtolower($depart_final),'open')){
							if($comma_time == 1)
									{
										$array_script_time .= $k.',"Open Time"';
										$comma_time = 0;
									}
									else 
									{
										$array_script_time .= ',
										'.$k.',"Open Time"';
									}
							$k++;
						}
						//eof of james add
						
			$totaldepa_rowcount--;		
			}
			/* amit commented start
			$query_time = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_region = '".tep_db_input($result['departure_region'])."' and departure_address = '".tep_db_input($result_address['departure_address'])."' order by  departure_id"; 
			$row_time = mysql_query($query_time);
			while($result_time = mysql_fetch_array($row_time))
			{
				if($comma_time == 1)
				{
$array_script_time .= $k.',"'.$result_time['departure_time'].'"';
					$comma_time = 0;
				}
				else
				{
$array_script_time .= ',
'.$k.',"'.$result_time['departure_time'].'"';
				}
					
				$k++;
			}
			
			amit commented end*/
$array_script_time .= ');
';
	}
	
$array_script .= ');
';
	
	$i++;
} 

echo $array_script;
echo $array_script_time;
echo '</script>';

?>
<script type="text/javascript">
<!--

function clearcombo(elem){

	var i;
	for (i = elem.options.length; i >= 0; i--) elem.options[i] = null;
	elem.selectedIndex = -1;
}
function populatecombo2(elem, index, addr, city){

	addr.value="";
	city.value="";
	if (array1.length >= index){
		if (array1[index]){
			for (var i = 0; i < array1[index].length; i= i + 4){
				elem.options[elem.options.length] = new Option(array1[index][i + 1], array1[index][i]);
			}
		}
		else{
			elem.options[elem.options.length] = new Option("<?php echo TEXT_HEADING_NONE_AVAILABLE;?>", 0);
		}
	}
	else{
		elem.options[elem.options.length] = new Option("<?php echo TEXT_HEADING_NONE_AVAILABLE;?>", 0);
	}
}
function populatecombo3(elem, index, addr, city, pik){
	if (array2.length >= index){
		if (array2[index]){
			for (var i = 0; i < array2[index].length; i= i + 2){
			elem.options[elem.options.length] = new Option(array2[index][i + 1], array2[index][i]);
			}
			addr.value=array1[eval((index-(index%1000))/1000)][eval(parseInt(index%1000)*4+2)];
			city.value=array1[eval((index-(index%1000))/1000)][eval(parseInt(index%1000)*4+2)];
			document.getElementById("fulladdress").innerHTML = array1[eval((index-(index%1000))/1000)][eval(parseInt(index%1000)*4+1)];
			document.getElementById("fulladdress").innerHTML += ', '+array1[eval((index-(index%1000))/1000)][eval(parseInt(index%1000)*4+2)];
			document.getElementById("mappath").innerHTML = array1[eval((index-(index%1000))/1000)][eval(parseInt(index%1000)*4+3)];
		}
		else{
			elem.options[elem.options.length] = new Option("<?php echo TEXT_HEADING_NONE_AVAILABLE;?>", 0);
		}
	}
	else{
		elem.options[elem.options.length] = new Option("<?php echo TEXT_HEADING_NONE_AVAILABLE;?>", 0);
	}
}
function clickcombo(nWhich,elem1,elem2,elem3,addr,city,pik){
document.cart_quantity._1_H_address.value='';
	if (nWhich == "1"){
		clearcombo(elem2);
		clearcombo(elem3);	
		populatecombo2(elem2, elem1[elem1.selectedIndex].value, addr, city);
	}
	if (nWhich == "2"){
		clearcombo(elem3);
		populatecombo3(elem3, elem2[elem2.selectedIndex].value, addr, city, pik);
	}
	return true;
}
// -->
function validate(){
var check = true;
var set2 = document.cart_quantity._1_H_address; 
if(typeof(set2)=="undefined" || set2.value != ''){
var check = false;
}

/*if(document.cart_quantity.availabletourdate.value==""){
alert("<?php echo TEXT_SELECT_VALID_DEPARTURE_DATE;?>")
return false
}

if (document.cart_quantity._1_H_hotel3.value=="0" && check == true) {
alert("<?php echo TEXT_SELECT_PICKUP_LOCATION;?>")
return false
}
if (document.cart_quantity._1_H_hotel3.value=="<?php echo TEXT_SELECT_NOTHING;?>" && check == true) {
alert("<?php echo TEXT_SELECT_PICKUP_LOCATION;?>")
return false
}
if (document.cart_quantity._1_H_hotel3.value=="" && check == true) {
alert("<?php echo TEXT_SELECT_PICKUP_LOCATION;?>")
return false
}
if (document.cart_quantity._1_H_hotel3.value=="<?php echo TEXT_HEADING_NONE_AVAILABLE;?>" && check == true) {
alert("<?php echo TEXT_SELECT_PICKUP_LOCATION;?>")
return false
}*/
try{
H1=document.cart_quantity._1_H_hotel1[document.cart_quantity._1_H_hotel1.selectedIndex].value;
H2=document.cart_quantity._1_H_hotel2[document.cart_quantity._1_H_hotel2.selectedIndex].value;
H3=document.cart_quantity._1_H_hotel3[document.cart_quantity._1_H_hotel3.selectedIndex].value;

i=4;
while (array1[H1][i] < H2) i+=4;
j=0;
while (array2[H2][j] < H3) j+=2;
document.cart_quantity._1_H_hot1.value=document.cart_quantity._1_H_hotel1.value;
if (document.cart_quantity._1_H_hot1.value=="9") { document.cart_quantity._1_H_hot1.value="G" }
document.cart_quantity._1_H_hot2.value=array1[H1][i+1];
document.cart_quantity._1_H_hot3.value=array2[H2][j+1];
}catch(e){}
}
</script>
<?php
if($content == CONTENT_PRODUCT_INFO){	//普通页面
?>
<table id="old_shang_che_de_ji">
<?php
}
?>
<tr> 
		<td width="1">&nbsp;</td>
		
		<?php
		if($dis_buy_steps_2!=true && $on_shopping_cart != true){
			$nbsp='&nbsp;';
			if($content=='product_info_vegas_show'){
				$nbsp='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			echo '<td class="buy_steps_2" >'.$nbsp.'</td>';
			$dis_buy_steps_2=true;
		}else{
			echo '<td>&nbsp;</td>';
		}
		?> 
		
		<td class="buy_options_title" valign="top" id="location_address_title"><b><?php echo TEXT_HEADING_PICKUP_LOCATION;?></b></td>
</tr>

<tr> 
		<td width="1">&nbsp;</td>
		<td>&nbsp;</td>
		<td valign="top">
		<div  style="display:none">
		<select name="_1_H_hotel1" class="sel3" size="0" 
			onchange="return(clickcombo('1',document.cart_quantity._1_H_hotel1,document.cart_quantity._1_H_hotel2,document.cart_quantity._1_H_hotel3, document.cart_quantity._1_H_address,document.cart_quantity._1_H_city,5));">
			<option value="Please make a selection..."><?php echo TEXT_SELECT_PICKUP_REGION;?></option>
			<?php echo $region_combo;?>
		</select>
		</div>		
				
	 	<select name="_1_H_hotel2" class="sel3" size="0" onchange="return(clickcombo('2',document.cart_quantity._1_H_hotel1,document.cart_quantity._1_H_hotel2, document.cart_quantity._1_H_hotel3, document.cart_quantity._1_H_address,document.cart_quantity._1_H_city,5));"><option><?php echo TEXT_SELECT_NOTHING;?></option></select><br />
		<select name="_1_H_hotel3"  class="sel3" size="0"><option><?php echo TEXT_SELECT_NOTHING;?></option></select>
		
		<input type="hidden" name="_1_H_hot1" value="" />
		<input type="hidden" name="_1_H_hot2" value="" />
		<input type="hidden" name="_1_H_hot3" value="" />
		
		</td>
</tr>

<tr>
		<td width="1">&nbsp;</td>
		<td>&nbsp;</td>
		<td class="buy_options_title"><b><?php echo TEXT_PICKUP_ADDRESS;?></b></td>
</tr>
<tr>
		<td width="1">&nbsp;</td>
		<td>&nbsp;</td>
			<td class="main"><div id="fulladdress"></div><div id="mappath" style="display:none"></div>
			<input type="text" class="validate-select-custom-pickup " id="_1_H_address" title="<?php echo TEXT_SELECT_PICKUP_LOCATION; ?>" name="_1_H_address" style="border:0px; height:0px; width:0px;" />
			<input type="hidden" name="_1_H_city" style="border:0" size="30" />
		</td>
		
</tr>
<?php
if($content == CONTENT_PRODUCT_INFO){	//普通页面
?>
</table>
<?php
}
?>
		<script type="text/javascript">
		clickcombo('1',document.cart_quantity._1_H_hotel1,document.cart_quantity._1_H_hotel2,document.cart_quantity._1_H_hotel3, document.cart_quantity._1_H_address,document.cart_quantity._1_H_city,5);
		</script>
