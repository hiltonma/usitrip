<?php
/**
 * 订单归属类。此类只记录了航班信息用户修改72小时后非处理人点已阅的处理
 * @author lwkai 2013-07-31 11:06
 * 需求
 * [客人更新航班信息及留言]列表中的订单，如果客人添加航班信息，留言的时间超过72小时之后非订单归属人点击【已阅】、
 * 【设为已读】，订单归属人自动添加上此点击工号，（限原订单归属只有一个工号的订单）。
 * 见任务 http://113.106.94.150:83/index.php?m=tasks&a=view&task_id=329
 */

/**
 * 订单归属(目前只针对航班信息和留言的部分处理)
 * 航班信息用户修改72小时后非订单归属人点已阅的处理
 * 客人留言72小时后非订单归属人点已读的处理
 * @author lwkai 2013-07-31 11:06
 *
 */
class vesting_orders {
	/**
	 * 客人添加航班信息，留言的时间超过72小时\
	 * @var int
	 */
	private $_flights_timeout = 72;
	
	/**
	 * 客人留言内容，留言时间不能超过72小时
	 * @var int
	 */
	private $_message_timeout = 72;
	
	/**
	 * 取得指定订单的订单归属
	 * @param int $orders_id 订单ID
	 * @return String 
	 */
	private function get_vesting_orders($orders_id) {
		$sql = "select orders_owners from orders where orders_id='" . intval($orders_id) . "'";
		$result = tep_db_query($sql);
		$rs = tep_db_fetch_array($result);
		return $rs ? $rs['orders_owners'] : '';
	}
	
	/**
	 * 更新订单归属
	 * @param int $orders_id 订单ID
	 * @param string $orders_owner_admin_id 订单归属
	 * @return number
	 */
	private function set_vesting_orders($orders_id, $orders_owner_admin_id) {
		$data = array();
		$data['orders_owners'] = $orders_owner_admin_id;
		$data['orders_owner_commission']='0.5';
		return tep_db_fast_update('orders', "orders_id='" . intval($orders_id) . "'", $data);
	}
	
	/**
	 * 根据产品快照ID取得最新一条航班信息记录的日期
	 * @param int $orders_products_id 产品快照ID
	 * @return string
	 */
	private function get_update_flights_date($orders_products_id,$orders_id) {
		$sql = "select opfh.add_date from orders_product_flight_history as opfh,orders_products as op where op.orders_products_id=opfh.orders_products_id and op.orders_id='" . intval($orders_id) . "' and opfh.orders_products_id ='" . intval($orders_products_id) . "' order by opfh.history_id desc limit 1";
		$result = tep_db_query($sql);
		$rs = tep_db_fetch_array($result);
		return $rs ? $rs['add_date'] : '';
	}
	
	/**
	 * 根据留言ID与订单ID取得对应的留言时间
	 * @param int $message_id 留言ID
	 * @param int $orders_id  订单ID
	 * @return string
	 */
	private function get_message_date($message_id,$orders_id) {
		$sql = "select addtime from orders_message where orders_id='" . intval($orders_id) . "' and id='" . intval($message_id) . "'";
		$result = tep_db_query($sql);
		$rs = tep_db_fetch_array($result);
		return $rs ? $rs['addtime'] : '';
	}
	
	/**
	 * 检测客人修改航班信息已经过去的时长，并且当前处理人，是否非订单归属人
	 * 如果当前订单归属人小于2人，并且当前处理人非归属人本身，并且航班信息
	 * 更新已经过去72小时，则添加此人到订单归属
	 * @param int $admin_job_number 操作员工号
	 * @param int $orders_id 订单ID
	 * @param int $orders_products_id 产品快照
	 * @return number
	 */
	public function confirm_flights($admin_job_number,$orders_id,$orders_products_id) {
		$vesting_orders = $this->get_vesting_orders($orders_id);
		$vesting_orders = explode(',',$vesting_orders);
		// 如果当前操作人ID已经在订单归属中存在，则不需要做任何改变。
		if (in_array($admin_job_number, $vesting_orders)) { 
			return 0;
		}
		// 如果当前订单归属的人员超过或者等于两个，则不需要做任何改变。
		if (count($vesting_orders) > 1) {
			return 0;
		}
		$datetime = $this->get_update_flights_date($orders_products_id,$orders_id);
		// 如果客人更新航班信息超过了$this->_flights_timeout的小时数，则记录下此人的ID入订单归属
		if ($datetime && ((strtotime(date("y-m-d h:i:s")) - strtotime($datetime))/3600) > $this->_flights_timeout) {
			array_push($vesting_orders,$admin_job_number);
			return $this->set_vesting_orders($orders_id, join(',',$vesting_orders));
		}
	}
	
	/**
	 * 检测客人的留言是否已经过去72小时，并且处理人不在订单归属中，并且归属不大于2人
	 * @param int $admin_job_number 工号
	 * @param int $message_id 留言ID
	 * @param int $orders_id 订单ID
	 * @return number
	 */
	public function confirm_message($admin_job_number, $message_id, $orders_id) {
		$vesting_orders = $this->get_vesting_orders($orders_id);
		$vesting_orders = explode(',',$vesting_orders);
		// 如果当前操作人ID已经在订单归属中存在，则不需要做任何改变。
		if (in_array($admin_job_number, $vesting_orders)) {
			return 0;
		}
		// 如果当前订单归属的人员超过或者等于两个，则不需要做任何改变。
		if (count($vesting_orders) > 1) {
			return 0;
		}
		$datetime = $this->get_message_date($message_id,$orders_id);
		// 如果客人更新航班信息超过了$this->_flights_timeout的小时数，则记录下此人的ID入订单归属
		if ($datetime && ((strtotime(date("y-m-d h:i:s")) - strtotime($datetime))/3600) > $this->_message_timeout) {
			array_push($vesting_orders,$admin_job_number);
			return $this->set_vesting_orders($orders_id, join(',',$vesting_orders));
		}
	}
	
}
?>