<?php
/**
 * 子控制器的抽象类 负责处理页头和页尾信息
 * @author lwkai 2012-11-20 上午10:41:48
 *
 */
abstract class Abstract_Default {
	
	/**
	 * 模块名称
	 * @var string
	 */
	protected $_module = 'index';
	
	/**
	 * 动作名称
	 * @var string
	 * @author lwkai 2012-11-20 上午10:41:48
	 */
	protected $_action = 'index';
	
	/**
	 * smarty对象
	 * @var Smarty
	 */
	protected $_smarty = null;
	
	/**
	 * 数据库操作对象 db_mysql的实例
	 * @var db_mysql
	 */
	protected $_db = null;
	
	/**
	 * 构造URL的对象
	 * @var Url
	 * @author lwkai 2012-11-20 上午10:43:05
	 */
	protected $_url = null;
	
	/**
	 * 栏目类
	 * @var Category
	 * @author lwkai 2012-12-24 下午5:45:38
	 */
	protected $_category = null;
	
	/**
	 * 语言版本，默认是中文简体即array('name'=>'cn','code'=>'gb2312','id'=>2);
	 * 如果是繁体则 array('name'=>'tw','code'=>'big5','id'=>2);
	 * 如果是英文则 array('name'=>'en','code'=>'utf-8','id'=>1);
	 * @var array
	 * @author lwkai 2012-11-22 16:39
	 */
	protected $_language = array();
	
	/**
	 * 页面执行时间
	 * @var int
	 * @author lwkai 2012-11-22 16:38
	*/
	protected $_time;
	
	/**
	 * 保存所有SMARTY变量到此数组
	 * @var array
	 */
	protected $_data = array();
	
	/**
	 * 要显示的模板文件
	 * @var string
	 */
	protected $_template_file = '';
	
	/**
	 * 构造函数 初始化数据库连接类与页头连接类等等
	 * @param string $module_name 模块名称
	 * @author lwkai 2012-11-22 下午6:02:05
	 */
	protected function __construct($module_name = 'index') {
		$this->_module = $module_name;
		$this->_action = empty($_GET['action']) ? 'index' : (string) $_GET['action'];
		$this->_db = Db::get_db();
		$language = new Language($this->_db, isset($_GET['language']) ? $_GET['language'] : 'zh');
		$this->_language = $language->getCurrentLanguage();
		$language_file = DIR_FS_ROOT . 'public' . DS . 'languages' . DS . $this->_language['directory'] . '.php';
		if (file_exists($language_file)) {
			include $language_file;
		}
		$this->_url = new Url($this->_db,$this->_module,$this->_language['code']);
		$this->_category = new category(Db::get_db('usitrip'), $this->_language['name']);
		$this->_smarty = new Smarty();
		$this->_time = microtime(true);
		$this->before();
		$this->set_smarty();

	}

	/**
	 * 封装了smarty配置信息
	 */
	private function set_smarty() {
		$this->_smarty->template_dir	= defined('DIR_FS_TPL_ROOT') ? DIR_FS_TPL_ROOT : sprintf('%sviews' . DS . '%s' . DS, DIR_FS_ROOT, 'default');
		//$this->_smarty->set_root(sprintf('%s',ROOT_DIR));
		//$this->_smarty->setTemplate(sprintf('%s%s/','Templates/',$this->_module));
		$this->_smarty->compile_dir	   = defined('DIR_FS_TPL_CPE_ROOT') ? DIR_FS_TPL_CPE_ROOT : sprintf('%sruntime' .DS .'templates_c' . DS . '%s', DIR_FS_ROOT, 'default');
		$this->_smarty->caching		   = false;
		$this->_smarty->cache_dir	   = defined('DIR_FS_TPL_CACHE') ? DIR_FS_TPL_CACHE : DIR_FS_ROOT . 'runtime' . DS . 'cache';
		$this->_smarty->left_delimiter  = '{{';
		$this->_smarty->right_delimiter = '}}';

	}
	
	/**
	 * 初始化网站的页头所有公共的数据
	 * 
	 * @author lwkai 2013-1-4 下午5:46:31
	 */
	public function before() {
		$this->_data['head_base_href'] = $this->_url->getBaseHref($this->_language['code']);//($this->request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ROOT;
		// 载入系统中配置的一系列常量
		$rs = $this->_db->query("select configuration_key,configuration_value from configuration")->getAll();
		foreach ($rs as $key => $val) {
			define($val['configuration_key'],$val['configuration_value']);
		}
		// 获取当前模板常量
		$rs = $this->_db->query("select * from sys_theme where sys_theme_id='" . SYS_THEME . "'")->getOne();
		if ($rs) {
			
			define('DIR_WS_JS', DIR_WS_ROOT . 'views/' . $rs['sys_theme_floder_name'] . '/' . $rs['sys_theme_js_floder_name'] . '/');
			define('DIR_WS_IMG', DIR_WS_ROOT . 'views/' . $rs['sys_theme_floder_name'] . '/' . $rs['sys_theme_image_floder_name'] . '/');
			define('DIR_WS_CSS', DIR_WS_ROOT . 'views/' . $rs['sys_theme_floder_name'] . '/' . $rs['sys_theme_css_floder_name'] . '/');
			
			/**
			 * 模板所有目录
			 * @var string
			 * @author lwkai 2012-11-20 下午3:34:36
			 */
			define('DIR_FS_TPL_ROOT', DIR_FS_ROOT . 'views' . DS . $rs['sys_theme_floder_name'] . DS);
			
			/**
			 * 模板编译后的文件存放目录
			 * @var string
			 * @author lwkai 2012-11-20 下午3:37:03
			 */
			define('DIR_FS_TPL_CPE_ROOT', DIR_FS_ROOT . 'runtime' . DS . 'templates_c' . DS . $rs['sys_theme_floder_name']);
			if (!is_dir(DIR_FS_TPL_CPE_ROOT)) {
				if (!mkdir(DIR_FS_TPL_CPE_ROOT)) {
					throw new Exception('"runtime' . DS . 'templates_c"' . ' apache没写入权限！请设置权限为0755.');
				}
			}
			/**
			 * 模板缓存目录
			 * @var string
			 * @author lwkai 2012-11-20 下午3:38:33
			*/
			define('DIR_FS_TPL_CACHE',DIR_FS_ROOT . 'runtime' . DS . 'cache' . DS . $rs['sys_theme_floder_name']);
				
			
		} else {
			My_Exception::mythrow('theme_err', '模板配置错误！');
		}
		
		$arr = $_GET;
		if ($this->_language['code'] == 'zh') {
			$arr['language'] = 'tw';
			$this->_data['language'][0] = array(
					'href' => $this->_url->create('',$arr),
					'text' => '繁体',
					'class'=> 'traditional'
			);
			$arr['language'] = 'en';
			$this->_data['language'][1] = array(
					'href' => $this->_url->create('',$arr),
					'text' => 'English',
					'class'=> 'english'
			);
		} elseif ($this->_language['code'] == 'tw') {
			$arr['language'] = 'zh';
			$this->_data['language'][0] = array(
					'href' => $this->_url->create('',$arr),
					'text' => '简体',
					'class'=> 'traditional'
			);
			$arr['language'] = 'en';
			$this->_data['language'][1] = array(
					'href' => $this->_url->create('',$arr),
					'text' => 'English',
					'class'=> 'english'
			);
		} else {
			$arr['language'] = 'zh';
			$this->_data['language'][0] = array(
					'href' => $this->_url->create('',$arr),
					'text' => '简体',
					'class'=> 'traditional'
			);
			$arr['language'] = 'tw';
			$this->_data['language'][1] = array(
					'href' => $this->_url->create('',$arr),
					'text' => '繁体',
					'class'=> 'traditional'
			);
		}
		$this->_data['nav_index'] = $this->_url->create('',array('module'=>'index'),array('is_ssl'=>true)); 
		// 初始化页头的连到主站的连接
		$_CategoriesIds = array('25','24','33','54','157','298','182','299','196');	//注：这些数字是目录ID，可在后台categories.php页面查到
		$navlist = array();
		$navlist[] = array('text' => '首页','href' => $this->_url->create('',array(),array('is_self'=>'usitrip')));
		foreach ($_CategoriesIds as $key => $val) {
			if ($val == 196) {
				$navlist[] = array(
					'href'=>$this->_url->create('',array('pagename'=>'new_travel_companion_index.php'),array('is_self' => 'usitrip')),
					'text'=>'结伴同游');
			}
			$navlist[] = array(
					//'cId' => $val,
					'href' => $this->_url->create('',array('cpath'=>$val),array('is_self' => 'usitrip')),
					'text' => $this->_category->get_name($val)
			);
		}
		$this->_data['navlist'] = $navlist;
		$this->_data['page_title'] = '走四方旅游';
		
		$this->_data['head_charset'] = $this->_language['charset'];
		$this->_data['customer_id'] = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '';
		$this->_data['login_href'] = $this->_url->create('',array('pagename'=>'login.php','ret'=>$this->_url->create('',$_GET)),array('is_self'=>'usitrip','is_ssl'=>true));
		$this->_data['js_login'] = $this->_url->create('',array('pagename'=>'login.php'),array('is_self'=>'usitrip','is_ssl'=>true));
		$this->_data['logout_href'] = $this->_url->create('',array('pagename'=>'logoff.php','ret'=>$this->_url->create('',array('module'=>'index'))),array('is_self'=>'usitrip'));
		$this->_data['js_logout'] = $this->_url->create('',array('pagename'=>'logoff.php'),array('is_self'=>'usitrip'));
		$this->_data['reg_href'] = $this->_url->create('',array('pagename'=>'create_account.php'),array('is_self'=>'usitrip','is_ssl'=>true));
		$this->_data['user_account'] = $this->_url->create('',array('pagename'=>'account.php'),array('is_self'=>'usitrip','is_ssl'=>true));
		$this->_data['my_orders'] = $this->_url->create('',array('pagename'=>'account_history.php'),array('is_self'=>'usitrip','is_ssl'=>true));
		$this->_data['my_favorites'] = $this->_url->create('',array('pagename'=>'my_favorites.php'),array('is_self'=>'usitrip','is_ssl'=>true));
		$this->_data['help'] = $this->_url->create('',array('pagename' => 'faq_question.php'),array('is_self'=>'usitrip'));
		$this->_data['top_search_action'] = $this->_url->create('',array('pagename'=>'advanced_search_result.php'),array('is_self'=>'usitrip'));
		$this->_data['top_search_ajax_url'] = $this->_url->create('',array('module'=>'usitrip','action'=>'attractions'),array('is_ajax'=>true));
		$this->_data['username'] = isset($_SESSION['customer_first_name']) ? $_SESSION['customer_first_name'] : '';
		
		//处理页脚
			// 新手入门
		$foot = array();
		// 订购流程
		$foot['order_process'] = $this->_url->create('',array('pagename'=>'order_process.php'),array('is_self'=>'usitrip'));
		// 常见问题
		$foot['faq_question'] = $this->_url->create('',array('pagename'=>'faq_question.php'),array('is_self'=>'usitrip'));
		// 支付方式
		$foot['payment'] = $this->_url->create('',array('pagename'=>'payment.php'),array('is_self'=>'usitrip'));
		// 订购协议
		$foot['order_agreement'] = $this->_url->create('', array('pagename'=>'order_agreement.php'), array('is_self'=>'usitrip'));
		// 签证相关
		$foot['visa_related'] = $this->_url->create('', array('pagename'=>'visa_related.php'), array('is_self'=>'usitrip'));
		// 结伴同游流程
		$foot['companions_process'] = $this->_url->create('', array('pagename' => 'companions_process.php'), array('is_self' => 'usitrip'));
		// 积分豪礼
		$foot['points'] = $this->_url->create('', array('pagename' => 'points.php'), array('is_self' => 'usitrip'));
		
		//旅美须知
		$foot['tour_america_need'] =  $this->_url->create('', array('pagename' => 'tour_america_need.php'), array('is_self' => 'usitrip'));
		
		//会员积分
		$foot['faq_points'] = $this->_url->create('', array('pagename' => 'faq_points.php'), array('is_self' => 'usitrip'));
		
		// 签证相关
		$foot['visa_related'] = $this->_url->create('', array('pagename' => 'visa_related.php'), array('is_self' => 'usitrip'));
		$this->_data['foot'] = $foot;
		$city = array();
		$city['toCity'] = array(
			array('name' => '洛杉矶'),
			array('name' => '旧金山'),
			array('name' => '拉斯维加斯'),
			array('name' => '纽约'),
			array('name' => '华盛顿'),
			array('name' => '波士顿'),
			array('name' => '温哥华')
		);
		foreach ($city['toCity'] as $key => $val) {
			$val['href'] = $this->_url->create('',array('pagename'=>'advanced_search_result.php','fcw'=>rawurlencode(iconv('gb2312',$this->_language['charset'],$val['name']))),array('is_self' => 'usitrip'));
			$val['name'] = $val['name'] . '旅游';
			$city['toCity'][$key] = $val;
		}
		$city['jingDian'] = array(
			array('name' => '黄石公园'),
			array('name' => '大峡谷'),
			array('name' => '优胜美地'),
			array('name' => '主题公园'),
			array('name' => '尼亚加拉瀑布'),
			array('name' => '落基山'),
			array('name' => '夏威夷')
		);
		foreach ($city['jingDian'] as $key => $val) {
			$val['href'] = $this->_url->create('',array('pagename'=>'advanced_search_result.php','fcw'=>rawurlencode(iconv('gb2312',$this->_language['charset'],$val['name']))),array('is_self' => 'usitrip'));
			$val['name'] = $val['name'] . '旅游';
			$city['jingDian'][$key] = $val;
		}
		$this->_data['toCity'] = $city['toCity'];
		$this->_data['jingDian'] = $city['jingDian'];
		// 友情连接
		$this->_data['links'] = Attractions_Usitrip::getLinks();
		$this->_data['links_more'] = $this->_url->create('', array('pagename' => 'links.php'),array('is_self'=>'usitrip'));
		
		// 页脚导航
		$footer = array();
		$footer['index'] = $this->_url->create('', array('pagename'=>'index.php'),array('is_self'=>'usitrip'));
		$footer['about'] = $this->_url->create('', array('pagename'=>'about/about.html'),array('is_self'=>'usitrip'));
		$footer['about_us'] = $this->_url->create('', array('pagename' => 'about_us.html'), array('is_self' => 'usitrip'));
		$footer['contact_us'] = $this->_url->create('', array('pagename' => 'contact_us.php'), array('is_self' => 'usitrip'));		
		$footer['copyright'] = $this->_url->create('', array('pagename' => 'privacy-policy.php'), array('is_self' => 'usitrip'));
		$footer['links'] = $this->_url->create('',array('pagename' => 'links.php'), array('is_self' => 'usitrip'));
		$footer['faq_question'] = $this->_url->create('', array('pagename' => 'faq_question.php'), array('is_self' => 'usitrip'));
		$footer['sitemap'] = $this->_url->create('',array('pagename'=>'sitemap.php'),array('is_self'=>'usitrip'));
		$footer['affiliate'] = $this->_url->create('',array('pagename' => 'affiliate.php'),array('is_self'=>'usitrip'));
		$this->_data['footer'] = $footer;
		
		$this->_data['a_jia_href'] = 'http://www.bbb.org/baton-rouge/business-reviews/travel-agencies-and-bureaus/unitedstars-international-ltd-in-baton-rouge-la-90012303';
		
		// 微博的JS代码
		$this->_data['other_js'] = "(function(){
			//-- load ga script
			var s = document.createElement('script');
			var _bdhmProtocol = (('https:' == document.location.protocol) ? ' https://' : ' http://');
			s.src = _bdhmProtocol + 'tjs.sjs.sinajs.cn/open/api/js/wb.js';
			var head = document.getElementsByTagName('head');
			if(head&&head[0]) { head[0].appendChild(s); }
		})();</script>";
	}

	/**
	 * 赋值SMARTY变量，并处理后输出HTML
	 * @author lwkai 2013-1-4 下午5:46:31
	 */
	public function after() {
		//写smarty模板变量，必须在->display之前引用
		if(is_object($this->_smarty)){
	  		foreach ($this->_data as $key => $val) {
				$this->_smarty->assign($key, $val);
			}
			if ($this->_template_file) {
				//$this->_smarty->display($this->_template_file);
				$html = $this->_smarty->fetch($this->_template_file);
				header('Content-Type:text/html;charset=' . $this->_language['charset']);
				echo Convert::db_to_html($html,$this->_language['charset']);
			}
		}
	}
	
	/**
	 * 析构对象 即释放的时候
	 * @author lwkai 2013-1-4 下午5:46:31
	 */
	public function __destruct(){
		$this->after();
	}
}
?>
