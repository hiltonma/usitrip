<?php if(tep_not_null($swf_file)){
	$width_height = getimagesize($maps_file);
	
	$swf_w = $width_height[0];
	$swf_h = $width_height[1];
	if(!(int)$swf_w || !(int)$swf_h ){
		$swf_w = 800;
		$swf_h = 600;
	}
	$swf_w = min(930,$swf_w);
	if($swf_w==930){
		$swf_h = round($swf_w*($width_height[1]/max(1,$width_height[0])),0);
	}
?>
<script src="includes/javascript/swfobject_modified.js" type="text/javascript"></script>
<div style="padding-top:10px;">
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?= $swf_w?>" height="<?= $swf_h?>" align="top" id="FlashID" title="title">
		<param name="movie" value="<?php echo $swf_file;?>" />
		<param name="quality" value="high" />
		<param name="wmode" value="opaque" />
		<param name="swfversion" value="9.0.45.0" />
		<!-- 此 param 标签提示使用 Flash Player 6.0 r65 和更高版本的用户下载最新版本的 Flash Player。如果您不想让用户看到该提示，请将其删除。 -->
		<param name="expressinstall" value="Scripts/expressInstall.swf" />
		<param name="SCALE" value="exactfit" />
		<!-- 下一个对象标签用于非 IE 浏览器。所以使用 IECC 将其从 IE 隐藏。 -->
		<!--[if !IE]>-->
		<object data="<?php echo $swf_file;?>" type="application/x-shockwave-flash" width="<?= $swf_w?>" height="<?= $swf_h?>" align="top">
			<!--<![endif]-->
			<param name="quality" value="high" />
			<param name="wmode" value="opaque" />
			<param name="swfversion" value="9.0.45.0" />
			<param name="expressinstall" value="includes/javascript/expressInstall.swf" />
			<param name="SCALE" value="exactfit" />
			<!-- 浏览器将以下替代内容显示给使用 Flash Player 6.0 和更低版本的用户。 -->
			<div>
				<h4><?php echo db_to_html("此页面上的内容需要较新版本的 Adobe Flash Player。")?></h4>
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" width="112" height="33" /></a></p>
			</div>
			<!--[if !IE]>-->
		</object>
		<!--<![endif]-->
	</object>
</div>
<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
//-->
</script>
<?php
}else{ echo db_to_html("不存在的地图文件！"); };
?>