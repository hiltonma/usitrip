<?php
/**
 * 后台管理员登录日志管理类
 * @author lwkai 2013-10-10
 *
 */
class AdminLoginLogs {
	/**
	 * 搜索条件开始日期
	 * @var string
	 */
	private $_startDate = '';
	/**
	 * 搜索条件结束日期
	 * @var string
	 */
	private $_endDate = '';
	/**
	 * 搜索条件IP
	 * @var string
	 */
	private $_ip = '';
	/**
	 * 搜索条件工号
	 * @var string
	 */
	private $_job_number = '';
	
	/**
	 * 每页显示多少条记录
	 * @var int
	 */
	private $_pageSize = 10;
		
	/**
	 * 构造函数 初始化时，可以带上搜索时需要的条件
	 * @param string $StartDate 开始时间段[可选]
	 * @param string $EndDate 结束时间段[可选]
	 * @param string $IP IP地址[可选]
	 * @param int $job_number 用户工号[可选]
	 * @param int $pagesize 列表一页多少条,默认10
	 */
	public function __construct($StartDate='',$EndDate='',$IP='',$job_number='',$pagesize=10){
		$this->_endDate = $EndDate;
		$this->_startDate = $StartDate;
		$this->_ip = $IP;
		$this->_job_number = $job_number;
		$this->_pageSize = $pagesize;
	}
	/**
	 * 添加一条记录
	 * @param int $admin_id 当前要记录到日志的用户ID
	 * @return number
	 */
	public function add($admin_id){
		$data = array();
		$data['ip'] = tep_get_ip_address();
		$data['time'] = date('Y-m-d H:i:s');
		$data['admin_id'] = intval($admin_id);
		tep_db_fast_insert('admin_login_logs', $data);
		return tep_db_insert_id();
	}
	
	/**
	 * 取得数据列表
	 * @return multitype:NULL multitype:multitype:  string
	 */
	public function getList() {
		$rtn = array();
		$record = array();
		$sql = "select a.admin_job_number,alog.* from admin_login_logs as alog,admin as a ";
		$sql .= " where " . $this->createWhere() . " order by alog.id desc";
		$keywords_query_numrows = 0;
		$_split = new splitPageResults($_GET['page'], $this->_pageSize, $sql, $keywords_query_numrows);
		$rs = tep_db_query($sql);
		while($row = tep_db_fetch_array($rs)) {
			$record[] = $row;
		}
		$rtn['list'] = $record;
		$rtn['pagelink'] = $_split->display_links($keywords_query_numrows, $this->_pageSize, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array (
				'page',
				'y',
				'x',
				'action'
		)));
		$rtn['pagecount'] = $_split->display_count($keywords_query_numrows, $this->_pageSize, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		return $rtn;
	}
	
	/**
	 * 根据条件组合查询条件并返回
	 * @return string
	 */
	private function createWhere(){
		$where = 'a.admin_id = alog.admin_id';
		$where .= $this->_startDate != '' ? ' and alog.time >= \'' . date('Y-m-d H:i:s',strtotime($this->_startDate)) . '\'' : '';
		$where .= $this->_endDate != '' ? ' and alog.time <= \'' . date('Y-m-d 23:59:59',strtotime($this->_endDate)) . '\'' : '';
		$where .= $this->_ip != '' ? ' and alog.ip=\'' . tep_db_input($this->_ip) . '\'' : '';
		$where .= $this->_job_number != '' ? ' and a.admin_job_number=\'' . intval($this->_job_number) . '\'' : '';
		return $where;
	}
	
	/**
	 * 删除记录
	 * @param array|string $id
	 */
	public function del($id=array()){
		$ids = '';
		if (is_array($id)) {			
			foreach($id as $key => $val) {
				$id = intval($val);
				$ids .= $id > 0 ? $id . ',' : '';
			}			
			$ids = trim($ids,',');
		} elseif (is_string($id)) {
			$id = intval($id);
			$ids = $id > 0 ? $id : '';
		}
		if ($ids) {
			$sql = "delete from admin_login_logs where id in (" . $ids . ")";
			tep_db_query($sql);
		}
	}
}