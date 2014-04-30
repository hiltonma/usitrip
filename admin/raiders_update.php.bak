<?php
set_time_limit(0);
require ('includes/application_top.php');
//引入FCK编辑器
include(DIR_FS_ADMIN.'includes/modules/ckfinder.php');

$info = array ();
require DIR_WS_INCLUDES . 'classes/T.class.php';
require DIR_WS_INCLUDES . 'classes/Raiders.class.php';
require DIR_WS_INCLUDES . 'classes/RaidersCatalog.class.php';
require DIR_WS_INCLUDES . 'classes/RaidersTags.class.php';
// require '../js/ckfinder/core/connector/php/php5/CommandHandler/test.php';
// exit;
$rc = new RaidersCatalog();
$rt = new RaidersTags();
$r = new Raiders();
if(isset($_POST['ajax'])){
	switch($_POST['ajax']){
		case 'drop_tags':
			$r->dropTags($_POST['rct_id'],$_POST['uid']);
			exit;
			break;
		case 'show_tags':
		echo json_encode($rt->showTags(iconv('UTF-8', CHARSET, $_POST['tags_name'])));
		exit;
		break;
	}
	
}
if(isset($_POST['ajax'])&&$_POST['ajax']=='add_article'){
	foreach($_POST as $key=>$value){
		$_POST[$key]=iconv('UTF-8', CHARSET, $value);
	}
}
$tags_arr = array ();
if ($_POST) {
	$data = array ();
	$data['article_title'] = $_POST['title'];
	$data['article_content'] = $_POST['content'];
	$data['article_from'] = $_POST['text_from'];
	$data['article_type'] = $_POST['text_type'];
	$data['is_show']=$_POST['is_show'];
	$data['article_key_words']=$_POST['article_key_words'];
	$data['article_desc']=$_POST['article_desc'];
	$data['add_user'] = tep_get_job_number_from_admin_id($_SESSION['login_id']);
	if($_POST['release_time']!='')
	$data['release_time']=$_POST['release_time'].' 00:00:01';
	$str_tags = substr($_POST['tags_id_hide'], 1);
	$data['tags_id_str'] = $rt->getArticleTags($str_tags).',';
	if(isset($_POST['article_id_hide'])&&$_POST['article_id_hide']){
		$data['update_time'] = date('Y-m-d H:i:s');
		if(isset($_POST['is_show'])&&$_POST['is_show'])
			$data['add_time'] = date('Y-m-d H:i:s');
		$r->update($data,$_POST['article_id_hide']);
		$article_id=$_POST['article_id_hide'];
	}else{
		$data['add_time'] = date('Y-m-d H:i:s');
		$data['update_time'] = date('Y-m-d H:i:s');
		$article_id=$r->addOne($data);
	}
	$rt->changeArticleTags($article_id,$str_tags);
}
if(isset($_POST['ajax'])&&$_POST['ajax']=='add_article'){
	echo $article_id;
	exit;
}elseif($_POST){
	header('Location: raiders_update.php?article_id='.$article_id);
}
if ($_GET && $_GET['article_id']) { //查询文章信息
	$rt->set($_GET['article_id']);
	$tags_arr = $rt->getList();
	$info = $r->getOne($_GET['article_id']);
}
$catalog_option = $rc->getOption($info['article_type']);
if ($_GET && $_GET['tags_key']) {
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />

<title>攻略文章</title>
<link type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/jquery_ui.css" />
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css"
	href="includes/jquery-1.3.2/jquery_ui.css" />
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js" ></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js" ></script>
</head>

<body>
<?php
require (DIR_WS_INCLUDES . 'header.php');

?>
<h1>旅游攻略修改</h1>

	<br />
	<br />
	<form method="post" action="" name="change_raiders" >
	<input type="hidden" id="article_id_hide" name="article_id_hide" value="<?=$_GET['article_id']?>" />
	<table width="80%" border="1" align="center">
		<tr>
			<td width="10%" align="right">标题(title)：</td>
			<td width="90%"><input name="title" id="title" type="text" size="64"
				value="<?=isset($info['article_title'])?$info['article_title']:'';?>" /></td>
		</tr>
		<tr>
			<td width="10%" align="right">DESC：</td>
			<td width="90%"><input name="article_desc" id="article_desc" type="text" size="64"
				value="<?=isset($info['article_desc'])?$info['article_desc']:'';?>" /></td>
		</tr>
		<tr>
			<td width="10%" align="right">Key Words：</td>
			<td width="90%"><input name="article_key_words" id="article_key_words" type="text" size="64"
				value="<?=isset($info['article_key_words'])?$info['article_key_words']:'';?>" /></td>
		</tr>
		<tr>
			<td align="right">内容:<p style="color:#F00">敬告：若要上传图片请把图片名称转成全英文不带空格的名称，否则图片将在一定时间内可能会被删除！正确的文件名：info_image_20131120.jpg，错误的文件名：中文和特殊符号.jpg</p></td>
			<td>
			<!--<textarea id="content"  name="content" cols="128" rows="48" class="xheditor"><?=isset($info['article_content'])?$info['article_content']:'';?></textarea>-->
			<?php 
			$ckeditor->config['Plugins'][] = 'watermark';
			$ckeditor->editor('content', stripslashes($info['article_content']),array('id'=>'content') );?>
			</td>
		</tr>
		<tr >
		<td align="right">状态：</td>
		<td>
		<select name="is_show">
		<option value="0" >未上线</option>
		<option value="1" <?php if($info['is_show']==1) echo 'selected'; ?>>已上线</option>
		<option value="2" <?php if($info['is_show']==2) echo 'selected'; ?>>等待上线</option>
		</select>
		</td>
		</tr>
		<tr>
			<td align="right">自动上线时间:</td>
			<td><input class="textTime" type="text" name="release_time" value="<?=$info['release_time']!='0000-00-00 00:00:00'?date('Y-m-d',strtotime($info['release_time'])):date('Y-m-d')?>" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"/></td>
		</tr>
		<tr>
			<td align="right">来源于：</td>
			<td><input type="text" name="text_from" id="text_from"
				value="<?=isset($info['article_from'])?$info['article_from']:'';?>" /></td>
		</tr>
		<tr>
			<td align="right">栏目：</td>
			<td><select name="text_type" id="text_type">
					<option>请选择类型</option>
		<?php echo $catalog_option;?>
	</select></td>
		</tr>
		<tr style="height:auto">
			<td align="right">标签：</td>
			<td style="height:auto"><span id="tags_show" style="height:auto"><?php if($tags_arr){ $tags_str='';?><?php foreach($tags_arr as $value){$tags_str.=','.$value['tags_id'];?><span style="cursor:pointer;margin-right: 15px;padding: .5em 1em; background:#cad5eb;" onclick="dropTags(this,<?=$value['tags_id']?>)"><?=$value['tags_name']?></span><?php }?> <?php }?></span><br />
			<input type="text"	name="tgs_from" onkeyup="showTags(this)" />
			<input type="hidden" id="tags_id_hide" name="tags_id_hide" value="<?=$tags_str.','?>" />
			<div id="tags_list" style="border: 1px solid #817F82;position: absolute;width:136px;left:375px; background:#CCCCCC"></div>
			</td>
		</tr>
		<tr>
			<td align="right"><input type="reset" value="重置" /></td>
			<td><input type="submit" value="提交" />  <input type="button" value="返回" onclick="location.href='raiders.php'" />  <a href="../raiders_info.php?is_test=1&type_id=<?php echo $info['article_type']?>&article_id=<?php echo $info['article_id']?>" target="_blank">预览</a><!-- <input type="button" onclick="autoAdd()" value="test" /> --> </td>
		</tr>
	</table>
</form>

	<script type="text/javascript">
//$('#content').xheditor({upLinkUrl:"upload.php",upLinkExt:"zip,rar,txt",upImgUrl:"upload.php",upImgExt:"jpg,jpeg,gif,png"});

function dropTags(doc,tags_id){
	if(confirm("确定要删除这个标签吗？")){
	var r_id=jQuery('#article_id_hide').val();
		jQuery(doc).remove();
		jQuery(tags_id_hide).val(jQuery(tags_id_hide).val().replace(','+tags_id+',',','));
	}
}
function showTags(doc){
	if(doc.value){
		jQuery.post("raiders_update.php",{ajax:'show_tags',tags_name:doc.value},function(result){
			var str='';
			for(i=0;i<result.length;i++){
				str+='<li onclick="addTags('+result[i]['tags_id']+",'"+result[i]['tags_name']+"'"+')">'+result[i]['tags_name']+'</li>';
			}
			jQuery("#tags_list").html(str);
			jQuery("#tags_list").show();
		  },'json');
	}
}
function addTags(tags_id,tags_name){
	if(jQuery(tags_id_hide).val().indexOf(','+tags_id+',')<0){
	var span='<span style="cursor:pointer;margin-right: 15px;padding: .5em 1em; background:#cad5eb;" onclick="dropTags(this,'+tags_id+')">'+tags_name+'</span>';
	jQuery("#tags_show").append(span);
	jQuery("#tags_list").hide();
	jQuery(tags_id_hide).val(jQuery(tags_id_hide).val()+tags_id+',');
	}
	
}
setInterval('autoAdd()',60000);
function autoAdd(){
	var title=jQuery("#title").val();
	var article_desc=jQuery("#article_desc").val();
	var article_key_words=jQuery("#article_key_words").val();
	var content=CKEDITOR.instances.content.getData();//jQuery("#content").val();
	var text_from=jQuery("#text_from").val();
	var text_type=jQuery("#text_type").val();
	var tags_hide=jQuery("#tags_id_hide").val();
	var article_id_hide=jQuery("#article_id_hide").val();
	jQuery.post("raiders_update.php",{ajax:'add_article',title:title,content:content,text_from:text_from,text_type:text_type,tags_id_hide:tags_hide,article_id_hide:article_id_hide,article_desc:article_desc,article_key_words:article_key_words},function(result){
			jQuery("#article_id_hide").val(result);
		  },'json');
}
</script>
<?php
require (DIR_WS_INCLUDES . 'application_bottom.php'); 
?>