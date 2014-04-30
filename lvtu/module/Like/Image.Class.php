<?php
/**
 * 对游记中的图片进行喜欢操作
 * @package Like
 * @author lwkai by 2013-04-01
 */
class Like_Image extends Like_Abstract {
	
	/**
	 * 本类需要操作的表
	 */
	protected $_table = 'travel_image';
	
	/**
	 * 查询是否已经标记的查询标记符
	 */
	protected $_target = 1;
	
	/**
	 * 调用父类的构造函数
	 */
	public function __constrcut() {
		parent::__construct();
	}
	
	/**
	 * 喜欢数增加1，返回被改变的记录数
	 * @param int $id 要添加喜欢的图片记录ID
	 * @return number
	 */
	public function addLike($id, $userid) {
		$id = intval($id);
		$rtn = 0;
		if ($id > 0) {
			$tarval_id = $this->getTravelId($id);
			$this->addHistory($tarval_id, $userid, $id);
			$num = $this->countLike($tarval_id, $id, $this->_target);
			$data = array('like_number' => $num);
			$rtn = $this->_db->update($this->_table, $data, 'image_id="' . $id . '"');
			$travel = Like_Factory::getLike('Travels');
		}
		return $rtn;
	}
	
	/**
	 * 喜欢数减一,返回被改变的记录数
	 * @param int $id 要减少喜欢数的ID
	 * @param int $userid 做传记的用户
	 * @return number
	 */
	public function delLike($id, $userid) {
		$id = intval($id);
		$num = (intval($num) > 0 ? intval($num) : 1);
		$rtn = 0;
		if ($id > 0) {
			$tarval_id = $this->getTravelId($id);
			$this->delHistory($tarval_id, $userid, $id);
			$num = $this->countLike($tarval_id, $id, $this->_target);
			$data = array('like_number' => $num);
			$rtn = $this->_db->update($this->_table, $data, 'image_id="' . $id . '"');
		}
		return $rtn;
	}
	
	/**
	 * 根据图片ID 取得游记ID
	 * @param int $id 图片ID
	 * @return int
	 */
	public function getTravelId($id) {
		$travel_id = $this->_db->query("select travel_notes_id from " . $this->_table . " where image_id='" . $id . "'")->getOne();
		return $travel_id['travel_notes_id'];
	}
}
?>