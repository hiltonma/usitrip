<?php
require('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
require(DIR_FS_LANGUAGES . $language . '/product_info.php' );

//如果出发地是夏威夷则要转到夏威夷的目录 by Howard 2012-11-02
if($_GET['fcw']=='%CF%C4%CD%FE%D2%C4' || $_GET['fcw']=='夏威夷'){
	$go_url = tep_href_link(FILENAME_DEFAULT, 'cPath=33');
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $go_url);	
	exit;
}

//=====================dleno add prodcut list page -start=====================================================
	require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');
	//===============================================================
	if(intval($_GET['ms']) || intval($_GET['me'])){
		unset($_GET['m']);//输入价格范围时不使用价格选项条件
	}
	$urlParme=array();
	$_GET['fcw'] = tep_db_input($_GET['fcw']);
	if ($_GET['fcw'] == db_to_html('输入出发地')) $_GET['fcw'] = '';
	$_GET['tcw'] = tep_db_input($_GET['tcw']);
	if ($_GET['tcw'] == db_to_html('输入目的地')) $_GET['tcw'] = '';
	if ($_GET['w'] == db_to_html('请输入关键字')) $_GET['w'] = '';
	// 下面不知道哪清掉了这个GET参数，在这里用另一个变量保存下来。by lwkai add 2012-11-6 11:34
	if ($_GET['u'] == 'true') {
		$_GET['fcw'] = rawurlencode(iconv('utf-8',CHARSET . '//IGNORE',rawurldecode($_GET['fcw'])));
		$_GET['tcw'] = rawurlencode(iconv('utf-8',CHARSET . '//IGNORE',rawurldecode($_GET['tcw'])));
		$_GET['w']   = rawurlencode(iconv('utf-8',CHARSET . '//IGNORE',rawurldecode($_GET['w'])));
		
	}
	//格式化一下代码
	$_GET['fcw'] = (string)$_GET['fcw'];
	$_GET['tcw'] = (string)$_GET['tcw'];
	$_GET['w'] = (string)$_GET['w'];
	
	$js_save_cookie = 'fcw:'.rawurldecode($_GET['fcw']).',tcw:'.rawurldecode($_GET['tcw']).',d:'.rawurldecode($_GET['d']).',w:'.rawurldecode($_GET['w']);
	$_GET['fcw'] = rawurldecode($_GET['fcw']);
	$_GET['tcw'] = rawurldecode($_GET['tcw']);
	$_GET['w'] = rawurldecode($_GET['w']);
	
	/* 自定义SEO关键字 */
	$lwk_d = (empty($_GET['d']) ? '' : $_SEARCH_DATA['Days'][$_GET['d']]['name']);
	$lwk_fcw = (empty($_GET['fcw']) ? '' : $_GET['fcw']);
	$lwk_w = (empty($_GET['w']) ? '' : $_GET['w']);
	$lwk_tcw = (empty($_GET['tcw']) ? '' : $_GET['tcw']);

	$the_title = (empty($lwk_fcw) ? '' : $lwk_fcw . db_to_html('出发')) . 
				 (empty($lwk_tcw) ? '' : db_to_html('到') . $lwk_tcw) . 
				 (empty($lwk_d) ? '' : db_to_html($lwk_d)) . 
				 db_to_html('旅游线路推荐-度假行程安排攻略-美国华人旅行社-usitrip走四方旅游网');
	$the_key_words = (empty($lwk_fcw) ? '' : $lwk_fcw) . db_to_html('旅游线路，') . (empty($lwk_tcw) ? '' : $lwk_tcw) . db_to_html('旅游攻略');
	$_des = (empty($lwk_fcw) ? '' : $lwk_fcw) . (empty($lwk_tcw) ? '' : $lwk_tcw);
	$the_desc = db_to_html('Usitrip走四方旅游网身为最知名华人旅行社,为全球华人量身定制') . $_des . db_to_html('旅游线路, ') . $_des . db_to_html('旅行团结伴同游, ') . $_des . db_to_html('旅行旅游团价格报价,') . (empty($lwk_tcw) ? '' : $lwk_tcw) . db_to_html('旅游酒店机票等服务信息.');
	
	
	
	
	
	$urlParme['cid'] = $cid = $_GET['cid'];//地理位置cid
	
	$urlParme['fcs'] = $fcs = $_GET['fcs'];//更多出发城市
	$urlParme['fcw'] = $fcw = html_to_db($_GET['fcw']);//出发城市
	if(!tep_not_null($_GET['fc'])){	//在没有选择城市ID的情况下，如果出发地字符变量中有包括出发城市的情况就默认当前选中的城市ID为该字符串对应的城市ID
		$_GET['fc'] = tep_get_city_id($fcw);
		unset($urlParme['fcw']); unset($fcw); unset($_GET['fcw']); 
	}
	$urlParme['fc'] = $fc = $_GET['fc'];//出发城市id
	
	$fcw=='输入出发地' && $urlParme['fcw'] = $fcw = $_GET['fcw'] = '';
	$urlParme['tc'] = $tc = $_GET['tc'];//目的景点id
	$urlParme['tcs'] = $tcs = $_GET['tcs'];//更多目的景点
	
	$urlParme['tcw'] = $tcw = html_to_db($_GET['tcw']);//目的景点
	
	$urlParme['fc'] = $fc = $_GET['fc'];//出发城市id
	$tcw=='输入目的地' && $urlParme['tcw'] = $tcw = $_GET['tcw'] = '';
	
	if(!tep_not_null($_GET['vc'])){
		$_GET['vc'] = tep_get_city_id($tcw);
		unset($urlParme['tcw']); unset($tcw); unset($_GET['tcw']); 
	}

	$urlParme['vc'] = $vc = $_GET['vc'];//途径景点
	$urlParme['svc'] = $svc = $_GET['svc'];//更多途径景点
	
	$urlParme['m'] = $m = strip_tags($_GET['m']);//价格
	$urlParme['ms'] = $ms = strip_tags($_GET['ms']);//价格开始
	$urlParme['me'] = $me = strip_tags($_GET['me']);//价格结束
	$urlParme['dep_date0'] = $dep_date0 = strip_tags($_GET['dep_date0']);//出发日期
	$urlParme['d'] = $d = strip_tags($_GET['d']);//持续时间
	$urlParme['of'] = $of = strip_tags($_GET['of']);//优惠活动
	$urlParme['st'] = $st = strip_tags($_GET['st']);//排序
	
	//$_GET['w'] = tep_db_prepare_input($_GET['w']);
	$_GET['w'] = tep_db_input(tep_db_prepare_input($_GET['w']));
	$w = strip_tags(html_to_db($_GET['w']));//关键字
	$w = str_replace(',',' ',$w); //去掉逗号这些无用的东西
	$w = preg_replace('/[[:space:]]+/',' ',$w);
	$w = trim($w);
	//=====如关键词太多需要限制不能超过5个================
	$substrKeyWords = explode(' ',$w);
	$masKeyNum = 5;
	$excessKeyWordsTips = NULL;
	if(sizeof($substrKeyWords)>$masKeyNum){
		$excessKeyWordsTips = db_to_html("提示：“{$substrKeyWords[$masKeyNum]}”及其后面的关键词均被忽略，因为走四方网的查询限制在{$masKeyNum}个关键词以内！");
		$w = implode(' ',array_slice($substrKeyWords, 0, $masKeyNum));
	}
	$urlParme['w'] = $w;
	
	$pp = $_GET['pp'];//页数
	//===============================================================
	//=====this page function :
	//构造搜索项URL
	function makesearchUrl($mod='',$val='',$dir=false){
		global $urlParme,$categories_search_link;
		$url = makeDirUrl($urlParme,$mod,$val,$dir);
		return tep_href_link('advanced_search_result.php',$url);
	}
	//===========function end
	$_schSlt = array();
	$ajaxTypename = '_s_search';
	
	//=====================dleno add prodcut list page -end==================================================
	$navigation_title='';
	$w = trim($w);
	if($w!=''){
		//$navigation_title.="<i>关键词：</i><span>".str_replace(' ','<font color="#CCC">OR</font>',$w)."</span>&nbsp;&nbsp;";
		$navigation_title.="<i>关键词：</i><span>".stripslashes($w)."</span>&nbsp;&nbsp;";
	}
	if(trim($fcw)!=''){
		$navigation_title.="<i>出发城市：</i><span>{$fcw}</span>&nbsp;&nbsp;";
	}
	if(trim($tcw)!=''){
		$navigation_title.="<i>目的景点：</i><span>{$tcw}</span>&nbsp;&nbsp;";
	}
	if(intval($ms)>0||intval($me)>0){
		$navigation_title .= "<i>价格：</i>";
		$navigation_title .= intval($ms)>0?"<span>\${$ms}</span>":"<span>?</span>";
		$navigation_title .= "&nbsp;-&nbsp;";
		$navigation_title .= intval($me)>0?"<span>\${$me}</span>":"<span>?</span>";
		$navigation_title.="&nbsp;&nbsp;";
	}
	
	if(tep_not_null($dep_date0)){
		$dep_date0 = date("Y-m-d",strtotime($dep_date0));
		$navigation_title .= "<i>出发时间：</i>";
		$navigation_title .= '<span>'.$dep_date0.'</span>';
		$navigation_title.="&nbsp;&nbsp;";
	}
	

$now_date = date('Y-m-d');
$expires_date = $now_date.date(' H:i:s');
$expires_date = date('Y-m-d 00:00:00',strtotime($expires_date)+86400);
//此为取价格列时需要的条件
$specilWhere = "s.status='1' 
			AND (s.expires_date>'{$expires_date}' or s.expires_date is null or s.expires_date='0000-00-00 00:00:00')";
//价格列
$specilClunmsName = "round(IF({$specilWhere}, s.specials_new_products_price, p.products_price)/cur.value)";

$dataTableClunms = "p.is_hotel,p.is_visa_passport, p.products_id, p.products_stock_status, 

				pd.products_name, pd.products_small_description, 
				
				s.expires_date, s.status as s_status, s.specials_type as s_specials_type, s.specials_new_products_price, 
				
				pdrp.value as pdrp_value, pdrp.people_number, pdrp.status as pdrp_status, pdrp.products_departure_date_begin as pdrp_pddb, 
				pdrp.products_departure_date_end as pdrp_pdde, pdrp.excluding_dates as pdrp_eld_date, 
				
				pbgo.one_or_two_option, pbgo.status as pbgo_status, pbgo.products_departure_date_begin as pbgo_pddb, 
				pbgo.products_departure_date_end as pbgo_pdde, pbgo.excluding_dates as pbgo_eld_date, 
				
				p.products_image, p.products_vacation_package, p.products_video, p.products_durations, p.display_room_option, 
				p.products_durations_type, p.products_durations_description, p.departure_city_id, p.departure_end_city_id, p.products_model, 
				p.products_is_regular_tour, p.manufacturers_id, p.products_tax_class_id, p.products_class_id, p.tour_type_icon, 
				p.use_buy_two_get_one_price, p.min_num_guest, round(p.products_price/cur.value) as products_price, p.is_transfer, p.is_cruises,
				{$specilClunmsName} as final_price";

$dataTables = TABLE_PRODUCTS_TO_CATEGORIES . " p2c," .TABLE_TRAVEL_AGENCY . " ta, ". TABLE_PRODUCTS_DESCRIPTION . " pd , ".TABLE_CURRENCIES." cur ";//要查询的常规表
$dataWhere = " WHERE 1  ";//要查询的常规条件

//关键词搜索处理 {
$useTmpTable = false; //是否使用临时表处理关键词(如果使用了临时表，可以省略很多Like，并且能按近似度高低来排序)
$listAllLikeDataWhere = true; //是否强制列出所有的Like条件，不管是否使用临时表
$w = trim($w);
if(!tep_not_null($w) || $w == html_to_db(SEARCH_BOX_TIPS) || $w == html_to_db(SEARCH_BOX_TIPS1)) $w="";
if($w!=''){	//到这一步时$w已经被转化成数据库一致的编码了gb2312
	//关键词分词处理doc/short_keywords.php{
	if(file_exists(DIR_FS_CATALOG.'doc/short_keywords.php') && strlen($w)==strlen(preg_replace('/[[:space:]]+/','',$w))){
		include(DIR_FS_CATALOG.'doc/short_keywords.php');
		/**====按关键字长度降序排序====**/
		if(!function_exists('_usort_ZtoA')){
			function _usort_ZtoA($a, $b){
				if (mb_strlen($a) == mb_strlen($b)) {
					return 0;
				}
				return (mb_strlen($a) > mb_strlen($b)) ? -1 : 1;
			}
		}
		if(is_array($short_keywords)){
			usort($short_keywords,"_usort_ZtoA");
			$trans = array(); //array("from" => "to", "from1" => "to1");
			foreach((array)$short_keywords as $val){
				$val = trim($val);
				if(tep_not_null($val) && stripos($w, $val)!==false){
					$trans[$val] = " ".$val." ";
				}
			}
			$w = strtr($w,$trans);
			$w = trim(preg_replace('/[[:space:]]+/',' ',$w));
			//echo $w; exit;
		}
	}
	//关键词分词处理 }
	
	if($useTmpTable != true || $listAllLikeDataWhere == true){
		$dataWhere .= " and (";
	}
	$_todb_w = addslashes(tep_db_prepare_input(($w)));
	if($useTmpTable != true || $listAllLikeDataWhere == true){
		$dataWhere .= " p.products_model like Binary '%".strtoupper($_todb_w)."%' OR p.provider_tour_code like Binary '%{$_todb_w}%' OR p.provider_tour_code_sub like Binary '%{$_todb_w}%' OR pd.products_name like Binary '%{$_todb_w}%' OR pd.products_small_description like Binary '%{$_todb_w}%' OR pd.products_description like Binary '%{$_todb_w}%' ";
	}
	//组合关键词搜索处理
	$_todb_w1 = preg_replace('/[[:space:]]+/',' ',$_todb_w);
	$keywordsArray = explode(' ',$_todb_w1);
	$keywordsArray = array_unique($keywordsArray);
	$tmpTableWhere ="";
	if($useTmpTable != true || $listAllLikeDataWhere == true){
		if(sizeof($keywordsArray)>1){
			foreach($keywordsArray as $w_key => $w_val){
				$dataWhere .= " OR p.products_model like Binary '%".strtoupper($w_val)."%' OR pd.products_name like Binary '%{$w_val}%' "; //组合的关键词只搜索标题
			}
		}
	}
	
	//模糊关键词搜索处理
	$faultArray = array();
	foreach($keywordsArray as $m_key => $m_val){
		$f_sql = tep_db_query('SELECT key_words0, key_words1 FROM '.TABLE_FAULT_TOLERANT_KEYWORDS.' WHERE key_words0="'.$m_val.'" || key_words1="'.$m_val.'"');
		while($f_rows = tep_db_fetch_array($f_sql)){
				$faultArray[] = $f_rows['key_words0'];
				$faultArray[] = $f_rows['key_words1'];
		}
	}
	if((int)sizeof($faultArray)){
		if($useTmpTable != true || $listAllLikeDataWhere == true){
			foreach((array)$faultArray as $f_key => $f_val){
				if(!in_array($f_val, $keywordsArray)){
					$dataWhere .= " OR pd.products_name like Binary '%".addslashes($f_val)."%' "; //模糊关键词只搜索标题
				}
			}
		}
	}else{
		//模糊关键词搜索处理1
		$allFaultSql = tep_db_query('SELECT key_words0, key_words1 FROM '.TABLE_FAULT_TOLERANT_KEYWORDS.' WHERE key_words1!="" and key_words0!="" ');
		$faultPat = array();
		$faultReplace = array();
		$sAction = false;
		while($allFaultRows = tep_db_fetch_array($allFaultSql)){
			$faultPat[] = $allFaultRows['key_words1'];
			$faultReplace[] = $allFaultRows['key_words0'];
			if(stripos($_todb_w, $allFaultRows['key_words1'])!==false){$sAction = true;}
		}
		if($sAction == true && (int)sizeof($faultPat)){
			$nw = array();
			foreach($keywordsArray as $m_key => $m_val){
				$nw[] =addslashes(str_replace($faultPat, $faultReplace, $m_val));
				if($useTmpTable != true || $listAllLikeDataWhere == true){
					//echo $m_val."|".$nw."|".$allFaultRows['key_words1'].'|'.$allFaultRows['key_words0']."<br>";
					$dataWhere .= " OR pd.products_name like Binary '%".$nw[(sizeof($nw)-1)]."%' ";
				}
			}
		}
	}
	if($useTmpTable != true || $listAllLikeDataWhere == true){
		$dataWhere .= " ) ";
	}
	
	$useTmpTableTimeStart = microtime(true);
	if($useTmpTable==true){
		//创建临时表，用来确定关键词的先后顺序排序 { TEMPORARY
		tep_db_query(' DROP TABLE IF EXISTS tmp_keywords_products; ');
		tep_db_query(' CREATE TEMPORARY TABLE `tmp_keywords_products` (`tmp_products_id` INT(11) NOT NULL default 0 ,`tmp_sort_id` INT(11) UNSIGNED NOT NULL  ,PRIMARY KEY ( `tmp_products_id`, `tmp_sort_id` )) ;');
		$tmp_sort_id = 0;
		
		$KeywdsCollections = array();
		//1.全匹配
		$KeywdsCollections[] = str_replace(' ','%',$_todb_w1);
		//4.模糊关键词的匹配，排在最后
		foreach((array)$faultArray as $val){
			$KeywdsCollections[] = $val;
		}
		//2.部分匹配(关键词需要循环降级处理){
		$PartialMatchKeywds = explode(' ',$_todb_w1);
		foreach($PartialMatchKeywds as $key => $v){
			$num_source[] = $key;
		}
		uniqueCombinationNumber($all_key_result, $num_source, 0 , min(5, (sizeof($num_source)-1)));	//计算关键词的各种排列，步长不能超过5，否则系统资源吃不消
		foreach((array)$all_key_result as $val){
			$splitVal = str_split($val);
			$newKeyword = "";
			foreach($splitVal as $val1){
				$newKeyword .= $PartialMatchKeywds[$val1].'%';
			}
			$KeywdsCollections[] = substr($newKeyword,0,-1);
		}
	
		function loopTmpAraay($keywdArray){
			global $KeywdsCollections;
			$numSource=array();
			for($i=0, $n = sizeof($keywdArray)-1; $i<$n; $i++ ){
				$numSource[] = $i;
			}
			
			uniqueCombinationNumber($allKeyResult, $numSource, 0 , min(5, (sizeof($numSource)-1)));	//计算关键词的各种排列，步长不能超过5，否则系统资源吃不消
			foreach((array)$allKeyResult as $val){
				$splitVal = str_split($val);
				$newKeyword = "";
				foreach($splitVal as $val1){
					$newKeyword .= $keywdArray[$val1].'%';
				}
				$KeywdsCollections[] = substr($newKeyword,0,-1);
			}
			unset($keywdArray[sizeof($keywdArray)-1]);
			if(sizeof($keywdArray)>2){ loopTmpAraay($keywdArray); }
		}
		if(sizeof($PartialMatchKeywds)>2){
			loopTmpAraay($PartialMatchKeywds);
		}
		
		
		// } 
		
		//3.单个关键词匹配顺序
		foreach((array)$keywordsArray as $val){
			$KeywdsCollections[] = $val;
		}
		foreach((array)$nw as $val){
			$KeywdsCollections[] = $val;
		}
		
		$KeywdsCollections = array_unique($KeywdsCollections);
		//特殊排序
		if(!function_exists('_usort_keyNum')){
			function _usort_keyNum($a, $b){
				$_a = preg_match_all('/%/',$a,$m);
				$_b = preg_match_all('/%/',$b,$M);
				if ( $_a == $_b && strlen($a)==strlen($b)) {
					return 0;
				}
				if($_a > $_b){
					return -1;
				}
				if(strlen($a)>strlen($b)){
					return -1;
				}
				return 1;
			}
		}
		if(preg_match_all('/%/',$KeywdsCollections[0],$_m) >0 ){
			usort($KeywdsCollections,"_usort_keyNum");
		}
		//print_r($KeywdsCollections);
		
		//5.插入临时表数据
		$insertObj=array('PD.products_name','P.products_model','P.provider_tour_code','P.provider_tour_code_sub','PD.products_small_description','PD.products_description');
		//$insertObj=array('PD.products_name');
		for($i=0,$n=count($insertObj); $i<$n; $i++){
			foreach((array)$KeywdsCollections as $val){
				$sql = 'SELECT P.products_id FROM '.TABLE_PRODUCTS.' P, '.TABLE_PRODUCTS_DESCRIPTION.' PD WHERE PD.products_id=P.products_id and '.$insertObj[$i].' like Binary "%'.$val.'%" and PD.language_id="1" and P.products_status = "1" Order By P.products_id DESC';
				
				$query = tep_db_query($sql);
				while($rows = tep_db_fetch_array($query)){
					$checkSql = tep_db_query('SELECT tmp_products_id FROM `tmp_keywords_products` WHERE tmp_products_id="'.$rows['products_id'].'" limit 1');
					$check = tep_db_fetch_array($checkSql);
					if(!(int)$check['tmp_products_id']){
						$tmp_sort_id++;
						tep_db_query('INSERT INTO `tmp_keywords_products` (`tmp_products_id`,`tmp_sort_id`) VALUES ("'.$rows['products_id'].'","'.$tmp_sort_id.'");');
					}
				}
			}
		}
		
		$dataTables .= " , tmp_keywords_products tkp ";
		$dataWhere .= " and tkp.tmp_products_id = p.products_id ";
		//创建临时表，用来根据关键词的先后顺序排序 }
	}
	$useTmpTableTimeEnd = microtime(true);
	$useTmpTableTime = $useTmpTableTimeEnd-$useTmpTableTimeStart;
}
//关键词搜索处理 }

$dataTables .= " ,".TABLE_PRODUCTS . " p ";
$dataWhere .= " and p.products_status = '1' and p2c.products_id = p.products_id ";

if(intval($cid)>0){
	$this_cate_ids = tep_get_category_subcategories_ids($cid);//get cate's child id
	$dataWhere .= " and p2c.categories_id in (" . $this_cate_ids . ") ";
}

$dataWhere .= " and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' 
				and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code ";

$dataTables .= " left join " . TABLE_SPECIALS . " s on s.products_id = p.products_id 
				left join products_buy_two_get_one pbgo on  pbgo.products_id = p.products_id 
				left join products_double_room_preferences pdrp on pdrp.products_id=p.products_id 
				";

$dataOrder = "";
	$fc = intval($fc);
	$fc && $dataWhere .= " and CONCAT(',', p.departure_city_id, ',') like '%,".addslashes($fc).",%' ";
	$tc = intval($tc);
	$tc && $dataWhere .= " and CONCAT(',', p.departure_end_city_id, ',') like '%,".addslashes($tc).",%' ";
	$shid = intval($shid);
	$shid && $dataWhere .= " and ps.products_hotel_id='{$shid}' ";
	
	//取得默认排序
// 	$dataOrder = tep_get_products_list_default_order_by_from_city($fc, $vc);
	
	//优先排序开始{
	require 'admin/includes/classes/ProductShowOrderBy.class.php';
	$end_place=$tc?$tc:$vc;
	$product_show_order_by=new ProductShowOrderBy($current_category_id, $fc, $end_place,$d, $w,$cat_mnu_sel);
	$ids_str=$product_show_order_by->getShowId();
	if($ids_str){
		$tmp_o=explode(',', $ids_str['products_ids']);
		krsort($tmp_o);
		$ids_str=join(',', $tmp_o);
		$dataOrder = ' ORDER BY p.products_stock_status DESC,find_in_set(p.products_id,"'.$ids_str.'")DESC';}
// 		$dataOrder = ' ORDER BY p.products_stock_status DESC,substring_index("'.$ids_str['products_ids'].'",p.products_id,1)';}
		////优先排序结束}
	if(trim($fcw)!=''){
		
		$fcwinc = tep_db_query("select city_id from " . TABLE_TOUR_CITY . " where city like Binary '%".addslashes(tep_db_prepare_input(($fcw)))."%'");
		$fcw_ids = array();
		while ($rt = tep_db_fetch_array($fcwinc)){
			$fcw_ids[] = $rt['city_id'];
		}
		if(count($fcw_ids)){
			$dataWhere .= " and ( ";
			$exp='';
			foreach($fcw_ids as $ctid){
				$dataWhere .= " {$exp} FIND_IN_SET('{$ctid}',p.departure_city_id) ";
				$exp=" or ";
			}
			$dataWhere.=" )";
		}else{
			$dataWhere .= " and 0 ";
		}
	}
	
	if(trim($tcw)!='' || $vc){
		$dataTables .= ",products_destination pdt "; 
		$dataWhere .= " and p.products_id = pdt.products_id ";
		if($vc){
			$dataWhere .= " and pdt.city_id='{$vc}' ";
		}
		if(trim($tcw)!=''){
			$tcwinvc = tep_db_query("select city_id from " . TABLE_TOUR_CITY . " where city like Binary '%".addslashes(tep_db_prepare_input(($tcw)))."%'");
			$tcw_vcids = array();
			while ($rt = tep_db_fetch_array($tcwinvc)){
				$tcw_vcids[] = $rt['city_id'];
			}
			if(count($tcw_vcids)){
				$dataWhere .= " and ( (";
				$exp='';
				foreach($tcw_vcids as $ctid){
					$dataWhere .= " {$exp} FIND_IN_SET('{$ctid}',p.departure_end_city_id) ";
					$exp=" or ";
				}
				$dataWhere.=" )";
				$tcw_vcids = "'".join("','",$tcw_vcids)."'";
				$dataWhere.=" or pdt.city_id in({$tcw_vcids}) ";
				$dataWhere.=" )";
			}else{
				$dataWhere .= " and 0 ";
			}
		}
	}
	
	$ms = intval($ms);
	(!$ms||$ms<0) && $ms = '';
	$me = intval($me);
	(!$me||$me<0) && $me = '';
	if($ms || $me){
		$ms && $dataWhere .= " and {$specilClunmsName}>='{$ms}' ";
		$me && $dataWhere .= " and {$specilClunmsName}<='{$me}' ";
	}else{
		switch($m){
			case 1:
				$dataWhere .= " and {$specilClunmsName}<'100' ";
				break;
			case 2:
				$dataWhere .= " and {$specilClunmsName}>='100' and {$specilClunmsName}<='200' ";
				break;
			case 3:
				$dataWhere .= " and {$specilClunmsName}>='201' and {$specilClunmsName}<='500' ";
				break;
			case 4:
				$dataWhere .= " and {$specilClunmsName}>='501' and {$specilClunmsName}<='1000' ";
				break;
			case 5:
				$dataWhere .= " and {$specilClunmsName}>='1001' and {$specilClunmsName}<='2000' ";
				break;
			case 6:
				$dataWhere .= " and {$specilClunmsName}>'2000' ";
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
	//此为按出发时间需要的条件
	if(tep_not_null($dep_date0)){
		$dataWhere .= " and pddfs.products_id = p.products_id and pddfs.departure_date='".$dep_date0."' ";
		$dataTables .= " , products_departure_dates_for_search pddfs ";
	}

	switch($of){
		case 1://买2送1
			$dataWhere .= " and 
			(p.tour_type_icon like '%buy2-get-1%' 
			 or 
			(p.products_class_id = '4' and p.use_buy_two_get_one_price ='1' and pbgo.status='1' 
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
			(p.products_class_id = '4' and p.use_buy_two_get_one_price ='1' and pbgo.status='1' 
			AND (pbgo.one_or_two_option='2' OR pbgo.one_or_two_option='0')  
			AND (pbgo.products_departure_date_begin <= '{$now_date} 00:00:00' OR pbgo.products_departure_date_begin='0000-00-00 00:00:00' 
				 OR pbgo.products_departure_date_begin='') 
			AND (pbgo.products_departure_date_end >='{$now_date} 23:59:59' 
				 OR pbgo.products_departure_date_end='0000-00-00 00:00:00' OR pbgo.products_departure_date_end='' ) 
			and (pbgo.excluding_dates='' OR pbgo.excluding_dates not like '%{$now_date}%')) 
			) ";
			break;
		case 3://双人折扣
			$dataWhere .= "  and 
			(p.tour_type_icon like '%2-pepole-spe%' 
			 or 
			(pdrp.status='1'  AND pdrp.people_number='2'
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
			if($w && strpos($dataTables, 'tmp_keywords_products')!==false ){
				$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, final_price DESC,p.products_last_modified DESC ";
			}
				$dataOrder = " ORDER BY final_price DESC,p.products_last_modified DESC ";
			break;
		case 'p_a'://价格升序
			if($w && strpos($dataTables, 'tmp_keywords_products')!==false ){
				$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, final_price ,p.products_last_modified DESC  ";
			}
				$dataOrder = " ORDER BY final_price ,p.products_last_modified DESC ";
			break;
		case 'o_d'://热销降序
			if($w && strpos($dataTables, 'tmp_keywords_products')!==false ){
				$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, p.products_ordered DESC,p.products_last_modified DESC  ";
			}
				$dataOrder = " ORDER BY p.products_ordered DESC,p.products_last_modified DESC ";
			
			break;
		case 'o_a'://热销升序
			if($w && strpos($dataTables, 'tmp_keywords_products')!==false ){
				$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, p.products_ordered ,p.products_last_modified DESC  ";
			}
				$dataOrder = " ORDER BY p.products_ordered ,p.products_last_modified DESC ";
			break;
		case 'd_d'://持续时间降序
			if($w && strpos($dataTables, 'tmp_keywords_products')!==false ){
				$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, p.products_durations_type,products_durations DESC";
			}
				$dataOrder = " ORDER BY p.products_durations_type,products_durations DESC ";
			break;
		case 'd_a'://持续时间升序
			if($w && strpos($dataTables, 'tmp_keywords_products')!==false ){
				$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, p.products_durations_type DESC ,p.products_durations";
			}
				$dataOrder = " ORDER BY p.products_durations_type DESC ,p.products_durations ";
			break;
		default:
			if(!tep_not_null($dataOrder)){
				if( strpos($dataTables, 'tmp_keywords_products')!==false ){
					$dataOrder = " ORDER BY tkp.tmp_sort_id ASC, p.products_ordered DESC, p.products_last_modified DESC ";
				}elseif(is_numeric($w)){	//如果关键词为纯数字字符串则以产品ID严格匹配的优先					
					$dataOrder = ' ORDER BY find_in_set(p.products_id, "'.$w.'") DESC ';
				}else{
					//$dataOrder = " ORDER BY p2c.products_sort_order DESC,p.products_durations_type DESC ,p.products_durations ";
					$dataOrder = " ORDER BY p.products_stock_status DESC, p.products_ordered DESC, p.products_last_modified DESC ";
					
					//如果关键字完全匹配产品产品名称则此产品排在最优先的位置 start {
					if(tep_not_null($_GET['w'])){
						$_w = strip_tags(html_to_db($_GET['w']));
						$_sql_str = 'SELECT products_id FROM `products_description` WHERE `products_name` Like Binary "%'.preg_replace('/[[:space:]]+/','',$_w).'%" ';
						$_sql = tep_db_query($_sql_str);
						$_keys = NULL;
						while($_rows = tep_db_fetch_array($_sql)){
							$_keys[] = $_rows['products_id'];
						}
						if(is_array($_keys)){
							$dataOrder = ' ORDER BY find_in_set(p.products_id, "'.implode(',',$_keys).'") DESC ';
						}
					}
					//如果关键字完全匹配产品产品名称则此产品排在最优先的位置 end }
				}
			}
			break;
	}

//$dataWhere.= ' AND p.is_hotel<1 ';	//搜索结果不能有酒店 2013-07-04以后又能搜酒店了 fixed by Howard

$query = tep_db_query("select COUNT( DISTINCT p.products_id ) as rows from {$dataTables} {$dataWhere}");
$query = tep_db_fetch_array($query);
$total = (int)$query["rows"];
$db_perpage = MAX_DISPLAY_SEARCH_RESULTS;
//  取得翻页条 第四个参数是 设置另一种显示模式 共2种 0 或者大于零
$_pageType = 1;

$pages = makePager($total,'pp',$db_perpage,false,$_pageType);
$limit = " LIMIT ".($pp-1)*$db_perpage.",$db_perpage;";

//$dataTableClunms = '*';
//$dataTables = 'products p ';
//$dataTables .= ',products_description pd ';
//$dataWhere = 'WHERE 1 ';
//$dataWhere.= ' AND pd.products_id = p.products_id ';
//$dataWhere.= ' AND p.products_status="1" ';
//$dataOrder = ' ORDER BY p.products_id ASC';
$querySQL = "SELECT {$dataTableClunms} FROM {$dataTables} {$dataWhere} GROUP BY p.products_id  {$dataOrder} ".$limit;

// echo $querySQL; 
//exit;
//============================为列表和筛选选项准备==============================================================}



//===========================取得当前数据的所有出发城市============================================={
unset($_SEARCH_DATA['From_City']); //在此以前的所有出发城市不用了，到时把前面的数据删除

if(!tep_not_null($fc)){
	$startCitySQL = "SELECT p.departure_city_id FROM {$dataTables} {$dataWhere} GROUP BY p.departure_city_id ";
	$startCityQuery = tep_db_query($startCitySQL);
	$tmpArray = array();
	while($startCityRows = tep_db_fetch_array($startCityQuery)){
		//$tmpArray[] = $startCityRows['departure_city_id'];
		$_departure_city_ids = explode(',',$startCityRows['departure_city_id']);
		foreach((array)$_departure_city_ids as $key => $val){
			$val = (int)$val;
			if($val>0){
				$tmpArray[] = $val;
			}
		}
	}
	$tmpArray = array_unique($tmpArray);
	$start_city_id_string = implode(',', $tmpArray);
}

if(!tep_not_null($start_city_id_string)){ $start_city_id_string = (int)$fc; }
$citySQL = "SELECT city_id as id, city as name, departure_city_status, is_attractions FROM `tour_city` WHERE city_id in({$start_city_id_string}) ORDER BY `city_id` ASC ";
$cityQuery = tep_db_query($citySQL);
while($cityRows = tep_db_fetch_array($cityQuery)){
	$_SEARCH_DATA['From_City'][$cityRows['id']] = $cityRows;
	$_SEARCH_DATA['From_City'][$cityRows['id']]['name'] = db_to_html($cityRows['name']);
}
//===========================取得当前数据的所有出发城市=============================================}
//===========================取得当前数据的所有目的城市============================================={
unset($_SEARCH_DATA['To_City']);
if(!tep_not_null($tc)){
	$endCitySQL = "SELECT p.departure_end_city_id FROM {$dataTables} {$dataWhere} GROUP BY p.departure_end_city_id ";
	$endCityQuery = tep_db_query($endCitySQL);
	$tmpArray = array();
	while($endCityRows = tep_db_fetch_array($endCityQuery)){
		$_departure_city_ids = explode(',',$endCityRows['departure_end_city_id']);
		foreach((array)$_departure_city_ids as $key => $val){
			$val = (int)$val;
			if($val>0){
				$tmpArray[] = $val;
			}
		}
	}
	$tmpArray = array_unique($tmpArray);
	$end_city_id_string = implode(',', $tmpArray);
}

if(!tep_not_null($end_city_id_string)){ $end_city_id_string = (int)$tc; }
$citySQL = "SELECT city_id as id, city as name, departure_city_status, is_attractions FROM `tour_city` WHERE city_id in({$end_city_id_string}) ORDER BY  `city_id` DESC, `is_attractions` DESC ";
$cityQuery = tep_db_query($citySQL);
while($cityRows = tep_db_fetch_array($cityQuery)){
	$_SEARCH_DATA['To_City'][$cityRows['id']] = $cityRows;
	$_SEARCH_DATA['To_City'][$cityRows['id']]['name'] = db_to_html($cityRows['name']);
}

//===========================取得当前数据的所有目的城市=============================================}
//===========================取得当前数据的所有途经景点============================================={
unset($_SEARCH_DATA['Via_City']);
if(!tep_not_null($vc)){
	if(stripos($dataTables,'products_destination')===false){
		$viaCitySQL = "SELECT pdt.city_id FROM products_destination pdt, {$dataTables} {$dataWhere} and pdt.products_id=p.products_id GROUP BY pdt.city_id ";
	}else{
		$viaCitySQL = "SELECT pdt.city_id FROM {$dataTables} {$dataWhere} GROUP BY pdt.city_id ";
	}
	$viaCityQuery = tep_db_query($viaCitySQL);
	$tmpArray = array();
	while($viaCityRows = tep_db_fetch_array($viaCityQuery)){
		$_departure_city_ids = explode(',',$viaCityRows['city_id']);
		foreach((array)$_departure_city_ids as $key => $val){
			$val = (int)$val;
			if($val>0){
				$tmpArray[] = $val;
			}
		}
	}
	$tmpArray = array_unique($tmpArray);
	$via_city_id_string = implode(',', $tmpArray);
}

if(!tep_not_null($via_city_id_string)){ $via_city_id_string = (int)$vc; }
$citySQL = "SELECT city_id as id, city as name, departure_city_status, is_attractions FROM `tour_city` WHERE city_id in({$via_city_id_string}) ORDER BY  `city_id` DESC, `is_attractions` DESC ";
$cityQuery = tep_db_query($citySQL);
while($cityRows = tep_db_fetch_array($cityQuery)){
	$_SEARCH_DATA['Via_City'][$cityRows['id']] = $cityRows;
	$_SEARCH_DATA['Via_City'][$cityRows['id']]['name'] = db_to_html($cityRows['name']);
}

//===========================取得当前数据的所有途经景点=============================================}
//===========================取得当前数据的所有价格==============================================={
unset($_SEARCH_DATA['Price']);
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
//===========================取得当前数据的所有价格==============================================={
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
		//search_addseo($_sitem['name']);
	}
	//===========================取得当前数据的所有持续时间===========================================}

	$breadcrumb->add(NAVBAR_TITLE_1,tep_href_link('advanced_search_result.php',''));
	$navigation_title && $breadcrumb->add(db_to_html($navigation_title));
	$BreadOff = true;
	//========================SEO Start===========================================================
	//require(DIR_FS_LANGUAGES . $language . '/' . 'header_tags.php');
	
	//$the_desc = HEAD_DESC_TAG_ALL;
	//$the_key_words = HEAD_KEY_TAG_ALL;
	//$the_title = HEAD_TITLE_TAG_ALL;

	//========================================================================================
	
	$_sitem = $_SEARCH_DATA['From_City'][$fc];
	if($_sitem){
		//search_addseo($_sitem['name'].db_to_html('出发'));
	}
	$_sitem = $_SEARCH_DATA['To_City'][$tc];
	if($_sitem){
		//search_addseo(db_to_html('到').$_sitem['name']);
	}
	//========================================================================================
	
	$_sitem = $_SEARCH_DATA['Via_City'][$vc];
	if($_sitem){
		//search_addseo($_sitem['name']);
	}
	//========================================================================================
	
	$_sitem = $_SEARCH_DATA['Price'][$m];
	if($_sitem){
		//search_addseo($_sitem['name']);
	}
	//========================================================================================
	
	$_sitem = $_SEARCH_DATA['DaysItem'][$d];
	if($_sitem){
		//search_addseo($_sitem['name']);
	}
	//========================================================================================
	$_SEARCH_DATA['Offer']=array();
	$_SEARCH_DATA['Offer'][0]=array('id'=>'','name'=>db_to_html('全部'));
	$_SEARCH_DATA['Offer']['1']=array('id'=>'1','name'=>db_to_html('买2送1'));
	//$_SEARCH_DATA['Offer']['2']=array('id'=>'2','name'=>db_to_html('买2送2'));
	//$_SEARCH_DATA['Offer']['3']=array('id'=>'3','name'=>db_to_html('双人折扣'));
	$_SEARCH_DATA['Offer']['4']=array('id'=>'4','name'=>db_to_html('特价'));
	$_SEARCH_DATA['Offer']['5']=array('id'=>'5','name'=>db_to_html('低价保证'));
	
	$_sitem = $_SEARCH_DATA['Offer'][$of];
	if($_sitem){
		//search_addseo($_sitem['name']);
	}
	//========================SEO End  ===========================================================

//============================为列表和筛选选项准备=============================================================={

	$validation_include_js='true';
	$validation_div_span ='span';
	$Show_Calendar_JS = 'true';

	$content = CONTENT_ADVANCED_SEARCH_RESULT;
	
	require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	
	require(DIR_FS_INCLUDES . 'application_bottom.php');
?>