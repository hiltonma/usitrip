<?php 
/**
 * 结伴同游有人回贴
 * @author lwkai 2012-06-11
 *
 */
class reply_companion_mail extends companion_mail{
	/**
	 * 当前回复人的ID
	 * @var int
	 */
	private $reply_customers_id = 0;

	/**
	 * 回复贴的回复内容
	 * @var string
	 */
	private $reply_content = '';

	/**
	 * 构造函数
	 * @param int $companion_id 结伴同游主题贴ID
	 * @param int $customer_id 当前回复人的用户ID
	 * @param int $reply_id 当前回复的贴子ID
	 * @param string $action_type 以什么方式发邮件 'true' HTML 'false' 纯文本
	 */
	public function __construct($companion_id,$customer_id,$reply_id,$action_type = 'true'){
		$this->action_type = $action_type;
		$this->companion_id = (int)$companion_id;
		$this->reply_customers_id = $customer_id;
		// 结伴同游回复开关
		$this->travel_companion_email_switch = TRAVEL_COMPANION_RE_EMAIL_SWITCH;
		if ($this->travel_companion_email_switch == 'true') {
			$this->get_companion_user_info();
			if ($this->customers_id != $this->reply_customers_id) {
				parent::__construct();
				$this->get_products_info($this->products_id);
				$this->get_companion_reply($reply_id);
				$this->init_mail();
				$this->add_session();
			}
		}
	}

	/**
	 * 组合邮件内容。
	 */
	private function init_mail(){
		$this->mail_subject = '走四方结伴同游—有人回帖 ';
		$tTcPath = tep_get_category_patch($this->categories_id);
		$this->mail_content = '尊敬的 ' . $this->to_name . "\n";
		$this->mail_content .= '您发起的名称为 『' . strip_tags($this->companion_title) . "』 的结伴同游，有人回复了一条新内容！";
		$this->mail_content .= '线路是：<a href="' . $this->products_urlname . '" target="_blank">' . $this->products_name . "</a>\n";
		$this->mail_content .= '点此连接查看：<a href="' . tep_href_link('new_bbs_travel_companion_content.php', 'TcPath=' . $tTcPath . '&t_companion_id=' . $this->companion_id) . '" target="_blank">'
				. tep_href_link('new_bbs_travel_companion_content.php', 't_companion_id=' . $this->companion_id) . '</a> 注：如果点击打不开，请复制连接，粘贴到浏览器地址栏中打开。' . "\n\n";
		$this->mail_content .= '回帖人的资料如下：'."\n";

		$customer_info = tep_get_customers_info($this->reply_customers_id);
		$this->mail_content .= '姓名：'.$customer_info['customers_firstname']."\n";
		$this->mail_content .= '电子邮箱：'.$customer_info['customers_email_address']."\n";
		$this->mail_content .= $this->mail_separator . "\n";
		$this->mail_content .= '回帖的内容：' . "\n" . nl2br(tep_db_output($this->reply_content)) . "\n";
		$this->mail_content .= $this->mail_separator . "\n\n";

		$this->mail_content .= $this->mail_foot;
	}

	/**
	 * 取得回复贴的内容
	 * @param int $reply_id 回复贴插入后的ID
	 */
	private function get_companion_reply($reply_id){
		$reply_sql = tep_db_query("SELECT  t_c_reply_content FROM travel_companion_reply WHERE t_c_reply_id=" . (int)$reply_id);
		$reply_row = tep_db_fetch_array($reply_sql);
		$this->reply_content = $reply_row['t_c_reply_content'];
	}
}

?>