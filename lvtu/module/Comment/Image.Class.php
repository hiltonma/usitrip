<?php
/**
 * 图片评论操作类
 * @package Comment
 * @author lwkai by 2013-04-02
 */
class Comment_Image extends Comment_Abstract {
	
	/**
	 * 图片评论标记
	 * @var int
	 */
	protected $_target = 1;
	
	/**
	 * 图片表
	 * @var string
	 */
	protected $_table = 'travel_image';
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 根据传过来的图片或者心情ID，取得游记ID
	 * @param int $id 被评论的心情或者图片ID
	 * @return 游记ID
	 */
	protected function getTravelsId($id) {
		$sql = "select travel_notes_id from " . $this->_table . " where image_id='" . intval($id) . "'";
		$rs = $this->_db->query($sql)->getOne();
		return $rs['travel_notes_id'];
	}
	
	/**
	 * 对图片表的评论总数加1
	 * @param int $id 要增加１个评论的记录id
	 * @return int 受影响的记录总数
	 */
	protected function addOne($id) {
		$num = $this->getCommentsNum($id);
		$data = array('replay_number' => $num);
		$rs = $this->_db->update($this->_table, $data,'image_id="' . intval($id) . '"');
		return $rs;
	}
}

?>