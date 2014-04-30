<?php
/**
 * 销售跟踪类，只用于后台。aben
 */
class salestrack {
	var $login_id;	//当前登录的管理员id
	/**
	 * 销售跟踪的主表名称
	 */
	var $tables;
	var $viewall;

	/**
	 * salestrack构造函数
	 *
	 * @param unknown_type $arr
	 * @param ref: boolen $this->viewall (是否是销售跟踪管理员)
	 * @param ref: string $this->tables (销售跟踪主表名称)
	 * @param ref: string $this->tb_code_history (销售跟踪code历史记录表名称)
	 * @param ref: string $this->tb_email_history (销售跟踪email历史记录表名称)
	 * @param ref: string $this->tb_item_history (销售跟踪其他内容历史记录表名称)
	 */
	function __construct($arr = array()){
		global $login_id, $messageStack;
		global $can_top_salestrack; /*如果个参数有为true,则该人可以看所有人的销售跟踪记录,否则只能看自己的*/
		if(is_array($arr) && count($arr)>0){
			foreach($arr as $key => $value){
				$this->$key = $value;
			}
		}
		$this->login_id = $login_id;
		$this->tables = 'salestrack'; //留言本的主表名称
		$this->tb_code_history='salestrack_code_history';
		$this->tb_email_history='salestrack_email_history';
		$this->tb_item_history='salestrack_item_history';

		//$this->viewall 是否能看所有人的销售跟踪记录
		if($can_top_salestrack === true){
			$this->viewall=true;
		}else{
			$this->viewall=false;
		}

	}

	/**
	 * 显示查询条件之项目类别阵列
	 * @param none
	 * @return array $data
	 * */
	public function showKeyItem()
	{
		$data=false;
		$data[]=array('id'=>'customer_email','text'=>'客人email');
		$data[]=array('id'=>'customer_name','text'=>'客人姓名');
		$data[]=array('id'=>'customer_tel','text'=>'客人联系电话');
		$data[]=array('id'=>'customer_mobile','text'=>'客人手机');
		$data[]=array('id'=>'customer_qq','text'=>'客人QQ');
		$data[]=array('id'=>'customer_msn','text'=>'客人MSN');
		$data[]=array('id'=>'customer_skype','text'=>'客人SKYPE');
		return $data;
	}

	/**
	 * 批量计算并更新订单归属
	 * */
	public function calc_order_owner_batch()
	{
		//$sql ='SELECT a.orders_id FROM( SELECT orders_id,orders_owner_admin_id ,customers_email_address,date_purchased,orders_paid,orders_owners  FROM orders WHERE (orders_owners IS NULL OR orders_owners=\'\') AND (customers_email_address IS NOT NULL ) ) AS a, orders_total AS b  WHERE b.class=\'ot_total\' AND a.orders_id=b.orders_id AND a.orders_paid>=b.value;';
		/*$sql = "select o.orders_id from orders as o,orders_total as ot where ot.class='ot_total' AND o.orders_id = ot.orders_id AND cast(o.orders_paid as decimal) >= cast(ot.value as decimal) AND (o.orders_owners IS NULL OR o.orders_owners = '') AND (o.customers_email_address IS NOT NULL OR o.customers_email_address <> '')";
		$sql_query = tep_db_query($sql);
		$data = false;
		while($rows = tep_db_fetch_array($sql_query))
		{
			//echo $rows['orders_id'],'<br/>';
			tep_db_call_sp('call mysp_autobind_orders_ownerid('.$rows['orders_id'].');');
		}*/
	}

	/**
	*根据订单号计算订单归属
	*@para int $orders_id 订单号
	*@para bool $refix 是否重新计算订单归属
	*@return bool
	*/
	public function fixed_orders_owners($orders_id, $refix = false)
	{
		return ;
		$orders_id=(int)$orders_id;
		$data = false;
		if($orders_id<1){ return false; }
		$sql = 'SELECT orders_owners FROM orders WHERE orders_id='.$orders_id;
		$sql_query = tep_db_query($sql);
		$data = tep_db_fetch_array($sql_query);

		//print_r($data); //return true;
		if(is_array($data))
		{
			$refix_flag = false;/*为true才重新计算订单归属*/

			if(strlen($data['orders_owners'])<1) $refix_flag = false;

			if($refix == true ) $refix_flag = true;

			if($refix_flag == true)
			{
				$data2 = false;
				$sql2 = 'CALL mysp_autobind_orders_ownerid('.$orders_id.')';
				$data2 = tep_db_call_sp($sql2);
				/*$sql_query2 = tep_db_call_sp($sql2);
				while($rows = tep_db_fetch_array($sql_query2))
				{
				$data2 = $rows;
				}*/
				//print_r($data2);
			}
			return $refix_flag;
		}
	}


	/**
	 * 通过订单号,返回订单的主要信息及所有销售跟踪的资料给订单归属的人工判断做参考
	 * @param int $orders_id
	 * @return array $data返回 array{array['orders_main'],array['orders_code'],array['salestrack']}2维阵列
	 */
	Public function getinfo_forOrdersOwner_check($orders_id)
	{
		$data=false;
		$orders_id=(int)$orders_id;
		if($orders_id<1){ return false; }
		/*订单主要信息*/
		$sql='SELECT customers_email_address,date_purchased,orders_owner_commission,admin_id_orders,orders_owner_admin_id,orders_owners FROM orders WHERE orders_id='.$orders_id;
		$sqlQuery=tep_db_query($sql);
		$data['orders_main'] = tep_db_fetch_array($sqlQuery);

		if(is_array($data['orders_main'])){
			$email=$data['orders_main']['customers_email_address'];
			$purchasedate=$data['orders_main']['date_purchased'];
			/*订单线路信息*/
			$sql2="SELECT products_id,products_name,products_model FROM orders_products WHERE orders_id=".$orders_id;
			$sqlQuery2 = tep_db_query($sql2);
			while($rows2 = tep_db_fetch_array($sqlQuery2)){
				$data['orders_code'][]=$rows2;
			}

			/*订单之email对应的销售跟踪的主要信息*/
			$sql3 = 'SELECT distinct salestrack_id,login_id FROM salestrack_email_history ';
			if($data['orders_main']['orders_owner_commission']==1){
				$sql3 = $sql3.' WHERE email=\''.$email.'\' AND (add_date BETWEEN date_add(\''.$purchasedate.'\',interval -180 day) AND \''.$purchasedate.'\')';
			}
			else{
				$sql3 = $sql3.' WHERE email=\''.$email.'\' AND (add_date BETWEEN date_add(\''.$purchasedate.'\',interval -180 day) AND date_add(\''.$purchasedate.'\',interval 90 day))';
			}
			$sqlQuery3=tep_db_query($sql3);
			while($rows3=tep_db_fetch_array($sqlQuery3)){
				$data['salestrack'][]=$rows3;
			}
			return $data;

		}
		return false;
	}



	/**
	 * 获取某条销售跟踪记录的详细记录
	 * @param  int $salestrack_id (销售跟踪ID)
	 * @return array $arr{['main']{},['code_history']{},['email_history']{},['item_history']{}} (返回2维阵列)
	*/
	public function get_st($salestrack_id){
		global $messageStack;
		$error = false;
		if(!tep_not_null($salestrack_id)){
			$error = true;
			$messageStack->add('id不能为空！','error');
		}
		$data = false;
		$where = '';
		if(!($this->viewall)){
			$where = ' AND login_id='.$this->login_id;
		}

		if($error==false){
			/*主表*/
			$sql='SELECT * FROM '.$this->tables.' WHERE salestrack_id=' . (int)$salestrack_id . $where;
			//echo '<br/>sql:'.$sql;
			$sqlQuery=tep_db_query($sql);
			while($rows = tep_db_fetch_array($sqlQuery)){
				$data['main'][] = $rows;	//数据记录数据
			}
			if(is_array($data['main'])){
				/*code history*/
				$sql_code='SELECT * FROM '.$this->tb_code_history.' WHERE salestrack_id=' . (int)$salestrack_id;
				$sqlQuery_code=tep_db_query($sql_code);
				$rows1=false;
				while($rows1 = tep_db_fetch_array($sqlQuery_code)){
					$data['code_history'][]=$rows1;
				}
				/*email history*/
				$sql_email='SELECT * FROM '.$this->tb_email_history.' WHERE salestrack_id=' . (int)$salestrack_id;
				$sqlQuery_email=tep_db_query($sql_email);
				$rows2=false;
				while($rows2 = tep_db_fetch_array($sqlQuery_email)){
					$data['email_history'][]=$rows2;
				}
				/*other item history*/
				$sql_item='SELECT * FROM '.$this->tb_item_history.' WHERE salestrack_id=' . (int)$salestrack_id;
				$sqlQuery_item=tep_db_query($sql_item);
				$rows3=false;
				while($rows3 = tep_db_fetch_array($sqlQuery_item)){
					$data['item_history'][]=$rows3;
				}

				return $data;
			}
			return false;
		}
	}

	/**
	 * 根据订单号查询出的相关的销售跟踪记录的ID
	 * 输入订单号,返回销售跟踪ID阵列
	 * @param  int $orders_id (订单号)
	*/
	public function get_correspond_salestrack_list($orders_id)
	{
		$checkout_date='';
		$email='';
		$data=false;


		return false;
	}


	/**
	 * 需要记录的其他变更历史的项目列表
	 * @param none
	 * @return array $arr(项目列表之阵列)
	*/
	public function itemList(){
		$arr=false;
		$arr[]=array('key'=>'customer_name','text'=>'客人姓名');
		$arr[]=array('key'=>'customer_tel','text'=>'客人电话');
		$arr[]=array('key'=>'customer_mobile','text'=>'手机');
		$arr[]=array('key'=>'customer_qq','text'=>'QQ');
		$arr[]=array('key'=>'customer_msn','text'=>'MSN');
		$arr[]=array('key'=>'customer_skype','text'=>'SKYPE');
		$arr[]=array('key'=>'customer_plan_tdate','text'=>'计划参团时间');
		$arr[]=array('key'=>'next_condate','text'=>'下次联系时间');
		$arr[]=array('key'=>'customer_info','text'=>'客户咨询信息');
		$arr[]=array('key'=>'orders_id','text'=>'订单号');
		return $arr;
	}

	/**
	 * 匹配要记录的其他项目的名称
	 * @param  string $key.销售跟踪中记录的用户的项目的数据库栏位名称(英文名称)
	 * @return string $string (项目名称)
	*/
	Public function getItemName($key)
	{
		$arr=$this->itemList();
		$n=count($arr);
		for($i=0;$i<$n;$i++){
			if($arr[$i]['key']==$key){
				return $arr[$i]['text'];
			}
		}
		return '';
	}

	/**
	 * 插入新的销售跟踪
	 * @param $post_array 为准备要输入数据库的数据如$_POST
	 * @param $action 默认为 insert是插入，update是更新
	 * @param $update_where 默认为空，如果是更新数据库则需要填写WHERE之后的条件
	 * @return boolen
	 */
	public function addnew($post_array){
		global $messageStack;
		$error = false;
		//数据判断
		if(!tep_not_null($post_array['customer_name'])){
			$error=true;
			$messageStack->add('客人姓名必须填写','error');
		}
		if(!tep_not_null($post_array['customer_tel']) AND !tep_not_null($post_array['customer_mobile']) AND !tep_not_null($post_array['customer_email'])
		AND !tep_not_null($post_array['customer_qq']) AND !tep_not_null($post_array['customer_msn']) AND !tep_not_null($post_array['customer_skype'])){
			$error=true;
			$messageStack->add('客人电话,手机,E-mail,QQ,MSN,SKYPE至少要填写一个','error');
		}
		if(!tep_not_null($post_array['customer_info'])){
			$error=true;
			$messageStack->add('必须输入用户咨询内容','error');
		}
		//添加到主表及附表
		if($error==false){
			$tmp_now=date('Y-m-d H:i:s');
			$post_array['add_date'] = $tmp_now;
			$post_array['login_id'] = $this->login_id;
			if(!tep_not_null($post_array['customer_plan_tdate'])){
				$post_array['customer_plan_tdate']=null;
			}
			if(@tep_not_null($post_array['customer_email'])){
				$post_array['email_last_update_date']=$tmp_now;
			}
			//print_r($post_array);
			$insert_id = tep_db_fast_insert($this->tables, $post_array,'');
			if((int)$insert_id){
				//$messageStack->add_session('数据插入成功', 'success');	//当操作成功后跳到新页面时用此方法记录成功提示信息


				/*$ar_tmp用来存储临时数据*/
				$arr_tmp['salestrack_id']=$insert_id;
				$arr_tmp['add_date']=$tmp_now;
				$arr_tmp['login_id']=$this->login_id;
				/*拆分前台传递过来的code*/
				$arr_code=explode(',',$post_array['code']);

				/*将code拆分后分别插入到code history表中最大可填写8个*/
				//如果填写的团号太多则记录一日志信息
				if(count($arr_code) > 8){
					$this->error_log($arr_code);
				}

				for($i=0, $n = min(count($arr_code), 8); $i<$n; $i++){
					if(tep_not_null($arr_code[$i])){
						$arr_tmp['code']=$arr_code[$i];
						tep_db_fast_insert($this->tb_code_history,$arr_tmp,'');
					}
				}

				/*如果有填写email,则记录这个操作到email history表中*/
				if(tep_not_null($post_array['customer_email'])){
					$arr_tmp['email']=$post_array['customer_email'];
					tep_db_fast_insert($this->tb_email_history, $arr_tmp,'code');
				}
				$messageStack->add_session('数据插入成功.'.$tmp_now, 'success');	//当操作成功后跳到新页面时用此方法记录成功提示信息

				return $insert_id;	//返回被插入的新notebook_id
			}
			else{
				$error=true;
				$messageStack->add('插入失败.'.$tmp_now,'error');
			}
			return true;
		}
		return true; //成功返回true失败返回false
	}

	/**
	 * 更新销售跟踪记录
	 * @param array $post_array
	 * @param int $salestrack_id
	 * @return true/false
	 */
	public function update($post_array,$salestrack_id){
		global $messageStack;
		$error = false;
		//数据判断
		if(!tep_not_null($post_array['customer_name'])){
			$error=true;
			$messageStack->add('客人姓名必须填写','error');
		}
		if(!tep_not_null($post_array['customer_tel']) AND !tep_not_null($post_array['customer_mobile']) AND !tep_not_null($post_array['customer_email'])
		AND !tep_not_null($post_array['customer_qq']) AND !tep_not_null($post_array['customer_msn']) AND !tep_not_null($post_array['customer_skype'])){
			$error=true;
			$messageStack->add('客人电话,手机,E-mail,QQ,MSN,SKYPE至少要填写一个','error');
		}
		if(!tep_not_null($post_array['customer_info'])){
			$error=true;
			$messageStack->add('必须输入用户咨询内容','error');
		}
		$where = '';
		if(!($this->viewall)){
			$where = ' AND login_id='.$this->login_id;
		}
		if($error==false){
			$tmp_now=date('Y-m-d H:i:s');
			$data_old=false;
			/*读取原记录,然后比较变更的内容,向从表中添加历史记录后,再更新主表*/
			$sql='SELECT * FROM '.$this->tables.' WHERE salestrack_id=' . (int)$salestrack_id . $where;
			/*读取原记录*/
			$sqlQuery=tep_db_query($sql);
			$data_old = tep_db_fetch_array($sqlQuery);
			/* while($rows = tep_db_fetch_array($sqlQuery)){
			$data_old = $rows;	//读取原记录
			} */

			//print_r($data_old); echo '<hr/>'; print_r($post_array);
			if(is_array($data_old)){
				$customer_name_old=$data_old['customer_name'];

				/*$ar_tmp用来存储临时数据--for团号和邮箱*/
				$arr_tmp['salestrack_id']=$salestrack_id;
				$arr_tmp['add_date']=$tmp_now;
				$arr_tmp['login_id']=$this->login_id;

				/*如果有新的团号,则添加新的code history*/
				if(tep_not_null($post_array['code'])){

					/*拆分前台传递过来的code*/
					$arr_code=explode(',',$post_array['code']);
					//如果填写的团号太多则记录一日志信息
					if(count($arr_code) > 8){
						$this->error_log($arr_code);
					}
					/*将code拆分后分别插入到code history表中最多8个*/
					for($i=0, $n=min(count($arr_code),8); $i<$n; $i++){
						if(tep_not_null($arr_code[$i])){
							$arr_tmp['code']=$arr_code[$i];
							tep_db_fast_insert($this->tb_code_history,$arr_tmp,'');
						}
					}
				}

				/*如果email被更新,则向email history中增加记录*/
				if($post_array['customer_email']!=$data_old['customer_email']){
					$arr_tmp['email']=$post_array['customer_email'];
					$id11=tep_db_fast_insert($this->tb_email_history, $arr_tmp,'code');
					//echo $id11; exit();
				}

				//echo '<hr/>'.$tmp_now.'<hr/>';
				//print_r($data_old);

				/*other item history---判断其他项目是否有变化,如果有,则记录变更历史*/
				$arr_tmp1=false;/*把item history所需要的列都列出,然后一一赋值*/
				$arr_tmp1['salestrack_id']=$salestrack_id;
				$arr_tmp1['add_date']=$tmp_now;
				$arr_tmp1['login_id']=$this->login_id;

				$arr_tmp2=$this->itemList();/*需要判断的项目列表*/

				//print_r($arr_tmp2); //exit();
				$n1=count($arr_tmp2);
				for($i=0; $i<$n1;$i++){
					$item_name=$arr_tmp2[$i]['key'];
					$old_value=$data_old[$item_name];
					$new_value=$post_array[$item_name];
					//echo '<br/>'.$item_name;
					if($old_value!=$new_value){
						//echo '<br/>'.$item_name.':'.$new_value.', old: '.$old_value;
						$arr_tmp1['item_name']=$item_name;
						$arr_tmp1['old_value']=$old_value;
						$arr_tmp1['new_value']=$new_value;
						tep_db_fast_insert($this->tb_item_history, $arr_tmp1);
					}
				}

				/*开始更新主表内容*/
				/*团号累计*/
				if(tep_not_null($post_array['code'])){
					if(tep_not_null($data_old['code'])){
						$post_array['code'] = $data_old['code'].','.$post_array['code'];
					}
					else {
						$post_array['code'] = $post_array['code'];
					}
				}
				else{
					$post_array['code'] = $data_old['code'];
				}
				/*如果email没有变,则不变更栏位email_last_update_date*/
				if($post_array['customer_email']!=$data_old['customer_email']){
					$post_array['email_last_update_date']=$tmp_now;
				}else{
					$post_array['email_last_update_date']=$data_old['email_last_update_date'];
				}
				$where =' salestrack_id='.$salestrack_id;

				$rows_effected=tep_db_fast_update($this->tables, $where, $post_array);
				if((int)$rows_effected){
					$error=false;
					//$messageStack->add('销售跟踪记录更新成功', 'success');
					$messageStack->add_session('销售跟踪记录更新成功. '.$tmp_now,'success');
				}
				//print_r($post_array);

				return $rows_effected;

			}
			return false;
		}
		return true; //成功返回true失败返回false
	}


	/**
	 * 删除一条或多条销售跟踪记录
	 * @param unknown_type $notebook_ids 可以是单个留言id或数组如(array)$_POST['notebook_ids'] 或 
	 
	public function delete($notebook_ids){		
		global $messageStack;
		$error = false;
		return false;
	}*/


	/**
	 * 根据条件列出销售跟踪数据，返回数组或false
	 *
	 * @param unknown_type $tables 要读取的数据表,可取多表,如 table1_1 t1, table_2 t2
	 * @param unknown_type $fields 要读取的字段，默认为*
	 * @param unknown_type $where 条件，默认为1
	 * @param unknown_type $group_by 默认为空 GROUP BY 的内容，如 GROUP BY abc
	 * @param unknown_type $order_by 排序方式默认为空。如ORDER BY t1.t_it DESC
	 * @return unknown array $data or false	其中$data['splitPages']为数据的分页信息
	 */
	public function getlists($tables ='', $fields = '*', $where='',$group_by='',$order_by=''){

		$data = false;
		if(!tep_not_null($tables)){
			$tables = $this->tables;
		}
		$pageMaxRowsNum = 10; //每页显示10条记录
		$sql = 'SELECT '.$fields.' FROM '.$tables.' where '.$where.$group_by.$order_by;
		//echo $sql;
		$keywords_query_numrows = 0;
		$_split = new splitPageResults($_GET['page'], $pageMaxRowsNum, $sql, $keywords_query_numrows);
		//var_dump($_split); exit;
		$data['splitPages']['count'] = $_split->display_count($keywords_query_numrows, $pageMaxRowsNum, (int)$_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);//分页数据,显示总数
		$data['splitPages']['links'] = $_split->display_links($keywords_query_numrows, $pageMaxRowsNum, MAX_DISPLAY_PAGE_LINKS, (int)$_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ;//分页数据翻页
		//$sql = $_split->sql_query;
		$sqlQuery = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sqlQuery)){
			$data[] = $rows;	//记录数据
		}
		//print_r($data);
		if(is_array($data[0])){
			return $data;
		}
		return false;	//无数据时返回false
	}

	/**
	 * 手动修改订单归属者
	 * @para int $orders_id订单号
	 * @para string $orders_owners 客服工号,多个以逗号隔开
	 * @return unknown array $data or false
	 */
	public function edit_orders_owners($orders_id,$orders_owners,$login_id)
	{
		$datetime = date('Y-m-d H:i:s');
		//删除旧的
		$sql = 'UPDATE `orders_owner_detail` SET is_deleted=1 WHERE orders_id='.$orders_id.' AND is_deleted=0';
		//插入新的订单归属记录到明细表 (bug: 如果新的没有结果,则不能记录是谁把这个记录给清空的)
		$sql2 = 'INSERT INTO `orders_owner_detail`(orders_id,owner_login_id,add_date,is_deleted,add_login_id) SELECT '.$orders_id.' AS orders_id, admin_id,\''.$datetime.'\' AS ad_date,0,'.$login_id.' AS add_login_id FROM `admin` WHERE admin_job_number IN('.$orders_owners.')';
		//更新订单主表之订单归属
		$sql3 = 'UPDATE `orders` SET orders_owners =(SELECT GROUP_CONCAT(admin_id) AS admin_job_number FROM `admin` WHERE admin_job_number IN('.$orders_owners.')) WHERE orders_id='.$orders_id;

		//echo $sql2.'<hr/>'; echo $sql3;

		tep_db_query($sql);
		tep_db_query($sql2);
		tep_db_query($sql3);
	}

	/**
	 * 根据订单号列出订单归属的修改历史
	 * @param int $orders_id订单号
	 * @return array $data or false
	 */
	public function show_edit_history($orders_id)
	{
		$data = false;
		$sql = 'SELECT owner_login_id,add_date,is_deleted,add_login_id FROM `orders_owner_detail` WHERE orders_id='.$orders_id;
		$sql_query =  tep_db_query($sql);
		while( $rows = tep_db_fetch_array($sql_query) )
		{
			$data[] = $rows;
		}
		return $data;
	}

	/**
	*列出admin的列表
	*@param none
	*@return array $arr (管理员列表之阵列)
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

	/**
	 * 匹配admin的名字,通过数组,减少访问数据库的次数.
	 * @param  int $admin_id (后台ID)
	 * @param  array $admin_list (后台管理员信息数组)
	 * @return string $string (管理员名字)
	 */
	public function get_admin_name($admin_id,$admin_list){
		if($admin_id=="0" or $admin_id==""){
			return '';
		}
		$n=count($admin_list);
		for($i=0;$i<$n;$i++){
			if($admin_list[$i]['id']==$admin_id){
				return $admin_list[$i]['text'];
			}
		}
		return '';
	}

	/**
	 * 记录超限日志找出可疑数据
	 * @param array $code_array
	 */
	private function error_log(array $code_array){
		$error_log_file = DIR_FS_CATALOG.'tmp/max_code_log.txt';
		$error_notes.= 'date:'.date("Y-m-d H:i:s")." login_id:".$this->login_id."\n";
		$error_notes.= print_r($code_array, true)."\n";
		if($handle = fopen($error_log_file, 'ab')){
			fwrite($handle, $error_notes);
			fclose($handle);
		}
	}
}
?>