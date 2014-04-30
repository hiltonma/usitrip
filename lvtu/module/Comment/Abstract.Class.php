<?php
/**
 * 评论抽象类
 * @package Comment
 * @author lwkai by 2013-04-02
 */
abstract class Comment_Abstract {
	
	/**
	 * 数据库操作类
	 * @var Db_Mysql
	 */
	protected $_db = null;
	
	/**
	 * 要操作的表，比如 图片表 心情表
	 * @var string
	 */
	protected $_table = '';
	 
	/**
	 * 评论表
	 * @var string
	 */
	protected $_comment_table = 'travel_comment';
	

	
	/**
	 * 评论区分标记 1为图片 2为心情 3为游记
	 */
	protected $_target = 0;
	
	/**
	 * 取评论的时候，一页多少条记录
	 */
	protected $_pagesize = 10;
	
	/**
	 * 初始化数据库操作类
	 */
	public function __construct() {
		$this->_db = Db::get_db();
	}
	
	/**
	 * 取得评论总条数
	 * @return number
	 */
	public function getCommentsNum($id) {
		$rs = $this->_db->query("select count(comment_id) as num from " . $this->_comment_table . " where target_id='" . $this->_target . "' and commented_id='" . intval($id) . "'")->getOne();
		return $rs['num'];
	}
	
	/**
	 * 取得留言内容
	 * @param int $page 取数据的第几页的页数
	 * @param int $id 要取得的评论是哪个对象上的，比如是图片，这个就是图片的ID
	 * @return array
	 */
	public function getComments($page, $id){
		$page = intval($page);
		$recordset_count = $this->getCommentsNum();
		$page_count = ceil($recordset_count / $this->_pagesize);
		if ($page > $page_count) {
			$page = $page_count;
		}
		if ($page < 1) {
			$page = 1;
		}
		$offset = ($this->_pagesize * ($page - 1));
		$sql="select * from " . $this->_comment_table . " where target_id='" . $this->_target . "' and commented_id='" . $id . "' order by create_time desc,comment_id desc limit " . $offset . "," . $this->_pagesize;
		$rs = $this->_db->query($sql)->getAll();
		return $rs;	
	}
	
	/**
	 * 保存评论到数据表,返回最新插入的这条记录的ID
	 * @param array $data 关联数组 array('content'=>'评论内容','commented_id'=>'评论对象ID','user_id'=>'评论人ID'[,'replay_to'=>'回复给某个用户(ID)','parent_id'=>被回复的评论的ID]);
	 * @return number
	 */
	public function saveComment($data) {
		$rtn = 0;
		if (isset($data['content'],$data['commented_id'],$data['user_id'])) {
			$data['create_time'] = date('Y-m-d H:i:s');
			$data['target_id'] = $this->_target;
			$rtn = $this->_db->insert($this->_comment_table, $data);
			$travles_id = $this->getTravelsId($data['commented_id']);
			$this->addOne($data['commented_id']);
			if ($travles_id > 0) {
				//$this->addTravelsOne($travles_id);
				$travels = Comment_Factory::getComment('Travels');
				$travels->addOne($travles_id);
			}
		}
		return $rtn;
	}
	
	/**
	 * 删除评论
	 * @param int $id 要删除的对象ID[如图像ID，评论ID]
	 * @return int 受影响的记录数
	 */
	public function delComment($id) {
		$rtn = 0;
		if (intval($id)) {
			$rtn = $this->_db->delete($this->_comment_table, "target_id='" . $this->_target . "' and commented_id='" . $id . "'");
			$travels_id = $this->getTravelsId($id);
			if ($travels_id > 0 && $rtn > 0) {
				//$this->delTravels($travels_id, $rtn);
				$travels = Comment_Factory::getComment('Travels');
				$travels->addOne($travels_id);
			}
		}
		
		return $rtn;
	}
	
	/**
	 * 回复评论
	 */
	public function replayComment($data) {
		$rtn = 0;
		if (isset($data['content'],$data['commented_id'],$data['user_id'],$data['replay_to'])) {
			$data['parent_id'] = $data['replay_to'];
			$user_rs = $this->_db->query("select user_id from " . $this->_comment_table . " where comment_id='" . $data['replay_to'] . "'")->getOne();
			$data['replay_to'] = $user_rs['user_id'];
			$rtn = $this->saveComment($data);
		}
		return $rtn;
	}
	
	/**
	 * 根据传过来的图片或者心情ID，取得游记ID
	 * @param int $id 被评论的心情或者图片ID
	 * @return 游记ID
	 */
	abstract protected function getTravelsId($id);
	
	/**
	 * 评论更新总数量
	 * @param int $id 要增加评论的记录ID
	 * @return int 受影响的记录条数
	 */
	abstract protected function addOne($id);
	
// 	/**
// 	 * 对游记的评论数加1,返回受影响的记录数
// 	 * @param int $id 需要增一的游记ID
// 	 * @return int
// 	 */
// 	private function addTravelsOne($id) {
// 		$data = array('replay_number' => 'replay_number + 1');
// 		$rtn = $this->_db->update($this->_travels_table, $data, 'travel_notes_id="' . intval($id) . '"');
// 		return $rtn;
// 	}
	
// 	/**
// 	 * 对游记的评论数减去$num,返回受影响的记录数
// 	 * @param int $id  要减去的游记ID
// 	 * @param int $num  要减去的值[默认1]
// 	 * @return int
// 	 */
// 	private function delTravels($id, $num = 1) {
// 		$num = (intval($num) > 0 ? intval($num) : 1);
// 		$data = array('replay_number' => 'replay_number - ' . $num);
// 		$rtn = $this->_db->update($this->_travels_table, $data, 'travel_notes_id="' . intval($id) . '"');
// 		return $rtn;
// 	}
}

?>
