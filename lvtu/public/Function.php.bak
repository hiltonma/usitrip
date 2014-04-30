<?php
/**
 * 自动加载类
 * @param string $class_name
 */
function __autoload($class_name) {
	$dirs = array(
			DIR_FS_CONTROLLER,
			DIR_FS_MODULE
	);
	$class_name = str_replace('_', ' ', strtolower($class_name));
	$class_name = ucwords(strtolower($class_name));
	$class_name = str_replace(' ', '_', $class_name);
	foreach( $dirs as $dir ) {
		$file_dir = $dir . $class_name . '.Class.php';

		if(file_exists($file_dir)) {
			require($file_dir);
			break;
		}
		// 添加支持目录调用的功能 add 2013-04-01 16:46
		$temp = str_replace('_', DS, $class_name);
		$file_dir = $dir . $temp . '.Class.php';
		if (file_exists($file_dir)) {
			require ($file_dir);
			break;
		}
	}
}

function echoPre($obj) {
	echo '<pre>';
	print_r($obj);
	echo '</pre>';
}

set_error_handler('lvtu_error');
set_exception_handler('lvtu_exception');
function lvtu_error($errorno,$errstr,$errfile,$errlint){
	if(!(error_reporting() & $errorno)){
		return;
	}
	switch($errorno){
		case E_USER_ERROR: break;
		
	}
}
function lvtu_exception(){
	
}
?>