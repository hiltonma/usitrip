<?php
$logfile = 'test_uploader.log';
$data = 'No file';
if($_FILES){
	$data = '[FILES]'.PHP_EOL.print_r($_FILES, true);
}
if($_POST){
	$data = $_POST;
	$data['type'] = 'POST';
	$data = json_encode($data);
}
if($_GET){
	$data = $_GET;
	$data['type'] = 'GET';
	$data = json_encode($data);
}
file_put_contents($logfile, $data);
echo $data;
?>