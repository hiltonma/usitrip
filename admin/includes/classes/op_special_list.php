<?php
/**
 * 操作员需要特别审查的订单之列表。aben
 *
 */
class op_special_list {
	var $login_id;	//当前登录的管理员id

	/**
	 * @param unknown_type $arr
	 */
	function __construct($arr = array()){
		global $login_id;
		if(is_array($arr) && count($arr)>0){
			foreach($arr as $key => $value){
				$this->$key = $value;
			}
		}
		$this->login_id = $login_id;
	}
	
	/**
	 * OP需审核的订单列表:从下单给地接开始到发凭证前,除[未付款]，[已付款未下单]、[已发参团凭证]外所有订单都在此列表.
	 *@return array $arr / false
	 */
	public function getlist_need_op_check(){
		return;
		$data_orders_checked_to_provider = false;/*[给地接下过单的订单列表]*/
		//$sql1 = 'SELECT DISTINCT c.orders_id FROM orders_status AS a,orders_status_history AS b , orders AS c WHERE a.os_groups_id=2 AND a.orders_status_id = b.orders_status_id AND b.orders_id = c.orders_id';
		//$sql1 = "SELECT b.orders_id FROM (SELECT DISTINCT orders_products_id FROM provider_order_products_status_history WHERE popc_user_type=0) AS a,orders_products AS b WHERE a.orders_products_id=b.orders_products_id";
		$sql1 = "SELECT b.orders_id FROM provider_order_products_status_history AS a,orders_products AS b WHERE a.orders_products_id=b.orders_products_id and a.popc_user_type=0 group by a.orders_products_id";
		$sql_query1 = tep_db_query($sql1);
		while($rows = tep_db_fetch_array($sql_query1))
		{
			$data_orders_checked_to_provider[] = $rows['orders_id'];
		}
		//print_r($data_orders_checked_to_provider ); exit();
		
		$data_orders_except = false;/*[要过滤的列表]: 未付款的订单列表+发过电子参团凭证的订单列表*/
		$sql2 = 'SELECT orders_id FROM orders WHERE (orders_paid = 0) UNION SELECT DISTINCT b.orders_id FROM orders_status_history AS a,orders AS b WHERE (a.orders_status_id = 100002) AND a.orders_id = b.orders_id';
		$sql_query2 = tep_db_query($sql2);
		while($rows2 = tep_db_fetch_array($sql_query2))
		{
			$data_orders_except[] = $rows2['orders_id'];
		}
		//print_r($data_orders_except ); exit();		
		
		if(!is_array($data_orders_checked_to_provider))	return false; 
		
		/*从所有 [给地接下过订单的订单列表] 中去除 [要过滤的列表]*/
		$data = array_diff($data_orders_checked_to_provider, $data_orders_except);
		
		//print_r($data ); exit();
		//$data = false;	$data = array('0'=>39654); //测试用资料
		
		if(is_array($data)) 
		{
			$data2 = false;
			foreach($data AS $key=>$value)
			{
				$sql3 = 'CALL mysp_get_order_info_for_getlist_need_op_check('.$value.')';
				//$sql3 = 'CALL mysp_get_order_info_for_getlist_need_op_check(39654)';
				//echo $sql3.'<br/>';
				
				/*$sql_query3 = tep_db_call_sp($sql3);
				while($rows3 = tep_db_fetch_array($sql_query3))
				{
					$data2[] = $rows3;			
				}
				*/
				$data2 = tep_db_call_sp($sql3);
			}
			//print_r($data2 ); exit();
			return $data2;
		}
		else
		{
			return false;
		}		
	}
	
	
	/**
	 * 操作员认为有问题的订单
	 *@return array $arr / false
	 */
	public function getlist_eticketsent_op_think_it_problem(){
		return;
		$error = false;
		$data = false;
		if($error==false){
			$sql='CALL mysp_getlist_eticketsent_op_think_it_problem';
			/*$sqlQuery=tep_db_call_sp($sql);
			while($rows = tep_db_fetch_array($sqlQuery)){
				$data[] = $rows;	//数据记录数据
			}*/
			$data = tep_db_call_sp($sql);
			if(is_array($data[0])){
				return $data;
			}
			return false;
		}
	}
	
	/*
	 *问题订单管控的订单状态列表
	 *@param none
	 *@return array 订单状态ID及文字的阵列
	 */	
	public function get_status_list()
	{
		$data = false;
		$sql ='SELECT c.os_groups_name, a.orders_status_id,b.orders_status_name FROM orders_problemcontrol_statusid AS a, orders_status AS b, orders_status_groups AS c WHERE a.is_deleted=\'0\' AND a.orders_status_id=b.orders_status_id AND b.os_groups_id=c.os_groups_id';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data[] = $rows;	
		}
		return $data;
	}
	
	/**
	*新增记录
	*@para int $orders_id 付款记录编号
	*@para string $remark 备注内容
	*@para int $login_id 登录ID
	*@return int inserted_id 插入的id
	*/
	public function add_remark($orders_id, $remark, $login_id)
	{
		$orders_id = (int)$orders_id;
		$remark = substr($remark,0,100);
		$login_id = (int)$login_id;				
		if($orders_id<1 || $login_id<1 ){ return 'fsdfsdf'; }
		
		$inserted_id = 0;		
		$datetime = date('Y-m-d H:i:s');
		
		$data = false;
		$data[orders_id] = $orders_id;
		$data[remark] = tep_db_prepare_input($remark);
		$data[login_id] = $login_id;
		$data[add_date] = date('Y-m-d H:i:s');
		$data['role'] = 'op';
		$inserted_id = tep_db_fast_insert('orders_remark',$data,'');

		return $inserted_id;
	}
		
	/*
	 *获取订单备注
	 *@param none
	 *@return array 订单备注(备注内容,添加时间,工号)
	 */	
	public function get_orders_remark($orders_id)
	{
		$data = false;
		$sql ='SELECT a.remark,a.add_date,b.admin_job_number FROM orders_remark AS a,admin AS b WHERE a.orders_id='.$orders_id.' AND a.role=\'op\' AND a.login_id=b.admin_id ORDER BY add_date DESC LIMIT 0,1;';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data = $rows;
		}
		return $data;
	}	
	
	/*
	 *记录问题完成的历史日志
	 *@param int : $orders_id  订单号
	 *@param int : $orders_status_id 订单状态ID
	 *@param int : $login_id 后台ID
	 *@return int : 插入的完成记录的ID
	 */	
	public function finish_problem($orders_id, $orders_status_id,$login_id)
	{
		$inserted_id = 0;
		//$sql = 'INSERT INTO orders_problems_track_history(orders_id,orders_status_id, login_id, add_date, is_finished)VALUES('.$orders_id.','.$orders_status_id.','.$login_id.',now(),'1')';
		//$sql_query = tep_db_fast_insert($sql);
		$data['orders_id'] = $orders_id;
		$data['orders_status_id'] = $orders_status_id;
		$data['login_id'] = $login_id;
		$data['add_date'] = date('Y-m-d H:i:s');
		$data['is_finished'] = '1';
		$inserted_id = tep_db_fast_insert('orders_problems_track_history',$data,'');
		return $inserted_id;
	}
	
	
	
	/*
	 *列出admin的列表
	 *@param none
	 *@return array $arr / false
	 */
	public function admin_list()
	{
		$data=false;
		$sql="SELECT admin_id,CONCAT(admin_lastname,' ', admin_firstname,'(',admin_job_number,')') AS admin_name FROM `admin` ORDER BY admin_lastname,admin_firstname";
		$sqlQuery = tep_db_query($sql);
		$data[]=array('id'=>'','text'=>'------------');/*默认为空的选项*/
		while($rows = tep_db_fetch_array($sqlQuery)){
			$data[] = array('id'=>$rows['admin_id'], 'text'=>$rows['admin_name']);	//数据记录数据
		}
		if(is_array($data[0])){
			return $data;
		}
		return false;	//无数据时返回false
	}

	/*
	 * 匹配admin的名字,通过数组,减少访问数据库的次数.
	 * @param int $admin_id 后台ID
	 * @param array $admin_list 管理员信息数组
	 * @return string $text
	 */
	public function get_admin_name($admin_id,$admin_list){
		$data = '';
		if((int)$admin_id > 0){
			$n=count($admin_list);
			for($i=0;$i<$n;$i++){
				if($admin_list[$i]['id']==$admin_id){
					$data = $admin_list[$i]['text'];
					break;
				}
			}
		}
		return $data;
	}
	
}
?>