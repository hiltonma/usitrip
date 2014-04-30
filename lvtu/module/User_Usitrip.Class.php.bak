<?php
/**
 * 用户信息类
 * @author lwkai 2013-4-28 上午11:51:56
 *
 */
class User_Usitrip {
	/**
	 * 数据库操作
	 * @var Db_Mysql
	 * @author lwkai 2013-3-4 上午9:57:00
	 */
	static private  $_db = null;
	
	
	private function Db() {
		if (self::$_db == null) {
			self::$_db = Db::get_db('usitrip');
		}
		return self::$_db;
	}
	
	/**
	 * 取得用户部分信息,用户图片，名称，性别，ID
	 * @param int $userid 用户ID
	 * @return array;
	 */
	static public function getUserToComment($userid) {
		$sql = "select customers_face,customers_firstname,customers_id,customers_gender from customers where customers_id='" . intval($userid) . "'";
		$rs = self::Db()->query($sql)->getOne();
		if (ENABLE_SSL) {
			$url = HTTPS_USITRIPURL;
		} else {
			$url = HTTP_USITRIPURL;
		}
		$rs['customers_face'] = empty($rs['customers_face']) ? $url . '/image/touxiang_no-sex.gif' : $url . '/images/face/' . $rs['customers_face'];
		return $rs;
	}
	
	/**
	 * 后台用户登录用
	 * @param string $username 用户名
	 * @param string $password 密码
	 * @author lwkai 2013-5-3 下午4:25:11
	 */
	static public function login($username,$password){
		$sql = "select * from admin where admin_email_address='" . trim($username) . "'";
		$rs = self::Db()->query($sql)->getOne();
		if ($rs) {
			$pas = explode(':',$rs['admin_password']);
			$password = md5($pas[1] . $password);
			if ($password == $pas[0]) {
				$_SESSION['login_id'] = $rs['admin_id'];
				$_SESSION['lvtu_login_id'] = $rs['admin_id'];
				$_SESSION['login_groups_id'] = $rs['admin_groups_id'];
				$_SESSION['login_first_name'] = $rs['admin_publicity_name'];
				$_SESSION['login_firstname'] = $rs['admin_publicity_name'];
				return true;
			}
		}
		return false;
	}
	
}