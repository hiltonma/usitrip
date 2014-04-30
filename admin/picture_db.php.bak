<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

/**
 * 图片库管理
 */
require('includes/application_top.php');

// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('picture_db');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
  
require('includes/classes/picture_db.php');
// print_r($_SESSION);
$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$error = false;
$pic = new picture_db;

switch($action){
	case "uploadImage":	//上传和压缩图片
	echo $pic->uploadImage();
	exit;
	break;
	
	case "addToDB": //写资料到数据库
	if(tep_not_null($_POST['oldFileName'])){	//更新图片
		$pic->replaceImage($messageStack, $_POST['oldFileName']);
		tep_redirect(tep_href_link('picture_db.php',tep_get_all_get_params(array('action'))));
	}else {	//插入新图片
		$pic->insert($messageStack);
		tep_redirect(tep_href_link('picture_db.php',tep_get_all_get_params(array('page','action'))));
	}
	exit;
	break;
	
	case "deleteImage":	//删除图片
		$js_str = '[JS]';
		if((int)$_GET['picture_id'] && $login_groups_id=="1"){
			$pIds = $pic->getProductsIds((int)$_GET['picture_id']);
			if(is_array($pIds) && sizeof($pIds)>0){
				$js_str.= 'alert("该图片已经被以下产品使用，不可删除！'.implode(',',$pIds).'");';
			}else{
				$succeed_del_num = $pic->delete((int)$_GET['picture_id']);
				$messageInfo = '';
				if($succeed_del_num > 0){
					$messageInfo = '成功删除了'.$succeed_del_num.'张图片！';
					$messageStack->add_session($messageInfo, 'success');
				}else{
					$messageInfo = '没有图片被删除！';
					$messageStack->add_session($messageInfo, 'error');
				}
				$js_str.= 'window_reload();';
				$js_str.= 'alert("'.$messageInfo .'");';
			}
			
		}elseif($login_groups_id!="1"){
			$js_str.= 'alert("您不是顶级管理员，无权做删除操作！");';
		}
		$js_str.= '[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
	exit;
	break;
	
	case "deleteImages":	//批量删除图片
		$js_str = '[JS]';
		if($login_groups_id!="1"){
			$js_str.= 'alert("您不是顶级管理员，无权做删除操作！");';
		}else{
			$succeed_del_num = 0;
			for($_picture_id = (int)$_POST['picture_id_start']; $_picture_id <= (int)$_POST['picture_id_end']; $_picture_id++){
				$pIds = $pic->getProductsIds((int)$_picture_id);
				if(is_array($pIds) && sizeof($pIds)>0){
					//$js_str.= 'alert("该图片已经被以下产品使用，不可删除！'.implode(',',$pIds).'");';
				}else{
					$succeed_del_num+= $pic->delete((int)$_picture_id);
				}
				
			}
			
			if($succeed_del_num > 0){
				$messageInfo = '成功删除了'.$succeed_del_num.'张图片！';
				$messageStack->add_session($messageInfo, 'success');
			}else{
				$messageInfo = '没有图片被删除！';
				$messageStack->add_session($messageInfo, 'error');
			}
			$js_str.= 'window_reload();';
			$js_str.= 'alert("'.$messageInfo .'");';
		}
		$js_str.= '[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
	exit;
	break;
}

//取得国家、州省、城市和景点等初始资料
$countries = array();
$countries[0] = array('id'=>'','text'=>PLEASE_SELECT);
$countries = array_merge($countries, tep_get_countries());
$zones = tep_prepare_country_zones_pull_down($_GET['countries_id']);
$cities = tep_prepare_zones_city_pull_down($_GET['zone_id']);
//图片列表
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>图片库管理</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
/* 重载页面 */
function window_reload(){
	location.reload();
}

/* 删除图片*/
function delete_image(picture_id){
	if(picture_id>0){
		if(confirm("您真的要删除此图片？有风险哟……")){
			var url = 'picture_db.php?action=deleteImage&picture_id=' + picture_id;
			ajax_get_submit(url);
		}
	}
}
/* 批量删除图片*/
function delete_images(from){
	var _picture_id_start = $('#picture_id_start').val();
	var _picture_id_end = $('#picture_id_end').val();
	if(_picture_id_start > _picture_id_end || _picture_id_start<1 || _picture_id_end<1){
		alert("您的操作不符合规则，暂取消您删除图片的权利！"+'_picture_id_start:'+_picture_id_start+' _picture_id_end:'+_picture_id_end);
		return false;
	}
	if(confirm("您真的要批量删除图片？有很大风险哟……")){
		var url = 'picture_db.php?action=deleteImages';
		var from_id = from.id;
		$(from).find('button').html('删除中，请稍候……');
		$(from).find('button').attr('disabled',true);
		ajax_post_submit(url,from_id);
	}
	return false;
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

/*复制网址*/
function linkCopy(fromId) {
var msg = document.getElementById(fromId).innerHTML;
	if (document.all){
		if(window.clipboardData.setData("Text",msg)){
			alert("成功复制："+msg+"\t");
		}
	}else{
		alert("实在是对不起，目前此功能只支持IE内核的浏览器！");
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
var fileMaxNum = 50;	//每次最多能上传几张？
var phpUrl = "picture_db.php?action=uploadImage";	//接收图片的网址
var imagesWidth = 800; //输出的图片最大宽度
var imagesHeight = 600; //输出的图片最大高度
var imagesQuality = 90; //图片质量
var PhotoUploaderUrl = "<?= HTTP_SERVER?>/admin/includes/javascript/zip_upload/PhotoUploader.swf"; //图片上传处理的flash文件地址
var expressInstallUrl = "<?= HTTP_SERVER?>/admin/includes/javascript/zip_upload/expressInstall.swf"; //图片上传处理的flash扩展文件地址
var inputBoxId = "upload_all_picture";	//保存返回结果的文本框ID

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
						//tmp_array[6];	//返回的 图片缩略图网址720
					}
					if(inputBoxId!=""){
						var tmp_var = jQuery("#"+inputBoxId).val()+message+";";
						jQuery("#"+inputBoxId).val(tmp_var);
						jQuery("#"+inputBoxId).show();
					}
					/* 此处针对本文件特殊设置，复制到其它文件时可免{*/
					jQuery("#imagesList tr:first").after('<tr><td class="dataTableContent"><a target="_blank" href="'+tmp_array[4]+'"><img src="'+tmp_array[5]+'" width="100" /></a></td>	<td class="dataTableContent"><a href="javascript:void(0)" onClick="selImagesToInput(this);" >'+tmp_array[5]+'</a></td></tr>');
					jQuery("#submitToDb").show();
					/* 此处针对本文件特殊设置，复制到其它文件时可免}*/
					//alert(message);	//打印上传文件之后的返回信息

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
		alert("请点击“选择上传文件”按钮来选择单张或多张图片，之后再点击“开始上传”按钮！");
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


</head>

<body onLoad="showFlash();" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div>
<h1>Picture图片管理</h1>
<fieldset>
<legend align="left"> 图片搜索/筛选 </legend>

<form action="" method="get" enctype="application/x-www-form-urlencoded">
<table>
<tr><td>
<label>国家：<?php echo tep_draw_pull_down_menu('countries_id', $countries, '',' id="s_countries_id" onChange="this.form.submit();"');?></label>
<label>所属省份/州名：<?php echo tep_draw_pull_down_menu('zone_id', $zones, '','id="s_zone_id" onChange="this.form.submit();"');?></label>
<label>所属城市/景点：<?php echo tep_draw_pull_down_menu('city_id', $cities, '','id="s_city_id" onChange="this.form.submit();"');?></label>
<label>图片名称：<?php echo tep_draw_input_field('picture_name');?></label>
<label>城市/景点：<?php echo tep_draw_input_field('city_name');?></label>
<label><button type="submit">开始搜索</button></label>
<label><a href="<?= tep_href_link('picture_db.php');?>">清空搜索选项</a></label>
<label><button type="button" onclick="imageInput=null; if($('#s_countries_id').val()<1 || $('#s_zone_id').val()<1 || $('#s_city_id').val()<1) { alert('请先选国家、省份和景点……');}else{showPopup('imageslayr_0','imageslayr_popupCon_0',100);}">上传新图片</button>
</label>
</td></tr>
</table>
</form>

</fieldset>
		<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('picture_db');
		$list = $listrs->showRemark();
		?>
<fieldset>
	<legend align="left"> 图片列表 </legend>
<?php
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>

<table width="100%" cellspacing="1" cellpadding="0" border="0">
  <tbody><tr class="dataTableHeadingRow">
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent" height="25">ID号</td>
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent">缩略图</td>
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent">名称</td>
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent">原图地址</td>
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent">所属类别</td>
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent">使用了此图片的产品</td>
	<td nowrap="nowrap" align="center" class="dataTableHeadingContent">操作</td>
  </tr>
  <?php
  $lists = $pic->lists(true);
  if(is_array($lists)){
  	for($i = 0, $n = (sizeof($lists)-1); $i < $n; $i++){
  		if(!$_class || $_class == "attributes-odd"){
  			$_class = "attributes-even";
  		}elseif($_class == "attributes-even"){
  			$_class = "attributes-odd";
  		}
  ?>
  <tr class="<?= $_class;?>">
	<td class="dataTableContent"><?= $lists[$i]['picture_id'];?>&nbsp;</td>
	<td class="dataTableContent" style="padding:5px;">
	<a href="<?= $lists[$i]['picture_url'];?>" target="_blank"><img src="<?= $lists[$i]['picture_url_thumb'];?>" /></a>
	<br />
	[<a href="javascript:void(0);" onClick="showPopup('imageslayr_0','imageslayr_popupCon_0',100); $('#oldFileName').val($(this).attr('oldSrc')); $('#pictureName').val($(this).attr('oldName')); " oldSrc="<?= $lists[$i]['picture_dir_file'];?>" oldName="<?= tep_db_output($lists[$i]['picture_name']);?>">替换</a>]</td>
	<td nowrap="" class="dataTableContent"><?= tep_db_output($lists[$i]['picture_name']);?></td>
	<td nowrap="" class="dataTableContent">
	大图：<i style="font-style:normal; color:#CCCCCC;">点击左边图片打开的那张图片就是大图！</i><br /><span id="bigPic<?= $lists[$i]['picture_id'];?>"><?= $lists[$i]['picture_url'];?></span>&nbsp;&nbsp;[<a href="javascript:void(0)" onclick="linkCopy('bigPic<?= $lists[$i]['picture_id'];?>')">复制地址</a>]<br />
	小图：<i style="font-style:normal; color:#CCCCCC;">小图就是左边那张图片！</i><br /><span id="minPic<?= $lists[$i]['picture_id'];?>"><?= $lists[$i]['picture_url_thumb'];?></span>&nbsp;&nbsp;[<a href="javascript:void(0)" onclick="linkCopy('minPic<?= $lists[$i]['picture_id'];?>')">复制地址</a>]
	</td>
	<td nowrap="" class="dataTableContent">
	<?php
	if(!tep_not_null($lists[$i]['countries_name'])){
		echo tep_db_get_field_value('countries_name', 'countries', ' countries_id='.(int)$lists[$i]['countries_id'] );
	}else{
		echo $lists[$i]['countries_name'];
	}
	?>
	&gt;&gt;
	<?php
	if(!tep_not_null($lists[$i]['zone_name'])){
		echo tep_db_get_field_value('zone_name', 'zones', ' zone_id='.(int)$lists[$i]['zone_id'] );
	}else{
		echo $lists[$i]['zone_name'];
	}
	?>
	&gt;&gt;
	<?php
	if(!tep_not_null($lists[$i]['city'])){
		echo tep_db_get_field_value('city', 'tour_city', ' city_id='.(int)$lists[$i]['city_id'] );
	}else{
		echo $lists[$i]['city'];
	}
	?>
	</td>
	<td nowrap="" class="dataTableContent">
	<?php
	$pids = array_trim($pic->getProductsIds($lists[$i]['picture_id']),',');
	$_tmp_num = 0;
	foreach ((array)$pids as $_key => $_product_id){
		if((int)$_product_id){
			$_tmp_num++;
			//echo '<a target="_blank" href="'.tep_catalog_href_link('product_info.php','products_id='.$_product_id).'">'.$_tmp_num.'. '.tep_get_products_name($_product_id).'</a><br />';
			echo '<a target="_blank" href="'.tep_catalog_href_link('product_info.php','products_id='.$_product_id).'">'.$_product_id.'</a><br />';
		}
	}
	?>
	</td>
	<td nowrap="" class="dataTableContent">
	<?php if($login_groups_id =="1"){?>
	[<a href="javascript:void(0);" onclick="delete_image(<?= $lists[$i]['picture_id'];?>)">删除</a>]
	<?php }else{?>
	<span style="color:#666666;">[删除]</span>
	<?php } ?>
	</td>
  </tr>
  <?php
  	}
  }
  $split_page_info = $lists['splitPageResults'];
  ?>
  
	<tr>
		
		<td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
			<td class="smallText" valign="top"><?php echo $lists['splitPageResults']['display_count']; ?></td>
			<td class="smallText" align="right"><?php echo $lists['splitPageResults']['display_links']; ?>&nbsp;</td>
		  </tr>
		</table></td>
	  </tr>  
</tbody></table>
</fieldset>

<?php if($login_groups_id =="1"){	//顶级管理员才有批量删除权限?>
<fieldset>
<legend align="left"> 批量删除 </legend>
<form id="delete_images_form" action="" method="post" enctype="multipart/form-data" onsubmit="delete_images(this); return false;">
<table>
<tr><td>
<label>ID号从<?php echo tep_draw_input_field('picture_id_start','','id="picture_id_start"');?></label>
<label>至<?php echo tep_draw_input_field('picture_id_end','','id="picture_id_end"');?></label>
<label><button type="submit">开始删除</button></label>
</td></tr>
</table>
</form>

</fieldset>
<?php }?>

</div>

<?php
/* 可移动的多重层 start {*/
?>

<div class="popup" id="imageslayr_0" onClick="findLayer('imageslayr_0')" >
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
	<tr>
	<td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
	<td class="con">
	<div class="popupCon" id="imageslayr_popupCon_0" style="width:895px">
	<div class="popupConTop" id="imageslayr_drag_0" ondblclick="changeCon(document.getElementById('imageslayr_0_H_title_top'),'imageslayr_0','imageslayr_LayerBody_0');">
	<h4><b>Picture图片添加</b></h4>
	<div class="popupClose" onClick="document.getElementById('imageslayr_0').style.display='none'"><img src="images/icons/icon_x.gif" alt="close"/></div>
	<div class="popupChange" title="最小化/还原" id="imageslayr_0_H_title_top" onClick="changeCon(this,'imageslayr_0','imageslayr_LayerBody_0');">-</div>
	</div>
	<form action="<?= tep_href_link('picture_db.php',tep_get_all_get_params());?>" method="post" enctype="multipart/form-data" target="_self" id="form_add">
	<input type="hidden" name="action" value="addToDB" />
	<div id="imageslayr_LayerBody_0">
		<div class="picture_form" style="display:none" >
		<label>国家：<?php echo tep_draw_pull_down_menu('countries_id', $countries);?></label>
		<label>所属省份/州名：<?php echo tep_draw_pull_down_menu('zone_id', $zones);?></label>
		<label>所属城市/景点：<?php echo tep_draw_pull_down_menu('city_id', $cities);?></label>
		<label>已经上传的图片资料字符串：<input id="upload_all_picture" name="upload_all_picture" type="text" /></label>		
		<label>如果此值不为空则是替换此文件：<input id="oldFileName" name="oldFileName" type="text" /></label>		
		</div>
		<div>
		<label>图片名称：<?php echo tep_draw_input_field('picture_name','','id="pictureName"');?></label>
		<label><input name="add_watermark" type="checkbox" value="1" checked="checked" /> 添加水印</label>
		</div>
		<div>
			<dl class="uploadImg">
				<dd>
					<div class="photoUploading"><ul id="ulPhotoList"></ul></div>
					</dd>
				<dd id="">
					<a id="ALinkAddPhoto"  class="btn btnGrey" href="javascript:void(0)"><div id="divAddPhoto">加载中……</div></a>
					<button type="button" onClick="upload();">开始上传</button> <a href="javascript:showFlash();" style="color:#FF0000;" >建议上传jpg格式的图片，不要传其它图片如bmp等！图片宽高比例建议为4:3，图片大小不限！</a>
					</dd>
				<dd id="submitToDb" style="display:none">
				<button type="submit" style="font-size:14px; font-weight:bold; color:#FF6600; padding:5px;">如果图片全部上传完毕，请点击这里保存到数据库！</button>
				</dd>
				</dl>
			</div>
		
<div style="width:100%; max-height:200px; height:200px; overflow-y:auto; overflow-x:auto; ">
	<table id="imagesList" border="0" cellpadding="0" cellspacing="3"><tr class="dataTableRow">
		<td class="dataTableContent">图片</td>
		<td class="dataTableContent">URL</td>
		</tr>
	</table>
</div></div>
	</form>
	</div>
	</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	</table>
	<iframe class="iframeBg" frameborder="0"></iframe>
	</div>

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
$layer_obj[sizeof($layer_obj)] = array('pop' => 'imageslayr_0',
'drag' => "imageslayr_drag_0",
'con' => "imageslayr_popupCon_0",
'con_width' => "895px",
'body_id' => "imageslayr_LayerBody_0",
'title' => '邮轮图片库管理',
'body_con' => ''
);

$now_obj = $layer_obj[sizeof($layer_obj) - 1];

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