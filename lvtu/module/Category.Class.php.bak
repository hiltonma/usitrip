<?php
/**
 * 栏目URL类 本项目需要取得主站的几个栏目目录，需要使用
 * @author lwkai
 * @date 2012-11-20 下午3:07:14
 * @formatter:off
 * @link <1275124829@163.com>lwkai
 */
class Category {
	
	/**
	 * 数据库资源连接对象
	 * @var db_mysql
	 * @author lwkai 2012-11-19 下午2:47:28
	 */
	private $_db = NULL;
	
	/**
	 * 当前的语言版本
	 * @var int
	 * @author lwkai 2012-11-19 下午2:48:49
	 */
	private $_language = '';
	
	/**
	 * 栏目名称获取类
	 * @param db_mysql $db 数据库连接资源类
	 * @param int $language_id 
	 * @author lwkai 2012-11-20 下午3:05:49
	 */
	public function __construct($db, $language_id = 1) {
		$this->_db = $db;
		$this->_language = (int)$language_id;
		$this->_language = $this->_language > 0 ? $this->_language : 1;
	}
	/**
	 * 取得目录名称
	 * @param int $category_id 需要获取的ID
	 * @return string
	 */
	public function get_name($category_id) {
		$result = $this->_db->query("select categories_name from `categories_description` where categories_id = '" . (int)$category_id . "' and language_id = '" . $this->_language . "'")->getOne();
		$name = $result['categories_name'];
		$name = explode(' ',$name);
		return $name[0];
	}
	
	/**
	 * 取得目录对应的URL地址
	 * @param int $category_id 需要获取的ID
	 * @return string
	 * @author lwkai 2012-11-19 下午2:56:52
	 */
	public function get_url($category_id) {
		$result = $this->_db->query("select categories_urlname from `categories` where categories_id='" . (int)$category_id . "'")->getOne();
		return empty($result['categories_urlname']) ? $category_id : $result['categories_urlname'];
	}
	
	/**
	 * 根据URL获取栏目ID和父ID
	 * @param string $category_str 栏目的URL字符串
	 * @return array('categories_id'=>当前栏目ID,'parent_id'=>父栏目ID)
	 * @author lwkai 2012-11-21 上午11:55:25
	 */
	public function is_category($category_str) {
		if (is_numeric($category_str)) {
			$result = $this->_db->query("select categories_id,parent_id from `categories` where categories_id='" . (int)$category_str . "'")->getOne();
		} else {
			$result = $this->_db->query("select categories_id,parent_id from `categories` where categories_urlname='" . $category_str . "' or categories_urlname='" . $category_str . "/'")->getOne();
		}
		return $result;
	}
	

}
?>