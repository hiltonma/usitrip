<?php
/**
 * 订单修改导致订单归属更改的处理类
 * @author wtj
 * @date 2013-8-1
 */
class OrdersOwnerChange{
	private $_time_long=72;
	private $_table_name='orders_change_history_use_owner';//表名
	private $_type_array=array(100115,100124,100100,100128);//需要更新归属的集中更新状态
	/**
	 * 分配订单
	 * @param int $orders_id order id
	 * @param int $status 状态
	 * @param int $user_id login id
	 * @return 0
	 */
	public function runing($orders_id,$status,$user_id){
		return;
		if($this->checkStatus($status)&&$this->checkTime((int)$orders_id)&&$this->checkOwnerNumber($orders_id,$user_id)){
			$this->changeOwner($orders_id, $user_id);
		}
		$this->dropRecord($orders_id);
		return 0;
	}
	/**
	 * 添加记录
	 * @param int $user_id user id
	 * @param int $type 类型
	 * @param int $orders_id order id
	 * @return number
	 */
	public function addRecord($user_id,$type,$orders_id){
		return ;
		$data=array('add_time'=>date('Y-m-d H:i:s'),'user_id'=>$user_id,'change_type'=>$type,'orders_id'=>$orders_id);
		tep_db_fast_insert($this->_table_name, $data);
		return tep_db_insert_id();
		return 0;
	}
	/**
	 * 判断状态是否符合条件
	 * @param int $status 状态编码
	 * @return boolean
	 */
	private function checkStatus($status){
		return in_array($status, $this->_type_array);
	}
	/**
	 * 删除记录
	 * @param int $orders_id order id
	 * @return 0
	 */
	public function dropRecord($orders_id){
		$str_sql='update '.$this->_table_name.' set is_drop=1 where orders_id='.(int)$orders_id;
		tep_db_query($str_sql);
		return 0;
	}
	/**
	 * 修改订单的归属
	 * @param int $orders_id order id
	 * @param int $user_id user id
	 * @return 0
	 */
	public function changeOwner($orders_id,$user_id){
		return;
		$str_sql='update orders set orders_owners = CONCAT(orders_owners,",'.tep_get_job_number_from_admin_id((int)$user_id).'") where orders_id='.(int)$orders_id;
		tep_db_query($str_sql);
		return 0;
	}
	/**
	 * 返回订单归属人的个数
	 * @param int $orders_id orders id
	 * @return number
	 */
	private function checkOwnerNumber($orders_id,$user_id){
		$orders_owner=tep_db_get_field_value('orders_owners', 'orders','orders_id='.(int)$orders_id);
		$owner_number=count(explode(',',$orders_owner));
		if($owner_number==1&&$orders_owner!=tep_get_job_number_from_admin_id((int)$user_id))
		return true;
		else
		return false;
	}
	/**
	 * 检查记录的时间到现在的时间是否超过72小时
	 * @param int $orders_id
	 * @return boolean
	 */
	private function checkTime($orders_id){
		$add_time=tep_db_get_field_value('add_time', $this->_table_name,'orders_id='.(int)$orders_id.' and is_drop=0 order by add_time');
		if($add_time)
		return (time()-strtotime($add_time)>$this->_time_long*3600)?true:false;
		else 
			return false;
	}
	public function checkIsChange($table,$field,$value,$where=1){
		$field_value=tep_db_get_field_value($field, $table,$where);
		if($field_value!=$value)
			return true;
		else 
			return false;
	}
}
?>