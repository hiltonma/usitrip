<?php 
/**
 * 把邮件处理好，放入SESSION准备发送
 * 结合AJAX来发送 这里只放入SESSION 并不进行真正的发送
 * @author lwkai 2012-06-11
 *
 */
abstract class send_mail_ready{

	/**
	 * 收件人姓名
	 * @var string
	 */
	protected $to_name = '';

	/**
	 * 收件人邮箱地址
	 * @var string
	 */
	protected $to_email_address = '';

	/**
	 * 邮件标题
	 * @var string
	 */
	protected $mail_subject = '';

	/**
	 * 邮件内容
	 * @var string
	 */
	protected $mail_content = '';

	/**
	 * 邮件以谁的名义发送
	 * @var string
	 */
	protected $from_email_name = '';

	/**
	 * 发送邮件的邮箱地址
	 * @var string
	 */
	protected $from_email_address = '';

	/**
	 * 邮件分隔符
	 * @var unknown_type
	 */
	protected $mail_separator = EMAIL_SEPARATOR;

	/**
	 * 邮件页脚共用信息
	 * @var string
	 */
	protected $mail_foot = CONFORMATION_EMAIL_FOOTER;

	/**
	 * 邮件以纯文本发送还是HTML true 表示html邮件
	 * @var string 'true|false'
	 */
	protected $action_type = 'true';

	/**
	 * 构造函数 初始化 发送名义与发送邮箱地址
	 */
	public function __construct(){
		$this->from_email_name = (defined("STORE_OWNER") ? STORE_OWNER : '');
		$this->from_email_address = (defined("STORE_OWNER_EMAIL_ADDRESS") ? STORE_OWNER_EMAIL_ADDRESS : '');
	}

	/**
	 * 添加进SESSION 以待发送
	 */
	protected function add_session(){
		$a_i = count($_SESSION['need_send_email']);
		

		$_SESSION['need_send_email'][$a_i]['to_name'] = db_to_html($this->to_name);
		
		$_SESSION['need_send_email'][$a_i]['to_email_address'] = $this->to_email_address;
		
		$_SESSION['need_send_email'][$a_i]['email_subject'] = db_to_html($this->mail_subject);

		$_SESSION['need_send_email'][$a_i]['email_text'] = db_to_html($this->mail_content);
		
		$_SESSION['need_send_email'][$a_i]['from_email_name'] = db_to_html($this->from_email_name);
		
		$_SESSION['need_send_email'][$a_i]['from_email_address'] = $this->from_email_address;
		
		$_SESSION['need_send_email'][$a_i]['action_type'] = $this->action_type;

		/*$string = '';
		$string .= 'to_name:' . $this->to_name . "\n";
		$string .= 'to_email_address:' . $this->to_email_address . "\n";
		$string .= 'email_subject:' . $this->mail_subject . "\n";
		$string .= 'email_text:' . $this->mail_content . "\n";
		$string .= 'from_email_name:' . $this->from_email_name . "\n";
		$string .= 'from_email_name:' . $this->from_email_name . "\n";
		$string .= 'from_email_address:' . $this->from_email_address . "\n";
		
		$string .= 'action_type:' . $this->action_type . "\n";
		$this->save_to_file($string);*/
		
	}
	
	private function save_to_file($somecontent = ''){
		$filename = DIR_FS_CATALOG . 'test.txt';
		$somecontent .= file_get_contents($filename);
		file_put_contents($filename,$somecontent);
		
	}
}
?>