<?php
//自动更新landing page 价格
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

/**
 * 专题页面资料自动修正
 * @author Howard
 */
class ajax_landing_page{
	/**
	 * 团号标签的id前缀
	 * @var string
	 */
	private $model_tag = 'model_';
	/**
	 * 价格标签的id前缀
	 * @var string
	 */
	private $new_price_tag = 'new_price_';
	/**
	 * 原价标签的id前缀
	 * @var string
	 */
	private $old_price_tag = 'old_price_';
	/**
	 * 产品标题的id前缀
	 * @var string
	 */
	private $title_tag = 'title_';
	/**
	 * 副标题的id前缀
	 * @var string
	 */
	private $sub_title_tag = 'sub_title_';
	/**
	 * 出发地id前缀
	 * @var string
	 */
	private $start_city_tag = 'start_city_';
	/**
	 * 结束地id前缀
	 * @var string
	 */
	private $end_city_tag = 'end_city_';
	/**
	 * 出团日期id标签
	 * @var string
	 */
	private $departure_date_tag = 'departure_date_';
	/**
	 * 行程特色id标签
	 * @var string
	 */
	private $small_description = 'small_description_';
	/**
	 * 产品图片的id前缀
	 * @var string
	 */
	private $image_tag = 'image_';
	/**
	 * 要自动修正的产品id串
	 * @var string
	 */
	private $p_ids;
	/**
	 * 最终要输出的js代码
	 * @var string
	 */
	public $js_str;

	/**
	 * 专题页面资料自动修正类
	 * 注意：（1）前端页面的产品价格id必须是以new_price_+产品id的格式；（2）标题id必须是title_+产品id；
	 * @example 
	 * 团号：<span id="model_3121">$100</span>
	 * 原价：<span id="old_price_3121">$100</span>
	 * 现价：<span id="new_price_3121">$98</span>
	 * 标题：<a id="title_3121" href="http://www.usitrip.com/niuyue-jiujinshan-hafo-daxiagu-guigu-15d-lvyou.html">纽约,旧金山,哈佛,大峡谷,硅谷十五日游</a>
	 * 副标题：<div id="sub_title_3121">2013年1月1日 - 2014年3月31日 : 周三/周六</div>
	 * 出团日期：<div id="departure_date_3121">2013年1月1日 - 2014年3月31日 : 周三/周六</div>
	 * 出发地：<div id="start_city_3121">纽约</div>
	 * 结束地：<div id="end_city_3121">纽约, 波士顿</div>
	 * 图片：<img id="image_3121" src="http://www.usitrip.com/web_action/familyfun/images/city2.jpg" />
	 * @param array $GET GET参数组
	 */
	public function __construct($GET){
		$this->p_ids = substr($_GET['p_ids'],0,-1);
		$this->js_str = '[JS]';
		$this->fixStart();
	}
	public function __destruct(){
		//print_r($this->Products);
		$this->js_str .= '[/JS]';
		$this->js_str = preg_replace('/[[:space:]]+/',' ',$this->js_str);
		echo $this->js_str;
	}
	/**
	 * 开始写修复代码
	 */
	public function fixStart(){
		$p = $this->getProducts();
		if($p){
			foreach ($p as $key => $val){
				$this->fixOldPrice($val);
				$this->fixNewPrice($val);
				$this->fixModel($val);
				$this->fixTitle($val);
				$this->fixSubTitle($val);
				$this->fixStartCity($val);
				$this->fixEndCity($val);
				$this->fixDepartureDate($val);
				$this->fixSmallDescription($val);
				$this->fixImage($val);
			}
		}
	}
	
	/**
	 * 修复团号
	 */
	private function fixModel($rows){
		$model = $this->model_tag . $rows['products_id'];
		$this->js_str .= '
			  var '.strtoupper($model).' = document.getElementById("'.$model.'");
				if('.strtoupper($model).'!=null){
					'.strtoupper($model).'.innerHTML = "'.$rows['products_model'].'";
				}
			  ';
	}
	/**
	 * 修复产品原价
	 */
	private function fixOldPrice($rows){
		$old_p = $this->old_price_tag . $rows['products_id'];
		$products_price = $rows['productsPrice'];
		$this->js_str .= '
			  var '.strtoupper($old_p).' = document.getElementById("'.$old_p.'");
				if('.strtoupper($old_p).'!=null){
					'.strtoupper($old_p).'.innerHTML = "'.$products_price.'";
				}
			  ';
		
	}
	/**
	 * 修复价格
	 */
	private function fixNewPrice($rows){		
		$new_p = $this->new_price_tag . $rows['products_id'];
		$price = $rows['newPrice'] ? $rows['newPrice'] : $rows['productsPrice'];
		$this->js_str .= '
			  var '.strtoupper($new_p).' = document.getElementById("'.$new_p.'");
				if('.strtoupper($new_p).'!=null){
					'.strtoupper($new_p).'.innerHTML = "'.$price.'";
				}
			  ';
	}
	/**
	 * 修复标题
	 */
	private function fixTitle($rows){
		$title = $this->title_tag . $rows['products_id'];
		$this->js_str .= '
			  var '.strtoupper($title).' = document.getElementById("'.$title.'");
				if('.strtoupper($title).'!=null){
					'.strtoupper($title).'.innerHTML = "'. db_to_html(tep_db_output(preg_replace('/\*\*.+/','',$rows['products_name']))).'";
				}
			  ';
	}
	/**
	 * 修复副标题
	 */
	private function fixSubTitle($rows){
		$subTitle = $this->sub_title_tag . $rows['products_id'];
		if(preg_match('/\*\*(.+)\*\*/', $rows['products_name'], $matches )){
			$this->js_str .= '
				  var '.strtoupper($subTitle).' = document.getElementById("'.$subTitle.'");
					if('.strtoupper($subTitle).'!=null){
						'.strtoupper($subTitle).'.innerHTML = "'. db_to_html(tep_db_output($matches[1])) .'";
					}
				  ';
		}
	}
	/**
	 * 修复出发地
	 */
	private function fixStartCity($rows){
		$tag = $this->start_city_tag . $rows['products_id'];
		$city = tep_get_product_departure_city($rows['products_id']);
		$this->js_str .= '
			  var '.strtoupper($tag).' = document.getElementById("'.$tag.'");
				if('.strtoupper($tag).'!=null){
					'.strtoupper($tag).'.innerHTML = "'. db_to_html(tep_db_output($city)) .'";
				}
			  ';
	}
	/**
	 * 修复结束地
	 * @todo 目前取的还是出发地
	 */
	private function fixEndCity($rows){
		$tag = $this->end_city_tag . $rows['products_id'];
		$city = tep_get_city_names($rows['departure_end_city_id']);
		$city = implode(', ', $city);
		$this->js_str .= '
			  var '.strtoupper($tag).' = document.getElementById("'.$tag.'");
				if('.strtoupper($tag).'!=null){
					'.strtoupper($tag).'.innerHTML = "'. db_to_html(tep_db_output($city)) .'";
				}
			  ';
	}
	/**
	 * 修复出团日期
	 */
	private function fixDepartureDate($rows){
		$tag = $this->departure_date_tag . $rows['products_id'];
		$data = tep_get_display_operate_info($rows['products_id'],1);
		$data = implode(', ', $data);
		$this->js_str .= '
			  var '.strtoupper($tag).' = document.getElementById("'.$tag.'");
				if('.strtoupper($tag).'!=null){
					'.strtoupper($tag).'.innerHTML = "'. tep_db_output($data) .'";
				}
			  ';
	}
	/**
	 * 修复行程特色
	 */
	private function fixSmallDescription($rows){
		$tag = $this->small_description . $rows['products_id'];
		$this->js_str .= '
			  var '.strtoupper($tag).' = document.getElementById("'.$tag.'");
				if('.strtoupper($tag).'!=null){
					'.strtoupper($tag).'.innerHTML = "'. db_to_html(tep_db_output($rows['products_small_description'])) .'";
				}
			  ';
	}
	/**
	 * 修复图片
	 * @todo 图片暂时不处理
	 */
	private function fixImage($rows){
		
	}
	/**
	 * 取产品资料
	 */
	private function getProducts(){
		global $currencies;
		$data = array();
		$sql = tep_db_query("select p.products_id, p.products_model, p.products_tax_class_id, p.products_price, p.departure_end_city_id, pd.products_name, pd.products_small_description from " . TABLE_PRODUCTS . " p, products_description pd where pd.language_id=1 and p.products_id=pd.products_id and p.products_id in (".$this->p_ids.") ");
		$i = 0;
		while(true && $rows = tep_db_fetch_array($sql)){
			$tax_rate_val_get = tep_get_tax_rate($rows['products_tax_class_id']);
			$new_price = tep_get_products_special_price($rows['products_id']);
			if ($new_price) {	//有特价
				$products_price = $currencies->display_price($rows['products_price'], $tax_rate_val_get);
				$new_price = $currencies->display_price($new_price, $tax_rate_val_get);
				$rows['newPrice'] = $new_price;
				$rows['productsPrice'] = $products_price;		
			} else {	//无特价
				//解决币种非美元的问题
				$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($rows['products_id']);
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$rows['products_price'] = tep_get_tour_price_in_usd($rows['products_price'],$tour_agency_opr_currency);
				}
				$rows['productsPrice'] = $currencies->display_price($rows['products_price'], $tax_rate_val_get);				
			}
			$data[$i] = $rows;
			$i++;
		}
		return $data;
	}
	
}


$content = 'ajax_landingPages';
if($_GET['action']=="process" && tep_not_null($_GET['p_ids'])){
	new ajax_landing_page($_GET);
}
?>