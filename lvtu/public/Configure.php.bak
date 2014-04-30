<?php
/**
 * 网站常量配置文件
 * 
 * 关闭格式化本文件的执行
 * @formatter:off
 */

/**
 * 保护其他页面不让直接访问常量
 * @var boolean
 */
define('IN_TRIP_IMAGE', true);

if ($_SERVER['HTTP_HOST'] == 'test.usitrip.com') {
	/**
	 * 如果是生产站(即上线站)，则设置为TRUE
	 * @var boolean
	 */
	define('IS_LIVE_SITES', false);
	
	/**
	 * 如果是测试站,则设置为TRUE
	 * @var boolean
	 */
	define('IS_QA_SITES', true);
	
	/**
	 * 如果是开发站，则设置为TRUE
	 * @var boolean
	 */
	define('IS_DEV_SITES', false);
	
	define('CN_HTTP_SERVER', 'http://test.usitrip.com');
	define('CN_HTTPS_SERVER', 'https://test.usitrip.com');
	define('TW_HTTP_SERVER', 'http://test.usitrip.com');
	define('TW_HTTPS_SERVER', 'https://test.usitrip.com');
	define('EN_HTTP_SERVER','');
	define('EN_HTTPS_SERVER','');
	
	/**
	 * 主站的域名
	 * @var string
	 * @author lwkai 2013-3-1 上午11:16:16
	 */
	define('HTTP_USITRIPURL','http://test.usitrip.com/');
	define('HTTPS_USITRIPURL','https://test.usitrip.com/');
} else {
	/**
	 * 如果是生产站(即上线站)，则设置为TRUE
	 * @var boolean
	 */
	define('IS_LIVE_SITES', true);
	
	/**
	 * 如果是测试站,则设置为TRUE
	 * @var boolean
	*/
	define('IS_QA_SITES', false);
	
	/**
	 * 如果是开发站，则设置为TRUE
	 * @var boolean
	*/
	define('IS_DEV_SITES', false);
	
	define('CN_HTTP_SERVER', 'http://www.usitrip.com');
	define('CN_HTTPS_SERVER', 'https://www.usitrip.com');
	define('TW_HTTP_SERVER', 'http://www.usitrip.com');
	define('TW_HTTPS_SERVER', 'https://www.usitrip.com');
	define('EN_HTTP_SERVER','');
	define('EN_HTTPS_SERVER','');
	
	/**
	 * 主站的域名
	 * @var string
	 * @author lwkai 2013-3-1 上午11:16:16
	 */
	define('HTTP_USITRIPURL','http://www.usitrip.com/');
	define('HTTPS_USITRIPURL','https://www.usitrip.com/');
}

/**
 * 是否启用SSL
 * @var boolean
 */
define('ENABLE_SSL', true);


define('HTTP_COOKIE_DOMAIN', '.usitrip');
define('HTTPS_COOKIE_DOMAIN', '.usitrip');
/**
 * 系统分隔符
 * @var string
 */
define('DS', DIRECTORY_SEPARATOR);
/**
 * 网站根目录
 * @var string
 */
define('DIR_FS_ROOT', dirname(dirname(__FILE__)) . DS);

/**
 * 模块所在目录 module 文件夹
 * @var string
 */
define('DIR_FS_MODULE', DIR_FS_ROOT . 'module' . DS);

/**
 * 控制器所在目录 controller 文件夹
 * @var unknown_type
 * @author lwkai 2012-11-23 下午5:46:13
 */
define('DIR_FS_CONTROLLER', DIR_FS_ROOT . 'controller' . DS);

/**
 * 语言文件所有位置
 * @var string
 * @author lwkai 2012-12-27 下午3:22:11
 */
define('DIR_FS_LANGUAGE', DIR_FS_ROOT . 'public' . DS . 'languages' . DS);

/**
 * 数据库配置文件位置
 * @var string
 */
define('DIR_FS_DATABASE',DIR_FS_ROOT . 'public' . DS . 'Datebase.php');

/**
 * 数据库是否采用持久连接
 * @var boolean
 */
define('DB_LASTING',false);

/**
 * SQL是否启用缓存
 * @var boolean
 */
define('ENABLE_SQL_CACHE', true);

/**
 * 不能缓存的数据表名称
 * @var string
 */
define('NO_CACHE_TABLES','sessions,sql_query_logs');

/**
 * 保存session的方式，如果保存到数据库就设置为mysql，如果保存到文件夹就设置空值。注意此值要和主站的相同常量值要保持一致！
 * @var string
 */
define('STORE_SESSIONS', '');

/**
 * 如果以文件夹的方式保存session，在这里设置文件夹的保存位置。注意此值要和主站的相同常量值要保持一致！
 * @var string
 */
define('SESSION_WRITE_DIRECTORY', '/tmp/usitrip.session');

/**
 * 是否生成html静态页面
 * @var int
 * @author lwkai 2012-11-20 上午9:56:46
 */
define('IS_CREATE_HTML',0);

/**
 * SEO网页的扩展名，即生成的文件扩展名
 * @var string
 * @author lwkai 2012-11-20 上午9:57:46
 */
define('SEO_EXTENSION','.html');

/**
 * SEO的GET参数和页面的分隔符，要用一些文件、参数和参数值中不容易出现的字符。不能用一个-；也不推荐用“\/:*"<>|”，因为无法生成文件名带有这些符号的静态页面！可用+号。
 * 即URL参数分隔符
 * @var string
 * @author lwkai 2012-11-20 上午10:10:01
 */
define('SEO_EXTENSION_SEPARATOR','--');

/**
 * SMARTY所有目录
 * @var string
 */
define('DIR_FS_SMARTY', DIR_FS_ROOT . 'smarty' . DS);

/**
 * 是否打开调试
 * @var boolean
 */
define('DEBUG', false);

/**
 * 页面用常量，WEB根目录
 * @var string
 * @author lwkai 2012-12-25 上午10:10:16
 */
define('DIR_WS_ROOT', '/lvtu/');



?>