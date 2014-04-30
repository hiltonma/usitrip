<?php
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


function CVVPopUpWindow(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=233,screenX=150,screenY=150,top=150,left=150')
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>
