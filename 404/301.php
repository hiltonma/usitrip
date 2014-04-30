<?php
/**
 * 301 重定向 针对老站 的产品
 * @author  lwkai  2012-05-15
 * 
 */
if ($error404 === false) return;

//$_SERVER['HTTP_HOST'] = cn.test.com
//$_SERVER['REQUEST_URI'] = jiebantongyou-content/tci-6686-tcp-2.html

// 请求的域名
$req_301 = $_SERVER['REQUEST_URI'];
// 请求的URL 不带域名
$req_domain = $_SERVER['HTTP_HOST'];

// 需要转向到的域名（这里需要用中文的） 判断是否有 / 结束 
$go_domain = substr(SCHINESE_HTTP_SERVER,-1) == '/' ? SCHINESE_HTTP_SERVER : SCHINESE_HTTP_SERVER . '/';

// 签证 域名 跳转 首页 index  还有一个
$go_url = false;
switch ($req_domain) {
	/*case 'ns1.usitrip.com':
	case 'ns2.usitrio.com':
		$go_url = $go_domain;
   		break;*/
	case 'visa.usitrip.com':
		$go_url = $go_domain . 'qianzheng/';
		break;
}

if ($go_url !== false) {
	header("HTTP/1.1 301 Moved Permanently");
   	header("Location: " . $go_url);	
	exit();
}

$go_url = false;

// 301 跳转规则  array('原地址正则' => '新地址[空表示跳转到首页]')
$preg_array = array(
	'/index\.asp.*/i'                            => '',
	'/page\/link\.asp.*/i' 						=> 'links.html',
	'/WebAction\/2\/.*/i' 						=> 'web_action/students/index.html',
	'/webAction\/2011daojishi\/.*/i' 			=> '',
	'/webaction\/2011middleautumn\/.*/i'		=> '',
	'/WebAction\/2012NewYear\/.*/i' 			=> '',
	'/WebAction\/2012YellowStone\/.*/i' 		=> 'web_action/2012yellow_stone/index.html',
	'/webaction\/YellowStone_house\/?.*/i' 		=> 'web_action/yhuts/index.html',
	'/WebAction\/3\/.*/i' 						=> 'web_action/shopping/index.html',
	'/WebAction\/4\/.*/i' 						=> 'web_action/familyfun/index.html',
	'/WebAction\/7\/.*/i' 						=> 'web_action/2012yellow_stone/index.html',
	'/webAction\/itTrip\/.*/i' 					=> 'web_action/googleapple/index.html',
	'/WebAction\/showAndPlane\/index\.html.*/i' => 'web_action/show_and_plane/index.html',
	'/webAction\/studyTour\/.*/i'				=> '', //海外游学
	'/WebCart\/cart\.asp.*/i'					=> '',
	'/WebCart\/pay\.asp.*/i'					=> '',
	'/WebHotel\/Hotel\.asp.*/i' 				=> 'jiudianyuding/', // 酒店
	'/WebOld\/about\-us\.asp.*/i' 				=> 'about_us.php', //关于我们 
	'/WebOld\/cancellation\-and\-refund\-policy\.asp.*/i' => 'change_plan.php',//变更取消退款条例
	'/WebOld\/card\.asp.*/i'					=> '',
	'/WebOld\/card_duoka\.asp.*/i'				=> '',
	'/WebOld\/card_join\.asp.*/i'				=> '',
	'/WebOld\/card_yika\.asp.*/i'				=> '',
	'/WebOld\/card_yongjin\.asp.*/i'			=> '',
	'/WebOld\/customer\-agreement\.asp.*/i'		=> 'order_agreement.php', //订购须知(订购流程)
	'/WebOld\/download_acknowledgement_card_billing\.asp.*/i' => 'payment.php', //付款方式
	'/WebOld\/feedback\.asp.*/i'				=> 'contact_us.php', //联系我们
	'/WebOld\/friend\.asp.*/i'					=> 'jiebantongyou/', //结伴同游 
	'/WebOld\/friendadd\.asp.*/i'				=> 'jiebantongyou/', //结伴同游
	'/WebOld\/login\.asp.*/i'					=> 'login.php',
	'/WebOld\/mail\.asp.*/i'					=> '',
	'/WebOld\/maillogin\.asp.*/i'				=> '',
	'/WebOld\/news\.asp.*/i'					=> 'announce.php',
	'/WebOld\/offers\.asp.*/i'					=> '',
	'/WebOld\/payment\-faq\.asp.*/i'				=> 'faq_question.php',
	'/WebOld\/place\.asp.*/i'					=> 'sinotour.php',//景点介绍
	'/WebOld\/privacy\-policy\.asp.*/i'			=> 'privacy-policy.php',
	'/WebOld\/profile\.asp.*/i'					=> 'login.php',
	'/WebOld\/reg\.asp.*/i'						=> 'create_account.php',
	'/WebOld\/sitemap\.asp.*/i'					=> 'sitemap.php',
	'/WebOld\/teamIntr\.asp.*/i'				=> 'team_introduced.php', //团队介绍
	'/WebOld\/tour\-inquiry\.asp.*/i'			=> '',
	'/WebPlane\/Plane\.asp.*/i'					=> '',
	'/WebTravel\/PlaceEnd\.asp.*/i'				=> 'sinotour.php',
	'/WebTravel\/PlaceStart\.asp.*/i'			=> 'sinotour.php',
	'/WebTravel\/travelForAutumn\.asp.*/i'  	=> '',
	'/WebVisa\/Visa\.asp.*/i'					=> 'qianzheng/',
	'/WebTravel\/IndexStartPlace\.asp\?.*?StartCity\=([^&]*)/i' => 'advanced_search_result.php?fcw=',
	'/WebTravel\/IndexEndPlace\.asp\?.*?EndCity\=([^&]*)/i' => 'advanced_search_result.php?tcw=',
	'/WebTravel\/IndexArea\.asp\?.*?id\=([^&]*)/i' => '', //大栏目跳转正则
	'/WebPlane\/Plane\.asp.*/i' 				=> '',
	//'/WebOld\/placedetails\.asp.*/i'			=> 'sinotour.php',	//此转向已在2013-09-23被余总叫取消
	'/WebOld\/travellist\.asp.*/i'				=> 'sinotour.php',
	'/WebOld\/adcontent\.asp.*/i'				=> '',
	'/WebOld\/friendview\.asp.*/i'				=> 'jiebantongyou/',
	'/WebTravel\/search\.asp\?.*?place1=([^&]*)/i' => 'advanced_search_result.php?fcw='
	
);

// 遍历所有规则，找出需要转向的地址
foreach ($preg_array as $key => $val) {
	// 如果有匹配的
	if (preg_match($key,$req_301,$matches) != 0) {
		// 如果有参数需要附带在新URL上
		if(!empty($matches[1])) {
			if ( is_numeric($matches[1]) == true) {
				// 大栏目跳转判断
				switch($matches[1]) {
					case '2': //美东
						$go_url = $go_domain . 'niuyuelvyou/packages/';
						break;
					case '3': //美西
						$go_url = $go_domain . 'luoshanjilvyou/packages/';
						break;
					case '4': //夏威夷
						$go_url = $go_domain . 'hawaii-tours/packages/';
						break;
					case '17': //加拿大
						$go_url = $go_domain . 'wengehualvyou/packages/';
						break;
					case '21': //欧洲
						$go_url = $go_domain . 'ouzhoulvyou/packages/';
						break;
					case '18': //亚洲 （新站没有 跳首页）
						$go_url = $go_domain;
						break;
				}
			} else {
				// 搜索景点跳转
				$go_url = $go_domain . $val . rawurlencode(iconv('utf-8','gb2312',urldecode($matches[1])));
			}
		} else {
			// 直接组合新的URL
			$go_url = $go_domain . $val;	
		}
		break;
	}
}

// 根据转向结果，进行转向
if ($go_url !== false) {
	header("HTTP/1.1 301 Moved Permanently");
   	header("Location: " . $go_url);	
	exit();
}

// 产品详细页转向
if (preg_match('/WebTravel\/Travel.asp\?.*?id\=([^&]+)(.*rid\=(\d+))?/i',$req_301,$matches) != 0) {
	//print_r($matches);
	if ($matches[1] != '') {
		$products_id_query = "select SQL_CACHE products_urlname from products where products_id='" . $matches[1] . "'";
		$products_url = mysql_query($products_id_query);
		$articles_id = mysql_fetch_row($products_url);
		if (is_array($articles_id)) {
			$gourl = $go_domain . $articles_id[0] . '.html';
			if (isset($matches[3]) && is_numeric($matches[3])) {
				$gourl .= '?ref=' . (int)$matches[3] . '&utm_source=' . (int)$matches[3] . '&utm_medium=af&utm_term=detaillink&affiliate_banner_id=1';
			}
			
			header("HTTP/1.1 301 Moved Permanently");
   			header("Location: " . $gourl);
   			exit();
		}
	}
}

?>