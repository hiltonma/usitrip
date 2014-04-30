<?php 
/**
 * 结伴同游发起人下单后给参与人发邮件
 * 请申请结伴的人支付费用邮件
 * @author lwkai 2012-06-11
 *
 */
class companion_under_order_mail extends send_mail_ready{

	/**
	 * 订单详情内容
	 * @var string
	 */
	private $order_info_content = '';
	/**
	 * 订单ID号
	 * @var int
	 */
	private $order_id = 0;

	/**
	 * order 类的引用
	 * @var Class order
	 */
	private $order = null;
	
	/**
	 * companions_personal_pay 类的引用
	 * @var Class companions_personal_pay
	 */
	private $personal_pay = null;
	/**
	 * 下订单的用户名称
	 * @var string
	 */
	private $customer_name = '';

	/**
	 * eg:
	 * @param string|array $to_email_address 可以是一个或者多个需要发送的邮箱帐号
	 * @param unknown_type $order_id 订单ID号
	 * @param order $order order类的实例
	 * @param unknown_type $action_type
	 */
	public function __construct($to_email_address,$order_id,$order = null,$action_type = 'true'){
		parent::__construct();
		$this->action_type = $action_type;
		$this->order_id =  (int)$order_id;
		$this->order_info_content = $this->init($order);
		
		// 引入算价格的类
		require_once DIR_FS_CLASSES . 'companions_personal_pay.php';
		$this->personal_pay = new companions_personal_pay($this->order_id);
		
		if (is_array($to_email_address) === true) {
			foreach($to_email_address as $person => $mail){
				$this->morePerson($mail, $person);
			}
		} else {
			$person_arr = explode(',',$to_email_address);
			foreach ($person_arr as $key => $mail){
				$person = $this->getCustomersName($mail);
				$this->morePerson($mail, $person);
			}
		}
	}
	
	/**
	 * 根据用户邮箱地址获取用户名称
	 * @param string $mail
	 * @return string
	 */
	private function getCustomersName($mail){
		return tep_get_customer_name_from_email($mail);
	}
	
	
	/** 
	 * 对多人发送邮件
	 * @param unknown_type $email
	 * @param unknown_type $toPerson
	 */
	private function morePerson($email,$toPerson){

		$this->to_name = $toPerson;
		$this->to_email_address = $email;
		$this->init_mail();
		$this->add_session();
	}

	/**
	 * 组合邮件
	 */
	private function init_mail(){
		$this->mail_subject = "走四方结伴同游--等待付款！" . " ";
		$this->mail_content = '尊敬的『' . $this->to_name . '』您好：'."\n\n";
		$this->mail_content .= '非常感谢您预订美国走四方(Usitrip.com)的旅游产品！' . "\n";
			
		$links = tep_href_link('orders_travel_companion_info.php','order_id=' . $this->order_id ,'SSL');
		$links = str_replace('/admin/','/',$links);
			
		$this->mail_content .= '您参与的结伴同游，发起人『' . $this->customer_name . '』已下了订单，请打开下面的链接支付您的订单款项，走四方在此订单完全支付成功后会尽快确认订单！' . "\n";
		$this->mail_content .= '<a href="'.$links.'" target="_blank">' . $links . '</a>注：如果点击打不开链接，请复制该地址到浏览器地址栏打开。' . "\n\n";

		$this->mail_content .= $this->order_info_content;
		
		// 获取当前收件人需要支付的款项
		$this->mail_content .= $this->personal_pay->getCustomersPay($this->to_email_address) . "\n";
		$this->mail_content .= '<span style="color:#f00">注：上述款项不包括小孩费用！</span>' . "\n";
		// 获取当前订单中需要付的小孩费用，由找不到小孩的归属人。所以每个邮件里都列出了当前订单的所有小孩的费用。
		$this->mail_content .= $this->personal_pay->getChildPay() . "\n";
		
		$this->mail_content .= $this->mail_separator . "\n";
		
		$this->mail_content .= "此邮件为系统自动发出，请勿直接回复！\n\n";
		$this->mail_content .= $this->mail_foot . "\n\n";
	}

	// 下面是取得订单详情 用来发邮件的部分

	/**
	 * 初始化  引用 order 类
	 * @return string
	 */
	private function init($order) {
		if (is_object($order)){
			$this->order = $order;
		} else {
			if($this->order_id > 0){
				if (class_exists('order') == false) {
					require_once DIR_FS_CLASSES . 'order.php';
				}
				$this->order = new order($this->order_id);
			}
		}
		$this->customer_name = $this->order->customer['name'];
		return $this->getString();
	}

	/**
	 * 取得对应订单线路详情
	 * @return string
	 */
	private function get_line_details(){
		$temp = $this->order->products;
		$mail_string = '订单号：' . $this->order_id . "\n";
		//print_r($temp);
		$mail_string .= $this->mail_separator . "\n";
		for ($k = 0, $len = count($temp); $k < $len; $k++) {
			if ($temp[$k]['is_hotel'] != '1'){
				$mail_string .= '线路名称：' . $temp[$k]['name'] . "\n";
				$mail_string .= '旅游团号：' . $temp[$k]['model'] . "\n";
				$mail_string .= '出发日期：' . date('Y-m-d',strtotime($temp[$k]['products_departure_date'])) . "\n";
			} else {
				$mail_string .= '酒店名称：' . $temp[$k]['name'] . "\n";
				$mail_string .= '酒店编号：' . $temp[$k]['model'] . "\n";
				$mail_string .= '入住日期：' . date('Y-m-d',strtotime($temp[$k]['products_departure_date'])) . "\n";
				$mail_string .= '退房日期：' . date('Y-m-d',strtotime($temp[$k]['hotel_checkout_date'])) . "\n";
			}

			$mail_string .= $temp[$k]['products_room_info'] . "\n"; //房间信息
			// 如果没有出发地点 ，则出发时间也没有
			if (tep_not_null($temp[$k]['products_departure_location'])) {
				$dtime = explode(' ', $temp[$k]['products_departure_time']);   //出发时间
				$mail_string .= '出发地点：' . $dtime[1] . ' ' . $temp[$k]['products_departure_location'] . "\n";   //出发地点
			}
			$mail_string .= "\n";
		}
		return $mail_string;
	}

	/**
	 * 取得对应订单的总价格
	 * @return string
	 */
	private function get_total_price(){
		$temp = $this->order->totals;
		$mail_string = $this->mail_separator . "\n";
		foreach($temp as $key => $val) {
			$mail_string .= $val['title'] . $val['text'] . "\n";
		}
		return $mail_string;
	}

	/**
	 * 取得订单的行程与总价组合成字符串并返回
	 * @return string
	 */
	public function getString() {
		$mail_string = '订单详情：' . $this->orders_id . "&nbsp;&nbsp;（您可以使用预订时注册的Email登录您的帐户来查询订单详情）\n";
		$mail_string .= $this->get_line_details();
		$mail_string .= $this->get_total_price();

		return $mail_string;

	}
}

?>