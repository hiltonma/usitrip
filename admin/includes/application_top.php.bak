<?php
/*
  $Id: application_top.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// set timezone
//ini_set('date.timezone','Asia/Shanghai');
ini_set('date.timezone','UTC-07:00');
// Start the clock for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());
  
define('_TEP_ROOT_DIR_',dirname(__FILE__).'/../../');
// set the level of error reporting
  ini_set('display_errors', '1'); 
  error_reporting(E_ALL & ~E_NOTICE);

// Check if register_globals is enabled.
// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  if (function_exists('ini_get')) {
    ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
  }

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/configure_local.php')){
	  include('includes/configure_local.php');
  }elseif(file_exists('includes/local/configure.php')){
	  include('includes/local/configure.php');
  }else{
// include server parameters
	include('includes/configure.php');
  }

if((!defined('IS_LIVE_SITES') || IS_LIVE_SITES!=true) && (!defined('IS_QA_SITES') || IS_QA_SITES!=true) && (!defined('IS_DEV_SITES') || IS_DEV_SITES!=true)){
	die("Please define your local configure. define IS_DEV_SITES, IS_QA_SITES and IS_LIVE_SITES. please see config/dev/includes/configure.php and config/dev/admin/includes/configure.php");
}

// define the project version
include('includes/version.php');


// set php_self in the local scope
  $PHP_SELF = (isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME']);
// 限制一些全局变量(禁止改变登录id和所属组别)
if($PHP_SELF!='login.php' && (isset($_GET['login_id']) || isset($_GET['login_groups_id']) || isset($_POST['login_id']) || isset($_POST['login_groups_id']))){
	echo ('这些变量不可随便改变！login_id, login_groups_id');
	exit;
}
// Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');
 //添加PageCache支持
require_once DIR_FS_CATALOG."includes/classes/mcache.php";
 $mcache = MCache::instance();

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

//     define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
// define('MENU_DHTML', false);

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'oanda');
  define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// define our general functions used application-wide
  
  require(DIR_WS_FUNCTIONS . 'html_output.php');
  require(DIR_WS_FUNCTIONS . 'zhh_function.php');
//Admin begin
 require(DIR_WS_FUNCTIONS . 'password_funcs.php');
//Admin end

// initialize the logger class
  require(DIR_WS_CLASSES . 'logger.php');

// include encoding.php
  require(DIR_WS_CLASSES . 'encoding.php');

// include select_menus.php
  require(DIR_WS_CLASSES . 'select_menus.php');


  $CharEncoding=new Encoding();
  $CharEncoding->SetGetEncoding("BIG5")||die("The encode name is error!");
  $CharEncoding->SetToEncoding("GBK")||die("The encode name is error!");

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCAdminID');
    define('PHP_SESSION_PATH', '/');
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
  }

// lets start our session
  tep_session_start();

// set the language
$language = $HTTP_GET_VARS['language'] = 'sc';
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($PHP_SELF);
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// entry/item info classes
  require(DIR_WS_CLASSES . 'object_info.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// file uploading class
  require(DIR_WS_CLASSES . 'upload.php');

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

//定义目的地指南的目录路径
  if (isset($HTTP_GET_VARS['DgPath'])) {
    $DgPath = $HTTP_GET_VARS['DgPath'];
  } else {
    $DgPath = '';
  }

  if (tep_not_null($DgPath)) {
    $DgPath_array = tep_parse_gd_category_path($DgPath);
    $DgPath = implode('_', $DgPath_array);
    $current_dg_category_id = $DgPath_array[(sizeof($DgPath_array)-1)];
  } else {
    $current_dg_category_id = 0;
  }

// default open navigation box
  if (!tep_session_is_registered('selected_box')) {
    tep_session_register('selected_box');
    $selected_box = 'configuration';
  }

  if (isset($HTTP_GET_VARS['selected_box'])) {
    $selected_box = $HTTP_GET_VARS['selected_box'];
  }

  //Cache control system 
//  include(DIR_WS_INCLUDES . 'cache_configure.php');
  $cache_blocks = array(array('title' => TEXT_CACHE_COOLMENU, 'code' => 'coolmenu', 'file' => 'coolmenu-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories1', 'file' => 'categories_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES4, 'code' => 'categories4', 'file' => 'categories_box4-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true));

// check if a default currency is set
  if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }

// check if a default language is set
  if (!defined('DEFAULT_LANGUAGE')) {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }

  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }

// navigation history
	if(!(int)$login_id && $current_page!="logoff.php" && !preg_match('/ajax/',$current_page) && $current_page != FILENAME_LOGIN && $current_page != FILENAME_PASSWORD_FORGOTTEN && $current_page != 'createreg_illregulartour.php' ){
		$session_old_full_url = HTTP_SERVER.$_SERVER["REQUEST_URI"];
		tep_session_register('session_old_full_url');
	}


//Admin begin
  if ($current_page != FILENAME_LOGIN && $current_page != FILENAME_PASSWORD_FORGOTTEN &&  $current_page != 'ajax_edit_room_info.php'  && $current_page != 'createreg_illregulartour.php' && $current_page != 'orders_settlement_ajax.php' && $smsajax != true) {
    tep_admin_check_login();
  }
  
//权限设置文件
require(DIR_WS_INCLUDES . 'reports_permissions.php');
//Admin end
// Include OSC-AFFILIATE
  require('includes/affiliate_application_top.php');
// include giftvoucher
  require(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php');

// WebMakers.com Added: Includes Functions for Attribute Sorter and Copier
require(DIR_WS_FUNCTIONS . 'webmakers_added_functions.php');
require(DIR_WS_FUNCTIONS . 'from_email_auto_login.php');


//include('includes/application_top_support.php');
include('includes/application_top_newsdesk.php');
include('includes/application_top_faqdesk.php');

// Points/Rewards Module V2.1rc2a(调用前台的积分函数)
require(DIR_FS_DOCUMENT_ROOT . 'includes/functions/redemptions.php');

//james added for article

// include the articles functions
  require(DIR_WS_FUNCTIONS . 'articles.php');

// Article Manager
  if (isset($HTTP_GET_VARS['tPath'])) {
    $tPath = $HTTP_GET_VARS['tPath'];
  } else {
    $tPath = '';
  }

  if (tep_not_null($tPath)) {
    $tPath_array = tep_parse_topic_path($tPath);
    $tPath = implode('_', $tPath_array);
    $current_topic_id = $tPath_array[(sizeof($tPath_array)-1)];
  } else {
    $current_topic_id = 0;
  }

  

//cpunc短信类
include(DIR_WS_CLASSES . 'cpunc_sms.php');
$cpunc = new cpunc_SMS;

//Smarty2.0 模板类，用于新系统。（从客服培训系统开使用）
	ini_set("memory_limit","64M");
	require_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/Smarty.class.php');
	$smarty = new Smarty;
	$smarty->left_delimiter = '{:';
	$smarty->right_delimiter = ':}';
	$smarty->template_dir = DIR_FS_ADMIN.'html_tpl/';
	$smarty->compile_dir = DIR_FS_ADMIN.'html_tpl/templates_c/';
	
	$fileperms = substr(sprintf('%o', fileperms($smarty->compile_dir)), -4);
	/*if($fileperms!="0777"){
		echo $smarty->compile_dir." Write Failure! Please setup 0777 for it's fileperms!";
		exit;
	}*/
	
	$smarty->compile_check = false;	//每次访问时检查模板是否更新
	$smarty->debugging = false;
	$smarty->caching = false;   //是否启用缓存
	
	//$CssArray[] = 'includes/stylesheet.css';	//全局css文件路径
	//$CssArray[] = 'includes/javascript/spiffyCal/spiffyCal_v2_1.css';	//可考虑去掉放到实际文件中
	
	$JavaScriptSrc[] = 'includes/javascript/general.js'; //全局js文件路径
	$JavaScriptSrc[] = 'includes/big5_gb-min.js';
	$JavaScriptSrc[] = 'includes/jquery-1.3.2/jquery-1.3.2.min.js';
	$JavaScriptSrc[] = 'includes/javascript/add_global.js';
	
	//$JavaScriptSrc[] = 'includes/javascript/spiffyCal/spiffyCal_v2_1.js';	//可考虑去掉放到实际文件中
	$title = TITLE;
	$DOCTYPE = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	
	//面包屑导航 start
	require(DIR_FS_DOCUMENT_ROOT . 'includes/classes/breadcrumb.php');
	$breadcrumb = new breadcrumb;
	//$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
	
	if(isset($dir_id)) {
		$_SESSION['last_visited_dir_id'] = $dir_id;
	}
	if(!empty($_SESSION['last_visited_dir_id'])){
		$last_visited_dir_id = $_SESSION['last_visited_dir_id'];
	}
	
	$open_dir_id = "";
	if((int)$last_visited_dir_id){
		$open_dir_id = $last_visited_dir_id;
	}
	
	$BreadArray = tep_get_top_to_now_class($last_visited_dir_id);
	$Bread = "";
	//面包屑导航
	if(preg_match('/zhh_system/',basename($_SERVER['PHP_SELF']))){
		for($i=max(0,(count($BreadArray)-1)); $i>=0; $i--){
			$breadcrumb->add($BreadArray[$i]['text'], tep_href_link('zhh_system_words_list.php','dir_id='.$BreadArray[$i]['id']));
		}
	}else{
		$breadcrumb->add('首页', tep_href_link('index.php'));
	}
	
	$login_name = str_replace(' ','',tep_get_admin_customer_name($login_id));
//Smarty2.0 模板类 end

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

if(!isset($_GET['download'])){
	header("Content-type: text/html; charset=".CHARSET);
}
?>