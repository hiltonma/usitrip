<?php
/**
 * 检测当前用户是否有权限打印邀请函
 * @author lwkai 2012-10-24 15:38
 * @link <1275124829@163.com> lwkai
 * @package 打印邀请函
 */
class visa_invitation {
	/**
	 * 当前订单产品ID[orders_products_id]
	 * @var int
	 */
	private $opid = 0;
	
	/**
	 * 当前登录用户ID
	 * @var int
	 */
	private $customers_id = 0;
	
	/**
	 * 当前订单产品中所有参团人员的邮箱地址
	 * @var array
	 */
	private $participate_email_address = array();
	
	/**
	 * 检测当前登录用户是否有权限打印邀请函
	 * @param int $opid 订单产品ID[Orders_Products_id]
	 * @param int $customers_id 当前登录的用户ID
	 * @throws Exception 如果没有权限，则抛出错误
	 */
	public function __construct($opid,$customers_id = 0) {
		$this->opid = (int)$opid;
		$this->customers_id = (int)$customers_id;
	}
	
	/**
	 * 检测当前用户是否有权打印该邀请函。
	 * @return boolean
	 */
	public function check() {
		$rtn = true;
		if ((int)$this->opid && (int)$this->customers_id) {
			// 对当前登录的用户进行检测，是否是下单人自己
			//$customer_id_check = $this->checkCustomer(); 、
			/* 有可能发送的邀请函收件箱中没有下单人的下单帐号邮箱地址，此时用下单人邮箱地址登进来查看，会不显示用户的护照号待信息。
			 * 所以这里不再判断是否是下单人，统一去参团人列表中查，是否是发送给当前登录的这个邮箱，如果不是，不让他看。 
			 */
			//if ($customer_id_check != true) {
				$cur_email = $this->getCurCustomersAddress();
				// 财务要查看发送是否正确，这里直接判断是否是财务登录查看，赋给他查看权
				if ($cur_email == '2355652793@qq.com') {
					
				} else {
					// 是否是参与人登录打印邀请函
					$check = $this->checkEmailByParticipate($cur_email);
					if ($check == false) {
						$rtn = false;
					}
				}
			//}
			// 检测邀请函状态是否可用
			$check = $this->checkInvitationStatus();
			if ($check == false) {
				$rtn = false;
				//throw new Exception('对不起！您无权查看该邀请函！');
			}
		} else {
			$rtn = false;
			//throw new Exception('对不起！您无权查看该邀请函！');
		}
		return $rtn;
	}
	
	/**
	 * 获取参团人员的邮箱与用户名
	 * @return array('邮箱'=>'用户名'[,...]):
	 */
	public function getParticipateEmail(){
		if (empty($this->participate_email_address)) {
			$this->getParticipateEmailAddress();
		}
		return $this->participate_email_address;
	}
	
	/**
	 * 获取下单人的邮箱地址
	 * @return array('邮箱地址'=>'用户名称');
	 */
	public function getCustomersEmail() {
		$email = array();
		//@todo 需要获取用户名称的英文名，这里只获取了邮箱，还需要再改进
		$sql = "select o.customers_id,o.customers_name,o.customers_email_address from orders_product_eticket as ope,orders as o where o.orders_id = ope.orders_id and ope.orders_products_id='" . $this->opid . "'";
		$rs = tep_db_fetch_array(tep_db_query($sql));
		if ($rs != false) {
			if (empty($rs['customers_email_address'])) {
				$mail = tep_get_customers_email($rs['customers_id']);
				$email[$mail] = $rs['customers_name'];
			} else {
				$email[$rs['customers_email_address']] = $rs['customers_name'];
			}
		}
		return $email;
	}
	
	/**
	 * 设置邀请函已经发送状态
	 * @param int $admin_job_number 发送邀请函的人的工号
	 */
	public function isSendMail($admin_job_number) {
		if ((int)$admin_job_number > 0) {
			$sql = "update orders_product_eticket set visa_invitation_send_date = CONCAT(visa_invitation_send_date,'@" . date('Y-m-d H:i:s') . '|' . $admin_job_number . "') where orders_products_id='" . $this->opid . "'";
			tep_db_query($sql);
		}
	}
	
	/**
	 * 返回发送邮件的时间与发送人的工号
	 * @param int $opid 订单产品ID
	 * @return array(array('date'=>'发送日期时间','job_number'=>'发送人工号')[,array('date'=>'发送日期时间','job_number'=>'发送人工号')[,...]])
	 */
	public static function getSendMailDateJobNumber($opid){
		$rtn = array();
		if ((int)$opid > 0) {
			$sql = "select visa_invitation_send_date from orders_product_eticket where orders_products_id='" . (int)$opid . "'";
			$rs = tep_db_fetch_array(tep_db_query($sql));
			if ($rs != false) {
				$temp = trim($rs['visa_invitation_send_date'],'@');
				if (!empty($temp)) {
					$temp = explode('@', $temp);
					foreach ($temp as $key => $val) {
						$_temp = explode('|',$val);
						$rtn[] = array('date'=>$_temp[0],'job_number'=>$_temp[1]);
					}
				}
			}
		}
		return $rtn;
	}
	
	/**
	 * 获取当前登录用户的EMAIL地址并返回
	 * @return string
	 */
	private function getCurCustomersAddress() {
		return tep_get_customers_email($this->customers_id);
	}
	
	/**
	 * 获取当前产品的参团人员邮箱
	 */
	private function getParticipateEmailAddress() {
		$this->participate_email_address = array();
		$sql = "select orders_id,guest_name,guest_email from orders_product_eticket where orders_products_id='" . $this->opid . "'";
		$result = tep_db_query($sql);
		$rs = tep_db_fetch_array($result);
		if ($rs != false) {
			$temp = rtrim($rs['guest_email'],'<::>');
			$temp_name = rtrim($rs['guest_name'],'<::>');
			
			if (!empty($temp)) {
				$temp = explode('<::>',$temp);
				$temp_name = explode('<::>',$temp_name);
				$i = 0;
				foreach($temp as $key=>$val) {
					$this->participate_email_address[trim($val)] = $temp_name[$i];
					$i++;
				}
			}
		}
	}
	
	/**
	 * 检测传进来的邮箱地址是否在参团人邮箱地址中存在，存在返回TRUE，否则FALSE
	 * @param string $email 需要检测的邮箱地址
	 * @return boolean
	 */
	private function checkEmailByParticipate($email) {
		$rtn = false;
		if (!empty($email)) {
			if (empty($this->participate_email_address)) {
				$this->getParticipateEmailAddress();
			}
			if (!empty($this->participate_email_address)) {
				$fined = array_key_exists(trim($email), $this->participate_email_address);
				if ($fined !== false) {
					$rtn = true;
				}
			}
		}
		return $rtn;
	}
	
	/**
	 * 检测该邀请函是否可用
	 * @return boolean
	 */
	private function checkInvitationStatus(){
		$rtn = false;
		$sql = "select visa_invitation_send_date from orders_product_eticket where orders_products_id='" . $this->opid . "'";
		$result = tep_db_fetch_array(tep_db_query($sql));
		if ($result != false) {
			if (!empty($result['visa_invitation_send_date'])) {
				$rtn = true;
			}
		}
		return $rtn;
	}
	
	/**
	 * 检测当前用户是否与下订单用户一致，如果是下单人登录，则返回TRUE，否则FLASE
	 * @return boolean
	 */
	private function checkCustomer() {
		$rtn = false;
		$sql = "select o.customers_id from orders_product_eticket as opt,orders as o where o.orders_id = opt.orders_id and opt.orders_products_id='" . $this->opid . "'";
		$result = tep_db_fetch_array(tep_db_query($sql));
		if ($result != false) {
			if (isset($_SESSION['customer_id']) && $_SESSION['customer_id'] == $result['customers_id']) {
				$rtn = true;
			}
		}
		return $rtn;
	}
}