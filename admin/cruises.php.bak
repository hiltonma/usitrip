<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('cruises');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

require(DIR_FS_DOCUMENT_ROOT.'includes/functions/cruises_functions.php');
//引入FCK编辑器
//include(DIR_FS_ADMIN.'includes/modules/ckfinder.php');
/*定义图片文件路径*/
$imageDir = DIR_FS_DOCUMENT_ROOT.'images/cruises/';
$http_url = HTTP_SERVER.'/images/cruises/';
//每房间最大入住人数上限(默认值)
$maxPerOfGuest = 4;
//每房间最大入住人数下限(默认值)
$minNumGuest = 1;

$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$error = false;
switch($action){
	case "syncCabinDeckName"://从产品选项中同步客舱、甲板名称
	$cabinSql = tep_db_query('SELECT DISTINCT products_options_id FROM `cruises_cabin` ');
	while ($rows=tep_db_fetch_array($cabinSql)) {
		$cname = tep_get_product_option_name_from_optionid((int)$rows['products_options_id']);
		tep_db_query('UPDATE `cruises_cabin` SET cruises_cabin_name="'.$cname.'" WHERE products_options_id="'.(int)$rows['products_options_id'].'" ');
	}
	
	$deckSql = tep_db_query('SELECT DISTINCT products_options_values_id FROM `cruises_cabin_deck` ');
	while ($rows=tep_db_fetch_array($deckSql)) {
		$aSql = tep_db_query('SELECT products_options_values_name FROM `products_options_values` WHERE products_options_values_id="'.(int)$rows['products_options_values_id'].'" ');
		while ($aRows=tep_db_fetch_array($aSql)) {
			tep_db_query('UPDATE `cruises_cabin_deck` SET cruises_cabin_deck_name="'.$aRows['products_options_values_name'].'"WHERE products_options_values_id="'.(int)$rows['products_options_values_id'].'" ');
		}
	}
	$js_str = '[JS]';
	$js_str.= 'alert("Updated done.");';
	$js_str.= '[/JS]';
	echo $js_str;
	exit;
	
	break;
	case "getTaxText":	//取得税项并写到taxTextInput {
	$tax = getCruisesPerPersonTaxT((int)$_GET['products_options_id']);
	$js_str = '[JS]';
	$js_str.= 'document.getElementById("taxTextInput").value="$'.number_format($tax, 2, '.', '').'";';
	$js_str.= '[/JS]';
	echo $js_str;
	exit;
	break; //}
	case "uploadImage":	//上传压缩图片
	/*
	* 文件直接上传到/images/cruises
	*/
	$tmp_microtime = time();
	$new_name = 'detail_'.mt_rand(0,9).'_'.$tmp_microtime;

	$headers = getallheaders();
	$exc_name = preg_replace('/^.*\./','.',$headers['Image-Name']);
	/*上传后文件名称*/
	$new_name .= strtolower($exc_name);

	$image_name = $imageDir.$new_name;
	$file = fopen($image_name, 'wb');
	if(fwrite($file, $GLOBALS['HTTP_RAW_POST_DATA'])=== FALSE){
		echo "0";
		exit();
	}

	//生成两种大小的缩略图
	imageCompression($image_name,150, str_replace('detail_','150_thumb_',$image_name));
	imageCompression($image_name,720, str_replace('detail_','720_thumb_',$image_name));

	echo "1"."|".$new_name."|".$imageDir."|".$image_name."|".$http_url.$new_name."|".$http_url.str_replace('detail_','150_thumb_',$new_name)."|".$http_url.str_replace('detail_','720_thumb_',$new_name);
	//状态码  |       文件名|  目录名     |全名（目录+文件）|图片网址             |图片缩略图网址150 | 图片缩略图网址720
	fclose($file);
	exit;
	break;
	case "getChildDecks":
		/**
	 * 取得客舱选项下的所有子选项（甲板）
	 */		
		if((int)$_GET['products_options_id']){
			$js_str = '[JS]';
			$filter_products_options_value_id = '';
			$sql = tep_db_query('SELECT products_options_values_id FROM `cruises_cabin_deck` WHERE cruises_id="'.(int)$cruises_id.'" ');
			while($rows = tep_db_fetch_array($sql)){
				$filter_products_options_value_id[] = $rows['products_options_values_id'];
			}
			
			$_where = '';
			if(is_array($filter_products_options_value_id)){
				$_where .= ' AND pv.products_options_values_id NOT IN('.implode(',' , $filter_products_options_value_id).') ';
			}
			$sql_str = 'SELECT pv.products_options_values_id, pv.products_options_values_name  FROM `products_options_values` pv, `products_options_values_to_products_options` povtpo WHERE pv.products_options_values_id=povtpo.products_options_values_id AND povtpo.products_options_id ="'.(int)$_GET['products_options_id'].'" AND pv.products_options_values_name!="NULL" '.$_where.' ORDER BY pv.`products_options_values_id` ASC ';
			$sql = tep_db_query($sql_str);
			//echo $sql_str;exit;
			$options = array();
			while($rows = tep_db_fetch_array($sql)){
				$options[] = array('id'=>$rows['products_options_values_id'], 'text'=>$rows['products_options_values_name']);
			}
			$select_menu = tep_draw_pull_down_menu('_products_options_values_id',$options);
			$select_menu = preg_replace('/[[:space:]]+/',' ',addslashes($select_menu));
			$js_str .= 'document.getElementById("DecksPullDownMenu").innerHTML = "<span>甲板名称：</span>'.$select_menu.'"; ';
			$js_str .= '[/JS]';
		}
		echo $js_str;
		exit;
		break;
	case "addCabinConfirm":
		/**
	 * 添加客舱
	 */
		if((int)$_GET['cruises_id'] && (int)$_GET['products_options_id']){
			$data = ajax_to_general_string($_GET);
			$data['cruises_cabin_name'] = tep_get_products_options_name((int)$_GET['products_options_id']);
			tep_db_fast_insert('cruises_cabin',$data);
			$js_str = '[JS]document.location="'.tep_href_link('cruises.php','action=edit&cruises_id='.(int)$_GET['cruises_id']).'";[/JS]';
			echo $js_str;
		}
		exit;
		break;
	case 'addDeckConfirm':
		/**
	 * 添加甲板
	 */
		if((int)$_GET['products_options_values_id'] && (int)$_GET['cruises_id']){
			$data = ajax_to_general_string($_GET);
			$data['cruises_cabin_deck_name'] = tep_get_products_options_value_name($data['products_options_values_id']);
			tep_db_fast_insert('cruises_cabin_deck',$data);
			$js_str = '[JS]document.location="'.tep_href_link('cruises.php','action=edit&cruises_id='.(int)$_GET['cruises_id']).'";[/JS]';
			echo $js_str;
		}
		exit;
		break;
	case "addConfirm":
		/**
	 * 添加邮轮资料
	 */
		if(tep_not_null($_POST['cruises_name'])){
			$cruises_name = strip_tags(tep_db_prepare_input(ajax_to_general_string($_POST['cruises_name'])));
			if((int)CheckCruisesName($cruises_name)){
				$js_str = '[JS]alert("数据库已经存在您输入的邮轮名称！");[/JS]';
				echo db_to_html($js_str);
				die();
			}

			$data = ajax_to_general_string($_POST);
			$data['add_date']=$data['up_date']=date("Y-m-d H:i:s");
			$data = html_to_db ($data);
			$cruises_id = tep_db_fast_insert('`cruises`', $data);
			tep_db_query('OPTIMIZE TABLE cruises');
			$js_str = '[JS]document.location="'.tep_href_link('cruises.php','action=edit&cruises_id='.$cruises_id).'";[/JS]';
			echo $js_str;
		}
		exit;
		break;
	case "edit":
		/**
	 * 编辑邮轮团页面
	 */
		if((int)$_GET['cruises_id']){
			$sql=tep_db_query('SELECT * FROM `cruises` c WHERE c.cruises_id ="'.(int)$_GET['cruises_id'].'"');
			$rows=tep_db_fetch_array($sql);
			foreach ($rows as $key => $val){
				$$key = tep_db_prepare_input($val);
			}
			//取得外观图片
			$images['cruises'] = getCruisesImages($cruises_id, 'cruises',(int)$_GET['cruises_id']);
			//取得已上传的图片信息
			if(!file_exists($imageDir)){
				die("The file $imageDir does not exist");
			}
			$loadedImages = '';


			//取得客舱和甲板资料
			$csql=tep_db_query('SELECT * FROM `cruises_cabin` WHERE cruises_id="'.(int)$cruises_id.'" ORDER BY sort_id ASC, products_options_id DESC');
			$cabins = array();
			$loop=0;
			while($crows = tep_db_fetch_array($csql)){
				$cabins[$loop] = tep_db_prepare_input($crows);
				//图片
				$cabins[$loop]['images'] = getCruisesImages($crows['products_options_id'], 'cabin', (int)$_GET['cruises_id']);
				//print_r($cabins[$loop]);
				//甲板
				$dsql = tep_db_query('SELECT * FROM `cruises_cabin_deck` WHERE products_options_id="'.$crows['products_options_id'].'" and cruises_id="'.(int)$cruises_id.'" ORDER BY sort_id ASC, products_options_values_id DESC ');
				$lp1 = 0;
				while($drows=tep_db_fetch_array($dsql)){
					$cabins[$loop]['decks'][$lp1] = tep_db_prepare_input($drows);
					$cabins[$loop]['decks'][$lp1]['images'] = getCruisesImages($drows['products_options_values_id'], 'deck',(int)$_GET['cruises_id']);
					$lp1++;
				}
				$loop++;
			}
			//取得邮轮介绍属性资料
			$ASql = tep_db_query('SELECT * FROM `cruises_content_attribute` WHERE 1');
			$AOptions = array();
			while($ARows=tep_db_fetch_array($ASql)){
				$AOptions[]=array('id'=>$ARows['attribute_id'], 'text'=>$ARows['attribute_name']);
			}
			$aSql = tep_db_query('SELECT * FROM `cruises_content_attribute_value` WHERE cruises_id="'.(int)$cruises_id.'" ORDER BY sort_id ASC, attribute_id DESC');
			$attributes = false;
			$loop=0;
			while ($aRows = tep_db_fetch_array($aSql)) {
				$attributes[$loop]=$aRows;
				$loop++;
			}

		}

		break;
	case "editConfirm":
		//快速更新邮轮
		$_POST['up_date'] = date("Y-m-d H:i:s");
		$allowFields = '*';
		$updateNum = tep_db_fast_update('cruises', ' cruises_id='.(int)$_POST['cruises_id'],(array)$_POST,$allowFields);
		//快速更新客舱
		if(is_array($_POST['cabins'])){
			foreach ($_POST['cabins'] as $key => $val){
				tep_db_fast_update('cruises_cabin', 'cruises_id='.(int)$_POST['cruises_id'].' and products_options_id='.(int)$val['products_options_id'],(array)$val,'*');
				//快速更新图片集（客舱）
				tep_db_query('DELETE FROM `cruises_images` WHERE cruises_id='.(int)$_POST['cruises_id'].' and `images_type` = "cabin" and images_link_id ="'.(int)$val['products_options_id'].'" ');
				if(tep_not_null($val['images_url_thumb_min'])){
					$_val['images_url'] = str_replace('150_thumb','detail',$val['images_url_thumb_min']);
					$_val['images_url_thumb'] = str_replace('150_thumb','720_thumb',$val['images_url_thumb_min']);
					$_val['images_url_thumb_min'] = $val['images_url_thumb_min'];
					$_val['images_type'] ='cabin';
					$_val['images_link_id'] =(int)$val['products_options_id'];
					$_val['images_title'] = $val['cruises_cabin_name'];
					$_val['images_content'] = $val['cruises_cabin_content'];
					$_val['cruises_id'] = (int)$_POST['cruises_id'];
					tep_db_fast_insert('cruises_images', $_val);
				}

				//快速更新甲板
				if(is_array($val['decks'])){
					foreach ($val['decks'] as $key1 => $val1){
						tep_db_fast_update('cruises_cabin_deck', 'cruises_id='.(int)$_POST['cruises_id'].' and products_options_values_id='.(int)$val1['products_options_values_id'],(array)$val1,'*');
						//快速更新图片集（甲板）
						tep_db_query('DELETE FROM `cruises_images` WHERE cruises_id='.(int)$_POST['cruises_id'].' and `images_type` = "deck" and images_link_id ="'.(int)$val1['products_options_values_id'].'" ');
						if(tep_not_null($val['images_url_thumb_min'])){
							$_val1['images_url'] = str_replace('150_thumb','detail',$val1['images_url_thumb_min']);
							$_val1['images_url_thumb'] = str_replace('150_thumb','720_thumb',$val1['images_url_thumb_min']);
							$_val1['images_url_thumb_min'] = $val1['images_url_thumb_min'];
							$_val1['images_type'] ='deck';
							$_val1['images_link_id'] =(int)$val1['products_options_values_id'];
							$_val1['images_title'] = $val1['cruises_cabin_deck_name'];
							$_val1['images_content'] = $val1['cruises_cabin_deck_content'];
							$_val1['cruises_id'] = (int)$_POST['cruises_id'];
							
							tep_db_fast_insert('cruises_images', $_val1);
						}
					}
				}
			}
		}
		//快速更新介绍属性
		//print_vars($_POST);
		//exit;
		tep_db_query('DELETE FROM `cruises_content_attribute_value` WHERE `cruises_id` = "'.(int)$_POST['cruises_id'].'" ');
		if(is_array($_POST['attributes'])){
			foreach ($_POST['attributes'] as $key => $val){
				$_POST['attributes'][$key]['cruises_id'] = (int)$_POST['cruises_id'];
				tep_db_fast_insert('cruises_content_attribute_value', $_POST['attributes'][$key]);
			}
		}
		//快速更新图片集
		tep_db_query('DELETE FROM `cruises_images` WHERE cruises_id="'.(int)$_POST['cruises_id'].'" and `images_type` = "cruises" and images_link_id ="'.(int)$_POST['cruises_id'].'" ');
		if(is_array($_POST['images']['cruises'])){
			foreach($_POST['images']['cruises'] as $key => $val){
				$_POST['images']['cruises'][$key]['images_url'] = str_replace('150_thumb','detail',$_POST['images']['cruises'][$key]['images_url_thumb_min']);
				$_POST['images']['cruises'][$key]['images_url_thumb'] = str_replace('150_thumb','720_thumb',$_POST['images']['cruises'][$key]['images_url_thumb_min']);
				$_POST['images']['cruises'][$key]['images_type'] ='cruises';
				$_POST['images']['cruises'][$key]['images_link_id'] =(int)$_POST['cruises_id'];
				$_POST['images']['cruises'][$key]['cruises_id'] =(int)$_POST['cruises_id'];

				tep_db_fast_insert('cruises_images', $_POST['images']['cruises'][$key]);
			}
		}

		$messageStack->add_session('Updated Successfully.', 'success');
		tep_redirect(tep_href_link('cruises.php','action=edit&cruises_id='.(int)$_POST['cruises_id']));
		//tep_href_link('cruises.php','action=edit&cruises_id='.$cruises_id)
		exit;
		break;
	case "delCabinConfirm":
		//删除客舱和子甲板
		$js_str = '[JS]';
		if((int)$_GET['products_options_id']){
			tep_db_query('DELETE FROM `cruises_cabin_deck` WHERE `products_options_id` = "'.(int)$_GET['products_options_id'].'" and cruises_id ="'.(int)$_GET['cruises_id'].'" ');
			tep_db_query('DELETE FROM `cruises_cabin` WHERE `products_options_id` = "'.(int)$_GET['products_options_id'].'" and cruises_id ="'.(int)$_GET['cruises_id'].'" ');
			$js_str .= 'jQuery("#Cabin_'.(int)$_GET['products_options_id'].'").fadeOut(500,function(){
   							jQuery("#Cabin_'.(int)$_GET['products_options_id'].'").remove();
 						});';

		}
		$js_str .= '[/JS]';
		echo preg_replace('/[[:space:]]+/', ' ',$js_str);
		exit;
		break;
	case "delDecksConfirm":
		//删除甲板
		$js_str = '[JS]';
		if((int)$_GET['products_options_values_id']){
			tep_db_query('DELETE FROM `cruises_cabin_deck` WHERE cruises_id="'.(int)$_GET['cruises_id'].'" and  `products_options_values_id` = "'.(int)$_GET['products_options_values_id'].'" ');
			$js_str .= 'jQuery("#Decks_'.(int)$_GET['products_options_values_id'].'").fadeOut(500,function(){
   							jQuery("#Decks_'.(int)$_GET['products_options_values_id'].'").remove();
 						});';

		}
		$js_str .= '[/JS]';
		echo preg_replace('/[[:space:]]+/', ' ',$js_str);
		exit;
		break;

}

//数据查询 {
$table = ' cruises c ';
$where = ' 1 ';
$order_by = ' c.cruises_id DESC ';
if(tep_not_null($_GET['cruises_name'])){
	$where .= ' AND c.cruises_name Like "%'.tep_db_input(tep_db_prepare_input($_GET['cruises_name'])).'%" ';
}
if((int)$_GET['agency_id']){
	$where .= ' AND c.agency_id = "'.(int)$_GET['agency_id'].'" ';
}

$sql_str = 'SELECT * FROM '.$table.' WHERE '.$where.' ORDER BY '.$order_by;
$cruises_query_numrows = 0;
$cruises_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $cruises_query_numrows);

$cruises_query = tep_db_query($sql_str);


//数据查询 }

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
CHECKBOX, INPUT, RADIO, SELECT, TEXTAREA, FILE {
    font-size: 14px;
    margin: 3px;
}
</style>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
<?php if(strtolower(CHARSET)=="big5"){?>
var onblur0 = 'obj.value = traditionalized(obj.value); ';
<?php }else{?>
var onblur0 = 'obj.value = simplized(obj.value); ';
<?php }?>

/*同步客舱、甲板最新名称*/
function syncCabinDeckName(){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=syncCabinDeckName')) ?>");
	ajax_get_submit(url);
}

function spanOnClick(obj){
	if(obj.tagName.toLowerCase()=="span"){
		jQuery(obj).replaceWith('<input id="'+obj.id+'" name="'+ obj.id +'" onblur="inputOnBlur(this)" onkeydown="Kdown(this)" type="text" value="'+ jQuery(obj).text() +'" />');
	}
}

/* 提交输入的数据 */
function inputOnBlur(obj){
	if(obj.tagName.toLowerCase()=="input"){
		eval(onblur0);
		val = obj.value.replace(/[\"\<\>]+/g,'');
		if(val.length<2){
			alert("输入框文字至少要2个字！");
			return false;
		}
		if(jQuery("#old_"+obj.id).val() != val){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=updateConfirm')) ?>");
			url += '&'+obj.id+'='+encodeURIComponent(val);
			url += '&ajax=true';
			ajax_get_submit(url);
		}else{
			inputChangeToSpan(obj);
		}
	}
}

function inputChangeToSpan(obj){
	if(obj.tagName.toLowerCase()=="input"){
		val = obj.value.replace(/[\"\<\>]+/g,'');
		jQuery(obj).replaceWith('<span id="'+obj.id+'" onclick="spanOnClick(this)">'+ val +'</span>');
	}
}

/* 按回车键时提交数据 */
function Kdown(obj){
	obj.onkeydown = function(e){
		var e = window.event || e;
		if(e.keyCode==13){ inputOnBlur(obj); }
	}
}

function keyEdit(keyId){
	spanOnClick(document.getElementById('key_words0_'+keyId));
	spanOnClick(document.getElementById('key_words1_'+keyId));
}

function keyDelete(keyId){
	if(confirm("您确定要删除这个邮轮的所有资料？删除后不可再恢复！")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=deleteConfirm')) ?>");
		url += '&key_id='+keyId;
		ajax_get_submit(url);
	}
}

function keyAdd(){
	var form_id = "form_search";
	var form = document.getElementById(form_id);
	if(form.elements['cruises_name'].value==""){
		alert("请输入关键词后再点击“增加”按钮！");
		return false;
	}

	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=addConfirm')) ?>");
	ajax_post_submit(url,form_id);
}

/* 快捷键搜索或增加记录 */
jQuery(document).ready(function(){
	jQuery("#form_search").submit(function(){
		formSearchSubmit(this);
	});
});

function formSearchSubmit(obj){
	obj.onkeydown = function(e){
		var e = window.event || e;
		//alert(e.keyCode);
		if(e.ctrlKey && e.keyCode==13){
			keyAdd();
		}else if(e.keyCode==13){ obj.submit();}

	}
}

</script>
<script type="text/javascript">
/*添加、删除客舱和甲板的动作*/
function showPanl(n){
	if(n==1){
		jQuery('#addCabin').show();
		jQuery('#addDeck').hide();
	}
	if(n==2){
		jQuery('#addCabin').hide();
		jQuery('#addDeck').show();
	}
}

function addCabin(cruises_id){
	var _ProductsOptionsId = document.getElementById('formC').elements['_products_options_id'].value;
	if(_ProductsOptionsId<1){
		alert('客舱名称不能为空！');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=addCabinConfirm')) ?>");
	url += '&cruises_id='+encodeURIComponent(cruises_id);
	url += '&products_options_id='+encodeURIComponent(_ProductsOptionsId);
	url += '&ajax=true';
	ajax_get_submit(url);
}
function addDeck(){
	var _productsOptionsValuesId = document.getElementById('formC').elements['_products_options_values_id'].value;
	if(_productsOptionsValuesId<1){
		alert('甲板名称不能为空！');
		return false;
	}
	var _cruisesCabin = document.getElementById('formC').elements['_products_options_id_1'].value;
	if(_cruisesCabin<1){
		alert('必须先选择客舱！如果没有客舱则应先新创建客舱。');
		return false;

	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=addDeckConfirm')) ?>");
	url += '&cruises_id='+encodeURIComponent(<?= $cruises_id;?>);
	url += '&products_options_id='+encodeURIComponent(_cruisesCabin);
	url += '&products_options_values_id='+encodeURIComponent(_productsOptionsValuesId);
	url += '&ajax=true';
	ajax_get_submit(url);
}

function delCabin(products_options_id, cruises_id){
	if(products_options_id>0){
		if(confirm("你真的要删除这个客舱？一旦删除就无法恢复……")){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=delCabinConfirm')) ?>");
			url += '&products_options_id='+encodeURIComponent(products_options_id)+'&cruises_id='+cruises_id;
			ajax_get_submit(url);
		}
	}
}

function delDecks(products_options_values_id, cruises_id){
	if(products_options_values_id>0){
		if(confirm("你真的要删除这个甲板？一旦删除就无法恢复……")){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=delDecksConfirm')) ?>");
			url += '&products_options_values_id='+encodeURIComponent(products_options_values_id)+'&cruises_id='+cruises_id;
			ajax_get_submit(url);
		}
	}
}


/*选择图片库图片*/
var imageInput = null;
function openImagesLayer(InputObj){
	imageInput = InputObj;
	//alert(imageInput.value);
	showPopup("imageslayr_0","imageslayr_popupCon_0",100);
}

function selImagesToInput(obj){

	if(imageInput!=null){
		imageInput.value = obj.innerHTML;
		var imgObjId = jQuery(imageInput).attr('name') + '_src';
		document.getElementById('imageslayr_0').style.display='none';
		jQuery("#"+imgObjId).attr('src',obj.innerHTML);
		document.getElementById(imgObjId).src = obj.innerHTML;
		//alert(document.getElementById(imgObjId));
	}
}
</script>
<?php //压缩上传功能{?>
<script src="includes/javascript/zip_upload/swfobject.js" type="text/javascript"></script>
<script type="text/javascript">
var eAddPhoto;
var eAppendPhoto;
var jPhotoList;
var jProcess;
var allSize = 0;
var doneCount = 0;
var infos = [];	//初始化上传图片数组
var isInited = false;
var fileMaxNum = 100;	//每次最多能上传几张？
var phpUrl = "cruises.php?action=uploadImage";	//接收图片的网址
var imagesWidth = 1024; //输出的图片最大宽度
var imagesHeight = 768; //输出的图片最大高度
var imagesQuality = 80; //图片质量
var PhotoUploaderUrl = "<?= HTTP_SERVER?>/admin/includes/javascript/zip_upload/PhotoUploader.swf"; //图片上传处理的flash文件地址
var expressInstallUrl = "<?= HTTP_SERVER?>/admin/includes/javascript/zip_upload/expressInstall.swf"; //图片上传处理的flash扩展文件地址
var inputBoxId = "";	//保存返回结果的文本框ID

function showFlash() {
	jPhotoList = jQuery("#ulPhotoList");

	jSelect = jQuery("#divSelect");
	jProcess = jQuery("#divProcess");
	jDone = jQuery("#divDone");

	var vars = {
		serverUrl: phpUrl,
		jsUpdateInfo: "jsUpdateInfo",
		imgWidth: imagesWidth,
		imgHeight: imagesHeight,
		imgQuality: imagesQuality
	}
	var vars1 = vars;
	vars1.flashID = "divAddPhoto";
	vars1.labelColor = "#000000";
	vars1.labelText = "选择上传文件";
	vars1.hasUnderLine = false;
	swfobject.embedSWF(PhotoUploaderUrl, "divAddPhoto", "80", "25", "10.0.0", expressInstallUrl, vars1, { wmode: "Transparent" });

}
function jsUpdateInfo(flashID, name, status, size, message) {
	//alert(status+':'+message);
	var index = (status == "selected" ? infos.length : getIndexByName(name));
	if (status == "selected") {
		//infos=[];
		if (infos.length == 0) {

			allSize = 0;
			//doneCount = 0;
			jPhotoList.children().remove();
			//showPopup('popupUpload_Load', 'popupConUpload');
			//jDone.hide();
			jSelect.show();
			jQuery("#divAppendPhoto").height(20);
		}
		if (getIndexByName(name) >= 0) {
			alert(name + "已存在!");
			return;
		}

		if(infos.length >= fileMaxNum){
			alert("一次只能上传"+fileMaxNum+"张!");
			return;
		}else{

			//alert(jQuery("#ulPhotoList").html());
			infos.push({
				name: name,
				flashID: flashID,
				title: name.substr(0, name.lastIndexOf(".")),
				status: status
			});

			jPhotoList = jQuery("#ulPhotoList");

			jPhotoList.append('<li><div class="name">' + name + '</div><div class="size">' + formatSize(size) + '</div><div class="status ' + status + '"></div><div class="process" style="width:0%;"></div></li>');

			jQuery(".selected",jQuery("li:nth-child(" + infos.length + ")",jPhotoList)).click(function() {
				swfobject.getObjectById(flashID).Remove(name);
			});

			allSize += size;
		}
	} else {
		var jPhoto = jQuery("li:nth-child(" + (index + 1) + ")", jPhotoList);
		var jSize = jQuery("div.size", jPhoto);
		var jStatus = jQuery(".status", jPhoto);
		var jProgress = jQuery(".process", jPhoto);
		infos[index].status = status;

		if (status == "void") {
			jPhoto.remove();
			allSize -= size;
			var temp = [];
			for (i = 0; i < infos.length; i++) {
				if (infos[i].name != name) {
					temp.push(infos[i]);
				}
			}
			infos = temp;
			updateSummary();
		} else {
			jStatus.removeClass();
			jStatus.addClass("status " + status);

			switch (status) {
				case "loading":
				jProgress.width(message);
				break;
				case "loaded":
				jSize.text(formatSize(size));
				break;
				case "notLoad":
				alert(message);
				break;
				case "uploaded":

				if(message=="0"){
					alert('上传失败，请重新上传!');
					infos=[];
				}else{
					jProgress.width("100%");
					++doneCount;
					updateProgress();
					upload();
					var tmp_array = message.split('|');
					if(tmp_array[0]=="1"){
						tmp_array[1];	//返回的 文件名
						tmp_array[2];	//返回的 目录名
						tmp_array[3];	//返回的 全名（目录+文件）
						tmp_array[4];	//返回的 图片网址
						tmp_array[5];	//返回的 图片缩略图网址150
						tmp_array[6];	//返回的 图片缩略图网址720
					}
					if(inputBoxId!=""){
						var tmp_var = jQuery("#"+inputBoxId).val()+message+";";
						jQuery("#"+inputBoxId).val(tmp_var);
						jQuery("#"+inputBoxId).show();
					}
					/* 此处针对本文件特殊设置，复制到其它文件时可免{*/
					jQuery("#imagesList tr:first").after('<tr><td class="dataTableContent"><a target="_blank" href="'+tmp_array[4]+'"><img src="'+tmp_array[5]+'" width="100" /></a></td>	<td class="dataTableContent"><a href="javascript:void(0)" onClick="selImagesToInput(this);" >'+tmp_array[5]+'</a></td></tr>');

					/* 此处针对本文件特殊设置，复制到其它文件时可免}*/
					//alert(message);

				}
				break;
				case "notUpload":


				jProgress.width("100%");
				++doneCount;
				updateProgress();
				alert(message);
				upload();
				break;


			}
		}
	}
}

function formatSize(size) {
	if (size > 1024 * 1024) {
		return Math.round(size * 100 / (1024 * 1024)) / 100 + "MB";
	} else {
		return Math.floor(size / 1024) + "KB";
	}
}
function updateProgress() {
	var allPersent = Math.floor(doneCount * 100 / infos.length) + "%";
	jQuery("div:nth-child(1) div:only-child", jProcess).width(allPersent);
	jQuery("span:nth-child(2)", jProcess).text(allPersent);
}
function updateSummary() {
	jQuery("#txtPhotoCount").text(infos.length);
	jQuery("#txtAllSize").text(formatSize(allSize));
}
function getIndexByName(name) {
	for (var i = 0; i < infos.length; i++) {
		if (infos[i].name == name) {
			return i;
		}
	}
	return -1;
}

function upload() {
	if (infos.length == 0) {
		alert("请先选择照片");
		return;
	}

	//
	/*点击上传时将*/
	/*jQuery("#need_up_img_id").html('<img src="images/ajax-loading.gif"  style="padding:50px" />');*/
	var index;
	for (var i = 0; i < infos.length; i++) {
		var info = infos[i];
		if (info.status == "selected") {
			index = i;
			break;
		}
	}
	//
	if (doneCount == 0) {
		jQuery("#divAppendPhoto").height(0);
		updateProgress();
		jSelect.hide();
		jProcess.show();
	}
	jQuery(".selected", jQuery("li:nth-child(" + (infos+1) + ")", jPhotoList)).unbind("click");
	if(typeof(infos[index])=="undefined"){
		//alert("请先选择上传文件才能上传！");
		return false;
	}
	swfobject.getObjectById(infos[index].flashID).Load(infos[index].name);
}

</script>

<?php //压缩上传功能}?>

<style>
.liInput {margin:10px 0;}
</style>
</head>
<body onLoad="showFlash();" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->


<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('cruises');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo BOX_CRUISES_ADMIN?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'cruises.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get', 'id="form_search" onsubmit="return false;" '); ?>
			<table width="200" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="main" nowrap>邮轮名称：<?= tep_draw_input_field('cruises_name')?>&nbsp;</td>
					<td class="main" nowrap>
					<?php
					$opctions[0] = array('id'=>0, 'text'=>'-供应商-');
					$opctions = array_merge($opctions,tep_get_all_agency());
					echo tep_draw_pull_down_menu('agency_id',$opctions);
					?>
					</td>
					<td class="main" nowrap><input type="submit" style="display:none" /><input type="button" onClick="jQuery('#form_search').submit();" value="搜索(Enter)" /> 或 <input type="button" onClick="keyAdd()" value="增加(Ctrl+Enter)" /> <button type="button" onClick="window.location.href='<?= tep_href_link("cruises.php");?>'"> 返回(Back) </button>
					</td>
				</tr>
			</table>
			<div><?php if($login_groups_id == '1'){ echo $sql_str; }?></div>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->		  </td>
      </tr>
      <tr>
        <td>
		

		
		<fieldset>
		<?php if($_GET['action']=="edit" && (int)$_GET['cruises_id']){?>
	
<a id="fast-save-btn" href="#" onClick="document.getElementById('formC').submit();"></a>
<!--[if IE]>
<script type="text/javascript">
jQuery(function(){
	var btn = jQuery('#fast-save-btn');
	var timer;
	jQuery(window).scroll(function(){
		clearTimeout(timer);
		var t = jQuery(document).scrollTop();
		var l = 20;
		timer = setTimeout(function(){
			btn.css({left:l+'px',top:(t+390)+'px',zIndex:10});
		},300);
	});
});
</script>
<![endif]-->
		
		<legend align="left"> 编辑邮轮 </legend>
		
		<?php 
		$i = 0;
		//上传图片弹出层 start {
		$imageslayr_body_con_pat =
		<<<HTML

		<div>
			<dl class="uploadImg">
				<dd>
					<div class="photoUploading"><ul id="ulPhotoList"></ul></div>
					</dd>
				<dd>
					<a id="ALinkAddPhoto"  class="btn btnGrey" href="javascript:void(0)"><div id="divAddPhoto">加载中……</div></a>
					<button type="button" onClick="upload();">开始上传</button> <a href="javascript:showFlash();">允许上传jpg,gif,png格式的图片！图片宽高比例建议为24:10，如720px*300px</a>
					</dd>
				</dl>
			</div>
		
HTML;

		//文件夹树{
		function _dirtree($path="abc/") {
			global $imageDir,$http_url;
			$data = false;
			$d = dir($path);
			while(false !== ($v = $d->read())) {
				if($v == "." || $v == "..") { continue; }
				$file = $d->path."/".$v;
				//echo "<dt>$v</dt>";
				if(strpos($v,'150_thumb')!==false){
					$data[] = array('url'=> $http_url.$v,'fs_dir'=> $imageDir.$v, 'time'=>date("Y-m-d H:i:s", filemtime($imageDir.$v)) );
				}
				//if(is_dir($file)) dirtree($file);
			}
			$d->close();
			return $data;
		}

		$fileInfo = _dirtree($imageDir);

		$imageslayr_body_con_pat .= '
<div style="width:100%; max-height:400px; height:400px; overflow-y:auto; overflow-x:auto; ">
	<table id="imagesList" border="0" cellpadding="0" cellspacing="3"><tr class="dataTableRow">
		<td class="dataTableContent">图片</td>
		<td class="dataTableContent">URL</td>
		</tr>
';

		if(is_array($fileInfo)){
			foreach($fileInfo as $key => $val ){
				$imageslayr_body_con_pat .= '
		<tr>
			<td class="dataTableContent"><a target="_blank" href="'.str_replace('150_thumb','detail',$val['url']).'"><img src="'.$val['url'].'" width="100" /></a></td>
			<td class="dataTableContent"><a href="javascript:void(0)" onClick="selImagesToInput(this);" >'.$val['url'].'</a></td>
			</tr>';
			}
		}
		$imageslayr_body_con_pat .= '
</table>
</div>';
		//文件夹树}

		$layer_obj[sizeof($layer_obj)] = array('pop' => 'imageslayr_' . $i,
		'drag' => "imageslayr_drag_" . $i,
		'con' => "imageslayr_popupCon_" . $i,
		'con_width' => "895px",
		'body_id' => "imageslayr_LayerBody_" . $i,
		'title' => '邮轮图片库管理',
		'body_con' => $imageslayr_body_con_pat
		);

		$now_obj = $layer_obj[sizeof($layer_obj) - 1];
		$images_layers = pop_layer_tpl($now_obj);
		
		echo '<div><span class="col_a" target="_blank" style="z-index:5" onclick="imageInput=null; showPopup(\'' . $now_obj['pop'] . '\',\'' . $now_obj['con'] . '\',100);">邮轮图片库管理</span></div>';
		//上传图片弹出层 end }


		?>

		<button type="button" onClick="syncCabinDeckName();">同步客舱、甲板名称</button>
		<form action="" method="post" enctype="multipart/form-data" name="formC" id="formC">
		<table id="dataTable" border="0" cellspacing="1" cellpadding="0">
			  <tr class="dataTableRow">
			    <td height="25" align="right" nowrap="nowrap" class="dataTableContent">ID号：</td>
				<td align="left" nowrap="nowrap" class="dataTableContent"><?= $cruises_id;?><input name="cruises_id" type="hidden" value="<?= $cruises_id;?>"></td>
				</tr>
			  <tr class="dataTableRow">
			    <td height="25" class="dataTableContent" align="right">邮轮名称：</td>
			    <td class="dataTableContent" ><?= tep_draw_input_field('cruises_name','','size="100"');?></td>
			    </tr>
			  <tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">供应商：</td>
			  	<td class="dataTableContent" >
			  		<?= tep_draw_pull_down_menu('agency_id',$opctions,$agency_id);?>
		  		</td>
			  	</tr>
			  <tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">客舱：</td>
			  	<td class="dataTableContent" id="cruises_cabin_box" >
			  	<?php
			  	foreach((array)$cabins as $key => $val){
			  		$cabins[$key]['images_url_thumb_min'] = str_replace('detail_','150_thumb_',$cabins[$key]['images'][0]['images_url']);
				?>
			  		<ul id="Cabin_<?=$cabins[$key]['products_options_id']?>">
			  			<li><div style="padding-top:10px"><b>客舱<?= ($key+1).'：'.tep_db_output($cabins[$key]['cruises_cabin_name']);?></b> [<a href="javascript:delCabin(<?=$cabins[$key]['products_options_id']?>, <?= $cruises_id;?>)">删除</a>]<?= tep_draw_hidden_field('cabins['.$key.'][products_options_id]', $cabins[$key]['products_options_id']);?><?= tep_draw_hidden_field('cabins['.$key.'][cruises_cabin_name]', $cabins[$key]['cruises_cabin_name']);?></div></li>
			  			<li><span>排序：</span><?= tep_draw_input_field('cabins['.$key.'][sort_id]',$cabins[$key]['sort_id'],'size="4"');?></li>
			  			<li><span>客舱介绍：</span><?= tep_draw_textarea_field('cabins['.$key.'][cruises_cabin_content]','wrap',50,5, $cabins[$key]['cruises_cabin_content']);?></li>
			  			
			  			<li>
						<span>客舱图片：</span>
						<a target="_blank" href="<?= $cabins[$key]['images'][0]['images_url']?>"><img id="cabins[<?=$key?>][images_url_thumb_min]_src" src="<?= $cabins[$key]['images_url_thumb_min']?>" /></a>
						<?= tep_draw_input_field('cabins['.$key.'][images_url_thumb_min]',$cabins[$key]['images_url_thumb_min'], 'size="100" onClick="openImagesLayer(this);" ');?>
						</li>
			  			<li>
			  				
			  				<?php
			  				if(is_array($cabins[$key]['decks'])){
			  					echo '<ul style="margin-left:60px;"><li><b>客舱甲板</b></li></ul>';
			  				}
			  				foreach((array)$cabins[$key]['decks'] as $key1 => $val1){
			  					$cabins[$key]['decks'][$key1]['images_url_thumb_min'] = str_replace('detail_','150_thumb_',$cabins[$key]['decks'][$key1]['images'][0]['images_url']);
					?>
			  				<ul style="margin-left:60px;" id="Decks_<?= $cabins[$key]['decks'][$key1]['products_options_values_id'];?>">
			  					<li><b>客舱甲板 <?= ($key1+1);?></b> <?= tep_db_output($cabins[$key]['decks'][$key1]['cruises_cabin_deck_name']);?> [<a href="javascript:delDecks(<?=$cabins[$key]['decks'][$key1]['products_options_values_id']?>, <?= $cruises_id;?>)">删除</a>] <?= tep_draw_hidden_field('cabins['.$key.'][decks]['.$key1.'][products_options_values_id]',$cabins[$key]['decks'][$key1]['products_options_values_id']);?></li>
			  					<li><span>甲板排序：</span><?= tep_draw_input_field('cabins['.$key.'][decks]['.$key1.'][sort_id]',$cabins[$key]['decks'][$key1]['sort_id'],'size="4"');?></li>
			  					<li><span>甲板名称：</span><?= tep_draw_input_field('cabins['.$key.'][decks]['.$key1.'][cruises_cabin_deck_name]',$cabins[$key]['decks'][$key1]['cruises_cabin_deck_name'],'size="30" readonly="true" ');?></li>
			  					<li><span>最多入住：</span><?= tep_draw_input_num_en_field('cabins['.$key.'][decks]['.$key1.'][max_per_of_guest]',(($cabins[$key]['decks'][$key1]['max_per_of_guest']) ? $cabins[$key]['decks'][$key1]['max_per_of_guest'] : $maxPerOfGuest),'size="30"');?>人 <em>最多不超过<?= $maxPerOfGuest?>人</em></li>
			  					<li><span>最少入住：</span><?= tep_draw_input_num_en_field('cabins['.$key.'][decks]['.$key1.'][min_num_guest]',$cabins[$key]['decks'][$key1]['min_num_guest'],'size="30"');?>人 <em>最少不少于<?= $minNumGuest?>人</em></li>
								
			  					<li><span>甲板介绍：</span><?= tep_draw_textarea_field('cabins['.$key.'][decks]['.$key1.'][cruises_cabin_deck_content]','wrap',50,5, $cabins[$key]['decks'][$key1]['cruises_cabin_deck_content']);?></li>
								<li><span>甲板图片：</span>
								<a target="_blank" href="<?= $cabins[$key]['decks'][$key1]['images'][0]['images_url']?>"><img id="cabins[<?=$key?>][decks][<?=$key1?>][images_url_thumb_min]_src" src="<?= $cabins[$key]['decks'][$key1]['images_url_thumb_min']?>" /></a>
								<?= tep_draw_input_field('cabins['.$key.'][decks]['.$key1.'][images_url_thumb_min]',$cabins[$key]['decks'][$key1]['images_url_thumb_min'], 'size="100" onClick="openImagesLayer(this);" ');?></li>
								
			  					</ul>
			  				<?php }?>
			  				</li>
			  			</ul>
			  		<?php } ?>
			  		
			  		
			  		
			  		<ul>
			  			<li><span><a href="javascript:showPanl(1)">添加新客舱</a></span><span><a href="javascript:showPanl(2)">添加新甲板</a></span></li>
			  			</ul>
			  		
			  		<ul id="addCabin" style="display:none">
			  			<li>
			  				<span>客舱名称：</span>
							<?php
							$filter_products_options_id = '';
							//$sql = tep_db_query('SELECT products_options_id FROM `cruises_cabin` WHERE 1 ');
							$sql = tep_db_query('SELECT products_options_id FROM `cruises_cabin` WHERE cruises_id ="'.(int)$cruises_id.'" ');
							while($rows = tep_db_fetch_array($sql)){
								$filter_products_options_id[] = $rows['products_options_id'];
							}
							$attrOptions = getAgencyProductsOptions($agency_id, implode(',',(array)$filter_products_options_id));
							echo tep_draw_pull_down_menu('_products_options_id', $attrOptions);
							?>
							<button type="button" onClick="addCabin(<?= $cruises_id?>)">确定添加客舱</button>
			  				</li>
			  			</ul>
			  		
			  		<ul id="addDeck" style="display:none">
			  			<li><span>所属客舱：</span>
						<?php
						$cruises_cabin_options[0] = array('id'=>"0",'text'=>'--请选择客舱--');
						foreach((array)getCruisesCabinOptions($cruises_id) as $key => $val){
							$cruises_cabin_options[] = $val;
						}
						echo tep_draw_pull_down_menu('_products_options_id_1', $cruises_cabin_options, '', 'onChange="getChildDecks(this.value)" ');
						?>
			  			<li id="DecksPullDownMenu">
						<span>甲板名称：</span>
						</li>
			  			<li>
							<button type="button" onClick="addDeck()">确定添加甲板</button>
			  			</li>
			  			</ul>
						<script type="text/javascript">
						function getChildDecks(val){
							if(val>0){
								var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=getChildDecks')) ?>");
								url += '&products_options_id='+encodeURIComponent(val);
								url += '&ajax=true';
								ajax_get_submit(url);
							}
						}
						</script>
		  		</td>
			  	</tr>
			  
			  <tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">邮轮介绍：</td>
			  	<td class="dataTableContent"  >
			  		<?php
			  		//tep_draw_textarea_field('cruises_content','wrap',80,10)
			  		//$ckeditor->editor('cruises_content', $cruises_content);
			  		//读取介绍

				?>
			  		<ul id="attribute">
			  			<?php
						$subAOptions = $AOptions;
			  			if(is_array($attributes)){
			  				foreach($attributes as $i => $val){
								foreach((array)$subAOptions as $key => $vals){
									if($subAOptions[$key]['id'] == $attributes[$i]['attribute_id']){
										unset($subAOptions[$key]);
									}
								}
				?>
			  			<li class="liInput">
			  				<?php
							echo tep_draw_pull_down_menu('attributes['.$i.'][attribute_id]',$AOptions,$attributes[$i]['attribute_id']).'：';
			  				echo tep_draw_textarea_field('attributes['.$i.'][value_content]', 'wrap', 80, 3,$attributes[$i]['value_content']).' 排序：'.tep_draw_input_num_en_field('attributes['.$i.'][sort_id]',$attributes[$i]['sort_id'],'size="4" ');
				?>
			  				</li>
			  			<?php
							}
						}
						
						$_subAOptions = array();
						foreach($subAOptions as $key => $val){
							$_subAOptions[] = $val;
						}
						?>
			  			</ul>
			  		<ul><li><a href="javascript:addAttribute();">添加邮轮介绍</a></li></ul>
			  		<script type="text/javascript">
			  		function addAttribute(){
			  			var i = jQuery('#attribute li').length;
			  			var selects = '<?= preg_replace('/[[:space:]]+/',' ',tep_draw_pull_down_menu('attributes[][attribute_id]',$_subAOptions));?>';
			  			var textarea = '<?= preg_replace('/[[:space:]]+/',' ',tep_draw_textarea_field('attributes[][value_content]', 'wrap', 80, 3));?>';
			  			var input = '<?= preg_replace('/[[:space:]]+/',' ',tep_draw_input_num_en_field('attributes[][sort_id]','','size="4" '));?>';
			  			selects = selects.replace(/attributes\[\]/,'attributes['+i+']');
			  			textarea = textarea.replace(/attributes\[\]/,'attributes['+i+']');
			  			input = input.replace(/attributes\[\]/,'attributes['+i+']');
			  			if(i>0){
			  				jQuery('#attribute li:last').after('<li class="liInput">'+selects+'：'+textarea+' 排序：'+input+'</li>');
			  			}else{
			  				jQuery('#attribute').append('<li class="liInput">'+selects+'：'+textarea+' 排序：'+input+'</li>');
			  			}
			  		}
				</script>
		  		</td>
			  	</tr>
			
			<tr class="dataTableRow">
				<td height="25" align="right" class="dataTableContent">政府收费和税收：</td>
				<td class="dataTableContent">
				<?php
				//选择税收属性ID
				$attrOptions = getAgencyProductsOptions($agency_id, implode(',',(array)$filter_products_options_id));
				$tax_attrOptions = array();
				$tax_attrOptions[] = array('id'=>"0",'text'=>"--选择一个税项--");
				foreach($attrOptions as $key => $val){
					if(preg_match('@税@',$val['text'])){
						$tax_attrOptions[] = $val;
					}
				}
				
				echo tep_draw_pull_down_menu('tax_products_options_id', $tax_attrOptions, '','onChange="autoWriteTaxText(this)" ');
				echo tep_draw_input_num_en_field('tax','','id="taxTextInput" size="10" ');
				?>
				<script type="text/javascript">
				function autoWriteTaxText(obj){
					var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("cruises.php",'action=getTaxText')) ?>")+"&products_options_id="+obj.value;
					ajax_get_submit(url);
				}
				</script>
				</td>
				</tr>
			<tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">政府收费和税收说明：</td>
			  	<td class="dataTableContent">
			  		<?php

			  		//$ckeditor->editor('tax_content', $tax_content);
			  		echo tep_draw_textarea_field('tax_content', 'wrap', 80, 3);
					?>
		  		</td>
			  	</tr>

			
			  <tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">&nbsp;</td>
			  	<td class="dataTableContent" >&nbsp;</td>
			  	</tr>
			  <tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">邮轮介绍图片：</td>
			  	<td class="dataTableContent" >
			  		
			  		
			  		<ul id="cruisesImages">
			  			<?php
			  			if(is_array($images['cruises'])){
			  				foreach($images['cruises'] as $key => $val){
			  					$images['cruises'][$key]['images_url_thumb_min'] = str_replace('detail_','150_thumb_',$images['cruises'][$key]['images_url']);
					?>
			  			<li>
			  				<div><?=($key+1)?>
			  					<a target="_blank" href="<?= $images['cruises'][$key]['images_url']?>"><img id="images[cruises][<?=$key?>][images_url_thumb_min]_src" src="<?= $images['cruises'][$key]['images_url_thumb_min']?>" /></a>
			  					</div>
			  				<div><?=($key+1)?>图片：<?= tep_draw_input_field('images[cruises]['.$key.'][images_url_thumb_min]',$images['cruises'][$key]['images_url_thumb_min'], 'size="100" onClick="openImagesLayer(this);" ');?></div>
			  				<div><?=($key+1)?>标题：<?= tep_draw_input_field('images[cruises]['.$key.'][images_title]', $images['cruises'][$key]['images_title'], 'size="100"');?></div>
			  				<div><?=($key+1)?>排序：<?= tep_draw_input_field('images[cruises]['.$key.'][sort_id]', $images['cruises'][$key]['sort_id'], 'size="4"');?></div>
			  				<div><?=($key+1)?>介绍：<?= tep_draw_textarea_field('images[cruises]['.$key.'][images_content]', 'wrap',97,3, $images['cruises'][$key]['images_content']);?></div>
			  				
			  				</li>
			  			<?php
			  				}
			  			}
					?>
			  			</ul>
						
						<ul>
						<li><a href="javascript:addCruisesImages();">添加邮轮介绍图片</a></li>
						</ul>

<script type="text/javascript">						
/*添加邮轮介绍图片*/
function addCruisesImages(){
	var n = jQuery('#cruisesImages li').length;
	var _img = '<?= preg_replace('/[[:space:]]+/',' ','<div><a target="_blank" href=""><img id="images[cruises][][images_url_thumb_min]_src" src="" /></a></div>');?>';
	var _imgsrc = '<div>'+(n+1)+'图片：<?= preg_replace('/[[:space:]]+/',' ',tep_draw_input_field('images[cruises][][images_url_thumb_min]','', 'size="100" onClick="openImagesLayer(this);" '));?></div>';
	var _title = '<div>'+(n+1)+'标题：<?= preg_replace('/[[:space:]]+/',' ',tep_draw_input_field('images[cruises][][images_title]','', 'size="100"'));?></div>';
	var _sort = '<div>'+(n+1)+'排序：<?= preg_replace('/[[:space:]]+/',' ',tep_draw_input_field('images[cruises][][sort_id]','', 'size="4"'));?></div>';
	var _content = '<div>'+(n+1)+'介绍：<?= preg_replace('/[[:space:]]+/',' ',tep_draw_textarea_field('images[cruises][][images_content]', 'wrap',97,3));?></div>';
	_img = _img.replace(/\[\]/,'['+n+']');
	_imgsrc = _imgsrc.replace(/\[\]/,'['+n+']');
	_title = _title.replace(/\[\]/,'['+n+']');
	_sort = _sort.replace(/\[\]/,'['+n+']');
	_content = _content.replace(/\[\]/,'['+n+']');

	jQuery('#cruisesImages').append('<li>'+_img+_imgsrc+_title+_sort+_content+'</li>');

}
</script>

			  		
			  		</td>
		  	</tr>
			  <tr class="dataTableRow">
			  	<td height="25" align="right" class="dataTableContent">&nbsp;</td>
			  	<td class="dataTableContent" >
			  		<button type="submit">确定更新</button>
			  		<input name="action" type="hidden" id="action" value="editConfirm">
			  		
			  		</td>
		  	</tr>
			</table>
		
		</form>
		</fieldset>
		
		
		
		
		<?php }else{?>
		<legend align="left"> Stats Results </legend>
		  <table id="dataTable" border="0" cellspacing="1" cellpadding="0">
			  <tr class="dataTableHeadingRow">
			    <td width="150" align="center" nowrap="nowrap" class="dataTableHeadingContent">ID号</td>
				<td width="300" align="center" nowrap="nowrap" class="dataTableHeadingContent">邮轮名称</td>
			    <td width="300" align="center" nowrap="nowrap" class="dataTableHeadingContent">供应商</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">操作</td>
			  </tr>
			<?php while($cruises_rows = tep_db_fetch_array($cruises_query)){?>  
			  <tr id="tr_<?= $cruises_rows['cruises_id'];?>" class="dataTableRow">
			    <td class="dataTableContent"><?= $cruises_rows['cruises_id'];?></td>
			    <td height="25" class="dataTableContent" ><?= $cruises_rows['cruises_name'];?></td>
		        <td nowrap class="dataTableContent" ><?= tep_get_agencyname_from_id($cruises_rows['agency_id']);?></td>
		        <td nowrap class="dataTableContent">
		        	[<a href="<?= tep_href_link('cruises.php','action=edit&cruises_id='.$cruises_rows['cruises_id']);?>">编辑</a>]&nbsp;
		        	[<a href="javascript:void(0)" onClick="keyDelete(<?= $cruises_rows['cruises_id'];?>);">删除</a>]
					</td>
			  </tr>
			  
			<?php }?>  
			</table>
		</fieldset>
		<?php
		}
		?>
		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $cruises_split->display_count($cruises_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $cruises_split->display_links($cruises_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

<?php
/* 可移动的多重层 start {*/
echo $images_layers;
?>
<style type="text/css">
* {
	margin:0;
	padding:0;
	list-style: none;
}
img {
	border:0;
}
body {
	font-size:12px;
	font-family:宋体, Tahoma, Arial, Verdana, Helvetica, sans-serif;
	color:#353535;
}
/* 弹出层 */
.popup {
	display:none;
	position:absolute;
	text-align:center;
}
.actLayers .popupConTop {
	background:#eee;
}
.iframeBg {
	position:absolute;
	top:0;
	left:0;
	width:100%;
	height:100%;
}
.popupTable {
	position:relative;
	z-index:1;
}
.popupBg {
	display:none;
	position:absolute;
	z-index:99;
	top:0;
	left:0;
	background:#ccc;
	filter:alpha(opacity=30);
	-moz-opacity:0.3;
	opacity:0.3;
}
.popupBgIframe {
	filter:alpha(opacity=0);
	-moz-opacity:0;
	opacity:0;
}
.popupTable td.side {
	background:url(images/icons/popup_png.png);
}
.popupTable td.topLeft {
	width:6px;
	height:6px;
	background:url(images/icons/popup_t1.png);
}
.popupTable td.topRight {
	width:6px;
	height:6px;
	background:url(images/icons/popup_t3.png);
}
.popupTable td.botLeft {
	height:6px;
	background:url(images/icons/popup_b1.png);
}
.popupTable td.botRight {
	background:url(images/icons/popup_b3.png);
}
.popupTable td.con {
	background:none;
	_filter:none;
	background:#fff;
}
.popup .popupClose {
	float:right;
	margin:3px 0 0;
	width:16px;
	height:16px;
	cursor:pointer;
}
.popup .popupChange {
	float:right;
	margin:3px 10px 0 0;
	display:inline;
	width:14px;
	height:14px;
	border:1px solid #1d476d;
	color:#1d476d;
	text-align:center;
	font-size:15px;
	line-height:14px;
	cursor:pointer;
}
/* 弹出框按钮 */
.btnPopup {
	margin:15px 0 0 180px;
	display:inline;
}
.popupCon {
	padding:12px;
	text-align:left;
	overflow:hidden;
}
.popupCon .popupConTop {
	width:100%;
	height:25px;
	border-bottom:1px #DBDBDB dashed;
	position:relative;
	cursor:move;
}
.popupCon .popupConTop h4 {
	float:left;
	height:25px;
	line-height:25px;
	color:#FF6D03;
}
.popupCon .popupConTop h4 b {
	font-size:14px;
	color:#000;
}
.popupCon .popupConTop span {
	position:absolute;
	right:0;
	top:3px;
	width:16px;
	height:16px;
	cursor:pointer;
}
</style>

<script type="text/javascript">
function changeCon(n,popupId,conId){
	ObjId(conId).style.display= ObjId(conId).style.display==''?'none':'';
	ObjId(popupId).style.height = ObjId(popupId).offsetHeight > 65 ? "65px" : ObjId(popupId).offsetHeight;
	var ifr = null;
	var iframes = ObjId(popupId).getElementsByTagName('iframe'); //iframeBg
	for(var i=0; i<iframes.length; i++){
		if(iframes[i].className=="iframeBg"){
			iframes[i].style.height = ObjId(popupId).offsetHeight;
			break;
		}
	}
	n.innerHTML = n.innerHTML =="+"?"-":"+";
}
</script>

<script type="text/javascript">
// 弹出层
var ObjId=function(id){return document.getElementById(id)};
function bodySize(){
	var a=new Array();
	a.st = document.body.scrollTop?document.body.scrollTop:document.documentElement.scrollTop;
	a.sl = document.body.scrollLeft?document.body.scrollLeft:document.documentElement.scrollLeft;
	a.sw = document.documentElement.clientWidth;
	a.sh = document.documentElement.clientHeight;
	return a;
}

function centerElement(obj,top){
	var s = bodySize();
	var w = ObjId(obj).offsetWidth;
	var h = ObjId(obj).offsetHeight;
	ObjId(obj).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
	if(top!="" && top!=null){
		ObjId(obj).style.top = top+s.st+ "px";
	}else{
		ObjId(obj).style.top = parseInt((s.sh - h)/2) + s.st + "px";
	}
}

function hideAllSelect(){
	var selects = document.getElementsByTagName("SELECT");
	for(var i = 0 ; i<selects.length;i++){
		selects[i].style.display = "none";
	}
}

function showPopup(popupId,popupCon,top){
	ObjId(popupId).style.display="block";  //显示弹出窗
	ObjId(popupId).style.width = (ObjId(popupCon).offsetWidth + 12) + "px";   //设置弹出层的宽度
	ObjId(popupId).style.height = (ObjId(popupCon).offsetHeight + 12) + "px";  //设置弹出层的高度
	centerElement(popupId,top);
	//hideAllSelect();
	window.onresize = function() {centerElement(popupId,top);}//屏幕改变的时候重新设定悬浮框

	//设置弹出层层级显示
	findLayer(popupId);
}

function closePopup(popupId){
	ObjId(popupId).style.display='none';
	ObjId("popupBg").style.display='none';
}

//以下是与拖动层有关的代码 php只改pop_id_string和drag_id_string即可
<?php
if (is_array($layer_obj) && sizeof($layer_obj)) {
	$pop_id_string = $drag_id_string = "";
	for ($i = 0; $i < sizeof($layer_obj); $i++) {
		$pop_id_string.=$layer_obj[$i]['pop'] . ',';
		$drag_id_string.=$layer_obj[$i]['drag'] . ',';
	}
	$pop_id_string = substr($pop_id_string, 0, -1);
	$drag_id_string = substr($drag_id_string, 0, -1);
}
?>

var pop_id_string = "<?= $pop_id_string; ?>";
var drag_id_string = "<?= $drag_id_string; ?>";
var pop_ids = pop_id_string.split(',');
var drag_ids = drag_id_string.split(',');
if(pop_ids.length!=drag_ids.length){ alert("Pop与Drag总数不一样。"); }
var layers = new Array();
function findLayer(eID){
	for(var i=0; i<pop_ids.length; i++){
		layers[i] = ObjId(pop_ids[i]);
	}
	for(j = 0 ; j < layers.length;j++){
		if(layers[j].id == eID){
			jh(j);
			px(j);
			break;
		}else{
			continue;
		}
	}
}

function jh(y){
	for(a=0;a<layers.length;a++){
		if(layers[y].id == layers[a].id){
			layers[a].className = 'popup actLayers';
		}else{
			layers[a].className = 'popup';
		}
	}
}

function px(x){//排序函数
	var maxNum;
	if(layers[x].style.zIndex == ''){layers[x].style.zIndex = 100;}
	maxNum = layers[x].style.zIndex;
	for(i=0;i<layers.length;i++){
		if(layers[i].style.zIndex == ''){layers[i].style.zIndex = 100;}
		if(maxNum <= layers[i].style.zIndex){
			maxNum = parseInt(layers[i].style.zIndex)+1;
		}else{
			continue;
		}
	}
	layers[x].style.zIndex = maxNum;
}

//弹出层顶部拖曳
Array.prototype.extend = function(C) {
	for (var B = 0, A = C.length; B < A; B++) {
		this.push(C[B]);
	}
	return this;
}

function divDrag() {
	var A, B, popupcn;
	this.dragStart = function(e) {
		e = e || window.event;
		if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
		var pos = this.popuppos;
		popupcn = this.parent || this;
		if (document.defaultView) {
			_top = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("top");
			_left = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("left");
		}
		else {
			if (popupcn.currentStyle) {
				_top = popupcn.currentStyle["top"];
				_left = popupcn.currentStyle["left"];
			}
		}
		pos.ox = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(_left);
		pos.oy = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(_top);
		if ( !! A) {
			if (document.removeEventListener) {
				document.removeEventListener("mousemove", A, false);
				document.removeEventListener("mouseup", B, false);
			}
			else {
				document.detachEvent("onmousemove", A);
				document.detachEvent("onmouseup", B);
			}
		}
		A = this.dragMove.create(this);
		B = this.dragEnd.create(this);
		if (document.addEventListener) {
			document.addEventListener("mousemove", A, false);
			document.addEventListener("mouseup", B, false);
		}
		else {
			document.attachEvent("onmousemove", A);
			document.attachEvent("onmouseup", B);
		}

		this.stop(e);
	}
	this.dragMove = function(e) {
		e = e || window.event;
		var pos = this.popuppos;
		popupcn = this.parent || this;
		popupcn.style.top = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(pos.oy) + 'px';
		popupcn.style.left = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(pos.ox) + 'px';
		this.stop(e);
	}
	this.dragEnd = function(e) {
		var pos = this.popuppos;
		e = e || window.event;
		if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
		popupcn = this.parent || this;
		if ( !! (this.parent)) {
			this.style.backgroundColor = pos.color
		}
		if (document.removeEventListener) {
			document.removeEventListener("mousemove", A, false);
			document.removeEventListener("mouseup", B, false);
		}
		else {
			document.detachEvent("onmousemove", A);
			document.detachEvent("onmouseup", B);
		}
		A = null;
		B = null;
		//popupcn.style.zIndex=(++zIndex);
		this.stop(e);
	}
	this.shiftColor = function() {
		this.style.backgroundColor = "#dddddd";
	}
	this.position = function(e) {
		var t = e.offsetTop;
		var l = e.offsetLeft;
		while (e = e.offsetParent) {
			t += e.offsetTop;
			l += e.offsetLeft;
		}
		return {
			x: l,
			y: t,
			ox: 0,
			oy: 0,
			color: null
		}
	}
	this.stop = function(e) {
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
		if (e.preventDefault) {
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
	}
	this.stop1 = function(e) {
		e = e || window.event;
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
	}
	this.create = function(bind) {
		var B = this;
		var A = bind;
		return function(e) {
			return B.apply(A, [e]);
		}
	}
	this.dragStart.create = this.create;
	this.dragMove.create = this.create;
	this.dragEnd.create = this.create;
	this.shiftColor.create = this.create;
	this.initialize = function() {
		for (var A = 0, B = arguments.length; A < B; A++) {
			C = arguments[A];
			if (! (C.push)) {
				C = [C];
			}
			popupC = (typeof(C[0]) == 'object') ? C[0] : (typeof(C[0]) == 'string' ? ObjId(C[0]) : null);
			if (!popupC) continue;
			popupC.popuppos = this.position(popupC);
			popupC.dragMove = this.dragMove;
			popupC.dragEnd = this.dragEnd;
			popupC.stop = this.stop;
			if ( !! C[1]) {
				popupC.parent = C[1];
				popupC.popuppos.color = popupC.style.backgroundColor;
			}
			if (popupC.addEventListener) {
				popupC.addEventListener("mousedown", this.dragStart.create(popupC), false);
				if ( !! C[1]) {
					popupC.addEventListener("mousedown", this.shiftColor.create(popupC), false);
				}
			}
			else {
				popupC.attachEvent("onmousedown", this.dragStart.create(popupC));
				if ( !! C[1]) {
					popupC.attachEvent("onmousedown", this.shiftColor.create(popupC));
				}
			}
		}
	}
	this.initialize.apply(this, arguments);
}

function auto_new_obj(){
	for(var i=0; i<pop_ids.length; i++){
		new divDrag([ObjId(drag_ids[i]),ObjId(pop_ids[i])]);
	}
}
auto_new_obj();


</script>

<?php
/* 可移动的多重层 end }*/
?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
