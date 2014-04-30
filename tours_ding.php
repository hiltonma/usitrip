<?php
require('includes/application_top.php');
if((int)$_GET['tours_experience_id']){
	tep_db_query('update `tours_experience` set tours_ding=tours_ding+1 WHERE tours_experience_id="'.(int)$_GET['tours_experience_id'].'" ');
	
	$sql=tep_db_query('SELECT tours_ding FROM `tours_experience` WHERE tours_experience_id="'.(int)$_GET['tours_experience_id'].'" ');
	$row=tep_db_fetch_array($sql);
	echo $row['tours_ding'];
}
?>