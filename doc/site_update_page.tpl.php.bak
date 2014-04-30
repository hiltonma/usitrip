<?php
if(basename($_SERVER['PHP_SELF'])!="index.php" && basename($_SERVER['PHP_SELF'])!="404.php" && basename($_SERVER['PHP_SELF'])!="ajax_site_update_page.tpl.php"){
	die("No permission! site_update_page.tpl.php");
}
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header("Content-type: text/html; charset=utf-8");

if($_GET['UniqueIdentifier']=="UpdateCountdown" && $_GET['ajax']=="true" && $_GET["method"]=="get" && $_GET["submit"]=="true"){
	$updated_done_file = dirname(__FILE__).'/updated_done.txt';
	if(file_exists($updated_done_file) && filemtime($updated_done_file) >= filemtime(__FILE__)){
		echo '[SUCCESS]1[/SUCCESS]';
	}
	exit;
}

$max_wait_time = 60; //最大等待时间数
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>服务器维护中。。。</title>
<meta content="八八八|八八八网|八八八旅游|旅游八八八|美东|美西|夏威夷|特色美国游|加拿大|拉丁美洲|欧洲|日本|酒店预订|目的地指南|结伴同游" name="keywords"/>
<meta content="八八八网为您提供美国旅游与加拿大旅游套餐，绝对超值，游遍全美国与加拿大。我们的顾客可以享受种类繁多，内容丰富，度身为您定制的旅游套餐，以及我们优质的服务。" name="description"/>
<style type="text/css">
body,ul,ol,dl,dd,h1,h2,h3,h4,h5,h6,p,form,fieldset,legend,input,textarea,select,button,th,td{ margin:0; padding:0;}
img{border:0;}
body{ background:#91b9f0; font-size:12px; font-family:Tahoma,Arial,Helvetica,sans-serif; color:#111;}
.serverSuspend{ height:650px; margin:0 auto; padding-top:15px; }
.serverSuspend .pic{ height:405px; background:#fff; text-align:center;}
.serverSuspend .pic img{ display:inline-block; }
.serverSuspend p{ width:630px; margin:0 auto; padding-top:20px; line-height:30px; font-size:14px;}
</style>

<script type="text/javascript">
var SiteUpdatedSeconds = 0;
var SiteUpdatedStopSumit = false;
function tmpAjaxCheckFileForUpdateSite(){
	SiteUpdatedSeconds++;
	if(SiteUpdatedSeconds %5 ==0 ){
		var ajaxTmp = false;
		if(window.XMLHttpRequest) {
			 ajaxTmp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) {
			try{
					ajaxTmp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
			try{
					ajaxTmp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
		if (!ajaxTmp) {
			window.alert("不能创建XMLHttpRequest对象实例");
		}
		var httpHead = (document.location.protocol=="https:") ? "https://" : "http://";
		var url = httpHead + "<?= $_SERVER['HTTP_HOST'];?>/ajax_site_update_page.tpl.php?ajax=true&method=get&submit=true&UniqueIdentifier=UpdateCountdown";
		url += "&randnumforajaxaction=" + Math.random();
		ajaxTmp.open("GET", url, true);
		ajaxTmp.send(null); 
		ajaxTmp.onreadystatechange = function() { 
			if (ajaxTmp.readyState == 4 && ajaxTmp.status == 200) { 
				if(ajaxTmp.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){
					SiteUpdatedStopSumit = true;
					window.location = document.URL;
				}
			}
		}
	}
	if(SiteUpdatedStopSumit == false){
		var tmp_val = document.getElementById("SpanNumber").innerHTML;
		if(tmp_val<1){ tmp_val = <?= $max_wait_time;?>;}
		document.getElementById("SpanNumber").innerHTML = tmp_val-1;
		window.setTimeout('tmpAjaxCheckFileForUpdateSite()',1000);
	}
}

</script>

</head>

<body>
<div class="serverSuspend">
    <div class="pic"><img src="/server_suspend.png" alt="服务器维护中。。。" title="服务器维护中。。。"/></div>
    <p>www.888trip.com正在更新中，大约<span id="SpanNumber"><?= $max_wait_time;?></span>秒左右恢复正常，感谢您对八八八网的支持！</p>
</div>
</body>
<script type="text/javascript">
tmpAjaxCheckFileForUpdateSite();
</script>
</html>