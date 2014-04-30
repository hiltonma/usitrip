<?php
/**
 * 文章类别管理
 * @author wtj
 * @date 2013-11-6
 */
class RaidersCatalog extends T {
	private $deep;
	function __construct() {
		$this->setTableName('raiders_type');
		$this->setIdName('type_id');
	}
	/**
	 * (non-PHPdoc)
	 * @see T::set()
	 */
	function set($arr) {
		switch ($this->select_type) {
			case 1: //列表页
				$this->setFileds('type_id,type_name,parent_id');
				$this->setOderBy('parent_id');
			case 2: //后台列表页
				$str_sql='SELECT DISTINCT t1.type_id,t1.type_name,t1.parent_id,IF(t1.parent_id=0,"根目录",t2.type_name) AS parent_name FROM '.$this->table.' t1,'.$this->table.' t2 WHERE t1.parent_id=t2.type_id OR t1.parent_id=0 order by t1.type_id';
				$this->setStrSql($str_sql);
			case 3 : // 通过父类ID获取相对于的类型
				$this->setFileds('type_id,type_name');
				$this->data=array('parent_id'=>$arr);
		}
	}
	/**
	 * 生成OPTION的无线处理
	 * @param array $arr 查询出来的数组
	 * @param int $select 选中的项
	 * @param int $parent_id 父级ID
	 * @param int $deep 深度
	 * @return string
	 */
	function getOptionShow($arr, $select = '', $parent_id = 0, $deep = 0) {
		$str_need = '';
		$str_nbsp = '';
		for ($i = 0; $i < $deep; $i ++) {
			$str_nbsp .= '&nbsp;&nbsp;';
		}
		foreach ($arr as $key => $value) {
			if ($select) {
				$select_str = ($select == $value['type_id']) ? 'selected' : '';
			}
			if ($value['parent_id'] == $parent_id) {
				$str_need .= '<option value="' . $value['type_id'] . '" ' . $select_str . '>' . $str_nbsp . $value['type_name'] . '</option>';
				unset($arr[$key]);
				$str_need .= $this->getOptionShow($arr, $select, $value['type_id'], ++ $deep);
			}
		}
		return $str_need;
	}
	/**
	 * 获取无限级分类的OPTION
	 * @param int $select 选中的ID
	 * @return string
	 */
	function getOption($select = '') {
		$this->setGetType(1);
		$info = $this->getList();
		return $this->getOptionShow($info, $select);
	}
	/**
	 * 通过父ID获取数组信息
	 * @param int $parent_id 父ID
	 */
	function getInfoFromParentId($parent_id){
		$this->setGetType(3);
		$arr=$this->getList($parent_id);
		return $arr;
	}
	/**
	 * 获取后台用于增加的select OPTIN选项
	 */
	function getBackAddOption(){
		$arr=$this->getInfoFromParentId(0);
		$str_need='';
		foreach($arr as $value){
			$str_need.="<option value='$value[type_id]'>$value[type_name]</option>";
		}
		return $str_need;
	}
	/**
	 * 删除
	 */
	function dropType($type_id){
		$str_sql="SELECT article_id FROM raiders_article WHERE article_type=".$type_id.' LIMIT 1';
		if($this->doSelect($str_sql)&&$this->getInfoFromParentId($type_id)){
			return 0;
		}else{
			if(!is_null($this->getParentIdByType($type_id))){
				$this->dropOne($type_id);
			return 1;
			}else{
				return 0;
			}
			
		}
	}
	/**
	 * 修改
	 * @param string $type_name 类型名称
	 * @param int $type_id 类型ID
	 */
	function changeOne($type_name,$type_id){
		return $this->update(array('type_name'=>$type_name), $type_id);
	}
	/**
	 * 后台显示分类
	 */
	function getTd() {
		
		$this->setGetType(2);
		$info = $this->getList();
		return $info;
	}
	/**
	 * 获取前台的现实列表数组
	 * @param int $parent 父级别ID
	 * @return array
	 */
	function getIndexInfo($parent){
		$arr=$this->getInfoFromParentId(0);
		foreach($arr as $key=>$value){
			if($value['type_id']==$parent&&$parent!=0){
				$arr[$key]['son']=$this->getInfoFromParentId($parent);
			}else{
				$arr[$key]['son']=array();
			}
		}
		return $arr;
	}
	/**
	 * 创建所有父类能拿到的所有类型
	 * @param int $parent_id
	 * @return string
	 */
	function createAllTypeByParentId($parent_id){
		$str_need='';
		if($parent_id==0)
			return 0;
		$info=$this->getInfoFromParentId($parent_id);
		foreach($info as $value){
			$str_need.=','.$value['type_id'];
		}
		return substr($str_need, 1).','.$parent_id;
	}
	function getTypeName($id=0,$parent_id=0){
		$str_sql='SELECT type_name FROM '.$this->table.' WHERE type_id='.($id?$id:$parent_id);
		$this->setStrSql($str_sql);
		$info=$this->getList();
		return $info[0]['type_name'];
	}
	function getParentIdByType($type_id){
		$info=$this->getOne($type_id);
		return $info['parent_id'];
	}
}