<?php
/**
 * 评论工厂类 根据需要构建出需要的类
 * @package Comment
 * @author lwkai by 2013-04-02 17:47
 */
class Comment_Factory {
	
	/**
	 * 根据参数创建所城的类
	 * @param string $name 需要创建的类文件名
	 */
	static public function getComment($name) {
		$class_name = 'Comment_' . ucfirst($name);
		return new $class_name();
	}
}
?>