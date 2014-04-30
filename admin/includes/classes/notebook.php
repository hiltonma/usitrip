<?php
/**
 * 留言本类，只用于后台。aben
 *
 */
class notebook {
	var $login_id;	//当前登录的管理员id
	/**
	 * 留言本的主表名称
	 */
	var $tables;

	/**
	 * Account构造函数
	 *
	 * @param unknown_type $arr
	 */
	function __construct($arr = array()){
		global $login_id, $messageStack;
		if(is_array($arr) && count($arr)>0){
			foreach($arr as $key => $value){
				$this->$key = $value;
			}
		}
		$this->login_id = $login_id;
		$this->tables = 'notebook'; //留言本的主表名称
	}
	/**
	 * 获取某条留言本内容（不含已做删除标记的留言）
	 *@param  string $notebook_id (记事本ID)
	 *@return array $arr / false
	 */
	public function getnote($notebook_id){
		global $messageStack;
		$error = false;
		$notebook_id = (int)$notebook_id;
		if(!tep_not_null($notebook_id)){
			$error = true;
			$messageStack->add('id不能为空！','error');
		}
		$data = false;
		if($error==false){
			$sql='SELECT * FROM '.$this->tables.' WHERE notebook_id='.$notebook_id.' AND is_deleted=0';
			$sqlQuery=tep_db_query($sql);
			while($rows = tep_db_fetch_array($sqlQuery)){
				$data[] = $rows;	//数据记录数据
			}
			if(is_array($data[0])){
				return $data;
			}
			return false;
		}

	}
	/**
	 * 通过订单ID获取留言列表
	 * @param int $orders_id 订单ID
	 * @return array||NULL
	 */
	public function getListByOrdersId($orders_id){
		$str_sql='SELECT notebook_id FROM '.$this->tables.' WHERE orders_id='.(int)$orders_id;
		$sql_query=tep_db_query($str_sql);
		$rows=array();
		while($row=tep_db_fetch_array($sql_query)){
			$rows[]=$row;
		}
		return $rows;
	}
	/**
	 * 插入（或更新）留言到数据库
	 * @param $post_array 为准备要输入数据库的数据如$_POST
	 * @param $action 默认为 insert是插入，update是更新
	 * @param $update_where 默认为空，如果是更新数据库则需要填写WHERE之后的条件
	 * @return boolen
	 */
	public function insert_or_update($post_array, $action='insert', $update_where=''){
		global $messageStack;
		$error = false;

		//书写数据判断代码
		if(!tep_not_null($post_array['content'])){
			$error = true;
			$messageStack->add('留言内容不能为空！','error');
		}
		if(!(int)$post_array['to_login_id']){
			$error = true;
			$messageStack->add('请选择留言接收人！','error');
		}
		//书写数据库代码
		if($error==false){
			if($action=='insert'){
				$post_array['add_date'] = date('Y-m-d H:i:s');
				$post_array['sent_login_id'] = $this->login_id;
				$post_array['orders_id']=$post_array['orders_id'];
				$insert_id = tep_db_fast_insert($this->tables, $post_array,'notebook_id,is_deleted');
				if((int)$insert_id){
					$messageStack->add_session('数据插入成功', 'success');	//当操作成功后跳到新页面时用此方法记录成功提示信息
				}
				return $insert_id;	//返回被插入的新notebook_id
			}else{
				$post_array['update_date'] = date('Y-m-d H:i:s');
				tep_db_fast_update($this->tables, 'notebook_id="'.(int)$post_array['notebook_id'].'" and sent_login_id ="'.(int)$this->login_id.'"', $post_array,'content,orders_id,to_login_id,update_date');
				$messageStack->add_session('数据更新成功', 'success');	//当操作成功后跳到新页面时用此方法记录成功提示信息
			}
			return true;
		}
		//$messageStack->add('失败', 'error');	//不重新跳到新页面时用此方法记录错误提示信息
		return false; //成功返回true失败返回false
	}

	/**
	 * 回复留言
	 *
	 * @param unknown_type $post_array
	 * @param unknown_type $update_where
	 * @return none
	 */
	public function reply($post_array, $update_where=''){
		global $messageStack;
		$error = false;
		if(!tep_not_null($post_array['answer_content'])){
			$error=true;$messageStack->add('回复内容不能为空','error');
		}
		if($error==false){
			$sql = 'SELECT answer_content FROM '.$this->tables.' WHERE notebook_id="'.(int)$post_array['notebook_id'].'" AND is_deleted=0 AND is_finished=0 ';
			$sql_query = tep_db_query($sql);
			while($rows =  tep_db_fetch_array($sql_query))
			{
				$data = $rows;
			}
			//print_r($data); exit();
			$post_content=$post_array['answer_content'];
			if(is_array($data))
			{
				$post_array['answer_content'] = $post_array['answer_content'] . "\n" . $data['answer_content'];
				$post_array['answer_date']=date('Y-m-d H:i:s');
				$post_array['is_replyed']=1;
				$post_array['answer_login_id']=$this->login_id;
				//print_r($post_array);//exit();
				tep_db_fast_update($this->tables, 'notebook_id="'.(int)$post_array['notebook_id'].'" AND is_deleted=0 AND is_finished=0 ', $post_array,'answer_date,answer_login_id,answer_content,is_replyed,is_finished');
				$this->addHistoryReplay((int)$post_array['notebook_id'], $post_content);
				$messageStack->add_session('回复成功!','success');
			}
		}
	}
	/**
	 * 增加留言的回复历史记录
	 * @param int $note_id 留言的ID
	 * @param string $content 留言的内容
	 */
	public function addHistoryReplay($note_id,$content){
		$str_sql='insert into notebook_history set notebook_id='.$note_id.' ,replay_content="'.$content.'", replay_user='.$this->login_id.',replay_time=now()';
		tep_db_query($str_sql);
	}
	/**
	 * 获取一个留言的历史记录
	 * @param unknown_type $note_id
	 */
	public function getHistoryReplay($note_id){
		$data=array();
		$str_sql='select t1.*,CONCAT(t2.admin_lastname," ", t2.admin_firstname,"(",t2.admin_job_number,")") AS admin_name FROM `admin` t2, notebook_history t1 where t1.replay_user=t2.admin_id and t1.notebook_id='.$note_id.' order by t1.replay_time DESC';
		$query=tep_db_query($str_sql);
		while($rows =  tep_db_fetch_array($query)){
			$data[]=$rows;
		}
		return $data;
	}
	/**
	 * 删除一条或多条留言
	 * @param in: int / array $notebook_ids 可以是单个留言id或数组如(array)$_POST['notebook_ids']
	 * @return boolen
	 */
	public function delete($notebook_ids){
		global $messageStack;
		$error = false;
		$where = ' where sent_login_id='.$this->login_id;
		$ids='';
		if (is_array($notebook_ids)){
			$where .= ' AND notebook_id IN ('.implode(',',$notebook_ids).') ';
			$ids=implode(',',$notebook_ids);
		}elseif (is_string($notebook_ids)){
			$where .= ' AND notebook_id = "'.$notebook_ids.'" ';
			$ids=$notebook_ids;
		}else {
			$error = true;
			$messageStack->add('无删除目标的ID号，本次删除失败！','error');
		}
		//书写数据库代码
		if($error==false){
			tep_db_query('UPDATE '.$this->tables.' SET is_deleted=1,deleted_login_id='.$this->login_id.', delete_date="'.date('Y-m-d H:i:s').'"'.$where);
			$messageStack->add_session('ID号为:'.$ids.'的留言记录成功被删除！');
			return true;
		}
		return false;
	}
	/**
	 * 根据条件列出留言数据，返回数组或false
	 *
	 * @param unknown_type $tables 要读取的数据表,可取多表,如 table1_1 t1, table_2 t2
	 * @param unknown_type $fields 要读取的字段，默认为*
	 * @param unknown_type $where 条件，默认为1
	 * @param unknown_type $group_by 默认为空 GROUP BY 的内容，如 GROUP BY abc
	 * @param unknown_type $order_by 排序方式默认为空。如ORDER BY t1.t_it DESC
	 * @return unknown array $data or false	其中$data['splitPages']为数据的分页信息
	 */
	public function lists($tables ='', $fields = '*', $where='',$group_by='',$order_by=''){
		$data = false;
		if(!tep_not_null($tables)){
			$tables = $this->tables;
		}
		$pageMaxRowsNum = 10; //每页显示10条记录
		$sql = 'SELECT '.$fields.' FROM '.$tables.' where is_deleted=0 AND '.$where.$group_by.$order_by;
		//echo $sql;
		$keywords_query_numrows = 0;
		$_split = new splitPageResults($_GET['page'], $pageMaxRowsNum, $sql, $keywords_query_numrows);
		//var_dump($_split); exit;
		$data['splitPages']['count'] = $_split->display_count($keywords_query_numrows, $pageMaxRowsNum, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);//分页数据,显示总数
		$data['splitPages']['links'] = $_split->display_links($keywords_query_numrows, $pageMaxRowsNum, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ;//分页数据翻页
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

	/*
	 *列出admin的列表
	 *@param none
	 *@return array $arr / false
	 */
	public function admin_list()
	{
		$data=false;
		$sql="SELECT admin_id,CONCAT(admin_lastname,' ', admin_firstname,'(',admin_job_number,')') AS admin_name FROM `admin` where admin_groups_id<>0 ORDER BY admin_job_number asc";
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
	/**
	 * 改变某个留言的当前状态
	 * @param int $id 留言id
	 * @param int $value 状态值：1已解决，2为未解决
	 * @return int 没有修改时返回0，成功修改时返回成功修改的记录数
	 */
	public function changeNext($id,$value,$owner_id){
		if($owner_id==$_SESSION['login_id']){
			$sql_add=' ,owner_click=1,owner_click_time="'.date('Y-m-d H:i:s').'" ';
		}
		$str_sql='update '.$this->tables.' set next_status='.(int)$value.$sql_add.' where notebook_id='.(int)$id;
		tep_db_query($str_sql);
		return tep_db_affected_rows();
	}
	/**
	 * 给目标留言解决人添加/扣减分数（确定积分）
	 * @param int $notebook_id 留言编号
	 * @param int $status_value 问题解决的状态值1为已解决，2为未解决，其它值将不扣也不减分
	 * @param Assessment_Score $AS 客服考核类
	 * @return 
	 */
	public function add_confirm_score($notebook_id, $status_value, Assessment_Score $AS){
		$login_id = $_SESSION['login_id'];
		if($login_id && in_array($status_value,array('1','2'))){ //如果更新成功就根据value给留言对象加+1分（已解决）或-1分（未解决）
			$info = $this->getnote($notebook_id);
			$info = $info[0];
			if($info['to_login_id'] && $login_id!=$info['to_login_id']){	//自己不能给自己+-分
				$score = '1';
				$score_cotent .= tep_get_admin_customer_name($login_id).'给'.tep_get_admin_customer_name($info['to_login_id']);
				if($status_value==1){
					$score_cotent.= '加'.$score.'分，原因：帮助同事解决问题！';
				}else if($status_value==2){
					$score = '-1';
					$score_cotent.= '扣'.$score.'分，原因：没有解决问题！';
				}
				if($AS->add_pending_score($info['to_login_id'],$score,$score_cotent,'notebook_id',$notebook_id,'1',$login_id, 2,$info['notebook_id'])){
					return $score;	//返回成功添加的分数
				}
			}
		}
		return '';
	}
}
?>