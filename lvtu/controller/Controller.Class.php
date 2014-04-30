<?php
/**
 * 总控制器
 * @author lwkai
 * @date 2012-11-20 下午4:27:21
 * @formatter:off
 * @link <1275124829@163.com>lwkai
 */
class Controller {
	
    private $module_name = 'index';
    private $action_name = 'index';
    private function __construct() {
    	$this->parse_url();
        $this->_session_start();
		$this->module_name = empty($_GET['module']) ? $this->module_name : (string) Convert::special_chars_html($_GET['module']);
        $this->action_name = empty($_GET['action']) ? $this->action_name : (string) Convert::special_chars_html($_GET['action']);
    }


    /**
     * 显示页面
     */
    public function execute() {
    	$str_tmp = $this->module_name . '_Con';
    	if (class_exists($str_tmp)) {
    		$class = new $str_tmp($this->module_name);
    		$function = $this->action_name . '_action';
    		if (method_exists($class, $function)) {
    			$class->$function();
    		} else {
    			My_Exception::mythrow('404', '您请求的页面不存在！');
    		}
    	} else {
    		My_Exception::mythrow('404', '您请求的页面不存在！');
    	}
    }

    /**
     * 实例化一个 controller 的句柄
     */
    public static function handle() {
        return new self();
    }

    /**
     * 打开session
     */
    protected function _session_start() {
    	session_save_path(SESSION_WRITE_DIRECTORY);
    	//SESSION 记录在数据库
        if(STORE_SESSIONS=='mysql'){
        	session_set_save_handler(array('Session', 'open'),
                         array('Session', 'close'),
                         array('Session', 'read'),
                         array('Session', 'write'),
                         array('Session', 'destroy'),
                         array('Session', 'gc')
                         );
    	}
        // 这一句不能少 不然 取不到SESSION by lwkai add 13-03-4
        if (isset($_COOKIE['osCsid'])) { // 如果COOKIE存在，则读取这个值
        	session_id($_COOKIE['osCsid']); //如果站点不是在二级目录里面，则可能需要得到这个SESSIONID值
        } elseif (isset($_GET['oscsid'])) {
        	session_id($_GET['oscsid']);
        	unset($_GET['oscsid']);
        }
       	session_start();
    }
    
    /**
     * 解析URL的参数
     */
    private function parse_url() {
    	if (isset($_SERVER['SERVER_SOFTWARE']) && strtolower(substr($_SERVER['SERVER_SOFTWARE'],0,6)) == 'apache') {
    		$domain = $_SERVER['SERVER_NAME']; //apache 用此
    	} elseif (isset($_SERVER['SERVER_SOFTWARE']) && strtolower(substr($_SERVER['SERVER_SOFTWARE'],0,5)) == 'nginx') {
    		$domain = $_SERVER['HTTP_HOST']; //nginx 用此	
    	}

    	if (strpos(TW_HTTP_SERVER, $domain) !== false) {
    		$_GET['language'] = 'tw';
    	} else if (strpos(EN_HTTP_SERVER, $domain) !== false) {
    		$_GET['language'] = 'en';
    	} else {
    		$_GET['language'] = 'zh';
    	}
    	
    	$param = $_SERVER['REQUEST_URI'];
    	$param = substr($param,strlen(DIR_WS_ROOT));
    	$url = new Url(Db::get_db(), $this->module_name);
    	$url->parse($param);
    }
}