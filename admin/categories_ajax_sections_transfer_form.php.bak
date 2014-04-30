<?php
$query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_LOCATION." WHERE products_id = ".intval($products_id).' ORDER BY type ASC,products_transfer_location_id ASC');
$product_location_list  = array();
$product_location_droplist = array(array('id'=>'0','text'=>'Select Location')); 

while($row = tep_db_fetch_array($query )){
	$product_location_list[] = $row ;	
	$product_location_droplist[] = array('id'=>$row['products_transfer_location_id'],'text'=>$row['short_address']."(".$row['zipcode'].")");
}

$query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_ROUTE."  WHERE products_id = ".intval($products_id).' ORDER BY products_transfer_route_id ASC');
$product_route_list = array();
while($row = tep_db_fetch_array($query )){
	$product_route_list[] =  $row;
}
?>

<style>
	.transferLocationList {
		font-size:12px;
		height:300px;overflow:auto;
		width:100%;
		background:#F4F4F4;
	}
	.panel td {text-align:left}
	.transferLocationList  table {
	}
	.panel  th {
		background:#C9C9C9;
		color:#fff;font-size:12px;text-align:left
	}		
	.panel  .addbox {
		background:#C9C9C9;
	}
	</style>
	<script type="text/javascript">
	//location
	var index = 0;
	function addTransferLocation(){
		var address = jQuery('#tmpTransferShortAddress').val();
		var zipcode =  jQuery('#tmpTransferZipcode').val();
		if(address == '' ||  zipcode == ''){					
			return ;
		}
		jQuery('#tmpTransferShortAddress').val("");		
		jQuery('#tmpTransferZipcode').val("");
		var type =  jQuery('#tmpTransferType').val();		
		if(type == '0' ){
			type_html = '<?php  echo tep_draw_pull_down_menu('new_type[\'+index+\']',array(array('id'=>'0','text'=>'Airport'),array('id'=>'1','text'=>'Location')),'0')?>';
		}else{
			type_html = '<?php  echo tep_draw_pull_down_menu('new_type[\'+index+\']',array(array('id'=>'0','text'=>'Airport'),array('id'=>'1','text'=>'Location')),'1')?>';
		}
		var randid = 'transfer_location_'+index;		
		jQuery('#insertLocationBefore').before('<tr id="'+randid+'"><td><?php echo  tep_draw_input_field('new_short_address[\'+index+\']','\'+address+\'',' size="40"') ?></td><td><?php echo  tep_draw_input_field('new_zipcode[\'+index+\']','\'+zipcode+\'',' size="10"') ?></td><td>'+type_html+'</td><td><button type="button"  onclick="deleteTransferLocation(\''+randid+'\',\'0\') ;"> <img src="images/no.gif" width="15" height="15" title="del" alt="del" /> </button></td></tr>');
		index++;
	}
	function deleteTransferLocation(objid ,locid){
		if(locid != 0 ){
			var keeper = jQuery("#deletedLocationKeeper");
			var value = jQuery.trim(keeper.val());
			if(value == '') {
				keeper.val(locid);
			}else{
				keeper.val(value+","+locid);
			}
		}		
		jQuery("tr[id="+objid+"]").remove();
	}

	function updateTransferLocation(objid,locid){
		if(locid != 0 ){
			var keeper = jQuery("#updatedLocationKeeper");
			var value = jQuery.trim(keeper.val());
			if(value == '') {
				keeper.val(locid);
			}else{
				var ids = value.slice(",");
				for(i=0;i<ids.length;i++){
					if(locid == ids[i]){
						return ;
					}
				}				
				keeper.val(value+","+locid);
			}
		}
	}
	//route function
	var newRouteIndex = 0 ;
	function getLocationSelector(name ,value,param){
		var items =[	
			{"id":'0','text':'Select Location '}
			<?php foreach($product_location_droplist as $item){
			echo ',{"id":"'.$item['id'].'","text":"'.format_for_js($item['text'])."\"}\n";
			}?>
			];
			var html = '<select name="'+name+'" '+param+'>';
			for(var i=1 ; i<items.length;i++){
				if(items[i].id == value){
					html+='<option value="'+items[i].id+'" selected >'+items[i].text+'</option>';
				}else{
					html+='<option value="'+items[i].id+'"  >'+items[i].text+'</option>';
				}
			}
			html+="</select>";
			return html;
	}
	function addRoute(){
		var pickup = jQuery('#tmpPickupLocationId').val();
		var dropoff =  jQuery('#tmpDropoffLocationId').val();
		var price_base =  jQuery('#tmp_price_base').val();
		var price1 =  jQuery('#tmp_price1').val();
		var price1_cost =  jQuery('#tmp_price1_cost').val();
		var price2 =  jQuery('#tmp_price2').val();
		var price2_cost =  jQuery('#tmp_price2_cost').val();
		var paramSel ='';		
		if(pickup == '0' || dropoff=='0'){
			return ;
		}
		if(pickup == dropoff){
			alert(" different pickup and drop location needed! ");
			return ;
		}
		
		var html='<tr id="route_'+newRouteIndex+'">	<td>'+getLocationSelector('new_pickup_location_id['+newRouteIndex+']',pickup,paramSel)+'</td>';
		 html+= '<td width="30%">'+getLocationSelector('new_dropoff_location_id['+newRouteIndex+']',dropoff,paramSel)+'</td>';
		// html+= '<td width="30%"><input name="new_price_base['+newRouteIndex+']" value="'+price_base+'"/></td>';
		 html+= '<td width="10%"><input name="new_price1['+newRouteIndex+']" value="'+price1+'"/></td>';		 
		 html+= '<td width="10%"><input name="new_price2['+newRouteIndex+']" value="'+price2+'"/></td>';
		 html+='<td width="10%"><input name="new_price1_cost['+newRouteIndex+']" value="'+price1_cost+'"/></td>';
		 html+='<td width="10%"><input name="new_price2_cost['+newRouteIndex+']" value="'+price2_cost+'"/></td>';
		 html+='<td  width="10%"><button type="button"  onclick="deleteRoute(\'route_'+newRouteIndex+'\',\'0\');"> <img src="images/no.gif" width="15" height="15" title="del" alt="del" /> </button></td>';
		jQuery('#insertRouteBefore').before(html);
		newRouteIndex++;
	}

	function deleteRoute(objid,rid){
		if(rid != 0 ){
			var keeper = jQuery("#deletedRouteKeeper");
			var value = jQuery.trim(keeper.val());
			if(value == '') {
				keeper.val(rid);
			}else{
				keeper.val(value+","+rid);
			}
		}		
		jQuery("tr[id="+objid+"]").remove();
	}

	function updateRoute(objid,locid){
		if(locid != 0 ){
			var keeper = jQuery("#updatedRouteKeeper");
			var value = jQuery.trim(keeper.val());
			if(value == '') {
				keeper.val(locid);
			}else{
				var ids = value.slice(",");
				for(i=0;i<ids.length;i++){
					if(locid == ids[i]){
						return ;
					}
				}				
				keeper.val(value+","+locid);
			}
		}
	}
	</script>
 <form name="new_product"  id="new_product">
	<table width="100%" class="main"  cellspacing="0" cellpadding="2" border="0" bgcolor="#FFFFFF">
		<!-- <tr>
			<th  valign="top" width="20%">Transfer Type  :</th>
			<td>
			<?php
			//接送方式选择
			echo tep_draw_checkbox_field('transfer_type[0]' ,'0').' 送机场服务（单次） ';
			echo tep_draw_checkbox_field('transfer_type[1]' ,'1').' 机场接应服务（单次） ';
			echo tep_draw_checkbox_field('transfer_type[2]' ,'2').' 机场接应，送机场服务（两次服务） ';
			echo tep_draw_checkbox_field('transfer_type[3]' ,'3').' 送机场，机场接应服务（两次服务） ';
			echo tep_draw_checkbox_field('transfer_type[4]' ,'4').' 长途客车接应服务（单次）／双次 ';
			?>
			</td>
		</tr> -->
		<tr>
			<th  valign="top" width="20%">Location :</th>
			<td  class="panel" width="80%">
			<table width="90%" cellspacing="1" cellpadding="3" border="0" style="border:1px solid gray">	
			<tr><th width="45%">Location</th><th width="20%">ZipCode</th><th width="15%">Type</th><th width="20%">&nbsp;</th></tr>
			<tr id="panel" class="addbox">
				<td><?php echo tep_draw_input_field('tmp_short_address','',' id="tmpTransferShortAddress" size="40"')?></td>
				<td><?php echo tep_draw_input_field('tmp_zipcode','','id="tmpTransferZipcode" size="10" ')?></td>
				<td><?php echo tep_draw_pull_down_menu('tmp_type',array(array('id'=>'0','text'=>'Airport'),array('id'=>'1','text'=>'Location')),'1',' id="tmpTransferType"')?></td>
				<td>
					<button type="button"  onclick="addTransferLocation();" );"> Add </button>
					<?php  echo tep_draw_hidden_field('deleted_location','',' id ="deletedLocationKeeper"  '); ?>
					<?php  echo tep_draw_hidden_field('updated_location','',' id ="updatedLocationKeeper"  '); ?>
				</td>
			</tr>		
			</table>

			<div class="transferLocationList" style="margin-top:3px;">
			<table width="90%" cellspacing="1" cellpadding="3" border="0">			
			<?php 
				foreach($product_location_list as $rowindex=>$row){
				$id = $row['products_transfer_location_id'];
				?>
			<tr  id="loc_<?php echo $id?>">
				<td  width="45%"><?php echo tep_draw_input_field('short_address['.$id.']',$row['short_address'],' size="40" onkeyup="updateTransferLocation(\'loc_'.$id.'\',\''.$id.'\')"')?></td>
				<td  width="20%"><?php echo tep_draw_input_field('zipcode['.$id.']',$row['zipcode'],'size="10" onkeyup="updateTransferLocation(\'loc_'.$id.'\',\''.$id.'\')"')?></td>
				<td width="15%"><?php echo tep_draw_pull_down_menu('type['.$id.']',array(array('id'=>'0','text'=>'Airport'),array('id'=>'1','text'=>'Location')),$row['type'],' id="transfer_type" onchange="updateTransferLocation(\'loc_'.$id.'\',\''.$id.'\')"')?></td>
				<td  width="20%"><button type="button"  onclick="deleteTransferLocation('loc_<?php echo $id?>','<?php echo $id?>')" );"> <img src="images/no.gif" width="15" height="15" title="del" alt="del" /> </button></td>
			</tr>
			<?php }?>		
			<tr style="display:none" id="insertLocationBefore"><td colspan="4"></td></tr>
			</table>
			</div>
		</td></tr>
		<tr><td></td><td>
		<!--
		<ol>
			请注意:
			<li></li>
			<li></li>
		</ol> -->
		</td></tr>
		<tr>
			<th  valign="top">Route Price :
			<br><div style="text-align:left;color:red">
			1.洛杉矶接送服务请不要设置接送线路价格。<br/>
			2.如果location1 到location2接和送的价格都一样只需添加一条即可，如果不一样则需要添加location1->location2的价格和location2->location1的价格。
			</div>
			</th>
			<td  class="panel">		
			<table width="90%" cellspacing="1" cellpadding="3" border="0" style="border:1px solid gray">
			<tr>
				<th width="20%">Location1</th>
				<th width="20%">Location2</th>
				<!--<th width="10%">Price Base</th>-->
				<th width="10%">Retail  1-3</th>				
				<th width="10%">Retail  4-6</th>
				<th width="10%">Cost 1-3</th>
				<th width="10%">Cost 4-6</th>
				<th width="20%">&nbsp;</th>
			</tr>	
			<tr class="addbox">
				<td ><?php echo tep_draw_pull_down_menu('tmp_pickup_location_id',$product_location_droplist,'0',' id="tmpPickupLocationId" ')?></td>
				<td ><?php echo tep_draw_pull_down_menu('tmp_dropoff_location_id',$product_location_droplist,'0',' id="tmpDropoffLocationId" ')?></td>
				<!--<td ><?php echo tep_draw_input_field('tmp_price_base','0.00',' id="tmp_price_base" ')?></td>-->
				<td ><?php echo tep_draw_input_field('tmp_price1','0.00',' id="tmp_price1" ')?></td>					
				<td ><?php echo tep_draw_input_field('tmp_price2','0.00',' id="tmp_price2" ')?></td>	
				<td ><?php echo tep_draw_input_field('tmp_price1_cost','0.00',' id="tmp_price1_cost" ')?></td>	
				<td ><?php echo tep_draw_input_field('tmp_price2_cost','0.00',' id="tmp_price2_cost" ')?></td>	
				<td ><button type="button" onclick="addRoute()"> [Add] </button>
				<?php  echo tep_draw_hidden_field('deleted_route','',' id ="deletedRouteKeeper"  '); ?>
				<?php  echo tep_draw_hidden_field('updated_route','',' id ="updatedRouteKeeper"  '); ?>
				</td>

			</tr>	
			</table>
			<div class="transferLocationList" style="margin-top:3px;">
			<table width="90%" cellspacing="1" cellpadding="3" border="0">
				<?php foreach($product_route_list as $row) {
					$id = $row['products_transfer_route_id'];
				?>
				<tr id="route_<?php echo $id ?>">			
				<td width="20%"><?php echo tep_draw_pull_down_menu('pickup_location_id['.$id .']',$product_location_droplist,$row['pickup_location_id'],' onchange="updateRoute(\'route_'.$id.'\',\''.$id.'\')"')?></td>
				<td width="20%"><?php echo tep_draw_pull_down_menu('dropoff_location_id['.$id .']',$product_location_droplist,$row['dropoff_location_id'],' onchange="updateRoute(\'route_'.$id.'\',\''.$id.'\')"')?></td>
				<!--<td width="10%"><?php echo tep_draw_input_field('price_base['.$id .']',$row['price_base'],'  onkeyup="updateRoute(\'route_'.$id.'\',\''.$id.'\')" ')?></td>-->
				<td width="10%"><?php echo tep_draw_input_field('price1['.$id .']',$row['price1'],' onkeyup="updateRoute(\'route_'.$id.'\',\''.$id.'\')" ')?></td>	
				<td width="10%"><?php echo tep_draw_input_field('price2['.$id .']',$row['price2'],' onkeyup="updateRoute(\'route_'.$id.'\',\''.$id.'\')" ')?></td>	
				<td width="10%"><?php echo tep_draw_input_field('price1_cost['.$id .']',$row['price1_cost'],' onkeyup="updateRoute(\'route_'.$id.'\',\''.$id.'\')" ')?></td>	
				<td width="10%"><?php echo tep_draw_input_field('price2_cost['.$id .']',$row['price2_cost'],' onkeyup="updateRoute(\'route_'.$id.'\',\''.$id.'\')" ')?></td>	
				<td  width="20%"><button type="button"  onclick="deleteRoute('route_<?php echo $id?>','<?php echo $id?>')" );"> <img src="images/no.gif" width="15" height="15" title="del" alt="del" /> </button></td>
				</tr>
				<?}?>
				<tr style="display:none" id="insertRouteBefore"><td colspan="6"></td></tr>
			</table>
			</div>
			</td></tr>

			<tr><td colspan="2" align="center">
			<?php
				  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
				  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
				  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
		 				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				  }else{
						echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				  }
			?>
			</td></tr>
	</table>
	</form>