<?php
/**
 * 抽象类，供图片类与心情类公用
 * @author lwkai
 *
 */
abstract class Abstract_Manage {
	/**
	 * 数据库操作类
	 * @var Db_Mysql
	 */
	//private $_db = null;
	
	/**
	 * 本类需要操作的数据表
	 * @var string
	 */
	protected $_table = '';
	
	/**
	 * 当前对象类型标记 【图片为[Image]对应前台1 心情为[Mood]对应前台2 游记为[Travels]对应前吧3】
	 * @var string
	 */
	protected $_target = '';
	
	/**
	 * 栏位名称前缀
	 */
	protected $_field_prefix = '';
	
	/**
	 * 保存一些不希望重复NEW的对象
	 * @var unknown_type
	 * @author lwkai 2013-4-11 上午11:44:45
	 */
	protected $_obj = array();
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		//$this->_db = Db::get_db();
	}
	
	/**
	 * 获得数据库操作对象
	 * @return Db_Mysql
	 * @author lwkai 2013-4-11 上午10:06:16
	 */
	protected function db() {
		if (isset($this->_obj['db'])) {
			return $this->_obj['db'];
		} else {
			$this->_obj['db'] = Db::get_db();
			return $this->_obj['db'];
		}
	}
	
	/**
	 * 删除图片或者心情，并且删除相应的喜欢数与评论,返回删除操作受影响的记录数
	 * @param int $id 要删除的ID
	 * @return number
	 */
	public function del($id) {
		// 删除评论，
		$comment = Comment_Factory::getComment($this->_target);
		$like = Like_Factory::getLike($this->_target);
		$comment->delComment($id);
		$test = $like->delLike($id,0);
		$rs = $this->db()->delete($this->_table, $this->_field_prefix . '_id = "' . intval($id) . '"');
		return $rs;
	}

}
?>