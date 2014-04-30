<?php
//transfer-service 
if($_GET['section'] == 'tour_transfer' &&  $_GET['action'] == 'process'){		
	//rebuild post array	
	foreach($_POST as $key=>$value){
		if($key == 'aryFormData') continue ;
		$leftbrackPos = strpos($key , '[') ;
		$rightbrackPos = strpos($key , ']') ;
		if($leftbrackPos!== false && $rightbrackPos!==false&& $leftbrackPos<$rightbrackPos){
			$postKey = substr($key,0,$leftbrackPos);
			$arrKey = substr($key,$leftbrackPos+1,$rightbrackPos-$leftbrackPos-1);
			if(!is_array($_POST[$postKey])){
				$_POST[$postKey] = array();
			}
			if($arrKey==""){
				$_POST[$postKey][] = $value ;
			}else{
				$_POST[$postKey][$arrKey] = $value ;
			}				
		}
	}
	//added location
	if(tep_not_null($_POST['new_short_address'])){		
		foreach($_POST['new_short_address'] as $key=>$new_short_address){
			if(tep_not_null($new_short_address) && tep_not_null($_POST['new_zipcode'][$key])&&is_numeric($_POST['new_type'][$key])){
				$insert = array(
					'products_id'=>$products_id,
					'short_address'=>tep_db_prepare_input($new_short_address),
					'detail_address'=>'',
					'zipcode'=>tep_db_prepare_input($_POST['new_zipcode'][$key]),
					'type'=>intval($_POST['new_type'][$key])
				);
				tep_db_perform(TABLE_PRODUCTS_TRANSFER_LOCATION, $insert);
			}
		}
	}
	//delete location
	if(tep_not_null($_POST['deleted_location'])){
		tep_db_query("DELETE FROM ".TABLE_PRODUCTS_TRANSFER_LOCATION." WHERE products_id=".(int)$products_id." AND products_transfer_location_id IN (".$_POST['deleted_location'].")");
	}
	//updated location
	if(tep_not_null($_POST['updated_location'])){
		$query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_LOCATION." WHERE products_id=".(int)$products_id." AND products_transfer_location_id IN (".$_POST['updated_location'].")");
		while($row = tep_db_fetch_array($query)){
			$id = $row['products_transfer_location_id'];
			if($row['short_address'] != $_POST['short_address'][$id] || $row['zipcode'] != $_POST['zipcode'][$id] || $row['type'] != $_POST['type'][$id] ){
				$needUpdate = array(
					'short_address'=>tep_db_prepare_input($_POST['short_address'][$id]) ,
					'zipcode'=>tep_db_prepare_input($_POST['zipcode'][$id]),
					'type'=>$_POST['type'][$id] ,
				);
				tep_db_perform(TABLE_PRODUCTS_TRANSFER_LOCATION, $needUpdate, 'update', "products_transfer_location_id = '" . $id . "' AND products_id=".(int)$products_id);
			}
		}
	}
	//route add
	if(tep_not_null($_POST['new_pickup_location_id'])){
		foreach($_POST['new_pickup_location_id'] as $key=>$location1){
			if(is_numeric($location1) 
				&& is_numeric($_POST['new_dropoff_location_id'][$key])
				//&&is_numeric($_POST['new_price_base'][$key])
				&&is_numeric($_POST['new_price1'][$key])
				&&is_numeric($_POST['new_price2'][$key])
				&&is_numeric($_POST['new_price1_cost'][$key])
				&&is_numeric($_POST['new_price2_cost'][$key])
			){
				$insert = array(
					'products_id'=>$products_id,
					'pickup_location_id'=>intval($location1),
					'dropoff_location_id'=>intval($_POST['new_dropoff_location_id'][$key]),
					'price_base'=>strval(floatval($_POST['new_price_base'][$key])),
					'price1'=>strval(floatval($_POST['new_price1'][$key])),
					'price2'=>strval(floatval($_POST['new_price2'][$key])),
					'price1_cost'=>strval(floatval($_POST['new_price1_cost'][$key])),
					'price2_cost'=>strval(floatval($_POST['new_price2_cost'][$key]))
				);
				tep_db_perform(TABLE_PRODUCTS_TRANSFER_ROUTE, $insert);
			}
		}
	}
	//delete
	if(tep_not_null($_POST['deleted_route'])){
		tep_db_query("DELETE FROM ".TABLE_PRODUCTS_TRANSFER_ROUTE." WHERE products_id=".(int)$products_id." AND products_transfer_route_id IN (".$_POST['deleted_route'].")");
	}
	//updated
	if(tep_not_null($_POST['updated_route'])){
		$query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_ROUTE." WHERE products_id=".(int)$products_id." AND products_transfer_route_id IN (".$_POST['updated_route'].")");		
		while($row = tep_db_fetch_array($query)){
				//|| $row['price_base'] != $_POST['price_base'][$id]
			$id = $row['products_transfer_route_id'];
			if($row['pickup_location_id'] != $_POST['pickup_location_id'][$id] 
				|| $row['dropoff_location_id'] != $_POST['dropoff_location_id'][$id] 				
				|| $row['price1'] != $_POST['price1'][$id]
				|| $row['price2'] != $_POST['price2'][$id]
				|| $row['price1_cost'] != $_POST['price1_cost'][$id]
				|| $row['price2_cost'] != $_POST['price2_cost'][$id])
			{
				
				$needUpdate =array(					
					'pickup_location_id'=>intval($_POST['pickup_location_id'][$id]),
					'dropoff_location_id'=>intval($_POST['dropoff_location_id'][$id]),
					'price_base'=>strval(floatval($_POST['price_base'][$id])),
					'price1'=>strval(floatval($_POST['price1'][$id])),
					'price2'=>strval(floatval($_POST['price2'][$id])),
					'price1_cost'=>strval(floatval($_POST['price1_cost'][$id])),
					'price2_cost'=>strval(floatval($_POST['price2_cost'][$id]))
				);						
				tep_db_perform(TABLE_PRODUCTS_TRANSFER_ROUTE, $needUpdate, 'update', "products_transfer_route_id = '" . $id . "' AND products_id=".(int)$products_id);
			}
		}
	}
	//echo "<pre>";print_r($_POST);
}
?>