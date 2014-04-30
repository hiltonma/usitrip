<?php
/**
 * 心情喜欢操作类
 * @package Like
 * @author lwkai by 2013-04-02
 */
class Like_Mood extends Like_Abstract {
	
	protected $_table = 'travel_day';
	
	protected $_target = 2;
	/**
	 * 调用父类的构造函数
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
			$tarval_id = $this->getTravelId($id);
			//try {
				$this->addHistory($tarval_id, $userid, $id);
				$num = $this->countLike($tarval_id, $id, $this->_target);	
				$data = array('like_number' => $num);
				$rtn = $this->_db->update($this->_table, $data, 'day_id="' . $id . '"');
			//} catch (Exception $e) {
			//	$rtn = 618;
				
			//}
		}
		return $rtn;
	}
	
	/**
	 * 根据传过来的图片或者心情ID，取得游记ID
	 * @param int $id 被评论的心情或者图片ID
	 * @return 游记ID
	 */
	public function getTravelId($id) {
		$sql = "select travel_notes_id from " . $this->_table . " where day_id='" . intval($id) . "'";
		$rs = $this->_db->query($sql)->getOne();
		return $rs['travel_notes_id'];
	}
	
	/**
	 * 喜欢数减一,返回被改变的记录数
	 * @param int $id 要减少喜欢数的ID
	 * @return number
	 */
	public function delLike($id, $userid) {

		$id = intval($id);
		$rtn = 0;
		if ($id > 0) {
			$tarval_id = $this->getTravelId($id);

			//try {
				$this->delHistory($tarval_id, $userid, $id);
				//ddHistory($id, $tarval_id, $userid);
				
				$num = $this->countLike($tarval_id, $id, $this->_target);
				$data = array('like_number' => $num);
				$rtn = $this->_db->update($this->_table, $data, 'day_id="' . $id . '"');
			//} catch(Exception $e) {
			//	$rtn = 618;
				
			//}
		}
		return $rtn;
	}
}
