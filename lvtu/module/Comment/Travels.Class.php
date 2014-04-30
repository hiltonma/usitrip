<?php
/**
 * 游记喜欢操作类
 * @package Comment
 */
class Comment_Travels extends Comment_Abstract {
	
	/**
	 * 图片表
	 * @var string
	 */
	protected $_table = 'travel_notes';
	
	public function __construct() {
		parent::__construct();
	}
	
	/* (non-PHPdoc)
	 * @see Comment_Abstract::getTravelsId()
	 */
	protected function getTravelsId($id) {
		return $id;
	}

	/* (non-PHPdoc)
	 * @see Comment_Abstract::addOne()
	 */
	protected function addOne($id) {
		$num = $this->getCommentsNum($id);
		$data = array('replay_number' => $num);
		$rs = $this->_db->update($this->_table, $data,'travel_notes_id="' . intval($id) . '"');
		return $rs;
	}

	/**
	 * 取得评论总条数
	 * @return number
	 */
	public function getCommentsNum($id) {
		$num = 0;
		$rs = $this->_db->query("select image_id from travel_image where travel_notes_id='" . intval($id) . "'")->getAll();
		$image = Comment_Factory::getComment('Image');
		foreach($rs as $key => $val) {
			$num += $image->getCommentsNum($val['image_id']);
		}
		$sql = "select day_id from travel_day where travel_notes_id='" . intval($id) . "'";
		$rs = $this->_db->query($sql)->getAll();
		$mood = Comment_Factory::getComment('Mood');
		foreach($rs as $key=>$val) {
			$num += $mood->getCommentsNum($val['day_id']);
		}
		return $num;
	}
}