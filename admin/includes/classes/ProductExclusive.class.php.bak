<?php
/**
 * 独家特惠
 * @author wtj
 * @date 2013-8-5
 */
class ProductExclusive extends ProductList{
	/**
	 * 构造方法,生成要查询的字段,缩短执行时间
	 */
	function __construct(){
		$this->_fileds='p.agency_id,p.products_id,p.only_our_free,pd.products_name';
	}
	/**
	 * (non-PHPdoc)
	 * @see ProductList::createWhere()
	 */
	function createWhere($get){
		$str='';
		$str.=$get['agency_id']?' AND p.agency_id='.(int)$get['agency_id']:'';
		$str.=$get['product_id']?' AND p.products_id='.(int)$get['product_id']:'';
		$str.=$get['pri_key']?' AND p.only_our_free like BINARY "%'.$get['pri_key'].'%"':'';
		return $str;
	}
	/**
	 * 更改独家特惠
	 * @param string $content 独家特惠的内容
	 * @param array||int $id 产品ID
	 * @return number
	 */
	function changeOne($content,$id){
		if(!$id)
			return;
		$str_sql='update '.$this->_product_table.' set only_our_free="'.$content.'" where ';
		$str='';
		if(is_array($id)){
		foreach ($id as $value){
			$str.=','.$value;
		}
		$str='('.substr($str, 1).')';}
		else $str='('.$id.')';
		$str_sql.=' products_id in'.$str;
		tep_db_query($str_sql);
		return 0;
	}
}