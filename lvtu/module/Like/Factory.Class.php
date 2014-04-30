<?php
/**
 * 喜欢工厂类 根据需要构建出需要的类
 * @package Like
 * @author lwkai by 2013-04-01 17:32
 */
class Like_Factory {
	
	/**
	 * 根据参数创建所需的类
	 * @param string $name 需要创建的类文件名
	 */
	static public function getLike($name) {
		$class_name = 'Like_' . ucfirst($name);
		return new $class_name();
	}
}
?>