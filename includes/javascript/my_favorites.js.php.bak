<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--
<?php
}
?>

function RemoveFavorites(f_id) {
	if(typeof(f_id)=="undefined"){
		alert("No f_id");
		return false;
	}else{
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link('ajax_favorites.php', 'ajax=true&action=del_favorites')) ?>");
		url+= '&f_id='+f_id;
		ajax_get_submit(url,"","","");
	}
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>

