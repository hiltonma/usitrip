<?php
require_once(dirname(__FILE__)."/api.php");
class kuxun_api implements api {			
	/**
	 * 酷讯API
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
	/**
	 * (non-PHPdoc)
	 * @see api::set_list_xml()
	 */
	public function set_list_xml(DOMDocument $dom, $datas){
		global $domroot;
		$datasTotal = sizeof($datas);
		if((int)$datasTotal){
			$dom->preserveWhiteSpace = false;	//保留空白
			$dom->formatOutput = true;			//标准格式输出
				
			$domroot = cel("dujia_xianlus",$dom);
			for($i=0; $i < min(100,$datasTotal); $i++){
				$_ul = cel("dujia_xianlu");
				//$_ul->setAttribute('id',(int)$datas[$i]['products_id']);
				//$_ul->setAttribute('id2',(int)$datas[$i]['products_id']);
		
				$_type = cel("type");	/*【线路类型】 数字类型，（（ 不可为空 ））； 1=旅行团 2=自由行 3=地接社 0=其他*/
				$_type->appendChild($dom->createTextNode("3"));
				$_ul->appendChild($_type);
		
				$_rangetype = cel("rangetype");	/*【出游范围】数字类型，（（ 不可为空 ））； 1=国内游 2=出境游 3=周边游*/
				$_rangetype->appendChild($dom->createTextNode("2"));
				$_ul->appendChild($_rangetype);
		
				$_traffictype = cel("traffictype");	/*【出行方式】数字类型，（（ 不可为空 ））； 11=双飞  22=火车去火车回  33=客车去客车回 44=邮轮来回  12=飞机去火车回 13=飞机去客车回 14=飞机去邮轮回  21=火车去飞机回 23=火车去客车回 24=火车去邮轮回  31=客车去飞机回 32=客车去火车回 34=汽车去邮轮回  43=邮轮去客车回 42=邮轮去火车回 41=邮轮去飞机回*/
				$_traffictype->appendChild($dom->createTextNode("11"));
				$_ul->appendChild($_traffictype);
		
				$_link = cel("link");	/*【原网站的线路链接】 字符串类型*/
				$_link->appendChild(cval(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $datas[$i]['products_id'].'&utm_source=qunar&utm_medium=cpc_qunar&utm_term=2013-11-10')));
				$_ul->appendChild($_link);
		
				$_code = cel("code");	/*【线路唯一编号】字符串类型，（（ 不可为空）），长度不超过64个字符。线路的唯一标志，以后线路更新依据此编号来判断是否是同条线路。*/
				$_code->appendChild(cval($datas[$i]['products_id']));
				$_ul->appendChild($_code);
		
				$_title = cel("title");	/*【线路名称】字符串类型，（（ 不可为空 ）），长度不超过128个字符。*/
				$_title->appendChild(cval(tep_db_output($datas[$i]['products_name'])));
				$_ul->appendChild($_title);
		
				/*【出发城市】字符串类型，（（ 不可为空 ））。只能放一个城市，同一条线路有多个出发城市的，请拆分成多条线路 {*/
				$_fromcity = cel("fromcity");
				$_fromcity->appendChild(cval(tep_db_output($datas[$i]['city'])));
				$_ul->appendChild($_fromcity);
				/*}*/
		
				/*【线路起价】浮点类型，（（ 不可为空 ））。人民币计价直接填写数字，美元计价请填写“$3400”{*/
				$_price = cel("price");
				//$_price->appendChild(cval(tep_db_output($datas[$i]['price'])));
				$_price->appendChild($dom->createTextNode(str_replace(',','',tep_db_output($datas[$i]['price']))));
				$_ul->appendChild($_price);
				/*}*/
		
				$_days = cel("days");	/*【行程天数】数字类型，（（ 不可为空 ））。*/
				$_days->appendChild($dom->createTextNode($datas[$i]['days']));
				$_ul->appendChild($_days);
		
				/*【行程天数的文字描述】字符串类型。
				 $_daysinfo = cel("daysinfo");
				$_daysinfo->appendChild(cval("daysinfo"));
				$_ul->appendChild($_daysinfo);
				*/
		
				/*【是否可延期·自由行特有字段】数字类型 ；1=可延期  2=不可延期
				 $_isdelay = cel("isdelay");
				$_isdelay->appendChild(cval("isdelay"));
				$_ul->appendChild($_isdelay);
				*/
		
				/*【发团周期】字符串类型 。如：每周二发团、每月1-5号发团..... */
				$_startdayinfo = cel("startdayinfo");
				$_startdayinfo->appendChild(cval(tep_db_output($datas[$i]['startdayinfo'])));
				$_ul->appendChild($_startdayinfo);
		
				$_startdays = cel("startdays");	/*【具体发团日期】字符串类型，逗号分隔的日期类型，（（ 不可为空 ）），最多放置200个日期。请严格按照此格式填写，不填写或格式错误将导致线路无法被搜索到。对于不同发团日期导致不同价格的情况，请在日期后以符号“#”分隔价格（价格为整数，不填写单位，默认为“元”）：如“2010-11-8#1500，2010-11-18#1600，2010-11-28#1400，2010-12-8#1300 */
				$_startdays->appendChild(cval(tep_db_output($datas[$i]['startdays'])));
				$_ul->appendChild($_startdays);
		
				/*【行程安排或自由行的推荐行程的内容放到这里面】{ */
				$_routes = cel("routes");
				//以下需根据行程内容来循环
				foreach((array)$datas[$i]['route'] as $key => $val){
					$_route = cel("route");
					$__pos = cel("pos");
					$__pos->appendChild($dom->createTextNode($key));
					$_route->appendChild($__pos);
						
					$__title = cel("title");
					$__title->appendChild(cval("第".$key."天"));
					$_route->appendChild($__title);
						
					$__description = cel("description");
					//$__description->appendChild(cval(tep_db_output(strip_tags($val["travel_content"]))));
					$__description->appendChild(cval(tep_db_output(strip_tags($val["travel_name"]))));
					$_route->appendChild($__description);
						
					$__sightnames = cel("sightnames");
					$__sightnames->appendChild(cval(tep_db_output(strip_tags($val["travel_hotel"]))));
					$_route->appendChild($__sightnames);
						
					$_routes->appendChild($_route);
				}
		
				$_ul->appendChild($_routes);
				/*【行程安排或自由行的推荐行程的内容放到这里面】} */
		
				/*【自由行的飞机描述信息。自由行的特有字段，旅游团请设置为空。{
				 $_flights = cel("flights");
				$_flight = cel("flight");
				$__pos = cel("pos");
				$__pos->appendChild($dom->createTextNode("1"));
				$__title = cel("title");
				$__title->appendChild(cval("第1天"));
				$__fromtime = cel("fromtime");
				$__fromtime->appendChild(cval("6:45"));
				$__totime = cel("totime");
				$__totime->appendChild(cval("10:30"));
				$__toairport = cel("toairport");
				$__toairport->appendChild(cval("上海虹桥机场"));
				$__airline = cel("airline");
				$__airline->appendChild(cval("南方航空"));
				$__code = cel("code");
				$__code->appendChild(cval("CZ6716"));
				$__model = cel("model");
				$__model->appendChild(cval("737"));
				$__seat = cel("seat");
				$__seat->appendChild(cval("经济舱"));
				$__ischange = cel("ischange");
				$__ischange->appendChild(cval("2"));
		
				$_flight->appendChild($__pos);
				$_flight->appendChild($__title);
				$_flight->appendChild($__fromtime);
				$_flight->appendChild($__totime);
				$_flight->appendChild($__toairport);
				$_flight->appendChild($__airline);
				$_flight->appendChild($__code);
				$_flight->appendChild($__model);
				$_flight->appendChild($__seat);
				$_flight->appendChild($__ischange);
				$_flights->appendChild($_flight);
				$_ul->appendChild($_flights);
				【自由行的飞机描述信息。自由行的特有字段，旅游团请设置为空。}*/
		
				/*【自由行的酒店描述信息。自由行的特有字段，旅游团请设置为空 {
				 $_hotels = cel("hotels");
				$_hotel = cel("hotel");
				$__pos = cel("pos");
				$__pos->appendChild($dom->createTextNode("1"));
					
				$_ul->appendChild($_hotels);
				【自由行的酒店描述信息。自由行的特有字段，旅游团请设置为空 }*/
		
				/*【费用包含】字符串类型 {*/
				$_costin = cel("costin");
				//$_costin->appendChild(cval(tep_db_output(strip_tags($datas[$i]['products_other_description']))));
				$_costin->appendChild(cval(nl2br(strip_tags($datas[$i]['products_other_description']))));
				$_ul->appendChild($_costin);
				/*}*/
		
				/*【费用不包含】字符串类型 {*/
				$_costout = cel("costout");
				//$_costout->appendChild(cval(tep_db_output(strip_tags($datas[$i]['products_package_excludes']))));
				$_costout->appendChild(cval(nl2br(strip_tags($datas[$i]['products_package_excludes']))));
				$_ul->appendChild($_costout);
				/*}*/
		
				/*【报价说明】字符串类型 {*/
				$_costinfo = cel("costinfo");
				$_costinfo->appendChild(cval(nl2br(strip_tags($datas[$i]['products_pricing_special_notes']))));
				$_ul->appendChild($_costinfo);
				/*}*/
		
				/*【提前报名天数】数字类型 {*/
				$_presigndays = cel("presigndays");
				$_presigndays->appendChild($dom->createTextNode("2"));
				$_ul->appendChild($_presigndays);
				/*}*/
		
				/*【线路特色】字符串类型 {*/
				$_tip_feature = cel("tip_feature");
				$_tip_feature->appendChild(cval(nl2br(strip_tags($datas[$i]['products_small_description']))));
				$_ul->appendChild($_tip_feature);
				/*}*/
		
				/*【注意事项】字符串类型 {*/
				$_tip_notice = cel("tip_notice");
				$_tip_notice->appendChild(cval(nl2br(strip_tags($datas[$i]['products_package_special_notes']))));
				$_ul->appendChild($_tip_notice);
				/*}*/
		
				/*【温馨提示】字符串类型 {*/
				$_tip_friendly = cel("tip_friendly");
				$_tip_friendly->appendChild(cval(nl2br(strip_tags($datas[$i]['products_notes']))));
				$_ul->appendChild($_tip_friendly);
				/*}*/
		
				/*【线路焦点图url】*/
				$_imgurl = cel("imgurl");
				$_imgurl->appendChild(cval(DIR_WS_IMAGES . rawurlencode($datas[$i]['products_image'])));
				$_ul->appendChild($_imgurl);
				/*}*/
		
				/*【是否支持在线预订】数字类型，（（ 不可为空 ））；1=支持在线预订  2=不支持在线预订 {*/
				$_isbook = cel("isbook");
				$_isbook->appendChild($dom->createTextNode("1"));
				$_ul->appendChild($_isbook);
				/*}*/
		
				/*【是否是重点推荐线路】数字类型，（（ 不可为空 ））； 1=推荐  2=不推荐 {*/
				$_istop = cel("istop");
				$_istop->appendChild($dom->createTextNode("1"));
				$_ul->appendChild($_istop);
				/*}*/
		
		
			}
		}				
	}
}
?>