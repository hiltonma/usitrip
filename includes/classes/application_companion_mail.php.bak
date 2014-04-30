<?php
/**
 * 结伴同游有人申请
 * @author lwkai 2012-06-11
 *
 */
class application_companion_mail extends companion_mail{

	/**
	 * 当前申请人的ID
	 * @var int
	 */
	private $app_customers_id = 0;

	/**
	 * 申请信息保存的记录ID
	 * @var int
	 */
	private $application_id = 0;
	/**
	 * 结伴同游有人申请
	 * @param int $companion_id 结伴发起贴ID
	 * @param int $customer_id 申请用户ID
	 * @param int $app_id 申请信息记录的ID
	 * @param string $action_type 邮件类型 'true'|'false' true 为 html邮件 false为文本邮件
	 */
	public function __construct($companion_id,$customer_id,$app_id,$action_type = 'true'){
		$this->action_type = $action_type;
		$this->companion_id = $companion_id;
		$this->app_customers_id = $customer_id;
		$this->application_id = $app_id;
		$this->travel_companion_email_switch = 'true';
		if ($this->travel_companion_email_switch == 'true') {
			$this->get_companion_user_info();
			if ($this->customers_id != $this->app_customers_id) {
				parent::__construct();
				$this->get_products_info();
				$this->init_mail();
				$this->add_session();
			}
		}
	}

	private function init_mail(){
		$this->mail_subject = '走四方结伴同游—有人申请 ';
		$this->mail_content = '尊敬的 ' . $this->to_name . "\n";
		$tTcPath = tep_get_category_patch($this->categories_id);
		$this->mail_content .= '您发起的名称为 『' . $this->companion_title . '』的结伴同游,有新的人员申请。' . "\n" .
				'线路是：<a href="' . tep_href_link($this->products_urlname) . '" target="_blank">' . $this->products_name . '</a>' . "\n" .
				'点此连接查看<a href="' . tep_href_link('my_travel_companion.php','action=my_sent') . '" target="_blank">' . tep_href_link('my_travel_companion.php','action=my_sent') .
				'</a> 注：如果点击打不开，请复制该连接到浏览器地址栏打开。' . "\n\n";
		
		$this->mail_content .= $this->mail_separator . "\n";
		$this->mail_content .= '申请人的资料如下：'."\n";
		$this->mail_content .= $this->get_app_info();
		$this->mail_content .= $this->mail_separator . "\n\n";
		$this->mail_content .= $this->mail_foot;
	}

	/**
	 * 取得申请人的申请信息
	 * @param int $app_id 申请人申请信息
	 * @return string
	 */
	private function get_app_info(){
		$reply_sql = tep_db_query("SELECT  tca_cn_name,tca_en_name,tca_gender,tca_email_address,tca_phone,tca_people_num,tca_content FROM travel_companion_application WHERE tca_id=" . (int)$this->application_id);
		$reply_row = tep_db_fetch_array($reply_sql);
		$rtn_html = '姓名：'.$reply_row['tca_cn_name'].' ['.$reply_row['tca_en_name'].']'."\n";
		$rtn_html .= '性别：'.($reply_row['tca_gender']=="2" ? '女士' : '先生')."\n";
		$rtn_html .= '电子邮箱：'.$reply_row['tca_email_address']."\n";
		$rtn_html .= '电话：'.$reply_row['tca_phone']."\n";
		$rtn_html .= '人数：'.$reply_row['tca_people_num']."\n";
		$rtn_html .= '留言内容：'.tep_db_output($reply_row['tca_content'])."\n";
		return $rtn_html;
	}
}


?>