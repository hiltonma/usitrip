<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />

<?php
if ( file_exists(DIR_FS_INCLUDES . 'header_tags.php') ) {
  require(DIR_FS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE ?></title>
<?php
}
?>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link href="<?php echo TEMPLATE_STYLE;?>" rel="stylesheet" type="text/css" />
<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/page_css/bbs_travel_companion.css'?>" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript" src="spiffyCal/spiffyCal-v2-1-2009-05-11-min.js"></script>
<script type="text/javascript" src="menujs-2008-04-15-min.js"></script>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>usitrip-tabs-2009-06-19.min.js" charset="gb2312"></script>

<?php if($language=='schinese'){?>
<script type="text/javascript" src="big5_gb-min.js"></script>
<?php }else{?>
<script type="text/javascript" src="gb_big5-min.js"></script>
<?php }?>

<script type="text/javascript">
function url_ssl(url){
	var SSL_ = false;
	if(document.URL.search(/^https:\/\//)>-1){
		SSL_ = true;
	}
	var new_url = url;
	if(SSL_==true){
		new_url = url.replace(/^http:\/\//,"https://");
	}else{
		new_url = url.replace(/^https:\/\//,"http://");
	}
	return new_url;
}

//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>

<?php if (isset($javascript) && file_exists(DIR_FS_JAVASCRIPT . basename($javascript))) { require(DIR_FS_JAVASCRIPT . basename($javascript)); } ?>

</head>

<body id="tc">

<!--ajax弹出层的背景-->
<table width="100%" id="bg" class="center_pop_bg" style="display:none;"><tr><td height="1000px">&nbsp;</td></tr></table>


<div class="right_content">
<div id="date_select_layer">
<div id="spiffycalendar" style="z-index:1000;margin-left:-102px;"></div>
</div>     
	 <?php
	 require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/header_travel_bbs.php');
	 require(DIR_FS_CONTENT . $content . '.tpl.php');
	 
	 ?>
     
</div>
<?php
//结伴同游发贴模块
require_once('travel_companion_tpl.php');
?>

<?php
// display only on prod site .
if($_SERVER['HTTP_HOST']=='208.109.123.18' || $_SERVER['HTTP_HOST']=='208.109.123.18'){

	/*google track code*/
	if(strtolower(CHARSET)=='gb2312'){
		$UA_code = 'UA-1565452-1';	//big5
		if(date('Y-m-d') >= '2009-05-30' ){
			$UA_code = 'UA-19590146-1';	//gb2312
		}
	}else{
		$UA_code = 'UA-1565452-1';	//big5
		if(date('Y-m-d') >= '2009-05-30' ){
			$UA_code = 'UA-19590146-1';	//gb2312
		}
	}

	switch ($request_type) {
		case ($request_type != 'SSL'):
		echo '
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
		</script>';
		break;
		case ($request_type == 'SSL'):
		echo '
		<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
		</script>';
		break;
	}
	
	echo '
	<script type="text/javascript">
	_uacct = "'.$UA_code.'";
	urchinTracker();
	</script>';

	//跟踪代码2
	echo "\n".'<script type="text/javascript" src="http://s3.amazonaws.com/new.cetrk.com/pages/scripts/0010/7642.js"> </script>';

}
?>

</body>
</html>
