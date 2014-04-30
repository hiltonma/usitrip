<?php
define('_TEP_ROOT_DIR_',dirname(__FILE__).'/../');
define('API_ROOT',dirname(__FILE__).'/');
define('DIR_FS_CATALOG', _TEP_ROOT_DIR_);
define('DIR_FS_INCLUDES', _TEP_ROOT_DIR_.'includes/');
define('DIR_FS_INCLUDES', DIR_FS_INCLUDES . 'functions/');
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
//BEGIN  -vincent
//MCache 支援mcache模块 请不要删除该行
require_once DIR_FS_CATALOG."includes/classes/mcache.php";
// END
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
// include the password crypto functions
require(DIR_FS_FUNCTIONS . 'password_funcs.php');

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
if (isset($HTTP_POST_VARS[tep_session_name()])) {
	tep_session_id($HTTP_POST_VARS[tep_session_name()]);
} elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[tep_session_name()]) ) {
	tep_session_id($HTTP_GET_VARS[tep_session_name()]);
}

// start the session
$session_started = false;
if($tep_session_start_Off!=true){
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
}


//$HTTP_GET_VARS['language']='sc';

if (isset($HTTP_GET_VARS['language'])) {
	include(DIR_FS_CLASSES . 'language.php');
	$lng = new language();
	$lng->set_language($HTTP_GET_VARS['language']);
	$language = $lng->language['directory'];
	$languages_id = $lng->language['id'];
}
if(!tep_not_null($language)){
	$language = 'schinese';
	$languages_id = '1';
}
require(DIR_FS_LANGUAGES . $language . '.php');

//=====================================================
function writeover($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$dirname = dirname($filename);
	@chmod($dirname,0777);
	@touch($filename);
	$handle = fopen($filename,$method);
	$iflock && flock($handle,LOCK_EX);
	fwrite($handle,$data);
	$method=="rb+" && ftruncate($handle,strlen($data));
	fclose($handle);
	$chmod && @chmod($filename,0777);
}
function readover($filename,$method='rb',$readsize='D'){
	$filedata="";
	if(file_exists($filename)){
		$filesize = @filesize($filename);
		$readsize!='D' && $filesize = min($filesize,$readsize);
		$filedata = '';
		if ($handle = @fopen($filename,$method)) {
			flock($handle,LOCK_SH);
			$filedata = @fread($handle,$filesize);
			fclose($handle);
		}
	}
	return $filedata;
}
function DlFilemtime($file) {
	return file_exists($file) ? intval(filemtime($file)) : 0;
}
function message($msg){
	$dom = createDom();
	$message = $dom->createElement('message');
	$dom->appendChild($message);
	$msg = iconv('GB2312','UTF-8//IGNORE',$msg);
	$msg = $dom->createCDATASection($msg);
	$message->appendChild($msg);
	outDom($dom);
}
function createDom(){
	$dom = new DOMDocument('1.0', CHARSET);
	return $dom;
}
function outDom($dom){
	header('Content-Type: text/xml; charset='.CHARSET);
	echo $dom->saveXML();
	exit;
}
function cel($nodeName,$parentNode=NULL){
	global $dom,$domroot;
	$node = $dom->createElement($nodeName);
	if($parentNode===NULL){
		$domroot->appendChild($node);
	}else{
		$parentNode->appendChild($node);
	}
	return $node;
}

function cval($value, $createCDATA = true){
	global $dom;
	$value = db_to_html($value);
	$value = iconv(CHARSET,'UTF-8//IGNORE',$value);
	$value = preg_replace('/[[:space:]]+/',' ',$value);
	if($createCDATA!==true){
		return $value;
	}
	return $dom->createCDATASection($value);
}
function formathtml($html){
    $html = preg_replace('/>(\s+)/','>',$html);
    $html = preg_replace('/(\s+)</','<',$html);
    $html = preg_replace('/(\s+)/',' ',$html);
    $html = str_replace("\r\n",'',$html);
    $html = str_replace("\t",'',$html);
    return $html;
}
?>