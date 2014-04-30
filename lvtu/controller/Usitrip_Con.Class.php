<?php
/**
 * 主站的数据读取类
 * @author lwkai 2013-3-22 下午4:01:06
 *
 */
class Usitrip_Con extends Abstract_Default {
	
	public function __construct($module) {
		parent::__construct($module);

	}
	
	/**
	 * 获取旅游景点
	 * 
	 * @author lwkai 2013-3-22 下午4:00:47
	 */
	public function attractions_action(){
		$key = isset($_POST['val']) ? $_POST['val'] : '';
		$key = Convert::iconv('utf-8', 'gb2312', $key);
		$arr = Attractions_Usitrip::getAttractionsByWord($key);
		echo json_encode(Convert::iconv('gb2312', 'utf-8', $arr));
	}
	
	
	public function __destruct(){
		parent::__destruct();
	}
}
