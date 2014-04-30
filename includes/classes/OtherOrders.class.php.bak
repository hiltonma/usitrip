<?php
/**
 * 漏单的一个管理
 * @author wtj
 * @date 2013-3-20
 */
class OtherOrders {
	private $type;
	/**
	 * 构造函数，初始化类型
	 * 
	 * @param int $type=1美国，$type=2中国        	
	 */
	function __construct($type = '') {
		if ($type) {
			$this->type = $type;
		} else {
			// 系统时间 7.30am-17.30pm 美国工号分，其他时间中国的分
			$_us_time_num_start = strtotime(date('Y-m-d 07:30:00'));
			$_us_time_num_end = strtotime(date('Y-m-d 17:30:00'));
			$_now_time_num = strtotime(date('Y-m-d H:i:s'));
			$this->type = 2;
			if ($_now_time_num >= $_us_time_num_start && $_now_time_num <= $_us_time_num_end) {
				$this->type = 1;
			}
		}
	}
	/**
	 * 检查是否已经有订单归属了
	 * @param unknown_type $order_id
	 * @return boolean
	 */
	function checkOrdersOwnerHave($order_id){
		$str_sql='SELECT orders_owners FROM `orders` WHERE orders_id='.(int)$order_id;
		$sql_query=tep_db_query($str_sql);
		$arr=tep_db_fetch_array($sql_query);
		if($arr['orders_owners']==NULL)
			return true;
		else 
			return false;
	}
	/**
	 * 获取JOBS_ID
	 *
	 * @return Ambigous <>
	 */
	function getJobsNumber() {
		$str_sql = 'select jobs_id from orders_get_jobs where is_get=1 and jobs_type=' . $this->type;
		$sql_query = tep_db_query($str_sql);
		$return = tep_db_fetch_array($sql_query);
		$this->roolOne();
		return $return['jobs_id'];
	}
	/**
	 * 获取列表
	 *
	 * @return multitype:multitype:
	 */
	function getList() {
		$return = array();
		$str_sql = 'select * from orders_get_jobs where jobs_type=' . $this->type.' order by jobs_id';
		$sql_query = tep_db_query($str_sql);
		while ( $arr = tep_db_fetch_array($sql_query) ) {
			$return[] = $arr;
		}
		return $return;
	}
	/**
	 * 往前滚一位人
	 *
	 * @return boolean
	 */
	function roolOne() {
		$arr_list = $this->getList();
		$mark = false;
		if ($arr_list) {
			for($i = 0; $i < 3; $i ++) {
				foreach ( $arr_list as $key => $value ) {
					if ($mark) {
						$id = $value['orders_jobs_id'];
						break 2;
					}
					if ($value['is_get'] == 1)
						$mark = true;
				}
			}
			$str_sql = 'update orders_get_jobs set is_get=0 where jobs_type=' . $this->type;
			tep_db_query($str_sql);
			$str_sql = "update orders_get_jobs set is_get=1 where orders_jobs_id=$id";
			tep_db_query($str_sql);
			return true;
		}
	}
	/**
	 * 检查销售跟踪
	 *
	 * @param unknown_type $orders_id
	 *        	订单的ID
	 */
	function checkTrack($orders_id) {
		$arr_product = $model_arr = $arr_tmp2 = array();
		foreach ( $_SESSION['cart']->contents as $key => $v ) {
			$arr_product[] = ( int ) $key;
		}
		$str_tmp = join(',', $arr_product);
		$str_sql = 'select products_model from products where products_id in (' . $str_tmp . ') and is_hotel=0';
		$sql_query = tep_db_query($str_sql);
		while ( $arr = tep_db_fetch_array($sql_query) ) {
			$model_arr[] = $arr['products_model'];
		}
		$login_id = '';
		$str_sql = 'select customers_email_address from orders where orders_id=' . $orders_id;
		$sql_query = tep_db_query($str_sql);
		$arr_tmp = tep_db_fetch_array($sql_query);
		//$str_sql = 'select login_id,GROUP_CONCAT(code) as code FROM salestrack WHERE customer_email="' . $arr_tmp['customers_email_address'] . '" GROUP BY login_id order by add_date';
		$str_sql = 'select login_id,code FROM salestrack WHERE customer_email="' . $arr_tmp['customers_email_address'] . '" order by add_date';
		$sql_query = tep_db_query($str_sql);
		while ( $arr = tep_db_fetch_array($sql_query) ) {
			$arr_tmp2 = explode(',', $arr['code']);
			foreach ( $model_arr as $value ) {
				if (in_array(trim($value), $arr_tmp2)) {
					$mark = true;
					continue;
				} else {
					$mark = false;
					break;
				}
			}
			if ($mark) {
				$login_id = $arr['login_id'];
				break;
			}
		}
		return $login_id;
	}
	/**
	 * 增加
	 *
	 * @param unknown_type $jobs_id        	
	 * @param unknown_type $type        	
	 * @return string
	 */
	function addOne($jobs_id, $type) {
		if (! $this->checkHaveGet($type, '', $jobs_id)) {
			$array = array(
					'jobs_id' => ( int ) $jobs_id,
					'jobs_type' => ( int ) $type 
			);
			if (! $this->checkHaveGet($type)) {
				$array['is_get'] = 1;
			}
			tep_db_perform('orders_get_jobs', $array);
		} else {
			return '已有这个工号。不可重复添加';
		}
	}
	/**
	 * 删除
	 *
	 * @param unknown_type $id        	
	 */
	function dropOne($id) {
		if ($this->checkHaveGet('', $id)) {
			$this->roolOne();
		}
		$str_sql = 'delete from orders_get_jobs where orders_jobs_id=' . $id;
		tep_db_query($str_sql);
	}
	/**
	 * 检查是否有isget为1的记录
	 *
	 * @param int $type
	 *        	类型
	 * @param int $id
	 *        	orders_jobs_id
	 * @param int $jobs_id        	
	 * @return number
	 */
	private function checkHaveGet($type = '', $id = '', $jobs_id = '') {
		$str_sql = 'select orders_jobs_id from orders_get_jobs where is_get = 1 ';
		$type ? $str_sql .= ' and jobs_type=' . $type : '';
		$id ? $str_sql .= ' and orders_jobs_id=' . $id : '';
		$jobs_id ? $str_sql .= ' and jobs_id = ' . $jobs_id : '';
		$sql_query = tep_db_query($str_sql);
		return tep_db_num_rows($sql_query);
	}
	/**
	 * 添加销售跟踪链接，绕过去检查SP
	 *
	 * @param int $orders_id        	
	 * @param int $login_id        	
	 */
	function changeOwner($orders_id, $login_id) {
		tep_db_query('update orders set orders_owner_admin_id="' . $login_id . '", orders_owner_commission="1" where orders_id="' . ( int ) $orders_id . '" ');
	}
	/**
	 * 更改订单的标记，看是否是掉单给的归属
	 *
	 * @param int $orders_id
	 *        	订单号
	 */
	function changeMark($orders_id, $is_other = 1) {
		tep_db_query('update orders set is_other_owner=' . ( int ) $is_other . ' where orders_id=' . ( int ) $orders_id);
	}
	/**
	 * 改变订单归属人
	 * 
	 * @param unknown_type $orders_id        	
	 * @param unknown_type $login_str
	 *        	订单归属人
	 * @param unknown_type $orders_owner_commission
	 *        	拥有订单的比例
	 * @param unknown_type $orders_owner_admin_id
	 *        	订单的连接工号
	 */
	public function changeHaving($orders_id, $login_str, $orders_owner_commission = 1, $orders_owner_admin_id = '') {
		// echo 'UPDATE orders set
		// orders_owner_commission='.$orders_owner_commission.',orders_owners="'.$login_str.'"
		// where orders_id='.(int)$orders_id;
		// die();
		$str_sql = 'UPDATE orders set orders_owner_commission=' . $orders_owner_commission . ',orders_owners="' . $login_str . '",orders_owner_admin_id=' . ( int ) $orders_owner_admin_id . ' where orders_id=' . ( int ) $orders_id;
		
		tep_db_query($str_sql);
	}
	/**
	 * 从客服ID号取得工号
	 *
	 * @param unknown_type $admin_id        	
	 */
	function tep_get_job_number_from_admin_id($admin_id) {
		$sql = tep_db_query('SELECT admin_job_number FROM `admin` WHERE admin_id =' . trim(tep_db_prepare_input(tep_db_input($admin_id))));
		$row = tep_db_fetch_array($sql);
		return $row['admin_job_number'];
	}
	/**
	 * 记录订单是从哪里过来的
	 * @param unknown_type $orders_id
	 */
	function changeFrom($orders_id){
		if(isset($_COOKIE['url_from']))
			return;
		$str_sql='update orders set customers_advertiser="'.$_COOKIE['url_from'].'" WHERE orders_id='.$orders_id;
		tep_db_query($str_sql);
	}
}