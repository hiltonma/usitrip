<?php
require_once(dirname(__FILE__)."/api.php");
class qunar_api implements api {			
	/**
	 * 对方要什么货币的价格CNY为人民币，USD为美元
	 * @see http://www.xe.com/zh-CN/currencyconverter/full/
	 * @var string
	 */
	protected $CurrencyCode = 'CNY';
	/**
	 * 是否输出静态xml页面
	 * @var boolean
	 */
	public $ouputStatic = true;
	/**
	 * 去哪儿API
	 * @param DOMDocument $dom xml类
	 * @param array $datas 产品数据
	 */
	public function __construct(DOMDocument $dom, $datas){
		$this->set_list_xml($dom, $datas);
	}	
	/**
	 * (non-PHPdoc)
	 * @see api::set_product_details_xml()
	 * @todo 若需要产品详细数据再写 
	 */
	public function set_product_details_xml($products_id){
		
	}
	
	public function set_list_xml(DOMDocument $dom, $datas){
		global $domroot;
		$datasTotal = sizeof($datas);
		if((int)$datasTotal){
			$dom->preserveWhiteSpace = false;	//保留空白
			$dom->formatOutput = true;			//标准格式输出
			
			$domroot = cel("routes",$dom);
			for($i=0; $i < min(100,$datasTotal); $i++){
				$_ul = cel("item");
				//线路URL 必填
				$_ul->setAttribute('url',tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $datas[$i]['products_id'].'&utm_source=qunar&utm_medium=cpc_qunar&utm_term=2013-11-05'));
				//teamno团号
				$_ul->setAttribute('teamno',cval(tep_db_output($datas[$i]['products_model']), false));
				//线路标题 必
				$_ul->setAttribute('title',cval(tep_db_output($datas[$i]['products_name']), false));
				//线路主题 必填 如常规游
				$_ul->setAttribute('subject',cval('常规游', false));
				//线路报价 必填
				$_ul->setAttribute('price',cval($this->get_usd_to_target_currency(str_replace('$', '',str_replace(',','',tep_db_output($datas[$i]['price'])))), false));
				//价格币种 (走四方额外加的)
				$_ul->setAttribute('priceCurrency',$this->CurrencyCode);				
				//线路图片url 多个图片则以英文逗号分割 长宽尽量大于480像素画面清晰无logo
				$_ul->setAttribute('route_snapShot',cval(DIR_WS_IMAGES . rawurlencode($datas[$i]['products_image']), false));
				//跟团游/自由行 必填
				$_ul->setAttribute('function',cval('跟团游', false));
				//出发城市 必填
				$_ul->setAttribute('departure',cval(tep_db_output($datas[$i]['city']), false));
				//目的地城市 多个城市则以英文逗号分割 必填
				$_ul->setAttribute('arrive',cval(tep_db_output(implode(',', $datas[$i]['destination_city'])),false));
				//国内游/出境游
				$_ul->setAttribute('type',cval('出境游',false));
				//出发时间 必填 格式yyyy-MM-dd
				$_ul->setAttribute('dateOfDeparture',cval(tep_db_output($datas[$i]['startdays']),false));
				//plainDateString="出发时间原始字符串 如天天发团 周二发团"
				$_ul->setAttribute('plainDateString',cval(tep_db_output($datas[$i]['startdayinfo']),false));
				//dateOfExpire="结束时间 必填 格式yyyy-MM-dd"
				$_ul->setAttribute('dateOfExpire',cval(tep_db_output($datas[$i]['startdays']),false));
				//itineraryDay="行程天数 必填 正整数"
				$_ul->setAttribute('itineraryDay',cval(tep_db_output($datas[$i]['days']),false));
				//advanceday="提前报名天数 正整数"
				$_ul->setAttribute('advanceday','2');
				
				//行程特色features 必填
				$_tip_features = cel("features");
				//for ($jjjj=0; $jjjj<5; $jjjj++){
				$_tip_feature = cel("feature");
				$_tip_feature->appendChild(cval(nl2br(strip_tags($datas[$i]['products_small_description']))));
				$_tip_features->appendChild($_tip_feature);
				//}
				$_ul->appendChild($_tip_features);
				
				//特别提醒,注意事项tips每行用\r\n分隔
				$_tip_notice = cel("tips");
				$_tip_notice->appendChild(cval(str_replace('<br />', '\r\n', nl2br(strip_tags($datas[$i]['products_package_special_notes'])))));
				$_ul->appendChild($_tip_notice);
				
				//预定条款,报价说明<bookingTerms>预定条款 每行用\r\n分隔</bookingTerms>
				$_costinfo = cel("bookingTerms");
				$_costinfo->appendChild(cval(str_replace('<br />', '\r\n', nl2br(strip_tags($datas[$i]['products_pricing_special_notes'])))));
				$_ul->appendChild($_costinfo);
				
				//【费用包含】字符串类型 
				$_costin = cel("feeInclude");
				$_costin->appendChild(cval(str_replace('<br />', '\r\n', nl2br(strip_tags($datas[$i]['products_other_description'])))));
				$_ul->appendChild($_costin);
					
				//【费用不包含】字符串类型 
				$_costout = cel("feeExclude");
				$_costout->appendChild(cval(str_replace('<br />', '\r\n', nl2br(strip_tags($datas[$i]['products_package_excludes'])))));
				$_ul->appendChild($_costout);						

				//每日行程描述miscellaneous 每天一条 必填
				$_routes = cel("miscellaneous");
				//以下需根据行程内容来循环
				foreach((array)$datas[$i]['route'] as $key => $val){
					$_route = cel("itineraryDay");
					//天数 必填 如：第一天 1
					$_route->setAttribute('day', (int)$key);
					//每日行程标题 必填
					$_route->setAttribute('title', cval(tep_db_output(strip_tags($val["travel_name"])),false));
					//每日住宿 accommodation
					$_route->setAttribute('accommodation', cval(tep_db_output(strip_tags($val["travel_hotel"])),false));
					//行程信息
					$_route->appendChild(cval(tep_db_output(strip_tags($val["travel_content"]))));
					$_routes->appendChild($_route);
				}					
				$_ul->appendChild($_routes);
				
				//价格日期表 必填
				$_routeDates = cel('routeDates');
				foreach((array)$datas[$i]['datePrice'] as $key => $val){
					$_route_date = cel('routeDate');
					//日期 格式yyyy-MM-dd 必填
					$_route_date->setAttribute('date', $val['date']);
					//每日价格,人民币
					$_route_date->setAttribute('price', cval($this->get_usd_to_target_currency(str_replace('$', '',$val['price'])), false));
					//每日儿童价
					$_route_date->setAttribute('priceOfChild', cval($this->get_usd_to_target_currency(str_replace('$', '',$val['priceOfChild'])),false));
					//价格币种 (走四方额外加的)
					$_route_date->setAttribute('priceCurrency',$this->CurrencyCode);
					//该日可预订人数
					$_route_date->setAttribute('retainCount', cval('100',false));
					//起价说明 不能超过500个字符，需去除html标签
					//$_route_date->setAttribute('pricedesc', cval('起价说明',false));
					$_routeDates->appendChild($_route_date);
				}
				$_ul->appendChild($_routeDates);

			}
		}				
	}
	/**
	 * 取得当天美元兑换成去哪儿想要的货币值
	 * @param integer $usd 美元币值，注意：必须是美元的值。如：123.56代表$123.56
	 * @param int $decimal_places 兑换后保留几位小数？
	 * @return float
	 */
	public function get_usd_to_target_currency($usd, $decimal_places = 0){
		global $currencies;
		$cny = 0;
		if(!is_object($currencies)){
			die('缺少币种类！可能$currencies被改过');
		}
		if(!$currencies->currencies['USD']['value']) die('美元已经被干掉了');
		if(!$currencies->currencies[$this->CurrencyCode]['value']) die($this->CurrencyCode.'已经被停止使用');		
		$cny = $usd * $currencies->currencies['USD']['value'] * $currencies->currencies[$this->CurrencyCode]['value'];
		return round($cny, $decimal_places);
	}
}
?>