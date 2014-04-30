<?php
/**
 * 后台管理
 * @author lwkai 2013-5-3 下午5:21:00
 *
 */
class Admin_Con extends Abstract_Admin {
	
	public function __construct($module) {
		parent::__construct($module);
		//登录检测
		if (!isset($_SESSION['login_id'],$_SESSION['login_groups_id'],$_SESSION['login_first_name'],$_SESSION['login_firstname'])){
			header('Location:' . $this->_url->create('',array('module'=>'index','action'=>'index')));
			//exit;
		}
	}
	
	/**
	 * 框架页
	 * 
	 * @author lwkai 2013-5-3 下午5:21:22
	 */
	public function index_action(){
		$this->_data['top'] = $this->_url->create('',array('action'=>'top'));
		$this->_data['left'] = $this->_url->create('',array('action'=>'left'));
		$this->_data['right'] = $this->_url->create('',array('action'=>'right'));
		$this->_template_file = 'index.html';
	}
	
	/**
	 * 框架顶部页
	 * 
	 * @author lwkai 2013-5-3 下午5:21:34
	 */
	public function top_action(){
		$this->_data['first_name'] = $_SESSION['login_firstname'];
		$this->_data['logout'] = $this->_url->create('',array('module'=>'index','action'=>'logout'));
		$this->_template_file = 'admin_top.html';
	}
	
	/**
	 * 左边菜单
	 * 
	 * @author lwkai 2013-5-3 下午5:21:46
	 */
	public function left_action(){
		$this->_data['base_config'] = $this->_url->create('',array('action'=>'right'));
		$this->_data['city_manage'] = $this->_url->create('',array('action'=>'city'));
		$this->_data['travel_manage'] = $this->_url->create('',array('action'=>'travel'));
		$this->_template_file = 'left.html';
	}
	
	/**
	 * 右边默认页
	 * 
	 * @author lwkai 2013-5-3 下午5:21:56
	 */
	public function right_action(){
		
		$sql = "select * from configuration";
		$rs = $this->_db->query($sql)->getAll();
		foreach ($rs as $key => $val) {
			switch ($val['configuration_key']) {
				case 'SYS_THEME':
					$sql = "select * from sys_theme order by  sys_theme_id desc";
					$theme_rs = $this->_db->query($sql)->getAll();
					foreach($theme_rs as $k => $v) {
						if ($val['configuration_value'] == $v['sys_theme_id']) {
							$v['selected'] = true;
						}
						$theme_rs[$k] = $v;
					}
					$val['theme_list'] = $theme_rs;
					break;
				
			}
			$rs[$key] = $val;
		}
		$this->_data['config_list'] = $rs;
		if(isset($_SESSION['update_status'])) {
			$this->_data['update_msg'] = $_SESSION['update_status'];
			unset($_SESSION['update_status']);
		}
		$this->_data['right_form_action'] = $this->_url->create('',array('action'=>'saveConfig'),array('is_noseo'=>true));
		$this->_template_file = 'right.html';
	}
	
	/**
	 * 保存网站配置
	 * 
	 * @author lwkai 2013-5-6 上午10:57:49
	 */
	public function saveconfig_action(){
		if (isset($_POST['theme']) && is_array($_POST['theme'])) {
			foreach($_POST['theme'] as $key => $val) {
				$rtn = $this->_db->update('configuration', array('configuration_value'=>intval($val)),"configuration_id='" . intval($key) . "'");
			}
		}
		if (isset($_POST['config']) && is_array($_POST['config'])) {
			foreach ($_POST['config'] as $key => $val) {
				$this->_db->update('configuration', array('configuration_value'=>$val),"configuration_id='" . intval($key) . "'");
			}
		}
		$_SESSION['update_status'] = array('msg'=>'更新成功','status'=>'success');
		header('Location:' . $_SERVER['HTTP_REFERER']);
	}
	
	/**
	 * 置顶的景点管理
	 * 
	 * @author lwkai 2013-5-6 上午10:57:06
	 */
	public function city_action(){
		$sql = "select * from travel_city_top order by sort_id desc";
		$paging = new Paging($this->_db, $this->_url, $sql, 10);
		$rs = $this->_db->query($paging->getSql())->getAll();
		foreach ($rs as $key => $val) {
			$rtn = Attractions_Usitrip::getAttractionsById($val['city_id']);
			//var_dump($rtn);
			$val['city_name'] = $rtn[0]['city'];
			$rs[$key] = $val;
		}
		$this->_data['form_action'] = $this->_url->create('',array('action'=>'city_update'),array('is_noseo'=>true));
		//var_dump($paging->getPageLinksToArray(5));
		$this->_data['page'] = $paging->getPageLinksToArray(5);
		//var_dump($this->_data['page']);
		$this->_data['city_list'] = $rs;
		$this->_template_file = 'city.html';
	}
	
	/**
	 * 修改景点排序
	 * 
	 * @author lwkai 2013-5-6 下午1:59:05
	 */
	public function city_update_action(){
		if (isset($_POST['order']) && is_array($_POST['order'])) {
			foreach ($_POST['order'] as $key => $val) {
				$this->_db->update('travel_city_top', array('sort_id'=>intval($val)),"id='" . intval($key) . "'");
			}
		}
		if (isset($_POST['del']) && is_array($_POST	['del'])) {
			foreach ($_POST['del'] as $key => $val) {
				$this->_db->delete('travel_city_top', "id='" . intval($val) . "'");
			}
		}
		header('Location:' . $_SERVER['HTTP_REFERER']);
	}
	
	/**
	 * 游记管理
	 * 
	 * @author lwkai 2013-5-6 上午10:57:24
	 */
	public function travel_action(){
		$sql = "select * from travel_notes where 1=1";
		if (isset($_GET['customers_id']) && intval($_GET['customers_id'])) {
			$sql .= " and customers_id='" . intval($_GET['customers_id']) . "'";
		}
		if (isset($_GET['products_id']) && intval($_GET['products_id'])) {
			$sql .= " and products_id='" . intval($_GET['products_id']) . "'";
		}
		if (isset($_GET['destination']) && $_GET['destination'] != '') {
			$sql .= " and destination like '%" . Convert::db_input($_GET['destination']) . "%'";
		}
		$sql .=  " order by is_top desc,verify asc,add_time desc";
		$paging = new Paging($this->_db, $this->_url, $sql, 10);
		$rs = $this->_db->query($paging->getSql())->getAll();
		$image = new Image();
		foreach($rs as $key => $val) {
			$val['customers_href'] = $this->_url->create('',array('action'=>'travel','customers_id'=>$val['customers_id']));
			$val['customers_name'] = Attractions_Usitrip::getUserName($val['customers_id']);
			$val['line_href'] = $this->_url->create('',array('action'=>'travel','products_id'=>$val['products_id']));
			$val['end_city_href'] = $this->_url->create('',array('action'=>'travel','destination'=>$val['destination']));
			$val['travel_name'] = Attractions_Usitrip::getProductsName($val['products_id']);
			$val['href'] = str_replace('admin/','',$this->_url->create('',array('module'=>'album','action'=>'show','albumid'=>$val['travel_notes_id'])));
			$val['day_num'] = $image->getCountDay($val['travel_notes_id']);
			$address = Attractions_Usitrip::getAttractionsById(str_replace(',,',',',trim(str_replace('!', ',', $val['destination']),',')));
			foreach($address as $key2 => $val2) {
				//$des .= $val2['city'] . ',';
				$val2['href'] = $this->_url->create('',array('action'=>'travel','destination'=>$val2['city_id']));
				$val2['is_top'] = false;
				$check = $this->_db->query("select id from travel_city_top where city_id='" . $val2['city_id'] . "' and sort_id>0")->getOne();
				if ($check) {
					$val2['is_top'] = true;
				}
				$address[$key2] = $val2;
			}
			$val['city_name'] = $address;//trim($des,',');
			$rs[$key] = $val;
		}
		$this->_data['travel_list'] = $rs;
		$this->_data['form_action'] = $this->_url->create('',array('action'=>'travel_update'),array('is_noseo'=>true));
		$this->_data['page'] = $paging->getPageLinksToArray(5);
		$this->_data['add_city_top'] = $this->_url->create('',array('action'=>'add_city_top'),array('is_noseo'=>true));
		$this->_template_file = 'travel.html';
	}
	
	/**
	 * 修改游记状态，审核置顶等
	 * 
	 * @author lwkai 2013-5-6 下午4:50:14
	 */
	public function travel_update_action(){
		if (isset($_POST['verify']) && is_array($_POST['verify'])) {
			foreach ($_POST['verify'] as $key => $val) {
				$this->_db->update('travel_notes', array('verify'=>intval($val),'edit_time'=>date('Y-m-d H:i:s')),"travel_notes_id='" . intval($key) . "'");
			}
		}
		
		if (isset($_POST['is_top']) && is_array($_POST['is_top'])) {
			foreach ($_POST['is_top'] as $key => $val) {
				$this->_db->update('travel_notes', array('is_top'=>intval($val),'edit_time'=>date('Y-m-d H:i:s')),"travel_notes_id='" . intval($key) . "'");
			}
		}
		header('Location:' . $_SERVER['HTTP_REFERER']);
	}
	
	/**
	 * ajax提交的置顶目的地的请求。
	 * 
	 * @author lwkai 2013-5-8 上午10:38:27
	 */
	public function add_city_top_action(){
		$rtn = 0;
		if(isset($_POST['id']) && intval($_POST['id'])) {
			$id = intval($_POST['id']);
			$rs = $this->_db->query("select id,sort_id from travel_city_top where city_id='" . $id . "'")->getOne();
			if ($rs) {
				if (!intval($rs['sort_id'])) {
					$this->_db->update('travel_city_top', array('sort_id'=>'1'),"id='" . $rs['id'] . "'");
				}
			} else {
				$this->_db->insert('travel_city_top', array('city_id'=>$id,'sort_id'=>1));
			}
			$rtn = 1;
		}
		echo '{"status":' . $rtn . '}';
	}
	
	public function __destruct() {
		parent::__destruct();
	}
}