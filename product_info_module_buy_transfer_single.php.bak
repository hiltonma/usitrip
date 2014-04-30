<?php 

ob_start();
//接送服务 前台选项
//Vincent 2011-8-24
$textareaTips = '如果您有任何其他特殊要求，请在此留言， 我们尽量满足您的需求。(60字内)';
$waterMarkerForAddress= '门牌号码, 街道名称, 城市';
$waterMarkerForPickAddress= '请输入：门牌号码/街道名称/城市名字 /邮政编码';
if(!is_numeric($transfer_products_id)) {$transfer_products_id = $products_id;}
$db_query =tep_db_query( 'SELECT is_transfer, transfer_type FROM '.TABLE_PRODUCTS." WHERE products_id = ".$transfer_products_id );
$transfer_products_info = tep_db_fetch_array($db_query);

$locationJs = '';
$locationArray = tep_transfer_get_locations($transfer_products_id);
foreach($locationArray as $key=>$row){
	$locationJs .= '{"id":"'.$row['products_transfer_location_id'].'","address":"'.format_for_js($row['short_address']).'","zipcode":"'.format_for_js($row['zipcode']).'","type":"'.format_for_js($row['type']).'"},';
}
$locationJs = substr($locationJs,0,-1);

$routeJs = '';$routeCount = 0 ;
$routesArray = tep_transfer_get_routes($transfer_products_id);
foreach($routesArray as $row){
	$routeJs .= '{"loc1":"'.$row['pickup_location_id'].'","loc2":"'.$row['dropoff_location_id'].'"},';
	$routeCount++;
}
$routeJs = substr($routeJs,0,-1);
$hours = array();for($i = 1;$i<=12;$i++){$hours[] = array('id'=>str_pad($i,2,'0',STR_PAD_LEFT),'text'=>str_pad($i,2,'0',STR_PAD_LEFT));}
$minutes = array();for($i = 0;$i<=59;$i=$i+5){$minutes[] = array('id'=>str_pad($i,2,'0',STR_PAD_LEFT),'text'=>str_pad($i,2,'0',STR_PAD_LEFT ));}
$baggage_totals= array();for($i = 0;$i<=6;$i++){	$baggage_totals[] = array('id'=>$i,'text'=>$i);}
$guest_totals = array();for($i = 1;$i<=18;$i++){$guest_totals[] = array('id'=>$i,'text'=>$i);}
$am = array(	array('id'=>'am','text'=>'AM'),	array('id'=>'pm','text'=>'PM'));
?>
<script type="text/javascript" >


function popAlert(msg,js){	
	jQuery("#PopupNoticeWords").html(msg);	
	//document.getElementById("PopupNoticeWords").innerHTML = msg;
	showPopup("PopupNotice","PopupNoticeCon",0);	
	if(typeof(js) != 'undefined'){
		eval(js);
	}
}

var tips = '<?php echo $textareaTips; ?>';
var tipsForAddress = '<?php echo $waterMarkerForAddress; ?>';
var tipsForPickAddress = '<?php echo $waterMarkerForPickAddress; ?>';

var locations = [<?php echo $locationJs ;?>];
var route = [<?php echo $routeJs ;?>];

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
function getLocationInfo(id){
	for(var i=0 ; i<locations.length;i++){
		if(locations[i].id == id){
			return locations[i];
		}
	}
	return {};
}

function getLocSelHtml(routeid,ltype,type,varname,param,divid){
	var ihtml = "";var add=false;
	for(var i=0 ; i<locations.length;i++){
		if(type=='airport'&&locations[i].type == '0')	add = true;
		else if(type=='location'&&locations[i].type == '1')	add = true;
		else if(type=='all')	add = true;
		else	add = false;
		if(add == true){
			if(locations[i].zipcode == '0') text = locations[i].address;
			else text = locations[i].address+"("+locations[i].zipcode+")";
			ihtml += '<li '+param+'><input type="radio" name="'+varname+'"  value="'+locations[i].id+'" />'+text+'</li>';
		}
	}
	ltext = ltype == 0?'起点':'终点';//如果不能找到您到达的地址和邮编请取消上面的选择，请填写你居住地的邮编及详细地址。
	var addForLocation = '<div class="shuttlePostCustomize"><p>其他地区请直接填写你居住地的邮编及详细地址，并取消上面的选择。'
		+'<a href="javascript:;"  onclick=" jQuery(this).parents().prev().find(\'input[type=radio]\').attr(\'checked\',false);jQuery(this).parents().prev().find(\'li\').removeClass(\'click\');" class="blue">取消选择</a></p>	'
		+'<p><label>邮编：</label><?php echo tep_draw_input_field('','' ,'  class="text postcode"  onkeyup="checkSelectStatus(this,\\\'\'+ltext+\'\\\',\'+routeid+\')" ') ?>'
		+'<label>地址：</label><?php echo tep_draw_input_field('',$waterMarkerForAddress ,'  class="text address"  onkeyup="checkSelectStatus(this,\\\'\'+ltext+\'\\\',\'+routeid+\')" style="color:#999"  onBlur="setWatermark(this,\\\'blur\\\')" onFocus="setWatermark(this,\\\'focus\\\')"  onkeydown="setWatermark(this,\\\'keydown\\\');"') ?></p></div>';
	if(type == 'airport'){
		html = '<h4>'+ltext+'<label>(机场)</label></h4><ul class="shuttleUl" id="'+divid+'">'+ihtml+'</ul>';		
	}else if(type == 'location'){
		html = '<h4>'+ltext+'<label>(地址和邮编)</label></h4><ul class="shuttleUl2" id="'+divid+'">'+ihtml+'</ul>'+addForLocation;		
	}else{
		html = '<h4>'+ltext+'<label>(地址和邮编)</label></h4><ul class="shuttleUl2" id="'+divid+'">'+ihtml+'</ul>'+addForLocation;	
	}
	return html
}

function checkSelectStatus(src,ltype,routeid){
	if(jQuery(src).parent().parent().prev().find(":radio:checked").length != 0){
		popAlert("对不起！<br/>请先取消以上"+ltype+"选择，再填写邮编和地址。","SetPopBox('ShuttleRoute"+routeid+"');");
		return ;
	}
}



function setLabel(routeId,type){
	if(typeof(type) == 'undefined'){		
		jQuery("#labelFlightDate"+routeId).html('航班准确起飞时间：');
		jQuery("#labelFlightDeparture"+routeId).html('接应您的准确地点：');		
		jQuery("#fieldPickDate"+routeId+" input").show();
		jQuery("#TmpPickDateDiv"+routeId).show();jQuery("#fieldPickDate"+routeId).show();
	}else{
		jQuery("#labelFlightDate"+routeId).html('航班准确抵达时间：');
		jQuery("#labelFlightDeparture"+routeId).html('期望被送达的地点：');
		jQuery("#fieldPickDate"+routeId+" input").hide();
		jQuery("#TmpPickDateDiv"+routeId).hide();jQuery("#fieldPickDate"+routeId).hide();

	}
}

function setShuttleType(type){
	jQuery(".shuttleUl li").unbind('click');
	jQuery(".shuttleUl2 li").unbind('click');	

	if(type == 1){
		jQuery("#transfer_booking_steps_2").show();
		jQuery("#transfer_booking_steps_3").hide();		
		jQuery("#ShuttleLocationContainer1").html(getLocSelHtml(1,0,'location','pickup1','','pickup1div')+getLocSelHtml(1,1,'airport','dropoff1','','dropoff1div'));
		setLabel(1);	setLabel(2);		
	}else if(type == 2){
		jQuery("#transfer_booking_steps_2").show();
		jQuery("#transfer_booking_steps_3").hide();
		jQuery("#ShuttleLocationContainer1").html(getLocSelHtml(1,0,'airport','pickup1','','pickup1div')+getLocSelHtml(1,1,'location','dropoff1','','dropoff1div'));		
		jQuery("#routeTitle1").html("选择机场接应路线：");
		setLabel(1,1);	setLabel(2,1);
	}else if(type == 3){
		jQuery("#transfer_booking_steps_2").show();
		jQuery("#transfer_booking_steps_3").show();
		jQuery("#ShuttleLocationContainer1").html(getLocSelHtml(1,0,'airport','pickup1','','pickup1div')+getLocSelHtml(1,1,'location','dropoff1','','dropoff1div'));		
		jQuery("#ShuttleLocationContainer2").html(getLocSelHtml(2,0,'location','pickup2','','pickup2div')+getLocSelHtml(2,1,'airport','dropoff2','','dropoff2div'));
		jQuery("#routeTitle1").html("选择机场接应路线：");
		jQuery("#routeTitle2").html("选择送机场路线：");	
		setLabel(1,1);	setLabel(2);
	}else if(type == 4){
		jQuery("#transfer_booking_steps_2").show();
		jQuery("#transfer_booking_steps_3").show();
		jQuery("#ShuttleLocationContainer1").html(getLocSelHtml(1,0,'location','pickup1','','pickup1div')+getLocSelHtml(1,1,'airport','dropoff1','','dropoff1div'));		
		jQuery("#ShuttleLocationContainer2").html(getLocSelHtml(2,0,'airport','pickup2','','pickup2div')+getLocSelHtml(2,1,'location','dropoff2','','dropoff2div'));		
		jQuery("#routeTitle1").html("选择送机场路线：");	
		jQuery("#routeTitle2").html("选择机场接应路线：");	
		setLabel(1);	setLabel(2,1);
	}else if(type == 5){		
		jQuery("#transfer_booking_steps_2").show();
		jQuery("#transfer_booking_steps_3").hide();
		jQuery("#ShuttleLocationContainer1").html(getLocSelHtml(1,0,'all','pickup1',' onclick="setLocationAvaliable(this,\'dropoff1div\')"','pickup1div')+getLocSelHtml(1,1,'all','dropoff1',' onclick="setLocationAvaliable(this,\'pickup1div\')"','dropoff1div'));		
		jQuery("#ShuttleLocationContainer2").html(getLocSelHtml(2,0,'all','pickup2',' onclick="setLocationAvaliable(this,\'dropoff2div\')"','pickup2div')+getLocSelHtml(2,1,'all','dropoff2',' onclick="setLocationAvaliable(this,\'pickup2div\')"','dropoff2div'));		
		jQuery("#routeTitle1").html("选择第一次接送路线：");
		setLabel(1);setLabel(2);
	}else if(type == 6){
		jQuery("#transfer_booking_steps_2").show();
		jQuery("#transfer_booking_steps_3").show();
		jQuery("#ShuttleLocationContainer1").html(getLocSelHtml(1,0,'all','pickup1',' onclick="setLocationAvaliable(this,\'dropoff1div\')"','pickup1div')+getLocSelHtml(1,1,'all','dropoff1',' onclick="setLocationAvaliable(this,\'pickup1div\')"','dropoff1div'));		
		jQuery("#ShuttleLocationContainer2").html(getLocSelHtml(2,0,'all','pickup2',' onclick="setLocationAvaliable(this,\'dropoff2div\')"','pickup2div')+getLocSelHtml(2,1,'all','dropoff2',' onclick="setLocationAvaliable(this,\'pickup2div\')"','dropoff2div'));		
		jQuery("#routeTitle1").html("选择第一次接送路线：");
		jQuery("#routeTitle2").html("选择第二次接送路线：");
		setLabel(1);setLabel(2);
	}
	jQuery("#TextBox_ShuttleTypePop").html(jQuery('input[type=radio][name=shuttleType][value='+type+']').parent().text());
	jQuery('#ConTitleA_ShuttleTypePop').html("可修改");
	jQuery('#TransferType').val(type);
	resetRoute(2);resetRoute(1);
	resetShuttleInfo(1);resetShuttleInfo(2);	

	jQuery(".shuttleUl li").click(function(){
		jQuery(this).parent().find("li").removeClass("click");
		jQuery(this).addClass("click");
		jQuery(this).find(":radio").attr("checked",true); 
	});
	jQuery(".shuttleUl2 li").click(function(){
		 jQuery(this).parent().find("li").removeClass("click");
		jQuery(this).addClass("click");
		jQuery(this).find(":radio").attr("checked",true);       
	});
	jQuery(".shuttleCon li").hover(function(){
	    jQuery(this).addClass("hover");
	},function(){
	    jQuery(this).removeClass("hover");
	});	
	transferBudget();
}

function setLocationAvaliable(src , target){
	//jQuery(src).parent().parent().find("input[]")
	<?php if($transfer_products_info['transfer_type']!='1') echo 'return true;'?>
	var v1 = jQuery(src).find(":radio").attr('value');
	var vv = jQuery("#"+target+"  :checked").val();
	if(v1==vv) return ;

	jQuery("#"+target+" li").each(function(){		
		v2 = jQuery(this).find(":radio").attr('value');
		jQuery(this).unbind("click");
		if(v1 == v2 || !hasRoute(v1,v2)){				
			jQuery(this).find(":radio").attr('checked',false);
			jQuery(this).find(":radio").attr('disabled',true);		
			jQuery(this).css({"color":"#999"});
		}else {
			jQuery(this).find(":radio").removeAttr('disabled');			
			jQuery(this).click(function(){				
				jQuery(this).parent().find("li").removeClass("click");
				jQuery(this).addClass("click");
				jQuery(this).find("input[type=radio]").attr("checked",true);   
			});
			jQuery(this).css({"color":"#111111"});
		}
		});
}

function checkPostCode(postcode){	
	var id = 0 ;
	for(var i=0 ; i<locations.length;i++){
		if(postcode == locations[i].zipcode){
			id = locations[i].id;
			break;
		}
	}	
	return id ;
}

function setRouteText(routeNum, type,id,address,zipcode ){
	if(zipcode == '0') text = address;
	else text = address+"("+zipcode+")";	
	if(type == 'pickup'){
		jQuery("#PickupId"+routeNum).val(id);
		jQuery("#PickupAddress"+routeNum).val(address);
		jQuery("#PickupZipcode"+routeNum).val(zipcode);
		jQuery('#pickup'+routeNum+'address').html(text);
	}else{
		jQuery("#DropoffId"+routeNum).val(id);
		jQuery("#DropoffAddress"+routeNum).val(address);
		jQuery("#DropoffZipcode"+routeNum).val(zipcode);
		jQuery('#dropoff'+routeNum+'address').html(text);
	}
	jQuery("#TextBox_ShuttleRoute"+routeNum).children().show();
	transferBudget();
}

function setRoute(routeNum){
	var pickupObj = jQuery("#pickup"+routeNum+"div");
	var dropoffObj = jQuery("#dropoff"+routeNum+"div");	
	var pickup = pickupObj.find("input[type=radio]:checked").val();
	var pickupText = "";var dropoffText="";
	var dropoff =dropoffObj.find("input[type=radio]:checked").val();
	var postcode_cuz = '';
	//pickup
	var postCodeobj= pickupObj.next().find(".postcode");
	if(typeof(pickup) == 'undefined' && typeof(postCodeobj)!='undefined'){
			postcode_cuz = jQuery.trim(pickupObj.next().find(".postcode").val());
			if(postcode_cuz == ''){
				popAlert("对不起！<br/>请您先选择或填写需要接送的起点。" ,"SetPopBox('ShuttleRoute"+routeNum+"');");
				return ;
			}
			checkid = checkPostCode(postcode_cuz);
			if(checkid >  0){
				var addressText = jQuery.trim(pickupObj.next().find(".address").val());
				if(addressText == tipsForAddress){
					popAlert("对不起！<br/>请填写起点地址信息。",'SetPopBox(\'ShuttleRoute'+routeNum+'\');');					
				}else{
					setRouteText(routeNum,'pickup',checkid,addressText,postcode_cuz);
				}
			}else{
				popAlert("对不起！<br>	我们暂时不能为邮编 "+postcode_cuz+"的区域服务!<br>请检查您输入的邮编或点击右侧自定义服务的“提交所需服务”按钮，提交您的服务需求。","SetPopBox('ShuttleRoute"+routeNum+"');");
				return ;
				//
			}
	}else{
		info = getLocationInfo(pickup);
		setRouteText(routeNum,'pickup',pickup,info.address,info.zipcode);
	}
	//dropoff
	postCodeobj = dropoffObj.next().find(".postcode");
	if(typeof(dropoff) == 'undefined' && typeof(postCodeobj)!='undefined'){
		postcode_cuz = jQuery.trim(dropoffObj.next().find(".postcode").val());
		if(postcode_cuz == ''){
			popAlert("对不起！<br/>请您先选择或填写需要送达的终点。","SetPopBox('ShuttleRoute"+routeNum+"');");
			return ;
		}
		checkid = checkPostCode(postcode_cuz);
		if(checkid >  0){	
			var addressText = jQuery.trim(dropoffObj.next().find(".address").val());
			if(addressText == tipsForAddress){
					popAlert("对不起！<br/>请填写终点地址信息。",'SetPopBox(\'ShuttleRoute'+routeNum+'\');');					
			}else{
				setRouteText(routeNum,'dropoff',checkid,addressText,postcode_cuz);
			}
		}else{
			popAlert("对不起！<br>	我们暂时不能为邮编 "+postcode_cuz+"的区域服务!<br>请检查您输入的邮编或点击右侧自定义服务的“提交所需服务”按钮，提交您的服务需求。","SetPopBox('ShuttleRoute"+routeNum+"');");
			return ;
		}
	}else{
		info = getLocationInfo(dropoff);
		setRouteText(routeNum,'dropoff',dropoff,info.address,info.zipcode);
	}
	//
	jQuery('#ConTitleA_ShuttleRoute'+routeNum).html("可修改");
	transferBudget();
}



function textareaTips(obj,event){	
	if(event == 'focus'){
		if(obj.value == tips){	obj.value = "";obj.style.color='#111';}
		else{		obj.style.color='#111';	}
	}else{		
		if(obj.value == ""){	obj.value = tips;obj.style.color='#999';}
		else{	obj.style.color='#111';}
	}
}

function updateMaxBaggageTotal(guesttotalobj,routenum){
	var guesttotal = jQuery(guesttotalobj).val();
	var lastCarPerson= guesttotal%6;
	var carTotal = Math.floor(guesttotal/6);
	var maxBaggageTotal = carTotal*4 ;
	if(lastCarPerson!=0){
		if(lastCarPerson <= 4)maxBaggageTotal+=6;
		else if(lastCarPerson == 5)maxBaggageTotal+=5;
	}
	var cbt=jQuery("#TmpBaggageTotal"+routenum).val();	
	var html = "";
	for(var i = 0 ;i<=maxBaggageTotal;i++){
		checked = i==cbt?" selected ": ""; 	html+= '<option value="'+i+'"' + checked+'>'+i+'</option>';
	}
	jQuery("#TmpBaggageTotal"+routenum).html(html);
}
function resetShuttleInfo(routeNum){
	jQuery("#TmpFlightNumber"+routeNum).val('');
	jQuery("#TmpFlightDateDiv"+routeNum+" input").val('');
	jQuery("#TmpFlightDeparture"+routeNum).val('');
	jQuery("#TmpComment"+routeNum).val('');
	setWatermark(document.getElementById("TmpFlightDeparture"+routeNum),'blur','pickAddress');
	textareaTips(document.getElementById("TmpComment"+routeNum),'blur');
	
	jQuery("#FlightNumber"+routeNum).val('');
	jQuery("#FlightDeparture"+routeNum).val('');
	jQuery("#FlightArrivalTime"+routeNum).val('');
	jQuery("#PickupId"+routeNum).val('');
	jQuery("#PickupZipcode"+routeNum).val('');
	jQuery("#DropoffId"+routeNum).val('');
	jQuery("#DropoffZipcode"+routeNum).val('');
	jQuery("#GuestTotal"+routeNum).val('');
	jQuery("#BaggageTotal"+routeNum).val('');
	jQuery("#Comment"+routeNum).val('');
	jQuery("#TextBox_ShuttleRoute"+routeNum).children().hide();
	jQuery("#TextBox_ShuttleRoute"+routeNum+"_Detail").html("&nbsp;");
	jQuery("#ConTitleA_ShuttleRoute"+routeNum).html("选择");
	jQuery("#ConTitleA_ShuttleRoute"+routeNum+"_Detail").html("选择");
	jQuery("#GuestTotal"+routeNum).val('');
	jQuery('#tmp_flight_arrival_time'+routeNum+'_date').val('');
	jQuery('#tmp_pick_time'+routeNum+'_date').val('');
}
function resetRoute(routeNum){
	jQuery("#PickupId"+routeNum).val('');
	jQuery("#PickupAddress"+routeNum).val('');
	jQuery("#PickupZipcode"+routeNum).val('');
	
	jQuery("#DropoffId"+routeNum).val('');
	jQuery("#DropoffIdAddress"+routeNum).val('');
	jQuery("#DropoffZipcode"+routeNum).val('');	
	jQuery('#pickup'+routeNum+'address').html('&nbsp;');
	jQuery('#dropoff'+routeNum+'address').html('&nbsp;');
}

function setRouteDetail(routeNum){
	var tType= jQuery("#TransferType").val();
	var flightNumber = jQuery("#TmpFlightNumber"+routeNum).val();
	var flightDeparture = jQuery.trim(jQuery("#TmpFlightDeparture"+routeNum).val());
	if(flightDeparture == tipsForPickAddress) flightDeparture = '';
	
	var flightArrival = jQuery("#TmpFlightDeparture"+routeNum).val();	
	var fdate = jQuery("#TmpFlightDateDiv"+routeNum+" :input[name=tmp_flight_arrival_time"+routeNum+"_date]").val();	
	var hours =  jQuery("#TmpFlightArrivalTime"+routeNum+"Hours").val();
	var minutes =  jQuery("#TmpFlightArrivalTime"+routeNum+"Minutes").val();
	var am =  jQuery("#TmpFlightArrivalTime"+routeNum+"Am").val();

	var fdate2 = jQuery("#TmpPickDateDiv"+routeNum+" :input[name=tmp_pick_time"+routeNum+"_date]").val();	
	var hours2 =  jQuery("#TmpPickTime"+routeNum+"Hours").val();
	var minutes2 =  jQuery("#TmpPickTime"+routeNum+"Minutes").val();
	var am2 =  jQuery("#TmpPickTime"+routeNum+"Am").val();

	
	var guesttotal = jQuery("#TmpGuestTotal"+routeNum).val();
	var baggagetotal = jQuery("#TmpBaggageTotal"+routeNum).val();
	var comment = jQuery("#TmpComment"+routeNum).val();
	
	var isPickRoute = ( ( tType == 1 && routeNum==1)||( tType == 3 && routeNum==2)||( tType == 4 && routeNum==1)||tType==4||tType==5)? true:false;
	if(comment == tips) comment=""; 
	if(comment.length > 60){
		popAlert("备注留言请保持60字以内，当前字数"+comment.length+"。",'SetPopBox(\'ShuttleRoute'+routeNum+'_Detail\');');
		return ;
	}
	comment = comment.substr(0,60);
	var pickd = new Date(Date.parse(fdate2+" "+hours2+":"+minutes2+" "+am2));//pickdate used home to airport
	if(flightNumber=="" || fdate==''|| flightDeparture==''|| (isPickRoute &&fdate2 == "")){		
		popAlert("对不起！<br/>请提供完整的接送服务相关信息。",'SetPopBox(\'ShuttleRoute'+routeNum+'_Detail\');');
		return ;
	}
	
	
	//set detail
	jQuery("#FlightNumber"+routeNum).val(flightNumber);
	jQuery("#FlightDeparture"+routeNum).val(flightDeparture);
	jQuery("#FlightArrivalTime"+routeNum).val(fdate+" "+hours+":"+minutes+" "+am);
	if(isPickRoute)	jQuery("#FlightPickTime"+routeNum).val(fdate2+" "+hours2+":"+minutes2+" "+am2);
	jQuery("#GuestTotal"+routeNum).val(guesttotal);
	jQuery("#BaggageTotal"+routeNum).val(baggagetotal);
	jQuery("#Comment"+routeNum).val(comment);
	//date check
	
	var d = new Date(Date.parse(fdate+" "+hours+":"+minutes+" "+am));	
	if(d.getTime()< new Date().getTime()+3600000*24){
		style=' style="color:#999" ';
		popAlert("对不起！<br/>接送服务需要提前24小时预订。",'SetPopBox(\'ShuttleRoute'+routeNum+'_Detail\');');
		doBudget=false;
	}	else{
		 style="";
		 doBudget = true;
	}
	var html = " <p ><label>航空公司名称/号码：</label>"+flightNumber+"&nbsp;&nbsp;</br/>";
	if(isPickRoute){
		html+="<label>航班起飞准确时间：</label><span "+style+">"+fdate+" "+hours+":"+minutes+" "+am+"</span>&nbsp;&nbsp;</br/>";
		html+="<label>接应您的准确地点：</label>"+flightDeparture+"</span>&nbsp;&nbsp;</br/>";
		html+="<label>希望被接应的时间：</label>"+fdate2+" "+hours2+":"+minutes2+" "+am2+"&nbsp;&nbsp;</br/>";
	}else{
		html+="<label>航班准确抵达时间：</label><span "+style+">"+fdate+" "+hours+":"+minutes+" "+am+"</span>&nbsp;&nbsp;</br/>";
		html+="<label>期望被送达的地点：</label>"+flightDeparture+"</span>&nbsp;&nbsp;</br/>";	
	}
	html+="<label>人数：&nbsp;&nbsp;</label>"+guesttotal+"人&nbsp;&nbsp;<label>行李：</label>"+baggagetotal+"件&nbsp;&nbsp;</p>";
	
	if(comment!='') html+="<p><label>留言：</label>"+comment+"</p>";
	jQuery("#TextBox_ShuttleRoute"+routeNum+"_Detail").html(html);
	jQuery('#ConTitleA_ShuttleRoute'+routeNum+"_Detail").html("可修改");
	if(doBudget == true) transferBudget();
}

function transferBudget(){
	var ttype = jQuery("#TransferType").val();
	if(ttype == '1' || ttype == '2'|| ttype == '5'){
		if(jQuery("#PickupId1").val() != "" && jQuery("#GuestTotal1").val() != "" && jQuery("#Dropoff1").val() != ""){
			auto_update_budget();
		}
	}
	if(ttype == '3' || ttype == '4'|| ttype == '6'){
		if( (jQuery("#PickupId1").val() != "" && jQuery("#GuestTotal1").val() != "" && jQuery("#Dropoff1").val() != "") && (jQuery("#PickupId2").val() != "" && jQuery("#GuestTotal2").val() != "" && jQuery("#Dropoff2").val() != "")){
			auto_update_budget();
		}
	}
}
function setWatermark(obj,e ,target){
	var ot = "";
	if(typeof(target) == 'undefined'){
		ot = "<?php echo $waterMarkerForAddress?>";
	}else if(target=='comment'){
		ot = "<?php echo $tips?>";
	}else{
		ot = "<?php echo $waterMarkerForPickAddress?>";
	}
	var val = jQuery.trim(jQuery(obj).val());
	
	if(e == 'blur'){
		if(val == "") {jQuery(obj).val(ot);}
	}else if(e=='focus'){
		if(val == ot){jQuery(obj).val("");}
	}else if(e=='keydown'){	}
	if(val==''|| val == ot){
		jQuery(obj).css('color','#999');
	}else{
		jQuery(obj).css('color','#000');
	}
}
</script>
<style>
.conTitle .validation_right {
display:none;
}
</style>
	<li id="transfer_booking_steps_1">
		<div class="num"></div>
		<div class="con">
			<div id="ConTitle_ShuttleTypePop" class="conTitle conTitleActive">
				<h2>请选择接驳类型：<a id="ConTitleA_ShuttleTypePop" href="javascript:;" onclick="jQuery('.choosePop').css('z-index','0');jQuery('#ShuttleTypePop input[value='+jQuery('#TransferType').val()+']').parent().click()">选择</a></h2>
				<!--shuttle type select dialog begin -->
				<div id="Close_ShuttleTypePop" class="close shuttleClose" style="display: none;"><a href="javascript:void(0);"></a></div>
				<div id="ShuttleTypePop" class="choosePop shuttlePop"
					style="display: none;">
					<div class="shuttleCon">
						<ul class="shuttleUl">
							<li class=""  ><input type="radio"  value="1"  name="shuttleType">居住地送往机场服务 (单次服务)</li>
							<li class=""  ><input type="radio"  value="2"  name="shuttleType">机场接应送往居住地服务 (单次服务)</li>
							<li class=""  ><input type="radio"  value="3"  name="shuttleType">机场接应送往居住地，居住地送往机场服务(两次服务)</li>
							<li class=""  ><input type="radio"  value="4"  name="shuttleType">居住地送往机场服务，机场接应送往居住地(两次服务)</li>
							<?php if($transfer_products_info['transfer_type']  == '1') {?>
							<li class=""  ><input type="radio"  value="5"  name="shuttleType">区域接应服务（单次）</li>
							<li class=""  ><input type="radio"  value="6"  name="shuttleType">区域接应服务（双次）</li>
							<?php }?>
						</ul>
					</div>
					<div class="btnCenter"><a href="javascript:;"  onclick="jQuery('.choosePop').css('z-index','0');setShuttleType(jQuery('input[type=radio][name=shuttleType]:checked').val());" class="btn btnOrange"><button type="button">确 定</button></a><a href="javascript:void(0);" class="btn btnGrey"><button  type="button">取 消</button>	</a></div>
				</div>
				<!--shuttle type select dialog end -->
			</div>
			<div class="place" id="TextBox_ShuttleTypePop" onclick="SetPopBox('ShuttleTypePop');"></div>
			<?php
					echo tep_draw_hidden_field('transferType','',' id="TransferType" ');
					
					echo tep_draw_hidden_field('transfer_products_id',$transfer_products_id,' id="TransferProductsId" ');
					echo tep_draw_hidden_field('flight_number1','',' id="FlightNumber1" ');
					echo tep_draw_hidden_field('flight_departure1','',' id="FlightDeparture1" ');
					echo tep_draw_hidden_field('flight_arrival_time1','',' id="FlightArrivalTime1" ');
					echo tep_draw_hidden_field('flight_pick_time1','',' id="FlightPickTime1" ');
					echo tep_draw_hidden_field('pickup_id1','',' id="PickupId1" ');
					echo tep_draw_hidden_field('pickup_address1','',' id="PickupAddress1" ');
					echo tep_draw_hidden_field('pickup_zipcode1','',' id="PickupZipcode1" ');
					echo tep_draw_hidden_field('dropoff_id1','',' id="DropoffId1" ');
					echo tep_draw_hidden_field('dropoff_address1','',' id="DropoffAddress1" ');
					echo tep_draw_hidden_field('dropoff_zipcode1','',' id="DropoffZipcode1" ');
					echo tep_draw_hidden_field('guest_total1','',' id="GuestTotal1" ');
					echo tep_draw_hidden_field('baggage_total1','',' id="BaggageTotal1" ');
					echo tep_draw_hidden_field('comment1','',' id="Comment1" ');
					
					echo tep_draw_hidden_field('flight_number2','',' id="FlightNumber2" ');
					echo tep_draw_hidden_field('flight_departure2','',' id="FlightDeparture2" ');
					echo tep_draw_hidden_field('flight_arrival_time2','',' id="FlightArrivalTime2" ');
					echo tep_draw_hidden_field('flight_pick_time2','',' id="FlightPickTime2" ');
					echo tep_draw_hidden_field('pickup_id2','',' id="PickupId2" ');
					echo tep_draw_hidden_field('pickup_address2','',' id="PickupAddress2" ');
					echo tep_draw_hidden_field('pickup_zipcode2','',' id="PickupZipcode2" ');
					echo tep_draw_hidden_field('dropoff_id2','',' id="DropoffId2" ');
					echo tep_draw_hidden_field('dropoff_address2','',' id="DropoffAddress2" ');
					echo tep_draw_hidden_field('dropoff_zipcode2','',' id="DropoffZipcode2" ');
					echo tep_draw_hidden_field('guest_total2','',' id="GuestTotal2" ');
					echo tep_draw_hidden_field('baggage_total2','',' id="BaggageTotal2" ');
					echo tep_draw_hidden_field('comment2','',' id="Comment2" ');
			?>
		</div>
	</li>
<?php 
	for($i=2;$i<=3;$i++) {
		$num = $i ;
		$routeId = $i-1;
	
?>
	<li id="transfer_booking_steps_<?php echo $num?>" style="display:none">
		<div class="num num<?php echo $num?>"></div>
		<div class="con">
			<div class="conTitle" id="ConTitle_ShuttleRoute<?php echo $routeId?>">
				<h2>	<span id="routeTitle<?php echo $routeId?>">请选择线路：</span><a href="javascript:;" id="ConTitleA_ShuttleRoute<?php echo $routeId?>">可选择</a></h2>
				<!-- 起点 终点选框开始 -->
				<div class="close shuttleClose" id="Close_ShuttleRoute<?php echo $routeId?>"><a href="javascript:void(0);"></a></div>
				<div class="choosePop shuttlePop" id="ShuttleRoute<?php echo $routeId?>">
					<div class="shuttleCon" id="ShuttleLocationContainer<?php echo $routeId?>"></div>
					<div class="btnCenter">	<a class="btn btnOrange" href="javascript:;" onclick="setRoute(<?php echo $routeId?>)"><button type="button">确	定</button> </a><a class="btn btnGrey" href="javascript:void(0);"><button type="button">取 消</button> </a></div>
				</div>
				</div>
				<!-- 起点 终点选框结束 -->

			<div class="place"  id="TextBox_ShuttleRoute<?php echo $routeId?>" onclick="SetPopBox('ShuttleRoute<?php echo $routeId?>');"><label style="display:none">起点：</label><span id="pickup<?php echo $routeId?>address"></span>&nbsp;<label  style="display:none">终点：</label><span id="dropoff<?php echo $routeId?>address"></span></div>

			<div class="conTitle" id="ConTitle_ShuttleRoute<?php echo $routeId?>_Detail">
				<h2>请设置接应时间、乘车人数、行李数：<a href="javascript:;" id="ConTitleA_ShuttleRoute<?php echo $routeId?>_Detail">可选择</a></h2>
				<!-- 人数选框开始 -->
				<div class="close shuttleClose" id="Close_ShuttleRoute<?php echo $routeId?>_Detail"><a href="javascript:void(0);"></a></div>
				<div class="choosePop shuttlePop" id="ShuttleRoute<?php echo $routeId?>_Detail">
					<div class="shuttleFlight">					
					<p>
						<label>航空公司名称/号码:</label><?php echo tep_draw_input_field('tmp_flight_number'.$routeId ,'' ,'  class="text flightNum"  id="TmpFlightNumber'.$routeId.'"') ?>						
					</p>
					
			        <p><label id="labelFlightDate<?php echo $routeId?>" style="float:left">航班准确起飞时间:</label>
			        	<div style="float:left" id="TmpFlightDateDiv<?php echo $routeId?>">
						<script type="text/javascript">
								var flightdate<?php echo $routeId?> = new ctlSpiffyCalendarBox("flightdate<?php echo $routeId?>", "cart_quantity", "tmp_flight_arrival_time<?php echo $routeId?>_date","btnfdate<?php echo $routeId?>",'',scBTNMODE_CUSTOMBLUE);
								flightdate<?php echo $routeId?>.dateFormat="MM/dd/yyyy";	
								flightdate<?php echo $routeId?>.readonly=true;										 				 
								flightdate<?php echo $routeId?>.useDateRange = true;
								flightdate<?php echo $routeId?>.focusClick=true;
								var d = new Date(new Date().getTime() + 3600000*24);
								flightdate<?php echo $routeId?>.setMinDate(d.getFullYear() , d.getMonth()+1,d.getDate());
								flightdate<?php echo $routeId?>.JStoRunOnSelect = '';
								flightdate<?php echo $routeId?>.writeControl();
							</script>
							</div>
							<?php echo tep_draw_pull_down_menu('tmp_flight_arrival_time'.$routeId.'_hours', $hours,9,'id="TmpFlightArrivalTime'.$routeId.'Hours"')?>
							<?php echo tep_draw_pull_down_menu('tmp_flight_arrival_time'.$routeId.'_minutes', $minutes,30,'id="TmpFlightArrivalTime'.$routeId.'Minutes"')?>
							<?php echo tep_draw_pull_down_menu('tmp_flight_arrival_time'.$routeId.'_am', $am,'am','id="TmpFlightArrivalTime'.$routeId.'Am"')?>
			        </p>
					<p>
					<label id="labelFlightDeparture<?php echo $routeId?>">接应您的准确地点:</label><?php echo tep_draw_input_field('tmp_flight_departure'.$routeId ,$waterMarkerForPickAddress ,'  class="text flightAddr"  id="TmpFlightDeparture'.$routeId.'" onFocus="setWatermark(this,\'focus\',\'pickAddress\')" style="color:#999" onBlur="setWatermark(this,\'blur\',\'pickAddress\')"') ?>
					</p>
					<div id="fieldPickDate<?php echo $routeId?>">
					<p>
						<label style="float:left">期望被接应的时间:</label>
						<div style="float:left" id="TmpPickDateDiv<?php echo $routeId?>">
							<script type="text/javascript">
								var pickdate<?php echo $routeId?> = new ctlSpiffyCalendarBox("pickdate<?php echo $routeId?>", "cart_quantity", "tmp_pick_time<?php echo $routeId?>_date","btnpdate<?php echo $routeId?>",'',scBTNMODE_CUSTOMBLUE);
								pickdate<?php echo $routeId?>.dateFormat="MM/dd/yyyy";	
								pickdate<?php echo $routeId?>.readonly=true;
								pickdate<?php echo $routeId?>.useDateRange = true;
								pickdate<?php echo $routeId?>.focusClick=true;
								var d = new Date(new Date().getTime() + 3600000*24);
								pickdate<?php echo $routeId?>.setMinDate(d.getFullYear() , d.getMonth()+1,d.getDate());
								pickdate<?php echo $routeId?>.JStoRunOnSelect = '';
								pickdate<?php echo $routeId?>.writeControl();	
							</script>
						</div>
						<?php echo tep_draw_pull_down_menu('tmp_pick_time'.$routeId.'_hours', $hours,9,'id="TmpPickTime'.$routeId.'Hours"')?>
						<?php echo tep_draw_pull_down_menu('tmp_pick_time'.$routeId.'_minutes', $minutes,30,'id="TmpPickTime'.$routeId.'Minutes"')?>
						<?php echo tep_draw_pull_down_menu('tmp_pick_time'.$routeId.'_am', $am,'am','id="TmpPickTime'.$routeId.'Am"')?>						
			        </p>
					 <?php //<p class="pickDateTips">接应客人的大致时间，国际航班提前4小时，美国国内航班提前3小时到居住地接应。</p> ?>
					</div>
			        <p>
			        	<label>需要接应的人数:</label><?php echo tep_draw_pull_down_menu('tmp_guest_total'.$routeId, $guest_totals,'1','    id="TmpGuestTotal'.$routeId.'" onchange="updateMaxBaggageTotal(this,'.$routeId.')"')?> 人
			        	<label class="thing">行李:</label> <?php echo tep_draw_pull_down_menu('tmp_baggage_total'.$routeId, $baggage_totals,'0','  class="text"  id="TmpBaggageTotal'.$routeId.'"')?> 件</p>
			        <p><label style="float:left">特殊要求备注留言:</label>
			        <?php 
			        	echo tep_draw_textarea_field(
			        				'tmp_comment'.$routeId, "0", "42", "3",
			        				$tips,
			        				" id=\"TmpComment".$routeId."\"  onBlur=\"textareaTips(this,'blur');\"  onfocus=\"textareaTips(this,'focus')\" class=\"textarea\" style=\"color:#999\"",false
			        			);
			        ?>
			       </p>
			       </div>
					<div class="shuttleFlightTip">
						<h5>温馨提示：</h5>
						<p>1.我们会根据航班信息合理安排接应时间，请提供准确信息。</p>
						<p>2.四岁以上必须占座位，没有大人小孩之分，暂不提供CAR SEAT服务，请谅解。</p>
						<p>3.我们统一使用具备TCP商业保险的7人座商务休旅车，乘坐人数最多6人，超过6人将按照多一部接驳车的方式计算。</p>
						<p>4.乘坐6位客人，大件行李不超过4件；乘坐5位客人，大件行李不超过5件；乘坐4位或4位以下客人，大件行李不超过6件。超过请邮件到 service@usitrip.com 寻求报价。随身携带手提包不限制。</p>
						<p>5.行李规格：每件长*宽*高均不超过158cm，不超过32kg。</p>
					
					</div>
					<div class="btnCenter"><a class="btn btnOrange" href="javascript:;" onclick="setRouteDetail(<?php echo $routeId?>);return false;"><button type="button">确	定</button> </a><a class="btn btnGrey" href="javascript:void(0);"><button type="button">取 消</button> </a></div>
				</div>
			</div>
			<!-- 人数选框结束 -->
			  <div class="place"  id="TextBox_ShuttleRoute<?php echo $routeId?>_Detail" onclick="SetPopBox('ShuttleRoute<?php echo $routeId?>_Detail');">&nbsp;</div>
		</div>
	</li>
<?php }

echo db_to_html(ob_get_clean());

?>
<script type="text/javascript">
setShuttleType(1);
</script>
