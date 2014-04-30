<?php
/*
 * osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
 * Copyright (c) 2003 osCommerce Released under the GNU General Public License
 */

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
//echo 'test the configure.php';
//print_r($_SERVER);
//exit;
define('IS_LIVE_SITES', false); // if is prod site set true else set false
define('IS_QA_SITES', true);
define('IS_DEV_SITES', false);
define('ENABLE_SSL', true);	//是否使用SSL安全链接(因为imagecss.usitrip.com.cn这个域名无SSL证书会导致图片等无法正常显示，所以关闭)

if ($_SERVER['HTTP_HOST'] == 'cn.usitrip.com') {
	define('HTTP_SERVER', 'http://cn.usitrip.com'); // eg, http://localhost - should not be empty for productive servers
	define('HTTPS_SERVER', 'https://cn.usitrip.com'); // eg, https://localhost - should not be empty for productive servers
	define('HTTP_COOKIE_DOMAIN', '.usitrip.com');
	define('HTTPS_COOKIE_DOMAIN', '.usitrip.com');
} else {
	define('HTTP_SERVER', 'http://test.usitrip.com'); // eg, http://localhost - should not be empty for productive servers
	define('HTTPS_SERVER', 'https://test.usitrip.com'); // eg, https://localhost - should not be empty for productive servers
	define('HTTP_COOKIE_DOMAIN', '.usitrip.com');
	define('HTTPS_COOKIE_DOMAIN', '.usitrip.com');
}

//图片服务器主机如：http://imagecss.usitrip.com.cn，不要与主站域名相同，否则会产生cookie传送的流量与时间，浪费时间
define('IMAGES_HOST', ($_SERVER['SERVER_PORT'] == '443' ? HTTPS_SERVER : HTTP_SERVER));

define('HTTP_COOKIE_PATH', '/');
define('HTTPS_COOKIE_PATH', '/');
define('DIR_WS_HTTP_CATALOG', '/');
define('DIR_WS_HTTPS_CATALOG', '/');
//define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_IMAGES', IMAGES_HOST . '/images/');

define('DIR_WS_INCLUDES', IMAGES_HOST . '/includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
define('DIR_WS_VIDEO', 'video/');
//Added for BTS1.0
//模板路径
define('DIR_WS_TEMPLATES', IMAGES_HOST.'/templates/');
define('DIR_WS_CONTENT', DIR_WS_TEMPLATES . 'content/');
define('DIR_WS_JAVASCRIPT', DIR_WS_INCLUDES . 'javascript/');
//End BTS1.0
define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
define('DIR_FS_CATALOG', '/var/www/html/test.usitrip/');
define('DIR_FS_INCLUDES', DIR_FS_CATALOG . 'includes/');
define('DIR_FS_PROVIDERS_INCLUDES', DIR_FS_CATALOG . 'providers/includes/');
define('DIR_FS_TEMPLATES', DIR_FS_CATALOG.'templates/');
define('DIR_FS_CONTENT', DIR_FS_TEMPLATES . 'content/');

define('DIR_FS_FUNCTIONS', DIR_FS_INCLUDES . 'functions/');
define('DIR_FS_CLASSES', DIR_FS_INCLUDES . 'classes/');
define('DIR_FS_MODULES', DIR_FS_INCLUDES . 'modules/');
define('DIR_FS_LANGUAGES', DIR_FS_INCLUDES . 'languages/');
define('DIR_FS_PROVIDERS_LANGUAGES', DIR_FS_CATALOG . 'providers/includes/languages/');

define('DIR_FS_BOXES', DIR_FS_INCLUDES . 'boxes/');
define('DIR_FS_JAVASCRIPT', DIR_FS_INCLUDES . 'javascript/');
define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
define('DIR_WS_TEMPLATE_IMAGES', IMAGES_HOST.'/image/');
define('DIR_WS_ICONS', DIR_WS_TEMPLATE_IMAGES . 'icons/');
define('CC_ENC_KEY_SECURE_KEY', 'D6M9Qa+J%P~|GcE$[cz!pk@W)*%ZusN]6'); //Credit Card encryption key. please don't modify it without permission
// define our database connection
define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'qatest007201305');
define('DB_SERVER_PASSWORD', 'qatest007201305');
define('DB_DATABASE', 'usitrip_com_test');
define('USE_PCONNECT', 'false'); // use persisstent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'


// howard added 
define('DIR_BLOG_FS_IMAGES', DIR_FS_CATALOG . 'images/blog/');
define('DIR_FACE_FS_IMAGES', DIR_FS_CATALOG . 'images/face/');
define('DIR_PHOTOS_FS_IMAGES', DIR_FS_CATALOG . 'images/photos/');

?>
