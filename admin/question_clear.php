<?php
  require('includes/application_top.php');
  $startDate = '2011-4-1 0:0:0';
  /*
  *查询指定的未回复问题是否有
  *已经回复的问题有返回true
  */
 $repeadCheck = array();
function isRepeated($row){
	global $startDate ;
	global $repeadCheck ;
	$my_replyed= tep_db_query('SELECT question FROM  tour_question '
		.' WHERE que_replied = 1 AND  date > \''	.$startDate
		.'\' AND customers_email = \''.$row['customers_email'].'\' AND products_id = \''.$row['products_id'].'\'');	
	$org_question = preg_replace("/(\s|\n)+/",'',$row['question']);	
	while($row1 = mysql_fetch_assoc($my_replyed)){
		$row1_question = preg_replace("/(\s|\n)+/",'',$row1['question']);
		if($org_question == $row1_question){
			return true;
		}
	}
	$checkArray = array('question'=>$org_question , 'products_id'=>$row['products_id'] , 'customers_email'=>$row['customers_email']);
	if(in_array($checkArray ,$repeadCheck )){
		return true;
	}else{
		$repeadCheck[] = $checkArray;
		return false;
	}
	
}
$all_not_replayed = tep_db_query("SELECT * FROM tour_question WHERE que_replied = 0 AND date > '".$startDate."'");
$need_delete = array();
while($row = mysql_fetch_assoc($all_not_replayed)){
	if(isRepeated($row)){
		$need_delete[] = $row['que_id'];
	}
}

//$need_d = tep_db_query("SELECT * FROM tour_question WHERE que_id IN (".implode(',',$need_delete).") AND que_replied = 0");
if(!empty($need_delete))
tep_db_query("DELETE FROM tour_question WHERE que_id IN (".implode(',',$need_delete).") AND que_replied = 0");
/*
echo "<ul>";
while($row = mysql_fetch_assoc($need_d )){
	echo '<li><input type="checkbox" name="id[]" value="'.$row['que_id'].'" />'.$row['que_id']." - ".$row['question']."</li>";
}
echo "</ol>";*/

?>
<div style="font-weight:bold;background:blue ;color:#fff;margin:10em 0 auto 0;height;100%;text-align:center;line-height:2em">
<div style="font-size:24px;">Success</div>
<div style="background-color:white;color:black;margin:5px;">Repeat Question List : <?php echo implode(',',$need_delete) ?></div>
<?php echo  count($need_delete) ." items deleted ..."; ?><a href="<?php echo tep_href_link(FILENAME_QUESTION_ANSWERS) ?>" style="color:white">Go back</a>
</div>