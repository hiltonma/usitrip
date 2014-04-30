<?php
/**
 * api接口
 * @author Howard
 */ 
interface api{
	/**
	 * 设置产品列表的xml数据
	 * @param DOMDocument $dom xml类
	 * @param array $datas 产品数据
	 */
	public function set_list_xml(DOMDocument $dom, $datas);
	/**
	 * 设置产品详细数据的xml
	 * @param int $products_id类 产品id
	 */
	public function set_product_details_xml($products_id);
}
?>