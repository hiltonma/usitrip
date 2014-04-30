<?php 
/**
 * 根据大分类ID获取对应所有产品的出发城市与结束城市
 * 用在结伴同游栏目中发起结伴贴 进行筛选 线路用
 * @author lwkai 2012-06-21
 *
 */
class companions_line_filter{
	/**
	 * 当前区域ID [类别ID]
	 * @var int
	 */
	private $categories_id = 0;
	
	/**
	 * 子类别集合
	 * @var string
	 */
	private $subcategory_ids = '0';

	
	private $products_ids = '';
	
	/**
	 * 根据大分类ID取得所有小分类ID
	 * @param int $categories_id 大分类ID
	 */
	public function __construct($categories_id){
		if((int)$categories_id > 0) {
			$this->categories_id = (int)$categories_id;
			$this->get_all_categories();
		}
	}
	
	/**
	 * 根据区域ID 得到所有子景点ID
	 */
	private function get_all_categories(){
		$sql = tep_db_query("select categories_id from categories where parent_id='" . $this->categories_id . "' and categories_status=1");
		if (tep_db_num_rows($sql) > 0) {
			while ($row = tep_db_fetch_array($sql)) {
				$rtn[] = $row['categories_id'];
			}
			$rtn = join(',',$rtn);
		}
		$rtn = ($rtn ? $rtn : '0');
		$this->subcategory_ids = $rtn;
	}
	
	/**
	 * 根据子类别得到所有产品ID
	 */
	private function get_all_products_id(){
		$sql = tep_db_query("select products_id from products_to_categories where categories_id in (" . $this->subcategory_ids . ") and products_id <> 0");
		if (tep_db_num_rows($sql) > 0) {
			while ($row = tep_db_fetch_array($sql)) {
				$rtn[] = $row['products_id']; 
			}
			$rtn = join(',',$rtn);
		}
		$rtn = ($rtn ? $rtn : '0');
		$this->products_ids = $rtn;
	}
	
	/**
	 * 根据子分类ID取得对应线路下的所有结束城市 ID
	 * @return string
	 */
	private function get_departure_end_city_id(){
		$sql = tep_db_query("select DISTINCT p.departure_end_city_id  from products p, products_to_categories p2c  where p.products_status = '1' and p.products_id = p2c.products_id  and p2c.categories_id in (" . $this->subcategory_ids . ") and p2c.products_id <> 0");
		if (tep_db_num_rows($sql) > 0) {
			while ($row = tep_db_fetch_array($sql)) {
				$rtn[] = $row['departure_end_city_id'];
			}
			$rtn = join(',',$rtn);
			$rtn = preg_replace("/,+/",',',$rtn);
		}
		$rtn = ($rtn ? $rtn : '0');
		return $rtn;
	}
	
	/**
	 * 根据子分类ID取得对应线路下的所有出发城市ID
	 * @return string
	 */
	private function get_departure_city_ids(){
		$sql = "select DISTINCT p.departure_city_id  from products p, products_to_categories p2c  where p.products_status = '1' and p.products_id = p2c.products_id  and p2c.categories_id in (" . $this->subcategory_ids . ") and p2c.products_id <> 0";
		$sql = tep_db_query($sql);
		if (tep_db_num_rows($sql) > 0) {
			while ($row = tep_db_fetch_array($sql)) {
				$rtn[] = $row['departure_city_id'];
			}
			$rtn = join(',', $rtn);
			$rtn = preg_replace("/,+/",',',$rtn);
		}
		$rtn = ($rtn ? $rtn : '0');
		return $rtn;
	}
	
	/**
	 * 获取当前区域所有线路的结束城市
	 * @return array [array('城市ID'=>'城市名称')]
	 */
	public function get_end_departure_city(){
		$depature_city_ids = $this->get_departure_end_city_id();
		return $this->get_city($depature_city_ids);
	}
	
	/**
	 * 获取当前区域的所有线路的出发城市
	 * @return array [array('城市ID'=>'城市名称')]
	 */
	public function get_departure_city(){
		$depature_city_ids = $this->get_departure_city_ids();
		return $this->get_city($depature_city_ids);
	}
	
	/**
	 * 根据传入的城市ID取得对应的城市名称
	 * @param string $depature_city_ids 城市的ID[2,3,4,5]
	 * @return array [array('城市ID'=>'城市名称')]
	 */
	private function get_city($depature_city_ids){
		$sql = tep_db_query("select city_id, city from tour_city where city_id in (" . $depature_city_ids . ") AND departure_city_status = '1' AND `is_attractions` !='1' order by city");
		$rtn = array();
		if (tep_db_num_rows($sql) > 0) {
			while ($row = tep_db_fetch_array($sql)) {
				$rtn[$row['city_id']] = $row['city'];
			}
		}
		return $rtn;
	}
	
	
	
	/**
	 * 根据出发城市或者结束城市找出可进行结伴同游的线路
	 * @param string $start_city_id 出发城市
	 * @param string $end_city_id  结束城市
	 * @return array
	 */
	public function get_products_name($start_city_id,$end_city_id){
		if (TRAVEL_COMPANION_OFF_ON == 'true') {
			$sql = "select p.products_id,pd.products_name from products_description as pd,products as p where p.products_id = pd.products_id";
			if (tep_not_null($start_city_id) != false) {
				$sql .= ' and FIND_IN_SET("' . $start_city_id . '",p.departure_city_id)';
			}
			if (tep_not_null($end_city_id) != false) {
				$sql .= ' and FIND_IN_SET("' . $end_city_id . '",p.departure_end_city_id)';
			}
			$sql .= " and p.display_room_option = 1 and p.products_status=1 and p.is_hotel=0";
			$data = tep_db_query($sql);
			$rtn = array();
			while ($row = tep_db_fetch_array($data)) {
				$rtn[] = $row;
			}
			return $rtn;
		}
	}
}
?>