<?php
// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// check if register_globals is enabled.
// since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  if (function_exists('ini_get')) {
    ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
  }

/////-----
session_cache_expire(30);
session_start();
session_name("domestic_orders");
$inactive = 1800;
if(isset($_SESSION['start']) ) {
	$session_life = time() - $_SESSION['start'];
	if($session_life > $inactive){
		require_once("domestic_logoff.php");
	}
}
$_SESSION['start'] = time();
/////-----------------
define("BACK_PATH", "../");
// include server parameters
  require(BACK_PATH.'includes/configure.php');
  define("DIR_WS_DOMESTIC", "domestic/");

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

// include the list of project filenames
  require(BACK_PATH.DIR_WS_INCLUDES . 'filenames.php');
//MCACHE Module
 require_once DIR_FS_CATALOG."includes/classes/mcache.php";
// include the list of project database tables
  require(BACK_PATH.DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
//define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(BACK_PATH.DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

 

// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }


// define general functions used application-wide
  require(BACK_PATH.DIR_WS_FUNCTIONS . 'general.php');
  //require(BACK_PATH.DIR_WS_FUNCTIONS . 'general_addon.php');
  require(BACK_PATH.DIR_WS_FUNCTIONS . 'html_output.php');

   //initialize the logger class
  require(BACK_PATH.DIR_WS_CLASSES . 'logger.php');

// include encoding.php
  require(BACK_PATH.DIR_WS_CLASSES . 'encoding.php');

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// set the session name and save path
  session_name('domestic_orders');

  // set the session ID if it exists
   if (isset($HTTP_POST_VARS[session_name()])) {
     session_id($HTTP_POST_VARS[session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[session_name()]) ) {
     session_id($HTTP_GET_VARS[session_name()]);
   }
   require(BACK_PATH.DIR_WS_FUNCTIONS . 'sessions.php');
// include currencies class and create an instance
  require(BACK_PATH.DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
  require(BACK_PATH.DIR_WS_CLASSES . 'mime.php');
  require(BACK_PATH.DIR_WS_CLASSES . 'email.php');


// BOF: WebMakers.com Added: Functions Library
    include(BACK_PATH.DIR_WS_FUNCTIONS . 'webmakers_added_functions.php');
// EOF: WebMakers.com Added: Functions Library

// include the password crypto functions
  require(BACK_PATH.DIR_WS_FUNCTIONS . 'password_funcs.php');

// split-page-results
  require(BACK_PATH.DIR_WS_CLASSES . 'split_page_results.php');
  require(BACK_PATH.DIR_WS_FUNCTIONS . 'zhh_function.php');

// initialize the message stack for output messages
  //require(BACK_PATH.DIR_WS_CLASSES . 'message_stack.php');
//  $messageStack = new messageStack;

?>
