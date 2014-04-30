<?php
$_dir_name = dirname(__FILE__);

//如果是找不到图片(.gif, .jpg, .jpeg, .bmp, .png, .icon)的马上返回404,别浪费时间有几快就跑几快！
function _404_image_check($_dir_name) {
	if (preg_match('/\.(gif|jpg|jpeg|bmp|png|icon)$/', strtolower($_SERVER['REQUEST_URI']))) {
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		//写到tmp日志好让我们及时修正
		$filename = $_dir_name . '/tmp/404.log';
		$txt_conten = 'Referer:' . $_SERVER['HTTP_REFERER'] . "\t" . 'Uri:' . $_SERVER['REQUEST_URI'] . PHP_EOL;
		if (0 && !!$handle = fopen($filename, 'a')) { //暂时不写了，太多了妈的(先修正再说)
			fwrite($handle, $txt_conten);
		}
		exit();
	}
}
_404_image_check($_dir_name);

$seo_extension = '.html';
if (file_exists('includes/configure_local.php')) {
	include ('includes/configure_local.php');
} elseif (file_exists('includes/local/configure.php')) {
	include ('includes/local/configure.php');
} else {
	// include server parameters
	require ('includes/configure.php');
}

$daynamic_html_exenstion = '';
mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
mysql_select_db(DB_DATABASE);

//$req = substr($_SERVER['REQUEST_URI'], 13); // test2 site
//$req = substr($_SERVER['REQUEST_URI'], 1); // live site
//$_SERVER['HTTP_HOST'] = cn.test.com


$req = explode($_SERVER['HTTP_HOST'], HTTP_SERVER);

$req = substr(preg_replace('@^' . $req[1] . '@', '', $_SERVER['REQUEST_URI']), 1);

//$_SERVER['REQUEST_URI'] = jiebantongyou-content/tci-6686-tcp-2.html


/*
 * $req = str_replace('/reviews','',$req); $req =
 * str_replace('/traveler-photos','',$req); $req =
 * str_replace('/question-&-answer','',$req);
 */
//$req = str_replace('-product-details','',$req);


$req = str_replace('/introduction/', '/', $req);
$req = str_replace('/introductionCruises/', '/', $req);
$req = str_replace('/tours/', '/', $req);
$req = str_replace('/packages/', '/', $req);
$req = str_replace('/recommended/', '/', $req);
$req = str_replace('/special/', '/', $req);
$req = str_replace('/diy/', '/', $req);
$req = str_replace('/show/', '/', $req);

$req = str_replace('/maps/', '/', $req);

$req = str_replace('tours-search/', '', $req);

$req = str_replace('/&osCsid=', '/?osCsid=', $req);
$_GET['seeAll'] = $HTTP_GET_VARS['seeAll'] = false;
//echo $req;exit;
function checkformnu($checkstring, $full_url) {
	$newurl = explode($checkstring, $full_url);
	if ($newurl[0] != $full_url) {
		return $checkstring;
	} else {
		return '';
	}
}

$error404 = false;

// new url handling


// there might be a query string after the product's name. Extract it and set GET variables
if (strpos($req, '?') !== false) {
	list ($req, $query_string) = explode('?', $req);
	parse_str($query_string, $HTTP_GET_VARS);
	$_GET = $HTTP_GET_VARS;
	// globals will be set later
}

//echo $_SERVER['REQUEST_URI'];exit;
/*
 * if(checkformnu('default',$_SERVER['REQUEST_URI']) == 'default'){ $error404 =
 * false; $_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF =
 * 'seo_news.php'; }else
 */

if (checkformnu('/tours-search/', $_SERVER['REQUEST_URI']) == '/tours-search/') { //start of check search page
	$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'advanced_search_result.php';
	
	$GET_array_adv_serch = array ();
	
	$vars_adv_se = explode('/', $req);
	for ($i = 0, $n = sizeof($vars_adv_se); $i < $n; $i ++) {
		if (strpos($vars_adv_se[$i], '[]')) {
			$GET_array_adv_serch[substr($vars_adv_se[$i], 0, -2)][] = $vars_adv_se[$i + 1];
		} else {
			if (preg_match('/(\%[^\%]{2})+/', $vars_adv_se[$i + 1])) {
				$HTTP_GET_VARS[$vars_adv_se[$i]] = rawurldecode(str_replace('slash', '/', $vars_adv_se[$i + 1]));
			} else {
				$HTTP_GET_VARS[$vars_adv_se[$i]] = str_replace('slash', '/', $vars_adv_se[$i + 1]);
			}
		}
		$i ++;
	}
	
	if (sizeof($GET_array_adv_serch) > 0) {
		while (list ($key, $value) = each($GET_array_adv_serch)) {
			if (preg_match('/(\%[^\%]{2})+/', $vars_adv_se[$i + 1])) {
				$HTTP_GET_VARS[$key] = rawurldecode(str_replace('slash', '/', $value));
			} else {
				$HTTP_GET_VARS[$key] = str_replace('slash', '/', $value);
			}
		}
	}
} else {
	
	$cat_mnu_sel = '';
	switch ($_SERVER['REQUEST_URI']) {
		case checkformnu('/question-&-answer', $_SERVER['REQUEST_URI']) == '/question-&-answer':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "qanda";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/question-&-answer';
			break;
		case checkformnu('/prices', $_SERVER['REQUEST_URI']) == '/prices':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "prices";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/prices';
			break;
		case checkformnu('/cruisesIntroduction', $_SERVER['REQUEST_URI']) == '/cruisesIntroduction':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "cruisesIntroduction";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/cruisesIntroduction';
			break;
		case checkformnu('/departure', $_SERVER['REQUEST_URI']) == '/departure':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "departure";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/departure';
			break;
		case checkformnu('/notes', $_SERVER['REQUEST_URI']) == '/notes':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "notes";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/notes';
			break;
		case checkformnu('/frequentlyqa', $_SERVER['REQUEST_URI']) == '/frequentlyqa':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "frequentlyqa";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/frequentlyqa';
			break;
		case checkformnu('/reviews', $_SERVER['REQUEST_URI']) == '/reviews':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "reviews";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/reviews';
			break;
		case checkformnu('/traveler-photos', $_SERVER['REQUEST_URI']) == '/traveler-photos':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "photos";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/traveler-photos';
			break;
		case checkformnu('/video', $_SERVER['REQUEST_URI']) == '/video':
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "video";
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/video';
			break;
		case checkformnu('/introduction/', $_SERVER['REQUEST_URI']) == '/introduction/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "introduction";
			break;
		case checkformnu('/introductionCruises/', $_SERVER['REQUEST_URI']) == '/introductionCruises/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "introductionCruises";
			break;
		case checkformnu('/tours/', $_SERVER['REQUEST_URI']) == '/tours/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "tours";
			break;
		case checkformnu('/packages/', $_SERVER['REQUEST_URI']) == '/packages/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "vcpackages";
			break;
		case checkformnu('/recommended/', $_SERVER['REQUEST_URI']) == '/recommended/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "recommended";
			break;
		case checkformnu('/special/', $_SERVER['REQUEST_URI']) == '/special/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "special";
			break;
		case checkformnu('/diy/', $_SERVER['REQUEST_URI']) == '/diy/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "diy";
			break;
		case checkformnu('/show/', $_SERVER['REQUEST_URI']) == '/show/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "show";
			break;
		case checkformnu('/maps/', $_SERVER['REQUEST_URI']) == '/maps/':
			$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "maps";
			break;
		case checkformnu('/all-questions', $_SERVER['REQUEST_URI']) == '/all-questions': //检查全部问题 vincent 2011-3-28
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "qanda";
			$_GET['seeAll'] = $HTTP_GET_VARS['seeAll'] = 'all-questions';
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/all-questions';
			break;
		case checkformnu('/all-reviews', $_SERVER['REQUEST_URI']) == '/all-reviews': //检查全部评论
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "reviews";
			$_GET['seeAll'] = $HTTP_GET_VARS['seeAll'] = 'all-reviews';
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/all-reviews';
			break;
		case checkformnu('/all-photos', $_SERVER['REQUEST_URI']) == '/all-photos': //检查全部相片
			$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "photos";
			$_GET['seeAll'] = $HTTP_GET_VARS['seeAll'] = 'all-photos';
			$daynamic_html_exenstion = 'true';
			$explode_prod_url_string = '/all-photos';
			break;
	}
	
	//check for pagin star
	if (checkformnu('/articles/', $_SERVER['REQUEST_URI']) == '/articles/') {
		//echo $req;
		$explode_html_url = explode('/', $req);
		
		foreach ($explode_html_url as $key => $val) {
			$val = str_replace('.html', '', $val);
			$article_id_query = "select SQL_CACHE articles_id from articles where articles_seo_url='" . $val . "'";
			$article = mysql_query($article_id_query);
			if (list ($articles_id) = mysql_fetch_row($article)) {
				$HTTP_GET_VARS['articles_id'] = $articles_id;
				$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'article_info.php';
				break;
			}
		}
		
		//$get_url = str_replace('.html','',$url);
	}
	if (checkformnu('-p-', $_SERVER['REQUEST_URI']) == '-p-') {
		$reqimplode = explode('-p-', $req);
		//$req = trim($reqimplode[0]).'.html';
		$HTTP_GET_VARS['page'] = trim(str_replace('.html', '', $reqimplode[1]));
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('-p-' . $HTTP_GET_VARS['page'], '', $req));
	} //pagin value end
	

	if (checkformnu('/p-', $_SERVER['REQUEST_URI']) == '/p-') {
		
		$reqimplode = explode('/p-', $req);
		$reqexplodepage = explode('/', $reqimplode[1]);
		$HTTP_GET_VARS['page'] = trim($reqexplodepage[0]);
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('p-' . $HTTP_GET_VARS['page'] . '/', '', $req));
	}
	if (checkformnu('/rn-', $_SERVER['REQUEST_URI']) == '/rn-') {
		
		$reqimplode = explode('/rn-', $req);
		$reqexplodepage = explode('/', $reqimplode[1]);
		$HTTP_GET_VARS['rn'] = trim($reqexplodepage[0]);
		
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('rn-' . $HTTP_GET_VARS['rn'] . '/', '', $req));
	}
	if (checkformnu('/s-', $_SERVER['REQUEST_URI']) == '/s-') {
		
		$reqimpsort = explode('/s-', $req);
		$reqexplodesort = explode('/', $reqimpsort[1]);
		$HTTP_GET_VARS['sort'] = trim($reqexplodesort[0]);
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('s-' . $HTTP_GET_VARS['sort'] . '/', '', $req));
	}
	
	if (checkformnu('/vs-', $_SERVER['REQUEST_URI']) == '/vs-') {
		$reqimpsort = explode('/vs-', $req);
		$reqexplodesort = explode('/', $reqimpsort[1]);
		$HTTP_GET_VARS['sort1'] = trim($reqexplodesort[0]);
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('vs-' . $HTTP_GET_VARS['sort1'] . '/', '', $req));
	}
	if (checkformnu('/vp-', $_SERVER['REQUEST_URI']) == '/vp-') {
		
		$reqimplode = explode('/vp-', $req);
		//$req = trim($reqimplode[0]);
		$reqexplodepage = explode('/', $reqimplode[1]);
		$HTTP_GET_VARS['page1'] = trim($reqexplodepage[0]);
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('vp-' . $HTTP_GET_VARS['page1'] . '/', '', $req));
	}
	
	//check for pagin end
	

	if (checkformnu('/join-affiliate-program.html', $_SERVER['REQUEST_URI']) == '/join-affiliate-program.html') {
		$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'join-affiliate-program.php';
	}
} //end of check search page


if (checkformnu('/question_detail', $_SERVER['REQUEST_URI']) == '/question_detail') {
	$str_tmp = str_replace('.html', '', $req);
	$arr_tmp = explode('-', $str_tmp);
	if (isset($arr_tmp[1])) {
		$HTTP_GET_VARS['question_id'] = $arr_tmp[1];
		$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'question_detail.php';
		$error404 = false;
	} else {
		$error404 = true;
	}
}

//echo basename($PHP_SELF);


if (basename($PHP_SELF) == '404.php') {
	if ((substr($req, 0 - strlen($seo_extension)) == $seo_extension) || $daynamic_html_exenstion == 'true') { // product
		

		if ($daynamic_html_exenstion == 'true') {
			$explode_safe_url = explode($explode_prod_url_string, $req);
			$product_url = $explode_safe_url[0];
			//$sql = "select SQL_CACHE p.products_id, p2c.categories_id from products p, products_to_categories p2c where p.products_id = p2c.products_id and products_urlname = '" . mysql_real_escape_string($product_url) . "' Limit 1 ";
		} else {
			$product_url = substr($req, 0, strlen($req) - strlen($seo_extension));
			//$sql = "select SQL_CACHE p.products_id, p2c.categories_id from products p, products_to_categories p2c where p.products_id = p2c.products_id and products_urlname = '" . mysql_real_escape_string($product_url) . "' Limit 1";
		}
		
		$sql = "select SQL_CACHE products_id,regions_id,tour_type_icon from products where products_urlname = '" . mysql_real_escape_string($product_url) . "' Limit 1";
		//echo $sql; exit;
		//print_r($req);
		$product = mysql_query($sql);
		//if (list ($products_id, $current_category_id) = mysql_fetch_row($product)) {
		if ($row = mysql_fetch_array($product)) {
			/*
			 * 此功能已经取消 // Howard added 特定的团页面重定向 LL to PAR start $p_pids =
			 * array(1222,1232,1221,1231,358,482,688,355,357,715,360,361,362,686);
			 * $r_pids =
			 * array(147,656,143,655,312,313,343,139,141,315,317,216,318,319);
			 * if(count($p_pids) != sizeof($r_pids)){ die('Plx check array
			 * $p_pids and $r_pids on 404.php'); } foreach($p_pids as $key =>
			 * $val){ if($val==$products_id){ $products_id = $r_pids[$key];
			 * break; } } // Howard added 特定的团页面重定向 LL to PAR end
			 */
			
			$HTTP_GET_VARS['products_id'] = $row['products_id'];
			$HTTP_POST_VARS['tour_type_icon'] = $row['tour_type_icon'];
			$HTTP_POST_VARS['regions_id'] = $row['regions_id'];
			$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'product_info.php';
		} else {
			$error404 = true;
		}
	} else { // category
		$reqarr = explode('/', $req);
		foreach ($reqarr as $rk => $rv) {
			if ($rv == '')
				unset($reqarr[$rk]);
		}
		//最多支持3级目录
		$reqarr[1] = isset($reqarr[1]) ? $reqarr[1] : '';
		$reqarr[2] = isset($reqarr[2]) ? $reqarr[2] : '';
		$req0 = $reqarr[0] . '/';
		$req1 = $reqarr[0] . '/' . $reqarr[1] . '/';
		$req2 = $reqarr[0] . '/' . $reqarr[1] . '/' . $reqarr[2] . '/';
		//echo $req.'<br>'.$cat_mnu_sel;exit;
		//$sql = "select SQL_CACHE categories_id,categories_urlname from categories where categories_urlname = '" . mysql_real_escape_string($req0) . "' or categories_urlname = '" . mysql_real_escape_string($req1) . "' or categories_urlname = '" . mysql_real_escape_string($req2) . "' ORDER BY categories_urlname DESC";
		$sql = "select SQL_CACHE categories_id,categories_urlname from categories where categories_urlname = '" . mysql_real_escape_string($req0) . "' ORDER BY categories_urlname DESC";
		//echo $sql;exit;
		$cat = mysql_query($sql);
		if (list ($current_category_id, $current_categories_urlname) = mysql_fetch_row($cat)) {
			
			$_SERVER['PHP_SELF'] = 'index.php';
			$HTTP_SERVER_VARS['PHP_SELF'] = 'index.php';
			// SEO 用到的栏目ID by lwkai add 2013-04-07
			$HTTP_GET_VARS['seo_categories_id'] = $current_category_id;
			$PHP_SELF = 'index.php';
			if ($cat_mnu_sel == '') {
				$cat_mnu_sel = $_GET['cat_mnu_sel'] = $HTTP_GET_VARS['cat_mnu_sel'] = "tours";
			}
			if ($current_categories_urlname == $req2) {
				unset($reqarr[2], $reqarr[1]);
			}
			if ($current_categories_urlname == $req1) {
				unset($reqarr[1]);
			}
			unset($reqarr[0]);
		} else {
			$error404 = true;
		}
		foreach ($reqarr as $rk => $rv) {
			if (!empty($rv)) {
				list ($gk, $gv) = explode('-', $rv);
				if ($gk && $gv) {
					if (strlen($gk) > 0 && preg_match('/^GLOBALS/i', $gk)) {
						//exit('Request var not alow!');
					} else {
						${$gk} = $HTTP_GET_VARS[$gk] = $_GET[$gk] = $gv;
					}
				}
			}
		}
		//print_r($_GET);exit;
	}
} else {
	/*
	 * $urlname = substr($req, 0, strlen($req) - strlen($seo_extension)); $sql =
	 * "select stores_id from stores where stores_urlname = '" .
	 * mysql_real_escape_string($urlname) . "'"; $store = mysql_query($sql);
	 * if(list($stores_id) = mysql_fetch_row($store)) { // store
	 * $HTTP_GET_VARS['sId'] = $stores_id; }
	 */
}

//About Landing Page
require (dirname(__FILE__) . "/404/landing_page.php");

//目的地指南
require (dirname(__FILE__) . "/404/highlights_guide.php");
//友情连接
require (dirname(__FILE__) . "/404/links.php");
//结伴同游bbs
require (dirname(__FILE__) . "/404/new_travel_companion.php");
require (dirname(__FILE__) . "/404/travel_companion.php");

//客户咨询页列表
require (dirname(__FILE__) . "/404/all_question_answers.php");
//走四方专家
require_once (dirname(__FILE__) . "/404/tours-experts.php");

//SEO资讯
require (dirname(__FILE__) . "/404/seo_article.php");
//评价回访
require (dirname(__FILE__) . "/404/reviews.php");
//电子书和电子杂志
require (dirname(__FILE__) . "/404/magazine_Ebook.php");

// 老站URL 301 永久重定向 目前只处理产品ID by lwkai 2012-05-15
require (dirname(__FILE__) . "/404/301.php");

//网站地图xml
if (preg_match('/^sitemap\.xml/', $req)) {
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'create_site_map.php';
	$error404 = false;
}
//网站地图html
if (preg_match('/^sitemap\.html/', $req)) {
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'sitemap.php';
	$error404 = false;
}
//签证
require (dirname(__FILE__) . "/404/visa.php");
//攻略
require (dirname(__FILE__) . "/404/raiders.php");

//////////真正的404与非404处理//////////////////
if ($error404 === true) {
	
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	define('NO_SET_SNAPSHOT', true);
	require_once ('includes/application_top.php');
	$BreadOff = true;
	//Why Login after can not go to snapshot? if $display_error_on_current_page == true
	$display_error_on_current_page = false;
	if ($display_error_on_current_page == true) {
		$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'index.php';
		$HTTP_GET_VARS['error_message'] = 'Error 404 - The page you are looking for was not found on this server.';
		require ('index.php');
	} else {
		$content = '404';
		require_once (DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE); //模板加载页面
		require (DIR_FS_INCLUDES . 'application_bottom.php');
		exit();
	}
} else {
	
	$max_page_age = 10; //缓存多长时间(注意如果.htaccess中有设置由以.htaccess中的为准，因为IE浏览器遇到session的时候就不缓存了)
	$ExpiresTag = $max_page_age; //Etag标签信息
	$ExpiresTag .= ($max_page_age < 10) ? time() : substr(time(), 0, -(strlen($max_page_age) - 1));
	$ExpiresTag .= $_COOKIE['customer_id'] . $_COOKIE['osCsid'] . $_COOKIE['login_id'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['REQUEST_URI'];
	//$ExpiresTag .= $_SERVER['REQUEST_URI'];
	
	$ExpiresTag = md5($ExpiresTag);
	$headerDate = gmdate("D, d M Y H:i:s", time()) . " GMT";
	$headerExpires = gmdate("D, d M Y H:i:s", (time() + $max_page_age)) . " GMT";
	$headerLastModified = gmdate("D, d M Y H:i:s",time()) . " GMT";
	//echo $ExpiresTag; exit;
	header("Cache-Control: private,must-revalidate,max-age=" . $max_page_age);	//http/1.1 新增的，类似Expires但比它更准确(有max-age,public)
	header("Date: " . $headerDate);
	header("Expires: " . $headerExpires); 				//此值是http/1.0的产品(IE6等低版本的浏览器可用)
	header("Last-Modified: " . $headerLastModified);
	//header("Pragma: public");
	
	if ($_SERVER["HTTP_IF_MODIFIED_SINCE"]) { //上一次修改(浏览)的时间(对应Last-Modified),此值ie与火狐等浏览器的格式不一样，注意区别
		$tmp_since = preg_replace('/GMT.+/', 'GMT', $_SERVER["HTTP_IF_MODIFIED_SINCE"]);
		//$tmp_nowtime = gmdate("D, d M Y H:i:s") . " GMT"; //现在的时间
		$tmp_subval = (time() - strtotime($tmp_since)); //上次访问到现在隔了多长时间
	}
	//304直接滚回给浏览器，减轻服务器负担
	if ($_SERVER["HTTP_IF_NONE_MATCH"] == $ExpiresTag || ($tmp_subval && $tmp_subval < $max_page_age)) {
		header("Etag: " . $ExpiresTag, true, 304);
		header("HTTP/1.1 304 Not Modified");
		exit();
	}
	//正常的200状态
	header("Etag: " . $ExpiresTag);
	header("HTTP/1.1 200 OK");
	header("Status: 200 OK");
	
	// 设置全局GET变量给普通变量
	foreach ($HTTP_GET_VARS as $key => $value){
		$$key = $value;
	}
	$_GET = $HTTP_GET_VARS;
	//print_r($_GET);
	//exit($req);
	//echo $_SERVER['PHP_SELF'];exit;	
	include ($_SERVER['PHP_SELF']);
}
?>