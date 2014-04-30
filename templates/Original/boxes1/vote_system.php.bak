<?php
//只在目录页显示
$vote_system=true;
if($vote_system==true && (int)$_GET['cPath'] ){

	$vote_yellow = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_state=1 AND v_s_start_date <="'.date('Y-m-d').'" AND v_s_end_date >="'.date('Y-m-d').'" Order By v_s_sort Limit 1 ');
	while($row = tep_db_fetch_array($vote_yellow)){

?>
<div>
<a href="<?=tep_href_link('vote_system.php','v_s_id='.(int)$row['v_s_id'])?>"><img style="margin-top:7px;" border="0" alt="<?php echo db_to_html('参与调查 获取积分');?>" src="image/banner_logo/<?php echo $language ?>/diaocha-jifen-banner.jpg"/></a>
</div>

<?php
	}
}
?>