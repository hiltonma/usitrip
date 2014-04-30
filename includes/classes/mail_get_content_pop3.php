<?php
/**
* 
*/
class mail_get_content_pop3 extends mail_get_content_abstract {
	
	/**
	 * mail_receive_imap类的实例
	 * @var mail_receive_imap
	 */
	private $get_mail_obj = null;
	
	/**
	 * 保存收到的所有邮件序号列表。有可能有很多邮件需要收取，保存在这里方便第二次调用读取，不用再去服务器取此数据
	 * @var array
	 */
	private $mail_list = NULL;
	
	/**
	 * 用POP3方式收取邮件。
	 * @param string $server POP3服务器地址
	 * @param string $user 登录用户名  
	 * @param string $pass 登录密码
	 * @param int $port POP3端口 (默认110)
	 * @param int $time_out 超时时间(秒)默认5秒
	 */
	public function __construct($server, $user, $pass, $port=110, $time_out=5,$debug = false) {
		if (!empty($server) && !empty($user) && !empty($pass)) {
			$this->get_mail_obj = new mail_receive_pop3($server, $user, $pass, $port, $time_out);
			$this->get_mail_obj->set_debug($debug);
		}
	}
	
	/**
	 * 接收未读邮件
	 */
	public function receive(){
		$end_num = 0;
		//$this->get_mail_obj->connection();
		$mail_list = $this->get_mail_obj->get_list();
		$this->mail_list = $mail_list;
		if (is_array($mail_list)) {
			$end_num = count($mail_list);
			$mail_list = array_reverse($mail_list);
			//$unseen_arr = $this->get_mail_obj->get_unseen_identifier();
			foreach ($mail_list as $key => $val) {
				$temp = $this->get_mail_obj->get_mail($val['num']);
				$identifier = $this->get_mail_obj->get_identifier($val['num']);
				$identifier = $identifier[1];
				//$this->get_mail_obj-> unseen($val);//@ todo 这里设置邮件未读 测试用，过后，需要注释掉这句
				if (is_array($temp)) {
					$len = count($this->mail_arr);
					$this->mail_arr[$len]['content'] = $temp;
					$this->mail_arr[$len]['identifier'] = $identifier; // 保存邮件的标识符
					// 生成随机文件名
					$filename = md5($identifier);
					// 随机取一个
					$this->mail_arr[$len]['name'] = $filename;
				}
			}
		}
		$this->get_mail_obj->Close();
		return $end_num;
	}
	
	public function set_unseen($num){
		throw new Exception('对不起！POP3方式暂不支持设置邮件为未读！');
	}
}
?>