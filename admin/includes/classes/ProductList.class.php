<?php
/**
 * 产品列表类
 * @author wtj
 * @date 2013-8-5
 */
class ProductList{
	protected $page_number=50;
	private $number;
	protected $_product_table='products';
	protected $_des_table='products_description';
	protected $_fileds='p.*,pd.*';
	function __construct(){
		
	}
	/**
	 * 获取products list
	 * @param int $agency_id 供应商ID
	 * @param string $order_by 排序字段名，p==products,pd==products_description
	 * @return array 包含分页信息
	 */
	public function getList($get,$order_by=''){
		$str_sql='select '.$this->_fileds.' from '.$this->_product_table.' p,'.$this->_des_table.' pd where p.products_id=pd.products_id ';
// 		$agency_id?$str_sql.=' AND p.agency_id='.$agency_id:'';
		$str_sql.=$this->createWhere($get);
		$str_sql.=' ORDER BY products_head_desc_tag,products_head_title_tag,products_head_keywords_tag';
		//$order_by?$str_sql.='order by '.$order_by:'';
		//$this->number = tep_db_num_rows(tep_db_query($str_sql));
		//$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$track_query_numrows = 0;
		//分页的地方。给一次性返回回去
		$track_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $str_sql, $track_query_numrows);
		$a = $track_split->display_links($track_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array (
				'page',
				'y',
				'x',
				'action'
		)));
		$b = $track_split->display_count($track_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		return array (
				'info' => $this->doSelect($str_sql),
				'a' => $a,
				'b' => $b
		);
	}
	/**
	 * 查询
	 * @param string $str_sql 查询的SQL语句
	 * @return array
	 */
	protected function doSelect($str_sql) {
		$return = array ();
		$sql_query = tep_db_query($str_sql);
		while ($row = tep_db_fetch_array($sql_query)) {
			$return[] = $row;
		}
		return $return;
	}
	/**
	 * 更新一张表的一个字段
	 * @param string $table 表
	 * @param string $id_name 表ID的名称
	 * @param int $id_value 表ID的值
	 * @param string $fileds_name 要更改的字段的名称
	 * @param string||int $fileds_value 要更改字段的值
	 * @return number
	 */
	protected function changeOneLine($table,$id_name,$id_value,$fileds_name,$fileds_value){
		$str_sql="UPDATE $table set $fileds_name='$fileds_value' WHERE $id_name=".(int)$id_value;
		tep_db_query($str_sql);
		return 0;
	}
	/**
	 * 创建where条件用于子方法的
	 */
	protected function createWhere($get){
		
	}
}