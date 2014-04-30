<?php
/**
 * 产品 KTD管理
 * @author wtj
 * @date 2013-8-21
 */
class ProductMetaTag extends ProductList{
	private $_table_name='products_description';
	function __construct(){
		parent::__construct();
		$this->_fileds='p.products_id,p.agency_id,p.products_urlname,pd.products_name,pd.products_head_title_tag,pd.products_head_desc_tag,pd.products_head_keywords_tag';
	}
	/**
	 * 修改KTD
	 * @param int $id 产品ID
	 * @param string $value 修改为的值
	 * @param string $type 类型，ktd的那一种。
	 * @return void|number
	 */
	function change($id,$value,$type){
		switch($type){
			case 'tt': $fileds_name='products_head_title_tag';break;
			case 'dt':$fileds_name='products_head_desc_tag';break;
			case 'kt':$fileds_name='products_head_keywords_tag';break;
			default:return;break;
		}
		$this->changeOneLine($this->_table_name, 'products_id', $id, $fileds_name, strip_tags($value));
		return 0;
	}
	function createWhere($get){
		$str='';
		$str.=$get['agency_id']?' AND p.agency_id='.(int)$get['agency_id']:'';
		$str.=$get['product_id']?' AND p.products_id='.(int)$get['product_id']:'';
		$str.=$get['tt']?' AND pd.products_head_title_tag like"%'.$get['tt'].'%"':'';
		$str.=$get['dt']?' AND pd.products_head_desc_tag like"%'.$get['dt'].'%"':'';
		$str.=$get['kt']?' AND pd.products_head_keywords_tag like"%'.$get['kt'].'%"':'';
		return $str;
	}
	
}