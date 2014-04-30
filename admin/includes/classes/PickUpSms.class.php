<?php
/**
 * 上传接机导游信息类
 * @package 
 */
class PickUpSms{
	/**
	 * 使用此功能的供应商ID
	 * @var array
	 */
	public static $allows_agency = array(201,209,217,235);
	public function __construct(){
		$this->allows_agency = self::$allows_agency;
	}
	/**
	 * 取得某个供应商今天要发送的接机短信
	 * @param int $agency_id
	 */
	public function get_tody_pick_up_sms($agency_id){
		return $this->get_one_day_pick_up_sms($agency_id, date('Y-m-d'));
	}
	/**
	 * 取得某个供应商昨天发送的接机短信
	 * @param int $agency_id
	 */
	public function get_yesterday_pick_up_sms($agency_id){
		return $this->get_one_day_pick_up_sms($agency_id, date('Y-m-d',strtotime('-1 day')));
	}
	/**
	 * 取得某个供应商某个日期要发送的接机短信
	 * @param int $agency_id
	 * @param date $date
	 */
	public function get_one_day_pick_up_sms($agency_id, $date){
		$sql = tep_db_query('SELECT * FROM `cpunc_sms_pick_up` where agency_id="'.(int)$agency_id.'" and send_date="'.date('Y-m-d',strtotime($date)).'" ');
		$row = tep_db_fetch_array($sql);
		return $row;
	}
	/**
	 * 提交供应商的接机短信内容
	 */
	public function submit_sms($agency_id, $sms_content){
		$today = date('Y-m-d');
		$array = array(
		'agency_id' => (int)$agency_id,
		'send_date' => $today,
		'sms_content'=>tep_db_prepare_input($sms_content)
		);
		if($this->get_tody_pick_up_sms($agency_id)){
			$array['sent_status'] = '0';
			tep_db_perform('cpunc_sms_pick_up',$array,'update','agency_id="'.(int)$agency_id.'" and send_date="'.$today.'" ');
		}else{
			tep_db_perform('cpunc_sms_pick_up',$array);
		}
		return tep_db_affected_rows();
	}
	/**
	 * 发接机短信
	 * @param $agency_phone_numbers 手机号码集array('供应商id'=>array('手机号1','手机号2',...))
	 * @param $country 手机号所在的国家cn中国,us美国，其它的不管
	 */
	public static function send_pick_up_sms($agency_phone_numbers = array(), $country = 'cn'){
		$data = array();
		$date = date('Y-m-d');
		include_once(DIR_FS_DOCUMENT_ROOT.'includes/classes/cpunc_sms.php');
		include_once(DIR_FS_DOCUMENT_ROOT.'includes/classes/ensms.php');
		$cnSms = new cpunc_SMS;
		$usSms = new ensms;
		$return = array();

		foreach ($agency_phone_numbers as $agency_id => $phone_numbers){
			$query = tep_db_query('SELECT * FROM `cpunc_sms_pick_up` where agency_id="'.(int)$agency_id.'" and send_date="'.$date.'" and sent_status=0 ');
			while($rows = tep_db_fetch_array($query)){
				$sms_content = trim($rows['sms_content']);
				if(!$sms_content) continue;
				if($country == 'cn'){	//中国手机用此方法发
					$pn = implode(',',(array)$phone_numbers);
					if($pn){
						$return[] = $cnSms->SendSMS($pn, $sms_content);
					}
				}elseif($country == 'us'){	//美国手机用此方法发
					$pn = implode(',',(array)$phone_numbers);
					if($pn){
						if($usSms->addMsg($sms_content,'gb2312')->send($pn)){
							$return[] = 1;
						}
					}
				}
			}
		}
		return $return;
	}
	/**
	 * 更新短信发送状态
	 * @param int $agency_id
	 * @param dtate $date
	 */
	public static function update_sent_status($agency_id, $date){
		tep_db_query('update cpunc_sms_pick_up set sent_status="1", sent_date_time=now() where agency_id="'.(int)$agency_id.'" and send_date="'.$date.'" and sent_status=0 ');
		return tep_db_affected_rows();
	}
	/**
	 * 发当天的接机短信(用于服务器cron计划任务)
	 */
	public static function cron_send_pick_up_sms(){
		$agency_ids = self::$allows_agency;
		$date = date('Y-m-d');
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		foreach ($agency_ids as $agency_id){
			//取中国手机
			$china_numbers = array($agency_id => self::get_guest_phone($agency_id, $tomorrow, 'cn'));
			//取美国手机
			$us_numbers = array($agency_id => self::get_guest_phone($agency_id, $tomorrow, 'us'));
			//发中国手机
			if($china_numbers){
				self::send_pick_up_sms($china_numbers, 'cn');
			}
			//发美国手机
			if($us_numbers){
				self::send_pick_up_sms($us_numbers, 'us');
			}
			//更新短信发送状态
			self::update_sent_status($agency_id, $date);
		}
		return true;
	}
	/**
	 * 取得某天出团的客户电话信息
	 * @param int $agency_id 供应商id
	 * @param date $date 日期
	 * @param string $country 国家cn中国,us美国
	 * @return array 返回手机号码数组
	 */
	public static function get_guest_phone($agency_id, $date, $country){
		//取得已发电子参团凭证的订单手机号，手机号的格式如下。中国：+861**********(合计14位),美国+1**********(合计12位)
		//$pat = array('cn'=>preg_quote('+861**********'), 'us'=> preg_quote('+1**********') );
		$phones = array();	//待发的手机号
		//条件：需要发接机导游信息、已经发电子参团凭证、不包括子团
		$sql_str = 'SELECT o.guest_emergency_cell_phone, p.agency_id, op.products_departure_date, o.orders_id FROM products p, orders o, orders_products op where op.products_id=p.products_id and op.orders_id=o.orders_id and o.orders_status="100002" and o.need_pick_up_sms="1" and op.parent_orders_products_id="0" group by op.orders_products_id ';
		$sql = tep_db_query($sql_str);
		while ($rows = tep_db_fetch_array($sql)) {
			if(self::get_phone_for_orders_need_pick_up_sms($rows['orders_id'])){	//指定了发送日期和接收手机号码时
				$phones = self::get_phone_for_orders_need_pick_up_sms($rows['orders_id'], $agency_id, $date);
			}elseif($rows['agency_id']==(int)$agency_id && $rows['products_departure_date']==date('Y-m-d 00:00:00',strtotime($date))){	//无指定发送日期和接收的手机号码时
				$cellular_phone = $rows['guest_emergency_cell_phone'];
				if($cellular_phone!=''){
					$phone = NUM_num($cellular_phone);
					$phone = preg_replace('/[[:space:]]+/','',$phone);
					//发现有/,\;等字符就要分割
					$phones = array_merge($phones, preg_split('/['.preg_quote('\|:;').']+|[,]+|[\/]+/', $phone));
				}
			}
		}

		return self::filter_phones($phones, $country);
	}
	/**
	 * 从需要发接机短信的订单表中提取手机信息
	 * @param int $orders_id 订单号
	 * @param int $agency_id 供应商id
	 * @param string $send_date 发送日期
	 * @return array
	 */
	public static function get_phone_for_orders_need_pick_up_sms($orders_id, $agency_id='', $send_date=''){
		$phones = array();
		$where = ' where orders_id="'.(int)$orders_id.'" ';
		if($agency_id){
			$where.= ' and agency_id="'.(int)$agency_id.'" ';
		}
		if($send_date){
			$where.= ' and send_date="'.date('Y-m-d 00:00:00',strtotime($send_date)).'" ';
		}
		$sql = tep_db_query('SELECT mobile_phone FROM `orders_need_pick_up_sms` '.$where);
		while ($rows = tep_db_fetch_array($sql)) {
			$phones[] = $rows['mobile_phone'];
		}
		return $phones;
	}
	/**
	 * 取得某订单的自定义接机信息记录
	 * @param int $orders_id 订单号
	 */
	public static function get_orders_need_pick_up_sms($orders_id){
		$data = array();
		$where = ' where orders_id="'.(int)$orders_id.'" ';
		$sql = tep_db_query('SELECT * FROM `orders_need_pick_up_sms` '.$where);
		while ($rows = tep_db_fetch_array($sql)) {
			$data[] = $rows;
		}
		return $data;
	}
	/**
	 * 添加需要发接机短信的客户手机信息
	 * @param array $post 传入的数组
	 * @return 0|1 若成功返回1，失败返回0
	 */
	public static function record_pick_up_sms(array $post){
		$success = 0;
		tep_db_query('update orders set need_pick_up_sms="1" where orders_id="'.(int)$post['orders_id'].'" ');
		tep_db_query('DELETE FROM orders_need_pick_up_sms where orders_id="'.(int)$post['orders_id'].'" ');
		$data = array();
		if(is_array($post['send_dates']) && $post['send_dates'][0]){
			foreach($post['send_dates'] as $key => $_date){
				$data[] = array('orders_id'=>$post['orders_id'],'admin_id' => $post['admin_id'], 'added_date' => date('Y-m-d H:i:s'), 'send_date' => date('Y-m-d H:i:s', strtotime($_date)), 'agency_id'=>$post['agency_ids'][$key], 'mobile_phone'=>$post['mobile_phones'][$key]);
			}
		}
		for($i=0, $n=sizeof($data); $i<$n; $i++){
			if(tep_db_fast_insert('orders_need_pick_up_sms',$data[$i],'orders_need_pick_up_sms_id')){
				$success = 1;
			}
		}
		return $success;
	}
	/**
	 * 从手机号数组中筛选出某个国家的电话码
	 * @param array $phones 电话号码数组
	 * @param string $country 要取得电话的国家代码cn是中国，us是美国
	 * @return array
	 */
	public static function filter_phones(array $phones,$country='cn'){
		$data = array();
		if($phones){	//筛选中国，美国电话
			foreach ($phones as $phone){
				$ph = preg_replace('/[^\d]+/','',$phone);
				$ph = preg_replace('/^0+/','',$ph);
				if($country == 'cn'){
					if(strlen($ph)==13 && preg_match('/^861/',$ph)){	//中国13位手机号(带国家区号86)
						$data[] = preg_replace('/^861/','1',$ph);	//最后产生11位手机号给亿美的接口用
					}
				}elseif ($country == 'us'){
					if(strlen($ph)==11 && preg_match('/^1/',$ph)){	//美国带国家区号1的11位电话1+3位区域号+7位电话号码，如：12258038765
						$data[] = $ph;	//产生11位美国手机号给兄弟的接口用http://www.hi8d.com
					}
				}
			}
		}
		//过滤重复的号码
		$data = array_unique($data);
		return $data;
	}
	/**
	 * 记录发送日志时间，主要是记录时间方便在cron过程可以查询
	 */
	public static function record_log(){
		$log = date('Y-m-d H:i:s')."done".PHP_EOL;
		$log_flie = DIR_FS_CATALOG.'tmp/pick_up_sms.log';
		if($handle = fopen($log_flie, 'ab')){
			if(flock($handle , LOCK_EX)){
				fwrite($handle, $log);
				flock($handle , LOCK_UN);
			}
			fclose($handle);
		}
		return true;
	}
}
?>