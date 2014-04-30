<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
//echo 'test the configure.php';
//print_r($_SERVER);
//exit;

define('IS_LIVE_SITES', false); // if is prod site set true else set false
define('IS_QA_SITES', false);
define('IS_DEV_SITES', true);

if($_SERVER['HTTP_HOST']=='192.168.1.3'){
	die('Can not use 192.168.1.3');
}
if($_SERVER['HTTP_HOST']=='localhost' || preg_match('/:1157$/',$_SERVER['HTTP_HOST'])){	//for dev on local
	require(dirname(__FILE__).'/configure_local.php');
}
if($_SERVER['HTTP_HOST']=='cn.usitrip.com'){
  define('HTTP_SERVER', 'http://cn.usitrip.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'http://cn.usitrip.com'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', true); // secure webserver for checkout procedure?  
  define('HTTP_COOKIE_DOMAIN', 'cn.usitrip.com');
  define('HTTPS_COOKIE_DOMAIN', 'cn.usitrip.com');
}else{
  define('HTTP_SERVER', 'http://'.$_SERVER['HTTP_HOST']); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'http://'.$_SERVER['HTTP_HOST']); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', false); // secure webserver for checkout procedure?  
  define('HTTP_COOKIE_DOMAIN', $_SERVER['HTTP_HOST']);
  define('HTTPS_COOKIE_DOMAIN', $_SERVER['HTTP_HOST']);

} 
  define('IMAGES_HOST',HTTP_SERVER);
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
 define('DIR_WS_VIDEO', 'video/');
//Added for BTS1.0
  define('DIR_WS_TEMPLATES', 'templates/');
  define('DIR_WS_CONTENT', DIR_WS_TEMPLATES . 'content/');
  define('DIR_WS_JAVASCRIPT', DIR_WS_INCLUDES . 'javascript/');
//End BTS1.0
  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/home/howard/public_html/usitrip/');
  define('DIR_FS_INCLUDES', DIR_FS_CATALOG . 'includes/');
  
  define('DIR_FS_FUNCTIONS', DIR_FS_INCLUDES . 'functions/');
  define('DIR_FS_CLASSES', DIR_FS_INCLUDES . 'classes/');
  define('DIR_FS_MODULES', DIR_FS_INCLUDES . 'modules/');
  define('DIR_FS_LANGUAGES', DIR_FS_INCLUDES . 'languages/');

  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
  define('DIR_WS_TEMPLATE_IMAGES','image/');
  define('DIR_WS_ICONS', DIR_WS_TEMPLATE_IMAGES . 'icons/');

  define('CC_ENC_KEY_SECURE_KEY','D6M9Qa+J%P~|GcE$[cz!pk@W)*%ZusN]6'); //Credit Card encryption key. please don't modify it without permission

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'dev');
  define('DB_SERVER_PASSWORD', 'dev007');
  define('DB_DATABASE', 'usitrip_com_howard_dev');
  define('USE_PCONNECT', 'false'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'

// howard added 
  define('DIR_BLOG_FS_IMAGES',DIR_FS_CATALOG.'images/blog/');
  define('DIR_FACE_FS_IMAGES',DIR_FS_CATALOG.'images/face/');
  define('DIR_PHOTOS_FS_IMAGES',DIR_FS_CATALOG.'images/photos/');
?>
