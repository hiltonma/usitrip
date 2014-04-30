<script type="text/javascript">
<?php  
$db_query =tep_db_query( 'SELECT is_transfer, transfer_type FROM '.TABLE_PRODUCTS." WHERE products_id = ".intval($transfer_products_id) );
$transfer_products_info = tep_db_fetch_array($db_query);
$locationJs = '';
$locationArray = tep_transfer_get_locations(intval($transfer_products_id));
foreach($locationArray as $key=>$row){
	$locationJs .= '{"id":"'.$row['products_transfer_location_id'].'","address":"'.format_for_js($row['short_address']).'","zipcode":"'.format_for_js($row['zipcode']).'","type":"'.format_for_js($row['type']).'"},';
}
$locationJs = substr($locationJs,0,-1);
$routeJs = '';$routeCount = 0 ;
$routesArray = tep_transfer_get_routes(intval($transfer_products_id));
foreach($routesArray as $row){
	$routeJs .= '{"loc1":"'.$row['pickup_location_id'].'","loc2":"'.$row['dropoff_location_id'].'"},';
	$routeCount++;
}
$routeJs = substr($routeJs,0,-1);

echo 'var locations=['.$locationJs .'];';
echo 'var route=['.$routeJs .'];';
?>
function setTransferAddress(id,addrId,zipcodeId){
	var addr='';
	var zipcode ='';
	for(i=0;i<locations.length;i++){
		if(locations[i].id==id) {
			addr = locations[i].address;
			zipcode = locations[i].zipcode;
			break;
		}
	}
	jQuery('#'+addrId).val(addr);
	jQuery('#'+zipcodeId).val(zipcode);
}
function hasRoute(loc1,loc2){
	for(var i=0 ; i < route.length;i++){
		if((route[i].loc1 == loc1 && route[i].loc2 == loc2 )||(route[i].loc2 == loc1 && route[i].loc1 == loc2 )){
			return true;
		}
	}
	return false;
}
function getLocationTextById(id){
	for(var i=0 ; i<locations.length;i++){
		if(locations[i].id == id){
			if(locations[i].zipcode == '0') text = locations[i].address;
			else text = locations[i].address+"("+locations[i].zipcode+")";
			return text;
		}
	}
	return '';
}

function setLocationAvaliable(srcobj , targetId){
	//jQuery(src).parent().parent().find("input[]")
	<?php if($product_info['transfer_type']!='1') echo 'return true;' //只有固定线路的情况需要检查选项的可用性?>
	var v1 = jQuery(srcobj).val();
	jQuery("#"+targetId+" option").each(function(){
		var v2 = jQuery(this).attr("value");
		if(v2 == v1 || !hasRoute(v1,v2)){
			jQuery(this).attr("selected" ,false);
			jQuery(this).attr("disabled" ,true);
		}else{
			jQuery(this).removeAttr("disabled");	
		}
	});
}

function setTransferOption(objid , type ){
	var html = '<option value="0"> -------------- </option>';
	if(type == 'reset') {	jQuery("#"+objid).html("");return ;}
	var value = jQuery("#"+objid).val();
	for(i = 0 ;i<locations.length;i++){
		if( type=='all' || (type=='airport'&&locations[i].type == '0') || (type=='location'&&locations[i].type == '1') ){			
			selected = value == locations[i].id ? ' selected  ':'';
			if(locations[i].type == '0') text = "(Airport)"+locations[i].address;
			else text = "("+locations[i].zipcode+")"+locations[i].address;
			html+= '<option value="'+locations[i].id+'" '+selected+'>'+text+'</option>';
		}
	}
	jQuery("#"+objid).html(html);
}

function setTransferType(type){
	if(type == 1) {
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").hide();height = "450px";
		setTransferOption("PickupId1",'location');setTransferOption("DropoffId1",'airport');
		setTransferOption("PickupId2",'reset');	setTransferOption("DropoffId2",'reset');				
	}else if(type == 2 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").hide();height = "450px";
		setTransferOption("PickupId1",'airport');	setTransferOption("DropoffId1",'location');
		setTransferOption("PickupId2",'reset');	setTransferOption("DropoffId2",'reset');		
	}else if(type == 3 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").show();	height = "700px";
		setTransferOption("PickupId1",'airport');setTransferOption("DropoffId1",'location');
		setTransferOption("PickupId2",'location');	setTransferOption("DropoffId2",'airport');		
	}else if(type == 4 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").show();	height = "700px";
		setTransferOption("PickupId1",'location');setTransferOption("DropoffId1",'airport');
		setTransferOption("PickupId2",'airport');	setTransferOption("DropoffId2",'location');	
	}else if(type == 5 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").hide();	height = "450px";
		setTransferOption("PickupId1",'all');setTransferOption("DropoffId1",'all');
		setTransferOption("PickupId2",'all');	setTransferOption("DropoffId2",'all');	
	}else if(type == 6 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").show();	height = "700px";
		setTransferOption("PickupId1",'all');setTransferOption("DropoffId1",'all');
		setTransferOption("PickupId2",'all');	setTransferOption("DropoffId2",'all');	
	}
}

function updateMaxBaggageTotal(gtid,btid){
	var guesttotal = jQuery('#'+gtid).val();
	var lastCarPerson= guesttotal%6;
	var carTotal = Math.floor(guesttotal/6);
	var maxBaggageTotal = carTotal*4 ;
	if(lastCarPerson!=0){
		if(lastCarPerson <= 4)maxBaggageTotal+=6;
		else if(lastCarPerson == 5)maxBaggageTotal+=5;
	}	
	var cbt=jQuery("#"+btid).val();	
	var html = "";
	for(var i = 0 ;i<=maxBaggageTotal;i++){
		checked = i==cbt?" selected ": ""; 	
		html+= '<option value="'+i+'"' + checked+'>'+i+'</option>';
	}
	jQuery("#"+btid).html(html);
}
</script>
<?php 
		$transfer_info_array = array();
		//print_vars($transfer_info_array);
		$transfer_type_options = array(
			array('id'=>'1','text'=>'送机场服务(单次)'),
			array('id'=>'2','text'=>'机场接应服务（单次）'),
			array('id'=>'3','text'=>'机场接应，送机场服务（两次服务）'),
			array('id'=>'4','text'=>'送机场，机场接应服务（两次服务）'),
			array('id'=>'5','text'=>'区域接应服务（单次）'),
			array('id'=>'6','text'=>'区域接应服务（双次）')
		);
		if($product_info['transfer_type'] == '0'){
			unset($transfer_type_options[4]);unset($transfer_type_options[5]); //洛杉矶没有区域接送
		}
		$locations = tep_transfer_get_locations($products_id );
		$locationsPulldown = array(array('id'=>'0' , 'text'=>db_to_html('----------')));
		foreach($locations as $v) {			
			if($v['type'] == '0') $zipcode = 'Airport';else $zipcode = $v['zipcode'];
			$locationsPulldown[] = array('id'=>$v['products_transfer_location_id'] , 'text'=>db_to_html('('.$zipcode.')'.$v['short_address']));
		}
		$guestPulldown = array();
		for($guest_num = 1 ;$guest_num <=18;$guest_num++){
			$guestPulldown[] = array('id'=>$guest_num,'text'=>$guest_num);
		}
		$bagPulldown = array();
		for($bag_num = 0 ;$bag_num <=36;$bag_num++){
			$bagPulldown[] = array('id'=>$bag_num,'text'=>$bag_num);
		}
		$routes = tep_transfer_get_routes($products_id);
		$output =  '<b>接驳类型</b>'.tep_draw_pull_down_menu('transferType' , $transfer_type_options,$transfer_info_array['transferType'] , ' onchange="setTransferType(this.options[this.selectedIndex].value)"').'<br/><br/>';
		for($routeIndex=1;$routeIndex<=2 ;$routeIndex++){
			$output.=  '<div id="transfer_route_'.$routeIndex.'"><span style="ont-family: Tahoma,SimSun,Arial,Helvetica,sans-serif;font-size:18px;color:#108BCD;font-weight:bold;font-style:italic">'.$routeIndex.'.</span>';
			$output.= '<b>起点</b>： '.tep_draw_pull_down_menu('pickup_id'.$routeIndex ,$locationsPulldown,$transfer_info_array['pickup_id'.$routeIndex] , ' id="PickupId'.$routeIndex.'" onchange="setTransferAddress(jQuery(this).val() ,\'PickupAddress'.$routeIndex.'\',\'PickupZipcode'.$routeIndex.'\');setLocationAvaliable(this,\'DropoffId'.$routeIndex.'\')"').'<br>';
			$output.= '<b>地址</b>： '.tep_draw_input_field("pickup_address".$routeIndex ,$transfer_info_array['pickup_address'.$routeIndex],' size="40" id="PickupAddress'.$routeIndex.'"'  ).''.tep_draw_hidden_field("pickup_zipcode".$routeIndex ,$transfer_info_array['pickup_zipcode'.$routeIndex],' id="PickupZipcode'.$routeIndex.'"'  ).'<br/>';
			$output.= '<b>终点</b>：'.tep_draw_pull_down_menu('dropoff_id'.$routeIndex ,$locationsPulldown,$transfer_info_array['dropoff_id'.$routeIndex] , ' id="DropoffId'.$routeIndex.'" onchange="setTransferAddress(jQuery(this).val() ,\'DropoffAddress'.$routeIndex.'\',\'PickupZipcode'.$routeIndex.'\');setLocationAvaliable(this,\'PickupId'.$routeIndex.'\')"').'<br>';
			$output.= '<b>地址</b>：'.tep_draw_input_field("dropoff_address".$routeIndex ,$transfer_info_array['dropoff_address'.$routeIndex],' size="40"  id="DropoffAddress'.$routeIndex.'"').'<br/>';
			$output.= '<b>航班号</b>：'.tep_draw_input_field("flight_number".$routeIndex ,$transfer_info_array['flight_number'.$routeIndex] ,' size="7"').'&nbsp;&nbsp;<b>出发地点</b>:'.tep_draw_input_field("flight_departure".$routeIndex ,$transfer_info_array['flight_departure'.$routeIndex] );
			$output.= '<br/><b>抵达时间</b>：'.tep_draw_input_field("flight_arrival_time".$routeIndex ,$transfer_info_array['flight_arrival_time'.$routeIndex] ).' 抵达时间必须使用 mm/dd/yyyy hh:mm A 的结构例如:12/01/2011 9:35 AM';
			$output.= '<br/><b>人数</b>：'.tep_draw_pull_down_menu("guest_total".$routeIndex ,$guestPulldown,$transfer_info_array['guest_total'.$routeIndex] ,' id="GuestTotal'.$routeIndex.'" onchange="updateMaxBaggageTotal(\'GuestTotal'.$routeIndex.'\',\'BaggageTotal'.$routeIndex.'\')"').'人&nbsp;&nbsp; <b>行李</b>:'.tep_draw_pull_down_menu("baggage_total".$routeIndex ,$bagPulldown,$transfer_info_array['baggage_total'.$routeIndex] ,' id="BaggageTotal'.$routeIndex.'"  onchange="updateMaxBaggageTotal(\'GuestTotal'.$routeIndex.'\',\'BaggageTotal'.$routeIndex.'\')"');
			$output.= '<br/><b>留言</b>：<br/>'.tep_draw_textarea_field("comment".$routeIndex, true, 50, 2,$transfer_info_array['comment'.$routeIndex]);
			$output.= '</div>';
		}
		echo db_to_html($output);
		echo '<script language="javascript">setTransferType(1);updateMaxBaggageTotal(\'GuestTotal1\',\'BaggageTotal1\');updateMaxBaggageTotal(\'GuestTotal2\',\'BaggageTotal2\');</script>';
		?>
