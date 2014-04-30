<?php
/**
 * 心情评论操作类
 * @package Comment
 * @author lwkai by 2013-04-02
 */
class Comment_Mood extends Comment_Abstract {
	
	/**
	 * 心情评论的标记ID
	 * @var int
	 */
	protected $_target = 2;
	
	/**
	 * 心情表，即每天的说明表
	 * @var string
	 */
	protected $_table = 'travel_day';
	
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 根据传过来的图片或者心情ID，取得游记ID
	 * @param int $id 被评论的心情或者图片ID
	 * @return 游记ID
	 */
	protected function getTravelsId($id) {
		$sql = "select travel_notes_id from " . $this->_table . " where day_id='" . intval($id) . "'";
		$rs = $this->_db->query($sql)->getOne();
		return $rs['travel_notes_id'];
	}
	
	/**
	 * 对当前心情表的评论总数加1
	 * @param int $id 需要增加评论总数的ID
	 * @return int 受影响的记录总数
	 */
	protected function addOne($id) {
		$num = $this->getCommentsNum($id);
		$data = array('replay_number' => $num);
		$rtn = $this->_db->update($this->_table, $data, 'day_id="' . intval($id) . '"');
		return $rtn;
	}
}

?>