<?php
 require_once('includes/configure.php');
 
//处理目录参数
$TcPath = $_GET['TcPath'];
$GetStr = '';
if($TcPath!=''){
	$GetStr .= '&TcPath='.$TcPath; 
}
$products_id = (int)$_GET['products_id'];
if($products_id!=''){
	$GetStr .= '&products_id='.$products_id; 
}
$osCsid = $_GET['osCsid'];
if($osCsid!=''){
	$GetStr .= '&osCsid='.$osCsid; 
}

$customers_id = $_GET['customers_id'];
if($customers_id!=''){
	$GetStr .= '&customers_id='.$customers_id; 
}

$GetStr = preg_replace('/^&/','?',$GetStr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>结伴同游-走四方网</title>
<meta name="Description" content="为了让广大用户更方便快捷地找到结伴同游伙伴，走四方网特别推出了结伴同游信息交流平台！" />
<meta name="Keywords" content="结伴同游" />
<meta name="Reply-to" content="service@usitrip.com" />
<meta name="robots" content="index, follow" />

<base href="<?php echo HTTP_SERVER; ?>" />
<script type="text/javascript">
function left_show_or_hidden(){
	var sidebar_content = document.getElementById('sidebar_content');
	if(sidebar_content.cols=="205, *"){
		sidebar_content.cols="0, *";
	}else{
		sidebar_content.cols="205, *";
	}
}

</script>
</head>

<frameset id="sidebar_content" name="sidebar_content" title="鼠标按住此处可左右拖动" cols="205, *" frameborder="1" border="6" framespacing="5" bordercolor="#93CEF3" onDblClick="left_show_or_hidden();">
    <frame id="NavigetionFrame" name="NavigetionFrame" src="bbs_travel_companion_leftside.php<?=$GetStr?>" scrolling="auto" frameborder="1" />
    <frame id="ContentFrame" name="ContentFrame" src="bbs_travel_companion_rightindex.php<?=$GetStr?>" frameborder="0" />

</frameset>

<noframes>
<body bgcolor="#FFFFFF">
	<p>结伴同游 走四方网。快乐旅行，由此开始！黄石国家公园，大峡谷，尼亚加拉大瀑布……夏威夷，纽约，洛杉矶，旧金山，华盛顿……<?php echo date('Y-m-d');?>。</p>
	<p>为了让广大用户更方便快捷地找到结伴同游伙伴，走四方网特别推出了“结伴同游”信息交流平台！现在，你只需要发起活动将自己的结伴同游旅游需求，游伴要求详细的写入结伴同游活动中，就可以轻通过走四方网的服务网络来帮您完成结伴同游的任务啦！</p>
</body>

</noframes>
</html>
