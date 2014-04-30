<?php
/**
 * 构造Usitrip主站的URL
 * @author lwkai 2013-4-28 上午11:52:32
 *
 */
class Url_Usitrip {
	
	/**
	 * 栏目类
	 * @var category
	 * @author lwkai 2012-11-20 上午11:31:23
	 */
	private $_category = null;
	
	/**
	 * 数据库连接资源
	 * @var db_mysql
	 * @author lwkai 2012-11-20 上午10:45:36
	 */
	private $_db = null;
	
	public function __construct($db) {
		$this->_db = $db;
		$this->_category = new Category($this->_db);
	}
	
	/**
	 * 生成主站所需要的URL连接
	 * $var['cpath'] 有这个键，则表示要生成主站的栏目连接
	 * $var['pagename'] 表示指定某一个页面文件页 如果需要转换成文件夹，则进行相应转换，否则直接带上页面名称
	 * @param string $url 主站域名
	 * @param array $var 参数
	 * @param array $action URL生成动作指令
	 * @return string
	 * @author lwkai 2013-3-1 上午11:17:15
	 */
	public function create($url, $var, $action) {
		unset($var['module'],$var['action']);
		if (empty($url) || !preg_match("/^http(s)?:\/\/\w{1}\.\w+\.\w+/", $url)) {
			if ($action['is_ssl'] == true && ENABLE_SSL == true) {
				$url = defined('HTTPS_USITRIPURL') ? HTTPS_USITRIPURL : 'No constant HTTPS_USITRIPURL configuration';
			} else {
				$url = defined('HTTP_USITRIPURL') ? HTTP_USITRIPURL : 'No constant HTTP_USITRIPURL configuration';
			}
		}
		if (isset($var['pagename'])) {
			switch ($var['pagename']) {
				case 'new_travel_companion_index.php' :
					$url .= 'jiebantongyou/';
					unset($var['pagename']);
					$url = $this->addParam($url, $var);
					break;
				default:
					$url .= $var['pagename'];
					unset($var['pagename']);
					$url = $this->addParam($url, $var, false);
			}
		} elseif (isset($var['cpath'])) {
			$rs = $this->_category->get_url((int)$var['cpath']);
			$url .= $rs;
		} elseif (isset($var['product_id'])) {
			$url .= Attractions_Usitrip::getProductUrl($var['product_id']);
			$url .= '.html';
		}
		return $url;
	}
	
	/**
	 * 根据主站那边的方式生成的URL参数
	 * @param string $url 生成出来的URL地址
	 * @param array $params 还没添加进来的其他参数
	 * @param boolean $seo 是否生成SEO方式的参数连接 true 是 false 默认?后接&
	 * @return string
	 * @author lwkai 2013-3-19 下午3:47:18
	 */
	private function addParam($url,$params,$seo = true){
		if (is_array($params)) {
			if ($seo && $params) {
				foreach($params as $key => $val) {
					$url .= $key . '-' . $val . '-';
				}
				$url = substr($url,0,-1).'.html';
			} elseif ($params) {
				$url .= strpos($url,'?') === false ? '?' : '&';
				foreach($params as $key => $val) {
					$url .= $key . '=' . $val . '&';
				}
				$url = rtrim($url,'&');
			}	
		}
		return $url;
	}
}