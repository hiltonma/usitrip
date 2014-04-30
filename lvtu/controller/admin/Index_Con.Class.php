<?php 
/**
 * 首页控制器
 * @author lwkai
 * @date 2012-11-12 下午6:03:14
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Index_Con extends Abstract_Admin {
    public function __construct($module) {
        parent::__construct($module);
        
    }
    
    /**
     * 登录页面
     * 
     * @author lwkai 2013-5-3 下午1:29:33
     */
    public function index_action() {
    	$this->_data['form_action'] = $this->_url->create('',array('action'=>'login'),array('is_noseo'=>true));
    	if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])) {
    		$this->_data['err_msg'] = $_SESSION['error_msg'];
    		unset($_SESSION['error_msg']);
    	}
    	$this->_template_file = 	'login.html';
    }
    
    public function login_action(){
    	if (isset($_POST['username'],$_POST['password'])) {
    		$username = $_POST['username'];
    		$password = $_POST['password'];
    		$can_login_arr = $this->_db->query("select admin_mail from travel_admin where status=0")->getAll();
    		$find = false;
    		foreach ($can_login_arr as $key => $val) {
    			if (trim($username) == trim($val['admin_mail'])) {
    				$find = true;
    				break;
    			}
    		}
    		if ($find) {
    		$rtn = User_Usitrip::login($username, $password);
	    		if ($rtn) {
	    			header('Location:' . $this->_url->create('',array('module'=>'admin')));
	    		} else {
	    			$_SESSION['error_msg'] = '用户名或者密码错误！';
	    			header('Location:' . $this->_url->create('',array('action'=>'index')));
	    		}
    		} else {
    			$_SESSION['error_msg'] = '对不起！您未被授权使用该管理后台！';
    			header('Location:' . $this->_url->create('',array('action'=>'index')));
    		}
    	}
    }
    
    public function logout_action(){
    	unset($_SESSION['login_id'],$_SESSION['login_groups_id'],$_SESSION['login_first_name'],$_SESSION['login_firstname'],$_SESSION['lvtu_login_id']);
    	header('Location:' . $this->_url->create('',array('module'=>'index','action'=>'index')));
    }
    
    public function __destruct(){
    	parent::__destruct();
    }
}
?>