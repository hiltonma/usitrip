<?php

abstract class Abstract_Admin {
	
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
	 * @var object
	 */
	protected $_smarty = null;
	
	/**
	 * 数据库操作对象 db_mysql的实例
	 * @var db_mysql
	 */
	protected $_db = null;
	
	/**
	 * 构造URL的对名胜
	 * @var url
	 * @author lwkai 2012-11-20 上午10:43:05
	 */
	protected $_url = null;
	
	/**
	 * 语言版本，默认是中文简体即array('name'=>'cn','code'=>'gb2312','id'=>2);
	 * 如果是繁体则 array('name'=>'tw','code'=>'big5','id'=>2);
	 * 如果是英文则 array('name'=>'en','code'=>'utf-8','id'=>1);
	 * @var array
	 * @author lwkai 2012-11-22 16:39
	 */
	protected $_language = array('name'=>'cn','code'=>'gb2312','id'=>2);
	
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
    
    protected function __construct($module_name = 'index') {
    	$this->_module = $module_name;
    	$this->_action = empty($_GET['action']) ? 'index' : (string) $_GET['action'];
    	$this->_db = Db::get_db();//Db_Pdo::getConn();
    	$language = new Language($this->_db, isset($_GET['language']) ? $_GET['language'] : 'zh');
    	$this->_language = $language->getCurrentLanguage();
    	$language_file = DIR_FS_ROOT . 'public' . DS . 'languages' . DS . $this->_language['directory'] . '.php';
    	if (file_exists($language_file)) {
    		include $language_file;
    	}
    	$this->_url = new Url($this->_db,$this->_module,$this->_language['name']);
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
    
    public function before() {
    	// 载入系统中配置的一系列常量
    	$rs = $this->_db->query("select configuration_key,configuration_value from configuration")->getAll();
    	foreach ($rs as $key => $val) {
    		define($val['configuration_key'],$val['configuration_value']);
    	}
    	define('DIR_WS_JS', DIR_WS_ROOT . '../views/admin/js/');
    	define('DIR_WS_IMG', DIR_WS_ROOT . '../views/admin/image/');
    	define('DIR_WS_CSS', DIR_WS_ROOT . '../views/admin/css/');
    		
    	/**
    	 * 模板所有目录
    	 * @var string
    	 * @author lwkai 2012-11-20 下午3:34:36
    	*/
    	define('DIR_FS_TPL_ROOT', DIR_FS_ROOT . 'views' . DS . 'admin' . DS);
    		
    	/**
    	 * 模板编译后的文件存放目录
    	 * @var string
    	 * @author lwkai 2012-11-20 下午3:37:03
    	*/
    	define('DIR_FS_TPL_CPE_ROOT', DIR_FS_ROOT . 'runtime' . DS . 'templates_c' . DS . 'admin');
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
    	define('DIR_FS_TPL_CACHE',DIR_FS_ROOT . 'runtime' . DS . 'cache' . DS . 'admin');
    	if (!is_dir(DIR_FS_TPL_CACHE)) {
    		if (!mkdir(DIR_FS_TPL_CACHE)) {
    			throw new Exception('"runtime' . DS . 'cache"' . ' apache没写入权限！请设置权限为0755.');
    		}
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
    	
    	$this->_data['head_base_href'] = $this->_url->getBaseHref($this->_language['code']);
    	$this->_data['head_charset'] = $this->_language['charset'];
    }

    public function after() {
    	//写smarty模板变量，必须在->display之前引用
    	if(is_object($this->_smarty)){
      		foreach ($this->_data as $key => $val) {
      			//echo '$this->_smarty->assign(\'' . $key . '\',\'' . print_r($val,true) . '\')' . '<br/>';
    			$this->_smarty->assign($key, $val);
    		}
    		//exit;
    		if ($this->_template_file) {
    			//$this->_smarty->display($this->_template_file);
    			$html = $this->_smarty->fetch($this->_template_file);
    			header('Content-Type:text/html;charset=' . $this->_language['charset']);
    			header("Cache-Control:no-cache,must-revalidate,no-store");   //这个no-store加了之后，Firefox下有效
    			header("Pragma:no-cache");
    			header("Expires:Wed, 23 Aug 2006 12:40:27 UTC");
    			echo Convert::db_to_html($html,$this->_language['charset']);
    		}
    	}
    }

    public function __destruct(){
    	$this->after();
    }
}
?>
