<?php
/**
 * 根据订单ID获取行程信息与订单总价格，并以字符串返回，方便发邮件用
 * by lwkai create 2012-06-01
 *
 */
class c_send_mail {
	/**
	 * order 类的引用
	 * @var Class order
	 */
	private $order = null;
	
	/**
	 * 需要返回的邮件文字内容
	 * @var String
	 */
	private $mail_string = '';
	
	/**
	 * 订单ID号
	 * @var int
	 */
	private $orders_id = 0;
	
	/**
	 * 构造函数 初始化时需要传入订单ID
	 * @param int $orders_id 订单ID [可选]
	 * @param string $tpl 需要使用的邮件模板 [可选]目前没用到
	 */
	public function __construct($orders_id = 0, $tpl=''){
		$this->orders_id = (int)$orders_id;
		$this->init();
	}
	
	/**
	 * 设置订单号
	 * @param int $orders_id
	 */
	public function set_orders_id($orders_id){
		$this->orders_id = $orders_id;
	}
	
	/**
	 * 初始化  引用 order 类
	 * @throws Exception 如果订单ID号为零，则抛出异常。
	 */
	private function init() {
		if($this->orders_id > 0){
			if (class_exists('order') == false) {
				require DIR_WS_CLASSES . 'order.php';
			}
			$this->order = new order($this->orders_id);
		} else {
			throw new Exception('send_mail类设置订单号错误！订单号不能为零！');
		}
	}
	
	/**
	 * 取得对应订单线路详情
	 */
	private function get_line_details(){
		$temp = $this->order->products;
		//print_r($temp);
		$this->mail_string .= '-----------------------------------------------------------------------------------------------------------' . "\n";
		for ($k = 0, $len = count($temp); $k < $len; $k++) {
			$this->mail_string .= '线路名称：' . $temp[$k]['name'] . "\n";
			$this->mail_string .= '旅游团号：' . $temp[$k]['model'] . "\n";
			$this->mail_string .= '出发日期：' . $temp[$k]['products_departure_date'] . "\n";
			$this->mail_string .= $temp[$k]['products_room_info'] . "\n"; //房间信息
			// 如果没有出发地点 ，由出发时间也没有
			if (tep_not_null($temp[$k]['products_departure_location'])) {
				$dtime = explode(' ', $temp[$k]['products_departure_time']);   //出发时间
				$this->mail_string .= '出发地点：' . $dtime[1] . ' ' . $temp[$k]['products_departure_location'] . "\n";   //出发地点
			}
			$this->mail_string .= "\n";
		}
	}

	/**
	 * 取得对应订单的总价格
	 */
	private function get_total_price(){
		$temp = $this->order->totals;
		$this->mail_string .= '-----------------------------------------------------------------------------------------------------------' . "\n";
		foreach($temp as $key => $val) {
			$this->mail_string .= $val['title'] . $val['text'] . "\n";
		}
	}
	
	/**
	 * 取得订单的行程与总价组合成字符串并返回
	 * @return string
	 */
	public function getString() {
		$this->mail_string .= '订单详情：' . $this->orders_id . "\n";
		$this->get_line_details();
		$this->get_total_price();
		
		return $this->mail_string;
		
	}
}

?>