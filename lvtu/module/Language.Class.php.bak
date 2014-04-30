<?php 

/**
 * 语言类
 * @author lwkai 2012-12-27 上午11:14:11
 *
 */
class Language {
	
	/**
	 * 枚举的可能的语言
	 * @var Array
	 * @author lwkai 2012-12-27 上午11:13:45
	 */
	private $_languages = array(
		'tw' => 'zh[-_]tw|chinese traditional',       //繁体
		'zh' => 'zh|chinese simplified',              //简体
		'en' => 'en([-_][[:alpha:]]{2})?|english',    //英语
		'es' => 'es([-_][[:alpha:]]{2})?|spanish',    //西班牙
		'ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
		'bg' => 'bg|bulgarian',
		'br' => 'pt[-_]br|brazilian portuguese',
		'ca' => 'ca|catalan',
		'cs' => 'cs|czech',
		'da' => 'da|danish',
		'de' => 'de([-_][[:alpha:]]{2})?|german',
		'el' => 'el|greek',
		'et' => 'et|estonian',
		'fi' => 'fi|finnish',
		'fr' => 'fr([-_][[:alpha:]]{2})?|french',
		'gl' => 'gl|galician',
		'he' => 'he|hebrew',
		'hu' => 'hu|hungarian',
		'id' => 'id|indonesian',
		'it' => 'it|italian',
		'ja' => 'ja|japanese',
		'ko' => 'ko|korean',
		'ka' => 'ka|georgian',
		'lt' => 'lt|lithuanian',
		'lv' => 'lv|latvian',
		'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
		'no' => 'no|norwegian',
		'pl' => 'pl|polish',
		'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
		'ro' => 'ro|romanian',
		'ru' => 'ru|russian',
		'sk' => 'sk|slovak',
		'sr' => 'sr|serbian',
		'sv' => 'sv|swedish',
		'th' => 'th|thai',
		'tr' => 'tr|turkish',
		'uk' => 'uk|ukrainian',
	);
	
	/**
	 * 数据库操作类
	 * @var Db_Mysql
	 * @author lwkai 2012-12-27 上午11:16:32
	 */
	private $_db = null;
	
	/**
	 * 当前网站支持的语言
	 * @var array
	 * @author lwkai 2012-12-27 上午11:17:59
	 */
	private $_site_language = array();
	
	/**
	 * 当前浏览器的语言
	 * @var string
	 * @author lwkai 2012-12-27 上午11:27:15
	 */
	private $_browser_language = '';
	
	/**
	 * 当前的语言
	 * @var array
	 * @author lwkai 2012-12-27 上午11:51:07
	 */
	private $_current_language = array();
	
	public function __construct($db,$user_language) {
		$this->_db = $db;
		$result = $this->_db->query("select languages_id, name, code, image, directory, charset from languages order by sort_order")->getAll();
		foreach ($result as $key => $val) {
			$this->_site_language[$val['code']] = array(
				'id'        => $val['languages_id'],
				'name'      => $val['name'],
				'code'      => $val['code'],
				'image'     => $val['image'],
				'directory' => $val['directory'],
				'charset'   => $val['charset']
			);
		}
		$this->setLanguage($user_language);
		$this->getBrowserLanguage();
	}
	
	/**
	 * 返回当前的语言
	 * @return array
	 * @author lwkai 2012-12-27 上午11:57:54
	 */
	public function getCurrentLanguage() {
		return $this->_current_language;
	}
	
	/**
	 * 取得浏览器所使用的语言
	 * @author lwkai 2012-12-27 上午11:57:31
	 */
	private function getBrowserLanguage() {
		$this->_browser_language = explode(',', getenv('HTTP_ACCEPT_LANGUAGE'));
		for ($i=0, $n=sizeof($this->_browser_language); $i<$n; $i++) {
			foreach ($this->_languages as $key => $value) {
				if (preg_match('/^(' . $value . ')(;q=[0-9]\\.[0-9])?$/i', $this->_browser_language[$i]) && isset($this->_site_language[$key])) {
					$this->_current_language = $this->_site_language[$key];
					break 2;
				}
			}
		}
	}
	
	/**
	 * 设置当前语言
	 * @param string $language
	 * @author lwkai 2012-12-27 上午11:56:44
	 */
	private function setLanguage($language) {
		if (!empty($language) && isset($this->_site_language[$language])) {
			$this->_current_language = $this->_site_language[$language];
		} else {
			$this->_current_language = $this->_site_language['zh'];
		}
	}
}