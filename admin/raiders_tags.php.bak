<?php
set_time_limit(0);
require ('includes/application_top.php');
require DIR_WS_INCLUDES . 'classes/T.class.php';
require DIR_WS_INCLUDES . 'classes/RaidersTags.class.php';
$rt = new RaidersTags();
$mark = '';
$tags_name = isset($_GET['tags_name']) ? $_GET['tags_name'] : '';
$url = isset($_GET['tags_url']) ? $_GET['tags_url'] : '';
if (isset($_POST['ajax']) && $_POST['ajax']) {
	$need_update = false;
	switch ($_POST['ajax']) {
		case 'tags_name':
			$data['tags_name'] = iconv('UTF-8', CHARSET, $_POST['value']);
			$need_update = true;
			break;
		case 'tags_url':
			$data['tags_url'] = iconv('UTF-8', CHARSET, $_POST['value']);
			$need_update = true;
			break;
	}
	if ($need_update)
		$rt->update($data, $_POST['tags_id']);
	exit();
}
if ($_POST) {
	$data = array (
			'tags_name' => $_POST['tags_name'],
			'tags_url' => $_POST['tags_url'],
			'tags_uid' => uniqid() 
	);
	$rt->addOne($data);
}
if (isset($_GET['drop_id']) && $_GET['drop_id']) {
	if ($rt->drop($_GET['drop_id'])) {
		$mark = '<font color="#00CC33">删除成功</font>';
	} else {
		$mark = "<font color=red>删除失败！请确认该标签没有跟文章关联！！</font>";
	}
}
$info = $rt->getBackList($tags_name, $url);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>旅游攻略标签</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css"
	href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js"></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js"></script>
</head>

<body>
<?php
require (DIR_WS_INCLUDES . 'header.php');

?>
<h1>旅游攻略标签</h1>
	<br />
	<h1><?=$mark?></h1>
	<br />
	<form method="post" action="" name="add_tags">
		<table width="60%" border="1">
			<tr>
				<td>标签名称：</td>
				<td><input type="text" name="tags_name" /></td>
				<td>标签URL：</td>
				<td><input type="text" name="tags_url" /></td>
				<td><input type="submit" value="添加" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
	<br />
	<form method="get" action="" name="search">
		<table>
			<tr>
				<td>标签名称:</td>
				<td><input type="text" name="tags_name" value="<?=$tags_name?>" /></td>
				<td>标签URL：</td>
				<td><input type="text" name="tags_url" value="<?=$url?>" /></td>
				<td><input type="submit" value="查询" /></td>
				<td><input type="button" value="清除搜索结果"
					onclick="location.href='raiders_tags.php'" /></td>
			</tr>
		</table>
	</form>
	<table width="80%" border="1">
		<tr>
			<th>ID</th>
			<th>name</th>
			<th>URL</th>
			<th>action</th>
		</tr>
  <?php foreach($info['info'] as $value){?>
  <tr>
			<td><?=$value['tags_id']?></td>
			<td><span id="tags_name_span_<?=$value['tags_id']?>"
				onclick="showHideType('tags_name',<?=$value['tags_id']?>)"><?=$value['tags_name']?></span>
				<input type="text" id="tags_name_text_<?=$value['tags_id']?>"
				value="<?=$value['tags_name']?>"
				onblur="changeTypeName(this,<?=$value['tags_id']?>,'tags_name')"
				style="display: none" /></td>
			<td><span id="tags_url_span_<?=$value['tags_id']?>"
				onclick="showHideType('tags_url',<?=$value['tags_id']?>)"><?=$value['tags_url']?></span>
				<input type="text" id="tags_url_text_<?=$value['tags_id']?>"
				value="<?=$value['tags_url']?>"
				onblur="changeTypeName(this,<?=$value['tags_id']?>,'tags_url')"
				style="display: none" /></td>
			<td><a href="?drop_id=<?=$value['tags_id']?>">删除</a></td>
		</tr>
  <?php }?>
  <tr>
			<td colspan="2"><?=$info['page_info_a']?></td>
			<td colspan="2"><?=$info['page_info_b']?></td>
		</tr>
	</table>
	<script type="text/javascript" language="javascript">
function showHideType(name,type_id){
	if(jQuery('#'+name+'_span_'+type_id).is(":hidden")){
		jQuery('#'+name+'_span_'+type_id).show();
		jQuery('#'+name+'_text_'+type_id).hide();
	}else{
		jQuery('#'+name+'_span_'+type_id).hide();
		jQuery('#'+name+'_text_'+type_id).show();
	}
}
function changeTypeName(doc,tags_id,name){
	if(jQuery('#'+name+'_span_'+tags_id).text()!=jQuery('#'+name+'_text_'+tags_id).val()){
		jQuery.post("raiders_tags.php",{ajax:name,tags_id:tags_id,value:doc.value},function(result){
			
		  },'json');
	}
	jQuery('#'+name+'_span_'+tags_id).text(doc.value);
	showHideType(name,tags_id);
	
}
</script>
<?php
require (DIR_WS_INCLUDES . 'application_bottom.php'); 
?>