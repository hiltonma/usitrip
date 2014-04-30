<?php
/**
 * 订单详细页edit_orders.php发送短信类！
 * @package 
 */
class sendajaxsms{
	public function __construct(){

	}
	/**
	 * 订单详细页面发送短信
	 * @param string $phones 手机号码
	 * @param string $content 短信内容
	 * @param string $orders_id 订单号
	 * @param string $login_id 发送人的登录id
	 * @param Assessment_Score $score_obj 考核积分对象
	 */
	public function send_sms($phones, $content, $orders_id, $login_id, Assessment_Score $score_obj){
		include_once(DIR_FS_DOCUMENT_ROOT.'includes/classes/cpunc_sms.php');
		include_once(DIR_FS_DOCUMENT_ROOT.'includes/classes/ensms.php');
		include_once('includes/classes/PickUpSms.class.php');	//要用到此类的部分功能
		
		if(preg_match('/'.preg_quote('[当前订单旅客发送短信]').'/',CPUNC_USE_RANGE) && !empty($content) && !empty($orders_id)){
			if(!$phones){	//无传号码过来时
				$sql = 'select o.guest_emergency_cell_phone, c.confirmphone, c.customers_cellphone, c.customers_mobile_phone, o.customers_telephone, c.customers_telephone as CTEL from orders o, customers c where o.orders_id = "'.$orders_id.'" and o.customers_id = c.customers_id ';
				$resu = tep_db_query($sql);
				$row_customer_info = tep_db_fetch_array($resu);
				$allphone = implode(',',$row_customer_info);
			}else{	//有传号码时
				$allphone = tep_db_output($phones);
			}

			$phone = '';
			$added_score = false;	//员工考核积分是否已经添加
			$all_phones = preg_split('/['.preg_quote('\|:;').']+|[,]+|[\/]+/', $allphone);
			$sms_content = iconv('utf-8', 'GB2312//IGNORE', $content);
			$json = array();
			if($cn_phones = PickUpSms::filter_phones($all_phones,'cn')){	//发中国手机
				$phone = $cn_phones[0];	//只发第一个有效电话
				//echo $phone.'CN';
				$cnSms = new cpunc_SMS;
				$cpunc_sms_history_id = (int)($cnSms->SendSMS($phone, width_half_to_full($sms_content)));	//将字母转全角后发
				if($cpunc_sms_history_id){
					$orders_sms_history_id = $this->add_orders_sms_history($orders_id, 'cpunc_sms_history', 'cpunc_sms_history_id', $cpunc_sms_history_id, $login_id,$sms_content,$phones);	//记录历史索引表
					if($added_score === false){	//只加一次积分给发信人
						$added_score === true;
						$this->add_score($score_obj, $login_id, $orders_id, $orders_sms_history_id);
					}
					$json['CN'] = 1;
				}
			}
			if ($usa_phones = PickUpSms::filter_phones($all_phones,'us')){	//发美国手机
				$phone = $usa_phones[0];	//只发第一个有效电话
				//echo $phone.'USA';
				$usSms = new ensms;
				if($msg_id = $usSms->addMsg($sms_content,'gb2312')->send($phone)){
					$orders_sms_history_id = $this->add_orders_sms_history($orders_id, 'cpunc_sms_hi8d_history', 'msg_id', $msg_id, $login_id,$sms_content,$phones);	//记录历史索引表
					if($added_score === false){	//只加一次积分给发信人
						$added_score === true;
						$this->add_score($score_obj, $login_id, $orders_id, $orders_sms_history_id);
					}
					$json['US'] = 1;
				}
			}
		}

		if(!$json) $json['error']='1';
		return json_encode($json);
	}
	/**
	 * 记录订单详细页面的短信发送历史
	 * @param int $orders_id 订单id
	 * @param string $table_name 详细短信历史记录的表名如cpunc_sms_hi8d_history或cpunc_sms_history
	 * @param string $key_id_name 关键id字段名称
	 * @param int $key_id_value 关键id值
	 * @param int $admin_id 发送人id
	 * @param int $msm_content 发送内容
	 * @param int $mobile_phone 电话号码
	 */
	private function add_orders_sms_history($orders_id, $table_name = 'cpunc_sms_hi8d_history', $key_id_name='msg_id', $key_id_value='1', $admin_id,$msm_content,$mobile_phone){
		$data = array();
		$data['orders_id'] = (int)$orders_id;
		$data['table_name'] = $table_name;
		$data['key_id_name'] = $key_id_name;
		$data['key_id_value'] = (int)$key_id_value;
		$data['added_date'] = date('Y-m-d H:i:s');
		$data['admin_id'] = (int)$admin_id;
		$data['sms_content'] = $msm_content;
		$data['sms_phone'] = $mobile_phone;
		$data = tep_db_prepare_input($data);
		tep_db_perform('orders_sms_history',$data);
		return tep_db_insert_id();
	}
	/**
	 * 给短信发送人添加考核积分
	 * @param Assessment_Score $score 考核积分类
	 * @param int $login_id 管理员id
	 * @param int $oID 订单id
	 * @param int $orders_sms_history_id 订单处理手机短信息发送记录的id值
	 */
	private function add_score(Assessment_Score $score, $login_id, $oID, $orders_sms_history_id){
		if($score->checkLoginOwner($oID))
		$score->add_pending_score($login_id, 1, tep_get_admin_customer_name($login_id).'发送通知短信给客户！系统自动加+1分。', 'orders_sms_history_id', $orders_sms_history_id, '1', CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID, 1, $oID);
	}
}


?>