<?php
/**
 * 把获取供应商信息的东西独立拿出来，因为可能很多地方要用 避免重复
 * @author wtj
 * @date 2013-8-2
 */
class getAgency{
	private $_table_name='';
	/**
	 * 获取供应商信息
	 * @return array
	 */
	function get() {
		$str_sql = 'select agency_id,agency_name,agency_name1 from tour_travel_agency';
		return $this->doSelect($str_sql);
	}
	
	/**
	 * 查询
	 * @param string $str_sql 查询的SQL语句
	 * @return array
	 */
	function doSelect($str_sql) {
		$return = array ();
		$sql_query = tep_db_query($str_sql);
		while ($row = tep_db_fetch_array($sql_query)) {
			$return[] = $row;
		}
		return $return;
	}
	/**
	 * 生成供应商的OPTION
	 * @param array $array 数组
	 * @param string|int $val 判断等于的值
	 * @return string
	 */
	function dreawAgencyOption($array, $val = '') {
		$str_return = '';
		foreach ($array as $value) {
			if ($val == $value['agency_id'])
				$str_return .= "<option value='$value[agency_id]' selected>$value[agency_name]</option>";
			else
				$str_return .= "<option value='$value[agency_id]'>$value[agency_name]</option>";
		}
		return $str_return;
	}
	/**
	 * 供应商二维数组降成一维
	 * @param array $arr agency数组
	 * @return array
	 */
	function createOneAgency($arr) {
		$arr_return = array ();
		foreach ($arr as $key => $value) {
			$arr_return[$value['agency_id']] = $value['agency_name'];
		}
		return $arr_return;
	}
}