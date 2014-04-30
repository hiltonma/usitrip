<?php
/**
 * 用imap的方式接收服务器的邮件。
 * 由于收邮件有可能会非常耗时，所以调用的时候，请注意
 * @author lwkai by 2012-09-29 16:24
 *
 */
 
 require 'mail_get_content_abstract.php';
class mail_get_content_imap extends mail_get_content_abstract {
	
	/**
	 * mail_receive_imap类的实例
	 * @var mail_receive_imap
	 */
	private $get_mail_obj = null;
	
	/**
	 * 用IMAP方式收取邮件。
	 * @param string $server IMAP服务器地址
	 * @param string $user 登录用户名  
	 * @param string $pass 登录密码
	 * @param int $port IMAP端口 (默认143)
	 * @param int $time_out 超时时间(秒)默认5秒
	 */
	public function __construct($server, $user, $pass, $port=143, $time_out=5,$debug = false) {
		if (!empty($server) && !empty($user) && !empty($pass)) {
			$this->get_mail_obj = new mail_receive_imap($server, $user, $pass, $port, $time_out);
			$this->get_mail_obj->set_debug($debug);
		}
	}
	
	/**
	 * 接收未读邮件
	 */
	public function receive(){
		$end_num = 0;
		$this->get_mail_obj->connection();
		$unseen_num = $this->get_mail_obj->get_unseen();
		if ($unseen_num > 0) {
			$end_num = $unseen_num;
			$unseen_arr = $this->get_mail_obj->get_unseen_identifier();
			foreach ($unseen_arr as $key => $val) {
				$temp = $this->get_mail_obj->get_mail($val);
				//$this->get_mail_obj->unseen($val);//@ todo 这里设置邮件未读 测试用，过后，需要注释掉这句
				if (is_array($temp)) {
					$len = count($this->mail_arr);
					$this->mail_arr[$len]['content'] = $temp;
					$this->mail_arr[$len]['identifier'] = $val; // 保存邮件的标识符
					// 生成文件名
					$filename = md5($val);
					// 随机取一个
					$this->mail_arr[$len]['name'] = $filename;
				}
			}
		}
		$this->get_mail_obj->Close();
		return $end_num;
	}
	
	public function set_unseen($num){
		$unseen_num = $this->get_mail_obj-> unseen($num);
	}
}
?>