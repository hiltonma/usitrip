<?php
/**
 * 留言本类，只用于后台。以下功能未添加，由许月方实施具体功能。
 *
 */
class guestbook {
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
	 * 获取某条留言本内容
	 *@param  string $notebook_id (记事本ID)
	 *@return array $arr / false
	*/
	public function getnote($notebook_id){
		global $messageStack;
		$error = false;
		if(!tep_not_null($_GET['notebook_id'])){
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
			$post_array['answer_date']=date('Y-m-d H:i:s');
			$post_array['is_replyed']=1;
			$post_array['answer_login_id']=$this->login_id;
			print_r($post_array);//exit();
			tep_db_fast_update($this->tables, 'notebook_id="'.(int)$post_array['notebook_id'].'" AND is_deleted=0', $post_array,'answer_date,answer_login_id,answer_content,is_replyed,is_finished');
		    $messageStack->add_session('回复成功!','success');
		}		
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
	public function adminList()
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
	 * @param array $adminList 管理员信息数组
	 * @return string $text
	 */
	public function getadminname($admin_id,$adminList){
		if($admin_id=="0" or $admin_id==""){
			return '';
		}
		$n=count($adminList);
		for($i=0;i<$n;$i++){
			if($adminList[$i]['id']==$admin_id){
				return $adminList[$i]['text'];
			}
		}
		return '';
	}
}
?>