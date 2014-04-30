<?php
/**
 * 异常处理类
 * @author lwkai 2013-4-28 上午11:58:45
 *
 */
class Error_Con extends Abstract_Default {
	public function __construct($module) {
		parent::__construct($module);
	}
	
	public function index_action(){
		$this->_data['errormessage'] = $_SESSION['err_message'];
		$this->_template_file = 'error.html';
	}
	
	public function fzf_action(){
		$this->_data['errormessage'] = $_SESSION['err_404'];
		$this->_template_file = '404.html';
	}
}