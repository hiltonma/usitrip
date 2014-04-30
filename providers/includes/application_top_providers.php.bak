<?php
// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

// check if register_globals is enabled.
// since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  if (function_exists('ini_get')) {
    ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
  }

/////-----
session_cache_expire(60);
session_start();
session_name("osCProvidersID");
$inactive = 3600;
if(isset($_SESSION['start']) ) {
	$session_life = time() - $_SESSION['start'];
	if($session_life > $inactive){
		require_once("providers_logoff.php");
	}
}
$_SESSION['start'] = time();
/////-----------------
define("BACK_PATH", "../");
// include server parameters
if (file_exists(BACK_PATH.'includes/configure_local.php')) {
    include(BACK_PATH.'includes/configure_local.php');
} elseif (file_exists(BACK_PATH.'includes/local/configure.php')) {
    include(BACK_PATH.'includes/local/configure.php');
} else {
    require(BACK_PATH.'includes/configure.php');
}
//require(BACK_PATH.'includes/configure.php');
  define("DIR_WS_PROVIDERS", "providers/");

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

//MCACHE Module
 require_once DIR_FS_CATALOG."includes/classes/mcache.php";
// include the list of project filenames
  require(DIR_FS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_FS_INCLUDES . 'database_tables.php');

// customization for the design layout
//define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_FS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');
	
  $language="english";
  
// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }
	define(SEO_EXTENSION,'.html');
  require(DIR_FS_PROVIDERS_LANGUAGES . $language.'.php');

// define general functions used application-wide
  require(DIR_FS_FUNCTIONS . 'general.php');
  require(DIR_FS_FUNCTIONS . 'html_output.php');

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// set the session name and save path
  session_name('osCProvidersID');
  
  // set the session ID if it exists
   if (isset($HTTP_POST_VARS[session_name()])) {
     session_id($HTTP_POST_VARS[session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[session_name()]) ) {
     session_id($HTTP_GET_VARS[session_name()]);
   }
   require(DIR_FS_FUNCTIONS . 'sessions.php');
// include currencies class and create an instance
  require(DIR_FS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
  require(DIR_FS_CLASSES . 'mime.php');
  require(DIR_FS_CLASSES . 'email.php');
  require(DIR_FS_CLASSES . 'order.php');


// BOF: WebMakers.com Added: Functions Library
    include(DIR_FS_FUNCTIONS . 'webmakers_added_functions.php');
// EOF: WebMakers.com Added: Functions Library

// include the password crypto functions
  require(DIR_FS_FUNCTIONS . 'password_funcs.php');

// split-page-results
  require(DIR_FS_CLASSES . 'split_page_results.php');
	require(DIR_FS_FUNCTIONS . 'zhh_function.php');

// initialize the message stack for output messages
  //require(DIR_FS_CLASSES . 'message_stack.php');
//  $messageStack = new messageStack;
?>