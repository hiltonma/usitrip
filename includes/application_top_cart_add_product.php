<?php

/**
 * 该文件模块响应action=add_product动作,将商品添加到购物车
 * vincent
 */
if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {
	//取得所有属于邮轮团的产品属性ID
    $cruisesOptionIds = getAllCruisesOptionIds();

    $productSql = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$HTTP_POST_VARS['products_id'] . "' ");
    $product_info = tep_db_fetch_array($productSql);			
    
    //transfer-service {
    $product_transfer_info = $productInfo;			
    //}
    //amit added to serversite validation for departure date{
    // vincent检查出发日期的有效性 {
    $is_start_date_required=is_tour_start_date_required((int)$HTTP_GET_VARS['products_id']);
    if($is_start_date_required==true && tep_check_product_is_hotel((int)$HTTP_GET_VARS['products_id'])==0  && tep_check_product_is_transfer((int)$HTTP_GET_VARS['products_id'] ,$product_transfer_info )==0){
        if ($HTTP_POST_VARS['availabletourdate'] == "") {
            if ($HTTP_POST_VARS['ajax'] == 'true') {
                //echo '[ERROR]'.general_to_ajax_string('Plx select date').'[/ERROR]';
                //exit();
            } else {
                $messageStack->add_session('product_info', TEXT_SELECT_VALID_DEPARTURE_DATE, 'error');
                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&error_departure_date=true'));
                exit();
            }
        } else {//检查出发时间是否在今天之前
            $tmp_dp_date = substr($HTTP_POST_VARS['availabletourdate'], 0, 10);
            $currect_sys_date = date('Y-m-d');
            if (@tep_get_compareDates($tmp_dp_date, $currect_sys_date) == "invalid") {
                if ($HTTP_POST_VARS['ajax'] == 'true') {
                    echo '[ERROR]' . 'Date format error!' . '[/ERROR]';exit();
                } else {
                    $messageStack->add_session('product_info', TEXT_SELECT_VALID_DEPARTURE_DATE, 'error');
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&error_departure_date=true'));
                    exit();
                }
            }
            //priority mail date
            $extra_values = '';
            if(isset($HTTP_POST_VARS['priority_mail_ticket_needed_date']) && $HTTP_POST_VARS['priority_mail_ticket_needed_date'] != ''){
            
            $current_sys_date = date('Y-m-d');
            $valid_ticket_needed_date = strtotime($current_sys_date) + (7*24*60*60);
            $priority_mail_db_date = tep_get_date_db($HTTP_POST_VARS['priority_mail_ticket_needed_date']);
            if((strtotime($priority_mail_db_date) < $valid_ticket_needed_date) || (strtotime($priority_mail_db_date) > strtotime($tmp_dp_date)) ){
                if($HTTP_POST_VARS['ajax']=='true'){
                    echo '[ERROR]' . general_to_ajax_string(ERROR_CHECK_PRIORITY_MAIL_DATE) . '[/ERROR]';exit();
                }else{
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_ticket_needed_date=true'));
                    exit();
                }
            }else{
                $extra_values = tep_get_cart_add_update_extra_values('priority_mail_ticket_needed_date', $HTTP_POST_VARS['priority_mail_ticket_needed_date'], $extra_values);										
            }
            
            }
            if(isset($HTTP_POST_VARS['priority_mail_delivery_address']) && $HTTP_POST_VARS['priority_mail_delivery_address'] != ''){
                $HTTP_POST_VARS['priority_mail_delivery_address'] = ajax_to_general_string($HTTP_POST_VARS['priority_mail_delivery_address']);
                $extra_values = tep_get_cart_add_update_extra_values('priority_mail_delivery_address', $HTTP_POST_VARS['priority_mail_delivery_address'], $extra_values);										
            }
            if(isset($HTTP_POST_VARS['priority_mail_recipient_name']) && $HTTP_POST_VARS['priority_mail_recipient_name'] != ''){
                $HTTP_POST_VARS['priority_mail_recipient_name'] = ajax_to_general_string($HTTP_POST_VARS['priority_mail_recipient_name']);
                $extra_values = tep_get_cart_add_update_extra_values('priority_mail_recipient_name', $HTTP_POST_VARS['priority_mail_recipient_name'], $extra_values);										
            }
            //priority mail date
            
        }
    }
    //}检查出发日期的有效性 end
    //vincent add hotel-extension  检查酒店延住checkin 或者checkout时间的有效性{
    if(tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date'])){
        $HTTP_POST_VARS['late_hotel_checkout_date'] = substr($HTTP_POST_VARS['late_hotel_checkout_date'],0,10);
        $temp_late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
        $temp_late_hotel_checkin_date = strtotime($temp_late_hotel_checkin_date_split[2].'-'.$temp_late_hotel_checkin_date_split[0].'-'.$temp_late_hotel_checkin_date_split[1]);
        $temp_late_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkout_date']);
        $temp_late_hotel_checkout_date = strtotime($temp_late_hotel_checkout_date_split[2].'-'.$temp_late_hotel_checkout_date_split[0].'-'.$temp_late_hotel_checkout_date_split[1]);
        if($temp_late_hotel_checkin_date > $temp_late_hotel_checkout_date){
            tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_he_checkout_date=true'));
            exit();
        }
    }else if(tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date'])){
        $temp_late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
        $temp_late_hotel_checkin_date = strtotime($temp_late_hotel_checkin_date_split[2].'-'.$temp_late_hotel_checkin_date_split[0].'-'.$temp_late_hotel_checkin_date_split[1]);
        $temp_late_hotel_checkout_date = $temp_late_hotel_checkin_date + (24*60*60);
        $HTTP_POST_VARS['late_hotel_checkout_date'] = date("m/d/Y", $temp_late_hotel_checkout_date);
    }
    if(tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'])){
        $HTTP_POST_VARS['early_hotel_checkin_date'] = date('m/d/Y', strtotime($HTTP_POST_VARS['early_hotel_checkin_date']));
        $temp_early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date']);
        $temp_early_hotel_checkin_date = strtotime($temp_early_hotel_checkin_date_split[2].'-'.$temp_early_hotel_checkin_date_split[0].'-'.$temp_early_hotel_checkin_date_split[1]);
        
		$HTTP_POST_VARS['early_hotel_checkout_date'] = date('m/d/Y', strtotime($HTTP_POST_VARS['early_hotel_checkout_date']));
        $temp_early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date']);
        $temp_early_hotel_checkout_date = strtotime($temp_early_hotel_checkout_date_split[2].'-'.$temp_early_hotel_checkout_date_split[0].'-'.$temp_early_hotel_checkout_date_split[1]);
        if($HTTP_POST_VARS['early_hotel_checkout_date']==''){
            $temp_early_hotel_checkout_date = $temp_early_hotel_checkin_date + (24*60*60);
            $HTTP_POST_VARS['early_hotel_checkout_date'] = date("m/d/Y", $temp_early_hotel_checkout_date);
        }
        if($temp_early_hotel_checkin_date > $temp_early_hotel_checkout_date){			
            if ($HTTP_POST_VARS['ajax'] == 'true') {
                echo '[ERROR]'.general_to_ajax_string(db_to_html('请设置正确的入住日期和离店日期！')).'[/ERROR]';exit();
            }else{
                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_he_checkin_date=true'));
                exit();
            }
        }
        //}
        
        //{ copy form t4f 
        //check if product sold out
        $hotel_check_not_sold_date = list_all_dates_btwn_two_dates_in_array(date('Y-m-d',strtotime($HTTP_POST_VARS['early_hotel_checkin_date'])),date('Y-m-d',strtotime($HTTP_POST_VARS['early_hotel_checkout_date'])));									
        $qry_sold_dates="SELECT products_id, products_soldout_date FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." sd WHERE sd.products_id='".(int)$HTTP_POST_VARS['products_id']."' UNION SELECT products_id, departure_date products_soldout_date FROM  products_remaining_seats prs WHERE prs.remaining_seats_num=0 AND prs.products_id='".(int)$HTTP_POST_VARS['products_id']."'";
        $res_sold_dates=tep_db_query($qry_sold_dates);
        while($row_sold_dates=tep_db_fetch_array($res_sold_dates)){		
            if(in_array($row_sold_dates['products_soldout_date'],$hotel_check_not_sold_date)){	
                if ($HTTP_POST_VARS['ajax'] == 'true') {
                    echo '[ERROR]'.general_to_ajax_string(db_to_html('你设置的入住日期和离店日期包含已售完的日期！无法进行预定')).'[/ERROR]';exit();
                }else{
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_sold_date_selected=true'));	
                    exit();
                }
            }
        }
        //}
    }
    
    //transfer-service {
        //接送服务检验数据有效性		
        if(is_numeric($HTTP_POST_VARS['transfer_products_id']) && tep_check_product_is_transfer((int)$HTTP_POST_VARS['transfer_products_id']) == '1' && (is_numeric($HTTP_POST_VARS['PickupId1'])||is_numeric($HTTP_POST_VARS['PickupId2']))){
            $id = $HTTP_POST_VARS['transfer_products_id'];
            $transfer_error_msg = tep_transfer_validate($id , $HTTP_POST_VARS);
            if($transfer_error_msg != ''){
                if ($HTTP_POST_VARS['ajax'] == 'true') {
                    echo '[JS]popAlert("'.general_to_ajax_string(db_to_html($transfer_error_msg)).'");[/JS]';exit();
                }else{
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_transfer_validate=true'));
                    exit();
                }
            }
        }
    //}hotel-extendsion end	
    //}amit added to serversite validation for departure date
    /*tff取消 检查座位数 nirav added for remaining seats---start{
    $totalinroom_adult=0;
    if($HTTP_POST_VARS['numberOfRooms']>0){
        for($i=0;$i<$HTTP_POST_VARS['numberOfRooms'];$i++){
            $totalinroom_adult+=($HTTP_POST_VARS['room-'.$i.'-adult-total']+$HTTP_POST_VARS['room-'.$i.'-child-total']);
        }
    }else{
        $totalinroom_adult=($HTTP_POST_VARS['room-0-adult-total']+$HTTP_POST_VARS['room-0-child-total']);
    }
    if(is_seats_availavle((int)$HTTP_POST_VARS['products_id'], substr($HTTP_POST_VARS['availabletourdate'],0,10), $totalinroom_adult)==false && $is_gift_certificate_tour==false && $is_start_date_required==true){echo "Here";
    $run_available_seats = get_remaining_seats((int)$HTTP_POST_VARS['products_id']);
    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&err_remainig_seats_available=true&avail_seats_var='.$run_available_seats[substr($HTTP_POST_VARS['availabletourdate'],0,10)]));exit();
    }
    //} nirav added for remaining seats---end */
    
    if ($HTTP_POST_VARS['_1_H_hot3'] != "") {        
        $HTTP_POST_VARS['departurelocation'] = tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot3']) . '::::' . tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot2']) . ' ' . tep_db_prepare_input($HTTP_POST_VARS['_1_H_address']);
        if ($HTTP_POST_VARS['ajax'] == 'true') {           
            $HTTP_POST_VARS['departurelocation'] = ajax_to_general_string($HTTP_POST_VARS['departurelocation']);
        }
        //echo $HTTP_POST_VARS['departurelocation'];exit;// $HTTP_POST_VARS['departurelocation'] =  $HTTP_POST_VARS['_1_H_hot3'].'::::'.' '.$HTTP_POST_VARS['_1_H_address'];
    } elseif (isset($HTTP_POST_VARS['_1_H_hot3'])) {        
        $HTTP_POST_VARS['departurelocation'] = ajax_to_general_string($HTTP_POST_VARS['departurelocation']);
        
        $errorhhot3 = ERROR_SEL_SHUTTLE;
        if ($HTTP_POST_VARS['ajax'] == 'true') {
            //echo '[ERROR]'.iconv(CHARSET,'utf-8'.'//IGNORE',$errorhhot3).'[/ERROR]';
            //exit();
        } else {
            $messageStack->add_session('product_info', ERROR_SEL_SHUTTLE, 'error');
            tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&errorhhot3=' . $errorhhot3 . ''));
            exit();
        }
    }else{        
        //vincent fixed20110803{ 
        if ($HTTP_POST_VARS['ajax'] == 'true') {
            $HTTP_POST_VARS['departurelocation'] = ajax_to_general_string($HTTP_POST_VARS['departurelocation']);
        }
        //}
    }    
    //$product_hotel_price = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int) $HTTP_POST_VARS['products_id'] . "' ");
    $product_hotel_result = $product_info;	
    
    /*// Howard added new group buy start {
    $is_new_group_buy = 0;
    $group_buy_specials_sql = tep_db_query('SELECT * FROM `specials` WHERE products_id ="'.(int) $HTTP_POST_VARS['products_id'].'" AND status="1" AND start_date <="'.date('Y-m-d H:i:s').'" AND expires_date >"'.date('Y-m-d H:i:s').'" ');
    $group_buy_specials = tep_db_fetch_array($group_buy_specials_sql);
    if((int)$group_buy_specials['products_id']){
        if($group_buy_specials['specials_type'] =="1"){
            $tmpPurchasedNum = tep_get_product_orders_guest_count($group_buy_specials['products_id'],$group_buy_specials['start_date'],$group_buy_specials['expires_date']);
            $tmpBalanceNum = max(0,(int)($group_buy_specials['specials_max_buy_num']-$tmpPurchasedNum));
        }
        //0为普通特价，1为限量团购，2为限时团购
		if(($group_buy_specials['specials_type'] =="1" && (int)$tmpBalanceNum) || $group_buy_specials['specials_type']=="2" || $group_buy_specials['specials_type']=="0"){
            if($group_buy_specials['specials_type'] =="1" || $group_buy_specials['specials_type']=="2"){
				$is_new_group_buy = 1;
			}
			if((int)$group_buy_specials['specials_new_products_single']) $product_hotel_result['products_single'] = $group_buy_specials['specials_new_products_single'];
            if((int)$group_buy_specials['specials_new_products_single_pu']) $product_hotel_result['products_single_pu'] = $group_buy_specials['specials_new_products_single_pu'];
            if((int)$group_buy_specials['specials_new_products_double']) $product_hotel_result['products_double'] = $group_buy_specials['specials_new_products_double'];
            if((int)$group_buy_specials['specials_new_products_triple']) $product_hotel_result['products_triple'] = $group_buy_specials['specials_new_products_triple'];
            if((int)$group_buy_specials['specials_new_products_quadr'])	$product_hotel_result['products_quadr'] = $group_buy_specials['specials_new_products_quadr'];
            if((int)$group_buy_specials['specials_new_products_kids'])	$product_hotel_result['products_kids'] = $group_buy_specials['specials_new_products_kids'];
        }
    }
    // Howard added new group buy end }*/
	//Howard 取得通过特价和团购检查后的最终标准价
	tep_get_final_price_from_specials($HTTP_POST_VARS['products_id'], $product_hotel_result, $is_new_group_buy);			
    //amit modified to make sure price on usd {
    $tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int) $HTTP_POST_VARS['products_id']);//获取代理商结算货币
    if(tep_check_product_is_hotel((int)$products_id)==0){
        //行程价格计算
        if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
            $product_hotel_result['products_single'] = tep_get_tour_price_in_usd($product_hotel_result['products_single'], $tour_agency_opr_currency);
            $product_hotel_result['products_single_pu'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_pu'], $tour_agency_opr_currency);
            $product_hotel_result['products_double'] = tep_get_tour_price_in_usd($product_hotel_result['products_double'], $tour_agency_opr_currency);
            $product_hotel_result['products_triple'] = tep_get_tour_price_in_usd($product_hotel_result['products_triple'], $tour_agency_opr_currency);
            $product_hotel_result['products_quadr'] = tep_get_tour_price_in_usd($product_hotel_result['products_quadr'], $tour_agency_opr_currency);
            $product_hotel_result['products_kids'] = tep_get_tour_price_in_usd($product_hotel_result['products_kids'], $tour_agency_opr_currency);

			$product_hotel_result['products_single_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_cost'], $tour_agency_opr_currency);
			$product_hotel_result['products_single_pu_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_pu_cost'], $tour_agency_opr_currency);
			$product_hotel_result['products_double_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_double_cost'], $tour_agency_opr_currency);
			$product_hotel_result['products_triple_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_triple_cost'], $tour_agency_opr_currency);
			$product_hotel_result['products_quadr_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_quadr_cost'], $tour_agency_opr_currency);
			$product_hotel_result['products_kids_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_kids_cost'], $tour_agency_opr_currency);
		}
		//}amit modified to make sure price on usd
		//howard added for single pair up {
		$single_price_tmp = $product_hotel_result['products_single'];
		if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $product_hotel_result['products_single_pu']) {
			$single_price_tmp = $product_hotel_result['products_single_pu'];
		}
		$a_price = array();//$a 变量是房间价格
		$a_price[1] = $single_price_tmp; //单人或单人配房/人
		$a_price[2] = $product_hotel_result['products_double']; //双人间/人
		$a_price[3] = $product_hotel_result['products_triple']; //3人间/人
		$a_price[4] = $product_hotel_result['products_quadr']; //4人间/人
		$e = $product_hotel_result['products_kids']; //儿童/人
		
		$single_cost_tmp = $product_hotel_result['products_single_cost'];
		if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $product_hotel_result['products_single_pu_cost']) {
			$single_cost_tmp = $product_hotel_result['products_single_pu_cost'];
		}
		$a_cost[1] = $single_cost_tmp; //single or single pair up
		$a_cost[2] = $product_hotel_result['products_double_cost']; //double
		$a_cost[3] = $product_hotel_result['products_triple_cost']; //triple
		$a_cost[4] = $product_hotel_result['products_quadr_cost']; //quadr
		$e_cost = $product_hotel_result['products_kids_cost']; //child Kid
		//}howard added for single cost pair up
		
		$get_reg_date_price_array = explode('!!!', $HTTP_POST_VARS['availabletourdate']);
		$HTTP_POST_VARS['availabletourdate'] = $get_reg_date_price_array[0];
		
		/* Price Displaying - standard price for different reg/irreg sections - start {*/ 
		
		$check_standard_price_dates = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and products_id ='".(int)$products_id."' ");
		if(tep_db_num_rows($check_standard_price_dates)>0){
			$row_standard_price_dates = tep_db_fetch_array($check_standard_price_dates);
			$operate_start_date = $row_standard_price_dates['operate_start_date'];
			$operate_end_date = $row_standard_price_dates['operate_end_date'];
		}else{
			$check_standard_price_dates1 = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and products_id ='".(int)$products_id."' ");
			$row_standard_price_dates1 = tep_db_fetch_array($check_standard_price_dates1);
			$operate_start_date = $row_standard_price_dates1['operate_start_date'];
			$operate_end_date = $row_standard_price_dates1['operate_end_date'];
		}
		
		$check_section_standard_price_query = "select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where operate_start_date ='".$operate_start_date."' and operate_end_date = '".$operate_end_date."' and products_id ='".(int)$products_id."' and products_single > 0 ";
		$check_section_standard_price = tep_db_query($check_section_standard_price_query);
		if(tep_db_num_rows($check_section_standard_price)>0){
			$row_section_standard_price = tep_db_fetch_array($check_section_standard_price);
			 
			 if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
				$row_section_standard_price['products_single'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single'],$tour_agency_opr_currency);
				$row_section_standard_price['products_single_pu'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_pu'],$tour_agency_opr_currency);
				$row_section_standard_price['products_double'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double'],$tour_agency_opr_currency);
				$row_section_standard_price['products_triple'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple'],$tour_agency_opr_currency);
				$row_section_standard_price['products_quadr'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr'],$tour_agency_opr_currency);
				$row_section_standard_price['products_kids'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids'],$tour_agency_opr_currency);

				$row_section_standard_price['products_single_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_cost'],$tour_agency_opr_currency);
				$row_section_standard_price['products_single_pu_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_pu_cost'],$tour_agency_opr_currency);
				$row_section_standard_price['products_double_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double_cost'],$tour_agency_opr_currency);
				$row_section_standard_price['products_triple_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple_cost'],$tour_agency_opr_currency);
				$row_section_standard_price['products_quadr_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr_cost'],$tour_agency_opr_currency);
				$row_section_standard_price['products_kids_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids_cost'],$tour_agency_opr_currency);
			}
			 $a_price[1] = $row_section_standard_price['products_single']; //single
			 if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $row_section_standard_price['products_single_pu']) {
				$a_price[1] = $row_section_standard_price['products_single_pu'];
			}
			 $a_price[2] = $row_section_standard_price['products_double']; //double
			 $a_price[3] = $row_section_standard_price['products_triple']; //triple
			 $a_price[4] = $row_section_standard_price['products_quadr']; //quadr
			 $e = $row_section_standard_price['products_kids'];
			 
			 $a_cost[1] = $row_section_standard_price['products_single_cost']; //single
			 if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $row_section_standard_price['products_single_pu_cost']) {
				$a_cost[1] = $row_section_standard_price['products_single_pu_cost'];
			}
			 $a_cost[2] = $row_section_standard_price['products_double_cost']; //double
			 $a_cost[3] = $row_section_standard_price['products_triple_cost']; //triple
			 $a_cost[4] = $row_section_standard_price['products_quadr_cost']; //quadr
			 $e_cost = $row_section_standard_price['products_kids_cost']; //child Kid
		}
		 
		/* Price Displaying - standard price for different reg/irreg sections - end } */ 

		//amit added to check if special price is available for specific date start {
		$special_dt_chk = 0;
		$check_specip_pride_date_select = "select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where available_date ='" . $tmp_dp_date . "' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0) and products_id ='" . (int) $HTTP_POST_VARS['products_id'] . "' ";
		$check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
		while ($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)) {
			//amit modified to make sure price on usd
			if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
				$check_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_single_pu'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_pu'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'], $tour_agency_opr_currency);

				$check_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_cost'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_single_pu_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_pu_cost'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double_cost'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple_cost'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids_cost'], $tour_agency_opr_currency);
				$check_specip_pride_date_row['extra_charge_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['extra_charge_cost'], $tour_agency_opr_currency);
			}
			//amit modified to make sure price on usd
			//howard added for single pair up
			$single_price_tmp = $check_specip_pride_date_row['spe_single'];
			if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $check_specip_pride_date_row['spe_single_pu']) {
				$single_price_tmp = $check_specip_pride_date_row['spe_single_pu'];
			}
			$a_price[1] = $single_price_tmp; //single or single pair up
			$a_price[2] = $check_specip_pride_date_row['spe_double']; //double
			$a_price[3] = $check_specip_pride_date_row['spe_triple']; //triple
			$a_price[4] = $check_specip_pride_date_row['spe_quadruple']; //quadr
			$e = $check_specip_pride_date_row['spe_kids']; //child Kid
			//howard added for single pair up
			$single_cost_tmp = $check_specip_pride_date_row['spe_single_cost'];
			if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $check_specip_pride_date_row['spe_single_pu_cost']) {
				$single_cost_tmp = $check_specip_pride_date_row['spe_single_pu_cost'];
			}
			$a_cost[1] = $single_cost_tmp; //single or single pair up
			$a_cost[2] = $check_specip_pride_date_row['spe_double_cost']; //double
			$a_cost[3] = $check_specip_pride_date_row['spe_triple_cost']; //triple
			$a_cost[4] = $check_specip_pride_date_row['spe_quadruple_cost']; //quadr
			$e_cost = $check_specip_pride_date_row['spe_kids_cost']; //child Kid
			$date_price_cost = $check_specip_pride_date_row['extra_charge_cost'];
			$special_dt_chk = 1;
		}
		//}amit added to check if special price is available specific date end
		//amit added to check if regular special price available start {

        if ($special_dt_chk == 0 && $get_reg_date_price_array[1] != '') {
            $check_reg_specip_pride_date_select = "select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_start_day_id ='" . $get_reg_date_price_array[1] . "' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='" . (int) $HTTP_POST_VARS['products_id'] . "' ";
            $check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
            while ($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)) {

                if ($check_reg_specip_pride_date_row['extra_charge'] > 0) {
                    //amit modified to make sure price on usd
                    if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
                        $check_reg_specip_pride_date_row['extra_charge_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['extra_charge_cost'], $tour_agency_opr_currency);
                    }
                    //amit modified to make sure price on usd
                    $date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
                } else {
                    //amit modified to make sure price on usd
                    if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
                        $check_reg_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_single_pu'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_pu'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'], $tour_agency_opr_currency);

                        $check_reg_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_single_pu_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_pu_cost'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'], $tour_agency_opr_currency);
                        $check_reg_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'], $tour_agency_opr_currency);
                    }
                    //amit modified to make sure price on usd
                    //howard added for single pair up
                    $single_price_tmp = $check_reg_specip_pride_date_row['spe_single'];
                    if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $check_reg_specip_pride_date_row['spe_single_pu']) {
                        $single_price_tmp = $check_reg_specip_pride_date_row['spe_single_pu'];
                    }
                    $a_price[1] = $single_price_tmp; //single or single pair up
                    $a_price[2] = $check_reg_specip_pride_date_row['spe_double']; //double
                    $a_price[3] = $check_reg_specip_pride_date_row['spe_triple']; //triple
                    $a_price[4] = $check_reg_specip_pride_date_row['spe_quadruple']; //quadr
                    $e = $check_reg_specip_pride_date_row['spe_kids']; //child Kid

                    $single_cost_tmp = $check_reg_specip_pride_date_row['spe_single_cost'];
                    if ((int) $HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int) $check_reg_specip_pride_date_row['spe_single_pu_cost']) {
                        $single_cost_tmp = $check_reg_specip_pride_date_row['spe_single_pu_cost'];
                    }
                    $a_cost[1] = $check_reg_specip_pride_date_row['spe_single_cost']; //single or single pair up
                    $a_cost[2] = $check_reg_specip_pride_date_row['spe_double_cost']; //double
                    $a_cost[3] = $check_reg_specip_pride_date_row['spe_triple_cost']; //triple
                    $a_cost[4] = $check_reg_specip_pride_date_row['spe_quadruple_cost']; //quadr
                    $e_cost = $check_reg_specip_pride_date_row['spe_kids_cost']; //child Kid
                }

                $special_dt_chk = 1;
            }
        }
        //}amit added to check if regular special price avalilable end
    }else{
        //酒店产品
        $a_price[1] = 0; //single
        $a_price[2] = 0; //double
        $a_price[3] = 0; //triple
        $a_price[4] = 0; //quadr
        $e = 0; //child Kid
            
        $a_cost[1] = 0; //single
        $a_cost[2] = 0; //double
        $a_cost[3] = 0; //triple
        $a_cost[4] = 0; //quadr
        $e_cost = 0; //child Kid
    }
    //hotel-extension
    /* Hotel Extensions - Early/Late check-in/out - start */
    $hotel_extension_price = array(0,0,0,0,0);	
    $hotel_extension_price_cost =array(0,0,0,0,0);
    $early_hotel_extension_price = array(0,0,0,0,0);	
    $early_hotel_extension_price_cost= array(0,0,0,0,0);
    $late_hotel_extension_price = array(0,0,0,0,0);	
    $late_hotel_extension_price_cost = array(0,0,0,0,0);	
    $hotel_extension_info = "";
    
    if((isset($HTTP_POST_VARS['early_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date']) && isset($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_arrival_hotels'])) || (isset($HTTP_POST_VARS['late_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date']) && isset($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['staying_late_hotels'])) ){
		//提前延住价格计算{
        if(isset($HTTP_POST_VARS['early_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date']) && isset($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'])  && tep_not_null($HTTP_POST_VARS['early_arrival_hotels'])){
            $early_hotel_id = $HTTP_POST_VARS['early_arrival_hotels'];
            $tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$early_hotel_id); //酒店结算货币
            //get default standard prices of hotel - start {
            $hotel_price_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$early_hotel_id . "' ");
            $hotel_result = tep_db_fetch_array($hotel_price_query);
            //amit modified to make sure price on usd ,这部分貌似冗余-vincent{ no function ?
            if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
                $hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
                $hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
                $hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
                $hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
                $hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
                $hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
                $hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
                $hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
                $hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
                $hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
            }
            //}amit modified to make sure price on usd
            //get default standard prices of hotel - end}
            $early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date']);
            $early_hotel_checkin_date = $early_hotel_checkin_date_split[2].'-'.$early_hotel_checkin_date_split[0].'-'.$early_hotel_checkin_date_split[1];
            $early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date']);
            $early_hotel_checkout_date = $early_hotel_checkout_date_split[2].'-'.$early_hotel_checkout_date_split[0].'-'.$early_hotel_checkout_date_split[1];

            $early_total_dates_price = array();
            $loop_start = strtotime($early_hotel_checkin_date);
            $loop_end = strtotime($early_hotel_checkout_date) - (24*60*60);
            $loop_increment = (24*60*60);
            $total_early_stay_days = 0;
            $early_hotel_price_extra = '';
            for($d=$loop_start; $d<=$loop_end; $d=$d + $loop_increment){
                $early_arrival_date = date("Y-m-d", $d);	
                $hotel_price  = array(0,0,0,0,0);
                $hotel_price_cost =  array(0,0,0,0,0);
                //酒店基本价格{
                $hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
                $hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
                $hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
                $hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
                $hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
                $hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
                //}酒店基本价格结束
                //检查酒店是否有设置不规则时间段价格 operate_start_date< $early_arrival_date<operate_end_date{
                $hotel_standard_prices = tep_db_query("select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id ='".(int)$early_hotel_id."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$early_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$early_arrival_date."' ");
                if(tep_db_num_rows($hotel_standard_prices)>0){
                    $hotel_std_price = tep_db_fetch_array($hotel_standard_prices);
                    if(tep_not_null($hotel_std_price['products_single']))			$hotel_price[1] = $hotel_std_price['products_single'];
                    if(tep_not_null($hotel_std_price['products_double']))			$hotel_price[2] = $hotel_std_price['products_double'];
                    if(tep_not_null($hotel_std_price['products_triple']))			$hotel_price[3] = $hotel_std_price['products_triple'];
                    if(tep_not_null($hotel_std_price['products_quadr']))   			$hotel_price[4] = $hotel_std_price['products_quadr'];
                    if(tep_not_null($hotel_std_price['products_kids']))				$hotel_price[0] = $hotel_std_price['products_kids'];
                    if(tep_not_null($hotel_std_price['products_single_cost']))	$hotel_price_cost[1] = $hotel_std_price['products_single_cost'];
                    if(tep_not_null($hotel_std_price['products_double_cost']))	$hotel_price_cost[2] = $hotel_std_price['products_double_cost'];
                    if(tep_not_null($hotel_std_price['products_triple_cost']))	$hotel_price_cost[3] = $hotel_std_price['products_triple_cost'];
                    if(tep_not_null($hotel_std_price['products_quadr_cost']))	$hotel_price_cost[4] = $hotel_std_price['products_quadr_cost'];
                    if(tep_not_null($hotel_std_price['products_kids_cost']))		$hotel_price_cost[0] = $hotel_std_price['products_kids_cost'];
                }
                //}
                //检查当前日期是否在规则时间段价格中 available_date = ''{
                $check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$early_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$early_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and available_date = '' and products_id ='".(int)$early_hotel_id."' ";
                $check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
                if(tep_db_num_rows($check_reg_specip_pride_date_query)>0){
                    while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
                        if($check_reg_specip_pride_date_row['extra_charge'] > 0 && $check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($early_arrival_date))+1) ){
                            if($check_reg_specip_pride_date_row['prefix'] == '-'){ 
                                foreach($hotel_price as $key=>$price)$hotel_price[$key]= $price - $check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) $hotel_price_cost[$key]= $price - $check_reg_specip_pride_date_row['extra_charge_cost'];
                                //$early_hotel_price_extra .= '(Special price '.date("D", $d).' - $'.$check_reg_specip_pride_date_row['extra_charge'].')';
                            }else{
                                foreach($hotel_price as $key=>$price) 	$hotel_price[$key]= $price + $check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) 	$hotel_price_cost[$key]= $price +$check_reg_specip_pride_date_row['extra_charge_cost'];
                            }
                            //$date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
                        }else if($check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($early_arrival_date))+1) ){
                            //$early_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' $'.$hotel_price[1].')';								
                            $hotel_price[1] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'],$tour_agency_opr_currency); //single									
                            $hotel_price[2] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency); 
                            $hotel_price[3] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
                            $hotel_price[4] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency); 
                            $hotel_price[0] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency); 
                            $hotel_price_cost[1] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[2] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[3] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[4] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[0] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency); 
                        }
                        $special_dt_chk = 1;
                    }
                }
                //}检查当前日期是否在规则时间段价格中
                //检查优惠规则 available_date =$early_arrival_date{
                $check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$early_arrival_date."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$early_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$early_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$early_hotel_id."' ";
                $check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
                if(tep_db_num_rows($check_specip_pride_date_query)>0){
                    while($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)){
                        //amit modified to make sure price on usd
                        if($check_specip_pride_date_row['extra_charge'] > 0){
                            if($check_reg_specip_pride_date_row['prefix'] == '-'){ 
                                foreach($hotel_price as $key=>$price)$hotel_price[$key]= $price - $check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) $hotel_price_cost[$key]= $price - $check_reg_specip_pride_date_row['extra_charge_cost'];
                                //$early_hotel_price_extra .= '(Special price '.date("D", $d).' - $'.$check_reg_specip_pride_date_row['extra_charge'].')';
                            }else{
                                foreach($hotel_price as $key=>$price) 	$hotel_price[$key]= $price + $check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) 	$hotel_price_cost[$key]= $price +$check_reg_specip_pride_date_row['extra_charge_cost'];
                            }
                        }else{
                            $hotel_price[1] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
                            $hotel_price[2] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
                            $hotel_price[3] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
                            $hotel_price[4] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
                            $hotel_price[0] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
                            $hotel_price_cost[1] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[2] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[3] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[4] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[0] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
                        }
                        $special_dt_chk = 1;
                    }
                }
                //} 检查优惠规则
                $early_hotel_extension_price[1] = $early_hotel_extension_price[1] + $hotel_price[1]; //single
                $early_hotel_extension_price[2] = $early_hotel_extension_price[2] + $hotel_price[2]; //double
                $early_hotel_extension_price[3] = $early_hotel_extension_price[3] + $hotel_price[3]; //triple
                $early_hotel_extension_price[4] = $early_hotel_extension_price[4] + $hotel_price[4]; //quadr
                $early_hotel_extension_price[0] = $early_hotel_extension_price[0] + $hotel_price[0]; //kids					
                $early_hotel_extension_price_cost[1] = $early_hotel_extension_price_cost[1] + $hotel_price_cost[1]; //single
                $early_hotel_extension_price_cost[2] = $early_hotel_extension_price_cost[2] + $hotel_price_cost[2]; //double
                $early_hotel_extension_price_cost[3] = $early_hotel_extension_price_cost[3] + $hotel_price_cost[3]; //triple
                $early_hotel_extension_price_cost[4] = $early_hotel_extension_price_cost[4] + $hotel_price_cost[4]; //quadr
                $early_hotel_extension_price_cost[0] = $early_hotel_extension_price_cost[0] + $hotel_price_cost[0]; //kids
                
                $total_early_stay_days++;
                $early_total_dates_price[] = array('id'=>date("m/d/y", $d), 'text'=>$hotel_price[1]);
            }			
        } //提前延住价格计算}
        
        //延后延住价格计算{
        if(isset($HTTP_POST_VARS['late_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date']) && isset($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['staying_late_hotels'])){
            $late_hotel_id = $HTTP_POST_VARS['staying_late_hotels'];
            $tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$early_hotel_id);
            //get default standard prices of hotel - start
            $hotel_price_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$late_hotel_id . "' ");
            $hotel_result = tep_db_fetch_array($hotel_price_query);
            //amit modified to make sure price on usd
            if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
                $hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
                $hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
                $hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
                $hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
                $hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
                $hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
            }
            //amit modified to make sure price on usd
            //get default standard prices of hotel - end
            $late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
            $late_hotel_checkin_date = $late_hotel_checkin_date_split[2].'-'.$late_hotel_checkin_date_split[0].'-'.$late_hotel_checkin_date_split[1];
            $late_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkout_date']);
            $late_hotel_checkout_date = $late_hotel_checkout_date_split[2].'-'.$late_hotel_checkout_date_split[0].'-'.$late_hotel_checkout_date_split[1];

            $late_total_dates_price = array();
            $loop_start = strtotime($late_hotel_checkin_date) + (24*60*60);
            $loop_end = strtotime($late_hotel_checkout_date);
            $loop_increment = (24*60*60);
            $total_late_stay_days = 0;
            $late_hotel_price_extra = '';
            for($d=$loop_start; $d<=$loop_end; $d=$d + $loop_increment){
                $late_arrival_date = date("Y-m-d", $d);
                $hotel_price= array(0,0,0,0,0);
                $hotel_price_cost = array(0,0,0,0,0);
                $hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
                $hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
                $hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
                $hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
                $hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);					
                $hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
                $hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
                //get sections standard price
                $hotel_standard_prices = tep_db_query("select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id ='".(int)$late_hotel_id."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' ");
                if(tep_db_num_rows($hotel_standard_prices)>0){
                    $hotel_std_price = tep_db_fetch_array($hotel_standard_prices);
                    if(tep_not_null($hotel_std_price['products_single']))			$hotel_price[1] = $hotel_std_price['products_single'];
                    if(tep_not_null($hotel_std_price['products_double']))			$hotel_price[2] = $hotel_std_price['products_double'];
                    if(tep_not_null($hotel_std_price['products_triple']))			$hotel_price[3] = $hotel_std_price['products_triple'];
                    if(tep_not_null($hotel_std_price['products_quadr']))			$hotel_price[4] = $hotel_std_price['products_quadr'];
                    if(tep_not_null($hotel_std_price['products_kids']))				$hotel_price[0] = $hotel_std_price['products_kids'];
                    if(tep_not_null($hotel_std_price['products_single_cost']))	$hotel_price_cost[1] = $hotel_std_price['products_single_cost'];
                    if(tep_not_null($hotel_std_price['products_double_cost']))	$hotel_price_cost[2] = $hotel_std_price['products_double_cost'];
                    if(tep_not_null($hotel_std_price['products_triple_cost']))	$hotel_price_cost[3] = $hotel_std_price['products_triple_cost'];
                    if(tep_not_null($hotel_std_price['products_quadr_cost']))	$hotel_price_cost[4] = $hotel_std_price['products_quadr_cost'];
                    if(tep_not_null($hotel_std_price['products_kids_cost']))		$hotel_price_cost[0] = $hotel_std_price['products_kids_cost'];
                }
                //get sections standard price available_date = ''
                $check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and available_date = '' and products_id ='".(int)$late_hotel_id."' ";
                $check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
                if(tep_db_num_rows($check_reg_specip_pride_date_query)>0){
                    while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
                        //echo $early_arrival_date . '---' . (date("w", strtotime($late_arrival_date))+1);
                        if($check_reg_specip_pride_date_row['extra_charge'] > 0 && $check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($late_arrival_date))+1) ){
                            if($check_reg_specip_pride_date_row['prefix'] == '-'){
                                foreach($hotel_price as $key=>$price) $hotel_price [$key] = $price - $check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) $hotel_price_cost [$key] = $price - $check_reg_specip_pride_date_row['extra_charge_cost'];
                            }else{
                                foreach($hotel_price as $key=>$price) $hotel_price [$key] = $price +$check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) $hotel_price_cost [$key] = $price + $check_reg_specip_pride_date_row['extra_charge_cost'];
                            }
                            //$date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
                        }else if($check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($late_arrival_date))+1) ){
                            //amit modified to make sure price on usd
                            $hotel_price[1] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);							
                            $hotel_price[2] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);							
                            $hotel_price[3] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);							
                            $hotel_price[4] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
                            $hotel_price[0] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);							
                            $hotel_price_cost[1] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);							
                            $hotel_price_cost[2] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);							
                            $hotel_price_cost[3] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);						
                            $hotel_price_cost[4] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[0] =tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
                            //amit modified to make sure price on usd
                        }
                        $special_dt_chk = 1;
                    }
                }
                //available_date =$late_arrival_date
                $check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$late_arrival_date."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$early_hotel_id."' ";
                $check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
                if(tep_db_num_rows($check_specip_pride_date_query)>0){
                    while($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)){						
                        if($check_specip_pride_date_row['extra_charge'] > 0){
                            if($check_reg_specip_pride_date_row['prefix'] == '-'){
                                foreach($hotel_price as $key=>$price) $hotel_price [$key] = $price - $check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) $hotel_price_cost [$key] = $price - $check_reg_specip_pride_date_row['extra_charge_cost'];
                            }else{
                                foreach($hotel_price as $key=>$price) $hotel_price [$key] = $price +$check_reg_specip_pride_date_row['extra_charge'];
                                foreach($hotel_price_cost as $key=>$price) $hotel_price_cost [$key] = $price + $check_reg_specip_pride_date_row['extra_charge_cost'];
                            }								
                        }else{
                            //amit modified to make sure price on usd
                            $hotel_price[1] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);							
                            $hotel_price[2] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);							
                            $hotel_price[3] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);							
                            $hotel_price[4] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
                            $hotel_price[0] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);							
                            $hotel_price_cost[1] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);							
                            $hotel_price_cost[2] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);							
                            $hotel_price_cost[3] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);						
                            $hotel_price_cost[4] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
                            $hotel_price_cost[0] =tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
                            //amit modified to make sure price on usd
                        }
                        $special_dt_chk = 1;
                    }
                }
                $late_hotel_extension_price[1] = $late_hotel_extension_price[1] + $hotel_price[1]; //single
                $late_hotel_extension_price[2] = $late_hotel_extension_price[2] + $hotel_price[2]; //single
                $late_hotel_extension_price[3] = $late_hotel_extension_price[3] + $hotel_price[3]; //single
                $late_hotel_extension_price[4] = $late_hotel_extension_price[4] + $hotel_price[4]; //single
                $late_hotel_extension_price[0] = $late_hotel_extension_price[0] + $hotel_price[0]; //single
                    
                $late_hotel_extension_price_cost[1] = $late_hotel_extension_price_cost[1] + $hotel_price_cost[1]; //single
                $late_hotel_extension_price_cost[2] = $late_hotel_extension_price_cost[2] + $hotel_price_cost[2]; //single
                $late_hotel_extension_price_cost[3] = $late_hotel_extension_price_cost[3] + $hotel_price_cost[3]; //single
                $late_hotel_extension_price_cost[4] = $late_hotel_extension_price_cost[4] + $hotel_price_cost[4]; //single
                $late_hotel_extension_price_cost[0] = $late_hotel_extension_price_cost[0] + $hotel_price_cost[0]; //single
                //echo $late_arrival_date.' '.$hotel_price;
                //echo '<br>';
                $total_late_stay_days++;
                $late_total_dates_price[] = array('id'=>date("m/d/y", $d), 'text'=>$hotel_price[1]);
            }
            /*
             $previous_value = 0;
             $count = 0;
             for($p=0; $p<=sizeof($late_total_dates_price); $p++){

             if($late_total_dates_price[$p]['text'] != $previous_value){
             if($p!=0){
             if($previous_date!=$previous_key){
             $late_hotel_price_extra .= '-'.$previous_key;
             }
             $late_hotel_price_extra .= ': $'.$previous_value.' @ '.$count.' Night(s) = '.number_format(($previous_value*$count), 2).'<br />';
             $count = 0;
             }
             $previous_date = $late_total_dates_price[$p]['id'];
             $late_hotel_price_extra .= ' &nbsp; '.$previous_date;

             }
             $count++;
             $previous_value = $late_total_dates_price[$p]['text'];
             $previous_key = $late_total_dates_price[$p]['id'];
             }
             */
        } 
        //延后延住价格计算结束 }

        $hotel_extension_price[1] = $early_hotel_extension_price[1] + $late_hotel_extension_price[1];
        $hotel_extension_price[2] = $early_hotel_extension_price[2] + $late_hotel_extension_price[2];
        $hotel_extension_price[3] = $early_hotel_extension_price[3] + $late_hotel_extension_price[3];
        $hotel_extension_price[4] = $early_hotel_extension_price[4] + $late_hotel_extension_price[4];
        $hotel_extension_price[0] = $early_hotel_extension_price[0] + $late_hotel_extension_price[0];

        $hotel_extension_price_cost[1] = $early_hotel_extension_price_cost[1] + $late_hotel_extension_price_cost[1];
        $hotel_extension_price_cost[2] = $early_hotel_extension_price_cost[2] + $late_hotel_extension_price_cost[2];
        $hotel_extension_price_cost[3] = $early_hotel_extension_price_cost[3] + $late_hotel_extension_price_cost[3];
        $hotel_extension_price_cost[4] = $early_hotel_extension_price_cost[4] + $late_hotel_extension_price_cost[4];
        $hotel_extension_price_cost[0] = $early_hotel_extension_price_cost[0] + $late_hotel_extension_price_cost[0];
    }
    /* Hotel Extensions - Early/Late check-in/out - end */
    //hotel-extension

    $total_no_guest_tour = 0; // Total no of guest for entering names
    
    //Room Price ===================================================================================================================================================================================================
    if ($_POST['numberOfRooms']) {//if there is rooms 有房间
        if ($_POST['numberOfRooms'] == 16 && $_POST['travel_comp'] == '1') {
            $_POST['numberOfRooms'] = 1;
        }
        /* *************************************** */
        $validation_no = $product_hotel_result['maximum_no_of_guest'];
        /*  *************************************** */
        $errormessageperson = "";		
        $total_info_room = "</br>" . TEXT_TOTAL_OF_ROOMS . "" . $_POST['numberOfRooms'];
        $early_he_total_info_room = "<br>".TEXT_TOTAL_OF_ROOMS." ".$_POST['numberOfRooms'];//hotel-extension
        $late_he_total_info_room = "<br>".TEXT_TOTAL_OF_ROOMS." ".$_POST['numberOfRooms'];//hotel-extension
        $bed_option_info = $total_room_adult_child_info = $_POST['numberOfRooms'] . "###";		
        $room_price_old = 0;//$room_price_old 是原价
        for ($i = 0; $i < $_POST['numberOfRooms']; $i++) {
            $is_buy_two_get_one = check_buy_two_get_one($products_id, ($_POST['room-' . $i . '-adult-total'] + $_POST['room-' . $i . '-child-total']), $tmp_dp_date);
            /***************************BOC: this is validation for the first room ******************************* */
            if ($_POST['room-' . $i . '-adult-total'] != '') {
                /*  **************total number of person in room **************** */
                $totalinroom[$i] = $_POST['room-' . $i . '-adult-total'];
                if ($_POST['room-' . $i . '-child-total'] != '') {
                    $totalinroom[$i] += $_POST['room-' . $i . '-child-total'];
                    
                }
                /* **************total number of person in room **************** */

                /* **************validation for more than 4 person **************** */
                if ($totalinroom[$i] > $validation_no) {
                    $errormessageperson = $validation_no;
                } else {
                    if ($totalinroom[$i] == 2) { //当前房间人数=2时，可以接收床型选项的值
                        $bed_option_info .= (int) $_POST['room-' . $i . '-bed'] . '###';
                    }
                    /* * **************total price for room no 1 **************** */
                    if ($_POST['room-' . $i . '-child-total'] == '0') {
                    	
                        $room_price[$i] = $_POST['room-' . $i . '-adult-total'] * $a_price[$_POST['room-' . $i . '-adult-total']];
                        $room_price_old += $room_price[$i];
                        //hotel-extension {
                        $he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
                        $early_he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$early_hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
                        $late_he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$late_hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
                        //}计算酒店延住						
                        if ($HTTP_POST_VARS['room-' . $i . '-adult-total'] == '2') {
                            $double_room_pre = double_room_preferences($products_id, $tmp_dp_date);//双人一房折扣团_酒店延住没有计算该部分的优惠 hotel-extension
                            if ((int) $double_room_pre) {
                                $room_price[$i] = 2 * ($a_price[2] - $double_room_pre);
                            }
                        } else {
                            //买二送1价 无小孩
                            if ((int) $is_buy_two_get_one) {								
                                if ($HTTP_POST_VARS['room-' . $i . '-adult-total'] == '3') {
                                    if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
                                        $room_price[$i] = 2 * $a_price[2] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
                                    }
                                }
                                if ($HTTP_POST_VARS['room-' . $i . '-adult-total'] == '4') {
                                    if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
                                        $room_price[$i] = 2 * $a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格4人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
                                    }
                                    if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '3') {										
                                        $room_price[$i] = 2 * ($a_price[2] + get_products_surcharge($products_id));//买二送二
                                    }
                                }
                            }
                        }
                        $room_price_cost[$i] = $_POST['room-' . $i . '-adult-total'] * $a_cost[$_POST['room-' . $i . '-adult-total']];
                        //hotel-extension {
                        $he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total']];
                        $early_he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$early_hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total']];
                        $late_he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$late_hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total']];
                        // }hotel-extension
                    } elseif ($_POST['room-' . $i . '-child-total'] != '0') {
                        if ($_POST['room-' . $i . '-adult-total'] == '1' && $_POST['room-' . $i . '-child-total'] == '1') {
                            $room_price[$i] = 2 * $a_price[2];
                            $room_price_old += $room_price[$i];
                            $room_price_cost[$i] = 2 * $a_cost[2];
                            $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2]; //房间$i大人平均价
                            $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2]; //房间$i小孩平均价
                            //hotel-extension {								
                            $he_room_price[$i] = 2*$hotel_extension_price[2];
                            $early_he_room_price[$i] = 2*$early_hotel_extension_price[2];
                            $late_he_room_price[$i] = 2*$late_hotel_extension_price[2];
                            //} hotel-extension				
                            //双人一房折扣团 大人小孩同一价 - hotel-extension未计入该优惠
                            $double_room_pre = double_room_preferences($products_id, $tmp_dp_date);
                            if ((int) $double_room_pre) {
                                $room_price[$i] = 2 * ($a_price[2] - $double_room_pre);
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $room_price[$i] / 2; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $room_price[$i] / 2; //房间$i小孩平均价
                            }
                        } elseif ($_POST['room-' . $i . '-adult-total'] == '1' && $_POST['room-' . $i . '-child-total'] == '2') {
//                             $room_price[$i] = min((2 * $a_price[2]) + $e, 3 * $a_price[3]);
//                             $room_price_old += $room_price[$i];
//                             //hotel-extension {												
//                             $he_room_price[$i] = min((2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0],3*$hotel_extension_price[3]+$hotel_extension_price[0]);									
//                             $early_he_room_price[$i] = min((2*$early_hotel_extension_price[2]) + 2*$early_hotel_extension_price[0],3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0]);
//                             $late_he_room_price[$i] = min((2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0],3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0]);
                            
                        	$room_price_option1[$i] = (2*$a_price[2]) + $e;
                        	$room_price_option2[$i] = 3*$a_price[3];
                        	$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
                        	$room_price_old += $room_price[$i];
                        	//hotel-extension {
                        	$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
                        	$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
                        	$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
                        	
                        	$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + $early_hotel_extension_price[0];
                        	$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3];
                        	$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);
//}hotel-extension
                            if ($room_price_option1[$i] <= $room_price_option2[$i]) {
                            	
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = ($a_price[2] + $e) / 2; //房间$i小孩平均价
                            } else {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i小孩平均价
                            }
                            //买二送1价 1大人 2小孩
                            if ((int) $is_buy_two_get_one) {
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
                                    $room_price[$i] = 2 * $a_price[2] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
                                }
                                $tmp_var = number_format(($room_price[$i] / 3), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            $room_price_cost[$i] = min((2 * $a_cost[2]) + $e_cost, 3 * $a_cost[3]);
                            //hotel-extension {
                            $he_room_price_cost[$i] = min((2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0],3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0]);
                            $early_he_room_price_cost[$i] = min((2*$early_hotel_extension_price_cost[2]) + 2*$early_hotel_extension_price_cost[0],3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0]);
                            $late_he_room_price_cost[$i] = min((2*$late_hotel_extension_price_cost[2]) + 2*$late_hotel_extension_price_cost[0], 3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0]);							
                            //}hotel-extension
                        } elseif ($_POST['room-' . $i . '-adult-total'] == '1' && $_POST['room-' . $i . '-child-total'] == '3') {							
                            $room_price[$i] = min((2 * $a_price[2]) + 2 * $e, 3 * $a_price[3] + $e, 4 * $a_price[4]);
                            $room_price_old += $room_price[$i];
                            //hotel-extension {
                            $he_room_price[$i] = min((2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0],3*$hotel_extension_price[3]+$hotel_extension_price[0],4*$hotel_extension_price[4]);
                            $early_he_room_price[$i] = min((2*$early_hotel_extension_price[2]) + 2*$early_hotel_extension_price[0],3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0],4*$early_hotel_extension_price[4]);
                            $late_he_room_price[$i] = min((2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0],3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0],4*$late_hotel_extension_price[4]);
                            //} hotel-extension
                            if ($room_price_option1[$i] == $room_price[$i]) {
                                $tmp_var = number_format((($a_price[2] + 2 * $e) / 3), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            if ($room_price_option2[$i] == $room_price[$i]) {
                                $tmp_var = number_format(((2 * $a_price[3] + $e) / 3), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            if ($room_price_option3[$i] == $room_price[$i]) {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4]; //房间$i小孩平均价
                            }
                            //买二送1价 1大人 3小孩
                            if ((int) $is_buy_two_get_one) {
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
                                    $room_price[$i] = 2 * $a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
                                }
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '3') {
                                    $room_price[$i] = 2 * ($a_price[2] + get_products_surcharge($products_id));//买二送二价
                                }
                                $tmp_var = number_format(($room_price[$i] / 4), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            $room_price_cost[$i] = min((2 * $a_cost[2]) + 2 * $e_cost, 3 * $a_cost[3] + $e_cost, 4 * $a_cost[4]);
                            //hotel-extension {
                            $he_room_price_cost[$i] = min((2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0],3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0],4*$hotel_extension_price_cost[4]);
                            $early_he_room_price_cost[$i] = min((2*$early_hotel_extension_price_cost[2]) + 2*$early_hotel_extension_price_cost[0],3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0],4*$early_hotel_extension_price_cost[4]);
                            $late_he_room_price_cost[$i] = min((2*$late_hotel_extension_price_cost[2]) + 2*$late_hotel_extension_price_cost[0],3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0],4*$late_hotel_extension_price_cost[4]);
                            //}hotel-extension
                        } elseif ($_POST['room-' . $i . '-adult-total'] == '2' && $_POST['room-' . $i . '-child-total'] == '1') {							
                            $room_price[$i] = min((2 * $a_price[2]) + $e, 3 * $a_price[3]);
                            $room_price_old += $room_price[$i];
                            //hotel-extension {							
                            $he_room_price[$i] = min((2*$hotel_extension_price[2]) + $hotel_extension_price[0],3*$hotel_extension_price[3]);							
                            $early_he_room_price[$i] = min( (2*$early_hotel_extension_price[2]) + $early_hotel_extension_price[0],3*$early_hotel_extension_price[3]);							
                            $late_he_room_price[$i] = min((2*$late_hotel_extension_price[2]) + $late_hotel_extension_price[0],3*$late_hotel_extension_price[3]);
                            //} hotel-extension
                            if ($room_price_option1[$i] <= $room_price_option2[$i]) {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e; //房间$i小孩平均价
                            } else {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i小孩平均价
                            }
                            //买二送1价 2大人 1小孩
                            if ((int) $is_buy_two_get_one) {
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
                                    $room_price[$i] = 2 * $a_price[2] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
                                }
                                $tmp_var = number_format(($room_price[$i] / 3), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            $room_price_cost[$i] = min((2 * $a_cost[2]) + $e_cost, 3 * $a_cost[3]);
                            //hotel-extension {
                            $he_room_price_cost[$i] = min((2*$hotel_extension_price_cost[2]) + $hotel_extension_price_cost[0],3*$hotel_extension_price_cost[3]);							
                            $early_he_room_price_cost[$i] = min( (2*$early_hotel_extension_price_cost[2]) + $early_hotel_extension_price_cost[0],3*$early_hotel_extension_price_cost[3]);							
                            $late_he_room_price_cost[$i] = min((2*$late_hotel_extension_price_cost[2]) + $late_hotel_extension_price_cost[0],3*$late_hotel_extension_price_cost[3]);
                            //}hotel-extension
                        } elseif ($_POST['room-' . $i . '-adult-total'] == '2' && $_POST['room-' . $i . '-child-total'] == '2') {						
                            $room_price[$i] = min((2 * $a_price[2]) + 2 * $e, 3 * $a_price[3] + $e,4 * $a_price[4]);
                            $room_price_old += $room_price[$i];
                            //hotel-extension{
                            $he_room_price[$i] = min((2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0],3*$hotel_extension_price[3]+$hotel_extension_price[0],4*$hotel_extension_price[4]);
                            $early_he_room_price[$i] = min((2*$early_hotel_extension_price[2]) + 2*$early_hotel_extension_price[0],3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0],4*$early_hotel_extension_price[4]);
                            $late_he_room_price[$i] = min((2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0],3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0],4*$late_hotel_extension_price[4]);
                            //}hotel-extension
                            if ($room_price_option1[$i] == $room_price[$i]) {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e; //房间$i小孩平均价
                            }
                            if ($room_price_option2[$i] == $room_price[$i]) {
                                $tmp_var = number_format(($a_price[3] + $e) / 2, 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = ($a_price[3] + $e) / 2; //房间$i小孩平均价
                            }
                            if ($room_price_option3[$i] == $room_price[$i]) {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4]; //房间$i小孩平均价
                            }

                            //买二送1价 2大人 2小孩
                            if ((int) $is_buy_two_get_one) {
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {									
                                    $room_price[$i] = 2 * $a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
                                }
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '3') {
                                    $room_price[$i] = 2 * ($a_price[2] + get_products_surcharge($products_id));//买二送二价
                                }
                                $tmp_var = number_format(($room_price[$i] / 4), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            $room_price_cost[$i] = min((2 * $a_cost[2]) + 2 * $e_cost, 3 * $a_cost[3] + $e_cost, 4 * $a_cost[4]);
                            //hotel-extension {							
                            $he_room_price_cost[$i] = min((2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0],3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0],4*$hotel_extension_price_cost[4]);
                            $early_he_room_price_cost[$i] = min((2*$early_hotel_extension_price_cost[2]) + 2*$early_hotel_extension_price_cost[0],3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0],4*$early_hotel_extension_price_cost[4]);
                            $late_he_room_price_cost[$i] = min((2*$late_hotel_extension_price_cost[2]) + 2*$late_hotel_extension_price_cost[0],3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0],4*$late_hotel_extension_price_cost[4]);
                            //}hotel-extension							
                        } elseif ($_POST['room-' . $i . '-adult-total'] == '3' && $_POST['room-' . $i . '-child-total'] == '1') {							
                            $room_price[$i] = min(3 * $a_price[3] + $e, 4 * $a_price[4]);
                            $room_price_old += $room_price[$i];
                            //hotel-extension{
                            $he_room_price[$i] = min(3*$hotel_extension_price[3]+$hotel_extension_price[0],4*$hotel_extension_price[4]);														
                            $early_he_room_price[$i] = min(3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0],4*$early_hotel_extension_price[4]);														
                            $late_he_room_price[$i] = min(3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0],4*$late_hotel_extension_price[4]);
                            //}hotel-extension
                            if ($room_price_option1[$i] <= $room_price_option2[$i]) {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e; //房间$i小孩平均价
                            } else {
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4]; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4]; //房间$i小孩平均价
                            }
                            //买二送1价 3大人 1小孩
                            if ((int) $is_buy_two_get_one) {								
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '2') {
                                    $room_price[$i] = 2 * $a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
                                }
                                if ($is_buy_two_get_one == '1' || $is_buy_two_get_one == '3') {									
                                    $room_price[$i] = 2 * ($a_price[2] + get_products_surcharge($products_id));//买二送二
                                }
                                $tmp_var = number_format(($room_price[$i] / 4), 2, '.', '');
                                $_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i大人平均价
                                $_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var; //房间$i小孩平均价
                            }
                            $room_price_cost[$i] = min(3 * $a_cost[3] + $e_cost, 4 * $a_cost[4]);
                            //hotel-extension {
                            $he_room_price_cost[$i] = min(3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0],4*$hotel_extension_price_cost[4]);
                            $early_he_room_price_cost[$i] = min(3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0],4*$early_hotel_extension_price_cost[4]);
                            $late_he_room_price_cost[$i] = min(3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0],4*$late_hotel_extension_price_cost[4]);
                            //}
                        }
                    }
                    /*  * **************total price for room no 1 **************** */
                    $roomno = $i + 1;
                    if ($language == 'tchinese' || $language == 'schinese') {						
                        $total_info_room .= "<br>" . tep_get_total_of_adult_in_room($roomno) . " " . $_POST['room-' . $i . '-adult-total'];
                        $early_he_total_info_room .= "<br>".tep_get_total_of_adult_in_room($roomno)." ".$_POST['room-'.$i.'-adult-total'];
                        $late_he_total_info_room .= "<br>".tep_get_total_of_adult_in_room($roomno) ." ".$_POST['room-'.$i.'-adult-total'];
                        if ($_POST['room-' . $i . '-child-total'] != '0'){
                                $total_info_room .= "<br>" . tep_get_total_of_children_in_room($roomno) . " " . $_POST['room-' . $i . '-child-total'];
                                $early_he_total_info_room .= "<br>".tep_get_total_of_children_in_room($roomno)." ".$_POST['room-'.$i.'-child-total'];
                                $late_he_total_info_room .= "<br>".tep_get_total_of_children_in_room($roomno)." ".$_POST['room-'.$i.'-child-total'];
                        }
                        if ($totalinroom[$i] == 2 && tep_not_null($_POST['room-' . $i . '-bed'])) { //如有床型信息才加入床型
                            $total_info_room .= "<br>" . tep_get_bed_of_room($roomno) . " " . tep_get_bed_name($_POST['room-' . $i . '-bed']);
                            $early_he_total_info_room .= "<br>" . tep_get_bed_of_room($roomno) . " " . tep_get_bed_name($_POST['room-' . $i . '-bed']);
                            $late_he_total_info_room .= "<br>" . tep_get_bed_of_room($roomno) . " " . tep_get_bed_name($_POST['room-' . $i . '-bed']);
                        }
                        $total_info_room .= "<br>" . tep_get_total_of_room($roomno) . " " . $currencies->format($room_price[$i]);
                        if($he_room_price[$i] > 0){							
                            if($early_he_room_price[$i] > 0){
                                $early_he_total_info_room .= "<br>".tep_get_total_of_room($roomno)." ".$currencies->format($early_he_room_price[$i]);
                            }
                            if($late_he_room_price[$i] > 0){
                                $late_he_total_info_room .= "<br>".tep_get_total_of_room($roomno)." ".$currencies->format($late_he_room_price[$i]);
                            }
                        }				
                        $total_no_guest_tour = $total_no_guest_tour + $_POST['room-' . $i . '-adult-total'] + $_POST['room-' . $i . '-child-total'];
                        $total_room_adult_child_info .= $_POST['room-' . $i . '-adult-total'] . '!!' . $_POST['room-' . $i . '-child-total'] . '###';
                    } else { //default language
                        $total_info_room .= "<br>" . TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS . " " . $roomno . ": " . $_POST['room-' . $i . '-adult-total'];
                        $early_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-adult-total'];
                        $late_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-adult-total'];
                        if ($_POST['room-' . $i . '-child-total'] != '0'){
                            $total_info_room .= "<br>" . TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS . " " . $roomno . ": " . $_POST['room-' . $i . '-child-total'];
                            $early_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-child-total'];
                            $late_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-child-total'];
                        }
                        //$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($room_price[$i],2);
                        if($room_price[$i]>0){	//价格不为0才显示共计信息
                            $total_info_room .= "<br>" . TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM . " " . $roomno . ": " . $currencies->format($room_price[$i]);
                        }
                        if($he_room_price[$i] > 0){							
                            if($early_he_room_price[$i] > 0){
                                $early_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($early_he_room_price[$i],2);
                            }
                            if($late_he_room_price[$i] > 0){
                                $late_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($late_he_room_price[$i],2);
                            }
                        }						
                        $total_no_guest_tour = $total_no_guest_tour + $_POST['room-' . $i . '-adult-total'] + $_POST['room-' . $i . '-child-total'];
                        $total_room_adult_child_info .= $_POST['room-' . $i . '-adult-total'] . '!!' . $_POST['room-' . $i . '-child-total'] . '###';
                    }
                }
            }
            /*                         * **************************EOC: this is validation for the first room ******************************* */
            /*                         * **************************************************************************************************** */
            /*                         * **************************************************************************************************** */
        }
        if ($errormessageperson != '') {
            // howard edit for fast cart
            if ($HTTP_POST_VARS['ajax'] == 'true') {				
                echo '[ERROR]' . general_to_ajax_string(TEXT_MAX_ALLOW_ROOM . $errormessageperson) . '[/ERROR]';
                exit();
            } else {
                // howard edit for fast cart
                $messageStack->add_session('product_info', TEXT_MAX_ALLOW_ROOM . $errormessageperson, 'error');
                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&maxnumber=' . $errormessageperson . ''));
                exit();
            }
        } else {
            //howard fixed 2010-08-10 start 
            //comment by vincent : 计算房间总价
            $total_room_price = 0;$total_room_price_cost = 0;
            $total_hotel_extension_price=0;$total_hotel_extension_price_cost=0;
            $total_early_hotel_extension_price=0;$total_early_hotel_extension_price_cost=0;
            $total_late_hotel_extension_price=0;$total_late_hotel_extension_price_cost=0;
            for ($n = 0,$len=count($room_price); $n < $len; $n++) $total_room_price += $room_price[$n];			
            for ($n = 0,$len=count($room_price_cost); $n < $len; $n++) $total_room_price_cost += $room_price_cost[$n];			
            for ($n = 0,$len=count($he_room_price); $n < $len;$n++) $total_hotel_extension_price += $he_room_price[$n] ;
            for ($n = 0,$len=count($he_room_price_cost); $n < $len;$n++) $total_hotel_extension_price_cost += $he_room_price_cost[$n] ;
            for ($n = 0,$len=count($early_he_room_price); $n < $len;$n++) $total_early_hotel_extension_price += $early_he_room_price[$n] ;
            for ($n = 0,$len=count($early_he_room_price_cost); $n < $len;$n++) $total_early_hotel_extension_price_cost += $early_he_room_price_cost[$n] ;
            for ($n = 0,$len=count($late_he_room_price); $n < $len;$n++) $total_late_hotel_extension_price += $late_he_room_price[$n] ;
            for ($n = 0,$len=count($late_he_room_price_cost); $n < $len;$n++) $total_late_hotel_extension_price_cost += $late_he_room_price_cost[$n] ;			
            //featured group deal discount {
            $check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$products_id."' and departure_restriction_date <= '".$tmp_dp_date."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
            if(check_is_featured_deal($products_id) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
                $diy_sort_orders=2;//2 for featured discount and 1 for group ordering
            }
            //}featured group deal discount
            //amit added to add extra 3% if agency is L&L Travel start 针对L&L供应商增加3%附加费 {
            $pro_agency_info_array = tep_get_tour_agency_information((int) $HTTP_POST_VARS['products_id']);
            if ($pro_agency_info_array['final_transaction_fee'] > 0) {
                $total_room_price = tep_get_total_fares_includes_agency($total_room_price, $pro_agency_info_array['final_transaction_fee']);
                $total_info_room .= "<br>" . sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_PERSENT, $pro_agency_info_array['final_transaction_fee']);
            }
            //}amit added to add extra 3% if agency is L&L Travel end
            $hotel_extension_info .= $HTTP_POST_VARS['early_hotel_checkin_date'].'|=|'.$HTTP_POST_VARS['early_hotel_checkout_date'].'|=|'.$HTTP_POST_VARS['early_arrival_hotels'].'|=|'.$HTTP_POST_VARS['early_hotel_rooms'].'|=|'.$total_early_hotel_extension_price.'|=|'.$total_early_hotel_extension_price_cost.'|=|'.$HTTP_POST_VARS['late_hotel_checkin_date'].'|=|'.$HTTP_POST_VARS['late_hotel_checkout_date'].'|=|'.$HTTP_POST_VARS['staying_late_hotels'].'|=|'.$HTTP_POST_VARS['late_hotel_rooms'].'|=|'.$total_late_hotel_extension_price.'|=|'.$total_late_hotel_extension_price_cost;
            
            if(tep_check_product_is_transfer($HTTP_POST_VARS['products_id'],$product_transfer_info)!='1'){
                //howard added if total_no_guest_tour < min_num_guest display error
                $check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="' . (int) $HTTP_POST_VARS['products_id'] . '" limit 1');
                $check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
                if ($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']) {
                    //howard added for fast add cart
                    if ($HTTP_POST_VARS['ajax'] == 'true') {
                        //echo '[ERROR]'.iconv(CHARSET,'utf-8'.'//IGNORE',TEXT_PRODUCTS_MIN_GUEST.$check_min_guest_row['min_num_guest']).'[/ERROR]';
                        //exit;
                    } else {
                        //howard added for fast add cart end
                        $messageStack->add_session('product_info', TEXT_PRODUCTS_MIN_GUEST . $check_min_guest_row['min_num_guest'], 'error');
                        tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&minnumber=' . $check_min_guest_row['min_num_guest'] . ''));
                        exit();
                    }
                }
            }
            
            //transfer-service {	
            $transfer_price =0;$transfer_cost = 0 ;
            if(tep_not_null($HTTP_POST_VARS['transfer_products_id']) && tep_check_product_is_transfer($HTTP_POST_VARS['transfer_products_id'])){
                $transfer_price_info = tep_transfer_calculation_price(intval($HTTP_POST_VARS['transfer_products_id']) ,$HTTP_POST_VARS,true);
                $transfer_price = $transfer_price_info['price'] ;
                $transfer_cost = $transfer_price_info['cost'] ;
            }
            //}
            //howard added if total_no_guest_tour < min_num_guest display error end
            //check $total_room_price start
            
            //属性价格统计;注意位置不能乱动
            $attribute_total = 0;
            //如果产品是邮轮团，那么必须选择一个价格>0的客舱甲板
            $hasSelDeck = false;
            $isCruises = false;
            if($product_info['is_cruises']=="1"){
                $isCruises = true;
            }
            foreach((array)$_POST['id'] as $option_id => $option_value_id){
                    $attributePrice = attributes_price_display($products_id,$option_id,$option_value_id);
                    $attribute_total += $attributePrice;
                    if(in_array($option_id,(array)$cruisesOptionIds)){
                        $isCruises = true;
                        if($attributePrice > 0){
                            $hasSelDeck = true;
                        }
                    }
                    //限制另外收费产品属性中标明的人数不能大于参团总人数，因为可能导致BOSS亏本。属性值名称格式如：2人接送 10:30pm-12:00am
                    if((int)$attributePrice>0){
                    	$option_guest_num = tep_db_get_field_value('products_options_values_name', 'products_options_values', 'products_options_values_id="'.(int)$option_value_id.'"');
                    	if(preg_match('/^\d+人/', preg_replace('/[[:space:]]+/','',$option_guest_num))){
                    		$option_guest_num = (int)$option_guest_num;
                    		if((int)$total_no_guest_tour && ($total_no_guest_tour < $option_guest_num)){
                    			$option_name = tep_get_product_option_name_from_optionid((int)$option_id);
                    			$error_msg = db_to_html('参团总人数'.$total_no_guest_tour.' 与'.$option_name.'人数'.$option_guest_num.'不匹配！');
                    			if ($HTTP_POST_VARS['ajax'] == 'true') {
                    				echo '[ERROR]' . general_to_ajax_string($error_msg) . '[/ERROR]';exit();
                    			} else {
                    				$messageStack->add_session('product_info', $error_msg, 'error');
                    				tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&error_departure_date=true'));
                    				exit();
                    			}
                    		}
                    	}
                    }
            }
            
            //邮轮团选项检查{
            if( $isCruises==true && $hasSelDeck == false ){
                if ($HTTP_POST_VARS['ajax'] == 'true') {
                    echo '[ERROR]' . general_to_ajax_string(db_to_html('由于此产品是邮轮团，所以您需要选择一个客舱的甲板！')) . '[/ERROR]';				
                    exit;
                } else {
                    $messageStack->add_session('product_info', db_to_html('由于此产品是邮轮团，所以您需要选择一个客舱的甲板！'), 'error');
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id']));
                    exit();
                }
            }
            //}
            

            if ( $total_room_price < 0.01 && $early_he_room_price<0.01 && $transfer_price < 0.01 && ($attribute_total < 0.01 || $isCruises==false) ) {
                if ($HTTP_POST_VARS['ajax'] == 'true') {
                    echo '[ERROR]' . general_to_ajax_string(db_to_html('总价为' . $total_room_price . '，不能预订请注意“价格明细”中的说明！')) . '[/ERROR]';
                    exit;
                } else {
                    $messageStack->add_session('product_info', db_to_html('总价为' . $total_room_price . '，不能预订请注意“价格明细”中的说明！'), 'error');
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&total_price_error=' . $total_room_price));
                    exit();
                }
            }
            //check $total_room_price end
            //amit added to remove qty start
            /*$cart_products_array = $cart->get_products();
            for ($i = 0, $n = sizeof($cart_products_array); $i < $n; $i++) {
                if ($HTTP_POST_VARS['products_id'] == (int) $cart_products_array[$i]['id']) {
                    $cart->remove($cart_products_array[$i]['id']);
                }
            }*/
            $cart_products_array =  $cart->get_products();
            for ($i=0, $n=sizeof($cart_products_array); $i<$n; $i++) {
                if(tep_check_product_is_hotel($HTTP_POST_VARS['products_id'])==1){
                    $cmp_early_hotel_product_id = tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['h_e_id']);
                    $cmp_late_hotel_product_id = tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['h_l_id']);
                    if(strval($cmp_early_hotel_product_id) == strval($cart_products_array[$i]['id']) || strval($cmp_late_hotel_product_id) == strval($cart_products_array[$i]['id']) ){
                        $cart->remove($cart_products_array[$i]['id']);
                    }
                }else{
                    $cmp_early_hotel_product_id = tep_get_uprid($HTTP_POST_VARS['early_arrival_hotels'], $HTTP_POST_VARS['h_e_id']);
                    $cmp_late_hotel_product_id = tep_get_uprid($HTTP_POST_VARS['staying_late_hotels'], $HTTP_POST_VARS['h_l_id']);
                    if($HTTP_POST_VARS['products_id'] == (int)$cart_products_array[$i]['id'] || strval($cmp_early_hotel_product_id) == strval($cart_products_array[$i]['id']) || strval($cmp_late_hotel_product_id) == strval($cart_products_array[$i]['id']) ){
                        $cart->remove($cart_products_array[$i]['id']);
                    }
                }
            }
			//amit added to remove qty end
            if (tep_session_is_registered('customer_id')){
                tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$products_id");
            }
            
            //$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id'])) + 1, $HTTP_POST_VARS['id'], '', $HTTP_POST_VARS['availabletourdate'], $HTTP_POST_VARS['departurelocation'], '', '', '', '', '', $total_room_price, $total_info_room, $total_no_guest_tour, $total_room_adult_child_info, $date_price_cost, $total_room_price_cost, $HTTP_POST_VARS['travel_comp'], $HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy']);
            if(tep_check_product_is_hotel($HTTP_POST_VARS['products_id'])==1){
                if(isset($HTTP_POST_VARS['h_e_id'])){
                    foreach($_POST['h_e_id'] as $attr_key=>$attr_val){
						$_POST['id'][$attr_key] = $attr_val;
					}
					$cart->add_cart($HTTP_POST_VARS['products_id'], 1, $_POST['id'],'',$early_hotel_checkin_date,$HTTP_POST_VARS['departurelocation'],'','','','','',$total_early_hotel_extension_price,$early_he_total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_early_hotel_extension_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info, $extra_values);
                }else{
                    foreach((array)$_POST['h_l_id'] as $attr_key=>$attr_val){
						$_POST['id'][$attr_key] = $attr_val;
					}
					$cart->add_cart($HTTP_POST_VARS['products_id'], 1, $_POST['id'],'',$late_hotel_checkin_date,$HTTP_POST_VARS['departurelocation'],'','','','','',$total_late_hotel_extension_price,$late_he_total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_late_hotel_extension_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'],$hotel_extension_info, $extra_values);
                }
            }elseif(tep_check_product_is_transfer($HTTP_POST_VARS['products_id'])){
                //接送服务单独预订
                $transfer_info = tep_transfer_encode_info($HTTP_POST_VARS);
                $cart->add_cart($HTTP_POST_VARS['transfer_products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['transfer_products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'','','','','','','','',$transfer_price,'',0,'',$date_price_cost, $transfer_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,$transfer_info);
            }else{
                $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'',$HTTP_POST_VARS['availabletourdate'],$HTTP_POST_VARS['departurelocation'],'','','','','',$total_room_price,$total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_room_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,'', $extra_values);
                if((int)$total_hotel_extension_price > 0){
                    if($total_early_hotel_extension_price > 0){
                    	
                        $cart->add_cart($HTTP_POST_VARS['early_arrival_hotels'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['early_arrival_hotels'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['h_e_id'],'',$early_hotel_checkin_date,'','','','','','',$total_early_hotel_extension_price,$early_he_total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_early_hotel_extension_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,'', $extra_values);										
                    }
                    if($total_late_hotel_extension_price > 0){
                        $cart->add_cart($HTTP_POST_VARS['staying_late_hotels'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['staying_late_hotels'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['h_l_id'],'',$late_hotel_checkin_date,'','','','','','',$total_late_hotel_extension_price,$late_he_total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_late_hotel_extension_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,'', $extra_values);										
                    }
                }
                if($transfer_price > 0.1){
                    $transfer_info = tep_transfer_encode_info($HTTP_POST_VARS);
                    $cart->add_cart($HTTP_POST_VARS['transfer_products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['transfer_products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'','','','','','','','',$transfer_price,'',0,'',$date_price_cost, $transfer_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,$transfer_info);				
                }
            }
            //howard added for fast add cart
            if ($HTTP_POST_VARS['ajax'] == 'true') {
                $cp_sum = $cart->contents;
                foreach ($cp_sum as $key => $value) {
                    $cart_sum +=$value['qty'];
                }
                echo '[Cart_Sum]' . max(0, $cart_sum) . '[/Cart_Sum]';
                echo '[Cart_Total]' . $currencies->format($cart->show_total()) . '[/Cart_Total]';
                exit;
            }
            //howard added for fast add cart end
            tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
        }			
    } else { 
        //if there is no rooms 无房间		==========================================================================================================================================================================================================================================
        $totaladultticket = $_POST['room-0-adult-total'];
        if ($_POST['room-0-child-total'] != ''){
            $totalchildticket = $_POST['room-0-child-total'];
        }
        $total_no_guest_tour = $totaladultticket + $totalchildticket;
        $total_room_adult_child_info = "0###" . $totaladultticket . "!!" . $totalchildticket;
        $bed_option_info = "0###";
        $total_adult_ticket_price = $totaladultticket * $a_price[1];
        $total_child_ticket_price = $totalchildticket * $e;

        $total_room_price = $total_adult_ticket_price + $total_child_ticket_price;
        
        $_SESSION['adult_average'][0][$HTTP_POST_VARS['products_id']] = $a_price[1]; //大人平均价
        $_SESSION['child_average'][0][$HTTP_POST_VARS['products_id']] = $e; //小孩平均价
        //amit added for cost cal
        $total_adult_ticket_price_cost = $totaladultticket * $a_cost[1];
        $total_child_ticket_price_cost = $totalchildticket * $e_cost;
        $total_room_price_cost = $total_adult_ticket_price_cost + $total_child_ticket_price_cost;
        //amit added for cost cal

        $total_info_room .= "<br>" . TEXT_SHOPPIFG_CART_ADULTS_NO . " : " . $totaladultticket;       
        if ($totalchildticket != 0){
            $total_info_room .= "<br>" . TEXT_SHOPPIFG_CART_CHILDREDN_NO . " : " . $totalchildticket;
        }
        //$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL." : $".number_format($total_room_price,2);
        if($total_room_price>0){	//价格不为0才显示共计信息
            $total_info_room .= "<br>" . TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL . " : " . $currencies->format($total_room_price);
        }
        //featured group deal discount
        $check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$HTTP_POST_VARS['products_id']."' and departure_restriction_date <= '".$tmp_dp_date."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
        if(check_is_featured_deal($HTTP_POST_VARS['products_id']) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
            $diy_sort_orders=2;//2 for featured discount and 1 for group ordering
        }
        //featured group deal discount			
        //amit added to add extra 3% if agency is L&L Travel start
        $pro_agency_info_array = tep_get_tour_agency_information((int) $HTTP_POST_VARS['products_id']);
        if ($pro_agency_info_array['final_transaction_fee'] > 0) {
            $total_room_price = tep_get_total_fares_includes_agency($total_room_price, $pro_agency_info_array['final_transaction_fee']);
            $total_info_room .= "<br>" . sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_PERSENT, $pro_agency_info_array['final_transaction_fee']);
        }
        //amit added to add extra 3% if agency is L&L Travel end
        //howard added if total_no_guest_tour < min_num_guest display error
        $check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="' . (int) $HTTP_POST_VARS['products_id'] . '" limit 1');
        $check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
        if ($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']) {

            //howard added for fast add cart
            if ($HTTP_POST_VARS['ajax'] == 'true') {
                //echo '[ERROR]'.iconv(CHARSET,'utf-8'.'//IGNORE',TEXT_PRODUCTS_MIN_GUEST.$check_min_guest_row['min_num_guest']).'[/ERROR]';
                //exit;
            } else {
                //howard added for fast add cart end
                $messageStack->add_session('product_info', TEXT_PRODUCTS_MIN_GUEST . $check_min_guest_row['min_num_guest'], 'error');
                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&minnumber=' . $check_min_guest_row['min_num_guest'] . ''));
                exit();
            }
        }
        //howard added if total_no_guest_tour < min_num_guest display error end
        //transfer-service {	
        $transfer_price =0;$transfer_cost = 0 ;
        if(tep_not_null($HTTP_POST_VARS['transfer_products_id']) && tep_check_product_is_transfer($HTTP_POST_VARS['transfer_products_id'])){
            $transfer_price_info = tep_transfer_calculation_price(intval($HTTP_POST_VARS['transfer_products_id']) ,$HTTP_POST_VARS,true);
            $transfer_price = $transfer_price_info['price'] ;
            $transfer_cost = $transfer_price_info['cost'] ;
        }
        //}
        //check $total_room_price start
        
        //属性价格统计;注意位置不能乱动
        $attribute_total = 0;
        //如果产品是邮轮团，那么必须选择一个价格>0的客舱甲板
        $hasSelDeck = false;
        $isCruises = false;
        if($product_info['is_cruises']=="1"){
            $isCruises = true;
        }
        foreach((array)$_POST['id'] as $option_id => $option_value_id){
        	$attributePrice = attributes_price_display($products_id,$option_id,$option_value_id);
        	$attribute_total += $attributePrice;
        	if(in_array($option_id,(array)$cruisesOptionIds)){
        		$isCruises = true;
        		if($attributePrice > 0){
        			$hasSelDeck = true;
        		}
        	}
        	//限制另外收费产品属性中标明的人数不能大于参团总人数，因为可能导致BOSS亏本。属性值名称格式如：2人接送 10:30pm-12:00am
        	if((int)$attributePrice>0){
        		$option_guest_num = tep_db_get_field_value('products_options_values_name', 'products_options_values', 'products_options_values_id="'.(int)$option_value_id.'"');
        		if(preg_match('/^\d+人/', preg_replace('/[[:space:]]+/','',$option_guest_num))){
        			$option_guest_num = (int)$option_guest_num;
        			if((int)$total_no_guest_tour && ($total_no_guest_tour < $option_guest_num)){
        				$option_name = tep_get_product_option_name_from_optionid((int)$option_id);
        				$error_msg = db_to_html('参团总人数'.$total_no_guest_tour.' 与'.$option_name.'人数'.$option_guest_num.'不匹配！');
        				if ($HTTP_POST_VARS['ajax'] == 'true') {
        					echo '[ERROR]' . general_to_ajax_string($error_msg) . '[/ERROR]';exit();
        				} else {
        					$messageStack->add_session('product_info', $error_msg, 'error');
        					tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&error_departure_date=true'));
        					exit();
        				}
        			}
        		}
        	}
        }
        
        //邮轮团选项检查{
        if( $isCruises==true && $hasSelDeck == false ){
            if ($HTTP_POST_VARS['ajax'] == 'true') {
                echo '[ERROR]' . general_to_ajax_string(db_to_html('由于此产品是邮轮团，所以您需要选择一个客舱的甲板！')) . '[/ERROR]';				
                exit;
            } else {
                $messageStack->add_session('product_info', db_to_html('由于此产品是邮轮团，所以您需要选择一个客舱的甲板！'), 'error');
                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id']));
                exit();
            }
        }
        //}

        if ( $total_room_price < 0.01 && $transfer_price < 0.01 && ($attribute_total < 0.01 || $isCruises==false)) {
            if ($HTTP_POST_VARS['ajax'] == 'true') {
                echo '[ERROR]' . general_to_ajax_string(db_to_html('总价为' . $total_room_price . '，不能预订请注意“价格明细”中的说明！')) . '[/ERROR]';				
                exit;
            } else {
                $messageStack->add_session('product_info', db_to_html('总价为' . $total_room_price . '，不能预订请注意“价格明细”中的说明！'), 'error');
                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'] . '&total_price_error=' . $total_room_price));
                exit();
            }
        }
        //check $total_room_price end
        //amit added to remove qty start
        $cart_products_array = $cart->get_products();
        for ($i = 0, $n = sizeof($cart_products_array); $i < $n; $i++) {
            if ($HTTP_POST_VARS['products_id'] == (int) $cart_products_array[$i]['id']) {
                $cart->remove($cart_products_array[$i]['id']);
            }
        }
        //amit added to remove qty end

        if (tep_session_is_registered('customer_id')){
            tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$products_id");
        }
        
        
        if($total_room_price == 0 &&   $transfer_price  > 0.1) {
            //单独预订接送服务
            $transfer_info = tep_transfer_encode_info($HTTP_POST_VARS);
            $cart->add_cart($HTTP_POST_VARS['transfer_products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['transfer_products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'','','','','','','','',$transfer_price,'',0,'',$date_price_cost, $transfer_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,$transfer_info, $extra_values);
        }else {
            $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id'])) + 1, $HTTP_POST_VARS['id'], '', $HTTP_POST_VARS['availabletourdate'], $HTTP_POST_VARS['departurelocation'], '', '', '', '', '', $total_room_price , $total_info_room, $total_no_guest_tour, $total_room_adult_child_info, $date_price_cost, $total_room_price_cost, $HTTP_POST_VARS['travel_comp'], $HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'],'','', $extra_values);
            if($transfer_price > 0.1){
                //组合预订接送服务
                $transfer_info = tep_transfer_encode_info($HTTP_POST_VARS);
                $cart->add_cart($HTTP_POST_VARS['transfer_products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['transfer_products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'','','','','','','','',$transfer_price,'',0,'',$date_price_cost, $transfer_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,$transfer_info, $extra_values);
            }
        }
        //howard added for fast add cart
        if ($HTTP_POST_VARS['ajax'] == 'true') {
            $cp_sum = $cart->contents;
            foreach ($cp_sum as $key => $value) {
                $cart_sum +=$value['qty'];
            }
            echo '[Cart_Sum]' . max(0, $cart_sum) . '[/Cart_Sum]';
            echo '[Cart_Total]' . $currencies->format($cart->show_total()) . '[/Cart_Total]';
            exit;
        }
        //howard added for fast add cart end
        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
    } //elseif($_POST['numberOfRooms'])//if there is rooms
}
?>