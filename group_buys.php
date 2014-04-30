<?php
//团购页面，包括了团购需知页面和团购首页 Howard added
require('includes/application_top.php');

//广告基数--每个团的默认已经购人数
$ban_purchasedNum = array();
$ban_purchasedNum[1989] = 11;
$ban_purchasedNum[1990] = 8;
$ban_purchasedNum[1991] = 9;
$ban_purchasedNum[1992] = 7;
//当前目录
$current_category_id = 268;


//$start_time = microtime(true);
//数据提交区域 start {
if($_POST['ajax']=="true" && $_GET['action']=='SendGroupBuyRecommendEmailToFriend'){
    $error = false;
    if((int)$_POST['prod_id']){
        if(!tep_not_null($_POST['to_email_address'])){
            $error = true;
        }else{
            $mails_address = explode(',', $_POST['to_email_address']);
            for($i=0; $i<count($mails_address); $i++){
                $mails_address[$i] = trim($mails_address[$i]);
                if(tep_validate_email($mails_address[$i]) == false){
                    $error = true;
                    break;
                }
            }
        }
        
        $mail_subject = tep_db_output($_POST['mail_subject']);
        $mail_text = tep_db_output($_POST['mail_text']);
        $ProdName = tep_db_output($_POST['ProdName']);

        if(!tep_not_null($mail_subject)){
            $error = true;
        }
        if(!tep_not_null($mail_text)){
            $error = true;
        }
        if($error == true){
            die('消息不全！');
        }
        
        if((int)$customer_id){
            $ref_id = $customer_id;
            $from_name = db_to_html(tep_customers_name($customer_id));
        }elseif(tep_not_null($_POST['FromAddress'])){
            $customers_id = form_email_get_customers_id($_POST['FromAddress']);
            $ref_id = $customers_id;
            if(!$customers_id){
                $js_error_str = 'alert("此账号不存在，请重新输入账号！");';
                echo '[JS]'.db_to_html($js_error_str).'[/JS]';
                exit;
            }
            $from_name = db_to_html(tep_customers_name($customers_id));
        }
        
        
        $from_name = iconv(CHARSET,'utf-8',$from_name);
        
        $ProdUrl = tep_href_link('product_info.php', 'products_id='.(int)$prod_id.'&ref='.$ref_id.'&affiliate_banner_id=1');
        
        $from_email_name = iconv(CHARSET,'utf-8',db_to_html("走四方网 "));
        $email_subject = iconv(CHARSET,'utf-8',db_to_html('来自您朋友')).$from_name.iconv(CHARSET,'utf-8',db_to_html('的推荐：')).$mail_subject.' ';
        
        $from_email_address = 'automail@usitrip.com';
        
        $patterns = array();
        $patterns[0] = '{EmailContent}';
        $patterns[1] = '{HTTP_SERVER}';
        $patterns[2] = '{FromName}';
        $patterns[3] = '{ProdName}';
        $patterns[4] = '{ProdUrl}';
        $patterns[5] = '{CONFORMATION_EMAIL_FOOTER}';
        
        $replacements = array();
        $replacements[0] = $mail_text;
        $replacements[1] = HTTP_SERVER;
        $replacements[2] = $from_name;
        $replacements[3] = $ProdName;
        $replacements[4] = $ProdUrl;
        $replacements[5] = iconv(CHARSET,'utf-8',db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER)));
        
        $email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
        $email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/share_to_friend.tpl.html');
        $email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
        
		$email_text = str_replace($patterns ,$replacements, iconv(CHARSET,'utf-8'.'//IGNORE',db_to_html($email_tpl)));
        $email_text = preg_replace('/[[:space:]]+/',' ',$email_text);
        
        
        $as = count($_SESSION['need_send_email']);
        $mails_address = array_unique($mails_address);
        foreach((array)$mails_address as $key => $val){
            if(strpos($val, '@') >0 ){
                //howard add use session+ajax send email
                $_SESSION['need_send_email'][$as]['to_name'] = $to_name;
                $_SESSION['need_send_email'][$as]['to_email_address'] = $val;
                $_SESSION['need_send_email'][$as]['email_subject'] = $email_subject;
                $_SESSION['need_send_email'][$as]['email_text'] = $email_text;
                $_SESSION['need_send_email'][$as]['from_email_name'] = $from_email_name;
                $_SESSION['need_send_email'][$as]['from_email_address'] = $from_email_address;
                $_SESSION['need_send_email'][$as]['email_charset'] = 'utf-8';
                $_SESSION['need_send_email'][$as]['action_type'] = 'true';
                $as++;
            }
            //howard add use session+ajax send email end
        }
        $js_str = 'auto_send_session_mail();';
        $js_str .= 'jQuery("#emailCon").hide();';
        $js_str .= 'jQuery("#emailBtnCenter").hide();';
        $js_str .= 'jQuery("#emailConSuccess").show();';
        $js_str .= 'SendEmaiSuccessAction();';
        
        echo '[JS]'.$js_str.'[/JS]';
        exit;
    }
}
//数据提交区域 end }

define('ONLY_USE_NEW_CSS', true);
define('TEXT_PRODUCT_POINTS', '赠 <span class="jifen_num">%s</span> <a class="sp3" href="'.tep_href_link('points.php').'" target="_blank">积分</a>');

if($_GET['do']=='note'){
    //团购需知页面 start {
    $the_title = db_to_html('团购须知 - 走四方网');
    $the_desc = db_to_html('团购须知');
    $the_key_words = db_to_html('团购,旅游团购');
    $breadcrumb->add(db_to_html('团购须知'), tep_href_link('group_buys.php','do=note'));
    $content = 'group_buys_note';
    //团购需知页面 end }
}else{
    
    if($_GET['do']=='expires'){
    //往日团购{
        if(!isset($_GET['gb_type'])) $_GET['gb_type'] = 2;
        
        $the_title = db_to_html('往日团购 - 走四方网');
        $breadcrumb->add(db_to_html('往日团购'), tep_href_link('group_buys.php','do=expires'));
        $content = 'group_buys_expires';
        $other_css_base_name = 'group_buys';
        
        $group_buys_products_sql_str0 = 'SELECT p.*, sgbh.start_date, sgbh.expires_date, sgbh.specials_type, sgbh.issue_num, pd.products_name, pd.products_small_description FROM `products` p, `products_description` pd,  specials_group_buy_history sgbh WHERE pd.products_id = p.products_id AND sgbh.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND sgbh.specials_type=1 AND sgbh.expires_date <"'.$Today_date.'" Order By sgbh.specials_type ASC, sgbh.issue_num ASC ';
        
        $group_buys_products_sql_str1 = 'SELECT p.*, sgbh.start_date, sgbh.expires_date, sgbh.specials_type, sgbh.issue_num, pd.products_name, pd.products_small_description FROM `products` p, `products_description` pd,  specials_group_buy_history sgbh WHERE pd.products_id = p.products_id AND sgbh.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND sgbh.specials_type=2 AND sgbh.expires_date <"'.$Today_date.'" Order By sgbh.specials_type ASC, sgbh.issue_num ASC ';
        
    //}
    }else{
    //今日团购{
        $the_title = db_to_html('团购 - 走四方网');
        $breadcrumb->add(db_to_html('团购'), tep_href_link('group_buys.php',''));
        $content = 'group_buys';
        
        $group_buys_products_sql_str0 = 'SELECT p.*, s.start_date, s.expires_date, s.specials_type, s.specials_max_buy_num, s.invite_info, s.remaining_num, pd.products_name, pd.products_small_description FROM `products` p, `products_description` pd,  specials s WHERE pd.products_id = p.products_id AND s.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND s.status="1" AND s.specials_type=1 AND s.start_date <="'.$Today_date.'" AND s.expires_date >"'.$Today_date.'" Order By s.specials_type ASC ';
        
        $group_buys_products_sql_str1 = 'SELECT p.*, s.start_date, s.expires_date, s.specials_type, s.specials_max_buy_num, s.invite_info, s.remaining_num, pd.products_name, pd.products_small_description FROM `products` p, `products_description` pd,  specials s WHERE pd.products_id = p.products_id AND s.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND s.status="1" AND s.specials_type=2 AND s.start_date <="'.$Today_date.'" AND s.expires_date >"'.$Today_date.'" Order By s.specials_type ASC ';
    //今日团购}
    }
    
//函数处理 start {
$max_rows_page = 5;	//每页最多5条记录

/**
 * 取得团购产品数据
 *
 * @param unknown_type $num 0为限量团1为限时团
 * @return unknown
 */
function _getGroupBuyDatas($num){
    if($num!="0" && $num!="1") die("PLX check _getGroupBuyDatas");
    global $group_buys_products_sql_str0, $group_split0, $datas0;
    global $group_buys_products_sql_str1, $group_split1, $datas1;
    global $currencies, $mcache, $max_rows_page, $Today_date;
    
    //echo $group_buys_products_sql_str0;
    $group_buys_products_sql_str = 'group_buys_products_sql_str'.$num;
    
    $group_split = 'group_split'.$num;
    $$group_split = new splitPageResults($$group_buys_products_sql_str, $max_rows_page);
    global $$group_split;
    $group_buys_products_sql = tep_db_query($$group_split->sql_query);
    
    $_datas = 'datas'.$num;
    $datas = array();
    
    $i = 0;
    while($group_buys_products = tep_db_fetch_array($group_buys_products_sql)){
        //产品标题切割
        $group_buys_products['products_name1']=strstr($group_buys_products['products_name'], '**');
        if($group_buys_products['products_name1']!='' && $group_buys_products['products_name1']!==false)$group_buys_products['products_name']=str_replace($group_buys_products['products_name1'],'',$group_buys_products['products_name']);
        
        $datas[$i] = $group_buys_products;

        //出发地点
        $display_str_departure_city = '';
        if(!tep_not_null($group_buys_products['departure_city_id'])){ $group_buys_products['departure_city_id'] = 0; }
        $cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in (".$group_buys_products['departure_city_id'].")  order by city");
        while($cityclass = tep_db_fetch_array($cityquery)){
            $display_str_departure_city .= " " .$cityclass['city'] . ", ";
        }
        $datas[$i]['display_str_departure_city'] = substr($display_str_departure_city, 0, -2);
        
        //结束地点
        if($group_buys_products['departure_end_city_id'] == ''){
        $group_buys_products['departure_end_city_id'] = 0;
        }
        $city_class_departure_end_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $group_buys_products['departure_end_city_id'] . ")");
        $display_str_end_city="";
        while($city_class_departure_end_at = tep_db_fetch_array($city_class_departure_end_at_query)) {
            //$datas[$i]['display_str_end_city'] .= $city_class_departure_end_at['city'].', '.$city_class_departure_end_at['zone_code'].', '.$city_class_departure_end_at['countries_iso_code_3'].'； ';
            $display_str_end_city .= $city_class_departure_end_at['city'].', ';
        }
        $datas[$i]['display_str_end_city'] = substr($display_str_end_city, 0, -2);
        
        //持续时间
        $h_or_d = "天";
        if($group_buys_products['products_durations_type']=="1"){
            $h_or_d = "小时";
        }
        $datas[$i]['display_products_durations'] = $group_buys_products['products_durations'].$h_or_d;

        
        //积分信息 start {
        if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) {
            if ($new_price = tep_get_products_special_price($group_buys_products['products_id'])) {
                $products_price_points = tep_display_points($new_price,tep_get_tax_rate($group_buys_products['products_tax_class_id']));
            } else {
                $products_price_points = tep_display_points($group_buys_products['products_price'],tep_get_tax_rate($group_buys_products['products_tax_class_id']));
            }
            $products_points = tep_calc_products_price_points($products_price_points);
            $products_points = get_n_multiple_points($products_points , $group_buys_products['products_id']);
            if ((USE_POINTS_FOR_SPECIALS == 'true') || $new_price == false) {
                $datas[$i]['products_points_info'] = sprintf(TEXT_PRODUCT_POINTS ,number_format($products_points,POINTS_DECIMAL_PLACES));
            }
        }
        //积分信息 end }
        
        //产品图片 start {
        $datas[$i]['products_pics_src'] = array();
        $srcI = 0;
        $ext_img_exist = tep_db_query("select prod_extra_image_id, product_image_name from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$group_buys_products['products_id']."' order by image_sort_order ");
        
        if(tep_db_num_rows($ext_img_exist)>0){
            if($group_buys_products['products_image_med']!=''){
                $datas[$i]['products_pics_src'][$srcI]= 'images/'.$group_buys_products['products_image_med'];
            }
            
            while($extra_images_rows = tep_db_fetch_array($ext_img_exist)){
                $url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
                if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
                    $url_product_image_name = $extra_images_rows['product_image_name'];
                }
                $datas[$i]['products_pics_src'][$srcI]= $url_product_image_name;
                $srcI++;
            }
                
        }else{
            if ($group_buys_products['products_image_med']!='') {
                $new_image = $group_buys_products['products_image_med'];
            } else {
                $new_image = $group_buys_products['products_image'];
            }
            
            if(!tep_not_null($new_image)){
                $new_image = 'noimage_large.jpg';
            }
            $datas[$i]['products_pics_src'][$srcI] = 'images/'.$new_image;
        }
        //产品图片 end }
        
        //载入1条用于团购显示的评论 start {
        $reviews_sql = tep_db_query('SELECT * FROM `reviews` r, `reviews_description` rd WHERE r.products_id="'.$group_buys_products['products_id'].'" AND rd.reviews_id = r.reviews_id AND is_top="1" AND reviews_status="1" limit 1 ');
        $reviews = tep_db_fetch_array($reviews_sql);
        if((int)$reviews['reviews_id']){
            $datas[$i]['reviews'] = $reviews;
            //第一个字加粗
            $db_charset = 'gb2312';
            $reviews_text = trim(strip_tags($reviews['reviews_text']));
            $datas[$i]['reviews']['reviews_text'] = '<b>'.mb_substr($reviews_text,0,1,$db_charset).'</b>'.mb_substr($reviews_text,1,mb_strlen($reviews_text,$db_charset),$db_charset);
            //评论更新时间
            $datas[$i]['reviews']['modified_date'] = chardate($reviews['last_modified'], "I", "1");
            //满意度
            $datas[$i]['reviews']['booking_ratings'] = get_ratings_datas($reviews['booking_rating']);
            $datas[$i]['reviews']['travel_ratings'] = get_ratings_datas($reviews['travel_rating']);
        }
        //载入1条用于团购显示的评论 end }

        //价格标签 start {
        $tax_rate_val_get = tep_get_tax_rate($group_buys_products['products_tax_class_id']);
        if ($new_price = tep_get_products_special_price($group_buys_products['products_id'])) {
            $products_price = $currencies->display_price($new_price, $tax_rate_val_get);
        } else {
            $products_price = $currencies->display_price($group_buys_products['products_price'], $tax_rate_val_get);
        }
        
        $datas[$i]['priceTag'] = preg_replace('/^(\$)([\d+,+]+)([\.\d+]+)$/','<span>$1</span><b>$2</b>',trim(strip_tags($products_price)));
        $datas[$i]['oldPrice'] = $currencies->display_price($group_buys_products['products_price'], $tax_rate_val_get);
        
        $oldPriceNum = tep_add_tax($group_buys_products['products_price'], $tax_rate_val_get);
        $newPriceNum = tep_add_tax($new_price, $tax_rate_val_get);
        $SaveNum = $oldPriceNum - $newPriceNum;
        
        $datas[$i]['Discount'] = round(($newPriceNum/$oldPriceNum)*100).'折';
        $datas[$i]['Save'] = $currencies->display_price($SaveNum,0);
        //价格标签 end }
        
        //出团时间
        $datas[$i]['display_start_days'] = html_to_db(strip_tags(tep_get_display_operate_info($group_buys_products['products_id'])));
        //$array_avaliabledate_store = get_avaliabledate($group_buys_products['products_id']);
        $array_avaliabledate_store = $mcache->product_departure_date($group_buys_products['products_id']);
        $array_avaliabledate_store = remove_soldout_dates($group_buys_products['products_id'], (array)$array_avaliabledate_store);
        array_multisort($array_avaliabledate_store,SORT_ASC);
        $datas[$i]['first_date'] = "";
        foreach((array)$array_avaliabledate_store as $key => $val){
            $first_date = date("Y-m-d H:i:s", strtotime(substr($val,0,10)));
            if($datas[$i]['first_date'] == "" && $first_date >= date('Y-m-d',strtotime($Today_date))){
                $datas[$i]['first_date'] = $first_date;
            }
            //最后一个出发日期
            $datas[$i]['last_departure_date'] = $first_date;
        }
        
        
        $datas[$i]['last_departure_date'] = preg_replace('/ \(.+$/','',$datas[$i]['last_departure_date']);
        $datas[$i]['last_departure_date'] = tep_get_date_disp($datas[$i]['last_departure_date']);
        //已订人数显示，计时结束时间 start {
        $TodayTime = strtotime($Today_date);
        
        $datas[$i]['emailTitle'] = $datas[$i]['display_start_days']."出发 ".$datas[$i]['products_name'];
        $datas[$i]['emailContent'] = "震撼团购，上线走四方。本行程原价".$datas[$i]['oldPrice']."，现价".strip_tags($datas[$i]['priceTag'])."，超大幅度优惠等你来团。";
        
        if($group_buys_products['specials_type']==1){	//1为倒数团
            $datas[$i]['purchasedNum'] = tep_get_product_orders_guest_count($group_buys_products['products_id'],$group_buys_products['start_date'],$group_buys_products['expires_date']);
            //如果已经人数少于20则在此基础加20人，相当于做广告
            if($datas[$i]['purchasedNum']<20){
                $datas[$i]['purchasedNum'] += $ban_purchasedNum[$group_buys_products['products_id']];
            }
            
            $datas[$i]['balanceNum'] = max(0,(int)($group_buys_products['specials_max_buy_num']-$datas[$i]['purchasedNum']));
            //如果产品部有手工更新则以产品部的设置为准
            if($datas[$i]['remaining_num']!=""){
                $datas[$i]['balanceNum'] = $datas[$i]['remaining_num'];
            }
            
            $datas[$i]['orderNumInfo'] = '限<b>'.$group_buys_products['specials_max_buy_num'].'</b>人&nbsp;&nbsp;还剩<b class="green">'.$datas[$i]['balanceNum'].'</b>人！';
            //倒数团根据能选择的最近出发日期和根据特价中设定的到期日的最小值来倒数
            $datas[$i]['CountdownEndTime'] = min((strtotime($datas[$i]['first_date'])+86400),strtotime($group_buys_products['expires_date'])) -$TodayTime;
            
        }elseif($group_buys_products['specials_type']==2){	//2为限时团
            $datas[$i]['purchasedNum'] = tep_get_product_orders_guest_count($group_buys_products['products_id'],'',$Today_date);
            //如果已经人数少于20则在此基础加20人，相当于做广告
            if($datas[$i]['purchasedNum']<20){
                $datas[$i]['purchasedNum'] += 20;
            }
            $datas[$i]['orderNumInfo'] = '<b>'.$datas[$i]['purchasedNum'].'</b>人已经成功预订！';
            //限时团根据特价中设定的到期日设置倒计时，如果特价中没设置到期日期则，根据能选择的最近出发日期来倒数
            $datas[$i]['CountdownEndTime'] = strtotime($group_buys_products['expires_date'])-$TodayTime;
            if(!(int)strtotime($group_buys_products['expires_date'])){
                $datas[$i]['CountdownEndTime'] = strtotime($datas[$i]['first_date'])+86400-$TodayTime;
            }
            $datas[$i]['emailTitle'] = "一年内有效 ".$datas[$i]['products_name'];
        }
        //已订人数显示，计时结束时间 end }
        
        
        $i++;
    }
    return $datas;
}
//函数处理 end }
    
    $group_split0 = array();
    $group_split1 = array();
    //限量团(由于美工没有设计模板，暂时未开放)
	//$datas0 = _getGroupBuyDatas(0);
    //限时团
	$datas1 = _getGroupBuyDatas(1);
    $page_links = $group_split1->display_links_2011(5);
/*
print_r($datas0);
echo "<hr>";
print_r($datas1);
exit;
*/
    //无团购产品时的处理方法{
    $noGroups = array();
    $noGroups['group_null_image'] = 'group_null_cn.jpg';
    if(strtolower(CHARSET)=='big5') $noGroups['group_null_image'] = 'group_null_tw.jpg';
    
    $expiredSql = tep_db_query('SELECT p.products_id, pd.products_name FROM `products` p, `products_description` pd, specials_group_buy_history sgbh WHERE pd.products_id = p.products_id AND sgbh.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND sgbh.specials_type>0 AND sgbh.expires_date <"'.$Today_date.'" Group By p.products_id Order By sgbh.issue_num DESC, p.products_id DESC Limit 4');
    while($expiredRows = tep_db_fetch_array($expiredSql)){
        $noGroups['expired_products'][]=array('products_id'=>$expiredRows['products_id'], 'name'=> strip_tags($expiredRows['products_name']), 'link'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$expiredRows['products_id']), 'link_target'=>"_blank");
    }
    
    //无团购产品时的处理方法}
    
    $validation_include_js = 'true';
}


$is_group_buy_page = true;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');

?>