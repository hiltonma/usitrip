<?php
set_time_limit(0);
require('includes/application_top.php');
require DIR_WS_INCLUDES.'classes/T.class.php';
require DIR_WS_INCLUDES.'classes/Raiders.class.php';
require DIR_WS_INCLUDES.'classes/RaidersTags.class.php';
$r=new Raiders;
if($_POST&&isset($_POST['ajax'])){
	if($_POST['ajax']=='change_inline'){
		$r->changeInline($_POST['article_id'],$_POST['value']);
	}
	echo $value?'已上线':'未上线';
	exit;
}
if(isset($_GET['drop'])&&$_GET['drop']){
	$r->dropArticle($_GET['drop']);
	$mark='删除成功';
}
$rt=new RaidersTags();
$r->setGetType(3);
$info=$r->getPageList();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>攻略文章</title>
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
<div style="background:#00FF99"><?php echo $mark;?></div>
<h1>旅游攻略</h1>
<br /><br />
<form  action="" name="serach" method="get">
		<table width="100%" border="1">
			<tr>
				<td>标题:</td>
				<td><input type="text" name="title" value="<?php echo $_GET['title'];?>" /></td>
				<td>添加人工号:</td>
				<td>
				<input type="text" name="add_user" value="<?php echo $_GET['add_user'];?>"/>				</td>
				<td>状态：<select name="is_show">
      <option value="">请选择重要与否</option>
 	<option value="0" <?php if($_GET['is_show']==0) echo 'selected'?>>未上线</option>
	<option value="1" <?php if($_GET['is_show']==1) echo 'selected'?>>已上线</option>
    </select></td>
				<td><input type="submit" value="SEARCH" /></td>
				<td><input type="button" value="添加"
					onclick="location.href='/admin/raiders_update.php'" /></td>
				<td><input type="button" value="清空搜索结果"
					onclick="location.href='/admin/raiders.php'" /></td>
			</tr>
		</table>
</form>
	<br />
	<br />
	<table width="100%" border="1">
  <tr>
    <td>ID</td>
    <td>标题</td>
    <td>栏目</td>
    <td>来源于</td>
    <td>添加时间</td>
	<td>标签</td>
	<td>状态</td>
	<td>添加人工号</td>
	<td>action</td>
  </tr>
  <?php foreach($info['info'] as $value){?>
  <tr>
    <td><?=$value['article_id']?></td>
    <td><?=$value['article_title']?></td>
    <td><?=$value['type_name']?></td>
    <td><?=$value['article_from']?></td>
    <td><?=$value['add_time']?></td>
	<td><?php foreach($rt->getArticleShow($value['article_id']) as $v){
		echo $v['tags_name'].'  ';
	}?></td>
	<td style="color:"><?=($value['is_show']==1)?'<span style="color:green" id="is_show_'.$value['article_id'].'">已上线</span>':'<span style="color:red" id="is_show_'.$value['article_id'].'">未上线</span>'?></td>
	<td><?=$value['add_user']?></td>
	<td><input type="button" value="删除" onclick="if(confirm('确定要删除吗？')){location.href='?drop=<?=$value['article_id']?>';}"/> <input type="button" value="更新" onclick="location.href='raiders_update.php?article_id=<?=$value['article_id']?>'"/>  <input type="button" value="<?=($value['is_show']==0)?'已上线':'未上线'?>" onclick="changeInline(<?=$value['article_id']?>,<?=($value['is_show']==0)?1:0?>)" /></td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="4"><?=$info['page_info_a']?></td>
    <td colspan="5"><?=$info['page_info_b']?></td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
function changeInline(article_id,value){
jQuery.post("raiders.php",{ajax:'change_inline',article_id:article_id,value:value},function(result){
			//
		  },'json');
		  var show=value==0?'未上线':'已上线';
		  jQuery('#is_show_'+article_id).html(show);
}
</script>
<?php
require (DIR_WS_INCLUDES . 'application_bottom.php'); 
?>