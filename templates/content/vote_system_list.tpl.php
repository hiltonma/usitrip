<?php echo tep_get_design_body_header(VOTE_SYSTEM_TITLE); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:30px; margin-bottom:20px;">
  <tr>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0e7bb6">
	  <tr>
		<td align="center" class="VoteTitle"><?php echo db_to_html('状态')?></td>
		<td align="center" class="VoteTitle"><?php echo db_to_html('调查项目名')?></td>
		<td align="center" class="VoteTitle"><?php echo db_to_html('调查编号')?></td>
		<td align="center" class="VoteTitle"><?php echo db_to_html('调查有效时间')?></td>
		<td align="center" class="VoteTitle"><?php echo db_to_html('可得积分')?></td>
	  </tr>
	  
	  <?php
	  $do = 0;
	  $to_date = date('Y-m-d');
	  while($vote_rows = tep_db_fetch_array($vote_sql)){
	  	$do++;
		if($do%2==0){ $class = "VoteContentTr2"; }else{ $class = "VoteContentTr";}
		if($to_date>=$vote_rows['v_s_start_date'] && $to_date<=$vote_rows['v_s_end_date']){
			$state_name ='有效';
			$title_name = '<a href="'.tep_href_link('vote_system.php', 'v_s_id='.$vote_rows['v_s_id']).'" class="sp3">'.$vote_rows['v_s_title'].'</a>';
		}elseif($to_date > $vote_rows['v_s_end_date']){
			$state_name ='已过期';
			$class .= " VoteContentTrEnd";
			$title_name = $vote_rows['v_s_title'];

		}elseif($to_date < $vote_rows['v_s_start_date']){
			$state_name ='未开始';
			$class .= " VoteContentTrEnd";
			$title_name = $vote_rows['v_s_title'];
		}
	  ?>
	  <tr class="<?= $class?>">
	    <td height="30" align="center"><?php echo db_to_html($state_name)?></td>
	    <td align="center"><?php echo db_to_html($title_name);?></td>
	    <td align="center"><?php echo str_replace('-','',$vote_rows['v_s_start_date']).$vote_rows['v_s_id']?></td>
	    <td align="center"><?php echo $vote_rows['v_s_start_date'].db_to_html(' 至 ').$vote_rows['v_s_end_date']?></td>
	    <td align="center"><?php echo $vote_rows['v_s_points'];?></td>
	    </tr>
	  <?php }?>
	</table>
	</td>
  </tr>
  <tr>
    <td height="25" class="main"><span style="color:#666666"><?php echo VOTE_SYSTEM_NOTE;?></span></td>
  </tr>
</table>


<?php echo tep_get_design_body_footer();?>