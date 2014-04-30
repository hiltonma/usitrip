<?php
/**
 * 数据库基本操作类，此类会被具体操作的数据库功能继承
 * @author tieGump
 * @package 数据库
 */
abstract class T{
	protected $db,$lang_id;
	protected $table;
	protected $id_name;
	protected $page_max=20;
	protected $page_holder='page';
	protected $fildes='*';
	protected $order_by='';
	protected $group_by='';
	protected $limit='';
	protected $data=array();
	protected $data_like=array();
	protected $str_sql='';
	protected $select_type=1;
	protected $clear_post=1;
	protected $retrun_data=array();
	function doSelect($str_sql){
		$data=array();
		$query=tep_db_query($str_sql);
		while($row=tep_db_fetch_array($query)){
			$data[]=$row;
		}
		return $data;
	}
	function setOderBy($order_by){
		$this->order_by=$order_by;
	}
	function setGroupBy($group_by){
		$this->group_by=$group_by;
	}
	function setIdName($id_name){
		$this->id_name=$id_name;
	}
	function setTableName($table_name){
		$this->table=$table_name;
	}
	function setFileds($fildes='*'){
		$this->fildes=$fildes;
	}
	function setLimit($limit){
		$this->limit=$limit;
	}
	function clearData(){
		$this->data=array();
		$this->str_sql='';
	}
	function setStrSql($str_sql){
		$this->str_sql=$str_sql;
	}
	abstract function set($arr);
	function createWhere(){
		if($this->data){
			$need='';
			foreach($this->data as $key=>$value){
				$need.=" AND $key='$value'";
			}
			foreach($this->data_like as $key=>$value){
				$need.=" AND $key like '$value'";
			}
			return $need;
		}else{
			return '1';
		}
	}
	function setGetType($type){
		$this->select_type=$type;
	}
	function getSql(){
		if($this->str_sql){
			return $this->str_sql;
			
		}else{
			$str_sql='SELECT '.$this->fildes.' FROM '.$this->table.' WHERE 1 '.$this->createWhere().($this->order_by?' ORDER BY '.$this->order_by:'').($this->group_by?'GROUP BY '.$this->group_by:'').($this->limit?' LIMIT '.$this->limit:'');
			return $str_sql;
		}
	}
	function getPageList($arr=array()){
		$this->set($arr);
		$str_sql=$this->getSql();
		$track_query_numrows = 0;
		$max_rows_every_page = MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
		$track_split = new splitPageResults($HTTP_GET_VARS['page'], $max_rows_every_page, $str_sql, $track_query_numrows);
		$a = $track_split->display_links($track_query_numrows, $max_rows_every_page, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array (
				'page',
				'y',
				'x',
				'action'
		)));
		$b = $track_split->display_count($track_query_numrows, $max_rows_every_page, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		$this->clearData();
		$data['info']=$this->doSelect($str_sql);
		$data['page_info_a']=$a;
		$data['page_info_b']=$b;
		return $data;
	}
	function getList($arr=array()){
		$this->set($arr);
		$str_sql=$this->getSql();
		$this->clearData();
		return $this->doSelect($str_sql);
	}
	function getOne($where){
		$str_sql='SELECT '.$this->fildes.' FROM '.$this->table.' WHERE ';
		if(is_numeric($where)){
			$str_sql.=$this->id_name.'='.$where;
		}else{
			$str_sql.=$where;
		}
		$query = tep_db_query($str_sql);
		$rows = tep_db_fetch_array($query);
		return $rows;
	}
	function update($post,$where){
		$need=$this->clearPost($post);
		$where=$this->idToOne($where);
		$str_sql='UPDATE '.$this->table.' SET '.$need.' WHERE '.$where;
		tep_db_query($str_sql);
		return 1;
	}
	function addOne($post){
		$need=$this->clearPost($post);
		$str_sql="INSERT INTO ".$this->table.' SET '.$need;
		tep_db_query($str_sql);
		return tep_db_insert_id();
	}
	function getInsertId(){
		return tep_db_insert_id();
	}
	function addN($post){
		$filed='';
		$need=array();
		foreach($_POST as $key=>$value){
			$filed.=','.$key;
			$i=0;
			foreach ($value as $v){
				!isset($need[$i])?$need[$i]='':'';
				$need[$i].=','.$v;
				$i++;
			}
		}
		$val='';
		foreach ($need as $value){
			$val.=',('.substr($value, 1).')';
		}
		$str_sql= 'INSERT INTO '.$this->table.'('.substr($filed,1).')VALUES'.substr($val, 1);
		tep_db_fetch_array($str_sql);
	}
	function dropOne($where){
		$str_sql='DELETE FROM '.$this->table.' WHERE '.$this->idToOne($where);
		tep_db_query($str_sql);
	}
	function idToOne($id){
		$return='';
		if(is_array($id)){
			$need=$this->id_name.' IN ('.join(',', $id).')';
		}elseif(is_numeric($id)){
			$need=$this->id_name.'='.$id;
		}else{
			$need=$id;
		}
		return $need;
	}
	function clearPost($post,$interval=','){
		if(!$post)
			return 1;
		if($this->clear_post){
			tep_db_prepare_input($post);
		}
		$need='';
		foreach($post as $key=>$value){
			$need.=' '.$interval." $key='$value'";
		}
		$need=substr($need, strlen($interval)+1);
		return $need;
	}
	function twoToOne($arr){
		if(!is_array($arr))
			return 0;
		$need=array();
		foreach($arr as $v){
			$need[]=$v;
		}
		return $need;
	}
}
?>