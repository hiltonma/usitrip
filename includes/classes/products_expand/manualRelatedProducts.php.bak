<?php
/**
 * 手工关联的产品类，一般用于选择乐园等产品场合，目的是让用户快速找到与某产品有关联的其它产品。如涉及到选择乐园、峡谷的产品等！
 * @package 
 * @author Howard by 2012-10-13
 */
class manualRelatedProducts{
	/**
	 * 产品ID
	 * @var int
	 */
	public $product_id;
	/**
	 * 关联产品的格式范例
	 * @var string
	 */
	public $related_format_example = '产品id=>内容;id1=>内容1...123=>迪士尼;456=>海洋世界...';
	
	public function __construct($product_id){
		$this->product_id = (int)$product_id;
		if(!(int)$this->product_id) die('没有传入正确的产品ID');
	}
	/**
	 * 取得产品手工关联的产品信息，用于前台显示
	 * @param int $product_id
	 * @return array|false
	 */
	public function getManualRelatedInfo($product_id=0){
		$data = false;
		if(!(int)$product_id) $product_id = $this->product_id;
		$sql = tep_db_query('SELECT manual_related_products_title, manual_related_products_content FROM `products_description` WHERE products_id="'.(int)$product_id.'" AND language_id="1" ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['manual_related_products_title'])){
			$data['title'] = $row['manual_related_products_title'];
			$array = explode(';',$row['manual_related_products_content']);
			$data['content'] = array();
			foreach((array)$array as $val){
				$_array = explode('=>',$val);
				if((int)$_array[0]) $data['content'][] = array('id'=>(int)$_array[0], 'text'=>trim($_array[1]), 'href'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_array[0]));
			}
		}
		return $data;
	}
	/**
	 * 输入产品关联数据到数据库，用于后台数据写入
	 * @param int $product_id 产品ID
	 * @param string $title 手工关联产品标题
	 * @param string $content 手工关联产品内容。格式产品id=>内容;id1=>内容1...如：123=>迪士尼;456=>海洋世界...
	 * @param int $language_id 语言id
	 */
	public function inputManualRelated($product_id, $title, $content, $language_id=1){
		if((int)$product_id){
			$title = tep_db_prepare_input($title);
			$content = tep_db_prepare_input($content);
			$array = explode(';',$content);
			$_pids = array();
			foreach((array)$array as $val){
				$_array = explode('=>',$val);
				$_pids[] = (int)$_array[0];
			}
			if(in_array($product_id, $_pids)){	//添加产品关联
				for($i=0, $n=sizeof($_pids); $i<$n; $i++){
					tep_db_query('UPDATE `products_description` SET manual_related_products_title="'.tep_db_input($title).'", manual_related_products_content="'.tep_db_input($content).'" WHERE products_id="'.(int)$_pids[$i].'" AND language_id="'.(int)$language_id.'" ');
				}
			}else{	//删除产品关联(只能删除当前编辑的这个产品的)
				tep_db_query('UPDATE `products_description` SET manual_related_products_title="", manual_related_products_content="" WHERE products_id="'.(int)$product_id.'" AND language_id="'.(int)$language_id.'" ');
			}
		}
	}
}
?>