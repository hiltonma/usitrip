<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
require ('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('products_show_order_by');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require 'includes/classes/ProductShowOrderBy.class.php';
if($_POST['ajax']){
	switch($_POST['ajax']){
		case 1 : 
			$city_array=ProductShowOrderBy::getPlace($_POST['city_name'],$_POST['type']);
			echo json_encode($city_array);
			exit;break;
		case 2 :
			echo ProductShowOrderBy::getCategories($_POST['parent_id']);
			exit;break;
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>前台页面产品排序管理</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function checkLi(value,type,doc){
	jQuery("#city_hide_"+type).val(value);
	jQuery("#city_div_"+type).html('');
	jQuery("#city_text_"+type).val(doc.innerHTML);
}
function showCity(doc,type){
	if(doc.value){
		jQuery.post("products_show_order_by.php",{ajax:'1',city_name:doc.value,type:type},function(result){
			var str='';
			for(i=0;i<result.length;i++){
				str+='<li onclick="checkLi('+result[i]['city_id']+','+type+',this)">'+result[i]['city']+'</li>';
			}
			jQuery("#city_div_"+type).html(str);
		  },'json');
	}
}
function getCategory(parent_id){
	if(parent_id)
	jQuery.post("products_show_order_by.php",{ajax:'2',parent_id:parent_id},function(result){
		if(result){
			jQuery('#category_sun').html(result);
		}
	});
}
</script>
<style type="text/css">
#connter {
	width: 960px;
	margin: 0 auto;
}
li{ overflow:hidden; cursor:pointer; height:15px}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

table th,#TableList td {
	border: 1px solid #DCDCDC;
	background: #eee;
	line-height: 25px;
}

#TableList td {
	background: #fff;
	text-align: center;
}
.tr1{
	background: #DCDCDC ;
}
tr{ height:30px}
.contentTable,.contentTable td{ 
pading:5px; border:1px solid #DCDCDC; border-bottom-color:#666; border-right-color:#666;border-left-color:#666; width:300px; border-spacing:0;
}
.ui-helper-hidden-accessible{display:none}
</style>
</head>
<body>
<?php
$categories_id=isset($_GET['categories_id'])?$_GET['categories_id']:'';
$categories_tags=isset($_GET['categories_tags'])?$_GET['categories_tags']:'';
$days=isset($_GET['days'])?$_GET['days']:'';
$start_city_text=isset($_GET['start_city_text'])?$_GET['start_city_text']:'';
$end_city_text=isset($_GET['end_city_text'])?$_GET['end_city_text']:'';
$start_city=isset($_GET['start_city'])?$_GET['start_city']:'';
$end_city=isset($_GET['end_city'])?$_GET['end_city']:'';
$pri_key=isset($_GET['pri_key'])?$_GET['pri_key']:'';
$category_sun=isset($_GET['category_sun'])?$_GET['category_sun']:'';
$class_category=$category_sun?$category_sun:$categories_id;
$order_by=new ProductShowOrderBy($class_category, $start_city, $end_city, $days, $pri_key,$categories_tags);

if($_POST){
	if(isset($_POST['products_default_order_id'])&&$_POST['products_default_order_id']){
		$order_by->changeOne($_POST['id_str'],$_POST['products_default_order_id']);
	}else{
		$order_by->addOne($_POST['id_str']);
	}
}
$info=$order_by->getShowId();
?>

<?php
require (DIR_WS_INCLUDES . 'header.php');
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>
<h1>(产品中心)前台页面产品排序管理</h1>
<br /><br />
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('products_show_order_by');
$list = $listrs->showRemark();
?>
<fieldset style="position:relative">
<legend style="text-align:left">搜索区域</legend>
<form action="" method="get">
	
	产品列表标签：
	<select name="categories_tags">
	<?php echo $order_by->drawOption($order_by->_type_array,$categories_tags)?>
	</select>
	出发城市：
	<input type="text" name="start_city_text" value="<?=$start_city_text?>" onkeyup="showCity(this,1)" id="city_text_1" onchange="if(this.value=='')jQuery('#city_hide_1').val('');"/><div id="city_div_1" style="border: 1px solid #817F82;left: 0;position: absolute;top: 45px;width:136px;left:257px"></div><input type="hidden" name="start_city" value="<?=$start_city?>" id='city_hide_1'/>
	目的城市：
	<input type="text" name="end_city_text" value="<?=$end_city_text?>" onkeyup="showCity(this,2)" id="city_text_2" onchange="if(this.value=='')jQuery('#city_hide_2').val('');"/>
	<div id="city_div_2" style="border: 1px solid #817F82;left: 0;position: absolute;top: 45px;width:136px;left:467px"></div>
	<input type="hidden" name="end_city" value="<?=$end_city?>" id='city_hide_2'/>
	日期：
	<select name="days">
	<?php echo $order_by->drawOption($order_by->_day_array,$days)?>
	</select>
	关键字：
	<input type="text" value="<?=$pri_key?>" name="pri_key" />
	<br /><br />
	大类别:
	<select name="categories_id" onchange="getCategory(this.value)">
	<?php echo $order_by->drawOption($order_by->_categories_array,$categories_id)?>
	</select>
	<span id="category_sun">
	<?php if($categories_id){
		echo $order_by->getCategories($categories_id,$category_sun);
	}?>
	</span>
<input type="submit" value="提交" />
</form>
</fieldset>
<br /><br />
<fieldset>
<legend style="text-align:left">ID显示的值</legend>
<form method="post" action="">
<textarea name="id_str" cols="80" rows="4"><?=$info['products_ids']?></textarea><input type="hidden" name="products_default_order_id" value="<?=$info['products_default_order_id']?>" />
<input type="submit" value="修改" />
</form>
</fieldset>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>