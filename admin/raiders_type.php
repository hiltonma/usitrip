<?php
require('includes/application_top.php');
require DIR_WS_INCLUDES.'classes/T.class.php';
require DIR_WS_INCLUDES.'classes/RaidersCatalog.class.php';
$rc=new RaidersCatalog;
$mark='';
if(isset($_GET['drop'])&&$_GET['drop']){
	if($rc->dropType($_GET['drop'])){
		$mark='<font color="#00CC33">删除成功</font>';
	}else{
		$mark="<font color=red>删除失败！请确认该目录的下级目录为空！改目录下的文章为空</font>";
	}
}
if($_POST&&isset($_POST['parent_id'])){
	$rc->addOne($_POST);
	header('Location: raiders_type.php');
}
if(isset($_POST['ajax'])&&$_POST['ajax']=='change_type'){
	$rc->changeOne(iconv('UTF-8', CHARSET, $_POST['type_name']), $_POST['type_id']);
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>攻略分类</title>
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
<h1>攻略分类</h1>
<br />
<h1><?=$mark?></h1>
<br />

<form method="post" action="" name="add_type">
<table width="60%" border="1">
  <tr>
    <td>上级目录：</td>
    <td><select name="parent_id">
	<option value="0">根目录</option>
	<?=$rc->getBackAddOption()?>
	</select></td>
    <td>目录名称：</td>
    <td><input type="text" name="type_name" /></td>
    <td><input type="submit" value="增加"/></td>
  </tr>
</table>
</form>
<br /><br />
</table><table width="80%" border="1">
  <tr>
    <th>ID</th>
    <th>目录名称</th>
    <th>上级目录</th>
    <th>action</th>
  </tr>
  <tr>
  <?php foreach($rc->getTd() as $key=>$value){?>
    <td><?=$value['type_id']?></td>
    <td><span id="type_span_<?=$value['type_id']?>" onclick="showHideType(<?=$value['type_id']?>)"><?=$value['type_name']?></span><input type="text" id="type_text_<?=$value['type_id']?>"value="<?=$value['type_name']?>" style="display:none" onblur="changeTypeName(this,<?=$value['type_id']?>)"/></td>
    <td><?=$value['parent_name']?></td>
    <td><a href="?drop=<?=$value['type_id']?>">删除</a></td>
    
  </tr>
  <?php }?>
</table>
<script type="text/javascript" language="javascript">
function showHideType(type_id){
	if(jQuery('#type_span_'+type_id).is(":hidden")){
		jQuery('#type_span_'+type_id).show();
		jQuery('#type_text_'+type_id).hide();
	}else{
		jQuery('#type_span_'+type_id).hide();
		jQuery('#type_text_'+type_id).show();
	}
}
function changeTypeName(doc,type_id){
	if(jQuery('#type_span_'+type_id).text()!=jQuery('#type_text_'+type_id).val()){
		jQuery.post("raiders_type.php",{ajax:'change_type',type_id:type_id,type_name:doc.value},function(result){
			
		  },'json');
	}
	jQuery('#type_span_'+type_id).text(doc.value);
	showHideType(type_id);
	
}
</script>
<?php
require (DIR_WS_INCLUDES . 'application_bottom.php'); 
?>