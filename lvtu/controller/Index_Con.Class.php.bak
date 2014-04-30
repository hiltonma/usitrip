<?php
/**
 * 首页控制器
 * @author lwkai
 * @date 2012-11-12 下午6:03:14
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Index_Con extends Abstract_Default {
    public function __construct($module) {
        parent::__construct($module);
    }
    
    /**
     * 首页默认显示的页面
     * 
     * @author lwkai 2013-1-29 下午6:09:03
     */
    public function index_action() {
    	$this->_data['current_js_file'] = 'index.js';
    	$this->_data['current_css_file'] = 'index-201301041157.css';
    	$this->_data['head_title'] = 'usitrip走四方-华人首选出国旅游网_专业美国华人旅行社_旅游攻略景点线路_签证移民留学游学';
    	$travel = new Travel();
    	$_list = $travel->getAttractionslist();
    	$list = array(array());
    	foreach($_list as $key => $val) {
    		$tmp = array(
    			'name' => $val,
    			'href' => $this->_url->create('',array('module'=>'index','action'=>'index','attraction'=>$key))	
    		);
    		$list[] = $tmp;
    	}
    	$this->_data['list'] = $list;
    	$pagesize = 18;
    	$where = '1=1';
    	if (isset($_GET['attraction']) && intval($_GET['attraction'])) {
    		$where .= ' and destination like "%!' . intval($_GET['attraction']) . '!%"';
    	}
    	if (isset($_GET['productid']) && intval($_GET['productid'])) {
    		$where .= " and products_id='" . intval($_GET['productid']) . "'";
    	}
		$rs = $travel->getList($pagesize,$where);
		$image = new Image();
		$wh = $image->getSize('face');
		foreach($rs as $key => $val) {
			$val['href'] = $this->_url->create('',array('module'=>'album','action'=>'show','albumid'=>$val['travel_notes_id']));
			$val['add_time'] = date('Y-m-d',strtotime($val['add_time']));
			$val['products_name'] = Attractions_Usitrip::getProductsName($val['products_id']);
			$img_wh = Picture_Zoom::zoomImage(327, 239, DIR_FS_ROOT . 'upimg' . DS . $wh['width'] . 'x' . $wh['height'] . $val['cover_image']);
			$val['image_width'] = $img_wh['new_w'];
			$val['image_height'] = $img_wh['new_h'];
			$val['cover_image'] = DIR_WS_ROOT . 'upimg/' . $wh['width'] . 'x' . $wh['height'] . $val['cover_image'];
			$val['customer_name'] = Attractions_Usitrip::getUserName($val['customers_id']); 
			$val['day_num'] = $image->getCountDay($val['travel_notes_id']);
			$rs[$key] = $val;
		}
		$this->_data['travel_list'] = $rs;
		$this->_data['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->_data['totalAlbumNum'] = $travel->getRowsTotal();
		$this->_data['pagesize'] = $pagesize;
    	$this->_data['test_product_url'] = $this->_url->create('',array('product_id'=>1,'module'=>'product_info'));
    	
    	$this->_data['test_page_2'] = $this->_url->create('',array('module'=>'product_list','cpath'=>25,'tabs'=>'topic','page'=>2));
    	
    	
    	
    	$this->_template_file = 	'index.html';
    }
    
    public function __destruct(){
    	parent::__destruct();
    }
}
?>