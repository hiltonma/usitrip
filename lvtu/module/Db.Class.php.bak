<?php
/**
 * 数据库连接类
 * @author lwkai
 * @date 2012-11-25 下午3:30:37
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Db {
	/**
	 * 保存已经打开的数据库连接
	 * @var Array 保存创建的数据库连接对象
	 */
	private static $_db = array();
	
	private function __construct() {
		//私有构造函数，不允许直接NEW
	}
	
	/**
	 * 初始化数据库连接，使用此类，不能直接new，而是调此静态方法
	 * @param string $dbname 要连接的数据库配置数组的KEY名
	 * @return database object
	 */
	public static function get_db($dbname = 'trip_image', $type = 'MySQL') {
		$type = ucfirst(strtolower($type));
		if (empty(self::$_db[$dbname . $type])) {
			$class = 'Db_' . $type;
            $pdo_handle = new $class($dbname);
            self::$_db[$dbname . $type] = $pdo_handle;
        }
        return self::$_db[$dbname . $type];
	}
	
}
?>