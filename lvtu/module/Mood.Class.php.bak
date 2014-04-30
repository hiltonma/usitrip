<?php
/**
 * 心情操作类，暂时实现删除心情
 */
class Mood extends Abstract_Manage{

	/**
	 * 本类需要操作的数据表[travel_day]
	 * @var string
	 */
	protected $_table = 'travel_day';
	
	/**
	 * 当前对象类型标记 【图片为1[对应Image] 心情为 2[对应Mood] 游记为3】
	 * @var string
	 */
	protected $_target = 'Mood';
	
	/**
	 * 栏位前缀名称
	 * @var string
	 */
	protected $_field_prefix = 'day';
	

	/**
	 * 初始化需要的参数
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 添加一条心情，返回插入的新记录ID
	 * @param array $data 要插入的数据键值对
	 * @return int
	 * @author lwkai 2013-4-23 上午11:41:03
	 */
	public function add($data){
		$rtn = $this->db()->insert($this->_table, $data);
		return $rtn;
	}
	
	/**
	 * 浏览数加1
	 * @param int $id 增加浏览数的ID
	 * @author lwkai 2013-5-3 下午2:29:39
	 */
	public function addViews($id) {
		return $this->update(array('read_number'=>'read_number + 1'), 'day_id=' . intval($id));
	}
	
	/**
	 * 根据图片ID取得浏览数
	 * @param int $id 图片ID
	 * @return Ambigous <>|number
	 * @author lwkai 2013-5-3 下午3:05:58
	 */
	public function getViews($id) {
		$sql = "select read_number from " . $this->_table . " where day_id='" . intval($id) . "'";
		$rs = $this->db()->query($sql)->getOne();
		if ($rs) {
			return $rs['read_number'];
		} else {
			return 0;
		}
	}
	
	/**
	 * 修改心情内容，返回受影响的记录数
	 * @param array $data 要修改的键值对
	 * @param string $where 修改条件
	 * @return number
	 * @author lwkai 2013-4-23 上午11:45:53
	 */
	public function update($data, $where) {
		$rtn = $this->db()->update($this->_table, $data, $where);
		return $rtn;
	}
	
	/**
	 * @param int $id 游记ID
	 * 根据游记ID删除所有相关的心情
	 * (non-PHPdoc)
	 * @see Abstract_Manage::del()
	 */
	public function del($id) {
		$sql = "select * from " . $this->_table . " where travel_notes_id='" . intval($id) . "'";
		$rtn = $this->db()->query($sql)->getAll();
		foreach($rtn as $key=>$val) {
			parent::del($val['day_id']);
		}
	}
	
	/**
	 * 删除一条心情
	 * @param int $id 心情ID
	 * @return Ambigous <number, number>
	 * @author lwkai 2013-4-27 下午1:48:13
	 */
	public function delOne($id) {
		return parent::del($id);
	}
	
	/**
	 * 根据游记ID取得最早的一条心情
	 * @param int $travel_id 游记ID
	 * @return string
	 * @author lwkai 2013-4-25 下午3:29:09
	 */
	public function getOneByTravelId($travel_id){
		$rtn = '';
		$sql = "select description from " . $this->_table . " where travel_notes_id = '" . intval($travel_id) . "' order by time_taken asc limit 1";
		$rs3 = $this->db()->query($sql)->getOne();
		if ($rs3) {
			$rtn = htmlspecialchars($rs3['description']);
		}
		return $rtn;
	}
	
	/**
	 * 根据心情ID取得游记ID
	 * @param int $day_id 心情ID
	 * @return int
	 * @author lwkai 2013-4-12 下午23:27:15
	 */
	public function getTravelId($day_id) {
		$sql = "select travel_notes_id from " . $this->_table . " where " . $this->_field_prefix . "_id='" . intval($day_id) . "'";
		$rs = $this->db()->query($sql)->getOne();
		return intval($rs['travel_notes_id']);
	}
	
	/**
	 * 取得心情列表
	 * @param string $where 获取条件
	 * @return multitype:multitype: 
	 * @author lwkai 2013-4-28 上午10:35:23
	 */
	public function getList($where = '') {
		$sql = "select * from " . $this->_table;// . " where travel_notes_id='" . intval($id) . "'";
		if ($where != '') {
			$sql .= ' where ' . $where;
		}
		$rtn = $this->db()->query($sql)->getAll();
		return $rtn;
	}
}
?>