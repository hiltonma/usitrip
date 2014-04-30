<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
file_put_contents('/var/www/html/lwkai/usitrip.1/wwwroot/lvtu/post.txt',print_r($_POST,true));
file_put_contents('/var/www/html/lwkai/usitrip.1/wwwroot/lvtu/file.txt',print_r($_FILES,true));
// Define a destination
$targetFolder = '/var/www/html/lwkai/usitrip.1/wwwroot/lvtu/uploads'; // Relative to the root

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

//if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['myfile']['tmp_name'];
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetFolder,'/') . '/' . $_FILES['myfile']['name'];
	//file_put_contents('/var/www/html/lwkai/usitrip.1/wwwroot/lvtu/file.txt',print_r($_FILES,true));
	if (isset($_FILES['myfile'])) {
		copy($_FILES['myfile']['tmp_name'],'/var/www/html/lwkai/usitrip.1/wwwroot/lvtu/' . $_POST['Filename']);
	} else {
		move_uploaded_file($_FILES['Filedata']['tmp_name'],'/var/www/html/lwkai/usitrip.1/wwwroot/lvtu/' . $_FILES['Filedata']['name']);
	}
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['myfile']['name']);

	
	//if (in_array(strtolower($fileParts['extension']),$fileTypes)) {
		//move_uploaded_file($tempFile,$targetFile);
        //echo '1';
        echo '{"exif":{"Image_Width":0,"Image_height":0,"Pic_Modified_Time":0,"name":"' . $_FILES['Filedata']['name'] . '","image_Width":0,"image_height":0,"pic_Modified_Time":0},"activity":[{"length":611,"width":683,"url":"http://img1.qunarzz.com/wugc/p223/201301/13/d1ccb5b70c84682093835fbb.jpg_255x186_16818bf6.jpg","atType":2,"src":"http://img.qunarzz.com/wugc/p223/201301/13/d1ccb5b70c84682093835fbb.jpg","textContent":true}]}';
	//} else {
	//	echo 'Invalid file type1111111.';
	//}
//}
?>