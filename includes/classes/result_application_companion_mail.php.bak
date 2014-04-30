<?php 
/**
 * 申请结伴同游结果邮件
 * @author lwkai 2012-6-28
 *
 */
class result_application_companion_mail extends companion_mail{
	/**
	 * 结伴同游发起人姓名
	 * @var string
	 */
	private $sponsor_name = '';
	
	/**
	 * 结伴同游发起人性别
	 * @var string
	 */
	private $sponsor_gender = '';
	
	/**
	 * 结伴同游发起人邮箱地址
	 * @var string
	 */
	private $sponsor_email = '';
	
	/**
	 * 结伴同游发起人电话
	 * @var string
	 */
	private $sponsor_tel = '';
	
	/**
	 * 结伴同游现有人数
	 * @var int
	 */
	private $sponsor_people = 0;
	
	/**
	 * 结伴同游个人备注
	 * @var string
	 */
	private $sponsor_message = '';
	
	/**
	 * 取消通过的申请时，留的言
	 * @var unknown_type
	 */
	private $canceled_message = '';
	
	/**
	 * 申请记录的ID号
	 * @var int
	 */
	private $tca_id = 0;
	
	/**
	 * 同意、拒绝、取消 结伴同游申请
	 * status 只能是 agree,refuse,canceled
	 * @param int $tca_id 申请记录ID
	 * @param string $status 处理结果状态[agree 同意 ，refuse 拒绝，canceled 取消]
	 */
	public function __construct($tca_id,$status){
		$this->tca_id = $tca_id;
		$this->get_customers_info();
		$this->get_companion_user_info();
		$this->get_products_info();
		switch($status){
			case 'agree':
				$this->agree_mail();
				break;
			case 'refuse':
				$this->refuse_mail();
				break;
			case 'canceled':
				$this->canceled_mail();
				break;
			default:
				return;
				break;
		}
		$this->travel_companion_email_switch = 'true';
		parent::__construct();
		$this->add_session();
	}
	/**
	 * 同意申请邮件
	 */
	private function agree_mail(){
		$this->mail_subject = '走四方结伴同游—申请通过 ';
		$this->mail_content = '尊敬的 ' . $this->to_name . "\n";
		$tTcPath = tep_get_category_patch($this->categories_id);
		$this->mail_content .= '恭喜！你向 『' . $this->sponsor_name . '』申请名称为 『' . $this->companion_title . '』的结伴同游,申请已通过。' .
				'线路是：<a href="' . tep_href_link($this->products_urlname) . '" target="_blank">' . $this->products_name . '</a>' . "\n" .
				'点此连接查看<a href="' . tep_href_link('my_travel_companion.php','action=my_applied') . '" target="_blank">' . tep_href_link('my_travel_companion.php','action=my_applied') .
				'</a> 注：如果点击打不开，请复制该连接到浏览器地址栏打开。' . "\n\n";
		
		$this->mail_content .= $this->mail_separator . "\n";
		$this->mail_content .= '发起人的资料如下：'."\n";
		$this->mail_content .= '姓名：' . $this->sponsor_name . "\n";
		$this->mail_content .= '性别：' . $this->sponsor_gender . "\n";
		$this->mail_content .= '电子邮箱：' . $this->sponsor_email . "\n";
		$this->mail_content .= '电话：' . $this->sponsor_tel . "\n";
		$this->mail_content .= '人数：' . $this->sponsor_people . "\n";
		$this->mail_content .= '留言内容：' . $this->sponsor_message . "\n";
		$this->mail_content .= $this->mail_separator . "\n\n";
		$this->mail_content .= $this->mail_foot;
	}
	
	/**
	 * 拒绝申请邮件
	 */
	private function refuse_mail(){
		$this->mail_subject = '走四方结伴同游—申请未通过 ';
		$this->mail_content = '尊敬的 ' . $this->to_name . "\n";
		$tTcPath = tep_get_category_patch($this->categories_id);
		$this->mail_content .= '很遗憾！你向 『' . $this->sponsor_name . '』申请名称为 『' . $this->companion_title . '』的结伴同游,未被对方接爱或者对方已经有了同游者。' . "\n" .
				'走四方建议您查阅其他同游信息，或者自行发布一条,'.
				'线路是：<a href="' . tep_href_link($this->products_urlname) . '" target="_blank">' . $this->products_name . '</a>' . "的结伴同游贴 。\n" .
				'点此连接查看<a href="' . tep_href_link('my_travel_companion.php','action=my_applied') . '" target="_blank">' . tep_href_link('my_travel_companion.php','action=my_applied') .
				'</a> 注：如果点击打不开，请复制该连接到浏览器地址栏打开。' . "\n\n";
		
		$this->mail_content .= $this->mail_separator . "\n\n";
		$this->mail_content .= $this->mail_foot;
	}
	
	/**
	 * 取消同意邮件
	 */
	private function canceled_mail(){
		$this->mail_subject = '走四方结伴同游—申请被取消 ';
		$this->mail_content = '尊敬的 ' . $this->to_name . "\n";
		$tTcPath = tep_get_category_patch($this->categories_id);
		$this->mail_content .= '很抱歉！你向 『' . $this->sponsor_name . '』申请名称为 『' . $this->companion_title . '』的结伴同游,申请被取消通过。' .
				'走四方建议您查阅其他同游信息，或者自行发布一条，线路是：<a href="' . tep_href_link($this->products_urlname) . '" target="_blank">' . $this->products_name . '</a>的结伴同游贴。' . "\n" .
				'点此连接查看<a href="' . tep_href_link('my_travel_companion.php','action=my_applied') . '" target="_blank">' . tep_href_link('my_travel_companion.php','action=my_applied') .
				'</a> 注：如果点击打不开，请复制该连接到浏览器地址栏打开。' . "\n\n";
		
		$this->mail_content .= $this->mail_separator . "\n";
		$this->mail_content .= '发起人给您的留言内容如下：'."\n";
		$this->mail_content .= $this->canceled_message . "\n";
		$this->mail_content .= $this->mail_separator . "\n\n";
		$this->mail_content .= $this->mail_foot;
	}
	
	/**
	 * 覆写父类方法  获取发起人的相关信息
	 * (non-PHPdoc)
	 * @see companion_mail::get_companion_user_info()
	 */
	protected function get_companion_user_info(){
		//print_r('SELECT customers_id,customers_name,email_address,categories_id,t_companion_title FROM `travel_companion` WHERE t_companion_id="' . $this->companion_id . '" Limit 1 ');
		$mail_sql = tep_db_query('SELECT customers_id,t_gender,customers_phone,now_people_man,personal_introduction,customers_name,email_address,categories_id,t_companion_title,products_id FROM `travel_companion` WHERE t_companion_id="' . $this->companion_id . '" Limit 1 ');
		$mail_row = tep_db_fetch_array($mail_sql);
		$this->customers_id = $mail_row['customers_id'];
		$this->sponsor_name = strip_tags($mail_row['customers_name']) . " ";
		$this->sponsor_email = strip_tags($mail_row['email_address']);
		$this->sponsor_people = ($mail_row['now_people_man'] + $this->get_agree_application());
		$this->sponsor_message = $mail_row['personal_introduction'];
		$this->categories_id = $mail_row['categories_id'];
		$this->companion_title = $mail_row['t_companion_title'];
		$this->products_id = $mail_row['products_id'];
		switch($mail_row['t_gender']){
			case '1':
				$this->sponsor_gender = '男士';
				break;
			case '2':
				$this->sponsor_gender = '女士';
				break;
			default:
				$this->sponsor_gender = '未知';
				break;
		}
	}
	
	/**
	 * 根据申请记录ID取得申请人信息
	 * 中文姓名
	 * 发起贴ID
	 * 邮箱地址
	 * 通过申请后再取消时的留言
	 */
	private function get_customers_info(){
		$sql = tep_db_query("select tca_cn_name,t_companion_id,tca_email_address,verify_status_sms from travel_companion_application where tca_id='" . $this->tca_id . "'");
		$temp = tep_db_fetch_array($sql);
		$this->to_name = $temp['tca_cn_name'];
		$this->companion_id = $temp['t_companion_id'];
		$this->to_email_address = $temp['tca_email_address'];
		$message = explode(':',$temp['verify_status_sms']);
		$this->canceled_message = $message[1];
	}
	
	/**
	 * 取得已经同意申请的人数
	 * @return int
	 */
	private function get_agree_application(){
		$sql = tep_db_query("select count(tca_id) as t from travel_companion_application where t_companion_id='" . $this->companion_id . "' and tca_verify_status = 1");
		$temp = tep_db_fetch_array($sql);
		return $temp['t'];
	}
}
?>