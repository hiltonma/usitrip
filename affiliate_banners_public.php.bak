<?php
/**
 * 销售联盟iframe内容页面处理程序
 */

require('includes/application_top.php');

//联盟主题活动处理 start {
$link_adderss = '';
switch ($_GET['theme_name']){
	case 'googleapple': $link_adderss = HTTP_SERVER.'/web_action/googleapple/index.html'; break;
	case 'familyfun': 	$link_adderss = HTTP_SERVER.'/web_action/familyfun/index.html'; break;
	case 'shopping': 	$link_adderss = HTTP_SERVER.'/web_action/shopping/index.html'; break;
	case '2012yellow_stone': $link_adderss = HTTP_SERVER.'/web_action/2012yellow_stone/index.html'; break;
	case 'yhuts': 		$link_adderss = HTTP_SERVER.'/web_action/yhuts/index.html'; break;
}
if($link_adderss!=''){
	tep_redirect($link_adderss);
	exit;
}
//联盟主题活动处理 end }

//收affiliate标记参数 start {
$affiliate_parameter = '';
$affiliate_parameters = array();
if(tep_not_null($_GET['_ref'])){
	$affiliate_parameters[]= 'ref='.$_GET['_ref'];
}
if(tep_not_null($_GET['_utm_source'])){
	$affiliate_parameters[]= 'utm_source='.$_GET['_utm_source'];
}
if(tep_not_null($_GET['_utm_medium'])){
	$affiliate_parameters[]= 'utm_medium='.$_GET['_utm_medium'];
}
if(tep_not_null($_GET['_utm_term'])){
	$affiliate_parameters[]= 'utm_term='.$_GET['_utm_term'];
}
if(tep_not_null($_GET['_affiliate_banner_id'])){
	$affiliate_parameters[]= 'affiliate_banner_id='.$_GET['_affiliate_banner_id'];
}
if(sizeof($affiliate_parameters)>0 ){
	$affiliate_parameter = implode('&',$affiliate_parameters);
}
//收affiliate标记参数 end }


//模板start{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>销售联盟</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link href="templates/Original/page_css/affiliate_banners.css" rel="stylesheet" type="text/css" />
<?php
//注意：/main_page.tpl.php也用到此代码块 start {
$use_merger = true;
if($use_merger==true){// 合并后的JS文件
?>
<script type="text/javascript" src="jquery-1.3.2/merger/merger.min.js"></script>
<?php
}else{// 合并前的JS文件
?>
<script type="text/javascript" src="jquery-1.3.2/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jquery-1.3.2/jquery.cookie.min.js"></script>
<script type="text/javascript" src="jquery-1.3.2/jquery.autocomplete.min.js"></script>
<?php
}
?>
<script type="text/javascript">
var serverDateTime = '<?php echo date('Y-m-d H:i:s');?>';
<?php /* 修正IE下选择下拉列表的Bugs */?>
jQuery().ready(function() {
	jQuery("select").change(function(){ document.body.focus(); });
});
<?php /* 修正IE下背景不缓存的问题 */?>
try {
  document.execCommand("BackgroundImageCache", false, true);
} catch(e) {}

</script>

<?php
//注意：/main_page.tpl.php也用到此代码块 end }
?>

<script type="text/javascript">
function S_Check_Onfocus(obj){
	if(obj.value=="<?php echo SEARCH_BOX_TIPS1 ?>"){
		obj.value="";
		/* obj.className='input_search2'; */
	}
}
function S_Check_Onblur(obj){
	if(obj.value=="" || obj.value=="<?php echo SEARCH_BOX_TIPS1 ?>"){
		obj.value="<?php echo SEARCH_BOX_TIPS1 ?>";
	}
}
/* /以下是通用型的 */
function Check_Onfocus(obj){
	if(obj.value==obj.title){
		obj.value="";
		/* obj.className='input_search2'; */
		obj.style.color = "#353535";
	}
}
function Check_Onblur(obj){
	if(obj.value==""){
		obj.value=obj.title;
		obj.style.color = "#BBBBBB";
	}
}
</script>
</head>
<body>
<?php
switch($_GET['iframe_action']){
	case 'products':
	ob_start();
	$product_info_query = tep_db_query("SELECT
			 p.products_id, pd.products_name, p.is_hotel, p.products_tax_class_id,
			 p.products_is_regular_tour, p.products_model, p.products_image, p.products_image_med,p.products_price,p.products_durations,p.products_durations_type
			 			 
			 FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
			 WHERE p.products_status = '1'
			 AND p.products_id = '" .  (int) $_GET['_products_id'] . "'
			 AND pd.products_id = p.products_id
			 AND pd.language_id = '" . (int) $languages_id . "'");
	$product_info = tep_db_fetch_array($product_info_query);
	if((int)$product_info['products_id']){
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($product_info['products_id']);
		if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
			$product_info['products_price'] = tep_get_tour_price_in_usd($product_info['products_price'], $tour_agency_opr_currency);
		}
		$qi_string = '';
		if ($product_info['products_durations'] > 1 && !(int) $product_info['products_durations_type']) {
			$qi_string = '起';
		}
		
		$tax_rate_val_get = tep_get_tax_rate($product_info['products_tax_class_id']);
		if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
			//如果产品是特价团则需要把原价保存到新变量以备后面所用（紧记在数据中添加变量时使用驼峰法命名）
			$product_info['oldProductsPrice'] = $product_info['products_price'];
			$currencies->display_price(($product_info['products_price'] - $new_price), $tax_rate_val_get);
			if($product_info['is_hotel'] == '1'){//<p><del>%s</del></p> <p><b>双人间%s/晚</b></p>
			$products_price = sprintf('<p><del>%s</del></p> <p><b>%s</b></p>',$currencies->display_price($product_info['products_price'], $tax_rate_val_get),$currencies->display_price($new_price, $tax_rate_val_get) . $qi_string);
			}else{
				$products_price = sprintf('<p><del>%s</del></p> <p><b>%s</b></p>',$currencies->display_price($product_info['products_price'], $tax_rate_val_get),$currencies->display_price($new_price, $tax_rate_val_get) . $qi_string);
			}
		}else{
			if($product_info['is_hotel'] == '1'){ //<p><b>双人间 %s/晚</b></p>
				$products_price = sprintf("<p><b> %s</b></p>",$currencies->display_price($product_info['products_price'], $tax_rate_val_get) . $qi_string);
			}else{
				$products_price = sprintf("<p><b> %s </b></p>",$currencies->display_price($product_info['products_price'], $tax_rate_val_get) . $qi_string);
			}
		}
		
		
		$data = $product_info;
		$data['href'] = tep_href_link('product_info.php','products_id='.$product_info['products_id'].'&'.$affiliate_parameter);
		$data['name'] = $product_info['products_name'];
		$data['price'] = $products_price;
		$data['src'] = ((stripos( $product_info['products_image'],'http://')===false) ? 'images/':''). $product_info['products_image'];
		
	}
?>
<!--产品start{-->
<div id="products">
	<?php if((int)$data['products_id']){?>
	<div class="get_code_view_panel">
		<a href="<?= $data['href'];?>" target="_blank"><img width="151" border="0" src="<?= $data['src'];?>"></a>
		<p><a href="<?= $data['href'];?>" target="_blank"><?= $data['name'];?></a></p>
		<p>旅游团号：<?= $data['products_model'];?></p>
		<?= $data['price'];?>
	</div>
	<?php }?>
</div>
<!--产品end}-->
<?php 
	echo db_to_html(ob_get_clean());
	break;
	case 'searchB':
	//此处不要用ob_start
?>
<!--边侧搜索框start{-->
<div id="searchB">
<?php
$for_affiliate = true;
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'search.php');
?>
</div>
<!--边侧搜索框end}-->
<?php 
	break;
	case 'searchT': //通栏start {
	ob_start();
?>
<!--通栏搜索框start{-->
<form action="<?= tep_href_link('advanced_search_result.php',$affiliate_parameter);?>" method="get" enctype="application/x-www-form-urlencoded" target="_blank">
<div id="searchT" class="get_code_view_panel">
	<?php if($_GET['_search_logo']==='1'){	//显示走四方Logo?>
	<div class="logo">
		<a href="<?= HTTP_SERVER.'/?'.$affiliate_parameter;?>" target="_blank"><img src="/image/affiliate/site_logo.png" width="167" height="74" alt="走四方网" border="none" /></a>
	</div>
	<?php }?>

	<div class="search">
		<div class="searchbox">
			<?= tep_draw_input_field('w', '', ' placeholder="请输入出发城市或想去的景点" class="searchipt" ');?>
			<input type="submit" value="搜索" class="searchbtn">
		</div>
		<?php if($_GET['_search_keywords']==='1'){	//显示热门关键词?>
		<dl class="keywords">
			<dt>热门推荐：</dt>
			<dd><a target="_blank" href="<?= tep_href_link('advanced_search_result.php','w=纽约&'.$affiliate_parameter);?>">纽约</a></dd>
			<dd><a target="_blank" href="<?= tep_href_link('advanced_search_result.php','w=洛杉矶&'.$affiliate_parameter);?>">洛杉矶</a></dd>
			<dd><a target="_blank" href="<?= tep_href_link('advanced_search_result.php','w=旧金山&'.$affiliate_parameter);?>">旧金山</a></dd>
			<dd><a target="_blank" href="<?= tep_href_link('advanced_search_result.php','w=檀香山&'.$affiliate_parameter);?>">檀香山</a></dd>
		</dl>
		<?php }?>
	</div>
</div>
</form>
<!--通栏搜索框end}-->
<?php
	 echo db_to_html(ob_get_clean());
	 //通栏end }	
	break;
	
}
?>
<?php if(strtolower(CHARSET)=='gb2312'){?>
<script type="text/javascript" src="big5_gb-min.js"></script>
<?php }else{?>
<script type="text/javascript" src="gb_big5-min.js"></script>
<?php }
?>
</body>
</html>
<?php
//模板end}
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>