<?php

/*
  $Id: application_top.php,v 1.1.1.1 2004/03/04 23:40:37 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// 随便过滤一些字符串形式的GET参数
if(isset($_GET)){
	foreach($_GET as $key => $val){
		if(is_string($val)){
			$_GET[$key] = $HTTP_GET_VARS[$key] = str_replace(array('>','<'),array('&gt;','&lt;'),strip_tags($val));
		}
	}
}
if(isset($_SERVER['REQUEST_URI'])){
	$_SERVER['REQUEST_URI'] = str_replace(array('>','<'),array('&gt;','&lt;'),strip_tags($_SERVER['REQUEST_URI']));
}
if(isset($_SERVER['REDIRECT_QUERY_STRING'])){
	$_SERVER['REDIRECT_QUERY_STRING'] = str_replace(array('>','<'),array('&gt;','&lt;'),strip_tags($_SERVER['REDIRECT_QUERY_STRING']));
}
// check client ip
if(file_exists('tmp/Black_List_Ip.txt')){
	$tmp_text = file('tmp/Black_List_Ip.txt');
	if(getenv('HTTP_CLIENT_IP')){
		$client_ip = getenv('HTTP_CLIENT_IP');
	}elseif(getenv('HTTP_X_FORWARDED_FOR')){
		$client_ip = getenv('HTTP_X_FORWARDED_FOR');
	}elseif(getenv('REMOTE_ADDR')){
		$client_ip = getenv('REMOTE_ADDR');
	}else{
		$client_ip = $_SERVER['REMOTE_ADDR'];
	}
	//封锁或解封
	foreach((array)$tmp_text as $key => $val){
		if(trim($val) == $client_ip){
			//解封
			if(isset($_POST['stopipsolve']) && $_POST['stopipsolve']=="1"){
				unset($tmp_text[$key]);
				@file_put_contents ('tmp/Black_List_Ip.txt', $tmp_text, LOCK_EX);
				header('Location: http://'.$_SERVER['HTTP_HOST']);
				exit;
			}
			//封锁
			echo ('Top Your Ip '.$client_ip.' has stop! <form action="/" method="post" target="_self"><input type="hidden" name="stopipsolve" value="1" /><button type="submit">Click here to solve</button></form> ');
			exit;
		}
	}
}
// set timezone
//ini_set('date.timezone','Asia/Shanghai');
ini_set('date.timezone','UTC-07:00');
// start the timer for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());
define('_TEP_ROOT_DIR_', dirname(__FILE__) . '/../');
// set the level of error reporting
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

// check if register_globals is enabled.
// since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
if (function_exists('ini_get')) {
    ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
}

define("BACK_PATH", "");

// Set the local configuration parameters - mainly for developers
if (file_exists('includes/configure_local.php')) {
    include('includes/configure_local.php');
} elseif (file_exists('includes/local/configure.php')) {
    include('includes/local/configure.php');
} else {
    require('includes/configure.php');
}
if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
        header('Location: install/index.php');
    }
}

if(IS_LIVE_SITES === true){
	define('IS_PROD_SITE',"1");
}

/*
// 自动加载类{
function __autoload($class_name){
	require_once DIR_FS_CLASSES.$class_name . '.php';
}
// 自动加载类}
*/

if((!defined('IS_LIVE_SITES') || IS_LIVE_SITES!=true) && (!defined('IS_QA_SITES') || IS_QA_SITES!=true) && (!defined('IS_DEV_SITES') || IS_DEV_SITES!=true)){
	die("Please define your local configure. define IS_DEV_SITES, IS_QA_SITES and IS_LIVE_SITES. please see config/dev/includes/configure.php and config/dev/admin/includes/configure.php");
}

//非登录\注册\支付\页面都不要用443端口
if($_SERVER['SERVER_PORT']=='443' && $_SERVER['REQUEST_URI'] && strpos($_SERVER['REQUEST_URI'],'payment') === false && !in_array(basename($_SERVER['PHP_SELF']), array('login.php','create_account.php')) ){
	header('Location: '.HTTP_SERVER.$_SERVER['REQUEST_URI']);
}
// define the project version
//  define('PROJECT_VERSION', '[CRE Loaded6 v6.1]');
include('includes/version.php');

// set the type of request (secure or not)
$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
if (!isset($PHP_SELF))
    $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
} else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}

// include the list of project filenames
require(DIR_FS_INCLUDES . 'filenames.php');

// include the list of project database tables
require(DIR_FS_INCLUDES . 'database_tables.php');

// include score_constant.php howard added
require(DIR_FS_INCLUDES . 'score_constant.php');

// customization for the design layout
//define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
// include the database functions
require(DIR_FS_FUNCTIONS . 'database.php');

//添加MCache模块 提供缓存功能
define('USE_MCACHE',false);
 require_once DIR_FS_CATALOG."includes/classes/mcache.php";

$mcache = MCache::instance();
// make a connection to the database... now
tep_db_connect() or die('Unable to connect to database server!');

//缓存网站配置文件,categories, BEGIN
//vincent 2011-4-21
/*$cache_cfg = $mcache->fetch('site_main_config',MCache::WEEK);
if( $cache_cfg== null){
	$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
	$cache_cfg = array();
	while ($configuration = tep_db_fetch_array($configuration_query)) {
		$cache_cfg[] = $configuration;
	}
	$mcache->add('site_main_config',$cache_cfg);
}
foreach($cache_cfg as $configuration) define($configuration['cfgKey'], $configuration['cfgValue']);*/
$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
$cache_cfg = array();
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}
// set the application parameters
/*
$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
}*/
//缓存网站配置文件 END
//====================================
define('_SMARTY_ROOT_', _TEP_ROOT_DIR_ . '/smarty-2.0/libs/');
define('_SMARTY_TPL_', DIR_FS_TEMPLATES . 'smarty/');
define('_SMARTY_TPLC_', _TEP_ROOT_DIR_ . 'templates_c/');
//====================================
////
//旧团购相关常量
//
define('GROUP_BUY_ON', false); //旧团购总开关
define('GROUP_MIN_GUEST_NUM', 10); //旧团购人数下限
//define('DISCOUNT_PERCENTAGE', 0.05); 
/*优惠幅度5%，20%>GP≥13%时，团购折扣为3%，GP≥20%时，团购折扣为5%，GP<13%时没有优惠，此常量已经失效。被函数auto_get_group_buy_discount替代 */
define('DECIMAL_DIGITS', 0); //优惠总额小数点位数，0为优惠的值为整数，采用四舍五入
define('GROUP_BUY_INCLUDE_SUB_TOUR', false); //是否包括短期团，即无房间的团
// if gzip_compression is enabled, start to buffer the output
include(DIR_FS_INCLUDES . 'application_top_gzip.php');

// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
        $GET_array = array();
        $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
        $vars = explode('/', substr(getenv('PATH_INFO'), 1));
        for ($i = 0, $n = sizeof($vars); $i < $n; $i++) {
            if (strpos($vars[$i], '[]')) {
                $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i + 1];
            } else {
                $HTTP_GET_VARS[$vars[$i]] = $vars[$i + 1];
            }
            $i++;
        }

        if (sizeof($GET_array) > 0) {
            while (list($key, $value) = each($GET_array)) {
                $HTTP_GET_VARS[$key] = $value;
            }
        }
    }
}

// define general functions used application-wide
require(DIR_FS_FUNCTIONS . 'general.php');
require(DIR_FS_FUNCTIONS . 'html_output.php');
require(DIR_FS_FUNCTIONS . 'zhh_function.php');
require(DIR_FS_FUNCTIONS . 'from_email_auto_login.php');

require(DIR_FS_FUNCTIONS . 'score.php');
require(DIR_FS_FUNCTIONS . 'google_analysis.php');
require(DIR_FS_FUNCTIONS . 'chinese_lunar.php');
// 404 location
 if(stristr($HTTP_GET_VARS['error_message'],'Error 404')||stristr($HTTP_GET_VARS['error_message'],'Error404')){
  	tep_redirect(tep_href_link('404.php', '', NONSSL));
}	
// set the cookie domain
$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
if (USE_CACHE == 'true')
    include(DIR_FS_FUNCTIONS . 'cache.php');

// include shopping cart class
require(DIR_FS_CLASSES . 'shopping_cart.php');

// include easy discount products class
require(DIR_FS_CLASSES . 'easy_discount.php');

require(DIR_FS_CLASSES . 'word_press.php');
$WordPress = new word_press();

// include encoding class
require(DIR_FS_CLASSES . 'encoding.php');

$CharEncoding = new Encoding();
$CharEncoding->SetGetEncoding("BIG5") || die("The encode name is error!");
$CharEncoding->SetToEncoding("GBK") || die("The encode name is error!");

// begin PayPal_Shopping_Cart_IPN 2.8 (DMG)
require(DIR_FS_MODULES . 'payment/paypal/classes/osC/osC.class.php');
// end PayPal_Shopping_Cart_IPN
require(DIR_FS_MODULES . 'double_room_preferences.php');

// include navigation history class
require(DIR_FS_CLASSES . 'navigation_history.php');

// some code to solve compatibility issues
require(DIR_FS_FUNCTIONS . 'compatibility.php');

// check if sessions are supported, otherwise use the php3 compatible session class
if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCsid');
    define('PHP_SESSION_PATH', $cookie_path);
    define('PHP_SESSION_DOMAIN', $cookie_domain);
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_FS_CLASSES . 'sessions.php');
}

// define how the session functions will be used
require(DIR_FS_FUNCTIONS . 'sessions.php');

// set the session name and save path
tep_session_name('osCsid');
tep_session_save_path(SESSION_WRITE_DIRECTORY);



if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
} elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
}

// set the session ID if it exists
if (isset($HTTP_POST_VARS[tep_session_name()])) {
    tep_session_id($HTTP_POST_VARS[tep_session_name()]);
} elseif (($request_type == 'SSL') && isset($HTTP_GET_VARS[tep_session_name()])) {
    tep_session_id($HTTP_GET_VARS[tep_session_name()]);
}

// start the session
$session_started = false;
if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time() + 60 * 60 * 24 * 30, $cookie_path, $cookie_domain);

    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {
        tep_session_start();
        $session_started = true;
    }
} elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (tep_not_null($user_agent)) {
        $spiders = file(DIR_FS_INCLUDES . 'spiders.txt');

        for ($i = 0, $n = sizeof($spiders); $i < $n; $i++) {
            if (tep_not_null($spiders[$i])) {
                if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
                    $spider_flag = true;
                    break;
                }
            }
        }
    }

    if ($spider_flag == false) {
        tep_session_start();
        $session_started = true;
    }
} else {
    tep_session_start();
    $session_started = true;
}

// include the password crypto functions
require(DIR_FS_FUNCTIONS . 'password_funcs.php');

//客户自动登录类
require(DIR_FS_CLASSES . 'auto_login.php');
$autoLogin = new auto_login($_GET);
//页面顶部登录状态判断与输出
if($_GET['action']=='TopMiniLoginBoxCheck' && $ajax){
	echo iconv('gb2312','utf-8//IGNORE',$autoLogin->displayTopMiniLoginBox());
	exit;
}

//amit added to set seo structure start  11/02/2006
define(SEO_EXTENSION, '.html');

// SEOv2.0
if (isset($HTTP_GET_VARS['products_id']) && $HTTP_GET_VARS['products_id'] != '' && (int) $current_category_id != (int) $_SESSION['last_visited_cat'] && (int) $_SESSION['last_visited_cat']) {
//howard fixed 2010-01-21 start
//amit added to check session cat within product
    $include_child_cat = true;
    if ($include_child_cat == true) {
        $child_cat = array(0 => (int) $_SESSION['last_visited_cat']);
        tep_get_subcategories($child_cat, (int) $_SESSION['last_visited_cat']);
        $child_cat_str = implode(',', $child_cat);
        $where_cat_str = ' and p2c.categories_id in(' . $child_cat_str . ') ';
    } else {
        $where_cat_str = ' and p2c.categories_id ="' . (int) $_SESSION['last_visited_cat'] . '" ';
    }


    $check_product_cat_sql = "select p.products_id, p2c.categories_id from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_id ='" . (int) $HTTP_GET_VARS['products_id'] . "' " . $where_cat_str;
//echo $check_product_cat_sql;
//howard fixed 2010-01-21 end

    $check_product_cat_row = tep_db_query($check_product_cat_sql);
    if (tep_db_num_rows($check_product_cat_row) > 0) {
        $current_category_id = $_SESSION['last_visited_cat'];
    }
}

//$current_category_id = 142;

if (!empty($current_category_id)) {
//scs commented: BOF
//tep_get_parent_categories($cPath, $current_category_id);
//scs commented: EOF	
//scs added BOF
    $_SESSION['last_visited_cat'] = $current_category_id;

    if (tep_get_parent_categories($cPath, $current_category_id)) {
        
    } else {
        $cPath = @array_reverse($cPath);
    }
    //scs added EOF    
    $cPath[''] = $current_category_id;
    $_GET['cPath'] = $HTTP_GET_VARS['cPath'] = $cPath = @implode('_', $cPath);
}
//amit added to set seo sturcture end
//bof of james add
require(DIR_FS_INCLUDES . 'header_seo.php');
//eof of james add
// set SID once, even if empty
$SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
if (($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true)) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
        $SESSION_SSL_ID = $ssl_session_id;
        tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
        tep_session_destroy();
        tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
}

// verify the browser user agent if the feature is enabled
if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
        $SESSION_USER_AGENT = $http_user_agent;
        tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
        tep_session_destroy();
        tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
}

// verify the IP address if the feature is enabled
if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();
    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
        $SESSION_IP_ADDRESS = $ip_address;
        tep_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
		tep_session_destroy();
        tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
	
	//成都IP暂时不开放{
	$not_check_cd_ip = true;
	if(tep_session_is_registered('has_write_cd_ips_file')){
		//$not_check_cd_ip = false;
		$not_check_cd_ip = true;
	}else{
		$has_write_cd_ips_file = true;
		tep_session_register('has_write_cd_ips_file');
	}
	
	$cd_ips_file = DIR_FS_CATALOG.'/tmp/cd_ips.txt';
	if(file_exists($cd_ips_file)){		
		$cd_ips = file_get_contents($cd_ips_file);
		if(preg_match('/'.preg_quote($ip_address).'/im', $cd_ips)){
			//die('SQL error!');
		}
	}
	//}
	//print_r($_SESSION);
}

// create the shopping cart & fix the cart if necesary
if (tep_session_is_registered('cart') && is_object($cart)) {
    if (PHP_VERSION < 4) {
        $broken_cart = $cart;
        $cart = new shoppingCart;
        $cart->unserialize($broken_cart);
        //file_put_contents('/var/www/html/888trip.com/wwwroot/123456.txt', date('Y-m-d H:i:s') . 'tep_session_is_registered(\'cart\')=' . tep_session_is_registered('cart') . "\n" . 'is_object($cart)=' . is_object($cart) . "\n" . 'PHP_VERSION=' . PHP_VERSION . "\n\n");
    }
} else {
    tep_session_register('cart');
    $cart = new shoppingCart;
}

// begin PayPal_Shopping_Cart_IPN V2.8 DMG
//   require_once DIR_FS_MODULES . 'payment/paypal/functions/paypal.fnc.php';
PayPal_osC::check_order_status(true);
// end PayPal_Shopping_Cart_IPN
// include currencies class and create an instance
require(DIR_FS_CLASSES . 'currencies.php');
$currencies = new currencies();

if (!tep_session_is_registered('easy_discount')) {
    tep_session_register('easy_discount');
}

$easy_discount = new easy_discount();

// include the mail classes
require(DIR_FS_CLASSES . 'mime.php');
require(DIR_FS_CLASSES . 'email.php');

/* / howard added for language
  if(!isset($_COOKIE['language']) && !isset($HTTP_GET_VARS['language'])){
  $lcoal_ip = tep_get_ip_address();
  $sql = tep_db_query("select count(*) as total from china_ip_range where begin_n <= INET_ATON('".$lcoal_ip."') AND end_n >= INET_ATON('".$lcoal_ip."');");
  $res_total = tep_db_result($sql,"0","total");
  if($res_total>0){
  $HTTP_GET_VARS['language']='sc';
  }else{
  $HTTP_GET_VARS['language']='tw';
  }
  setcookie("language", $HTTP_GET_VARS['language'], time() +(3600*24*30*365*10));
  }elseif($HTTP_GET_VARS['language']=='tw' || $HTTP_GET_VARS['language']=='sc'){
  setcookie("language", $HTTP_GET_VARS['language'], time() +(3600*24*30*365*10));
  }elseif($_COOKIE['language']=='tw' || $_COOKIE['language']=='sc'){
  $HTTP_GET_VARS['language'] = $_COOKIE['language'];
  }
 */

if ((HTTP_SERVER == SCHINESE_HTTP_SERVER || $_SERVER['HTTP_HOST'] == 'gb.usitrip.com') && ($language == 'tchinese' || !tep_session_is_registered('language'))) {
    $HTTP_GET_VARS['language'] = 'sc';
}

if ((HTTP_SERVER == TW_CHINESE_HTTP_SERVER || $_SERVER['HTTP_HOST'] == 'big5.usitrip.com') && ($language == 'schinese' || !tep_session_is_registered('language'))) {
    $HTTP_GET_VARS['language'] = 'tw';
}
/* if not PREF auto judge ip */
if (AUTO_JUDGE_IP_ON == 'true' && HTTP_SERVER == TW_CHINESE_HTTP_SERVER && !tep_session_is_registered('language') && !tep_session_is_registered('PREF')) {
    $PREF = '1';
    tep_session_register('PREF');
    require(DIR_FS_FUNCTIONS . 'auto_judge_ip.php');
//if(auto_judge_ip(get_client_ip())){
//tep_redirect(SCHINESE_HTTP_SERVER.$_SERVER['REQUEST_URI']);
//}
    //浏览器语言判断，等用户的DNS都更新后再恢复
	if (get_browser_lange() == 'zh-cn' && ($_SERVER['HTTP_HOST'] != 'gb.usitrip.com' && $_SERVER['HTTP_HOST'] != 'big5.usitrip.com')) {
        
		//tep_redirect(SCHINESE_HTTP_SERVER . $_SERVER['REQUEST_URI']);
        //exit;
    }

//if(preg_match('/google\.cn/',$HTTP_SERVER_VARS['HTTP_REFERER'])){
//echo $HTTP_SERVER_VARS['HTTP_REFERER'];
//}

    $patterns_r = array();
    $patterns_r[0] = '/google\.cn/';
    $patterns_r[1] = '/baidu\.com/';
    $patterns_r[2] = '/one\.cn\.yahoo\.com/';

    for ($i = 0; $i < count($patterns_r); $i++) {
        if (preg_match($patterns_r[$i], $HTTP_SERVER_VARS['HTTP_REFERER'])) {
            tep_redirect(SCHINESE_HTTP_SERVER . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}

// set the language
if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
        tep_session_register('language');
        tep_session_register('languages_id');
    }

    include(DIR_FS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
        $lng->set_language($HTTP_GET_VARS['language']);
    } else {
        $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
}

//fixed bugs 
if (!tep_not_null($language)) {
    $language = 'tchinese';
    $languages_id = '1';
}
//fixed bugs end
// include the language translations
require(DIR_FS_LANGUAGES . $language . '.php');

// currency
if (!tep_session_is_registered('currency') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) )) {
    if (!tep_session_is_registered('currency'))
        tep_session_register('currency');

    if (isset($HTTP_GET_VARS['currency'])) {
        if (!$currency = tep_currency_exists($HTTP_GET_VARS['currency']))
            $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
        $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
}

// navigation history
//$navigation = new navigationHistory;
if (tep_session_is_registered('navigation')) {
    if (PHP_VERSION < 4) {
        $broken_navigation = $navigation;
        $navigation = new navigationHistory;
        $navigation->unserialize($broken_navigation);
    } else {
    	$navigation = $_SESSION['navigation'];
    }
} else {
    tep_session_register('navigation');
    $navigation = new navigationHistory;
}

if(!is_object($navigation)){
	$navigation = new navigationHistory;
}

$navigation->add_current_page();
// BOF: Down for Maintenance except for admin ip
if (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != getenv('REMOTE_ADDR')) {
    if (DOWN_FOR_MAINTENANCE == 'true' and !strstr($PHP_SELF, DOWN_FOR_MAINTENANCE_FILENAME)) {
        tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME));
    }
}
// do not let people get to down for maintenance page if not turned on
if (DOWN_FOR_MAINTENANCE == 'false' and strstr($PHP_SELF, DOWN_FOR_MAINTENANCE_FILENAME)) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}
// EOF: WebMakers.com Added: Down for Maintenance
// BOF: WebMakers.com Added: Functions Library
include(DIR_FS_FUNCTIONS . 'webmakers_added_functions.php');

//如果IP号给封了，就停止访问
if (check_ip_status() == false) {
    die('Your IP has stop use.');
}

include(DIR_FS_FUNCTIONS . 'get_avaliabledate.php');
// EOF: WebMakers.com Added: Functions Library
// Shopping cart actions
// Lango added for template BOF:
require(DIR_FS_INCLUDES . 'template_application_top.php');
// Lango added for template EOF:
// initialize the message stack for output messages
require(DIR_FS_CLASSES . 'message_stack.php');
$messageStack = new messageStack;

if (isset($HTTP_GET_VARS['action'])) {
	// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
        tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
        $goto = FILENAME_SHOPPING_CART;
        $parameters = array('action', 'cPath', 'products_id', 'pid', 'mnu');
    } else {
        $goto = basename($PHP_SELF);
        if ($HTTP_GET_VARS['action'] == 'buy_now') {
            $parameters = array('action', 'pid', 'products_id');
        } else {
            $parameters = array('action', 'pid');
        }
    }
    switch ($HTTP_GET_VARS['action']) {
    	// customer wants to update the product quantity in their shopping cart
        case 'update_product' :
            for ($i = 0, $n = sizeof($HTTP_POST_VARS['products_id']); $i < $n; $i++) {
                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {                	
                    $cart->remove($HTTP_POST_VARS['products_id'][$i]);//删除选中的产品
                } else { }
            }
            tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
            break;            
       // customer adds a product from the products page
        case 'add_product' :  include(DIR_FS_CATALOG."/includes/application_top_cart_add_product.php"); break; 
        // Modificacion Wishlist Checkboxes
        case 'add_products_wishlist' : if (isset($HTTP_POST_VARS['add_wishprod'])) {
                if ($HTTP_POST_VARS['borrar'] == 0) {
                    foreach ($HTTP_POST_VARS['add_wishprod'] as $value) {
                        if (ereg('^[0-9]+$', $value)) {
                            tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = $value and customers_id = '" . $customer_id . "'");
                            $cart->add_cart($value, $cart->get_quantity(tep_get_uprid($value, $HTTP_POST_VARS['id'])) + 1, $HTTP_POST_VARS['id']);
                        }
                    }
                    tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                }
                if ($HTTP_POST_VARS['borrar'] == 1) {
                    foreach ($HTTP_POST_VARS['add_wishprod'] as $value) {
                        if (ereg('^[0-9]+$', $value)) {
                            tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = $value and customers_id = '" . $customer_id . "'");
                        }
                    }
                    tep_redirect(tep_href_link(FILENAME_WISHLIST));
                }
            }
            break;
        // Fin Wishlist Checkboxes
        // Add product to the wishlist
        case 'add_wishlist' :
        	 if (ereg('^[0-9]+$', $HTTP_GET_VARS['products_id'])) {
                if ($HTTP_GET_VARS['products_id']) {
                    tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
                    tep_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id, products_model, products_name, products_price) values ('" . $customer_id . "', '" . $products_id . "', '" . $products_model . "', '" . $products_name . "', '" . $products_price . "' )");
                }
            }
            tep_redirect(tep_href_link(FILENAME_WISHLIST));
            break;


        case 'wishlist_add_cart': reset($lvnr);
            reset($lvanz);
            while (list($key, $elem) = each($lvnr)) {
                (list($key1, $elem1) = each($lvanz));
                tep_db_query("update " . TABLE_WISHLIST . " set products_quantity = '" . $elem1 . "' where customers_id = '" . $customer_id . "' and products_id = '" . $elem . "'");
                tep_db_query("delete from " . TABLE_WISHLIST . " where customers_id= '" . $customer_id . "' and products_quantity = '999'");
                $produkte_mit_anzahl = tep_db_query("select * from " . TABLE_WISHLIST . " where customers_id = '" . $customer_id . "' and products_id = '" . $elem . "' and products_quantity<>'0'");

                while ($HTTP_GET_VARS = tep_db_fetch_array($produkte_mit_anzahl)) {
                    $cart->add_cart($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['products_quantity']);
                }
            }
            reset($lvanz);
            tep_redirect(tep_href_link(FILENAME_WISHLIST));
            break;


// remove item from the wishlist
        case 'remove_wishlist':
            tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $HTTP_GET_VARS['pid'] . "' and customers_id = '" . $customer_id . "'");
            tep_redirect(tep_href_link(FILENAME_WISHLIST));
            break;

// performed by the 'buy now' button in product listings and review page
        case 'buy_now' : if (isset($HTTP_GET_VARS['products_id'])) {
//Wishlist 2.0.1 Modification
                if (tep_session_is_registered('customer_id')) {
                    tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id= '" . $HTTP_GET_VARS['products_id'] . "'");
                }
// End Wishlist 2.0.1 Modification
                if (tep_has_product_attributes($HTTP_GET_VARS['products_id'])) {
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));
                } else {
                    $cart->add_cart($HTTP_GET_VARS['products_id'], $cart->get_quantity($HTTP_GET_VARS['products_id']) + 1);
                }
            }
            tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
            break;

        case 'notify' : if (tep_session_is_registered('customer_id')) {
                if (isset($HTTP_GET_VARS['products_id'])) {
                    $notify = $HTTP_GET_VARS['products_id'];
                } elseif (isset($HTTP_GET_VARS['notify'])) {
                    $notify = $HTTP_GET_VARS['notify'];
                } elseif (isset($HTTP_POST_VARS['notify'])) {
                    $notify = $HTTP_POST_VARS['notify'];
                } else {
                    tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                }
                if (!is_array($notify))
                    $notify = array($notify);
                for ($i = 0, $n = sizeof($notify); $i < $n; $i++) {
                    $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $notify[$i] . "' and customers_id = '" . $customer_id . "'");
                    $check = tep_db_fetch_array($check_query);
                    if ($check['count'] < 1) {
                        tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify[$i] . "', '" . $customer_id . "', now())");
                    }
                }
                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
            } else {
                $navigation->set_snapshot();
                if (tep_not_null($_COOKIE['LoginDate'])) {
                    $messageStack->add_session('login', LOGIN_OVERTIME);
                    setcookie('LoginDate', '');
                }
                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
            }
            break;
        case 'notify_remove' : if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['products_id'])) {
                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
                $check = tep_db_fetch_array($check_query);
                if ($check['count'] > 0) {
                    tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
                }
                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
            } else {
                $navigation->set_snapshot();
                if (tep_not_null($_COOKIE['LoginDate'])) {
                    $messageStack->add_session('login', LOGIN_OVERTIME);
                    setcookie('LoginDate', '');
                }
                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
            }
            break;

        case 'cust_order' : if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid'])) {
                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {


// Although the product has attributes we still delete it from the WISHLIST:
                    if ($rfw == 1)
                        tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$pid");
                    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid'], 'NONSSL'));
                } else {
// First delete from wishlist:
                    if ($rfw == 1)
                        tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$pid");

                    $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid']) + 1);
                }
            }
            tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
            break;
    }
}

// include the who's online functions
require(DIR_FS_FUNCTIONS . 'whos_online.php');
tep_update_whos_online();

// include validation functions (right now only email address)
require(DIR_FS_FUNCTIONS . 'validations.php');

// split-page-results
require(DIR_FS_CLASSES . 'split_page_results.php');

// Points/Rewards Module V2.1rc2a
require(DIR_FS_FUNCTIONS . 'redemptions.php');

// auto activate and expire banners
require(DIR_FS_FUNCTIONS . 'banner.php');
tep_expire_banners();
tep_activate_banners();


// auto expire special products
require(DIR_FS_FUNCTIONS . 'specials.php');
tep_activate_specials();
tep_expire_specials();

// auto expire featured products
require(DIR_FS_FUNCTIONS . 'featured.php');
tep_expire_featured();

// calculate category path
if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
} elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {
    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);
} else {
    $cPath = '';
}

if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array) - 1)];
} else {
    $current_category_id = 0;
}

// include the breadcrumb class and start the breadcrumb trail
require(DIR_FS_CLASSES . 'breadcrumb.php');
$breadcrumb = new breadcrumb;

if (!isset($_GET['TcPath'])) { //如果是在结伴同游BBS下面则不显首页导航路径
    $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
//$breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));
}

// add category names or the manufacturer name to the breadcrumb trail
if (isset($cPath_array) && !isset($_GET['TcPath'])) { //如果是在结伴同游BBS下面则不显目录导航路径
	//使用mcache - vincent	
	$cpath_array = MCache::search_categories('categories_id', $cPath_array,true);
	$categories_name_for_seo = '';
	if(count($cpath_array) > 0){
		$i=0;
		foreach($cpath_array as $categories){			
			$breadcrumb->add(db_to_html(preg_replace('/ .+/', '', $categories['categories_name'])), tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i + 1)))));
			$categories_name_for_seo = $categories['categories_name'];
			$i++;
		}
	}
	// 使用mcache
	/*
    for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
        $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int) $cPath_array[$i] . "' and language_id = '" . (int) $languages_id . "'");
        if (tep_db_num_rows($categories_query) > 0) {
            $categories = tep_db_fetch_array($categories_query);
            $breadcrumb->add(db_to_html(preg_replace('/ .+/', '', $categories['categories_name'])), tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i + 1)))));
        } else {
            break;
        }
    }*/
} elseif (isset($HTTP_GET_VARS['manufacturers_id'])) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int) $HTTP_GET_VARS['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturers_query)) {
        $manufacturers = tep_db_fetch_array($manufacturers_query);
        $breadcrumb->add(db_to_html($manufacturers['manufacturers_name']), tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));
    }
}

// add the products model to the breadcrumb trail
if (isset($HTTP_GET_VARS['products_id']) && !isset($_GET['TcPath'])) { //如果是在结伴同游BBS下面则不显示产品导航路径
    $model_query = tep_db_query("select products_model, products_info_tpl from " . TABLE_PRODUCTS . " where products_id = '" . (int) $HTTP_GET_VARS['products_id'] . "'");
    if (tep_db_num_rows($model_query)) {
        $model = tep_db_fetch_array($model_query);
        /* //添加拉斯维加斯秀的路径 strart
          if($model['products_info_tpl']=='product_info_vegas_show'){
          $breadcrumb->add(db_to_html('拉斯维加斯秀'), tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=show'));
          }
          //添加拉斯维加斯秀的路径 end */

        $breadcrumb->add(db_to_html($model['products_model']), tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $HTTP_GET_VARS['products_id']));
    }
}

//结伴同游bbs目录路径 start
//$Tccurrent_category_id 是指当前的结伴同游id
if (isset($_GET['TcPath'])) {
    $TcPath = $_GET['TcPath'];
}
if (tep_not_null($TcPath)) {
    $TcPath_array = tep_parse_category_path($TcPath);
    $TcPath = implode('_', $TcPath_array);
    $Tccurrent_category_id = $TcPath_array[(sizeof($TcPath_array) - 1)];
} else {
    $Tccurrent_category_id = 0;
}

if (isset($TcPath_array)) {

    $breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));

    for ($i = 0, $n = sizeof($TcPath_array); $i < $n; $i++) {
        $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int) $TcPath_array[$i] . "' and language_id = '" . (int) $languages_id . "'");
        if (tep_db_num_rows($categories_query) > 0) {
            $categories = tep_db_fetch_array($categories_query);
            $breadcrumb->add(db_to_html(preg_replace('/ .+/', '', $categories['categories_name'])), tep_href_link('new_travel_companion_index.php', 'TcPath=' . implode('_', array_slice($TcPath_array, 0, ($i + 1)))));
        } else {
            break;
        }
    }
}
//结伴同游bbs目录路径 end
// set which precautions should be checked
define('WARN_INSTALL_EXISTENCE', 'true');
//define('WARN_CONFIG_WRITEABLE', 'true');
define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
define('WARN_SESSION_AUTO_START', 'true');
define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');
//客服销售跟踪清除销售联盟标记
require(DIR_FS_CLASSES . 'servers_sales_track.php');
//servers_sales_track::clear_ref_info(); //根据sofia要求，生成一次单后再清除这些变量，所以不在这里处理
// 载入后台客服在前台操作客人前台的类
require(DIR_FS_CLASSES . 'adminToClient.php');
$Admin = new adminToClient((int)$_COOKIE['login_id'], (int)$_COOKIE['ParentOrdersId']);

// Include OSC-AFFILIATE
require(DIR_FS_INCLUDES . 'affiliate_application_top.php');
require(DIR_FS_INCLUDES . 'add_ccgvdc_application_top.php');

include('includes/application_top_cre_setting.php');

include('includes/application_top_newsdesk.php');
include('includes/application_top_faqdesk.php');


// BOF: WebMakers.com Added: Header Tags Controller v1.0
require(DIR_FS_FUNCTIONS . 'header_tags.php');

// Clean out HTML comments from ALT tags etc.
require(DIR_FS_FUNCTIONS . 'clean_html_comments.php');
// Also used by: WebMakers.com Added: FREE-CALL FOR PRICE
// EOF: WebMakers.com Added: Header Tags Controller v1.0
//DWD Modify: Information Page Unlimited 1.1f - PT
require(DIR_FS_FUNCTIONS . 'information_html_output.php');


//amit added to track cutomer refferal url start
// Ad Tracker Begin - Amit 
//user_tracking modications
if (!$referer_url) {
    if ($HTTP_SERVER_VARS['HTTP_REFERER'] && !eregi($HTTP_SERVER_VARS['HTTP_HOST'], $HTTP_SERVER_VARS['HTTP_REFERER'])) {
//if ($HTTP_SERVER_VARS['HTTP_REFERER']){
        $referer_url = $HTTP_SERVER_VARS['HTTP_REFERER'];
//session_register('referer_url'); 
        setcookie('referer_url', $referer_url, time() + AFFILIATE_COOKIE_LIFETIME);
//tep_session_register('referer_url'); DID NOT WORK
    }
}

if (tep_not_null($_GET['referer_url']) || tep_not_null($_POST['referer_url'])) {
    $referer_url = tep_not_null($_POST['referer_url']) ? $_POST['referer_url'] : $_GET['referer_url'];
    tep_session_register('referer_url');
}


// howard added
if (eregi('usitrip.com/landing-page/new_years_tours.html', $HTTP_SERVER_VARS['HTTP_REFERER']) && !eregi('usitrip.com/landing-page/new_years_tours.html', $HTTP_COOKIE_VARS['referer_url'])) {
    $referer_url = $HTTP_SERVER_VARS['HTTP_REFERER'];
    setcookie('referer_url', $referer_url, time() + AFFILIATE_COOKIE_LIFETIME);
}
// howard added end
//amit added to new yahoo tracking start  
if (isset($_GET["OVRAW"])) {
    $_GET["utm_source"] = 'yahoo';
    $_GET["utm_medium"] = 'cpc';
    $_GET["utm_term"] = $_GET["OVRAW"];
    $_GET["utm_content"] = "OVADID=" . $_GET["OVADID"] . "  OVKWID=" . $_GET["OVKWID"];
    $_GET["utm_campaign"] = $_GET["OVKEY"];
}
//amit added to new yahoo tracking end
// 以第一次来源为准
if(!$HTTP_COOKIE_VARS['customers_advertiser']){
	if (isset($_GET["utm_source"]) && ($_GET["utm_source"] != "SiteInnerAds" || !isset($_COOKIE['customers_advertiser']))) {
		$advertiser = $_GET["utm_source"];
		$customers_advertiser1 = $advertiser;
		$utm_medium = $_GET["utm_medium"];
		$utm_term = $_GET["utm_term"];
		$utm_content = $_GET["utm_content"];
		$utm_campaign = $_GET["utm_campaign"];
	
		$sql_data_array = array(
			'customers_advertiser' => tep_db_input($customers_advertiser1),
			'utm_medium' => tep_db_input($utm_medium),
			'utm_term' => tep_db_input($utm_term),
			'utm_content' => tep_db_input($utm_content),
			'utm_campaign' => tep_db_input($utm_campaign),
			'clicks_date' => 'now()',
			'cust_req_ip' => tep_get_ip_address());
		tep_db_perform(TABLE_AD_SOURCE_CLICKS_STORES, $sql_data_array);
		$customers_ad_click_id1 = tep_db_insert_id();
	
		session_register('advertiser');
	
		tep_setcookie('customers_advertiser', $customers_advertiser1, time() + AFFILIATE_COOKIE_LIFETIME, $cookie_path, $cookie_domain);
		tep_setcookie('customers_ad_click_id', $customers_ad_click_id1, time() + AFFILIATE_COOKIE_LIFETIME, $cookie_path, $cookie_domain);
	}
}



if ($HTTP_COOKIE_VARS['customers_advertiser']) { // Customer comes back and is registered in cookie
    $customers_advertiser = $HTTP_COOKIE_VARS['customers_advertiser'];
    $customers_ad_click_id = $HTTP_COOKIE_VARS['customers_ad_click_id'];
}


if ($HTTP_COOKIE_VARS['referer_url']) { // Customer comes back and is registered in cookie
    $referer_url = $HTTP_COOKIE_VARS['referer_url'];
}

// Ad Tracker End - Amit 
//zhh added for cart sum

$cart_sum = count($cart->contents);

//在帐户或邮件中要显示的用户名称
$first_or_last_name = $customer_first_name;

$current_page = ($_SERVER['SCRIPT_FILENAME']) ? (basename($_SERVER['SCRIPT_FILENAME'])) : basename($PHP_SELF);

//统计登录用户的总积分
if ((int) $customer_id) {
    $my_score_sum = get_user_score($customer_id);
}

//howard added country Default value
/* if(!(int)$country){
  $country = '223';	//China
  if(CHARSET==strtolower('big5')){	//tw or usa
  $country = '223';
  }
  } */
//howard added country Default value end
//howard added Sign in front of Record page
//a：当客户在某一个页面进行登录时，登录后应该返回到同以页面而不要到“我的走四方”首页；
//b：当客户在任何页面然后进行注册时，注册完成后统一进入到“我的走四方”首页；

$base_php_self = basename($_SERVER['PHP_SELF']);
if (!(int) $customer_id && $base_php_self != "login.php" && $base_php_self != "logoff.php" && $base_php_self != "create_account.php" && $base_php_self != "password_forgotten.php" && !preg_match('/ajax/', $base_php_self) && !defined('AJAX_MOD')
        && !tep_not_null($_GET['action']) && $base_php_self != "javascript.php" && !defined('NO_SET_SNAPSHOT')) {

    $navigation->set_snapshot();
}
if ($base_php_self == "create_account.php") {
    $navigation->clear_snapshot();
}
//howard added Sign in front of Record page end
//howard added if customer from mail to the site, he auto login.
//如果从电子邮件登录我们网站将设置为已经登录
if (!tep_session_is_registered('customer_id')) {
    from_email_auto_login();
}
//howard added if customer from mail to the site, he auto login. end
//howard add vote system points
if (tep_session_is_registered('customer_id') && (int) $customer_id) {
    $points_added = add_session_points($customer_id);
    if (tep_not_null($points_added)) {
        $messageStack->add('global', db_to_html($points_added), 'success');
    }
}
//howard add vote system points end
//记录推荐朋友的积分id号
if ((int) $_GET['refriend_points_unique_id'] && !tep_session_is_registered('refriend_points_unique_id')) {
    $refriend_points_unique_id = (int) $_GET['refriend_points_unique_id'];
    tep_session_register('refriend_points_unique_id');
}

//howard added validation js define
define('VALIDATION_JS', 'validation-2009-07-21-min.js');

//结伴同游帖子列表回帖每页最多的行数
define('TRAVEL_LIST_MAX_ROW', '9');

//设置搜索页翻页是否使用ajax方式
define('SEARCH_SPLIT_PAGE_USE_AJAX', false);

/////
//新年的市场活动：老客户推荐
//市场活动的周期为2009.12.18-2010.03.18
//2011-01-01至2011-12-31的老客户推荐也可再送积分，同时新客户还能使用老客户提供的优惠券
$Old_Customer_Testimonials_Start_Date = '2009-12-18 00:00:00';
$Old_Customer_Testimonials_End_Date = '2011-12-31 23:59:59';
$Today_date = date('Y-m-d H:i:s'); //任何情况下此日期均不可删除，它已被用作网站标准日期
$Old_Customer_Testimonials_Action = false;
if ($Today_date >= $Old_Customer_Testimonials_Start_Date && $Today_date <= $Old_Customer_Testimonials_End_Date) {
    $Old_Customer_Testimonials_Action = true;
}

//cpunc短信类
include(DIR_FS_CLASSES . 'cpunc_sms.php');
$cpunc = new cpunc_SMS;

//缩略图相关函数
include(DIR_FS_FUNCTIONS . 'thumbnails.php');

//成果网CPS合作API接口
include(DIR_FS_CATALOG . 'partners/chanet/api.php');
$chanets = new chanet_api;

//Smarty
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

require(_SMARTY_ROOT_ . "Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = _SMARTY_TPL_;
$smarty->compile_dir = _SMARTY_TPLC_;

$fileperms = substr(sprintf('%o', fileperms($smarty->compile_dir)), -4);
if($fileperms!="0777"){
	echo $smarty->compile_dir." Write Failure! Please setup 0777 for it's fileperms!";
	exit;
}

$smarty->caching = 0;
$smarty->left_delimiter = '{:';
$smarty->right_delimiter = ':}';
$smarty->php_handling = SMARTY_PHP_ALLOW;//允许模板页里执行PHP代码

//版本切换支持 by vincent start 
$allow_version_switch_array = array(
  'account','account_edit','login','create_account','account','account_password','account_history'
,'account_old','account_edit_old','login_old','create_account_old','account_old','account_history_old','change_password'
/*
,'my_credits','new_orders','orders_travel_companion',
'address_book','eticket_list','orders_ask','my_favorites','my_coupon','my_points','refer_friends','points_actions_history','feedback_approval',
'my_points_help','points_terms','i_sent_bbs','i_reply_bbs','latest_bbs','affiliate_summary','affiliate_details','affiliate_details','affiliate_banners',
'affiliate_sales','affiliate_payment','affiliate_contact','affiliate_faq','account_newsletters','account_notifications','account_history_info','my_favorites'*/
);
/*
if(tep_not_null($_GET['version']) && $_GET['version'] == 'old'){
	setcookie('customer_version_selected' , 'old',time() + 3600*24*30);
	define('USE_OLD_VERSION',1);
}elseif(tep_not_null($_GET['version']) && $_GET['version'] == 'new'){
	setcookie('customer_version_selected' , 'new',time()+ 3600*24*30);
	define('USE_OLD_VERSION',0);
}
if(!defined('USE_OLD_VERSION') ){
	if($_COOKIE['customer_version_selected'] == 'old'){
		define('USE_OLD_VERSION',1);
	}else{
		define('USE_OLD_VERSION',0);
	}
}//版本切换支持 end*/
define('USE_OLD_VERSION',0);
header("Content-type: text/html; charset=".CHARSET);

/*//记录新老版本切换动作
if(tep_not_null($_GET['version'])){
	if($_GET['version'] == 'old'){
		tep_statistics_addpage('login_reg_new_to_old' ,'',true);
	}else if($_GET['version'] == 'new'){
		tep_statistics_addpage('login_reg_old_to_new' ,'',true);
	}
}*/

// 载入用于登录旧站的的类
require(DIR_FS_CLASSES . 'login_old.php');
$old = new login_old;


//持续天数数组{
/*$_SEARCH_DATA['Days']=array();
$_SEARCH_DATA['Days'][0]=array('id'=>'','name'=>html_to_db(TEXT_DURATION_OPTION_1));
$_SEARCH_DATA['Days']['1']=array('id'=>'1','name'=>'1天以内');
$_SEARCH_DATA['Days']['7']=array('id'=>'7','name'=>'2天');
$_SEARCH_DATA['Days']['2']=array('id'=>'2','name'=>'2-3天');
$_SEARCH_DATA['Days']['8']=array('id'=>'8','name'=>'3天');
$_SEARCH_DATA['Days']['9']=array('id'=>'9','name'=>'3-4天');
$_SEARCH_DATA['Days']['10']=array('id'=>'10','name'=>'4天');
$_SEARCH_DATA['Days']['3']=array('id'=>'3','name'=>'4-5天');
$_SEARCH_DATA['Days']['11']=array('id'=>'11','name'=>'5天');
$_SEARCH_DATA['Days']['12']=array('id'=>'12','name'=>'5-6天');
$_SEARCH_DATA['Days']['13']=array('id'=>'13','name'=>'6天');
$_SEARCH_DATA['Days']['4']=array('id'=>'4','name'=>'6-7天');
$_SEARCH_DATA['Days']['14']=array('id'=>'14','name'=>'7天');
$_SEARCH_DATA['Days']['15']=array('id'=>'15','name'=>'7-8天');
$_SEARCH_DATA['Days']['16']=array('id'=>'16','name'=>'8天');
$_SEARCH_DATA['Days']['17']=array('id'=>'17','name'=>'8-9天');
$_SEARCH_DATA['Days']['18']=array('id'=>'18','name'=>'9天');
$_SEARCH_DATA['Days']['5']=array('id'=>'5','name'=>'9-10天');
$_SEARCH_DATA['Days']['6']=array('id'=>'6','name'=>'10天及以上');
*/
//持续天数数组}

//===========================取得当前数据的所有持续时间==========================================={
		$_SEARCH_DATA['Days'] = array();
		$daysSQL = "SELECT DISTINCT IF(p.products_durations_type='0' AND p.products_durations>0, p.products_durations, round((p.products_durations/24),2) ) as days FROM " . TABLE_PRODUCTS . " p ORDER BY days ASC ";
		$daysQuery = tep_db_query($daysSQL);
		$_SEARCH_DATA['Days'][0]=array('id'=>'','name'=>db_to_html('不限'));
		while($daysRows = tep_db_fetch_array($daysQuery)){
			if($daysRows['days']<=1){
				$_SEARCH_DATA['Days']['1']=array('id'=>'1','name'=>db_to_html('1天以内'));
				continue;
			}
			if($daysRows['days']>=2 && $daysRows['days']<3 ){
				$_SEARCH_DATA['Days']['2']=array('id'=>'2','name'=>db_to_html('2天'));
				//$_SEARCH_DATA['Days']['3']=array('id'=>'3','name'=>db_to_html('2-3天'));
				//$_SEARCH_DATA['Days']['4']=array('id'=>'4','name'=>db_to_html('3天'));
				
				continue;
			}
			
			if($daysRows['days']>=3 && $daysRows['days']<4 ){
				$_SEARCH_DATA['Days']['3']=array('id'=>'3','name'=>db_to_html('3天'));
				//$_SEARCH_DATA['Days']['6']=array('id'=>'6','name'=>db_to_html('4天'));
				
				continue;
			}

			if($daysRows['days']>=4 && $daysRows['days']<5 ){
				$_SEARCH_DATA['Days']['4']=array('id'=>'4','name'=>db_to_html('4天'));
				//$_SEARCH_DATA['Days']['8']=array('id'=>'8','name'=>db_to_html('5天'));
				
				continue;
			}

			if($daysRows['days']>=5 && $daysRows['days']<6 ){
				$_SEARCH_DATA['Days']['5']=array('id'=>'5','name'=>db_to_html('5天'));
				//$_SEARCH_DATA['Days']['10']=array('id'=>'10','name'=>db_to_html('6天'));
				continue;
			}
			if($daysRows['days']>=6 && $daysRows['days']<7 ){
				$_SEARCH_DATA['Days']['6']=array('id'=>'6','name'=>db_to_html('6天'));
				//$_SEARCH_DATA['Days']['12']=array('id'=>'12','name'=>db_to_html('7天'));
				continue;
			}

			if($daysRows['days']>=7 && $daysRows['days']<8 ){
				$_SEARCH_DATA['Days']['7']=array('id'=>'7','name'=>db_to_html('7天'));
				//$_SEARCH_DATA['Days']['14']=array('id'=>'14','name'=>db_to_html('9天'));
				continue;
			}
			if($daysRows['days'] >= 8 && $daysRows['days'] < 9) {
				$_SEARCH_DATA['Days']['8'] = array('id' => '8', 'name' => db_to_html('8天'));
				continue;
			}
			if($daysRows['days'] >= 9 && $daysRows['days'] < 10) {
				$_SEARCH_DATA['Days']['9'] = array('id' => '9', 'name' => db_to_html('9天'));
				continue;
			}
			if($daysRows['days'] >= 10 && $daysRows['days'] < 11) {
				$_SEARCH_DATA['Days']['10'] = array('id' => '10', 'name' => db_to_html('10天'));
				continue;
			}
			if($daysRows['days'] >= 11 && $daysRows['days'] < 12) {
				$_SEARCH_DATA['Days']['11'] = array('id' => '11', 'name' => db_to_html('11天'));
				continue;
			}
			
		}
		if (array_key_exists('2',$_SEARCH_DATA['Days']) || array_key_exists('3',$_SEARCH_DATA['Days'])) {
			$_SEARCH_DATA['Days']['12'] = array('id' => '12', 'name' => db_to_html('2-3天'));
		}
		
		if (array_key_exists('4',$_SEARCH_DATA['Days']) || array_key_exists('5',$_SEARCH_DATA['Days'])) {
			$_SEARCH_DATA['Days']['13'] = array('id' => '13', 'name' => db_to_html('4-5天'));
		}
		
		if (array_key_exists('6',$_SEARCH_DATA['Days']) || array_key_exists('7',$_SEARCH_DATA['Days'])) {
			$_SEARCH_DATA['Days']['14'] = array('id' => '14', 'name' => db_to_html('6-7天'));
		}
		
		if (array_key_exists('8',$_SEARCH_DATA['Days']) || array_key_exists('9',$_SEARCH_DATA['Days'])) {
			$_SEARCH_DATA['Days']['15'] = array('id' => '15', 'name' => db_to_html('8-9天'));
		}
		
		if (array_key_exists('10',$_SEARCH_DATA['Days']) || array_key_exists('11',$_SEARCH_DATA['Days'])) {
			$_SEARCH_DATA['Days']['16'] = array('id' => '16', 'name' => db_to_html('10天及以上'));
		}
?>