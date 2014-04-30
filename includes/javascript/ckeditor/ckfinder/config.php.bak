<?php
/*
 * ### CKFinder : Configuration File - Basic Instructions
 *
 * In a generic usage case, the following tasks must be done to configure
 * CKFinder:
 *     1. Check the $baseUrl and $baseDir variables;
 *     2. If available, paste your license key in the "LicenseKey" setting;
 *     3. Create the CheckAuthentication() function that enables CKFinder for authenticated users;
 *
 * Other settings may be left with their default values, or used to control
 * advanced features of CKFinder.
 */

/**
 * This function must check the user session to be sure that he/she is
 * authorized to upload and access files in the File Browser.
 *
 * @return boolean
 */
define('_TEP_ROOT_DIR_',dirname(__FILE__).'/../../../../');
define('DIR_FS_CATALOG', _TEP_ROOT_DIR_);
define('DIR_WS_INCLUDES', _TEP_ROOT_DIR_.'includes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
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

function CheckAuthentication()
{
	global $_accUploadCkeditor,$customer_id;
	// WARNING : DO NOT simply return "true". By doing so, you are allowing
	// "anyone" to upload and list the files in your server. You must implement
	// some kind of session validation here. Even something very simple as...

	// return isset($_SESSION['IsAuthorized']) && $_SESSION['IsAuthorized'];

	// ... where $_SESSION['IsAuthorized'] is set to "true" as soon as the
	// user logs in your system. To be able to use session variables don't
	// forget to add session_start() at the top of this file.
	if($_accUploadCkeditor=='allow' && intval($customer_id)>0){
		return true;
	}else{
		return false;
	}
}

// LicenseKey : Paste your license key here. If left blank, CKFinder will be
// fully functional, in demo mode.
$config['LicenseName'] = '';
$config['LicenseKey'] = '';

/*
 Uncomment lines below to enable PHP error reporting and displaying PHP errors.
 Do not do this on a production server. Might be helpful when debugging why CKFinder does not work as expected.
*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/*
To make it easy to configure CKFinder, the $baseUrl and $baseDir can be used.
Those are helper variables used later in this config file.
*/

/*
$baseUrl : the base path used to build the final URL for the resources handled
in CKFinder. If empty, the default value (/userfiles/) is used.

Examples:
	$baseUrl = 'http://example.com/ckfinder/files/';
	$baseUrl = '/userfiles/';

ATTENTION: The trailing slash is required.
*/
$baseUrl = '/images/uploadfile/'.$customer_id.'/';

/*
$baseDir : the path to the local directory (in the server) which points to the
above $baseUrl URL. This is the path used by CKFinder to handle the files in
the server. Full write permissions must be granted to this directory.

Examples:
	// You may point it to a directory directly:
	$baseDir = '/home/login/public_html/ckfinder/files/';
	$baseDir = 'C:/SiteDir/CKFinder/userfiles/';

	// Or you may let CKFinder discover the path, based on $baseUrl.
	// WARNING: resolveUrl() *will not work* if $baseUrl does not start with a slash ("/"),
	// for example if $baseDir is set to  http://example.com/ckfinder/files/
	$baseDir = resolveUrl($baseUrl);

ATTENTION: The trailing slash is required.
*/
$baseDir = resolveUrl($baseUrl);

/*
 * ### Advanced Settings
 */

/*
Thumbnails : thumbnails settings. All thumbnails will end up in the same
directory, no matter the resource type.
*/
$config['Thumbnails'] = Array(
		'url' => $baseUrl . '_thumbs',
		'directory' => $baseDir . '_thumbs',
		'enabled' => true,
		'directAccess' => false,
		'maxWidth' => 100,
		'maxHeight' => 100,
		'bmpSupported' => false,
		'quality' => 80);

/*
Set the maximum size of uploaded images. If an uploaded image is larger, it
gets scaled down proportionally. Set to 0 to disable this feature.
*/
$config['Images'] = Array(
		'maxWidth' => 1600,
		'maxHeight' => 1200,
		'quality' => 80);

/*
RoleSessionVar : the session variable name that CKFinder must use to retrieve
the "role" of the current user. The "role", can be used in the "AccessControl"
settings (bellow in this page).

To be able to use this feature, you must initialize the session data by
uncommenting the following "session_start()" call.
*/
$config['RoleSessionVar'] = 'CKFinder_UserRole';
//session_start();

/*
AccessControl : used to restrict access or features to specific folders.

Many "AccessControl" entries can be added. All attributes are optional.
Subfolders inherit their default settings from their parents' definitions.

	- The "role" attribute accepts the special '*' value, which means
	  "everybody".
	- The "resourceType" attribute accepts the special value '*', which
	  means "all resource types".
*/

$config['AccessControl'][] = Array(
		'role' => '*',
		'resourceType' => '*',
		'folder' => '/',

		'folderView' => true,
		'folderCreate' => true,
		'folderRename' => true,
		'folderDelete' => true,

		'fileView' => true,
		'fileUpload' => true,
		'fileRename' => true,
		'fileDelete' => true);

/*
For example, if you want to restrict the upload, rename or delete of files in
the "Logos" folder of the resource type "Images", you may uncomment the
following definition, leaving the above one:

$config['AccessControl'][] = Array(
		'role' => '*',
		'resourceType' => 'Images',
		'folder' => '/Logos',

		'folderView' => true,
		'folderCreate' => true,
		'folderRename' => true,
		'folderDelete' => true,

		'fileView' => true,
		'fileUpload' => false,
		'fileRename' => false,
		'fileDelete' => false);
*/

/*
ResourceType : defines the "resource types" handled in CKFinder. A resource
type is nothing more than a way to group files under different paths, each one
having different configuration settings.

Each resource type name must be unique.

When loading CKFinder, the "type" querystring parameter can be used to display
a specific type only. If "type" is omitted in the URL, the
"DefaultResourceTypes" settings is used (may contain the resource type names
separated by a comma). If left empty, all types are loaded.

maxSize is defined in bytes, but shorthand notation may be also used.
Available options are: G, M, K (case insensitive).
1M equals 1048576 bytes (one Megabyte), 1K equals 1024 bytes (one Kilobyte), 1G equals one Gigabyte.
Example: 'maxSize' => "8M",
*/
$config['DefaultResourceTypes'] = '';

$config['ResourceType'][] = Array(
		'name' => 'Files',				// Single quotes not allowed
		'url' => $baseUrl . 'files',
		'directory' => $baseDir . 'files',
		'maxSize' => 0,
		'allowedExtensions' => '7z,bmp,csv,doc,docx,gif,gz,gzip,flv,jpeg,jpg,pdf,png,ppt,pptx,rar,swf,tar,tgz,txt,xls,xlsx,zip',
		'deniedExtensions' => '');

$config['ResourceType'][] = Array(
		'name' => 'Images',
		'url' => $baseUrl . 'images',
		'directory' => $baseDir . 'images',
		'maxSize' => "16M",
		'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
		'deniedExtensions' => '');

$config['ResourceType'][] = Array(
		'name' => 'Flash',
		'url' => $baseUrl . 'flash',
		'directory' => $baseDir . 'flash',
		'maxSize' => 0,
		'allowedExtensions' => 'swf,flv',
		'deniedExtensions' => '');

/*
 Due to security issues with Apache modules, it is recommended to leave the
 following setting enabled.

 How does it work? Suppose the following:

	- If "php" is on the denied extensions list, a file named foo.php cannot be
	  uploaded.
	- If "rar" (or any other) extension is allowed, one can upload a file named
	  foo.rar.
	- The file foo.php.rar has "rar" extension so, in theory, it can be also
	  uploaded.

In some conditions Apache can treat the foo.php.rar file just like any PHP
script and execute it.

If CheckDoubleExtension is enabled, each part of the file name after a dot is
checked, not only the last part. In this way, uploading foo.php.rar would be
denied, because "php" is on the denied extensions list.
*/
$config['CheckDoubleExtension'] = true;

/*
If you have iconv enabled (visit http://php.net/iconv for more information),
you can use this directive to specify the encoding of file names in your
system. Acceptable values can be found at:
	http://www.gnu.org/software/libiconv/

Examples:
	$config['FilesystemEncoding'] = 'CP1250';
	$config['FilesystemEncoding'] = 'ISO-8859-2';
*/
$config['FilesystemEncoding'] = 'UTF-8';

/*
Perform additional checks for image files
if set to true, validate image size
*/
$config['SecureImageUploads'] = true;

/*
Indicates that the file size (maxSize) for images must be checked only
after scaling them. Otherwise, it is checked right after uploading.
*/
$config['CheckSizeAfterScaling'] = true;

/*
For security, HTML is allowed in the first Kb of data for files having the
following extensions only.
*/
$config['HtmlExtensions'] = array('html', 'htm', 'xml', 'js');

/*
Folders to not display in CKFinder, no matter their location.
No paths are accepted, only the folder name.
The * and ? wildcards are accepted.
*/
$config['HideFolders'] = Array(".svn", "CVS");

/*
Files to not display in CKFinder, no matter their location.
No paths are accepted, only the file name, including extension.
The * and ? wildcards are accepted.
*/
$config['HideFiles'] = Array(".*");

/*
After file is uploaded, sometimes it is required to change its permissions
so that it was possible to access it at the later time.
If possible, it is recommended to set more restrictive permissions, like 0755.
Set to 0 to disable this feature.
Note: not needed on Windows-based servers.
*/
$config['ChmodFiles'] = 0777 ;

/*
See comments above.
Used when creating folders that does not exist.
*/
$config['ChmodFolders'] = 0755 ;

/*
Force ASCII names for files and folders.
If enabled, characters with diactric marks, like 
will be automatically converted to ASCII letters.
*/
$config['ForceAscii'] = false;


include_once "plugins/imageresize/plugin.php";
include_once "plugins/fileeditor/plugin.php";

$config['plugin_imageresize']['smallThumb'] = '90x90';
$config['plugin_imageresize']['mediumThumb'] = '120x120';
$config['plugin_imageresize']['largeThumb'] = '180x180';
