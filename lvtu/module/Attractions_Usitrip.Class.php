<?php
/**
 * 景点获取类
 * @author lwkai 2013-3-4 上午9:33:37
 *
 */
class Attractions_Usitrip {
	
	/**
	 * 数据库操作对象
	 * @var Db_Mysql
	 * @author lwkai 2013-2-27 下午1:50:20
	 */
	private static $_db = null;
	
	private function db() {
		if (self::$_db == null) {
			self::$_db = Db::get_db('usitrip');
		}
		return self::$_db;
	}
	/**
	 * 根据传过来的字符串搜索包含该字符串的景点
	 * @param string $str 需要查找的字符
	 * @return array
	 * @author lwkai 2013-2-27 下午1:59:18
	 */
	static public function getAttractionsByWord($str){
		if (empty($str)) {
			return array();
		}
		$str = Convert::db_input($str);
		$sql = "select city_id as id,city as name from tour_city where city like BINARY '" . $str . "%'";
		//echo $sql;
		$rs = self::db()->query($sql)->getAll();
		return $rs;
	}
	
	/**
	 * 根据传过来的ID取得对应的景点名称
	 * @param string $ids 景点表的ID，多个请用英文逗号隔开
	 * @return array
	 * @author lwkai 2013-3-1 上午9:00:16
	 */
	static public function getAttractionsById($ids) {
		if (empty($ids)) {
			return array();
		}
		$sql = "select city_id,city from tour_city where city_id in (" . $ids . ")";
		$rs = self::db()->query($sql)->getAll();
		return $rs;
	}
	
	/**
	 * 根据当前用户获取他曾经购买过的旅游线路名称
	 * @param string $key 要查找的文字
	 * @author lwkai 2013-3-20 下午5:55:52
	 */
	static public function getOrdersProducts($key) {
		if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
			return array();
		}
		$key = Convert::db_input($key);
		$sql = "select op.products_id as id,op.products_name as name from orders_products op,orders o where o.orders_id = op.orders_id and o.customers_id='" . (int)$_SESSION['customer_id'] . "' and op.products_name like BINARY '%" . $key . "%'  group by op.products_id order by orders_products_id desc";
		$rs = self::db()->query($sql)->getAll();
		return $rs;
	}
	
	/**
	 * 根据产品ID获得SEO的URL地址。如果找到此产品并且SEO设置了这个URL，
	 * 则返回设置的URL，如果没设置URL，则返回产品ID，如果没找到这个产品，则返回0
	 * @param int $product_id 产品ID
	 * @return string
	 * @author lwkai 2012-11-21 下午3:39:19
	 */
	static public function getProductUrl($product_id = 0) {
		$sql = "select products_urlname from `products` where products_id='" . (int)$product_id . "' and products_status='1'";
		$result = self::db()->query($sql)->getOne();
		return isset($result['products_urlname']) ? ($result['products_urlname'] ? $result['products_urlname'] : $product_id ) : 0;
	}
	
	/**
	 * 根据产品ID获得线路名称
	 * @param int $id 产品ID
	 * @return string
	 * @author lwkai 2013-4-19 下午4:00:05
	 */
	static public function getProductsName($id) {
		$sql = "select products_name as name from products_description where products_id='" . intval($id) . "'";
		$rs = self::db()->query($sql)->getOne();
		return $rs['name'];
	}
	
	/**
	 * 根据用户ID获取用户名称
	 * @param int $userid 用户ID
	 * @return string
	 * @author lwkai 2013-3-22 下午5:17:55
	 */
	static public function getUserName($userid){
		if ($userid <= 0) {
			return '';
		}
		$sql = "select customers_firstname from customers where customers_id='" . intval($userid) . "'";
		$rs = self::db()->query($sql)->getOne();
		return $rs['customers_firstname'];
	}
	
	/**
	 * 取得首页的友情连接
	 * @return array
	 * @author lwkai 2013-4-22 下午5:07:34
	 */
	static public function getLinks(){
		$sql = "select l.links_url as href,ld.links_title as name from links l, links_description ld where l.links_id = ld.links_id and l.display_on_home_page=1 order by l.links_id desc";
		$rs = self::db()->query($sql)->getAll();
		return $rs;
	}
}