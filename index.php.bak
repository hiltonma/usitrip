<?php
require('includes/application_top.php');

define('ShowMicroChannel', true);	//在首页和列表页显示微信

// 如果是签证域名访问，则301跳转到对应签证页 by lwkai 2012-05-25
if ( $_SERVER['HTTP_HOST'] == 'visa.usitrip.com') {
	$go_domain = substr(SCHINESE_HTTP_SERVER,-1) == '/' ? SCHINESE_HTTP_SERVER : SCHINESE_HTTP_SERVER . '/';
	header("HTTP/1.1 301 Moved Permanently");
   	header("Location: " . $go_domain . 'qianzheng/');	
	exit();
}
// 签证域名转向 结束


require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
$current_category_name=tep_get_categories_name($current_category_id);
$category_depth = 'top'; 
if (isset($cPath) && tep_not_null($cPath)) {
   $category_depth = 'products'; 
}
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
require(DIR_FS_LANGUAGES . $language . '/product_info.php' );


		/*$_sitem = $_SEARCH_DATA['Days'][$d];
		if($_sitem){
			search_addseo($_sitem['name']);
		}*/
		//===========================取得当前数据的所有持续时间===========================================}

if ($category_depth == 'products') {//产品列表页

	/*$category_query = tep_db_query("select cd.categories_id, cd.categories_top_banner_image_alt_tag, cd.categories_name, 
		 cd.categories_video, cd.categories_video_description, cd.categories_seo_description,
		 cd.categories_heading_title, cd.categories_description, cd.categories_recommended_tours, 
		 c.categories_recommended_tours_ids,  cd.categories_map, c.categories_image, c.categories_banner_image
		 from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd 
		 where c.categories_id = '" . $current_category_id . "' 
		 and cd.categories_id = '" . $current_category_id . "' 
		 and cd.language_id = '" . $languages_id . "'");
	$category = tep_db_fetch_array($category_query);*/	
	$category = MCache::fetch_categories($current_category_id);	
	//howard added meta for seo start
	//if($cat_mnu_sel!="tours"){
		$meta_array = tep_get_categories_header_tags_array($current_category_id, $cat_mnu_sel);
		if(tep_not_null($meta_array)){
			$the_title = db_to_html($meta_array['meta_title']);
			$the_key_words = db_to_html($meta_array['meta_keywords']);
			$the_desc = db_to_html($meta_array['meta_description']);
		}
		
		
	//}
	
/*	$pat_content = strip_tags($current_category_name);
	$add_key = tep_add_meta_keywords_from_thesaurus($pat_content, 1);
	if(is_array($add_key) && count($add_key)>0){
		$the_key_words .= ','.db_to_html(implode(',',$add_key));
	}	
*/	
	//howard added meta for seo end
	//补加Tab标签路径
	$add_other_breadcrumb=false;
	if($cat_mnu_sel == 'show'){
		$add_other_breadcrumb=true;
	}
	if($add_other_breadcrumb==true){
		if($cat_mnu_sel == 'show'){
			$breadcrumb_string = db_to_html('拉斯维加斯秀');
		}else if($cat_mnu_sel == 'diy'){
			$breadcrumb_string = db_to_html('夏威夷自助游');
		}else if($cat_mnu_sel == 'introduction'){
			$breadcrumb_string = TEXT_TAB_INTRODUCTION;
		}else if($cat_mnu_sel == 'tours'){
			$breadcrumb_string = TEXT_TAB_TOURS;
		}else if($cat_mnu_sel == 'vcpackages'){
			$breadcrumb_string = TEXT_TAB_VACATION_PACKAGES;
		}else if($cat_mnu_sel == 'recommended'){
			$breadcrumb_string = TEXT_TAB_RECOMMENDED;
		}else if($cat_mnu_sel == 'special'){
			$breadcrumb_string = TEXT_TAB_SPECIAL;
		}else if($cat_mnu_sel == 'maps'){
			$breadcrumb_string = TEXT_TAB_MAP;
		}else{
			$breadcrumb_string = TEXT_TAB_TOURS;
		}
		$breadcrumb->add($breadcrumb_string, '');
	}

	$BreadOff = true;
	$content = CONTENT_INDEX_PRODUCTS;
	$javascript = 'product_info.js.php';
	$js_get_parameters[] = 'content='.$content;
	
	//=====================dleno add prodcut list page -start=====================================================
	require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');
	$categories_search_link=tep_href_link(FILENAME_DEFAULT,'cPath='.$current_category_id.'&mnu='.$cat_mnu_sel);
	$categories_search_link = explode('?',$categories_search_link);
	$pageurl=$categories_search_link[0];
	$pagequery=$_SERVER['REQUEST_URI'];
	$pagequery=explode('?',$pagequery);
	$pagequery = $pagequery[1];
	
	//=====this page function :
	//构造搜索项URL
	function makesearchUrl($mod='',$val='',$dir=true){
		global $urlParme,$categories_search_link;
		$url = makeDirUrl($urlParme,$mod,$val,$dir);
		return $categories_search_link[0].$url.($categories_search_link[1]?'?'.$categories_search_link[1]:'');
	}
	//===========function end
	$openAjaxUrl = false;//是否启用ajax连接请求
	$lasVegas = false;//是否拉斯加斯秀
	$isHotels = false;	//是否是酒店列表
	$isCruises = false;	//是否是邮轮列表
	if(preg_match('/^182/',$cPath)){
		$isHotels = true;
	}
	if(preg_match('/^267/',$cPath)){
		$isCruises = true;
	}
	
	if(isCrawler()!=false){	//如果是搜索引擎则不用AJAX方式
		$openAjaxUrl = false;
	}
	$_schSlt = array();
	$ajaxTypename = '_search';
	$this_cate_ids = tep_get_category_subcategories_ids($current_category_id);//get cate's child id
	$cPathOnly=getcPathOnly($cPath);
	//====构造初始查询条件====
	$dataTables = TABLE_PRODUCTS_TO_CATEGORIES . " p2c," . TABLE_PRODUCTS . " p ";//要查询的常规表
	$dataWhere = " where 1 and p.products_status = '1' and p.products_id = p2c.products_id ";//要查询的常规条件
	if($cat_mnu_sel== 'special'){//特价行程查询所有
		if($current_category_id!=$cPathOnly)$top_cate_ids = tep_get_category_subcategories_ids($cPathOnly);
		else $top_cate_ids = $this_cate_ids;
		$dataWhere .= " and p2c.categories_id in (" . $top_cate_ids . ") ";
		unset($_GET['tc'],$_GET['vc'],$_GET['svc'],$_GET['m'],$_GET['d'],$_GET['shid'],$_GET['ssh']);//去除不需要的参数
		$_GET['of']=4;//设定固定条件
	}elseif($cat_mnu_sel == 'show' && $cPath=='24_32'){//拉斯加斯秀
		$lasVegas = true;
		$dataWhere .= " and p2c.categories_id in (" . $this_cate_ids . ") ";
		unset($_GET['tc'],$_GET['vc'],$_GET['svc'],$_GET['d'],$_GET['of']);//去除不需要的参数
	}else{
		$dataWhere .= " and p2c.categories_id in (" . $this_cate_ids . ") ";
		unset($_GET['shid'],$_GET['ssh']);//去除不需要的参数
	}
	//套餐条件
	if($cat_mnu_sel== 'vcpackages'){
		$dataWhere .= " and p.products_durations >= '".TOURS_PACKAGE_MIN_DAY_NUM."' and p.products_durations_type < '1' ";
	}else if($cat_mnu_sel== '' || $cat_mnu_sel== 'tours'){
	//短期条件
		$dataWhere .= " and ((p.products_durations < '".TOURS_PACKAGE_MIN_DAY_NUM."' and p.products_durations_type < '1' ) OR p.products_durations_type >= '1') ";
	}else{
		$dataWhere .= " ";
	}
	//========初始条件end
	//==================
	$showSearchOption =false;//是否启用搜索条件
	$showSearchOptionUL=false;//是否启用搜索条件选择UL
	if($cat_mnu_sel== 'vcpackages' || $cat_mnu_sel== 'tours' || ($cat_mnu_sel== 'special' && $_GET['of']=='4') || $lasVegas){
		$showSearchOption =true;
		if($cat_mnu_sel== 'vcpackages' || $cat_mnu_sel== 'tours' || $lasVegas)$showSearchOptionUL=true;
	}
	
	$urlParme=array();
	$urlParme['ic'] = $ic = $_GET['ic'];//邮轮名称
	$urlParme['fc'] = $fc = $_GET['fc'];//出发城市
	$urlParme['tc'] = $tc = $_GET['tc'];//目的景点
	$urlParme['hs'] = $hs = $_GET['hs'];//酒店星级
	$urlParme['hm'] = $hm = $_GET['hm'];//酒店餐食
	$urlParme['hi'] = $hi = $_GET['hi'];//酒店上网服务
	$urlParme['hl'] = $hl = $_GET['hl'];//酒店位置
	$urlParme['vc'] = $vc = $_GET['vc'];//途径景点
	$urlParme['m'] = $m = $_GET['m'];//价格
	$urlParme['d'] = $d = $_GET['d'];//持续时间
	$urlParme['of'] = $of = $_GET['of'];//优惠活动
	$urlParme['st'] = $st = $_GET['st'];//排序
	$urlParme['svc'] = $svc = $_GET['svc'];//更多途径景点
	$urlParme['shid'] = $shid = $_GET['shid'];//表演场地
	$urlParme['ssh'] = $ssh = $_GET['ssh'];//显示更多表演场地
	
	$pp = $_GET['pp'];//页数
	//=====================dleno add prodcut list page -end==================================================
	
	//============================为列表和筛选选项准备=============================================================={
	$now_date = date('Y-m-d');
	$expires_date = $now_date.date(' H:i:s');
	$expires_date = date('Y-m-d 00:00:00',strtotime($expires_date)+86400);
	//此为取价格列时需要的条件
	$specilWhere = " s.status='1' 
				AND (s.expires_date > '{$expires_date}' or s.expires_date is null or s.expires_date ='0000-00-00 00:00:00') ";
	//价格列
	$specilClunmsName = " round(IF({$specilWhere}, s.specials_new_products_price, p.products_price)/cur.value) ";
	
	$dataTableClunms = "p.is_visa_passport, p.products_id, pd.products_name,pd.products_small_description ,p.products_stock_status,
	
				   s.expires_date,s.status as s_status, s.specials_type as s_specials_type,
				   
				   pdrp.value as pdrp_value,pdrp.`people_number`,pdrp.`status` as pdrp_status,
				   pdrp.`products_departure_date_begin` as pdrp_pddb,pdrp.`products_departure_date_end` as pdrp_pdde,
				   pdrp.excluding_dates as pdrp_eld_date,
				   
				   pbgo.`one_or_two_option`,pbgo.`status` as pbgo_status,
				   pbgo.`products_departure_date_begin` as pbgo_pddb,pbgo.`products_departure_date_end` as pbgo_pdde,
				   pbgo.`excluding_dates` as pbgo_eld_date,
				   
				   p.products_image,  p.products_vacation_package, p.products_video, p.products_durations,p.display_room_option,
				   p.products_durations_type, p.products_durations_description, p.departure_city_id,p.departure_end_city_id, 
				   p.products_model,  p.products_is_regular_tour,  p.manufacturers_id, round(p.products_price/cur.value) as products_price, 
				   p.products_tax_class_id, p.products_class_id,p.tour_type_icon,p.use_buy_two_get_one_price,p.min_num_guest,p.is_transfer,
				   {$specilClunmsName} as final_price ";
	
	$dataWhere .= " and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' 
					and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code ";
				


	/*拉斯维加斯入住时长*/
	//if(isset($ls) && !empty($ls)){
			switch($ls){
				case 1:
					$dataWhere.="and pd.products_name like '%一晚%' ";
					break;
				case 2:
					$dataWhere.="and pd.products_name like '%两晚%' ";
					break;
				case 3:
					$dataWhere.="and pd.products_name like '%三晚%' ";
					break;
			}
	//}
	/*拉斯维加斯入住时长$dataWhere.="and pd.products_name like '%四晚%' ";*/

	



	if($isHotels){
		//========酒店产品
		require_once(DIR_FS_FUNCTIONS . 'hotels_functions.php');
		$dataTables .= " left join `hotel` h on h.products_id = p.products_id ";
		$dataWhere .= " ";
		$dataTableClunms .= ", h.hotel_stars, h.meals_id, h.internet_id, h.approximate_location_id, h.hotel_address, h.hotel_phone, h.hotel_map ";
		//========推荐的酒店Tab
		/*
		洛杉矶[1]：2000，889，1974，1977，1978
		纽约[66]：2043，893，1904，1973，892，2044，1906
		温哥华[270]：2090，2089，2088，2096
		旧金山[2]：1976，901
		*/
		if($cat_mnu_sel=="recommended"){
			$_recommended[1] = '2000,889,1974,1977,1978';
			$_recommended[2] = '1976,901';
			$_recommended[66] = '2043,893,1904,1973,892,2044,1906';
			$_recommended[270] = '2090,2089,2088,2096';
			$_inPid = implode(',',$_recommended);
			if(array_key_exists($fc, $_recommended )){
				$_inPid = $_recommended[$fc];
			}
			$dataWhere .= " and p.products_id IN(".$_inPid.") ";
		}
	}
	if($isCruises){
		//=======邮轮团
		require_once(DIR_FS_FUNCTIONS . 'cruises_functions.php');
		$dataTables .= " left join `products_to_cruises` ptc on ptc.products_id = p.products_id ";
		$dataWhere .= " ";
		$dataTableClunms .= ", ptc.cruises_id ";
		
	}
	if($lasVegas){
		//========拉斯加斯秀
		$dataTables .= " left join " . TABLE_SPECIALS . " s on s.products_id = p.products_id 
					left join products_buy_two_get_one pbgo on  pbgo.products_id = p.products_id 
					left join products_double_room_preferences pdrp on pdrp.products_id=p.products_id
					left join " . TABLE_MANUFACTURERS . " m on m.manufacturers_id = p.manufacturers_id,
					`products_show` ps," . TABLE_TRAVEL_AGENCY . " ta,". TABLE_PRODUCTS_DESCRIPTION . " pd ,".TABLE_CURRENCIES." cur ";
		$dataWhere .= " and p.products_stock_status ='1' and p.products_info_tpl ='product_info_vegas_show' 
						and ps.products_id=p.products_id ";
		$dataTableClunms .= ", ps.products_hotel_id, ps.min_watch_age";
	}else{
		$dataTables .= " left join " . TABLE_SPECIALS . " s on s.products_id = p.products_id 
					left join products_buy_two_get_one pbgo on  pbgo.products_id = p.products_id 
					left join products_double_room_preferences pdrp on pdrp.products_id=p.products_id,
					" . TABLE_TRAVEL_AGENCY . " ta, ". TABLE_PRODUCTS_DESCRIPTION . " pd ,".TABLE_CURRENCIES." cur ";
	}
	
	
	$dataOrder = "";
	if($showSearchOption){
		$fc && $dataWhere .= " and CONCAT(',', p.departure_city_id, ',') like '%,".addslashes($fc).",%' ";
		$tc && $dataWhere .= " and CONCAT(',', p.departure_end_city_id, ',') like '%,".addslashes($tc).",%' ";
		$shid && $dataWhere .= " and ps.products_hotel_id='{$shid}' ";
		
		//取得默认排序
// 		$dataOrder = tep_get_products_list_default_order_by_from_city($fc, $vc);
		
		//优先排序开始{
		require 'admin/includes/classes/ProductShowOrderBy.class.php';
		$end_place=$tc?$tc:$vc;
		$product_show_order_by=new ProductShowOrderBy($current_category_id, $fc, $end_place,$d, $w,$cat_mnu_sel);
		$ids_str=$product_show_order_by->getShowId();
		if($ids_str){
			$tmp_o=explode(',', $ids_str['products_ids']);
			krsort($tmp_o);
			$ids_str=join(',', $tmp_o);
		$dataOrder = ' ORDER BY p.products_stock_status DESC,find_in_set(p.products_id,"'.$ids_str.'")DESC';
// 		$dataOrder = ' ORDER BY p.products_stock_status DESC,field(p.products_id,'.$ids_str.')';
		}
			////优先排序结束}
		if($vc){
			$dataWhere .= " and p.products_id = pdt.products_id and pdt.city_id='{$vc}' ";
			$dataTables .= ",products_destination pdt "; 
		}
		if($isHotels){
			if((int)$hs){
				$dataWhere .= " and h.hotel_stars=".(int)$hs." ";
			}
			if((int)$hm){
				$dataWhere .= " and h.meals_id=".(int)$hm." ";
			}
			if((int)$hi){
				$dataWhere .= " and h.internet_id=".(int)$hi." ";
			}
			if((int)$hl){
				$dataWhere .= " and h.approximate_location_id=".(int)$hl." ";
			}
		}
		if($isCruises){
			if((int)$ic){
				$dataWhere .= " and ptc.cruises_id=".(int)$ic." ";
			}
		}
		
		
		if($lasVegas){
			switch($m){
				case 1:
					$dataWhere .= " and {$specilClunmsName} < '100' ";
					break;
				case 2:
					$dataWhere .= " and {$specilClunmsName} >= '100' and {$specilClunmsName} <= '200' ";
					break;
				case 3:
					$dataWhere .= " and {$specilClunmsName} >= '201' and {$specilClunmsName} <= '400' ";
					break;
				case 4:
					$dataWhere .= " and {$specilClunmsName} >= '401' and {$specilClunmsName} <= '600' ";
					break;
				case 5:
					$dataWhere .= " and {$specilClunmsName} > '600' ";
					break;
			}
		}else{
			switch($m){
				case 1:
					$dataWhere .= " and {$specilClunmsName} < '100' ";
					break;
				case 2:
					$dataWhere .= " and {$specilClunmsName} >= '100' and {$specilClunmsName} <= '200' ";
					break;
				case 3:
					$dataWhere .= " and {$specilClunmsName} >= '201' and {$specilClunmsName} <= '500' ";
					break;
				case 4:
					$dataWhere .= " and {$specilClunmsName} >= '501' and {$specilClunmsName} <= '1000' ";
					break;
				case 5:
					$dataWhere .= " and {$specilClunmsName} >= '1001' and {$specilClunmsName} <= '2000' ";
					break;
				case 6:
					$dataWhere .= " and {$specilClunmsName} >'2000' ";
					break;
			}
		}
		switch($d){ //持续时间
			case 1:
				$dataWhere .= " and (p.products_durations_type >= '1' || (p.products_durations ='1' and  p.products_durations_type < 1) ) ";	//1天以内
				break;
			case 2:
				$dataWhere .= " and (p.products_durations >= '2' and p.products_durations < '3' and p.products_durations_type < 1) "; //2天
				break;
			case 3:
				$dataWhere .= " and (p.products_durations >= '3' and p.products_durations < '4' and p.products_durations_type < 1) ";	//3天
				break;
			case 4:
				$dataWhere .= " and (p.products_durations >= '4' and p.products_durations < '5' and p.products_durations_type < 1) "; //4天
				break;
			case 5:
				$dataWhere .= " and (p.products_durations >= '5' and p.products_durations < '6' and p.products_durations_type < 1) "; 
				break;
			case 6:
				$dataWhere .= " and (p.products_durations >= '6'  and p.products_durations < '7' and p.products_durations_type < 1) "; 
				break;
			case 7:
				$dataWhere .= " and (p.products_durations >= '7' and p.products_durations < '8' and p.products_durations_type < 1) ";	
				break;
			case 8:
				$dataWhere .= " and (p.products_durations >= '8' and p.products_durations < '9' and p.products_durations_type < 1) "; 
				break;
			case 9:
				$dataWhere .= " and (p.products_durations >= '9' and p.products_durations < '10' and p.products_durations_type < 1) "; 
				break;
			case 10:
				$dataWhere .= " and (p.products_durations >= '10' and p.products_durations < '11' and p.products_durations_type < 1) "; 
				break;
			case 11:
				$dataWhere .= " and (p.products_durations >='11' and p.products_durations < '12' and p.products_durations_type < 1) ";	
				break;
			case 12://2-3 day
				$dataWhere .= " and (p.products_durations >= '2' and p.products_durations <= '3' and p.products_durations_type < 1) "; 
				break;
			case 13://4-5 day
				$dataWhere .= " and (p.products_durations >= '4' and p.products_durations <= '5' and p.products_durations_type < 1) "; 
				break;
			case 14://6-7 day
				$dataWhere .= " and (p.products_durations >= '6' and p.products_durations <= '7' and p.products_durations_type < 1) "; 
				break;
			case 15://8-9 day
				$dataWhere .= " and (p.products_durations >= '8' and p.products_durations <= '9' and p.products_durations_type < 1) "; 
				break;
			case 16://9 day
				$dataWhere .= " and (p.products_durations >='10' and p.products_durations_type < 1) ";	//10天及以上
				break;
		}
		switch($of){
			case 1://买2送1
				$dataWhere .= " and 
				(p.tour_type_icon like '%buy2-get-1%' 
				 or 
				(p.products_class_id = '4' and p.use_buy_two_get_one_price ='1' and pbgo.`status`='1' 
				AND (pbgo.one_or_two_option='1' OR pbgo.one_or_two_option='0')  
				AND (pbgo.products_departure_date_begin <= '{$now_date} 00:00:00' OR pbgo.products_departure_date_begin='0000-00-00 00:00:00' 
					 OR pbgo.products_departure_date_begin='') 
				AND (pbgo.products_departure_date_end >='{$now_date} 23:59:59' 
					 OR pbgo.products_departure_date_end='0000-00-00 00:00:00' OR pbgo.products_departure_date_end='' ) 
				and (pbgo.excluding_dates='' OR pbgo.excluding_dates not like '%{$now_date}%')) 
				) ";
				break;
			case 2://买2送2
				$dataWhere .= " and 
				(p.tour_type_icon like '%buy2-get-2%' 
				 or 
				(p.products_class_id = '4' and p.use_buy_two_get_one_price ='1' and pbgo.`status`='1' 
				AND (pbgo.one_or_two_option='2' OR pbgo.one_or_two_option='0')  
				AND (pbgo.products_departure_date_begin <= '{$now_date} 00:00:00' OR pbgo.products_departure_date_begin='0000-00-00 00:00:00' 
					 OR pbgo.products_departure_date_begin='') 
				AND (pbgo.products_departure_date_end >='{$now_date} 23:59:59' 
					 OR pbgo.products_departure_date_end='0000-00-00 00:00:00' OR pbgo.products_departure_date_end='' ) 
				and (pbgo.excluding_dates='' OR pbgo.excluding_dates not like '%{$now_date}%')) 
				) ";
				break;
			case 3://双人折扣
				$dataWhere .= " and 
				(p.tour_type_icon like '%2-pepole-spe%' 
				 or 
				(pdrp.`status`='1'  AND pdrp.people_number='2'
				AND (pdrp.products_departure_date_begin <= '{$now_date} 00:00:00' OR pdrp.products_departure_date_begin='0000-00-00 00:00:00' 
					 OR pdrp.products_departure_date_begin='') 
				AND (pdrp.products_departure_date_end >='{$now_date} 23:59:59' 
					 OR pdrp.products_departure_date_end='0000-00-00 00:00:00' OR pdrp.products_departure_date_end='') 
				and (pdrp.excluding_dates='' OR pdrp.excluding_dates not like '%{$now_date}%')) 
				) ";
				break;
			case 4://特价
				$dataWhere .= " and (p.tour_type_icon like '%specil-jia%' or ({$specilWhere})) ";
				break;
			case 5://低价保证
				$dataTables .= ",configuration conf";
				$dataWhere .= " and conf.configuration_key='LOW_PRICE_GUARANTEE_PRODUCTS' and FIND_IN_SET(p.products_id,conf.configuration_value) ";
				break;
		}
		switch($st){
			case 'p_d'://价格降序
				$dataOrder = " order by final_price DESC,p.products_last_modified DESC ";
				break;
			case 'p_a'://价格升序
				$dataOrder = " order by final_price ,p.products_last_modified DESC ";
				break;
			case 'o_d'://热销降序
				$dataOrder = " order by p.products_ordered DESC,p.products_last_modified DESC ";
				break;
			case 'o_a'://热销升序
				$dataOrder = " order by p.products_ordered ,p.products_last_modified DESC ";
				break;
			case 'd_d'://持续时间降序
				$dataOrder = " order by p.products_durations_type,products_durations DESC ";
				break;
			case 'd_a'://持续时间升序
				$dataOrder = " order by p.products_durations_type DESC ,p.products_durations ";
				break;
			default:
				if(!tep_not_null($dataOrder)){
					$dataOrder = tep_get_products_list_default_order_by($cPath, $cat_mnu_sel);
					if($dataOrder == ''){
						$dataOrder = " ORDER BY p.products_stock_status DESC, p2c.products_sort_order DESC, p.products_ordered DESC, p.products_last_modified DESC ";
					}
				}
				break;
		}
	}

	if($isHotels!==true){	//在非酒店目录下面不能有酒店
		$dataWhere.= ' AND p.is_hotel<1 ';
	}
	$querySQL = "select {$dataTableClunms} from {$dataTables} {$dataWhere} group by p.products_id {$dataOrder}";
	//============================为列表和筛选选项准备==============================================================}

	//==========================SEO & search mode Start====================================================================

	if(true){
		//==========================================================
		//所有出发城市
		$_SEARCH_DATA['From_City'] = array();
		if(!tep_not_null($top_cate_ids)){
			if($current_category_id==$cPathOnly){
				if(tep_not_null($this_cate_ids)){
					$top_cate_ids = $this_cate_ids;
				}else{
					$top_cate_ids = tep_get_category_subcategories_ids($current_category_id);
				}
			}else{
				$top_cate_ids = tep_get_category_subcategories_ids($cPathOnly);
			}
		}
		
		$categories_more_city = array();
		if(tep_not_null($top_cate_ids)){
			$depature_city_ids='';
			$query = tep_db_query("select  p.departure_city_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where p.products_status = '1' and p.products_id = p2c.products_id  and p2c.categories_id in (" . $top_cate_ids . ") group by p.departure_city_id");
			while($row = tep_db_fetch_array($query)){		 
				$depature_city_ids .= "".$row['departure_city_id'].",";
			}
			//$depature_city_ids = substr($depature_city_ids, 0, -1);
			$depature_city_ids = trim($depature_city_ids,',');
			$depature_city_ids = $depature_city_ids ? $depature_city_ids : "0";
			$sql = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in (".$depature_city_ids.") AND departure_city_status = '1' AND `is_attractions` !='1' order by city");
			while($row = tep_db_fetch_array($sql)){
				$categories_more_city[] =$row;
			}
			foreach($categories_more_city as $key=>$row){
				$name = db_to_html(preg_replace('/ .+/','',$row['city']));
				$_sch_temp=array(
						'id'=>$row['city_id'],
						'name'=>$name
				);
				$fc && $_sch_temp['id']==$fc && $_schSlt['fc'] = $_sch_temp;
				$_SEARCH_DATA['From_City'][$row['city_id']]=$_sch_temp;
			}
		}
	}
	if($showSearchOptionUL){
		if($lasVegas){
			//==========================================================
			$_SEARCH_DATA['SHotel']=array();
			$query = tep_db_query("SELECT ps.products_hotel_id as id, pd.products_name as name FROM `products_show` ps, `products_description` pd WHERE pd.products_id = ps.products_hotel_id AND language_id ='{$languages_id}' AND ps.products_hotel_id > '0' Group By ps.products_hotel_id");
			while($row = tep_db_fetch_array($query)){
				$row['name']= db_to_html($row['name']);
				$_SEARCH_DATA['SHotel'][$row['id']] =$row;
			}
			$_sitem = $_SEARCH_DATA['SHotel'][$shid];
			if($_sitem){
				$_sitem['name'] = explode('/',$_sitem['name']);
				$_sitem['name'] = $_sitem['name'][0];
				search_addseo(db_to_html('表演场地：').$_sitem['name']);
			}
			//==========================================================
			$_SEARCH_DATA['Price']=array();
			$_SEARCH_DATA['Price'][0]=array('id'=>'','name'=>db_to_html('不限'));
			$_SEARCH_DATA['Price']['1']=array('id'=>'1','name'=>db_to_html('$100以内'));
			$_SEARCH_DATA['Price']['2']=array('id'=>'2','name'=>db_to_html('$101-$200'));
			$_SEARCH_DATA['Price']['3']=array('id'=>'3','name'=>db_to_html('$201-$400'));
			$_SEARCH_DATA['Price']['4']=array('id'=>'4','name'=>db_to_html('$401-$600'));
			$_SEARCH_DATA['Price']['5']=array('id'=>'5','name'=>db_to_html('$600以上'));
			
			$_sitem = $_SEARCH_DATA['Price'][$m];
			if($_sitem){
				search_addseo($_sitem['name']);
			}
			//==========================================================
		}else{
			//==========================================================
			//出发城市
			//if($current_category_id!=$cPathOnly){//以出发城市查看时不显示
				//if($cPathOnly!=$cPath){//选择了某个景点时，查询出只属于该景点的出发城市
					$depature_city_ids=array();
					$query = tep_db_query("select p.departure_city_id from {$dataTables} {$dataWhere} group by p.departure_city_id");
					while($row = tep_db_fetch_array($query)){
						if($row['departure_city_id']){
							$row['departure_city_id'] = explode(',',$row['departure_city_id']);
							foreach($row['departure_city_id'] as $v){
								$depature_city_ids[$v]= $v;
							}
						}
					}
					$_SEARCH_DATA['From_City']=array();
					
					if(!tep_not_null($depature_city_ids)){ $depature_city_ids[0] = (int)$fc; }
					
					if(count($depature_city_ids)){
						$depature_city_ids = "'".join("','",$depature_city_ids)."'";
						$depature_city_ids = trim($depature_city_ids,',');
						$query = tep_db_query("select city_id as id, city as name from " . TABLE_TOUR_CITY . " 
								where city_id in (".$depature_city_ids.") AND departure_city_status = '1' 
								AND `is_attractions` !='1' group by city");
						while($row = tep_db_fetch_array($query)){
							$row['name']= db_to_html($row['name']);
							$_SEARCH_DATA['From_City'][$row['id']] =$row;
						}
					}
				//}
			//}
			$_sitem = $_SEARCH_DATA['From_City'][$fc];
			if($_sitem){
				search_addseo($_sitem['name'].db_to_html('出发'));
			}
			//===========================取得当前数据的所有目的城市============================================={
			$departure_end_city_ids=array();
			$query = tep_db_query("select  p.departure_end_city_id  from {$dataTables} {$dataWhere} group by p.departure_end_city_id");
			while($row = tep_db_fetch_array($query)){		 
				if($row['departure_end_city_id']){
					$row['departure_end_city_id'] = explode(',',$row['departure_end_city_id']);
					foreach($row['departure_end_city_id'] as $v){
						$departure_end_city_ids[$v]= $v;
					}
				}
			}
			$_SEARCH_DATA['To_City']=array();
			if(count($departure_end_city_ids)){
				$departure_end_city_ids = "'".join("','",$departure_end_city_ids)."'";
				$query = tep_db_query("select c.city_id as id, c.city as name from 
						" . TABLE_TOUR_CITY . " c," . TABLE_ZONES . " z," . TABLE_COUNTRIES . " as co
						where c.city_id in (".$departure_end_city_ids.")  and c.state_id = z.zone_id
						and z.zone_country_id = co.countries_id
						group by c.city_id
						order by c.city_id desc");
				while($row = tep_db_fetch_array($query)){
					$row['name']= db_to_html($row['name']);
					$_SEARCH_DATA['To_City'][$row['id']] =$row;
				}
			}
			$_sitem = $_SEARCH_DATA['To_City'][$tc];
			if($_sitem){
				search_addseo(db_to_html('到').$_sitem['name']);
			}
			//===========================取得当前数据的所有目的城市=============================================}
			//===========================取得当前数据的所有途经景点============================================={
			//if($current_category_id==$cPathOnly){
				//只列出本区域的途经景点，比如美东下面不能出现旧金山的景点等，以下是目录与区域的ID对应数据
				$category2region = array('24'=>'1', '25'=>'2', '33'=>'6','54'=>'19','157'=>'21,22,24,25,26,27,29,39,42,43,44,53');
				if(tep_not_null($category2region[$cPathOnly])){
					$departure_via_city_ids='0';
					if(stripos($dataTables,'products_destination')===false){
						$query = tep_db_query("select DISTINCT pdt.city_id  from {$dataTables}, products_destination pdt {$dataWhere} and  p.products_id = pdt.products_id ");
					}else{
						$query = tep_db_query("select DISTINCT pdt.city_id  from {$dataTables} {$dataWhere} ");
					}
					while($row = tep_db_fetch_array($query)){		 
						$departure_via_city_ids .= ",".$row['city_id'];
					}
					//$departure_via_city_ids = substr($departure_via_city_ids, 0, -1);
					$_SEARCH_DATA['Via_City']=array();
					$query = tep_db_query("select c.city_id as id, c.city as name from 
							" . TABLE_TOUR_CITY . " c," . TABLE_ZONES . " z," . TABLE_COUNTRIES . " as co
							where c.city_id in (".$departure_via_city_ids.")  and c.state_id = z.zone_id
							and z.zone_country_id = co.countries_id and c.regions_id in(".$category2region[$cPathOnly].")
							group by c.city_id
							order by c.city_id desc");
					while($row = tep_db_fetch_array($query)){
						$row['name']= db_to_html($row['name']);
						$_SEARCH_DATA['Via_City'][$row['id']] =$row;
					}
					$_sitem = $_SEARCH_DATA['Via_City'][$vc];
					if($_sitem){
						search_addseo($_sitem['name']);
					}
				}
			//}
			//===========================取得当前数据的所有途经景点=============================================}
			//===========================取得当前数据的所有价格==============================================={
			$_SEARCH_DATA['Price']=array();
			$priceSQL = "SELECT DISTINCT round(IF(s.status='1' AND (s.expires_date>'".$expires_date."' or s.expires_date is null or s.expires_date='0000-00-00 00:00:00'), s.specials_new_products_price, p.products_price)/cur.value) as price FROM {$dataTables} {$dataWhere} ORDER BY price ASC ";
			$priceQuery = tep_db_query($priceSQL);
			$_SEARCH_DATA['Price'][0]=array('id'=>'','name'=>db_to_html('不限'));
			while($priceRows = tep_db_fetch_array($priceQuery)){
				if($priceRows['price']<=100){
					$_SEARCH_DATA['Price']['1']=array('id'=>'1','name'=>db_to_html('$100以内'));
					continue;
				}
				if($priceRows['price']>=101 && $priceRows['price']<=200){
					$_SEARCH_DATA['Price']['2']=array('id'=>'2','name'=>db_to_html('$101-$200'));
					continue;
				}
				if($priceRows['price']>=201 && $priceRows['price']<=500){
					$_SEARCH_DATA['Price']['3']=array('id'=>'3','name'=>db_to_html('$201-$500'));
					continue;
				}
				if($priceRows['price']>=501 && $priceRows['price']<=1000){
					$_SEARCH_DATA['Price']['4']=array('id'=>'4','name'=>db_to_html('$501-$1000'));
					continue;
				}
				if($priceRows['price']>=1001 && $priceRows['price']<=2000){
					$_SEARCH_DATA['Price']['5']=array('id'=>'5','name'=>db_to_html('$1001-$2000'));
					continue;
				}
				if($priceRows['price']>2000 ){
					$_SEARCH_DATA['Price']['6']=array('id'=>'6','name'=>db_to_html('$2000以上'));
					continue;
				}
			}
			
			$_sitem = $_SEARCH_DATA['Price'][$m];
			if($_sitem){
				search_addseo($_sitem['name']);
			}
			//===========================取得当前数据的所有价格===============================================}
			//===========================取得当前数据的所有持续时间==========================================={
			$_SEARCH_DATA['DaysItem']=array();
			$daysSQL = "SELECT DISTINCT IF(p.products_durations_type='0' AND p.products_durations>0, p.products_durations, round((p.products_durations/24),2) ) as days FROM {$dataTables} {$dataWhere} ORDER BY days ASC ";
			$daysQuery = tep_db_query($daysSQL);
			$_SEARCH_DATA['DaysItem'][0]=array('id'=>'','name'=>db_to_html('不限'));
			while($daysRows = tep_db_fetch_array($daysQuery)){
				if($daysRows['days']<=1){
					$_SEARCH_DATA['DaysItem']['1']=array('id'=>'1','name'=>db_to_html('1天以内'));
					continue;
				}
	
				if($daysRows['days']>=2 && $daysRows['days']<3 ){
					$_SEARCH_DATA['DaysItem']['2']=array('id'=>'2','name'=>db_to_html('2天'));
					continue;
				}
	
				if($daysRows['days']>=3 && $daysRows['days']<4 ){
					$_SEARCH_DATA['DaysItem']['3']=array('id'=>'3','name'=>db_to_html('3天'));
					continue;
				}
				if($daysRows['days']>=4 && $daysRows['days']<5 ){
					$_SEARCH_DATA['DaysItem']['4']=array('id'=>'4','name'=>db_to_html('4天'));
					continue;
				}
				if($daysRows['days']>=5 && $daysRows['days']<6 ){
					$_SEARCH_DATA['DaysItem']['5']=array('id'=>'5','name'=>db_to_html('5天'));
					continue;
				}
	
				if($daysRows['days']>=6 && $daysRows['days']<7 ){
					$_SEARCH_DATA['DaysItem']['6']=array('id'=>'6','name'=>db_to_html('6天'));
					continue;
				}
	
				if($daysRows['days']>=7 && $daysRows['days']<8 ){
					$_SEARCH_DATA['DaysItem']['7']=array('id'=>'7','name'=>db_to_html('7天'));
					continue;
				}
	
				if($daysRows['days']>=8 && $daysRows['days']<9 ){
					$_SEARCH_DATA['DaysItem']['8']=array('id'=>'8','name'=>db_to_html('8天'));
					continue;
				}
				if($daysRows['days']>=9 && $daysRows['days']<10 ){
					$_SEARCH_DATA['DaysItem']['9']=array('id'=>'9','name'=>db_to_html('9天'));
					continue;
				}
				if($daysRows['days']>=10 && $daysRows['days']<11 ){
					$_SEARCH_DATA['DaysItem']['10']=array('id'=>'10','name'=>db_to_html('10天'));
					continue;
				}
				if($daysRows['days']>=11 && $daysRows['days']<12 ){
					$_SEARCH_DATA['DaysItem']['11']=array('id'=>'11','name'=>db_to_html('11天'));
					continue;
				}
			}
			if (array_key_exists('2',$_SEARCH_DATA['DaysItem']) || array_key_exists('3',$_SEARCH_DATA['DaysItem'])) {
				$_SEARCH_DATA['DaysItem']['12'] = array('id' => '12', 'name' => db_to_html('2-3天'));
			}
			
			if (array_key_exists('4',$_SEARCH_DATA['DaysItem']) || array_key_exists('5',$_SEARCH_DATA['DaysItem'])) {
				$_SEARCH_DATA['DaysItem']['13'] = array('id' => '13', 'name' => db_to_html('4-5天'));
			}
			
			if (array_key_exists('6',$_SEARCH_DATA['DaysItem']) || array_key_exists('7',$_SEARCH_DATA['DaysItem'])) {
				$_SEARCH_DATA['DaysItem']['14'] = array('id' => '14', 'name' => db_to_html('6-7天'));
			}
			
			if (array_key_exists('8',$_SEARCH_DATA['DaysItem']) || array_key_exists('9',$_SEARCH_DATA['DaysItem'])) {
				$_SEARCH_DATA['DaysItem']['15'] = array('id' => '15', 'name' => db_to_html('8-9天'));
			}
			
			if (array_key_exists('10',$_SEARCH_DATA['DaysItem']) || array_key_exists('11',$_SEARCH_DATA['DaysItem'])) {
				$_SEARCH_DATA['DaysItem']['16'] = array('id' => '16', 'name' => db_to_html('10天及以上'));
			}
			$_sitem = $_SEARCH_DATA['DaysItem'][$d];
			if($_sitem){
				search_addseo($_sitem['name']);
			}
			//===========================取得当前数据的所有持续时间===========================================}
			//============================ 优惠活动 ==============================
			$_SEARCH_DATA['Offer']=array();
			$_SEARCH_DATA['Offer'][0]=array('id'=>'','name'=>db_to_html('全部'));
			$_SEARCH_DATA['Offer']['1']=array('id'=>'1','name'=>db_to_html('买2送1'));
			//$_SEARCH_DATA['Offer']['2']=array('id'=>'2','name'=>db_to_html('买2送2'));
			//$_SEARCH_DATA['Offer']['3']=array('id'=>'3','name'=>db_to_html('双人折扣'));
			//$_SEARCH_DATA['Offer']['4']=array('id'=>'4','name'=>db_to_html('特价'));
			$_SEARCH_DATA['Offer']['5']=array('id'=>'5','name'=>db_to_html('低价保证'));
			
			$_sitem = $_SEARCH_DATA['Offer'][$of];
			if($_sitem){
				search_addseo($_sitem['name']);
			}
			//==========================================================
		
			if($isHotels){
			//===========================酒店==========================================={
				//取得当前数据的所有酒店星级
				$_SEARCH_DATA['Hotel_Stars']=array();
				$_SEARCH_DATA['Hotel_Stars'][0]=array('id'=>'','name'=>db_to_html('全部'));
				$query = tep_db_query("select DISTINCT h.hotel_stars from {$dataTables} {$dataWhere} ");
				while($row = tep_db_fetch_array($query)){		 
					if((int)$row['hotel_stars']>2){
						$_SEARCH_DATA['Hotel_Stars'][$row['hotel_stars']]=array('id'=>$row['hotel_stars'],'name'=>$row['hotel_stars'].db_to_html('星'));
					}elseif((int)$row['hotel_stars']==2){
						$_SEARCH_DATA['Hotel_Stars'][$row['hotel_stars']]=array('id'=>$row['hotel_stars'],'name'=>db_to_html('经济型'));
					}
				}
				//餐食
				$_SEARCH_DATA['Hotel_Meals']=array();
				$_SEARCH_DATA['Hotel_Meals'][0]=array('id'=>'','name'=>db_to_html('全部'));
				$query = tep_db_query("select DISTINCT h.meals_id from {$dataTables} {$dataWhere} ");
				while($row = tep_db_fetch_array($query)){		 
					if((int)$row['meals_id']){
						$_SEARCH_DATA['Hotel_Meals'][$row['meals_id']]=array('id'=>$row['meals_id'],'name'=>db_to_html(getHotelMealsOptions($row['meals_id'])));
					}
				}
				//上网服务
				$_SEARCH_DATA['Hotel_Internet']=array();
				$_SEARCH_DATA['Hotel_Internet'][0]=array('id'=>'','name'=>db_to_html('全部'));
				$query = tep_db_query("select DISTINCT h.internet_id from {$dataTables} {$dataWhere} ");
				while($row = tep_db_fetch_array($query)){		 
					if((int)$row['internet_id']){
						$_SEARCH_DATA['Hotel_Internet'][$row['internet_id']]=array('id'=>$row['internet_id'],'name'=>db_to_html(getHotelInternetOptions($row['internet_id'])));
					}
				}
				//酒店位置
				$_SEARCH_DATA['Hotel_Locaction']=array();
				$_SEARCH_DATA['Hotel_Locaction'][0]=array('id'=>'','name'=>db_to_html('全部'));
				$query = tep_db_query("select DISTINCT h.approximate_location_id from {$dataTables} {$dataWhere} ");
				while($row = tep_db_fetch_array($query)){		 
					if((int)$row['approximate_location_id']){
						$_SEARCH_DATA['Hotel_Locaction'][$row['approximate_location_id']]=array('id'=>$row['approximate_location_id'],'name'=>db_to_html(getHotelApproximateLocation($row['approximate_location_id'])));
					}
				}
			//===========================酒店===========================================}
			}
			
			if($isCruises){
			//===========================邮轮==========================================={
				//取得当前数据的所有邮轮名称
				$_SEARCH_DATA['Cruises']=array();
				$_SEARCH_DATA['Cruises'][0]=array('id'=>'','name'=>db_to_html('全部'));
				$query = tep_db_query("select DISTINCT ptc.cruises_id from {$dataTables} {$dataWhere} ");
				while($row = tep_db_fetch_array($query)){		 
					if((int)$row['cruises_id']){
						$_SEARCH_DATA['Cruises'][$row['cruises_id']]=array('id'=>$row['cruises_id'],'name'=>db_to_html(getCruisesName($row['cruises_id'])));
					}
				}
			//===========================邮轮===========================================}
			}
		
		}
	}


	//==========================SEO & search mode End  ====================================================================
	$validation_include_js='true';
	$validation_div_span ='span';
} else {//首页

	require_once(DIR_FS_CLASSES . 'index.php');
	//黄石公园,洛杉矶,纽约,华盛顿,旧金山,拉斯维加斯,主题乐园,波士顿,檀香山,奥兰多
	$headerCategorys[35] = '';
	$headerCategorys[29] = '';
	$headerCategorys[55] = '';
	$headerCategorys[52] = '';
	$headerCategorys[30] = '';
	$headerCategorys[32] = '';
	$headerCategorys[41] = '';
	$headerCategorys[59] = '';
	$headerCategorys[307] = '';
	$headerCategorys[266] = '';
	
	
	
	
	
	$content = CONTENT_INDEX_DEFAULT;
	$other_css_base_name = 'index';
}
$Show_Calendar_JS = "true";

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>