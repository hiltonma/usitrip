<?php
/*
所有php格式的JS都在这里处理后输出
*/
error_reporting(E_ALL & ~E_NOTICE);
define('_TEP_ROOT_DIR_',dirname(__FILE__).'/');

$_GET['files'] = str_replace(array('/','.'),'',$_GET['files']);
if(isset($_GET['files']) && $_GET['files']!=""){
	$_files = preg_replace('/\.js$/','',$_GET['files']);
	
	$JavascriptPhpFile = _TEP_ROOT_DIR_.'includes/javascript/'.$_files.'.js.php';
	if(!file_exists($JavascriptPhpFile) || !is_file($JavascriptPhpFile)){
		//echo $JavascriptPhpFile." no find.";
		echo "file no exists.";
		exit;
	}
	if(isset($_GET['parameters']) && $_GET['parameters']!=""){
		$parameters = $_GET['parameters'];
		$parameters = explode('&',base64_decode($parameters));
		foreach($parameters as $pk=>$pv){
			$get_var_array = explode('=',$pv);
			if(sizeof($get_var_array)==2){
				$$get_var_array[0] = $_GET[$get_var_array[0]] = $get_var_array[1];
			}
		}
		if(!isset($keyformatstring) || $keyformatstring!='md5md5md5'){
			die('decode error!');
		}
	}
}

//$expires_tags = date('Ymd').'|'.filemtime($_SERVER['SCRIPT_FILENAME']).'|'.filemtime($JavascriptPhpFile); //缓存过期标签//如果当前文件有更新则不缓存
//以下为缓存机制{
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: max-age=600");
$expires_tags = md5(date('Ymd').'|'.filemtime($_SERVER['SCRIPT_FILENAME']).'|'.filemtime($JavascriptPhpFile).'|'.implode(',',$_GET)); //缓存过期标签//如果当前文件有更新则不缓存
//}

if ($_SERVER["HTTP_IF_NONE_MATCH"] == $expires_tags){
  header('Etag:'.$expires_tags,true,304);
  header('HTTP/1.1 304 Not Modified');
  exit();
}else {
  header('Etag:'.$expires_tags);
}

//require('includes/application_top.php');
$base_php_self = "javascript.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
define('DIR_FS_CATALOG', _TEP_ROOT_DIR_);
define('DIR_FS_INCLUDES', _TEP_ROOT_DIR_.'includes/');
define('DIR_FS_FUNCTIONS', DIR_FS_INCLUDES . 'functions/');
define('DIR_FS_CLASSES', DIR_FS_INCLUDES . 'classes/');
define('DIR_FS_LANGUAGES', DIR_FS_INCLUDES . 'languages/');
define('SEO_EXTENSION','.html');
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);
// Set the local configuration parameters - mainly for developers
if (file_exists(DIR_FS_INCLUDES . 'configure_local.php')){
	require(DIR_FS_INCLUDES . 'configure_local.php');
}elseif(file_exists(DIR_FS_INCLUDES . 'local/configure.php')){
	require(DIR_FS_INCLUDES . 'local/configure.php');
}else{
// include server parameters
	require(DIR_FS_INCLUDES . 'configure.php');
}
//MCACHE Module
 require_once DIR_FS_CATALOG."includes/classes/mcache.php";
// include the list of project filenames
require(DIR_FS_INCLUDES . 'filenames.php');
// include the list of project database tables
require(DIR_FS_INCLUDES . 'database_tables.php');
require(DIR_FS_FUNCTIONS . 'database.php');

tep_db_connect() or message('Unable to connect to database server!');
$configuration_query = tep_db_query('select configuration_key as cfgKey,configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
while($configuration = tep_db_fetch_array($configuration_query)){
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

require(DIR_FS_FUNCTIONS . 'general.php');
require(DIR_FS_FUNCTIONS . 'html_output.php');
require(DIR_FS_FUNCTIONS . 'zhh_function.php');

require(DIR_FS_CLASSES . 'currencies.php');
$currencies = new currencies();
//=====session=============================
$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
// set the cookie domain
$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

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
}elseif (function_exists('ini_set')) {
	ini_set('session.cookie_lifetime', '0');
	ini_set('session.cookie_path', $cookie_path);
	ini_set('session.cookie_domain', $cookie_domain);
}

// set the session ID if it exists
if (isset($_POST[tep_session_name()])) {
	tep_session_id($_POST[tep_session_name()]);
} elseif ( ($request_type == 'SSL') && isset($_GET[tep_session_name()]) ) {
	tep_session_id($_GET[tep_session_name()]);
}

//$_GET['language']='sc';
//$_GET['language']='tw';

if (isset($_GET['language'])) {
	include(DIR_FS_CLASSES . 'language.php');
	$lng = new language();
	$lng->set_language($_GET['language']);
	$language = $lng->language['directory'];
	$languages_id = $lng->language['id'];
}
if(!tep_not_null($language)){
	$language = 'schinese';
	$languages_id = '1';
}

if($language!="schinese" && $language!="tchinese"){
	die("No \$language");
}

require(DIR_FS_LANGUAGES . $language . '.php');

header("Content-type: application/x-javascript; charset=".CHARSET);
//echo "alert('".$language.":".CHARSET."')";
//exit;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require(DIR_FS_FUNCTIONS . 'webmakers_added_functions.php');

if(tep_not_null($JavascriptPhpFile) && file_exists($JavascriptPhpFile)){
	require_once($JavascriptPhpFile);
}else{
	echo $JavascriptPhpFile.' no find!';
}
?>