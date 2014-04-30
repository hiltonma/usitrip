<?php
//写smarty模板变量，必须在->display之前引用
if(is_object($smarty)){
	$defined_vars = get_defined_vars();
	foreach ($defined_vars as $key => $val) {
		if(in_array($key, array('_GET', '_POST', '_COOKIE', '_FILES', 'GLOBALS', '_SERVER')) ) {
		}else{
			$smarty->assign($key, ${$key});
		}
	}
}
?>