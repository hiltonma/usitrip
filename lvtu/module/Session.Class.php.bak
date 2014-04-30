<?php 
/**
 * 保存SESSION到数据库类
 * @author lwkai 2013-1-8 下午4:29:19
 * @example
 * session_set_save_handler(
 * 		array('Session', 'open'),
 * 		array('Session', 'close'),
 * 		array('Session', 'read'),
 * 		array('Session', 'write'),
 * 		array('Session', 'destroy'),
 * 		array('Session', 'gc')
 * 	);
 * 	if (session_id() == "") session_start();
 */
class Session {
	/**
	 * 数据库操作对象
	 * @var Db_Mysql
	 * @author lwkai 2013-1-8 下午3:16:29
	 */
	private static $_db = null;
	
	/**
	 * SESSION 过期时间
	 * @var int
	 * @author lwkai 2013-1-8 下午3:54:47
	 */
	private static $_session_life = 0;

	
	private static function setDb($db) {
		self::$_db = $db;
		if (!self::$_session_life = get_cfg_var('session.gc_maxlifetime')) {
			self::$_session_life = 1440;
		}
	}
	
	/**
	 * 
	 * 
	 * @author lwkai 2013-1-8 下午4:03:35
	 */
	public static function open() {
		return true;
	}
	
	public static function close() {
		return true;
	}
	/**
	 * 读取SESSION
	 * @param int $id session id
	 * @return string string of the sessoin
	 */
	public static function read($id) {
		self::setDb(Db::get_db('usitrip'));
		$sql = "select value from sessions where sesskey = '" . $id . "' and expiry > '" . time() . "'";
		$value = self::$_db->query($sql)->getOne();
		if ($value) {
			return $value['value'];
		}
		return '';
	}
	
	/**
	 * 写入SESSION
	 * @param int $id session id
	 * @param string $data of the session
	 */
	public static function write($id, $data) {
		self::setDb(Db::get_db('usitrip'));
		$expiry = time() + self::$_session_life;
		$sql = sprintf("REPLACE INTO sessions VALUES('%s', '%s', '%s')",
				Convert::db_input($id),
				Convert::db_input($expiry),
				Convert::db_input($data)
		);
		return self::$_db->execute($sql);
	}
	
	/**
	 * 释放SESSION
	 * @param int $id session id
	 * @return bool
	 */
	public static function destroy($id) {
		self::setDb(Db::get_db('usitrip'));
		return self::$_db->delete('sessions', sprintf(" sesskey = '%s'", Convert::db_input($id)));
	}
	
	/**
     * Garbage Collector
     * @param int life time (sec.)
     * @return bool
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability    1
     * @usage execution rate 1/100
     *        (session.gc_probability/session.gc_divisor)
     */
    public static function gc($max) {
    	self::setDb(Db::get_db('usitrip'));
    	return self::$_db->delete('sessions', sprintf(" expiry < '%s' ", Convert::db_input(time())));
    }
}
?>