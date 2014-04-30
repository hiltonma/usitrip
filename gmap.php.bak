<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
//usitrip.com
define('GMAP_API_KEY','ABQIAAAAvy4v5guI00btuf35XUCHMRTo2_hn4g3bPjde8vv-FnQrYo4KZRQemyWoR1PQtymH4RTjsh_SdOVKkQ');
//192.168.0.107:8888
//define('GMAP_API_KEY','ABQIAAAAvy4v5guI00btuf35XUCHMRSAPL4-clOluTDvtnef3CX0E3atNBRaL-r4aZyFviv23_ikJV9FXkrWIg');

	require_once('includes/application_top.php');
	require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');
	$products_id=(int)$_GET['products_id'];
	if($products_id<=0){
		exit;
	}
	//require_once(DIR_FS_LANGUAGES . $language . '/'. FILENAME_PRODUCT_INFO);
	require_once(DIR_FS_CLASSES.'gmap.class.php');
	
	$gm = & new GoogleMap(GMAP_API_KEY);
	$gm->SetMapZoom(10);
	if($_GET['distance_from']!=""){
		$gm->mFromAddress = $_GET['distance_from'];
		$gm->SetFromAddressLatLng($gm->mFromAddress);
	}
	$selected_addr=tep_db_input($_GET['selected_addr']);
	$extra_url="";
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<title><?php echo db_to_html('上车地点选择助手');?></title>
<?php echo $gm->GmapsKey(); ?>
<style type="text/css">
*{ margin:0; padding:0;}
input.text{ height:16px; border:1px solid #d5d5d5; line-height:16px; color:#111; padding:2px 3px; font-size:12px; }
.mapLeft { float:left;}
.mapRight{  position:absolute; right:0; top:0; width:500px; overflow:hidden;}
.mapRight #map{ overflow:hidden;}
.mapLeftCol{ width:315px; height:460px; overflow:auto; position:relative; float:left; margin-right:10px; display:inline; font-family:Arial; color:#000000; font-size:12px; }
.mapLeftColTitle { float:left; padding:3px; width:292px; font-weight:bold; }
.mapLeftColCon { position:relative; border-top:none; float:left; padding:1px; width:295px; }
.leftContentListing {	width:292px; float:left; }
.leftContentListing span { float:left; padding-right:3px; }
.leftContentListing div {	width:220px; float:left; }
.leftContentListing span.disp_dist	{ clear:right; width:auto; float:right;	}
.odd { background-color:#FFFFFF; padding:3px 0 3px 3px; }
.even { background-color:#F2EFE9; padding:3px 0 3px 3px; }
.linkAddr { text-decoration:none; color:#113F92; font-size:12px; outline:none; }
.linkAddr:hover	{ text-decoration:underline; }
.linkTime	{ text-decoration:none; color:#113F92; font-size:11px; }
.linkTime:hover	{ text-decoration:underline; }
input { border: 1px solid #015998; width:235px; vertical-align:middle; }
.valignMid	{ vertical-align:middle;	}
.sub1 { height:21px; width:52px; background:#FFFFFF; display:inline; }
.infowindowTxt { font-family:Arial; font-size:14px; color:#000066; }
.sp1 {font-family: Arial;font-size: 12px;color:#F1740E; text-decoration:none; }
.font_red {color:#FF0000; }
.txt12bluereg{ color:#113F92; font-size:12px; line-height:18px; font-family:Arial; font-weight:bold; text-decoration:none;	}
.txt12bluereg:hover { color:#FF0000; text-decoration:none; }
.closeHlpTxt{ padding-top:5px;}
.closeHlpTxt, .closeHlpTxt a { font-size:12px; }
.pad_bot5 { padding-bottom:5px; }
</style>
</head>
<body>
<?php
$i=1;
$query = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." group by departure_region order by departure_region";
$row = mysql_query($query);
while($result = mysql_fetch_array($row)){
	$pickup_regions[$i]=htmltoutf8($result['departure_region']);
	$comma = 1;
	$k=1;
	$j= (int)$i.'00'.($k-1);
	$query_address = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_region = '".tep_db_input($result['departure_region'])."' order by departure_time";
	$row_address = mysql_query($query_address);
	while($result_address = mysql_fetch_array($row_address)){
			$j++;
			$map_arrar_add = "";
			if($comma == 1){
$array_script .= $j.',"'.htmltoutf8($result_address['departure_address']).'","'.htmltoutf8($result_address['departure_full_address']).'","'.$map_arrar_add.'"';
				$comma = 0;
			}else{
$array_script .= ',
'.$j.',"'.htmltoutf8($result_address['departure_address']).'","'.htmltoutf8($result_address['departure_full_address']).'","'.$map_arrar_add.'"';
			}
			
$array_script_time .= 'array2['.$j.'] = new Array(
';
			
			$pickup_address[$i][$j]=htmltoutf8($result['departure_region'])."{:##:}".htmltoutf8($result_address['departure_address']);
			$pickup_full_address[$i][$j]=htmltoutf8($result_address['departure_full_address']);
			$pickup_full_address_display[$i][$j]=htmltoutf8($result_address['departure_address']).", ".htmltoutf8($result_address['departure_full_address']);
			$comma_time = 1;
						
			$query_time ="select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_region = '".tep_db_input($result['departure_region'])."' and departure_address = '".tep_db_input($result_address['departure_address'])."' order by  departure_id";
			$row_time = mysql_query($query_time);
			$rowcount = 1;
			$totaldepa_rowcount = mysql_num_rows($row_time);
			while($result_time = mysql_fetch_array($row_time))
			{
				
					$departure_id=$result_time['departure_id'];
					///////////////////
					$len=strlen($result_time['departure_time']);
					if($len == 6){
						$depart_final = '0'.$result_time['departure_time'];
					}else{
						$depart_final = $result_time['departure_time'];
					}

					if(strstr($depart_final,'pm'))
					{
						$pma[$result_time['departure_id']] = $depart_final ;
					}else if(strstr($depart_final,'am')){
						$ama[$result_time['departure_id']] = $depart_final ;
					}else{
						$other_times[$result_time['departure_id']] = $depart_final ;
					}
					////////////////////////
			
					
					if($totaldepa_rowcount == 1){
						
					
					//stort array start
						if(count($ama ))
						array_multisort($ama,SORT_ASC);
						if(count($pma))
						array_multisort($pma,SORT_ASC);
					
					//shor array end
					
					if($ama != ''){
						foreach($ama as $key => $val){
							if(substr($val,0,1) == 0){
								$val = substr($val,1,7);
							}
							if($comma_time == 1)
							{
								$array_script_time .= $k.',"'.$val.'"';
								$comma_time = 0;
							}else{
								$array_script_time .= ',
								'.$k.',"'.$val.'"';
							}
							$pickup_time[$j][$k]=$val;
							$k++;
						}
					}
					if($pma != ''){
						foreach($pma as $key => $val){
							if(substr($val,0,1) == 0){
								$val = substr($val,1,7);
							}
							if($comma_time == 1)
							{
								$array_script_time .= $k.',"'.$val.'"';
								$comma_time = 0;
							}else{
								$array_script_time .= ',
								'.$k.',"'.$val.'"';
							}
							$pickup_time[$j][$k]=$val;
							$k++;
						}	
					}
					if(is_array($other_times)){
						foreach($other_times as $key => $val)
						{
							if($comma_time == 1){
								$array_script_time .= $k.',"'.$val.'"';
								$comma_time = 0;
							}else{
								$array_script_time .= ',
								'.$k.',"'.$val.'"';
							}
							
							$pickup_time[$j][$k]=$val;
							$k++;
						}
					}
					 ////make array blank start
					 $ama = array();
					 $pma = array();  
					 $other_times = array();
					 ////make array blank end
					}
					// bof of james add
					if(strstr(strtolower($depart_final),'open')){
						if($comma_time == 1){
							$array_script_time .= $k.',"Open Time"';
							$comma_time = 0;
						}else{
							$array_script_time .= ',
							'.$k.',"Open Time"';
						}
						$pickup_time[$j][$k]=$val;
						$k++;
					}
					//eof of james add
				$totaldepa_rowcount--;		
			}
$array_script_time .= ');
';
	}
$array_script .= ');
';
	$i++;
}

if(is_array($pickup_regions)){
	foreach($pickup_regions as $key_departure_region=>$departure_region){
		if(is_array($pickup_address[$key_departure_region])){
			foreach($pickup_address[$key_departure_region] as $key_departure_full_address=>$val_departure_full_address){
				$departure_full_address=$pickup_full_address[$key_departure_region][$key_departure_full_address];
				$departure_full_address_display[$departure_full_address]=$pickup_full_address_display[$key_departure_region][$key_departure_full_address];
			//	$gm->SetAddress($departure_full_address);
			//	$gm->SetInfoWindowText($departure_full_address);
			//	$gm->SetSideClick($departure_full_address);
				$gm->SetDistance($departure_full_address);

				if(is_array($pickup_time[$key_departure_full_address])){
					foreach($pickup_time[$key_departure_full_address] as $key_pickup_time=>$val_pickup_time){
						$arr_pickup_time[utf8tohtml($departure_full_address)][$key_pickup_time]=$val_pickup_time;
						$arr_pickup_location_full_str[utf8tohtml($departure_full_address)][$key_pickup_time]=$key_departure_region."-".$key_departure_full_address."-".$key_pickup_time.':#####:'.htmlspecialchars(utf8tohtml($val_departure_full_address.'{:##:}'.$departure_full_address), ENT_QUOTES);
					}
				}
			}
		}
	}
}

$arr_numric_distance=array();
if(is_array($gm->mDistanceArr)){
	foreach($gm->mDistanceArr as $k=>$v){
		$arr_numric_distance[$k]=floatval($v);
	}
}

@asort($arr_numric_distance);
$gm->mDistanceArr=$arr_numric_distance;
$sorted_distance_arr = $gm->mDistanceArr;
if(is_array($sorted_distance_arr)){
	foreach($sorted_distance_arr as $departure_full_address=>$departure_distance){
		if($selected_addr!=""){
			if($selected_addr==$departure_full_address){
				$gm->SetAddress(utf8tohtml($departure_full_address));
				$gm->SetInfoWindowText('<font class="infowindow_txt"><br />'.wordwrap(utf8tohtml($departure_full_address_display[$departure_full_address]), 50, "<br />").'</font>');
				$gm->SetSideClick(utf8tohtml($departure_full_address_display[$departure_full_address]));
			}
		}else{
			$gm->SetAddress(utf8tohtml($departure_full_address));
			$gm->SetInfoWindowText('<font class="infowindow_txt"><br />'.wordwrap(utf8tohtml($departure_full_address_display[$departure_full_address]), 50, "<br />").'</font>');
			$gm->SetSideClick(utf8tohtml($departure_full_address_display[$departure_full_address]));
		}

	}
}

?>
<div class="mapLeftCol">
	<div class="closeHlpTxt pad_bot5">
		<?php 
			if($selected_addr==""){
				echo db_to_html('上车地点选择助手使用Google地图来帮助您查找距您所输入地址最近的上车地点。如果使用时出现错误，请关闭此助手，从上车地点的下拉列表中选择上车地点。
');
			}
		?>
	</div>
	<div class="mapLeft">
	<?php 
		echo tep_draw_form('search_closest_location', tep_href_link("gmap.php", 'action=search'), 'get');
		echo '<div class="valignMid"><b>';
		echo db_to_html('输入地址，查找最近的上车地点：')."</b><br />".tep_draw_input_field("distance_from", db_to_html('地址, 城市, 邮编'), 'onfocus="if(this.value==\''.db_to_html('地址, 城市, 邮编').'\'){this.value=\'\'}"');
		echo tep_draw_hidden_field("products_id", $products_id, 'id="products_id"');
		echo tep_draw_hidden_field("cPath", $cPath);
		echo tep_draw_hidden_field("action", "search");
		echo tep_draw_hidden_field("selected_addr", $selected_addr);
		echo '&nbsp;<input type="image" src="image/button_find.gif" style="border:0;" class="sub1" />';
		echo '</div>';
		echo '</form>';
		if($gm->mFromAddress!=""){
			$extra_url.="&distance_from=".$gm->mFromAddress;
			$gm->SetAddress($gm->mFromAddress);?>
			<div class="fleft"><?php echo db_to_html('您已输入：');?></div> <a href="javascript:void(<?php echo count($gm->mAddressArr)-1;?>);" class="link_addr fleft" onclick="javascript:sideClick(<?php echo count($gm->mAddressArr)-1;?>);" title=""><?php echo $gm->mFromAddress;?></a>
<?php }
		if($selected_addr!=""){
			$extra_url.="&selected_addr=".$selected_addr;
		}
	?>
	</div>
<?php if($selected_addr==""){?>
	<div class="mapLeftColTitle">
		<?php echo db_to_html('上车地点');?>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;		
		<a href="javascript:void(0);" class="txt12bluereg" onclick="parent.showGMapHelper('<?php echo tep_href_link('gmap.php','products_id='.$products_id.$extra_url);?>');"><?php echo db_to_html('刷新列表');?></a>
	</div>
<?php }?>
	<div class="mapLeftColCon" id="div_destination_content">
	<?php 
	if(count($gm->mAddressArr)>0){
		echo '<div class="font_red">'.db_to_html('点击地址在地图上查看上车地点位置。点击时间选定上车地点。').'</div>';
	}else{
		$gm->SetAddress("");
	}?>
		<div class="leftContentListing odd">
		<?php if($selected_addr==""){?>
			<span><strong>&nbsp; &nbsp;</strong></span>
		<?php }?>
			<div><strong><?php echo db_to_html('地址');?></strong></div>
			<span class="disp_dist">
			<?php 
				if($gm->mFromAddress!=""){
					echo '<strong>'.db_to_html('距离').'</strong>';
				}
			?>
			</span>
		</div>
	<?php 
	$total_addr_cntr=count($gm->mAddressArr);
	if($gm->mFromAddress!=""){
		$total_addr_cntr--;
	}
	$selected_addr_cntr="";
	for($i=0; $i < $total_addr_cntr; $i++){
		if($i%2==0){
			$class='odd';
		}else{
			$class='even';
		}
	?>
		<div class="leftContentListing <?php echo $class;?>">
			<?php if($selected_addr==""){?>
				<span><strong><?php echo sprintf('%02d',$i+1);?>&nbsp;</strong></span>
			<?php }?>
				<div>
					<div id="directions_<?php echo sprintf('%02d',$i);?>" style="display:none; "></div>
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td>
								<a href="javascript:void(<?php echo $i;?>);" class="linkAddr mapLeft" onclick="javascript:sideClick(<?php echo $i;?>);" title=""><?php echo $gm->mSideClickArr[$i];?></a>
							</td>
						</tr>
				<?php 
					if(is_array($arr_pickup_time[$gm->mAddressArr[$i]])){
						$time_cntr=0;?>
						<tr>
							<td>
					<?php 
						foreach($arr_pickup_time[$gm->mAddressArr[$i]] as $location=>$time){
							$time_cntr++;
							if($time_cntr > 1){
								echo " | ";
							}
							$pickup_location_full_str=$arr_pickup_location_full_str[$gm->mAddressArr[$i]][$location];
							$pickup_location_full_str .= "{:##:}".$time;
						?>
									<a href="javascript:void(0);" onclick="parent.closePopup('popupMap');set_selected_location('<?php echo $pickup_location_full_str;?>', '<?php echo $products_id;?>');" class="sp1"><?php echo $time;?></a>
					<?php }
						}?>
							</td>
						</tr>
					</table>
				</div>
				<span id="distance_<?php echo $i;?>" class="disp_dist">
					<?php 
						if($gm->mFromAddress!="" && $selected_addr==""){
							echo $gm->mDistanceArr[$gm->mAddressArr[$i]]."mi";
						}
					?>
				</span>
		</div>
	<?php }?>
	</div>
</div>
<div class="mapRight">
<?php 
	echo $gm->SetMapWidth(500);
	echo $gm->SetMapHeight(460);
	echo $gm->MapHolder();
	echo $gm->InitJs();
	echo $gm->UnloadMap();
?>
</div>
<script type="text/javascript">
<!-- 
var toAddress=[];
fromAddress="<?php echo $gm->mFromAddress;?>";
<?php
foreach($gm->mAddressArr as $k=>$v){?>
	<?php
	if($v!="" && $v!=$gm->mFromAddress){?>
		toAddress[<?php echo $k;?>]="<?php echo $v;?>";
<?php 
	}
}
if($selected_addr!=""){?>
	setDirections(fromAddress, toAddress, "en_US");
<?php }?>

function set_selected_location(location_str, prod_id){
	location_str = location_str.split(':#####:');
	var locationData = location_str[0];
	locationData = locationData.split('-');
	var locationStr = location_str[1];
	locationStr = locationStr.split('{:##:}');
	var showlocationStr = '['+locationStr[0]+'] '+locationStr[3]+' '+locationStr[1]+', '+locationStr[2];
	parent.jQuery('#TextBox_placePop').html(showlocationStr);
	
	var Address_1_H_hotel1_radio = parent.jQuery("input[name='Address_1_H_hotel1_radio']")
	if(!Address_1_H_hotel1_radio.is('input')){
		parent.jQuery('<input name="Address_1_H_hotel1_radio" value="'+locationData[0]+'" type="radio" style="display:none;">').appendTo('#cart_quantity');
	}else{
		Address_1_H_hotel1_radio.each(function(){
			if(parent.jQuery(this).val()==locationData[0]){
				parent.jQuery(this).attr('checked',true);
			}else{
				parent.jQuery(this).attr('checked',false);
			}
		});
	}
	Address_1_H_hotel2_radio = parent.jQuery("input[name='Address_1_H_hotel2_radio']");
	if(!Address_1_H_hotel2_radio.is('input')){
		parent.jQuery('<input name="Address_1_H_hotel2_radio" value="'+locationData[1]+'" type="radio" style="display:none;">').appendTo('#cart_quantity');
	}else{
		Address_1_H_hotel2_radio.each(function(){
			if(parent.jQuery(this).val()==locationData[1]){
				parent.jQuery(this).attr('checked',true);
			}else{
				parent.jQuery(this).attr('checked',false);
			}
		});
	}
	parent.jQuery("input[name='_1_H_address']").val(locationStr[2]);
	parent.jQuery("input[name='_1_H_city']").val(locationStr[2]);
	
	parent.jQuery("input[name='_1_H_hot1']").val(locationData[0]);
	parent.jQuery("input[name='_1_H_hot2']").val(locationStr[3]+' '+locationStr[1]);
	parent.jQuery("input[name='_1_H_hot3']").val(locationStr[3]);
	
	parent.jQuery("select[name='_1_H_hotel1']").val(locationData[0]);
	parent.jQuery("select[name='_1_H_hotel2']").append('<option value="'+locationData[1]+'"></option>');
	parent.jQuery("select[name='_1_H_hotel2']").val(locationData[1]);
	parent.jQuery("select[name='_1_H_hotel3']").append('<option value="'+locationData[2]+'"></option>');
	parent.jQuery("select[name='_1_H_hotel3']").val(locationData[2]);
	
}
//parent.jQuery('#popupConMap').width(825);
parent.jQuery('#gMaptips').hide();
parent.jQuery('#gMapIframe').show();
parent.showPopup('popupMap','popupConMap',true,0,0,'','',true);
-->
</script>
</body>
</html>