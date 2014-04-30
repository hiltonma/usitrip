<?php
/**
 * 签证的邀请函处理
 * @author lwkai by 2012-10-10 17:57
 * @package 签证的邀请函处理
 */
class visa_invitation_print {
	
	/**
	 * 订单产品ID
	 * @var int
	 */
	private $orders_products_id = 0;
	
	/**
	 * 订单号
	 * @var int
	 */
	private $orders_id = 0;
	
	/**
	 * 产品ID
	 *
	 * @var int
	 */
	private $products_id = 0;
	
	/**
	 * 当前订单产品的总价格
	 *
	 * @var float
	 */
	private $products_price = 0;
	/**
	 * 签证模板文件地址
	 *
	 * @var string
	 */
	private $template_file = 'email_tpl/visa_invitation.tpl.html';
	
	/**
	 * 参团人员出生日期护照号码国籍数组。
	 * 姓名，性别，出生日期，护照号码，国籍
	 *
	 * @var array
	 */
	private $guest = array();
	
	/**
	 * 邮件的地址
	 *
	 * @var ArrayObject;
	 */
	private $mail_array = array();
	
	/**
	 * 处理订单中邀请函。
	 * 如果不传$orders_products_id则必须调用 set_orders_products_id来设置。
	 * 对应的是orders_products表的orders_products_id
	 *
	 * @param int $orders_products_id
	 *        	订单产品ID[可选]
	 */
	public function __construct($orders_products_id = 0) {
		$this->set_orders_products_id($orders_products_id);
	}
	
	/**
	 * 设置唯一标识符
	 *
	 * @param string $uuid 传入的uuid
	 */
	function getEmailArray() {
		return $this->mail_array;
	}
	
	/**
	 * 设置订单产品ID
	 *
	 * @param int $products_id
	 *        	产品ID
	 */
	public function set_orders_products_id($orders_products_id) {
		if ((int)$orders_products_id > 0) {
			$this->orders_products_id = $orders_products_id;
		}
	}
	
	/**
	 * 设置邀请函模板文件路径。
	 * 注意需要绝对物理路径。
	 *
	 * @param string $file        	
	 */
	public function set_template_file($file) {
		if (isset($file) && ! empty($file)) {
			if (file_exists($file)) {
				$this->template_file = $file;
			}
		}
	}
	
	/**
	 * 设置客人的生日，护照号码，国籍资料。
	 * 如果第一个参数传进来是以数组形式，则后面的参数，不再需要。否则所有参数缺一不可。
	 * 数组形式
	 * array(
	 * 	'参团人ID'=>array(
	 * 		'dob'=>'出生日期',
	 * 		'passport_number'=>'护照号码',
	 * 		'nationality'=>'国籍',
	 * 		'money'=>'金额',
	 * 		'guest_name'=>'参团人姓名',
	 * 		'email'=>'参团人邮箱',
	 * 		'sex'=>'参团人性别')
	 * 	[,'参团人ID'=>array(
	 * 		'dob'=>'出生日期',
	 * 		'passport_number' => '护照号码',
	 * 		'nationality'=>'国籍'
	 * 		...
	 * )'[,...]])
	 *
	 * @param array 参团信息数组
	 * @throws Exception 如果用户传进来的日期无效，则抛出异常
	 */
	public function set_user_dob($guest_id) {
		$this->guest = array();
		if (is_array($guest_id)) {
			foreach ( $guest_id as $key => $val ) {
				if (strtotime($val['dob']) === false) {
					throw new Exception('这不是一个有效的日期');
				}
				$this->guest[$key]['dob'] = date('Y-m-d', strtotime($val['dob']));
				$this->guest[$key]['passport_number'] = $val['passport_number'];
				$this->guest[$key]['nationality'] = $val['nationality'];
				$this->guest[$key]['money'] = $val['money'];
				$this->guest[$key]['is_send'] = 1;
				$this->guest[$key]['guest_name'] = $val['guest_name'];
				$this->guest[$key]['e_mail'] = $val['email'];
				$this->guest[$key]['guest_gender'] = $val['sex'];
				$this->mail_array[$val['email']] = $val['guest_name'];
			}
		}
	}

	/**
	 * 获取旅游开始结束日期
	 */
	private function _travel_period() {
		$sql = "select `products_id`,`products_departure_date`,`final_price` from `orders_products` where `orders_products_id`='" . $this->orders_products_id . "'";
		$result = tep_db_query($sql);
		$row = tep_db_fetch_array($result);
		$rtn = array();
		if ($row) {
			$rtn['start'] = $row['products_departure_date'];
			$rtn['end'] = tep_get_products_end_date($row['products_id'], $rtn['start']);
			$this->products_price = $row['final_price'];
		}
		return $rtn;
	}
	
	/**
	 * 获取当前订单产品的订单号和产品ID
	 *
	 * @throws Exception 如果订单产品ID为零，则抛出异常
	 */
	private function _get_ordersid_productsid() {
		if ($this->orders_products_id == 0) {
			throw new Exception('订单产品ID不能为零，请设置订单产品ID！');
		}
		$sql = "select `orders_id`,`products_id` from `orders_product_eticket` where `orders_products_id`='" . $this->orders_products_id . "'";
		$result = tep_db_fetch_array(tep_db_query($sql));
		if ($result) {
			$this->orders_id = $result['orders_id'];
			$this->products_id = $result['products_id'];
		}
	}
	
	/**
	 * 获得行程天数信息列表
	 */
	private function get_day_list() {
		if ($this->products_id == 0) {
			$this->_get_ordersid_productsid();
		}
		$sql = "select `travel_name`,`travel_hotel`,`travel_index` from `products_travel` where `products_id`='" . $this->products_id . "' order by `travel_index` asc";
		$result = tep_db_query($sql);
		$rtn = array();
		while ( $row = tep_db_fetch_array($result) ) {
			$rtn[] = $row;
		}
		return $rtn;
	}
	
	/**
	 * 读取模板文件
	 */
	private function read_file() {
		if (file_exists($this->template_file)) {
			return file_get_contents($this->template_file);
		}
	}
	
	/**
	 * 根据性别返回对应的Ms或者Mr或者性别不等于男也不等于女时，返回空
	 *
	 * @param string $gender        	
	 */
	private function gender_prefix($gender) {
		$rtn = '';
		if (! empty($gender)) {
			if (trim($gender) == '男') {
				$rtn = 'Mr.';
			} elseif (trim($gender) == '女') {
				$rtn = 'Ms.';
			} else {
				$rtn = '';
			}
		}
		return $rtn;
	}
	
	/**
	 * 把信息和模板整合，并返回整合后的内容
	 *
	 * @return string
	 * @throws Exception 如果模板文件内容为空！则抛出异常
	 */
	public function doit($email='') {
		$html = $this->read_file();
		if (! empty($html)) {
			// 先取得行程的开始结束日期
			$dates = $this->_travel_period();
			// 获得产品ID和订单号
			$this->_get_ordersid_productsid();
			// 获得行程信息列表
			$day_list_temp = $this->get_day_list();
			$day_list = '';
			$i = 0;
			// 组合行程信息为一个字符串
			foreach ( $day_list_temp as $key => $val ) {
				$day_list .= 'DAY ' . $val['travel_index'] . ' (' . date('m/d', strtotime(date('Y-m-d H:i:s', strtotime($dates['start'])) . ' +' . $i . 'days')) . ') ' . $val['travel_name'] . ' <br />';
				$day_list .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hotel:' . $val['travel_hotel'] . '<br />';
				$i ++;
			}
			// 取模板中需要循环的地方
			if (preg_match('/<!--\s+BEGIN list_start\s*-->(.*)\s*<!--\s*END list_start\s*-->/ism', $html, $matchs)) {
				$list_start = $matchs[1];
				$html = preg_replace('/<!--\s*BEGIN\s+list_start\s*-->(.*)<!--\s*END\s+list_start\s*-->/ism', '{list_start}', $html);
			}
			$list_start_handle = '';
			$name_all = '';
			$len = count($this->guest);
			if ($len == 0) { // 如果调用此方法的时候，还没获取名称数据，则调用guest_name取得用户信息
				$this->createGuest($email);
				$len = count($this->guest);
			}
			foreach ( $this->guest as $key => $value ) {
				$i = 0;
				$mr = $this->gender_prefix($value['guest_gender']);
				if (($i + 1) == $len && $i != 0) {
					$name_all .= ' and ' . $mr . $value['guest_name'];
				} else {
					$name_all .= $mr . $value['guest_name'] . '&nbsp;';
				}
				$temp = str_replace(
						array('{name}','{date}','{passportnumber}','{nationality}'), 
						array($mr . $value['guest_name'],date('M.d<\s\up>S</\s\up>Y', strtotime($value['dob'])),$value['passport_number'],$value['nationality']), 
						$list_start
				);
				$list_start_handle .= $temp;
				$i ++;
			}
			if ($len > 1) {
				$is_have = ' have';
			} else {
				$is_have = ' has';
			}
			$date_start = date('M.d<\s\up>S</\s\up>Y', strtotime($dates['start']));
			$date_end = date('M.d<\s\up>S</\s\up>Y', strtotime($dates['end']));
			$money=$this->getMoney();
			if (empty($money)) {
				$money = '';
			} else {
				$money = 'COST : $ ' . $money;
			}
			$html = str_replace('{list_start}', $list_start_handle, $html);
			$html = str_replace(
					array('{start_date}','{end_date}','{orders_id}','{send_date}','{day_list}','{name_all}','{name_have}','{money}'), 
					array($date_start,$date_end,$this->orders_id,date('m/d/Y'),$day_list,$name_all,$is_have,$money), 
					$html
			);
			return db_to_html($html);
		} else {
			throw new Exception('模板文件异常，内容为空!请检查！');
		}
	}
	/**
	 * 检查是否存在发送记录
	 * @param string $guest_id
	 */
	function checkHave($guest_id) {
		$str_sql = 'select orders_product_eticket_guest_id from orders_product_eticket_guest where orders_products_id=' . $this->orders_products_id . ' and guest_id="' . $guest_id . '"';
		$sql_query = tep_db_query($str_sql);
		$row = tep_db_fetch_array($sql_query);
		return $row;
	}
	/**
	 * 统计所有的金额
	 * @return float
	 */
	function getMoney(){
		foreach($this->guest as $value){
			$money+=$value['money'];
		}
		return $money;
	}
	/**
	 * 添加邀请函信息到数据库
	 */
	function addInvitationToDb() {
		$inset_id = '';
		$send_time = date('Y-m-d H:i:s');
		foreach ( $this->guest as $key => $val ) {
			$data = array(
					'guest_id' => $key,
					'guest_name' => $val['guest_name'],
					'orders_products_id' => $this->orders_products_id,
					'guest_dob' => $val['dob'],
					'passport_number' => $val['passport_number'],
					'nationality' => $val['nationality'],
					'guest_gender' => $val['guest_gender'],
					'send_time' => $send_time,
					'money' => $val['money'],
					'is_send' => $val['is_send'],
					'guest_email' => $val['e_mail'],
					'guest_gender' => $val['guest_gender']
			);
			if ($need_id = $this->checkHave($key)) {
				
				$need_id_id = $need_id['orders_product_eticket_guest_id'];
				$where = ' orders_product_eticket_guest_id =' . $need_id_id;
				$action = 'update';
				$data = tep_db_prepare_input($data);
				tep_db_perform('orders_product_eticket_guest', $data, $action, $where);
				$inset_id .= ',' . $need_id_id;
			} else {
				
				$where = '';
				$action = 'insert';
				$data = tep_db_prepare_input($data);
				tep_db_perform('orders_product_eticket_guest', $data, $action, $where);
				$inset_id .= ',' . tep_db_insert_id();
			}
		}
		$inset_id = substr($inset_id, 1);
		$str_sql = 'update orders_product_eticket_guest set connet_id_str="' . $inset_id . '" where orders_product_eticket_guest_id in(' . $inset_id . ')';
		// echo $str_sql;
		tep_db_query($str_sql);
	}
	/**
	 * 前台页面通过用户邮件，orders_product_id，查找相应的邀请函信息
	 *
	 * @param int $opid	orders_product_id
	 * @param string $email	用户邮件
	 */
	function getInvitation($opid, $email) {
		$str_sql = 'select connet_id_str from orders_product_eticket_guest where orders_products_id=' . ( int ) $opid . ' and guest_email="' . $email . '"';
		$sql_query = tep_db_query($str_sql);
		$rows = tep_db_fetch_array($sql_query);
		if ($rows) {
			$data = array();
			$str_sql = 'select * from orders_product_eticket_guest where orders_product_eticket_guest_id in(' . $rows['connet_id_str'] . ')';
			$sql_query = tep_db_query($str_sql);
			while ( $rows = tep_db_fetch_array($sql_query) ) {
				$this->guest[] = $rows;
			}
			return;
		} else
			return;
	}
	/**
	 * 获取一个客户的邀请函信息
	 * @param string $guest_name 客户名称
	 * @return multitype:
	 */
	function getOneInvitation($guest_id) {
		if (is_numeric($guest_id)) {
			$str_sql = 'select * from orders_product_eticket_guest where orders_products_id=' . $this->orders_products_id . ' and guest_id="' . $guest_id . '"';
			$sql_query = tep_db_query($str_sql);
			$row = tep_db_fetch_array($sql_query);
			return $row;
		}
	}
	/**
	 * 后台返回客户的信息，用于创建列表
	 * @return multitype:unknown multitype:unknown number  Ambigous <>
	 */
	function getGuestName() {
		$sql = "select `guest_name`,`guest_gender`,guest_email from `orders_product_eticket` where `orders_products_id`='" . $this->orders_products_id . "'";
		$result = tep_db_query($sql);
		while ( $row = tep_db_fetch_array($result) ) {
			$temp = explode('<::>', $row['guest_name']);
			$gender = explode('<::>', $row['guest_gender']);
			$email_arr = explode('<::>', $row['guest_email']);
			for($i = 0, $len = count($temp); $i < $len - 1; $i ++) {
				if (preg_match('/([^\[]+)\]/', $temp[$i], $matchs)) {
					//$this->guest[$i]['guest_name'] = $matchs[1];
					if ($row = $this->getOneInvitation($i)) {
						$rtn[] = array(
								'guest_id' => $row['guest_id'],
								'guest_name' => $row['guest_name'],
								'dob' => $row['guest_dob'],
								'passport_number' => $row['passport_number'],
								'nationality' => $row['nationality'],
								'is_send' => $row['is_send'],
								'money' => $row['money'],
								'sex' => $gender[$i],
								'e_mail' => empty($email_arr[$i]) ? $row['guest_email'] : $email_arr[$i] 
						);
					} else {
						$rtn[] = array(
								'guest_id' => $i,
								'guest_name' => $matchs[1],
								'e_mail' => $email_arr[$i] 
						);
					}
				}
				//$this->guest[$i]['guest_gender'] = $gender[$i];
			}
		}
		return $rtn;
	}
	/**
	 * 用户登录查看邀请函的时候创建客户信息
	 * @param string $guest_email 用户邮箱地址
	 */
	function createGuest($guest_email) {
		$str_sql = "select connet_id_str from orders_product_eticket_guest where orders_products_id=" . $this->orders_products_id . ' and guest_email="' . $guest_email . '" order by send_time desc';
		//echo $str_sql;
		$sql_query = tep_db_query($str_sql);
		$row = tep_db_fetch_array($sql_query);
		if ($row) {
			$str_sql = 'select * from orders_product_eticket_guest where orders_product_eticket_guest_id in(' . $row['connet_id_str'] . ')';
			$sql_query = tep_db_query($str_sql);
			while($row=tep_db_fetch_array($sql_query)){
				$this->guest[$row['guest_id']]['guest_name'] = $row['guest_name'];
				$this->guest[$row['guest_id']]['guest_gender'] = $row['guest_gender'];
				$this->guest[$row['guest_id']]['dob'] = $row['guest_dob'];
				$this->guest[$row['guest_id']]['passport_number'] = $row['passport_number'];
				$this->guest[$row['guest_id']]['nationality'] = $row['nationality'];
				$this->guest[$row['guest_id']]['money'] = $row['money'];
				
			}
		}
	}
}