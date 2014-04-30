<?php

if($_GET['section'] == 'products_send_soldout_email'){
	require('products_send_soldout_email.php');
	exit;
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
require('includes/application_top.php');

// by lwkai added 复制产品 start
if($_GET['section'] == 'copy_products') {
	$products_id = isset($_POST['pid']) ? $_POST['pid'] : '';
	$val = isset($_POST['val']) ? $_POST['val'] : '';
	$typd = isset($_POST['type']) ? $_POST['type'] : '';
	if (tep_not_null($products_id) && tep_not_null($val) && tep_not_null($type)) {
		$products_arr = explode(',',$products_id);
		foreach($products_arr as $key=>$v){
			$data = array();
			if ($type == 'early_arrival'){
				$data['hotels_for_early_arrival'] = trim($val);
			} else {
				$data['hotels_for_late_departure'] = trim($val);
			}
			tep_db_fast_update('products','products_id=' . intval($v),$data);
		}
		echo '{"state":0,"error":"' . $_POST['pid'] . '"}';
	} else {
		echo '{"state":1,"error":"数据不全"}';
	}
	exit;
}
// 复制产品 end
require(DIR_WS_CLASSES . 'Price_Change_Alert.class.php');
$PCA = new Price_Change_Alert;
require('includes/functions/categories_description.php');
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CATEGORIES);
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$languages = tep_get_languages();
header("Content-type: text/html; charset=".CHARSET."");

//auot_update_products_special_note();
if($_GET['section'] == 'tour_content' && $_COOKIE['is_show_tour_content']){
	setcookie('is_show_tour_content',0);
	$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_CONTENT.' Section', 'success');
}
//howard added for ajax

//打开图片库取出图片供选择{
if($_GET['action'] == 'open_picture_db'){
	if(!tep_not_null($_GET['image_input_box_id']) || !tep_not_null($_GET['image_type']) || !tep_not_null($_GET['city_ids']) ){
		die('not find image_input_box_id image_type or city_ids');
	}
	
	$picData = array();
	$js_str = '[JS]';
	//print_r($_GET);
	require('includes/classes/picture_db.php');
	$pic = new picture_db;	
	$_array_city_id = explode(',',$city_ids);
	foreach ((array)$_array_city_id as $key => $city_id){
		$_GET['city_id'] = $city_id;
		$array = $pic->lists(true);
		if(is_array($array)){
			$picData = array_merge($picData, $array);
		}
	}
	$ul = '<ul class="ajax_layer_ul">';
	if(is_array($picData) && $n = sizeof($picData)){
		$json = array();
		for($i=0; $i<$n; $i++){
			if((int)$picData[$i]['picture_id']){
				$ul.='<li><label>'.tep_draw_radio_field('picture_id',$picData[$i]['picture_id'],'','','onClick="get_picture_db_to_input_box(&quot;'.(string)$_GET['image_input_box_id'].'&quot;,&quot;'.(string)$_GET['image_type'].'&quot;,this,&quot;'.(string)$_GET['preview_box_id'].'&quot;)" thumbsrc="'.$picData[$i]['picture_url_thumb'].'" detailsrc="'.$picData[$i]['picture_url'].'" ').' ID:'.$picData[$i]['picture_id'].' 景点:'.tep_get_city_name($picData[$i]['city_id']).'<br /><a href="'.$picData[$i]['picture_url'].'" target="_blank"><img src="'.$picData[$i]['picture_url_thumb'].'" /></a>'.'</label></li>';
			}
		}
	}
	$ul.='</ul>';
	
	$js_str.= "$('#picture_list').html('".$ul."');";
	$js_str.= "showPopup('imageslayr_0','imageslayr_popupCon_0',100);";
	$js_str.= '[/JS]';
	$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
	echo $js_str;
	exit;
}
$city_ids = implode(',',(array)tep_get_product_destination_city_ids($_GET['pID']));
//打开图片库取出图片供选择}

//Howard added 关联产品类 2012-10-13
require(DIR_FS_CATALOG.'includes/classes/products_expand/manualRelatedProducts.php');
$manualRelatedProducts = new manualRelatedProducts((int)$_GET['pID']);


if($_GET['action']=='auto_get_keyword' && $_POST['ajax']=='true'){
	$sql = tep_db_query('SELECT products_name,products_description,products_small_description FROM `products_description` WHERE products_id="'.(int)$_POST['products_id'].'" and language_id="1" LIMIT 1');
	$row = tep_db_fetch_array($sql);
	$pat_content = strip_tags($row['products_name'].$row['products_description'].$row['products_small_description']);
	$tmp_key = tep_add_meta_keywords_from_thesaurus($pat_content, 2);
	$tmp_key_string = implode(',',(array)$tmp_key);
	echo $tmp_key_string;
	exit;
}
if($_GET['action']=='ajax_get_hotel_info'){
	$hotel_id = $_POST['hotel_id'];
	if((int)$hotel_id > 0){
	$product_hotel_info_query = tep_db_query("select hotel_address, hotel_phone from hotel where hotel_id = '".$hotel_id."'");
	$product_hotel_info = tep_db_fetch_array($product_hotel_info_query);
	?>
    <table style="margin-left:24px;">
    	<tr><td class="main">Address: </td><td class="main"><?php echo $product_hotel_info['hotel_address']; ?></td></tr>
        <tr><td class="main">Phone: </td><td class="main"><?php echo $product_hotel_info['hotel_phone']; ?></td></tr>
        <tr><td class="main" colspan="2"><a href="<?php echo tep_href_link(FILENAME_HOTEL_ADMIN, 'hotel_id='.$hotel_id.'&action=edit&search_one_hotel=true'); ?>" target="_blank">Edit Hotel Information</a></td></tr>
    </table>
    <?php
	}else{
	?>
    <table style="margin-left:24px;">
    	<tr><td class="main"><b><u>OR</u></b> <a href="<?php echo tep_href_link(FILENAME_HOTEL_ADMIN, 'action=new'); ?>" target="_blank">Add New Hotel</a></td></tr>
    </table>
    <?php
	}
	exit;
}

if(isset($_POST['aryFormData']))
  {
 		$aryFormData = $_POST['aryFormData'];

		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {
		  	   if (eregi('--leftbrack', $key)) {
				$key = str_replace('--leftbrack','[',$key);
				$key = str_replace('rightbrack--',']',$key);
			  }

			  $value2 = str_replace('@@amp;','&',$value2);
			  $value2 = str_replace('@@plush;','+',$value2);


			  //$value2 = iconv('utf-8','gb2312'.'//IGNORE',$value2);
			  $value2 = mb_convert_encoding($value2, 'gb2312', 'UTF-8');
			  $value2 = str_replace('ooooo','&bull;',$value2);

			  $_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes2($value2);

			  //echo "$key=>$value2<br>";
		  }
		}

}
//exit;

$products_id = tep_db_prepare_input($_GET['pID']);
$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($products_id);

if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
$display_tour_agency_opr_currency_note = '<span class="errorText">'.$tour_agency_opr_currency.':</span>';
}else{
$display_tour_agency_opr_currency_note = '';
}

$error='false';


if($_GET['section'] == 'tour_categorization' &&  $_GET['action'] == 'process'){




	if($_POST['regions_id'] == '0'){
		$messageStack->add('Please select valid option for Tour Regions.', 'error');
		$error='true';
	}

	if($_POST['agency_id'] == ''){
		$messageStack->add('Please select valid option for Tour Provider.', 'error');
		$error='true';
	}

	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

		$language_id = $languages[$i]['id'];

		if($_POST['products_name['.$language_id.']'] == ''){
		$messageStack->add('Tour Name value should not be blank.', 'error');
		$error='true';
		}

	}

	if($_POST['products_model'] == '')
	{
		$messageStack->add('Tour Code value should not be blank.', 'error');
		//$error='true';
	}

	if(isset($_POST['products_model']) && $_POST['products_model']!='')
	{
		$check_same_model =  tep_db_query("select products_model from ".TABLE_PRODUCTS."  where products_id not in (".(int)$products_id.") and products_model = '".$_POST['products_model']."'");
		if(tep_db_num_rows($check_same_model) > 0) {
				$messageStack->add('Please enter another products-model as this was already in use.', 'error');
				$error='true';
		}
	}
		$urlname = tep_db_prepare_input($_POST['products_urlname']);
         		if(!tep_not_null($urlname)){
					$urlname = seo_generate_urlname(tep_db_prepare_input($_POST['products_name[1]']));
				}else{
				 	$urlname = seo_generate_urlname($urlname);
				}


	if($urlname!='')
	{
		$check_same_model =  tep_db_query("select products_urlname from ".TABLE_PRODUCTS."  where products_id not in (".(int)$products_id.") and products_urlname = '".$urlname."' ");
		if(tep_db_num_rows($check_same_model) > 0) {
				$messageStack->add('Please enter another products-url as this was already in use.', 'error');
				$error='true';
		}
	}

	/*
	if($_POST['products_price'] == ''){
		$messageStack->add('Tour Price value should not be blank.', 'error');
		$error='true';
	}
	*/

	if($error == 'false'){
		 if($_POST['regions_id']!=$_POST['prev_regions_id'] && $_POST['prev_regions_id']!=''){
			$messageStack->add('Note: You have changed tour region value. Please make sure all Attractions under Operation tab are correct.', 'error');

		}

		 if($_POST['agency_id']!=$_POST['prev_agency_id'] && $_POST['prev_agency_id']!=''){
			$messageStack->add('Note: You have changed tour provider value. Please make sure all Tour Attributes under Attribute tab and all Departure Time and Locations under operation tab are correct.', 'error');
			 // BOF: WebMakers.com Added: Update Product Attributes and Sort Order
			  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
			  $rows = 0;
			  $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$_POST['agency_id']."' order by products_options_sort_order, products_options_name");
			  while ($options = tep_db_fetch_array($options_query)) {
				if($_POST['agency_id']!=''){
					  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$_POST['agency_id']."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");

				} else {
						$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
				}
				while ($values = tep_db_fetch_array($values_query)) {
				  $rows ++;
				  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,  products_options_sort_order, multiplier from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
				  if (tep_db_num_rows($attributes_query) > 0) {
					$attributes = tep_db_fetch_array($attributes_query);
					if ($_POST['option['.$rows.']']) {
					  if ( ($_POST['prefix['.$rows.']'] <> $attributes['price_prefix']) || ($_POST['price['.$rows.']'] <> $attributes['options_values_price']) || ($_POST['single_price['.$rows.']'] <> $attributes['single_values_price']) || ($_POST['double_price['.$rows.']'] <> $attributes['double_values_price']) || ($_POST['triple_price['.$rows.']'] <> $attributes['triple_values_price']) || ($_POST['quadruple_price['.$rows.']'] <> $attributes['quadruple_values_price']) || ($_POST['kids_price['.$rows.']'] <> $attributes['kids_values_price']) || ($_POST['products_options_sort_order['.$rows.']'] <> $attributes['products_options_sort_order']) ) {

						//tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $_POST['price['.$rows.']'] . "', single_values_price='". $_POST['single_price['.$rows.']'] ."', double_values_price='". $_POST['double_price['.$rows.']'] ."', triple_values_price='". $_POST['triple_price['.$rows.']'] ."', quadruple_values_price='". $_POST['quadruple_price['.$rows.']'] ."', kids_values_price='". $_POST['kids_price['.$rows.']'] ."', price_prefix = '" . $_POST['prefix['.$rows.']'] . "', products_options_sort_order = '" . $_POST['products_options_sort_order['.$rows.']'] . "'  where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
					  			$sql_data_array_update = array(
				 					'options_values_price' => $_POST['price['.$rows.']'],
									'single_values_price' => $_POST['single_price['.$rows.']'],
									'double_values_price' => $_POST['double_price['.$rows.']'],
									'triple_values_price' => $_POST['triple_price['.$rows.']'],
									'quadruple_values_price' => $_POST['quadruple_price['.$rows.']'],
									'kids_values_price' => $_POST['kids_price['.$rows.']'],
									'price_prefix' => $_POST['prefix['.$rows.']'],
									'products_options_sort_order' => $_POST['products_options_sort_order['.$rows.']']
									);

							tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_update, 'update', "products_attributes_id = '" . $attributes['products_attributes_id'] . "'");

					  }
					} else {
					  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
					}
				  } elseif ($_POST['option['.$rows.']']) {
					//tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id . "', '" . $options['products_options_id'] . "', '" . $values['products_options_values_id'] . "', '" . $_POST['price['.$rows.']'] . "', '" . $_POST['prefix['.$rows.']'] . "', '". $_POST['single_price['.$rows.']'] ."', '" . $_POST['double_price['.$rows.']'] . "', '" . $_POST['triple_price['.$rows.']'] . "', '" . $_POST['quadruple_price['.$rows.']'] . "','" . $_POST['kids_price['.$rows.']'] . "', '" . $_POST['products_options_sort_order['.$rows.']'] . "')");
				 					$sql_data_array_insert = array(
									'products_id' => $products_id,
									'options_id' => $options['products_options_id'],
									'options_values_id' => $values['products_options_values_id'],
				 					'options_values_price' => $_POST['price['.$rows.']'],
									'price_prefix' => $_POST['prefix['.$rows.']'],
									'single_values_price' => $_POST['single_price['.$rows.']'],
									'double_values_price' => $_POST['double_price['.$rows.']'],
									'triple_values_price' => $_POST['triple_price['.$rows.']'],
									'quadruple_values_price' => $_POST['quadruple_price['.$rows.']'],
									'kids_values_price' => $_POST['kids_price['.$rows.']'],
									'products_options_sort_order' => $_POST['products_options_sort_order['.$rows.']']
									);
									tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_insert);

				  }
				}
			  }
			// EOF: WebMakers.com Added: Update Product Attributes and Sort Order

		}

	}



}

if($_GET['section'] == 'tour_transfer' &&  $_GET['action'] == 'process'){
	include(DIR_FS_ADMIN.'categories_ajax_sections_transfer_process.php');
}

if($_GET['section'] == 'tour_content' &&  $_GET['action'] == 'process'){

	/*for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

		$language_id = $languages[$i]['id'];
		if($_POST['products_description['.$language_id.']'] == ''){
		$messageStack->add('Tour Itinerary should not be blank.', 'error');
		$error='true';
		}

	}*/

}

if($_GET['section'] == 'tour_meta_tag' &&  $_GET['action'] == 'process'){

	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

		$language_id = $languages[$i]['id'];

		if($_POST['products_head_title_tag['.$language_id.']'] == ''){
		$messageStack->add('Please Enter the value for Products Mata Tag Page Title.', 'error');
		$error='true';
		}

		if($_POST['products_head_desc_tag['.$language_id.']'] == ''){
		$messageStack->add('Please Enter the value for Products Mata Tag Header Description.', 'error');
		$error='true';
		}

		if($_POST['products_head_keywords_tag['.$language_id.']'] == ''){
		$messageStack->add('Please Enter the value for Products Mata Tag Keywords.', 'error');
		$error='true';
		}

	}

}



if($_GET['section'] == 'tour_operation' &&  $_GET['action'] == 'process'){
	if(tep_check_product_is_hotel($products_id)==0){ //hotel-extension
	if($_POST['products_durations'] == ''){
		$messageStack->add('Please Enter valid value for Products Duration.', 'error');
		$error='true';
	}
	} //hotel-extension 

	if($_POST['departure_city_id'] == ''){
		$messageStack->add('Please Enter the value for Products Start Departure City.', 'error');
		$error='true';
	}

}

if($_GET['section'] == 'room_and_price' &&  $_GET['action'] == 'process'){
	if($_POST['products_price'] == ''){
			$messageStack->add('Tour Price value should not be blank.', 'error');
			$error='true';
	}
}
if(isset($_GET['action']) &&  $_GET['action'] == 'process' && $error=='false'){
		switch($_GET['section']) {
			case 'tour_categorization':
				//start of tour categorization section
				if($_POST['is_hotel'] == '1' && $_POST['is_transfer']=='1'){
					$messageStack->add('Product type error ! 产品不能同时是酒店和接送服务', 'error');
					break;
				}
				$urlname = tep_db_prepare_input($_POST['products_urlname']);
         		if(!tep_not_null($urlname)){
					$urlname = seo_generate_urlname(tep_db_prepare_input($_POST['products_name[1]']));
				}else{
				 	$urlname = seo_generate_urlname($urlname);
				}

				$sql_data_array_product = array(
 				  	'products_status' => tep_db_prepare_input($_POST['products_status']),
 				  	'products_stock_status' => tep_db_prepare_input($_POST['products_stock_status']),
				  	'products_vacation_package' => tep_db_prepare_input($_POST['products_vacation_package']),
				   	'products_type' => tep_db_prepare_input($_POST['products_type']),
				    'regions_id' => tep_db_prepare_input($_POST['regions_id']),
					'agency_id' => tep_db_prepare_input($_POST['agency_id']),
					'products_urlname' => $urlname,
					'products_model' => tep_db_prepare_input($_POST['products_model']),
					'products_model_sub' => tep_db_prepare_input($_POST['products_model_sub']),
					'products_model_sub_notes' => tep_db_prepare_input($_POST['products_model_sub_notes']),
					'provider_tour_code' => tep_db_prepare_input($_POST['provider_tour_code']),
					'provider_tour_code_sub' => tep_db_prepare_input($_POST['provider_tour_code_sub']),
                	//'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
					//'products_price' => tep_db_prepare_input($_POST['products_price']),
					'products_class_id' => 	tep_db_prepare_input($_POST['products_class_id']),
					'products_class_content' => tep_db_prepare_input($_POST['products_class_content']),
					'upgrade_to_product_id' => tep_db_prepare_input($_POST['upgrade_to_product_id']),
					//'sort_order' => (int)$_POST['sort_order'],
					'products_info_tpl' => tep_db_prepare_input($_POST['products_info_tpl']),
				  	'products_last_modified' => 'now()',
					'book_limit_days_number' => (int)$_POST['book_limit_days_number'],
					'with_air_transfer' => (int)$_POST['with_air_transfer'],
					'is_visa_passport' => (int)$_POST['is_visa_passport'],
					'is_hotel' => tep_db_prepare_input($_POST['is_hotel']),//hotel-extension
					'is_transfer' => tep_db_prepare_input($_POST['is_transfer']),//transfer-service
					'transfer_type' => tep_db_prepare_input($_POST['transfer_type']),//transfer-service
					'is_cruises' => (int)$_POST['is_cruises'], //cruises
				);
            	tep_db_perform(TABLE_PRODUCTS,  $sql_data_array_product, 'update', "products_id = '" . (int)$products_id . "'");
            	MCache::update_product($products_id);//MCache update
				//拉斯维加斯Show start
				if(tep_db_prepare_input($_POST['products_info_tpl'])=="product_info_vegas_show"){
					$sql_date_array_show = array('products_hotel_id'=>(int)$_POST['products_hotel_id'], 'min_watch_age'=>(int)$_POST['min_watch_age']);
					$check_show = tep_db_query('SELECT products_id FROM `products_show` WHERE products_id="'.(int)$products_id.'" Limit 1');
					$check_show_row = tep_db_fetch_array($check_show);
					if(!(int)$check_show_row['products_id']){
						$sql_date_array_show['products_id'] = (int)$products_id;
						tep_db_perform('`products_show`', $sql_date_array_show );
					}else{
						tep_db_perform('`products_show`', $sql_date_array_show , 'update', "products_id = '" . (int)$products_id . "'");
					}
				}else{
					tep_db_query('DELETE FROM `products_show` WHERE `products_id` = "'.(int)$products_id.'" ');
				}
				//拉斯维加斯Show end
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				 	$language_id = $languages[$i]['id'];
				 	$sql_data_array = array(
						'products_name' => tep_db_prepare_input($_POST['products_name['.$language_id.']']),
						'products_name_provider' => tep_db_prepare_input($_POST['products_name_provider['.$language_id.']'])
					);
					//检查产品名称不可以重复，特别是酒店
					$check_name_sql = tep_db_query('SELECT products_id FROM '.TABLE_PRODUCTS_DESCRIPTION.' WHERE products_name="'.$sql_data_array['products_name'].'" and language_id ="'.(int)$language_id.'" and products_id !="'.(int)$products_id.'" Limit 1 ' );
					$check_name_row = tep_db_fetch_array($check_name_sql);
					if((int)$check_name_row['products_id']){
						$sql_data_array['products_name'] .= '[重复名称]';
					}
					tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					MCache::update_product($products_id);//MCache update
				}
				 //更新产品手工关联数据 Howard added
				$manualRelatedProducts->inputManualRelated((int)$products_id, $_POST['manual_related_products_title'], $_POST['manual_related_products_content'], 1);
				 //填写酒店资料 start
				if(preg_match('/^182(\_){0,1}/',$_GET['cPath'])){
					$sql_data_array = array(
						'products_id' => (int)$products_id,
						'hotel_star' => (int)$_POST['hotel_star'],
						'hotel_address' => tep_db_prepare_input($_POST['hotel_address'])
					);
					$check_hotel_sql = tep_db_query('SELECT products_hotels_id FROM `products_hotels` WHERE products_id="'.(int)$products_id.'" ');
					$check_hotel_row = tep_db_fetch_array($check_hotel_sql);
					if((int)$check_hotel_row['products_hotels_id']){
						tep_db_perform('products_hotels', $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
					}else{
						tep_db_perform('products_hotels', $sql_data_array);
					}
				}
				 //填写酒店资料 end
				 //酒店信息表关联设置 {
				//酒店延住提取的酒店资料保存在hotel表中，设置 product到hotel的1对1关系
				//@athor vincent
				if(tep_check_product_is_hotel(intval($products_id))){
					$query = tep_db_query('SELECT hotel_id FROM hotel WHERE products_id = '.intval($products_id).' LIMIT 1');
					$hotel = tep_db_fetch_array($query);
					if($hotel == false) 
						$old_hotel_id = '';
					else 
						$old_hotel_id=trim($hotel['hotel_id']);
					$new_hotel_id= trim($_POST['hotel_id']);
					if($new_hotel_id != $old_hotel_id){
						 tep_db_query("UPDATE  hotel SET products_id=''  WHERE products_id = ".intval($products_id));
						tep_db_query("UPDATE  hotel SET products_id='".intval($products_id)."'  WHERE hotel_id = '".$new_hotel_id.'\'');
					}
				}
				 //}
				 //{
				 /**
				  * cruises 相关，如果是邮轮团则写或更新products_to_cruises表资料，否则删除；不能提前删除，必须检查，因为此表还有其它信息内容。
				  * @athor Howard
				  */				 
				 if((int)$_POST['is_cruises'] && (int)$_POST['cruises_id']){
				 	$checkSql = tep_db_query('SELECT cruises_id FROM `products_to_cruises` WHERE products_id="'.(int)$products_id.'" AND cruises_id="'.(int)$_POST['cruises_id'].'" ');
				 	if(tep_db_num_rows($checkSql)<1){
				 		tep_db_query('DELETE FROM `products_to_cruises` WHERE `products_id` = "'.(int)$products_id.'" ');
				 		tep_db_query('INSERT INTO `products_to_cruises` (`products_id`,`cruises_id`) VALUES ('.(int)$products_id.', '.(int)$_POST['cruises_id'].');');
				 	}
				 }else{
				 	tep_db_query('DELETE FROM `products_to_cruises` WHERE `products_id` = "'.(int)$products_id.'" ');
				 }
				 //}

				 $messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_CATEGORIZATION.' Section', 'success');
				//end of tour categorization section
			break;
			case 'room_and_price': //房间标准价格更新 start {
				//start of tour room and price
				if($_POST['display_room_option'] == 0) {
					$_POST['products_single'] = $_POST['products_single1'];
					$_POST['products_single_pu'] = $_POST['products_single1_pu'];
					$_POST['products_kids'] = $_POST['products_kids1'];
					$_POST['products_double'] = '';
					$_POST['products_triple'] = '';
					$_POST['products_quadr'] = '';
					if($access_full_edit == 'true') {
						$_POST['products_single_cost'] = $_POST['products_single1_cost'];
						$_POST['products_single_pu_cost'] = $_POST['products_single1_pu_cost'];
						$_POST['products_kids_cost'] = $_POST['products_kids1_cost'];
						$_POST['products_double_cost'] = '';
						$_POST['products_triple_cost'] = '';
						$_POST['products_quadr_cost'] = '';
					}
					$_POST['maximum_no_of_guest'] = '';
				}
				$sql_data_array_product = array(
 				  	'display_hotel_upgrade_notes' => tep_db_prepare_input($_POST['display_hotel_upgrade_notes']),
				  	'display_room_option' => tep_db_prepare_input($_POST['display_room_option']),
					'maximum_no_of_guest' => tep_db_prepare_input($_POST['maximum_no_of_guest']),
					'products_margin' => tep_db_prepare_input($_POST['products_margin']),
					'products_single' => tep_db_prepare_input($_POST['products_single']),
					'products_single_pu' => tep_db_prepare_input($_POST['products_single_pu']),
					'products_double' => tep_db_prepare_input($_POST['products_double']),
					'products_triple' => tep_db_prepare_input($_POST['products_triple']),
					'products_quadr' => tep_db_prepare_input($_POST['products_quadr']),
					'products_kids' => tep_db_prepare_input($_POST['products_kids']),
					'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
				  	'products_price' => tep_db_prepare_input($_POST['products_price']),
					'max_allow_child_age' => tep_db_prepare_input($_POST['max_allow_child_age']),
					'transaction_fee' => tep_db_prepare_input($_POST['transaction_fee']),
				  	'products_last_modified' => 'now()',
					'min_num_guest' => tep_db_prepare_input($_POST['min_num_guest']),
					'products_surcharge' => tep_db_prepare_input($_POST['products_surcharge']),
					'use_buy_two_get_one_price' => tep_db_prepare_input($_POST['use_buy_two_get_one_price']),
					'only_our_free'=>tep_db_prepare_input($_POST['only_our_free'])
				);
				if($access_full_edit == 'true') {
					$sql_data_array_product_cost = array(
						'products_single_cost' => tep_db_prepare_input($_POST['products_single_cost']),
						'products_single_pu_cost' => tep_db_prepare_input($_POST['products_single_pu_cost']),
						'products_double_cost' => tep_db_prepare_input($_POST['products_double_cost']),
						'products_triple_cost' => tep_db_prepare_input($_POST['products_triple_cost']),
						'products_quadr_cost' => tep_db_prepare_input($_POST['products_quadr_cost']),
						'products_kids_cost' => tep_db_prepare_input($_POST['products_kids_cost'])
					);
            		$sql_data_array_product = array_merge($sql_data_array_product, $sql_data_array_product_cost);
				}
            	tep_db_perform(TABLE_PRODUCTS,  $sql_data_array_product, 'update', "products_id = '" . (int)$products_id . "'");
				if($_POST['notice_customer_service']=="1"){
					//更新产品价格最后更新时间（你记得这个动作很重要就行）
					$PCA->update_product_price_last_modified($products_id,'Room and Price');
				}
				//删除客户购物车内容
				del_customers_basket_for_products($products_id);
				
				MCache::update_product($products_id);//MCache update
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

				 	$language_id = $languages[$i]['id'];

				 	$sql_data_array = array('products_pricing_special_notes' => tep_db_prepare_input($_POST['products_pricing_special_notes['.$language_id.']']));
					//check html code
					if(check_html_code($sql_data_array['products_pricing_special_notes'])==false){
						$messageStack->add('The html code have error for products_pricing_special_notes!', 'error');
					}

					tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					MCache::update_product($products_id);//MCache update
				  }

				$messageStack->add('Updated '.TEXT_HEADING_TITLE_ROOM_AND_PRICE.' Section', 'success');
				//end of tour room and price
				//房间标准价格更新 end }
			break;
			case 'tour_operation':
				//非标准价格更新
				//start of tour operation {
				//**********first fetch from the product table where it is first regular of irregular ***********//
				$product_regular_query = tep_db_query("select * from ".TABLE_PRODUCTS." where products_id = '" . (int)$products_id . "' ");
				$product_regular_result = tep_db_fetch_array($product_regular_query);
				//**************************************** START CODING FOR UPDATING "products_is_regular_tour"   *************/
		  		//********** Code for inserrt update delete  product_start_date/products_available tables*****************//
				$newproductypecode = 1;
				if($_POST['products_type']==3 || $newproductypecode == 1) {
					tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");
					tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id = '" . $product_regular_result['products_id'] . "'");
					tep_db_query("delete from ".TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id = '" . (int)$products_id . "'");
					for($no_main_sec=1;$no_main_sec<=$_POST['numberofsection'];$no_main_sec++) {
						$sql_data_irregular_pricing = array(
							'products_id' => $products_id,
							'operate_start_date' => tep_db_prepare_input($_POST['operate_start_date_month'.$no_main_sec]."-".$_POST['operate_start_date_day'.$no_main_sec]."-".$_POST['operate_start_date_year'.$no_main_sec]),
							'operate_end_date' => tep_db_prepare_input($_POST['operate_end_date_month'.$no_main_sec]."-".$_POST['operate_end_date_day'.$no_main_sec]."-".$_POST['operate_end_date_year'.$no_main_sec]),
							'products_single' => tep_db_prepare_input($_POST['products_single'.$no_main_sec]),
							'products_single_pu' => tep_db_prepare_input($_POST['products_single_pu'.$no_main_sec]),
							'products_double' => tep_db_prepare_input($_POST['products_double'.$no_main_sec]),
							'products_triple' => tep_db_prepare_input($_POST['products_triple'.$no_main_sec]),
							'products_quadr' => tep_db_prepare_input($_POST['products_quadr'.$no_main_sec]),
							'products_kids' => tep_db_prepare_input($_POST['products_kids'.$no_main_sec]),
							'products_single_cost' => tep_db_prepare_input($_POST['products_single_cost'.$no_main_sec]),
							'products_single_pu_cost' => tep_db_prepare_input($_POST['products_single_pu_cost'.$no_main_sec]),
							'products_double_cost' => tep_db_prepare_input($_POST['products_double_cost'.$no_main_sec]),
							'products_triple_cost' => tep_db_prepare_input($_POST['products_triple_cost'.$no_main_sec]),
							'products_quadr_cost' => tep_db_prepare_input($_POST['products_quadr_cost'.$no_main_sec]),
							'products_kids_cost' => tep_db_prepare_input($_POST['products_kids_cost'.$no_main_sec])
						);
						if(tep_db_prepare_input($_POST['products_single'.$no_main_sec]) > 0){
							// || tep_db_prepare_input($_POST['products_kids'.$no_main_sec]) > 0
							tep_db_perform(TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE, $sql_data_irregular_pricing);
						}
						if($_POST['products_is_regular_tour'.$no_main_sec]==0) {
							$sql_data_irregular_description = array(
						   	   'products_id' => $products_id,
								'products_durations_description' => tep_db_prepare_input($_POST['products_durations_description'.$no_main_sec]),
								'operate_start_date' => tep_db_prepare_input($_POST['operate_start_date_month'.$no_main_sec]."-".$_POST['operate_start_date_day'.$no_main_sec]."-".$_POST['operate_start_date_year'.$no_main_sec]),
								'operate_end_date' => tep_db_prepare_input($_POST['operate_end_date_month'.$no_main_sec]."-".$_POST['operate_end_date_day'.$no_main_sec]."-".$_POST['operate_end_date_year'.$no_main_sec])
							);
							tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_irregular_description);
							for($no_dates=1;$no_dates<=$_POST['numberofdates'.$no_main_sec];$no_dates++) {
								if($_POST['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]!='') {
									if($product_regular_result['display_room_option'] == '0'){
										$_POST['avaliable_single_pu_price_'.$no_main_sec.'_'.$no_dates] = 0;
										$_POST['avaliable_double_price_'.$no_main_sec.'_'.$no_dates] = 0;
										$_POST['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates] = 0;
										$_POST['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates] = 0;
									}
									if($access_full_edit == 'true') {
										$sql_data_irregular_date = array(
											'products_id' => $products_id,
											'available_date' => tep_db_prepare_input($_POST['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]),
											'extra_charge' => tep_db_prepare_input($_POST['avaliable_day_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_single' => tep_db_prepare_input($_POST['avaliable_single_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_single_pu' => tep_db_prepare_input($_POST['avaliable_single_pu_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_double' => tep_db_prepare_input($_POST['avaliable_double_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_triple' => tep_db_prepare_input($_POST['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_quadruple' => tep_db_prepare_input($_POST['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_kids' => tep_db_prepare_input($_POST['avaliable_kids_price_'.$no_main_sec.'_'.$no_dates]),
											'extra_charge_cost' => tep_db_prepare_input($_POST['avaliable_day_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'spe_single_cost' => tep_db_prepare_input($_POST['avaliable_single_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'spe_single_pu_cost' => tep_db_prepare_input($_POST['avaliable_single_pu_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'spe_double_cost' => tep_db_prepare_input($_POST['avaliable_double_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'spe_triple_cost' => tep_db_prepare_input($_POST['avaliable_triple_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'spe_quadruple_cost' => tep_db_prepare_input($_POST['avaliable_quadruple_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'spe_kids_cost' => tep_db_prepare_input($_POST['avaliable_kids_price_cost_'.$no_main_sec.'_'.$no_dates]),
											'prefix' => tep_db_prepare_input($_POST['avaliable_day_prefix_'.$no_main_sec.'_'.$no_dates]),
											'sort_order' => tep_db_prepare_input($_POST['avaliable_day_sort_order_'.$no_main_sec.'_'.$no_dates]),
											'operate_start_date' => tep_db_prepare_input($_POST['operate_start_date_month'.$no_main_sec]."-".$_POST['operate_start_date_day'.$no_main_sec]."-".$_POST['operate_start_date_year'.$no_main_sec]),
											'operate_end_date' => tep_db_prepare_input($_POST['operate_end_date_month'.$no_main_sec]."-".$_POST['operate_end_date_day'.$no_main_sec]."-".$_POST['operate_end_date_year'.$no_main_sec])
										);
									}else{
										$sql_data_irregular_date = array(
											'products_id' => $products_id,
											'available_date' => tep_db_prepare_input($_POST['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]),
											'extra_charge' => tep_db_prepare_input($_POST['avaliable_day_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_single' => tep_db_prepare_input($_POST['avaliable_single_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_single_pu' => tep_db_prepare_input($_POST['avaliable_single_pu_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_double' => tep_db_prepare_input($_POST['avaliable_double_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_triple' => tep_db_prepare_input($_POST['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_quadruple' => tep_db_prepare_input($_POST['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates]),
											'spe_kids' => tep_db_prepare_input($_POST['avaliable_kids_price_'.$no_main_sec.'_'.$no_dates]),
											'prefix' => tep_db_prepare_input($_POST['avaliable_day_prefix_'.$no_main_sec.'_'.$no_dates]),
											'sort_order' => tep_db_prepare_input($_POST['avaliable_day_sort_order_'.$no_main_sec.'_'.$no_dates]),
											'operate_start_date' => tep_db_prepare_input($_POST['operate_start_date_month'.$no_main_sec]."-".$_POST['operate_start_date_day'.$no_main_sec]."-".$_POST['operate_start_date_year'.$no_main_sec]),
											'operate_end_date' => tep_db_prepare_input($_POST['operate_end_date_month'.$no_main_sec]."-".$_POST['operate_end_date_day'.$no_main_sec]."-".$_POST['operate_end_date_year'.$no_main_sec])
										);
									}
									tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_date);
								}
							}
						} elseif ($_POST['products_is_regular_tour'.$no_main_sec]==1) {
							for($daysofweek=1;$daysofweek<8;$daysofweek++) {
								if(isset($_POST['weekday_option'.$no_main_sec."_".$daysofweek])) {
									if($product_regular_result['display_room_option'] == '0') {
										$_POST['weekday_single_pu_price'.$no_main_sec.$daysofweek] = 0;
										$_POST['weekday_double_price'.$no_main_sec.$daysofweek] = 0;
										$_POST['weekday_triple_price'.$no_main_sec.$daysofweek] = 0;
										$_POST['weekday_quadruple_price'.$no_main_sec.$daysofweek] = 0;
									}
									if($access_full_edit == 'true') {
										$sql_data_irregular_weeday = array(
											'products_id' => $products_id,
											//products_durations_description' => tep_db_prepare_input($_POST['products_durations_description'.$no_main_sec]),
											'products_start_day' => $daysofweek,
											'extra_charge' => tep_db_prepare_input($_POST['weekday_price'.$no_main_sec.$daysofweek]),
											'extra_charge_cost' => tep_db_prepare_input($_POST['weekday_price_cost'.$no_main_sec.$daysofweek]),
											'spe_single' => tep_db_prepare_input($_POST['weekday_single_price'.$no_main_sec.$daysofweek]),
											'spe_single_pu' => tep_db_prepare_input($_POST['weekday_single_pu_price'.$no_main_sec.$daysofweek]),
											'spe_double' => tep_db_prepare_input($_POST['weekday_double_price'.$no_main_sec.$daysofweek]),
											'spe_triple' => tep_db_prepare_input($_POST['weekday_triple_price'.$no_main_sec.$daysofweek]),
											'spe_quadruple' => tep_db_prepare_input($_POST['weekday_quadruple_price'.$no_main_sec.$daysofweek]),
											'spe_kids' => tep_db_prepare_input($_POST['weekday_kids_price'.$no_main_sec.$daysofweek]),
											'spe_single_cost' => tep_db_prepare_input($_POST['weekday_single_price_cost'.$no_main_sec.$daysofweek]),
											'spe_single_pu_cost' => tep_db_prepare_input($_POST['weekday_single_pu_price_cost'.$no_main_sec.$daysofweek]),
											'spe_double_cost' => tep_db_prepare_input($_POST['weekday_double_price_cost'.$no_main_sec.$daysofweek]),
											'spe_triple_cost' => tep_db_prepare_input($_POST['weekday_triple_price_cost'.$no_main_sec.$daysofweek]),
											'spe_quadruple_cost' => tep_db_prepare_input($_POST['weekday_quadruple_price_cost'.$no_main_sec.$daysofweek]),
											'spe_kids_cost' => tep_db_prepare_input($_POST['weekday_kids_price_cost'.$no_main_sec.$daysofweek]),
											'prefix' => tep_db_prepare_input($_POST['weekday_prefix'.$no_main_sec.$daysofweek]),
											'sort_order' => tep_db_prepare_input($_POST['weekday_sort_order'.$no_main_sec.$daysofweek]),
											'operate_start_date' => tep_db_prepare_input($_POST['operate_start_date_month'.$no_main_sec]."-".$_POST['operate_start_date_day'.$no_main_sec]."-".$_POST['operate_start_date_year'.$no_main_sec]),
											'operate_end_date' => tep_db_prepare_input($_POST['operate_end_date_month'.$no_main_sec]."-".$_POST['operate_end_date_day'.$no_main_sec]."-".$_POST['operate_end_date_year'.$no_main_sec])
										);
									}else{
										$sql_data_irregular_weeday = array(
											'products_id' => $products_id,
											//products_durations_description' => tep_db_prepare_input($_POST['products_durations_description'.$no_main_sec]),
											'products_start_day' => $daysofweek,
											'extra_charge' => tep_db_prepare_input($_POST['weekday_price'.$no_main_sec.$daysofweek]),
											'spe_single' => tep_db_prepare_input($_POST['weekday_single_price'.$no_main_sec.$daysofweek]),
											'spe_single_pu' => tep_db_prepare_input($_POST['weekday_single_pu_price'.$no_main_sec.$daysofweek]),
											'spe_double' => tep_db_prepare_input($_POST['weekday_double_price'.$no_main_sec.$daysofweek]),
											'spe_triple' => tep_db_prepare_input($_POST['weekday_triple_price'.$no_main_sec.$daysofweek]),
											'spe_quadruple' => tep_db_prepare_input($_POST['weekday_quadruple_price'.$no_main_sec.$daysofweek]),
											'spe_kids' => tep_db_prepare_input($_POST['weekday_kids_price'.$no_main_sec.$daysofweek]),
											'prefix' => tep_db_prepare_input($_POST['weekday_prefix'.$no_main_sec.$daysofweek]),
											'sort_order' => tep_db_prepare_input($_POST['weekday_sort_order'.$no_main_sec.$daysofweek]),
											'operate_start_date' => tep_db_prepare_input($_POST['operate_start_date_month'.$no_main_sec]."-".$_POST['operate_start_date_day'.$no_main_sec]."-".$_POST['operate_start_date_year'.$no_main_sec]),
											'operate_end_date' => tep_db_prepare_input($_POST['operate_end_date_month'.$no_main_sec]."-".$_POST['operate_end_date_day'.$no_main_sec]."-".$_POST['operate_end_date_year'.$no_main_sec])
										);
									}
									tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_weeday);
								}
							}
						}
					}
				}
				//**************************************** end CODING FOR UPDATING "products_is_regular_tour"  *************/
				//***************************************** start code for updating "Destination" *******************//
				if(isset($_POST['selectedcityid']) && ($_POST['selectedcityid'] != ''))	{
					tep_db_query("delete from " . TABLE_PRODUCTS_DESTINATION . " where products_id = '" . (int)$products_id . "'");
					MCache::update_product($products_id);//MCache update
					$selectedcityinsert = explode("::",$_POST['selectedcityid']);
					foreach($selectedcityinsert as $key => $val) {
						//echo "$key => $val <br>";
						$sql_data_duration_days = array('products_id' => $products_id,'city_id' => $val);
						tep_db_perform(TABLE_PRODUCTS_DESTINATION, $sql_data_duration_days);
					}
				}
				//***************************************** end code for updating "Destination" *******************//
				//***************************************** start code for updating "Departure" *******************//
				if(isset($_POST['numberofdaparture']) && ($_POST['numberofdaparture'] != '1')){
					tep_db_query("delete from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = '" . (int)$products_id . "'");
					for($departure_places=1;$departure_places<$_POST['numberofdaparture'];$departure_places++){
						if(tep_db_prepare_input($_POST['departure_address_'.$departure_places]) != '' && tep_db_prepare_input($_POST['depart_time_'.$departure_places]) != '' && tep_db_prepare_input($_POST['regioninsertid_'.$departure_places]) == $departure_places ) {
							$sql_data_departure_places = array(
								'products_id' => $products_id,
								'departure_address' => tep_db_prepare_input($_POST['departure_address_'.$departure_places]),
								'departure_full_address' => tep_db_prepare_input($_POST['departure_full_address_'.$departure_places]),
								'products_hotels_ids' => tep_db_prepare_input(strtolower(preg_replace('/[[:space:]]+/','',$_POST['products_hotels_ids_'.$departure_places]))),
								'departure_time' => tep_db_prepare_input($_POST['depart_time_'.$departure_places]),
								'departure_region' => tep_db_prepare_input($_POST['depart_region_'.$departure_places]),
								'map_path' => tep_db_prepare_input($_POST['departure_map_path_'.$departure_places]),
								'departure_tips' => tep_db_prepare_input($_POST['departure_tips_'.$departure_places])
							);
							$sql_data_departure_places['tour_provider_regions_id'] = false;
							if((int)$_POST['tour_provider_regions_id_'.$departure_places]){
								$sql_data_departure_places['tour_provider_regions_id'] = (int)$_POST['tour_provider_regions_id_'.$departure_places];
							}else{
								//如果是新增的上车地址也要写到供应商上车地址列表
								require_once('includes/classes/tour_provider_regions.php');
								$tour_provider_regions = new tour_provider_regions;
								$agency_ids = explode(',',tep_get_products_agency_id($products_id));
								if(tep_not_null($agency_ids)){
									$data_array = array(
										'agency_ids' => $agency_ids,
										'region' => tep_db_prepare_input($_POST['depart_region_'.$departure_places]),
										'address' => tep_db_prepare_input($_POST['departure_address_'.$departure_places]),
										'full_address' => tep_db_prepare_input($_POST['departure_full_address_'.$departure_places]),
										'departure_time' => tep_db_prepare_input($_POST['depart_time_'.$departure_places]),
										'map_path' => tep_db_prepare_input($_POST['departure_map_path_'.$departure_places]),
										'departure_tips' => tep_db_prepare_input($_POST['departure_tips_'.$departure_places]),
										'products_hotels_ids' => tep_db_prepare_input(strtolower(preg_replace('/[[:space:]]+/','',$_POST['products_hotels_ids_'.$departure_places])))
									);
									$sql_data_departure_places['tour_provider_regions_id'] = $tour_provider_regions->insert($data_array, 'noseession');
								}
							}
							//写当前产品的上车地址必须有地址ID时才写数据
							if((int)$sql_data_departure_places['tour_provider_regions_id']){
								tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_departure_places);
							}
						}
					}
				}
				//***************************************** end code for updating "Departure" *******************//
				if(!isset($_POST['display_pickup_hotels'])){
					$_POST['display_pickup_hotels'] = 0;
				}
				$product_sql_data_array = array(
 				  	'departure_city_id' => tep_db_prepare_input($_POST['departure_city_id']),
				  	'departure_end_city_id' => tep_db_prepare_input($_POST['departure_end_city_id']),
					'products_durations' => tep_db_prepare_input($_POST['products_durations']),
                  	'products_durations_type' => tep_db_prepare_input($_POST['products_durations_type']),
					'display_pickup_hotels' => tep_db_prepare_input($_POST['display_pickup_hotels']),
				  	'products_last_modified' => 'now()'
				);
            	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				if($_POST['notice_customer_service']=="1"){
					//更新产品价格最后更新时间（你记得这个动作很重要就行）
					$PCA->update_product_price_last_modified($products_id,'tour_operation');
				}
				del_customers_basket_for_products($products_id);
				MCache::update_product($products_id);//MCache update
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					$language_id = $languages[$i]['id'];
					$sql_data_array = array(
	 					'products_small_description' => tep_db_prepare_input($_POST['products_small_description['.$language_id.']']),
						'products_description' => tep_db_prepare_input($_POST['products_description['.$language_id.']']),
						'products_notes' => tep_db_prepare_input($_POST['products_notes['.$language_id.']']),
						'products_other_description' => tep_db_prepare_input($_POST['products_other_description['.$language_id.']']),
						'products_package_excludes' => tep_db_prepare_input($_POST['products_package_excludes['.$language_id.']']),
						'products_package_special_notes' => tep_db_prepare_input($_POST['products_package_special_notes['.$language_id.']'])
					);
					//tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					//MCache::update_product($products_id);//MCache update
        		}
				/**========== Start - insert soldout dates ==========**/
				if($_POST['mainSoldOutValue'] > 0){
					$qry_remove_soldout_dates = "DELETE FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." WHERE products_id='".(int)$products_id . "'";
					$res_remove_soldout_dates = tep_db_query($qry_remove_soldout_dates);
					for($i=1; $i <= $_POST['mainSoldOutValue']; $i++){
						if($_POST['products_soldout_date_'.$i]!=""){
							$qry_remove_dup_soldout_dates = "DELETE FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." WHERE products_id='".(int)$products_id . "' AND products_soldout_date = '".$_POST['products_soldout_date_'.$i]."'";
							$res_remove_dup_soldout_dates = tep_db_query($qry_remove_dup_soldout_dates);
							$qry_add_soldout_dates = "INSERT INTO ".TABLE_PRODUCTS_SOLDOUT_DATES."(products_id, products_soldout_date) VALUES ('".(int)$products_id."', '".$_POST['products_soldout_date_'.$i]."')";
							$res_add_soldout_dates = tep_db_query($qry_add_soldout_dates);
						}
					}
				}
				/**========== End - insert soldout dates ==========**/
				/**========== Start - insert RemainingSeats ==========**/
				if($_POST['mainRemainingSeatsValue'] > 0){
					$qry_remove_remaining_seats = "DELETE FROM products_remaining_seats WHERE products_id='".(int)$products_id . "'";
					$res_remove_remaining_seats = tep_db_query($qry_remove_remaining_seats);
					for($i=1; $i <= $_POST['mainRemainingSeatsValue']; $i++){
						if($_POST['products_remaining_seats_'.$i]!=""&&$_POST['products_remaining_seats_num_'.$i]!=""){
							$qry_remove_dup_remaining_seats = "DELETE FROM products_remaining_seats WHERE products_id='".(int)$products_id . "' AND departure_date = '".$_POST['products_remaining_seats_'.$i]."' AND remaining_seats_num = '".$_POST['products_remaining_seats_num_'.$i]."'";
							$res_remove_dup_remaining_seats = tep_db_query($qry_remove_dup_remaining_seats);
							$qry_add_remaining_seats = "INSERT INTO products_remaining_seats(products_id, departure_date,remaining_seats_num,update_date) VALUES ('".(int)$products_id."', '".$_POST['products_remaining_seats_'.$i]."','".$_POST['products_remaining_seats_num_'.$i]."',now())";
							$res_add_remaining_seats = tep_db_query($qry_add_remaining_seats);
						}
					}
				}
				/**========== End - insert RemainingSeats ==========**/
				//=========== Update table products_departure_dates_for_search =========================={
				require_once(DIR_FS_ADMIN.'includes/functions/get_avaliabledate.php');
				$dateArray = get_avaliabledate((int)$products_id);
				$today = date("Y-m-d");
				tep_db_query('DELETE FROM `products_departure_dates_for_search` WHERE `products_id` = "'.(int)$products_id.'" OR `departure_date` <"'.$today.'" ');
				foreach((array)$dateArray as $key => $val){
					$date = substr($key,0,10);
					if($date>=$today){
						tep_db_query('INSERT INTO `products_departure_dates_for_search` (`products_id`, `departure_date`) VALUES ("'.(int)$products_id.'", "'.$date.'")  ');
					}
				}
				//=========== Update table products_departure_dates_for_search ==========================}
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_OPERATION.' Section', 'success');
				//end of tour operation }
			break;
			case 'tour_content':
				//start of tour content
				//print_r($_POST);exit;
				$tour_type_icons_string = '';
				for($i=0;$i<4;$i++){
					if(isset($_POST['tour_type_icon'][$i])){
						$tour_type_icons_string .= tep_db_prepare_input($_POST['tour_type_icon'][$i]).',';
					}
				}
				$tour_type_icons_string = substr($tour_type_icons_string,0,-1);
				$product_sql_data_array = array(
				 				  	'display_itinerary_notes' => tep_db_prepare_input($_POST['display_itinerary_notes']),				                                	'tour_type_icon' => tep_db_prepare_input($tour_type_icons_string),
								  	'products_last_modified' => 'now()'
									);

            	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				
				del_customers_basket_for_products($products_id);
				
				MCache::update_product($products_id);//MCache update
            	

				tep_db_query('delete from products_travel where products_id="'.(int)$products_id.'"');//删除对应行程
				$this_products_durations = tep_db_query('SELECT products_durations,products_durations_type from '.TABLE_PRODUCTS.' where products_id="'.(int)$products_id.'"');
				$this_products_durations = tep_db_fetch_array($this_products_durations);
				$is_travel_to_eticket = true; //false;
				//旧系统只更新行程天数超过1天的行程电子参团凭证信息，新系统则全部更新，不管它是几天的
				if(intval($this_products_durations['products_durations'])>1 && intval($this_products_durations['products_durations_type'])==0){
					$is_travel_to_eticket = true;
				}
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					 $language_id = $languages[$i]['id'];
					 $sql_data_array = array(
										'products_small_description' => tep_db_prepare_input($_POST['products_small_description'][$language_id]),
										'products_description' => tep_db_prepare_input($_POST['products_description'][$language_id]),
										'travel_tips' => tep_db_prepare_input($_POST['travel_tips'][$language_id]),
										'products_notes' => tep_db_prepare_input($_POST['products_notes'][$language_id]),
										'products_other_description' => tep_db_prepare_input($_POST['products_other_description'][$language_id]),
										'products_package_excludes' => tep_db_prepare_input($_POST['products_package_excludes'][$language_id]),
										'products_package_special_notes' => tep_db_prepare_input($_POST['products_package_special_notes'][$language_id])
										);
	
					tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					MCache::update_product($products_id);//MCache update
					if($is_travel_to_eticket){
						//eticket同步========================================================
						$sql="SELECT `eticket_itinerary`, `eticket_hotel` FROM `products_description` WHERE `products_id`='".intval($products_id)."' and `language_id`='".intval($language_id)."'";
						$eticket_query = tep_db_query($sql);
						$eticket_data = tep_db_fetch_array($eticket_query);
						$eticket_data['eticket_itinerary'] = explode('!##!',$eticket_data['eticket_itinerary']);
						$eticket_data['eticket_hotel'] = explode('!##!',$eticket_data['eticket_hotel']);
					}
					//行程相关Start=========================================================
					  $travel = $products_travel[$languages[$i]['id']];
	  				  $_tper = 'travel_';
					 // print_r($_FILES);exit;
					  foreach($travel as $tav_key=>$tav_val){
							$travel_img=$_FILES['products_travel_img_'.$languages[$i]['id'].'_'.$tav_key];
							if(basename($travel_img['name']) != '' ){
								$travel_img_ext = explode('.',$travel_img['name']);
								$travel_img_ext = $travel_img_ext[count($travel_img_ext)-1];
								$travel_img_ext=="" && $travel_img_ext='jpg';
								$filename = 'travel_'.time().'_'.$tav_key.'_'.rand(1000,3000).'.'.$travel_img_ext;
								$uploadfile = DIR_FS_CATALOG_IMAGES.$filename;
								if (move_uploaded_file($travel_img['tmp_name'],$uploadfile)) {
									$oldimg = DIR_FS_CATALOG_IMAGES.$tav_val['oldimg'];
									if(is_file($oldimg)){
										@unlink($oldimg);
									}
									$tav_val['img'] = $filename;
								} 
							}else{
								$tav_val['img'] = $tav_val['oldimg'];
							}
							unset($tav_val['oldimg']);
							$travel_sqldata=array();
							$travel_sqldata['products_id']=(int)$products_id;
							$travel_sqldata['langid']=$languages[$i]['id'];
							$travel_sqldata[$_tper.'index']=$tav_key;
							foreach($tav_val as $tl_dbk=>$tl_dbv){
								$travel_sqldata[$_tper.$tl_dbk]=$tl_dbv;
							}
							if($is_travel_to_eticket){
								if($travel_sqldata[$_tper.'name']!='' || $travel_sqldata[$_tper.'hotel']!=''){
									$eticket_data['eticket_itinerary'][$tav_key-1] = $travel_sqldata[$_tper.'name'];
									$eticket_data['eticket_hotel'][$tav_key-1] = '';
									if($travel_sqldata[$_tper.'hotel']!=''){
											$tmphotel = explode("\r",$travel_sqldata[$_tper.'hotel']);
											$eticket_data['eticket_hotel'][$tav_key-1] = join(', ',$tmphotel);
											$eticket_data['eticket_hotel'][$tav_key-1] .= ' 或者同等级酒店';
									}
								}
							}
							tep_db_perform('products_travel', $travel_sqldata);
					  }
					  if($is_travel_to_eticket){
						  $eticket_data['eticket_itinerary'] = join('!##!',$eticket_data['eticket_itinerary']);
						  $eticket_data['eticket_hotel'] = join('!##!',$eticket_data['eticket_hotel']);
						  
						  $sql="UPDATE `products_description` SET `eticket_itinerary`='".tep_db_input($eticket_data['eticket_itinerary'])."', `eticket_hotel`='".tep_db_input($eticket_data['eticket_hotel'])."' WHERE `products_id`='".intval($products_id)."' and `language_id`='".intval($language_id)."'";
						  tep_db_query($sql);
					  }
					//行程相关End=========================================================
        		}
				setcookie('is_show_tour_content',1);
				header("Location: ".$_SERVER['HTTP_REFERER']);exit;
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_CONTENT.' Section', 'success');
				//check html code
				foreach($sql_data_array as $key => $val){
					if(check_html_code($val)==false){
						$messageStack->add('The html code have error for '.$key.'!', 'error');
					}
				}
				//end of tour content
			break;
			case 'tour_eticket':
				//start of tour eticket

				$product_sql_data_array = array(
				 				  	'products_special_note' => auto_add_note_to_products_special_note((int)$products_id,tep_db_prepare_input($_POST['products_special_note'])),
									'note_to_agency' => tep_db_prepare_input($_POST['note_to_agency']),
								  	'products_last_modified' => 'now()'
									);

            	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				MCache::update_product($products_id);//MCache update
				//check html code
				foreach($product_sql_data_array as $key => $val){
					if(check_html_code($val)==false){
						$messageStack->add('The html code have error for '.$key.'!', 'error');
					}
				}

				if($_POST['products_durations_type'] == 0 && $_POST['products_durations'] != 0 && $_POST['products_durations'] > 0 ){
					$duration_count = $_POST['products_durations'];
				}else if($_POST['products_durations_type'] != 0){
					$duration_count = 1;
				}

				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				 $language_id = $languages[$i]['id'];




				 $eticket_itinerary_separated = '';
				 $eticket_hotel_separated = '';
				 $eticket_notes_separated = '';
				 $eticket_pickup_separated = '';
					for($dj=1; $dj<=$duration_count; $dj++){
					//echo $_POST['eticket_notes['.$language_id.']['.$dj.']'].'<br>'; !##!

					  	if($eticket_itinerary_separated == ''){
							if($_POST['eticket_itinerary['.$language_id.']['.$dj.']'] == ''){
								$eticket_itinerary_separated = ' ';
							}else{
					    		$eticket_itinerary_separated = $_POST['eticket_itinerary['.$language_id.']['.$dj.']'];
							}
						}else{
							$eticket_itinerary_separated .= '!##!'.$_POST['eticket_itinerary['.$language_id.']['.$dj.']'];
						}

						if($eticket_hotel_separated == ''){
							if($_POST['eticket_hotel['.$language_id.']['.$dj.']'] == ''){
							$eticket_hotel_separated = ' ';
							}else{
					    	$eticket_hotel_separated = $_POST['eticket_hotel['.$language_id.']['.$dj.']'];
							}

						}else{
							$eticket_hotel_separated .= '!##!'.$_POST['eticket_hotel['.$language_id.']['.$dj.']'];
						}

						if($eticket_notes_separated == ''){
							if($_POST['eticket_notes['.$language_id.']['.$dj.']'] == ''){
							$eticket_notes_separated = ' ';
							}else{
					    	$eticket_notes_separated = $_POST['eticket_notes['.$language_id.']['.$dj.']'];
							}
						}else{
							$eticket_notes_separated .= '!##!'.$_POST['eticket_notes['.$language_id.']['.$dj.']'];
						}
						if($eticket_pickup_separated == ''){
							if($HTTP_POST_VARS['eticket_pickup['.$language_id.']['.$dj.']'] == ''){
							$eticket_pickup_separated = ' ';
							}else{
					    	$eticket_pickup_separated = $HTTP_POST_VARS['eticket_pickup['.$language_id.']['.$dj.']'];
							}
						}else{
							$eticket_pickup_separated .= '!##!'.$HTTP_POST_VARS['eticket_pickup['.$language_id.']['.$dj.']'];
						}

					 }


				 $sql_data_array = array(
				 					'eticket_itinerary' => tep_db_prepare_input($eticket_itinerary_separated),
									'eticket_hotel' => tep_db_prepare_input($eticket_hotel_separated),
									'eticket_notes' => tep_db_prepare_input($eticket_notes_separated),
									'eticket_pickup' => tep_db_prepare_input($eticket_pickup_separated)
									);

				tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
				MCache::update_product($products_id);//MCache update
					//check html code
					foreach($sql_data_array as $key => $val){
						if(check_html_code($val)==false){
							$messageStack->add('The html code have error for '.$key.'!', 'error');
						}
					}
        		}
				$messageStack->add('Updated '.TEXT_HEADING_ETICKET_INFORMATION.' Section', 'success');
				//end of tour eticket
			break;
			case 'tour_image_video':
				//start of tour image video

				$messageStack->add('Updated '.TEXT_HEADING_IMAGES_VIDEOS.' Section', 'success');
				//end of tour image video
			break;
			case 'tour_meta_tag':
				//start of tour meta tag

				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				 $language_id = $languages[$i]['id'];
				 $sql_data_array = array(
				 					'products_head_title_tag' => tep_db_prepare_input($_POST['products_head_title_tag['.$language_id.']']),
         							'products_head_desc_tag' => tep_db_prepare_input($_POST['products_head_desc_tag['.$language_id.']']),
         							'products_head_keywords_tag' => tep_db_prepare_input($_POST['products_head_keywords_tag['.$language_id.']'])
									);

				tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
        		MCache::update_product($products_id);//MCache update
				}
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_TAG_INFORMATION.' Section', 'success');
				//end of tour meta tag
			break;

			//hotel-extension begin
			case 'tour_hotels_nearby_attractions':
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {		  
				 $language_id = $languages[$i]['id'];
				 $sql_data_array = array(
				 					'products_hotel_nearby_attractions' => tep_db_prepare_input($HTTP_POST_VARS['products_hotel_nearby_attractions['.$language_id.']'])
									);
									
				tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
        	  }
				$messageStack->add('Updated '.TEXT_PRODUCT_HOTELS_NEARBY_ATTRACTIONS.' Section', 'success');

			break;
			case 'tour_hotels_pre_post':
				
				$sql_data_array = array(
				 					'hotels_for_early_arrival' => tep_db_prepare_input($HTTP_POST_VARS['early_arrival_hotels']),
         							'hotels_for_late_departure' => tep_db_prepare_input($HTTP_POST_VARS['late_departure_hotels']),
									'recommend_transfer_id'=>tep_db_prepare_input($HTTP_POST_VARS['recommend_transfer_id'])
									);
									
				tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				$messageStack->add('Updated '.TEXT_PRODUCT_HOTELS_PRE_POST.' Section', 'success');

			break;
			//hotel-extension end
			case 'tour_attribute': //选项价格更新 start {
				//start of tour attribute
				// BOF: WebMakers.com Added: Update Product Attributes and Sort Order
        		$rows = 0;
				$agency_option_ids_string = "'0', ";
				$select_optionsid_of_agencyid_query = tep_db_query("select products_options_id from " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . "  where agency_id = '".$_POST['agency_id']."' group by products_options_id");
				while($select_optionsid_of_agencyid_row = tep_db_fetch_array($select_optionsid_of_agencyid_query)) {
					$agency_option_ids_string .= "'" . $select_optionsid_of_agencyid_row['products_options_id'] . "', ";
				}
				$agency_option_ids_string = substr($agency_option_ids_string, 0, -2);
				$select_optionid_with_agencyid = "select options_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pavtp where products_id = '" . (int)$products_id . "' and options_id not in (".$agency_option_ids_string.") ";
				$select_optionid_with_agencyid_query = tep_db_query($select_optionid_with_agencyid);
				while($select_optionid_with_agencyid_row = tep_db_fetch_array($select_optionid_with_agencyid_query)){
					tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_id='".$select_optionid_with_agencyid_row['options_id']."' and products_id = '" . (int)$products_id . "'");
				}
				$options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$_POST['agency_id']."' order by products_options_sort_order, products_options_name");
				while ($options = tep_db_fetch_array($options_query)) {
					if($_POST['agency_id']!=''){
						$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$_POST['agency_id']."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
					} else {
						$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
					}
					while ($values = tep_db_fetch_array($values_query)) {
						$rows ++;
						$attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,single_values_price_cost, double_values_price_cost,triple_values_price_cost,quadruple_values_price_cost,kids_values_price_cost,  products_options_sort_order, multiplier from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
						if (tep_db_num_rows($attributes_query) > 0) {
							$attributes = tep_db_fetch_array($attributes_query);
							if ($_POST['option['.$rows.']']) {
								//if ( ($_POST['prefix['.$rows.']'] <> $attributes['price_prefix']) || ($_POST['price['.$rows.']'] <> $attributes['options_values_price']) || ($_POST['single_price['.$rows.']'] <> $attributes['single_values_price']) || ($_POST['double_price['.$rows.']'] <> $attributes['double_values_price']) || ($_POST['triple_price['.$rows.']'] <> $attributes['triple_values_price']) || ($_POST['quadruple_price['.$rows.']'] <> $attributes['quadruple_values_price']) || ($_POST['kids_price['.$rows.']'] <> $attributes['kids_values_price']) || ($_POST['products_options_sort_order['.$rows.']'] <> $attributes['products_options_sort_order']) ) {
								$sql_data_array_update = array(
					 				'options_values_price' => $_POST['price['.$rows.']'],
									'single_values_price' => $_POST['single_price['.$rows.']'],
									'double_values_price' => $_POST['double_price['.$rows.']'],
									'triple_values_price' => $_POST['triple_price['.$rows.']'],
									'quadruple_values_price' => $_POST['quadruple_price['.$rows.']'],
									'kids_values_price' => $_POST['kids_price['.$rows.']'],
									'price_prefix' => $_POST['prefix['.$rows.']'],
									'products_options_sort_order' => $_POST['products_options_sort_order['.$rows.']'],
									'multiplier' => max(1, (int)$_POST['multipliers['.$rows.']'])
								);
								if($access_full_edit == 'true') {
									$sql_data_array_product_cost = array(
										'options_values_price_cost' => $_POST['price_cost['.$rows.']'],
										'single_values_price_cost' => $_POST['single_price_cost['.$rows.']'],
										'double_values_price_cost' => $_POST['double_price_cost['.$rows.']'],
										'triple_values_price_cost' => $_POST['triple_price_cost['.$rows.']'],
										'quadruple_values_price_cost' => $_POST['quadruple_price_cost['.$rows.']'],
										'kids_values_price_cost' => $_POST['kids_price_cost['.$rows.']']
									);
									$sql_data_array_update = array_merge($sql_data_array_update, $sql_data_array_product_cost);
								}
								tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_update, 'update', "products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
								// }
							} else {
				  				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
							}
						} elseif ($_POST['option['.$rows.']']) {
			   				$sql_data_array_insert = array(
								'products_id' => $products_id,
								'options_id' => $options['products_options_id'],
								'options_values_id' => $values['products_options_values_id'],
			 					'options_values_price' => $_POST['price['.$rows.']'],
								'price_prefix' => $_POST['prefix['.$rows.']'],
								'single_values_price' => $_POST['single_price['.$rows.']'],
								'double_values_price' => $_POST['double_price['.$rows.']'],
								'triple_values_price' => $_POST['triple_price['.$rows.']'],
								'quadruple_values_price' => $_POST['quadruple_price['.$rows.']'],
								'kids_values_price' => $_POST['kids_price['.$rows.']'],
								'products_options_sort_order' => $_POST['products_options_sort_order['.$rows.']'],
								'multiplier' => max(1, (int)$_POST['multipliers['.$rows.']'])
							);
							if($access_full_edit == 'true') {
								$sql_data_array_product_cost_insert = array(
									'options_values_price_cost' => $_POST['price_cost['.$rows.']'],
									'single_values_price_cost' => $_POST['single_price_cost['.$rows.']'],
									'double_values_price_cost' => $_POST['double_price_cost['.$rows.']'],
									'triple_values_price_cost' => $_POST['triple_price_cost['.$rows.']'],
									'quadruple_values_price_cost' => $_POST['quadruple_price_cost['.$rows.']'],
									'kids_values_price_cost' => $_POST['kids_price_cost['.$rows.']']
								);
								$sql_data_array_insert = array_merge($sql_data_array_insert, $sql_data_array_product_cost_insert);
							}
			   				tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_insert);
						}
					}
				}
				if($_POST['notice_customer_service']=="1"){
					//更新产品价格最后更新时间（你记得这个动作很重要就行）
					$PCA->update_product_price_last_modified($products_id,'tour_attribute');
				}
				del_customers_basket_for_products($products_id);
				
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_ATTRIBUTES.' Section', 'success');
				//end of tour attribute
			//选项价格更新 end }
			break;

		 } //end of swich

} //end of check proccess




$parameters = array('products_name' => '',
						'products_model' => '',
						'provider_tour_code' => '',
						'products_urlname' => '',
                       'products_description' => '',
                       'products_notes' => '',
					    'products_pricing_special_notes' => '',
					   'products_other_description' => '',
					   'products_package_excludes' => '',
					   'products_package_special_notes' => '',
					   'eticket_itinerary' => '',
					   'eticket_hotel' => '',
					   'eticket_notes' => '',
					   'eticket_pickup' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_is_regular_tour' => '',
                       'products_image' => '',
					   'products_map' => '',
                       'products_image_med' => '',
                       'products_image_lrg' => '',
                       'products_price' => '',
                       'products_durations' => '',
                       'products_durations_type' => '',
                       'products_durations_description' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
					   'display_room_option' => '',
                       'products_status' => '',
					   'products_stock_status' => '',
					   'display_itinerary_notes' => '',
					   'display_hotel_upgrade_notes' => '',
					   'products_type' => '',
					   'operate_start_date' => '',
					   'operate_end_date' => '',
                       'products_tax_class_id' => '1',
					   'agency_id' => '',
					   'products_margin' => '',
					   'display_pickup_hotels' => '1',
                       'manufacturers_id' => '',
					   'min_num_guest' => '1',
					   'products_info_tpl' => '',
					   'is_hotel' => '', //hotel-extension
					    'is_transfer' => '', //transfer-service
					    'only_our_free'=>'',
					   );

$pInfo = new objectInfo($parameters);
 $product_query = tep_db_query("select p.tour_type_icon, ta.default_max_allow_child_age , p.max_allow_child_age, ta.default_transaction_fee , p.transaction_fee, p.products_class_id, p.products_class_content, p.book_limit_days_number, p.with_air_transfer,p.is_visa_passport, p.products_margin, p.products_video, p.operate_start_date, p.operate_end_date, p.products_type, p.products_info_tpl, p.maximum_no_of_guest,p.products_single,p.products_single_pu,p.products_double,p.products_triple,p.products_quadr,p.products_kids,p.products_single_cost,p.products_single_pu_cost, p.products_double_cost, p.products_triple_cost, p.products_quadr_cost, p.products_kids_cost, p.products_special_note, p.note_to_agency, p.regions_id, p.agency_id, p.departure_city_id, p.departure_end_city_id, p.display_pickup_hotels, p.products_model, p.products_model_sub, p.products_model_sub_notes, p.provider_tour_code, p.provider_tour_code_sub, p.products_urlname, p.products_stock_status,
 pd.products_name, pd.products_description, pd.products_other_description, pd.products_package_excludes , pd.products_package_special_notes, pd.eticket_itinerary, pd.eticket_hotel, pd.eticket_notes, pd.products_pricing_special_notes, pd.products_small_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_is_regular_tour,p.products_map ,p.products_image, p.products_image_med, p.products_image_lrg, p.products_price, p.products_durations, p.products_durations_type, p.products_durations_description, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status,p.products_vacation_package, p.display_room_option, p.products_tax_class_id, p.manufacturers_id, p.display_itinerary_notes, p.display_hotel_upgrade_notes, p.min_num_guest, p.products_surcharge, p.use_buy_two_get_one_price , p.upgrade_to_product_id, pd.eticket_pickup , p.is_hotel, p.is_transfer,p.transfer_type,p.recommend_transfer_id, pd.products_hotel_nearby_attractions ,p.hotels_for_early_arrival, p.hotels_for_late_departure, p.is_cruises, p.only_our_free,pd.eticket_old,
pd.manual_related_products_title, pd.manual_related_products_content 
 from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd , ".TABLE_TRAVEL_AGENCY." as ta where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_id = pd.products_id and p.agency_id = ta.agency_id and pd.language_id = '" . (int)$languages_id . "'");
$product = tep_db_fetch_array($product_query);

$pInfo->objectInfo($product);


/*
if($_GET['upimage']=='true'){
$messageStack->add('Updated '.TEXT_HEADING_IMAGES_VIDEOS.' Section', 'success');

}
*/


 if ($messageStack->size > 0) {
        echo $messageStack->output();
 }

switch ($_GET['section']) {

	case 'tour_categorization':
		//start of tour categorization section


		if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
		switch ($pInfo->products_status) {
			  case '0': $in_status = false; $out_status = true; break;
			  case '1':
			  default: $in_status = true; $out_status = false;
		}

		//hotel-extension begin
		if (!isset($pInfo->is_hotel)) $pInfo->is_hotel = '0';
		switch ($pInfo->is_hotel) {
		  case '1': $is_hotel_yes = true; $is_hotel_no = false; break;
		  case '0':
		  default: $is_hotel_yes = false; $is_hotel_no = true;
		}
		//hotel-extension end
		//transfer-service {		
		if (!isset($pInfo->is_transfer)) $pInfo->is_transfer = '0';
		switch ($pInfo->is_transfer) {
		  case '1': $is_transfer_yes= true; $is_transfer_no = false; break;
		  case '0':
		  default: $is_transfer_yes = false; $is_transfer_no = true;
		}
		//}transfer-service

		if (!isset($pInfo->products_stock_status)) $pInfo->products_stock_status = '1';
		switch ($pInfo->products_stock_status) {
			  case '0': $in_stock = false; $out_stock = true; break;
			  case '1':
			  default: $in_stock = true; $out_stock = false;
		}

		if (!isset($pInfo->products_vacation_package)) $pInfo->products_vacation_package = '0';
		switch ($pInfo->products_vacation_package) {
		  case '0': $in_status_products_vacation_package = false; $out_status_products_vacation_package = true; break;
		  case '1':
		  default: $in_status_products_vacation_package = true; $out_status_products_vacation_package = false;
		}

		//$products_type_array = array(array('id' => '', 'text' => TEXT_NONE));
		$products_type_array = tep_db_query("select products_type_id, products_type_name from " . TABLE_PRODUCTS_TYPES . " order by products_type_id");
		while ($products_type_info = tep_db_fetch_array($products_type_array)) {
		  $products_type_arrays[] = array('id' => $products_type_info['products_type_id'],
										 'text' => $products_type_info['products_type_name']);
		}


		$agency_array = array(array('id' => '', 'text' => TEXT_NONE));
		$agency_query = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
		while ($agency_result = tep_db_fetch_array($agency_query)) {
		  $agency_array[] = array('id' => $agency_result['agency_id'],
										 'text' => $agency_result['agency_name']);
		}
		/*
		 $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
		$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
		while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		  $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
									 'text' => $tax_class['tax_class_title']);
		}
		*/
		?>
		<?php
		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}


		 if($display_room_yes) {
		 ?>
		<div id="optionyes" style="visibility: visible; display: inline;"></div>

		   <div id="optionno" style="visibility: hidden; display: none;"></div>
		 <?php
		 }elseif($display_room_no){
		 ?>
		<div id="optionyes" style=" visibility: hidden; display: none;"></div>

		   <div id="optionno" style="visibility: visible; display: inline;"></div>
		<?php }?>
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_CATEGORIZATION; ?></b></td></tr>
		 <tr><td class="main" colspan="2">

		 <form name="new_product"  id="new_product">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php 
            if ($can_open_or_close_products == true) {
            	echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE;
            	echo '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE;
            } else {
				echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;';
				if ($in_status) {
					echo tep_draw_hidden_field('products_status','1');
					echo '<span style="background:#99FF00">√&nbsp;' . TEXT_PRODUCT_AVAILABLE . '</span>&nbsp;&nbsp;X&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE;
				} elseif ($out_status) {
					echo tep_draw_hidden_field('products_status','0');
					echo 'X&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;&nbsp;<span style="color:#ff0000">√&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE . '</span>';
				}
			} 
            
            ?></td>
         </tr>

		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STOCK; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_stock_status', '1', $in_stock) . '&nbsp;' . TEXT_PRODUCTS_IN_STOCK . '&nbsp;' . tep_draw_radio_field('products_stock_status', '0', $out_stock) . '&nbsp;' . TEXT_PRODUCTS_OUT_STOCK; ?>
            <?php
            $countemail = tep_db_query("SELECT count(products_id) as countemail FROM `products_soldout_email` WHERE `products_id`='".(int)$_GET['pID']."' and `email`!='#soldout_sending#'");
			$countemail = tep_db_fetch_array($countemail);
			$countemail = intval($countemail['countemail']);
			if($countemail>0){
				$soldout_sending = tep_db_query("SELECT count(products_id) as soldout_sending FROM `products_soldout_email` WHERE `products_id`='".(int)$_GET['pID']."' and `email`='#soldout_sending#'");
				$soldout_sending = tep_db_fetch_array($soldout_sending);
				$soldout_sending = intval($soldout_sending['soldout_sending']);
			?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" id="send_soldout_email_button" onclick="send_soldout_email('<?php echo (int)$_GET['pID'];?>');" value=" <?php echo db_to_html('发送“恢复预订通知”的邮件');?> "><?php echo db_to_html('(待发送邮件 <b style="color:red" id="send_soldout_email_numb">'.$countemail.'</b> 封)');?><span id="soldout_msg" style="color:#F00;padding-left:10px;"><?php
			$soldout_email_sending=0;
            if($soldout_sending){
				$soldout_email_sending=1;
				echo db_to_html('正在发送“恢复预订通知”的邮件... ...');
			}
			?></span>
            <script type="text/javascript">
			var soldout_email_sending = <?php echo $soldout_email_sending;?>;
			function send_soldout_email(pid){
				if(soldout_email_sending){
					if(!confirm("<?php echo db_to_html('邮件已经是发送状态，您确定要再次发送吗？');?>")){
						return false;	
					}
				}
				var obj = jQuery('#send_soldout_email_button');
				obj.attr('disabled',true);
				var html = '<script';
				html += ' type="text/javascript"';
				html += ' src="categories_ajax_sections.php?section=products_send_soldout_email&product_id='+pid+'"';
				html += ' charset="<?php echo CHARSET;?>">';
				html += '</';
				html += 'script>';
				show_sendmail_msg(1);
				jQuery('#soldout_msg').html('<?php echo db_to_html('正在发送“恢复预订通知”的邮件... ...');?>');
				
				jQuery(obj).after(html);
			}
			function show_sendmail_msg(t){
				if(t==1){
					alert('<?php echo db_to_html('点击“确定”程序开始发送邮件\r\n\r\n您可以离开这个页面做其他操作\r\n而不会影响邮件的发送！');?>');
				}else if(t==2){
					jQuery('#send_soldout_email_button').remove();
					jQuery('#send_soldout_email_numb').html('0');
					var str = '<?php echo db_to_html('“恢复预订通知”的邮件已经全部发送完毕!');?>'
					jQuery('#soldout_msg').html(str);
					alert(str);
				}else if(t==3){
					jQuery('#send_soldout_email_button').remove();
					jQuery('#send_soldout_email_numb').html('0');
					var str = '<?php echo db_to_html('产品不存在，或没有待发送邮件！');?>'
					jQuery('#soldout_msg').html(str);
					alert(str);
				}
			}
			</script>
            <?php }?>
            </td>
         </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		   <tr>
				<td class="main" ><?php echo db_to_html("是否为接送服务产品?"); ?></td>
				<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('is_transfer', '1', $is_transfer_yes,'',' onclick="jQuery(\'#transferTr\').fadeIn(\'slow\');jQuery(\':radio[name=is_hotel][value=0]\').trigger(\'click\')"') . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('is_transfer', '0', $is_transfer_no,'',' onclick="jQuery(\'#transferTr\').fadeOut(\'slow\')"') . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
		   </tr>
		   <?php //hotel-extension begin?>
		   <tr>
				<td class="main" ><?php echo db_to_html("是否为酒店产品?"); ?></td>
				<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('is_hotel', '1', $is_hotel_yes,'',' onclick="jQuery(\'#hotelIdTr\').fadeIn(\'slow\');jQuery(\':radio[name=is_transfer][value=0]\').trigger(\'click\');" ') . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('is_hotel', '0', $is_hotel_no,'',' onclick="jQuery(\'#hotelIdTr\').fadeOut(\'slow\')" ') . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
		   </tr>
		   <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		   </tr>
		    <tr id="hotelIdTr" style="<?php echo $is_hotel_yes==true? '' : 'display:none' ?>">
				<td class="main" ><?php echo db_to_html("酒店管理中对应的 Hotel_id"); ?></td>
				<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
				<?php
			  //@author关联 酒店管理表
				$sql = tep_db_query('SELECT hotel_id as id , hotel_name as text FROM hotel WHERE 1');
				$all_hotels=array('0'=>array('id'=>'','text'=>'===请选择 =='));
				while($hotel=tep_db_fetch_array($sql)){
					$all_hotels[] = $hotel;
				}
				if($is_hotel_yes==true){
					$query = tep_db_query('SELECT hotel_id FROM hotel WHERE products_id = '.intval($_GET['pID']).' LIMIT 1');
					$row = tep_db_fetch_array($query);
					if($row==false) $myhotelId='';
					else $myhotelId=$row['hotel_id'];
				}
				$order = array();
				foreach($all_hotels as $h)$order[]=$h['text'];
				array_multisort($order,SORT_ASC,SORT_STRING,$all_hotels);
				$ordered = array();
				foreach($all_hotels as $row) $ordered[]=$row;
				echo tep_draw_pull_down_menu('hotel_id', $ordered, $myhotelId, 'onchange="get_hotel_info(this.value);"');
				?>
                <div id="div_hotel_info">
                <?php
				if((int)$myhotelId > 0){
				$hotel_id = $myhotelId;
				$product_hotel_info_query = tep_db_query("select hotel_address, hotel_phone from hotel where hotel_id = '".$hotel_id."'");
				$product_hotel_info = tep_db_fetch_array($product_hotel_info_query);
				?>
				<table style="margin-left:24px;">
					<tr><td class="main">Address: </td><td class="main"><?php echo $product_hotel_info['hotel_address']; ?></td></tr>
					<tr><td class="main">Phone: </td><td class="main"><?php echo $product_hotel_info['hotel_phone']; ?></td></tr>
					<tr><td class="main" colspan="2"><a href="<?php echo tep_href_link(FILENAME_HOTEL_ADMIN, 'hotel_id='.$hotel_id.'&action=edit&search_one_hotel=true'); ?>" target="_blank">Edit Hotel Information</a></td></tr>
				</table>
                <?php }else{ ?>
				<table style="margin-left:24px;">
					<tr><td class="main"><b><u>OR</u></b> <a href="<?php echo tep_href_link(FILENAME_HOTEL_ADMIN, 'action=new'); ?>" target="_blank">Add New Hotel</a></td></tr>
				</table>
				<?php } ?>
                </div>
				</td>
		   </tr>
		   <tr id="transferTr" style="<?php echo $is_transfer_yes==true? '' : 'display:none' ?>">
				<td class="main" ><?php echo db_to_html("接送服务计费方式"); ?></td>
				<td class="main"><?php 
					echo tep_draw_separator('pixel_trans.gif', '24', '15') ; 
					$transfer_types = array(
						array('id'=>0,'text'=>'线路固定价格'),
						array('id'=>1,'text'=>'线路差异价格')
					);
					echo tep_draw_pull_down_menu('transfer_type', $transfer_types, $pInfo->transfer_type, '');
					echo '<ul style="color:red;margin-left:30px"><li><b>线路固定价格:</b>适用于洛杉矶接送服务，该服务只提供机场到各地点的服务，价格固定 需要在"Room And Price"选项卡下设定价格</li>
					<li><b>线路差异价格:</b>适用于纽约接送服务，该服务需要设定每条线路的详细价格，在"Transfer Routine&Price"选项卡设置价格
					</li></ul>';
				?>
				</td>
			</tr>
		   <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		   </tr>
		   <?php
			//hotel-extension end
			// cruises start {
		   ?>  
		    <tr>
            <td class="main"><?php echo TEXT_IS_CRUISES; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
			<?php
			
			echo tep_draw_radio_field('is_cruises', '1', (int)$pInfo->is_cruises?true:false,'', 'onClick="jQuery(\'#cruises_list\').show();"') . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('is_cruises', '0', (int)$pInfo->is_cruises?false:true, '', 'onClick="jQuery(\'#cruises_list\').hide();"');
			echo '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE_VACATION_PACKAGE;

			$cruisesSql = tep_db_query('SELECT cruises_id, cruises_name FROM `cruises` WHERE agency_id="'.(int)$pInfo->agency_id.'" ');
			$coptions = array();
			$coptions[] = array('id'=>0,'text'=>'--请选择邮轮--');
			while($cruisesRows = tep_db_fetch_array($cruisesSql)){
				$coptions[] = array('id'=>$cruisesRows['cruises_id'], 'text'=>$cruisesRows['cruises_name']);
			}
			
			$cruises_list_display = 'none';
			if((int)$pInfo->is_cruises){
				$cruises_list_display = 'block';
				$csql = tep_db_query('SELECT cruises_id FROM `products_to_cruises` WHERE products_id ="'.(int)$pInfo->products_id.'" ');
				$crow = tep_db_fetch_array($csql);
			}
			?>
			
			<div id="cruises_list" style="margin:10px 24px; display:<?= $cruises_list_display;?>;">
			<?php echo tep_draw_pull_down_menu('cruises_id',$coptions,$crow['cruises_id']); ?>
			</div>
			
			</td>
         </tr>
		   <?php
			//cruises end }
		   ?>  
		 
		    <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_VACATION_PACKAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
			<?php
			if($pInfo->products_id == '')
			{
				$products_pricing_special_notes[1] = TOURS_DEFAULT_PRICING_NOTES;
				echo tep_draw_radio_field('products_vacation_package', '1', $in_status_products_vacation_package) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('products_vacation_package', '0', $out_status_products_vacation_package);
			}else{
				echo tep_draw_radio_field('products_vacation_package', '1', $in_status_products_vacation_package) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('products_vacation_package', '0', $out_status_products_vacation_package);
			}

			echo '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE_VACATION_PACKAGE;

			?></td>
         </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		   <tr>
            <td class="main"><?php echo HEADING_TITLE_PRODUCTS_TYPE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('products_type', $products_type_arrays, $pInfo->products_type); //'onChange="show_go(this.value);"'?></td>
         </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_REGION; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('regions_id', tep_get_region_tree(), $pInfo->regions_id); //'id="regions_id" onChange="javascript: generate();"' ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_AGENCY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
			<?php

			echo tep_draw_pull_down_menu('agency_id', $agency_array, $pInfo->agency_id,'id="agency_id" onChange="change_tour_attri_list(\''.$pInfo->products_id.'\',this.value);" '); //onChange="getInfo(\'\',\'-2\'); change_tour_attri_list(\''.$pInfo->products_id.'\',this.value);"

			echo tep_draw_hidden_field('products_id', $pInfo->products_id);
			echo tep_draw_hidden_field('prev_agency_id', $pInfo->agency_id);
			echo tep_draw_hidden_field('prev_regions_id', $pInfo->regions_id);

			?>

			</td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

			<?php
				$products_name_readonly = '';	//是否禁用产品修改功能。是有漏洞，不过可暂时这样处理！
				$products_url_readonly = false;	//是否禁用产品url修改功能。是有漏洞，不过可暂时这样处理！
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					if($products_name_readonly == '' && $products_detail_permissions['Categorization']['不能修改产品名称和URL地址'] == true){
						$products_name_readonly = ' readonly="true" ';
						$products_url_readonly = true;
					}
			?>
					  <tr>
						<td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
						<td class="main">
						<?php
						echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])),'size="60" '.$products_name_readonly);
						?>
						</td>
					  </tr>
			<?php
				}
			?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>

            <td class="main"><?php echo TEXT_PRODUCTS_URL_NAME; ?></td>

            <td class="main">
			<?php
			$onfocusStr = ' if(this.getAttribute(\'readonly\') == \'readonly\' || this.getAttribute(\'readonly\') == true){ popup_show(\'popup\', \'popup_drag\', \'popup_exit\', \'element-bottom\', 0, 10, \'products_urlname\');} ';
			if($products_url_readonly === true){
				$onfocusStr = '';
			}
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_urlname', $pInfo->products_urlname, ' id="products_urlname" size="60" readonly="readonly"  onfocus="'.$onfocusStr.'"');
			?>
			</td>

          </tr>

		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  <tr>

            <td class="main"><?php echo db_to_html('页面模板:'); ?></td>

            <td class="main">
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?>
			<?php
			$tpl_array = array();
			$tpl_array[] = array('id'=>'', 'text'=> db_to_html('Default(默认)'));
			$tpl_array[] = array('id'=>'product_info_vegas_show', 'text'=> db_to_html('拉斯维加斯秀'));
			echo tep_draw_pull_down_menu('products_info_tpl', $tpl_array ,$pInfo->products_info_tpl,' id="products_info_tpl" onChange="set_tpl_obj(this)" ');
			?>
			</td>

          </tr>

		  <?php
		  $show_display = 'none';
		  if($pInfo->products_info_tpl=="product_info_vegas_show"){
		  	$show_display = '';
			if(!isset($products_hotel_id) || !isset($min_watch_age)){
				$show_sql = tep_db_query('SELECT * FROM `products_show` WHERE products_id = "'.(int)$products_id.'" Limit 1');
				$show_row = tep_db_fetch_array($show_sql);
				$products_hotel_id = (int)$show_row['products_hotel_id'];
				$min_watch_age = (int)$show_row['min_watch_age'];
			}
		  }
		  ?>
		  <tr id="show_hotel" style="display:<?= $show_display?>">

            <td class="main"><?php echo db_to_html('表演场地:'); ?></td>

            <td class="main">
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?>
			<?php
			$hotel_array = array();
			$hotel_array[] = array('id'=>"0", 'text'=> db_to_html('无'));
			$hotel_sql = tep_db_query('SELECT pd.products_id, pd.products_name FROM `products_description` pd, `products_to_categories` ptc WHERE ptc.products_id=pd.products_id AND categories_id = "182" AND language_id="'.$languages_id.'" Group By pd.products_id ');
			while($hotel_rows = tep_db_fetch_array($hotel_sql)){
				$hotel_array[] = array('id'=>(int)$hotel_rows['products_id'], 'text'=> db_to_html(tep_db_output($hotel_rows['products_name'])));
			}
			echo tep_draw_pull_down_menu('products_hotel_id', $hotel_array );
			?>
			</td>

          </tr>
		  <tr id="min_watch_year" style="display:<?= $show_display?>">

            <td class="main"><?php echo db_to_html('最小观看年龄:'); ?></td>

            <td class="main">
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?>
			<?php
			$age_array = array();
			for($ag=0; $ag<20; $ag++){
				if($ag==0){
					$age_array[] = array('id'=>$ag, 'text'=> db_to_html('无限制'));
				}else{
					$age_array[] = array('id'=>$ag, 'text'=> db_to_html($ag.' 岁以上'));
				}
			}
			echo tep_draw_pull_down_menu('min_watch_age', $age_array );
			?>
			</td>

          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		    <?php
							for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
						?>
								  <tr>
									<td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME_PROVIDER; ?></td>
									<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name_provider[' . $languages[$i]['id'] . ']', (isset($products_name_provider[$languages[$i]['id']]) ? $products_name_provider[$languages[$i]['id']] : tep_get_products_name_provider($pInfo->products_id, $languages[$i]['id'])),'size="60"'); ?></td>
								  </tr>
						<?php
							}
						?>

		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL;    $products_id_model = $pInfo->products_id;?></td>
            <td class="main"><?php
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->products_model;

			echo tep_draw_hidden_field('products_model', $pInfo->products_model);

			?></td>
          </tr>
          <tr>
            <td class="main"><?php echo db_to_html('子团号(Sub-Tour Code)：'); ?></td>
            <td class="main"><?php
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model_sub', $pInfo->products_model_sub, ' size="60"');
			echo db_to_html('<span style="color:#FF0000">&nbsp;多个子团号请用;号分隔如：UST HNLL61-986; UST HNLL63-987。子团号的格式与Tour Code的格式一样</span>');
			//子团号包含了供应商信息USSLC2-118，如2就是供应商id号，而供应商代码只能起个参考作用。所以子团号是非常重要的！
			?></td>
          </tr>

		  <tr>
            <td class="main"><?php echo db_to_html('子团号注释：'); ?></td>
            <td class="main"><?php
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model_sub_notes', $pInfo->products_model_sub_notes, ' size="60"');
			?></td>
          </tr>

		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			<tr>
			<td class="main"><?php echo TEXT_PRODUCTS_PROVIDER_MODEL;?>
			</td>
			<td class="main"><?php
			//如果provider_tour_code为空时自动添加供应商代码
			if(!tep_not_null($pInfo->provider_tour_code)){
				$pInfo->provider_tour_code = tep_get_agency_code($pInfo->agency_id).'-请补代码';
			}
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('provider_tour_code', $pInfo->provider_tour_code);

			?></td>
		  </tr>
			<tr>
			<td class="main"><?php echo db_to_html('子供应商代码(Sub-Provider Tour Code)：');?>
			</td>
			<td class="main"><?php

			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('provider_tour_code_sub', $pInfo->provider_tour_code_sub, ' size="60"');
			echo db_to_html('<span style="color:#FF0000">&nbsp;多个子供应商代码;号分隔</span>');
			?></td>
		  </tr>
		  <tr>
			<td class="main"><?php echo TEXT_PRODUCTS_UPGRADE_TO;?>
			</td>
			<td class="main"><?php

			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('upgrade_to_product_id', $pInfo->upgrade_to_product_id);

			?></td>
		  </tr>
		  <tr>
            <td class="main"><?php echo db_to_html('团的级别products class:'); ?></td>
            <td class="main"><?php
			$option_array = array();
			$option_sql = tep_db_query('SELECT * FROM `products_class` ORDER BY `products_class_id`');
			while($option_rows = tep_db_fetch_array($option_sql)){
				$option_array[] = array('id'=> $option_rows['products_class_id'], 'text'=> $option_rows['products_class_name']);
				if(!tep_not_null($pInfo->products_class_content) && $option_rows['products_class_id']=='1'){
					$products_class_content = $option_rows['products_class_defaults_content'];
				}
			}

			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_class_id', $option_array ,$pInfo->products_class_id,' id="products_class_id" onchange="get_products_class_defaults_content(this.value)"');

			?>

			</td>
          </tr>
		  <tr>
		  <td class="main"><?php echo db_to_html('团的级别描述:'); ?></td>
		  <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('products_class_content', $pInfo->products_class_content,'id="products_class_content" size="60"');?></td>
		  </tr>
		  
          <?php //Howard added 2010-07-29 start?>
          <tr>
		  <td class="main">How many days can be booked before departure date:</td>
		  <td class="main">
		  <?php
			$days_number_array = array();
			$days_number = 0;
			if((int)$pInfo->book_limit_days_number){ $days_number = $pInfo->book_limit_days_number; }
			for($i=0; $i<8; $i++){
				if($i==0){
					$days_number_array[] = array('id'=>$i, 'text'=> AUTO_GET_FROM_AGENCY);
				}else{
					$days_number_array[] = array('id'=>$i, 'text'=> $i.' days');
				}
			}
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'.tep_draw_pull_down_menu('book_limit_days_number', $days_number_array ,$days_number );
		  	echo '<span style="color:#FF0000">&nbsp;'.BOOK_LIMIT_DAYS_NUMBER_TIPS.'</span>';
		  ?></td>
		  </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <?php //Howard added 2010-07-29 end?>
		  
		  <?php //关联产品信息输入框 Howard added 2012-10-13 start {?>
          <tr>
		  <td class="main">手工关联产品：</td>
		  <td class="main">
		  关联的标题：<?= tep_draw_input_field('manual_related_products_title', $pInfo->manual_related_products_title,'size="20"');?>
		  <br />
		  关联的产品(<b style="color:#F00">须包含本产品的id</b>)：<?= tep_draw_input_field('manual_related_products_content', $pInfo->manual_related_products_content,'size="100"');?>
		  格式：<?= $manualRelatedProducts->related_format_example;?>
		  </td>
		  </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <?php //关联产品信息输入框 Howard added 2012-10-13 end }?>
		  
          <tr>
		  <td class="main"><?php echo db_to_html('Airport Transfer:'); ?></td>
		  <td class="main">
		  <?php
		  $with_air_transfer = "0";
		  $with_air_transfer_0_checked = true;
		  $with_air_transfer_1_checked = false;
		  
		  if($pInfo->with_air_transfer=="1"){
			  $with_air_transfer = $pInfo->with_air_transfer;
			  $with_air_transfer_0_checked = false;
			  $with_air_transfer_1_checked = true;
		  }
		  echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;';
		  echo '<label>'.tep_draw_radio_field('with_air_transfer','0',$with_air_transfer_0_checked).db_to_html(' without airport transfer').'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
		  echo '<label>'.tep_draw_radio_field('with_air_transfer','1',$with_air_transfer_1_checked).db_to_html(' with airport transfer').'</label>';
		  ?>
		  </td>
		  </tr>
			<?php
								   if (!isset($pInfo->is_visa_passport)) $pInfo->is_visa_passport = '0';
									switch ($pInfo->is_visa_passport) {
									  case '2': $is_visa_yes = false; $is_visa_no = false; $is_require = true;  break;
									  case '1': $is_visa_yes = true; $is_visa_no = false; $is_require = false;  break;
									  case '0':
									  default: $is_visa_yes = false; $is_visa_no = true; $is_require = false;
									}
								 ?>
								 <tr>
										<td class="main" ><?php echo TEXT_IS_PASSPORT_VISA; ?></td>
										<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_radio_field('is_visa_passport', '0', $is_visa_no, '') . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_VISA_NO . '&nbsp;' . tep_draw_radio_field('is_visa_passport', '2', $is_require) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_VISAPASS_YES . '&nbsp;' .  tep_draw_radio_field('is_visa_passport', '1', $is_visa_yes, '') . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_VISAPASS_NO; ?></td>
								 </tr>
		  <?php
		  //如果是酒店资料 start
		  if(preg_match('/^182(\_){0,1}/',$_GET['cPath'])){
		  	$hotel_sql = tep_db_query('SELECT * FROM `products_hotels` WHERE products_id="'.(int)$_GET['pID'].'" ');
			$hotel_row = tep_db_fetch_array($hotel_sql);
			if(isset($_POST['hotel_star'])){
				$hotel_star = $_POST['hotel_star'];
			}else{
				$hotel_star = $hotel_row['hotel_star'];
			}
			if(isset($_POST['hotel_address'])){
				$hotel_address = $_POST['hotel_address'];
			}else{
				$hotel_address = $hotel_row['hotel_address'];
			}
		  ?>
		  <tr bgcolor="#FFF0E8">
		  <td class="main"><?php echo db_to_html('酒店星级:'); ?></td>
		  <td class="main">
		  <?php
		  $option_star_array = array();
		  $option_star_array[0] = array('id'=>0, 'text'=>db_to_html('无'));
		  $option_star_array[1] = array('id'=>1, 'text'=>1);
		  $option_star_array[2] = array('id'=>2, 'text'=>2);
		  $option_star_array[3] = array('id'=>3, 'text'=>3);
		  $option_star_array[4] = array('id'=>4, 'text'=>4);
		  $option_star_array[5] = array('id'=>5, 'text'=>5);
		  $option_star_array[6] = array('id'=>6, 'text'=>6);
		  echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('hotel_star', $option_star_array ,$hotel_star,' id="hotel_star" ').'&nbsp;'.db_to_html('星');
		  ?>
		  </td>
		  </tr>
		  <tr bgcolor="#FFF0E8">
		  <td class="main"><?php echo db_to_html('酒店地址:'); ?></td>
		  <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('hotel_address', $hotel_address,'id="hotel_address" size="60"');?></td>
		  </tr>
		  <?php
		  }
		  //如果是酒店资料 end
		  ?>


		  <?php /*
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>


		  */ ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
		  <td colspan="2">
		  <div id="tour_attribute_list">

		  </div>
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  echo tep_image_button('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '').(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_categorization">
		  <input type="hidden" name="qaanscall" value="true">

		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  </table>
			</form>
		 </td></tr>
		 </table>
		<?php
		//end of tour categorization section
    break;

	case 'room_and_price':
		//start of tour room and price {
		$is_transfer_product = tep_check_product_is_transfer($pInfo->products_id);
		$tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
		$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
		while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		  $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
									 'text' => $tax_class['tax_class_title']);
		}

		if (!isset($pInfo->display_hotel_upgrade_notes)) $pInfo->display_hotel_upgrade_notes = '1';
		  switch ($pInfo->display_hotel_upgrade_notes) {
		  case '0': $display_hotel_upgrade_notes_in_status = false; $display_hotel_upgrade_notes_out_status = true; break;
		  case '1':
		  default: $display_hotel_upgrade_notes_in_status = true; $display_hotel_upgrade_notes_out_status = false;
		}

		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}


		if($is_transfer_product) $display_room_yes = true;
		 if($display_room_yes)
		 {
		  $div_first_room_yes_display = '<div id="optionyes" style="visibility: visible; display: inline;">';
		  $div_first_room_no_display =  '<div id="optionno" style="visibility: hidden; display: none;">';
		 }elseif($display_room_no){
		  $div_first_room_yes_display = '<div id="optionyes" style=" visibility: hidden; display: none;">';
		  $div_first_room_no_display =  '<div id="optionno" style="visibility: visible; display: inline;">';
		}
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_ROOM_AND_PRICE; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		<?php if($products_detail_permissions['RoomAndPrice']['能看不能编'] != true){?>	
		 <form name="new_product"  id="new_product">
		<?php } ?>
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		<?php
		$parent_cat_array = array();
		if((int)$current_category_id == 0){
			$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $pInfo->products_id . "'");
			$product_to_categories = tep_db_fetch_array($product_to_categories_query);
			$current_category_id = $product_to_categories['categories_id'];
		}
		  tep_get_parent_categories($parent_cat_array, $current_category_id);	
		  /*
		  if($current_category_id == CRUISE_CATEGORY_ID){
			$parent_cat_array[] = $current_category_id;
		  }
		  if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){
		  	echo tep_draw_hidden_field('is_cruise', '1');
		  }
		  */
		?>

		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php if($display_tour_agency_opr_currency_note != ''){ echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';} ?><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
		  <tr>
			<td class="main" width="25%" valign="top" style="line-height:20px;">
			<?php 
				echo TEXT_PRODUCTS_PRICE_NET; 
				/*
				if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){
				echo '<br />Additional Fee for Outside:<br />Additional Fee for Balcony:';
				}*/
			?>
			</td>
			<td class="main" width="25%"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') .$display_tour_agency_opr_currency_note. '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'.$price_input_readonly);
			/*
			if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){
			echo '<br />'.tep_draw_separator('pixel_trans.gif', '10', '15').' + '.tep_draw_input_field('products_price_cruise1', $pInfo->products_price_cruise1). '<br />'.tep_draw_separator('pixel_trans.gif', '10', '15').' + '.tep_draw_input_field('products_price_cruise2', $pInfo->products_price_cruise2);
			}
			*/
			?></td>					
			<td rowspan="2" class="smallText" width="50%">
			<?php if($pInfo->is_hotel == 1){ ?>
			<small style="color:#FF0000;"><b>Note:</b> Per room price shows at front page.  Enter double price x 2 here.</small>
			<?php } ?>
			</td>					
		  </tr>
			<tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') .$display_tour_agency_opr_currency_note. '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'.$price_input_readonly); ?></td>
          </tr>
		  <tr>
		  <td class="main">独家特惠</td>
		  <td class="main"><?php echo tep_draw_textarea_field('only_our_free', 'soft', '70', '2',$pInfo->only_our_free); ?></td>
		  </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		<?php  if(!$is_transfer_product){  //一般行程价格设置	  ?>
		  <tr>
            <td class="main"><?php echo '是否显示房间：'; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_room_option', '1', $display_room_yes,'', 'onclick="toggleBox_edit(\'optionyes\',\'optionno\');"') . '&nbsp; Yes &nbsp;' . tep_draw_radio_field('display_room_option', '0', $display_room_no,'', 'onclick="toggleBox_edit(\'optionno\',\'optionyes\');"') . '&nbsp; No'; ?></td>
		</tr>


		<?php
			
   		$maximum_no_of_guest_array[] = array('id' => '1', 'text' => '1');
		$maximum_no_of_guest_array[] = array('id' => '2', 'text' => '2');
		$maximum_no_of_guest_array[] = array('id' => '3', 'text' => '3');
		$maximum_no_of_guest_array[] = array('id' => '4', 'text' => '4');
		if($is_transfer_product){
			$maximum_no_of_guest_array = array();
			for($i=1;$i<=18;$i++) $maximum_no_of_guest_array[] = array('id' => $i, 'text' => $i);
		}
		

		if($pInfo->maximum_no_of_guest!='')
			$selected_guest = $pInfo->maximum_no_of_guest;
		else
			$selected_guest = '4';	
		
		if($access_full_edit == 'true') {
		?>
		 <tr>
			<td class="main"><?php echo TEXT_HEADING_GROSS_PROFIT; ?></td>
			<td class="main">
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_margin', $pInfo->products_margin, 'size="7"'); ?> %<input type="button" id="gross_profit_btn" value="算毛利" /><script type="text/javascript">
			jQuery(document).ready(function(r){
				jQuery('#gross_profit_btn').click(function(){
					var Retail = jQuery('#productsSingle').val();
					var Cost = jQuery('#productsSingleCost').val();
					var Retail_1 = jQuery('#productsSingle1').val();
					var Cost_1 = jQuery('#productsSingle1Cost').val();
					var productsMargin = 0;
					if (parseFloat(Retail) > 0 && parseFloat(Cost) > 0) {
						productsMargin = (((Retail-Cost)/Retail)*100).toFixed(2);
					} else if (parseFloat(Retail_1) > 0 && parseFloat(Cost_1) > 0) {
						productsMargin = (((Retail_1-Cost_1)/Retail_1)*100).toFixed(2);						
					} else {
						alert('您原价和卖价没填写呢！'); 
						return false;
					}
					jQuery('input[name=products_margin]').val(productsMargin);
					if( productsMargin < 1){
						alert('请检查卖和底价，这样公司要亏钱赚啊！');
					}
				});
			});
			</script></td>
		 </tr>
		  <tr class="dataTableRow">
			<td class="dataTableContent" colspan="2">
			  <?php echo $div_first_room_yes_display;?>
			  	 <table width="100%">
				  <tr>
					<td></td>
					<td>
					<table   border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  	<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' ;?></td>
						<td class="main"><?php if($display_tour_agency_opr_currency_note != ''){ echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';} ?>Retail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="main">Cost</td>
					  </tr>
					</table>
					</td>
				  </tr>
				  <!-- 单间 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_SINGLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'id="productsSingle"size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_single_cost', $pInfo->products_single_cost, 'id="productsSingleCost" size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single_cost, document.new_product.products_single);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <!-- 单人配房 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_SINGLE_PU_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_single_pu', $pInfo->products_single_pu, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_single_pu_cost', $pInfo->products_single_pu_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single_pu_cost, document.new_product.products_single_pu);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				   <!-- 双人间 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_DOUBLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_double_cost', $pInfo->products_double_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_double_cost, document.new_product.products_double);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				 <!-- 三人间 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_TRIPLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_triple_cost', $pInfo->products_triple_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_triple_cost, document.new_product.products_triple);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>				  
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <!-- 四人间 -->
				  <tr>
					<td class="main" nowrap><?php echo TEXT_HEADING_QUADRUPLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_quadr', $pInfo->products_quadr, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_quadr_cost', $pInfo->products_quadr_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_quadr_cost, document.new_product.products_quadr);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				<!-- 儿童价格 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note. '&nbsp;' . tep_draw_input_field('products_kids', $pInfo->products_kids, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_kids_cost', $pInfo->products_kids_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_kids_cost, document.new_product.products_kids);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
                 				
				 <!-- 允许的最多客人数 -->
				  <tr>
					<td class="main" width="20%" align="left"><?php echo '每房间最多可住几人： '; ?></td>
            		<td class="main" idth="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_pull_down_menu('maximum_no_of_guest', $maximum_no_of_guest_array,$selected_guest); ?></td>
				  </tr>
				  </table>
		  		</div>
			</td>
		  </tr>
		  <!-- 不显示房间价格  一日内的行程-->
		  <tr class="dataTableRow">
			<td class=dataTableContent colspan="2">
			  <?php echo  $div_first_room_no_display;?>
			  	 <table width="100%">
				   <tr>
					<td></td>
					<td>
					<table   border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  	<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' ;?></td>
						<td class="main"><?php if($display_tour_agency_opr_currency_note != ''){ echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';} ?>Retail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="main">Cost</td>
					  </tr>
					</table>
					</td>
				  </tr>
				  <!--成人价格 -->
				  <tr>
					<td class="main" width="25%" align="left"><?php echo TEXT_HEADING_ADULT_PRICE; ?></td>
					<td class="main" width="75%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') .$display_tour_agency_opr_currency_note. '&nbsp;' . tep_draw_input_field('products_single1', $pInfo->products_single, 'id="productsSingle1" size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_single1_cost', $pInfo->products_single_cost, 'id="productsSingle1Cost" size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single1_cost, document.new_product.products_single1);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td> </tr>
				  <!--小孩价格 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') .$display_tour_agency_opr_currency_note. '&nbsp;' . tep_draw_input_field('products_kids1', $pInfo->products_kids, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_kids1_cost', $pInfo->products_kids_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_kids1_cost, document.new_product.products_kids1);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  </table>
		  </div>
			</td>
		  </tr>
		  <?php  }else{ 
		//not full edit model
		?>
		    <tr class="dataTableRow">
			<td class="dataTableContent" colspan="2">
			  <?php echo $div_first_room_yes_display;?>
			  	 <table width="100%">
				   <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>

				  <tr>
					<td class="main" width="20%" align="left"><?php echo '每房间最多可住几人： '; ?></td>
					<td class="main" idth="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_pull_down_menu('maximum_no_of_guest', $maximum_no_of_guest_array,$selected_guest ); ?></td>
				  </tr>
				<!-- 单人 -->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_SINGLE_OCC_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
				  <?php	/*	if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){  ?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single_cruise1', $pInfo->products_single_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single_cruise2', $pInfo->products_single_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php		}*/?>		  
				<!-- 单人配房 -->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_SINGLE_PU_OCC_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single_pu', $pInfo->products_single_pu, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
				<!-- 二人间 -->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_DOUBLE_OCC_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>				  
				  <?php	/*	if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){ ?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_double_cruise1', $pInfo->products_double_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_double_cruise2', $pInfo->products_double_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php	 }*/?>
				  <!-- 三人间 -->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_TRIPLE_OCC_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
				  <?php	/*	 if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){ ?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_triple_cruise1', $pInfo->products_triple_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_triple_cruise2', $pInfo->products_triple_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php	 }*/?>
				  <!-- 四人间 -->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <tr>
					<td class="main" nowrap><?php echo TEXT_HEADING_QUADRUPLE_OCC_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_quadr', $pInfo->products_quadr, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
				  <?php	/* if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_quadr_cruise1', $pInfo->products_quadr_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_quadr_cruise2', $pInfo->products_quadr_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php		}*/?>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				<!-- 小孩价格 -->
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_OCC_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids', $pInfo->products_kids, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
				  <?php /* if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){ ?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids_cruise1', $pInfo->products_kids_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids_cruise2', $pInfo->products_kids_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php		} */?>
				  </table>
		  </div>
			</td>
		  </tr>
		  <!-- 一日内选项 -->
		  <tr class="dataTableRow">
			<td class=dataTableContent colspan="2">
			  <?php echo  $div_first_room_no_display;?>
			  	 <table width="100%">
				 <!-- 成人价格-->
				   <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>				
				  <tr>
					<td class="main" width="25%" align="left"><?php echo TEXT_HEADING_ADULT_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main" width="75%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_single1', $pInfo->products_single, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
				  <?php /*	if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single1_cruise1', $pInfo->products_single_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single1_cruise2', $pInfo->products_single_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php	} */?>
				<!-- 小孩价格-->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_PRICE; ?> <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_kids1', $pInfo->products_kids, 'size="7"'.$price_input_readonly); ?></td>
				  </tr>
                  <?php	/*if($parent_cat_array[sizeof($parent_cat_array)-1] == CRUISE_CATEGORY_ID){?>
                  <tr>
                  	<td class="main">Additional Fee for Outside: <?php echo $display_tour_agency_opr_currency_note; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids1_cruise1', $pInfo->products_kids_cruise1, 'size="7"'); ?></td>
                  </tr>
                  <tr>
                  	<td class="main">Additional Fee for Balcony: <?php echo $display_tour_agency_opr_currency_note; ?></td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids1_cruise2', $pInfo->products_kids_cruise2, 'size="7"'); ?></td>
                  </tr>
                  <?php	 } */?>				  
				  </table>
		  </div>
			</td>
		  </tr>
		   <?php } //end for full_edit

		}else {	//接送服务价格设置 ?>
			
		<tr class="dataTableRow">
			<td class="dataTableContent" colspan="2">			
			  	 <table width="100%">
				  <tr>
					<td></td>
					<td>
					<table   border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  	<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' ;?></td>
						<td class="main"><?php if($display_tour_agency_opr_currency_note != ''){ echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';} ?>Retail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="main">Cost <?php  echo tep_draw_hidden_field('display_room_option','1');?></td>
					  </tr>
					</table>
					</td>
				  </tr>
				  <!-- 1-3 -->
				  <tr>
					<td class="main">1-3 Person Price</td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_single_cost', $pInfo->products_single_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single_cost, document.new_product.products_single);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <!-- 4-6 -->
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>				  
				  <tr>
					<td class="main">4-6 Person Price</td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_double_cost', $pInfo->products_double_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_double_cost, document.new_product.products_double);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				 <!-- 非标准时间 -->
				  <tr>
					<td class="main">Overtime Service Price
					<div style="color:red">(Price per car at extra time)</div>
					</td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') .$display_tour_agency_opr_currency_note . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'.$price_input_readonly). '&nbsp;' . tep_draw_input_field('products_triple_cost', $pInfo->products_triple_cost, 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_triple_cost, document.new_product.products_triple);"  type=button value="算底价" <?= $price_button?>></td>
				  </tr>				  
                  	
				  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
			</td>
		  </tr>

		<?php }	 ?>
		   <!-- 小孩年龄-->
		  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
		  <tr>
			<td class="main"><?php echo TEXT_PRODUCTS_MAX_CILD_AGE; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('max_allow_child_age', $pInfo->max_allow_child_age, 'size="6"').'years <small style="color:#FF0000;"><b>Note:</b> Leave Black if you want to use Agency Default Maximum allow Child Age: '.$pInfo->default_max_allow_child_age.' years</b>';?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		    <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TRASACTION_FEE; ?></td>
            <td class="main">
			<?php
			$default_transaction_fee_array = array(array('id' => '', 'text' => '--None--'));
			$default_transaction_fee_array[] = array('id' => '1', 'text' => '1');
			$default_transaction_fee_array[] = array('id' => '2', 'text' => '2');
			$default_transaction_fee_array[] = array('id' => '3', 'text' => '3');
			$default_transaction_fee_array[] = array('id' => '4', 'text' => '4');
			$default_transaction_fee_array[] = array('id' => '5', 'text' => '5');
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('transaction_fee', $default_transaction_fee_array, $pInfo->transaction_fee). '%<small style="color:#FF0000;"><b>Note:</b> Leave None Selected if you want to use Agency Default Transation Fee: <b>'.(int)$pInfo->default_transaction_fee.'%</b>';
			?>
			</td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <?php
			for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			?>
			<tr>
            <td class="main" valign="top" nowrap="nowrap"><?php echo TEXT_PRODUCTS_SPECIAL_RPICING_NOTE; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_pricing_special_notes[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_pricing_special_notes[$languages[$i]['id']]) ? $products_pricing_special_notes[$languages[$i]['id']] : str_replace('&nbsp;',' ',tep_get_products_pricing_special_notes($pInfo->products_id, $languages[$i]['id']))),'id=id_products_pricing_special_notes[' . $languages[$i]['id'] . ']'); ?></td>

              </tr>
            </table></td>
          </tr>

			<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			<?php } ?>

		  <tr>
            <td class="main" nowrap="nowrap"><?php echo TEXT_PRODUCTS_DISPLAY_HOTEL_UPGRADE_NOTES; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_hotel_upgrade_notes', '1', $display_hotel_upgrade_notes_in_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('display_hotel_upgrade_notes', '0', $display_hotel_upgrade_notes_out_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
         </tr>
		  <?php //howard added products_surcharge for buy two get one?>
		  <tr>
			<td class="main" width="20%" align="left"><?php echo TEXT_PRODUCTS_BUY_TWO_GET_ONE_SURCHARGE; ?></td>
			<td class="main" idth="80%" align="left">
			<?php
			if($pInfo->products_surcharge!=''){
				$products_surcharge = $pInfo->products_surcharge;
			}else{
				$products_surcharge = '0';
			}
			?>
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_surcharge'); ?>
			</td>
		  </tr>
		  <tr>
			<td class="main" width="20%" align="left"><?php echo TEXT_USE_BUY_TWO_GET_ONE_PRICE; ?></td>
			<td class="main" idth="80%" align="left">
			<?php
			if($pInfo->use_buy_two_get_one_price!=''){
				$use_buy_two_get_one_price = $pInfo->use_buy_two_get_one_price;
			}else{
				$use_buy_two_get_one_price = '0';
			}
			if($use_buy_two_get_one_price=='1'){
				$use_buy_two_get_one_price_check_1 = 'true';
				$use_buy_two_get_one_price_check_2 = '';
			}else{
				$use_buy_two_get_one_price_check_1 = '';
				$use_buy_two_get_one_price_check_2 = 'true';
			}
			//echo $use_buy_two_get_one_price;
			?>
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('use_buy_two_get_one_price', '1', $use_buy_two_get_one_price_check_1 ) . '&nbsp;Yes&nbsp;' . tep_draw_radio_field('use_buy_two_get_one_price', '0', $use_buy_two_get_one_price_check_2) . '&nbsp;No'; ?>
			</td>
		  </tr>
		  <?php //howard added products_surcharge for buy two get one end?>
		  <?php //howard added min_num_guest?>
		  <tr>
			<td class="main" width="20%" align="left"><?php echo TEXT_PRODUCTS_MIN_GUEST; ?></td>
			<td class="main" idth="80%" align="left">
			<?php
			$min_num_guest_array = array();
			for($m_i=1;$m_i<10; $m_i++){
				$min_num_guest_array[] = array('id' => $m_i, 'text' => $m_i);
			}

			if($pInfo->min_num_guest!=''){
				$selected_min_guest = $pInfo->min_num_guest;
			}else{
				$selected_min_guest = '1';
			}
			?>
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('min_num_guest', $min_num_guest_array,$selected_min_guest ); ?>
			</td>
		  </tr>
		  <?php //howard added min_num_guest end?>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
		  <td colspan="2" align="center" class="main">
		<?php
		if($products_detail_permissions['RoomAndPrice']['能看不能编'] != true){
		  	echo tep_image_button('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		}else{
		?>
		<button disabled="disabled"> 能看不能编 </button>
		<?php
		}
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_room_and_price">
		  <input type="hidden" name="qaanscall" value="true">
		  <label><input type="checkbox" name="notice_customer_service" value="1" /> 通知商务中心</label>
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>



		  </table>
		<?php if($products_detail_permissions['RoomAndPrice']['能看不能编'] != true){?>
		</form>
		<?php }?>
		 </td></tr>
		 </table>
		 <?php
		//end of tour room and price }
    break;

	case 'tour_operation':
		//start of tour operation {
		$operation_price_readonly = '';
		if($products_detail_permissions['Operation']['不能输入价格'] == true){
			$operation_price_readonly = ' readonly="readonly" ';
		}

		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}



		 if($display_room_yes) { ?>
			<div id="optionyes" style="visibility: visible; display: inline;"></div>
		   <div id="optionno" style="visibility: hidden; display: none;"></div>
		 <?php }elseif($display_room_no) { ?>
			<div id="optionyes" style=" visibility: hidden; display: none;"></div>
		   <div id="optionno" style="visibility: visible; display: inline;"></div>
		<?php
		}

		//amit added to check operatedate start
		  $months_operate_array = array();
		  for ($i=1; $i<13; $i++) {
			$months_operate_array[] = array('id' => sprintf("%02d", $i),
									'text' => strftime('%b', mktime(0,0,0,$i,15)));
		  }

		  $days_operate_array = array();

					  for ($i=1; $i<32; $i++) {
						$days_operate_array[] = array('id' => sprintf("%02d", $i),
									'text' => $i);
					  }
			// howard update the code for Year
		  //$operate_years_array = array(array('id' => '', 'text' => ''));
		  $operate_years_array = array();
		  $start_year = date('Y')-1;
		  $end_year = date('Y',strtotime('+3 years'));
		  for($y_num = $start_year; $y_num < $end_year; $y_num++){
			  $operate_years_array[] = array('id' => $y_num, 'text' => $y_num);
		  }
		  //$operate_years_array[] = array('id' => '2007', 'text' => '2007');
		  //$operate_years_array[] = array('id' => '2008', 'text' => '2008');
		  //$operate_years_array[] = array('id' => '2009', 'text' => '2009');
		  //$operate_years_array[] = array('id' => '2010', 'text' => '2010');
		 //amit added to check operatedate end

		$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
		$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and (c.is_attractions='0' or c.is_attractions='2') order by c.city");
		while ($city_class = tep_db_fetch_array($city_class_query)) {
		  $city_class_array[] = array('id' => $city_class['city_id'],
									 'text' => $city_class['city'].', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
		}

		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_OPERATION; ?></b></td></tr>
		 <tr><td class="main" colspan="2">

		 <form name="new_product"  id="new_product">
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <?php if($access_full_edit == 'true') { ?>
		 <tr>
			<td class="main" colspan="2"><?php echo TEXT_HEADING_GROSS_PROFIT.'&nbsp;'; ?>

			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_margin', $pInfo->products_margin, 'size="7"'); ?> %</td>
		 </tr>
		 <tr>
		<td class="errorText" colspan="2">
		<?php echo TEXT_NOTICE_CP_AND_RP;?>
		</td>
		</tr>
		<?php } ?>
		 <?php
			$no_of_sec=regu_irregular_section_numrow($pInfo->products_id);
		 ?>
		  <tr >
			<td class="main" colspan="2"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
		</tr>
		<tr id="on_change_show">
		  <td align="left" valign="top" class="main"># of regular/irregular sections</td>
			<td nowrap width="980"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15');?>
			  <div id="reg_irrg_id">
			  <input type="text" name="numberofsection" <?= $operation_price_readonly;?> value="<?php echo $no_of_sec;?>">
			  <input type="button" name="go" value="Go" <?php echo ($operation_price_readonly!='' ? 'disabled' : '') ?> onClick="getInfoRegIrreg('createreg_illregulartour.php?product_id=<?=$pInfo->products_id;?>&numberofsection='+document.new_product.numberofsection.value,'-1');">

			  </div>
			  <div id="div_id_reg_irreg_input">
				<?php
					$_GET['numberofsection']=$no_of_sec;
					$_GET['product_id']=$pInfo->products_id;
					include("createreg_illregulartour_edit.php");
				?>
				  </div>

			  </td>
		  </tr>
		   <tr>
		   <td colspan="2"> <?php echo tep_draw_hidden_field('regions_id', $pInfo->regions_id); ?>
		   <div id="country_state_start_city_id">

            
		<?php
		if($pInfo->products_id != "")
						{
						$sel2values = "";
						$countingcity = 0;
							//$destination_query = tep_db_query("select * from ".TABLE_PRODUCTS_DESTINATION." where products_id = ".$pInfo->products_id." order by city_id");
							//while ($destination_result = tep_db_fetch_array($destination_query))
							//{
							$ajax_pass_products_id = $pInfo->products_id;
								$city_edit_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co, ".TABLE_PRODUCTS_DESTINATION." as pde where pde.products_id = ".$pInfo->products_id." and pde.city_id = c.city_id and s.zone_country_id = co.countries_id and c.state_id = s.zone_id and c.city !='' and (c.is_attractions='1' or c.is_attractions='2') order by c.city");
								while($city_edit_result = tep_db_fetch_array($city_edit_query)){
									$sel2values .= "<option value=".$city_edit_result['city_id'].">".$city_edit_result['city'].', '.$city_edit_result['zone_code'].', '.$city_edit_result['countries_iso_code_3']."</option>";

										if($countingcity == 0){
											$allready_product_city = $city_edit_result['city_id'];
										}else{
											$allready_product_city .= ",".$city_edit_result['city_id'];
										}
										$countingcity++;
								}

							//}
							$tempsolution="";
						if($allready_product_city == '') $allready_product_city=0;

						$city_new_query = tep_db_query("select ttc.city_id, ttc.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where s.zone_country_id = co.countries_id and ttc.state_id = s.zone_id and  ttc.regions_id = regi.regions_id and regi.regions_id = ".$pInfo->regions_id." and ttc.city_id not in (".$allready_product_city.") and ttc.city!='' and (ttc.is_attractions='1' or ttc.is_attractions='2') order by ttc.city");
						 while ($city_new_result = tep_db_fetch_array($city_new_query))
						{
						 $tempsolution .=  "<option value=".$city_new_result['city_id'].">".$city_new_result['city'].', '.$city_new_result['zone_code'].', '.$city_new_result['countries_iso_code_3']."</option>";
						}

						}

					?>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

			<tr>
			<td class="main" width="13%"><?php echo TEXT_HEADING_CITIES_BY_COUNTRY; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '21', '10'); ?>
			<?php
			/*$ajax_exist_departure_ids = str_replace(',','|',$pInfo->departure_city_id);
			$ajax_exist_departure_end_cities = str_replace(',','|',$pInfo->departure_end_city_id);*/

			echo tep_draw_pull_down_menu('countries_search_id', tep_get_countries('select country'), '', 'onChange="change_region_state_list_edit(this.value,'.$_GET['pID'].','.$pInfo->regions_id.');"');
			?>
			</td>
		   </tr>

			<tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<?php
			if($pInfo->departure_city_id == ''){
			$pInfo->departure_city_id = 0;
			}
				$city_start_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_start_class_left = tep_db_fetch_array($city_start_class_query_left)) {
				  $city_start_class_array_left[] = array('id' => $city_start_class_left['city_id'],
											 'text' => $city_start_class_left['city'].', '.$city_start_class_left['zone_code'].', '. $city_start_class_left['countries_iso_code_3']);
				}

				$city_start_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_start_class_right = tep_db_fetch_array($city_start_class_query_right)) {
				  $city_start_class_array_right[] = array('id' => $city_start_class_right ['city_id'],
											 'text' => $city_start_class_right ['city'].', '.$city_start_class_right['zone_code'].', '. $city_start_class_right['countries_iso_code_3']);
				}
			?>
			<tr>
			<td class="main" nowrap ><?php echo TEXT_PRODUCTS_DURATION_SELECT_CITY; ?></td>
			<td class="main">
				<table border="0">
				  <tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_city_id_temp', $city_start_class_array_left, '',' id="departure_city_id_temp" multiple="multiple" size="10"'); ?></td>
					<td><INPUT onClick="moveOptions(this.form.departure_city_id_temp, this.form.departure_city_id_temp1);" type=button value="-->">
					<BR><INPUT onClick="moveOptions(this.form.departure_city_id_temp1, this.form.departure_city_id_temp);" type=button value="<--"></td>
					<td>
					<?php echo  tep_draw_pull_down_menu('departure_city_id_temp1', $city_start_class_array_right, '',' id="departure_city_id_temp1" multiple="multiple" size="10"'); ?>
					<input type="hidden" name="departure_city_id" value="">
					</td>
				  </tr>
				</table>
				<?php //echo tep_draw_separator('pixel_trans.gif', '21', '15') . '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_class_array, $pInfo->departure_city_id); ?>
			</td>
			</tr>
			<tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>

		   <tr>
			<td class="main"><?php echo TEXT_PRODUCTS_DEPARTURE_END_CITY; ?></td>
			<td class="main">
			<?php
			if($pInfo->departure_end_city_id == ''){
			$pInfo->departure_end_city_id = 0;
			}
				$city_end_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_end_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_class_left = tep_db_fetch_array($city_end_class_query_left)) {
				  $city_end_class_array_left[] = array('id' => $city_class_left['city_id'],
											 'text' => $city_class_left['city'].', '.$city_class_left['zone_code'].', '. $city_class_left['countries_iso_code_3']);
				}

				$city_end_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_end_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_class_right = tep_db_fetch_array($city_end_class_query_right)) {
				  $city_end_class_array_right[] = array('id' => $city_class_right ['city_id'],
											 'text' => $city_class_right ['city'].', '.$city_class_right['zone_code'].', '. $city_class_right['countries_iso_code_3']);
				}
			?>
			<table border="0">
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_end_city_id_temp', $city_end_class_array_left, '',' id="departure_end_city_id_temp" multiple="multiple" size="10"'); ?></td>
				<td><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp, this.form.departure_end_city_id_temp1);" type=button value="-->">
				<BR><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp1, this.form.departure_end_city_id_temp);" type=button value="<--"></td>
				<td>
				<?php echo  tep_draw_pull_down_menu('departure_end_city_id_temp1', $city_end_class_array_right, '',' id="departure_end_city_id_temp1" multiple="multiple" size="10"'); ?>
				<input type="hidden" name="departure_end_city_id" value="">
				</td>
			  </tr>
			</table>
			</td>
		  </tr>
		 <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		  <tr>
			<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESTINATION; ?></td>
			<td class="main">
			<table border="0">
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '20', '12');
					?>
						<select name="sel1" id="sel1" size="10" multiple="multiple">
						<?php
						echo $tempsolution;
						?>
						</select>
					</td>
					<td align="center" valign="middle">
						<input type="button" value="--&gt;"
						 onclick="moveOptions(this.form.sel1, this.form.sel2);" /><br />
						<input type="button" value="&lt;--"
						 onclick="moveOptions(this.form.sel2, this.form.sel1);" />
					</td>
					<td>
						<select name="sel2" size="10" multiple="multiple">
						<?php
						if($pInfo->products_id != "")
						{
							echo $sel2values;
						}
						?>
						</select>
						<input type="hidden" name="selectedcityid" value="">
					</td>
				</tr>

			</table>
			</td>
		  </tr>
		  </table>
		   </div>

		   </td>
		   </tr>
		  <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		<?php
				$arr_times_type = array();
				$arr_times_type[] = array('id' => 0,'text' => 'days');
				$arr_times_type[] = array('id' => 1,'text' => 'hours');
				$arr_times_type[] = array('id' => 2,'text' => 'minutes');
		$tr_display = '';
		if($products_detail_permissions['Operation']['只能更新发出结束途经城市景点']== true){
			$tr_display = ' style="display:none" ';
		}
		?>
		  <tr <?= $tr_display?>>
			<td class="main"><?php echo TEXT_PRODUCTS_DURATION; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_durations', $pInfo->products_durations, 'size="2" '); //onChange="change_itenerary_hotel('.$pInfo->products_id.');" ?>
			<?php echo tep_draw_pull_down_menu('products_durations_type',$arr_times_type,$pInfo->products_durations_type); //,'onchange="change_itenerary_hotel('.$pInfo->products_id.');"'?>
			</td>
		  </tr>
		  <tr <?= $tr_display?>>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		 <tr <?= $tr_display?>>
			 <td class="main" nowrap><?php echo TEXT_PRODUCTS_DISPLAY_PICKUP_HOTELS; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
			<?php echo tep_draw_checkbox_field('display_pickup_hotels', '1', $pInfo->display_pickup_hotels); ?></td>
		  </tr>
		 <tr <?= $tr_display?>>
			<td colspan="2" ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		 <?php
         //上车地点和时间start {
		?>
         <tr <?= $tr_display?>>
			<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DEPARTURE_PLACE; ?></td>
			<td class="main">
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">

						  <tr>
							<td colspan="6"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr>
							<td colspan="6">
							 <table border="0" cellpadding="0" cellspacing="0" width="100%">
								  <tr class="dataTableHeadingRow">
									<td class="dataTableHeadingContent" width="58" align="left">Region</td>
									<td class="dataTableHeadingContent" width="100" align="left">Departure Time</td>
									<td class="dataTableHeadingContent" width="60" align="center">Location</td>
									<td class="dataTableHeadingContent" width="80" align="center">Address</td>
									<td class="dataTableHeadingContent" width="80" align="center">Map Path</td>
									<td class="dataTableHeadingContent" width="70" align="center">Tips</td>
								  </tr>
								 </table>
							</td>
						  </tr>
						  <tr>
							<td colspan="6"><?php echo tep_black_line(); ?></td>
						  </tr>

						
                        <tr class="dataTableRow">
						<td class=dataTableContent colspan="6">
						  <div id="div_id_departure"><?php include('categories_ajax_sections_edit_departure_data.php'); //echo $edit_departure_data; ?></div>
						  </td>
						</tr>

						 <tr>
							<td colspan="6"><?php echo tep_black_line(); ?></td>
						  </tr>

						  <tr class="dataTableRow">
							<td class="dataTableContent" width="110" valign=top> <div id="div_id_region_select"></div>&nbsp;
									<?php
									$options_region = array();
									$regionquery = 'select DISTINCT region from '.TABLE_TOUR_PROVIDER_REGIONS.' where FIND_IN_SET("'.$pInfo->agency_id.'", agency_ids) order by region';
									$regionrow = tep_db_query($regionquery);
									$options_region[]= array('id'=>'', 'text'=>'');
									while($products_departure_result = tep_db_fetch_array($regionrow)){
										$options_region[]= array('id'=>$products_departure_result['region'], 'text'=>$products_departure_result['region']);
									}
									echo tep_draw_pull_down_menu('region', $options_region);
									?>
							</td>
							<td class="dataTableContent" width="100" valign="top"><input type="hidden" name="numberofdaparture" value="<?php echo $k; ?>">&nbsp;<?php echo tep_draw_input_num_en_field('depart_time', '', 'size="12"'); ?><br>(HH:MMam <br> e.g:- 09:00am)</td>
							<td class="dataTableContent" width="60" valign="top"><?php echo tep_draw_input_field('departure_address', '', 'size="20"'); ?></td>
							<td class="dataTableContent" width="80" valign="top"><?php echo tep_draw_input_field('departure_full_address', '', 'size="30"'); ?><br><?php echo db_to_html('附近的酒店')?><?php echo tep_draw_input_field('products_hotels_ids', '', 'size="20" style="ime-mode: disabled;"'); ?>
							<br>
							<span style="color:#999999"><?php echo db_to_html('提示：可输入接送地酒店id，多个id用英文","号隔开如：45,713')?></span>
							</td>
							<td class="dataTableContent" width="80" valign="top"><?php echo tep_draw_input_field('departure_map_path', '', 'size="30"'); ?></td>
							<td class="dataTableContent" width="70" valign="top">
							<?php echo tep_draw_textarea_field('departure_tips', 'virtual', '20','3'); ?>
							<br>
							<?php echo tep_image_submit('button_insert.gif', '', 'onClick="return add_departure()"'); ?>

							</td>
						   </tr>
						<tr>
						<td colspan="6"><?php echo tep_black_line(); ?></td>
					   </tr>
					 </table>
			</td>
		 </tr>
         <?php /*
		 <tr>
         <td>&nbsp;</td>
         <td><a href="<?php echo tep_href_link('fast_enter_departure_time_and_location.php','cPath='.$cPath.'&pID='.$pID)?>">Click here fast enter Departure Time and Location</a></td>
         </tr>
		 */ ?>
         <?php
         //上车地点和时间end }
		 ?>

		<tr <?= $tr_display?>>
			<td colspan="2" ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		</tr>
		<tr <?= $tr_display?>>
			<td class="main" valign="top"><?php echo TEXT_SOLDOUT_DATES; ?></td>
			<td class="main">
				<div id="mainSoldOutDiv" style="width:90%;">
				<?php
					$qry_sold_dates="SELECT * FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." sd WHERE sd.products_id='".$pInfo->products_id."'";
					$res_sold_dates=tep_db_query($qry_sold_dates);
					$total_sold_dates_num=tep_db_num_rows($res_sold_dates);
					$total_sold_dates=$total_sold_dates_num;
					if($total_sold_dates_num <= 0){
						$total_sold_dates=1;
					}
					echo tep_draw_hidden_field("mainSoldOutValue", $total_sold_dates, 'id="mainSoldOutValue"');

					if($total_sold_dates_num > 0){
						while($row_sold_dates=tep_db_fetch_array($res_sold_dates)){
						$count++;
					?>
							<div id="div_products_soldout_date_<?php echo $count; ?>" style="float:left; ">
								<table width="110px" border="0" cellspacing="3" cellpadding="0">
									<tr>
										<td align="left">
											<nobr><?php echo tep_draw_input_field('products_soldout_date_'.$count, $row_sold_dates['products_soldout_date'], 'size="10" id="products_soldout_date_'.$count.'"');?><a href="javascript:NewCal('products_soldout_date_<?php echo $count;?>');" id="products_soldout_dt_lnk_<?php echo $count;?>"><img src='<?php echo DIR_WS_IMAGES;?>cal.gif' width='16' height='16' border='0' alt='Pick a date'></a>&nbsp;<a href="javascript:remove_date('<?php echo $count;?>');" id="products_soldout_delete_lnk_<?php echo $count;?>"><?php echo tep_image(DIR_WS_IMAGES.'no.gif', TEXT_PRODUCTS_IMAGE_REMOVE_SHORT, '16', '16');?></a></nobr>
										</td>
									</tr>
								</table>
							</div>
			<?php	}
					}else{
						$count=1;
					?>
							<div id="div_products_soldout_date_<?php echo $count; ?>" style="float:left; ">
								<table width="110px" border="0" cellspacing="3" cellpadding="0">
									<tr>
										<td align="left">
											<nobr><?php echo tep_draw_input_field('products_soldout_date_'.$count, '', 'size="10" id="products_soldout_date_'.$count.'"');?><a href="javascript:NewCal('products_soldout_date_<?php echo $count;?>');"><img src='<?php echo DIR_WS_IMAGES;?>cal.gif' width='16' height='16' border='0' alt='Pick a date'></a></nobr>
										</td>
									</tr>
								</table>
							</div>
			<?php }?>
				</div>
				<div style="clear:both;"><a href="javascript:;" onClick="addSoldoutDates();"><b><font color="#000099">Add New</font></b></a>
				</div>
			</td>
		 </tr>
         <tr <?= $tr_display?>>
            <td class="main" valign="top"><?php echo TEXT_REMAINING_SEATS; ?></td>
               <td>
                  <table border="1" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
		            <tr>
                       <td rowspan="4"><?php echo TEXT_REMAINING_SEATS; ?></td>
		                  <td>
                             <div id="mainRemainingSeatsDiv">
		                     <?php
		                       $qry_remaining_seats = "SELECT * FROM  products_remaining_seats as prs WHERE prs.products_id = '".$pInfo->products_id."' ORDER BY `departure_date` ASC";
		                       $res_remaining_seats = tep_db_query($qry_remaining_seats);
		                       $total_remaining_seats_num = tep_db_num_rows($res_remaining_seats);
		                       $total_remaining_seats = $total_remaining_seats_num;
		                       if($total_remaining_seats_num <= 0){
						           $total_remaining_seats = 1;
					           }
					           echo tep_draw_hidden_field("mainRemainingSeatsValue", $total_remaining_seats, 'id="mainRemainingSeatsValue"');

					           if($total_remaining_seats_num > 0){
					                while($row_remaining_seats=tep_db_fetch_array($res_remaining_seats)){

						            $counts++;


		                     ?>
		                       <div id="div_products_remaining_seats_<?php echo $counts; ?>">
		                       <table border="1" cellpadding="1" cellspacing="1">
                               <tr>
		                          <td width="210" valign="top" nowrap="nowrap" ><p>出团日期&nbsp;<?php echo $counts; ?><label><?php echo tep_draw_input_field('products_remaining_seats_'.$counts,$row_remaining_seats['departure_date'], 'size="10" id="products_remaining_seats_'.$counts.'"');?><a href="javascript:NewCal('products_remaining_seats_<?php echo $counts;?>');" id="products_remaining_seats_lnk_<?php echo $counts;?>"><img src='<?php echo DIR_WS_IMAGES;?>cal.gif' width='16' height='16' border='0' alt='Pick a date'></a></label></p></td>
                                  <td width="210" valign="top" ><p>余座位<?php echo tep_draw_input_field('products_remaining_seats_num_'.$counts,$row_remaining_seats['remaining_seats_num'], 'size="1" style="ime-mode:disabled" id="products_remaining_seats_num_'.$counts.'"');?>个<a href="javascript:remove_departure_date('<?php echo $counts;?>');" id="products_remaining_seats_num_delete_lnk_<?php echo $counts;?>"><?php echo tep_image(DIR_WS_IMAGES.'no.gif', TEXT_PRODUCTS_IMAGE_REMOVE_SHORT, '16', '16');?></a></p></td>
		                       </tr>
		                       </table>
		                       </div>
					               <?php }
					           }else{
					           	  $counts = 1;?>
					           	  <div id="div_products_remaining_seats_<?php echo $counts; ?>">
					           	  <table border="1" cellpadding="1" cellspacing="1">
					           	  <tr>
		                            <td width="210" valign="top" nowrap="nowrap" ><p>出团日期&nbsp;<?php echo $counts; ?><label><?php echo tep_draw_input_field('products_remaining_seats_'.$counts,'', 'size="10" id="products_remaining_seats_'.$counts.'"');?><a href="javascript:NewCal('products_remaining_seats_<?php echo $counts;?>');"><img src='<?php echo DIR_WS_IMAGES;?>cal.gif' width='16' height='16' border='0' alt='Pick a date'></a></label></p></td>
                                    <td width="210" valign="top" nowrap="nowrap" ><p>余座位<?php echo tep_draw_input_field('products_remaining_seats_num_'.$counts,'', 'size="1" style="ime-mode:disabled" id="products_remaining_seats_num_'.$counts.'"');?>个</p></td>
		                          </tr>
		                          </table>
		                       </div>


					          <?php }?>

		                      </div>
		                 </td>
		            </tr>
		                       <tr>
		                          <td><a href="javascript:;" onClick="addDepartureDates();"><b><font color="#000099">Add New</font></b></a></td>
		                       </tr>
                   </table>
               </td>
          </tr>

		 <tr <?= $tr_display?>>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>



		  <tr>
		  <td colspan="2" align="center" class="main">
		  <?php
		 echo tep_image_button('button_update.gif', IMAGE_UPDATE, ' onclick="SelectAll_ajax(document.getElementById(\'new_product\').elements[\'sel2\'],departure_end_city_id_temp1,departure_city_id_temp1); sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		 echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		 echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		 if($_GET['searchkey'] != '' || $_GET['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }
		  echo tep_draw_hidden_field('display_room_option',$pInfo->display_room_option);
		  ?>
		  <input type="hidden" name="req_section" value="tour_operation">
		  <input type="hidden" name="qaanscall" value="true">
			<label><input type="checkbox" name="notice_customer_service" value="1" /> 通知商务中心</label>
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour operation }
    break;
	case 'tour_content':
		//start of tour content
		if (!isset($pInfo->display_itinerary_notes)) $pInfo->display_itinerary_notes = '1';
		  switch ($pInfo->display_itinerary_notes) {
		  case '0': $display_itinerary_notes_in_status = false; $display_itinerary_notes_out_status = true; break;
		  case '1':
		  default: $display_itinerary_notes_in_status = true; $display_itinerary_notes_out_status = false;
		}

		$display_tour_type_icon_specil = false;
		$display_tour_type_icon_specil_2p = false;
		$display_tour_type_icon_buy2get1free_tour = false;
		$display_tour_type_icon_buy2get2free_tour = false;

		if (preg_match("/\bspecil\-jia\b/i", $pInfo->tour_type_icon)) {
			$display_tour_type_icon_specil = true;
		}
		if (preg_match("/\b2\-pepole\-spe\b/i", $pInfo->tour_type_icon)) {
			$display_tour_type_icon_specil_2p = true;
		}
		if (preg_match("/\bbuy2\-get\-1\b/i", $pInfo->tour_type_icon)) {
			$display_tour_type_icon_buy2get1free_tour = true;
		}
		if (preg_match("/\bbuy2\-get\-2\b/i", $pInfo->tour_type_icon)) {
			$display_tour_type_icon_buy2get2free_tour = true;
		}
		
		$products_travel = array();
		$sql = tep_db_query('SELECT * FROM `products_travel` WHERE products_id="'.$products_id.'"');
		while($row = tep_db_fetch_array($sql)){
			$products_travel[$row['langid']][$row['travel_index']]['name']=$row['travel_name'];
			$products_travel[$row['langid']][$row['travel_index']]['img']=$row['travel_img'];
			$products_travel[$row['langid']][$row['travel_index']]['imgalt']=$row['travel_imgalt'];
			$products_travel[$row['langid']][$row['travel_index']]['content']=$row['travel_content'];
			$products_travel[$row['langid']][$row['travel_index']]['hotel']=$row['travel_hotel'];
		}
		?>
		<script type="text/javascript">
		jQuery(function(){
			var config = {
				toolbar:'Basic',
				width : 600
			};
			/* jQuery('.jquery_ckeditor').ckeditor(config);*/
		});
		</script>
		<table width="100%" border="0" cellpadding="2" cellspacing="0">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_CONTENT; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
         <?php echo tep_draw_form('new_product', 'categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : ''), 'post', 'enctype="multipart/form-data" '); ?>
		 <?php
		 	$content_main_table_style = '';
		 	if($products_detail_permissions['Content']['只能编辑_显示的类型标签'] == true){
		 		$content_main_table_style = ' style="display:none" ';
			}
		 ?>		 
		 <table id="content_main_table" <?= $content_main_table_style?> width="100%" border="0" cellspacing="0" cellpadding="2">
			<?php
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			?>
			<tr>
            <td width="21%" valign="top" nowrap class="main"><?php echo TEXT_PRODUCTS_SMALL_DESCRIPTION; ?></td>
            <td width="79%"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
                <td class="main">
				<?php echo tep_draw_textarea_field('products_small_description[' . $languages[$i]['id'] . ']', 'soft', '80', '4', (isset($products_small_description[$languages[$i]['id']]) ? $products_small_description[$languages[$i]['id']] : tep_get_products_small_description($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor"'); ?>
				</td>
				</tr>
				<tr>
				<td class="main"></td>
				<td class="main"><?php echo '<a target="_blank" href="' . HTTP_CATALOG_SERVER.'/tours-search/keywords/'.tep_get_products_model($_GET['pID']) . '"><b>Click here to Preview Highlights</b></a>'; ?></td>
				</tr>
            </table></td>
          	</tr>
		    <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			<?php
				}
			?>
			<tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DISPLAY_ITINERARY_NOTES; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_itinerary_notes', '1', $display_itinerary_notes_in_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('display_itinerary_notes', '0', $display_itinerary_notes_out_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
         	</tr>
		  	<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          	</tr>
			<?php
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			?>
			  <tr>
				<td class="main" valign="top">Old <?php echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '80', '30', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor"'); ?></td>

				  </tr>
				</table></td>
			  </tr>
              <tr>
				<td class="main" valign="top">Itinerary Tips;</td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('travel_tips[' . $languages[$i]['id'] . ']', 'soft', '80', '5', (isset($travel_tips[$languages[$i]['id']]) ? $travel_tips[$languages[$i]['id']] : tep_get_products_travel_tips($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor"'); ?></td>

				  </tr>
				</table></td>
			  </tr>
              <tr>
              <td class="main">
              New Itinerary:<br>(Prior to old format)
              </td>
              <td class="main">
              <table width="100%" border="0" cellpadding="3" cellspacing="1">
                                     <tr>
                                        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
                                        <td class="main"><b>Name</b></td>
                                        <td class="main"><b>Images</b>(不上传则不替换)</td>
                                        <td class="main"><b>Content</b></td>
                                        <td class="main"><b>Hotel</b><br />(格式:酒店名称<b style='color:red'>|</b>酒店星级)[一行一个]</td>
                                      </tr>

                                <?php
								$ItineraryNum = $pInfo->products_durations;
								$pInfo->products_durations_type && $ItineraryNum = 1;
                                   for($j=1; $j<=$ItineraryNum; $j++){
								?>
                                  <tr class="main">
	    <td class="main" width="6%" valign="top">Day <strong><?php echo $j;?></strong></td>
	    <td width="20%" valign="top">
        <?php echo tep_draw_input_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][name]', (isset($products_travel[$languages[$i]['id']][$j]['name']) ? $products_travel[$languages[$i]['id']][$j]['name'] : ''), 'size="25" '); ?>
    </td>
    <td width="16%" nowrap="nowrap" valign="top">
    	alt:<?php echo tep_draw_input_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][imgalt]', (isset($products_travel[$languages[$i]['id']][$j]['imgalt']) ? $products_travel[$languages[$i]['id']][$j]['imgalt'] : ''), 'size="20"'); ?><br />
       上传:<input name="products_travel_img_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>" type="file" id="products_travel_img_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>" size="15"><br />
       URL:<input id="url_products_travel_img_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>" title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,'thumb','<?= $city_ids?>','products_travel_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>_oldimg_preview');" name="products_travel[<?php echo $languages[$i]['id'];?>][<?php echo $j;?>][oldimg]" type="text" value="<?php echo $products_travel[$languages[$i]['id']][$j]['img'];?>"><br />
       <?php
       if($products_travel[$languages[$i]['id']][$j]['img']!=''){
		   if(stripos($products_travel[$languages[$i]['id']][$j]['img'], 'http://') === false && stripos($products_travel[$languages[$i]['id']][$j]['img'], '/temp') === false){
				$products_travel[$languages[$i]['id']][$j]['img']=DIR_WS_CATALOG_IMAGES.$products_travel[$languages[$i]['id']][$j]['img'];
		   }
		   echo '<br><a href="'.$products_travel[$languages[$i]['id']][$j]['img'].'" target="_blank">查看原图</a>';
	   }
	   ?>
	   <img src="<?= $products_travel[$languages[$i]['id']][$j]['img']?>" id="products_travel_<?php echo $languages[$i]['id'];?>_<?php echo $j;?>_oldimg_preview" width="100">
		</td>
    	<td width="30%" valign="top">
      <?php echo tep_draw_textarea_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][content]', 'soft', '35', '3', (isset($products_travel[$languages[$i]['id']][$j]['content']) ? $products_travel[$languages[$i]['id']][$j]['content'] : '')); ?>
	  <?php //复制行程内容到其他团 start {?>
	  <button type="button" onclick="jQuery('#tmp_div_<?= $j;?>').toggle();">复制当天行程到其他团</button>
	  <div id="tmp_div_<?= $j;?>" style="display:none" >产品ID：<input id="tmp_input_<?= $j;?>" type="text" size="35" />
	  <br />(英文逗号隔开产品id，并且不可有空格。如：14,789,3054)
	  <br />范围：
	  <label><input range="tmp_range" type="checkbox" value="all" onclick="jQuery(&quot;#tmp_div_<?= $j;?> input[range='tmp_range']&quot;).attr('checked', this.checked )" /> 全部</label>
	  <label><input range="tmp_range" type="checkbox" value="name" /> Name</label>
	  <label><input range="tmp_range" type="checkbox" value="images" /> Images</label>
	  <label><input range="tmp_range" type="checkbox" value="content" /> Content</label>
	  <label><input range="tmp_range" type="checkbox" value="hotel" /> Hotel</label>
	  
	  <br /><button type="button" onclick="copy_travel(this,<?= (int)$pInfo->products_id?>, jQuery('#tmp_input_<?= $j;?>').val(), <?= $j?>, jQuery(&quot;#tmp_div_<?= $j;?> input[range='tmp_range']&quot;))">开始复制</button>
	  </div>
	  <?php //复制行程内容到其他团 end }?>
	  
		</td>
    	<td width="28%" valign="top">
      <?php echo tep_draw_textarea_field('products_travel[' . $languages[$i]['id'] . ']['.$j.'][hotel]', 'soft', '30', '3', (isset($products_travel[$languages[$i]['id']][$j]['hotel']) ? $products_travel[$languages[$i]['id']][$j]['hotel'] : '')); ?>
		</td>
  	</tr>
                                <?php
								  }
								?></table>
              </td>
              </tr>
			  <tr>
				<td class="main" valign="top"><?php echo db_to_html('行程注意事项:'); ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_notes[' . $languages[$i]['id'] . ']', 'soft', '80', '8', (isset($products_notes[$languages[$i]['id']]) ? $products_notes[$languages[$i]['id']] : tep_get_products_notes($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor"'); ?></td>

				  </tr>
				</table></td>
			  </tr>
				<tr>
				<td class="main" valign="top"><?php echo TEXT_PRODUCTS_OTHER_DESCRIPTION; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_other_description[' . $languages[$i]['id'] . ']', 'soft', '80', '8', (isset($products_other_description[$languages[$i]['id']]) ? $products_other_description[$languages[$i]['id']] : tep_get_products_other_description($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor" id=id_products_other_description[' . $languages[$i]['id'] . ']'); ?></td>

				  </tr>
				</table></td>
			  </tr>

			   <tr>
				<td class="main" valign="top" nowrap="nowrap"><?php echo TEXT_PRODUCTS_PACKAGE_EXCLUDES; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_package_excludes[' . $languages[$i]['id'] . ']', 'soft', '80', '8', (isset($products_package_excludes[$languages[$i]['id']]) ? $products_package_excludes[$languages[$i]['id']] : tep_get_products_package_excludes($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor" id=id_products_package_excludes[' . $languages[$i]['id'] . ']'); ?></td>

				  </tr>
				</table></td>
			  </tr>

			   <tr>
				<td class="main" valign="top"><?php echo TEXT_PRODUCTS_PACKAGE_SPECIAL_NOTES; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_package_special_notes[' . $languages[$i]['id'] . ']', 'soft', '80', '20', (isset($products_package_special_notes[$languages[$i]['id']]) ? $products_package_special_notes[$languages[$i]['id']] : tep_get_products_package_special_notes($pInfo->products_id, $languages[$i]['id'])),'class="jquery_ckeditor" id=id_products_package_special_notes[' . $languages[$i]['id'] . ']'); ?></td>

				  </tr>
				</table></td>
			  </tr>

		<?php } ?>
			</table>
			<table id="content_type_icon_table" width="100%" cellspacing="0" cellpadding="2" border="0">
			<tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TOUR_TYPE_ICON; ?></td>
            <td class="main" valign="top">
				<table cellpadding="0" cellspacing="0">
				<tr>
				<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?></td>
				<td class="main">
				<?php
				
				if($products_detail_permissions['Content']['不能编辑_显示的类型标签'] != true){
					$box_disabled = '';
					$box_onclick = '';
				}else{
					$box_disabled = ' disabled="disabled" ';
					$box_onclick = ' onclick="this.checked=true;" ';
				}
					echo tep_draw_checkbox_field('tour_type_icon[0]', 'specil-jia', $display_tour_type_icon_specil, '', ($display_tour_type_icon_specil==true ? $box_onclick : $box_disabled) ) . '特价 &nbsp; ';
					echo tep_draw_checkbox_field('tour_type_icon[1]', '2-pepole-spe', $display_tour_type_icon_specil_2p, '', ($display_tour_type_icon_specil_2p==true ? $box_onclick : $box_disabled)) . '双人特价 &nbsp;';
					echo tep_draw_checkbox_field('tour_type_icon[2]', 'buy2-get-1', $display_tour_type_icon_buy2get1free_tour, '', ($display_tour_type_icon_buy2get1free_tour==true ? $box_onclick : $box_disabled)) . '买2送1 &nbsp; ';
					echo tep_draw_checkbox_field('tour_type_icon[3]', 'buy2-get-2', $display_tour_type_icon_buy2get2free_tour, '', ($display_tour_type_icon_buy2get2free_tour==true ? $box_onclick : $box_disabled)) . '买2送2 &nbsp; ';
				?>
				</td>
				</tr>
				</table>

			</td>
         	</tr>
			</table>
			
		<table id="content_button_table" width="100%" cellspacing="0" cellpadding="2" border="0">
		  <tr>
		  <td colspan="2" align="center">
          <input type="hidden" name="ajax" value="true" />
		  <?php
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
		  //echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_content">
		  <input type="hidden" name="qaanscall" value="true">

		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour content
    break;

	case 'tour_eticket':
		//start of tour eticket
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_ETICKET_INFORMATION; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 <form name="new_product"  id="new_product">
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
		  <td colspan="2">
		  		<table width="100%" border="0" cellspacing="0" cellpadding="2">
				
				<?php
				$show_old_eticket = true; //显示旧电子参团凭证模板
				if($show_old_eticket==true){
				?>
				<tr>
				<td  valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
				  <tr>
					<td class="main" width="15%" nowrap>电子参团凭证内容(旧)：</td>
					<td class="main">
					<?php echo $pInfo->eticket_old;?>
					</td>
				  </tr>
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  </table>
				</td>
				</tr>
				<?php } ?>
				
				<tr>
				<td  valign="top">		
				<div id="eticket_div" >

							<?php

							if($pInfo->products_durations_type == 0 && $pInfo->products_durations !=0 && $pInfo->products_durations > 0 ){
							$count = $pInfo->products_durations;
							}else if($pInfo->products_durations_type != 0){
							$count = 1;
							}

							?>
							

							
							<table width = "100%"  >
								<tr>

									<td colspan=2 >

											</td>

											<td  >

							<table width="100%"   border="0" >
															<tr>
							<td width="10%"></td>

							<td class="main"  align="left"  width="25%">
							<b>	<?php echo TEXT_HEADING_ETICKET_ITINERARY; ?></b>
							</td>
							<td class="main"  align="left"  width="25%">
							<b>	<?php echo TEXT_HEADING_ETICKET_PICKUP; ?></b>
							</td>
							<td class="main" align="left" width="25%">
								<b><?php echo TEXT_HEADING_ETICKET_HOTEL; ?> </b>
							</td>
							<td class="main" align="left" width="25%" >
								<b><?php echo TEXT_HEADING_ETICKET_NOTES; ?> </b>
							</td>
						</tr>
						</table></td></tr>


					<?php
					
					
						for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					 ?>
					 <tr>
						<td colspan=2 class="main" >

							<?php echo '电子参团凭证内容(新)：' .  tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
						</td>

						<td>
							<table width="60%"  border="0" >
								<tr>
									<td>

						<table width="100%">
							<?php
							for($j=1; $j<=$count; $j++)
							{
								//777
								$content_prod=tep_get_products_eticket_itinerary($products_id, $languages[$i]['id']);
								$splite_content =  explode("!##!", $content_prod);


								$content_prod_hotel=tep_get_products_eticket_hotel($products_id, $languages[$i]['id']);
								$splite_content_hotel =  explode("!##!", $content_prod_hotel);
								$content_prod_pickup=tep_get_products_eticket_pickup($products_id, $languages[$i]['id']);
								$splite_content_pickup =  explode("!##!", $content_prod_pickup);

										$content_prod_notes=tep_get_products_eticket_notes($products_id, $languages[$i]['id']);
										$splite_content_notes =  explode("!##!", $content_prod_notes);

							?>
									  <tr>
										   <td class="main" align="center" width="10%" nowrap>Day <?php echo $j ?></td>
											<td class="main" align="center" width="25%"><?php echo tep_draw_textarea_field('eticket_itinerary[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_itinerary[$languages[$i]['id']]) ? $eticket_itinerary[$languages[$i]['id']] : $splite_content[$j-1]),'id=eticket_itinerary[' . $languages[$i]['id'] . ']'); ?>
											</td>
											<td class="main" width="25%" align="center"><?php echo tep_draw_textarea_field('eticket_pickup[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_pickup[$languages[$i]['id']]) ? $eticket_pickup[$languages[$i]['id']] : $splite_content_pickup[$j-1]),'id=eticket_pickup[' . $languages[$i]['id'] . ']'); ?></td>
							<td class="main" width="25%" align="center"><?php echo tep_draw_textarea_field('eticket_hotel[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_hotel[$languages[$i]['id']]) ? $eticket_hotel[$languages[$i]['id']] : $splite_content_hotel[$j-1]),'id=eticket_hotel[' . $languages[$i]['id'] . ']'); ?></td>

										 <td class="main" width="25%" align="center">
											<?php echo tep_draw_textarea_field('eticket_notes[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_notes[$languages[$i]['id']]) ? $eticket_notes[$languages[$i]['id']] : $splite_content_notes[$j-1]),'id=eticket_notes[' . $languages[$i]['id'] . ']'); ?>
											</td>

									  </tr>


									<?php
									}
									?>
									</table>
										</td></tr></table></td></tr>

							<?php
									}
					
					?>
					
					</table>


				</div>
				</td></tr>
				</table>


				<table width="100%" border="0" cellspacing="0" cellpadding="2">
				  <tr>
					<td class="main" width="15%" nowrap><?php echo TEXT_HEADING_SPL_NOTES; ?></td>
					<td class="main">
					<?php
					echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_special_note', 'soft', '90', '15',auto_add_note_to_products_special_note((int)$products_id,$pInfo->products_special_note));

					?></td>
				  </tr>
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  <tr>
					<td class="main" width="15%" nowrap>请供应商注意的信息:</td>
					<td class="main">
					<?php
					echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('note_to_agency', 'soft', '90', '15',$pInfo->note_to_agency);

					?></td>
				  </tr>
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  </table>

		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		if($products_detail_permissions['Eticket']['能看不能编'] == true){
		?>
			<button disabled="disabled"> 能看不能编 </button>
		<?php 
		}else{
		  echo tep_image_button('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		}
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';

		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
		 		echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
		   		echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }

		  echo tep_draw_hidden_field('products_durations_type', $pInfo->products_durations_type);
		  echo tep_draw_hidden_field('products_durations', $pInfo->products_durations);
		  ?>
		  <input type="hidden" name="req_section" value="tour_eticket">
		  <input type="hidden" name="qaanscall" value="true">

		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour eticket
    break;

	case 'tour_image_video':
		//start of tour image video
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_IMAGES_VIDEOS; ?></b><h1 style="color:#FF0000">注意：请确保您上传的图片文件名不能含中文、空格等，正确的图片名格式是：英字母、数字和-(中划线)或_(下划线)组成的名称，同时不允许全角字符的文件名。</h1></td></tr>
		 <tr><td class="main" colspan="2">

		<?php
		if($products_detail_permissions['ImageVideo']['能看不能编']!=true){
			echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') . '&action=upload_product_video', 'post', 'enctype="multipart/form-data" ');
		}
		?>
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') || (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_med') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . '图片地址：'.tep_draw_input_field('products_image_med', $pInfo->products_image_med,'title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,\'detail\',\''.$city_ids.'\');" id="products_image_med" size="100" ') . '<br>缩略图地址：'.tep_draw_input_field('products_previous_image_med', $pInfo->products_image_med,'title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,\'thumb\',\''.$city_ids.'\');" id="products_previous_image_med" size="100" ') . '</td></tr></table>';
           } if (($_GET['pID']) && ($pInfo->products_image_med) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . 
		   tep_image(((stripos($pInfo->products_image_med,'http://') ===false) ? DIR_WS_CATALOG_IMAGES:'') . $pInfo->products_image_med, $pInfo->products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'width="150" align="left" hspace="0" vspace="5" id="preview_box"') .
		    '图片地址：'.tep_draw_input_field('products_previous_image_med', $pInfo->products_image_med,'title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,\'detail\',\''.$city_ids.'\',\'preview_box\');" id="products_previous_image_med" size="100" ') .'<br>缩略图地址：'.tep_draw_input_field('products_previous_image', $pInfo->products_image,'title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,\'thumb\',\''.$city_ids.'\',\'preview_box\');" id="products_previous_image" size="100" ') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15');
		  //隐藏删除图片选项
		  //echo '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); 
		  ?>

		   </td>
          </tr>
		  <tr class="dataTableRow">
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '30'); ?></td>
          </tr>
		  <tr class="dataTableRow">
			 <td class="main" valign="top" colspan="2"><b>Extra Images:</b></td>
		  </tr>
		  <tr class="dataTableRow">
            <td class="main" valign="top" colspan="2">
								<div>
								<table width="90%"  border="1" cellspacing="3" cellpadding="3">
								  <tr class="dataTableHeadingRow">
									<td width="350" class="dataTableHeadingContent">Image</td>
									<td width="150" nowrap="nowrap" class="dataTableHeadingContent">Sort Order</td>
									<td width="150" class="dataTableHeadingContent">Remove</td>
								  </tr>
								</table>
								</div>
								<div id="myDiv">
								<?php

								$category_intro_query_sql  = "select *  from " . TABLE_PRODUCTS_EXTRA_IMAGES . " where products_id = '" . $_GET['pID'] . "' order by image_sort_order";
								$category_intro_query = tep_db_query($category_intro_query_sql);

								$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
								//$category_intro = tep_db_fetch_array($category_intro_query);
								if($tt_into_cnt_row > 0){
									?>
									<input type="hidden" value="<?php echo $tt_into_cnt_row; ?>" id="theValue" />
									<?php
									$row = 1;
									while($category_intro = tep_db_fetch_array($category_intro_query)){
									?>
									<div id="my<?php echo $row;?>Div">
									<table width="90%" border="0" cellspacing="3" cellpadding="3">
									  <tr >
										<td  valign="top"  width="350" class="main">
										<?php
										if($category_intro['product_image_name']!= '') {
											$img_src = HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES.$category_intro['product_image_name'];
											if(preg_match('/^http:/',$category_intro['product_image_name'])){
												$img_src = $category_intro['product_image_name'];
											}
										?>
										<img src="<?php echo $img_src;?>" alt="<?php echo $category_intro['product_image_name'];?>" title="<?php echo $category_intro['product_image_name'];?>" width="100" id="preview_box_<?= $row?>"><br/>
										<?php } ?>

										Image URL<br /><input type="input" name="previouse_image_introfile[]" value="<?php echo $category_intro['product_image_name'];?>" size="41" id="previouse_image_introfile_<?= $row?>" title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,'detail','<?= $city_ids?>','preview_box_<?= $row?>');">
										<br />Or Upload<br />
										<input type='file' name='image_introfile[]'>


										</td>
										<td valign='top' width="150"><input type="text" name='cat_intro_sort_order[]' size="10" value="<?php echo $category_intro['image_sort_order'];?>"></td>
										<td valign='top' width="150"><input type="hidden" name="db_categories_introduction_id[]" value="<?php echo $category_intro['prod_extra_image_id'];?>"> <input type="hidden" id="remove_id_form_db_<?php echo $category_intro['prod_extra_image_id'];?>" name="remove_id_form_db[]"  value="off"><input type="checkbox" name="delete_frm_db[]" onClick="document.getElementById('remove_id_form_db_<?php echo $category_intro['prod_extra_image_id'];?>').value='on'"></td>
									  </tr>
									</table>
									</div>
									<?php
									$row++;
									}
									?>
									<?
								}else{
								?>
								<input type="hidden" value="1" id="theValue" />
								<div id="my1Div">
								<table width="90%" border="0" cellspacing="3" cellpadding="3">
								  <tr>
									<td valign="top" width="350" class="main">
									<img src="" width="100" id="preview_introfile_0"><br/>
									Image URL<br /><input type="input" id="previouse_image_introfile_0" name="previouse_image_introfile[]" size="41" title="双击可以打开图片选择器"  ondblclick="open_picture_db(this,'detail','<?= $city_ids?>','preview_introfile_0');">
									<br />Or Upload<br />
									<input type='file' name='image_introfile[]'>
									</td>
									<td valign='top' width="150"><input type="text" name='cat_intro_sort_order[]' size="10" value="1"></td>
									<td valign='top' width="150"><a href="javascript:;" onClick="removeEvent('my1Div')">Remove</a></td>
								  </tr>
								</table>
								</div>
								<?php
								}
								?>
								</div>
								<p><a href="javascript:;" onClick="addEventExtra('<?= $city_ids?>');"><b><font color="#000099">Add More Images</font></b></a></p>

			</td>
          </tr>
          <!--<tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php //echo TEXT_PRODUCTS_IMAGE_MEDIUM; ?></span></td>
           <?php //if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') || (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php //echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>'; ?>
           <?php //} else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php //echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image', $pInfo->products_image,'') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '</td></tr></table>';
           /*} if (($_GET['pID']) && ($pInfo->products_image) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42');*/ ?> </span></td>
          </tr>-->
		<?php  ?>
		 <?php
			/*//hotel-extension begin {
		  if(tep_check_product_is_hotel($products_id)==1){
		  ?>
		  <tr>
            <td colspan="2"  class="dataTableRow"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
          </tr>
		   <tr class="dataTableRow">
			 <td class="main" valign="top" colspan="2"><b>Hotel Images:</b></td>
		  </tr>
		  <tr class="dataTableRow">
            <td class="main" valign="top" colspan="2">
			<div>
			<table width="70%"  border="1" cellspacing="3" cellpadding="3">
			  <tr class="dataTableHeadingRow">
				<td width="330" class="dataTableHeadingContent">Image</td>
				<td width="250" class="dataTableHeadingContent">Title</td>
				<td width="150" nowrap="nowrap" class="dataTableHeadingContent">Sort Order</td>
				<td width="150" class="dataTableHeadingContent">Remove</td>
			  </tr>
			</table>
			</div>				
			<div id="myDivHotel">
			<?php
			$category_intro_query_sql  = "select *  from " . TABLE_PRODUCTS_HOTEL_IMAGES . " where products_id = '" . $HTTP_GET_VARS['pID'] . "' order by hotel_image_sort_order";
			$category_intro_query = tep_db_query($category_intro_query_sql);								
			$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
			//$category_intro = tep_db_fetch_array($category_intro_query);
			if($tt_into_cnt_row > 0){
				?>
				<input type="hidden" value="<?php echo $tt_into_cnt_row; ?>" id="theValueHotel" />
				<?php
				$row = 1;
				while($category_intro = tep_db_fetch_array($category_intro_query)){
				?>
				<div id="my<?php echo $row;?>DivHotel">
				<table width="70%" border="0" cellspacing="3" cellpadding="3">
				  <tr >
					<td  valign="top"  width="210">
					<?php
					if($category_intro['products_hotel_image_name']!= '') {
					?>
					<img src="<?php echo DIR_WS_CATALOG_IMAGES.$category_intro['products_hotel_image_name'];?>" alt="<?php echo $category_intro['products_hotel_image_name'];?>" width="100" height="100"><br/>
					<?php } ?>
					<input type='file' name='products_hotel_image_name[]'>
					<input type="hidden" name="previouse_products_hotel_image_name[]" value="<?php echo $category_intro['products_hotel_image_name'];?>">
					</td>
					<td valign='top' width="160"><input type="text" name='products_hotel_image_title[]' value="<?php echo $category_intro['products_hotel_image_title'];?>"></td>
					<td valign='top' width="200"><input type="text" name='hotel_image_sort_order[]' size="10" value="<?php echo $category_intro['hotel_image_sort_order'];?>"></td>
					<td valign='top' width="150"><input type="hidden" name="db_products_hotel_image_id[]" value="<?php echo $category_intro['products_hotel_image_id'];?>"> <input type="hidden" id="remove_id_form_hotel_db_<?php echo $category_intro['products_hotel_image_id'];?>" name="remove_id_form_hotel_db[]"  value="off"><input type="checkbox" name="delete_frm_hotel_db[]" onClick="document.getElementById('remove_id_form_hotel_db_<?php echo $category_intro['products_hotel_image_id'];?>').value='on'"></td>
				  </tr>
				</table>
				</div>		
				<?php		
				$row++;
				}
				?>
				<?	
			}else{
			?>
			<input type="hidden" value="1" id="theValueHotel" />
			<div id="my1DivHotel">
			<table width="70%" border="0" cellspacing="3" cellpadding="3">
			  <tr>
				<td valign="top"  width="210" align="left"><input type='file' name='products_hotel_image_name[]'>		
				</td>
				<td valign='top' width="160" align="left"><input type="text" name='products_hotel_image_title[]' value=""></td>
				<td valign='top' width="200" align="left"><input type="text" name='hotel_image_sort_order[]' size="10" value="1"></td>
				<td valign='top' width="150" align="left"><a href="javascript:;" onClick="removeEventHotel('my1DivHotel')">Remove</a></td>
			  </tr>
			</table>
			</div>
			<?php
			}
			?>
			</div>
			<p><a href="javascript:;" onClick="addEventExtraHotel();"><b><font color="#000099">Add More Images</font></b></a></p>
			
			</td>
          </tr>
		  <?php
		  } 
		  ?>
		  <tr>
		  <?php //hotel-extension end } */?>
            <td colspan="2"  class="dataTableRow"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_MAP_NOTE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') || (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_map') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_map', $pInfo->products_map,'') . tep_draw_hidden_field('products_previous_map', $pInfo->products_map) . '</td></tr></table>';
           } if (($_GET['pID']) && ($pInfo->products_map) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_map . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_map, $pInfo->products_map, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_map', $pInfo->products_map) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_map" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_map" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
		   </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		    <tr>
            <td  class="main"><?php echo TEXT_PRODUCTS_VIDEO; ?></td>
			<td class="main">
			<small>
			Direct URL
			</small>&nbsp;&nbsp;<?php echo tep_draw_input_field('products_video_url', '', 'size="30"'); ?>
			<?php
			if(substr($pInfo->products_video,0,5) == 'http:'){
			echo "<br>".$pInfo->products_video;
			$directvurl="show";
			}
			?>
			<br><small>or</small><br>
			<small>Upload Video</small>&nbsp;&nbsp;
			<?php echo tep_draw_file_field('products_video').tep_draw_hidden_field('products_previous_video', $pInfo->products_video); ?>
			<br>
			<input type="checkbox" name="unlink_video" value="yes"> Remove Video
			<?php
			if($directvurl != "show" && $pInfo->products_video != '' ){
			echo $pInfo->products_video;
			}
			?>
			</td>
          </tr>

		    <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  //echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		if($products_detail_permissions['ImageVideo']['能看不能编'] == true){
		?>
		<button disabled="disabled"> 能看不能编 </button>
		<?php
		}else{
		  	echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
		}
		  
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';

		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
		 		echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
		   		echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_image_video">
		  <input type="hidden" name="qaanscall" value="true">

		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  </table>
		<?php
		if($products_detail_permissions['ImageVideo']['能看不能编']!=true){?>
		</form>
		<?php }?>
		 </td></tr>
		 </table>
		 <?php
		//end of tour image video
    break;

	case 'tour_meta_tag':
		//start of tour meta tag
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_PRODUCT_METTA_INFO; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		<?php if($products_detail_permissions['MetaTag']['能看不能编'] != true){?>
		 <form name="new_product"  id="new_product">
		<?php }?>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				 <?php
					for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				?>
				 <tr>
				 <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  <tr>
					<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
					<td><table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
					  </tr>
					</table></td>
				  </tr>

				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				   <tr>
					<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
					<td><table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
							  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				   <tr>
					<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS; ?></td>
					<td><table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?>
						<input name="button_get" type="button" value="自动获得Meta Keywords" onclick="auto_get_keyword(<?php echo $_GET['pID']?>)" />
						</td>
					  </tr>

					</table></td>
				  </tr>
				  <?php } ?>
				  <tr>
				  <td colspan="2" align="center">
				<?php
				if($products_detail_permissions['MetaTag']['能看不能编'] != true){
				  echo tep_image_button('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
				}else{
				?>
				<button disabled="disabled"> 能看不能编 </button>
				<?php
				}
				  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
				  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
		 				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '') .(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				  }else{
						echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				  }
				 ?>
				  <input type="hidden" name="req_section" value="tour_metataginformation">
				  <input type="hidden" name="qaanscall" value="true">

				  </td>
				  </tr>
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>

		  		</table>
		<?php if($products_detail_permissions['MetaTag']['能看不能编'] != true){?>
		</form>
		<?php }?>
		 </td></tr>
		 </table>

		 <?php
		//end of tour meta tag
    break;
	//hotel-extension begin {
	case 'tour_hotels_nearby_attractions':
		?>
		<table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_PRODUCT_HOTELS_NEARBY_ATTRACTIONS; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 <?php if($products_detail_permissions['HotelsNearbyAttractions']['能看不能编'] != true){?>
		 <form name="new_product" id="new_product">
		 <?php }?>		 		
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<?php
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {				
				?>
			    <tr>
				<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
				<td class="main">
				<?php echo tep_draw_textarea_field('products_hotel_nearby_attractions[' . $languages[$i]['id'] . ']', 'soft', '100', '30', (isset($products_hotel_nearby_attractions[$languages[$i]['id']]) ? $products_hotel_nearby_attractions[$languages[$i]['id']] : tep_get_products_hotel_nearby_attractions($pInfo->products_id, $languages[$i]['id']))); ?>
				</td>
				</tr>
				<?php
				}
				?>
				<tr>
				  <td align="center" colspan="2">
				  <?php
				if($products_detail_permissions['HotelsNearbyAttractions']['能看不能编'] != true){
				  echo tep_image_button('button_update.gif', IMAGE_UPDATE, 'style="cursor:pointer" onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'false\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
				}else{
				?>
				<button disabled="disabled"> 能看不能编 </button>
				<?php
				}
				  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
				  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
		 				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
				  }else{
						echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
				  }
				 ?>
				  <input type="hidden" name="req_section" value="tour_hotels_nearby_attractions">
				  <input type="hidden" name="qaanscall" value="true">	
				  
				  </td>
				</tr>
			</table>
		 <?php if($products_detail_permissions['HotelsNearbyAttractions']['能看不能编'] != true){?>
		 </form>
		 <?php }?>		 		
		</table>
		<?php
	break;
	//hotel-extension end }
	case 'tour_hotels_pre_post':
		//start of tour hotels tag {
		?>
		<?php if($products_detail_permissions['HotelsTransferServices']['能看不能编'] != true){?>
		<form name="new_product" id="new_product">	
		<?php }?>
		<table border="0" cellspacing="0" cellpadding="2">
		<tr><td colspan="2" style="padding:15px;"></td></tr>
		 	 		
		 <tr><td class="main" colspan="2"><b>Transfer Service </b></td></tr>
		 <tr><td class="main" colspan="2">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td  class="main">Transfer Service:</td>
				<td colspan="2" >
				<select name="recommend_transfer_id" id="RecommendTransferId"  >
					<option value="0" > - Please select recommend transfer service - </option>
				<?php 
				$query = tep_db_query("SELECT p.products_id,p.products_model,p.provider_tour_code,pd.products_name from " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_DESCRIPTION." pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.is_transfer = '1' and p.products_status = '1' order by pd.products_name");
				while($row = tep_db_fetch_array($query)){
					$selected = $row['products_id'] == $pInfo->recommend_transfer_id ? ' selected ' : '';
					echo '<option value="'.$row['products_id'].'"  '.$selected.'>'.$row['products_name'].' - '.$row['products_model'].' ['.$row['provider_tour_code'].']</option>';
				}
				?>
				</select></td>
			</tr>
			</table>
		 </td>
		 </tr>
		 <tr><td colspan="2" style="padding:15px;"></td></tr>
         <tr><td class="main" colspan="2"><b><?php echo TEXT_PRODUCT_HOTELS_PRE_POST; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td colspan="3" class="main">Hotels for Early Arrival:
				<script language="text/javascript">
				function copyhotel(obj){
					var parentObj = jQuery(obj).parent();
					var val = parentObj.find('input[id=\'val_copy\']').val();
					var pid = parentObj.find('input[id=\'products_id_copy\']').val();
					var type = parentObj.find('input[id=\'s_type\']').val();
					if(confirm("你确定要复制此信息到指定的线路？")) {
						jQuery.post('categories_ajax_sections.php?section=copy_products',{'pid':pid,'val':val,'type':type},function(r){
							if (r.state == 0) {
								parentObj.find('input[id=\'products_id_copy\']').val('');
								alert('复制成功！');
							} else {
								alert(r.error);
							}
						},'json');
					}
					return false;
				}
				</script>
				</td>
				</tr>
				<tr>
					<?php
					$total_early_arrival_hotels = '';
					$selected_early_arrival_hotels = '';
					$selected_early_arrival_hotels_array = explode(",", $pInfo->hotels_for_early_arrival);
					if(isset($_GET['agency'])){
						$my_agency=$_GET['agency'];
					}else{
						$str_sql='select agency_id from '.TABLE_PRODUCTS.' WHERE products_id='.(int)$_GET['pID'];
						$tmp_array=tep_db_fetch_array(tep_db_query($str_sql));
						$my_agency=$tmp_array['agency_id'];
					}
					//$early_arrival_hotels_query = tep_db_query("select h.hotel_id, h.hotel_name from " . TABLE_HOTELS . " h, ".TABLE_HOTELS_TO_AGENCY." h2a where h.hotel_id = h2a.hotel_id and h2a.agency_id = '".$pInfo->agency_id."' and h.arriving_early = '1' order by h.hotel_name");
					$early_arrival_hotels_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_DESCRIPTION." pd where p.products_id = pd.products_id and agency_id=".(int)$my_agency." and pd.language_id = '" . (int)$languages_id . "' and p.is_hotel = '1' and p.products_status = '1' order by pd.products_name");
					while ($early_arrival_hotels = tep_db_fetch_array($early_arrival_hotels_query)) {
					  if(in_array($early_arrival_hotels['products_id'], $selected_early_arrival_hotels_array)){
					  	$selected_early_arrival_hotels .= "<option value=".$early_arrival_hotels['products_id'].">".$early_arrival_hotels['products_name']."</option>";
					  }else{
					  	$total_early_arrival_hotels .= "<option value=".$early_arrival_hotels['products_id'].">".$early_arrival_hotels['products_name']."</option>";
					  }
					}
					?>
					<td>
					<select name="early_arrival_hotels_temp" id="early_arrival_hotels_temp" size="10" multiple="multiple">
					<?php echo $total_early_arrival_hotels; ?>
					</select>
					</td>
					<td>
					<input type="button" value="--&gt;" onclick="moveOptions(this.form.early_arrival_hotels_temp, this.form.early_arrival_hotels_temp1);" /><br />
					<input type="button" value="&lt;--" onclick="moveOptions(this.form.early_arrival_hotels_temp1, this.form.early_arrival_hotels_temp);" />
					</td>
					<td>
					<select name="early_arrival_hotels_temp1" id="early_arrival_hotels_temp1" size="10" multiple="multiple">
					<?php echo $selected_early_arrival_hotels;?>
					</select>
					<input type="hidden" name="early_arrival_hotels" id="early_arrival_hotels" value="">
					</td>
					<td style="padding:10px;"><fieldset><legend>复制到其他产品</legend>产品ID：<br/><input type="text" id="products_id_copy" /><input type="hidden" id="val_copy" value="<?php echo $pInfo->hotels_for_early_arrival ?>"/><input type="hidden" id="s_type" value="early_arrival"/><br/>(英文逗号隔开产品id，并且不可有空格。如：14,789,3054)<br/><button onclick="return copyhotel(this)">复制</button></fieldset></td>
				</tr>
				<tr>
					<td colspan="3" class="main">Hotels for Late Departure:</td>
				</tr>
				<tr>
					<?php
					$total_late_departure_hotels = '';
					$selected_late_departure_hotels = '';
					$selected_late_departure_hotels_array = explode(",", $pInfo->hotels_for_late_departure);
					
					$late_departure_hotels_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_DESCRIPTION." pd where p.products_id = pd.products_id and agency_id=".(int)$my_agency." and pd.language_id = '" . (int)$languages_id . "' and p.is_hotel = '1' and p.products_status = '1' order by pd.products_name");
					while ($late_departure_hotels = tep_db_fetch_array($late_departure_hotels_query)) {
					  if(in_array($late_departure_hotels['products_id'], $selected_late_departure_hotels_array)){
					  	$selected_late_departure_hotels .= "<option value=".$late_departure_hotels['products_id'].">".$late_departure_hotels['products_name']."</option>";
					  }else{
					  	$total_late_departure_hotels .= "<option value=".$late_departure_hotels['products_id'].">".$late_departure_hotels['products_name']."</option>";
					  }
					}
					?>
					<td>
					<select name="late_departure_hotels_temp" id="late_departure_hotels_temp" size="10" multiple="multiple">
					<?php echo $total_late_departure_hotels; ?>
					</select>
					</td>
					<td>
					<input type="button" value="--&gt;" onclick="moveOptions(this.form.late_departure_hotels_temp, this.form.late_departure_hotels_temp1);" /><br />
					<input type="button" value="&lt;--" onclick="moveOptions(this.form.late_departure_hotels_temp1, this.form.late_departure_hotels_temp);" />
					</td>
					<td>
					<select name="late_departure_hotels_temp1" id="late_departure_hotels_temp1" size="10" multiple="multiple">
					<?php echo $selected_late_departure_hotels; ?>
					</select>
					<input type="hidden" name="late_departure_hotels" id="late_departure_hotels" value="">
					</td>
						<td style="padding:10px;"><fieldset><legend>复制到其他产品</legend>产品ID：<br/><input type="text" id="products_id_copy" /><input type="hidden" id="val_copy" value="<?php echo $pInfo->hotels_for_late_departure ?>"/><input type="hidden" id="s_type" value="late_departure"/><br/>(英文逗号隔开产品id，并且不可有空格。如：14,789,3054)<br/><button onclick="return copyhotel(this)">复制</button></fieldset></td>
				</tr>
				<tr>
				  <td colspan="3" align="center">
				  <?php
				/*
				  if($pInfo->is_hotel == 1){
					//$onclick_function = '';
					$onclick_function = 'SelectAll_ajax(document.new_product.sel2,document.new_product.departure_end_city_id_temp1,document.new_product.departure_city_id_temp1); ';
				 }else{
					$onclick_function = 'SelectAll_ajax(document.new_product.sel2,document.new_product.departure_end_city_id_temp1,document.new_product.departure_city_id_temp1); ';
				 }*/
				if($products_detail_permissions['HotelsTransferServices']['能看不能编'] != true){
				  echo tep_image_button('button_update.gif', IMAGE_UPDATE, 'style="cursor:pointer" onclick="SelectAll_hotels(document.new_product.late_departure_hotels_temp1, document.new_product.late_departure_hotels); SelectAll_hotels(document.new_product.early_arrival_hotels_temp1, document.new_product.early_arrival_hotels); sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'false\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
				}else{
				?>
				<button disabled="disabled"> 能看不能编 </button>
				<?php
				}
				  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
				  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
		 				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
				  }else{
						echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&page='.$page.'&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
				  }
				 ?>
				  <input type="hidden" name="req_section" value="tour_hotels_pre_post">
				  <input type="hidden" name="qaanscall" value="true">	
				  
				  </td>
				</tr>
				<tr>
					<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
			</table>
		 
		 </td></tr>
		</table>
		<?php if($products_detail_permissions['HotelsTransferServices']['能看不能编'] != true){?>
		</form>
		<?php }?>
<?php break;	//hotel-extension end }
	case 'tour_attribute':
		//start of tour attribute

		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}


		?>

		 <?php
		 if($display_room_yes)
		 {
		 ?>
		<div id="optionyes" style="visibility: visible; display: inline;"></div>

		   <div id="optionno" style="visibility: hidden; display: none;"></div>
		 <?php
		 }elseif($display_room_no)
		 {
		 ?>
		 <script type="text/javascript">
		 	option_value_yn = <?php echo $display_room_no;?>;
		 </script>
		<div id="optionyes" style=" visibility: hidden; display: none;"></div>

		   <div id="optionno" style="visibility: visible; display: inline;"></div>
		<?php
		}?>


		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_ATTRIBUTES; ?></b></td></tr>
		 <tr><td class="main" colspan="2">

		 <form name="new_product"  id="new_product">
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">

		 <?php if($access_full_edit == 'true') { ?>
		 <tr>
			<td class="main" colspan="2"><?php echo TEXT_HEADING_GROSS_PROFIT.'&nbsp;'; ?>

			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_margin', $pInfo->products_margin, 'size="7"'); ?> %</td>
		 </tr>
		 <tr>
			<td class="errorText" colspan="2">
			<?php echo TEXT_NOTICE_CP_AND_RP; ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
        <td colspan="2">
		<div id="tour_attribute_list"><table border="3" cellspacing="5" cellpadding="2" align="center" bgcolor="000000">

			<?php
				$rows = 0;
				$attribute_price_readonly = '';
				if($products_detail_permissions['Attribute']['不能输入价格'] == true || $products_detail_permissions['Attribute']['不能更新'] == true){
					$attribute_price_readonly = ' readonly="readonly" ';
				}
				
			   if($pInfo->agency_id!='')
				{
				  $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$pInfo->agency_id."' order by products_options_sort_order, products_options_name");

				}
				else
				{
				$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
				}
				while ($options = tep_db_fetch_array($options_query)) {

				if($pInfo->agency_id!='')
				{


				  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option, pov.def_price, pov.def_single, pov.def_double, pov.def_triple, pov.def_quadruple, pov.def_kids, pov.def_cost, pov.def_single_cost, pov.def_double_cost, pov.def_triple_cost, pov.def_quadruple_cost, pov.def_kids_cost from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$pInfo->agency_id."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");


				 }
				 else
				 {
					$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.is_per_order_option, pov.def_price, pov.def_single, pov.def_double, pov.def_triple, pov.def_quadruple, pov.def_kids, pov.def_cost, pov.def_single_cost, pov.def_double_cost, pov.def_triple_cost, pov.def_quadruple_cost, pov.def_kids_cost from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
				 }
				  $header = false;
				  while ($values = tep_db_fetch_array($values_query)) {
					$rows ++;
					if (!$header) {
					  $header = true;
			?>
					  <tr valign="top">
						<td><table border="2" cellpadding="2" cellspacing="2" bgcolor="FFFFFF">
						  <tr class="dataTableHeadingRow">
						  <td colspan="11" class="attributeBoxContent" align="center">Active Attributes</td>
						 </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left"><?php echo $options['products_options_name']; ?></td>
							<td class="dataTableHeadingContent" width="50" align="center" title="倍数系数，右边字段的数值已经是乘以倍数后的结果，此值用于批量更新属性价格时才用到">倍数系数</td>
							<td class="dataTableHeadingContent" width="50" align="center"><?php echo 'Prefix'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Price'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php if($display_room_yes){ echo 'Single'; }else{echo 'Adult';}?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Double'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Triple'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Quadruple'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Kids'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Sort Order'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Special'; ?></td>
						  </tr>
			<?php
					}
						$attributes = array();

						  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, options_values_price_cost, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,single_values_price_cost, double_values_price_cost,triple_values_price_cost,quadruple_values_price_cost,kids_values_price_cost, products_options_sort_order, multiplier from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $pInfo->products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
						  if (tep_db_num_rows($attributes_query) > 0) {
							$attributes = tep_db_fetch_array($attributes_query);
						  }

						if($attributes['single_values_price']>0 || $attributes['double_values_price']>0 || $attributes['triple_values_price']>0 || $attributes['quadruple_values_price']>0 || $attributes['kids_values_price']>0)
						{
							$check_spe_price_attri=true;
						}
						else
						{
							$check_spe_price_attri=false;
						}


			?>
						  <tr class="dataTableRow">
							<td class="dataTableContent"><?php echo tep_draw_checkbox_field('option[' . $rows . ']', $attributes['products_attributes_id'], $attributes['products_attributes_id']) . '&nbsp;' . $values['products_options_values_name']; ?><?php echo( ($values['is_per_order_option'] == 1)? ' <small class="col_red">(Per Order)</small>' : '' ); ?>&nbsp;</td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('multipliers[' . $rows . ']', max(1, (int)$attributes['multiplier']), 'size="2"'.$attribute_price_readonly); ?>
							<?php if($values['products_options_values_id']){	//根据系数倍数重新计算属性价格?>
							<a id="auto_reset_price_multiplier_<?= $values['products_options_values_id'];?>" href="javascript:void(0);"><img src="images/icon_reset.gif" title="根据系数倍数重新计算属性价格" /></a>
							<script type="text/javascript">
							jQuery('#auto_reset_price_multiplier_<?= $values['products_options_values_id'];?>').click(function(){
								if(jQuery(this).prev().attr('name').indexOf('multipliers[')==-1){
									alert('找不到系数倍数输入框了，叫技术部找原因！');
									return false;
								}
								var beiShu = jQuery(this).prev().val();
								jQuery('input[name="price[<?= $rows;?>]"]').val((beiShu * <?= $values['def_price'];?>));
								jQuery('input[name="price_cost[<?= $rows;?>]"]').val((beiShu * <?= $values['def_cost'];?>));
								jQuery('input[name="single_price[<?= $rows;?>]"]').val((beiShu * <?= $values['def_single'];?>));
								jQuery('input[name="single_price_cost[<?= $rows;?>]"]').val((beiShu * <?= $values['def_single_cost'];?>));
								jQuery('input[name="double_price[<?= $rows;?>]"]').val((beiShu * <?= $values['def_double'];?>));
								jQuery('input[name="double_price_cost[<?= $rows;?>]"]').val((beiShu * <?= $values['def_double_cost'];?>));
								jQuery('input[name="triple_price[<?= $rows;?>]"]').val((beiShu * <?= $values['def_triple'];?>));
								jQuery('input[name="triple_price_cost[<?= $rows;?>]"]').val((beiShu * <?= $values['def_triple_cost'];?>));
								jQuery('input[name="quadruple_price[<?= $rows;?>]"]').val((beiShu * <?= $values['def_quadruple'];?>));
								jQuery('input[name="quadruple_price_cost[<?= $rows;?>]"]').val((beiShu * <?= $values['def_quadruple_cost'];?>));
								jQuery('input[name="kids_price[<?= $rows;?>]"]').val((beiShu * <?= $values['def_kids'];?>));
								jQuery('input[name="kids_price_cost[<?= $rows;?>]"]').val((beiShu * <?= $values['def_kids_cost'];?>));
								//触发通知商务中心功能！
								jQuery('input[name="notice_customer_service"]').attr('checked',true);
							});
							</script>
							<?php }?>
							</td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('prefix[' . $rows . ']', $attributes['price_prefix'], 'size="2"'.$attribute_price_readonly); ?></td>
							<td class="dataTableContent"  align="center" style=" white-space:nowrap;">
							<?php
							//如果产品选项价格小于1就载入产品选项默认价格和成本 {
							if($attributes['products_attributes_id']<1){	//该选项未被选中时才设置默认价格和成本
								if(!(int)$attributes['options_values_price']) $attributes['options_values_price'] = $values['def_price'];
								if(!(int)$attributes['single_values_price']) $attributes['single_values_price'] = $values['def_single'];
								if(!(int)$attributes['double_values_price']) $attributes['double_values_price'] = $values['def_double'];
								if(!(int)$attributes['triple_values_price']) $attributes['triple_values_price'] = $values['def_triple'];
								if(!(int)$attributes['quadruple_values_price']) $attributes['quadruple_values_price'] = $values['def_quadruple'];
								if(!(int)$attributes['kids_values_price']) $attributes['kids_values_price'] = $values['def_kids'];
								
								if(!(int)$attributes['options_values_price_cost']) $attributes['options_values_price_cost'] = $values['def_cost'];
								if(!(int)$attributes['single_values_price_cost']) $attributes['single_values_price_cost'] = $values['def_single_cost'];
								if(!(int)$attributes['double_values_price_cost']) $attributes['double_values_price_cost'] = $values['def_double_cost'];
								if(!(int)$attributes['triple_values_price_cost']) $attributes['triple_values_price_cost'] = $values['def_triple_cost'];
								if(!(int)$attributes['quadruple_values_price_cost']) $attributes['quadruple_values_price_cost'] = $values['def_quadruple_cost'];
								if(!(int)$attributes['kids_values_price_cost']) $attributes['kids_values_price_cost'] = $values['def_kids_cost'];
								
							}
							//如果产品选项价格小于1就载入产品选项默认价格和成本 }
							if($access_full_edit == 'true') {
								echo 'RP '.$display_tour_agency_opr_currency_note.tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], ' id="price'.$rows.'"  size="7" '.$attribute_price_readonly.($check_spe_price_attri?'disabled="disabled"':'').''.$price_input_readonly);
								echo '<br>CP '.$display_tour_agency_opr_currency_note.tep_draw_input_field('price_cost[' . $rows . ']', $attributes['options_values_price_cost'], ' id="price_cost'.$rows.'" size="7"'.$attribute_price_readonly.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'price_cost'.$rows.'\'), document.getElementById(\'price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'>';

							}else{
								echo $display_tour_agency_opr_currency_note.tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], 'size="7" '.$attribute_price_readonly.($check_spe_price_attri?'disabled="disabled"':'').'');
							}
							?></td>

							<?php
							
							if($access_full_edit == 'true') {
							?>
							<td class="dataTableContent"  align="center" width="70">
							<?php
							if($check_spe_price_attri){
								echo tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], ' id="single_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('single_price_cost[' . $rows . ']', $attributes['single_values_price_cost'], ' id="single_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'single_price_cost'.$rows.'\'), document.getElementById(\'single_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'>';
							}else {
								echo '<div id="show_sub_divattri_s_'.$rows.'" style="display:none" >'.tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'id="single_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('single_price_cost[' . $rows . ']', $attributes['single_values_price_cost'], ' id="single_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'single_price_cost'.$rows.'\'), document.getElementById(\'single_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'></div><div id="hide_sub_divattri_s_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';
							}
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php
							
							if($check_spe_price_attri){
								echo tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], ' id="double_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('double_price_cost[' . $rows . ']', $attributes['double_values_price_cost'], ' id="double_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'double_price_cost'.$rows.'\'), document.getElementById(\'double_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'>';
							}else {
								echo '<div id="show_sub_divattri_d_'.$rows.'" style="display:none" >'.tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], ' id="double_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('double_price_cost[' . $rows . ']', $attributes['double_values_price_cost'], 'id="double_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'double_price_cost'.$rows.'\'), document.getElementById(\'double_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'></div><div id="hide_sub_divattri_d_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';
							}
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php
							
							if($check_spe_price_attri){
								echo tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], ' id="triple_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('triple_price_cost[' . $rows . ']', $attributes['triple_values_price_cost'], ' id="triple_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'triple_price_cost'.$rows.'\'), document.getElementById(\'triple_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'>';
							}else {
								echo '<div id="show_sub_divattri_t_'.$rows.'" style="display:none" >'.tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'id="triple_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('triple_price_cost[' . $rows . ']', $attributes['triple_values_price_cost'], 'id="triple_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'triple_price_cost'.$rows.'\'), document.getElementById(\'triple_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'></div><div id="hide_sub_divattri_t_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';
							}
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php
							
							if($check_spe_price_attri){
								echo tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], ' id="quadruple_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('quadruple_price_cost[' . $rows . ']', $attributes['quadruple_values_price_cost'], ' id="quadruple_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'quadruple_price_cost'.$rows.'\'), document.getElementById(\'quadruple_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'>';
							}else {
								echo '<div id="show_sub_divattri_q_'.$rows.'" style="display:none" >'.tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'id="quadruple_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('quadruple_price_cost[' . $rows . ']', $attributes['quadruple_values_price_cost'], 'id="quadruple_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'quadruple_price_cost'.$rows.'\'), document.getElementById(\'quadruple_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'></div><div id="hide_sub_divattri_q_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';
							}
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php
							
							if($check_spe_price_attri){
								echo tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], ' id="kids_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('kids_price_cost[' . $rows . ']', $attributes['kids_values_price_cost'], ' id="kids_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'kids_price_cost'.$rows.'\'), document.getElementById(\'kids_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'>';
							}else {
								echo '<div id="show_sub_divattri_k_'.$rows.'" style="display:none" >'.tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'id="kids_price'.$rows.'" size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('kids_price_cost[' . $rows . ']', $attributes['kids_values_price_cost'], 'id="kids_price_cost'.$rows.'" size="7"'.$price_input_readonly).'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'kids_price_cost'.$rows.'\'), document.getElementById(\'kids_price'.$rows.'\'));"  type="button" value="算底价(a)" '.$price_button.'></div><div id="hide_sub_divattri_k_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';
							} ?>
							</td>
							<?php }else { ?>
							<td class="dataTableContent"  align="center" width="70"><?php if($check_spe_price_attri){ tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"');}else {echo '<div id="show_sub_divattri_s_'.$rows.'" style="display:none" >'.tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_s_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';}	?></td>
							<td class="dataTableContent"  align="center" width="70"><?php if($check_spe_price_attri){tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_d_'.$rows.'" style="display:none" >'.tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_d_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; } ?></td>
							<td class="dataTableContent"  align="center" width="70"><?php  if($check_spe_price_attri){tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_t_'.$rows.'" style="display:none" >'.tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_t_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; }?></td>
							<td class="dataTableContent"  align="center" width="70"><?php  if($check_spe_price_attri){tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_q_'.$rows.'" style="display:none" >'.tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_q_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; }?></td>
							<td class="dataTableContent"  align="center" width="70"><?php  if($check_spe_price_attri){tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_k_'.$rows.'" style="display:none" >'.tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_k_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; } ?></td>
							<?php } ?>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $rows . ']', $attributes['products_options_sort_order'], 'size="7"'); ?></td>
							<td class="dataTableContent" width="70" align="center">
							<?php
							if($can_update_price === true){
								echo  ($check_spe_price_attri?'<a href="javascript:void(0)" onclick="javascript:delete_spe_price_attri('.$rows.');">Delete spe. Price</a>':'<a href="javascript:void(0)" onclick="javascript:enter_spec_price_attri('.$rows.')">Sepecial Price</a>');
							}else{
								echo '&nbsp;';
							}
							?>
							</td>
							</tr>
			<?php
				  }
				  if ($header) {
			?>
						</table></td>

			<?php
				  }
				}
			?>
					  </tr>
					</table></div></td>
				  </tr>
			<?php
			// EOF: WebMakers.com Added: Draw Attribute Tables
			?>


		  <tr>
		  <td colspan="2" align="center" class="main">
		  <?php
		  if($products_detail_permissions['Attribute']['不能更新'] != true){
		  	echo tep_image_button('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$_GET['section'].'&pID=' . $_GET['pID'].'&cPath=' . $_GET['cPath'].(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
		  }
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  if($_GET['searchkey'] != '' || $_GET['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($_GET['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $_GET['pID'] .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '&cPath=' . $_GET['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
		  }
		  echo tep_draw_hidden_field('prev_agency_id', $pInfo->agency_id);
		  echo tep_draw_hidden_field('agency_id', $pInfo->agency_id);
		  ?>
		  <input type="hidden" name="req_section" value="tour_attribute">
		  <input type="hidden" name="qaanscall" value="true">
		<label><input type="checkbox" name="notice_customer_service" value="1" /> 通知商务中心</label>
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php


		//end of tour attribute
    break;
	//start transfer service route setup  form{
	case 'tour_transfer': include(DIR_FS_ADMIN.'categories_ajax_sections_transfer_form.php');break;
	//}
 }
?>
<script type="text/javascript">
// 价格若有更新就通知商务中心 start {
function price_updated_notice(obj){
	var oldVal = '';
	jQuery(obj).focus(function(){
		oldVal = jQuery(this).val();
	});
	jQuery(obj).blur(function(){
		if(jQuery(this).val()!=oldVal){
			jQuery('input[name="notice_customer_service"]').attr('checked',true);
		}
	});
}
jQuery(document).ready(function(){
	var objTags = "input[name^='products_single'],input[name^='products_double'],input[name^='products_triple'],input[name^='products_quadr'],input[name^='products_kids']";
	objTags += ",input[name^='price['],input[name^='single_price['],input[name^='double_price['],input[name^='triple_price['],input[name^='quadruple_price['],input[name^='kids_price[']";
	objTags += "input[name^='weekday'],input[name^='avaliable']";
	price_updated_notice(objTags);
});
// 价格若有更新就通知商务中心 end }

</script>