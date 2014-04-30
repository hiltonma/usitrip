<?php
/**
 * 构造页面中的URL
 * @author lwkai
 * @date 2012-11-20 下午2:34:31
 * @formatter:off
 * @link <1275124829@163.com>lwkai
 */
class Url{
	
	/**
	 * URL 参数与值之间的分隔符
	 * @var string
	 * @author lwkai 2012-11-20 上午10:17:39
	 */
	private $_separator = '';
	
	/**
	 * 默认的文件扩展名
	 * @var string
	 * @author lwkai 2012-11-20 下午1:51:11
	 */
	private $_expanded_name = '';
	
	/**
	 * 数据库连接资源
	 * @var db_mysql
	 * @author lwkai 2012-11-20 上午10:45:36
	 */
	private $_db = null;
	
	/**
	 * 是否启用了 SSL模式
	 * @var boolean
	 * @author lwkai 2012-11-20 上午10:59:33
	 */
	private $_enable_ssl = false;
	
	/**
	 * 网站当前模块名称
	 * @var string
	 * @author lwkai 2012-11-20 上午11:08:05
	 */
	private $_module = '';
	
	/**
	 * 当前动作名称
	 * @var string
	 * @author lwkai 2012-11-20 上午11:08:19
	 */
	private $_action = 'index';
	
	/**
	 * 栏目类
	 * @var category
	 * @author lwkai 2012-11-20 上午11:31:23
	 */
	private $_category = null;
	
	/**
	 * 存放其他URL构造类对象，如果初始化过后存在此，避免重复初始化
	 * @var array
	 * @author lwkai 2013-1-5 下午4:09:17
	 */
	private $_url = array();
	
	/**
	 * 产品类
	 * @var product
	 * @author lwkai 2012-11-21 下午3:32:29
	 */
	private $_product = null;
	
	/**
	 * 当前页面语言版本
	 * @var string
	 * @author lwkai 2012-11-22 17:18
	 */
	private $_language = '';
	
	/**
	 * 当前URL是否是用的SSL方式
	 * @var boolean
	 * @author lwkai 2013-4-23 下午2:36:02
	 */
	private $_currentSSL = false;
	
	public function __construct($db, $module, $language = 'zh') {
		$this->_currentSSL = ($_SERVER['SERVER_PORT'] == '443' ? true : false);
		$this->_separator = defined('SEO_EXTENSION_SEPARATOR') && SEO_EXTENSION_SEPARATOR != '' ? SEO_EXTENSION_SEPARATOR : '--';
		$this->_expanded_name = defined('SEO_EXTENSION') && SEO_EXTENSION != '' ? SEO_EXTENSION : '.html';
		$this->_enable_ssl = defined('ENABLE_SSL') ? ENABLE_SSL : false;
		$this->_language = $language;
		$this->_module = $module;
		$this->_db = $db;
		$this->_category = new Category(Db::get_db('usitrip'), $this->_language);
		//$this->_product = new Product($db, array('code' => $this->_language));
	}
	
	/**
	 * 返回页面的BASEHREF值
	 * @param string $language 当前语言字符串值[zh,en,tw]等等
	 * @param boolean $ssl 是否开启SSL模式
	 * @return string
	 * @author lwkai 2013-1-6 下午5:56:46
	 */
	public function getBaseHref($language, $ssl = true) {
		$ssl = !!$ssl;
		switch ($language) {
			case 'tw':
				$url = ($this->_enable_ssl && $ssl && $this->_currentSSL) ? TW_HTTPS_SERVER : TW_HTTP_SERVER;
				break;
			case 'zh':
				$url = ($this->_enable_ssl && $ssl && $this->_currentSSL) ? CN_HTTPS_SERVER : CN_HTTP_SERVER;
				break;
			case 'en':
				$url = ($this->_enable_ssl && $ssl && $this->_currentSSL) ? EN_HTTPS_SERVER : EN_HTTP_SERVER;
				break;
		}
		return $url;
	}
	
	/**
	 * 根据SSL生成带SSL或非SSL的URL
	 * @param boolean $ssl 是否需要 HTTPS 开头的URL
	 * @return string
	 * @author lwkai 2012-11-20 下午2:05:56
	 */
	private function is_ssl($ssl, $language) {
		$url = $this->getBaseHref($language, $ssl);
		return $url . DIR_WS_ROOT;
	}
	
	/**
	 * 生成非SEO的URL
	 * @param array $var 参数数组
	 * @param boolean $is_ajax 是否是AJAX用的URL
	 * @return string
	 * @author lwkai 2012-11-20 下午2:15:01
	 */
	private function create_noseo($var, $is_ajax = false) {
		$url = '?';
		foreach($var as $key => $val) {
			$url .= $key . '=' . $val . '&';
		}
		
		if ($is_ajax == true) {
			$url .= 'ajax=true';
		}
		return $url;
	}
	
	/**
	 * SEO方式的URL
	 * @param array $var
	 * @author lwkai 2012-11-20 下午2:31:17
	 */
	private function create_seo($var) {
		$url = '';
		$noext = false;
		if ($var['action'] == 'index' && $var['module'] == 'index' && count($var) == 2) {
			unset($var['action'],$var['module']);
			$noext = true;
		} else {
			$url .= $var['module'];
			$url .= '/' . $var['action'];
			unset($var['module'],$var['action']);
			if (count($var)) $url .= '/';
		}
		foreach ($var as $key => $val) {
			$url .= $key . $this->_separator . $val . $this->_separator;
		}
		if (count($var) > 0) {
			$url = substr($url,0,-1 * strlen($this->_separator));
			$noext = false;
		}
		if (!$noext) {
			$url .= $this->_expanded_name;
		}
		return $url;
	}
	
	/**
	 * 如果是栏目，则用CPATH参数名
	 * @param string $url 网站域名，如果非本站，请传完整的域名
	 * @param array $var 参数数组，array('module'=>'模块名称','action'=>'动作方法','cpath'=>'栏目ID','tabs'=>'子栏目','product_id'=>产品ID,其他参数)
	 * 
	 * @param array $action array('is_ssl'=>true|false, 'is_noseo'=>true|false, 'is_ajax'=>true|false, 'is_self' => string) 
	 * 		is_ssl 是否需要使用SSL方式，在网站开启SSL后。
	 * 		is_noseo 是否此URL不需要按SEO的形式生成
	 * 		is_ajax 是否此URL是用在AJAX请求上
	 * 		is_self 是否是生成本系统所用的URL，'self'[本系统],'usitrip'[www.usitrip.com站点用],其它请自己定义
	 * @return string
	 * @author lwkai 2012-11-20 上午10:47:10
	 */
	public function create($url = '', $var = array(), $action = array()) {
		$action['is_ssl']   = isset($action['is_ssl']) ? !!$action['is_ssl'] : false;
		$action['is_noseo'] = isset($action['is_noseo']) ? !!$action['is_noseo'] : false;
		$action['is_ajax']  = isset($action['is_ajax']) ? !!$action['is_ajax'] : false;
		//是否需要生成非本站 self 本站URL
		$action['is_self'] = isset($action['is_self']) ? $action['is_self'] : 'self';
		$var['language'] = isset($var['language']) ? $var['language'] : $this->_language;
		if (empty($url)) {
			$url = $this->is_ssl($action['is_ssl'],$var['language']);
			unset($var['language']);
		}
		$var['module'] = empty($var['module']) ? $this->_module : $var['module'];
		$var['action'] = empty($var['action']) ? $this->_action : $var['action'];
		
		switch ($action['is_self']) {
			case 'usitrip': //创建USITRIP的URL
				$url = $this->create_usitrip($url,$var,$action);
				break;
			default: //创建本系统的URL
				$url .= $this->create_self($var, $action);
		}
		
		return $url;
	}

	/**
	 * 创建本系统所需要的URL形式
	 * @param array $var 参数数组，array('module'=>'模块名称','action'=>'动作方法','cpath'=>'栏目ID','tabs'=>'子栏目','product_id'=>产品ID, 其他参数)
	 * @param array $action array('is_ssl'=>true|false, 'is_noseo'=>true|false, 'is_ajax'=>true|false)
	 * @return string
	 * @author lwkai 2013-1-5 下午3:54:36
	 */
	private function create_self($var,$action) {
		if ($action['is_noseo'] == true || $action['is_ajax'] == true) {
			$url = $this->create_noseo($var, $action['is_ajax']);
		} else {
			$url = $this->create_seo($var);
		}
		return $url;
	}
	
	/**
	 * 生成USITRIP主站所需要的URL
	 * @param string $url 是否是生成其他站的URL，本站留空即可
	 * @param array $var GET参数键值对 array('key'=>'val')
	 * @param array $action URL生成形式选项
	 * @author lwkai 2013-1-5 下午3:59:46
	 */
	private function create_usitrip($url, $var, $action) {
		if (!isset($this->_url['usitrip'])) {
			$usitrip_url = new Url_Usitrip(Db::get_db('usitrip'));
			$this->_url['usitrip'] = $usitrip_url;
		}
		return $this->_url['usitrip']->create($url, $var, $action);
	}
	
	/**
	 * 解析URL地址，并还原GET参数
	 * @param string $url_str
	 * @author lwkai 2012-11-20 上午10:47:10
	 */
	public function parse($url_str) {
		if (empty($url_str)) return;
		if (strpos($url_str, '?') === false) {
			$url_str_2 = preg_replace('/' . preg_quote($this->_expanded_name). '$/i','',$url_str);
			$str_arr = explode('/',$url_str_2);
			isset($str_arr[0]) && $_GET['module'] = $str_arr[0];
			isset($str_arr[1]) && $_GET['action'] = $str_arr[1];
			$param_arr = '';
			if (isset($str_arr[2]) && $str_arr[2]) {
				$param_arr = explode($this->_separator, $str_arr[2]);
			}
			if (is_array($param_arr)) {
				for ($i = 0, $len = count($param_arr); $i < $len; $i += 2) {
					$_GET[strtolower($param_arr[$i])] = $param_arr[$i+1];
				}
			}
		}
	}

}