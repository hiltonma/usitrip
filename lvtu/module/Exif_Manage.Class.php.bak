<?php
/**
 * 图片EXIF信息处理类
 * @author lwkai 2013-4-9 下午1:30:22
 *
 */
class Exif_Manage {
	
	/**
	 * 数据库操作对象
	 * @var Db_Mysql
	 * @author lwkai 2013-4-9 下午1:26:03
	 */
	private $_db = null;
	
	/**
	 * Exif 信息保存表
	 * @var string
	 * @author lwkai 2013-4-11 上午9:22:22
	 */
	private $_table = 'travel_exif';
	
	public function __construct() {
		$this->_db = Db::get_db();
	}
	
	/**
	 * 删除EXIF信息
	 * @param int $id 图片ID
	 * @return number;
	 * @author lwkai 2013-4-9 下午1:29:29
	 */
	public function del($id) {
		$rs = $this->_db->delete($this->_table, "image_id = '" . intval($id) . "'");
		return $rs;
	}
	
	/**
	 * 添加一个图片拍摄信息
	 * @param array $data 需要添加的键值数组
	 * @author lwkai 2013-4-11 上午9:19:42
	 */
	public function add($data) {
		$rs = $this->_db->insert($this->_table, $data);
		return $rs;
	}
	
	/**
	 * 根据图片ID取得对应的EXIF信息
	 * @param int $image_id 图片ID
	 * @return array
	 * @author lwkai 2013-4-16 上午10:30:47
	 */
	public function get($image_id) {
		$rtn = $this->_db->query("select * from " . $this->_table . " where image_id = '" . intval($image_id) . "'")->getOne();
		return $rtn;
	}
}