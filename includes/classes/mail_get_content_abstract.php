<?php
/**
 * 收邮件抽象类
 * @pagepack
 * @author lwkai 2012-9-29 16:24
 */
abstract class mail_get_content_abstract {
	
	/**
	 * 保存收到的邮件内容
	 * @var array
	 */
	protected $mail_arr = array();
	
	/**
	 * 从服务器接收未读邮件
	 */
	abstract public function receive();
	
	/**
	 * 取得接收回来的邮件内容
	 * @return array
	 */
	public function get_mail() {
		return $this->mail_arr;
	}
}
?>