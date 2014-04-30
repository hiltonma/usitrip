<?php 
/**
 * 根据订单ID获取个人所需要付的款项
 * 
 * @author lwkai 2012-06-20
 *
 */
class companions_personal_pay{

	/**
	 * 用户需要付的款
	 * eg: array('10045'=>'450','600004'=>'15200');
	 * array('用户ID'=>'需付款金额')
	 * @var array
	 *
	 */
	private $customers_pay = array();

	/**
	 * 当前订单号
	 * @var int
	*/
	private $orders_id = 0;

	/**
	 * 订单付款原始记录数据 [数据库中读取回来的结果集数组]
	 * @var array
	 */
	private $orders_data = array();

	/**
	 * 根据订单ID取得个人秘需要要付的款
	 * @param int $orders_id 订单ID号
	*/
	public function __construct($orders_id){
		if (is_numeric($orders_id) == true) {
			$this->orders_id = $orders_id;
			$this->getOrders();
		}
	}

	/**
	 * 取得当前订单所有用户的数据
	 */
	private function getOrders(){
		$orders_sql = tep_db_query("select products_id,customers_id,guest_name,is_child,payables,paid from orders_travel_companion where orders_id='" . $this->orders_id . "'");
		if (tep_db_num_rows($orders_sql) > 0){
			while (false !== ($row = tep_db_fetch_array($orders_sql))){
				$this->orders_data[] = $row;
			}
		}
	}

	/**
	 * 格式化数据
	 */
	private function formatData(){
		$child = array();
		$rtn = array();
		foreach($this->orders_data as $key => $val) {
			if ($val['is_child'] === 'true') {
				$child[$val['guest_name']] = $child[$val['guest_name']] + ($val['payables'] - $val['paid']);
			} else {
				$rtn[$val['customers_id']]['needpay'] = $rtn[$val['customers_id']]['needpay'] + $val['payables'];
				$rtn[$val['customers_id']]['paid'] = $rtn[$val['customers_id']]['paid'] + $val['paid'];
			}
		}
		foreach($rtn as $key => $val){
			$this->customers_pay[$key] = '您需要付款：$' . number_format($val['needpay'],2);
			if ($val['paid'] > 0) {
				$this->customers_pay[$key] .= '<br/>已付款：$' . number_format($val['paid'],2) . '<br/>还需付款：$' . 
					($val['needpay'] - $val['paid'] > 0 ? number_format($val['needpay'] - $val['paid'],2) : 0) . '<br/>';
			}
		}
		foreach($child as $key => $val){
			$temp .= '小孩：姓名『' . tep_filter_guest_chinese_name($key) . '』,需付款：$' . number_format($val,2) . '<br/>';
		}
		$this->customers_pay['child'] = ($temp == '' ? '' : $temp . '<span style="color:#f00">注：如果不是您的小孩，则不需付对应款项！</span>');

	}

	/**
	 * 取得用户所需要付的款
	 * @param string|int $customers 用户的ID号或者邮箱地址
	 * @return multitype: 用户需要付的钱
	 */
	public function getCustomersPay($customers){
		if (count($this->customers_pay) == 0) {
			$this->formatData();
		}
		if (is_numeric($customers) == false) {
			$customers_id = $this->getCustomersId($customers);
		} else {
			$customers_id = $customers;
		}
		if ((int)$customers_id > 0){
			return $this->customers_pay[$customers_id];
		} else {
			return 0;
		}
	}

	/**
	 * 获得小孩部分需要付的款
	 * @return multitype:
	 */
	public function getChildPay(){
		return $this->customers_pay['child'];
	}

	/**
	 * 根据用户注册邮箱取得用户ID
	 * @param string $mail 用户注册的邮箱地址
	 * @return 用户的ID
	 */
	private function getCustomersId($mail){
		if (tep_not_null($mail) == true) {
			$sql = tep_db_query("select customers_id from customers where customers_email_address='" . $mail . "'");
			if (tep_db_num_rows($sql) > 0){
				$row = tep_db_fetch_array($sql);
				return $row['customers_id'];
			} else {
				return 0;
			}
		}
	}
}
?>