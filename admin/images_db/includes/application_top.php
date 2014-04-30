<?php
session_start();

// set the level of error reporting
  ini_set('display_errors', 1); 
  error_reporting(E_ALL & ~E_NOTICE);
/*/网站所在路径Dev
define('PATH_DIR','/home/howard/public_html/usitrip/admin/images_db/');  
define('WEB_DIR','http://howard-dev.usitrip.com/admin/images_db/');  
define('IMAGES_DIR','/var/www/html/usitrip/images/db/');  
define('IMAGES_HTTP_DIR','http://208.109.123.18/images/db/');  
*/
//网站所在路径Prod
define('TOP_DIR', str_replace('/admin/images_db/includes','',str_replace('\\','/',dirname(__FILE__))));
define('ADMIN_DIR', TOP_DIR.'/admin');
require(ADMIN_DIR. '/includes/configure.php');
define('PATH_DIR', ADMIN_DIR.'/images_db/');  
define('WEB_DIR', HTTP_SERVER.'/admin/images_db/');  
define('IMAGES_DIR',TOP_DIR.'/images/db/');  
define('IMAGES_HTTP_DIR', HTTP_SERVER.'/images/db/');  

/*数据表名称*/
define('TABLE_CATEGORIES' , 'categories');

/*JS相关提示*/
define('JS_MSN_FIREFOX_WRING',"如果您正在使用FireFox！\\n请在浏览器地址栏输入'about:config'并回车\\n然后将'signed.applets.codebase_principal_support'设置为'true'");

/* define our database connection
  define('DB_SERVER', 'localhost'); 
  define('DB_SERVER_USERNAME', '');
  define('DB_SERVER_PASSWORD', '');
*/
  define('DB_DATABASE_IMAGES_DB', 'images_for_tours');

  define('SM_WIDTH','148');	/* 小图宽度 */
  define('SM_HEIGHT','111');	/* 小图高度 */


require(PATH_DIR.'includes/classes/set_pagination.php'); 
require(PATH_DIR.'includes/functions/general.php'); 
require(PATH_DIR.'includes/functions/html_output.php'); 
require(PATH_DIR.'includes/functions/database.php'); 
tep_db_connect() or die('Unable to connect to database server!');
require(PATH_DIR.'includes/functions/zhh_function.php'); 
require(PATH_DIR.'includes/functions/thumbnails.php'); 
//require(PATH_DIR.'includes/functions/sessions.php'); 


if($_GET['lang']=='en' && $_SESSION['lang']!='en'){
	$_SESSION['lang']='en';
	session_start();

}elseif($_GET['lang']=='cn' && $_SESSION['lang']!='cn'){
	$_SESSION['lang']='cn';
	session_start();
}

if($_SESSION['lang']=='en'){
	require(PATH_DIR.'includes/lang/lang_en.php'); 
}else{
	require(PATH_DIR.'includes/lang/lang_cn.php'); 
}
//echo $lang;
//print_r($_SESSION);

/*取得方案类别函数*/
function get_image_group_name($id){
	if((int)$id<1){ return false; }
	$sql= tep_db_query("select * from `images_group` where `group_id`='".(int)$id."' ");
	$row= tep_db_fetch_array($sql);
	if($_SESSION['lang']=='en' && $row['group_name_en']!=""){
		return $row['group_name_en'];
	}else{
		return $row['group_name'];
	}
}
/*取得景点类别函数*/
function get_product_group_name(&$categories_str, $id){
	if((int)$id<1){ return false; }
	$sql= tep_db_query("SELECT * FROM `categories` c, `categories_description` cd  where c.categories_id=cd.categories_id AND c.categories_id ='".(int)$id."' ");
	$row= tep_db_fetch_array($sql);
	$categories_str = $row['categories_name'] .' &gt; '. $categories_str ;
	if((int)$row['parent_id']>0){
		get_product_group_name($categories_str, (int)$row['parent_id']);
	}
	$categories_str = preg_replace('/ &gt; $/','',$categories_str);
	return $categories_str;
}

?>