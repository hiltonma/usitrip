<?php
/**
 * 投诉类，用于处理投诉要处理的所有问题。
 * @author wtj @date 2013-7-23
 */
class OrderComplaints {
	private $_id; //投诉的ID
	private $_table; //表名
	/**
	 * 严重度数组
	 * @var ArrayObject
	 */
	public $severity_array = array (
			'普通',
			'严重',
			'非常严重' 
	);
	public $serverity_color_array=array(
			'普通'=>'#00FF00',
			'严重'=>'#0000FF',
			'非常严重'=>'red' 
			);
	/**
	 * 进度数组
	 * @var array
	 */
	public $step_array = array (
			'待处理',
			'已跟进',
			'已回复',
			'已解决' 
	);
	/**
	 * 问题数组
	 * @var array
	 */
	public $problem_array = array (
			'接机',
			'导游',
			'行程安排',
			'其他' 
	);
	/**
	 * 赔付类型数组
	 * @var array
	 */
	public $type_array = array (
			'积分',
			'现金',
			'COUPON' 
	);
	private $page_number = 50; //每页显示条数
	private $number; //查询后台的时候的记录数

	/**
	 * 构造函数
	 * @param int $id 投诉ID
	 */
	function __construct($id = '') {
		$this->_table = 'orders_complaints';
		$this->_id = $id;
	}

	/**
	 * 订单页列表获取
	 * @param int $orders_id 订单ID
	 * @return array
	 */
	function getListIndex($orders_id) {
		$str_sql = 'SELECT * FROM ' . $this->_table . '  where orders_id=' . (int) $orders_id . ' order by add_time DESC';
		return $this->doSelect($str_sql);
	}

	/**
	 * 投诉页列表获取
	 * @return array
	 */
	function getList() {
		$where = $table = '';
		$where .= $this->check('orders_id', 't1.orders_id');
		$where .= $this->check('agency_id', 't1.supplier_id');
		$where .= $this->check('jobs_id', 't1.jobs_id');
		$where .= $this->check('problem', 't1.problem');
		$where .= $this->check('severity', 't1.severity');
		$where .= (isset($_GET['key_world']) && $_GET['key_world']) ? ' AND t1.complaints_content like "%' . $_GET['key_world'] . '%"' : '';
		$where .= $this->check('complaints_step', 't1.complaints_step');
		$table = $this->_table . ' t1';
		//当要关联某个表时，才载入相应的表。所以这个地方的SQL是有多个的
		if ((isset($_GET['o_b_time']) && $_GET['o_b_time']) || (isset($_GET['o_e_time']) && $_GET['o_e_time'])) {
			$where .= ' AND t1.orders_id=t2.orders_id';
			$where .= (isset($_GET['o_b_time']) && $_GET['o_b_time']) ? ' AND t2.date_purchased>"' . $_GET['o_b_time'] . '"' : '';
			$where .= (isset($_GET['o_e_time']) && $_GET['o_e_time']) ? ' AND t2.date_purchased<="' . $_GET['o_e_time'] . '"' : '';
			$table .= ',orders t2';
		}
		if ((isset($_GET['u_b_time']) && $_GET['u_b_time']) || (isset($_GET['u_e_time']) && $_GET['u_e_time'])) {
			$where .= 'and t2.orders_id=t3.orders_id and t1.orders_products_id=t3.orders_products_id ';
			$where .= (isset($_GET['u_b_time']) && $_GET['u_b_time']) ? ' AND t3.products_departure_date>"' . $_GET['u_b_time'] . '"' : '';
			$where .= (isset($_GET['u_e_time']) && $_GET['u_e_time']) ? ' AND t3.products_departure_date<="' . $_GET['u_e_time'] . '"' : '';
			$table .= ',orders_products t3';
		}
		if ((isset($_GET['u_e_time']) && $_GET['u_e_time'])) {
			$where .= ' AND t1.complaints_id=t4.orders_complaints_id';
			$where .= $this->check('type', 't4.orders_complaints_type');
			$table .= ',orders_complaints_money t4';
		}
		$str_sql = 'SELECT t1.* from ' . $table . ' WHERE 1 ' . $where;
		$str_sql .= ' order by t1.add_time DESC ';
		$this->number = tep_db_num_rows(tep_db_query($str_sql));
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$track_query_numrows = 0;
		//分页的地方。给一次性返回回去
		$track_split = new splitPageResults($_GET['page'], $this->page_number, $str_sql, $track_query_numrows);
		$a = $track_split->display_links($track_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array (
				'page',
				'y',
				'x',
				'action' 
		)));
		$b = $track_split->display_count($track_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		return array (
				'info' => $this->doSelect($str_sql),
				'a' => $a,
				'b' => $b 
		);
	}

	/**
	 * 通过投诉ID查询相应的类型信息
	 * @param int $complants_id 投诉ID
	 * @return arra||NULL
	 */
	function getMoneyInfo($complants_id) {
		$str_sql = 'SELECT * FROM orders_complaints_money WHERE orders_complaints_id=' . (int) $complants_id;
		return $this->doSelect($str_sql);
	}

	/**
	 * 投诉页面获取单个信息
	 * @param int $complaints_id 投诉ID
	 * @return array
	 */
	function getOne($complaints_id) {
		$str_sql = 'select * from ' . $this->_table . ' where complaints_id=' . (int)$complaints_id;
		$tmp = $this->doSelect($str_sql);
		$cps_list = $tmp[0];
		$str_sql = 'select * from orders_complaints_money where orders_complaints_id=' . (int)$complaints_id;
		$money_list = $this->doSelect($str_sql);
		$travel_list = $this->getOrderTraverInfo($cps_list['orders_id'], $cps_list['orders_products_id']);
		return array (
				'cps_list' => $cps_list,
				'money_list' => $money_list,
				'travel_list' => $travel_list 
		);
	}

	/**
	 * 获取订单的行程
	 * @param int $orders_id 订单ID
	 * @param int $orders_products_id 订单产品ID
	 * @return array
	 */
	function getOrderTraverInfo($orders_id, $orders_products_id = '') {
		$str_sql = 'select t2.orders_products_id,t2.products_name,t2.products_departure_date from orders t1,orders_products t2 where t1.orders_id=t2.orders_id and t1.orders_id=' . $orders_id;
		$tmp = $this->doSelect($str_sql);
		$travel_name_str = '';
		$time_show = '';
		foreach ($tmp as $value) {
			if ($orders_products_id == $value['orders_products_id']) {
				$travel_name_str .= '<option value="' . $value['orders_products_id'] . '::' . $value['products_departure_date'] . '" selected>' . $value['products_name'] . '</option>';
				$time_show = $value['products_departure_date'];
			} else {
				$travel_name_str .= '<option value="' . $value['orders_products_id'] . '::' . $value['products_departure_date'] . '">' . $value['products_name'] . '</option>';
				if (!$time_show)
					$time_show = $value['products_departure_date'];
			}
		}
		return array (
				'time_show' => $time_show,
				'travel_name' => $travel_name_str 
		);
	}

	/**
	 * 订单页面显示备注，打印了一个表单
	 * @param array $info 单个投诉信息
	 */
	function showAdd($info = array()) {
		$orders_id = (is_array($info) ? $info['orders_id'] : $info);
		$complaints_id = is_array($info) ? $info['complaints_id'] : '';
		$complaints_content = is_array($info) ? $info['complaints_content'] : '';
		$complaints_remark = is_array($info) ? $info['complaints_remark'] : '';
		$order_travel_info = $this->getOrderTraverInfo($orders_id, isset($info['orders_products_id']) ? $info['orders_products_id'] : '');
		echo <<<EOF
<form method="post" action="" name="add_complaints" ><table border="1" class="order_head" style="color:#000000;display:none" name="add_other_info"><tr><td align="right">订单号：</td><td align="left"><input type="text" name="orders_id" value="$orders_id" disabled/></td></tr><tr><td align="right">行程名称：</td><td align="left"><select name="travel_name" onchange="changeInTime(this.value,jQuery(this).parents(\'table.order_head\'))">$order_travel_info[travel_name]</select></td></tr><tr><td align="right">编号：</td><td align="left"><input type="hidden" id="complaints_id_h" name="complaints_id" value="$complaints_id"/><input type="text" id="complaints_id" value="$complaints_id" disabled/></td></tr><tr><td align="right">参团时间：</td><td align="left"><input type="hidden" name="u_time" id="my_u_time_hide" value="$order_travel_info[time_show]"/><input type="text" id="my_u_time" value="$time_show" disabled/></td></tr><tr><td align="right">顾客投诉内容：</td><td align="left"><input type="text" name="complaints_content" value="$complaints_content"/></td></tr><tr><td align="right">处理备注：</td><td align="left"><textarea name="complaints_remark" style="width:150px; height:100px">$complaints_remark</textarea></td><tr><td align="right"><input type="reset" value="重置" /></td><td align="left"><input type="submit" value="确定" /></td></tr></tr></table></form>
EOF;
	}

	/**
	 * 订单页处理备注提交
	 */
	function doAdd() {
		if (isset($_POST['complaints_id'])) {
			$tmp = explode('::', $_POST['travel_name']);
			$str_sql = "UPDATE " . $this->_table . " set join_time='$_POST[u_time]',complaints_remark='".addslashes($_POST[complaints_remark])."',complaints_content='".addslashes($_POST[complaints_content])."',orders_products_id=$tmp[0] where complaints_id=$_POST[complaints_id]";
			//echo $str_sql;
			tep_db_query($str_sql);
		}
	}
	/**
	 * 修改一个供应商的投诉的数目
	 * @param 投诉ID $complaints_id
	 * @param 供应商ID $agency_in
	 * @return number
	 */
	function changeAgencyComplaints($complaints_id,$agency_in){
		$str_sql='select supplier_id from '.$this->_table.' where complaints_id='.(int)$complaints_id;
		$arr_tmp=$this->doSelect($str_sql);
		$agency_id=$arr_tmp[0]['supplier_id'];
		if($agency_id&&$agency_id!=$agency_in){
			$str_sql='UPDATE tour_travel_agency SET complaints_number=complaints_number-1 where agency_id='.(int)$agency_id.' and complaints_number>0';
			tep_db_query($str_sql);
			$str_sql='UPDATE tour_travel_agency SET complaints_number=complaints_number+1 where agency_id='.(int)$agency_in;
			tep_db_query($str_sql);
		}
		return 0;
	}
	/**
	 * 投诉页处理修改
	 */
	function changeOneBack() {
// 		$tmp = explode('::', $_POST['agency_id']);
		$this->changeAgencyComplaints($_POST['complaints_id'], $_POST['agency_id']);
		$str_sql = "UPDATE `orders_complaints` SET supplier_id=$_POST[agency_id], 	`severity`='" . html_to_db(tep_db_prepare_input(ajax_to_general_string($_POST[severity]))) . "',complaints_content='" . html_to_db(tep_db_prepare_input($_POST[complaints_contents])) . "',`complaints_step`='" . html_to_db(tep_db_prepare_input(ajax_to_general_string($_POST[step]))) . "',`problem`='" . html_to_db(tep_db_prepare_input(ajax_to_general_string($_POST[problem]))) . "' where complaints_id=$_POST[complaints_id]";
		tep_db_query($str_sql);
		$tmp1 = '';
		if ($_POST['change_type']) {
			foreach ($_POST['change_type'] as $key => $value) {
				if(isset($_POST['change_money'][$key])&&$_POST['change_money'][$key])
				$tmp1 .= ',("' . $value . '",' . $_POST['change_money'][$key] . ',' . $_POST['complaints_id'] . ')';
			}
			if ($tmp1) {
				$str_sql = 'insert into orders_complaints_money(orders_complaints_type,orders_complaints_money,orders_complaints_id)values' . substr($tmp1, 1);
			}
			tep_db_query($str_sql);
			
		}
		if($_POST['complaints_remark']){
			$str_sql='insert into orders_complaints_remark set complaints_remark="'.addslashes($_POST['complaints_remark']).'",complaints_id='.(int)$_POST['complaints_id'].',add_time="'.date('Y-m-d H:i:s').'",add_user='.tep_get_job_number_from_admin_id($_SESSION['login_id']);
			tep_db_query($str_sql);
		}
	}

	/**
	 * 删除一个金额类型
	 * @param int $id 投诉类型页ID
	 */
	function dropOneMoney($id) {
		$str_sql = 'delete from orders_complaints_money where orders_complaints_money_id=' . $id;
		tep_db_query($str_sql);
	}

	/**
	 * 添加一个投诉
	 * @param int $orders_id
	 * @return number
	 */
	function addOne($orders_id) {
		$str_sql = "select t3.agency_id from orders t1,orders_products t2,products t3 where t1.orders_id=t2.orders_id and t1.orders_id=" . (int) $orders_id . " and t2.products_id=t3.products_id";
		$arr = $this->doSelect($str_sql);
		$str_sql='UPDATE tour_travel_agency SET complaints_number=complaints_number+1 where agency_id='.(int)$arr[0]['agency_id'];
		tep_db_query($str_sql);
		$array = array (
				'orders_id' => $orders_id,
				'supplier_id' => $arr[0]['agency_id'],
				'add_time' => date('Y-m-d H:i:s'),
				'jobs_id' => tep_get_job_number_from_admin_id($_SESSION['login_id']) 
		);
		tep_db_perform($this->_table, $array);
		$str_sql='';
		return tep_db_insert_id();
	}

	/**
	 * 删除一个投诉
	 * @param int $id
	 */
	function dropOne($id) {
		$str_sql = 'DELETE FROM ' . $this->_table . ' where complaints_id=' . (int) ($id);
		tep_db_query($str_sql);
	}

	/**
	 * 生成GET POST 判断查询
	 * @param string $key get post key
	 * @param string $co 表行名
	 * @return string
	 */
	function check($key, $co) {
		return (isset($_GET[$key]) && $_GET[$key]) ? ' and ' . $co . '="' . $_GET[$key] . '"' : '';
	}

	/**
	 * 更改表的一个字段
	 * @param string $co 列名
	 * @param string|int $value 值
	 */
	function changeOne($co, $value) {
		$str_sql = 'UPDATE ' . $this->_table . ' set ' . $co . '="' . $value . '" where complaints_id=' . $this->_id;
		tep_db_query($str_sql);
	}

	/**
	 * 获取供应商信息
	 * @return multitype:multitype:
	 */
	function getAgency() {
		$str_sql = 'select agency_id,agency_name,agency_name1 from tour_travel_agency';
		return $this->doSelect($str_sql);
	}

	/**
	 * 查询
	 * @param string $str_sql 查询的SQL语句
	 * @return array
	 */
	function doSelect($str_sql) {
		$return = array ();
		$sql_query = tep_db_query($str_sql);
		while ($row = tep_db_fetch_array($sql_query)) {
			$return[] = $row;
		}
		return $return;
	}

	/**
	 * 通过array生成select的option
	 * @param array $array 数组
	 * @param int $val 用于判断等于的值
	 * @return string
	 */
	function dreawOption($array, $val = '') {
		$str = '';
		foreach ($array as $value) {
			if ($val == $value)
				$str .= '<option selected>' . $value . '</option>';
			else
				$str .= "<option>$value</option>";
		}
		return $str;
	}

	/**
	 * 生成供应商的OPTION
	 * @param array $array 数组
	 * @param string|int $val 判断等于的值
	 * @return string
	 */
	function dreawAgencyOption($array, $val = '') {
		$str_return = '';
		foreach ($array as $value) {
			if ($val == $value['agency_id'])
				$str_return .= "<option value='$value[agency_id]' selected>$value[agency_id]</option>";
			else
				$str_return .= "<option value='$value[agency_id]'>$value[agency_id]</option>";
		}
		return $str_return;
	}

	/**
	 * 创建分页所需的URL
	 *
	 * @param string $url URL
	 * @return string
	 */
	function createPage($url) {
		$str_back = '';
		$page = ceil($this->number / $this->page_number);
		if ($page > 1) {
			$str_back = '--共' . $page . '页--<select name="page" id="problem_page">';
			$str_back .= '<option value="">请选择页码</option>';
			for ($i = 1; $i <= $page; $i ++) {
				$str_back .= '<option value="' . $i . '">第-' . $i . '-页</option>';
			}
			$str_back .= '</select>';
			$str_back .= '<input type="button" value="确定" onclick="location.href=\'' . $this->doUrl($url) . 'page=\'+document.getElementById(\'problem_page\').value"';
		}
		return $str_back;
	}

	/**
	 * 处理分页要用的URL
	 *
	 * @param string $url URL
	 * @return Ambigous <>
	 */
	function doUrl($url) {
		$arr_tmp = explode('&page', $url);
		$str_tmp = $arr_tmp[0];
		if (false === strpos($str_tmp, '?'))
			$str_tmp .= '?';
		else
			$str_tmp .= '&';
		return $str_tmp;
	}

	/**
	 * 供应商二维数组降成一维
	 * @param array $arr agency数组
	 * @return array
	 */
	function createOneAgency($arr) {
		$arr_return = array ();
		foreach ($arr as $key => $value) {
			$arr_return[$value['agency_id']] = $value['agency_name'];
		}
		return $arr_return;
	}
	/**
	 * 获取投诉备注
	 * @param int $id 备注的ID
	 * @return array
	 */
	function getOrdersComplaintsRemark($id){
		$str_sql='select complaints_remark_id,complaints_remark,complaints_id,add_time,add_user from orders_complaints_remark where complaints_id='.(int)$id;
		return $this->doSelect($str_sql);
	}
	/**
	 * 获取有投诉的供应商
	 * @return array
	 */
	function getAgencyComplaitsNumber(){
		$str_sql='select agency_id,complaints_number from tour_travel_agency where complaints_number>0';
		return $this->doSelect($str_sql);
	}
}