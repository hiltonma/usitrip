<?php
/**
 * 游记喜欢操作类
 * @package Like
 * @author lwkai
 */
class Like_Travels extends Like_Abstract {
	
	/**
	 * 本类操作的表
	 * @var string
	 */
	protected $_table = 'travel_notes';
	
	/**
	 * 操作标记类型
	 * @var int
	 */
	protected $_target = 3;
	
	/**
	 * 调用父类构造函数
	 */
	public function __construct() {
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
			$this->addHistory($id, $userid);
			$num = $this->countLike($id);
			$data = array('like_number' => $num);
			$rtn = $this->_db->update($this->_table, $data, 'travel_notes_id="' . $id . '"');
		}
		return $rtn;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Like_Abstract::getTravelId()
	 */
	public function getTravelId($id) {
		return $id;
	}
	
	/**
	 * 喜欢数减一,返回被改变的记录数
	 * @param int $id 要减少喜欢数的ID
	 * @param int $num 减少多少数[默认1]
	 * @return number
	 */
	public function delLike($id, $userid) {
		$id = intval($id);
		$num = (intval($num) > 0 ? intval($num) : 1);
		$rtn = 0;
		if ($id > 0) {
			$this->delHistory($id, $userid);
			$num = $this->countLike($id);
			$data = array('like_number' => $num);
			$rtn = $this->_db->update($this->_table, $data, 'travel_notes_id="' . $id . '"');
		}
		return $rtn;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Like_Abstract::isLike()
	 */
	public function isLike($id, $user_id) {
		$id = intval($id);
		$user_id = intval($user_id);
		$rtn = false;
		if ($id > 0 && $user_id > 0) {
			$sql = "select add_id from " . $this->_history_table . " where add_id ='0' and travel_id='" . intval($id) . "' and user_id='" . intval($user_id) . "' and target='" . $this->_target . "'";
			$temp = $this->_db->query($sql)->getOne();
			if ($temp) {
				$rtn = true;
			}
		}
		return $rtn;
	}
}
