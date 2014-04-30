<?php
/*
WebMakers.com Added: webmakers_added_functions.php
Additional functions for the admin
*/


function tep_delete_products_attributes($delete_product_id) {
	// delete products attributes
	//  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "pad, " . TABLE_PRODUCT_ATTRIBUTES . 'pa where pa.products_id = '" . $delete_product_id . "'" . " and pad.products_attributes_id='" . pa.products_attributes_id . "'");

	// delete associated downloads
	$products_delete_from_query= tep_db_query("select pa.products_id, pad.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad  where pa.products_id='" . $delete_product_id . "' and pad.products_attributes_id= pa.products_attributes_id");
	while ( $products_delete_from=tep_db_fetch_array($products_delete_from_query) ) {
		tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $products_delete_from['products_attributes_id'] . "'");
	}
	//        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_copy_to_check['products_id'] . "'");

	tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $delete_product_id . "'");
}
function tep_copy_products_attributes($products_id_from,$products_id_to) {

	global $copy_attributes_delete_first, $copy_attributes_duplicates_skipped, $copy_attributes_duplicates_overwrite, $copy_attributes_include_downloads, $copy_attributes_include_filename;

	// $products_id_to= $copy_to_products_id;
	// $products_id_from = $pID;

	$products_copy_to_query= tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id='" . $products_id_to . "'");
	$products_copy_to_check_query= tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id='" . $products_id_to . "'");
	$products_copy_from_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_from . "'");
	$products_copy_from_check_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_from . "'");

	// Check for errors in copy request
	if (!$products_copy_from_check=tep_db_fetch_array($products_copy_from_check_query) or !$products_copy_to_check=tep_db_fetch_array($products_copy_to_check_query) or $products_id_to == $products_id_from ) {
		echo '<table width="100%"><tr>';
		if ($products_id_to == $products_id_from) {
			// same products_id
			echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: Cannot copy from Product ID #' . $products_id_from . ' to Product ID # ' . $products_id_to . ' ... No copy was made' . '</b>' . '</td>';
		} else {
			if (!$products_copy_from_check) {
				// no attributes found to copy
				echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: No Attributes to copy from Product ID #' . $products_id_from . ' for: ' . tep_get_products_name($products_id_from) . ' ... No copy was made' . '</b>' . '</td>';
			} else {
				// invalid products_id
				echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: There is no Product ID #' . $products_id_to . ' ... No copy was made' . '</b>' . '</td>';
			}
		}
		echo '</tr></table>';
	} else {

		if (false) { // Used for testing
			echo $products_id_from . 'x' . $products_id_to . '<br>';
			echo $copy_attributes_delete_first;
			echo $copy_attributes_duplicates_skipped;
			echo $copy_attributes_duplicates_overwrite;
			echo $copy_attributes_include_downloads;
			echo $copy_attributes_include_filename . '<br>';
		} // false for testing

		if ($copy_attributes_delete_first=='1') {
			// delete all attributes and downloads first
			$products_delete_from_query= tep_db_query("select pa.products_id, pad.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad  where pa.products_id='" . $products_id_to . "' and pad.products_attributes_id= pa.products_attributes_id");
			while ( $products_delete_from=tep_db_fetch_array($products_delete_from_query) ) {
				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $products_delete_from['products_attributes_id'] . "'");
			}
			tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_copy_to_check['products_id'] . "'");
		}

		while ( $products_copy_from=tep_db_fetch_array($products_copy_from_query) ) {
			$rows++;
			// This must match the structure of your products_attributes table
			// Current Field Order: products_attributes_id, options_values_price, price_prefix, products_options_sort_order, product_attributes_one_time, products_attributes_weight, products_attributes_weight_prefix, products_attributes_units, products_attributes_units_price
			// First test for existing attribute already being there

			$check_attribute_query= tep_db_query("select products_id, products_attributes_id, options_id, options_values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_to . "' and options_id='" . $products_copy_from['options_id'] . "' and options_values_id ='" . $products_copy_from['options_values_id'] . "'");
			$check_attribute= tep_db_fetch_array($check_attribute_query);
			// Check if there is a download with this attribute
			$check_attributes_download_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id ='" . $products_copy_from['products_attributes_id'] . "'");
			$check_attributes_download=tep_db_fetch_array($check_attributes_download_query);

			// Process Attribute
			$skip_it=false;
			switch (true) {
				case ($check_attribute and $copy_attributes_duplicates_skipped):
					// skip duplicate attributes
					//          echo 'DUPLICATE ' . ' Option ' . $products_copy_from['options_id'] . ' Value ' . $products_copy_from['options_values_id'] . ' Price ' . $products_copy_from['options_values_price'] . ' SKIPPED<br>';
					$skip_it=true;
					break;
				case (!$copy_attributes_include_downloads and $check_attributes_download['products_attributes_id']):
					// skip download attributes
					//          echo 'Download - ' . ' Attribute ID ' . $check_attributes_download['products_attributes_id'] . ' do not copy it<br>';
					$skip_it=true;
					break;
				default:
					//          echo '$check_attributes_download ' . $check_attributes_download['products_attributes_id'] . '<br>';
					if ($check_attributes_download['products_attributes_id']) {
						if (DOWNLOAD_ENABLED=='false' or !$copy_attributes_include_downloads) {
							// do not copy this download
							//              echo 'This is a download not to be copied <br>';
							$skip_it=true;
						} else {
							// copy this download
							//              echo 'This is a download to be copied <br>';
						}
					}

					// skip anything when $skip_it
					if (!$skip_it) {
						if ($check_attribute['products_id']) {
							// Duplicate attribute - update it
							//              echo 'Duplicate - Update ' . $check_attribute['products_id'] . ' Option ' . $check_attribute['options_id'] . ' Value ' . $check_attribute['options_values_id'] . ' Price ' . $products_copy_from['options_values_price'] . '<br>';

							$sql_data_array = array(
							'options_id' => tep_db_prepare_input($products_copy_from['options_id']),
							'options_values_id' => tep_db_prepare_input($products_copy_from['options_values_id']),
							'options_values_price' => tep_db_prepare_input($products_copy_from['options_values_price']),
							'price_prefix' => tep_db_prepare_input($products_copy_from['price_prefix']),
							'single_values_price' => tep_db_prepare_input($products_copy_from['single_values_price']),
							'double_values_price' => tep_db_prepare_input($products_copy_from['double_values_price']),
							'triple_values_price' => tep_db_prepare_input($products_copy_from['triple_values_price']),
							'quadruple_values_price' => tep_db_prepare_input($products_copy_from['quadruple_values_price']),
							'kids_values_price' => tep_db_prepare_input($products_copy_from['kids_values_price']),
							'products_options_sort_order' => tep_db_prepare_input($products_copy_from['products_options_sort_order']),
							'single_values_price_cost' => tep_db_prepare_input($products_copy_from['single_values_price_cost']),
							'double_values_price_cost' => tep_db_prepare_input($products_copy_from['double_values_price_cost']),
							'triple_values_price_cost' => tep_db_prepare_input($products_copy_from['triple_values_price_cost']),
							'quadruple_values_price_cost' => tep_db_prepare_input($products_copy_from['quadruple_values_price_cost']),
							'kids_values_price_cost' => tep_db_prepare_input($products_copy_from['kids_values_price_cost']),
							'options_values_price_cost' => tep_db_prepare_input($products_copy_from['options_values_price_cost'])

							);

							$cur_attributes_id = $check_attribute['products_attributes_id'];
							tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', 'products_id = \'' . tep_db_input($products_id_to) . '\' and products_attributes_id=\'' . tep_db_input($cur_attributes_id) . '\'');
						} else {
							// New attribute - insert it
							//              echo 'New - Insert ' . 'Option ' . $products_copy_from['options_id'] . ' Value ' . $products_copy_from['options_values_id']  . ' Price ' . $products_copy_from['options_values_price'] . '<br>';
							tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id_to . "', '" . $products_copy_from['options_id'] . "', '" . $products_copy_from['options_values_id'] . "', '" . $products_copy_from['options_values_price'] . "', '" . $products_copy_from['price_prefix'] . "',  '" . $products_copy_from['single_values_price'] . "',
'" . $products_copy_from['double_values_price'] . "',
'" . $products_copy_from['triple_values_price'] . "',
'" . $products_copy_from['quadruple_values_price'] . "',
'" . $products_copy_from['kids_values_price'] . "',
'" . $products_copy_from['products_options_sort_order'] . "',
'" . $products_copy_from['single_values_price_cost'] . "',
'" . $products_copy_from['double_values_price_cost'] . "',
'" . $products_copy_from['triple_values_price_cost'] . "',
'" . $products_copy_from['quadruple_values_price_cost'] . "',
'" . $products_copy_from['kids_values_price_cost'] . "',
'" . $products_copy_from['options_values_price_cost'] . "'
) ");
						}

						// Manage download attribtues
						if (DOWNLOAD_ENABLED == 'true') {
							if ($check_attributes_download and $copy_attributes_include_downloads) {
								// copy download attributes
								//                echo 'Download - ' . ' Attribute ID ' . $check_attributes_download['products_attributes_id'] . ' ' . $check_attributes_download['products_attributes_filename'] . ' copy it<br>';
								$new_attribute_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_to . "' and options_id='" . $products_copy_from['options_id'] . "' and options_values_id ='" . $products_copy_from['options_values_id'] . "'");
								$new_attribute= tep_db_fetch_array($new_attribute_query);

								$sql_data_array = array(
								'products_attributes_id' => tep_db_prepare_input($new_attribute['products_attributes_id']),
								'products_attributes_filename' => tep_db_prepare_input($check_attributes_download['products_attributes_filename']),
								'products_attributes_maxdays' => tep_db_prepare_input($check_attributes_download['products_attributes_maxdays']),
								'products_attributes_maxcount' => tep_db_prepare_input($check_attributes_download['products_attributes_maxcount'])
								);

								$cur_attributes_id = $check_attribute['products_attributes_id'];
								tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD, $sql_data_array);
							}
						}
					} // $skip_it
					break;
			} // end of switch
		} // end of products attributes while loop
	} // end of no attributes or other errors
} // eof: tep_copy_products_attributes
////
// Check if product has attributes
function tep_has_product_attributes($products_id) {
	$attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "'");
	$attributes = tep_db_fetch_array($attributes_query);

	if ($attributes['count'] > 0) {
		return true;
	} else {
		return false;
	}
}



/**
 * 取得后台管理人员名称，默认是选工号
 *
 * @param unknown_type $groups_id
 */

function tep_get_admin_customer_name($admin_id='', $type = 'job_num'){
	if($admin_id==CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID) return 'CSR System';

	$the_admin_customer_query= tep_db_query("select admin_firstname, admin_lastname, admin_email_address, admin_job_number  from " . TABLE_ADMIN . " where admin_id = '" . $admin_id . "'");
	$the_admin_customer = tep_db_fetch_array($the_admin_customer_query);
	if($type=='job_num'){	//显示工号
		$str_admin = $the_admin_customer['admin_job_number'];
	}
	if(preg_replace('/[[:space:]]+/','',$str_admin)==""){	//工号为空时才使用姓名
		if($the_admin_customer['admin_firstname'] != ''){
			$str_admin = $the_admin_customer['admin_firstname'].' '.$the_admin_customer['admin_lastname'];
		}else{
			$str_admin = $the_admin_customer['admin_email_address'];
		}
	}
	return $str_admin;
}
/**
 * 根据紧急程度值取得订单下一步理人紧急程度的中文名称
 * @param boolean $getColor 是否取还颜色值的数组,默认[false]不取。取与不取返回的数组格式不一样，须注意
 * @param string $urgency_value 具体值[all所有,或者已知的KEY，返回对应的中文名称]
 * @return array
 */
function tep_get_need_next_urgency_name($urgency_value = 'all',$getColor = false){
	//$array = array('general'=>'<span>普通</span>', 'urgent'=>'<span style="color:red">紧急</span>', 'very_urgent'=>'<b style="color:red">非常紧急<b>');
	$urgency_value = strtolower($urgency_value);
	$str_sql = "select name,text,color from orders_status_urgency group by name";
	$result = tep_db_query($str_sql);
	$array = array();
	while($rs = tep_db_fetch_array($result)) {
		if($getColor == true) {
			$array[] = $rs;
		} else {
			$array[$rs['name']] = $rs['text'];
		}
		if ($urgency_value != 'all' && $urgency_value == strtolower($rs['name'])) {
			return '<span style="color:' . $rs['color'] . '">' . $rs['text'] . '</span>';
		}
	}
	if($urgency_value == 'all'){
		return $array;
	}
	return '';
}

function tep_get_admin_customer_email($admin_id=''){
	$the_admin_customer_query= tep_db_query("select admin_email_address  from " . TABLE_ADMIN . " where admin_id = '" . $admin_id . "'");
	$the_admin_customer = tep_db_fetch_array($the_admin_customer_query);
	return $the_admin_customer['admin_email_address'];
}



//dispaly specific attribute  price start

function attributes_price_display($products_id,$option,$value) {
	global $g_number, $total_room_adult_child_info, $hotel_checkout_date, $finaldate;

	$attributes_price = 0;

	$attribute_price_query = tep_db_query("select options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
	while ($attribute_price = tep_db_fetch_array($attribute_price_query)) {

		//amit modified to make sure price on usd
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
		if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
			$attribute_price['single_values_price'] = tep_get_tour_price_in_usd($attribute_price['single_values_price'],$tour_agency_opr_currency);
			$attribute_price['double_values_price'] = tep_get_tour_price_in_usd($attribute_price['double_values_price'],$tour_agency_opr_currency);
			$attribute_price['triple_values_price'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price'],$tour_agency_opr_currency);
			$attribute_price['quadruple_values_price'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price'],$tour_agency_opr_currency);
			$attribute_price['kids_values_price'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price'],$tour_agency_opr_currency);
			$attribute_price['options_values_price'] = tep_get_tour_price_in_usd($attribute_price['options_values_price'],$tour_agency_opr_currency);
		}
		//amit modified to make sure price on usd

		//new change start
		$att[1] = $attribute_price['single_values_price']; //single
		$att[2] = $attribute_price['double_values_price']; //double
		$att[3] = $attribute_price['triple_values_price']; //triple
		$att[4] = $attribute_price['quadruple_values_price']; //quadr
		$ett = $attribute_price['kids_values_price']; //child Kid
		//need to change logic according to new added field

		// the attribute price per day for hotel code should be here
		$option_name = tep_get_product_option_name_from_optionid((int)$option);
		if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/???x?裨绮皖??e/i", strtolower($option_name)) || preg_match("/请选择早餐类别/i", strtolower($option_name)) ){
			$loop_start = strtotime($finaldate);
			$loop_end = strtotime($hotel_checkout_date);
			$loop_increment = (24*60*60);
			$hotel_stay_days = (int)($loop_end-$loop_start)/$loop_increment;
			$att[1] = $attribute_price['single_values_price'] = $attribute_price['single_values_price'] * $hotel_stay_days; //single
			$att[2] = $attribute_price['double_values_price'] = $attribute_price['double_values_price'] * $hotel_stay_days; //single
			$att[3] = $attribute_price['triple_values_price'] = $attribute_price['triple_values_price'] * $hotel_stay_days; //single
			$att[4] = $attribute_price['quadruple_values_price'] = $attribute_price['quadruple_values_price'] * $hotel_stay_days; //single
			$ett = $attribute_price['kids_values_price'] = $attribute_price['kids_values_price'] * $hotel_stay_days; //single
			$attribute_price['options_values_price'] = $attribute_price['options_values_price'] * $hotel_stay_days;

		}

		//amit added to check apply attribute per order start
		$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
		//amit added to check apply attribute per order end
		if(($attribute_price['single_values_price'] > 0 || $attribute_price['double_values_price'] > 0 || $attribute_price['triple_values_price'] > 0 || $attribute_price['quadruple_values_price'] > 0) && $is_per_order_attribute != 1 ){

			$final_chk_price = 0;
			//check for old data stored on customer bascket start
			if($total_room_adult_child_info == ''){

				$final_chk_price = $attribute_price['single_values_price']*$g_number;
			}else{

				//check for old data stored on customer bascket end
				$tot_nn_roms_chked = get_total_room_from_str($total_room_adult_child_info);

				if($tot_nn_roms_chked > 0){
					for($ri=1;$ri<=$tot_nn_roms_chked;$ri++){

						$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($total_room_adult_child_info,$ri);

						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,$ri);

						if($chaild_adult_no_arr[1] == 0){
							$final_chk_price += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
						}else{

							if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
								$low_price_formula_option[0] = 2*$att[2];
								$low_price_formula_option[1] = $att[1]+$ett;
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
								$low_price_formula_option[0] = (2*$att[2]) + $ett;
								$low_price_formula_option[1] = 3*$att[3];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
								$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
								$low_price_formula_option[1] = 3*$att[3]+$ett;
								$low_price_formula_option[2] = 4*$att[4];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
							}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
								$low_price_formula_option[0] = (2*$att[2]) + $ett;
								$low_price_formula_option[1] = 3*$att[3];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
								$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
								$low_price_formula_option[1] = 3*$att[3]+$ett;
								$low_price_formula_option[2] = 4*$att[4];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
							}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
								$low_price_formula_option[0] = 3*$att[3]+$ett;
								$low_price_formula_option[1] = 4*$att[4];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}

						}


					}
				}else{
					//tour with no room option but special price added

					/*
					$final_chk_price = $attribute_price['single_values_price']*$g_number;
					*/
					$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($total_room_adult_child_info,1);
					$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,1);
					$final_chk_price = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;

				}

			}


			if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $final_chk_price;
			} else {
				$attributes_price += $final_chk_price;
			}

			//echo (int)$products_id.'-->'.$attributes_price.'<br>';

		}else{

			//amit added to check apply attribute per order start
			$temp_default_per_count = $g_number;
			if($is_per_order_attribute == 1){
				$temp_default_per_count = 1; // per order attribute
			}
			//amit added to check apply attribute per order end
			if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $attribute_price['options_values_price']*$temp_default_per_count;
			} else {
				$attributes_price += $attribute_price['options_values_price']*$temp_default_per_count;
			}
		}
		//new change end

	}

	return $attributes_price;
}

//display specific attribute price end

function attributes_price_display_cost($products_id,$option,$value) {
	global $g_number, $total_room_adult_child_info, $hotel_checkout_date, $finaldate;

	$attributes_price = 0;

	$attribute_price_query = tep_db_query("select options_values_price_cost, price_prefix, single_values_price_cost, double_values_price_cost, triple_values_price_cost, quadruple_values_price_cost, kids_values_price_cost  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
	while ($attribute_price = tep_db_fetch_array($attribute_price_query)) {

		//amit modified to make sure price on usd
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
		if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
			$attribute_price['single_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['single_values_price_cost'],$tour_agency_opr_currency);
			$attribute_price['double_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['double_values_price_cost'],$tour_agency_opr_currency);
			$attribute_price['triple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price_cost'],$tour_agency_opr_currency);
			$attribute_price['quadruple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price_cost'],$tour_agency_opr_currency);
			$attribute_price['kids_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price_cost'],$tour_agency_opr_currency);
			$attribute_price['options_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['options_values_price_cost'],$tour_agency_opr_currency);
		}
		//amit modified to make sure price on usd

		//new change start
		$att[1] = $attribute_price['single_values_price_cost']; //single
		$att[2] = $attribute_price['double_values_price_cost']; //double
		$att[3] = $attribute_price['triple_values_price_cost']; //triple
		$att[4] = $attribute_price['quadruple_values_price_cost']; //quadr
		$ett = $attribute_price['kids_values_price_cost']; //child Kid
		//need to change logic according to new added field

		// the attribute price per day for hotel code should be here
		$option_name = tep_get_product_option_name_from_optionid((int)$option);
		if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/???x?裨绮皖??e/i", strtolower($option_name)) || preg_match("/请选择早餐类别/i", strtolower($option_name)) ){
			$loop_start = strtotime($finaldate);
			$loop_end = strtotime($hotel_checkout_date);
			$loop_increment = (24*60*60);
			$hotel_stay_days = (int)($loop_end-$loop_start)/$loop_increment;
			$att[1] = $attribute_price['single_values_price_cost'] = $attribute_price['single_values_price_cost'] * $hotel_stay_days; //single
			$att[2] = $attribute_price['double_values_price_cost'] = $attribute_price['double_values_price_cost'] * $hotel_stay_days; //single
			$att[3] = $attribute_price['triple_values_price_cost'] = $attribute_price['triple_values_price_cost'] * $hotel_stay_days; //single
			$att[4] = $attribute_price['quadruple_values_price_cost'] = $attribute_price['quadruple_values_price_cost'] * $hotel_stay_days; //single
			$ett = $attribute_price['kids_values_price_cost'] = $attribute_price['kids_values_price_cost'] * $hotel_stay_days; //single
			$attribute_price['options_values_price_cost'] = $attribute_price['options_values_price_cost'] * $hotel_stay_days;

		}
		//amit added to check apply attribute per order start
		$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
		//amit added to check apply attribute per order end
		if(($attribute_price['single_values_price_cost'] > 0 || $attribute_price['double_values_price_cost'] > 0 || $attribute_price['triple_values_price_cost'] > 0 || $attribute_price['quadruple_values_price_cost'] > 0) && $is_per_order_attribute != 1 ){

			$final_chk_price = 0;
			//check for old data stored on customer bascket start
			if($total_room_adult_child_info == ''){

				$final_chk_price = $attribute_price['single_values_price_cost']*$g_number;
			}else{

				//check for old data stored on customer bascket end
				$tot_nn_roms_chked = get_total_room_from_str($total_room_adult_child_info);

				if($tot_nn_roms_chked > 0){
					for($ri=1;$ri<=$tot_nn_roms_chked;$ri++){

						$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($total_room_adult_child_info,$ri);

						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,$ri);

						if($chaild_adult_no_arr[1] == 0){
							$final_chk_price += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
						}else{

							if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
								$low_price_formula_option[0] = 2*$att[2];
								$low_price_formula_option[1] = $att[1]+$ett;
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
								$low_price_formula_option[0] = (2*$att[2]) + $ett;
								$low_price_formula_option[1] = 3*$att[3];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
								$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
								$low_price_formula_option[1] = 3*$att[3]+$ett;
								$low_price_formula_option[2] = 4*$att[4];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
							}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
								$low_price_formula_option[0] = (2*$att[2]) + $ett;
								$low_price_formula_option[1] = 3*$att[3];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
								$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
								$low_price_formula_option[1] = 3*$att[3]+$ett;
								$low_price_formula_option[2] = 4*$att[4];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
							}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
								$low_price_formula_option[0] = 3*$att[3]+$ett;
								$low_price_formula_option[1] = 4*$att[4];
								$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
							}

						}


					}
				}else{
					//tour with no room option but special price added

					$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($total_room_adult_child_info,1);
					$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,1);
					$final_chk_price = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;

				}

			}


			if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $final_chk_price;
			} else {
				$attributes_price += $final_chk_price;
			}

			//echo (int)$products_id.'-->'.$attributes_price.'<br>';

		}else{

			//amit added to check apply attribute per order start
			$temp_default_per_count = $g_number;
			if($is_per_order_attribute == 1){
				$temp_default_per_count = 1; // per order attribute
			}
			//amit added to check apply attribute per order end
			if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $attribute_price['options_values_price_cost']*$temp_default_per_count;
			} else {
				$attributes_price += $attribute_price['options_values_price_cost']*$temp_default_per_count;
			}
		}
		//new change end

	}

	return $attributes_price;
}

//display specific attribute price end
/**
 * 取得房间总数（从房间信息字符串）
 *
 * @param unknown_type $str
 * @return unknown
 */
function get_total_room_from_str($str){
	$get_room_no_array = explode('###',$str);
	return $get_room_no_array[0];
}

function tep_get_room_total_persion_from_str($str,$room_no){
	$get_room_no_array = explode('###',$str);
	$adu_and_chile_val_array =	explode('!!',$get_room_no_array[$room_no]);
	$total_ad_ch_val = $adu_and_chile_val_array[0] + $adu_and_chile_val_array[1];
	return $total_ad_ch_val;
}

function tep_get_room_adult_child_persion_on_room_str($str,$room_no){
	$get_room_no_array = explode('###',$str);
	$adu_and_chile_val_array =	explode('!!',$get_room_no_array[$room_no]);

	$total_ad_ch_val_array[0] = $adu_and_chile_val_array[0];
	$total_ad_ch_val_array[1] = $adu_and_chile_val_array[1];

	return $total_ad_ch_val_array;
}

/**
 * 取得参团人员总数
 * @param string $str 为房间信息的字符串 3###2!!0###2!!2###2!!1###或0###2!!1###
 * @return int
 */
function tep_get_travel_adult_child_total($str){
	$num = 0;
	$array = tep_get_room_info_array_from_str($str);
	for($i=0,$n=sizeof($array); $i<$n; $i++){
		$num += $array[$i]['adultNum'] + $array[$i]['childNum'];
	}
	if($num==0){
		$num = $array['adult_num'] + $array['child_num'];
	}
	return $num;
}

/**
 * 将房间信息字符串转成数组
 * @param unknown_type $str 为房间信息的字符串 3###2!!0###2!!2###2!!1###或0###2!!1###
 * @return array
 * @author Howard
 */
function tep_get_room_info_array_from_str($str){
	if(!tep_not_null($str)) return false;
	$data = array();
	$get_room_no_array = explode('###',$str);
	//房间总数
	$data['room_total'] = (int)$get_room_no_array[0];
	//各个房间成人和小孩数据
	if(!(int)$data['room_total']){
		$_tmp_array = explode('!!',$get_room_no_array[1]);
		$data['adult_num'] = (int)$_tmp_array[0];
		$data['child_num'] = (int)$_tmp_array[1];
	}else{
		for($i=1, $n=count($get_room_no_array); $i<$n; $i++){
			$_tmp_array = array();
			if(tep_not_null($get_room_no_array[$i])){
				$_tmp_array = explode('!!',$get_room_no_array[$i]);
				$data[] = array('adultNum'=> (int)$_tmp_array[0], 'childNum'=> (int)$_tmp_array[1]);
			}
		}
	}
	return $data;
}

/**
 * 转换日期格式：'2008-12-31' 格式转到 '12/31/2008' 格式
 *
 * @param date $date
 * @return date
 */
function tep_get_date_disp($date)
{
	if($date!='')
	{
		$date_disp = strtotime($date);
		$date_return = date("m/d/Y",$date_disp);
	}
	else
	{
		$date_return='';
	}
	return $date_return;
}

/**
 * 将日期格式化成数据库的标准日期格式
 * @param $date 日期字符
 * @param $outFormat 输出的日期格式，默认为Y-m-d
 * @example tep_get_date_db('12/31/2008') change the date format from '12/31/2008' to '2008-12-31'
 * @author Howard
 */
function tep_get_date_db($date,$outFormat='Y-m-d'){
	/*if($date!='')
	{
	$date_disp = explode("/",$date);
	$date_return = $date_disp[2].'-'.$date_disp[0].'-'.$date_disp[1];
	}
	else
	{
	$date_return='';
	}
	*/
	$date_return = '';
	if(tep_not_null($date)){
		$date_return = date($outFormat,strtotime($date));
	}

	return $date_return;
}


function tep_get_products_name_model_from_orders_products($orders_products_id) {
	/*
	$product_query = tep_db_query("select products_id, products_name, products_model, orders_id, final_invoice_amount, final_price, final_price_cost  from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '" . (int)$orders_products_id . "'");

	*/

	$product_query = tep_db_query("select op.products_id, op.products_name, op.products_model, op.orders_id, op.final_invoice_amount, op.final_price, op.final_price_cost, oph.invoice_number, oph.invoice_amount, op.products_departure_date from   ".TABLE_ORDERS_PRODUCTS." as op left join ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." as oph on oph.orders_products_id = op.orders_products_id and  ord_prod_history_id IN (SELECT max(ord_prod_history_id) FROM ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." where orders_products_id = '" . (int)$orders_products_id . "'  GROUP BY orders_products_id) where op.orders_products_id = '" . (int)$orders_products_id . "'");


	$product = tep_db_fetch_array($product_query);
	return $product;
}

function tep_get_products_prov_tourcode_from_orders_products($orders_products_id) {

	$product_query = tep_db_query("select p.provider_tour_code from " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_PRODUCTS." p  where op.products_id = p.products_id and op.orders_products_id = '" . (int)$orders_products_id . "'");
	$product = tep_db_fetch_array($product_query);
	return $product;
}
function tep_get_total_of_room($i){
	if(defined('TEXT_TOTLE_OF_ROOM'.$i)){
		return constant('TEXT_TOTLE_OF_ROOM'.$i);
	}
	/*		if($i == "1"){
	return TEXT_TOTLE_OF_ROOM1;
	}else if($i == "2"){
	return TEXT_TOTLE_OF_ROOM2;
	}else if($i == "3"){
	return TEXT_TOTLE_OF_ROOM3;
	}else if($i == "4"){
	return TEXT_TOTLE_OF_ROOM4;
	}else if($i == "5"){
	return TEXT_TOTLE_OF_ROOM5;
	}else if($i == "6"){
	return TEXT_TOTLE_OF_ROOM6;
	}
	*/
}

function tep_get_total_of_adult_in_room($i){
	if(defined('TEXT_OF_ADULTS_IN_ROOM'.$i)){
		return constant('TEXT_OF_ADULTS_IN_ROOM'.$i);
	}
	/*		if($i == "1"){
	return TEXT_OF_ADULTS_IN_ROOM1;
	}else if($i == "2"){
	return TEXT_OF_ADULTS_IN_ROOM2;
	}else if($i == "3"){
	return TEXT_OF_ADULTS_IN_ROOM3;
	}else if($i == "4"){
	return TEXT_OF_ADULTS_IN_ROOM4;
	}else if($i == "5"){
	return TEXT_OF_ADULTS_IN_ROOM5;
	}else if($i == "6"){
	return TEXT_OF_ADULTS_IN_ROOM6;
	}
	*/
}
function tep_get_total_of_children_in_room($i){
	if(defined('TEXT_OF_CHILDREN_IN_ROOM'.$i)){
		return constant('TEXT_OF_CHILDREN_IN_ROOM'.$i);
	}
	/*		if($i == "1"){
	return TEXT_OF_CHILDREN_IN_ROOM1;
	}else if($i == "2"){
	return TEXT_OF_CHILDREN_IN_ROOM2;
	}else if($i == "3"){
	return TEXT_OF_CHILDREN_IN_ROOM3;
	}else if($i == "4"){
	return TEXT_OF_CHILDREN_IN_ROOM4;
	}else if($i == "5"){
	return TEXT_OF_CHILDREN_IN_ROOM5;
	}else if($i == "6"){
	return TEXT_OF_CHILDREN_IN_ROOM6;
	}
	*/
}
//取得$i床型标题
function tep_get_bed_of_room($i){

	if($i == "1"){
		return TEXT_BED_OF_ROOM1;
	}else if($i == "2"){
		return TEXT_BED_OF_ROOM2;
	}else if($i == "3"){
		return TEXT_BED_OF_ROOM3;
	}else if($i == "4"){
		return TEXT_BED_OF_ROOM4;
	}else if($i == "5"){
		return TEXT_BED_OF_ROOM5;
	}else if($i == "6"){
		return TEXT_BED_OF_ROOM6;
	}
}
function tep_get_bed_name($n){
	if($n == "0"){ return TEXT_BED_STANDARD; }
	if($n == "1"){ return TEXT_BED_KING; }
	if($n == "2"){ return TEXT_BED_QUEEN; }
}

function tour_code_encode($string) {
	return $string;
	/*
	$n = TEXT_TOUR_CODE_ENCODE_ROTATE_VALUE;
	$length = strlen($string);
	$result = '';

	for($i = 0; $i < $length; $i++) {
	$ascii = ord($string{$i});

	$rotated = $ascii;

	if ($ascii > 64 && $ascii < 91) {
	$rotated += $n;
	$rotated > 90 && $rotated += -90 + 64;
	$rotated < 65 && $rotated += -64 + 90;
	} elseif ($ascii > 96 && $ascii < 123) {
	$rotated += $n;
	$rotated > 122 && $rotated += -122 + 96;
	$rotated < 97 && $rotated += -96 + 122;
	}

	$result .= chr($rotated);
	}

	return $result;
	*/
}

function tour_code_decode($string) {
	$n = -(int)TEXT_TOUR_CODE_ENCODE_ROTATE_VALUE;
	$length = strlen($string);
	$result = '';

	for($i = 0; $i < $length; $i++) {
		$ascii = ord($string{$i});

		$rotated = $ascii;

		if ($ascii > 64 && $ascii < 91) {
			$rotated += $n;
			$rotated > 90 && $rotated += -90 + 64;
			$rotated < 65 && $rotated += -64 + 90;
		} elseif ($ascii > 96 && $ascii < 123) {
			$rotated += $n;
			$rotated > 122 && $rotated += -122 + 96;
			$rotated < 97 && $rotated += -96 + 122;
		}

		$result .= chr($rotated);
	}

	return $result;
}

function tep_get_rand_color($row_no) {
	if((int)$row_no < 0 || $row_no > 10){
		$row_no = 0;
	}

	$rand_color[0] = '#D2E9FF';
	$rand_color[1] = '#EAD5FF';
	$rand_color[2] = '#FFC4C4';
	$rand_color[3] = '#bbf29b';
	$rand_color[4] = '#FFD2E9';
	$rand_color[5] = '#EDEEB7';
	$rand_color[6] = '#73dff9';
	$rand_color[7] = '#B6F3F3';
	$rand_color[8] = '#a1b9d6';
	$rand_color[9] = '#B9B7D0';
	$rand_color[10] = '#eadfc6';
	return $rand_color[$row_no]; // returns color
}

function imageCompression($imgfile="",$thumbsize=0,$savePath=NULL,$new_width=0,$new_height=0) {
	/*echo $imgfile;
	echo '<br />';
	echo $thumbsize;
	echo '<br />';
	echo $savePath;
	echo '<br />';*/
	if($savePath==NULL) {
		header('Content-type: image/jpeg');
		/* To display the image in browser

		*/

	}
	list($width,$height)=getimagesize($imgfile);
	/* The width and the height of the image also the getimagesize retrieve other information as well   */
	//echo $width;
	//echo $height;

	if($new_width==0 && $new_height==0) {
		$imgratio=$width/$height;
		/*
		To compress the image we will calculate the ration
		For eg. if the image width=700 and the height = 921 then the ration is 0.77...
		if means the image must be compression from its height and the width is based on its height
		so the newheight = thumbsize and the newwidth is thumbsize*0.77...
		*/

		if($imgratio>1) {
			$newwidth=$thumbsize;
			$newheight=$thumbsize/$imgratio;
		} else {
			$newheight=$thumbsize;
			$newwidth=$thumbsize*$imgratio;
		}
	}else{
		$newwidth=$new_width;
		$newheight=$new_height;
	}
	$thumb=imagecreatetruecolor($newwidth,$newheight); // Making a new true color image
	//$source=imagecreatefromjpeg($imgfile); // Now it will create a new image from the source

	$source=imagecreatefromfile($imgfile); // Now it will create a new image from the source
	imagecopyresampled($thumb,$source,0,0,0,0,$newwidth,$newheight,$width,$height);  // Copy and resize the image

	//imagejpeg($thumb,$savePath,100);
	imagejpeg($thumb,$savePath,93);
	/*
	Out put of image
	if the $savePath is null then it will display the image in the browser
	*/
	imagedestroy($thumb);
	/*
	Destroy the image
	*/

}

//if the file is not in jpg format function for other files
function imagecreatefromfile($path, $user_functions = false)
{
	$info = @getimagesize($path);

	if(!$info)
	{
		return false;
	}

	$functions = array(
	IMAGETYPE_GIF => 'imagecreatefromgif',
	IMAGETYPE_JPEG => 'imagecreatefromjpeg',
	IMAGETYPE_PNG => 'imagecreatefrompng',
	IMAGETYPE_WBMP => 'imagecreatefromwbmp',
	IMAGETYPE_XBM => 'imagecreatefromwxbm',
	);

	if($user_functions)
	{
		$functions[IMAGETYPE_BMP] = 'imagecreatefrombmp';
	}

	if(!$functions[$info[2]])
	{
		return false;
	}

	if(!function_exists($functions[$info[2]]))
	{
		return false;
	}

	return $functions[$info[2]]($path);
}

function is_leaf_category($categories_id)
{
	$check_leaf_query = tep_db_query("select parent_id from ".TABLE_CATEGORIES." where parent_id=".$categories_id);
	if(tep_db_num_rows($check_leaf_query)==0)
	{
		$is_leaf = 1;
	}
	else
	{
		$is_leaf = 0;
	}
	return $is_leaf;
}

/**
* 自动生成产品的团号 products_model
* 产品的CODE公式是出发城市代码+供应商ID+"-"+产品ID
*/
function tep_get_auto_generate_tourcode($model,$agency_id,$city_id,$products_id){
	$auto_generated_model='';
	if((int)$city_id && (int)$agency_id){
		$sql = tep_db_query('SELECT c.countries_iso_code_2, tc.city_code FROM `tour_city` tc, `regions` r, `countries` c where tc.city_id ="'.(int)$city_id.'" and tc.regions_id=r.regions_id and r.countries_id=c.countries_id and tc.departure_city_status="1" group by tc.city_id ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['city_code']) && tep_not_null($row['countries_iso_code_2'])){
			//$auto_generated_model =$row['countries_iso_code_2'].$row['city_code'].$agency_id; 不要国家代码了
			$auto_generated_model =$row['city_code'].$agency_id;
			$auto_generated_model .= '-'.(int)$products_id;
		}
	}
	/*
	if($agency_id==GLOBUS_AGENCY_ID){
	$auto_generated_model = $model;
	}else{
	//$auto_generated_model = $model.'-'.$agency_id;
	$auto_generated_model = tep_get_departure_city_code_from_city_id($city_id);
	$auto_generated_model .= $agency_id.'-'.$products_id;
	}
	*/

	return $auto_generated_model;
}

function tep_get_departure_city_code_from_city_id($city_id)
{
	if($city_id ==''){
		$city_id = 0;
	}
	$display_str_departure_city = '';
	$cityquery = tep_db_query("select c.city_id, c.city, c.city_code, co.countries_iso_code_2 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co  where  c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in (".$city_id.") order by c.city");
	while($cityclass = tep_db_fetch_array($cityquery)){
		if($cityclass['city_code']!=''){
			$display_str_departure_city .= trim($cityclass['city_code']);
		}
		$countries_iso_code_3 = trim($cityclass['countries_iso_code_2']);
	}

	return $countries_iso_code_3.$display_str_departure_city;
}




function tep_copy_reg_irreg_date($products_id_from,$products_id_to)
{

	if ($products_id_to == $products_id_from) {
		// same products_id
		echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: Cannot copy from Product ID #' . $products_id_from . ' to Product ID # ' . $products_id_to . ' ... No copy was made' . '</b>' . '</td>';
	}
	else
	{
		$select_reg_irreg_date = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id=".$products_id_from."");
		if(tep_db_num_rows($select_reg_irreg_date)>0)
		{
			while($row_reg_irreg_date = tep_db_fetch_array($select_reg_irreg_date))
			{

				$sql_data_irregular_date = array('products_id' => $products_id_to,
				'products_start_day'=> tep_db_prepare_input($row_reg_irreg_date['products_start_day']),
				'available_date' => tep_db_prepare_input($row_reg_irreg_date['available_date']),
				'extra_charge' => tep_db_prepare_input($row_reg_irreg_date['extra_charge']),
				'spe_single' => tep_db_prepare_input($row_reg_irreg_date['spe_single']),
				'spe_double' => tep_db_prepare_input($row_reg_irreg_date['spe_double']),
				'spe_triple' => tep_db_prepare_input($row_reg_irreg_date['spe_triple']),
				'spe_quadruple' => tep_db_prepare_input($row_reg_irreg_date['spe_quadruple']),
				'spe_kids' => tep_db_prepare_input($row_reg_irreg_date['spe_kids']),
				'prefix' => tep_db_prepare_input($row_reg_irreg_date['prefix']),
				'sort_order' => tep_db_prepare_input($row_reg_irreg_date['sort_order']),
				'operate_start_date' => tep_db_prepare_input($row_reg_irreg_date['operate_start_date']),
				'operate_end_date' => tep_db_prepare_input($row_reg_irreg_date['operate_end_date']),
				'products_durations_description' => tep_db_prepare_input($row_reg_irreg_date['products_durations_description']),
				'extra_charge_cost' => tep_db_prepare_input($row_reg_irreg_date['extra_charge_cost']),
				'spe_single_cost' => tep_db_prepare_input($row_reg_irreg_date['spe_single_cost']),
				'spe_double_cost' => tep_db_prepare_input($row_reg_irreg_date['spe_double_cost']),
				'spe_triple_cost' => tep_db_prepare_input($row_reg_irreg_date['spe_triple_cost']),
				'spe_quadruple_cost' => tep_db_prepare_input($row_reg_irreg_date['spe_quadruple_cost']),
				'spe_kids_cost' => tep_db_prepare_input($row_reg_irreg_date['spe_kids_cost'])
				);

				tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_date);
			}
		}

		//=====================//

		$select_reg_irreg_desc = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_DESCRIPTION." where products_id=".$products_id_from."");
		if(tep_db_num_rows($select_reg_irreg_desc)>0)
		{
			while($row_reg_irreg_desc = tep_db_fetch_array($select_reg_irreg_desc))
			{
				$sql_data_irregular_desc = array('products_id' => $products_id_to,
				'products_durations_description'=> tep_db_prepare_input($row_reg_irreg_desc['products_durations_description']),
				'operate_start_date'=> tep_db_prepare_input($row_reg_irreg_desc['operate_start_date']),
				'operate_end_date'=> tep_db_prepare_input($row_reg_irreg_desc['operate_end_date']),
				);

				tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_irregular_desc);
			}
		}

		//===============//

		$select_product_destination = tep_db_query("select * from ".TABLE_PRODUCTS_DESTINATION." where products_id=".$products_id_from."");
		if(tep_db_num_rows($select_product_destination)>0)
		{
			while($row_product_destination = tep_db_fetch_array($select_product_destination))
			{
				$sql_data_product_destination = array('products_id' => $products_id_to,
				'city_id'=> tep_db_prepare_input($row_product_destination['city_id'])
				);

				tep_db_perform(TABLE_PRODUCTS_DESTINATION, $sql_data_product_destination);
			}
		}

		//===============//

		$select_product_departure = tep_db_query("select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id=".$products_id_from."");
		if(tep_db_num_rows($select_product_departure)>0)
		{
			while($row_product_departure = tep_db_fetch_array($select_product_departure))
			{
				$sql_data_product_departure = array('products_id' => $products_id_to,
				'departure_region'=> tep_db_prepare_input($row_product_departure['departure_region']),
				'departure_address'=> tep_db_prepare_input($row_product_departure['departure_address']),
				'departure_time'=> tep_db_prepare_input($row_product_departure['departure_time']),
				'map_path'=> tep_db_prepare_input($row_product_departure['map_path']),
				'departure_full_address'=> tep_db_prepare_input($row_product_departure['departure_full_address']),
				);

				tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_product_departure);
			}
		}

	}
}


function tep_get_provider_tourcode($products_id){
	$get_provider_tourcode = tep_db_query("select provider_tour_code from ".TABLE_PRODUCTS." where products_id = '".$products_id."'");
	$row_provider_tourcode = tep_db_fetch_array($get_provider_tourcode);
	return $row_provider_tourcode['provider_tour_code'];
}

/**
 * 取得订单行程中组合团子团$sub_products_id的出发日期
 *
 * @param int $orders_id 订单号
 * @param int $products_id 主产品ID
 * @param int $sub_products_id 子产品ID
 */
function tep_get_sub_orders_products_departure_date($orders_id, $products_id, $sub_products_id){
	$sql = tep_db_query('SELECT products_departure_date FROM `orders_products` WHERE orders_id ="'.(int)$orders_id.'" AND products_id ="'.(int)$products_id.'" ');
	$row = tep_db_fetch_array($sql);
	$row['products_departure_date'] = trim($row['products_departure_date']);
	$startDateTime = strtotime($row['products_departure_date']);
	if(tep_not_null($row['products_departure_date'])){
		$sql = tep_db_query('SELECT products_model_sub FROM `products` WHERE products_id ="'.(int)$products_id.'" ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['products_model_sub'])){
			$tmp_products_model_array = explode(';',$row['products_model_sub']);
			for($i=0, $n=sizeof($tmp_products_model_array); $i<$n;$i++){
				$t_product_id = tep_get_products_id_from_model(trim($tmp_products_model_array[$i]));
				$prodSql = tep_db_query('SELECT products_durations, products_durations_type FROM `products` WHERE products_id="'.(int)$t_product_id.'" ');
				$prodRow = tep_db_fetch_array($prodSql);
				$_day = 0;
				if($prodRow['products_durations_type']=="0"){
					$_day = $prodRow['products_durations']*86400;
				}
				if($i>0 && $t_product_id==$sub_products_id){
					return date('Y-m-d l',$startDateTime-(1*86400));	//组合团是连接着玩的，所以要减一天
				}

				$startDateTime += $_day;
			}
		}
	}
	return false;
}

/**
 * 从产品model中取得产品的ID号
 *
 * @param string $products_model
 * @param int $type 0为取得产品ID号，1为取得供应商ID号
 */
function tep_get_products_id_from_model($products_model, $type=0){
	$tmp_array = explode('-', $products_model);
	if (preg_match('/(\d+)$/', $tmp_array[0], $m)) {
		$sub_agency_id = $m[1];
	}
	$sub_products_id = (int) $tmp_array[1];
	if($type>0){
		return $sub_agency_id;
	}
	return $sub_products_id;
}

/**
 * 从产品model中取得供应商ID号
 *
 * @param string $products_model
 */
function tep_get_agency_id_from_model($products_model){
	return tep_get_products_id_from_model($products_model,1);
}

/**
 * 从订单中取得地接的团号返回字符串
 * @param int $order_id
 * @return string
 * @author Amit 真是的，搞样式在里面干嘛啊
 */
function tep_get_provider_tours_codes_from($order_id){
	$return_ad_name_model ='';
	$products_model_status_query = tep_db_query("select p.provider_tour_code, op.admin_and_provider_confirmed  from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p  where p.products_id=op.products_id and op.orders_id = '" . (int)$order_id . "'  order by op.products_departure_date desc");

	while($products_model_name_row = tep_db_fetch_array($products_model_status_query)){
		$style = ' style="color:#FF6600; font-weight: bold; " ';
		if((int)$products_model_name_row['admin_and_provider_confirmed']){
			$style = '';
		}
		$return_ad_name_model .= '<div'.$style.'>'.$products_model_name_row['provider_tour_code'].'</div>';
	}

	return $return_ad_name_model;

}
/**
 * 从订单号取得地接的最后回复时间
 * @author Howard
 * @param int $order_id 订单id
 * @param date $date_format 输出的日期格式
 * @return array
 */
function tep_get_provider_last_re_time($order_id, $date_format = 'Y-m-d'){
	$data = array();
	//取得本行订单的产品快照id
	$orders_products_ids = get_orders_products_ids($order_id);
	foreach($orders_products_ids as $orders_products_id){
		$sql = tep_db_query('SELECT provider_status_update_date FROM `provider_order_products_status_history` where orders_products_id="'.$orders_products_id.'" and popc_user_type="1" order by popc_id desc Limit 1');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['provider_status_update_date'])){
			$data[] = date($date_format, strtotime($row['provider_status_update_date']));
		}
	}
	return $data;
}

/**
 * 从订单号取得订单产品的快照id
 * @param int $order_id
 * @return array
 */
function get_orders_products_ids($order_id){
	$data = array();
	$sql = tep_db_query('SELECT orders_products_id FROM `orders_products` WHERE orders_id="'.(int)$order_id.'" order by products_departure_date desc ');	//出发日期倒序排
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = $rows['orders_products_id'];
	}
	return $data;
}

/**
 * 取得某个订单的OP备注信息
 * @param int $order_id
 * @return array
 */
function get_orders_op_remark($order_id){
	$data = array();
	$sql_order_remark = tep_db_query('SELECT a.role,b.admin_job_number,a.remark,a.add_date FROM orders_remark AS a, admin AS b WHERE a.orders_id='.(int)$order_id.' AND a.login_id=b.admin_id ORDER BY a.add_date DESC');
	while ($rows = tep_db_fetch_array($sql_order_remark)){
		$data[] = $rows;
	}
	return $data;
}

function tep_get_atoz_filter_links($filename){
	global $HTTP_GET_VARS;
	$filter_array = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z);
	$size_atoz_filter = sizeof($filter_array);
	echo '<a href="' . tep_href_link($filename, tep_get_all_get_params(array('action','page','option_page','filter_search')).'filter_search=', 'NONSSL').'">All</a>';
	foreach($filter_array as $key=>$val){
		echo '&nbsp;|&nbsp;';
		if($HTTP_GET_VARS['filter_search']==$val){
			$valdis = '<b><font color="#FF0000">'.$val.'</font></b>';
		}else{
			$valdis = $val;
		}
		echo '<a href="' . tep_href_link($filename, tep_get_all_get_params(array('action','page','option_page','filter_search')).'filter_search='.$val, 'NONSSL').'">'.$valdis.'</a>';

		/*if($size_atoz_filter != ($key+1)){
		echo '&nbsp;|&nbsp;';
		}*/
	}
}
//function to get the no.of travelers/passengers of the all orders of 1 particular agency. for report travelers by provider
function tep_get_traveler_count_from_agency_id($agency_id,$where_date=''){

	$traveler_count_query = tep_db_query("select ope.guest_name from " . TABLE_ORDERS . " as o,".TABLE_ORDERS_PRODUCTS." as op, ".TABLE_PRODUCTS." as p, ".TABLE_ORDERS_PRODUCTS_ETICKET." as ope where ope.orders_id = o.orders_id and ope.products_id = op.products_id and o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id='".$agency_id."' ".$where_date." ");//,ope.guest_number
	$total_travelers = 0;
	$total_guests = 0;
	while($row_traveler_count = tep_db_fetch_array($traveler_count_query)){
		//echo $row_room_info['guest_name'];
		$guestnames = explode('<::>',$row_traveler_count['guest_name']);
		$total_guests = sizeof($guestnames)-1;
		$total_travelers = $total_travelers + $total_guests;
	}

	return $total_travelers;

}

function tep_get_tour_price_in_usd($price_value, $operate_currency_cod) {
	global $currencies;
	if(!is_object($currencies)){
		require_once(DIR_WS_CLASSES . 'currencies.php');
		$currencies = new currencies();
	}
	//$check_tour_agency_operate_currency_query = tep_db_query("select ta.operate_currency_code  from " . TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id = '" .$products_id. "'");
	// $check_tour_agency_operate_currency = tep_db_fetch_array($check_tour_agency_operate_currency_query);
	if($operate_currency_cod != 'USD' && $operate_currency_cod != ''){
		$currency_rate =   $currencies->currencies[$operate_currency_cod]['value'];
		//$price_value = number_format(tep_round($price_value / $currency_rate, $currencies->currencies[$operate_currency_cod]['decimal_places']), 2, '.', '');
		$price_value = number_format(tep_round($price_value / $currency_rate, 0), 2, '.', '');
	}

	return $price_value;
}

function tep_get_tour_agency_operate_currency($products_id){
	$check_tour_agency_operate_currency_query = tep_db_query("select ta.operate_currency_code  from " . TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id = '" .$products_id. "'");
	$check_tour_agency_operate_currency = tep_db_fetch_array($check_tour_agency_operate_currency_query);
	return $check_tour_agency_operate_currency['operate_currency_code'];
}

/**
 * 统计某客户已兑换的积分
 */
function tep_calculate_customer_redeemed_points($customers_id){
	$points_redeemed_query = tep_db_query("select sum(points_pending) as redeemed_points from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_status = 4 and customer_id = '" . (int)$customers_id . "'");
	$row_points_redeemed = tep_db_fetch_array($points_redeemed_query);
	return $row_points_redeemed['redeemed_points']*-1;
}

/**
 * 统计某客户的总积分(有效积分)
 * 客户的总有效积分 = 已确认积分 - 已兑换积分
function tep_get_customers_points_total($customers_id){
	if((int)POINTS_AUTO_EXPIRES){
		$start_date = date('Y-m-d 00:00:00', strtotime('- '. POINTS_AUTO_EXPIRES .' month'));
		$_ex_where .= ' AND date_added >= "'.$start_date.'" ';
		//在有效期内已用积分
		$has_use_sql = tep_db_query('SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id .'" '. $_ex_where.' AND points_status="4" ');
		$has_use = tep_db_fetch_array($has_use_sql);
		$has_points = abs($has_use['total']);
		//在有效期内得到的积分
		$get_sql = tep_db_query('SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id .'" '. $_ex_where.' AND points_status="2" ');
		$has_get = tep_db_fetch_array($get_sql);
		$get_points = $has_get['total'];
		//$sql_str = 'SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id . $_ex_where.'" AND ( points_status="2" || points_status="4") ';
		$row['total'] = $get_points - $has_points;
	}else{
		$sql_str = 'SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id.'" AND ( points_status="2" || points_status="4") ';
		$sql = tep_db_query($sql_str);
		$row = tep_db_fetch_array($sql);
	}
	$row['total'] = max(0,$row['total']);
	return (int)$row['total'];
}
 */

function tep_get_tour_agency_name_from_product_id($products_id){
	$check_tour_agency_name_query = tep_db_query("select ta.agency_name  from " . TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id = '" .$products_id. "'");
	$check_tour_agency_name = tep_db_fetch_array($check_tour_agency_name_query);
	return $check_tour_agency_name['agency_name'];
}

/*function tep_get_shopping_points($id = '', $check_session = true) {
global $customer_id;

if (is_numeric($id) == false) {
if (tep_session_is_registered('customer_id')) {
$id = $customer_id;
} else {
return 0;
}
}
if (tep_not_null(POINTS_AUTO_EXPIRES)) {
$points_query = tep_db_query("select customers_shopping_points from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$id . "' and customers_points_expires > CURDATE() limit 1");
} else {
$points_query = tep_db_query("select customers_shopping_points from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$id . "' limit 1");
}

$points = tep_db_fetch_array($points_query);

return $points['customers_shopping_points'];
}
function tep_calc_shopping_pvalue($points) {

return tep_round(((float)$points * (float)REDEEM_POINT_VALUE), 2);
}
*/
/**
 *获取TABLE_ORDERS_PRODUCTS_ETICKET中指定 orders_products_id 的产品记录的用户名字信息
 * @param int $orders_products_id 产品快照记录ID
 **/
function tep_get_products_guest_names_lists($ord_product_id,$p_departure_date=''){
	$str_display_guest_name_list_dis = '';
	$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where  orders_products_id = '" . (int)$ord_product_id . "'");//orders_id = '" . (int)$ordid . "' and products_id=".(int)$productid." ");
	$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
	$guestnames = explode('<::>',$orders_eticket_result['guest_name']);
	if($orders_eticket_result['guest_number']==0){
		foreach($guestnames as $key=>$val)$loop = $key;
	}else{
		$loop = $orders_eticket_result['guest_number'];
	}
	for($noofguest=1;$noofguest<=$loop;$noofguest++){
		$guest_name_incudes_child_age = explode('||',$guestnames[($noofguest-1)]);
		if(isset($guest_name_incudes_child_age[1])){

			if($p_departure_date != ''){
				$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($p_departure_date,0,10)));
			}
			$str_display_guest_name_list_dis .= $noofguest.'. '. stripslashes( tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) ) .'('.$di_childage_difference_in_year.')<br />';
		}else{
			$str_display_guest_name_list_dis .= $noofguest.'. '. stripslashes( tep_filter_guest_chinese_name($guestnames[($noofguest-1)]) ) .'<br />';
		}
	}
	return $str_display_guest_name_list_dis;
}
function GetDateDifference($StartDateString=NULL, $EndDateString=NULL) {
	$ReturnArray = array();

	$SDSplit = explode('/',$StartDateString);
	$StartDate = mktime(0,0,0,$SDSplit[0],$SDSplit[1],$SDSplit[2]);

	$EDSplit = explode('/',$EndDateString);
	$EndDate = mktime(0,0,0,$EDSplit[0],$EDSplit[1],$EDSplit[2]);

	$DateDifference = $EndDate-$StartDate;

	$ReturnArray['YearsSince'] = $DateDifference/60/60/24/365;
	$ReturnArray['MonthsSince'] = $DateDifference/60/60/24/365*12;
	$ReturnArray['DaysSince'] = $DateDifference/60/60/24;
	$ReturnArray['HoursSince'] = $DateDifference/60/60;
	$ReturnArray['MinutesSince'] = $DateDifference/60;
	$ReturnArray['SecondsSince'] = $DateDifference;

	$y1 = date("Y", $StartDate);
	$m1 = date("m", $StartDate);
	$d1 = date("d", $StartDate);
	$y2 = date("Y", $EndDate);
	$m2 = date("m", $EndDate);
	$d2 = date("d", $EndDate);

	$diff = '';
	$diff2 = '';
	if (($EndDate - $StartDate)<=0) {
		// Start date is before or equal to end date!
		$diff = "0 days";
		$diff2 = "Days: 0";
	} else {

		$y = $y2 - $y1;
		$m = $m2 - $m1;
		$d = $d2 - $d1;
		$daysInMonth = date("t",$StartDate);
		if ($d<0) {$m--;$d=$daysInMonth+$d;}
		if ($m<0) {$y--;$m=12+$m;}
		$daysInMonth = date("t",$m2);

		// Nicestring ("1 year, 1 month, and 5 days")
		if ($y>0) $diff .= $y==1 ? "1" : "$y";
		//if ($y>0 && $m>0) $diff .= ", ";
		if ($m>0) $diff .= $m==1? ".1" : ".$m";

		if($y==1){
			$diff .= " year";
		}else{
			$diff .= " years";
		}

		// if (($m>0||$y>0) && $d>0) $diff .= ", and ";
		// if ($d>0) $diff .= $d==1 ? "1 day" : "$d days";

		// Nicestring 2 ("Years: 1, Months: 1, Days: 1")
		if ($y>0) $diff2 .= $y==1 ? "Years: 1" : "Years: $y";
		if ($y>0 && $m>0) $diff2 .= ", ";
		if ($m>0) $diff2 .= $m==1? "Months: 1" : "Months: $m";
		if (($m>0||$y>0) && $d>0) $diff2 .= ", ";
		if ($d>0) $diff2 .= $d==1 ? "Days: 1" : "Days: $d";

	}
	$ReturnArray['NiceString'] = $diff;
	$ReturnArray['NiceString2'] = $diff2;
	// return $ReturnArray;
	return $ReturnArray['NiceString'];
}

//howard added
/*取得产品行程天数*/
function get_products_departure_date_num($products_id){
	if(!(int)$products_id){
		return 0;
	}
	$sql = tep_db_query('SELECT products_durations, products_durations_type FROM `products` WHERE products_id="'.(int)$products_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	if((int)$row['products_durations_type']==0){
		return $row['products_durations'];
	}elseif((int)$row['products_durations_type']==1){
		return $row['products_durations']/24;
	}elseif((int)$row['products_durations_type']==2){
		return $row['products_durations']/24/60;
	}

}

/* get login amdin name*/
function get_login_name(){
	global $login_id;
	if(tep_session_is_registered('login_id')){
		$sql = tep_db_query('SELECT admin_firstname FROM `admin` WHERE admin_id="'.(int)$login_id.'" limit 1');
		$row = tep_db_fetch_array($sql);
		return $row['admin_firstname'];
	}else{
		return false;
	}
}

/* get login amdin name*/
function get_login_publicity_name(){
	global $login_id;
	if(tep_session_is_registered('login_id')){
		$sql = tep_db_query('SELECT admin_firstname, admin_publicity_name FROM `admin` WHERE admin_id="'.(int)$login_id.'" limit 1');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['admin_publicity_name'])){
			return $row['admin_publicity_name'];
		}else{
			return $row['admin_firstname'];
		}
	}else{
		return false;
	}
}

/* sending point mail to coustomers*/
function send_point_to_customers($customers_id=0){
	global $currencies;
	//暂停积分邮件功能，先跟踪一段时间再看
	if(!(int)$customers_id){
		tep_mail('ZhouHaoHua', 'xmzhh2000@hotmail.com', date("Y-m-d H:i:s").'send_point_to_customers ', date("Y-m-d H:i:s"), 'TffBlackAmin', 'automail@usitrip.com', 'true', 'gb2312');
		return false;
	}

	if(!is_object($currencies)){
		require_once(DIR_WS_CLASSES . 'currencies.php');
		$currencies = new currencies();
	}

	$customers_id = (int)$customers_id;

	$where = ' WHERE customers_newsletter ="1" ';

	if($customers_id>0){ $where .= ' AND customers_id = "'.(int)$customers_id.'" ';}



	$cust_sql = tep_db_query('SELECT  customers_id, customers_firstname, customers_email_address, customers_char_set FROM `customers` '.$where.' ORDER BY `customers_id` ASC ');
	while($cust_rows = tep_db_fetch_array($cust_sql)){

		$has_point = tep_get_shopping_points($cust_rows['customers_id'], false);
		$PointSumString = sprintf(MY_POINTS_CURRENT_BALANCE, number_format($has_point,POINTS_DECIMAL_PLACES),$currencies->format(tep_calc_shopping_pvalue($has_point)));

		$pending_points_query = "select unique_id, orders_id, points_pending, points_comment, date_added, points_status, points_type, feedback_other_site_url, products_id, admin_id from " . TABLE_CUSTOMERS_POINTS_PENDING . " where customer_id = '".(int)$cust_rows['customers_id']."' order by unique_id desc limit 5";

		$pending_points_query = tep_db_query($pending_points_query);

		$TableTrContent ='';

		if (tep_db_num_rows($pending_points_query)) {
			while ($pending_points = tep_db_fetch_array($pending_points_query)) {
				$orders_status_query = tep_db_query("select o.orders_id, o.orders_status, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cust_rows['customers_id'] . "' and o.orders_id = '" . (int)$pending_points['orders_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'");
				$orders_status = tep_db_fetch_array($orders_status_query);

				if ($pending_points['points_status'] == '1') $points_status_name = TEXT_POINTS_PENDING;
				if ($pending_points['points_status'] == '2') $points_status_name = TEXT_POINTS_CONFIRMED;
				if ($pending_points['points_status'] == '3') $points_status_name = '<span style="color:#FF6600">' . TEXT_POINTS_CANCELLED . '</span>';
				if ($pending_points['points_status'] == '4') $points_status_name = '<span style="color:#FF6666">' . TEXT_POINTS_REDEEMED . '</span>';

				if ($orders_status['orders_status'] == 2 && $pending_points['points_status'] == 1 || $orders_status['orders_status'] == 3 && $pending_points['points_status'] == 1) {
					$points_status_name = TEXT_POINTS_PROCESSING;
				}

				if (($pending_points['points_type'] == 'SP') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_COMMENT')) {
					$pending_points['points_comment'] = TEXT_DEFAULT_COMMENT;
				}
				if (($pending_points['points_type'] == 'TP') && ($pending_points['points_comment'] == 'USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT')) {
					$pending_points['points_comment'] = USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REDEEMED') {
					$pending_points['points_comment'] = TEXT_DEFAULT_REDEEMED;
				}
				if ($pending_points['points_comment'] == 'TEXT_WELCOME_POINTS_COMMENT') {
					$pending_points['points_comment'] = TEXT_WELCOME_POINTS_COMMENT;
				}
				if ($pending_points['points_comment'] == 'TEXT_VALIDATION_ACCOUNT_POINT_COMMENT') {
					$pending_points['points_comment'] = TEXT_VALIDATION_ACCOUNT_POINT_COMMENT;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS_PHOTOS') {
					$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS_PHOTOS;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_FEEDBACK_APPROVAL') {
					$pending_points['points_comment'] = TEXT_DEFAULT_FEEDBACK_APPROVAL;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_ANSWER') {
					$pending_points['points_comment'] = TEXT_DEFAULT_ANSWER;
				}

				$referred_customers_name = '';
				if ($pending_points['points_type'] == 'RF') {
					$referred_name_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$pending_points['orders_id'] . "' limit 1");
					$referred_name = tep_db_fetch_array($referred_name_query);
					if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REFERRAL') {
						$pending_points['points_comment'] = TEXT_DEFAULT_REFERRAL;
					}
					$referred_customers_name = $referred_name['customers_name'];
				}

				if (($pending_points['points_type'] == 'RV') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS')) {
					$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS;
				}
				if(($pending_points['points_type'] == 'VT') && ($pending_points['points_comment'] == 'TEXT_VOTE_POINTS_COMMENT')){
					$pending_points['points_comment'] = TEXT_VOTE_POINTS_COMMENT;
				}

				if (($pending_points['orders_id'] == 0) || ($pending_points['points_type'] == 'RF') || ($pending_points['points_type'] == 'RV')  || ($pending_points['points_type'] == 'PH') || ($pending_points['points_type'] == 'QA')) {
					$orders_status['orders_status_name'] = '<span class="pointWarning">' . TEXT_STATUS_ADMINISTATION . '</span>';
					$pending_points['orders_id'] = '<span class="pointWarning">' . TEXT_ORDER_ADMINISTATION . '</span>';
				}

				$TableTrContent.='<tr>';
				$TableTrContent.='<td height="20" bgcolor="#FFFFFF" style="font-size:12px; color:#223C6A">'.tep_date_short($pending_points['date_added']).'</td>';
				$TableTrContent.='<td bgcolor="#FFFFFF" style="font-size:12px; color:#223C6A" nowrap="nowrap">'.'#' . $pending_points['orders_id'] . '&nbsp;&nbsp;' . ($orders_status['orders_status_name']).'</td>';
				$TableTrContent.='<td bgcolor="#FFFFFF" style="font-size:12px; color:#223C6A">'.(nl2br($pending_points['points_comment'])) .'&nbsp;' . ($referred_customers_name).'</td>';
				$TableTrContent.='<td bgcolor="#FFFFFF" style="font-size:12px; color:#223C6A">'.$points_status_name.'</td>';
				$TableTrContent.='<td bgcolor="#FFFFFF" style="font-size:12px; color:#223C6A" align="right">'. number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES).'</td>';
				$TableTrContent.='</tr>';

			}
		}


		$to_name = $cust_rows['customers_firstname'].' ';
		$to_email_address = $cust_rows['customers_email_address'];
		$email_subject = POINTS_CALL_MAIL.' ';
		$from_email_name = STORE_OWNER.' ';
		$from_email_address = STORE_OWNER_EMAIL_ADDRESS;

		//howard added new eamil tpl
		$patterns = array();
		$patterns[0] = '{CustomerName}';
		$patterns[1] = '{images}';
		$patterns[2] = '{HTTP_SERVER}';
		$patterns[3] = '{HTTPS_SERVER}';
		$patterns[4] = '{PointSumString}';
		$patterns[5] = '{TableTrContent}';
		$patterns[6] = '{YYYY-MM-DD}';
		$patterns[7] = '{EMAIL}';
		$patterns[8] = '{TFF_POINTS_DESCRIPTION}';
		$patterns[9] = '{TFF_POINTS_DESCRIPTION_CONTENT}';
		$patterns[10] = '{TEXT_HOW_SAVE}';
		$patterns[11] = '{TEXT_SAVINGS}';
		$patterns[12] = '{POINTS_TERMS_LINKS}';
		$patterns[13] = '{CONFORMATION_EMAIL_FOOTER}';

		$replacements = array();
		$replacements[0] = $to_name;
		$replacements[1] = HTTP_SERVER.'/email_tpl/images';
		$replacements[2] = HTTP_SERVER;
		$replacements[3] = HTTPS_SERVER;
		//$replacements[4] = str_replace('$','\$',$PointSumString);
		//$replacements[5] = str_replace('$','\$',$TableTrContent);
		$replacements[4] = $PointSumString;
		$replacements[5] = $TableTrContent;
		$replacements[6] = date('Y-m-d');
		$replacements[7] = $to_email_address;
		$replacements[8] = TFF_POINTS_DESCRIPTION;
		$replacements[9] = TFF_POINTS_DESCRIPTION_CONTENT;
		$replacements[10] = TEXT_HOW_SAVE;
		$replacements[11] = TEXT_SAVINGS;
		$replacements[12] = tep_catalog_href_link('points_terms.php');
		$replacements[13] = nl2br(CONFORMATION_EMAIL_FOOTER);

		$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
		$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/customers_point.tpl.html');
		$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');

		$email_text = str_replace( $patterns ,$replacements, preg_replace('/[[:space:]]+/',' ',$email_tpl));

		//howard added new eamil tpl end
		if( strtolower($cust_rows['customers_char_set']) =='big5'){
			$email_charset = 'big5';
		}else{
			$email_charset = 'gb2312';
		}

		tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset);
		if(!(int)$customers_id){
			echo $to_email_address."\n";
		}
	}
}

// 发送旅游归来邮件给客户
function send_tours_back_to_customers(){
	require(DIR_WS_FUNCTIONS . 'banner.php');

	$v_s_id = '2';	//旅游调查邮件的ID
	$space_day_num = 3;	//旅游结束后几天给客户发邮件?
	$start_date = '2012-10-13 00:00:00';	//设置开始使用此功能的最早的订单日期
	$test_customers_id = '';
	//$test_customers_id = ' AND customers_id = "60469"';

	$ToDay = date('Y-m-d');
	$Whiere_Ex = ' AND ( v_s_end_date >="'.$ToDay.'" || v_s_end_date="" || v_s_end_date="0000-00-00" )';
	$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="'.(int)$v_s_id.'" AND v_s_state ="1" AND  v_s_start_date <="'.$ToDay.'" '.$Whiere_Ex.' limit 1');
	$vote_row = tep_db_fetch_array($vote_sql);
	$point = max((int)$vote_row['v_s_points'],1);
	if((int)$vote_row['v_s_id']){

		$orders_sql = tep_db_query('SELECT orders_id, customers_id FROM `orders` WHERE orders_status="100006" AND tours_back_mail ="0" AND date_purchased > "'.$start_date.'"  '. $test_customers_id );
		while($orders_rows = tep_db_fetch_array($orders_sql)){
			$_sql = 'SELECT p.products_durations,  p.products_durations_type, op.products_departure_date  FROM `orders_products` op , `products` p WHERE  p.products_id=op.products_id AND op.orders_id="'.(int)$orders_rows['orders_id'].'" Group By op.orders_id Order By op.products_departure_date DESC Limit 1';
			$orders_prod_sql = tep_db_query($_sql);
			$orders_prod_row = tep_db_fetch_array($orders_prod_sql);
			if(!(int)$orders_prod_row['products_durations_type']){
				$back_date_max = date('Y-m-d', strtotime('-'.($orders_prod_row['products_durations']+$space_day_num).' day')). ' 00:00:00';
			}elseif((int)$orders_prod_row['products_durations_type']=='1'){
				$back_date_max = date('Y-m-d', strtotime('-'.(round($orders_prod_row['products_durations']/24, 1)+$space_day_num).' day')). ' 00:00:00';
			}elseif((int)$orders_prod_row['products_durations_type']=='2'){
				$back_date_max = date('Y-m-d', strtotime('-'.(round($orders_prod_row['products_durations']/24/60, 1)+$space_day_num).' day')). ' 00:00:00';
			}
			//echo $back_date_max."||".$orders_prod_row['products_departure_date']; exit;
			if(strtotime($back_date_max) >= strtotime($orders_prod_row['products_departure_date'])){
				//echo 'orders_id: '. $orders_rows['orders_id']."   date:".$orders_prod_row['products_departure_date']."   maxdate:".$back_date_max." customers_id:".$orders_rows['customers_id']."\n";
				//exit;
				$cust_sql = tep_db_query('SELECT customers_id,customers_firstname,customers_email_address,customers_char_set FROM `customers` WHERE customers_id ="'.(int)$orders_rows['customers_id'].'" AND customers_newsletter ="1" ');
				$cust_rows = tep_db_fetch_array($cust_sql);

				if((int)$cust_rows['customers_id']){
					$to_name = $cust_rows['customers_firstname'].' ';
					$to_email_address = $cust_rows['customers_email_address'];
					$email_subject = strip_tags($vote_row['v_s_title']).' ';
					$from_email_name = STORE_OWNER.' ';
					$from_email_address = STORE_OWNER_EMAIL_ADDRESS;

					if( strtolower($cust_rows['customers_char_set']) =='big5'){
						$email_charset = 'big5';
						$language = 'tw';
					}else{
						$email_charset = 'gb2312';
						$language = 'sc';
					}

					//howard added new eamil tpl
					$patterns = array();
					$patterns[0] = '{CustomerName}';
					$patterns[1] = '{images}';
					$patterns[2] = '{HTTP_SERVER}';
					$patterns[3] = '{HTTPS_SERVER}';
					$patterns[4] = '{Point}';
					$patterns[5] = '{HREF}';
					$patterns[6] = '{EMAIL}';

					$_href = HTTP_SERVER.'/vote_system.php?v_s_id='.$v_s_id.'&orders_id='.$orders_rows['orders_id'];

					$replacements = array();
					$replacements[0] = $to_name;
					$replacements[1] = HTTP_SERVER.'/email_tpl/images';
					$replacements[2] = HTTP_SERVER;
					$replacements[3] = HTTPS_SERVER;
					$replacements[4] = $point;
					$replacements[5] = $_href;
					$replacements[6] = $to_email_address;

					$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
					$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/customer_tour_back.tpl.html');
					$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');

					$email_text = str_replace( $patterns ,$replacements, ($email_tpl));
					$email_text = preg_replace('/[[:space:]]+/',' ',$email_text);

					//加入newsletter广告
					$banner_language_code_name = (strtolower($email_charset)=='gb2312') ? 'schinese':'tchinese';
					$banners = get_banners("Tours Feedback", $banner_language_code_name);
					if(tep_not_null($banners)){
						for($i=0; $i<count($banners); $i++){
							if(tep_not_null($banners[$i]['FinalCode'])){
								$email_text .= iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['FinalCode']);
							}else{
								$email_text .= '<div><a title="'.iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['alt']).'" href="'.$banners[$i]['links'].'" target="_blank"><img border="0" src="'.$banners[$i]['src'].'" alt="'.iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['alt']).'" /></a></div>';
							}
						}
					}

					//howard added new eamil tpl end
					tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset);

					tep_db_query('UPDATE `orders` SET tours_back_mail="1" WHERE orders_id="'.(int)$orders_rows['orders_id'].'" ');
					echo $to_email_address."\n";
				}
			}
		}
		return true;
	}
}

// howard added for vote system

function display_vote($v_s_id , $table_width ='100%' , $form_name='', $form_method='post', $form_action='',$form_target='', $submit_name='', $charset=CHARSET, $orders_id='', $submit_image =''){
	global $error_string;

	if(!(int)$v_s_id){ return false;}
	$string = '';
	$string_foot = '';
	if(tep_not_null($form_name)){
		$string .='<form id="'.$form_name.'" name="'.$form_name.'" method="'.$form_method.'" action="'.$form_action.'" style="margin:0px;" target="'.$form_target.'">';
		if(tep_not_null($charset)){
			$string .=tep_draw_hidden_field('vote_code',$charset);
		}
		$string_foot = '</form>';
	}

	if((int)$orders_id){
		$string .=tep_draw_hidden_field('orders_id',$orders_id);
	}
	$ToDay = date('Y-m-d');
	$Whiere_Ex = ' AND ( v_s_end_date >="'.$ToDay.'" || v_s_end_date="" || v_s_end_date="0000-00-00" )';
	$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="'.(int)$v_s_id.'" AND v_s_state ="1" AND  v_s_start_date <="'.$ToDay.'" '.$Whiere_Ex.' limit 1');
	if($_GET['action']=='preview'){
		$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="'.(int)$v_s_id.'" limit 1');
	}
	//取得vote_system
	while($vote_rows = tep_db_fetch_array($vote_sql)){
		$string.= '<table border="0" cellspacing="0" cellpadding="0" style="width:'.$table_width.';"><tr><td align="right">'.iconv('gb2312',$charset.'//IGNORE','调查编号：').str_replace('-','',$vote_rows['v_s_start_date']).$vote_rows['v_s_id'].'</td></tr></table><table  border="0" cellspacing="0" cellpadding="0" style="width:'.$table_width.'; float:left; margin-bottom:5px;">';

		if(tep_not_null($error_string)) {
			$string.= '<tr><td bgcolor="#FFE6E6" style="border:1px #FFCC00 solid; padding:4px 0px 4px 3px; color:#223C6A; font-size:12px;" align="left">'.$error_string.'</td></tr>';
		}

		$string.= '<tr><td style="height:5px;"></td></tr><tr><td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; padding:4px 0px 4px 3px; color:#223C6A; font-size:14px;" align="left"><b>'.iconv('gb2312',$charset.'//IGNORE',$vote_rows['v_s_description']).'</b></td></tr><tr><td style="border:1px #FFCC00 solid; border-top:0px; " align="left">';


		//取得vote_system_item
		$item_sql = tep_db_query('SELECT * FROM `vote_system_item` WHERE v_s_id="'.(int)$vote_rows['v_s_id'].'" Order By v_s_i_sort ASC, v_s_i_id ASC ');
		$item_num = 0;
		while($item_rows = tep_db_fetch_array($item_sql)){
			$item_num ++;
			$background_color = '#FFFFFF';
			if($item_num % 2==0){ $background_color='#FFFBEB';}
			$string.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;"><tr><td height="32" style="color:#223C6A; font-size:12px; background-color:'.$background_color.'" >&nbsp;<b>'.$item_num.'. '.iconv('gb2312',$charset.'//IGNORE',$item_rows['v_s_i_title']).'</b></td></tr></table>';
			//取得答案选项
			$string.= '<table border="0" cellspacing="0" cellpadding="0" style="width:100%; background-color:'.$background_color.'"><tr>';
			if((int)$item_rows['v_s_i_type']!='2'){
				//单选复选
				$options_sql = tep_db_query('SELECT * FROM `vote_system_item_options` WHERE v_s_i_id="'.$item_rows['v_s_i_id'].'" Order By v_s_i_o_id ');
				$check_box_i = 0;
				while($options_rows = tep_db_fetch_array($options_sql)){

					if((int)$item_rows['v_s_i_type'] =='0'){
						$checked = false;
						$tmp_var = $_POST['v_s_i_o_id'][$vote_rows['v_s_id']][(int)$item_rows['v_s_i_id']];
						if($tmp_var == (int)$options_rows['v_s_i_o_id']){
							$checked = true;
						}
						$input_box = tep_draw_radio_field('v_s_i_o_id['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']', (int)$options_rows['v_s_i_o_id'] , $checked );
					}elseif((int)$item_rows['v_s_i_type'] =='1'){
						$checked = false;
						$tmp_var = $_POST['v_s_i_o_id'][$vote_rows['v_s_id']][(int)$item_rows['v_s_i_id']][$check_box_i];
						if($tmp_var == (int)$options_rows['v_s_i_o_id']){
							$checked = true;
						}
						$input_box = tep_draw_checkbox_field('v_s_i_o_id['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']['.$check_box_i.']', (int)$options_rows['v_s_i_o_id'] , $checked );
						$check_box_i++;
					}

					$string.= '<td width="1%" nowrap="nowrap" style="font-size:12px">&nbsp;'.$input_box.'&nbsp;</td><td style="font-size:12px" >'.iconv('gb2312',$charset.'//IGNORE',$options_rows['v_s_i_o_title']).'</td>';
				}
			}else{
				//文本
				$tmp_var = $_POST['text_vote'][$vote_rows['v_s_id']][(int)$item_rows['v_s_i_id']];
				if(tep_not_null($_POST['vote_code'])){
					$tmp_var = iconv($_POST['vote_code'],CHARSET.'//IGNORE',$tmp_var);
				}
				$string.= '<td style="font-size:12px">&nbsp;'.tep_draw_input_field('text_vote['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']', $tmp_var ,' style="width:400px; font-size:12px" ').'</td>';
			}
			$string.= '</tr><tr><td>&nbsp;</td></tr></table>';
		}

		if(tep_not_null($submit_name)){
			if(!tep_not_null($submit_image)){
				$string .= '<tr><td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; border-top-width:0px; padding:4px 0px 4px 3px; color:#223C6A; " align="center"><input type="submit" name="Submit" value=" '.iconv('gb2312',$charset.'//IGNORE','提交').' " style="font-size:14px;" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="'.iconv('gb2312',$charset.'//IGNORE','重新选择').'" style="font-size:14px;" />'.tep_draw_hidden_field('ation_vote','true').'</td></tr>';
			}else{
				$string .= '<tr><td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; border-top-width:0px; padding:4px 0px 4px 3px; color:#223C6A; " align="center">'.tep_image_submit('vote_submit.gif', iconv('gb2312',$charset.'//IGNORE','提交')).'&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="'.iconv('gb2312',$charset.'//IGNORE','重新选择').'" style="font-size:14px; border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	background-color: #fff5cc;" />'.tep_draw_hidden_field('ation_vote','true').'</td></tr>';
			}
		}

		$string.= '</td></tr></table>';
	}

	return $string.$string_foot;
}

function tep_get_settlement_informations($order_id){
	$ord_settle_prods_query_sql = "select osi.orders_settlement_id, osi.reference_comments, osi.orders_payment_method as payment_method, osi.settlement_date, o.orders_id, o.orders_status, o.date_purchased, osh.date_added, osi.updated_by, ot.text, ot.value, osi.order_value from  orders as o left join ".TABLE_ORDERS_SETTLEMENT_INFORMATION." as osi on osi.orders_id = o.orders_id
LEFT JOIN ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi2 ON osi.orders_id = o.orders_id and osi.orders_id = osi2.orders_id
AND osi.orders_settlement_id < osi2.orders_settlement_id
AND osi.date_added < osi2.date_added, ".TABLE_ORDERS_STATUS_HISTORY." osh LEFT JOIN ".TABLE_ORDERS_STATUS_HISTORY." osh2 ON osh.orders_id = osh2.orders_id
AND osh.orders_status_history_id < osh2.orders_status_history_id
AND osh.date_added < osh2.date_added, ".TABLE_ORDERS_TOTAL." ot where osh2.date_added IS NULL and osi2.date_added IS NULL and o.orders_status = osh.orders_status_id and o.orders_id = osh.orders_id and o.orders_id=ot.orders_id and ot.class='ot_total' and o.orders_id='".$order_id."' group by o.orders_id order by osh.date_added desc ";
	$ord_settle_prods_query = tep_db_query($ord_settle_prods_query_sql);
	if($ord_settle_prods_row = tep_db_fetch_array($ord_settle_prods_query)){
		return $ord_settle_prods_row;
	}else{
		return '';
	}
}

function tep_get_latest_settelemt_date($order_id,$order_status){
	$ord_settle_prods_query_sql = "select osh.date_added, osh.updated_by from ".TABLE_ORDERS_STATUS_HISTORY." osh where osh.orders_id='".$order_id."' and osh.orders_status_id='".$order_status."' order by osh.date_added desc limit 1";
	$ord_settle_prods_query = tep_db_query($ord_settle_prods_query_sql);
	if($ord_settle_prods_row = tep_db_fetch_array($ord_settle_prods_query)){
		return $ord_settle_prods_row;
	}else{
		return '';
	}
}

function highlightWords($string, $words)
{

	if($words!=''){
		//$string = str_ireplace($words, '<span class=highlight_word>'.$words.'</span>', $string);
		$string = preg_replace("/(".$words.")/i", "<span class=highlight_word>".$words."</span>", $string);
	}

	return $string;
}

function tep_get_paid_payment_history_invoice_amt_and_tour_detail($orders_products_id, $ord_prod_payment_id = '') {
	$product_query = tep_db_query("select op.products_id, op.products_name, op.products_model, op.orders_id, op.final_invoice_amount, op.final_invoice_amount_history, op.final_price, op.final_price_cost, oph.invoice_number, oph.invoice_amount, op.products_departure_date from   ".TABLE_ORDERS_PRODUCTS." as op left join ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." as oph on oph.orders_products_id = op.orders_products_id and  ord_prod_history_id IN (SELECT max(ord_prod_history_id) FROM ".TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY." where orders_products_id = '" . (int)$orders_products_id . "'  GROUP BY orders_products_id) where op.orders_products_id = '" . (int)$orders_products_id . "'");

	$product = tep_db_fetch_array($product_query);

	if($ord_prod_payment_id != '' && $product['final_invoice_amount_history'] != ''){

		$final_inv_amt_his_tot = explode("!!##!!",$product['final_invoice_amount_history']);
		$total_of_paid_inovice = 0;
		$total_of_paid_inovice_old_check = true;
		for($di= 0; $di < sizeof($final_inv_amt_his_tot); $di++){

			$final_inv_amt_his_key_val = explode('||',$final_inv_amt_his_tot[$di]);

			//echo 'key=>'.$final_inv_amt_his_key_val[0].'value=>'.$final_inv_amt_his_key_val[1].'<br/>';
			if($final_inv_amt_his_key_val[0] == $ord_prod_payment_id){
				$product['final_invoice_amount'] = $final_inv_amt_his_key_val[1];
			}

			$check_payment_cancelled = tep_db_fetch_array(tep_db_query("select is_payment_cancelled, payment_amount from ".TABLE_ORDERS_PRODUCTS_PAYMENT_HISTORY." where ord_prod_payment_id = '".(int)$final_inv_amt_his_key_val[0]."'"));
			if($check_payment_cancelled['payment_amount'] > 0 || $check_payment_cancelled['is_payment_cancelled'] != '1'){
				$total_of_paid_inovice  = $total_of_paid_inovice + $final_inv_amt_his_key_val[1];
			}

			if($ord_prod_payment_id < 77){
				$total_of_paid_inovice_old_check = false;
			}

		}

		if($total_of_paid_inovice_old_check == true){
			$product['total_final_invoice_amount'] = $total_of_paid_inovice;
		}

	}

	return $product;
}

// howard added email track code
function email_track_code($mail_type='newsletter', $mail_address='xmzhh2000@126.com', $id=0 , $id_key = 'orders_id', $orders_eticket_log_id=0){
	$img_rul = HTTP_SERVER.'/email_track.php';
	$img_rul .='?mail_type='.$mail_type;
	$img_rul .='&mail_address='.$mail_address;
	if((int)$id){
		$img_rul .='&key_field='.$id_key.'&key_id='.$id;
		//$img_rul .='&'.$id_key.'='.$id;
	}
	//E-ticket Log Start
	if((int)$orders_eticket_log_id > 0){
		$img_rul .='&orders_eticket_log_id='.$orders_eticket_log_id;
	}
	//E-ticket Log End
	$img_str = '<img src="'.$img_rul.'" width="1" height="1" style="display:none" />';
	return $img_str;
}
// howard added email track code end

//howard added 结伴同游 取得付款状态
function get_travel_companion_status($status_id){
	$status_array = array();
	$status_array[0] = '未付款';
	$status_array[1] = '<font color="#FF0000">等待审核</font>';
	$status_array[2] = '<font color="#009900">付款完成</font>';
	$status_array[3] = '<font color="#FF0000">已部分付款</font>';
	return $status_array[(int)$status_id];
}

//检测结是否是结伴同游
function is_travel_comp($orders_id, $products_id=0){
	if(!(int)$orders_id){ return false; }
	$where_str = ' orders_id="'.(int)$orders_id.'" ';
	if((int)$products_id){
		$where_str .= ' AND products_id="'.(int)$products_id.'" ';
	}
	$sql = tep_db_query('SELECT orders_travel_companion_id FROM `orders_travel_companion` WHERE '.$where_str.' LIMIT 1 ');
	$row = tep_db_fetch_array($sql);
	if((int)$row['orders_travel_companion_id']){
		return true;
	}

	return false;
}

/**
 * 自动取消没有及时付款的结伴同游订单
 * @author Howard
 */
function orders_travel_companion_cancel(){
	if(!defined('TRAVEL_COMPANION_MAX_PAY_DAY') || !defined('TRAVEL_COMPANION_OFF_ON') || TRAVEL_COMPANION_OFF_ON != 'true'){
		return false;
	}

	//require(DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'order_info_to_mail.php');

	$max_day_num = (int)TRAVEL_COMPANION_MAX_PAY_DAY;
	//$end_time = date("Y-m-d H:i:s", strtotime('-'.$max_day_num.' day'));
	$the_date_time = date("Y-m-d H:i:s");
	//echo $end_time;
	//一、取得未完成付款的订单号
	//echo 'SELECT o.orders_id FROM `orders_travel_companion` tc , `orders` o WHERE tc.orders_id=o.orders_id AND tc.orders_travel_companion_status<1 AND tc.expired_date < "'.$the_date_time.'" AND o.orders_status !="6" Group By tc.orders_id';
	$order_sql = tep_db_query('SELECT o.orders_id FROM `orders_travel_companion` tc , `orders` o WHERE tc.orders_id=o.orders_id AND tc.orders_travel_companion_status<1 AND tc.expired_date < "'.$the_date_time.'" AND o.orders_status !="6" Group By tc.orders_id');
	$orders_ids ="";
	while($order_rows = tep_db_fetch_array($order_sql)){
		$orders_ids .= $order_rows['orders_id'].",";

		$sql_date_array = array(
		'orders_id' => (int)$order_rows['orders_id'],
		'orders_status_id' => '1',
		'date_added' => date('Y-m-d H:i:s'),
		'customer_notified' => '1',
		'comments' => '结伴同游订单超过'.$max_day_num.'天没有全部完成付款，订单被系统自动取消！'
		);
		tep_db_perform('orders_status_history', $sql_date_array);

	}

	$orders_ids = substr($orders_ids,0,strlen($orders_ids)-1);
	//二、根据订单取得结伴同游资料
	if(tep_not_null($orders_ids)){
		//更改订单状态为取消
		tep_db_query('update orders set orders_status ="6" where orders_id in('.$orders_ids.') ');
		//发邮件到服务邮箱通知客服人员处理 被取消的订单退款
		$to_name = db_to_html('走四方客服 ');
		$to_email_address = SEND_EXTRA_ORDER_EMAILS_TO;
		$email_subject = db_to_html('结伴同游订单被系统自动取消，请跟进处理！').date('YmdHi')." ";
		$from_email_name = db_to_html('走四方网邮件系统 ');
		$email_text = db_to_html('被取消的订单：').$orders_ids;
		$from_email_address = 'service@usitrip.com';
		$email_charset = CHARSET;
		if($to_email_address!=""){
			tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset);
		}

		$rtn = array();
		$sql = tep_db_query('SELECT customers_id,orders_id FROM `orders_travel_companion` WHERE orders_id in('.$orders_ids.') AND customers_id > 0 Order By orders_id ');
		while($rows=tep_db_fetch_array($sql)){
			if((int)$rows['customers_id']){
				$rtn[$rows['orders_id'] . '_' . $rows['customers_id']] = $rows['customers_id'];


				//发取消订单邮件
				/*$to_name = db_to_html(tep_customers_name((int)$rows['customers_id']));
				$to_email_address = tep_get_customers_email((int)$rows['customers_id']);
				$email_subject = db_to_html('走四方结伴同游--订单已撤销，订单号：').(int)$rows['orders_id'];
				$email_text = db_to_html('尊敬的『 ').$to_name."』：\n";
				//$email_text .= db_to_html('您的结伴同游订单（订单号：<a href="'.tep_catalog_href_link('orders_travel_companion.php','order_id='.(int)$rows['orders_id']).'" target="_blank">'.(int)$rows['orders_id'].'</a>）由于在'.$max_day_num.'天内还没有全部完成付款，订单已经被自动取消！若您还需要订购行程，请重新在'.HTTP_SERVER.'上选择订购，若有问题请您及时联系走四方客服人员，谢谢。')."\n";
				$email_text .= db_to_html('您的结伴同游订单（订单号：<a href="'.tep_catalog_href_link('orders_travel_companion.php','order_id='.(int)$rows['orders_id']).'" target="_blank">'.(int)$rows['orders_id'].'</a>）由于一直未收到您们的款项，系统已自动取消该订单，若您还需要订购，请在'.HTTP_SERVER.'上重新选择订购，若有问题请您及时联系走四方客服人员，感谢！')."\n";
				$email_text .= db_to_html('请点此连接进行查看：' . "\n" . '<a href="'.tep_catalog_href_link('orders_travel_companion.php','order_id='.(int)$rows['orders_id']).'" target="_blank">' . tep_catalog_href_link('orders_travel_companion.php','order_id='.(int)$rows['orders_id']) . '</a> 注：如果点击无法打开，请复制地址到浏览器地址栏打开。' . "\n");

				// by lwkai 2012-06-04 add 获取当前订单的信息
				$s_mail = new c_order_info_to_mail((int)$rows['orders_id']);
				$email_text .= db_to_html($s_mail->getString());
				unset($s_mail);
				// by lwkai 2012-06-04 end


				/* 暂取消逾期未付款的旅客名称信息{
				$email_text .= db_to_html('逾期未付款的旅客名称如下：')."\n\n";
				//取得该订单所有未付款的客户名称
				$no_pay_sql = tep_db_query('SELECT guest_name FROM `orders_travel_companion` WHERE orders_id = "'.(int)$rows['orders_id'].'" AND orders_travel_companion_status < 1 ');
				while($no_pay_rows = tep_db_fetch_array($no_pay_sql)){
				$email_text .= db_to_html($no_pay_rows['guest_name'])."\n";
				}
				$email_text .= db_to_html("\n非常抱歉给您带来不便。我们会尽快跟进后续的处理程序！\n\n");
				暂取消逾期未付款的旅客名称信息}* /

				$email_text .= db_to_html("\n此邮件为系统自动发出，请勿直接回复！\n\n");
				$email_text .= db_to_html(CONFORMATION_EMAIL_FOOTER)."\n\n";

				$from_email_name = STORE_OWNER;
				$from_email_address = 'automail@usitrip.com';
				$email_charset = CHARSET;

				echo "\n".$to_name."\n";
				echo $to_email_address."\n";
				echo $email_subject."\n";
				echo $email_text."\n";
				echo $from_email_name."\n";
				echo $from_email_address."\n";
				echo $email_charset."\n";

				tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset);
				*/
				//echo $rows['orders_id']."\n";
			} //end for

		}// end while

		// 发送邮件类　 by lwkai 2012-07-03 add
		if (class_exists('send_mail_ready') == false) {
			require_once DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'send_mail_ready.php';
		}
		if (class_exists('companions_reminder_mail') == false){
			require_once DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'companions_reminder_mail.php';

		}
		//　类结束
		foreach($rtn as $key => $val) {
			$str_arr = explode('_',$key);
			//  发邮件  by lwkai 2012-07-03 add
			new companions_reminder_mail($str_arr[1], $str_arr[0],2);
			// 发邮件结束
		}
	}
}

/**
 * 取得结伴同游订单的电子邮件列表
 * @param $order_id
 * @param $products_id
 * @author Howard
 */
function get_order_travel_companion_email_list($order_id, $products_id=0){
	if(!(int)$order_id){ return false;}
	$where_p ="";
	if((int)$products_id>0){
		$where_p = ' AND products_id="'.(int)$products_id.'" ';
	}

	$to_email_address =array();
	$e_sql = tep_db_query('SELECT customers_id FROM `orders_travel_companion` WHERE  orders_id="'.(int)$order_id.'" '.$where_p.' Order By products_id ASC ');
	while($e_rows = tep_db_fetch_array($e_sql)){
		if((int)$e_rows['customers_id']){
			$mail_string = tep_get_customers_email((int)$e_rows['customers_id']);
			if(!in_array($mail_string,$to_email_address)){
				$to_email_address[]= $mail_string;
			}
		}
	}
	if(count($to_email_address)){
		$to_email_address = join(',',$to_email_address);
		return $to_email_address;
	}

	return '';
}

/**
 * 订单催款提醒
 * $space_day 到达下单后7天如果没有付款催一次客人
 * 没有销售手动去催款时，系统在生成订单后的7天自动发出。要排除的订单状态为(6,100137,100054,100094)
 * 7天未付款发送催促付款邮件，此功能仅对订单状态时pending时(同时没有付过款)有用，需要以订单状态为准，凡销售更改了订单状态，如：催促付款/中国银行转账 等，就不会发送催促付款的邮件！
 * @author Howard
 */
function orders_pay_note($space_day = 7){
	if(!(int)$space_day){
		return false;
	}
	//如果不是生产站则马上停止，以免在测试站发到真实的客人那里去了
	if(IS_LIVE_SITES == false || !defined('IS_LIVE_SITES')){
		return false;
	}
	$that_date = date('Y-m-d',time()-$space_day*24*3600);
	//$sql_str = 'SELECT customers_id,orders_id,customers_name,customers_email_address,date_purchased FROM `orders` where orders_paid < 1 AND orders_status NOT IN(6,100137,100054,100094) AND date_purchased < "'.$that_date.' 00:00:00" AND has_sent_pay_note="0" ';
	$sql_str = 'SELECT customers_id,orders_id,customers_name,customers_email_address,date_purchased FROM `orders` where orders_paid < 1 AND orders_status="1" AND date_purchased < "'.$that_date.' 00:00:00" AND has_sent_pay_note="0" ';
	$sql = tep_db_query($sql_str);
	$mail_subject_str = '走四方(usitrip.com)请尽快支付您的订单（订单号%s）';
	$customer_notified ='尊敬的{客户名称}先生/女士：'."\n";
	$customer_notified.='您在走四方网站{购买日期}提交的订单：{订单ID}，截至邮件发送之时尚未查询到相关付款记录；请尽快安排支付预订费用。如果您确认已经付款，请直接与我们客服人员联系进行核实；我们会尽快处理您的订单！'."\n";
	$customer_notified.='（温馨提示：在您成功支付、收到旅游电子参团凭证前，您的座位未被保留）'."\n";
	$customer_notified.='订单详情：<a target="_blank" href="{订单详细页}">{订单ID}</a>（您可以使用预订时注册的Email登录您的账户来查询订单详情）'."\n\n";
	$orders_status_value = '100137';	//发送催款邮件后订单更新什么状态？
	while ($rows = tep_db_fetch_array($sql)){
		$_check_history_sql = tep_db_query('SELECT orders_status_history_id FROM `orders_status_history` WHERE orders_id = "'.(int)$rows['orders_id'].'" and orders_status_id IN(6,100137,100054,100094) Limit 1 ');
		$_check_history = tep_db_fetch_array($_check_history_sql);
		if(!(int)$_check_history['orders_status_history_id']){
			$date_time = date('Y-m-d H:i:s');
			$customers_name = $rows['customers_name'];
			$customers_email_address = $rows['customers_email_address'];
			if(!tep_not_null($customers_name) || !tep_not_null($customers_email_address)){	//如果用户姓名和邮箱只要有一个为空都重新到客户表重新取得数据
				$customer_original_info = tep_get_customers_info($rows['customers_id']);
				$customers_name = $customer_original_info["customers_firstname"];
				$customers_email_address = $customer_original_info["customers_email_address"];
			}
			$replace = array('{客户名称}'=>$customers_name, '{购买日期}'=>date('Y年m月d日',strtotime($rows['date_purchased'])),'{订单ID}'=>$rows['orders_id'],'{订单详细页}'=>tep_catalog_href_link('account_history_info.php','order_id='.$rows['orders_id']));
			$_customer_notified = strtr($customer_notified, $replace);
			//修改订单状态
			tep_db_query("update `orders` SET has_sent_pay_note='1',orders_status ='{$orders_status_value}', last_modified='{$date_time}', next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id ='{$rows['orders_id']}'");
			tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
						(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)
						values ('" .$rows['orders_id']. "', '" .$orders_status_value. "', '".$date_time."', '1','" . tep_db_input(tep_db_prepare_input($_customer_notified)) . "','".CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID."')");
			//发邮件
			$user_lang_code = customers_language_code($to_email_address);
			$email_subject = iconv('gb2312', $user_lang_code . '//IGNORE', sprintf($mail_subject_str,$rows['orders_id']))." ";
			$to_name = iconv('gb2312', $user_lang_code . '//IGNORE', $customers_name);
			$to_email_address = $customers_email_address;
			$email_text = $_customer_notified;
			$email_text.= CONFORMATION_EMAIL_FOOTER;
			$email_text = iconv('gb2312', $user_lang_code . '//IGNORE', $email_text);
			$from_email_name = STORE_OWNER;
			$from_email_name = iconv('gb2312', $user_lang_code . '//IGNORE', $from_email_name);
			$from_email_address = 'automail@usitrip.com';
			if(tep_not_null($to_email_address)){
				tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address,'true', $user_lang_code);
			}
		}
		//echo $rows['orders_id']; exit;
	}
	return true;
}

/**
 * 结伴同游订单付款提醒通知邮件
 * @author Howard
 */
function orders_travel_companion_note(){

	//require(DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'order_info_to_mail.php');

	if(!defined('TRAVEL_COMPANION_MAX_PAY_DAY') || !defined('TRAVEL_COMPANION_OFF_ON') || TRAVEL_COMPANION_OFF_ON != 'true'){
		return false;
	}

	$max_day_num = (int)TRAVEL_COMPANION_MAX_PAY_DAY + 1; //1 让下单时间大于三天 常量在我写注释的时候是3
	$end_time = date("Y-m-d", strtotime('-'.$max_day_num.' day'));
	$to_day = date("Y-m-d");
	//echo $end_time;
	//一、取得未完成付款的订单号 //date_purchased 下单时间

	$order_sql = tep_db_query('SELECT o.orders_id,tc.orders_travel_companion_status,o.orders_status,o.date_purchased FROM `orders_travel_companion` tc , `orders` o WHERE tc.orders_id=o.orders_id AND tc.orders_travel_companion_status<1 AND o.orders_status !="6" and tc.is_child = "false" and o.date_purchased<"' . $end_time . '"  Group By tc.orders_id order by o.orders_id desc');
	//echo 'SELECT o.orders_id,tc.orders_travel_companion_status,o.orders_status,o.date_purchased FROM `orders_travel_companion` tc , `orders` o WHERE tc.orders_id=o.orders_id AND tc.orders_travel_companion_status<1 AND o.orders_status !="6" and tc.is_child = "false" and o.date_purchased<"' . $end_time . '"  Group By tc.orders_id order by o.orders_id desc';
	$orders_ids = array();
	while($order_rows = tep_db_fetch_array($order_sql)){
		$orders_ids[] = $order_rows['orders_id'];
	}
	$orders_ids = join(",",$orders_ids);

	//echo $orders_ids."\n";
	$rtn = array();
	if($orders_ids!=""){
		$sql = 'SELECT customers_id,orders_id,expired_date FROM `orders_travel_companion` WHERE orders_id in('.$orders_ids.') AND customers_id > 0 AND orders_travel_companion_status<1 AND last_send_mail_date < "'.$to_day.'" and is_child="false"';
		//echo $sql . '<br/>';
		$sql = tep_db_query($sql);

		while($rows=tep_db_fetch_array($sql)){
			if((int)$rows['customers_id']){
				$rtn[$rows['orders_id'] . '_' . $rows['customers_id']] = $rows['orders_id'];

				/*在发邮件前新更新最后发送日期，以免被重发
				echo 'update orders_travel_companion SET last_send_mail_date="'.$to_day.'" WHERE customers_id="'.(int)$rows['customers_id'].'" AND orders_id="'.(int)$rows['orders_id'].'" ';
				tep_db_query('update orders_travel_companion SET last_send_mail_date="'.$to_day.'" WHERE customers_id="'.(int)$rows['customers_id'].'" AND orders_id="'.(int)$rows['orders_id'].'" ');*/


				//tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset);

				//echo $rows['orders_id']."\n";
			}

		}

		// 发送邮件 by lwkai 2012-06-29 add
		if (class_exists('send_mail_ready') == false) {
			require_once DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'send_mail_ready.php';
		}
		if (class_exists('companions_reminder_mail') == false){
			require_once DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'companions_reminder_mail.php';

		}

		//print_r($rtn);

		foreach ($rtn as $key => $val){
			$str_arr = explode('_', $key);
			tep_db_query('update orders_travel_companion SET last_send_mail_date="'.$to_day.'" WHERE customers_id="'.(int)$str_arr[1].'" AND orders_id="'.(int)$str_arr[0].'" ');
			//echo $str_arr[1].','. $str_arr[0] .'<br/>';
			new companions_reminder_mail($str_arr[1], $str_arr[0]);
		}
		// 发送邮件结束
	}
}

//根据客户帐号取得客户使用的言语编码
function customers_language_code($customers_email_address){
	$sql = tep_db_query('SELECT customers_char_set FROM `customers` WHERE customers_email_address ="'.tep_db_prepare_input($customers_email_address).'" limit 1 ');
	$row = tep_db_fetch_array($sql);
	if(strtolower($row['customers_char_set'])=='big5'){
		return $row['customers_char_set'];
	}else{
		return 'gb2312';
	}
}

//取得结伴同游帖子的类型
function tep_bbs_type_name($type_id){
	if($type_id==100 || $type_id==0){ return false;}

	$sql = tep_db_query('SELECT * FROM `travel_companion_bbs_type` WHERE type_id="'.(int)$type_id.'" LIMIT 1 ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['type_name'])){
		return $row['type_name'];
	}
	return false;
}

/**
 * 检测订单中是否有团体购买的
 * @param unknown_type $order_id
 * @author Howard
 */
function have_group_buy($order_id){
	$sql = tep_db_query('SELECT group_buy_discount FROM `orders_products` WHERE orders_id="'.(int)$order_id.'" ');
	while($rows = tep_db_fetch_array($sql)){
		if($rows['group_buy_discount']>0){
			return true;
		}
	}
	return false;
}

/**
 * 检测订单中是否有新团购团
 * @param unknown_type $order_id
 * @author Howard
 */
function have_new_group_buy($order_id){
	$sql = tep_db_query('SELECT is_new_group_buy FROM `orders_products` WHERE orders_id="'.(int)$order_id.'" ');
	while($rows = tep_db_fetch_array($sql)){
		if($rows['is_new_group_buy']=="1"){
			return true;
		}
	}
	return false;
}

/**
 * 检测订单中是否有未定出发时间的团购团
 * @param unknown_type $order_id
 * @author Howard
 */
function is_no_sel_date_for_group_buy($order_id){
	$sql = tep_db_query('SELECT no_sel_date_for_group_buy FROM `orders_products` WHERE orders_id="'.(int)$order_id.'" ');
	while($rows = tep_db_fetch_array($sql)){
		if($rows['no_sel_date_for_group_buy']>0){
			return true;
		}
	}
	return false;
}

//从 tour_question 提取电子邮件地址到 newsletters_email
function get_email_for_question_to_newsletters($email=''){
	if($email==''){
		$sql = tep_db_query('SELECT customers_email FROM `tour_question` WHERE accept_newsletter ="1" Group By customers_email ');
		while($rows = tep_db_fetch_array($sql)){
			if(preg_match('/@/',$rows['customers_email']) ){
				$check_sql = tep_db_query('SELECT customers_id FROM `customers` WHERE `customers_email_address` = "'.$rows['customers_email'].'" Limit 1 ');
				$check_row = tep_db_fetch_array($check_sql);
				$check1_sql = tep_db_query('SELECT newsletters_email_id FROM `newsletters_email` WHERE `newsletters_email_address` = "'.$rows['customers_email'].'" Limit 1 ');
				$check1_row = tep_db_fetch_array($check1_sql);

				if(!(int)$check_row['customers_id'] && !(int)$check1_row['newsletters_email_id']){
					tep_db_query('INSERT INTO `newsletters_email` (`newsletters_email_address`) VALUES ("'.$rows['customers_email'].'");');
				}
			}
		}
	}else{
		$check_sql = tep_db_query('SELECT customers_id FROM `customers` WHERE `customers_email_address` = "'.mysql_real_escape_string($email).'" Limit 1 ');
		$check_row = tep_db_fetch_array($check_sql);
		$check1_sql = tep_db_query('SELECT newsletters_email_id FROM `newsletters_email` WHERE `newsletters_email_address` = "'.mysql_real_escape_string($email).'" Limit 1 ');
		$check1_row = tep_db_fetch_array($check1_sql);

		if(!(int)$check_row['customers_id'] && !(int)$check1_row['newsletters_email_id']){
			tep_db_query('INSERT INTO `newsletters_email` (`newsletters_email_address`) VALUES ("'.$email.'");');
		}
	}
	return true;
}

//howard added for 单人配房标记
function get_single_pu_tags($order_id, $pord_id){
	$orders_eticket_query = tep_db_query("select guest_name, agree_single_occupancy_pair_up from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$order_id . "' and products_id=".(int)$pord_id." ");
	$result = tep_db_fetch_array($orders_eticket_query);

	$gender_tags = '';
	if($result['agree_single_occupancy_pair_up']=="1"){
		$gender_tags = '系统不知客人性别';
	}

	if(tep_not_null($result['guest_name'])){
		$guestnames = explode('<::>',$result['guest_name']);
		foreach((array)$guestnames as $key => $val){
			if(preg_match('/^.*\((f|m)\)$/',$val)){
				$gender_tags = str_replace('(f)','(Female)',$val);
				$gender_tags = str_replace('(m)','(Male)',$val);
				break;
			}
		}
		//return $result['guest_name'];
	}
	return $gender_tags;
}

function get_datedifference($date_from, $date_to='', $compare_db='db'){
	if($date_to=='' || !tep_not_null($date_to)){
		if($compare_db=='db'){
			$qry_cur_sql_time="SELECT now() as cur_time FROM ".TABLE_ORDERS." LIMIT 1";
			$res_cur_sql_time=tep_db_query($qry_cur_sql_time);
			$row_cur_sql_time=tep_db_fetch_array($res_cur_sql_time);
			$date_to=strtotime($row_cur_sql_time['cur_time']);//time();
		}else{
			$date_to=time();
		}
	}else{
		$date_to=strtotime($date_to);
	}
	$date_from=strtotime($date_from);

	$dateDiff = $date_to - $date_from;
	$fullDays = floor($dateDiff/(60*60*24));
	if((int)$fullDays<=0){
		$fullHours = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
		if((int)$fullHours<=0){
			$fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);
			$ret_diff=$fullMinutes.' '.($fullMinutes=='1'?'minute':'minutes');
		}else{
			$ret_diff=$fullHours.' '.($fullHours=='1'?'hour':'hours');
		}
	}else{
		$ret_diff=$fullDays.' '.($fullDays=='1'?'day':'days');
	}

	return $ret_diff;
}

function set_providers_agency($providers_id, $agency_id)
{
	//$qry_check_existing="SELECT pl.providers_id FROM ".TABLE_PROVIDERS_LOGIN." pl WHERE pl.providers_id='".$providers_id."' AND (CONCAT(',', pl.providers_agency_id, ',') REGEXP ',".$agency_id.",')";
	$qry_check_existing="SELECT pl.providers_id FROM ".TABLE_PROVIDERS_LOGIN." pl WHERE pl.providers_email_address='".$providers_id."' AND (CONCAT(',', pl.providers_agency_id, ',') REGEXP ',".$agency_id.",')";
	$res_check_existing=tep_db_query($qry_check_existing);

	if(tep_db_num_rows($res_check_existing)==0)
	{
		$agency_id="if((length(providers_agency_id)>0), concat(providers_agency_id, ',', ".$agency_id.") , ".$agency_id.")";
		$qry_update_agency="UPDATE ".TABLE_PROVIDERS_LOGIN." SET providers_agency_id=".$agency_id." WHERE providers_id='".$providers_id."'";
		$res_update_agency=tep_db_query($qry_update_agency);
	}
}

function tep_get_providers_agency_id($providers_id)
{
	$qry_get_agency_id="SELECT providers_agency_id FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_id='".$providers_id."'";
	$res_get_agency_id=tep_db_query($qry_get_agency_id);
	if(tep_db_num_rows($res_get_agency_id)>0)
	$row_get_agency_id=tep_db_fetch_array($res_get_agency_id);
	else
	$row_get_agency_id=array();

	return $row_get_agency_id;
}
function tep_get_providers_agency($providers_id, $from_agency_id='0')//$from_agency_id=1 for direct from agency id else from providers id
{
	$ret_agency="";
	if($from_agency_id=='1'){
		$row_get_agency_id=array('providers_agency_id' => $providers_id);
	}else{
		$row_get_agency_id=tep_get_providers_agency_id($providers_id);
	}
	$agency_id_in="";
	if(count($row_get_agency_id)>0){
		if(tep_not_null($row_get_agency_id['providers_agency_id']))
		$agency_id_in=$row_get_agency_id['providers_agency_id'];
		else
		$agency_id_in=0;
	}
	else
	$agency_id_in=0;

	$qry_providers_agency = "select a.agency_id, a.agency_name, a.agency_name1 from " . TABLE_TRAVEL_AGENCY . " a where a.agency_id IN (".$agency_id_in.")";
	$res_providers_agency=tep_db_query($qry_providers_agency);
	while($row_providers_agency = tep_db_fetch_array($res_providers_agency))
	{
		if($ret_agency!="")
		$ret_agency.=", ";

		//$ret_agency.=tep_db_output($row_providers_agency['agency_name']);
		$ret_agency.=tep_db_output($row_providers_agency['agency_name1']);
	}

	return $ret_agency;
}

/**
 * 检查某产品是否需要提与供应商交流的输入框
 *
 * @param unknown_type $products_id
 * @param unknown_type $orders_id
 * @return unknown
 */
function get_if_display_provider_status_history($products_id, $orders_id){
	$qry_is_provider_active="SELECT ta.providers_display_status_hist, ta.providers_start_date, o.date_purchased, DATEDIFF(o.date_purchased, ta.providers_start_date) as date_diff_from_today, DATEDIFF(op.order_item_purchase_date, ta.providers_start_date) as date_diff_order_item, op.order_item_purchase_date FROM ".TABLE_TRAVEL_AGENCY." ta, ".TABLE_PRODUCTS." p, ".TABLE_ORDERS." o, ".TABLE_ORDERS_PRODUCTS." op WHERE ta.agency_id = p.agency_id AND p.products_id = op.products_id AND op.orders_id = o.orders_id AND op.orders_id = '".$orders_id."' AND p.products_id = '".$products_id."'";
	$res_is_provider_active=tep_db_query($qry_is_provider_active);
	$row_is_provider_active=tep_db_fetch_array($res_is_provider_active);
	if( $row_is_provider_active['providers_display_status_hist']=='1' /* && ( ($row_is_provider_active['date_diff_from_today'] >= 0) || ($row_is_provider_active['date_diff_order_item'] >= 0 && $row_is_provider_active['order_item_purchase_date'] != '0000-00-00 00:00:00')) */ ){
		$display_providers_comment=1;
	}else{
		$display_providers_comment=0;
	}
	return $display_providers_comment;
}

function tep_get_provider_order_status_name($orders_status_id, $language_id = '') {
	global $languages_id;

	if (!$language_id) $language_id = $languages_id;
	$orders_status_query = tep_db_query("select provider_order_status_name from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " where provider_order_status_id = '" . (int)$orders_status_id . "'");// and language_id = '" . (int)$language_id . "'");
	$orders_status = tep_db_fetch_array($orders_status_query);

	return $orders_status['provider_order_status_name'];
}

// Sets the status of a providers
function tep_set_providers_status($providers_id, $status) {
	if ($status == '1') {
		return tep_db_query("update " . TABLE_PROVIDERS_LOGIN . " set providers_status = '1' where providers_id = '" . (int)$providers_id . "'");
	} elseif ($status == '0') {
		return tep_db_query("update " . TABLE_PROVIDERS_LOGIN . " set providers_status = '0' where providers_id = '" . (int)$providers_id . "'");
	} else {
		return -1;
	}
}

/**
 * 使用订单ID号和产品ID号，获取具体的航班信息
 * 
 * 后台"订单"(Order)信息的"Reservation List"下的"航班信息"(Flight Information)
 *
 * @param int $orders_products_id 产品快照ID
 * @return HTML 航班信息
 * @author Amit
 */
function get_flignt_info_popup($orders_products_id=0){
	global $order;
	$qry_flight_info = "SELECT op.products_departure_date, p.products_durations, p.products_durations_type, f.orders_flight_id, f.sent_confirm_email_to_provider, f.airline_name, f.flight_no, f.airline_name_departure, f.flight_no_departure, f.airport_name, f.airport_name_departure, f.arrival_date, f.arrival_time, f.departure_date , f.departure_time, f.show_warning_on_admin,f.admin_job_number, f.admin_time, p.products_id,op.orders_id
	FROM ".TABLE_ORDERS_PRODUCTS_FLIGHT." f, ".TABLE_ORDERS_PRODUCTS." op, ".TABLE_PRODUCTS." p 
	WHERE op.orders_id = f.orders_id AND op.products_id = f.products_id AND p.products_id = op.products_id AND f.orders_products_id = '".(int)$orders_products_id."' ";
	$res_flight_info = tep_db_query($qry_flight_info);
	$row_flight_info = tep_db_fetch_array($res_flight_info);

	$ret_flight_info = "";
	if(tep_not_null($row_flight_info["arrival_date"]) || tep_not_null($row_flight_info["departure_date"])){
		$products_departure_date = tep_get_date_disp($row_flight_info["products_departure_date"]);
		if($row_flight_info['products_durations'] > 0 && $row_flight_info['products_durations_type'] == 0){
			$prod_dura_day = $row_flight_info["products_durations"]-1;
		}else{
			$prod_dura_day = 0;
		}
		$flight_info_font="";
		$depart_start_font="";
		$depart_end_font="";
		$departure_date_same_arrival_date = true;
		if(date("Y-m-d", strtotime($row_flight_info['products_departure_date'])) != date("Y-m-d", strtotime($row_flight_info['arrival_date']))){
			$depart_start_font='color="#FF0000"';
			$flight_info_font=' style="color:red;"';
			$departure_date_same_arrival_date = false;
		}

		$departure_date_end = tep_get_date_db(date_add_day($prod_dura_day, 'd', $products_departure_date));
		$f_departure_date = tep_get_date_db($row_flight_info['departure_date']);
		if( $departure_date_end != $f_departure_date ){
			$depart_end_font='color="#FF0000"';
			$flight_info_font=' style="color:red;"';
		}

		//如第一天的到达时期和航班信息一致，并且离开的航班信息晚于此团的结束时间，系统自动将航班信息发送给PAR，SEA等使用provider accounting的供应商
		//if(($order->info['orders_status']!="100006" && $order->info['orders_status']!="6") && $departure_date_same_arrival_date == true && $departure_date_end < $f_departure_date ){
		//该订单中，达到航班信息与团的开始时间一致，无论离开航班信息与此团结束时间一致或不一致或为空，都需要自动发送"Pls confirm flight info."给供应商
		if(($order->info['orders_status']!="100006" && $order->info['orders_status']!="6") && $departure_date_same_arrival_date == true ){
			$js_codes = '<script type="text/javascript">jQuery().ready(function(){';
			$autoSendMail = false;
			if($autoSendMail == true && !(int)$row_flight_info['sent_confirm_email_to_provider']){	//自动给供应商发邮件
				$js_codes.= 'function auto_sent_flight_confirm_to_provider_'.$orders_products_id.'(){';
				$js_codes.= '	if(confirm("该订单中，达到航班信息与团的开始时间一致，无论离开航班信息与此团结束时间一致或不一致或为空，都需要自动发送"Pls confirm flight info."给供应商！是否确定发送？")){';
				$js_codes.= '		var frm = document.getElementById("edit_order"); if(frm==null){ return false; }';
				$js_codes.= '		frm.elements["provider_order_status_id_'.$orders_products_id.'"].value = "15"; ';
				$js_codes.= '		frm.elements["btnProvidersConfirmation_'.$orders_products_id.'"].click(); ';
				$js_codes.= '	}';
				$js_codes.= '} auto_sent_flight_confirm_to_provider_'.$orders_products_id.'(); ';

				tep_db_query('UPDATE '.TABLE_ORDERS_PRODUCTS_FLIGHT.' SET sent_confirm_email_to_provider="1" WHERE orders_flight_id="'.$row_flight_info['orders_flight_id'].'" ');
			}

			$js_codes.= 'jQuery("#hotelEx_'.$orders_products_id.'").before("<div class=col_red><b>请提示客人增加预订酒店延住</b></div>");';
			$js_codes.= '});</script>';
			$ret_flight_info .= $js_codes;
		}

		$last_added_date_str = '[最后一次日期位]';
		$last_added_date = '';
		$ret_flight_info.='
						<span><a class="thumbnail" href="javascript:void(0);"><strong><nobr><u '.$flight_info_font.'>'.TXT_FLIGHT_INFO.'</u></nobr></strong>
						<span style="text-align:left;">
							<table id="flightInformationHistory_'.$orders_products_id.'" class="dataTableContent" border="0" width="100%">
								<tr>
									<td colspan="4"><strong>'.TITLE_FLIGHT_INFO.'</strong></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><strong>'.TITLE_AR.' (<font '.$depart_start_font.'>'.sprintf(TXT_TOUR_STARTS_ON, $products_departure_date).'</font>)</strong></td>
									<td colspan="2" align="center"><strong>'.TITLE_DR.' (<font '.$depart_end_font.'>'.sprintf(TXT_TOUR_ENDS_ON, date_add_day($prod_dura_day, 'd', $products_departure_date)).'</font>)</strong></td>
								</tr>
								';
		if ((int)$row_flight_info['admin_job_number'] > 0) {
			$ret_flight_info .= '<tr><td colspan="4" style="color:red">审核：' . $row_flight_info['admin_job_number'] . '，' . $row_flight_info['admin_time'] . '</td></tr>';
		}
		$ret_flight_info .= '<tr><td colspan="4">{历史信息存放区}</td></tr>';
		$ret_info = '<table class="flignt_info_history">
								<tr>
									<td class="title" width="18%">添加日期:</td>
									<td class="title" colspan="2">'.$last_added_date_str.'</td>';
		$ret_info .= '         </tr>';
		$ret_info .= '         <tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_AIRLINE_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["airline_name"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_AIRLINE_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["airline_name_departure"]).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_FLIGHT_NUM.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["flight_no"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_FLIGHT_NUM.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["flight_no_departure"]).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_AIRPORT_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["airport_name"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_AIRPORT_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["airport_name_departure"]).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_DATE.'</td>
									<td nowrap="nowrap">'.tep_db_output(tep_get_date_db($row_flight_info["arrival_date"],'m/d/Y')).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_DATE.'</td>
									<td nowrap="nowrap">'.tep_db_output(tep_get_date_db($row_flight_info["departure_date"],'m/d/Y')).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_TIME.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["arrival_time"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_TIME.'</td>
									<td nowrap="nowrap">'.tep_db_output($row_flight_info["departure_time"]).'</td>
								</tr>
							</table>
							';
		//调入历史记录
		$h_sql = tep_db_query('SELECT * FROM `orders_product_flight_history` where orders_products_id ="'.(int)$orders_products_id.'" order by history_id DESC; ');
		$_loop = 0;
		while($h_rows = tep_db_fetch_array($h_sql)){
			$_loop++;
			$by = ' by customer';
			if($h_rows['admin_id']){
				$by = ' by '.tep_get_admin_customer_name($h_rows['admin_id']);
			}
			if($_loop>1){	//因为最新的一条与当前的航班信息一样，所以第一条不用显示。
				$ret_info.= '<hr>
							<table class="flignt_info_history">
							<tr>
							<td class="title" width="18%">添加日期:</td>
							<td class="title" colspan="3">' . ($h_rows['add_date']) .$by. '</td>
							
							</tr>
							<tr>
									
									<td class="title" nowrap="nowrap">'.TITLE_AR_AIRLINE_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["airline_name"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_AIRLINE_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["airline_name_departure"]).'</td>
								</tr>
								<tr>
									
									<td class="title" nowrap="nowrap">'.TITLE_AR_FLIGHT_NUM.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["flight_no"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_FLIGHT_NUM.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["flight_no_departure"]).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_AIRPORT_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["airport_name"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_AIRPORT_NAME.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["airport_name_departure"]).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_DATE.'</td>
									<td nowrap="nowrap">'.tep_db_output(tep_get_date_db($h_rows["arrival_date"],'m/d/Y')).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_DATE.'</td>
									<td nowrap="nowrap">'.tep_db_output(tep_get_date_db($h_rows["departure_date"],'m/d/Y')).'</td>
								</tr>
								<tr>
									<td class="title" nowrap="nowrap">'.TITLE_AR_TIME.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["arrival_time"]).'</td>
									<td class="title" nowrap="nowrap">'.TITLE_DR_TIME.'</td>
									<td nowrap="nowrap">'.tep_db_output($h_rows["departure_time"]).'</td>
								</tr>
								</table>';
			}elseif($_loop==1){	//把最后一次更新的日期取出来替换[最后一次日期位]的内容
				$last_added_date = $h_rows['add_date'].$by;
			}
		}

		$ret_flight_info.='</table></span></a>';

		if((int)$row_flight_info['show_warning_on_admin']){
			$ret_flight_info .= '<a target="_blank" id="is_read_btn_'.(int)$row_flight_info['orders_flight_id'].'" onclick="is_read('.(int)$row_flight_info['orders_flight_id'].',' . $orders_products_id . ',' . $row_flight_info['orders_id'] . ',' . $row_flight_info['products_id'] . ');return false;" style="cursor:pointer; color:#FF0000" basehref="'.tep_href_link('orders_warning.php','action=hidden_flight&orders_flight_id='.(int)$row_flight_info['orders_flight_id'].'&orders_products_id=' . $orders_products_id . '&products_id=' . $row_flight_info['products_id'] . '&orders_id=' . $row_flight_info['orders_id']).'">[已阅]</a>'; //已阅就是在 航班信息更新 中关闭这条信息
		}
		$ret_flight_info.='</span>';
	}

    $ret_flight_info = str_replace('{历史信息存放区}', $ret_info, $ret_flight_info);
	//用真正的日期替换[最后一次日期位]
	$ret_flight_info = str_replace($last_added_date_str, $last_added_date, $ret_flight_info);
	return $ret_flight_info;
}

/**
	*获取产品快照的客户email信息
	*/
function tep_get_products_guest_emails_lists($ord_product_id){
	$str_display_guest_email_list_dis = '';
	$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_products_id=".(int)$ord_product_id." ");//where orders_id = '" . (int)$ordid . "' and products_id=".(int)$productid." ");
	$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
	$guestemails = explode('<::>',$orders_eticket_result['guest_email']);
	if($orders_eticket_result['guest_number']==0){
		foreach($guestemails as $key=>$val){
			$loop = $key;
		}
	}else{
		$loop = $orders_eticket_result['guest_number'];
	}
	for($noofguest=1;$noofguest<=$loop;$noofguest++){
		$str_display_guest_email_list_dis .= stripslashes($guestemails[($noofguest-1)]) .'<br />';
	}
	return $str_display_guest_email_list_dis;
}

function tep_is_gift_certificate_product($products_id)
{
	/* //amit commented because no gift certificate  on TFF
	$qry_is_gc="SELECT pc.* FROM ".TABLE_PRODUCTS_TO_CATEGORIES." pc WHERE pc.products_id='".(int)$products_id."' AND pc.categories_id='".GIFT_CERTIFICATE_CAT_ID."'";
	$res_is_gc=tep_db_query($qry_is_gc);

	if(tep_db_num_rows($res_is_gc))
	return true;
	else
	return false;
	*/
	return false;
}

function tep_get_products_provider_name($product_id, $language = '') {
	global $languages_id;

	if (empty($language)) $language = $languages_id;
	$product_query = tep_db_query("select products_name_provider from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_name_provider'];
}


//amit added to check guest by parse string start

function tep_get_rooms_adults_childern($roomsinfo_string,$room,$customer_type){
	$parse_child = tep_get_total_of_children_in_room($room);
	$parse_adult = tep_get_total_of_adult_in_room($room);

	switch ($customer_type) {
		case 'adult' :

			if(preg_match('/'.preg_quote($parse_adult).' ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
		case 'children' :
			if(preg_match('/'.preg_quote($parse_child).' ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
		case 'price' :
			if(preg_match('/<br>'.TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM.' '.$room.': ([$][0-9.,]+)/', $roomsinfo_string, $m)) {
				$get_room_info_price = explode(TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM.' '.$room.': ', $roomsinfo_string);
				$rtn_val = $m[1];
			}
			break;
	}

	if($customer_type == 'price'){
		return $rtn_val;
	}else{
		return (int)$rtn_val;
	}
}

function tep_get_no_adults_childern($roomsinfo_string,$customer_type){

	switch ($customer_type) {
		case 'adult' :

			if(preg_match('/'.preg_quote(TEXT_SHOPPIFG_CART_ADULTS_NO).' : ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
		case 'children' :
			if(preg_match('/'.preg_quote(TEXT_SHOPPIFG_CART_CHILDREDN_NO).' : ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
		case 'price' :
			if(preg_match('/'.TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL.' : ([$][0-9.,]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
	}

	if($customer_type == 'price'){
		return $rtn_val;
	}else{
		return (int)$rtn_val;
	}
}

function tep_get_total_nos_of_rooms($roomsinfo_string){
	if(preg_match('/'.preg_quote(TEXT_TOTAL_OF_ROOMS).'([0-9]+)/', $roomsinfo_string, $m)) {
		$rtn_val = $m[1];
	}

	return (int)$rtn_val;
}

//amit added to check guest by parse string end

function get_cancellation_history_popup($orders_id, $products_id, $orders_products_id=0){
	global $cancellation_history_bottom;
	$get_cancellation_history = tep_db_query("select cancellation_history from ".TABLE_ORDERS." where orders_id = '".$orders_id."'");
	$row_cancellation_history = tep_db_fetch_array($get_cancellation_history);
	if(tep_not_null($row_cancellation_history['cancellation_history'])){
		$cancellation_history_array = explode("||==||", $row_cancellation_history['cancellation_history']);
		$cancellation_history_exists = false;
		foreach($cancellation_history_array as $key=>$val){
			$cancellation_history_values = explode("||", $val);
			$split_orders_products_id = explode("$$", $cancellation_history_values[0]);
			if(tep_not_null($split_orders_products_id[1])){
				if($split_orders_products_id[1] == $orders_products_id){
					$cancellation_history_exists = true;
				}
			}else{
				if($split_orders_products_id[0] == $products_id){
					$cancellation_history_exists = true;
				}
			}
			if($cancellation_history_values[0] == $products_id){
				$cancellation_history_exists = true;
			}
		}
	}
	$ret_cancellation_history = '';
	if($cancellation_history_exists == true){
		$cancellation_history_bottom[$orders_products_id] = array();
		$ret_cancellation_history .= '<span><a class="thumbnail col_a1_popup" href="javascript:void(0);"><nobr><u>View Cancellation History</u></nobr><span style="width:850px;text-align:left;">
				<table class="dataTableContent" border="0" width="100%">
					<tr>
						<td valign="top"><b>Reason for Cancellation</b></td>
						<td valign="top"><b>Did you cancel with provider?</b></td>
						<td valign="top"><b>Customer\'s cancellation was received on</b></td>
						<td valign="top"><b>Customer Cancellation Fee</b></td>
						<td valign="top"><b>Provider Cancellation Fee</b></td>
						<td valign="top"><b>How did you calculate the cancellation fee?</b></td>
						<td valign="top"><b>Updated by</b></td>
					</tr>';
		$cancellation_history_array = explode("||==||", $row_cancellation_history['cancellation_history']);
		foreach($cancellation_history_array as $key=>$val){
			if(tep_not_null($val)){
				$cancellation_history_values = explode("||", $val);
				//if($cancellation_history_values[0] == $products_id){
				//$cancellation_history_bottom[$products_id][] = array($cancellation_history_values[1], $cancellation_history_values[2], $cancellation_history_values[3], (tep_not_null($cancellation_history_values[4])? '$'.$cancellation_history_values[4]:''), (tep_not_null($cancellation_history_values[5])?'$'.$cancellation_history_values[5]:''), nl2br(stripslashes($cancellation_history_values[6])), $cancellation_history_values[7].' On '.$cancellation_history_values[8]);
				$split_orders_products_id = explode("$$", $cancellation_history_values[0]);
				if(($cancellation_history_values[0] == $products_id && $split_orders_products_id[1] == '') || ($split_orders_products_id[1] == $orders_products_id)){
					$cancellation_history_bottom[$orders_products_id][] = array($cancellation_history_values[1], $cancellation_history_values[2], $cancellation_history_values[3], (tep_not_null($cancellation_history_values[4])? '$'.$cancellation_history_values[4]:''), (tep_not_null($cancellation_history_values[5])?'$'.$cancellation_history_values[5]:''), nl2br(stripslashes($cancellation_history_values[6])), $cancellation_history_values[7].' On '.$cancellation_history_values[8]);
					$ret_cancellation_history .= '<tr>
								<td valign="top">'.$cancellation_history_values[1].'&nbsp;</td>
								<td valign="top">'.$cancellation_history_values[2].'&nbsp;</td>
								<td valign="top">'.$cancellation_history_values[3].'&nbsp;</td>
								<td valign="top">'.(tep_not_null($cancellation_history_values[4])? '$'.$cancellation_history_values[4]:'').'&nbsp;</td>
								<td valign="top">'.(tep_not_null($cancellation_history_values[5])?'$'.$cancellation_history_values[5]:'').'&nbsp;</td>
								<td valign="top">'.nl2br(stripslashes($cancellation_history_values[6])).'&nbsp;</td>
								<td valign="top">'.$cancellation_history_values[7].' On '.$cancellation_history_values[8].'&nbsp;</td>
							</tr>';
				}
			}
		}
		$ret_cancellation_history .= '</table></span></a></span>';
	}
	return $ret_cancellation_history;

}

function tep_get_admin_customer_firstname($admin_id='', $user_type='0'){//0=Admin, 1=Providers
	if($user_type=='1')
	{
		$the_admin_customer_query=tep_db_query("select p.providers_firstname as admin_firstname, p.providers_lastname as admin_lastname, p.providers_email_address as admin_email_address FROM ".TABLE_PROVIDERS_LOGIN. " p where p.providers_id = '".$admin_id."'");
	}
	else
	{
		$the_admin_customer_query= tep_db_query("select admin_firstname, admin_lastname, admin_email_address  from " . TABLE_ADMIN . " where admin_id = '" . $admin_id . "'");
	}

	$the_admin_customer = tep_db_fetch_array($the_admin_customer_query);

	if($the_admin_customer['admin_firstname'] != ''){
		$str_admin = $the_admin_customer['admin_firstname']; //.' '.$the_admin_customer['admin_lastname']
	}else{
		$str_admin = $the_admin_customer['admin_email_address'];
	}
	return $str_admin;
}
function tep_get_tour_agency_information($products_id){
	$check_tour_agency_operate_currency_query = tep_db_query("select p.agency_id, ta.agency_name, case when p.transaction_fee > 0 then p.transaction_fee else ta.default_transaction_fee end as final_transaction_fee, ta.is_gender_info, ta.is_hotel_pickup_info from " . TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id = '" .$products_id. "'");
	$check_tour_agency_operate_currency = tep_db_fetch_array($check_tour_agency_operate_currency_query);
	return $check_tour_agency_operate_currency;
}

function tep_get_products_special_price($product_id) {
	$exchangecurrency = tep_get_tour_agency_operate_currency($product_id);
	$product_query = tep_db_query("select products_price, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
	if (tep_db_num_rows($product_query)) {
		$product = tep_db_fetch_array($product_query);
		//amit modified to make sure price on usd
		if($exchangecurrency != 'USD' && $exchangecurrency != ''){
			$product['products_price'] = tep_get_tour_price_in_usd($product['products_price'],$exchangecurrency);
		}
		//amit modified to make sure price on usd
		$product_price = $product['products_price'];
	} else {
		return false;
	}

	$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status = '1'");
	if (tep_db_num_rows($specials_query)) {
		$special = tep_db_fetch_array($specials_query);
		//amit modified to make sure price on usd
		if($exchangecurrency != 'USD' && $exchangecurrency != ''){
			$special['specials_new_products_price'] = tep_get_tour_price_in_usd($special['specials_new_products_price'],$exchangecurrency);
		}
		//amit modified to make sure price on usd
		$special_price = $special['specials_new_products_price'];
	} else {
		$special_price = false;
	}

	if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
		return $special_price;
	}

	$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $product_id . "'");
	$product_to_categories = tep_db_fetch_array($product_to_categories_query);
	$category = $product_to_categories['categories_id'];

	$sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type from " . TABLE_SALEMAKER_SALES . " where sale_categories_all like '%," . $category . ",%' and sale_status = '1' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_date_end >= now() or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')");
	if (tep_db_num_rows($sale_query)) {
		$sale = tep_db_fetch_array($sale_query);
	} else {
		return $special_price;
	}

	if (!$special_price) {
		$tmp_special_price = $product_price;
	} else {
		$tmp_special_price = $special_price;
	}

	switch ($sale['sale_deduction_type']) {
		case 0:
			$sale_product_price = $product_price - $sale['sale_deduction_value'];
			$sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
			break;
		case 1:
			$sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
			$sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
			break;
		case 2:
			$sale_product_price = $sale['sale_deduction_value'];
			$sale_special_price = $sale['sale_deduction_value'];
			break;
		default:
			return $special_price;
	}

	if ($sale_product_price < 0) {
		$sale_product_price = 0;
	}

	if ($sale_special_price < 0) {
		$sale_special_price = 0;
	}

	if (!$special_price) {
		return number_format($sale_product_price, 4, '.', '');
	} else {
		switch($sale['sale_specials_condition']){
			case 0:
				return number_format($sale_product_price, 4, '.', '');
				break;
			case 1:
				return number_format($special_price, 4, '.', '');
				break;
			case 2:
				return number_format($sale_special_price, 4, '.', '');
				break;
			default:
				return number_format($special_price, 4, '.', '');
		}
	}
}

function tep_get_total_of_adult_in_room_another($i){

	if($i == "1"){
		return TEXT_OF_ADULTS_IN_ROOM1_ANOTHER;
	}else if($i == "2"){
		return TEXT_OF_ADULTS_IN_ROOM2_ANOTHER;
	}else if($i == "3"){
		return TEXT_OF_ADULTS_IN_ROOM3_ANOTHER;
	}else if($i == "4"){
		return TEXT_OF_ADULTS_IN_ROOM4_ANOTHER;
	}else if($i == "5"){
		return TEXT_OF_ADULTS_IN_ROOM5_ANOTHER;
	}else if($i == "6"){
		return TEXT_OF_ADULTS_IN_ROOM6_ANOTHER;
	}
}
function tep_get_total_of_children_in_room_another($i){

	if($i == "1"){
		return TEXT_OF_CHILDREN_IN_ROOM1_ANOTHER;
	}else if($i == "2"){
		return TEXT_OF_CHILDREN_IN_ROOM2_ANOTHER;
	}else if($i == "3"){
		return TEXT_OF_CHILDREN_IN_ROOM3_ANOTHER;
	}else if($i == "4"){
		return TEXT_OF_CHILDREN_IN_ROOM4_ANOTHER;
	}else if($i == "5"){
		return TEXT_OF_CHILDREN_IN_ROOM5_ANOTHER;
	}else if($i == "6"){
		return TEXT_OF_CHILDREN_IN_ROOM6_ANOTHER;
	}
}

function is_double_booked($orders_id, $products_id=''){
	$is_double_book_bool=false;
	$where_extra_products = "";
	if((int)$products_id > 0){
		$where_extra_products = " and op.products_id='".(int)$products_id."'";
	}
	//$order_prod_sql = "SELECT op.products_departure_date, op.products_model, op.orders_id, op.products_id, o.customers_id, ope.guest_name FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o, ".TABLE_ORDERS_PRODUCTS_ETICKET." AS ope WHERE o.orders_id= op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND o.orders_status != 6 AND op.orders_id='".(int)$orders_id."' ".$where_extra_products." ";
	$order_prod_sql = "SELECT op.products_departure_date, op.products_model, op.orders_id, op.products_id, o.customers_id, ope.guest_name FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o, ".TABLE_ORDERS_PRODUCTS_ETICKET." AS ope WHERE o.orders_id= op.orders_id AND op.orders_products_id = ope.orders_products_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND o.orders_status != 6 AND op.orders_id='".(int)$orders_id."' ".$where_extra_products." ";
	$order_prod_query = tep_db_query($order_prod_sql);
	while($order_prod_row = tep_db_fetch_array($order_prod_query)){

		//$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_id FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o, ".TABLE_ORDERS_PRODUCTS_ETICKET." AS ope WHERE o.orders_id=op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND op.products_departure_date ='".$order_prod_row['products_departure_date']."' AND o.customers_id='".$order_prod_row['customers_id']."' AND ope.guest_name='".addslashes($order_prod_row['guest_name'])."' AND op.products_departure_date!='0000-00-00 00:00:00' AND op.products_model='".$order_prod_row['products_model']."' AND op.orders_id !='".(int)$orders_id."' AND o.orders_status != 6 AND op.orders_id > 0 ".$where_extra_products." ";
		$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_id FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o, ".TABLE_ORDERS_PRODUCTS_ETICKET." AS ope WHERE o.orders_id=op.orders_id And op.orders_products_id = ope.orders_products_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND op.products_departure_date ='".$order_prod_row['products_departure_date']."' AND o.customers_id='".$order_prod_row['customers_id']."' AND ope.guest_name='".addslashes($order_prod_row['guest_name'])."' AND op.products_departure_date!='0000-00-00 00:00:00' AND op.products_model='".$order_prod_row['products_model']."' AND op.orders_id !='".(int)$orders_id."' AND o.orders_status != 6 AND op.orders_id > 0 ".$where_extra_products." ";
		$check_double_book_query = tep_db_query($check_double_book_sql);
		if($check_double_book_row = tep_db_fetch_array($check_double_book_query)){
			$is_double_book_bool=true;
			$is_double_book_bool_array[1]='<a href="'.tep_href_link(FILENAME_ORDERS, 'cID='.(int)$check_double_book_row['customers_id']).'"><img src="'.DIR_WS_IMAGES.'dbl_book_txt.gif" border="0" /></a>';
		}
	}
	$is_double_book_bool_array[0] = $is_double_book_bool;
	return $is_double_book_bool_array;
}

//发送优惠券到期提醒邮件
function send_coupon_expire_notes(){
	global $currencies;
	if(!is_object($currencies)){
		require_once(DIR_WS_CLASSES . 'currencies.php');
		$currencies = new currencies();
	}
	$date_3day_after = date("Y-m-d 00:00:00", time()+(86400*3));
	$sql = tep_db_query('SELECT * FROM `coupons` WHERE coupon_expire_date ="'.$date_3day_after.'" and need_customers_active = "1" and coupon_active ="Y" ');
	while($rows = tep_db_fetch_array($sql)){
		if((int)$rows['restrict_to_customers']){
			//如果有人用了就不提醒用户了，不管这券能用几次
			$chek_used_sql = tep_db_query('SELECT coupon_id FROM `coupon_redeem_track` WHERE coupon_id="'.$rows['coupon_id'].'" Limit 1');
			$chek_used = tep_db_fetch_array($chek_used_sql);
			if(!(int)$chek_used['coupon_id']){
				if(substr($rows['coupon_amount'],-1)=="%"){
					$Yuan = $rows['coupon_amount'];
				}else{
					$Yuan = str_replace('$',preg_quote('$'),$currencies->format($rows['coupon_amount']));
				}
				$ExpireDate = substr($rows['coupon_expire_date'],0,10);

				$patterns = array();
				$patterns[0] = '{CustomerName}';
				$patterns[1] = '{images}';
				$patterns[2] = '{HTTP_SERVER}';
				$patterns[3] = '{ToDate}';
				$patterns[4] = '{Yuan}';
				$patterns[5] = '{EMAIL}';
				$patterns[6] = '{ExpireDate}';
				$patterns[7] = '{CONFORMATION_EMAIL_FOOTER}';

				$cus_sql = tep_db_query('SELECT customers_firstname, customers_lastname, customers_email_address, customers_char_set FROM `customers` WHERE customers_id="'.(int)$rows['restrict_to_customers'].'" ');
				$cus_row = tep_db_fetch_array($cus_sql);

				$to_name = db_to_html(tep_db_output($cus_row['customers_firstname']))." ";
				if(!tep_not_null($cus_row['customers_firstname'])){
					$to_name = db_to_html(tep_db_output($cus_row['customers_lastname']))." ";
				}
				$to_email_address = $cus_row['customers_email_address'];
				$from_email_address = "automail@usitrip.com";
				$from_email_name = db_to_html("走四方网 ");
				$email_subject = db_to_html("您在走四方网的优惠券即将到期！").$from_email_name;

				$replacements = array();
				$replacements[0] = $to_name;
				$replacements[1] = HTTP_SERVER.'/email_tpl/images';
				$replacements[2] = HTTP_SERVER;
				$replacements[3] = date("Y-m-d");
				$replacements[4] = $Yuan;
				$replacements[5] = $to_email_address;
				$replacements[6] = $ExpireDate;
				$replacements[7] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));

				$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
				$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/coupon_expire_notes.tpl.html');
				$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');

				$email_tpl = preg_replace('/'.preg_quote('<!--','/').'(.+)'.preg_quote('-->','/').'/','',$email_tpl);
				$email_tpl = preg_replace('/[[:space:]]+/',' ',$email_tpl);
				$email_text = preg_replace( $patterns ,$replacements, db_to_html($email_tpl));

				$email_charset = "gb2312";
				if(tep_not_null($cus_row['customers_char_set'])){
					$email_charset = $cus_row['customers_char_set'];
				}
				if(tep_not_null($to_email_address)){
					tep_mail(iconv(CHARSET,$email_charset.'//IGNORE',$to_name), $to_email_address, iconv(CHARSET,$email_charset.'//IGNORE',$email_subject), iconv(CHARSET,$email_charset.'//IGNORE',$email_text), iconv(CHARSET,$email_charset.'//IGNORE',$from_email_name), $from_email_address, 'true', $email_charset);
				}
			}
		}
	}
}

//取得并判断产品的子供应商
function get_provider_tour_code_sub($products_id){
	$sql = tep_db_query('SELECT provider_tour_code_sub FROM `products` WHERE products_id ="'.(int)$products_id.'" ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['provider_tour_code_sub'])){
		$array = explode(';',$row['provider_tour_code_sub']);
		return $array;
	}
	return false;
}

function get_double_booked_canceled_reference_orders_ids($orders_id, $products_id=''){
	$where_extra_products = "";
	if((int)$products_id > 0){
		$where_extra_products = " and op.products_id='".(int)$products_id."'";
	}
	$order_prod_sql = "SELECT op.products_departure_date, op.products_model, op.orders_id, op.products_id, o.customers_id, ope.guest_name FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o, ".TABLE_ORDERS_PRODUCTS_ETICKET." AS ope WHERE o.orders_id= op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND op.orders_id='".(int)$orders_id."' ".$where_extra_products." ";
	$order_prod_query = tep_db_query($order_prod_sql);
	$li=0;
	while($order_prod_row = tep_db_fetch_array($order_prod_query)){

		$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_id, o.orders_status, o.payment_method FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_ORDERS." AS o, ".TABLE_ORDERS_PRODUCTS_ETICKET." AS ope WHERE o.orders_id=op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND op.products_departure_date ='".$order_prod_row['products_departure_date']."' AND o.customers_id='".$order_prod_row['customers_id']."' AND ope.guest_name='".addslashes($order_prod_row['guest_name'])."' AND op.products_departure_date!='0000-00-00 00:00:00' AND op.products_model='".$order_prod_row['products_model']."' AND op.orders_id > 0 ".$where_extra_products." ";
		$check_double_book_query = tep_db_query($check_double_book_sql);
		while($check_double_book_row = tep_db_fetch_array($check_double_book_query)){
			$get_double_book_list_array[$li]['orders_id'] = $check_double_book_row['orders_id'];
			$get_double_book_list_array[$li]['orders_status'] = $check_double_book_row['orders_status'];
			$get_double_book_list_array[$li]['payment_method'] = $check_double_book_row['payment_method'];
			$li++;
		}
	}

	return $get_double_book_list_array;
}

function tep_get_settlement_heading($orders_id){
	$show_heading=false;
	$sql_settlement_payment_get = "select min(op.products_departure_date) AS opdatemin, osi.reference_comments, osi.orders_payment_method, osi.settlement_date, o.payment_method, o.orders_id, o.orders_status, o.date_purchased, osh.date_added, osi.updated_by, ot.text, ot.value, osi.order_value, osbi.orders_settlement_batch_no, osbi.orders_settlement_batch_total, (select sum(order_value) from orders_settlement_information where orders_id = o.orders_id) as total_settlement_amount from
".TABLE_ORDERS." as o 
left join ".TABLE_ORDERS_SETTLEMENT_INFORMATION." as osi on osi.orders_id = o.orders_id   
left join ".TABLE_ORDERS_SETTLEMENT_BATCH_INFO." as osbi on osi.settlement_date_short = osbi.orders_settlement_date_short
LEFT JOIN ".TABLE_ORDERS_SETTLEMENT_INFORMATION." osi2 ON osi.orders_id = o.orders_id and osi.orders_id = osi2.orders_id
AND osi.orders_settlement_id < osi2.orders_settlement_id
AND osi.date_added < osi2.date_added,
".TABLE_ORDERS_STATUS_HISTORY." osh LEFT JOIN ".TABLE_ORDERS_STATUS_HISTORY." osh2 ON osh.orders_id = osh2.orders_id
AND osh.orders_status_history_id < osh2.orders_status_history_id
AND osh.date_added < osh2.date_added, ".TABLE_ORDERS_TOTAL." ot, " . TABLE_ORDERS_PRODUCTS . " as op where osh2.date_added IS NULL and osi2.date_added IS NULL and o.orders_status = osh.orders_status_id and o.orders_id = osh.orders_id and o.orders_id=ot.orders_id and ot.class='ot_total' and ot.value > 0 and o.orders_id = op.orders_id and o.orders_id='".(int)$orders_id."' group by o.orders_id having (osbi.orders_settlement_batch_no is NULL and o.orders_status ='100006' and osi.orders_payment_method like '%Credit Card%') or (ot.value != total_settlement_amount ) ";
	$run_settlement_payment_get = tep_db_query($sql_settlement_payment_get);
	$settle_amt = tep_db_fetch_array($run_settlement_payment_get);
	if(number_format($settle_amt['total_settlement_amount'],2,'.','') != number_format($settle_amt['value'], 2, '.', '')  && $settle_amt['value'] != 0){
		$show_heading=true;
	} else {
		$show_heading=false;
	}
	return $show_heading;
}

function get_latest_orders_status_from_order_id($orders_id){
	$get_latest_orders_status_sql = "select orders_status from ".TABLE_ORDERS." where orders_id = '".$orders_id."'";
	$get_latest_orders_status_query = tep_db_query($get_latest_orders_status_sql);
	$get_latest_orders_status_row = tep_db_fetch_array($get_latest_orders_status_query);
	return $get_latest_orders_status_row['orders_status'];
}
function get_older_values_for_update($order_products_id){
	$sql_get_old_data="SELECT o.orders_id,op.products_id,op.products_departure_date,op.total_room_adult_child_info,opt.guest_name,o.date_purchased from ".TABLE_ORDERS." as o ,".TABLE_ORDERS_PRODUCTS." as op LEFT JOIN ".TABLE_ORDERS_PRODUCTS_ETICKET." opt ON opt.orders_id=op.orders_id and op.products_id=opt.products_id WHERE o.orders_id=op.orders_id and op.orders_products_id=".(int)$order_products_id." group by op.orders_products_id";
	$run_sql_get_data = tep_db_query($sql_get_old_data);
	$row_data=tep_db_fetch_array($run_sql_get_data);
	//	while($row_data=tep_db_fetch_array($run_sql_get_data)){
	$return_old_value_array=array(
	'depature_date_old'=>$row_data['products_departure_date'],
	'total_room_adult_child_info_old'=>$row_data['total_room_adult_child_info'],
	'guest_name_old'=>$row_data['guest_name'],
	'date_purchased'=>$row_data['date_purchased'],
	);

	//}
	return $return_old_value_array;
}

/**
 * 创建更新客户信息的历史记录
 * @param int $order_products_id 产品快照id
 * @param string $depature_date 出发日期
 * @param string $total_room_info 房间人数信息
 * @param string $guest_name 顾客名字信息
 * @param string $time 更新时间
 */
function create_update_history_orders($order_products_id,$depature_date,$total_room_info,$guest_name,$time=''){
	$take_time='';
	if($time==''){
		$take_time='now()';
		$mody_login = $_SESSION['login_id'];
	}else{
		$take_time=$time;
		$mody_login = CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID;
	}
	$has_confirmed = '0';
	$confirmed_admin_id = '0';
	if($mody_login == CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID){
		$has_confirmed = '1';
		$confirmed_admin_id	= CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID;
	}
	$create_history_here=array(
	'op_order_products_ids'=>$order_products_id,
	'op_history_departure_date_loc'=>$depature_date,
	'op_history_guest_name'=>$guest_name,
	'op_history_lodging'=>$total_room_info,
	'op_history_modify_by_id'=>$mody_login,
	'op_modify_date'=>$take_time,
	'has_confirmed'=>$has_confirmed,
	'confirmed_admin_id'=>$confirmed_admin_id
	);
	tep_db_perform(ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY,$create_history_here);
	
	//客户信息更新后提醒主管OP等
	$orders_id = tep_db_get_field_value('orders_id', 'orders_products', 'orders_products_id="'.(int)$order_products_id.'" ');
	tep_set_order_up_no_change_price($orders_id, '1', '客户信息更新 '.$guest_name.' '.$total_room_info);	
}

/**
 * 显示客人信息和发出日期上车地址等信息的更新历史(后台)
 *
 * @param unknown_type $orders_products_id
 * @return unknown
 */
function show_histories_action($orders_products_id, $i, $can_confirm_order_products_departure_histoty){
	$sql_select_history="select *  from ".ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY." where op_order_products_ids = '".$orders_products_id."' order by op_histoty_id DESC";
	$get_histories = tep_db_query($sql_select_history);
	$ret_show_history = '';
	$inner_table='';
	$inner_table_lodging='';
	$k=0;
	$ret_show_history .= '<span><a class="thumbnail col_a1_popup" href="javascript:void(0);"><nobr><u>客人信息更新历史记录</u><h1 style="color:#FF0000" id="crssgxjl_'.$orders_products_id.'"></h1></nobr><span style="width:850px;text-align:left;">
				<table id="guestInfoUpdatedHistories_'.$orders_products_id.'" class="dataTableContent" border="0" width="100%">
					<tr>
						<td valign="top"><b>'.TXT_SHOW_HISTORY_SR_NO.'</b></td>
						<td valign="top"><b>'.TXT_SHOW_HISTORY_GUEST_NAME.'</b></td>
						<td valign="top"><b>'.TXT_SHOW_HISTORY_LODGING.'</b></td>
						<td valign="top"><b>'.TXT_SHOW_HISTORY_DEPARTURE_DATE.'</b></td>
						<td valign="top"><b>'.TXT_SHOW_HISTORY_UPDATED_BY.'</b></td>
						<td valign="top"><b>'.TXT_SHOW_HISTORY_MODIFYDATE.'</b></td>
					</tr>';
	while($row_updated_history = tep_db_fetch_array($get_histories)){
		$k++;
		### GUEST NAME START HERE
		$guestnames = explode('<::>',$row_updated_history['op_history_guest_name']);
		$inner_table='<table class="dataTableContent" border="0" cellspacing="0" cellpadding="0">';
		for($i=0;$i<sizeof($guestnames);$i++){
			$cust_name_bdate_arr=explode('||',$guestnames[$i]);
			if($cust_name_bdate_arr[0]!=''){
				$inner_table .=  '<tr><td nowrap="nowrap">'.stripslashes($cust_name_bdate_arr[0]).'&nbsp;&nbsp;'.$cust_name_bdate_arr[1];
				if($cust_name_bdate_arr[1]!=''){
					$inner_table.='(Birth Date)';
				}
				$inner_table .=  '</td></tr>';
			}
		}
		$inner_table.='</table>';
		### GUEST NAME END HERE
		### LODING START HERE
		$room_total_adults = 0;
		$room_total_children = 0;
		$total_room=get_total_room_from_str($row_updated_history['op_history_lodging']);
		if($total_room>0){
			$inner_table_lodging='<table width="100%" cellspacing="0" cellpadding="0" border="0" class="dataTableContent">';
			$inner_table_lodging.='<tr><td class="p_l1 tab_t_bg">'.TXT_SHOW_HISTORY_ROOMS.'</td><td class="tab_t_bg ">'.TXT_SHOW_HISTORY_ADULT.'</td><td class="tab_t_bg">'.TXT_SHOW_HISTORY_CHILD.'</td><td>&nbsp;</td><tr>';
			for($t=1;$t<=$total_room;$t++){
				$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_updated_history['op_history_lodging'],$t);
				$room_total_adults = $room_total_adults + $chaild_adult_no_arr[0];
				$room_total_children = $room_total_children + $chaild_adult_no_arr[1];
				$inner_table_lodging.='<tr><td class="p_l1 order_default">'.$t.'</td><td class="order_default">'.$chaild_adult_no_arr[0].'</td><td class="order_default">'.$chaild_adult_no_arr[1].'</td><td>&nbsp;</td></tr>';
			}
			$inner_table_lodging.='</table>';
		}else{
			$inner_table_lodging='<table width="100%" cellspacing="0" cellpadding="0" border="0">
		 <tr><td class="tab_t_bg ">'.TXT_SHOW_HISTORY_ADULT.'</td><td class="tab_t_bg ">'.TXT_SHOW_HISTORY_CHILD.'</td></tr>';
			$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_updated_history['op_history_lodging'],1);
			$total_adults = $chaild_adult_no_arr[0];
			$total_children = $chaild_adult_no_arr[1];

			$room_total_adults = $room_total_adults + $total_adults;
			$room_total_children = $room_total_children + $total_children;
			$inner_table_lodging.='<tr><td class="order_default">'.$total_adults.'</td><td class="order_default">'.$total_children.'</td></tr>';
			$inner_table_lodging.='</table>';

		}
		### LODING END HERE
		$_confirm_msn = '';
		if($k==1 && $can_confirm_order_products_departure_histoty === true){
			$_confirm_msn = '<button type="button" style="padding:6px;" onclick="confirm_histories_action('.(int)$row_updated_history['op_histoty_id'].', this, '.$i.')">审核</button>';
			$_confirm_msn.= '<script>jQuery("#crssgxjl_'.$orders_products_id.'").text("主管OP注意"); jQuery(document).ready(function(){ jQuery("input[name=\'btnProvidersConfirmation_'.$orders_products_id.'\']").attr("disabled",true).attr("title","主管OP必须审核新客户信息后才能发地接！");}); </script>';

		}
		if($row_updated_history['has_confirmed']=='1'){ $_confirm_msn = '[已由'.tep_get_admin_customer_name($row_updated_history['confirmed_admin_id']).'审核]'; }
		$ret_show_history .= '<tr>
								<td valign="top"><b>'.$k.'</b>&nbsp;</td>
								<td valign="top" width="23%">'.$inner_table.'&nbsp;</td>
								<td valign="top" width="20%">'.$inner_table_lodging.'&nbsp;</td>
								<td valign="top" width="15%">'.tep_date_short_with_day($row_updated_history['op_history_departure_date_loc'],DATE_FORMAT_WITH_DAY_AT_END).'&nbsp;</td>
								<td valign="top" width="13%">'.tep_get_admin_customer_name($row_updated_history['op_history_modify_by_id']).'&nbsp;</td>
								<td valign="top">'.date('Y-m-d H:i:s',strtotime($row_updated_history['op_modify_date'])).'&nbsp;'.$_confirm_msn.'</td>
							</tr>';
	}
	$ret_show_history .= '</table></span></a></span>';
	return $ret_show_history;
}

function tep_get_last_purchase_date_of_tour_show_here($products_id){
	$product_query_raw_pop="select o.date_purchased FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id ".$where." and op.products_id='".(int)$products_id."' order by o.date_purchased DESC limit 1";
	$run_sql_last_date=tep_db_query($product_query_raw_pop);
	$row_date_last=tep_db_fetch_array($run_sql_last_date);
	return $row_date_last['date_purchased'];

}

function tep_auto_cancelled_zero_cost_price($orders_id,$provider_cancellation_fee=0,$o_prod_id=0){

	$extra_where = '';
	if($o_prod_id > 0){
		$extra_where = ' and op.orders_products_id='.$o_prod_id;
	}
	$orders_products_query = tep_db_query("select op.products_id, op.orders_products_id, op.products_model, op.products_name , op.final_price, op.final_price_cost, op.customer_invoice_no, op.customer_invoice_total from ".TABLE_ORDERS_PRODUCTS." op where op.orders_id = '". (int)$orders_id ."' and  op.final_price_cost > 0 ".$extra_where." order by op.orders_products_id asc");
	while ($orders_products_detail = tep_db_fetch_array($orders_products_query)) {

		$cost_adjust_value = $orders_products_detail['final_price_cost'] - $provider_cancellation_fee;
		//update pricing history table start
		$sql_data_array_original_insert = array(
		'orders_products_id' => $orders_products_detail['orders_products_id'],
		'products_model' => $orders_products_detail['products_model'],
		'products_name' => tep_db_input($orders_products_detail['products_name']),
		'retail' => 0,
		'cost' => ($cost_adjust_value * -1),
		'invoice_number' => $orders_products_detail["customer_invoice_no"],
		'invoice_amount' => $orders_products_detail["customer_invoice_total"],
		'comment' => 'Auto Updated due to Provider Cancellation Fee!!###!!',
		'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
		'last_updated_date' => 'now()'
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
		//update pricing history table end

		//update order total and order cost to 0 start
		tep_db_query("update ".TABLE_ORDERS." set order_cost = order_cost - $cost_adjust_value where orders_id='".(int)$orders_id."'");
		/*
		$sql_data_array = array('order_cost' => '0');
		tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id='".(int)$orders_id."'");
		*/
		//update order total and order cost to 0 end

		//canclled reward points of order start
		if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
			$customer_query = tep_db_query("select unique_id, customer_id, points_pending, points_status from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_type = 'SP' and points_status != 3 and points_status != 4 and orders_id = '" . (int)$orders_id  . "' limit 1");
			$customer_points = tep_db_fetch_array($customer_query);
			if (tep_db_num_rows($customer_query)) {
				$set_comment = ", points_comment = 'Order Cancelled'";
				tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 3 " . $set_comment . " where orders_id = '" . (int)$orders_id  . "' and unique_id = '".$customer_points['unique_id']."'");
				tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
			}
		}
		//canclled reward points of order end

		//update order product table start
		$sql_data_array = array(
		'final_price_cost' => $provider_cancellation_fee
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array, 'update', "orders_products_id='".$orders_products_detail['orders_products_id']."'");
		//update order product table end

		//update order product attribute start
		$sql_data_array = array(
		'options_values_price_cost' => '0'
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', " orders_id='".(int)$orders_id."' and orders_products_id='".$orders_products_detail['orders_products_id']."'");
		//update order product attribute end

	}
	return true;
}

function tep_get_is_apply_price_per_order_option_value($value_id){
	global $languages_id;
	$values = tep_db_query("select is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "' and language_id = '" . $languages_id . "'");
	$values_values = tep_db_fetch_array($values);

	return (int)$values_values['is_per_order_option'];
}

function tep_get_address_format_id($country_id) {
	$address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
	if (tep_db_num_rows($address_format_query)) {
		$address_format = tep_db_fetch_array($address_format_query);
		return $address_format['format_id'];
	} else {
		return '1';
	}
}

function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
	//global $payment;
	$address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
	$address = tep_db_fetch_array($address_query);

	/*
	if($payment == 'authorizenet' || $payment == 'cc_cvc'){
	$address['firstname'] = ''; //$order->info['cc_owner']
	$address['lastname'] = '';
	}
	*/
	//amit fixed to don't show name on billing
	$address['firstname'] = '';
	$address['lastname'] = '';
	//amit added to fixed don't show name on billing


	$format_id = tep_get_address_format_id($address['country_id']);

	$total_address_formated_str = trim(tep_address_format($format_id, $address, $html, $boln, $eoln));


	if(substr($total_address_formated_str,0,6) == '<br />'){
		$total_address_formated_str = substr($total_address_formated_str,6,strlen($total_address_formated_str));
	}

	return $total_address_formated_str;
}
function get_roominfo_formatted_string($new_roominfo_string, $old_roominfo_string){
	$total_rooms = get_total_room_from_str($new_roominfo_string);
	$return_str = '';
	$return_str .= '<table width="250" cellspacing="0" cellpadding="0" border="0">';
	if($total_rooms > 0){
		$return_str .= '<tr><td class="p_l1 tab_t_bg ">'.TXT_ROOMS.'</td><td class="tab_t_bg ">'.TXT_ADULTS.'</td><td class="tab_t_bg ">'.TXT_CHILDREN.'</td><td class="tab_t_bg ">'.TXT_PRICE.'</td></tr>';
		for($t=1;$t<=$total_rooms;$t++){
			$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($new_roominfo_string,$t);
			$room_total_price = tep_get_rooms_adults_childern($old_roominfo_string,$t,'price');

			$return_str .= '<tr><td class="p_l1 order_default"><span>'.$t.'</span></td><td class="order_default">'.$chaild_adult_no_arr[0].'</td><td class="order_default">'.$chaild_adult_no_arr[1].'</td><td class="order_default">'.$room_total_price.'</td></tr>';
		}
	}else{
		$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($new_roominfo_string, 1);
		$total_adults = $chaild_adult_no_arr[0];
		$total_children = $chaild_adult_no_arr[1];
		$total_price = tep_get_no_adults_childern($old_roominfo_string,'price');
		$return_str .= '<tr><td class="tab_t_bg ">'.TXT_ADULTS.'</td><td class="tab_t_bg ">'.TXT_CHILDREN.'</td><td class="tab_t_bg ">'.TXT_PRICE.'</td></tr><tr><td class="order_default">'.$total_adults.'</td><td class="order_default">'.$total_children.'</td><td class="order_default">'.$total_price.'</td></tr>';
	}

	if(strstr(strip_tags($old_roominfo_string), TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT)){
		$return_str .= '<tr><td colspan="4">'.TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT.'</td></tr>';
	}
	/*if(strstr(strip_tags($old_roominfo_string), TXT_FEATURED_DEAL_DISCOUNT)){
	$return_str .= '<tr><td colspan="4">'.TXT_FEATURED_DEAL_DISCOUNT.'</td></tr>';
	}*/
	$return_str .= '</table>';
	return $return_str;
}


function authorized_response_proccess(){
	global $response , $insert_id, $order, $i_need_pay, $o_t_c_ids, $error_back_page, $error_back_page_get_parameters;
	global $x_Amount,$authorizenet_failed_cntr, $response_auth_trans_id;
	$paypal_usd = 0;
	if($x_Amount>0){
		$paypal_usd = $x_Amount;
	}else{
		//die('Plx Check authorizenet.php, $x_Amount value null. ');
	}

	// Change made by using ADC Direct Connection
	$response_vars = explode(',', $response[0]);
	$x_response_code = $response_vars[0];
	$x_response_subcode = $response[1];
	$x_response_reason_code = $response[2];
	$x_response_reason_text = $response[3];
	if ($x_response_code != '1') {
		//tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '".(int)$insert_id."' "); //Remove order
		//update orders status for current insert order
		$sql_data_array_ord_status = array('orders_status' => '100060'); //create cc faild
		tep_db_perform(TABLE_ORDERS, $sql_data_array_ord_status, 'update', "orders_id = '".(int)$insert_id."'");

		$cc_failed_reason_tracking = "Reason Code: ".$x_response_reason_code.chr(13).chr(10)."Reason: ".$x_response_reason_text.chr(13).chr(10).chr(10);

		if(trim($order->info['comments']) != ''){
			$cc_failed_reason_tracking .= "Customer Comment: ".$order->info['comments'];
		}

		$sql_data_array = array('orders_id' => $insert_id,
		'orders_status_id' => '100060',
		'date_added' => 'now()',
		'customer_notified' => '0',
		'comments' => $cc_failed_reason_tracking);
		tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		//amit added to fixed recover pending status when cc failed for TC order start
		if($i_need_pay>0 && tep_not_null($o_t_c_ids) && basename($_SERVER['PHP_SELF']) == 'checkout_process_travel_companion.php'){
			$sql_date_array = array(
			'orders_id' => (int)$insert_id,
			'orders_status_id' => '1',
			'date_added' => 'now()',
			'customer_notified' => '1',
			'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
			'comments' => 'Travel Companion Order CC Auth Failed. Recover Order Status to Pending'
			);
			tep_db_perform('orders_status_history', $sql_date_array);
			$sql_data_array = array('orders_status' => '1');
			tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id = '" . (int)$insert_id . "'");
		}
		//amit added to fixed recover pending status when cc failed for TC order end

	}else{ // success code
		//update orders table to cc success
		//amit added to take avs info in log start
		$autho_address_verification_status['A'] = "Address (Street) matches, ZIP does not";
		$autho_address_verification_status['B'] = "Address information not provided for AVS check";
		$autho_address_verification_status['E'] = "AVS error";
		$autho_address_verification_status['G']= "Non-U.S. Card Issuing Bank";
		$autho_address_verification_status['N'] = "No Match on Address (Street) or ZIP";
		$autho_address_verification_status['P'] = "AVS not applicable for this transaction";
		$autho_address_verification_status['R'] = "Retry System unavailable or timed out";
		$autho_address_verification_status['S']= "Service not supported by issuer";
		$autho_address_verification_status['U'] = "Address information is unavailable";
		$autho_address_verification_status['W'] = "Nine digit ZIP matches, Address (Street) does not";
		$autho_address_verification_status['X'] = "Address (Street) and nine digit ZIP match";
		$autho_address_verification_status['Y'] = "Address (Street) and five digit ZIP match";
		$autho_address_verification_status['Z'] = "Five digit ZIP matches, Address (Street) does not";


		$autho_avs_card_code_status['M'] = "Match";
		$autho_avs_card_code_status['N'] = "No Match";
		$autho_avs_card_code_status['P'] = "Not Processed";
		$autho_avs_card_code_status['S'] = "Should have been present";
		$autho_avs_card_code_status['U'] = "Issuer unable to process request";
		//amit added to take avs info in log end


		$cc_type = (tep_not_null($_POST['cc_type'])) ? $_POST['cc_type'] :$order->info['cc_type'];
		$cc_number = (tep_not_null($_POST['cc_number'])) ? $_POST['cc_number'] :$order->info['cc_number'];
		$cc_expires = (tep_not_null($_POST['cc_expires'])) ? $_POST['cc_expires'] :$order->info['cc_expires'];
		$cc_owner = (tep_not_null($_POST['cc_owner'])) ? $_POST['cc_owner'] :$order->info['cc_owner'];
		//amit added for auto charged start
		$response_auth_trans_id = $response[6];
		if (!tep_session_is_registered('response_auth_trans_id')) {
			tep_session_register('response_auth_trans_id');
		}
		//amit added for auto charged end
		$avs_authorized_db_insert_note = "Address Verification Status: ".$autho_address_verification_status[''.$response[5].'']."
													Card Code Status: ".$autho_avs_card_code_status[''.$response[38].'']."
													Card Type: ".$cc_type."
													Card Number: xxxx".substr($cc_number,-4)."
													Expiration Date: ".$cc_expires."
													Total Amount: USD ".$paypal_usd."
													Transaction ID: ".$response_auth_trans_id."
													Name: ".$cc_owner."";	
		if($i_need_pay>0 && tep_not_null($o_t_c_ids)){
			$sql = tep_db_query('SELECT guest_name FROM '.TABLE_ORDERS_TRAVEL_COMPANION.' WHERE orders_id="'.(int)$insert_id.'" AND orders_travel_companion_id in('.$o_t_c_ids.') ');
			$r_guest_name = '';
			while($rows = tep_db_fetch_array($sql)){
				$r_guest_name .= ','.$rows['guest_name'];
			}
			$r_guest_name = substr($r_guest_name,1);
			$avs_authorized_db_insert_note.= "\n".'Travel Companion Payment: '.$r_guest_name."\nPlease pay attention to the credit card transaction amount!";
		}
		$sql_data_array = array('orders_id' => $insert_id,
		'orders_status_id' => '100062',
		'date_added' => 'now()',
		'customer_notified' => '0',
		'comments' => tep_db_input($avs_authorized_db_insert_note)
		);
		tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		//update orders table to cc success

		return true;

	}
}
/*function get_redemption_awards($customer_shopping_points_spending) {
global $order;

if (USE_POINTS_FOR_REDEEMED == 'false') {
if (!$customer_shopping_points_spending) {
return true;
}
return false;
} else {
return true;
}
}
*/
// sets the customers Pending points
/*function tep_add_pending_points($customer_id, $insert_id, $points_toadd, $points_comment, $points_type, $feedback_url='', $products_id='') {

$points_awarded = ($points_type != 'SP') ? $points_toadd : $points_toadd * POINTS_PER_AMOUNT_PURCHASE;

if (POINTS_AUTO_ON == '0' || $points_comment=='TEXT_DEFAULT_REFERRAL') {

$sql_data_array = array('customer_id' => (int)$customer_id,
'orders_id' => (int)$insert_id,
'products_id' => (int)$products_id,
'points_pending' => $points_awarded,
'date_added' => 'now()',
'points_comment' => $points_comment,
'points_type' => $points_type,
'points_status' => 2,
'feedback_other_site_url' => $feedback_url);

tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);

$sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";

} else {
$sql_data_array = array('customer_id' => (int)$customer_id,
'orders_id' => (int)$insert_id,
'products_id' => (int)$products_id,
'points_pending' => $points_awarded,
'date_added' => 'now()',
'points_comment' => $points_comment,
'points_type' => $points_type,
'points_status' => 1,
'feedback_other_site_url' => $feedback_url);
tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);

$sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";
}
tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
}
*/
/**
 * 自动取消15天后还未付款的订单。
 * 未付款订单15天自动撤销，此功能仅对订单状态是pending时有用(同时未付过款)，需要以订单状态为准，不能以付款状态为准，凡销售更改了订单状态，如：催促付款/中国银行转账 等，订单虽未付款，但是不会自动撤销。
 * @param $daysNum 指定的过期天数
 * @author Howard
 */
function auto_cancelled_orders_for_days($daysNum = 15){
	if(!(int)$daysNum){
		return false;
	}
	//如果不是生产站则马上停止，以免在测试站发到真实的客人那里去了
	if(IS_LIVE_SITES == false || !defined('IS_LIVE_SITES')){
		return false;
	}

	$that_date = date('Y-m-d',time()-$daysNum*24*3600);
	//$sql_str = 'SELECT customers_id,orders_id,customers_name,customers_email_address,date_purchased FROM `orders` where orders_paid < 1 AND orders_status!="6" AND date_purchased < "'.$that_date.' 00:00:00" ';
	$sql_str = 'SELECT customers_id,orders_id,customers_name,customers_email_address,date_purchased FROM `orders` where orders_paid < 1 AND orders_status in(1,100137) AND date_purchased < "'.$that_date.' 00:00:00" ';
	$sql = tep_db_query($sql_str);
	$orders_status_value = '6';

	$mail_subject_str = '走四方(usitrip.com)订单已撤销（订单号%s）';
	$customer_notified ='尊敬的{客户名称}先生/女士：'."\n";
	$customer_notified.='您在走四方网站{购买日期}提交的订单：{订单ID}，在'.$daysNum.'天内尚未收到任何支付款项，此订单已过有效期，特此通知。'."\n";
	$customer_notified.='（温馨提示：在您成功支付、收到旅游电子参团凭证前，您的座位未被保留）'."\n";
	$customer_notified.='订单详情：<a target="_blank" href="{订单详细页}">{订单ID}</a>（您可以使用预订时注册的Email登录您的账户来查询订单详情）'."\n";
	$customer_notified.='如果您已经成功付款，请直接与我们客服人员联系进行核实，及时帮您处理。'."\n";
	$customer_notified.='如果您还需要继续预订此行程或者更换其他行程，请在<a target="_blank" href="http://www.usitrip.com">www.usitrip.com</a>重新选购，感谢您对走四方的信任和支持！'."\n\n";

	while ($rows = tep_db_fetch_array($sql)){
		$date_time = date('Y-m-d H:i:s');
		$customers_name = $rows['customers_name'];
		$customers_email_address = $rows['customers_email_address'];
		if(!tep_not_null($customers_name) || !tep_not_null($customers_email_address)){	//如果用户姓名和邮箱只要有一个为空都重新到客户表重新取得数据
			$customer_original_info = tep_get_customers_info($rows['customers_id']);
			$customers_name = $customer_original_info["customers_firstname"];
			$customers_email_address = $customer_original_info["customers_email_address"];
		}
		$replace = array('{客户名称}'=>$customers_name, '{购买日期}'=>date('Y年m月d日',strtotime($rows['date_purchased'])),'{订单ID}'=>$rows['orders_id'],'{订单详细页}'=>tep_catalog_href_link('account_history_info.php','order_id='.$rows['orders_id']));
		$_customer_notified = strtr($customer_notified, $replace);
		//修改订单状态
		tep_db_query("update `orders` SET orders_status ='{$orders_status_value}', last_modified='{$date_time}', next_admin_id=0, need_next_admin=0, need_next_urgency='' where orders_id ='{$rows['orders_id']}'");
		tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
					(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)
					values ('" .$rows['orders_id']. "', '" .$orders_status_value. "', '".$date_time."', '1','" . tep_db_input(tep_db_prepare_input($_customer_notified)) . "','".CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID."')");
		//退还客户的预订积分
		auto_changed_points_info_for_orders_status_6_100005_100130_100134($rows['orders_id'], $orders_status_value);
		//发邮件
		$user_lang_code = customers_language_code($to_email_address);
		$email_subject = iconv('gb2312', $user_lang_code . '//IGNORE', sprintf($mail_subject_str,$rows['orders_id']))." ";
		$to_name = iconv('gb2312', $user_lang_code . '//IGNORE', $customers_name);
		$to_email_address = $customers_email_address;
		$email_text = $_customer_notified;
		$email_text.= CONFORMATION_EMAIL_FOOTER;
		$email_text = iconv('gb2312', $user_lang_code . '//IGNORE', $email_text);
		$from_email_name = STORE_OWNER;
		$from_email_name = iconv('gb2312', $user_lang_code . '//IGNORE', $from_email_name);
		$from_email_address = 'automail@usitrip.com';
		if(tep_not_null($to_email_address)){
			tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address,'true', $user_lang_code);
		}
		//echo $rows['orders_id']; exit;
	}
}



/**
 * 订单更新为已收款状态时自动确认相关客户的积分
 * @param $oID
 * @author Howard
 */
function auto_changed_points_info_for_orders_status_100006($oID){
	if(!(int)$oID) return false;
	$customer_query = tep_db_query("select unique_id, customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " where (points_status = 1 or points_status = 3) and points_type = 'SP' and orders_id = '" . (int) $oID . "' limit 1");
	$customer_points = tep_db_fetch_array($customer_query);
	if (tep_db_num_rows($customer_query)) {
		tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 2, points_comment = 'TEXT_DEFAULT_COMMENT' where orders_id = '" . (int) $oID . "' and unique_id = '" . $customer_points['unique_id'] . "'");
		tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
		$sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";

		//如果客户是第一次下订单则将为其推荐人添加积分
		//给老客户推荐人积分> $300 => 1000 , <=$300 => 500 start
		$Old_Customer_Testimonials_Order_End_Time = '2010-12-31 23:59:59';
		$Todaytime = date('Y-m-d H:i:s');
		if ($Todaytime <= $Old_Customer_Testimonials_Order_End_Time) {
			$customer_order_sql = tep_db_query('SELECT count(*) total FROM `orders` WHERE customers_id="' . (int) $customer_points['customer_id'] . '" AND orders_status="100006" ');
			$customer_order_row = tep_db_fetch_array($customer_order_sql);
			if ($customer_order_row['total'] < 2) {
				$order_total_sql = tep_db_query('SELECT value FROM `orders_total` WHERE orders_id="' . (int) $oID . '" AND class="ot_total" ');
				$order_total_row = tep_db_fetch_array($order_total_sql);
				$old_customer_add_points = 500;
				if ($order_total_row['value'] > 300) {
					$old_customer_add_points = 1000;
				}
				$new_customer_email_address = tep_get_customers_email((int) $customer_points['customer_id']);
				$old_customer_sql = tep_db_query('SELECT old_customer_email_address FROM `old_customer_testimonials` WHERE new_customer_email_address="' . $new_customer_email_address . '" AND orders_id<1 ');
				$old_customer_row = tep_db_fetch_array($old_customer_sql);
				if (tep_not_null($old_customer_row['old_customer_email_address'])) {
					//给老客户加分
					$old_customer_id = form_email_get_customers_id($old_customer_row['old_customer_email_address']);
					$sql_data_array = array('customer_id' => (int) $old_customer_id,
					'points_pending' => $old_customer_add_points,
					'date_added' => 'now()',
					'points_comment' => 'TEXT_DEFAULT_REFERRAL',
					'points_type' => 'RF',
					'points_status' => 2,
					'orders_id' => (int) $oID);

					tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
					$unique_id = tep_db_insert_id();
					tep_db_query("update `old_customer_testimonials` set orders_id = '" . (int) $oID . "' where new_customer_email_address = '" . $new_customer_email_address . "' limit 1");
					tep_auto_fix_customers_points((int)$old_customer_id);	//自动校正用户积分
				}
			}
		}
		//给老客户推荐人积分> $300 => 1000 , <=$300 => 500 end
		// howard added send points mail to customers
		if (abs($customer_points['points_pending'])) {
			require_once(DIR_FS_CATALOG . 'includes/languages/schinese/my_points.php');
			send_point_to_customers((int) $customer_points['customer_id']);
		}
	}

	//团购推荐朋友的1000积分确认
	$points_query = tep_db_query("select unique_id, customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " where (points_status = 1 or points_status = 3) and points_type = 'RF' and orders_id = '" . (int) $oID . "' limit 1");
	while($points_points = tep_db_fetch_array($points_query)){
		tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status='2' where unique_id='".$points_points['unique_id']."' ");
		tep_auto_fix_customers_points((int)$points_points['customer_id']);	//自动校正用户积分
	}

}


/**
 * 订单更新为取消或冻结状态时自动退还相关客户的积分（并把该订单曾经使用的积分数清除）
 * @param $oID
 * @param $status
 * @author Howard
 */
function auto_changed_points_info_for_orders_status_6_100005_100130_100134($oID, $status){
	if(!(int)$oID || ($status!=6 && $status!=100005 && $status!=100130 && $status!=100134)) return false;
	//Howard added 把该订单总价中的积分数据清零start
	$_sql = 'UPDATE orders_total SET `value` ="0.00", `text`="-$0" WHERE orders_id = "'. (int) $oID .'" AND `class` ="ot_redemptions" ';
	tep_db_query($_sql);
	//echo $_sql; exit;
	//Howard added 把该订单总价中的积分数据清零end

	$customer_query = tep_db_query("select unique_id, customer_id, points_pending, points_status from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_type = 'SP' and points_status !='3' and points_status !='4' and orders_id = '" . (int) $oID . "' limit 1");
	$customer_points = tep_db_fetch_array($customer_query);
	if (tep_db_num_rows($customer_query)) {
		if ($status == 6) {
			$set_comment = ", points_comment = 'TEXT_ORDER_CANCELLED_COMMENT'";
		} else if ($status == 100005) {
			$set_comment = ", points_comment = 'TEXT_ORDER_REFUNDED_COMMENT'";
		} else if ($status == 100134) {
			$set_comment = ", points_comment = 'TEXT_ORDER_REFUNDED_COMMENT'";
		} else if ($status == 100130) {
			$set_comment = ", points_comment = 'TEXT_ORDER_CANCELLED_BUY_NEW_COMMENT'";
		}


		tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 3 " . $set_comment . " where orders_id = '" . (int) $oID . "' and unique_id = '" . $customer_points['unique_id'] . "'");
		tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
	}

	//auto confirm refunded used redeem points start
	$customer_query = tep_db_query("select unique_id, customer_id, points_pending, points_status from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_type = 'SP' and points_status = 4 and points_pending < 0 and orders_id = '" . (int) $oID . "' limit 1");
	$customer_points = tep_db_fetch_array($customer_query);
	if (tep_db_num_rows($customer_query)) {

		$set_comment = ", points_pending='0.00' ";
		tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 4 " . $set_comment . " where orders_id = '" . (int) $oID . "' and unique_id = '" . $customer_points['unique_id'] . "'");
		tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
	}
	//auto confirm refunded used redeem points end

}
/**
 * hotel-extension 酒店延住相关函数
 * 检查产品是否为酒店产品是酒店返回1不是返回0
 * @param int $product_id 产品ID
 * @return int 0,1 
 */
function tep_check_product_is_hotel($product_id){
	$product_id = intval($product_id);
	$check_hotel_query = tep_db_query("select is_hotel from ".TABLE_PRODUCTS." where products_id = '".$product_id."'");
	$check_hotel = tep_db_fetch_array($check_hotel_query);
	if($check_hotel['is_hotel'] == '1'){
		$check_is_hotel = 1;
	}else{
		$check_is_hotel = 0;
	}
	return $check_is_hotel;
}

/**
 *hotel-extension 酒店延住相关函数
 *不清楚英文站上该函数的功能
 */
function enleve_accent($chaine){
	return $chaine;
}

/**
 * 根据城市ID号串取得城市名称，返回array
 * @param $cities_id_string
 * @author Howard
 */
function tep_get_city_names($cities_id_string){
	$array = array();
	$ids = explode(',',$cities_id_string);
	for($i=0, $n=sizeof($ids); $i<$n; $i++){
		$sql = tep_db_query('SELECT city FROM '.TABLE_TOUR_CITY.' where city_id="'.(int)$ids[$i].'" ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['city'])){
			$array[]=$row['city'];
		}
	}
	return $array;
}

//
/**
 * 取得某产品至少要提前几天或几个小时购买，返回int
 * @param $products_id
 * @author Howard
 */
function get_products_advance($products_id){
	//howard added advance set start
	$advance = 2;	//默认可提前几天购买
	$advance_sql = tep_db_query("select agency_id, book_limit_days_number, with_air_transfer from ".TABLE_PRODUCTS." where products_id = ".(int)$products_id." limit 1 ");
	$advance_row = tep_db_fetch_array($advance_sql);
	if((int)$advance_row['book_limit_days_number']){
		$advance = (int)$advance_row['book_limit_days_number'];
	}elseif((int)$advance_row['agency_id']){

		$agency_sql = tep_db_query('select book_limit_days, book_limit_days_type, book_limit_days_air, book_limit_days_type_air from '.TABLE_TRAVEL_AGENCY.' where agency_id="'.(int)$advance_row['agency_id'].'" limit 1');
		$agency = tep_db_fetch_array($agency_sql);

		if($advance_row['with_air_transfer']=="1"){	//接机团
			if((int)$agency['book_limit_days_air']){
				$advance = (int)$agency['book_limit_days_air'];
				if($agency['book_limit_days_type_air']=="hours"){
					$time = time();
					$to_day = date("Y-m-d H:i:s", $time);
					$min_can_book_date = date('Y-m-d H:i:s',$time+(int)$agency['book_limit_days_air']*60*60);
					$final_min_can_book_date = date("Y-m-d", strtotime($min_can_book_date)+24*60*60-1);
					$advance = (int)(strtotime($final_min_can_book_date)+24*60*60 - $time)/(24*60*60);
					if(!(int)$advance){ $advance = 1; }
				}
			}
		}else{	//非接机团

			if((int)$agency['book_limit_days']){
				$advance = (int)$agency['book_limit_days'];
				if($agency['book_limit_days_type']=="hours"){
					$time = time();
					$to_day = date("Y-m-d H:i:s", $time);
					$min_can_book_date = date('Y-m-d H:i:s',$time+(int)$agency['book_limit_days']*60*60);
					$final_min_can_book_date = date("Y-m-d", strtotime($min_can_book_date)+24*60*60-1);
					$advance = (int)(strtotime($final_min_can_book_date)+24*60*60 - $time)/(24*60*60);
					if(!(int)$advance){ $advance = 1; }
				}
			}
		}

	}
	return (int)$advance;
	//howard added advance set end
}

/**
 * 判断行程是否需要出发日期
 * 检查 products_type 的值
 * 英文站是 14..中文站先留接口
 * @author vincent
 * @param int $products_id
 * @return boolean
 */
function  is_tour_start_date_required($products_id){
	if(!defined('CAT_ID_PASSES_CARDS'))  define('CAT_ID_PASSES_CARDS',14);
	$ret_is_start_date_required = true;
	$qry_date_req = 'select p.products_type from ' . TABLE_PRODUCTS . ' p WHERE p.products_id = "'.(int)$products_id.'"';
	$res_date_req = tep_db_query($qry_date_req);
	$row_date_req = tep_db_fetch_array($res_date_req);
	if(tep_not_null($row_date_req['products_type'])){
		if($row_date_req['products_type'] == CAT_ID_PASSES_CARDS){
			$ret_is_start_date_required = false;
		}
	}
	return $ret_is_start_date_required;
}

function tep_get_order_start_stop($order_id,$blinking=0){ //0 stop, 1 start
	$sql_data_array = array('is_blinking' => $blinking,'is_blinking_date'=>date('Y-m-d H:i:s' ,time()));
	tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id='".(int)$order_id."'");
	return true;
}
//E-ticket Log Start
function tep_get_eticket_log_content($oID, $opid, $updated_by, $is_provider = 0, $is_notify = 0){
	global $login_id, $languages_id;

	require_once(DIR_WS_CLASSES . 'order.php');
	$order = new order($oID);

	for($jj=0; $jj<count($order->products); $jj++){
		if($order->products[$jj]['orders_products_id'] == $opid){
			break;
		}
	}
	$_GET['i'] = $jj;
	ob_start();
	require_once("eticket_email.php");
	$email_order = ob_get_contents();
	ob_end_clean();

	$eticket_log_data_array = array('orders_products_id' => $opid,
	'orders_eticket_last_modified' => 'now()',
	'orders_eticket_content' => $email_order,
	'orders_eticket_is_customer_notified' => $is_notify,
	'orders_eticket_updated_by' => $updated_by,
	'orders_eticket_updator_type' => $is_provider
	);
	tep_db_perform(TABLE_ORDERS_ETICKET_LOG, $eticket_log_data_array);

	return true;
}
//E-ticket Log End

/**
 * //双人一房的优惠模块
//根据Y团类型优惠N元每人，返回值是N元
//数据库名称products_double_room_preferences
 */
function double_room_preferences($products_id, $departure_date=''){
	$products_id = tep_get_prid($products_id);
	if(!(int)$products_id){ return false; }
	if($departure_date==""){ $departure_date = date('Y-m-d');}
	if(tep_not_null($departure_date)){
		$departure_date = substr($departure_date,0,10);
		if(strlen($departure_date)!=10){
			echo "Date erroe";
		}
	}

	$sql_str = ('SELECT products_double_room_preferences_id,value,excluding_dates FROM `products_double_room_preferences` WHERE products_id="'.(int)$products_id.'" AND status="1" AND people_number="2" AND (products_departure_date_begin <= "'.$departure_date.' 00:00:00" || products_departure_date_begin="0000-00-00 00:00:00" || products_departure_date_begin="") AND (products_departure_date_end >="'.$departure_date.' 23:59:59" || products_departure_date_end="0000-00-00 00:00:00" || products_departure_date_end="" ) Order By value DESC limit 1 ');
	$sql = tep_db_query($sql_str);
	//echo $sql_str;
	$row = tep_db_fetch_array($sql);
	if((int)$row['products_double_room_preferences_id']){
		if(strstr($row['excluding_dates'],$departure_date)){
			return false;
		}
		return $row['value'];
	}
	return false;
}
function tep_get_product_hotel_detail_array($prod_id){
	$product_hotel_info_query = tep_db_query("select hotel_address, hotel_name, hotel_phone from hotel where products_id = '".$prod_id."'");
	$product_hotel_info = tep_db_fetch_array($product_hotel_info_query);
	return $product_hotel_info;
}

/**
 * 对$roomtotal 增加额外的 3% 费用 @_-
 * vincent
 * @param int $roomtotal
 * @param int $persiontage 百分比
 */
function tep_get_total_fares_includes_agency($roomtotal, $persiontage=0){
	$roomtotal = $roomtotal + ($roomtotal*($persiontage/100));
	return $roomtotal;
}

/**
 * 取得客户存在我们网站的信用余额
 * @author amit
 */
function tep_get_customer_credits_balance($customer_id){
	$get_customer_credits = tep_db_query("select customers_credit_issued_amt from ".TABLE_CUSTOMERS." where customers_id = '".(int)$customer_id."'");
	$customer_credits_balance = 0;
	if(tep_db_num_rows($get_customer_credits)>0){
		$row_customer_credits = tep_db_fetch_array($get_customer_credits);
		$customer_credits_balance = $row_customer_credits['customers_credit_issued_amt'];
	}
	return $customer_credits_balance;
}
function tep_check_priority_mail_is_active($prod_id){
	//$check_tour_agency_name_query = tep_db_query("select ta.agency_id, ta.is_priority_mail_note from " . TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id = '" .$prod_id. "'");
	//$check_tour_agency_name = tep_db_fetch_array($check_tour_agency_name_query);
	$priority_mail_is_active = 0;
	/*if($check_tour_agency_name['is_priority_mail_note'] == 1){
	$priority_mail_is_active = 1;
	}
	*/

	$products_attributes_query = tep_db_query("select products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . (int)$prod_id . "' and options_id = '".PRIORITY_MAIL_PRODUCTS_OPTIONS_ID."'");
	if(tep_db_num_rows($products_attributes_query) > 0){
		$priority_mail_is_active = 1;
	}
	return $priority_mail_is_active;
}
function tep_get_cart_add_update_extra_values($fieldname, $fieldvalue, $extra_values_str=''){
	if(tep_not_null($extra_values_str)){
		$extra_values_array = explode("|##!##|", $extra_values_str);
		$total_ext_values = sizeof($extra_values_array);
		$new_extra_values_str = '';
		$update_count=0;
		for($ext = 0; $ext < $total_ext_values; $ext++){
			$get_field_info = explode("|#|", $extra_values_array[$ext]);
			if($get_field_info[0] == $fieldname){
				$get_field_info[1] = $fieldvalue;
				$update_count++;
			}
			if(tep_not_null($get_field_info[0]) && tep_not_null($get_field_info[1])){
				$new_extra_values_str .= $get_field_info[0] . '|#|' . $get_field_info[1] . '|##!##|';
			}
		}
		if($update_count==0){
			if(tep_not_null($fieldname) && tep_not_null($fieldvalue)){
				$new_extra_values_str .= $fieldname . '|#|' . $fieldvalue . '|##!##|';
			}
		}
	}else{
		$new_extra_values_str = $fieldname . '|#|' . $fieldvalue . '|##!##|';
	}
	return $new_extra_values_str;
}
function tep_get_cart_get_extra_field_value($fieldname, $extra_values_str=''){
	$fieldvalue = '';
	if(tep_not_null($extra_values_str)){
		$extra_values_array = explode("|##!##|", $extra_values_str);
		$total_ext_values = sizeof($extra_values_array);
		$new_extra_values_str = '';
		for($ext = 0; $ext < $total_ext_values; $ext++){
			$get_field_info = explode("|#|", $extra_values_array[$ext]);
			if($get_field_info[0] == $fieldname){
				$fieldvalue = $get_field_info[1];
			}
		}
	}
	return $fieldvalue;
}


/**
 * print_r变量信息返回字符串
 * @param [$var ....]
 */
function print_vars2(){
	$output = '';
	$args = func_get_args();
	foreach($args as $arg){
		$type = gettype($arg);
		if($type == 'boolean')$output .= $arg == true ? 'true':'false' ;
		else if(in_array($type,array('integer','double','string')))$output .= $arg ;
		else if(in_array($type,array('array','object')))$output .=print_r($arg,true);
		else if(in_array($type,array('NULL','unknown type')))$output .= $type;
		else if($type == 'resource')$output .= '['.get_resource_type($arg).']'.$arg;
		else $output .= 'unknow';
		$output .= "\n";
	}
	return  $output;
}

/*
*接送服务 获取产品的有地点
*group =true会 分组为airport 和location
*/
function tep_transfer_get_locations($products_id , $group = false){
	$locationQuery = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_LOCATION." WHERE products_id = '".intval($products_id)."' ORDER BY type ASC,products_transfer_location_id ASC");
	$locations = array();
	$airport = array();
	$loc = array();
	while($row = tep_db_fetch_array($locationQuery)){
		if($row['zipcode'] == '0')
		$airport[] = $row ;
		else
		$loc[] = $row ;
		$locations[] = $row ;
	}
	if($group){
		return array('airport'=>$airport,'location'=>$loc);
	}else{
		return $locations;
	}
}
/**
 *获取全部路线
 **/
function tep_transfer_get_routes($products_id){
	$query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_ROUTE."  WHERE products_id = ".intval($products_id).' ORDER BY products_transfer_route_id ASC');
	$routeCount = 0 ;
	$routes = array();
	while($row = tep_db_fetch_array($query ))$routes[] = $row ;
	return $routes;
}

/**
 * 接送服务相关函数 ，检查产品是否为接送服务
 * 如果产品的数据已经查询出来请设置 $data 减少一次数据库操作
 * @param int $product_id
 * @param array [$data]  
 */
function tep_check_product_is_transfer($product_id , $data = array()){
	$product_id = intval($product_id);
	if(!isset($data['is_transfer'])){
		$chec_query = tep_db_query("select is_transfer from ".TABLE_PRODUCTS." where products_id = '".$product_id."'");
		$check = tep_db_fetch_array($chec_query);
	}else{
		$check = $data ;
	}
	if($check['is_transfer'] == '1'){
		$check_result = 1;
	}else{
		$check_result = 0;
	}
	return $check_result;
}
/*
*检查线路信息是否有效
*/
function tep_transfer_validate($products_id , $params ,$product_transfer_type = null){
	$products_id  = intval($products_id);
	$errorMsg = '';
	//检查产品是否是正常状态
	$query = tep_db_query('SELECT products_status,products_stock_status ,transfer_type,is_transfer FROM '.TABLE_PRODUCTS .' WHERE products_id = '.$products_id.' LIMIT 1');
	$product_info = tep_db_fetch_array($query);
	$product_transfer_type = intval($product_info['transfer_type']);
	if($product_info['products_status'] != '1' ){
		$errorMsg = '该产品暂时无法进行预订!'; //
	}elseif($product_info['products_stock_status'] == '0'){
		$errorMsg = '该产品已经售完,无法进行预订!';
	}
	if($errorMsg != '') return $errorMsg ;
	//读取soldout信息
	$soldout_dates = array();
	$soldout_dates_query=tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." sd WHERE sd.products_id='".$products_id."'");
	while($row_sold_dates=tep_db_fetch_array($soldout_dates_query)){
		$soldout_dates[] =  date('Y-m-d',strtotime($row_sold_dates['products_soldout_date']. ' 00:00:00'));
	}
	//读取operate信息
	$operate_info = array();
	$sql = tep_db_query('SELECT operate_start_date,operate_end_date,products_start_day,available_date  FROM '.TABLE_PRODUCTS_REG_IRREG_DATE.' WHERE products_id = '.$products_id);

	while($row=tep_db_fetch_array($sql)){
		if(check_valid_available_date_endate($row['operate_end_date']) == 'valid'){
			$row['operate_start'] = intval(date('Ymd',strtotime(str_replace('-','/',$row['operate_start_date']))));
			$row['operate_end'] = intval(date('Ymd'   ,strtotime(str_replace('-','/',$row['operate_end_date']))));
			$operate_info[] =  $row;
		}
	}
	$locations1 = tep_transfer_get_locations($products_id, false);
	$locations = array();
	foreach($locations1 as $l) $locations[] = $l['products_transfer_location_id'];
	$route = $product_transfer_type == '1' ? tep_transfer_get_routes($products_id):array();
	$route_is_set = false;
	$validRouteCount = 0 ;
	if($errorMsg == ''){
		for($i=1 ; $i<=2;$i++){
			if(is_numeric($params['pickup_id'.$i]) && is_numeric($params['dropoff_id'.$i]) && intval($params['guest_total'.$i]) > 0) {
				$validRouteCount++;
				$arrival_time = strtotime($params['flight_arrival_time'.$i]);
				$arrival_time_str_short = date('Y-m-d' ,$arrival_time );
				$arrival_time_int = intval(date('Ymd' ,$arrival_time ));
				$arrival_time_str_w = intval(date('w' ,$arrival_time ))+1;
				if(in_array($arrival_time_str_short,$soldout_dates)){
					$errorMsg = $arrival_time_str_short.' 的接送服务已经预订完了,请选择其他日期!'; //
					break;
				}
				//check operate
				$matchOperate = false;
				foreach($operate_info as $op){
					if($op['products_start_day'] == '0' && trim($op['available_date']) == $arrival_time_str_short){
						$matchOperate  = true ;
						break;
					}elseif($arrival_time_int >= $op['operate_start'] && $arrival_time_int<=  $op['operate_end'] ){
						if($op['products_start_day'] == $arrival_time_str_w){
							$matchOperate  = true ;
							break;
						}
					}
				}
				if($matchOperate ===false){
					$errorMsg = '预订的日期无效,请更换预订日期!';
					break;
				}
				//
				if($arrival_time <=  time()+ 3600*24){
					$errorMsg = '接送服务需要提前24小时预订!'; //
					break;
				}else if($params['pickup_id'.$i] == $params['dropoff_id'.$i]){
					$errorMsg = '接送服务的起点和终点不能设置为相同地点, 请检查!';
					break;
				}else if(!in_array($params['pickup_id'.$i] , $locations) || !in_array($params['dropoff_id'.$i] , $locations)){
					$errorMsg = '错误的接送服务起点或终点, 请检查!';
					break;
				}
				if($product_transfer_type == '1'){
					$in_route = false;
					foreach($route as $r){
						if(
						($params['pickup_id'.$i] == $r['pickup_location_id'] && $params['dropoff_id'.$i] == $r['dropoff_location_id'] )
						|| ($params['pickup_id'.$i] ==$r['dropoff_location_id'] && $params['dropoff_id'.$i] == $r['pickup_location_id'] )){
							$in_route = true ;
						}
					}
					if($in_route  !== true) {
						$errorMsg = '指定的线路不存在!';
						break;
					}
				}
			}
		}
		if($validRouteCount == 0 ) $errorMsg = '接送路线信息错误，请检查！';
	}
	//tep_log(print_vars2($route,$locations,$params));
	return $errorMsg ;
}
/*
*根据参数计算接送服务价格
*/
function tep_transfer_calculation_price($products_id , $params , $return_cost = false){
	$products_id = intval($products_id);
	$query = tep_db_query("SELECT transfer_type,is_transfer,products_single as price_1_3,products_double as price_4_6 ,products_triple AS price_overtime,products_single_cost as cost_1_3,products_double_cost as cost_4_6 ,products_triple_cost AS cost_overtime FROM ".TABLE_PRODUCTS.' WHERE products_id = '.$products_id." LIMIT 1");
	$product_price = tep_db_fetch_array($query);

	$locations = array();
	$locationQuery = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_LOCATION." WHERE products_id = '".$products_id."' ORDER BY type ASC,products_transfer_location_id ASC");
	while($row = tep_db_fetch_array($locationQuery)){$locations[$row['products_transfer_location_id']] = $row;}

	$price = 0 ;
	$cost = 0 ;
	//线路价格固定
	if($product_price['transfer_type'] == '0'){
		for($i=1;$i<=2;$i++){
			$guest_total = intval($params['guest_total'.$i]);
			$flight_arrival_time = strtotime($params['flight_arrival_time'.$i]);
			$flight_arrival_time_int = intval(date('Hi',$flight_arrival_time));
			$pickup = $params['pickup_id'.$i];
			$dropoff = $params['dropoff_id'.$i];
			if(array_key_exists($pickup ,$locations ) && array_key_exists($dropoff ,$locations ) && $guest_total > 0){
				//进行执行价格的查询
				$carAmount = floor($guest_total/6);
				$exPerson = $guest_total%6;
				if($exPerson> 0  && $exPerson <= 3 ){
					$exPrice = $product_price['price_1_3'];
					$exCost = $product_price['cost_1_3'];
				}elseif($exPerson>3 ){
					$exPrice = $product_price['price_4_6'];
					$exCost = $product_price['cost_4_6'];
				}else {
					$exPrice=0;
					$exCost = 0 ;
				}
				$price=  $price + $carAmount * $product_price['price_4_6'] + $exPrice;
				$cost = $cost +  $carAmount * $product_price['cost_4_6'] + $exCost;

				if($flight_arrival_time_int < 830 ||  $flight_arrival_time_int > 2230){
					$price = $price + ceil($guest_total/6)* $product_price['price_overtime'];
					$cost = $cost + ceil($guest_total/6)* $product_price['cost_overtime'];
				}
			}
		}
	}else if($product_price['transfer_type'] == '1'){//线路价格不固定

		$routeQuery = tep_db_query("SELECT pickup_location_id AS pickup , dropoff_location_id AS dropoff ,price1 AS price_1_3, price2 AS price_4_6 ,price1_cost AS cost_1_3, price2_cost AS cost_4_6 FROM ".TABLE_PRODUCTS_TRANSFER_ROUTE." WHERE products_id = '".$products_id."' ");
		$routelist = array();
		while($row = tep_db_fetch_array($routeQuery)){
			$routelist[] = $row;
		}

		for($i=1;$i<=2;$i++){
			$guest_total = intval($params['guest_total'.$i]);
			$flight_arrival_time = strtotime($params['flight_arrival_time'.$i]);
			$flight_arrival_time_int = intval(date('Hi',$flight_arrival_time));
			$pickup = $params['pickup_id'.$i];
			$dropoff = $params['dropoff_id'.$i];
			if(array_key_exists($pickup ,$locations ) && array_key_exists($dropoff ,$locations ) && $guest_total > 0){
				//find route
				$matchedRoute = array();
				foreach($routelist as $route){
					if(($route['pickup'] == $pickup && $route['dropoff'] == $dropoff) || ($route['dropoff'] == $pickup && $route['pickup'] == $dropoff)){
						$matchedRoute = $route;
						break;
					}
				}
				if(empty($matchedRoute)){
					return '对不起!我们暂时未提供你指定线路的接送服务。';
				}else{
					$carAmount = floor($guest_total/6);
					$exPerson = $guest_total%6;
					if($exPerson> 0  && $exPerson <= 3 ){
						$exPrice = $matchedRoute['price_1_3'];
						$exCost = $matchedRoute['cost_1_3'];
					}elseif($exPerson>3) {
						$exPrice = $matchedRoute['price_4_6'];
						$exCost = $matchedRoute['cost_4_6'];
					}else{
						$exPrice=0;
						$exCost = 0 ;
					}
					$price =  $price + $carAmount * $matchedRoute['price_4_6'] + $exPrice;
					$cost =  $cost + $carAmount * $matchedRoute['cost_4_6'] + $exCost;
					if($flight_arrival_time_int < 830 ||  $flight_arrival_time_int > 2230){
						$price = $price + ceil($guest_total/6)* $product_price['price_overtime'];
						$cost = $cost + ceil($guest_total/6)* $product_price['cost_overtime'];
					}
				}
			}
		}
	}
	if($return_cost === true){
		return  array('price'=>$price ,'cost'=>$cost);
	}else{
		return $price;
	}
}
/**
 * 将输入的接送服务参数编码为存放到购物车的字符串形式
 * 返回的字符串使用GB2312编码
 * @param unknown_type $param
 */
function tep_transfer_encode_info($params  , $ajax_trans = true){
	//tep_log($params);
	$sep1 = "|==|";
	$sep2 = "||";
	foreach($params as $key=>$value){
		if(!is_array($value) && $value != ''){
			$params[$key] = str_replace(array($sep1 ,$sep2 ),'',$value);
			if($ajax_trans){
				$params[$key] = ajax_to_general_string($params[$key]);
			}
		}
	}
	$output = $params['transferType'].$sep1;
	$route =array(
	'0'=>array(
	$params['pickup_id1'],$params['pickup_zipcode1'],$params['pickup_address1'],
	$params['dropoff_id1'],$params['dropoff_zipcode1'],$params['dropoff_address1'],
	$params['flight_number1'],$params['flight_departure1'],$params['flight_arrival_time1'],
	$params['guest_total1'],$params['baggage_total1'],$params['comment1'],$params['flight_pick_time1']
	),
	'1'=>array(
	$params['pickup_id2'],$params['pickup_zipcode2'],$params['pickup_address2'],
	$params['dropoff_id2'],$params['dropoff_zipcode2'],$params['dropoff_address2'],
	$params['flight_number2'],$params['flight_departure2'],$params['flight_arrival_time2'],
	$params['guest_total2'],$params['baggage_total2'],$params['comment2'],$params['flight_pick_time2']
	));
	for($i=0;$i<2;$i++){
		$output.= implode($sep2 , $route[$i]) .$sep1;
	}
	return html_to_db(substr($output ,0, strlen($output) - strlen($sep1)));
}

/**
 * 将输入的接送服务的字符串转成数组
 * @param unknown_type $param
 */
function tep_transfer_decode_info($param_string){
	$keys = array('pickup_id','pickup_zipcode','pickup_address','dropoff_id','dropoff_zipcode','dropoff_address','flight_number','flight_departure','flight_arrival_time','guest_total','baggage_total','comment','flight_pick_time');
	$sep1 = "|==|";
	$sep2 = "||";
	$route_parts = explode($sep1 ,$param_string);
	$para_arr = array();
	$para_arr['transferType'] = intval($route_parts [0]);
	$routeArray =array();
	for($i=1 ;$i<3;$i++){
		$route = array();
		$rparam = explode($sep2 , $route_parts[$i]);
		for($j=0,$k=count($keys);$j<$k;$j++){
			if($keys[$j] == 'flight_arrival_time' && trim($rparam[$j]) !=''){
				$value = date('m/d/Y h:i A',strtotime($rparam[$j]));
			}else if($keys[$j] == 'flight_pick_time' && trim($rparam[$j])!=''){
				$value = date('m/d/Y h:i A',strtotime($rparam[$j]));
			}else{
				$value = $rparam[$j];
			}
			$para_arr[$keys[$j].$i] = $value ;
			$route[$keys[$j]] = $value ;
		}
		$routeArray[] = $route;
	}
	$para_arr['routes'] = $routeArray;
	return $para_arr;
}
/*
*输出接送服务路线信息文本
*/
function tep_transfer_display_route($transfer_info_array,$forMail = false){
	$output = '';
	$routeIndex= 1;
	foreach($transfer_info_array['routes'] as $route){
		if($route['guest_total'] > 0 &&is_numeric($route['pickup_id'])&&is_numeric($route['dropoff_id']) ){
			if($forMail  == true) {
				$prefix = '-';
			}else{
				$prefix='';
				$output .= "<span style=\"font-size:12px;color:#000;font-style:italic\"><strong>".$routeIndex." .</strong> </span>";
			}
			$output .= $prefix."<strong>起点：</strong> ".tep_db_output($route['pickup_address']).($route['pickup_zipcode']!=0 && $route['pickup_zipcode']!='' ? '('.$route['pickup_zipcode'].')':'' );
			$output .= "&nbsp;&nbsp;<strong>终点：</strong> ".tep_db_output($route['dropoff_address']).($route['dropoff_zipcode']!=0 && $route['dropoff_zipcode']!='' ? '('.$route['dropoff_zipcode'].')':'' );
			$output .= "<br>".$prefix."<strong>航空公司名称/号码：</strong>".$route['flight_number'];
			$output .= "<br>".$prefix."<strong>航班起飞/抵达时间：</strong>".$route['flight_arrival_time'];
			$output .= "<br>"." <strong>接应/送达详细地点：</strong>".$route['flight_departure'];
			if($route['flight_pick_time']!=''){
				$output .= "<br>".$prefix."<strong>期待被接应时间：</strong>".$route['flight_pick_time'];
			}
			$output .=" <br><strong>人数：</strong>".$route['guest_total']."人 <strong>行李：</strong>".$route['baggage_total']."件";
			if($route['comment']!='')$output .= "<br>".$prefix."<strong>留言：</strong>".tep_db_output($route['comment']);
			$output .= "<br><br>";
			$routeIndex++;
		}
	}
	if($forMail == true){
		return strip_tags(str_replace(array('&nbsp;','<br>'),array(' ',"\n") , $output));
	}
	return $output;
}
/**
* 读取订单产品的路线设置信息
*/
function tep_transfer_get_order_transfer($order_products_id){
	$query = tep_db_query("SELECT * FROM ".TABLE_ORDERS_PRODUCTS_TRANSFER.' WHERE orders_products_id = '.intval($order_products_id));
	$route_array = array( 'routes'=>array());
	$index = 1;
	while($row = tep_db_fetch_array($query)){
		$route_array['orders_products_transfer_id'] = $row['orders_products_transfer_id'];
		$route_array['orders_products_id'] = $row['orders_products_id'];
		$route_array['created_date'] = $row['created_date'];
		$route_array['orders_id'] = $row['orders_id'];

		$flight_arrival_time =  date('m/d/Y h:i A',strtotime($row['flight_arrival_time']));
		$flight_pick_time =  $row['flight_pick_time'] == ''||$row['flight_pick_time'] =="1970-1-1 0:0:0" ? '':date('m/d/Y h:i A',strtotime($row['flight_pick_time']));

		$route_array['flight_number'.$index] = $row['flight_number'];
		$route_array['flight_departure'.$index] = $row['flight_departure'];
		$route_array['flight_arrival_time'.$index] = $flight_arrival_time;
		$route_array['pickup_id'.$index] = $row['pickup_id'];
		$route_array['pickup_address'.$index] = $row['pickup_address'];
		$route_array['pickup_zipcode'.$index] = $row['pickup_zipcode'];
		$route_array['dropoff_id'.$index] = $row['dropoff_id'];
		$route_array['dropoff_address'.$index] = $row['dropoff_address'];
		$route_array['dropoff_zipcode'.$index] = $row['dropoff_zipcode'];
		$route_array['guest_total'.$index] = $row['guest_total'];
		$route_array['baggage_total'.$index] = $row['baggage_total'];
		$route_array['comment'.$index] = $row['comment'];
		$tmp=array(
		'flight_number'=> $row['flight_number'],
		'flight_departure'=> $row['flight_departure'],
		'flight_arrival_time'=> $flight_arrival_time,
		'pickup_id'=> $row['pickup_id'],
		'pickup_address'=> $row['pickup_address'],
		'pickup_zipcode'=> $row['pickup_zipcode'],
		'dropoff_id'=> $row['dropoff_id'],
		'dropoff_address'=> $row['dropoff_address'],
		'dropoff_zipcode'=> $row['dropoff_zipcode'],
		'guest_total'=> $row['guest_total'],
		'baggage_total'=> $row['baggage_total'],
		'comment'=> $row['comment']
		);
		if($flight_pick_time !=''){
			$route_array['flight_pick_time'.$index] = $flight_pick_time;
			$tmp['flight_pick_time' ] = $flight_pick_time;
		}
		$route_array ['routes'][] = $tmp;
		$index++;
	}
	return $route_array;
}
/**
* 根据接驳车产品地址ID取得邮政编码
*/
function getZipCodeFromTransfer($location_id){
	$sql = tep_db_query('SELECT zipcode FROM `products_transfer_location` WHERE products_transfer_location_id="'.(int)$location_id.'" ');
	$row = tep_db_fetch_array($sql);
	return $row['zipcode'];
}

/**
 * 打印变量信息 ，在生产站和QA 站无效
 * @param [$var ....]
 */
function print_vars(){
	if(IS_LIVE_SITES === true || IS_QA_SITES === true){
		return  ;
	}
	$args = func_get_args();
	$output = call_user_func_array("print_vars2",$args);
	echo '<pre style="font-family: Courier New;font-size:12px;text-align:left">';
	echo htmlspecialchars($output);
	echo '</pre>';
}

function tep_get_product_option_name_from_optionid($opt_id){
	global $languages_id;
	$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt where popt.products_options_id='" . (int)$opt_id . "' and popt.language_id = '" . (int)$languages_id . "'");
	if(tep_db_num_rows($products_options_name_query) > 0){
		$products_options_name = tep_db_fetch_array($products_options_name_query);
		return $products_options_name['products_options_name'];
	}else{
		return false;
	}
}


/**
 * 取得订单产品的付款状态数组
 * 0未付款，1已付款，2已部分付款
 */
function tep_get_orders_products_payment_status_array(){
	$array = array();
	$array[] = array('id'=>'0','text'=>'未付款', 'color'=>'');
	$array[] = array('id'=>'1','text'=>'已付款', 'color'=>'#F00');	//已付款是红字
	$array[] = array('id'=>'2','text'=>'已部分付款', 'color'=>'#090');		//已部分付款是绿色
	return $array;
}

/**
 * 取得订单产品的付款状态名称
 *
 * @param unknown_type $status_id
 */
function tep_get_orders_products_payment_status_name($status_id,$need_style=true){
	$status_id = (int)$status_id;
	$array = tep_get_orders_products_payment_status_array();
	if($need_style==true && $array[$status_id]['color']!=''){
		return '<b style="color:'.$array[$status_id]['color'].'">'.$array[$status_id]['text'].'</b>';
	}
	return $array[$status_id]['text'];
}

/**
 * 取得订单的付款状态名称
 *
 * @param unknown_type $orders_id
 */
function tep_get_orders_payment_status_name($orders_id){
	$string = '';
	$sql = tep_db_query('SELECT orders_products_payment_status FROM '.TABLE_ORDERS_PRODUCTS.' WHERE orders_id ="'.(int)$orders_id.'"  ORDER BY products_departure_date DESC ');
	while ($rows = tep_db_fetch_array($sql)) {
		$string.='<div>'.tep_get_orders_products_payment_status_name($rows['orders_products_payment_status']).'</div>';
	}
	return $string;
}

/**
 * 更新客服对订单的所有权
 *
 * @param unknown_type $login_id
 * @param unknown_type $orders_id
 * @param unknown_type $commission
 */
function tep_update_order_orders_owner($login_id, $orders_id, $commission='1'){
	return;
	if(tep_check_service_account($login_id)){	//是客服的账号才能更新
		//tep_db_query('update '.TABLE_ORDERS.' set orders_owner_admin_id="'.(int)$login_id.'", orders_owner_commission="'.tep_db_input(tep_db_prepare_input($commission)).'" where orders_id="'.(int)$orders_id.'" and (orders_owner_commission="" || orders_owner_commission="0" || orders_owner_commission IS NULL ); ');
		$date_time = date("Y-m-d H:i:s");
		//原来没有销售链接的单,打开后自动添加链接及销售跟踪
		$str = 'CALL mysp_orders_addowner_nolink('.(int)$orders_id.','.(int)$login_id.',"'.$date_time.'");';
		//echo $str; exit;
		tep_db_call_sp($str);
	}
}

/**
 * 检查某个登录id是不是客服，返回true或false
 * admin_groups_id = 7或5和48的就是客服，其它不是，其中操作员42也不能算
 * @param unknown_type $login_id
 * 
 */
function tep_check_service_account($login_id){
	$sql = tep_db_query('SELECT admin_groups_id FROM `admin` WHERE admin_id ="'.(int)$login_id.'" ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['admin_groups_id'])){
		$_tmp_array = explode(',',$row['admin_groups_id']);
		if(in_array('5',$_tmp_array) || in_array('7',$_tmp_array) || in_array('48',$_tmp_array)){
			return true;
		}
	}
	return false;
}

/**
 * 从工号取得客服的id
 *
 * @param unknown_type $admin_job_number
 */
function tep_get_admin_id_from_job_number($admin_job_number){
	$sql = tep_db_query('SELECT admin_id FROM `admin` WHERE admin_job_number ="'.tep_db_prepare_input(tep_db_input($admin_job_number)).'" ');
	$row = tep_db_fetch_array($sql);
	return (int)$row['admin_id'];
}

/**
 * 从客服ID号取得工号
 *
 * @param unknown_type $admin_id
 */
function tep_get_job_number_from_admin_id($admin_id){
	$sql = tep_db_query('SELECT admin_job_number FROM `admin` WHERE admin_id ="'.tep_db_prepare_input(tep_db_input($admin_id)).'" ');
	$row = tep_db_fetch_array($sql);
	return (int)$row['admin_job_number'];
}

/**
 * 当支付成功时记录客户的支付过程并更新订单已付款的数据（必须是已经成功支付了的）,并使此订单置顶
 *
 * @param int $orders_id
 * @param number $usa_value
 * @param string $payment_method
 * @param string $comment （如果是人民币支付的则要记录当时的汇率，人民币数值等）
 * @param int $orders_id_include_time 带时间值的订单号（用于在线付款） 
 */
function tep_payment_success_update($orders_id, $usa_value, $payment_method='', $comment='', $admin_id=0, $orders_id_include_time){
	$dateTime = date('Y-m-d H:i:s');
	if(strlen($orders_id_include_time)<1){ die('no $orders_id_include_time'); }
	if((int)$orders_id && $usa_value!="" && $payment_method!=""){
		$sql = tep_db_query('SELECT orders_payment_history_id FROM `orders_payment_history` where orders_id="'.(int)$orders_id.'" and orders_id_include_time="'.(string)$orders_id_include_time.'" ');
		$row = tep_db_fetch_array($sql);
		if(!(int)$row['orders_payment_history_id']){	//无此历史ID时才记录以防重复
			$data_array = array('orders_id'=>(int)$orders_id,
			'orders_id_include_time' => $orders_id_include_time,
			'orders_value'=>$usa_value,
			'payment_method'=>$payment_method,
			'comment'=>$comment,
			'add_date'=>$dateTime,
			'admin_id'=>$admin_id);
			tep_db_perform('orders_payment_history', $data_array);
			$sql = tep_db_query('select sum(orders_value) as total FROM orders_payment_history WHERE orders_id="'.(int)$orders_id.'" ');
			$row = tep_db_fetch_array($sql);
			tep_db_query('update `orders` set orders_paid="'.$row['total'].'" WHERE orders_id ="'.(int)$orders_id.'" ');
			//更新订单付款状态
			$orders_total_sql = tep_db_query('SELECT value FROM `orders_total` where orders_id="'.(int)$orders_id.'" and class="ot_total" ');
			$orders_row = tep_db_fetch_array($orders_total_sql);
			if(tep_round($orders_row['value'], 2) <= tep_round($row['total'], 2)){
				tep_db_query('update `orders_products` set orders_products_payment_status="1" WHERE orders_id ="'.(int)$orders_id.'" ');
			}elseif ((int)$row['total']){
				tep_db_query('update `orders_products` set orders_products_payment_status="2" WHERE orders_id ="'.(int)$orders_id.'" ');
			}
			tep_db_query('update `orders` set last_modified="'.$dateTime.'", is_top="1" where orders_id ="'.(int)$orders_id.'" ');
			//检查和设置客人再次付款订单(付款记录是负值的不记)
			if((int)$usa_value > 0){
				tep_set_or_close_again_paid_orders($orders_id, '1');
			}
			//此处发电子邮件通知财务人员，未做
			return true;
		}
	}
	return false;
}

/**
 * 设置或取消客人再次付款订单
 * @param int $orders_id 订单号
 * @param int $val 设置的值1为设置，0为取消设置
 */
function tep_set_or_close_again_paid_orders($orders_id, $val='1'){
	$orders_id = (int)$orders_id;
	if($val=="1"){ //订单有发送地接历史，客人再次付款的才可以设置为再次付款订单
		$sql = tep_db_query('SELECT count(*) as num FROM orders_payment_history where orders_id="'.$orders_id.'" and orders_value>0 ');
		$row = tep_db_fetch_array($sql);
		if((int)$row['num']<2){
			return false;
		}
		$psql = tep_db_query('SELECT popsh.popc_id FROM `orders_products` op, provider_order_products_status_history popsh where op.orders_products_id=popsh.orders_products_id and op.orders_id="'.$orders_id.'" Limit 1');
		$prow = tep_db_fetch_array($psql);
		if(!$prow['popc_id']){
			return false;
		}
	}
	tep_db_query('update orders set is_again_paid="'.(int)$val.'" where orders_id="'.(int)$orders_id.'" ');
	return tep_db_affected_rows();
}

/**
 * 取得我们与供应商的订单行程交流历史信息，并自动分组
 *
 * @param unknown_type $orders_products_id
 */
function tep_get_provider_order_products_status_history($orders_products_id){
	$qry_order_prod_history="select h.popc_id, h.provider_comment, h.provider_status_update_date, h.popc_user_type, s.provider_order_status_name, op.products_id,
							op.products_model, if(h.popc_user_type=0, h.popc_updated_by, p.agency_id) as updated_by, popc_updated_by, p.agency_id
							FROM " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY . " h, " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " s, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p
							WHERE
							h.provider_order_status_id=s.provider_order_status_id AND
							h.orders_products_id=op.orders_products_id AND
							op.products_id=p.products_id AND
							h.notify_usi4trip=1 AND
							h.orders_products_id='".(int)$orders_products_id."' ORDER BY p.products_id ASC, h.popc_id ASC";

	$res_order_prod_history = tep_db_query($qry_order_prod_history);
	$agency_prod_historys = false;
	$data = false;
	while($rows = tep_db_fetch_array($res_order_prod_history)){
		$agency_prod_historys[] = $rows;
	}
	//按左边我们，右边地接的一一对应形式填充数据
	if($agency_prod_historys){
		for($i=0; $i<sizeof($agency_prod_historys); $i++){
			$data[][$agency_prod_historys[$i]['popc_user_type']] = $agency_prod_historys[$i];

			if(!tep_not_null($data['heardTitie'][0])) $data['heardTitie'][0] = STORE_NAME;	//列标题
			if(!tep_not_null($data['heardTitie'][1])) $data['heardTitie'][1] = $agency_prod_historys[$i]['agency_id'];//tep_get_providers_agency($agency_prod_historys[$i]['agency_id'], 1);
		}
	}
	return $data;
}

/**
 * 添加航班信息更新历史记录
 */
function tep_add_orders_product_flight_history($data_array,$orders_products_id,$admin_id=0,$customers_id=0,$sent_confirm_email_to_provider_value=0 ){
	$will_add = true;
	$check_sql = tep_db_query('select * FROM orders_product_flight_history where orders_products_id ="'.(int)$orders_products_id.'" order by history_id DESC Limit 1 ');
	$check_row = tep_db_fetch_array($check_sql);
	if ((int)$check_row['history_id']){
		if($data_array['airline_name']==$check_row['airline_name']
		&& $data_array['flight_no']==$check_row['flight_no']
		&& $data_array['airline_name_departure']==$check_row['airline_name_departure']
		&& $data_array['flight_no_departure']==$check_row['flight_no_departure']
		&& $data_array['airport_name']==$check_row['airport_name']
		&& $data_array['airport_name_departure']==$check_row['airport_name_departure']
		&& $data_array['arrival_date']==$check_row['arrival_date']
		&& $data_array['arrival_time']==$check_row['arrival_time']
		&& $data_array['departure_date']==$check_row['departure_date']
		&& $data_array['departure_time']==$check_row['departure_time']
		){
			$will_add = false;
		}
	}
	if($will_add === true){
		$sql_data_array = array('orders_products_id'=>(int)$orders_products_id,
		'airline_name'=>$data_array['airline_name'],
		'flight_no'=>$data_array['flight_no'],
		'airline_name_departure'=>$data_array['airline_name_departure'],
		'flight_no_departure'=>$data_array['flight_no_departure'],
		'airport_name'=>$data_array['airport_name'],
		'airport_name_departure'=>$data_array['airport_name_departure'],
		'arrival_date'=>$data_array['arrival_date'],
		'arrival_time'=>$data_array['arrival_time'],
		'departure_date'=>$data_array['departure_date'],
		'departure_time'=>$data_array['departure_time'],
		'add_date' => date('Y-m-d H:i:s')
		);
		if((int)$admin_id) $sql_data_array['admin_id'] = (int)$admin_id;
		if((int)$customers_id) $sql_data_array['customers_id'] = (int)$customers_id;
		tep_db_perform('orders_product_flight_history',$sql_data_array);

		tep_db_query('update '.TABLE_ORDERS_PRODUCTS_FLIGHT.' set sent_confirm_email_to_provider="'.(int)$sent_confirm_email_to_provider_value.'" where orders_products_id="'.(int)$orders_products_id.'" ');
		
		//航班信息更新后提醒主管OP等
		$orders_id = tep_db_get_field_value('orders_id', 'orders_products', 'orders_products_id="'.(int)$orders_products_id.'" ');
		tep_set_order_up_no_change_price($orders_id, '1', '航班信息更新');
	}
}


/**
 * 判断某个产品是不是酒店
 *
 * @param unknown_type $products_id
 * @return unknown
 */
function product_is_hotel($products_id){
	/*
	//酒店目录id号是182
	$c_id = 182;
	$sql = tep_db_query('SELECT products_id FROM `products_to_categories` WHERE products_id="'.(int)$products_id.'" AND categories_id="'.(int)$c_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	if($row['products_id']>0){ return true; }
	*/
	$sql = tep_db_query('SELECT is_hotel FROM `products` WHERE products_id="'.(int)$products_id.'" ');
	$row = tep_db_fetch_array($sql);
	return (int)$row['is_hotel'];
}

/**
 * 过电子参团凭证滤客人的中文名字
 * 格式：中文名 [zhou haohua] 
 * @param unknown_type $customers_name 中文名 [zhou haohua]
 */
function tep_filter_guest_chinese_name($customers_name = ''){
	$customers_name = trim($customers_name);
	if(preg_match('/\[(.+)\]/',$customers_name, $m) ){
		$customers_name = $m[1];
	}
	return $customers_name;
}

/**
 * 添加上车地址更新历史记录
 *
 * @param int $orders_products_id 订单产品快照id
 * @param string $departure_location 注意：这个地址是不需要经过tep_db_prepare_input和tep_db_input转码的
 * @param int $updated_by 操作员id
 */
function tep_add_departure_location_history($orders_products_id, $departure_location, $updated_by=96){
	$old_location = tep_db_query('SELECT history_id, departure_location FROM `orders_products_departure_location_history` WHERE orders_products_id="'.(int)$orders_products_id.'" order By history_id DESC Limit 1');
	$old_location = tep_db_fetch_array($old_location);
	if(!(int)$old_location['history_id'] || trim($departure_location) != trim($old_location['departure_location'])){
		tep_db_query('INSERT INTO `orders_products_departure_location_history` set orders_products_id="'.(int)$orders_products_id.'", departure_location="'.tep_db_prepare_input(tep_db_input($departure_location)).'", added_time="'.date('Y-m-d H:i:s').'", updated_by="'.$updated_by.'" ');
		//出发日期、上车地址更新后提醒主管OP等
		$orders_id = tep_db_get_field_value('orders_id', 'orders_products', 'orders_products_id="'.(int)$orders_products_id.'" ');
		tep_set_order_up_no_change_price($orders_id, '1', '出发日期或上车地址更新');
	}
}

/**
 * 取得上车地址的历史记录
 *
 * @param unknown_type $orders_products_id
 */
function tep_get_departure_location_history($orders_products_id){
	$data = false;
	$sql = tep_db_query('SELECT * FROM `orders_products_departure_location_history` WHERE orders_products_id = "'.(int)$orders_products_id.'" order by history_id DESC ');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = $rows;
	}
	return $data;
}

/**
 * 添加或删除操作员认为有问题的订单
 * @param int $order_id 订单id
 * @param int $login_id 管理员id
 * @param int $action 动作'add'为添加,'sub'为删除
 */
function tep_add_or_sub_op_think_problems_orders($order_id, $login_id, $action='add'){
	$order_id = (int)$order_id;
	if($action=='add'){
		if(!(int)tep_db_get_field_value('orders_id', 'orders_op_think_problems', ' orders_id="'.$order_id.'" ')){
			tep_db_query('INSERT INTO `orders_op_think_problems` SET orders_id="'.$order_id.'", admin_id="'.(int)$login_id.'", added_date="'.date('Y-m-d H:i:s').'" ');
		}
	}elseif($action=='sub'){
		tep_db_query('DELETE FROM `orders_op_think_problems` WHERE `orders_id` = "'.$order_id.'" ');
	}
}
/**
 * 添加或删除已下单地接未回复的记录到数据表
 * @param int $orders_products_id 订单快照id
 * @param int $login_id 管理员id
 * @param int $action 动作'add'为添加,'sub'为删除
 */
function tep_add_or_sub_sent_provider_not_re_rows ($orders_products_id, $login_id, $action='add'){
	$orders_products_id = (int)$orders_products_id;
	if($action=='add'){
		if(!(int)tep_db_get_field_value('orders_products_id', 'orders_products_sent_provider_not_re', ' orders_products_id="'.$orders_products_id.'" ')){
			tep_db_query('INSERT INTO `orders_products_sent_provider_not_re` SET orders_products_id="'.$orders_products_id.'", admin_id="'.(int)$login_id.'", added_date="'.date('Y-m-d H:i:s').'" ');
		}
	}elseif($action=='sub'){
		tep_db_query('DELETE FROM `orders_products_sent_provider_not_re` WHERE `orders_products_id` = "'.$orders_products_id.'" ');
	}
}

/**
 * 产品克隆
 * @param int $products_id 产品id
 * @param int $categories_id 目录id
 */
function copy_product_clone_to_category($products_id, $categories_id){
	$products_id = (int)$products_id;
	$categories_id = (int)$categories_id;
	//产品主表复制
	$fields = tep_db_table_fields_names('products');
	$pKey = array_search('products_id', $fields);
	unset($fields[$pKey]);
	$field_str = implode(', ', $fields);
	$sql_str = 'INSERT INTO products ('.$field_str.') SELECT '.$field_str.' FROM products WHERE products_id="'.(int)$products_id.'" ;';
	tep_db_query($sql_str);
	$dup_products_id = tep_db_insert_id();
	if($dup_products_id > 0) {	//副表复制
		$tables = array('products_attributes', 'products_attributes_download', 'products_buy_two_get_one', 'products_departure', 'products_departure_dates_for_search', 'products_description', 'products_destination', 'products_extra_images', 'products_hotels', 'products_reg_irreg_date', 'products_reg_irreg_description', 'products_reg_irreg_standard_price', 'products_start_day', 'products_to_cruises', 'products_transfer_location', 'products_transfer_route', 'products_travel' );
		foreach ($tables as $table_name){
			$fields = tep_db_table_fields_names($table_name);
			$pKey = array_search('products_id', $fields);
			if($pKey !== false){	//有products_id字段的表才能被复制
				unset($fields[$pKey]);
				//清除单一主键的表字段
				$primary_keys = tep_db_table_primary_keys($table_name);
				if($primary_keys && sizeof($primary_keys)==1){
					foreach ($primary_keys as $key_name){
						$priKey = array_search($key_name, $fields);
						if($priKey!==false) unset($fields[$priKey]);
					}
				}
				if($fields){
					$field_str = implode(', ', $fields);
					tep_db_query('INSERT INTO '.$table_name.' ('.$field_str.', products_id ) SELECT '.$field_str.', "'.$dup_products_id.'" FROM '.$table_name.' WHERE products_id="'.(int)$products_id.'" ;');
				}
			}
		}
		tep_db_query('INSERT INTO products_to_categories set products_id="'.$dup_products_id.'", categories_id="'.$categories_id.'" ;');	//设置产品所属目录
		tep_update_products_model($dup_products_id);	//更新新产品团号
		tep_set_product_status($dup_products_id, '0');	//关闭新产品
		tep_db_query('UPDATE products SET products_urlname=CONCAT(products_urlname, "-'.$dup_products_id.'") WHERE products_id="'.$dup_products_id.'" ');	//设置新产品的url信息
	}
	return $dup_products_id;
}
/**
 * 产品行程内容复制
 * @param int $source_products_id 源产品id
 * @param string $target_products_id 目标产品id，多外产品用,号隔开
 * @param int $travel_index 产品第几天的索引值
 * @param string $cp_range 复制范围
 * @return 返回成功复制的数目
 */
function tep_copy_products_travel($source_products_id, $target_products_id, $travel_index, $cp_range = 'all'){
	$source_products_id = (int)$source_products_id;
	$travel_index = (int)$travel_index;
	//数据检查
	if(!$source_products_id || !preg_match('/^[0-9]+(,[0-9]+)*$/',$target_products_id) || !$travel_index) return false;
	$rows = 0;
	if($cp_range=='all'){	//全盘复制
		tep_db_query('DELETE FROM `products_travel` WHERE `travel_index` = "'.$travel_index.'" AND `products_id` in('.$target_products_id.');');
		$fields = tep_db_table_fields_names('products_travel');
		unset($fields[array_search('products_id', $fields)]);	//去掉products_id
		$field_str = implode(',',$fields);
		foreach (explode(',',$target_products_id) as $tpid){
			tep_db_query('INSERT INTO `products_travel` ('.$field_str.', products_id ) SELECT '.$field_str.', "'.$tpid.'" FROM `products_travel` WHERE products_id="'.$source_products_id.'" AND travel_index="'.$travel_index.'" AND langid="1" ;');
			$rows += tep_db_affected_rows();
		}
	}else {		//部分复制
		$replace_str = '';
		$sql_str = 'UPDATE products_travel a, products_travel b SET [fields] WHERE a.travel_index="'.$travel_index.'" AND b.travel_index="'.$travel_index.'" AND a.products_id in ('.$target_products_id.') AND b.products_id="'.$source_products_id.'" AND a.langid=b.langid';
		switch($cp_range){
			case 'name': //复制行程标题
				$replace_str = ' a.travel_name = b.travel_name ';
			break;
			case 'images': //复制图片
				$replace_str = ' a.travel_imgalt = b.travel_imgalt, a.travel_img = b.travel_img ';
			break;
			case 'content':	//复制行程内容
				$replace_str = ' a.travel_content = b.travel_content ';
			break;
			case 'hotel':	//复制酒店内容
				$replace_str = ' a.travel_hotel = b.travel_hotel ';
			break;
		}
		if($replace_str!=''){
			$sql_str = str_replace('[fields]',$replace_str, $sql_str);
			tep_db_query($sql_str);
			$rows += tep_db_affected_rows();
		}
	}
	return $rows;
}
/**
 * 更新产品团号
 * @param int $products_id
 */
function tep_update_products_model($products_id){
	$city_id = tep_get_product_departure_city($products_id, 'id');
	$agency_id = tep_db_get_field_value('agency_id','products',' products_id='.(int)$products_id);
	$products_model = tep_create_product_code($agency_id, $city_id, $products_id);
	if($products_model){
		tep_db_query('UPDATE products SET products_model="'.$products_model.'" where products_id='.(int)$products_id);
		return tep_db_affected_rows();
	}
	return false;
}

/**
 * 生成产品的团号 products_model
 * @param int $agency_id 供应商id
 * @param int $city_id 出发城市id
 * @param int $products_id 产品id
 * @example 产品的CODE公式是出发城市代码+供应商ID+"-"+产品ID
 */
function tep_create_product_code($agency_id, $city_id, $products_id){
	$auto_generated_model='';
	if((int)$city_id && (int)$agency_id){
		$sql = tep_db_query('SELECT c.city_code FROM `tour_city` c WHERE c.city_id ="'.(int)$city_id.'" ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['city_code'])){
			$auto_generated_model =$row['city_code'].(int)$agency_id;
			$auto_generated_model .= '-'.(int)$products_id;
		}
	}
	return $auto_generated_model;
}


/**
 * 上传地接提供的发票文件
 * @param string $_files_name 文件域的名称
 * @param int $orders_id 订单号
 * @param int $products_id 产品id号
 * @param int $orders_products_id 订单产品id号
 * @param array $allow_type 允许上传的类型
 * @return true|false
 */
function uploadInvoices($_files_name, $orders_id, $products_id, $orders_products_id, $allow_type = array('pdf','rar','zip')){
	$save_path = DIR_FS_CATALOG . 'images/invoices/';
	$file_type = get_file_type($_FILES[$_files_name]['tmp_name']);

	if (in_array(strtolower($file_type), $allow_type)) {
		//允许用户文件，文件名格式invoice_订单号_产品id_时间.扩展名
		$ex_name        = preg_replace('/.+\./','',$_FILES[$_files_name]['name']);
		$save_file_name = 'invoice_' . $orders_id . '_' . $products_id . '_' . date('mdHis') . '.' . $ex_name;

		if (!move_uploaded_file($_FILES[$_files_name]["tmp_name"], $save_path . $save_file_name)) {
			echo "CANNOT MOVE {$_FILES[$_files_name]['name']}" . PHP_EOL;
			exit;
		}
		tep_db_query('update ' . TABLE_ORDERS_PRODUCTS . ' set customer_invoice_files=CONCAT("' . $save_file_name . '", ";", customer_invoice_files ) WHERE orders_products_id="' . $orders_products_id . '" ');
		return true;
	} else {
		//删除不适合的文件
		@unlink($_FILES[$_files_name]['tmp_name']);
	}
	return false;
}
/**
 * 记录紧急联系电话更新历史
 * @param int $orders_id 订单号
 * @param string $new_cellphone 新电话
 * @param string $old_cellphone 旧电话
 * @param int $admin_id 操作员id
 */
function tep_update_customers_cellphone_history($orders_id, $new_cellphone, $old_cellphone, $admin_id=0){
	if(trim($new_cellphone)!=trim($old_cellphone)){
		$array = array(
				'orders_id'=>(int)$orders_id,
				'new_number'=>tep_db_prepare_input($new_cellphone),
				'old_number'=>tep_db_prepare_input($old_cellphone),
				'add_date'=>date('Y-m-d H:i:s'),
				'admin_id'=>(int)$admin_id
		);
		tep_db_perform('orders_customers_cellphone_history', $array);
		//联系电话修改后提醒主管OP等
		tep_set_order_up_no_change_price($orders_id, '1', '联系电话修改');
		return true;
	}
	return false;
}
/**
 * 取得客户紧急联系电话更新历史
 * @param int $orders_id 订单号
 * @return array
 */
function tep_get_customers_cellphone_history($orders_id){
	$data = array();	
	$sql = tep_db_query('SELECT * FROM orders_customers_cellphone_history where orders_id="'.(int)$orders_id.'" order by orders_customers_cellphone_history_id DESC');
	while($rows = tep_db_fetch_array($sql)){
		$data[] = $rows;
	}
	return $data;
}
/**
 * 设置或取消 已付款(含部分)订单更新（参团日期、航班、乘车点、客人姓名、性别、参团人数、房间数、联系电话任意一项有更改）提醒
 * 以下条件必须同时满足才设置：
 * 1、有发送地接历史记录的
 * 2、订单金额没有发生变化
 * 3、订单内容有更新：参团日期、航班、乘车点、客人姓名、性别、参团人数、房间数、联系电话任意一项有更改。
 * @param int $orders_id 订单号
 */
function tep_set_order_up_no_change_price($orders_id,$value=1,$update_explain=''){
	global $skip_set_order_up_no_change_price;
	if($skip_set_order_up_no_change_price === true){
		return false;
	}
		
	if(tep_db_get_field_value('orders_paid', TABLE_ORDERS, 'orders_id = "'.(int)$orders_id.'" ') > 0){
		if($value=='1' && !tep_db_get_field_value('popsh.popc_id', TABLE_ORDERS_PRODUCTS.' op, '.TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY.' popsh', ' popsh.orders_products_id=op.orders_products_id and op.orders_id = "'.(int)$orders_id.'" ') ){	//如果无发地接记录就不用设置
			return 0;
		}
		tep_db_query('update ' . TABLE_ORDERS . ' set order_up_no_change_status="'.$value.'" WHERE orders_id = "'.(int)$orders_id.'" ');
		$n = tep_db_affected_rows();
		//加完后要记录历史记录orders_up_no_change_status_history
		tep_db_query('INSERT INTO `orders_up_no_change_status_history` (`orders_id` ,`order_up_no_change_status` ,`admin_id` ,`add_time` ,`update_explain`) VALUES ("'.(int)$orders_id.'", "'.(int)$value.'", "'.$_SESSION['login_id'].'", "'.date('Y-m-d H:i:s').'", "'.tep_db_input(tep_db_prepare_input($update_explain)).'");');
		return $n;
	}
}
?>