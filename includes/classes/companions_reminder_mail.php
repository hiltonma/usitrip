<?php 
/**
 * 结伴同游催款邮件 [参与人邮件与发起人邮件]
 * @author lwkai 2012-06-29
 *
 */
class companions_reminder_mail extends send_mail_ready{
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
	 * 发起人的邮箱地址
	 * @var unknown_type
	 */
	private $customer_address = '';
	
	/**
	 * 用户自己的语言编码
	 * @var string
	 */
	private $customers_char_set = 'gb2312';
	
	/**
	 * 接收邮件人的ID
	 * @var int
	 */
	private $customers_id = 0;
	
	/**
	 * 结伴同游催款邮件和取消订单邮件
	 * @param int $customers_id 收接邮件的人的ID
	 * @param int $orders_id 订单ID
	 * @param int $type 催款邮件还是取消订单邮件 1 催款 2 取消
	 * @param string $action_type 'true'|'false'
	 */
	public function __construct($customers_id,$orders_id,$type = 1,$action_type='true'){
		$this->travel_companion_email_switch = 'true';
		parent::__construct();
		$this->action_type = $action_type;
		$this->order_id =  (int)$orders_id;
		$this->customers_id = (int)$customers_id;
		$this->get_customers_info();
		switch ($type) {
			case 1: // 发送催款邮件
				$this->order_info_content = $this->init();
				if ($this->to_email_address == $this->customer_address) { // 如果是自己给自己 则表示是发给发起人的，这时候需要发送另一封邮件。其实内容都一样，只是和谁结伴不一样而已
					//echo '$this->to_email_address=' . $this->to_email_address . '<br/>$this->from_email_address' . $this->customer_address . '<br/>';
					$this->customer_name = join(',',$this->get_together_name());
					//return ;
				}
				// 引入算价格的类
				require_once DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'companions_personal_pay.php';
				$this->personal_pay = new companions_personal_pay($this->order_id);
				
				$this->init_mail();
				break;
			case 2:
				$this->init(false);
				if ($this->to_email_address == $this->customer_address) { // 如果是自己给自己 则表示是发给发起人的，这时候需要发送另一封邮件。其实内容都一样，只是和谁结伴不一样而已
					//echo '$this->to_email_address=' . $this->to_email_address . '<br/>$this->from_email_address' . $this->customer_address . '<br/>';
					$this->customer_name = join(',',$this->get_together_name());
					//return ;
				}
				$this->cancel_mail();
				break;
			default:
				return;
		}

		$this->send_mail();
	}
	
	/**
	 * 取消订单邮件内容
	 */
	private function cancel_mail(){
		$this->mail_subject = "走四方结伴同游--订单取消" . " ";#订单号：" . $this->order_id . "日期:" . date('Y-m-d H:i:s') . " ";
		$this->mail_content = '尊敬的『' . $this->to_name . '』您好：'."\n\n";
		$this->mail_content .= '非常感谢您预订美国走四方(Usitrip.com)的旅游产品！' . "\n";
			
		$links = tep_href_link('orders_travel_companion_info.php','order_id=' . $this->order_id);
		$links = str_replace('/admin/','/',$links);
			
		$this->mail_content .= '您和『' . $this->customer_name . '』的结伴同游订单[订单号：' . $this->order_id . ']';
		$this->mail_content .= '由于一直未收到全部款项，订单已被自动取消。' . "\n";
		$lines = $this->get_products_name_url();
		foreach ($lines as $key => $val) {
			$this->mail_content .= '线路是：<a href="' . str_replace('/admin/','/',tep_href_link($val['products_urlname']) . '.html') . '" target="_blank">' . $val['products_name'] . '</a>' . "\n";
		}
		$this->mail_content .= '若您还需要订购行程，请重新在走四方<a href="http://208.109.123.18/" target="_blank">208.109.123.18</a>上选择订购，若有问题请您及时联系走四方客服人员，谢谢。' . "\n";
		$this->mail_content .= '点此连接查看被取消的订单<a href="'.$links.'" target="_blank">' . $links . '</a>。（注：如果点击打不开链接，请复制该地址到浏览器地址栏打开。）' . "\n\n";
		

		$this->mail_content .= "此邮件为系统自动发出，请勿直接回复！\n\n";
		$this->mail_content .= $this->mail_foot . "\n\n";
	}

	/**
	 * 根据订单产品ID取得线路名称与URL
	 * @return array
	 */
	private function get_products_name_url(){
		$sql = tep_db_query("select products_id from orders_travel_companion where orders_id='" . $this->order_id . "'");
		while($rows = tep_db_fetch_array($sql)){
			$products_id[]=$rows['products_id'];
		}
		$products_id = join(',',$products_id);
		if ( ! $products_id) $products_id = '0';
		$sql = tep_db_query("select p.products_urlname,pd.products_name from products as p,products_description as pd where p.products_id=pd.products_id and p.products_id in (" . $products_id . ") and p.is_hotel=0");
		$rtn = array();
		while($rows = tep_db_fetch_array($sql)) {
			$rtn[] = $rows;
		}
		return $rtn;
		
	}
	
	/**
	 * 催款邮件正文
	 */
	private function init_mail(){
		$this->mail_subject = "走四方结伴同游--请及时付款" . " ";#订单号：" . $this->order_id . "日期:" . date('Y-m-d H:i:s') . " ";
		$this->mail_content = '尊敬的『' . $this->to_name . '』您好：'."\n\n";
		$this->mail_content .= '非常感谢您预订美国走四方(Usitrip.com)的旅游产品！' . "\n";
			
		$links = tep_href_link('orders_travel_companion.php','order_id=' . $this->order_id);
		$links = str_replace('/admin/','/',$links);
			
		$this->mail_content .= '您和『' . $this->customer_name . '』的结伴同游订单';
		$this->mail_content .= '<a href="'.$links.'" target="_blank">' . $links . '</a>,款项还一直未支付，请您尽快去支付。若您已经支付成功，请您联系走四方工作客服人员。（注：如果点击打不开链接，请复制该地址到浏览器地址栏打开。）' . "\n\n";
	
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
	
	/**
	 * 发送邮件
	 */
	private function send_mail(){
		//if (in_array($this->to_email_address, array('2683692314@qq.com','1804690595@qq.com','1773247305@.qq.com'))){
		//echo $this->to_name . '<br/>';
		//echo $this->to_email_address . '<br/>';
		tep_mail(
			iconv(CHARSET,$this->customers_char_set.'//IGNORE',$this->to_name), 
			$this->to_email_address, 
			iconv(CHARSET,$this->customers_char_set.'//IGNORE',$this->mail_subject), 
			iconv(CHARSET,$this->customers_char_set.'//IGNORE',$this->mail_content), 
			iconv(CHARSET,$this->customers_char_set.'//IGNORE',$this->from_email_name), 
			$this->from_email_address, 
			'true', 
			$this->customers_char_set);
		//}
	}
	
	/**
	 * 根据当前用户ID 取得收件人的姓名、邮箱地址和用户习惯的编码
	 */
	private function get_customers_info(){
		$customers = tep_db_query("select customers_firstname, customers_email_address,customers_char_set from customers where customers_id = '" . (int)$this->customers_id . "'");
		$data = tep_db_fetch_array($customers);
		if (count($data) > 0) {
			$this->to_name = $data['customers_firstname'];
			$this->to_email_address = $data['customers_email_address'];
			if(tep_not_null($data['customers_char_set']) == true){
				$this->customers_char_set = $data['customers_char_set'];
			}
		}
	}
	
	/**
	 * 根据收件人用户ID获取结伴者的用户名称
	 */
	private function get_together_name(){
		$sql = tep_db_query("select guest_name from orders_travel_companion where orders_id='" . $this->order_id . "' and customers_id <> '" . $this->customers_id . "' and is_child <> 'true' group by customers_id");
		$rtn = array();
		while ($temp = tep_db_fetch_array($sql)) {
			if (preg_match("/[^\[]+\[([^\]]+)/", $temp['guest_name'],$matchs)) {
				$rtn[] = $matchs[1];
			}
		}
		return $rtn;
	}
	
	// 下面是取得订单详情 用来发邮件的部分
	
	/**
	 * 初始化  引用 order 类
	 * @param boolean $return 是否有返回订单详情内容 默认是返回
	 * @return string
	 */
	private function init($return = true) {
			if($this->order_id > 0){
				if (class_exists('order') == false) {
					require_once DIR_FS_CATALOG . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'order.php';
				}
				$this->order = new order($this->order_id);
			}

		$this->customer_name = $this->order->customer['name'];
		$this->customer_address = $this->order->customer['email_address'];
		if ($return == true) {
			return $this->getString();
		}
	}
	
	/**
	 * 取得对应订单线路详情
	 * @return string
	 */
	private function get_line_details(){
		$temp = $this->order->products;
		$mail_string = '';
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
		$mail_string = '订单详情：订单号[' . $this->order_id . "]&nbsp;&nbsp;（您可以使用预订时注册的Email登录您的帐户来查询订单详情）\n";
		$mail_string .= $this->get_line_details();
		$mail_string .= $this->get_total_price();
	
		return $mail_string;
	
	}
}
?>