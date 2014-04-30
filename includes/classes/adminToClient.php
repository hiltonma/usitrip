<?php
/**
 * 后台管理员到前台去帮客人下单的类
 * @package
 * @author Howard 
 */
final class adminToClient{
	/**
	 * 用户后台的登录id
	 * @var int
	 */
	public $login_id;
	/**
	 * 父订单id
	 * @var int
	 */
	public $parent_orders_id;
	/**
	 * 初始化时传入用户的后台登录id和父订单id
	 * @param int $login_id 后台登录id
	 * @param int $parent_orders_id 父订单的id，默认为无
	 */
	public function __construct($login_id, $parent_orders_id = 0){
		$this->login_id = (int)$login_id;
		$this->parent_orders_id = (int)$parent_orders_id;
	}
	/**
	 * 清除在后台订单中创建的父订单cookie值
	 */
	public function claer_parent_orders_id(){
		setcookie('ParentOrdersId', 0, time()-3600, '/', HTTP_COOKIE_DOMAIN);	//必须添加路径为/否则前台的程序找不到此变量
	}
	/**
	 * 将新订单合并到父订单
	 * 注意：在添加订单产品时不发邮件给客户；支付方式为银行转账
	 * @param int $new_orders_id 新订单id号
	 */
	public function orders_push_to_orders($new_orders_id){
		$new_orders_id = (int)$new_orders_id;
		$parent_orders_id = (int)$this->parent_orders_id;
		$this->claer_parent_orders_id();
		$new_customers_id = tep_db_get_field_value('customers_id', 'orders', 'orders_id="'.$new_orders_id.'"');
		$old_customers_id = tep_db_get_field_value('customers_id', 'orders', 'orders_id="'.$parent_orders_id.'"');
		
		//必要的检查
		if(!$new_orders_id || !$parent_orders_id || $new_customers_id!=$old_customers_id){
			return false;
		}
		//如果新订单的总价格项目比父订单的多也不可合并订单
		if(!$this->parent_sub_orders_total_item_check($parent_orders_id, $new_orders_id)){
			return false;
		}
		//如果子订单产品与父订单产品有重复的话也不可合并订单
		if(!$this->parent_sub_orders_item_check($parent_orders_id, $new_orders_id)){
			return false;
		}
		
		//删除新订单标记
		tep_db_query('DELETE FROM `orders` WHERE `orders_id` = "'.$new_orders_id.'" ');
		//删除订单归属明细
		tep_db_query('DELETE FROM `orders_owner_detail` WHERE `orders_id` = "'.$new_orders_id.'" ');
		//删除订单session信息
		tep_db_query('DELETE FROM `orders_session_info` WHERE `orders_id` = "'.$new_orders_id.'" ');
		//删除订单状态更新历史
		tep_db_query('DELETE FROM `orders_status_history` WHERE `orders_id` = "'.$new_orders_id.'" ');
		
		//更新订单最后修改时间
		tep_db_query('UPDATE `orders` SET last_modified=now() WHERE `orders_id` = "'.$parent_orders_id.'" ');
		//把订单产品记到父订单名下
		tep_db_query('UPDATE `orders_products` SET orders_id="'.$parent_orders_id.'", is_step3="1", step3_admin_id="'.(int)$this->login_id.'" WHERE `orders_id` = "'.$new_orders_id.'" ');
		//订单产品属性表更新
		tep_db_query('UPDATE `orders_products_attributes` SET orders_id="'.$parent_orders_id.'" WHERE `orders_id` = "'.$new_orders_id.'" ');
		//电子票表更新
		tep_db_query('UPDATE `orders_product_eticket` SET orders_id="'.$parent_orders_id.'" WHERE `orders_id` = "'.$new_orders_id.'" ');
		//航班信息表更新
		tep_db_query('UPDATE `orders_product_flight` SET orders_id="'.$parent_orders_id.'" WHERE `orders_id` = "'.$new_orders_id.'" ');
		//结伴同游订单表更新
		tep_db_query('UPDATE `orders_travel_companion` SET orders_id="'.$parent_orders_id.'" WHERE `orders_id` = "'.$new_orders_id.'" ');
		//用户积分表信息更新
		$this->parent_sub_orders_points_merge($parent_orders_id, $new_orders_id, $new_customers_id);
		//订单总价格更新
		$this->update_orders_total($parent_orders_id, $new_orders_id);
		
		return true;
	}
	/**
	 * 处理父子订单的积分合并
	 * @param int $parent_orders_id 父订单号
	 * @param int $new_orders_id 子订单号
	 * @param int $customers_id 客户id
	 */
	private function parent_sub_orders_points_merge($parent_orders_id, $new_orders_id, $customers_id){
		$points_sql = tep_db_query('SELECT points_pending FROM `customers_points_pending` WHERE `orders_id` = "'.$new_orders_id.'" and points_type="SP" and customer_id="'.$customers_id.'" ');
		$points_row = tep_db_fetch_array($points_sql);
		if($points_row['points_pending']){
			$sql = tep_db_query('SELECT unique_id, points_pending FROM `customers_points_pending` WHERE orders_id="'.$parent_orders_id.'" and points_type="SP" and customer_id="'.$customers_id.'" ');
			$row = tep_db_fetch_array($sql);
			if($row['unique_id']){
				//只有财务未锁定的积分才可以修改
				if(!tep_db_get_field_value('point_lock', 'orders', 'orders_id="'.$parent_orders_id.'"')){
					tep_db_query('update customers_points_pending set points_pending="'.($points_row['points_pending'] + $row['points_pending']).'" where unique_id ="'.$row['unique_id'].'" ');
				}
				tep_db_query('DELETE FROM `customers_points_pending` WHERE `orders_id` = "'.$new_orders_id.'" ');				
			}
		}
		//处理完成后校正用户积分
		tep_auto_fix_customers_points($customers_id);
	}
	/**
	 * 检查父子订单产品
	 * @param int $parent_orders_id
	 * @param int $sub_orders_id
	 * @return false|true 如果子订单产品比父订单产品有重复的话就返回false，否则返回true
	 */
	private function parent_sub_orders_item_check($parent_orders_id, $sub_orders_id){
		$str = 'SELECT products_id FROM `orders_products` where orders_id="%d" ';
		$psql = tep_db_query(sprintf($str, $parent_orders_id));
		$pids = array();
		while($prows = tep_db_fetch_array($psql)){
			$pids[]=$prows['products_id'];
		}
		$ssql = tep_db_query(sprintf($str, $sub_orders_id));
		$spids = array();
		while($srows = tep_db_fetch_array($ssql)){
			$spids[] = $srows['products_id'];
		}
		if(array_intersect($pids, $spids)){
			return false;
		}
		return true;
	}
	/**
	 * 查检父子订单总价项目数
	 * @param int $parent_orders_id 父订单id
	 * @param int $sub_orders_id 子订单id
	 * @return false|true 如果子订单的项目比父订单的多的话就返回false，否则返回true
	 */
	private function parent_sub_orders_total_item_check($parent_orders_id, $sub_orders_id){
		$str = 'SELECT count(*) as itemNum FROM `orders_total` where orders_id="%d" ';
		$psql = tep_db_query(sprintf($str, $parent_orders_id));
		$prow = tep_db_fetch_array($psql);
		$ssql = tep_db_query(sprintf($str, $sub_orders_id));
		$srow = tep_db_fetch_array($psql);
		if($srow['itemNum']<=$prow['itemNum']){
			return true;
		}
		return false;
	}
	/**
	 * 父子订单价格更新
	 * 把子订单价格信息添加到父订单然后删除子订单的价格信息
	 * @param int $parent_orders_id 父订单id
	 * @param int $sub_orders_id 子订单id
	 */
	private function update_orders_total($parent_orders_id, $sub_orders_id){
		tep_db_query('UPDATE `orders_total` p, `orders_total` s SET p.value = p.value+s.value, p.text=CONCAT("<b>$",ROUND((p.value+s.value),2),"</b>") WHERE p.orders_id = "'.(int)$parent_orders_id.'" and s.orders_id="'.(int)$sub_orders_id.'" and p.class=s.class ');
		tep_db_query('DELETE FROM `orders_total` WHERE orders_id="'.(int)$sub_orders_id.'" ');
	}
	/**
	 * 登录检查
	 * @param string $password 销售输入的密码
	 * @param bool $password 是否需要验证后台密码，默认为需要
	 * @return true|false
	 */
	public function login_check($password='', $need_check_passwd = true){
		if(!(int)$this->login_id){
			return true;
		}
		$result = false;
		$check_admin_query = tep_db_query("select a.admin_id, a.admin_password from " . TABLE_ADMIN . " a where a.admin_id = '" . (int)$this->login_id . "' ");
		$check_admin_row = tep_db_fetch_array($check_admin_query);
		if($need_check_passwd == true){
			if(!tep_validate_password($password, $check_admin_row['admin_password'])){
				$result = false;
			}else {
				$result = true;
			}
		}elseif((int)$check_admin_row['admin_id']){
			$result = true;
		}
		if($result == true){	//记录后台用户session值用于判断当前登录者是不是后台用户
			$admin_login_for_customers = 1;
			tep_session_register('admin_login_for_customers');
		}
		return $result;
	}
	/**
	 * 检测当前后台用户是否可以进入客人的个人中心
	 * @param array $groups_ids 允许进入客人个人中心的用户组id，默认为顶级管理员1和财务11可以进入，从2013-04-02后商务中心也可以进入了
	 * @return true|false
	 */
	public function check_allow_my_account(array $groups_ids = array(1,5,7,11,42,48)){
		if(!(int)$this->login_id){
			return true;
		}
		if((int)tep_session_is_registered('admin_login_for_customers')){
			$admin_groups_id = $this->get_groups_id((int)$this->login_id);
			if(!in_array($admin_groups_id, $groups_ids)){
				return false;
			}
		}
		return true;
	}
	/**
	 * 根据管理员id取得管理员的组id
	 * @param int $admin_id 管理员id
	 */
	public function get_groups_id($admin_id){
		$check_admin_query = tep_db_query("select a.admin_groups_id from " . TABLE_ADMIN . " a where a.admin_id = '" . (int)$admin_id . "' ");
		$check_admin_row = tep_db_fetch_array($check_admin_query);
		return $check_admin_row['admin_groups_id'];
	}
	/**
	 * 取得某管理员能看的用户中心页面
	 * @param int $admin_id 管理员id
	 */
	public function can_views_page($admin_id){
		$admin_id = $admin_id ? $admin_id : $this->login_id;
		$pages = array();
		$admin_groups_id = $this->get_groups_id((int)$admin_id);
		//默认销售进入客人用户中心只可以进入查看【订单管理】和【我的结伴同游】版块，其他的无法进入
		if(in_array($admin_groups_id,array(5,7,42,48))){
			$pages = array('account','account_history', 'account_history_info', 'orders_travel_companion', 'orders_travel_companion_info', 'account_history_payment_method','account_history_payment_checkout','account_history_payment_process');	//.php
		}
		return $pages;
	}
}
?>