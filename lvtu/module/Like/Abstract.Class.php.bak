<?php
/**
 * 喜欢抽象类
 * @package Like
 * @author lwkai 2013-04-01 16:48
 */
abstract class Like_Abstract {
	
	/**
	 * 数据库操作对象
	 * @var Db_Mysql
	 */
	protected $_db = null;
	
	/**
	 * 表名
	 * @var string
	 */
	protected $_table = '';
	
	/**
	 * 查找是否添加喜欢的标记 1为图片，2为心情，3为游记
	 * @var int
	 */
	protected $_target = 0;
	
	/**
	 * 添加喜欢的历史记录
	 */
	protected $_history_table = 'travel_like_history';
	
	/**
	 * 构造函数，初始化数据库操作对象
	 */
	public function __construct() {
		$this->_db = Db::get_db();
	}
	
	/**
	 * 添加喜欢
	 * @param  int $id 要添加喜欢的记录ID
	 */
	abstract protected function addLike($id, $userid);
	
	/**
	 * 删除喜欢
	 * @param int $id 删除喜欢的ID
	 * @param int $num 减去多少个喜欢数
	 */
	abstract protected function delLike($id, $userid);
	
	/**
	 * 根据ID取得游记ID
	 * @param int $id 
	 */
	abstract public function getTravelId($id);
	
	/**
	 * 检查某人某是否对某个图片增加过喜欢数,已经增加则返回真，否则返回假
	 * @param int $id 需要检查的图片ID
	 * @param int $userid 当前登录的用户ID
	 * @return boolean
	 */
	public function isLike( $id, $user_id) {
		$id = intval($id);
		$user_id = intval($user_id);
		$rtn = false;
		if ($id > 0 && $user_id > 0) {
			$sql = "select add_id from " . $this->_history_table . " where add_id ='" . intval($id) . "' and user_id='" . intval($user_id) . "' and target='" . $this->_target . "'";
			$temp = $this->_db->query($sql)->getOne();
			if ($temp) {
				$rtn = true;
			}
		}
		return $rtn;
	}
	
	/**
	 * 添加喜欢操作的历史记录
	 * @param int $travel_id 操作对象的所属游记ID
	 * @param int $userid 当前操作的用户ID
	 * @param [int] $id 操作对象的ID号
	 * @return number
	 */
	public function addHistory($travel_id, $userid, $id = 0) {
		$id = intval($id);
		$travel_id = intval($travel_id);
		$userid = intval($userid);
		$rtn = 0;
		if ($travel_id > 0 && $this->_target > 0 && $userid > 0) {
			$data = array(
				'user_id' => $userid,
				'add_id'  => $id,
				'travel_id' => $travel_id,
				'time' => date('Y-m-d H:i:s'),
				'target' => $this->_target
			);
			$rtn = $this->_db->insert($this->_history_table,$data);

		}
		return $rtn;
	}
	
	/**
	 * 删除掉取消的喜欢记录
	 * @param int $id 添加记录的ID号
	 * @param int $activity_type 操作动作 1为添加 2为取消
	 * @param int $userid 当前操作用户的ID
	 * @return Ambigous <number, number>
	 * @author lwkai 2013-4-24 上午11:50:55
	 */
	public function delHistory($travel_id, $userid = 0, $id = 0) {
		$id = intval($id);
		$travel_id = intval($travel_id);
		$userid = intval($userid);
		$rtn = 0;
		$where = " travel_id='" . $travel_id . "' and add_id='" . $id . "' and target='" . $this->_target . "'";
		if ($userid) {
			$where .= " and user_id='" . $userid . "'";
		}
		if ($travel_id > 0 && $this->_target > 0) {
			$rtn = $this->_db->delete($this->_history_table, $where);
		}
		return $rtn;
	}
	
	/**
	 * 统计某个对象的喜欢总数
	 * @param int $travel_id 游记ID
	 * @param [int] $id 图片或者心情ID号
	 * @return Ambigous <>
	 * @author lwkai 2013-4-24 下午12:04:50
	 */
	public function countLike($travel_id, $id = 0, $target = 0) {
		$sql = "select count(user_id) as num from " . $this->_history_table . " where 1=1";
		if ($travel_id) {
			$sql .= " and travel_id='" . $travel_id . "'";
		}
		if ($id) {
			$sql .= " and add_id='" . $id . "'";
		}
		if ($target) {
			$sql .= " and target='" . $target . "'";
		}
		$rtn = $this->_db->query($sql)->getOne();
		return $rtn['num'];
	}
	 
}
?>