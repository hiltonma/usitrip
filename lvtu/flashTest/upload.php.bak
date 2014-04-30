<?php
if ($_POST['iso_usitrip_upload'] == 'lwkai_upload') {
	file_put_contents('/var/www/html/usitrip/lvtu/tmp/post.txt',print_r($_POST,true));
	file_put_contents('/var/www/html/usitrip/lvtu/tmp/file.txt',print_r($_FILES,true));
	copy($_FILES['Filedata']['tmp_name'],'/var/www/html/usitrip/lvtu/tmp/' . $_POST['Filename']);
	echo 'ok';
}
?>