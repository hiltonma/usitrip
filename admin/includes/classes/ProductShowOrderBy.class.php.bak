<?php
/**
 * @author wtj
 * @date 2013-8-7
 */
class ProductShowOrderBy {
	private $_table_name='products_default_order';
	/**
	 * 天数的数组
	 * @var array
	 */
	public $_day_array = array (
			'' => '不限',
			'1' => '1天以内',
			'2' => '2天',
			'3' => '3天',
			'4' => '4天',
			'5' => '5天',
			'6' => '6天',
			'7' => '7天',
			'8' => '8天',
			'9' => '9天',
			'10' => '10天',
			'11' => '11天',
			'12' => '2-3天',
			'13' => '4-5天',
			'14' => '6-7天',
			'15' => '8-9天',
			'16' => '10天及以上' 
	);
	/**
	 * 地点分类
	 * @var array
	 */
	public $_type_array = array (
			'' => '不限',
			'vcpackages' => '经典休闲游',
			'tours' => '周边热点游',
			'special' => '限时团购' 
	);
	public $_categories_array=array(
			''=>'首页',
			'25'=>'美东',
			'24'=>'美西',
			'33'=>'夏威夷',
			'54'=>'加拿大'
			);
	/**
	 * 网站最上排：美东，美西
	 * @var int
	 */
	private $_place = '';
	/**
	 * 开始城市
	 * @var int
	 */
	private $_start_city = '';
	/**
	 * 结束城市
	 * @var int
	 */
	private $_end_city = '';
	/**
	 * 天数
	 * @var int
	 */
	private $_days = '';
	/**
	 * 查询关键字
	 * @var string
	 */
	private $_pri_key = '';
	/**
	 * 类型1经典2周边3限时抢购
	 * @var int
	 */
	private $_type = '';

	/**
	 *
	 * @param int $place
	 * @param int $start_place 开始城市
	 * @param int $end_place 结束城市
	 * @param int $days 天数
	 * @param string $pri_key 关键字
	 * @param int $type 类型1经典2周边3限时抢购
	 */
	public function __construct($place = '', $start_place = '', $end_place = '', $days = '', $pri_key = '', $type = '') {
		$this->_place = $place;
		$this->_start_city = $start_place;
		$this->_end_city = $end_place;
		$this->_days = $days;
		$this->_pri_key = $pri_key;
		$this->_type = $type;
	}
	/**
	 * 获取城市
	 * @param string $city_name city 名称的一些字段
	 * @param int $type 类型，1为出发地，0为目的地，默认是1
	 * @return array
	 */
	public function getPlace($city_name,$type=1) {
		$type==2?$type=0:'';
		if($type==1)
			$tmp='departure_city_status='.(int)$type.' and ';
		$str_sql='select city_id,city from tour_city where '.$tmp.' city  like BINARY "%'.iconv('utf-8', CHARSET, $city_name).'%" limit 10';
// 		echo $str_sql;
		$query=tep_db_query($str_sql);
		$rows=array();
		$i=0;
		while($row=tep_db_fetch_array($query)){
			$rows[$i]['city_id']=$row['city_id'];
			$rows[$i]['city']=iconv(CHARSET, 'utf-8', $row['city']);
			$i++;
		}
		return $rows;
	}

	/**
	 * 获取要优先显示的ID
	 */
	public function getShowId() {
		$str_sql.='SELECT products_ids,products_default_order_id FROM '.$this->_table_name.$this->createWhere();
		$info=tep_db_fetch_array(tep_db_query($str_sql));
		return $info;
	}
	/**
	 * 创建查找的where条件
	 * @return string
	 */
	private function createWhere(){
		$str_where=' WHERE ';
		$str_where.=' categories_id='.($this->_place?$this->_place:'0');
		$str_where.=' AND categories_tag='.($this->_type?'"'.$this->_type.'"':'""');
		$str_where.=' AND departure_city_id='.($this->_start_city?$this->_start_city:'0');
		$str_where.=' AND destination_city_id='.($this->_end_city?$this->_end_city:'0');
		$str_where.=' AND day_range='.($this->_days?$this->_days:'0');
		$str_where.=' AND keyword='.($this->_pri_key?'"'.$this->_pri_key.'"':'""');
		return $str_where;
	}
	public function changeOne($id_str,$id){
		$str_sql='UPDATE '.$this->_table_name.' set products_ids="'.$id_str.'" where products_default_order_id='.(int)$id;
		tep_db_query($str_sql);
		return 0;
	}
	/**
	 * 增加
	 * @param string $id_str
	 * @return insert id
	 */
	public function addOne($id_str) {
		$data=array(
				'categories_id'=>$this->_place,
				'categories_tag'=>$this->_type,
				'departure_city_id'=>$this->_start_city,
				'destination_city_id'=>$this->_end_city,
				'day_range'=>$this->_days,
				'keyword'=>$this->_pri_key,
				'products_ids'=>$id_str
				);
		tep_db_fast_insert($this->_table_name, $data);
		return tep_db_insert_id();
	}

	/**
	 * 删除
	 * @param id 产品列表默认排序表ID
	 * @return number
	 */
	public function dropOne($id) {
		$str_sql='DELETE FROM '.$this->_table_name.' WHERE products_default_order_id='.(int)$id;
		tep_db_query($str_sql);
		return 0;
	}
	/**
	 * 同过$_type_array等划出相应的option选项
	 * @param array $arr $_type_array等，例子参考$_type_array
	 * @param int|string $value 用于value比较的值
	 * @return string
	 */
	public function drawOption($arr,$value=''){
		$str_return='';
		foreach($arr as $key=>$val){
			$str_return.='<option value="'.$key.'"  '.(($key==$value)?'selected':'').'>'.$val.'</option>';
		}
		return $str_return;
	}
	/**
	 * 获取categories 的信息，通过父ID
	 * @param int $parent_id 父ID号
	 * @return string
	 */
	public function getCategories($parent_id=0,$value=''){
		$str_sql='select cd.categories_id,cd.categories_name from categories c,categories_description cd where c.categories_id=cd.categories_id and c.parent_id='.(int)$parent_id;
		$sql_query=tep_db_query($str_sql);
		$str_return='下一级目录：<select name="category_sun"><option value=0>请选择</option>';
		$mark=false;
		while($row=tep_db_fetch_array($sql_query)){
			$mark=true;
			$str_return.='<option value="'.$row['categories_id'].'" '.(($value==$row['categories_id'])?'selected':'').'>'.$row['categories_name'].'</option>';
		}
		if(!$mark)return;
		$str_return.='</select>';
		return $str_return;
	}
}