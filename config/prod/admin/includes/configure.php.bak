<?php
/*
 * osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
 * Copyright (c) 2003 osCommerce Released under the GNU General Public License
 */

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)


define('IS_LIVE_SITES', true); // if is prod site set true else set false
define('IS_QA_SITES', false);
define('IS_DEV_SITES', false);

define('SCHINESE_HTTP_SERVER', 'http://www.usitrip.com'); //CN site
define('TW_CHINESE_HTTP_SERVER', 'http://tw.usitrip.com'); //TW site

define('HTTP_SERVER', 'http://www.usitrip.com');
define('HTTPS_SERVER', 'http://www.usitrip.com');
define('HTTP_COOKIE_DOMAIN', '.usitrip.com');
define('HTTPS_COOKIE_DOMAIN', '.usitrip.com');
define('HTTP_CATALOG_SERVER', 'http://www.usitrip.com');
define('HTTPS_CATALOG_SERVER', 'http://www.usitrip.com');
define('ENABLE_SSL_CATALOG', 'false');

if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != "www.usitrip.com") {
	$url = SCHINESE_HTTP_SERVER . $_SERVER['REQUEST_URI'];
	header('Location: ' . $url);
	exit();
}

define('ENABLE_SSL', ((isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER["REMOTE_ADDR"], array('113.106.94.150','223.255.242.69'))) ? false : true));//是否使用SSL安全链接(深圳的ssl没有搞好暂时不用https)
//图片服务器主机如：http://imagecss.usitrip.com.cn，不要与主站域名相同，否则会产生cookie传送的流量与时间，浪费时间
define('IMAGES_HOST', ((isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? HTTPS_SERVER : HTTP_SERVER));

define('DIR_FS_DOCUMENT_ROOT', '/var/www/html/usitrip/'); // where the pages are located on the server
define('DIR_WS_ADMIN', '/admin/'); // absolute path required
define('DIR_FS_ADMIN', '/var/www/html/usitrip/admin/'); // absolute path required
define('DIR_WS_CATALOG', '/'); // absolute path required
define('DIR_FS_CATALOG', '/var/www/html/usitrip/'); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
define('DIR_FS_CATALOG_VIDEO', DIR_FS_CATALOG . 'video/');
define('DIR_FS_CLASSES', DIR_FS_ADMIN . 'includes/classes/');

// Added for Templating
define('DIR_FS_CATALOG_MAINPAGE_MODULES', DIR_FS_CATALOG_MODULES . 'mainpage_modules/');
define('DIR_WS_TEMPLATES', DIR_WS_CATALOG . 'templates/');
define('DIR_FS_TEMPLATES', DIR_FS_CATALOG . 'templates/');
define('CC_ENC_KEY_SECURE_KEY', 'D6M9Qa+J%P~|GcE$[cz!pk@W)*%ZusN]6'); //Credit Card encryption key. please don't modify it without permission
// define our database connection
define('DB_SERVER', '208.109.123.18'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'usitrip2013');
define('DB_SERVER_PASSWORD', '&92319*87HRz.com59');
define('DB_DATABASE', 'usitrip_com');
define('USE_PCONNECT', 'false'); // use persisstent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
?>
